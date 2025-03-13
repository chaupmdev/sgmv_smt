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
 * ツアー会社マスタ設定画面の出力フォームです。
 * TODO TwigやSmartyなどのテンプレートエンジンの導入を検討する
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Ata002Out {

    /**
     * 本社ユーザーフラグ
     * @var string
     */
    public $raw_honsha_user_flag = '';

    /**
     * 船名ID
     * @var string
     */
    public $raw_travel_agency_id = '';

    /**
     * 船名コード
     * @var string
     */
    public $raw_travel_agency_cd = '';

    /**
     * 船名
     * @var string
     */
    public $raw_travel_agency_name = '';

    /**
     * エンティティ化された本社ユーザーフラグを返します。
     * @return string エンティティ化された本社ユーザーフラグ
     */
    public function honsha_user_flag() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_honsha_user_flag);
    }

    /**
     * エンティティ化された船名IDを返します。
     * @return string エンティティ化された船名ID
     */
    public function travel_agency_id() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_agency_id);
    }

    /**
     * エンティティ化された船名コードを返します。
     * @return string エンティティ化された船名コード
     */
    public function travel_agency_cd() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_agency_cd);
    }

    /**
     * エンティティ化された船名を返します。
     * @return string エンティティ化された船名
     */
    public function travel_agency_name() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_travel_agency_name);
    }
}