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
 * 物販お申し込みのサイズ変更(個数変更)画面を表示します。
 * @package    View
 * @subpackage BPN
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Bpn_SizeChange extends Sgmov_View_Bpn_Input {

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
        $this->_ComiketDetail = new Sgmov_Service_ComiketDetail();
        $this->_ComiketBox = new Sgmov_Service_ComiketBox();
        $this->_PrefectureService     = new Sgmov_Service_Prefecture();
        $this->_EventService          = new Sgmov_Service_Event();
        $this->_EventsubService       = new Sgmov_Service_Eventsub();
        $this->_BoxService       = new Sgmov_Service_Box();
        $this->_BuildingService       = new Sgmov_Service_Building();
        $this->_CharterService       = new Sgmov_Service_Charter();
        $this->_ShohinService       = new Sgmov_Service_Shohin();
        
        parent::__construct();
    }
    
    public function executeInner() {

        $db = Sgmov_Component_DB::getPublic();

        $inForm = new Sgmov_Form_Bpn002In();
      
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
                // クレジットカードのエラーを削除する。
                $errorForm = $this->clearCreditCardError(@$sessionForm->error);
                $param = @$inForm->comiket_id;
            }
        } else {
            $session->deleteForm(self::FEATURE_ID);
            
            if(strlen($param) == 10) {
                Sgmov_Component_Redirect::redirectPublicSsl("/bpn/input2_dialog/{$param}");
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
        // 「comiket_detail」no_chg_flg チェック => "1" の場合はキャンセル・サイズ変更できない
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        $comiketDetailList = $this->_ComiketDetail->fetchComiketDetailByComiketId($db, $param);
        $comiketDetailInfo = $comiketDetailList[0];
        if(@!empty($comiketDetailInfo['no_chg_flg'])) {
            $title = urlencode("サイズ変更のお申し込みができませんでした");
            $message = urlencode("既に 商品が発行されているため、サイズ変更できませんでした。");
            Sgmov_Component_Redirect::redirectPublicSsl("/bpn/error?t={$title}&m={$message}");
        }
        ///////////////////////////////////////////////////////////////////////////////////////////////////

        $inForm->input_mode = $comiketInfo["eventsub_id"];
        $inForm->event_sel = $comiketInfo["event_id"];
        $inForm->eventsub_sel = $comiketInfo["eventsub_id"];

        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // 商品は、min(申込開始) ～　復路申込期間終了(時間あり)期間内にチェック
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        $this->checkShohinInTerm($db, $inForm->eventsub_sel);

        $resultData = $this->_createOutFormByInForm($inForm, $param);


        $outForm = $resultData["outForm"];

        $dispItemInfo = $resultData["dispItemInfo"];

        $comiketBoxList = $this->_ComiketBox->fetchComiketBoxDataListByIdAndType($db, $param, "5");
        $getComiketNum = $this->filterComiketBoxnResult($comiketBoxList);
        $shohinList = $this->_ShohinService->fetchShohinByEventSubId($db, $inForm->eventsub_sel);
        $getAllShohin = $this->filterShohinResult($shohinList);
        $originalChangeSuryo = false;
        foreach ($inForm->comiket_box_buppan_num_ary as $key => $value) {
            $checkResult = $this->_ShohinService->checkShohinTerm($db, $key);
            if(isset($getComiketNum[$key])){
                if(($value != $getComiketNum[$key]) &&  (empty($checkResult) || $checkResult["count"] == "0" )){
                    $errorForm->addError('comiket_box_buppan_num_ary_'.$key, "{$getAllShohin[$key]}は申込期間範囲外です。数量を元に戻します。（数量：{$value} => {$getComiketNum[$key]}）");
                }
            }
        }

        if(isset($dispItemInfo["input_buppan_lbls"]["expiry_all"])){
            $errorForm = new Sgmov_Form_Error();
            $errorForm->addError('comiket_box_buppan_num_ary', '全て商品が範囲外です。');
        }

        // チケット発行
        $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_BPN001);
        
        return array(
            'ticket'    => $ticket,
            'outForm'   => $outForm,
            'dispItemInfo' => $dispItemInfo,
            'errorForm' => $errorForm,
            'bpnType'   => $comiketInfo["bpn_type"]
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
//            $comikeDetailInfo = $comikeDetailInfoList[0];
            
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
            $inForm["comiket_booth_name"] = $comiketInfo["booth_name"];
            $inForm["building_name"] = $comiketInfo["building_name"];
            $inForm["building_booth_position"] = $comiketInfo["booth_position"];
            $inForm["comiket_booth_num"] = $comiketInfo["booth_num"];
            $inForm["comiket_staff_sei"] = $comiketInfo["staff_sei"];
            $inForm["comiket_staff_mei"] = $comiketInfo["staff_mei"];
            $inForm["comiket_staff_sei_furi"] = $comiketInfo["staff_sei_furi"];
            $inForm["comiket_staff_mei_furi"] = $comiketInfo["staff_mei_furi"];
            $inForm["comiket_staff_tel"] = $comiketInfo["staff_tel"];
            $inForm["comiket_zip1"] = mb_substr($comiketInfo["zip"], 0, 3);
            $inForm["comiket_zip2"] = mb_substr($comiketInfo["zip"], -4);
            $inForm["eventsub_zip"] = $eventsubInfo["zip"];
            $inForm["eventsub_address"] = $eventsubInfo["address"];
            $inForm["eventsub_term_fr"] = $eventsubInfo["term_fr"];
            $inForm["eventsub_term_to"] = $eventsubInfo["term_to"];
            $inForm['comiket_detail_type_sel'] = $comiketInfo["choice"];
            $inForm['bpn_type'] = $comiketInfo["bpn_type"];
            $session = Sgmov_Component_Session::get();
            $sessionForm = $session->loadForm(self::FEATURE_ID);
            $inFormFromSession = @$sessionForm->in;

            if (@empty($inFormFromSession->comiket_box_buppan_num_ary)) {
                $comiketBoxList = $this->_ComiketBox->fetchComiketBoxDataListByIdAndType($db, $param, "5");
                $inForm["comiket_box_buppan_num_ary"] = array();
                $inForm["comiket_box_buppan_ziko_shohin_cd_ary"] = array();
                foreach ($comiketBoxList as $key => $val) {
                    $inForm["comiket_box_buppan_num_ary"][$val['box_id']] = $val['num'];
                    $getZikoShohinCd = $this->_ShohinService->fetchShohinById($db, $val['box_id']);
                    $inForm["comiket_box_buppan_ziko_shohin_cd_ary"][$val['box_id']] = $getZikoShohinCd['ziko_shohin_cd'];
                }
            } else {
                $inForm["comiket_box_buppan_num_ary"] = @$inFormFromSession->comiket_box_buppan_num_ary;
                $inForm["comiket_box_buppan_ziko_shohin_cd_ary"] = @$inFormFromSession->comiket_box_buppan_ziko_shohin_cd_ary;
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
            
            $sessionForm = new Sgmov_Form_BpnSession();
            
            $inFormStdC = new stdClass();
            foreach ($inForm as $key => $val) {
                $inFormStdC->$key = $val;
            }
            
            $sessionForm->in = $inFormStdC;
            $session->saveForm(self::FEATURE_ID, $sessionForm);
        }  

        $bpn001Out = $this->createOutFormByInForm($inForm, new Sgmov_Form_Bpn001Out());

        if($inForm['bpn_type'] == "1"){
            // 普通物販
            $dataSet = $this->_ShohinService->fetchShohinByRyoFlg($db, $inForm["eventsub_sel"], $inForm['bpn_type']);
        }else{
            //当日物販 本日の商品のみ取得する
            $dataSet = $this->_ShohinService->fetchShohinByEventSubIdWithInTerm($db, $inForm["eventsub_sel"], $inForm['bpn_type']);
        }

        $bpn001Out["dispItemInfo"]["input_buppan_lbls"] = $this->setShohinNameList($dataSet);
        
        $dispItemInfo = $bpn001Out["dispItemInfo"];

        $comiketInfo = $this->_Comiket->fetchComiketById($db, $param);

        $comiketBoXList["comiket_box_data"] = array();
        $comiketBoxList = $this->_ComiketBox->fetchComiketBoxDataListByIdAndType($db, $param, "5");
        foreach ($comiketBoxList as $key => $val) {
            $comiketBoXList["comiket_box_data"][$val['box_id']] = $val['num'];
        }

        $dispItemInfo['comiket_box_data'] = $comiketBoXList["comiket_box_data"];

        $dispItemInfo['amount_tax'] = $comiketInfo['amount_tax'];
        
        $bpn001Out["dispItemInfo"] = $dispItemInfo;
        return $bpn001Out;
    }

    protected function filterComiketBoxnResult($comiketBoxList){
        $returnList = array();
        foreach ($comiketBoxList as $key => $value) {
            $returnList[$value['box_id']] = $value['num'];
        }

        return $returnList;
    }


    protected function filterShohinResult($shohinList){
        $returnList = array();
        foreach ($shohinList as $key => $value) {
            $returnList[$value['id']] = $value['name'];
        }

        return $returnList;
    }

    /**
     * セッションエラー配列から、クレジットカードエラーを削除する。
     * @param  $errorForm セッションエラー
     * @return $errorForm セッションエラー
     */
    protected function clearCreditCardError($errorForm){
        $errorArr = array("top_card_expire_year_cd_sel", "top_card_expire_month_cd_sel", "top_card_expire", "top_card_number", "top_security_cd");
        if(isset($errorForm)){
            foreach ($errorArr as $key => $value) {
                if(array_key_exists($value, $errorForm->_errors)){
                    unset($errorForm->_errors[$value]);
                }
            }
        }
       
        return $errorForm;
    }
}