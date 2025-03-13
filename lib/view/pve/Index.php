<?php
/**
 * @package    ClassDefFile
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/**#@+
 * include files
 */
require_once dirname(__FILE__).'/../../Lib.php';
Sgmov_Lib::useView('pve/Common');
Sgmov_Lib::useForms(array('PveSession', 'Pve001In'));
/**#@-*/

/**
 * 訪問見積もり申し込みりのセッション情報を破棄し、訪問見積もり申し込み入力画面を開きます
 * @package    View
 * @subpackage PVE
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pve_Index extends Sgmov_View_Pve_Common {

    /**
     * 処理を実行します。
     *
     */
    public function executeInner() {

        // セッションに情報があるかどうかを確認
        $session = Sgmov_Component_Session::get();
        $fromPveForm = $session->loadForm(self::FEATURE_ID);
        $fromPreForm = $session->loadForm(self::SCRID_TOPVE);

        // セッション情報の削除
        if (isset($fromPveForm) && $fromPveForm != NULL) {
            Sgmov_Component_Log::debug('セッション情報を削除します。');
            $session->deleteForm(self::FEATURE_ID);
        } else {
            Sgmov_Component_Log::debug('セッション情報は存在しません。');
        }
        if (isset($fromPreForm) && $fromPreForm != NULL) {
            Sgmov_Component_Log::debug('セッション情報を削除します。');
            $session->deleteForm(self::SCRID_TOPVE);
        } else {
            Sgmov_Component_Log::debug('セッション情報は存在しません。');
        }

        // 情報をセッションに保存
        $sessionForm = new Sgmov_Form_PveSession();
        $sessionForm->in = $this->_createInFormByMenuForm($_POST);
        $session->saveForm(self::FEATURE_ID, $sessionForm);

        // 訪問見積もり申し込み入力画面に遷移
        Sgmov_Component_Redirect::redirectPublicSsl('/pve/input/');
    }

    /**
     * メニューフォームの値を元に入力フォームを生成します。
     * @return Sgmov_Form_Pve001In 入力フォーム
     */
    public function _createInFormByMenuForm($post) {

        $inForm = new Sgmov_Form_Pve001In();

        if (isset($post['plan_cd_sel'])) {
            $inForm->plan_cd_sel = $post['plan_cd_sel'];
        } else {
            $inForm->plan_cd_sel = '';
        }

        return $inForm;
    }
}