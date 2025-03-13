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
Sgmov_Lib::useServices(array('Comiket', 'Shohin'));
Sgmov_Lib::useView('bpn/Common');
Sgmov_Lib::useView('bpn/Confirm');
Sgmov_Lib::useForms(array('Error', 'BpnSession', 'Bpn002Out'));
/**#@-*/

/**
 * 物販お申し込みサイズ変更の確認画面を表示します。
 * @package    View
 * @subpackage BPN
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Bpn_SizeChangeConfirm extends Sgmov_View_Bpn_Confirm {
    
    /**
     * コミケ申込データサービス
     * @var type 
     */
    protected $_Comiket;

    /**
     * 商品マスタ
     * @var type 
     */
    protected $_ShohinService;

    
    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_Comiket = new Sgmov_Service_Comiket();
        $this->_ShohinService = new Sgmov_Service_Shohin();
        
        parent::__construct();
    }

    /**
     * 
     */
    public function executeInner() {

        $parentRes = parent::executeInner();
        
        $session = Sgmov_Component_Session::get();

        $sessionForm = $session->loadForm(self::FEATURE_ID);
        $inForm = $sessionForm->in;
        
        // キャンセル画面判定する。
        $boxNumTotal = 0;
        foreach ($inForm->comiket_box_buppan_num_ary as $key => $val) {
            if (@!empty($val)) {
                $boxNumTotal += (integer) $val;
            }
        }
        
        // DB接続
        $db = Sgmov_Component_DB::getPublic();

        // コミケ申込データ
        $comiketInfo = $this->_Comiket->fetchComiketById($db, $inForm->comiket_id);

        // 物販タイプ（１：物販、２当日物販）
        $parentRes['dispItemInfo']["bpnType"] = $comiketInfo["bpn_type"];

        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // 商品は、min(申込開始) ～　復路申込期間終了(時間あり)期間内にチェック
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        $this->checkShohinInTerm($db, $inForm->eventsub_sel);
      
        // 宅配数量が 全て 0(もしくは空)の場合はキャンセル扱いにする。確認画面で '' をセットすることにより、金額部分を表示しないようにする。
        if (@empty($boxNumTotal)) {
            $comiketBoxInfo = $this->_ShohinService->getShohinAndComiketBox($db, $inForm->comiket_id, "5");
            $buppanArray = array();
            foreach ($comiketBoxInfo as $key => $value) {
                $buppanArray[$key]["name"] = $value["name_display"];
                if(@empty($value["name_display"])){
                    $buppanArray[$key]["name"] = $value["name"];
                }
                $buppanArray[$key]["num"] = $value["num"];
            }

            $outForm = $parentRes['outForm'];

            $outForm->raw_delivery_charge = $comiketInfo['amount_tax'];
            $outForm->raw_delivery_charge_buppan = $comiketInfo['amount_tax'];

            $parentRes['outForm'] = $outForm;
            $parentRes['dispItemInfo']['buppan_lbls'] = $buppanArray;
            $parentRes['dispItemInfo']['is_cancel'] = true;
            $parentRes['dispItemInfo']['src_amount_tax'] = $comiketInfo['amount_tax'];
        }


        return $parentRes;
    }
    
    /**
     * 
     * @param type $comiketId
     * @return string
     */
    protected function getBackInputPath($comiketId) {
        return "size_change";
    }
}