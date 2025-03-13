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
Sgmov_Lib::useView('eve/Common');
Sgmov_Lib::useView('eve/CheckCreditCard');
Sgmov_Lib::useForms(array('Error', 'EveSession', 'Eve002In'));
/**#@-*/
/**
 * イベント手荷物受付サービスのサイズ変更クレジットカード入力情報をチェックします。
 * @package    View
 * @subpackage EVE
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Eve_SizeChangeCheckCreditCard extends Sgmov_View_Eve_CheckCreditCard {

    /**
     * 
     * @param type $errorForm
     */
    protected function redirectProc($errorForm) {
Sgmov_Component_Log::debug('################# 601-1');
        if ($errorForm->hasError()) {
            Sgmov_Component_Redirect::redirectPublicSsl('/eve/size_change_credit_card');
        } else {
            Sgmov_Component_Redirect::redirectPublicSsl('/eve/size_change_confirm');
        }
    }
}