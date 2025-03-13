<?php
/**
 * @package    ClassDefFile
 * @author     自動生成
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useComponents(array('String'));
/**#@-*/

/**
 * 入力画面の出力フォームです。
 * TODO TwigやSmartyなどのテンプレートエンジンの導入を検討する
 * @package    Form
 * @author     自動生成
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Alp001Out {

    public $raw_comiket_id = "";

    /**
     * 識別選択値(法人/個人)
     * @var string
     */
    public $raw_comiket_div = '';

    /**
     * イベントコード選択値
     * @var string
     */
    public $raw_event_cd_sel = '';

    /**
     * イベントコードリスト
     * @var array
     */
    public $raw_event_cds = array();

    /**
     * イベントコードラベルリスト
     * @var array
     */
    public $raw_event_lbls = array();

    /**
     * イベントサブコード選択値
     * @var string
     */
    public $raw_eventsub_cd_sel = '';

    /**
     * イベントコードリスト
     * @var array
     */
    public $raw_eventsub_cds = array();

    /**
     * イベントコードラベルリスト
     * @var array
     */
    public $raw_eventsub_lbls = array();

    /**
     * イベント郵便番号
     * @var array
     */
    public $raw_eventsub_zip = '';

    /**
     * イベント場所
     * @var array
     */
    public $raw_eventsub_address = '';

    /**
     * イベント期間From - YYYY-mm-dd 00:00:00
     * @var array
     */
    public $raw_eventsub_term_fr = '';

    /**
     * イベント期間From - YYYY年mm月dd日
     * @var array
     */
    public $raw_eventsub_term_fr_nm = '';

    /**
     * イベント期間To- YYYY-mm-dd 00:00:00
     * @var array
     */
    public $raw_eventsub_term_to = '';

    /**
     * イベント期間To - YYYY年mm月dd日
     * @var array
     */
    public $raw_eventsub_term_to_nm = '';

    /**
     * サービス選択 
     * @var array
     */
     public $raw_comiket_detail_service_selected = "";

    /**
     * 顧客コード 使用する/しない
     */
    public $raw_comiket_customer_cd_sel = '';

    /**
     * 顧客コード
     * @var array
     */
    public $raw_comiket_customer_cd = '';

    /**
     * 顧客名 （法人用）
     * @var array
     */
    public $raw_office_name = '';

    /**
     * 顧客名 姓（個人用）
     * @var array
     */
    public $raw_comiket_personal_name_sei = '';

    /**
     * 顧客名 名（個人用）
     * @var array
     */
    public $raw_comiket_personal_name_mei = '';

    /**
     * 郵便番号1
     * @var array
     */
    public $raw_comiket_zip1 = '';

    /**
     * 郵便番号2
     * @var array
     */
    public $raw_comiket_zip2 = '';

    /**
     * 都道府県コード選択値
     * @var string
     */
    public $raw_comiket_pref_cd_sel = '';

    /**
     * 都道府県コードリスト
     * @var array
     */
    public $raw_comiket_pref_cds = array();

    /**
     * 都道府県コードラベルリスト
     * @var array
     */
    public $raw_comiket_pref_lbls = array();

    /**
     * 都道府県コードラベルリスト
     * @var array
     */
    public $raw_comiket_pref_nm = '';

    /**
     * 市区町村
     * @var string
     */
    public $raw_comiket_address = '';

    /**
     * 番地・建物名
     * @var string
     */
    public $raw_comiket_building = '';

    /**
     * 電話番号
     * @var string
     */
    public $raw_comiket_tel = '';

    /**
     * メールアドレス
     * @var string
     */
    public $raw_comiket_mail = '';

    /**
     * メールアドレス確認
     * @var string
     */
    public $raw_comiket_mail_retype = '';

    /**
     * ブース名
     * @var type
     */
    public $raw_comiket_booth_name = '';

    /**
     * 館名 ラベル用
     * @var string
     */
    public $raw_building_name = '';

    /**
     * 館名
     * @var string
     */
//    public $raw_comiket_booth_name = '';
    public $raw_building_name_sel = '';

    /**
     * 館名-idリスト
     * @var string
     */
    public $raw_building_name_ids = array();

    /**
     * 館名-namesリスト
     * @var string
     */
    public $raw_building_name_lbls = array();

    /**
     * 地区（現在のところ、使用するイベントはコミケアピールのみ）
     * @var string
     */
    public $raw_building_cd_and_name_sel = '';

    /**
     * ブース位置 ラベル
     * @var string
     */
    public $raw_building_booth_position = '';

    /**
     * ブース位置
     * @var string
     */
    public $raw_building_booth_position_sel = '';

    /**
     * ブース位置-idリスト
     * @var string
     */
    public $raw_building_booth_position_ids = '';

    /**
     * ブース位置-nameリスト
     * @var string
     */
    public $raw_building_booth_position_lbls = '';
//
//    /**
//     * コミケブースId選択値
//     * @var string
//     */
//    public $raw_building_booth_id_sel = '';
//
//    /**
//     * コミケブースIdリスト
//     * @var array
//     */
//    public $raw_building_booth_ids = array();
//
//    /**
//     * コミケブースIdラベルリスト
//     * @var array
//     */
//    public $raw_building_booth_lbls = array();

    /**
     * コミケブース番号
     * @var string
     */
    public $raw_comiket_booth_num = '';

    /**
     * 担当者名 姓
     * @var string
     */
    public $raw_comiket_staff_sei = '';

    /**
     * 担当者名 名
     * @var string
     */
    public $raw_comiket_staff_mei = '';

    /**
     * 担当者名 姓 フリガナ
     * @var string
     */
    public $raw_comiket_staff_sei_furi = '';

    /**
     * 担当者名 名 フリガナ
     * @var string
     */
    public $raw_comiket_staff_mei_furi = '';

    /**
     * 担当者電話番号
     * @var string
     */
    public $raw_comiket_staff_tel = '';

    /**
     * コミケ搬入区分
     * @var string
     */
    public $raw_comiket_detail_type_sel = '';

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 搬入
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * 搬入-集荷先名
     * @var string
     */
    public $raw_comiket_detail_outbound_name = '';
    
    /**
     * 搬入-集荷先名-姓
     * @var string
     */
    public $raw_comiket_detail_outbound_name_sei = '';
    
    /**
     * 搬入-集荷先名-名
     * @var string
     */
    public $raw_comiket_detail_outbound_name_mei = '';

    /**
     * 搬入-集荷先郵便番号1
     * @var string
     */
    public $raw_comiket_detail_outbound_zip1 = '';

    /**
     * 搬入-集荷先郵便番号2
     * @var string
     */
    public $raw_comiket_detail_outbound_zip2 = '';

    /**
     * 搬入-集荷先都道府県コード選択値
     * @var string
     */
    public $raw_comiket_detail_outbound_pref_cd_sel = '';

    /**
     * 搬入-集荷先都道府県コードリスト
     * @var array
     */
    public $raw_comiket_detail_outbound_pref_cds = array();

    /**
     * 搬入-集荷先都道府県コードラベルリスト
     * @var array
     */
    public $raw_comiket_detail_outbound_pref_lbls = array();

    /**
     * 搬入-集荷先市区町村
     * @var string
     */
    public $raw_comiket_detail_outbound_address = '';

    /**
     * 搬入-集荷先番地・建物名
     * @var string
     */
    public $raw_comiket_detail_outbound_building = '';

    /**
     * 搬入-集荷先TEL
     * @var string
     */
    public $raw_comiket_detail_outbound_tel = '';

    /**
     * 搬入-お預り日時-年 選択値
     * @var string
     */
    public $raw_comiket_detail_outbound_collect_date_year_sel = '';

    /**
     * 搬入-お預り日時-年 リスト
     * @var array
     */
    public $raw_comiket_detail_outbound_collect_date_year_cds = array();

    /**
     * 搬入-お預り日時-年 ラベルリスト
     * @var array
     */
    public $raw_comiket_detail_outbound_collect_date_year_lbls = array();

    /**
     * 搬入-お預り日時-月 選択値
     * @var string
     */
    public $raw_comiket_detail_outbound_collect_date_month_sel = '';

    /**
     * 搬入-お預り日時-月 リスト
     * @var array
     */
    public $raw_comiket_detail_outbound_collect_date_month_cds = array();

    /**
     * 搬入-お預り日時-月 ラベルリスト
     * @var array
     */
    public $raw_comiket_detail_outbound_collect_date_month_lbls = array();

    /**
     * 搬入-お預り日時-日 選択値
     * @var string
     */
    public $raw_comiket_detail_outbound_collect_date_day_sel = '';

    /**
     * 搬入-お預り日時-日 リスト
     * @var array
     */
    public $raw_comiket_detail_outbound_collect_date_day_cds = array();

    /**
     * 搬入-お預り日時-日 ラベルリスト
     * @var array
     */
    public $raw_comiket_detail_outbound_collect_date_day_lbls = array();

    /**
     * 搬入-お預り日時-時間帯 選択値
     * @var string
     */
    public $raw_comiket_detail_outbound_collect_time_sel = '';

    /**
     * 搬入-お預り日時-時間帯 リスト
     * @var array
     */
    public $raw_comiket_detail_outbound_collect_time_cds = array();

    /**
     * 搬入-お預り日時-時間帯 ラベルリスト
     * @var array
     */
    public $raw_comiket_detail_outbound_collect_time_lbls = array();

    /**
     * 搬入-お届け日時-年 選択値
     * @var string
     */
    public $raw_comiket_detail_outbound_delivery_date_year_sel = '';

    /**
     * 搬入-お届け日時-年 リスト
     * @var array
     */
    public $raw_comiket_detail_outbound_delivery_date_year_cds = array();

    /**
     * 搬入-お届け日時-年 ラベルリスト
     * @var array
     */
    public $raw_comiket_detail_outbound_delivery_date_year_lbls = array();

    /**
     * 搬入-お届け日時-月 選択値
     * @var string
     */
    public $raw_comiket_detail_outbound_delivery_date_month_sel = '';

    /**
     * 搬入-お届け日時-月 リスト
     * @var array
     */
    public $raw_comiket_detail_outbound_delivery_date_month_cds = array();

    /**
     * 搬入-お届け日時-月 ラベルリスト
     * @var array
     */
    public $raw_comiket_detail_outbound_delivery_date_month_lbls = array();

    /**
     * 搬入-お届け日時-日 選択値
     * @var string
     */
    public $raw_comiket_detail_outbound_delivery_date_day_sel = '';

    /**
     * 搬入-お届け日時-日 リスト
     * @var array
     */
    public $raw_comiket_detail_outbound_delivery_date_day_cds = array();

    /**
     * 搬入-お届け日時-日 ラベルリスト
     * @var array
     */
    public $raw_comiket_detail_outbound_delivery_date_day_lbls = array();

    /**
     * 搬入-お届け日時-時間帯 選択値
     * @var string
     */
    public $raw_comiket_detail_outbound_delivery_time_sel = '';

    /**
     * 搬入-お届け日時-時間帯 リスト
     * @var array
     */
    public $raw_comiket_detail_outbound_delivery_time_cds = array();

    /**
     * 搬入-お届け日時-時間帯 ラベルリスト
     * @var array
     */
    public $raw_comiket_detail_outbound_delivery_time_lbls = array();

    /**
     * 搬入-サービス選択区分
     * @var string
     */
    public $raw_comiket_detail_outbound_service_sel = '';

    /**
     * 搬入-宅配数量
     * @var string
     */
    public $raw_comiket_box_outbound_num_ary = array();

    /**
     * 搬入-カーゴ選択
     * @var string
     */
    public $raw_comiket_cargo_outbound_num_sel = '';

    /**
     * 搬入-カーゴCds
     * @var string
     */
    public $raw_comiket_cargo_outbound_num_cds = array();

    /**
     * 搬入-カーゴラベル
     * @var string
     */
    public $raw_comiket_cargo_outbound_num_lbls = array();

    /**
     * 搬入-カーゴ数量
     * @var string
     */
//    public $raw_comiket_cargo_outbound_num_ary = array();

    /**
     * 搬入-貸切台数
     * @var string
     */
    public $raw_comiket_charter_outbound_num_ary = array();

    /**
     * 搬入-備考
     * @var string
     */
    public $raw_comiket_detail_outbound_note = '';

    /**
     * 搬入-備考-1行目
     * @var string
     */
    public $raw_comiket_detail_outbound_note1 = '';

    /**
     * 搬入-備考-2行目
     * @var string
     */
    public $raw_comiket_detail_outbound_note2 = '';

    /**
     * 搬入-備考-3行目
     * @var string
     */
    public $raw_comiket_detail_outbound_note3 = '';

    /**
     * 搬入-備考-4行目
     * @var string
     */
    public $raw_comiket_detail_outbound_note4 = '';


/////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 復路
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * 復路-配送先名
     * @var string
     */
    public $raw_comiket_detail_inbound_name = '';

    /**
     * 復路-配送先郵便番号1
     * @var string
     */
    public $raw_comiket_detail_inbound_zip1 = '';

    /**
     * 復路-配送先郵便番号2
     * @var string
     */
    public $raw_comiket_detail_inbound_zip2 = '';

    /**
     * 復路-配送先都道府県コード選択値
     * @var string
     */
    public $raw_comiket_detail_inbound_pref_cd_sel = '';

    /**
     * 復路-配送先都道府県コードリスト
     * @var array
     */
    public $raw_comiket_detail_inbound_pref_cds = array();

    /**
     * 復路-配送先都道府県コードラベルリスト
     * @var array
     */
    public $raw_comiket_detail_inbound_pref_lbls = array();

    /**
     * 復路-配送先市区町村
     * @var string
     */
    public $raw_comiket_detail_inbound_address = '';

    /**
     * 復路-配送先番地・建物名
     * @var string
     */
    public $raw_comiket_detail_inbound_building = '';

    /**
     * 復路-配送先TEL
     * @var string
     */
    public $raw_comiket_detail_inbound_tel = '';

    /**
     * 復路-お預り日時-年 選択値
     * @var string
     */
    public $raw_comiket_detail_inbound_collect_date_year_sel = '';

    /**
     * 復路-お預り日時-年 リスト
     * @var array
     */
    public $raw_comiket_detail_inbound_collect_date_year_cds = array();

    /**
     * 復路-お預り日時-年 ラベルリスト
     * @var array
     */
    public $raw_comiket_detail_inbound_collect_date_year_lbls = array();

    /**
     * 復路-お預り日時-月 選択値
     * @var string
     */
    public $raw_comiket_detail_inbound_collect_date_month_sel = '';

    /**
     * 復路-お預り日時-月 リスト
     * @var array
     */
    public $raw_comiket_detail_inbound_collect_date_month_cds = array();

    /**
     * 復路-お預り日時-月 ラベルリスト
     * @var array
     */
    public $raw_comiket_detail_inbound_collect_date_month_lbls = array();

    /**
     * 復路-お預り日時-日 選択値
     * @var string
     */
    public $raw_comiket_detail_inbound_collect_date_day_sel = '';

    /**
     * 復路-お預り日時-日 リスト
     * @var array
     */
    public $raw_comiket_detail_inbound_collect_date_day_cds = array();

    /**
     * 復路-お預り日時-日 ラベルリスト
     * @var array
     */
    public $raw_comiket_detail_inbound_collect_date_day_lbls = array();

    /**
     * 復路-お預り日時-時間帯 選択値
     * @var string
     */
    public $raw_comiket_detail_inbound_collect_time_sel = '';

    /**
     * 復路-お預り日時-時間帯 リスト
     * @var array
     */
    public $raw_comiket_detail_inbound_collect_time_cds = array();

    /**
     * 復路-お預り日時-時間帯 ラベルリスト
     * @var array
     */
    public $raw_comiket_detail_inbound_collect_time_lbls = array();

    /**
     * 復路-お届け日時-年 選択値
     * @var string
     */
    public $raw_comiket_detail_inbound_delivery_date_year_sel = '';

    /**
     * 復路-お届け日時-年 リスト
     * @var array
     */
    public $raw_comiket_detail_inbound_delivery_date_year_cds = array();

    /**
     * 復路-お届け日時-年 ラベルリスト
     * @var array
     */
    public $raw_comiket_detail_inbound_delivery_date_year_lbls = array();

    /**
     * 復路-お届け日時-月 選択値
     * @var string
     */
    public $raw_comiket_detail_inbound_delivery_date_month_sel = '';

    /**
     * 復路-お届け日時-月 リスト
     * @var array
     */
    public $raw_comiket_detail_inbound_delivery_date_month_cds = array();

    /**
     * 復路-お届け日時-月 ラベルリスト
     * @var array
     */
    public $raw_comiket_detail_inbound_delivery_date_month_lbls = array();

    /**
     * 復路-お届け日時-日 選択値
     * @var string
     */
    public $raw_comiket_detail_inbound_delivery_date_day_sel = '';

    /**
     * 復路-お届け日時-日 リスト
     * @var array
     */
    public $raw_comiket_detail_inbound_delivery_date_day_cds = array();

    /**
     * 復路-お届け日時-日 ラベルリスト
     * @var array
     */
    public $raw_comiket_detail_inbound_delivery_date_day_lbls = array();

    /**
     * 復路-お届け日時-時間帯 選択値
     * @var string
     */
    public $raw_comiket_detail_inbound_delivery_time_sel = '';

    /**
     * 復路-お届け日時-時間帯 リスト
     * @var array
     */
    public $raw_comiket_detail_inbound_delivery_time_cds = array();

    /**
     * 復路-お届け日時-時間帯 ラベルリスト
     * @var array
     */
    public $raw_comiket_detail_inbound_delivery_time_lbls = array();

    /**
     * 復路-サービス選択区分
     * @var string
     */
    public $raw_comiket_detail_inbound_service_sel = '';

    /**
     * 復路-宅配数量
     * @var string
     */
    public $raw_comiket_box_inbound_num_ary = array();

    /**
     * 復路-カーゴ選択
     * @var string
     */
    public $raw_comiket_cargo_inbound_num_sel = "";

    /**
     * 復路-カーゴCds
     * @var string
     */
    public $raw_comiket_cargo_inbound_num_cds = array();

    /**
     * 復路-カーゴラベル
     * @var string
     */
    public $raw_comiket_cargo_inbound_num_lbls = array();

    /**
     * 復路-カーゴ数量
     * @var string
     */
//    public $raw_comiket_cargo_inbound_num_ary = array();

    /**
     * 復路-貸切台数
     * @var string
     */
    public $raw_comiket_charter_inbound_num_ary = array();

    /**
     * 復路-備考
     * @var string
     */
    public $raw_comiket_detail_inbound_note = '';

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


////////////////////////////////////////////////////////////////////////////////
// 支払
////////////////////////////////////////////////////////////////////////////////


    /**
     * お支払方法コード選択値
     * @var string
     */
    public $raw_comiket_payment_method_cd_sel = '';

    /**
     * お支払店コード選択値
     * @var string
     */
    public $raw_comiket_convenience_store_cd_sel = '';

    /**
     * お支払店コードリスト
     * @var array
     */
    public $raw_comiket_convenience_store_cds = array();

    /**
     * お支払店コードラベルリスト
     * @var array
     */
    public $raw_comiket_convenience_store_lbls = array();

    /**
     * クレジットカード番号
     * @var string
     */
    public $raw_card_number = '';

    /**
     * 有効期限 月
     * @var string
     */
    public $raw_card_expire_month_cd_sel = '';

    /**
     * 有効期限 年
     * @var string
     */
    public $raw_card_expire_year_cd_sel = '';

    /**
     * セキュリティコード
     * @var string
     */
    public $raw_security_cd = '';

////////////////////////////////////////////////////////////////////////////////
// 入力モード
////////////////////////////////////////////////////////////////////////////////

    /**
     *
     * @var type
     */
    public $raw_input_mode = '';


////////////////////////////////////////////////////////////////////////////////
// 隠し項目
////////////////////////////////////////////////////////////////////////////////

    /**
     * イベント受付終了時間超過フラグ
     * @var array
     */
    public $eve_entry_timeover_flg = array();

    /**
     * イベント受付終了日付
     * @var array
     */
    public $eve_entry_timeover_date = array();
    
////////////////////////////////////////////////////////////////////////////////
// ホテル対応用
////////////////////////////////////////////////////////////////////////////////
    /**
     * 預かり所CDS
     * @var type 
     */
    public $raw_parcel_room_cd_sel = '';
    
    /**
     * 預かり所CDS
     * @var type 
     */
    public $raw_parcel_room_cds = array();
    
    /**
     * 預かり所Lbls
     * @var type 
     */
    public $raw_parcel_room_lbls = array();
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    /**
     * 受渡日時CDS
     * @var type 
     */
    public $raw_comiket_detail_inbound_delivery_date_cd_sel = '';
    
    /**
     * 受渡日時CDS
     * @var type 
     */
    public $raw_comiket_detail_inbound_delivery_date_cds = array();
    
    /**
     * 受渡日時Lbls
     * @var type 
     */
    public $raw_comiket_detail_inbound_delivery_date_lbls = array();
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    /**
     * 受渡場所
     * @var type 
     */
    public $raw_parcel_room = '';
    
////////////////////////////////////////////////////////////////////////////////
    
////////////////////////////////////////////////////////////////////////////////
// ふるさと祭り対応用
////////////////////////////////////////////////////////////////////////////////
    
    /**
     * 顧客区分
     * @var type 
     */
    public $raw_comiket_customer_kbn_sel = '';
    
    /**
     * 便種区分 - 往路
     * @var type 
     */
    public $raw_comiket_detail_outbound_binshu_kbn_sel = '';
    
    /**
     * 便種区分 - 復路
     * @var type 
     */
    public $raw_comiket_detail_inbound_binshu_kbn_sel = '';
    
    /**
     * エンティティ化されたコミケ申込みIDを返します。
     * @return string エンティティ化された識別コード選択値
     */
    public function comiket_id() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_id);
    }

    /**
     * エンティティ化された識別コードを返します。
     * @return string エンティティ化された識別コード選択値
     */
    public function comiket_div() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_div);
    }

    /**
     * エンティティ化されたイベントコード選択値を返します。
     * @return string エンティティ化された都道府県コード選択値
     */
    public function event_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_event_cd_sel);
    }

    /**
     * エンティティ化されたイベントコードリストを返します。
     * @return array エンティティ化された都道府県コードリスト
     */
    public function event_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_event_cds);
    }

    /**
     * エンティティ化されたイベントコードラベルリストを返します。
     * @return array エンティティ化された都道府県コードラベルリスト
     */
    public function event_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_event_lbls);
    }

    /**
     * エンティティ化されたイベントサブコード選択値を返します。
     * @return string エンティティ化された都道府県コード選択値
     */
    public function eventsub_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_eventsub_cd_sel);
    }

    /**
     * エンティティ化されたイベントサブコードリストを返します。
     * @return array エンティティ化された都道府県コードリスト
     */
    public function eventsub_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_eventsub_cds);
    }

    /**
     * エンティティ化されたイベントサブコードラベルリストを返します。
     * @return array エンティティ化された都道府県コードラベルリスト
     */
    public function eventsub_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_eventsub_lbls);
    }

    /**
     * エンティティ化されたイベント郵便番号を返します。
     * @return array エンティティ化された郵便番号
     */
    public function eventsub_zip() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_eventsub_zip);
    }

    /**
     * エンティティ化されたイベント場所を返します。
     * @return array エンティティ化された都道府県コードラベルリスト
     */
    public function eventsub_address() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_eventsub_address);
    }

    /**
     * エンティティ化されたイベント期間Fromを返します。
     * @return array エンティティ化された都道府県コードラベルリスト
     */
    public function eventsub_term_fr() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_eventsub_term_fr);
    }

    /**
     * エンティティ化されたイベント期間Fromを返します。
     * @return array エンティティ化された都道府県コードラベルリスト
     */
    public function eventsub_term_fr_nm() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_eventsub_term_fr_nm);
    }

    /**
     * エンティティ化されたイベント期間Fromを返します。
     * @return array エンティティ化された都道府県コードラベルリスト
     */
    public function eventsub_term_to() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_eventsub_term_to);
    }

    /**
     * エンティティ化されたイベント期間Fromを返します。
     * @return array エンティティ化された都道府県コードラベルリスト
     */
    public function eventsub_term_to_nm() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_eventsub_term_to_nm);
    }

    /**
     * エンティティ化されたサービスを返します。
     * @return array エンティティ化された都道府県コードラベルリスト
     */
    public function comiket_detail_service_selected() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_service_selected);
    }

    /**
     * エンティティ化された顧客コード 使用選択を返します。
     * @return string エンティティ化された顧客コード使用選択
     */
    public function comiket_customer_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_customer_cd_sel);
    }

    /**
     * エンティティ化された顧客コードを返します。
     * @return string エンティティ化された顧客コード
     */
    public function comiket_customer_cd() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_customer_cd);
    }

    /**
     * エンティティ化された顧客名（法人用）を返します。
     * @return string エンティティ化された顧客名
     */
    public function office_name() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_office_name);
    }

    /**
     * エンティティ化された顧客名-姓（個人用）を返します。
     * @return string エンティティ化された顧客名
     */
    public function comiket_personal_name_sei() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_personal_name_sei);
    }

    /**
     * エンティティ化された顧客名-名（個人用）を返します。
     * @return string エンティティ化された顧客名
     */
    public function comiket_personal_name_mei() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_personal_name_mei);
    }

    /**
     * エンティティ化された郵便番号1を返します。
     * @return string エンティティ化された郵便番号1
     */
    public function comiket_zip1() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_zip1);
    }

    /**
     * エンティティ化された郵便番号2を返します。
     * @return string エンティティ化された郵便番号2
     */
    public function comiket_zip2() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_zip2);
    }

    /**
     * エンティティ化された都道府県コード選択値を返します。
     * @return string エンティティ化された都道府県コード選択値
     */
    public function comiket_pref_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_pref_cd_sel);
    }

    /**
     * エンティティ化された都道府県コードリストを返します。
     * @return array エンティティ化された都道府県コードリスト
     */
    public function comiket_pref_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_pref_cds);
    }

    /**
     * エンティティ化された都道府県コードラベルリストを返します。
     * @return array エンティティ化された都道府県コードラベルリスト
     */
    public function comiket_pref_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_pref_lbls);
    }

    /**
     * エンティティ化された都道府県コードラベルリストを返します。
     * @return array エンティティ化された都道府県コードラベルリスト
     */
    public function comiket_pref_nm() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_pref_nm);
    }

    /**
     * エンティティ化された市区町村を返します。
     * @return string エンティティ化された市区町村
     */
    public function comiket_address() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_address);
    }

    /**
     * エンティティ化された番地・建物名を返します。
     * @return string エンティティ化された番地・建物名
     */
    public function comiket_building() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_building);
    }

    /**
     * エンティティ化された電話番号を返します。
     * @return string エンティティ化された電話番号
     */
    public function comiket_tel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_tel);
    }

    /**
     * エンティティ化されたメールアドレスを返します。
     * @return string エンティティ化されたメールアドレス
     */
    public function comiket_mail() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_mail);
    }

    /**
     * エンティティ化されたメールアドレス確認を返します。
     * @return string エンティティ化されたメールアドレス確認
     */
    public function comiket_mail_retype() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_mail_retype);
    }

    /**
     * エンティティ化された館名を返します。
     * @return string エンティティ化された館名
     */
    public function building_name() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_building_name);
    }

    /**
     * エンティティ化された館名を返します。
     * @return string エンティティ化された館名
     */
    public function building_name_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_building_name_sel);
    }

    /**
     * エンティティ化された館名-idリストを返します。
     * @return string エンティティ化された館名-idリスト
     */
    public function building_name_ids() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_building_name_ids);
    }

    /**
     * エンティティ化された館名-lblリストを返します。
     * @return string エンティティ化された館名-lblリスト
     */
    public function building_name_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_building_name_lbls);
    }

    /**
     * エンティティ化されたブース位置を返します。
     * @return string エンティティ化されたブース位置
     */
    public function building_cd_and_name_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_building_cd_and_name_sel);
    }

    /**
     * エンティティ化されたブース位置を返します。
     * @return string エンティティ化されたブース位置
     */
    public function building_booth_position() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_building_booth_position);
    }

    /**
     * エンティティ化されたブース位置を返します。
     * @return string エンティティ化されたブース位置
     */
    public function building_booth_position_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_building_booth_position_sel);
    }

    /**
     * エンティティ化されたブース位置-idリストを返します。
     * @return string エンティティ化されたブース位置-idリスト
     */
    public function building_booth_position_ids() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_building_booth_position_ids);
    }

    /**
     * エンティティ化されたブース位置-ラベルリストを返します。
     * @return string エンティティ化されたブース位置-ラベルリスト
     */
    public function building_booth_position_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_building_booth_position_lbls);
    }

    /**
     * エンティティ化されたブース名を返します。
     * @return string エンティティ化されたブース名
     */
    public function comiket_booth_name() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_booth_name);
    }
//
//    /**
//     * エンティティ化された選択ブースIDを返します。
//     * @return string エンティティ化されたブース名
//     */
//    public function building_booth_id_sel() {
//        return Sgmov_Component_String::htmlspecialchars($this->raw_building_booth_id_sel);
//    }
//
//    /**
//     * エンティティ化されたブースID一覧を返します。
//     * @return string エンティティ化されたブースID一覧
//     */
//    public function building_booth_ids() {
//        return Sgmov_Component_String::htmlspecialchars($this->raw_building_booth_ids);
//    }
//
//    /**
//     * エンティティ化されたブース名一覧を返します。
//     * @return string エンティティ化されたブース名一覧
//     */
//    public function building_booth_lbls() {
//        return Sgmov_Component_String::htmlspecialchars($this->raw_building_booth_lbls);
//    }

    /**
     * エンティティ化されたブース番号を返します。
     * @return string エンティティ化されたブース番号
     */
    public function comiket_booth_num() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_booth_num);
    }

    /**
     * エンティティ化された担当者名-姓を返します。
     * @return string エンティティ化された担当者名-姓
     */
    public function comiket_staff_sei() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_staff_sei);
    }

    /**
     * エンティティ化された担当者名-名を返します。
     * @return string エンティティ化された担当者名-名
     */
    public function comiket_staff_mei() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_staff_mei);
    }

    /**
     * エンティティ化された担当者名-姓-フリガナを返します。
     * @return string エンティティ化された担当者名-姓
     */
    public function comiket_staff_sei_furi() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_staff_sei_furi);
    }

    /**
     * エンティティ化された担当者名-名-フリガナを返します。
     * @return string エンティティ化された担当者名-名
     */
    public function comiket_staff_mei_furi() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_staff_mei_furi);
    }

    /**
     * エンティティ化された担当者電話番号を返します。
     * @return string エンティティ化されたブース番号
     */
    public function comiket_staff_tel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_staff_tel);
    }

    /**
     * エンティティ化された往復区分を返します。
     * @return string エンティティ化された往復区分
     */
    public function comiket_detail_type_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_type_sel);
    }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 搬入
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * エンティティ化された搬入-集荷先名を返します。
     * @return string エンティティ化された搬入-集荷先名
     */
    public function comiket_detail_outbound_name() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_name);
    }
    
    /**
     * エンティティ化された搬入-集荷先名を返します。
     * @return string エンティティ化された搬入-集荷先名-姓
     */
    public function comiket_detail_outbound_name_sei() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_name_sei);
    }
    
    /**
     * エンティティ化された搬入-集荷先名を返します。
     * @return string エンティティ化された搬入-集荷先名-名
     */
    public function comiket_detail_outbound_name_mei() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_name_mei);
    }

    /**
     * エンティティ化された搬入-配送先郵便番号を返します。
     * @return string エンティティ化された搬入-配送先郵便番号
     */
    public function comiket_detail_outbound_zip1() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_zip1);
    }

    /**
     * エンティティ化された搬入-配送先郵便番号を返します。
     * @return string エンティティ化された搬入-配送先郵便番号
     */
    public function comiket_detail_outbound_zip2() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_zip2);
    }

    /**
     * エンティティ化された搬入-配送先都道府県コード選択値を返します。
     * @return string エンティティ化された搬入-配送先都道府県コード選択値
     */
    public function comiket_detail_outbound_pref_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_pref_cd_sel);
    }

    /**
     * エンティティ化された搬入-配送先都道府県コードリストを返します。
     * @return string エンティティ化された搬入-配送先都道府県コードリスト
     */
    public function comiket_detail_outbound_pref_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_pref_cds);
    }

    /**
     * エンティティ化された搬入-配送先都道府県コードラベルリストを返します。
     * @return string エンティティ化された搬入-配送先都道府県コードラベルリスト
     */
    public function comiket_detail_outbound_pref_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_pref_lbls);
    }

    /**
     * エンティティ化された搬入-配送先郵便番号を返します。
     * @return string エンティティ化された搬入-配送先郵便番号
     */
    public function comiket_detail_outbound_address() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_address);
    }

    /**
     * エンティティ化された搬入-配送先郵便番号を返します。
     * @return string エンティティ化された搬入-配送先郵便番号
     */
    public function comiket_detail_outbound_building() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_building);
    }

    /**
     * エンティティ化された搬入-集荷先TELを返します。
     * @return string エンティティ化された搬入-集荷先TEL
     */
    public function comiket_detail_outbound_tel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_tel);
    }

    /**
     * エンティティ化された搬入-お預り日時-年 選択値を返します。
     * @return string エンティティ化された搬入-お預り日時-年 選択値
     */
    public function comiket_detail_outbound_collect_date_year_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_collect_date_year_sel);
    }

    /**
     * エンティティ化された搬入-お預り日時-年 リストを返します。
     * @return string エンティティ化された搬入-お預り日時-年 リスト
     */
    public function comiket_detail_outbound_collect_date_year_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_collect_date_year_cds);
    }

    /**
     * エンティティ化された搬入-お預り日時-年 ラベルリストを返します。
     * @return string エンティティ化された搬入-お預り日時-年 ラベルリスト
     */
    public function comiket_detail_outbound_collect_date_year_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_collect_date_year_lbls);
    }

    /**
     * エンティティ化された搬入-お預り日時-月 選択値を返します。
     * @return string エンティティ化された搬入-お預り日時-月 選択値
     */
    public function comiket_detail_outbound_collect_date_month_sel () {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_collect_date_month_sel);
    }

    /**
     * エンティティ化された搬入-お預り日時-月 リストを返します。
     * @return string エンティティ化された搬入-お預り日時-月 リスト
     */
    public function comiket_detail_outbound_collect_date_month_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_collect_date_month_cds);
    }

    /**
     * エンティティ化された搬入-お預り日時-月 ラベルリストを返します。
     * @return string エンティティ化された搬入-お預り日時-月 ラベルリスト
     */
    public function comiket_detail_outbound_collect_date_month_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_collect_date_month_lbls);
    }

    /**
     * エンティティ化された搬入-お預り日時-日 選択値を返します。
     * @return string エンティティ化された搬入-お預り日時-日 選択値
     */
    public function comiket_detail_outbound_collect_date_day_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_collect_date_day_sel);
    }

    /**
     * エンティティ化された搬入-お預り日時-日 リスト 選択値を返します。
     * @return string エンティティ化された搬入-お預り日時-日 リスト
     */
    public function comiket_detail_outbound_collect_date_day_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_collect_date_day_cds);
    }

    /**
     * エンティティ化された搬入-お預り日時-日 ラベルリストを返します。
     * @return string エンティティ化された搬入-お預り日時-日 ラベルリスト
     */
    public function comiket_detail_outbound_collect_date_day_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_collect_date_day_lbls);
    }

    /**
     * エンティティ化された搬入-お預り日時-時間帯 選択値を返します。
     * @return string エンティティ化された搬入-お預り日時-時間帯 選択値
     */
    public function comiket_detail_outbound_collect_time_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_collect_time_sel);
    }

    /**
     * エンティティ化された搬入-お預り日時-時間帯 リストを返します。
     * @return string エンティティ化された搬入-お預り日時-時間帯 リスト
     */
    public function comiket_detail_outbound_collect_time_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_collect_time_cds);
    }

    /**
     * エンティティ化された搬入-お預り日時-時間帯 ラベルリストを返します。
     * @return string エンティティ化された搬入-お預り日時-時間帯 ラベルリスト
     */
    public function comiket_detail_outbound_collect_time_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_collect_time_lbls);
    }

    /**
     * エンティティ化された搬入-お届け日時-年 選択値を返します。
     * @return string エンティティ化された搬入-お届け日時-年 選択値
     */
    public function comiket_detail_outbound_delivery_date_year_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_delivery_date_year_sel);
    }

    /**
     * エンティティ化された搬入-お届け日時-年 リストを返します。
     * @return string エンティティ化された搬入-お届け日時-年 リスト
     */
    public function comiket_detail_outbound_delivery_date_year_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_delivery_date_year_cds);
    }

    /**
     * エンティティ化された搬入-お届け日時-年 ラベルリストを返します。
     * @return string エンティティ化された搬入-お届け日時-年 ラベルリスト
     */
    public function comiket_detail_outbound_delivery_date_year_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_delivery_date_year_lbls);
    }

    /**
     * エンティティ化された搬入-お届け日時-月 選択値を返します。
     * @return string エンティティ化された搬入-お届け日時-月 選択値
     */
    public function comiket_detail_outbound_delivery_date_month_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_delivery_date_month_sel);
    }

    /**
     * エンティティ化された搬入-お届け日時-月 リストを返します。
     * @return string エンティティ化された搬入-お届け日時-月 リスト
     */
    public function comiket_detail_outbound_delivery_date_month_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_delivery_date_month_cds);
    }

    /**
     * エンティティ化された搬入-お届け日時-月 ラベルリストを返します。
     * @return string エンティティ化された搬入-お届け日時-月 ラベルリスト
     */
    public function comiket_detail_outbound_delivery_date_month_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_delivery_date_month_lbls);
    }

    /**
     * エンティティ化された搬入-お届け日時-日 選択値を返します。
     * @return string エンティティ化された搬入-お届け日時-日 選択値
     */
    public function comiket_detail_outbound_delivery_date_day_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_delivery_date_day_sel);
    }

    /**
     * エンティティ化された搬入-お届け日時-日 リストを返します。
     * @return string エンティティ化された搬入-お届け日時-日 リスト
     */
    public function comiket_detail_outbound_delivery_date_day_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_delivery_date_day_cds);
    }

    /**
     * エンティティ化された往路-お届け日時-日 ラベルリストを返します。
     * @return string エンティティ化された往路-お届け日時-日 ラベルリスト
     */
    public function comiket_detail_outbound_delivery_date_day_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_delivery_date_day_lbls);
    }

    /**
     * エンティティ化された往路-お届け日時-時間帯 選択値を返します。
     * @return string エンティティ化された往路-お届け日時-時間帯 選択値
     */
    public function comiket_detail_outbound_delivery_time_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_delivery_time_sel);
    }

    /**
     * エンティティ化された往路-お届け日時-時間帯 リストを返します。
     * @return string エンティティ化された往路-お届け日時-時間帯 リスト
     */
    public function comiket_detail_outbound_delivery_time_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_delivery_time_cds);
    }

    /**
     * エンティティ化された往路-お届け日時-時間帯 ラベルリストを返します。
     * @return string エンティティ化された往路-お届け日時-時間帯 ラベルリスト
     */
    public function comiket_detail_outbound_delivery_time_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_delivery_time_lbls);
    }

    /**
     * エンティティ化された往路-サービス選択区分を返します。
     * @return string エンティティ化された往路-サービス選択区分
     */
    public function comiket_detail_outbound_service_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_service_sel);
    }

    /**
     * エンティティ化された往路-宅配数量返します。
     * @return string エンティティ化された往路-宅配数量
     */
    public function comiket_box_outbound_num_ary($key="cmp") {
        return Sgmov_Component_String::htmlspecialchars(@$this->raw_comiket_box_outbound_num_ary[$key]);
    }

//
//    /**
//     * 往路-カーゴ選択
//     * @var string
//     */
//    public $raw_comiket_cargo_outbound_num_sel;
//
//    /**
//     * 往路-カーゴCds
//     * @var string
//     */
//    public $raw_comiket_cargo_outbound_num_cds;
//
//    /**
//     * 往路-カーゴラベル
//     * @var string
//     */
//    public $raw_comiket_cargo_outbound_num_lbls;

    /**
     * エンティティ化された往路-カーゴ選択値を返します。
     * @return string エンティティ化された往路-カーゴ選択値
     */
    public function comiket_cargo_outbound_num_sel() {
        return Sgmov_Component_String::htmlspecialchars(@$this->raw_comiket_cargo_outbound_num_sel);
    }

    /**
     * エンティティ化された往路-カーゴ値を返します。
     * @return string エンティティ化された往路-カーゴ値
     */
    public function comiket_cargo_outbound_num_cds() {
        return Sgmov_Component_String::htmlspecialchars(@$this->raw_comiket_cargo_outbound_num_cds);
    }

    /**
     * エンティティ化された往路-カーゴラベルを返します。
     * @return string エンティティ化された往路-カーゴラベル
     */
    public function comiket_cargo_outbound_num_lbls() {
        return Sgmov_Component_String::htmlspecialchars(@$this->raw_comiket_cargo_outbound_num_lbls);
    }

//
//    /**
//     * エンティティ化された往路-カーゴ数量を返します。
//     * @return string エンティティ化された往路-カーゴ数量
//     */
//    public function comiket_cargo_outbound_num_ary($key="cmp") {
//        return Sgmov_Component_String::htmlspecialchars(@$this->raw_comiket_cargo_outbound_num_ary[$key]);
//    }

    /**
     * エンティティ化された往路-貸切台数を返します。
     * @return string エンティティ化された往路-貸切台数
     */
    public function comiket_charter_outbound_num_ary($key="cmp") {
        return Sgmov_Component_String::htmlspecialchars(@$this->raw_comiket_charter_outbound_num_ary[$key]);
    }

    /**
     * エンティティ化された往路-備考を返します。
     * @return string エンティティ化された往路-備考
     */
    public function comiket_detail_outbound_note() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_note);
    }

    /**
     * エンティティ化された往路-備考-1行目を返します。
     * @return string エンティティ化された往路-備考
     */
    public function comiket_detail_outbound_note1() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_note1);
    }

    /**
     * エンティティ化された往路-備考-2行目を返します。
     * @return string エンティティ化された往路-備考
     */
    public function comiket_detail_outbound_note2() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_note2);
    }

    /**
     * エンティティ化された往路-備考-3行目を返します。
     * @return string エンティティ化された往路-備考
     */
    public function comiket_detail_outbound_note3() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_note3);
    }

    /**
     * エンティティ化された往路-備考-4行目を返します。
     * @return string エンティティ化された往路-備考
     */
    public function comiket_detail_outbound_note4() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_note4);
    }


/////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 復路
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * エンティティ化された復路-配送先名を返します。
     * @return string エンティティ化された復路-配送先名
     */
    public function comiket_detail_inbound_name() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_name);
    }

    /**
     * エンティティ化された復路-配送先郵便番号を返します。
     * @return string エンティティ化された復路-配送先郵便番号
     */
    public function comiket_detail_inbound_zip1() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_zip1);
    }

    /**
     * エンティティ化された復路-配送先郵便番号を返します。
     * @return string エンティティ化された復路-配送先郵便番号
     */
    public function comiket_detail_inbound_zip2() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_zip2);
    }

    /**
     * エンティティ化された復路-配送先都道府県コード選択値を返します。
     * @return string エンティティ化された復路-配送先都道府県コード選択値
     */
    public function comiket_detail_inbound_pref_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_pref_cd_sel);
    }

    /**
     * エンティティ化された復路-配送先都道府県コードリストを返します。
     * @return string エンティティ化された復路-配送先都道府県コードリスト
     */
    public function comiket_detail_inbound_pref_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_pref_cds);
    }

    /**
     * エンティティ化された復路-配送先都道府県コードラベルリストを返します。
     * @return string エンティティ化された復路-配送先都道府県コードラベルリスト
     */
    public function comiket_detail_inbound_pref_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_pref_lbls);
    }

    /**
     * エンティティ化された復路-配送先郵便番号を返します。
     * @return string エンティティ化された復路-配送先郵便番号
     */
    public function comiket_detail_inbound_address() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_address);
    }

    /**
     * エンティティ化された復路-配送先郵便番号を返します。
     * @return string エンティティ化された復路-配送先郵便番号
     */
    public function comiket_detail_inbound_building() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_building);
    }

    /**
     * エンティティ化された復路-配送先TELを返します。
     * @return string エンティティ化された復路-配送先TEL
     */
    public function comiket_detail_inbound_tel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_tel);
    }

    /**
     * エンティティ化された復路-お預り日時-年 選択値を返します。
     * @return string エンティティ化された復路-お預り日時-年 選択値
     */
    public function comiket_detail_inbound_collect_date_year_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_collect_date_year_sel);
    }

    /**
     * エンティティ化された復路-お預り日時-年 リストを返します。
     * @return string エンティティ化された復路-お預り日時-年 リスト
     */
    public function comiket_detail_inbound_collect_date_year_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_collect_date_year_cds);
    }

    /**
     * エンティティ化された復路-お預り日時-年 ラベルリストを返します。
     * @return string エンティティ化された復路-お預り日時-年 ラベルリスト
     */
    public function comiket_detail_inbound_collect_date_year_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_collect_date_year_lbls);
    }

    /**
     * エンティティ化された復路-お預り日時-月 選択値を返します。
     * @return string エンティティ化された復路-お預り日時-月 選択値
     */
    public function comiket_detail_inbound_collect_date_month_sel () {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_collect_date_month_sel);
    }

    /**
     * エンティティ化された復路-お預り日時-月 リストを返します。
     * @return string エンティティ化された復路-お預り日時-月 リスト
     */
    public function comiket_detail_inbound_collect_date_month_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_collect_date_month_cds);
    }

    /**
     * エンティティ化された復路-お預り日時-月 ラベルリストを返します。
     * @return string エンティティ化された復路-お預り日時-月 ラベルリスト
     */
    public function comiket_detail_inbound_collect_date_month_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_collect_date_month_lbls);
    }

    /**
     * エンティティ化された復路-お預り日時-日 選択値を返します。
     * @return string エンティティ化された復路-お預り日時-日 選択値
     */
    public function comiket_detail_inbound_collect_date_day_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_collect_date_day_sel);
    }

    /**
     * エンティティ化された復路-お預り日時-日 リスト 選択値を返します。
     * @return string エンティティ化された復路-お預り日時-日 リスト
     */
    public function comiket_detail_inbound_collect_date_day_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_collect_date_day_cds);
    }

    /**
     * エンティティ化された復路-お預り日時-日 ラベルリストを返します。
     * @return string エンティティ化された復路-お預り日時-日 ラベルリスト
     */
    public function comiket_detail_inbound_collect_date_day_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_collect_date_day_lbls);
    }

    /**
     * エンティティ化された復路-お預り日時-時間帯 選択値を返します。
     * @return string エンティティ化された復路-お預り日時-時間帯 選択値
     */
    public function comiket_detail_inbound_collect_time_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_collect_time_sel);
    }

    /**
     * エンティティ化された復路-お預り日時-時間帯 リストを返します。
     * @return string エンティティ化された復路-お預り日時-時間帯 リスト
     */
    public function comiket_detail_inbound_collect_time_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_collect_time_cds);
    }

    /**
     * エンティティ化された復路-お預り日時-時間帯 ラベルリストを返します。
     * @return string エンティティ化された復路-お預り日時-時間帯 ラベルリスト
     */
    public function comiket_detail_inbound_collect_time_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_collect_time_lbls);
    }

    /**
     * エンティティ化された復路-お届け日時-年 選択値を返します。
     * @return string エンティティ化された復路-お届け日時-年 選択値
     */
    public function comiket_detail_inbound_delivery_date_year_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_delivery_date_year_sel);
    }

    /**
     * エンティティ化された復路-お届け日時-年 リストを返します。
     * @return string エンティティ化された復路-お届け日時-年 リスト
     */
    public function comiket_detail_inbound_delivery_date_year_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_delivery_date_year_cds);
    }

    /**
     * エンティティ化された復路-お届け日時-年 ラベルリストを返します。
     * @return string エンティティ化された復路-お届け日時-年 ラベルリスト
     */
    public function comiket_detail_inbound_delivery_date_year_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_delivery_date_year_lbls);
    }

    /**
     * エンティティ化された復路-お届け日時-月 選択値を返します。
     * @return string エンティティ化された復路-お届け日時-月 選択値
     */
    public function comiket_detail_inbound_delivery_date_month_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_delivery_date_month_sel);
    }

    /**
     * エンティティ化された復路-お届け日時-月 リストを返します。
     * @return string エンティティ化された復路-お届け日時-月 リスト
     */
    public function comiket_detail_inbound_delivery_date_month_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_delivery_date_month_cds);
    }

    /**
     * エンティティ化された復路-お届け日時-月 ラベルリストを返します。
     * @return string エンティティ化された復路-お届け日時-月 ラベルリスト
     */
    public function comiket_detail_inbound_delivery_date_month_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_delivery_date_month_lbls);
    }

    /**
     * エンティティ化された復路-お届け日時-日 選択値を返します。
     * @return string エンティティ化された復路-お届け日時-日 選択値
     */
    public function comiket_detail_inbound_delivery_date_day_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_delivery_date_day_sel);
    }

    /**
     * エンティティ化された復路-お届け日時-日 リストを返します。
     * @return string エンティティ化された復路-お届け日時-日 リスト
     */
    public function comiket_detail_inbound_delivery_date_day_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_delivery_date_day_cds);
    }

    /**
     * エンティティ化された復路-お届け日時-日 ラベルリストを返します。
     * @return string エンティティ化された復路-お届け日時-日 ラベルリスト
     */
    public function comiket_detail_inbound_delivery_date_day_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_delivery_date_day_lbls);
    }

    /**
     * エンティティ化された復路-お届け日時-時間帯 選択値を返します。
     * @return string エンティティ化された復路-お届け日時-時間帯 選択値
     */
    public function comiket_detail_inbound_delivery_time_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_delivery_time_sel);
    }

    /**
     * エンティティ化された復路-お届け日時-時間帯 リストを返します。
     * @return string エンティティ化された復路-お届け日時-時間帯 リスト
     */
    public function comiket_detail_inbound_delivery_time_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_delivery_time_cds);
    }

    /**
     * エンティティ化された復路-お届け日時-時間帯 ラベルリストを返します。
     * @return string エンティティ化された復路-お届け日時-時間帯 ラベルリスト
     */
    public function comiket_detail_inbound_delivery_time_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_delivery_time_lbls);
    }

    /**
     * エンティティ化された復路-サービス選択区分を返します。
     * @return string エンティティ化された復路-サービス選択区分
     */
    public function comiket_detail_inbound_service_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_service_sel);
    }

    /**
     * エンティティ化された復路-宅配数量返します。
     * @return string エンティティ化された復路-宅配数量
     */
    public function comiket_box_inbound_num_ary($key="cmp") {
        if(!isset($this->raw_comiket_box_inbound_num_ary[$key])) {
            return "";
        }
        return Sgmov_Component_String::htmlspecialchars(@$this->raw_comiket_box_inbound_num_ary[$key]);
    }

    /**
     * エンティティ化された復路-カーゴ選択値を返します。
     * @return string エンティティ化された復路-カーゴ選択値
     */
    public function comiket_cargo_inbound_num_sel() {
        return Sgmov_Component_String::htmlspecialchars(@$this->raw_comiket_cargo_inbound_num_sel);
    }

    /**
     * エンティティ化された復路-カーゴ値を返します。
     * @return string エンティティ化された復路-カーゴ値
     */
    public function comiket_cargo_inbound_num_cds() {
        return Sgmov_Component_String::htmlspecialchars(@$this->raw_comiket_cargo_inbound_num_cds);
    }

    /**
     * エンティティ化された復路-カーゴラベルを返します。
     * @return string エンティティ化された復路-カーゴラベル
     */
    public function comiket_cargo_inbound_num_lbls() {
        return Sgmov_Component_String::htmlspecialchars(@$this->raw_comiket_cargo_inbound_num_lbls);
    }
//
//    /**
//     * エンティティ化された復路-カーゴ数量を返します。
//     * @return string エンティティ化された復路-カーゴ数量
//     */
//    public function comiket_cargo_inbound_num_ary($key="cmp") {
//        if(!isset($this->raw_comiket_cargo_inbound_num_ary[$key])) {
//            return "";
//        }
//        return Sgmov_Component_String::htmlspecialchars(@$this->raw_comiket_cargo_inbound_num_ary[$key]);
//    }

    /**
     * エンティティ化された復路-貸切台数を返します。
     * @return string エンティティ化された復路-貸切台数
     */
    public function comiket_charter_inbound_num_ary($key="cmp") {
        if(!isset($this->raw_comiket_charter_inbound_num_ary[$key])) {
            return "";
        }
        return Sgmov_Component_String::htmlspecialchars(@$this->raw_comiket_charter_inbound_num_ary[$key]);
    }

    /**
     * エンティティ化された復路-備考を返します。
     * @return string エンティティ化された復路-備考
     */
    public function comiket_detail_inbound_note() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_note);
    }

    /**
     * エンティティ化された復路-備考-1行目を返します。
     * @return string エンティティ化された復路-備考
     */
    public function comiket_detail_inbound_note1() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_note1);
    }

    /**
     * エンティティ化された復路-備考-2行目を返します。
     * @return string エンティティ化された復路-備考
     */
    public function comiket_detail_inbound_note2() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_note2);
    }

    /**
     * エンティティ化された復路-備考-3行目を返します。
     * @return string エンティティ化された復路-備考
     */
    public function comiket_detail_inbound_note3() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_note3);
    }

    /**
     * エンティティ化された復路-備考-4行目を返します。
     * @return string エンティティ化された復路-備考
     */
    public function comiket_detail_inbound_note4() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_note4);
    }


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 支払
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



    /**
     * エンティティ化されたお支払方法コード選択値を返します。
     * @return string エンティティ化されたお支払方法コード選択値
     */
    public function comiket_payment_method_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_payment_method_cd_sel);
    }

    /**
     * エンティティ化されたお支払店コード選択値を返します。
     * @return string エンティティ化されたお支払店コード選択値
     */
    public function comiket_convenience_store_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_convenience_store_cd_sel);
    }

    /**
     * エンティティ化されたお支払店コードリストを返します。
     * @return string エンティティ化されたお支払店コードリスト
     */
    public function comiket_convenience_store_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_convenience_store_cds);
    }

    /**
     * エンティティ化されたお支払店ラベルリストを返します。
     * @return string エンティティ化されたお支払店ラベルリスト
     */
    public function comiket_convenience_store_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_convenience_store_lbls);
    }

    /**
     * エンティティ化されたクレジットカード番号を返します。
     * @return string エンティティ化されたクレジットカード番号
     */
    public function card_number() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_card_number);
    }

    /**
     * エンティティ化された有効期限 月を返します。
     * @return string エンティティ化された有効期限 月
     */
    public function card_expire_month_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_card_expire_month_cd_sel);
    }

    /**
     * エンティティ化された有効期限 年を返します。
     * @return string エンティティ化された有効期限 年
     */
    public function card_expire_year_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_card_expire_year_cd_sel);
    }

    /**
     * エンティティ化されたセキュリティコードを返します。
     * @return string エンティティ化されたセキュリティコード
     */
    public function security_cd() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_security_cd);
    }

    /**
     * エンティティ化された受付時間超過フラグリストを返します。
     * @return array エンティティ化された受付時間超過フラグリスト
     */
    public function eve_timeover_flg() {
        return Sgmov_Component_String::htmlspecialchars($this->eve_entry_timeover_flg);
    }

    /**
     * エンティティ化された受付時間日付リストを返します。
     * @return array エンティティ化された受付時間日付リスト
     */
    public function eve_timeover_date() {
        return Sgmov_Component_String::htmlspecialchars($this->eve_entry_timeover_date);
    }


    public function input_mode() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_input_mode);
    }
    
////////////////////////////////////////////////////////////////////////////////
// ホテル対応用
////////////////////////////////////////////////////////////////////////////////
    
    /**
     * エンティティ化された預かり所コードを返します。
     */
    public function raw_parcel_room_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_parcel_room_cd_sel);
    }
    
    /**
     * エンティティ化された預かり所コードリストを返します。
     * @return string エンティティ化されたお支払店コードリスト
     */
    public function raw_parcel_room_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_parcel_room_cds);
    }

    /**
     * エンティティ化された預かり所ラベルリストを返します。
     * @return string エンティティ化されたお支払店ラベルリスト
     */
    public function raw_parcel_room_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_parcel_room_lbls);
    }
    
////////////////////////////////////////////////////////////////////////////////
    /**
     * エンティティ化された受渡日時コードを返します。
     */
    public function raw_comiket_detail_inbound_delivery_date_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_delivery_date_cd_sel);
    }
    
    /**
     * エンティティ化された受渡日時コードリストを返します。
     * @return string エンティティ化された受渡日時コードリスト
     */
    public function raw_comiket_detail_inbound_delivery_date_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_delivery_date_cds);
    }

    /**
     * エンティティ化された受渡日時ラベルリストを返します。
     * @return string エンティティ化された受渡日時ラベルリスト
     */
    public function raw_comiket_detail_inbound_delivery_date_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_delivery_date_lbls);
    }
    
////////////////////////////////////////////////////////////////////////////////
    
    /**
     * エンティティ化された受渡場所を返します。
     * @return string エンティティ化された受渡場所
     */
    public function raw_parcel_room() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_parcel_room);
    }
    
////////////////////////////////////////////////////////////////////////////////
    
////////////////////////////////////////////////////////////////////////////////
// ふるさと祭り対応用
////////////////////////////////////////////////////////////////////////////////
    
    /**
     * エンティティ化された顧客区分を返します。
     * @return string エンティティ化された受渡場所
     */
    public function comiket_customer_kbn_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_customer_kbn_sel);
    }
    
    /**
     * エンティティ化された便種区分(往路)を返します。
     * @return string エンティティ化された受渡場所
     */
    public function comiket_detail_outbound_binshu_kbn_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_outbound_binshu_kbn_sel);
    }
    
    /**
     * エンティティ化された便種区分(復路)を返します。
     * @return string エンティティ化された受渡場所
     */
    public function comiket_detail_inbound_binshu_kbn_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_binshu_kbn_sel);
    }

// 追加
    // 記事欄コードリスト
    public $raw_comiket_detail_inbound_kijiran_cds = array();
    // 記事欄ラベルリスト
    public $raw_comiket_detail_inbound_kijiran_lbls = array();

    public function comiket_detail_inbound_kijiran_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_kijiran_cds);
    }
    public function comiket_detail_inbound_kijiran_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_kijiran_lbls);
    }


    // 記事欄
//    public $raw_comiket_detail_inbound_kijiran1 = '';
//    public $raw_comiket_detail_inbound_kijiran2 = '';
//    public $raw_comiket_detail_inbound_kijiran3 = '';
    public $raw_comiket_detail_inbound_kijiran4 = '';

    // 記事欄エンティティ化
//    public function comiket_detail_inbound_kijiran1() {
//        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_kijiran1);
//    }
//    public function comiket_detail_inbound_kijiran2() {
//        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_kijiran2);
//    }
//    public function comiket_detail_inbound_kijiran3() {
//        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_kijiran3);
//    }
    public function comiket_detail_inbound_kijiran4() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_kijiran4);
    }

    // 記事欄コンボ選択値
    public $raw_comiket_detail_inbound_kijiran_cd_sel1 = '';
    public $raw_comiket_detail_inbound_kijiran_cd_sel2 = '';
    public $raw_comiket_detail_inbound_kijiran_cd_sel3 = '';

    // 記事欄エンティティ化
    public function comiket_detail_inbound_kijiran_cd_sel1() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_kijiran_cd_sel1);
    }
    public function comiket_detail_inbound_kijiran_cd_sel2() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_kijiran_cd_sel2);
    }
    public function comiket_detail_inbound_kijiran_cd_sel3() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comiket_detail_inbound_kijiran_cd_sel3);
    }


}