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
 * コメントマスタ画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Cmm002Out {

    /**
     * 本社ユーザーフラグ
     * @var string
     */
    public $raw_honsha_user_flag = '';

    /**
     * 表示種別
     * @var string
     */
    public $raw_sp_list_kind = '';

    /**
     * コメントID
     * @var string
     */
    public $raw_comment_id = '';

    /**
     * コメント区分
     * @var string
     */
    public $raw_comment_flg = '';

    /**
     * コメントタイトル
     * @var string
     */
    public $raw_comment_title = '';

    /**
     * コメント住所
     * @var string
     */
    public $raw_comment_address = '';

    /**
     * コメント氏名
     * @var string
     */
    public $raw_comment_name = '';

    /**
     * コメント営業所
     * @var string
     */
    public $raw_comment_office = '';
    public $raw_comment_office_cds = '';
    public $raw_comment_office_lbls = '';

    /**
     * コメントテキスト
     * @var string
     */
    public $raw_comment_text = '';

    /**
     * コメント写真[1]
     * @var string
     */
    public $raw_comment_file_1 = '';

    /**
     * コメント写真[2]
     * @var string
     */
    public $raw_comment_file_2 = '';

    /**
     * コメント掲載開始日
     * @var string
     */
    public $raw_comment_start_date = '';

    /**
     * コメント掲載終了日
     * @var string
     */
    public $raw_comment_end_date = '';

    /**
     * エンティティ化された本社ユーザーフラグを返します。
     * @return string エンティティ化された本社ユーザーフラグ
     */
    public function honsha_user_flag() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_honsha_user_flag);
    }

    /**
     * エンティティ化された表示種別を返します。
     * @return string エンティティ化された表示種別
     */
    public function sp_list_kind()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sp_list_kind);
    }

    /**
     * エンティティ化されたコメントIDを返します。
     * @return string エンティティ化されたコメントID
     */
    public function comment_id() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comment_id);
    }

    /**
     * エンティティ化されたコメントフラグを返します。
     * @return string エンティティ化されたコメントフラグ
     */
    public function comment_flg() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comment_flg);
    }

    /**
     * エンティティ化されたコメントタイトルを返します。
     * @return string エンティティ化されたコメントタイトル
     */
    public function comment_title() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comment_title);
    }

    /**
     * エンティティ化されたコメント住所を返します。
     * @return string エンティティ化されたコメント住所
     */
    public function comment_address() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comment_address);
    }

    /**
     * エンティティ化されたコメント氏名を返します。
     * @return string エンティティ化されたコメント氏名
     */
    public function comment_name() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comment_name);
    }

    /**
     * エンティティ化されたコメント営業所を返します。
     * @return string エンティティ化されたコメント営業所
     */
    public function comment_office() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comment_office);
    }
    public function comment_office_cds() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comment_office_cds);
    }
    public function comment_office_lbls() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comment_office_lbls);
    }

    /**
     * エンティティ化されたコメントテキストを返します。
     * @return string エンティティ化されたコメントテキスト
     */
    public function comment_text() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comment_text);
    }

    /**
     * エンティティ化されたコメント写真[1]を返します。
     * @return string エンティティ化されたコメント写真[1]
     */
    public function comment_file_1() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comment_file_1);
    }

    /**
     * エンティティ化されたコメント写真[2]を返します。
     * @return string エンティティ化されたコメント写真[2]
     */
    public function comment_file_2() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comment_file_2);
    }

    /**
     * エンティティ化されたコメント掲載開始日を返します。
     * @return string エンティティ化されたコメント掲載開始日
     */
    public function comment_start_date() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comment_start_date);
    }

    /**
     * エンティティ化されたコメント掲載終了日を返します。
     * @return string エンティティ化されたコメント掲載終了日
     */
    public function comment_end_date() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comment_end_date);
    }
}