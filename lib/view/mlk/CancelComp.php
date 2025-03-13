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
Sgmov_Lib::useServices(array('Comiket','ComiketDetail', 'CenterMail', 'Charter', 'ComiketBox', ));
Sgmov_Lib::useView('mlk/Common');
Sgmov_Lib::useView('mlk/Input');
Sgmov_Lib::useView('mlk/Confirm');
Sgmov_Lib::useForms(array('Error', 'EveSession', 'Eve001Out', 'Eve002In'));
/**#@-*/

/**
 * コミケサービスのお申し込み入力画面を表示します。
 * @package    View
 * @subpackage EVE
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Eve_CancelComp extends Sgmov_View_Eve_Common {

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
     * コミケ申込明細データサービス
     * @var Sgmov_Service_Comiket
     */
    private $_ComiketDetail;
    
    /**
     * コミケ申込宅配ボックスサービス
     * @var Sgmov_Service_ComiketBox 
     */
    private $_ComiketBox;

    ///////////////////////////////////////////////////////////////////////////////////////////////////
    // ▼ 業務連携用設定値
    ///////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * リクエストのユーザーIDのキー
     */
    const REQUEST_USER_ID_KEY = 'userId';

    /**
     * リクエストのパスワードのキー
     */
    const REQUEST_PASSWORD_KEY = 'passWord';

    /**
     * リクエストのファイルのキー
     */
    const REQUEST_FILE_KEY = 'filename';

    /**
     * リクエストのデータのキー
     */
    const REQUEST_DATA_KEY = 'data';

    /**
     * 送信プロトコル。ssl の場合は 'ssl://' を指定します。
     *
     * @var string
     */
    public $_wsProtocol;

    /**
     * 送信先ホスト名
     *
     * @var string
     */
    public $_wsHost;

    /**
     * 送信先パス
     *
     * @var string
     */
    public $_wsPath;

    /**
     * 送信先ポート
     *
     * @var integer
     */
    public $_wsPort;

    /**
     * ユーザーID
     *
     * @var string
     */
    public $_wsUserId;

    /**
     * パスワード
     *
     * @var string
     */
    public $_wsPassWord;


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

        $this->_ComiketDetail = new Sgmov_Service_ComiketDetail();
        $this->_ComiketBox = new Sgmov_Service_ComiketBox();

        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // ▼ 業務連携用設定値
        ///////////////////////////////////////////////////////////////////////////////////////////////////

        $this->_wsProtocol = Sgmov_Component_Config::getWsProtocol ();
        $this->_wsHost = Sgmov_Component_Config::getWsHost ();
        $this->_wsPath = Sgmov_Component_Config::getWsCancelPath();
        $this->_wsPort = Sgmov_Component_Config::getWsPort ();
        $this->_wsUserId = Sgmov_Component_Config::getWsUserId ();
        $this->_wsPassWord = Sgmov_Component_Config::getWsPassword ();

        parent::__construct();
    }

    /**
     *
     * @return type
     * @throws Sgmov_Component_Exception
     * @throws Exception
     */
    public function executeInner() {
        Sgmov_Component_Log::debug("CancelComplete######################");
        // セッションに情報があるかどうかを確認
        $session = Sgmov_Component_Session::get();
        // DB接続
        $db = Sgmov_Component_DB::getPublic();
        // 情報
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        $inForm = new Sgmov_Form_Eve002In();

        $errorForm = NULL;
        $param =  filter_input(INPUT_POST, 'param');
        if(strlen($param) == 10) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
        }
        if(!empty($param)) {
//            // チェックデジットチェック
            if(strlen($param) <= 10){
                Sgmov_Component_Log::debug("CancelComplete51######################");
                Sgmov_Component_Log::debug ( '11桁以上ではない' );
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
            }

            if(!is_numeric($param)){
                Sgmov_Component_Log::debug("CancelComplete52######################");
                Sgmov_Component_Log::debug ( '数値ではない' );
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
            }
            $id = substr($param, 0, 10);
            $cd = substr($param, 10);
            
            $sp = self::getChkD2($id);
            if($sp !== intval($cd)){
                Sgmov_Component_Log::debug("CancelComplete53######################");
               Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
            }
        }
        $paramOrg = $param;
        $param = intval(substr($param, 0, 10));

        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // 申込データ存在チェック
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        $comiketInfo = $this->_Comiket->fetchComiketById($db, $param);
        $title = "お申込み情報が見つかりません";
        $message = "お申込み情報が見つかりませんでした。";
        if (@empty($comiketInfo)) { 
            // del_flg = 0：初期中 : send_result = 3：送信成功 : batch_status = 4：完了（管理者メール済）
            $this->redirectErrorPage($title, $message, $arg1);
        } else {
            if (@$comiketInfo['del_flg'] != '0') {
                $title = "キャンセル処理は完了しております";
                $this->redirectErrorPage($title, '', $arg1);
            } else if (@$comiketInfo['send_result'] != '3' || @$comiketInfo['batch_status'] != '4'){
                $this->redirectErrorPage($title, $message, $arg1);
            }
        }
        $comiketDetailList = $this->_ComiketDetail->fetchComiketDetailByComiketId($db, $param);

        ///////////////////////////////////////////////////////////////////////////////////////////////////
        $this->checkReqDate($param, 'キャンセル');
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        // 「comiket_detail」no_chg_flg チェック => "1" の場合はキャンセル・サイズ変更できない(搬出のみ)
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        if(@!empty($comiketDetailList[0]['no_chg_flg'])) {
            $title = urlencode("キャンセルのお申し込みができませんでした");
            $message = urlencode("既に 送り状が発行されているため、キャンセルできませんでした。");
            Sgmov_Component_Redirect::redirectPublicSsl("/eve/error?t={$title}&m={$message}");
        }
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // ▼ 業務連携（キャンセルAPI実行）
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        $renkeiFlg = false; 
        // 業務連携が完了しているデータだけ業務側キャンセルAPI実行する
        if ($comiketInfo['payment_method_cd'] != '1') {  // コンビニ前払いではない
            $renkeiFlg = $this->execWebApiCancelComiket( $this->_wsProtocol, $this->_wsHost, $this->_wsPath, $this->_wsPort, $param, $paramOrg);
        }
        // HP側DB削除 /////////////////////////////////
        if ($renkeiFlg) {
            // 業務連携成功
            $this->_Comiket->updateDelFlg($db, $param, "2"); // 2：削除済
        } else {
            $this->_Comiket->updateDelFlg($db, $param, "1"); // 1：削除中(送信中、送信失敗)
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // ▼ キャンセル完了メール送信
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        foreach ($comiketDetailList as $key => $comiketDetailData) {
            $comiketBoxDataList = $this->_ComiketBox->fetchComiketBoxDataListByIdAndType($db, $param, $comiketDetailData['type']);
            foreach ($comiketBoxDataList as $boxDataKey => $comiketBoxData) {
                $comiketDetailList[$key]['comiket_box_list'][$boxDataKey] = $comiketBoxData;
            }
        }
        $comiketInfo['comiket_detail_list'] = $comiketDetailList;

        $this->sendCompleteMail($comiketInfo, $comiketInfo['mail'], '', '', 'cancel');
        // 管理者側にメール送信
        $mailTo = Sgmov_Component_Config::getComiketCancelAdminMail();
        $this->sendCompleteMail($comiketInfo, $mailTo, '', '', 'sgmv_cancel');
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        $dispItemInfo['comiket'] = $comiketInfo;
        $dispItemInfo['comiket']['tagId'] = $comiketDetailList[0]['cd'];
        
        return array(
            'dispItemInfo' => $dispItemInfo,
        );
    }
}