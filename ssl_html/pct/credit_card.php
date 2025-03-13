<?php
/**
 * 手荷物受付サービスのお申し込みのクレジットカード入力画面を表示します。
 * @package    ssl_html
 * @subpackage PCR
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
//メンテナンス期間チェック
require_once dirname(__FILE__) . '/../../lib/component/maintain_pct.php';
// 現在日時
$nowDate = new DateTime('now');
if ($main_stDate_pct <= $nowDate && $nowDate <= $main_edDate_pct) {
    header("Location: /maintenance.php");
    exit;
}
/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('pct/CreditCard');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Pct_CreditCard();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Pcr002Out
 */
$pcr002Out = $forms['outForm'];

/**
 * エラーフォーム
 * @var Sgmov_Form_Error
 */
$e = $forms['errorForm'];

    // スマートフォン・タブレット判定
    $detect = new MobileDetect();
    $isSmartPhone = $detect->isMobile();
    if ($isSmartPhone) {
        $inputTypeNumber = 'number';
    } else {
        $inputTypeNumber = 'text';
    }
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
    <title>旅客手荷物受付サービスのお申し込み│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css" rel="stylesheet" type="text/css" />
    <!--[if lt IE 9]>
    <link href="/css/form_ie8.css" rel="stylesheet" type="text/css" />
    <![endif]-->
    <script src="/js/ga.js" type="text/javascript"></script>
</head>
<body>
<?php
    $gnavSettings = 'contact';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/header.php';
?>
    <div id="breadcrumb">
        <ul class="wrap">
            <li><a href="/">ホーム</a></li>
            <li><a href="/contact/">お問い合わせ</a></li>
            <li class="current">旅客手荷物受付サービスのお申し込み</li>
        </ul>
    </div>
    <div id="main">
        <div class="wrap clearfix">
            <h1 class="page_title">旅客手荷物受付サービスのお申し込み</h1>
            <form action="/pct/check_credit_card" method="post">
                <input name="ticket" type="hidden" value="<?php echo $ticket ?>" />
                <div class="section">
                    <p class="sentence br">
                        確定送料をご確認の上、クレジットカード情報をご入力ください。
                        <br />※審査の結果、決済不可の場合、または申込(決済)後にキャンセル・金額変更があった場合等は、必ず下記問い合わせ番号にお電話をお願いいたします。
                        <!--<br />4月6日より下記SGムービングクルーズ専用ダイヤルにて、お申し込みを受付致します。-->
                        <br />インターネットお申込みについてのお問合せは、
                        <br />SGムービング株式会社TOKYO BASE　　TEL：03-6850-7828　（受付時間平日9時～17時）
                        <br />メールでのお問合せ・ご質問については、<a data-inquiry-type="11" href="/pin/input" target = "_brank">こちら</a>からお願いします。
                        <!--<br /><br /><span>電話番号：</span><strong>03-6850-7828</strong>(受付時間:平日10時～17時)-->
                    </p>
                    <p
                    <dl class="plain_dl">
                        <dd>
                            <!--電話番号：</span><strong>03-6850-7828</strong>(受付時間:平日10時～17時)-->
                            <!--<br />-->
                            <!--<strong>03-5763-9188</strong>(携帯電話から)-->
                            <!--<br /> (受付時間:平日10時～17時)-->
                        </dd>
                    </dl>

<?php if (isset($e) && $e->hasError()) { ?>
                    <div class="err_msg">
                        <p class="sentence br attention">[!ご確認ください]下記の項目が正しく入力・選択されていません。</p>
                        <ul>
<?php
        // エラー表示
        if ($e->hasErrorForId('top_card_expire_month_cd_sel')) {
            echo '<li><a href="#card_expire_month">有効期限 月' . $e->getMessage('top_card_expire_month_cd_sel') . '</a></li>';
        }
        if ($e->hasErrorForId('top_card_expire_year_cd_sel')) {
            echo '<li><a href="#card_expire_year">有効期限 年' . $e->getMessage('top_card_expire_year_cd_sel') . '</a></li>';
        }
        if ($e->hasErrorForId('top_card_expire')) {
            echo '<li><a href="#card_expire_month">カードの有効期限' . $e->getMessage('top_card_expire') . '</a></li>';
        }
        if ($e->hasErrorForId('top_card_number')) {
            echo '<li><a href="#card_number">クレジットカード番号' . $e->getMessage('top_card_number') . '</a></li>';
        }
        if ($e->hasErrorForId('top_security_cd')) {
            echo '<li><a href="#security_cd">セキュリティコード' . $e->getMessage('top_security_cd') . '</a></li>';
        }
?>

                        </ul>
                        <p class="under">
                            インターネットお申込みについてのお問合せは、
                            <br />SGムービング株式会社TOKYO BASE　　TEL：03-6850-7828　（受付時間平日9時～17時）
                            <br />メールでのお問合せ・ご質問については、<a data-inquiry-type="11" href="/pin/input" target = "_brank">こちら</a>からお願いします。
                        </p>
                    </div>
<?php } ?>

                    <div class="result02">
                        <table id="kingaku" class="result_tbl">
                            <tbody>
                                <tr>
                                    <td nowrap>確定送料:</td>
                                    <td nowrap class="right money">
                                        <span><?php echo $pcr002Out->delivery_charge(); ?></span>円（税込）
                                    </td>
                                </tr>
                            </tbody>
                        </table>
<?php
    if (intval($pcr002Out->repeater_discount()) > 0) {
?>
                        <p class="text_center">※リピータ割引（<?php echo number_format($pcr002Out->repeater_discount()); ?>円）が適用されました。</p>
<?php
    }
?>
                    </div>

                    <h4 class="table_title">カード情報</h4>

                    <div class="dl_block">
                        <dl class="form_list clearfix" id="card_list">
                            <dt>有効期限</dt>
                            <dd<?php if (isset($e) && ($e->hasErrorForId('top_card_expire_year_cd_sel') || $e->hasErrorForId('top_card_expire_month_cd_sel') || $e->hasErrorForId('top_card_expire'))) { echo ' class="form_error"'; } ?>>
                                <label for="card_expire_month">
                                    <select id="card_expire_month" name="card_expire_month_cd_sel">
                                        <option value="" selected="selected">月を選択</option>
<?php
        echo Sgmov_View_Pct_CreditCard::_createPulldown($pcr002Out->card_expire_month_cds(), $pcr002Out->card_expire_month_lbls(), $pcr002Out->card_expire_month_cd_sel());
?>
                                </select>
                                月
                            </label>
                            <label for="card_expire_year">
                                <select id="card_expire_year" name="card_expire_year_cd_sel">
                                    <option value="" selected="selected">年を選択</option>
<?php
        echo Sgmov_View_Pct_CreditCard::_createPulldown($pcr002Out->card_expire_year_cds(), $pcr002Out->card_expire_year_lbls(), $pcr002Out->card_expire_year_cd_sel());
?>
                                    </select>
                                    年
                                </label>
                                <span class="f80">カードに記載されている順のまま入力してください</span>
                            </dd>
                        </dl>
                        <dl>
                            <dt class="condition">
                                カード番号
                                <p class="f12">※半角数字・ハイフンなし</p>
                            </dt>
                            <dd<?php if (isset($e) && $e->hasErrorForId('top_card_number')) { echo ' class="form_error"'; } ?>>
                                <input autocapitalize="off" class="w_280" id="card_number" inputmode="numeric" maxlength="16" name="card_number" data-pattern="^\d+$" placeholder="例）9999999999999999" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $pcr002Out->card_number(); ?>" />
                            </dd>
                        </dl>
                        <dl>
                            <dt class="condition">
                                セキュリティコード
                                <p class="f12">※半角数字</p>
                            </dt>
                            <dd<?php if (isset($e) && $e->hasErrorForId('top_security_cd')) { echo ' class="form_error"'; } ?>>
                                <input autocapitalize="off" class="w_60" id="security_cd" inputmode="numeric" maxlength="4" name="security_cd" data-pattern="^\d+$" placeholder="例）9999" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $pcr002Out->security_cd(); ?>" />
                                <span class="f80 instruction_security_cd">
                                    セキュリティコードはカード裏面のサインパネルに表示されている数字末尾3桁です
                                    <br />（アメリカンエクスプレスカードのみ4桁）
                                </span>
                            </dd>
                        </dl>
                        <dl>
                            <dt>お支払い方法</dt>
                            <dd>
                                1回
                            </dd>
                        </dl>

                    </div>

                    <p class="text_center">
                        <input id="submit_button" name="confirm_btn" type="button" value="内容を確認する" />
                    </p>
                </div>
            </form>
        </div>
    </div>

<?php
    $footerSettings = 'under';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/footer.php';
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
    <script charset="UTF-8" type="text/javascript" src="/common/js/jquery.ah-placeholder.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/pct/js/credit_card.js"></script>
</body>
</html>