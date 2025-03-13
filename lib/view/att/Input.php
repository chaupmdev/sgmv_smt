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
Sgmov_Lib::useView('att/Common');
Sgmov_Lib::useForms(array('Error', 'Att002Out'));
/**#@-*/

/**
 * ツアー発着地マスタ入力画面を表示します。
 * @package    View
 * @subpackage ATT
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Att_Input extends Sgmov_View_Att_Common {

    /**
     * ログインサービス
     * @var Sgmov_Service_Login
     */
    public $_loginService;

    /**
     * 都道府県サービス
     * @var Sgmov_Service_Prefecture
     */
    private $_PrefectureService;

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
     * ツアー発着地サービス
     * @var Sgmov_Service_TravelTerminal
     */
    private $_TravelTerminalService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_loginService          = new Sgmov_Service_Login();
        $this->_PrefectureService     = new Sgmov_Service_Prefecture();
        $this->_TravelAgencyService   = new Sgmov_Service_TravelAgency();
        $this->_TravelService         = new Sgmov_Service_Travel();
        $this->_TravelTerminalService = new Sgmov_Service_TravelTerminal();
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
        $ticket = $session->publishTicket($this->getFeatureId(), self::GAMEN_ID_ATT002);

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
Sgmov_Component_Log::debug($session);Sgmov_Component_Log::debug($sessionForm);
        // 出力情報を作成
        $outForm = $this->_createOutFormByReload($sessionForm);

        // TODO オブジェクトから値を直接取得できるよう修正する
        // オブジェクトから取得できないため、配列に型変換
        $sessionForm = (array)$sessionForm;
        $errorForm = $sessionForm['error'];

        // チケット発行
        $ticket = $session->publishTicket($this->getFeatureId(), self::GAMEN_ID_ATT002);

        return array(
            'ticket'    => $ticket,
            'outForm'   => $outForm,
            'errorForm' => $errorForm,
        );
    }

    /**
     * 入力フォームの値を出力フォームを生成します。
     * @return Sgmov_Form_Att002Out 出力フォーム
     */
    private function _createOutFormByUpdate($post) {

        $outForm = new Sgmov_Form_Att002Out();

        $outForm->raw_honsha_user_flag = $this->_loginService->getHonshaUserFlag();

        $db = Sgmov_Component_DB::getPublic();
        $travelAgency = $this->_TravelAgencyService->fetchTravelAgencies($db);
        $prefectures  = $this->_PrefectureService->fetchPrefectures($db);

        // 船名
        $outForm->raw_travel_agency_cds  = $travelAgency['ids'];
        $outForm->raw_travel_agency_lbls = $travelAgency['names'];

        // 都道府県
        array_shift($prefectures['ids']);
        array_shift($prefectures['names']);
        $outForm->raw_pref_cds  = $prefectures['ids'];
        $outForm->raw_pref_lbls = $prefectures['names'];

        if (empty($post['id'])) {
            return $outForm;
        }

        $travelTerminal = $this->_TravelTerminalService->fetchTravelTerminalLimit($db, array('id' => $post['id']));
        
        if (empty($travelTerminal)) {
            return $outForm;
        }

        $travel = $this->_TravelService->fetchTravels($db, array('travel_agency_id' => $travelTerminal['travel_agency_id']));

        // 乗船日名
        $outForm->raw_travel_cds  = $travel['ids'];
        $outForm->raw_travel_lbls = $travel['names'];

        $outForm->raw_travel_terminal_id         = $travelTerminal['id'];
        $outForm->raw_travel_agency_cd_sel       = $travelTerminal['travel_agency_id'];
        $outForm->raw_travel_cd_sel              = $travelTerminal['travel_id'];
        $outForm->raw_travel_terminal_cd         = $travelTerminal['cd'];
        $outForm->raw_travel_terminal_name       = $travelTerminal['name'];
        $outForm->raw_zip1                       = $travelTerminal['zip1'];
        $outForm->raw_zip2                       = $travelTerminal['zip2'];
        $outForm->raw_pref_cd_sel                = $travelTerminal['pref_id'];
        $outForm->raw_address                    = $travelTerminal['address'];
        $outForm->raw_building                   = $travelTerminal['building'];
        $outForm->raw_store_name                 = $travelTerminal['store_name'];
        $outForm->raw_tel                        = $travelTerminal['tel'];
        $outForm->raw_terminal_cd                = $travelTerminal['terminal_cd'];
        $outForm->raw_departure_date             = $travelTerminal['departure_date'];
        $outForm->raw_departure_time             = $travelTerminal['departure_time'];
        $outForm->raw_arrival_date               = $travelTerminal['arrival_date'];
        $outForm->raw_arrival_time               = $travelTerminal['arrival_time'];
        $outForm->raw_departure_client_cd        = $travelTerminal['departure_client_cd'];
        $outForm->raw_departure_client_branch_cd = $travelTerminal['departure_client_branch_cd'];
        $outForm->raw_arrival_client_cd          = $travelTerminal['arrival_client_cd'];
        $outForm->raw_arrival_client_branch_cd   = $travelTerminal['arrival_client_branch_cd'];

        return $outForm;
    }

    /**
     * 入力フォームの値を出力フォームを生成します。
     * @return Sgmov_Form_Att002Out 出力フォーム
     */
    private function _createOutFormByReload($inForm) {

        $outForm = new Sgmov_Form_Att002Out();

        $outForm->raw_honsha_user_flag = $this->_loginService->getHonshaUserFlag();

        $db = Sgmov_Component_DB::getPublic();
        $travelAgency = $this->_TravelAgencyService->fetchTravelAgencies($db);
        $prefectures  = $this->_PrefectureService->fetchPrefectures($db);

        // 船名
        $outForm->raw_travel_agency_cds  = $travelAgency['ids'];
        $outForm->raw_travel_agency_lbls = $travelAgency['names'];

        // 都道府県
        array_shift($prefectures['ids']);
        array_shift($prefectures['names']);
        $outForm->raw_pref_cds  = $prefectures['ids'];
        $outForm->raw_pref_lbls = $prefectures['names'];

        // TODO オブジェクトから値を直接取得できるよう修正する
        // オブジェクトから取得できないため、配列に型変換
        $inForm = (array)$inForm;

        if (!empty($inForm['travel_agency_cd_sel'])) {
            $travel = $this->_TravelService->fetchTravels($db, array('travel_agency_id' => $inForm['travel_agency_cd_sel']));
        } else {
            $travel = array(
                'ids'   => array(),
                'names' => array(),
            );
        }

        // 乗船日名
        $outForm->raw_travel_cds  = $travel['ids'];
        $outForm->raw_travel_lbls = $travel['names'];

        $outForm->raw_travel_terminal_id         = $inForm['travel_terminal_id'];
        $outForm->raw_travel_agency_cd_sel       = $inForm['travel_agency_cd_sel'];
        $outForm->raw_travel_cd_sel              = $inForm['travel_cd_sel'];
        $outForm->raw_travel_terminal_cd         = $inForm['travel_terminal_cd'];
        $outForm->raw_travel_terminal_name       = $inForm['travel_terminal_name'];
        $outForm->raw_zip1                       = $inForm['zip1'];
        $outForm->raw_zip2                       = $inForm['zip2'];
        $outForm->raw_pref_cd_sel                = $inForm['pref_cd_sel'];
        $outForm->raw_address                    = $inForm['address'];
        $outForm->raw_building                   = $inForm['building'];
        $outForm->raw_store_name                 = $inForm['store_name'];
        $outForm->raw_tel                        = $inForm['tel'];
        $outForm->raw_terminal_cd                = $inForm['terminal_cd'];
        $outForm->raw_departure_date             = $inForm['departure_date'];
        $outForm->raw_departure_time             = $inForm['departure_time'];
        $outForm->raw_arrival_date               = $inForm['arrival_date'];
        $outForm->raw_arrival_time               = $inForm['arrival_time'];
        $outForm->raw_departure_client_cd        = $inForm['departure_client_cd'];
        $outForm->raw_departure_client_branch_cd = $inForm['departure_client_branch_cd'];
        $outForm->raw_arrival_client_cd          = $inForm['arrival_client_cd'];
        $outForm->raw_arrival_client_branch_cd   = $inForm['arrival_client_branch_cd'];

        return $outForm;
    }
}