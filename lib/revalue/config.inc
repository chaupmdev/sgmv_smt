<?php
/*****************************/
/** 汎用定数               */
/*****************************/
define ("OK", "OK");
define ("NG", "NG");

/*****************************/
/** リバリュー専用問い合わせフォーム           */
/** メール設定                 */
/*****************************/
// ユーザ側メール送信設定
define ("REVALUE_TOIAWASE_MAIL_USER_SUBJECT",	"お問い合わせ内容確認メール(自動送信)");		// 件名
define ("REVALUE_TOIAWASE_MAIL_USER_FROM",		"sgmoving_system@sagawa-exp.co.jp");	// メール送信元アドレス（From）
define ("REVALUE_TOIAWASE_MAIL_USER_REPLY_TO",	"sgmoving_system@sagawa-exp.co.jp");	// 送信メールの返送先アドレス（Reply-To）
define ("REVALUE_TOIAWASE_MAIL_USER_ERROR_TO",	"si-service1@sg-systems.co.jp");		// 送信エラー時の返送先アドレス（Return-Path）

// 管理者側メール送信設定
$revalueToiawaseMailAdminTo  = "si-service1@sg-systems.co.jp";
//$revalueToiawaseMailAdminTo  = "kaihatsu-moving@sagawa-exp.co.jp,k_asano002@sagawa-exp.co.jp,h_kimura001@sagawa-exp.co.jp,i_kawano@sagawa-exp.co.jp,k_orimoto@sagawa-exp.co.jp,m_honda009@sagawa-exp.co.jp,k_satou054@sagawa-exp.co.jp,h_kashimoto@sagawa-exp.co.jp";
define ("REVALUE_TOIAWASE_MAIL_ADMIN_SUBJECT",	"リバリュー専用お問い合わせフォームより");				// 件名
define ("REVALUE_TOIAWASE_MAIL_ADMIN_TO",		$revalueToiawaseMailAdminTo);				// メール送信先アドレス（To）
define ("REVALUE_TOIAWASE_MAIL_ADMIN_FROM",		"sgmoving_system@sagawa-exp.co.jp");		// メール送信元アドレス（From）
define ("REVALUE_TOIAWASE_MAIL_ADMIN_REPLY_TO",	"sgmoving_system@sagawa-exp.co.jp");		// 送信メールの返送先アドレス（Reply-To）
define ("REVALUE_TOIAWASE_MAIL_ADMIN_ERROR_TO",	"si-service1@sg-systems.co.jp");			// 送信エラー時の返送先アドレス（Return-Path）

?>
