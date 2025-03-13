<?php
/**
 * 法人設置輸送入力画面を表示します。
 * @package    ssl_html
 * @subpackage PCS
 * @author     K.Hamada(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../../lib/Lib.php';
Sgmov_Lib::useView('pcs/Input');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Pcs_Input();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Pcs001Out
 */
$pcs001Out = $forms['outForm'];

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
    <meta name="Description" content="設置輸送のお問い合わせページです。">
    <meta name="author" content="SG MOVING Co.,Ltd">
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved.">
    <meta property="og:locale" content="ja_JP">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.sagawa-mov.co.jp/pcs/input/">
    <meta property="og:site_name" content="<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl-ogp.php'); ?>">
    <meta property="og:image" content="/img/ogp/og_image_sgmv.jpg">
    <meta property="twitter:card" content="summary_large_image">
    <link rel="shortcut icon" href="/img/common/favicon.ico">
    <link rel="apple-touch-icon" sizes="192x192" href="/img/ogp/touch-icon.png">
    <title>設置輸送のお問い合わせ｜お問い合わせ｜<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl.php'); ?></title>
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
                <h1 class="topLead">設置輸送のお問い合わせ<em>Inquiry</em></h1>
                <ul id="pagePath">
                    <li><a href="/"><img class="home" src="/img/common/icon54.jpg" alt=""></a></li>
                    <li><a href="/contact/">お問い合わせ</a></li>
                    <li>設置輸送のお問い合わせ</li>
                </ul>
            </div>
        </div>
        <!-- Start ************************************************ -->

        <!-- End ************************************************ -->
        <div class="wrap clearfix">
<!--            <p class="sentence">
                午前9時から午後5時までに拝見しております。<br />
                お問い合わせフォームからのお問い合わせにつきましては、お返事が数日かかる場合がございますので、あらかじめご了承ください。特にお急ぎのお客様は、恐れ入りますがナビダイヤル 0570-056-006(受付時間:9時～17時)までお電話にてお問い合わせください。
            </p>-->

    <?php if (isset($e) && $e->hasError()) { ?>
                        <div class="err_msg">
                            <p class="sentence br attention">下記の項目が正しく入力・選択されていません。</p>
                            <ul>
    <?php
            // エラー表示
            if ($e->hasErrorForId('top_inquiry_type_cd_sel')) {
                echo '<li><a href="#cate1">お問い合わせ種類' . $e->getMessage('top_inquiry_type_cd_sel') . '</a></li>';
            }
            if ($e->hasErrorForId('top_inquiry_category_cd_sel')) {
                echo '<li><a href="#cate2">お問い合わせカテゴリー' . $e->getMessage('top_inquiry_category_cd_sel') . '</a></li>';
            }
            if ($e->hasErrorForId('top_inquiry_title')) {
                echo '<li><a href="#comment">お問い合わせ件名' . $e->getMessage('top_inquiry_title') . '</a></li>';
            }
            if ($e->hasErrorForId('top_inquiry_content')) {
                echo '<li><a href="#comment">お問い合わせ内容' . $e->getMessage('top_inquiry_content') . '</a></li>';
            }
            if ($e->hasErrorForId('top_company_name')) {
                echo '<li><a href="#corp_name">会社名' . $e->getMessage('top_company_name') . '</a></li>';
            }
            if ($e->hasErrorForId('top_post_name')) {
                echo '<li><a href="#corp_name">部署名' . $e->getMessage('top_post_name') . '</a></li>';
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
                echo '<li><a href="#phone">電話種類その他' . $e->getMessage('top_tel_other') . '</a></li>';
            }
            if ($e->hasErrorForId('top_fax')) {
                echo '<li><a href="#fax">FAX番号' . $e->getMessage('top_fax') . '</a></li>';
            }
            if ($e->hasErrorForId('top_mail')) {
                echo '<li><a href="#mail">メールアドレス' . $e->getMessage('top_mail') . '</a></li>';
            }
            if ($e->hasErrorForId('top_contact_method_cd_sel')) {
                echo '<li><a href="#mail">連絡方法' . $e->getMessage('top_contact_method_cd_sel') . '</a></li>';
            }
            if ($e->hasErrorForId('top_zip')) {
                echo '<li><a href="#add">郵便番号' . $e->getMessage('top_zip') . '</a></li>';
            }
            if ($e->hasErrorForId('top_pref_cd_sel')) {
                echo '<li><a href="#add">都道府県' . $e->getMessage('top_pref_cd_sel') . '</a></li>';
            }
            if ($e->hasErrorForId('top_address')) {
                echo '<li><a href="#add">住所' . $e->getMessage('top_address') . '</a></li>';
            }
    ?>
                            </ul>
                        </div>
    <?php } ?>

            <!-- ▼お問い合わせ情報 ここから-->
        <form action="/pcs/check_input/" method="post">
            <div class="section">
                <h3 class="cont_inner_title">お問い合わせ情報</h3>
                <div class="dl_block">
                    <input type="hidden" name="ticket" value="<?php echo $ticket ?>" />
                    <dl id="pcs_form_list_01">
                        <dt id="cate1">種類<span>必須</span></dt>
                        <dd<?php if (isset($e) && $e->hasErrorForId('top_inquiry_type_cd_sel')) { echo ' class="form_error"'; } ?>>
                            <ul class="clearfix">
                                <li>
                                    <label for="inquiry_contact" class="radio-label">
                                        <input type="radio" id="inquiry_contact" value="1" name="inquiry_type_cd_sel" <?php if($pcs001Out->inquiry_type_cd_sel() === '1') echo ' checked="checked"'; ?> />
                                        お問い合わせ
                                    </label>
                                </li>
                                <li>
                                    <label for="inquiry_application" class="radio-label">
                                        <input type="radio" id="inquiry_application" value="2" name="inquiry_type_cd_sel" <?php if($pcs001Out->inquiry_type_cd_sel() === '2') echo ' checked="checked"'; ?> />
                                        お申し込み
                                    </label>
                                </li>
                            </ul>
                        </dd>
                    </dl>
                    <dl id="pcs_form_list_02">
                        <dt id="cate2">カテゴリー<span>必須</span></dt>
                        <dd<?php if (isset($e) && $e->hasErrorForId('top_inquiry_category_cd_sel')) { echo ' class="form_error"'; } ?>>
                            <ul class="clearfix">
                                <li>
                                    <label for="inquiry_furniture" class="radio-label">
                                        <input type="radio" id="inquiry_furniture" value="1" name="inquiry_category_cd_sel" <?php if($pcs001Out->inquiry_category_cd_sel() === '1') echo ' checked="checked"'; ?> />
                                        家具・家電の設置について
                                    </label>
                                </li>
<!--                                <li>
                                    <label for="inquiry_other" class="radio-label">
                                        <input type="radio" id="inquiry_other" value="3" name="inquiry_category_cd_sel" <?php if($pcs001Out->inquiry_category_cd_sel() === '3') echo ' checked="checked"'; ?> />
                                        その他設置について
                                    </label>
                                </li>-->
                                <li>
                                    <label for="inquiry_event" class="radio-label">
                                        <input type="radio" id="inquiry_event" value="4" name="inquiry_category_cd_sel" <?php if($pcs001Out->inquiry_category_cd_sel() === '4') echo ' checked="checked"'; ?> />
                                        イベント輸送について
                                    </label>
                                </li>
                                <li>
                                    <label for="inquiry_charter" class="radio-label">
                                        <input type="radio" id="inquiry_charter" value="5" name="inquiry_category_cd_sel" <?php if($pcs001Out->inquiry_category_cd_sel() === '5') echo ' checked="checked"'; ?> />
                                        チャーター輸送について
                                    </label>
                                </li>
                                <li>
                                    <label for="inquiry_setting" class="radio-label">
                                        <input type="radio" id="inquiry_setting" value="7" name="inquiry_category_cd_sel" <?php if($pcs001Out->inquiry_category_cd_sel() === '7') echo ' checked="checked"'; ?> />
                                        設置輸送について
                                    </label>
                                </li>
                                <li>
                                    <label for="inquiry_particular" class="radio-label">
                                        <input type="radio" id="inquiry_particular" value="8" name="inquiry_category_cd_sel" <?php if($pcs001Out->inquiry_category_cd_sel() === '8') echo ' checked="checked"'; ?> />
                                        特殊輸送について
                                    </label>
                                </li>
                                <li>
                                    <label for="inquiry_confidential_document" class="radio-label">
                                        <input type="radio" id="inquiry_confidential_document" value="9" name="inquiry_category_cd_sel" <?php if($pcs001Out->inquiry_category_cd_sel() === '9') echo ' checked="checked"'; ?> />
                                        機密文書について
                                    </label>
                                </li>
                                <li>
                                    <label for="inquiry_reserved" class="radio-label">
                                        <input type="radio" id="inquiry_reserved" value="10" name="inquiry_category_cd_sel" <?php if($pcs001Out->inquiry_category_cd_sel() === '10') echo ' checked="checked"'; ?> />
                                        貸切輸送について
                                    </label>
                                </li>
                                <li>
                                    <label for="inquiry_technical" class="radio-label">
                                        <input type="radio" id="inquiry_technical" value="11" name="inquiry_category_cd_sel" <?php if($pcs001Out->inquiry_category_cd_sel() === '11') echo ' checked="checked"'; ?> />
                                        精密機器・重量物輸送について
                                    </label>
                                </li>
                                <li>
                                    <label for="inquiry_art" class="radio-label">
                                        <input type="radio" id="inquiry_art" value="12" name="inquiry_category_cd_sel" <?php if($pcs001Out->inquiry_category_cd_sel() === '12') echo ' checked="checked"'; ?> />
                                        美術品輸送について
                                    </label>
                                </li>
                                <li>
                                    <label for="inquiry_cruise" class="radio-label">
                                        <input type="radio" id="inquiry_cruise" value="13" name="inquiry_category_cd_sel" <?php if($pcs001Out->inquiry_category_cd_sel() === '13') echo ' checked="checked"'; ?> />
                                        旅客手荷物受付について
                                    </label>
                                </li>
                                <li>
                                    <label for="inquiry_extension" class="radio-label">
                                        <input type="radio" id="inquiry_extension" value="14" name="inquiry_category_cd_sel" <?php if($pcs001Out->inquiry_category_cd_sel() === '14') echo ' checked="checked"'; ?> />
                                        延長保証支援サービスについて
                                    </label>
                                </li>
                                <li>
                                    <label for="inquiry_etc" class="radio-label">
                                        <input type="radio" id="inquiry_etc" value="6" name="inquiry_category_cd_sel" <?php if($pcs001Out->inquiry_category_cd_sel() === '6') echo ' checked="checked"'; ?> />
                                        その他
                                    </label>
                                </li>
                            </ul>
                        </dd>
                    </dl>
                    <dl id="pcs_form_list_03">
                        <dt>件名</dt>
                        <dd class="width_change<?php if (isset($e) && $e->hasErrorForId('top_inquiry_title')) { echo ' form_error'; } ?>">
                            <input type="text" class="w80p" maxlength="80" name="inquiry_title" value="<?php echo $pcs001Out->inquiry_title(); ?>" />
                        </dd>
                    </dl>
                    <dl id="pcs_form_list_04">
                        <dt id="comment">お問い合わせ内容<span>必須</span></dt>
                        <dd class="width_change<?php if (isset($e) && $e->hasErrorForId('top_inquiry_content')) { echo ' form_error'; } ?>">
                            <textarea class="w100p" rows="9" cols="" name="inquiry_content"><?php echo $pcs001Out->inquiry_content() ?></textarea>
                            <p>
                            ※1000文字まででお願いいたします。</p></dd>
                    </dl>
                </div>
            </div>
            <!-- ▲お問い合わせ情報 ここまで-->
            <!-- ▼お客様情報 ここから-->
            <div class="section">
                <h3 class="cont_inner_title">お客様情報</h3>
                <div class="dl_block">
                    <dl id="pcs_form_list_05">
                        <dt id="corp_name">会社名<span>必須</span></dt>
                        <dd<?php if (isset($e) && $e->hasErrorForId('top_company_name')) { echo ' class="form_error"'; } ?>>
                            <input type="text" value="<?php echo $pcs001Out->company_name(); ?>" maxlength="80" name="company_name" class="w_220">
                        </dd>
                    </dl>
                    <dl id="pcs_form_list_06">
                        <dt>部署名</dt>
                        <dd<?php if (isset($e) && $e->hasErrorForId('top_post_name')) { echo ' class="form_error"'; } ?>>
                            <input type="text" value="<?php echo $pcs001Out->post_name(); ?>" maxlength="80" name="post_name" class="w_220">
                        </dd>
                    </dl>
                    <dl id="pcs_form_list_07">
                        <dt id="name">担当者名<span>必須</span></dt>
                        <dd<?php if (isset($e) && $e->hasErrorForId('top_charge_name')) { echo ' class="form_error"'; } ?>>
                            <input type="text" value="<?php echo $pcs001Out->charge_name(); ?>" class="w200" maxlength="40" name="charge_name">
                        </dd>
                    </dl>
                    <dl id="pcs_form_list_08">
                        <dt id="furigana">担当者名フリガナ<span>必須</span></dt>
                        <dd<?php if (isset($e) && $e->hasErrorForId('top_charge_furigana')) { echo ' class="form_error"'; } ?>>
                            <input type="text" value="<?php echo $pcs001Out->charge_furigana(); ?>" class="w200" maxlength="40" name="charge_furigana">
                        </dd>
                    </dl>
                    <dl id="pcs_form_list_09">
                        <dt id="phone">電話番号<span>必須</span></dt>
                        <dd<?php if (isset($e) && ($e->hasErrorForId('top_tel') || $e->hasErrorForId('top_tel_type_cd_sel') || $e->hasErrorForId('top_tel_other'))) { echo ' class="form_error"'; } ?>>
                            <input type="text" value="<?php echo $pcs001Out->tel1(); ?>" class="w_70" maxlength="5" name="tel1">
                            -
                            <input type="text" value="<?php echo $pcs001Out->tel2(); ?>" class="w_70" maxlength="5" name="tel2">
                            -
                            <input type="text" value="<?php echo $pcs001Out->tel3(); ?>" class="w_70" maxlength="4" name="tel3">
                            <ul>
                                <li>
                                    <label for="tel_company" class="radio-label">
                                        <input<?php if($pcs001Out->tel_type_cd_sel() === '2') echo ' checked="checked"'; ?> type="radio" id="tel_company" value="2" name="tel_type_cd_sel">
                                        勤務先 </label>
                                </li>
                                <li>
                                    <label for="tel_mobile" class="radio-label">
                                        <input<?php if($pcs001Out->tel_type_cd_sel() === '1') echo ' checked="checked"'; ?> type="radio" id="tel_mobile" value="1" name="tel_type_cd_sel">
                                        携帯 </label>
                                </li>
                                <li>
                                    <label for="tel_etc" class="radio-label">
                                        <input<?php if($pcs001Out->tel_type_cd_sel() === '3') echo ' checked="checked"'; ?> type="radio" id="tel_etc" value="3" name="tel_type_cd_sel">
                                        その他 </label>
                                    <input type="text" value="<?php echo $pcs001Out->tel_other(); ?>" class="w65" maxlength="20" name="tel_other">
                                </li>
                            </ul>
                        </dd>
                    </dl>
                    <dl id="pcs_form_list_10">
                        <dt>FAX番号</dt>
                        <dd<?php if (isset($e) && $e->hasErrorForId('top_fax')) { echo ' class="form_error"'; } ?>>
                            <input type="text" value="<?php echo $pcs001Out->fax1(); ?>" class="w_70" maxlength="5" name="fax1">
                            -
                            <input type="text" value="<?php echo $pcs001Out->fax2(); ?>" class="w_70" maxlength="5" name="fax2">
                            -
                            <input type="text" value="<?php echo $pcs001Out->fax3(); ?>" class="w_70" maxlength="4" name="fax3">
                        </dd>
                    </dl>
                    <dl id="pcs_form_list_11">
                        <dt id="mail">メールアドレス<span>必須</span></dt>
                        <dd class="width_change<?php if (isset($e) && $e->hasErrorForId('top_mail')) { echo ' form_error'; } ?>">
                            <input type="text" value="<?php echo $pcs001Out->mail(); ?>" class="w_220" maxlength="80" name="mail">
                        </dd>
                    </dl>
                    <dl id="pcs_form_list_12">
                        <dt>連絡方法</dt>
                        <dd<?php if (isset($e) && $e->hasErrorForId('top_contact_method_cd_sel')) { echo ' class="form_error"'; } ?>>
                            <ul class="three_col clearfix">
                                <li>
                                    <label for="contact_phone" class="radio-label">
                                        <input type="radio" id="contact_phone" value="1" name="contact_method_cd_sel" <?php if($pcs001Out->contact_method_cd_sel() === '1') echo ' checked="checked"'; ?> />
                                        電話 </label>
                                <li>
                                <label for="contact_fax" class="radio-label">
                                    <input type="radio" id="contact_fax" value="2" name="contact_method_cd_sel" <?php if($pcs001Out->contact_method_cd_sel() === '2') echo ' checked="checked"'; ?> />
                                    FAX </label></li>
                                <li>
                                <label for="contact_mail" class="radio-label">
                                    <input type="radio" id="contact_mail" value="3" name="contact_method_cd_sel" <?php if($pcs001Out->contact_method_cd_sel() === '3') echo ' checked="checked"'; ?> />
                                    メール </label></li>
                            </ul>
                        </dd>
                    </dl>
                    <dl id="pcs_form_list_13">
                        <dt>電話連絡可能時間帯</dt>
                        <dd>
                                    <label for="contact_available1" class="radio-label right50">
                                        <input<?php if($pcs001Out->contact_available_cd_sel() === '2') echo ' checked="checked"'; ?> type="radio" id="contact_available1" value="2" name="contact_available_cd_sel">
                                        終日OK </label><br class="pcH">
                                    <label for="contact_available2" class="radio-label">
                                        <input<?php if($pcs001Out->contact_available_cd_sel() === '1') echo ' checked="checked"'; ?> type="radio" id="contact_available2" value="1" name="contact_available_cd_sel">
                                        時間指定 </label>
                                    <select name="contact_start_cd_sel">
                                        <option value=""></option>
    <?php
            $contact_start_cds = $pcs001Out->contact_start_cds();
            $contact_start_lbls = $pcs001Out->contact_start_lbls();
            $option = '';
            for ($i = 0; $i < 24; ++$i) {
                $cd = $contact_start_cds[$i];
                $lbl = $contact_start_lbls[$i];
                if ($pcs001Out->contact_start_cd_sel() === $cd) {
                    $option .= '<option value="'.$i.'" selected="selected">'.$i.'</option>'.PHP_EOL;
                } else {
                    $option .= '<option value="'.$i.'">'.$i.'</option>'.PHP_EOL;
                }
            }
            echo $option;
    ?>
                                    </select>
                                    時
                                    ～
                                    <select name="contact_end_cd_sel">
                                        <option value=""></option>
    <?php
            $contact_end_cds = $pcs001Out->contact_end_cds();
            $contact_end_lbls = $pcs001Out->contact_end_lbls();
            for($i = 0;$i<24;$i++){
                $cd = $contact_end_cds[$i];
                $lbl = $contact_end_lbls[$i];
                if($pcs001Out->contact_end_cd_sel() === $cd){
                    echo "<option value='{$i}' selected>{$i}</option>\n";
                }else{
                    echo "<option value='{$i}'>{$i}</option>\n";
                }
            }
    ?>
                                    </select>
                                    時
                        </dd>
                    </dl>
                    <dl id="pcs_form_list_14">
                        <dt id="add">住所<span>必須</span></dt>
                        <dd<?php if (isset($e) && ($e->hasErrorForId('top_zip') || $e->hasErrorForId('top_address') || $e->hasErrorForId('top_pref_cd_sel'))) { echo ' class="form_error"'; } ?>>
                            <ul>
                                <li> 〒
                                    <input type="text" value="<?php echo $pcs001Out->zip1(); ?>" class="w_70" maxlength="3" name="zip1">
                                    -
                                    <input type="text" value="<?php echo $pcs001Out->zip2(); ?>" class="w_70" maxlength="4" name="zip2">
                                    <input type="button" onClick="AjaxZip2.zip2addr('input_forms','zip1','pref_cd_sel','address','zip2','','', '<?php echo Sgmov_View_Pcs_Common::FEATURE_ID ?>', '<?php echo Sgmov_View_Pcs_Common::GAMEN_ID_PCS001 ?>', '<?php echo $ticket ?>');"value="住所検索" name="address_search_btn" class="button ml10" />
                                </li>
                                <li>
                                    <select class="w110" name="pref_cd_sel">
    <?php
            $pref_cds = $pcs001Out->pref_cds();
            $pref_lbls = $pcs001Out->pref_lbls();
            $count = count($pref_cds);
            $option = '';
            for ($i = 0; $i < $count; ++$i) {
                $cd = $pref_cds[$i];
                $lbl = $pref_lbls[$i];
                if ($pcs001Out->pref_cd_sel() === $cd) {
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
                                    <input type="text" value="<?php echo $pcs001Out->address(); ?>" class="w_220" maxlength="80" name="address">
                                    市町村以下 </li>
                            </ul>
                        </dd>
                    </dl>
                </div>
            </div>
            <!-- ▲お客様情報 ここまで-->
            <div class="border_box"><strong>お問い合わせにあたって</strong>
                <ul>
                    <li>ご連絡までにお時間をいただく場合がございます。ご了承ください。</li>
                    <li>電話番号、メールアドレスが誤っておりますとご連絡ができません。間違いがないかご確認ください。</li>
                </ul>
            </div>
            <div class="attention_area">

                <!--▼個人情報の取り扱いここから-->
                <?php
                    include_once dirname(__FILE__) . '/../parts/input_attention_area.php';
                ?>
                <!--▲個人情報の取り扱いここまで-->
            </div>
                <!-- △△個人情報の取り扱い　section　ここまで -->
                <div class="text_center comBtn02 btn01 fadeInUp animate">
                    <div class="btnInner">
                        <input id="submit_button" name="confirm_btn" type="button" value="同意して次に進む（入力内容の確認）" />
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
    <script> 
	if($('#checkbox_agreement').is(":checked")) {
		$("#submit_button").attr("disabled", false);
		document.getElementById("submit_button").style.opacity = "1";
	} else {
		$("#submit_button").attr("disabled", true);
		document.getElementById("submit_button").style.opacity = "0.2";
	}
	function changePageButton() {
            var checked = $('#checkbox_agreement').prop("checked");
            if (checked) {
                    $("#submit_button").attr("disabled", false);
                    document.getElementById("submit_button").style.opacity = "1";
            } else {
                    $("#submit_button").attr("disabled", true);
                    document.getElementById("submit_button").style.opacity = "0.2";
            }
	}

	$('#checkbox_agreement').on('click', function() {
		if($(this).is(":checked")) {
			$(this).val(1);
		} else {
			$(this).val(0);
		}
		changePageButton();
	});
    </script>
</body>

</html>