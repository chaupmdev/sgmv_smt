<?php
/**
 * @package    ClassDefFile
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/**#@+
 * include files
 */
require_once dirname(__FILE__).'/../../Lib.php';
Sgmov_Lib::useView('bpn/Common');
Sgmov_Lib::useView('bpn/CheckInput');
Sgmov_Lib::useForms(array('Error', 'BpnSession', 'Bpn001In', 'Bpn002In'));
Sgmov_Lib::useServices(array('HttpsZipCodeDll','ComiketBox'));
/**#@-*/
/**
 * 物販お申し込みサイズ変更で入力情報をチェックします
 * @package    View
 * @subpackage BPN
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Bpn_SizeChangeCheckInput extends Sgmov_View_Bpn_CheckInput {
    
    /**
     * コミケ申込データサービス
     * @var type 
     */
    protected $_Comiket;
    
    /**
     * コミケ詳細申込データサービス
     * @var type 
     */
    protected $_ComiketBox;
    

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_Comiket = new Sgmov_Service_Comiket();
        $this->_ComiketBox = new Sgmov_Service_ComiketBox();
        
        parent::__construct();
    }

    /**
     *
     * @param type $inForm
     * @param type $errorForm
     */
    public function _redirectProc($inForm, $errorForm) {
        if ($errorForm->hasError()) {
            Sgmov_Component_Redirect::redirectPublicSsl('/bpn/size_change');
        }
    
        $boxNumTotal = 0;
        foreach ($inForm->comiket_box_buppan_num_ary as $key => $val) {
            if (@!empty($val)) {
                $boxNumTotal += (integer) $val;
            }
        }

        if (@empty($boxNumTotal)) {
            Sgmov_Component_Redirect::redirectPublicSsl('/bpn/size_change_confirm');
        }
        
        // 個人の場合は、クレジット・コンビニ支払で表示画面切り替え
        switch ($inForm->comiket_payment_method_cd_sel) {
            case '1': // コンビニ
                Sgmov_Component_Redirect::redirectPublicSsl('/bpn/size_change_confirm');
                break;
            case '2': // クレジット
                Sgmov_Component_Redirect::redirectPublicSsl('/bpn/size_change_credit_card');
                break;
            default:
                Sgmov_Component_Redirect::redirectPublicSsl('/bpn/size_change_confirm');
                break;
        }
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_Pcr001In $inForm 入力フォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($inForm, $db) {
        $errorForm = new Sgmov_Form_Error();
        if (filter_input(INPUT_POST, 'hid_timezone_flg') == '1') {
            $errorForm->addError('event_sel', '選択のイベントは受付時間を超過しています。');
        }

        ////////////////////////////////////////////////////////////////
        // 個数チェック（既存の個数合計）
        ////////////////////////////////////////////////////////////////
        $comiketBoxList = $this->_ComiketBox->fetchComiketBoxDataListByIdAndType($db, $inForm->comiket_id, "5");
        $totalNumDb = 0;
        foreach ($comiketBoxList as $key => $val) {
            $totalNumDb += (integer) $val['num'];
        }

        $getComiketNum = $this->filterComiketBoxnResult($comiketBoxList);
        $shohinList = $this->_ShohinService->fetchShohinByEventSubId($db, $inForm->eventsub_sel);
        $getAllShohin = $this->filterShohinResult($shohinList);
        foreach ($inForm->comiket_box_buppan_num_ary as $key => $value) {
            $checkResult = $this->_ShohinService->checkShohinTerm($db, $key);
             if(isset($getComiketNum[$key])){
                if(($value != $getComiketNum[$key]) && (empty($checkResult) || $checkResult["count"] == "0" )){
                    $errorForm->addError("comiket_box_buppan_num_ary_".$key, "{$getAllShohin[$key]}は申込期間範囲外です。数量を元に戻します。（数量：{$value} => {$getComiketNum[$key]}）");
                }
            }
        }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 物販
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $this->checkSizeChangeBuppanboundNumAry($db, $inForm, $errorForm, $inForm->comiket_box_buppan_num_ary, "comiket_box_buppan_num_ary", "商品");

        $boxNumTotal = 0;
        foreach ($inForm->comiket_box_buppan_num_ary as $key => $val) {
            
            if (@!empty($val)) {
                $boxNumTotal += (integer) $val;
            }
            
            if ($inForm->comiket_box_buppan_num_ary[$key] == "0") {
                $inForm->comiket_box_buppan_num_ary[$key] = "";
            }
        }

        if (@!empty($boxNumTotal)) {
            if(!$errorForm->hasError()) {
                ////////////////////////////////////////////////////////////////
                // 個数チェック（画面からの個数合計）
                ////////////////////////////////////////////////////////////////
                $totalNumDisp = 0;
                foreach ($inForm->comiket_box_buppan_num_ary as $key => $val) {
                    if (@empty($val)) {
                        continue;
                    }
                    $totalNumDisp += (integer) $val;
                }

                if ($totalNumDb < $totalNumDisp) {
                    $errorForm->addError("comiket_box_buppan_num_ary", "商品数量合計が元の数量合計より大きくなっています。（元の数量合計：{$totalNumDb}枚）");
                }
            }

            if(!$errorForm->hasError()) {
                ////////////////////////////////////////////////////////////////
                // 金額チェック（前回より金額が増えたらエラー）
                ////////////////////////////////////////////////////////////////
                $comiketInfo = $this->_Comiket->fetchComiketById($db, $inForm->comiket_id); // $inForm->comiket_idに設定されている申込IDは、アクセスされたID
                // $inForm->delivery_charge は入力された個数の金額
                // $comiketInfo['amount_tax'] は、アクセスされた申込IDの金額
                if (@!empty($comiketInfo) && $comiketInfo['amount_tax'] < $inForm->delivery_charge_buppan) { // 金額が前回の時より多かった場合はエラー
                    $amountTaxOld = number_format($comiketInfo['amount_tax']);
                    $amountTaxNew = number_format($inForm->delivery_charge_buppan);
                    $errorForm->addError("comiket_box_buppan_num_ary", "商品合計金額が元の金額より大きくなっています。（元の金額：￥{$amountTaxOld}／今回の金額￥{$amountTaxNew}）");
                }
            }
        }

        return $errorForm;
    }
    
    /**
     * 
     * @param type $post
     * @param type $creditCardForm
     * @return type
     */
    public function _createInFormFromPost($post, $creditCardForm) {
        $session = Sgmov_Component_Session::get();
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        $inForm =  $sessionForm->in;

        $buppanArray = @$_POST['comiket_box_buppan_num_ary'];
        if (@empty($buppanArray)) {
            $buppanArray = array();
        }

        $zihoShohinArray = @$_POST['comiket_box_buppan_ziko_shohin_cd_ary'];
        if (@empty($zihoShohinArray)) {
            $zihoShohinArray = array();
        }

        $inForm->comiket_box_buppan_num_ary =  $buppanArray;
        $inForm->comiket_box_buppan_ziko_shohin_cd_ary =  $zihoShohinArray;


/////////////////////////////////////////////////////////////////////////////////////////////////////////
// 支払方法
/////////////////////////////////////////////////////////////////////////////////////////////////////////
        $calcDataInfoData = $this->calcEveryKindData((array)$inForm);

        $calcDataInfo = $calcDataInfoData["treeDataForBuppan"];
        $inForm->delivery_charge = @$calcDataInfo['amount_tax'];
        $inForm->delivery_charge_buppan = @$calcDataInfo['amount_tax'];

        return $inForm;
    }

    /**
     * 商品の申込期間を確認する
     * @param db 
     * @param array $buppanArray 
     */
    public function checkShohinTermSizeChange($db, $buppanArray, $errorForm){
        foreach ($buppanArray["comiket_box_buppan_num_ary"] as $key => $value) {
            $check = $this->_ShohinService->checkShohinTerm($db, $key);
            if(@empty($check) || $check["count"] == 0){
                $errorForm->addError("comiket_box_buppan_num_ary_{$key}", "商品{$key}の申込期間範囲外です。");
            }
        }
        return $errorForm;
    }


    protected function filterShohinResult($shohinList){
        $returnList = array();
        foreach ($shohinList as $key => $value) {
            $returnList[$value['id']] = $value['name'];
        }

        return $returnList;
    }





     /**
     *
     * @param type $inForm
     * @param type $errorForm
     * @param type $targetList
     * @param type $targetClassName
     * @param type $errMsgBuppan
     */
    protected function checkSizeChangeBuppanboundNumAry($db, $inForm, &$errorForm, $targetList, $targetClassName, $errMsgBuppan, $isEmptyCheck = true) {
        $result = array(
            "errflg" => FALSE,
            "errData" => array(),
        );
        $notEmptyCount = 0;
        $errorFlg = FALSE;

        $i = 1;
        $totalCnt = count($targetList);
        $shohinList = $this->_ShohinService->fetchShohinByEventSubId($db, $inForm->eventsub_sel);
        $getAllShohin = $this->filterShohinResult($shohinList);
        foreach($targetList as $key => $val) {
            // 0 は法人で使用するためとばす
            if($key == "0") {
                continue;
            }
            $v = Sgmov_Component_Validator::createSingleValueValidator($val)->isNotEmpty();

            if($val == "0"){
                $notEmptyCount++;
            }

            $v = Sgmov_Component_Validator::createSingleValueValidator($val)->isInteger(1)->isWebSystemNg();
            if (!$v->isValid() && $val != "0") {
                $errorFlg = TRUE;
                $errorForm->addError($targetClassName."_".$key, "{$getAllShohin[$key]}の入力値を確認してください。（数値のみ）");
            }

            if($val > 40){
                $errorFlg = TRUE;
                $errorForm->addError($targetClassName."_".$key, "{$getAllShohin[$key]}は40枚まで入力可能としてください。");
            }
            $i++;
        }

        $isErrFlg2 = FALSE;
        // if($errorFlg) {
        //     //$errorForm->addError($targetClassName, "{$errMsgBuppan}の入力値を確認してください。（数値のみ）");
        //     $isErrFlg2 = TRUE;
        // }

        if(!$isErrFlg2) {
            if(!empty($inForm->delivery_charge_buppan)
                    && intval($inForm->delivery_charge_buppan) > 999999) {
                $errorForm->addError($targetClassName, "{$errMsgBuppan}-物販料金は、￥999,999までが取り扱い金額となります。");
            }

            // if($inForm->comiket_payment_method_cd_sel === '3') { // 電子マネー
            //     if(!empty($inForm->delivery_charge_buppan)
            //             && intval($inForm->delivery_charge_buppan) > 10000) {
            //         if(!array_key_exists($targetClassName, $errorForm->_errors)) {
            //             $errorForm->addError($targetClassName, "{$errMsgBuppan}-電子マネーの場合、物販料金は￥10,000までが取り扱い金額となります。");
            //         }
            //     }
            // }
        }
    }

    protected function filterComiketBoxnResult($comiketBoxList){
        $returnList = array();
        foreach ($comiketBoxList as $key => $value) {
            $returnList[$value['box_id']] = $value['num'];
        }

        return $returnList;
    }
}