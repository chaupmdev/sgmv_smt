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
Sgmov_Lib::useView('pre/Common');
Sgmov_Lib::useForms(array('PveSession', 'Pre001Out'));
/**#@-*/

/**
 * 概算見積りのセッション情報を破棄し、概算見積り入力画面を開きます
 * @package    View
 * @subpackage PRE
 * @author     H.Tsuji(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pre_Index extends Sgmov_View_Pre_Common {

    /**
     * 処理を実行します。
     *
     */
    public function executeInner() {

        $url = '';
        if (empty($_POST)) {
            // GETパラメータ取得
            $getParam = $this->_parseGetParameter();

            // リダイレクトゲットパラメータの生成
            $url = $this->createUrl($getParam);
        }

        // セッションに情報があるかどうかを確認
        $session = Sgmov_Component_Session::get();
        $fromPreForm = $session->loadForm(self::SCRID_PRE);

        // セッション情報の削除
        if (isset($fromPreForm) && $fromPreForm != NULL) {
            Sgmov_Component_Log::debug('セッション情報を削除します。');
            $session->deleteForm(self::SCRID_PRE);
        } else {
            Sgmov_Component_Log::debug('セッション情報は存在しません。');
        }

        if (!empty($_POST)) {
            // 情報をセッションに保存
            $sessionForm = new Sgmov_Form_PveSession();
            $sessionForm->in = $this->_createInFormByMenuForm($_POST);
            $session->saveForm(self::SCRID_PRE, $sessionForm);
        }

        // 概算見積り入力画面に遷移
        Sgmov_Component_Redirect::redirectPublicSsl('/pre/input' . $url);
    }

    /**
     * メニューフォームの値を元に入力フォームを生成します。
     * @return Sgmov_Form_Pre002In 入力フォーム
     */
    public function _createInFormByMenuForm($post) {

        $inForm = new Sgmov_Form_Pre001Out();

        if (isset($post['plan_cd_sel'])) {
            $inForm->raw_plan_cd_sel = $post['plan_cd_sel'];
        } else {
            $inForm->raw_plan_cd_sel = '';
        }

        return $inForm;
    }

    /**
     * URLを生成します
     *
     * @param $getParam GETパラメータ
     *
     */
    public function createUrl($getParam) {

        $url = '';

        foreach ($getParam as $gP) {
            $url .= '/' . $gP;
        }

        return $url;
    }
}