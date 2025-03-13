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
class Sgmov_Form_Msb001In {

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
    
    /**
     * 顧客区分
     * @var type 
     */
    public $comiket_customer_kbn_sel = '';
    
    /**
     * 配送or預り選択
     * @var type 
     */
    public $comiket_detail_service_sel = '';
    

//////////////////////////////////////////////////////////////////////////////////////////////
// 手荷物預かり
//////////////////////////////////////////////////////////////////////////////////////////////
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
     * 集荷先名
     * @var string
     */
    public $comiket_detail_name = '';
    
    /**
     * 集荷先名-姓
     * @var string
     */
    public $comiket_detail_name_sei = '';
    
    /**
     * 集荷先名-名
     * @var string
     */
    public $comiket_detail_name_mei = '';

    /**
     * 集荷先郵便番号1
     * @var string
     */
    public $comiket_detail_zip1 = '';

    /**
     * 集荷先郵便番号2
     * @var string
     */
    public $comiket_detail_zip2 = '';

    /**
     * 集荷先都道府県
     * @var string
     */
    public $comiket_detail_pref_cd_sel = '';

    /**
     * 集荷先市区町村
     * @var string
     */
    public $comiket_detail_address = '';

    /**
     * 集荷先番地・建物名
     * @var string
     */
    public $comiket_detail_building = '';

    /**
     * 集荷先TEL
     * @var string
     */
    public $comiket_detail_tel = '';

    /**
     * お預かり日時-年
     * @var string
     */
    public $comiket_detail_collect_date_year_sel = '';

    /**
     * お預かり日時-月
     * @var string
     */
    public $comiket_detail_collect_date_month_sel = '';

    /**
     * お預かり日時-日
     * @var string
     */
    public $comiket_detail_collect_date_day_sel = '';

    /**
     * 備考-1行目
     * @var string
     */
    public $comiket_detail_note1 = '';

    /**
     * 備考-2行目
     * @var string
     */
    public $comiket_detail_note2 = '';

    /**
     * 備考-3行目
     * @var string
     */
    public $comiket_detail_note3 = '';

    /**
     * 備考-4行目
     * @var string
     */
    public $comiket_detail_note4 = '';
}