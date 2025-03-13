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
Sgmov_Lib::useServices(array('Comiket',
    // 2022-03-25 ToanDD3 implement SMT6-84
    'EventLogin'
));
Sgmov_Lib::useView('mdr/Common');
Sgmov_Lib::useForms(array('Error', 'EveSession', 'Eve001Out', 'Eve002In'));
/**#@-*/

/**
 * コミケサービスのお申し込み入力画面を表示します。
 * @package    View
 * @subpackage MDR
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Mdr_Input extends Sgmov_View_Mdr_Common {

    /**
     * 共通サービス
     * @var Sgmov_Service_AppCommon
     */
    protected $_appCommon;

    /**
     * コミケ申込データサービス
     * @var Sgmov_Service_Comiket
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

    // 2022-03-25 ToanDD3 implementn SMT6-84
    private $_EventLoginService;

    //     /**
    //      * 宅配サービス
    //      * @var type
    //      */
    //protected $_BoxService;

//     /**
//      * カーゴサービス
//      * @var type
//      */
//     protected $_CargoService;

//     /**
//      * 館マスタサービス(ブース番号)
//      * @var type
//      */
//     protected $_BuildingService;


    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_appCommon             = new Sgmov_Service_AppCommon();
        $this->_Comiket = new Sgmov_Service_Comiket();
        $this->_PrefectureService     = new Sgmov_Service_Prefecture();
        $this->_EventService          = new Sgmov_Service_Event();
        $this->_EventsubService       = new Sgmov_Service_Eventsub();
        //$this->_BoxService       = new Sgmov_Service_Box();
        //$this->_CargoService       = new Sgmov_Service_Cargo();
        //$this->_BuildingService       = new Sgmov_Service_Building();
        $this->_CharterService       = new Sgmov_Service_Charter();
        // 2022-03-25 ToanDD3 implementn SMT6-84
        $this->_EventLoginService   = new Sgmov_Service_EventLogin();

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
        
        ///////////////////////////////////////////////////////////////////////////////////
        // 入力画面の場合の初期表示にて、金額取得用セッションデータ破棄
        ///////////////////////////////////////////////////////////////////////////////////
        $_SESSION[dirname(__FILE__) . "_treeData"] = null;
        
        // セッションに情報があるかどうかを確認
        $session = Sgmov_Component_Session::get();

        // 2022-03-25 ToanDD3 implement SMT6-59
        //Check security taio
        if ($this->isSecurityTaio()) {
            // Check user type from session
            if (!isset($_SESSION[self::LOGIN_ID]['user_type'])) {
                Sgmov_Component_Redirect::redirectPublicSsl("/event/userSelect?event_nm=mdr");
            }   
        }
        
        if(@empty($_SERVER["HTTP_REFERER"]) || strpos($_SERVER["HTTP_REFERER"], "/mdr/") == false) {
            // セッション情報を破棄
            $session->deleteForm(self::FEATURE_ID);
        }
        
        // セッション情報
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        // フォーム
        $inForm = new Sgmov_Form_Eve002In();
        // エラー引数
        $errorForm = NULL;
        // パラメータ
        $param = filter_input(INPUT_GET, 'param');

///////////////////////////////////////////////////////////////////////////////////////////////////
//// ▼ チェックデジット判定 Start
///////////////////////////////////////////////////////////////////////////////////////////////////
        if(strlen($param) == 10) {
            Sgmov_Component_Redirect::redirectPublicSsl("/mdr/input2_dialog/{$param}");
        }

        $input2DialogFlg = filter_input(INPUT_POST, 'input2_dialog');

        if($input2DialogFlg == "1") {
            $input2DialogComiketId = filter_input(INPUT_POST, 'id');

            if(!is_numeric($input2DialogComiketId)){
                Sgmov_Component_Redirect::redirectPublicSsl("/mdr/temp_error");
                exit;
            }
            
            $db = Sgmov_Component_DB::getPublic();
            $comiketData = $this->_Comiket->fetchComiketById($db, intval($input2DialogComiketId));

            if(empty($comiketData)) {
                Sgmov_Component_Redirect::redirectPublicSsl("/mdr/temp_error");
            }
            $input2DialogData1 = filter_input(INPUT_POST, 'data1');
            
            $input2DialogData1Tel = mb_convert_kana(str_replace(array('-', 'ー', '−', '―', '‐'), '', $input2DialogData1), 'rnask', 'UTF-8');

            $input2DialogData1Mail = mb_convert_kana($input2DialogData1, 'rnask', 'UTF-8');
            
            if($comiketData['tel'] == $input2DialogData1Tel
                    || $comiketData['staff_tel'] == $input2DialogData1Tel
                    || $comiketData['mail'] == $input2DialogData1Mail) {
                // セッション情報を破棄
                $session->deleteForm(self::FEATURE_ID);
                
                $param = $input2DialogComiketId . self::getChkD($input2DialogComiketId);
                Sgmov_Component_Redirect::redirectPublicSsl("/mdr/input/{$param}");
            } else {
                Sgmov_Component_Redirect::redirectPublicSsl("/mdr/temp_error/{$comiketData['event_id']}");
            }
        }
        
        if(!empty($param)) {
//            // チェックデジットチェック
            if(strlen($param) <= 10){
Sgmov_Component_Log::debug ( '11桁以上ではない' );
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
            }

            if(!is_numeric($param)){
Sgmov_Component_Log::debug ( '数値ではない' );
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
            }
            $id = substr($param, 0, 10);
            $cd = substr($param, 10);
            
Sgmov_Component_Log::debug ( 'id:'.$id );
Sgmov_Component_Log::debug ( 'cd:'.$cd );
            
            $sp = self::getChkD($id);
            
Sgmov_Component_Log::debug ( 'sp:'.$sp );

            if($sp !== intval($cd)){
Sgmov_Component_Log::debug ( 'CD不一致' );
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
            }
            $param = intval(substr($param, 0, 10));
        } 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        if(@!empty($_SERVER["REQUEST_URI"]) && strpos($_SERVER["REQUEST_URI"], "/mdr/input2") !== false && empty($param)) {
            // input2 初期表示時にGETパラメータがない場合

            $checkForm = $sessionForm->in;
            $checkForm = (array)$checkForm;

            if(empty($checkForm['comiket_id'])) {
                // 申込みIDがセッションにない場合
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
            }
        }
        if(@!empty($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], "/mdr/") !== false ){
            // 初期表示時以外(入力エラーなどで戻ってきた場合など)
            if (isset($sessionForm)) {
                $clearFlg = filter_input(INPUT_GET, 'clr');
                $inForm    = $sessionForm->in;
                if (empty($clearFlg)) {
                    $errorForm = @$sessionForm->error;
                } else {
                    $errorForm = NULL;
                }

                // セッション破棄
                $sessionForm->error = NULL;
            }
        } else {
            // 初期表示時
            if(!empty($sessionForm)) {
                $sessionForm->in = NULL;
                $sessionForm->error = NULL;
            } else {
                // 2022-03-25 ToanDD3 implement SMT6-84
                if (isset($_SESSION[self::LOGIN_ID]['user_type']) && $_SESSION[self::LOGIN_ID]['user_type'] === 1) {
                    $db = Sgmov_Component_DB::getPublic();
                    $sesUserEmail = $_SESSION[self::LOGIN_ID]['email'];
                    // Get data from 会員マスタ for display default on the form.
                    $eventMemeberData = $this->_EventLoginService->fetchEventLoginValid($db, $sesUserEmail);

                    $inForm->comiket_personal_name_sei = $eventMemeberData['name_sei'];
                    $inForm->comiket_personal_name_mei = $eventMemeberData['name_mei'];
                    $inForm->comiket_zip1              = substr($eventMemeberData['zip'], 0, 3);
                    $inForm->comiket_zip2              = substr($eventMemeberData['zip'], 3, 4);
                    $inForm->comiket_pref_cd_sel       = $eventMemeberData['pref_id'];
                    $inForm->comiket_address           = $eventMemeberData['address'];
                    $inForm->comiket_building          = $eventMemeberData['building'];
                    $inForm->comiket_tel               = $eventMemeberData['tel'];
                }
            }
        }

        
//        $ev = '810';
//        if(!empty($ev) && is_numeric($ev)) {
//            $inForm->event_sel = intval($ev);
//            $inForm->input_mode = $ev;
//            $inForm->comiket_div = '1'; // 個人
//            $inForm->eventsub_sel = "811";
//        }
        
        //GiapLN Implement get event_id, eventSubId with event_nm  2022/03/25
        $db = Sgmov_Component_DB::getPublic();
        $eventsubInfo =  $this->_EventsubService->getEventIdByShikibetsushi($db, strtolower(self::FEATURE_ID));
        if (!empty($eventsubInfo)) {
            $ev = $eventsubInfo['event_id'];
            if(!empty($ev) && is_numeric($ev)) {
                $inForm->event_sel = intval($ev);
                $inForm->input_mode = $ev;
                $inForm->comiket_div = '1'; // 個人
                $inForm->eventsub_sel = $eventsubInfo['id'];
            }
        } else {
            Sgmov_Component_Log::err("Error in '".__FILE__."' at line ".__LINE__.": no eventsub_selected_data in dispItemInfo !");
        }
        
        // フォーム情報生成する。
        $resultData = $this->_createOutFormByInForm($inForm, $param);
        // フォーム情報
        $outForm = $resultData["outForm"];
        // 表示項目
        $dispItemInfo = $resultData["dispItemInfo"];
        
        // DB接続
        
        ////////////////////////////////////////////////////////////////////////////////////////////////////
        // 入力可能期間チェック
        ////////////////////////////////////////////////////////////////////////////////////////////////////
        //$eventsubInfo = $this->_EventsubService->fetchEventsubByEventsubId($db, $inForm->eventsub_sel);
        if (isset($eventsubInfo)) {
            if (isset($eventsubInfo['arrival_to_time'])) {
                $now = new DateTime();
                $arrivalToTime = new DateTime($eventsubInfo['arrival_to_time']);
                if ($now>$arrivalToTime) {
                    
                    $event = get_object_vars($outForm);
                    $eventInfo = $this->_EventService->fetchEventInfoByEventId($db, $event['raw_event_cd_sel']);
                    
                    $title = urlencode("公開終了しています");
                    
                    $eventName = $eventInfo['name'];
                    $arrivalToTime = $eventsubInfo['arrival_to_time'];
                    $arrivalToTime = str_replace('-', '/', $arrivalToTime);
                    
                    $message = urlencode("{$eventName}のお申込は {$arrivalToTime} をもって終了しました。");
                    Sgmov_Component_Redirect::redirectPublicSsl("/mdr/error?t={$title}&m={$message}");
                    exit;
                }
            } else {
                Sgmov_Component_Log::err("Error in '".__FILE__."' at line ".__LINE__.": no arrival_to_time in the eventsub_selected_data of dispItemInfo !");
            }
        } 
//        else {
//            Sgmov_Component_Log::err("Error in '".__FILE__."' at line ".__LINE__.": no eventsub_selected_data in dispItemInfo !");
//        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////
        if (isset($eventsubInfo)) {
            if (isset($eventsubInfo['departure_fr_time'])) {
                $now = new DateTime();
                $departureFrTo = new DateTime($eventsubInfo['departure_fr_time']);
                
                if ($now<$departureFrTo) {
                    $db = Sgmov_Component_DB::getPublic();
                    $event = get_object_vars($outForm);
                    $eventInfo = $this->_EventService->fetchEventInfoByEventId($db, $event['raw_event_cd_sel']);
                    $title = urlencode("公開開始前です");
                    $eventName = $eventInfo['name'];
                    $departureFrTime = $eventsubInfo['departure_fr_time'];
                    $departureFrTime = str_replace('-', '/', $departureFrTime);
                    $message = urlencode("{$eventName}のお申込は {$departureFrTime} に開始します。");
                    Sgmov_Component_Redirect::redirectPublicSsl("/mdr/error?t={$title}&m={$message}");
                    exit;
                }
            } else {
                Sgmov_Component_Log::err("Error in '".__FILE__."' at line ".__LINE__.": no departure_fr_time in the eventsub_selected_data of dispItemInfo !");
            }
        } 
//        else {
//            Sgmov_Component_Log::err("Error in '".__FILE__."' at line ".__LINE__.": no eventsub_selected_data in dispItemInfo !");
//        }
        
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
                    $message = urlencode("{$eventName}の搬出のお申込は {$arrivalFr} に開始します。");
                    Sgmov_Component_Redirect::redirectPublicSsl("/mdr/error?t={$title}&m={$message}");
                    exit;
            }
        }
        
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // チケット発行
        $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_MDR001);
        //GiapLN implement SMT6-125 2022/04/12
        $hasCommiket = false;
        if (isset($_SESSION[self::LOGIN_ID]['user_type']) && $_SESSION[self::LOGIN_ID]['user_type'] == 1) {
            $email = $_SESSION[self::LOGIN_ID]['email'];
            $comiketHistory = $this->_Comiket->fetchComiketUserHistory($db, $email, $inForm->event_sel, $inForm->eventsub_sel);
            
            if (!empty($comiketHistory)) {
                $hasCommiket = true; 
            }
        }
        return array(
            'ticket'    => $ticket,
            'outForm'   => $outForm,
            'dispItemInfo' => $dispItemInfo,
            'errorForm' => $errorForm, 
            'hasCommiket'  => $hasCommiket
        );
    }

    /**
     * 入力フォームの値を出力フォームを生成します。
     * @param Sgmov_Form_Eve001In $inForm 入力フォーム
     * @return Sgmov_Form_Eve001Out 出力フォーム
     */
    protected function _createOutFormByInForm($inForm, $param=NULL) {
        $inForm = (array)$inForm;
        return $this->createOutFormByInForm($inForm, new Sgmov_Form_Eve001Out());
    }
}
