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
 * @subpackage UNA
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Una_CheckInput extends Sgmov_View_Una_Common {

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
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_UNA001, $this->_getTicket());

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
        } else {
            Sgmov_Component_Redirect::redirectPublicSsl('/'.$this->_DirDiv.'/confirm');
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
        $buildingNameInfo = $this->_BuildingService->fetchBuildingById($db, $inForm->building_booth_position_sel);
        $inForm->building_name_sel = @$buildingNameInfo['cd'];
        $inForm->comiket_booth_num = @filter_input(INPUT_POST, 'comiket_booth_num');


        /** コミケ申込明細データ **/
        // 搬入
        $inForm->comiket_detail_type_sel = filter_input(INPUT_POST, 'comiket_detail_type_sel');
        
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


        $inForm->input_mode = filter_input(INPUT_POST, 'input_mode');


        // 往路-お預かり可能日
        $inForm->hid_comiket_detail_outbound_collect_date_from = filter_input(INPUT_POST, 'hid_comiket-detail-outbound-collect-date-from');
        $inForm->hid_comiket_detail_outbound_collect_date_to = filter_input(INPUT_POST, 'hid_comiket-detail-outbound-collect-date-to');
        
        
        $inForm->comiket_detail_outbound_pref_cd_sel = $inForm->comiket_pref_cd_sel;
        
        $inForm->comiket_detail_type_sel2         = filter_input(INPUT_POST, 'comiket_detail_type_sel2');
        $inForm->comiket_detail_collect_date_sel   = filter_input(INPUT_POST, 'comiket_detail_collect_date_sel');

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
        $buildings = $this->_BuildingService->fetchBuildingByEventId($db, $inForm->eventsub_sel);
        
        $eventArray = $this->_EventService->fetchEventListWithinTerm($db);
        $eventsubArray = $this->_EventsubService->fetchEventsubListWithinTermByEventId($db, $inForm->event_sel);
        $eventsubInfo = NULL;
        if(!empty($inForm->eventsub_sel)) {
            $eventsubInfo = $this->_EventsubService->fetchEventsubByEventsubId($db, $inForm->eventsub_sel);
        }

        if (filter_input(INPUT_POST, 'hid_timezone_flg') == '1') {
            $errorForm->addError('event_sel', '選択のツアーは受付時間を超過しています。');
        }

        if(empty($inForm->event_sel)) {
            $errorForm->addError('event_sel', 'ツアーが選択されていません。');
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

        // 往復選択
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_detail_type_sel)->isSelected()->isIn(array_keys($this->comiket_detail_type_lbls));
        if (!$v->isValid()) {
            $errorForm->addError('comiket_detail_type_sel', '往復選択' . $v->getResultMessageTop());
        }

        //宿泊先
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->building_booth_position_sel)->isSelected()->isIn($buildings['ids']);
        if (!$v->isValid()) {
            $errorForm->addError('building_name_sel', '宿泊先' . $v->getResultMessageTop());
        }
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // 搬入
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $zipV = NULL;
        if($inForm->comiket_detail_type_sel == "1" || $inForm->comiket_detail_type_sel == "3") { // 搬入の場合

            // 搬入/搬出、個人/法人 、日付指定の有無チェック
            if($this->checkColAndDelDate("outbound", $inForm->comiket_div, $inForm->comiket_detail_outbound_service_sel, $eventsubInfo)) {

                $isOutboundCollectDateCheck = true;

                // 受付時間が終了しているときは（jsの非同期通信でチェックしている）、以下2項目に '1900-01-01' が入る
                if ($inForm->hid_comiket_detail_outbound_collect_date_from == '1900-01-01'
                        && $inForm->hid_comiket_detail_outbound_collect_date_to == '1900-01-01') {
                    $errorForm->addError('comiket_detail_outbound_collect_date', 'お預かり希望日時:受付時間は終了しました。');
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
                            $errorForm->addError('comiket_detail_outbound_collect_date', 'お預かり希望日時:受付時間は終了しました。');
                            $isOutboundCollectDateCheck = false;
                        }
                    }
                }


                if ($isOutboundCollectDateCheck) {
                    // 集荷希望日時 必須チェック
                    $v = Sgmov_Component_Validator::createDateValidator($inForm->comiket_detail_outbound_collect_date_year_sel,
                                $inForm->comiket_detail_outbound_collect_date_month_sel, $inForm->comiket_detail_outbound_collect_date_day_sel)->isNotEmpty();
                    if (!$v->isValid()) {
                        $errorForm->addError('comiket_detail_outbound_collect_date', 'お預かり希望日時' . $v->getResultMessageTop());
                    }

                    // 集荷希望日時 範囲チェック
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
                        // お預かり日終了日
                        $date = new DateTime($inForm->hid_comiket_detail_outbound_collect_date_to);

                        $max_year  = intval($date->format('Y'));
                        $max_month = intval($date->format('m'));
                        $max_day   = intval($date->format('d'));
                        $max  = intval($date->format('U'));
                        $formatDateEnd = $date->format('Y/m/d');

                        // お預かり日開始日
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
                                $errorForm->addError('comiket_detail_outbound_collect_date', "お預かり希望日時は、{$formatDateSt}を入力してください。");
                            } elseif ($formatDateSt < $formatDateEnd) {
                                $errorForm->addError('comiket_detail_outbound_collect_date', "お預かり希望日時は、{$formatDateSt}～{$formatDateEnd}までの日付を入力してください。");
                            } elseif ($formatDateSt > $formatDateEnd) {
                                $errorForm->addError('comiket_detail_outbound_collect_date', "お預かり希望日時は、受付時間が終了しました。");
                            }
                        } else {
                            $currentDate = new DateTime();
                            $nextDate = $currentDate->modify("1day");
                            $nextDateYMD = $nextDate->format("Y/m/d");
                            $dateSelect = new DateTime($inForm->comiket_detail_outbound_collect_date_year_sel.'-'.$inForm->comiket_detail_outbound_collect_date_month_sel.'-'.$inForm->comiket_detail_outbound_collect_date_day_sel);
                            $dateSelectYMD = $dateSelect->format("Y/m/d");
                            Sgmov_Component_Log::debug('#################### Check Collect Date input');
                            Sgmov_Component_Log::debug($dateSelectYMD);
                            Sgmov_Component_Log::debug($nextDateYMD);
                            if ($dateSelectYMD < $nextDateYMD) {
                                $errorForm->addError('comiket_detail_outbound_collect_date', "お預かり希望日時は、{$nextDateYMD}以降を入力してください。");
                            }
                        }
                    }

                    // 搬入/搬出、個人/法人 、時間指定の有無チェック
                    if($this->checkColAndDelTime("outbound", $inForm->comiket_div, $inForm->comiket_detail_outbound_service_sel, $eventsubInfo)) {
                        // 集荷希望日時-時間帯 必須チェック
                        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_detail_outbound_collect_time_sel)->isSelected()->
                                isIn(array_keys($this->comiket_detail_time_lbls));
                        $is_valid = $v->isValid();
                        if (!$is_valid) {
                            $errorForm->addError('comiket_detail_outbound_collect_date', 'お預かり希望日時-時間帯' . $v->getResultMessageTop());
                        }
                        
                        // 集荷希望日時-時間帯 最終日チェック
                        $comiket_detail_outbound_collect_time_cds = array_keys($this->comiket_detail_time_lbls);
                        if ($max_year === intval($inForm->comiket_detail_outbound_collect_date_year_sel)
                                && $max_month === intval($inForm->comiket_detail_outbound_collect_date_month_sel)
                                && $max_day === intval($inForm->comiket_detail_outbound_collect_date_day_sel)) {
                            // array_popで時間帯リスト(配列)の最後をとりだしている
                            array_pop($comiket_detail_outbound_collect_time_cds);
                        }
                        $v->isIn($comiket_detail_outbound_collect_time_cds);
                        if ($is_valid && !$v->isValid()) {
                            $errorForm->addError('comiket_detail_outbound_collect_date', '集荷可能期間の最終日のため、18時～20時は選択できません。');
                        }
                    }
                }
            }

            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            // 引渡し日 必須チェック
            $v = Sgmov_Component_Validator::createDateValidator($inForm->comiket_detail_outbound_delivery_date_year_sel,
                        $inForm->comiket_detail_outbound_delivery_date_month_sel, $inForm->comiket_detail_outbound_delivery_date_day_sel)->isNotEmpty();
            if (!$v->isValid()) {
                $errorForm->addError('comiket_detail_outbound_delivery_date', '宿泊日（引き渡し希望日）' . $v->getResultMessageTop());
            }

            // お届け日時 範囲チェック
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
                        $errorForm->addError('comiket_detail_outbound_delivery_date', "宿泊日（引き渡し希望日）、{$formatDateSt}～{$formatDateEnd}までの日付を入力してください。");
                    } elseif ($formatDateSt > $formatDateEnd) {
                        // 画面上では、DBの日付をそのまま表示するだけなので（特に計算はしない）、恐らく、以下はとおることはないが、exception で対応すると、画面側がシステムエラーになるため、addErrorで対応した方が良い
                        throw new Exception("There must be an error in the data in ".__FILE__." at line ".__LINE__.": formatDateSt {$formatDateSt} > formatDateEnd {$formatDateEnd}?!)");
                    }
                }
            }


            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            // サービス選択
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_detail_outbound_service_sel)->isSelected()->isIn(array_keys($this->comiket_detail_service_lbls));
            if (!$v->isValid()) {
                $errorForm->addError('comiket_detail_outbound_service_sel', 'サービス選択' . $v->getResultMessageTop());
            }
            
            // TODO マジックナンバーを定数にする
            $checkDeparture = ((intval($inForm->comiket_detail_type_sel2) & 1) === 1);
            $checkArrival   = ((intval($inForm->comiket_detail_type_sel2) & 2) === 2);

            $this->checkComiketBoxOutInboundNumAry($inForm, $errorForm, $inForm->comiket_box_outbound_num_ary, "comiket_box_outbound_num_ary");
            
            if ($checkArrival) {
                $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_detail_collect_date_sel)->isSelected();
                if (!$v->isValid()) {
                    $errorForm->addError('top_comiket_detail_collect_date', '復路集荷日' . $v->getResultMessageTop());
                }
            }
        }
       
        ////////////////////////////////////////////////////////////////////////////////////////////
        
        // エラーがない場合はメールアドレス一致チェック
        if (!$errorForm->hasError()) {
            
            $key = array_search($inForm->comiket_pref_cd_sel, $prefectures['ids']);

            $receive = $this->_getAddress($inForm->comiket_zip1.$inForm->comiket_zip2
                    , $prefectures['names'][$key] . $inForm->comiket_address . $inForm->comiket_building);

            $errorFlg = false;
            if (empty($receive['ShopCodeFlag'])) {
                $errorForm->addError('comiket_zip', '集荷先住所の入力内容をお確かめください。');
                $errorFlg = true;
            } elseif (!empty($receive['ExchangeFlag'])) {
                $errorForm->addError('comiket_zip', '集荷先住所は集荷・配達できない地域の恐れがあります。');
                $errorFlg = true;
            } elseif (!empty($receive['TimeZoneFlag'])
                    && ((!empty($inForm->comiket_detail_outbound_collect_time_sel) && $inForm->comiket_detail_outbound_collect_time_sel !== '00')
                        || (!empty($inForm->comiket_detail_outbound_delivery_time_sel) && $inForm->comiket_detail_outbound_delivery_time_sel !== '00'))) {
                $errorForm->addError('comiket_detail_outbound_collect_date', '集荷先住所は時間帯指定できない地域の恐れがあります。');
                $errorFlg = true;
            } elseif (!empty($receive['RelayFlag'])) {
                $errorForm->addError('comiket_detail_outbound_zip', '集荷先住所は配達できない地域の恐れがあります。');
                $errorFlg = true;
            }
            // メールアドレス一致チェック
            $v = Sgmov_Component_Validator::createMultipleValueValidator(array($inForm->comiket_mail, $inForm->comiket_mail_retype))->isStringComparison();
            if (!$v->isValid()) {
                $errorForm->addError('comiket_mail_retype', 'メールアドレス確認' . $v->getResultMessageTop());
            }
        }

        if (!$errorForm->hasError()) {
            // エラーがない場合は郵便番号・住所の存在チェック
            if($inForm->comiket_detail_type_sel == "1" || $inForm->comiket_detail_type_sel == "3") { // 搬入の場合
                if ($inForm->comiket_pref_cd_sel == 47) {
                    $errorForm->addError('comiket_pref', '沖縄県はお申込できません。');
                }
                
            }
        }

        return $errorForm;
    }

    /**
     * 搬入・搬出選択時の数量、金額上限チェック
     * @param type $inForm
     * @param type $errorForm
     * @param type $targetList
     * @param type $targetClassName
     */
    protected function checkComiketBoxOutInboundNumAry($inForm, &$errorForm, $targetList, $targetClassName) {
        $checkArrival   = ((intval($inForm->comiket_detail_type_sel2) & 2) === 2);

        $messageErr = "";
        $isFirst = true;
        foreach($targetList as $key => $val) {
            // 0 は法人で使用するためとばす
            if($key == "0") {
                continue;
            }
            $v = Sgmov_Component_Validator::createSingleValueValidator($val)->isNotEmpty();

            if (!$v->isValid()) {
                if ($isFirst) {
                    $messageErr = "宅配数量-往路を入力してください。";
                } else {
                    if ($inForm->comiket_detail_type_sel2 == '3') {
                        $messageErr = !empty($messageErr) ? "宅配数量を入力してください。" : "宅配数量-復路を入力してください。";
                    }
                }
            }
            $isFirst = false;
        }
        if (empty($messageErr)) {
            $messageErr = "";
            $isFirst = true;
            foreach($targetList as $key => $val) {
                // 0 は法人で使用するためとばす
                if($key == "0") {
                    continue;
                }
                $v = Sgmov_Component_Validator::createSingleValueValidator($val)->isInteger(1)->isLengthLessThanOrEqualTo(3)->isWebSystemNg();
                if (!$v->isValid()) {
                    if ($isFirst) {
                        $messageErr = "宅配数量-往路".$v->getResultMessageTop();
                    } else {
                        if ($inForm->comiket_detail_type_sel2 == '3') {
                            $messageErr = !empty($messageErr) ? "宅配数量".$v->getResultMessageTop() : "宅配数量-復路".$v->getResultMessageTop();
                        }
                    }
                }
                $isFirst = false;
            }
        }
        //合計40個
        if (empty($messageErr)) {
            $countCnt = 0;
            foreach($targetList as $key => $val) {
                // 0 は法人で使用するためとばす
                if($key == "0") {
                    continue;
                }
                $countCnt += $val;
            }
            if ($countCnt > 40) {
                $messageErr = "宅配合計は40個まで入力可能としてください。";
            }
        }
        if (!empty($messageErr)) {
            $errorForm->addError($targetClassName, $messageErr);
        }
    }
}