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
Sgmov_Lib::useView('pin/Common');
Sgmov_Lib::useForms(array('Error', 'PinSession', 'Pin001In'));
/**#@-*/

 /**
 * お問い合わせ入力情報をチェックします。
 * @package    View
 * @subpackage PIN
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pin_CheckInput extends Sgmov_View_Pin_Common
{
    
    /**
     * 都道府県サービス
     * @var Sgmov_Service_Prefecture
     */
    public $_PrefectureService;
    
    /**
     * 郵便番号DLLサービス
     * @var Sgmov_Service_SocketZipCodeDll
     */
    public $_SocketZipCodeDll;
    
    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_PrefectureService = new Sgmov_Service_Prefecture();
        $this->_SocketZipCodeDll = new Sgmov_Service_SocketZipCodeDll();
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
     *   pin/confirm へリダイレクト
     *   </li></ol>
     * </li><li>
     * 入力エラー有り
     *   <ol><li>
     *   pin/input へリダイレクト
     *   </li></ol>
     * </li></ol>
     */
    public function executeInner()
    {
        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();

        // チケットの確認と破棄
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_PIN001, $this->_getTicket());

        // 入力チェック
        $inForm = $this->_createInFormFromPost($_POST);
        $errorForm = $this->_validate($inForm);

        // 情報をセッションに保存
        $sessionForm = new Sgmov_Form_PinSession();
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
            Sgmov_Component_Redirect::redirectPublicSsl('/pin/input/');
        } else {
            Sgmov_Component_Redirect::redirectPublicSsl('/pin/confirm/');
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
     * @param array $post ポスト情報
     * @return Sgmov_Form_Pin001In 入力フォーム
     */
    public function _createInFormFromPost($post)
    {
        $inForm = new Sgmov_Form_Pin001In();

        if (isset($post['inquiry_type_cd_sel'])) {
            $inForm->inquiry_type_cd_sel = $post['inquiry_type_cd_sel'];
        } else {
            $inForm->inquiry_type_cd_sel = '';
        }

        if (isset($post['need_reply_cd_sel'])) {
            $inForm->need_reply_cd_sel = $post['need_reply_cd_sel'];
        } else {
            $inForm->need_reply_cd_sel = '';
        }

        $inForm->company_name = $post['company_name'];
        $inForm->name = $post['name'];
        $inForm->furigana = $post['furigana'];
        $inForm->tel1 = $post['tel1'];
        $inForm->tel2 = $post['tel2'];
        $inForm->tel3 = $post['tel3'];
        $inForm->mail = $post['mail'];
        $inForm->zip1 = $post['zip1'];
        $inForm->zip2 = $post['zip2'];
        $inForm->pref_cd_sel = $post['pref_cd_sel'];
        $inForm->address = $post['address'];
        $inForm->inquiry_title = $post['inquiry_title'];
        $inForm->inquiry_content = $post['inquiry_content'];
        $inForm->chb_agreement = $post['chb_agreement'];// filter_input(INPUT_POST, 'chb_agreement');
        return $inForm;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_Pin001In $inForm 入力フォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($inForm)
    {
        // 都道府県を取得
        $db = Sgmov_Component_DB::getPublic();
        $service = new Sgmov_Service_Prefecture();
        $prefs = $service->fetchPrefectures($db);

        // 通常の入力ではありえない値の場合はシステムエラー
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->inquiry_type_cd_sel)->

                                        isIn(array_keys($this->inquiry_type_lbls));
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->need_reply_cd_sel)->

                                        isIn(array_keys($this->need_reply_lbls));
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->pref_cd_sel)->

                                        isIn($prefs['ids']);
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }

        // 入力チェック
        $errorForm = new Sgmov_Form_Error();

        // お問い合わせ種類コード選択値
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->inquiry_type_cd_sel)->isNotEmpty();
        if (!$v->isValid()) {
            $errorForm->addError('top_inquiry_type_cd_sel', $v->getResultMessageTop());
        }

        // ＳＧムービングからの回答コード選択値
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->need_reply_cd_sel)->isNotEmpty();
        if (!$v->isValid()) {
            $errorForm->addError('top_need_reply_cd_sel', $v->getResultMessageTop());
        }

        // 会社名
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->company_name)->

                                        isLengthLessThanOrEqualTo(80);
        if (!$v->isValid()) {
            $errorForm->addError('top_company_name', $v->getResultMessageTop());
        }

        // お名前
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->name)->

                                        isNotEmpty()->

                                        isLengthLessThanOrEqualTo(40);
        if (!$v->isValid()) {
            $errorForm->addError('top_name', $v->getResultMessageTop());
        }

        // フリガナ
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->furigana)->

                                        isNotEmpty()->

                                        isLengthLessThanOrEqualTo(40);
        if (!$v->isValid()) {
            $errorForm->addError('top_furigana', $v->getResultMessageTop());
        }

        // 電話番号
        $v = Sgmov_Component_Validator::createPhoneValidator($inForm->tel1, $inForm->tel2, $inForm->tel3)->isPhone();
        if (!$v->isValid()) {
            $errorForm->addError('top_tel', $v->getResultMessageTop());
        }

        // メールアドレス
        if ($inForm->need_reply_cd_sel !== '0') {
            // 回答が必要な場合はメールアドレスは必須である
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->mail)->

                                            isNotEmpty()->

                                            isLengthLessThanOrEqualTo(80)->

                                            isMail();
            if (!$v->isValid()) {
                $errorForm->addError('top_mail_2', $v->getResultMessageTop());
            }
        } else {
            // 回答が不要な場合はメールアドレスは必須ではない
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->mail)->

                                            isLengthLessThanOrEqualTo(80)->

                                            isMail();
            if (!$v->isValid()) {
                $errorForm->addError('top_mail', $v->getResultMessageTop());
            }
        }
        
        // 郵便番号(最後に存在確認をするので別名でバリデータを作成)
        $zipV = Sgmov_Component_Validator::createZipValidator($inForm->zip1, $inForm->zip2)->isZipCode();
        if (!$zipV->isValid()) {
            $errorForm->addError('top_zip', $zipV->getResultMessageTop());
        }


        // 都道府県

        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->pref_cd_sel);

        $v->isSelected();

        if (!$v->isValid()) {

            $errorForm->addError('top_pref_cd_sel', $v->getResultMessageTop());

        }


        // 住所
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->address)->

                                        isLengthLessThanOrEqualTo(80);
        if (!$v->isValid()) {
            $errorForm->addError('top_address', $v->getResultMessageTop());
        }

        // お問い合わせ件名
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->inquiry_title)->

                                        isLengthLessThanOrEqualTo(80);
        if (!$v->isValid()) {
            $errorForm->addError('top_inquiry_title', $v->getResultMessageTop());
        }

        // お問い合わせ内容
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->inquiry_content)->

                                        isNotEmpty()->

                                        isLengthLessThanOrEqualTo(1000);
        if (!$v->isValid()) {
            $errorForm->addError('top_inquiry_content', $v->getResultMessageTop());
        }

        // エラーがない場合は郵便番号存在チェック
        if (!$errorForm->hasError()) {
            $zipV->zipCodeExist();
            if (!$zipV->isValid()) {
                $errorForm->addError('top_zip', $zipV->getResultMessageTop());
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
