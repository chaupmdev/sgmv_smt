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
class Sgmov_Form_Mve005Out
{
    /**
     * お名前
     * @var string
     */
    public $raw_name = '';

    /**
     * フリガナ
     * @var string
     */
    public $raw_furigana = '';

    /**
     * 電話番号
     * @var string
     */
    public $raw_tel = '';

    /**
     * 電話種類コード選択値
     * @var string
     */
    public $raw_tel_type_cd_sel = '';

    /**
     * 電話種類その他
     * @var string
     */
    public $raw_tel_other = '';

    /**
     * 電話連絡可能コード選択値
     * @var string
     */
    public $raw_contact_available_cd_sel = '';

    /**
     * 電話連絡可能開始時刻コード選択値
     * @var string
     */
    public $raw_contact_start_cd_sel = '';

    /**
     * 電話連絡可能開始時刻コードリスト
     * @var array
     */
    public $raw_contact_start_cds = array();

    /**
     * 電話連絡可能開始時刻ラベルリスト
     * @var array
     */
    public $raw_contact_start_lbls = array();

    /**
     * 電話連絡可能終了時刻コード選択値
     * @var string
     */
    public $raw_contact_end_cd_sel = '';

    /**
     * 電話連絡可能終了時刻コードリスト
     * @var array
     */
    public $raw_contact_end_cds = array();

    /**
     * 電話連絡可能終了時刻ラベルリスト
     * @var array
     */
    public $raw_contact_end_lbls = array();

    /**
     * メールアドレス
     * @var string
     */
    public $raw_mail = '';

    /**
     * 備考
     * @var string
     */
    public $raw_comment = '';

    /**
     * エンティティ化されたお名前を返します。
     * @return string エンティティ化されたお名前
     */
    public function name()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_name);
    }

    /**
     * エンティティ化されたフリガナを返します。
     * @return string エンティティ化されたフリガナ
     */
    public function furigana()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_furigana);
    }

    /**
     * エンティティ化された電話番号を返します。
     * @return string エンティティ化された電話番号
     */
    public function tel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel);
    }

    /**
     * エンティティ化された電話種類コード選択値を返します。
     * @return string エンティティ化された電話種類コード選択値
     */
    public function tel_type_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel_type_cd_sel);
    }

    /**
     * エンティティ化された電話種類その他を返します。
     * @return string エンティティ化された電話種類その他
     */
    public function tel_other()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_tel_other);
    }

    /**
     * エンティティ化された電話連絡可能コード選択値を返します。
     * @return string エンティティ化された電話連絡可能コード選択値
     */
    public function contact_available_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_available_cd_sel);
    }

    /**
     * エンティティ化された電話連絡可能開始時刻コード選択値を返します。
     * @return string エンティティ化された電話連絡可能開始時刻コード選択値
     */
    public function contact_start_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_start_cd_sel);
    }

    /**
     * エンティティ化された電話連絡可能開始時刻コードリストを返します。
     * @return array エンティティ化された電話連絡可能開始時刻コードリスト
     */
    public function contact_start_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_start_cds);
    }

    /**
     * エンティティ化された電話連絡可能開始時刻ラベルリストを返します。
     * @return array エンティティ化された電話連絡可能開始時刻ラベルリスト
     */
    public function contact_start_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_start_lbls);
    }

    /**
     * エンティティ化された電話連絡可能終了時刻コード選択値を返します。
     * @return string エンティティ化された電話連絡可能終了時刻コード選択値
     */
    public function contact_end_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_end_cd_sel);
    }

    /**
     * エンティティ化された電話連絡可能終了時刻コードリストを返します。
     * @return array エンティティ化された電話連絡可能終了時刻コードリスト
     */
    public function contact_end_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_end_cds);
    }

    /**
     * エンティティ化された電話連絡可能終了時刻ラベルリストを返します。
     * @return array エンティティ化された電話連絡可能終了時刻ラベルリスト
     */
    public function contact_end_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_contact_end_lbls);
    }

    /**
     * エンティティ化されたメールアドレスを返します。
     * @return string エンティティ化されたメールアドレス
     */
    public function mail()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_mail);
    }

    /**
     * エンティティ化された備考を返します。
     * @return string エンティティ化された備考
     */
    public function comment()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comment);
    }

}
?>
