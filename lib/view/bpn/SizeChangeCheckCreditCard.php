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
Sgmov_Lib::useView('bpn/CheckCreditCard');
Sgmov_Lib::useForms(array('Error', 'BpnSession', 'Bpn002In'));
/**#@-*/
/**
 * 物販お申し込みサイズ変更で入力カード情報をチェックします
 * @package    View
 * @subpackage BPN
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Bpn_SizeChangeCheckCreditCard extends Sgmov_View_Bpn_CheckCreditCard {

    /**
     * 
     * @param type $errorForm
     */
    protected function redirectProc($errorForm) {
        if ($errorForm->hasError()) {
            Sgmov_Component_Redirect::redirectPublicSsl('/bpn/size_change_credit_card');
        } else {
            Sgmov_Component_Redirect::redirectPublicSsl('/bpn/size_change_confirm');
        }
    }
}