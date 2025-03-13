<?php
/**
 * @package    ClassDefFile
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
// イベント識別子(URLのpath)はマジックナンバーで記述せずこの変数で記述してください
$dirDiv=Sgmov_Lib::setDirDiv(dirname(__FILE__));
Sgmov_Lib::useAllComponents();
Sgmov_Lib::useServices(array('CruiseRepeater', 'Prefecture', 'Yubin', 'SocketZipCodeDll'
    , 'Event', 'Box', 'Cargo', 'Building', 'Charter', 'Eventsub'
    , 'AppCommon', 'HttpsZipCodeDll', 'BoxFare', 'CargoFare', 'Comiket', 'EventsubCmb', 'Time'));
Sgmov_Lib::useView('Public');
/**#@-*/

//define("COMIKET_DEV_INDIVIDUA", 1); // 個人
//define("COMIKET_DEV_BUSINESS", 2); // 法人
/**
 * イベントサービスのお申し込みフォームの共通処理を管理する抽象クラスです。
 * @package    View
 * @subpackage EVE
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Eve_Common extends Sgmov_View_Public {
    /**
     * 機能ID
     */
    const FEATURE_ID = 'EVE';

    /**
     * イベントID
     */
    const EVENT_ID = '5';

    /**
     * イベントサブID
     */
    const EVENT_SUB_ID = '1501';



    /**
     * EVE001の画面ID
     */
    const GAMEN_ID_EVE001 = 'EVE001';

    /**
     * EVE002の画面ID
     */
    const GAMEN_ID_EVE002 = 'EVE002';

    /**
     * EVE003の画面ID
     */
    const GAMEN_ID_EVE003 = 'EVE003';

    /**
     * 個人
     */
    const COMIKET_DEV_INDIVIDUA = "1";

    /**
     * 法人
     */
    const COMIKET_DEV_BUSINESS = "2";

    const CURRENT_TAX = 1.08;

    /**
     * 識別コード選択値
     * @var array
     */
    public $comiket_div_lbls = array(
        1 => '<span class="disp_comiket" style="display:none;">電子決済の方(クレジット、コンビニ決済、電子マネー)</span><span class="disp_design">出展者</span><span class="disp_gooutcamp">出展者（個人・法人含む）</span><span class="disp_etc">個人</span>',
        2 => '請求書にて請求',
    );
//    public $comiket_div_lbls = array(
//        1 => '個人<span class="disp_comiket" style="display:none;">（または法人の電子決済）</span><strong class="disp_design red" style="display:none" >（出展者の方はこちらからお申し込みください）</strong>',
//        2 => '法人（請求書にて請求）',
//    );

    /**
     * 集荷の往復コード選択値
     * @var array
     */
    public $comiket_detail_type_lbls = array(
        1 => '搬入（お客様⇒会場）',
        2 => '搬出（会場⇒お客様）',
//        2 => '搬出', // 国内クルーズ
        3 => '搬入（お客様⇒会場）と搬出（会場⇒お客様）',
    );

    /**
     * サービス選択値
     * @var array
     */
    public $comiket_detail_service_lbls = array(
        1 => '宅配便',
        2 => 'カーゴ',
        3 => '貸切（チャーター）',
    );

    /**
     * 集荷希望時間帯コード選択値(お預かり日時)
     * @var array
     */
    public $comiket_detail_time_lbls = array(
        '00' => '指定なし',
        '10:00:00-13:00:00' => '10:00～13:00',
        '12:00:00-15:00:00' => '12:00～15:00',
        '15:00:00-18:00:00' => '15:00～18:00',
        '18:00:00-20:00:00' => '18:00～20:00',
    );

    /**
     * 集荷希望時間帯コード選択値(引渡し希望日)
     * @var array
     */
    public $comiket_detail_time_lbls_par30m = array(
        '00' => '指定なし',
        '07:00:00-07:30:00' => '07:00',
        '07:30:00-08:00:00' => '07:30',
        '08:00:00-08:30:00' => '08:00',
        '08:30:00-09:00:00' => '08:30',
        '09:00:00-09:30:00' => '09:00',
        '09:30:00-10:00:00' => '09:30',
        '10:00:00-10:30:00' => '10:00',
        '10:30:00-11:00:00' => '10:30',
        '11:00:00-11:30:00' => '11:00',
    );

    /**
     * 配送希望時間帯コード選択値
     */
    public $comiket_detail_delivery_timezone = array();

    /**
     * プルダウンカーゴ用
     * @var type array
     */
    public $comiket_cargo_item_list = array(
//        "0" => "0",
        "1" => "1",
        "2" => "2",
        "3" => "3",
    );

    /**
     * 集荷の往復コード選択値
     * @var array
     */
    public $terminal_lbls = array(
        1 => '搬入のみ',
        2 => '搬出のみ',
        3 => '往復',
    );

    /**
     * お支払方法コード選択値
     * @var array
     */
    public $payment_method_lbls = array(
        '' => '',
        1  => 'コンビニ前払い',
        2  => 'クレジットカード',
        3  => '電子マネー',
        4  => 'コンビニ後払い',
    );

    /**
     * お支払店コード選択値
     * @var array
     */
    public $convenience_store_lbls = array(
        1 => 'セブンイレブン',
        2 => 'ローソン、セイコーマート、ファミリーマート、サークルＫサンクス、ミニストップ',
        3 => 'デイリーヤマザキ',
    );

    /**
     * 集荷希望時間帯コード選択値
     * @var array
     */
    public $cargo_collection_st_time_lbls = array(
        '00' => '指定なし',
        10 => '10:00～13:00',
        12 => '12:00～15:00',
        15 => '15:00～18:00',
        18 => '18:00～20:00',
    );

    /**
     * 集荷希望終了時刻コード選択値
     * @var array
     */
    public $cargo_collection_ed_time_lbls = array(
        '00' => '00',
        10 => '13',
        12 => '15',
        15 => '18',
        18 => '20',
    );

///////////////////////////////////////////////////////////////////////////////////
// サービス
///////////////////////////////////////////////////////////////////////////////////

    /**
     * 共通サービス
     * @var Sgmov_Service_AppCommon
     */
    private $_appCommon;

    /**
     * 都道府県サービス
     * @var Sgmov_Service_Prefecture
     */
    private $_ComiketService;

    /**
     * 都道府県サービス
     * @var Sgmov_Service_Prefecture
     */
    private $_PrefectureService;

    /**
     * イベントサービス
     * @var Sgmov_Service_Event
     */
    private $_EventService;

    /**
     * イベントサービス
     * @var Sgmov_Service_Eventsub
     */
    private $_EventsubService;

    /**
     * 宅配サービス
     * @var type
     */
    private $_BoxService;


    /**
     * カーゴサービス
     * @var type
     */
    private $_CargoService;

    /**
     * 館マスタサービス(ブース番号)
     * @var type
     */
    private $_BuildingService;

    private $_HttpsZipCodeDll;

    private $_BoxFareService;

    protected $_CharterService;

    protected $_CargoFareService;

    protected $_EventsubCmbService;

    protected $_SocketZipCodeDll;

    /**
     * 時間帯サービス
     * @var type
     */
    private $_TimeService;

//    protected $_Charter;

    // 識別子
    protected $_DirDiv;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_appCommon             = new Sgmov_Service_AppCommon();
        $this->_ComiketService = new Sgmov_Service_Comiket();
        $this->_PrefectureService     = new Sgmov_Service_Prefecture();
        $this->_EventService          = new Sgmov_Service_Event();
        $this->_EventsubService       = new Sgmov_Service_Eventsub();
        $this->_BoxService            = new Sgmov_Service_Box();
        $this->_CargoService          = new Sgmov_Service_Cargo();
        $this->_BuildingService       = new Sgmov_Service_Building();
        $this->_CharterService        = new Sgmov_Service_Charter();
        $this->_HttpsZipCodeDll       = new Sgmov_Service_HttpsZipCodeDll();
        $this->_BoxFareService        = new  Sgmov_Service_BoxFare();
        $this->_CargoFareService      = new  Sgmov_Service_CargoFare();

        $this->_EventsubCmbService    = new Sgmov_Service_EventsubCmb();

        $this->_TimeService           = new Sgmov_Service_Time();

        $this->_SocketZipCodeDll                 = new Sgmov_Service_SocketZipCodeDll();

        // 識別子のセット
        $this->_DirDiv = Sgmov_Lib::setDirDiv(dirname(__FILE__));

        $comiketCargoItemList = array();
        for($i=1; $i <= 99; $i++) {
            $comiketCargoItemList[$i] = $i;
        }
        $this->comiket_cargo_item_list = $comiketCargoItemList;
    }

    /**
     * 表示用時刻を取得する
     * @param object $db
     * @return
     */
    protected function _fetchTime($begin, $end, $step = 1) {
        $ids = array('');
        $names = array('');
        for ($i = $begin; $i <= $end; $i += $step) {
            $ids[] = sprintf('%02d', $i);
            $names[] = $i;
        }
        return array(
            'ids' => $ids,
            'names' => $names,
        );
    }

    /**
     * プルダウンを生成し、HTMLソースを返します。
     * TODO pre/Inputと同記述あり
     *
     * @param $cds コードの配列
     * @param $lbls ラベルの配列
     * @param $select 選択値
     * @return 生成されたプルダウン
     */
    public static function _createPulldown($cds, $lbls, $select, $flg = null, $date = null) {

        $html = '';

        if (empty($cds)) {
            return $html;
        }
Sgmov_Component_Log::debug("################## 301-1");
Sgmov_Component_Log::debug($select);
Sgmov_Component_Log::debug($cds);
Sgmov_Component_Log::debug($lbls);
        $count = count($cds);
        for ($i = 0; $i < $count; ++$i) {

            // $flg（受付時間超過フラグ）があるならプロパティ文字列を作成する
            $timeover = '';
            if (!empty($flg)) {
                $timeover = ' timeoverflg="' .$flg[$i] . '"';
            }

            // $date（受付終了日付）があるならプロパティ文字列を作成する
            $timeoverDt = '';
            if (!empty($date)) {
                $timeoverDt = ' timeoverdate="' .$date[$i] . '"';
            }

            if ($select === $cds[$i]) {
                $html .= '<option value="' . $cds[$i] . '" selected="selected"' . $timeover . $timeoverDt . '>' . $lbls[$i] . '</option>' . PHP_EOL;
            } else {
                $html .= '<option value="' . $cds[$i] . '"' . $timeover . $timeoverDt . '>' . $lbls[$i] . '</option>' . PHP_EOL;
            }
        }

        return $html;
    }

    public static function _getWeek($year, $month, $day) {
        $week = array("日", "月", "火", "水", "木", "金", "土");
//        $outboundCollectFr = date('Y-m-d', strtotime("$year"));
        $resultWeek = $week[date('w', strtotime("{$year}-{$month}-{$day}"))];
        return $resultWeek;
    }

    public static function _getTimeFormatSelectPulldownData($cds, $lbls, $select) {

        $html = '';

        if (empty($cds)) {
            return $html;
        }

        $count = count($cds);
        for ($i = 0; $i < $count; ++$i) {
            if ($select === $cds[$i]) {
                $timeInfo = explode('-', $cds[$i]);
                if(count($timeInfo) != 2) {
                    return "指定なし";
                }
                return date('H:i', strtotime($timeInfo[0])) . "～" . date('H:i', strtotime($timeInfo[1]));
            }
        }

        return "";
    }

    public static function _getTimeFormatSelectPulldownDataForInBound($cds, $lbls, $select) {

        $html = '';

        if (empty($cds)) {
            return $html;
        }

        $count = count($cds);
        for ($i = 0; $i < $count; ++$i) {
            if ($select === $cds[$i]) {
                $arrCds = explode(',', $cds[$i]);
                $arrTimezoneInfo = explode('～', $arrCds[1]);
                if($arrCds[0] == '00') {
                    return "指定なし";
                } else if ($arrCds[0] == '11') {
                    return "午前中";
                }
                return $arrTimezoneInfo[0] . "～" . $arrTimezoneInfo[1];
            }
        }

        return "";
    }

    public static function _getCodeSelectPulldownData($cds, $lbls, $select) {

        $html = '';

        if (empty($cds)) {
            return $html;
        }

        $count = count($cds);
        for ($i = 0; $i < $count; ++$i) {
            if ($select === $cds[$i]) {
                return $cds[$i];
            }
        }

        return "";
    }

    public static function _getLabelSelectPulldownData($cds, $lbls, $select) {

        $html = '';

        if (empty($cds)) {
            return $html;
        }

        $count = count($cds);
        for ($i = 0; $i < $count; ++$i) {
            if ($select === $cds[$i]) {
                return $lbls[$i];
            }
        }

        return "";
    }

    /**
     * プルダウンを生成し、HTMLソースを返します。
     * TODO pre/Inputと同記述あり
     *
     * @param $cds コードの配列
     * @param $lbls ラベルの配列
     * @param $select 選択値
     * @return 生成されたプルダウン
     */
    public static function _createPulldownAddDate($cds, $lbls, $select, $dates = null) {

        $html = '';

        if (empty($cds)) {
            return $html;
        }

        $count = count($cds);
        for ($i = 0; $i < $count; ++$i) {
            if ($select === $cds[$i]) {
                $html .= '<option data-date="' . $dates[$i] . '" value="' . $cds[$i] . '" selected="selected">' . $lbls[$i] . '</option>' . PHP_EOL;
            } else {
                $html .= '<option data-date="' . $dates[$i] . '" value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
            }
        }

        return $html;
    }

    /**
     * プルダウンを生成し、HTMLソースを返します。
     *
     * @param $cds コードの配列
     * @param $lbls ラベルの配列
     * @param $select 選択値
     * @return 生成されたプルダウン
     */
    public static function _createPulldownAddDiscount($cds, $lbls, $select, $discount = null) {

        $html = '';

        if (empty($cds)) {
            return $html;
        }

        $count = count($cds);
        for ($i = 0; $i < $count; ++$i) {
            if ($select === $cds[$i]) {
                $html .= '<option data-discount="' . $discount[$i] . '" value="' . $cds[$i] . '" selected="selected">' . $lbls[$i] . '</option>' . PHP_EOL;
            } else {
                $html .= '<option data-discount="' . $discount[$i] . '" value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
            }
        }

        return $html;
    }

    /**
     * 割引を返します。
     *
     * @param $checkDeparture
     * @param $checkArrival
     * @param $db
     * @param $_TravelService  ツアーサービス
     * @param $_CruiseRepeater クルーズリピータサービス
     * @param $inForm
     * @return array 往復割引・リピータ割引
     */
    protected static function _getDiscount($checkDeparture, $checkArrival, $db, $_TravelService, $_CruiseRepeater, $inForm) {

        if (!$checkDeparture || !$checkArrival) {
            return array(
                'round_trip_discount' => 0,
                'repeater_discount'   => 0,
            );
        }

        $discount = $_TravelService->fetchDiscount(
            $db,
            array(
                'travel_id' => $inForm['travel_cd_sel']
            )
        );

        if (empty($discount['round_trip_discount'])) {
            $discount['round_trip_discount'] = 0;
        }

        if ($inForm['payment_method_cd_sel'] !== '2') {
            $discount['repeater_discount'] = 0;
        } else {
            $cruise_repeater = $_CruiseRepeater->fetchCruiseRepeaterLimit(
                $db,
                array(
                    'tel' => $inForm['tel1'] . $inForm['tel2'] . $inForm['tel3'],
                    'zip' => $inForm['zip1'] . $inForm['zip2'],
                )
            );
            if (empty($cruise_repeater['tels'][0])) {
                $discount['repeater_discount'] = 0;
            }
        }

        return $discount;
    }

    /**
     *
     * @param type $eventList
     * @param type $id
     */
    public static function _getEventData($eventList, $id) {
//Sgmov_Component_Log::debug("########################## 501");
//Sgmov_Component_Log::debug($eventList);
//Sgmov_Component_Log::debug($id);
        foreach($eventList as $val) {
            if($val["id"] == $id) {
//Sgmov_Component_Log::debug("########################## 502");
                return $val;
            }
        }
//Sgmov_Component_Log::debug("########################## 503");
        return array();
    }

    /**
     *
     * @param type $inForm
     * @param Sgmov_Form_Eve001Out $outForm
     * @return type
     */
    protected function createOutFormByInForm($inForm, $outForm = array()) {

        $dispItemInfo = array();

        // TODO オブジェクトから値を直接取得できるよう修正する
        //
        // オブジェクトから取得できないため、配列に型変換
        $inForm = (array)$inForm;

        $db = Sgmov_Component_DB::getPublic();

        $eventAll = $this->_EventService->fetchEventListWithinTerm2($db);
//Sgmov_Component_Log::debug("################## 101");
//Sgmov_Component_Log::debug($eventAll);

        $eventAll2 = array();
        $eventIds = array();
        $eventNames = array();
        $eventNames2 = array();

        $week = array("日", "月", "火", "水", "木", "金", "土");
        foreach($eventAll as $key => $val) {
            $eventIds[] = $val["id"];
            $eventNames[] = $val["name"];
            $eventNames2[] = $val["event_name"] . "　" . $val["eventsub_name"];
//            $outboundCollectFr = date('Y-m-d', strtotime('-11 day', strtotime($val["term_fr"])));
//            $outboundCollectFrWeek = $week[date('w', strtotime($outboundCollectFr))];
//            $outboundCollectTo = date('Y-m-d', strtotime('-5 day', strtotime($val["term_to"])));
//            $outboundCollectToWeek = $week[date('w', strtotime($outboundCollectTo))];
//            $outboundDeliveryFr = date('Y-m-d', strtotime($val["term_fr"]));
//            $outboundDeliveryFrWeek = $week[date('w', strtotime($outboundDeliveryFr))];
//            $outboundDeliveryTo = date('Y-m-d', strtotime($val["term_to"]));
//            $outboundDeliveryToWeek = $week[date('w', strtotime($outboundDeliveryTo))];
//
//            $inboundCollectFr = date('Y-m-d', strtotime($val["term_fr"]));
//            $inboundCollectFrWeek = $week[date('w', strtotime($inboundCollectFr))];
//            $inboundCollectTo = date('Y-m-d', strtotime($val["term_to"]));
//            $inboundCollectToWeek = $week[date('w', strtotime($inboundCollectTo))];
//            $inboundDeliveryFr = date('Y-m-d', strtotime('+5 day', strtotime($val["term_to"])));
//            $inboundDeliveryFrWeek = $week[date('w', strtotime($inboundDeliveryFr))];
//            $inboundDeliveryTo = date('Y-m-d', strtotime('+11 day', strtotime($val["term_to"])));
//            $inboundDeliveryToWeek = $week[date('w', strtotime($inboundDeliveryTo))];
//
//            $val["outbound_collect_fr"] = date('Y年m月d日', strtotime($outboundCollectFr)) . "（" . $outboundCollectFrWeek . "）";
//            $val["outbound_collect_to"] = date('Y年m月d日', strtotime($outboundCollectTo)) . "（" . $outboundCollectToWeek . "）";
//            $val["outbound_delivery_fr"] = date('Y年m月d日', strtotime($outboundDeliveryFr)) . "（" . $outboundDeliveryFrWeek . "）";
//            $val["outbound_delivery_to"] = date('Y年m月d日', strtotime($outboundDeliveryTo)) . "（" . $outboundDeliveryToWeek . "）";
//
//            $val["inbound_collect_fr"] = date('Y年m月d日', strtotime($inboundCollectFr)) . "（" . $inboundCollectFrWeek . "）";
//            $val["inbound_collect_to"] = date('Y年m月d日', strtotime($inboundCollectTo)) . "（" . $inboundCollectToWeek . "）";
//            $val["inbound_delivery_fr"] = date('Y年m月d日', strtotime($inboundDeliveryFr)) . "（" . $inboundDeliveryFrWeek . "）";
//            $val["inbound_delivery_to"] = date('Y年m月d日', strtotime($inboundDeliveryTo)) . "（" . $inboundDeliveryToWeek . "）";
            $eventAll2[] = $val;
        }

//Sgmov_Component_Log::debug("################## 102");
//Sgmov_Component_Log::debug($eventAll2);

        $outForm->raw_comiket_id  = @$inForm['comiket_id'];

        // 出展イベント
        $dispItemInfo["event_alllist"] = $eventAll2;
        $outForm->raw_event_cds  = $eventIds;
//        $outForm->raw_event_lbls = $eventNames;
        $outForm->raw_event_lbls = $eventNames2;
        $outForm->raw_event_cd_sel = $inForm["event_sel"];

//Sgmov_Component_Log::debug("################## 103");
//Sgmov_Component_Log::debug($inForm);
//$inForm["event_sel"] = "1";
        // 出展イベントサブ
        $eventsubAry2 = $this->_EventsubService->fetchEventsubListWithinTermByEventId($db, $inForm["event_sel"]);

Sgmov_Component_Log::debug("################## 103");
Sgmov_Component_Log::debug($eventsubAry2);

/////////////////////////////////////////////////////////////////////////////////////////////////////////
// 入力モード制御
/////////////////////////////////////////////////////////////////////////////////////////////////////////

        $outForm->raw_input_mode = $inForm['input_mode'];
        if(!empty($inForm['input_mode']) && !empty($eventsubAry2)) {
            // イベントIDは上記にて設定済み
            $eventsubList = $eventsubAry2["list"];
//            $outForm->raw_event_cd_sel = $inForm["event_sel"];
            $inForm['eventsub_sel'] = $eventsubList[0]['id'];
//            $outForm->raw_eventsub_cd_sel = $eventsubList[0]['id'];
        }

///////////////////////////////////////////////////////////////////////////////////////////////////////////

        // イベントサブリストをループで回し、１つ１つ申込受付時間が過ぎていないか確認。過ぎているならフラグをセット
        $sysdate = new DateTime();
        foreach ($eventAll2 as $data) {
            $eventSubData = $this->_EventsubService->fetchEventsubListWithinTermByEventId($db, $data['id']);

            foreach ($eventSubData['list'] as $key => $value) {
                $arrvalDt = $eventSubData['list'][$key]['arrival_to_time'];
                $eveEndDt   = date('U', strtotime($arrvalDt));
                $currentDt  = intval($sysdate->format('U'));
//                Sgmov_Component_Log::debug('イベントサブID：' . $eventSubData['list'][$key]['id']);
//                Sgmov_Component_Log::debug('イベント受付終了時間：' . $eventSubData['list'][$key]['arrival_to_time'] . '【' . date('U', strtotime($eventSubData['list'][$key]['arrival_to_time'])) . '】');
//                Sgmov_Component_Log::debug('現在日：' . $sysdate->format('Y/m/d') . '【' . intval($sysdate->format('U')) . '】');

                if ($eveEndDt < $currentDt) {
                    array_push($outForm->eve_entry_timeover_flg, '1');
                } else {
                    array_push($outForm->eve_entry_timeover_flg, '0');
                }

                // 申込終了時間を文字列化してフォームにセット
                $strWeek = $this->_getWeek(date('Y', strtotime($arrvalDt)), date('m', strtotime($arrvalDt)), date('d', strtotime($arrvalDt)));
                array_push($outForm->eve_entry_timeover_date, date('Y年n月j日', strtotime($arrvalDt)) . '（' . $strWeek . '）' . date('H:i', strtotime($arrvalDt)));
            }
        }

        $eventsubAry3 = array();
        $dispItemInfo["eventsub_selected_data"] = "";
        if(!empty($eventsubAry2)) {
            $outForm->raw_eventsub_cds  = $eventsubAry2['ids'];
            $outForm->raw_eventsub_lbls = $eventsubAry2['names'];
            $outForm->raw_eventsub_cd_sel = $inForm["eventsub_sel"];

//            if(!empty($inForm["comiket_detail_outbound_pref_cd_sel"])) {
//
//            }
            $inboundHatsuJis2 = "";
            $inboundChakuJis2 = "";
//            if(($inForm["comiket_detail_type_sel"] == "2" || $inForm["comiket_detail_type_sel"] == "3") // 搬出 または 往復
//                   && !empty($inForm["comiket_detail_inbound_pref_cd_sel"])) { // 搬出の 都道府県が選択されている場合
//                // 搬出 /////////////////////////////////////////////
//                $this->setYubinDllInfoToInForm($inForm);
//                $inboundHatsuJis2 = $inForm["inbound_hatsu_jis2code"];
//                $inboundChakuJis2 = $inForm["inbound_chaku_jis2code"];
//            }

            $eventsubCmbAry = $this->_EventsubCmbService->cmbEventsubList($eventsubAry2["list"], $inForm["eventsub_sel"], $inboundHatsuJis2, $inboundChakuJis2);
            $eventsubAry3 = $eventsubCmbAry["list"];
            $dispItemInfo["eventsub_selected_data"] = $eventsubCmbAry["selectedData"];
        }
        $dispItemInfo["eventsub_list"] = $eventsubAry3;



//Sgmov_Component_Log::debug("################## 104");
//Sgmov_Component_Log::debug($dispItemInfo["eventsub_list"]);

        // 場所(イベント)
//        $outForm->raw_event_place = $inForm["event_place"];

        if(@!empty($dispItemInfo["eventsub_selected_data"])) {
//
//            $dispItemInfo["eventsub_selected_data"]->zip;
            $outForm->raw_eventsub_zip = $dispItemInfo["eventsub_selected_data"]["zip"];
//            // 場所(イベント)
            $outForm->raw_eventsub_address = $dispItemInfo["eventsub_selected_data"]["address"];
        }

        // 期間（イベント）
        if(!empty($inForm["event_sel"])) {
            if(!empty($dispItemInfo["eventsub_selected_data"])) {
                $outForm->raw_eventsub_term_fr = $inForm["eventsub_term_fr"] = $dispItemInfo["eventsub_selected_data"]["term_fr"];
                $outForm->raw_eventsub_term_to = $inForm["eventsub_term_to"] = $dispItemInfo["eventsub_selected_data"]["term_to"];

                // ▼ 以下で、お預かり・お届け日が同じ場合は、設定しておく（画面側では、レベル表示のため（変更不可））

                ////////////////////////////////////////////////////////////////////////////////
                // 搬入
                ////////////////////////////////////////////////////////////////////////////////
                if($dispItemInfo["eventsub_selected_data"]["is_eq_outbound_collect"]) {
Sgmov_Component_Log::debug("################## 351-1");

                    $inForm["comiket_detail_outbound_collect_date_year_sel"] = $dispItemInfo["eventsub_selected_data"]["outbound_collect_fr_year"];
                    $inForm["comiket_detail_outbound_collect_date_month_sel"] = $dispItemInfo["eventsub_selected_data"]["outbound_collect_fr_month"];
                    $inForm["comiket_detail_outbound_collect_date_day_sel"] = $dispItemInfo["eventsub_selected_data"]["outbound_collect_fr_day"];
                    $comiketDetailOutboundCollectDate = new DateTime("{$inForm["comiket_detail_outbound_collect_date_year_sel"]}-{$inForm["comiket_detail_outbound_collect_date_month_sel"]}-{$inForm["comiket_detail_outbound_collect_date_day_sel"]}");
                    $comiketDetailOutboundCollectDateFormat = $comiketDetailOutboundCollectDate->format('Y-m-d');
                    $today = new DateTime();
                    $todayFormat = $today->format("Y-m-d");
                    if($comiketDetailOutboundCollectDateFormat <= $todayFormat) {
Sgmov_Component_Log::debug("################## 351-2");
//                        $inForm["comiket_detail_outbound_collect_date_year_sel"] = $today->format('Y');
//                        $inForm["comiket_detail_outbound_collect_date_month_sel"] = $today->format('m');
//                        $inForm["comiket_detail_outbound_collect_date_day_sel"] = $today->format('d');
                        $dispItemInfo["eventsub_selected_data"]["outbound_collect_fr_year"] = $inForm["comiket_detail_outbound_collect_date_year_sel"] = $today->format('Y');
                        $dispItemInfo["eventsub_selected_data"]["outbound_collect_fr_month"] = $inForm["comiket_detail_outbound_collect_date_month_sel"] = $today->format('m');
                        $dispItemInfo["eventsub_selected_data"]["outbound_collect_fr_day"] = $inForm["comiket_detail_outbound_collect_date_day_sel"] = $today->format('d');
                    }
                }

                if($dispItemInfo["eventsub_selected_data"]["is_eq_outbound_delivery"]) {
                    $inForm["comiket_detail_outbound_delivery_date_year_sel"] = $dispItemInfo["eventsub_selected_data"]["outbound_delivery_fr_year"];
                    $inForm["comiket_detail_outbound_delivery_date_month_sel"] = $dispItemInfo["eventsub_selected_data"]["outbound_delivery_fr_month"];
                    $inForm["comiket_detail_outbound_delivery_date_day_sel"] = $dispItemInfo["eventsub_selected_data"]["outbound_delivery_fr_day"];
                }

                ////////////////////////////////////////////////////////////////////////////////
                // 搬出
                ////////////////////////////////////////////////////////////////////////////////
                if($dispItemInfo["eventsub_selected_data"]["is_eq_inbound_collect"]) {
Sgmov_Component_Log::debug("################## 351-3");
                    $inForm["comiket_detail_inbound_collect_date_year_sel"] = $dispItemInfo["eventsub_selected_data"]["inbound_collect_fr_year"];
                    $inForm["comiket_detail_inbound_collect_date_month_sel"] = $dispItemInfo["eventsub_selected_data"]["inbound_collect_fr_month"];
                    $inForm["comiket_detail_inbound_collect_date_day_sel"] = $dispItemInfo["eventsub_selected_data"]["inbound_collect_fr_day"];
                    $comiketDetailInboundCollectDate = new DateTime("{$inForm["comiket_detail_inbound_collect_date_year_sel"]}-{$inForm["comiket_detail_inbound_collect_date_month_sel"]}-{$inForm["comiket_detail_inbound_collect_date_day_sel"]}");
                    $comiketDetailInboundCollectDateFormat = $comiketDetailInboundCollectDate->format('Y-m-d');
                    $today = new DateTime();
                    $todayFormat = $today->format("Y-m-d");
                    if($comiketDetailInboundCollectDateFormat <= $todayFormat) {
Sgmov_Component_Log::debug("################## 351-4");
//                        $inForm["comiket_detail_inbound_collect_date_year_sel"] = $today->format('Y');
//                        $inForm["comiket_detail_inbound_collect_date_month_sel"] = $today->format('m');
//                        $inForm["comiket_detail_inbound_collect_date_day_sel"] = $today->format('d');
                        $dispItemInfo["eventsub_selected_data"]["inbound_collect_fr_year"] = $inForm["comiket_detail_inbound_collect_date_year_sel"] = $today->format('Y');
                        $dispItemInfo["eventsub_selected_data"]["inbound_collect_fr_month"]= $inForm["comiket_detail_inbound_collect_date_month_sel"] = $today->format('m');
                        $dispItemInfo["eventsub_selected_data"]["inbound_collect_fr_day"] = $inForm["comiket_detail_inbound_collect_date_day_sel"] = $today->format('d');
                    }
                }

                if($dispItemInfo["eventsub_selected_data"]["is_eq_inbound_delivery"]) {
                    $inForm["comiket_detail_inbound_delivery_date_year_sel"] = $dispItemInfo["eventsub_selected_data"]["inbound_delivery_fr_year"];
                    $inForm["comiket_detail_inbound_delivery_date_month_sel"] = $dispItemInfo["eventsub_selected_data"]["inbound_delivery_fr_month"];
                    $inForm["comiket_detail_inbound_delivery_date_day_sel"] = $dispItemInfo["eventsub_selected_data"]["inbound_delivery_fr_day"];
                }
            }
            $outForm->raw_eventsub_term_fr_nm = date('Y年m月d日（' . $week[date('w', strtotime($inForm["eventsub_term_fr"]))] . '）', strtotime($inForm["eventsub_term_fr"]));
            $outForm->raw_eventsub_term_to_nm = date('Y年m月d日（' . $week[date('w', strtotime($inForm["eventsub_term_to"]))] . '）', strtotime($inForm["eventsub_term_to"]));
        }

        // 識別（法人・個人）
        $outForm->raw_comiket_div = $inForm["comiket_div"];
//        if(@$inForm["event_sel"] == '2') { // コミケ
//            $dispItemInfo["comiket_div_lbls"] = array();
//            foreach($this->comiket_div_lbls as $key => $val) {
//                $dispItemInfo["comiket_div_lbls"][$key] = strip_tags($val);
//            }
//        } else {
            $dispItemInfo["comiket_div_lbls"] = $this->comiket_div_lbls;
//        }

        // 顧客コード 使用選択肢
//        if(empty($inForm["comiket_customer_cd_sel"])) {
//            $outForm->raw_comiket_customer_cd_sel = $inForm["comiket_customer_cd_sel"]; // デフォルト:使用する
//        } else {
//            $outForm->raw_comiket_customer_cd_sel = $inForm["comiket_customer_cd_sel"];
//        }

        // 顧客コード
        $outForm->raw_comiket_customer_cd = $inForm["comiket_customer_cd"];

        // 顧客名(法人)
        $outForm->raw_office_name = $inForm["office_name"];

        // 顧客名(個人)
        $outForm->raw_comiket_personal_name_sei = $inForm["comiket_personal_name_sei"];
        $outForm->raw_comiket_personal_name_mei = $inForm["comiket_personal_name_mei"];

        // 郵便番号1
        $outForm->raw_comiket_zip1 = $inForm["comiket_zip1"];

        // 郵便番号2
        $outForm->raw_comiket_zip2 = $inForm["comiket_zip2"];

        // 都道府県名
        $outForm->raw_comiket_pref_nm = "";
        if(@!empty($inForm["comiket_pref_cd_sel"])) {
            $prefInfo = $this->_PrefectureService->fetchPrefecturesById($db, $inForm["comiket_pref_cd_sel"]);
            $outForm->raw_comiket_pref_nm = $prefInfo["name"];
        }
        $prefectureAry = $this->_PrefectureService->fetchPrefectures($db);
        array_shift($prefectureAry["ids"]);
        array_shift($prefectureAry["names"]);
        $outForm->raw_comiket_pref_cds  = $prefectureAry["ids"];
        $outForm->raw_comiket_pref_lbls = $prefectureAry["names"];
        $outForm->raw_comiket_pref_cd_sel = $inForm["comiket_pref_cd_sel"];

        // 市区町村
        $outForm->raw_comiket_address = $inForm["comiket_address"];

        // 番地・建物名
        $outForm->raw_comiket_building = $inForm["comiket_building"];

        // 電話番号
        $outForm->raw_comiket_tel = $inForm["comiket_tel"];

        // メールアドレス
        $outForm->raw_comiket_mail = $inForm["comiket_mail"];

        // メールアドレス確認
        $outForm->raw_comiket_mail_retype = $inForm["comiket_mail_retype"];

        ///////////////////////////////////////////////////////////////////////////////////
        // ブース・館 関連
        /////////////////////////////////////////////////////////////////////////////////////
        // ブース名-テキスト
        $outForm->raw_comiket_booth_name = $inForm["comiket_booth_name"];

        // 館名
        $buildingNameInfoAry = $this->_BuildingService->fetchBuildingNameByEventsubId($db, $inForm["eventsub_sel"]);
        $outForm->raw_building_name = $inForm["building_name"]; // 編集画面のラベル表示用
        $outForm->raw_building_name_sel = $inForm["building_name_sel"];
        $outForm->raw_building_name_ids = $buildingNameInfoAry['ids'];
        $outForm->raw_building_name_lbls = $buildingNameInfoAry['names'];

        // ブース位置
        $buildingBoothPostionInfoAry = $this->_BuildingService->fetchBuildingBoothPostionByBuildingCd($db, $inForm["building_name_sel"], $inForm["eventsub_sel"]);
        $outForm->raw_building_booth_position = $inForm["building_booth_position"]; // 編集画面のラベル表示用
        $outForm->raw_building_booth_position_sel = $inForm["building_booth_position_sel"];
        $outForm->raw_building_booth_position_ids = $buildingBoothPostionInfoAry['ids'];
        $outForm->raw_building_booth_position_lbls = $buildingBoothPostionInfoAry['names'];

//        // ブース番号
//        $buildingList = $this->_BuildingService->fetchBuildingByEventId($db, $inForm["event_sel"]);
//        $outForm->raw_building_booth_ids = $buildingList["ids"];
//        $outForm->raw_building_booth_lbls = $buildingList["names"];
//        $outForm->raw_building_booth_id_sel = $inForm["building_booth_id_sel"];

        // ブース番号-テキスト
        $outForm->raw_comiket_booth_num = $inForm["comiket_booth_num"];

        // 担当者名
        $outForm->raw_comiket_staff_sei = $inForm["comiket_staff_sei"];
        $outForm->raw_comiket_staff_mei = $inForm["comiket_staff_mei"];

        // 担当者名-フリガナ
        $outForm->raw_comiket_staff_sei_furi = $inForm["comiket_staff_sei_furi"];
        $outForm->raw_comiket_staff_mei_furi = $inForm["comiket_staff_mei_furi"];

        // 担当者電話番号
        $outForm->raw_comiket_staff_tel = $inForm["comiket_staff_tel"];

        // 搬入選択
        $outForm->raw_comiket_detail_type_sel = $inForm["comiket_detail_type_sel"];
        $dispItemInfo["comiket_detail_type_lbls"] = $this->comiket_detail_type_lbls;

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 搬入・搬出共通
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // 搬入、搬出-サービス選択
        $dispItemInfo["comiket_detail_service_lbls"] = $this->comiket_detail_service_lbls;

        // 搬入、搬出-各種サービス
        $dispItemInfo["outbound_box_lbls"] = array();
        $dispItemInfo["inbound_box_lbls"] = array();
        if($inForm["comiket_detail_type_sel"] == "3") { // 搬入と搬出
            $dispItemInfo["outbound_box_lbls"] = $this->_BoxService->fetchBox2($db, $inForm["eventsub_sel"], $inForm["comiket_div"], "1"); // 搬入
            $dispItemInfo["inbound_box_lbls"] = $this->_BoxService->fetchBox2($db, $inForm["eventsub_sel"], $inForm["comiket_div"], "2"); // 搬出
        } else if($inForm["comiket_detail_type_sel"] == "1") { // 搬入
            $dispItemInfo["outbound_box_lbls"] = $this->_BoxService->fetchBox2($db, $inForm["eventsub_sel"], $inForm["comiket_div"], "1"); // 搬入
        } else if($inForm["comiket_detail_type_sel"] == "2") { // 搬出
            $dispItemInfo["inbound_box_lbls"] = $this->_BoxService->fetchBox2($db, $inForm["eventsub_sel"], $inForm["comiket_div"], "2"); // 搬出
        }

//        if($inForm["eventsub_sel"] == "11") { // コミケの場合
//            foreach($dispItemInfo["box_lbls"] as $key => $val) {
//                $dispItemInfo["box_lbls"][$key]["name"] = "";
//            }
//        }
//Sgmov_Component_Log::debug("################## 305");
//Sgmov_Component_Log::debug($dispItemInfo);
        $dispItemInfo["cargo_lbls"] = $this->_CargoService->fetchCargo($db);
        $dispItemInfo["charter_lbls"] = $this->_CharterService->fetchCharter($db);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 搬入
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // 搬入-集荷先名
        $outForm->raw_comiket_detail_outbound_name = $inForm["comiket_detail_outbound_name"];

        // 搬入-集荷先郵便番号1
        $outForm->raw_comiket_detail_outbound_zip1 = $inForm["comiket_detail_outbound_zip1"];

        // 搬入-集荷先郵便番号2
        $outForm->raw_comiket_detail_outbound_zip2 = $inForm["comiket_detail_outbound_zip2"];

        // 搬入-集荷先都道府県
        $outForm->raw_comiket_detail_outbound_pref_cds  = $prefectureAry["ids"];
        $outForm->raw_comiket_detail_outbound_pref_lbls = $prefectureAry["names"];
        $outForm->raw_comiket_detail_outbound_pref_cd_sel = $inForm["comiket_detail_outbound_pref_cd_sel"];

        // 搬入-集荷先市区町村
        $outForm->raw_comiket_detail_outbound_address =  $inForm["comiket_detail_outbound_address"];

        // 搬入-集荷先番地・建物名
        $outForm->raw_comiket_detail_outbound_building =  $inForm["comiket_detail_outbound_building"];

        // 搬入-集荷先TEL
        $outForm->raw_comiket_detail_outbound_tel =  $inForm["comiket_detail_outbound_tel"];

        $date = new DateTime();
        $years  = $this->_appCommon->getYears($date->format('Y'), 1, false);
        $months = $this->_appCommon->months;
        $days   = $this->_appCommon->days;
        array_shift($months);
        array_shift($days);

//        $dispItemInfo[""]
        // 搬入-お預かり日時
        $outForm->raw_comiket_detail_outbound_collect_date_year_sel = $inForm["comiket_detail_outbound_collect_date_year_sel"];
        $outForm->raw_comiket_detail_outbound_collect_date_year_cds = $years;
        $outForm->raw_comiket_detail_outbound_collect_date_year_lbls = $years;
        $outForm->raw_comiket_detail_outbound_collect_date_month_sel = $inForm["comiket_detail_outbound_collect_date_month_sel"];
        $outForm->raw_comiket_detail_outbound_collect_date_month_cds = $months;
        $outForm->raw_comiket_detail_outbound_collect_date_month_lbls = $months;
        $outForm->raw_comiket_detail_outbound_collect_date_day_sel = $inForm["comiket_detail_outbound_collect_date_day_sel"];
        $outForm->raw_comiket_detail_outbound_collect_date_day_cds = $days;
        $outForm->raw_comiket_detail_outbound_collect_date_day_lbls = $days;
        // 搬入-お預かり日時-時間帯
        $comiket_detail_time_lbls = $this->comiket_detail_time_lbls;
        $outForm->raw_comiket_detail_outbound_collect_time_sel = $inForm["comiket_detail_outbound_collect_time_sel"];
        $outForm->raw_comiket_detail_outbound_collect_time_cds = array_keys($comiket_detail_time_lbls);
        $outForm->raw_comiket_detail_outbound_collect_time_lbls = array_values($comiket_detail_time_lbls);

        // 搬入-お届け日時
        $comiket_detail_time_lbls_par30m = $this->comiket_detail_time_lbls_par30m;
        $outForm->raw_comiket_detail_outbound_delivery_date_year_sel = $inForm["comiket_detail_outbound_delivery_date_year_sel"];
        $outForm->raw_comiket_detail_outbound_delivery_date_year_cds = $years;
        $outForm->raw_comiket_detail_outbound_delivery_date_year_lbls = $years;
        $outForm->raw_comiket_detail_outbound_delivery_date_month_sel = $inForm["comiket_detail_outbound_delivery_date_month_sel"];
        $outForm->raw_comiket_detail_outbound_delivery_date_month_cds = $months;
        $outForm->raw_comiket_detail_outbound_delivery_date_month_lbls = $months;
        $outForm->raw_comiket_detail_outbound_delivery_date_day_sel = $inForm["comiket_detail_outbound_delivery_date_day_sel"];
        $outForm->raw_comiket_detail_outbound_delivery_date_day_cds = $days;
        $outForm->raw_comiket_detail_outbound_delivery_date_day_lbls = $days;
        // 搬入-お届け日時-時間帯
        $outForm->raw_comiket_detail_outbound_delivery_time_sel = $inForm["comiket_detail_outbound_delivery_time_sel"];
        $outForm->raw_comiket_detail_outbound_delivery_time_cds = array_keys($comiket_detail_time_lbls_par30m);
        $outForm->raw_comiket_detail_outbound_delivery_time_lbls = array_values($comiket_detail_time_lbls_par30m);

        // 搬入-サービス選択
        $outForm->raw_comiket_detail_outbound_service_sel = $inForm["comiket_detail_outbound_service_sel"];

        // 搬入-宅配
        $outForm->raw_comiket_box_outbound_num_ary = $inForm["comiket_box_outbound_num_ary"];

        // 搬入-カーゴ
//        $outForm->raw_comiket_cargo_outbound_num_ary = $inForm["comiket_cargo_outbound_num_ary"];
        $outForm->raw_comiket_cargo_outbound_num_sel = $inForm["comiket_cargo_outbound_num_sel"];
        $outForm->raw_comiket_cargo_outbound_num_cds = array_keys($this->comiket_cargo_item_list);
        $outForm->raw_comiket_cargo_outbound_num_lbls = array_values($this->comiket_cargo_item_list);

        // 搬入-チャーター
        $outForm->raw_comiket_charter_outbound_num_ary = $inForm["comiket_charter_outbound_num_ary"];

        // 搬入-備考
//        $outForm->raw_comiket_detail_outbound_note = $inForm["comiket_detail_outbound_note"];

        // 搬入-備考-1行目
        $outForm->raw_comiket_detail_outbound_note1 = $inForm["comiket_detail_outbound_note1"];

        // 搬入-備考-2行目
        $outForm->raw_comiket_detail_outbound_note2 = $inForm["comiket_detail_outbound_note2"];

        // 搬入-備考-3行目
        $outForm->raw_comiket_detail_outbound_note3 = $inForm["comiket_detail_outbound_note3"];

        // 搬入-備考-4行目
        $outForm->raw_comiket_detail_outbound_note4 = $inForm["comiket_detail_outbound_note4"];

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 搬出
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // 搬出-集荷先名
        $outForm->raw_comiket_detail_inbound_name = $inForm["comiket_detail_inbound_name"];

        // 搬出-集荷先郵便番号1
        $outForm->raw_comiket_detail_inbound_zip1 = $inForm["comiket_detail_inbound_zip1"];

        // 搬出-集荷先郵便番号2
        $outForm->raw_comiket_detail_inbound_zip2 = $inForm["comiket_detail_inbound_zip2"];

        // 搬出-集荷先都道府県
        $outForm->raw_comiket_detail_inbound_pref_cds  = $prefectureAry["ids"];
        $outForm->raw_comiket_detail_inbound_pref_lbls = $prefectureAry["names"];
        $outForm->raw_comiket_detail_inbound_pref_cd_sel = $inForm["comiket_detail_inbound_pref_cd_sel"];

        // 搬出-集荷先市区町村
        $outForm->raw_comiket_detail_inbound_address =  $inForm["comiket_detail_inbound_address"];

        // 搬出-集荷先番地・建物名
        $outForm->raw_comiket_detail_inbound_building =  $inForm["comiket_detail_inbound_building"];

        // 搬出-集荷先TEL
        $outForm->raw_comiket_detail_inbound_tel =  $inForm["comiket_detail_inbound_tel"];

        // 搬出-お預かり日時
        $outForm->raw_comiket_detail_inbound_collect_date_year_sel = $inForm["comiket_detail_inbound_collect_date_year_sel"];
        $outForm->raw_comiket_detail_inbound_collect_date_year_cds = $years;
        $outForm->raw_comiket_detail_inbound_collect_date_year_lbls = $years;
        $outForm->raw_comiket_detail_inbound_collect_date_month_sel = $inForm["comiket_detail_inbound_collect_date_month_sel"];
        $outForm->raw_comiket_detail_inbound_collect_date_month_cds = $months;
        $outForm->raw_comiket_detail_inbound_collect_date_month_lbls = $months;
        $outForm->raw_comiket_detail_inbound_collect_date_day_sel = $inForm["comiket_detail_inbound_collect_date_day_sel"];
        $outForm->raw_comiket_detail_inbound_collect_date_day_cds = $days;
        $outForm->raw_comiket_detail_inbound_collect_date_day_lbls = $days;
        // 搬出-お預かり日時-時間帯
        $outForm->raw_comiket_detail_inbound_collect_time_sel = $inForm["comiket_detail_inbound_collect_time_sel"];
        $outForm->raw_comiket_detail_inbound_collect_time_cds = array_keys($comiket_detail_time_lbls_par30m);
        $outForm->raw_comiket_detail_inbound_collect_time_lbls = array_values($comiket_detail_time_lbls_par30m);

        // 搬出-お届け日時
        $outForm->raw_comiket_detail_inbound_delivery_date_year_sel = $inForm["comiket_detail_inbound_delivery_date_year_sel"];
        $outForm->raw_comiket_detail_inbound_delivery_date_year_cds = $years;
        $outForm->raw_comiket_detail_inbound_delivery_date_year_lbls = $years;
        $outForm->raw_comiket_detail_inbound_delivery_date_month_sel = $inForm["comiket_detail_inbound_delivery_date_month_sel"];
        $outForm->raw_comiket_detail_inbound_delivery_date_month_cds = $months;
        $outForm->raw_comiket_detail_inbound_delivery_date_month_lbls = $months;
        $outForm->raw_comiket_detail_inbound_delivery_date_day_sel = $inForm["comiket_detail_inbound_delivery_date_day_sel"];
        $outForm->raw_comiket_detail_inbound_delivery_date_day_cds = $days;
        $outForm->raw_comiket_detail_inbound_delivery_date_day_lbls = $days;

        // 時間帯マスタからデータを取得
        $timeDataList = $this->_TimeService->fetchTimeDataList($db);

        foreach ($timeDataList as $timeData) {
            $this->comiket_detail_delivery_timezone[$timeData['cd'] .','. $timeData['name']] = $timeData['name'];
        }

        // 搬出-お届け日時-時間帯
        $outForm->raw_comiket_detail_inbound_delivery_time_sel = $inForm["comiket_detail_inbound_delivery_time_sel"];
        $outForm->raw_comiket_detail_inbound_delivery_time_cds = array_keys($this->comiket_detail_delivery_timezone);
        $outForm->raw_comiket_detail_inbound_delivery_time_lbls = array_values($this->comiket_detail_delivery_timezone);

        // 搬出-サービス選択
        $outForm->raw_comiket_detail_inbound_service_sel = $inForm["comiket_detail_inbound_service_sel"];
//Sgmov_Component_Log::debug("################ 122");
//Sgmov_Component_Log::debug($inForm);

        // 搬出-宅配
        $outForm->raw_comiket_box_inbound_num_ary = $inForm["comiket_box_inbound_num_ary"];

        // 搬出-カーゴ
//        $outForm->raw_comiket_cargo_inbound_num_ary = $inForm["comiket_cargo_inbound_num_ary"];
        $outForm->raw_comiket_cargo_inbound_num_sel = $inForm["comiket_cargo_inbound_num_sel"];
        $outForm->raw_comiket_cargo_inbound_num_cds = array_keys($this->comiket_cargo_item_list);
        $outForm->raw_comiket_cargo_inbound_num_lbls = array_values($this->comiket_cargo_item_list);

        // 搬出-チャーター
        $outForm->raw_comiket_charter_inbound_num_ary = $inForm["comiket_charter_inbound_num_ary"];

        // 搬出-備考
//        $outForm->raw_comiket_detail_inbound_note = $inForm["comiket_detail_inbound_note"];

        // 搬入-備考-1行目
        $outForm->raw_comiket_detail_inbound_note1 = $inForm["comiket_detail_inbound_note1"];

        // 搬入-備考-2行目
        $outForm->raw_comiket_detail_inbound_note2 = $inForm["comiket_detail_inbound_note2"];

        // 搬入-備考-3行目
        $outForm->raw_comiket_detail_inbound_note3 = $inForm["comiket_detail_inbound_note3"];

        // 搬入-備考-4行目
        $outForm->raw_comiket_detail_inbound_note4 = $inForm["comiket_detail_inbound_note4"];

/////////////////////////////////////////////////////////////////////////////////////////////////////////
// 支払
/////////////////////////////////////////////////////////////////////////////////////////////////////////
        // 送料
        $outForm->raw_delivery_charge = $inForm["delivery_charge"];

        // リピータ割引
        $outForm->raw_repeater_discount = $inForm["repeater_discount"];

        // お支払方法コード選択値
Sgmov_Component_Log::debug("######################## 901");
Sgmov_Component_Log::debug($inForm);
        $outForm->raw_comiket_payment_method_cd_sel = $inForm["comiket_payment_method_cd_sel"];

        // お支払店コード選択値
        $outForm->raw_comiket_convenience_store_cd_sel = $inForm["comiket_convenience_store_cd_sel"];

        // お支払店コードリスト
        $outForm->raw_comiket_convenience_store_cds = array_keys($this->convenience_store_lbls);

        // お支払店コードラベルリスト
        $outForm->raw_comiket_convenience_store_lbls = array_values($this->convenience_store_lbls);

        // クレジットカード番号
        $outForm->raw_card_number = $inForm["card_number"];

        // 有効期限 月
        $outForm->raw_card_expire_month_cd_sel = $inForm["card_expire_month_cd_sel"];

        // 有効期限 年
        $outForm->raw_card_expire_year_cd_sel = $inForm["card_expire_year_cd_sel"];

        // セキュリティコード
        $outForm->raw_security_cd = $inForm["security_cd"];

        $outForm->raw_delivery_charge = $inForm["delivery_charge"];

//        $eventEndFlg = '0';
//        if (new DateTime() > strtotime($eventsubAry2[0]['arrival_to_time'])) {
//            $eventEndFlg = '1';
//        }
Sgmov_Component_Log::debug("######################## 901");
Sgmov_Component_Log::debug($outForm);
        return array("outForm" => $outForm
                , "dispItemInfo" => $dispItemInfo
//                , "$eventEndFlg" => $eventEndFlg
            );
    }

    public function _getAddress($zip, $address) {
//        $zip = $inForm->zip1 . $inForm->zip2;
//        $address = $prefectures['names'][array_search($inForm->pref_cd_sel, $prefectures['ids'])] . $inForm->address . $inForm->building;
        return $this->_SocketZipCodeDll->searchByAddress($address, $zip);
    }

    /**
     *
     * @param type $inForm
     */
    public function setYubinDllInfoToInForm(&$inForm) {
        $db = Sgmov_Component_DB::getPublic();
//Sgmov_Component_Log::debug("##################################### 310 common");
//Sgmov_Component_Log::debug($inForm["eventsub_sel"]);
        $eventsubData = $this->_EventsubService->fetchEventsubByEventsubId($db, $inForm["eventsub_sel"]);
//Sgmov_Component_Log::debug("##################################### 312 common");
//Sgmov_Component_Log::debug($eventsubData);

//        $resultEventZipDll = $this->_HttpsZipCodeDll->_execYubin7(array(
//            "szZipCode" => @$eventsubData["zip"],
//            "szAddress" => @$eventsubData["address"],
//            "szTel" => "",
//        ));

        $resultEventZipDll = $this->_getAddress(@$eventsubData["zip"], @$eventsubData["address"]);

        if($inForm['comiket_detail_type_sel'] == "1"
                || $inForm['comiket_detail_type_sel'] == "3" ) { // 搬入（もしくは搬入搬出）
            // 搬入 /////////////////////////////////////////////

//            $resultOutboundPrefData = array();
//            if(!empty($inForm['comiket_detail_outbound_pref_cd_sel'])) {

                $resultOutboundPrefData = $this->_PrefectureService->fetchPrefecturesById($db, $inForm['comiket_detail_outbound_pref_cd_sel']);

//                $resultOutboundHatsuZipDll = $this->_HttpsZipCodeDll->_execYubin7(array(
//                    "szZipCode" => @$inForm['comiket_detail_outbound_zip1'] . @$inForm['comiket_detail_outbound_zip2'],
//                    "szAddress" => @$resultOutboundPrefData["name"] . @$inForm['comiket_detail_outbound_address'] . @$inForm['comiket_detail_outbound_building'],
//                    "szTel" => @$inForm['comiket_detail_outbound_tel'],
//                ));

                $resultOutboundHatsuZipDll = $this->_getAddress(@$inForm['comiket_detail_outbound_zip1'] . @$inForm['comiket_detail_outbound_zip2']
                        , @$resultOutboundPrefData["name"] . @$inForm['comiket_detail_outbound_address'] . @$inForm['comiket_detail_outbound_building']);

                $inForm["outbound_hatsu_jis2code"] = @$resultOutboundHatsuZipDll["JIS2Code"];
                $inForm["outbound_hatsu_jis5code"] = @$resultOutboundHatsuZipDll["JIS5Code"];
                $inForm["outbound_hatsu_shop_check_code"] = @$resultOutboundHatsuZipDll["ShopCheckCode"];
                $inForm["outbound_hatsu_shop_check_code_eda"] = @$resultOutboundHatsuZipDll["ShopCheckCodeEda"];
                $inForm["outbound_hatsu_shop_code"] = @$resultOutboundHatsuZipDll["ShopCode"];
                $inForm["outbound_hatsu_shop_local_code"] = @$resultOutboundHatsuZipDll["ShopLocalCode"];

                $inForm["outbound_chaku_jis2code"] = @$resultEventZipDll["JIS2Code"];
                $inForm["outbound_chaku_jis5code"] = @$resultEventZipDll["JIS5Code"];
                $inForm["outbound_chaku_shop_check_code"] = @$resultEventZipDll["ShopCheckCode"];
                $inForm["outbound_chaku_shop_check_code_eda"] = @$resultEventZipDll["ShopCheckCodeEda"];
                $inForm["outbound_chaku_shop_code"] = @$resultEventZipDll["ShopCode"];
                $inForm["outbound_chaku_shop_local_code"] = @$resultEventZipDll["ShopLocalCode"];
//            }
        }

        // 搬出 /////////////////////////////////////////////
        if($inForm['comiket_detail_type_sel'] == "2"
                || $inForm['comiket_detail_type_sel'] == "3" ) { // 搬出（もしくは搬入搬出）
            // 搬出 /////////////////////////////////////////////
            $inForm["inbound_hatsu_jis2code"] = @$resultEventZipDll["JIS2Code"];
            $inForm["inbound_hatsu_jis5code"] = @$resultEventZipDll["JIS5Code"];
            $inForm["inbound_hatsu_shop_check_code"] = @$resultEventZipDll["ShopCheckCode"];
            $inForm["inbound_hatsu_shop_check_code_eda"] = @$resultEventZipDll["ShopCheckCodeEda"];
            $inForm["inbound_hatsu_shop_code"] = @$resultEventZipDll["ShopCode"];
            $inForm["inbound_hatsu_shop_local_code"] = @$resultEventZipDll["ShopLocalCode"];


            $resultInboundPrefData = $this->_PrefectureService->fetchPrefecturesById($db, $inForm['comiket_detail_inbound_pref_cd_sel']);

//            $resultInboundZipDll = $this->_HttpsZipCodeDll->_execYubin7(array(
//                "szZipCode" => @$inForm['comiket_detail_inbound_zip1'] . @$inForm['comiket_detail_inbound_zip2'],
//                "szAddress" => @$resultInboundPrefData["name"] . @$inForm['comiket_detail_inbound_address'] . @$inForm['comiket_detail_inbound_building'],
//                "szTel" => @$inForm['comiket_detail_inbound_tel'],
//            ));

            $resultInboundZipDll = $this->_getAddress(@$inForm['comiket_detail_inbound_zip1'] . @$inForm['comiket_detail_inbound_zip2']
                    , @$resultInboundPrefData["name"] . @$inForm['comiket_detail_inbound_address'] . @$inForm['comiket_detail_inbound_building']);

            $inForm["inbound_chaku_jis2code"] = @$resultInboundZipDll["JIS2Code"];
            $inForm["inbound_chaku_jis5code"] = @$resultInboundZipDll["JIS5Code"];
            $inForm["inbound_chaku_shop_check_code"] = @$resultInboundZipDll["ShopCheckCode"];
            $inForm["inbound_chaku_shop_check_code_eda"] = @$resultInboundZipDll["ShopCheckCodeEda"];
            $inForm["inbound_chaku_shop_code"] = @$resultInboundZipDll["ShopCode"];
            $inForm["inbound_chaku_shop_local_code"] = @$resultInboundZipDll["ShopLocalCode"];
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    }

    protected function setInboundYubinDllInfoToInForm(&$inForm) {
    }

    /**
     *
     * @param type $inForm
     */
    protected function calcEveryKindData($inForm, $comiketId = "") {
Sgmov_Component_Log::debug("####################### 701-2-start");
        /////////////////////////////////////////////////////////////////////////////////
        // 送料計算
        /////////////////////////////////////////////////////////////////////////////////

//
//        $resultArray = array();
//        if($inForm['comiket_detail_type_sel'] == "1") {
//            $resultArray["comiket_detail_list"][] = array();
//        }

        $fareTaxTotal = 0;

//        $fareTaxTotalOutbound = 0;
//        $fareTaxTotalInbound = 0;
        // DB接続
        $db = Sgmov_Component_DB::getPublic();

        $this->setYubinDllInfoToInForm($inForm);

        $tableDataInfo = $this->_cmbTableDataFromInForm($inForm, $comiketId);
        $tableTreeData = $tableDataInfo["treeData"];
        $tableTreeData['amount_tax'] = 0;
        $tableTreeData['amount'] = 0;

        //個人 Or 法人の電子決済
        /////////////////////////////////////////////////////////////////////////////////
        // 宅配BOX
        /////////////////////////////////////////////////////////////////////////////////
        $costAmountTaxOutboundTotal2 = 0;
        $fareAmountTaxOutboundTotal2 = 0;
        if($inForm['comiket_detail_type_sel'] == "1"
                || $inForm['comiket_detail_type_sel'] == "3" ) { // 搬入（もしくは搬入搬出）
            if($inForm['comiket_detail_outbound_service_sel'] == "1") { // 宅配
                 // 搬入 ///////////////////////////////////////////////////////////

                    foreach($tableTreeData["comiket_detail_list"] as $keyDet => $valDet) {
                        if($valDet['type'] == "1" || $valDet['type'] == "3") {
                            $costAmountTaxOutboundTotal = 0;
                            $fareAmountOutboundTotal = 0;
                            $tableTreeData["comiket_detail_list"][$keyDet]['cost'] = 0;
                            $tableTreeData["comiket_detail_list"][$keyDet]['cost_tax'] = 0;
                            $tableTreeData["comiket_detail_list"][$keyDet]['fare'] = 0;
                            $tableTreeData["comiket_detail_list"][$keyDet]['fare_tax'] = 0;

                            if(!empty($valDet["comiket_box_list"])) {
                                foreach($valDet["comiket_box_list"] as $keyComiketBox => $valComiketBox) {
                                    if($valComiketBox['type'] == "1" || $valComiketBox['type'] == "3") {
                                        ///////////////////////////////////////////////////////
                                        // 料金計算(孫) 【comiket_box】
                                        ///////////////////////////////////////////////////////
    //                                    $boxInfo = $this->_BoxService->fetchBoxById($db, $key);
                                        $boxInfo = $this->_BoxService->fetchBoxById($db, $valComiketBox['box_id']);
                                        $boxFareData = $this->_BoxFareService->fetchBoxFareByJis2AndBoxId($db, $inForm["outbound_hatsu_jis2code"], $inForm["outbound_chaku_jis2code"], $boxInfo["cd"], $tableTreeData["eventsub_id"]);

                                        if(!empty($boxInfo)) {
                                            // 保管料金（税込）
                                            $costAmountTaxOutboundTotal += intval($boxInfo['cost_tax']) * intval($valComiketBox['num']);
                                            $tableTreeData["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["cost_price_tax"] = intval($boxInfo['cost_tax']);
                                            $tableTreeData["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["cost_amount_tax"] = intval($boxInfo['cost_tax']) * intval($valComiketBox['num']);
                                        }

                                        if(!empty($boxFareData)) {
                                            // 運賃（税抜）
                                            // TODO fare ? fare_tax
                                            $fareAmountOutboundTotal += intval($boxFareData['fare']) * intval($valComiketBox['num']);
                                            $tableTreeData["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["fare_price"] = intval($boxFareData['fare']);
                                            $tableTreeData["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["fare_amount"] = intval($boxFareData['fare']) * intval($valComiketBox['num']);
                                        }
                                    }
                                }
                            }
                            ///////////////////////////////////////////////////////
                            // 料金計算(子) 【comiket_detail】
                            ///////////////////////////////////////////////////////
                            // 税抜
                            $tableTreeData["comiket_detail_list"][$keyDet]['fare'] = $fareAmountOutboundTotal;
                            $tableTreeData["comiket_detail_list"][$keyDet]['cost'] = ceil($costAmountTaxOutboundTotal / Sgmov_View_Eve_Common::CURRENT_TAX);

                            // 税込
                            $tableTreeData["comiket_detail_list"][$keyDet]['cost_tax'] = $costAmountTaxOutboundTotal;
                            $costAmountTaxOutboundTotal2 += $costAmountTaxOutboundTotal;

                            $tableTreeData["comiket_detail_list"][$keyDet]['fare_tax'] = floor($fareAmountOutboundTotal * Sgmov_View_Eve_Common::CURRENT_TAX);
                            $fareAmountTaxOutboundTotal2 += $tableTreeData["comiket_detail_list"][$keyDet]['fare_tax'];

Sgmov_Component_Log::debug("####################### 701-2");
Sgmov_Component_Log::debug($costAmountTaxOutboundTotal2);
Sgmov_Component_Log::debug($fareAmountTaxOutboundTotal2);
//$inForm['comiket_box_outbound_num_ary']
//Sgmov_Component_Log::debug($valDet["comiket_box_list"]);
                        }
                    }
//                }
            }
        }


        $fareAmountTaxInboundTotal2 = 0;
        if($inForm['comiket_detail_type_sel'] == "2"
                || $inForm['comiket_detail_type_sel'] == "3" ) { // 搬出（もしくは搬入搬出）
            if($inForm['comiket_detail_inbound_service_sel'] == "1") { // 宅配

//                foreach($inForm['comiket_box_inbound_num_ary'] as $key => $val) {
//
//                    if(empty($val)) {
//                        continue;
//                    }

                    foreach($tableTreeData["comiket_detail_list"] as $keyDet => $valDet) {

                        if($valDet['type'] == "2" || $valDet['type'] == "3") {
                            $fareAmountInboundTotal = 0;
                            $tableTreeData["comiket_detail_list"][$keyDet]['cost'] = 0;
                            $tableTreeData["comiket_detail_list"][$keyDet]['cost_tax'] = 0;
                            $tableTreeData["comiket_detail_list"][$keyDet]['fare'] = 0;
                            $tableTreeData["comiket_detail_list"][$keyDet]['fare_tax'] = 0;

                            if(!empty($valDet["comiket_box_list"])) {
                                foreach($valDet["comiket_box_list"] as $keyComiketBox => $valComiketBox) {
                                    if($valComiketBox['type'] == "2" || $valComiketBox['type'] == "3") { // 搬出
                                        ///////////////////////////////////////////////////////
                                        // 料金計算(孫) 【comiket_box】
                                        ///////////////////////////////////////////////////////
    //                                    $boxInfo = $this->_BoxService->fetchBoxById($db, $key);
                                        $boxInfo = $this->_BoxService->fetchBoxById($db, $valComiketBox['box_id']);
    //Sgmov_Component_Log::debug("############# 151-1");
                                        $boxFareData = $this->_BoxFareService->fetchBoxFareByJis2AndBoxId($db, $inForm["inbound_hatsu_jis2code"], $inForm["inbound_chaku_jis2code"], $boxInfo["cd"],  $tableTreeData["eventsub_id"]);
    //Sgmov_Component_Log::debug("############# 151-2");
    //Sgmov_Component_Log::debug($boxFareData);
    //Sgmov_Component_Log::debug($inForm);
                                        if(!empty($boxFareData)) {
                                            // ●運賃（税抜）
                                            $fareAmountInboundTotal += intval($boxFareData['fare']) * intval($valComiketBox["num"]);
                                            $tableTreeData["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["fare_price"] = intval($boxFareData['fare']);
                                            $tableTreeData["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["fare_amount"] = intval($boxFareData['fare']) * intval($valComiketBox["num"]);
                                        }
                                    }
                                }
                            }

                            ///////////////////////////////////////////////////////
                            // 料金計算(子) 【comiket_detail】
                            ///////////////////////////////////////////////////////
                            $tableTreeData["comiket_detail_list"][$keyDet]['fare'] = $fareAmountInboundTotal;
                            $tableTreeData["comiket_detail_list"][$keyDet]['fare_tax'] = floor($fareAmountInboundTotal * Sgmov_View_Eve_Common::CURRENT_TAX);

                            $fareAmountTaxInboundTotal2 += $tableTreeData["comiket_detail_list"][$keyDet]['fare_tax'];
                        }
                    }
//                }
            }
        }
        $tableTreeData['amount_tax'] = $tableTreeData['amount'] = 0;
        $boxAmoutTaxTotal = 0;
        if(!empty($costAmountTaxOutboundTotal2) || !empty($fareAmountTaxOutboundTotal2)) {
            $boxAmoutTaxTotal += $costAmountTaxOutboundTotal2 + $fareAmountTaxOutboundTotal2;
//            $tableTreeData['amount'] = ceil(($fareAmountTaxOutboundTotal + $fareAmountTaxInboundTotal + $costAmountTaxOutboundTotal2) / Sgmov_View_Eve_Common::CURRENT_TAX);
        }
//Sgmov_Component_Log::debug("####################### 701-3");
//Sgmov_Component_Log::debug($costAmountTaxOutboundTotal2);
//Sgmov_Component_Log::debug($fareAmountTaxOutboundTotal2);
//Sgmov_Component_Log::debug($boxAmoutTaxTotal);

        if(!empty($fareAmountTaxInboundTotal2)) {
            $boxAmoutTaxTotal += $fareAmountTaxInboundTotal2;
//            $tableTreeData['amount_tax'] = $fareAmountTaxOutboundTotal + $fareAmountTaxInboundTotal + $costAmountTaxOutboundTotal2;
//            $tableTreeData['amount'] = ceil(($fareAmountTaxOutboundTotal + $fareAmountTaxInboundTotal + $costAmountTaxOutboundTotal2) / Sgmov_View_Eve_Common::CURRENT_TAX);
        }
//Sgmov_Component_Log::debug("####################### 701-4");
//Sgmov_Component_Log::debug($boxAmoutTaxTotal);

        if(!empty($boxAmoutTaxTotal)) {
            $tableTreeData['amount_tax'] += $boxAmoutTaxTotal;
            $tableTreeData['amount'] += ceil($boxAmoutTaxTotal / Sgmov_View_Eve_Common::CURRENT_TAX);
        }
//Sgmov_Component_Log::debug("####################### 701-5");
//Sgmov_Component_Log::debug($boxAmoutTaxTotal);
//Sgmov_Component_Log::debug($tableTreeData);

        /////////////////////////////////////////////////////////////////////////////////
        // カーゴ
        /////////////////////////////////////////////////////////////////////////////////
Sgmov_Component_Log::debug("####################### 701");
Sgmov_Component_Log::debug($inForm);
//        if($inForm['comiket_div'] == self::COMIKET_DEV_BUSINESS) { // 法人
            $cargoFareTaxTotalOutbound = 0;
            if ($inForm['comiket_detail_type_sel'] == "1" || $inForm['comiket_detail_type_sel'] == "3") { // 搬入（もしくは搬入搬出）
                if ($inForm['comiket_detail_outbound_service_sel'] == "2") { // カーゴ
//Sgmov_Component_Log::debug("####################### 701-1");
                    foreach($tableTreeData["comiket_detail_list"] as $keyDet => $valDet) {
//                        $cargoFareTaxTotalOutbound = 0;
//                        $tableTreeData["comiket_detail_list"][$keyDet]['fare'] = $cargoFareTaxTotalOutbound;
//Sgmov_Component_Log::debug("####################### 701-2");
                        if($valDet['type'] == "1" || $valDet['type'] == "3") {
//Sgmov_Component_Log::debug("####################### 701-3");
                            if(!empty($valDet["comiket_cargo_list"])) {
                                foreach($valDet["comiket_cargo_list"] as $keyComiketCargo => $valComiketCargo) {
    //Sgmov_Component_Log::debug("####################### 701-4");
                                    ///////////////////////////////////////////////////////
                                    // 料金計算(孫) 【comiket_cargo】
                                    ///////////////////////////////////////////////////////
                                    $cargoFareData = $this->_CargoFareService->fetchCargoFareByJis2AndCargoNum(
                                            $db, $inForm["outbound_hatsu_jis2code"], $inForm["outbound_chaku_jis2code"], $inForm["comiket_cargo_outbound_num_sel"], $tableTreeData["eventsub_id"]); // 13は東京(jis2code)

                                    if(isset($cargoFareData["cargo_fare"]) && !empty($cargoFareData["cargo_fare"])) {
                                        $fareAmountTax = intval($cargoFareData["cargo_fare"]);
                                    } else {
                                        $fareAmountTax = 0;
                                    }
                                    $cargoFareTaxTotalOutbound+=$fareAmountTax;
    //Sgmov_Component_Log::debug("####################### 701-5");
    //Sgmov_Component_Log::debug($fareAmountTax);
    //Sgmov_Component_Log::debug($cargoFareData);

                                    $tableTreeData["comiket_detail_list"][$keyDet]["comiket_cargo_list"][$keyComiketCargo]['fare_amount'] = $fareAmountTax;
                                }

                                $tableTreeData["comiket_detail_list"][$keyDet]['fare'] = $cargoFareTaxTotalOutbound;
                            }
                        }
                    }
                }
            }

            $cargoFareTaxTotalInbound = 0;
            if ($inForm['comiket_detail_type_sel'] == "2" || $inForm['comiket_detail_type_sel'] == "3") { // 搬出（もしくは搬入搬出）
                if ($inForm['comiket_detail_inbound_service_sel'] == "2") { // カーゴ
                    foreach($tableTreeData["comiket_detail_list"] as $keyDet => $valDet) {
//                        $cargoFareTaxTotalInbound = 0;
//                        $tableTreeData["comiket_detail_list"][$keyDet]['fare'] = $cargoFareTaxTotalInbound;

                        if($valDet['type'] == "2" || $valDet['type'] == "3") { // 搬出
                            if(!empty($valDet["comiket_cargo_list"])) {
                                foreach($valDet["comiket_cargo_list"] as $keyComiketCargo => $valComiketCargo) {
                                    ///////////////////////////////////////////////////////
                                    // 料金計算(孫) 【comiket_cargo】
                                    ///////////////////////////////////////////////////////
                                    $cargoFareData = $this->_CargoFareService->fetchCargoFareByJis2AndCargoNum(
                                            $db, $inForm["inbound_hatsu_jis2code"], $inForm["inbound_chaku_jis2code"], $inForm["comiket_cargo_inbound_num_sel"], $tableTreeData["eventsub_id"]); // 13は東京(jis2code)
                                    if(isset($cargoFareData["cargo_fare"]) && !empty($cargoFareData["cargo_fare"])) {
                                        $fareAmountTax = intval($cargoFareData["cargo_fare"]);
                                    } else {
                                        $fareAmountTax = 0;
                                    }
    //                                $fareTaxTotal+=$fareAmountTax;
                                    $cargoFareTaxTotalInbound += $fareAmountTax;

                                    $tableTreeData["comiket_detail_list"][$keyDet]["comiket_cargo_list"][$keyComiketCargo]['fare_amount'] = $fareAmountTax;
                                }
                                $tableTreeData["comiket_detail_list"][$keyDet]['fare'] = $cargoFareTaxTotalInbound;
                            }
                        }
                    }
                }
            }

//            if ($inForm['comiket_detail_outbound_service_sel'] == "2"
//                    || $inForm['comiket_detail_inbound_service_sel'] == "2" ) { // カーゴ
//                $tableTreeData['amount'] = $cargoFareTaxTotalOutbound + $cargoFareTaxTotalInbound;
//                $tableTreeData['amount_tax'] = floor(($cargoFareTaxTotalOutbound + $cargoFareTaxTotalInbound) * Sgmov_View_Eve_Common::CURRENT_TAX);
//            }

            $cargoAmountTotal = 0;
            if ($inForm['comiket_detail_outbound_service_sel'] == "2") { // 搬入-カーゴ
                $cargoAmountTotal += $cargoFareTaxTotalOutbound;
            }
            if ($inForm['comiket_detail_inbound_service_sel'] == "2" ) { // 搬出-カーゴ
                $cargoAmountTotal += $cargoFareTaxTotalInbound;
            }

            if(!empty($cargoAmountTotal)) {
                $tableTreeData['amount'] += $cargoAmountTotal;
                $tableTreeData['amount_tax'] += ceil($cargoAmountTotal * Sgmov_View_Eve_Common::CURRENT_TAX);
            }
//        }

        /////////////////////////////////////////////////////////////////////////////////
        // 貸切
        /////////////////////////////////////////////////////////////////////////////////
        if($inForm['comiket_div'] == self::COMIKET_DEV_BUSINESS) { // 法人
            $charterFareTaxTotalOutbound = 0;
            if($inForm['comiket_detail_type_sel'] == "1"
                    || $inForm['comiket_detail_type_sel'] == "3" ) { // 搬入（もしくは搬入搬出）
                if($inForm['comiket_detail_outbound_service_sel'] == "3") { // チャーター
                    $charterFareTaxTotalOutbound = 0;
                }
            }

            $charterFareTaxTotalInbound = 0;
            if($inForm['comiket_detail_type_sel'] == "2"
                    || $inForm['comiket_detail_type_sel'] == "3" ) { // 搬出（もしくは搬入搬出）
                if($inForm['comiket_detail_inbound_service_sel'] == "3") { // チャーター
                    $charterFareTaxTotalInbound = 0;
                }
            }

            if ($inForm['comiket_detail_outbound_service_sel'] == "3"
                    || $inForm['comiket_detail_inbound_service_sel'] == "3" ) { // チャーター
                $tableTreeData['amount'] += 0;
                $tableTreeData['amount_tax'] += 0;
            }
        }

//        Sgmov_Component_Log::debug("####################### 702");
        $comiketData = $tableTreeData;
        $comiketDetailDataList = $comiketData["comiket_detail_list"];
        $comiketBoxDataList = array();
        foreach($comiketDetailDataList as $key => $val) {
            if(isset($val["comiket_box_list"])) {
                foreach($val["comiket_box_list"] as $key2 => $val2) {
                    $comiketBoxDataList[] = $val2;
                }
            }
        }

        $comiketCargoDataList = array();
        foreach($comiketDetailDataList as $key => $val) {
            if(isset($val["comiket_cargo_list"])) {
                foreach($val["comiket_cargo_list"] as $key2 => $val2) {
                    $comiketCargoDataList[] = $val2;
                }
            }
        }

        $comiketCharterDataList = array();
        foreach($comiketDetailDataList as $key => $val) {
            if(isset($val["comiket_charter_list"])) {
                foreach($val["comiket_charter_list"] as $key2 => $val2) {
                    $comiketCharterDataList[] = $val2;
                }
            }
        }
Sgmov_Component_Log::debug("####################### 701-2-end");
        return array(
//            "fareTaxTotal" => $fareTaxTotal,
//            "fareTaxTotalOutbound" => $fareTaxTotalOutbound,
//            "fareTaxTotalInbound" => $fareTaxTotalInbound,
            "treeData" => $tableTreeData,
            "flatData" => array(
                "comiketData" => $comiketData,
                "comiketDetailDataList" => $comiketDetailDataList,
                "comiketBoxDataList" => $comiketBoxDataList,
                "comiketCargoDataList" => $comiketCargoDataList,
                "comiketCharterDataList" => $comiketCharterDataList,
            ),
        );

    }

    /**
     *
     * @param type $inForm
     * @param type $comiketId
     * @return type
     */
    public function _cmbTableDataFromInForm($inForm, $comiketId="") {

//        $resComiketDataDetailList = array();

        $comiketData = $this->_createComiketInsertDataByInForm($inForm, $comiketId);
        $comiketDetailDataList = $this->_createComiketDetailInsertDataByInForm($inForm, $comiketId);
        $comiketData["comiket_detail_list"] = $comiketDetailDataList;
        $comiketBoxDataList = $this->_createComiketBoxInsertDataByInForm($inForm, $comiketId);

        foreach($comiketBoxDataList as $key => $val) {
            foreach($comiketData["comiket_detail_list"] as $key2 => $val2) {
                if($comiketData["comiket_detail_list"][$key2]["type"] == $val["type"]) {
                    $comiketData["comiket_detail_list"][$key2]["comiket_box_list"][$key] = $val;
                }
            }
        }

        $comiketCargoDataList = $this->_createComiketCargoInsertDataByInForm($inForm, $comiketId);

        foreach($comiketCargoDataList as $key => $val) {
            foreach($comiketData["comiket_detail_list"] as $key2 => $val2) {
                if($comiketData["comiket_detail_list"][$key2]["type"] == $val["type"]) {
                    $comiketData["comiket_detail_list"][$key2]["comiket_cargo_list"][$key] = $val;
                }
            }
        }

        $comiketCharterDataList = $this->_createComiketCharterInsertDataByInForm($inForm, $comiketId);
//Sgmov_Component_Log::debug("################# 651");
//Sgmov_Component_Log::debug($comiketCharterDataList);
//Sgmov_Component_Log::debug($comiketData["comiket_detail_list"]);

        foreach($comiketCharterDataList as $key => $val) {
            foreach($comiketData["comiket_detail_list"] as $key2 => $val2) {
//Sgmov_Component_Log::debug("################# 651-1 :" . $key2);
//Sgmov_Component_Log::debug($comiketData["comiket_detail_list"][$key2]);
//Sgmov_Component_Log::debug($val["type"]);
                if($comiketData["comiket_detail_list"][$key2]["type"] == $val["type"]) {
                    $comiketData["comiket_detail_list"][$key2]["comiket_charter_list"][$key] = $val;
                }
            }
        }
        return array(
            "treeData" => $comiketData,
            "flatData" => array(
                "comiketData" => $comiketData,
                "comiketDetailDataList" => $comiketDetailDataList,
                "comiketBoxDataList" => $comiketBoxDataList,
                "comiketCargoDataList" => $comiketCargoDataList,
                "comiketCharterDataList" => $comiketCharterDataList,
            ),
        );
    }
    
    /**
     * 顧客コード取得
     * @param type $eventSel
     * @return string
     */
    private function getCustomerCd($eventSel) {
//        $customerCd = "12089295502";
//        if($eventSel == '1') { // デザインフェスタ
//            $customerCd = "12089295501";
//        } else if($eventSel == '11') { // コミケ
//            $customerCd = "12089295502";
//        } else if ($eventSel == '5') { // 東京マラソン
//            $customerCd = "13550786251";
//        } else {
//            $customerCd = "12089295502";
//        }
        
        return "13550786251";
    }


    public function _createComiketInsertDataByInForm($inForm, $id) {
        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        $batch_status = '1';

        $customerCd = $inForm['comiket_customer_cd'];
        $merchantResult = @$inForm['merchant_result'];
        if($inForm['comiket_div'] == self::COMIKET_DEV_BUSINESS) { // 法人
            $inForm['comiket_personal_name_sei'] = "";
            $inForm['comiket_personal_name_mei'] = "";

            $inForm['comiket_payment_method_cd_sel'] = "5";  // 念のため
            $inForm['comiket_convenience_store_cd_sel'] = NULL;
            $inForm['payment_order_id'] = NULL;
            $inForm['authorization_cd'] = NULL;

        } else { // 個人
////            $customerCd = NULL;
//            if($inForm['event_sel'] == '1') { // デザインフェスタ
//                $customerCd = "12089295501";
//            } else if($inForm['event_sel'] == '11') { // コミケ
//                $customerCd = "12089295502";
//            } else {
////                $customerCd = "048101000001";
//                $customerCd = "12089295502";
//            }
            $customerCd = $this->getCustomerCd($inForm['event_sel']);
            $inForm['office_name'] = "";

            if($inForm['comiket_payment_method_cd_sel'] == '1') { // コンビニ前払
                $inForm['authorization_cd'] = NULL;
            } else if($inForm['comiket_payment_method_cd_sel'] == '2') { // クレジット
                $inForm['comiket_convenience_store_cd_sel'] = NULL;
            } else if($inForm['comiket_payment_method_cd_sel'] == '4') { // コンビニ後払い

                $inForm['comiket_convenience_store_cd_sel'] = NULL;
                $inForm['payment_order_id'] = NULL;
                $inForm['authorization_cd'] = NULL;
            } else { // 電子マネー  と 法人売掛

                $inForm['comiket_convenience_store_cd_sel'] = NULL;
                $inForm['payment_order_id'] = NULL;
                $inForm['authorization_cd'] = NULL;
            }
        }

        if(!empty($inForm["comiket_id"])) {
            $comiketInfo = $this->_ComiketService->fetchComiketById($db, $inForm["comiket_id"]);
            $comiketInfo["id"] = $id;
            unset($comiketInfo["created"]);
            unset($comiketInfo["modified"]);
            $comiketInfo["choice"] = $inForm['comiket_detail_type_sel'];

            $comiketInfo["merchant_result"] = @$inForm['merchant_result'];
            $comiketInfo["merchant_datetime"] = @$inForm['merchant_datetime'];
            $comiketInfo["receipted"] = @$inForm['receipted'];
            $comiketInfo["send_result"] = "0";
            $comiketInfo["sent"] = NULL;
            $comiketInfo["batch_status"] = $batch_status;
            $comiketInfo["retry_count"] = "0";
            $comiketInfo["payment_method_cd"] = @$inForm['comiket_payment_method_cd_sel'];
            $comiketInfo["convenience_store_cd"] = @$inForm['comiket_convenience_store_cd_sel'];

            $comiketInfo["receipt_cd"] = @$inForm['receipt_cd'];
            $comiketInfo["authorization_cd"] = @$inForm['authorization_cd'];
            $comiketInfo["payment_order_id"] = @$inForm['payment_order_id'];

            $comiketInfo["create_ip"] = $_SERVER["REMOTE_ADDR"];
            $comiketInfo["modify_ip"] = $_SERVER["REMOTE_ADDR"];
            $comiketInfo["transaction_id"] = @$inForm["sgf_res_transactionId"];
            $comiketInfo["auto_authoriresult"] = @$inForm["sgf_res_autoAuthoriresult"];
            $comiketInfo["haraikomi_url"] = @$inForm['payment_url'];
            $comiketInfo["kounyuten_no"] = @$inForm['sgf_res_shopOrderId'];

//            if($comiketInfo['event_id'] == '1') { // デザインフェスタ
//                $customerCd = "12089295501";
//            } else if($comiketInfo['event_id'] == '11') { // コミケ
//                $customerCd = "12089295502";
//            } else if ($comiketInfo['event_id'] == '5') { // 東京マラソン
//                $customerCd = "13550786251";
//            } else {
//                $customerCd = "12089295502";
//            }
            $customerCd = $this->getCustomerCd($comiketInfo['event_id']);
            $comiketInfo['customer_cd'] = $customerCd;

            return $comiketInfo;
        }

        $buildingNameRes = $this->_BuildingService->fetchBuildingNameByCd($db, $inForm['building_name_sel'], $inForm['eventsub_sel']);
        $buildingInfo = $this->_BuildingService->fetchBuildingById($db, $inForm['building_booth_position_sel']);
Sgmov_Component_Log::debug("########################## 201");
//Sgmov_Component_Log::debug($buildingInfo);
Sgmov_Component_Log::debug($inForm);
        $data = array(
            "id" => $id,
            "merchant_result" => @$inForm['merchant_result'],
            "merchant_datetime" => @$inForm['merchant_datetime'],
            "receipted" => @$inForm['receipted'],
            "send_result" => "0",
            "sent" => NULL,
            "batch_status" => $batch_status,
            "retry_count" => "0",
            "payment_method_cd" => !empty($inForm['comiket_payment_method_cd_sel']) ? $inForm['comiket_payment_method_cd_sel'] : "5", // 法人の場合は5(法人売掛？？)
            "convenience_store_cd" => @$inForm['comiket_convenience_store_cd_sel'],
            "receipt_cd" => @$inForm['receipt_cd'],
            "authorization_cd" => @$inForm['authorization_cd'],
            "payment_order_id" => @$inForm['payment_order_id'],
            "div" => $inForm['comiket_div'],
            "event_id" => $inForm['event_sel'],
            "eventsub_id" => $inForm['eventsub_sel'],
            "customer_cd" => $customerCd,
            "office_name" => $inForm['office_name'],
            "personal_name_sei" => $inForm['comiket_personal_name_sei'],
            "personal_name_mei" => $inForm['comiket_personal_name_mei'],
            "zip" => $inForm['comiket_zip1'] . $inForm['comiket_zip2'],
            "pref_id" => $inForm['comiket_pref_cd_sel'],
            "address" => $inForm['comiket_address'],
            "building" => $inForm['comiket_building'],
            "tel" => $inForm['comiket_tel'],
            "mail" => $inForm['comiket_mail'],
            "booth_name" => @$inForm['comiket_booth_name'],
            'building_cd' => $inForm['building_name_sel'],
            "building_name" => @$buildingNameRes['name'],
            "booth_position" => empty($buildingInfo) ? "" : $buildingInfo['booth_position'],
            "booth_num" => $inForm['comiket_booth_num'],
            "staff_sei" => @$inForm['comiket_staff_sei'],
            "staff_mei" => @$inForm['comiket_staff_mei'],
            "staff_sei_furi" => @$inForm['comiket_staff_sei_furi'],
            "staff_mei_furi" => @$inForm['comiket_staff_mei_furi'],
            "staff_tel" => $inForm['comiket_staff_tel'],
            "choice" => $inForm['comiket_detail_type_sel'],
            "amount" => "0", // ?
            "amount_tax" => "0", // ?
            "create_ip" => $_SERVER["REMOTE_ADDR"],
//            "created" => "",
            "modify_ip" => $_SERVER["REMOTE_ADDR"],
//            "modified" => "",
            "transaction_id" => @$inForm['sgf_res_transactionId'],
            "auto_authoriresult" => @$inForm['sgf_res_autoAuthoriresult'],
            "haraikomi_url" => @$inForm['payment_url'],
            "kounyuten_no" => @$inForm['sgf_res_shopOrderId'],
        );
//Sgmov_Component_Log::debug("############################################ 401");
//Sgmov_Component_Log::debug($data);
        return $data;
    }

    public function _createComiketDetailInsertDataByInForm($inForm, $id) {
        $returnList = array();

        $customerCd = "";
        if(!empty($id)) {
            $customerCd = sprintf("%07d", $id);
        }

        $db = Sgmov_Component_DB::getPublic();

        if($inForm['comiket_detail_type_sel'] == "1"
                || $inForm['comiket_detail_type_sel'] == "3" ) { // 搬入（もしくは搬入搬出）

            if(empty($inForm['comiket_detail_outbound_collect_date_year_sel'])
                    || empty($inForm['comiket_detail_outbound_collect_date_month_sel'])
                    || empty($inForm['comiket_detail_outbound_collect_date_day_sel'])) {
                $comiket_detail_outbound_collect_date = "";
            } else {
                $comiket_detail_outbound_collect_date =
                        $inForm['comiket_detail_outbound_collect_date_year_sel']
                        . '-' . $inForm['comiket_detail_outbound_collect_date_month_sel']
                        . '-' . $inForm['comiket_detail_outbound_collect_date_day_sel'];
            }

            if(empty($inForm['comiket_detail_outbound_delivery_date_year_sel'])
                    || empty($inForm['comiket_detail_outbound_delivery_date_month_sel'])
                    || empty($inForm['comiket_detail_outbound_delivery_date_day_sel'])) {
                $comiket_detail_outbound_delivery_date = "";
            } else {
                $comiket_detail_outbound_delivery_date =
                        $inForm['comiket_detail_outbound_delivery_date_year_sel']
                        . '-' . $inForm['comiket_detail_outbound_delivery_date_month_sel']
                        . '-' . $inForm['comiket_detail_outbound_delivery_date_day_sel'];
            }

            $note = $inForm['comiket_detail_outbound_note1'];

            $collectStTime = null;
            $collectEdTime = null;
            if(!empty($inForm['comiket_detail_outbound_collect_time_sel'])) {
                $outboundCollectTimeList = explode('-', $inForm['comiket_detail_outbound_collect_time_sel']);

                if(empty($outboundCollectTimeList)) {
                    $collectStTime = null;
                    $collectEdTime = null;
                } else if(count($outboundCollectTimeList) == 2) {
                    $collectStTime = $outboundCollectTimeList[0];
                    $collectEdTime = $outboundCollectTimeList[1];
                } else if(count($outboundCollectTimeList) == 1) {
                    if($outboundCollectTimeList[0] == 'null') {
                        $collectStTime = $outboundCollectTimeList[0];
                    } else {
                        $outboundCollectTimeList[0] = null;
                    }
                    $collectEdTime = null;
                }
            }

            $deliveryStTime = null;
            $deliveryEdTime = null;
            if(!empty($inForm['comiket_detail_outbound_delivery_time_sel'])) {
                $outboundDeliveryTimeList = explode('-', $inForm['comiket_detail_outbound_delivery_time_sel']);

                if(empty($outboundDeliveryTimeList)) {
                    $deliveryStTime = null;
                    $deliveryEdTime = null;
                } else if(count($outboundDeliveryTimeList) == 2) {
                    $deliveryStTime = $outboundDeliveryTimeList[0];
                    $deliveryEdTime = $outboundDeliveryTimeList[1];
                } else if(count($outboundDeliveryTimeList) == 1) {
                    if($outboundDeliveryTimeList[0] == '00') {
                        $outboundDeliveryTimeList[0] = null;
                    } else {
                        $deliveryStTime = $outboundDeliveryTimeList[0];
                    }
                    $deliveryEdTime = null;
                }
            }

            $data = array(
                "comiket_id" => $id,
                "type" => "1",
                "cd" => "ev{$customerCd}1",
                "name" => $inForm['comiket_detail_outbound_name'],

                "hatsu_jis5code" => @$inForm["outbound_hatsu_jis5code"],
                "hatsu_shop_check_code" => @$inForm["outbound_hatsu_shop_check_code"],
                "hatsu_shop_check_code_eda" => @$inForm["outbound_hatsu_shop_check_code_eda"],
                "hatsu_shop_code" => @$inForm["outbound_hatsu_shop_code"],
                "hatsu_shop_local_code" => @$inForm["outbound_hatsu_shop_local_code"],

                "chaku_jis5code" => @$inForm["outbound_chaku_jis5code"],
                "chaku_shop_check_code" => @$inForm["outbound_chaku_shop_check_code"],
                "chaku_shop_check_code_eda" => @$inForm["outbound_chaku_shop_check_code_eda"],
                "chaku_shop_code" => @$inForm["outbound_chaku_shop_code"],
                "chaku_shop_local_code" => @$inForm["outbound_chaku_shop_local_code"],

                "zip" => $inForm['comiket_detail_outbound_zip1'] . $inForm['comiket_detail_outbound_zip2'],
                "pref_id" => $inForm['comiket_detail_outbound_pref_cd_sel'],
                "address" => $inForm['comiket_detail_outbound_address'],
                "building" => $inForm['comiket_detail_outbound_building'],
                "tel" => $inForm['comiket_detail_outbound_tel'],

                "collect_date" => $comiket_detail_outbound_collect_date,
                "collect_st_time" => $collectStTime,
                "collect_ed_time" => $collectEdTime,

                "delivery_date" => $comiket_detail_outbound_delivery_date,
                "delivery_st_time" => $deliveryStTime,
                "delivery_ed_time" => $deliveryEdTime,

                "service" => $inForm['comiket_detail_outbound_service_sel'],
                "note" => $note,
                "fare" => "0", // ?
                "fare_tax" => "0", // ?
                "cost" => "0", // ?
                "cost_tax" => "0", // ?
                "delivery_timezone_cd" => null,
                "delivery_timezone_name" => null,
            );

            $returnList[] = $data;
        }

        if($inForm['comiket_detail_type_sel'] == "2"
                || $inForm['comiket_detail_type_sel'] == "3" ) { // 搬出（もしくは搬入搬出）

            if(empty($inForm['comiket_detail_inbound_collect_date_year_sel'])
                    || empty($inForm['comiket_detail_inbound_collect_date_month_sel'])
                    || empty($inForm['comiket_detail_inbound_collect_date_day_sel'])) {
                $comiket_detail_inbound_collect_date = "";
            } else {
                $comiket_detail_inbound_collect_date =
                        $inForm['comiket_detail_inbound_collect_date_year_sel']
                        . '-' . $inForm['comiket_detail_inbound_collect_date_month_sel']
                        . '-' . $inForm['comiket_detail_inbound_collect_date_day_sel'];
            }

            if(empty($inForm['comiket_detail_inbound_delivery_date_year_sel'])
                    || empty($inForm['comiket_detail_inbound_delivery_date_month_sel'])
                    || empty($inForm['comiket_detail_inbound_delivery_date_day_sel'])) {
                $comiket_detail_inbound_delivery_date = "";
            } else {
                $comiket_detail_inbound_delivery_date =
                        $inForm['comiket_detail_inbound_delivery_date_year_sel']
                        . '-' . $inForm['comiket_detail_inbound_delivery_date_month_sel']
                        . '-' . $inForm['comiket_detail_inbound_delivery_date_day_sel'];
            }


            $note = $inForm['comiket_detail_inbound_note1'];

            $collectStTime = null;
            $collectEdTime = null;

            $deliveryStTime = null;
            $deliveryEdTime = null;
            if($inForm['comiket_div'] == self::COMIKET_DEV_BUSINESS && $inForm['comiket_detail_inbound_service_sel'] != "1") { // 法人 かつ　サービス が宅配ではない場合
                // 法人の場合は搬出のお届け日時がないため以下で設定
//                $deliveryDate = "9999-12-31 00:00:00";
                $deliveryDate = null;
//                $deliveryTime = "-1"; // TODO -1で良い?
                $deliveryStTime = null;
                $deliveryEdTime = null;
            } else { // 個人

                $deliveryDate = null;
                if (!empty($comiket_detail_inbound_delivery_date)) {
                    $deliveryDate = $comiket_detail_inbound_delivery_date;
                }
                $deliveryTime = $inForm['comiket_detail_inbound_delivery_time_sel'];
                if(!empty($inForm['comiket_detail_inbound_delivery_time_sel'])) {
                    $arrTimezone = explode(',', $inForm['comiket_detail_inbound_delivery_time_sel']);
                    $inboundDeliveryTimeList = explode('～', $arrTimezone[1]);

                    if(empty($inboundDeliveryTimeList)) {
                        $deliveryStTime = null;
                        $deliveryEdTime = null;
                    } else if(count($inboundDeliveryTimeList) == 2) {
                        $deliveryStTime = $inboundDeliveryTimeList[0];
                        $deliveryEdTime = $inboundDeliveryTimeList[1];
                    } else if(count($inboundDeliveryTimeList) == 1) {
                        if($arrTimezone[0] == "00" || $arrTimezone[0] == "11") {
                            $deliveryStTime = null;
                        } else {
                            $deliveryStTime = $inboundDeliveryTimeList[0];
                        }
                        $deliveryEdTime = null;
                    }
                }
            }

            if(!empty($inForm['comiket_detail_inbound_collect_time_sel'])) {
                $inboundCollectTimeList = explode('-', $inForm['comiket_detail_inbound_collect_time_sel']);

                if(empty($inboundCollectTimeList)) {
                    $collectStTime = null;
                    $collectEdTime = null;
                } else if(count($inboundCollectTimeList) == 2) {
                    $collectStTime = $inboundCollectTimeList[0];
                    $collectEdTime = $inboundCollectTimeList[1];
                } else if(count($inboundCollectTimeList) == 1) {
                    if($inboundCollectTimeList[0] == "00") {
                        $collectStTime = null;
                    } else {
                        $collectStTime = $inboundCollectTimeList[0];
                    }
                    $collectEdTime = null;
                }
            }

            $timezoneCd = '';
            $timezoneNm = '';
            if (!empty($inForm['comiket_detail_inbound_delivery_time_sel'])) {
                $arrTimezomeVal = explode(',', $inForm['comiket_detail_inbound_delivery_time_sel']);
                $timezoneCd = $arrTimezomeVal[0];
                $timezoneNm = $arrTimezomeVal[1];
            }

            $data = array(
                "comiket_id" => $id,
                "type" => "2",
                "cd" => "ev{$customerCd}2",
                "name" => $inForm['comiket_detail_inbound_name'],

                "hatsu_jis5code" => @$inForm["inbound_hatsu_jis5code"],
                "hatsu_shop_check_code" => @$inForm["inbound_hatsu_shop_check_code"],
                "hatsu_shop_check_code_eda" => @$inForm["inbound_hatsu_shop_check_code_eda"],
                "hatsu_shop_code" => @$inForm["inbound_hatsu_shop_code"],
                "hatsu_shop_local_code" => @$inForm["inbound_hatsu_shop_local_code"],

                "chaku_jis5code" => @$inForm["inbound_chaku_jis5code"],
                "chaku_shop_check_code" => @$inForm["inbound_chaku_shop_check_code"],
                "chaku_shop_check_code_eda" => @$inForm["inbound_chaku_shop_check_code_eda"],
                "chaku_shop_code" => @$inForm["inbound_chaku_shop_code"],
                "chaku_shop_local_code" => @$inForm["inbound_chaku_shop_local_code"],

                "zip" => $inForm['comiket_detail_inbound_zip1'] . $inForm['comiket_detail_inbound_zip2'],
                "pref_id" => $inForm['comiket_detail_inbound_pref_cd_sel'],
                "address" => $inForm['comiket_detail_inbound_address'],
                "building" => $inForm['comiket_detail_inbound_building'],
                "tel" => $inForm['comiket_detail_inbound_tel'],

                "collect_date" => $comiket_detail_inbound_collect_date,
                "collect_st_time" => $collectStTime,
                "collect_ed_time" => $collectEdTime,

                "delivery_date" => $deliveryDate,
                "delivery_st_time" => $deliveryStTime,
                "delivery_ed_time" => $deliveryEdTime,

                "service" => $inForm['comiket_detail_inbound_service_sel'],
                "note" => $note,
                "fare" => "0", // ?
                "fare_tax" => "0", // ?
                "cost" => "0", // ?
                "cost_tax" => "0", // ?
                "delivery_timezone_cd" => $timezoneCd,
                "delivery_timezone_name" => $timezoneNm,
            );

            $returnList[] = $data;
        }

        return $returnList;
    }

    public function _createComiketBoxInsertDataByInForm($inForm, $id) {

        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        $returnList = array();

        if($inForm['comiket_detail_type_sel'] == "1"
                || $inForm['comiket_detail_type_sel'] == "3" ) { // 搬入（もしくは搬入搬出）
            if($inForm['comiket_detail_outbound_service_sel'] == "1") { // 宅配

                foreach($inForm['comiket_box_outbound_num_ary'] as $key => $val) {
                    $boxData = $this->_BoxService->fetchBoxById($db, $key);
                    if(empty($val)) {
                        continue;
                    }
                    $boxFareData = $this->_BoxFareService->fetchBoxFareByJis2AndBoxId($db, $inForm["outbound_hatsu_jis2code"], $inForm["outbound_chaku_jis2code"], $key, $inForm['eventsub_sel']);
                    if(empty($boxFareData)) {
                        $fare = 0;
                    } else {
                        $fare = intval($boxFareData["fare"]);
                    }

//                        $fare = $fareTax / 1.08;
//                        $fare = ceil(fare);
                    $fareAmount = $fare * intval($val);
                    $data = array(
                        "comiket_id" => $id,
                        "type" => "1", // 搬入
//                        "name" => @empty($boxData["name"]) ? "" : $boxData["name"],
                        "box_id" => $key,
                        "num" => "$val",
                        "fare_price" => $fare, // ?
                        "fare_amount" => $fareAmount, // ?
                        "fare_price_tax" => "0", // ?
                        "fare_amount_tax" => "0", // ?
                        "cost_price" => "0", // ?
                        "cost_amount" => "0", // ?
                        "cost_price_tax" => "0", // ?
                        "cost_amount_tax" => "0", // ?
                    );
                    $returnList[] = $data;
                }
            }
        }

        if($inForm['comiket_detail_type_sel'] == "2"
                || $inForm['comiket_detail_type_sel'] == "3" ) { // 搬出（もしくは搬入搬出）
            if($inForm['comiket_detail_inbound_service_sel'] == "1") { // 宅配

                foreach($inForm['comiket_box_inbound_num_ary'] as $key => $val) {

                    $boxData = $this->_BoxService->fetchBoxById($db, $key);
                    if(empty($val)) {
                        continue;
                    }
                    $boxFareData = $this->_BoxFareService->fetchBoxFareByJis2AndBoxId($db, $inForm["inbound_hatsu_jis2code"], $inForm["inbound_chaku_jis2code"], $key, $inForm['eventsub_sel']);
//                    if(empty($boxFareData)) {
//                        throw
//                    }
                    if(empty($boxFareData)) {
                        $fareTax = 0;
                    } else {
                        $fareTax = intval($boxFareData["fare"]);
                    }

                    $fareAmountTax = $fareTax * intval($val);
                    $data = array(
                        "comiket_id" => $id,
                        "type" => "2", // 搬出
//                        "name" => @empty($boxData["name"]) ? "" : $boxData["name"],
                        "box_id" => $key,
                        "num" => "$val",
                        "fare_price" => "0", // ?
                        "fare_amount" => "0", // ?
                        "fare_price_tax" => $fareTax, // ?
                        "fare_amount_tax" => $fareAmountTax, // ?
                        "cost_price" => "0", // ?
                        "cost_amount" => "0", // ?
                        "cost_price_tax" => "0", // ?
                        "cost_amount_tax" => "0", // ?
                    );
                    $returnList[] = $data;
                }
            }
        }

        return $returnList;
    }

    public function _createComiketCargoInsertDataByInForm($inForm, $id) {

        $db = Sgmov_Component_DB::getPublic();

        $returnList = array();

//        if($inForm['comiket_div'] == self::COMIKET_DEV_INDIVIDUA && $inForm['event_sel'] == '2'
//                || ($inForm['comiket_div'] == self::COMIKET_DEV_INDIVIDUA && $inForm['event_sel'] == '4')
//                || $inForm['comiket_div'] == self::COMIKET_DEV_BUSINESS) { // (個人 かつ　コミケの場合) または 法人の場合
//            return $returnList;


            if ($inForm['comiket_detail_type_sel'] == "1" || $inForm['comiket_detail_type_sel'] == "3") { // 搬入（もしくは搬入搬出）
                if ($inForm['comiket_detail_outbound_service_sel'] == "2") { // カーゴ
//                if($inForm['comiket_div'] == "1" && $inForm['comiket_customer_cd_sel'] == '1') { // 法人 && 顧客コードを使用する
//Sgmov_Component_Log::debug("########################### 260");
//Sgmov_Component_Log::debug($inForm);
                    $cargoFareData = @$this->_CargoFareService->fetchCargoFareByJis2AndCargoNum(
                            $db, $inForm["outbound_hatsu_jis2code"], $inForm["outbound_chaku_jis2code"], $inForm["comiket_cargo_outbound_num_sel"], $inForm["eventsub_sel"]); // 13は東京(jis2code)

//                unset($inForm['comiket_cargo_outbound_num_ary'][0]);
//                foreach ($inForm['comiket_cargo_outbound_num_ary'] as $key => $val) {
                        $data = array(
                            "comiket_id" => $id,
                            "type" => "1", // 搬入
//                        "cargo_id" => "999", // ?
                            "num" => @$inForm["comiket_cargo_outbound_num_sel"],
                            "fare_amount" => @$cargoFareData["cargo_fare"],
                        );
                        $returnList[] = $data;
//                }
//                } else { // 個人 or 法人 && 顧客コードを使用しない
//
//                }
                }
            }

            if ($inForm['comiket_detail_type_sel'] == "2" || $inForm['comiket_detail_type_sel'] == "3") { // 搬出（もしくは搬入搬出）
                if ($inForm['comiket_detail_inbound_service_sel'] == "2") { // カーゴ
//                if($inForm['comiket_div'] == "1" && $inForm['comiket_customer_cd_sel'] == '1') { // 法人 && 顧客コードを使用する
                    $cargoFareData = @$this->_CargoFareService->fetchCargoFareByJis2AndCargoNum(
                            $db, $inForm["inbound_hatsu_jis2code"], $inForm["inbound_chaku_jis2code"], $inForm["comiket_cargo_inbound_num_sel"], $inForm["eventsub_sel"]); // 13は東京(jis2code)
//                unset($inForm['comiket_cargo_inbound_num_ary'][0]);
//                foreach ($inForm['comiket_cargo_inbound_num_ary'] as $key => $val) {

                        $data = array(
                            "comiket_id" => $id,
                            "type" => "2", // 搬出
//                        "cargo_id" => "$key",
                            "num" => @$inForm["comiket_cargo_inbound_num_sel"],
                            "fare_amount" => @$cargoFareData["cargo_fare"],
                        );
                        $returnList[] = $data;
//                }
//                } else { // 個人 or 法人 && 顧客コードを使用しない
//
//                }
                }
            }
//        }

        return $returnList;
    }

    public function _createComiketCharterInsertDataByInForm($inForm, $id) {

        $db = Sgmov_Component_DB::getPublic();

        $returnList = array();

        if($inForm['comiket_div'] != self::COMIKET_DEV_BUSINESS) { // 法人以外
            return $returnList;
        }

        if($inForm['comiket_detail_type_sel'] == "1"
                || $inForm['comiket_detail_type_sel'] == "3" ) { // 搬入（もしくは搬入搬出）
            if($inForm['comiket_detail_outbound_service_sel'] == "3") { // チャーター

//                if($inForm['comiket_div'] == "1") { // 法人
                    unset($inForm['comiket_charter_outbound_num_ary'][0]);
                    foreach($inForm['comiket_charter_outbound_num_ary'] as $key => $val) {

                        if(empty($val)) {
                            continue;
                        }
                        $charterInfo = $this->_CharterService->fetchCharterById($db, $key);
                        $data = array(
                            "comiket_id" => $id,
                            "type" => "1", // 搬入
                            "name" => $charterInfo['name'],
                            "num" => "$val",
//                            "price" => "0", // ?
//                            "amount" => "0", // ?
                        );
                        $returnList[] = $data;
                    }
//                } else { // 個人
//
//                }
            }
        }

        if($inForm['comiket_detail_type_sel'] == "2"
                || $inForm['comiket_detail_type_sel'] == "3" ) { // 搬出（もしくは搬入搬出）
            if($inForm['comiket_detail_inbound_service_sel'] == "3") { // チャーター
//                if($inForm['comiket_div'] == "1") { // 法人
                    unset($inForm['comiket_charter_inbound_num_ary'][0]);
                    foreach($inForm['comiket_charter_inbound_num_ary'] as $key => $val) {
                        if(empty($val)) {
                            continue;
                        }
                        $charterInfo = $this->_CharterService->fetchCharterById($db, $key);
                        $data = array(
                            "comiket_id" => $id,
                            "type" => "2", // 搬出
                            "name" => $charterInfo['name'],
                            "num" => "$val",
//                            "price" => "0", // ?
//                            "amount" => "0", // ?
                        );
                        $returnList[] = $data;
                    }
//                } else { // 個人
//
//                }
            }
        }

        return $returnList;
    }

    /**
     *
     * @param type $inForm
     * @return type
     */
    public function checkCurrentDateWithInTerm($inForm) {

        if(!is_array($inForm)) {
            $inForm = (array)$inForm;
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // 搬入・搬出の申込期間を過ぎていないかチェック
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        if(@empty($inForm['comiket_detail_type_sel']) || @empty($inForm['eventsub_sel'])) {
            return;
        }

        if($inForm['comiket_detail_type_sel'] == "1") { // 往路
            if(!$this->isCurrentDateWithInTerm("departure", $inForm['eventsub_sel'])) {
                Sgmov_Component_Redirect::redirectPublicSsl("/".$this->_DirDiv."/error_term/{$inForm['comiket_detail_type_sel']}");
                exit;
            }
        } else if($inForm['comiket_detail_type_sel'] == "2") { // 復路
            if(!$this->isCurrentDateWithInTerm("arrival", $inForm['eventsub_sel'])) {
                Sgmov_Component_Redirect::redirectPublicSsl("/".$this->_DirDiv."/error_term/{$inForm['comiket_detail_type_sel']}");
                exit;
            }
        } else { // 往復
            $isDepartureErr = FALSE;
            $isArrivalErr = FALSE;
            if(!$this->isCurrentDateWithInTerm("departure", $inForm['eventsub_sel'])) {
                $isDepartureErr = TRUE;
            }

            if(!$this->isCurrentDateWithInTerm("arrival", $inForm['eventsub_sel'])) {
                $isArrivalErr = TRUE;
            }

            if($isDepartureErr && $isArrivalErr) {
                Sgmov_Component_Redirect::redirectPublicSsl("/".$this->_DirDiv."/error_term/3"); // 往復エラー画面
                exit;
            } else if($isDepartureErr) {
                Sgmov_Component_Redirect::redirectPublicSsl("/".$this->_DirDiv."/error_term/1"); // 往路エラー画面
                exit;
            } else if($isArrivalErr) {
                Sgmov_Component_Redirect::redirectPublicSsl("/".$this->_DirDiv."/error_term/2"); // 復路エラー画面
                exit;
            }
        }
    }

    /**
     *
     * @param type $keyPrefix
     * @param type $eventsubId
     * @return boolean
     */
    public function isCurrentDateWithInTerm($keyPrefix, $eventsubId) {
        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();
        $eventsubData = $this->_EventsubService->fetchEventsubByEventsubId($db, $eventsubId);
//        $departureTo = $eventsubData["departure_to"];

        $termFr = $eventsubData["{$keyPrefix}_fr"];
        $termFrDateTime = new DateTime($termFr);
        $termFrYMD = $termFrDateTime->format("Y-m-d");

        $currentDateTime = new DateTime('now');
        $currentYMDForFr = $currentDateTime->format("Y-m-d");
        if($keyPrefix == 'arrival') {
            $currentYMDForTo = $currentDateTime->format("Y-m-d H:i:s");

            $termTo = $eventsubData["{$keyPrefix}_to_time"];
            $termToDateTime = new DateTime($termTo);
            $termToYMD = $termToDateTime->format("Y-m-d H:i:s");
        } else {
            $currentYMDForTo = $currentDateTime->format("Y-m-d");

            $termTo = $eventsubData["{$keyPrefix}_to"];
            $termToDateTime = new DateTime($termTo);
            $termToYMD = $termToDateTime->format("Y-m-d");
        }
//Sgmov_Component_Log::debug("########################### 260-0 :" . $keyPrefix);
//Sgmov_Component_Log::debug($termFrYMD);
//Sgmov_Component_Log::debug($currentYMDForFr);
//Sgmov_Component_Log::debug($currentYMDForTo);
//Sgmov_Component_Log::debug($termToYMD);
        if($termFrYMD <= $currentYMDForFr && $currentYMDForTo <= $termToYMD) {
//Sgmov_Component_Log::debug("########################### 260-1");
            return TRUE;
        }
//Sgmov_Component_Log::debug("########################### 260-2");
        return FALSE;
    }

    public static function getChkD($param) {

                // 顧客コードを配列化
                $param2 = str_split($param);


                // 掛け算数値配列（固定らしいのでベタ書き）
                $intCheck = array(
                    0 => 4,
                    1 => 3,
                    2 => 2,
                    3 => 9,
                    4 => 8,
                    5 => 7,
                    6 => 6,
                    7 => 5,
                    8 => 4,
                    9 => 3,
//                    10 => 2,
                );

                $total = 0;
                for ($i = 0; $i < count($intCheck); $i++) {
                    $total += $param2[$i] * $intCheck[$i];
                }


//        $target = intval($param);
//        $result = $target % 7;
        return $total;
    }

    public static function getChkD2($param) {

        $target = intval($param);
        $result = $target % 7;
        return $result;
    }

    /**
     *
     * @param type $check
     * @param type $inForm
     * @param type $eventsubInfo
     * @param type $inOutboundFlg
     * @return boolean
     */
    public function checkColAndDelDate($inOutboundFlg, $comiketDiv, $serviceSel, $eventsubInfo) {

            if($inOutboundFlg == "outbound") {
                // 個人・宅配・往路集荷・日付指定
                if($comiketDiv == self::COMIKET_DEV_INDIVIDUA && $serviceSel == '1' && $eventsubInfo['kojin_box_col_date_flg'] == '1') {
                    return TRUE;
                }

                // 個人・カーゴ・往路集荷・日付指定
                if($comiketDiv == self::COMIKET_DEV_INDIVIDUA && $serviceSel == '2' && $eventsubInfo['kojin_cag_col_date_flg'] == '1') {
                    return TRUE;
                }

                // 法人・宅配・往路集荷・日付指定
                if($comiketDiv == self::COMIKET_DEV_BUSINESS && $serviceSel == '1' && $eventsubInfo['hojin_box_col_date_flg'] == '1') {
                    return TRUE;
                }

                // 法人・カーゴ・往路集荷・日付指定
                if($comiketDiv == self::COMIKET_DEV_BUSINESS && $serviceSel == '2' && $eventsubInfo['hojin_cag_col_date_flg'] == '1') {
                    return TRUE;
                }

                // 法人・貸切・往路集荷・日付指定
                if($comiketDiv == self::COMIKET_DEV_BUSINESS && $serviceSel == '3' && $eventsubInfo['hojin_kas_col_date_flg'] == '1') {
                    return TRUE;
                }

            } else if($inOutboundFlg == "inbound") {
                // 個人・宅配・復路配達・日付指定
                if($comiketDiv == self::COMIKET_DEV_INDIVIDUA && $serviceSel == '1' && $eventsubInfo['kojin_box_dlv_date_flg'] == '1') {
                    return TRUE;
                }

                // 個人・カーゴ・復路配達・日付指定
                if($comiketDiv == self::COMIKET_DEV_INDIVIDUA && $serviceSel  == '2' && $eventsubInfo['kojin_cag_dlv_date_flg'] == '1') {
                    return TRUE;
                }

                // 法人・宅配・復路配達・日付指定
                if($comiketDiv == self::COMIKET_DEV_BUSINESS && $serviceSel  == '1' && $eventsubInfo['hojin_box_dlv_date_flg'] == '1') {
                    return TRUE;
                }

                // 法人・カーゴ・復路配達・日付指定
                if($comiketDiv == self::COMIKET_DEV_BUSINESS && $serviceSel  == '2' && $eventsubInfo['hojin_cag_dlv_date_flg'] == '1') {
                    return TRUE;
                }

                // 法人・貸切・復路配達・日付指定
                if($comiketDiv == self::COMIKET_DEV_BUSINESS && $serviceSel  == '3' && $eventsubInfo['hojin_kas_dlv_date_flg'] == '1') {
                    return TRUE;
                }
            }

            return FALSE;
    }

    /**
     *
     * @param type $check
     */
    public function checkColAndDelTime($inOutboundFlg, $comiketDiv, $serviceSel, $eventsubInfo) {
        if($inOutboundFlg == "outbound") {
            // 個人・宅配・往路集荷・時間指定
            if($comiketDiv == self::COMIKET_DEV_INDIVIDUA && $serviceSel == '1' && $eventsubInfo['kojin_box_col_time_flg'] == '1') {
                return TRUE;
            }

            // 個人・カーゴ・往路集荷・時間指定
            if($comiketDiv == self::COMIKET_DEV_INDIVIDUA && $serviceSel == '2' && $eventsubInfo['kojin_cag_col_time_flg'] == '1') {
                return TRUE;
            }

            // 法人・宅配・往路集荷・時間指定
            if($comiketDiv == self::COMIKET_DEV_BUSINESS && $serviceSel == '1' && $eventsubInfo['hojin_box_col_time_flg'] == '1') {
                return TRUE;
            }

            // 法人・カーゴ・往路集荷・時間指定
            if($comiketDiv == self::COMIKET_DEV_BUSINESS && $serviceSel == '2' && $eventsubInfo['hojin_cag_col_time_flg'] == '1') {
                return TRUE;
            }

            // 法人・貸切・往路集荷・時間指定
            if($comiketDiv == self::COMIKET_DEV_BUSINESS && $serviceSel == '3' && $eventsubInfo['hojin_kas_col_time_flg'] == '1') {
                return TRUE;
            }

        } else if($inOutboundFlg == "inbound") {
            // 個人・宅配・復路配達・時間指定
            if($comiketDiv == self::COMIKET_DEV_INDIVIDUA && $serviceSel == '1' && $eventsubInfo['kojin_box_dlv_time_flg'] == '1') {
                return TRUE;
            }

            // 個人・カーゴ・復路配達・時間指定
            if($comiketDiv == self::COMIKET_DEV_INDIVIDUA && $serviceSel == '2' && $eventsubInfo['kojin_cag_dlv_time_flg'] == '1') {
                return TRUE;
            }

            // 法人・宅配・復路配達・時間指定
            if($comiketDiv == self::COMIKET_DEV_BUSINESS && $serviceSel == '1' && $eventsubInfo['hojin_box_dlv_time_flg'] == '1') {
                return TRUE;
            }

            // 法人・カーゴ・復路配達・時間指定
            if($comiketDiv == self::COMIKET_DEV_BUSINESS && $serviceSel == '2' && $eventsubInfo['hojin_cag_dlv_time_flg'] == '1') {
                return TRUE;
            }

            // 法人・貸切・復路配達・時間指定
            if($comiketDiv == self::COMIKET_DEV_BUSINESS && $serviceSel == '3' && $eventsubInfo['hojin_kas_dlv_time_flg'] == '1') {
                return TRUE;
            }
        }

        return FALSE;
    }
}
