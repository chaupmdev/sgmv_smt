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
Sgmov_Lib::useView('fmt/Common', 'CommonConst');
Sgmov_Lib::useView('fmt/Bcm');
Sgmov_Lib::useForms(array('Error', 'EveSession', 'Eve004Out', ));
Sgmov_Lib::useServices(array('Comiket', 'ComiketDetail', 'ComiketBox', 'ComiketCargo', 'ComiketCharter'
    , 'CenterMail', 'SgFinancial', 'HttpsZipCodeDll', 'CargoFare', 'BoxFare', 'Charter', 'OutBoundCollectCal', 'Eventsub'));
Sgmov_Lib::useImageQRCode();
Sgmov_Lib::use3gMdk(array('3GPSMDK'));
/**#@-*/

/**
 * イベント手荷物受付サービスのお申し込み内容を登録し、完了画面を表示します。
 * @package    View
 * @subpackage EVE
 * @author     K.Sawada
 * @copyright  2018-2019 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Fmt_Complete extends Sgmov_View_Fmt_Common {

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

    private $_BoxFareService;

    protected $_CharterService;

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
        $this->_CargoService        = new Sgmov_Service_Cargo();
        $this->_CargoFareService    = new Sgmov_Service_CargoFare();
        $this->_BuildingService     = new Sgmov_Service_Building();
        $this->_CharterService      = new Sgmov_Service_Charter();

        $this->_Comiket             = new Sgmov_Service_Comiket();
        $this->_ComiketDetail       = new Sgmov_Service_ComiketDetail();
        $this->_ComiketBox          = new Sgmov_Service_ComiketBox();
        $this->_ComiketCargo        = new Sgmov_Service_ComiketCargo();
        $this->_ComiketCharter      = new Sgmov_Service_ComiketCharter();

        $this->_SgFinancial         = new Sgmov_Service_SgFinancial();
        $this->_centerMailService   = new Sgmov_Service_CenterMail();
        $this->_HttpsZipCodeDll     = new Sgmov_Service_HttpsZipCodeDll();
        $this->_BoxFareService      = new Sgmov_Service_BoxFare();
        $this->_CharterService      = new Sgmov_Service_Charter();

        $this->_BcmView             = new Sgmov_View_Fmt_Bcm();

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
    public function executeInner() {

        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();

        //チケットの確認と破棄
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_EVE003, $this->_getTicket());

        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        // セッションから情報を取得
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        if (empty($sessionForm)) {
            $sessionForm = new Sgmov_Form_EveSession();
        }

        $inForm = $this->_createDataByInForm($db, $sessionForm->in);

Sgmov_Component_Log::debug('############################### 1201');
Sgmov_Component_Log::debug($inForm);

        // 搬入出の申込期間チェック
        $this->checkCurrentDateWithInTerm($inForm);
        
        /////////////////////////////////////////////////////////////////////////////////////////////
        // 搬入-申込Stチェック
        /////////////////////////////////////////////////////////////////////////////////////////////
        if($inForm['comiket_detail_type_sel'] == "1") { // 搬入
            $yearColl = @$inForm['comiket_detail_outbound_collect_date_year_sel'];
            $monthColl = @$inForm['comiket_detail_outbound_collect_date_month_sel'];
            $dayColl = @$inForm['comiket_detail_outbound_collect_date_day_sel'];
            
            $lastTime = $this->getLastSyukaTime();
            $dateColl = date("Y-m-d {$lastTime}", strtotime("{$yearColl}-{$monthColl}-{$dayColl}-1day"));
            $dateNow = date('Y-m-d H:i:s');

            if ($dateColl <= $dateNow) {
                // セッション情報を破棄
                $session->deleteForm(self::FEATURE_ID);
                Sgmov_Component_Redirect::redirectPublicSsl("/fmt/error/?t=受付時間が終了しました&m=お預かり日：{$yearColl}/{$monthColl}/{$dayColl}の受付時間が終了しました");
                exit;
            }
        }
        /////////////////////////////////////////////////////////////////////////////////////////////
        
        /////////////////////////////////////////////////////////////////////////////////////////////
        // 搬出-クール便申込時間チェック
        /////////////////////////////////////////////////////////////////////////////////////////////
        $this->checkCoolbinClosingDate($inForm);
        /////////////////////////////////////////////////////////////////////////////////////////////
        
        // ********************************************************************************************************
        // 日付またぎチェックコードを挿入する
        // ********************************************************************************************************



        ///////////////////////////////////////////////////////////////////////////////////////////////
        // DB登録時に必要なデータをセットする
        ///////////////////////////////////////////////////////////////////////////////////////////////
        $this->setYubinDllInfoToInForm($inForm);

        //登録用IDを取得
        $comiketId = $this->_Comiket->select_id($db);

        // 2038年問題対応のため、date()ではなくDateTime()を使う
        // DateTime::createFromFormat()はPHP5.3未満で対応していない
        if (method_exists('DateTime', 'createFromFormat')) {
            $date = DateTime::createFromFormat('U.u', gettimeofday(true))
                ->setTimezone(new DateTimeZone('Asia/Tokyo'));
        } else {
            $date = new DateTime();
        }
        
        $isErrSgfCancelApi = false; // SGFキャンセルAPI失敗フラグ

        if ($inForm['comiket_div'] == self::COMIKET_DEV_INDIVIDUA) { // 個人
//Sgmov_Component_Log::debug("########### 801-3");
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
                        $calcDataInfo = $calcDataInfoData["treeData"];

//                        $comiketDataInfo["treeData"]["amount"] = $calcDataInfo['amount'];
//                        $comiketDataInfo["treeData"]["amount_tax"] = $calcDataInfo['amount_tax'];


//Sgmov_Component_Log::debug("############ SgFinancial.requestSgFinancialService");
//Sgmov_Component_Log::debug($comiketDataInfo["treeData"]);
                        $resultData = array();
                        try {
                            $resultData = $this->_SgFinancial->requestSgFinancialService($calcDataInfo);
                            $inForm['sgf_res_autoAuthoriresult'] = $resultData["transactionInfo"]["autoAuthoriresult"];
                            $inForm['sgf_res_shopOrderId'] = @$resultData["transactionInfo"]["shopOrderId"];
                            $inForm['sgf_res_transactionId'] = @$resultData["transactionInfo"]["transactionId"];
                        } catch(Exception $e) {
                            $inForm['sgf_res_transactionId'] = $inForm['sgf_res_shopOrderId'] = $inForm['sgf_res_autoAuthoriresult'] = NULL;
                        }
//                        $res = $resultData;

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
Sgmov_Component_Log::debug('################################## 1011-0');
                                    Sgmov_Component_Log::info("SgFinancial-cancel-OK");
                                } else {
Sgmov_Component_Log::debug('################################## 1011-1');
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
Sgmov_Component_Log::debug('################################## 1011-2');
                            $inForm['sgf_res_autoAuthoriresult'] = NULL;
                            throw new Exception("Error in ".__FILE__." at line ".__LINE__.": requestSgFinancialService transaction did not successfully return : ".json_encode($resultData)." !");
                        }

                        $inForm['merchant_result'] = '1';

                    } catch (Exception $e) {
Sgmov_Component_Log::debug('################################## 1011-3');
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
                    $checkForm = $this->_createCheckCreditCardDataByInForm($inForm);
                    if (!empty($checkForm)) {
                        // ベリトランスデータ送信 $inFormの決済データ送信結果、入金確認日時、コンビニ後払自動審査結果は以下関数で設定
                        $inForm = $this->_transact($checkForm, $inForm);
                    }
                    break;
                case '1': // コンビニ決済(先払い)
                    $checkForm = $this->_createCheckConvenienceStoreDataByInForm($db, $inForm);
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
//            $inForm['merchant_result'] = '1'; // 決済データ送信結←tahira change 0を入れると申込完了メールが送信されない為1を固定セットにする
            $inForm['merchant_result'] = '0'; // 決済データ送信結果
            $inForm['merchant_datetime'] = NULL; // 決済データ送信日時
            $inForm['receipted'] = NULL; // 入金確認日時
            $inForm['auto_authoriresult'] = NULL; // コンビニ後払自動審査結果
        }
Sgmov_Component_Log::debug("########### 801-35");
Sgmov_Component_Log::debug($inForm['comiket_payment_method_cd_sel']);

        $backInputPaht = "input2";
        if (@empty($inForm['comiket_id'])) {
            $backInputPaht = "input";
        }

        $db->begin();
        try {

            ////////////////////////////////////////////////////////////
            // 基本データ作成
            ////////////////////////////////////////////////////////////
//            $comiketDataInfo = $this->_cmbTableDataFromInform($inForm, $comiketId);

            ////////////////////////////////////////////////////////////
            // 料金計算
            ////////////////////////////////////////////////////////////
            $calcDataInfoData = $this->calcEveryKindData($inForm, $comiketId);
            $calcDataInfo = $calcDataInfoData["treeData"];
            $comiketDataInfoFlat = $calcDataInfoData["flatData"];
            // TODO 料金切上/切下計算
//            $comiketDataInfoFlat["comiketData"]['amount'] = $calcDataInfo['amount'];
//            $comiketDataInfoFlat["comiketData"]['amount_tax'] = $calcDataInfo['amount_tax'];

            ////////////////////////////////////////////////////////////
            // DB登録
            ////////////////////////////////////////////////////////////
//Sgmov_Component_Log::debug("################# 401");
//Sgmov_Component_Log::debug($comiketDataInfoFlat["comiketData"]);
Sgmov_Component_Log::debug("################# 405-1");
Sgmov_Component_Log::debug($comiketDataInfoFlat["comiketData"]);
            $this->_Comiket->insert($db, $comiketDataInfoFlat["comiketData"]);
Sgmov_Component_Log::debug("################# 405-2");
            foreach ($comiketDataInfoFlat["comiketDetailDataList"] as $key => $val) {
                $this->_ComiketDetail->insert($db, $val);
            }
Sgmov_Component_Log::debug("################# 405-3");
            foreach ($comiketDataInfoFlat["comiketBoxDataList"] as $key => $val) {
                $this->_ComiketBox->insert($db, $val);
            }
//Sgmov_Component_Log::debug("################# 405-4");
//            foreach ($comiketDataInfoFlat["comiketCargoDataList"] as $key => $val) {
//                $this->_ComiketCargo->insert($db, $val);
//            }
//Sgmov_Component_Log::debug("################# 405-5");
//            foreach ($comiketDataInfoFlat["comiketCharterDataList"] as $key => $val) {
//                $this->_ComiketCharter->insert($db, $val);
//            }

        } catch (Exception $e) {
            $db->rollback();
            $_SESSION["Sgmov_View_Eve.inputErrorInfo"] = array("db_insert_error" => "・データベースの登録・更新に失敗しました。");

            Sgmov_Component_Log::debug("リダイレクト /fmt/{$backInputPaht}/");
            Sgmov_Component_Log::err('データベースの登録・更新に失敗しました。');
            Sgmov_Component_Log::err($e);
            Sgmov_Component_Redirect::redirectPublicSsl("/fmt/{$backInputPaht}/");
            exit;
        }
        $db->commit();
        
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

            Sgmov_Component_Redirect::redirectPublicSsl("/fmt/{$backInputPaht}/");
            exit;
        }

        $li = $this->_cmbTableDataFromInForm($inForm, $comiketId);

        if($li["treeData"]['payment_method_cd']  == '4' // コンビニ後払い
                && $inForm['merchant_result'] == '0'
                ) {
            $outForm = $this->_createOutFormByInForm($inForm);
            throw new Sgmov_Component_Exception("", 500 ,NULL, array(
                'outForm' => $outForm,
                'payment_method_cd'=> $li["treeData"]['payment_method_cd'],
                    ));
        }

        // オンラインバッチ起動（業務連携）
        $bcmResult = $this->_BcmView->execute($comiketId);

        // 支払種別が【コンビニ後払い】の場合
//        Sgmov_Component_Log::info('支払種別：' . @$comiketDataInfo["treeData"]['payment_method_cd']);

        if (@$comiketDataInfo["treeData"]['payment_method_cd'] == self::PAYMENT_METHOD_CONVINI_AFTER) {
//            Sgmov_Component_Log::info('取引API結果：' . $resultData['result']);
//            Sgmov_Component_Log::info('業務連携結果：' . $bcmResult['sendSts']);
            // 取引登録が正常終了かつ業務連携バッチ処理の結果が成功なら出荷報告処理を行う
            if ($resultData['result'] == 'OK' && @$bcmResult['sendSts'] == '0') {
//                foreach ($bcmResult as $key => $val) {
//                    Sgmov_Component_Log::info($key . '：' . $val);
//                }

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
                    $param = array('res_sgf_transactionId' => $resultData["transactionInfo"]["transactionId"]);
                    $mail_to = Sgmov_Component_Config::getLogMailTo();
                    Sgmov_Component_Mail::sendTemplateMail($param, dirname(__FILE__) . '/../../mail_template/eve_error_for_sgfinancial_shipment_report.txt', $mail_to);
                    Sgmov_Component_Log::info('SgFinancial-ShipmentReport-NG');
                    Sgmov_Component_Log::info($shipmentCompResArr);
                }
            } else {
                // 出荷報告処理APIが実行されなかった場合はシステム管理者にエラーメールを送信する
                Sgmov_Component_Log::info("@@@@ 出荷報告処理APIの未実行となった場合はシステム管理者にエラーメールを送信する");
                $param = array('res_sgf_transactionId' => @$resultData["transactionInfo"]["transactionId"]);
                $mail_to = Sgmov_Component_Config::getLogMailTo();
                Sgmov_Component_Mail::sendTemplateMail($param, dirname(__FILE__) . '/../../mail_template/fmt_error_for_sgfinancial_shipment_report.txt', $mail_to);
                Sgmov_Component_Log::info('SgFinancial-ShipmentReport-NG');
            }
        }

//Sgmov_Component_Log::debug($inForm);

        $li["treeData"]['amount'] = $calcDataInfo['amount'];
        $li["treeData"]['amount_tax'] = $calcDataInfo['amount_tax'];

Sgmov_Component_Log::debug("########### 801-5");
//Sgmov_Component_Log::debug($li);
Sgmov_Component_Log::debug($li["treeData"]);

        $type = $li['flatData']['comiketDetailDataList'][0]['type']; // 往路か復路かの区分
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
            $this->sendCompleteMail($li["treeData"], $li["treeData"]['mail'], $ccMail, $type);
        }

        // 出力情報を設定
        $outForm = $this->_createOutFormByInForm($inForm);

        // 出力情報を設定
        $outForm->raw_qr_code_string = $comiketId;
        // セッション情報を破棄
        $session->deleteForm(self::FEATURE_ID);

        $eventData = $this->_EventService->fetchEventById($db, $li["treeData"]['event_id']);
        $eventsubData = $this->_EventsubService->fetchEventsubByEventsubId($db, $li["treeData"]['eventsub_id']);

        return array(
            'outForm' => $outForm,
            'eventData' => $eventData,
            'eventsubData' => $eventsubData,
            'collect_date' => date('Y年n月j日', strtotime($li['flatData']['comiketDetailDataList'][0]['collect_date'])),
            'type' => $type,
            'payment_method_cd'=> $li["treeData"]['payment_method_cd'],
            );
    }

    /**
     *
     * @param type $comiket
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
     *
     * @param type $comiket
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
     *
     * @param type $param
     */
    public function errorInformationForSgFinancial($param) {
        // システム管理者メールアドレスを取得する。
        $mail_to = Sgmov_Component_Config::getLogMailTo();
        //テンプレートメールを送信する。
        Sgmov_Component_Mail::sendTemplateMail($param, dirname(__FILE__) . '/../../mail_template/eve_error_for_sgfinancial.txt', $mail_to);
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
Sgmov_Component_Log::debug($pay_limit_convenience_store);
/*
        // SGムービングの支払期限
        $embarkation_date = $this->_TravelService->fetchEmbarkationDate($db, array('travel_id' => $inForm['travel_cd_sel']));
        $departure = $this->_TravelTerminalService->fetchTravelDeparture($db, array('travel_id' => $inForm['travel_cd_sel']), true);
        if (!empty($embarkation_date)) {
            $date = new DateTime($embarkation_date);
        } elseif (!empty($departure['dates'])) {
            $date = new DateTime($departure['dates'][array_search($inForm['travel_departure_cd_sel'], $departure['ids'])]);
        }
        if (!empty($date)) {
            $date->modify('-10 day');
            $pay_limit = $date->format('Y/m/d');
Sgmov_Component_Log::debug($pay_limit);
        }
*/
        if (empty($pay_limit) || $pay_limit > $pay_limit_convenience_store) {
            $pay_limit = $pay_limit_convenience_store;
        }
Sgmov_Component_Log::debug($pay_limit);
/*
        if (!empty($inForm['cargo_collection_date_year_cd_sel'])
                && !empty($inForm['cargo_collection_date_month_cd_sel'])
                && !empty($inForm['cargo_collection_date_day_cd_sel'])) {
            $date2 = new DateTime($inForm['cargo_collection_date_year_cd_sel']
                    . '/' . $inForm['cargo_collection_date_month_cd_sel']
                    . '/' . $inForm['cargo_collection_date_day_cd_sel']);
            switch ($date2->format('N')) {
                case '1': // 月
                case '2': // 火
                    $date2->modify('-4 day');
                    break;
                case '3': // 水
                case '4': // 木
                case '5': // 金
                case '6': // 土
                    $date2->modify('-2 day');
                    break;
                case '7': // 日
                    $date2->modify('-3 day');
                    break;
                default:
                    break;
            }
            $pay_limit2 = $date2->format('Y/m/d');
Sgmov_Component_Log::debug($pay_limit2);
            if (empty($pay_limit) || $pay_limit > $pay_limit2) {
                $pay_limit = $pay_limit2;
            }
Sgmov_Component_Log::debug($pay_limit);
        }
*/
Sgmov_Component_Log::debug($pay_limit);
        return $pay_limit;
    }

    /**
     * 入力フォームの値を元にデータを生成します。
     * @param Sgmov_Form_Pcr001-003In $inForm 入力フォーム
     * @return array データ
     */
    public function _createDataByInForm($db, $inForm) {

        // TODO オブジェクトから値を直接取得できるよう修正する
        // オブジェクトから取得できないため、配列に型変換
        $inForm = (array) $inForm;
//Sgmov_Component_Log::debug("############################################### 101");
//Sgmov_Component_Log::debug($inForm);

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

//        $inForm['delivery_charge'] = "2550";

        $inForm['merchant_datetime'] = $date->format('Y/m/d H:i:s.u');

        $inForm['payment_order_id'] = 'sagawa-moving-event_' . $date->format('YmdHis') . '_' . str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');
        $inForm['pay_limit'] = $this->_getPayLimit($db, $inForm);

        return $inForm;
    }

    /**
     *
     * @param type $inForm
     */
//    public function _createCheckConvenienceStoreDeferred($inForm) {
//        $resultInfo = array();
//
//        // function defination to convert array to xml
//        function cnvArrayToXml( $data, &$xml_data ) {
//            foreach( $data as $key => $value ) {
//                if( is_numeric($key) ){
//                    $key = 'item'.$key; //dealing with <0/>..<n/> issues
//                }
//                if( is_array($value) ) {
//                    $subnode = $xml_data->addChild($key);
//                    cnvArrayToXml($value, $subnode);
//                } else {
//                    $xml_data->addChild("$key",htmlspecialchars("$value"));
//                }
//             }
//        }
//
//        // initializing or creating array
//        $data = array('total_stud' => 500);

        // creating object of SimpleXMLElement
        /*
        $xml_data = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><data></data>');
        */
        // function call to convert array to xml
//        cnvArrayToXml($data,$xml_data);
//
//        //saving generated xml file;
//        $result = $xml_data->asXML('/file/path/name.xml');
//
//        return $resultInfo;
//    }

    /**
     * 入力フォームの値を元にクレジットカード決済用データを生成します。
     * @param Sgmov_Form_Pcr001-003In $inForm 入力フォーム
     * @return array 決済用データ
     */
    public function _createCheckCreditCardDataByInForm($inForm) {
Sgmov_Component_Log::debug('############################ 22-1');
        // セキュリティコード
        $securityCode = htmlspecialchars($inForm['security_cd']);
Sgmov_Component_Log::debug('############################ 22-2');
        // 要求電文パラメータ値の指定
        $data = new CardAuthorizeRequestDto();
Sgmov_Component_Log::debug('############################ 22-3');
        // 取引ID
        $data->setOrderId($inForm['payment_order_id']);
Sgmov_Component_Log::debug('############################ 22-4');
        // 支払金額
        $data->setAmount(strval($inForm['delivery_charge']));
Sgmov_Component_Log::debug('############################ 22-5');
        // カード番号
        $data->setCardNumber($inForm['card_number']);
Sgmov_Component_Log::debug('############################ 22-6');
        // カード有効期限 MM/YY
        $cardExpire = $inForm['card_expire_month_cd_sel'] . '/' . substr($inForm['card_expire_year_cd_sel'], -2);
        $data->setCardExpire($cardExpire);
Sgmov_Component_Log::debug('############################ 22-7');
        // 与信方法
        $data->setWithCapture('true');
Sgmov_Component_Log::debug('############################ 22-8');
        // 支払オプション
/*
        $jpo1 = $inForm['jpo1'];
        $jpo2 = $inForm['jpo2'];
        switch ($jpo1) {
            case '61';
                $jpo = $jpo1.'C'.$jpo2;
                break;
            case '10';
            case '80';
            default:
                $jpo = $jpo1;
                break;
        }
*/
Sgmov_Component_Log::debug('############################ 22-9');
        // 支払は一回払い固定にする
        $jpo = '10';
        if (isset($jpo)) {
            $data->setJpo($jpo);
        }
Sgmov_Component_Log::debug('############################ 22-10');
        // セキュリティコード
        if (isset($securityCode)) {
            $data->setSecurityCode($securityCode);
        }
Sgmov_Component_Log::debug('############################ 22-11');
        return $data;
    }

    /**
     * 入力フォームの値を元にコンビニ決済用データを生成します。
     * @param Sgmov_Form_Pcr001-003In $inForm 入力フォーム
     * @return array 決済用データ
     */
    public function _createCheckConvenienceStoreDataByInForm($db, $inForm) {

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
//        $data->setName1($inForm['surname']);
        $data->setName1($inForm['comiket_personal_name_sei']);

//Sgmov_Component_Log::debug("############### 301");
//Sgmov_Component_Log::debug($inForm);
        // 名
        $data->setName2(!empty($inForm['comiket_personal_name_mei']) ? $inForm['comiket_personal_name_mei'] : '（申込者）');
//        $data->setName2($inForm['forename']);

        // 電話番号
//        $data->setTelNo($inForm['tel1'] . '-' . $inForm['tel2'] . '-' . $inForm['tel3']);
        $data->setTelNo($inForm['comiket_tel']);

        // 支払期限
        $data->setPayLimit($inForm['pay_limit']);

        // 支払区分
        // リザーブパラメータのため無条件に '0' を設定する
        $data->setPaymentType('0');

        return $data;
    }

    /**
     * 入力フォームの値を元にインサート用データを生成します。
     * @param Sgmov_Form_Pcr001-003In $inForm 入力フォーム
     * @return array インサート用データ
     */
//    public function _createInsertDataByInForm($inForm, $id) {
//
//        $cargo_collection_date = null;
//        if (!empty($inForm['cargo_collection_date_year_cd_sel'])
//                && !empty($inForm['cargo_collection_date_month_cd_sel'])
//                && !empty($inForm['cargo_collection_date_day_cd_sel'])) {
//            $cargo_collection_date = $inForm['cargo_collection_date_year_cd_sel']
//                    . '/' . $inForm['cargo_collection_date_month_cd_sel']
//                    . '/' . $inForm['cargo_collection_date_day_cd_sel'];
//        }
//
//        $batch_status = '0';
//        if (!empty($inForm['merchant_result']) && !empty($inForm['authorization_cd'])) {
//            $batch_status = '1';
//        }
//
//        $data = array(
//            'id'                       => $id,
//            'merchant_result'          => $inForm['merchant_result'],
//            'merchant_datetime'        => $inForm['merchant_datetime'],
//            'batch_status'             => $batch_status,
//            'surname'                  => $inForm['surname'],
//            'forename'                 => $inForm['forename'],
//            'surname_furigana'         => $inForm['surname_furigana'],
//            'forename_furigana'        => $inForm['forename_furigana'],
//            'number_persons'           => $inForm['number_persons'],
//            'tel'                      => $inForm['tel1'] . $inForm['tel2'] . $inForm['tel3'],
//            'mail'                     => $inForm['mail'],
//            'zip'                      => $inForm['zip1'] . $inForm['zip2'],
//            'pref_id'                  => $inForm['pref_cd_sel'],
//            'address'                  => $inForm['address'],
//            'building'                 => $inForm['building'],
//            'travel_id'                => $inForm['travel_cd_sel'],
//            'room_number'              => $inForm['room_number'],
//            'terminal_cd'              => $inForm['terminal_cd_sel'],
//            'departure_quantity'       => $inForm['departure_quantity'],
//            'arrival_quantity'         => $inForm['arrival_quantity'],
//            'travel_departure_id'      => !empty($inForm['travel_departure_cd_sel']) ? $inForm['travel_departure_cd_sel'] : null,
//            'cargo_collection_date'    => $cargo_collection_date,
//            'cargo_collection_st_time' => !empty($inForm['cargo_collection_st_time_cd_sel']) ? $inForm['cargo_collection_st_time_cd_sel'] . ':00' : null,
//            'cargo_collection_ed_time' => !empty($inForm['cargo_collection_ed_time_cd_sel']) ? $inForm['cargo_collection_ed_time_cd_sel'] . ':00' : null,
//            'travel_arrival_id'        => !empty($inForm['travel_arrival_cd_sel']) ? $inForm['travel_arrival_cd_sel'] : null,
//            'payment_method_cd'        => !empty($inForm['payment_method_cd_sel']) ? $inForm['payment_method_cd_sel'] : null,
//            'convenience_store_cd'     => !empty($inForm['convenience_store_cd_sel']) ? $inForm['convenience_store_cd_sel'] : null,
//            'authorization_cd'         => $inForm['authorization_cd'],
//            'receipt_cd'               => $inForm['receipt_cd'],
//            'payment_order_id'         => $inForm['payment_order_id'],
//        );
//
//        return $data;
//    }

    /**
     * 入力フォームの値を元にメール送信用データを生成します。
     * @param Sgmov_Form_Pin001In $inForm 入力フォーム
     * @return array インサート用データ
     */
//    public function _createMailDataByInForm($db, $inForm) {
//
//        $data = array();
//
////        $prefectures = $this->_PrefectureService->fetchPrefectures($db);
////
////        $travel    = $this->_TravelService->fetchTravel($db, array('travel_agency_id' => $inForm['travel_agency_cd_sel']));
////        $departure = $this->_TravelTerminalService->fetchTravelDeparture($db,
////                array('travel_id' => $inForm['travel_cd_sel']));
////        $arrival   = $this->_TravelTerminalService->fetchTravelArrival($db,
////                array('travel_id' => $inForm['travel_cd_sel']));
////
////        $cargo_collection_date = '';
////        if (!empty($inForm['cargo_collection_date_year_cd_sel'])
////                && !empty($inForm['cargo_collection_date_month_cd_sel'])
////                && !empty($inForm['cargo_collection_date_day_cd_sel'])) {
////            $cargo_collection_date = $inForm['cargo_collection_date_year_cd_sel']
////                    . '年' . ltrim($inForm['cargo_collection_date_month_cd_sel'], '0')
////                    . '月' . ltrim($inForm['cargo_collection_date_day_cd_sel'], '0')
////                    . '日';
////        }
////
////        $cargo_collection_st_time = '';
////        if (!empty($inForm['cargo_collection_st_time_cd_sel'])) {
////            if ($inForm['cargo_collection_st_time_cd_sel'] === '00') {
////                $cargo_collection_st_time = '指定なし';
////            } else {
////                $cargo_collection_st_time = ltrim($inForm['cargo_collection_st_time_cd_sel'], '0') . '時';
////            }
////        }
////
////        $cargo_collection_ed_time = '';
////        if (!empty($inForm['cargo_collection_ed_time_cd_sel'])) {
////            if ($inForm['cargo_collection_ed_time_cd_sel'] === '00') {
////                $cargo_collection_ed_time = '指定なし';
////            } else {
////                $cargo_collection_ed_time = ltrim($inForm['cargo_collection_ed_time_cd_sel'], '0') . '時';
////            }
////        }
////
////        $data = array(
////            'surname'                  => $inForm['surname'],
////            'forename'                 => $inForm['forename'],
////            'surname_furigana'         => $inForm['surname_furigana'],
////            'forename_furigana'        => $inForm['forename_furigana'],
////            'number_persons'           => $inForm['number_persons'],
////            'mail'                     => $inForm['mail'],
////            'tel'                      => $inForm['tel1'] . '-' . $inForm['tel2'] . '-' . $inForm['tel3'],
////            'zip'                      => $inForm['zip1'] . '-' . $inForm['zip2'],
////            'pref_name'                => $prefectures['names'][array_search($inForm['pref_cd_sel'], $prefectures['ids'])],
////            'address'                  => $inForm['address'],
////            'building'                 => $inForm['building'],
////            'travel_name'              => $travel['names'][array_search($inForm['travel_cd_sel'], $travel['ids'])],
////            'room_number'              => $inForm['room_number'],
////            'departure_quantity'       => $inForm['departure_quantity'],
////            'arrival_quantity'         => $inForm['arrival_quantity'],
////            'departure_name'           => $departure['names'][array_search($inForm['travel_departure_cd_sel'], $departure['ids'])],
////            'cargo_collection_date'    => $cargo_collection_date,
////            'cargo_collection_st_time' => $cargo_collection_st_time,
////            'cargo_collection_ed_time' => $cargo_collection_ed_time,
////            'arrival_name'             => @$arrival['names'][array_search($inForm['travel_arrival_cd_sel'], $arrival['ids'])],
////        );
////
////        // 受付番号
////        $data['mail_receipt_cd'] = '';
////        if (!empty($inForm['receipt_cd'])) {
////            $data['mail_receipt_cd'] = $inForm['receipt_cd'];
////        } elseif (!empty($inForm['authorization_cd'])) {
////            $data['mail_receipt_cd'] = $inForm['authorization_cd'];
////        }
////
////        // 集荷の往復
////        switch ($inForm['terminal_cd_sel']) {
////            case '1':
////                $data['terminal'] = '搬入のみ';
////                break;
////            case '2':
////                $data['terminal'] = '搬出のみ';
////                break;
////            case '3':
////                $data['terminal'] = '往復';
////                break;
////            default:
////                $data['terminal'] = '';
////                break;
////        }
////
////        // お支払方法
////        switch ($inForm['payment_method_cd_sel']) {
////            case '1':
////                $data['payment_method'] = 'コンビニ決済';
////                break;
////            case '2':
////                $data['payment_method'] = 'クレジットカード';
////                break;
////            default:
////                $data['payment_method'] = '';
////                break;
////        }
//
//        return $data;
//    }

    /**
     * セッションの値を元に出力フォームを生成します。
     * @param $inForm 入力フォーム
     * @return Sgmov_Form_Pcr004Out 出力フォーム
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
/*
        $payment_limit = '';
        if (!empty($inForm['cargo_collection_date_year_cd_sel'])
                && !empty($inForm['cargo_collection_date_month_cd_sel'])
                && !empty($inForm['cargo_collection_date_day_cd_sel'])) {
            $date = new DateTime($inForm['cargo_collection_date_year_cd_sel']
                    . '/' . $inForm['cargo_collection_date_month_cd_sel']
                    . '/' . $inForm['cargo_collection_date_day_cd_sel']);
            switch ($date->format('N')) {
                case '1': // 月
                case '2': // 火
                    $date->modify('-4 day');
                    break;
                case '3': // 水
                case '4': // 木
                case '5': // 金
                case '6': // 土
                    $date->modify('-2 day');
                    break;
                case '7': // 日
                    $date->modify('-3 day');
                    break;
                default:
                    break;
            }
            $payment_limit = $date->format('Y年m月d日');
            switch ($date->format('N')) {
                case '1':
                    $payment_limit .= '（月）';
                    break;
                case '2':
                    $payment_limit .= '（火）';
                    break;
                case '3':
                    $payment_limit .= '（水）';
                    break;
                case '4':
                    $payment_limit .= '（木）';
                    break;
                case '5':
                    $payment_limit .= '（金）';
                    break;
                case '6':
                    $payment_limit .= '（土）';
                    break;
                case '7':
                    $payment_limit .= '（日）';
                    break;
                default:
                    break;
            }
        }
        $outForm->raw_payment_limit = $payment_limit;
*/
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
        Sgmov_Component_Mail::sendTemplateMail($parm, dirname(__FILE__) . '/../../mail_template/eve_error.txt', $mail_to);
    }

    /**
     * 決済用データの入力値の妥当性検査を行います。
     * @param $checkForm 決済用データ
     * @param Sgmov_Form_Eve003In $inForm 入力フォーム
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
Sgmov_Component_Log::debug($response);
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
                    $this->errorInformation(array("payment_order_id" => $inForm['payment_order_id'],"event_id" => $inForm['comiket_id'],"errMsg" => $errorMessage));
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