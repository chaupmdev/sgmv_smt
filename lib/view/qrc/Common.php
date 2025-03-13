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
Sgmov_Lib::useAllComponents();
Sgmov_Lib::useServices(array(
    'CruiseRepeater', 'Prefecture', 'Yubin', 'SocketZipCodeDll', 'Event', 'Box', 'Cargo', 'Building', 'Charter', 'Eventsub', 'AppCommon', 'HttpsZipCodeDll', 'BoxFare', 'CargoFare', 'Comiket', 'EventsubCmb', 'Time', 'CenterMail', 'ComiketDetail'
));
Sgmov_Lib::useView('Public');
/**#@-*/

//define("COMIKET_DEV_INDIVIDUA", 1); // 個人
//define("COMIKET_DEV_BUSINESS", 2); // 法人
/**
 * イベントサービスのお申し込みフォームの共通処理を管理する抽象クラスです。
 * @package    View
 * @subpackage RMS
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Qrc_Common extends Sgmov_View_Public
{
    /**
     * 機能ID
     */
    const FEATURE_ID = 'QRC';

    /**
     * イベントID
     */
    const EVENT_ID = '9';

    /**
     * イベントサブID
     */
    const EVENT_SUB_ID = '9';

    /**
     * お預かり日
     * 値をセットした場合この日付を優先します
     */
    // 年
    const COLLECT_YEAR  = '2022';
    // 月
    const COLLECT_MONTH = '3';
    // 日
    const COLLECT_DAY   = '1';
    // 時間帯
    const COLLECT_TIMEFRAME   = '16:00～21:00';

    /**
     * RMS001の画面ID
     */
    const GAMEN_ID_QRC001 = 'QRC001';

    /**
     * RMS002の画面ID
     */
    const GAMEN_ID_QRC002 = 'QRC002';

    /**
     * RMS003の画面ID
     */
    const GAMEN_ID_QRC003 = 'QRC003';

    /**
     * 個人
     */
    const COMIKET_DEV_INDIVIDUA = "1";

    /**
     * 法人
     */
    const COMIKET_DEV_BUSINESS = "2";

    /**
     * 消費税率
     */
    const CURRENT_TAX = 1.10;

    /**
     * 識別コード選択値
     * @var array
     */
    public $comiket_div_lbls = array(
        1 => '<span class="disp_comiket" style="display:none;">電子決済の方(クレジット、コンビニ決済、電子マネー)</span><span class="disp_design">出展者</span><span class="disp_gooutcamp">出展者（個人・法人含む）</span><span class="disp_etc">個人</span>',
        2 => '請求書にて請求',
    );

    /**
     * 集荷の往復コード選択値
     * @var array
     */
    public $comiket_detail_type_lbls = array(
        2 => '搬出（会場⇒お客様）',
    );

    /**
     * サービス選択値
     * @var array
     */
    public $comiket_detail_service_lbls = array(
        1 => '宅配便'
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
     * @var array
     */
    public $comiket_cargo_item_list = array(
        "1" => "1",
        "2" => "2",
        "3" => "3",
    );

    /**
     * 集荷の往復コード選択値
     * @var array
     */
    public $terminal_lbls = array(
        2 => '搬出のみ',
        3 => '往復',
    );

    /**
     * お支払方法コード選択値
     * @var array
     */
    public $payment_method_lbls = array(
        '' => '',
        2  => 'クレジットカード',
        3  => '電子マネー',
    );

    /**
     * お支払店コード選択値
     * @var array
     */
    public $convenience_store_lbls = array(
        1 => 'セブンイレブン',
        2 => 'ローソン、セイコーマート、ファミリーマート、ミニストップ',
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
     * コミケ申込データサービス
     * @var Sgmov_Service_Prefecture
     */
    private $_ComiketService;

    /**
     * コミケ申込データサービス
     * @var Sgmov_Service_Prefecture
     */
    private $_ComiketDetailService;

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

    /**
     * 郵便番号DLLサービス
     * @var Sgmov_Service_HttpsZipCodeDll
     */
    private $_HttpsZipCodeDll;

    /**
     * 宅配運賃マスタサービス
     * @var Sgmov_Service_BoxFare
     */
    private $_BoxFareService;

    /**
     * 貸切マスタサービス
     * @var Sgmov_Service_Charter
     */
    protected $_CharterService;

    /**
     * カーゴ運賃マスタサービス
     * @var Sgmov_Service_CarhoFare
     */
    protected $_CargoFareService;

    /**
     * イベントデータコンバート情報を扱います。
     * @var type
     */
    protected $_EventsubCmbService;

    /**
     * 郵便番号DLLサービス
     * @var Sgmov_Service_SocketZipCodeDll
     */
    protected $_SocketZipCodeDll;

    /**
     * 時間帯サービス
     * @var type
     */
    private $_TimeService;

    // 識別子
    protected $_DirDiv;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        $this->_appCommon             = new Sgmov_Service_AppCommon();
        $this->_ComiketService = new Sgmov_Service_Comiket();
        $this->_ComiketDetailService = new Sgmov_Service_ComiketDetail();
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
        for ($i = 1; $i <= 99; $i++) {
            $comiketCargoItemList[$i] = $i;
        }

        $this->comiket_cargo_item_list = $comiketCargoItemList;
    }

    /**
     * 表示用時刻を取得する
     * @param object $db
     * @return
     */
    protected function _fetchTime($begin, $end, $step = 1)
    {
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
    public static function _createPulldown($cds, $lbls, $select, $flg = null, $date = null)
    {

        $html = '';

        if (empty($cds)) {
            return $html;
        }

        $count = count($cds);
        for ($i = 0; $i < $count; ++$i) {

            // $flg（受付時間超過フラグ）があるならプロパティ文字列を作成する
            $timeover = '';
            if (!empty($flg)) {
                $timeover = ' timeoverflg="' . $flg[$i] . '"';
            }

            // $date（受付終了日付）があるならプロパティ文字列を作成する
            $timeoverDt = '';
            if (!empty($date)) {
                $timeoverDt = ' timeoverdate="' . $date[$i] . '"';
            }

            if ($select === $cds[$i]) {
                $html .= '<option value="' . $cds[$i] . '" selected="selected"' . $timeover . $timeoverDt . '>' . $lbls[$i] . '</option>' . PHP_EOL;
            } else {
                $html .= '<option value="' . $cds[$i] . '"' . $timeover . $timeoverDt . '>' . $lbls[$i] . '</option>' . PHP_EOL;
            }
        }

        return $html;
    }

    public static function _getWeek($year, $month, $day)
    {
        $week = array("日", "月", "火", "水", "木", "金", "土");
        $resultWeek = $week[date('w', strtotime("{$year}-{$month}-{$day}"))];
        return $resultWeek;
    }

    public static function _getTimeFormatSelectPulldownData($cds, $lbls, $select)
    {

        $html = '';

        if (empty($cds)) {
            return $html;
        }

        $count = count($cds);
        for ($i = 0; $i < $count; ++$i) {
            if ($select === $cds[$i]) {
                $timeInfo = explode('-', $cds[$i]);
                if (count($timeInfo) != 2) {
                    return "指定なし";
                }
                return date('H:i', strtotime($timeInfo[0])) . "～" . date('H:i', strtotime($timeInfo[1]));
            }
        }

        return "";
    }

    public static function _getTimeFormatSelectPulldownDataForInBound($cds, $lbls, $select)
    {

        $html = '';

        if (empty($cds)) {
            return $html;
        }

        $count = count($cds);
        for ($i = 0; $i < $count; ++$i) {
            if ($select === $cds[$i]) {
                $arrCds = explode(',', $cds[$i]);
                $arrTimezoneInfo = explode('～', $arrCds[1]);

                if ($arrCds[0] == '00') {
                    return "指定なし";
                } else if ($arrCds[0] == '11') {
                    return "午前中";
                }

                return $arrTimezoneInfo[0] . "～" . $arrTimezoneInfo[1];
            }
        }

        return "";
    }

    public static function _getCodeSelectPulldownData($cds, $lbls, $select)
    {

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

    public static function _getLabelSelectPulldownData($cds, $lbls, $select)
    {

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
    public static function _createPulldownAddDate($cds, $lbls, $select, $dates = null)
    {

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
    public static function _createPulldownAddDiscount($cds, $lbls, $select, $discount = null)
    {

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
    protected static function _getDiscount($checkDeparture, $checkArrival, $db, $_TravelService, $_CruiseRepeater, $inForm)
    {

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
    public static function _getEventData($eventList, $id)
    {
        foreach ($eventList as $val) {
            if ($val["id"] == $id) {
                return $val;
            }
        }
        return array();
    }

    /**
     *
     * @param type $dispItemInfo
     * @param type $dataList
     * @param type $index
     * @return type
     */
    protected function setBoxName($dataList)
    {

        $returnList = [];
        foreach ($dataList as $key => $value) {
            $returnList[$key]['id'] = $value['id'];
            $returnList[$key]['cd'] = $value['cd'];
            $returnList[$key]['eventsub_id'] = $value['eventsub_id'];
            $returnList[$key]['size'] = $value['size'];
            $returnList[$key]['name'] = $value['name_display'];
            $returnList[$key]['size_display'] = $value['size_display'];
            if (empty($value['name_display'])) {
                $returnList[$key]['name'] = $value['name'];
            }
        }

        return $returnList;
    }

    /**
     *
     * @param type $inForm
     * @param Sgmov_Form_Qrc001Out $outForm
     * @return type
     */
    protected function createOutFormByInForm($inForm, $outForm = array())
    {
        $dispItemInfo = array();

        $inForm = (array)$inForm;

        $db = Sgmov_Component_DB::getPublic();

        $eventAll = $this->_EventService->fetchEventListWithinTerm2($db);

        $eventAll2 = array();
        $eventIds = array();
        $eventNames = array();
        $eventNames2 = array();

        $week = array("日", "月", "火", "水", "木", "金", "土");
        foreach ($eventAll as $key => $val) {
            $eventIds[] = $val["id"];
            $eventNames[] = $val["name"];
            $eventNames2[] = $val["event_name"] . "　" . $val["eventsub_name"];
            $eventAll2[] = $val;
        }

        $outForm->raw_comiket_id  = @$inForm['comiket_id'];

        // 出展イベント
        $dispItemInfo["event_alllist"] = $eventAll2;
        $outForm->raw_event_cds  = $eventIds;
        $outForm->raw_event_lbls = $eventNames2;
        $outForm->raw_event_cd_sel = $inForm["event_sel"];

        // 出展イベントサブ
        $eventsubAry2 = $this->_EventsubService->fetchEventsubListWithinTermByEventId($db, $inForm["event_sel"]);


        /////////////////////////////////////////////////////////////////////////////////////////////////////////
        // 入力モード制御
        /////////////////////////////////////////////////////////////////////////////////////////////////////////

        $outForm->raw_input_mode = $inForm['input_mode'];
        if (!empty($inForm['input_mode']) && !empty($eventsubAry2)) {
            // イベントIDは上記にて設定済み
            $eventsubList = $eventsubAry2["list"];
            $inForm['eventsub_sel'] = $eventsubList[0]['id'];
        }

        // イベントサブリストをループで回し、１つ１つ申込受付時間が過ぎていないか確認。過ぎているならフラグをセット
        $sysdate = new DateTime();
        foreach ($eventAll2 as $data) {
            $eventSubData = $this->_EventsubService->fetchEventsubListWithinTermByEventId($db, $data['id']);

            foreach ($eventSubData['list'] as $key => $value) {
                $arrvalDt = $eventSubData['list'][$key]['arrival_to_time'];
                $eveEndDt   = date('U', strtotime($arrvalDt));
                $currentDt  = intval($sysdate->format('U'));

                if ($eveEndDt < $currentDt) {
                    array_push($outForm->eve_entry_timeover_flg, "1");
                } else {
                    array_push($outForm->eve_entry_timeover_flg, "0");
                }

                // 申込終了時間を文字列化してフォームにセット
                $strWeek = $this->_getWeek(date('Y', strtotime($arrvalDt)), date('m', strtotime($arrvalDt)), date('d', strtotime($arrvalDt)));
                array_push($outForm->eve_entry_timeover_date, date('Y年n月j日', strtotime($arrvalDt)) . '（' . $strWeek . '）' . date('H:i', strtotime($arrvalDt)));
            }
        }
        $eventsubAry3 = array();
        $dispItemInfo["eventsub_selected_data"] = "";
        if (!empty($eventsubAry2)) {
            $outForm->raw_eventsub_cds  = $eventsubAry2['ids'];
            $outForm->raw_eventsub_lbls = $eventsubAry2['names'];
            $outForm->raw_eventsub_cd_sel = $inForm["eventsub_sel"];

            $inboundHatsuJis2 = "";
            $inboundChakuJis2 = "";
            $eventsubCmbAry = $this->_EventsubCmbService->cmbEventsubList($eventsubAry2["list"], $inForm["eventsub_sel"], $inboundHatsuJis2, $inboundChakuJis2);

            $eventsubAry3 = $eventsubCmbAry["list"];
            $dispItemInfo["eventsub_selected_data"] = $eventsubCmbAry["selectedData"];
        }
        $dispItemInfo["eventsub_list"] = $eventsubAry3;


        if (@!empty($dispItemInfo["eventsub_selected_data"])) {
            $outForm->raw_eventsub_zip = $dispItemInfo["eventsub_selected_data"]["zip"];
            // 場所(イベント)
            $outForm->raw_eventsub_address = $dispItemInfo["eventsub_selected_data"]["address"];
        }

        // 期間（イベント）
        if (!empty($inForm["event_sel"])) {
            if (!empty($dispItemInfo["eventsub_selected_data"])) {
                $outForm->raw_eventsub_term_fr = $inForm["eventsub_term_fr"] = $dispItemInfo["eventsub_selected_data"]["term_fr"];
                $outForm->raw_eventsub_term_to = $inForm["eventsub_term_to"] = $dispItemInfo["eventsub_selected_data"]["term_to"];

                // ▼ 以下で、お預かり・お届け日が同じ場合は、設定しておく（画面側では、レベル表示のため（変更不可））


                ////////////////////////////////////////////////////////////////////////////////
                // 搬出
                ////////////////////////////////////////////////////////////////////////////////
                if ($dispItemInfo["eventsub_selected_data"]["is_eq_inbound_collect"]) {
                    $inForm["comiket_detail_inbound_collect_date_year_sel"] = $dispItemInfo["eventsub_selected_data"]["inbound_collect_fr_year"];
                    $inForm["comiket_detail_inbound_collect_date_month_sel"] = $dispItemInfo["eventsub_selected_data"]["inbound_collect_fr_month"];
                    $inForm["comiket_detail_inbound_collect_date_day_sel"] = $dispItemInfo["eventsub_selected_data"]["inbound_collect_fr_day"];
                    $comiketDetailInboundCollectDate = new DateTime("{$inForm["comiket_detail_inbound_collect_date_year_sel"]}-{$inForm["comiket_detail_inbound_collect_date_month_sel"]}-{$inForm["comiket_detail_inbound_collect_date_day_sel"]}");
                    $comiketDetailInboundCollectDateFormat = $comiketDetailInboundCollectDate->format('Y-m-d');
                    $today = new DateTime();
                    $todayFormat = $today->format("Y-m-d");

                    if ($comiketDetailInboundCollectDateFormat <= $todayFormat) {
                        $dispItemInfo["eventsub_selected_data"]["inbound_collect_fr_year"] = $inForm["comiket_detail_inbound_collect_date_year_sel"] = $today->format('Y');
                        $dispItemInfo["eventsub_selected_data"]["inbound_collect_fr_month"] = $inForm["comiket_detail_inbound_collect_date_month_sel"] = $today->format('m');
                        $dispItemInfo["eventsub_selected_data"]["inbound_collect_fr_day"] = $inForm["comiket_detail_inbound_collect_date_day_sel"] = $today->format('d');
                    }
                }

                if ($dispItemInfo["eventsub_selected_data"]["is_eq_inbound_delivery"]) {
                    $inForm["comiket_detail_inbound_delivery_date_year_sel"] = $dispItemInfo["eventsub_selected_data"]["inbound_delivery_fr_year"];
                    $inForm["comiket_detail_inbound_delivery_date_month_sel"] = $dispItemInfo["eventsub_selected_data"]["inbound_delivery_fr_month"];
                    $inForm["comiket_detail_inbound_delivery_date_day_sel"] = $dispItemInfo["eventsub_selected_data"]["inbound_delivery_fr_day"];
                }
            }
            $outForm->raw_eventsub_term_fr_nm = date('Y年m月d日（' . $week[date('w', strtotime($inForm["eventsub_term_fr"]))] . '）', strtotime($inForm["eventsub_term_fr"]));
            $outForm->raw_eventsub_term_to_nm = date('Y年m月d日（' . $week[date('w', strtotime($inForm["eventsub_term_to"]))] . '）', strtotime($inForm["eventsub_term_to"]));

            // イベントはお預かり日が固定なので時間帯も固定に設定
            $dispItemInfo['eventsub_selected_data']['collect_timeframe'] = self::COLLECT_TIMEFRAME;
        }

        // 識別（法人・個人）
        $outForm->raw_comiket_div = $inForm["comiket_div"];

        $dispItemInfo["comiket_div_lbls"] = $this->comiket_div_lbls;

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
        if (@!empty($inForm["comiket_pref_cd_sel"])) {
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
        //        $buildingBoothPostionInfoAry = $this->_BuildingService->fetchBuildingBoothPostionByBuildingCd($db, $inForm["building_name_sel"], $inForm["eventsub_sel"]);
        $buildingListByEventsubId = $this->_BuildingService->fetchBuildingDataByEventsubId($db, $inForm["eventsub_sel"]);
        $boothPostionIds = array();
        $boothPostionLbls = array();
        foreach ($buildingListByEventsubId as $key => $val) {
            $boothPostionIds[] =  $val['id'];
            $boothPostionLbls[] = $val['booth_position'];
        }
        $outForm->raw_building_booth_position = $inForm["building_booth_position"]; // 編集画面のラベル表示用
        $outForm->raw_building_booth_position_sel = $inForm["building_booth_position_sel"];
        $outForm->raw_building_booth_position_ids = $boothPostionIds;
        $outForm->raw_building_booth_position_lbls = $boothPostionLbls;

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

        // 搬出選択
        $outForm->raw_comiket_detail_type_sel = $inForm["comiket_detail_type_sel"];

        $dispItemInfo["comiket_detail_type_lbls"] = $this->comiket_detail_type_lbls;

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // 搬出共通
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // 搬出-サービス選択
        $dispItemInfo["comiket_detail_service_lbls"] = $this->comiket_detail_service_lbls;

        // 搬出-各種サービス
        $dispItemInfo["inbound_box_lbls"] = array();
        $dataList = [];

        $dataList = $this->_BoxService->fetchBox2($db, $inForm["eventsub_sel"], $inForm["comiket_div"], "2"); // 搬出

        $dispItemInfo["inbound_box_lbls"] = $this->setBoxName($dataList);

        $typeSel = array("1", "2");

        $dispItemInfo["cargo_lbls"] = $this->_CargoService->fetchCargo($db);
        $dispItemInfo["charter_lbls"] = $this->_CharterService->fetchCharter($db);



        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // 搬出
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $date = new DateTime();

        // お預かり日時-時間帯
        $comiket_detail_time_lbls = $this->comiket_detail_time_lbls;

        $comiket_detail_time_lbls_par30m = $this->comiket_detail_time_lbls_par30m;

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
        $years  = $this->_appCommon->getYears($date->format('Y'), 0, false);
        $months = array('', '11');
        $days   = array('', '16', '17');
        array_shift($months);
        array_shift($days);

        // 預かり日選択可能
        if (self::COLLECT_YEAR == '' || self::COLLECT_MONTH == '' || self::COLLECT_DAY == '') {
            $outForm->raw_comiket_detail_inbound_collect_date_year_sel = $inForm["comiket_detail_inbound_collect_date_year_sel"];
            $outForm->raw_comiket_detail_inbound_collect_date_month_sel = $inForm["comiket_detail_inbound_collect_date_month_sel"];
            $outForm->raw_comiket_detail_inbound_collect_date_day_sel = $inForm["comiket_detail_inbound_collect_date_day_sel"];
        }
        // 預かり日が固定
        else {
            $outForm->raw_comiket_detail_inbound_collect_date_year_sel  = self::COLLECT_YEAR;
            $outForm->raw_comiket_detail_inbound_collect_date_month_sel = self::COLLECT_MONTH;
            $outForm->raw_comiket_detail_inbound_collect_date_day_sel   = self::COLLECT_DAY;
        }

        $outForm->raw_comiket_detail_inbound_collect_date_year_cds = $years;
        $outForm->raw_comiket_detail_inbound_collect_date_year_lbls = $years;
        $outForm->raw_comiket_detail_inbound_collect_date_month_cds = $months;
        $outForm->raw_comiket_detail_inbound_collect_date_month_lbls = $months;
        $outForm->raw_comiket_detail_inbound_collect_date_day_cds = $days;
        $outForm->raw_comiket_detail_inbound_collect_date_day_lbls = $days;

        // 搬出-お預かり日時-時間帯
        $outForm->raw_comiket_detail_inbound_collect_time_sel = $inForm["comiket_detail_inbound_collect_time_sel"];
        $outForm->raw_comiket_detail_inbound_collect_time_cds = array_keys($comiket_detail_time_lbls_par30m);
        $outForm->raw_comiket_detail_inbound_collect_time_lbls = array_values($comiket_detail_time_lbls_par30m);

        // 搬出-お届け日時
        $years  = $this->_appCommon->getYears($date->format('Y'), 0, false);
        $months = array('', '11');
        $days   = array('', '19', '20', '21', '22', '23', '24');
        array_shift($months);
        array_shift($days);

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
            $this->comiket_detail_delivery_timezone[$timeData['cd'] . ',' . $timeData['name']] = $timeData['name'];
        }

        // 搬出-お届け日時-時間帯
        $outForm->raw_comiket_detail_inbound_delivery_time_sel = $inForm["comiket_detail_inbound_delivery_time_sel"];
        $outForm->raw_comiket_detail_inbound_delivery_time_cds = array_keys($this->comiket_detail_delivery_timezone);
        $outForm->raw_comiket_detail_inbound_delivery_time_lbls = array_values($this->comiket_detail_delivery_timezone);

        // 搬出-サービス選択
        $outForm->raw_comiket_detail_inbound_service_sel = $inForm["comiket_detail_inbound_service_sel"];

        // 搬出-宅配
        $outForm->raw_comiket_box_inbound_num_ary = $inForm["comiket_box_inbound_num_ary"];

        // 搬出-カーゴ
        $outForm->raw_comiket_cargo_inbound_num_sel = $inForm["comiket_cargo_inbound_num_sel"];
        $outForm->raw_comiket_cargo_inbound_num_cds = array_keys($this->comiket_cargo_item_list);
        $outForm->raw_comiket_cargo_inbound_num_lbls = array_values($this->comiket_cargo_item_list);

        // 搬出-チャーター
        $outForm->raw_comiket_charter_inbound_num_ary = $inForm["comiket_charter_inbound_num_ary"];

        // 搬出-備考-1行目
        $outForm->raw_comiket_detail_inbound_note1 = $inForm["comiket_detail_inbound_note1"];

        // 搬出-備考-2行目
        $outForm->raw_comiket_detail_inbound_note2 = $inForm["comiket_detail_inbound_note2"];

        // 搬出-備考-3行目
        $outForm->raw_comiket_detail_inbound_note3 = $inForm["comiket_detail_inbound_note3"];

        // 搬出-備考-4行目
        $outForm->raw_comiket_detail_inbound_note4 = $inForm["comiket_detail_inbound_note4"];

        /////////////////////////////////////////////////////////////////////////////////////////////////////////
        // 支払
        /////////////////////////////////////////////////////////////////////////////////////////////////////////
        // 送料
        $outForm->raw_delivery_charge = @empty($inForm["delivery_charge"]) ? 0 : $inForm["delivery_charge"];

        // リピータ割引
        $outForm->raw_repeater_discount = $inForm["repeater_discount"];

        // お支払方法コード選択値
        $outForm->raw_comiket_payment_method_cd_sel = $inForm["comiket_payment_method_cd_sel"];

        // クレジットカード番号
        $outForm->raw_card_number = $inForm["card_number"];

        // 有効期限 月
        $outForm->raw_card_expire_month_cd_sel = $inForm["card_expire_month_cd_sel"];

        // 有効期限 年
        $outForm->raw_card_expire_year_cd_sel = $inForm["card_expire_year_cd_sel"];

        // セキュリティコード
        $outForm->raw_security_cd = $inForm["security_cd"];

        //QRコード GETパラメータのセット
        $outForm->raw_qr_toiawase_no = $inForm["qr_toiawase_no"];
        $outForm->raw_qr_toiban = $inForm["qr_toiban"];
        $outForm->raw_qr_uriage_kingaku = $inForm["qr_uriage_kingaku"];
        //これがカードの決済金額となる
        $outForm->raw_delivery_charge = $inForm["qr_uriage_kingaku"];
        //巡り巡って決済金額が消えてしまうので再投入
        $inForm['delivery_charge'] = $inForm["qr_uriage_kingaku"];

        return array(
            "outForm" => $outForm, "dispItemInfo" => $dispItemInfo
        );
    }

    /**
     * 住所情報を取得します。
     * @param type $zip
     * @param type $address
     * @return type
     */
    public function _getAddress($zip, $address)
    {
        return $this->_SocketZipCodeDll->searchByAddress($address, $zip);
    }

    /**
     *
     * @param type $inForm
     */
    public function setYubinDllInfoToInForm(&$inForm)
    {
        $db = Sgmov_Component_DB::getPublic();

        $eventsubData = $this->_EventsubService->fetchEventsubByEventsubId($db, $inForm["eventsub_sel"]);
        $resultEventZipDll = $this->_getAddress(@$eventsubData["zip"], @$eventsubData["address"]);

        if ($inForm['comiket_detail_type_sel'] == "2") { // 搬出
            $inForm["inbound_hatsu_jis2code"] = @$resultEventZipDll["JIS2Code"];
            $inForm["inbound_hatsu_jis5code"] = @$resultEventZipDll["JIS5Code"];
            $inForm["inbound_hatsu_shop_check_code"] = @$resultEventZipDll["ShopCheckCode"];
            $inForm["inbound_hatsu_shop_check_code_eda"] = @$resultEventZipDll["ShopCheckCodeEda"];
            $inForm["inbound_hatsu_shop_code"] = @$resultEventZipDll["ShopCode"];
            $inForm["inbound_hatsu_shop_local_code"] = @$resultEventZipDll["ShopLocalCode"];


            $resultInboundPrefData = $this->_PrefectureService->fetchPrefecturesById($db, $inForm['comiket_detail_inbound_pref_cd_sel']);

            $resultInboundZipDll = $this->_getAddress(
                @$inForm['comiket_detail_inbound_zip1'] . @$inForm['comiket_detail_inbound_zip2'],
                @$resultInboundPrefData["name"] . @$inForm['comiket_detail_inbound_address'] . @$inForm['comiket_detail_inbound_building']
            );

            $inForm["inbound_chaku_jis2code"] = @$resultInboundZipDll["JIS2Code"];
            $inForm["inbound_chaku_jis5code"] = @$resultInboundZipDll["JIS5Code"];
            $inForm["inbound_chaku_shop_check_code"] = @$resultInboundZipDll["ShopCheckCode"];
            $inForm["inbound_chaku_shop_check_code_eda"] = @$resultInboundZipDll["ShopCheckCodeEda"];
            $inForm["inbound_chaku_shop_code"] = @$resultInboundZipDll["ShopCode"];
            $inForm["inbound_chaku_shop_local_code"] = @$resultInboundZipDll["ShopLocalCode"];
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    }

    protected function setInboundYubinDllInfoToInForm(&$inForm)
    {
    }

    /**
     *
     * @param type $inForm
     */
    protected function calcEveryKindData($inForm, $comiketId = "", $isAmountDataFromSession = false)
    {
        /////////////////////////////////////////////////////////////////////////////////
        // 送料計算
        /////////////////////////////////////////////////////////////////////////////////

        $fareTaxTotal = 0;
        // DB接続
        $db = Sgmov_Component_DB::getPublic();

        $this->setYubinDllInfoToInForm($inForm);

        $tableDataInfo = $this->_cmbTableDataFromInForm($inForm, $comiketId);

        $tableTreeData = $tableDataInfo["treeData"];
        $sessionTableTreeData = @$_SESSION[dirname(__FILE__) . "_treeData"];

        /////////////////////////////////////////////////////////////////////////////////
        // 配送料計算
        /////////////////////////////////////////////////////////////////////////////////

        $tableTreeData['amount_tax'] = 0;
        $tableTreeData['amount'] = 0;

        $procList = array(
            'tableTreeData' => $tableTreeData,
        );

        $resultList = array();

        foreach ($procList as $keyTree => $valTree) {

            $valTree['amount_tax'] = $valTree['amount'] = 0;
            // $valTree['amount_tax'] = $valTree['amount'] = 0;
            $detailAmountTotal = 0;
            $detailAmountTaxTotal = 0;
            foreach ($valTree["comiket_detail_list"] as $keyDet => $valDet) {
                /////////////////////////////////////////////////////////////////////////////////
                // 税抜計算
                /////////////////////////////////////////////////////////////////////////////////
                $detailAmountTotal += @empty($valDet['fare']) ? 0 : $valDet['fare'];
                $detailAmountTotal += @empty($valDet['cost']) ? 0 : $valDet['cost'];

                /////////////////////////////////////////////////////////////////////////////////
                // 税込計算
                /////////////////////////////////////////////////////////////////////////////////
                $detailAmountTaxTotal += @empty($valDet['fare_tax']) ? 0 : $valDet['fare_tax'];
                $detailAmountTaxTotal += @empty($valDet['cost_tax']) ? 0 : $valDet['cost_tax'];
            }
            // $valTree['amount'] = $detailAmountTotal;
            // $valTree['amount_tax'] = $detailAmountTaxTotal;
            $valTree['amount'] = 0;
            // $valTree['amount_tax'] = @$inForm['URIAGE_KINGAKU'];
            $valTree['amount_tax'] = @$inForm['qr_uriage_kingaku'];

            $resultList[$keyTree] = $valTree;
        }

        $tableTreeData = $resultList['tableTreeData'];
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $flatDataInfo = $this->getFlatData($tableTreeData);

        $_SESSION[dirname(__FILE__) . "_treeData"] = $tableTreeData;
        return array(
            "treeData" => $tableTreeData,
            "flatData" => $flatDataInfo,
        );
    }

    /**
     *
     * @param type $comiketDetailDataList
     */
    private function getFlatData($comiketData)
    {

        $comiketDetailDataList = $comiketData["comiket_detail_list"];

        $comiketBoxDataList = array();
        foreach ($comiketDetailDataList as $key => $val) {
            if (isset($val["comiket_box_list"])) {
                foreach ($val["comiket_box_list"] as $key2 => $val2) {
                    $comiketBoxDataList[] = $val2;
                }
            }
        }

        $comiketCargoDataList = array();
        foreach ($comiketDetailDataList as $key => $val) {
            if (isset($val["comiket_cargo_list"])) {
                foreach ($val["comiket_cargo_list"] as $key2 => $val2) {
                    $comiketCargoDataList[] = $val2;
                }
            }
        }

        $comiketCharterDataList = array();
        foreach ($comiketDetailDataList as $key => $val) {
            if (isset($val["comiket_charter_list"])) {
                foreach ($val["comiket_charter_list"] as $key2 => $val2) {
                    $comiketCharterDataList[] = $val2;
                }
            }
        }

        return array(
            "comiketData" => $comiketData,
            "comiketDetailDataList" => $comiketDetailDataList,
            "comiketBoxDataList" => $comiketBoxDataList,
            "comiketCargoDataList" => $comiketCargoDataList,
            "comiketCharterDataList" => $comiketCharterDataList,
        );
    }

    /**
     *
     * @param type $inForm
     * @param type $comiketId
     * @return type
     */
    public function _cmbTableDataFromInForm($inForm, $comiketId = "")
    {


        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // comiket データ作成
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $comiketDataForHaiso = $this->_createComiketInsertDataByInForm($inForm, $comiketId);

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // comiket_detail データ作成
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $comiketDetailDataList = $this->_createComiketDetailInsertDataByInForm($inForm, $comiketId);
        $comiketDetailDataListForHaiso = array();
        // 配送用
        foreach ($comiketDetailDataList as $key => $val) {
            $comiketDetailDataListForHaiso[] = $val;
        }
        $comiketDataForHaiso["comiket_detail_list"] = $comiketDetailDataListForHaiso;

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // comiket_box データ作成
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $comiketBoxDataList = $this->_createComiketBoxInsertDataByInForm($inForm, $comiketId);
        $comiketBoxDataListForHaiso = array();
        // 配送用
        foreach ($comiketBoxDataList as $key => $val) {
            $comiketBoxDataListForHaiso[] = $val;
        }
        // [配送用] comiket_box 設定
        foreach ($comiketBoxDataListForHaiso as $key => $val) {
            foreach ($comiketDataForHaiso["comiket_detail_list"] as $key2 => $val2) {
                if ($comiketDataForHaiso["comiket_detail_list"][$key2]["type"] == $val["type"]) {
                    $comiketDataForHaiso["comiket_detail_list"][$key2]["comiket_box_list"][$key] = $val;
                }
            }
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // comiket_cargo データ作成 ※ 配送のみ
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $comiketCargoDataListForHaiso = $this->_createComiketCargoInsertDataByInForm($inForm, $comiketId);

        foreach ($comiketCargoDataListForHaiso as $key => $val) {
            foreach ($comiketDataForHaiso["comiket_detail_list"] as $key2 => $val2) {
                if ($comiketDataForHaiso["comiket_detail_list"][$key2]["type"] == $val["type"]) {
                    $comiketDataForHaiso["comiket_detail_list"][$key2]["comiket_cargo_list"][$key] = $val;
                }
            }
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // comiket_cargo データ作成 ※ 配送のみ
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $comiketCharterDataListForHasio = $this->_createComiketCharterInsertDataByInForm($inForm, $comiketId);

        foreach ($comiketCharterDataListForHasio as $key => $val) {
            foreach ($comiketDataForHaiso["comiket_detail_list"] as $key2 => $val2) {
                if ($comiketDataForHaiso["comiket_detail_list"][$key2]["type"] == $val["type"]) {
                    $comiketDataForHaiso["comiket_detail_list"][$key2]["comiket_charter_list"][$key] = $val;
                }
            }
        }


        return array(
            //////////////////////////////////////////////////////////////////////////////////////////////
            // 配送用
            //////////////////////////////////////////////////////////////////////////////////////////////
            "treeData" => $comiketDataForHaiso,
            "flatData" => array(
                "comiketData" => $comiketDataForHaiso,
                "comiketDetailDataList" => $comiketDetailDataListForHaiso,
                "comiketBoxDataList" => $comiketBoxDataListForHaiso,
                "comiketCargoDataList" => $comiketCargoDataListForHaiso,
                "comiketCharterDataList" => $comiketCharterDataListForHasio,
            ),
        );
    }

    /**
     * 顧客コード取得
     * @param type $eventSel
     * @return string
     */
    private function getCustomerCd($eventSel)
    {

        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        $eventInfo = $this->_EventService->fetchEventInfoByEventId($db, $eventSel);

        return $eventInfo['customer_cd'];
    }


    public function _createComiketInsertDataByInForm($inForm, $id, $type = "")
    {
        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        // $batch_status = '1';
        $batch_status = '4';

        $customerCd = $inForm['comiket_customer_cd'];
        $merchantResult = @$inForm['merchant_result'];
        if ($inForm['comiket_div'] == self::COMIKET_DEV_BUSINESS) { // 法人
            $inForm['comiket_personal_name_sei'] = "";
            $inForm['comiket_personal_name_mei'] = "";

            $inForm['comiket_payment_method_cd_sel'] = "5";  // 念のため
            $inForm['comiket_convenience_store_cd_sel'] = NULL;
            $inForm['payment_order_id'] = NULL;
            $inForm['authorization_cd'] = NULL;
        } else { // 個人
            $customerCd = $this->getCustomerCd($inForm['event_sel']);

            $inForm['office_name'] = "";

            //$inForm['comiket_payment_method_cd_sel'] = '2';

            if ($inForm['comiket_payment_method_cd_sel'] == '1') { // コンビニ前払
                $inForm['authorization_cd'] = NULL;
            } else if ($inForm['comiket_payment_method_cd_sel'] == '2') { // クレジット
                $inForm['comiket_convenience_store_cd_sel'] = NULL;
            } else if ($inForm['comiket_payment_method_cd_sel'] == '4') { // コンビニ後払い

                $inForm['comiket_convenience_store_cd_sel'] = NULL;
                $inForm['payment_order_id'] = NULL;
                $inForm['authorization_cd'] = NULL;
            } else { // 電子マネー  と 法人売掛

                $inForm['comiket_convenience_store_cd_sel'] = NULL;
                $inForm['payment_order_id'] = NULL;
                $inForm['authorization_cd'] = NULL;
            }
        }

        if (!empty($inForm["comiket_id"])) {
            $comiketInfo = $this->_ComiketService->fetchComiketById($db, $inForm["comiket_id"]);
            $comiketInfo["id"] = $id;
            unset($comiketInfo["created"]);
            unset($comiketInfo["modified"]);
            $comiketInfo["choice"] = $inForm['comiket_detail_type_sel'];

            $comiketInfo["merchant_result"] = @$inForm['merchant_result'];
            $comiketInfo["merchant_datetime"] = @$inForm['merchant_datetime'];
            $comiketInfo["receipted"] = @$inForm['receipted'];
            $comiketInfo["send_result"] = "3";
            $comiketInfo["sent"] = date('Y-m-d H:i:s');
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

            $customerCd = $this->getCustomerCd($comiketInfo['event_id']);
            $comiketInfo['customer_cd'] = $customerCd;

            return $comiketInfo;
        }

        $buildingNameRes = $this->_BuildingService->fetchBuildingNameByCd($db, $inForm['building_name_sel'], $inForm['eventsub_sel']);
        $buildingInfo = $this->_BuildingService->fetchBuildingById($db, $inForm['building_booth_position_sel']);

        $data = array(
            "id" => $id,
            "merchant_result" => @$inForm['merchant_result'],
            "merchant_datetime" => @$inForm['merchant_datetime'],
            "receipted" => @$inForm['receipted'],
            "send_result" => "3",
            "sent" => date('Y-m-d H:i:s'),
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
            "building_name" => null,
            "booth_position" => null,
            "booth_num" => null,
            // "staff_sei" => @empty($inForm['comiket_staff_sei']) ? "　" : $inForm['comiket_staff_sei'],
            // "staff_mei" => @empty($inForm['comiket_staff_mei']) ? "　" : $inForm['comiket_staff_mei'],
            "staff_sei" => @empty($inForm['comiket_personal_name_sei']) ? "　" : $inForm['comiket_personal_name_sei'],
            "staff_mei" => @empty($inForm['comiket_personal_name_mei']) ? "　" : $inForm['comiket_personal_name_mei'],
            "staff_sei_furi" => @empty($inForm['comiket_staff_sei_furi']) ? "-" : $inForm['comiket_staff_sei_furi'],
            "staff_mei_furi" => @empty($inForm['comiket_staff_mei_furi']) ? "-" : $inForm['comiket_staff_mei_furi'],
            //入力項目なし 当日担当者電話番号
            // "staff_tel" => @empty($inForm['comiket_staff_tel']) ? "00000000000" : $inForm['comiket_staff_tel'],
            //申込登録者の電話番号
            // "staff_tel" => @empty($inForm['comiket_detail_inbound_tel']) ? "00000000000" : $inForm['comiket_tel'],
            //配送先の電話番号
            "staff_tel" => @empty($inForm['comiket_detail_inbound_tel']) ? "00000000000" : $inForm['comiket_detail_inbound_tel'],
            "choice" => $inForm['comiket_detail_type_sel'],
            "amount" => "0", // ?
            "amount_tax" => "0", // ?
            "create_ip" => $_SERVER["REMOTE_ADDR"],
            "modify_ip" => $_SERVER["REMOTE_ADDR"],
            "transaction_id" => @$inForm['sgf_res_transactionId'],
            "auto_authoriresult" => @$inForm['sgf_res_autoAuthoriresult'],
            "haraikomi_url" => @$inForm['payment_url'],
            "kounyuten_no" => @$inForm['sgf_res_shopOrderId'],
            "del_flg" => '0',
            "customer_kbn" => '1', //　バシ　追加　TODO
            "bpn_type" => '0',
            "uketsuke_no" => @$inForm['qr_uketsuke_no'],
            "toiawase_no" => @$inForm['qr_toiawase_no'],
            "ark_uketsuke_no" => @$inForm['qr_ark_uketsuke_no'],
            "kessai_meisai_id" => @$inForm['qr_kessai_meisai_id'],
            "uriage_kingaku" => @$inForm['qr_uriage_kingaku'],
            "system_kbn" => @$inForm['qr_system_kbn'],
            "cd" => @$inForm['qr_cd']
        );
        return $data;
    }

    public function _createComiketDetailInsertDataByInForm($inForm, $id)
    {
        $returnList = array();

        $customerCd = "";
        if (!empty($id)) {
            $customerCd = sprintf("%07d", $id);
        }

        $db = Sgmov_Component_DB::getPublic();

        if ($inForm['comiket_detail_type_sel'] == "2") { // 搬出

            if (
                empty($inForm['comiket_detail_inbound_collect_date_year_sel'])
                || empty($inForm['comiket_detail_inbound_collect_date_month_sel'])
                || empty($inForm['comiket_detail_inbound_collect_date_day_sel'])
            ) {
                $comiket_detail_inbound_collect_date = "";
            } else {
                $comiket_detail_inbound_collect_date =
                    $inForm['comiket_detail_inbound_collect_date_year_sel']
                    . '-' . $inForm['comiket_detail_inbound_collect_date_month_sel']
                    . '-' . $inForm['comiket_detail_inbound_collect_date_day_sel'];
            }

            if (
                empty($inForm['comiket_detail_inbound_delivery_date_year_sel'])
                || empty($inForm['comiket_detail_inbound_delivery_date_month_sel'])
                || empty($inForm['comiket_detail_inbound_delivery_date_day_sel'])
            ) {
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
            if ($inForm['comiket_div'] == self::COMIKET_DEV_BUSINESS && $inForm['comiket_detail_inbound_service_sel'] != "1") { // 法人 かつ　サービス が宅配ではない場合
                // 法人の場合は搬出のお届け日時がないため以下で設定
                $deliveryDate = null;
                $deliveryStTime = null;
                $deliveryEdTime = null;
            } else { // 個人

                $deliveryDate = null;
                if (!empty($comiket_detail_inbound_delivery_date)) {
                    $deliveryDate = $comiket_detail_inbound_delivery_date;
                }
                $deliveryTime = $inForm['comiket_detail_inbound_delivery_time_sel'];
                if (!empty($inForm['comiket_detail_inbound_delivery_time_sel'])) {
                    $arrTimezone = explode(',', $inForm['comiket_detail_inbound_delivery_time_sel']);
                    $inboundDeliveryTimeList = explode('～', $arrTimezone[1]);

                    if (empty($inboundDeliveryTimeList)) {
                        $deliveryStTime = null;
                        $deliveryEdTime = null;
                    } else if (count($inboundDeliveryTimeList) == 2) {
                        $deliveryStTime = $inboundDeliveryTimeList[0];
                        $deliveryEdTime = $inboundDeliveryTimeList[1];
                    } else if (count($inboundDeliveryTimeList) == 1) {
                        if ($arrTimezone[0] == "00" || $arrTimezone[0] == "11") {
                            $deliveryStTime = null;
                        } else {
                            $deliveryStTime = $inboundDeliveryTimeList[0];
                        }
                        $deliveryEdTime = null;
                    }
                }
            }

            if (!empty($inForm['comiket_detail_inbound_collect_time_sel'])) {
                $inboundCollectTimeList = explode('-', $inForm['comiket_detail_inbound_collect_time_sel']);

                if (empty($inboundCollectTimeList)) {
                    $collectStTime = null;
                    $collectEdTime = null;
                } else if (count($inboundCollectTimeList) == 2) {
                    $collectStTime = $inboundCollectTimeList[0];
                    $collectEdTime = $inboundCollectTimeList[1];
                } else if (count($inboundCollectTimeList) == 1) {
                    if ($inboundCollectTimeList[0] == "00") {
                        $collectStTime = null;
                    } else {
                        $collectStTime = $inboundCollectTimeList[0];
                    }
                    $collectEdTime = null;
                }
            }

            // TODO VASI
            // お預かり日時は、2020/10/23 16:00 21:00
            $collectStTime = null;
            $collectEdTime = null;


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
                "binshu_kbn" => '0'
            );

            $returnList[] = $data;
        }

        return $returnList;
    }

    public function _createComiketBoxInsertDataByInForm($inForm, $id)
    {

        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        $returnList = array();


        if ($inForm['comiket_detail_type_sel'] == "2") { // 搬出
            if ($inForm['comiket_detail_inbound_service_sel'] == "1") { // 宅配

                foreach ($inForm['comiket_box_inbound_num_ary'] as $key => $val) {

                    $boxData = $this->_BoxService->fetchBoxById($db, $key);
                    if (empty($val)) {
                        continue;
                    }

                    $boxFareData = @$this->_BoxFareService->fetchBoxFareByJis2AndBoxId($db, $inForm["inbound_hatsu_jis2code"], $inForm["inbound_chaku_jis2code"], $key, $inForm['eventsub_sel']);

                    if (empty($boxFareData)) {
                        $fareTax = 0;
                    } else {
                        $fareTax = intval($boxFareData["fare"]);
                    }

                    $fareAmountTax = $fareTax * intval($val);
                    $data = array(
                        "comiket_id" => $id,
                        "type" => "2", // 搬出
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

    public function _createComiketCargoInsertDataByInForm($inForm, $id)
    {

        $db = Sgmov_Component_DB::getPublic();

        $returnList = array();
        if ($inForm['comiket_detail_type_sel'] == "2") { // 搬出
            if ($inForm['comiket_detail_inbound_service_sel'] == "2") { // カーゴ
                $cargoFareData = @$this->_CargoFareService->fetchCargoFareByJis2AndCargoNum(
                    $db,
                    $inForm["inbound_hatsu_jis2code"],
                    $inForm["inbound_chaku_jis2code"],
                    $inForm["comiket_cargo_inbound_num_sel"],
                    $inForm["eventsub_sel"]
                ); // 13は東京(jis2code)

                $data = array(
                    "comiket_id" => $id,
                    "type" => "2", // 搬出
                    "num" => @$inForm["comiket_cargo_inbound_num_sel"],
                    "fare_amount" => @$cargoFareData["cargo_fare"],
                );
                $returnList[] = $data;
            }
        }
        return $returnList;
    }

    public function _createComiketCharterInsertDataByInForm($inForm, $id)
    {

        $db = Sgmov_Component_DB::getPublic();

        $returnList = array();

        if ($inForm['comiket_div'] != self::COMIKET_DEV_BUSINESS) { // 法人以外
            return $returnList;
        }

        if ($inForm['comiket_detail_type_sel'] == "2") { // 搬出
            if ($inForm['comiket_detail_inbound_service_sel'] == "3") { // チャーター
                unset($inForm['comiket_charter_inbound_num_ary'][0]);
                foreach ($inForm['comiket_charter_inbound_num_ary'] as $key => $val) {
                    if (empty($val)) {
                        continue;
                    }
                    $charterInfo = $this->_CharterService->fetchCharterById($db, $key);
                    $data = array(
                        "comiket_id" => $id,
                        "type" => "2", // 搬出
                        "name" => $charterInfo['name'],
                        "num" => "$val",
                    );
                    $returnList[] = $data;
                }
            }
        }

        return $returnList;
    }

    /**
     *
     * @param type $inForm
     * @return type
     */
    public function checkCurrentDateWithInTerm($inForm)
    {

        if (!is_array($inForm)) {
            $inForm = (array)$inForm;
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // 搬出の申込期間を過ぎていないかチェック
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if (@empty($inForm['comiket_detail_type_sel']) || @empty($inForm['eventsub_sel'])) {
            return;
        }

        if ($inForm['comiket_detail_type_sel'] == "1") { // 往路
            if (!$this->isCurrentDateWithInTerm("departure", $inForm['eventsub_sel'])) {
                Sgmov_Component_Redirect::redirectPublicSsl("/" . $this->_DirDiv . "/error_term/{$inForm['comiket_detail_type_sel']}");
                exit;
            }
        } else if ($inForm['comiket_detail_type_sel'] == "2") { // 復路
            if (!$this->isCurrentDateWithInTerm("arrival", $inForm['eventsub_sel'])) {
                Sgmov_Component_Redirect::redirectPublicSsl("/" . $this->_DirDiv . "/error_term/{$inForm['comiket_detail_type_sel']}");
                exit;
            }
        } else { // 往復
            $isDepartureErr = FALSE;
            $isArrivalErr = FALSE;
            if (!$this->isCurrentDateWithInTerm("departure", $inForm['eventsub_sel'])) {
                $isDepartureErr = TRUE;
            }

            if (!$this->isCurrentDateWithInTerm("arrival", $inForm['eventsub_sel'])) {
                $isArrivalErr = TRUE;
            }


            if ($isDepartureErr && $isArrivalErr) {
                Sgmov_Component_Redirect::redirectPublicSsl("/" . $this->_DirDiv . "/error_term/3"); // 往復エラー画面
                exit;
            } else if ($isDepartureErr) {
                Sgmov_Component_Redirect::redirectPublicSsl("/" . $this->_DirDiv . "/error_term/1"); // 往路エラー画面
                exit;
            } else if ($isArrivalErr) {
                Sgmov_Component_Redirect::redirectPublicSsl("/" . $this->_DirDiv . "/error_term/2"); // 復路エラー画面
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
    public function isCurrentDateWithInTerm($keyPrefix, $eventsubId)
    {
        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();
        $eventsubData = $this->_EventsubService->fetchEventsubByEventsubId($db, $eventsubId);

        $termFr = $eventsubData["{$keyPrefix}_fr"];
        $termFrDateTime = new DateTime($termFr);
        $termFrYMD = $termFrDateTime->format("Y-m-d");

        $currentDateTime = new DateTime('now');
        $currentYMDForFr = $currentDateTime->format("Y-m-d");
        if ($keyPrefix == 'arrival') {
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


        if ($termFrYMD <= $currentYMDForFr && $currentYMDForTo <= $termToYMD) {
            return TRUE;
        }
        return FALSE;
    }

    public static function getChkD($param)
    {

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
        );

        $total = 0;
        for ($i = 0; $i < count($intCheck); $i++) {
            $total += $param2[$i] * $intCheck[$i];
        }



        return $total;
    }

    public static function getChkD2($param)
    {

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
    public function checkColAndDelDate($inOutboundFlg, $comiketDiv, $serviceSel, $eventsubInfo)
    {

        if ($inOutboundFlg == "inbound") {
            // 個人・宅配・復路配達・日付指定
            if ($comiketDiv == self::COMIKET_DEV_INDIVIDUA && $serviceSel == '1' && $eventsubInfo['kojin_box_dlv_date_flg'] == '1') {
                return TRUE;
            }

            // 個人・カーゴ・復路配達・日付指定
            if ($comiketDiv == self::COMIKET_DEV_INDIVIDUA && $serviceSel  == '2' && $eventsubInfo['kojin_cag_dlv_date_flg'] == '1') {
                return TRUE;
            }

            // 法人・宅配・復路配達・日付指定
            if ($comiketDiv == self::COMIKET_DEV_BUSINESS && $serviceSel  == '1' && $eventsubInfo['hojin_box_dlv_date_flg'] == '1') {
                return TRUE;
            }

            // 法人・カーゴ・復路配達・日付指定
            if ($comiketDiv == self::COMIKET_DEV_BUSINESS && $serviceSel  == '2' && $eventsubInfo['hojin_cag_dlv_date_flg'] == '1') {
                return TRUE;
            }

            // 法人・貸切・復路配達・日付指定
            if ($comiketDiv == self::COMIKET_DEV_BUSINESS && $serviceSel  == '3' && $eventsubInfo['hojin_kas_dlv_date_flg'] == '1') {
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     *
     * @param type $check
     */
    public function checkColAndDelTime($inOutboundFlg, $comiketDiv, $serviceSel, $eventsubInfo)
    {
        if ($inOutboundFlg == "inbound") {
            // 個人・宅配・復路配達・時間指定
            if ($comiketDiv == self::COMIKET_DEV_INDIVIDUA && $serviceSel == '1' && $eventsubInfo['kojin_box_dlv_time_flg'] == '1') {
                return TRUE;
            }

            // 個人・カーゴ・復路配達・時間指定
            if ($comiketDiv == self::COMIKET_DEV_INDIVIDUA && $serviceSel == '2' && $eventsubInfo['kojin_cag_dlv_time_flg'] == '1') {
                return TRUE;
            }

            // 法人・宅配・復路配達・時間指定
            if ($comiketDiv == self::COMIKET_DEV_BUSINESS && $serviceSel == '1' && $eventsubInfo['hojin_box_dlv_time_flg'] == '1') {
                return TRUE;
            }

            // 法人・カーゴ・復路配達・時間指定
            if ($comiketDiv == self::COMIKET_DEV_BUSINESS && $serviceSel == '2' && $eventsubInfo['hojin_cag_dlv_time_flg'] == '1') {
                return TRUE;
            }

            // 法人・貸切・復路配達・時間指定
            if ($comiketDiv == self::COMIKET_DEV_BUSINESS && $serviceSel == '3' && $eventsubInfo['hojin_kas_dlv_time_flg'] == '1') {
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * 完了メール送信
     * @param $comiket 設定用配列
     * @param $sendTo2 宛先
     * @param sendCc   転送先
     * @param $type    往復区分
     * @return bool true:成功
     */
    public function sendCompleteMail($comiket, $sendTo2 = '', $sendCc = '', $type = '', $tmplateType = '')
    {

        try {

            if (@empty($tmplateType)) {
                //添付ファイルの有無を判別
                $isAttachment = ($comiket['choice'] == 2 || $comiket['choice'] == 3) ? true : false;
            } else {

                if ($tmplateType == 'cancel' || $tmplateType == 'sgmv_cancel') { // キャンセルメールの場合はqrコードは添付しない
                    $isAttachment = false;
                } else {
                    $comiketDetailList = $comiket['comiket_detail_list'];
                    $isAttachment = false;
                    if ($comiketDetailList[0]['type'] == 2) { // 復路
                        $isAttachment = true;
                    }
                }
                $tmplateType = '_' . $tmplateType;
            }

            //宛先
            $sendTo = $sendTo2;
            if (empty($sendTo2)) {
                $sendTo = $comiket['mail'];
            }

            //テンプレートデータ
            $data = array();

            //メールテンプレート(申込者用)
            $mailTemplate = array();

            //メールテンプレート(SGMV営業所用)
            $mailTemplateSgmv = array();

            // DBへ接続
            $db = Sgmov_Component_DB::getPublic();

            // イベント情報
            $eventData = $this->_EventService->fetchEventById($db, $comiket['event_id']);

            // イベントサブ情報
            $eventsubData = $this->_EventsubService->fetchEventsubByEventsubId($db, $comiket['eventsub_id']);


            $payMethodList = array('2' => 'クレジット', '3' => '電子マネー');
            if (@empty($payMethodList[$comiket['payment_method_cd']])) {
                $data['conveni_prepay_status'] = "";
            } else {
                $data['conveni_prepay_status'] = $payMethodList[$comiket['payment_method_cd']];
            }
            /////////////////////////////////////////////////////////////////////////////////////////////

            $isBoxOrCargoFlg = FALSE;

            $week = ['日', '月', '火', '水', '木', '金', '土'];

            $frDay = date('w', strtotime($eventsubData["term_fr"]));
            $toDay = date('w', strtotime($eventsubData["term_to"]));

            $termFr = new DateTime($eventsubData["term_fr"]);
            $termTo = new DateTime($eventsubData["term_to"]);
            $termFrName = $termFr->format('Y年m月d日');
            $termToName = $termTo->format('Y年m月d日');

            $comiketPrefData = $this->_PrefectureService->fetchPrefecturesById($db, $comiket['pref_id']);

            if ($comiket['div'] == self::COMIKET_DEV_BUSINESS) {
                //法人用メールテンプレート
                $mailTemplate[] = "/" . $this->_DirDiv . "_complete_business{$tmplateType}.txt";

                // 2018.09.10 tahira add 申込者と営業所で使用するメールテンプレートをわけ別々に送信する
                $mailTemplateSgmv[] = "/" . $this->_DirDiv . "_complete_business_sgmv{$tmplateType}.txt";

                $data['surname'] = $comiket['office_name'];
                $data['forename'] = "";
                $data['comiket_div'] = $this->comiket_div_lbls[intval($comiket['div'])];
                $data['comiket_customer_cd'] = $comiket['customer_cd'];
                $data['comiket_office_name'] = $comiket['office_name'];

                $comiketDetailTypeLbls = $this->comiket_detail_type_lbls;
                $data['comiket_choice'] = "搬出（会場⇒お客様）";

                $data['comiket_payment_method'] = "売掛";
            } else {
                //個人用メールテンプレート
                $mailTemplate[] = "/" . $this->_DirDiv . "_complete_individual{$tmplateType}.txt";

                // 申込者と営業所で使用するメールテンプレートをわけ別々に送信する
                $mailTemplateSgmv[] = "/" . $this->_DirDiv . "_complete_individual_sgmv{$tmplateType}.txt";

                $data['comiket_id'] = sprintf('%010d', $comiket['id']);
                $data['surname'] = $comiket['personal_name_sei'];
                $data['forename'] = $comiket['personal_name_mei'];

                $data['comiket_div'] = '出展者'; // rooms41
                if ($eventData['id'] === '2') { // コミケ
                    $data['comiket_div'] = '電子決済の方(クレジット、コンビニ決済、電子マネー)';
                }

                $data['comiket_personal_name_seimei'] = $comiket['personal_name_sei'] . " " . $comiket['personal_name_mei'];

                $comiketDetailTypeLbls = $this->comiket_detail_type_lbls;
                $data['comiket_choice'] = "搬出（会場⇒お客様）";

                $paymentMethodLbls = $this->payment_method_lbls;
                $data['comiket_payment_method'] = $paymentMethodLbls[$comiket['payment_method_cd']];

                $data['digital_money_attention_note'] = "";
                if ($comiket['payment_method_cd'] == '3') { // 電子マネー
                    $data['digital_money_attention_note'] = "※ イベント当日、受付にて電子マネーでの決済をお願いします。";
                    // $data['digital_money_attention_note'] = "※ 下記金額をキャンセルしました。";
                }
            }

            $data['comiket_id'] = sprintf('%010d', $comiket['id']); //【コミケID】
            //            $data['event_name'] = $eventData["name"] . " " . $eventsubData["name"]; //【出展イベント】
            $data['event_name'] = $eventData["name"];
            $data['place_name'] = $eventsubData["venue"]; //【場所】

            // 【期間】
            $kikan = $termFrName . "(" . $week[$frDay] . ")" . " ～ " . $termToName . "(" . $week[$toDay] . ")";

            if ($termFrName == $termToName) {
                $kikan = $termFrName . "(" . $week[$frDay] . ")";
            }


            // 【問合せ番号】
            $toiawase_no = @$comiket['toiawase_no'];
            if (empty($toiawase_no)) {
                $toiawase_no = @$comiket['comiket_detail_list'][0]["toiawase_no"];
            }

            $data['period_name'] = $kikan; //【期間】
            $data['toiawase_no'] = $toiawase_no; //【問合せ番号】
            $data['comiket_zip'] = "〒" . substr($comiket['zip'], 0, 3) . '-' . substr($comiket['zip'], 3); //【郵便番号】
            $data['comiket_pref_name'] = $comiketPrefData['name']; //【都道府県】
            $data['comiket_address'] = $comiket['address']; //【住所】
            $data['comiket_building'] = $comiket['building']; //【ビル】
            $data['comiket_tel'] = $comiket['tel']; //【電話番号】
            $data['comiket_mail'] = $comiket['mail']; //【メール】

            $comiket_detail_list = $comiket['comiket_detail_list'];

            foreach ($comiket_detail_list as $k => $comiket_detail) {
                //サービスごとの数量表示
                $num_area = '';
                switch ($comiket_detail['service']) {
                    case 1: //宅配
                        $num_area .= '【宅配数量】' . PHP_EOL;
                        $comiket_box_list = (isset($comiket_detail['comiket_box_list'])) ? $comiket_detail['comiket_box_list'] : array();
                        foreach ($comiket_box_list as $cb => $comiket_box) {
                            $boxInfo = $this->_BoxService->fetchBoxById($db, $comiket_box['box_id']);
                            $boxName = $boxInfo['name_display'];
                            if (empty($boxName)) {
                                $boxName = $boxInfo['name'];
                            }
                            if (($comiket_detail['type'] == 2 && $comiket_box['type'] == 2) || ($comiket_detail['type'] == 1 && $comiket_box['type'] == 1)
                            ) {
                                $num_area .= '    ' . @preg_replace("/&emsp;/", '', @strip_tags($boxName)) . ' ［' . $comiket_box['num'] . ' 個］' . PHP_EOL;
                            }
                        }
                        $isBoxOrCargoFlg = TRUE;
                        break;
                    case 2: //カーゴ
                        $num_area .= '【カーゴ数量】' . PHP_EOL;
                        $comiket_cargo_list = (isset($comiket_detail['comiket_cargo_list'])) ? $comiket_detail['comiket_cargo_list'] : array();
                        foreach ($comiket_cargo_list as $cb => $comiket_cargo) {
                            if (($comiket_detail['type'] == 2 && $comiket_cargo['type'] == 2) || ($comiket_detail['type'] == 1 && $comiket_cargo['type'] == 1)
                            ) {
                                $num_area .= '    ' . $comiket_cargo['num'] . ' 台' . PHP_EOL;
                            }
                        }
                        $isBoxOrCargoFlg = TRUE;
                        $num_area .= '【顧客管理番号】' . $comiket_detail['cd'] . PHP_EOL;
                        break;
                    case 3: //貸切
                        $num_area .= '【貸切台数】' . PHP_EOL;
                        $comiket_charter_list = (isset($comiket_detail['comiket_charter_list'])) ? $comiket_detail['comiket_charter_list'] : array();
                        foreach ($comiket_charter_list as $cb => $comiket_charter) {
                            if (($comiket_detail['type'] == 2 && $comiket_charter['type'] == 2) || ($comiket_detail['type'] == 1 && $comiket_charter['type'] == 1)
                            ) {
                                $num_area .= '    ' . $comiket_charter['name'] . ' ［' . $comiket_charter['num'] . ' 台］' . PHP_EOL;
                            }
                        }
                        $num_area .= '【顧客管理番号】' . $comiket_detail['cd'] . PHP_EOL;
                        break;
                }

                if (empty($comiket_detail['collect_date'])) {
                    $collectDateName = "";
                } else {
                    $collectDate = new DateTime($comiket_detail['collect_date']);
                    $collectDateName = $collectDate->format('Y年m月d日');
                }

                if (empty($comiket_detail['delivery_date'])) {
                    $deliveryDateName = "";
                } else {
                    $deliveryDate = new DateTime($comiket_detail['delivery_date']);
                    $deliveryDateName = $deliveryDate->format('Y年m月d日');
                }

                if (
                    empty($comiket_detail['collect_st_time'])
                    || $comiket_detail['collect_st_time'] == "00"
                ) {
                    $collectStTimeName = "指定なし";
                    $collectEdTimeName = "";
                    // お届け時間帯が設定されている場合は値をセット
                    if (self::COLLECT_TIMEFRAME != '') {

                        $collectStTimeName = self::COLLECT_TIMEFRAME;
                    }
                } else {
                    $collectStTime = new DateTime($comiket_detail['collect_st_time']);
                    $collectEdTime = new DateTime($comiket_detail['collect_ed_time']);
                    $collectStTimeName = $collectStTime->format("H:i") . "～";
                    $collectEdTimeName = $collectEdTime->format("H:i");
                }

                if (
                    empty($comiket_detail['delivery_st_time'])
                    || $comiket_detail['delivery_st_time'] == "00"
                ) {

                    $deliveryStTimeName = @$comiket_detail['delivery_timezone_name'];
                    $deliveryEdTimeName = "";
                } else {
                    $deliveryStTime = new DateTime($comiket_detail['delivery_st_time']);
                    $deliveryEdTime = new DateTime($comiket_detail['delivery_ed_time']);
                    $deliveryStTimeName = $deliveryStTime->format("H:i") . "～";
                    $deliveryEdTimeName = $deliveryEdTime->format("H:i");
                }

                $serviceNum = intval($comiket_detail['service']);
                $serviceName = "宅配便";

                if ($comiket_detail['type'] == 2) {
                    $prefData = $this->_PrefectureService->fetchPrefecturesById($db, $comiket_detail['pref_id']);
                    //搬出用メールテンプレート
                    $mailTemplate[] = '/' . $this->_DirDiv . '_parts_complete_choice_2.txt';

                    // 申込者と営業所で使用するメールテンプレートをわけ別々に送信する
                    $mailTemplateSgmv[] = '/' . $this->_DirDiv . '_parts_complete_choice_2_sgmv.txt';

                    $data['type2_name'] = $comiket_detail['name'];                  //【配送先名】
                    $data['type2_zip'] = "〒" . substr($comiket_detail['zip'], 0, 3) . '-' . substr($comiket_detail['zip'], 3); //【配送先郵便番号】
                    $data['type2_pref'] = $prefData["name"];  //【都道府県】
                    $data['type2_address'] = $comiket_detail['address'];            //【市町村区】
                    $data['type2_building'] = $comiket_detail['building'];          //【建物番地名】
                    $data['type2_tel'] = $comiket_detail['tel'];                    //【配送先電話番号】
                    $data['type2_collect_date'] = $collectDateName;  //【お預かり日時】
                    $data['type2_collect_st_time'] = $collectStTimeName;
                    $data['type2_collect_ed_time'] = $collectEdTimeName;
                    $data['type2_delivery_date'] = $deliveryDateName; //【お届け日時】
                    $data['type2_delivery_st_time'] = $deliveryStTimeName;
                    $data['type2_delivery_ed_time'] = $deliveryEdTimeName;
                    $data['type2_service'] = $serviceName;            //【サービス選択】
                    $data['type2_num_area'] = $num_area;                            //【数量】
                    $data['type2_note'] = $comiket_detail['note'];                  //【備考】
                }
            }

            $footerTmpName = $tmplateType;
            if (@empty($footerTmpName)) {
                $footerTmpName = "_complete";
            }
            //フッター用メールテンプレート
            if ($comiket['div'] == self::COMIKET_DEV_BUSINESS) { // 法人
                $mailTemplate[] = "/" . $this->_DirDiv . "_parts{$footerTmpName}_footer_type_1.txt";

                //  申込者と営業所で使用するメールテンプレートをわけ別々に送信する
                $mailTemplateSgmv[] = "/" . $this->_DirDiv . "_parts{$footerTmpName}_footer_type_1.txt";
            } else { // 個人
                $mailTemplate[] = "/" . $this->_DirDiv . "_parts{$footerTmpName}_footer_type_2.txt";

                //  申込者と営業所で使用するメールテンプレートをわけ別々に送信する
                $mailTemplateSgmv[] = "/" . $this->_DirDiv . "_parts{$footerTmpName}_footer_type_2.txt";
            }



            $data['comiket_amount'] = '\\' . number_format($comiket['amount']);
            $data['comiket_amount_tax'] = '\\' . number_format($comiket['amount_tax']);

            $urlPublicSsl = Sgmov_Component_Config::getUrlPublicSsl();
            $comiketIdCheckD = self::getChkD(sprintf("%010d", $comiket['id']));
            $data['edit_url'] = $urlPublicSsl . "/" . $this->_DirDiv . "/input/" . sprintf('%010d', $comiket["id"]) . $comiketIdCheckD;

            // 説明書URL
            //            $data['manual_url'] = $urlPublicSsl . "/".$this->_DirDiv."/pdf/manual/manual_{$comiket["eventsub_id"]}.pdf";
            $data['manual_url'] = '';

            if ($eventsubData['manual_display'] == '1') { // コミケとGoOutCampは記載しない
                $fileName = $eventData['name'] . $eventsubData['name'] . '.pdf';
                // eventとeventsubの名称が同じ場合
                if ($eventData['name'] == $eventsubData['name']) {
                    $fileName = $eventData['name'] . '.pdf';
                }
                // スペースを_に置換
                // TODO:マスタにマニュアルファイル名を持たせた方が安全
                $fileName = str_replace(' ', '', $fileName);

                $data['manual_url'] = "【宅配便WEB申込みのご案内】" . PHP_EOL . $urlPublicSsl . "/" . $this->_DirDiv . "/pdf/manual/{$fileName}";
            }
            //            $data['manual_url'] = $urlPublicSsl . "/".$this->_DirDiv."/pdf/manual/{$eventData['name']}{$eventsubData['name']}.pdf";

            $data['paste_tag_url'] = "";
            if (($comiket['choice'] == "1" || $comiket['choice'] == "3")
                && $isBoxOrCargoFlg
                && $eventsubData['paste_display'] == '1'
            ) {
                // 貼付票URL
                $pasteTagId = sprintf("%010d", $comiket['id']) . self::getChkD2($comiket['id']);
                $data['paste_tag_url'] = "【貼付票URL】" . PHP_EOL . $urlPublicSsl . "/" . $this->_DirDiv . "/paste_tag/{$pasteTagId}/";
            }

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // サイズ変更/キャンセルURL
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            $data['cancel_url'] = $urlPublicSsl . "/" . $this->_DirDiv . "/cancel/" . sprintf('%010d', $comiket["id"]) . $comiketIdCheckD;
            $data['size_change_url'] = $urlPublicSsl . "/" . $this->_DirDiv . "/size_change/" . sprintf('%010d', $comiket["id"]) . $comiketIdCheckD;
            //-------------------------------------------------
            //メール送信実行
            $objMail = new Sgmov_Service_CenterMail();
            if (!$isAttachment) {
                // 申込者へメール
                $objMail->_sendThankYouMail($mailTemplate, $sendTo, $data);

                // 営業所へメール(CCとして設定する用にもっていた変数をToの方へ)
                if ($sendCc !== null && $sendCc !== '') {
                    $objMail->_sendThankYouMail($mailTemplateSgmv, $sendCc, $data);
                }
            } else {

                // qrコードファイル出力
                $qr = new Image_QRCode();

                $image = $qr->makeCode(
                    htmlspecialchars($comiket["id"]),
                    array('output_type' => 'return', "module_size" => 10,)
                );
                imagepng($image, dirname(__FILE__) . "/tmp/qr{$comiket["id"]}.png");
                imagedestroy($image);

                $attachment = dirname(__FILE__) . '/tmp/qr' . $comiket['id'] . '.png';
                $attach_mime_type = 'image/png';
                // 申込者へメール
                $objMail->_sendThankYouMailAttached($mailTemplate, $sendTo, $data, $attachment, $attach_mime_type);

                // 2018.09.10 tahira add 申込者と営業所で使用するメールテンプレートをわけ別々に送信する
                // 営業所へメール(CCとして設定する用にもっていた変数をToの方へ)
                if ($sendCc !== null && $sendCc !== '') {
                    $objMail->_sendThankYouMailAttached($mailTemplateSgmv, $sendCc, $data, $attachment, $attach_mime_type);
                }

                unlink(dirname(__FILE__) . "/tmp/qr{$comiket["id"]}.png");
            }
            unset($objMail);
        } catch (Exception $e) {
            Sgmov_Component_Log::err('メール送信に失敗しました。');
            Sgmov_Component_Log::err($e);
            throw new Exception('メール送信に失敗しました。');
        }

        return true;
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////
    // ▼ 業務連携用設定値
    ///////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * リクエストのユーザーIDのキー
     */
    const REQUEST_USER_ID_KEY = 'userId';

    /**
     * リクエストのパスワードのキー
     */
    const REQUEST_PASSWORD_KEY = 'passWord';

    /**
     * リクエストのファイルのキー
     */
    const REQUEST_FILE_KEY = 'filename';

    /**
     * リクエストのデータのキー
     */
    const REQUEST_DATA_KEY = 'data';

    /**
     *
     * @param type $wsProtocol
     * @param type $wsHost
     * @param type $wsPath
     * @param type $wsPort
     * @param type $param comiket_id
     * @param type $paramOrg comiket_id + チェックデジット(モジュラス)
     */
    protected function execWebApiCancelComiket($wsProtocol, $wsHost, $wsPath, $wsPort, $param, $paramOrg, $dispErrTitle = "", $dispErrMsg = "")
    {

        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        $comiketInfo = $this->_ComiketService->fetchComiketById($db, $param);

        // コミケ明細申込データ
        $comiketDetailList = $this->_ComiketDetailService->fetchComiketDetailByComiketId($db, $param);

        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // ▼ 業務連携用リクエストデータ作成
        ///////////////////////////////////////////////////////////////////////////////////////////////////

        $sendFileName = $this->_DirDiv . '_' . date('YmdHis') . '.csv';
        $boundary = "-----" . md5(uniqid());

        //////////////////////////////////////////////////
        // CSVデータ作成
        //////////////////////////////////////////////////
        $strCsvData = "";
        $strCsvData .= "\"HEADER\"";
        $strCsvData .= "\r\n";
        $strCsvData .= '"H","' . $param . '"';
        $strCsvData .= "\r\n";
        $strCsvData .= "\"TRAILER\"";

        //////////////////////////////////////////////////
        // BODY部分作成
        //////////////////////////////////////////////////

        $body = "";

        // ユーザーID
        $body .= "--{$boundary}\r\n";
        $body .= "Content-Disposition: form-data; name=\"" . self::REQUEST_USER_ID_KEY . "\"\r\n";
        $body .= "\r\n";
        $body .= $this->_wsUserId . "\r\n";

        // パスワード
        $body .= "--{$boundary}\r\n";
        $body .= "Content-Disposition: form-data; name=\"" . self::REQUEST_PASSWORD_KEY . "\"\r\n";
        $body .= "\r\n";
        $body .= $this->_wsPassWord . "\r\n";

        // ファイル名
        $body .= "--{$boundary}\r\n";
        $body .= "Content-Disposition: form-data; name=\"" . self::REQUEST_FILE_KEY . "\"\r\n";
        $body .= "\r\n";
        $body .= $sendFileName . "\r\n";

        // ファイルデータを設定
        $body .= "--{$boundary}\r\n";
        $body .= "Content-Disposition: form-data; name=\"" . self::REQUEST_DATA_KEY . "\"; filename=\"{$sendFileName}\"\r\n";
        $body .= "Content-Type: text/plain\r\n";
        $body .= "\r\n";
        $body .= "{$strCsvData}\r\n";

        // 送信データ末尾の区切り文字を追加
        $body .= "--{$boundary}--\r\n";
        $body .= "\r\n\r\n";

        //////////////////////////////////////////////////
        // HEADER部分作成
        //////////////////////////////////////////////////
        $contentLength = strlen($body);

        $header = "";
        $header .= "POST " . $wsPath . " HTTP/1.1\r\n";
        $header .= "Host: " . $wsHost . "\r\n";
        $header .= "Content-type: multipart/form-data, boundary={$boundary}\r\n";
        $header .= "Connection: close\r\n";
        $header .= "Content-length: {$contentLength}\r\n";
        $header .= "\r\n";

        $request = $header . $body;

        // デバッグログを出力
        if (Sgmov_Component_Log::isDebug()) {
            Sgmov_Component_Log::debug("リクエストデータ\n" . $request);
        }

        //////////////////////////////////////////////////
        // 業務連携開始
        //////////////////////////////////////////////////

        $errno = "";
        $errstr = "";
        try {
            $fp = fsockopen($wsProtocol . $wsHost, $wsPort, $errno, $errstr, 30);
            if (!$fp) {
                Sgmov_Component_Log::debug($wsProtocol);
                Sgmov_Component_Log::debug($wsHost);
                Sgmov_Component_Log::debug($wsPort);
                Sgmov_Component_Log::debug($errno);
                Sgmov_Component_Log::debug($errstr);
                throw new Exception(@"業務サーバへの接続に失敗しました。\n _wsProtocol: {$wsProtocol} / _wsHost: {$wsHost} / _wsPort: {$wsPort}");
            }

            // デバッグログを出力
            if (Sgmov_Component_Log::isDebug()) {
                Sgmov_Component_Log::debug("接続確認\n" . $fp);
            }

            // デバッグログを出力
            if (Sgmov_Component_Log::isDebug()) {
                Sgmov_Component_Log::debug("IF処理開始\n" . $fp);
            }

            // データ送信
            if (!fwrite($fp, $request)) {

                // デバッグログを出力
                if (Sgmov_Component_Log::isDebug()) {
                    Sgmov_Component_Log::debug("リクエストの送信に失敗しました。\n" . $fp);
                }

                throw new Sgmov_Component_Exception('リクエストの送信に失敗しました。', Sgmov_Component_ErrorCode::ERROR_BCR_WS_SEND);
            }

            // デバッグログを出力
            if (Sgmov_Component_Log::isDebug()) {
                Sgmov_Component_Log::debug("データ送信完了\n" . $fp);
            }

            // デバッグログを出力
            if (Sgmov_Component_Log::isDebug()) {
                Sgmov_Component_Log::debug("ステータスラインの受信開始。\n" . $fp);
            }

            // ステータスラインの受信
            if (!($status = fgets($fp))) {

                // デバッグログを出力
                if (Sgmov_Component_Log::isDebug()) {
                    Sgmov_Component_Log::debug("ステータスラインの受信に失敗しました。\n" . $fp);
                }

                throw new Sgmov_Component_Exception('ステータスラインの受信に失敗しました。', Sgmov_Component_ErrorCode::ERROR_BCR_WS_RECV_STATUS);
            }

            // 受信ステータスをログ出力
            if (Sgmov_Component_Log::isDebug()) {
                Sgmov_Component_Log::debug("recvStatus\n" . $status);
            }

            // ステータスコードの確認
            if (substr_count($status, "200 OK") == 0) {
                throw new Sgmov_Component_Exception('ステータスが200ではありません。', Sgmov_Component_ErrorCode::ERROR_BCR_WS_BAD_STATUS);
            }

            // データの受信
            $response = '';

            while (!feof($fp)) {
                if (($buf = fread($fp, 4096)) == FALSE) {
                    throw new Sgmov_Component_Exception('データの受信に失敗しました。', Sgmov_Component_ErrorCode::ERROR_BCR_WS_RECV_DATA);
                }
                $response .= $buf;
            }
            $response = @mb_convert_encoding($response, "UTF-8", "SJIS");
            // レスポンス値を強制出力（強制のためwarningレベル）
            Sgmov_Component_Log::warning("レスポンス\n" . $response);
            $response = substr($response, strpos($response, "\r\n\r\n") + 4);

            $resBody = explode("\r\n", $response);


            if (count($resBody) < 4 || $resBody[1] != "\"HEADER\"" || $resBody[3] != "\"TRAILER\"") {
                throw new Sgmov_Component_Exception('データの受信に失敗しました。', Sgmov_Component_ErrorCode::ERROR_BCR_WS_RECV_DATA);
            }

            $item = explode("\",\"", $resBody[2]);

            $sendStatus = trim($item[0], '"');
            $ukeNo = trim($item[1], '"');
            $exception = trim($item[2], '"');
            $exceptionMessage = trim($item[3], '"');

            if ($sendStatus != "0") {
                throw new Exception(@"sendStatus: {$sendStatus} / ukeNo: {$ukeNo} / exception: {$exception} / exceptionMessage: {$exceptionMessage}");
            }

            // 接続終了
            @fclose($fp);
        } catch (Exception $e) {

            // 接続終了
            @fclose($fp);

            // デバッグログを出力
            if (Sgmov_Component_Log::isDebug()) {
                Sgmov_Component_Log::debug("エラー内容ログ出力\n" . $e);
            }
            $clasName = get_class($this);
            $message = "rooms41: 業務連携に失敗しました。\n[$this->_DirDiv] {$clasName} : paramOrg = {$paramOrg} / param = {$param}\n\nDB comiket.del_flg（論理削除フラグ）は更新しています。\n\n";
            $message .= "Exceptionメッセージ: " . $e->getMessage() . "\n\n";
            $message .= "業務サーバ接続情報: _wsProtocol: {$wsProtocol} / _wsHost: {$wsHost} / _wsPort: {$wsPort} / _wsPath: {$wsPath}";

            // システム管理者メールアドレスを取得する。
            $mailTo = Sgmov_Component_Config::getLogMailTo();
            //            $mailData = $this->createMailData($comiketInfo);
            //            $eventsubInfo = $this->_EventsubService->fetchEventsubByEventsubId($db, $comiketInfo['eventsub_id']);

            $mailData = $comiketInfo;
            $mailData['message'] = $message;
            $divType = "individual";
            if ($comiketInfo['div'] == self::COMIKET_DEV_BUSINESS) {
                $divType = "business";
            }
            $mailTemplateList = array(
                "/Rms_cancel_{$divType}_error.txt",
                //                "/Rms_parts_cancel_footer_type_{$comiketDetailInfo['type']}.txt",
            );
            $mailData['explanation_url_convenience_payment_method'] = " ";

            // 問い合わせ番号
            $mailData['toiawase_no'] = $comiketDetailList["0"]["toiawase_no"];

            // メールを送信する。
            $objMail = new Sgmov_Service_CenterMail();
            $objMail->_sendThankYouMail($mailTemplateList, $mailTo, $mailData);

            // 業務連携失敗
            return false;
        }
        ///////////////////////////////////////////////////////////////////////////////////////////////////

        // 業務連携成功
        return true;
    }

    /**
     *
     * @param type $comiketId
     */
    protected function checkReqDate($comiketId, $titleParts = "", $MessageParts = "")
    {
        if (@!empty($titleParts) && @empty($MessageParts)) {
            $MessageParts = $titleParts;
        }

        $db = Sgmov_Component_DB::getPublic();
        $comiketInfo = $this->_Comiket->fetchComiketById($db, $comiketId);
        $comiketDetailList = $this->_ComiketDetail->fetchComiketDetailByComiketId($db, $comiketId);

        foreach ($comiketDetailList as $key => $comiketDetailInfo) {
            if ($comiketDetailInfo['type'] == '2') { // 往路
                $eventsubInfo = $this->_EventsubService->fetchEventsubByEventsubId($db, $comiketInfo['eventsub_id']);
                $toDate = date('Y-m-d H:i:s');
                $eventTermEndDate = date('Y-m-d H:i:s', strtotime($eventsubInfo['arrival_to_time']));

                if ($eventTermEndDate <= $toDate) {
                    $eventTermEndDate2 = date('Y年m月d日', strtotime($eventTermEndDate));

                    $title = urlencode("催事・イベント配送受付サービスの搬出の{$titleParts}お申し込みができませんでした");
                    $message = urlencode("既に {$eventTermEndDate2} を過ぎているため搬出の{$titleParts}のお申し込みができませんでした。({$eventTermEndDate}まで可能)");
                    Sgmov_Component_Redirect::redirectPublicSsl("/" . $this->_DirDiv . "/error?t={$title}&m={$message}");
                }

                /////////////////////////////////////////////////////////////////////////////////////////////
                // 搬出-クール便申込時間チェック
                /////////////////////////////////////////////////////////////////////////////////////////////
                $inForm = array(
                    'eventsub_sel' => $comiketInfo['eventsub_id'],
                    'comiket_detail_inbound_binshu_kbn_sel' => $comiketDetailInfo['binshu_kbn'],
                    'comiket_detail_inbound_collect_date_year_sel' => date('Y', strtotime($comiketDetailInfo['collect_date'] . " 00:00:00")),
                    'comiket_detail_inbound_collect_date_month_sel' => date('m', strtotime($comiketDetailInfo['collect_date'] . " 00:00:00")),
                    'comiket_detail_inbound_collect_date_day_sel' => date('d', strtotime($comiketDetailInfo['collect_date'] . " 00:00:00")),
                );
                $this->checkCoolbinClosingDate($inForm, true, $MessageParts);
                /////////////////////////////////////////////////////////////////////////////////////////////
            }
        }
    }

    /**
     *
     * @param type $inForm
     */
    protected function checkCoolbinClosingDate($inForm, $isRedirect = true, $msgParts = 'お申込')
    {

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // シーフードショー大阪・アグリフードＥＸＰＯ大阪 では、クール便の終了時間は特に早まることはないためここで return しておく
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        return array(
            'isErr' => false,
            'message' => "",
        );

        if ($inForm['comiket_detail_inbound_binshu_kbn_sel'] == '1' || $inForm['comiket_detail_inbound_binshu_kbn_sel'] == '2') {
            // クール便（冷蔵・冷凍）の場合
            $closingTime = "14:30:00";
            $selectedCollectDate = @"{$inForm['comiket_detail_inbound_collect_date_year_sel']}-{$inForm['comiket_detail_inbound_collect_date_month_sel']}-{$inForm['comiket_detail_inbound_collect_date_day_sel']}";
            $todateYmd = date('Y-m-d');
            $todateYmdHis = date('Y-m-d H:i:s');
            $closingdate = date("Y-m-d {$closingTime}");

            $db = Sgmov_Component_DB::getPublic();
            $eventsubInfo = $this->_EventsubService->fetchEventsubByEventsubId($db, $inForm['eventsub_sel']);

            $termToFrom = $eventsubInfo['term_to'];


            if ($selectedCollectDate == $todateYmd && $termToFrom != $selectedCollectDate && $closingdate < $todateYmdHis) {
                // セッション情報を破棄
                $title = urlencode("当日飛脚クール便の{$msgParts}は、{$closingTime}までにお申込を完了してください。");
                $message = "";

                if ($isRedirect) {
                    Sgmov_Component_Redirect::redirectPublicSsl("/" . $this->_DirDiv . "/error/?t={$title}&m={$message}");
                    exit;
                } else {
                    return array(
                        'isErr' => true,
                        'message' => "当日飛脚クール便のお申込は、{$closingTime}までにお申込を完了してください。",
                    );
                }
            }
        }

        return array(
            'isErr' => false,
            'message' => "",
        );
    }
}
