<?php
/**
 * @package    ClassDefFile
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/**#@+
 * include files
 */
require_once dirname(__FILE__).'/../../Lib.php';
Sgmov_Lib::useView('pct/Common');
Sgmov_Lib::useForms(array('Error', 'PctSession', 'Pcr001In'));
/**#@-*/
/**
 * 旅客手荷物受付サービスのお申し込み入力情報をチェックします。
 * @package    View
 * @subpackage PCR
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pct_CheckInput extends Sgmov_View_Pct_Common {

    /**
     * 都道府県サービス
     * @var Sgmov_Service_Prefecture
     */
    public $_PrefectureService;

    /**
     * ツアー会社サービス
     * @var Sgmov_Service_TravelAgency
     */
    private $_TravelAgencyService;

    /**
     * ツアーサービス
     * @var Sgmov_Service_Travel
     */
    private $_TravelService;

    /**
     * ツアー発着地サービス
     * @var Sgmov_Service_TravelTerminal
     */
    private $_TravelTerminalService;

    /**
     * ツアー配送料金エリアサービス
     * @var Sgmov_Service_TravelDeliveryChargeAreas
     */
    private $_TravelDeliveryChargeAreasService;

    /**
     * 郵便番号DLLサービス
     * @var Sgmov_Service_SocketZipCodeDll
     */
    public $_SocketZipCodeDll;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_PrefectureService                = new Sgmov_Service_Prefecture();
        $this->_TravelAgencyService              = new Sgmov_Service_TravelAgency();
        $this->_TravelService                    = new Sgmov_Service_Travel();
        $this->_TravelTerminalService            = new Sgmov_Service_TravelTerminal();
        $this->_TravelDeliveryChargeAreasService = new Sgmov_Service_TravelDeliveryChargeAreas();
        $this->_SocketZipCodeDll                 = new Sgmov_Service_SocketZipCodeDll();
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
        $featureId = self::FEATURE_ID;
        if (isset($_POST['req_flg']) && $_POST['req_flg'] == self::PCR_IVR_REQUEST){
            $featureId = self::FEATURE_ID_IVR;
        }
        $session->checkTicket($featureId, self::GAMEN_ID_PCR001, $this->_getTicket());

        // 入力チェック
        $sessionForm = $session->loadForm($featureId);
        if (empty($sessionForm)) {
            $sessionForm = new Sgmov_Form_PctSession();
            $sessionForm->in = null;
        }
        $inForm = $this->_createInFormFromPost($_POST, $sessionForm->in);
        $errorForm = $this->_validate($inForm, $db);

        // 情報をセッションに保存
        $sessionForm->in = $inForm;
        $sessionForm->error = $errorForm;
        if ($errorForm->hasError()) {
            $sessionForm->status = self::VALIDATION_FAILED;
        } else {
            $sessionForm->status = self::VALIDATION_SUCCEEDED;
        }
        $session->saveForm($featureId, $sessionForm);

        // リダイレクト
        if ($errorForm->hasError()) {
            if ($inForm->req_flg == '1') {
                Sgmov_Component_Redirect::redirectPublicSsl('/pct/input_call');
            } else {
                Sgmov_Component_Redirect::redirectPublicSsl('/pct/input');
            }
        }
        if ($inForm->req_flg == '1') {
            Sgmov_Component_Redirect::redirectPublicSsl('/pct/confirm_call');
        } else {
            switch ($inForm->payment_method_cd_sel) {
                case '1':
                     Sgmov_Component_Redirect::redirectPublicSsl('/pct/confirm');
                    break;
                case '2':
                    Sgmov_Component_Redirect::redirectPublicSsl('/pct/credit_card');
                    break;
            }
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
        $inForm = new Sgmov_Form_Pcr001In();

        // チケット
        $inForm->ticket = filter_input(INPUT_POST, 'ticket');
        $inForm->req_flg = filter_input(INPUT_POST, 'req_flg');
        if ($inForm->req_flg == self::PCR_IVR_REQUEST) { 
            $inForm->surname  = '';
            $inForm->forename = '';
        } else {
            $inForm->surname  = mb_convert_kana(filter_input(INPUT_POST, 'surname'), 'RNASKV', 'UTF-8');
            $inForm->forename = mb_convert_kana(filter_input(INPUT_POST, 'forename'), 'RNASKV', 'UTF-8');
        }
        
        $inForm->surname_furigana  = filter_input(INPUT_POST, 'surname_furigana');
        $inForm->forename_furigana = filter_input(INPUT_POST, 'forename_furigana');
        $inForm->number_persons = filter_input(INPUT_POST, 'number_persons');

        $inForm->tel1 = filter_input(INPUT_POST, 'tel1');
        $inForm->tel2 = filter_input(INPUT_POST, 'tel2');
        $inForm->tel3 = filter_input(INPUT_POST, 'tel3');
        
        $inForm->mail        = filter_input(INPUT_POST, 'mail');
        $inForm->retype_mail = filter_input(INPUT_POST, 'retype_mail');

        $inForm->zip1        = filter_input(INPUT_POST, 'zip1');
        $inForm->zip2        = filter_input(INPUT_POST, 'zip2');
        $inForm->pref_cd_sel = filter_input(INPUT_POST, 'pref_cd_sel');
        $inForm->address     = filter_input(INPUT_POST, 'address');
        $inForm->building    = filter_input(INPUT_POST, 'building');

        $inForm->travel_agency_cd_sel    = filter_input(INPUT_POST, 'travel_agency_cd_sel');
        $inForm->call_operator_id_cd_sel    = filter_input(INPUT_POST, 'call_operator_id_cd_sel');
        
        $inForm->travel_cd_sel           = filter_input(INPUT_POST, 'travel_cd_sel');
        $inForm->room_number             = filter_input(INPUT_POST, 'room_number');
        $inForm->terminal_cd_sel         = filter_input(INPUT_POST, 'terminal_cd_sel');
        $inForm->departure_quantity      = filter_input(INPUT_POST, 'departure_quantity');
        $inForm->arrival_quantity        = filter_input(INPUT_POST, 'arrival_quantity');
        $inForm->travel_departure_cd_sel = filter_input(INPUT_POST, 'travel_departure_cd_sel');

        $inForm->cargo_collection_date_year_cd_sel  = filter_input(INPUT_POST, 'cargo_collection_date_year_cd_sel');
        $inForm->cargo_collection_date_month_cd_sel = filter_input(INPUT_POST, 'cargo_collection_date_month_cd_sel');
        $inForm->cargo_collection_date_day_cd_sel   = filter_input(INPUT_POST, 'cargo_collection_date_day_cd_sel');
        $inForm->cargo_collection_st_time_cd_sel    = filter_input(INPUT_POST, 'cargo_collection_st_time_cd_sel');
        //$inForm->cargo_collection_st_minute_cd_sel  = filter_input(INPUT_POST, 'cargo_collection_st_minute_cd_sel');
        if (isset($post['cargo_collection_st_time_cd_sel']) && isset($this->cargo_collection_ed_time_lbls[$post['cargo_collection_st_time_cd_sel']])) {
            $inForm->cargo_collection_ed_time_cd_sel = trim($this->cargo_collection_ed_time_lbls[$post['cargo_collection_st_time_cd_sel']]);
        }
        //$inForm->cargo_collection_ed_minute_cd_sel  = filter_input(INPUT_POST, 'cargo_collection_ed_minute_cd_sel');

        $inForm->travel_arrival_cd_sel = filter_input(INPUT_POST, 'travel_arrival_cd_sel');

        //$inForm->delivery_day_year_cd_sel  = filter_input(INPUT_POST, 'delivery_day_year_cd_sel');
        //$inForm->delivery_day_month_cd_sel = filter_input(INPUT_POST, 'delivery_day_month_cd_sel');
        //$inForm->delivery_day_day_cd_sel   = filter_input(INPUT_POST, 'delivery_day_day_cd_sel');
        //$inForm->delivery_time_cd_sel      = filter_input(INPUT_POST, 'delivery_time_cd_sel');
        //$inForm->delivery_minute_cd_sel    = filter_input(INPUT_POST, 'delivery_minute_cd_sel');

        $inForm->payment_method_cd_sel    = filter_input(INPUT_POST, 'payment_method_cd_sel');
        $inForm->convenience_store_cd_sel = filter_input(INPUT_POST, 'convenience_store_cd_sel');
         
        // TODO オブジェクトから値を直接取得できるよう修正する
        // オブジェクトから取得できないため、配列に型変換
        $creditCardForm = (array)$creditCardForm;

        $inForm->card_number = isset($creditCardForm['card_number']) ? $creditCardForm['card_number'] : '';
        $inForm->card_expire_month_cd_sel = isset($creditCardForm['card_expire_month_cd_sel']) ? $creditCardForm['card_expire_month_cd_sel'] : '';
        $inForm->card_expire_year_cd_sel = isset($creditCardForm['card_expire_year_cd_sel']) ? $creditCardForm['card_expire_year_cd_sel'] : '';
        $inForm->security_cd = isset($creditCardForm['security_cd']) ? $creditCardForm['security_cd'] : '';
        
        //$inForm->chb_agreement = $post['chb_agreement'];// filter_input(INPUT_POST, 'chb_agreement');
        $inForm->chb_agreement = isset($post['chb_agreement']) ? $post['chb_agreement'] : "";// filter_input(INPUT_POST, 'chb_agreement');

        return $inForm;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_Pcr001In $inForm 入力フォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($inForm, $db) {
        // 都道府県のリストを取得しておく
        $prefectures = $this->_PrefectureService->fetchPrefectures($db);
        $convenience = ($inForm->payment_method_cd_sel === '1');
        $travelAgency = $this->_TravelAgencyService->fetchTravelAgency($db, $inForm->req_flg, self::SITE_FLAG, $convenience);
        $travel = array('ids' => array(),);
        if (!empty($inForm->travel_agency_cd_sel)) {
            
            $travel = $this->_TravelService->fetchTravel($db, array('travel_agency_id' => $inForm->travel_agency_cd_sel), $inForm->req_flg, self::SITE_FLAG);
            $travel_convenience = $this->_TravelService->fetchTravel($db, array('travel_agency_id' => $inForm->travel_agency_cd_sel),$inForm->req_flg, self::SITE_FLAG, $convenience);
        }
        $departure = array('ids' => array(),);
        $arrival   = array('ids' => array(),);
        if (!empty($inForm->travel_cd_sel)) {
            $departure = $this->_TravelTerminalService->fetchTravelDeparture($db, array('travel_id' => $inForm->travel_cd_sel), $inForm->req_flg, self::SITE_FLAG);
            $arrival   = $this->_TravelTerminalService->fetchTravelArrival($db, array('travel_id' => $inForm->travel_cd_sel), $inForm->req_flg, self::SITE_FLAG);
        }
        //$hour = $this->_fetchTime(0, 23);
        //$cargo_collection_st_time_cds = $hour['ids'];
        //$cargo_collection_ed_time_cds = $hour['ids'];
        $cargo_collection_st_time_cds = array_keys($this->cargo_collection_st_time_lbls);
        $cargo_collection_ed_time_cds = array_values($this->cargo_collection_ed_time_lbls);

        // 入力チェック
        $errorForm = new Sgmov_Form_Error();

        // お名前 姓 必須チェック 30文字チェック WEBシステムNG文字チェック
        if ($inForm->req_flg == self::PCR_WEB_REQUEST) {
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->surname)->isNotEmpty()->isLengthLessThanOrEqualTo(30)->
                    isNotHalfWidthKana()->isWebSystemNg();
            if (!$v->isValid()) {
                $errorForm->addError('top_surname', $v->getResultMessageTop());
            }
            // お名前 名 必須チェック 30文字チェック WEBシステムNG文字チェック
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->forename)->isNotEmpty()->isLengthLessThanOrEqualTo(30)->
                    isNotHalfWidthKana()->isWebSystemNg();
            if (!$v->isValid()) {
                $errorForm->addError('top_forename', $v->getResultMessageTop());
            }
        }
        
        // お名前フリガナ 姓 必須チェック 30文字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->surname_furigana)->isNotEmpty()->isLengthLessThanOrEqualTo(30)->
                isAlphaCharactersOrFullWidthSquareJapaneseSyllabaryCharacters()->isNotHalfWidthKana()->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_surname_furigana', $v->getResultMessageTop());
        }
        // お名前フリガナ 名 必須チェック 30文字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->forename_furigana)->isNotEmpty()->isLengthLessThanOrEqualTo(30)->
                isAlphaCharactersOrFullWidthSquareJapaneseSyllabaryCharacters()->isNotHalfWidthKana()->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_forename_furigana', $v->getResultMessageTop());
        }
        // 同行のご家族人数 必須チェック 3文字チェック 半角数値チェック WEBシステムNG文字チェック
//        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->number_persons)->isNotEmpty()->isInteger()->isLengthLessThanOrEqualTo(3)->isWebSystemNg();
//        if (!$v->isValid()) {
//            $errorForm->addError('top_number_persons', $v->getResultMessageTop());
//        }
        // 電話番号 必須チェック 型チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createPhoneValidator($inForm->tel1, $inForm->tel2, $inForm->tel3)->isNotEmpty()->isPhone()->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_tel', $v->getResultMessageTop());
        }
        //電話番号 11桁までチェック（12桁超える場合、NG）
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->tel1. $inForm->tel2. $inForm->tel3)->isNotEmpty()->isLengthLessThanOrEqualToForPhone();
        if (!$v->isValid()) {
            $errorForm->addError('top_tel', $v->getResultMessageTop());
        }
        
        // メールアドレス 必須チェック 80文字チェック
        //通常WEB申込又はコンビニ決済の場合、メール入力チェック必要となる
        if ($inForm->req_flg == self::PCR_WEB_REQUEST || $inForm->payment_method_cd_sel == '1' || !empty($inForm->mail) || !empty($inForm->retype_mail)) {
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->mail)->isNotEmpty()->isMail()->isLengthLessThanOrEqualTo(80)->isWebSystemNg();
            if (!$v->isValid()) {
                $errorForm->addError('top_mail', $v->getResultMessageTop());
            }
            // メールアドレス確認 必須チェック 80文字チェック
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->retype_mail)->isNotEmpty()->isMail()->isLengthLessThanOrEqualTo(80)->isWebSystemNg();
            if (!$v->isValid()) {
                $errorForm->addError('top_retype_mail', $v->getResultMessageTop());
            }
        }
        
        // 郵便番号(最後に存在確認をするので別名でバリデータを作成) 必須チェック
        $zipV = Sgmov_Component_Validator::createZipValidator($inForm->zip1, $inForm->zip2)->isNotEmpty()->isZipCode();
        if (!$zipV->isValid()) {
            $errorForm->addError('top_zip', $zipV->getResultMessageTop());
        }
        // 都道府県 値範囲チェック 必須チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->pref_cd_sel)->isSelected();
        if (!$v->isValid()) {
            $errorForm->addError('top_pref_cd_sel', $v->getResultMessageTop());
        }
        // 市区町村 必須チェック 40文字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->address)->isNotEmpty()->isLengthLessThanOrEqualTo(40)->
                isNotHalfWidthKana()->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_address', $v->getResultMessageTop());
        }
        // 番地・建物名 必須チェック 40文字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->building)->/*isNotEmpty()->*/isLengthLessThanOrEqualTo(40)->
                isNotHalfWidthKana()->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_building', $v->getResultMessageTop());
        }
        //コールセンター電話番号
        if ($inForm->req_flg == self::PCR_IVR_REQUEST) {
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->call_operator_id_cd_sel)->isSelected();
            if (!$v->isValid()) {
                $errorForm->addError('top_call_operator_id_cd_sel', $v->getResultMessageTop());
            }   
        }        
        $isTravelErr = FALSE;
        // 船名 必須チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->travel_agency_cd_sel)->isSelected()->isIn((array)$travelAgency['ids']);
        if (!$v->isValid()) {
            $errorForm->addError('top_travel_agency_cd_sel', $v->getResultMessageTop());
            $isTravelErr = TRUE;
        }
        // 乗船日 必須チェック
        $dateV = Sgmov_Component_Validator::createSingleValueValidator($inForm->travel_cd_sel)->isSelected()->isIn((array)$travel['ids']);
        if (!$dateV->isValid()) {
            $errorForm->addError('top_travel_cd_sel', $dateV->getResultMessageTop());
            $isTravelErr = TRUE;
        }

        // コンビニ支払い期限チェック(クレジットの場合はプルダウンにでてこないのでコンビニだけでOK)
        if($convenience && $isTravelErr) {
            $travelAgency2 = $this->_TravelAgencyService->fetchTravelAgency($db, $inForm->req_flg, self::SITE_FLAG);
            $v2 = Sgmov_Component_Validator::createSingleValueValidator($inForm->travel_agency_cd_sel)->isIn((array)$travelAgency2['ids']);
            if($v2->isValid()) {
                $errorForm->delError('top_travel_agency_cd_sel');
                $errorForm->delError('top_travel_cd_sel');
                $errorForm->addError('top_travel_cd_sel_convenience', 'コンビニ決済のご依頼受付期間は終了しています（乗船日の7日前まで）。現在はクレジット決済のみ受け付け可能です。');
            }
        }

        // 船内のお部屋番号 必須チェック 6文字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->room_number)->isNotEmpty()->
                isHalfWidthAlphaNumericCharacters()->isLengthLessThanOrEqualTo(6)->isNotHalfWidthKana()->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_room_number', $v->getResultMessageTop());
        }
        // 集荷の往復 必須チェック 値範囲チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->terminal_cd_sel)->isSelected()->isIn(array_keys($this->terminal_lbls));
        if (!$v->isValid()) {
            $errorForm->addError('top_terminal_cd_sel', $v->getResultMessageTop());
        }
        // TODO マジックナンバーを定数にする
        $checkDeparture = ((intval($inForm->terminal_cd_sel) & 1) === 1);
        $checkArrival   = ((intval($inForm->terminal_cd_sel) & 2) === 2);
        if ($checkDeparture) {
            // 配送荷物個数 往路 必須チェック 3文字チェック 半角数値チェック WEBシステムNG文字チェック
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->departure_quantity)->isNotEmpty()->
                    isInteger(1)->isLengthLessThanOrEqualTo(3)->isWebSystemNg();
            if (!$v->isValid()) {
                $errorForm->addError('top_departure_quantity', $v->getResultMessageTop());
            }
        }
        if ($checkArrival) {
            // 配送荷物個数 復路 必須チェック 3文字チェック 半角数値チェック WEBシステムNG文字チェック
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->arrival_quantity)->isNotEmpty()->
                    isInteger(1)->isLengthLessThanOrEqualTo(3)->isWebSystemNg();
            if (!$v->isValid()) {
                $errorForm->addError('top_arrival_quantity', $v->getResultMessageTop());
            }
        }
        if ($checkDeparture) {
            // 出発地 値範囲チェック 必須チェック
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->travel_departure_cd_sel)->isSelected()->isIn((array)$departure['ids']);
            if (!$v->isValid()) {
                $errorForm->addError('top_travel_departure_cd_sel', $v->getResultMessageTop());
            }
            // 集荷希望日付 必須チェック
            $v = Sgmov_Component_Validator::createDateValidator($inForm->cargo_collection_date_year_cd_sel,
                    $inForm->cargo_collection_date_month_cd_sel, $inForm->cargo_collection_date_day_cd_sel);
            if (empty($inForm->travel_departure_cd_sel) || empty($departure['dates'])) {
                $max = null;
                $max_year  = null;
                $max_month = null;
                $max_day   = null;
            } else {
                $date = new DateTime($departure['dates'][array_search($inForm->travel_departure_cd_sel, $departure['ids'])]);
                $dateStart = clone $date;
                
                $date->modify(self::COLLECT_DATE_END);
                $max_year  = intval($date->format('Y'));
                $max_month = intval($date->format('m'));
                $max_day   = intval($date->format('d'));
                $max  = intval($date->format('U'));
                
                $dateStart->modify(self::COLLECT_DATE_START);
                $min  = intval($dateStart->format('U'));
            }
            $current_date = new DateTime('tomorrow');
            $current_time = intval($current_date->format('U'));
            if (empty($min) || $min < $current_time) {
                $min = $current_time;
            }
            $v->isSelected()->isDate($min, $max);
            if (!$v->isValid()) {
                $errorForm->addError('top_cargo_collection_date', $v->getResultMessageTop());
            }
            
            // 集荷希望開始時刻 必須チェック
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->cargo_collection_st_time_cd_sel)->isSelected()->
                    isIn($cargo_collection_st_time_cds);
            $is_valid = $v->isValid();
            if (!$is_valid) {
                $errorForm->addError('top_cargo_collection_st_time', $v->getResultMessageTop());
            }
            if ($max_year === intval($inForm->cargo_collection_date_year_cd_sel)
                    && $max_month === intval($inForm->cargo_collection_date_month_cd_sel)
                    && $max_day === intval($inForm->cargo_collection_date_day_cd_sel)) {
                array_pop($cargo_collection_st_time_cds);
            }
            $v->isIn($cargo_collection_st_time_cds);
            if ($is_valid && !$v->isValid()) {
                $errorForm->addError('top_cargo_collection_st_time_last', '集荷可能期間の最終日のため、18時～20時は選択できません。');
            }
            // 集荷希望終了時刻 必須チェック
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->cargo_collection_ed_time_cd_sel)->isSelected()->
                    isIn($cargo_collection_ed_time_cds)->isInteger(intval($inForm->cargo_collection_st_time_cd_sel));
            $is_valid = $v->isValid();
            if (!$is_valid) {
                $errorForm->addError('top_cargo_collection_ed_time', $v->getResultMessageTop());
            }
            if ($max_year === intval($inForm->cargo_collection_date_year_cd_sel)
                    && $max_month === intval($inForm->cargo_collection_date_month_cd_sel)
                    && $max_day === intval($inForm->cargo_collection_date_day_cd_sel)) {
                array_pop($cargo_collection_ed_time_cds);
            }
            $v->isIn($cargo_collection_ed_time_cds);
            if ($is_valid && !$v->isValid()) {
                $errorForm->addError('top_cargo_collection_st_time_last', '集荷可能期間の最終日のため、18時～20時は選択できません。');
            }
        }
        if ($checkArrival) {
            // 到着地 値範囲チェック 必須チェック
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->travel_arrival_cd_sel)->isSelected()->isIn((array)$arrival['ids']);
            if (!$v->isValid()) {
                $errorForm->addError('top_travel_arrival_cd_sel', $v->getResultMessageTop());
            }
        }
        // お支払方法 値範囲チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->payment_method_cd_sel)->isIn(array_keys($this->payment_method_lbls));
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        // お支払方法 必須チェック
        $v->isSelected();
        if (!$v->isValid()) {
            $errorForm->addError('top_payment_method_cd_sel', $v->getResultMessageTop());
        }
        if ($inForm->payment_method_cd_sel === '1') {
            // お支払い店舗 必須チェック
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->convenience_store_cd_sel)->isSelected()->
                    isIn(array_keys($this->convenience_store_lbls));
            if (!$v->isValid()) {
                $errorForm->addError('top_convenience_store_cd_sel', $v->getResultMessageTop());
            }
        }
        // エラーがない場合はメールアドレス一致チェック
        if (!$errorForm->hasError()) {
            if ($inForm->req_flg == self::PCR_WEB_REQUEST || $inForm->payment_method_cd_sel === '1' || !empty($inForm->mail) || !empty($inForm->retype_mail)) {
                $v = Sgmov_Component_Validator::createMultipleValueValidator(array($inForm->mail, $inForm->retype_mail))->isStringComparison();
                if (!$v->isValid()) {
                    $errorForm->addError('top_mail', $v->getResultMessageTop());
                }
            }
        }

        // エラーがない場合は郵便番号・住所の存在チェック
        if (!$errorForm->hasError()) {
            $zipV->zipCodeExistSocket()->zipCodeCollectable();
            if (!$zipV->isValid()) {
                $errorForm->addError('top_zip', $zipV->getResultMessageTop());
            }
            $receive = $this->_getAddress($inForm, $prefectures);
            if (empty($receive['ShopCodeFlag'])) {
                $errorForm->addError('top_address', 'の入力内容をお確かめください。');
            } elseif (!empty($receive['ExchangeFlag'])) {
                $errorForm->addError('top_address', 'は集荷・配達できない地域の恐れがあります。');
            } elseif (!empty($receive['TimeZoneFlag']) && $inForm->cargo_collection_st_time_cd_sel !== '00') {
                $errorForm->addError('top_address', 'は時間帯指定できない地域の恐れがあります。');
            }
        }

        // エラーがない場合はコンビニ決済時の乗船日チェック
        if (!$errorForm->hasError() && $inForm->payment_method_cd_sel === '1') {
            $dateV->isIn((array)$travel_convenience['ids']);
            if (!$dateV->isValid()) {
                $errorForm->addError('top_travel_cd_sel_convenience', 'コンビニ決済のご依頼受付期間は終了しています（乗船日の7日前まで）。現在はクレジット決済のみ受け付け可能です。');
            }
        }

        // エラーがない場合はコンビニ決済の送料上限チェック
        if (!$errorForm->hasError() && $inForm->payment_method_cd_sel === '1') {
            $data = array('prefecture_id' => $inForm->pref_cd_sel);
            $departure_charge    = 0;
            $arrival_charge      = 0;
            $round_trip_discount = 0;
            $checkDeparture = ((intval($inForm->terminal_cd_sel) & 1) === 1);
            $checkArrival   = ((intval($inForm->terminal_cd_sel) & 2) === 2);
            $travelId = $inForm->travel_cd_sel;
            $travelInfo = $this->_TravelService->fetchTravelLimit($db, array('id' => $travelId));
            if ($checkDeparture) {
                if ($travelInfo['charge_flg'] == '1') {
                    $departure_charge = $this->_TravelDeliveryChargeAreasService->fetchDeliveryCharge($db, $data + array('travel_terminal_id' => $inForm->travel_departure_cd_sel), $inForm->req_flg);
                } else {
                    $departure_charge = $this->_TravelDeliveryChargeAreasService->fetchDeliveryChargeNewCharge($db, $data + array('travel_terminal_id' => $inForm->travel_departure_cd_sel), $inForm->req_flg);
                }
            }
            if ($checkArrival) {
                if ($travelInfo['charge_flg'] == '1') {
                    $arrival_charge = $this->_TravelDeliveryChargeAreasService->fetchDeliveryCharge($db, $data + array('travel_terminal_id' => $inForm->travel_arrival_cd_sel), $inForm->req_flg);
                } else {
                    $arrival_charge = $this->_TravelDeliveryChargeAreasService->fetchDeliveryChargeNewCharge($db, $data + array('travel_terminal_id' => $inForm->travel_arrival_cd_sel), $inForm->req_flg);
                }
            }
            if ($checkDeparture && $checkArrival) {
                $round_trip_discount = $this->_TravelService->fetchRoundTripDiscount($db, array('travel_id'=>$inForm->travel_cd_sel));
            }
            $delivery_charge = $departure_charge * intval($inForm->departure_quantity)
                + $arrival_charge * intval($inForm->arrival_quantity)
                - $round_trip_discount * min($inForm->departure_quantity, $inForm->arrival_quantity);
            // 30万円がコンビニ決済の上限支払額
            $v = Sgmov_Component_Validator::createSingleValueValidator(strval($delivery_charge))->isInteger(null, 300000);
            if (!$v->isValid()) {
                $errorForm->addError('top_payment_method_cd_sel_convenience', '送料が30万円を超えたため、コンビニ決済できません。クレジットカードでお支払いください。');
            }
        }

        return $errorForm;
    }

    /**
     * 住所情報を取得します。
     * @param Sgmov_Form_Pcr001In $inForm 入力フォーム
     * @return boolean
     */
    public function _getAddress($inForm, $prefectures) {
        $zip = $inForm->zip1 . $inForm->zip2;
        $address = $prefectures['names'][array_search($inForm->pref_cd_sel, $prefectures['ids'])] . $inForm->address . $inForm->building;
        return $this->_SocketZipCodeDll->searchByAddress($address, $zip);
    }
}