<?php
/**
 * @package    ClassDefFile
 * @author     自動生成
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useComponents(array('String'));
Sgmov_Lib::useForms(array('Bpn002Out'));
/**#@-*/

/**
 * 入力画面の出力フォームです。
 * TODO TwigやSmartyなどのテンプレートエンジンの導入を検討する
 * @package    Form
 * @author     自動生成
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Bpn003Out extends Sgmov_Form_Bpn002Out {
    
    /**
     * 選択イベント名
     * @var string
     */
    // public $raw_event_cd_sel_nm;
    
    /**
     * 選択ブース番号名
     * @var string
     */
    // public $raw_building_booth_id_sel_nm;
    
    
    /**
     * エンティティ化されたイベントコード選択値を返します。
     * @return string エンティティ化された都道府県コード選択値
     */
    // public function raw_event_cd_sel_nm() {
    //    return Sgmov_Component_String::htmlspecialchars($this->raw_event_cd_sel_nm);
    // }
    
    // public function building_booth_id_sel_nm() {
    //     return Sgmov_Component_String::htmlspecialchars($this->raw_building_booth_id_sel_nm);
    // }
}