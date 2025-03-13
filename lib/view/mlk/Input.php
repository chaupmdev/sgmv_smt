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
Sgmov_Lib::useServices(array('Comiket',));
Sgmov_Lib::useView('mlk/Common');
Sgmov_Lib::useForms(array('Error', 'EveSession', 'Eve001Out', 'Eve002In'));
/**#@-*/
//URL_LENG_INVALID_KEY
define('URL_LENG_INVALID_KEY', 'URL_LENG_INVALID');
define('URL_FORMAT_ERR_KEY', 'URL_FORMAT_ERR');
define('URL_NOT_EXISTS_CODE_KEY', 'URL_NOT_EXISTS_CODE');
define('URL_INVALID_INDEX_CODE_KEY', 'URL_INVALID_INDEX_CODE');
define('URL_EXISTS_COMIKET_KEY', 'URL_EXISTS_COMIKET');
/**
 * コミケサービスのお申し込み入力画面を表示します。
 * @package    View
 * @subpackage EVE
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Eve_Input extends Sgmov_View_Eve_Common {
    public $ERR_MSG = array(
            URL_LENG_INVALID_KEY => ['title' => '手荷物当日配送サービスURL無効です', 'message' => '手荷物当日配送サービスの申込のURLは無効です。'],
            URL_FORMAT_ERR_KEY => ['title' => '手荷物当日配送サービスURL無効です', 'message' => '手荷物当日配送サービスの申込のURLは無効です。'],
            URL_NOT_EXISTS_CODE_KEY => ['title' => '手荷物当日配送サービスURL無効です', 'message' => '手荷物当日配送サービスのお申込みの申込番号は無効です。'],
            URL_INVALID_INDEX_CODE_KEY => ['title' => '手荷物当日配送サービスURL無効です', 'message' => '手荷物当日配送サービスのお申込みの申込番号は無効です。'],
            URL_EXISTS_COMIKET_KEY => ['title' => '手荷物当日配送サービスURL無効です', 'message' => '手荷物当日配送サービスのお申込みの申込番号は使用中です。'],
    );

    /**
     * 共通サービス
     * @var Sgmov_Service_AppCommon
     */
    protected $_appCommon;

    /**
     * コミケ申込データサービス
     * @var type
     */
    protected $_Comiket;

    /**
     * 都道府県サービス
     * @var Sgmov_Service_Prefecture
     */
    protected $_PrefectureService;

    /**
     * イベントサービス
     * @var Sgmov_Service_Event
     */
    protected $_EventService;

    /**
     * イベントサブサービス
     * @var Sgmov_Service_Eventsub
     */
    protected $_EventsubService;

    /**
     * 宅配サービス
     * @var type
     */
    protected $_BoxService;

    /**
     * カーゴサービス
     * @var type
     */
    protected $_CargoService;

    /**
     * 館マスタサービス(ブース番号)
     * @var type
     */
    protected $_BuildingService;


    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_appCommon             = new Sgmov_Service_AppCommon();
        $this->_Comiket = new Sgmov_Service_Comiket();
        $this->_PrefectureService     = new Sgmov_Service_Prefecture();
        $this->_EventService          = new Sgmov_Service_Event();
        $this->_EventsubService       = new Sgmov_Service_Eventsub();
        $this->_BoxService       = new Sgmov_Service_Box();
        $this->_CargoService       = new Sgmov_Service_Cargo();
        $this->_BuildingService       = new Sgmov_Service_Building();
        $this->_CharterService       = new Sgmov_Service_Charter();

        parent::__construct();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * セッションに情報があるかどうかを確認
     * </li><li>
     * 情報有り
     *   <ol><li>
     *   セッション情報を元に出力情報を作成
     *   </li></ol>
     * </li><li>
     * 情報無し
     *   <ol><li>
     *   出力情報を設定
     *   </li></ol>
     * </li><li>
     * テンプレート用の値をセット
     * </li><li>
     * チケット発行
     * </li></ol>
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['ticket']:チケット文字列
     * </li><li>
     * ['outForm']:出力フォーム
     * </li><li>
     * ['errorForm']:エラーフォーム
     * </li></ul>
     */
    public function executeInner() {
        
        $db = Sgmov_Component_DB::getPublic();
        // セッションに情報があるかどうかを確認
        $session = Sgmov_Component_Session::get();
        if(@empty($_SERVER["HTTP_REFERER"]) || strpos($_SERVER["HTTP_REFERER"], "/". $this->_DirDiv ."/") == false) {
            // セッション情報を破棄
            $session->deleteForm(self::FEATURE_ID);
        }
        // セッション情報
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        // フォーム
        $inForm = new Sgmov_Form_Eve002In();
        // エラー引数
        $errorForm = NULL;

        
        if(@!empty($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], "/". $this->_DirDiv ."/") !== false ){
Sgmov_Component_Log::debug('################## 112233-1');
            // 初期表示時以外(入力エラーなどで戻ってきた場合など)
            if (isset($sessionForm->in)) {
                $clearFlg = filter_input(INPUT_GET, 'clr');
                $inForm    = @$sessionForm->in;
                if (empty($clearFlg)) {
                    $errorForm = @$sessionForm->error;
                } else {
                    $errorForm = NULL;
                }

                // セッション破棄
                @$sessionForm->error = NULL;
            }
        } else {
            // 初期表示時
            if(!empty($sessionForm)) {
                $sessionForm = new Sgmov_Form_EveSession();
            }
        }

        if (@empty($sessionForm)) {
            $sessionForm = new Sgmov_Form_EveSession();
        }
        @$sessionForm->cancel = array();
        $session->saveForm(self::FEATURE_ID, $sessionForm);
        

        $eventsubInfo =  $this->_EventsubService->getEventIdByShikibetsushi($db, strtolower(self::FEATURE_ID));
        
        if (!empty($eventsubInfo)) {
            $ev = $eventsubInfo['event_id'];
            if(!empty($ev) && is_numeric($ev)) {
                $inForm->event_sel = intval($ev);
                $inForm->input_mode = $ev;
                $inForm->eventsub_sel = $eventsubInfo['id'];
            }
        } else {
            Sgmov_Component_Log::err("Error in '".__FILE__."' at line ".__LINE__.": no eventsub_selected_data in dispItemInfo !");
        }

        $param = filter_input(INPUT_GET, 'tagId');//filter_input(INPUT_GET, 'param');
        $isBack = filter_input(INPUT_GET, 'back');
        
        $hachakutenInfo = [];
        if (isset($param) && !empty($param)) {
            $param = strtoupper($param);
        }
        $actual_link = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        Sgmov_Component_Log::info("####################################URL##############################################");
        Sgmov_Component_Log::info($actual_link);
        
        $this->checkValidateUrl($hachakutenInfo, $db, $param);
        if (!isset($inForm->delivery_date_store)) {
            $inForm->delivery_date_store = $hachakutenInfo['delivery_date_store'];
        }
        
        $isPassDate = 0;
        $isChange = 0;
        $deliveryDateStore = '';
        if ($isBack && $isBack == 1) {
            if ($inForm->delivery_date_store !== $hachakutenInfo['delivery_date_store']) {
//                if ($currentTime >= $hachakutenInfo['input_end_time']) {
                    $isChange = filter_input(INPUT_GET, 'change');
                    if ($isChange == 1) {
                        $inForm->delivery_date_store = $hachakutenInfo['delivery_date_store'];
                        if ($inForm->addressee_type_sel == "1") {
                            $inForm->comiket_detail_delivery_date = "";
                        }
                    } else {
                        $isChange = 0;
                    }
                    $isPassDate = 1;
//                }
            }
        }
         

        ///////////////////////////////////////////////////////////////////////////////////////////////////
        $resultData = $this->_createOutFormByInForm($inForm, $param);
        

        $outForm = $resultData["outForm"];


        $dispItemInfo = $resultData["dispItemInfo"];
        
        ////////////////////////////////////////////////////////////////////////////////////////////////////
        // 入力可能期間チェック
        ////////////////////////////////////////////////////////////////////////////////////////////////////
        if (isset($eventsubInfo)) {
            if (isset($eventsubInfo['arrival_to_time'])) {
                $now = new DateTime();
                $arrivalToTime = new DateTime($eventsubInfo['arrival_to_time']);
                if ($now > $arrivalToTime) {

                    $event = get_object_vars($outForm);
                    $eventInfo = $this->_EventService->fetchEventInfoByEventId($db, $event['raw_event_cd_sel']);

                    $title = urlencode("公開終了しています");

                    $eventName = $eventInfo['name'];
                    $arrivalToTime = $eventsubInfo['arrival_to_time'];
                    $arrivalToTime = str_replace('-', '/', $arrivalToTime);

                    $message = urlencode("{$eventName}{$eventsubInfo['name']}のお申込は {$arrivalToTime} をもって終了しました。");
                    Sgmov_Component_Redirect::redirectPublicSsl("/". $this->_DirDiv ."/error?t={$title}&m={$message}");
                    exit;
                }
            } else {
                Sgmov_Component_Log::err("Error in '".__FILE__."' at line ".__LINE__.": no arrival_to_time in the eventsub_selected_data of dispItemInfo !");
            }
        } 

        ////////////////////////////////////////////////////////////////////////////////////////////////////
        if (isset($eventsubInfo)) {
            if (isset($eventsubInfo['departure_fr_time'])) {
                $now = new DateTime();
                $departureFrTo = new DateTime($eventsubInfo['departure_fr_time']);
                if ($now < $departureFrTo) {
                    $db = Sgmov_Component_DB::getPublic();
                    $event = get_object_vars($outForm);
                    $eventInfo = $this->_EventService->fetchEventInfoByEventId($db, $event['raw_event_cd_sel']);
                    $title = urlencode("公開開始前です");
                    $eventName = $eventInfo['name'];
                    $departureFrTime = $eventsubInfo['departure_fr_time'];
                    $departureFrTime = str_replace('-', '/', $departureFrTime);
                    $message = urlencode("{$eventName}{$eventsubInfo['name']}のお申込は {$departureFrTime} に開始します。");
                    Sgmov_Component_Redirect::redirectPublicSsl("/". $this->_DirDiv ."/error?t={$title}&m={$message}");
                    exit;
                }
            } else {
                Sgmov_Component_Log::err("Error in '".__FILE__."' at line ".__LINE__.": no departure_fr_time in the eventsub_selected_data of dispItemInfo !");
            }
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////
        if (isset($eventsubInfo)) {
            $now2 = date('Y-m-d');
            if ($eventsubInfo['departure_to'] < $now2
                    && $now2 < $eventsubInfo['arrival_fr']
                    ) {
                    $db = Sgmov_Component_DB::getPublic();
                    $event = get_object_vars($outForm);
                    $eventInfo = $this->_EventService->fetchEventInfoByEventId($db, $event['raw_event_cd_sel']);
                    $title = urlencode("お申込み期間範囲外です");
                    $eventName = $eventInfo['name'];
                    $arrivalFr = $eventsubInfo['arrival_fr'];
                    $arrivalFr = str_replace('-', '/', $arrivalFr);
                    $message = urlencode("{$eventName}{$eventsubInfo['name']}の搬出のお申込は {$arrivalFr} に開始します。");
                    Sgmov_Component_Redirect::redirectPublicSsl("/". $this->_DirDiv ."/error?t={$title}&m={$message}");
                    exit;
            }
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////
        //// ▼ ホテル情報取得
        ///////////////////////////////////////////////////////////////////////////////////////////////////

        $boxInfoList = $this->_BoxService->fetchBoxByEventsubId($db, $inForm->eventsub_sel);
        $dispItemInfo['boxId'] =  @$boxInfoList[0]['id'];

        ///////////////////////////////////////////////////////////////////////////////////////////////////
        
        $dispItemInfo['isConfRule'] = $inForm->is_conf_rule;
        $codePath = substr($param, 0, 8);
        
        $dataHachakutenAll = $this->_HachakutenService->fetchAllHachakuten($db, $codePath);

        // チケット発行
        $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_EVE001);
        
        return array(
            'ticket'    => @$ticket,
            'outForm'   => $outForm,
            'dispItemInfo' => $dispItemInfo,
            'hachakutenInfo' => $hachakutenInfo,
            'dataHachakutenAll' =>json_encode($dataHachakutenAll),
            'errorForm' => $errorForm,
            'isPassDate' => $isPassDate,
            'isChange' => $isChange,
            'deliveryDateStore' => $deliveryDateStore
        );
    }
    public function checkValidateUrl(&$dataHachakuten, $db, $params) {

        if(!is_string($params) || strlen($params) != 15) {
            $this->redirectErrorPage($this->ERR_MSG[URL_LENG_INVALID_KEY]['title'], $this->ERR_MSG[URL_LENG_INVALID_KEY]['message']);
            //$this->redirectErrorPage($this->ERR_MSG[URL_FORMAT_ERR_KEY]['title'], $this->ERR_MSG[URL_FORMAT_ERR_KEY]['message']);

        } else {
            $pattern = '/\S{8}-[0-9]{6}$/';//XX-HND01-000011
            if (!preg_match($pattern, $params)) {
                $this->redirectErrorPage($this->ERR_MSG[URL_FORMAT_ERR_KEY]['title'], $this->ERR_MSG[URL_FORMAT_ERR_KEY]['message']);
            }
            
            $code = substr($params, 0, 8);
            $dataHachakuten = $this->_HachakutenService->fetchValidHachakutenByCode($db, $code);
            if (empty($dataHachakuten)) {
                
                $this->redirectErrorPage($this->ERR_MSG[URL_NOT_EXISTS_CODE_KEY]['title'], $this->ERR_MSG[URL_NOT_EXISTS_CODE_KEY]['message']);
            }
            $indexIncreMerge = substr($params, 9, 6);
            $index = substr($indexIncreMerge, 0, 5);
            $sp = intval(substr($indexIncreMerge, 5, 1));
            $cd = self::getChkD2($index);
            if ($cd !== $sp) {
                $this->redirectErrorPage($this->ERR_MSG[URL_INVALID_INDEX_CODE_KEY]['title'], $this->ERR_MSG[URL_INVALID_INDEX_CODE_KEY]['message']);
            }

            $dataComiket = $this->_Comiket->fetchComiketByDetailCD($db, $params, self::DUPLICATE_DURATION_MONTHS);

            if (!empty($dataComiket)) {
                $this->redirectErrorPage($this->ERR_MSG[URL_EXISTS_COMIKET_KEY]['title'], $this->ERR_MSG[URL_EXISTS_COMIKET_KEY]['message']);
            }

            $dataHachakuten['delivery_date_store'] = '';
            $currentTime = date('His');
            if (!empty($dataHachakuten['input_end_time'])) {
                if ($currentTime >= $dataHachakuten['input_end_time']."00") {
                    $dataHachakuten['delivery_date_store'] = date('Y/m/d', strtotime("+1 day", strtotime(date('Y-m-d'))));
                } else {
                    $dataHachakuten['delivery_date_store'] = date('Y/m/d');
                }
            }
        }
    }

    /**
     * 入力フォームの値を出力フォームを生成します。
     * @param Sgmov_Form_Pve001In $inForm 入力フォーム
     * @return Sgmov_Form_Pve001Out 出力フォーム
     */
    protected function _createOutFormByInForm($inForm, $param=NULL) {
        $inForm = (array)$inForm;
        $inForm['comiket_id'] = $param;
        return $this->createOutFormByInForm($inForm, new Sgmov_Form_Eve001Out());
    }
}