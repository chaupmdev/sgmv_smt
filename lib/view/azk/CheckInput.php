<?php
/**#@+
 * include files
 */
require_once dirname(__FILE__).'/../../Lib.php';
Sgmov_Lib::useView('azk/Common');
Sgmov_Lib::useForms(array('Error', 'AzkSession', 'Azk001In', 'Azk002In'));
Sgmov_Lib::useServices(array('HttpsZipCodeDll'));
/**#@-*/
/**
 * 旅客手荷物受付サービスのお申し込み入力情報をチェックします。
 * @package    View
 * @subpackage AZK
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Azk_CheckInput extends Sgmov_View_Azk_Common {

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
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_AZK001, $this->_getTicket());

        // 入力チェック
        $sessionForm = $session->loadForm(self::FEATURE_ID);

        if (empty($sessionForm)) {
            $sessionForm = new Sgmov_Form_AzkSession();
            $sessionForm->in = null;
        }

        $inForm = $this->_createInFormFromPost($_POST, $sessionForm->in);

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
     *
     * @param type $inForm
     * @param type $errorForm
     */
    public function _redirectProc($inForm, $errorForm) {
        if ($errorForm->hasError()) {
            Sgmov_Component_Redirect::redirectPublicSsl('/azk/input/'.$inForm->shikibetsushi);
        }
        
        // 個人の場合は、クレジット・コンビニ支払で表示画面切り替え
        switch ($inForm->comiket_payment_method_cd_sel) {
            case '1': // コンビニ
                Sgmov_Component_Redirect::redirectPublicSsl('/azk/confirm');
                break;
            case '2': // クレジット
                Sgmov_Component_Redirect::redirectPublicSsl('/azk/credit_card');
                break;
            case '3': // 電子マネー
                Sgmov_Component_Redirect::redirectPublicSsl('/azk/confirm');
                break;
            case '4': // コンビニ後払い
                Sgmov_Component_Redirect::redirectPublicSsl('/azk/confirm');
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

        $inForm = new Sgmov_Form_Azk002In();
        $creditCardForm = (array)$creditCardForm;
        // チケット
        $inForm->ticket = filter_input(INPUT_POST, 'ticket');
        $inForm->input_mode = filter_input(INPUT_POST, 'input_mode');
        // イベント識別子
        $inForm->shikibetsushi = filter_input(INPUT_POST, 'shikibetsushi');

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
        // $inForm->comiket_zip1 = mb_convert_kana(filter_input(INPUT_POST, 'comiket_zip1'), 'rnask', 'UTF-8');
        // $inForm->comiket_zip2 = mb_convert_kana(filter_input(INPUT_POST, 'comiket_zip2'), 'rnask', 'UTF-8');
        // $inForm->comiket_pref_cd_sel = filter_input(INPUT_POST, 'comiket_pref_cd_sel');
        // $inForm->comiket_address = mb_convert_kana(filter_input(INPUT_POST, 'comiket_address'), 'RNASKV', 'UTF-8');
        // $inForm->comiket_building = mb_convert_kana(filter_input(INPUT_POST, 'comiket_building'), 'RNASKV', 'UTF-8');
        $inForm->comiket_tel = @str_replace(array('-', 'ー', '−', '―', '‐', 'ｰ'), "-", mb_convert_kana(filter_input(INPUT_POST, 'comiket_tel'), 'rnask', 'UTF-8'));
        $inForm->comiket_mail = mb_convert_kana(filter_input(INPUT_POST, 'comiket_mail'), 'rnask', 'UTF-8');
        $inForm->comiket_mail_retype = mb_convert_kana(filter_input(INPUT_POST, 'comiket_mail_retype'), 'rnask', 'UTF-8');

        // $inForm->comiket_booth_name = mb_convert_kana(filter_input(INPUT_POST, 'comiket_booth_name'), 'RNASKV', 'UTF-8');
        // $inForm->building_name_sel = filter_input(INPUT_POST, 'building_name_sel');
        // $inForm->building_name = filter_input(INPUT_POST, 'building_name');
        // $inForm->building_booth_position_sel = filter_input(INPUT_POST, 'building_booth_position_sel');
        // $inForm->building_booth_position = filter_input(INPUT_POST, 'building_booth_position');
        // $buildingNameInfo = $this->_BuildingService->fetchBuildingById($db, $inForm->building_booth_position_sel);
        // $inForm->building_name_sel = @$buildingNameInfo['cd'];

        //$inForm->comiket_booth_num = @filter_input(INPUT_POST, 'comiket_booth_num');
        //$inForm->comiket_staff_sei = mb_convert_kana(filter_input(INPUT_POST, 'comiket_staff_sei'), 'RNASKV', 'UTF-8');
        //$inForm->comiket_staff_mei = mb_convert_kana(filter_input(INPUT_POST, 'comiket_staff_mei'), 'RNASKV', 'UTF-8');
        $inForm->comiket_staff_sei_furi = mb_convert_kana(filter_input(INPUT_POST, 'comiket_staff_sei_furi'), 'RNASKV', 'UTF-8');
        $inForm->comiket_staff_mei_furi = mb_convert_kana(filter_input(INPUT_POST, 'comiket_staff_mei_furi'), 'RNASKV', 'UTF-8');
        $inForm->comiket_staff_tel = @str_replace(array('-', 'ー', '−', '―', '‐', 'ｰ'), "-", mb_convert_kana(filter_input(INPUT_POST, 'comiket_staff_tel'), 'rnask', 'UTF-8'));

        /** コミケ申込明細データ **/
        $inForm->comiket_detail_type_sel = filter_input(INPUT_POST, 'comiket_detail_type_sel');
        $inForm->comiket_detail_azukari_kaisu_type_sel = filter_input(INPUT_POST, 'comiket_detail_azukari_kaisu_type_sel');
        
        // ブラウザでインスペクターより、取り出し回数を変更する場合は、デフォルト設定「１：一回のみ」を選択する
        if (empty($inForm->comiket_detail_azukari_kaisu_type_sel) || $inForm->comiket_detail_azukari_kaisu_type_sel != "1" ) {
            $inForm->comiket_detail_azukari_kaisu_type_sel = "1";
        }

        $inForm->comiket_detail_service_sel = filter_input(INPUT_POST, 'comiket_detail_service_sel');
        $inForm->comiket_detail_name = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_name'), 'RNASKV', 'UTF-8');
        // $inForm->comiket_detail_zip1 = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_zip1'), 'rnask', 'UTF-8');
        // $inForm->comiket_detail_zip2 = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_zip2'), 'rnask', 'UTF-8');
        // $inForm->comiket_detail_pref_cd_sel = filter_input(INPUT_POST, 'comiket_detail_pref_cd_sel');
        // $inForm->comiket_detail_address = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_address'), 'RNASKV', 'UTF-8');
        // $inForm->comiket_detail_building = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_building'), 'RNASKV', 'UTF-8');
        $inForm->comiket_detail_tel = @str_replace(array('-', 'ー', '−', '―', '‐', 'ｰ'), "-", mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_tel'), 'rnask', 'UTF-8'));
        $inForm->comiket_detail_collect_date_year_sel = filter_input(INPUT_POST, 'comiket_detail_collect_date_year_sel');
        $inForm->comiket_detail_collect_date_month_sel = filter_input(INPUT_POST, 'comiket_detail_collect_date_month_sel');
        $inForm->comiket_detail_collect_date_day_sel = filter_input(INPUT_POST, 'comiket_detail_collect_date_day_sel');
        // $inForm->comiket_detail_note1 = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_note1'), 'RNASKV', 'UTF-8');
        // $inForm->comiket_detail_note2 = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_note2'), 'RNASKV', 'UTF-8');
        // $inForm->comiket_detail_note3 = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_note3'), 'RNASKV', 'UTF-8');
        // $inForm->comiket_detail_note4 = mb_convert_kana(filter_input(INPUT_POST, 'comiket_detail_note4'), 'RNASKV', 'UTF-8');

        /** コミケットボックス **/
        $inForm->comiket_box_num_ary = $this->cstm_filter_input_array(INPUT_POST, 'comiket_box_num_ary', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY, 'rnask');


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


        // // お名前 姓
        // $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_personal_name_sei)->isNotEmpty()->isLengthLessThanOrEqualTo(8)->
        //         isNotHalfWidthKana()->isWebSystemNg();
        // if (!$v->isValid()) {
        //     $errorForm->addError('comiket_personal_name-seimei', 'お名前' . $v->getResultMessageTop());
        // }
        // // お名前 名 （法人の場合もあるため、必須チェックは外す）
        // $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_personal_name_mei)->isNotEmpty()->isLengthLessThanOrEqualTo(8)->
        //         isNotHalfWidthKana()->isWebSystemNg();
        // if (!$v->isValid()) {
        //     $errorForm->addError('comiket_personal_name-seimei', 'お名前' . $v->getResultMessageTop());
        // }

        // 担当者名-姓-フリガナ
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_personal_name_sei)->isNotEmpty()->isLengthLessThanOrEqualTo(8)->isAlphaCharactersOrFullWidthSquareJapaneseSyllabaryCharacters()->isNotHalfWidthKana()->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('comiket_personal_name-seimei', 'お申込者' . $v->getResultMessageTop());
        }
        
        // 担当者名-名-フリガナ
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_personal_name_mei)->isNotEmpty()->isLengthLessThanOrEqualTo(8)->isAlphaCharactersOrFullWidthSquareJapaneseSyllabaryCharacters()->isNotHalfWidthKana()->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('comiket_personal_name-seimei', 'お申込者' . $v->getResultMessageTop());
        }

        // // 郵便番号
        // // 郵便番号(最後に存在確認をするので別名でバリデータを作成) 必須チェック
        // $zipV = Sgmov_Component_Validator::createZipValidator($inForm->comiket_zip1, $inForm->comiket_zip2)->isNotEmpty()->isZipCode();
        // if (!$zipV->isValid()) {
        //     $errorForm->addError('comiket_zip', '郵便番号' . $zipV->getResultMessageTop());
        // }

        // // 都道府県
        // $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_pref_cd_sel)->isSelected()->isIn($prefectures['ids']);
        // if (!$v->isValid()) {
        //     $errorForm->addError('comiket_pref', '都道府県' . $v->getResultMessageTop());
        // }

        // // 市区町村 必須チェック 40文字チェック WEBシステムNG文字チェック
        // $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_address)->isNotEmpty()->isLengthLessThanOrEqualTo(14)->
        //         isNotHalfWidthKana()->isWebSystemNg();
        // if (!$v->isValid()) {
        //     $errorForm->addError('comiket_address', '市区町村' . $v->getResultMessageTop());
        // } else {
        //     $prefName = @$prefectures["names"][$prefectures["ids"][$inForm->comiket_pref_cd_sel]];
        //     if (strpos($inForm->comiket_address, $prefName) !== false) {
        //         $errorForm->addError('comiket_address', '市区町村には都道府県名は入力しないで下さい。');
        //     }
        // }

        // // 番地・建物名・部屋番号 必須チェック 40文字チェック WEBシステムNG文字チェック
        // $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_building)->isNotEmpty()->isLengthLessThanOrEqualTo(30)->
        //         isNotHalfWidthKana()->isWebSystemNg();
        // if (!$v->isValid()) {
        //     $errorForm->addError('comiket_building', '番地・建物名・部屋番号' . $v->getResultMessageTop());
        // } else {
        //     $prefName = @$prefectures["names"][$prefectures["ids"][$inForm->comiket_pref_cd_sel]];
        //     if (strpos($inForm->comiket_building, $prefName) !== false) {
        //         $errorForm->addError('comiket_building', '番地・建物名・部屋番号には都道府県名は入力しないで下さい。');
        //     }
        // }

        // 電話番号 必須チェック 型チェック WEBシステムNG文字チェック
        $comiketTel = @str_replace(array('-', 'ー', '−', '―', '‐', 'ｰ'), "", $inForm->comiket_tel);
        $v = Sgmov_Component_Validator::createSingleValueValidator($comiketTel)->isNotEmpty()->isPhoneHyphen();
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

        // // ブース名 必須チェック 16文字チェック
        // if(!empty($eventsubInfo) && $eventsubInfo['booth_display'] ==  '1') {
        //     $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_booth_name)->isNotEmpty()->isLengthLessThanOrEqualTo(16)->isWebSystemNg();
            
        //     if (!$v->isValid()) {
        //         $errorForm->addError('comiket_booth_name', 'ブース名' . $v->getResultMessageTop());
        //     }
        // }

        // if(!empty($eventsubInfo) && $eventsubInfo['building_display'] == '1') {
        //     // 館名
        //     $buildingNameInfoAry = $this->_BuildingService->fetchBuildingNameByEventsubId($db, $inForm->eventsub_sel);
        //     if ($inForm->event_sel != '2') {
        //         $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->building_name_sel)->isSelected()->isIn($buildingNameInfoAry['ids']);
        //         if (!$v->isValid()) {
        //             $errorForm->addError('building_name_sel', 'ブースNO' . $v->getResultMessageTop());
        //         }
        //     }

        //     if (!empty($inForm->building_name_sel)) {
        //         $checkFlg = FALSE;
        //         foreach($buildingNameInfoAry["list"] as $key => $val) {
        //             if($val["cd"] == $inForm->building_name_sel) {
        //                 $v = Sgmov_Component_Validator::createSingleValueValidator($val["name"])->isLengthLessThanOrEqualTo(16);
        //                 if(!$v->isValid()) {
        //                     $errorForm->addError('building_name_sel', 'ブースNO' . $v->getResultMessageTop());
        //                 }
        //                 $checkFlg = TRUE;
        //                 break;
        //             }
        //         }

        //         if(!$checkFlg) {
        //             $errorForm->addError('building_name_sel', '選択ブースNOに該当するデータが存在しません。');
        //         }
        //     }

        //     // ブース位置
        //     // ブース位置の未設定はエラーにしない
        //     $buildingBoothPostionInfoAry = $this->_BuildingService->fetchBuildingByEventId($db, $inForm->eventsub_sel);
        //     $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->building_booth_position_sel)->isNotEmpty()->isSelected()->isIn($buildingBoothPostionInfoAry['ids']);
        //     if (!$v->isValid()) {
        //             $errorForm->addError('building_name_sel', 'ブースNO' . $v->getResultMessageTop());
        //     } 


        //     // ブース番号-テキスト
        //     $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_booth_num)->isNotEmpty()->isLengthLessThanOrEqualTo(4)->isInteger(0)->isNotHalfWidthKana()->isWebSystemNg();
        //     if (!$v->isValid()) {
        //         $errorForm->addError('building_name_sel', 'ブースNO' . $v->getResultMessageTop());
        //     } 
        // }

        // 担当者名-姓
        // $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_staff_sei)->isNotEmpty()->isLengthLessThanOrEqualTo(8)->isNotHalfWidthKana()->isWebSystemNg();
        // if (!$v->isValid()) {
        //     $errorForm->addError('comiket_staff_seimei', '当日の担当者名' . $v->getResultMessageTop());
        // } 

        // // 担当者名-名
        // $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_staff_mei)->isNotEmpty()->isLengthLessThanOrEqualTo(8)->isNotHalfWidthKana()->isWebSystemNg();
        // if (!$v->isValid()) {
        //     $errorForm->addError('comiket_staff_seimei', '当日の担当者名' . $v->getResultMessageTop());
        // } 

        // 担当者名-姓-フリガナ
        // $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_staff_sei_furi)->isNotEmpty()->isLengthLessThanOrEqualTo(8)->isAlphaCharactersOrFullWidthSquareJapaneseSyllabaryCharacters()->isNotHalfWidthKana()->isWebSystemNg();
        // if (!$v->isValid()) {
        //     $errorForm->addError('comiket_staff_seimei_furi', 'ご利用者名' . $v->getResultMessageTop());
        // }
        
        // // 担当者名-名-フリガナ
        // $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_staff_mei_furi)->isNotEmpty()->isLengthLessThanOrEqualTo(8)->isAlphaCharactersOrFullWidthSquareJapaneseSyllabaryCharacters()->isNotHalfWidthKana()->isWebSystemNg();
        // if (!$v->isValid()) {
        //     $errorForm->addError('comiket_staff_seimei_furi', 'ご利用者名' . $v->getResultMessageTop());
        // }

        // // 担当者電話番号 必須チェック 型チェック WEBシステムNG文字チェック
        // $comiketStaffTell = @str_replace(array('-', 'ー', '−', '―', '‐', 'ｰ'), "", $inForm->comiket_staff_tel);
        // $v = Sgmov_Component_Validator::createSingleValueValidator($comiketStaffTell)->isNotEmpty()->isPhoneHyphen();
        // if (!$v->isValid()) {
        //     $errorForm->addError('comiket_staff_tel', '担当者電話番号' . $v->getResultMessageTop());
        // } else {
        //     $v = Sgmov_Component_Validator::createSingleValueValidator($comiketStaffTell)->isLengthMoreThanOrEqualTo(8)->isLengthLessThanOrEqualTo(12);
        //     if (!$v->isValid()) {
        //         $errorForm->addError('comiket_staff_tel', '担当者電話番号の数値部分' . $v->getResultMessageTop());
        //     }
        // }

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// コミケ宅配明細データ
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $this->comiketDetailValidate($inForm, $errorForm);

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// コミケ預かりデータ
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $this->comiketBoxValidate($inForm, $errorForm, $inForm->comiket_box_num_ary, "comiket_box_num");
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 支払方法
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $this->_checkPaymentMethod($inForm, $errorForm);
       
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        // // エラーがない場合はメールアドレス一致チェック
        // if (!$errorForm->hasError()) {

        //     // // 郵便番号と住所を確認する。
        //     // $aKey = array_search($inForm->comiket_pref_cd_sel, $prefectures['ids']);
        //     // $addressResult = $this->_getAddress($inForm->comiket_zip1.$inForm->comiket_zip2
        //     //         , $prefectures['names'][$aKey] . $inForm->comiket_address . $inForm->comiket_building);

        //     // if (empty($addressResult['ShopCodeFlag'])) {
        //     //     $errorForm->addError('comiket_zip', '住所の入力内容をお確かめください。');
        //     // }

        //     // 郵便番号と住所を確認する。
        //     $aKey = array_search($inForm->comiket_pref_cd_sel, $prefectures['ids']);

        //     $address = $prefectures['names'][$aKey] . $inForm->comiket_address . $inForm->comiket_building;
        //     $zipCode = $inForm->comiket_zip1 . $inForm->comiket_zip2;
        //     $addressResult = $this->_getByAddressWithZipCode($zipCode, $address);

        //     if (empty($addressResult)) {
        //         $errorForm->addError('comiket_zip', '住所の入力内容をお確かめください。');
        //     }

        //     // メールアドレス一致チェック
        //     $v = Sgmov_Component_Validator::createMultipleValueValidator(array($inForm->comiket_mail, $inForm->comiket_mail_retype))->isStringComparison();
        //     if (!$v->isValid()) {
        //         $errorForm->addError('comiket_mail_retype', 'メールアドレス確認' . $v->getResultMessageTop());
        //     }
        // }


        return $errorForm;
    }

    /**
     *
     * @param type $type
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


    public function comiketDetailValidate($inForm, &$errorForm) {
        // // 集荷先名
        // $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_detail_name)->isNotEmpty()->isLengthLessThanOrEqualTo(32)->
        //         isNotHalfWidthKana()->isWebSystemNg();
        // if (!$v->isValid()) {
        //     $errorForm->addError('comiket_detail_name', '氏名' . $v->getResultMessageTop());
        // }

        // // お届け先TEL 必須チェック 型チェック WEBシステムNG文字チェック
        // $comiektDetailTel = @str_replace(array('-', 'ー', '−', '―', '‐', 'ｰ'), "", $inForm->comiket_detail_tel);
        // $v = Sgmov_Component_Validator::createSingleValueValidator($comiektDetailTel)->isNotEmpty()->isPhoneHyphen();
        // if (!$v->isValid()) {
        //     $errorForm->addError('comiket_detail_tel', 'TEL' . $v->getResultMessageTop());
        // } else {
        //     $v = Sgmov_Component_Validator::createSingleValueValidator($comiektDetailTel)->isLengthMoreThanOrEqualTo(8)->isLengthLessThanOrEqualTo(12);
        //     if (!$v->isValid()) {
        //         $errorForm->addError('comiket_detail_tel', 'TELの数値部分' . $v->getResultMessageTop());
        //     }
        // }

        // お預かり日 必須チェック
        $v = Sgmov_Component_Validator::createDateValidator($inForm->comiket_detail_collect_date_year_sel,
                        $inForm->comiket_detail_collect_date_month_sel, $inForm->comiket_detail_collect_date_day_sel)->isNotEmpty();
        if (!$v->isValid()) {
            $errorForm->addError('comiket_detail_collect_date', '利用日' . $v->getResultMessageTop());
        } else {
            // TODO
            $current_date = new DateTime('today');
        }
      
    }

    /**
     *
     * @param type $inForm
     * @param type $errorForm
     */
    public function _checkPaymentMethod($inForm, &$errorForm) {
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
        // if ($inForm->comiket_payment_method_cd_sel === '1') {
        //     // お支払い店舗 必須チェック
        //     $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_convenience_store_cd_sel)->isSelected()->
        //             isIn(array_keys($this->convenience_store_lbls));
        //     if (!$v->isValid()) {
        //         $errorForm->addError('payment_method', 'お支払い方法' . $v->getResultMessageTop());
        //     }
        // }
    }

    /**
     *
     * @param type $inForm
     * @param type $errorForm
     * @param type $targetList
     * @param type $targetClassName
     * @param type $errMsgOutInbount
     */
    protected function comiketBoxValidate($inForm, &$errorForm, $targetList, $targetClassName, $isEmptyCheck = true, $fromSizeChange = false) {

        $totalCnt = 0;
        $notEmptyCount = 0;
        $errorFlg = false;
        foreach($targetList as $key => $val) {
           
            $v = Sgmov_Component_Validator::createSingleValueValidator($val)->isNotEmpty();

            if ($v->isValid()) {
                $notEmptyCount++;
            }

            $v = Sgmov_Component_Validator::createSingleValueValidator($val)->isInteger(1)->isLengthLessThanOrEqualTo(3)->isWebSystemNg();
            if (!$v->isValid()) {
                $errorFlg = true;
            }

            if(!$v->isValid() && $fromSizeChange && $val == "0"){
                $errorFlg = false;
            }

            $totalCnt += $val;
        }


        if(0 == $notEmptyCount && $isEmptyCheck) {
            $errorForm->addError($targetClassName, "手荷物数量を入力してください。");
        }

        $isErrFlg2 = false;
        if($errorFlg) {
            $errorForm->addError($targetClassName, "手荷物数量の入力値を確認してください。（数値のみ）");
            $isErrFlg2 = true;
        }

        // if(5 <= $notEmptyCount) {
        //     $errorForm->addError($targetClassName, "手荷物数量の入力は４つまでです。");
        //     $isErrFlg2 = TRUE;
        // }

        // 合計40個超える場合、エラー発生します。
        if(!$isErrFlg2 && $totalCnt > 40){
            $errorForm->addError($targetClassName, "手荷物数量合計は40個まで入力可能としてください。");
            $isErrFlg2 = TRUE;
        }

        if(!$isErrFlg2) {
            if (!empty($inForm->delivery_charge)) {
                if ($inForm->comiket_payment_method_cd_sel === '1' && intval($inForm->delivery_charge) > 299999) { // コンビニ前払い
                    $errorForm->addError($targetClassName, "手荷物数量コンビニ前払いの場合、送料は￥299,999までが取り扱い金額となります。");
                } else if($inForm->comiket_payment_method_cd_sel === '3' && intval($inForm->delivery_charge) > 10000) { // 電子マネー
                    $errorForm->addError($targetClassName, "手荷物数量電子マネーの場合、送料は￥10,000までが取り扱い金額となります。");
                } elseif (intval($inForm->delivery_charge) > 999999) {
                    $errorForm->addError($targetClassName, "手荷物数量送料は、￥999,999までが取り扱い金額となります。");
                }
            }
        }
    }


    // /**
    //  *
    //  * @param type $inForm
    //  * @param type $errorForm
    //  * @param type $targetList
    //  * @param type $targetClassName
    //  * @param type $errMsgOutInbount
    //  */
    // protected function checkComiketBoxOutInboundNumAry($inForm, &$errorForm, $targetList, $targetClassName, $errMsgOutInbount, $isEmptyCheck = true, $fromSizeChange = false) {
        
    //     $result = array(
    //         "errflg" => FALSE,
    //         "errData" => array(),
    //     );
    //     $notEmptyCount = 0;
    //     $errorFlg = FALSE;
    //     $totalCnt = 0;
    //     foreach($targetList as $key => $val) {
    //         // 0 は法人で使用するためとばす
    //         if($key == "0") {
    //             continue;
    //         }
    //         $v = Sgmov_Component_Validator::createSingleValueValidator($val)->isNotEmpty();

    //         if ($v->isValid()) {
    //             $notEmptyCount++;
    //         }

    //         $v = Sgmov_Component_Validator::createSingleValueValidator($val)->isInteger(1)->isLengthLessThanOrEqualTo(3)->isWebSystemNg();
    //         if (!$v->isValid()) {
    //             $errorFlg = TRUE;
    //         }

    //         if(!$v->isValid() && $fromSizeChange && $val == "0"){
    //             $errorFlg = FALSE;
    //         }

    //         $totalCnt += $val;
    //     }

    //     $isErrFlg2 = FALSE;
    //     if($errorFlg) {
    //         $errorForm->addError($targetClassName, "{$errMsgOutInbount}-宅配数量の入力値を確認してください。（数値のみ）");
    //         $isErrFlg2 = TRUE;
    //     }
    //     if(5 <= $notEmptyCount) {
    //         $errorForm->addError($targetClassName, "{$errMsgOutInbount}-宅配数量の入力は４つまでです。");
    //         $isErrFlg2 = TRUE;
    //     }
    //     if(0 == $notEmptyCount && $isEmptyCheck) {
    //         $errorForm->addError($targetClassName, "{$errMsgOutInbount}-宅配数量を入力してください。");
    //     }

    //     // 合計40個超える場合、エラー発生します。
    //     if(!$isErrFlg2 && $totalCnt > 40){
    //         $errorForm->addError($targetClassName, "{$errMsgOutInbount}-宅配合計は40個まで入力可能としてください。");
    //         $isErrFlg2 = TRUE;
    //     }

    //     if(!$isErrFlg2) {
    //         if (!empty($inForm->delivery_charge)) {
    //             if ($inForm->comiket_payment_method_cd_sel === '1' && intval($inForm->delivery_charge) > 299999) { // コンビニ前払い
    //                 $errorForm->addError($targetClassName, "{$errMsgOutInbount}-コンビニ前払いの場合、送料は￥299,999までが取り扱い金額となります。");
    //             } else if($inForm->comiket_payment_method_cd_sel === '3' && intval($inForm->delivery_charge) > 10000) { // 電子マネー
    //                 $errorForm->addError($targetClassName, "{$errMsgOutInbount}-電子マネーの場合、送料は￥10,000までが取り扱い金額となります。");
    //             } elseif (intval($inForm->delivery_charge) > 999999) {
    //                 $errorForm->addError($targetClassName, "{$errMsgOutInbount}-送料は、￥999,999までが取り扱い金額となります。");
    //             }
    //         }
    //     }
    // }
}