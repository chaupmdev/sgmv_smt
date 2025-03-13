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
Sgmov_Lib::useForms(array('Error', 'Cmm002Out'));
/**#@-*/

/**
 * コメントマスタ入力画面を表示します。
 * @package    View
 * @subpackage Cmm
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Cmm_Input extends Sgmov_View_Cmm_Common {

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
        if (isset($_POST['id'])) {
            return $this->_executeInnerUpdate($_POST);
        } else {
            return $this->_executeInnerReload($_POST);
        }
    }

    /**
     * 新規・変更ボタン押下の場合の処理を実行します。
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
    public function _executeInnerUpdate($post) {
        // セッション削除
        $session = Sgmov_Component_Session::get();
        $featureId = $this->getFeatureId();
        $session->deleteForm($featureId);

        // 出力情報を作成
        $outForm = $this->_createOutFormByUpdate($post);
        $errorForm = new Sgmov_Form_Error();

        // チケット発行
        $ticket = $session->publishTicket($featureId, self::GAMEN_ID_CMM002);

        return array(
            'ticket'    => $ticket,
            'outForm'   => $outForm,
            'errorForm' => $errorForm,
        );
    }

    /**
     * 入力フォームの値を出力フォームを生成します。
     * @return Sgmov_Form_Cmm002Out 出力フォーム
     */
    private function _createOutFormByUpdate($post) {

        // DB接続
        $db = Sgmov_Component_DB::getPublic();

        $outForm = new Sgmov_Form_Cmm002Out();
        $outForm->raw_honsha_user_flag = $this->_loginService->getHonshaUserFlag();
        $outForm->raw_sp_list_kind     = $this->getSpKind();

        // 拠点コードの取得
        $centers = $this->_center->fetchCenters($db);
        $outForm->raw_comment_office_cds  = $centers['ids'];
        $outForm->raw_comment_office_lbls = $centers['names'];
        $outForm->raw_comment_file_1 = '/common/img/cmm/no_image.png';
        $outForm->raw_comment_file_2 = '/common/img/cmm/no_image.png';

        if (empty($post['id'])) {
            return $outForm;
        }

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
        $outForm->raw_comment_start_date = $comment['comment_start_date'];
        $outForm->raw_comment_end_date   = $comment['comment_end_date'];

        $filePath = '../../maintenance/common/img/cmm/files/';
        $filePath2 = '/common/img/cmm/files/';
        $fileNames = array($outForm->raw_comment_id . '_1.jpg', $outForm->raw_comment_id . '_2.jpg');
        if (is_readable($filePath . $fileNames[0])) {
            $outForm->raw_comment_file_1 = $filePath2 . $fileNames[0];
        }
        if (is_readable($filePath . $fileNames[1])) {
            $outForm->raw_comment_file_2 = $filePath2 . $fileNames[1];
        }

        return $outForm;
    }

    /**
     * 新規・変更ボタン押下ではない場合の処理を実行します。
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
    public function _executeInnerReload($post) {
        // セッション取得
        $session = Sgmov_Component_Session::get();
        $featureId = $this->getFeatureId();
        $sessionForm = $session->loadForm($featureId);

        // 出力情報を作成
        $outForm = $this->_createOutFormByReload($sessionForm);
        // TODO オブジェクトから値を直接取得できるよう修正する
        // オブジェクトから取得できないため、配列に型変換
        $sessionForm = (array)$sessionForm;
        $errorForm = $sessionForm['error'];

        // チケット発行
        $ticket = $session->publishTicket($featureId, self::GAMEN_ID_CMM002);

        return array(
            'ticket'    => $ticket,
            'outForm'   => $outForm,
            'errorForm' => $errorForm,
        );
    }

    /**
     * 入力フォームの値を出力フォームを生成します。
     * @return Sgmov_Form_Cmm002Out 出力フォーム
     */
    private function _createOutFormByReload($inForm) {

        // DB接続
        $db = Sgmov_Component_DB::getPublic();

        $outForm = new Sgmov_Form_Cmm002Out();
        $outForm->raw_honsha_user_flag = $this->_loginService->getHonshaUserFlag();
        $outForm->raw_sp_list_kind = $this->getSpKind();
        // 拠点コードの取得
        $centers = $this->_center->fetchCenters($db);
        $outForm->raw_comment_office_cds  = $centers['ids'];
        $outForm->raw_comment_office_lbls = $centers['names'];
        $outForm->raw_comment_file_1 = '/common/img/cmm/no_image.png';
        $outForm->raw_comment_file_2 = '/common/img/cmm/no_image.png';

        // TODO オブジェクトから値を直接取得できるよう修正する
        // オブジェクトから取得できないため、配列に型変換
        $inForm = (array)$inForm;

        $outForm->raw_comment_id         = $inForm['comment_id'];
        $outForm->raw_comment_flg        = $inForm['comment_flg'];
        $outForm->raw_comment_title      = $inForm['comment_title'];
        $outForm->raw_comment_address    = $inForm['comment_address'];
        $outForm->raw_comment_name       = $inForm['comment_name'];
        $outForm->raw_comment_office     = $inForm['comment_office'];
        $outForm->raw_comment_text       = $inForm['comment_text'];
        $outForm->raw_comment_start_date = $inForm['comment_start_date'];
        $outForm->raw_comment_end_date   = $inForm['comment_end_date'];

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

    /**
     * プルダウンを生成し、HTMLソースを返します。
     * TODO pre/Inputと同記述あり
     *
     * @param $cds コードの配列
     * @param $lbls ラベルの配列
     * @param $select 選択値
     * @return 生成されたプルダウン
     */
    public static function _createPulldown($cds, $lbls, $select) {

        $html = '';

        if (empty($cds)) {
            return $html;
        }

        $count = count($cds);
        for ($i = 0; $i < $count; ++$i) {
            if ($select === $cds[$i]) {
                $html .= '<option value="' . $cds[$i] . '" selected="selected">' . $lbls[$i] . '</option>' . PHP_EOL;
            } else {
                $html .= '<option value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
            }
        }

        return $html;
    }
}