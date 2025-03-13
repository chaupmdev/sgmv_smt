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
Sgmov_Lib::useServices(array('Comiket','ComiketDetail','ComiketBox',));
Sgmov_Lib::useView('bpn/Common');
Sgmov_Lib::useView('bpn/Input');
Sgmov_Lib::useForms(array('Error', 'BpnSession', 'Bpn001Out', 'Bpn002In'));
/**#@-*/

/**
 * 物販お申し込みキャンセルの完了画面を表示します。
 * @package    View
 * @subpackage BPN
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Bpn_CancelComp extends Sgmov_View_Bpn_Common {

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
        $this->_appCommon             = new Sgmov_Service_AppCommon();
        $this->_Comiket = new Sgmov_Service_Comiket();
        $this->_ComiketDetail = new Sgmov_Service_ComiketDetail();
        $this->_ComiketBox = new Sgmov_Service_ComiketBox();
        $this->_PrefectureService     = new Sgmov_Service_Prefecture();
        $this->_EventService          = new Sgmov_Service_Event();
        $this->_EventsubService       = new Sgmov_Service_Eventsub();
        $this->_BoxService       = new Sgmov_Service_Box();
        $this->_BuildingService       = new Sgmov_Service_Building();
        $this->_CharterService       = new Sgmov_Service_Charter();

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

    public function executeInner() {
        
        // ▼▼▼ キャンセル・サイズ変更対応が 11月からになったので、処理を止めておく(メールに追加必要)
//        Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
        // ▲▲▲ キャンセル・サイズ変更対応が 11月からになったので、処理を止めておく(メールに追加必要)
        
        // セッションに情報があるかどうかを確認
        $session = Sgmov_Component_Session::get();
        // DB接続
        $db = Sgmov_Component_DB::getPublic();
        $param = filter_input(INPUT_POST, 'param');

        // 情報
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        $inForm = new Sgmov_Form_Bpn002In();

        $errorForm = NULL;

        if(strlen($param) == 10) {
            Sgmov_Component_Redirect::redirectPublicSsl("/bpn/input2_dialog/{$param}");
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

        }
        $paramOrg = $param;
        $param = intval(substr($param, 0, 10));

        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // 申込データ存在チェック
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        $comiketInfo = $this->_Comiket->fetchComiketById($db, $param);
        if (@empty($comiketInfo) || @$comiketInfo['del_flg'] != '0'
                || (
                    (@$comiketInfo['send_result'] != '3' || @$comiketInfo['batch_status'] != '4')
                    && (@$comiketInfo['payment_method_cd'] != '1')  // コンビニ前払
                   )
                ) {
                // del_flg = 0：初期中 : send_result = 3：送信成功 : batch_status = 4：完了（管理者メール済）
            $title = "お申込み情報が見つかりません";
            $message = "お申込み情報が見つかりませんでした。";
            Sgmov_Component_Redirect::redirectPublicSsl("/bpn/error?t={$title}&m={$message}");
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // 商品は、min(申込開始) ～　復路申込期間終了(時間あり)期間内にチェック
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        $this->checkShohinInTerm($db, $comiketInfo['eventsub_id']);

        $comiketDetailList = $this->_ComiketDetail->fetchComiketDetailByComiketId($db, $param);
        $comiketDetailInfo = $comiketDetailList[0];

        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // 「comiket_detail」no_chg_flg チェック => "1" の場合はキャンセル・サイズ変更できない(搬出のみ)
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        if(@!empty($comiketDetailInfo['no_chg_flg'])) {
            $title = urlencode("キャンセルのお申し込みができませんでした");
            $message = urlencode("既に 商品が発行されているため、キャンセルできませんでした。");
            Sgmov_Component_Redirect::redirectPublicSsl("/bpn/error?t={$title}&m={$message}");
        }
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // ▼ 業務連携（キャンセルAPI実行）
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        $renkeiFlg = false; 
        // 業務連携が完了しているデータだけ業務側キャンセルAPI実行する
        if ($comiketInfo['payment_method_cd'] != '1') {  // コンビニ前払いではない
            // TODO VASI
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
        $comiketBoxList = $this->_ComiketBox->fetchComiketBoxDataListByIdAndType($db, $param, $comiketDetailInfo['type']);
        $comiketDetailInfo['comiket_box_list'] = $comiketBoxList;
        $comiketInfo['comiket_detail_list'][] = $comiketDetailInfo;

        $comiketInfo['sgf_res_shopOrderId'] = '';
        $comiketInfo['sgf_res_transactionId'] = @$comiketInfo['transaction_id'];

        // 問い合わせ番号
        $comiketInfo['toiawase_no'] = $comiketDetailInfo["toiawase_no"];

        // 管理者側にメール送信
        $mailTo = Sgmov_Component_Config::getComiketCancelAdminMail();
        
        if($comiketInfo["bpn_type"] == "1"){
            // 物販
            $this->sendCompleteMail($comiketInfo, $comiketInfo['mail'], '', '_cancel');
            $this->sendCompleteMail($comiketInfo, $mailTo, '', '_sgmv_cancel');
        }else{
            // 当日物販
            $this->sendCompleteMailForActiveShohin($comiketInfo, $comiketInfo['mail'], '', '_cancel');
            $this->sendCompleteMailForActiveShohin($comiketInfo, $mailTo, '', '_sgmv_cancel');
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////

        $dispItemInfo['comiket'] = $comiketInfo;
        return array(
            'dispItemInfo' => $dispItemInfo,
            'bpnType'      => $comiketInfo["bpn_type"],
            'shohin_patterm'      => $comiketInfo["list_ptrn"]
        );
    }

    /**
     * 入力フォームの値を出力フォームを生成します。
     * @param Sgmov_Form_Bpn001In $inForm 入力フォーム
     * @return Sgmov_Form_Bpn001Out 出力フォーム
     */
    protected function _createOutFormByInForm($inForm, $param=NULL) {
        if(!empty($param)) {
            $inForm = (array)$inForm;

            $db = Sgmov_Component_DB::getPublic();
            $comiketInfo = $this->_Comiket->fetchComiketById($db, $param);
            $comikeDetailInfoList = $this->_ComiketDetail->fetchComiketDetailByComiketId($db, $param);
            // デザフェスでは詳細は１件しかない
            $comikeDetailInfo = $comikeDetailInfoList[0];

            if(empty($comiketInfo)) {
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
            }

            $eventInfo = $this->_EventService->fetchEventById($db, $comiketInfo["event_id"]);
            $eventsubInfo = $this->_EventsubService->fetchEventsubByEventsubId($db, $comiketInfo["eventsub_id"]);

            $inForm["comiket_id"] = $param;
            $inForm["event_sel"] = $comiketInfo["event_id"];
            $inForm["eventsub_sel"] = $comiketInfo["eventsub_id"];
            $inForm["comiket_div"] = $comiketInfo["div"];
            $inForm["comiket_customer_cd"] = $comiketInfo["customer_cd"];
            $inForm["office_name"] = $comiketInfo["office_name"];
            $inForm["comiket_personal_name_sei"] = $comiketInfo["personal_name_sei"];
            $inForm["comiket_personal_name_mei"] = $comiketInfo["personal_name_mei"];
            $inForm["comiket_zip"] = $comiketInfo["zip"];
            $inForm["comiket_pref_cd_sel"] = $comiketInfo["pref_id"];
            $inForm["comiket_address"] = $comiketInfo["address"];
            $inForm["comiket_building"] = $comiketInfo["building"];
            $inForm["comiket_tel"] = $comiketInfo["tel"];
            $inForm["comiket_mail"] = $comiketInfo["mail"];
            $inForm["comiket_mail_retype"] = $comiketInfo["mail"];
            $inForm["building_name"] = $comiketInfo["building_name"];
            $inForm["building_booth_position"] = $comiketInfo["booth_position"];
            $inForm["comiket_booth_num"] = $comiketInfo["booth_num"];
            $inForm["comiket_staff_sei"] = $comiketInfo["staff_sei"];
            $inForm["comiket_staff_mei"] = $comiketInfo["staff_mei"];
            $inForm["comiket_staff_sei_furi"] = $comiketInfo["staff_sei_furi"];
            $inForm["comiket_staff_mei_furi"] = $comiketInfo["staff_mei_furi"];
            $inForm["comiket_staff_tel"] = $comiketInfo["staff_tel"];
            $inForm["comiket_detail_type_sel"] = $comikeDetailInfo['type']; // 搬出
            $inForm["comiket_zip1"] = mb_substr($comiketInfo["zip"], 0, 3);
            $inForm["comiket_zip2"] = mb_substr($comiketInfo["zip"], -4);
            $inForm["eventsub_zip"] = $eventsubInfo["zip"];
            $inForm["eventsub_address"] = $eventsubInfo["address"];
            $inForm["eventsub_term_fr"] = $eventsubInfo["term_fr"];
            $inForm["eventsub_term_to"] = $eventsubInfo["term_to"];
            $inForm["comiket_payment_method_cd_sel"] = $comiketInfo['payment_method_cd'];
        }

        $bpn001Out = $this->createOutFormByInForm($inForm, new Sgmov_Form_Bpn001Out());

        $dispItemInfo = $bpn001Out["dispItemInfo"];

        $comiketInfo = $this->_Comiket->fetchComiketById($db, $param);
        $dispItemInfo['amount_tax'] = $comiketInfo['amount_tax'];

        $bpn001Out["dispItemInfo"] = $dispItemInfo;
        return $bpn001Out;
    }
}