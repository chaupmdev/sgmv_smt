<?php
/**
 * @package    ClassDefFile
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/**#@+
 * include files
 */
require_once dirname(__FILE__).'/../../Lib.php';
Sgmov_Lib::useView('sso/Common');
Sgmov_Lib::useForms(array('Error', 'EveSession', 'Eve002In'));
/**#@-*/
/**
 * イベント手荷物受付サービスのお申し込み入力情報をチェックします。
 * @package    View
 * @subpackage EVE
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Sso_CheckCreditCard extends Sgmov_View_Sso_Common {
    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        parent::__construct();
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
     *   sso/confirm へリダイレクト
     *   </li></ol>
     * </li><li>
     * 入力エラー有り
     *   <ol><li>
     *   sso/input へリダイレクト
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
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_EVE002, $this->_getTicket());

        // 入力チェック
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        if (empty($sessionForm)) {
            $sessionForm = new Sgmov_Form_EveSession();
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
        
//Sgmov_Component_Log::debug("##################################### 201 check_credit_card");
//Sgmov_Component_Log::debug($sessionForm->in);

        // リダイレクト
        $this->redirectProc($errorForm);
    }
    
    /**
     * 
     * @param type $errorForm
     */
    protected function redirectProc($errorForm) {
        if ($errorForm->hasError()) {
            Sgmov_Component_Redirect::redirectPublicSsl('/sso/credit_card');
        } else {
            Sgmov_Component_Redirect::redirectPublicSsl('/sso/confirm');
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
     * @param Sgmov_Form_Eve001In $inputForm 入力フォーム
     * @return Sgmov_Form_Eve002In クレジット入力フォーム
     */
    public function _createInFormFromPost($post, $inputForm) {
        $inForm = new Sgmov_Form_Eve002In();
        
        $inForm = $inputForm;

        // チケット
        $inForm->ticket = $post['ticket'];
        
        // TODO 全画面のcredit_card で送料計算しているが、以下で再度行う
        $calcDataInfoData = $this->calcEveryKindData((array)$inForm);
        $calcDataInfo = $calcDataInfoData["treeData"];
        $inForm->delivery_charge = @$calcDataInfo['amount_tax'];
        
//        $inForm->delivery_charge = "2000";
//        $inForm->repeater_discoun = "300";
        $inForm->card_number = $post['card_number'];
        $inForm->card_expire_month_cd_sel = $post['card_expire_month_cd_sel'];
        $inForm->card_expire_year_cd_sel = $post['card_expire_year_cd_sel'];
        $inForm->security_cd = $post['security_cd'];
//
//        $inForm->surname  = $inputForm->surname;
//        $inForm->forename = $inputForm->forename;
//        $inForm->surname_furigana  = $inputForm->surname_furigana;
//        $inForm->forename_furigana = $inputForm->forename_furigana;
//
//        $inForm->number_persons = $inputForm->number_persons;
//
//        $inForm->tel1 = $inputForm->tel1;
//        $inForm->tel2 = $inputForm->tel2;
//        $inForm->tel3 = $inputForm->tel3;
//
//        $inForm->mail        = $inputForm->mail;
//        $inForm->retype_mail = $inputForm->retype_mail;
//
//        $inForm->zip1        = $inputForm->zip1;
//        $inForm->zip2        = $inputForm->zip2;
//        $inForm->pref_cd_sel = $inputForm->pref_cd_sel;
//        $inForm->address     = $inputForm->address;
//        $inForm->building    = $inputForm->building;
//
//        $inForm->travel_agency_cd_sel = $inputForm->travel_agency_cd_sel;
//        $inForm->travel_cd_sel = $inputForm->travel_cd_sel;
//        $inForm->room_number = $inputForm->room_number;
//        $inForm->terminal_cd_sel = isset($inputForm->terminal_cd_sel)?$inputForm->terminal_cd_sel:null;
//        $inForm->departure_quantity = $inputForm->departure_quantity;
//        $inForm->arrival_quantity = $inputForm->arrival_quantity;
//        $inForm->travel_departure_cd_sel = isset($inputForm->travel_departure_cd_sel)?$inputForm->travel_departure_cd_sel:null;
//        
//        $inForm->cargo_collection_date_year_cd_sel  = $inputForm->cargo_collection_date_year_cd_sel;
//        $inForm->cargo_collection_date_month_cd_sel = $inputForm->cargo_collection_date_month_cd_sel;
//        $inForm->cargo_collection_date_day_cd_sel   = $inputForm->cargo_collection_date_day_cd_sel;
//        $inForm->cargo_collection_st_time_cd_sel    = $inputForm->cargo_collection_st_time_cd_sel;
//        //$inForm->cargo_collection_st_minute_cd_sel  = $inputForm->cargo_collection_st_minute_cd_sel;
//        $inForm->cargo_collection_ed_time_cd_sel    = $inputForm->cargo_collection_ed_time_cd_sel;
//        //$inForm->cargo_collection_ed_minute_cd_sel  = $inputForm->cargo_collection_ed_minute_cd_sel;
//
//        $inForm->travel_arrival_cd_sel = isset($inputForm->travel_arrival_cd_sel)?$inputForm->travel_arrival_cd_sel:null;
//        $inForm->payment_method_cd_sel = isset($inputForm->payment_method_cd_sel)?$inputForm->payment_method_cd_sel:null;
//        $inForm->convenience_store_cd_sel = isset($inputForm->convenience_store_cd_sel)?$inputForm->convenience_store_cd_sel:null;
        

        return $inForm;
    }
    
    
    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_Eve002In $inForm 入力フォーム
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