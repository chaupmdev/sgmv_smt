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
Sgmov_Lib::useForms(array('Error', 'Cmm012Out'));
/**#@-*/

/**
 * コメントマスタ削除確認画面を表示します。
 * @package    View
 * @subpackage CMM
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Cmm_Delete extends Sgmov_View_Cmm_Common {

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
     * 拠点サービス
     * @var Sgmov_Service_Center
     */
    public $_center;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_loginService = new Sgmov_Service_Login();
        $this->_CommentDataService = new Sgmov_Service_CommentData();
        $this->_center = new Sgmov_Service_Center();
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
    public function executeInner() {

        // セッション削除
        $session = Sgmov_Component_Session::get();
        $session->deleteForm($this->getFeatureId());

        // 出力情報を作成
        $outForm = $this->_createOutForm($_POST);

        $errorForm = new Sgmov_Form_Error();

        // チケット発行
        $ticket = $session->publishTicket($this->getFeatureId(), self::GAMEN_ID_CMM012);

        return array(
            'ticket'    => $ticket,
            'outForm'   => $outForm,
            'errorForm' => $errorForm,
        );
    }

    /**
     * 出力フォームを生成します。
     * @return Sgmov_Form_Ata012Out 出力フォーム
     */
    private function _createOutForm($post) {

        $outForm = new Sgmov_Form_Cmm012Out();
        $outForm->raw_honsha_user_flag = $this->_loginService->getHonshaUserFlag();
        $outForm->raw_sp_list_kind = $this->getSpKind();

        if (empty($post['id'])) {
            return $outForm;
        }

        $db = Sgmov_Component_DB::getPublic();
        $comment = $this->_CommentDataService->fetchCommentData($db, array('id' => $post['id']));
        if (empty($comment)) {
            return $outForm;
        }

        $outForm->raw_comment_id         = $comment['id'];
        $outForm->raw_comment_flg        = $comment['comment_flg'];
        $outForm->raw_comment_title      = $comment['comment_title'];
        $outForm->raw_comment_address    = $comment['comment_address'];
        $outForm->raw_comment_name       = $comment['comment_name'];
        $outForm->raw_comment_office     = $comment['comment_office'];
        $outForm->raw_comment_text       = $comment['comment_text'];
        $outForm->raw_comment_start_date = $comment['comment_start_date_japanese'];
        $outForm->raw_comment_end_date   = $comment['comment_end_date_japanese'];
        $outForm->raw_center_name        = $comment['centernm'];

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