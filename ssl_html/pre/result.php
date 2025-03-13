<?php
/**
 * 概算お見積もり確認画面を表示します。
 * @package    ssl_html
 * @subpackage PVE
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('pre/Result', 'pre/Common');
/**#@-*/


// 処理を実行
$view = new Sgmov_View_Pre_Result();
$forms = $view->execute();

/**
 * 出力内容フォーム
 *
 * @var Sgmov_Form_pre002Out
 */
$output = $forms['outForm'];

$isMoveDateError = false;
if (@!empty($forms['errInfo']['isMoveDateError'])) {
    $isMoveDateError = true;
}
/**
 * 出力内容の取得（共通出力項目）
 *
 */
// タイプコード
$type_cd = $output->type_cd();
// 【入力】全選択ボタン押下フラグ
$all_sentakbtn_click_flag = $output->all_sentakbtn_click_flag();
// 【入力】コースコード
$course_cd_sel = $output->course_cd_sel();
if ($course_cd_sel == "") {
    $course_cd_sel = 0;
}
// 【入力】プランコード
$plan_cd_sel = $output->plan_cd_sel();
// 【入力】エアコン取り付け有無
$aircon_exist_flag_sel = $output->aircon_exist_flag_sel();
// 【入力】出発エリアコード
$from_area_cd_sel = $output->from_area_cd_sel();
// 【入力】到着エリアコード
$to_area_cd_sel = $output->to_area_cd_sel();
// 【入力】お引越し予定日付年
$move_date_year_cd_sel = $output->move_date_year_cd_sel();
// 【入力】お引越し予定日付月
$move_date_month_cd_sel = $output->move_date_month_cd_sel();
// 【入力】お引越し予定日付日
$move_date_day_cd_sel = $output->move_date_day_cd_sel();
// キャンペーンリスト・タイトル
$camp_titles = $output->campaign_names();
// キャンペーンリスト・説明
$campaign_contents = $output->campaign_contents();
// キャンペーンリスト・開始日
$campaign_starts = $output->campaign_starts();
// キャンペーンリスト・終了日
$campaign_ends = $output->campaign_ends();
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
    <meta name="Description" content="引越・設置のＳＧムービング株式会社のWEBサイトです。個人の方はもちろん、法人様への実績も豊富です。無料お見積りはＳＧムービング株式会社(フリーダイヤル:0570-056-006(受付時間:平日9時～17時))まで。" />
    <meta name="author" content="SG MOVING Co.,Ltd" />
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved." />
    <title>概算お見積りの結果｜お問い合わせ│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css" rel="stylesheet" type="text/css" />
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
            <li><a href="/pve/input/">お問い合わせ</a></li>
            <li class="current">概算お見積りの結果</li>
        </ul>
    </div>
    
    <div id="main">
        <div class="wrap clearfix">
            <h1 class="page_title">概算お見積りの結果</h1>
            
            <?php if ($isMoveDateError) : ?>
                <div class="err_msg">
                    <p class="sentence br attention">[!ご確認ください]下記の項目が正しく入力・選択されていません。</p>
                    <ul>
                        <li style="color:red;">
                            <?= @$forms['errInfo']['errorMessage'] ?>
                        </li>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="section">
                <h2 class="section_title">概算お見積り条件</h2>
                <div class="dl_block">
                    <dl>
                        <dt>お引越しコース</dt>
                        <dd><?php echo $output->course(); ?></dd>
                    </dl>
                    <dl>
                        <dt>お引越しプラン</dt>
                        <dd><?php echo $output->plan(); ?></dd>
                    </dl>
                    <dl>
                        <dt>お引越し先</dt>
                        <dd>
                            <?php echo $output->from_area() . PHP_EOL; ?>
                            から
                            <?php echo $output->to_area() . PHP_EOL; ?>
                        </dd>
                    </dl>
                    <dl>
                        <dt>お引越し予定日</dt>
                        <dd class="text_link <?php if ($isMoveDateError) : ?>form_error<?php endif; ?>">
                            <?php echo $output->move_date() . PHP_EOL; ?>
                            <br class="pcH" />
                            <a class="left10" href="#other_day">お得な日程がないかカレンダーでチェック！</a>
                        </dd>
                    </dl>
                    <dl>
                        <dt>エアコンの取り付け、取り外し</dt>
                        <dd><?php echo $output->aircon_exist(); ?></dd>
                    </dl>
                </div>
            </div>
            <div class="section" id="result">
                <h2 class="section_title">概算お見積り結果</h2>
                <div class="dl_block btm30">
                    <dl>
                        <dt>概算料金</dt>
                        <dd>&yen;<?php echo number_format($output->base_price()); ?>～</dd>
                    </dl>
                    <dl class="discount">
                        <dt>WEB割引</dt>
                        <dd>-&nbsp;&yen;<?php echo number_format(Sgmov_View_Pre_Result::_getWebWaribiki()); ?></dd>
                    </dl>
                    <dl class="discount">
                        <dt>その他割引</dt>
                        <dd>
<?php
    $retParam = Sgmov_View_Pre_Result::_createMitsumoriCampInfoHtml($output->base_price(), $output->discount_campaign_infos());
    echo $retParam[0];
?>

                        </dd>
                    </dl>
                    <dl class="sum">
                        <dt>合計</dt>
                        <dd>&yen;<?php echo number_format($retParam[1]); ?>～</dd>
                    </dl>
                </div>

<?php
    if ($output->raw_oc_content) {
?>
                <!--キャンペーン内容-->
                <ul class="campaignContent">
                    <li style="color:red; font-size:114%; font-weight:bold;">
                        <?php echo $output->oc_content(); ?>

                    </li>
                </ul>
                <!--キャンペーン内容-->
<?php
    }
?>

                <ul>
                    <li>◇提携サイトにて割引がある場合、<span>WEB割引は適用されません。</span>お見積り段階で<span>別途割引</span>させていただきます。</li>
                    <li>
                        ◇表示されたお見積り金額につきましては、<span>概算料金</span>となります。「立地条件」「間取り」「お荷物の量」等で必要となる車両・人員・梱包資材等の変動により、<span>料金が異なる場合</span>がございます。
                        <br />※お見積り金額につきましては、ご訪問させていただいた担当係員までお尋ねください。
                    </li>
                    <li>◇エアコンの取り付け・取り外しにつきましては、上記概算料金に含まれておりません。</li>
                    <li>◇その他・オプションサービスにつきましては、別途料金が発生いたします。</li>
                    <li>
                        ◇<span>お申込みをご希望</span>のお客様は、下記ボタンより<span>入力フォームにお進み下さい。</span>
                        <br />※お引越しの成約につきましては、入力フォームにご入力後、当社担当者にてご訪問等によるお見積り完了後の成立となります。
                    </li>
                </ul>
<?php
    if ($output->aircon_exist_flag_sel() == 1) {
?>
                <p class="border_box">
                    ※これとは別に、エアコンの取り付け・取り外し代が必要となります。
                    <br />台数や作業内容によって金額が変化しますので、別途お問い合わせください。
                </p>
<?php
    }
?>
            </div>

            <form action="/pre/topve/" method="post">
                <div class="btn_area">
                    <a class="back" href="/pre/input/">違う条件で再見積り</a>
                    <?php if ($isMoveDateError) : ?>
                        <input id="submit_button" style="background-color: gray;" name="submit" type="button" onclick="return false;" value="この内容でお引越を依頼する" />
                    <?php else :?>
                        <input id="submit_button" name="submit" type="submit" value="この内容でお引越を依頼する" />
                    <?php endif; ?>
                </div>
            </form>

            <div class="section" id="other_day">
                <h2 class="section_title">その他の日程でのお引越し料金</h2>
                
                <?php if ($isMoveDateError) : ?>
                    <div class="err_msg">
                        <p class="sentence br attention">[!ご確認ください]下記の項目が正しく入力・選択されていません。</p>
                        <ul>
                            <li style="color:red;">
                                <?= @$forms['errInfo']['errorMessage'] ?>
                            </li>
                        </ul>
                    </div>
                    <br>
                <?php endif; ?>
                
                <h3 class="large_mst">お得な日程がないかカレンダーでチェック！</h3>
                <p class="sentence">
                    選択された日程以外にも、料金がお安くなるお得な引越日がございます。
                    <br />下記のカレンダーを参考に、一度ご検討してみてはいかがでしょうか？
                    <br />※料金をクリックすると、自動的に再計算されます。
                </p>
<?php
// カレンダー表示処理
// カレンダー年
$calyear = $output->cal_year();
// カレンダー月
$calmonth = $output->cal_month();
// 選択日付年
$sntkYear = $output->move_date_year_cd_sel();
// 選択日付月
$sntkMonth = $output->move_date_month_cd_sel();
// 選択日付日
$sntkDay = $output->move_date_day_cd_sel();
// カレンダー日付リスト
$caldays = $output->cal_days();
// カレンダー祝日フラグリスト
$calholidayflags = $output->cal_holiday_flags();
// カレンダーキャンペーンフラグリスト
$calcampaignflags = $output->cal_campaign_flags();
// カレンダー料金リスト
$calprices = $output->cal_prices();

// 既存URLのresult以降削除
$calpricelinks = '/pre/result/' . Sgmov_View_Pre_Common::FUNC_CALLINK_DAY . '/' . $calyear . '/' . $calmonth;

// 前月リンクアドレス
$prevmonthlink = $output->prev_month_link();
if (!empty($prevmonthlink)) {
    $prevmonthlink = '<a class="back" href="' . $prevmonthlink . '">前の月へ</a>';
}
// 次月リンクアドレス
$nextmonthlink = $output->next_month_link();
if (!empty($nextmonthlink)) {
    $nextmonthlink = '<a class="next" href="' . $nextmonthlink . '">次の月へ</a>';
}

// 前週リンクアドレス
$prevweeklink = $output->prev_week_link();
if (!empty($prevweeklink)) {
    $prevweeklink = '<a class="back" href="' . $prevweeklink . '">前の週へ</a>';
}
// 次週リンクアドレス
$nextweeklink = $output->next_week_link();
if (!empty($nextweeklink)) {
    $nextweeklink = '<a class="next" href="' . $nextweeklink . '">次の週へ</a>';
}
// スマホ版週表示開始日
$startweekday = $output->start_week_day();
?>

                <table class="spH spTabH" id="calendar_table">
                    <tbody>
                        <tr>
                            <th colspan="7" id="calendar_ttl" scope="col">
                                <?php echo $prevmonthlink . PHP_EOL; ?>
                                <span><?php echo $calyear; ?></span>年
                                <span><?php echo $calmonth; ?></span>月
                                <?php echo $nextmonthlink . PHP_EOL; ?>
                            </th>
                        </tr>
                        <tr id="week">
                            <th>月</th>
                            <th>火</th>
                            <th>水</th>
                            <th>木</th>
                            <th>金</th>
                            <th class="sat">土</th>
                            <th class="sun">日</th>
                        </tr>
<?php
    echo Sgmov_View_Pre_Result::_createCalendarHtml($calyear, $calmonth, $sntkYear, $sntkMonth, $sntkDay, $caldays, $calholidayflags, $calcampaignflags, $calprices, $calpricelinks, $prevmonthlink, $nextmonthlink);
?>
                    </tbody>
                </table>

                <table class="pcH" id="sp_calendar_table">
                    <tbody>
<?php
    echo Sgmov_View_Pre_Result::_createSPCalendarHtml($calyear, $calmonth, $sntkYear, $sntkMonth, $sntkDay, $caldays, $calholidayflags, $calcampaignflags, $calprices, $calpricelinks, $prevmonthlink, $nextmonthlink, $prevweeklink, $nextweeklink, $startweekday);
?>
                    </tbody>
                </table>

                <div class="caption">
                    <span class="yoteibi"></span>お引越し予定日
                    <br class="pcH" /><span class="campaign"></span>キャンペーン実施中
                </div>

                <form action="" method="post">
                    <input name="param" type="hidden" value="" />
                </form>

<?php
    // キャンペーン情報が存在する場合、出力する
    echo Sgmov_View_Pre_Result::_createCampInfoHtml($camp_titles, $campaign_contents, $campaign_starts, $campaign_ends, $output->course(), $output->plan(), $output->from_area(), $output->to_area());
?>

            </div>
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
    <script charset="UTF-8" type="text/javascript" src="/common/js/multi_send.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/common.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/pre/js/radioChange.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/pre/js/allcourcechange.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/pre/js/result.js"></script>
</body>
</html>