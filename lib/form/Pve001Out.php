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
Sgmov_Lib::useServices(array("Apartment"));

/**#@-*/

 /**
 * 訪問見積入力画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Pve001Out
{

	/**
	 * マンション サービス
	 * @var Sgmov_Service_Apartment
	 */
	public $_ApartmentService;


    /**
     * 概算見積存在フラグ
     * @var string
     */
    public $raw_pre_exist_flag = '';

    /**
     * 概算見積コース
     * @var string
     */
    public $raw_pre_course = '';

    /**
     * 概算見積プラン
     * @var string
     */
    public $raw_pre_plan = '';

    /**
     * 概算見積エアコン有無
     * @var string
     */
    public $raw_pre_aircon_exist = '';

    /**
     * 概算見積出発エリア
     * @var string
     */
    public $raw_pre_from_area = '';

    /**
     * 概算見積到着エリア
     * @var string
     */
    public $raw_pre_to_area = '';

    /**
     * 概算見積引越予定日
     * @var string
     */
    public $raw_pre_move_date = '';

    /**
     * 概算見積金額
     * @var string
     */
    public $raw_pre_estimate_price = '';

    /**
     * 概算見積基本料金
     * @var string
     */
    public $raw_pre_estimate_base_price = '';

    /**
     * 概算見積適用割引キャンペーン名リスト
     * @var array
     */
    public $raw_pre_cam_discount_names = array();

    /**
     * 概算見積適用割引キャンペーン内容リスト
     * @var array
     */
    public $raw_pre_cam_discount_contents = array();

    /**
     * 概算見積適用割引キャンペーン開始日リスト
     * @var array
     */
    public $raw_pre_cam_discount_starts = array();

    /**
     * 概算見積適用割引キャンペーン終了日リスト
     * @var array
     */
    public $raw_pre_cam_discount_ends = array();

    /**
     * 概算見積適用割引キャンペーン金額リスト
     * @var array
     */
    public $raw_pre_cam_discount_prices = array();

    /**
     * 概算見積適用閑散繁忙期キャンペーン名リスト
     * @var array
     */
    public $raw_pre_cam_kansanhanbo_names = array();

    /**
     * 概算見積適用閑散繁忙期キャンペーン内容リスト
     * @var array
     */
    public $raw_pre_cam_kansanhanbo_contents = array();

    /**
     * 概算見積適用閑散繁忙期キャンペーン開始日リスト
     * @var array
     */
    public $raw_pre_cam_kansanhanbo_starts = array();

    /**
     * 概算見積適用閑散繁忙期キャンペーン終了日リスト
     * @var array
     */
    public $raw_pre_cam_kansanhanbo_ends = array();

    /**
     * 概算見積適用閑散繁忙期キャンペーン金額リスト
     * @var array
     */
    public $raw_pre_cam_kansanhanbo_prices = array();

    /**
     * タイプコード
     * @var string
     */
    public $raw_type_cd = '';

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
     * 個人向けサービス ページ の選択れた メニュー<br />
     * ( 'ladies' / 'moving' / 'transport' / 'overseas' / 'voyage' / 'voyage' / '': (別ページから遷移した時) )
     * @var string
     */
    public $raw_menu_personal = "";

    /**
     * マンション名 コード 選択値
     * @var string
     */
    public $raw_apartment_cd_sel = '';
    /**
     * マンション名 コード リスト
     * @var array
     */
    public $raw_apartment_cds = array();
    /**
     * マンション名 ラベル リスト
     * @var array
     */
    public $raw_apartment_lbls = array();


    /**
     * 訪問見積第一希望日年コード選択値
     * @var string
     */
    public $raw_visit_date1_year_cd_sel = '';

    /**
     * 訪問見積第一希望日月コード選択値
     * @var string
     */
    public $raw_visit_date1_month_cd_sel = '';

    /**
     * 訪問見積第一希望日日コード選択値
     * @var string
     */
    public $raw_visit_date1_day_cd_sel = '';

    /**
     * 訪問見積第一希望日年コードリスト
     * @var array
     */
    public $raw_visit_date1_year_cds = array();

    /**
     * 訪問見積第一希望日年ラベルリスト
     * @var array
     */
    public $raw_visit_date1_year_lbls = array();

    /**
     * 訪問見積第一希望日月コードリスト
     * @var array
     */
    public $raw_visit_date1_month_cds = array();

    /**
     * 訪問見積第一希望日月ラベルリスト
     * @var array
     */
    public $raw_visit_date1_month_lbls = array();

    /**
     * 訪問見積第一希望日日コードリスト
     * @var array
     */
    public $raw_visit_date1_day_cds = array();

    /**
     * 訪問見積第一希望日日ラベルリスト
     * @var array
     */
    public $raw_visit_date1_day_lbls = array();

    /**
     * 訪問見積第二希望日年コード選択値
     * @var string
     */
    public $raw_visit_date2_year_cd_sel = '';

    /**
     * 訪問見積第二希望日月コード選択値
     * @var string
     */
    public $raw_visit_date2_month_cd_sel = '';

    /**
     * 訪問見積第二希望日日コード選択値
     * @var string
     */
    public $raw_visit_date2_day_cd_sel = '';

    /**
     * 訪問見積第二希望日年コードリスト
     * @var array
     */
    public $raw_visit_date2_year_cds = array();

    /**
     * 訪問見積第二希望日年ラベルリスト
     * @var array
     */
    public $raw_visit_date2_year_lbls = array();

    /**
     * 訪問見積第二希望日月コードリスト
     * @var array
     */
    public $raw_visit_date2_month_cds = array();

    /**
     * 訪問見積第二希望日月ラベルリスト
     * @var array
     */
    public $raw_visit_date2_month_lbls = array();

    /**
     * 訪問見積第二希望日日コードリスト
     * @var array
     */
    public $raw_visit_date2_day_cds = array();

    /**
     * 訪問見積第二希望日日ラベルリスト
     * @var array
     */
    public $raw_visit_date2_day_lbls = array();

    /**
     * 現住所郵便番号1
     * @var string
     */
    public $raw_cur_zip1 = '';

    /**
     * 現住所郵便番号2
     * @var string
     */
    public $raw_cur_zip2 = '';

    /**
     * 現住所都道府県コード選択値
     * @var string
     */
    public $raw_cur_pref_cd_sel = '';

    /**
     * 現住所都道府県コードリスト
     * @var array
     */
    public $raw_cur_pref_cds = array();

    /**
     * 現住所都道府県ラベルリスト
     * @var array
     */
    public $raw_cur_pref_lbls = array();

    /**
     * 現住所住所
     * @var string
     */
    public $raw_cur_address = '';

    /**
     * 現住所エレベーターコード選択値
     * @var string
     */
    public $raw_cur_elevator_cd_sel = '';

    /**
     * 現住所階数
     * @var string
     */
    public $raw_cur_floor = '';

    /**
     * 現住所住居前道幅コード選択値
     * @var string
     */
    public $raw_cur_road_cd_sel = '';

    /**
     * 新住所郵便番号1
     * @var string
     */
    public $raw_new_zip1 = '';

    /**
     * 新住所郵便番号2
     * @var string
     */
    public $raw_new_zip2 = '';

    /**
     * 新住所都道府県コード選択値
     * @var string
     */
    public $raw_new_pref_cd_sel = '';

    /**
     * 新住所都道府県コードリスト
     * @var array
     */
    public $raw_new_pref_cds = array();

    /**
     * 新住所都道府県ラベルリスト
     * @var array
     */
    public $raw_new_pref_lbls = array();

    /**
     * 新住所住所
     * @var string
     */
    public $raw_new_address = '';

    /**
     * 新住所エレベーターコード選択値
     * @var string
     */
    public $raw_new_elevator_cd_sel = '';

    /**
     * 新住所階数
     * @var string
     */
    public $raw_new_floor = '';

    /**
     * 新住所住居前道幅コード選択値
     * @var string
     */
    public $raw_new_road_cd_sel = '';

    /**
     * お名前
     * @var string
     */
    public $raw_name = '';

    /**
     * フリガナ
     * @var string
     */
    public $raw_furigana = '';

    /**
     * 電話番号1
     * @var string
     */
    public $raw_tel1 = '';

    /**
     * 電話番号2
     * @var string
     */
    public $raw_tel2 = '';

    /**
     * 電話番号3
     * @var string
     */
    public $raw_tel3 = '';

    /**
     * 電話種類コード選択値
     * @var string
     */
    public $raw_tel_type_cd_sel = '';

    /**
     * 電話種類その他
     * @var string
     */
    public $raw_tel_other = '';

    /**
     * 電話連絡可能コード選択値
     * @var string
     */
    public $raw_contact_available_cd_sel = '';

    /**
     * 電話連絡可能開始時刻コード選択値
     * @var string
     */
    public $raw_contact_start_cd_sel = '';

    /**
     * 電話連絡可能開始時刻コードリスト
     * @var array
     */
    public $raw_contact_start_cds = array();

    /**
     * 電話連絡可能開始時刻ラベルリスト
     * @var array
     */
    public $raw_contact_start_lbls = array();

    /**
     * 電話連絡可能終了時刻コード選択値
     * @var string
     */
    public $raw_contact_end_cd_sel = '';

    /**
     * 電話連絡可能終了時刻コードリスト
     * @var array
     */
    public $raw_contact_end_cds = array();

    /**
     * 電話連絡可能終了時刻ラベルリスト
     * @var array
     */
    public $raw_contact_end_lbls = array();

    /**
     * メールアドレス
     * @var string
     */
    public $raw_mail = '';

    /**
     * 備考
     * @var string
     */
    public $raw_comment = '';

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


    public function __construct() {

    	$this->_ApartmentService = new Sgmov_Service_Apartment();

    }

	 /**
     * エンティティ化された他社連携キャンペーンIDを返します。
     * @return array エンティティ化された他社連携キャンペーンID
     */
    public function oc_id()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_oc_id);
    }

	 /**
     * エンティティ化された他社連携キャンペーン名称を返します。
     * @return array エンティティ化された他社連携キャンペーン名称
     */
    public function oc_name()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_oc_name);
    }

	 /**
     * エンティティ化された他社連携キャンペーン内容を返します。
     * @return array エンティティ化された他社連携キャンペーン内容
     */
    public function oc_content()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_oc_content);
    }


    /**
     * エンティティ化された概算見積存在フラグを返します。
     * @return string エンティティ化された概算見積存在フラグ
     */
    public function pre_exist_flag()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_pre_exist_flag);
    }

    /**
     * エンティティ化された概算見積コースを返します。
     * @return string エンティティ化された概算見積コース
     */
    public function pre_course()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_pre_course);
    }

    /**
     * エンティティ化された概算見積プランを返します。
     * @return string エンティティ化された概算見積プラン
     */
    public function pre_plan()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_pre_plan);
    }

    /**
     * エンティティ化された概算見積エアコン有無を返します。
     * @return string エンティティ化された概算見積エアコン有無
     */
    public function pre_aircon_exist()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_pre_aircon_exist);
    }

    /**
     * エンティティ化された概算見積出発エリアを返します。
     * @return string エンティティ化された概算見積出発エリア
     */
    public function pre_from_area()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_pre_from_area);
    }

    /**
     * エンティティ化された概算見積到着エリアを返します。
     * @return string エンティティ化された概算見積到着エリア
     */
    public function pre_to_area()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_pre_to_area);
    }

    /**
     * エンティティ化された概算見積引越予定日を返します。
     * @return string エンティティ化された概算見積引越予定日
     */
    public function pre_move_date()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_pre_move_date);
    }

    /**
     * エンティティ化された概算見積金額を返します。
     * @return string エンティティ化された概算見積金額
     */
    public function pre_estimate_price()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_pre_estimate_price);
    }

    /**
     * エンティティ化された概算見積基本料金を返します。
     * @return string エンティティ化された概算見積基本料金
     */
    public function pre_estimate_base_price()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_pre_estimate_base_price);
    }

    /**
     * エンティティ化された概算見積適用割引キャンペーン名リストを返します。
     * @return array エンティティ化された概算見積適用割引キャンペーン名リスト
     */
    public function pre_cam_discount_names()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_pre_cam_discount_names);
    }

    /**
     * エンティティ化された概算見積適用割引キャンペーン内容リストを返します（改行文字の前にBRタグが挿入されます）。
     * @return array エンティティ化された概算見積適用割引キャンペーン内容リスト
     */
    public function pre_cam_discount_contents()
    {
        return Sgmov_Component_String::nl2br(Sgmov_Component_String::htmlspecialchars($this->raw_pre_cam_discount_contents));
    }

    /**
     * エンティティ化された概算見積適用割引キャンペーン開始日リストを返します。
     * @return array エンティティ化された概算見積適用割引キャンペーン開始日リスト
     */
    public function pre_cam_discount_starts()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_pre_cam_discount_starts);
    }

    /**
     * エンティティ化された概算見積適用割引キャンペーン終了日リストを返します。
     * @return array エンティティ化された概算見積適用割引キャンペーン終了日リスト
     */
    public function pre_cam_discount_ends()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_pre_cam_discount_ends);
    }

    /**
     * エンティティ化された概算見積適用割引キャンペーン金額リストを返します。
     * @return array エンティティ化された概算見積適用割引キャンペーン金額リスト
     */
    public function raw_pre_cam_discount_prices()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_pre_cam_discount_prices);
    }

    /**
     * エンティティ化された概算見積適用閑散繁忙期キャンペーン名リストを返します。
     * @return array エンティティ化された概算見積適用閑散繁忙期キャンペーン名リスト
     */
    public function pre_cam_kansanhanbo_names()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_pre_cam_kansanhanbo_names);
    }

    /**
     * エンティティ化された概算見積適用閑散繁忙期キャンペーン内容リストを返します（改行文字の前にBRタグが挿入されます）。
     * @return array エンティティ化された概算見積適用閑散繁忙期キャンペーン内容リスト
     */
    public function pre_cam_kansanhanbo_contents()
    {
        return Sgmov_Component_String::nl2br(Sgmov_Component_String::htmlspecialchars($this->raw_pre_cam_kansanhanbo_contents));
    }

    /**
     * エンティティ化された概算見積適用閑散繁忙期キャンペーン開始日リストを返します。
     * @return array エンティティ化された概算見積適用閑散繁忙期キャンペーン開始日リスト
     */
    public function pre_cam_kansanhanbo_starts()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_pre_cam_kansanhanbo_starts);
    }

    /**
     * エンティティ化された概算見積適用閑散繁忙期キャンペーン終了日リストを返します。
     * @return array エンティティ化された概算見積適用閑散繁忙期キャンペーン終了日リスト
     */
    public function pre_cam_kansanhanbo_ends()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_pre_cam_kansanhanbo_ends);
    }

    /**
     * エンティティ化された概算見積適用閑散繁忙期キャンペーン金額リストを返します。
     * @return array エンティティ化された概算見積適用閑散繁忙期キャンペーン金額リスト
     */
    public function raw_pre_cam_kansanhanbo_prices()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_pre_cam_kansanhanbo_prices);
    }

    /**
     * エンティティ化されたタイプコードを返します。
     * @return string エンティティ化されたタイプコード
     */
    public function type_cd()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_type_cd);
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
     * エンティティ化された 個人向けサービス ページ の選択れた メニュー
     * @return string エンティティ化された 個人向けサービス ページ の選択れた メニュー
     */
    public function menu_personal() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_menu_personal);
    }

    /**
     * エンティティ化された マンション名 コード選択値 を返します。
     * @return エンティティ化された マンション名 コード選択値
     */
    public function apartment_cd_sel() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_apartment_cd_sel);
    }
    /**
     * エンティティ化された マンション名 コードリスト を返します。
     * @return エンティティ化された マンション名 コードリスト
     */
    public function apartment_cds() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_apartment_cds);
    }
    /**
     * エンティティ化された マンション名 ラベルリスト を返します。
     * @return array エンティティ化された マンション名 ラベルリスト
     */
    public function apartment_lbls() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_apartment_lbls);
    }


    /**
     * エンティティ化された訪問見積第一希望日年コード選択値を返します。
     * @return string エンティティ化された訪問見積第一希望日年コード選択値
     */
    public function visit_date1_year_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date1_year_cd_sel);
    }

    /**
     * エンティティ化された訪問見積第一希望日月コード選択値を返します。
     * @return string エンティティ化された訪問見積第一希望日月コード選択値
     */
    public function visit_date1_month_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date1_month_cd_sel);
    }

    /**
     * エンティティ化された訪問見積第一希望日日コード選択値を返します。
     * @return string エンティティ化された訪問見積第一希望日日コード選択値
     */
    public function visit_date1_day_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date1_day_cd_sel);
    }

    /**
     * エンティティ化された訪問見積第一希望日年コードリストを返します。
     * @return array エンティティ化された訪問見積第一希望日年コードリスト
     */
    public function visit_date1_year_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date1_year_cds);
    }

    /**
     * エンティティ化された訪問見積第一希望日年ラベルリストを返します。
     * @return array エンティティ化された訪問見積第一希望日年ラベルリスト
     */
    public function visit_date1_year_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date1_year_lbls);
    }

    /**
     * エンティティ化された訪問見積第一希望日月コードリストを返します。
     * @return array エンティティ化された訪問見積第一希望日月コードリスト
     */
    public function visit_date1_month_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date1_month_cds);
    }

    /**
     * エンティティ化された訪問見積第一希望日月ラベルリストを返します。
     * @return array エンティティ化された訪問見積第一希望日月ラベルリスト
     */
    public function visit_date1_month_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date1_month_lbls);
    }

    /**
     * エンティティ化された訪問見積第一希望日日コードリストを返します。
     * @return array エンティティ化された訪問見積第一希望日日コードリスト
     */
    public function visit_date1_day_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date1_day_cds);
    }

    /**
     * エンティティ化された訪問見積第一希望日日ラベルリストを返します。
     * @return array エンティティ化された訪問見積第一希望日日ラベルリスト
     */
    public function visit_date1_day_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date1_day_lbls);
    }

    /**
     * エンティティ化された訪問見積第二希望日年コード選択値を返します。
     * @return string エンティティ化された訪問見積第二希望日年コード選択値
     */
    public function visit_date2_year_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date2_year_cd_sel);
    }

    /**
     * エンティティ化された訪問見積第二希望日月コード選択値を返します。
     * @return string エンティティ化された訪問見積第二希望日月コード選択値
     */
    public function visit_date2_month_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date2_month_cd_sel);
    }

    /**
     * エンティティ化された訪問見積第二希望日日コード選択値を返します。
     * @return string エンティティ化された訪問見積第二希望日日コード選択値
     */
    public function visit_date2_day_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date2_day_cd_sel);
    }

    /**
     * エンティティ化された訪問見積第二希望日年コードリストを返します。
     * @return array エンティティ化された訪問見積第二希望日年コードリスト
     */
    public function visit_date2_year_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date2_year_cds);
    }

    /**
     * エンティティ化された訪問見積第二希望日年ラベルリストを返します。
     * @return array エンティティ化された訪問見積第二希望日年ラベルリスト
     */
    public function visit_date2_year_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date2_year_lbls);
    }

    /**
     * エンティティ化された訪問見積第二希望日月コードリストを返します。
     * @return array エンティティ化された訪問見積第二希望日月コードリスト
     */
    public function visit_date2_month_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date2_month_cds);
    }

    /**
     * エンティティ化された訪問見積第二希望日月ラベルリストを返します。
     * @return array エンティティ化された訪問見積第二希望日月ラベルリスト
     */
    public function visit_date2_month_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date2_month_lbls);
    }

    /**
     * エンティティ化された訪問見積第二希望日日コードリストを返します。
     * @return array エンティティ化された訪問見積第二希望日日コードリスト
     */
    public function visit_date2_day_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date2_day_cds);
    }

    /**
     * エンティティ化された訪問見積第二希望日日ラベルリストを返します。
     * @return array エンティティ化された訪問見積第二希望日日ラベルリスト
     */
    public function visit_date2_day_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date2_day_lbls);
    }

    /**
     * エンティティ化された現住所郵便番号1を返します。
     * @return string エンティティ化された現住所郵便番号1
     */
    public function cur_zip1()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_zip1);
    }

    /**
     * エンティティ化された現住所郵便番号2を返します。
     * @return string エンティティ化された現住所郵便番号2
     */
    public function cur_zip2()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_zip2);
    }

    /**
     * エンティティ化された現住所都道府県コード選択値を返します。
     * @return string エンティティ化された現住所都道府県コード選択値
     */
    public function cur_pref_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_pref_cd_sel);
    }

    /**
     * エンティティ化された現住所都道府県コードリストを返します。
     * @return array エンティティ化された現住所都道府県コードリスト
     */
    public function cur_pref_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_pref_cds);
    }

    /**
     * エンティティ化された現住所都道府県ラベルリストを返します。
     * @return array エンティティ化された現住所都道府県ラベルリスト
     */
    public function cur_pref_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_pref_lbls);
    }

    /**
     * エンティティ化された現住所住所を返します。
     * @return string エンティティ化された現住所住所
     */
    public function cur_address()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_address);
    }

    /**
     * エンティティ化された現住所エレベーターコード選択値を返します。
     * @return string エンティティ化された現住所エレベーターコード選択値
     */
    public function cur_elevator_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_elevator_cd_sel);
    }

    /**
     * エンティティ化された現住所階数を返します。
     * @return string エンティティ化された現住所階数
     */
    public function cur_floor()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_floor);
    }

    /**
     * エンティティ化された現住所住居前道幅コード選択値を返します。
     * @return string エンティティ化された現住所住居前道幅コード選択値
     */
    public function cur_road_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_road_cd_sel);
    }

    /**
     * エンティティ化された新住所郵便番号1を返します。
     * @return string エンティティ化された新住所郵便番号1
     */
    public function new_zip1()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_new_zip1);
    }

    /**
     * エンティティ化された新住所郵便番号2を返します。
     * @return string エンティティ化された新住所郵便番号2
     */
    public function new_zip2()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_new_zip2);
    }

    /**
     * エンティティ化された新住所都道府県コード選択値を返します。
     * @return string エンティティ化された新住所都道府県コード選択値
     */
    public function new_pref_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_new_pref_cd_sel);
    }

    /**
     * エンティティ化された新住所都道府県コードリストを返します。
     * @return array エンティティ化された新住所都道府県コードリスト
     */
    public function new_pref_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_new_pref_cds);
    }

    /**
     * エンティティ化された新住所都道府県ラベルリストを返します。
     * @return array エンティティ化された新住所都道府県ラベルリスト
     */
    public function new_pref_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_new_pref_lbls);
    }

    /**
     * エンティティ化された新住所住所を返します。
     * @return string エンティティ化された新住所住所
     */
    public function new_address()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_new_address);
    }

    /**
     * エンティティ化された新住所エレベーターコード選択値を返します。
     * @return string エンティティ化された新住所エレベーターコード選択値
     */
    public function new_elevator_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_new_elevator_cd_sel);
    }

    /**
     * エンティティ化された新住所階数を返します。
     * @return string エンティティ化された新住所階数
     */
    public function new_floor()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_new_floor);
    }

    /**
     * エンティティ化された新住所住居前道幅コード選択値を返します。
     * @return string エンティティ化された新住所住居前道幅コード選択値
     */
    public function new_road_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_new_road_cd_sel);
    }

    /**
     * エンティティ化されたお名前を返します。
     * @return string エンティティ化されたお名前
     */
    public function name()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_name);
    }

    /**
     * エンティティ化されたフリガナを返します。
     * @return string エンティティ化されたフリガナ
     */
    public function furigana()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_furigana);
    }

    /**
     * エンティティ化された電話番号1を返します。
     * @return string エンティティ化された電話番号1
     */
    public function tel1()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel1);
    }

    /**
     * エンティティ化された電話番号2を返します。
     * @return string エンティティ化された電話番号2
     */
    public function tel2()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel2);
    }

    /**
     * エンティティ化された電話番号3を返します。
     * @return string エンティティ化された電話番号3
     */
    public function tel3()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel3);
    }

    /**
     * エンティティ化された電話種類コード選択値を返します。
     * @return string エンティティ化された電話種類コード選択値
     */
    public function tel_type_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel_type_cd_sel);
    }

    /**
     * エンティティ化された電話種類その他を返します。
     * @return string エンティティ化された電話種類その他
     */
    public function tel_other()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel_other);
    }

    /**
     * エンティティ化された電話連絡可能コード選択値を返します。
     * @return string エンティティ化された電話連絡可能コード選択値
     */
    public function contact_available_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_available_cd_sel);
    }

    /**
     * エンティティ化された電話連絡可能開始時刻コード選択値を返します。
     * @return string エンティティ化された電話連絡可能開始時刻コード選択値
     */
    public function contact_start_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_start_cd_sel);
    }

    /**
     * エンティティ化された電話連絡可能開始時刻コードリストを返します。
     * @return array エンティティ化された電話連絡可能開始時刻コードリスト
     */
    public function contact_start_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_start_cds);
    }

    /**
     * エンティティ化された電話連絡可能開始時刻ラベルリストを返します。
     * @return array エンティティ化された電話連絡可能開始時刻ラベルリスト
     */
    public function contact_start_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_start_lbls);
    }

    /**
     * エンティティ化された電話連絡可能終了時刻コード選択値を返します。
     * @return string エンティティ化された電話連絡可能終了時刻コード選択値
     */
    public function contact_end_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_end_cd_sel);
    }

    /**
     * エンティティ化された電話連絡可能終了時刻コードリストを返します。
     * @return array エンティティ化された電話連絡可能終了時刻コードリスト
     */
    public function contact_end_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_end_cds);
    }

    /**
     * エンティティ化された電話連絡可能終了時刻ラベルリストを返します。
     * @return array エンティティ化された電話連絡可能終了時刻ラベルリスト
     */
    public function contact_end_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_end_lbls);
    }

    /**
     * エンティティ化されたメールアドレスを返します。
     * @return string エンティティ化されたメールアドレス
     */
    public function mail()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_mail);
    }

    /**
     * エンティティ化された備考を返します。
     * @return string エンティティ化された備考
     */
    public function comment()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comment);
    }

}
?>