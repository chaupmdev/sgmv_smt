<?php
/**
 * 概算お見積もり入力画面を表示します。
 * @package    ssl_html
 * @subpackage PRE
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('pre/Input');
/**#@-*/

// お問い合わせ入力画面に遷移
// 現在計算されている金額と新料金の金額が大きくずれているためとり急ぎお問い合わせページに飛ばす
Sgmov_Component_Redirect::redirectPublicSsl('/pin/input/9');


// 処理を実行
$view = new Sgmov_View_Pre_Input();
$forms = $view->execute();

/**
 * フォーム
 * @var Sgmov_Form_pre001Out
 */
$pre001Out = $forms['outForm'];

/**
 * エラー
 * @var Sgmov_Form_Error
 */
$errorFlg = false;
if (isset($forms['errorForm'])) {
    $errorFlg = true;
    $error = $forms['errorForm'];
}

/**
 * 出力内容の取得
 *
 */
// 【入力】全選択ボタン押下フラグ
$all_sentakbtn_click_flag = $pre001Out->all_sentakbtn_click_flag();
// タイプコード
$type_cd = $pre001Out->type_cd();

// 選択コースコード
$course_cd_sel = $pre001Out->course_cd_sel();
if ($course_cd_sel == "") {
    $course_cd_sel = 0;
}

// 選択プランコード
$plan_cd_sel = $pre001Out->plan_cd_sel();
if ($plan_cd_sel == "") {
    $plan_cd_sel = 0;
}

// 初期選択時選択コースコード
//print_r($pre001Out);
$init_course_cd_sel = $pre001Out->init_course_cd_sel();
if ($init_course_cd_sel == "") {
    $init_course_cd_sel = 0;
}
// 初期選択時選択プランコード
$init_plan_cd_sel = $pre001Out->init_plan_cd_sel();
if ($init_plan_cd_sel == "") {
    $init_plan_cd_sel = 0;
}

// コース表示フラグリスト
$initCorce = $pre001Out->course_view_flag();
// コース全表示ボタン表示フラグ
$AllCorceBtn = $pre001Out->course_allbtn_flag();
// プラン非活性フラグリスト
$initPln = $pre001Out->plan_view_flag();
// エアコン取り付け有無
$aircon_exist_flag_sel = $pre001Out->aircon_exist_flag_sel();
// 出発エリアコード選択値
$from_area_cd_sel = $pre001Out->from_area_cd_sel();
// 到着エリアコード選択値
$to_area_cd_sel = $pre001Out->to_area_cd_sel();

// コース選択値チェック保持
$corceSel = Sgmov_View_Pre_Input::_getPulldownSelect(8, $course_cd_sel);

// プラン選択値チェック保持
$planSel = Sgmov_View_Pre_Input::_getPulldownSelect(5, $plan_cd_sel);

// エアコン取り付け有無チェック保持
$airconSel = array(
    0 => null,
    1 => null,
);
if ($aircon_exist_flag_sel === '1') {
    $airconSel[0] = Sgmov_View_Pre_Common::CHECKED;
} else if ($aircon_exist_flag_sel === '0') {
    $airconSel[1] = Sgmov_View_Pre_Common::CHECKED;;
}

// 出発地域ラベル
$from_area_cds = $pre001Out->from_area_cds();

// 出発地域ラベル
$from_area_lbls = $pre001Out->from_area_lbls();

// 到着地域ラベル
$to_area_cds = $pre001Out->to_area_cds();

// 到着地域ラベル
$to_area_lbls = $pre001Out->to_area_lbls();

// 引越し予定日（年）コード
$move_date_year_cds = $pre001Out->move_date_year_cds();

// 引越し予定日（年）ラベル
$move_date_year_lbls = $pre001Out->move_date_year_lbls();

// 引越し予定日（月）コード
$move_date_month_cds = $pre001Out->move_date_month_cds();

// 引越し予定日（月）ラベル
$move_date_month_lbls = $pre001Out->move_date_month_lbls();

// 引越し予定日（日）コード
$move_date_day_cds = $pre001Out->move_date_day_cds();

// 引越し予定日（日）ラベル
$move_date_day_lbls = $pre001Out->move_date_day_lbls();

// 引越し予定日（年）
$move_date_year_cd_sel = $pre001Out->move_date_year_cd_sel();

// 引越し予定日（月）
$move_date_month_cd_sel = $pre001Out->move_date_month_cd_sel();

// 引越し予定日（日）
$move_date_day_cd_sel = $pre001Out->move_date_day_cd_sel();

// キャンペーンリスト・タイトル
$camp_titles = $pre001Out->campaign_names();

// キャンペーンリスト・説明
$campaign_contents = $pre001Out->campaign_contents();

// キャンペーンリスト・開始日
$campaign_starts = $pre001Out->campaign_starts();

// キャンペーンリスト・終了日
$campaign_ends = $pre001Out->campaign_ends();
?>
<!DOCTYPE html>
<html dir="ltr" lang="ja-jp" xml:lang="ja-jp">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0" />
    <meta http-equiv="content-style-type" content="text/css" />
    <meta http-equiv="content-script-type" content="text/javascript" />
    <meta name="Keywords" content="佐川急便,ＳＧムービング,sgmoving,無料見積り,お引越し,設置,法人,家具輸送,家電輸送,精密機器輸送,設置配送" />
    <meta name="Description" content="概算お見積り（個人用）のご案内です。［コースとプランとお引越し先を選択するだけの、簡単・無料お見積り］ご訪問いただきまして、ありがとうございます。お引越条件を選択いただくと、概算料金が計算できます。 " />
    <meta name="author" content="SG MOVING Co.,Ltd" />
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved." />
    <title>概算お見積り（個人用）｜お問い合わせ│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/plan.css" rel="stylesheet" type="text/css" />
    <link href="/css/pre.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css" rel="stylesheet" type="text/css" />
    <link href="/css/course.css" rel="stylesheet" type="text/css" />
    <!--[if lt IE 9]>
    <link href="/css/hide_plan.css" rel="stylesheet" type="text/css" />
    <link href="/css/form_ie8.css" rel="stylesheet" type="text/css" />
    <![endif]-->
    <script src="/js/ga.js" type="text/javascript"></script>
</head>
<body>
<?php
    $gnavSettings = 'contact';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/parts/header.php';
?>
    <div id="breadcrumb">
        <ul class="wrap">
            <li><a href="/">ホーム</a></li>
            <li><a href="/contact/">お問い合わせ</a></li>
            <li class="current">概算お見積りフォーム</li>
        </ul>
    </div>

    <div id="main">

        <div class="wrap clearfix">
            <h1 class="page_title">概算お見積りフォーム</h1>

            <p class="sentence">
                ご訪問いただきまして、ありがとうございます。お引越条件を選択いただくと、概算料金が計算できます。 
                <br /><span style="color:red;">※ご注意</span>
                <br />引越プランにつきまして下記の日程が予約で一杯となっており、お見積り・お申込みが出来ませんのでご注意ください。
                <br />また、期間外に関しましても受付できないエリア、日程もございます。あらかじめご容赦ください。
                <br />・家族引越 3月15日より4月8日まで
                <br />・単身引越(カーゴプラン含む) 3月21日より3月31日まで
                <br />・単品プラン・生活応援プラン 3月21日より3月31日まで
            </p>

<?php
    if ($errorFlg && $error->hasError()) {
?>
            <div class="err_msg">
                <p class="sentence br attention">[!ご確認ください]下記の項目が正しく入力・選択されていません。</p>
                <ul>
<?php
        if ($error->hasErrorForId('top_course_cd_sel')) {
            echo '<li><a href="#pre_form_title_01">ご希望のお引越しコース' . $error->getMessage('top_course_cd_sel') . '</a></li>';
        }
        if ($error->hasErrorForId('top_plan_cd_sel')) {
            echo '<li><a href="#pre_form_title_02">ご希望のお引越しプラン' . $error->getMessage('top_plan_cd_sel') . '</a></li>';
        }
        if ($error->hasErrorForId('top_from_area_cd_sel')) {
            echo '<li><a href="#from_area">現在お住まいの地域' . $error->getMessage('top_from_area_cd_sel') . '</a></li>';
        }
        if ($error->hasErrorForId('top_to_area_cd_sel')) {
            echo '<li><a href="#to_area">お引越し先の地域' . $error->getMessage('top_to_area_cd_sel') . '</a></li>';
        }
        if ($error->hasErrorForId('top_cource_plan_from_to')) {
            echo '<li><a href="#to_area">' . $error->getMessage('top_cource_plan_from_to') . '</a></li>';
        }
        if ($error->hasErrorForId('top_move_date')) {
            echo '<li><a href="#move_date">ご希望のお引越し予定日' . $error->getMessage('top_move_date') . '</a></li>';
        }
        if ($error->hasErrorForId('top_move_date_all')) {
            echo '<li><a href="#move_date">ご希望のお引越し予定日を選択してください。</a></li>';
        }
?>
                </ul>
            </div>
<?php
    }
?>

            <form action="/pre/check_input" method="post">
                <div class="section" id="pre_form_title_01">
                    <h3 class="column_title">1.コースのご選択</h3>
                    <dl class="plan_column spTabH">
                        <dt>コース</dt>
                        <dd<?php if ($errorFlg && $error->hasErrorForId('top_course_cd_sel')){ echo ' class="form_error"'; } ?>>
                            <ul class="form_radio clearfix">
                                <li>
                                    <label class="radio-label" for="i1">
                                        <input <?php echo $corceSel[0]; ?> id="i1" name="course_cd_sel" type="radio" value="1" />
                                        カーゴコース
                                    </label>
                                </li>
                                <li>
                                    <label class="radio-label" for="i2">
                                        <input <?php echo $corceSel[1]; ?> id="i2" name="course_cd_sel" type="radio" value="2" />
                                        少量コース
                                    </label>
                                </li>
                                <li>
                                    <label class="radio-label" for="i3">
                                        <input <?php echo $corceSel[2]; ?> id="i3" name="course_cd_sel" type="radio" value="3" />
                                        1部屋コース
                                    </label>
                                </li>
                                <li>
                                    <label class="radio-label" for="i4">
                                        <input <?php echo $corceSel[3]; ?> id="i4" name="course_cd_sel" type="radio" value="4" />
                                        2部屋コース
                                    </label>
                                </li>
                                <li>
                                    <label class="radio-label" for="i5">
                                        <input <?php echo $corceSel[4]; ?> id="i5" name="course_cd_sel" type="radio" value="5" />
                                        3部屋コース
                                    </label>
                                </li>
                                <li>
                                    <label class="radio-label" for="i6">
                                        <input <?php echo $corceSel[5]; ?> id="i6" name="course_cd_sel" type="radio" value="6" />
                                        4部屋コース
                                    </label>
                                </li>
                                <li>
                                    <label class="radio-label" for="i7">
                                        <input <?php echo $corceSel[6]; ?> id="i7" name="course_cd_sel" type="radio" value="7" />
                                        5部屋コース
                                    </label>
                                </li>
                                <li>
                                    <label class="radio-label" for="i8">
                                        <input <?php echo $corceSel[7]; ?> id="i8" name="course_cd_sel" type="radio" value="8" />
                                        6部屋コース
                                    </label>
                                </li>
                            </ul>
                            <img alt="" id="courseImg" style="display:none;" />
                            <p class="sentence text_caution" id="courseMsg" style="display:none;"></p>
                        </dd>
                    </dl>

                    <dl class="plan_column pcH">
                        <dd<?php if ($errorFlg && $error->hasErrorForId('top_course_cd_sel')){ echo ' class="form_error"'; } ?>>
                            <div id="course">
                                <ul class="form_radio clearfix">
                                    <li>
                                        <label class="radio-label" for="i1s">
                                            <input <?php echo $corceSel[0]; ?> id="i1s" name="course_cd_sel2" type="radio" value="1" />
                                            カーゴコース
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="i2s">
                                            <input <?php echo $corceSel[1]; ?> id="i2s" name="course_cd_sel2" type="radio" value="2" />
                                            少量コース
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="i3s">
                                            <input <?php echo $corceSel[2]; ?> id="i3s" name="course_cd_sel2" type="radio" value="3" />
                                            1部屋コース
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="i4s">
                                            <input <?php echo $corceSel[3]; ?> id="i4s" name="course_cd_sel2" type="radio" value="4" />
                                            2部屋コース
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="i5s">
                                            <input <?php echo $corceSel[4]; ?> id="i5s" name="course_cd_sel2" type="radio" value="5" />
                                            3部屋コース
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="i6s">
                                            <input <?php echo $corceSel[5]; ?> id="i6s" name="course_cd_sel2" type="radio" value="6" />
                                            4部屋コース
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="i7s">
                                            <input <?php echo $corceSel[6]; ?> id="i7s" name="course_cd_sel2" type="radio" value="7" />
                                            5部屋コース
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="i8s">
                                            <input <?php echo $corceSel[7]; ?> id="i8s" name="course_cd_sel2" type="radio" value="8" />
                                            6部屋コース
                                        </label>
                                    </li>
                                </ul>
                            </div>
                            <p class="text_center">およその間取り</p>
                            <p class="sentence text_caution" id="courseMsg2" style="display:none;">単身カーゴプラン、単身AIR CARGO プラン専用のコースです。</p>
                        </dd>
                    </dl>

                </div>

                <div class="section" id="pre_form_title_02">
                    <h3 class="column_title">2.プランのご選択</h3>
                    <div class="plan_table">
                        <div class="pc_table spTabH">
                            <table<?php if ($errorFlg && $error->hasErrorForId('top_plan_cd_sel')){ echo ' class="form_error"'; } ?>>
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
                                        <input <?php echo $planSel[0]; ?> <?php echo $initPln['CARGO']; ?> id="p1" name="plan_cd_sel" type="radio" value="1" />
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
                                        <input <?php echo $planSel[1]; ?> <?php echo $initPln['AIRCARGO']; ?> id="p2" name="plan_cd_sel" type="radio" value="2" />
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
                                        <input <?php echo $planSel[3]; ?> <?php echo $initPln['OMAKASE']; ?> id="p4" name="plan_cd_sel" type="radio" value="4" />
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
                                        <input <?php echo $planSel[2]; ?> <?php echo $initPln['STANDARD']; ?> id="p3" name="plan_cd_sel" type="radio" value="3">
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
                                        <input <?php echo $planSel[4]; ?> <?php echo $initPln['CHARTAR']; ?> id="p5" name="plan_cd_sel" type="radio" value="5" />
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

                            <div class="select_plan<?php if ($errorFlg && $error->hasErrorForId('top_plan_cd_sel')){ echo ' form_error'; } ?>">
                                <div class="plan_name">
                                    <label class="radio-label" for="p1s">
                                        <input <?php echo $planSel[0]; ?> <?php echo $initPln['CARGO']; ?> id="p1s" name="plan_cd_sel2" type="radio" value="1" />
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

                            <div class="select_plan<?php if ($errorFlg && $error->hasErrorForId('top_plan_cd_sel')){ echo ' form_error'; } ?>">
                                <div class="plan_name">
                                    <label class="radio-label" for="p2s">
                                        <input <?php echo $planSel[1]; ?> <?php echo $initPln['AIRCARGO']; ?> id="p2s" name="plan_cd_sel2" type="radio" value="2" />
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

                            <div class="select_plan<?php if ($errorFlg && $error->hasErrorForId('top_plan_cd_sel')){ echo ' form_error'; } ?>">
                                <div class="plan_name">
                                    <label class="radio-label" for="p4s">
                                        <input <?php echo $planSel[3]; ?> <?php echo $initPln['OMAKASE']; ?> id="p4s" name="plan_cd_sel2" type="radio" value="4" />
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

                            <div class="select_plan<?php if ($errorFlg && $error->hasErrorForId('top_plan_cd_sel')){ echo ' form_error'; } ?>">
                                <div class="plan_name">
                                    <label class="radio-label" for="p3s">
                                        <input <?php echo $planSel[2]; ?> <?php echo $initPln['STANDARD']; ?> id="p3s" name="plan_cd_sel2" type="radio" value="3" />
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

                            <div class="select_plan<?php if ($errorFlg && $error->hasErrorForId('top_plan_cd_sel')){ echo ' form_error'; } ?>">
                                <div class="plan_name">
                                    <label class="radio-label" for="p5s">
                                        <input <?php echo $planSel[4]; ?> <?php echo $initPln['CHARTAR']; ?> id="p5s" name="plan_cd_sel2" type="radio" value="5" />
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
                    <h3 class="column_title">3.その他の条件</h3>
                    <div class="dl_block">
                        <dl>
                            <dt id="from_area">現在お住まいの地域</dt>
                            <dd<?php if($errorFlg) { if (($error->hasErrorForId('top_from_area_cd_sel')) || ($error->hasErrorForId('top_cource_plan_from_to'))) { echo ' class="form_error"'; } } ?>>
                                <select id="fromarea1" name="from_area_cd_sel">
<?php
echo Sgmov_View_Pre_Input::_createAreaPulldown($from_area_cds, $from_area_lbls, $from_area_cd_sel, Sgmov_View_Pre_Common::AREA_HYOJITYPE_NORMAL);
?>
                                </select>
                                <select id="fromarea2" name="from_area_cd_sel" style="display: none">
<?php
        echo Sgmov_View_Pre_Input::_createAreaPulldown($from_area_cds, $from_area_lbls, $from_area_cd_sel, Sgmov_View_Pre_Common::AREA_HYOJITYPE_OKINAWANASHI);
?>
                                </select>
                                <select id="fromarea3" name="from_area_cd_sel" style="display: none">
<?php
        echo Sgmov_View_Pre_Input::_createAreaPulldown($from_area_cds, $from_area_lbls, $from_area_cd_sel, Sgmov_View_Pre_Common::AREA_HYOJITYPE_AIRCARGO_TO);
?>
                                </select>
                                <p id="planMsg"></p>
                            </dd>
                        </dl>
                        <dl>
                            <dt id="to_area">お引越し先の地域</dt>
                            <dd<?php if($errorFlg) { if (($error->hasErrorForId('top_to_area_cd_sel')) || ($error->hasErrorForId('top_cource_plan_from_to'))) { echo ' class="form_error"'; } } ?>>
                                <select id="toarea1" name="to_area_cd_sel">
<?php
        echo Sgmov_View_Pre_Input::_createAreaPulldown($to_area_cds, $to_area_lbls, $to_area_cd_sel, Sgmov_View_Pre_Common::AREA_HYOJITYPE_NORMAL);
?>
                                </select>
                                <select id="toarea2" name="to_area_cd_sel" style="display: none">
<?php
        echo Sgmov_View_Pre_Input::_createAreaPulldown($to_area_cds, $to_area_lbls, $to_area_cd_sel, Sgmov_View_Pre_Common::AREA_HYOJITYPE_OKINAWANASHI);
?>
                                </select>
                                <select id="toarea3" name="to_area_cd_sel" style="display: none">
<?php
        echo Sgmov_View_Pre_Input::_createAreaPulldown($to_area_cds, $to_area_lbls, $to_area_cd_sel, Sgmov_View_Pre_Common::AREA_HYOJITYPE_AIRCARGO);
?>
                                </select>
                                <select id="toarea4" name="to_area_cd_sel" style="display: none">
<?php
        echo Sgmov_View_Pre_Input::_createAreaPulldown($to_area_cds, $to_area_lbls, $to_area_cd_sel, Sgmov_View_Pre_Common::AREA_HYOJITYPE_AIRCARGO_TO_FUK);
?>
                                </select>
                                <select id="toarea5" name="to_area_cd_sel" style="display: none">
<?php
        echo Sgmov_View_Pre_Input::_createAreaPulldown($to_area_cds, $to_area_lbls, $to_area_cd_sel, Sgmov_View_Pre_Common::AREA_HYOJITYPE_AIRCARGO_TO_TOK);
?>
                                </select>
                                <select id="toarea6" name="to_area_cd_sel" style="display: none">
<?php
        echo Sgmov_View_Pre_Input::_createAreaPulldown($to_area_cds, $to_area_lbls, $to_area_cd_sel, Sgmov_View_Pre_Common::AREA_HYOJITYPE_AIRCARGO_TO_HOK);
?>
                                </select>
                            </dd>
                        </dl>
                        <dl>
                            <dt id="move_date">お引越し予定日</dt>
                            <dd<?php if($errorFlg) { if (($error->hasErrorForId('top_move_date')) || ($error->hasErrorForId('top_move_date_all'))) { echo ' class="form_error"'; } } ?>>

                                <select class="date" name="move_date_year_cd_sel">
<?php
echo Sgmov_View_Pre_Input::_createPulldown($move_date_year_cds, $move_date_year_lbls, $move_date_year_cd_sel);
?>
                                        </select>
                                        年

                                        <select class="date" name="move_date_month_cd_sel">
<?php
echo Sgmov_View_Pre_Input::_createPulldown($move_date_month_cds, $move_date_month_lbls, $move_date_month_cd_sel);
?>
                                        </select>
                                        月

                                        <select class="date" name="move_date_day_cd_sel">
<?php
echo Sgmov_View_Pre_Input::_createPulldown($move_date_day_cds, $move_date_day_lbls, $move_date_day_cd_sel);
?>
                                        </select>
                                        日

                                <p class="sentence">※1週間後～2ヶ月先までの日付</p>
                            </dd>
                        </dl>
                        <dl>
                            <dt>エアコンの取り付け、取り外し</dt>
                            <dd>
                                <ul class="clearfix three_col">
                                    <li>
                                        <label class="radio-label" for="a1">
                                            <input <?php echo $airconSel[0] ?> id="a1" name="aircon_exist_flag_sel" type="radio" value="1" />
                                            あり
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-label" for="a2">
                                            <input <?php echo $airconSel[1] ?> id="a2" name="aircon_exist_flag_sel" type="radio" value="0" />
                                            なし
                                        </label>
                                    </li>
                                </ul>
                            </dd>
                        </dl>
                    </div>
                    <!--plan_table-->

                    <p class="text_center">
                        <input id="submit_button" name="confirm_btn" type="button" value="概算見積もりを計算する" />
                    </p>
                </div>
                <!-- 他社連携キャンペーンID-->
                <?php if($pre001Out->oc_id()){ ?>
                    <input name="oc_id" type="hidden" value="<?php echo $pre001Out->oc_id(); ?>">
                <?php } ?>
                <!-- 他社連携キャンペーン名称-->
                <?php if($pre001Out->oc_name()){ ?>
                    <input name="oc_name" type="hidden" value="<?php echo $pre001Out->oc_name(); ?>">
                <?php } ?>
                <!-- 他社連携キャンペーン内容-->
                <?php if($pre001Out->raw_oc_content){ ?>
                    <input name="oc_content" type="hidden" value="<?php echo $pre001Out->oc_content(); ?>">
                <?php } ?>

                <!-- 初期表示時タイプコード -->
                <input name="type_cd" type="hidden" value="<?php echo $type_cd; ?>">
                <!-- 初期表示時コースコード -->
                <input name="init_cource_cd" type="hidden" value="<?php echo $init_course_cd_sel; ?>">
                <!-- 初期表示時プランコード -->
                <input name="init_plan_cd" type="hidden" value="<?php echo $init_plan_cd_sel; ?>">
                <!-- 全選択ボタン押下フラグ -->
                <input name="all_sentakbtn_click_flag" id="allbtnClickFlg" type="hidden" value="<?php echo $all_sentakbtn_click_flag; ?>">
                <!-- 出発地域コード -->
                <input type="hidden" name="formareacd" />
                <!-- 到着地域コード -->
                <input type="hidden" name="toareacd" />
                <!-- 個人向けサービス ページ 選択されたメニュー -->
                <input type="hidden" name="personal" value="<?php echo $pre001Out->personal(); ?>" />
            </form>
        </div>
    </div>
    <!--main-->

<?php
    $footerSettings = 'under';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/parts/footer.php';
?>

    <!--[if lt IE 9]>
    <script charset="UTF-8" type="text/javascript" src="/js/jquery-1.12.0.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/lodash-compat.min.js"></script>
    <![endif]-->
    <!--[if gte IE 9]><!-->
    <script charset="UTF-8" type="text/javascript" src="/js/jquery-2.2.0.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/lodash.min.js"></script>
    <!--<![endif]-->
    <script charset="UTF-8" type="text/javascript" src="/js/common.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/smooth_scroll.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/multi_send.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/pre/js/radio.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/pre/js/radioChange.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/pre/js/planChange.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/pre/js/CourcePlanCheck.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/pre/js/allcourcechange.js"></script>
</body>
</html>