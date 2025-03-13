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
    , 'Event', 'Box', 'Building', 'Charter', 'Eventsub'
    , 'AppCommon', 'HttpsZipCodeDll', 'BoxFare', 'Comiket', 'EventsubCmb', 'Time', 'CenterMail', 'Shohin', 'ComiketBox', 'ComiketDetail', 'Building'));
Sgmov_Lib::useView('Public');
/**#@-*/

/**
 * 物販お申し込みフォームの共通処理を管理する抽象クラスです。
 * @package    View
 * @subpackage BPN
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Bpn_Common extends Sgmov_View_Public {
    /**
     * 機能ID
     */
    const FEATURE_ID = 'BPN';

    /**
     * BPN001の画面ID
     */
    const GAMEN_ID_BPN001 = 'BPN001';

    /**
     * BPN002の画面ID
     */
    const GAMEN_ID_BPN002 = 'BPN002';

    /**
     * BPN003の画面ID
     */
    const GAMEN_ID_BPN003 = 'BPN003';

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
     * 識別コード選択値
     * @var array
     */
    public $comiket_div_lbls = array(
        1 => '<span class="disp_comiket">電子決済の方(クレジット、コンビニ決済、電子マネー)</span>',
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
        //3  => '現金',
        4  => 'コンビニ後払い',
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
     * @var Sgmov_Service_Comiket
     */
    private $_ComiketService;

    /**
     * コミケ申込明細データサービス
     * @var Sgmov_Service_ComiketDetail
     */
    private $_ComiketDetail;

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
     * @var Sgmov_Service_Box
     */
    private $_BoxService;

    /**
     * 館マスタサービス(ブース番号)
     * @var Sgmov_Service_BuildingService
     */
    private $_BuildingService;

    private $_HttpsZipCodeDll;

    private $_BoxFareService;

    protected $_EventsubCmbService;

    protected $_SocketZipCodeDll;

    protected $_ComiketBox;

    /**
     * 時間帯サービス
     * @var Sgmov_Service_TimeService
     */
    private $_TimeService;

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
        $this->_BuildingService       = new Sgmov_Service_Building();
        $this->_HttpsZipCodeDll       = new Sgmov_Service_HttpsZipCodeDll();
        $this->_BoxFareService        = new  Sgmov_Service_BoxFare();

        $this->_EventsubCmbService    = new Sgmov_Service_EventsubCmb();

        $this->_TimeService           = new Sgmov_Service_Time();

        $this->_SocketZipCodeDll      = new Sgmov_Service_SocketZipCodeDll();
        
        $this->_ShohinService            = new Sgmov_Service_Shohin();
        $this->_ComiketBox            = new Sgmov_Service_ComiketBox();

        $this->_ComiketDetail            = new Sgmov_Service_ComiketDetail();
    }

    /**
     * プルダウンを生成し、HTMLソースを返します。
     * TODO pre/Inputと同記述あり
     *
     * @param array $cds 
     * @param array $lbls 
     * @param integer $select 
     * @param integer $flg 
     * @param integer $date 
     * @return string $html
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
    * 選択済みコンボボックスのラベルを返す
    * @return type
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
     * 商品マスタの情報をまとめる。
     * @param array $dataList
     * @return array $dataList
     */
    protected function setShohinNameList($dataList){
        $totalCnt = 0;
        $soldOutTotal = 0;
        foreach ($dataList as $key => $value) {
            // 完売
            $soldOutCnt = $value['count'];
            if(empty($value['max_shohin_count']) || $value['count'] >= $value['max_shohin_count']){
                $soldOutCnt = 9999;
                $soldOutTotal++;
            }

            $dataList[$key]['soldOutCnt'] = $soldOutCnt;
            

            // 申込期間
            $currentDtWithTime = strtotime(date("Y-m-d H:i:s"));
            $dataList[$key]['expiry_status'] = "1";
            if(($currentDtWithTime >= strtotime($value['term_fr'])) && (strtotime($value['term_to']) >= $currentDtWithTime)){
                $dataList[$key]['expiry_status'] = "0";
            }else{
                $totalCnt++;
            }
        }

        if((count($dataList) == $totalCnt)){
            $dataList["expiry_all"] = "1";
        }elseif($soldOutTotal == count($dataList)){
            $dataList["sold_out_all"] = "1";
        }

        return $dataList;
    }


    /**
     * 曜日の決定
     * @return type
     */
    public static function _getWeek($year, $month, $day) {
        $week = array("日", "月", "火", "水", "木", "金", "土");
        $resultWeek = $week[date('w', strtotime("{$year}-{$month}-{$day}"))];
        
        return $resultWeek;
    }

    /**
     * 入力フォームから画面出力用のオブジェクトを生成
     * @param array $inForm
     * @param Sgmov_Form_Bpn001Out $outForm
     * @return array $outForm
     */
    protected function createOutFormByInForm($inForm, $outForm = array()) {
        $dispItemInfo = array();
      
        // オブジェクトから取得できないため、配列に型変換
        $inForm = (array)$inForm;

        $db = Sgmov_Component_DB::getPublic();



        ////////////////////////////////////////////////////////////////////////////////////////////
        // イベント情報
        ////////////////////////////////////////////////////////////////////////////////////////////
        $eventAll = $this->_EventService->fetchEventAllList($db, $inForm["event_sel"]);

        $eventInfo = $this->_EventService->fetchEventInfoByEventId($db, $inForm["event_sel"]);
        $eventsubInfo = $this->_EventsubService->fetchEventsubByEventsubId($db, $inForm["eventsub_sel"]);

        $dispItemInfo["event_name"] = $eventInfo["name"];
        $dispItemInfo["eventsub_selected_data"] = $eventsubInfo;


        $eventIds = array();
        $eventNames2 = array();
        $week = array("日", "月", "火", "水", "木", "金", "土");
        foreach($eventAll as $val) {
            $eventIds[] = $val["id"];
            $eventNames2[] = $val["event_name"] . "　" . $eventsubInfo['name'];//$val["eventsub_name"];
        }
        $outForm->raw_comiket_id  = @$inForm['comiket_id'];

        $outForm->raw_event_cds  = $eventIds;
        $outForm->raw_event_lbls = $eventNames2;
        $outForm->raw_event_cd_sel = $inForm["event_sel"];
        $outForm->raw_eventsub_cd_sel = $inForm["eventsub_sel"];

        // 出展イベントサブ
        //$eventsubAry2 = $this->_EventsubService->fetchEventsubListWithinTermByEventId($db, $inForm["event_sel"]);

        $eventsubAry3 = array();
        $dispItemInfo["eventsub_list"] = $eventsubAry3;

        // 場所(イベント)
        if(@!empty($dispItemInfo["eventsub_selected_data"])) {
            $outForm->raw_eventsub_zip = $dispItemInfo["eventsub_selected_data"]["zip"];
            $outForm->raw_eventsub_address = $dispItemInfo["eventsub_selected_data"]["address"];
            $outForm->raw_eventsub_term_fr = $inForm["eventsub_term_fr"] = $dispItemInfo["eventsub_selected_data"]["term_fr"];
            $outForm->raw_eventsub_term_to = $inForm["eventsub_term_to"] = $dispItemInfo["eventsub_selected_data"]["term_to"];
            $outForm->raw_eventsub_term_fr_nm = date('Y年m月d日（' . $week[date('w', strtotime($inForm["eventsub_term_fr"]))] . '）', strtotime($inForm["eventsub_term_fr"]));
            $outForm->raw_eventsub_term_to_nm = date('Y年m月d日（' . $week[date('w', strtotime($inForm["eventsub_term_to"]))] . '）', strtotime($inForm["eventsub_term_to"]));
        }

        ////////////////////////////////////////////////////////////////////////////////////////////
        // 入力モード制御
        ////////////////////////////////////////////////////////////////////////////////////////////
        $outForm->raw_input_mode = $inForm['input_mode'];

        ////////////////////////////////////////////////////////////////////////////////////////////
        // コミケ申込
        ////////////////////////////////////////////////////////////////////////////////////////////

        $outForm->raw_comiket_div = '1'; // コミケは個人のみ
        $dispItemInfo["comiket_div_lbls"] = $this->comiket_div_lbls;

        // 顧客コード 使用選択肢
//        if(empty($inForm["comiket_customer_cd_sel"])) {
//            $outForm->raw_comiket_customer_cd_sel = $inForm["comiket_customer_cd_sel"]; // デフォルト:使用する
//        } else {
//            $outForm->raw_comiket_customer_cd_sel = $inForm["comiket_customer_cd_sel"];
//        }

        // 物販タイプ
        $outForm->raw_bpn_type = $inForm["bpn_type"];

        // 商品パタン
        $outForm->raw_shohin_pattern = $inForm["shohin_pattern"];

        // 識別子
        $outForm->raw_shikibetsushi = $inForm["shikibetsushi"];


        $now = new DateTime();
        $year[] = $now->format("Y");
        $month[] = $now->format("m");
        $day[] = $now->format("d");
        if($inForm["bpn_type"] == "1"){
            $startDate = new \DateTime($outForm->raw_eventsub_term_fr);
            $endDate = new \DateTime($outForm->raw_eventsub_term_to);
            
            // $year = ['' => "年を選択"];
            // $month = ['' => "月を選択"];
            // $day = ['' => "日を選択"];

            $year = [];
            $month = [];
            $day = [];
            for($date = $startDate; $date <= $endDate; $date->modify('+1 day')){
                $year[] = $date->format("Y");
                $month[] = $date->format("m");
                $day[] = $date->format("d");
            }
            $year = array_unique($year);
            $month = array_unique($month);
            $month = array_values($month);
            $day = array_unique($day);
            $day = array_values($day);
        }



        if($inForm["bpn_type"] == "2"){
            $inForm["comiket_detail_collect_date_year_sel"] = $now->format("Y");
            $inForm["comiket_detail_collect_date_month_sel"] = $now->format("m");
            $inForm["comiket_detail_collect_date_day_sel"] = $now->format("d");
        }

        $outForm->raw_collect_year_cds  = $year;
        $outForm->raw_collect_year_lbls = $year;
        $outForm->raw_collect_year_cd_sel = $inForm["comiket_detail_collect_date_year_sel"];


        $outForm->raw_collect_month_cds  = $month;
        $outForm->raw_collect_month_lbls = $month;
        $outForm->raw_collect_month_cd_sel = $inForm["comiket_detail_collect_date_month_sel"];

        $outForm->raw_collect_day_cds  = $day;
        $outForm->raw_collect_day_lbls = $day;
        $outForm->raw_collect_day_cd_sel = $inForm["comiket_detail_collect_date_day_sel"];


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

        // ブース名-テキスト
        $outForm->raw_comiket_booth_name = $inForm["comiket_booth_name"];

        // 館名
        $buildingNameInfoAry = $this->_BuildingService->fetchBuildingNameByEventsubId($db, $inForm["eventsub_sel"]);
        $outForm->raw_building_name = $inForm["building_name"]; // 編集画面のラベル表示用
        $outForm->raw_building_name_sel = $inForm["building_name_sel"];
        $outForm->raw_building_name_ids = $buildingNameInfoAry['ids'];
        $outForm->raw_building_name_lbls = $buildingNameInfoAry['names'];

        $buildingListByEventsubId = $this->_BuildingService->fetchBuildingDataByEventsubId($db, $inForm["eventsub_sel"]);
        $boothPostionIds = array();
        $boothPostionLbls = array();

        foreach($buildingListByEventsubId as $val) {
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


        // 商品 1:物販、2:当日物販
        $dataSet = $this->_ShohinService->fetchShohin($db, $inForm["eventsub_sel"], $inForm["bpn_type"], $inForm["shohin_pattern"]);


        $dispItemInfo["input_buppan_lbls"] = $this->setShohinNameList($dataSet);
        if(isset($dispItemInfo["input_buppan_lbls"]["sold_out_all"])){
            unset($dispItemInfo["input_buppan_lbls"]["sold_out_all"]);
            $dispItemInfo["sold_out_all"] = "1";
        }

        $outForm->raw_comiket_box_buppan_num_ary = $inForm["comiket_box_buppan_num_ary"];
        

        $outForm->raw_comiket_box_buppan_ziko_shohin_cd_ary = $inForm["comiket_box_buppan_ziko_shohin_cd_ary"];

        // 数量コンポポックス
        $comiket_box_buppan_num_arr = array();
        foreach (range(1, 10) as $val) {
            $comiket_box_buppan_num_arr[] = $val; 
        }


        $outForm->raw_comiket_box_buppan_num_sel = $inForm["comiket_box_buppan_num_ary"];
        $outForm->raw_comiket_box_buppan_num_ids = $comiket_box_buppan_num_arr;
        $outForm->raw_comiket_box_buppan_num_lbls = $comiket_box_buppan_num_arr;

        ////////////////////////////////////////////////////////////////////////////////////////////
        // 支払
        ////////////////////////////////////////////////////////////////////////////////////////////
        // 送料
        $outForm->raw_delivery_charge = @empty($inForm["delivery_charge"]) ? 0 : $inForm["delivery_charge"];
        
        // 送料
        $outForm->raw_delivery_charge_buppan = @empty($inForm["delivery_charge_buppan"]) ? 0: $inForm["delivery_charge_buppan"];

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

        return array("outForm" => $outForm
                , "dispItemInfo" => $dispItemInfo
            );
    }

    /**
     * 住所と郵便番号と住所情報を取得します。
     * @param integer $zip
     * @param string $address
     * @return array
     */
    public function _getAddress($zip, $address) {
        return $this->_SocketZipCodeDll->searchByAddress($address, $zip);
    }

    /**
     * 郵便番号から住所情報を取得します。
     * @param integer $zip
     * @return array
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
     * 送料の計算
     * @param array $inForm
     * @param string $comiketID
     */
    protected function calcEveryKindData($inForm, $comiketId = "") {
        // DB接続
        $db = Sgmov_Component_DB::getPublic();

        $tableDataInfo = $this->_cmbTableDataFromInForm($inForm, $comiketId);
        $tableTreeData = $tableDataInfo["treeData"];
        $tableTreeDataForBuppan = $tableDataInfo["treeDataForBuppan"];
        $tableTreeData['amount_tax'] = 0;
        $tableTreeData['amount'] = 0;
        $buppanCostTotal = 0;
        $buppanFareTotal = 0;
        $procList = array(
            'tableTreeData' => $tableTreeData,
            'tableTreeDataForBuppan' => $tableTreeDataForBuppan,
        );
            
        $resultList = array();
        foreach ($procList as $keyTree => $valTree) {
            
            foreach($valTree["comiket_detail_list"] as $keyDet => $valDet) {
                        $buppanCostTotal = 0;
                        $buppanFareTotal = 0;
                        if(isset($valDet["comiket_box_list"])){
                            foreach($valDet["comiket_box_list"] as $keyComiketBox => $valComiketBox) {
                                $boxInfo = $this->_ShohinService->fetchShohinById($db, $valComiketBox['box_id']);
                                if(!empty($boxInfo)) {
                                    // 保管料金（税込）
                                    $buppanCostTotal += intval($boxInfo['cost_tax']) * intval($valComiketBox['num']);

                                    $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["cost_price"] = 0;
                                    $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["cost_amount"] = 0;

                                    $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["cost_price_tax"] = intval($boxInfo['cost_tax']);
                                    $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["cost_amount_tax"] = intval($boxInfo['cost_tax']) * intval($valComiketBox['num']);
                                }
                            }

                        /////////////////////////////////////////////////////////
                        //// 料金計算(子) 【comiket_detail】
                        /////////////////////////////////////////////////////////
                        // $valTree["comiket_detail_list"][$keyDet]['fare'] = ceil((string)($buppanFareTotal / Sgmov_View_Bpn_Common::CURRENT_TAX));

                        // $valTree["comiket_detail_list"][$keyDet]['fare_tax'] = $buppanFareTotal;

                        $valTree["comiket_detail_list"][$keyDet]['cost'] = ceil((string)($buppanCostTotal / Sgmov_View_Bpn_Common::CURRENT_TAX));
                        // // 税込
                        $valTree["comiket_detail_list"][$keyDet]['cost_tax'] = $buppanCostTotal;
                    }
                }
                
                $resultList[$keyTree] = $valTree;
            }

            $tableTreeData = $resultList['tableTreeData'];
            $tableTreeDataForBuppan = $resultList['tableTreeDataForBuppan'];

        $procList = array(
            'tableTreeData' => $tableTreeData,
            'tableTreeDataForBuppan' => $tableTreeDataForBuppan,
        );

        $resultList = array();
        foreach ($procList as $keyTree => $valTree) {
            $valTree['amount_tax'] = $tableTreeData['amount'] = 0;
            $detailAmountTotal = 0;
            $detailAmountTaxTotal = 0;
            foreach($valTree["comiket_detail_list"] as $keyDet => $valDet) {
                /////////////////////////////////////////////////////////////////////////////////
                // 税抜計算
                /////////////////////////////////////////////////////////////////////////////////
                //$detailAmountTotal += @empty($valDet['fare']) ? 0 : $valDet['fare'];
                $detailAmountTotal += @empty($valDet['cost']) ? 0 : $valDet['cost'];
                
                /////////////////////////////////////////////////////////////////////////////////
                // 税込計算
                /////////////////////////////////////////////////////////////////////////////////
               // $detailAmountTaxTotal += @empty($valDet['fare_tax']) ? 0 : $valDet['fare_tax'];
                $detailAmountTaxTotal += @empty($valDet['cost_tax']) ? 0 : $valDet['cost_tax'];
            }
            $valTree['amount'] = $detailAmountTotal;
            $valTree['amount_tax'] = $detailAmountTaxTotal;

            $resultList[$keyTree] = $valTree;
        }
        $tableTreeData = $resultList['tableTreeData'];
        $tableTreeDataForBuppan = $resultList['tableTreeDataForBuppan'];

        $flatDataInfo = $this->getFlatData($tableTreeData);
        $flatDataInfoForBuppan = $this->getFlatData($tableTreeDataForBuppan);

        return array(
            "treeData" => $tableTreeData,
            "flatData" => $flatDataInfo,
            "treeDataForBuppan" => $tableTreeDataForBuppan,
            "flatDataForBuppan" => $flatDataInfoForBuppan,
        );

    }

    /**
     * 申込登録を行うカラム情報を取得する
     * @param array $comiketData
     * @return array
     */
    private function getFlatData($comiketData) {
        
        $comiketDetailDataList = $comiketData["comiket_detail_list"];
        $comiketBoxDataList = array();
        foreach($comiketDetailDataList as $val) {
            if(isset($val["comiket_box_list"])) {
                foreach($val["comiket_box_list"] as $val2) {
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
     * 申込登録を行うデータをセットする
     * @param array $inForm
     * @param integer $comiketId
     * @return array
     */
    public function _cmbTableDataFromInForm($inForm, $comiketId="") {
        $comiketDataForBuppan = array();
        
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // comiket データ作成
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $comiketDataForHaiso = $this->_createComiketInsertDataByInForm($inForm, $comiketId);
        $comiketDataForBuppan = $comiketDataForHaiso;
        
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // comiket_detail データ作成
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $comiketDetailDataList = $this->_createComiketDetailInsertDataByInForm($inForm, $comiketId);
        $comiketDetailDataListForHaiso = array();
        $comiketDetailDataListForBuppan = array();
        // 配送用と物販用で分ける
        foreach ($comiketDetailDataList as $key => $val) {
            if ($val['type'] == '5') {
                $comiketDetailDataListForBuppan[] = $val;
            } else {
                $comiketDetailDataListForHaiso[] = $val;
            }
        }
        $comiketDataForHaiso["comiket_detail_list"] = $comiketDetailDataListForHaiso;
        $comiketDataForBuppan["comiket_detail_list"] = $comiketDetailDataListForBuppan;
        
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // comiket_detail データ作成
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $comiketBoxDataList = $this->_createComiketBoxInsertDataByInForm($inForm, $comiketId);
        $comiketBoxDataListForHaiso = array();
        $comiketBoxDataListForBuppan = array();
        // 配送用と物販用で分ける
        foreach($comiketBoxDataList as $key => $val) {
            $comiketBoxDataListForBuppan[] = $val;
            // if ($val['type'] == '5') { // 物販の場合
            //} else {
              //  $comiketBoxDataListForHaiso[] = $val;
            //}
        }
        // // [配送用] comiket_box 設定
        // foreach($comiketBoxDataListForHaiso as $key => $val) {
        //     foreach($comiketDataForHaiso["comiket_detail_list"] as $key2 => $val2) {
        //         if($comiketDataForHaiso["comiket_detail_list"][$key2]["type"] == $val["type"]) {
        //             $comiketDataForHaiso["comiket_detail_list"][$key2]["comiket_box_list"][$key] = $val;
        //         }
        //     }
        // }
        // [物販用] comiket_box 設定
        foreach($comiketBoxDataListForBuppan as $key => $val) {
            foreach($comiketDataForBuppan["comiket_detail_list"] as $key2 => $val2) {
                if($comiketDataForBuppan["comiket_detail_list"][$key2]["type"] == $val["type"]) {
                    $comiketDataForBuppan["comiket_detail_list"][$key2]["comiket_box_list"][$key] = $val;
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
            //////////////////////////////////////////////////////////////////////////////////////////////
            // 物販用
            //////////////////////////////////////////////////////////////////////////////////////////////
            "treeDataForBuppan" => $comiketDataForBuppan,
            "flatDataForBuppan" => array(
                "comiketData" => $comiketDataForBuppan,
                "comiketDetailDataList" => $comiketDetailDataListForBuppan,
                "comiketBoxDataList" => $comiketBoxDataListForBuppan,
                "comiketCargoDataList" => array(),
                "comiketCharterDataList" => array(),
            ),
        );
    }


    /**
     * 当日物販画面用
     * 
     * @param array inForm
     * @param integer comiketId
     *
     * @return array
     *
     */
    protected function calcEveryKindDataActiveShohin($inForm, $comiketId=""){
         /////////////////////////////////////////////////////////////////////////////////
        // 送料計算
        /////////////////////////////////////////////////////////////////////////////////
        // DB接続
        $db = Sgmov_Component_DB::getPublic();

        $tableDataInfo = $this->_comikeTableSetDataForActiveShohin($inForm, $comiketId);

        $tableTreeDataForBuppan = $tableDataInfo["treeDataForBuppan"];
        $tableTreeData['amount_tax'] = 0;
        $tableTreeData['amount'] = 0;
        $buppanCostTotal = 0;
        $buppanFareTotal = 0;
        $procList = array(
            'tableTreeDataForBuppan' => $tableTreeDataForBuppan,
        );
            
        $resultList = array();
        foreach ($procList as $keyTree => $valTree) {
            foreach($valTree["comiket_detail_list"] as $keyDet => $valDet) {
                        $buppanCostTotal = 0;
                        $buppanFareTotal = 0;
                        if(isset($valDet["comiket_box_list"])){
                            foreach($valDet["comiket_box_list"] as $keyComiketBox => $valComiketBox) {
                                $boxInfo = $this->_ShohinService->fetchShohinById($db, $valComiketBox['box_id']);
                                if(!empty($boxInfo)) {
                                    // 保管料金（税込）
                                    $buppanCostTotal += intval($boxInfo['cost_tax']) * intval($valComiketBox['num']);

                                    $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["cost_price"] = 0;
                                    $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["cost_amount"] = 0;

                                    $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["cost_price_tax"] = intval($boxInfo['cost_tax']);
                                    $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["cost_amount_tax"] = intval($boxInfo['cost_tax']) * intval($valComiketBox['num']);
                                }
                            }

                        $valTree["comiket_detail_list"][$keyDet]['cost'] = ceil((string)($buppanCostTotal / Sgmov_View_Bpn_Common::CURRENT_TAX));
                        // // 税込
                        $valTree["comiket_detail_list"][$keyDet]['cost_tax'] = $buppanCostTotal;
                    }
                }
                
                $resultList[$keyTree] = $valTree;
            }

            $tableTreeDataForBuppan = $resultList['tableTreeDataForBuppan'];

        $procList = array(
            'tableTreeDataForBuppan' => $tableTreeDataForBuppan,
        );

        $resultList = array();
        foreach ($procList as $keyTree => $valTree) {
            $valTree['amount_tax'] = $tableTreeData['amount'] = 0;
            $detailAmountTotal = 0;
            $detailAmountTaxTotal = 0;
            foreach($valTree["comiket_detail_list"] as $keyDet => $valDet) {
                /////////////////////////////////////////////////////////////////////////////////
                // 税抜計算
                /////////////////////////////////////////////////////////////////////////////////
                //$detailAmountTotal += @empty($valDet['fare']) ? 0 : $valDet['fare'];
                $detailAmountTotal += @empty($valDet['cost']) ? 0 : $valDet['cost'];
                
                /////////////////////////////////////////////////////////////////////////////////
                // 税込計算
                /////////////////////////////////////////////////////////////////////////////////
               // $detailAmountTaxTotal += @empty($valDet['fare_tax']) ? 0 : $valDet['fare_tax'];
                $detailAmountTaxTotal += @empty($valDet['cost_tax']) ? 0 : $valDet['cost_tax'];
            }
            $valTree['amount'] = $detailAmountTotal;
            $valTree['amount_tax'] = $detailAmountTaxTotal;

            $resultList[$keyTree] = $valTree;
        }
        $tableTreeDataForBuppan = $resultList['tableTreeDataForBuppan'];

        $flatDataInfoForBuppan = $this->getFlatData($tableTreeDataForBuppan);

        return array(
            "treeDataForBuppan" => $tableTreeDataForBuppan,
            "flatDataForBuppan" => $flatDataInfoForBuppan,
        );

    }


    /**
     * 当日物販画面用
     * 
     * @param array inForm
     * @param integer comiketId
     *
     * @return array
     *
     */
    public function _comikeTableSetDataForActiveShohin($inForm, $comiketId){
        $comiketDataForBuppan = array();
        
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // comiket データ作成
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $comiketDataForBuppan = $this->_createComiketInsertDataForActiveShohin($inForm, $comiketId);

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // comiket_detail データ作成
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $comiketDetailDataListForBuppan = $this->_createComiketDetailInsertDataForActiveShohin($inForm, $comiketId);

        $comiketDataForBuppan["comiket_detail_list"] = $comiketDetailDataListForBuppan;
        
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // comiket_detail データ作成
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $comiketBoxDataListForBuppan = $this->_createComiketBoxInsertDataByInForm($inForm, $comiketId);

        // [物販用] comiket_box 設定
        foreach($comiketBoxDataListForBuppan as $key => $val) {
            foreach($comiketDataForBuppan["comiket_detail_list"] as $key2 => $val2) {
                if($comiketDataForBuppan["comiket_detail_list"][$key2]["type"] == $val["type"]) {
                    $comiketDataForBuppan["comiket_detail_list"][$key2]["comiket_box_list"][$key] = $val;
                }
            }
        }

        return array(
            //////////////////////////////////////////////////////////////////////////////////////////////
            // 物販用
            //////////////////////////////////////////////////////////////////////////////////////////////
            "treeDataForBuppan" => $comiketDataForBuppan,
            "flatDataForBuppan" => array(
                "comiketData" => $comiketDataForBuppan,
                "comiketDetailDataList" => $comiketDetailDataListForBuppan,
                "comiketBoxDataList" => $comiketBoxDataListForBuppan,
                "comiketCargoDataList" => array(),
                "comiketCharterDataList" => array(),
            ),
        );
    }


    /**
     * comiketテーブルに登録するデータを整形する
     * @return type
     */
    public function _createComiketInsertDataForActiveShohin($inForm, $id) {
        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        $batch_status = '1';

        $customerCd = $inForm['comiket_customer_cd'];
        //$merchantResult = @$inForm['merchant_result'];
        if($inForm['comiket_div'] == self::COMIKET_DEV_BUSINESS) { // 法人
            $inForm['comiket_personal_name_sei'] = "";
            $inForm['comiket_personal_name_mei'] = "";

            $inForm['comiket_payment_method_cd_sel'] = "5";  // 念のため
            $inForm['comiket_convenience_store_cd_sel'] = NULL;
            $inForm['payment_order_id'] = NULL;
            $inForm['authorization_cd'] = NULL;

        } else { // 個人
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
            $comiketInfo['customer_cd'] = substr($customerCd, 0, 11);

            return $comiketInfo;
        }

        //$buildingNameRes = $this->_BuildingService->fetchBuildingNameByCd($db, $inForm['building_name_sel'], $inForm['eventsub_sel']);
        //$buildingInfo = $this->_BuildingService->fetchBuildingById($db, $inForm['building_booth_position_sel']);

        $eventsubData = $this->_EventsubService->fetchEventsubByEventsubId($db, $inForm['eventsub_sel']);

        // 都道府県
        $rmTodoFuken = mb_substr ($eventsubData["address"], 3, mb_strlen($eventsubData["address"], "UTF-8"), "UTF-8");

        // イベントサブ住所
        $address = mb_substr ($rmTodoFuken, 0, 14, "UTF-8");

        // イベントサブ番地
        $banchi = (mb_strlen($rmTodoFuken, "UTF-8") > 14 ? mb_substr ($rmTodoFuken, 14, mb_strlen($rmTodoFuken, "UTF-8"), "UTF-8") : "");


        $personal_name_sei = "個人お名前姓";
        $personal_name_mei = "個人お名前名";
        $tel = "00000000000";
        // sgmoving_system@sagawa-mov.co.jp
        $mail = "sgmoving_system@sagawa-mov.co.jp";
        // if($inForm["eventsub_sel"] == "302" && $inForm["bpn_type"] == "2" && $inForm["shohin_pattern"] == "2"){
        //     $personal_name_sei = $inForm['comiket_personal_name_sei'];
        //     $personal_name_mei = $inForm['comiket_personal_name_mei'];
        //     $tel = $inForm['comiket_tel'];
        //     $mail = $inForm['comiket_mail'];
        // }

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
            "personal_name_sei" => $personal_name_sei,
            "personal_name_mei" => $personal_name_mei,
            "zip" => $eventsubData['zip'],
            "pref_id" =>  substr($eventsubData['jis5cd'], 0, 2),
            "address" => $address,
            "building" => $banchi,
            "tel" => $tel,
            "mail" => $mail,
            "booth_name" => "*",
            "building_name" => null,
            "booth_position" => null,
            "booth_num" => null,
            "staff_sei" => "S",
            "staff_mei" => "G",
            "staff_sei_furi" => "セイ",
            "staff_mei_furi" => "メイ",
            "staff_tel" => @empty($inForm['comiket_staff_tel']) ? "00000000000" : $inForm['comiket_staff_tel'],
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
            "customer_kbn" => '1',
            "bpn_type" => '2',
            "list_ptrn" => @$inForm['shohin_pattern']
        );

        return $data;
    }

    
    /**
     * 顧客コード取得
     * @param integer $eventSel
     * @return string
     */
    private function getCustomerCd($eventSel) {
        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();
        
        $eventInfo = $this->_EventService->fetchEventInfoByEventId($db, $eventSel);

        return $eventInfo['customer_cd'];
    }


    /**
     * comiketテーブルに登録するデータを整形する
     * @return type
     */
    public function _createComiketInsertDataByInForm($inForm, $id) {
        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        $batch_status = '1';

        $customerCd = $inForm['comiket_customer_cd'];
        //$merchantResult = @$inForm['merchant_result'];
        if($inForm['comiket_div'] == self::COMIKET_DEV_BUSINESS) { // 法人
            $inForm['comiket_personal_name_sei'] = "";
            $inForm['comiket_personal_name_mei'] = "";

            $inForm['comiket_payment_method_cd_sel'] = "5";  // 念のため
            $inForm['comiket_convenience_store_cd_sel'] = NULL;
            $inForm['payment_order_id'] = NULL;
            $inForm['authorization_cd'] = NULL;

        } else { // 個人
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
            $comiketInfo['customer_cd'] = substr($customerCd, 0, 11);

            return $comiketInfo;
        }

        $buildingNameRes = $this->_BuildingService->fetchBuildingNameByCd($db, $inForm['building_name_sel'], $inForm['eventsub_sel']);
        $buildingInfo = $this->_BuildingService->fetchBuildingById($db, $inForm['building_booth_position_sel']);

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
            "building_name" => @$buildingNameRes['name'],
            "booth_position" => empty($buildingInfo) ? "" : $buildingInfo['booth_position'],
            "booth_num" => @@sprintf('%02s', $inForm['comiket_booth_num']),// $inForm['comiket_booth_num'],
            "staff_sei" => @empty($inForm['comiket_personal_name_sei']) ? "　" : $inForm['comiket_personal_name_sei'],
            "staff_mei" => @empty($inForm['comiket_personal_name_mei']) ? "　" : $inForm['comiket_personal_name_mei'],
            "staff_sei_furi" => @empty($inForm['comiket_staff_sei_furi']) ? "-" : $inForm['comiket_staff_sei_furi'],
            "staff_mei_furi" => @empty($inForm['comiket_staff_mei_furi']) ? "-" : $inForm['comiket_staff_mei_furi'],
            "staff_tel" => @empty($inForm['comiket_staff_tel']) ? "00000000000" : $inForm['comiket_staff_tel'],
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
            "del_flg" => '0',
            "customer_kbn" => '1',
            "bpn_type" => '1',
            "list_ptrn" => @$inForm['shohin_pattern']
        );

        return $data;
    }

    /**
     * 物販物に数量が入力されているかチェックする
     * @return type
     */
    public function checkBuppanRecCount($inForm){
        $isBuppan = false;
        if (@!empty($inForm['comiket_box_buppan_num_ary'])) {
            foreach ($inForm['comiket_box_buppan_num_ary'] as $val) {
                // 物販が枚数入力されているかチェック
                if (@!empty($val)) {
                    $isBuppan = true;
                    break;
                }
            }
        }

        return $isBuppan;
    }

    /**
     * comiket_detailテーブルに登録するデータを整形する
     * @return type
     */
    public function _createComiketDetailInsertDataByInForm($inForm, $id) {
        $returnList = array();

        $customerCd = "";
        if(!empty($id)) {
            $customerCd = sprintf("%07d", $id);
        }

        // 物販
        if(isset($inForm['comiket_box_buppan_num_ary']) &&  count($inForm['comiket_box_buppan_num_ary']) > 0){

            $collect_delivery_date = $inForm['comiket_detail_collect_date_year_sel']."/".$inForm['comiket_detail_collect_date_month_sel']."/".$inForm['comiket_detail_collect_date_day_sel'];
            if(empty($inForm['comiket_detail_collect_date_year_sel'])
                    || empty($inForm['comiket_detail_collect_date_month_sel'])
                    || empty($inForm['comiket_detail_collect_date_day_sel'])) {
                $collect_delivery_date = "9999/12/31";
            }

            $collectStTime = null;
            $collectEdTime = null;
            //$deliveryDate ="9999-12-31";
            $deliveryStTime =null;
            $timezoneCd ="";
            $timezoneNm = null;
            $note = "";
            $deliveryEdTime =null;

            $data = array(
                "comiket_id" => $id,
                "type" => "5",
                "cd" => "bp{$customerCd}2",
                "name" => $inForm["comiket_personal_name_sei"].$inForm["comiket_personal_name_mei"],

                "hatsu_jis5code" => "00000",
                "hatsu_shop_check_code" => "0000",
                "hatsu_shop_check_code_eda" => "00",
                "hatsu_shop_code" => "000",
                "hatsu_shop_local_code" => "000",

                "chaku_jis5code" => "00000",
                "chaku_shop_check_code" =>"0000",
                "chaku_shop_check_code_eda" => "00",
                "chaku_shop_code" => "000",
                "chaku_shop_local_code" => "000",

                "zip" => $inForm["comiket_zip1"].$inForm["comiket_zip2"],
                "pref_id" => $inForm["comiket_pref_cd_sel"],
                "address" => $inForm["comiket_address"],
                "building" => $inForm["comiket_building"],
                "tel" => $inForm["comiket_tel"],

                "collect_date" => $collect_delivery_date,
                "collect_st_time" => $collectStTime,
                "collect_ed_time" => $collectEdTime,

                "delivery_date" => $collect_delivery_date,
                "delivery_st_time" => $deliveryStTime,
                "delivery_ed_time" => $deliveryEdTime,

                "service" => 6, // 物販
                "note" => $note,
                "fare" => "0", // ?
                "fare_tax" => "0", // ?
                "cost" => "0", // ?
                "cost_tax" => "0", // ?
                "delivery_timezone_cd" => $timezoneCd,
                "delivery_timezone_name" => $timezoneNm,
                "binshu_kbn" => '0',
                "toiawase_no" => @$inForm['comiket_toiawase_no'],
                "toiawase_no_niugoki" => @$inForm['comiket_toiawase_no_niugoki']
            );

                $returnList[] = $data;
            }

        return $returnList;
    }


    /**
     * 当日物販画面用
     * 
     * @param array inForm
     * @param integer comiketId
     *
     * @return array
     *
     */
    public function _createComiketDetailInsertDataForActiveShohin($inForm, $id) {
        $returnList = array();

        $customerCd = "";
        if(!empty($id)) {
            $customerCd = sprintf("%07d", $id);
        }

        $db = Sgmov_Component_DB::getPublic();

        // 物販
        if(isset($inForm['comiket_box_buppan_num_ary']) &&  count($inForm['comiket_box_buppan_num_ary']) > 0){

            $eventsubData = $this->_EventsubService->fetchEventsubByEventsubId($db, $inForm['eventsub_sel']);

            // 都道府県
            $rmTodoFuken = mb_substr ($eventsubData["address"], 3, mb_strlen($eventsubData["address"], "UTF-8"), "UTF-8");

            // イベントサブ住所
            $address = mb_substr ($rmTodoFuken, 0, 14, "UTF-8");

            // イベントサブ番地
            $banchi = (mb_strlen($rmTodoFuken, "UTF-8") > 14 ? mb_substr ($rmTodoFuken, 14, mb_strlen($rmTodoFuken, "UTF-8"), "UTF-8") : "");

            $collect_delivery_date = $inForm['comiket_detail_collect_date_year_sel']."/".$inForm['comiket_detail_collect_date_month_sel']."/".$inForm['comiket_detail_collect_date_day_sel'];
            if(empty($inForm['comiket_detail_collect_date_year_sel'])
                    || empty($inForm['comiket_detail_collect_date_month_sel'])
                    || empty($inForm['comiket_detail_collect_date_day_sel'])) {
                    $collect_delivery_date = "9999/12/31";
            }

            $data = array(
                "comiket_id" => $id,
                "type" => "5",
                "cd" => "bp{$customerCd}2",
                "name" => "申込者",

                "hatsu_jis5code" => "00000",
                "hatsu_shop_check_code" => "0000",
                "hatsu_shop_check_code_eda" => "00",
                "hatsu_shop_code" => "000",
                "hatsu_shop_local_code" => "000",

                "chaku_jis5code" => "00000",
                "chaku_shop_check_code" =>"0000",
                "chaku_shop_check_code_eda" => "00",
                "chaku_shop_code" => "000",
                "chaku_shop_local_code" => "000",

                "zip" => $eventsubData["zip"],
                "pref_id" => substr($eventsubData['jis5cd'], 0, 2),
                "address" => $address,
                "building" => $banchi,
                "tel" => "0000000000",

                "collect_date" => $collect_delivery_date,
                "collect_st_time" => null,
                "collect_ed_time" => null,

                "delivery_date" => $collect_delivery_date,
                "delivery_st_time" => null,
                "delivery_ed_time" => null,

                "service" => 6, // 物販
                "note" => "",
                "fare" => "0", // ?
                "fare_tax" => "0", // ?
                "cost" => "0", // ?
                "cost_tax" => "0", // ?
                "delivery_timezone_cd" => "",
                "delivery_timezone_name" => null,
                "binshu_kbn" => '0',
                "toiawase_no" => @$inForm['comiket_toiawase_no'],
                "toiawase_no_niugoki" => @$inForm['comiket_toiawase_no_niugoki']

            );

            $returnList[] = $data;
        }

        return $returnList;
    }

    public function _createComiketBoxInsertDataByInForm($inForm, $id) {

        $returnList = array();

        // 商品
        foreach($inForm['comiket_box_buppan_num_ary'] as $key => $val) {
            if(empty($val)) {
                continue;
            }
       
            $data = array(
                "comiket_id" => $id,
                "type" => "5", // 物販
                "box_id" => $key,
                "num" => "$val",
                "fare_price" => "0", 
                "fare_amount" => "0", 
                "fare_price_tax" => "0", 
                "fare_amount_tax" => "0", 
                "cost_price" => "0", 
                "cost_amount" => "0", 
                "cost_price_tax" => "0", 
                "cost_amount_tax" => "0", 
                "ziko_shohin_cd" => @$inForm["comiket_box_buppan_ziko_shohin_cd_ary"][$key], 
            );
            $returnList[] = $data;
        }

        return $returnList;
    }

    /**
     * チェックデジットを算出する
     * @return type
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

    /**
     * チェックデジットを算出する(セブンチェック：7DR)
     * @return type
     */
    public static function getChkD2($param) {

        $target = intval($param);
        $result = $target % 7;
        return $result;
    }

    /**
     * メールテンプレートを取得する
     * @return type
     */
    private function getIndividualMailTemplate($comiket, $tmplateType, $screen){
        //メールテンプレート(申込者用)
        $mailTemplate = array();

        // フッター用メールテンプレート
        $footerTmpName = $tmplateType;
        if (@empty($footerTmpName)) {
            $footerTmpName = "_complete";
        }

        if($comiket['div'] == self::COMIKET_DEV_BUSINESS){
            //法人用メールテンプレート
            $mailTemplate[] = "/bpn{$screen}_complete_business{$tmplateType}.txt";

            // 物販用メールテンプレート
            $mailTemplate[] = '/bpn_parts_complete_choice_3.txt';

            $mailTemplate[] = "/bpn_parts{$footerTmpName}_footer_type_1.txt";
        }else{
            //個人用メールテンプレート
            $mailTemplate[] = "/bpn{$screen}_complete_individual{$tmplateType}.txt";

            // 物販用メールテンプレート
            $mailTemplate[] = '/bpn_parts_complete_choice_3.txt';

            $mailTemplate[] = "/bpn_parts{$footerTmpName}_footer_type_2.txt";
        }

        return $mailTemplate;
    }

    /**
     * SGMV営業所用メールテンプレートを取得する
     * @return type
     */
    private function getSGMVMailTemplate($comiket, $tmplateType, $screen){
        //メールテンプレート(SGMV営業所用)
        $mailTemplateSgmv = array();

        // フッター用メールテンプレート
        $footerTmpName = $tmplateType;
        if (@empty($footerTmpName)) {
            $footerTmpName = "_complete";
        }

        if($comiket['div'] == self::COMIKET_DEV_BUSINESS){
            // 営業所で使用するメールテンプレートをわけ別々に送信する
            $mailTemplateSgmv[] = "/bpn{$screen}_complete_business_sgmv{$tmplateType}.txt";

            // 営業用テンプレート
            $mailTemplateSgmv[] = '/bpn_parts_complete_choice_3_sgmv.txt';

            //営業所で使用するメールテンプレートをわけ別々に送信する
            $mailTemplateSgmv[] = "/bpn_parts{$footerTmpName}_footer_type_1.txt";
        }else{
            // 営業所で使用するメールテンプレートをわけ別々に送信する
            $mailTemplateSgmv[] = "/bpn{$screen}_complete_individual_sgmv{$tmplateType}.txt";
            if($tmplateType == "_sgmv_cancel"){
                $mailTemplateSgmv[] = "/bpn{$screen}_complete_individual{$tmplateType}.txt";
            }

            // 営業用テンプレート
            $mailTemplateSgmv[] = '/bpn_parts_complete_choice_3_sgmv.txt';

            // 営業所で使用するメールテンプレートをわけ別々に送信する
            $mailTemplateSgmv[] = "/bpn_parts{$footerTmpName}_footer_type_2.txt";
        }


        return $mailTemplateSgmv;
    }


    /**
     * 完了メールを送信する
     * @return type
     */
    private function sendBuppanCompleteMail($comiket, $mailTemplate, $mailTemplateSgmv, $tmplateType, $data, $sendTo2, $sendCc){
        try {
            // QRコードを添付する。
            $isAttachment = true;
            if($tmplateType == "_sgmv_cancel" || $tmplateType == "_cancel"){
                $isAttachment = false;                
            }

            if(!@empty($tmplateType)){
                $tmplateType = '_' . $tmplateType;
            }

            $sendTo = $sendTo2;
            if(empty($sendTo2)) {
                $sendTo = $comiket['mail'];
            }

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
    }


    /**
     * 完了メール送信
     * @param array $comiket
     * @param string $sendTo2
     * @param string $sendCc
     * @param string $tmplateType
     * @param string $fromTojitsuBuppan
     * @return bool true:成功
     */
    public function sendCompleteMailForActiveShohin($comiket, $sendTo2 = '', $sendCc = '', $tmplateType = '', $fromTojitsuBuppan = ""){
            // 物販用メールテンプレート
            $mailTemplate =  $this->getIndividualMailTemplate($comiket, $tmplateType, "_tojitsu");

            // 営業用テンプレート
            $mailTemplateSgmv = $this->getSGMVMailTemplate($comiket, $tmplateType, "_tojitsu");

            $data = $this->setMailData($comiket);

            $this->sendBuppanCompleteMail($comiket, $mailTemplate, $mailTemplateSgmv, $tmplateType, $data, $sendTo2, $sendCc);

        return true;
    }

    /**
     * メールテンプレートの置換
     * @return type
     */
    private function setMailData($comiket){

        // テンプレートデータ
        $data = array();

        /////////////////////////////////////////////////////////////////////////////////////////////
        // 管理者用のために、コンビニ前払い-未払いの場合にメール内容に文言を追加する
        /////////////////////////////////////////////////////////////////////////////////////////////
        $payMethodList = array('1' => 'コンビニ前払い', '2' => 'クレジット', '3' => '電子マネー', '4' => 'コンビニ後払い', '5' => '法人売掛',);
        if (@$comiket['payment_method_cd'] == '1') { // コンビニ前払い
            if (@empty($comiket['receipted'])) {
                $data['conveni_prepay_status'] = 'コンビニ前払い:未';
            } else {
                $data['conveni_prepay_status'] = 'コンビニ前払い:済';
            }
        } else {
            if (@empty($payMethodList[$comiket['payment_method_cd']])) {
                $data['conveni_prepay_status'] = "";
            } else {
                $data['conveni_prepay_status'] = $payMethodList[$comiket['payment_method_cd']];
            }
        }

        $data["header"] = "卓上飛沫ブロッカー";
        if($comiket["list_ptrn"] == "2"){
            $data["header"] = "梱包資材";
        }

        // if($comiket["list_ptrn"] == "2"){
        //     $data["header"] = "当日販売";
        // }elseif($comiket["bpn_type"] == "2" && $comiket["list_ptrn"] == "1"){
        //     $data["header"] = "卓上飛沫ブロッカー";
        // }

        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        // イベント情報
        $eventData = $this->_EventService->fetchEventById($db, $comiket['event_id']);

        // イベントサブ情報
        $eventsubData = $this->_EventsubService->fetchEventsubByEventsubId($db, $comiket['eventsub_id']);

        $week = ['日', '月', '火', '水', '木', '金', '土'];

        $frDay = date('w',strtotime($eventsubData["term_fr"]));
        $toDay = date('w',strtotime($eventsubData["term_to"]));

        $termFr = new DateTime($eventsubData["term_fr"]);
        $termTo = new DateTime($eventsubData["term_to"]);
        $termFrName = $termFr->format('Y年m月d日');
        $termToName = $termTo->format('Y年m月d日');

        $comiketPrefData = $this->_PrefectureService->fetchPrefecturesById($db, $comiket['pref_id']);

        // 【ブース名】
        $data['comiket_booth_name'] = '';
        if ($eventsubData['booth_display'] !== '0') {
            $data['comiket_booth_name'] = PHP_EOL . '【ブース名】' . $comiket['booth_name'];
        }

        // 【ブースNO】
        $data['comiket_building_name'] = '';
        if ($eventsubData['building_display'] !== '0') {
            $building = "";
            if($comiket['building_name'] !== "その他"){
                $building = $comiket['building_name']. "ホール ";
            }
            $data['comiket_building_name'] = PHP_EOL . '【ブースNO】' . $building . $comiket['booth_position'] . " " . $comiket['booth_num'];
        }
        
        if ($comiket['div'] == self::COMIKET_DEV_BUSINESS) {
            $data['surname'] = $comiket['office_name'];
            $data['forename'] = "";
            $data['comiket_div'] = $this->comiket_div_lbls[intval($comiket['div'])];
            $data['comiket_customer_cd'] = @substr($comiket['customer_cd'], 0, 11);
            $data['comiket_office_name'] = $comiket['office_name'];
            $data['comiket_payment_method'] = "売掛";
        } else {
            $data['surname'] = $comiket['personal_name_sei'];
            $data['forename'] = $comiket['personal_name_mei'];
         
            $data['comiket_div'] = '出展者'; // デザインフェスタ
            if($eventData['id'] === '2') { // コミケ
                $data['comiket_div'] = '電子決済の方(クレジット、コンビニ決済、電子マネー)';
            }

            $data['comiket_personal_name_seimei'] = $comiket['personal_name_sei'] . " " . $comiket['personal_name_mei'];
            

            // バシ　2020/09/30
            // 今回は、コンビニ前払は使わない。メールに金額は改行されるのでattention_messageを削除しました。

            //$data['convenience_store'] = "";
            // $attention_message = '';
            // if($comiket['payment_method_cd'] == '1') { // お支払い方法 = コンビニ前払
            //     $convenienceStoreLbls = $this->convenience_store_lbls;
            //     $convenienceStoreCd = intval($comiket['convenience_store_cd']);
            //     $data['convenience_store'] = " （" . $convenienceStoreLbls[$convenienceStoreCd] . "）"
            //             . PHP_EOL . "【受付番号】{$comiket['receipt_cd']}";

            //     //払込票URL
            //     if(!empty($comiket['payment_url'])) {
            //         $data['convenience_store'] .= PHP_EOL . "【払込票URL】{$comiket['payment_url']}";
            //     }
            // }
            // $data['attention_message'] = $attention_message;

            // $data['convenience_store_late'] = "";
            // if($comiket['payment_method_cd'] == '4') { // お支払い方法 = コンビニ後払
            //     $data['convenience_store_late'] =
            //             "【ご購入店受注番号】{$comiket['sgf_res_shopOrderId']}"
            //     . PHP_EOL . "【お問合せ番号】{$comiket['sgf_res_transactionId']}";
            // }

            $paymentMethodLbls = $this->payment_method_lbls;
            
            // バシ　2020/09/30
            // 今回は、コンビニ前払は使わない。メールに金額は改行されるのでattention_messageを削除しました。
            //$data['comiket_payment_method'] = $paymentMethodLbls[$comiket['payment_method_cd']] . $data['convenience_store'];
            
            $data['comiket_payment_method'] = $paymentMethodLbls[$comiket['payment_method_cd']];
            $data['digital_money_attention_note'] = "";
        }

        $data["moushikominame"] = "卓上飛沫ブロッカー";
        if($comiket["bpn_type"] == "2" && $comiket["list_ptrn"] == "2"){
            $data['moushikominame'] = "梱包資材";
        }elseif($comiket["bpn_type"] == "1"){
            $data['moushikominame'] = $comiket['personal_name_sei']." ".$comiket['personal_name_mei']."様";
        }

        $data['comiket_id'] = sprintf('%010d', $comiket['id']); //【コミケID】
        // $toiawase_no = @$comiket['toiawase_no'];
        // if(@empty($toiawase_no)){
        //     $toiawase_no = @$comiket["comiket_detail_list"][0]['toiawase_no'];
        // }
        // $data['toiawase_no'] = $toiawase_no; //【問合せ番号】
        $data['event_name'] = $eventData["name"] . " " . $eventsubData["name"];//【出展イベント】
        $data['place_name'] = $eventsubData["venue"];//【場所】

        // 【期間】
        $kikan = $termFrName ."(".$week[$frDay].")" . " ～ " . $termToName ."(".$week[$toDay].")";
        if($termFrName == $termToName){
            $kikan = $termFrName ."(".$week[$frDay].")";
        }

        if($comiket['eventsub_id'] == "303" && $comiket['bpn_type'] == "2" && $comiket['list_ptrn'] == "2"){
            $data['period_name'] = "【期間】".$kikan; //【期間】
        }else{
            $data['period_name'] = "【会期】".$kikan; //【期間】
        }

        $data['comiket_zip'] = "〒" . substr($comiket['zip'], 0, 3) . '-' . substr($comiket['zip'], 3);//【郵便番号】
        $data['comiket_pref_name'] = $comiketPrefData['name'];//【都道府県】
        $data['comiket_address'] = $comiket['address'];//【住所】
        $data['comiket_building'] = $comiket['building'];//【ビル】
        $data['comiket_tel'] = $comiket['tel'];//【電話番号】
        $data['comiket_mail'] = $comiket['mail'];//【メール】
        $data['comiket_staff_seimei'] = $comiket['staff_sei'] . " " . $comiket['staff_mei'];//【セイ】【メイ】
        $data['comiket_staff_seimei_furi'] = $comiket['staff_sei_furi'] . " " . $comiket['staff_mei_furi'];//【セイ】【メイ】フリ
        $data['comiket_staff_tel'] = $comiket['staff_tel'];//【担当電話番号】

        $data['personal_name_seimei'] = "";
        $data['mail'] = "";
        if($comiket['eventsub_id'] == "302" && $comiket['bpn_type'] == "2" && $comiket['list_ptrn'] == "2"){ //ゲームマーケットの梱包資材の場合、
            $data['personal_name_seimei'] = "【お申込者】".$comiket['personal_name_sei']." ".$comiket['personal_name_mei'];//【お申込者】
            $data['mail'] = " 【メールアドレス】".$comiket['mail'];//【メールアドレス】
        }

        //$data['toiawase_no'] = @$comiket['toiawase_no'];//【問合せ番号】
        $comiket_detail_list = $comiket['comiket_detail_list'];
        foreach ($comiket_detail_list as $comiket_detail) {
            //サービスごとの数量表示
            $num_area = '';
            $boxName = "";
            $comiket_box_list = (isset($comiket_detail['comiket_box_list'])) ? $comiket_detail['comiket_box_list'] : array();
            foreach ($comiket_box_list as $comiket_box) {
                $boxInfo = $this->_ShohinService->fetchShohinById($db, $comiket_box['box_id']);
                if(!empty($boxInfo)){
                    $boxName = $boxInfo['name'];
                }

                $boxName = @strip_tags($boxName);
                $num_area .= '    ' .  @trim($boxName) . ' [' . number_format($comiket_box['num']) . ' 枚]' . PHP_EOL;
            }
           
            // 商品と数量
            $data['type3_num_area'] = $num_area;

            $day = "";
            if(empty($comiket_detail['collect_date']) || $comiket_detail['collect_date'] == "9999/12/31" || $comiket_detail['collect_date'] == "9999-12-31") {
                $collectDateName = "";
            } else {
                $collectDate = new DateTime($comiket_detail['collect_date']);
                $collectDateName = $collectDate->format('Y年m月d日');

                $day = $this->_getWeek($collectDate->format('Y'), $collectDate->format('m'), $collectDate->format('d'));
            }

            $data['toiawase_no'] = @$comiket_detail['toiawase_no_niugoki'];//【問合せ番号】
        }

        $data['collect_date'] = "";
        $data['collect_st_time'] = "";
        $data['collect_ed_time'] = "";
        if(!empty($collectDateName)){
            $data['collect_date'] = "【商品引き渡し日】".$collectDateName."(".$day.")"; //【お預かり日時】
        }

        // 金額（税抜）
        $data['comiket_amount'] = '\\' . number_format($comiket['amount']);
        // 金額（税込）
        $data['comiket_amount_tax'] = '\\' . number_format($comiket['amount_tax']);

        $urlPublicSsl = Sgmov_Component_Config::getUrlPublicSsl();
        $comiketIdCheckD = self::getChkD(sprintf("%010d", $comiket['id']));

        // 変更 
        $data['edit_url'] = $urlPublicSsl . "/bpn/input/" . sprintf('%010d', $comiket["id"]) . $comiketIdCheckD;

        // 説明書URL
        // $data['manual_url'] = $urlPublicSsl . "/bpn/pdf/manual/manual_{$comiket["eventsub_id"]}.pdf";
        $data['manual_url'] = '';
        $data['paste_tag_url'] = "";
        // if($eventData['id'] != '2') { // コミケは記載しない
        // if($eventData['id'] != '2' && $eventData['id'] !== '4') { // コミケとGoOutCampは記載しない

        // VASI TODO
        // if($eventsubData['manual_display'] == '1') { // コミケとGoOutCampは記載しない
        //     $data['manual_url'] = "【説明書URL】" . PHP_EOL . $urlPublicSsl . "/bpn/pdf/manual/{$eventData['name']}{$eventsubData['name']}.pdf";
        // }
        // $data['manual_url'] = $urlPublicSsl . "/bpn/pdf/manual/{$eventData['name']}{$eventsubData['name']}.pdf";

        // バシ　2020/09/30
        // 今回は、コンビニ前払は使わない。メールに金額は改行されるのでattention_messageを削除しました。

//         $data['explanation_url_convenience_payment_method'] = "";
//         if($comiket['payment_method_cd'] == '1') { // お支払い方法 = コンビニ前払
//                 $data['explanation_url_convenience_payment_method'] =
// "
// 以下の内容でお支払いください。
// ・セブンイレブン
// 　受付番号をメモまたは払込票を印刷し、
// セブンイレブンのレジカウンターにてお支払い手続きをお願いいたいます。

// ・ローソン
// ・セイコーマート
// ・ファミリーマート
// ・ミニストップ
// 　受付番号を控えて、コンビニ備え付けの端末でお支払い手続きをお願いいたします。
// 端末操作方法は、各コンビニエンスストアのホームページにてご確認ください。

// ・デイリーヤマザキ
// 　受付番号をメモまたは払込票を印刷し、
// デイリーヤマザキ店頭レジにてお支払い手続きをお願いいたいます。
// ";
//         }
        
        ////////////////////////////////////////////////////////////////////////////////////////////
        // サイズ変更/キャンセルURL
        ////////////////////////////////////////////////////////////////////////////////////////////
        //$data['cancel_url'] = $urlPublicSsl . "/bpn/cancel/" . sprintf('%010d', $comiket["id"]) . $comiketIdCheckD;
        $data['cancel_url'] = "キャンセルしたい場合は
下記のＵＲＬから登録して下さい".PHP_EOL.$urlPublicSsl . "/bpn/cancel/" . sprintf('%010d', $comiket["id"]) . $comiketIdCheckD;


        if($comiket["bpn_type"] == "2" && $comiket["list_ptrn"] == "2"){
            $data['cancel_url'] = "";
        }


        //$data['size_change_url'] = $urlPublicSsl . "/bpn/size_change/" . sprintf('%010d', $comiket["id"]) . $comiketIdCheckD;

        return $data;
    }

    /**
     * 完了メール送信
     * @param array $comiket
     * @param string $sendTo2
     * @param string $sendCc
     * @param string $tmplateType
     * @param string $fromTojitsuBuppan
     * @return bool true:成功
     */
    public function sendCompleteMail($comiket, $sendTo2 = '', $sendCc = '', $tmplateType = '', $fromTojitsuBuppan = "") {
            $data = $this->setMailData($comiket);

            // 物販用メールテンプレート
            $mailTemplate =  $this->getIndividualMailTemplate($comiket, $tmplateType, "");

            // 営業用テンプレート
            $mailTemplateSgmv = $this->getSGMVMailTemplate($comiket, $tmplateType, "");

            $this->sendBuppanCompleteMail($comiket, $mailTemplate, $mailTemplateSgmv, $tmplateType, $data, $sendTo2, $sendCc);

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
     * キャンセル処理
     * @param string $wsProtocol
     * @param string $wsHost
     * @param string $wsPath
     * @param string $wsPort
     * @param string $param comiket_id
     * @param string $paramOrg comiket_id + チェックデジット(モジュラス)
     */
    protected function execWebApiCancelComiket($wsProtocol, $wsHost, $wsPath, $wsPort, $param, $paramOrg , $dispErrTitle = "", $dispErrMsg = "") {
        
        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();
        
        $comiketInfo = $this->_ComiketService->fetchComiketById($db, $param);
        // コミケ明細申込データ
        $comiketDetailList = $this->_ComiketDetail->fetchComiketDetailByComiketId($db, $param);

        ////////////////////////////////////////////////////////////////////////////////////////////
        // ▼ 業務連携用リクエストデータ作成
        ////////////////////////////////////////////////////////////////////////////////////////////
        
        $sendFileName = 'BPN_' . date ( 'YmdHis' ) . '.csv';
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
            $message = "物販: 業務連携に失敗しました。\n[bpn] {$clasName} : paramOrg = {$paramOrg} / param = {$param}\n\nDB comiket.del_flg（論理削除フラグ）は更新しています。\n\n";
            $message .= "Exceptionメッセージ: " . $e->getMessage() . "\n\n";
            $message .= "業務サーバ接続情報: _wsProtocol: {$wsProtocol} / _wsHost: {$wsHost} / _wsPort: {$wsPort} / _wsPath: {$wsPath}";
            // システム管理者メールアドレスを取得する。
            $mailTo = Sgmov_Component_Config::getLogMailTo ();
//            $mailData = $this->createMailData($comiketInfo);
//            $eventsubInfo = $this->_EventsubService->fetchEventsubByEventsubId($db, $comiketInfo['eventsub_id']);
            $mailData = $comiketInfo;
            $mailData['message'] = $message;
            $divType = "individual";
            if ($comiketInfo['div'] == self::COMIKET_DEV_BUSINESS) {
                $divType = "business";
            }
            $mailTemplateList = array(
                "/bpn_cancel_{$divType}_error.txt",
//                "/dsn_parts_cancel_footer_type_{$comiketDetailInfo['type']}.txt",
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

        // 業務連携成功
        return true;
    }
    
    /**
     * 配列から、商品名を漉す。
     * @param db 
     * @param array $shohinList 
     */
    protected function filterShohinResult($shohinList){
        $returnList = array();
        foreach ($shohinList as $value) {
            $returnList[$value['id']] = $value['name'];
        }
        return $returnList;
    }


    /**
     * 物販開催の有効期間チェック
     * @return type
     */
    public function checkShohinInTerm($db, $eventSubId){
        $shohinTerm = $this->_ShohinService->getTerm($db, $eventSubId);
        $eventsubInfo = $this->_EventsubService->getEventId($db, $eventSubId);
        if(!empty($shohinTerm)){

            // shohin.term_fr(申込開始)
            $mindate = strtotime($shohinTerm["min"]);
            // eventsub.arrival_to_time(復路申込期間終了)
            $maxdate = strtotime($eventsubInfo["arrival_to_time"]);
            // $currentDate = strtotime(date("Y-m-d"));
            $currentDateWithTime = strtotime(date("Y-m-d H:i:s"));
            if($currentDateWithTime < $mindate || $currentDateWithTime > $maxdate){
                $title = urlencode("物販サービス申込受付期間外です");
                $eventName = $eventsubInfo['eventname']." ".$eventsubInfo['eventsubname'];
                $message = urlencode("「{$eventName}」のお申込期間は範囲外です。");
                Sgmov_Component_Redirect::redirectPublicSsl("/bpn/error?t={$title}&m={$message}");
                exit;
            }
        }
    }

    /**
     * 購入商品の入力チェック
     * @param db 
     * @param array inform
     *
     * @param array $shohinList 
     */
    public function checkValidShohin($db, $inForm){
        $originalChangeSuryo = false;
        $allExpiry = 0;
        foreach ($inForm["comiket_box_buppan_num_ary"] as $key => $value) {
            $checkResult = $this->_ShohinService->checkShohinTerm($db, $key);
            if(@!empty($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], "/bpn/size_change") !== false ){

                // コミケ申込宅配データ
                $comiketBoxList = $this->_ComiketBox->fetchComiketBoxDataListByIdAndType($db, $inForm["comiket_id"], "5");

                // 数量
                $getComiketNum = $this->filterComiketBoxnResult($comiketBoxList);

                if(isset($getComiketNum[$key])){
                    if(($value != $getComiketNum[$key]) && (empty($checkResult) || $checkResult["count"] == "0")){
                        $originalChangeSuryo = true;
                    }
                }
            }else{
                if(!empty($value) && (empty($checkResult) || $checkResult["count"] == "0")){
                    $originalChangeSuryo = true;
                }
            }

            if(empty($checkResult) || $checkResult["count"] == "0"){
                $allExpiry++;
            }
        }

        if(count($inForm["comiket_box_buppan_num_ary"]) == $allExpiry){
            $originalChangeSuryo = true;
        }

        if($originalChangeSuryo){
            if(@!empty($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], "/bpn/size_change") !== false ){
                    Sgmov_Component_Redirect::redirectPublicSsl("/bpn/size_change/");
            }else{
                Sgmov_Component_Redirect::redirectPublicSsl("/bpn/input2/");
            }
            exit;
        }
    }


    /**
     * コミケ申込宅配情報から、数量を取得する。
     *
     * @param array comiketBoxList 
     * @return array returnList 
     *
     **/
    protected function filterComiketBoxnResult($comiketBoxList){
        $returnList = array();
        foreach ($comiketBoxList as $value) {
            $returnList[$value['box_id']] = $value['num'];
        }

        return $returnList;
    }

     /**
     * コミケ申込宅配情報から、数量をチェックする
     *
     * @param array comiketBoxList 
     * @return array returnList 
     *
     **/
    public function checkBoxCount($db, $inForm, $sessionForm, $session){
        $errorForm = new Sgmov_Form_Error();
        foreach ($inForm["comiket_box_buppan_num_ary"] as $key => $value) {
            $dataSet = $this->_ShohinService->fetchShohin($db, $inForm["eventsub_sel"], $inForm["bpn_type"], $inForm["shohin_pattern"], $key);
            foreach ($dataSet as $resultVal) {
                $existsPlusCurrentCnt = $resultVal["count"] + $value;
                // max_shohin_count == 0
                // max_shohin_countは入力した数量より小さいの場合
                // 既に登録した商品数は、max_shohin_countより大きいの場合
                // 既に登録した商品数　＋　現在の数量は、max_shohin_countより超える場合、
                if(!empty($value) && ($resultVal["count"] > $resultVal["max_shohin_count"] || $resultVal["max_shohin_count"] == "0")){
                    $errorForm->addError('comiket_box_buppan_num_ary'."_".$key, "{$resultVal['name']}は完売しました。");
                }elseif($resultVal["max_shohin_count"] != 0 && $existsPlusCurrentCnt > $resultVal["max_shohin_count"]){
                    $errorForm->addError('comiket_box_buppan_num_ary'."_".$key, "{$resultVal['name']}は上限を超えています");
                }
            }
        }


        if(!empty($errorForm->_errors)){
            $sessionForm->error = $errorForm;
            $sessionForm->status = self::VALIDATION_FAILED;
            $session->saveForm(self::FEATURE_ID, $sessionForm);
            $redirectUrl = '/bpn/input/'.$inForm["eventsub_sel"]."/".$inForm["bpn_type"]."/".$inForm["shohin_pattern"];
            Sgmov_Component_Redirect::redirectPublicSsl($redirectUrl);
            exit;
        }
    }
}