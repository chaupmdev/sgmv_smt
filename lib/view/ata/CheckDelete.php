<?php
/**
 * @package    ClassDefFile
 * @author     T.Aono(SGS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('ata/Common');
Sgmov_Lib::useForms(array('Error', 'AtaSession', 'Ata002In'));
/**#@-*/

/**
 * ツアー会社入力情報をチェックします。
 * @package    View
 * @subpackage ATA
 * @author     T.Aono(SGS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Ata_CheckDelete extends Sgmov_View_Ata_Common {

    /**
     * ツアー会社サービス
     * @var Sgmov_Service_TravelAgency
     */
    private $_TravelAgencyService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_TravelAgencyService = new Sgmov_Service_TravelAgency();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * チケットの確認と破棄
     * </li><li>
     * 情報をセッションに保存
     * </li><li>
     * 入力チェック
     * </li><li>
     * 入力エラー無し
     *   <ol><li>
     *   ata/confirm へリダイレクト
     *   </li></ol>
     * </li><li>
     * 入力エラー有り
     *   <ol><li>
     *   ata/delete へリダイレクト
     *   </li></ol>
     * </li></ol>
     */
    public function executeInner() {
        Sgmov_Component_Log::debug('チケットの確認と破棄');
        $session = Sgmov_Component_Session::get();
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_ATA012, $this->_getTicket());

        Sgmov_Component_Log::debug('情報を取得');
        $inForm = $this->_createInFormFromPost();
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        if (empty($sessionForm)) {
            $sessionForm = new Sgmov_Form_Ata002In();
        }
        $sessionForm->travel_agency_id = $inForm->travel_agency_id;

        Sgmov_Component_Log::debug('入力チェック');
        $errorForm = $this->_deleteTravelAgency($sessionForm);

        Sgmov_Component_Log::debug('セッション保存');
        $sessionForm->error = $errorForm;
        if ($errorForm->hasError()) {
            $sessionForm->status = self::VALIDATION_FAILED;
        } else {
            $sessionForm->status = self::VALIDATION_SUCCEEDED;
        }

        // リダイレクト
        if ($errorForm->hasError()) {
            $session->saveForm(self::FEATURE_ID, $sessionForm);
            Sgmov_Component_Log::debug('リダイレクト /ata/delete/');
            Sgmov_Component_Redirect::redirectMaintenance('/ata/delete/');
        } else {
            // TODO 確認画面と完了画面を作る
            $session->deleteForm($this->getFeatureId());
            Sgmov_Component_Log::debug('リダイレクト /ata/list/');
            Sgmov_Component_Redirect::redirectMaintenance('/ata/list/');
        }
    }

    /**
     * POST値からチケットを取得します。
     * チケットが存在しない場合は空文字列を返します。
     * @return string チケット文字列
     */
    public function _getTicket() {
        if (!isset($_POST['ticket'])) {
            $ticket = '';
        } else {
            $ticket = $_POST['ticket'];
        }
        return $ticket;
    }

    /**
     * POST情報から入力フォームを生成します。
     * @return Sgmov_Form_Ata002In 入力フォーム
     */
    public function _createInFormFromPost() {
        $inForm = new Sgmov_Form_Ata002In();

        $inForm->travel_agency_id = filter_input(INPUT_POST, 'travel_agency_id');

        return $inForm;
    }

    /**
     * セッション情報を元にツアー会社情報を削除します。
     *
     * 同時更新によって更新に失敗した場合はエラーフォームにメッセージを設定して返します。
     *
     * @param Sgmov_Form_AocSession $sessionForm セッションフォーム
     * @return Sgmov_Form_Error エラーフォーム
     */
    public function _deleteTravelAgency($sessionForm) {
        // DBへ接続
        $db = Sgmov_Component_DB::getAdmin();

        // 情報をDBへ格納
        if (!empty($sessionForm->travel_agency_id)) {
            $data = array(
                'id' => $sessionForm->travel_agency_id,
            );
            $ret = $this->_TravelAgencyService->_deleteTravelAgency($db, $data);
        }

        $errorForm = new Sgmov_Form_Error();
        if (isset($ret) && $ret === false) {
            $errorForm->addError('top_conflict', '別のユーザーによって更新されています。すべての変更は適用されませんでした。');
        }
        return $errorForm;
    }
}