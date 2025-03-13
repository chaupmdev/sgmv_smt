<?php

/**
 * @package    ClassDefFile
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
// イベント識別子(URLのpath)はマジックナンバーで記述せずこの変数で記述してください
$dirDiv = Sgmov_Lib::setDirDiv(dirname(__FILE__));
Sgmov_Lib::useView($dirDiv . '/Common');
Sgmov_Lib::useView($dirDiv . '/CheckCreditCard');
Sgmov_Lib::useForms(array('Error', 'QrcSession', 'Qrc002In'));
/**#@-*/
/**
 * イベント手荷物受付サービスのサイズ変更クレジットカード入力情報をチェックします。
 * @package    View
 * @subpackage RMS
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Qrc_SizeChangeCheckCreditCard extends Sgmov_View_Qrc_CheckCreditCard
{

    // 識別子
    protected $_DirDiv;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        // 識別子のセット
        $this->_DirDiv = Sgmov_Lib::setDirDiv(dirname(__FILE__));

        parent::__construct();
    }
    /**
     *
     * @param type $errorForm
     */
    protected function redirectProc($errorForm)
    {
        if ($errorForm->hasError()) {
            Sgmov_Component_Redirect::redirectPublicSsl('/' . $this->_DirDiv . '/size_change_credit_card');
        } else {
            Sgmov_Component_Redirect::redirectPublicSsl('/' . $this->_DirDiv . '/size_change_confirm');
        }
    }
}
