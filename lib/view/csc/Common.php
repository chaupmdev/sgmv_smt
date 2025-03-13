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
    'CruiseRepeater', 'Prefecture', 'Yubin', 'SocketZipCodeDll', 'Event', 'Box', 'Cargo', 'Building', 'Charter'
    , 'Eventsub', 'AppCommon', 'HttpsZipCodeDll', 'BoxFare', 'CargoFare', 'Comiket'
    , 'EventsubCmb', 'Time', 'CenterMail', 'ComiketDetail', 'OutBoundCollectCal'
    , 'CostcoDelivery', 'CostcoShohin', 'CostcoOption', 'CostcoHaisokanoJis5', 'CostcoCustomerCd'
    , 'CostcoDataDisplay', 'Prefecture', 'CostcoDeliveryFukusukonpo', 'CostcoLeadTime'));
Sgmov_Lib::useView('Public');
/**#@-*/

//define("COMIKET_DEV_INDIVIDUA", 1); // 個人
//define("COMIKET_DEV_BUSINESS", 2); // 法人
/**
 * イベントサービスのお申し込みフォームの共通処理を管理する抽象クラスです。
 * @package    View
 * @subpackage CSC
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Csc_Common extends Sgmov_View_Public
{
    /**
     * 機能ID
     */
    const FEATURE_ID = 'CSC';

    /**
     * イベントID
     */
    const EVENT_ID = '5000';

    /**
     * DSN001の画面ID
     */
    const GAMEN_ID_DSN001 = 'DSN001';

    /**
     * DSN002の画面ID
     */
    const GAMEN_ID_DSN002 = 'DSN002';

    /**
     * DSN003の画面ID
     */
    const GAMEN_ID_DSN003 = 'DSN003';

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
     * 都道府県基本番号(リードタイム、配送料の基本値を取得する)
     */
    const COMMON_PREF_ID = '99';

    /**
     * 階段上げ作業の発生する商品サイズ
     */
    const KAIDANAGE_SIZE = '301.0';

    /**
     * 階段上げ作業のオプション種別
     */
    public $kaidanage_option_type = array('A', 'B');

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
        2 => '搬出（会場⇒お客様）',
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
     * 往路・出荷日範囲計算マスタサービス
     * @var Sgmov_Service_OutBoundCollectCal
     */
    protected $_OutBoundCollectCal;

    /**
     * 時間帯サービス
     * @var type
     */
    private $_TimeService;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $_CostcoCustomerCd;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $_CostcoDelivery;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $_CostcoShohin;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $_CostcoOption;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $_CostcoDataDisplayService;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $_CostcoHaisokanoJis5Service;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $_CostcoDeliveryFukusukonpo;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $_CostcoLeadTime;

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

        $this->_SocketZipCodeDll = new Sgmov_Service_SocketZipCodeDll();

        $this->_OutBoundCollectCal = new Sgmov_Service_OutBoundCollectCal();

        $this->_CostcoCustomerCd = new Sgmov_Service_CostcoCustomerCd();

        $this->_CostcoDelivery = new Sgmov_Service_CostcoDelivery();

        $this->_CostcoShohin = new Sgmov_Service_CostcoShohin();

        $this->_CostcoOption = new Sgmov_Service_CostcoOption();

        $this->_CostcoDataDisplayService = new Sgmov_Service_CostcoDataDisplay();

        $this->_CostcoHaisokanoJis5Service = new Sgmov_Service_CostcoHaisokanoJis5();

        $this->_CostcoDeliveryFukusukonpo = new Sgmov_Service_CostcoDeliveryFukusukonpo();

        $this->_CostcoLeadTime = new Sgmov_Service_CostcoLeadTime();

        $comiketCargoItemList = array();
        for ($i = 1; $i <= 99; $i++) {
            $comiketCargoItemList[$i] = $i;
        }

        $this->comiket_cargo_item_list = $comiketCargoItemList;
    }



    /**
     * 年月日から短縮表記の曜日を返す
     * @param int $year
     * @param int $month
     * @param int $day
     * @return str
     */
    public static function _getWeek($year, $month, $day)
    {
        $week = array("日", "月", "火", "水", "木", "金", "土");
        $resultWeek = $week[date('w', strtotime("{$year}-{$month}-{$day}"))];

        return $resultWeek;
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
     * 住所情報を取得します。
     * @param type $zip
     * @return type
     */
    public function _getAddressByZip($zip)
    {
        try {
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
     *ソケット通信でjis2,jis5取得
     *
     * @param type $inForm
     */
    public function setYubinDllInfoToInForm(&$inForm)
    {
        $db = Sgmov_Component_DB::getPublic();

        // イベントサブ情報取得(eventsub検索)
        $eventsubData = $this->_EventsubService->fetchEventsubIdAndSubid($db, $inForm["c_event_id"], $inForm["c_eventsub_id"]);
        // 搬出配送元情報を取得：取得したイベントサブ情報で郵便番号検索(ソケット通信)
        $resultEventZipDll = $this->_getAddress(@$eventsubData["zip"], @$eventsubData["address"]);

        // 搬出配送元 /////////////////////////////////////////////
        $inForm["inbound_hatsu_jis2code"] = @$resultEventZipDll["JIS2Code"];
        $inForm["inbound_hatsu_jis5code"] = @$resultEventZipDll["JIS5Code"];
        $inForm["inbound_hatsu_shop_check_code"] = @$resultEventZipDll["ShopCheckCode"];
        $inForm["inbound_hatsu_shop_check_code_eda"] = @$resultEventZipDll["ShopCheckCodeEda"];
        $inForm["inbound_hatsu_shop_code"] = @$resultEventZipDll["ShopCode"];
        $inForm["inbound_hatsu_shop_local_code"] = @$resultEventZipDll["ShopLocalCode"];


        $resultInboundPrefData = $this->_PrefectureService->fetchPrefecturesById($db, $inForm['d_pref_id']);

        // 搬出配送先情報を取得：入力した住所情報で郵便番号検索(ソケット通信)
        $resultInboundZipDll = $this->_getAddress(
            @$inForm['l_zip1'] . @$inForm['l_zip2'],
            @$resultInboundPrefData["name"] . @$inForm['d_address'] . @$inForm['d_building']
        );

        // 搬出配送先 /////////////////////////////////////////////
        $inForm["inbound_chaku_jis2code"] = @$resultInboundZipDll["JIS2Code"];
        $inForm["inbound_chaku_jis5code"] = @$resultInboundZipDll["JIS5Code"];
        $inForm["inbound_chaku_shop_check_code"] = @$resultInboundZipDll["ShopCheckCode"];
        $inForm["inbound_chaku_shop_check_code_eda"] = @$resultInboundZipDll["ShopCheckCodeEda"];
        $inForm["inbound_chaku_shop_code"] = @$resultInboundZipDll["ShopCode"];
        $inForm["inbound_chaku_shop_local_code"] = @$resultInboundZipDll["ShopLocalCode"];
    }

    /**
     * 0(ゼロ)文字チェック
     *
     * @param [type] $val
     * @return bool
     */
    protected function emptyNotZero($val) {
        if (@$val == '0') {
            return false;
        }
        return @empty($val);
    }

    /**
     * 送料計算
     * @param type $inForm
     */
    protected function calcEveryKindData($inForm, $comiketId = "", $isAmountDataFromSession = false)
    {
        $fareTaxTotal = 0;
        // DB接続
        $db = Sgmov_Component_DB::getPublic();

        // ソケット通信でjis5取得
        $this->setYubinDllInfoToInForm($inForm);

        // comiket、comiket_detail、comiket_boxの登録データを作成
        $tableDataInfo = $this->_cmbTableDataFromInForm($inForm, $comiketId);
        $tableTreeData = $tableDataInfo["treeData"];

        // 料金計算処理
        $tableTreeData['amount_tax'] = 0;
        $tableTreeData['amount'] = 0;
        $costTotal = 0;
        $fareTotalHaiso = 0;
        $fareTotalOption = 0;

        $procList = array(
            'tableTreeData' => $tableTreeData,
        );
        $shohinDataType = ''; // 6: 通常商品、7: D24
        $resultList = array();
        foreach ($procList as $keyTree => $valTree) {

            foreach ($valTree["comiket_detail_list"] as $keyDet => $valDet) {
                $costTotal = 0;
                $fareTotalHaiso = 0;
                $fareTotalOption = 0;
                $fareTotalKokyakuHaiso = 0;
                $fareTotalKokyakuOption = 0;

                if (isset($valDet["comiket_box_list"])) {
                    foreach ($valDet["comiket_box_list"] as $keyComiketBox => $valComiketBox) {
                        $kingakuTaxHaiso = 0;
                        $kingakuKokyakuTaxHaiso = 0;
                        $kingakuOption = 0;
                        $kingakuTaxOption = 0;
                        $kingakuKokyakuTaxOption = 0;

                        $shohinInfo = $this->_CostcoShohin->getInfo($db, $inForm['c_kanri_no']);
                        //2023/01/10 GiapLN imp ticket #SMT6-352
                        if (empty($shohinInfo)) {
                            Sgmov_Component_Log::err('商品情報を取得できない。コストコ_商品マスタのデータをご確認してください。');
                            throw new Exception('商品情報を取得できない。コストコ_商品マスタのデータをご確認してください。');
                        }
                        if ($valComiketBox['type'] == '6' || $valComiketBox['type'] == '7') { // 商品

                            // 転送料金を含む配送料を決定
                            // getShohinInfo()の配送料取得と同じ処理
                            $tmpDelivInfo = @$this->_CostcoDelivery->getInfoPlusTensoryo($db, $inForm['c_event_id'], $inForm['c_eventsub_id'], $shohinInfo['size']);
                            //2023/01/10 GiapLN imp ticket #SMT6-352
                            if (empty($tmpDelivInfo)) {
                                Sgmov_Component_Log::err('コストコ_配送料金マスタを取得できない。コストコ_配送料金マスタのデータをご確認してください。');
                                throw new Exception('コストコ_配送料金マスタを取得できない。コストコ_配送料金マスタのデータをご確認してください。');
                            }
                            // 都道府県コードが一致する場合
                            //2023/01/10 GiapLN imp ticket #SMT6-352
                            if (!empty($tmpDelivInfo)) {
                                foreach ($tmpDelivInfo as $v) {
                                    if ($v['delivery_pref_id'] == $inForm['d_pref_id']) {
                                        $delivInfo = $v;
                                        break;
                                    }
                                }
                            }
                            
                            // 都道府県コードが一致しない場合は99：self::COMMON_PREF_IDで決定
                            if (empty($delivInfo)) {
                                //2023/01/10 GiapLN imp ticket #SMT6-352
                                if (!empty($tmpDelivInfo)) {
                                    foreach ($tmpDelivInfo as $v) {
                                        if ($v['delivery_pref_id'] == self::COMMON_PREF_ID) {
                                            $delivInfo = $v;
                                            break;
                                        }
                                    }
                                }
                            }

                            // 税込み料金 DBの値が0の為、常に0
                            $kingakuTaxHaiso = isset($delivInfo['fare_tax']) ? intval($delivInfo['fare_tax']) : 0;
                            // 税込み顧客負担料金
                            $kingakuKokyakuTaxHaiso = isset($delivInfo['fare_tax_kokyaku']) ? intval($delivInfo['fare_tax_kokyaku']) : 0;

                            // 2022/01/25 追加
                            // 梱包数が複数の場合
                            if ($shohinInfo['konposu'] != '1') {
                                // 複数梱包時の料金を取得
                                $fukusukonpoInfo = @$this->_CostcoDeliveryFukusukonpo->getInfo($db, $inForm['c_event_id'], $inForm['c_eventsub_id'], $shohinInfo['konposu']);

                                //税込み複数梱包金額の合計を加算
                                if (isset($fukusukonpoInfo) && !empty($fukusukonpoInfo)) {
                                    $kingakuKokyakuTaxHaiso += $fukusukonpoInfo['fare_tax'] * ($shohinInfo['konposu'] - 1);
                                }
                            }
                            // 2022/01/25 追加
                            $shohinDataType = $valComiketBox['type'];
                        }

                        Sgmov_Component_Log::debug('############################ valComiketBox');
                        Sgmov_Component_Log::debug($valComiketBox);

                        if ($valComiketBox['type'] == '8') { // オプション

                            // 階段上げ作業
                            if ($valComiketBox['shohin_cd'] == 'A' || $valComiketBox['shohin_cd'] == 'B') {  // 階段
                                $optionInfoKaidan = $this->_CostcoOption->getInfoKaidan($db, $inForm['c_event_id'], $inForm['c_eventsub_id'], $inForm['l_kaidan_type']);
                                // 階段料金税抜き
                                //2023/01/10 GiapLN imp ticket #SMT6-352
                                $kingakuOption    = isset($optionInfoKaidan['fare']) ? intval($optionInfoKaidan['fare']) : 0;
                                // 階段料金税込み
                                $kingakuTaxOption = isset($optionInfoKaidan['fare_tax']) ? intval($optionInfoKaidan['fare_tax']) : 0;

                                // 階段上げ以外のオプション
                            } else {
                                // オプションサービス：3：無償オプション、1：有償オプション、2：オプションなし
                                //c_option_cd_typeが1の場合、2：オプションなし。c_option_cd_typeが2の場合、0：オプションなし
                                if (@!isset($inForm['c_option_cd']) || ($inForm['c_option_cd_type'] == '1' && $inForm['c_option_cd'] == '2') || ($inForm['c_option_cd_type'] == '2' && @$inForm['c_option_cd'] == '0')) {

                                    Sgmov_Component_Log::debug('############################ valComiketBox-OptionNoCharge');

                                    // 無償オプション または オプションなし は0円
                                    $kingakuTaxOption  = 0;
                                    // 2022/01/25 追加 コメントアウト
                                    //                                    if ($shohinInfo['konposu'] != '1') {  // 梱包数が２以上の場合
                                    //                                        // 複数梱包時の料金を取得
                                    //                                        $fukusukonpoInfo = @$this->_CostcoDeliveryFukusukonpo->getInfo($db, $inForm['c_event_id'], $inForm['c_eventsub_id'], $shohinInfo['konposu']);
                                    //                                        // 税込み複数梱包時金額
                                    //
                                    //                                        // オプション料金でなく配送料に足しこむ
                                    //                                        //$kingakuTaxOption = $fukusukonpoInfo['fare_tax']*($shohinInfo['konposu']-1);
                                    //                                        $kingakuKokyakuTaxHaiso += $fukusukonpoInfo['fare_tax']*($shohinInfo['konposu']-1);
                                    //                                    }
                                    // 2022/01/25 追加
                                    // 有償オプション
                                } else {
                                    Sgmov_Component_Log::info('############################ valComiketBox-OptionCharge');
                                    if ($inForm['c_option_cd_type'] == '1') {
                                        Sgmov_Component_Log::debug('############################ c_option_cd_type = 1');
                                        //3：無償オプション、1：有償オプション
                                        if ($inForm['c_option_cd'] == '3') {
                                            $yumusyouKbn = '0';//無償オプション
                                        } else {
                                            $yumusyouKbn = '1';//有償オプション
                                        }
                                        //選択したオプション内容より、オプションマスタを取得する。
                                        $optionInfo = $this->_CostcoOption->getInfoBySelectOption($db, $inForm['c_event_id'], $inForm['c_eventsub_id'], $shohinInfo['option_id'], $yumusyouKbn);
                                    } else if ($inForm['c_option_cd_type'] == '2'){
                                        Sgmov_Component_Log::info('############################ c_option_cd_type = 2');
                                        Sgmov_Component_Log::info('############################ c_option_cd = '.$inForm['c_option_cd']);
                                        $optionInfo = $this->_CostcoOption->getInfoOptionById($db, $inForm['c_option_cd']);
                                    }
                                    // 税抜きオプション金額
                                    $kingakuOption = intval(@empty($optionInfo['fare']) ? '0' : $optionInfo['fare']);
                                    // 税込みオプション金額
                                    $kingakuTaxOption = intval(@empty($optionInfo['fare_tax']) ? '0' : $optionInfo['fare_tax']);   
                                }
                            }
                        }

                        if (
                            @!$this->emptyNotZero($kingakuTaxHaiso)
                            || @!$this->emptyNotZero($kingakuKokyakuTaxHaiso)
                            || @!$this->emptyNotZero($kingakuTaxOption)
                            || @!$this->emptyNotZero($kingakuKokyakuTaxOption)
                        ) {
                            // 配送料金（税込）
                            $fareTotalHaiso += $kingakuTaxHaiso;
                            $fareTotalKokyakuHaiso += $kingakuKokyakuTaxHaiso;
                            $fareTotalOption += $kingakuTaxOption;
                            $fareTotalKokyakuOption += $kingakuKokyakuTaxOption;

                            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                            // 作業費項目にはオプションの金額
                            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                            $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["cost_price"]      = $kingakuOption;
                            $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["cost_amount"]     = $kingakuOption;
                            $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["cost_price_tax"]  = $kingakuTaxOption;
                            $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["cost_amount_tax"] = $kingakuTaxOption;

                            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                            // 送料：通常商品用（D24ではない）
                            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                            $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["fare_price"]  = 0;
                            $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["fare_amount"] = 0;


                            if ($valComiketBox['type'] == '6') { // 通常商品
                                $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["fare_price_tax"]  = $kingakuKokyakuTaxHaiso;
                                $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["fare_amount_tax"] = $kingakuKokyakuTaxHaiso;
                            } else { // D24
                                $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["fare_price_tax"]  = $fareTotalHaiso;
                                $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["fare_amount_tax"] = $fareTotalHaiso;
                            }

                            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                            // 送料：顧客全額負担商品用(D24)
                            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                            $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["fare_price_kokyaku"]  = 0;
                            $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["fare_amount_kokyaku"] = 0;

                            $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["fare_price_tax_kokyaku"]  = $kingakuKokyakuTaxHaiso;
                            $valTree["comiket_detail_list"][$keyDet]["comiket_box_list"][$keyComiketBox]["fare_amount_tax_kokyaku"] = $kingakuKokyakuTaxHaiso;
                        }
                    }

                    /////////////////////////////////////////////////////////
                    //// 料金計算(子) 【comiket_detail】
                    /////////////////////////////////////////////////////////
                    // TODO:税抜金額を計算している箇所をマスタから直接持ってきたい
                    if ($shohinDataType == '6') { // 6: 通常商品
                        $valTree["comiket_detail_list"][$keyDet]['fare'] = ceil((string)($fareTotalKokyakuHaiso / Sgmov_View_Csc_Common::CURRENT_TAX));
                        $valTree["comiket_detail_list"][$keyDet]['fare_tax'] = $fareTotalKokyakuHaiso;
                    } else { // 7: D24
                        $valTree["comiket_detail_list"][$keyDet]['fare'] = ceil((string)($fareTotalHaiso / Sgmov_View_Csc_Common::CURRENT_TAX));
                        $valTree["comiket_detail_list"][$keyDet]['fare_tax'] = $fareTotalHaiso;
                    }

                    $valTree["comiket_detail_list"][$keyDet]['cost'] = ceil((string)($costTotal / Sgmov_View_Csc_Common::CURRENT_TAX));
                    $valTree["comiket_detail_list"][$keyDet]['cost_tax'] = $costTotal;

                    // D24対象商品
                    $valTree["comiket_detail_list"][$keyDet]['fare_kokyaku'] = ceil((string)($fareTotalKokyakuHaiso / Sgmov_View_Csc_Common::CURRENT_TAX));
                    $valTree["comiket_detail_list"][$keyDet]['fare_tax_kokyaku'] = $fareTotalKokyakuHaiso;
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
            $detailAmountTaxTotalKokyaku = 0;
            foreach ($valTree["comiket_detail_list"] as $keyDet => $valDet) {
                /////////////////////////////////////////////////////////////////////////////////
                // 税抜計算
                /////////////////////////////////////////////////////////////////////////////////
                $detailAmountTotal += @empty($valDet['fare']) ? 0 : $valDet['fare'];
                $detailAmountTotal += @empty($valDet['cost']) ? 0 : $valDet['cost'];

                /////////////////////////////////////////////////////////////////////////////////
                // 税込計算（D24ではない）
                /////////////////////////////////////////////////////////////////////////////////
                $detailAmountTaxTotal += @empty($valDet['fare_tax']) ? 0 : $valDet['fare_tax'];
                $detailAmountTaxTotal += @empty($valDet['cost_tax']) ? 0 : $valDet['cost_tax'];

                /////////////////////////////////////////////////////////////////////////////////
                // 税込計算（D24）
                /////////////////////////////////////////////////////////////////////////////////
                $detailAmountTaxTotalKokyaku += @empty($valDet['fare_tax_kokyaku']) ? 0 : $valDet['fare_tax_kokyaku'];
            }
            $valTree['amount'] = $detailAmountTotal;
            $valTree['amount_tax'] = $detailAmountTaxTotal;
            $valTree['amount_tax_kokyaku'] = $detailAmountTaxTotalKokyaku;

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
     * 登録用の配列データを作成
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
     * comiket、comiket_detail、comiket_boxの登録データを作成
     *
     * @param type $inForm
     * @param type $comiketId
     * @return type
     */
    public function _cmbTableDataFromInForm($inForm, $comiketId = "")
    {
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // comiket 登録データ作成
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $comiketDataForHaiso = $this->_createComiketInsertDataByInForm($inForm, $comiketId);

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // comiket_detail 登録データ作成
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $comiketDetailDataList = $this->_createComiketDetailInsertDataByInForm($inForm, $comiketId);
        $comiketDetailDataListForHaiso = array();

        // 配送用
        foreach ($comiketDetailDataList as $key => $val) {
            $comiketDetailDataListForHaiso[] = $val;
        }

        $comiketDataForHaiso["comiket_detail_list"] = $comiketDetailDataListForHaiso;

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // comiket_box 登録データ作成
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
                // if ($comiketDataForHaiso["comiket_detail_list"][$key2]["type"] == $val["type"]) {
                $comiketDataForHaiso["comiket_detail_list"][$key2]["comiket_box_list"][$key] = $val;
                // }
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
                "comiketCargoDataList" => array(),
                "comiketCharterDataList" => array(),
            ),
        );
    }

    /**
     * comiketテーブルに登録データを作成
     *
     * @param [type] $inForm
     * @param [type] $id
     * @param string $type
     * @return array
     */
    public function _createComiketInsertDataByInForm($inForm, $id, $type = "")
    {
        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();
        $tmpCd = $this->_CostcoCustomerCd->getInfo($db, $inForm['c_event_id'], $inForm['c_eventsub_id']);
        //2023/01/10 GiapLN imp ticket #SMT6-352
        if (!isset($tmpCd['customer_cd'])) {
            Sgmov_Component_Log::err('顧客コードを取得できない。コストコ_顧客コードマスタのデータをご確認してください。');
            throw new Exception('顧客コードを取得できない。コストコ_顧客コードマスタのデータをご確認してください。');
        }
        $customerCd = $tmpCd['customer_cd'];

        $data = array(
            "id" => $id,
            "merchant_result" => '0',
            "merchant_datetime" => NULL,
            "receipted" => NULL,
            "send_result" => "0",
            "sent" => NULL,
            "batch_status" => '1', // バッチ処理状況 1:登録済, 2: 申込み者へメール送付済, 3:連携データ送信済, 4：完了（管理者メール済）
            "retry_count" => "0",
            "payment_method_cd" => "6", // お支払方法 1：コンビニ決済 2：クレジットカード、3：電子マネー、4：コンビニ後払い、5:法人売掛、6：支払いなし
            "convenience_store_cd" => 0,
            "receipt_cd" => NULL,
            "authorization_cd" => NULL,
            "payment_order_id" => NULL,
            "div" => '3', // 識別 1:個人、2:法人、3：設置
            "event_id" => $inForm['c_event_id'],
            "eventsub_id" => $inForm['c_eventsub_id'],
            "customer_cd" => $customerCd,
            "office_name" => "",
            "personal_name_sei" => $inForm['c_personal_name_sei'],
            "personal_name_mei" => $inForm['c_personal_name_mei'],
            "zip" => $inForm['l_zip1'] . $inForm['l_zip2'],
            "pref_id" => $inForm['d_pref_id'],
            "address" => $inForm['d_address'],
            "building" => $inForm['d_building'],
            "tel" => $inForm['c_tel'],
            "mail" => $inForm['c_mail'],
            "booth_name" => "-",
            "building_name" => "-",
            "booth_position" => "-",
            "booth_num" => "-",
            "staff_sei" => "　",
            "staff_mei" => "　",
            "staff_sei_furi" => "　",
            "staff_mei_furi" => "　",
            //GiapLN imp ticket #SMT6-385 2022/12/27
            "staff_tel" => $inForm['staff_tel'],//"99988887777",
            "choice" => "2", // 2：復路のみ
            "amount" => "0", // ?
            "amount_tax" => "0", // ?
            "create_ip" => $_SERVER["REMOTE_ADDR"],
            //            "created" => "",
            "modify_ip" => $_SERVER["REMOTE_ADDR"],
            //             "modified" => "",
            "transaction_id" => "-",
            "auto_authoriresult" => "-",
            "haraikomi_url" => "-",
            "kounyuten_no" => "-",
            "del_flg" => '0',
            "customer_kbn" => '1',
            "bpn_type" => '0',

            "amount_kokyaku" => "0",
            "amount_tax_kokyaku" => "0",
        );

        return $data;
    }

    /**
     * comiket_detailテーブルに登録データを作成
     *
     * @param [type] $inForm
     * @param [type] $id
     * @return array
     */
    public function _createComiketDetailInsertDataByInForm($inForm, $id)
    {
        $db = Sgmov_Component_DB::getPublic();

        // 顧客コードの取得
        $tmpCd = $this->_CostcoCustomerCd->getInfo($db, $inForm['c_event_id'], $inForm['c_eventsub_id']);
        //2023/01/10 GiapLN imp ticket #SMT6-352
        if (!isset($tmpCd['customer_cd'])) {
            Sgmov_Component_Log::err('顧客コードを取得できない。コストコ_顧客コードマスタのデータをご確認してください。');
            throw new Exception('顧客コードを取得できない。コストコ_顧客コードマスタのデータをご確認してください。');
        }
        $customerCd = $tmpCd['customer_cd'];

        // 顧客コードにコミケIDを左から7ケタ0で埋めセット
        // なぜ$customerCdにセットしなおしているのか意図不明
        if (!empty($id)) {
            $customerCd = sprintf("%07d", $id);
        }

        // 商品情報取得
        $shohinInfo = $this->_CostcoShohin->getInfo($db, $inForm['c_kanri_no']);

        if (@empty($shohinInfo)) {
            return array();
        }

        $sagyoJikanSum = 0;
        // オプション情報の取得
        //$optionInfo = @$this->_CostcoOption->getInfo($db, $inForm['c_event_id'], $inForm['c_eventsub_id'], $shohinInfo['option_id']);
        $sagyoJikanOption = 0; // オプションなし は 0
        //c_option_cd_typeが１とは重複オプションがない。
        //c_option_cd_typeが２とは重複オプションがある。
        if (@$inForm['c_option_cd_type'] == '1') {
            if (@$inForm['c_option_cd'] == '3') { // 無償オプション
                $optionInfo = @$this->_CostcoOption->getInfoBySelectOption($db, $inForm['c_event_id'], $inForm['c_eventsub_id'], $shohinInfo['option_id'], '0');
                $sagyoJikanOption = isset($optionInfo['sagyo_jikan']) ? $optionInfo['sagyo_jikan'] : 0;
            } else if (@$inForm['c_option_cd'] == '1') { // 有償オプション
                $optionInfo = @$this->_CostcoOption->getInfoBySelectOption($db, $inForm['c_event_id'], $inForm['c_eventsub_id'], $shohinInfo['option_id'], '1');
                $sagyoJikanOption = isset($optionInfo['sagyo_jikan']) ? $optionInfo['sagyo_jikan'] : 0;
            }   
        } else if (@$inForm['c_option_cd_type'] == '2' && @$inForm['c_option_cd'] != '0') {//オプションなしの以外時、作業時間を計算
            $optionInfo = @$this->_CostcoOption->getInfoOptionById($db, $inForm['c_option_cd']);
            $sagyoJikanOption = isset($optionInfo['sagyo_jikan']) ? $optionInfo['sagyo_jikan'] : 0;
        }

        $sagyoJikanSum += $sagyoJikanOption;

        // 階段上げ作業：作業あり / l_kaidan_type=A：外階段、l_kaidan_type=B：内階段
        if (@$inForm['c_kaidan_cd'] == '1') {
            $optionInfoKaidan = @$this->_CostcoOption->getInfoKaidan($db, $inForm['c_event_id'], $inForm['c_eventsub_id'], $inForm['l_kaidan_type']);
            if (@!empty($optionInfoKaidan)) {
                $sagyoJikanSum += $optionInfoKaidan['sagyo_jikan'];
            } 
        }

        $today = new DateTime();
        $todayFormat = $today->format("Y-m-d");
        $data = array(
            "comiket_id" => $id,
            "type" => "2",
            "cd" => "ev{$customerCd}2",
            "name" => $inForm['d_name'],

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

            "zip" => $inForm['l_zip1'] . $inForm['l_zip2'],
            "pref_id" => $inForm['d_pref_id'],
            "address" => $inForm['d_address'],
            "building" => $inForm['d_building'],
            //GiapLN imp ticket #SMT6-385 2022/12/27
            "tel" => $inForm['staff_tel'],//$inForm['c_tel'],

            "collect_date" => $todayFormat,
            "collect_st_time" => NULL,
            "collect_ed_time" => NULL,

            "delivery_date" => @empty($inForm['d_delivery_date_fmt']) ? NULL : $inForm['d_delivery_date_fmt'],
            "delivery_st_time" => NULL,
            "delivery_ed_time" => NULL,

            "service" => "7", // サービス選択 1：宅配便、2：カーゴ、3：貸切 ... 7: コストコ
            "note" => NULL,
            "fare" => "0", // ?
            "fare_tax" => "0", // ?
            "cost" => "0", // ?
            "cost_tax" => "0", // ?
            "delivery_timezone_cd" => NULL,
            "delivery_timezone_name" => NULL,
            "binshu_kbn" => '0',
            "toiawase_no" => @$inForm['comiket_toiawase_no'],
            "toiawase_no_niugoki" => @$inForm['comiket_toiawase_no_niugoki'],

            "fare_kokyaku" => "0",
            "fare_tax_kokyaku" => "0",
            "sagyo_jikan" => $sagyoJikanSum,
            "kokyaku_futan_flg" => (@$shohinInfo['data_type'] == '7' ? '2' : '1'), // '2' => D24、'1' => 非D24
        );

        return array($data);
    }

    /**
     * comiket_boxテーブルに登録データを作成
     *
     * @param [type] $inForm
     * @param [type] $id
     * @return array
     */
    public function _createComiketBoxInsertDataByInForm($inForm, $id)
    {

        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        $returnList = array();
        $dataArr = array();

        // 商品情報の取得
        $shohinInfo = $this->_CostcoShohin->getInfo($db, $inForm['c_kanri_no']);


        if (@!empty($shohinInfo)) { // 商品情報の設定
            // 配送料金の取得
            $deliveryInfo = $this->_CostcoDelivery->getInfo($db, $inForm['c_event_id'], $inForm['c_eventsub_id'], $shohinInfo['size']);
            
            $dataArr['Shohin']['name'] = $shohinInfo['shohin_name'];
            $dataArr['Shohin']['size'] = $shohinInfo['size'];
            $dataArr['Shohin']['num'] = $shohinInfo['konposu'];
            if (@$shohinInfo['data_type'] == '6') { // 通常商品
                //2023/01/10 GiapLN imp ticket #SMT6-352
                $dataArr['Shohin']['fare_price_tax'] = isset($deliveryInfo['fare_tax_kokyaku']) ? $deliveryInfo['fare_tax_kokyaku'] : 0;
                $dataArr['Shohin']['fare_amount_tax'] = isset($deliveryInfo['fare_tax_kokyaku']) ? $deliveryInfo['fare_tax_kokyaku'] : 0;
            } else { // D24商品
                //2023/01/10 GiapLN imp ticket #SMT6-352
                $dataArr['Shohin']['fare_price_tax'] = isset($deliveryInfo['fare_tax']) ? $deliveryInfo['fare_tax'] : 0;
                $dataArr['Shohin']['fare_amount_tax'] = isset($deliveryInfo['fare_tax']) ? $deliveryInfo['fare_tax'] : 0;
            }

            $dataArr['Shohin']['cost_price_tax'] = '0';
            $dataArr['Shohin']['cost_amount_tax'] = '0';
            $dataArr['Shohin']['type'] = $shohinInfo['data_type']; // 商品

            $dataArr['Shohin']['fare_price_tax_kokyaku'] = $deliveryInfo['fare_tax_kokyaku'];
            $dataArr['Shohin']['fare_amount_tax_kokyaku'] =  $deliveryInfo['fare_tax_kokyaku'];
            $dataArr['Shohin']['sagyo_jikan'] = '0';
            $dataArr['Shohin']['shohin_cd'] = $shohinInfo['shohin_cd'];
            $dataArr['Shohin']['box_id'] = '1'; // 業務連携時 ascで並び替えに使用
        }

        // オプション情報の設定
        // オプション情報取得
        //$optionInfo = $this->_CostcoOption->getInfo($db, $inForm['c_event_id'], $inForm['c_eventsub_id'], $shohinInfo['option_id']);

        $sagyoJikanOption = 0;
        $optionYushoMusho = '';
        if (@$inForm['c_option_cd_type'] == '1') {
        	Sgmov_Component_Log::debug("########################## type =1");
            // 無償オプション
            if (@$inForm['c_option_cd'] == '3') {
                $optionYushoMusho = '開梱・設置・廃材回収あり';
                $optionInfo = $this->_CostcoOption->getInfoBySelectOption($db, $inForm['c_event_id'], $inForm['c_eventsub_id'], $shohinInfo['option_id'], '0');
                $sagyoJikanOption = isset($optionInfo['sagyo_jikan']) ? $optionInfo['sagyo_jikan'] : 0;
                // 有償オプション
            } else if (@$inForm['c_option_cd'] == '1') {
                $optionYushoMusho = '開梱・設置・廃材回収あり';
                $optionInfo = $this->_CostcoOption->getInfoBySelectOption($db, $inForm['c_event_id'], $inForm['c_eventsub_id'], $shohinInfo['option_id'], '1');
                $sagyoJikanOption = isset($optionInfo['sagyo_jikan']) ? $optionInfo['sagyo_jikan'] : 0;
            } else {
                //梱包個数が1の場合
                if ($shohinInfo['konposu'] == '1') {
                    $optionYushoMusho = '軒先渡し';
                    $sagyoJikanOption = 0;
                } else {
                    $optionYushoMusho = "梱包数：{$shohinInfo['konposu']}";
                    $sagyoJikanOption = 0;
                    // 複数梱包の料金はオプション料金に含めない
                    //$optionInfo['fare_tax'] = 1800*($shohinInfo['konposu']-1);
                    //$optionInfo['fare_tax'] = 0;
                }
            }   
        } else if (@$inForm['c_option_cd_type'] == '2'){
        	Sgmov_Component_Log::debug("########################## type =2");
            if (@$inForm['c_option_cd'] == '0') {
                //梱包個数が1の場合
                if ($shohinInfo['konposu'] == '1') {
                    $optionYushoMusho = '軒先渡し';
                    $sagyoJikanOption = 0;
                } else {
                    $optionYushoMusho = "梱包数：{$shohinInfo['konposu']}";
                    $sagyoJikanOption = 0;
                }
            } else {
                $optionInfo = $this->_CostcoOption->getInfoOptionById($db, $inForm['c_option_cd']);
                $optionYushoMusho = "開梱・設置・廃材回収あり";
                $sagyoJikanOption = isset($optionInfo['sagyo_jikan']) ? $optionInfo['sagyo_jikan'] : 0;
            }
        }
        $dataArr['Option']['name'] = "{$optionYushoMusho}";
        $dataArr['Option']['size'] = NULL;
        $dataArr['Option']['num'] = $shohinInfo['konposu'];
        $dataArr['Option']['fare_price_tax'] = '0';
        $dataArr['Option']['fare_amount_tax'] = '0';
        $dataArr['Option']['cost_price_tax'] =  isset($optionInfo['fare_tax']) ? $optionInfo['fare_tax'] : '0';
        $dataArr['Option']['cost_amount_tax'] = isset($optionInfo['fare_tax']) ? $optionInfo['fare_tax'] : '0';
        $dataArr['Option']['type'] = '8'; // オプションサービス

        $dataArr['Option']['fare_price_tax_kokyaku'] = '0';
        $dataArr['Option']['fare_amount_tax_kokyaku'] =  '0';
        $dataArr['Option']['sagyo_jikan'] = $sagyoJikanOption;
        $dataArr['Option']['shohin_cd'] = NULL;
        $dataArr['Option']['box_id'] = '2'; // 業務連携時 ascで並び替えに使用

        // TODO：オプションがある場合、複数梱包数が登録されない対応
        // 2022/03/10現在で該当商品がないが対応必要になると思う
        //       // 複数梱包の設定
        //       if ($shohinInfo['konposu'] != '1') {
        //           $dataArr['Konposu']['name'] = '梱包数：'.$shohinInfo['konposu'];
        //           $dataArr['Konposu']['size'] = NULL;
        //           $dataArr['Konposu']['num'] = $shohinInfo['konposu'];
        //           $dataArr['Konposu']['fare_price_tax']  = '0';
        //           $dataArr['Konposu']['fare_amount_tax'] = '0';
        //           $dataArr['Konposu']['cost_price_tax']  = '0';
        //           $dataArr['Konposu']['cost_amount_tax'] = '0';
        //           $dataArr['Konposu']['type'] = '8';
        //           $dataArr['Konposu']['fare_price_tax_kokyaku'] = '0';
        //           $dataArr['Konposu']['fare_amount_tax_kokyaku'] = '0';
        //           $dataArr['Konposu']['sagyo_jikan'] = '0';
        //           $dataArr['Konposu']['shohin_cd'] = NULL;
        //           $dataArr['Konposu']['box_id'] = '2'; // 業務連携時 ascで並び替えに使用
        //       }

        // 階段上げ情報の設定
        if (@$inForm['c_kaidan_cd'] == '1') { // 階段上げ作業：作業あり / l_kaidan_type=A：外階段、l_kaidan_type=B：内階段
            // 階段上げ情報の取得
            $optionInfo = $this->_CostcoOption->getInfoKaidan($db, $inForm['c_event_id'], $inForm['c_eventsub_id'], $inForm['l_kaidan_type']);
            $nameSetting = '';
            if ($inForm['l_kaidan_type'] == 'A') {
                $nameSetting = '外階段3階～5階上げ';
            } else {
                $nameSetting = '内階段2階～3階上げ';
            }

            $dataArr['Kaidan']['name'] = $nameSetting; //'階段　有　' . $optionInfo['option_name'];
            $dataArr['Kaidan']['size'] = NULL;
            $dataArr['Kaidan']['num'] = '1';
            $dataArr['Kaidan']['fare_price_tax'] = '0';
            $dataArr['Kaidan']['fare_amount_tax'] = '0';
            //2023/01/10 GiapLN imp ticket #SMT6-352
            $dataArr['Kaidan']['cost_price_tax'] = isset($optionInfo['fare_tax']) ? $optionInfo['fare_tax'] : 0;
            $dataArr['Kaidan']['cost_amount_tax'] = isset($optionInfo['fare_tax']) ? $optionInfo['fare_tax'] : 0;
            $dataArr['Kaidan']['type'] = '8';
            $dataArr['Kaidan']['fare_price_tax_kokyaku'] = '0';
            $dataArr['Kaidan']['fare_amount_tax_kokyaku'] = '0';
            //2023/01/10 GiapLN imp ticket #SMT6-352
            $dataArr['Kaidan']['sagyo_jikan'] = isset($optionInfo['sagyo_jikan']) ? $optionInfo['sagyo_jikan'] : 0;
            $dataArr['Kaidan']['shohin_cd'] = $inForm['l_kaidan_type']; // 階段の場合は 外階段：A or 内階段：B
            $dataArr['Kaidan']['box_id'] = '3'; // 業務連携時 ascで並び替えに使用
        }

        // リサイクル情報の設定
        if (@!empty($inForm['l_recycl_name'])) { // リサイクル：希望する =>l_recycl_name：品目
            // オプション情報にリサイクルは含まれていないので取得不要
            // $optionInfo = $this->_CostcoOption->getInfo($db, $inForm['c_event_id'], $inForm['c_eventsub_id'], $shohinInfo['option_id']);

            $dataArr['Recycl']['name'] = 'リサ　有　' . @mb_substr($inForm['l_recycl_name'], 0, 100); // 画面空の入力値なので100で切っておく
            $dataArr['Recycl']['size'] = NULL;
            $dataArr['Recycl']['num'] = '1';
            $dataArr['Recycl']['fare_price_tax'] = '0';
            $dataArr['Recycl']['fare_amount_tax'] = '0';
            $dataArr['Recycl']['cost_price_tax'] = '0';
            $dataArr['Recycl']['cost_amount_tax'] = '0';
            $dataArr['Recycl']['type'] = '9'; // リサイクル

            $dataArr['Recycl']['fare_price_tax_kokyaku'] = '0';
            $dataArr['Recycl']['fare_amount_tax_kokyaku'] =  '0';
            $dataArr['Recycl']['sagyo_jikan'] = '0';
            $dataArr['Recycl']['shohin_cd'] = NULL;
            $dataArr['Recycl']['box_id'] = '4'; // 業務連携時 ascで並び替えに使用
        }


        foreach ($dataArr as $key => $val) {
            $data = array(
                "comiket_id" => $id,
                "type" => $val['type'], // 6：通常商品、7：顧客請求商品(D24)、8：オプション、9:リサイクル
                "name" => $val['name'],
                "box_id" => $val['box_id'], // 1：オプション、2：階段、3：リサイクル <= オプションと階段のキー被りエラーになるため box_id をずらす
                "num" => $val['num'],
                "fare_price" => "0", // ?
                "fare_amount" => "0", // ?
                "fare_price_tax" => $val['fare_price_tax'], // ?
                "fare_amount_tax" => $val['fare_amount_tax'], // ?
                "cost_price" => "0", // ?
                "cost_amount" => "0", // ?
                "cost_price_tax" => $val['cost_price_tax'], // ?
                "cost_amount_tax" => $val['cost_amount_tax'], // ?
                // "data_type" => "0",
                "fare_price_kokyaku" => "0",
                "fare_amount_kokyaku" => "0",
                "fare_price_tax_kokyaku" => $val['fare_price_tax_kokyaku'],
                "fare_amount_tax_kokyaku" => $val['fare_amount_tax_kokyaku'],
                "sagyo_jikan" => $val['sagyo_jikan'],
                "shohin_cd" => $val['shohin_cd'],
                "note1" => $val['name'],
            );
            $returnList[] = $data;
        }

        return $returnList;
    }


    /**
     * チェックデジットの算出
     *
     * @return int
     */
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

    /**
     * チェックデジットの算出
     *
     * @return int
     */
    public static function getChkD2($param)
    {

        $target = intval($param);
        $result = $target % 7;
        return $result;
    }

    /**
     * 完了メール送信
     *
     * @param $comiket 設定用配列
     * @param $sendTo2 宛先
     * @param sendCc   転送先
     * @param $type    往復区分
     * @return bool true:成功
     */
    public function sendCompleteMail($comiket, $sendTo2 = '', $sendCc = '', $type = '', $tmplateType = '', $inForm = array())
    {
        try {

            if (@empty($tmplateType)) {
                //添付ファイルの有無を判別
                $isAttachment = ($comiket['choice'] == 2 || $comiket['choice'] == 3) ? true : false;
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
            $eventsubData = $this->_EventsubService->fetchEventsubIdAndSubid($db, $comiket['event_id'], $comiket['eventsub_id']);


            /////////////////////////////////////////////////////////////////////////////////////////////

            $week = ['日', '月', '火', '水', '木', '金', '土'];

            $comiketPrefData = $this->_PrefectureService->fetchPrefecturesById($db, $comiket['pref_id']);

            $data['comiket_id'] = sprintf('%010d', $comiket['id']); //【コミケID】
            $data['event_name'] = $eventData["name"] . $eventsubData["name"]; //【出展イベント】

            $data['place_name'] = $eventsubData["venue"]; //【場所】


            //個人用メールテンプレート
            $mailTemplate[] = "/csc_complete_individual{$tmplateType}.txt";

            // 申込日
            $data['moushikomibi'] = date('Y/m/d');

            // 商品情報
            $shohinInfo = $this->_CostcoShohin->getInfo($db, $inForm['c_kanri_no']);
            //2023/01/10 GiapLN imp ticket #SMT6-352
            if (empty($shohinInfo)) {
                Sgmov_Component_Log::err('商品情報を取得できない。コストコ_商品マスタのデータをご確認してください。');
                throw new Exception('商品情報を取得できない。コストコ_商品マスタのデータをご確認してください。');
            }
            $data['kanri_no'] = $shohinInfo['shohin_cd'] . '：' . $shohinInfo['shohin_name'];
            

            /*
            * BR タグを改行コードに変換する
            */
            function br2nl($string)
            {
                // 大文字・小文字を区別しない
                return preg_replace('/<br[[:space:]]*\/?[[:space:]]*>/i', "\n　　", $string);
            }
            // オプション
            $data['option_kibo'] = '';
            $data['option_sagyo_naiyo'] = '';
            if (@!empty($shohinInfo)) {
                if (@$inForm['c_option_cd_type'] == '1') {
                	Sgmov_Component_Log::debug("############################ type =1");
                    //画面の選択したオプション内容より、オプションマスタを取得する。
                    //無償オプション
                    if (isset($inForm['c_option_cd']) && $inForm['c_option_cd'] == '3') {
                        $optionInfo = @$this->_CostcoOption->getInfoBySelectOption($db, $inForm['c_event_id'], $inForm['c_eventsub_id'], $shohinInfo['option_id'], '0'); // 商品
                    }
                    //有償オプション
                    if (isset($inForm['c_option_cd']) && $inForm['c_option_cd'] == '1') {
                        $optionInfo = @$this->_CostcoOption->getInfoBySelectOption($db, $inForm['c_event_id'], $inForm['c_eventsub_id'], $shohinInfo['option_id'], '1'); // 商品
                    }
                } else if (@$inForm['c_option_cd_type'] == '2'){
                	Sgmov_Component_Log::debug("############################ type =2");
                    if ($inForm['c_option_cd'] !='0') {//オプション無し以外
                        $optionInfo = @$this->_CostcoOption->getInfoOptionById($db, $inForm['c_option_cd']); 
                    }
                }

                // 2022/01/25 追加
                // 梱包数
                $data['konposu'] = "【梱包数】{$shohinInfo['konposu']}";
                // 2022/01/25 追加
                if (@$inForm['c_option_cd_type'] == '1') {
                    if (@$inForm['c_option_cd'] == '3') { // 無償オプション
                        //$data['option_kibo'] = '【オプションサービス】無償オプション を選択';
                        //2023/01/10 GiapLN imp ticket #SMT6-352
                        if (isset($optionInfo['sagyo_naiyo'])) {
                            $data['option_kibo'] = "【オプションサービス】{$optionInfo['sagyo_naiyo']} を選択";
                            $data['option_sagyo_naiyo'] = @br2nl($optionInfo['sagyo_naiyo']);
                        } else {
                            $data['option_kibo'] = "【オプションサービス】";
                            $data['option_sagyo_naiyo'] = '';
                        }
                        
                    } else if (@$inForm['c_option_cd'] == '1') { // 有償オプション
                        //$data['option_kibo'] = '【オプションサービス】有償オプション を選択';
                        //2023/01/10 GiapLN imp ticket #SMT6-352
                        if (isset($optionInfo['sagyo_naiyo'])) {
                            $data['option_kibo'] = "【オプションサービス】{$optionInfo['sagyo_naiyo']} を選択";
                            $data['option_sagyo_naiyo'] = @br2nl($optionInfo['sagyo_naiyo']);
                        } else {
                            $data['option_kibo'] = "【オプションサービス】";
                            $data['option_sagyo_naiyo'] = '';
                        }
                        
                    } else if (@$inForm['c_option_cd'] == '2') { // オプションなし
                        $data['option_kibo'] = '【オプションサービス】オプションなし を選択';
                        //                } else { // c_option_cd が NULL の場合（梱包数２以上）
                        //                    $data['option_kibo'] = "【オプションサービス】梱包数：{$shohinInfo['konposu']}";
                    }
                } else if(@$inForm['c_option_cd_type'] == '2'){//c_option_cd_type=2
                    if (@$inForm['c_option_cd'] == '0') { // オプションなし
                        $data['option_kibo'] = '【オプションサービス】オプションなし を選択';
                    } else {
                        //2023/01/11 GiapLN imp ticket #SMT6-352
                        if (!empty($optionInfo)) {
                            if ($optionInfo['yumusyou_kbn'] == '1') {//有償オプション
                                //$data['option_kibo'] = '【オプションサービス】有償オプション を選択';
                                $data['option_kibo'] = "【オプションサービス】{$optionInfo['sagyo_naiyo']} を選択";
                                $data['option_sagyo_naiyo'] = @br2nl($optionInfo['sagyo_naiyo']);
                            } else {//無償オプション
                                //$data['option_kibo'] = '【オプションサービス】無償オプション を選択';
                                $data['option_kibo'] = "【オプションサービス】{$optionInfo['sagyo_naiyo']} を選択";
                                $data['option_sagyo_naiyo'] = @br2nl($optionInfo['sagyo_naiyo']);
                            }
                        } else {
                            $data['option_kibo'] = "【オプションサービス】";
                            $data['option_sagyo_naiyo'] = '';
                        }
                        
                    }
                }
            }

            // 階段上げ作業
            $data['kaidan_title'] = '';
            $data['kaidan_kibo']  = '';
            $data['kaidan_type']  = '';
            if ((@$inForm['l_is_kaidan'] == 'true' || @$inForm['l_is_kaidan'] == true)
                && @!empty($inForm['c_kaidan_cd'])
            ) {
                $data['kaidan_title'] = '【階段上げ作業】';
                $data['kaidan_kibo'] = @$inForm['c_kaidan_cd'] == '1' ? '作業あり' : '作業なし';
                if (@$inForm['c_kaidan_cd'] == '1') { // 作業あり
                    $data['kaidan_type'] = $inForm['l_kaidan_type'] == 'A' ? '：外階段あり' : '：内階段あり';
                }
            }

            // リサイクル
            $data['recycl_name'] = '';
            $data['recycl_kibo'] = '';
            if ($shohinInfo['option_id'] == '1' || $shohinInfo['option_id'] == '3' || $shohinInfo['option_id'] == '5') {
                $data['recycl_name'] = '';
                $data['recycl_kibo'] = $inForm['c_recycl_cd'] == '1' ? '【リサイクル】希望する：' : '【リサイクル】希望しない';
                if ($inForm['c_recycl_cd']  == '1') { // 希望する
                    // 画面からのデータのため念のため 100文字制限かけておく
                    $data['recycl_name'] = @mb_substr($inForm['l_recycl_name'], 0, 100);
                }
            }

            $getBoxInfoForCostco = function ($comiket, $type) {
                $comiketBoxList = $comiket['comiket_detail_list'][0]['comiket_box_list'];
                $resultList = array();
                foreach ($comiketBoxList as $key => $val) {
                    if (is_array($type)) {
                        if (in_array($val['type'], $type)) {
                            $resultList[] = $val;
                        }
                    } else {
                        if ($val['type'] == $type) {
                            $resultList[] = $val;
                        }
                    }
                }
                return $resultList;
            };


            // 配送金額
            $boxInfoShohinList = $getBoxInfoForCostco($comiket, array('6', '7')); // 6: 通常商品、7: D24商品
            $boxInfoShohin = $boxInfoShohinList[0];
            if ($boxInfoShohin['type'] == '6') { // 6: 通常商品
                $data['haiso_box_fare_amount_tax'] = number_format($boxInfoShohin['fare_amount_tax_kokyaku']);
            } else { // 7: D24商品
                $data['haiso_box_fare_amount_tax'] = number_format($boxInfoShohin['fare_amount_tax']);
            }

            // オプション・リサイクル金額
            // $data['option_box_fare_amount_tax_biko'] ='';
            $boxInfoOptionList = $getBoxInfoForCostco($comiket, array('8')); // 8: オプション
            $optionInfo = array();
            $optionKingakuSum = 0;
            foreach ($boxInfoOptionList as $key => $val) {
                //if (@empty($val['shohin_cd']) && (@$inForm['c_option_cd'] == '1' || @empty($inForm['c_option_cd']))) {
                //金額計算条件：有償オプション（商品問わず）、無償オプション（マスタより設定)、画面のオプション無しの場合、金額が入らない            
                
//                if (@empty($val['shohin_cd']) 
//                        && (
//                                @$inForm['c_option_cd'] == '1' || 
//                                @empty($inForm['c_option_cd']) ||
//                                (
//                                    @$inForm['c_option_cd'] == '3' && 
//                                        (
//                                            $shohinInfo['option_id'] != 4 || $shohinInfo['data_type'] != 7
//                                        )
//                                        
//                                ) //D24且つオプション４の以外の時、無償の場合でも、メールとデータベースに金額が入ります。
//                            )
//                        ) {
//                    // 商品コードが空 かつ 有償オプション または、梱包数が2以上の場合
//                    $optionKingakuSum += intval($val['cost_amount_tax']);
//                } else if (@$val['shohin_cd'] == 'A' || @$val['shohin_cd'] == 'B') {
//                    $optionKingakuSum += intval($val['cost_amount_tax']);
//                }
                if (@empty($val['shohin_cd'])) {
                    if (
                            ($inForm['c_option_cd_type'] == '1' && $inForm['c_option_cd'] !='2') ||//オプション無しの以外
                            ($inForm['c_option_cd_type'] == '2' && $inForm['c_option_cd'] !='0')//オプション無しの以外
                       ) {
                        $optionKingakuSum += intval($val['cost_amount_tax']);
                    }
                } else if (@$val['shohin_cd'] == 'A' || @$val['shohin_cd'] == 'B') {
                    $optionKingakuSum += intval($val['cost_amount_tax']);
                }
            }
            $data['option_box_fare_amount_tax'] = number_format($optionKingakuSum);

            // $data['option_box_fare_amount_tax_biko'] = "計算式：オプション料金+1800*(梱包数-1)";


            $data['surname'] = $comiket['personal_name_sei'];
            $data['forename'] = $comiket['personal_name_mei'];
            $data['comiket_personal_name_seimei'] = $comiket['personal_name_sei'] . " " . $comiket['personal_name_mei'];


            $data['comiket_zip'] = "〒" . substr($comiket['zip'], 0, 3) . '-' . substr($comiket['zip'], 3); //【郵便番号】
            $data['comiket_pref_name'] = $comiketPrefData['name']; //【都道府県】
            $data['comiket_address'] = $comiket['address']; //【住所】
            $data['comiket_building'] = $comiket['building']; //【ビル】
            $data['comiket_tel'] = $comiket['tel']; //【電話番号】
            $data['comiket_mail'] = $comiket['mail']; //【メール】
            $data['comiket_staff_seimei'] = $comiket['staff_sei'] . " " . $comiket['staff_mei']; //【セイ】【メイ】
            $data['comiket_staff_seimei_furi'] = $comiket['staff_sei_furi'] . " " . $comiket['staff_mei_furi']; //【セイ】【メイ】フリ
            $data['comiket_staff_tel'] = $comiket['staff_tel']; //【担当電話番号】

            $haitatsuKiboItemInfo = $this->_CostcoDataDisplayService->getInfo($db, $inForm['c_event_id'], $inForm['c_eventsub_id'], 'HAITATSU_KIBO_ITEM');
            // 数量
            $comiket_detail_list = $comiket['comiket_detail_list'];
            foreach ($comiket_detail_list as $k => $comiket_detail) {
                if (!isset($haitatsuKiboItemInfo['display_val']) || $haitatsuKiboItemInfo['display_val'] != 'OFF') {
                    if (empty($comiket_detail['delivery_date'])) {
                        $deliveryDateName = "";
                    } else {
                        $deliveryDate = new DateTime($comiket_detail['delivery_date']);
                        $extWeek = date('w', strtotime($comiket_detail["delivery_date"]));
                        $deliveryDateName = '【配達希望日】' . $deliveryDate->format('Y年m月d日') . "(" . $week[$extWeek] . ")";
                    }
                } else {
                    $deliveryDateName = "";
                }

                $prefData = $this->_PrefectureService->fetchPrefecturesById($db, $comiket_detail['pref_id']);
                $comiketType = "type2"; //搬出
                $data[$comiketType . '_name'] = $comiket_detail['name'];                                                             //【配送先名】
                $data[$comiketType . '_zip'] = "〒" . substr($comiket_detail['zip'], 0, 3) . '-' . substr($comiket_detail['zip'], 3); //【配送先郵便番号】
                $data[$comiketType . '_pref'] = $prefData["name"];                                                                   //【都道府県】
                $data[$comiketType . '_address'] = $comiket_detail['address'];                                                       //【市町村区】
                $data[$comiketType . '_building'] = $comiket_detail['building'];                                                     //【建物番地名】
                $data[$comiketType . '_tel'] = $comiket_detail['tel'];                                                               //【配送先電話番号】
                $data[$comiketType . '_delivery_date'] = @$deliveryDateName;                                                          //【お届け日時】
            }
            //GiapLN fix bug SMT6-229 2022.08.04 
            $baseUrl = Sgmov_Component_Config::getUrlPublicSsl();
            $data['url_tyuijiko'] = !empty($eventsubData['csc_nensho_file_nm']) ? $baseUrl.'/csc/pdf/' . $eventsubData['csc_nensho_file_nm'] : '';

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
                // 営業所へメール(CCとして設定する用にもっていた変数をToの方へ)
                if (@!empty($sendCc)) {
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
     * 商品情報取得
     *
     * @return array
     */
    protected function getShohinInfo($inputInfo = array())
    {
        if (@empty($inputInfo)) {
            $inputInfo = $_POST;
        }

        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();
        $shohinInfo = @$this->_CostcoShohin->getInfo($db, $inputInfo['c_kanri_no']);
        $optionInfos = array();
        $delivInfo = array();
        $kaidanInfoList = array();
        $fukusukonpoInfo = array();
        $optionDispInfo = array();

        if (@!empty($shohinInfo)) {
            // オプション情報
            $optionInfos = @$this->_CostcoOption->getInfoAll($db, $inputInfo['c_event_id'], $inputInfo['c_eventsub_id'], $shohinInfo['option_id']);
            // オプションサービスの無償オプションの表示
            $optionDispInfo['dispMusho']  = '0';
            // オプションサービスの有償オプションの表示
            $optionDispInfo['dispYusho']  = '0';
            // リサイクルの表示
            $optionDispInfo['dispRecycle'] = '0';
            // オプション作業名
            $optionDispInfo['dispSagyoNm'] = '';
            //オプション情報はマスタから参照して、表示するように対応する。
            if (!empty($optionInfos)) {
                //option_typeがA、Bの場合、表示されない。
                foreach ($optionInfos as $option) {
                    //0:無償オプション
                    if (isset($option['yumusyou_kbn']) && $option['yumusyou_kbn'] == '0') {
                        $optionDispInfo['dispMusho']  = '1';
                        $optionDispInfo['dispSagyoNm'] .= empty($optionDispInfo['dispSagyoNm']) ? $option['sagyo_naiyo'] : '　<br>' . $option['sagyo_naiyo'];
                    }
                    //1:有償オプション
                    if (isset($option['yumusyou_kbn']) && $option['yumusyou_kbn'] == '1') {
                        $optionDispInfo['dispYusho']  = '1';
                        $optionDispInfo['dispSagyoNm'] .= empty($optionDispInfo['dispSagyoNm']) ? $option['sagyo_naiyo'] : '　<br>' . $option['sagyo_naiyo'];
                    }

                    //リサイクル区別：0：リサイクルなし、1：リサイクルあり
                    if (isset($option['recycle_kbn']) && $option['recycle_kbn'] == '1') {
                        $optionDispInfo['dispRecycle']  = '1';
                    }
                }
//                switch ($optionInfo['option_type']) {
//                    case '1':
//                        $optionDispInfo['dispMusho']   = '1';
//                        $optionDispInfo['dispRecycle'] = '1';
//                        break;
//                    case '3':
//                        $optionDispInfo['dispMusho']   = '0';
//                        $optionDispInfo['dispRecycle'] = '1';
//                        break;
//                    case '5':
//                        $optionDispInfo['dispMusho']   = '1';
//                        $optionDispInfo['dispRecycle'] = '1';
//                        break;
//                    case '8':
//                        $optionDispInfo['dispMusho']   = '0';
//                        $optionDispInfo['dispRecycle'] = '0';
//                        break;
//                    case '9':
//                        $optionDispInfo['dispMusho']   = '0';
//                        $optionDispInfo['dispRecycle'] = '0';
//                        break;
//                    case '9999':
//                        $optionDispInfo['dispMusho']   = '0';
//                        $optionDispInfo['dispRecycle'] = '0';
//                        break;
//                }
            }

            // 階段上げ作業の有無
            $shohinInfo['is_kaidan'] = FALSE;
            if (self::KAIDANAGE_SIZE <= $shohinInfo['size']) {
                $shohinInfo['is_kaidan'] = TRUE;
                foreach ($this->kaidanage_option_type as $v) {
                    $kaidanInfoList[$v] = @$this->_CostcoOption->getInfoKaidan($db, $inputInfo['c_event_id'], $inputInfo['c_eventsub_id'], $v);
                }
            }

            // 転送料金を含む配送料を決定
            // 入力画面で検索された場合、フォームの配置順で都道府県選択が行われない場合があるのでダミーで取得
            if (!isset($inputInfo['d_pref_id']) || empty($inputInfo['d_pref_id'])) {
                $delivInfo = @$this->_CostcoDelivery->getInfo($db, $inputInfo['c_event_id'], $inputInfo['c_eventsub_id'], $shohinInfo['size']);
                
            } else {
                // calcEveryKindData()の配送料取得と同じ処理
                $tmpDelivInfo = @$this->_CostcoDelivery->getInfoPlusTensoryo($db, $inputInfo['c_event_id'], $inputInfo['c_eventsub_id'], $shohinInfo['size']);
                // 都道府県コードが一致する場合
                //2023/01/10 GiapLN imp ticket #SMT6-352
                if (!empty($tmpDelivInfo)) {
                    foreach ($tmpDelivInfo as $v) {
                        if ($v['delivery_pref_id'] == $inputInfo['d_pref_id']) {
                            $delivInfo = $v;
                            break;
                        }
                    }
                }
                
                // 都道府県コードが一致しない場合は99：self::COMMON_PREF_IDで決定
                if (empty($delivInfo)) {
                    //2023/01/10 GiapLN imp ticket #SMT6-352
                    if (!empty($tmpDelivInfo)) {
                        foreach ($tmpDelivInfo as $v) {
                            if ($v['delivery_pref_id'] == self::COMMON_PREF_ID) {
                                $delivInfo = $v;
                                break;
                            }
                        }
                    }
                }
            }
            // 複数配送料を取得
            $fukusukonpoInfo = @$this->_CostcoDeliveryFukusukonpo->getInfo($db, $inputInfo['c_event_id'], $inputInfo['c_eventsub_id'], $shohinInfo['konposu']);
        }

        return array(
            'shohin'     => $shohinInfo,
            'option'     => $optionInfos,
            'kaidanList' => $kaidanInfoList,
            'deliv'      => $delivInfo,
            'konpo'      => $fukusukonpoInfo,
            'optionDisp' => $optionDispInfo,

        );
    }

    /**
     * 入力値バリデーション
     *
     * @return void
     */
    protected function checkInput($inputInfo = array())
    {
        if (@empty($inputInfo)) {
            $inputInfo = $_POST;
        }

        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        $errInfoList = array();
        //2023/02/20 GiapLN imp ticket #SMT6-390
        $tmpCd = $this->_CostcoCustomerCd->getInfo($db, $inputInfo['c_event_id'], $inputInfo['c_eventsub_id']);
        if (!isset($tmpCd['customer_cd'])) {
            $errorInfo = array(
                'key' => 'customer_cd',
                'itemName' => '',
                'errMsg' => '適用開始日、終了日により、顧客マスタを取得できていません。',
            );
            if (empty($errInfoList)) {
                $errInfoList[] = $errorInfo;
            }
        }
        
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        // 管理番号(商品コード)
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        $v = @Sgmov_Component_Validator::createSingleValueValidator($inputInfo['c_kanri_no'])->isNotEmpty()->isLengthLessThanOrEqualTo(30)->isWebSystemNg();
        $errInfoList = $this->checkErrInfo($errInfoList, $v, 'c_kanri_no', 'アイテム番号');
        
        if (!preg_match("/^[a-zA-Z0-9!#<>:;&~@%+$'\*\^\(\)\[\]\|\/\.,_-]+$/", $inputInfo['c_kanri_no'])) {
            $errorInfo = array(
                'key' => 'c_kanri_no',
                'itemName' => 'アイテム番号',
                'errMsg' => 'は半角英数字+半角記号で入力してください(一部記号除く)',
            );
            //$errInfoList[] = $errorInfo;
            if (empty($errInfoList)) {
                $errInfoList[] = $errorInfo;
            }
        }

        $shohinInfo = $this->getShohinInfo($inputInfo);
        if (@empty($shohinInfo['shohin'])) {
            $errorInfo = array(
                'key' => 'c_kanri_no',
                'itemName' => 'アイテム番号',
                'errMsg' => 'に対応する商品はありません',
            );
            //$errInfoList[] = $errorInfo;
            if (empty($errInfoList)) {
                $errInfoList[] = $errorInfo;
            }
        }

        $checkRangeKibo = array(
            '1', // 希望する、階段作業あり
            '2'  // 希望しない、階段作業なし
        );

        if (@empty($errInfoList)) {
            // 管理番号（商品コード）の入力ＯＫの場合以下をチェック
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            // オプションサービス
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            if (@!empty($shohinInfo['option'])) { // オプションが存在する場合は必須
                //GiapLN update task Cosco #SMT6-345 3.11.2022
                //$v = @Sgmov_Component_Validator::createSingleValueValidator($inputInfo['c_option_cd'])->isIn(array('1', '2', '3'));
                $v = @Sgmov_Component_Validator::createSingleValueValidator($inputInfo['c_option_cd'])->isNotEmpty();
                $errInfoList = $this->checkErrInfo($errInfoList, $v, 'c_option_cd', 'オプションサービス');
            }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            // 階段上げ作業
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            if (@!empty($shohinInfo['shohin']['is_kaidan'])) { // 階段上げ作業が存在する場合は必須
                $v = @Sgmov_Component_Validator::createSingleValueValidator($inputInfo['c_kaidan_cd'])->isIn($checkRangeKibo);
                $errInfoList = $this->checkErrInfo($errInfoList, $v, 'c_kaidan_cd', '階段上げ作業');

                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                // 外階段内階段選択
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                if (@$inputInfo['c_kaidan_cd'] == '1') { // 階段上げ作業あり
                    $v = @Sgmov_Component_Validator::createSingleValueValidator($inputInfo['l_kaidan_type'])->isIn(array('A', 'B'));
                    $errInfoList = $this->checkErrInfo($errInfoList, $v, 'c_kaidan_cd', '外階段あり・内階段あり');
                }
            }

            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            // リサイクル
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            $v = @Sgmov_Component_Validator::createSingleValueValidator($inputInfo['c_recycl_cd'])->isIn($checkRangeKibo);
            $errInfoList = $this->checkErrInfo($errInfoList, $v, 'c_recycl_cd', 'リサイクル');
            if (@$inputInfo['c_recycl_cd'] == '1') { // 希望する
                $v = @Sgmov_Component_Validator::createSingleValueValidator($inputInfo['l_recycl_name'])->isNotEmpty()->isLengthLessThanOrEqualTo(50);
                if (!$v->isValid()) {
                    $errInfoList = $this->checkErrInfo($errInfoList, $v, 'c_recycl_cd', 'リサイクル');
                } else {
                    $optionId = $shohinInfo['shohin']['option_id'];
                    $shohinNm = $shohinInfo['shohin']['shohin_name'];
                    
                    $recyclCdIsErr = false;
                    if (($optionId == '3' || $optionId == '4') && $inputInfo['l_recycl_name'] != '冷蔵庫') {
                        $recyclCdIsErr = true;
                    }
                    if ($optionId == '5' && $inputInfo['l_recycl_name'] != '洗濯機') {
                        $recyclCdIsErr = true;
                    }
                    if ($optionId == '1' && $inputInfo['l_recycl_name'] != 'テレビ') {
                        $recyclCdIsErr = true;
                    }
                    if ($inputInfo['l_recycl_name'] == 'エアコン' && in_array($optionId, ['1','3','4','5']) === false && strpos($shohinNm, 'エアコン') === false) {
                        $recyclCdIsErr = true;
                    }
                    if ($recyclCdIsErr) {
                        $errorInfo = array(
                            'key' => 'c_recycl_cd',
                            'itemName' =>  '',
                            'errMsg' => '家電リサイクル品は購入品と同等品を選択してください。',
                        );
                        $errInfoList[] = $errorInfo;
                    }
                    
                }
            }
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        // 氏名
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        $v = @Sgmov_Component_Validator::createSingleValueValidator($inputInfo['c_personal_name_sei'])
            ->isNotEmpty()->isLengthLessThanOrEqualTo(8)
            ->isNotHalfWidthKana()->isWebSystemNg();
        $errInfoList = $this->checkErrInfo($errInfoList, $v, 'c_personal_name_sei', '氏名');

        $v = @Sgmov_Component_Validator::createSingleValueValidator($inputInfo['c_personal_name_mei'])
            ->isNotEmpty()->isLengthLessThanOrEqualTo(8)
            ->isNotHalfWidthKana()->isWebSystemNg();
        $errInfoList = $this->checkErrInfo($errInfoList, $v, 'c_personal_name_sei', '氏名');

        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        // 電話番号
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        //GiapLN imp ticket #SMT6-381 2022/12/28
        //$v = Sgmov_Component_Validator::createSingleValueValidator($inputInfo['c_tel'])->isNotEmpty()->isPhoneHyphen();
        $v = Sgmov_Component_Validator::createSingleValueValidator($inputInfo['c_tel'])->isNotEmpty()->isPhoneHyphen()->isLengthLessThanOrEqualToForPhone();
        $errInfoList = $this->checkErrInfo($errInfoList, $v, 'c_tel', '電話番号');

        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        // メールアドレス
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        $v = Sgmov_Component_Validator::createSingleValueValidator($inputInfo['c_mail'])->isNotEmpty()->isMail()
            ->isLengthLessThanOrEqualTo(100)->isWebSystemNg();
        $errInfoList = $this->checkErrInfo($errInfoList, $v, 'c_mail', 'メールアドレス');

        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        // メールアドレス確認
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        $v = Sgmov_Component_Validator::createSingleValueValidator($inputInfo['l_mail_kakunin'])
            ->isNotEmpty()->isMail()->isLengthLessThanOrEqualTo(100)->isWebSystemNg();
        $errInfoList = $this->checkErrInfo($errInfoList, $v, 'l_mail_kakunin', 'メールアドレス確認');

        if ($inputInfo['c_mail'] != $inputInfo['l_mail_kakunin']) {
            $errorInfo = array(
                'key' => 'l_mail_kakunin',
                'itemName' => 'メールアドレス確認',
                'errMsg' => 'がメールアドレスと一致しません',
            );
            $errInfoList[] = $errorInfo;
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        // 配送先宛名
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        $v = Sgmov_Component_Validator::createSingleValueValidator($inputInfo['d_name'])
            ->isNotEmpty()->isLengthLessThanOrEqualTo(32)->isNotHalfWidthKana()->isWebSystemNg();
        $errInfoList = $this->checkErrInfo($errInfoList, $v, 'd_name', '配送先宛名');
        
         //GiapLN imp ticket #SMT6-385 2022/12/27 
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        // 配送先電話番号
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        //GiapLN imp ticket #SMT6-381 2022/12/28
        $v = Sgmov_Component_Validator::createSingleValueValidator($inputInfo['staff_tel'])->isNotEmpty()->isPhoneHyphen()->isLengthLessThanOrEqualToForPhone();
        $errInfoList = $this->checkErrInfo($errInfoList, $v, 'staff_tel', '配送先電話番号');
        
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        // 郵便番号
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        $zipV = Sgmov_Component_Validator::createZipValidator($inputInfo['l_zip1'], $inputInfo['l_zip2'])->isNotEmpty()->isZipCode();
        $errInfoList = $this->checkErrInfo($errInfoList, $zipV, 'l_zip1', '郵便番号');

        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        // 都道府県
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        $prefectures = $this->_PrefectureService->fetchPrefectures($db);
        $v = Sgmov_Component_Validator::createSingleValueValidator($inputInfo['d_pref_id'])
            ->isSelected()->isIn($prefectures['ids']);
        $errInfoList = $this->checkErrInfo($errInfoList, $v, 'd_pref_id', '都道府県');

        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        // 市区町村
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        $v = Sgmov_Component_Validator::createSingleValueValidator($inputInfo['d_address'])
            ->isNotEmpty()->isLengthLessThanOrEqualTo(14)->isNotHalfWidthKana()->isWebSystemNg();
        $errInfoList = $this->checkErrInfo($errInfoList, $v, 'd_address', '市区町村');

        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        // 番地・建物名・部屋番号
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        $v = Sgmov_Component_Validator::createSingleValueValidator($inputInfo['d_building'])
            ->isNotEmpty()->isLengthLessThanOrEqualTo(30)->isNotHalfWidthKana()->isWebSystemNg();
        $errInfoList = $this->checkErrInfo($errInfoList, $v, 'd_building', '番地・建物名・部屋番号');

        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        // 配達希望日
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        if (@!empty($inputInfo['d_delivery_date_fmt'])) {
            $delivDateStr = date('Y/m/d', strtotime($inputInfo['d_delivery_date_fmt']));

            // デバッグ
            Sgmov_Component_Log::debug($delivDateStr);
            Sgmov_Component_Log::debug($inputInfo['d_delivery_date_fmt']);

            if ($inputInfo['d_delivery_date_fmt'] !=  $delivDateStr) {
                $errorInfo = array(
                    'key' => 'd_delivery_date',
                    'itemName' => '配達希望日',
                    'errMsg' => 'を見直してください',
                );
                $errInfoList[] = $errorInfo;
            }

            // リードタイムのバリデート
            $v = Sgmov_Component_Validator::createSingleValueValidator($inputInfo['d_from'])->isNotEmpty()->isInteger();
            $errInfoList = $this->checkErrInfo($errInfoList, $v, 'd_from', '配達希望日の日付');
            $v = Sgmov_Component_Validator::createSingleValueValidator($inputInfo['d_to'])->isNotEmpty()->isInteger();
            $errInfoList = $this->checkErrInfo($errInfoList, $v, 'd_to', '配達希望日の日付');

            //GiapLN implement task #SMT6-348 2022.11.17
//            $DELIV_DATE_MIN = '+' . $inputInfo['d_from'];
//            $DELIV_DATE_MAX = '+' . ($inputInfo['d_from'] + $inputInfo['d_to']);
//            $delivDateMinStr = date('Y/m/d', strtotime(date('Y/m/d') . " {$DELIV_DATE_MIN} day"));
//            $delivDateMaxtr = date('Y/m/d', strtotime(date('Y/m/d') . " {$DELIV_DATE_MAX} day"));
//
//            if ($delivDateStr < $delivDateMinStr || $delivDateMaxtr < $delivDateStr) {
//                $errorInfo = array(
//                    'key' => 'd_delivery_date',
//                    'itemName' => '配達希望日',
//                    'errMsg' => "は、配達可能日範囲外です（申込日より{$delivDateMinStr}～{$delivDateMaxtr}まで選択可能）",
//                );
//                $errInfoList[] = $errorInfo;
//            }
            $arrDateDis = json_decode($inputInfo['arr_date_dis']);
            if (!empty($arrDateDis)) {
                $countDateDis = count($arrDateDis);
                $k = 0;
                $delivDateMin = date('Y/m/d', strtotime($arrDateDis[0][0]));
                $delivDateMax = date('Y/m/d', strtotime($arrDateDis[$countDateDis - 1][1]));
                
                foreach ($arrDateDis as $rowDate) {
                    $delivDateMinStr = date('Y/m/d', strtotime($rowDate[0]));
                    $delivDateMaxtr = date('Y/m/d', strtotime($rowDate[1]));
                    if ($delivDateStr < $delivDateMinStr || $delivDateMaxtr < $delivDateStr) {
                        $k++;
                    }
                }

                if ($countDateDis === $k) {
                    $errorInfo = array(
                        'key' => 'd_delivery_date',
                        'itemName' => '配達希望日',
                        'errMsg' => "は、配達可能日範囲外です（申込日より{$delivDateMin}～{$delivDateMax}まで選択可能）",
                    );
                    $errInfoList[] = $errorInfo;
                }
            }
        }

        // 配送可能地域かチェックする
        if (@empty($errInfoList)) {
            $prefecturesSelected = $this->_PrefectureService->fetchPrefecturesById($db, $inputInfo['d_pref_id']);
            $inputAddress = @$prefecturesSelected["name"] . @$inputInfo['d_address'] . @$inputInfo['d_building'];
            $resultZipDll = $this->_getAddress($inputInfo['l_zip1'] . $inputInfo['l_zip2'], @$inputAddress);
            $dllAddress = $resultZipDll['KenName'] . $resultZipDll['CityName'] . $resultZipDll['TownName'];
            Sgmov_Component_Log::debug($inputInfo['c_eventsub_id']);
            Sgmov_Component_Log::debug($resultZipDll);
            Sgmov_Component_Log::debug($inputAddress);
            Sgmov_Component_Log::debug($dllAddress);
            Sgmov_Component_Log::debug(strpos($inputAddress, $dllAddress));
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            // 住所チェック
            ///////////////////////////////////////////////////////////////////////////////////////////////////////

            if (0 !== strpos($inputInfo['l_zip1'] . $inputInfo['l_zip2'] . $inputAddress, $resultZipDll['ZipCode'] . $dllAddress)) {
                $errorInfo = array(
                    'key' => 'l_zip1',
                    'itemName' => '',
                    'errMsg' => '郵便番号と住所を確認してください',
                );
                $errInfoList[] = $errorInfo;
                return $errInfoList;
            }

            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            // 配送可能地域チェック
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            // 2022-12-21 FPT-AnNV6 update SMT6-383
            $haisoFukaFlg = "1"; //配送不可地域フラグ
            $jis5Info = $this->_CostcoHaisokanoJis5Service->getInfo($db, $inputInfo['c_event_id'], $inputInfo['c_eventsub_id'], @$resultZipDll["JIS5Code"], $haisoFukaFlg);

            // if (@empty($jis5Info)) {
            //     $errorInfo = array(
            //         'key' => 'l_zip1',
            //         'itemName' => '',
            //         'errMsg' => 'ご利用不可の配送地域です',
            //     );
            //     $errInfoList[] = $errorInfo;
            // }
            //沖縄の場合、配達不可
            if (!empty($resultZipDll["JIS5Code"]) && substr($resultZipDll["JIS5Code"], 0,2) == 47) {
                 $errorInfo = array(
                     'key' => 'l_zip1',
                     'itemName' => '',
                     'errMsg' => '沖縄県は、配達不可地域となっております',
                 );
                 $errInfoList[] = $errorInfo;
            } elseif (!empty($resultZipDll['RelayFlag']) || !empty($resultZipDll['ExchangeFlag']) || !empty($jis5Info)) { //離島な住所をチェック
                $errorInfo = array(
                    'key' => 'l_zip1',
                    'itemName' => '',
                    'errMsg' => 'ご住所は配達不可地域となっております',
                );
                $errInfoList[] = $errorInfo;
                return $errInfoList;
            }
        }
        return $errInfoList;
    }

    /**
     * 入力値バリデーション
     *
     * @return void
     */
    protected function checkMstInput($inputInfo = array(), $currShohin = array())
    {

        if (@empty($inputInfo)) {
            $inputInfo = $_POST;
        }

        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        $errInfoList = array();

        // shohin_cd 商品コード
        $v = Sgmov_Component_Validator::createSingleValueValidator($inputInfo['shohin_cd'])
            ->isNotEmpty()->isLengthLessThanOrEqualTo(30)->isLengthMoreThanOrEqualTo(5)->isNotHalfWidthKana()->isWebSystemNg();
        $errInfoList = $this->checkErrInfo($errInfoList, $v, 'shohin_cd', '商品コード');

        if (!empty($inputInfo['shohin_cd'])) {
            if (!preg_match("/^[A-Za-z0-9_! \"~#$%&'()\[\]{}|<>*+,\-.\\:\/;=?@^_]+$/", $inputInfo['shohin_cd'])) {
                $errorInfo = array(
                    'key' => 'shohin_cd',
                    'itemName' => '商品コード',
                    'errMsg' => "は半角英数字で入力してください。",
                );
                $errInfoList[] = $errorInfo;
            }
        }

        // shohin_name 商品名
        $v = Sgmov_Component_Validator::createSingleValueValidator($inputInfo['shohin_name'])
            ->isNotEmpty()->isLengthLessThanOrEqualTo(100)->isNotHalfWidthKana()->isWebSystemNg();
        $errInfoList = $this->checkErrInfo($errInfoList, $v, 'shohin_name', '商品名');

        $checkLength = Sgmov_Lib::getByteNumber($inputInfo['shohin_name']);
        if ($checkLength > 200) {
            $errorInfo = array(
                'key' => 'shohin_name',
                'itemName' => '',
                'errMsg' => "商品名は200バイト以内で入力してください。",
            );
            $errInfoList[] = $errorInfo;
        }

        // size サイズ
        $v = Sgmov_Component_Validator::createSingleValueValidator($inputInfo['size'])
            ->isNotEmpty();
        $errInfoList = $this->checkErrInfo($errInfoList, $v, 'size', 'サイズ');

        $size = explode('.', $inputInfo['size']);
        if (mb_strlen($inputInfo['size']) > 0) {
            if (count($size) == 2 && mb_strlen($size[1]) > 1) {
                $errorInfo = array(
                    'key' => 'size',
                    'itemName' => '',
                    'errMsg' => "サイズの小数点は1文字以内で入力してください。",
                );
                $errInfoList[] = $errorInfo;
            } else {
                if($inputInfo['size'] > 999.9) {
                    $errorInfo = array(
                        'key' => 'size',
                        'itemName' => '',
                        'errMsg' => "サイズは999.9以下の数値で入力してください。",
                    );
                    $errInfoList[] = $errorInfo;
                }
                if($inputInfo['size'] < 1.0) {
                    $errorInfo = array(
                        'key' => 'size',
                        'itemName' => '',
                        'errMsg' => "サイズは1.0以上の数値で入力してください。",
                    );
                    $errInfoList[] = $errorInfo;
                }
            }   
        }

        // option_id オプションid
        $v = Sgmov_Component_Validator::createSingleValueValidator($inputInfo['option_id'])
            ->isNotEmpty()->isInteger();
        $errInfoList = $this->checkErrInfo($errInfoList, $v, 'option_id', 'オプションid');

        // data_type データ種別
        $v = Sgmov_Component_Validator::createSingleValueValidator($inputInfo['data_type'])
            ->isNotEmpty()->isInteger();
        $errInfoList = $this->checkErrInfo($errInfoList, $v, 'data_type', 'データ種別');

        // juryo 重量
        $v = Sgmov_Component_Validator::createSingleValueValidator($inputInfo['juryo'])
            ->isNotEmpty()->isInteger();
        $errInfoList = $this->checkErrInfo($errInfoList, $v, 'juryo', '重量');
        
        if (mb_strlen($inputInfo['juryo']) > 0) {
            if ($inputInfo['juryo'] <= 0) {
                $errorInfo = array(
                    'key' => 'juryo',
                    'itemName' => '',
                    'errMsg' => "重量は1以上の数値で入力してください。",
                );
                $errInfoList[] = $errorInfo;
            }
            if ($inputInfo['juryo'] > 9999) {
                $errorInfo = array(
                    'key' => 'juryo',
                    'itemName' => '',
                    'errMsg' => "重量は9999以下の数値で入力してください。",
                );
                $errInfoList[] = $errorInfo;
            }
        }

        // start_date 適用開始日
        $v = Sgmov_Component_Validator::createSingleValueValidator($inputInfo['start_date'])
            ->isNotEmpty();
        $errInfoList = $this->checkErrInfo($errInfoList, $v, 'start_date', '適用開始日');

        // end_date 適用終了日
        $v = Sgmov_Component_Validator::createSingleValueValidator($inputInfo['end_date'])
            ->isNotEmpty();
        $errInfoList = $this->checkErrInfo($errInfoList, $v, 'end_date', '適用終了日');

        if (!empty($inputInfo['start_date']) && !empty($inputInfo['end_date'])) {
            $startDtArr = explode('-', $inputInfo['start_date']);
            $endDtArr = explode('-', $inputInfo['end_date']);
            $isValidEndDt = true;
            if ((int) $startDtArr[0] > (int) $endDtArr[0]) {
                $isValidEndDt = false;
            } else if ((int) $startDtArr[0] == (int) $endDtArr[0] && (int) $startDtArr[1] > (int) $endDtArr[1]) {
                $isValidEndDt = false;
            } else if ((int) $startDtArr[0] == (int) $endDtArr[0] && (int) $startDtArr[1] == (int) $endDtArr[1] && (int) $startDtArr[2] > (int) $endDtArr[2]) {
                $isValidEndDt = false;
            }

            if ($isValidEndDt === false) {
                $errorInfo = array(
                    'key' => 'start_date',
                    'itemName' => '',
                    'errMsg' => "適用開始日は適用終了日以前の日付としてください。",
                );
                $errInfoList[] = $errorInfo;
            }
        }

        // konposu 梱包数
        $v = Sgmov_Component_Validator::createSingleValueValidator($inputInfo['konposu'])
            ->isNotEmpty()->isInteger();
        $errInfoList = $this->checkErrInfo($errInfoList, $v, 'konposu', '梱包数');
        
        if (mb_strlen($inputInfo['konposu']) > 0) {
            if ($inputInfo['konposu'] <= 0) {
                $errorInfo = array(
                    'key' => 'konposu',
                    'itemName' => '',
                    'errMsg' => "梱包数は1以上の数値で入力してください。",
                );
                $errInfoList[] = $errorInfo;
            }
            if ($inputInfo['konposu'] > 999) {
                $errorInfo = array(
                    'key' => 'konposu',
                    'itemName' => '',
                    'errMsg' => "梱包数は999以下の数値で入力してください。",
                );
                $errInfoList[] = $errorInfo;
            }
        }

        if (empty($errInfoList)) {
            if (isset($currShohin['id'])) {
                $shohin = $this->_CostcoShohin->checkAvailableShohinCd($db, $inputInfo, $currShohin['id']);
            } else {
                $shohin = $this->_CostcoShohin->checkAvailableShohinCd($db, $inputInfo);
            }
            if (!empty($shohin)) {
                $errorInfo = array(
                    'key' => 'shohin_cd',
                    'itemName' => '',
                    'errMsg' => "商品コードは既に存在する為、登録できません。",
                );
                $errInfoList[] = $errorInfo;
            }
        }
        
        return $errInfoList;
    }
    
    /**
     * バリデーションのエラーを返す
     *
     * @param [type] $errInfoList
     * @param [type] $v
     * @param [type] $itemKey
     * @param [type] $itemName
     * @return void
     */
    protected function checkErrInfo($errInfoList, $v, $itemKey, $itemName)
    {
        if (!$v->isValid()) {
            $errorInfo = array(
                'key' => $itemKey,
                'itemName' => $itemName,
                'errMsg' => $v->getResultMessageTop(),
            );
            $errInfoList[] = $errorInfo;
        }
        return $errInfoList;
    }

    /**
     * リードタイム取得
     *
     * @return array
     */
    protected function getLeadTime($inputInfo = '')
    {

        if (@empty($inputInfo)) {
            $inputInfo = $_POST;
        }

        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        // 都道府県コード
        $inputJis2 = 13;
        if ($inputInfo['d_pref_id'] != '') {
            $inputJis2 = $inputInfo['d_pref_id'];
        }

        $leadTimeInfo = @$this->_CostcoLeadTime->getInfo($db, $inputInfo['c_event_id'], $inputInfo['c_eventsub_id'], $inputJis2);
        return $leadTimeInfo;
    }

    /**
     * GETで取得した店舗コードのチェック
     * 店舗コードが数字4桁以外はfalseを返す
     *
     * @param [type] $shopCode
     * @return bool
     */
    protected function checkQueryShopCode($shopCode)
    {

        $returnParam = false;

        if (!empty($shopCode)) {
            // 2022/03/29 現在 数字4桁が店舗コード
            if (preg_match('/^[0-9]{4}$/', $shopCode) === 1) {
                $returnParam = true;
            }
        }

        return $returnParam;
    }

    protected function getCostcoOptions() {
        $returnOpts = array(
            '' => '',
            '9999' => '9999：その他'
        );
        // DB接続
        $db = Sgmov_Component_DB::getPublic();
        $options = $this->_CostcoOption->getAll($db);
        if (!empty($options)) {
            foreach ($options as $val) {
                if (ctype_digit($val['option_type'])) {
                    $returnOpts[(int) $val['option_type']] = $val['option_type'] . '：' . $val['option_name'];
                }
            }
        }
        ksort($returnOpts);
        return $returnOpts;
    }
}
