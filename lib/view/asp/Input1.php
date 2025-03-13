<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('asp/Common');
Sgmov_Lib::useServices(array('Login', 'Calendar', 'CoursePlan', 'SpecialPrice'));
Sgmov_Lib::useForms(array('Error', 'AspSession', 'Asp004Out', 'Asp002In', 'Asp004In', 'Asp005In', 'Asp006In', 'Asp008In',
     'Asp009In'));
/**#@-*/

 /**
 * 特価編集名称入力画面を表示します。
 * @package    View
 * @subpackage ASP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Asp_Input1 extends Sgmov_View_Asp_Common
{
    /**
     * ログインサービス
     * @var Sgmov_Service_Login
     */
    public $_loginService;

    /**
     * 特価サービス
     * @var Sgmov_Service_SpecialPrice
     */
    public $_specialPriceService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        $this->_loginService = new Sgmov_Service_Login();
        $this->_specialPriceService = new Sgmov_Service_SpecialPrice();
    }

    /**
     * 処理を実行します。
     *
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['ticket']:チケット文字列
     * </li><li>
     * ['outForm']:出力フォーム
     * </li><li>
     * ['errorForm']:エラーフォーム
     * </li></ul>
     */
    public function executeInner()
    {
        if (isset($_POST['create_btn_x'])) {
            return $this->_executeInnerCreate();
        } else if (isset($_POST['edit_btn_x'])) {
            return $this->_executeInnerUpdate();
        } else {
            return $this->_executeInnerReload();
        }
    }

    /**
     * 新規ボタン押下の場合の処理を実行します。
     * <ol><li>
     * セッション情報を削除
     * </li><li>
     * 遷移元情報を取得
     * </li><li>
     * 必要な情報をセッションに保存
     * </li><li>
     * 出力情報を作成
     * </li><li>
     * チケット発行
     * </li></ol>
     *
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['ticket']:チケット文字列
     * </li><li>
     * ['outForm']:出力フォーム
     * </li><li>
     * ['errorForm']:エラーフォーム
     * </li></ul>
     */
    public function _executeInnerCreate()
    {
        Sgmov_Component_Log::debug('新規ボタン押下の場合の処理を実行します。');

        // セッション削除
        $session = Sgmov_Component_Session::get();
        $session->deleteForm($this->getFeatureId());

        // 遷移元情報を取得
        $sessionForm = $this->_createSessionFormForCreate();

        // 必要な情報をセッションに保存
        $session->saveForm($this->getFeatureId(), $sessionForm);

        // 出力情報を作成
        $outForm = $this->_createOutFormBySessionForm($sessionForm);
        $errorForm = new Sgmov_Form_Error();

        // チケット発行
        $ticket = $session->publishTicket($this->getFeatureId(), self::GAMEN_ID_ASP004);

        return array('ticket'=>$ticket,
                         'outForm'=>$outForm,
                         'errorForm'=>$errorForm);
    }

    /**
     * 新規・変更ボタン押下ではない場合の処理を実行します。
     * <ol><li>
     * セッションに情報がなければ不正遷移エラー
     * </li><li>
     * セッション情報を元に出力情報を作成
     * </li><li>
     * チケット発行
     * </li></ol>
     *
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['ticket']:チケット文字列
     * </li><li>
     * ['outForm']:出力フォーム
     * </li><li>
     * ['errorForm']:エラーフォーム
     * </li></ul>
     */
    public function _executeInnerReload()
    {
        Sgmov_Component_Log::debug('新規・変更ボタン押下ではない場合の処理を実行します。');

        // セッションに情報がなければ不正遷移エラー
        $session = Sgmov_Component_Session::get();
        $sessionForm = $session->loadForm($this->getFeatureId());
        if (is_null($sessionForm)) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '画面IDが不正です。');
        }

        // 出力情報を作成
        $outForm = $this->_createOutFormBySessionForm($sessionForm);
        $errorForm = $sessionForm->asp004_error;

        // チケット発行
        $ticket = $session->publishTicket($this->getFeatureId(), self::GAMEN_ID_ASP004);

        return array('ticket'=>$ticket,
                         'outForm'=>$outForm,
                         'errorForm'=>$errorForm);
    }

    /**
     * 変更ボタン押下の場合の処理を実行します。
     * <ol><li>
     * セッション情報を削除
     * </li><li>
     * 入力情報を取得
     * </li><li>
     * 入力値のチェック
     * </li><li>
     * 条件をチェック
     * </li><li>
     * 条件チェックでエラーがある場合、詳細画面にリダイレクト
     * </li><li>
     * 変更元データをチェック済みデータとしてセッションに保存
     * </li><li>
     * 出力情報を作成
     * </li><li>
     * チケット発行
     * </li></ol>
     *
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['ticket']:チケット文字列
     * </li><li>
     * ['outForm']:出力フォーム
     * </li><li>
     * ['errorForm']:エラーフォーム
     * </li></ul>
     */
    public function _executeInnerUpdate()
    {
        Sgmov_Component_Log::debug('変更ボタン押下の場合の処理を実行します。');

        // セッション削除
        $session = Sgmov_Component_Session::get();
        $session->deleteForm($this->getFeatureId());

        if ($_POST['gamen_id'] !== self::GAMEN_ID_ASP002) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(self::ERROR_VIEW_INVALID_PAGE_ACCESS, '画面IDが不正です。');
        }

        // 入力情報を取得
        $inForm = $this->_createInFormFromPostForUpdate($_POST);

        // 入力チェック(不正入力かどうかのチェックで、不正な場合はシステムエラーになります。)
        $this->_validateForUpdate($inForm);

        // 条件チェック→詳細画面にエラー表示
        $errorForm = $this->_checkConditionForUpdate($inForm);
        if ($errorForm->hasError()) {
            $sessionForm = new Sgmov_Form_AspSession();
            $sessionForm->asp002_error = $errorForm;
            $session->saveForm($this->getFeatureId(), $sessionForm);

            $to = $this->_createSpDetailUrlForUpdate($inForm->sp_list_kind, $inForm->sp_list_view_mode, $inForm->sp_cd);
            Sgmov_Component_Log::debug('リダイレクト ' . $to);
            Sgmov_Component_Redirect::redirectMaintenance($to);
        }

        // 入力フォームからセッションフォームを作成
        $sessionForm = $this->_createSessionFormByInFormForUpdate($inForm);

        // 更新対象の情報を取得
        $this->_setSessionFormForUpdate($sessionForm);
        $session->saveForm($this->getFeatureId(), $sessionForm);

        // 出力情報を作成
        $outForm = $this->_createOutFormBySessionForm($sessionForm);

        // チケット発行
        $ticket = $session->publishTicket($this->getFeatureId(), self::GAMEN_ID_ASP004);

        return array('ticket'=>$ticket,
                         'outForm'=>$outForm,
                         'errorForm'=> new Sgmov_Form_Error());
    }

    /**
     * 遷移元情報を設定してセッションフォームを生成します。
     *
     * 渡された遷移元情報が不正な場合はアプリケーションエラーとなります。
     *
     * @return Sgmov_Form_AspSession セッションフォーム
     */
    public function _createSessionFormForCreate()
    {
        $sessionForm = new Sgmov_Form_AspSession();
        if (!isset($_POST['gamen_id'])) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '画面IDが指定されていません。');
        } else if ($_POST['gamen_id'] === self::GAMEN_ID_ASP001) {
            // 一覧画面
            $sessionForm->gamen_id = self::GAMEN_ID_ASP001;
            $sessionForm->sp_list_kind = $_POST['sp_list_kind'];
            $sessionForm->sp_list_view_mode = $_POST['sp_list_view_mode'];
            $sessionForm->sp_cd = '';
            $sessionForm->sp_timestamp = '';
            $sessionForm->backto_sp_cd = '';
        } else if ($_POST['gamen_id'] === self::GAMEN_ID_ASP002) {
            // 詳細画面
            $sessionForm->gamen_id = self::GAMEN_ID_ASP002;
            $sessionForm->sp_list_kind = $_POST['sp_list_kind'];
            $sessionForm->sp_list_view_mode = $_POST['sp_list_view_mode'];
            $sessionForm->sp_cd = '';
            $sessionForm->sp_timestamp = '';
            $sessionForm->backto_sp_cd = $_POST['sp_cd'];
        } else {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '画面IDが不正です。');
        }

        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->sp_list_kind);
        $v->isIn(array(self::SP_LIST_KIND_EXTRA, self::SP_LIST_KIND_CAMPAIGN));
        if (!$v->isValid()) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '特価一覧種別が不正です。');
        }

        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->sp_list_view_mode);
        $v->isIn(array(self::SP_LIST_VIEW_OPEN, self::SP_LIST_VIEW_DRAFT, self::SP_LIST_VIEW_CLOSE));
        if (!$v->isValid()) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '特価一覧表示モードが不正です。');
        }

        // 0以上の整数
        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->backto_sp_cd);
        $v->isInteger(0);
        if (!$v->isValid()) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '特価コードが不正です。');
        }

        return $sessionForm;
    }

    /**
     * セッションフォームの値を元に出力フォームを生成します。
     * @param Sgmov_Form_AspSession $sessionForm セッションフォーム
     * @return Sgmov_Form_Asp004Out 出力フォーム
     */
    public function _createOutFormBySessionForm($sessionForm)
    {
        $outForm = new Sgmov_Form_Asp004Out();

        // 本社ユーザーかどうか
        $outForm->raw_honsha_user_flag = $this->getHonshaUserFlag();

        // 一覧に戻るリンクのURL
        $outForm->raw_sp_list_url = $this->createSpListUrl($sessionForm->sp_list_kind, $sessionForm->sp_list_view_mode);

        // 閑散繁忙設定かキャンペーン設定か(戻り先一覧画面のものとは別に管理)
        $outForm->raw_sp_kind = $this->getSpKind();

        // CancelボタンのURL
        $outForm->raw_cancel_btn_url = $this->_createCancelUrl($sessionForm);

        // セッション値の適用
        if (isset($sessionForm->asp004_in)) {
            $outForm->raw_sp_name = $sessionForm->asp004_in->sp_name;
            $outForm->raw_sp_content = $sessionForm->asp004_in->sp_content;
            $outForm->raw_sp_regist_user = $sessionForm->asp004_in->sp_regist_user;
        }
        return $outForm;
    }

    /**
     * 戻るボタン用のリンクURLを生成します。
     * @param Sgmov_Form_AspSession $sessionForm セッションフォーム
     * @return string 戻るボタン用のリンクURL
     */
    public function _createCancelUrl($sessionForm)
    {
        $url = '/asp';
        if ($sessionForm->gamen_id === self::GAMEN_ID_ASP001) {
            $url .= '/list';
        } else if ($sessionForm->gamen_id === self::GAMEN_ID_ASP002) {
            $url .= '/detail';
        } else {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, '画面IDが不正です。');
        }

        if ($sessionForm->sp_list_kind === self::SP_LIST_KIND_EXTRA) {
            $url .= '/' . self::GET_PARAM_EXTRA;
        } else if ($sessionForm->sp_list_kind === self::SP_LIST_KIND_CAMPAIGN) {
            $url .= '/' . self::GET_PARAM_CAMPAIGN;
        } else {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, '特価一覧種別が不正です。');
        }

        if ($sessionForm->sp_list_view_mode === self::SP_LIST_VIEW_OPEN) {
            $url .= '/' . self::GET_PARAM_OPEN;
        } else if ($sessionForm->sp_list_view_mode === self::SP_LIST_VIEW_DRAFT) {
            $url .= '/' . self::GET_PARAM_DRAFT;
        } else if ($sessionForm->sp_list_view_mode === self::SP_LIST_VIEW_CLOSE) {
            $url .= '/' . self::GET_PARAM_CLOSE;
        } else {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, '特価一覧表示モードが不正です。');
        }

        if ($sessionForm->gamen_id === self::GAMEN_ID_ASP002) {
            $url .= '/' . $sessionForm->backto_sp_cd;
        }

        return $url;
    }

    ////////////////////////////////////////////////////
    // 以下更新のみで使用
    ////////////////////////////////////////////////////

    /**
     * POST情報から入力フォームを生成します。
     * @param array $post ポスト情報
     * @return Sgmov_Form_Asp002In 入力フォーム
     */
    public function _createInFormFromPostForUpdate($post)
    {
        $inForm = new Sgmov_Form_Asp002In();
        $inForm->sp_list_kind = $_POST['sp_list_kind'];
        $inForm->sp_list_view_mode = $_POST['sp_list_view_mode'];
        $inForm->sp_cd = $_POST['sp_cd'];
        $inForm->sp_timestamp = $_POST['sp_timestamp'];
        return $inForm;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_Asp002In $inForm 入力フォーム
     */
    public function _validateForUpdate($inForm)
    {
        if (Sgmov_Component_Log::isDebug()) {
            Sgmov_Component_Log::debug('入力チェック:$inForm=' . Sgmov_Component_String::toDebugString($inForm));
        }

        if ($inForm->sp_list_kind !== $this->getSpKind()) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '削除画面URLの特価種別はPOSTされた特価一覧種別と同じでなければなりません。');
        }

        // 一覧画面の表示モード
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->sp_list_view_mode)->
                                        isNotEmpty()->
                                        isIn(array(self::SP_LIST_VIEW_CLOSE, self::SP_LIST_VIEW_DRAFT, self::SP_LIST_VIEW_OPEN));
        if (!$v->isValid()) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '特価一覧表示モードが不正です');
        }

        // 特価ID
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->sp_cd)->
                                        isNotEmpty()->
                                        isInteger(0);
        if (!$v->isValid()) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '特価コードが不正です');
        }

        // タイムスタンプ
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->sp_timestamp)->isNotEmpty();
        if (!$v->isValid()) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '特価タイムスタンプが不正です');
        }
    }

    /**
     * 条件チェックを行います。
     *
     * IDに一致する特価が見つからない場合はエラーメッセージを返します。
     *
     * ユーザーの支店が特価の担当支店ではない場合はシステムエラーとなります。
     *
     * タイムスタンプが一致しない場合はエラーメッセージを返します。
     *
     * 終了状態になってしまっている場合はエラーメッセージを返します。
     *
     * @param Sgmov_Form_Asp002In $inForm 入力フォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _checkConditionForUpdate($inForm)
    {
        $errorForm = new Sgmov_Form_Error();

        $db = Sgmov_Component_DB::getAdmin();
        $spInfo = $this->_specialPriceService->
                        fetchSpecialPricesById($db, $inForm->sp_cd);
        if (is_null($spInfo)) {
            Sgmov_Component_Log::info('IDに一致する特価が見つかりません。id=' . $inForm->sp_cd . ', timestamp=' . $inForm->sp_timestamp);
            $errorForm->addError('top_delete', '別のユーザーによって更新されています。');
            return $errorForm;
        }
        if ($spInfo['center_id'] !== $this->_loginService->getLoginUser()->centerId) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, 'ユーザーの所属する拠点と特価の担当拠点が異なります');
        }
        if ($spInfo['timestamp'] !== $inForm->sp_timestamp) {
            Sgmov_Component_Log::info('タイムスタンプが一致しません。id=' . $inForm->sp_cd . ', POSTtimestamp=' . $inForm->sp_timestamp . ', DBtimestamp=' . $spInfo['timestamp']);
            $errorForm->addError('top_delete', '別のユーザーによって更新されています。');
            return $errorForm;
        }
        $splits = explode(Sgmov_Service_Calendar::DATE_SEPARATOR, $spInfo['max_date']);
        $maxTime = mktime(0, 0, 0, intval($splits[1]), intval($splits[2]) + 1, intval($splits[0]));
        if ($maxTime <= time()) {
            Sgmov_Component_Log::info('特価の期間は終了しています。id=' . $inForm->sp_cd . ', POSTtimestamp=' . $inForm->sp_timestamp . ', DBtimestamp=' . $spInfo['timestamp']);
            $errorForm->addError('top_delete', '別のユーザーによって更新されています。');
            return $errorForm;
        }

        return $errorForm;
    }

    /**
     * 詳細画面のURLを生成します。
     * @param string $spListKind 特価一覧種別
     * @param string $spListViewMode 特価一覧表示モード
     * @param string $spCd 特価ID
     * @return string 詳細画面のURL
     */
    public function _createSpDetailUrlForUpdate($spListKind, $spListViewMode, $spCd)
    {
        $url = '/asp/detail';
        if ($spListKind === self::SP_LIST_KIND_EXTRA) {
            $url .= '/' . self::GET_PARAM_EXTRA;
        } else if ($spListKind === self::SP_LIST_KIND_CAMPAIGN) {
            $url .= '/' . self::GET_PARAM_CAMPAIGN;
        } else {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, '特価一覧種別が不正です。');
        }

        if ($spListViewMode === self::SP_LIST_VIEW_OPEN) {
            $url .= '/' . self::GET_PARAM_OPEN;
        } else if ($spListViewMode === self::SP_LIST_VIEW_DRAFT) {
            $url .= '/' . self::GET_PARAM_DRAFT;
        } else if ($spListViewMode === self::SP_LIST_VIEW_CLOSE) {
            $url .= '/' . self::GET_PARAM_CLOSE;
        } else {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, '特価一覧表示モードが不正です。');
        }

        $url .= '/' . $spCd;

        return $url;
    }

    /**
     * 入力フォームの値を元にセッションフォームを生成します。
     * @param Sgmov_Form_Asp002In $inForm 入力フォーム
     * @return Sgmov_Form_AspSession 出力フォーム
     */
    public function _createSessionFormByInFormForUpdate($inForm)
    {
        $sessionForm = new Sgmov_Form_AspSession();
        $sessionForm->gamen_id = self::GAMEN_ID_ASP002;
        $sessionForm->sp_list_kind = $inForm->sp_list_kind;
        $sessionForm->sp_list_view_mode = $inForm->sp_list_view_mode;
        $sessionForm->sp_cd = $inForm->sp_cd;
        $sessionForm->sp_timestamp = $inForm->sp_timestamp;
        $sessionForm->backto_sp_cd = $inForm->sp_cd;
        return $sessionForm;
    }

    /**
     * DB情報を元にセッションフォームに値を設定します。
     * @param Sgmov_Form_AspSession $sessionForm
     * @return
     */
    public function _setSessionFormForUpdate($sessionForm)
    {
        $id = $sessionForm->sp_cd;
        $db = Sgmov_Component_DB::getAdmin();
        // 特価の取得
        $spInfo = $this->_specialPriceService->
                        fetchSpecialPricesById($db, $id);
        $spCoursePlanIds = $this->_specialPriceService->
                                fetchCoursesPlansSpecialPricesById($db, $id);
        $spFromAreaIds = $this->_specialPriceService->
                                fetchFromAreasSpecialPricesById($db, $id);
        $spToAreaIds = $this->_specialPriceService->
                            fetchSpecialPricesToAreasById($db, $id);
        $temp = $this->_specialPriceService->
                        fetchTargetDates($db, $id);
        $spTargetDates = $temp['target_dates'];

        // ASP004
        $sessionForm->asp004_in = new Sgmov_Form_Asp004In();
        $sessionForm->asp004_in->sp_name = $spInfo['title'];
        if ($spInfo['special_price_division'] === '2') {
            $sessionForm->asp004_in->sp_content = $spInfo['description'];
        }
        $sessionForm->asp004_in->sp_regist_user = $spInfo['create_user_name'];

        // ASP005
        $sessionForm->asp005_in = new Sgmov_Form_Asp005In();
        $sessionForm->asp005_in->course_plan_sel_cds = $spCoursePlanIds;
        $sessionForm->asp005_in->from_area_sel_cds = $spFromAreaIds;
        $sessionForm->asp005_in->to_area_sel_cds = $spToAreaIds;

        // APS006
        $sessionForm->asp006_in = new Sgmov_Form_Asp006In();
        $sessionForm->asp006_in->sel_days = $spTargetDates;

        // priceset
        $sessionForm->priceset_kbn = $spInfo['priceset_kbn'];
        if ($sessionForm->priceset_kbn === self::PRICESET_KBN_ALL) {
            // ASP008
            $sessionForm->asp008_in = new Sgmov_Form_Asp008In();
            $sessionForm->asp008_in->sp_whole_charge = $spInfo['batchprice'];
        } else if ($sessionForm->priceset_kbn === self::PRICESET_KBN_EACH) {
            // ASP009
            $sessionForm->asp009_in = new Sgmov_Form_Asp009In();
            $sessionForm->asp009_in->course_plan_sel_cds = $spCoursePlanIds;
            $sessionForm->asp009_in->from_area_sel_cds = $spFromAreaIds;
            $sessionForm->asp009_in->to_area_sel_cds = $spToAreaIds;

            $sessionForm->asp009_in->all_charges = array();
            foreach ($spCoursePlanIds as $spCoursePlanId) {
                $coursePlanCdSplit = explode(Sgmov_Service_CoursePlan::ID_DELIMITER, $spCoursePlanId);
                $spCourseId = $coursePlanCdSplit[0];
                $spPlanId = $coursePlanCdSplit[1];
                foreach ($spFromAreaIds as $spFromAreaId) {
                    $details = $this->_specialPriceService->
                                    fetchSpecialPriceDetailInfoById($db, $id, $spCourseId, $spPlanId, $spFromAreaId);
                    $spDetailToAreaIds = $details['to_area_ids'];
                    $spDetailPriceDifferences = $details['price_differences'];
                    // 順番も含めて一致することを確認
                    if ($spDetailToAreaIds !== $spToAreaIds) {
                        Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, 'データ不整合');
                    }

                    // コピー
                    $allChargesKey = $spCoursePlanId . Sgmov_Service_CoursePlan::ID_DELIMITER . $spFromAreaId;
                    $sessionForm->asp009_in->all_charges[$allChargesKey] = $spDetailPriceDifferences;
                }
            }
        }
    }
}
?>
