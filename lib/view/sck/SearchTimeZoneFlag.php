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
Sgmov_Lib::useServices('SocketZipCodeDll');
/**#@-*/
Sgmov_Component_Log::debug(__LINE__);
/**
 * 郵便番号から郵便番号DLLをソケット通信で検索し、時間帯指定不可地区を返します。
 * @package    View
 * @subpackage TDC
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Sck_SearchTimeZoneFlag extends Sgmov_View_Public {

    /**
     * 郵便番号DLLサービス
     * @var Sgmov_Service_SocketZipCodeDll
     */
    public $_SocketZipCodeDll;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_SocketZipCodeDll = new Sgmov_Service_SocketZipCodeDll();
    }

    /**
     * 処理を実行します。
     *
     */
    public function executeInner() {

        $featureId     = filter_input(INPUT_POST, 'featureId');
        $fromGamenId   = filter_input(INPUT_POST, 'id');
        $ticket        = filter_input(INPUT_POST, 'ticket');

        $zip  = '';
        $zip .= filter_input(INPUT_POST, 'zip1');
        $zip .= filter_input(INPUT_POST, 'zip2');

        // チケット確認
        $this->_checkSession($featureId, $fromGamenId, $ticket);

        try {
            if (empty($zip) || strlen($zip) !== 7) {
                throw new Exception;
            }
            $receive = $this->_SocketZipCodeDll->searchByZipCode($zip);
            $timeZoneFlag = !empty($receive['TimeZoneFlag']);
        }
        catch (exception $e) {
            $timeZoneFlag = false;
        }
        return $timeZoneFlag;
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
            Sgmov_Component_Log::warning('【時間帯指定不可地区検索 不正使用】チケットが存在していません。');
            header('HTTP/1.0 404 Not Found');
            exit;
        }

        $tickets = &$_SESSION[Sgmov_Component_Session::_KEY_TICKETS];
        if (!isset($tickets[$featureId]) || $tickets[$featureId] !== $fromGamenId . $ticket) {
            Sgmov_Component_Log::warning('【時間帯指定不可地区検索 不正使用】チケットが不正です。　'.$tickets[$featureId].' <=> '.$fromGamenId . $ticket);
            header('HTTP/1.0 404 Not Found');
            exit;
        } else {
            Sgmov_Component_Log::debug('時間帯指定不可地区検索実行 機能ID=>'.$tickets[$featureId].' <=> '.$fromGamenId . $ticket);
        }
    }
}