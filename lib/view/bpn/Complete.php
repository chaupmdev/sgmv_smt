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
Sgmov_Lib::useView('bpn/Common', 'CommonConst');
Sgmov_Lib::useView('bpn/Bcm');
Sgmov_Lib::useForms(array('Error', 'BpnSession', 'Bpn004Out' ));
Sgmov_Lib::useServices(array('Comiket', 'ComiketDetail', 'ComiketBox', 'ComiketCargo', 'ComiketCharter'
    , 'CenterMail', 'SgFinancial', 'HttpsZipCodeDll', 'BoxFare', 'Charter', 'Eventsub', 'ComiketKanren', 'GyomuApi'));
Sgmov_Lib::useImageQRCode();
Sgmov_Lib::use3gMdk(array('3GPSMDK'));
/**#@-*/

/**
 * 物販お申し込みを登録し、完了画面を表示します。
 * @package    View
 * @subpackage BPN
 * @author     K.Sawada
 * @copyright  2018-2019 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Bpn_Complete extends Sgmov_View_Bpn_Common {

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
    protected $_ComiketBox;

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
     * 宅配運賃サービス
     * @var type
     */
    private $_BoxFareService;

    /**
     * 貸切サービス
     * @var type
     */
    protected $_CharterService;

    /**
     * 業務連携サービス
     * @var type
     */
    public $_gyomuApiService;

    /**
     * BCMイベント輸送サービスのコンビニ先払専用のお申し込み送信バッチ
     * @var type
     */
    protected $_BcmView;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_appCommon           = new Sgmov_Service_AppCommon();
        $this->_PrefectureService   = new Sgmov_Service_Prefecture();
        $this->_EventService        = new Sgmov_Service_Event();
        $this->_EventsubService     = new Sgmov_Service_Eventsub();
        $this->_BoxService          = new Sgmov_Service_Box();
        $this->_BuildingService     = new Sgmov_Service_Building();
        $this->_CharterService      = new Sgmov_Service_Charter();

        $this->_Comiket             = new Sgmov_Service_Comiket();
        $this->_ComiketDetail       = new Sgmov_Service_ComiketDetail();
        $this->_ComiketBox          = new Sgmov_Service_ComiketBox();
        $this->_ComiketCargo        = new Sgmov_Service_ComiketCargo();
        $this->_ComiketCharter      = new Sgmov_Service_ComiketCharter();
        $this->_ComiketKanren       = new Sgmov_Service_ComiketKanren();

        $this->_SgFinancial         = new Sgmov_Service_SgFinancial();
        $this->_centerMailService   = new Sgmov_Service_CenterMail();
        $this->_HttpsZipCodeDll     = new Sgmov_Service_HttpsZipCodeDll();
        $this->_BoxFareService      = new Sgmov_Service_BoxFare();
        $this->_CharterService      = new Sgmov_Service_Charter();
        $this->_gyomuApiService     = new Sgmov_Service_GyomuApi();

        $this->_BcmView             = new Sgmov_View_Bpn_Bcm();

        $this->_Comiket->setTrnsactionFlg(FALSE);
        $this->_ComiketDetail->setTrnsactionFlg(FALSE);
        $this->_ComiketBox->setTrnsactionFlg(FALSE);
        $this->_ComiketCargo->setTrnsactionFlg(FALSE);
        $this->_ComiketCharter->setTrnsactionFlg(FALSE);

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
    public function executeInner($argBackInputPaht="", $bpnType="") {
        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();

         //チケットの確認と破棄
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_BPN003, $this->_getTicket());

        // セッションから情報を取得
        $sessionForm = $session->loadForm(self::FEATURE_ID);

        if (empty($sessionForm)) {
            $sessionForm = new Sgmov_Form_BpnSession();
        }

        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        ////////////////////////////////////////////////////////////////////////////////////////////
        // コミケ申込宅配データの商品の個数を確認する。
        ////////////////////////////////////////////////////////////////////////////////////////////
        $sessionInfoArr = (array) $sessionForm->in;


        ////////////////////////////////////////////////////////////////////////////////////////////
        // 商品の最大数量チェック
        // 当日物販以外
        ////////////////////////////////////////////////////////////////////////////////////////////
        //if($sessionInfoArr["bpn_type"] == "1"){
        $this->checkBoxCount($db, $sessionInfoArr, $sessionForm, $session);
        //}

        // comiket登録前に業務側から請求先問番取得
        $toiawaseNoBuppan = $this->getToiawaseNo();

        $inForm = $this->_createDataByInForm($db, $sessionForm->in, $toiawaseNoBuppan);

        // comiket登録前に業務側から荷動き先問番取得
        $inForm['comiket_toiawase_no_niugoki'] = $this->getToiawaseNo();

        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // サイトの表示を shohin.term_fr(申込開始) ～ eventsub.arrival_to_time(復路申込期間終了) で制御する
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        $this->checkShohinInTerm($db, $inForm["eventsub_sel"]);


        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // サイズ変更、キャンセル、入力用,
        // 途中で、単商品期間が範囲外になった場合、入力画面に戻る。
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        $this->checkValidShohin($db, $inForm);
     
        $eventsubData = $this->_EventsubService->fetchEventsubByEventsubId($db, $inForm['eventsub_sel']);

        // 新しいコミケID
        $comiketId = $this->_Comiket->select_id($db);

        // ベリトランス
        $registerData = $this->payment($db, $inForm, $comiketId);

        // エラーが発生した時、戻る画面
        $backInputPath = $argBackInputPaht;
        if (@empty($backInputPath)) {
            $backInputPath = "input2";
            if (@empty($inForm['comiket_id'])) {
                $backInputPath = "input";
            }
        }

        $backInputPath = $backInputPath.'/'.$inForm["eventsub_sel"]."/".$inForm["bpn_type"]."/".$inForm["shohin_pattern"];

        // 申込登録する際に、空になる
        if(empty($bpnType)){
            $bpnType = $inForm["bpn_type"];
        }

        // コミケ申込情報登録
        $calcDataInfoData = $this->registerComiket($db, $registerData, $comiketId, $backInputPath, $bpnType);

        // 業務連携
        $returnResult = $this->sendDataToGyomu($db, $registerData, $comiketId, $calcDataInfoData, $backInputPath, $sessionForm, $session, $toiawaseNoBuppan, $bpnType);
        $inForm = $returnResult['inform'];
        $li = $returnResult['li'];


        $outFormBuppan =  new Sgmov_Form_Bpn004Out();
        $outFormBuppan = $this->_createOutFormByInForm($inForm);

        $outFormBuppan->raw_qr_code_string = $comiketId;

        // セッション情報を破棄
        $session->deleteForm(self::FEATURE_ID);

        return array(
            'type_sel' => "5",
            'convenience_sel' => $inForm['comiket_convenience_store_cd_sel'],
            //'outForm' => $outForm,
            'eventsubData' => $eventsubData,
            'outFormBuppan' => $outFormBuppan,
            'collect_date' => date('Y年n月j日'),
            'payment_method_cd'=> $li["treeData"]['payment_method_cd'],
            'returnToInputPath' => $backInputPath
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
            Sgmov_Component_Redirect::redirectPublicSsl("/bpn/error?t={$title}&m={$message}");
        }
        return $toiawaseNoInfo['toiawaseNo'];
    }

    /**
    * ベリトランス決済処理
    * @return type
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
                        $comiketDataInfo = $this->_cmbTableDataFromInform($inForm, $comiketId);
                        $calcDataInfoData = $this->calcEveryKindData($inForm, $comiketId, true);
                        $calcDataInfo = $calcDataInfoData["treeDataForBuppan"];
                        $resultData = array();
                        try {
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
                    $checkForm = $this->_createCheckCreditCardDataByInForm($inForm, $type);
                    if (!empty($checkForm)) {
                        // ベリトランスデータ送信 $inFormの決済データ送信結果、入金確認日時、コンビニ後払自動審査結果は以下関数で設定
                        $inForm = $this->_transact($checkForm, $inForm);
                    }
                    break;
                case '1': // コンビニ決済(先払い)
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
            $inForm['merchant_result'] = '0'; // 決済データ送信結果
            $inForm['merchant_datetime'] = NULL; // 決済データ送信日時
            $inForm['receipted'] = NULL; // 入金確認日時
            $inForm['auto_authoriresult'] = NULL; // コンビニ後払自動審査結果
        }

        return $inForm;
    }


     /**
     * コミケテーブルへ申込情報登録
     * @return type
     */
     protected function registerComiket($db, $inForm, $comiketId, $argBackInputPath, $bpnType){
        $db->begin();
        try {

            ////////////////////////////////////////////////////////////
            // 料金計算
            ////////////////////////////////////////////////////////////
            if(!empty($bpnType) && $bpnType == "1"){
                // 物販用
                $calcDataInfoData = $this->calcEveryKindData($inForm, $comiketId, false);
            }else{
                // 当日物販用
                $calcDataInfoData = $this->calcEveryKindDataActiveShohin($inForm, $comiketId, false);
            }

            $comiketDataInfoFlat = $calcDataInfoData["flatDataForBuppan"];


            ////////////////////////////////////////////////////////////
            // DB登録
            ////////////////////////////////////////////////////////////

            $comiketDataInfoFlat["comiketData"]["choice"] = "5";
            /** コミケ申込データ　**/
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
                $this->_ComiketBox->insert2($db, $val);
            }

            // /** コミケ申込カーゴデータ　**/
            // foreach ($comiketDataInfoFlat["comiketCargoDataList"] as $key => $val) {
            //     $this->_ComiketCargo->insert($db, $val);
            // }

            // /** コミケ申込宅配データ　**/
            // foreach ($comiketDataInfoFlat["comiketCharterDataList"] as $key => $val) {
            //     $this->_ComiketCharter->insert($db, $val);
            // }

        } catch (Exception $e) {
            $db->rollback();
            $_SESSION["Sgmov_View_Bpn.inputErrorInfo"] = array("db_insert_error" => "・データベースの登録・更新に失敗しました。");
            Sgmov_Component_Log::debug("リダイレクト /bpn/{$argBackInputPath}/");
            Sgmov_Component_Log::err('データベースの登録・更新に失敗しました。');
            Sgmov_Component_Log::err($e);
            Sgmov_Component_Redirect::redirectPublicSsl("/bpn/{$argBackInputPath}/");
            exit;
        }
        $db->commit();

        return $calcDataInfoData;
    }

     /**
     * 業務連携処理
     * @return type
     */
     protected function sendDataToGyomu($db, $inForm, $comiketId, $calcDataInfoData, $backInputPath, $sessionForm, $session, $toiawaseNoBuppan, $bpnType){

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

            Sgmov_Component_Redirect::redirectPublicSsl("/bpn/{$backInputPath}/");
            exit;
        }

        ////////////////////////////////////////////////////////////////////////////////////////////

        $liTreeData = $calcDataInfoData["treeDataForBuppan"];

        if($liTreeData['payment_method_cd']  == '4' // コンビニ後払い
                && $inForm['merchant_result'] == '0'
                ) {
            $outForm = $this->_createOutFormByInForm($inForm);
            throw new Sgmov_Component_Exception("", 500 ,NULL, array(
                'outForm' => $outForm,
                'payment_method_cd'=> $liTreeData['payment_method_cd'],
                    ));
        }


        Sgmov_Component_Log::debug('物販画面⇒業務連携バッチ実行開始');
        // オンラインバッチ起動（業務連携）
        $bcmResult = $this->_BcmView->execute($comiketId);

        Sgmov_Component_Log::debug('物販画面⇒業務連携バッチ実行終了');
        Sgmov_Component_Log::debug($bcmResult);

        $this->sendCompleteMail2(array('treeData' => $liTreeData), $inForm, $bpnType);

        return array(
            'inform' => $inForm,
            'li' => array('treeData' => $liTreeData)
        );

    }

    /**
     * 完了メール送信
     * @param type $li
     * @param type $inForm
     */
    protected function sendCompleteMail2($li, $inForm, $bpnType) {
        // メール送信
        if ((!empty($li["treeData"]['mail']) && !empty($li["treeData"]['merchant_result']))
                || $li["treeData"]['payment_method_cd'] == '3' // 電子マネー
                || $li["treeData"]['payment_method_cd'] == '5' // 法人売掛
                ) {
            $li["treeData"]['sgf_res_shopOrderId'] = @$inForm['sgf_res_shopOrderId'];
            $li["treeData"]['sgf_res_transactionId'] = @$inForm['sgf_res_transactionId'];
            $li["treeData"]['payment_url'] = @$inForm['payment_url'];
            $ccMail = '';
            // if($this->isCharter($li["treeData"])) {
            //     $ccMail = Sgmov_Component_Config::getComiketCharterFinMailCc();
            // } else if($this->isCargo($li["treeData"])) {
            //     $ccMail = Sgmov_Component_Config::getComiketCargoFinMailCc();
            // }


            $toMail = $li["treeData"]['mail'];
            if($inForm["bpn_type"] == "2" && ($inForm["shohin_pattern"] == "1" || $inForm["shohin_pattern"] == "2")){// 卓上飛沫ブロック
                $toMail = Sgmov_Component_Config::getBuppanCompleteMail();
            }

            if($bpnType == "1"){// 事前物販
                $this->sendCompleteMail($li["treeData"], $toMail, $ccMail);
            }else{
                //　梱包資材
                $this->sendCompleteMailForActiveShohin($li["treeData"], $toMail, $ccMail);
            }
        }
    }

    /**
     * チャーター便のチェック
     * @param type $comiket
     * return bool true：チャーター便 / false：チャーター便でない
     */
    private function isCharter($comiket) {
        $comiketDetailList = $comiket['comiket_detail_list'];

        foreach($comiketDetailList as $key => $val) {
            if($val['service'] == '3') { // チャーターの場合
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * カーゴ便のチェック
     * @param type $comiket
     * return bool true：カーゴ便 / false：カーゴ便でない
     */
    private function isCargo($comiket) {
        $comiketDetailList = $comiket['comiket_detail_list'];

        foreach($comiketDetailList as $key => $val) {
            if($val['service'] == '2') { // カーゴの場合
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * キャンセル処理に失敗した場合、管理者にメールする
     */
    public function errorInformationForSgFinancial($param) {
        // システム管理者メールアドレスを取得する。
        $mail_to = Sgmov_Component_Config::getLogMailTo();
        //テンプレートメールを送信する。
        Sgmov_Component_Mail::sendTemplateMail($param, dirname(__FILE__) . '/../../mail_template/bpn_error_for_sgfinancial.txt', $mail_to);
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
        $date_convenience_store->modify($max_day);
        $pay_limit_convenience_store = $date_convenience_store->format('Y/m/d');

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

        $inForm = (array) $inForm;
        $inForm['authorization_cd'] = '';
        $inForm['receipt_cd']       = '';

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

        // 問い合わせ番号
        $inForm['comiket_toiawase_no'] = $toiawaseNo;

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
        $data->setAmount(strval($inForm['delivery_charge_buppan']));

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
        $data->setAmount(strval($inForm['delivery_charge_buppan']));

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
     * @return Sgmov_Form_Pcr004Out 出力フォーム
     */
    public function _createOutFormByInForm($inForm) {

        $outForm = new Sgmov_Form_Bpn004Out();

        $outForm->raw_convenience_store_cd_sel = @$inForm['comiket_convenience_store_cd_sel'];
        $outForm->raw_mail = @$inForm['comiket_mail'];
        $outForm->raw_merchant_result = @$inForm['merchant_result'];
        $outForm->raw_payment_method_cd_sel = @$inForm['comiket_payment_method_cd_sel'];
        $outForm->raw_payment_url = isset($inForm['payment_url']) ? $inForm['payment_url'] : null;
        $outForm->raw_receipt_cd = @$inForm['receipt_cd'];
        $outForm->raw_comiket_detail_type_sel = @$inForm['comiket_detail_type_sel'];
        $outForm->raw_sgf_shop_order_id = "";
        if(isset($inForm['sgf_res_shopOrderId'])) {
            $outForm->raw_sgf_shop_order_id = @$inForm['sgf_res_shopOrderId'];
        }
        $outForm->raw_sgf_transaction_id = "";
        if(isset($inForm['sgf_res_transactionId'])) {
            $outForm->raw_sgf_transaction_id = @$inForm['sgf_res_transactionId'];
        }

        $outForm->raw_eventsub_cd_sel = @$inForm['eventsub_sel'];
        $outForm->raw_bpn_type = @$inForm['bpn_type'];
        $outForm->raw_shohin_pattern = $inForm["shohin_pattern"];
        $outForm->raw_shikibetsushi = $inForm["shikibetsushi"];

        return $outForm;
    }

    /**
     * システム管理者へ失敗メールを送信
     * @return
     */
    public function errorInformation($parm = array())
    {
        // システム管理者メールアドレスを取得する。
        $mail_to = Sgmov_Component_Config::getLogMailTo();
        //テンプレートメールを送信する。
        Sgmov_Component_Mail::sendTemplateMail($parm, dirname(__FILE__) . '/../../mail_template/bpn_error.txt', $mail_to);
    }

    /**
     * 決済用データの入力値の妥当性検査を行います。
     * @param $checkForm 決済用データ
     * @param Sgmov_Form_Eve003In $inForm 入力フォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _transact($checkForm, $inForm) {
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

        // 物販タイプを判定する。
        $screen = "卓上飛沫ブロッカー";
        if(!empty($inForm["bpn_type"]) && $inForm["bpn_type"] == "2"){
            $screen = "当日物販サービス";
        }

        // 決済の実行
        $transaction = new TGMDK_Transaction();
        $response = $transaction->execute($checkForm, $props);
        if (!isset($response)) {
            // 予期しない例外
            $inForm['merchant_result'] = '0';
            $inForm['receipted'] = NULL;
            Sgmov_Component_Log::debug('予期しない例外');
            $this->errorInformation(array("payment_order_id" => "","event_id" => $inForm['comiket_id'],"errMsg" => "No response to the transaction execution　with parameters checkForm=".json_encode($checkForm)." and props=".json_encode($props), "screen" => $screen));
        } else {
            // 想定応答の取得
            Sgmov_Component_Log::debug('想定応答の取得');

            // 取引ID取得
            $resultOrderId = $response->getOrderId();
            Sgmov_Component_Log::debug($resultOrderId);

            // 結果コード取得
            $resultStatus = $response->getMStatus();
            Sgmov_Component_Log::debug($resultStatus);

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
                    Sgmov_Component_Log::debug('成功');
                    break;
                case 'pending';
                    // 失敗
                    $inForm['merchant_result'] = '0';
                    Sgmov_Component_Log::debug('失敗');
                    break;
                case 'failure';
                    $this->errorInformation(array("payment_order_id" => $inForm['payment_order_id'],"event_id" => $inForm['comiket_id'],"errMsg" => $errorMessage, "screen" => $screen));
                default:
                    // 失敗
                    $inForm['merchant_result'] = '0';
                    Sgmov_Component_Log::debug('失敗');
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
                    break;
                case '1': // コンビニ決済
                    // 受付番号
                    $inForm['receipt_cd'] = $response->getReceiptNo();
                    $inForm['receipted'] = NULL;
                    // 払込票URL
                    $inForm['payment_url'] = $response->getHaraikomiUrl();
                    break;
                default:
                    break;
            }

        }

        return $inForm;
    }

    /**
     * 商品マスタ情報から、商品名を取得する。
     *
     * @param array comiketBoxList 
     * @return array returnList 
     *
     **/
    protected function filterShohinResult($shohinList){
        $returnList = array();
        foreach ($shohinList as $key => $value) {
            $returnList[$value['id']] = $value['name'];
        }

        return $returnList;
    }
}