<?php
/**
 * イベント輸送サービスのメールアドレスを変更します。
 * @package    /lib/view/eve
 * @author     Juj-Yamagami(SP)
 * @copyright  2018 Sp Media-Tec CO,.LTD. All rights reserved.
 */

/**
 * #@+
 * include files
 */
require_once dirname ( __FILE__ ) . '/../../Lib.php';
// イベント識別子(URLのpath)はマジックナンバーで記述せずこの変数で記述してください
$dirDiv=Sgmov_Lib::setDirDiv(dirname(__FILE__));
Sgmov_Lib::useAllComponents ( FALSE );
Sgmov_Lib::useView($dirDiv.'/Common', 'CommonConst');
Sgmov_Lib::useForms(array('Error', 'EveSession', 'Eve004Out'));
Sgmov_Lib::useServices(array('Comiket', 'ComiketDetail', 'ComiketBox', 'ComiketCargo', 'ComiketCharter'
    , 'CenterMail', 'SgFinancial', 'HttpsZipCodeDll', 'CargoFare', 'BoxFare', 'Charter'));
Sgmov_Lib::useImageQRCode();

/**
 * #@-
 */
class Sgmov_View_Eve_ReSendMail extends Sgmov_View_Eve_Common {

    /**
     * 都道府県サービス
     * @var Sgmov_Service_Prefecture
     */
    private $_PrefectureService;

    /**
     * コミケ申込データサービス
     * @var Sgmov_Service_Comiket
     */
    private $_Comiket;

    /**
     * コミケ申込明細データサービス
     * @var Sgmov_Service_Comiket
     */
    private $_ComiketDetail;

    /**
     * コミケ申込宅配データサービス
     * @var Sgmov_Service_Comiket
     */
    private $_ComiketBox;

    /**
     * コミケ申込カーゴデータサービス
     * @var Sgmov_Service_Comiket
     */
    private $_ComiketCargo;

    /**
     * コミケ申込貸切データサービス
     * @var Sgmov_Service_Comiket
     */
    private $_ComiketCharter;

    /**
     * イベントサービス
     * @var Sgmov_Service_Event
     */
    private $_EventService;

    /**
     * イベントサブサービス
     * @var Sgmov_Service_Event
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
     * カーゴ料金サービス
     * @var type
     */
    protected $_CargoFareService;

    /**
     * 館マスタサービス(ブース番号)
     * @var type
     */
    private $_BuildingService;

    /**
     * 拠点メールアドレスサービス
     * @var Sgmov_Service_CenterMail
     */
    public $_centerMailService;

    private $_BoxFareService;

    protected $_CharterService;

    // 識別子
    protected $_DirDiv;

    //--------------------------------------------------------------------------
    // DB区分の定数設定
    //--------------------------------------------------------------------------
    /** フラグ:OFF */
    const FLG_OFF                       = '0';
    /** フラグ:ON */
    const FLG_ON                        = '1';

    // コミケ申込データ(comiket)
    /** 支払区分:コンビニ前払い */
    const PAYMENT_METHOD_CONV_PREPAY    = '1';
    /** 支払区分:クレジットカード */
    const PAYMENT_METHOD_CREDIT_CARD    = '2';
    /** 支払区分:電子マネー */
    const PAYMENT_METHOD_ERE_MONEY      = '3';
    /** 支払区分:コンビニ後払い */
    const PAYMENT_METHOD_CONV_POSTPAY   = '4';
    /** 支払区分:法人申込 */
    const PAYMENT_METHOD_CORP           = '5';

    /** 支払先コンビニ区分:セブンイレブン */
    const CONV_STORE_SEVEN_ELEVEN       = '1';
    /** 支払先コンビニ区分:イーコンテクスト決済(ローソン、セイコーマート、ファミリーマート、サークルＫサンクス、ミニストップ) */
    const CONV_STORE_E_CONTEXT          = '2';
    /** 支払先コンビニ区分:その他(デイリーヤマザキ) */
    const CONV_STORE_OTHER              = '3';

    /** 識別:個人*/
    const DIV_KOJIN                     = '1';
    /** 識別:法人*/
    const DIV_HOJIN                     = '2';

    /** 選択:往路のみ */
    const CHOICE_OUTWARD_ONLY           = '1';
    /** 選択:復路のみ */
    const CHOICE_RETURN_ONLY            = '2';
    /** 選択:往復 */
    const CHOICE_BOTH                   = '3';

    // コミケ申込明細(comiket_detail)
    /** 往復区分:往路 */
    const TYPE_OUTWARD                  = '1';
    /** 往復区分:復路 */
    const TYPE_RETURN                   = '2';

    /** サービス区分:宅配 */
    const SERVICE_DELIVERY              = '1';
    /** サービス区分:カーゴ */
    const SERVICE_CARGO                 = '2';
    /** サービス区分:チャーター */
    const SERVICE_CHARTER               = '3';

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_PrefectureService   = new Sgmov_Service_Prefecture();
        $this->_EventService        = new Sgmov_Service_Event();
        $this->_EventsubService     = new Sgmov_Service_Eventsub();
        $this->_BuildingService     = new Sgmov_Service_Building();

        $this->_BoxService          = new Sgmov_Service_Box();
        $this->_BoxFareService      = new Sgmov_Service_BoxFare();
        $this->_CargoService        = new Sgmov_Service_Cargo();
        $this->_CargoFareService    = new Sgmov_Service_CargoFare();
        $this->_CharterService      = new Sgmov_Service_Charter();

        $this->_Comiket             = new Sgmov_Service_Comiket();
        $this->_ComiketDetail       = new Sgmov_Service_ComiketDetail();
        $this->_ComiketBox          = new Sgmov_Service_ComiketBox();
        $this->_ComiketCargo        = new Sgmov_Service_ComiketCargo();
        $this->_ComiketCharter      = new Sgmov_Service_ComiketCharter();

        $this->_centerMailService   = new Sgmov_Service_CenterMail();

        $this->_Comiket->setTrnsactionFlg(FALSE);
        $this->_ComiketDetail->setTrnsactionFlg(FALSE);
        $this->_ComiketBox->setTrnsactionFlg(FALSE);
        $this->_ComiketCargo->setTrnsactionFlg(FALSE);
        $this->_ComiketCharter->setTrnsactionFlg(FALSE);

        // 識別子のセット
        $this->_DirDiv = Sgmov_Lib::setDirDiv(dirname(__FILE__));

    }

    /**
	 * 処理
	 */
    public function executeInner() {
        Sgmov_Component_Log::info('イベント申込 メール再送信処理を実行します。');
		$comiket_id = filter_input(INPUT_GET, "comiket_id" );

        if (empty($comiket_id) || is_null($comiket_id)) {
            echo '申込み番号がNULLです。';
            return;
        }

        // DBコネクション
        $db = Sgmov_Component_DB::getAdmin();

		Sgmov_Component_Log::debug ('comiket_id = '.$comiket_id);

        // コミケ申込データの取得
        $comiketData = $this->getComiketData($db, $comiket_id);

        // コミケ申込詳細データの取得
        $comiketDetailDataList = $this->getComiketDetailData($db, $comiket_id);


        foreach ($comiketDetailDataList as $key => $comiketDetailData) {
            if ($comiketDetailData['service'] === self::SERVICE_DELIVERY) {
                $comiketBoxDataList = $this->getComiketBoxData($db, $comiket_id, $comiketDetailData['type']);
                foreach ($comiketBoxDataList as $boxDataKey => $comiketBoxData) {
                    $comiketDetailDataList[$key]['comiket_box_list'][$boxDataKey] = $comiketBoxData;
                }
            } else if ($comiketDetailData['service'] === self::SERVICE_CARGO) {
                $comiketCargoData = $this->getComiketCargoData($db, $comiket_id, $comiketDetailData['type']);
                $comiketDetailDataList[$key]['comiket_cargo_list'][0]   = $comiketCargoData;

            } else if ($comiketDetailData['service'] === self::SERVICE_CHARTER) {
                $comiketCharterData = $this->getComiketCharterData($db, $comiket_id, $comiketDetailData['type']);
                $comiketDetailDataList[$key]['comiket_charter_list'][0] = $comiketCharterData;

            }
        }

        $comiketData['comiket_detail_list'] = $comiketDetailDataList;

        // カーゴ、貸切時のムービング営業所CCメールアドレスを取得
        $ccMail = '';
        if ($comiketData['payment_method_cd'] === self::SERVICE_CARGO) {
            $ccMail = Sgmov_Component_Config::getComiketCargoFinMailCc();
        } else if ($comiketData['payment_method_cd'] === self::SERVICE_CHARTER) {
            $ccMail = Sgmov_Component_Config::getComiketCharterFinMailCc();
        }

        $type = $comiketDetailDataList[0]['type']; // 往路か復路かの区分

        $result = $this->sendCompleteMail($db, $comiketData, $ccMail, $type);

        if ($result) {
            echo '再送信の処理を行いました。';
        } else {
            echo '再送信の処理を行いました。';
        }

        Sgmov_Component_Log::info('イベント申込 メール再送信処理を終了します。');
		return;
	}

    /**
     * 完了メール送信
     * @param obj $db DBコネクション
     * @param array $comiket 設定用配列
     * @param string $sendCc 転送先
     * @param string $type 往復区分
     * @return bool true:成功
     */
    public function sendCompleteMail($db, $comiket, $sendCc = '', $type = '') {

        try {

            // 添付ファイルの有無を【コミケ申込データ(comiket).選択(choice)】で判別
            $isAttachment = ( $comiket['choice'] == self::CHOICE_RETURN_ONLY || $comiket['choice'] == self::CHOICE_BOTH ) ? true : false;
            // 宛先メールアドレス
            $sendTo = $comiket['mail'];
            // メールテンプレートにセットするデータ配列
            $data = array();
            //メールテンプレート(申込者用)
            $mailTemplate = array();
            //メールテンプレート(SGMV営業所用)
            $mailTemplateSgmv = array();

            $eventData = $this->_EventService->fetchEventById($db, $comiket['event_id']);
            $eventsubData = $this->_EventsubService->fetchEventsubByEventsubId($db, $comiket['eventsub_id']);

            //-------------------------------------------------
            //テンプレートデータ作成
            //-------------------------------------------------
            $isBoxOrCargoFlg = FALSE;

            $termFr = new DateTime($eventsubData["term_fr"]);
            $termTo = new DateTime($eventsubData["term_to"]);
            $termFrName = $termFr->format('Y年m月d日');
            $termToName = $termTo->format('Y年m月d日');
            $comiketPrefData = $this->_PrefectureService->fetchPrefecturesById($db, $comiket['pref_id']);

            if ($comiket['div'] == self::DIV_HOJIN) {
                //法人用メールテンプレート
                $mailTemplate[] = '/eve_complete_business.txt';

                // 2018.09.10 tahira add 申込者と営業所で使用するメールテンプレートをわけ別々に送信する
                $mailTemplateSgmv[] = '/eve_complete_business_sgmv.txt';

                $data['comiket_id'] = sprintf('%010d', $comiket['id']);
                $data['surname'] = $comiket['office_name'];
                $data['forename'] = "";//$comiket['personal_name_mei'];
                $data['event_name'] = $eventData["name"] . " " . $eventsubData["name"]; //【出展イベント】
                $data['place_name'] = $eventsubData["venue"];//"〒" . $eventsubData["zip"] . " " . $eventsubData["address"]; //【場所】
                $data['period_name'] = $termFrName . " ～ " . $termToName; //【期間】
                $data['comiket_div'] = $this->comiket_div_lbls[intval($comiket['div'])];
                $data['comiket_customer_cd'] = $comiket['customer_cd'];
                $data['comiket_office_name'] = $comiket['office_name'];
                $data['comiket_zip'] = "〒" . substr($comiket['zip'], 0, 3) . '-' . substr($comiket['zip'], 3);
                $data['comiket_pref_name'] = $comiketPrefData['name'];
                $data['comiket_address'] = $comiket['address'];
                $data['comiket_building'] = $comiket['building'];
                $data['comiket_tel'] = $comiket['tel'];
                $data['comiket_mail'] = $comiket['mail'];
                if ($eventData['id'] === '10') {  // 国内クルーズ
                    $data['comiket_booth_name'] = PHP_EOL . '【部屋番号】' . $comiket['booth_name'];
                } else {
                    $data['comiket_booth_name'] = PHP_EOL . '【ブース名】' . $comiket['booth_name'];
                }
                $data['comiket_building_name'] = PHP_EOL . '【館名】' . $comiket['building_name'] . " " . $comiket['booth_position'] . " " . $comiket['booth_num'];
                if ($eventsubData['booth_display'] == self::FLG_OFF) {
                    $data['comiket_booth_name'] = '';
                }
                if ($eventsubData['building_display'] == self::FLG_OFF) {
                    $data['comiket_building_name'] = '';
                }
                $data['comiket_staff_seimei'] = $comiket['staff_sei'] . " " . $comiket['staff_mei'];
                $data['comiket_staff_seimei_furi'] = $comiket['staff_sei_furi'] . " " . $comiket['staff_mei_furi'];
                $data['comiket_staff_tel'] = $comiket['staff_tel'];
                $comiketDetailTypeLbls = $this->comiket_detail_type_lbls;
                $data['comiket_choice'] = $comiketDetailTypeLbls[$comiket['choice']];

                $data['comiket_payment_method'] = "売掛";
            } else {
                //個人用メールテンプレート
                $mailTemplate[] = '/eve_complete_individual.txt';

                // 2018.09.10 tahira add 申込者と営業所で使用するメールテンプレートをわけ別々に送信する
                $mailTemplateSgmv[] = '/eve_complete_individual_sgmv.txt';

                $data['comiket_id'] = sprintf('%010d', $comiket['id']);
                $data['surname'] = $comiket['personal_name_sei'];
                $data['forename'] = $comiket['personal_name_mei'];
                $data['event_name'] = $eventData["name"] . " " . $eventsubData["name"]; //【出展イベント】
                $data['place_name'] = $eventsubData["venue"];//"〒" . $eventsubData["zip"] . " " . $eventsubData["address"]; //【場所】
                $data['period_name'] = $termFrName . " ～ " . $termToName; //【期間】

                $data['comiket_div'] = '出展者'; // デザインフェスタ
                if($eventData['id'] === '2') { // コミケ
                    $data['comiket_div'] = '電子決済の方(クレジット、コンビニ決済、電子マネー)';
                }

                $data['comiket_personal_name_seimei'] = $comiket['personal_name_sei'] . " " . $comiket['personal_name_mei'];
                $data['comiket_zip'] = "〒" . substr($comiket['zip'], 0, 3) . '-' . substr($comiket['zip'], 3);
                $data['comiket_pref_name'] = $comiketPrefData['name'];
                $data['comiket_address'] = $comiket['address'];
                $data['comiket_building'] = $comiket['building'];
                $data['comiket_tel'] = $comiket['tel'];
                $data['comiket_mail'] = $comiket['mail'];

                if ($eventData['id'] === '10') {  // 国内クルーズ
                    $data['comiket_booth_name'] = PHP_EOL . '【部屋番号】' . $comiket['booth_name'];
                } else {
                    $data['comiket_booth_name'] = PHP_EOL . '【ブース名】' . $comiket['booth_name'];
                }

                if($eventData['id'] === '2') { // コミケ
                    $data['comiket_building_name'] = PHP_EOL . '【ブース番号】' . $comiket['building_name'] . " " . $comiket['booth_position'] . " " . $comiket['booth_num'];
                } else {
                    $data['comiket_building_name'] = PHP_EOL . '【館名】' . $comiket['building_name'] . " " . $comiket['booth_position'] . " " . $comiket['booth_num'];
                }

                if ($eventsubData['booth_display'] == self::FLG_OFF) {
                    $data['comiket_booth_name'] = '';
                }
                if ($eventsubData['building_display'] == self::FLG_OFF) {
                    $data['comiket_building_name'] = '';
                }
                $data['comiket_staff_seimei'] = $comiket['staff_sei'] . " " . $comiket['staff_mei'];
                $data['comiket_staff_seimei_furi'] = $comiket['staff_sei_furi'] . " " . $comiket['staff_mei_furi'];
                $data['comiket_staff_tel'] = $comiket['staff_tel'];

                $comiketDetailTypeLbls = $this->comiket_detail_type_lbls;
                $data['comiket_choice'] = $comiketDetailTypeLbls[$comiket['choice']];

                $data['convenience_store'] = "";
                $attention_message = '';
                if ($comiket['payment_method_cd'] == self::PAYMENT_METHOD_CONV_PREPAY) { // お支払い方法 = コンビニ前払
                    $convenienceStoreLbls = $this->convenience_store_lbls;
                    $convenienceStoreCd = (int) $comiket['convenience_store_cd'];
                    $data['convenience_store'] = " （" . $convenienceStoreLbls[$convenienceStoreCd] . "）"
                            . PHP_EOL . "【受付番号】{$comiket['receipt_cd']}";

                    //払込票URL
                    if(!empty($comiket['haraikomi_url'])) {
                        $data['convenience_store'] .= PHP_EOL . "【払込票URL】{$comiket['haraikomi_url']}";
                    }
                    // 申込区分が【往路】ならば注意文言を表示する
                    if ($type == self::TYPE_OUTWARD) {
                        $attention_message = '※お支払いはお預かり日時の前日までに入金していただきますようお願いいたします。';
                    }
                }
                $data['attention_message'] = $attention_message;

                $data['convenience_store_late'] = "";
                if($comiket['payment_method_cd'] == self::PAYMENT_METHOD_CONV_POSTPAY) { // お支払い方法 = コンビニ後払
                    $data['convenience_store_late'] =
                            "【ご購入店受注番号】{$comiket['kounyuten_no']}"
                    . PHP_EOL . "【お問合せ番号】{$comiket['transaction_id']}";
                }

                $paymentMethodLbls = $this->payment_method_lbls;
                $data['comiket_payment_method'] = $paymentMethodLbls[$comiket['payment_method_cd']] . $data['convenience_store'];

                $data['digital_money_attention_note'] = "";
                if($comiket['payment_method_cd'] == self::PAYMENT_METHOD_ERE_MONEY) { // 電子マネー
                    $data['digital_money_attention_note'] = "※ イベント当日、受付にて電子マネーでの決済をお願いします。";
                }
            }

            $comiket_detail_list = $comiket['comiket_detail_list'];
            foreach ($comiket_detail_list as $k => $comiket_detail) {

                //サービスごとの数量表示
                $num_area = '';
                switch ($comiket_detail['service']) {
                    case self::SERVICE_DELIVERY :   //宅配
                        $num_area .= '【宅配数量】' . PHP_EOL;
                        $comiket_box_list = (isset($comiket_detail['comiket_box_list'])) ? $comiket_detail['comiket_box_list'] : array();
                        foreach ($comiket_box_list as $cb => $comiket_box) {
                            $boxInfo = $this->_BoxService->fetchBoxById($db, $comiket_box['box_id']);

                            if (($comiket_detail['type'] == self::TYPE_RETURN && $comiket_box['type'] == self::TYPE_RETURN)
                                    || ($comiket_detail['type'] == self::TYPE_OUTWARD && $comiket_box['type'] == self::TYPE_OUTWARD)) {
                                $num_area .= '    ' . $boxInfo['name'] . ' ' . $comiket_box['num'] . ' 個' . PHP_EOL;
                            }
                        }
                        $isBoxOrCargoFlg = TRUE;
                        break;
                    case self::SERVICE_CARGO :      //カーゴ
                        $num_area .= '【カーゴ数量】' . PHP_EOL;
                        $comiket_cargo_list = (isset($comiket_detail['comiket_cargo_list'])) ? $comiket_detail['comiket_cargo_list'] : array();
                        foreach ($comiket_cargo_list as $cb => $comiket_cargo) {

                            if (($comiket_detail['type'] == self::TYPE_RETURN && $comiket_cargo['type'] == self::TYPE_RETURN)
                                    || ($comiket_detail['type'] == self::TYPE_OUTWARD && $comiket_cargo['type'] == self::TYPE_OUTWARD)) {
                                $num_area .= '    ' . $comiket_cargo['num'] . ' 台' . PHP_EOL;
                            }
                        }
                        $isBoxOrCargoFlg = TRUE;
                        $num_area .= '【顧客管理番号】' . $comiket_detail['cd'] . PHP_EOL;
                        break;
                    case self::SERVICE_CHARTER :    //貸切
                        $num_area .= '【貸切台数】' . PHP_EOL;
                        $comiket_charter_list = (isset($comiket_detail['comiket_charter_list'])) ? $comiket_detail['comiket_charter_list'] : array();
                        foreach ($comiket_charter_list as $cb => $comiket_charter) {

                            if (($comiket_detail['type'] == self::TYPE_RETURN && $comiket_charter['type'] == self::TYPE_RETURN)
                                    || ($comiket_detail['type'] == self::TYPE_OUTWARD && $comiket_charter['type'] == self::TYPE_OUTWARD)) {
                                $num_area .= '    ' . $comiket_charter['name'] . ' ' . $comiket_charter['num'] . ' 台' . PHP_EOL;
                            }
                        }
                        $num_area .= '【顧客管理番号】' . $comiket_detail['cd'] . PHP_EOL;
                        break;
                }

                if(empty($comiket_detail['collect_date'])) {
                    $collectDateName = "";
                } else {
                    $collectDate = new DateTime($comiket_detail['collect_date']);
                    $collectDateName = $collectDate->format('Y年m月d日');
                }

                if(empty($comiket_detail['delivery_date'])) {
                    $deliveryDateName = "";
                } else {
                    $deliveryDate = new DateTime($comiket_detail['delivery_date']);
                    $deliveryDateName = $deliveryDate->format('Y年m月d日');
                }

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

                if(empty($comiket_detail['delivery_st_time'])
                        || $comiket_detail['delivery_st_time'] == "00") {
                    $deliveryStTimeName = "指定なし";
                    $deliveryEdTimeName = "";
                } else {
                    $deliveryStTime = new DateTime($comiket_detail['delivery_st_time']);
                    $deliveryEdTime = new DateTime($comiket_detail['delivery_ed_time']);
                    $deliveryStTimeName = $deliveryStTime->format("H:i") . "～";
                    $deliveryEdTimeName = $deliveryEdTime->format("H:i");
                }

                $serviceNum = (int) $comiket_detail['service'];
                $serviceName = $this->comiket_detail_service_lbls[$serviceNum];

                if ($comiket_detail['type'] == self::TYPE_RETURN) {
                    $prefData = $this->_PrefectureService->fetchPrefecturesById($db, $comiket_detail['pref_id']);
                    //搬出用メールテンプレート
                    $mailTemplate[] = '/eve_parts_complete_choice_2.txt';

                    $mailTemplateSgmv[] = '/eve_parts_complete_choice_2_sgmv.txt';

                    $data['type2_name'] = $comiket_detail['name'];                      //【配送先名】
                    $data['type2_zip'] = "〒" . substr($comiket_detail['zip'], 0, 3)
                            . '-' . substr($comiket_detail['zip'],3);                   //【配送先郵便番号】
                    $data['type2_pref'] = $prefData["name"];                            //【都道府県】
                    $data['type2_address'] = $comiket_detail['address'];                //【市町村区】
                    $data['type2_building'] = $comiket_detail['building'];              //【建物番地名】
                    $data['type2_tel'] = $comiket_detail['tel'];                        //【配送先電話番号】
                    $data['type2_collect_date'] = $collectDateName;                     //【お預かり日時】
                    $data['type2_collect_st_time'] = $collectStTimeName;                //【お預かり開始時刻】
                    $data['type2_collect_ed_time'] = $collectEdTimeName;                //【お預かり終了時刻】
                    $data['type2_delivery_date'] = $deliveryDateName;                   //【お届け日時】
                    $data['type2_delivery_st_time'] = $deliveryStTimeName;              //【お届け開始時刻】
                    $data['type2_delivery_ed_time'] = $deliveryEdTimeName;              //【お届け終了時刻】
                    $data['type2_service'] = $serviceName;                              //【サービス選択】
                    $data['type2_num_area'] = $num_area;                                //【数量】
                    $data['type2_note'] = $comiket_detail['note'];                      //【備考】
                } else {
                    $prefData = $this->_PrefectureService->fetchPrefecturesById($db, $comiket_detail['pref_id']);
                    //搬入用メールテンプレート
                    $mailTemplate[] = '/eve_parts_complete_choice_1.txt';

                    $mailTemplateSgmv[] = '/eve_parts_complete_choice_1_sgmv.txt';

                    $data['type1_name'] = $comiket_detail['name'];                      //【集荷先名】
                    $data['type1_zip'] =  "〒" . substr($comiket_detail['zip'], 0, 3)
                            . '-' . substr($comiket_detail['zip'], 3);                  //【集荷先郵便番号】
                    $data['type1_pref'] = $prefData["name"];                            //【都道府県】
                    $data['type1_address'] = $comiket_detail['address'];                //【市町村区】
                    $data['type1_building'] = $comiket_detail['building'];              //【建物番地名】
                    $data['type1_tel'] = $comiket_detail['tel'];                        //【集荷先電話番号】
                    $data['type1_collect_date'] = $collectDateName;                     //【お預かり日時】
                    $data['type1_collect_st_time'] = $collectStTimeName;                //【お預かり開始時刻】
                    $data['type1_collect_ed_time'] = $collectEdTimeName;                //【お預かり終了時刻】
                    $data['type1_delivery_date'] = $deliveryDateName;                   //【お届け日時】
                    $data['type1_delivery_st_time'] = $deliveryStTimeName;              //【お届け開始時刻】
                    $data['type1_delivery_ed_time'] = $deliveryEdTimeName;              //【お届け終了時刻】
                    $data['type1_service'] = $serviceName;                              //【サービス選択】
                    $data['type1_num_area'] = $num_area;                                //【数量】
                    $data['type1_note'] = $comiket_detail['note'];                      //【備考】
                }
            }

            //フッター用メールテンプレート
            if ($comiket['div'] == self::DIV_HOJIN) { // 法人
                $mailTemplate[] = '/eve_parts_complete_footer_type_1.txt';
                $mailTemplateSgmv[] = '/eve_parts_complete_footer_type_1.txt';
            } else { // 個人
                $mailTemplate[] = '/eve_parts_complete_footer_type_2.txt';
                $mailTemplateSgmv[] = '/eve_parts_complete_footer_type_2.txt';
            }

            $data['comiket_amount'] = '\\' . number_format($comiket['amount']);
            $data['comiket_amount_tax'] = '\\' . number_format($comiket['amount_tax']);
            $urlPublicSsl = Sgmov_Component_Config::getUrlPublicSsl();
            $comiketIdCheckD = self::getChkD(sprintf("%010d", $comiket['id']));
            $data['edit_url'] = $urlPublicSsl . "/".$this->_DirDiv."/input/" . sprintf('%010d', $comiket["id"]) . $comiketIdCheckD;

            // 説明書URL
            $data['manual_url'] = '';
            if($eventsubData['manual_display'] == self::FLG_ON) { // 説明書表示フラグが立っているならURLを追加
                $data['manual_url'] = "【説明書URL】" . PHP_EOL . $urlPublicSsl . "/".$this->_DirDiv."/pdf/manual/{$eventData['name']}{$eventsubData['name']}.pdf";
            }

            $data['paste_tag_url'] = "";
            if(($comiket['choice'] == self::CHOICE_OUTWARD_ONLY || $comiket['choice'] == self::CHOICE_BOTH)
                    && $isBoxOrCargoFlg && $eventsubData['paste_display'] == self::FLG_ON)  {
                // 貼付票URL
                $pasteTagId = sprintf("%010d", $comiket['id']) . self::getChkD2($comiket['id']);
                $data['paste_tag_url'] = "【貼付票URL】" . PHP_EOL . $urlPublicSsl . "/".$this->_DirDiv."/paste_tag/{$pasteTagId}/";
            }

            $data['explanation_url_convenience_payment_method'] = "";
            if($comiket['payment_method_cd'] == self::PAYMENT_METHOD_CONV_PREPAY) { // お支払い方法 = コンビニ前払
                    $data['explanation_url_convenience_payment_method'] =
"
以下のURLから各コンビニの支払い方法をご確認いただけます。
・セブンイレブン
https://www.sagawa-mov.co.jp/cvs/pc/711.html
・ローソン
https://www.sagawa-mov.co.jp/cvs/pc/lawson.html
・セイコーマート
https://www.sagawa-mov.co.jp/cvs/pc/seicomart.html
・ファミリーマート
https://www.sagawa-mov.co.jp/cvs/pc/famima2.html
・サークルＫサンクス
https://www.sagawa-mov.co.jp/cvs/pc/circleksunkus_econ.html
・ミニストップ
https://www.sagawa-mov.co.jp/cvs/pc/ministop_loppi.html
・デイリーヤマザキ
https://www.sagawa-mov.co.jp/cvs/pc/dailyamazaki.html
";
            }

            //-------------------------------------------------
            //メール送信実行
            $objMail = new Sgmov_Service_CenterMail();
            if (!$isAttachment) {
                // 申込者へメール
                $sendResult = $objMail->_sendThankYouMail($mailTemplate, $sendTo, $data);

                if ($sendCc !== null && $sendCc !== '') {
                    $objMail->_sendThankYouMail($mailTemplateSgmv, $sendCc, $data);
                }
            } else {
                // qrコードファイル出力
                $qr = new Image_QRCode();

                $image = $qr->makeCode(htmlspecialchars($comiket["id"]),
                                       array('output_type' => 'return', "module_size"=> 10));
                imagepng($image, dirname(__FILE__) . "/tmp/qr{$comiket["id"]}.png");
                imagedestroy($image);

                $attachment = dirname(__FILE__) . '/tmp/qr' . $comiket['id'] . '.png';
                $attach_mime_type = 'image/png';
                // 申込者へメール
                $sendResult = $objMail->_sendThankYouMailAttached($mailTemplate, $sendTo, $data, $attachment, $attach_mime_type);

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

        // 送信結果がNGなら false で return
        if (!$sendResult) {
            return false;
        }

        return true;
    }

    /**
     * コミケ申込データを取得
     * @param object $db
     * @param string $comiket_id
     * @return type
     */
	private function getComiketData($db, $comiket_id) {
        return $this->_Comiket->fetchComiketById($db, $comiket_id);
    }

    private function getComiketDetailData($db, $comiket_id) {
        return $this->_ComiketDetail->fetchComiketDetailByComiketId($db, $comiket_id);
    }

    private function getComiketBoxData($db, $comiket_id, $type) {
        return $this->_ComiketBox->fetchComiketBoxDataListByIdAndType($db, $comiket_id, $type);
    }

    private function getComiketCargoData($db, $comiket_id, $type) {
        return $this->_ComiketCargo->fetchComiketCargoDataListByIdAndType($db, $comiket_id, $type);
    }

    private function getComiketCharterData($db, $comiket_id, $type) {
        return $this->_ComiketCharter->fetchComiketCharterDataListByIdAndType($db, $comiket_id, $type);
    }
}