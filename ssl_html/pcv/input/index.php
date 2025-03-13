<?php
/**
 * 法人オフィス移転訪問見積もり申し込み入力画面を表示します。
 * @package    ssl_html
 * @subpackage PCV
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../../lib/Lib.php';
Sgmov_Lib::useView('pcv/Input');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Pcv_Input();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Pcv001Out
 */
$pcv001Out = $forms['outForm'];

/**
 * エラーフォーム
 * @var Sgmov_Form_Error
 */
$e = $forms['errorForm'];
?>
<!DOCTYPE html>
<html dir="ltr" lang="ja-jp" xml:lang="ja-jp">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0">
    <meta http-equiv="content-style-type" content="text/css">
    <meta http-equiv="content-script-type" content="text/javascript">
    <meta name="Description" content="オフィス移転訪問お見積りページです。">
    <meta name="author" content="SG MOVING Co.,Ltd">
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved.">
    <meta property="og:locale" content="ja_JP">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.sagawa-mov.co.jp/pcv/input/">
    <meta property="og:site_name" content="<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl-ogp.php'); ?>">
    <meta property="og:image" content="/img/ogp/og_image_sgmv.jpg">
    <meta property="twitter:card" content="summary_large_image">
    <link rel="shortcut icon" href="/img/common/favicon.ico">
    <link rel="apple-touch-icon" sizes="192x192" href="/img/ogp/touch-icon.png">
    <title>オフィス移転訪問お見積りフォーム｜お問い合わせ｜<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl.php'); ?></title>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_style.php'); ?>
    <link href="/css/form/common.css" rel="stylesheet" type="text/css">
    <link href="/css/form/form.css" rel="stylesheet" type="text/css">
    <!--[if lt IE 9]>
    <link href="/css/form_ie8.css" rel="stylesheet" type="text/css">
    <![endif]-->
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_script.php'); ?>
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/analytics_head.php'); ?>
    <script src="/js/form/ga.js" type="text/javascript"></script>
</head>

<body>
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/analytics_body.php'); ?>
    <!-- ヘッダStart ************************************************ -->
    <div id="container">
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/header.php'); ?>
    <!-- ヘッダEnd ************************************************ -->

    <div id="main">
        <div class="pageTitle style01">
            <div class="comBox">
                <h1 class="topLead">オフィス移転訪問お見積りフォーム<em>Estimate</em></h1>
                <ul id="pagePath">
                    <li><a href="/"><img class="home" src="/img/common/icon54.jpg" alt=""></a></li>
                    <li><a href="/contact/">お問い合わせ</a></li>
                    <li>オフィス移転訪問お見積りフォーム</li>
                </ul>
            </div>
        </div>
        <!-- Start ************************************************ -->

        <!-- End ************************************************ -->
        <div class="wrap clearfix">
            <p class="sentence">お客様の下へお伺いし、詳細なお見積りを出させていただきます。<br />
                下記の必要項目にご記入の上、送信ください。<br />
                こちらよりご連絡させていただきます。<br />
                ※3月15日～4月10日は繁忙期のため、概算お見積り適用外となります。<br />
                別途お見積りさせていただきます。
            </p>
    <?php if ($e->hasError()) { ?>
                        <div class="err_msg">
                            <p class="sentence br attention">下記の項目が正しく入力・選択されていません。</p>
                            <ul>
    <?php
            // エラー表示
            if ($e->hasErrorForId('top_company_name')) {
                echo '<li><a href="#corp_name">会社名' . $e->getMessage('top_company_name') . '</a></li>';
            }
            if ($e->hasErrorForId('top_company_furigana')) {
                echo '<li><a href="#corp_furigana">会社名フリガナ' . $e->getMessage('top_company_furigana') . '</a></li>';
            }
            if ($e->hasErrorForId('top_charge_name')) {
                echo '<li><a href="#name">担当者名' . $e->getMessage('top_charge_name') . '</a></li>';
            }
            if ($e->hasErrorForId('top_charge_furigana')) {
                echo '<li><a href="#furigana">担当者名フリガナ' . $e->getMessage('top_charge_furigana') . '</a></li>';
            }
            if ($e->hasErrorForId('top_tel')) {
                echo '<li><a href="#phone">電話番号' . $e->getMessage('top_tel') . '</a></li>';
            }
            if ($e->hasErrorForId('top_tel_type_cd_sel')) {
                echo '<li><a href="#phone">電話種類コード' . $e->getMessage('top_tel_type_cd_sel') . '</a></li>';
            }
            if ($e->hasErrorForId('top_tel_other')) {
                echo '<li><a href="#phone">電話番号種類その他' . $e->getMessage('top_tel_other') . '</a></li>';
            }
            if ($e->hasErrorForId('top_mail')) {
                echo '<li><a href="#mail">メールアドレス' . $e->getMessage('top_mail') . '</a></li>';
            }
            if ($e->hasErrorForId('top_from_area_cd_sel')) {
                echo '<li><a href="#from_area">現在お住まいの地域' . $e->getMessage('top_from_area_cd_sel') . '</a></li>';
            }
            if ($e->hasErrorForId('top_to_area_cd_sel')) {
                echo '<li><a href="#to_area">お引越し先の地域' . $e->getMessage('top_to_area_cd_sel') . '</a></li>';
            }
            if ($e->hasErrorForId('top_move_date')) {
                echo '<li><a href="#move_date">お引越し予定日' . $e->getMessage('top_move_date') . '</a></li>';
            }
            if ($e->hasErrorForId('top_visit_date1')) {
                echo '<li><a href="#visit_date">訪問お見積り希望日「第一希望日」' . $e->getMessage('top_visit_date1') . '</a></li>';
            }
            if ($e->hasErrorForId('top_visit_date2')) {
                echo '<li><a href="#visit_date">訪問お見積り希望日「第二希望日」' . $e->getMessage('top_visit_date2') . '</a></li>';
            }
            if ($e->hasErrorForId('top_cur_zip')) {
                echo '<li><a href="#add_now">現住所　郵便番号' . $e->getMessage('top_cur_zip') . '</a></li>';
            }
            if ($e->hasErrorForId('top_cur_pref_cd_sel')) {
                echo '<li><a href="#add_now">現住所　都道府県' . $e->getMessage('top_cur_pref_cd_sel') . '</a></li>';
            }
            if ($e->hasErrorForId('top_cur_address')) {
                echo '<li><a href="#add_now">現住所　住所' . $e->getMessage('top_cur_address') . '</a></li>';
            }
            if ($e->hasErrorForId('top_cur_floor')) {
                echo '<li><a href="#add_now">現住所　階数' . $e->getMessage('top_cur_floor') . '</a></li>';
            }
            if ($e->hasErrorForId('top_new_zip')) {
                echo '<li><a href="#add_new">新住所　郵便番号' . $e->getMessage('top_new_zip') . '</a></li>';
            }
            if ($e->hasErrorForId('top_new_pref_cd_sel')) {
                echo '<li><a href="#add_new">新住所　都道府県' . $e->getMessage('top_new_pref_cd_sel') . '</a></li>';
            }
            if ($e->hasErrorForId('top_new_address')) {
                echo '<li><a href="#add_new">新住所　住所' . $e->getMessage('top_new_address') . '</a></li>';
            }
            if ($e->hasErrorForId('top_new_floor')) {
                echo '<li><a href="#add_new">新住所　階数' . $e->getMessage('top_new_floor') . '</a></li>';
            }
            if ($e->hasErrorForId('top_number_of_people')) {
                echo '<li><a href="#number_of_people">移動人数' . $e->getMessage('top_number_of_people') . '</a></li>';
            }
            if ($e->hasErrorForId('top_tsubo_su')) {
                echo '<li><a href="#tsubo_su">フロア坪数' . $e->getMessage('top_tsubo_su') . '</a></li>';
            }
            if ($e->hasErrorForId('top_comment')) {
                echo '<li><a href="#comment">備考' . $e->getMessage('top_comment') . '</a></li>';
            }
    ?>
                            </ul>
                        </div>
    <?php } ?>

            <!-- ▽▽お客様情報　section　ここから -->
            <form action="/pcv/check_input/" method="post">
            <div class="section">
                <h3 class="cont_inner_title">お客様情報</h3>
                <div class="dl_block">
                    <input type="hidden" name="ticket" value="<?php echo $ticket ?>" />
                    <dl>
                        <dt id="corp_name">会社名<span>必須</span></dt>
                        <dd class="<?php if ($e->hasErrorForId('top_company_name')) { echo ' form_error'; } ?>">
                            <input type="text" value="<?php echo $pcv001Out->company_name() ?>" maxlength="30" name="company_name" class="w_220">
                        </dd>
                    </dl>
                    <dl>
                        <dt id="corp_furigana">会社名フリガナ<span>必須</span></dt>
                        <dd class="<?php if ($e->hasErrorForId('top_company_furigana')) { echo ' form_error'; } ?>">
                            <input type="text" value="<?php echo $pcv001Out->company_furigana() ?>" maxlength="30" name="company_furigana" class="w_220">
                        </dd>
                    </dl>
                    <dl>
                        <dt id="name">担当者名<span>必須</span></dt>
                        <dd<?php if ($e->hasErrorForId('top_charge_name')) { echo ' class="form_error"'; } ?>>
                            <input type="text" value="<?php echo $pcv001Out->charge_name() ?>" class="w_120" maxlength="30" name="charge_name">
                        </dd>
                    </dl>
                    <dl>
                        <dt id="furigana">担当者名フリガナ<span>必須</span></dt>
                        <dd<?php if ($e->hasErrorForId('top_charge_furigana')) { echo ' class="form_error"'; } ?>>
                            <input type="text" value="<?php echo $pcv001Out->charge_furigana() ?>" class="w_120" maxlength="30" name="charge_furigana">
                        </dd>
                    </dl>
                    <dl>
                        <dt id="phone">電話番号<span>必須</span></dt>
                        <dd<?php if (($e->hasErrorForId('top_tel')) || ($e->hasErrorForId('top_tel_other')) || ($e->hasErrorForId('top_tel_type_cd_sel'))) { echo ' class="form_error"'; } ?>>
                            <input type="text" value="<?php echo $pcv001Out->tel1(); ?>" class="w_70" maxlength="5" name="tel1">
                            -
                            <input type="text" value="<?php echo $pcv001Out->tel2(); ?>" class="w_70" maxlength="5" name="tel2">
                            -
                            <input type="text" value="<?php echo $pcv001Out->tel3(); ?>" class="w_70" maxlength="4" name="tel3">
                            <ul>
                                <li>
                                    <label for="tel_company" class="radio-label">
                                        <input type="radio" id="tel_company" value="2" name="tel_type_cd_sel" <?php if ($pcv001Out->tel_type_cd_sel() === '2') echo ' checked="checked"'; ?> />
                                        勤務先 </label>
                                </li>
                                <li>
                                    <label for="tel_mobile" class="radio-label">
                                        <input type="radio" id="tel_mobile" value="1" name="tel_type_cd_sel" <?php if ($pcv001Out->tel_type_cd_sel() === '1') echo ' checked="checked"'; ?> />
                                        携帯 </label>
                                </li>
                                <li>
                                    <label for="tel_etc" class="radio-label">
                                        <input type="radio" id="tel_etc" value="3" name="tel_type_cd_sel" <?php if ($pcv001Out->tel_type_cd_sel() === '3') echo ' checked="checked"'; ?> />
                                        その他 </label>
                                    <input type="text" value="<?php echo $pcv001Out->tel_other(); ?>" class="w65" maxlength="20" name="tel_other">
                                </li>
                            </ul>
                        </dd>
                    </dl>
                    <dl>
                        <dt id="mail">メールアドレス<span>必須</span></dt>
                        <dd class="<?php if ($e->hasErrorForId('top_mail')) { echo ' form_error'; } ?>">
                            <input type="text" value="<?php echo $pcv001Out->mail(); ?>" maxlength="80" name="mail" class="w_220">
                        </dd>
                    </dl>
                    <dl>
                        <dt>連絡方法</dt>
                        <dd>
                            <ul class="three_col clearfix">
                                <li>
                                    <label for="contact_phone" class="radio-label">
                                        <input type="radio" id="contact_phone" value="1" name="contact_method_cd_sel" <?php if ($pcv001Out->contact_method_cd_sel() === '1') echo ' checked="checked"'; ?> />
                                        電話 </label>
                                </li>
                                <li>
                                    <label for="contact_mail" class="radio-label">
                                        <input type="radio" id="contact_mail" value="2" name="contact_method_cd_sel" <?php if ($pcv001Out->contact_method_cd_sel() === '2') echo ' checked="checked"'; ?> />
                                        メール </label>
                                </li>
                            </ul>
                        </dd>
                    </dl>
                    <dl>
                        <dt>電話連絡可能時間帯</dt>
                        <dd>
                                    <label for="contact_available1" class="radio-label right50">
                                        <input type="radio" id="contact_available1" value="2" name="contact_available_cd_sel" <?php if ($pcv001Out->contact_available_cd_sel() === '2') echo ' checked="checked"'; ?> />
                                        終日可 </label><br class="pcH">
                                    <label for="contact_available2" class="radio-label">
                                        <input type="radio" id="contact_available2" value="1" name="contact_available_cd_sel" <?php if ($pcv001Out->contact_available_cd_sel() === '1') echo ' checked="checked"'; ?> />
                                        時間指定 </label>
                                    <select name="contact_start_cd_sel">
    <?php
            $contact_start_cds = $pcv001Out->contact_start_cds();
            $contact_start_lbls = $pcv001Out->contact_start_lbls();
            for ($i = 0; $i < count($contact_start_cds); ++$i) {
                $cd = $contact_start_cds[$i];
                $lbl = $contact_start_lbls[$i];
                if ($pcv001Out->contact_start_cd_sel() === $cd) {
                    echo '<option value="'.$cd.'" selected="selected">'.$lbl.'</option>'.PHP_EOL;
                } else {
                    echo '<option value="'.$cd.'">'.$lbl.'</option>'.PHP_EOL;
                }
            }
    ?>
                                    </select>
                                    時&#12288;～
                                    <select name="contact_end_cd_sel">
                                        <option value=""></option>
    <?php
            $contact_end_cds = $pcv001Out->contact_end_cds();
            $contact_end_lbls = $pcv001Out->contact_end_lbls();
            for ($i = 0; $i < count($contact_end_cds); ++$i) {
                $cd = $contact_end_cds[$i];
                $lbl = $contact_end_lbls[$i];
                if ($pcv001Out->contact_end_cd_sel() === $cd) {
                    echo '<option value="'.$cd.'" selected="selected">'.$lbl.'</option>'.PHP_EOL;
                } else {
                    echo '<option value="'.$cd.'">'.$lbl.'</option>'.PHP_EOL;
                }
            }
    ?>
                                    </select>
                                    時
                        </dd>
                    </dl>
                </div>
            </div>
            <!-- △△お客様情報　section　ここまで -->
            <!-- ▽▽お引越情報　section　ここから -->
            <div class="section">
                <h3 class="cont_inner_title">お引越情報</h3>
                <div class="dl_block">
                    <dl>
                        <dt id="from_area">現在お住まいの地域<span>必須</span></dt>
                        <dd<?php if ($e->hasErrorForId('top_from_area_cd_sel')) { echo ' class="form_error"'; } ?>>
                            <select class="w110" name="from_area_cd_sel">
    <?php
            $from_area_cds = $pcv001Out->from_area_cds();
            $from_area_lbls = $pcv001Out->from_area_lbls();
            for ($i = 0; $i < count($from_area_cds); ++$i) {
                $cd = $from_area_cds[$i];
                $lbl = $from_area_lbls[$i];
                if ($pcv001Out->from_area_cd_sel() === $cd) {
                    echo '<option value="'.$cd.'" selected="selected">'.$lbl.'</option>'.PHP_EOL;
                } else {
                    echo '<option value="'.$cd.'">'.$lbl.'</option>'.PHP_EOL;
                }
            }
    ?>
                            </select>
                        </dd>
                    </dl>
                    <dl>
                        <dt id="to_area">お引越先の地域<span>必須</span></dt>
                        <dd<?php if ($e->hasErrorForId('top_to_area_cd_sel')) { echo ' class="form_error"'; } ?>>
                            <select class="w110" name="to_area_cd_sel">
    <?php
            $to_area_cds = $pcv001Out->to_area_cds();
            $to_area_lbls = $pcv001Out->to_area_lbls();
            for ($i = 0; $i < count($to_area_cds); ++$i) {
                $cd = $to_area_cds[$i];
                $lbl = $to_area_lbls[$i];
                if ($pcv001Out->to_area_cd_sel() === $cd) {
                    echo '<option value="'.$cd.'" selected="selected">'.$lbl.'</option>'.PHP_EOL;
                } else {
                    echo '<option value="'.$cd.'">'.$lbl.'</option>'.PHP_EOL;
                }
            }
    ?>
                            </select>
                        </dd>
                    </dl>
                    <dl>
                        <dt id="move_date">お引越予定日</dt>
                        <dd<?php if ($e->hasErrorForId('top_move_date')) { echo ' class="form_error"'; } ?>>
                            <select name="move_date_year_cd_sel">
    <?php
            $move_date_year_cds = $pcv001Out->move_date_year_cds();
            $move_date_year_lbls = $pcv001Out->move_date_year_lbls();
            for ($i = 0; $i < count($move_date_year_cds); ++$i) {
                $cd = $move_date_year_cds[$i];
                $lbl = $move_date_year_lbls[$i];
                if ($pcv001Out->move_date_year_cd_sel() === $cd) {
                    echo '<option value="'.$cd.'" selected="selected">'.$lbl.'</option>'.PHP_EOL;
                } else {
                    echo '<option value="'.$cd.'">'.$lbl.'</option>'.PHP_EOL;
                }
            }
    ?>
                            </select>
                            年
                            <select name="move_date_month_cd_sel">
    <?php
            $move_date_month_cds = $pcv001Out->move_date_month_cds();
            $move_date_month_lbls = $pcv001Out->move_date_month_lbls();
            for ($i = 0; $i < count($move_date_month_cds); ++$i) {
                $cd = $move_date_month_cds[$i];
                $lbl = $move_date_month_lbls[$i];
                if ($pcv001Out->move_date_month_cd_sel() === $cd) {
                    echo '<option value="'.$cd.'" selected="selected">'.$lbl.'</option>'.PHP_EOL;
                } else {
                    echo '<option value="'.$cd.'">'.$lbl.'</option>'.PHP_EOL;
                }
            }
            $move_date_month_cds = $pcv001Out->move_date_month_cds();
            $move_date_month_lbls = $pcv001Out->move_date_month_lbls();
    ?>
                            </select>
                            月
                            <select name="move_date_day_cd_sel">
    <?php
            $move_date_day_cds = $pcv001Out->move_date_day_cds();
            $move_date_day_lbls = $pcv001Out->move_date_day_lbls();
            for ($i = 0; $i < count($move_date_day_cds); ++$i) {
                $cd = $move_date_day_cds[$i];
                $lbl = $move_date_day_lbls[$i];
                if ($pcv001Out->move_date_day_cd_sel() === $cd) {
                    echo '<option value="'.$cd.'" selected="selected">'.$lbl.'</option>'.PHP_EOL;
                } else {
                    echo '<option value="'.$cd.'">'.$lbl.'</option>'.PHP_EOL;
                }
            }
    ?>
                            </select>
                            日 <br class="pcH">
                            ※1週間後～半年先までの日付 </dd>
                    </dl>
                    <dl>
                        <dt id="visit_date">訪問お見積り希望日</dt>
                        <dd>
                            <div<?php if ($e->hasErrorForId('top_visit_date1')) { echo ' class="form_error"'; } ?> id="date_01"> 第1希望日<br class="pcH" />
                                <select name="visit_date1_year_cd_sel">
    <?php
            $visit_date1_year_cds = $pcv001Out->visit_date1_year_cds();
            $visit_date1_year_lbls = $pcv001Out->visit_date1_year_lbls();
            for ($i = 0; $i < count($visit_date1_year_cds); ++$i) {
                $cd = $visit_date1_year_cds[$i];
                $lbl = $visit_date1_year_lbls[$i];
                if ($pcv001Out->visit_date1_year_cd_sel() === $cd) {
                    echo '<option value="'.$cd.'" selected="selected">'.$lbl.'</option>'.PHP_EOL;
                } else {
                    echo '<option value="'.$cd.'">'.$lbl.'</option>'.PHP_EOL;
                }
            }
    ?>
                                </select>
                                年
                                <select name="visit_date1_month_cd_sel">
    <?php
            $visit_date1_month_cds = $pcv001Out->visit_date1_month_cds();
            $visit_date1_month_lbls = $pcv001Out->visit_date1_month_lbls();
            for ($i = 0; $i < count($visit_date1_month_cds); ++$i) {
                $cd = $visit_date1_month_cds[$i];
                $lbl = $visit_date1_month_lbls[$i];
                if ($pcv001Out->visit_date1_month_cd_sel() === $cd) {
                    echo '<option value="'.$cd.'" selected="selected">'.$lbl.'</option>'.PHP_EOL;
                } else {
                    echo '<option value="'.$cd.'">'.$lbl.'</option>'.PHP_EOL;
                }
            }
            $visit_date1_month_cds = $pcv001Out->visit_date1_month_cds();
            $visit_date1_month_lbls = $pcv001Out->visit_date1_month_lbls();
    ?>
                                </select>
                                月
                                <select name="visit_date1_day_cd_sel">
    <?php
            $visit_date1_day_cds = $pcv001Out->visit_date1_day_cds();
            $visit_date1_day_lbls = $pcv001Out->visit_date1_day_lbls();
            for ($i = 0; $i < count($visit_date1_day_cds); ++$i) {
                $cd = $visit_date1_day_cds[$i];
                $lbl = $visit_date1_day_lbls[$i];
                if ($pcv001Out->visit_date1_day_cd_sel() === $cd) {
                    echo '<option value="'.$cd.'" selected="selected">'.$lbl.'</option>'.PHP_EOL;
                } else {
                    echo '<option value="'.$cd.'">'.$lbl.'</option>'.PHP_EOL;
                }
            }
    ?>
                                </select>
                                日 <br class="pcH">
                                ※1週間後～半年先までの日付
                            </div>
                            <div<?php if ($e->hasErrorForId('top_visit_date2')) { echo ' class="form_error"'; } ?> id="date_02"> 第2希望日<br class="pcH" />
                                <select name="visit_date2_year_cd_sel">
    <?php
            $visit_date2_year_cds = $pcv001Out->visit_date2_year_cds();
            $visit_date2_year_lbls = $pcv001Out->visit_date2_year_lbls();
            for ($i = 0; $i < count($visit_date2_year_cds); ++$i) {
                $cd = $visit_date2_year_cds[$i];
                $lbl = $visit_date2_year_lbls[$i];
                if ($pcv001Out->visit_date2_year_cd_sel() === $cd) {
                    echo '<option value="'.$cd.'" selected="selected">'.$lbl.'</option>'.PHP_EOL;
                } else {
                    echo '<option value="'.$cd.'">'.$lbl.'</option>'.PHP_EOL;
                }
            }
    ?>
                                </select>
                                年
                                <select name="visit_date2_month_cd_sel">
    <?php
            $visit_date2_month_cds = $pcv001Out->visit_date2_month_cds();
            $visit_date2_month_lbls = $pcv001Out->visit_date2_month_lbls();
            for ($i = 0; $i < count($visit_date2_month_cds); ++$i) {
                $cd = $visit_date2_month_cds[$i];
                $lbl = $visit_date2_month_lbls[$i];
                if ($pcv001Out->visit_date2_month_cd_sel() === $cd) {
                    echo '<option value="'.$cd.'" selected="selected">'.$lbl.'</option>'.PHP_EOL;
                } else {
                    echo '<option value="'.$cd.'">'.$lbl.'</option>'.PHP_EOL;
                }
            }
            $visit_date2_month_cds = $pcv001Out->visit_date2_month_cds();
            $visit_date2_month_lbls = $pcv001Out->visit_date2_month_lbls();
    ?>
                                </select>
                                月
                                <select name="visit_date2_day_cd_sel">
    <?php
            $visit_date2_day_cds = $pcv001Out->visit_date2_day_cds();
            $visit_date2_day_lbls = $pcv001Out->visit_date2_day_lbls();
            for ($i = 0; $i < count($visit_date2_day_cds); ++$i) {
                $cd = $visit_date2_day_cds[$i];
                $lbl = $visit_date2_day_lbls[$i];
                if ($pcv001Out->visit_date2_day_cd_sel() === $cd) {
                    echo '<option value="'.$cd.'" selected="selected">'.$lbl.'</option>'.PHP_EOL;
                } else {
                    echo '<option value="'.$cd.'">'.$lbl.'</option>'.PHP_EOL;
                }
            }
    ?>
                                </select>
                                日 <br class="pcH" />
                                ※1週間後～半年先までの日付 </div>
                        </dd>
                    </dl>
                </div>
            </div>
            <!-- △△お引越情報　section　ここまで -->
            <!-- ▽▽現在のお住まいについて　section　ここから -->
            <div class="section">
                <h3 class="cont_inner_title">現在のお住まいについて</h3>
                <div class="dl_block">
                    <dl>
                        <dt id="add_now">現住所<span>必須</span></dt>
                        <dd<?php if (($e->hasErrorForId('top_cur_zip')) || ($e->hasErrorForId('top_cur_pref_cd_sel')) || ($e->hasErrorForId('top_cur_address'))) { echo ' class="form_error"'; } ?>>
                            <ul>
                                <li> 〒
                                    <input type="text" value="<?php echo $pcv001Out->cur_zip1(); ?>" class="w_70" maxlength="3" name="cur_zip1">
                                    -
                                    <input type="text" value="<?php echo $pcv001Out->cur_zip2(); ?>" class="w_70" maxlength="4" name="cur_zip2">
                                    <input type="button" onClick="AjaxZip2.zip2addr('input_forms','cur_zip1','cur_pref_cd_sel','cur_address','cur_zip2','','', '<?php echo Sgmov_View_Pcv_Common::FEATURE_ID ?>', '<?php echo Sgmov_View_Pcv_Common::GAMEN_ID_PCV001 ?>', '<?php echo $ticket ?>');" name="cur_adrs_search_btn" value="住所検索" class="button ml10"  />
                                </li>
                                <li>
                                    <select class="w110" name="cur_pref_cd_sel">
    <?php
            $pref_cds = $pcv001Out->cur_pref_cds();
            $pref_lbls = $pcv001Out->cur_pref_lbls();
            $count = count($pref_cds);
            $option = '';
            for ($i = 0; $i < $count; ++$i) {
                $cd = $pref_cds[$i];
                $lbl = $pref_lbls[$i];
                if ($pcv001Out->cur_pref_cd_sel() === $cd) {
                    $option .= '<option value="'.$cd.'" selected="selected">'.$lbl.'</option>'.PHP_EOL;
                } else {
                    $option .= '<option value="'.$cd.'">'.$lbl.'</option>'.PHP_EOL;
                }
            }
            echo $option;
    ?>
                                    </select>
                                    都道府県 </li>
                                <li>
                                    <input type="text" value="<?php echo $pcv001Out->cur_address() ?>" class="w_220" maxlength="40" name="cur_address" />
                                    市町村以下 </li>
                            </ul>
                        </dd>
                    </dl>
                    <dl>
                        <dt>エレベーターの有無</dt>
                        <dd>
                            <ul class="three_col clearfix">
                                <li>
                                    <label for="cur_elevator1" class="radio-label">
                                        <input type="radio" id="cur_elevator1" value="1" name="cur_elevator_cd_sel" <?php if ($pcv001Out->cur_elevator_cd_sel() === '1') echo ' checked="checked"'; ?> />
                                        あり</label>
                                </li>
                                <li>
                                    <label for="cur_elevator2" class="radio-label">
                                        <input type="radio" id="cur_elevator2" value="0" name="cur_elevator_cd_sel" <?php if ($pcv001Out->cur_elevator_cd_sel() === '0') echo ' checked="checked"'; ?> />
                                        なし</label>
                                </li>
                            </ul>
                        </dd>
                    </dl>
                    <dl>
                        <dt>現在お住まいの階</dt>
                        <dd<?php if ($e->hasErrorForId('top_cur_floor')) { echo ' class="form_error"'; } ?>>
                            <input type="text" maxlength="2" size="3" value="<?php print $pcv001Out->cur_floor(); ?>" name="cur_floor">
                            階 </dd>
                    </dl>
                    <dl>
                        <dt>住居前道幅</dt>
                        <dd>
                            <ul class="three_col clearfix">
                                <li>
                                    <label for="road1" class="radio-label">
                                        <input type="radio" id="road1" value="1" name="cur_road_cd_sel" <?php if ($pcv001Out->cur_road_cd_sel() === '1') echo ' checked="checked"'; ?> />
                                        車両通行不可</label>
                                </li>
                                <li>
                                    <label for="road2" class="radio-label">
                                        <input type="radio" id="road2" value="2" name="cur_road_cd_sel" <?php if ($pcv001Out->cur_road_cd_sel() === '2') echo ' checked="checked"'; ?> />
                                        1台通行可</label>
                                </li>
                                <li>
                                    <label for="road3" class="radio-label">
                                        <input type="radio" id="road3" value="3" name="cur_road_cd_sel" <?php if ($pcv001Out->cur_road_cd_sel() === '3') echo ' checked="checked"'; ?> />
                                        2台すれ違い可</label>
                                </li>
                            </ul>
                        </dd>
                    </dl>
                </div>
            </div>
            <!-- △△現在のお住まいについて　section　ここまで -->
            <!-- ▽▽お引越先のお住まいについて　section　ここから -->
            <div class="section">
                <h3 class="cont_inner_title">お引越先のお住まいについて</h3>
                <div class="dl_block">
                    <dl>
                        <dt>新住所<span>必須</span></dt>
                        <dd<?php if (($e->hasErrorForId('top_new_zip')) || ($e->hasErrorForId('top_new_pref_cd_sel')) || ($e->hasErrorForId('top_new_address'))) { echo ' class="form_error"'; } ?>>
                            <ul>
                                <li> 〒
                                    <input type="text" name="new_zip1" maxlength="3" class="w_70" value="<?php echo $pcv001Out->new_zip1(); ?>">
                                    -
                                    <input type="text" name="new_zip2" maxlength="4" class="w_70" value="<?php echo $pcv001Out->new_zip2(); ?>">
                                    <input type="button" name="new_adrs_search_btn" value="住所検索" class="button ml10" onClick="AjaxZip2.zip2addr('input_forms','new_zip1','new_pref_cd_sel','new_address','new_zip2','','', '<?php echo Sgmov_View_Pcv_Common::FEATURE_ID ?>', '<?php echo Sgmov_View_Pcv_Common::GAMEN_ID_PCV001 ?>', '<?php echo $ticket ?>');" />
                                </li>
                                <li>
                                    <select name="new_pref_cd_sel" class="w110">
    <?php
            $pref_cds = $pcv001Out->new_pref_cds();
            $pref_lbls = $pcv001Out->new_pref_lbls();
            $count = count($pref_cds);
            $option = '';
            for ($i = 0; $i < $count; ++$i) {
                $cd = $pref_cds[$i];
                $lbl = $pref_lbls[$i];
                if ($pcv001Out->new_pref_cd_sel() === $cd) {
                    $option .= '<option value="'.$cd.'" selected="selected">'.$lbl.'</option>'.PHP_EOL;
                } else {
                    $option .= '<option value="'.$cd.'">'.$lbl.'</option>'.PHP_EOL;
                }
            }
            echo $option;
    ?>
                                    </select>
                                    都道府県 </li>
                                <li>
                                    <input type="text" name="new_address" maxlength="40" class="w_220" value="<?php echo $pcv001Out->new_address() ?>">
                                    市町村以下 </li>
                            </ul>
                        </dd>
                    </dl>
                    <dl>
                        <dt>エレベーターの有無</dt>
                        <dd>
                            <ul class="three_col clearfix">
                                <li>
                                    <label class="radio-label" for="new_elevator1">
                                        <input type="radio" name="new_elevator_cd_sel" value="1" id="new_elevator1" <?php if ($pcv001Out->new_elevator_cd_sel() === '1') echo ' checked="checked"'; ?> />
                                        あり </label>
                                </li>
                                <li>
                                    <label class="radio-label" for="new_elevator2">
                                        <input type="radio" name="new_elevator_cd_sel" value="0" id="new_elevator2" <?php if ($pcv001Out->new_elevator_cd_sel() === '0') echo ' checked="checked"'; ?> />
                                        なし </label>
                                </li>
                            </ul>
                        </dd>
                    </dl>
                    <dl>
                        <dt>新しいお住まいの階</dt>
                        <dd<?php if ($e->hasErrorForId('top_new_floor')) { echo ' class="form_error"'; } ?>>
                            <input type="text" name="new_floor" value="<?php print $pcv001Out->new_floor(); ?>" size="3" maxlength="2">
                            階 </dd>
                    </dl>
                    <dl>
                        <dt>住居前道幅</dt>
                        <dd>
                            <ul class="three_col clearfix">
                                <li>
                                    <label class="radio-label" for="new_road1">
                                        <input type="radio" name="new_road_cd_sel" value="1" id="new_road1" <?php if ($pcv001Out->new_road_cd_sel() === '1') echo ' checked="checked"'; ?> />
                                        車両通行不可 </label>
                                </li>
                                <li>
                                    <label class="radio-label" for="new_road2">
                                        <input type="radio" name="new_road_cd_sel" value="2" id="new_road2" <?php if ($pcv001Out->new_road_cd_sel() === '2') echo ' checked="checked"'; ?> />
                                        1台通行可 </label>
                                </li>
                                <li>
                                    <label class="radio-label" for="new_road3">
                                        <input type="radio" name="new_road_cd_sel" value="3" id="new_road3" <?php if ($pcv001Out->new_road_cd_sel() === '3') echo ' checked="checked"'; ?> />
                                        2台すれ違い可 </label>
                                </li>
                            </ul>
                        </dd>
                    </dl>
                    <dl>
                        <dt>移動人数</dt>
                        <dd<?php if ($e->hasErrorForId('top_number_of_people')) { echo ' class="form_error"'; } ?>>
                            <input type="text" name="number_of_people" maxlength="3" class="w_70" value="<?php echo $pcv001Out->number_of_people(); ?>">
                            人 </dd>
                    </dl>
                    <dl>
                        <dt>フロア坪数</dt>
                        <dd<?php if ($e->hasErrorForId('top_tsubo_su')) { echo ' class="form_error"'; } ?>>
                            <input type="text" name="tsubo_su" maxlength="10" class="w_70" value="<?php echo $pcv001Out->tsubo_su(); ?>">
                            坪 </dd>
                    </dl>
                    <dl>
                        <dt>備考欄</dt>
                        <dd class="<?php if ($e->hasErrorForId('top_comment')) { echo ' form_error'; } ?>">
                            <textarea name="comment" cols="" rows="9" class="w100p"><?php echo $pcv001Out->comment(); ?></textarea>
                            <p>※300文字以内でお願いいたします。</p></dd>
                    </dl>
                </div>
            </div>
            <!-- △△お引越先のお住まいについて　section　ここまで -->

            <!-- ▽▽お問い合わせにあたって　section　ここから -->
            <div class="border_box"><strong>お問い合わせにあたって</strong>
                <ul>
                    <li>ご連絡までにお時間をいただく場合がございます。ご了承ください。</li>
                    <li>電話番号、メールアドレスが誤っておりますとご連絡ができません。間違いがないかご確認ください。</li>
                </ul>
            </div>
            <!-- △△お問い合わせにあたって　section　ここまで -->
            <!-- ▽▽個人情報の取り扱い　section　ここから -->
            <div class="attention_area">

                <!--▼個人情報の取り扱いここから-->
                <div id="privacy_policy" class="accordion">
                    <h3 class="accordion_button">個人情報の取扱いについて</h3>
                    <div id="privacy_contents" class="ac_content">
                        <p class="sentence">
                            SGムービング株式会社（以下、「弊社」という）は、以下の方針に基づき、個人情報の保護・管理・運用を行っております。必ずお読みください。
                            <br />本サイトにおいて個人情報をご提供いただいた場合、弊社の個人情報の取り扱いに関しご同意いただいたものといたします。
                        </p>
                        <h4 class="ttl">個人情報の取扱について</h4>
                        <ol>
                            <li>
                                <h3>個人情報の取扱いの基本方針</h3>
                                <p>ご提供いただいた個人情報は、弊社が定める「個人情報保護方針」に従い、適切な保護措置を講じ、厳重に管理いたします。</p>
                            </li>
                            <li>
                                <h3>個人情報の取得及び利用目的</h3>
                                <p>弊社は、取得した個人情報は、以下の目的にのみ利用いたします。ただし、ご本人が容易に知覚できない方法での個人情報の取得はいたしません。</p>
                                <ul>
                                    <li>お客様への見積作成およびご依頼頂いた作業を行うため。</li>
                                    <li>お客様のご依頼に付随する作業およびサービスを行うため。</li>
                                    <li>お客様からお預かりした配送物をお届けするため。</li>
                                    <li>お客様から頂いた意見又は要望に回答・対応するため。</li>
                                    <li>お客様等への報告や必要な処理を行うため。</li>
                                    <li>お客様からの各種お問い合わせや資料請求等にご対応するため</li>
                                </ul>
                                <p>上記以外の目的で取得した個人情報を利用する場合は、改めてご本人に目的をお知らせし、同意を得るものといたします。</p>
                            </li>
                            <li>
                                <h3>個人情報の第三者提供について</h3>
                                <p>ご提供いただいた個人情報は、ご本人の同意なしに第三者への提供はいたしません。ただし、法令に基づき、国の機関または地方公共団体等より法的義務を伴う協力要請を受けた場合には、例外的にご本人の同意なく関連機関等に提供する場合がございます。</p>
                            </li>
                            <li>
                                <h3>個人情報の取扱いの委託について</h3>
                                <p>弊社は、ご本人の同意なしに個人情報の取扱いの全部または一部を委託する場合がございます。委託に当たっては、十分な個人情報の保護水準を満たしている者を選定し、委託を受けた者に対する必要かつ適切な監督を行います。</p>
                            </li>
                            <li>
                                <h3>個人情報提供の任意性及びその結果について</h3>
                                <p>弊社への個人情報の提供につきましては、ご本人の任意です。ただし、個人情報をご提供いただけない場合、弊社の各種サービスのご提供が行えなくなるおそれがございますのであらかじめご了承ください。</p>
                            </li>
                            <li>
                                <h3>弊社の個人情報保護管理者</h3>
                                <p>個人情報保護管理者：管理部 部長</p>
                            </li>
                            <li>
                                <h3>個人情報に関する苦情、相談、開示・訂正・削除等の請求先について</h3>
                                <p>弊社は、ご本人またはご本人の代理人から個人情報に関する苦情、相談、開示、内容の訂正、追加又は削除、利用目的の通知、利用の停止、消去及び第三者への提供の停止、第三者提供記録の開示についての請求を受けた場合は、弊社の手続きに従って速やかに対応します。個人情報の開示、内容の訂正、追加又は削除、利用目的の通知、利用の停止、消去及び第三者への提供の停止、第三者提供記録の開示については、弊社の「個人情報の取扱いに関する窓口（下記記載）」までご請求ください。</p>
                            </li>
                            <li>
                                <h3>安全管理</h3>
                                <p>弊社は、個人情報への不正アクセスまたは個人情報の紛失、破壊、改ざん、漏えい等の危険に対して、必要な安全対策を継続的に講じるよう努めます。</p>
                            </li>
                        </ol>
                        <p id="contact" class="sentence">
                            ＳＧムービング株式会社　個人情報の取扱いに関する窓口
                            <br />
                            東京都江東区新砂三丁目2番9号 ＸフロンティアＥＡＳＴ6階
                        </p>
                    </div>
                </div>
                <!--▲個人情報の取り扱いここまで-->
            </div>
            <!-- △△個人情報の取り扱い　section　ここまで -->
            <div class="text_center comBtn02 btn01 fadeInUp animate">
                <div class="btnInner">
                    <input id="submit_button" name="submit" type="submit" value="同意して次に進む（入力内容の確認）" />
                </div>
            </div>
            </form>
        </div>
    </div>
    <!--main-->
    </div>
    <!-- フッターStart ************************************************ -->
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/footer.php'); ?>
    <!-- フッターEnd ************************************************ -->
    <!--[if lt IE 9]>
    <script charset="UTF-8" type="text/javascript" src="/js/form/jquery-1.12.0.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/form/lodash-compat.min.js"></script>
    <![endif]-->
    <!--[if gte IE 9]><!-->
    <script charset="UTF-8" type="text/javascript" src="/js/form/jquery-2.2.0.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/form/lodash.min.js"></script>
    <!--<![endif]-->
    <script charset="UTF-8" type="text/javascript" src="/js/form/common.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/form/multi_send.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/form/ajax_searchaddress.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/form/hissuChange.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/form/radio.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/form/input.js"></script>
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_script_foot.php'); ?>
</body>

</html>