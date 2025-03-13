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
class Sgmov_View_Cmm_CheckInput extends Sgmov_View_Cmm_Common {

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
     *   pin/confirm へリダイレクト
     *   </li></ol>
     * </li><li>
     * 入力エラー有り
     *   <ol><li>
     *   pin/input へリダイレクト
     *   </li></ol>
     * </li></ol>
     */
    public function executeInner() {
        Sgmov_Component_Log::debug('チケットの確認と破棄');
        $session = Sgmov_Component_Session::get();
        $this->_this_featureId = $this->getFeatureId();
        $session->checkTicket($this->_this_featureId, self::GAMEN_ID_CMM002, $this->_getTicket());
        $this->_kind = $this->getSpKind();

        Sgmov_Component_Log::debug('情報を取得');
        $inForm = $this->_createInFormFromPost($_POST);
        $sessionForm = $session->loadForm($this->_this_featureId);
        if (empty($sessionForm)) {
            $sessionForm = new Sgmov_Form_Cmm002In();
        }
        $sessionForm->comment_id         = $inForm->comment_id;
        if( $this->_kind === self::SP_LIST_KIND_COMMENTS){
            $sessionForm->comment_flg = self::SP_LIST_KIND_COMMENTS;
        } else if ($this->_kind === self::SP_LIST_KIND_ATTENTION) {
            $sessionForm->comment_flg = self::SP_LIST_KIND_ATTENTION;
        }
        $sessionForm->comment_title      = $inForm->comment_title;
        $sessionForm->comment_address    = $inForm->comment_address;
        $sessionForm->comment_name       = $inForm->comment_name;
        $sessionForm->comment_office     = (!empty($inForm->comment_office)) ? $inForm->comment_office : null;
        $sessionForm->comment_text       = $inForm->comment_text;
        $sessionForm->comment_start_date = $inForm->comment_start_date;
        $sessionForm->comment_end_date   = $inForm->comment_end_date;

        Sgmov_Component_Log::debug('入力チェック');
        $errorForm = $this->_validate($sessionForm);
        if (!$errorForm->hasError()) {
            $errorForm = $this->_updateCommandData($sessionForm);
        }

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
            Sgmov_Component_Log::debug('リダイレクト /cmm/input/'.$toForm);
            Sgmov_Component_Redirect::redirectMaintenance('/cmm/input/'.$toForm);
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
     * @param array $post ポスト情報
     * @return Sgmov_Form_Cmm002In 入力フォーム
     */
    public function _createInFormFromPost($post) {

        $inForm = new Sgmov_Form_Cmm002In();

        $inForm->comment_id         = filter_input(INPUT_POST, 'comment_id');
        $inForm->comment_flg        = filter_input(INPUT_POST, 'comment_flg');
        $inForm->comment_title      = filter_input(INPUT_POST, 'comment_title');
        $inForm->comment_address    = filter_input(INPUT_POST, 'comment_address');
        $inForm->comment_name       = filter_input(INPUT_POST, 'comment_name');
        $inForm->comment_office     = filter_input(INPUT_POST, 'comment_office');
        $inForm->comment_text       = filter_input(INPUT_POST, 'comment_text');
        $inForm->comment_start_date = filter_input(INPUT_POST, 'comment_start_date');
        $inForm->comment_end_date   = filter_input(INPUT_POST, 'comment_end_date');

        return $inForm;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_AtaSession $sessionForm セッションフォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($sessionForm) {
        // 入力チェック
        $errorForm = new Sgmov_Form_Error();

        if( $this->_kind === self::SP_LIST_KIND_COMMENTS){
            // タイトル
            $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->comment_title)->
                    isNotEmpty()->
                    isLengthLessThanOrEqualTo(50)->
                    isWebSystemNg();
            if (!$v->isValid()) {
                $errorForm->addError('top_comment_title', $v->getResultMessageTop());
                $errorForm->addError('comment_title', $v->getResultMessage());
            }
            // 住所
            $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->comment_address)->
                    isNotEmpty()->
                    isLengthLessThanOrEqualTo(100)->
                    isWebSystemNg()->
                    isAddress();
            if (!$v->isValid()) {
                $errorForm->addError('top_comment_address', $v->getResultMessageTop());
                $errorForm->addError('comment_address', $v->getResultMessage());
            }
        } else if ($this->_kind === self::SP_LIST_KIND_ATTENTION) {
            // 営業所
            $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->comment_office)->
                    isNotEmpty()->
                    isLengthLessThanOrEqualTo(100)->
                    isWebSystemNg();
            if (!$v->isValid()) {
                $errorForm->addError('top_comment_office', $v->getResultMessageTop());
                $errorForm->addError('comment_office', $v->getResultMessage());
            }
        }

        // 氏名
        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->comment_name)->
                isNotEmpty()->
                isLengthLessThanOrEqualTo(30)->
                isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_comment_name', $v->getResultMessageTop());
            $errorForm->addError('comment_name', $v->getResultMessage());
        }

        // コメント
        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->comment_text)->
                isNotEmpty()->
                isLengthLessThanOrEqualTo(500)->
                isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_comment_text', $v->getResultMessageTop());
            $errorForm->addError('comment_text', $v->getResultMessage());
        }

        // 写真[1]
        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->comment_file_1)->
                isLengthLessThanOrEqualTo(120)->
                isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_comment_file_1', $v->getResultMessageTop());
            $errorForm->addError('comment_file_1', $v->getResultMessage());
        }

        // 写真[2]
        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->comment_file_2)->
                isLengthLessThanOrEqualTo(120)->
                isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_comment_file_2', $v->getResultMessageTop());
            $errorForm->addError('comment_file_2', $v->getResultMessage());
        }

        $date = new DateTime('1990/01/01');
        $min = intval($date->format('U'));

        // 掲載開始日
        $comment_start_date = self::_formatDate($sessionForm->comment_start_date);
        $beginV = Sgmov_Component_Validator::createDateValidator(
                $comment_start_date[1],
                $comment_start_date[2],
                $comment_start_date[3])->
                isNotEmpty()->
                isDate($min);
        if (!$beginV->isValid()) {
            $errorForm->addError('top_comment_start_date', $beginV->getResultMessageTop());
            $errorForm->addError('comment_start_date', $beginV->getResultMessage());
        }

        // 掲載終了日
        $comment_end_date = self::_formatDate($sessionForm->comment_end_date);
        $endV = Sgmov_Component_Validator::createDateValidator(
                $comment_end_date[1],
                $comment_end_date[2],
                $comment_end_date[3])->
                isNotEmpty()->
                isDate($min);
        if (!$endV->isValid()) {
            $errorForm->addError('top_comment_end_date', $endV->getResultMessageTop());
            $errorForm->addError('comment_end_date', $endV->getResultMessage());
        } elseif ($beginV->isValid()) {
            $date = new DateTime($comment_start_date[4]);
            $min  = intval($date->format('U'));
            $endV->isDate($min);
            if (!$endV->isValid()) {
                $errorForm->addError('top_comment_date_check', 'には掲載開始日以降の日付を入力してください。');
                $errorForm->addError('comment_date_check', '掲載開始日以降の日付を入力してください。');
            }
        }

        return $errorForm;
    }

    /**
     * セッション情報を元にコメントマスタデータを更新します。
     *
     * 同時更新によって更新に失敗した場合はエラーフォームにメッセージを設定して返します。
     *
     * @param Sgmov_Form_AocSession $sessionForm セッションフォーム
     * @return Sgmov_Form_Error エラーフォーム
     */
    public function _updateCommandData($sessionForm) {
        $comment_start_date = self::_formatDate($sessionForm->comment_start_date);
        $comment_end_date   = self::_formatDate($sessionForm->comment_end_date);

        // DBへ接続
        $db = Sgmov_Component_DB::getAdmin();

        // 情報をDBへ格納
        if (!empty($sessionForm->comment_id)) {
            $id = $sessionForm->comment_id;
            $data = array(
                'comment_id'         => $id,
                'comment_flg'        => $sessionForm->comment_flg,
                'comment_title'      => $sessionForm->comment_title,
                'comment_address'    => $sessionForm->comment_address,
                'comment_name'       => $sessionForm->comment_name,
                'comment_office'     => $sessionForm->comment_office,
                'comment_text'       => $sessionForm->comment_text,
                'comment_start_date' => $comment_start_date[4],
                'comment_end_date'   => $comment_end_date[4],
            );
            $ret = $this->_CommentDataService->_updateCommentData($db, $data);
        } else {
            //登録用IDを取得
            $id = $this->_CommentDataService->select_id($db);
            $data = array(
                'comment_id'         => $id,
                'comment_flg'        => $sessionForm->comment_flg,
                'comment_title'      => $sessionForm->comment_title,
                'comment_address'    => $sessionForm->comment_address,
                'comment_name'       => $sessionForm->comment_name,
                'comment_office'     => $sessionForm->comment_office,
                'comment_text'       => $sessionForm->comment_text,
                'comment_start_date' => $comment_start_date[4],
                'comment_end_date'   => $comment_end_date[4],
            );
            $ret = $this->_CommentDataService->_insertCommentData($db, $data);
        }

        $errorForm = new Sgmov_Form_Error();
        if ($ret === false) {
            $errorForm->addError('top_conflict', '別のユーザーによって更新されています。すべての変更は適用されませんでした。');
        }

        // ファイルアップロード
        $this->uploadCommentFile($id, $errorForm);

        return $errorForm;
    }

    public function uploadCommentFile($id, &$errorForm) {
        //$filePath = Sgmov_Component_Config::getUrlPublicHttp().'/public_html/cmm/files/';
        $filePaths    = array('../../public_html/', '../../maintenance/common/img/');
        $filePathSub  = array('cmm/', 'files/');
        $fileName_1   = $id . '_1.jpg';
        $fileName_2   = $id . '_2.jpg';
        $filePathBase = '';
        foreach ($filePaths as $k1 => $d1) {
            $filePath = $d1;
            foreach ($filePathSub as $k2 => $d2) {
                $filePath .= $d2;
                if (!is_writable($filePath)) {
                    if (!mkdir($filePath)) {
                        $errorForm->addError('top_fileupload', '写真アップロード先のフォルダ作成に失敗しました。');
                        return;
                    }
                    chmod($filePath, 0777);
                }
            }
            if ($k1 == 0) {
                $filePathBase = $filePath;
                if (is_uploaded_file($_FILES["comment_file_1"]["tmp_name"])) {
                    if (move_uploaded_file($_FILES["comment_file_1"]["tmp_name"], $filePath . $fileName_1)) {
                        chmod($filePath . $fileName_1, 0777);
                    } else {
                        $errorForm->addError('top_fileupload', '写真[1]をアップロードできませんでした。');
                    }
                } else {
                    //$errorForm->addError('top_fileupload', '写真[1]にアップロードするファイルが選択されていません。');
                }
                if (is_uploaded_file($_FILES["comment_file_2"]["tmp_name"])) {
                    if (move_uploaded_file($_FILES["comment_file_2"]["tmp_name"], $filePath . $fileName_2)) {
                        chmod($filePath . $fileName_2, 0777);
                    } else {
                        $errorForm->addError('top_fileupload', '写真[2]をアップロードできませんでした。');
                    }
                } else {
                    //$errorForm->addError('top_fileupload', '写真[2]にアップロードするファイルが選択されていません。');
                }
            } else {
                if (is_readable($filePathBase . $fileName_1)) {
                    copy($filePathBase . $fileName_1, $filePath . $fileName_1);
                }
                if (is_readable($filePathBase . $fileName_2)) {
                    copy($filePathBase . $fileName_2, $filePath . $fileName_2);
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

    /**
     * 日付整形
     *
     * @param $s string 日付文字列
     * @return array 日付配列
     */
    private static function _formatDate($s) {

        $matches = array();

        if (empty($s)) {
            return array(
                1 => '',
                2 => '',
                3 => '',
                4 => '',
            );
        }

        // 全角数字を半角に変換する
        $s = mb_convert_kana($s, 'n', 'UTF-8');

        // 日付文字列かチェックする
        if (preg_match('{^\D*(\d{4})\D+(\d{1,2})\D+(\d{1,2})\D*$}u', $s, $matches) === 1
            || preg_match('{^\D*(\d{4})(\d{2})(\d{2})\D*$}u', $s, $matches) === 1
        ) {
            if (strlen($matches[2]) === 1) {
                $matches[2] = '0' . $matches[2];
            }
            if (strlen($matches[3]) === 1) {
                $matches[3] = '0' . $matches[3];
            }
            $matches[4] = $matches[1] . '/' . $matches[2] . '/' . $matches[3];
            return $matches;
        //} elseif (preg_match('{^\D*(\d{4})\D+(\d{1,2})\D*$}u', $s, $matches) === 1
        //    || preg_match('{^\D*(\d{4})(\d{2})\D*$}u', $s, $matches) === 1
        //) {
        //    return $matches;
        //} elseif (preg_match('{^\D*(\d{4})\D*$}u', $s, $matches) === 1) {
        //    return $matches;
        }
        // 日付ではない場合
        return array(
            1 => 'a',
            2 => 'a',
            3 => 'a',
            4 => 'a',
        );
    }
}