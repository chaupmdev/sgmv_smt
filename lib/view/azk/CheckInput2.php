<?php
/**#@+
 * include files
 */
require_once dirname(__FILE__).'/../../Lib.php';
Sgmov_Lib::useView('azk/Common');
Sgmov_Lib::useView('azk/CheckInput');
Sgmov_Lib::useForms(array('Error', 'AzkSession', 'Azk001In', 'Azk002In'));
Sgmov_Lib::useServices(array('HttpsZipCodeDll'));
/**#@-*/
/**
 * イベント手荷物受付サービスのお申し込み入力情報をチェックします。
 * @package    View
 * @subpackage AZK
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Azk_CheckInput2 extends Sgmov_View_Azk_CheckInput {

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     *
     * @param type $inForm
     * @param type $errorForm
     */
    public function _redirectProc($inForm, $errorForm) {
        if ($errorForm->hasError()) {
            Sgmov_Component_Redirect::redirectPublicSsl('/azk/input2'.$inForm->shikibetsushi);
        }

        // 個人の場合は、クレジット・コンビニ支払で表示画面切り替え
        switch ($inForm->comiket_payment_method_cd_sel) {
            // case '1': // コンビニ
            //     Sgmov_Component_Redirect::redirectPublicSsl('/azk/confirm');
                //break;
            case '2': // クレジット
                Sgmov_Component_Redirect::redirectPublicSsl('/azk/credit_card');
                break;
            default:
                Sgmov_Component_Redirect::redirectPublicSsl('/azk/confirm');
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


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 支払方法
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $this->_checkPaymentMethod($inForm, $errorForm);



        return $errorForm;
    }
}