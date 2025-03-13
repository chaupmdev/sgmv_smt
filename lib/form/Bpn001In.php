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
class Sgmov_Form_Bpn001In {

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
     * 物販
     * @var string
     */
    public $comiket_box_buppan_num_ary = array();

    /**
     * 物販在庫商品コード
     * @var string
     */
    public $comiket_box_buppan_ziko_shohin_cd_ary = array();
   
    /**
     *
     * @var type
     */
    public $input_mode = '';
    
    public $parcel_room = '';
    
    public $is_conf_rule = false;
    
    /**
     * 顧客区分
     * @var type 
     */
    public $comiket_customer_kbn_sel = '';

    /**
     * 物販タイプ
     * @var type 
     */
    public $bpn_type = '';

    /**
     * 商品パタン
     * @var type 
     */
    public $shohin_pattern = '';


    /**
     * 商品引き渡し日-年
     * @var type 
     */
    public $comiket_detail_collect_date_year_sel = '';


    /**
     * 商品引き渡し日-月
     * @var type 
     */
    public $comiket_detail_collect_date_month_sel = '';


    /**
     * 商品引き渡し日-日
     * @var type 
     */
    public $comiket_detail_collect_date_day_sel = '';

    /**
     * 数量 プルダウン
     * @var string
     */
    public $comiket_box_buppan_num_sel = '';

    /**
     * イベント識別子
     * @var string
     */
    public $shikibetsushi = '';
}