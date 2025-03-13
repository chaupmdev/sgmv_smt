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
Sgmov_Lib::useServices(array('CruiseRepeater', 'Prefecture', 'Yubin', 'SocketZipCodeDll'
    , 'Event', 'Box', 'Cargo', 'Building', 'Charter', 'Eventsub'
    , 'AppCommon', 'HttpsZipCodeDll', 'BoxFare', 'CargoFare', 'Comiket', 'EventsubCmb', 'Time', 'CenterMail', 'ComiketDetail', 'OutBoundCollectCal'));
Sgmov_Lib::useView('Public');
/**#@-*/

/**
 * イベントサービスのお申し込みフォームの共通処理を管理する抽象クラスです。
 * @package    View
 * @subpackage UNA
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Una_Common extends Sgmov_View_Public {
    /**
     * 機能ID
     */
    const FEATURE_ID = 'UNA';

    /**
     * イベントID
     */
    const EVENT_ID = '2100';

    /**
     * イベントサブID
     */
    const EVENT_SUB_ID = '2101';

    /**
     * UNA001の画面ID
     */
    const GAMEN_ID_UNA001 = 'UNA001';

    /**
     * UNA002の画面ID
     */
    const GAMEN_ID_UNA002 = 'UNA002';

    /**
     * UNA003の画面ID
     */
    const GAMEN_ID_UNA003 = 'UNA003';

    /**
     * 個人
     */
    const COMIKET_DEV_INDIVIDUA = "1";

    /**
     * 法人
     */
    const COMIKET_DEV_BUSINESS = "2";

    // 消費税率
    const CURRENT_TAX = 1.10;

    // postgresのint型最大値
    const INT_MAX = 2147483647;
    
    /**
     * 当日より、7日後
     */
    const DELIVERY_START = 7;
    /**
     * 当日より、14日後
     */
    const DELIVERY_END = 14;
    
    /**
     * 初期表示、お預かり日の最大
     */
    const COLLECT_RANGE_DEFAULT = 5;
    
    const ADD_DELIVERY_DAYS = 7;
    

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
        1 => '搬入（お客様⇒会場）',
//        2 => '搬出（会場⇒お客様）',
    );

    /**
     * サービス選択値
     * @var array
     */
    public $comiket_detail_service_lbls = array(
        1 => '宅配便',
        2 => 'カーゴ',
        3 => '貸切（チャーター）',
        4 => 'ミルクラン',
        5 => '手荷物',
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
        5  => '売掛',
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
         10  => '13',
         12  => '15',
         15  => '18',
         18  => '20',
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
     * 往路・出荷日範囲計算マスタサービス
     * @var Sgmov_Service_OutBoundCollectCal
     */
    protected $_OutBoundCollectCal;

    /**
     * 時間帯サービス
     * @var type
     */
    private $_TimeService;

    // イベント識別子
    protected $_DirDiv;
    // 識別子(顧客の識別)
    protected $_KokyakuDiv;

    // 現在日時
    protected $_NowDate;



    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_appCommon             = new Sgmov_Service_AppCommon();
        $this->_ComiketService        = new Sgmov_Service_Comiket();
        $this->_ComiketDetailService  = new Sgmov_Service_ComiketDetail();
        $this->_PrefectureService     = new Sgmov_Service_Prefecture();
        $this->_EventService          = new Sgmov_Service_Event();
        $this->_EventsubService       = new Sgmov_Service_Eventsub();
        $this->_BoxService            = new Sgmov_Service_Box();
        $this->_CargoService          = new Sgmov_Service_Cargo();
        $this->_BuildingService       = new Sgmov_Service_Building();
        $this->_CharterService        = new Sgmov_Service_Charter();
        $this->_HttpsZipCodeDll       = new Sgmov_Service_HttpsZipCodeDll();
        $this->_BoxFareService        = new Sgmov_Service_BoxFare();
        $this->_CargoFareService      = new Sgmov_Service_CargoFare();

        $this->_EventsubCmbService    = new Sgmov_Service_EventsubCmb();

        $this->_TimeService           = new Sgmov_Service_Time();

        $this->_SocketZipCodeDll      = new Sgmov_Service_SocketZipCodeDll();

        $this->_OutBoundCollectCal = new Sgmov_Service_OutBoundCollectCal();

        $comiketCargoItemList = array();
        for($i=1; $i <= 99; $i++) {
            $comiketCargoItemList[$i] = $i;
        }

        // イベント識別子のセット
        $this->_DirDiv = Sgmov_Lib::setDirDiv(dirname(__FILE__));

        // 識別子(顧客の識別)のセット
        $this->_KokyakuDiv = self::COMIKET_DEV_INDIVIDUA;

        // 現在日時
        $this->_NowDate = new dateTime();

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

    /**
     * 年月日から短縮表記の曜日を返す
     * @param int $year
     * @param int $month
     * @param int $day
     * @return str
     */
    public static function _getWeek($year, $month, $day) {
        $week = array("日", "月", "火", "水", "木", "金", "土");
        $resultWeek = $week[date('w', strtotime("{$year}-{$month}-{$day}"))];

        return $resultWeek;
    }

    /**
     * 時間帯のプルダウンで選択された項目をラベルで表示する
     * @return str
     */
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

    /**
     * 時間帯のプルダウンで選択された項目をラベルで表示する
     * 往路・搬出のお届け指定日時の場合
     * @return str
     */
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

    /**
     * プルダウンで選択された項目のvalueを表示する
     * @return str
     */
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

    /**
     * プルダウンで選択された項目のラベルを表示する
     * @return str
     */
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
        foreach($eventList as $val) {
            if($val["id"] == $id) {
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
     protected function setBoxName($dataList){

        $returnList = [];
        foreach ($dataList as $key => $value) {
            $returnList[$key]['id'] = $value['id'];
            $returnList[$key]['cd'] = $value['cd'];
            $returnList[$key]['eventsub_id'] = $value['eventsub_id'];
            $returnList[$key]['size'] = $value['size'];
            $returnList[$key]['name'] = $value['name_display'];
            $returnList[$key]['size_display'] = $value['size_display'];
            if(empty($value['name_display'])){
                $returnList[$key]['name'] = $value['name'];
            }
        }

        return $returnList;
    }

    /**
     * 画面で入力、出力に使用するフォームオブジェクトの生成
     * @param type $inForm
     * @param Sgmov_Form_Una001Out $outForm
     * @return type
     */
    protected function createOutFormByInForm($inForm, $outForm = array()) {
        $dispItemInfo = array();
        $inForm = (array)$inForm;

        $db = Sgmov_Component_DB::getPublic();

        // アクセス日時で開催中のイベント情報を取得する
        $eventAll = $this->_EventService->fetchEventListWithinTerm2($db, null, null, self::FEATURE_ID);

        $eventAll2   = array();
        $eventIds    = array();
        $eventNames  = array();
        $eventNames2 = array();

        $week = array("日", "月", "火", "水", "木", "金", "土");
        foreach($eventAll as $key => $val) {
            $eventIds[]    = $val["id"];
            $eventNames[]  = $val["name"];
            $eventNames2[] = $val["event_name"] . $val["eventsub_name"];
            $eventAll2[]   = $val;
        }


        $outForm->raw_comiket_id  = @$inForm['comiket_id'];
        
        // 出展イベント
        $dispItemInfo["event_alllist"] = $eventAll2;
        $outForm->raw_event_cds  = $eventIds;
        $outForm->raw_event_lbls = $eventNames2;
        //$inForm["event_sel"]はイベントID
        $outForm->raw_event_cd_sel = $inForm["event_sel"];

        // イベントIDが往復いずれかの期間にあればイベントサブから取得する
        $eventsubAry2 = $this->_EventsubService->fetchEventsubListWithinTermByEventId($db, $inForm["event_sel"]);


        /////////////////////////////////////////////////////////////////////////////////////////////////////////
        // 入力モード制御
        /////////////////////////////////////////////////////////////////////////////////////////////////////////

        $outForm->raw_input_mode = $inForm['input_mode'];
        if(!empty($inForm['input_mode']) && !empty($eventsubAry2)) {
            // イベントIDは上記にて設定済み
            $eventsubList = $eventsubAry2["list"];
            $inForm['eventsub_sel'] = $eventsubList[0]['id'];
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////

        // イベント、イベントサブリストをループで回し、１つ１つ申込受付時間が過ぎていないか確認。過ぎているならフラグをセット
        // イベントをプルダウンで任意に選択して申込できていた頃の名残でこの処理自体は必要
        // 現在日時取得
        $sysdate = $this->_NowDate;
        // イベントでループ
        foreach ($eventAll2 as $data) {
            // イベントサブのデータを取得
            $eventSubData = $this->_EventsubService->fetchEventsubListWithinTermByEventId($db, $data['id']);

            foreach ($eventSubData['list'] as $key => $value) {
                // 申込終了日時
                $arrvalDt = $eventSubData['list'][$key]['arrival_to_time'];

                // unixタイムスタンプに変換して比較
                $eveEndDt   = date('U', strtotime($arrvalDt));
                $currentDt  = intval($sysdate->format('U'));
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

        // イベントサブ情報をリスト化
        $eventsubAry3 = array();
        $dispItemInfo["eventsub_selected_data"] = "";
        if(!empty($eventsubAry2)) {
            $outForm->raw_eventsub_cds  = $eventsubAry2['ids'];
            $outForm->raw_eventsub_lbls = $eventsubAry2['names'];
            $outForm->raw_eventsub_cd_sel = $inForm["eventsub_sel"];

            $inboundHatsuJis2 = "";
            $inboundChakuJis2 = "";

            // イベントサブ情報を画面表示用に調整する
            $eventsubCmbAry = $this->_EventsubCmbService->cmbEventsubList($eventsubAry2["list"], $inForm["eventsub_sel"], $inboundHatsuJis2, $inboundChakuJis2);
            $eventsubAry3 = $eventsubCmbAry["list"];
            $dispItemInfo["eventsub_selected_data"] = $eventsubCmbAry["selectedData"];
        }
        $dispItemInfo["eventsub_list"] = $eventsubAry3;

        // 場所(イベント)
        if(@!empty($dispItemInfo["eventsub_selected_data"])) {
            $outForm->raw_eventsub_zip = $dispItemInfo["eventsub_selected_data"]["zip"];
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
                // is_eq_outbound_collect：お預かり日のfrom-toが同じ日付に設定されている場合TRUE
                if($dispItemInfo["eventsub_selected_data"]["is_eq_outbound_collect"]) {
                    // 可読性が悪いので選択日を一旦変数にセット
                    $selY = $dispItemInfo["eventsub_selected_data"]["outbound_collect_fr_year"];
                    $selM = $dispItemInfo["eventsub_selected_data"]["outbound_collect_fr_month"];
                    $selD = $dispItemInfo["eventsub_selected_data"]["outbound_collect_fr_day"];

                    $inForm["comiket_detail_outbound_collect_date_year_sel"]  = $selY;
                    $inForm["comiket_detail_outbound_collect_date_month_sel"] = $selM;
                    $inForm["comiket_detail_outbound_collect_date_day_sel"]   = $selD;

                    // 選択日をDateTimeオブジェクトに変換
                    $comiketDetailOutboundCollectDate = new DateTime("{$selY}-{$selM}-{$selD}");
                    
                    // アクセス日をDateTimeオブジェクトに変換
                    $today = new DateTime();

                    // アクセス日が預かり日を過ぎている場合はアクセス日に丸める
                    if($comiketDetailOutboundCollectDate <= $today) {
                        $dispItemInfo["eventsub_selected_data"]["outbound_collect_fr_year"]  = $today->format('Y');
                        $dispItemInfo["eventsub_selected_data"]["outbound_collect_fr_month"] = $today->format('m');
                        $dispItemInfo["eventsub_selected_data"]["outbound_collect_fr_day"]   = $today->format('d');
                        $inForm["comiket_detail_outbound_collect_date_year_sel"]             = $today->format('Y');
                        $inForm["comiket_detail_outbound_collect_date_month_sel"]            = $today->format('m');
                        $inForm["comiket_detail_outbound_collect_date_day_sel"]              = $today->format('d');
                    }
                }

                // is_eq_outbound_delivery：引き渡し日のfrom-toが同じ日付に設定されている場合TRUE
                if($dispItemInfo["eventsub_selected_data"]["is_eq_outbound_delivery"]) {
                    $inForm["comiket_detail_outbound_delivery_date_year_sel"]  = $dispItemInfo["eventsub_selected_data"]["outbound_delivery_fr_year"];
                    $inForm["comiket_detail_outbound_delivery_date_month_sel"] = $dispItemInfo["eventsub_selected_data"]["outbound_delivery_fr_month"];
                    $inForm["comiket_detail_outbound_delivery_date_day_sel"]   = $dispItemInfo["eventsub_selected_data"]["outbound_delivery_fr_day"];
                }

                ////////////////////////////////////////////////////////////////////////////////
                // 搬出
                ////////////////////////////////////////////////////////////////////////////////
                // お預かり日
                // is_eq_inbound_collect：お預かり日のfrom-toが同じ日付に設定されている場合TRUE
                if($dispItemInfo["eventsub_selected_data"]["is_eq_inbound_collect"]) {
                    // 可読性が悪いので選択日を一旦変数にセット
                    $selY = $dispItemInfo["eventsub_selected_data"]["inbound_collect_fr_year"];
                    $selM = $dispItemInfo["eventsub_selected_data"]["inbound_collect_fr_month"];
                    $selD = $dispItemInfo["eventsub_selected_data"]["inbound_collect_fr_day"];

                    $inForm["comiket_detail_inbound_collect_date_year_sel"]  = $selY;
                    $inForm["comiket_detail_inbound_collect_date_month_sel"] = $selM;
                    $inForm["comiket_detail_inbound_collect_date_day_sel"]   = $selD;

                    // 選択日をDateTimeオブジェクトに変換
                    $comiketDetailInboundCollectDate =  new DateTime("{$selY}-{$selM}-{$selD}");;
                    // アクセス日をDateTimeオブジェクトに変換
                    $today = new DateTime();

                    // アクセス日が預かり開始日を過ぎている場合はアクセス日に丸める
                    if($comiketDetailInboundCollectDate <= $today) {
                        $dispItemInfo["eventsub_selected_data"]["inbound_collect_fr_year"]  = $today->format('Y');
                        $dispItemInfo["eventsub_selected_data"]["inbound_collect_fr_month"] = $today->format('m');
                        $dispItemInfo["eventsub_selected_data"]["inbound_collect_fr_day"]   = $today->format('d');
                        $inForm["comiket_detail_inbound_collect_date_year_sel"]             = $today->format('Y');
                        $inForm["comiket_detail_inbound_collect_date_month_sel"]            = $today->format('m');
                        $inForm["comiket_detail_inbound_collect_date_day_sel"]              = $today->format('d');
                    }
                }

                // お届け日
                // is_eq_inbound_delivery：お届け日のfrom-toが同じ日付に設定されている場合TRUE
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
        if(@!empty($inForm["comiket_pref_cd_sel"])) {
            $prefInfo = $this->_PrefectureService->fetchPrefecturesById($db, $inForm["comiket_pref_cd_sel"]);
            $outForm->raw_comiket_pref_nm = $prefInfo["name"];
        }

        // 宿泊先
        $outForm->raw_building_booth_position = "";
        if(@!empty($inForm["building_booth_position_sel"])) {
            $buildingInfo = $this->_BuildingService->fetchBuildingById($db, $inForm["building_booth_position_sel"]);
            
            $outForm->raw_building_booth_position = $buildingInfo["name"];
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
        $buildingListByEventsubId = $this->_BuildingService->fetchBuildingDataByEventsubId($db, $inForm["eventsub_sel"]);
        $boothPostionIds = array();
        $boothPostionLbls = array();
        foreach($buildingListByEventsubId as $key => $val) {
            $boothPostionIds[] =  $val['id'];
            // コンボの表示をここで制御
            $boothPostionLbls[] = $val['name'];
        }
        //$outForm->raw_building_booth_position = $inForm["building_booth_position"]; // 編集画面のラベル表示用
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
        $dataList = [];

        $typeSel = array("1", "2");

        if (!isset($inForm['comiket_detail_type_sel2'])) {
            $inForm['comiket_detail_type_sel2'] = '1';
        }
        $dispItemInfo["box_lbls"] = [];
        $dataList = $this->_BoxService->fetchBox2($db, $inForm["eventsub_sel"], $inForm["comiket_div"], "1"); // 搬入
        $dispItemInfo["outbound_box_lbls"] = $this->setBoxName($dataList);
        $dispItemInfo["box_lbls"] = array_merge($dispItemInfo["box_lbls"], $dispItemInfo["outbound_box_lbls"]);

        $dataList2 = $this->_BoxService->fetchBox2($db, $inForm["eventsub_sel"], $inForm["comiket_div"], "2"); // 搬出
        $dispItemInfo["inbound_box_lbls"] = $this->setBoxName($dataList2);
        $dispItemInfo["box_lbls"] = array_merge($dispItemInfo["box_lbls"], $dispItemInfo["inbound_box_lbls"]);

        
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

        //宿泊日のリストを取得
        $deliTmpDate1 = new DateTime();
        $deliTmpDate2 = new DateTime();
        $minDeliveryDate = $deliTmpDate1->modify(self::DELIVERY_START.'day');
        $maxDeliveryDate = $deliTmpDate2->modify(self::DELIVERY_END.'day');
        
        //イベントサブマスタより、最大引渡し日を取得する
        $maxOutBoundDate = new DateTime($dispItemInfo['eventsub_selected_data']['out_bound_loading_to']);
        
        if ($maxDeliveryDate > $maxOutBoundDate) {
            $maxDeliveryDate = clone $maxOutBoundDate;
        }
        
        $delivery = $this->getListYearMonthDay($minDeliveryDate, $maxDeliveryDate);
        //出荷希望日のリストを取得
        $collectTmpDate1 = new DateTime();
        $collectTmpDate2 = clone $minDeliveryDate;

        $minCollectDate = $collectTmpDate1->modify('1day');
        $maxCollectDate = $collectTmpDate2->modify('-'.self::COLLECT_RANGE_DEFAULT.'day');
        $collectDate = $this->getListYearMonthDay($minCollectDate, $maxCollectDate);
        
        $dispItemInfo["eventsub_selected_data"]['outbound_delivery_fr_dt'] = $minDeliveryDate->format('Y-m-d');
        $dispItemInfo["eventsub_selected_data"]['outbound_delivery_to_dt'] = $maxDeliveryDate->format('Y-m-d');
        $dispItemInfo["eventsub_selected_data"]['outbound_delivery_fr'] = $minDeliveryDate->format('Y年m月d日').'（' . $week[$minDeliveryDate->format('w')]  . '）';
        $dispItemInfo["eventsub_selected_data"]['outbound_delivery_to'] = $maxDeliveryDate->format('Y年m月d日').'（' . $week[$maxDeliveryDate->format('w')]  . '）';
        
        $dispItemInfo["eventsub_selected_data"]['outbound_collect_fr_dt'] = $minCollectDate->format('Y-m-d');
        $dispItemInfo["eventsub_selected_data"]['outbound_collect_to_dt'] = $maxCollectDate->format('Y-m-d');
        $dispItemInfo["eventsub_selected_data"]['outbound_collect_fr'] = $minCollectDate->format('Y年m月d日').'（' . $week[$minCollectDate->format('w')]  . '）';
        $dispItemInfo["eventsub_selected_data"]['outbound_collect_to'] = $maxCollectDate->format('Y年m月d日').'（' . $week[$maxCollectDate->format('w')]  . '）';
        
        // 搬入-お預かり日時
        $outForm->raw_comiket_detail_outbound_collect_date_year_sel = $inForm["comiket_detail_outbound_collect_date_year_sel"];
        $outForm->raw_comiket_detail_outbound_collect_date_year_cds = $collectDate[0];
        $outForm->raw_comiket_detail_outbound_collect_date_year_lbls = $collectDate[0];
        $outForm->raw_comiket_detail_outbound_collect_date_month_sel = $inForm["comiket_detail_outbound_collect_date_month_sel"];
        $outForm->raw_comiket_detail_outbound_collect_date_month_cds = $collectDate[1];
        $outForm->raw_comiket_detail_outbound_collect_date_month_lbls = $collectDate[1];
        $outForm->raw_comiket_detail_outbound_collect_date_day_sel = $inForm["comiket_detail_outbound_collect_date_day_sel"];
        $outForm->raw_comiket_detail_outbound_collect_date_day_cds = $collectDate[2];
        $outForm->raw_comiket_detail_outbound_collect_date_day_lbls = $collectDate[2];
        // 搬入-お預かり日時-時間帯
        $comiket_detail_time_lbls = $this->comiket_detail_time_lbls;
        $outForm->raw_comiket_detail_outbound_collect_time_sel = $inForm["comiket_detail_outbound_collect_time_sel"];
        $outForm->raw_comiket_detail_outbound_collect_time_cds = array_keys($comiket_detail_time_lbls);
        $outForm->raw_comiket_detail_outbound_collect_time_lbls = array_values($comiket_detail_time_lbls);

        // 搬入-お届け日時
        $comiket_detail_time_lbls_par30m = $this->comiket_detail_time_lbls_par30m;
        $outForm->raw_comiket_detail_outbound_delivery_date_year_sel = $inForm["comiket_detail_outbound_delivery_date_year_sel"];
        $outForm->raw_comiket_detail_outbound_delivery_date_year_cds = $delivery[0];
        $outForm->raw_comiket_detail_outbound_delivery_date_year_lbls = $delivery[0];
        $outForm->raw_comiket_detail_outbound_delivery_date_month_sel = $inForm["comiket_detail_outbound_delivery_date_month_sel"];
        $outForm->raw_comiket_detail_outbound_delivery_date_month_cds = $delivery[1];
        $outForm->raw_comiket_detail_outbound_delivery_date_month_lbls = $delivery[1];
        $outForm->raw_comiket_detail_outbound_delivery_date_day_sel = $inForm["comiket_detail_outbound_delivery_date_day_sel"];
        $outForm->raw_comiket_detail_outbound_delivery_date_day_cds = $delivery[2];
        $outForm->raw_comiket_detail_outbound_delivery_date_day_lbls = $delivery[2];

        // 時間帯マスタからデータを取得
        $timeDataList = $this->_TimeService->fetchTimeDataList($db);

        foreach ($timeDataList as $timeData) {
            $this->comiket_detail_delivery_timezone[$timeData['cd'] .','. $timeData['name']] = $timeData['name'];
            
            // キャンセル画面にお届け指定日表示する。
            if($inForm["comiket_detail_outbound_delivery_time_sel"] == $timeData['name']){
                $inForm["comiket_detail_outbound_delivery_time_sel"] = $timeData['cd'] .','. $timeData['name'];
            }
        }

        // 搬入-お届け日時-時間帯
        $outForm->raw_comiket_detail_outbound_delivery_time_sel = $inForm["comiket_detail_outbound_delivery_time_sel"];
        $outForm->raw_comiket_detail_outbound_delivery_time_cds = array_keys($comiket_detail_time_lbls_par30m);
        $outForm->raw_comiket_detail_outbound_delivery_time_lbls = array_values($comiket_detail_time_lbls_par30m);

        // 搬入-サービス選択
        $outForm->raw_comiket_detail_outbound_service_sel = $inForm["comiket_detail_outbound_service_sel"];

        // 搬入-宅配
        $outForm->raw_comiket_box_outbound_num_ary = $inForm["comiket_box_outbound_num_ary"];

        // 搬入-カーゴ
        // $outForm->raw_comiket_cargo_outbound_num_ary = $inForm["comiket_cargo_outbound_num_ary"];
        $outForm->raw_comiket_cargo_outbound_num_sel = $inForm["comiket_cargo_outbound_num_sel"];
        $outForm->raw_comiket_cargo_outbound_num_cds = array_keys($this->comiket_cargo_item_list);
        $outForm->raw_comiket_cargo_outbound_num_lbls = array_values($this->comiket_cargo_item_list);

        // 搬入-チャーター
        $outForm->raw_comiket_charter_outbound_num_ary = $inForm["comiket_charter_outbound_num_ary"];

        // 搬入-備考
        // $outForm->raw_comiket_detail_outbound_note = $inForm["comiket_detail_outbound_note"];

        // 搬入-備考-1行目
        $outForm->raw_comiket_detail_outbound_note1 = $inForm["comiket_detail_outbound_note1"];

        // 搬入-備考-2行目
        $outForm->raw_comiket_detail_outbound_note2 = $inForm["comiket_detail_outbound_note2"];

        // 搬入-備考-3行目
        $outForm->raw_comiket_detail_outbound_note3 = $inForm["comiket_detail_outbound_note3"];

        // 搬入-備考-4行目
        $outForm->raw_comiket_detail_outbound_note4 = $inForm["comiket_detail_outbound_note4"];

        /////////////////////////////////////////////////////////////////////////////////////////////////////////
        // 支払
        /////////////////////////////////////////////////////////////////////////////////////////////////////////
        // 送料
        $outForm->raw_delivery_charge = @empty($inForm["delivery_charge"]) ? 0 : $inForm["delivery_charge"];
        
        // リピータ割引
        $outForm->raw_repeater_discount = $inForm["repeater_discount"];

        // お支払方法コード選択値
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
        
        
        $detailCollectDate = array(
            'ids' => [],
            'names' => [],
        );
        if (!empty ($outForm->raw_comiket_detail_outbound_delivery_date_year_sel) && 
            !empty ($outForm->raw_comiket_detail_outbound_delivery_date_month_sel) &&
            !empty ($outForm->raw_comiket_detail_outbound_delivery_date_day_sel)) {
            $deliveryDateOutForm = $outForm->raw_comiket_detail_outbound_delivery_date_year_sel.'-'.$outForm->raw_comiket_detail_outbound_delivery_date_month_sel.'-'.$outForm->raw_comiket_detail_outbound_delivery_date_day_sel;
            $now = new DateTime($deliveryDateOutForm);
            $dateAdd  = $now->modify('+1 day');
            $endDate = new DateTime($deliveryDateOutForm);
            $endDate = $endDate->modify('+'.self::ADD_DELIVERY_DAYS.' days');
            for($date = $dateAdd; $date <= $endDate; $date->modify('+1 day')){
                array_push($detailCollectDate['ids'], $date->format("Y-m-d"));
                array_push($detailCollectDate['names'], $date->format("Y年m月d日"));
            }
        }
        // 到着地
        $outForm->raw_comiket_detail_collect_date_cds  = $detailCollectDate['ids'];
        $outForm->raw_comiket_detail_collect_date_lbls = $detailCollectDate['names'];
        $outForm->raw_comiket_detail_collect_date_sel = @$inForm['comiket_detail_collect_date_sel'];
        
        $outForm->raw_comiket_detail_type_sel2         = $inForm['comiket_detail_type_sel2'];
        
        // イベント名、イベントサブ名、ロゴ画像、PDFなどを一括セット
        $this->setDispEvent($dispItemInfo);
        
        
        return array("outForm" => $outForm
                , "dispItemInfo" => $dispItemInfo
            );
    }

    /**
     * 共通処理：画面表示用にイベント名、イベントサブ名、ロゴ画像、PDFなどを一括セット
     * @param type $zip
     * @param type $address
     * @return type
     */
    public function setDispEvent(&$dispItemInfo){
        // PDFダウンロードのキャッシュ対策用文字列
        $sysdate = $this->_NowDate;
        $cacheStr = '?'.$sysdate->format('YmdHi');
        foreach($dispItemInfo['event_alllist'] as $k=>$v){
            if(isset($dispItemInfo['eventsub_selected_data']['event_id']) && $v['id']==$dispItemInfo['eventsub_selected_data']['event_id']){
                    // event.name:例)デザインフェスタ
                    $dispItemInfo['dispEvent']['name']      =$v['event_name'];
                    // eventsub.name:例)vol.55
                    $dispItemInfo['dispEvent']['subName']   =$v['eventsub_name'];
                    // 例)SGムービング、佐川急便 など配送者名
                    $dispItemInfo['dispEvent']['sgName']='佐川急便　黒部営業所　内';
                    // 例)デザインフェスタ55 などお問い合わせ先の箇所などでマスタと微妙に違う文言を設定
                    $dispItemInfo['dispEvent']['customName']='黒部宇奈月キャニオンルートツアー手荷物配送係';
                    // 例)03-5857-2462 などお問い合わせ電話番号を設定
                    $dispItemInfo['dispEvent']['tel1']='03-5857-2462';
                    // 例)0120-333-603 など集荷専用電話番号(第2問合番号)を設定
                    $dispItemInfo['dispEvent']['tel2']='0120-333-603';
                    // ロゴ画像ファイル名：例)logo.una.gif logo_[イベント識別子].gif
                    $dispItemInfo['dispEvent']['logoImg']   ='/'.$this->_DirDiv.'/images/logo_'.$this->_DirDiv.'.jpg';
                    // 貼付票のサンプル：例)paste_tag_[イベント識別子].pdf
                    $dispItemInfo['dispEvent']['pasteTag']  ='/'.$this->_DirDiv.'/pdf/paste_tag/paste_tag_'.$this->_DirDiv.'.pdf'.$cacheStr;
                    // 説明書：例)manual_[イベント識別子].pdf
                    $dispItemInfo['dispEvent']['manual']    ='/'.$this->_DirDiv.'/pdf/manual/manual_'.$this->_DirDiv.'.pdf'.$cacheStr;
            }
        }
    }


    /**
     * 住所情報を取得します。
     * @param type $zip
     * @param type $address
     * @return type
     */
    public function _getAddress($zip, $address) {
        return $this->_SocketZipCodeDll->searchByAddress($address, $zip);
    }

    /**
     * 住所情報を取得します。
     * @param type $zip
     * @return type
     */
    public function _getAddressByZip($zip){
        try{
            $receive = $this->_SocketZipCodeDll->searchByZipCode($zip);

            if (empty($receive)) {
                // 接続に失敗した場合はfalseが返ってくるのでリターンする
                return [];
            }

            $hasKenName = isset($receive['KenName']) && !empty($receive['KenName']);
            $hasCityName = isset($receive['CityName']) && !empty($receive['CityName']);
            $hasTownName = isset($receive['TownName']) && !empty($receive['TownName']);

            if (!$hasKenName || !$hasCityName || !$hasTownName) {
                return [];
            }

            return [
                'kenName'  => $receive['KenName'],
                'cityName' => $receive['CityName'],
                'townName' => $receive['TownName'],
            ];
        } catch (\Exception $ex) {
        }

        return [];
    }

    /**
     *
     * @param type $inForm
     */
    public function setYubinDllInfoToInForm(&$inForm) {
        $db = Sgmov_Component_DB::getPublic();

        $eventsubData = $this->_EventsubService->fetchEventsubByEventsubId($db, $inForm["eventsub_sel"]);

        $resultEventZipDll = $this->_getAddress(@$eventsubData["zip"], @$eventsubData["address"]);

        if($inForm['comiket_detail_type_sel'] == "1") { // 搬入（もしくは搬入搬出）
            // 搬入 /////////////////////////////////////////////

                $resultOutboundPrefData = $this->_PrefectureService->fetchPrefecturesById($db, $inForm['comiket_pref_cd_sel']);


                $resultOutboundHatsuZipDll = $this->_getAddress(@$inForm['comiket_zip1'] . @$inForm['comiket_zip2']
                        , @$resultOutboundPrefData["name"] . @$inForm['comiket_address'] . @$inForm['comiket_building']);

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
        }
    }

    protected function setInboundYubinDllInfoToInForm(&$inForm) {
    }

    /**
     * 送料計算
     * @param type $inForm
     */
    protected function calcEveryKindData($inForm, $comiketId = "", $isAmountDataFromSession = false) {
        $fareTaxTotal = 0;
        // DB接続
        $db = Sgmov_Component_DB::getPublic();

        $this->setYubinDllInfoToInForm($inForm);

        $tableDataInfo = $this->_cmbTableDataFromInForm($inForm, $comiketId);
        $tableTreeData = $tableDataInfo["treeData"];

        $sessionTableTreeData = @$_SESSION[dirname(__FILE__) . "_treeData"];
        if ($isAmountDataFromSession) {
            Sgmov_Component_Log::debug('#################### calcEveryKindData 入力チェック画面表示前にsesison設定された金額を設定する');
            
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // 入力チェック画面表示前にsesison設定された金額を設定する
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            
            $isReturn = false;
            $flatDataInfo = array();
            
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // 配送用
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            if (!@empty($sessionTableTreeData)) {
                $tableTreeData['amount_tax'] = $sessionTableTreeData['amount_tax'];
                $tableTreeData['amount'] = $sessionTableTreeData['amount'];

                foreach ($sessionTableTreeData["comiket_detail_list"] as $key => $val) {
                    $tableTreeData["comiket_detail_list"][$key]['cost'] = $val['cost'];
                    $tableTreeData["comiket_detail_list"][$key]['cost_tax'] = $val['cost_tax'];
                    $tableTreeData["comiket_detail_list"][$key]['fare'] = $val['fare'];
                    $tableTreeData["comiket_detail_list"][$key]['fare_tax'] = $val['fare_tax'];

                    // 宅配ボックス
                    if(!@empty($val["comiket_box_list"])) {
                        foreach($val["comiket_box_list"] as $keyComiketBox => $valComiketBox) {
                            $tableTreeData["comiket_detail_list"][$key]["comiket_box_list"][$keyComiketBox]["cost_price_tax"] = $valComiketBox["cost_price_tax"];
                            $tableTreeData["comiket_detail_list"][$key]["comiket_box_list"][$keyComiketBox]["cost_amount_tax"] = $valComiketBox["cost_amount_tax"];
                            $tableTreeData["comiket_detail_list"][$key]["comiket_box_list"][$keyComiketBox]["fare_price"] = $valComiketBox["fare_price"];
                            $tableTreeData["comiket_detail_list"][$key]["comiket_box_list"][$keyComiketBox]["fare_amount"] = $valComiketBox["fare_amount"];
                        }
                    }
                }

                $flatDataInfo = $this->getFlatData($tableTreeData);
                $isReturn = true;
            }
            
            
            if ($isReturn) {
                return array(
                    "treeData" => $tableTreeData,
                    "flatData" => $flatDataInfo,
                );
            }
        }

        $tableTreeData['amount_tax'] = 0;
        $tableTreeData['amount'] = 0;
        $costTotal = 0;
        $fareTotal = 0;
        if ($inForm['comiket_detail_type_sel'] == "1" || $inForm['comiket_detail_type_sel'] == "2" || $inForm['comiket_detail_type_sel'] == "3") {
            $procList = array(
                'tableTreeData' => $tableTreeData,
            );
            
            $resultList = array();
            foreach ($procList as $keyTree => $valTree) {
                $isFirst = true;
                foreach($valTree["comiket_detail_list"] as $keyDet => $valDet) {
                    $costTotal = 0;
                    $fareTotal = 0;
                    if(isset($valDet["comiket_box_list"])){
                        foreach($valDet["comiket_box_list"] as $keyComiketBox => $valComiketBox) {
                            
                            $boxInfo = $this->_BoxService->fetchBoxById($db, $valComiketBox['box_id']);
                            
                            $hatsu_jis2code = 0;
                            $chaku_jis2code = 0;
                            
                            $hatsu_jis2code = @$inForm["outbound_hatsu_jis2code"]; 
                            $chaku_jis2code = @$inForm["outbound_chaku_jis2code"];
                            
                            
                            if(!empty($boxInfo)) {
                                // 保管料金（税込）
                                $costTotal += intval($boxInfo['cost_tax']) * intval($valComiketBox['num']);

                                $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["cost_price"] = 0;
                                $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["cost_amount"] = 0;

                                $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["cost_price_tax"] = intval($boxInfo['cost_tax']);
                                $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["cost_amount_tax"] = intval($boxInfo['cost_tax']) * intval($valComiketBox['num']);
                            }
                            if ($isFirst) {
                                $boxFareData = $this->_BoxFareService->fetchBoxFareByJis2AndBoxId($db, $hatsu_jis2code, $chaku_jis2code, $boxInfo["cd"],  $valTree["eventsub_id"]);
                            } else {
                                $boxFareData = $this->_BoxFareService->fetchBoxFareByJis2AndBoxId($db, $chaku_jis2code, $hatsu_jis2code, $boxInfo["cd"],  $valTree["eventsub_id"]);
                            }
                            
                            if(!empty($boxFareData)) {
                                // 運賃（税抜）
                                $fareTotal += intval($boxFareData['fare']) * intval($valComiketBox['num']);

                                $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["fare_price"] = 0;
                                $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["fare_amount"] = 0;

                                $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["fare_price_tax"] = intval($boxFareData['fare']);
                                $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["fare_amount_tax"] = intval($boxFareData['fare']) * intval($valComiketBox['num']);

                            }
                        }

                        /////////////////////////////////////////////////////////
                        //// 料金計算(子) 【comiket_detail】
                        /////////////////////////////////////////////////////////
                        $valTree["comiket_detail_list"][$keyDet]['fare'] = ceil((string)($fareTotal / Sgmov_View_Una_Common::CURRENT_TAX));

                        $valTree["comiket_detail_list"][$keyDet]['fare_tax'] = $fareTotal;

                        $valTree["comiket_detail_list"][$keyDet]['cost'] = ceil((string)($costTotal / Sgmov_View_Una_Common::CURRENT_TAX));
                        // // 税込
                        $valTree["comiket_detail_list"][$keyDet]['cost_tax'] = $costTotal;
                        
                        $isFirst = false;
                    }
                }
                
                $resultList[$keyTree] = $valTree;
            }

            $tableTreeData = $resultList['tableTreeData'];
        }
        
        /////////////////////////////////////////////////////////////////////////////////
        // 配送料計算
        /////////////////////////////////////////////////////////////////////////////////
        
        $procList = array(
            'tableTreeData' => $tableTreeData,
        );
        
        $resultList = array();

        foreach ($procList as $keyTree => $valTree) {

            $valTree['amount_tax'] = $valTree['amount'] = 0;
            $detailAmountTotal = 0;
            $detailAmountTaxTotal = 0;
            foreach($valTree["comiket_detail_list"] as $keyDet => $valDet) {
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
            $valTree['amount'] = $detailAmountTotal;
            $valTree['amount_tax'] = $detailAmountTaxTotal;

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
    private function getFlatData($comiketData) {
        
        $comiketDetailDataList = $comiketData["comiket_detail_list"];

        $comiketBoxDataList = array();
        foreach($comiketDetailDataList as $key => $val) {
            if(isset($val["comiket_box_list"])) {
                foreach($val["comiket_box_list"] as $key2 => $val2) {
                    $comiketBoxDataList[] = $val2;
                }
            }
        }

        return array(
                "comiketData" => $comiketData,
                "comiketDetailDataList" => $comiketDetailDataList,
                "comiketBoxDataList" => $comiketBoxDataList,
            );
    }

    /**
     *
     * @param type $inForm
     * @param type $comiketId
     * @return type
     */
    public function _cmbTableDataFromInForm($inForm, $comiketId="") {
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
        // comiket_detail データ作成
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $comiketBoxDataList = $this->_createComiketBoxInsertDataByInForm($inForm, $comiketId);
        $comiketBoxDataListForHaiso = array();
        
        
        // 配送用
        foreach($comiketBoxDataList as $key => $val) {
            $comiketBoxDataListForHaiso[] = $val;
        }

        // [配送用] comiket_box 設定
        foreach($comiketBoxDataListForHaiso as $key => $val) {
            foreach($comiketDataForHaiso["comiket_detail_list"] as $key2 => $val2) {
                if($comiketDataForHaiso["comiket_detail_list"][$key2]["type"] == $val["type"]) {
                    $comiketDataForHaiso["comiket_detail_list"][$key2]["comiket_box_list"][$key] = $val;
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
            ),
        );
    }

    /**
     * 顧客コード取得
     * @param type $eventSel
     * @return string
     */
    private function getCustomerCd($eventSel) {

        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        $eventInfo = $this->_EventService->fetchEventInfoByEventId($db, $eventSel);

        return $eventInfo['customer_cd'];
    }

    /**
     * 入力データをcomiketテーブルに登録できるように整形する
     * @param type $eventSel
     * @return string
     */
    public function _createComiketInsertDataByInForm($inForm, $id, $type="") {
        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        $batch_status = '1';

        $customerCd = $this->getCustomerCd($inForm['event_sel']);
        //法人顧客名の固定で設定する
        $inForm['office_name'] = "ＪＴＢ　富山支店";
        $inForm['comiket_div'] = self::COMIKET_DEV_BUSINESS;

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
       
        $buildingNameRes = $this->_BuildingService->fetchBuildingNameByCd($db, $inForm['building_name_sel'], $inForm['eventsub_sel']);

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
            //"office_name" => $inForm['office_name'],
            "office_name" => $inForm['comiket_personal_name_sei'].$inForm['comiket_personal_name_mei'],
            "personal_name_sei" => $inForm['comiket_personal_name_sei'],
            "personal_name_mei" => $inForm['comiket_personal_name_mei'],
            "zip" => $inForm['comiket_zip1'] . $inForm['comiket_zip2'],
            "pref_id" => $inForm['comiket_pref_cd_sel'],
            "address" => $inForm['comiket_address'],
            "building" => $inForm['comiket_building'],
            "tel" => $inForm['comiket_tel'],
            "mail" => $inForm['comiket_mail'],
            "booth_name" => @$buildingNameRes['name'],
            "building_name" => @$buildingNameRes['name'],
            "booth_position" => $inForm['building_booth_position_sel'],
            "booth_num" => @sprintf('%04s', $inForm['comiket_booth_num']),
            "staff_sei" => $inForm['comiket_personal_name_sei'],
            "staff_mei" => $inForm['comiket_personal_name_mei'],
            "staff_sei_furi" => $inForm['comiket_personal_name_sei'],
            "staff_mei_furi" => $inForm['comiket_personal_name_mei'], 
            "staff_tel" => $inForm['comiket_tel'],
            "choice" => $inForm['comiket_detail_type_sel2'],
            "amount" => "0", // ?
            "amount_tax" => "0", // ?
            "create_ip" => $_SERVER["REMOTE_ADDR"],
//            "created" => "",
            "modify_ip" => $_SERVER["REMOTE_ADDR"],
//             "modified" => "",
            "transaction_id" => @$inForm['sgf_res_transactionId'],
            "auto_authoriresult" => @$inForm['sgf_res_autoAuthoriresult'],
            "haraikomi_url" => @$inForm['payment_url'],
            "kounyuten_no" => @$inForm['sgf_res_shopOrderId'],
            "del_flg" => '0',
            "customer_kbn" => '1',
            "bpn_type" => '0'
        );
        
        return $data;
    }

    /**
     * 入力データをcomiket_detailテーブルに登録できるように整形する
     * @param type $eventSel
     * @return string
     */
    public function _createComiketDetailInsertDataByInForm($inForm, $id) {
        $returnList = array();

        $customerCd = "";
        if(!empty($id)) {
            $customerCd = sprintf("%07d", $id);
        }

        $db = Sgmov_Component_DB::getPublic();

        if($inForm['comiket_detail_type_sel'] == "1"
                || $inForm['comiket_detail_type_sel'] == "3" ) { // 搬入（もしくは搬入搬出）

            // お預かり日時の整形
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

            // 引渡し日の整形
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

            // 備考
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

            // お預かり日時の時間帯整形
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
                "name" => $inForm['comiket_personal_name_sei'] . '　' . $inForm['comiket_personal_name_mei'],
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

                "zip" => $inForm['comiket_zip1'] . $inForm['comiket_zip2'],
                "pref_id" => $inForm['comiket_pref_cd_sel'],
                "address" => $inForm['comiket_address'],
                "building" => $inForm['comiket_building'],
                "tel" => $inForm['comiket_tel'],

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
                "binshu_kbn" => "0",
                "toiawase_no" => @$inForm['comiket_toiawase_no'],
                "toiawase_no_niugoki" => @$inForm['comiket_toiawase_no_niugoki']
            );
                
            $returnList[] = $data;
            
            if ($inForm['comiket_detail_type_sel2'] == '3') {//集荷の往復 : 往復 
                $data2 = array(
                    "comiket_id" => $id,
                    "type" => "2",
                    "cd" => "ev{$customerCd}2",
                    "name" => $inForm['comiket_personal_name_sei'] . '　' . $inForm['comiket_personal_name_mei'],
                    "hatsu_jis5code" => @$inForm["outbound_chaku_jis5code"],
                    "hatsu_shop_check_code" => @$inForm["outbound_chaku_shop_check_code"],
                    "hatsu_shop_check_code_eda" => @$inForm["outbound_chaku_shop_check_code_eda"],
                    "hatsu_shop_code" => @$inForm["outbound_chaku_shop_code"],
                    "hatsu_shop_local_code" => @$inForm["outbound_chaku_shop_local_code"],

                    "chaku_jis5code" => @$inForm["outbound_hatsu_jis5code"],
                    "chaku_shop_check_code" => @$inForm["outbound_hatsu_shop_check_code"] ,
                    "chaku_shop_check_code_eda" => @$inForm["outbound_hatsu_shop_check_code_eda"],
                    "chaku_shop_code" => @$inForm["outbound_hatsu_shop_code"],
                    "chaku_shop_local_code" => @$inForm["outbound_hatsu_shop_local_code"],

                    "zip" => $inForm['comiket_zip1'] . $inForm['comiket_zip2'],
                    "pref_id" => $inForm['comiket_pref_cd_sel'],
                    "address" => $inForm['comiket_address'],
                    "building" => $inForm['comiket_building'],
                    "tel" => $inForm['comiket_tel'],
                    "collect_date" => @$inForm["comiket_detail_collect_date_sel"], 
                    "collect_st_time" => null,
                    "collect_ed_time" => null, 
                    
                    "delivery_date" => null,
                    "delivery_st_time" => null,
                    "delivery_ed_time" => null,

                    "service" => $inForm['comiket_detail_outbound_service_sel'],
                    "note" => $note,
                    "fare" => "0", // ?
                    "fare_tax" => "0", // ?
                    "cost" => "0", // ?
                    "cost_tax" => "0", // ?
                    "delivery_timezone_cd" => null,
                    "delivery_timezone_name" => null,
                    "binshu_kbn" => "0",
                    "toiawase_no" => @$inForm['comiket_toiawase_no2'],
                    "toiawase_no_niugoki" => @$inForm['comiket_toiawase_no_niugoki2']
                );
                $returnList[] = $data2;
            } 
        }
        
        return $returnList;
    }

    /**
     * 入力データをcomiket_boxテーブルに登録できるように整形する
     * @param type $eventSel
     * @return string
     */
    public function _createComiketBoxInsertDataByInForm($inForm, $id) {

        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();
        $returnList = array();

        if($inForm['comiket_detail_type_sel'] == "1"
                || $inForm['comiket_detail_type_sel'] == "3" ) { // 搬入（もしくは搬入搬出）
            if($inForm['comiket_detail_outbound_service_sel'] == "1") { // 宅配
                $isFirst = true;
                foreach($inForm['comiket_box_outbound_num_ary'] as $key => $val) { 
                    $boxData = $this->_BoxService->fetchBoxById($db, $key);
                    if(empty($val)) {
                        continue;
                    }
                    if(!isset($inForm["outbound_hatsu_jis2code"])){
                        $outbound_hatsu_jis2code = 0;
                        $outbound_chaku_jis2code = 0;
                    }else{
                        $outbound_hatsu_jis2code = $inForm['outbound_hatsu_jis2code'];
                        $outbound_chaku_jis2code = $inForm['outbound_chaku_jis2code'];
                    }
                    if ($isFirst) {
                        $boxFareData = $this->_BoxFareService->fetchBoxFareByJis2AndBoxId($db, $outbound_hatsu_jis2code, $outbound_chaku_jis2code, $boxData['cd'], $inForm['eventsub_sel']);
                    } else {
                        $boxFareData = $this->_BoxFareService->fetchBoxFareByJis2AndBoxId($db, $outbound_chaku_jis2code , $outbound_hatsu_jis2code, $boxData['cd'], $inForm['eventsub_sel']);
                    }
                    
                    if(empty($boxFareData)) {
                        $fare = 0;
                    } else {
                        $fare = intval($boxFareData["fare"]);
                    }

                    $fareAmount = $fare * intval($val);
                    $data = array(
                        "comiket_id" => $id,
                        "type" => $isFirst ? "1" : "2", // 搬入
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
                    
                    $isFirst = false;
                }
            }
        }
        
        return $returnList;
    }

    public function _createComiketCargoInsertDataByInForm($inForm, $id) {

        $db = Sgmov_Component_DB::getPublic();

        $returnList = array();

            if ($inForm['comiket_detail_type_sel'] == "1" || $inForm['comiket_detail_type_sel'] == "3") { // 搬入（もしくは搬入搬出）
                if ($inForm['comiket_detail_outbound_service_sel'] == "2") { // カーゴ
                    $cargoFareData = @$this->_CargoFareService->fetchCargoFareByJis2AndCargoNum(
                            $db, $inForm["outbound_hatsu_jis2code"], $inForm["outbound_chaku_jis2code"], $inForm["comiket_cargo_outbound_num_sel"], $inForm["eventsub_sel"]); // 13は東京(jis2code)

                        $data = array(
                            "comiket_id" => $id,
                            "type" => "1", // 搬入
                            "num" => @$inForm["comiket_cargo_outbound_num_sel"],
                            "fare_amount" => @$cargoFareData["cargo_fare"],
                        );
                        $returnList[] = $data;
                }
            }

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
                    );
                    $returnList[] = $data;
                }
            }
        }

        if($inForm['comiket_detail_type_sel'] == "2"
                || $inForm['comiket_detail_type_sel'] == "3" ) { // 搬出（もしくは搬入搬出）
            if($inForm['comiket_detail_inbound_service_sel'] == "3") { // チャーター
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
                    );
                    $returnList[] = $data;
                }
            }
        }

        return $returnList;
    }

    /**
     * 搬入出の申込期間チェック
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
     * 現在日時が申込期間内かチェック
     * @param type $keyPrefix
     * @param type $eventsubId
     * @return boolean
     */
    public function isCurrentDateWithInTerm($keyPrefix, $eventsubId) {
        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();
        $eventsubData = $this->_EventsubService->fetchEventsubByEventsubId($db, $eventsubId);
//        $departureTo = $eventsubData["departure_to"];

        // 開始日時
        $termFr = $eventsubData["{$keyPrefix}_fr"];
        $termFrDateTime = new DateTime($termFr);
        $termFrYMD = $termFrDateTime->format("Y-m-d");

        // 現在日時
        $currentDateTime = new DateTime('now');
        $currentYMDForFr = $currentDateTime->format("Y-m-d");

        // 復路・搬出期限
        if($keyPrefix == 'arrival') {
            $currentYMDForTo = $currentDateTime->format("Y-m-d H:i:s");
            // 復路・搬出終了日時(申込終了日時)
            $termTo = $eventsubData["{$keyPrefix}_to_time"];
            $termToDateTime = new DateTime($termTo);
            $termToYMD = $termToDateTime->format("Y-m-d H:i:s");
        // 往路・搬入期限
        } else {
            $currentYMDForTo = $currentDateTime->format("Y-m-d");

            // 往路・搬入終了日
            $termTo = $eventsubData["{$keyPrefix}_to"];
            $termToDateTime = new DateTime($termTo);
            $termToYMD = $termToDateTime->format("Y-m-d");
        }

        // eventsubのどちらかでチェック
        // 往路・搬入：departure_fr <= 現在日時 <= departure_to
        // 復路・搬出：arrival_fr   <= 現在日時 <= arrival_to_time
        if($termFrYMD <= $currentYMDForFr && $currentYMDForTo <= $termToYMD) {
            return TRUE;
        }

        return FALSE;
    }

    /*
    * チェックデジットの算出
    */
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
                );

                $total = 0;
                for ($i = 0; $i < count($intCheck); $i++) {
                    $total += $param2[$i] * $intCheck[$i];
                }

        return $total;
    }

    /*
    * チェックデジットの算出
    */
    public static function getChkD2($param) {

        $target = intval($param);
        $result = $target % 7;
        return $result;
    }

    /**
     * 搬入/搬出、個人/法人 、日付指定の有無チェック
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
     * 搬入/搬出、個人/法人 、時間指定の有無チェック
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

    /**
     * 完了メール送信
     * @param $comiket 設定用配列
     * @param $sendTo2 宛先
     * @param sendCc   転送先
     * @param $type    往復区分
     * @return bool true:成功
     */
    public function sendCompleteMail($comiket, $sendTo2 = '', $sendCc = '', $type = '', $tmplateType = '') {

        try {

            if (@empty($tmplateType)) {
                //添付ファイルの有無を判別
                $isAttachment = ( $comiket['choice'] == 2 || $comiket['choice'] == 3 ) ? true : false;
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
            if(empty($sendTo2)) {
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

            $week = ['日', '月', '火', '水', '木', '金', '土'];
            
            $comiketPrefData = $this->_PrefectureService->fetchPrefecturesById($db, $comiket['pref_id']);

            $data['comiket_id'] = sprintf('%010d', $comiket['id']);//【コミケID】
            $data['event_name'] = $eventData["name"] . $eventsubData["name"]; //【ツアー名】
            $data['comiket_personal_name_seimei'] = $comiket['personal_name_sei'] . " " . $comiket['personal_name_mei'];//【お申込者】
            
            $data['surname'] = $comiket['personal_name_sei'];
            $data['forename'] = $comiket['personal_name_mei'];
            
            $data['comiket_mail'] = $comiket['mail'];//【メール】
                    
            $data['comiket_building_name'] = $comiket['building_name'];
            $data['comiket_payment_method'] = "売掛";

            $data['comiket_building'] = $comiket['building_name'];//【ビル】
            $data['comiket_tel'] = $comiket['tel'];//【電話番号】
            $data['comiket_mail'] = $comiket['mail'];//【メール】
            $data['comiket_staff_seimei'] = $comiket['staff_sei'] . " " . $comiket['staff_mei'];//【セイ】【メイ】
            $data['comiket_staff_tel'] = $comiket['staff_tel'];//【担当電話番号】
            //
              // 【宿泊先】
            $data['comiket_building_name'] = $comiket['building_name'];
            
            $mailTemplate[] = "/".$this->_DirDiv."/complete_individual.txt";
            $mailTemplateSgmv[] = "/".$this->_DirDiv."/complete_individual_sgmv.txt";

            $data['comiket_zip'] = "〒" . substr($comiket['zip'], 0, 3) . '-' . substr($comiket['zip'], 3);//【郵便番号】
            $data['comiket_pref_name'] = $comiketPrefData['name'];//【都道府県】
            $data['comiket_address'] = $comiket['address'];//【住所】
            $data['comiket_building'] = $comiket['building'];//【ビル】
            $data['comiket_tel'] = $comiket['tel'];//【電話番号】

            //サービステンプレート
            $mailTemplate[] = "/".$this->_DirDiv."/parts_complete_choice_1.txt";
            $mailTemplateSgmv[] = "/".$this->_DirDiv."/parts_complete_choice_1_sgmv.txt";
            if ($type == '3') {//往復
                $mailTemplate[] = "/".$this->_DirDiv."/parts_complete_choice_2.txt";
                $mailTemplateSgmv[] = "/".$this->_DirDiv."/parts_complete_choice_2.txt";    
                //集荷の往復
                $data['comiket_choice'] = "宿泊施設 → 自宅";
            } else {
                //集荷の往復
                $data['comiket_choice'] = "自宅 → 宿泊施設";
            }
            
            // 数量
            $comiket_detail_list = $comiket['comiket_detail_list'];
            $oRoNum = "往路：". $comiket_detail_list[0]['comiket_box_list'][0]['num']. "個";
            $fukuRoNum = "";
            if ($type == '3') {
                $fukuRoNum = "\n　復路：" . $comiket_detail_list[1]['comiket_box_list'][1]['num']. "個";
            }
            $data['num_area'] = $oRoNum . $fukuRoNum;
            
            //問合せ番号
            $oroToi = "【往路・問合せ番号】";
            $fukuRoToi = "";
            
            //往路
            $comiket_detailOro = $comiket_detail_list[0];
            if(empty($comiket_detailOro['collect_date'])) {
                $collectDateName = "";
            } else {
                $collectDate = new DateTime($comiket_detailOro['collect_date']);
                $extWeek = date('w',strtotime($comiket_detailOro["collect_date"]));
                $collectDateName = $collectDate->format('Y年m月d日')."(".$week[$extWeek].")";
            }

            if(empty($comiket_detailOro['delivery_date'])) {
                $deliveryDateName = "";
            } else {
                $deliveryDate = new DateTime($comiket_detailOro['delivery_date']);
                $extWeek = date('w',strtotime($comiket_detailOro["delivery_date"]));
                $deliveryDateName = $deliveryDate->format('Y年m月d日')."(".$week[$extWeek].")";
            }

            if(empty($comiket_detailOro['collect_st_time'])
                    || $comiket_detailOro['collect_st_time'] == "00") {
                $collectStTimeName = "指定なし";
                $collectEdTimeName = "";
            } else {
                $collectStTime = new DateTime($comiket_detailOro['collect_st_time']);
                $collectEdTime = new DateTime($comiket_detailOro['collect_ed_time']);
                $collectStTimeName = $collectStTime->format("H:i") . "～";
                $collectEdTimeName = $collectEdTime->format("H:i");
            }

            if(empty($comiket_detailOro['delivery_st_time'])
                    || $comiket_detailOro['delivery_st_time'] == "00") {

                $deliveryStTimeName = @$comiket_detailOro['delivery_timezone_name'];
                $deliveryEdTimeName = "";
            } else {
                $deliveryStTime = new DateTime($comiket_detailOro['delivery_st_time']);
                $deliveryEdTime = new DateTime($comiket_detailOro['delivery_ed_time']);
                $deliveryStTimeName = $deliveryStTime->format("H:i") . "～";
                $deliveryEdTimeName = $deliveryEdTime->format("H:i");
            }

            $data['type1_collect_date'] = $collectDateName;                                                            //【お預かり日時】
            $data['type1_collect_st_time'] = $collectStTimeName;
            $data['type1_collect_ed_time'] = $collectEdTimeName;

            $data['type1_delivery_date'] = $deliveryDateName;                                                          //【お届け日時】
            $data['type1_delivery_st_time'] = $deliveryStTimeName;
            $data['type1_delivery_ed_time'] = $deliveryEdTimeName;
            $oroToi .= @$comiket_detailOro["toiawase_no_niugoki"];                                                    //【問合せ番号】
            //復路
            if ($type == '3') {
                $fukuRoToi = "\n【復路・問合せ番号】";
                $comiket_detailFukuRo = $comiket_detail_list[1];
                if(empty($comiket_detailFukuRo['collect_date'])) {
                    $collectDateName = "";
                } else {
                    $collectDate = new DateTime($comiket_detailFukuRo['collect_date']);
                    $extWeek = date('w',strtotime($comiket_detailFukuRo["collect_date"]));
                    $collectDateName = $collectDate->format('Y年m月d日')."(".$week[$extWeek].")";
                }             
                $data['type2_collect_date'] = $collectDateName;                                                            //【復路集荷日】
                $fukuRoToi .= @$comiket_detailFukuRo["toiawase_no_niugoki"];                                               //【問合せ番号】
            }
            
            $data['toiawase_no'] = $oroToi.$fukuRoToi;
            
//            foreach ($comiket_detail_list as $k => $comiket_detail) {
//                
//                $comiket_box_list = (isset($comiket_detail['comiket_box_list'])) ? $comiket_detail['comiket_box_list'] : array();
//                
//                foreach ($comiket_box_list as $cb => $comiket_box) {
//                    $data['type1_num_area'] = $oRoNum . $comiket_box['num']. "個";
//                }
//
//                if(empty($comiket_detail['collect_date'])) {
//                    $collectDateName = "";
//                } else {
//                    $collectDate = new DateTime($comiket_detail['collect_date']);
//                    $extWeek = date('w',strtotime($comiket_detail["collect_date"]));
//                    $collectDateName = $collectDate->format('Y年m月d日')."(".$week[$extWeek].")";
//                }
//
//                if(empty($comiket_detail['delivery_date'])) {
//                    $deliveryDateName = "";
//                } else {
//                    $deliveryDate = new DateTime($comiket_detail['delivery_date']);
//                    $extWeek = date('w',strtotime($comiket_detail["delivery_date"]));
//                    $deliveryDateName = $deliveryDate->format('Y年m月d日')."(".$week[$extWeek].")";
//                }
//
//                if(empty($comiket_detail['collect_st_time'])
//                        || $comiket_detail['collect_st_time'] == "00") {
//                    $collectStTimeName = "指定なし";
//                    $collectEdTimeName = "";
//                } else {
//                    $collectStTime = new DateTime($comiket_detail['collect_st_time']);
//                    $collectEdTime = new DateTime($comiket_detail['collect_ed_time']);
//                    $collectStTimeName = $collectStTime->format("H:i") . "～";
//                    $collectEdTimeName = $collectEdTime->format("H:i");
//                }
//
//                if(empty($comiket_detail['delivery_st_time'])
//                        || $comiket_detail['delivery_st_time'] == "00") {
//                    
//                    $deliveryStTimeName = @$comiket_detail['delivery_timezone_name'];
//                    $deliveryEdTimeName = "";
//                } else {
//                    $deliveryStTime = new DateTime($comiket_detail['delivery_st_time']);
//                    $deliveryEdTime = new DateTime($comiket_detail['delivery_ed_time']);
//                    $deliveryStTimeName = $deliveryStTime->format("H:i") . "～";
//                    $deliveryEdTimeName = $deliveryEdTime->format("H:i");
//                }
//
//                $data['type1_collect_date'] = $collectDateName;                                                            //【お預かり日時】
//                $data['type1_collect_st_time'] = $collectStTimeName;
//                $data['type1_collect_ed_time'] = $collectEdTimeName;
//                
//                $data['type1_delivery_date'] = $deliveryDateName;                                                          //【お届け日時】
//                $data['type1_delivery_st_time'] = $deliveryStTimeName;
//                $data['type1_delivery_ed_time'] = $deliveryEdTimeName;
//                $data['toiawase_no'] = @$comiket_detail["toiawase_no_niugoki"];                                                    //【問合せ番号】
//
//            }
                       
            $mailTemplate[] = "/".$this->_DirDiv."/parts_complete_footer.txt";
            $mailTemplateSgmv[] = "/".$this->_DirDiv."/parts_complete_footer.txt";
            
            $data['comiket_amount'] = '\\' . number_format($comiket['amount']);
            $data['comiket_amount_tax'] = '\\' . number_format($comiket['amount_tax']);
            
            //-------------------------------------------------
            //メール送信実行
            $objMail = new Sgmov_Service_CenterMail();
            // 申込者へメール
            $objMail->_sendThankYouMail($mailTemplate, $sendTo, $data);

            // 2018.09.10 tahira add 申込者と営業所で使用するメールテンプレートをわけ別々に送信する
            // 営業所へメール(CCとして設定する用にもっていた変数をToの方へ)
            if ($sendCc !== null && $sendCc !== '') {
                $objMail->_sendThankYouMail($mailTemplateSgmv, $sendCc, $data);
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
     * 業務連携申込キャンセル処理
     * @param type $wsProtocol
     * @param type $wsHost
     * @param type $wsPath
     * @param type $wsPort
     * @param type $param comiket_id
     * @param type $paramOrg comiket_id + チェックデジット(モジュラス)
     */
    protected function execWebApiCancelComiket($wsProtocol, $wsHost, $wsPath, $wsPort, $param, $paramOrg , $dispErrTitle = "", $dispErrMsg = "") {
        
        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();
        
        $comiketInfo = $this->_ComiketService->fetchComiketById($db, $param);

        // コミケ明細申込データ
        $comiketDetailList = $this->_ComiketDetailService->fetchComiketDetailByComiketId($db, $param);
        
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // ▼ 業務連携用リクエストデータ作成
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        
        $sendFileName = 'UNA_' . date ( 'YmdHis' ) . '.csv';
        $boundary = "-----" . md5 ( uniqid () );
        
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
        $contentLength = strlen ( $body );
        
        $header = "";
        $header .= "POST " . $wsPath . " HTTP/1.1\r\n";
        $header .= "Host: " . $wsHost . "\r\n";
        $header .= "Content-type: multipart/form-data, boundary={$boundary}\r\n";
        $header .= "Connection: close\r\n";
        $header .= "Content-length: {$contentLength}\r\n";
        $header .= "\r\n";
        
        $request = $header . $body;
        
        // デバッグログを出力
        if (Sgmov_Component_Log::isDebug ()) {
                Sgmov_Component_Log::debug ( "リクエストデータ\n" . $request );
        }

        //////////////////////////////////////////////////
        // 業務連携開始
        //////////////////////////////////////////////////
        
        $errno = "";
        $errstr = "";
        try {
            $fp = fsockopen ( $wsProtocol . $wsHost, $wsPort, $errno, $errstr, 30 );
            if (! $fp) {
                    Sgmov_Component_Log::debug ( $wsProtocol );
                    Sgmov_Component_Log::debug ( $wsHost );
                    Sgmov_Component_Log::debug ( $wsPort );
                    Sgmov_Component_Log::debug ( $errno );
                    Sgmov_Component_Log::debug ( $errstr );
                    throw new Exception(@"業務サーバへの接続に失敗しました。\n _wsProtocol: {$wsProtocol} / _wsHost: {$wsHost} / _wsPort: {$wsPort}");
            }

            // デバッグログを出力
            if (Sgmov_Component_Log::isDebug ()) {
                    Sgmov_Component_Log::debug ( "接続確認\n" . $fp );
            }

            // デバッグログを出力
            if (Sgmov_Component_Log::isDebug ()) {
                    Sgmov_Component_Log::debug ( "IF処理開始\n" . $fp );
            }

            // データ送信
            if (! fwrite ( $fp, $request )) {

                    // デバッグログを出力
                    if (Sgmov_Component_Log::isDebug ()) {
                            Sgmov_Component_Log::debug ( "リクエストの送信に失敗しました。\n" . $fp );
                    }

                    throw new Sgmov_Component_Exception ( 'リクエストの送信に失敗しました。', Sgmov_Component_ErrorCode::ERROR_BCR_WS_SEND );
            }

            // デバッグログを出力
            if (Sgmov_Component_Log::isDebug ()) {
                    Sgmov_Component_Log::debug ( "データ送信完了\n" . $fp );
            }

            // デバッグログを出力
            if (Sgmov_Component_Log::isDebug ()) {
                    Sgmov_Component_Log::debug ( "ステータスラインの受信開始。\n" . $fp );
            }

            // ステータスラインの受信
            if (! ($status = fgets ( $fp ))) {

                    // デバッグログを出力
                    if (Sgmov_Component_Log::isDebug ()) {
                            Sgmov_Component_Log::debug ( "ステータスラインの受信に失敗しました。\n" . $fp );
                    }

                    throw new Sgmov_Component_Exception ( 'ステータスラインの受信に失敗しました。', Sgmov_Component_ErrorCode::ERROR_BCR_WS_RECV_STATUS );
            }

            // 受信ステータスをログ出力
            if (Sgmov_Component_Log::isDebug ()) {
                    Sgmov_Component_Log::debug ( "recvStatus\n" . $status );
            }

            // ステータスコードの確認
            if (substr_count ( $status, "200 OK" ) == 0) {
                    throw new Sgmov_Component_Exception ( 'ステータスが200ではありません。', Sgmov_Component_ErrorCode::ERROR_BCR_WS_BAD_STATUS );
            }

            // データの受信
            $response = '';

            while ( ! feof ( $fp ) ) {
                if (($buf = fread ( $fp, 4096 )) == FALSE) {
                        throw new Sgmov_Component_Exception ( 'データの受信に失敗しました。', Sgmov_Component_ErrorCode::ERROR_BCR_WS_RECV_DATA );
                }
                $response .= $buf;
            }
            $response = @mb_convert_encoding($response, "UTF-8", "SJIS");
            // レスポンス値を強制出力（強制のためwarningレベル）
            Sgmov_Component_Log::warning ( "レスポンス\n" . $response);
            $response = substr ( $response, strpos ( $response, "\r\n\r\n" ) + 4 );
            
            $resBody = explode ( "\r\n", $response );
            
            
            if (count($resBody) < 4 || $resBody [1] != "\"HEADER\"" || $resBody[3] != "\"TRAILER\"") {
                throw new Sgmov_Component_Exception ( 'データの受信に失敗しました。', Sgmov_Component_ErrorCode::ERROR_BCR_WS_RECV_DATA );
            }
            
            $item = explode ( "\",\"", $resBody [2] );
            
            $sendStatus = trim ( $item [0], '"' );
            $ukeNo = trim ( $item [1], '"' );
            $exception = trim ( $item [2], '"' );
            $exceptionMessage = trim ( $item [3], '"' );
            
            if ($sendStatus != "0") {
                throw new Exception(@"sendStatus: {$sendStatus} / ukeNo: {$ukeNo} / exception: {$exception} / exceptionMessage: {$exceptionMessage}");
            }
            
            // 接続終了
            @fclose ( $fp );
        } catch ( Exception $e ) {

            // 接続終了
            @fclose ( $fp );

            // デバッグログを出力
            if (Sgmov_Component_Log::isDebug ()) {
                    Sgmov_Component_Log::debug ( "エラー内容ログ出力\n" . $e );
            }
            $clasName = get_class($this);
            $message = "イベント申込: 業務連携に失敗しました。\n[{$comiketInfo['event_key']}] {$clasName} : paramOrg = {$paramOrg} / param = {$param}\n\nDB comiket.del_flg（論理削除フラグ）は更新しています。\n\n";
            $message .= "Exceptionメッセージ: " . $e->getMessage() . "\n\n";
            $message .= "業務サーバ接続情報: _wsProtocol: {$wsProtocol} / _wsHost: {$wsHost} / _wsPort: {$wsPort} / _wsPath: {$wsPath}";

            // システム管理者メールアドレスを取得する。
            $mailTo = Sgmov_Component_Config::getLogMailTo ();

            $mailData = $comiketInfo;
            $mailData['message'] = $message;
            $divType = "individual";
            if ($comiketInfo['div'] == self::COMIKET_DEV_BUSINESS) {
                $divType = "business";
            }
            $mailTemplateList = array(
                "/".$this->_DirDiv."/cancel_{$divType}_error.txt",
//                "/".$this->_DirDiv."/parts_cancel_footer_type_{$comiketDetailInfo['type']}.txt",
            );

            // 問い合わせ番号
            $mailData['toiawase_no'] = $comiketDetailList["0"]["toiawase_no"];

            $mailData['explanation_url_convenience_payment_method'] = " ";
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
     * 搬入、搬出の有効期間チェック
     * @param type $comiketId
     */
    protected function checkReqDate($comiketId, $titleParts = "", $MessageParts = "") {
        if (@!empty($titleParts) && @empty($MessageParts)) {
            $MessageParts = $titleParts;
        }

        $db = Sgmov_Component_DB::getPublic();
        $comiketInfo = $this->_Comiket->fetchComiketById($db, $comiketId);
        $comiketDetailList = $this->_ComiketDetail->fetchComiketDetailByComiketId($db, $comiketId);

        foreach ($comiketDetailList as $key => $comiketDetailInfo) {
            if($comiketDetailInfo['type'] == '1') { // 往路
                
                /////////////////////////////////////////////////////////////////////////////////////////////
                // 各地域ごとの締切日チェック
                /////////////////////////////////////////////////////////////////////////////////////////////
                $eveSubData = $this->_EventsubService->fetchEventsubByEventsubId($db, $comiketInfo['eventsub_id']);
                $chakuJis2 = substr($eveSubData['jis5cd'], 0, 2);
                $hatsuJis2 = $comiketDetailInfo['pref_id'];
                $outBoundUnCollectCalInfo = $this->_OutBoundCollectCal->fetchOutBoundCollectCalByHaChaku($db, $comiketInfo['eventsub_id'], $hatsuJis2, $chakuJis2);
                
                $dateChNow = (new DateTime());
                $dateChArrival = new DateTime($outBoundUnCollectCalInfo['arrival_date']);

                if ($dateChArrival->format('Y-m-d H:i:s') <= $dateChNow->format('Y-m-d H:i:s')) {
                    $collectDate2 = $dateChArrival->format('Y-m-d H:i:s');
                    $collectDate2 = date('Y年m月d日 H:i:s', strtotime($collectDate2));

                    $title = "催事・イベント配送受付サービスの搬入の{$titleParts}の受付終了しました";
                    $message = "既に {$collectDate2} を過ぎているため搬入の{$titleParts}のお申し込みができませんでした。";
                    Sgmov_Component_Redirect::redirectPublicSsl("/".$this->_DirDiv."/error?t={$title}&m={$message}");
                }

                /////////////////////////////////////////////////////////////////////////////////////////////
                // 毎日お昼の１２時が【翌日集荷の指定締切り時間】 チェック
                /////////////////////////////////////////////////////////////////////////////////////////////
                $collectDate = $comiketDetailInfo['collect_date'];
                $lastSyukaTime = $this->getLastSyukaTime();
                $collectDate2 = date('Y-m-d H:i:s', strtotime("{$collectDate} {$lastSyukaTime} -1 day"));
                $toDate = date('Y-m-d H:i:s');

                if ($collectDate2 <= $toDate) {
                    $collectDate2 = date('Y年m月d日 H:i', strtotime($collectDate2));

                    $title = "催事・イベント配送受付サービスの搬入の{$titleParts}お申し込みができませんでした";
                    $message = "既に {$collectDate2} を過ぎているため搬入の{$titleParts}のお申し込みができませんでした。(集荷日前日 {$lastSyukaTime}まで可能)";
                    Sgmov_Component_Redirect::redirectPublicSsl("/".$this->_DirDiv."/error?t={$title}&m={$message}");
                }
            } else { // 復路
                $eventsubInfo = $this->_EventsubService->fetchEventsubByEventsubId($db, $comiketInfo['eventsub_id']);
                $toDate = date('Y-m-d H:i:s');
                $eventTermEndDate = date('Y-m-d H:i:s', strtotime($eventsubInfo['arrival_to_time']));

                if ($eventTermEndDate <= $toDate) {
                    $eventTermEndDate2 = date('Y年m月d日', strtotime($eventTermEndDate));

                    $title = urlencode("催事・イベント配送受付サービスの搬出の{$titleParts}お申し込みができませんでした");
                    $message = urlencode("既に {$eventTermEndDate2} を過ぎているため搬出の{$titleParts}のお申し込みができませんでした。({$eventTermEndDate}まで可能)");
                    Sgmov_Component_Redirect::redirectPublicSsl("/".$this->_DirDiv."/error?t={$title}&m={$message}");
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
    protected function checkCoolbinClosingDate($inForm, $isRedirect = true, $msgParts = 'お申込') {
        
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
                    Sgmov_Component_Redirect::redirectPublicSsl("/".$this->_DirDiv."/error/?t={$title}&m={$message}");
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
    
    private function getListYearMonthDay($startDate, $endDate) {
        $result = array();
        $minYear = $startDate->format('Y');
        $maxYear = $endDate->format('Y');
        $years  = $this->_appCommon->getYears($startDate->format('Y'), $maxYear-$minYear, false);
        
        $minMoth = $startDate->format('n');
        $maxMoth = $endDate->format('n');
        
        $months = array('');
        if ($minMoth == $maxMoth) {
            $months[] = $minMoth;
        } else {
            $months[] = $minMoth;
            $months[] = $maxMoth;
        }

        $days   = array('');
        $period = new DatePeriod($startDate,new DateInterval('P1D'),$endDate);
        
        foreach ($period as $key => $value) {
            $days[] = $value->format('d');
        }
        
        array_shift($months);
        array_shift($days);
        
        $result[0] = $years;
        $result[1] = $months;
        $result[2] = $days;
        
        return $result;
    }
}
