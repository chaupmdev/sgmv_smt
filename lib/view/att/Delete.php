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
Sgmov_Lib::useForms(array('Error', 'Att012Out'));
/**#@-*/

/**
 * ツアー発着地マスタ削除確認画面を表示します。
 * @package    View
 * @subpackage ATT
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Att_Delete extends Sgmov_View_Att_Common {

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

        // セッション削除
        $session = Sgmov_Component_Session::get();
        $session->deleteForm($this->getFeatureId());

        // 出力情報を作成
        $outForm = $this->_createOutForm($_POST);
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
     * 入力フォームの値を出力フォームを生成します。
     * @return Sgmov_Form_Att012Out 出力フォーム
     */
    private function _createOutForm($post) {

        $outForm = new Sgmov_Form_Att012Out();

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

        $temporaryTravel = $this->_TravelService->fetchTravelLimit($db, array('id' => $travelTerminal['travel_id']));
        $travel = $this->_TravelService->fetchTravels($db, array('travel_agency_id' => $temporaryTravel['travel_agency_id']));

        // 乗船日名
        $outForm->raw_travel_cds  = $travel['ids'];
        $outForm->raw_travel_lbls = $travel['names'];

        $outForm->raw_travel_terminal_id         = $travelTerminal['id'];
        $outForm->raw_travel_agency_name         = $travelTerminal['travel_agency_name'];
        $outForm->raw_travel_name                = $travelTerminal['travel_name'];
        $outForm->raw_travel_terminal_cd         = $travelTerminal['cd'];
        $outForm->raw_travel_terminal_name       = $travelTerminal['name'];
        $outForm->raw_zip1                       = $travelTerminal['zip1'];
        $outForm->raw_zip2                       = $travelTerminal['zip2'];
        $outForm->raw_pref_name                  = $travelTerminal['pref_name'];
        $outForm->raw_address                    = $travelTerminal['address'];
        $outForm->raw_building                   = $travelTerminal['building'];
        $outForm->raw_store_name                 = $travelTerminal['store_name'];
        $outForm->raw_tel                        = $travelTerminal['tel'];
        $outForm->raw_terminal_cd                = $travelTerminal['terminal_cd'];
        $outForm->raw_departure_date             = $travelTerminal['departure_date_japanese'];
        $outForm->raw_departure_time             = $travelTerminal['departure_time_japanese'];
        $outForm->raw_arrival_date               = $travelTerminal['arrival_date_japanese'];
        $outForm->raw_arrival_time               = $travelTerminal['arrival_time_japanese'];
        $outForm->raw_departure_client_cd        = $travelTerminal['departure_client_cd'];
        $outForm->raw_departure_client_branch_cd = $travelTerminal['departure_client_branch_cd'];
        $outForm->raw_arrival_client_cd          = $travelTerminal['arrival_client_cd'];
        $outForm->raw_arrival_client_branch_cd   = $travelTerminal['arrival_client_branch_cd'];

        return $outForm;
    }
}