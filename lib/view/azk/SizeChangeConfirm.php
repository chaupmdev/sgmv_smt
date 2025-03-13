<?php

/**#@+
 * include files
 */
require_once dirname(__FILE__).'/../../Lib.php';
Sgmov_Lib::useServices(array('Comiket','ComiketDetail', 'Box'));
Sgmov_Lib::useView('azk/Common');
Sgmov_Lib::useView('azk/Confirm');
Sgmov_Lib::useForms(array('Error', 'AzkSession', 'Azk003Out'));
/**#@-*/

/**
 * イベント手荷物受付サービスのお申し込み確認画面を表示します。
 * @package    View
 * @subpackage AZK
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Azk_SizeChangeConfirm extends Sgmov_View_Azk_Confirm {
    
    /**
     * コミケ申込データサービス
     * @var type 
     */
    protected $_Comiket;

    /**
     * コミケ申込明細データサービス
     * @var type 
     */
    protected $_ComiketDetail;

    /**
     * 宅配箱マスタサービス
     * @var type 
     */
    protected $_BoxService;
    
    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_Comiket = new Sgmov_Service_Comiket();
        $this->_ComiketDetail = new Sgmov_Service_ComiketDetail();
        $this->_BoxService = new Sgmov_Service_Box();
        
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
        
        
        $boxNumTotal = 0;
        foreach ($inForm->comiket_box_num_ary as $key => $val) {
            if (@!empty($val)) {
                $boxNumTotal += (integer) $val;
            }
        }
        
        // 宅配数量が 全て 0(もしくは空)の場合はキャンセル扱いにする。確認画面で '' をセットすることにより、金額部分を表示しないようにする。
        if (@empty($boxNumTotal)) {
            $db = Sgmov_Component_DB::getPublic();
            $comiketInfo = $this->_Comiket->fetchComiketById($db, $inForm->comiket_id);

            $comiketDetailInfo = $this->_ComiketDetail->fetchComiketDetailByComiketId($db, $inForm->comiket_id);
            $dispItemInfo = array();
            foreach ($comiketDetailInfo as $key => $value) {
                $boxInfo = $this->_BoxService->getBoxAndComiketBoxInfo($db, $value["comiket_id"], $value["type"]);
                foreach ($boxInfo as $boxKey => $boxVal) {
                    $dispItemInfo[$boxKey]["name"] = $boxVal["name_display"];
                    if(empty($value["name_display"])){
                        $dispItemInfo[$boxKey]["name"] = $boxVal["name"];
                        $dispItemInfo[$boxKey]["num"] = $boxVal["num"];
                    }
                }
            }

            $outForm = $parentRes['outForm'];
            $outForm->raw_delivery_charge = $comiketInfo['amount_tax'];
            $parentRes['outForm'] = $outForm;
            
            $parentRes['dispItemInfo']['is_cancel'] = true;
            $parentRes['dispItemInfo']['box'] = $dispItemInfo;
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