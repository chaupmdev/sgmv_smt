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
Sgmov_Lib::useView('mdr/Common');
Sgmov_Lib::useView('mdr/CheckInput');
Sgmov_Lib::useForms(array('Error', 'EveSession', 'Eve001In', 'Eve002In'));
Sgmov_Lib::useServices(array('HttpsZipCodeDll','ComiketBox'));
/**#@-*/
/**
 * イベント手荷物受付サービスのお申し込み入力情報をチェックします。
 * @package    View
 * @subpackage MDR
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Mdr_SizeChangeCheckInput extends Sgmov_View_Mdr_CheckInput {
    
    /**
     * コミケ申込データサービス
     * @var Sgmov_Service_Comiket 
     */
    protected $_Comiket;
    
    /**
     * コミケ詳細申込データサービス
     * @var Sgmov_Service_ComiketBox 
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
     * @param array $inForm
     * @param array $errorForm
     */
    public function _redirectProc($inForm, $errorForm) {
        if ($errorForm->hasError()) {
            Sgmov_Component_Redirect::redirectPublicSsl('/mdr/size_change');
        }
        
        $boxNumTotal = 0;
        if($inForm->comiket_detail_type_sel == "1") { // 往路
            foreach ($inForm->comiket_box_outbound_num_ary as $val) {
                if (@!empty($val)) {
                    $boxNumTotal += (integer) $val;
                }
            }
        } else { // 復路
            foreach ($inForm->comiket_box_inbound_num_ary as $val) {
                if (@!empty($val)) {
                    $boxNumTotal += (integer) $val;
                }
            }
        }
        
        if (@empty($boxNumTotal)) {
            Sgmov_Component_Redirect::redirectPublicSsl('/mdr/size_change_confirm');
        }
        
        // 個人の場合は、クレジット・コンビニ支払で表示画面切り替え
        switch ($inForm->comiket_payment_method_cd_sel) {
            case '1': // コンビニ
                Sgmov_Component_Redirect::redirectPublicSsl('/mdr/size_change_confirm');
                break;
            case '2': // クレジット
                Sgmov_Component_Redirect::redirectPublicSsl('/mdr/size_change_credit_card');
                break;
            default:
                Sgmov_Component_Redirect::redirectPublicSsl('/mdr/size_change_confirm');
                break;
        }
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_Eve001In $inForm 入力フォーム
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
        $comiketBoxList = $this->_ComiketBox->fetchComiketBoxDataListByIdAndType($db, $inForm->comiket_id, $inForm->comiket_detail_type_sel);
        $totalNumDb = 0;
        foreach ($comiketBoxList as $key => $val) {
            $totalNumDb += (integer) $val['num'];
        }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 搬出
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // 搬出-宅配数量
        if($inForm->comiket_detail_type_sel == "2") { // 復路
            
            ////////////////////////////////////////////////////////////////
            // 共通チェック
            ////////////////////////////////////////////////////////////////
            $this->checkComiketBoxOutInboundNumAry($inForm, $errorForm, $inForm->comiket_box_inbound_num_ary, "comiket_box_inbound_num_ary", "搬出", false, true);
            
            $boxNumTotal = 0;
            
            foreach ($inForm->comiket_box_inbound_num_ary as $key => $val) {
                
                if (@!empty($val)) {
                    $boxNumTotal += (integer) $val;
                }
                
                if ($inForm->comiket_box_inbound_num_ary[$key] == "0") {
                    $inForm->comiket_box_inbound_num_ary[$key] = "";
                }
            }
            
            if (@!empty($boxNumTotal)) {

                if(!$errorForm->hasError()) {
                    ////////////////////////////////////////////////////////////////
                    // 個数チェック（画面からの個数合計）
                    ////////////////////////////////////////////////////////////////
                    $totalNumDisp = 0;
                    foreach ($inForm->comiket_box_inbound_num_ary as $key => $val) {
                        if (@empty($val)) {
                            continue;
                        }
                        $totalNumDisp += (integer) $val;
                    }

                    if ($totalNumDb < $totalNumDisp) {
                        $errorForm->addError("comiket_box_inbound_num_ary", "搬出-宅配数量合計が元の数量合計より大きくなっています。（元の数量合計：{$totalNumDb}個）");
                    }
                }
                
                if(!$errorForm->hasError()) {
                    ////////////////////////////////////////////////////////////////
                    // 金額チェック（前回より金額が増えたらエラー）
                    ////////////////////////////////////////////////////////////////
                    $comiketInfo = $this->_Comiket->fetchComiketById($db, $inForm->comiket_id); // $inForm->comiket_idに設定されている申込IDは、アクセスされたID
                    // $inForm->delivery_charge は入力された個数の金額
                    // $comiketInfo['amount_tax'] は、アクセスされた申込IDの金額
                    if (@!empty($comiketInfo) && $comiketInfo['amount_tax'] < $inForm->delivery_charge) { // 金額が前回の時より多かった場合はエラー
                        $amountTaxOld = number_format($comiketInfo['amount_tax']);
                        $amountTaxNew = number_format($inForm->delivery_charge);
                        $errorForm->addError("comiket_box_inbound_num_ary", "搬出-合計金額が元の金額より大きくなっています。（元の金額：￥{$amountTaxOld}／今回の金額￥{$amountTaxNew}）");
                    }
                }
            }
        }

        return $errorForm;
    }
    
    /**
     * 
     * @param array $post
     * @param array $creditCardForm
     * @return array
     */
    public function _createInFormFromPost($post, $creditCardForm) {
        $session = Sgmov_Component_Session::get();
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        $inForm =  $sessionForm->in;
        
        $inForm->comiket_box_outbound_num_ary =  $this->cstm_filter_input_array(INPUT_POST, 'comiket_box_outbound_num_ary', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY, 'rnask');
        $inForm->comiket_box_inbound_num_ary =  $this->cstm_filter_input_array(INPUT_POST, 'comiket_box_inbound_num_ary', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY, 'rnask');
        
/////////////////////////////////////////////////////////////////////////////////////////////////////////
// 支払方法
/////////////////////////////////////////////////////////////////////////////////////////////////////////
        $calcDataInfoData = $this->calcEveryKindData((array)$inForm);
        $calcDataInfo = $calcDataInfoData["treeData"];
        
        $inForm->delivery_charge = @$calcDataInfo['amount_tax'];
        
        return $inForm;
    }
}