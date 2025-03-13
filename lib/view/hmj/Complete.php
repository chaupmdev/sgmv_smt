<?php
/**
 * @package    ClassDefFile
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
// イベント識別子(URLのpath)はマジックナンバーで記述せずこの変数で記述してください
$dirDiv=Sgmov_Lib::setDirDiv(dirname(__FILE__));
Sgmov_Lib::useView($dirDiv.'/Common');
Sgmov_Lib::useView($dirDiv.'/Bcm');
Sgmov_Lib::useForms(array('Error', 'EveSession', 'Eve004Out', ));
Sgmov_Lib::useServices(array('Comiket', 'ComiketDetail', 'ComiketBox', 'ComiketCargo', 'ComiketCharter'
    , 'CenterMail', 'SgFinancial', 'HttpsZipCodeDll', 'CargoFare', 'BoxFare', 'Charter', 'ComiketKanren', 'GyomuApi'));
Sgmov_Lib::useImageQRCode();
Sgmov_Lib::use3gMdk(array('3GPSMDK'));
/**#@-*/

/**
 * イベント手荷物受付サービスのお申し込み内容を登録し、完了画面を表示します。
 * @package    View
 * @subpackage DSN
 * @author     K.Sawada
 * @copyright  2018-2019 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Dsn_Complete extends Sgmov_View_Dsn_Common {

    const SEVEN_ELEVEN_CODE = 'sej';
    const E_CONTEXT_CODE    = 'econ';
    const WELL_NET_CODE     = 'other';

    /**
     * 支払方法：コンビニ後払い
     */
    const PAYMENT_METHOD_CONVINI_AFTER = 4;

    /**
     * 共通サービス
     * @var Sgmov_Service_AppCommon
     */
    private $_appCommon;

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
     * コミケ申込IDデータサービス
     * @var Sgmov_Service_ComiketKanren
     */
    private $_ComiketKanren;

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
     * sgFinancialサービス
     * @var type
     */
    private $_SgFinancial;

    /**
     * 拠点メールアドレスサービス
     * @var Sgmov_Service_CenterMail
     */
    public $_centerMailService;
    
    /**
     * 郵便番号DLLサービス
     * @var Sgmov_Service_HttpsZipCodeDll
     */
    protected $_HttpsZipCodeDll;

    /**
     * 拠点メールアドレスサービス
     * @var Sgmov_Service_CenterMail
     */
    public $_gyomuApiService;

    /**
     * 業務連携
     */
    protected $_BcmView;
    
    /**
     * 往路・集荷日範囲計算サービス
     * @var Sgmov_Service_Travel
     */
    public $_OutBoundUnCollectCal;

    // 識別子
    protected $_DirDiv;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_appCommon           = new Sgmov_Service_AppCommon();
        $this->_PrefectureService   = new Sgmov_Service_Prefecture();
        $this->_EventService        = new Sgmov_Service_Event();
        $this->_EventsubService     = new Sgmov_Service_Eventsub();
        $this->_BoxService          = new Sgmov_Service_Box();
        $this->_CargoService        = new Sgmov_Service_Cargo();
        $this->_CargoFareService    = new Sgmov_Service_CargoFare();
        $this->_BuildingService     = new Sgmov_Service_Building();

        $this->_Comiket             = new Sgmov_Service_Comiket();
        $this->_ComiketDetail       = new Sgmov_Service_ComiketDetail();
        $this->_ComiketBox          = new Sgmov_Service_ComiketBox();
        $this->_ComiketCargo        = new Sgmov_Service_ComiketCargo();
        $this->_ComiketCharter      = new Sgmov_Service_ComiketCharter();

        $this->_ComiketKanren       = new Sgmov_Service_ComiketKanren();

        $this->_SgFinancial         = new Sgmov_Service_SgFinancial();
        $this->_centerMailService   = new Sgmov_Service_CenterMail();
        $this->_HttpsZipCodeDll     = new Sgmov_Service_HttpsZipCodeDll();
        $this->_gyomuApiService     = new Sgmov_Service_GyomuApi();

        $this->_BcmView             = new Sgmov_View_Dsn_Bcm();

        $this->_Comiket->setTrnsactionFlg(FALSE);
        $this->_ComiketDetail->setTrnsactionFlg(FALSE);
        $this->_ComiketBox->setTrnsactionFlg(FALSE);
        $this->_ComiketCargo->setTrnsactionFlg(FALSE);
        $this->_ComiketCharter->setTrnsactionFlg(FALSE);
        
        $this->_OutBoundUnCollectCal = new Sgmov_Service_OutBoundCollectCal();

        // 識別子のセット
        $this->_DirDiv = Sgmov_Lib::setDirDiv(dirname(__FILE__));

        parent::__construct();
    }
    
    /**
     * 処理を実行します。
     * <ol><li>
     * セッションの継続を確認
     * </li><li>
     * チケットの確認と破棄
     * </li><li>
     * 入力チェック
     * </li><li>
     * セッションから情報を取得
     * </li><li>
     * 情報をDBへ格納
     * </li><li>
     * 出力情報を設定
     * </li><li>
     * セッション情報を破棄
     * </li></ol>
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['outForm']:出力フォーム
     * </li></ul>
     */
    public function executeInner($argBackInputPaht="") {
        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();

        //チケットの確認と破棄 TODO
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_DSN003, $this->_getTicket());

        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        // セッションから情報を取得
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        if (empty($sessionForm)) {
            $sessionForm = new Sgmov_Form_EveSession();
        }
        
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////
        // comiket登録前に業務側から請求先問番取得
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////
        $toiawaseNo = $this->getToiawaseNo();
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        $inForm = $this->_createDataByInForm($db, $sessionForm->in, $toiawaseNo);

        $tempArray = (array)$inForm;

        $exitsComiketId = "";
        if(!empty($tempArray["comiket_id"])){
            $comiketIdCheckD = $this->getChkD(sprintf("%010d", $tempArray["comiket_id"]));
            $exitsComiketId = sprintf('%010d', $tempArray["comiket_id"]) . $comiketIdCheckD;
       }

        // 搬入出の申込期間チェック
        $this->checkCurrentDateWithInTerm($inForm);
        
        // イベント情報
        $eventData = $this->_EventService->fetchEventById($db, $inForm["event_sel"]);

        // イベントサブ情報
        $eveSubData = $this->_EventsubService->fetchEventsubByEventsubId($db, $inForm['eventsub_sel']);


        /////////////////////////////////////////////////////////////////////////////////////////////
        // 搬入-申込Stチェック
        /////////////////////////////////////////////////////////////////////////////////////////////
        if($inForm['comiket_detail_type_sel'] == "1") { // 搬入
            $this->checkHannyuArrivalDate($db, $inForm, $eveSubData, $session);
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////
        // DB登録時に必要なデータをセットする
        ///////////////////////////////////////////////////////////////////////////////////////////////
        $this->setYubinDllInfoToInForm($inForm);

        // 搬入、搬出、搬入＋搬出
        $outForm = new Sgmov_Form_Eve004Out();

        //登録用IDを取得
        $comiketId = $this->_Comiket->select_id($db);

        // 往復
        $sessionForm->comiketId = $comiketId;

        // ベリトランス決済処理
        $registerData = $this->payment($db, $inForm, $comiketId);

        // エラーが発生した場合の戻る画面指定
        // サイズ変更からきた場合は$argBackInputPahtに'size_change'がセット
        // 通常の申込はカラ。ベリトランスの決済の成否にかかわらず
        //戻り先のpathだけ決定してcomiketテーブルに登録にいく
        $backInputPath = $argBackInputPaht;
        if (@empty($backInputPath)) {
            $backInputPath = "input2";
            // ベリトランス決済処理でコンビニ後払いの場合は$inForm['comiket_id']がセットされる
            if (@empty($inForm['comiket_id'])) {
                $backInputPath = "input";
            }
        }

        // コミケ申込情報登録
        // ベリトランス決済に失敗した場合もコミケ登録が行われる        // TODO:ロールバックが発生した場合は決済済、DB未登録のやらずぼったくり状態なので
        // 管理者メールなどを飛ばしてエラーが発生したことの通知
        // 画面には決済できてるのに登録失敗のメッセージを画面にもだしてお客様に問い合わせてもらう
        // このエラーの発生は入力情報の不備などではなくシステム回りや実装バグなので発生することはレアケース
        $calcDataInfoData = $this->registerComiket($db, $registerData, $comiketId, $backInputPath);

        // 業務連携
        $returnResult = $this->sendDataToGyomu($db, $registerData, $comiketId, $calcDataInfoData, $backInputPath, $sessionForm, $session, $toiawaseNo);
        $inForm = $returnResult['inform'];
        $li = $returnResult['li'];

        // 出力情報を設定
        $outForm = $this->_createOutFormByInForm($inForm);
        // QRコード用にcomiket_idを出力情報に設定
        $outForm->raw_qr_code_string = $comiketId;

        // イベント名、イベントサブ名、ロゴ画像、PDFなどを一括セット
        // イベントID
        $dispItemInfo['eventsub_selected_data']['event_id']=$inForm['event_sel'];
        // イベント情報
        $dispItemInfo['event_alllist'][]=array('id'           =>$eventData['id']
                                            ,'event_name'   =>$eventData['name']
                                            ,'eventsub_name'=>$eveSubData['name']
                                            );
        //一括セット
        $this->setDispEvent($dispItemInfo);

        // セッション情報を破棄
        $session->deleteForm(self::FEATURE_ID);

        return array(
            'type_sel'          => $inForm['comiket_detail_type_sel'],
            'convenience_sel'    => $inForm['comiket_convenience_store_cd_sel'],
            'outForm'           => $outForm,
            'eventData'         => $eventData,
            'eventsubData'      => $eveSubData,
            'collect_date'      => date('Y年n月j日'),
            //'collect_date'    => date('Y年n月j日', strtotime($li['treeData']['comiketDetailDataList'][0]['collect_date'])),
            'payment_method_cd' => $li["treeData"]['payment_method_cd'],
            'existsComiketId'   => $exitsComiketId,
            'dispItemInfo'      => $dispItemInfo,
            );
    }

    /**
     * コミケテーブル.請求書問番取得.
     * @return type
     */
    private function getToiawaseNo() {
        $toiawaseNoInfo = $this->_gyomuApiService->getToiawaseNo();
        if (@$toiawaseNoInfo['result'] != '0') { // 0は取得成功
            $errid = date('YmdHis') . '_' . str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789');
            
            Sgmov_Component_Log::err("======================================================================================");
            Sgmov_Component_Log::err("業務連携請求書問番取得時エラー");
            Sgmov_Component_Log::err("▼▼▼ ERROR_ID ▼▼▼");
            @Sgmov_Component_Log::err($errid);
            Sgmov_Component_Log::err("▼▼▼ レスポンス情報 ▼▼▼");
            @Sgmov_Component_Log::err($toiawaseNoInfo);
            Sgmov_Component_Log::err("▼▼▼ session 情報 ▼▼▼");
            @Sgmov_Component_Log::err($_SESSION);
            Sgmov_Component_Log::err("▼▼▼ server 情報 ▼▼▼");
            @Sgmov_Component_Log::err($_SERVER);
            Sgmov_Component_Log::err("▼▼▼ env 情報 ▼▼▼");
            @Sgmov_Component_Log::err($_ENV);
            Sgmov_Component_Log::err("======================================================================================");
                        
            // メールを送信する。
            // システム管理者メールアドレスを取得する。
            $mailTemplateList = array(
                "/common_error_event_webapi.txt",
            );
            $mailData = array();
            $mailData['errMsg'] = "業務連携請求書問番取得時にエラーが発生しました。" . PHP_EOL;
            $mailData['errMsg'] .=  "詳細は ERROR_ID をもとに ログ情報を確認してください。" . PHP_EOL . PHP_EOL;
            $mailData['errMsg'] .=  "[ERROR_ID]" . PHP_EOL;
            $mailData['errMsg'] .=  $errid . PHP_EOL . PHP_EOL;
            $mailData['errMsg'] .=  "[レスポンス情報]" . PHP_EOL;
            $mailData['errMsg'] .= @var_export($toiawaseNoInfo, true) . PHP_EOL . PHP_EOL;
            $mailData['errMsg'] .=  "[session情報]" . PHP_EOL;
            $mailData['errMsg'] .= @var_export($_SESSION, true) . PHP_EOL . PHP_EOL;
            $mailData['errMsg'] .=  "[server情報]" . PHP_EOL;
            $mailData['errMsg'] .= @var_export($_SERVER, true) . PHP_EOL . PHP_EOL;
            $mailData['errMsg'] .=  "[env情報]" . PHP_EOL;
            $mailData['errMsg'] .= @var_export($_ENV, true) . PHP_EOL . PHP_EOL;
            $mailData['errMsg'] = mb_substr($mailData['errMsg'] , 0, 1000);
            
            $mailTo = Sgmov_Component_Config::getLogMailTo();
            $objMail = new Sgmov_Service_CenterMail();
            $objMail->_sendThankYouMail($mailTemplateList, $mailTo, $mailData);
            
            $title = urlencode("システムエラー");
            $message = urlencode("エラーが発生しました。時間がたってからもう一度やりなおしてください。");
            Sgmov_Component_Redirect::redirectPublicSsl("/".$this->_DirDiv."/error?t={$title}&m={$message}");
        }
        return $toiawaseNoInfo['toiawaseNo'];
    }

    /**
     * 搬入の締切日をチェックする
     *
     * @param db
     * @param array inform
     * @param array eventSubData
     */
    private function checkHannyuArrivalDate($db, $inForm, $eveSubData, $session){
        // 各地域ごとの締切日チェック
        $chakuJis2 = substr($eveSubData['jis5cd'], 0, 2);
        $hatsuJis2 = $inForm['comiket_detail_outbound_pref_cd_sel'];
            
        $outBoundUnCollectCalInfo = $this->_OutBoundUnCollectCal->fetchOutBoundCollectCalByHaChaku($db, $inForm['eventsub_sel'], $hatsuJis2, $chakuJis2);
            
        $dateChNow = (new DateTime());
        $dateChArrival = new DateTime($outBoundUnCollectCalInfo['arrival_date']);
            
        if ($dateChArrival->format('Y-m-d H:i:s') <= $dateChNow->format('Y-m-d H:i:s')) {
            // セッション情報を破棄
            $session->deleteForm(self::FEATURE_ID);
            $dispDate = $dateChArrival->format('Y/m/d H:i:s');
            Sgmov_Component_Redirect::redirectPublicSsl("/".$this->_DirDiv."/error/?t=受付時間が終了しました&m=お預かり日：{$dispDate}の受付時間が終了しました");
            exit;
        }
            
        /////////////////////////////////////////////////////////////////////////////////////////////
        // 毎日お昼の１２時が【翌日集荷の指定締切り時間】 チェック
        /////////////////////////////////////////////////////////////////////////////////////////////
        $yearColl = @$inForm['comiket_detail_outbound_collect_date_year_sel'];
        $monthColl = @$inForm['comiket_detail_outbound_collect_date_month_sel'];
        $dayColl = @$inForm['comiket_detail_outbound_collect_date_day_sel'];
            
        $lastTime = $this->getLastSyukaTime();
        $dateColl = date("Y-m-d {$lastTime}", strtotime("{$yearColl}-{$monthColl}-{$dayColl}-1day"));
        $dateNow = date('Y-m-d H:i:s');

        if ($dateColl <= $dateNow) {
            // セッション情報を破棄
            $session->deleteForm(self::FEATURE_ID);
            Sgmov_Component_Redirect::redirectPublicSsl("/".$this->_DirDiv."/error/?t=受付時間が終了しました&m=お預かり日：{$yearColl}/{$monthColl}/{$dayColl}の受付時間が終了しました");
            exit;
        }
    }

    /**
     * ベリトランス決済処理
     *
     * @param db
     * @param array inform
     * @param int   comiketId
     * @param str   type
     */
    protected function payment($db, $inForm, $comiketId, $type = ''){
        // DateTime::createFromFormat()はPHP5.3未満で対応していない
        if (method_exists('DateTime', 'createFromFormat')) {
            $date = DateTime::createFromFormat('U.u', gettimeofday(true))
                ->setTimezone(new DateTimeZone('Asia/Tokyo'));
        } else {
            $date = new DateTime();
        }

        $isErrSgfCancelApi = false; // SGFキャンセルAPI失敗フラグ

        if ($inForm['comiket_div'] == self::COMIKET_DEV_INDIVIDUA) { // 個人
            $checkForm = array();
            // 個人の場合のみベリトランス or フィナンシャル連携
            switch ($inForm['comiket_payment_method_cd_sel']) {
                case '5': // 法人売掛
                    // 個人なので法人売掛はない
                    $inForm['merchant_result'] = '0'; // 決済データ送信結果
                    $inForm['merchant_datetime'] = NULL; // 決済データ送信日時
                    $inForm['receipted'] = NULL; // 入金確認日時
                    $inForm['auto_authoriresult'] = NULL; // コンビニ後払自動審査結果
                    break;
                case '4': // コンビニ後払い
                    $inForm['merchant_result'] = '0'; // 決済データ送信結果
                    $inForm['receipted'] = NULL; // 入金確認日時
                    $inForm['auto_authoriresult'] = NULL; // コンビニ後払自動審査結果
                    $inForm['merchant_datetime'] = $date->format('Y/m/d H:i:s.u');  // 決済データ送信日時
                    try {
                        // DB登録用のデータに整形
                        $comiketDataInfo = $this->_cmbTableDataFromInform($inForm, $comiketId);
                        $calcDataInfoData = $this->calcEveryKindData($inForm, $comiketId, true);
                        $calcDataInfo = $calcDataInfoData["treeData"];
                        $resultData = array();
                        try {
                            // SGフィナンシャルで決済をおこなっている
                            $resultData = $this->_SgFinancial->requestSgFinancialService($calcDataInfo);
                            $inForm['sgf_res_autoAuthoriresult'] = $resultData["transactionInfo"]["autoAuthoriresult"];
                            $inForm['sgf_res_shopOrderId'] = @$resultData["transactionInfo"]["shopOrderId"];
                            $inForm['sgf_res_transactionId'] = @$resultData["transactionInfo"]["transactionId"];
                        } catch(Exception $e) {
                            $inForm['sgf_res_transactionId'] = $inForm['sgf_res_shopOrderId'] = $inForm['sgf_res_autoAuthoriresult'] = NULL;
                        }

                        if ($resultData["result"] == "OK") {
                            if ($resultData["transactionInfo"]['autoAuthoriresult'] == "審査中"
                                    || $resultData["transactionInfo"]['autoAuthoriresult'] == "NG"
                                    ) {
                                $cancelResArr = array();
                                try {
                                    $cancelResArr = $this->_SgFinancial->requestCancel(
                                                array(
                                                    'res_sgf_transactionId' => $resultData["transactionInfo"]["transactionId"]
                                                    )
                                            );
                                } catch(Exception $e) {
                                   $cancelResArr["result"] = "NG";
                                }

                                if($cancelResArr["result"] == "OK") {
                                    Sgmov_Component_Log::info("SgFinancial-cancel-OK");
                                } else {
                                    
                                    // SGFキャンセルAPI失敗フラグ ON
                                    $isErrSgfCancelApi = true;
                                    
                                    // キャンセル処理に失敗した場合は、管理者にメールする
                                    $this->errorInformationForSgFinancial(array('res_sgf_transactionId' => $resultData["transactionInfo"]["transactionId"],'event_id' => $inForm["comiket_id"]));                                    
                                    Sgmov_Component_Log::info("SgFinancial-cancel-NG");
                                    Sgmov_Component_Log::info($cancelResArr);
                                    throw new Exception("Error in ".__FILE__." at line ".__LINE__.": Could not cancel the transaction ".$resultData["transactionInfo"]["transactionId"]." !");
                                }
                                throw new Exception("Error in ".__FILE__." at line ".__LINE__.": transaction ".$resultData["transactionInfo"]["transactionId"]." was successfully cancelled !");
                            }
                        } else {
                            $inForm['sgf_res_autoAuthoriresult'] = NULL;
                            throw new Exception("Error in ".__FILE__." at line ".__LINE__.": requestSgFinancialService transaction did not successfully return : ".json_encode($resultData)." !");
                        }

                        $inForm['merchant_result'] = '1';

                    } catch (Exception $e) {
                        Sgmov_Component_Log::info('コンビニ後払いに失敗しました。');
                        Sgmov_Component_Log::info($e);
                        Sgmov_Component_Log::info($inForm);
                        $inForm['merchant_result'] = '0';
                    }

                    break;
                case '3': // 電子マネー
                    $inForm['merchant_result'] = '0'; // 決済データ送信結果
                    $inForm['merchant_datetime'] = NULL;  // 決済データ送信日時
                    $inForm['receipted'] = NULL; // 入金確認日時
                    $inForm['auto_authoriresult'] = NULL; // コンビニ後払自動審査結果
                    break;
                case '2': // クレジットカード
                    // 決済用にカード情報等を設定
                    $checkForm = $this->_createCheckCreditCardDataByInForm($inForm, $type);
                    if (!empty($checkForm)) {
                        // ベリトランスデータ送信 $inFormの決済データ送信結果、入金確認日時、コンビニ後払自動審査結果は以下関数で設定
                        $inForm = $this->_transact($checkForm, $inForm);
                    }
                    break;
                case '1': // コンビニ決済(先払い)
                    // 決済用に申込情報等を設定
                    $checkForm = $this->_createCheckConvenienceStoreDataByInForm($db, $inForm, $type);
                    if (!empty($checkForm)) {
                        // ベリトランスデータ送信 $inFormの決済データ送信結果、入金確認日時、コンビニ後払自動審査結果は以下関数で設定
                        $inForm = $this->_transact($checkForm, $inForm);
                    }
                    break;
                default:
                    break;
            }
        } else { // 法人
            // 法人売掛
            $inForm['merchant_result'] = '0'; // 決済データ送信結果
            $inForm['merchant_datetime'] = NULL; // 決済データ送信日時
            $inForm['receipted'] = NULL; // 入金確認日時
            $inForm['auto_authoriresult'] = NULL; // コンビニ後払自動審査結果
        }

        return $inForm;
    }

    /**
     * 申込情報登録処理
     *
     * @param db
     * @param array inform
     * @param int comiketId
     * @param str argBackInputPath
     */
    protected function registerComiket($db, $inForm, $comiketId, $argBackInputPath){
        $db->begin();
        try {

            ////////////////////////////////////////////////////////////
            // 料金計算
            ////////////////////////////////////////////////////////////
            $calcDataInfoData = $this->calcEveryKindData($inForm, $comiketId, false);

            $calcDataInfo = $calcDataInfoData["treeData"];
            $comiketDataInfoFlat = $calcDataInfoData["flatData"];
          

            ////////////////////////////////////////////////////////////
            // DB登録
            ////////////////////////////////////////////////////////////


            /** コミケ申込データ　**/
            //ブースをNULLに設定する
            //$comiketDataInfoFlat["comiketData"]['booth_num'] = NULL;
            $this->_Comiket->insert($db, $comiketDataInfoFlat["comiketData"]);
            
            // TODO VASI
            // $comiketIds = array();
            // $comiketIds[0]['oya_id'] = $comiketId;
            // $comiketIds[0]['id'] = $comiketId; 

            /** コミケ申込関連データ　**/
            // foreach ($comiketIds as $key => $val) {
            //    $this->_ComiketKanren->insert($db, $val);
            // }

            /** コミケ申込明細データ　**/
            foreach ($comiketDataInfoFlat["comiketDetailDataList"] as $key => $val) {
                $this->_ComiketDetail->insert($db, $val);
            }

            /** コミケ申込宅配データ　**/
            foreach ($comiketDataInfoFlat["comiketBoxDataList"] as $key => $val) {
                $this->_ComiketBox->insert($db, $val);
            }

            /** コミケ申込カーゴデータ　**/
            foreach ($comiketDataInfoFlat["comiketCargoDataList"] as $key => $val) {
                $this->_ComiketCargo->insert($db, $val);
            }

            /** コミケ申込宅配データ　**/
            foreach ($comiketDataInfoFlat["comiketCharterDataList"] as $key => $val) {
                $this->_ComiketCharter->insert($db, $val);
            }

        } catch (Exception $e) {
            $db->rollback();
            $_SESSION["Sgmov_View_Dsn.inputErrorInfo"] = array("db_insert_error" => "・データベースの登録・更新に失敗しました。");

            Sgmov_Component_Log::debug("リダイレクト /".$this->_DirDiv."/{$argBackInputPath}/");
            Sgmov_Component_Log::err('データベースの登録・更新に失敗しました。');
            Sgmov_Component_Log::err($e);
            Sgmov_Component_Redirect::redirectPublicSsl("/".$this->_DirDiv."/{$argBackInputPath}/");
            exit;
        }
        $db->commit();

        return $calcDataInfoData;
    }

    /**
     * バッチを叩いて業務連携を行う
     * 
     * @param type $li
     * @param type $inForm
     */
    protected function sendDataToGyomu($db, $inForm, $comiketId, $calcDataInfoData, $backInputPath, $sessionForm, $session, $toiawaseNo){

        // クレジット決済とコンビニ後払い時に、決済の結果が失敗ならば申込入力画面に強制遷移させる
        if ($inForm['merchant_result'] == '0' &&
                ($inForm['comiket_payment_method_cd_sel'] == '2'
                    || $inForm['comiket_payment_method_cd_sel'] == '4')
            ) {

            $msg = 'クレジットの入力に誤りがあります。入力内容をご確認いただくか、別のお支払方法を選択してください。';
            if ($inForm['comiket_payment_method_cd_sel'] == '4') {
                $msg = 'コンビニ後払いの申請に失敗しました。別のお支払方法を選択してください。';
                
                if ($isErrSgfCancelApi) { // SGFキャンセルAPIが失敗している場合
                    // sgfキャンセル処理に失敗した場合は、sgfキャンセル送信フラグ = 1 (送信必要)にする
                    $this->_Comiket->updateSgfCancelFlg($db, $comiketId, "1");
                }
            }

            // エラーメッセージを作成する
            $errorForm = new Sgmov_Form_Error();
            $errorForm->addError('payment_method', $msg);

            $sessionForm->error = $errorForm;
            $sessionForm->status = self::VALIDATION_FAILED;
            $session->saveForm(self::FEATURE_ID, $sessionForm);

            Sgmov_Component_Redirect::redirectPublicSsl("/".$this->_DirDiv."/{$backInputPath}/");
            exit;
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////

        $liTreeData = $calcDataInfoData["treeData"];
        $liFlatData = $calcDataInfoData["flatData"];


        // コンビニ後払いはなし。
        // if($liTreeData['payment_method_cd']  == '4' // コンビニ後払い
        //         && $inForm['merchant_result'] == '0'
        //         ) {
        //     $outForm = $this->_createOutFormByInForm($inForm);
        //     throw new Sgmov_Component_Exception("", 500 ,NULL, array(
        //         'outForm' => $outForm,
        //         'payment_method_cd'=> $liTreeData['payment_method_cd'],
        //             ));
        // }


        Sgmov_Component_Log::debug('################# 2001 業務連携バッチ実行開始');
        // オンラインバッチ起動（業務連携）
        $bcmResult = $this->_BcmView->execute($comiketId);

        Sgmov_Component_Log::debug('################# 2001 業務連携バッチ実行終了');
        Sgmov_Component_Log::debug($bcmResult);

        // 支払種別が【コンビニ後払い】の場合
        if (@$liTreeData["treeData"]['payment_method_cd'] == self::PAYMENT_METHOD_CONVINI_AFTER) {

            // 取引登録が正常終了かつ業務連携バッチ処理の結果が成功なら出荷報告処理を行う
            if ($resultData['result'] == 'OK' && @$bcmResult['sendSts'] == '0') {
                try {
                    $shipmentCompResArr = $this->_SgFinancial->requestShipmentReport(
                                                array(
                                                    'res_sgf_transactionId' => $resultData["transactionInfo"]["transactionId"]
                                                    ),
                                                $bcmResult['delivery_slip_no']
                                            );
                } catch (Exception $ex) {
                    $shipmentCompResArr['result'] = 'NG';
                }

                if ($shipmentCompResArr['result'] == 'OK') {
                    Sgmov_Component_Log::info('SgFinancial-ShipmentReport-OK');
                } else {
                    // 出荷報告処理APIでエラーとなった場合はシステム管理者にエラーメールを送信する
                    Sgmov_Component_Log::info("@@@@ 出荷報告処理APIでエラーとなった場合はシステム管理者にエラーメールを送信する");
                    $param = array('res_sgf_transactionId' => $resultData["transactionInfo"]["transactionId"]);
                    $mail_to = Sgmov_Component_Config::getLogMailTo();
                    Sgmov_Component_Mail::sendTemplateMail($param, dirname(__FILE__) . '/../../mail_template/'.$this->_DirDiv.'_error_for_sgfinancial_shipment_report.txt', $mail_to);
                    Sgmov_Component_Log::info('SgFinancial-ShipmentReport-NG');
                    Sgmov_Component_Log::info($shipmentCompResArr);
                }
            } else {
                // 出荷報告処理APIが実行されなかった場合はシステム管理者にエラーメールを送信する
                Sgmov_Component_Log::info("@@@@ 出荷報告処理APIの未実行となった場合はシステム管理者にエラーメールを送信する");
                $param = array('res_sgf_transactionId' => @$resultData["transactionInfo"]["transactionId"]);
                $mail_to = Sgmov_Component_Config::getLogMailTo();
                Sgmov_Component_Mail::sendTemplateMail($param, dirname(__FILE__) . '/../../mail_template/'.$this->_DirDiv.'_error_for_sgfinancial_shipment_report.txt', $mail_to);
                Sgmov_Component_Log::info('SgFinancial-ShipmentReport-NG');
            }
        }

        // 問い合わせ番号
        $liTreeData["toiawase_no"] = $toiawaseNo;

        $this->sendCompleteMail2(array('treeData' => $liTreeData), $inForm);

        return array(
            'inform' => $inForm,
            'li' => array('treeData' => $liTreeData)
        );

    }

    /**
     * メール送信処理
     * 
     * @param type $li
     * @param type $inForm
     */
    protected function sendCompleteMail2($li, $inForm, $type= '') {

        // 往路か復路かの区分
        $comiketDetailDataListType = $li['treeData']['comiket_detail_list'][0]['type'];

        // メール送信
        if ((!empty($li["treeData"]['mail']) && !empty($li["treeData"]['merchant_result']))
                || $li["treeData"]['payment_method_cd'] == '3' // 電子マネー
                || $li["treeData"]['payment_method_cd'] == '5' // 法人売掛
                ) {
            $li["treeData"]['sgf_res_shopOrderId'] = @$inForm['sgf_res_shopOrderId'];
            $li["treeData"]['sgf_res_transactionId'] = @$inForm['sgf_res_transactionId'];
            $li["treeData"]['payment_url'] = @$inForm['payment_url'];
            $ccMail = '';
            if($this->isCharter($li["treeData"])) {
                $ccMail = Sgmov_Component_Config::getComiketCharterFinMailCc();
            } else if($this->isCargo($li["treeData"])) {
                $ccMail = Sgmov_Component_Config::getComiketCargoFinMailCc();
            }
            $this->sendCompleteMail($li["treeData"], $li["treeData"]['mail'], $ccMail, $comiketDetailDataListType);
        }
    }

    /**
     * チャーター便かチェック
     *
     * @param type $comiket
     */
    protected function isCharter($comiket) {
        $comiketDetailList = $comiket['comiket_detail_list'];

        foreach($comiketDetailList as $key => $val) {
            if($val['service'] == '3') { // チャーターの場合
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * カーゴ使用かチェック
     *
     * @param type $comiket
     */
    protected function isCargo($comiket) {
        $comiketDetailList = $comiket['comiket_detail_list'];

        foreach($comiketDetailList as $key => $val) {
            if($val['service'] == '2') { // カーゴの場合
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     *
     * @param type $param
     */
    public function errorInformationForSgFinancial($param) {
        Sgmov_Component_Log::debug( "errorInformationForSgFinancial(".json_encode($param).")" );
        // システム管理者メールアドレスを取得する。
        $mail_to = Sgmov_Component_Config::getLogMailTo();
        //テンプレートメールを送信する。
        Sgmov_Component_Mail::sendTemplateMail($param, dirname(__FILE__) . '/../../mail_template/'.$this->_DirDiv.'_error_for_sgfinancial.txt', $mail_to);
    }


    /**
     * POST値からチケットを取得します。
     * チケットが存在しない場合は空文字列を返します。
     * @return string チケット文字列
     */
    public function _getTicket() {
        if (!isset($_POST['ticket'])) {
            $ticket = '';
        } else {
            $ticket = $_POST['ticket'];
        }
        return $ticket;
    }

    /**
     * 入力フォームの値を元に支払期限を生成します。
     * @param Sgmov_Form_Pcr001-003In $inForm 入力フォーム
     * @return string 支払期限
     */
    public function _getPayLimit($db, $inForm) {
        // コンビニの支払期限
        $date_convenience_store = new DateTime();

        // 搬入の場合は、支払い期限は(お預かり日-1)になります。
        if ($inForm["comiket_detail_type_sel"] == "1") {
            $date_convenience_store = new DateTime($inForm["comiket_detail_outbound_collect_date_year_sel"]. "/".$inForm["comiket_detail_outbound_collect_date_month_sel"]. "/".$inForm["comiket_detail_outbound_collect_date_day_sel"]);
            $max_day = '-1 day';
        } else {
            switch ($inForm['comiket_convenience_store_cd_sel']) {
                case '1':
                    $service_option_type = self::SEVEN_ELEVEN_CODE;
                    $max_day = '+150 day';
                    break;
                case '2':
                    $service_option_type = self::E_CONTEXT_CODE;
                    $max_day = '+60 day';
                    break;
                case '3':
                    $service_option_type = self::WELL_NET_CODE;
                    $max_day = '+365 day';
                    break;
                default:
                    return;
            }
        }

        $date_convenience_store->modify($max_day);
        $pay_limit_convenience_store = $date_convenience_store->format('Y/m/d');
Sgmov_Component_Log::debug($pay_limit_convenience_store);

        if (empty($pay_limit) || $pay_limit > $pay_limit_convenience_store) {
            $pay_limit = $pay_limit_convenience_store;
        }

        return $pay_limit;
    }

    /**
     * 入力フォームの値を元にデータを生成します。
     * @param Sgmov_Form_Pcr001-003In $inForm 入力フォーム
     * @return array データ
     */
    public function _createDataByInForm($db, $inForm, $toiawaseNo = '0123456789') {

        // TODO オブジェクトから値を直接取得できるよう修正する
        // オブジェクトから取得できないため、配列に型変換
        $inForm = (array) $inForm;

        $inForm['authorization_cd'] = '';
        $inForm['receipt_cd']       = '';

        // 2038年問題対応のため、date()ではなくDateTime()を使う
        // DateTime::createFromFormat()はPHP5.3未満で対応していない
        if (method_exists('DateTime', 'createFromFormat')) {
            $date = DateTime::createFromFormat('U.u', gettimeofday(true))
                ->setTimezone(new DateTimeZone('Asia/Tokyo'));
        } else {
            $date = new DateTime();
        }

        $inForm['merchant_datetime'] = $date->format('Y/m/d H:i:s.u');
                
        $inForm['payment_order_id'] = 'sagawa-moving-event_' . $date->format('YmdHis') . '_' . str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuv')."_{$toiawaseNo}";
        $inForm['pay_limit'] = $this->_getPayLimit($db, $inForm);

        // 問合せ番号
        $inForm['comiket_toiawase_no'] = $toiawaseNo;

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////
        // comiket登録前に業務側から荷動き先問番取得
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////
        $inForm['comiket_toiawase_no_niugoki'] = $this->getToiawaseNo();

        return $inForm;
    }

    /**
     * 入力フォームの値を元にクレジットカード決済用データを生成します。
     * @param Sgmov_Form_Pcr001-003In $inForm 入力フォーム
     * @return array 決済用データ
     */
    public function _createCheckCreditCardDataByInForm($inForm, $type) {

        // セキュリティコード
        $securityCode = htmlspecialchars($inForm['security_cd']);

        // 要求電文パラメータ値の指定
        $data = new CardAuthorizeRequestDto();

        // 取引ID
        $data->setOrderId($inForm['payment_order_id']);

        // 支払金額
        $data->setAmount(strval($inForm['delivery_charge']));
        // カード番号
        $data->setCardNumber($inForm['card_number']);

        // カード有効期限 MM/YY
        $cardExpire = $inForm['card_expire_month_cd_sel'] . '/' . substr($inForm['card_expire_year_cd_sel'], -2);
        $data->setCardExpire($cardExpire);

        // 与信方法
        $data->setWithCapture('true');

        // 支払は一回払い固定にする
        $jpo = '10';
        if (isset($jpo)) {
            $data->setJpo($jpo);
        }

        // セキュリティコード
        if (isset($securityCode)) {
            $data->setSecurityCode($securityCode);
        }

        return $data;
    }

    /**
     * 入力フォームの値を元にコンビニ決済用データを生成します。
     * @param Sgmov_Form_Pcr001-003In $inForm 入力フォーム
     * @return array 決済用データ
     */
    public function _createCheckConvenienceStoreDataByInForm($db, $inForm, $type) {

        // 要求電文パラメータ値の指定
        $data = new CvsAuthorizeRequestDto();

        // お支払店舗
        switch ($inForm['comiket_convenience_store_cd_sel']) {
            case '1':
                $service_option_type = self::SEVEN_ELEVEN_CODE;
                break;
            case '2':
                $service_option_type = self::E_CONTEXT_CODE;
                break;
            case '3':
                $service_option_type = self::WELL_NET_CODE;
                break;
            default:
                break;
        }
        $data->setServiceOptionType($service_option_type);

        // 取引ID
        $data->setOrderId($inForm['payment_order_id']);

        // 支払金額
        $data->setAmount(strval($inForm['delivery_charge']));

        // 姓
        $data->setName1($inForm['comiket_personal_name_sei']);

        // 名
        $data->setName2(!empty($inForm['comiket_personal_name_mei']) ? $inForm['comiket_personal_name_mei'] : '（申込者）');

        // 電話番号
        $data->setTelNo($inForm['comiket_tel']);

        // 支払期限
        $data->setPayLimit($inForm['pay_limit']);

        // 支払区分
        // リザーブパラメータのため無条件に '0' を設定する
        $data->setPaymentType('0');

        return $data;
    }

    /**
     * セッションの値を元に出力フォームを生成します。
     * @param $inForm 入力フォーム
     * @return Sgmov_Form_Eve004Out 出力フォーム
     */
    public function _createOutFormByInForm($inForm) {

        $outForm = new Sgmov_Form_Eve004Out();

        $outForm->raw_convenience_store_cd_sel = @$inForm['comiket_convenience_store_cd_sel'];
        $outForm->raw_mail = @$inForm['comiket_mail'];
        $outForm->raw_merchant_result = @$inForm['merchant_result'];
        $outForm->raw_payment_method_cd_sel = @$inForm['comiket_payment_method_cd_sel'];
        $outForm->raw_payment_url = isset($inForm['payment_url']) ? $inForm['payment_url'] : null;
        $outForm->raw_receipt_cd = @$inForm['receipt_cd'];

        $outForm->raw_comiket_detail_type_sel = @$inForm['comiket_detail_type_sel'];

        $outForm->raw_comiket_detail_outbound_service_sel = @$inForm['comiket_detail_outbound_service_sel'];

        $outForm->raw_comiket_detail_inbound_service_sel =  @$inForm['comiket_detail_inbound_service_sel'];

        $outForm->raw_sgf_shop_order_id = "";
        if(isset($inForm['sgf_res_shopOrderId'])) {
            $outForm->raw_sgf_shop_order_id = @$inForm['sgf_res_shopOrderId'];
        }
        $outForm->raw_sgf_transaction_id = "";
        if(isset($inForm['sgf_res_transactionId'])) {
            $outForm->raw_sgf_transaction_id = @$inForm['sgf_res_transactionId'];
        }

        $outForm->raw_eventsub_cd_sel = @$inForm['eventsub_sel'];

        return $outForm;
    }

    /**
     * システム管理者へ失敗メールを送信
     * @return
     */
    public function errorInformation($parm = array())
    {
        Sgmov_Component_Log::debug( "errorInformation(".json_encode($parm).")" );
        // システム管理者メールアドレスを取得する。
        $mail_to = Sgmov_Component_Config::getLogMailTo();
        //テンプレートメールを送信する。
        Sgmov_Component_Mail::sendTemplateMail($parm, dirname(__FILE__) . '/../../mail_template/'.$this->_DirDiv.'_error.txt', $mail_to);
    }

    /**
     * 決済用データの入力値の妥当性検査を行います。
     * @param $checkForm 決済用データ
     * @param Sgmov_Form_Dsn003In $inForm 入力フォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _transact($checkForm, $inForm) {
        Sgmov_Component_Log::debug($checkForm);Sgmov_Component_Log::debug($inForm);
        // VeriTrans3G MerchantDevelopmentKitマーチャントCCID、マーチャントパスワード設定
        switch ($inForm['comiket_payment_method_cd_sel']) {
            case '2': // クレジットカード決済
                $props = array(
                    'merchant_ccid'       => Sgmov_Component_Config::getComiketMdkCreditCardMerchantCcId(),
                    'merchant_secret_key' => Sgmov_Component_Config::getComiketMdkCreditCardMerchantSecretKey(),
                );
                break;
            case '1': // コンビニ決済
                $props = array(
                    'merchant_ccid'       => Sgmov_Component_Config::getComiketMdkConvenienceStoreMerchantCcId(),
                    'merchant_secret_key' => Sgmov_Component_Config::getComiketMdkConvenienceStoreMerchantSecretKey(),
                );
                break;
            default:
                $props = null;
                break;
        }

        // 2038年問題対応のため、date()ではなくDateTime()を使う
        // DateTime::createFromFormat()はPHP5.3未満で対応していない
        if (method_exists('DateTime', 'createFromFormat')) {
            $date = DateTime::createFromFormat('U.u', gettimeofday(true))
                ->setTimezone(new DateTimeZone('Asia/Tokyo'));
        } else {
            $date = new DateTime();
        }

        $inForm['merchant_datetime'] = $date->format('Y/m/d H:i:s.u');
        $inForm['auto_authoriresult'] = NULL;

        Sgmov_Component_Log::debug($props);
        // 決済の実行
        $transaction = new TGMDK_Transaction();
        $response = $transaction->execute($checkForm, $props);
        Sgmov_Component_Log::debug('response = transaction->execute');
        Sgmov_Component_Log::debug($response);
        Sgmov_Component_Log::debug($inForm);
        if (!isset($response)) {
            // 予期しない例外
            $inForm['merchant_result'] = '0';
            $inForm['receipted'] = NULL;
            Sgmov_Component_Log::debug('予期しない例外');
            $this->errorInformation(array("payment_order_id" => "","event_id" => $inForm['comiket_id'],"errMsg" => "No response to the transaction execution　with parameters checkForm=".json_encode($checkForm)." and props=".json_encode($props)));
        } else {
            // 想定応答の取得
            Sgmov_Component_Log::debug('想定応答の取得');

            // 取引ID取得
            $resultOrderId = $response->getOrderId();
            Sgmov_Component_Log::debug($resultOrderId);

            // 結果コード取得
            $resultStatus = $response->getMStatus();
            Sgmov_Component_Log::debug("resultStatus={$resultStatus}");

            // 詳細コード取得
            $resultCode = $response->getVResultCode();
            Sgmov_Component_Log::debug($resultCode);

            // エラーメッセージ取得
            $errorMessage = $response->getMerrMsg();
            Sgmov_Component_Log::debug($errorMessage);

            switch ($resultStatus) {
                case 'success';
                    // 成功
                    $inForm['merchant_result'] = '1';
                    Sgmov_Component_Log::debug('resultStatus:success => 成功');
                    break;
                case 'pending';
                    // 失敗
                    $inForm['merchant_result'] = '0';
                    Sgmov_Component_Log::debug('resultStatus:pending => 失敗');
                    break;
                case 'failure';
                    $this->errorInformation(array("payment_order_id" => $inForm['payment_order_id'],"event_id" => $inForm['comiket_id'],"errMsg" => $errorMessage));
                default:
                    // 失敗
                    $inForm['merchant_result'] = '0';
                    Sgmov_Component_Log::debug('resultStatus:default => 失敗');
                    break;
            }

            switch ($inForm['comiket_payment_method_cd_sel']) {
                case '2': // クレジットカード決済
                    // 承認番号
                    $inForm['authorization_cd'] = $response->getResAuthCode();
                    $gatewayResDate = $response->getGatewayResponseDate();
                    if($inForm['merchant_result'] == '1') {
                        $inForm['receipted'] = date('Y-m-d H:i:s', strtotime($gatewayResDate));
                    } else {
                        $inForm['receipted'] = NULL;
                    }
                    $inForm['receipt_cd'] = NULL;
                    Sgmov_Component_Log::debug($inForm['authorization_cd']);
                    break;
                case '1': // コンビニ決済
                    // 受付番号
                    $inForm['receipt_cd'] = $response->getReceiptNo();
                    Sgmov_Component_Log::debug($inForm['receipt_cd']);
                    $inForm['receipted'] = NULL;
                    // 払込票URL
                    $inForm['payment_url'] = $response->getHaraikomiUrl();
                    Sgmov_Component_Log::debug($inForm['payment_url']);
                    break;
                default:
                    break;
            }

        }

        return $inForm;
    }
}
