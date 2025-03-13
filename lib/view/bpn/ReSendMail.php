<?php
/**
 * 物販お申し込みで登録完了メールを再送信する
 * @package    /lib/view/bpn
 * @author     Juj-Yamagami(SP)
 * @copyright  2018 Sp Media-Tec CO,.LTD. All rights reserved.
 */

/**
 * #@+
 * include files
 */
require_once dirname ( __FILE__ ) . '/../../Lib.php';
Sgmov_Lib::useView('bpn/Common', 'CommonConst');
Sgmov_Lib::useForms(array('Error', 'BpnSession', 'Bpn004Out'));
Sgmov_Lib::useServices(array('Comiket', 'ComiketDetail', 'ComiketBox', 'Shohin'
    , 'CenterMail', 'SgFinancial', 'HttpsZipCodeDll', 'CargoFare', 'Charter'));
Sgmov_Lib::useImageQRCode();

/**
 * #@-
 */
class Sgmov_View_Bpn_ReSendMail extends Sgmov_View_Bpn_Common {

    /**
     * 都道府県サービス
     * @var Sgmov_Service_Prefecture
     */
    protected $_PrefectureService;

    /**
     * コミケ申込データサービス
     * @var Sgmov_Service_Comiket
     */
    protected $_Comiket;

    /**
     * コミケ申込明細データサービス
     * @var Sgmov_Service_Comiket
     */
    protected $_ComiketDetail;

    /**
     * コミケ申込宅配データサービス
     * @var Sgmov_Service_Comiket
     */
    protected $_ComiketBox;

    /**
     * コミケ申込カーゴデータサービス
     * @var Sgmov_Service_Comiket
     */
    protected $_ComiketCargo;

    /**
     * コミケ申込貸切データサービス
     * @var Sgmov_Service_Comiket
     */
    protected $_ComiketCharter;

    /**
     * イベントサービス
     * @var Sgmov_Service_Event
     */
    protected $_EventService;

    /**
     * イベントサブサービス
     * @var Sgmov_Service_Event
     */
    protected $_EventsubService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_PrefectureService   = new Sgmov_Service_Prefecture();
        $this->_EventService        = new Sgmov_Service_Event();
        $this->_EventsubService     = new Sgmov_Service_Eventsub();

        $this->_Comiket             = new Sgmov_Service_Comiket();
        $this->_ComiketDetail       = new Sgmov_Service_ComiketDetail();
        $this->_ComiketBox          = new Sgmov_Service_ComiketBox();

        $this->_Comiket->setTrnsactionFlg(FALSE);
        $this->_ComiketDetail->setTrnsactionFlg(FALSE);
        $this->_ComiketBox->setTrnsactionFlg(FALSE);

        parent::__construct();
    }

    /**
     * 処理
     */
    public function executeInner() {
        Sgmov_Component_Log::info('物販 メール再送信処理を実行します。');
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
            $comiketBoxDataList = $this->getComiketBoxData($db, $comiket_id, $comiketDetailData['type']);
            foreach ($comiketBoxDataList as $boxDataKey => $comiketBoxData) {
                $comiketDetailDataList[$key]['comiket_box_list'][$boxDataKey] = $comiketBoxData;
            }
        }

        $comiketData['comiket_detail_list'] = $comiketDetailDataList;

        // CC
        $ccMail = '';
        if($comiketData["bpn_type"] == "2"){
            // 当日物販
            $result = $this->sendCompleteMailForActiveShohin($comiketData, $comiketData['mail'], $ccMail);
        }else{
            // 物販
            $result = $this->sendCompleteMail($comiketData, $comiketData['mail'], $ccMail);
        }

        if ($result) {
            // 管理者側にメール送信
            $mailTo = Sgmov_Component_Config::getComiketResendAdminMail();
            if($comiketData["bpn_type"] == "2"){
                // 当日物販
                $result = $this->sendCompleteMailForActiveShohin($comiketData, $mailTo, $ccMail);
            }else{
                // 物販
                $result = $this->sendCompleteMail($comiketData, $mailTo, $ccMail);
            }
        }
    
        if ($result) {
            echo '再送信の処理を行いました。1';
        } else {
            echo '再送信の処理を行いました。2';
        }

        Sgmov_Component_Log::info('物販 メール再送信処理を終了します。');
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
}