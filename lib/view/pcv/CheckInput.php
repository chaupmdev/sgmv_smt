<?php
/**
 * @package    ClassDefFile
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('pcv/Common');
Sgmov_Lib::useForms(array('Error', 'PcvSession', 'Pcv001In'));
/**#@-*/

 /**
 * 法人オフィス移転入力情報をチェックします。
 * @package    View
 * @subpackage PCV
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pcv_CheckInput extends Sgmov_View_Pcv_Common
{
    /**
     * 都道府県サービス
     * @var Sgmov_Service_Prefecture
     */
    public $_PrefectureService;
    /**
     * 拠点・エリアサービス
     * @var Sgmov_Service_CenterArea
     */
    public $_centerAreaService;
    /**
     * 郵便・住所サービス
     * @var Sgmov_Service_Yubin
     */
    public $_YubinService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        $this->_centerAreaService = new Sgmov_Service_CenterArea();
        $this->_YubinService = new Sgmov_Service_Yubin();
        $this->_PrefectureService = new Sgmov_Service_Prefecture();
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
     *   pcv/confirm へリダイレクト
     *   </li></ol>
     * </li><li>
     * 入力エラー有り
     *   <ol><li>
     *   pcv/input へリダイレクト
     *   </li></ol>
     * </li></ol>
     */
    public function executeInner()
    {
        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();

        // DB接続
        $db = Sgmov_Component_DB::getPublic();
        $db_yubin = Sgmov_Component_DB::getYubinPublic();

        // 出発エリア・到着エリアのリストを取得しておく
        $toAreas = $this->_centerAreaService->fetchToAreaList($db);
        $fromAreas = $this->_centerAreaService->fetchFromAreaList($db);
        $pref = $this->_PrefectureService->fetchPrefectures($db);

        // チケットの確認と破棄
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_PCV001, $this->_getTicket());

        // 入力チェック
        $inForm = $this->_createInFormFromPost($_POST);
        $errorForm = $this->_validate($inForm, $toAreas['ids'], $fromAreas['ids'], $pref);

        // 情報をセッションに保存
        $sessionForm = new Sgmov_Form_PcvSession();
        $sessionForm->in = $inForm;
        $sessionForm->error = $errorForm;
        if ($errorForm->hasError()) {
            $sessionForm->status = self::VALIDATION_FAILED;
        } else {
            $sessionForm->status = self::VALIDATION_SUCCEEDED;
        }
        $session->saveForm(self::FEATURE_ID, $sessionForm);

        // リダイレクト
        if ($errorForm->hasError()) {
            Sgmov_Component_Redirect::redirectPublicSsl('/pcv/input/');
        } else {
            Sgmov_Component_Redirect::redirectPublicSsl('/pcv/confirm/');
        }
    }

    /**
     * POST値からチケットを取得します。
     * チケットが存在しない場合は空文字列を返します。
     * @return string チケット文字列
     */
    public function _getTicket()
    {
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
     * @return Sgmov_Form_Pcv001In 入力フォーム
     */
    public function _createInFormFromPost($post)
    {
        $inForm = new Sgmov_Form_Pcv001In();
        $inForm->ticket = $post['ticket'];
        $inForm->company_name = $post['company_name'];
        $inForm->company_furigana = $post['company_furigana'];
        $inForm->charge_name = $post['charge_name'];
        $inForm->charge_furigana = $post['charge_furigana'];
        $inForm->tel1 = $post['tel1'];
        $inForm->tel2 = $post['tel2'];
        $inForm->tel3 = $post['tel3'];
        if (isset($post['tel_type_cd_sel'])) {
            $inForm->tel_type_cd_sel = $post['tel_type_cd_sel'];
        } else {
            $inForm->tel_type_cd_sel = '';
        }
        $inForm->tel_other = $post['tel_other'];
        $inForm->mail = $post['mail'];
        if (isset($post['contact_method_cd_sel'])) {
            $inForm->contact_method_cd_sel = $post['contact_method_cd_sel'];
        } else {
            $inForm->contact_method_cd_sel = '';
        }
        if (isset($post['contact_available_cd_sel'])) {
            $inForm->contact_available_cd_sel = $post['contact_available_cd_sel'];
        } else {
            $inForm->contact_available_cd_sel = '';
        }
        $inForm->contact_start_cd_sel = $post['contact_start_cd_sel'];
        $inForm->contact_end_cd_sel = $post['contact_end_cd_sel'];
        $inForm->from_area_cd_sel = $post['from_area_cd_sel'];
        $inForm->to_area_cd_sel = $post['to_area_cd_sel'];
        $inForm->move_date_year_cd_sel = $post['move_date_year_cd_sel'];
        $inForm->move_date_month_cd_sel = $post['move_date_month_cd_sel'];
        $inForm->move_date_day_cd_sel = $post['move_date_day_cd_sel'];
        $inForm->visit_date1_year_cd_sel = $post['visit_date1_year_cd_sel'];
        $inForm->visit_date1_month_cd_sel = $post['visit_date1_month_cd_sel'];
        $inForm->visit_date1_day_cd_sel = $post['visit_date1_day_cd_sel'];
        $inForm->visit_date2_year_cd_sel = $post['visit_date2_year_cd_sel'];
        $inForm->visit_date2_month_cd_sel = $post['visit_date2_month_cd_sel'];
        $inForm->visit_date2_day_cd_sel = $post['visit_date2_day_cd_sel'];
        $inForm->cur_zip1 = $post['cur_zip1'];
        $inForm->cur_zip2 = $post['cur_zip2'];
        $inForm->cur_pref_cd_sel = $post['cur_pref_cd_sel'];
        $inForm->cur_address = $post['cur_address'];
        if (isset($post['cur_elevator_cd_sel'])) {
            $inForm->cur_elevator_cd_sel = $post['cur_elevator_cd_sel'];
        } else {
            $inForm->cur_elevator_cd_sel = "";
        }
        $inForm->cur_floor = $post['cur_floor'];
        if (isset($post['cur_road_cd_sel'])) {
            $inForm->cur_road_cd_sel = $post['cur_road_cd_sel'];
        } else {
            $inForm->cur_road_cd_sel = "";
        }
        $inForm->new_zip1 = $post['new_zip1'];
        $inForm->new_zip2 = $post['new_zip2'];
        $inForm->new_pref_cd_sel = $post['new_pref_cd_sel'];
        $inForm->new_address = $post['new_address'];
        if (isset($post['new_elevator_cd_sel'])) {
            $inForm->new_elevator_cd_sel = $post['new_elevator_cd_sel'];
        } else {
            $inForm->new_elevator_cd_sel = "";
        }
        $inForm->new_floor = $post['new_floor'];
        if (isset($post['new_road_cd_sel'])) {
            $inForm->new_road_cd_sel = $post['new_road_cd_sel'];
        } else {
            $inForm->new_road_cd_sel = "";
        }
        $inForm->number_of_people = $post['number_of_people'];
        $inForm->tsubo_su = $post['tsubo_su'];
        $inForm->comment = $post['comment'];

        return $inForm;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_Pcv001In $inForm 入力フォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($inForm, $toAreaCds, $fromAreaCds, $pref)
    {

        // 入力チェック
        $errorForm = new Sgmov_Form_Error();
        $min = strtotime(date('Ymd', strtotime('+1 week')));
        $max = strtotime(date('Ymd', strtotime('+6 month')));

        // 会社名 必須チェック 30文字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->company_name)->isNotEmpty()->isLengthLessThanOrEqualTo(30)->

            isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_company_name', $v->getResultMessageTop());
        }
        // 会社名フリガナ 必須チェック 30文字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->company_furigana)->isNotEmpty()->isLengthLessThanOrEqualTo(30)->

            isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_company_furigana', $v->getResultMessageTop());
        }
        // 担当者名 必須チェック 30文字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->charge_name)->isNotEmpty()->isLengthLessThanOrEqualTo(30)->

            isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_charge_name', $v->getResultMessageTop());
        }
        // 担当者名フリガナ 必須チェック 30文字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->charge_furigana)->isNotEmpty()->isLengthLessThanOrEqualTo(30)->

            isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_charge_furigana', $v->getResultMessageTop());
        }
        // 電話番号 必須チェック 型チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createPhoneValidator($inForm->tel1, $inForm->tel2, $inForm->tel3)->isNotEmpty()->

            isPhone()->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_tel', $v->getResultMessageTop());
        }
        // 電話番号種類 値範囲チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->tel_type_cd_sel);
        $v->isIn(array_keys($this->tel_type_lbls));
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        // 電話種類コード選択値　必須チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->tel_type_cd_sel)->isNotEmpty();
        if (!$v->isValid()) {
            $errorForm->addError('top_tel_type_cd_sel', $v->getResultMessageTop());
        }
        // 電話番号種類その他 20文字チェック 数字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->tel_other)->isInteger()->isLengthLessThanOrEqualTo(20)->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_tel_other', $v->getResultMessageTop());
        }
        // メールアドレス 必須チェック 80文字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->mail)->isNotEmpty()->isMail()->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_mail', $v->getResultMessageTop());
        }
        $v->isLengthLessThanOrEqualTo(80);
        if (!$v->isValid()) {
            $errorForm->addError('top_mail', $v->getResultMessageTop());
        }
        // 連絡方法 値範囲チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->contact_method_cd_sel);
        $v->isIn(array_keys($this->contact_method_lbls));
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        // 電話連絡可能時間（終日||時間指定） 値範囲チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->contact_available_cd_sel);
        $v->isIn(array_keys($this->contact_available_lbls));
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        // 電話連絡可能開始時間 値範囲チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->contact_start_cd_sel);
        $v->isIn(array_keys($this->contact_start_lbls));
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        // 電話連絡可能開始時間 値範囲チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->contact_end_cd_sel);
        $v->isIn(array_keys($this->contact_end_lbls));
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        // 現在お住まいの地域 値範囲チェック 必須チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->from_area_cd_sel);
        $v->isIn($fromAreaCds);
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        $v->isSelected();
        if (!$v->isValid()) {
            $errorForm->addError('top_from_area_cd_sel', $v->getResultMessageTop());
        }
        // お引越し先の地域 値範囲チェック 必須チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->to_area_cd_sel);
        $v->isIn($toAreaCds);
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        $v->isSelected();
        if (!$v->isValid()) {
            $errorForm->addError('top_to_area_cd_sel', $v->getResultMessageTop());
        }
        // お引越し予定日 有効日チェック
        $v = Sgmov_Component_Validator::createDateValidator($inForm->move_date_year_cd_sel, $inForm->move_date_month_cd_sel,

             $inForm->move_date_day_cd_sel);
        $v->isDate($min, $max);
        if (!$v->isValid()) {
            $errorForm->addError('top_move_date', $v->getResultMessageTop());
        }
        if ($inForm->move_date_year_cd_sel !== "" && $inForm->move_date_month_cd_sel !== "" && $inForm->move_date_day_cd_sel !== "") {
            $move_date_flag = 1;
        } else {
            $move_date_flag = "";
        }
        // 訪問見積もり希望日時第一希望日 有効日チェック 訪問希望日1 < 予定日
        $v = Sgmov_Component_Validator::createDateValidator($inForm->visit_date1_year_cd_sel, $inForm->visit_date1_month_cd_sel,

             $inForm->visit_date1_day_cd_sel);
        $v->isDate($min, $max);
        if (!$v->isValid()) {
            $errorForm->addError('top_visit_date1', $v->getResultMessageTop());
        }
        if ($inForm->visit_date1_year_cd_sel !== "" && $inForm->visit_date1_month_cd_sel !== "" && $inForm->visit_date1_day_cd_sel !== "") {
            $visit_date1_flag = 1;
        } else {
            $visit_date1_flag = "";
        }
        // 有効日で引越し予定日と第一希望日に入力があれば訪問希望日1 < 予定日
        if (!$errorForm->hasError('top_visit_date1') && $move_date_flag === 1 && $visit_date1_flag === 1) {
            $move = mktime(0, 0, 0, $inForm->move_date_month_cd_sel, $inForm->move_date_day_cd_sel, $inForm->move_date_year_cd_sel);
            $visit1 = mktime(0, 0, 0, $inForm->visit_date1_month_cd_sel, $inForm->visit_date1_day_cd_sel, $inForm->visit_date1_year_cd_sel);
            if ($move <= $visit1) {
                $errorForm->addError('top_visit_date1', 'はお引越し予定日以前にしてください。');
            }
        }
        // 訪問見積もり希望日時第二希望日 有効日チェック 訪問希望日2 < 予定日
        $v = Sgmov_Component_Validator::createDateValidator($inForm->visit_date2_year_cd_sel, $inForm->visit_date2_month_cd_sel,

             $inForm->visit_date2_day_cd_sel);
        $v->isDate($min, $max);
        if (!$v->isValid()) {
            $errorForm->addError('top_visit_date2', $v->getResultMessageTop());
        }
        if ($inForm->visit_date2_year_cd_sel !== "" && $inForm->visit_date2_month_cd_sel !== "" && $inForm->visit_date2_day_cd_sel !== "") {
            $visit_date2_flag = 1;
        } else {
            $visit_date2_flag = "";
        }
        // 有効日で引越し予定日と第二希望日に入力があれば訪問希望日2 < 予定日
        if (!$errorForm->hasError('top_visit_date2') && $move_date_flag === 1 && $visit_date2_flag === 1) {
            $move = mktime(0, 0, 0, $inForm->move_date_month_cd_sel, $inForm->move_date_day_cd_sel, $inForm->move_date_year_cd_sel);
            $visit2 = mktime(0, 0, 0, $inForm->visit_date2_month_cd_sel, $inForm->visit_date2_day_cd_sel, $inForm->visit_date2_year_cd_sel);
            if ($move <= $visit2) {
                $errorForm->addError('top_visit_date2', 'はお引越し予定日以前にしてください。');
            }
        }
        // 現住所郵便番号(最後に存在確認をするので別名でバリデータを作成) 必須 WEBシステムNG文字チェック
        $cur_zipV = Sgmov_Component_Validator::createZipValidator($inForm->cur_zip1, $inForm->cur_zip2)->isNotEmpty()->isZipCode()->

            isWebSystemNg();
        if (!$cur_zipV->isValid()) {
            $errorForm->addError('top_cur_zip', $cur_zipV->getResultMessage());
        }
        // 現住所「都道府県」値範囲チェック 必須チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->cur_pref_cd_sel);
        $v->isIn($pref['ids']);
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        $v->isSelected();
        if (!$v->isValid()) {
            $errorForm->addError('top_cur_pref_cd_sel', $v->getResultMessageTop());
        }
        // 現住所「住所」必須チェック 40文字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->cur_address)->isNotEmpty()->isLengthLessThanOrEqualTo(40)->

            isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_cur_address', $v->getResultMessageTop());
        }
        // 現住所補足情報「エレベーター」値範囲チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->cur_elevator_cd_sel);
        $v->isIn(array_keys($this->elevator_lbls));
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        // 現住所補足情報「階数」3桁 半角数値チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->cur_floor)->isInteger()->isLengthLessThanOrEqualTo(2)->

            isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_cur_floor', $v->getResultMessageTop());
        }
        // 現住所補足情報「住居前道幅」値範囲チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->cur_road_cd_sel);
        $v->isIn(array_keys($this->road_lbls));
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        // 新住所郵便番号(最後に存在確認をするので別名でバリデータを作成) WEBシステムNG文字チェック
        $new_zipV = Sgmov_Component_Validator::createZipValidator($inForm->new_zip1, $inForm->new_zip2)->isZipCode()->isWebSystemNg()->

            isWebSystemNg();
        if (!$new_zipV->isValid()) {
            $errorForm->addError('top_new_zip', $new_zipV->getResultMessage());
        }
        // 新住所「都道府県」値範囲チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->new_pref_cd_sel);
        $v->isIn($pref['ids']);
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        // 新住所「住所」40文字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->new_address)->isLengthLessThanOrEqualTo(40)->

            isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_new_address', $v->getResultMessageTop());
        }
        // 新住所補足情報「エレベーター」値範囲チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->new_elevator_cd_sel);
        $v->isIn(array_keys($this->elevator_lbls));
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        // 新住所補足情報「階数」3桁 半角数値チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->new_floor)->isInteger()->isLengthLessThanOrEqualTo(2)->

            isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_new_floor', $v->getResultMessageTop());
        }
        // 新住所補足情報「住居前道幅」値範囲チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->new_road_cd_sel);
        $v->isIn(array_keys($this->road_lbls));
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        // 移動人数 半角数値チェック 10文字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->number_of_people)->isInteger(0, 1000000000)->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_number_of_people', $v->getResultMessageTop());
        }
        // フロア坪数 半角数値チェック 10文字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->tsubo_su)->isInteger(0, 1000000000)->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_tsubo_su', $v->getResultMessageTop());
        }
        // 備考 300文字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comment);
        $v->isLengthLessThanOrEqualTo(300)->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_comment', $v->getResultMessageTop());
        }

        // エラーがない場合は現住所郵便番号存在チェック
        if (!$errorForm->hasError()) {
            $cur_zipV->zipCodeExist();
            if (!$cur_zipV->isValid()) {
                $errorForm->addError('top_cur_zip', $cur_zipV->getResultMessage());
            }
        }
        // エラーがない場合は新住所郵便番号存在チェック
        if (!$errorForm->hasError()) {
            $new_zipV->zipCodeExist();
            if (!$new_zipV->isValid()) {
                $errorForm->addError('top_new_zip', $new_zipV->getResultMessage());
            }
        }
        
        // エラーがない場合はメールアドレスドメイン確認
        $spamMailDomainList = Sgmov_Component_Config::getSpamMailDomainList();
        foreach ($spamMailDomainList as $key => $val) {
            if (!$errorForm->hasError() 
                    && @strpos($inForm->mail, "@{$val}") !== false) {
                // メールアドレスに @qq.comが含まれているかどうか
                $errorForm->addError('top_mail', "は、@{$val}はご利用できません。");
            }
        }

        return $errorForm;
    }
}
?>
