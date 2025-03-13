<?php
/**
 * @package    ClassDefFile
 * @author     T.Aono(SGS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('cmm/Common');
Sgmov_Lib::useForms(array('Error', 'AtaSession', 'Cmm002In'));
/**#@-*/

/**
 * コメント入力情報をチェックします。
 * @package    View
 * @subpackage CMM
 * @author     T.Aono(SGS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_CMM_CheckDelete extends Sgmov_View_Cmm_Common {

    private $_this_featureId;
    private $_kind;

    /**
     * コメントマスタサービス
     * @var Sgmov_Service_CommentData
     */
    private $_CommentDataService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_CommentDataService = new Sgmov_Service_CommentData();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * チケットの確認と破棄
     * </li><li>
     * 情報をセッションに保存
     * </li><li>
     * 入力チェック
     * </li><li>
     * 入力エラー無し
     *   <ol><li>
     *   ata/confirm へリダイレクト
     *   </li></ol>
     * </li><li>
     * 入力エラー有り
     *   <ol><li>
     *   ata/delete へリダイレクト
     *   </li></ol>
     * </li></ol>
     */
    public function executeInner() {
        Sgmov_Component_Log::debug('チケットの確認と破棄');
        $session = Sgmov_Component_Session::get();
        $this->_this_featureId = $this->getFeatureId();
        $session->checkTicket($this->_this_featureId, self::GAMEN_ID_CMM012, $this->_getTicket());
        $this->_kind = $this->getSpKind();

        Sgmov_Component_Log::debug('情報を取得');
        $inForm = $this->_createInFormFromPost();
        $sessionForm = $session->loadForm($this->_this_featureId);
        if (empty($sessionForm)) {
            $sessionForm = new Sgmov_Form_Cmm002In();
        }
        $sessionForm->comment_id = $inForm->comment_id;

        Sgmov_Component_Log::debug('削除実行');
        $errorForm = $this->_deleteCommentData($sessionForm);

        Sgmov_Component_Log::debug('セッション保存');
        $sessionForm->error = $errorForm;
        if ($errorForm->hasError()) {
            $sessionForm->status = self::VALIDATION_FAILED;
        } else {
            $sessionForm->status = self::VALIDATION_SUCCEEDED;
        }

        // リダイレクト
        if( $this->_kind === self::SP_LIST_KIND_COMMENTS){
            $toForm = self::GET_PARAM_COMMENTS;
        } else if ($this->_kind === self::SP_LIST_KIND_ATTENTION) {
            $toForm = self::GET_PARAM_ATTENTION;
        }
        if ($errorForm->hasError()) {
            $session->saveForm($this->_this_featureId, $sessionForm);
            Sgmov_Component_Log::debug('リダイレクト /cmm/delete/'.$toForm);
            Sgmov_Component_Redirect::redirectMaintenance('/cmm/delete/'.$toForm);
        } else {
            // TODO 確認画面と完了画面を作る
            $session->deleteForm($this->_this_featureId);
            Sgmov_Component_Log::debug('リダイレクト /cmm/list/'.$toForm);
            Sgmov_Component_Redirect::redirectMaintenance('/cmm/list/'.$toForm);
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
     * @return Sgmov_Form_Ata002In 入力フォーム
     */
    public function _createInFormFromPost() {
        $inForm = new Sgmov_Form_Cmm002In();

        $inForm->comment_id = filter_input(INPUT_POST, 'comment_id');

        return $inForm;
    }

    /**
     * セッション情報を元にツアー会社情報を削除します。
     *
     * 同時更新によって更新に失敗した場合はエラーフォームにメッセージを設定して返します。
     *
     * @param Sgmov_Form_AocSession $sessionForm セッションフォーム
     * @return Sgmov_Form_Error エラーフォーム
     */
    public function _deleteCommentData($sessionForm) {
        // DBへ接続
        $db = Sgmov_Component_DB::getAdmin();

        // 情報をDBへ格納
        if (!empty($sessionForm->comment_id)) {
            $data = array(
                'id' => $sessionForm->comment_id,
            );
            $ret = $this->_CommentDataService->_deleteCommentData($db, $data);
        }

        $errorForm = new Sgmov_Form_Error();
        if (isset($ret) && $ret === false) {
            $errorForm->addError('top_conflict', '別のユーザーによって更新されています。すべての変更は適用されませんでした。');
        }

        // アップロードファイル削除
        if (!empty($sessionForm->comment_id)) {
        	$this->deleteCommentFile($sessionForm->comment_id, $errorForm);
        }

        return $errorForm;
    }

    public function deleteCommentFile($id, &$errorForm) {
        $filePaths = array('../../public_html/cmm/files/', '../../maintenance/common/img/cmm/files/');
        $fileNames = array($id . '_1.jpg', $id . '_2.jpg');
        foreach ($filePaths as $k1 => $d1) {
            $filePath = $d1;
            foreach ($fileNames as $k => $d) {
                if (is_writable($filePath . $d)) {
                    unlink($filePath . $d);
                }
            }
        }
    }

    /**
     * お客様の声設定かこの子に注目設定かを表すフラグを返します。
     * @return string '1':お客様の声設定 '2':この子に注目設定
     */
    public function getSpKind() {
        switch ($this->_this_featureId) {
            case self::FEATURE_ID_COMMENTS:
                return self::SP_LIST_KIND_COMMENTS;
            case self::FEATURE_ID_ATTENTION:
                return self::SP_LIST_KIND_ATTENTION;
            default:
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, '機能IDが不正です。');
        }
    }
}