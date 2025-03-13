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
 * ログイン画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Acm001Out
{
    /**
     * ユーザーアカウント
     * @var string
     */
    public $raw_user_account = '';

    /**
     * エンティティ化されたユーザーアカウントを返します。
     * @return string エンティティ化されたユーザーアカウント
     */
    public function user_account()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_user_account);
    }

}
?>
