<?php
/**
 * @package    ClassDefFile
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/**#@+
 * include files
 */
require_once dirname(__FILE__).'/../../Lib.php';
Sgmov_Lib::useView('mlk/Common');
Sgmov_Lib::useForms(array('Error', 'EveSession', 'Eve001In', 'Eve002In'));
Sgmov_Lib::useServices(array('HttpsZipCodeDll'));
/**#@-*/
/**
 * 旅客手荷物受付サービスのお申し込み入力情報をチェックします。
 * @package    View
 * @subpackage EVE
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Eve_CheckInput extends Sgmov_View_Eve_Common {

    /**
     * 都道府県サービス
     * @var Sgmov_Service_Prefecture
     */
    public $_PrefectureService;

    /**
     * イベントサービス
     * @var Sgmov_Service_Event
     */
    protected $_EventService;

    /**
     * イベントサブサービス
     * @var Sgmov_Service_Event
     */
    protected $_EventsubService;

    /**
     * 館サービス
     * @var Sgmov_Service_Building
     */
    protected $_BuildingService;

    /**
     * 郵便番号DLLサービス
     * @var Sgmov_Service_HttpsZipCodeDll
     */
    protected $_HttpsZipCodeDll;

    /**
     * 時間帯サービス
     * @var type
     */
    protected $_TimeService;


    protected $_SocketZipCodeDll;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_PrefectureService                = new Sgmov_Service_Prefecture();
        $this->_EventService                     = new Sgmov_Service_Event();
        $this->_EventsubService                  = new Sgmov_Service_Eventsub();
        $this->_BuildingService                  = new Sgmov_Service_Building();
        $this->_HttpsZipCodeDll                  = new Sgmov_Service_HttpsZipCodeDll();
        $this->_TimeService                      = new Sgmov_Service_Time();

        $this->_SocketZipCodeDll                 = new Sgmov_Service_SocketZipCodeDll();

        parent::__construct();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * セッションの継続を確認
     * </li><li>
     * チケットの確認と破棄
     * </li><li>
     * 入力チェック
     * </li><li>
     * 情報をセッションに保存
     * </li><li>
     * 入力エラー無し
     *   <ol><li>
     *   pcr/confirm へリダイレクト
     *   </li></ol>
     * </li><li>
     * 入力エラー有り
     *   <ol><li>
     *   pcr/input へリダイレクト
     *   </li></ol>
     * </li></ol>
     */
    public function executeInner() {
//Sgmov_Component_Log::debug("############## 705");
//Sgmov_Component_Log::debug($_POST);
        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();

        // DB接続
        $db = Sgmov_Component_DB::getPublic();

        // チケットの確認と破棄
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_EVE001, $this->_getTicket());

        // 入力チェック
        $sessionForm = $session->loadForm(self::FEATURE_ID);

        if (@empty($sessionForm->in)) {
            $sessionForm = new Sgmov_Form_EveSession();
            $sessionForm->in = null;
        }       
        
        $inForm = $this->_createInFormFromPost($_POST, $sessionForm->in);
        
        $this->loadDataToInform($db, $inForm);
        
 
        
        // 搬入出の申込期間チェック
        $this->checkCurrentDateWithInTerm((array)$inForm);

        // 時間帯マスタからデータを取得
        $timeDataList = $this->_TimeService->fetchTimeDataListByClassCd($db, '2');

        foreach ($timeDataList as $timeData) {
            $this->comiket_detail_delivery_timezone[$timeData['cd'] .','. $timeData['name']] = $timeData['name'];
        }

        $errorForm = $this->_validate($inForm, $db);
//Sgmov_Component_Log::debug("############## 110-01");
//Sgmov_Component_Log::debug($_POST);
//Sgmov_Component_Log::debug($inForm);

        // 情報をセッションに保存
        $sessionForm->in = $inForm;
        $sessionForm->error = $errorForm;
        if ($errorForm->hasError()) {
            $sessionForm->status = self::VALIDATION_FAILED;
        } else {
            $sessionForm->status = self::VALIDATION_SUCCEEDED;
        }
        $session->saveForm(self::FEATURE_ID, $sessionForm);


        // リダイレクト処理
        $this->_redirectProc($inForm, $errorForm);
    }
    
    public function loadDataToInform($db, &$inForm) {
        $inForm->office_name = "";
        
        $inForm->comiket_personal_name_sei = $inForm->comiket_staff_sei;
        $inForm->comiket_personal_name_mei = $inForm->comiket_staff_mei;
        
        $inForm->comiket_staff_sei_furi = $inForm->comiket_staff_sei;
        $inForm->comiket_staff_mei_furi = $inForm->comiket_staff_mei;
        
        $code = substr($inForm->comiket_id, 0, 8);

        $dataHachakuten = $this->_HachakutenService->fetchValidHachakutenByCode($db, $code);
        

        $inForm->comiket_zip1 = substr($dataHachakuten['zip'], 0, 3);
        $inForm->comiket_zip2 = substr($dataHachakuten['zip'], 3, 4);
        
        $inForm->comiket_tel = $dataHachakuten['tel'];
        //$inForm->comiket_address = $dataHachakuten['address'];
         $receive = $this->_SocketZipCodeDll->searchByZipCode($dataHachakuten['zip']);
         if (!empty($receive)) {
             $inForm->comiket_pref_cd_sel = $receive['JIS2Code'];
             $comiketAddressTo = $receive['KenName'].$receive['CityName'].$receive['TownName'];
             $inForm->comiket_address = $receive['CityName'].$receive['TownName'];
             if (strpos($dataHachakuten['address'], $inForm->comiket_address) !== false) {
                 $inForm->comiket_building = mb_substr($dataHachakuten['address'], mb_strlen($comiketAddressTo));
             }
         }
        //$inForm->comiket_pref_cd_sel = 27;
        // $inForm->comiket_building = '3丁目3-2';
         
        $inForm->service_hotel_airport_code = '';
        $dataHachakutenTo = [];
        if ($inForm->addressee_type_sel == self::DELIVERY_TYPE_SERVICE) {
            $inForm->sevice_center_sel = filter_input(INPUT_POST, 'sevice_center_sel');
            $dataHachakutenTo = $this->_HachakutenService->fetchHachakutenById($db, $inForm->sevice_center_sel);
        } else if ($inForm->addressee_type_sel == self::DELIVERY_TYPE_HOTEL) {
            $inForm->hotel_sel = filter_input(INPUT_POST, 'hotel_sel');
            $dataHachakutenTo = $this->_HachakutenService->fetchHachakutenById($db, $inForm->hotel_sel);
        } else if ($inForm->addressee_type_sel == self::DELIVERY_TYPE_AIRPORT) {
            $inForm->airport_sel = filter_input(INPUT_POST, 'airport_sel');
            $dataHachakutenTo = $this->_HachakutenService->fetchHachakutenById($db, $inForm->airport_sel);
        }
        
        $inForm->service_hotel_airport_code = @$dataHachakutenTo['hachakuten_shikibetu_cd'];
        $inForm->comiket_address_to = @$dataHachakutenTo['address'];
        $inForm->comiket_tel_to = @$dataHachakutenTo['tel'];
        
        $inForm->comiket_detail_delivery_date_min = filter_input(INPUT_POST, 'comiket_detail_delivery_date_min');
        $inForm->comiket_detail_delivery_date_hour = filter_input(INPUT_POST, 'comiket_detail_delivery_date_hour');
        $inForm->comiket_detail_delivery_date = filter_input(INPUT_POST, 'comiket_detail_delivery_date');
        
        $inForm->delivery_date_store = filter_input(INPUT_POST, 'delivery_date_store');
        
    }
    /**
     *
     * @param type $inForm
     * @param type $errorForm
     */
    public function _redirectProc($inForm, $errorForm) {
        if ($errorForm->hasError()) {
            Sgmov_Component_Redirect::redirectPublicSsl('/mlk/input?tagId='.$inForm->comiket_id);
        } else {  // 法人
            Sgmov_Component_Redirect::redirectPublicSsl('/mlk/credit_card');
        }
    }

    /**
     * POST値からチケットを取得します。
     * チケットが存在しない場合は空文字列を返します。
     * @return string チケット文字列
     */
    public function _getTicket() {
        if (!isset($_POST['ticket'])) {
            $ticket = '';
        } else {
            $ticket = $_POST['ticket'];
        }
        return $ticket;
    }

    /**
     * POST情報から入力フォームを生成します。
     *
     * 全ての値は正規化されてフォームに設定されます。
     *
     * @param array $post ポスト情報
     * @param Sgmov_Form_Pcr002In $creditCardForm 入力フォーム
     * @return Sgmov_Form_Pcr001In 入力フォーム
     */
    public function _createInFormFromPost($post, $creditCardForm) {

Sgmov_Component_Log::info("######## CheckInput._createInFormFromPost ######");
Sgmov_Component_Log::info($_POST);
Sgmov_Component_Log::info($_SERVER['HTTP_USER_AGENT']);


        $inForm = new Sgmov_Form_Eve002In();
        $creditCardForm = (array)$creditCardForm;
        // チケット
        $inForm->ticket = filter_input(INPUT_POST, 'ticket');

Sgmov_Component_Log::debug($_POST);
        
        $inForm->comiket_id = filter_input(INPUT_POST, 'comiket_id');
        $inForm->comiket_div = filter_input(INPUT_POST, 'comiket_div');
        $inForm->event_sel = filter_input(INPUT_POST, 'event_sel');
        $inForm->eventsub_sel = filter_input(INPUT_POST, 'eventsub_sel');
//        $inForm->event_place = filter_input(INPUT_POST, 'event_place');
//        $inForm->event_term_fr = filter_input(INPUT_POST, 'event_term_fr');
//        $inForm->event_term_to = filter_input(INPUT_POST, 'event_term_to');
        $inForm->eventsub_zip = filter_input(INPUT_POST, 'eventsub_zip');
        $inForm->eventsub_address = filter_input(INPUT_POST, 'eventsub_address');
        $inForm->eventsub_term_fr = filter_input(INPUT_POST, 'eventsub_term_fr');
        $inForm->eventsub_term_to = filter_input(INPUT_POST, 'eventsub_term_to');

        $inForm->comiket_customer_cd = filter_input(INPUT_POST, 'comiket_customer_cd');
        $inForm->customer_search_btn = filter_input(INPUT_POST, 'customer_search_btn');
        
        

        $inForm->comiket_zip1 = mb_convert_kana(filter_input(INPUT_POST, 'comiket_zip1'), 'rnask', 'UTF-8');
        $inForm->comiket_zip2 = mb_convert_kana(filter_input(INPUT_POST, 'comiket_zip2'), 'rnask', 'UTF-8');
        $inForm->comiket_pref_cd_sel = filter_input(INPUT_POST, 'comiket_pref_cd_sel');
        $inForm->comiket_address = mb_convert_kana(filter_input(INPUT_POST, 'comiket_address'), 'RNASKV', 'UTF-8');
        $inForm->comiket_building = mb_convert_kana(filter_input(INPUT_POST, 'comiket_building'), 'RNASKV', 'UTF-8');

        $inForm->comiket_tel = @str_replace(array('-', 'ー', '−', '―', '‐', 'ｰ'), "-", mb_convert_kana(filter_input(INPUT_POST, 'comiket_tel'), 'rnask', 'UTF-8'));
        $inForm->comiket_mail = mb_convert_kana(filter_input(INPUT_POST, 'comiket_mail'), 'rnask', 'UTF-8');
        $inForm->comiket_mail_retype = mb_convert_kana(filter_input(INPUT_POST, 'comiket_mail_retype'), 'rnask', 'UTF-8');

        $inForm->comiket_booth_name = mb_convert_kana(filter_input(INPUT_POST, 'comiket_booth_name'), 'RNASKV', 'UTF-8');
        $inForm->building_name_sel = filter_input(INPUT_POST, 'building_name_sel');
        $inForm->building_name = filter_input(INPUT_POST, 'building_name');
        $inForm->building_booth_position_sel = filter_input(INPUT_POST, 'building_booth_position_sel');
        $inForm->building_booth_position = filter_input(INPUT_POST, 'building_booth_position');

        $inForm->comiket_booth_num = filter_input(INPUT_POST, 'comiket_booth_num');

        $inForm->comiket_staff_sei = mb_convert_kana(filter_input(INPUT_POST, 'comiket_staff_sei'), 'RNASKV', 'UTF-8');
        $inForm->comiket_staff_mei = mb_convert_kana(filter_input(INPUT_POST, 'comiket_staff_mei'), 'RNASKV', 'UTF-8');
        $inForm->comiket_staff_sei_furi = mb_convert_kana(filter_input(INPUT_POST, 'comiket_staff_sei_furi'), 'RNASKV', 'UTF-8');
        $inForm->comiket_staff_mei_furi = mb_convert_kana(filter_input(INPUT_POST, 'comiket_staff_mei_furi'), 'RNASKV', 'UTF-8');
        $inForm->comiket_staff_tel = @str_replace(array('-', 'ー', '−', '―', '‐', 'ｰ'), "-", mb_convert_kana(filter_input(INPUT_POST, 'comiket_staff_tel'), 'rnask', 'UTF-8'));

        $inForm->comiket_detail_type_sel = self::COMIKET_DETAIL_OFUKU_TYPE_SEL;// filter_input(INPUT_POST, 'comiket_detail_type_sel');
        $inForm->comiket_detail_outbound_name = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_outbound_name'), 'RNASKV', 'UTF-8');
        $inForm->comiket_detail_outbound_zip1 = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_outbound_zip1'), 'rnask', 'UTF-8');
        $inForm->comiket_detail_outbound_zip2 = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_outbound_zip2'), 'rnask', 'UTF-8');
        $inForm->comiket_detail_outbound_pref_cd_sel = filter_input(INPUT_POST, 'comiket_detail_outbound_pref_cd_sel');
        $inForm->comiket_detail_outbound_address = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_outbound_address'), 'RNASKV', 'UTF-8');
        $inForm->comiket_detail_outbound_building = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_outbound_building'), 'RNASKV', 'UTF-8');
        $inForm->comiket_detail_outbound_tel = @str_replace(array('-', 'ー', '−', '―', '‐', 'ｰ'), "-", mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_outbound_tel'), 'rnask', 'UTF-8'));
        $inForm->comiket_detail_outbound_collect_date_year_sel = filter_input(INPUT_POST, 'comiket_detail_outbound_collect_date_year_sel');
        $inForm->comiket_detail_outbound_collect_date_month_sel = filter_input(INPUT_POST, 'comiket_detail_outbound_collect_date_month_sel');
        $inForm->comiket_detail_outbound_collect_date_day_sel = filter_input(INPUT_POST, 'comiket_detail_outbound_collect_date_day_sel');
        $inForm->comiket_detail_outbound_collect_time_sel = filter_input(INPUT_POST, 'comiket_detail_outbound_collect_time_sel');
        $inForm->comiket_detail_outbound_delivery_date_year_sel = filter_input(INPUT_POST, 'comiket_detail_outbound_delivery_date_year_sel');
        $inForm->comiket_detail_outbound_delivery_date_month_sel = filter_input(INPUT_POST, 'comiket_detail_outbound_delivery_date_month_sel');
        $inForm->comiket_detail_outbound_delivery_date_day_sel = filter_input(INPUT_POST, 'comiket_detail_outbound_delivery_date_day_sel');
        $inForm->comiket_detail_outbound_delivery_time_sel = filter_input(INPUT_POST, 'comiket_detail_outbound_delivery_time_sel');
        $inForm->comiket_detail_outbound_service_sel = filter_input(INPUT_POST, 'comiket_detail_outbound_service_sel');
//Sgmov_Component_Log::debug("########################## 701");
//Sgmov_Component_Log::debug($_POST);
        $inForm->comiket_box_outbound_num_ary = $this->cstm_filter_input_array(INPUT_POST, 'comiket_box_outbound_num_ary', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY, 'rnask');
        $inForm->comiket_cargo_outbound_num_sel = filter_input(INPUT_POST, 'comiket_cargo_outbound_num_sel');
//        $inForm->comiket_cargo_outbound_num_ary = $this->cstm_filter_input_array(INPUT_POST, 'comiket_cargo_outbound_num_ary', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY, 'rnask');
        $inForm->comiket_charter_outbound_num_ary = $this->cstm_filter_input_array(INPUT_POST, 'comiket_charter_outbound_num_ary', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY, 'rnask');
//        $inForm->comiket_detail_outbound_note = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_outbound_note'), 'RNASKV', 'UTF-8');
        $inForm->comiket_detail_outbound_note1 = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_outbound_note1'), 'RNASKV', 'UTF-8');
        $inForm->comiket_detail_outbound_note2 = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_outbound_note2'), 'RNASKV', 'UTF-8');
        $inForm->comiket_detail_outbound_note3 = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_outbound_note3'), 'RNASKV', 'UTF-8');
        $inForm->comiket_detail_outbound_note4 = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_outbound_note4'), 'RNASKV', 'UTF-8');


        $inForm->comiket_detail_inbound_name = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_inbound_name'), 'RNASKV', 'UTF-8');
        $inForm->comiket_detail_inbound_zip1 = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_inbound_zip1'), 'rnask', 'UTF-8');
        $inForm->comiket_detail_inbound_zip2 = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_inbound_zip2'), 'rnask', 'UTF-8');
        $inForm->comiket_detail_inbound_pref_cd_sel = filter_input(INPUT_POST, 'comiket_detail_inbound_pref_cd_sel');
        $inForm->comiket_detail_inbound_address = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_inbound_address'), 'RNASKV', 'UTF-8');
        $inForm->comiket_detail_inbound_building = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_inbound_building'), 'RNASKV', 'UTF-8');
        $inForm->comiket_detail_inbound_tel = @str_replace(array('-', 'ー', '−', '―', '‐', 'ｰ'), "-", mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_inbound_tel'), 'rnask', 'UTF-8'));
        $inForm->comiket_detail_inbound_collect_date_year_sel = filter_input(INPUT_POST, 'comiket_detail_inbound_collect_date_year_sel');
        $inForm->comiket_detail_inbound_collect_date_month_sel = filter_input(INPUT_POST, 'comiket_detail_inbound_collect_date_month_sel');
        $inForm->comiket_detail_inbound_collect_date_day_sel = filter_input(INPUT_POST, 'comiket_detail_inbound_collect_date_day_sel');
        $inForm->comiket_detail_inbound_collect_time_sel = filter_input(INPUT_POST, 'comiket_detail_inbound_collect_time_sel');
        
        ////////////////////////////////////////////////////////////////////////////////
        // ホテル対応用 (日付を設定する)
        ////////////////////////////////////////////////////////////////////////////////

        $inForm->comiket_detail_inbound_delivery_date_cd_sel = filter_input(INPUT_POST, 'comiket_detail_inbound_delivery_date_cd_sel');
        $inForm->comiket_detail_inbound_delivery_date_year_sel = @substr($inForm->comiket_detail_inbound_delivery_date_cd_sel, 0, 4);
        $inForm->comiket_detail_inbound_delivery_date_month_sel = @substr($inForm->comiket_detail_inbound_delivery_date_cd_sel, 4, 2);
        $inForm->comiket_detail_inbound_delivery_date_day_sel = @substr($inForm->comiket_detail_inbound_delivery_date_cd_sel, 6, 2);
        
        
        $inForm->comiket_detail_inbound_delivery_time_sel = filter_input(INPUT_POST, 'comiket_detail_inbound_delivery_time_sel');


        ////////////////////////////////////////////////////////////////////////////////
        // ホテル対応用 (日付を設定する)
        ////////////////////////////////////////////////////////////////////////////////
        
        $inForm->parcel_room = filter_input(INPUT_POST, 'parcel_room');
        
        ////////////////////////////////////////////////////////////////////////////////
        
        $inForm->comiket_detail_inbound_service_sel = filter_input(INPUT_POST, 'comiket_detail_inbound_service_sel');
        
        $inForm->comiket_box_inbound_num_ary = filter_input(INPUT_POST, 'comiket_box_inbound_num_ary') ? [filter_input(INPUT_POST, 'comiket_box_inbound_num_ary') => 1] : []; 
        
        $inForm->comiket_cargo_inbound_num_sel = filter_input(INPUT_POST, 'comiket_cargo_inbound_num_sel');
//        $inForm->comiket_cargo_inbound_num_ary = $this->cstm_filter_input_array(INPUT_POST, 'comiket_cargo_inbound_num_ary', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY, 'rnask');
        $inForm->comiket_charter_inbound_num_ary = $this->cstm_filter_input_array(INPUT_POST, 'comiket_charter_inbound_num_ary', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY, 'rnask');
        $inForm->comiket_detail_inbound_note = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_inbound_note'), 'RNASKV', 'UTF-8');
        $inForm->comiket_detail_inbound_note1 = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_inbound_note1'), 'RNASKV', 'UTF-8');
        

        $inForm->input_mode = filter_input(INPUT_POST, 'input_mode');

        $inForm->addressee_type_sel = filter_input(INPUT_POST, 'addressee_type_sel');

        // 復路-お届け可能日
        $inForm->hid_comiket_detail_inbound_delivery_date_from = filter_input(INPUT_POST, 'hid_comiket-detail-inbound-delivery-date-from');
        $inForm->hid_comiket_detail_inbound_delivery_date_to = filter_input(INPUT_POST, 'hid_comiket-detail-inbound-delivery-date-to');

        // 往路-お預かり可能日
        $inForm->hid_comiket_detail_outbound_collect_date_from = filter_input(INPUT_POST, 'hid_comiket-detail-outbound-collect-date-from');
        $inForm->hid_comiket_detail_outbound_collect_date_to = filter_input(INPUT_POST, 'hid_comiket-detail-outbound-collect-date-to');



/////////////////////////////////////////////////////////////////////////////////////////////////////////
// 支払方法
/////////////////////////////////////////////////////////////////////////////////////////////////////////
        $inForm->comiket_payment_method_cd_sel = filter_input(INPUT_POST, 'comiket_payment_method_cd_sel');
        $inForm->comiket_convenience_store_cd_sel = filter_input(INPUT_POST, 'comiket_convenience_store_cd_sel');
        //$calcDataInfo = $this->calcAmount((array)$inForm); 
        //$inForm->delivery_charge = @$calcDataInfo['aountTax'];
        $inForm->delivery_charge = null;//@$calcDataInfo['amount_tax'];

        $inForm->card_number = isset($creditCardForm['card_number']) ? $creditCardForm['card_number'] : null;
        $inForm->card_expire_month_cd_sel = isset($creditCardForm['card_expire_month_cd_sel']) ? $creditCardForm['card_expire_month_cd_sel'] : null;
        $inForm->card_expire_year_cd_sel = isset($creditCardForm['card_expire_year_cd_sel']) ? $creditCardForm['card_expire_year_cd_sel'] : null;
        $inForm->security_cd = isset($creditCardForm['security_cd']) ? $creditCardForm['security_cd'] : null;
        $inForm->is_conf_rule = true;
        
        ////////////////////////////////////////////////////////////////////////////////
        
        return $inForm;
    }

    /**
     *
     * @param type $type
     * @param type $variable_name
     * @param type $filter
     * @param type $options
     * @return type
     */
    private function cstm_filter_input_array($type, $variable_name, $filter = FILTER_DEFAULT, $options = null, $mbConvKanaOpt = NULL) {
        $res = filter_input($type, $variable_name, $filter, $options);

        if(empty($res)) {
            return array();
        }

        if(is_array($res) && !empty($mbConvKanaOpt)) {
            $resultList = array();
            foreach($res as $key => $val) {
                $resultList[$key] = mb_convert_kana($val, $mbConvKanaOpt, 'UTF-8');
            }
            $res = $resultList;
        }

        return $res;
    }


    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_Pcr001In $inForm 入力フォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($inForm, $db) {
        $errorForm = new Sgmov_Form_Error();

        // 都道府県のリストを取得しておく
        //$prefectures = $this->_PrefectureService->fetchPrefectures($db);
        $services = $this->_HachakutenService->fetchHachakutenByType($db, self::DELIVERY_TYPE_SERVICE);
        $hotels = $this->_HachakutenService->fetchHachakutenByType($db, self::DELIVERY_TYPE_HOTEL);
        $airports = $this->_HachakutenService->fetchHachakutenByType($db, self::DELIVERY_TYPE_AIRPORT);
        $eventArray = $this->_EventService->fetchEventListWithinTerm($db);
        $eventsubArray = $this->_EventsubService->fetchEventsubListWithinTermByEventId($db, $inForm->event_sel);
        $eventsubInfo = NULL;
        if(!empty($inForm->eventsub_sel)) {
            $eventsubInfo = $this->_EventsubService->fetchEventsubByEventsubId($db, $inForm->eventsub_sel);
        }

        if (filter_input(INPUT_POST, 'hid_timezone_flg') == '1') {
            $errorForm->addError('event_sel', '選択のイベントは受付時間を超過しています。');
        }

        if(empty($inForm->event_sel)) {
            $errorForm->addError('event_sel', '出展イベントが選択されていません。');
            return $errorForm;
        }

        // 出展イベント
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->event_sel)->isSelected()->isIn($eventArray['ids']);
        if (!$v->isValid()) {
            $errorForm->addError('event_sel', '出展イベント' . $v->getResultMessageTop());
        }

        // 出展イベントサブ
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->eventsub_sel)->isSelected()->isIn($eventsubArray['ids']);
        if (!$v->isValid()) {
            $errorForm->addError('event_sel', '出展イベントサブ' . $v->getResultMessageTop());
        }
        
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        $code = substr($inForm->comiket_id, 0, 8);
        $dataHachakuten = $this->_HachakutenService->fetchValidHachakutenByCode($db, $code);
        
        $dataHachakuten['delivery_date_store'] = '';
        $currentTime = date('His');
        if (!empty($dataHachakuten['input_end_time'])) {
            if ($currentTime >= $dataHachakuten['input_end_time']."00") {
                $dataHachakuten['delivery_date_store'] = date('Y/m/d', strtotime("+1 day", strtotime(date('Y-m-d'))));
            } else {
                $dataHachakuten['delivery_date_store'] = date('Y/m/d');
            }
        }
        if ($inForm->delivery_date_store !== $dataHachakuten['delivery_date_store']) {
            $errorForm->addError('delivery_date_store', "お預かり/お届け日が、{$inForm->delivery_date_store}での申込受付が終了しました。申込用QRコードを再読み込んでください。");
        }
        if ($inForm->addressee_type_sel == self::DELIVERY_TYPE_AIRPORT && !empty($inForm->airport_sel)) {
            if (!empty($inForm->comiket_detail_delivery_date) && $inForm->comiket_detail_delivery_date_hour != '' && $inForm->comiket_detail_delivery_date_min != '') {
                $dataAirportHachakuten = $this->_HachakutenService->fetchAirportHachakutenById($db, $inForm->airport_sel);
                //airport_flight_end_time
                if (!empty($dataAirportHachakuten['airport_flight_end_time'])) {
                    $dateSendDeliver = new DateTime($inForm->delivery_date_store);
                    $dateSendDeliverSelect = new DateTime($inForm->comiket_detail_delivery_date);
                    
                    $dateSendDeliverStr = $dateSendDeliver->format('Y-m-d');
                    $dateSendDeliverSelectStr = $dateSendDeliverSelect->format('Y-m-d');
                    
                    //format HH:MM
                    $flightEndTimeFormat = substr($dataAirportHachakuten['airport_flight_end_time'], 0, 2).':'.substr($dataAirportHachakuten['airport_flight_end_time'], 2, 2);
                    $deliveryDateHour = sprintf("%02d", $inForm->comiket_detail_delivery_date_hour);
                    $deliveryDateMin = sprintf("%02d", $inForm->comiket_detail_delivery_date_min);
                    
                    //画面の選択したフライ日がお預かり/お届け日と同じ時、入力した時間がマスタの時間の前の場合、エラーとなります。
                    if ($dateSendDeliverStr == $dateSendDeliverSelectStr &&
                            $deliveryDateHour.$deliveryDateMin < $dataAirportHachakuten['airport_flight_end_time']) {
                        $dateErrorStr = $dateSendDeliver->format('Y/m/d');
                        $errorForm->addError('comiket_detail_delivery_date', "搭乗日時は{$dateErrorStr} {$flightEndTimeFormat}以降に設定してください。");
                    } else {
                        $dateDeliveryAdd = new DateTime($inForm->delivery_date_store);
                        $dateDeliveryAdd->modify('+1 day');

                        //$flightEndTimeAdd2h = intval($dataAirportHachakuten['airport_flight_end_time']) + intval(self::ADDTION_FLIGHT_END);
                        //$flightEndTimeAdd2h = sprintf("%04d", $flightEndTimeAdd2h);
                        $flightEndTime = str_replace(":" ,"" ,self::ADDTION_FLIGHT_END);
                        //$flightEndTimeAdd2hFormat = substr($flightEndTimeAdd2h, 0, 2).':'.substr($flightEndTimeAdd2h, 2, 2);
                        $flightEndTimeAdd2hFormat = $dateDeliveryAdd->format('Y/m/d').' '.self::ADDTION_FLIGHT_END;

                        if ($deliveryDateHour.$deliveryDateMin > $flightEndTime) {
                            if ($dateDeliveryAdd->format('Y-m-d') == $inForm->comiket_detail_delivery_date) {
                                $errorForm->addError('comiket_detail_delivery_date', "搭乗日時は{$flightEndTimeAdd2hFormat}までを設定してください。");
                            }
                        }
                    }
                }   
            }
        }
        
        
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->addressee_type_sel)->isSelected()->isIn(array_keys($this->address_type_lbls));
        if (!$v->isValid()) {
            $errorForm->addError('addressee_type_sel', 'お届け先の選択' . $v->getResultMessageTop());
        } else {
            if ($inForm->addressee_type_sel == self::DELIVERY_TYPE_SERVICE) {
                $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->sevice_center_sel)->isSelected()->isIn($services['ids']);
                if (!$v->isValid()) {
                    $errorForm->addError('addressee_type_sel', 'お届け先の選択' . $v->getResultMessageTop());
                }
            } else if ($inForm->addressee_type_sel == self::DELIVERY_TYPE_HOTEL) {
                $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->hotel_sel)->isSelected()->isIn($hotels['ids']);
                if (!$v->isValid()) {
                    $errorForm->addError('addressee_type_sel', 'お届け先の選択' . $v->getResultMessageTop());
                }
            } else if ($inForm->addressee_type_sel == self::DELIVERY_TYPE_AIRPORT) {
                $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->airport_sel)->isSelected()->isIn($airports['ids']);
                if (!$v->isValid()) {
                    $errorForm->addError('addressee_type_sel', 'お届け先の選択' . $v->getResultMessageTop());
                }
            }
        }

        // お客様名
//        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_personal_name_sei.$inForm->comiket_personal_name_mei)->isNotEmpty()->isLengthLessThanOrEqualTo(16)->
//                isNotHalfWidthKana()->isWebSystemNg();
//        if (!$v->isValid()) {
//            $errorForm->addError('comiket_personal_name-seimei', 'お客様名' . $v->getResultMessageTop());
//        }
        
//        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_staff_sei.$inForm->comiket_staff_mei)->isNotEmpty()->isLengthLessThanOrEqualTo(16)->
//                isNotHalfWidthKana()->isWebSystemNg();
//        if (!$v->isValid()) {
//            $errorForm->addError('comiket_staff-seimei', '名前' . $v->getResultMessageTop());
//        }
        
        // 担当者名-姓
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_staff_sei)->isNotEmpty()->isLengthLessThanOrEqualTo(8)->isNotHalfWidthKana()->isWebSystemNg();
        if (!$v->isValid()) {
                $errorForm->addError('comiket_staff-seimei', '名前-姓' . $v->getResultMessageTop());
        }



        // 担当者名-名
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_staff_mei)->isNotEmpty()->isLengthLessThanOrEqualTo(8)->isNotHalfWidthKana()->isWebSystemNg();
        if (!$v->isValid()) {
                $errorForm->addError('comiket_staff-seimei', '名前-名' . $v->getResultMessageTop());
        }

        // 郵便番号
        // 郵便番号(最後に存在確認をするので別名でバリデータを作成) 必須チェック
        $zipV = Sgmov_Component_Validator::createZipValidator($inForm->comiket_zip1, $inForm->comiket_zip2)->isZipCode();
        if (!$zipV->isValid()) {
            $errorForm->addError('comiket_zip', '郵便番号' . $zipV->getResultMessageTop());
        }
        
        $comiketTel = @str_replace(array('-', 'ー', '−', '―', '‐', 'ｰ'), "", $inForm->comiket_staff_tel);
        $v = Sgmov_Component_Validator::createSingleValueValidator($comiketTel)->isNotEmpty()->isPhoneHyphen()->isLengthLessThanOrEqualToForPhone();
        if (!$v->isValid()) {
            $errorForm->addError('comiket_staff_tel', '電話番号' . $v->getResultMessageTop());
        } else {
            $v = Sgmov_Component_Validator::createSingleValueValidator($comiketTel)->isLengthMoreThanOrEqualTo(1)->isLengthLessThanOrEqualTo(12);
            if (!$v->isValid()) {
                $errorForm->addError('comiket_staff_tel', '電話番号' . $v->getResultMessageTop());
            }
        }

        // メールアドレス 必須チェック 100文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_mail)->isNotEmpty()->isMail()->isLengthLessThanOrEqualTo(100)->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('comiket_mail', 'メールアドレス' . $v->getResultMessageTop());
        }
        // メールアドレス確認 必須チェック 100文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_mail_retype)->isNotEmpty()->isMail()->isLengthLessThanOrEqualTo(100)->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('comiket_mail_retype', 'メールアドレス確認' . $v->getResultMessageTop());
        }
        $this->_checkPaymentMethod($inForm, $errorForm);
       
        if (empty($inForm->comiket_box_inbound_num_ary)) {
            $errorForm->addError('comiket_box_num', 'サイズを入力してください。');
        }
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_detail_inbound_note1)->isLengthLessThanOrEqualTo(16)->
                isNotHalfWidthKana()->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('comiket_detail_inbound_note1', '備考' . $v->getResultMessageTop());
        }
        if ($inForm->addressee_type_sel == self::DELIVERY_TYPE_AIRPORT) {
            
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_detail_delivery_date)->isSelected();
            if (!$v->isValid()) {
                $errorForm->addError('comiket_detail_delivery_date', '搭乗日時' . $v->getResultMessageTop());
            }
            
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_detail_delivery_date_hour)->isSelected();
            if (!$v->isValid()) {
                $errorForm->addError('comiket_detail_delivery_date', '搭乗日時' . $v->getResultMessageTop());
            }
            
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_detail_delivery_date_min)->isSelected();
            if (!$v->isValid()) {
                $errorForm->addError('comiket_detail_delivery_date', '搭乗日時' . $v->getResultMessageTop());
            }

            
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_detail_inbound_note)->isNotEmpty()->isLengthLessThanOrEqualTo(16)->
                    isNotHalfWidthKana()->isWebSystemNg();
            if (!$v->isValid()) {
                $errorForm->addError('comiket_detail_inbound_note', '便名' . $v->getResultMessageTop());
            }
        }
        if (!$errorForm->hasError()) {
            $v = Sgmov_Component_Validator::createMultipleValueValidator(array($inForm->comiket_mail, $inForm->comiket_mail_retype))->isStringComparison();
            if (!$v->isValid()) {
                $errorForm->addError('comiket_mail_retype', 'メールアドレス確認' . $v->getResultMessageTop());
            }
        }
        
        return $errorForm;
    }

    /**
     *
     * @param type $inForm
     * @param type $errorForm
     */
    public function _checkPaymentMethod($inForm, &$errorForm) {
        if($inForm->comiket_div == self::COMIKET_DEV_INDIVIDUA) { // 個人
            // お支払方法 値範囲チェック
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_payment_method_cd_sel)->isIn(array_keys($this->payment_method_lbls));
            if (!$v->isValid()) {
                $errorForm->addError('payment_method', 'お支払い方法' . $v->getResultMessageTop());
            }
            
            
            // お支払方法 必須チェック
            $v->isSelected();
            if (!$v->isValid()) {
                $errorForm->addError('payment_method', 'お支払い方法' . $v->getResultMessageTop());
            }
           
        }
    }
}