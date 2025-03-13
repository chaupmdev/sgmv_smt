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
 * 期間カレンダー画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Asp013Out
{
    /**
     * 日付リスト
     * @var array
     */
    public $raw_days = array();

    /**
     * 祝日フラグリスト
     * @var array
     */
    public $raw_holiday_flags = array();

    /**
     * 曜日コードリスト
     * @var array
     */
    public $raw_weekday_cds = array();

    /**
     * チェックボックス表示フラグリスト
     * @var array
     */
    public $raw_check_show_flags = array();

    /**
     * 選択日付リスト
     * @var array
     */
    public $raw_sel_days = array();

    /**
     * エンティティ化された日付リストを返します。
     * @return array エンティティ化された日付リスト
     */
    public function days()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_days);
    }

    /**
     * エンティティ化された祝日フラグリストを返します。
     * @return array エンティティ化された祝日フラグリスト
     */
    public function holiday_flags()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_holiday_flags);
    }

    /**
     * エンティティ化された曜日コードリストを返します。
     * @return array エンティティ化された曜日コードリスト
     */
    public function weekday_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_weekday_cds);
    }

    /**
     * エンティティ化されたチェックボックス表示フラグリストを返します。
     * @return array エンティティ化されたチェックボックス表示フラグリスト
     */
    public function check_show_flags()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_check_show_flags);
    }

    /**
     * エンティティ化された選択日付リストを返します。
     * @return array エンティティ化された選択日付リスト
     */
    public function sel_days()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sel_days);
    }

}
?>
