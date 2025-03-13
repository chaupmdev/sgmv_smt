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
 * 概算見積結果表示画面から訪問見積り画面への受け渡しフォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Pre003In
{
    /**
     * タイプコード
     * @var string
     */
    public $type_cd = '';

    /**
     * コースコード選択値
     * @var string
     */
    public $course_cd_sel = '';

    /**
     * プランコード選択値
     * @var string
     */
    public $plan_cd_sel = '';

    /**
     * エアコン有無フラグ選択値
     * @var string
     */
    public $aircon_exist_flag_sel = '';


    /**
     * 個人向けサービス ページから 選択されたメニュー
     * @var string
     */
    public $menu_personal = '';

    /**
     * 出発エリアコード選択値
     * @var string
     */
    public $from_area_cd_sel = '';

    /**
     * 到着エリアコード選択値
     * @var string
     */
    public $to_area_cd_sel = '';

    /**
     * 引越予定日年コード選択値
     * @var string
     */
    public $move_date_year_cd_sel = '';

    /**
     * 引越予定日月コード選択値
     * @var string
     */
    public $move_date_month_cd_sel = '';

    /**
     * 引越予定日日コード選択値
     * @var string
     */
    public $move_date_day_cd_sel = '';

    /**
     * コース
     * @var string
     */
    public $course = '';

    /**
     * プラン
     * @var string
     */
    public $plan = '';

    /**
     * エアコン有無
     * @var string
     */
    public $aircon_exist = '';

    /**
     * 出発エリア
     * @var string
     */
    public $from_area = '';

    /**
     * 到着エリア
     * @var string
     */
    public $to_area = '';

    /**
     * 引越予定日
     * @var string
     */
    public $move_date = '';

    /**
     * 概算金額
     * @var string
     */
    public $estimate_price = '';

    /**
     * 基本料金
     * @var string
     */
    public $base_price = '';

    /**
     * 割引キャンペーン情報（割引金額・タイトル）リスト
     * @var array
     */
    public $discount_campaign_infos = array();

    /**
     * 前月リンクアドレス
     * @var string
     */
    public $prev_month_link = '';

    /**
     * 次月リンクアドレス
     * @var string
     */
    public $next_month_link = '';

    /**
     * キャンペーン名リスト
     * @var array
     */
    public $campaign_names = array();

    /**
     * キャンペーン内容リスト
     * @var array
     */
    public $campaign_contents = array();

    /**
     * キャンペーン開始日リスト
     * @var array
     */
    public $campaign_starts = array();

    /**
     * キャンペーン終了日リスト
     * @var array
     */
    public $campaign_ends = array();

    /**
     * カレンダー年
     * @var string
     */
    public $cal_year = '';

    /**
     * カレンダー月
     * @var string
     */
    public $cal_month = '';

    /**
     * カレンダー日付リスト
     * @var array
     */
    public $cal_days = array();

    /**
     * カレンダー祝日フラグリスト
     * @var array
     */
    public $cal_holiday_flags = array();

    /**
     * カレンダーキャンペーンフラグリスト
     * @var array
     */
    public $cal_campaign_flags = array();

    /**
     * カレンダー料金リスト
     * @var array
     */
    public $cal_prices = array();

}
?>
