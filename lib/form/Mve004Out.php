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
class Sgmov_Form_Mve004Out
{
    /**
     * 現住所郵便番号
     * @var string
     */
    public $raw_cur_zip = '';

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
     * 現住所エレベーター有無フラグ選択値
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
     * 新住所郵便番号
     * @var string
     */
    public $raw_new_zip = '';

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
     * 新住所エレベーター有無フラグ選択値
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
     * エンティティ化された現住所郵便番号を返します。
     * @return string エンティティ化された現住所郵便番号
     */
    public function cur_zip()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_cur_zip);
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
     * エンティティ化された現住所エレベーター有無フラグ選択値を返します。
     * @return string エンティティ化された現住所エレベーター有無フラグ選択値
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
     * エンティティ化された新住所郵便番号を返します。
     * @return string エンティティ化された新住所郵便番号
     */
    public function new_zip()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_new_zip);
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
     * エンティティ化された新住所エレベーター有無フラグ選択値を返します。
     * @return string エンティティ化された新住所エレベーター有無フラグ選択値
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

}
?>
