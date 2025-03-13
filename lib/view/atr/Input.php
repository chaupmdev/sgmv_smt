<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('atr/Common');
Sgmov_Lib::useForms(array('Error', 'Atr002Out'));
/**#@-*/

/**
 * ツアーマスタ入力画面を表示します。
 * @package    View
 * @subpackage ATR
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Atr_Input extends Sgmov_View_Atr_Common {

    /**
     * ログインサービス
     * @var Sgmov_Service_Login
     */
    public $_loginService;

    /**
     * ツアー会社サービス
     * @var Sgmov_Service_TravelAgency
     */
    private $_TravelAgencyService;

    /**
     * ツアーサービス
     * @var Sgmov_Service_Travel
     */
    private $_TravelService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_loginService        = new Sgmov_Service_Login();
        $this->_TravelAgencyService = new Sgmov_Service_TravelAgency();
        $this->_TravelService       = new Sgmov_Service_Travel();
    }

    /**
     * 処理を実行します。
     *
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['ticket']:チケット文字列
     * </li><li>
     * ['outForm']:出力フォーム
     * </li><li>
     * ['errorForm']:エラーフォーム
     * </li></ul>
     */
    public function executeInner() {
        if (isset($_POST['id'])) {
            return $this->_executeInnerUpdate($_POST);
        } else {
            return $this->_executeInnerReload($_POST);
        }
    }

    /**
     * 新規・変更ボタン押下の場合の処理を実行します。
     *
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['ticket']:チケット文字列
     * </li><li>
     * ['outForm']:出力フォーム
     * </li><li>
     * ['errorForm']:エラーフォーム
     * </li></ul>
     */
    public function _executeInnerUpdate($post) {
        Sgmov_Component_Log::debug('新規・変更ボタン押下の場合の処理を実行します。');

        // セッション削除
        $session = Sgmov_Component_Session::get();
        $session->deleteForm($this->getFeatureId());

        // 出力情報を作成
        $outForm = $this->_createOutFormByUpdate($post);
        $errorForm = new Sgmov_Form_Error();

        // チケット発行
        $ticket = $session->publishTicket($this->getFeatureId(), self::GAMEN_ID_ATR002);

        return array(
            'ticket'    => $ticket,
            'outForm'   => $outForm,
            'errorForm' => $errorForm,
        );
    }

    /**
     * 新規・変更ボタン押下ではない場合の処理を実行します。
     *
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['ticket']:チケット文字列
     * </li><li>
     * ['outForm']:出力フォーム
     * </li><li>
     * ['errorForm']:エラーフォーム
     * </li></ul>
     */
    public function _executeInnerReload($post) {
        Sgmov_Component_Log::debug('新規・変更ボタン押下ではない場合の処理を実行します。');

        // セッション
        $session = Sgmov_Component_Session::get();
        $sessionForm = $session->loadForm(self::FEATURE_ID);

        // 出力情報を作成
        $outForm = $this->_createOutFormByReload($sessionForm);

        // TODO オブジェクトから値を直接取得できるよう修正する
        // オブジェクトから取得できないため、配列に型変換
        $sessionForm = (array)$sessionForm;
        $errorForm = $sessionForm['error'];

        // チケット発行
        $ticket = $session->publishTicket($this->getFeatureId(), self::GAMEN_ID_ATR002);

        return array(
            'ticket'    => $ticket,
            'outForm'   => $outForm,
            'errorForm' => $errorForm,
        );
    }

    /**
     * 入力フォームの値を出力フォームを生成します。
     * @return Sgmov_Form_Atr002Out 出力フォーム
     */
    private function _createOutFormByUpdate($post) {

        $outForm = new Sgmov_Form_Atr002Out();

        $outForm->raw_honsha_user_flag = $this->_loginService->getHonshaUserFlag();

        $db = Sgmov_Component_DB::getPublic();
        $travelAgency = $this->_TravelAgencyService->fetchTravelAgencies($db);

        // 船名
        $outForm->raw_travel_agency_cds  = $travelAgency['ids'];
        $outForm->raw_travel_agency_lbls = $travelAgency['names'];

        if (empty($post['id'])) {
            return $outForm;
        }

        $travel = $this->_TravelService->fetchTravelLimit($db, array('id' => $post['id']));
        
        if (empty($travel)) {
            return $outForm;
        }

        $outForm->raw_travel_id            = $travel['id'];
        $outForm->raw_travel_cd            = $travel['cd'];
        $outForm->raw_travel_name          = $travel['name'];
        $outForm->raw_travel_agency_cd_sel = $travel['travel_agency_id'];
        $outForm->raw_round_trip_discount  = $travel['round_trip_discount'];
        $outForm->raw_repeater_discount    = $travel['repeater_discount'];
        $outForm->raw_embarkation_date     = $travel['embarkation_date'];
        $outForm->raw_publish_begin_date   = $travel['publish_begin_date'];

        return $outForm;
    }

    /**
     * 入力フォームの値を出力フォームを生成します。
     * @return Sgmov_Form_Atr002Out 出力フォーム
     */
    private function _createOutFormByReload($inForm) {

        $outForm = new Sgmov_Form_Atr002Out();

        $outForm->raw_honsha_user_flag = $this->_loginService->getHonshaUserFlag();

        $db = Sgmov_Component_DB::getPublic();
        $travelAgency = $this->_TravelAgencyService->fetchTravelAgencies($db);

        // 船名
        $outForm->raw_travel_agency_cds  = $travelAgency['ids'];
        $outForm->raw_travel_agency_lbls = $travelAgency['names'];

        // TODO オブジェクトから値を直接取得できるよう修正する
        // オブジェクトから取得できないため、配列に型変換
        $inForm = (array)$inForm;

        // 船名
        $outForm->raw_travel_agency_cds  = $travelAgency['ids'];
        $outForm->raw_travel_agency_lbls = $travelAgency['names'];

        $outForm->raw_travel_id            = $inForm['travel_id'];
        $outForm->raw_travel_cd            = $inForm['travel_cd'];
        $outForm->raw_travel_name          = $inForm['travel_name'];
        $outForm->raw_travel_agency_cd_sel = $inForm['travel_agency_cd_sel'];
        $outForm->raw_round_trip_discount  = $inForm['round_trip_discount'];
        $outForm->raw_repeater_discount    = $inForm['repeater_discount'];
        $outForm->raw_embarkation_date     = $inForm['embarkation_date'];
        $outForm->raw_publish_begin_date   = $inForm['publish_begin_date'];

        return $outForm;
    }
}