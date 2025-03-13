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
 * コメントマスタ一覧画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Cmm001Out {

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
     * @var array
     */
    public $raw_comment_ids = array();

    /**
     * コメント区分
     * @var array
     */
    public $raw_comment_flgs = array();

    /**
     * コメントタイトル
     * @var array
     */
    public $raw_comment_titles = array();

    /**
     * コメント住所
     * @var array
     */
    public $raw_comment_addresses = array();

    /**
     * コメント氏名
     * @var array
     */
    public $raw_comment_names = array();

    /**
     * コメント営業所
     * @var array
     */
    public $raw_comment_offices = array();

    /**
     * コメントテキスト
     * @var array
     */
    public $raw_comment_texts = array();

    /**
     * コメント掲載開始日
     * @var array
     */
    public $raw_comment_start_dates = array();

    /**
     * コメント掲載終了日
     * @var array
     */
    public $raw_comment_end_dates = array();

    /**
     * 拠点名
     * @var array
     */
    public $raw_center_names = array();

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
    public function comment_ids() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comment_ids);
    }

    /**
     * エンティティ化されたコメントフラグを返します。
     * @return string エンティティ化されたコメントフラグ
     */
    public function comment_flgs() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comment_flgs);
    }

    /**
     * エンティティ化されたコメントタイトルを返します。
     * @return string エンティティ化されたコメントタイトル
     */
    public function comment_titles() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comment_titles);
    }

    /**
     * エンティティ化されたコメント住所を返します。
     * @return string エンティティ化されたコメント住所
     */
    public function comment_addresses() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comment_addresses);
    }

    /**
     * エンティティ化されたコメント氏名を返します。
     * @return string エンティティ化されたコメント氏名
     */
    public function comment_names() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comment_names);
    }

    /**
     * エンティティ化されたコメント営業所を返します。
     * @return string エンティティ化されたコメント営業所
     */
    public function comment_offices() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comment_offices);
    }

    /**
     * エンティティ化されたコメントテキストを返します。
     * @return string エンティティ化されたコメントテキスト
     */
    public function comment_texts() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comment_texts);
    }

    /**
     * エンティティ化されたコメント掲載開始日を返します。
     * @return string エンティティ化されたコメント掲載開始日
     */
    public function comment_start_dates() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comment_start_dates);
    }

    /**
     * エンティティ化されたコメント掲載終了日を返します。
     * @return string エンティティ化されたコメント掲載終了日
     */
    public function comment_end_dates() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_comment_end_dates);
    }

    /**
     * エンティティ化された拠点名を返します。
     * @return string エンティティ化された拠点名
     */
    public function center_names() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_center_names);
    }

}