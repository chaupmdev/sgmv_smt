<?php

/**
 * #@+
 * include files
 */
require_once dirname ( __FILE__ ) . '/../../Lib.php';
//Sgmov_Lib::useAllComponents ( FALSE );
Sgmov_Lib::useView('azk/Common', 'CommonConst');
Sgmov_Lib::useForms(array('Error', 'AzkSession', 'Azk003Out'));
Sgmov_Lib::useServices(array('Comiket', 'ComiketDetail', 'ComiketBox', 'CenterMail'));
Sgmov_Lib::useImageQRCode();

/**
 * #@-
 */
class Sgmov_View_Azk_ReSendMail extends Sgmov_View_Azk_Common {

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
     * 拠点メールアドレスサービス
     * @var Sgmov_Service_CenterMail
     */
    public $_centerMailService;



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


        $this->_Comiket             = new Sgmov_Service_Comiket();
        $this->_ComiketDetail       = new Sgmov_Service_ComiketDetail();
        $this->_ComiketBox          = new Sgmov_Service_ComiketBox();

        $this->_centerMailService   = new Sgmov_Service_CenterMail();

        $this->_Comiket->setTrnsactionFlg(FALSE);
        $this->_ComiketDetail->setTrnsactionFlg(FALSE);
        $this->_ComiketBox->setTrnsactionFlg(FALSE);

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

        // 20210518 vasi add
        // 申込ID制限
        if (!is_numeric($comiket_id)) {
            echo 'パラメータの申込IDは数文字を入力してください。';
            return;
        } else if ($comiket_id > 999999999) {
            echo 'パラメータの申込IDは桁数がオーバーしています。';
            return;
        } else if ($comiket_id < 0) {
            echo 'パラメータの申込IDは数文字を入力してください。';
            return;
        }

        // DBコネクション
        $db = Sgmov_Component_DB::getPublic();

		Sgmov_Component_Log::debug ('comiket_id = '.$comiket_id);

        // コミケ申込データの取得
        $comiketData = $this->getComiketData($db, $comiket_id);

        // 20210518 vasi add
        if (empty($comiketData)) {
            echo '対象のデータがみつかりません。';
            return;
        }
        
        // コミケ申込詳細データの取得
        $comiketDetailDataList = $this->getComiketDetailData($db, $comiket_id);

        foreach ($comiketDetailDataList as $key => $comiketDetailData) {
            $comiketBoxDataList = $this->getComiketBoxData($db, $comiket_id, $comiketDetailData['type']);
            foreach ($comiketBoxDataList as $boxDataKey => $comiketBoxData) {
                $comiketDetailDataList[$key]['comiket_box_list'][$boxDataKey] = $comiketBoxData;
            }
        }

        $comiketData['comiket_detail_list'] = $comiketDetailDataList;

        $sendTo = Sgmov_Component_Config::getEveCommonCompleteMail();

        $result = $this->sendCompleteMail($comiketData, $sendTo);
        
        if ($result) {
            // 管理者側にメール送信
            $mailTo = Sgmov_Component_Config::getComiketResendAdminMail();
          //  $result = $this->sendCompleteMail($comiketData, $mailTo);
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
        return $this->_ComiketBox->fetchComiketBoxDataListByIdAndTypeOrderByCd($db, $comiket_id, $type);
    }
}