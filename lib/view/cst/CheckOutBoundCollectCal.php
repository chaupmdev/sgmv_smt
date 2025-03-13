<?php
/**
 * @package    ClassDefFile
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useAllComponents();
Sgmov_Lib::useView('Public');
Sgmov_Lib::useServices(array('OutBoundCollectCal', 'Eventsub', 'AppCommon'));
/**#@-*/

/**
 * 当日搬入申込期間チェック。
 * @package    View
 * @subpackage CST
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Cst_CheckOutBoundCal extends Sgmov_View_Public {

    private $baseUrl = '';
    /**
     * 往路・集荷日範囲計算サービス
     * @var Sgmov_Service_Travel
     */
    public $_OutBoundUnCollectCal;

    /**
     * イベントサブマスタサービス
     * @var Sgmov_Service_Eventsub
     */
    public $_Eventsub;
    
    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_OutBoundUnCollectCal= new Sgmov_Service_OutBoundCollectCal();
        $this->_Eventsub = new Sgmov_Service_Eventsub();
    }

    /**
     * 処理を実行します。
     *
     */
    public function executeInner() {
        return $this->executeInnerN();
    }
    
    public function executeInnerN() {
        
        $featureId            = filter_input(INPUT_POST, 'featureId');
        $fromGamenId          = filter_input(INPUT_POST, 'id');
        $ticket               = filter_input(INPUT_POST, 'ticket');
        $eventSubId           = filter_input(INPUT_POST, 'eventsub_sel');                                       // イベントサブID
        if (@empty($eventSubId)) {
            $eventSubId = filter_input(INPUT_POST, 'eventsub_sel_rd');
        }
        $hatsuJis2            = filter_input(INPUT_POST, 'comiket_detail_outbound_pref_cd_sel');                // 集荷元JIS5（集荷元都道府県の選択）
        
        // チケット確認
        $this->_checkSession($featureId, $fromGamenId, $ticket);
        try {
            
            $db = Sgmov_Component_DB::getPublic();
            
            $eveSubData = $this->_Eventsub->fetchEventsubByEventsubId($db, $eventSubId);
            if (empty($eveSubData)) {
                Sgmov_Component_Log::info("イベントサブマスタが取得できませんでした。eventSubId:" . $eventSubId);
                return array(
                    "errorMsg" => 'イベント情報が取得できませんでした。',
                    "result" => '0',
                );
            }
            $chakuJis2 = substr($eveSubData['jis5cd'], 0, 2);

            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //  搬入申込期間が終了しているかチェック
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            $outBoundCollectCalList = $this->_OutBoundUnCollectCal->fetchOutBoundCollectCalByEventsubId($db, $eventSubId);
            $outBoundCollectCalDataMax = array();
            $plusPeriodMax = -9999999999;

            foreach ($outBoundCollectCalList as $key => $val) {
                if ($plusPeriodMax < @(int)$val['plus_period']) {
                    $plusPeriodMax = @(int)$val['plus_period'];
                    $outBoundCollectCalDataMax = $val;
                }
            }

            $outBoundUnCollectCalInfo = $this->_OutBoundUnCollectCal->fetchOutBoundCollectCalByHaChaku($db, $eventSubId
                    , @$outBoundCollectCalDataMax['hatsu_jis2'], $chakuJis2);
            


            $dateChNow = (new DateTime());
            $dateChArrival = new DateTime($outBoundUnCollectCalInfo['arrival_date']);

            if ($dateChArrival->format('Y-m-d H:i:s') <= $dateChNow->format('Y-m-d H:i:s')) {
                // 往路リードタイムマスタから、搬入申込期間が終了していた場合
                return array(
                    "errorMsg" => '受付期間が終了しました。',
                    "result" => '0',
                );
            }
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        } catch (Exception $e) { 
            // 多分上記でExceptionは発生しないが念のため 
        }
        
        return array(
            "errorMsg" => '受付期間中です。',
            "result" => '1',
        );
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
            Sgmov_Component_Log::warning('【ツアー会社検索 不正使用】チケットが存在していません。');
            header('HTTP/1.0 404 Not Found');
            exit;
        }

        $tickets = &$_SESSION[Sgmov_Component_Session::_KEY_TICKETS];
        if (!isset($tickets[$featureId]) || $tickets[$featureId] !== $fromGamenId . $ticket) {
            Sgmov_Component_Log::warning('【ツアー会社検索 不正使用】チケットが不正です。　'.$tickets[$featureId].' <=> '.$fromGamenId . $ticket);
            header('HTTP/1.0 404 Not Found');
            exit;
        } else {
            Sgmov_Component_Log::debug('ツアー会社検索実行 機能ID=>'.$tickets[$featureId].' <=> '.$fromGamenId . $ticket);
        }
    }
}

