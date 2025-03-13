<?php
/**
 * @package    ClassDefFile
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useComponents(array('String'));
/**#@-*/

 /**
 * 概算見積結果表示画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
 class Sgmov_Form_Pre002Out {

    /**
     * タイプコード
     * @var string
     */
    public $raw_type_cd = '';

    /**
     * 全選択ボタン押下フラグ
     * @var string
     */
    public $raw_all_sentakbtn_click_flag = '';

    /**
     * 入力画面初期表示時コースコード選択値
     * @var string
     */
    public $raw_init_course_cd_sel = '';

    /**
     * 入力画面初期表示時プランコード選択値
     * @var string
     */
    public $raw_init_plan_cd_sel = '';

    /**
     * コースコード選択値
     * @var string
     */
    public $raw_course_cd_sel = '';

    /**
     * プランコード選択値
     * @var string
     */
    public $raw_plan_cd_sel = '';

    /**
     * エアコン有無フラグ選択値
     * @var string
     */
    public $raw_aircon_exist_flag_sel = '';

    /**
     * 個人向けサービス ページの選択されたメニュー
     * @var string
     */
    public $menu_personal = '';

    /**
     * 出発エリアコード選択値
     * @var string
     */
    public $raw_from_area_cd_sel = '';

    /**
     * 到着エリアコード選択値
     * @var string
     */
    public $raw_to_area_cd_sel = '';

    /**
     * 引越予定日年コード選択値
     * @var string
     */
    public $raw_move_date_year_cd_sel = '';

    /**
     * 引越予定日月コード選択値
     * @var string
     */
    public $raw_move_date_month_cd_sel = '';

    /**
     * 引越予定日日コード選択値
     * @var string
     */
    public $raw_move_date_day_cd_sel = '';

    /**
     * コース
     * @var string
     */
    public $raw_course = '';

    /**
     * プラン
     * @var string
     */
    public $raw_plan = '';

    /**
     * エアコン有無
     * @var string
     */
    public $raw_aircon_exist = '';

    /**
     * 出発エリア
     * @var string
     */
    public $raw_from_area = '';

    /**
     * 到着エリア
     * @var string
     */
    public $raw_to_area = '';

    /**
     * 引越予定日
     * @var string
     */
    public $raw_move_date = '';

    /**
     * 基本料金
     * @var string
     */
    public $raw_base_price = '';

    /**
     * 割引キャンペーン情報（割引金額・タイトル）リスト
     * @var array
     */
    public $raw_discount_campaign_infos = array();

    /**
     * 前月リンクアドレス
     * @var string
     */
    public $raw_prev_month_link = '';

    /**
     * 次月リンクアドレス
     * @var string
     */
    public $raw_next_month_link = '';

    /**
     * 前週リンクアドレス
     * @var string
     */
    public $raw_prev_week_link = '';

    /**
     * 次週リンクアドレス
     * @var string
     */
    public $raw_next_week_link = '';

    /**
     * スマホ版週表示開始日
     * @var string
     */
    public $raw_start_week_day = '';

    /**
     * キャンペーン名リスト
     * @var array
     */
    public $raw_campaign_names = array();

    /**
     * キャンペーン内容リスト
     * @var array
     */
    public $raw_campaign_contents = array();

    /**
     * キャンペーン開始日リスト
     * @var array
     */
    public $raw_campaign_starts = array();

    /**
     * キャンペーン終了日リスト
     * @var array
     */
    public $raw_campaign_ends = array();

    /**
     * カレンダー年
     * @var string
     */
    public $raw_cal_year = '';

    /**
     * カレンダー月
     * @var string
     */
    public $raw_cal_month = '';

    /**
     * カレンダー日付リスト
     * @var array
     */
    public $raw_cal_days = array();

    /**
     * カレンダー祝日フラグリスト
     * @var array
     */
    public $raw_cal_holiday_flags = array();

    /**
     * カレンダーキャンペーンフラグリスト
     * @var array
     */
    public $raw_cal_campaign_flags = array();

    /**
     * カレンダー料金リスト
     * @var array
     */
    public $raw_cal_prices = array();

    /**
     * 他社連携キャンペーンID
     * @var string
     */
    public $raw_oc_id = '';

    /**
     * 他社連携キャンペーン名称
     * @var string
     */
    public $raw_oc_name = '';

    /**
     * 他社連携キャンペーン内容
     * @var string
     */
    public $raw_oc_content = '';

    /**
     * 個人向けサービス ページの選択されたメニュー
     * @var string
     */
    public $raw_menu_personal = '';

    /**
     * エンティティ化されたタイプコードを返します。
     * @return string エンティティ化されたタイプコード
     */
    public function type_cd() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_type_cd);
    }

    /**
     * エンティティ化された全選択ボタン押下フラグを返します。
     * @return string エンティティ化された全選択ボタン押下フラグ
     */
    public function all_sentakbtn_click_flag() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_all_sentakbtn_click_flag);
    }

    /**
     * エンティティ化された入力画面初期表示時コースコードを返します。
     * @return string エンティティ化された入力画面初期表示時コースコード
     */
    public function init_course_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_init_course_cd_sel);
    }

    /**
     * エンティティ化された入力画面初期表示時プランコードを返します。
     * @return string エンティティ化された入力画面初期表示時タイプコード
     */
    public function init_plan_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_init_plan_cd_sel);
    }

    /**
     * エンティティ化されたコースコード選択値を返します。
     * @return string エンティティ化されたコースコード選択値
     */
    public function course_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_course_cd_sel);
    }

    /**
     * エンティティ化されたプランコード選択値を返します。
     * @return string エンティティ化されたプランコード選択値
     */
    public function plan_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_plan_cd_sel);
    }

    /**
     * エンティティ化されたエアコン有無フラグ選択値を返します。
     * @return string エンティティ化されたエアコン有無フラグ選択値
     */
    public function aircon_exist_flag_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_aircon_exist_flag_sel);
    }

    /**
     * エンティティ化された出発エリアコード選択値を返します。
     * @return string エンティティ化された出発エリアコード選択値
     */
    public function from_area_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_area_cd_sel);
    }

    /**
     * エンティティ化された到着エリアコード選択値を返します。
     * @return string エンティティ化された到着エリアコード選択値
     */
    public function to_area_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_to_area_cd_sel);
    }

    /**
     * エンティティ化された引越予定日年コード選択値を返します。
     * @return string エンティティ化された引越予定日年コード選択値
     */
    public function move_date_year_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_move_date_year_cd_sel);
    }

    /**
     * エンティティ化された引越予定日月コード選択値を返します。
     * @return string エンティティ化された引越予定日月コード選択値
     */
    public function move_date_month_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_move_date_month_cd_sel);
    }

    /**
     * エンティティ化された引越予定日日コード選択値を返します。
     * @return string エンティティ化された引越予定日日コード選択値
     */
    public function move_date_day_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_move_date_day_cd_sel);
    }

    /**
     * エンティティ化されたコースを返します。
     * @return string エンティティ化されたコース
     */
    public function course() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_course);
    }

    /**
     * エンティティ化されたプランを返します。
     * @return string エンティティ化されたプラン
     */
    public function plan() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_plan);
    }

    /**
     * エンティティ化されたエアコン有無を返します。
     * @return string エンティティ化されたエアコン有無
     */
    public function aircon_exist() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_aircon_exist);
    }

    /**
     * エンティティ化された出発エリアを返します。
     * @return string エンティティ化された出発エリア
     */
    public function from_area() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_area);
    }

    /**
     * エンティティ化された到着エリアを返します。
     * @return string エンティティ化された到着エリア
     */
    public function to_area() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_to_area);
    }

    /**
     * エンティティ化された引越予定日を返します。
     * @return string エンティティ化された引越予定日
     */
    public function move_date() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_move_date);
    }

    /**
     * エンティティ化された基本料金を返します。
     * @return string エンティティ化された基本料金
     */
    public function base_price() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_base_price);
    }

    /**
     * エンティティ化された割引キャンペーン情報（割引金額・タイトル）を返します。
     * @return array エンティティ化された割引キャンペーン情報（割引金額・タイトル）リスト
     */
    public function discount_campaign_infos() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_discount_campaign_infos);
    }

    /**
     * エンティティ化された前月リンクアドレスを返します。
     * @return string エンティティ化された前月リンクアドレス
     */
    public function prev_month_link() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_prev_month_link);
    }

    /**
     * エンティティ化された次月リンクアドレスを返します。
     * @return string エンティティ化された次月リンクアドレス
     */
    public function next_month_link() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_next_month_link);
    }

    /**
     * エンティティ化された前週リンクアドレスを返します。
     * @return string エンティティ化された前週リンクアドレス
     */
    public function prev_week_link() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_prev_week_link);
    }

    /**
     * エンティティ化された次週リンクアドレスを返します。
     * @return string エンティティ化された次週リンクアドレス
     */
    public function next_week_link() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_next_week_link);
    }

    /**
     * エンティティ化されたスマホ用週表示開始日を返します。
     * @return string エンティティ化されたスマホ用週表示開始日
     */
    public function start_week_day() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_start_week_day);
    }

    /**
     * エンティティ化されたキャンペーン名リストを返します。
     * @return array エンティティ化されたキャンペーン名リスト
     */
    public function campaign_names() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_campaign_names);
    }

    /**
     * エンティティ化されたキャンペーン内容リストを返します（改行文字の前にBRタグが挿入されます）。
     * @return array エンティティ化されたキャンペーン内容リスト
     */
    public function campaign_contents() {
        return Sgmov_Component_String::nl2br(Sgmov_Component_String::htmlspecialchars($this->raw_campaign_contents));
    }

    /**
     * エンティティ化されたキャンペーン開始日リストを返します。
     * @return array エンティティ化されたキャンペーン開始日リスト
     */
    public function campaign_starts() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_campaign_starts);
    }

    /**
     * エンティティ化されたキャンペーン終了日リストを返します。
     * @return array エンティティ化されたキャンペーン終了日リスト
     */
    public function campaign_ends() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_campaign_ends);
    }

    /**
     * エンティティ化されたカレンダー年を返します。
     * @return string エンティティ化されたカレンダー年
     */
    public function cal_year() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cal_year);
    }

    /**
     * エンティティ化されたカレンダー月を返します。
     * @return string エンティティ化されたカレンダー月
     */
    public function cal_month() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cal_month);
    }

    /**
     * エンティティ化されたカレンダー日付リストを返します。
     * @return array エンティティ化されたカレンダー日付リスト
     */
    public function cal_days() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cal_days);
    }

    /**
     * エンティティ化されたカレンダー祝日フラグリストを返します。
     * @return array エンティティ化されたカレンダー祝日フラグリスト
     */
    public function cal_holiday_flags() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cal_holiday_flags);
    }

    /**
     * エンティティ化されたカレンダーキャンペーンフラグリストを返します。
     * @return array エンティティ化されたカレンダーキャンペーンフラグリスト
     */
    public function cal_campaign_flags() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cal_campaign_flags);
    }

    /**
     * エンティティ化されたカレンダー料金リストを返します。
     * @return array エンティティ化されたカレンダー料金リスト
     */
    public function cal_prices() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cal_prices);
    }

    /**
     * エンティティ化された他社連携キャンペーンIDを返します。
     * @return array エンティティ化された他社連携キャンペーン名称
     */
    public function oc_id() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_oc_id);
    }

    /**
     * エンティティ化された他社連携キャンペーン名称を返します。
     * @return array エンティティ化された他社連携キャンペーン名称
     */
    public function oc_name() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_oc_name);
    }

    /**
     * エンティティ化された他社連携キャンペーン内容を返します。
     * @return array エンティティ化された他社連携キャンペーン内容
     */
    public function oc_content() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_oc_content);
    }

    /**
     * エンティティ化された個人向けサービス ページの選択されたメニューを返します。
     * @return array エンティティ化された個人向けサービス ページの選択されたメニュー
     */
    public function menu_personal() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_menu_personal);
    }
}