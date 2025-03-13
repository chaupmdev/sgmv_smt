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
Sgmov_Lib::useServices(array('Comiket', 'Shohin'));
Sgmov_Lib::useView('bpn/Common');
Sgmov_Lib::useForms(array('Error', 'BpnSession', 'Bpn001Out', 'Bpn002In'));
/**#@-*/

/**
 * 物販お申し込みの入力画面を表示します。
 * @package    View
 * @subpackage BPN
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Bpn_Input extends Sgmov_View_Bpn_Common {

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
     * 館マスタサービス(ブース番号)
     * @var type
     */
    protected $_ShohinService;

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
        $this->_CharterService       = new Sgmov_Service_Charter();
        $this->_ShohinService       = new Sgmov_Service_Shohin();

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

       // セッションに情報があるかどうかを確認
        $session = Sgmov_Component_Session::get();
        if(@empty($_SERVER["HTTP_REFERER"]) || strpos($_SERVER["HTTP_REFERER"], "/bpn/") == false) {
            // セッション情報を破棄
            $session->deleteForm(self::FEATURE_ID);
        }
        
        // セッション情報
        $sessionForm = $session->loadForm(self::FEATURE_ID);

        // フォーム
        $inForm = new Sgmov_Form_Bpn002In();
        // エラー引数
        $errorForm = NULL;
        // パラメータ
        $param = filter_input(INPUT_GET, 'param');

        // DB接続
        $db = Sgmov_Component_DB::getPublic();

        ///////////////////////////////////////////////////////////////////////////////////////////////////
        //// ▼ チェックデジット判定 Start
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // 10桁でPostgresのint型の範囲内
        if(strlen($param) == 10 && is_numeric($param) && $param <= self::INT_MAX) {
            Sgmov_Component_Redirect::redirectPublicSsl("/bpn/input2_dialog/{$param}");
        }

        $input2DialogFlg = filter_input(INPUT_POST, 'input2_dialog');
        if($input2DialogFlg == "1") {
            $input2DialogComiketId = filter_input(INPUT_POST, 'id');
            if(!is_numeric($input2DialogComiketId)){
                Sgmov_Component_Redirect::redirectPublicSsl("/bpn/temp_error");
                exit;
            }
            
            $comiketData = $this->_Comiket->fetchComiketById($db, intval($input2DialogComiketId));
            if(empty($comiketData)) {
                Sgmov_Component_Redirect::redirectPublicSsl("/bpn/temp_error");
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
                Sgmov_Component_Redirect::redirectPublicSsl("/bpn/input/{$param}");
            } else {
                Sgmov_Component_Redirect::redirectPublicSsl("/bpn/temp_error/{$comiketData['event_id']}");
            }
        }

        if(@!empty($_SERVER["REQUEST_URI"]) && strpos($_SERVER["REQUEST_URI"], "/bpn/input2") !== false && empty($param)) {
            // input2 初期表示時にGETパラメータがない場合
            $checkForm = $sessionForm->in;
            $checkForm = (array)$checkForm;

            if(empty($checkForm['comiket_id'])) {
                // 申込みIDがセッションにない場合
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
            }
        }

        if(@!empty($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], "/bpn/") !== false ){
            // 初期表示時以外(入力エラーなどで戻ってきた場合など)
            if (isset($sessionForm)) {
                $clearFlg = filter_input(INPUT_GET, 'clr');

                if(strpos($_SERVER["REQUEST_URI"], "?clr") !== false ){
                    $clearFlg = "1";
                }

                $inForm    = @$sessionForm->in;
                if (empty($clearFlg)) {
                    // クレジットカードのエラーを削除する。
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
            }
        }

        $bpnType = "1";
        $shohinPattern = "1";
        if (empty($param)) {
            $title = "対象のデータが見つかりません。";
            $message = urlencode("対象のデータが見つかりません。");
            Sgmov_Component_Redirect::redirectPublicSsl("/bpn/error?t={$title}&m={$message}");
        } else {
            $splitParam = explode("/", $param);

            // イベント識別子
            if(isset($splitParam[0])){
                $shikibetsushi = $splitParam[0];
            }

            // 識別子 is_string、文字数チェック
            if (!is_string($shikibetsushi) && strlen($shikibetsushi)>3) {
                Sgmov_Component_Log::debug ( '文字値ではない' );
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
            }

            // イベント識別子からイベント情報(イベントID、イベントサブID)を取得
            $eventInfo = $this->_EventService->fetchEventByShikibetsushi($db, $shikibetsushi);

            if (empty($eventInfo)) {
                $title = "対象のデータが見つかりません。";
                $message = urlencode("対象のデータが見つかりません。");
                Sgmov_Component_Redirect::redirectPublicSsl("/bpn/error?t={$title}&m={$message}");
            } else {
                // イベント
                $inForm->event_sel = $eventInfo["eventid"];

                // inputmode
                $inForm->input_mode = $eventInfo["eventsubid"];

                // イベントサブ
                $inForm->eventsub_sel = $eventInfo["eventsubid"];
            }
            
            // 商品ヘッダパターン
            if(isset($splitParam[1])){
                $bpnType = $splitParam[1];
            }

            // 商品リストパターン
            if(isset($splitParam[2])){
                $shohinPattern = $splitParam[2];
            }
        }

        // GETパラメータで物販の状態決定
        // https://sagawa-mov-test04.media-tec.jp/bpn/input/dsn/1/2
        // dsn以降2つ目のパラメータが商品ヘッダ
        // 商品ヘッダパターン[事前物販用:1、当日物販用:2]
        $inForm->bpn_type = $bpnType;

        // dsn以降3つ目のパラメータが商品リスト
        // 商品リストパターン[飛沫ブロッカー用:1、梱包資材用:2]
        $inForm->shohin_pattern = $shohinPattern;

        // イベント識別子
        $inForm->shikibetsushi = $shikibetsushi;


        // 商品の申し込み期間は範囲外かどうかチェックする。
        $shohinTerm = $this->_ShohinService->checkShohinInfo($db, $inForm->eventsub_sel, $inForm->bpn_type, $inForm->shohin_pattern);

        if($shohinTerm['count'] < 1){
            $eventSubData = $this->_EventsubService->getEventId($db, $inForm->eventsub_sel);
            $title = urlencode("物販サービス申込受付期間外です");
            $eventName = $eventSubData['eventname']." ".$eventSubData['eventsubname'];
            $message = urlencode("「{$eventName}」のお申込期間は範囲外です。");
            Sgmov_Component_Redirect::redirectPublicSsl("/bpn/error?t={$title}&m={$message}");
            exit;
        }

        // フォーム情報生成する。
        $resultData = $this->_createOutFormByInForm($inForm, $param);
        // フォーム情報
        $outForm = $resultData["outForm"];
        // 表示項目
        $dispItemInfo = $resultData["dispItemInfo"];
        

        ////////////////////////////////////////////////////////////////////////////////////////////////////
        // 入力可能期間チェック
        ////////////////////////////////////////////////////////////////////////////////////////////////////
        $shohinList = $this->_ShohinService->fetchShohinByEventSubId($db, $inForm->eventsub_sel);
        $getAllShohin = $this->filterShohinResult($shohinList);
        if(!@empty($inForm->comiket_box_buppan_num_ary)){
            foreach ($inForm->comiket_box_buppan_num_ary as $key => $value) {
                $checkResult = $this->_ShohinService->checkShohinTerm($db, $key);
                if((empty($checkResult) || $checkResult["count"] == "0" )){
                    if(@empty($errorForm)){
                        $errorForm = new Sgmov_Form_Error();
                    }
                    $errorForm->addError('comiket_box_buppan_num_ary_'.$key, "{$getAllShohin[$key]}は申込期間範囲外です。");
                }
            }
        }

        if(isset($resultData["dispItemInfo"]["sold_out_all"])){
            $errorForm = new Sgmov_Form_Error();
            $errorForm->addError('sold_out_err', "全ての商品は完売しました。");
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // サイトの表示を shohin.term_fr(申込開始) ～ eventsub.arrival_to_time(復路申込期間終了) で制御する
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        $this->checkShohinInTerm($db, $inForm->eventsub_sel);


        // チケット発行
        $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_BPN001);

        return array(
            'ticket'    => $ticket,
            'outForm'   => $outForm,
            'dispItemInfo' => $dispItemInfo,
            'errorForm' => $errorForm,
        );
    }

    /**
     * 入力フォームの値を出力フォームを生成します。
     * @param Sgmov_Form_Bpn001In $inForm 入力フォーム
     * @return Sgmov_Form_Bpn001Out 出力フォーム
     */
    protected function _createOutFormByInForm($inForm, $param=NULL) {
        $inForm = (array)$inForm;
        return $this->createOutFormByInForm($inForm, new Sgmov_Form_Bpn001Out());
    }

}