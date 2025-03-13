<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TCP)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useAllComponents();
Sgmov_Lib::useView('Public');
Sgmov_Lib::useServices(array('Apartment'));
/**#@-*/

/**
 * マンションから住所を検索して返します。
 * @package    View
 * @subpackage TRA
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Apr_SearchApartment extends Sgmov_View_Public {

    /**
     * マンションサービス
     * @var Sgmov_Service_Apartment
     */
    public $_ApartmentService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_ApartmentService = new Sgmov_Service_Apartment();
    }

    /**
     * 処理を実行します。
     *
     */
    public function executeInner() {

        $featureId        = filter_input(INPUT_POST, 'featureId');
        $fromGamenId      = filter_input(INPUT_POST, 'id');
        $ticket           = filter_input(INPUT_POST, 'ticket');
        $apartment_cd_sel = filter_input(INPUT_POST, 'apartment_cd_sel');

        // チケット確認
        $this->_checkSession($featureId, $fromGamenId, $ticket);

        try {
            if (empty($apartment_cd_sel)) {
                throw new Exception;
            }
            // DB接続
            $db = Sgmov_Component_DB::getPublic();
            $apartment = $this->_ApartmentService->fetchAddress($db, array('id' => $apartment_cd_sel));
        }
        catch (exception $e) {
            $apartment = null;
        }
        return $apartment;
    }

    /**
     * チケットの確認を行います。
     * TODO ybn/SearchAddressと同記述あり
     */
    public function _checkSession($featureId, $fromGamenId, $ticket) {
        // セッション
        $session = Sgmov_Component_Session::get();

        // チケットの確認
        if (!isset($_SESSION[Sgmov_Component_Session::_KEY_TICKETS])) {
            Sgmov_Component_Log::warning('【マンション住所検索 不正使用】チケットが存在していません。');
            header('HTTP/1.0 404 Not Found');
            exit;
        }

        $tickets = &$_SESSION[Sgmov_Component_Session::_KEY_TICKETS];
        if (!isset($tickets[$featureId]) || $tickets[$featureId] !== $fromGamenId . $ticket) {
            Sgmov_Component_Log::warning('【マンション住所検索 不正使用】チケットが不正です。　'.$tickets[$featureId].' <=> '.$fromGamenId . $ticket);
            header('HTTP/1.0 404 Not Found');
            exit;
        } else {
            Sgmov_Component_Log::debug('マンション住所検索実行 機能ID=>'.$tickets[$featureId].' <=> '.$fromGamenId . $ticket);
        }
    }
}