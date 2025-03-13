<?php

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useAllComponents();
Sgmov_Lib::useServices(array('CruiseRepeater', 'Prefecture', 'Yubin', 'SocketZipCodeDll'
    , 'Event', 'Box', 'Building', 'Eventsub'
    , 'AppCommon', 'HttpsZipCodeDll', 'BoxFare', 'Comiket', 'EventsubCmb', 'Time', 'CenterMail', 'ComiketDetail', 'EventDate'));
Sgmov_Lib::useView('Public');
/**#@-*/

/**
 * イベントサービスのお申し込みフォームの共通処理を管理する抽象クラスです。
 * @package    View
 * @subpackage AZK
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Azk_Common extends Sgmov_View_Public {
    /**
     * 機能ID
     */
    const FEATURE_ID = 'AZK';

    /**
     * AZK001の画面ID
     */
    const GAMEN_ID_AZK001 = 'AZK001';

    /**
     * AZK002の画面ID
     */
    const GAMEN_ID_AZK002 = 'AZK002';

    /**
     * AZK003の画面ID
     */
    const GAMEN_ID_AZK003 = 'AZK003';

    /**
     * 個人
     */
    const COMIKET_DEV_INDIVIDUA = "1";

    /**
     * 法人
     */
    const COMIKET_DEV_BUSINESS = "2";

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
     * サービス選択値
     * @var array
     */
    public $comiket_detail_service_lbls = array(
        5 => '手荷物預かり',
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
     * 取出コード回数
     * @var array
     */
    public $comiket_detail_azukari_kaisu_type_lbls = array(
        1 => '１回のみ',
        2 => '複数回'
    );

    /**
     * 荷物形状
     * @var array
     */
    public $comiket_nimotsu_type_lbls = array(
        1 => 'スーツケース',
        2 => 'バッグ類',
        3 => 'その他'
    );

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

    /**
     * イベント期間
     * @var type
     */
    private $_EventDateService;


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
        $this->_BuildingService       = new Sgmov_Service_Building();
        $this->_HttpsZipCodeDll       = new Sgmov_Service_HttpsZipCodeDll();
        $this->_BoxFareService        = new  Sgmov_Service_BoxFare();
        $this->_EventsubCmbService    = new Sgmov_Service_EventsubCmb();
        $this->_TimeService           = new Sgmov_Service_Time();
        $this->_SocketZipCodeDll                 = new Sgmov_Service_SocketZipCodeDll();

        $this->_EventDateService      = new Sgmov_Service_EventDate();

        // $this->_AzukariBoxService = new Sgmov_Service_AzukariBox();
        // $this->_ComiketAzukari = new Sgmov_Service_ComiketAzukari();
        // $this->_AzukariSetting = new Sgmov_Service_AzukariSetting();
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
        foreach($eventList as $val) {
            if($val["id"] == $id) {
                return $val;
            }
        }
        return array();
    }

    /**
     *
     * @param type $inForm
     * @param Sgmov_Form_Azk001Out $outForm
     * @return type
     */
    protected function createOutFormByInForm($inForm, $outForm = array()) {
        $dispItemInfo = array();

        $inForm = (array)$inForm;

        $db = Sgmov_Component_DB::getPublic();

        $eventAll = $this->_EventService->fetchEventListWithinTerm2($db);

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

        $dispItemInfo["event_alllist"] = $eventAll2;

        // 出展イベント
        $outForm->raw_event_cds  = $eventIds;
        $outForm->raw_event_lbls = $eventNames2;
        $outForm->raw_event_cd_sel = $inForm["event_sel"];

        $outForm->raw_shikibetsushi = $inForm["shikibetsushi"];

        // 出展イベントサブ
        $eventsubAry2 = $this->_EventsubService->fetchEventsubListWithinTermByEventId($db, $inForm["event_sel"]);

        // 入力モード制御
        $outForm->raw_input_mode = $inForm['input_mode'];
        if(!empty($inForm['input_mode']) && !empty($eventsubAry2)) {
            // イベントIDは上記にて設定済み
            $eventsubList = $eventsubAry2["list"];
            $inForm['eventsub_sel'] = $eventsubList[0]['id'];
        }

        // イベントサブ
        $outForm->raw_comiket_id  = @$inForm['comiket_id'];

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
        foreach($buildingListByEventsubId as $key => $val) {
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

        // 取出回数
        $dispItemInfo["comiket_detail_azukari_kaisu_type_lbls"] = $this->comiket_detail_azukari_kaisu_type_lbls;


/////////////////////////////////////////////////////////////////////////////////////////////////////////
// 手荷物預かり
/////////////////////////////////////////////////////////////////////////////////////////////////////////

        // 取出回数
        $outForm->raw_comiket_detail_azukari_kaisu_type_sel  = @empty($inForm["comiket_detail_azukari_kaisu_type_sel"]) ? "1" : "2";

        //サービス選択
        $outForm->raw_comiket_detail_service_sel = $inForm["comiket_detail_service_sel"];

        // 手荷物:5
        $outForm->raw_comiket_detail_type_sel =  $inForm["comiket_detail_type_sel"];

        // 氏名
        $outForm->raw_comiket_detail_name  = $inForm["comiket_detail_name"];

        // 集荷先郵便番号1
        $outForm->raw_comiket_detail_zip1 = $inForm["comiket_detail_zip1"];

        // 集荷先郵便番号2
        $outForm->raw_comiket_detail_zip2 = $inForm["comiket_detail_zip2"];

        // 集荷先市区町村
        $outForm->raw_comiket_detail_address =  $inForm["comiket_detail_address"];

        // 集荷先番地・建物名
        $outForm->raw_comiket_detail_building =  $inForm["comiket_detail_building"];

        // 集荷先TEL
        $outForm->raw_comiket_detail_tel =  $inForm["comiket_detail_tel"];

        // 集荷先都道府県
        $outForm->raw_comiket_detail_pref_cds  = $prefectureAry["ids"];
        $outForm->raw_comiket_detail_pref_lbls = $prefectureAry["names"];
        $outForm->raw_comiket_detail_pref_cd_sel = $inForm["comiket_detail_pref_cd_sel"];

        $now = new DateTime();
        $years  = $this->_appCommon->getYears($now->format('Y'), 0, false);

        $eventTerm = $this->_EventDateService->fetchEventTerm($db, $inForm["eventsub_sel"]);

        $dates = array();
        foreach ($eventTerm as $key => $value) {
            $eventDate = new DateTime($value['from_to']);

            if ($now > $eventDate) {
                continue;
            }

            $dates[] = $value["from_to"];
        }

        $dates = array_unique($dates);
        $years = array();
        $months = array();
        $days = array();
        foreach ($dates as $key => $value) {
            $split = explode("-", $value);
            $years[] = $split[0];
            $months[] = $split[1];
            $days[] = $split[2];
        }

        $years = array_values(array_unique($years));
        $months = array_values(array_unique($months));
        $days = array_values(array_unique($days));



       // $dispItemInfo["eventsub_azukari"]["collect_fr_dt"] = "2021-06-22";
       // $dispItemInfo["eventsub_azukari"]["collect_to_dt"] = "2021-07-22";
       // $eventTerm = $this->_EventDateService->getAzukariTerm($db, $inForm["eventsub_sel"]);
       // $termList = array();
       // foreach ($eventTerm as $key => $value) {
       //      $groupCd = $value["group_cd"];
       //      $temp[$groupCd][] = $value["from_to"]; 
       // }
       //  $dateList = array();
       //  foreach ($temp as $key => $value) {
       //      foreach ($value as $k2 => $v2) {
       //          $dateList[$key][] = $v2;
       //          if (count($value) < 2){
       //              $dateList[$key][] = $v2;
       //          }
       //      }
       //  }
       //  $dispItemInfo["eventsub_azukari_term"] = $dateList;
        // $months = array('08');
        // $days   = array('21','22','28');
        // $currentDay = $date->format('d');
        // foreach ($days as $key => $value) {
        //     if ($currentDay > $value) {
        //         unset($days[$key]);
        //     }
        // }

        // $days = array_values($days);


        // 手荷物お預かり
        $outForm->raw_comiket_detail_collect_date_year_sel = $inForm["comiket_detail_collect_date_year_sel"];
        $outForm->raw_comiket_detail_collect_date_year_cds = $years;
        $outForm->raw_comiket_detail_collect_date_year_lbls = $years;
        $outForm->raw_comiket_detail_collect_date_month_sel = $inForm["comiket_detail_collect_date_month_sel"];
        $outForm->raw_comiket_detail_collect_date_month_cds = $months;
        $outForm->raw_comiket_detail_collect_date_month_lbls = $months;
        $outForm->raw_comiket_detail_collect_date_day_sel = $inForm["comiket_detail_collect_date_day_sel"];
        $outForm->raw_comiket_detail_collect_date_day_cds = $days;
        $outForm->raw_comiket_detail_collect_date_day_lbls = $days;


        // 手荷物数量
        $outForm->raw_comiket_box_num_ary = $inForm["comiket_box_num_ary"];

        // 備考-1行目
        $outForm->raw_comiket_detail_note1 = $inForm["comiket_detail_note1"];

        // 備考-2行目
        $outForm->raw_comiket_detail_note2 = $inForm["comiket_detail_note2"];

        // 備考-3行目
        $outForm->raw_comiket_detail_note3 = $inForm["comiket_detail_note3"];

        // 備考-4行目
        $outForm->raw_comiket_detail_note4 = $inForm["comiket_detail_note4"];

        // サービス選択-手荷物預かり
        $dispItemInfo["comiket_detail_service_lbls"] = $this->comiket_detail_service_lbls;

        // お預かり箱
        
        // $dispItemInfo["comiket_box_lbls"] = $this->_AzukariBoxService->fetchBox($db, $outForm->raw_eventsub_cd_sel, $outForm->raw_comiket_detail_azukari_kaisu_type_sel); 
        // $dispItemInfo["comiket_box_lbls"] =  $this->_BoxService->fetchBox2($db, $outForm->raw_eventsub_cd_sel, "1", "4");
        $dispItemInfo["comiket_box_lbls"] = $this->_BoxService->fetchBox2($db, $outForm->raw_eventsub_cd_sel, "1", "4");

        // 荷物形状
        $dispItemInfo["comiket_nimotsu_type_lbls"] = $this->comiket_nimotsu_type_lbls;


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

        return array("outForm" => $outForm
                , "dispItemInfo" => $dispItemInfo
            );
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
    public function _getByAddressWithZipCode($zip, $address){
        try{

            $receive = array();

            $receive = $this->_SocketZipCodeDll->searchByAddressWithZipCode($zip, $address);

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

        //$resultOutboundPrefData = $this->_PrefectureService->fetchPrefecturesById($db, $inForm['comiket_detail_pref_cd_sel']);

        // $resultOutboundHatsuZipDll = $this->_getAddress(@$inForm['comiket_detail_zip1'] . @$inForm['comiket_detail_zip2']
        //         , @$resultOutboundPrefData["name"] . @$inForm['comiket_detail_address'] . @$inForm['comiket_detail_building']);

        // $inForm["hatsu_jis2code"] = @$resultOutboundHatsuZipDll["JIS2Code"];
        // $inForm["hatsu_jis5code"] = @$resultOutboundHatsuZipDll["JIS5Code"];
        // $inForm["hatsu_shop_check_code"] = @$resultOutboundHatsuZipDll["ShopCheckCode"];
        // $inForm["hatsu_shop_check_code_eda"] = @$resultOutboundHatsuZipDll["ShopCheckCodeEda"];
        // $inForm["hatsu_shop_code"] = @$resultOutboundHatsuZipDll["ShopCode"];
        // $inForm["hatsu_shop_local_code"] = @$resultOutboundHatsuZipDll["ShopLocalCode"];

        // イベントサブ住所
        $inForm["hatsu_jis2code"] = @$resultEventZipDll["JIS2Code"];
        $inForm["hatsu_jis5code"] = @$resultEventZipDll["JIS5Code"];
        $inForm["hatsu_shop_check_code"] = @$resultEventZipDll["ShopCheckCode"];
        $inForm["hatsu_shop_check_code_eda"] = @$resultEventZipDll["ShopCheckCodeEda"];
        $inForm["hatsu_shop_code"] = @$resultEventZipDll["ShopCode"];
        $inForm["hatsu_shop_local_code"] = @$resultEventZipDll["ShopLocalCode"];

        $inForm["chaku_jis2code"] = @$resultEventZipDll["JIS2Code"];
        $inForm["chaku_jis5code"] = @$resultEventZipDll["JIS5Code"];
        $inForm["chaku_shop_check_code"] = @$resultEventZipDll["ShopCheckCode"];
        $inForm["chaku_shop_check_code_eda"] = @$resultEventZipDll["ShopCheckCodeEda"];
        $inForm["chaku_shop_code"] = @$resultEventZipDll["ShopCode"];
        $inForm["chaku_shop_local_code"] = @$resultEventZipDll["ShopLocalCode"];
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
            
        $procList = array(
            'tableTreeData' => $tableTreeData,
        );
        
        $resultList = array();
        foreach ($procList as $keyTree => $valTree) {
        
            foreach($valTree["comiket_detail_list"] as $keyDet => $valDet) {
                    $costTotal = 0;
                    $fareTotal = 0;
                    if(isset($valDet["comiket_box_list"])){
                        foreach($valDet["comiket_box_list"] as $keyComiketBox => $valComiketBox) {

                            $boxInfo = $this->_BoxService->fetchBoxById($db, $valComiketBox['box_id']);
                            if(!empty($boxInfo)) {
                                // 保管料金（税込）
                                $costTotal += intval($boxInfo['cost_tax']) * intval($valComiketBox['num']);

                                $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["cost_price"] = 0;
                                $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["cost_amount"] = 0;

                                $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["cost_price_tax"] = intval($boxInfo['cost_tax']);
                                $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["cost_amount_tax"] = intval($boxInfo['cost_tax']) * intval($valComiketBox['num']);
                            }
                        }

                    /////////////////////////////////////////////////////////
                    //// 料金計算(子) 【comiket_detail】
                    /////////////////////////////////////////////////////////
                    $valTree["comiket_detail_list"][$keyDet]['fare'] = ceil((string)($fareTotal / Sgmov_View_Azk_Common::CURRENT_TAX));

                    $valTree["comiket_detail_list"][$keyDet]['fare_tax'] = $fareTotal;

                    $valTree["comiket_detail_list"][$keyDet]['cost'] = ceil((string)($costTotal / Sgmov_View_Azk_Common::CURRENT_TAX));
                    // // 税込
                    $valTree["comiket_detail_list"][$keyDet]['cost_tax'] = $costTotal;
                }
            }
            
            $resultList[$keyTree] = $valTree;
        }

        $tableTreeData = $resultList['tableTreeData'];
        

        
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
        // comiket_azukari データ作成
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        //$comiketAzukariDataList = $this->_createComiketAzukariInsertDataByInForm($inForm, $comiketId);

        // // 配送用
        // $comiketAzukariDataListForHaiso = array();
        // foreach($comiketAzukariDataList as $key => $val) {
        //     $comiketAzukariDataListForHaiso[] = $val;
        // }

        //  // [配送用] comiket_box 設定
        // foreach($comiketAzukariDataListForHaiso as $key => $val) {
        //     foreach($comiketDataForHaiso["comiket_detail_list"] as $key2 => $val2) {
        //         $comiketDataForHaiso["comiket_detail_list"][$key2]["comiket_azukari_list"][$key] = $val;
        //     }
        // }

        
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // comiket_box データ作成
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



        return array (
            //////////////////////////////////////////////////////////////////////////////////////////////
            // 配送用
            //////////////////////////////////////////////////////////////////////////////////////////////
            "treeData" => $comiketDataForHaiso,
            "flatData" => array(
                "comiketData" => $comiketDataForHaiso,
                "comiketDetailDataList" => $comiketDetailDataListForHaiso,
                //"comiketAzukariDataList" => $comiketAzukariDataList,
                "comiketBoxDataList" => $comiketBoxDataList,
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


    public function _createComiketInsertDataByInForm($inForm, $id, $type="") {
        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        $batch_status = '1';

        $customerCd = $inForm['comiket_customer_cd'];
        $merchantResult = @$inForm['merchant_result'];
        
        $customerCd = $this->getCustomerCd($inForm['event_sel']);
        
        $inForm['office_name'] = "";

        if($inForm['comiket_payment_method_cd_sel'] == '1') { // コンビニ前払
            $inForm['authorization_cd'] = NULL;
        } else if($inForm['comiket_payment_method_cd_sel'] == '2') { // クレジット
            $inForm['comiket_convenience_store_cd_sel'] = NULL;
        } else { // 電子マネー
            $inForm['comiket_convenience_store_cd_sel'] = NULL;
            $inForm['payment_order_id'] = NULL;
            $inForm['authorization_cd'] = NULL;
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
            
            $customerCd = $this->getCustomerCd($comiketInfo['event_id']);
            $comiketInfo['customer_cd'] = $customerCd;

            return $comiketInfo;
        }

        $buildingNameRes = $this->_BuildingService->fetchBuildingNameByCd($db, $inForm['building_name_sel'], $inForm['eventsub_sel']);
        $buildingInfo = $this->_BuildingService->fetchBuildingById($db, $inForm['building_booth_position_sel']);

        $eventsubData = $this->_EventsubService->fetchEventsubByEventsubId($db, $inForm['eventsub_sel']);

        // 都道府県
        $rmTodoFuken = mb_substr ($eventsubData["address"], 3, mb_strlen($eventsubData["address"], "UTF-8"), "UTF-8");

        // イベントサブ住所
        $address = mb_substr ($rmTodoFuken, 0, 14, "UTF-8");

        // イベントサブ番地
        $banchi = (mb_strlen($rmTodoFuken, "UTF-8") > 14 ? mb_substr ($rmTodoFuken, 14, mb_strlen($rmTodoFuken, "UTF-8"), "UTF-8") : "");

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
            "div" => @$inForm['comiket_div'],
            "event_id" => $inForm['event_sel'],
            "eventsub_id" => $inForm['eventsub_sel'],
            "customer_cd" => $customerCd,
            "office_name" => $inForm['office_name'],
            "personal_name_sei" => @empty($inForm['comiket_personal_name_sei']) ? "-" : $inForm['comiket_personal_name_sei'],
            "personal_name_mei" => @empty($inForm['comiket_personal_name_mei']) ? "-" : $inForm['comiket_personal_name_mei'],
           
            // "zip" => @$inForm['comiket_zip1'] . @$inForm['comiket_zip2'],
            // "pref_id" => @$inForm['comiket_pref_cd_sel'],
            // "address" => @$inForm['comiket_address'],
            // "building" => @$inForm['comiket_building'],
            // "tel" => @$inForm['comiket_tel'],

            "zip" => $eventsubData["zip"],
            "pref_id" => substr($eventsubData['jis5cd'], 0, 2),
            "address" => $address,
            "building" => $banchi,
            "tel" => @$inForm['comiket_tel'],

            "mail" => @$inForm['comiket_mail'],
            "booth_name" => @$inForm['comiket_booth_name'],
            "building_name" => @$buildingNameRes['name'],
            "booth_position" => empty($buildingInfo) ? "" : $buildingInfo['booth_position'],
            "booth_num" => @@sprintf('%04s', $inForm['comiket_booth_num']),
            "staff_sei" => @empty($inForm['comiket_staff_sei']) ? "-" : $inForm['comiket_staff_sei'],
            "staff_mei" => @empty($inForm['comiket_staff_mei']) ? "-" : $inForm['comiket_staff_mei'],
            "staff_sei_furi" => @empty($inForm['comiket_staff_sei_furi']) ? "-" : $inForm['comiket_staff_sei_furi'],
            "staff_mei_furi" => @empty($inForm['comiket_staff_mei_furi']) ? "-" : $inForm['comiket_staff_mei_furi'],
            "staff_tel" => @empty($inForm['comiket_staff_tel']) ? "00000000000" : $inForm['comiket_staff_tel'],
            "choice" => "6", // 手荷物数量
            "amount" => "0", // ?
            "amount_tax" => "0", // ?
            "create_ip" => $_SERVER["REMOTE_ADDR"],
            "modify_ip" => $_SERVER["REMOTE_ADDR"],
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

    public function _createComiketDetailInsertDataByInForm($inForm, $id) {
        $returnList = array();

        $customerCd = "";
        if(!empty($id)) {
            $customerCd = sprintf("%07d", $id);
        }

        $db = Sgmov_Component_DB::getPublic();

        $comiket_detail_collect_date = "";
        if(!@empty($inForm['comiket_detail_collect_date_year_sel'])
                    && !@empty($inForm['comiket_detail_collect_date_month_sel'])
                    && !@empty($inForm['comiket_detail_collect_date_day_sel'])) {
            $comiket_detail_collect_date =
                    $inForm['comiket_detail_collect_date_year_sel']
                    . '-' . $inForm['comiket_detail_collect_date_month_sel']
                    . '-' . $inForm['comiket_detail_collect_date_day_sel'];
        }


        $comiket_detail_delivery_date = "";
        $collectStTime = null;
        $collectEdTime = null;
        $deliveryStTime = null;
        $deliveryEdTime = null;
        $deliveryDate = null;
        $timezoneCd = '';
        $timezoneNm = '';

        $note = $inForm['comiket_detail_note1'];


        $eventsubData = $this->_EventsubService->fetchEventsubByEventsubId($db, $inForm['eventsub_sel']);

        // 都道府県
        $rmTodoFuken = mb_substr ($eventsubData["address"], 3, mb_strlen($eventsubData["address"], "UTF-8"), "UTF-8");

        // イベントサブ住所
        $address = mb_substr ($rmTodoFuken, 0, 14, "UTF-8");

        // イベントサブ番地
        $banchi = (mb_strlen($rmTodoFuken, "UTF-8") > 14 ? mb_substr ($rmTodoFuken, 14, mb_strlen($rmTodoFuken, "UTF-8"), "UTF-8") : "");

        $data = array(
            "comiket_id" => $id,
            "type" => "4",
            "cd" => "ev{$customerCd}2",
            "name" => $inForm['comiket_detail_name'],

            "hatsu_jis5code" => @$inForm["hatsu_jis5code"],
            "hatsu_shop_check_code" => @$inForm["hatsu_shop_check_code"],
            "hatsu_shop_check_code_eda" => @$inForm["hatsu_shop_check_code_eda"],
            "hatsu_shop_code" => @$inForm["hatsu_shop_code"],
            "hatsu_shop_local_code" => @$inForm["hatsu_shop_local_code"],

            "chaku_jis5code" => @$inForm["chaku_jis5code"],
            "chaku_shop_check_code" => @$inForm["chaku_shop_check_code"],
            "chaku_shop_check_code_eda" => @$inForm["chaku_shop_check_code_eda"],
            "chaku_shop_code" => @$inForm["chaku_shop_code"],
            "chaku_shop_local_code" => @$inForm["chaku_shop_local_code"],

            // "zip" => @$inForm['comiket_detail_zip1'] . @$inForm['comiket_detail_zip2'],
            // "pref_id" => @$inForm['comiket_detail_pref_cd_sel'],
            // "address" => @$inForm['comiket_detail_address'],
            // "building" => @$inForm['comiket_detail_building'],
            // "tel" => @$inForm['comiket_detail_tel'],

            "zip" => $eventsubData["zip"],
            "pref_id" => substr($eventsubData['jis5cd'], 0, 2),
            "address" => $address,
            "building" => $banchi,
            "tel" =>  @$inForm['comiket_tel'],

            "collect_date" => $comiket_detail_collect_date,
            "collect_st_time" => $collectStTime,
            "collect_ed_time" => $collectEdTime,

            "delivery_date" => $deliveryDate,
            "delivery_st_time" => $deliveryStTime,
            "delivery_ed_time" => $deliveryEdTime,

            "service" => @$inForm['comiket_detail_service_sel'],
            "note" => $note,
            "fare" => "0", // ?
            "fare_tax" => "0", // ?
            "cost" => "0", // ?
            "cost_tax" => "0", // ?
            "delivery_timezone_cd" => $timezoneCd,
            "delivery_timezone_name" => $timezoneNm,
            "binshu_kbn" => '0',
            "azukari_kaisu_type" => @$inForm["comiket_detail_azukari_kaisu_type_sel"],
            "toiawase_no" => @$inForm['comiket_toiawase_no'],
            "toiawase_no_niugoki" => @$inForm['comiket_toiawase_no_niugoki']
        );

        $returnList[] = $data;

        return $returnList;
    }

    public function _createComiketBoxInsertDataByInForm($inForm, $id) {

        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        $returnList = array();

        foreach($inForm['comiket_box_num_ary'] as $key => $val) {
            if(empty($val)) {
                continue;
            }

            // 預かり箱マスタ
            //$boxData = $this->_AzukariBoxService->fetchBoxById($db, $key);
          
            $boxData = $this->_BoxService->fetchBoxById($db, $key);
            $fareTax = 0;
            if(!@empty($boxData["cost_tax"])) {
                $fareTax = intval($boxData["cost_tax"]);
            } 

            $fareAmountTax = $fareTax * intval($val);
            $data = array(
                "comiket_id" => $id,
                "type" => "4", // 手荷物
                "box_id" => $key,
                "num" => "$val",
                "fare_price" => "0", // 法人
                "fare_amount" => "0", // 法人
                "fare_price_tax" => $fareTax, // ?
                "fare_amount_tax" => $fareAmountTax, // ?
                "cost_price" => "0", // 法人
                "cost_amount" => "0", // 法人
                "cost_price_tax" => "0", // ?
                "cost_amount_tax" => "0", // ?
            );
            $returnList[] = $data;
        }

        return $returnList;
    }

    // public function _createComiketAzukariInsertDataByInForm($inForm, $id) {

    //     // DBへ接続
    //     $db = Sgmov_Component_DB::getPublic();

    //     $returnList = array();

    //     foreach($inForm['comiket_box_num_ary'] as $key => $val) {
    //         if(empty($val)) {
    //             continue;
    //         }

    //         // 預かり設定マスタ
    //         $azukariData = $this->_AzukariSetting->fetchByEventSubId($db, $inForm['eventsub_sel']);
            
    //         // 預かり箱マスタ
    //         $boxData = $this->_AzukariBoxService->fetchBoxById($db, $key);

    //         for ($i=1; $i <= $azukariData["max_azukari_cd"]; $i++) { 
    //             $azukariCd = sprintf("%03d", $i);
    //             for ($j=1; $j <= $azukariData["max_azukari_cd_sub"]; $j++) { 
    //                 $data[] = array(
    //                     "comiket_id" => $id,
    //                     "basho_cd" => "A", // TODO
    //                     "azukari_cd" => $azukariCd,
    //                     "azukari_cd_sub" => $j,
    //                     "azukari_box_id" => $boxData["id"],
    //                     "event_id" => $inForm['event_sel'],
    //                     "eventsub_id" => $inForm['eventsub_sel']
    //                 );
    //             }
    //         }
    //         //$returnList[] = $data;
    //     }

    //     return $data;
    // }

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

        if($termFrYMD <= $currentYMDForFr && $currentYMDForTo <= $termToYMD) {
            return TRUE;
        }

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
                );

                $total = 0;
                for ($i = 0; $i < count($intCheck); $i++) {
                    $total += $param2[$i] * $intCheck[$i];
                }
        
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
    public function sendCompleteMail($comiket, $sendTo2 = '', $sendCc = '', $tmplateType = '') {

        try {
            
            if (@empty($tmplateType)) {
                //添付ファイルの有無を判別
                $isAttachment = ( $comiket['choice'] == 2 || $comiket['choice'] == 3 ) ? true : false;
            } else {
                $isAttachment = true;
                if ($tmplateType == 'cancel' || $tmplateType == 'sgmv_cancel') { // キャンセルメールの場合はqrコードは添付しない
                    $isAttachment = false;
                }
                // if ($tmplateType == 'cancel' || $tmplateType == 'sgmv_cancel') { // キャンセルメールの場合はqrコードは添付しない
                //     $isAttachment = false;
                // } else {
                //     $isAttachment = true;
                //     $comiketDetailList = $comiket['comiket_detail_list'];
                //     $isAttachment = false;
                //     if ($comiketDetailList[0]['type'] == 2) { // 復路
                //         $isAttachment = true;
                //     }
                // }
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

            /////////////////////////////////////////////////////////////////////////////////////////////
            // 管理者用のために、コンビニ前払い-未払いの場合にメール内容に文言を追加する
            /////////////////////////////////////////////////////////////////////////////////////////////
            // $payMethodList = array('1' => 'コンビニ前払い', '2' => 'クレジット', '3' => '電子マネー', '4' => 'コンビニ後払い', '5' => '法人売掛',);
            // if (@$comiket['payment_method_cd'] == '1') { // コンビニ前払い
            //     if (@empty($comiket['receipted'])) {
            //         $data['conveni_prepay_status'] = 'コンビニ前払い:未';
            //     } else {
            //         $data['conveni_prepay_status'] = 'コンビニ前払い:済';
            //     }
            // } else {
            //     if (@empty($payMethodList[$comiket['payment_method_cd']])) {
            //         $data['conveni_prepay_status'] = "";
            //     } else {
            //         $data['conveni_prepay_status'] = $payMethodList[$comiket['payment_method_cd']];
            //     }
            // }

             if (@empty($payMethodList[$comiket['payment_method_cd']])) {
                    $data['conveni_prepay_status'] = "";
            } else {
                $data['conveni_prepay_status'] = $payMethodList[$comiket['payment_method_cd']];
            }
            /////////////////////////////////////////////////////////////////////////////////////////////
            
            $week = ['日', '月', '火', '水', '木', '金', '土'];

            $frDay = date('w',strtotime($eventsubData["term_fr"]));
            $toDay = date('w',strtotime($eventsubData["term_to"]));

            $termFr = new DateTime($eventsubData["term_fr"]);
            $termTo = new DateTime($eventsubData["term_to"]);

            $termFrName = $termFr->format('Y年m月d日');
            $termToName = $termTo->format('Y年m月d日');
            $comiketPrefData = $this->_PrefectureService->fetchPrefecturesById($db, $comiket['pref_id']);

            $data['comiket_id'] = sprintf('%010d', $comiket['id']);//【コミケID】
            $data['event_name'] = $eventData["name"] . " " . $eventsubData["name"]; //【出展イベント】
                
            // 【期間】年月日　(曜日)
            $kikan = $termFrName ."(".$week[$frDay].")" . " ～ " . $termToName ."(".$week[$toDay].")";
            if($termFrName == $termToName) {
                $kikan = $termFrName ."(".$week[$frDay].")";
            }

            $data['period_name'] = $kikan; //【期間】

            $data['place_name'] = $eventsubData["venue"];//【場所】

            // // 【ブースNO】
            // $data['comiket_booth_name'] = '';
            // if ($eventsubData['booth_display'] !== '0') {
            //     // 【ブース名】
            //     if ($eventData['id'] === '10') {  // 国内クルーズ
            //         $data['comiket_booth_name'] = PHP_EOL . '【部屋番号】' . $comiket['booth_name'];
            //     } else {
            //         $data['comiket_booth_name'] = PHP_EOL . '【ブース名】' . $comiket['booth_name'];
            //     }
            // }

            // // 【ブースNO】
            // $data['comiket_building_name'] = '';
            // if ($eventsubData['building_display'] !== '0') {
            //     $building = $comiket['building_name']. "ホール ";
            //     if($comiket['building_name'] == "その他"){
            //         $building = "";
            //     }
            //     $data['comiket_building_name'] = PHP_EOL . '【ブースNO】' . $building . $comiket['booth_position'] . " " . $comiket['booth_num'];
            // }

           
            //個人用メールテンプレート
            $mailTemplate[] = "/azk_complete_individual{$tmplateType}.txt";
            $mailTemplateSgmv[] = "/azk_complete_individual_sgmv{$tmplateType}.txt";

            $data['surname'] = $comiket['personal_name_sei'];
            $data['forename'] = $comiket['personal_name_mei'];
          
            //$data['surname'] = $comiket['staff_sei_furi'];
            //$data['forename'] = $comiket['staff_mei_furi'];
          
            $data['comiket_div'] = '出展者'; // デザインフェスタ
            if($eventData['id'] === '2') { // コミケ
                $data['comiket_div'] = '電子決済の方(クレジット、コンビニ決済、電子マネー)';
            }
            $data['comiket_personal_name_seimei'] = $comiket['personal_name_sei'] . " " . $comiket['personal_name_mei'];
          
            // $comiketDetailTypeLbls = $this->comiket_detail_type_lbls;
            // $data['comiket_choice'] = $comiketDetailTypeLbls[$comiket['choice']];
            // $data['convenience_store'] = "";
            $attention_message = '';
            
            // if($comiket['payment_method_cd'] == '1') { // お支払い方法 = コンビニ前払
            //     $convenienceStoreLbls = $this->convenience_store_lbls;
            //     $convenienceStoreCd = intval($comiket['convenience_store_cd']);
            //     $data['convenience_store'] = " （" . $convenienceStoreLbls[$convenienceStoreCd] . "）"
            //             . PHP_EOL . "【受付番号】{$comiket['receipt_cd']}";

            //     //払込票URL
            //     if(@!empty($comiket['payment_url'])) {
            //         $data['convenience_store'] .= PHP_EOL . "【払込票URL】{$comiket['payment_url']}";
            //     }
            //     // // 申込区分が【往路】ならば注意文言を表示する
            //     // if (@$type == '1') {
            //     //     $attention_message = '※お支払いはお預かり日時の前日17時までに入金していただきますようお願いいたします。';
            //     // }
            // }

            $data['attention_message'] = $attention_message;

            $paymentMethodLbls = $this->payment_method_lbls;
            // $data['comiket_payment_method'] = $paymentMethodLbls[$comiket['payment_method_cd']] . $data['convenience_store'];
            $data['comiket_payment_method'] = $paymentMethodLbls[$comiket['payment_method_cd']];

            $data['digital_money_attention_note'] = "";
            if($comiket['payment_method_cd'] == '3') { // 電子マネー
                $data['digital_money_attention_note'] = "※ イベント当日、受付にて電子マネーでの決済をお願いします。";
            }

            // 【期間】
            $kikan = $termFrName ."(".$week[$frDay].")" . " ～ " . $termToName ."(".$week[$toDay].")";
            if($termFrName == $termToName){
                $kikan = $termFrName ."(".$week[$frDay].")";
            }

            // $data['comiket_zip'] = "〒" . substr($comiket['zip'], 0, 3) . '-' . substr($comiket['zip'], 3);//【郵便番号】
            // $data['comiket_pref_name'] = $comiketPrefData['name'];//【都道府県】
            // $data['comiket_address'] = $comiket['address'];//【住所】
            // $data['comiket_building'] = $comiket['building'];//【ビル】
            // $data['comiket_tel'] = $comiket['tel'];//【電話番号】
            // $data['comiket_mail'] = $comiket['mail'];//【メール】
            // /$data['comiket_staff_seimei'] = $comiket['staff_sei'] . " " . $comiket['staff_mei'];//【セイ】【メイ】
            $data['comiket_staff_seimei_furi'] = $comiket['staff_sei_furi'] . " " . $comiket['staff_mei_furi'];//【セイ】【メイ】フリ
            $data['comiket_staff_tel'] = $comiket['staff_tel'];//【担当電話番号】

            // 数量
            $comiket_detail_list = $comiket['comiket_detail_list'];

            foreach ($comiket_detail_list as $k => $comiket_detail) {
                $num_area = '';
                switch ($comiket_detail['service']) {
                    case 5://手荷物
                        $num_area .= '【手荷物数量】' . PHP_EOL;
                        $comiket_box_list = (isset($comiket_detail['comiket_box_list'])) ? $comiket_detail['comiket_box_list'] : array();
                        foreach ($comiket_box_list as $cb => $comiket_box) {
                            $boxInfo = $this->_BoxService->fetchBoxById($db, $comiket_box['box_id']);
                            $boxName = $boxInfo['name_display'];
                            if(empty($boxName)){
                                $boxName = $boxInfo['name'];
                            }
                           
                            $num_area .= '    ' . @strip_tags($boxName) . ' ［' . $comiket_box['num'] . ' 個］' . PHP_EOL;
                        }
                        break;
                }

                if(empty($comiket_detail['collect_date'])) {
                    $collectDateName = "";
                } else {
                    $collectDate = new DateTime($comiket_detail['collect_date']);
                    $extWeek = date('w',strtotime($comiket_detail["collect_date"]));
                    $collectDateName = $collectDate->format('Y年m月d日')."(".$week[$extWeek].")";
                }

                // if(empty($comiket_detail['delivery_date'])) {
                //     $deliveryDateName = "";
                // } else {
                //     $deliveryDate = new DateTime($comiket_detail['delivery_date']);
                //     $extWeek = date('w',strtotime($comiket_detail["delivery_date"]));
                //     $deliveryDateName = $deliveryDate->format('Y年m月d日')."(".$week[$extWeek].")";
                // }

                if(empty($comiket_detail['collect_st_time'])
                        || $comiket_detail['collect_st_time'] == "00") {
                    $collectStTimeName = "指定なし";
                    $collectEdTimeName = "";
                } else {
                    $collectStTime = new DateTime($comiket_detail['collect_st_time']);
                    $collectEdTime = new DateTime($comiket_detail['collect_ed_time']);
                    $collectStTimeName = $collectStTime->format("H:i") . "～";
                    $collectEdTimeName = $collectEdTime->format("H:i");
                }


                // if(empty($comiket_detail['delivery_st_time'])
                //         || $comiket_detail['delivery_st_time'] == "00") {
                    
                //     $deliveryStTimeName = @$comiket_detail['delivery_timezone_name'];
                //     $deliveryEdTimeName = "";
                // } else {
                //     $deliveryStTime = new DateTime($comiket_detail['delivery_st_time']);
                //     $deliveryEdTime = new DateTime($comiket_detail['delivery_ed_time']);
                //     $deliveryStTimeName = $deliveryStTime->format("H:i") . "～";
                //     $deliveryEdTimeName = $deliveryEdTime->format("H:i");
                // }

                $serviceNum = intval($comiket_detail['service']);
                $serviceName = $this->comiket_detail_service_lbls[$serviceNum];

                // https or https
                $urlPublicSsl = Sgmov_Component_Config::getUrlPublicSsl();

                $comiketType = "type1";

                // 説明書URL
                $data['manual_url'] = '';
                $prefData = $this->_PrefectureService->fetchPrefecturesById($db, $comiket_detail['pref_id']);
                //搬出用メールテンプレート
                $mailTemplate[] = '/azk_parts_complete_choice_1.txt';

                // 申込者と営業所で使用するメールテンプレートをわけ別々に送信する
                $mailTemplateSgmv[] = '/azk_parts_complete_choice_1_sgmv.txt';

                $data['manual_url'] = "【説明書】". PHP_EOL . $urlPublicSsl . "/azk/pdf/manual/{$eventData['name']}{$eventsubData['name']}.pdf";

                $comiketType = "type1";//手荷物

                $data[$comiketType.'_name'] = $comiket_detail['name'];                                                             //【配送先名】
                // $data[$comiketType.'_zip'] = "〒" . substr($comiket_detail['zip'], 0, 3) . '-' . substr($comiket_detail['zip'],3); //【配送先郵便番号】
                // $data[$comiketType.'_pref'] = $prefData["name"];                                                                   //【都道府県】
                // $data[$comiketType.'_address'] = $comiket_detail['address'];                                                       //【市町村区】
                // $data[$comiketType.'_building'] = $comiket_detail['building'];                                                     //【建物番地名】
                $data[$comiketType.'_tel'] = $comiket_detail['tel'];                                                               //【配送先電話番号】
                $data[$comiketType.'_collect_date'] = $collectDateName;                                                            //【お預かり日時】
                $data[$comiketType.'_collect_st_time'] = $collectStTimeName;
                $data[$comiketType.'_collect_ed_time'] = $collectEdTimeName;
                // $data[$comiketType.'_delivery_date'] = $deliveryDateName;                                                          //【お届け日時】
                // $data[$comiketType.'_delivery_st_time'] = $deliveryStTimeName;
                // $data[$comiketType.'_delivery_ed_time'] = $deliveryEdTimeName;
                $data[$comiketType.'_service'] = $serviceName;                                                                     //【サービス選択】
                $data[$comiketType.'_num_area'] = $num_area;                                                                       //【数量】
                //$data[$comiketType.'_note'] = $comiket_detail['note'];                                                             //【備考】
                $data['toiawase_no'] = @$comiket_detail["toiawase_no_niugoki"];                                                    //【問合せ番号】

            }

            
            $footerTmpName = $tmplateType;
            if (@empty($footerTmpName)) {
                $footerTmpName = "_complete";
            }

            $mailTemplate[] = "/azk_parts{$footerTmpName}_footer_type_1.txt";

            // 2018.09.10 tahira add 申込者と営業所で使用するメールテンプレートをわけ別々に送信する
            $mailTemplateSgmv[] = "/azk_parts{$footerTmpName}_footer_type_1.txt";

            $data['comiket_amount'] = '\\' . number_format($comiket['amount']);
            $data['comiket_amount_tax'] = '\\' . number_format($comiket['amount_tax']);

            if($eventsubData['manual_display'] != '1') { // 説明書「1」以外の場合は、表示されない。
                $data['manual_url'] = "";
            }

            $comiketIdCheckD = self::getChkD(sprintf("%010d", $comiket['id']));
            $data['edit_url'] = $urlPublicSsl . "/azk/input/" . sprintf('%010d', $comiket["id"]) . $comiketIdCheckD;

            $data['paste_tag_url'] = "";
            if(($comiket['choice'] == "1" || $comiket['choice'] == "3")
                    && $eventsubData['paste_display'] == '1')  {
                    // 、もしくは往復 かつ 宅配orカーゴ(搬入、搬出のいずれか) かつ貼付票フラグが'0'の場合
                // 貼付票URL
                $pasteTagId = sprintf("%010d", $comiket['id']) . self::getChkD2($comiket['id']);
                //$data['paste_tag_url'] = "【貼付票URL】" . PHP_EOL . $urlPublicSsl . "/azk/paste_tag/{$pasteTagId}/";
            }
            
//             $data['explanation_url_convenience_payment_method'] = "";
//             if($comiket['payment_method_cd'] == '1') { // お支払い方法 = コンビニ前払
//                     $data['explanation_url_convenience_payment_method'] =
// "
// 以下の内容でお支払いください。
// ・セブンイレブン
// 　受付番号をメモまたは払込票を印刷し、
//   セブンイレブンのレジカウンターにてお支払い手続きをお願いいたいます。
  
// ・ローソン
// ・セイコーマート
// ・ファミリーマート
// ・ミニストップ
// 　受付番号を控えて、コンビニ備え付けの端末でお支払い手続きをお願いいたします。
//   端末操作方法は、各コンビニエンスストアのホームページにてご確認ください。

// ・デイリーヤマザキ
// 　受付番号をメモまたは払込票を印刷し、
//   デイリーヤマザキ店頭レジにてお支払い手続きをお願いいたいます。
// ";
    //        }
   

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // サイズ変更/キャンセルURL
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            $data['cancel_url'] = $urlPublicSsl . "/azk/cancel/" . sprintf('%010d', $comiket["id"]) . $comiketIdCheckD;
            $data['size_change_url'] = $urlPublicSsl . "/azk/size_change/" . sprintf('%010d', $comiket["id"]) . $comiketIdCheckD;
            //-------------------------------------------------
            //メール送信実行
            $objMail = new Sgmov_Service_CenterMail();
            
            if (!$isAttachment) {
                // 申込者へメール
                $objMail->_sendThankYouMail($mailTemplate, $sendTo, $data);

                // 2018.09.10 tahira add 申込者と営業所で使用するメールテンプレートをわけ別々に送信する
                // 営業所へメール(CCとして設定する用にもっていた変数をToの方へ)
                if ($sendCc !== null && $sendCc !== '') {
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
            echo "<pre>";
            print_r($e);exit;
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
    protected function execWebApiCancelComiket($wsProtocol, $wsHost, $wsPath, $wsPort, $param, $paramOrg , $dispErrTitle = "", $dispErrMsg = "") {
        
        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();
        
        $comiketInfo = $this->_ComiketService->fetchComiketById($db, $param);

        // コミケ明細申込データ
        $comiketDetailList = $this->_ComiketDetailService->fetchComiketDetailByComiketId($db, $param);
        
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // ▼ 業務連携用リクエストデータ作成
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        
        $sendFileName = 'AZK_' . date ( 'YmdHis' ) . '.csv';
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
            $message = "デザインフェスタ: 業務連携に失敗しました。\n[azk] {$clasName} : paramOrg = {$paramOrg} / param = {$param}\n\nDB comiket.del_flg（論理削除フラグ）は更新しています。\n\n";
            $message .= "Exceptionメッセージ: " . $e->getMessage() . "\n\n";
            $message .= "業務サーバ接続情報: _wsProtocol: {$wsProtocol} / _wsHost: {$wsHost} / _wsPort: {$wsPort} / _wsPath: {$wsPath}";

            // システム管理者メールアドレスを取得する。
            $mailTo = Sgmov_Component_Config::getLogMailTo ();

            $mailData = $comiketInfo;
            $mailData['message'] = $message;
            $mailTemplateList = array(
                "/azk_cancel_individual_error.txt",
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
}
