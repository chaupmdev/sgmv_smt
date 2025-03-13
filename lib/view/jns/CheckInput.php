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
// イベント識別子(URLのpath)はマジックナンバーで記述せずこの変数で記述してください
$dirDiv=Sgmov_Lib::setDirDiv(dirname(__FILE__));
Sgmov_Lib::useView($dirDiv.'/Common');
Sgmov_Lib::useForms(array('Error', 'EveSession', 'Eve001In', 'Eve002In'));
Sgmov_Lib::useServices(array('HttpsZipCodeDll', 'OutBoundCollectCal'));
/**#@-*/
/**
 * 旅客手荷物受付サービスのお申し込み入力情報をチェックします。
 * @package    View
 * @subpackage DSN
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Dsn_CheckInput extends Sgmov_View_Dsn_Common {

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

    /**
     * 郵便番号DLLサービス
     * @var Sgmov_Service_SocketZipCodeDll
     */
    protected $_SocketZipCodeDll;
    
    /**
     * 宅配サービス
     * @var Sgmov_Service_Box
     */
    protected $_BoxService;

    // 識別子
    protected $_DirDiv;

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
        $this->_BoxService                      = new Sgmov_Service_Box();

        $this->_SocketZipCodeDll                 = new Sgmov_Service_SocketZipCodeDll();
        $this->_OutBoundCollectCal               = new Sgmov_Service_OutBoundCollectCal();

        // 識別子のセット
        $this->_DirDiv = Sgmov_Lib::setDirDiv(dirname(__FILE__));

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
        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();

        // DB接続
        $db = Sgmov_Component_DB::getPublic();

        // チケットの確認と破棄
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_DSN001, $this->_getTicket());

        // 入力チェック
        $sessionForm = $session->loadForm(self::FEATURE_ID);

        if (empty($sessionForm)) {
            $sessionForm = new Sgmov_Form_EveSession();
            $sessionForm->in = null;
        }
        
        $inForm = $this->_createInFormFromPost($_POST, $sessionForm->in);

        // 搬入出の申込期間チェック
        $this->checkCurrentDateWithInTerm((array)$inForm);

        // 時間帯マスタからデータを取得
        $timeDataList = $this->_TimeService->fetchTimeDataList($db);

        foreach ($timeDataList as $timeData) {
            $this->comiket_detail_delivery_timezone[$timeData['cd'] .','. $timeData['name']] = $timeData['name'];
        }

        // バリデーション
        $errorForm = $this->_validate($inForm, $db);

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

    /**
     * リダイレクト先設定
     * @param type $inForm
     * @param type $errorForm
     */
    public function _redirectProc($inForm, $errorForm) {
        if ($errorForm->hasError()) {
            Sgmov_Component_Redirect::redirectPublicSsl('/'.$this->_DirDiv.'/input');
        } else if($inForm->comiket_div == self::COMIKET_DEV_BUSINESS) {  // 法人
            Sgmov_Component_Redirect::redirectPublicSsl('/'.$this->_DirDiv.'/confirm');
        }
        
        // 個人の場合は、クレジット・コンビニ支払で表示画面切り替え
        switch ($inForm->comiket_payment_method_cd_sel) {
            case '1': // コンビニ
                Sgmov_Component_Redirect::redirectPublicSsl('/'.$this->_DirDiv.'/confirm');
                break;
            case '2': // クレジット
                Sgmov_Component_Redirect::redirectPublicSsl('/'.$this->_DirDiv.'/credit_card');
                break;
            case '3': // 電子マネー
                Sgmov_Component_Redirect::redirectPublicSsl('/'.$this->_DirDiv.'/confirm');
                break;
            case '4': // コンビニ後払い
                Sgmov_Component_Redirect::redirectPublicSsl('/'.$this->_DirDiv.'/confirm');
                break;
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
        
        // DB接続
        $db = Sgmov_Component_DB::getPublic();

        $inForm = new Sgmov_Form_Eve002In();
        $creditCardForm = (array)$creditCardForm;
        // チケット
        $inForm->ticket = filter_input(INPUT_POST, 'ticket');


        /** イベント情報 **/
        $inForm->event_sel = filter_input(INPUT_POST, 'event_sel');
        $inForm->eventsub_sel = filter_input(INPUT_POST, 'eventsub_sel');
        $inForm->eventsub_zip = filter_input(INPUT_POST, 'eventsub_zip');
        $inForm->eventsub_address = filter_input(INPUT_POST, 'eventsub_address');
        $inForm->eventsub_term_fr = filter_input(INPUT_POST, 'eventsub_term_fr');
        $inForm->eventsub_term_to = filter_input(INPUT_POST, 'eventsub_term_to');

        /** コミケ申込データ **/
        $inForm->comiket_id = filter_input(INPUT_POST, 'comiket_id');
        $inForm->comiket_div = filter_input(INPUT_POST, 'comiket_div');
        $inForm->comiket_customer_cd = filter_input(INPUT_POST, 'comiket_customer_cd');
        $inForm->customer_search_btn = filter_input(INPUT_POST, 'customer_search_btn');
        $inForm->office_name = mb_convert_kana(filter_input(INPUT_POST, 'office_name'), 'RNASKV', 'UTF-8');
        $inForm->comiket_personal_name_sei = mb_convert_kana(filter_input(INPUT_POST, 'comiket_personal_name_sei'), 'RNASKV', 'UTF-8');
        $inForm->comiket_personal_name_mei = mb_convert_kana(filter_input(INPUT_POST, 'comiket_personal_name_mei'), 'RNASKV', 'UTF-8');
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
        $buildingNameInfo = $this->_BuildingService->fetchBuildingById($db, $inForm->building_booth_position_sel);
        $inForm->building_name_sel = @$buildingNameInfo['cd'];
        $inForm->comiket_booth_num = @filter_input(INPUT_POST, 'comiket_booth_num');
        $inForm->comiket_staff_sei = mb_convert_kana(filter_input(INPUT_POST, 'comiket_staff_sei'), 'RNASKV', 'UTF-8');
        $inForm->comiket_staff_mei = mb_convert_kana(filter_input(INPUT_POST, 'comiket_staff_mei'), 'RNASKV', 'UTF-8');
        $inForm->comiket_staff_sei_furi = mb_convert_kana(filter_input(INPUT_POST, 'comiket_staff_sei_furi'), 'RNASKV', 'UTF-8');
        $inForm->comiket_staff_mei_furi = mb_convert_kana(filter_input(INPUT_POST, 'comiket_staff_mei_furi'), 'RNASKV', 'UTF-8');
        $inForm->comiket_staff_tel = @str_replace(array('-', 'ー', '−', '―', '‐', 'ｰ'), "-", mb_convert_kana(filter_input(INPUT_POST, 'comiket_staff_tel'), 'rnask', 'UTF-8'));


        /** コミケ申込明細データ **/
        // 搬入
        $inForm->comiket_detail_type_sel = filter_input(INPUT_POST, 'comiket_detail_type_sel');
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

        $inForm->comiket_box_outbound_num_ary = $this->cstm_filter_input_array(INPUT_POST, 'comiket_box_outbound_num_ary', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY, 'rnask');
        $inForm->comiket_cargo_outbound_num_sel = filter_input(INPUT_POST, 'comiket_cargo_outbound_num_sel');

        $inForm->comiket_charter_outbound_num_ary = $this->cstm_filter_input_array(INPUT_POST, 'comiket_charter_outbound_num_ary', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY, 'rnask');
        $inForm->comiket_detail_outbound_note1 = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_outbound_note1'), 'RNASKV', 'UTF-8');
        $inForm->comiket_detail_outbound_note2 = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_outbound_note2'), 'RNASKV', 'UTF-8');
        $inForm->comiket_detail_outbound_note3 = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_outbound_note3'), 'RNASKV', 'UTF-8');
        $inForm->comiket_detail_outbound_note4 = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_outbound_note4'), 'RNASKV', 'UTF-8');


        /** コミケ申込明細データ **/
        // 搬出
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
        $inForm->comiket_detail_inbound_delivery_date_year_sel = filter_input(INPUT_POST, 'comiket_detail_inbound_delivery_date_year_sel');
        $inForm->comiket_detail_inbound_delivery_date_month_sel = filter_input(INPUT_POST, 'comiket_detail_inbound_delivery_date_month_sel');
        $inForm->comiket_detail_inbound_delivery_date_day_sel = filter_input(INPUT_POST, 'comiket_detail_inbound_delivery_date_day_sel');
        $inForm->comiket_detail_inbound_delivery_time_sel = filter_input(INPUT_POST, 'comiket_detail_inbound_delivery_time_sel');
        $inForm->comiket_detail_inbound_service_sel = filter_input(INPUT_POST, 'comiket_detail_inbound_service_sel');
        $inForm->comiket_box_inbound_num_ary = $this->cstm_filter_input_array(INPUT_POST, 'comiket_box_inbound_num_ary', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY, 'rnask');
        $inForm->comiket_cargo_inbound_num_sel = filter_input(INPUT_POST, 'comiket_cargo_inbound_num_sel');
        $inForm->comiket_charter_inbound_num_ary = $this->cstm_filter_input_array(INPUT_POST, 'comiket_charter_inbound_num_ary', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY, 'rnask');
//        $inForm->comiket_detail_inbound_note = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_inbound_note'), 'RNASKV', 'UTF-8');
        $inForm->comiket_detail_inbound_note1 = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_inbound_note1'), 'RNASKV', 'UTF-8');
        $inForm->comiket_detail_inbound_note2 = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_inbound_note2'), 'RNASKV', 'UTF-8');
        $inForm->comiket_detail_inbound_note3 = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_inbound_note3'), 'RNASKV', 'UTF-8');
        $inForm->comiket_detail_inbound_note4 = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_inbound_note4'), 'RNASKV', 'UTF-8');


        $inForm->input_mode = filter_input(INPUT_POST, 'input_mode');

        // 復路-お届け可能日
        $inForm->hid_comiket_detail_inbound_delivery_date_from = filter_input(INPUT_POST, 'hid_comiket-detail-inbound-delivery-date-from');
        $inForm->hid_comiket_detail_inbound_delivery_date_to = filter_input(INPUT_POST, 'hid_comiket-detail-inbound-delivery-date-to');

        // 往路-お預かり可能日
        $inForm->hid_comiket_detail_outbound_collect_date_from = filter_input(INPUT_POST, 'hid_comiket-detail-outbound-collect-date-from');
        $inForm->hid_comiket_detail_outbound_collect_date_to = filter_input(INPUT_POST, 'hid_comiket-detail-outbound-collect-date-to');
        
        
        /////////////////////////////////////////////////////////////////////////////////////////////////////////
        // 支払方法
        /////////////////////////////////////////////////////////////////////////////////////////////////////////
        $inForm->comiket_convenience_store_cd_sel = filter_input(INPUT_POST, 'comiket_convenience_store_cd_sel');
        $inForm->comiket_payment_method_cd_sel = filter_input(INPUT_POST, 'comiket_payment_method_cd_sel');//
        $calcDataInfoData = $this->calcEveryKindData((array)$inForm);
        $calcDataInfo = $calcDataInfoData["treeData"];
        $inForm->delivery_charge = @$calcDataInfo['amount_tax'];

        if (@!empty($creditCardForm)) {
            $inForm->card_number = @$creditCardForm['card_number'];
            $inForm->card_expire_month_cd_sel = @$creditCardForm['card_expire_month_cd_sel'];
            $inForm->card_expire_year_cd_sel = @$creditCardForm['card_expire_year_cd_sel'];
            $inForm->security_cd = @$creditCardForm['security_cd'];
        }

        return $inForm;
    }

    /**
     * $typeで指定した入力値を取得する
     * @param type $type：filter_inputメソッドの第1引数に準ずる
     * @param type $variable_name
     * @param type $filter
     * @param type $options
     * @return type
     */
    protected function cstm_filter_input_array($type, $variable_name, $filter = FILTER_DEFAULT, $options = null, $mbConvKanaOpt = NULL) {
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
     * @param Sgmov_Form_Eve001In $inForm 入力フォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($inForm, $db) {
        $errorForm = new Sgmov_Form_Error();

        // 都道府県のリストを取得しておく
        $prefectures = $this->_PrefectureService->fetchPrefectures($db);
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

        // 識別チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_div)->isSelected()->isIn(array_keys($this->comiket_div_lbls));
        if (!$v->isValid()) {
            $errorForm->addError('comiket_div', '識別' . $v->getResultMessageTop());
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

        // 顧客コード
        // 法人の場合チェック / 個人の場合は入力なし
        if($inForm->comiket_div == self::COMIKET_DEV_BUSINESS) { // 法人
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_customer_cd)->isNotEmpty()->isHalfWidthAlphaNumericCharacters()->isLengthLessThanOrEqualTo(12);
            if (!$v->isValid()) {
                $errorForm->addError('comiket_customer_cd', '顧客コード' . $v->getResultMessageTop());
            }

            // 顧客名
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->office_name)->isNotEmpty()->isLengthLessThanOrEqualTo(16)->
                    isNotHalfWidthKana()->isWebSystemNg();
            if (!$v->isValid()) {
                $errorForm->addError('office_name', '顧客名' . $v->getResultMessageTop());
            }

        } else { // 個人

            // お名前 姓
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_personal_name_sei)->isNotEmpty()->isLengthLessThanOrEqualTo(8)->
                    isNotHalfWidthKana()->isWebSystemNg();
            if (!$v->isValid()) {
                $errorForm->addError('comiket_personal_name-seimei', 'お名前' . $v->getResultMessageTop());
            }

            // お名前 名 （法人の場合もあるため、必須チェックは外す）
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_personal_name_mei)->isNotEmpty()->isLengthLessThanOrEqualTo(8)->
                    isNotHalfWidthKana()->isWebSystemNg();
            if (!$v->isValid()) {
                $errorForm->addError('comiket_personal_name-seimei', 'お名前' . $v->getResultMessageTop());
            }
        }

        // 郵便番号
        // 郵便番号(最後に存在確認をするので別名でバリデータを作成) 必須チェック
        $zipV = Sgmov_Component_Validator::createZipValidator($inForm->comiket_zip1, $inForm->comiket_zip2)->isNotEmpty()->isZipCode();
        if (!$zipV->isValid()) {
            $errorForm->addError('comiket_zip', '郵便番号' . $zipV->getResultMessageTop());
        }

        // 都道府県
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_pref_cd_sel)->isSelected()->isIn($prefectures['ids']);
        if (!$v->isValid()) {
            $errorForm->addError('comiket_pref', '都道府県' . $v->getResultMessageTop());
        }

        // 市区町村 必須チェック 40文字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_address)->isNotEmpty()->isLengthLessThanOrEqualTo(14)->
                isNotHalfWidthKana()->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('comiket_address', '市区町村' . $v->getResultMessageTop());
        } else {
            $prefName = @$prefectures["names"][$prefectures["ids"][$inForm->comiket_pref_cd_sel]];
            if (strpos($inForm->comiket_address, $prefName) !== false) {
                $errorForm->addError('comiket_address', '市区町村には都道府県名は入力しないで下さい。');
            }
        }

        // 番地・建物名・部屋番号 必須チェック 40文字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_building)->isNotEmpty()->isLengthLessThanOrEqualTo(30)->
                isNotHalfWidthKana()->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('comiket_building', '番地・建物名・部屋番号' . $v->getResultMessageTop());
        } else {
            $prefName = @$prefectures["names"][$prefectures["ids"][$inForm->comiket_pref_cd_sel]];
            if (strpos($inForm->comiket_building, $prefName) !== false) {
                $errorForm->addError('comiket_building', '番地・建物名・部屋番号には都道府県名は入力しないで下さい。');
            }
        }

        // 電話番号 必須チェック 型チェック WEBシステムNG文字チェック
        $comiketTel = @str_replace(array('-', 'ー', '−', '―', '‐', 'ｰ'), "", $inForm->comiket_tel);
        //GiapLN imp ticket #SMT6-381 2022/12/29
        $v = Sgmov_Component_Validator::createSingleValueValidator($comiketTel)->isNotEmpty()->isPhoneHyphen()->isLengthLessThanOrEqualToForPhone();
        if (!$v->isValid()) {
            $errorForm->addError('comiket_tel', '電話番号' . $v->getResultMessageTop());
        } else {
            $v = Sgmov_Component_Validator::createSingleValueValidator($comiketTel)->isLengthMoreThanOrEqualTo(8)->isLengthLessThanOrEqualTo(12);
            if (!$v->isValid()) {
                $errorForm->addError('comiket_tel', '電話番号の数値部分' . $v->getResultMessageTop());
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

        // ブース名 必須チェック 16文字チェック
        if(!empty($eventsubInfo) && $eventsubInfo['booth_display'] ==  '1') {
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_booth_name)->isNotEmpty()->isLengthLessThanOrEqualTo(16)->isWebSystemNg();
            
            if (!$v->isValid()) {
                $errorForm->addError('comiket_booth_name', 'ブース名' . $v->getResultMessageTop());
            }
        }

//        if(!empty($eventsubInfo) && $eventsubInfo['building_display'] == '1') {
//            // 館名
//            $buildingNameInfoAry = $this->_BuildingService->fetchBuildingNameByEventsubId($db, $inForm->eventsub_sel);
//            if ($inForm->event_sel != '2') {
//                $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->building_name_sel)->isSelected()->isIn($buildingNameInfoAry['ids']);
//                if (!$v->isValid()) {
//                    $errorForm->addError('building_name_sel', 'ブースNO' . $v->getResultMessageTop());
//                }
//            }
//
//            if (!empty($inForm->building_name_sel)) {
//                $checkFlg = FALSE;
//                foreach($buildingNameInfoAry["list"] as $key => $val) {
//                    if($val["cd"] == $inForm->building_name_sel) {
//                        $v = Sgmov_Component_Validator::createSingleValueValidator($val["name"])->isLengthLessThanOrEqualTo(16);
//                        if(!$v->isValid()) {
//                            $errorForm->addError('building_name_sel', 'ブースNO' . $v->getResultMessageTop());
//                        }
//                        $checkFlg = TRUE;
//                        break;
//                    }
//                }
//
//                if(!$checkFlg) {
//                    $errorForm->addError('building_name_sel', '選択ブースNOに該当するデータが存在しません。');
//                }
//            }
//
//            // ブース位置
//            // ブース位置の未設定はエラーにしない
//            $buildingBoothPostionInfoAry = $this->_BuildingService->fetchBuildingByEventId($db, $inForm->eventsub_sel);
//            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->building_booth_position_sel)->isNotEmpty()->isSelected()->isIn($buildingBoothPostionInfoAry['ids']);
//            if (!$v->isValid()) {
//                    $errorForm->addError('building_name_sel', 'ブースNO' . $v->getResultMessageTop());
//            } 
//
//
//            // ブース番号-テキスト
//            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_booth_num)->isNotEmpty()->isLengthLessThanOrEqualTo(4)->isInteger(0)->isNotHalfWidthKana()->isWebSystemNg();
//            if (!$v->isValid()) {
//                $errorForm->addError('building_name_sel', 'ブースNO' . $v->getResultMessageTop());
//            } 
//
//        }

        // 担当者名-姓
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_staff_sei)->isNotEmpty()->isLengthLessThanOrEqualTo(8)->isNotHalfWidthKana()->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('comiket_staff_seimei', '当日の担当者名' . $v->getResultMessageTop());
        } 

        // 担当者名-名
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_staff_mei)->isNotEmpty()->isLengthLessThanOrEqualTo(8)->isNotHalfWidthKana()->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('comiket_staff_seimei', '当日の担当者名' . $v->getResultMessageTop());
        } 

        // 担当者名-姓-フリガナ
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_staff_sei_furi)->isNotEmpty()->isLengthLessThanOrEqualTo(8)->isAlphaCharactersOrFullWidthSquareJapaneseSyllabaryCharacters()->isNotHalfWidthKana()->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('comiket_staff_seimei_furi', '当日の担当者名（フリガナ）' . $v->getResultMessageTop());
        }
        
        // 担当者名-名-フリガナ
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_staff_mei_furi)->isNotEmpty()->isLengthLessThanOrEqualTo(8)->isAlphaCharactersOrFullWidthSquareJapaneseSyllabaryCharacters()->isNotHalfWidthKana()->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('comiket_staff_seimei_furi', '当日の担当者名（フリガナ）' . $v->getResultMessageTop());
        }

        // 担当者電話番号 必須チェック 型チェック WEBシステムNG文字チェック
        $comiketStaffTell = @str_replace(array('-', 'ー', '−', '―', '‐', 'ｰ'), "", $inForm->comiket_staff_tel);
        //GiapLN imp ticket #SMT6-381 2022/12/29
        $v = Sgmov_Component_Validator::createSingleValueValidator($comiketStaffTell)->isNotEmpty()->isPhoneHyphen()->isLengthLessThanOrEqualToForPhone();
        if (!$v->isValid()) {
            $errorForm->addError('comiket_staff_tel', '担当者電話番号' . $v->getResultMessageTop());
        } else {
            $v = Sgmov_Component_Validator::createSingleValueValidator($comiketStaffTell)->isLengthMoreThanOrEqualTo(8)->isLengthLessThanOrEqualTo(12);
            if (!$v->isValid()) {
                $errorForm->addError('comiket_staff_tel', '担当者電話番号の数値部分' . $v->getResultMessageTop());
            }
        }

        // 往復選択
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_detail_type_sel)->isSelected()->isIn(array_keys($this->comiket_detail_type_lbls));
        if (!$v->isValid()) {
            $errorForm->addError('comiket_detail_type_sel', '往復選択' . $v->getResultMessageTop());
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // 搬入
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $zipV = NULL;
        if($inForm->comiket_detail_type_sel == "1" || $inForm->comiket_detail_type_sel == "3") { // 搬入の場合
            // 搬入-集荷先名
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_detail_outbound_name)->isNotEmpty()->isLengthLessThanOrEqualTo(32)->
                    isNotHalfWidthKana()->isWebSystemNg();
            if (!$v->isValid()) {
                $errorForm->addError('comiket_detail_outbound_name', '搬入-集荷先名' . $v->getResultMessageTop());
            }

            // 搬入-集荷先郵便番号
            // 郵便番号(最後に存在確認をするので別名でバリデータを作成) 必須チェック
            $zipV = Sgmov_Component_Validator::createZipValidator($inForm->comiket_detail_outbound_zip1, $inForm->comiket_detail_outbound_zip2)->isNotEmpty()->isZipCode();
            if (!$zipV->isValid()) {
                $errorForm->addError('comiket_detail_outbound_zip', '搬入-集荷先郵便番号' . $zipV->getResultMessageTop());
            }

            // 搬入-集荷先都道府県
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_detail_outbound_pref_cd_sel)->isSelected()->isIn($prefectures['ids']);
            if (!$v->isValid()) {
                $errorForm->addError('comiket_detail_outbound_pref', '搬入-集荷先都道府県' . $v->getResultMessageTop());
            }

            if(($inForm->comiket_detail_outbound_pref_cd_sel == '47' && $inForm->comiket_detail_outbound_service_sel == '2')) {
                $errorForm->addError('comiket_detail_outbound_pref', '搬入-本イベントでの出荷・配達不可地域となります。');
            }

            // 搬入-集荷先市区町村 必須チェック 40文字チェック WEBシステムNG文字チェック
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_detail_outbound_address)->isNotEmpty()->isLengthLessThanOrEqualTo(14)->
                    isNotHalfWidthKana()->isWebSystemNg();
            if (!$v->isValid()) {
                $errorForm->addError('comiket_detail_outbound_address', '搬入-集荷先市区町村' . $v->getResultMessageTop());
            } else {
                $prefName = @$prefectures["names"][$prefectures["ids"][$inForm->comiket_detail_outbound_pref_cd_sel]];
                if (strpos($inForm->comiket_detail_outbound_address, $prefName) !== false) {
                    $errorForm->addError('comiket_detail_outbound_address', '搬入-集荷先市区町村には都道府県名は入力しないで下さい。');
                }
            }
            
            // 搬入-集荷先番地・建物名・部屋番号 必須チェック 40文字チェック WEBシステムNG文字チェック
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_detail_outbound_building)->isNotEmpty()->isLengthLessThanOrEqualTo(30)->
                    isNotHalfWidthKana()->isWebSystemNg();
            if (!$v->isValid()) {
                $errorForm->addError('comiket_detail_outbound_building', '搬入-集荷先番地・建物名・部屋番号' . $v->getResultMessageTop());
            } else {
                $prefName = @$prefectures["names"][$prefectures["ids"][$inForm->comiket_detail_outbound_pref_cd_sel]];
                if (strpos($inForm->comiket_detail_outbound_building, $prefName) !== false) {
                    $errorForm->addError('comiket_detail_outbound_building', '搬入-集荷先番地・建物名・部屋番号には都道府県名は入力しないで下さい。');
                }
            }

            // 搬入-集荷先TEL 必須チェック 型チェック WEBシステムNG文字チェック
            $comiektDetailOutboundTel = @str_replace(array('-', 'ー', '−', '―', '‐', 'ｰ'), "", $inForm->comiket_detail_outbound_tel);
            //GiapLN imp ticket #SMT6-381 2022/12/29
            $v = Sgmov_Component_Validator::createSingleValueValidator($comiektDetailOutboundTel)->isNotEmpty()->isPhoneHyphen()->isLengthLessThanOrEqualToForPhone();
            if (!$v->isValid()) {
                $errorForm->addError('comiket_detail_outbound_tel', '搬入-集荷先TEL' . $v->getResultMessageTop());
            } else {
                $v = Sgmov_Component_Validator::createSingleValueValidator($comiektDetailOutboundTel)->isLengthMoreThanOrEqualTo(8)->isLengthLessThanOrEqualTo(12);
                if (!$v->isValid()) {
                    $errorForm->addError('comiket_detail_outbound_tel', '搬入-集荷先TELの数値部分' . $v->getResultMessageTop());
                }
            }

            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            // 搬入/搬出、個人/法人 、日付指定の有無チェック
            if($this->checkColAndDelDate("outbound", $inForm->comiket_div, $inForm->comiket_detail_outbound_service_sel, $eventsubInfo)) {

                $isOutboundCollectDateCheck = true;

                // 受付時間が終了しているときは（jsの非同期通信でチェックしている）、以下2項目に '1900-01-01' が入る
                if ($inForm->hid_comiket_detail_outbound_collect_date_from == '1900-01-01'
                        && $inForm->hid_comiket_detail_outbound_collect_date_to == '1900-01-01') {
                    $errorForm->addError('comiket_detail_outbound_collect_date', '搬入-お預かり日時:受付時間は終了しました。');
                    $isOutboundCollectDateCheck = false;
                } else {
                    // 本日より締切日が大きいの場合は、受付終了しました。
                    $chakuJis2 = substr($eventsubInfo['jis5cd'], 0, 2);
                    $currentDate = new DateTime();
                    $currentDate->format('Y-m-d H:i:s');
                    $outBoundCollectCalList = $this->_OutBoundCollectCal->fetchOutBoundCollectCalByHaChaku($db, $inForm->eventsub_sel, $inForm->comiket_pref_cd_sel, $chakuJis2);
                    if (@!empty($outBoundCollectCalList)) {
                        $arrivalDate = new DateTime($outBoundCollectCalList["arrival_date"]);
                        $arrivalDate->format('Y-m-d H:i:s');
                        if ($currentDate > $arrivalDate) {
                            $errorForm->addError('comiket_detail_outbound_collect_date', '搬入-お預かり日時:受付時間は終了しました。');
                            $isOutboundCollectDateCheck = false;
                        }
                    }
                }


                if ($isOutboundCollectDateCheck) {
                    // 搬入-お預かり日時 必須チェック
                    $v = Sgmov_Component_Validator::createDateValidator($inForm->comiket_detail_outbound_collect_date_year_sel,
                                $inForm->comiket_detail_outbound_collect_date_month_sel, $inForm->comiket_detail_outbound_collect_date_day_sel)->isNotEmpty();
                    if (!$v->isValid()) {
                        $errorForm->addError('comiket_detail_outbound_collect_date', '搬入-お預かり日時' . $v->getResultMessageTop());
                    }

                    // 搬入-お預かり日時 範囲チェック
                    if (empty($inForm->comiket_detail_outbound_collect_date_year_sel)
                            || empty($inForm->comiket_detail_outbound_collect_date_month_sel)
                            || empty($inForm->comiket_detail_outbound_collect_date_day_sel)
                            || ($inForm->comiket_detail_outbound_collect_time_sel != "00" && empty($inForm->comiket_detail_outbound_collect_time_sel))
                            ) {
                        $max = null;
                        $max_year  = null;
                        $max_month = null;
                        $max_day   = null;
                    } else {
                        $eventsubInfo = $this->_EventsubService->fetchEventsubByEventsubId($db, $inForm->eventsub_sel);
                        // 搬入-お預かり日終了日
                        $date = new DateTime($inForm->hid_comiket_detail_outbound_collect_date_to);

                        $max_year  = intval($date->format('Y'));
                        $max_month = intval($date->format('m'));
                        $max_day   = intval($date->format('d'));
                        $max  = intval($date->format('U'));
                        $formatDateEnd = $date->format('Y/m/d');

                        // 搬入-お預かり日開始日
                        $date = new DateTime($inForm->hid_comiket_detail_outbound_collect_date_from);
                        $min  = intval($date->format('U'));
                        $formatDateSt = $date->format('Y/m/d');

                        $current_date = new DateTime('tomorrow');
                        $current_time = intval($current_date->format('U'));
                        // 開始日がカラか開始日が明日より前の場合
                        // この条件 $min < $current_time は常に通る
                        if (empty($min) || $min < $current_time) {
                            $min = $current_time;
                        }

                        $v->isSelected()->isDate($min, $max);
                        if (!$v->isValid()) {
                            $formatDateSt = date('Y/m/d', $min);
                            // TODO:文字列の日付で比較はおかしい
                            // 開始日と終了日が一致：この条件でチェックしている意味がわからない
                            if($formatDateSt == $formatDateEnd) {
                                $errorForm->addError('comiket_detail_outbound_collect_date', "搬入-お預かり日時は、{$formatDateSt}を入力してください。");
                            } elseif ($formatDateSt < $formatDateEnd) {
                                $errorForm->addError('comiket_detail_outbound_collect_date', "搬入-お預かり日時は、{$formatDateSt}～{$formatDateEnd}までの日付を入力してください。");
                            } elseif ($formatDateSt > $formatDateEnd) {
                                $errorForm->addError('comiket_detail_outbound_collect_date', "搬入-お預かり日時は、受付時間が終了しました。");
                            }
                        }
                    }

                    // 搬入/搬出、個人/法人 、時間指定の有無チェック
                    if($this->checkColAndDelTime("outbound", $inForm->comiket_div, $inForm->comiket_detail_outbound_service_sel, $eventsubInfo)) {
                        // 搬入-お預かり日時-時間帯 必須チェック
                        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_detail_outbound_collect_time_sel)->isSelected()->
                                isIn(array_keys($this->comiket_detail_time_lbls));
                        $is_valid = $v->isValid();
                        if (!$is_valid) {
                            $errorForm->addError('comiket_detail_outbound_collect_date', '搬入-お預かり日時-時間帯' . $v->getResultMessageTop());
                        }

                        // 搬入-お預かり日時-時間帯 最終日チェック
                        $comiket_detail_outbound_collect_time_cds = array_keys($this->comiket_detail_time_lbls);
                        if ($max_year === intval($inForm->comiket_detail_outbound_collect_date_year_sel)
                                && $max_month === intval($inForm->comiket_detail_outbound_collect_date_month_sel)
                                && $max_day === intval($inForm->comiket_detail_outbound_collect_date_day_sel)) {
                            // array_popで時間帯リスト(配列)の最後をとりだしている
                            array_pop($comiket_detail_outbound_collect_time_cds);
                        }
                        $v->isIn($comiket_detail_outbound_collect_time_cds);
                        if ($is_valid && !$v->isValid()) {
                            $errorForm->addError('comiket_detail_outbound_collect_date', '搬入-集荷可能期間の最終日のため、18時～20時は選択できません。');
                        }
                    }
                }
            }

            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            // 搬入-引渡し日 必須チェック
            $v = Sgmov_Component_Validator::createDateValidator($inForm->comiket_detail_outbound_delivery_date_year_sel,
                        $inForm->comiket_detail_outbound_delivery_date_month_sel, $inForm->comiket_detail_outbound_delivery_date_day_sel)->isNotEmpty();
            if (!$v->isValid()) {
                $errorForm->addError('comiket_detail_outbound_delivery_date', '搬入-引渡し日' . $v->getResultMessageTop());
            }

            // 搬入-お届け日時 範囲チェック
            if (empty($inForm->comiket_detail_outbound_delivery_date_year_sel)
                    || empty($inForm->comiket_detail_outbound_delivery_date_month_sel)
                    || empty($inForm->comiket_detail_outbound_delivery_date_day_sel)
                    ) {
                $max = null;
                $max_year  = null;
                $max_month = null;
                $max_day   = null;
            } else {
                $eventsubInfo = $this->_EventsubService->fetchEventsubByEventsubId($db, $inForm->eventsub_sel);
                $dateSt = @new DateTime($eventsubInfo["out_bound_loading_fr"]);
                $dateEnd = @new DateTime($eventsubInfo["out_bound_loading_to"]);
                $min = intval($dateSt->format('U'));
                $max = intval($dateEnd->format('U'));

                $formatDateSt = $dateSt->format('Y/m/d');
                $formatDateEnd = $dateEnd->format('Y/m/d');

                $v->isSelected()->isDate($min, $max);
                if (!$v->isValid()) {
                    if ($formatDateSt < $formatDateEnd) {
                        $errorForm->addError('comiket_detail_outbound_delivery_date', "搬入-引渡し日は、{$formatDateSt}～{$formatDateEnd}までの日付を入力してください。");
                    } elseif ($formatDateSt > $formatDateEnd) {
                        // 画面上では、DBの日付をそのまま表示するだけなので（特に計算はしない）、恐らく、以下はとおることはないが、exception で対応すると、画面側がシステムエラーになるため、addErrorで対応した方が良い
                        throw new Exception("There must be an error in the data in ".__FILE__." at line ".__LINE__.": formatDateSt {$formatDateSt} > formatDateEnd {$formatDateEnd}?!)");
                    }
                }
            }


            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            // 搬入-サービス選択
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_detail_outbound_service_sel)->isSelected()->isIn(array_keys($this->comiket_detail_service_lbls));
            if (!$v->isValid()) {
                $errorForm->addError('comiket_detail_outbound_service_sel', '搬入-サービス選択' . $v->getResultMessageTop());
            }

            // 搬入-宅配数量
            if($inForm->comiket_detail_outbound_service_sel == "1") {
                $this->checkComiketBoxOutInboundNumAry($inForm, $errorForm, $inForm->comiket_box_outbound_num_ary, "comiket_box_outbound_num_ary", "搬入");
            }

            // 搬入-カーゴ数量
            if($inForm->comiket_detail_outbound_service_sel == "2") {
                $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_cargo_outbound_num_sel)->isLengthLessThanOrEqualTo(2)->isWebSystemNg()->isNotEmpty();
                if (!$v->isValid()) {
                    $errorForm->addError('comiket_cargo_outbound_num_ary', '搬入-カーゴ数量' . $v->getResultMessageTop());
                }

                if(!empty($inForm->delivery_charge)
                        && intval($inForm->delivery_charge) > 999999) {
                    if(!array_key_exists('comiket_cargo_outbound_num_ary', $errorForm->_errors)) {
                        $errorForm->addError('comiket_cargo_outbound_num_ary', "送料は、￥999,999までが取り扱い金額となります。");
                    }
                }

                if($inForm->comiket_payment_method_cd_sel === '3') { // 電子マネー
                    if(!empty($inForm->delivery_charge)
                            && intval($inForm->delivery_charge) > 10000) {
                        if(!array_key_exists('comiket_cargo_outbound_num_ary', $errorForm->_errors)) {
                            $errorForm->addError('comiket_cargo_outbound_num_ary', "電子マネーの場合、送料は￥10,000までが取り扱い金額となります。");
                        }
                    }
                }
            }

            // 搬入-貸切台数
            if($inForm->comiket_detail_outbound_service_sel == "3") {
                if($inForm->comiket_div == self::COMIKET_DEV_BUSINESS) { // 法人
                        $notErrFlg = FALSE;
                        foreach($inForm->comiket_charter_outbound_num_ary as $key => $val) {
                            // 0 は個人で使用するためとばす
                            if($key == "0") {
                                continue;
                            }
                            $v = Sgmov_Component_Validator::createSingleValueValidator($val)->isNotEmpty();
                            if($v->isValid()) {
                                $notErrFlg = TRUE;
                            }
                            $v = Sgmov_Component_Validator::createSingleValueValidator($val)->isInteger(1)->isLengthLessThanOrEqualTo(2)->isWebSystemNg();
                            if(!$v->isValid()) {
                                $errorForm->addError('comiket_charter_outbound_num_ary', '搬入-貸切台数' . $v->getResultMessageTop());
                            }
                        }
                        if(!$notErrFlg) {
                            $errorForm->addError('comiket_charter_outbound_num_ary', '搬入-貸切台数を入力してください');
                        }
                } else if($inForm->comiket_div == self::COMIKET_DEV_INDIVIDUA) { // 個人
                    // 処理なし
                }
            }

            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_detail_outbound_note1)->isLengthLessThanOrEqualTo(16)->
                    isNotHalfWidthKana()->isWebSystemNg();
            if (!$v->isValid()) {
                $errorForm->addError('comiket_detail_outbound_note', '搬入-備考-1行目' . $v->getResultMessageTop());
            }
        }

        ////////////////////////////////////////////////////////////////////////////////////////////
        // 搬出
        ////////////////////////////////////////////////////////////////////////////////////////////

       $chkInResult = $this->_checkInbound($inForm, $errorForm);
       $zipV_I = $chkInResult["zipV"];
              
        ////////////////////////////////////////////////////////////////////////////////////////////
        // 支払方法
        ////////////////////////////////////////////////////////////////////////////////////////////

        $this->_checkPaymentMethod($inForm, $errorForm);
       
        ////////////////////////////////////////////////////////////////////////////////////////////
        
        // エラーがない場合はメールアドレス一致チェック
        if (!$errorForm->hasError()) {

            // // 郵便番号と住所を確認する。
            // $aKey = array_search($inForm->comiket_pref_cd_sel, $prefectures['ids']);
            // $addressResult = $this->_getAddress($inForm->comiket_zip1.$inForm->comiket_zip2
            //         , $prefectures['names'][$aKey] . $inForm->comiket_address . $inForm->comiket_building);

            // if (empty($addressResult['ShopCodeFlag'])) {
            //     $errorForm->addError('comiket_zip', '住所の入力内容をお確かめください。');
            // }

            // 郵便番号と住所を確認する。
            /*
            $aKey = array_search($inForm->comiket_pref_cd_sel, $prefectures['ids']);
            $addressResult = $this->_getAddressByZip($inForm->comiket_zip1.$inForm->comiket_zip2);
            if (empty($addressResult)) {
                $errorForm->addError('comiket_zip', '住所の入力内容をお確かめください。');
            } else {
                // 入力した住所がソケット通信で郵便番号DLLを検索し取得した住所と合っているかのチェック
                // 県名が異なる場合にtrue
                $isInvalidKenName = $addressResult['kenName'] !== trim($prefectures['names'][$aKey]);

                // 住所がソケット通信で取得した住所から始まらない場合にtrue
                $systemTownName = $addressResult['cityName'] . $addressResult['townName'];
                $userInputTownName = trim($inForm->comiket_address) . trim($inForm->comiket_building);
                $isInvalidTownName = strpos($userInputTownName, $systemTownName) !== 0;
                if ($isInvalidKenName || $isInvalidTownName) {
                    $errorForm->addError('comiket_zip', '郵便番号と住所は合わせてください。');
                }
            }
            */

            // メールアドレス一致チェック
            $v = Sgmov_Component_Validator::createMultipleValueValidator(array($inForm->comiket_mail, $inForm->comiket_mail_retype))->isStringComparison();
            if (!$v->isValid()) {
                $errorForm->addError('comiket_mail_retype', 'メールアドレス確認' . $v->getResultMessageTop());
            }
        }

        if (!$errorForm->hasError()) {

            // エラーがない場合は郵便番号・住所の存在チェック
            if($inForm->comiket_detail_type_sel == "1" || $inForm->comiket_detail_type_sel == "3") { // 搬入の場合
                $key = array_search($inForm->comiket_detail_outbound_pref_cd_sel, $prefectures['ids']);

                $receive = $this->_getAddress($inForm->comiket_detail_outbound_zip1.$inForm->comiket_detail_outbound_zip2
                        , $prefectures['names'][$key] . $inForm->comiket_detail_outbound_address . $inForm->comiket_detail_outbound_building);

                $errorFlg = false;
                if (empty($receive['ShopCodeFlag'])) {
                    $errorForm->addError('comiket_detail_outbound_zip', '搬入-集荷先住所の入力内容をお確かめください。');
                    $errorFlg = true;
                } elseif (!empty($receive['ExchangeFlag'])) {
                    $errorForm->addError('comiket_detail_outbound_zip', '搬入-集荷先住所は集荷・配達できない地域の恐れがあります。');
                    $errorFlg = true;
                } elseif (!empty($receive['TimeZoneFlag'])
                        && ((!empty($inForm->comiket_detail_outbound_collect_time_sel) && $inForm->comiket_detail_outbound_collect_time_sel !== '00')
                            || (!empty($inForm->comiket_detail_outbound_delivery_time_sel) && $inForm->comiket_detail_outbound_delivery_time_sel !== '00'))) {
                    $errorForm->addError('comiket_detail_outbound_collect_date', '搬入-集荷先住所は時間帯指定できない地域の恐れがあります。');
                    $errorFlg = true;
                } elseif (!empty($receive['RelayFlag'])) {
                    $errorForm->addError('comiket_detail_outbound_zip', '搬入-集荷先住所は配達できない地域の恐れがあります。');
                    $errorFlg = true;
                }



                // 郵便番号と住所があっているかチェックする
                if (!$errorFlg) {
                    /*
                    $addressResult = $this->_getAddressByZip($inForm->comiket_detail_outbound_zip1.$inForm->comiket_detail_outbound_zip2);
                     if (empty($addressResult)) {
                        $errorForm->addError('comiket_zip', '搬入-集荷先住所の入力内容をお確かめください。');
                    } else {
                        // 入力した住所がソケット通信で郵便番号DLLを検索し取得した住所と合っているかのチェック
                        // 県名が異なる場合にtrue
                        $isInvalidKenName = $addressResult['kenName'] !== trim($prefectures['names'][$key]);

                        // 住所がソケット通信で取得した住所から始まらない場合にtrue
                        $systemTownName = $addressResult['cityName'] . $addressResult['townName'];
                        $userInputTownName = trim($inForm->comiket_detail_outbound_address) . trim($inForm->comiket_detail_outbound_building);
                        $isInvalidTownName = strpos($userInputTownName, $systemTownName) !== 0;
                        if ($isInvalidKenName || $isInvalidTownName) {
                            $errorForm->addError('comiket_detail_outbound_zip', '搬入-集荷先郵便番号と集荷先住所は合わせてください。');
                        }
                    }
                    */
                }
            }

            if($inForm->comiket_detail_type_sel == "2" || $inForm->comiket_detail_type_sel == "3") { // 搬出の場合
                $key = array_search($inForm->comiket_detail_inbound_pref_cd_sel, $prefectures['ids']);
                // 復路用時間帯対応（カンマ区切りでコード、名称を持っているため）
                $inboundColleTimeCd = '';
                if ($inForm->comiket_detail_inbound_collect_time_sel != NULL && $inForm->comiket_detail_inbound_collect_time_sel != '') {
                    $arrInbouCollectTimeCd = explode(',', $inForm->comiket_detail_inbound_collect_time_sel);
                    $inboundColleTimeCd = $arrInbouCollectTimeCd[0];
                }

                $outboundColleTimeCd = '';
                if ($inForm->comiket_detail_inbound_delivery_time_sel != NULL && $inForm->comiket_detail_inbound_delivery_time_sel != '') {
                    $arrOutbouCollectTimeCd = explode(',', $inForm->comiket_detail_inbound_delivery_time_sel);
                    $outboundColleTimeCd = $arrOutbouCollectTimeCd[0];
                }

                $receive = $this->_getAddress($inForm->comiket_detail_inbound_zip1.$inForm->comiket_detail_inbound_zip2
                        ,$prefectures['names'][$key] . $inForm->comiket_detail_inbound_address . $inForm->comiket_detail_inbound_building);


                $errorFlg = false;
                if (empty($receive['ShopCodeFlag'])) {
                    $errorForm->addError('comiket_detail_inbound_zip', '搬出-お届け先住所の入力内容をお確かめください。');
                    $errorFlg = true;
                } elseif (!empty($receive['ExchangeFlag'])) {
                    $errorForm->addError('comiket_detail_inbound_zip', '搬出-お届け先住所は集荷・配達できない地域の恐れがあります。');
                    $errorFlg = true;
                } elseif (!empty($receive['TimeZoneFlag'])
                        && ((!empty($inForm->comiket_detail_inbound_collect_time_sel) && $inboundColleTimeCd !== '00')
                            || (!empty($inForm->comiket_detail_inbound_delivery_time_sel) && $outboundColleTimeCd !== '00'))) {
                    $errorForm->addError('comiket_detail_inbound_delivery_date', '搬出-配送先住所は時間帯指定できない地域の恐れがあります。');
                    $errorFlg = true;
                } elseif (!empty($receive['RelayFlag'])) {
                    $errorForm->addError('comiket_detail_inbound_zip', '搬出-お届け先住所は配達できない地域の恐れがあります。');
                    $errorFlg = true;
                }

                // 郵便番号と住所があっているかチェックする
                if (!$errorFlg) {
                    /*
                    $addressResult = $this->_getAddressByZip($inForm->comiket_detail_inbound_zip1.$inForm->comiket_detail_inbound_zip2);
                     if (empty($addressResult)) {
                        $errorForm->addError('comiket_zip', '搬出-お届け先住所の入力内容をお確かめください。');
                    } else {
                        // 入力した住所がソケット通信で郵便番号DLLを検索し取得した住所と合っているかのチェック
                        // 県名が異なる場合にtrue
                        $isInvalidKenName = $addressResult['kenName'] !== trim($prefectures['names'][$key]);

                        // 住所がソケット通信で取得した住所から始まらない場合にtrue
                        $systemTownName = $addressResult['cityName'] . $addressResult['townName'];
                        $userInputTownName = trim($inForm->comiket_detail_inbound_address) . trim($inForm->comiket_detail_inbound_building);
                        $isInvalidTownName = strpos($userInputTownName, $systemTownName) !== 0;
                        if ($isInvalidKenName || $isInvalidTownName) {
                            $errorForm->addError('comiket_detail_inbound_zip', '搬出-お届け先郵便番号とお届け先住所は合わせてください。');
                        }
                    }
                    */
                }
            }
        }

        return $errorForm;
    }

    /**
     * 支払い方法のバリデーション
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
            if ($inForm->comiket_payment_method_cd_sel === '1') {
                // お支払い店舗 必須チェック
                $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_convenience_store_cd_sel)->isSelected()->
                        isIn(array_keys($this->convenience_store_lbls));
                if (!$v->isValid()) {
                    $errorForm->addError('payment_method', 'お支払い方法' . $v->getResultMessageTop());
                }
            }
        }
    }

    /**
     * 搬出選択時の入力バリデーション
     * @param type $errorForm
     */
    public function _checkInbound($inForm, &$errorForm) {

        ////////////////////////////////////////////////////////////////////////////////////////////
        // 搬出
        ////////////////////////////////////////////////////////////////////////////////////////////
        $zipV = NULL;
        if($inForm->comiket_detail_type_sel == "2" || $inForm->comiket_detail_type_sel == "3") { // 搬出の場合
            $db = Sgmov_Component_DB::getPublic();

            // 都道府県のリストを取得しておく
            $prefectures = $this->_PrefectureService->fetchPrefectures($db);

            // 搬出-集荷先名
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_detail_inbound_name)->isNotEmpty()->isLengthLessThanOrEqualTo(32)->
                    isNotHalfWidthKana()->isWebSystemNg();
            if (!$v->isValid()) {
                $errorForm->addError('comiket_detail_inbound_name', '搬出-お届け先名' . $v->getResultMessageTop());
            }

            // 搬出-集荷先郵便番号
            // 郵便番号(最後に存在確認をするので別名でバリデータを作成) 必須チェック
            $zipV = Sgmov_Component_Validator::createZipValidator($inForm->comiket_detail_inbound_zip1, $inForm->comiket_detail_inbound_zip2)->isNotEmpty()->isZipCode();
            if (!$zipV->isValid()) {
                $errorForm->addError('comiket_detail_inbound_zip', '搬出-お届け先郵便番号' . $zipV->getResultMessageTop());
            }

            // 搬出-集荷先都道府県
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_detail_inbound_pref_cd_sel)->isSelected()->isIn($prefectures['ids']);
            if (!$v->isValid()) {
                $errorForm->addError('comiket_detail_inbound_pref', '搬出-お届け先都道府県' . $v->getResultMessageTop());
            }
            if(($inForm->comiket_detail_inbound_pref_cd_sel == '47' && $inForm->comiket_detail_inbound_service_sel == '2')) {
                $errorForm->addError('comiket_detail_inbound_pref', '搬出-本イベントでの出荷・配達不可地域となります。');
            }

            // 搬出-集荷先市区町村 必須チェック 40文字チェック WEBシステムNG文字チェック
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_detail_inbound_address)->isNotEmpty()->isLengthLessThanOrEqualTo(14)->
                    isNotHalfWidthKana()->isWebSystemNg();
            if (!$v->isValid()) {
                $errorForm->addError('comiket_detail_inbound_address', '搬出-お届け先市区町村' . $v->getResultMessageTop());
            } else {
                $prefName = @$prefectures["names"][$prefectures["ids"][$inForm->comiket_detail_inbound_pref_cd_sel]];
                if (strpos($inForm->comiket_detail_inbound_address, $prefName) !== false) {
                    $errorForm->addError('comiket_detail_inbound_address', '搬出-お届け先市区町村には都道府県名は入力しないで下さい。');
                }
            }


            // 搬出-集荷先番地・建物名・部屋番号 必須チェック 40文字チェック WEBシステムNG文字チェック
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_detail_inbound_building)->isNotEmpty()->isLengthLessThanOrEqualTo(30)->
                    isNotHalfWidthKana()->isWebSystemNg();
            if (!$v->isValid()) {
                $errorForm->addError('comiket_detail_inbound_building', '搬出-お届け先番地・建物名・部屋番号' . $v->getResultMessageTop());
            } else {
                $prefName = @$prefectures["names"][$prefectures["ids"][$inForm->comiket_detail_inbound_pref_cd_sel]];
                if (strpos($inForm->comiket_detail_inbound_building, $prefName) !== false) {
                    $errorForm->addError('comiket_detail_inbound_building', '搬出-お届け先番地・建物名・部屋番号には都道府県名は入力しないで下さい。');
                }
            }

            // 搬出-お届け先TEL 必須チェック 型チェック WEBシステムNG文字チェック
            $comiektDetailInboundTel = @str_replace(array('-', 'ー', '−', '―', '‐', 'ｰ'), "", $inForm->comiket_detail_inbound_tel);
            //GiapLN imp ticket #SMT6-381 2022/12/29
            $v = Sgmov_Component_Validator::createSingleValueValidator($comiektDetailInboundTel)->isNotEmpty()->isPhoneHyphen()->isLengthLessThanOrEqualToForPhone();
            if (!$v->isValid()) {
                $errorForm->addError('comiket_detail_inbound_tel', '搬出-お届け先TEL' . $v->getResultMessageTop());
            } else {
                $v = Sgmov_Component_Validator::createSingleValueValidator($comiektDetailInboundTel)->isLengthMoreThanOrEqualTo(8)->isLengthLessThanOrEqualTo(12);
                if (!$v->isValid()) {
                    $errorForm->addError('comiket_detail_inbound_tel', '搬出-お届け先TELの数値部分' . $v->getResultMessageTop());
                }
            }

            ////////////////////////////////////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////////////////////////////////////

            // 搬出-お預かり日 必須チェック
            $v = Sgmov_Component_Validator::createDateValidator($inForm->comiket_detail_inbound_collect_date_year_sel,
                        $inForm->comiket_detail_inbound_collect_date_month_sel, $inForm->comiket_detail_inbound_collect_date_day_sel)->isNotEmpty();
            if (!$v->isValid()) {
                $errorForm->addError('comiket_detail_inbound_collect_date', '搬出-お預かり日' . $v->getResultMessageTop());
            } else {

            // 搬出-お預かり日 範囲チェック
                if (empty($inForm->comiket_detail_inbound_collect_date_year_sel)
                        || empty($inForm->comiket_detail_inbound_collect_date_month_sel)
                        || empty($inForm->comiket_detail_inbound_collect_date_day_sel)
                        ) {
                    $max = null;
                    $max_year  = null;
                    $max_month = null;
                    $max_day   = null;
                } else {
                    $eventsubInfo = $this->_EventsubService->fetchEventsubByEventsubId($db, $inForm->eventsub_sel);

                    $dateSt = new DateTime($eventsubInfo["in_bound_loading_fr"]);
                    $dateEnd = new DateTime($eventsubInfo["in_bound_loading_to"]);
                    $min = intval($dateSt->format('U'));
                    $max = intval($dateEnd->format('U'));

                    $formatDateSt = $dateSt->format('Y/m/d');
                    $formatDateEnd = $dateEnd->format('Y/m/d');

                    $current_date = new DateTime('today');
                    $current_time = intval($current_date->format('U'));
                    // 開始日がカラか開始日が今日より前の場合
                    // この条件 $min < $current_time は常に通る
                    if (empty($min) || $min < $current_time) {
                        $min = $current_time;
                    }

                    $v->isSelected()->isDate($min, $max);
                    if (!$v->isValid()) {

                        $formatDateSt = date('Y/m/d', $min);
                        // TODO:文字列の日付で比較はおかしい
                        // 開始日と終了日が一致：この条件でチェックしている意味がわからない
                        if($formatDateSt == $formatDateEnd) {
                            $errorForm->addError('comiket_detail_inbound_collect_date', "搬出-お預かり日は、{$formatDateSt}を入力してください。");
                        } elseif ($formatDateSt < $formatDateEnd) {
                            $errorForm->addError('comiket_detail_inbound_collect_date', "搬出-お預かり日は、{$formatDateSt}～{$formatDateEnd}までの日付を入力してください。");
                        } elseif ($formatDateSt > $formatDateEnd) {
                            $errorForm->addError('comiket_detail_inbound_collect_date', "搬出-お預かり日は、{$formatDateSt}～{$formatDateEnd}までの日付を入力してください。（お届け指定日期間もご確認ください）");
                        }
                    }
                }
            }

            ////////////////////////////////////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////////////////////////////////////

            $eventsubInfo = $this->_EventsubService->fetchEventsubByEventsubId($db, $inForm->eventsub_sel);
            if($this->checkColAndDelDate("inbound", $inForm->comiket_div, $inForm->comiket_detail_inbound_service_sel, $eventsubInfo)) {
                if($inForm->comiket_detail_inbound_service_sel == "1") {
                    // 搬出-お届け指定日時 必須チェック
                    $v = Sgmov_Component_Validator::createDateValidator($inForm->comiket_detail_inbound_delivery_date_year_sel,
                                $inForm->comiket_detail_inbound_delivery_date_month_sel, $inForm->comiket_detail_inbound_delivery_date_day_sel)->isNotEmpty();
                    if (!$v->isValid()) {
                        $errorForm->addError('comiket_detail_inbound_delivery_date', '搬出-お届け指定日時' . $v->getResultMessageTop());
                    }

                    // 搬出-お届け指定日時 範囲チェック
                    if (empty($inForm->comiket_detail_inbound_delivery_date_year_sel)
                            || empty($inForm->comiket_detail_inbound_delivery_date_month_sel)
                            || empty($inForm->comiket_detail_inbound_delivery_date_day_sel)
                            || ($inForm->comiket_detail_inbound_delivery_time_sel != "00" && empty($inForm->comiket_detail_inbound_delivery_time_sel))
                            ) {
                        $max = null;
                        $max_year  = null;
                        $max_month = null;
                        $max_day   = null;
                    } else {
                        $eventsubInfo = $this->_EventsubService->fetchEventsubByEventsubId($db, $inForm->eventsub_sel);
                        $date = new DateTime($inForm->hid_comiket_detail_inbound_delivery_date_from);

                        $min  = intval($date->format('U'));
                        $formatDateSt = $date->format('Y/m/d');
                        $date = new DateTime($inForm->hid_comiket_detail_inbound_delivery_date_to);
                        $max  = intval($date->format('U'));
                        $formatDateEnd = $date->format('Y/m/d');

                        $current_date = new DateTime('tomorrow');
                        // 開始日がカラか開始日が明日より前の場合
                        // この条件 $min < $current_time は常に通る
                        $current_time = intval($current_date->format('U'));
                        if (empty($min) || $min < $current_time) {
                            $min = $current_time;
                        }

                        $v->isSelected()->isDate($min, $max);
                        if (!$v->isValid()) {
                            // TODO:文字列の日付で比較はおかしい
                            if ($formatDateSt < $formatDateEnd) {
                                $errorForm->addError('comiket_detail_inbound_delivery_date', "搬出-お届け指定日時は、{$formatDateSt}～{$formatDateEnd}までの日付を入力してください。");
                            } elseif ($formatDateSt > $formatDateEnd) {
                                $errorForm->addError('comiket_detail_inbound_delivery_date', "搬出-お届け指定日時は、{$formatDateSt}～{$formatDateEnd}までの日付を入力してください。（お預かり日期間もご確認ください）");

                            }
                        }

                    }
             
                    if($this->checkColAndDelTime("inbound", $inForm->comiket_div, $inForm->comiket_detail_inbound_service_sel, $eventsubInfo)) {
                        if($inForm->comiket_div == self::COMIKET_DEV_INDIVIDUA &&
                                $inForm->comiket_detail_inbound_service_sel == '1') { // 個人 && 選択サービスが宅配の場合
                            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_detail_inbound_delivery_time_sel)->isSelected()->
                                    isIn(array_keys($this->comiket_detail_delivery_timezone));
                            $is_valid = $v->isValid();
                            if (!$is_valid) {
                                $errorForm->addError('comiket_detail_inbound_delivery_date', '搬出-お届け指定日時-時間帯' . $v->getResultMessageTop());
                            }
                        }
                    }
                }
            }

            ////////////////////////////////////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////////////////////////////////////

            // 搬出-サービス選択
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_detail_inbound_service_sel)->isSelected()->isIn(array_keys($this->comiket_detail_service_lbls));
            if (!$v->isValid()) {
                $errorForm->addError('comiket_detail_inbound_service_sel', '搬出-サービス選択' . $v->getResultMessageTop());
            }

            // 搬出-宅配数量
            if($inForm->comiket_detail_inbound_service_sel == "1") {
                $this->checkComiketBoxOutInboundNumAry($inForm, $errorForm, $inForm->comiket_box_inbound_num_ary, "comiket_box_inbound_num_ary", "搬出");
            }

            // 搬出-カーゴ数量
            if($inForm->comiket_detail_inbound_service_sel == "2") {
                if($inForm->comiket_div == self::COMIKET_DEV_BUSINESS
                        || ($inForm->comiket_div == self::COMIKET_DEV_INDIVIDUA && $inForm->event_sel == '2')) {
                    // 法人 または 個人 かつ コミケ の場合
                    $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_cargo_inbound_num_sel)->isLengthLessThanOrEqualTo(2)->isWebSystemNg()->isNotEmpty();
                    if (!$v->isValid()) {
                        $errorForm->addError('comiket_cargo_inbound_num_ary', '搬出-カーゴ数量' . $v->getResultMessageTop());
                    }

                    if(!empty($inForm->delivery_charge)
                            && intval($inForm->delivery_charge) > 999999) {
                        if(!array_key_exists('comiket_cargo_inbound_num_ary', $errorForm->_errors)) {
                            $errorForm->addError('comiket_cargo_inbound_num_ary', "送料は、￥999,999までが取り扱い金額となります。");
                        }
                    }

                    if($inForm->comiket_payment_method_cd_sel === '3') { // 電子マネー
                        if(!empty($inForm->delivery_charge)
                                && intval($inForm->delivery_charge) > 10000) {
                            if(!array_key_exists('comiket_cargo_inbound_num_ary', $errorForm->_errors)) {
                                $errorForm->addError('comiket_cargo_inbound_num_ary', "電子マネーの場合、送料は￥10,000までが取り扱い金額となります。");
                            }
                        }
                    }
                }
            }

            // 搬出-貸切台数
            if($inForm->comiket_detail_inbound_service_sel == "3") {
                if($inForm->comiket_div == self::COMIKET_DEV_BUSINESS) { // 法人
                        $notErrFlg = FALSE;
                        foreach($inForm->comiket_charter_inbound_num_ary as $key => $val) {
                            // 0 は個人で使用するためとばす
                            if($key == "0") {
                                continue;
                            }
                            $v = Sgmov_Component_Validator::createSingleValueValidator($val)->isNotEmpty();
                            if ($v->isValid()) {
                                $notErrFlg = TRUE;
                            }

                            $v = Sgmov_Component_Validator::createSingleValueValidator($val)->isInteger(1)->isLengthLessThanOrEqualTo(3)->isWebSystemNg();
                            if (!$v->isValid()) {
                                $errorForm->addError('comiket_charter_inbound_num_ary', '搬出-貸切台数' . $v->getResultMessageTop());
                                break;
                            }
                        }
                        if(!$notErrFlg) {
                            $errorForm->addError('comiket_charter_inbound_num_ary', '搬出-貸切台数を入力してください。');
                        }
                } else if($inForm->comiket_div == self::COMIKET_DEV_INDIVIDUA) { // 個人
                    // 処理なし
                }
            }


            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_detail_inbound_note1)->isLengthLessThanOrEqualTo(16)->
                    isNotHalfWidthKana()->isWebSystemNg();
            if (!$v->isValid()) {
                $errorForm->addError('comiket_detail_inbound_note', '搬出-備考-1行目' . $v->getResultMessageTop());
            }
        }

        return array(
            "zipV" => $zipV,
        );
    }

    /**
     * 搬入・搬出選択時の数量、金額上限チェック
     * @param type $inForm
     * @param type $errorForm
     * @param type $targetList
     * @param type $targetClassName
     * @param type $errMsgOutInbount
     */
    protected function checkComiketBoxOutInboundNumAry($inForm, &$errorForm, $targetList, $targetClassName, $errMsgOutInbount, $isEmptyCheck = true, $fromSizeChange = false) {
        
        $result = array(
            "errflg" => FALSE,
            "errData" => array(),
        );
        // 入力フォーム数カウント用
        $notEmptyCount = 0;
        $errorFlg = FALSE;
        // 入力個数カウント用
        $totalCnt = 0;
        foreach($targetList as $key => $val) {
            // 0 は法人で使用するためとばす
            if($key == "0") {
                continue;
            }
            $v = Sgmov_Component_Validator::createSingleValueValidator($val)->isNotEmpty();

            if ($v->isValid()) {
                $notEmptyCount++;
            }

            $v = Sgmov_Component_Validator::createSingleValueValidator($val)->isInteger(1)->isLengthLessThanOrEqualTo(3)->isWebSystemNg();
            if (!$v->isValid()) {
                $errorFlg = TRUE;
            }

            if(!$v->isValid() && $fromSizeChange && $val == "0"){
                $errorFlg = FALSE;
            }

            $totalCnt += $val;
        }

        $isErrFlg2 = FALSE;
        if($errorFlg) {
            $errorForm->addError($targetClassName, "{$errMsgOutInbount}-宅配数量の入力値を確認してください。（数値のみ）");
            $isErrFlg2 = TRUE;
        }
        if(5 <= $notEmptyCount) {
            $errorForm->addError($targetClassName, "{$errMsgOutInbount}-宅配数量の入力は４つまでです。");
            $isErrFlg2 = TRUE;
        }
        if(0 == $notEmptyCount && $isEmptyCheck) {
            $errorForm->addError($targetClassName, "{$errMsgOutInbount}-宅配数量を入力してください。");
        }

        // 宅配数量の合計が40個を超える場合
        if(!$isErrFlg2 && $totalCnt > 40){
            $errorForm->addError($targetClassName, "{$errMsgOutInbount}-宅配合計は40個まで入力可能としてください。");
            $isErrFlg2 = TRUE;
        }

        if(!$isErrFlg2) {
            if (!empty($inForm->delivery_charge)) {
                if ($inForm->comiket_payment_method_cd_sel === '1' && intval($inForm->delivery_charge) > 300000) { // コンビニ前払い
                    $errorForm->addError($targetClassName, "{$errMsgOutInbount}-コンビニ前払いの場合、送料は￥300,000までが取り扱い金額となります。");
                } else if($inForm->comiket_payment_method_cd_sel === '3' && intval($inForm->delivery_charge) > 10000) { // 電子マネー
                    $errorForm->addError($targetClassName, "{$errMsgOutInbount}-電子マネーの場合、送料は￥10,000までが取り扱い金額となります。");
                } elseif (intval($inForm->delivery_charge) > 999999) {
                    $errorForm->addError($targetClassName, "{$errMsgOutInbount}-送料は、￥999,999までが取り扱い金額となります。");
                }
            }
        }
    }
}