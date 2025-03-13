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
class Sgmov_View_Eve_CancelConf extends Sgmov_View_Eve_Common {

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
     * コミケ申込宅配Boxサービス
     * @var Sgmov_Service_Comiket_Box 
     */
    private $_ComiketBox;
    

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
        
        parent::__construct();
    }
    
    public function executeInner() {
        Sgmov_Component_Log::debug("Cancel######################");
        // DB接続
        $db = Sgmov_Component_DB::getPublic();
        // セッションに情報があるかどうかを確認
        $session = Sgmov_Component_Session::get();
        
        // 情報
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        
        $inForm = new Sgmov_Form_Eve002In();
        
        $errorForm = NULL;
        
        $param =  filter_input(INPUT_GET, 'id');
        
        if(strlen($param) == 10) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
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
            
            $sp = self::getChkD2($id);
            if($sp !== intval($cd)){
               Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
            }
        }
        
        $param = intval(substr($param, 0, 10));

        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // 申込データ存在チェック
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        $outForm = array();
        $comiketInfo = $this->_Comiket->fetchComiketById($db, $param);
        
        if (@empty($comiketInfo) || @$comiketInfo['del_flg'] != '0'
                || (
                    (@$comiketInfo['send_result'] != '3' || @$comiketInfo['batch_status'] != '4')
                   )
                ) {
            // del_flg = 0：初期中 : send_result = 3：送信成功 : batch_status = 4：完了（管理者メール済）
            $title = "お申込み情報が見つかりません";
            $message = "お申込み情報が見つかりませんでした。";
            Sgmov_Component_Redirect::redirectPublicSsl("/mlk/error?t={$title}&m={$message}");
        }
        $outForm['comiket'] = $comiketInfo;
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // 「comiket_detail」no_chg_flg チェック => "1" の場合はキャンセル・サイズ変更できない
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        $comiketDetailList = $this->_ComiketDetail->fetchComiketDetailByComiketId($db, $param);
         
        $outForm['comiket_detail_list'] = $comiketDetailList;
        $comiketDetailInfo = $comiketDetailList[0];
        

        if(@!empty($comiketDetailInfo['no_chg_flg'])) {
            $title = urlencode("キャンセルお申し込みができませんでした");
            $message = urlencode("既に 送り状が発行されているため、キャンセルできませんでした。");
            Sgmov_Component_Redirect::redirectPublicSsl("/mlk/error?t={$title}&m={$message}");
        }
        
        $comiketBoxList = $this->_ComiketBox->fetchComiketBoxByListComiketIds($db, [$param]);
        $outForm['comiket_box_list'] = $comiketBoxList;
               
        // チケット発行
        $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_EVE001);
        
        $hachakutenCodeFrom  = substr($comiketDetailInfo['cd'], 0, 8);
        $hachakutenCodeTo  = $comiketDetailList[1]['mlk_hachaku_shikibetu_cd'];
        
        $hachakutenFromInfo = $this->_HachakutenService->fetchHachakutenByCode($db, $hachakutenCodeFrom);
        $hachakutenToInfo = $this->_HachakutenService->fetchHachakutenByCode($db, $hachakutenCodeTo);

        if (empty($hachakutenFromInfo) || empty($hachakutenToInfo)) {
            $title = urlencode("キャンセルお申し込みができませんでした");
            $message = urlencode("発着情報が既に削除される為、キャンセルできません。");
            Sgmov_Component_Redirect::redirectPublicSsl("/mlk/error?t={$title}&m={$message}");
        }
        
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // 集荷日 チェック
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        $this->checkReqDate($param, 'キャンセル');
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        
        $outForm['hachakuten_from'] = $hachakutenFromInfo;
        $outForm['hachakuten_to'] = $hachakutenToInfo;
        
        $outForm['addressee_type_cd'] = $comiketDetailList[1]['mlk_hachaku_type_cd'];
        
        $outForm['addressee_type_nm'] = '';
        foreach ($this->address_type_lbls as $key => $val) {
            if ($outForm['addressee_type_cd'] == $key) {
                $outForm['addressee_type_nm'] = $val;
                break;
            }
        }
        $outForm['comiket_detail_delivery_date'] = '';
        $outForm['comiket_detail_delivery_date_hour'] = '';
        $outForm['comiket_detail_delivery_date_min'] = '';
        $outForm['comiket_detail_delivery_note'] = '';
        if ($outForm['addressee_type_cd'] == self::DELIVERY_TYPE_AIRPORT) {
            $outForm['comiket_detail_delivery_date'] = $comiketDetailList[1]['delivery_date'];
            $hourMin = $comiketDetailList[1]['delivery_st_time'];
            if (!empty($hourMin)) {
                $hourMinArr = explode(":", $hourMin);
                $outForm['comiket_detail_delivery_date_hour'] = $hourMinArr[0];
                $outForm['comiket_detail_delivery_date_min'] = $hourMinArr[1];
            }
            $outForm['comiket_detail_delivery_note'] = $comiketDetailList[1]['mlk_bin_nm'];
        }
        $outForm['comiket_box_name'] = '';
        $eventsubId = $comiketInfo['eventsub_id'];
        $boxId = $comiketBoxList[0]['box_id'];
        $boxList = $this->_BoxService->fetchBoxByEventsubId($db, $eventsubId);
        
        foreach ($boxList as $row) {
            if ($row['id'] == $boxId) {
                $outForm['comiket_box_name'] = $row['name'];
                break;
            }
        }
       
        
        return array(
            'ticket'  => $ticket,
            'outForm' => $outForm,
        );
    }

    /**
     * 入力フォームの値を出力フォームを生成します。
     * @param type $inForm 入力フォーム
     * @param type $db db
     * @return type
     */
    public function _createOutFormByInForm($inForm, $db=NULL) {
        $param = filter_input(INPUT_GET, 'id');
        if(!empty($param)) {
            $inForm = (array)$inForm;

            $db = Sgmov_Component_DB::getPublic();
            
            $param = intval(substr($param, 0, 10));
            
            $comiketInfo = $this->_Comiket->fetchComiketById($db, $param);
            $comikeDetailInfoList = $this->_ComiketDetail->fetchComiketDetailByComiketId($db, $param);
            
        
            // デザフェスでは詳細は１件しかない
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
            
            
            foreach ($comikeDetailInfoList as $key => $comikeDetailInfo) {
                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                // 搬入
                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                if ($comikeDetailInfo['type'] == '1') { // 往路
                    $inForm['comiket_detail_outbound_name'] = $comikeDetailInfo['name'];
                    $inForm['comiket_detail_outbound_zip1'] = @substr($comikeDetailInfo['zip'], 0,3);
                    $inForm['comiket_detail_outbound_zip2'] = substr($comikeDetailInfo['zip'], 3,7);
                    $inForm['comiket_detail_outbound_pref_cd_sel'] = $comikeDetailInfo['pref_id'];
                    $inForm['comiket_detail_outbound_address'] = $comikeDetailInfo['address'];
                    $inForm['comiket_detail_outbound_building'] = $comikeDetailInfo['building'];
                    $inForm['comiket_detail_outbound_tel'] = $comikeDetailInfo['tel'];

                    $collectDate = $comikeDetailInfo["collect_date"];
                    $inForm["comiket_detail_outbound_collect_date_year_sel"] = date('Y', strtotime($collectDate . " 00:00:00"));
                    $inForm["comiket_detail_outbound_collect_date_month_sel"] = date('m', strtotime($collectDate . " 00:00:00"));
                    $inForm["comiket_detail_outbound_collect_date_day_sel"] = date('d', strtotime($collectDate . " 00:00:00"));
                    if (@empty($comikeDetailInfo['collect_st_time'])) {
                        $inForm["comiket_detail_outbound_collect_time_sel"] = "00";
                    } else {
                        $inForm["comiket_detail_outbound_collect_time_sel"] = "{$comikeDetailInfo['collect_st_time']}-{$comikeDetailInfo['collect_ed_time']}";
                    }
                    $deliveryDate = $comikeDetailInfo["delivery_date"];
                    $inForm["comiket_detail_outbound_delivery_date_year_sel"] = date('Y', strtotime($deliveryDate . " 00:00:00"));
                    $inForm["comiket_detail_outbound_delivery_date_month_sel"] = date('m', strtotime($deliveryDate . " 00:00:00"));
                    $inForm["comiket_detail_outbound_delivery_date_day_sel"] = date('d', strtotime($deliveryDate . " 00:00:00"));
                    if (@empty($comikeDetailInfo['delivery_st_time'])) {
                        $inForm["comiket_detail_outbound_delivery_time_sel"] = "00";
                    } else {
                        $inForm["comiket_detail_outbound_delivery_time_sel"] = "{$comikeDetailInfo['delivery_st_time']}-{$comikeDetailInfo['delivery_ed_time']}";
                    }
                    $comiketBoxList = $this->_ComiketBox->fetchComiketBoxDataListByIdAndType($db, $param, $comikeDetailInfo['type']);
                    $inForm["comiket_box_outbound_num_ary"] = array();
                    foreach ($comiketBoxList as $key => $val) {
                        $inForm["comiket_box_outbound_num_ary"][$val['box_id']] = $val['num'];
                    }

                    $inForm['comiket_detail_outbound_note1'] = $comikeDetailInfo['note'];
                    $inForm['comiket_detail_outbound_service_sel'] = $comikeDetailInfo['service'];

                }

                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                // 搬出
                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                if ($comikeDetailInfo['type'] == '2') { // 復路
                    $inForm['comiket_detail_inbound_name'] = $comikeDetailInfo['name'];
                    $inForm['comiket_detail_inbound_zip1'] = @substr($comikeDetailInfo['zip'], 0,3);
                    $inForm['comiket_detail_inbound_zip2'] = substr($comikeDetailInfo['zip'], 3,7);
                    $inForm['comiket_detail_inbound_pref_cd_sel'] = $comikeDetailInfo['pref_id'];
                    $inForm['comiket_detail_inbound_address'] = $comikeDetailInfo['address'];
                    $inForm['comiket_detail_inbound_building'] = $comikeDetailInfo['building'];
                    $inForm['comiket_detail_inbound_tel'] = $comikeDetailInfo['tel'];

                    $collectDate = $comikeDetailInfo["collect_date"];
                    $inForm["comiket_detail_inbound_collect_date_year_sel"] = date('Y', strtotime($collectDate . " 00:00:00"));
                    $inForm["comiket_detail_inbound_collect_date_month_sel"] = date('m', strtotime($collectDate . " 00:00:00"));
                    $inForm["comiket_detail_inbound_collect_date_day_sel"] = date('d', strtotime($collectDate . " 00:00:00"));
                    if (@empty($comikeDetailInfo['collect_st_time'])) {
                        $inForm["comiket_detail_inbound_collect_time_sel"] = "00";
                    } else {
                        $inForm["comiket_detail_inbound_collect_time_sel"] = "{$comikeDetailInfo['collect_st_time']}-{$comikeDetailInfo['collect_ed_time']}";
                    }

                    $deliverytDate = $comikeDetailInfo["delivery_date"];
                    $inForm["comiket_detail_inbound_delivery_date_year_sel"] = date('Y', strtotime($deliverytDate . " 00:00:00"));
                    $inForm["comiket_detail_inbound_delivery_date_month_sel"] = date('m', strtotime($deliverytDate . " 00:00:00"));
                    $inForm["comiket_detail_inbound_delivery_date_day_sel"] = date('d', strtotime($deliverytDate . " 00:00:00"));
                    if (@empty($comikeDetailInfo['delivery_st_time'])) {
                        $inForm["comiket_detail_inbound_delivery_time_sel"] = "{$comikeDetailInfo['delivery_timezone_cd']},{$comikeDetailInfo['delivery_timezone_name']}";
                    } else {
                        $inForm["comiket_detail_inbound_delivery_time_sel"] = "{$comikeDetailInfo['delivery_st_time']}-{$comikeDetailInfo['delivery_ed_time']}";
                    }

                    $comiketBoxList = $this->_ComiketBox->fetchComiketBoxDataListByIdAndType($db, $param, $comikeDetailInfo['type']);
                    $inForm["comiket_box_inbound_num_ary"] = array();
                    foreach ($comiketBoxList as $key => $val) {
                        $inForm["comiket_box_inbound_num_ary"][$val['box_id']] = $val['num'];
                    }

                    $inForm['comiket_detail_inbound_note1'] = $comikeDetailInfo['note'];
                    $inForm['comiket_detail_inbound_service_sel'] = $comikeDetailInfo['service'];
                }
            }

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // お支払い情報
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////            
            $inForm["comiket_payment_method_cd_sel"] = $comiketInfo['payment_method_cd'];
            $inForm["comiket_convenience_store_cd_sel"] = $comiketInfo['convenience_store_cd'];
            
        }
        
        // 搬入出の申込期間チェック
        //$this->checkCurrentDateWithInTerm((array)$inForm);
        
        $eve001Out = $this->createOutFormByInForm($inForm, new Sgmov_Form_Eve001Out());
        
        $dispItemInfo = $eve001Out["dispItemInfo"];
        
        $comiketInfo = $this->_Comiket->fetchComiketById($db, $param);
        $dispItemInfo['amount_tax'] = $comiketInfo['amount_tax'];
        
        $eve001Out["dispItemInfo"] = $dispItemInfo;
        return $eve001Out;
    }
    private function getNameSelectBox($cds, $lbls, $select) {
        if (empty($select)) {
            return "";
        }
        $count = count($cds);
        for ($i = 0; $i < $count; ++$i) {
            if ($select == $cds[$i]) {
                return $lbls[$i];
            }
        }
        return "";
    }
    
    
    private function fetchDataOutForm(&$outForm, $dispItemInfo) {
        $outForm->addressee_type_name = $this->getNameSelectBox($outForm->raw_addressee_type_cds, $outForm->raw_addressee_type_lbls, $outForm->raw_addressee_type_sel);
        
        $outForm->hotel_service_airport_name = '';
        if ($outForm->raw_addressee_type_sel == self::DELIVERY_TYPE_AIRPORT) {
            $outForm->hotel_service_airport_name = $this->getNameSelectBox($outForm->raw_airport_cds, $outForm->raw_airport_lbls, $outForm->raw_airport_sel);
        } else if ($outForm->raw_addressee_type_sel == self::DELIVERY_TYPE_SERVICE) {
            $outForm->hotel_service_airport_name = $this->getNameSelectBox($outForm->raw_sevice_center_cds, $outForm->raw_sevice_center_lbls, $outForm->raw_sevice_center_sel);
        } else if ($outForm->raw_addressee_type_sel == self::DELIVERY_TYPE_HOTEL) {
            $outForm->hotel_service_airport_name = $this->getNameSelectBox($outForm->raw_hotel_cds, $outForm->raw_hotel_lbls, $outForm->raw_hotel_sel);
        }
        $outForm->comiket_box_name = '';
        $boxLbls = $dispItemInfo['box_lbls'];
        
        
        $boxIds = array_keys($outForm->raw_comiket_box_inbound_num_ary);

        foreach ($boxLbls as $item) {
            if ($item['cd'] == $boxIds[0]) {
                $outForm->comiket_box_name = $item['name'];
            }
        }

    }
    

}