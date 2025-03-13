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
Sgmov_Lib::useForms(array('Error', ));
Sgmov_Lib::useServices(array(
    'Event', 'Eventsub', 'Prefecture', 'CostcoDataDisplay',
    'CostcoLeadTime', 'EventBusinessHoliday'
));
Sgmov_Lib::useImageQRCode();
Sgmov_Lib::use3gMdk(array('3GPSMDK'));
/**#@-*/

/**
 * コストコ配送サービスの申込入力画面表示
 * @package    View
 * @subpackage CSC
 * @author     K.Sawada
 * @copyright  2021-2021 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Csc_Input extends Sgmov_View_Csc_Common
{

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $_EventService;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $_EventsubService;

   /**
     * 都道府県サービス
     * @var Sgmov_Service_Prefecture
     */
    private $_PrefectureService;

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
    private $_CostcoLeadTime;
    
    private $_EventBusinessHoliday;
    
    private $_CostcoCustomerCd;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        $this->_EventService = new Sgmov_Service_Event();
        $this->_EventsubService = new Sgmov_Service_Eventsub();
        $this->_PrefectureService = new Sgmov_Service_Prefecture();
        $this->_CostcoDataDisplayService = new Sgmov_Service_CostcoDataDisplay();
        $this->_CostcoLeadTime = new Sgmov_Service_CostcoLeadTime();
        $this->_EventBusinessHoliday = new Sgmov_Service_EventBusinessHoliday();
        $this->_CostcoCustomerCd = new Sgmov_Service_CostcoCustomerCd();
        parent::__construct();
    }

    /**
     * 処理を実行します。
     */
    public function executeInner()
    {

        Sgmov_Component_Log::debug("======================================================================================");
        @Sgmov_Component_Log::debug($_GET);
        @Sgmov_Component_Log::debug($_POST);
        Sgmov_Component_Log::debug("======================================================================================");

        $_SESSION[dirname(__FILE__) . "_treeData"] = null;

        // セッションに情報があるかどうかを確認
        $session = Sgmov_Component_Session::get();

        if (@empty($_SESSION["CSC"])) {
            $_SESSION["CSC"] = array();
        }

        if(@empty($_SERVER["HTTP_REFERER"]) || strpos($_SERVER["HTTP_REFERER"], "/csc/") == false) {
            // セッション情報を破棄
            $session->deleteForm(self::FEATURE_ID);
            $_SESSION["CSC"] = array();
        }
        Sgmov_Component_Log::debug("======================================================================================");
        $eventsubId = @$_GET['param'];
        // getの店舗コードが4ケタの数字でない場合はエラー
        if (@empty($eventsubId) || self::checkQueryShopCode($eventsubId) === false) {
            Sgmov_Component_Redirect::redirectPublicSsl("/404.html");
            exit;
        }
        Sgmov_Component_Log::debug("======================================================================================");
        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        // イベントサブを取得
        $eventsubInfo = $this->_EventsubService->fetchEventsubIdAndSubid($db, self::EVENT_ID, $eventsubId);
        $eventInfo = array();
        if (@empty($eventsubInfo)) {
            Sgmov_Component_Redirect::redirectPublicSsl("/404.html");
            exit;
        } else {
            $eventInfo = @$this->_EventService->fetchEventInfoByEventId($db, $eventsubInfo['event_id']);
            if (@empty($eventInfo)) {
                Sgmov_Component_Redirect::redirectPublicSsl("/404.html");
                exit;
            } elseif (!isset($_SESSION["CSC"]["ERROR_INFO"])){
                //2023/02/20 GiapLN imp ticket #SMT6-390
                $tmpCd = $this->_CostcoCustomerCd->getInfo($db, $eventsubInfo['event_id'], $eventsubId);
                if (!isset($tmpCd['customer_cd'])) {
                    $title = 'コストコマスタエラー';
                    $message = '適用開始日、終了日により、顧客マスタを取得できていません。';
                    Sgmov_Component_Redirect::redirectPublicSsl("/csc/error?t={$title}&m={$message}");
                    exit;
                }
            }
        }
        
        Sgmov_Component_Log::debug("======================================================================================");

        $inputInfo = @$_SESSION["CSC"]['INPUT_INFO'];
        if (@empty($inputInfo)) {
            $inputInfo = array();
        }
        $shohinInfo = @$_SESSION["CSC"]['SHOHIN_INFO'];
        if (@empty($shohinInfo)) {
            $inputInfo = array();
        }
        $errorInfo = @$_SESSION["CSC"]['ERROR_INFO'];
        if (@empty($errorInfo)) {
            $errorInfo = array();
        }
        Sgmov_Component_Log::debug("======================================================================================");

        $prefInfo = $this->_PrefectureService->fetchPrefectures($db);
        $prefInfo2= array();
        $count = 0;
        foreach($prefInfo['ids'] as $key => $val) {
            $data = array(
                'prefecture_id' => empty($val) ? '0' : $val ,
                'name' => @empty($prefInfo['names'][$count]) ? '選択してください' : $prefInfo['names'][$count],
            );
            $prefInfo2[] = $data;
            $count++;
        }

        // 店舗毎で配送希望日項目を表示するかどうかの判定フラグ取得
        $haitatsuKiboItemInfo = $this->_CostcoDataDisplayService->getInfo($db, $eventsubInfo['event_id'], $eventsubId, 'HAITATSU_KIBO_ITEM');

        // 店舗毎の注意文言取得
        $noticeMessageInfo = $this->_CostcoDataDisplayService->getInfo($db, $eventsubInfo['event_id'], $eventsubId, 'NOTICE_MESSAGE');

        // 店舗毎リードタイム初期値
        $leadtimeInfo = $this->_CostcoLeadTime->getInfo($db, $eventsubInfo['event_id'], $eventsubId, self::COMMON_PREF_ID);
        
        //GiapLN implement task #SMT6-348 2022.11.16
        $businessHolidayData = $this->_EventBusinessHoliday->getInfo($db, $eventsubInfo['event_id'], $eventsubId);
        $businessHoliday = [];
        foreach ($businessHolidayData as $row) {
            $businessHoliday[] = array(
                'holiday_from' => $row['holiday_from'],
                'holiday_to'    => $row['holiday_to']
            );
        }
        $randKeyForXss = md5(uniqid());

        $_SESSION['RAND_KEY_FOR_XSS'] =  $randKeyForXss;
        setcookie('RAND_KEY_FOR_XSS', $randKeyForXss, time()+60*60*2, '/'); // 2時間で設定

        return array(
            'status' => 'success',
            'message' => '初期情報処理に成功しました。',
            'res_data' => array(
                'event' => $eventInfo,
                'eventsub' => $eventsubInfo,
                'input_info' => $inputInfo,
                'shohin_info' => $shohinInfo,
                'pref_info' => $prefInfo2,
                'error_info' => $errorInfo,
                'eventsub_id' => $eventsubId,
                'haitatsu_kibo_item_info' => $haitatsuKiboItemInfo,
                'notice_message_info' => $noticeMessageInfo,
                'leadtime_info' => $leadtimeInfo,
                'business_holiday' => json_encode($businessHoliday),
                // 'rand_key_for_xss' => $randKeyForXss,
            )
        );

    }

}
