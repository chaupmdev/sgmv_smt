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
Sgmov_Lib::useView('ptu/Common');
Sgmov_Lib::useForms(array('Error', 'PtuSession', 'Ptu002In'));
/**#@-*/
/**
 * 単身カーゴプランのお申し込み入力情報をチェックします。
 * @package    View
 * @subpackage PTU
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Ptu_CheckCreditCard extends Sgmov_View_Ptu_Common {
    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
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
     *   ptu/confirm へリダイレクト
     *   </li></ol>
     * </li><li>
     * 入力エラー有り
     *   <ol><li>
     *   ptu/input へリダイレクト
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
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_PTU002, $this->_getTicket());

        // 入力チェック
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        if (empty($sessionForm)) {
            $sessionForm = new Sgmov_Form_PtuSession();
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
        $session->saveForm(self::FEATURE_ID, $sessionForm);

        // リダイレクト
        if ($errorForm->hasError()) {
            Sgmov_Component_Redirect::redirectPublicSsl('/ptu/credit_card');
        } else {
            Sgmov_Component_Redirect::redirectPublicSsl('/ptu/confirm');
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
     * @param Sgmov_Form_Ptu001In $inputForm 入力フォーム
     * @return Sgmov_Form_Ptu002In クレジット入力フォーム
     */
    public function _createInFormFromPost($post, $inputForm) {
        $inForm = new Sgmov_Form_Ptu002In();

        // チケット
        $inForm->ticket = $post['ticket'];

        $inForm->binshu_cd  = $inputForm->binshu_cd;

        $inForm->surname  = $inputForm->surname;
        $inForm->forename = $inputForm->forename;

        $inForm->tel1 = $inputForm->tel1;
        $inForm->tel2 = $inputForm->tel2;
        $inForm->tel3 = $inputForm->tel3;

        $inForm->fax1 = $inputForm->fax1;
        $inForm->fax2 = $inputForm->fax2;
        $inForm->fax3 = $inputForm->fax3;

        $inForm->mail        = $inputForm->mail;
        $inForm->retype_mail = $inputForm->retype_mail;

        $inForm->zip1        = $inputForm->zip1;
        $inForm->zip2        = $inputForm->zip2;
        $inForm->pref_cd_sel = $inputForm->pref_cd_sel;
        $inForm->address     = $inputForm->address;
        $inForm->building    = $inputForm->building;

        $inForm->surname_hksaki  = $inputForm->surname_hksaki;
        $inForm->forename_hksaki = $inputForm->forename_hksaki;

        $inForm->zip1_hksaki        = $inputForm->zip1_hksaki;
        $inForm->zip2_hksaki        = $inputForm->zip2_hksaki;
        $inForm->pref_cd_sel_hksaki = $inputForm->pref_cd_sel_hksaki;
        $inForm->address_hksaki     = $inputForm->address_hksaki;
        $inForm->building_hksaki    = $inputForm->building_hksaki;
        $inForm->tel1_hksaki = $inputForm->tel1_hksaki;
        $inForm->tel2_hksaki = $inputForm->tel2_hksaki;
        $inForm->tel3_hksaki = $inputForm->tel3_hksaki;
        $inForm->tel1_fuzai_hksaki = $inputForm->tel1_fuzai_hksaki;
        $inForm->tel2_fuzai_hksaki = $inputForm->tel2_fuzai_hksaki;
        $inForm->tel3_fuzai_hksaki = $inputForm->tel3_fuzai_hksaki;

        $inForm->hikitori_yotehiji_date_year_cd_sel  = $inputForm->hikitori_yotehiji_date_year_cd_sel;
        $inForm->hikitori_yotehiji_date_month_cd_sel = $inputForm->hikitori_yotehiji_date_month_cd_sel;
        $inForm->hikitori_yotehiji_date_day_cd_sel   = $inputForm->hikitori_yotehiji_date_day_cd_sel;

        $inForm->hikitori_yoteji_sel       		  = $inputForm->hikitori_yoteji_sel;
        if ($inputForm->hikitori_yoteji_sel == '2') {
        	$inForm->hikitori_yotehiji_time_cd_sel    = $inputForm->hikitori_yotehiji_time_cd_sel;
        } else if ($inputForm->hikitori_yoteji_sel == '3') {
        	$inForm->hikitori_yotehiji_justime_cd_sel= $inputForm->hikitori_yotehiji_justime_cd_sel;
        }

        $inForm->hikoshi_yotehiji_date_year_cd_sel  = $inputForm->hikoshi_yotehiji_date_year_cd_sel;
        $inForm->hikoshi_yotehiji_date_month_cd_sel = $inputForm->hikoshi_yotehiji_date_month_cd_sel;
        $inForm->hikoshi_yotehiji_date_day_cd_sel   = $inputForm->hikoshi_yotehiji_date_day_cd_sel;

        $inForm->hikoshi_yoteji_sel       		  = $inputForm->hikoshi_yoteji_sel;
        if ($inputForm->hikoshi_yoteji_sel == '2') {
        	$inForm->hikoshi_yotehiji_time_cd_sel    = $inputForm->hikoshi_yotehiji_time_cd_sel;
        } else if ($inputForm->hikoshi_yoteji_sel == '3') {
        	$inForm->hikoshi_yotehiji_justime_cd_sel= $inputForm->hikoshi_yotehiji_justime_cd_sel;
        }

        if ($inputForm->binshu_cd == self::BINSHU_TANPINYOSO) {
        	$inForm->tanhin_cd_sel = $inputForm->tanhin_cd_sel;
        	$inForm->tanNmFree = $inputForm->tanNmFree;
        } else {
        	$inForm->cago_daisu = $inputForm->cago_daisu;
        }

        $inForm->hidden_kihonKin = $inputForm->hidden_kihonKin;
        $inForm->hidden_hanshutsuSum = $inputForm->hidden_hanshutsuSum;
        $inForm->hidden_hannyuSum = $inputForm->hidden_hannyuSum;
        $inForm->hidden_mitumoriZeinuki = $inputForm->hidden_mitumoriZeinuki;
        $inForm->hidden_zeiKin = $inputForm->hidden_zeiKin;
        $inForm->hidden_mitumoriZeikomi = $inputForm->hidden_mitumoriZeikomi;

        $inForm->checkboxHanshutsu = isset($inputForm->checkboxHanshutsu) ? $inputForm->checkboxHanshutsu : '';
        $inForm->textHanshutsu = $inputForm->textHanshutsu;
        $inForm->checkboxHannyu = isset($inputForm->checkboxHannyu) ? $inputForm->checkboxHannyu : '';
        $inForm->textHannyu = $inputForm->textHannyu;

        $inForm->payment_method_cd_sel    = isset($inputForm->payment_method_cd_sel) ? $inputForm->payment_method_cd_sel : '';
        $inForm->convenience_store_cd_sel = isset($inputForm->convenience_store_cd_sel) ? $inputForm->convenience_store_cd_sel : '';

        $inForm->card_number = $post['card_number'];
        $inForm->card_expire_month_cd_sel = $post['card_expire_month_cd_sel'];
        $inForm->card_expire_year_cd_sel = $post['card_expire_year_cd_sel'];
        $inForm->security_cd = $post['security_cd'];

        return $inForm;
    }
    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_Ptu002In $inForm 入力フォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($inForm, $db) {

        // 入力チェック
        $errorForm = new Sgmov_Form_Error();

        // 有効期限 月 必須チェック 桁数チェック 半角数値チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->card_expire_month_cd_sel)->isNotEmpty()->isInteger()->isLengthLessThanOrEqualTo(2)->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_card_expire_month_cd_sel', $v->getResultMessageTop());
        }
        // 有効期限 年 必須チェック 桁数チェック 半角数値チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->card_expire_year_cd_sel)->isNotEmpty()->isInteger()->isLengthMoreThanOrEqualTo(4)->isLengthLessThanOrEqualTo(4)->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_card_expire_year_cd_sel', $v->getResultMessageTop());
        }

        // エラーがない場合は有効期限存在チェック
        if (!$errorForm->hasError()) {
            $v = Sgmov_Component_Validator::createDateValidator($inForm->card_expire_year_cd_sel, $inForm->card_expire_month_cd_sel, '01');
            $date = new DateTime();
            $date = new DateTime($date->format('Y-m-01'));
            $min = intval($date->format('U'));
            $v->isDate($min);
            if (!$v->isValid()) {
                //$errorForm->addError('top_card_expire_month_cd_sel', $v->getResultMessageTop());
                $errorForm->addError('top_card_expire', 'が切れています。');
            }
        }

        // クレジットカード番号 必須チェック 桁数チェック 半角数値チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->card_number)->isNotEmpty()->isInteger()->isLengthMoreThanOrEqualTo(14)->isLengthLessThanOrEqualTo(16)->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_card_number', $v->getResultMessageTop());
        }
        // セキュリティコード 必須チェック 桁数チェック 半角数値チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->security_cd)->isNotEmpty()->isInteger()->isLengthMoreThanOrEqualTo(3)->isLengthLessThanOrEqualTo(4)->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_security_cd', $v->getResultMessageTop());
        }

        return $errorForm;
    }
}