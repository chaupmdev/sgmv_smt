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
Sgmov_Lib::useServices(array('Comiket', 'EventDate'));
Sgmov_Lib::useView('azk/Common');
Sgmov_Lib::useForms(array('Error', 'AzkSession', 'Azk001Out', 'Azk002In'));
/**#@-*/

/**
 * コミケサービスのお申し込み入力画面を表示します。
 * @package    View
 * @subpackage MSB
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Azk_Input extends Sgmov_View_Azk_Common {

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
     * 館マスタサービス(ブース番号)
     * @var type
     */
    protected $_BuildingService;

    /**
     * イベント日付
     * @var type
     */
    protected $_EventDateService;


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
        $this->_BuildingService       = new Sgmov_Service_Building();
        $this->_EventDateService       = new Sgmov_Service_EventDate();

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
        
        if(@empty($_SERVER["HTTP_REFERER"]) || strpos($_SERVER["HTTP_REFERER"], "/azk/") == false) {
            // セッション情報を破棄
            $session->deleteForm(self::FEATURE_ID);
        }
        
        // セッション情報
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        // フォーム
        $inForm = new Sgmov_Form_Azk002In();
        // エラー引数
        $errorForm = NULL;
        // パラメータ
        $param = filter_input(INPUT_GET, 'param');

        $db = Sgmov_Component_DB::getPublic();

        // if (strpos($_SERVER["REQUEST_URI"], "/azk/input2") == false && empty($param)){
        //     print_r("sdf");exit;
        //     $title = "対象のデータが見つかりません。";
        //     $message = urlencode("対象のデータが見つかりません。");
        //     Sgmov_Component_Redirect::redirectPublicSsl("/azk/error?t={$title}&m={$message}");
        // }

        $shikibetsushi = "dsn";
        // if (strlen($param) > 3) {
        //     $comiketId = $param;

        //     // コミケID空チェック
        //     if (empty($comiketId)) {
        //         Sgmov_Component_Redirect::redirectPublicSsl("/azk/temp_error");
        //         exit;
        //     }


        //     // ▼　param チェック
        //     // if(!empty($comiketId)) {
        //     //     // チェックデジットチェック
        //     //     if(strlen($comiketId) <= 10){
        //     //         Sgmov_Component_Log::debug ( '11桁以上ではない' );
        //     //         Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
        //     //     }

        //     //     if(!is_numeric($comiketId)){
        //     //         Sgmov_Component_Log::debug ( '数値ではない' );
        //     //         Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
        //     //     }
        //     //     $id = substr($comiketId, 0, 10);
        //     //     $cd = substr($comiketId, 10);
                
        //     //     Sgmov_Component_Log::debug ( 'id:'.$id );
        //     //     Sgmov_Component_Log::debug ( 'cd:'.$cd );
                
        //     //     $sp = self::getChkD($id);
                
        //     //     Sgmov_Component_Log::debug ( 'sp:'.$sp );

        //     //     if($sp !== intval($cd)){
        //     //         Sgmov_Component_Log::debug ( 'CD不一致' );
        //     //         Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
        //     //     }
        //     //     $comiketId = intval(substr($comiketId, 0, 10));

        //     //     $comiketData = $this->_Comiket->fetchComiketById($db, intval($comiketId));

        //     //     $shikibetsushi = $comiketData['event_nm'];
        //     // } 
        //     // ▲　param チェック


        //     ///////////////////////////////////////////////////////////////////////////////////////////////////
        //     //// ▼ チェックデジット判定 Start
        //     ///////////////////////////////////////////////////////////////////////////////////////////////////
        //     // if(strlen($comiketId) == 10) {
        //     //     Sgmov_Component_Redirect::redirectPublicSsl("/azk/input2_dialog/{$comiketId}");
        //     // }


        //     // ▼　input2_dialog
        //     // $input2DialogFlg = filter_input(INPUT_POST, 'input2_dialog');
        //     // if($input2DialogFlg == "1") {
        //     //     $input2DialogComiketId = filter_input(INPUT_POST, 'id');
        //     //     if(!is_numeric($input2DialogComiketId)){
        //     //         Sgmov_Component_Redirect::redirectPublicSsl("/azk/temp_error");
        //     //         exit;
        //     //     }

        //     //     $comiketData = $this->_Comiket->fetchComiketById($db, intval($input2DialogComiketId));

        //     //     $shikibetsushi = $comiketData['event_nm'];

        //     //     if(empty($comiketData)) {
        //     //         Sgmov_Component_Redirect::redirectPublicSsl("/azk/temp_error");
        //     //     }

        //     //     $input2DialogData1 = filter_input(INPUT_POST, 'data1');

        //     //     $input2DialogData1Tel = mb_convert_kana(str_replace(array('-', 'ー', '−', '―', '‐'), '', $input2DialogData1), 'rnask', 'UTF-8');

        //     //     if($comiketData['tel'] == $input2DialogData1Tel || $comiketData['staff_tel'] == $input2DialogData1Tel) {
        //     //             // セッション情報を破棄
        //     //             $session->deleteForm(self::FEATURE_ID);
                        
        //     //             $param = $input2DialogComiketId . self::getChkD($input2DialogComiketId);
        //     //             Sgmov_Component_Redirect::redirectPublicSsl("/azk/input/{$comiketData['event_nm']}/{$param}");
        //     //     } else {
        //     //         Sgmov_Component_Redirect::redirectPublicSsl("/azk/temp_error/{$comiketData['event_id']}");
        //     //     }
        //     // }
        //     // ▲input2_dialog
        // }


        if(@!empty($_SERVER["REQUEST_URI"]) && strpos($_SERVER["REQUEST_URI"], "/azk/input2") !== false && empty($param)) {
            // input2 初期表示時にGETパラメータがない場合
            $checkForm = $sessionForm->in;
            $checkForm = (array)$checkForm;

            if(empty($checkForm['comiket_id'])) {
                // 申込みIDがセッションにない場合
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
            }
        }


        if(@!empty($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], "/azk/") !== false ){
            // 初期表示時以外(入力エラーなどで戻ってきた場合など)
            if (isset($sessionForm)) {

                $inForm    = $sessionForm->in;
                $errorForm = @$sessionForm->error;
                if (strpos($_SERVER["REQUEST_URI"], "clr") !== false ) {
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
            }
        }


        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if (!empty($param)) {
            // only input
            $splitParam = explode("/", $param);
            // イベント識別子
            if(isset($splitParam[0])){
                $shikibetsushi = $splitParam[0];
            }
        }

        // 識別子 is_stringチェック
        if (!is_string($shikibetsushi)) {           
            Sgmov_Component_Log::debug ( '文字値ではない' );
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
        }
        
        $eventInfo = $this->_EventService->fetchEventByShikibetsushi($db, $shikibetsushi);
        if (empty($eventInfo)) {
            $title = "対象のデータが見つかりません。";
            $message = urlencode("対象のデータが見つかりません。");
            Sgmov_Component_Redirect::redirectPublicSsl("/azk/error?t={$title}&m={$message}");
        } else {
            // イベント
            $inForm->event_sel = $eventInfo["eventid"];

            $inForm->comiket_div = '1'; // 個人

            // inputmode
            $inForm->input_mode = $eventInfo["eventsubid"];

            // イベントサブ
            $inForm->eventsub_sel = $eventInfo["eventsubid"];

            // 識別子
            $inForm->shikibetsushi = $shikibetsushi;
        }

        // フォーム情報生成する。
        $resultData = $this->_createOutFormByInForm($inForm);
        // フォーム情報
        $outForm = $resultData["outForm"];
        // 表示項目
        $dispItemInfo = $resultData["dispItemInfo"];

        // イベント期間
        $eventTerm = $this->_EventDateService->fetchEventTerm($db, $inForm->eventsub_sel);


        ////////////////////////////////////////////////////////////////////////////////////////////////////
        // 入力可能期間チェック
        ////////////////////////////////////////////////////////////////////////////////////////////////////
        $eventsubInfo = $this->_EventsubService->fetchEventsubByEventsubId($db, $inForm->eventsub_sel);
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
                    
                    $message = urlencode("{$eventName}のお申込は {$arrivalToTime} をもって終了しました。");
                    Sgmov_Component_Redirect::redirectPublicSsl("/msb/error?t={$title}&m={$message}");
                    exit;
                }
            } else {
                Sgmov_Component_Log::err("Error in '".__FILE__."' at line ".__LINE__.": no arrival_to_time in the eventsub_selected_data of dispItemInfo !");
            }
        } else {
            Sgmov_Component_Log::err("Error in '".__FILE__."' at line ".__LINE__.": no eventsub_selected_data in dispItemInfo !");
        }

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
                    Sgmov_Component_Redirect::redirectPublicSsl("/msb/error?t={$title}&m={$message}");
                    exit;
                }
            } else {
                Sgmov_Component_Log::err("Error in '".__FILE__."' at line ".__LINE__.": no departure_fr_time in the eventsub_selected_data of dispItemInfo !");
            }
        } else {
            Sgmov_Component_Log::err("Error in '".__FILE__."' at line ".__LINE__.": no eventsub_selected_data in dispItemInfo !");
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
                    $message = urlencode("{$eventName}の搬出のお申込は {$arrivalFr} に開始します。");
                    Sgmov_Component_Redirect::redirectPublicSsl("/msb/error?t={$title}&m={$message}");
                    exit;
            }
        }
        
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // チケット発行
        $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_AZK001);

        return array(
            'ticket'    => $ticket,
            'outForm'   => $outForm,
            'dispItemInfo' => $dispItemInfo,
            'errorForm' => $errorForm
        );
    }

    /**
     * 入力フォームの値を出力フォームを生成します。
     * @param Sgmov_Form_Eve001In $inForm 入力フォーム
     * @return Sgmov_Form_Eve001Out 出力フォーム
     */
    protected function _createOutFormByInForm($inForm) {
        $inForm = (array)$inForm;
        return $this->createOutFormByInForm($inForm, new Sgmov_Form_Azk001Out());
    }
}
