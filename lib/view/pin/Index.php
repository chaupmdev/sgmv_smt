<?php
/**
 * @package    ClassDefFile
 * @author     H.Tsuji(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__).'/../../Lib.php';
Sgmov_Lib::useView('pin/Common');
Sgmov_Lib::useForms(array('PinSession'));
/**#@-*/

 /**
 * お問い合わせのセッション情報を破棄し、お問い合わせ入力画面に遷移します。
 * @package    View
 * @subpackage PIN
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pin_Index extends Sgmov_View_Pin_Common {

    /**
     * 処理を実行します。
     *
     */
    public function executeInner() {

        // セッションに情報があるかどうかを確認
        $session = Sgmov_Component_Session::get();
        $sessionForm = $session->loadForm(self::FEATURE_ID);

        // セッション情報の削除
        if (isset($fromPreForm) && $fromPreForm != NULL) {
            Sgmov_Component_Log::debug('セッション情報を削除します。');
            $session->deleteForm(self::FEATURE_ID);
        } else {
            Sgmov_Component_Log::debug('セッション情報は存在しません。');
        }

        // 情報をセッションに保存
        $sessionForm = new Sgmov_Form_PinSession();
        $sessionForm->in = $this->_createInFormByMenuForm($_POST);
        $session->saveForm(self::FEATURE_ID, $sessionForm);

        // お問い合わせ入力画面に遷移
        Sgmov_Component_Redirect::redirectPublicSsl('/pin/input/');
    }

    /**
     * メニューフォームの値を元に入力フォームを生成します。
     * @return Sgmov_Form_Pin001In 入力フォーム
     */
    public function _createInFormByMenuForm($post) {

        $inForm = new Sgmov_Form_Pin001In();

        if (isset($post['inquiry_type_cd_sel'])) {
            $inForm->inquiry_type_cd_sel = $post['inquiry_type_cd_sel'];
        } else {
            $inForm->inquiry_type_cd_sel = '';
        }

        return $inForm;
    }
}