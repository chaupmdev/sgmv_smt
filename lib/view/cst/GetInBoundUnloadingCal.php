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
Sgmov_Lib::useServices(array('InBoundUnloadingCal'
                            ,'AlpenLeadTime'
                            , 'Eventsub'));
/**#@-*/

/**
 * 顧客コードから顧客情報を検索して返します。
 * @package    View
 * @subpackage CST
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Cst_GetInBoundCal extends Sgmov_View_Public {

    private $baseUrl = '';
    /**
     * 復路リードタイムマスタ
     */
    public $_InBoundUnloadingCal;

    /**
     * アルペンリードタイムマスタ
     */
    public $_AlpenLeadTime;

    /**
     * イベントサブマスタサービス
     */
    public $_Eventsub;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_InBoundUnloadingCal = new Sgmov_Service_InBoundUnloadingCal();
        $this->_AlpenLeadTime       = new Sgmov_Service_AlpenLeadTime();
        $this->_Eventsub            = new Sgmov_Service_Eventsub();
    }

    /**
     * 処理を実行します。
     *
     */
    public function executeInner() {

        $featureId            = filter_input(INPUT_POST, 'featureId');
        $fromGamenId          = filter_input(INPUT_POST, 'id');
        $ticket               = filter_input(INPUT_POST, 'ticket');
        $eventId              = filter_input(INPUT_POST, 'event_sel');                                          // イベントID
        $eventSubId           = filter_input(INPUT_POST, 'eventsub_sel');                                       // イベントサブID
        $chakuJis2            = filter_input(INPUT_POST, 'comiket_detail_inbound_pref_cd_sel');                 // 配送先JIS5（配送先都道府県の選択）
        $azukariYear          = filter_input(INPUT_POST, 'comiket_detail_inbound_collect_date_year_sel');       // 預かり年
        $azukariMonth         = filter_input(INPUT_POST, 'comiket_detail_inbound_collect_date_month_sel');      // 預かり月
        $azukariDay           = filter_input(INPUT_POST, 'comiket_detail_inbound_collect_date_day_sel');        // 預かり日
        $fromDt               = filter_input(INPUT_POST, 'hid_comiket-detail-inbound-delivery-date-from_ori');  // 元開始日付
        $toDt                 = filter_input(INPUT_POST, 'hid_comiket-detail-inbound-delivery-date-to_ori');    // 元開始日付

        ////////////////////////////////////////////////////////////////////////////////
        // コミケットアピール
        ////////////////////////////////////////////////////////////////////////////////
        // イベントサブIDを設定
        if ($eventSubId == '2503') {
            $azukariDay = '31'; // 2022/12/06で固定する（お届け日を 2022/12/31 固定にするため）
        }

        // DB接続
        $db = Sgmov_Component_DB::getPublic();

        // イベントサブマスタ取得
        $eveSubData = $this->_Eventsub->fetchEventsubByEventsubId($db, $eventSubId);
        if ($eventSubId == '1' || $eventSubId == '11') {
            $hatsuJis2 = '13';
        } else {
            if (empty($eveSubData)) {
                Sgmov_Component_Log::info("イベントサブマスタが取得できませんでした。eventSubId:" . $eventSubId);
            }
            $hatsuJis2 = (int)substr($eveSubData['jis5cd'], 0, 2);
        }

        // チケット確認
        $this->_checkSession($featureId, $fromGamenId, $ticket);
        $week = array("日", "月", "火", "水", "木", "金", "土");

        try {

            // 復路・搬出リードタイムマスタを取得
            switch($eventId){
                // アルペン：今後もアルペンのような店舗の申込がある場合は処理を追加する
                case'6000':
                    $inBoundData = $this->_AlpenLeadTime->getInfo($db, $eventId, $eventSubId, $chakuJis2);
                    break;
                // アルペン以外
                default:
                    // 復路・搬出リードタイムマスタを取得
                    $inBoundData = $this->_InBoundUnloadingCal->fetchInBoundUnloadingCalByHaChaku($db, $eventSubId, $hatsuJis2, $chakuJis2);
                    break;
            }
            if(empty($inBoundData)) {
                throw new Exception;
            }

            $baseUrl = $this->baseUrl;
            $result = array();

            // 預かり年月日から日付型オブジェクトを取得
            // 預かり日が選択された場合の日付がセットされる
            // 預かり日が固定の場合はhiddenにセットされた日付
            $azukariDtFr = new DateTime($azukariYear . '-' . $azukariMonth . '-' . $azukariDay);
            $azukariDtTo = new DateTime($azukariYear . '-' . $azukariMonth . '-' . $azukariDay);

            // 開始加算日
            $plusPeriod = $inBoundData['plus_period'];
            // 終了加算日
            $deliPeriod = $inBoundData['deli_period'];

            // 計算後開始日
            $addFrDt = $azukariDtFr->modify('+' . $plusPeriod . ' days');
            $today = new DateTime();
            $todayFormat = $today->format("Y-m-d");
            $addFrDtFormat = $addFrDt->format('Y-m-d');
            if($addFrDtFormat <= $todayFormat) {
                $addFrDtFormat = $todayFormat;
            }

//             if ($eveSubData['in_bound_unloading_fr'] <= $addFrDtFormat) {
//                 $addFrDtFormat = $eveSubData['in_bound_unloading_fr'];
//                 $addFrDt = new DateTime($eveSubData['in_bound_unloading_fr']);
//             }

            // 計算後終了日
            $addToDt = $azukariDtTo->modify('+' . ($plusPeriod + $deliPeriod) . ' days');
            // お届け終了日
            $inBoundUnloadingTo = new DateTime($eveSubData['in_bound_unloading_to']);

//            // TODO:この処理意味不明
//            if ($inBoundUnloadingTo < $addToDt) {
//                $addToDt = new DateTime($eveSubData['in_bound_unloading_to']);
//            }

        } catch (exception $e) {
//            throw $e;
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // お届け指定日時イベントの期間を設定する
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // お届け日の期間が変更になるイベントサブID
        $eventsubIdList = array("811", "26", '711');
        if (in_array($eventSubId, $eventsubIdList)) {
            // お届け日の期間が変更になる地域
            $temp = ["1", "2"]; // 北海道、青森
            // 中国、四国、九州(沖縄除く)
            foreach (range(31, 46) as $val) {
                array_push($temp, $val);
            }
            // $temp2 = ["47"]; // 沖縄
            $temp2 = array();

            $inboundLodingFr = $eveSubData['in_bound_unloading_fr'] . " 00:00:00";
            $inboundLodingTo = $eveSubData['in_bound_unloading_to'] . " 00:00:00";

            $azukariYear = date('Y', strtotime($inboundLodingFr));
            $azukariMonth = date('m', strtotime($inboundLodingFr));

            $azukariToYear = date('Y', strtotime($inboundLodingTo));
            $azukariToMonth = date('m', strtotime($inboundLodingTo));
            $azukariToDay = date('d', strtotime($inboundLodingTo));

            // 通常+2日
            if(in_array($chakuJis2 , $temp2)) {
                $azukariDay = date('d', strtotime($inboundLodingFr.' +2 days'));
            // 通常+1日
            } else if(in_array($chakuJis2 , $temp)){
                $azukariDay = date('d', strtotime($inboundLodingFr.' +1 days'));
            // 通常
            }else{
                $azukariDay = date('d', strtotime($inboundLodingFr));
            }
            
            $addFrDt = new DateTime($azukariYear . '-' . $azukariMonth . '-' . $azukariDay);
            $addToDt = new DateTime($azukariToYear . '-' . $azukariToMonth . '-' . $azukariToDay);

            return array(
                "strFrDate"     => @$addFrDt->format('Y年m月d日') . '（' . $week[$addFrDt->format('w')] . '）',  // 画面表示用開始日
                "strToDate"     => @$addToDt->format('Y年m月d日') . '（' . $week[$addToDt->format('w')] . '）',  // 画面表示用終了日
                "frDate"        => @$addFrDt->format('Y-m-d'),                                                  // 内部保持用開始日
                "toDate"        => @$addToDt->format('Y-m-d'),                                                  // 内部保持用終了日
            );
        }


        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // ゲームマーケット2021春　イベントサブ・預かり日から日付計算する。
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        if($eventSubId == "303"){
            $inboundLodingTo = $eveSubData['in_bound_unloading_to'] . " 00:00:00";
            $azukariToYear = date('Y', strtotime($inboundLodingTo));
            $azukariToMonth = date('m', strtotime($inboundLodingTo));
            $azukariToDay = date('d', strtotime($inboundLodingTo));

            $addToDt = new DateTime($azukariToYear . '-' . $azukariToMonth . '-' . $azukariToDay);
        }
       
        return array(
            "strFrDate"     => @$addFrDt->format('Y年m月d日') . '（' . $week[$addFrDt->format('w')] . '）',  // 画面表示用開始日
            "strToDate"     => @$addToDt->format('Y年m月d日') . '（' . $week[$addToDt->format('w')] . '）',  // 画面表示用終了日
            "frDate"        => @$addFrDt->format('Y-m-d'),                                                  // 内部保持用開始日
            "toDate"        => @$addToDt->format('Y-m-d'),                                                  // 内部保持用終了日
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

