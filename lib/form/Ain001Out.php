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
 * 問合管理一覧画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Ain001Out
{
    /**
     * 本社ユーザーフラグ
     * @var string
     */
    public $raw_honsha_user_flag = '';

    /**
     * フォーム種別表示モード
     * @var string
     */
    public $raw_form_mode = '';

    /**
     * 状況表示モード
     * @var string
     */
    public $raw_status_mode = '';

    /**
     * 状況コード選択値
     * @var string
     */
    public $raw_status_cd_sel = '';

    /**
     * 状況コードリスト
     * @var array
     */
    public $raw_status_cds = array();

    /**
     * 状況ラベルリスト
     * @var array
     */
    public $raw_status_lbls = array();

    /**
     * 更新者
     * @var array
     */
    public $raw_updater_name = array();

    /**
     * 顧客登録情報状況コードリスト
     * @var array
     */
    public $raw_inq_status_cds = array();

    /**
     * 顧客登録情報コードリスト
     * @var array
     */
    public $raw_inq_cds = array();

    /**
     * 顧客登録情報タイムスタンプリスト
     * @var array
     */
    public $raw_inq_timestamps = array();

    /**
     * 顧客登録情報フォーム種別コードリスト
     * @var array
     */
    public $raw_inq_form_cds = array();

    /**
     * 顧客登録情報チェックリスト
     * @var array
     */
    public $raw_inq_checks = array();

    /**
     * 顧客登録情報クレームフラグリスト
     * @var array
     */
    public $raw_inq_claims = array();

    /**
     * 顧客登録情報受信日時リスト
     * @var array
     */
    public $raw_inq_datetimes = array();

    /**
     * 顧客登録情報フォーム種別リスト
     * @var array
     */
    public $raw_inq_forms = array();

    /**
     * 顧客登録情報種類リスト
     * @var array
     */
    public $raw_inq_types = array();

    /**
     * 顧客登録情報名前リスト
     * @var array
     */
    public $raw_inq_names = array();

    /**
     * 顧客登録情報件名リスト
     * @var array
     */
    public $raw_inq_titles = array();

    /**
     * 顧客登録情報詳細URLリスト
     * @var array
     */
    public $raw_inq_detail_urls = array();

    /**
     * エンティティ化された本社ユーザーフラグを返します。
     * @return string エンティティ化された本社ユーザーフラグ
     */
    public function honsha_user_flag()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_honsha_user_flag);
    }

    /**
     * エンティティ化されたフォーム種別表示モードを返します。
     * @return string エンティティ化されたフォーム種別表示モード
     */
    public function form_mode()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_form_mode);
    }

    /**
     * エンティティ化された状況表示モードを返します。
     * @return string エンティティ化された状況表示モード
     */
    public function status_mode()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_status_mode);
    }

    /**
     * エンティティ化された状況コード選択値を返します。
     * @return string エンティティ化された状況コード選択値
     */
    public function status_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_status_cd_sel);
    }

    /**
     * エンティティ化された状況コードリストを返します。
     * @return array エンティティ化された状況コードリスト
     */
    public function status_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_status_cds);
    }

    /**
     * エンティティ化された状況ラベルリストを返します。
     * @return array エンティティ化された状況ラベルリスト
     */
    public function status_lbls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_status_lbls);
    }

    /**
     * エンティティ化された更新者を返します。
     * @return array エンティティ化された更新者
     */
    public function updater_name()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_updater_name);
    }

    /**
     * エンティティ化された顧客登録情報状況コードリストを返します。
     * @return array エンティティ化された顧客登録情報状況コードリスト
     */
    public function inq_status_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_inq_status_cds);
    }

    /**
     * エンティティ化された顧客登録情報コードリストを返します。
     * @return array エンティティ化された顧客登録情報コードリスト
     */
    public function inq_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_inq_cds);
    }

    /**
     * エンティティ化された顧客登録情報タイムスタンプリストを返します。
     * @return array エンティティ化された顧客登録情報タイムスタンプリスト
     */
    public function inq_timestamps()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_inq_timestamps);
    }

    /**
     * エンティティ化された顧客登録情報フォーム種別コードリストを返します。
     * @return array エンティティ化された顧客登録情報フォーム種別コードリスト
     */
    public function inq_form_cds()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_inq_form_cds);
    }

    /**
     * エンティティ化された顧客登録情報チェックリストを返します。
     * @return array エンティティ化された顧客登録情報チェックリスト
     */
    public function inq_checks()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_inq_checks);
    }

    /**
     * エンティティ化された顧客登録情報クレームフラグリストを返します。
     * @return array エンティティ化された顧客登録情報クレームフラグリスト
     */
    public function inq_claims()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_inq_claims);
    }

    /**
     * エンティティ化された顧客登録情報受信日時リストを返します。
     * @return array エンティティ化された顧客登録情報受信日時リスト
     */
    public function inq_datetimes()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_inq_datetimes);
    }

    /**
     * エンティティ化された顧客登録情報フォーム種別リストを返します。
     * @return array エンティティ化された顧客登録情報フォーム種別リスト
     */
    public function inq_forms()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_inq_forms);
    }

    /**
     * エンティティ化された顧客登録情報種類リストを返します。
     * @return array エンティティ化された顧客登録情報種類リスト
     */
    public function inq_types()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_inq_types);
    }

    /**
     * エンティティ化された顧客登録情報名前リストを返します。
     * @return array エンティティ化された顧客登録情報名前リスト
     */
    public function inq_names()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_inq_names);
    }

    /**
     * エンティティ化された顧客登録情報件名リストを返します。
     * @return array エンティティ化された顧客登録情報件名リスト
     */
    public function inq_titles()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_inq_titles);
    }

    /**
     * エンティティ化された顧客登録情報詳細URLリストを返します。
     * @return array エンティティ化された顧客登録情報詳細URLリスト
     */
    public function inq_detail_urls()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_inq_detail_urls);
    }

}
?>
