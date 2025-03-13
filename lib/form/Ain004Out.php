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
Sgmov_Lib::useForms(array('Pin002Out', 'Pcm002Out', 'Pcs002Out', 'Pem002Out'));
/**#@-*/

 /**
 * 問合管理詳細確認画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Ain004Out
{
    /**
     * 本社ユーザーフラグ
     * @var string
     */
    public $raw_honsha_user_flag = '';

    /**
     * 顧客登録情報受信日時
     * @var string
     */
    public $raw_inq_regist_datetime = '';

    /**
     * 顧客登録情報フォーム種別コード
     * @var string
     */
    public $raw_inq_form_cd = '';

    /**
     * 顧客登録情報フォーム種別
     * @var string
     */
    public $raw_inq_form = '';

    /**
     * 顧客登録情報対応状況
     * @var string
     */
    public $raw_inq_status = '';

    /**
     * 顧客登録情報完了日
     * @var string
     */
    public $raw_inq_done_datetime = '';

    /**
     * 顧客登録情報クレームフラグ
     * @var string
     */
    public $raw_inq_claim_flag = '';

    /**
     * お問い合わせフォーム
     * @var Sgmov_Form_Pin002Out
     */
    public $raw_pin002Out = null;

    /**
     * 法人引越輸送フォーム
     * @var Sgmov_Form_Pcm002Out
     */
    public $raw_pcm002Out = null;

    /**
     * 法人設置輸送フォーム
     * @var Sgmov_Form_Pcs002Out
     */
    public $raw_pcs002Out = null;

    /**
     * 採用エントリーフォーム
     * @var Sgmov_Form_Pem002Out
     */
    public $raw_pem002Out = null;

    /**
     * 対応履歴日時リスト
     * @var array
     */
    public $raw_history_datetimes = array();

    /**
     * 対応履歴更新者リスト
     * @var array
     */
    public $raw_history_updaters = array();

    /**
     * 対応履歴コメントリスト
     * @var array
     */
    public $raw_history_comments = array();

    /**
     * 対応履歴状況変更フラグリスト
     * @var array
     */
    public $raw_history_status_edit_flags = array();

    /**
     * 対応履歴状況リスト
     * @var array
     */
    public $raw_history_statuses = array();

    /**
     * 対応履歴クレーム変更フラグリスト
     * @var array
     */
    public $raw_history_claim_edit_flags = array();

    /**
     * 対応履歴クレームフラグリスト
     * @var array
     */
    public $raw_history_claim_flags = array();

    /**
     * 対応内容クレームフラグ
     * @var string
     */
    public $raw_answer_claim_flag = '';

    /**
     * 対応内容状況
     * @var string
     */
    public $raw_answer_status = '';

    /**
     * 対応内容コメント
     * @var string
     */
    public $raw_answer_comment = '';

    /**
     * 対応内容更新者
     * @var string
     */
    public $raw_answer_updater = '';

    /**
     * エンティティ化された本社ユーザーフラグを返します。
     * @return string エンティティ化された本社ユーザーフラグ
     */
    public function honsha_user_flag()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_honsha_user_flag);
    }

    /**
     * エンティティ化された顧客登録情報受信日時を返します。
     * @return string エンティティ化された顧客登録情報受信日時
     */
    public function inq_regist_datetime()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_inq_regist_datetime);
    }

    /**
     * エンティティ化された顧客登録情報フォーム種別コードを返します。
     * @return string エンティティ化された顧客登録情報フォーム種別コード
     */
    public function inq_form_cd()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_inq_form_cd);
    }

    /**
     * エンティティ化された顧客登録情報フォーム種別を返します。
     * @return string エンティティ化された顧客登録情報フォーム種別
     */
    public function inq_form()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_inq_form);
    }

    /**
     * エンティティ化された顧客登録情報対応状況を返します。
     * @return string エンティティ化された顧客登録情報対応状況
     */
    public function inq_status()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_inq_status);
    }

    /**
     * エンティティ化された顧客登録情報完了日を返します。
     * @return string エンティティ化された顧客登録情報完了日
     */
    public function inq_done_datetime()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_inq_done_datetime);
    }

    /**
     * エンティティ化された顧客登録情報クレームフラグを返します。
     * @return string エンティティ化された顧客登録情報クレームフラグ
     */
    public function inq_claim_flag()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_inq_claim_flag);
    }

    /**
     * お問い合わせフォームを返します｡;
     * @return Sgmov_Form_Pin002Out お問い合わせフォーム
     */
    public function pin002Out()
    {
        return $this->raw_pin002Out;
    }

    /**
     * 法人引越輸送フォームを返します｡;
     * @return Sgmov_Form_Pcm002Out 法人引越輸送フォーム
     */
    public function pcm002Out()
    {
        return $this->raw_pcm002Out;
    }

    /**
     * 法人設置輸送フォームを返します｡;
     * @return Sgmov_Form_Pcs002Out 法人設置輸送フォーム
     */
    public function pcs002Out()
    {
        return $this->raw_pcs002Out;
    }

    /**
     * 採用エントリーフォームを返します｡;
     * @return Sgmov_Form_Pem002Out 採用エントリーフォーム
     */
    public function pem002Out()
    {
        return $this->raw_pem002Out;
    }

    /**
     * エンティティ化された対応履歴日時リストを返します。
     * @return array エンティティ化された対応履歴日時リスト
     */
    public function history_datetimes()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_history_datetimes);
    }

    /**
     * エンティティ化された対応履歴更新者リストを返します。
     * @return array エンティティ化された対応履歴更新者リスト
     */
    public function history_updaters()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_history_updaters);
    }

    /**
     * エンティティ化された対応履歴コメントリストを返します（改行文字の前にBRタグが挿入されます）。
     * @return array エンティティ化された対応履歴コメントリスト
     */
    public function history_comments()
    {
        return Sgmov_Component_String::nl2br(Sgmov_Component_String::htmlspecialchars($this->raw_history_comments));
    }

    /**
     * エンティティ化された対応履歴状況変更フラグリストを返します。
     * @return array エンティティ化された対応履歴状況変更フラグリスト
     */
    public function history_status_edit_flags()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_history_status_edit_flags);
    }

    /**
     * エンティティ化された対応履歴状況リストを返します。
     * @return array エンティティ化された対応履歴状況リスト
     */
    public function history_statuses()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_history_statuses);
    }

    /**
     * エンティティ化された対応履歴クレーム変更フラグリストを返します。
     * @return array エンティティ化された対応履歴クレーム変更フラグリスト
     */
    public function history_claim_edit_flags()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_history_claim_edit_flags);
    }

    /**
     * エンティティ化された対応履歴クレームフラグリストを返します。
     * @return array エンティティ化された対応履歴クレームフラグリスト
     */
    public function history_claim_flags()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_history_claim_flags);
    }

    /**
     * エンティティ化された対応内容クレームフラグを返します。
     * @return string エンティティ化された対応内容クレームフラグ
     */
    public function answer_claim_flag()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_answer_claim_flag);
    }

    /**
     * エンティティ化された対応内容状況を返します。
     * @return string エンティティ化された対応内容状況
     */
    public function answer_status()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_answer_status);
    }

    /**
     * エンティティ化された対応内容コメントを返します（改行文字の前にBRタグが挿入されます）。
     * @return string エンティティ化された対応内容コメント
     */
    public function answer_comment()
    {
        return Sgmov_Component_String::nl2br(Sgmov_Component_String::htmlspecialchars($this->raw_answer_comment));
    }

    /**
     * エンティティ化された対応内容更新者を返します。
     * @return string エンティティ化された対応内容更新者
     */
    public function answer_updater()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_answer_updater);
    }

}
?>
