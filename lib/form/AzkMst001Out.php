<?php
 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useComponents(array('String'));
/**#@-*/

/**
 * 入力画面の出力フォームです。
 * TODO TwigやSmartyなどのテンプレートエンジンの導入を検討する
 * @package    Form
 * @author     自動生成
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_AzkMst001Out {

    /**
     * イベントコード選択値
     * @var string
     */
    public $raw_event_cd_sel = '';

    /**
     * 出展イベントサブ
     * @var string
     */
    public $raw_eventsub_cd_sel = '';

    /**
     * イベント識別子
     * @var string
     */
    public $raw_shikibetsushi = '';

    /**
     * 最大預りCD数
     * @var string
     */
    public $raw_max_azukari_cd = '';

    /**
     * エンティティ化されたイベントサブコード選択値を返します。
     * @return string エンティティ化された都道府県コード選択値
     */
    public function event_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_event_cd_sel);
    }

    /**
     * エンティティ化されたイベントサブコード選択値を返します。
     * @return string エンティティ化された都道府県コード選択値
     */
    public function eventsub_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_eventsub_cd_sel);
    }

    /**
     * エンティティ化されたイベント識別子選択値を返します。
     * @return string エンティティ化された都道府県コード選択値
     */
    public function shikibetsushi() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_shikibetsushi);
    }

    /**
     * エンティティ化された最大預りCD数値を返します。
     * @return string エンティティ化された都道府県コード選択値
     */
    public function max_azukari_cd() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_max_azukari_cd);
    }
}