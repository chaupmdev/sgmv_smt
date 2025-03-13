<?php
/**
 * @package    ClassDefFile
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**
 * 概算見積入力画面の入力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Ptu001In {

    /**
     * お名前 姓
     * @var string
     */
    public $surname = '';

    /**
     * お名前 名
     * @var string
     */
    public $forename = '';

    /**
     * 電話番号1
     * @var string
     */
    public $tel1 = '';

    /**
     * 電話番号2
     * @var string
     */
    public $tel2 = '';

    /**
     * 電話番号3
     * @var string
     */
    public $tel3 = '';

    /**
     * FAX番号1
     * @var string
     */
    public $fax1 = '';

    /**
     * FAX番号2
     * @var string
     */
    public $fax2 = '';

    /**
     * FAX番号3
     * @var string
     */
    public $fax3 = '';

    /**
     * メールアドレス
     * @var string
     */
    public $mail = '';

    /**
     * メールアドレス確認
     * @var string
     */
    public $retype_mail = '';

    /**
     * 郵便番号1
     * @var string
     */
    public $zip1 = '';

    /**
     * 郵便番号2
     * @var string
     */
    public $zip2 = '';

    /**
     * 現在のお住まい都道府県コード選択値
     * @var string
     */
    public $pref_cd_sel = '';

    /**
     * 市区町村
     * @var string
     */
    public $address = '';

    /**
     * 番地・建物名
     * @var string
     */
    public $building = '';

    /**
    * お名前 姓
    * @var string
    */
    public $surname_hksaki = '';

    /**
     * お名前 名
     * @var string
     */
    public $forename_hksaki = '';

    /**
    * 郵便番号1
    * @var string
    */
    public $zip1_hksaki = '';

    /**
     * 郵便番号2
     * @var string
     */
    public $zip2_hksaki = '';

    /**
    * お引越し先都道府県コード選択値
    * @var string
    */
    public $pref_cd_sel_hksaki = '';

    /**
    * 市区町村
    * @var string
    */
    public $address_hksaki = '';

    /**
     * 番地・建物名
     * @var string
     */
    public $building_hksaki = '';

    /**
    * 電話番号1
    * @var string
    */
    public $tel1_hksaki = '';

    /**
     * 電話番号2
     * @var string
     */
    public $tel2_hksaki = '';

    /**
     * 電話番号3
     * @var string
     */
    public $tel3_hksaki = '';

    /**
     * 不在時連絡先1
     * @var string
     */
    public $tel1_fuzai_hksaki = '';

    /**
     * 不在時連絡先2
     * @var string
     */
    public $tel2_fuzai_hksaki = '';

    /**
     * 不在時連絡先3
     * @var string
     */
    public $tel3_fuzai_hksaki = '';

    /**
     * お引取り予定日コード選択値
     * @var string
     */
    public $hikitori_yotehiji_date_year_cd_sel = '';

    /**
     * お引取り予定日コード選択値
     * @var string
     */
    public $hikitori_yotehiji_date_month_cd_sel = '';

    /**
     * お引取り予定日コード選択値
     * @var string
     */
    public $hikitori_yotehiji_date_day_cd_sel = '';

    /**
     * お引取り予定日コード選択値
     * @var string
     */
    public $hikitori_yotehiji_time_cd_sel = '';

    /**
    * お引取り予定日コード選択値
    * @var string
    */
    public $hikitori_yotehiji_justime_cd_sel = '';

    /**
    * お引越し予定コード選択値
    * @var string
    */
    public $hikitori_yoteji_sel = '';

    /**
     * お引越し予定日時選択値
     * @var string
     */
    public $hikoshi_yotehiji_date_year_cd_sel = '';

    /**
     * お引越し予定日時選択値
     * @var string
     */
    public $hikoshi_yotehiji_date_month_cd_sel = '';

    /**
     * お引越し予定日時選択値
     * @var string
     */
    public $hikoshi_yotehiji_date_day_cd_sel = '';

    /**
     * お引越し予定日時選択値
     * @var string
     */
    public $hikoshi_yotehiji_time_cd_sel = '';

    /**
    * お引越し予定日時選択値
    * @var string
    */
    public $hikoshi_yotehiji_justime_cd_sel = '';

    /**
    * お引越し予定コード選択値
    * @var string
    */
    public $hikoshi_yoteji_sel = '';

    /**
     * カーゴ台数
     * @var string
     */
    public $cago_daisu = '';

    /**
    * 単品輸送品目
    * @var string
    */
    public $tanhin_cd_sel = array();
    public $tanNmFree = array();

    public $checkboxHanshutsu = array();
    public $textHanshutsu = array();

    public $checkboxHannyu = array();
    public $textHannyu = array();


	public $hidden_kihonKin = '';
	public $hidden_hanshutsuSum = '';
	public $hidden_hannyuSum = '';
	public $hidden_mitumoriZeinuki = '';
	public $hidden_zeiKin = '';
	public $hidden_mitumoriZeikomi = '';

	public $binshu_cd = '';

    /**
     * お支払方法コード選択値
     * @var string
     */
    public $payment_method_cd_sel;

    /**
     * お支払店コード選択値
     * @var string
     */
    public $convenience_store_cd_sel = '';

    /**
     * クレジットカード番号
     * @var string
     */
    public $card_number = '';

    /**
     * 有効期限 月
     * @var string
     */
    public $card_expire_month_cd_sel = '';

    /**
     * 有効期限 年
     * @var string
     */
    public $card_expire_year_cd_sel = '';

    /**
     * セキュリティコード
     * @var string
     */
    public $security_cd = '';
}