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
    , 'AppCommon', 'HttpsZipCodeDll', 'BoxFare', 'CargoFare', 'Comiket', 'ComiketDetail','EventsubCmb', 'Time', 'Hachakuten', 'MlkBoxFare'));
Sgmov_Lib::useView('Public');

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
    const FEATURE_ID = 'MLK';

    /**
     * EVE001の画面ID
     */
    const GAMEN_ID_EVE001 = 'MLK001';

    /**
     * EVE002の画面ID
     */
    const GAMEN_ID_EVE002 = 'MLK002';

    /**
     * EVE003の画面ID
     */
    const GAMEN_ID_EVE003 = 'MLK003';

    /**
     * 個人
     */
    const COMIKET_DEV_INDIVIDUA = "1";

    /**
     * 法人
     */
    const COMIKET_DEV_BUSINESS = "2";
    const COMIKET_DIV_SETCHI = "3"; //識別:1:個人、2:法人、3：設置
    
    
    const COMIKET_DETAIL_OFUKU_TYPE_SEL = 3;//往復

    const CURRENT_TAX = 1.10;

    const DUPLICATE_DURATION_MONTHS = 3;//同じ発着点識別番号で登録出来ない期間
    
    const DELIVERY_TYPE_HOTEL = 3; //画面のお届け先の選択がホテル
    const DELIVERY_TYPE_SERVICE = 2;//画面のお届け先の選択がサービスセンター
    const DELIVERY_TYPE_AIRPORT = 1;//画面のお届け先の選択が空港
    const TYPE_NONE = 0;
    const ADDTION_FLIGHT_END = '17:59';//空港の申込の場合、受け付け時間は※翌日17：59は固定
    
    const SHIKIBETU_CD_DEFAULT  = '00000000';//金額計算する時、選択した発着識別番号で該当データが無い場合、この値でもう一回取得する
    
    //const ADDRESS_CENTER = '東京都中央区京橋１丁目２−４ 八重洲ノリオビル １Ｆ';//往路の着先住所、復路の出発住所
    const ADDRESS_CENTER = '東京都江東区新砂１丁目１２−１４ 佐川急便　城西営業所';//往路の着先住所、復路の出発住所
    
    const TEL_CENTER = '0570-01-0364';

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
     * @var array
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

    public $address_type_lbls = array(
        1 => '空港',
        2 => '佐川急便手ぶら観光カウンター',
        3 => 'ホテル',
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
    protected $_HachakutenService;
    protected $_MlkBoxFareService;
    
    // イベント識別子
    protected $_DirDiv;
    
    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
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
        $this->_HachakutenService       = new Sgmov_Service_Hachakuten();
        $this->_MlkBoxFareService       = new Sgmov_Service_MlkBoxFare();
        
        $comiketCargoItemList = array();
        for($i=1; $i <= 99; $i++) {
            $comiketCargoItemList[$i] = $i;
        }
        $this->comiket_cargo_item_list = $comiketCargoItemList;
        // イベント識別子のセット
        $this->_DirDiv = Sgmov_Lib::setDirDiv(dirname(__FILE__));
        
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
     * プルダウンを生成し、HTMLソースを返します。
     * TODO pre/Inputと同記述あり
     *
     * @param $cds コードの配列
     * @param $lbls ラベルの配列
     * @param $select 選択値
     * @return 生成されたプルダウン
     */
    public static function _createPulldownNoTranslate($cds, $lbls, $select, $flg = null, $date = null) {

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
                $html .= '<option value="' . $cds[$i] . '" selected="selected"' . $timeover . $timeoverDt . ' data-stt-ignore>' . $lbls[$i] . '</option>' . PHP_EOL;
            } else {
                $html .= '<option value="' . $cds[$i] . '"' . $timeover . $timeoverDt . ' data-stt-ignore>' . $lbls[$i] . '</option>' . PHP_EOL;
            }
        }

        return $html;
    }

    public static function _getWeek($year, $month, $day) {
        $week = array("日", "月", "火", "水", "木", "金", "土");
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
        Sgmov_Component_Log::debug("########################## 503 createOutFormByInForm");
        $dispItemInfo = array();

        // TODO オブジェクトから値を直接取得できるよう修正する
        //
        // オブジェクトから取得できないため、配列に型変換
        $inForm = (array)$inForm;
        
        $db = Sgmov_Component_DB::getPublic();
        
        $services = $this->_HachakutenService->fetchHachakutenByType($db, self::DELIVERY_TYPE_SERVICE);
        $hotels = $this->_HachakutenService->fetchHachakutenByType($db, self::DELIVERY_TYPE_HOTEL);
        $airports = $this->_HachakutenService->fetchHachakutenByType($db, self::DELIVERY_TYPE_AIRPORT);

        $eventAll = $this->_EventService->fetchEventListWithinTerm2($db, NULL, NULL, self::FEATURE_ID);

        $eventAll2 = array();
        $eventIds = array();
        $eventNames = array();
        $eventNames2 = array();

        $week = array("日", "月", "火", "水", "木", "金", "土");
        foreach($eventAll as $key => $val) {
            $eventIds[] = $val["id"];
            $eventNames[] = $val["name"];
            $eventNames2[] = $val["event_name"] . "　" . $val["eventsub_name"];

            $eventAll2[] = $val;
        }

        $outForm->raw_comiket_id  = @$inForm['comiket_id'];

        // 出展イベント
        $dispItemInfo["event_alllist"] = $eventAll2;
        $outForm->raw_event_cds  = $eventIds;
//        $outForm->raw_event_lbls = $eventNames;
        $outForm->raw_event_lbls = $eventNames2;
        $outForm->raw_event_cd_sel = $inForm["event_sel"];


        // 出展イベントサブ
        $eventsubAry2 = $this->_EventsubService->fetchEventsubListWithinTermByEventId($db, $inForm["event_sel"], NULL, NULL, self::FEATURE_ID);

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

            $inboundHatsuJis2 = "";
            $inboundChakuJis2 = "";


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
Sgmov_Component_Log::debug($inForm);

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
        $dispItemInfo["box_lbls"] = array();
        
        $dispItemInfo["outbound_box_lbls"] = $this->_BoxService->fetchBox2($db, $inForm["eventsub_sel"], $inForm["comiket_div"], "1"); // 搬入
        $dispItemInfo["inbound_box_lbls"] = $this->_BoxService->fetchBox2($db, $inForm["eventsub_sel"], $inForm["comiket_div"], "2"); // 搬出
        $dispItemInfo["box_lbls"] = array_merge($dispItemInfo["outbound_box_lbls"], $dispItemInfo["inbound_box_lbls"]);
        


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
        $timeDataList = $this->_TimeService->fetchTimeDataListByClassCd($db, '2');

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
        $outForm->raw_comiket_box_inbound_num_ary = $inForm["comiket_box_inbound_num_ary"]; //cau truc [key => 1]

        // 搬出-カーゴ
//        $outForm->raw_comiket_cargo_inbound_num_ary = $inForm["comiket_cargo_inbound_num_ary"];
        $outForm->raw_comiket_cargo_inbound_num_sel = $inForm["comiket_cargo_inbound_num_sel"];
        $outForm->raw_comiket_cargo_inbound_num_cds = array_keys($this->comiket_cargo_item_list);
        $outForm->raw_comiket_cargo_inbound_num_lbls = array_values($this->comiket_cargo_item_list);

        // 搬出-チャーター
        $outForm->raw_comiket_charter_inbound_num_ary = $inForm["comiket_charter_inbound_num_ary"];

        // 搬出-備考
        $outForm->raw_comiket_detail_inbound_note = $inForm["comiket_detail_inbound_note"];

        // 搬入-備考-1行目
        $outForm->raw_comiket_detail_inbound_note1 = $inForm["comiket_detail_inbound_note1"];

        // 搬入-備考-2行目
        //$outForm->raw_comiket_detail_inbound_note2 = $inForm["comiket_detail_inbound_note2"];

        // 搬入-備考-3行目
        //$outForm->raw_comiket_detail_inbound_note3 = $inForm["comiket_detail_inbound_note3"];

        // 搬入-備考-4行目
        //$outForm->raw_comiket_detail_inbound_note4 = $inForm["comiket_detail_inbound_note4"];

/////////////////////////////////////////////////////////////////////////////////////////////////////////
// 支払
/////////////////////////////////////////////////////////////////////////////////////////////////////////
        // 送料
        //$outForm->raw_delivery_charge = $inForm["delivery_charge"];

        // リピータ割引
        //$outForm->raw_repeater_discount = $inForm["repeater_discount"];

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
        
////////////////////////////////////////////////////////////////////////////////
// ホテル対応用
////////////////////////////////////////////////////////////////////////////////
        
//        $lang = @substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
//        if (@empty($_SESSION["common.web_lang"])) {
//            $_SESSION["common.web_lang"] = $lang;
//        } else {
//            $lang = $_SESSION["common.web_lang"];
//        }
//Sgmov_Component_Log::debug($_SERVER);
//        $outForm->raw_parcel_room_cd_sel = 'ja';
//        $outForm->raw_parcel_room_cds = array('ja');
//        $outForm->raw_parcel_room_lbls = array($dispItemInfo["eventsub_selected_data"]['parcel_room']);
//        if ($lang != 'ja' && @!empty($lang)) {
//            $parcelRoom = $dispItemInfo["eventsub_selected_data"]['parcel_room_en'];
//            $outForm->raw_parcel_room_cd_sel = 'en';
//            $outForm->raw_parcel_room_cds = array('en');
//            $outForm->raw_parcel_room_lbls = array($dispItemInfo["eventsub_selected_data"]['parcel_room_en']);
//        }
        
        $outForm->raw_comiket_detail_inbound_delivery_date_cd_sel = $inForm['comiket_detail_inbound_delivery_date_cd_sel'];
        $outForm->raw_comiket_detail_inbound_delivery_date_cds = array();
        $outForm->raw_comiket_detail_inbound_delivery_date_lbls = array();
        
        $targetDateCds = date('Ymd');
        $targetDateLbls = date('Y/m/d');
        
        $toDate = date('Ymd H:i:s');
        
        $maxCt = 3;
        for ($i=0; $i<=$maxCt; $i++) {
            $deliveryDateCd = date("Ymd", strtotime($targetDateCds . "+{$i} day"));
            if ($deliveryDateCd . " 06:00:00" <= $toDate) {
                $maxCt++;
                continue;
            }
            $outForm->raw_comiket_detail_inbound_delivery_date_cds[] = $deliveryDateCd;
            $deliveryDateLbl = date("Y/m/d", strtotime($targetDateCds . "+{$i} day"));
            $outForm->raw_comiket_detail_inbound_delivery_date_lbls[] = $deliveryDateLbl . "（" . $week[date('w', strtotime($deliveryDateCd))] . "）";
        }
        
//        $outForm->raw_parcel_room = $inForm['parcel_room'];
        
//        $outForm->raw_comiket_detail_delivery_date_cd_sel
        
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $outForm->raw_addressee_type_sel = $inForm["addressee_type_sel"];
        $outForm->raw_addressee_type_cds = array_keys($this->address_type_lbls);
        $outForm->raw_addressee_type_lbls = array_values($this->address_type_lbls);
        
        $outForm->raw_hotel_sel = $inForm["hotel_sel"];
        $outForm->raw_hotel_cds = $hotels['ids'];
        $outForm->raw_hotel_lbls = $hotels['names'];
        
        $outForm->raw_sevice_center_sel = $inForm["sevice_center_sel"];
        $outForm->raw_sevice_center_cds = $services['ids'];
        $outForm->raw_sevice_center_lbls = $services['names'];
        
        $outForm->raw_airport_sel = $inForm["airport_sel"];
        $outForm->raw_airport_cds = $airports['ids'];
        $outForm->raw_airport_lbls = $airports['names'];
        
        $outForm->raw_comiket_id = $inForm["comiket_id"];
        $outForm->raw_comiket_detail_delivery_date_min = $inForm["comiket_detail_delivery_date_min"];
        $outForm->raw_comiket_detail_delivery_date_hour = $inForm["comiket_detail_delivery_date_hour"];
        $outForm->raw_comiket_detail_delivery_date = $inForm["comiket_detail_delivery_date"];
        $outForm->delivery_date_store = @$inForm["delivery_date_store"];
        $outForm->comiket_address_to = @$inForm["comiket_address_to"];
        $outForm->comiket_tel_to = @$inForm["comiket_tel_to"];
        
        return array("outForm" => $outForm
                , "dispItemInfo" => $dispItemInfo
//                , "$eventEndFlg" => $eventEndFlg
            );
    }

    /**
     * 住所情報を取得します。
     * @param type $zip
     * @param type $address
     * @return type
     */
    public function _getAddress($zip, $address) {
//        $zip = $inForm->zip1 . $inForm->zip2;
//        $address = $prefectures['names'][array_search($inForm->pref_cd_sel, $prefectures['ids'])] . $inForm->address . $inForm->building;
        return $this->_SocketZipCodeDll->searchByAddress($address, $zip);
    }

    /**
     *
     * @param type $inForm
     */
    public function setYubinDllInfoToInForm(&$inForm, $chakuPrefCd = null) {
Sgmov_Component_Log::debug($inForm);
        $db = Sgmov_Component_DB::getPublic();
        $eventsubData = $this->_EventsubService->fetchEventsubByEventsubId($db, $inForm["eventsub_sel"]);

        $resultEventZipDll = $this->_getAddress(@$eventsubData["zip"], @$eventsubData["address"]);
Sgmov_Component_Log::debug($resultEventZipDll);

        // 搬出 /////////////////////////////////////////////
        $inForm["inbound_hatsu_jis2code"] = @$resultEventZipDll["JIS2Code"];
        $inForm["inbound_hatsu_jis5code"] = @$resultEventZipDll["JIS5Code"];
        $inForm["inbound_hatsu_shop_check_code"] = @$resultEventZipDll["ShopCheckCode"];
        $inForm["inbound_hatsu_shop_check_code_eda"] = @$resultEventZipDll["ShopCheckCodeEda"];
        $inForm["inbound_hatsu_shop_code"] = @$resultEventZipDll["ShopCode"];
        $inForm["inbound_hatsu_shop_local_code"] = @$resultEventZipDll["ShopLocalCode"];

        
        if (@empty($chakuPrefCd)) {
            // 東京預かり所の住所
            $chakuPrefCd = '13'; 
            $chakuZip1 = '100';
            $chakuZip2 = '0005';
            $chakuAddress = '東京都千代田区丸の内1‐9‐1';
            $chakubuilding = '';
        } else {
            $chakuPrefCd = $inForm['comiket_detail_inbound_pref_cd_sel'];
            $chakuZip1 = @$inForm['comiket_detail_inbound_zip1'];
            $chakuZip2 = @$inForm['comiket_detail_inbound_zip2'];
            $chakuAddress = @$inForm['comiket_detail_inbound_address'];
            $chakubuilding = @$inForm['comiket_detail_inbound_building'];
        }

        $resultInboundPrefData = $this->_PrefectureService->fetchPrefecturesById($db, $chakuPrefCd);

        $resultInboundZipDll = $this->_getAddress($chakuZip1 . $chakuZip2, @$resultInboundPrefData["name"] . $chakuAddress . $chakubuilding);

        $inForm["inbound_chaku_jis2code"] = @$resultInboundZipDll["JIS2Code"];
        $inForm["inbound_chaku_jis5code"] = @$resultInboundZipDll["JIS5Code"];
        $inForm["inbound_chaku_shop_check_code"] = @$resultInboundZipDll["ShopCheckCode"];
        $inForm["inbound_chaku_shop_check_code_eda"] = @$resultInboundZipDll["ShopCheckCodeEda"];
        $inForm["inbound_chaku_shop_code"] = @$resultInboundZipDll["ShopCode"];
        $inForm["inbound_chaku_shop_local_code"] = @$resultInboundZipDll["ShopLocalCode"];
    }
    
    protected function calcAmount($inForm) {
        // DB接続
        $db = Sgmov_Component_DB::getPublic();
        
        $code = $inForm['service_hotel_airport_code'];
        $keyBoxIds = array_keys($inForm['comiket_box_inbound_num_ary']);

        $boxId = $keyBoxIds[0];
        $dataBoxFare = $this->_MlkBoxFareService->getMlkBoxFareByCode($db, $code, $boxId);
        $returnData = array(
            'amount' => 0,
            'aountTax' => 0,
        );
        if (!empty($dataBoxFare)) {
            $returnData['aountTax'] = $dataBoxFare['fare'];
            $returnData['amount'] =  ceil((string)($dataBoxFare['fare'] / self::CURRENT_TAX));
            return $returnData;
        }

        $dataBoxFare = $this->_MlkBoxFareService->getMlkBoxFareByCode($db, self::SHIKIBETU_CD_DEFAULT, $boxId);
        if (!empty($dataBoxFare)) {
            $returnData['aountTax'] = $dataBoxFare['fare'];
            $returnData['amount'] =  ceil((string)($dataBoxFare['fare'] / self::CURRENT_TAX));

        } 

        return $returnData;
    }
    /**
     *
     * @param type $inForm
     */
    protected function calcEveryKindData($inForm, $comiketId = "") {
        // DB接続
        $db = Sgmov_Component_DB::getPublic();

        //$this->setYubinDllInfoToInForm($inForm);

        $tableDataInfo = $this->_cmbTableDataFromInForm($inForm, $comiketId);
        $tableTreeData = $tableDataInfo["treeData"];
        $detailAmountTotal = 0;
        $detailAmountTaxTotal = 0;
        foreach($tableTreeData["comiket_detail_list"] as $keyDet => $valDet) {
            $detailAmountTotal += $tableTreeData["comiket_detail_list"][$keyDet]['fare'];
            $detailAmountTaxTotal += $tableTreeData["comiket_detail_list"][$keyDet]['fare_tax'];
        }
	
        $tableTreeData['amount'] = $detailAmountTotal;
        $tableTreeData['amount_tax'] = $detailAmountTaxTotal;


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

//        $comiketCargoDataList = array();
//        foreach($comiketDetailDataList as $key => $val) {
//            if(isset($val["comiket_cargo_list"])) {
//                foreach($val["comiket_cargo_list"] as $key2 => $val2) {
//                    $comiketCargoDataList[] = $val2;
//                }
//            }
//        }
//
//        $comiketCharterDataList = array();
//        foreach($comiketDetailDataList as $key => $val) {
//            if(isset($val["comiket_charter_list"])) {
//                foreach($val["comiket_charter_list"] as $key2 => $val2) {
//                    $comiketCharterDataList[] = $val2;
//                }
//            }
//        }
        return array(
            "treeData" => $tableTreeData,
            "flatData" => array(
                "comiketData" => $comiketData,
                "comiketDetailDataList" => $comiketDetailDataList,
                "comiketBoxDataList" => $comiketBoxDataList,
                //"comiketCargoDataList" => $comiketCargoDataList,
               // "comiketCharterDataList" => $comiketCharterDataList,
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

//        $comiketCargoDataList = $this->_createComiketCargoInsertDataByInForm($inForm, $comiketId);
//
//        foreach($comiketCargoDataList as $key => $val) {
//            foreach($comiketData["comiket_detail_list"] as $key2 => $val2) {
//                if($comiketData["comiket_detail_list"][$key2]["type"] == $val["type"]) {
//                    $comiketData["comiket_detail_list"][$key2]["comiket_cargo_list"][$key] = $val;
//                }
//            }
//        }

//        $comiketCharterDataList = $this->_createComiketCharterInsertDataByInForm($inForm, $comiketId);
//
//        foreach($comiketCharterDataList as $key => $val) {
//            foreach($comiketData["comiket_detail_list"] as $key2 => $val2) {
//                if($comiketData["comiket_detail_list"][$key2]["type"] == $val["type"]) {
//                    $comiketData["comiket_detail_list"][$key2]["comiket_charter_list"][$key] = $val;
//                }
//            }
//        }
        
        return array(
            "treeData" => $comiketData,
            "flatData" => array(
                "comiketData" => $comiketData,
                "comiketDetailDataList" => $comiketDetailDataList,
                "comiketBoxDataList" => $comiketBoxDataList,
               // "comiketCargoDataList" => $comiketCargoDataList,
               // "comiketCharterDataList" => $comiketCharterDataList,
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
        
//        $customerCd = "12089295502";
//        if($eventSel == '1') { // デザインフェスタ
//            $customerCd = "12089295501";
//        } else if($eventSel == '11') { // コミケ
//            $customerCd = "12089295502";
//        }
//        return $customerCd;
    }


    public function _createComiketInsertDataByInForm($inForm, $id) {
        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();
        
        $batch_status = '1';

        $customerCd = $inForm['comiket_customer_cd'];
        $merchantResult = @$inForm['merchant_result'];

        $customerCd = $this->getCustomerCd($inForm['event_sel']);

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


        //@$personalNameSei = mb_substr($inForm['comiket_personal_name_sei'], 0, 8, "UTF-8");
        //@$personalNameMei = mb_substr($inForm['comiket_personal_name_sei'], 8, 15, "UTF-8");

        $zip = @$inForm['comiket_zip1'] . @$inForm['comiket_zip2'];
        $data = array(
            "id" => @$id,
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
            "div" => self::COMIKET_DIV_SETCHI,// 3：設置
            "event_id" => $inForm['event_sel'],
            "eventsub_id" => $inForm['eventsub_sel'],
            "customer_cd" => $customerCd,
            "office_name" => @empty($inForm['office_name']) ? " " : $inForm['office_name'],
            "personal_name_sei" => @empty($inForm['comiket_personal_name_sei']) ? " " : $inForm['comiket_personal_name_sei'],
            "personal_name_mei" => @empty($inForm['comiket_personal_name_mei']) ? " " : $inForm['comiket_personal_name_mei'],
            "zip" => @empty($zip) ? "0" : $zip,
            "pref_id" => @empty($inForm['comiket_pref_cd_sel']) ? "0" : $inForm['comiket_pref_cd_sel'],
            "address" => @empty($inForm['comiket_address']) ? " " : $inForm['comiket_address'],
            "building" => @empty($inForm['comiket_building']) ? " " : $inForm['comiket_building'],
            "tel" => @empty($inForm['comiket_tel']) ? " " : $inForm['comiket_tel'],
            "mail" => $inForm['comiket_mail'],
            "booth_name" => '',
            'building_cd' => NULL, 
            "building_name" => NULL,
            "booth_position" => NULL,
            "booth_num" => @$inForm['comiket_id'], //"booth_num" => NULL,
            "staff_sei" => @$inForm['comiket_staff_sei'],
            "staff_mei" => @$inForm['comiket_staff_mei'],
            "staff_sei_furi" => @$inForm['comiket_staff_sei_furi'],
            "staff_mei_furi" => @$inForm['comiket_staff_mei_furi'],
            "staff_tel" => @$inForm['comiket_staff_tel'],
            "choice" => "3", // 3：往路と復路
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
            "del_flg" => '0',
            "event_key" => strtolower(self::FEATURE_ID),
            "customer_kbn" => '1',
            "bpn_type" => '0', 
            //"mlk_hachaku_type_cd" => @$inForm['addressee_type_sel'],
            //"mlk_hachaku_shikibetu_cd" => @$inForm['service_hotel_airport_code'],
        );
        return $data;
    }

    public function _createComiketDetailInsertDataByInForm($inForm, $id) {
        $returnList = array();

        $db = Sgmov_Component_DB::getPublic();

//        if(empty($inForm['comiket_detail_inbound_collect_date_year_sel'])
//                || empty($inForm['comiket_detail_inbound_collect_date_month_sel'])
//                || empty($inForm['comiket_detail_inbound_collect_date_day_sel'])) {
//            $comiket_detail_inbound_collect_date = "";
//        } else {
//            $comiket_detail_inbound_collect_date =
//                    $inForm['comiket_detail_inbound_collect_date_year_sel']
//                    . '-' . $inForm['comiket_detail_inbound_collect_date_month_sel']
//                    . '-' . $inForm['comiket_detail_inbound_collect_date_day_sel'];
//        }
//
//        if(empty($inForm['comiket_detail_inbound_delivery_date_year_sel'])
//                || empty($inForm['comiket_detail_inbound_delivery_date_month_sel'])
//                || empty($inForm['comiket_detail_inbound_delivery_date_day_sel'])) {
//            $comiket_detail_inbound_delivery_date = "";
//        } else {
//            $comiket_detail_inbound_delivery_date =
//                    $inForm['comiket_detail_inbound_delivery_date_year_sel']
//                    . '-' . $inForm['comiket_detail_inbound_delivery_date_month_sel']
//                    . '-' . $inForm['comiket_detail_inbound_delivery_date_day_sel'];
//        }


//        $note = $inForm['comiket_detail_inbound_note1'];
//Sgmov_Component_Log::debug($inForm);
//        $collectStTime = null;
//        $collectEdTime = null;
//
//        $deliveryStTime = null;
//        $deliveryEdTime = null;
         // 個人

//        $deliveryDate = null;
//        if (!empty($comiket_detail_inbound_delivery_date)) {
//            $deliveryDate = $comiket_detail_inbound_delivery_date;
//        }
//        $deliveryTime = $inForm['comiket_detail_inbound_delivery_time_sel'];
//        if(!empty($inForm['comiket_detail_inbound_delivery_time_sel'])) {
//            $arrTimezone = explode(',', $inForm['comiket_detail_inbound_delivery_time_sel']);
//            $inboundDeliveryTimeList = explode('～', $arrTimezone[1]);
//
//            if(empty($inboundDeliveryTimeList)) {
//                $deliveryStTime = null;
//                $deliveryEdTime = null;
//            } else if(count($inboundDeliveryTimeList) == 2) {
//                $deliveryStTime = $inboundDeliveryTimeList[0];
//                $deliveryEdTime = $inboundDeliveryTimeList[1];
//            } else if(count($inboundDeliveryTimeList) == 1) {
//                if($arrTimezone[0] == "00" || $arrTimezone[0] == "11") {
//                    $deliveryStTime = null;
//                } else {
//                    $deliveryStTime = $inboundDeliveryTimeList[0];
//                }
//                $deliveryEdTime = null;
//            }
//        }
            

//        if(!empty($inForm['comiket_detail_inbound_collect_time_sel'])) {
//            $inboundCollectTimeList = explode('-', $inForm['comiket_detail_inbound_collect_time_sel']);
//
//            if(empty($inboundCollectTimeList)) {
//                $collectStTime = null;
//                $collectEdTime = null;
//            } else if(count($inboundCollectTimeList) == 2) {
//                $collectStTime = $inboundCollectTimeList[0];
//                $collectEdTime = $inboundCollectTimeList[1];
//            } else if(count($inboundCollectTimeList) == 1) {
//                if($inboundCollectTimeList[0] == "00") {
//                    $collectStTime = null;
//                } else {
//                    $collectStTime = $inboundCollectTimeList[0];
//                }
//                $collectEdTime = null;
//            }
//        }
//
//        $timezoneCd = '';
//        $timezoneNm = '';
//        if (!empty($inForm['comiket_detail_inbound_delivery_time_sel'])) {
//            $arrTimezomeVal = explode(',', $inForm['comiket_detail_inbound_delivery_time_sel']);
//            $timezoneCd = $arrTimezomeVal[0];
//            $timezoneNm = $arrTimezomeVal[1];
//        }
        //////////////////

        $hachakuShikibetuCdFrom = substr($inForm['comiket_id'], 0, 8);
        $dataHachakutenFrom = $this->_HachakutenService->fetchValidHachakutenByCode($db, $hachakuShikibetuCdFrom);
        $hachakuTypeFrom = $dataHachakutenFrom['type'];
        
        $resultAddressFrom = $this->_getAddress($inForm['comiket_zip1'].$inForm['comiket_zip2'], $inForm['comiket_address']);
        $resultAddressCenter = $this->_getAddress('', self::ADDRESS_CENTER);
        
        $dataHachakutenTo = $this->_HachakutenService->fetchValidHachakutenByCode($db, $inForm['service_hotel_airport_code']);
        $resultAddressTo = $this->_getAddress(@$dataHachakutenTo['zip'], @$dataHachakutenTo['address']);
        
        $collectDate = str_replace('/', '-', $inForm['delivery_date_store']);
        $deliveryDate = '';
        $deliveryStTime = '';
        if ($inForm['addressee_type_sel'] == self::DELIVERY_TYPE_AIRPORT) {
            $deliveryDateHour = sprintf("%02d", $inForm['comiket_detail_delivery_date_hour']);
            $deliveryDateMin = sprintf("%02d", $inForm['comiket_detail_delivery_date_min']);
            $deliveryDate = $inForm['comiket_detail_delivery_date'];
            $deliveryStTime = $deliveryDateHour.':'.$deliveryDateMin.':00';
            $binNm = $inForm['comiket_detail_inbound_note'];
        } else {
            $deliveryDate = $collectDate;
        }
        $arrAmount = $this->calcAmount($inForm);
        $fareTax = $arrAmount['aountTax'];
        $fare = $arrAmount['amount'];
        
        $data1 = array(
                "comiket_id" => $id,
                "type" => "1",
                "cd" => @$inForm['comiket_id'],
                "name" => $inForm['comiket_staff_sei']."　".$inForm['comiket_staff_mei'],
                        
                "hatsu_jis5code" => @$resultAddressFrom['JIS5Code'],
                "hatsu_shop_check_code" => @$resultAddressFrom['ShopCheckCode'],
                "hatsu_shop_check_code_eda" => @$resultAddressFrom['ShopCheckCodeEda'],
                "hatsu_shop_code" => @$resultAddressFrom['ShopCode'],
                "hatsu_shop_local_code" => @$resultAddressFrom['ShopLocalCode'],
                        
                "chaku_jis5code" => @$resultAddressCenter['JIS5Code'],
                "chaku_shop_check_code" => @$resultAddressCenter['ShopCheckCode'],
                "chaku_shop_check_code_eda" => @$resultAddressCenter['ShopCheckCodeEda'],
                "chaku_shop_code" => @$resultAddressCenter['ShopCode'],
                "chaku_shop_local_code" => @$resultAddressCenter['ShopLocalCode'],

                "zip" => $inForm['comiket_zip1'].$inForm['comiket_zip2'],
                "pref_id" => @$inForm['comiket_pref_cd_sel'], // 東京都
                "address" => @$inForm['comiket_address'],
                "building" => @$inForm['comiket_building'], 
                "tel" => @$inForm['comiket_tel'], 

                "collect_date" => "{$collectDate}" , // 受渡日(チェックアウト日) ※ ホテルに集荷する日
                "collect_st_time" => NULL,
                "collect_ed_time" => NULL,

                "delivery_date" => "{$deliveryDate}",
                "delivery_st_time" => NULL,
                "delivery_ed_time" => NULL,

                "service" => '4', // 4:ミルクラン
                "note" => $inForm['comiket_detail_inbound_note1'],
                "fare" => $fare, // ?
                "fare_tax" => $fareTax, // ?
                "cost" => "0", // ?
                "cost_tax" => "0", // ?
                "delivery_timezone_cd" => null,
                "delivery_timezone_name" => null,
                "binshu_kbn" => "0", 
                "toiawase_no" => @$inForm['comiket_toiawase_no'],
                "toiawase_no_niugoki" => @$inForm['comiket_toiawase_no_niugoki'],
                "mlk_hachaku_shikibetu_cd" => $hachakuShikibetuCdFrom,
                "mlk_hachaku_type_cd" => $hachakuTypeFrom,
                "mlk_bin_nm" => null,
            );
            $buildingName = '';
            if (@$resultAddressTo['KenName'] && @$resultAddressTo['CityName'] && @$resultAddressTo['TownName']) {
                $shortAddressTo = $resultAddressTo['KenName'].$resultAddressTo['CityName'].$resultAddressTo['TownName'];
                $buildingName = mb_substr ($dataHachakutenTo['address'], mb_strlen($shortAddressTo));
            }
            
            $data2 = array(
                "comiket_id" => $id,
                "type" => "2",
                "cd" => @$inForm['comiket_id'],
                "name" => $inForm['comiket_staff_sei']."　".$inForm['comiket_staff_mei'],
                        
                "hatsu_jis5code" => @$resultAddressCenter['JIS5Code'],
                "hatsu_shop_check_code" => @$resultAddressCenter['ShopCheckCode'],
                "hatsu_shop_check_code_eda" => @$resultAddressCenter['ShopCheckCodeEda'],
                "hatsu_shop_code" => @$resultAddressCenter['ShopCode'],
                "hatsu_shop_local_code" => @$resultAddressCenter['ShopLocalCode'],
                        
                "chaku_jis5code" => @$resultAddressTo['JIS5Code'],
                "chaku_shop_check_code" => @$resultAddressTo['ShopCheckCode'],
                "chaku_shop_check_code_eda" => @$resultAddressTo['ShopCheckCodeEda'],
                "chaku_shop_code" => @$resultAddressTo['ShopCode'],
                "chaku_shop_local_code" => @$resultAddressTo['ShopLocalCode'],

                
                
                "zip" => @$dataHachakutenTo['zip'], //zip
                "pref_id" => @$resultAddressTo['JIS2Code'], // 東京都 JIS2Code
                "address" => @$resultAddressTo['CityName'].@$resultAddressTo['TownName'],
                "building" => $buildingName,
                "tel" => @$dataHachakutenTo['tel'],

                "collect_date" => "{$collectDate}" , // 受渡日(チェックアウト日) ※ ホテルに集荷する日
                "collect_st_time" => NULL,
                "collect_ed_time" => NULL,

                "delivery_date" => "{$deliveryDate}",
                "delivery_st_time" => !empty($deliveryStTime) ? $deliveryStTime : NULL,
                "delivery_ed_time" => NULL,

                "service" => '4', // 4:ミルクラン
                "note" => $inForm['comiket_detail_inbound_note1'],
                "fare" => "0", // ?
                "fare_tax" => "0", // ?
                "cost" => "0", // ?
                "cost_tax" => "0", // ?
                "delivery_timezone_cd" => null,
                "delivery_timezone_name" => null,
                "binshu_kbn" => "0", 
                "toiawase_no" => @$inForm['comiket_toiawase_no2'],
                "toiawase_no_niugoki" => @$inForm['comiket_toiawase_no_niugoki2'],
                "mlk_hachaku_shikibetu_cd" => @$inForm['service_hotel_airport_code'],
                "mlk_hachaku_type_cd" => @$inForm['addressee_type_sel'],  
                "mlk_bin_nm" => $binNm,  
            );
            $returnList[] = $data1;
            $returnList[] = $data2;
        return $returnList;
        
    }

    public function _createComiketBoxInsertDataByInForm($inForm, $id) {

        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();
        $returnList = array();

        $boxIds = array_keys($inForm['comiket_box_inbound_num_ary']);
        //eventsub_sel
        $boxId = $this->_BoxService->findBoxIdByEventSubAndCd($db, $inForm['eventsub_sel'], $boxIds[0]);
        $arrAmount = $this->calcAmount($inForm);
        $fareTax = $arrAmount['aountTax'];
        $fareAmountTax = $arrAmount['aountTax'];
        
        $data1 = array(
            "comiket_id" => $id,
            "type" => "1", 
            "box_id" => $boxId['id'],//$boxIds[0],
            "num" => "1",
            "fare_price" => "0", 
            "fare_amount" => "0", 
            "fare_price_tax" => $fareTax, 
            "fare_amount_tax" => $fareAmountTax, 
            "cost_price" => "0", 
            "cost_amount" => "0", 
            "cost_price_tax" => "0", 
            "cost_amount_tax" => "0", 
        );


        $data2 = array(
            "comiket_id" => $id,
            "type" => "2", 
            "box_id" => $boxId['id'],
            "num" => "1",
            "fare_price" => "0", 
            "fare_amount" => "0", 
            "fare_price_tax" => "0", 
            "fare_amount_tax" => "0", 
            "cost_price" => "0", 
            "cost_amount" => "0", 
            "cost_price_tax" => "0", 
            "cost_amount_tax" => "0", 
        );

        $returnList[] = $data1;
        $returnList[] = $data2;
        return $returnList;
    }

    public function _createComiketCargoInsertDataByInForm($inForm, $id) {

        $db = Sgmov_Component_DB::getPublic();

        $returnList = array();


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
                Sgmov_Component_Redirect::redirectPublicSsl("/mlk/error_term/{$inForm['comiket_detail_type_sel']}");
                exit;
            }
        } else if($inForm['comiket_detail_type_sel'] == "2") { // 復路
            if(!$this->isCurrentDateWithInTerm("arrival", $inForm['eventsub_sel'])) {
                Sgmov_Component_Redirect::redirectPublicSsl("/mlk/error_term/{$inForm['comiket_detail_type_sel']}");
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
                Sgmov_Component_Redirect::redirectPublicSsl("/mlk/error_term/3"); // 往復エラー画面
                exit;
            } else if($isDepartureErr) {
                Sgmov_Component_Redirect::redirectPublicSsl("/mlk/error_term/1"); // 往路エラー画面
                exit;
            } else if($isArrivalErr) {
                Sgmov_Component_Redirect::redirectPublicSsl("/mlk/error_term/2"); // 復路エラー画面
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
//                    8 => 4,
//                    9 => 3,
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
     * 完了メール送信
     * @param $comiket 設定用配列
     * @param $sendTo2 宛先
     * @param sendCc   転送先
     * @param $type    往復区分
     * @return bool true:成功
     */
    public function sendCompleteMail($comiket, $sendTo2 = '', $sendCc = '', $type = '', $tmplateType = '') {

        try {
            Sgmov_Component_Log::debug("sendCompleteMail#########################");
            //添付ファイルの有無を判別
            $isAttachment = false; //2023/12/05 QRコードの表示は不要
            if ($tmplateType == 'cancel' || $tmplateType == "sgmv_cancel") {
                $isAttachment = false;
            }
            //宛先
//            $sendTo = $comiket['mail'];
            if(empty($sendTo2)) {
                $sendTo = $comiket['mail'];
            } else {
                $sendTo = $sendTo2;
            }
            //テンプレートデータ
            $data = array();
            //メールテンプレート(申込者用)
            $mailTemplate = array();

            //メールテンプレート(SGMV営業所用)
            $mailTemplateSgmv = array();

            // DBへ接続
            $db = Sgmov_Component_DB::getPublic();
            //-------------------------------------------------
            //テンプレートデータ作成
            //-------------------------------------------------
            $week = ['日', '月', '火', '水', '木', '金', '土'];
            $collectDate = $comiket['comiket_detail_list'][0]['collect_date'];
            $collectDateDt = new DateTime($collectDate);
            $extWeek = date('w',strtotime($collectDate));
            $collectDateName = $collectDateDt->format('Y年m月d日')."(".$week[$extWeek].")";
            $data['delivery_date'] = "";
            if ($comiket['comiket_detail_list'][1]['mlk_hachaku_type_cd'] == self::DELIVERY_TYPE_AIRPORT) {
                $deliveryDateDt = new DateTime($comiket['comiket_detail_list'][1]['delivery_date']);
                $extWeek = date('w',strtotime($comiket['comiket_detail_list'][1]['delivery_date']));
                $objDeliveryStTime = new DateTime($comiket['comiket_detail_list'][1]['delivery_st_time']);
                $deliveryStTime = $objDeliveryStTime->format("H:i");
                $deliveryDateName = $deliveryDateDt->format('Y年m月d日')."(".$week[$extWeek].") ".$deliveryStTime;
                //$data['delivery_date'] = "【搭乗日時/Flight date and time】" . $deliveryDateName;
                $flightName = $comiket['comiket_detail_list'][1]['mlk_bin_nm']; //画面の便名
                $data['delivery_date'] =  "\n" ."【搭乗日時/Flight date and time】" . $deliveryDateName . "\n" . "【便名/Flight number】".$flightName;
            }
            if ($tmplateType == 'cancel') {
                $mailTemplate[] = '/'.$this->_DirDiv.'/mlk_cancel_complete.txt';
            } elseif ($tmplateType == "sgmv_cancel") {
                $mailTemplate[] = '/'.$this->_DirDiv.'/mlk_cancel_complete_admin.txt';
            } else {//完了
                //個人用メールテンプレート
                $mailTemplate[] = '/'.$this->_DirDiv.'/mlk_receive_complete.txt';

                //SGMV業者
                $mailTemplateSgmv[] = '/'.$this->_DirDiv. '/mlk_receive_complete_admin.txt';
            }
            $data['comiket_id'] = sprintf('%010d', $comiket['id']);
           
            $data['tag_id'] = $comiket['comiket_detail_list'][0]['cd'];
            $data['personal_name_seimei'] = $comiket['staff_sei'] . " " . $comiket['staff_mei'];
            $data['mail'] = $comiket['mail'];
            $data['collect_date'] = $collectDateName ;
            // 【集荷先名/Collection name】
            $comiketDetailCd = (!empty($comiket['comiket_detail_list'][0]['cd']) && mb_strlen($comiket['comiket_detail_list'][0]['cd']) > 8) ? substr($comiket['comiket_detail_list'][0]['cd'], 0, 8) : '';
            $hachakutenMst = $this->_HachakutenService->fetchHachakutenByCode($db, $comiketDetailCd);
            $collectionName = (!empty($hachakutenMst)) ? $hachakutenMst['name_jp'] . '(' . $hachakutenMst['name_en'] . ')' : '';
            $data['name'] =  $collectionName;
            
            // 【お届け先名/Addressee name】
            $addressName = '';
            // If substr($comiket['cd'], 0, 8) = $comiket['mlk_hachaku_shikibetu_cd'], so no need to query again
            if ($comiketDetailCd == $comiket['comiket_detail_list'][1]['mlk_hachaku_shikibetu_cd']) {
                $addressName = $collectionName;
            } else {
                $hachakutenMst = $this->_HachakutenService->fetchHachakutenByCode($db, $comiket['comiket_detail_list'][1]['mlk_hachaku_shikibetu_cd']);
                $addressName = (!empty($hachakutenMst)) ? $hachakutenMst['name_jp'] . '(' . $hachakutenMst['name_en'] . ')' : '';
            }
            $data['address'] = $addressName;
            
            $data['service'] = $this->comiket_detail_service_lbls[1];
            //box_id
           // comiket_box_list
            $boxId = $comiket['comiket_detail_list'][0]['comiket_box_list'][0]['box_id'];
            $boxInfo = $this->_BoxService->fetchBoxById($db, $boxId);
            $data['num_area'] = "{$boxInfo['name']}/Size {$boxInfo['size_display']}［1 個］";
            $data['note'] = $comiket['comiket_detail_list'][0]['note'];
            $data['payment_method'] = 'クレジットカード/Credit Card';
            
            $data['amount'] = '\\' . number_format($comiket['amount']);
            $data['amount_tax'] = '\\' . number_format($comiket['amount_tax']);
            
            $urlPublicSsl = Sgmov_Component_Config::getUrlPublicSsl();
            $comiketIdCheckD = self::getChkD2(sprintf("%010d", $comiket['id']));
            $comiketId = sprintf('%010d', $comiket["id"]) . $comiketIdCheckD;
            $data['cancel_url'] = $urlPublicSsl . "/mlk/cancel?id={$comiketId}";
            //-------------------------------------------------
Sgmov_Component_Log::debug($mailTemplate);
Sgmov_Component_Log::debug($mailTemplateSgmv);
            //メール送信実行
            $objMail = new Sgmov_Service_CenterMail();
            if (!$isAttachment) {
                Sgmov_Component_Log::debug("44444444444444444444444");
                // 申込者へメール
                $objMail->_sendThankYouMail($mailTemplate, $sendTo, $data);

                // 営業所へメール(CCとして設定する用にもっていた変数をToの方へ)
                if ($sendCc !== null && $sendCc !== '') {
                    Sgmov_Component_Log::debug("555555555555555555555");
                    $objMail->_sendThankYouMail($mailTemplateSgmv, $sendCc, $data);
                }
            } else {
                // qrコードファイル出力
                $qr = new Image_QRCode();

                $image = $qr->makeCode(htmlspecialchars($comiket["id"]),
                                       array('output_type' => 'return', "module_size"=>10,));
                imagepng($image, dirname(__FILE__) . "/tmp/qr{$comiket["id"]}.png");
                imagedestroy($image);
                $attachment = dirname(__FILE__) . '/tmp/qr' . $comiket['id'] . '.png';
                $attach_mime_type = 'image/png';
                // 申込者へメール
                $objMail->_sendThankYouMailAttached($mailTemplate, $sendTo, $data, $attachment, $attach_mime_type);
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
    
    /**
     * 
     * @param type $path
     */
    protected function redirectErrorPage($title, $message, $arg1 = "") {
        
        $title = urlencode($title);
        $message = urlencode($message);
        $arg1 = urlencode($arg1);
        Sgmov_Component_Redirect::redirectPublicSsl("/mlk/error?t={$title}&m={$message}&a1={$arg1}");
    }

    /**
     * setBoxName
     * @param type $dataList
     * @return Array $returnList
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
     * 
     * @param type $comiketId
     */
    protected function checkReqDate($comiketId, $titleParts = "", $MessageParts = "") {
        if (@!empty($titleParts) && @empty($MessageParts)) {
            $MessageParts = $titleParts;
        }

        $db = Sgmov_Component_DB::getPublic();
        $comiketDetailList = $this->_ComiketDetailService->fetchComiketDetailByComiketId($db, $comiketId);
        $comiketDetailInfo = $comiketDetailList[0];
        $dataHachakuten = $this->_HachakutenService->fetchValidHachakutenByCode($db, $comiketDetailList[0]['mlk_hachaku_shikibetu_cd']);
        $deliveryDate = '';
        $currentTime = date('His');
        if (isset($dataHachakuten['input_end_time']) && !empty($dataHachakuten['input_end_time'])) {
            if ($currentTime >= $dataHachakuten['input_end_time']."00") {
                $deliveryDate = date('Y-m-d', strtotime("+1 day", strtotime(date('Y-m-d'))));
            } else {
                $deliveryDate = date('Y-m-d');
            }
        }
        //collect_date
        
        
        if ($deliveryDate !== $comiketDetailInfo['collect_date']) {
            $title = "手荷物当日配送サービスのキャンセルの受付終了しました。";
            $message = "既にお預かり/お届け日を過ぎているため、キャンセルのお申し込みができませんでした。";
            Sgmov_Component_Redirect::redirectPublicSsl("/". $this->_DirDiv ."/error?t={$title}&m={$message}");
        }
    }
    
    /**
     * 集荷・配達完了のメール送信
     * @param $sendTo2 宛先
     * @param $comiketData 設定用配列
     * @param $type    1:集荷、2:配達
     * @return bool true:成功
     */
    public function sendMlkGyomuMail($sendTo, $comiketData, $type = '') {

        try {
            if ($type == '1') {
                $mailTemplate = '/mlk/mlk_gyomu_collect_complete.txt';
            } else {
                $mailTemplate = '/mlk/mlk_gyomu_delivery_complete.txt';
            }
            //メール送信実行
            $objMail = new Sgmov_Service_CenterMail();
            Sgmov_Component_Log::debug($mailTemplate);
            // 申込者へメール
            $objMail->_sendMlkGyomuMail($mailTemplate, $sendTo, $comiketData);
            unset($objMail);
        } catch (Exception $e) {
            Sgmov_Component_Log::err('sendMlkGyomuMail：メール送信に失敗しました。');
            Sgmov_Component_Log::err($e);
            return false;
        }
        return true;
    }

    /**
     * 集荷・配達完了のメール送信（管理者向け）
     * @param $db データベース
     * @param $comiketData 設定用配列
     * @param $type    集荷又は配達
     * @return bool true:成功
     */
    public function sendMlkGyomuAdminMail($db, $comiketData, $type = '') {

        try {
            if ($type == '1') {
                $mailTemplate = '/mlk/mlk_gyomu_collect_complete_admin.txt';
            } else {
                $mailTemplate = '/mlk/mlk_gyomu_delivery_complete_admin.txt';
            }
            //メール送信実行
            $objMail = new Sgmov_Service_CenterMail();
            Sgmov_Component_Log::debug($mailTemplate);
            // 申込者へメール
            $objMail->_sendMlkGyomuAdminMail($db, $comiketData['pref_id'], $comiketData, $mailTemplate);
            unset($objMail);
        } catch (Exception $e) {
            Sgmov_Component_Log::err('sendMlkGyomuAdminMail：メール送信に失敗しました。');
            Sgmov_Component_Log::err($e);
            return false;
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
    protected function execWebApiCancelComiket($wsProtocol, $wsHost, $wsPath, $wsPort, $param, $paramOrg , $dispErrTitle = "", $dispErrMsg = "") {
        
        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();
        
        $comiketInfo = $this->_ComiketService->fetchComiketById($db, $param);
        
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // ▼ 業務連携用リクエストデータ作成
        ///////////////////////////////////////////////////////////////////////////////////////////////////

        $sendFileName = strtoupper($this->_DirDiv). '_' . date ( 'YmdHis' ) . '.csv';
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
                    Sgmov_Component_Log::debug ( "エラー内容ログ出力\n" . $e);
            }
            $clasName = get_class($this);
            $message = "ミルクラン: 業務連携に失敗しました。\n[mlk] {$clasName} : paramOrg = {$paramOrg} / param = {$param}\n\nDB comiket.del_flg（論理削除フラグ）は更新しています。\n\n";
            $message .= "Exceptionメッセージ: " . $e->getMessage() . "\n\n";
            $message .= "業務サーバ接続情報: _wsProtocol: {$wsProtocol} / _wsHost: {$wsHost} / _wsPort: {$wsPort} / _wsPath: {$wsPath}";
            // システム管理者メールアドレスを取得する。
            $mailTo = Sgmov_Component_Config::getLogMailTo ();
            $mailData['message'] = $message;
            $mailTemplate[] = '/'.$this->_DirDiv.'/mlk_cancel_error.txt';
            $mailData['comiket_id'] = sprintf('%010d', $comiketInfo['id']);
            $mailData['mail'] = $comiketInfo['mail'];
            $mailData['personal_name_seimei'] = $comiketInfo['staff_sei'] . " " . $comiketInfo['staff_mei'];
            // メールを送信する。
            $objMail = new Sgmov_Service_CenterMail();
            $objMail->_sendThankYouMail($mailTemplate, $mailTo, $mailData);
            // 業務連携失敗
            return false;
        }
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        
        // 業務連携成功
        return true;
    }
    
}
