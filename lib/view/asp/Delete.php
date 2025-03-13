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
Sgmov_Lib::useServices(array('CoursePlan', 'Login', 'SpecialPrice', 'Calendar'));
Sgmov_Lib::useForms(array('Error', 'AspSession', 'Asp002In', 'Asp003Out'));
/**#@-*/

 /**
 * 特価情報を削除し、削除完了画面を表示します。
 * @package    View
 * @subpackage ASP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Asp_Delete extends Sgmov_View_Asp_Common
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
     * <ol><li>
     * チケットの確認と破棄
     * </li><li>
     * 入力チェック
     * </li><li>
     * 条件チェック
     * </li><li>
     * 削除実行
     * </li><li>
     * 同時更新エラーの場合はエラーを設定して詳細画面へ戻る
     * </li><li>
     * 出力情報を設定
     * </li></ol>
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['outForm']:出力フォーム
     * </li></ul>
     */
    public function executeInner()
    {
        Sgmov_Component_Log::debug('チケットの確認と破棄');
        $session = Sgmov_Component_Session::get();
        $session->checkTicket($this->getFeatureId(), self::GAMEN_ID_ASP002, $this->_getTicket());

        $inForm = $this->_createInFormFromPost($_POST);

        // 入力チェック(不正入力かどうかのチェックで、不正な場合はシステムエラーになります。)
        $this->_validate($inForm);

        // 条件チェック→詳細画面にエラー表示
        $errorForm = $this->_checkCondition($inForm);
        if ($errorForm->hasError()) {
            $sessionForm = new Sgmov_Form_AspSession();
            $sessionForm->asp002_error = $errorForm;
            $session->saveForm($this->getFeatureId(), $sessionForm);

            $to = $this->_createSpDetailUrl($inForm->sp_list_kind, $inForm->sp_list_view_mode, $inForm->sp_cd);
            Sgmov_Component_Log::debug('リダイレクト ' . $to);
            Sgmov_Component_Redirect::redirectMaintenance($to);
        }

        // 削除実行
        $ret = $this->_deleteSpecialPrice($inForm);

        // エラーチェック→詳細画面にエラー表示
        if ($ret === FALSE) {
            Sgmov_Component_Log::debug('同時更新エラー');

            $sessionForm->asp002_error = new Sgmov_Form_Error();
            $sessionForm->asp002_error->
                        addError('top_delete', '削除に失敗しました。情報は別のユーザーによって更新されています。');
            $session->saveForm($this->getFeatureId(), $sessionForm);

            $to = $this->_createSpDetailUrl($inForm->sp_list_kind, $inForm->sp_list_view_mode, $inForm->sp_cd);
            Sgmov_Component_Log::debug('リダイレクト ' . $to);
            Sgmov_Component_Redirect::redirectMaintenance($to);
        }

        Sgmov_Component_Log::debug('出力情報を設定');
        $outForm = $this->_createOutFormByInForm($inForm);

        return array('outForm'=>$outForm);
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
     * @param array $post ポスト情報
     * @return Sgmov_Form_Asp002In 入力フォーム
     */
    public function _createInFormFromPost($post)
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
    public function _validate($inForm)
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
    public function _checkCondition($inForm)
    {
        $errorForm = new Sgmov_Form_Error();

        $db = Sgmov_Component_DB::getAdmin();
        $spInfo = $this->_specialPriceService->
                        fetchSpecialPricesById($db, $inForm->sp_cd);
        if (is_null($spInfo)) {
            Sgmov_Component_Log::info('削除に失敗しました。IDに一致する特価が見つかりません。id=' . $inForm->sp_cd . ', timestamp=' . $inForm->sp_timestamp);
            $errorForm->addError('top_delete', '削除に失敗しました。情報は別のユーザーによって更新されています。');
            return $errorForm;
        }
        if ($spInfo['center_id'] !== $this->_loginService->getLoginUser()->centerId) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, 'ユーザーの所属する拠点と特価の担当拠点が異なります');
        }
        if ($spInfo['timestamp'] !== $inForm->sp_timestamp) {
            Sgmov_Component_Log::info('削除に失敗しました。タイムスタンプが一致しません。id=' . $inForm->sp_cd . ', POSTtimestamp=' . $inForm->sp_timestamp . ', DBtimestamp=' . $spInfo['timestamp']);
            $errorForm->addError('top_delete', '削除に失敗しました。情報は別のユーザーによって更新されています。');
            return $errorForm;
        }
        $splits = explode(Sgmov_Service_Calendar::DATE_SEPARATOR, $spInfo['max_date']);
        $maxTime = mktime(0, 0, 0, intval($splits[1]), intval($splits[2]), intval($splits[0]));
        if ($maxTime < time()) {
            Sgmov_Component_Log::info('削除に失敗しました。特価の期間は終了しています。id=' . $inForm->sp_cd . ', POSTtimestamp=' . $inForm->sp_timestamp . ', DBtimestamp=' . $spInfo['timestamp']);
            $errorForm->addError('top_delete', '削除に失敗しました。終了した情報を削除することはできません。');
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
    public function _createSpDetailUrl($spListKind, $spListViewMode, $spCd)
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
     * 入力フォームの値を元に出力フォームを生成します。
     * @param Sgmov_Form_Asp002In $inForm 入力フォーム
     * @return Sgmov_Form_Asp003Out 出力フォーム
     */
    public function _createOutFormByInForm($inForm)
    {
        $outForm = new Sgmov_Form_Asp003Out();

        // 本社ユーザーかどうか
        $outForm->raw_honsha_user_flag = $this->getHonshaUserFlag();
        // 一覧に戻るリンクのURL
        $outForm->raw_sp_list_url = $this->createSpListUrl($inForm->sp_list_kind, $inForm->sp_list_view_mode);
        // 閑散繁忙設定かキャンペーン設定か
        $outForm->raw_sp_kind = $inForm->sp_list_kind;
        return $outForm;
    }

    /**
     * 入力情報を元に特価情報を削除します。
     * @param Sgmov_Form_Asp002In $inForm 入力フォーム
     * @return boolean 成功した場合TRUEをそうでない場合はFALSEを返します。
     */
    public function _deleteSpecialPrice($inForm)
    {
        $db = Sgmov_Component_DB::getAdmin();
        return $this->_specialPriceService->
                    deleteSpecialPrice($db, $inForm->sp_cd, $inForm->sp_timestamp);
    }

}

?>
