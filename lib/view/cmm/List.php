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
Sgmov_Lib::useView('cmm/Common');
Sgmov_Lib::useForms(array('Error', 'Cmm001Out'));
/**#@-*/

/**
 * コメントマスタ一覧画面を表示します。
 * @package    View
 * @subpackage CMM
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Cmm_List extends Sgmov_View_Cmm_Common {

    /**
     * ログインサービス
     * @var Sgmov_Service_Login
     */
    public $_loginService;

    /**
     * コメントマスタサービス
     * @var Sgmov_Service_CommentData
     */
    private $_CommentDataService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_loginService = new Sgmov_Service_Login();
        $this->_CommentDataService = new Sgmov_Service_CommentData();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * セッション情報の削除
     * </li><li>
     * GETパラメーターのチェック
     * </li><li>
     * GETパラメーターを元に出力情報を生成
     * </li></ol>
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['outForm']:出力フォーム
     * </li></ul>
     */
    public function executeInner() {
        Sgmov_Component_Log::debug('セッション情報の削除');
        Sgmov_Component_Session::get()->deleteForm($this->getFeatureId());

        $outForm = $this->_createOutFormByInForm();

        return array('outForm' => $outForm);
    }

    /**
     * GETパラメータから特価IDを取得します。
     *
     * [パラメータ]
     * <ol><li>
     * 'open' or 'draft' or 'close' または未指定
     * </li></ol>
     * 未指定の場合は'open'を返します。
     *
     * [例]
     * <ul><li>
     * /asp/list/campaign
     * </li><li>
     * /asp/list/campaign/close
     * </li></ul>
     * @return array
     * ['listModeCd']:一覧表示画面の表示モードコード
     */
    public function _parseGetParameter()
    {
        if (!isset($_GET['param'])) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_GET[\'param\']が未設定です。');
        }
    }
    /**
     * 入力フォームの値を出力フォームを生成します。
     * @return Sgmov_Form_Aap001Out 出力フォーム
     */
    private function _createOutFormByInForm() {

        $outForm = new Sgmov_Form_Cmm001Out();
        $outForm->raw_sp_list_kind = $this->getSpKind();

        $db = Sgmov_Component_DB::getPublic();
        $comment = $this->_CommentDataService->fetchCommentDatas($db, $outForm->raw_sp_list_kind, false);

        // コメント
        $outForm->raw_comment_ids         = $comment['ids'];
        $outForm->raw_comment_flgs        = $comment['flgs'];
        $outForm->raw_comment_titles      = $comment['titles'];
        $outForm->raw_comment_addresses   = $comment['addresses'];
        $outForm->raw_comment_names       = $comment['names'];
        $outForm->raw_comment_offices     = $comment['offices'];
        $outForm->raw_comment_texts       = $comment['texts'];
        $outForm->raw_comment_start_dates = $comment['start_dates'];
        $outForm->raw_comment_end_dates   = $comment['end_dates'];
        $outForm->raw_center_names        = $comment['center_names'];

        $outForm->raw_honsha_user_flag = $this->_loginService->getHonshaUserFlag();

        return $outForm;
    }

    /**
     * お客様の声設定かこの子に注目設定かを表すフラグを返します。
     * @return string '1':お客様の声設定 '2':この子に注目設定
     */
    public function getSpKind() {
        switch ($this->getFeatureId()) {
            case self::FEATURE_ID_COMMENTS:
                return self::SP_LIST_KIND_COMMENTS;
            case self::FEATURE_ID_ATTENTION:
                return self::SP_LIST_KIND_ATTENTION;
            default:
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, '機能IDが不正です。');
        }
    }
}