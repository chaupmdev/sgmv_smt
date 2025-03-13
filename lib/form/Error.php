<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useComponents(array('String'));
/**#@-*/

 /**
 * エラー情報を格納するフォームです。
 *
 * @package    Form
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Error
{
    /**
     * エラー情報を保持する配列
     * @var array
     */
    public $_errors = array();

    /**
     * エラーが存在するかどうかを確認します。
     * @return boolean エラーが存在する場合は TRUE を、存在しない場合は FALSE を返します。
     */
    public function hasError()
    {
        if (count($this->_errors) > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 指定されたIDに対するエラーが存在するかどうかを確認します。
     * @param object $id チェックするID
     * @return boolean 指定されたIDに対するエラーが存在する場合は TRUE を、
     * 存在しない場合は FALSE を返します。
     */
    public function hasErrorForId($id)
    {
        if (array_key_exists($id, $this->_errors)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 指定されたIDに対するメッセージを取得します。
     *
     * 出力文字はHTMLエンティティ化されます。
     *
     * @param object $id ID
     * @return string 指定されたIDに対するメッセージ文字列
     */
    public function getMessage($id)
    {
        return Sgmov_Component_String::htmlspecialchars($this->_errors[$id]);
    }

    /**
     * 指定されたID、メッセージのエラー情報を追加します。
     *
     * IDが既に存在する場合は上書きされます。
     *
     * @param string $id エラー項目のID
     * @param string $message エラーメッセージ
     */
    public function addError($id, $message)
    {
        $this->_errors[$id] = $message;
    }
    
    /**
     * 指定されたID、メッセージのエラー情報を削除します。
     *
     * IDが既に存在する場合は上書きされます。
     *
     * @param string $id エラー項目のID
     */
    public function delError($id)
    {
        if($this->hasErrorForId($id)) {
            unset($this->_errors[$id]);
        }
    }
}
?>
