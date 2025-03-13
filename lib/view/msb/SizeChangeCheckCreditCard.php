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
Sgmov_Lib::useView('dsn/Common');
Sgmov_Lib::useView('dsn/CheckCreditCard');
Sgmov_Lib::useForms(array('Error', 'EveSession', 'Eve002In'));
/**#@-*/
/**
 * イベント手荷物受付サービスのサイズ変更クレジットカード入力情報をチェックします。
 * @package    View
 * @subpackage DSN
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Dsn_SizeChangeCheckCreditCard extends Sgmov_View_Dsn_CheckCreditCard {

    /**
     * 
     * @param type $errorForm
     */
    protected function redirectProc($errorForm) {
        if ($errorForm->hasError()) {
            Sgmov_Component_Redirect::redirectPublicSsl('/dsn/size_change_credit_card');
        } else {
            Sgmov_Component_Redirect::redirectPublicSsl('/dsn/size_change_confirm');
        }
    }
}