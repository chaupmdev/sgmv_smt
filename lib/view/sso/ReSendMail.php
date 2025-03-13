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
//Sgmov_Lib::useAllComponents ( FALSE );
Sgmov_Lib::useView('sso/Common', 'CommonConst');
Sgmov_Lib::useForms(array('Error', 'EveSession', 'Eve004Out'));
Sgmov_Lib::useServices(array('Comiket', 'ComiketDetail', 'ComiketBox', 'ComiketCargo', 'ComiketCharter'
    , 'CenterMail', 'SgFinancial', 'HttpsZipCodeDll', 'CargoFare', 'BoxFare', 'Charter'));
Sgmov_Lib::useImageQRCode();

/**
 * #@-
 */
class Sgmov_View_Sso_ReSendMail extends Sgmov_View_Sso_Common {

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
    /** 支払先コンビニ区分:イーコンテクスト決済(ローソン、セイコーマート、ファミリーマート、ミニストップ) */
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
        
        parent::__construct();
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
        $db = Sgmov_Component_DB::getPublic();

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
//        if ($comiketData['payment_method_cd'] === self::SERVICE_CARGO) {
//            $ccMail = Sgmov_Component_Config::getComiketCargoFinMailCc();
//        } else if ($comiketData['payment_method_cd'] === self::SERVICE_CHARTER) {
//            $ccMail = Sgmov_Component_Config::getComiketCharterFinMailCc();
//        }

        $type = $comiketDetailDataList[0]['type']; // 往路か復路かの区分
        $result = $this->sendCompleteMail($comiketData, $comiketData['mail'], '', $type);

        if ($result) {
            // 管理者側にメール送信
            $mailTo = Sgmov_Component_Config::getComiketResendAdminMail();
            $result = $this->sendCompleteMail($comiketData, $mailTo, '', $type);
        }

        if ($result) {
            echo '再送信の処理を行いました。1';
        } else {
            echo '再送信の処理を行いました。2';
        }

        Sgmov_Component_Log::info('イベント申込 メール再送信処理を終了します。');

		return;
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