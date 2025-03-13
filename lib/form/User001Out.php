<?php
/**
 * @package    ClassDefFile
 * @author     GIapLN FPT Software
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useComponents(array('String'));
/**#@-*/


class Sgmov_Form_User001Out
{

    /**
     * メールアドレス
     * @var string
     */
    public $raw_email = '';

    /**
     * メールアドレス確認
     * @var string
     */
    public $raw_email_confirm = '';

    /**
     * エンティティ化されたお問い合わせ種類コード選択値を返します。
     * @return string エンティティ化されたお問い合わせ種類コード選択値
     */
    public function email()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_email);
    }

    /**
     * エンティティ化されたＳＧムービングからの回答コード選択値を返します。
     * @return string エンティティ化されたＳＧムービングからの回答コード選択値
     */
    public function email_confirm()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_email_confirm);
    }
}
?>
