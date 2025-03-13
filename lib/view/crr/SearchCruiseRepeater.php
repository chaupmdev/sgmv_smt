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
Sgmov_Lib::useServices(array('CruiseRepeater'));
/**#@-*/

/**
 * ツアー会社からツアーを検索して返します。
 * @package    View
 * @subpackage TRA
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Tra_SearchCruiseRepeater extends Sgmov_View_Public {

    /**
     * クルーズリピータサービス
     * @var Sgmov_Service_CruiseRepeater
     */
    public $_CruiseRepeater;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_CruiseRepeater = new Sgmov_Service_CruiseRepeater();
    }

    /**
     * 処理を実行します。
     *
     */
    public function executeInner() {

        $featureId   = filter_input(INPUT_POST, 'featureId');
        $fromGamenId = filter_input(INPUT_POST, 'id');
        $ticket      = filter_input(INPUT_POST, 'ticket');
        $tel1        = filter_input(INPUT_POST, 'search_tel1');
        $tel2        = filter_input(INPUT_POST, 'search_tel2');
        $tel3        = filter_input(INPUT_POST, 'search_tel3');
        $zip1        = filter_input(INPUT_POST, 'search_zip1');
        $zip2        = filter_input(INPUT_POST, 'search_zip2');

        // チケット確認
        $this->_checkSession($featureId, $fromGamenId, $ticket);

        try {
            if (strlen($tel1) === 0
                || strlen($tel2) === 0
                || strlen($tel3) === 0
                || strlen($zip1) === 0
                || strlen($zip2) === 0
            ) {
                throw new Exception;
            }
            // DB接続
            $db = Sgmov_Component_DB::getPublic();
            $cruiseRepeater = $this->_CruiseRepeater->fetchCruiseRepeaterLimit($db, array(
                'tel' => $tel1 . $tel2 . $tel3,
                'zip' => $zip1 . $zip2,
            ));
            $exist = !empty($cruiseRepeater['tels']);
        }
        catch (exception $e) {
            $exist = false;
        }
        return $exist;
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
            Sgmov_Component_Log::warning('【クルーズリピーター検索 不正使用】チケットが存在していません。');
            header('HTTP/1.0 404 Not Found');
            exit;
        }

        $tickets = &$_SESSION[Sgmov_Component_Session::_KEY_TICKETS];
        if (!isset($tickets[$featureId]) || $tickets[$featureId] !== $fromGamenId . $ticket) {
            Sgmov_Component_Log::warning('【クルーズリピーター検索 不正使用】チケットが不正です。　'.$tickets[$featureId].' <=> '.$fromGamenId . $ticket);
            header('HTTP/1.0 404 Not Found');
            exit;
        } else {
            Sgmov_Component_Log::debug('クルーズリピーター検索実行 機能ID=>'.$tickets[$featureId].' <=> '.$fromGamenId . $ticket);
        }
    }
}