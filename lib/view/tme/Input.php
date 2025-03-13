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
Sgmov_Lib::useServices(array('Comiket','EventLogin'));
// イベント識別子(URLのpath)はマジックナンバーで記述せずこの変数で記述してください
$dirDiv=Sgmov_Lib::setDirDiv(dirname(__FILE__));
Sgmov_Lib::useView($dirDiv.'/Common');
Sgmov_Lib::useForms(array('Error', 'EveSession', 'Eve001Out', 'Eve002In'));
/**#@-*/

/**
 * コミケサービスのお申し込み入力画面を表示します。
 * @package    View
 * @subpackage RMS
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Rms_Input extends Sgmov_View_Rms_Common {

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

    // 識別子
    protected $_DirDiv;

    // GiapLN implement SMT6-85
    private $_EventLoginService;
    
    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_appCommon               = new Sgmov_Service_AppCommon();
        $this->_Comiket                 = new Sgmov_Service_Comiket();
        $this->_PrefectureService       = new Sgmov_Service_Prefecture();
        $this->_EventService            = new Sgmov_Service_Event();
        $this->_EventsubService         = new Sgmov_Service_Eventsub();
        $this->_BoxService              = new Sgmov_Service_Box();
        $this->_CargoService            = new Sgmov_Service_Cargo();
        $this->_BuildingService         = new Sgmov_Service_Building();
        $this->_CharterService          = new Sgmov_Service_Charter();

        // 識別子のセット
        $this->_DirDiv = Sgmov_Lib::setDirDiv(dirname(__FILE__));

        // GiapLN implement SMT6-85
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
        
        // GiapLN implement SMT6-85
        // Check user type from session
        if (!isset($_SESSION[self::LOGIN_ID]['user_type'])) {
            // Redirect to 404 page if not loged in
            Sgmov_Component_Redirect::redirectPublicSsl('/event/userSelect?event_nm=tme');
        }
        
        if(@empty($_SERVER["HTTP_REFERER"]) || strpos($_SERVER["HTTP_REFERER"], "/".$this->_DirDiv."/") == false) {
            // セッション情報を破棄
            $session->deleteForm(self::FEATURE_ID);
        }
        
        // 情報
        // 初回表示時はNULL
        // self::FEATURE_ID＝TME
        // sessionForm初回表示時はNULL
        $sessionForm = $session->loadForm(self::FEATURE_ID);

        // 入力用のフォーム情報をつくって
        $inForm = new Sgmov_Form_Eve002In();

        $errorForm = NULL;
        // キャンセル画面とかに行く用
        $param = filter_input(INPUT_GET, 'param');


        // 通常申込では通らない
        if(@!empty($_SERVER["REQUEST_URI"]) && strpos($_SERVER["REQUEST_URI"], "/".$this->_DirDiv."/input2") !== false && empty($param)) {
            // input2 初期表示時にGETパラメータがない場合

            $checkForm = $sessionForm->in;
            $checkForm = (array)$checkForm;

            if(empty($checkForm['comiket_id'])) {
                // 申込みIDがセッションにない場合
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
            }
        }

        if(@!empty($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], "/".$this->_DirDiv."/") !== false ){
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
                // GiapLN implement SMT6-85
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


        // イベントIDとイベントサブIDをセット
//        $ev = self::EVENT_ID;
//        if(!empty($ev) && is_numeric($ev)) {
//            $inForm->event_sel = intval($ev);
//            $inForm->input_mode = $ev;
//            $inForm->comiket_div = "1";
//            $inForm->eventsub_sel = self::EVENT_SUB_ID;
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

        // 出力用のフォーム情報生成する。
        $resultData = $this->_createOutFormByInForm($inForm, $param);
       
        // 出力用のフォーム情報
        $outForm = $resultData["outForm"];
        
        // 表示用のフォーム情報
        $dispItemInfo = $resultData["dispItemInfo"];

        ////////////////////////////////////////////////////////////////////////////////////////////////////
        // 入力可能期間チェック
        ////////////////////////////////////////////////////////////////////////////////////////////////////
        
        //$eventsubInfo = $this->_EventsubService->fetchEventsubByEventsubId($db, $inForm->eventsub_sel);
        
        
        
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // チケット発行
        $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_RMS001);
        
        //GiapLN implement SMT6-125 2022/04/12
        $hasCommiket = false;
        if ($_SESSION[self::LOGIN_ID]['user_type'] == 1) {
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
     * 入力フォームの値を出力フォームに生成します。
     * @param Sgmov_Form_Eve001In $inForm 入力フォーム
     * @return Sgmov_Form_Eve001Out 出力フォーム
     */
    protected function _createOutFormByInForm($inForm, $param=NULL) {
        $inForm = (array)$inForm;
        return $this->createOutFormByInForm($inForm, new Sgmov_Form_Eve001Out());
    }
}