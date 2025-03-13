<?php
/**
 * 訪問見積もり申し込み入力画面を表示します。
 * @package    ssl_html
 * @subpackage PVE
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../../lib/Lib.php';
Sgmov_Lib::useView('pve/Input');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Pve_Input();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Pve001Out
 */
$pve001Out = $forms['outForm'];

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
    <meta name="Description" content="訪問お見積りページです。">
    <meta name="author" content="SG MOVING Co.,Ltd">
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved.">
    <meta property="og:locale" content="ja_JP">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.sagawa-mov.co.jp/pve/input/">
    <meta property="og:site_name" content="<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl-ogp.php'); ?>">
    <meta property="og:image" content="/img/ogp/og_image_sgmv.jpg">
    <meta property="twitter:card" content="summary_large_image">
    <link rel="shortcut icon" href="/img/common/favicon.ico">
    <link rel="apple-touch-icon" sizes="192x192" href="/img/ogp/touch-icon.png">
    <title><?php
    //タイトル
    if ($pve001Out->pre_exist_flag() === '1') {
        $title = 'お引越し申し込み';
    } else {
        $title = '訪問お見積りフォーム';
    }
    echo $title;
    ?>｜お問い合わせ｜<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl.php'); ?></title>
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
                <h1 class="topLead">訪問お見積りフォーム<em>Estimate</em></h1>
                <ul id="pagePath">
                    <li><a href="/"><img class="home" src="/img/common/icon54.jpg" alt=""></a></li>
                    <li><a href="/contact/">お問い合わせ</a></li>
                    <li>訪問お見積りフォーム</li>
                </ul>
            </div>
        </div>
        <!-- Start ************************************************ -->

        <!-- End ************************************************ -->
        <div class="wrap clearfix">
<!--<?php if ($pve001Out->pre_exist_flag() === '1') { ?>
            <h1 class="page_title">お引越し申し込み</h1>
<?php }else{ ?>
            <h1 class="page_title">訪問お見積りフォーム</h1>
<?php } ?>
-->
            <p class="sentence">
                お客様の下へお伺いし、詳細なお見積りを出させていただきます。 
                <br />下記の必要項目にご記入の上、送信ください。こちらよりご連絡させていただきます。
                <br /><span style="color:red;">※ご注意</span>
                <br />引越プランにつきまして下記の日程が予約で一杯となっており、お見積り・お申込みが出来ませんのでご注意ください。
                <br />また、期間外に関しましても受付できないエリア、日程もございます。あらかじめご容赦ください。
                <br />・家族引越 3月15日より4月8日まで
                <br />・単身引越(カーゴプラン含む) 3月21日より3月31日まで
                <br />・単品プラン・生活応援プラン 3月21日より3月31日まで
            </p>

<?php
    if (!empty($e) && $e->hasError()) {
?>
            <div class="err_msg">
                <p class="sentence br attention">[!ご確認ください]下記の項目が正しく入力・選択されていません。</p>
                <ul>
<?php
        // エラー表示
        if ($e->hasErrorForId('top_course_cd_sel')) {
            echo '<li><a href="#pve_form_list_01">お引越し先の間取り' . $e->getMessage('top_course_cd_sel') . '</a></li>';
        }
        if ($e->hasErrorForId('top_plan_cd_sel')) {
            echo '<li><a href="#pve_form_list_02">ご希望のお引越しプラン' . $e->getMessage('top_plan_cd_sel') . '</a></li>';
        }
        if ($e->hasErrorForId('top_from_area_cd_sel')) {
            echo '<li><a href="#pve_form_list_03">現在お住まいの地域' . $e->getMessage('top_from_area_cd_sel') . '</a></li>';
        }
        if ($e->hasErrorForId('top_to_area_cd_sel')) {
            echo '<li><a href="#pve_form_list_04">お引越し先の地域' . $e->getMessage('top_to_area_cd_sel') . '</a></li>';
        }
        if ($e->hasErrorForId('top_cource_plan_from_to')) {
            echo '<li><a href="#pve_form_list_01">' . $e->getMessage('top_cource_plan_from_to') . '</a></li>';
        }
        if ($e->hasErrorForId('top_move_date')) {
            echo '<li><a href="#pve_form_list_05">お引越し予定日' . $e->getMessage('top_move_date') . '</a></li>';
        }
        if ($e->hasErrorForId("apartment_cd_sel")) {
            echo '<li><a href="#pve_form_04">マンション' . $e->getMessage("apartment_cd_sel") . '</a></li>';
        }
        if ($e->hasErrorForId('top_visit_date1')) {
            echo '<li><a href="#pve_form_list_07">訪問見積もり希望日「第一希望日」' . $e->getMessage('top_visit_date1') . '</a></li>';
        }
        if ($e->hasErrorForId('top_visit_date2')) {
            echo '<li><a href="#pve_form_list_07">訪問見積もり希望日「第二希望日」' . $e->getMessage('top_visit_date2') . '</a></li>';
        }
        if ($e->hasErrorForId('top_cur_zip')) {
            echo '<li><a href="#pve_form_list_08">現住所　郵便番号' . $e->getMessage('top_cur_zip') . '</a></li>';
        }
        if ($e->hasErrorForId('top_cur_pref_cd_sel')) {
            echo '<li><a href="#pve_form_list_08">現住所　都道府県' . $e->getMessage('top_cur_pref_cd_sel') . '</a></li>';
        }
        if ($e->hasErrorForId('top_cur_address')) {
            echo '<li><a href="#pve_form_list_08">現住所　住所' . $e->getMessage('top_cur_address') . '</a></li>';
        }
        if ($e->hasErrorForId('top_cur_floor')) {
            echo '<li><a href="#pve_form_list_10">現住所　階数' . $e->getMessage('top_cur_floor') . '</a></li>';
        }
        if ($e->hasErrorForId('top_new_zip')) {
            echo '<li><a href="#pve_form_list_12">新住所　郵便番号' . $e->getMessage('top_new_zip') . '</a></li>';
        }
        if ($e->hasErrorForId('top_new_pref_cd_sel')) {
            echo '<li><a href="#pve_form_list_12">新住所　都道府県' . $e->getMessage('top_new_pref_cd_sel') . '</a></li>';
        }
        if ($e->hasErrorForId('top_new_address')) {
            echo '<li><a href="#pve_form_list_12">新住所　住所' . $e->getMessage('top_new_address') . '</a></li>';
        }
        if ($e->hasErrorForId('top_new_floor')) {
            echo '<li><a href="#pve_form_list_14">新住所　階数' . $e->getMessage('top_new_floor') . '</a></li>';
        }
        if ($e->hasErrorForId('top_name')) {
            echo '<li><a href="#pve_form_list_16">お名前' . $e->getMessage('top_name') . '</a></li>';
        }
        if ($e->hasErrorForId('top_furigana')) {
            echo '<li><a href="#pve_form_list_17">フリガナ' . $e->getMessage('top_furigana') . '</a></li>';
        }
        if ($e->hasErrorForId('top_mail')) {
            echo '<li><a href="#pve_form_list_18">メールアドレス' . $e->getMessage('top_mail') . '</a></li>';
        }
        if ($e->hasErrorForId('top_tel')) {
            echo '<li><a href="#pve_form_list_19">電話番号' . $e->getMessage('top_tel') . '</a></li>';
        }
        if ($e->hasErrorForId('top_tel_type')) {
            echo '<li><a href="#pve_form_list_19">電話番号種類' . $e->getMessage('top_tel_type') . '</a></li>';
        }
        if ($e->hasErrorForId('top_tel_other')) {
            echo '<li><a href="#pve_form_list_19">電話番号種類その他' . $e->getMessage('top_tel_other') . '</a></li>';
        }
        if ($e->hasErrorForId('top_comment')) {
            echo '<li><a href="#pve_form_list_20">備考' . $e->getMessage('top_comment') . '</a></li>';
        }
?>
                </ul>
            </div>
<?php
    }
?>

            <form action="/pve/check_input" data-feature-id="<?php echo Sgmov_View_Pve_Common::FEATURE_ID; ?>" data-id="<?php echo Sgmov_View_Pve_Common::GAMEN_ID_PVE001; ?>" method="post">
<?php
    if ($pve001Out->pre_exist_flag() === '1') {
?>

                <div class="section">

                    <h2 class="section_title">概算お見積り条件</h2>

                    <!--▼条件表示エリアここから-->
                    <div class="dl_block">
                        <dl>
                            <dt>お引越しコース</dt>
                            <dd>
                                <?php echo $pve001Out->pre_course(); ?>

                            </dd>
                        </dl>
                        <dl>
                            <dt>お引越しプラン</dt>
                            <dd>
                                <?php echo $pve001Out->pre_plan(); ?>

                            </dd>
                        </dl>
                        <dl>
                            <dt>お引越し先</dt>
                            <dd>
                                <?php echo $pve001Out->pre_from_area(); ?>から<?php echo $pve001Out->pre_to_area(); ?>

                            </dd>
                        </dl>
                        <dl>
                            <dt>お引越し予定日</dt>
                            <dd>
                                <?php
        echo substr($pve001Out->pre_move_date(), 0, 4) . '年' . substr($pve001Out->pre_move_date(), 4, 2) . '月' . substr($pve001Out->pre_move_date(), 6, 2) . '日';
                                ?>

                            </dd>
                        </dl>
                        <dl>
                            <dt>概算お見積り金額</dt>
                            <dd>
                                &yen;<?php echo number_format($pve001Out->pre_estimate_price()); ?>

                            </dd>
                        </dl>
                        <dl>
                            <dt>エアコンの取り付け、取り外し</dt>
                            <dd>
                                <?php echo Sgmov_View_Pve_Common::_getAirconKbnNm($pve001Out->pre_aircon_exist()); ?>

                            </dd>
                        </dl>
<?php
        // キャンペーン情報が存在する場合、出力する
        echo Sgmov_View_Pve_Common::_createCampInfoHtml($pve001Out->pre_cam_discount_names(), $pve001Out->pre_cam_discount_contents(),
                    $pve001Out->pre_cam_discount_starts(), $pve001Out->pre_cam_discount_ends());
?>

                    </div>
                </div>

<?php
    }
?>

                <input name="pre_exist_flag" type="hidden" value="<?php echo $pve001Out->pre_exist_flag(); ?>"/>
                <input name="pre_course" type="hidden" value="<?php echo $pve001Out->pre_course(); ?>"/>
                <input name="pre_plan" type="hidden" value="<?php echo $pve001Out->pre_plan(); ?>"/>
                <input name="pre_from_area" type="hidden" value="<?php echo $pve001Out->pre_from_area(); ?>"/>
                <input name="pre_to_area" type="hidden" value="<?php echo $pve001Out->pre_to_area(); ?>"/>
                <input name="pre_move_date" type="hidden" value="<?php echo $pve001Out->pre_move_date(); ?>"/>
                <input name="pre_estimate_price" type="hidden" value="<?php echo $pve001Out->pre_estimate_price(); ?>"/>
                <input name="pre_estimate_base_price" type="hidden" value="<?php echo $pve001Out->pre_estimate_base_price(); ?>"/>
                <input name="pre_aircon_exist" type="hidden" value="<?php echo $pve001Out->pre_aircon_exist(); ?>"/>
                <!--
                <input name="pre_cam_discount_names" type="hidden" value="<?php echo serialize($pve001Out->pre_cam_discount_names()); ?>"/>
                <input name="pre_cam_discount_contents" type="hidden" value="<?php echo serialize($pve001Out->pre_cam_discount_contents()); ?>"/>
                <input name="pre_cam_discount_starts" type="hidden" value="<?php echo serialize($pve001Out->pre_cam_discount_starts()); ?>"/>
                <input name="pre_cam_discount_ends" type="hidden" value="<?php echo serialize($pve001Out->pre_cam_discount_ends()); ?>"/>
                -->
<?php
    if ($pve001Out->pre_exist_flag() === '1') {
        echo '<br /><div style="display: none;">';
    }
?>

                <div class="section" id="pve_form_list_01">
                    <h3 class="column_title">1.お引越し先の間取りをお選びください。<?php echo $pve001Out->course_cd_sel(); ?></h3>
                    <dl class="plan_column spTabH">
                        <dt>間取り</dt>
                        <dd<?php if (!empty($e) && $e->hasErrorForId('top_course_cd_sel')) { echo ' class="form_error"'; } ?>>
                            <ul class="form_radio clearfix">
                                <li>
                                    <label class="radio-label" for="i1">
                                        <input<?php if ($pve001Out->course_cd_sel() === '1') echo ' checked="checked"'; ?> id="i1" name="course_cd_sel" type="radio" value="1" />
                                        1部屋（小）
                                    </label>
                                </li>
                                <li>
                                    <label class="radio-label" for="i2">
                                        <input<?php if ($pve001Out->course_cd_sel() === '2') echo ' checked="checked"'; ?> id="i2" name="course_cd_sel" type="radio" value="2" />
                                        ワンルーム
                                    </label>
                                </li>
                                <li>
                                    <label class="radio-label" for="i3">
                                        <input<?php if ($pve001Out->course_cd_sel() === '3') echo ' checked="checked"'; ?> id="i3" name="course_cd_sel" type="radio" value="3" />
                                        1K
                                    </label>
                                </li>
                                <li>
                                    <label class="radio-label" for="i4">
                                        <input<?php if ($pve001Out->course_cd_sel() === '4') echo ' checked="checked"'; ?> id="i4" name="course_cd_sel" type="radio" value="4" />
                                        1DK、2K
                                    </label>
                                </li>
                                <li>
                                    <label class="radio-label" for="i5">
                                        <input<?php if ($pve001Out->course_cd_sel() === '5') echo ' checked="checked"'; ?> id="i5" name="course_cd_sel" type="radio" value="5" />
                                        1LDK、2DK
                                    </label>
                                </li>
                                <li>
                                    <label class="radio-label" for="i6">
                                        <input<?php if ($pve001Out->course_cd_sel() === '6') echo ' checked="checked"'; ?> id="i6" name="course_cd_sel" type="radio" value="6" />
                                        2LDK、3DK、4K
                                    </label>
                                </li>
                                <li>
                                    <label class="radio-label" for="i7">
                                        <input<?php if ($pve001Out->course_cd_sel() === '7') echo ' checked="checked"'; ?> id="i7" name="course_cd_sel" type="radio" value="7" />
                                        3LDK、4DK、5K
                                    </label>
                                </li>
                                <li>
                                    <label class="radio-label" for="i8">
                                        <input<?php if ($pve001Out->course_cd_sel() === '8') echo ' checked="checked"'; ?> id="i8" name="course_cd_sel" type="radio" value="8" />
                                        4LDK、5DK、6K
                                    </label>
                                </li>
                            </ul>
                            <img alt="" id="courseImg" style="display:none;" />
                            <p class="sentence text_caution" id="courseMsg" style="display:none;"></p>
                        </dd>
                    </dl>
                    <dl class="plan_column pcH">
                        <dd<?php if (!empty($e) && $e->hasErrorForId('top_course_cd_sel')) { echo ' class="form_error"'; } ?>>
                            <div id="course">
                                <ul class="form_radio clearfix">
                                    <li>
                                        <label class="radio-label" for="i1s">
                                            <input<?php if ($pve001Out->course_cd_sel() === '1') echo ' checked="checked"'; ?> id="i1s" name="course_cd_sel2" type="radio" value="1" />
                                            1部屋（小）
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="i2s">
                                            <input<?php if ($pve001Out->course_cd_sel() === '2') echo ' checked="checked"'; ?> id="i2s" name="course_cd_sel2" type="radio" value="2" />
                                            ワンルーム
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="i3s">
                                            <input<?php if ($pve001Out->course_cd_sel() === '3') echo ' checked="checked"'; ?> id="i3s" name="course_cd_sel2" type="radio" value="3" />
                                            1K
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="i4s">
                                            <input<?php if ($pve001Out->course_cd_sel() === '4') echo ' checked="checked"'; ?> id="i4s" name="course_cd_sel2" type="radio" value="4" />
                                            1DK、2K
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="i5s">
                                            <input<?php if ($pve001Out->course_cd_sel() === '5') echo ' checked="checked"'; ?> id="i5s" name="course_cd_sel2" type="radio" value="5" />
                                            1LDK、2DK
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="i6s">
                                            <input<?php if ($pve001Out->course_cd_sel() === '6') echo ' checked="checked"'; ?> id="i6s" name="course_cd_sel2" type="radio" value="6" />
                                            2LDK、3DK、4K
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="i7s">
                                            <input<?php if ($pve001Out->course_cd_sel() === '7') echo ' checked="checked"'; ?> id="i7s" name="course_cd_sel2" type="radio" value="7" />
                                            3LDK、4DK、5K
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="i8s">
                                            <input<?php if ($pve001Out->course_cd_sel() === '8') echo ' checked="checked"'; ?> id="i8s" name="course_cd_sel2" type="radio" value="8" />
                                            4LDK、5DK、6K
                                        </label>
                                    </li>
                                </ul>
                            </div>
                            <p class="text_center">およその間取り</p>
                            <p class="sentence text_caution" id="courseMsg2" style="display:none;"></p>
                        </dd>
                    </dl>
                </div>

                <div class="section" id="pve_form_list_02">
                    <h3 class="column_title">2.ご希望のお引越しプランをお選びください。</h3>
                    <div class="plan_table">
                        <div class="pc_table spTabH">
                            <table>
                                <tr>
                                    <th rowspan="2" width="60px">選択</th>
                                    <th rowspan="2" width="185px">プラン</th>
                                    <th rowspan="2" width="258px">サービス内容</th>
                                    <th colspan="7">サービスの範囲</th>
                                </tr>
                                <tr>
                                    <th>箱詰め</th>
                                    <th>各種工事</th>
                                    <th>搬出</th>
                                    <th>移動</th>
                                    <th>搬入</th>
                                    <th>設置</th>
                                    <th>開梱</th>
                                </tr>
                                <tr>
                                    <td>
                                        <input <?php if ($pve001Out->plan_cd_sel() == '1') echo ' checked="checked"'; ?> id="p1" name="plan_cd_sel" type="radio" value="1" />
                                    </td>
                                    <td class="plan_name">
                                        <label for="p1">
                                            単身カーゴプラン
                                        </label>
                                    </td>
                                    <td class="plan_text">カーゴ単位のお得な料金プラン。</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><img alt="○" src="/images/personal/basics/icon_img01.png" /></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input <?php if ($pve001Out->plan_cd_sel() == '2') echo ' checked="checked"'; ?> id="p2" name="plan_cd_sel" type="radio" value="2" />
                                    </td>
                                    <td class="plan_name">
                                        <label for="p2">
                                            単身AIR CARGO プラン
                                        </label>
                                    </td>
                                    <td class="plan_text">ジェット機用荷物コンテナを貸切。急な転勤などにもおすすめです。</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><img alt="○" src="/images/personal/basics/icon_img01.png" /></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input<?php if ($pve001Out->plan_cd_sel() == '4') echo ' checked="checked"'; ?> id="p4" name="plan_cd_sel" type="radio" value="4" />
                                    </td>
                                    <td class="plan_name">
                                        <label for="p4">
                                            まるごとおまかせプラン
                                        </label>
                                    </td>
                                    <td class="plan_text">梱包から開梱まですべておまかせ頂くプラン</td>
                                    <td><img alt="○" src="/images/personal/basics/icon_img01.png" /></td>
                                    <td><img alt="○" src="/images/personal/basics/icon_img01.png" /></td>
                                    <td><img alt="○" src="/images/personal/basics/icon_img01.png" /></td>
                                    <td><img alt="○" src="/images/personal/basics/icon_img01.png" /></td>
                                    <td><img alt="○" src="/images/personal/basics/icon_img01.png" /></td>
                                    <td><img alt="○" src="/images/personal/basics/icon_img01.png" /></td>
                                    <td><img alt="○" src="/images/personal/basics/icon_img01.png" /></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input<?php if ($pve001Out->plan_cd_sel() == '3') echo ' checked="checked"'; ?> id="p3" name="plan_cd_sel" type="radio" value="3" />
                                    </td>
                                    <td class="plan_name">
                                        <label for="p3">
                                            スタンダードプラン
                                        </label>
                                    </td>
                                    <td class="plan_text">できることは自分でする標準的なプラン</td>
                                    <td></td>
                                    <td><img alt="○" src="/images/personal/basics/icon_img01.png" /></td>
                                    <td><img alt="○" src="/images/personal/basics/icon_img01.png" /></td>
                                    <td><img alt="○" src="/images/personal/basics/icon_img01.png" /></td>
                                    <td><img alt="○" src="/images/personal/basics/icon_img01.png" /></td>
                                    <td><img alt="○" src="/images/personal/basics/icon_img01.png" /></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input<?php if ($pve001Out->plan_cd_sel() == '5') echo ' checked="checked"'; ?> id="p5" name="plan_cd_sel" type="radio" value="5" />
                                    </td>
                                    <td class="plan_name">
                                        <label for="p5">
                                            チャータープラン
                                        </label>
                                    </td>
                                    <td class="plan_text">トラックのみをご利用する最小限のサポートプラン</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><img alt="○" src="/images/personal/basics/icon_img01.png" /></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                        <div class="sp_table pcH">

                            <div class="select_plan">
                                <div class="plan_name">
                                    <label class="radio-label" for="p1s">
                                        <input<?php if ($pve001Out->plan_cd_sel() == '1') echo ' checked="checked"'; ?> id="p1s" name="plan_cd_sel2" type="radio" value="1" />
                                        単身カーゴプラン
                                    </label>
                                </div>
                                <p class="sentence">カーゴ単位のお得な料金プラン。</p>
                                <div class="cont_inner_title">
                                    <h4>サービスの範囲</h4>
                                </div>
                                <ul class="service_icon clearfix">
                                    <li>移動</li>
                                </ul>
                            </div>

                            <div class="select_plan">
                                <div class="plan_name">
                                    <label class="radio-label" for="p2s">
                                        <input<?php if ($pve001Out->plan_cd_sel() == '2') echo ' checked="checked"'; ?> id="p2s" name="plan_cd_sel2" type="radio" value="2" />
                                        単身AIR CARGO プラン
                                    </label>
                                </div>
                                <p class="sentence">ジェット機用荷物コンテナを貸切。急な転勤などにもおすすめです。</p>
                                <div class="cont_inner_title">
                                    <h4>サービスの範囲</h4>
                                </div>
                                <ul class="service_icon clearfix">
                                    <li>移動</li>
                                </ul>
                            </div>

                            <div class="select_plan">
                                <div class="plan_name">
                                    <label class="radio-label" for="p4s">
                                        <input<?php if ($pve001Out->plan_cd_sel() == '4') echo ' checked="checked"'; ?> id="p4s" name="plan_cd_sel2" type="radio" value="4" />
                                        まるごとおまかせプラン
                                    </label>
                                </div>
                                <p class="sentence">梱包から開梱まですべておまかせ頂くプラン</p>
                                <div class="cont_inner_title">
                                    <h4>サービスの範囲</h4>
                                </div>
                                <ul class="service_icon clearfix">
                                    <li>箱詰め</li>
                                    <li>各種工事</li>
                                    <li>搬出</li>
                                    <li>移動</li>
                                    <li>搬入</li>
                                    <li>設置</li>
                                    <li>開梱</li>
                                </ul>
                            </div>

                            <div class="select_plan">
                                <div class="plan_name">
                                    <label class="radio-label" for="p3s">
                                        <input<?php if ($pve001Out->plan_cd_sel() == '3') echo ' checked="checked"'; ?> id="p3s" name="plan_cd_sel2" type="radio" value="3" />
                                        スタンダードプラン
                                    </label>
                                </div>
                                <p class="sentence">できることは自分でする標準的なプラン</p>
                                <div class="cont_inner_title">
                                    <h4>サービスの範囲</h4>
                                </div>
                                <ul class="service_icon clearfix">
                                    <li>各種工事</li>
                                    <li>搬出</li>
                                    <li>移動</li>
                                    <li>搬入</li>
                                    <li>設置</li>
                                </ul>
                            </div>

                            <div class="select_plan">
                                <div class="plan_name">
                                    <label class="radio-label" for="p5s">
                                        <input<?php if ($pve001Out->plan_cd_sel() == '5') echo ' checked="checked"'; ?> id="p5s" name="plan_cd_sel2" type="radio" value="5" />
                                        チャータープラン
                                    </label>
                                </div>
                                <p class="sentence">トラックのみをご利用する最小限のサポートプラン</p>
                                <div class="cont_inner_title">
                                    <h4>サービスの範囲</h4>
                                </div>
                                <ul class="service_icon clearfix">
                                    <li>移動</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!--plan_table-->
                </div>

                <div class="section other">
                    <h3 class="column_title">3.その他のお引越し条件をお選びください。</h3>
                    <div class="dl_block">
                        <dl class="default_dl" id="pve_form_list_03">
                            <dt id="add_now">現在お住まいの地域<span>必須</span></dt>
                            <dd<?php
        if (!empty($e)
            && ($e->hasErrorForId('top_from_area_cd_sel') || $e->hasErrorForId('top_cource_plan_from_to'))
        ) {
            echo ' class="form_error"';
        }
                            ?>>
                            <span id="planMsg"></span>
                                <select name="from_area_cd_sel" id="fromarea1">
<?php
        echo Sgmov_View_Pve_Input::_createAreaPulldown($pve001Out->from_area_cds(), $pve001Out->from_area_lbls(), $pve001Out->from_area_cd_sel(), Sgmov_View_Pve_Common::AREA_HYOJITYPE_NORMAL);
?>
                                </select>
                                <select style="display: none;" name="from_area_cd_sel" id="fromarea2">
<?php
        echo Sgmov_View_Pve_Input::_createAreaPulldown($pve001Out->from_area_cds(), $pve001Out->from_area_lbls(), $pve001Out->from_area_cd_sel(), Sgmov_View_Pve_Common::AREA_HYOJITYPE_OKINAWANASHI);
?>
                                </select>
                                <select style="display: none;" name="from_area_cd_sel" id="fromarea3">
<?php
        echo Sgmov_View_Pve_Input::_createAreaPulldown($pve001Out->from_area_cds(), $pve001Out->from_area_lbls(), $pve001Out->from_area_cd_sel(), Sgmov_View_Pve_Common::AREA_HYOJITYPE_AIRCARGO_TO);
?>
                                </select>
                            </dd>
                        </dl>
                        <dl>
                            <dt id="pve_form_list_04">お引越し先の地域<span>必須</span></dt>
                            <dd<?php
        if (!empty($e)
            && ($e->hasErrorForId('top_to_area_cd_sel') || $e->hasErrorForId('top_cource_plan_from_to'))
        ) {
            echo ' class="form_error"';
        }
                            ?>>
                                <select name="to_area_cd_sel" id="toarea1">
<?php
        echo Sgmov_View_Pve_Input::_createAreaPulldown($pve001Out->to_area_cds(), $pve001Out->to_area_lbls(), $pve001Out->to_area_cd_sel(), Sgmov_View_Pve_Common::AREA_HYOJITYPE_NORMAL);
?>
                                </select>
                                <select style="display: none;" name="to_area_cd_sel" id="toarea2">
<?php
        echo Sgmov_View_Pve_Input::_createAreaPulldown($pve001Out->to_area_cds(), $pve001Out->to_area_lbls(), $pve001Out->to_area_cd_sel(), Sgmov_View_Pve_Common::AREA_HYOJITYPE_OKINAWANASHI);
?>
                                </select>
                                <select style="display: none;" name="to_area_cd_sel" id="toarea3">
<?php
        echo Sgmov_View_Pve_Input::_createAreaPulldown($pve001Out->to_area_cds(), $pve001Out->to_area_lbls(), $pve001Out->to_area_cd_sel(), Sgmov_View_Pve_Common::AREA_HYOJITYPE_AIRCARGO);
?>
                                </select>
                                <select style="display: none;" name="to_area_cd_sel" id="toarea4">
<?php
        echo Sgmov_View_Pve_Input::_createAreaPulldown($pve001Out->to_area_cds(), $pve001Out->to_area_lbls(), $pve001Out->to_area_cd_sel(), Sgmov_View_Pve_Common::AREA_HYOJITYPE_AIRCARGO_TO_FUK);
?>
                                </select>
                                <select style="display: none;" name="to_area_cd_sel" id="toarea5">
<?php
        echo Sgmov_View_Pve_Input::_createAreaPulldown($pve001Out->to_area_cds(), $pve001Out->to_area_lbls(), $pve001Out->to_area_cd_sel(), Sgmov_View_Pve_Common::AREA_HYOJITYPE_AIRCARGO_TO_TOK);
?>
                                </select>
                                <select style="display: none;" name="to_area_cd_sel" id="toarea6">
<?php
        echo Sgmov_View_Pve_Input::_createAreaPulldown($pve001Out->to_area_cds(), $pve001Out->to_area_lbls(), $pve001Out->to_area_cd_sel(), Sgmov_View_Pve_Common::AREA_HYOJITYPE_AIRCARGO_TO_HOK);
?>
                                </select>
                            </dd>
                        </dl>
                        <dl>
                            <dt id="pve_form_list_05">お引越し予定日</dt>
                            <dd<?php if (!empty($e) && $e->hasErrorForId('top_move_date')) { echo ' class="form_error"'; } ?>>
                                <select name="move_date_year_cd_sel">
<?php
        echo Sgmov_View_Pve_Input::_createPulldown($pve001Out->move_date_year_cds(), $pve001Out->move_date_year_lbls(), $pve001Out->move_date_year_cd_sel());
?>
                                </select>
                                年
                                <select name="move_date_month_cd_sel">
<?php
        echo Sgmov_View_Pve_Input::_createPulldown($pve001Out->move_date_month_cds(), $pve001Out->move_date_month_lbls(), $pve001Out->move_date_month_cd_sel());
?>
                                </select>
                                月
                                <select name="move_date_day_cd_sel">
<?php
        echo Sgmov_View_Pve_Input::_createPulldown($pve001Out->move_date_day_cds(), $pve001Out->move_date_day_lbls(), $pve001Out->move_date_day_cd_sel());
?>
                                </select>
                                日
                                <br class="pcH" />※1週間後～2ヶ月先までの日付
                            </dd>
                        </dl>
                    </div>
                </div>
<?php if ($pve001Out->pre_exist_flag() === '1') {
        echo '</div>';
}?>
                <div class="section other">
                    <h3 class="column_title">4.詳細なお引越し情報を入力してください。</h3>
                    <div class="dl_block">
                        <dl id="pve_form_list_07">
                            <dt id="corp_name">訪問お見積り希望日</dt>
                            <dd<?php if (!empty($e) && ($e->hasErrorForId('top_visit_date1') || $e->hasErrorForId('top_visit_date2'))) { echo ' class="form_error"'; } ?>>
                                <ul>
                                    <li> 第1希望日 <br class="pcH">
                                        <select name="visit_date1_year_cd_sel">
<?php
        echo Sgmov_View_Pve_Input::_createPulldown($pve001Out->move_date_year_cds(), $pve001Out->move_date_year_lbls(), $pve001Out->visit_date1_year_cd_sel());
?>
                                        </select>
                                        年
                                        <select name="visit_date1_month_cd_sel">
<?php
        echo Sgmov_View_Pve_Input::_createPulldown($pve001Out->move_date_month_cds(), $pve001Out->move_date_month_lbls(), $pve001Out->visit_date1_month_cd_sel());
?>
                                        </select>
                                        月
                                        <select name="visit_date1_day_cd_sel">
<?php
        echo Sgmov_View_Pve_Input::_createPulldown($pve001Out->move_date_day_cds(), $pve001Out->move_date_day_lbls(), $pve001Out->visit_date1_day_cd_sel());
?>
                                        </select>
                                        日 <br class="pcH">
                                        ※1週間後～2ヶ月先までの日付 </li>
                                    <li> 第2希望日 <br class="pcH">
                                        <select name="visit_date2_year_cd_sel">
<?php
        echo Sgmov_View_Pve_Input::_createPulldown($pve001Out->move_date_year_cds(), $pve001Out->move_date_year_lbls(), $pve001Out->visit_date2_year_cd_sel());
?>
                                        </select>
                                        年
                                        <select name="visit_date2_month_cd_sel">
<?php
        echo Sgmov_View_Pve_Input::_createPulldown($pve001Out->move_date_month_cds(), $pve001Out->move_date_month_lbls(), $pve001Out->visit_date2_month_cd_sel());
?>
                                        </select>
                                        月
                                        <select name="visit_date2_day_cd_sel">
<?php
        echo Sgmov_View_Pve_Input::_createPulldown($pve001Out->move_date_day_cds(), $pve001Out->move_date_day_lbls(), $pve001Out->visit_date2_day_cd_sel());
?>
                                        </select>
                                        日 <br class="pcH">
                                        ※1週間後～2ヶ月先までの日付 </li>
                                </ul>
                            </dd>
                        </dl>
                    </div>
                    <h4 class="cont_inner_title">現在のお住まいについて</h4>
                    <div class="dl_block">
                        <dl id="pve_form_list_08">
                            <dt id="add_now">現住所<span>必須</span></dt>
                            <dd<?php
        if (!empty($e)
            && ($e->hasErrorForId('top_cur_zip') || $e->hasErrorForId('top_cur_pref_cd_sel') || $e->hasErrorForId('top_cur_address'))
        ) {
            echo ' class="form_error"';
        }
                            ?>>
                                <ul>
                                    <li>
                                        〒
                                        <input type="text" name="cur_zip1" maxlength="3" class="w_70" value='<?php echo $pve001Out->cur_zip1(); ?>' />
                                        -
                                        <input type="text" name="cur_zip2" maxlength="4" class="w_70" value='<?php echo $pve001Out->cur_zip2(); ?>' />
                                        <input name="cur_adrs_search_btn" type="button" value="住所検索" class="button ml10" />
                                    </li>
                                    <li>
                                        <select name="cur_pref_cd_sel">
<?php
        echo Sgmov_View_Pve_Input::_createPulldown($pve001Out->cur_pref_cds(), $pve001Out->cur_pref_lbls(), $pve001Out->cur_pref_cd_sel());
?>
                                        </select>
                                        都道府県
                                    </li>
                                    <li>
                                        <input type="text" name="cur_address" maxlength="40" class="w_220" value="<?php echo $pve001Out->cur_address() ?>" />
                                        市町村以下
                                    </li>
                                </ul>
                            </dd>
                        </dl>
                        <dl>
                            <dt>エレベーターの有無</dt>
                            <dd>
                                <ul class="three_col clearfix">
                                    <li>
                                        <label class="radio-label" for="cur_elevator1">
                                            <input<?php if ($pve001Out->cur_elevator_cd_sel() === '1') echo ' checked="checked"'; ?> id="cur_elevator1" name="cur_elevator_cd_sel" type="radio" value="1" />
                                            あり
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="cur_elevator2">
                                            <input<?php if ($pve001Out->cur_elevator_cd_sel() === '0') echo ' checked="checked"'; ?> id="cur_elevator2" name="cur_elevator_cd_sel" type="radio" value="0" />
                                            なし
                                        </label>
                                    </li>
                                </ul>
                            </dd>
                        </dl>
                        <dl>
                            <dt id="pve_form_list_10">現在お住まいの階</dt>
                            <dd>
                                <input type="text" name="cur_floor" value="<?php echo $pve001Out->cur_floor(); ?>" size="3" maxlength="2" istyle="4" format="3N" mode="numeric" />
                                階
                            </dd>
                        </dl>
                        <dl>
                            <dt>住居前道幅</dt>
                            <dd>
                                <ul class="three_col clearfix">
                                    <li>
                                        <label class="radio-label" for="cur_road1">
                                            <input<?php if ($pve001Out->cur_road_cd_sel() === '1') echo ' checked="checked"'; ?> id="cur_road1" name="cur_road_cd_sel" type="radio" value="1" />
                                            車両通行不可
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="cur_road2">
                                            <input<?php if ($pve001Out->cur_road_cd_sel() === '2') echo ' checked="checked"'; ?> id="cur_road2" name="cur_road_cd_sel" type="radio" value="2" />
                                            1台通行可
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="cur_road3">
                                            <input<?php if ($pve001Out->cur_road_cd_sel() === '3') echo ' checked="checked"'; ?> id="cur_road3" name="cur_road_cd_sel" type="radio" value="3" />
                                            2台すれ違い可
                                        </label>
                                    </li>
                                </ul>
                            </dd>
                        </dl>
                    </div>
                    <h4 class="cont_inner_title">お引越し先のお住まいについて</h4>
                    <div class="dl_block">
                        <dl>
                            <dt id="pve_form_list_12">新住所<span>必須</span></dt>
                            <dd<?php
        if (!empty($e)
            && ($e->hasErrorForId('top_new_zip') || $e->hasErrorForId('top_new_pref_cd_sel') || $e->hasErrorForId('top_new_address'))
        ) {
            echo ' class="form_error"';
        }
                            ?>>
                                <ul>
                                    <li>
                                        〒
                                        <input type="text" name="new_zip1" maxlength="3" class="w_70" value='<?php echo $pve001Out->new_zip1(); ?>' />
                                        -
                                        <input type="text" name="new_zip2" maxlength="4" class="w_70" value='<?php echo $pve001Out->new_zip2(); ?>' />
                                        <input name="new_adrs_search_btn" type="button" value="住所検索" class="button ml10" />
                                    </li>
                                    <li>
                                        <select name="new_pref_cd_sel">
<?php
        echo Sgmov_View_Pve_Input::_createPulldown($pve001Out->new_pref_cds(), $pve001Out->new_pref_lbls(), $pve001Out->new_pref_cd_sel());
?>
                                        </select>
                                        都道府県
                                    </li>
                                    <li>
                                        <input type="text" name="new_address" maxlength="40" class="w_220" value="<?php echo $pve001Out->new_address() ?>" />
                                        市町村以下
                                    </li>
                                </ul>
                            </dd>
                        </dl>
                        <dl>
                            <dt>エレベーターの有無</dt>
                            <dd>
                                <ul class="three_col clearfix">
                                    <li>
                                        <label class="radio-label" for="new_elevator1">
                                            <input<?php if ($pve001Out->new_elevator_cd_sel() === '1') echo ' checked="checked"'; ?> id="new_elevator1" name="new_elevator_cd_sel" type="radio" value="1" />
                                            あり
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="new_elevator2">
                                            <input<?php if ($pve001Out->new_elevator_cd_sel() === '0') echo ' checked="checked"'; ?> id="new_elevator2" name="new_elevator_cd_sel" type="radio" value="0" />
                                            なし
                                        </label>
                                    </li>
                                </ul>
                            </dd>
                        </dl>
                        <dl>
                            <dt id="pve_form_list_14">新しいお住まいの階</dt>
                            <dd<?php if (!empty($e) && $e->hasErrorForId('top_new_floor')) { echo ' class="form_error"'; } ?>>
                                <input type="text" name="new_floor" value="<?php echo $pve001Out->new_floor(); ?>" size="3" maxlength="2" istyle="4" format="3N" mode="numeric" />
                                階
                            </dd>
                        </dl>
                        <dl>
                            <dt>住居前道幅</dt>
                            <dd>
                                <ul class="three_col clearfix">
                                    <li>
                                        <label class="radio-label" for="new_road1">
                                            <input<?php if ($pve001Out->new_road_cd_sel() === '1') echo ' checked="checked"'; ?> id="new_road1" name="new_road_cd_sel" type="radio" value="1" />
                                            車両通行不可
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="new_road2">
                                            <input<?php if ($pve001Out->new_road_cd_sel() === '2') echo ' checked="checked"'; ?> id="new_road2" name="new_road_cd_sel" type="radio" value="2" />
                                            1台通行可
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="new_road3">
                                            <input<?php if ($pve001Out->new_road_cd_sel() === '3') echo ' checked="checked"'; ?> id="new_road3" name="new_road_cd_sel" type="radio" value="3" />
                                            2台すれ違い可
                                        </label>
                                    </li>
                                </ul>
                            </dd>
                        </dl>
                    </div>

                    <h4 class="cont_inner_title">お客様情報</h4>
                    <div class="dl_block">
                        <dl>
                            <dt id="pve_form_list_16">お名前<span>必須</span></dt>
                            <dd<?php if (!empty($e) && $e->hasErrorForId('top_name')) { echo ' class="form_error"'; } ?>>
                                <input type="text" name="name" maxlength="30" class="w_180" value="<?php echo $pve001Out->name(); ?>" />
                            </dd>
                        </dl>
                        <dl>
                            <dt id="pve_form_list_17">フリガナ<span>必須</span></dt>
                            <dd<?php if (!empty($e) && $e->hasErrorForId('top_furigana')) { echo ' class="form_error"'; } ?>>
                                <input type="text" name="furigana" maxlength="30" class="w_180" value="<?php echo $pve001Out->furigana(); ?>" />
                            </dd>
                        </dl>
                        <dl>
                            <dt id="pve_form_list_18">メールアドレス<span>必須</span></dt>
                            <dd class="width_change <?php if (!empty($e) && $e->hasErrorForId('top_mail')) { echo ' form_error'; } ?>">
                                <input type="text" name="mail" maxlength="40" class="w_220" value="<?php echo $pve001Out->mail(); ?>" />
                            </dd>
                        </dl>
                        <dl>
                            <dt id="pve_form_list_19">電話番号<span>必須</span></dt>
                            <dd<?php if (!empty($e) && ($e->hasErrorForId('top_tel') || $e->hasErrorForId('top_tel_other') || $e->hasErrorForId('top_tel_type'))) { echo ' class="form_error"'; } ?>>
                                <ul>
                                    <li>
                                        <input class="w_70" maxlength="5" name="tel1" type="text" value="<?php echo $pve001Out->tel1(); ?>" />
                                        -
                                        <input class="w_70" maxlength="5" name="tel2" type="text" value="<?php echo $pve001Out->tel2(); ?>" />
                                        -
                                        <input class="w_70" maxlength="4" name="tel3" type="text" value="<?php echo $pve001Out->tel3(); ?>" />
                                    </li>
                                    <li>
                                        <label class="radio-label">
                                            <input type="radio" name="tel_type_cd_sel" value="1" <?php if ($pve001Out->tel_type_cd_sel() === '1') echo ' checked="checked"'; ?>>
                                            ご自宅（携帯）
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label">
                                            <input type="radio" name="tel_type_cd_sel" value="2" <?php if ($pve001Out->tel_type_cd_sel() === '2') echo ' checked="checked"'; ?>>
                                            勤務先
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label">
                                            <input type="radio" name="tel_type_cd_sel" value="3" <?php if ($pve001Out->tel_type_cd_sel() === '3') echo ' checked="checked"'; ?>>
                                            その他
                                        </label>
                                        <input type="text" name="tel_other" size="20" maxlength="20" value="<?php echo $pve001Out->tel_other(); ?>">
                                    </li>
                                </ul>
                                <p>電話連絡可能時間帯</p>
                                <label class="radio-label right50" for="available1">
                                            <input<?php if ($pve001Out->contact_available_cd_sel() === '2') echo ' checked="checked"'; ?> id="available1" name="contact_available_cd_sel" type="radio" value="2" />
                                            終日可
                                        </label><br class="pcH">
                                    
                                        <label for="available2">
                                            <input<?php if ($pve001Out->contact_available_cd_sel() === '1') echo ' checked="checked"'; ?> id="available2" name="contact_available_cd_sel" type="radio" value="1" />
                                            時間指定
                                        </label>
                                        <select name="contact_start_cd_sel">
<?php
        echo Sgmov_View_Pve_Input::_createPulldown($pve001Out->contact_start_cds(), $pve001Out->contact_start_lbls(), $pve001Out->contact_start_cd_sel());
?>
                                        </select>
                                        時 ～
                                        <select name="contact_end_cd_sel">
<?php
        echo Sgmov_View_Pve_Input::_createPulldown($pve001Out->contact_end_cds(), $pve001Out->contact_end_lbls(), $pve001Out->contact_end_cd_sel());
?>
                                        </select>
                                        時
                            </dd>
                        </dl>
                        <dl>
                            <dt id="pve_form_list_20">備考欄</dt>
                            <dd class="width_change <?php if (!empty($e) && $e->hasErrorForId('top_comment')) { echo ' form_error'; } ?>">
                                備考:300文字まで
                                <br />
                                <textarea class="w100p" cols="70" name="comment" rows="9"><?php echo $pve001Out->comment(); ?></textarea>
                            </dd>
                        </dl>
                    </div>
                </div>
                <div class="border_box"><strong>お問い合わせにあたって</strong>
                    <ul>
                        <li>ご連絡までにお時間をいただく場合がございます。ご了承ください。</li>
                        <li>電話番号、メールアドレスが誤っておりますとご連絡ができません。間違いがないかご確認ください。</li>
                    </ul>
                </div>
                <div class="attention_area">

                    <!--▼個人情報の取り扱いここから-->
                    <div id="privacy_policy" class="accordion">
                        <h3 class="accordion_button">個人情報の取り扱い</h3>
                        <div id="privacy_contents" class="ac_content">
                            <p class="sentence">
                                SGムービング株式会社（以下「当社」）は、以下の方針に基づき、個人情報保護の管理・運用を行っております。
                                <br />必ずお読みください。
                                <br />本サイトにおいて個人情報をご提供頂いた場合、当社の個人情報の取り扱いに関しご同意いただいたものといたします。
                            </p>
                            <h4 class="ttl">個人情報の取扱について</h4>
                            <ol>
                                <li>
                                    <h3>個人情報の取扱の基本方針</h3>
                                    <p>ご入力いただいた個人情報は、当社が定める「個人情報保護方針」に従い、適切な保護措置を講じ、厳重に管理いたします。</p>
                                </li>
                                <li>
                                    <h3>当社が保有する個人情報の利用目的</h3>
                                    <p>ご入力いただいた個人情報は、以下の目的のみ利用致します。</p>
                                    <ul>
                                        <li>お客様への見積作成およびご依頼頂いた作業を行うため。</li>
                                        <li>お客様のご依頼に付随する作業およびサービスを行うため。</li>
                                        <li>お客様などへの報告や必要な処理を行うため。</li>
                                        <li>お客様からの各種お問い合わせや資料請求などにご対応するため。</li>
                                    </ul>
                                    <p>上記以外の目的で個人情報を利用する場合は、改めて目的をお知らせし、同意を得るものと致します。</p>
                                </li>
                                <li>
                                    <h3>個人情報の第三者提供について</h3>
                                    <p>ご提供いただいた個人情報は、ご本人のご同意なしに第三者への提供は致しません。但し、法令に基づき、国の機関または地方公共団体等より法的義務を伴う協力要請を受けた場合には、例外的にご本人の同意なく関連機関等に提供する場合がございます。</p>
                                </li>
                                <li>
                                    <h3>個人情報の取扱いの委託について</h3>
                                    <p>ご本人のご同意なしに個人情報の取扱いの全部または一部を委託することがあります。委託にあたっては、十分な個人情報の保護水準を満たしている者を選定し、委託を受けた者に対する必要、かつ適切な監督を行います。</p>
                                </li>
                                <li>
                                    <h3>個人情報提供の任意性</h3>
                                    <p>当社が必要とする個人情報をご提供頂くことは任意です。ただし、個人情報を提供いただけない場合は、当社の各種サービスのご提供が行えなくなるなどの支障がでる恐れがあります。</p>
                                </li>
                                <li>
                                    <h3>当社の個人情報保護管理者</h3>
                                    <p>個人情報保護管理者：管理部　部長</p>
                                </li>
                                <li>
                                    <h3>個人情報に関する苦情、相談、開示等の求め先について</h3>
                                    <p>ご自身の個人情報について、苦情、相談、利用目的の通知、開示、内容の訂正、追加又は削除、利用の停止、消去　及び第三者への提供の停止を請求する権利があり、当社は合理的な範囲で対応致します。これらの権利行使を行う場合は、下記の窓口にて受付を致します。</p>
                                </li>
                            </ol>
                            <p id="contact" class="sentence">

                                ≪個人情報に関するお問合せ窓口≫
                                <br />
                                <span>所在地</span>：東京都江東区新砂3-2-9　Xフロンティア　EAST 6階
                                <br />
                                <span>名称</span>：SGムービング株式会社 &#160; 本社 &#160; 管理部
                                <br />
                                <span>連絡先</span>：03-5857-2457(受付時間:平日9時～18時)
                            </p>
                        </div>
                    </div>
                    <!--▲個人情報の取り扱いここまで-->
                </div>
                <p class="text_center">
                    <div class="text_center comBtn02 btn01 fadeInUp animate">
                        <div class="btnInner">
                            <input id="submit_button" name="submit" type="submit" value="同意して次に進む（入力内容の確認）" />
                        </div>
                    </div>
                    <input name="ticket" type="hidden" value="<?php echo $ticket ?>" />

                    <!-- hidden 出発地域コード -->
                    <input name="formareacd" type="hidden" value="" />
                    <!-- hidden 到着地域コード -->
                    <input name="toareacd" type="hidden" value="" />

                    <input name="personal" type="hidden" value="<?php echo $pve001Out->menu_personal(); ?>" />
                </p>
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
    
<!--    <script charset="UTF-8" type="text/javascript" src="/js/form/input.js"></script>-->
    <script charset="UTF-8" type="text/javascript" src="/pve/js/CourcePlanCheck.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/pve/js/input.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/pve/js/planChange.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/pve/js/radio.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/pve/js/radioChange.js"></script>
    
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_script_foot.php'); ?>
</body>

</html>