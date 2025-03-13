<?php
/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useServices(array('Comiket','ComiketDetail','ComiketBox',));
Sgmov_Lib::useView('azk/Common');
Sgmov_Lib::useView('azk/Input');
Sgmov_Lib::useForms(array('Error', 'AzkSession', 'Azk002Out', 'Azk002In'));
/**#@-*/

/**
 * コミケサービスのお申し込み入力画面を表示します。
 * @package    View
 * @subpackage AZK
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Azk_SizeChange extends Sgmov_View_Azk_Input {

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
        
        parent::__construct();
    }
    
    public function executeInner() {
        
        $db = Sgmov_Component_DB::getPublic();
        
        $inForm = new Sgmov_Form_Azk002In();
        
        $errorForm = NULL;
        $param = filter_input(INPUT_GET, 'param');
        
        // セッションに情報があるかどうかを確認
        $session = Sgmov_Component_Session::get();

        if(empty($param)) {

            // 情報
            $sessionForm = $session->loadForm(self::FEATURE_ID);
            $inForm = @$sessionForm->in;
            if (@empty($inForm)) {
Sgmov_Component_Log::debug ( 'リクエストパラメータなし + セッションにデータなし' );
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
            } else {
                $clearFlg = filter_input(INPUT_GET, 'clr');
                $inForm    = $sessionForm->in;
                if (empty($clearFlg)) {
                    $errorForm = $sessionForm->error;
                } else {
                    $errorForm = NULL;
                }

                // セッション破棄
                $sessionForm->error = NULL;

                $param = @$inForm->comiket_id;
            }
        } else {
            $session->deleteForm(self::FEATURE_ID);
            
            if(strlen($param) == 10) {
                Sgmov_Component_Redirect::redirectPublicSsl("/azk/input2_dialog/{$param}");
            }
            
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
        
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // 申込データ存在チェック
        ///////////////////////////////////////////////////////////////////////////////////////////////////

        $comiketInfo = $this->_Comiket->fetchComiketById($db, $param);
        // if (@empty($comiketInfo) || @$comiketInfo['del_flg'] != '0'
        //         || (
        //             (@$comiketInfo['send_result'] != '3' || @$comiketInfo['batch_status'] != '4')
        //             && (@$comiketInfo['payment_method_cd'] != '1')  // コンビニ前払
        //            )
        //         ) {
        //         // del_flg = 0：初期中 : send_result = 3：送信成功 : batch_status = 4：完了（管理者メール済）
        //     $title = urlencode("お申込み情報が見つかりません");
        //     $message = urlencode("お申込み情報が見つかりませんでした。");
        //     Sgmov_Component_Redirect::redirectPublicSsl("/dsn/error?t={$title}&m={$message}");
        // }
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // 「comiket_detail」no_chg_flg チェック => "1" の場合はキャンセル・サイズ変更できない(搬出のみ)
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        $comiketDetailList = $this->_ComiketDetail->fetchComiketDetailByComiketId($db, $param);
        $comiketDetailInfo = $comiketDetailList[0];
        if(@!empty($comiketDetailInfo['no_chg_flg'])) {
            $title = urlencode("サイズ変更のお申し込みができませんでした");
            $message = urlencode("既に 送り状が発行されているため、サイズ変更できませんでした。");
            Sgmov_Component_Redirect::redirectPublicSsl("/dsn/error?t={$title}&m={$message}");
        }
        ///////////////////////////////////////////////////////////////////////////////////////////////////

        
        // 入力モード
        $inForm->input_mode = $comiketInfo["eventsub_id"];
        // イベント
        $inForm->event_sel = $comiketInfo["event_id"];
        // イベントサブID
        $inForm->eventsub_sel = $comiketInfo["eventsub_id"];
        
        // オブジェクト変換
        $resultData = $this->_createOutFormByInForm($inForm, $param);

        $outForm = $resultData["outForm"];
        $dispItemInfo = $resultData["dispItemInfo"];

        // チケット発行
        $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_AZK001);
        
        return array(
            'ticket'    => $ticket,
            'outForm'   => $outForm,
            'dispItemInfo' => $dispItemInfo,
            'errorForm' => $errorForm,
        );
    }
    
    /**
     * 入力フォームの値を出力フォームを生成します。
     * @param Sgmov_Form_Pve001In $inForm 入力フォーム
     * @return Sgmov_Form_Pve001Out 出力フォーム
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
            // $inForm["comiket_zip"] = $comiketInfo["zip"];
            // $inForm["comiket_pref_cd_sel"] = $comiketInfo["pref_id"];
            // $inForm["comiket_address"] = $comiketInfo["address"];
            // $inForm["comiket_building"] = $comiketInfo["building"];
            $inForm["comiket_tel"] = $comiketInfo["tel"];
            $inForm["comiket_mail"] = $comiketInfo["mail"];
            // $inForm["comiket_mail_retype"] = $comiketInfo["mail"];
            // $inForm["comiket_booth_name"] = $comiketInfo["booth_name"];
            // $inForm["building_name"] = $comiketInfo["building_name"];
            // $inForm["building_booth_position"] = $comiketInfo["booth_position"];
            $inForm["comiket_booth_num"] = $comiketInfo["booth_num"];
            $inForm["comiket_staff_sei"] = $comiketInfo["staff_sei"];
            $inForm["comiket_staff_mei"] = $comiketInfo["staff_mei"];
            $inForm["comiket_staff_sei_furi"] = $comiketInfo["staff_sei_furi"];
            $inForm["comiket_staff_mei_furi"] = $comiketInfo["staff_mei_furi"];
            $inForm["comiket_staff_tel"] = $comiketInfo["staff_tel"];
            $inForm["comiket_detail_type_sel"] = $comikeDetailInfo['type']; // 搬出
            // $inForm["comiket_zip1"] = mb_substr($comiketInfo["zip"], 0, 3);
            // $inForm["comiket_zip2"] = mb_substr($comiketInfo["zip"], -4);
            // $inForm["eventsub_zip"] = $eventsubInfo["zip"];
            $inForm["eventsub_address"] = $eventsubInfo["address"];
            $inForm["eventsub_term_fr"] = $eventsubInfo["term_fr"];
            $inForm["eventsub_term_to"] = $eventsubInfo["term_to"];
            
            $session = Sgmov_Component_Session::get();
            $sessionForm = $session->loadForm(self::FEATURE_ID);
            $inFormFromSession = @$sessionForm->in;

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // 手荷物預かり
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            $inForm['comiket_detail_name'] = $comikeDetailInfo['name'];
            // $inForm['comiket_detail_zip1'] = @substr($comikeDetailInfo['zip'], 0,3);
            // $inForm['comiket_detail_zip2'] = substr($comikeDetailInfo['zip'], 3,7);
            // $inForm['comiket_detail_pref_cd_sel'] = $comikeDetailInfo['pref_id'];
            // $inForm['comiket_detail_address'] = $comikeDetailInfo['address'];
            // $inForm['comiket_detail_building'] = $comikeDetailInfo['building'];
            $inForm['comiket_detail_tel'] = $comikeDetailInfo['tel'];
            $inForm["comiket_detail_service_sel"] = $comikeDetailInfo['service'];; // デザインフェスタは宅配のみ

            $collectDate = $comikeDetailInfo["collect_date"];
            $inForm["comiket_detail_collect_date_year_sel"] = date('Y', strtotime($collectDate . " 00:00:00"));
            $inForm["comiket_detail_collect_date_month_sel"] = date('m', strtotime($collectDate . " 00:00:00"));
            $inForm["comiket_detail_collect_date_day_sel"] = date('d', strtotime($collectDate . " 00:00:00"));
            if (@empty($comikeDetailInfo['collect_st_time'])) {
                $inForm["comiket_detail_collect_time_sel"] = "00";
            } else {
                $inForm["comiket_detail_collect_time_sel"] = "{$comikeDetailInfo['collect_st_time']}-{$comikeDetailInfo['collect_ed_time']}";
            }

            // $deliverytDate = $comikeDetailInfo["delivery_date"];
            // $inForm["comiket_detail_delivery_date_year_sel"] = date('Y', strtotime($deliverytDate . " 00:00:00"));
            // $inForm["comiket_detail_delivery_date_month_sel"] = date('m', strtotime($deliverytDate . " 00:00:00"));
            // $inForm["comiket_detail_delivery_date_day_sel"] = date('d', strtotime($deliverytDate . " 00:00:00"));
            // $inForm["comiket_detail_delivery_time_sel"] = "{$comikeDetailInfo['delivery_timezone_cd']},{$comikeDetailInfo['delivery_timezone_name']}";
            
            if (@empty($inFormFromSession)) {
                $comiketBoxList = $this->_ComiketBox->fetchComiketBoxDataListByIdAndType($db, $param, $comikeDetailInfo['type']);
                $inForm["comiket_box_num_ary"] = array();
                foreach ($comiketBoxList as $key => $val) {
                    $inForm["comiket_box_num_ary"][$val['box_id']] = $val['num'];
                }
            } else {
                $inForm["comiket_box_num_ary"] = @$inFormFromSession->comiket_box_num_ary;
            }

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // お支払い情報
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////            
            $inForm["comiket_payment_method_cd_sel"] = $comiketInfo['payment_method_cd'];
            $inForm["comiket_convenience_store_cd_sel"] = $comiketInfo['convenience_store_cd'];
            $inForm["delivery_charge"]  = $comiketInfo['amount_tax'];
            
            ////////////////////////////////////////////////////////////////////////////////////////////////////////
            // inForm をセッションに保存
            ////////////////////////////////////////////////////////////////////////////////////////////////////////
            
            $sessionForm = new Sgmov_Form_AzkSession();
            
            $inFormStdC = new stdClass();
            foreach ($inForm as $key => $val) {
                $inFormStdC->$key = $val;
            }
            
            $sessionForm->in = $inFormStdC;
            $session->saveForm(self::FEATURE_ID, $sessionForm);
            ////////////////////////////////////////////////////////////////////////////////////////////////////////
        }
        
        $eve001Out = $this->createOutFormByInForm($inForm, new Sgmov_Form_Azk002Out());
    
        return $eve001Out;
    }
}