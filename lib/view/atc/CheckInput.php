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
Sgmov_Lib::useView('atc/Common');
Sgmov_Lib::useForms(array('Error', 'AtcSession', 'Atc002In'));
/**#@-*/

/**
 * ツアー配送料金入力情報をチェックします。
 * @package    View
 * @subpackage ATC
 * @author     T.Aono(SGS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Atc_CheckInput extends Sgmov_View_Atc_Common {

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
     * ツアーエリアサービス
     * @var Sgmov_Service_TravelProvinces
     */
    private $_TravelProvincesService;

    /**
     * ツアー配送料金サービス
     * @var Sgmov_Service_TravelDeliveryCharge
     */
    private $_TravelDeliveryChargeService;

    /**
     * ツアー配送料金エリアサービス
     * @var Sgmov_Service_TravelDeliveryChargeAreas
     */
    private $_TravelDeliveryChargeAreasService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_TravelAgencyService              = new Sgmov_Service_TravelAgency();
        $this->_TravelService                    = new Sgmov_Service_Travel();
        $this->_TravelTerminalService            = new Sgmov_Service_TravelTerminal();
        $this->_TravelProvincesService           = new Sgmov_Service_TravelProvinces;
        $this->_TravelDeliveryChargeService      = new Sgmov_Service_TravelDeliveryCharge;
        $this->_TravelDeliveryChargeAreasService = new Sgmov_Service_TravelDeliveryChargeAreas;
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
     *   atc/confirm へリダイレクト
     *   </li></ol>
     * </li><li>
     * 入力エラー有り
     *   <ol><li>
     *   atc/input へリダイレクト
     *   </li></ol>
     * </li></ol>
     */
    public function executeInner() {
        Sgmov_Component_Log::debug('チケットの確認と破棄');
        $session = Sgmov_Component_Session::get();
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_ATC002, $this->_getTicket());

        Sgmov_Component_Log::debug('情報を取得');
        $inForm = $this->_createInFormFromPost($_POST);
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        if (empty($sessionForm)) {
            $sessionForm = new Sgmov_Form_Atc002In();
        }
        $sessionForm->travel_delivery_charge_id = $inForm->travel_delivery_charge_id;
        $sessionForm->travel_agency_cd_sel      = $inForm->travel_agency_cd_sel;
        $sessionForm->travel_cd_sel             = $inForm->travel_cd_sel;
        $sessionForm->travel_terminal_cd_sel    = $inForm->travel_terminal_cd_sel;
        $sessionForm->delivery_charg            = $inForm->delivery_charg;
	
        Sgmov_Component_Log::debug('入力チェック');
        $errorForm = $this->_validate($sessionForm);
        if (!$errorForm->hasError()) {
            $errorForm = $this->_updateTravelDeliveryCharge($sessionForm);
        }

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
            Sgmov_Component_Log::debug('リダイレクト /atc/input/');
            Sgmov_Component_Redirect::redirectMaintenance('/atc/input/');
        } else {
            // TODO 確認画面と完了画面を作る
            $session->deleteForm($this->getFeatureId());
            Sgmov_Component_Log::debug('リダイレクト /atc/list/');
            Sgmov_Component_Redirect::redirectMaintenance('/atc/list/');
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
     * @param array $post ポスト情報
     * @return Sgmov_Form_Atc002In 入力フォーム
     */
    public function _createInFormFromPost($post) {
        $inForm = new Sgmov_Form_Atc002In();

        $inForm->travel_delivery_charge_id = filter_input(INPUT_POST, 'travel_delivery_charge_id');
        $inForm->travel_agency_cd_sel      = filter_input(INPUT_POST, 'travel_agency_cd_sel');
        $inForm->travel_cd_sel             = filter_input(INPUT_POST, 'travel_cd_sel');
        $inForm->travel_terminal_cd_sel    = filter_input(INPUT_POST, 'travel_terminal_cd_sel');
        $inForm->delivery_charg            = filter_input(INPUT_POST, 'delivery_charg', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

        return $inForm;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_AtcSession $sessionForm セッションフォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($sessionForm) {

        // DB接続
        $db = Sgmov_Component_DB::getPublic();
        $travelAgency = $this->_TravelAgencyService->fetchTravelAgencies($db);

        if (!empty($sessionForm->travel_agency_cd_sel)) {
            $travel = $this->_TravelService->fetchTravels($db, array('travel_agency_id' => $sessionForm->travel_agency_cd_sel));
        } else {
            $travel = array(
                'ids' => array(),
            );
        }

        if (!empty($sessionForm->travel_cd_sel)) {
            $travelTerminal = $this->_TravelTerminalService->fetchTravelTerminals($db, array('travel_id' => $sessionForm->travel_cd_sel));
        } else {
            $travelTerminal = array(
                'ids' => array(),
            );
        }

        // 入力チェック
        $errorForm = new Sgmov_Form_Error();
	
	try {
		// 船名
		$v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->travel_agency_cd_sel)->
			isSelected()->
			isIn((array)$travelAgency['ids']);
		if (!$v->isValid()) {
		    $errorForm->addError('top_travel_agency_cd_sel', $v->getResultMessageTop());
		    $errorForm->addError('travel_agency_cd_sel', $v->getResultMessage());
		}

		// 乗船日名
		$v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->travel_cd_sel)->
			isSelected()->
			isIn((array)$travel['ids']);
		if (!$v->isValid()) {
		    $errorForm->addError('top_travel_cd_sel', $v->getResultMessageTop());
		    $errorForm->addError('travel_cd_sel', $v->getResultMessage());
		}

		// ツアー発着地
		$v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->travel_terminal_cd_sel)->
			isSelected()->
			isIn((array)$travelTerminal['ids']);
		if (!$v->isValid()) {
		    $errorForm->addError('top_travel_terminal_cd_sel', $v->getResultMessageTop());
		    $errorForm->addError('travel_terminal_cd_sel', $v->getResultMessage());
		}

		// ツアー配送料金
		$v = Sgmov_Component_Validator::createMultipleValueValidator($sessionForm->delivery_charg)->
			//isNotEmpty()->
			isInteger(0)->
			isLengthLessThanOrEqualTo(6)->
			isWebSystemNg();
		if (!$v->isValid()) {
		    $errorForm->addError('top_delivery_charg', $v->getResultMessageTop());
		    $errorForm->addError('delivery_charg', $v->getResultMessage());
		}
	} catch (Sgmov_Component_Exception $sce) {
		$errorForm->addError('top_delivery_charg', $sce->getMessage());
	}

        return $errorForm;
    }

    /**
     * セッション情報を元にツアー発着地情報を更新します。
     *
     * 同時更新によって更新に失敗した場合はエラーフォームにメッセージを設定して返します。
     *
     * @param Sgmov_Form_AocSession $sessionForm セッションフォーム
     * @return Sgmov_Form_Error エラーフォーム
     */
    public function _updateTravelDeliveryCharge($sessionForm) {

        // DBへ接続
        $db = Sgmov_Component_DB::getAdmin();

        // 情報をDBへ格納
        if (!empty($sessionForm->travel_delivery_charge_id)) {
            $data = array(
                'id'                        => $sessionForm->travel_delivery_charge_id,
                'travel_delivery_charge_id' => $sessionForm->travel_delivery_charge_id,
                'travel_terminal_id'        => $sessionForm->travel_terminal_cd_sel,
            );
        } else {
            //登録用IDを取得
            $id = $this->_TravelDeliveryChargeService->select_id($db);
            $data = array(
                'id'                        => $id,
                'travel_delivery_charge_id' => $id,
                'travel_terminal_id'        => $sessionForm->travel_terminal_cd_sel,
            );
        }
        $travelDeliveryChargeAreas = $this->_TravelDeliveryChargeAreasService->fetchDeliveryChargeAddProvinces($db,
                array('travel_terminal_id' => $sessionForm->travel_terminal_cd_sel));

        if (!empty($travelDeliveryChargeAreas) && !empty($travelDeliveryChargeAreas['ids'])) {
            $ret = $this->_TravelDeliveryChargeService->_updateTravelDeliveryCharge($db, $data);
        } else {
            $ret = $this->_TravelDeliveryChargeService->_insertTravelDeliveryCharge($db, $data);
        }
        foreach ($sessionForm->delivery_charg as $travel_provinces_id => $delivery_charg) {
            $data = array(
                'travel_areas_provinces_id' => $travel_provinces_id,
                'delivery_charg'            => $delivery_charg,
                    ) + $data;
            if ($this->_TravelDeliveryChargeAreasService->countProvinces($db, $data) > 0) {
                $ret = $ret && $this->_TravelDeliveryChargeAreasService->_updateTravelDeliveryChargeAreas($db, $data);
            } else {
                $ret = $ret && $this->_TravelDeliveryChargeAreasService->_insertTravelDeliveryChargeAreas($db, $data);
            }
        }

        $errorForm = new Sgmov_Form_Error();
        if ($ret === false) {
            $errorForm->addError('top_conflict', '別のユーザーによって更新されています。すべての変更は適用されませんでした。');
        }
        return $errorForm;
    }
}