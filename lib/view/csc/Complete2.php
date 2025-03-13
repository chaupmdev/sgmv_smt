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
Sgmov_Lib::useView('csc/Common', 'CommonConst');
Sgmov_Lib::useView('csc/Bcm');
Sgmov_Lib::useForms(array('Error', 'EveSession', 'Eve004Out',));
Sgmov_Lib::useServices(array(
    'Comiket', 'ComiketDetail', 'ComiketBox', 'CenterMail'
    , 'HttpsZipCodeDll', 'GyomuApi'
));
Sgmov_Lib::useImageQRCode();
Sgmov_Lib::use3gMdk(array('3GPSMDK'));
/**#@-*/

/**
 * イベント手荷物受付サービスのお申し込み内容を登録し、完了画面を表示します。
 * @package    View
 * @subpackage CSC
 * @author     K.Sawada
 * @copyright  2021-2021 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Csc_Complete2 extends Sgmov_View_Csc_Common
{

    const SEVEN_ELEVEN_CODE = 'sej';
    const E_CONTEXT_CODE    = 'econ';
    const WELL_NET_CODE     = 'other';

    /**
     * 支払方法：コンビニ後払い
     */
    const PAYMENT_METHOD_CONVINI_AFTER = 4;


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
     * 拠点メールアドレスサービス
     * @var Sgmov_Service_CenterMail
     */
    public $_gyomuApiService;

    /**
     * 業務連携
     */
    protected $_BcmView;


    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        $this->_Comiket             = new Sgmov_Service_Comiket();
        $this->_ComiketDetail       = new Sgmov_Service_ComiketDetail();
        $this->_ComiketBox          = new Sgmov_Service_ComiketBox();
        $this->_gyomuApiService     = new Sgmov_Service_GyomuApi();
        $this->_BcmView             = new Sgmov_View_Csc_Bcm();

        $this->_Comiket->setTrnsactionFlg(FALSE);
        $this->_ComiketDetail->setTrnsactionFlg(FALSE);
        $this->_ComiketBox->setTrnsactionFlg(FALSE);

        parent::__construct();
    }

    /**
     * コストコ配送サービスの申込完了登録、申込完了画面表示処理
     */
    public function executeInner($argBackInputPaht = "")
    {

        // セッションから入力画面の入力情報を取得
        $inputInfo = @$_SESSION["CSC"]['INPUT_INFO'];

        Sgmov_Component_Log::debug("======================================================================================");
        @Sgmov_Component_Log::debug($inputInfo);
        @Sgmov_Component_Log::debug($_SESSION);
        @Sgmov_Component_Log::debug($_COOKIE);
        Sgmov_Component_Log::debug("======================================================================================");

        if( @empty($_COOKIE['RAND_KEY_FOR_XSS'])
            || @empty($_COOKIE['RAND_KEY_FOR_XSS'])
            || @$_COOKIE['RAND_KEY_FOR_XSS'] != @$_SESSION['RAND_KEY_FOR_XSS']
        ) {
            Sgmov_Component_Log::err('####### XSS チェック失敗しました1 ###############');
            Sgmov_Component_Log::info('####### XSS チェック失敗しました2 ###############');
            Sgmov_Component_Log::debug('####### XSS チェック失敗しました3 ###############');
            $_SESSION['RAND_KEY_FOR_XSS'] =  NULL; // セッションクリア
            setcookie('RAND_KEY_FOR_XSS', '', time()-30, '/'); // クッキークリア
            Sgmov_Component_Redirect::redirectPublicSsl("/404.html");
            exit;
        }
        $_SESSION['RAND_KEY_FOR_XSS'] =  NULL; // セッションクリア
        setcookie('RAND_KEY_FOR_XSS', '', time()-30, '/'); // クッキークリア
        Sgmov_Component_Log::debug("======================================================================================");

        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        $eventsubId = $inputInfo['c_eventsub_id'];

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////
        // 入力チェック
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////
        $errInfoList = $this->checkInput($inputInfo);
        if (@!empty($errInfoList)) {
            Sgmov_Component_Log::err('データ登録に失敗しました。');
            Sgmov_Component_Log::err('入力された値に誤りがあります。');
            Sgmov_Component_Log::err($errInfoList);
            return array(
                'status' => 'error',
                'message' => '入力された値に誤りがあります。<br/>適用開始日、終了日により、顧客マスタを取得できてない可能性があります。',
                'data' => array(),
            );
        }
        Sgmov_Component_Log::debug("======================================================================================");


        ///////////////////////////////////////////////////////////////////////////////////////////////////////////
        // 業務側から請求先問番取得
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////
        // $toiawaseNo = '999999999';
        $toiawaseNo = $this->getToiawaseNo();
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////
        Sgmov_Component_Log::debug("======================================================================================");
        $inForm = $this->_createDataByInForm($db, $inputInfo, $toiawaseNo);

        ///////////////////////////////////////////////////////////////////////////////////////////////
        // 業務側から配送元、配送先の住所情報を取得する
        ///////////////////////////////////////////////////////////////////////////////////////////////
        $this->setYubinDllInfoToInForm($inForm);

        //登録用IDを取得
        $comiketId = $this->_Comiket->select_id($db);

        //  comiket、comiket_detail、comiket_boxに申込情報登録
        $calcDataInfoData = $this->registerComiket($db, $inForm, $comiketId);
        if (isset($calcDataInfoData['status']) && $calcDataInfoData['status'] == 'error') {
            Sgmov_Component_Log::err('適用開始日、終了日により、顧客マスタを取得できてない可能性があります。');
            return array(
                'status' => 'error',
                'message' => '申込情報の登録を失敗しました。<br/>適用開始日、終了日により、顧客マスタを取得できてない可能性があります。',
            );
        }
        Sgmov_Component_Log::debug("======================================================================================");
        // 業務連携
        //2023/01/16 GiapLN update ticket #SMT6-352
        try {
            $returnResult = $this->sendDataToGyomu($db, $inForm, $comiketId, $calcDataInfoData, $toiawaseNo);
        } catch (Exception $ex) {
            Sgmov_Component_Log::err($ex->getMessage());
            return array(
                'status' => 'error',
                'message' => '申込情報の登録を失敗しました。<br/>適用開始日、終了日により、顧客マスタを取得できてない可能性があります。',
            );
        }
        

        // 初期設定
        $inputInfo['c_kanri_no'] = '';
        $inputInfo['c_option_cd'] = '0';
        $inputInfo['c_recycl_cd'] = '0';
        $inputInfo['l_recycl_name'] = '';
        $inputInfo['c_kaidan_cd'] = '0';
        @$_SESSION["CSC"]['INPUT_INFO'] = $inputInfo;
        return array(
            'status' => 'success',
            'message' => '登録完了しました。',
            'res_data' => array(
                'comiket_id' => $comiketId,
                'eventsub_id' => $eventsubId,
            ),
        );
    }

    /**
     * コミケテーブル.請求書問番取得.
     * @return type
     */
    private function getToiawaseNo()
    {
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
            $mailData['errMsg'] = mb_substr($mailData['errMsg'], 0, 1000);

            $mailTo = Sgmov_Component_Config::getLogMailTo();
            $objMail = new Sgmov_Service_CenterMail();
            $objMail->_sendThankYouMail($mailTemplateList, $mailTo, $mailData);

            $title = urlencode("システムエラー");
            $message = urlencode("エラーが発生しました。時間がたってからもう一度やりなおしてください。");
            Sgmov_Component_Redirect::redirectPublicSsl("/dsn/error?t={$title}&m={$message}");
        }
        return $toiawaseNoInfo['toiawaseNo'];
    }

    /**
     * comiket、comiket_detail、comiket_boxに申込情報登録
     *
     * @param [type] $db
     * @param [type] $inForm
     * @param [type] $comiketId
     * @return void
     */
    protected function registerComiket($db, $inForm, $comiketId)
    {
        $db->begin();
        try {

            ////////////////////////////////////////////////////////////
            // 料金計算
            ////////////////////////////////////////////////////////////
            $calcDataInfoData = $this->calcEveryKindData($inForm, $comiketId, false);
            $comiketDataInfoFlat = $calcDataInfoData["flatData"];

            ////////////////////////////////////////////////////////////
            // DB登録
            ////////////////////////////////////////////////////////////

            /** コミケ申込データ　**/
            Sgmov_Component_Log::debug($comiketDataInfoFlat["comiketData"]);
            $this->_Comiket->insert($db, $comiketDataInfoFlat["comiketData"]);

            /** コミケ申込明細データ　**/
            foreach ($comiketDataInfoFlat["comiketDetailDataList"] as $key => $val) {
                $this->_ComiketDetail->insert($db, $val);
            }

            /** コミケ申込宅配データ　**/
            foreach ($comiketDataInfoFlat["comiketBoxDataList"] as $key => $val) {
                $this->_ComiketBox->insert($db, $val);
            }
        } catch (Exception $e) {
            Sgmov_Component_Log::err('データ登録に失敗しました。');
            Sgmov_Component_Log::err($e);
            return array(
                'status' => 'error',
                'message' => 'データ登録に失敗しました。',
                'data' => array(),
            );
        }
        $db->commit();

        return $calcDataInfoData;
    }

    /**
     * 業務連携
     *
     * @param [type] $db
     * @param [type] $inForm
     * @param [type] $comiketId
     * @return void
     */
    protected function sendDataToGyomu($db, $inForm, $comiketId, $calcDataInfoData, $toiawaseNo)
    {

        $liTreeData = $calcDataInfoData["treeData"];

        Sgmov_Component_Log::debug('################# 2001 業務連携バッチ実行開始');
        // オンラインバッチ起動（業務連携）
        $bcmResult = $this->_BcmView->execute($comiketId);

        Sgmov_Component_Log::debug('################# 2001 業務連携バッチ実行終了');
        Sgmov_Component_Log::debug($bcmResult);

        // 問い合わせ番号
        $liTreeData["toiawase_no"] = $toiawaseNo;

        $this->sendCompleteMail2(array('treeData' => $liTreeData), $inForm);

        return array(
            'inform' => $inForm,
            'li' => array('treeData' => $liTreeData)
        );
    }

    /**
     * 申込完了メール送信
     *
     * @param type $li
     * @param type $inForm
     */
    protected function sendCompleteMail2($li, $inForm, $type = '')
    {
        // 往路か復路かの区分
        $comiketDetailDataListType = $li['treeData']['comiket_detail_list'][0]['type'];

        // メール送信
        if (!empty($li["treeData"]['mail'])) {
            $this->sendCompleteMail($li["treeData"], $li["treeData"]['mail'], array(), $comiketDetailDataListType, '', $inForm);
        }
    }


    /**
     * 入力フォームの値を元にデータを生成します。
     * @param Sgmov_Form_Pcr001-003In $inForm 入力フォーム
     * @return array データ
     */
    public function _createDataByInForm($db, $inForm, $toiawaseNo = '0123456789')
    {
        // TODO オブジェクトから値を直接取得できるよう修正する
        // オブジェクトから取得できないため、配列に型変換
        $inForm = (array) $inForm;
        // 問合せ番号
        $inForm['comiket_toiawase_no'] = $toiawaseNo;

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////
        // comiket登録前に業務側から荷動き先問番取得
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////
        // $inForm['comiket_toiawase_no_niugoki'] = $this->getToiawaseNo();
        $inForm['comiket_toiawase_no_niugoki'] = NULL;

        return $inForm;
    }


    /**
     * システム管理者へ失敗メールを送信
     * @return
     */
    public function errorInformation($parm = array())
    {
        Sgmov_Component_Log::debug("errorInformation(" . json_encode($parm) . ")");
        // システム管理者メールアドレスを取得する。
        $mail_to = Sgmov_Component_Config::getLogMailTo();
        //テンプレートメールを送信する。
        Sgmov_Component_Mail::sendTemplateMail($parm, dirname(__FILE__) . '/../../mail_template/dsn_error.txt', $mail_to);
    }

}
