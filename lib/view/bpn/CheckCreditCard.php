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
Sgmov_Lib::useView('bpn/Common');
Sgmov_Lib::useForms(array('Error', 'BpnSession', 'Bpn002In'));
Sgmov_Lib::useServices(array('HttpsZipCodeDll','ComiketBox'));
/**#@-*/
/**
 * 物販お申し込みの入力カード情報をチェックします。
 * @package    View
 * @subpackage BPN
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Bpn_CheckCreditCard extends Sgmov_View_Bpn_Common {
    /**
     * コンストラクタでサービスを初期化します。
     */
    /**
     * コミケ詳細申込データサービス
     * @var type 
     */
    protected $_ComiketBox;


    public function __construct() {
        $this->_ComiketBox = new Sgmov_Service_ComiketBox();
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
     *   bpn/confirm へリダイレクト
     *   </li></ol>
     * </li><li>
     * 入力エラー有り
     *   <ol><li>
     *   bpn/input へリダイレクト
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
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_BPN002, $this->_getTicket());

        // 入力チェック
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        if (empty($sessionForm)) {
            $sessionForm = new Sgmov_Form_BpnSession();
        }

        $inForm = $this->_createInFormFromPost($_POST, $sessionForm->in);


        $comiketBoxList = $this->_ComiketBox->fetchComiketBoxDataListByIdAndType($db, $inForm->comiket_id, "5");
        $getComiketNum = $this->filterComiketBoxnResult($comiketBoxList);
        $originalChangeSuryo = false;
        $allExpiry = 0;
        foreach ($inForm->comiket_box_buppan_num_ary as $key => $value) {
            $checkResult = $this->_ShohinService->checkShohinTerm($db, $key);
            if(@!empty($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], "/bpn/size_change") !== false ){
                if(isset($getComiketNum[$key])){
                    if(($value != $getComiketNum[$key]) &&  (empty($checkResult) || $checkResult["count"] == "0" )){
                        $originalChangeSuryo = true;
                    }
                }
            }else{
                if(!@empty($value) && (empty($checkResult) || $checkResult["count"] == "0" )){
                    $originalChangeSuryo = true;
                }
            }

            if(empty($checkResult) || $checkResult["count"] == "0"){
                $allExpiry++;
            }
        }
        if(count($inForm->comiket_box_buppan_num_ary) == $allExpiry){
            $originalChangeSuryo = true;
        }
        if($originalChangeSuryo){
            if(@!empty($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], "/bpn/size_change") !== false ){
                    Sgmov_Component_Redirect::redirectPublicSsl("/bpn/size_change/");
            }else{
                Sgmov_Component_Redirect::redirectPublicSsl("/bpn/input/");
            }
            exit;
        }

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
        $this->redirectProc($errorForm);
    }
    
    /**
     * 
     * @param type $errorForm
     */
    protected function redirectProc($errorForm) {
        if ($errorForm->hasError()) {
            Sgmov_Component_Redirect::redirectPublicSsl('/bpn/credit_card');
        } else {
            Sgmov_Component_Redirect::redirectPublicSsl('/bpn/confirm');
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
     * @param Sgmov_Form_Bpn001In $inputForm 入力フォーム
     * @return Sgmov_Form_Bpn002In クレジット入力フォーム
     */
    public function _createInFormFromPost($post, $inputForm) {
        $inForm = new Sgmov_Form_Bpn002In();
        
        $inForm = $inputForm;

        // チケット
        $inForm->ticket = $post['ticket'];
        
        // TODO 全画面のcredit_card で送料計算しているが、以下で再度行う
       $calcDataInfoData = $this->calcEveryKindData((array)$inForm);

       $calcDataInfo = $calcDataInfoData["treeDataForBuppan"];
       $inForm->delivery_charge = @$calcDataInfo['amount_tax'];
       $inForm->delivery_charge_buppan = @$calcDataInfo['amount_tax'];
        
        // $inForm->repeater_discoun = "300";
        $inForm->card_number = $post['card_number'];
        $inForm->card_expire_month_cd_sel = $post['card_expire_month_cd_sel'];
        $inForm->card_expire_year_cd_sel = $post['card_expire_year_cd_sel'];
        $inForm->security_cd = $post['security_cd'];

        return $inForm;
    }
    
    
    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_Bpn002In $inForm 入力フォーム
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

    protected function filterComiketBoxnResult($comiketBoxList){
        $returnList = array();
        foreach ($comiketBoxList as $key => $value) {
            $returnList[$value['box_id']] = $value['num'];
        }

        return $returnList;
    }
}