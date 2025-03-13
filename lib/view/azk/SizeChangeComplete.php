<?php

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('azk/Common', 'CommonConst');
Sgmov_Lib::useView('azk/Bcm');
Sgmov_Lib::useView('azk/Complete');
Sgmov_Lib::useForms(array('Error', 'AzkSession', 'Azk003Out'));
Sgmov_Lib::useServices(array('Comiket', 'ComiketDetail', 'ComiketBox', 'CenterMail', 'SgFinancial', 'HttpsZipCodeDll', 'BoxFare'));
Sgmov_Lib::useImageQRCode();
Sgmov_Lib::use3gMdk(array('3GPSMDK'));
/**#@-*/

/**
 * イベント手荷物受付サービスのお申し込み内容を登録し、完了画面を表示します。
 * @package    View
 * @subpackage AZK
 * @author     K.Sawada
 * @copyright  2018-2019 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Azk_SizeChangeComplete extends Sgmov_View_Azk_Complete {

    /**
     * コミケ申込データサービス
     * @var type
     */
    protected $_Comiket;

    /**
     * コミケ詳細申込データサービス
     * @var type
     */
    protected $_ComiketDetail;

    /**
     * コミケ詳細申込データサービス
     * @var type
     */
    protected $_ComiketBox;

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

    ///////////////////////////////////////////////////////////////////////////////////////////////////
    // ▼ 業務連携用設定値
    ///////////////////////////////////////////////////////////////////////////////////////////////////
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

        $this->_Comiket = new Sgmov_Service_Comiket();
        $this->_ComiketDetail = new Sgmov_Service_ComiketDetail();
        $this->_ComiketBox = new Sgmov_Service_ComiketBox();
        $this->_EventService = new Sgmov_Service_Event();
        $this->_EventsubService = new Sgmov_Service_Eventsub();

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
        
        // ▼▼▼ キャンセル・サイズ変更対応が 11月からになったので、処理を止めておく(メールに追加必要)
//        Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
        // ▲▲▲ キャンセル・サイズ変更対応が 11月からになったので、処理を止めておく(メールに追加必要)

        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        // セッションに入力チェック済みの情報があるかどうかを確認
        $session = Sgmov_Component_Session::get();
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        $inForm = (array) $sessionForm->in;

        $boxNumTotal = 0;
        foreach ($inForm['comiket_box_num_ary'] as $key => $val) {
            if (@!empty($val)) {
                $boxNumTotal += (integer) $val;
            }
        }
Sgmov_Component_Log::debug($inForm);

        // コミケ情報
        $comiketInfo = $this->_Comiket->fetchComiketById($db, $inForm['comiket_id']);

        $comiketDetailList = $this->_ComiketDetail->fetchComiketDetailByComiketId($db, $inForm['comiket_id']);
        $comiketDetailInfo = $comiketDetailList[0];
        
        $comiketBoxList = $this->_ComiketBox->fetchComiketBoxDataListByIdAndTypeOrderByCd($db, $inForm['comiket_id'], $comiketDetailInfo['type']);


        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // 申込データ存在チェック
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        
        if (@empty($comiketInfo) || @$comiketInfo['del_flg'] != '0'
                || (
                    (@$comiketInfo['send_result'] != '3' || @$comiketInfo['batch_status'] != '4')
                    && (@$comiketInfo['payment_method_cd'] != '1')  // コンビニ前払
                   )
                ) {
                // del_flg = 0：初期中 : send_result = 3：送信成功 : batch_status = 4：完了（管理者メール済）
            $title = urlencode("お申込み情報が見つかりません");
            $message = urlencode("お申込み情報が見つかりませんでした。");
            Sgmov_Component_Redirect::redirectPublicSsl("/dsn/error?t={$title}&m={$message}");
        }
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        
        
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // 「comiket_detail」no_chg_flg チェック => "1" の場合はキャンセル・サイズ変更できない(搬出のみ)
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        if(@!empty($comiketDetailInfo['no_chg_flg'])) {
            $title = urlencode("サイズ変更のお申し込みができませんでした");
            $message = urlencode("既に 送り状が発行されているため、サイズ変更できませんでした。");
            Sgmov_Component_Redirect::redirectPublicSsl("/azk/error?t={$title}&m={$message}");
        }
        ///////////////////////////////////////////////////////////////////////////////////////////////////

        $parentRes = array();

        if (@empty($boxNumTotal)) {
            // 宅配数量合計が0の場合はキャンセル処理のみ
            // 出力情報を設定
            $outForm = $this->_createOutFormByInForm($inForm);
            // 出力情報を設定
            $outForm->raw_qr_code_string = $inForm['comiket_id'];
            $eventData = $this->_EventService->fetchEventById($db, $comiketInfo['event_id']);
            $eventsubData = $this->_EventsubService->fetchEventsubByEventsubId($db, $comiketInfo['eventsub_id']);
            $parentRes = array(
                'outForm' => $outForm,
                'eventData' => $eventData,
                'eventsubData' => $eventsubData,
                'is_cancel_only' => true,
            );
        } else {
            // 先に親処理の実行(セッションはなくなる)
            $parentRes = parent::executeInner("size_change"); // エラー時の戻り先指定
        }

        $comiketId = sprintf("%010d", $inForm['comiket_id']);
        $paramOrg = $comiketId . $this->getChkD($comiketId);
        
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // ▼ 業務連携（キャンセルAPI実行）
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        $renkeiFlg = false; 
        if ($comiketInfo['payment_method_cd'] != '1') {
            $renkeiFlg = $this->execWebApiCancelComiket($this->_wsProtocol , $this->_wsHost
                    , $this->_wsPath, $this->_wsPort, $inForm['comiket_id'], $paramOrg
                    , '個数・サイズ変更完了しました' ,'個数・サイズ変更完了しました。個数・サイズ変更完了メール送信してますので、ご確認ください。');
        }

        // HP側DB削除 /////////////////////////////////
        if ($renkeiFlg) {
            // 業務連携成功
            $this->_Comiket->updateDelFlg($db, $comiketId, "2"); // 2：削除済
        } else {
            $this->_Comiket->updateDelFlg($db, $comiketId, "1"); // 1：削除中(送信中、送信失敗)
        }

        // 宅配数量合計が0の場合はキャンセルメール送信(キャンセルのみ)
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // ▼ キャンセル完了メール送信
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        $comiketDetailInfo['comiket_box_list'] = $comiketBoxList;
        $comiketInfo['comiket_detail_list'][] = $comiketDetailInfo;

        $comiketInfo['sgf_res_shopOrderId'] = '';
        $comiketInfo['sgf_res_transactionId'] = @$comiketInfo['transaction_id'];


        $sendTo = Sgmov_Component_Config::getEveCommonCompleteMail();      
        $this->sendCompleteMail($comiketInfo, $sendTo, '', 'cancel');
        
        // 管理者側にメール送信
        $mailTo = Sgmov_Component_Config::getComiketCancelAdminMail();
        $this->sendCompleteMail($comiketInfo, $mailTo, '', 'sgmv_cancel');
        ///////////////////////////////////////////////////////////////////////////////////////////////////

        return $parentRes;
    }

    /**
     *
     * @param type $li
     * @param type $inForm
     */
    protected function sendCompleteMail2($li, $inForm) {
        // メール送信
        if ((!empty($li["treeData"]['mail']) && !empty($li["treeData"]['merchant_result']))
                || $li["treeData"]['payment_method_cd'] == '3' // 電子マネー
                ) {

            $li["treeData"]['sgf_res_shopOrderId'] = @$inForm['sgf_res_shopOrderId'];

            $li["treeData"]['sgf_res_transactionId'] = @$inForm['sgf_res_transactionId'];

            $li["treeData"]['payment_url'] = @$inForm['payment_url'];

            $ccMail = '';

            $this->sendCompleteMail($li["treeData"], $li["treeData"]['mail'], $ccMail, 'sizechange');
        }
    }
}