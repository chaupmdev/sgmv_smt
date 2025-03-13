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
Sgmov_Lib::useServices(array('Comiket',));
Sgmov_Lib::useView('sso/Common');
Sgmov_Lib::useView('sso/Confirm');
Sgmov_Lib::useForms(array('Error', 'EveSession', 'Eve003Out'));
/**#@-*/

/**
 * イベント手荷物受付サービスのお申し込み確認画面を表示します。
 * @package    View
 * @subpackage EVE
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Sso_SizeChangeConfirm extends Sgmov_View_Sso_Confirm {
    
    /**
     * コミケ申込データサービス
     * @var type 
     */
    protected $_Comiket;
    
    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_Comiket = new Sgmov_Service_Comiket();
        
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
        if($inForm->comiket_detail_type_sel == "1") { // 往路
            foreach ($inForm->comiket_box_outbound_num_ary as $key => $val) {
                if (@!empty($val)) {
                    $boxNumTotal += (integer) $val;
                }
            }
        } else { // 復路
            foreach ($inForm->comiket_box_inbound_num_ary as $key => $val) {
                if (@!empty($val)) {
                    $boxNumTotal += (integer) $val;
                }
            }
        }
        
        // 宅配数量が 全て 0(もしくは空)の場合はキャンセル扱いにする。確認画面で '' をセットすることにより、金額部分を表示しないようにする。
        if (@empty($boxNumTotal)) {
            $db = Sgmov_Component_DB::getPublic();
            $comiketInfo = $this->_Comiket->fetchComiketById($db, $inForm->comiket_id);
            $outForm = $parentRes['outForm'];
            $outForm->raw_delivery_charge = $comiketInfo['amount_tax'];
            $parentRes['outForm'] = $outForm;
            
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