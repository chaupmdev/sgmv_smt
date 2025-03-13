<?php
/**
 * @package    ClassDefFile
 * @author     自動生成
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**
 * 概算見積入力画面の入力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Eve001In {

    /**
     * コミケ申込ID
     * @var string
     */
    public $comiket_id = '';

    /**
     * 識別(法人/個人)
     * @var string
     */
    public $comiket_div = '';

    /**
     * 出展イベント
     * @var string
     */
    public $event_sel = '';

    /**
     * 出展イベントサブ
     * @var string
     */
    public $eventsub_sel = '';

    /**
     * 場所
     * @var string
     */
    public $event_place = '';

    /**
     * 期間 start
     * @var string
     */
    public $eventsub_term_fr = '';

    /**
     * 期間 end
     * @var string
     */
    public $eventsub_term_to = '';

    /**
     * 顧客コード 使用選択
     * @var string
     */
    public $comiket_customer_cd_sel = '';

    /**
     * 顧客コード
     * @var string
     */
    public $comiket_customer_cd = '';

    /**
     * 顧客名 （法人用）
     * @var string
     */
    public $office_name = '';

    /**
     * 顧客名 姓 （個人用）
     * @var string
     */
    public $comiket_personal_name_sei = '';

    /**
     * 顧客名 名（個人用）
     * @var string
     */
    public $comiket_personal_name_mei = '';

    /**
     * 郵便番号1
     * @var string
     */
    public $comiket_zip1 = '';

    /**
     * 郵便番号2
     * @var string
     */
    public $comiket_zip2 = '';

    /**
     * 都道府県
     * @var string
     */
    public $comiket_pref_cd_sel = '';

    /**
     * 市区町村
     * @var string
     */
    public $comiket_address = '';

    /**
     * 番地・建物名
     * @var string
     */
    public $comiket_building = '';

    /**
     * 電話番号
     * @var string
     */
    public $comiket_tel = '';

    /**
     * メールアドレス
     * @var string
     */
    public $comiket_mail = '';

    /**
     * メールアドレス確認
     * @var string
     */
    public $comiket_mail_retype = '';

    /**
     * ブース名
     * @var type
     */
    public $comiket_booth_name = '';

    /**
     * 館名 プルダウン
     * @var string
     */
    public $building_name_sel = '';

    /**
     * 館名 ラベル
     * @var string
     */
    public $building_name = '';

    /**
     * 地区 プルダウン
     * @var string
     */
    public $building_cd_and_name_sel = '';

    /**
     * 館位置 プルダウン
     * @var string
     */
    public $building_booth_position_sel = '';

    /**
     * ブース位置 ラベル
     * @var string
     */
    public $building_booth_position = '';

    /**
     * ブース番号 テキスト
     * @var string
     */
    public $comiket_booth_num = '';
    
    /**
     * ブース番号 テキスト
     * @var string
     */
    public $comiket_booth_addition_num = '';

    /**
     * スタッフ姓 テキスト
     * @var string
     */
    public $comiket_staff_sei = '';

    /**
     * スタッフ名 テキスト
     * @var string
     */
    public $comiket_staff_mei = '';

    /**
     * スタッフ姓 フリガナ テキスト
     * @var string
     */
    public $comiket_staff_sei_furi = '';

    /**
     * スタッフ名 フリガナ テキスト
     * @var string
     */
    public $comiket_staff_mei_furi = '';

    /**
     * ブース番号 テキスト
     * @var string
     */
    public $comiket_staff_tel = '';

    /**
     * 往復選択
     * @var string
     */
    public $comiket_detail_type_sel = '';

//////////////////////////////////////////////////////////////////////////////////////////////
// 搬入
//////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * 搬入-集荷先名
     * @var string
     */
    public $comiket_detail_outbound_name = '';
    
    /**
     * 搬入-集荷先名-姓
     * @var string
     */
    public $comiket_detail_outbound_name_sei = '';
    
    /**
     * 搬入-集荷先名-名
     * @var string
     */
    public $comiket_detail_outbound_name_mei = '';

    /**
     * 搬入-集荷先郵便番号1
     * @var string
     */
    public $comiket_detail_outbound_zip1 = '';

    /**
     * 搬入-集荷先郵便番号2
     * @var string
     */
    public $comiket_detail_outbound_zip2 = '';

    /**
     * 搬入-集荷先都道府県
     * @var string
     */
    public $comiket_detail_outbound_pref_cd_sel = '';

    /**
     * 搬入-集荷先市区町村
     * @var string
     */
    public $comiket_detail_outbound_address = '';

    /**
     * 搬入-集荷先番地・建物名
     * @var string
     */
    public $comiket_detail_outbound_building = '';

    /**
     * 搬入-集荷先TEL
     * @var string
     */
    public $comiket_detail_outbound_tel = '';

    /**
     * 搬入-お預かり日時-年
     * @var string
     */
    public $comiket_detail_outbound_collect_date_year_sel = '';

    /**
     * 搬入-お預かり日時-月
     * @var string
     */
    public $comiket_detail_outbound_collect_date_month_sel = '';

    /**
     * 搬入-お預かり日時-日
     * @var string
     */
    public $comiket_detail_outbound_collect_date_day_sel = '';

    /**
     * 搬入-お預かり日時-時間帯
     * @var string
     */
    public $comiket_detail_outbound_collect_time_sel = '';

    /**
     * 搬入-お届け日時-年
     * @var string
     */
    public $comiket_detail_outbound_delivery_date_year_sel = '';

    /**
     * 搬入-お届け日時-月
     * @var string
     */
    public $comiket_detail_outbound_delivery_date_month_sel = '';

    /**
     * 搬入-お届け日時-日
     * @var string
     */
    public $comiket_detail_outbound_delivery_date_day_sel = '';

    /**
     * 搬入-お届け日時-時間帯
     * @var string
     */
    public $comiket_detail_outbound_delivery_time_sel = '';

    /**
     * 搬入-サービス選択
     * @var string
     */
    public $comiket_detail_outbound_service_sel = '';

    /**
     * 搬入-宅配数量
     * @var string
     */
    public $comiket_box_outbound_num_ary = array();

    /**
     * 搬入-カーゴ選択値
     * @var string
     */
    public $comiket_cargo_outbound_num_sel = '';

    /**
     * 搬入-カーゴ数量
     * @var string
     */
//    public $comiket_cargo_outbound_num_ary = array();

    /**
     * 搬入-台数貸切
     * @var string
     */
    public $comiket_charter_outbound_num_ary = array();

    /**
     * 搬入-備考
     * @var string
     */
    public $comiket_detail_outbound_note = '';

    /**
     * 搬入-備考-1行目
     * @var string
     */
    public $comiket_detail_outbound_note1 = '';
   
    //GiapLN add 梱包ガイドライン 2022.07.13
    public $comiket_detail_caremark_flg = '';
    public $comiket_detail_caremark_flg_enable = '';
    
    /**
     * 搬入-備考-2行目
     * @var string
     */
    public $comiket_detail_outbound_note2 = '';

    /**
     * 搬入-備考-3行目
     * @var string
     */
    public $comiket_detail_outbound_note3 = '';

    /**
     * 搬入-備考-4行目
     * @var string
     */
    public $comiket_detail_outbound_note4 = '';

//////////////////////////////////////////////////////////////////////////////////////////////
// 復路
//////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * 復路-配送先名
     * @var string
     */
    public $comiket_detail_inbound_id = '';

    /**
     * 復路-配送先名
     * @var string
     */
    public $comiket_detail_inbound_name = '';

    /**
     * 復路-配送先郵便番号1
     * @var string
     */
    public $comiket_detail_inbound_zip1 = '';

    /**
     * 復路-配送先郵便番号2
     * @var string
     */
    public $comiket_detail_inbound_zip2 = '';

    /**
     * 復路-配送先都道府県
     * @var string
     */
    public $comiket_detail_inbound_pref_cd_sel = '';

    /**
     * 復路-配送先市区町村
     * @var string
     */
    public $comiket_detail_inbound_address = '';

    /**
     * 復路-配送先番地・建物名
     * @var string
     */
    public $comiket_detail_inbound_building = '';

    /**
     * 復路-配送先TEL
     * @var string
     */
    public $comiket_detail_inbound_tel = '';

    /**
     * 復路-お預かり日時-年
     * @var string
     */
    public $comiket_detail_inbound_collect_date_year_sel = '';

    /**
     * 復路-お預かり日時-月
     * @var string
     */
    public $comiket_detail_inbound_collect_date_month_sel = '';

    /**
     * 復路-お預かり日時-日
     * @var string
     */
    public $comiket_detail_inbound_collect_date_day_sel = '';

    /**
     * 復路-お預かり日時-時間帯
     * @var string
     */
    public $comiket_detail_inbound_collect_time_sel = '';

    /**
     * 復路-お届け日時-年
     * @var string
     */
    public $comiket_detail_inbound_delivery_date_year_sel = '';

    /**
     * 復路-お届け日時-月
     * @var string
     */
    public $comiket_detail_inbound_delivery_date_month_sel = '';

    /**
     * 復路-お届け日時-日
     * @var string
     */
    public $comiket_detail_inbound_delivery_date_day_sel = '';

    /**
     * 復路-お届け日時-時間帯
     * @var string
     */
    public $comiket_detail_inbound_delivery_time_sel = '';

    /**
     * 復路-サービス選択
     * @var string
     */
    public $comiket_detail_inbound_service_sel = '';

    /**
     * 復路-宅配数量
     * @var string
     */
    public $comiket_box_inbound_num_ary = array();

    /**
     * 復路-カーゴ選択値
     * @var string
     */
    public $comiket_cargo_inbound_num_sel = '';

    /**
     * 復路-カーゴ数量
     * @var string
     */
//    public $comiket_cargo_inbound_num_ary = array();

    //GiapLN add 梱包ガイドライン 2022.07.13
    public $comiket_detail_caremark_flg_inbound = '';
    public $comiket_detail_caremark_flg_enable_inbound = '';
    /**
     * 復路-台数貸切
     * @var string
     */
    public $comiket_charter_inbound_num_ary = array();

    /**
     * 復路-備考
     * @var string
     */
    public $comiket_detail_inbound_note = '';

    /**
     * 復路-備考-1行目
     * @var string
     */
    public $comiket_detail_inbound_note1 = '';

    /**
     * 復路-備考-2行目
     * @var string
     */
    public $comiket_detail_inbound_note2 = '';

    /**
     * 復路-備考-3行目
     * @var string
     */
    public $comiket_detail_inbound_note3 = '';

    /**
     * 復路-備考-4行目
     * @var string
     */
    public $comiket_detail_inbound_note4 = '';

    /**
     *
     * @var type
     */
    public $input_mode = '';

    /**
     * 復路-お届け可能日（開始）
     * @var String
     */
    public $hid_comiket_detail_inbound_delivery_date_from = '';

    /**
     * 復路-お届け可能日（終了）
     * @var String
     */
    public $hid_comiket_detail_inbound_delivery_date_to = '';

    /**
     * 往路-お預かり可能日（開始）
     * @var String
     */
    public $hid_comiket_detail_outbound_collect_date_from = '';

    /**
     * 往路-お預かり可能日（終了）
     * @var String
     */
    public $hid_comiket_detail_outbound_collect_date_to = '';
    
    ////////////////////////////////////////////////////////////////////////////////
    // ホテル対応用
    ////////////////////////////////////////////////////////////////////////////////
    
    public $comiket_detail_inbound_delivery_date_cd_sel = '';
    
    public $parcel_room = '';
    
    public $is_conf_rule = false;
    
    ////////////////////////////////////////////////////////////////////////////////
    
    //////////////////////////////////////////////////////////////////////////
    // ふるさと祭り対応
    //////////////////////////////////////////////////////////////////////////
    
    /**
     * 顧客区分
     * @var type 
     */
    public $comiket_customer_kbn_sel = '';
    
    /**
     * 便種区分 - 往路
     * @var type 
     */
    public $comiket_detail_outbound_binshu_kbn_sel = '';
    
    /**
     * 便種区分 - 復路
     * @var type 
     */
    public $comiket_detail_inbound_binshu_kbn_sel = '';
    
    ////////////////////////////////////////////////////////////////////////////////
    
    ////////////////////////////////////////////////////////////////////////////////
    // 荷物預り
    ////////////////////////////////////////////////////////////////////////////////
    /**
     * 配送or預り選択
     * @var type 
     */
    public $comiket_detail_service_selected = '';
    
    /**
     * 取出回数種別
     * @var type 
     */
    public $comiket_detail_azukari_kaisu_type_sel = '';
    
    /**
     * 預り場所
     */
    public $comiket_detail_azukari_basho_sel = '';
    
    /**
     * 出発地コード選択値
     * @var string
     */
    public $travel_departure_cd_sel = '';
    
    /**
     * 都道府県
     * @var string
     */
    public $comiket_booth_cd_sel = '';
    
    /**
     * 搬入-宅配数量
     * @var string
     */
    public $comiket_box_num = '';
    /**
     * 集荷希望年コード選択値
     * @var string
     */
    public $cargo_collection_date_year_cd_sel = '';

    /**
     * 集荷希望月コード選択値
     * @var string
     */
    public $cargo_collection_date_month_cd_sel = '';

    /**
     * 集荷希望日コード選択値
     * @var string
     */
    public $cargo_collection_date_day_cd_sel = '';

    /**
     * 集荷希望開始時刻コード選択値
     * @var string
     */
    public $cargo_collection_st_time_cd_sel = '';

    /**
     * 集荷希望終了時刻コード選択値
     * @var string
     */
    public $cargo_collection_ed_time_cd_sel = '';
    
    /**
     * 集荷希望年コード選択値
     * @var string
     */
    public $delivery_collection_date_year_cd_sel = '';

    /**
     * 集荷希望月コード選択値
     * @var string
     */
    public $delivery_collection_date_month_cd_sel = '';

    /**
     * 集荷希望日コード選択値
     * @var string
     */
    public $delivery_collection_date_day_cd_sel = '';

    /**
     * お届け先の選択
     * @var string
     */
    public $addressee_type_sel = '';
    
    
    public $sevice_center_sel = '';
    public $hotel_sel = '';
    public $airport_sel = '';
    
    public $comiket_detail_delivery_date_min = '';
    public $comiket_detail_delivery_date_hour = '';
    public $comiket_detail_delivery_date = '';
    
}