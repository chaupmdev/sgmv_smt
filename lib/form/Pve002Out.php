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
 * 訪問見積確認画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Pve002Out
{
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

//    /**
//     * 概算見積コース（名称）
//     * @var string
//     */
//    public $raw_pre_course_name = '';
//
//    /**
//     * 概算見積プラン（名称）
//     * @var string
//     */
//    public $raw_pre_plan_name = '';

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
     * 概算見積出発エリア（名称）
     * @var string
     */
    public $raw_pre_from_area_name = '';

//    /**
//     * 概算見積到着エリア（名称）
//     * @var string
//     */
//    public $raw_pre_to_area_name = '';
//
//    /**
//     * 概算見積引越予定日
//     * @var string
//     */
//    public $raw_pre_move_date = '';

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
     * 個人向けサービス ページの選択されたメニュー
     * @var string
     */
    public $raw_menu_personal = "";

    /**
     * マンション
     * @var string
     */
    public $raw_apartment_name = "";


    /**
     * 訪問見積第一希望日
     * @var string
     */
    public $raw_visit_date1 = '';

    /**
     * 訪問見積第二希望日
     * @var string
     */
    public $raw_visit_date2 = '';

    /**
     * 現住所郵便番号
     * @var string
     */
    public $raw_cur_zip = '';

    /**
     * 現住所都道府県住所
     * @var string
     */
    public $raw_cur_address_all = '';

    /**
     * 現住所エレベーターコード
     * @var string
     */
    public $raw_cur_elevator = '';

    /**
     * 現住所階数
     * @var string
     */
    public $raw_cur_floor = '';

    /**
     * 現住所住居前道幅
     * @var string
     */
    public $raw_cur_road = '';

    /**
     * 新住所郵便番号
     * @var string
     */
    public $raw_new_zip = '';

    /**
     * 新住所都道府県住所
     * @var string
     */
    public $raw_new_address_all = '';

    /**
     * 新住所エレベーターコード
     * @var string
     */
    public $raw_new_elevator = '';

    /**
     * 新住所階数
     * @var string
     */
    public $raw_new_floor = '';

    /**
     * 新住所住居前道幅
     * @var string
     */
    public $raw_new_road = '';

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
     * 電話番号
     * @var string
     */
    public $raw_tel = '';

    /**
     * 電話種類
     * @var string
     */
    public $raw_tel_type = '';

    /**
     * 電話種類その他
     * @var string
     */
    public $raw_tel_other = '';

    /**
     * 電話連絡可能
     * @var string
     */
    public $raw_contact_available = '';

    /**
     * 電話連絡可能開始時刻
     * @var string
     */
    public $raw_contact_start = '';

    /**
     * 電話連絡可能終了時刻
     * @var string
     */
    public $raw_contact_end = '';

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

	 /**
     * エンティティ化された他社連携キャンペーンIDを返します。
     * @return array エンティティ化された他社連携キャンペーン名称
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

//    /**
//     * エンティティ化された概算見積コース（名称）を返します。
//     * @return string エンティティ化された概算見積コース（名称）
//     */
//    public function pre_course_name()
//    {
//        return Sgmov_Component_String::htmlspecialchars($this->raw_pre_course_name);
//    }
//
//    /**
//     * エンティティ化された概算見積プラン（名称）を返します。
//     * @return string エンティティ化された概算見積プラン（名称）
//     */
//    public function pre_plan_name()
//    {
//        return Sgmov_Component_String::htmlspecialchars($this->raw_pre_plan_name);
//    }

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

//    /**
//     * エンティティ化された概算見積出発エリア（名称）を返します。
//     * @return string エンティティ化された概算見積出発エリア（名称）
//     */
//    public function pre_from_area_name()
//    {
//        return Sgmov_Component_String::htmlspecialchars($this->raw_pre_from_area_name);
//    }
//
//    /**
//     * エンティティ化された概算見積到着エリア（名称）を返します。
//     * @return string エンティティ化された概算見積到着エリア（名称）
//     */
//    public function pre_to_area_name()
//    {
//        return Sgmov_Component_String::htmlspecialchars($this->raw_pre_to_area_name);
//    }

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
     * エンティティ化されたコースを返します。
     * @return string エンティティ化されたコース
     */
    public function course()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_course);
    }

    /**
     * エンティティ化されたプランを返します。
     * @return string エンティティ化されたプラン
     */
    public function plan()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_plan);
    }

    /**
     * エンティティ化された出発エリアを返します。
     * @return string エンティティ化された出発エリア
     */
    public function from_area()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_area);
    }

    /**
     * エンティティ化された到着エリアを返します。
     * @return string エンティティ化された到着エリア
     */
    public function to_area()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_to_area);
    }

    /**
     * エンティティ化された引越予定日を返します。
     * @return string エンティティ化された引越予定日
     */
    public function move_date()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_move_date);
    }


    /**
     * エンティティ化された 個人向けサービス ページから選択されたメニュー を返します。
     * @return string
     */
    public function menu_personal() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_menu_personal);
    }

    /**
     * エンティティ化された マンション名 を返します。
     * @return string エンティティ化された マンション名
     */
    public function apartment_name() {
    	return Sgmov_Component_String::htmlspecialchars($this->raw_apartment_name);
    }

    /**
     * エンティティ化された訪問見積第一希望日を返します。
     * @return string エンティティ化された訪問見積第一希望日
     */
    public function visit_date1()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date1);
    }

    /**
     * エンティティ化された訪問見積第二希望日を返します。
     * @return string エンティティ化された訪問見積第二希望日
     */
    public function visit_date2()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_visit_date2);
    }

    /**
     * エンティティ化された現住所郵便番号を返します。
     * @return string エンティティ化された現住所郵便番号
     */
    public function cur_zip()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_zip);
    }

    /**
     * エンティティ化された現住所都道府県住所を返します。
     * @return string エンティティ化された現住所都道府県住所
     */
    public function cur_address_all()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_address_all);
    }

    /**
     * エンティティ化された現住所エレベーターコードを返します。
     * @return string エンティティ化された現住所エレベーターコード
     */
    public function cur_elevator()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_elevator);
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
     * エンティティ化された現住所住居前道幅を返します。
     * @return string エンティティ化された現住所住居前道幅
     */
    public function cur_road()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_road);
    }

    /**
     * エンティティ化された新住所郵便番号を返します。
     * @return string エンティティ化された新住所郵便番号
     */
    public function new_zip()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_new_zip);
    }

    /**
     * エンティティ化された新住所都道府県住所を返します。
     * @return string エンティティ化された新住所都道府県住所
     */
    public function new_address_all()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_new_address_all);
    }

    /**
     * エンティティ化された新住所エレベーターコードを返します。
     * @return string エンティティ化された新住所エレベーターコード
     */
    public function new_elevator()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_new_elevator);
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
     * エンティティ化された新住所住居前道幅を返します。
     * @return string エンティティ化された新住所住居前道幅
     */
    public function new_road()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_new_road);
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
     * エンティティ化された電話番号を返します。
     * @return string エンティティ化された電話番号
     */
    public function tel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel);
    }

    /**
     * エンティティ化された電話種類を返します。
     * @return string エンティティ化された電話種類
     */
    public function tel_type()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel_type);
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
     * エンティティ化された電話連絡可能を返します。
     * @return string エンティティ化された電話連絡可能
     */
    public function contact_available()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_available);
    }

    /**
     * エンティティ化された電話連絡可能開始時刻を返します。
     * @return string エンティティ化された電話連絡可能開始時刻
     */
    public function contact_start()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_start);
    }

    /**
     * エンティティ化された電話連絡可能終了時刻を返します。
     * @return string エンティティ化された電話連絡可能終了時刻
     */
    public function contact_end()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_end);
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
     * エンティティ化された備考を返します（改行文字の前にBRタグが挿入されます）。
     * @return string エンティティ化された備考
     */
    public function comment()
    {
        return Sgmov_Component_String::nl2br(Sgmov_Component_String::htmlspecialchars($this->raw_comment));
    }

}
?>
