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
 * 顧客コードから顧客情報を検索して返します。
 * @package    View
 * @subpackage CST
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Cst_GetOutBoundCal extends Sgmov_View_Public {

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
//        $featureId            = filter_input(INPUT_POST, 'featureId');
//        $fromGamenId          = filter_input(INPUT_POST, 'id');
//        $ticket               = filter_input(INPUT_POST, 'ticket');
//        $eventSubId           = filter_input(INPUT_POST, 'eventsub_sel');                                       // イベントサブID
//        $hatsuJis2            = filter_input(INPUT_POST, 'comiket_detail_outbound_pref_cd_sel');                // 集荷元JIS5（集荷元都道府県の選択）
//        $azukariYear          = filter_input(INPUT_POST, 'comiket_detail_outbound_delivery_date_year_sel');     // 引渡し年
//        $azukariMonth         = filter_input(INPUT_POST, 'comiket_detail_outbound_delivery_date_month_sel');    // 引渡し月
//        $azukariDay           = filter_input(INPUT_POST, 'comiket_detail_outbound_delivery_date_day_sel');      // 引渡し日
//        $fromDt               = filter_input(INPUT_POST, 'hid_comiket-detail-outbound-collect-date-from_ori');  // 元開始日付
//        $toDt                 = filter_input(INPUT_POST, 'hid_comiket-detail-outbound-collect-date-to_ori');    // 元開始日付
        
        return $this->executeInnerN();

    }
    
    public function executeInnerN() {
        
        $featureId            = filter_input(INPUT_POST, 'featureId');
        $fromGamenId          = filter_input(INPUT_POST, 'id');
        $ticket               = filter_input(INPUT_POST, 'ticket');
        $eventSubId           = filter_input(INPUT_POST, 'eventsub_sel');                                       // イベントサブID
        $hatsuJis2            = filter_input(INPUT_POST, 'comiket_detail_outbound_pref_cd_sel');                // 集荷元JIS5（集荷元都道府県の選択）
        //画面の引渡し日時の選ぶ値からイベントサブ.out_bound_loading_toとサブ日数を設定してから、getOutBoundCollectCalの計算に反映する。
        $minusNum = 0;
        if (filter_input(INPUT_POST, 'hid_minus_number_date')) {
            $minusNum             = filter_input(INPUT_POST, 'hid_minus_number_date');
            Sgmov_Component_Log::info('hid_minus_number_date isset='.$minusNum);
        }
        Sgmov_Component_Log::info('hid_minus_number_date='.$minusNum);
        
//        $deliveryYear          = filter_input(INPUT_POST, 'comiket_detail_outbound_delivery_date_year_sel');     // 引渡し年
//        $deliveryMonth         = filter_input(INPUT_POST, 'comiket_detail_outbound_delivery_date_month_sel');    // 引渡し月
//        $deliveryDay           = filter_input(INPUT_POST, 'comiket_detail_outbound_delivery_date_day_sel');      // 引渡し日
//        $fromDt               = filter_input(INPUT_POST, 'hid_comiket-detail-outbound-collect-date-from_ori');  // 元開始日付
//        $toDt                 = filter_input(INPUT_POST, 'hid_comiket-detail-outbound-collect-date-to_ori');    // 元開始日付
        
        // チケット確認
        $this->_checkSession($featureId, $fromGamenId, $ticket);
        try {
            if ($featureId == 'UNA') {
                $deliveryDtSelect            = filter_input(INPUT_POST, 'hid_comiket_detail_outbound_delivery_date');
                Sgmov_Component_Log::info("call getOutBoundShukaDateForUna:eventSubId={$eventSubId},hatsuJis2={$hatsuJis2},deliveryDtSelect={$deliveryDtSelect}");
                return $this->getOutBoundShukaDateForUna($eventSubId, $hatsuJis2, $deliveryDtSelect);
            } else {
                return $this->getOutBoundCollectCalFromTo($eventSubId, $hatsuJis2, $minusNum); 
            }
        } catch (Exception $e) { 
            // 多分上記でExceptionは発生しないが念のため 
            Sgmov_Component_Log::err("GetOutBoundCollectCal executeInnerN Error");
        }
        
        return null;
        
//        // DB接続
//        $db = Sgmov_Component_DB::getPublic();
//        if ($eventSubId == '1' || $eventSubId == '11') {
//            $chakuJis2 = '13';
//        } else {
//            $eveSubData = $this->_Eventsub->fetchEventsubByEventsubId($db, $eventSubId);
//            if (empty($eveSubData)) {
//                Sgmov_Component_Log::info("イベントサブマスタが取得できませんでした。eventSubId:" . $eventSubId);
//            }
//            $chakuJis2 = substr($eveSubData['jis5cd'], 0, 2);
//        }
//
//        $week = array("日", "月", "火", "水", "木", "金", "土");
//        try {
//            $outBoundData = $this->_OutBoundUnCollectCal->fetchOutBoundCollectCalByHaChaku($db, $eventSubId, $hatsuJis2, $chakuJis2);
//            if(empty($outBoundData)) {
//                throw new Exception("A call to fetchOutBoundCollectCalByHaChaku(db, {$eventSubId}, {$hatsuJis2}, {$chakuJis2}) returned NO outBoundData!)");
//            }
//            
//            // 引き渡し年月日から日付型オブジェクトを取得
//            $deliveryDtFr = new DateTime($eveSubData['last_arrival_date']);
//            $deliveryDtTo = new DateTime($eveSubData['last_arrival_date']);
//
//            // 集荷終了減算日
//            $toSubNum = @$outBoundData['plus_period'];
//            if (@empty($toSubNum)) {
//                $toSubNum = 0;
//            }
//            // 終了加算日
//            $frSubNum = $outBoundData['deli_period'];
//            if (@empty($frSubNum)) {
//                $frSubNum = 0;
//            }
//
//            // 計算後開始日
//            $subFrDt = $deliveryDtFr->modify('-' . ($toSubNum + $frSubNum) . ' days'); // $deliveryDtFr => 入力された日付
//            $subFrDtFormat = $subFrDt->format('Y-m-d');
//            
//            $shukaFrDate = new DateTime($eveSubData['out_bound_unloading_fr']);
//            
//            $shukaFrDateFormat = $shukaFrDate->format("Y-m-d");
//            
//            $currentDt = new DateTime('now');
//            $currentDtFormat = $currentDt->format('Y-m-d');
//            if($currentDtFormat < $shukaFrDateFormat
//                    && $subFrDtFormat <= $shukaFrDateFormat) {
//                // 『 本日日付 < DB [申込TBL] の集荷開始日 』 かつ 『 入力された引渡し日 - (リードタイムTBLの プラス期間+宅配可能期間)  <= DB [申込TBL] の集荷開始日 』
//                $subFrDt = $shukaFrDate;
//
//            } else if ($shukaFrDateFormat < $currentDtFormat
//                        && $subFrDtFormat <= $currentDtFormat) {
//                // 『 DB [申込TBL] の集荷開始日 < 本日日付 』 かつ 『 入力された引渡し日 - (リードタイムTBLの プラス期間+宅配可能期間)  <= 本日日付 』
//                $subFrDt = $currentDt->modify('+1day');
//            } else if ($subFrDtFormat <= $currentDtFormat) {
//                // 『 入力された引渡し日 - (リードタイムTBLの プラス期間+宅配可能期間)  <= 本日日付 』
//                $subFrDt = $currentDt->modify('+1day');
//            }
//            
//            // 計算後終了日
//            $subToDt = $deliveryDtTo->modify('-' . $toSubNum . ' days');
//            
//            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//            // 集荷希望日の前日12時を過ぎると、翌日は選択できなくする
//            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//            $dateChNow = (new DateTime());
//            $subFrDt2 = (new DateTime($subFrDt->format('Y-m-d H:i:s')));
//            $dateCh2 = $subFrDt2->modify('-1days'); // 
//            $dateCh2->setTime(12, 0, 0);
//            $dateChNowFmt = $dateChNow->format('Y-m-d H:i:s');
//            $dateCh2Fmt = $dateCh2->format('Y-m-d H:i:s');
//            if ($dateCh2Fmt <= $dateChNowFmt) {
//                $dateForSetting = $dateChNow->modify('+2days');
//                $subFrDt = $dateForSetting;
//            }
//            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//            if ($subToDt->format('Y-m-d') < $subFrDt->format('Y-m-d')) {
//                return array(
//                    "errorMsg" => '受付時間が終了しました。',
//                    "strFrDate"  => '',  // 画面表示用開始日
//                    "strToDate" => '',  // 画面表示用終了日
//                    "frDate" => '1900-01-01', // 内部保持用開始日
//                    "toDate" => '1900-01-01', // 内部保持用終了日
//                );
//            }
//            
//            // ここでreturnしないと$subFrDtは「undefined variable」エラーを発生
//            return array(
//                "strFrDate"     => $subFrDt->format('Y年m月d日') . '（' . $week[$subFrDt->format('w')] . '）',  // 画面表示用開始日
//                "strToDate"     => $subToDt->format('Y年m月d日') . '（' . $week[$subToDt->format('w')] . '）',  // 画面表示用終了日
//                "frDate"        => $subFrDt->format('Y-m-d'),                                                   // 内部保持用開始日
//                "toDate"        => $subToDt->format('Y-m-d'),                                                   // 内部保持用終了日
//            );
//        } catch (exception $e) {
//            Sgmov_Component_Log::debug("Exception {$e->getCode()} line {$e->getLine()} in '{$e->getFile()}': {$e->getMessage()}");
//        }
//        
//        return null;
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

