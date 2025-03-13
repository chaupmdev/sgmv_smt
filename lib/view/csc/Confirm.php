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
    'Event', 'Eventsub', 'Prefecture', 'CostcoDataDisplay'
));
Sgmov_Lib::useImageQRCode();
Sgmov_Lib::use3gMdk(array('3GPSMDK'));
/**#@-*/

/**
 * コストコ配送サービスの申込確認画面表示
 * @package    View
 * @subpackage CSC
 * @author     K.Sawada
 * @copyright  2021-2021 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Csc_Confirm extends Sgmov_View_Csc_Common
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
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        $this->_EventService = new Sgmov_Service_Event();
        $this->_EventsubService = new Sgmov_Service_Eventsub();
        $this->_PrefectureService     = new Sgmov_Service_Prefecture();
        $this->_CostcoDataDisplayService = new Sgmov_Service_CostcoDataDisplay();
        parent::__construct();
    }

    /**
     * コストコ配送サービスの申込確認画面表示処理
     */
    public function executeInner()
    {

        Sgmov_Component_Log::debug("======================================================================================");
        @Sgmov_Component_Log::debug($_GET);
        @Sgmov_Component_Log::debug($_POST);
        Sgmov_Component_Log::debug("======================================================================================");

        $_SESSION[dirname(__FILE__) . "_treeData"] = null;
        @$_SESSION["CSC"]['ERROR_INFO'] = array();

        // セッションから入力画面の入力情報を取得
        $inputInfo = @$_SESSION["CSC"]['INPUT_INFO'];
        if (@empty($inputInfo)) {
            $inputInfo = array();
        }
        
        Sgmov_Component_Log::debug("======================================================================================");
        // イベントサブ情報取得
        $eventsubId = @$inputInfo['c_eventsub_id'];
        if (@empty($eventsubId)) {
            Sgmov_Component_Redirect::redirectPublicSsl("/404.html");
            exit;
        }
        Sgmov_Component_Log::debug("======================================================================================");
        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        // イベントサブ情報取得(eventsub検索)
        $eventsubInfo = $this->_EventsubService->fetchEventsubIdAndSubid($db,$inputInfo['c_event_id'], $eventsubId);
        $eventInfo = array();
        if (@empty($eventsubInfo)) {
            Sgmov_Component_Redirect::redirectPublicSsl("/404.html");
            exit;
        } else {
            // イベント情報取得(event検索)
            $eventInfo = $this->_EventService->fetchEventInfoByEventId($db, $eventsubInfo['event_id']);
        }
        Sgmov_Component_Log::debug("======================================================================================");

        // セッションから商品情報を取得
        $shohinInfo = @$_SESSION["CSC"]['SHOHIN_INFO'];
        if (@empty($shohinInfo)) {
            $inputInfo = array();
        }
        // セッションからエラー情報を取得
        $errorInfo = @$_SESSION["CSC"]['ERROR_INFO'];
        if (@empty($errorInfo)) {
            $errorInfo = array();
        }

        // 都道府県情報を取得
        // 全件取得してコンボの項目用配列に整形
        $prefInfo = $this->_PrefectureService->fetchPrefectures($db);
        $prefInfo2= array();
        $count = 0;
        foreach($prefInfo['ids'] as $key => $val) {
            $data = array(
                'prefecture_id' => $val,
                'name' => @empty($prefInfo['names'][$count]) ? '選択してください' : $prefInfo['names'][$count],
            );
            $prefInfo2[] = $data;
            $count++;
        }

        // データ表示マスタ(配達希望メッセージ等)取得
        $haitatsuKiboItemInfo = $this->_CostcoDataDisplayService->getInfo($db, $eventsubInfo['event_id'], $eventsubId, 'HAITATSU_KIBO_ITEM');

        return array(
            'status' => 'success',
            'message' => '商品取得処理に成功しました。',
            'res_data' => array(
                'event' => $eventInfo,
                'eventsub' => $eventsubInfo,
                'input_info' => $inputInfo,
                'shohin_info' => $shohinInfo,
                'pref_info' => $prefInfo2,
                'error_info' => $errorInfo,
                'haitatsu_kibo_item_info' => $haitatsuKiboItemInfo,
            )
        );

    }
}
