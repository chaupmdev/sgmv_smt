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
 * 概算見積入力画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Pre001Out
{
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
     * コース表示フラグリスト
     * @var array
     */
    public $raw_course_view_flag = array();

    /**
     * コース全表示ボタンフラグ
     * @var string
     */
    public $raw_course_allbtn_flag = '';

    /**
     * プラン表示フラグリスト
     * @var array
     */
    public $raw_plan_view_flag = array();

    /**
     * エアコン有無フラグ選択値
     * @var string
     */
    public $raw_aircon_exist_flag_sel = '';

    /**
     * 出発エリアコード選択値
     * @var string
     */
    public $raw_from_area_cd_sel = '';

    /**
     * 出発エリアコードリスト
     * @var array
     */
    public $raw_from_area_cds = array();

    /**
     * 出発エリアラベルリスト
     * @var array
     */
    public $raw_from_area_lbls = array();

    /**
     * 到着エリアコード選択値
     * @var string
     */
    public $raw_to_area_cd_sel = '';

    /**
     * 到着エリアコードリスト
     * @var array
     */
    public $raw_to_area_cds = array();

    /**
     * 到着エリアラベルリスト
     * @var array
     */
    public $raw_to_area_lbls = array();

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
     * 引越予定日年コードリスト
     * @var array
     */
    public $raw_move_date_year_cds = array();

    /**
     * 引越予定日年ラベルリスト
     * @var array
     */
    public $raw_move_date_year_lbls = array();

    /**
     * 引越予定日月コードリスト
     * @var array
     */
    public $raw_move_date_month_cds = array();

    /**
     * 引越予定日月ラベルリスト
     * @var array
     */
    public $raw_move_date_month_lbls = array();

    /**
     * 引越予定日日コードリスト
     * @var array
     */
    public $raw_move_date_day_cds = array();

    /**
     * 引越予定日日ラベルリスト
     * @var array
     */
    public $raw_move_date_day_lbls = array();


    /**
     * 個人向けサービス ページの選択されたメニュー
     * @var string
     */
    public $menu_personal = '';


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
     * エンティティ化されたタイプコードを返します。
     * @return string エンティティ化されたタイプコード
     */
    public function type_cd()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_type_cd);
    }

    /**
     * エンティティ化された入力画面初期表示時コースコードを返します。
     * @return string エンティティ化された入力画面初期表示時コースコード
     */
    public function all_sentakbtn_click_flag()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_all_sentakbtn_click_flag);
    }

    /**
     * エンティティ化された入力画面初期表示時コースコードを返します。
     * @return string エンティティ化された入力画面初期表示時コースコード
     */
    public function init_course_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_init_course_cd_sel);
    }

    /**
     * エンティティ化された入力画面初期表示時プランコードを返します。
     * @return string エンティティ化された入力画面初期表示時タイプコード
     */
    public function init_plan_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_init_plan_cd_sel);
    }

    /**
     * エンティティ化されたコースコード選択値を返します。
     * @return string エンティティ化されたコースコード選択値
     */
    public function course_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_course_cd_sel);
    }

    /**
     * エンティティ化されたプランコード選択値を返します。
     * @return string エンティティ化されたプランコード選択値
     */
    public function plan_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_plan_cd_sel);
    }

    /**
     * エンティティ化されたコース表示フラグリストを返します。
     * @return array エンティティ化されたコース表示フラグリスト
     */
    public function course_view_flag()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_course_view_flag);
    }

    /**
     * エンティティ化されたコース全表示ボタンフラグを返します。
     * @return string エンティティ化されたコース全表示ボタンフラグ
     */
    public function course_allbtn_flag()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_course_allbtn_flag);
    }

    /**
     * エンティティ化されたプラン表示フラグリストを返します。
     * @return array エンティティ化されたプラン表示フラグリスト
     */
    public function plan_view_flag()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_plan_view_flag);
    }

    /**
     * エンティティ化されたエアコン有無フラグ選択値を返します。
     * @return string エンティティ化されたエアコン有無フラグ選択値
     */
    public function aircon_exist_flag_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_aircon_exist_flag_sel);
    }

    /**
     * エンティティ化された出発エリアコード選択値を返します。
     * @return string エンティティ化された出発エリアコード選択値
     */
    public function from_area_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_area_cd_sel);
    }

    /**
     * エンティティ化された出発エリアコードリストを返します。
     * @return array エンティティ化された出発エリアコードリスト
     */
    public function from_area_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_area_cds);
    }

    /**
     * エンティティ化された出発エリアラベルリストを返します。
     * @return array エンティティ化された出発エリアラベルリスト
     */
    public function from_area_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_area_lbls);
    }

    /**
     * エンティティ化された到着エリアコード選択値を返します。
     * @return string エンティティ化された到着エリアコード選択値
     */
    public function to_area_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_to_area_cd_sel);
    }

    /**
     * エンティティ化された到着エリアコードリストを返します。
     * @return array エンティティ化された到着エリアコードリスト
     */
    public function to_area_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_to_area_cds);
    }

    /**
     * エンティティ化された到着エリアラベルリストを返します。
     * @return array エンティティ化された到着エリアラベルリスト
     */
    public function to_area_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_to_area_lbls);
    }

    /**
     * エンティティ化された引越予定日年コード選択値を返します。
     * @return string エンティティ化された引越予定日年コード選択値
     */
    public function move_date_year_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_move_date_year_cd_sel);
    }

    /**
     * エンティティ化された引越予定日月コード選択値を返します。
     * @return string エンティティ化された引越予定日月コード選択値
     */
    public function move_date_month_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_move_date_month_cd_sel);
    }

    /**
     * エンティティ化された引越予定日日コード選択値を返します。
     * @return string エンティティ化された引越予定日日コード選択値
     */
    public function move_date_day_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_move_date_day_cd_sel);
    }

    /**
     * エンティティ化された引越予定日年コードリストを返します。
     * @return array エンティティ化された引越予定日年コードリスト
     */
    public function move_date_year_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_move_date_year_cds);
    }

    /**
     * エンティティ化された引越予定日年ラベルリストを返します。
     * @return array エンティティ化された引越予定日年ラベルリスト
     */
    public function move_date_year_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_move_date_year_lbls);
    }

    /**
     * エンティティ化された引越予定日月コードリストを返します。
     * @return array エンティティ化された引越予定日月コードリスト
     */
    public function move_date_month_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_move_date_month_cds);
    }

    /**
     * エンティティ化された引越予定日月ラベルリストを返します。
     * @return array エンティティ化された引越予定日月ラベルリスト
     */
    public function move_date_month_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_move_date_month_lbls);
    }

    /**
     * エンティティ化された引越予定日日コードリストを返します。
     * @return array エンティティ化された引越予定日日コードリスト
     */
    public function move_date_day_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_move_date_day_cds);
    }

    /**
     * エンティティ化された引越予定日日ラベルリストを返します。
     * @return array エンティティ化された引越予定日日ラベルリスト
     */
    public function move_date_day_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_move_date_day_lbls);
    }


    /**
     * エンティティ化された 個人向けサービス ページの選択されたメニュー を返します。
     * @return string エンティティ化された 個人向けサービス ページの選択されたメニュー
     */
    public function personal() {
    	return Sgmov_Component_String::htmlspecialchars($this->menu_personal);
    }


    /**
     * エンティティ化されたキャンペーン名リストを返します。
     * @return array エンティティ化されたキャンペーン名リスト
     */
    public function campaign_names()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_campaign_names);
    }

    /**
     * エンティティ化されたキャンペーン内容リストを返します（改行文字の前にBRタグが挿入されます）。
     * @return array エンティティ化されたキャンペーン内容リスト
     */
    public function campaign_contents()
    {
        return Sgmov_Component_String::nl2br(Sgmov_Component_String::htmlspecialchars($this->raw_campaign_contents));
    }

    /**
     * エンティティ化されたキャンペーン開始日リストを返します。
     * @return array エンティティ化されたキャンペーン開始日リスト
     */
    public function campaign_starts()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_campaign_starts);
    }

    /**
     * エンティティ化されたキャンペーン終了日リストを返します。
     * @return array エンティティ化されたキャンペーン終了日リスト
     */
    public function campaign_ends()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_campaign_ends);
    }
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
     * エンティティ化された他社連携キャンペーンIDを返します。
     * @return array エンティティ化されたキャンペーン開始日リスト
     */
    public function oc_id()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_oc_id);
    }

	    /**
     * エンティティ化された他社連携キャンペーン名称を返します。
     * @return array エンティティ化されたキャンペーン開始日リスト
     */
    public function oc_name()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_oc_name);
    }

    /**
     * エンティティ化された他社連携キャンペーン内容を返します。
     * @return array エンティティ化されたキャンペーン終了日リスト
     */
    public function oc_content()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_oc_content);
    }

}
?>
