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
 * キャンペーン一覧画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Pcl001Out
{
    /**
     * 出発地方コード
     * @var string
     */
    public $raw_from_region = '';

    /**
     * 出発地方コードリスト
     * @var array
     */
    public $raw_from_region_cds = array();

    /**
     * 出発地方ラベルリスト
     * @var array
     */
    public $raw_from_region_lbls = array();

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
     * キャンペーンコードリスト
     * @var array
     */
    public $raw_campaign_cds = array();

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
     * キャンペーン到着エリア全国フラグリスト
     * @var array
     */
    public $raw_campaign_zenkoku_flags = array();

    /**
     * キャンペーン対象コースコードリスト
     * @var array
     */
    public $raw_campaign_course_cds = array();

    /**
     * キャンペーン対象コースラベルリスト
     * @var array
     */
    public $raw_campaign_course_lbls = array();

    /**
     * キャンペーン対象プランラベルリスト
     * @var array
     */
    public $raw_campaign_plan_lbls = array();

    /**
     * キャンペーン到着地方コードリスト
     * @var array
     */
    public $raw_campaign_region_cds = array();

    /**
     * キャンペーン到着地方ラベルリスト
     * @var array
     */
    public $raw_campaign_region_lbls = array();

    /**
     * キャンペーン到着エリアラベルリスト
     * @var array
     */
    public $raw_campaign_to_area_lbls = array();

    /**
     * エンティティ化された出発地方コードを返します。
     * @return string エンティティ化された出発地方コード
     */
    public function from_region()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_region);
    }

    /**
     * エンティティ化された出発地方コードリストを返します。
     * @return array エンティティ化された出発地方コードリスト
     */
    public function from_region_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_region_cds);
    }

    /**
     * エンティティ化された出発地方ラベルリストを返します。
     * @return array エンティティ化された出発地方ラベルリスト
     */
    public function from_region_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_from_region_lbls);
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
     * エンティティ化されたキャンペーンコードリストを返します。
     * @return array エンティティ化されたキャンペーンコードリスト
     */
    public function campaign_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_campaign_cds);
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
     * エンティティ化されたキャンペーン内容リストを返します。
     * @return array エンティティ化されたキャンペーン内容リスト
     */
    public function campaign_contents()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_campaign_contents);
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
     * エンティティ化されたキャンペーン到着エリア全国フラグリストを返します。
     * @return array エンティティ化されたキャンペーン到着エリア全国フラグリスト
     */
    public function campaign_zenkoku_flags()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_campaign_zenkoku_flags);
    }

    /**
     * エンティティ化されたキャンペーン対象コースコードリストを返します。
     * @return array エンティティ化されたキャンペーン対象コースコードリスト
     */
    public function campaign_course_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_campaign_course_cds);
    }

    /**
     * エンティティ化されたキャンペーン対象コースラベルリストを返します。
     * @return array エンティティ化されたキャンペーン対象コースラベルリスト
     */
    public function campaign_course_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_campaign_course_lbls);
    }

    /**
     * エンティティ化されたキャンペーン対象プランラベルリストを返します。
     * @return array エンティティ化されたキャンペーン対象プランラベルリスト
     */
    public function campaign_plan_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_campaign_plan_lbls);
    }

    /**
     * エンティティ化されたキャンペーン到着地方コードリストを返します。
     * @return array エンティティ化されたキャンペーン到着地方コードリスト
     */
    public function campaign_region_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_campaign_region_cds);
    }

    /**
     * エンティティ化されたキャンペーン到着地方ラベルリストを返します。
     * @return array エンティティ化されたキャンペーン到着地方ラベルリスト
     */
    public function campaign_region_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_campaign_region_lbls);
    }

    /**
     * エンティティ化されたキャンペーン到着エリアラベルリストを返します。
     * @return array エンティティ化されたキャンペーン到着エリアラベルリスト
     */
    public function campaign_to_area_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_campaign_to_area_lbls);
    }

}
?>
