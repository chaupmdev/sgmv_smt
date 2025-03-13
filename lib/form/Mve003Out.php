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
 * 訪問見積入力画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Mve003Out
{
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
     * 現住所郵便番号
     * @var string
     */
    public $raw_cur_zip = '';

    /**
     * 新住所郵便番号
     * @var string
     */
    public $raw_new_zip = '';

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
    public function cargo_area_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cargo_area_lbls);
    }

    /**
     * エンティティ化された到着エリアコード選択値を返します。
     * @return string エンティティ化された到着エリアコード選択値
     */
    public function cargo_area_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cargo_area_cd_sel);
    }

   /**
     * エンティティ化された出発エリアラベルリストを返します。
     * @return array エンティティ化された出発エリアラベルリスト
     */
    public function cargo_area_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cargo_area_cds);
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
     * エンティティ化された現住所郵便番号を返します。
     * @return string エンティティ化された現住所郵便番号
     */
    public function cur_zip()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_zip);
    }

    /**
     * エンティティ化された新住所郵便番号を返します。
     * @return string エンティティ化された新住所郵便番号
     */
    public function new_zip()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_new_zip);
    }

}
?>
