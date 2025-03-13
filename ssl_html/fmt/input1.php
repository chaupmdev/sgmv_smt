<?php
/**
 * 催事・イベント配送受付お申し込み入力画面を表示します。
 * @package    ssl_html
 * @subpackage EVE
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('fmt/Input');

/**#@-*/

// 処理を実行
$view = new Sgmov_View_Fmt_Input();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Eve001Out
 */
$eve001Out = $forms['outForm'];

$dispItemInfo = $forms['dispItemInfo'];

/**
 * エラーフォーム
 * @var Sgmov_Form_Error
 */
$e = $forms['errorForm'];

//error_log(var_export($e->_errors, true));

    // スマートフォン・タブレット判定
    $detect = new MobileDetect();
    $isSmartPhone = $detect->isMobile();
    if ($isSmartPhone) {
        $inputTypeEmail  = 'email';
        $inputTypeNumber = 'number';
    } else {
        $inputTypeEmail  = 'text';
        $inputTypeNumber = 'text';
    }

// 2022-03-22 ToanDD3 implement SMT6-84
// User Type from Session
$userType = isset($_SESSION['FMT_LOGIN']['user_type']) ? $_SESSION['FMT_LOGIN']['user_type'] : -1;

$eventNm = isset($_SESSION['EVENT']['event_name']) ? $_SESSION['EVENT']['event_name'] : '';
$baseUrl = Sgmov_Component_Config::getUrlPublicSsl();

$hasCommiket = $forms['hasCommiket'];
?>

<!DOCTYPE html>
<html dir="ltr" lang="ja-jp" xml:lang="ja-jp">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0" />
    <meta http-equiv="content-style-type" content="text/css" />
    <meta http-equiv="content-script-type" content="text/javascript" />
    <meta name="Keywords" content="" />
    <meta name="Description" content="催事・イベント配送受付サービスのお申し込みのご案内です。" />
    <meta name="author" content="SG MOVING Co.,Ltd" />
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved." />
    <title>催事・イベント配送受付サービスのお申し込み｜お問い合わせ│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
<?php
    // キャッシュ対策
    $sysdate = new DateTime();
    $strSysdate = $sysdate->format('YmdHi');
?>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <link href="/fmt/css/eve.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
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
            <li class="current">催事・イベント配送受付サービスのお申し込み</li>
        </ul>
    </div>
    <div id="main">
        <div class="wrap clearfix">
            <h1 class="page_title">催事・イベント配送受付サービスのお申し込み</h1>
            <p class="sentence" style="margin-bottom: 5px;">
                以下のフォームにもれなくご入力をお願いいたします。
                <br />
                <span class="red" style="font-size: 1.5em;font-weight: bolder;">
                    必ず「sagawa-mov.co.jp」からのメールを受信する設定にしてください。
                </span>
                <span class="red">
                    <br>詳しくは<a href="#bounce_mail">こちら</a>
                </span>
                <br />梱包方法ガイドライン
                <br /><a href="http://www.sagawa-exp.co.jp/send/howto-packing/" target="_blank" style="color: #1774bc; text-decoration: underline;">http://www.sagawa-exp.co.jp/send/howto-packing/</a>
                <br />「商標についてーリンクガイドライン」の一読と確認をお願いいたします。
                <br /><a href="http://www.sagawa-exp.co.jp/help/" target="_blank" style="color: #1774bc; text-decoration: underline;">http://www.sagawa-exp.co.jp/help/</a>
                <br />沖縄など航空便対応エリアからご利用のお客様は、「航空便ご利用上の注意」及び「航空宅配便等個建運送約款」をご確認の上でお申し込みください。
                <br /><a href="/pdf/kokubin_goriyoujyono_tyui.pdf" target="_blank" style="color: #1774bc; text-decoration: underline;">航空便ご利用上の注意はこちら</a>
                <br /><a href="/pdf/kokutakuhaibinnado_unso_yakkan.pdf" target="_blank" style="color: #1774bc; text-decoration: underline;">航空宅配便等個建運送約款はこちら</a>
            </p>
            <div id="timeover" class="message_flame" style="display: none;">
            </div>


<?php
    if (isset($e) && $e->hasError()) {
?>
            <div class="err_msg">
                <p class="sentence br attention">[!ご確認ください]下記の項目が正しく入力・選択されていません。</p>
                <ul>
<?php
        // エラー表示
        foreach($e->_errors as $key => $val) {
            echo "<li><a href='#" . $key . "'>" . $val . '</a></li>';
        }
?>

                </ul>
                <p class="under">
                    インターネットでお申し込みが出来なかった場合は、
                    <br />下記ダイヤルにお問い合わせください。
                    <br />
                    <br />SG ムービング株式会社<!-- 東京営業所 -->
                    <br/>
                    <?php
                            $selectedEventNmae = Sgmov_View_Fmt_Input::_getLabelSelectPulldownData($eve001Out->event_cds(), $eve001Out->event_lbls(), $eve001Out->event_cd_sel());
                            if(!empty($selectedEventNmae)) {
                                $selectedEventNmae .= '係';
                            }
                            echo $selectedEventNmae;
                    ?>
                    <br/>TEL: 03-5534-1080(受付時間:平日10時～17時)<?php if ($eve001Out->eventsub_cd_sel() == '4') : ?>※年内は12月28日まで<?php endif; ?>
                </p>
            </div>

<?php
    }
?>
            <div class="section other">
                <form action="/fmt/check_input" data-feature-id="<?php echo Sgmov_View_Fmt_Common::FEATURE_ID ?>" data-id="<?php echo Sgmov_View_Fmt_Common::GAMEN_ID_EVE001 ?>" method="post">
                    <input name="ticket" type="hidden" value="<?php echo $ticket ?>" />
                    <input name="input_mode" type="hidden" value="<?php echo $eve001Out->input_mode(); ?>" />
                    <input name='input_type_email' type='hidden' value='<?php echo $inputTypeEmail; ?>' />
                    <input name='input_type_number' type='hidden' value='<?php echo $inputTypeNumber; ?>' />

                    <div class="section">
<?php
                        ///////////////////////////////////////////////
                        // 顧客情報入力エリア
                        ///////////////////////////////////////////////
                        include_once dirname(__FILE__) . '/parts/input_cstmr.php';

                        ///////////////////////////////////////////////
                        // 搬入情報入力エリア
                        ///////////////////////////////////////////////
                        include_once dirname(__FILE__) . '/parts/input_outbound.php';

                        ///////////////////////////////////////////////
                        // 搬出情報入力エリア
                        ///////////////////////////////////////////////
                        include_once dirname(__FILE__) . '/parts/input_inbound.php';
?>
                    </div>


                    <!--▼往復便ご利用のお客様の搬出発送ここから-->
<!--                    <div class="gray_block">
                        <strong>往復便ご利用のお客様の搬出発送</strong>
                        <ul class="disc_ul">
                            <li>
                                往復便ご利用のお客様は、下船日当日にターミナルで受付いたします。搬出用伝票のご記入は不要ですので、お客様のお名前をターミナル内SGムービング受付カウンター係員にお申し付けください。
                            </li>
                            <li>
                                お荷物が増えた場合は下船日当日の追加お申し込みも承ります。ターミナル内SGムービング受付カウンターへお越しください。伝票をお渡しいたします。追加分の配送代金は現金でお支払いください。
                            </li>
                        </ul>
                        <strong>片道便(搬出)のお申し込み</strong>
                        <ul class="disc_ul">
                            <li>
                                搬出便のお申し込みも事前にインターネットでお申し込みされると港での面倒な手続きを省略できます(下船日当日のターミナルでの受付はかなりの混雑が予想されます)。復路便のみお申し込みのお客様の伝票は、下船後にターミナルのSGムービング受付カウンターでお渡しいたします。
                            </li>
                            <li>
                                事前のお申し込みがなく搬出便のみのご利用は、下船日当日にターミナルで受付いたします。
                                <br />事前にお申し込みされていないお客様の伝票は受付カウンターにご用意しております。配送代金は受付時に現金でお支払いください。
                            </li>
                        </ul>
                    </div>-->
                    <!--▲往復便ご利用のお客様の搬出発送ここまで-->
                    <!--▼お支払い方法-->

                    <?php include_once dirname(__FILE__) . '/parts/input_payment_method.php'; ?>

                    <!--▲お支払い方法-->
<?php
                        ///////////////////////////////////////////////
                        // アテンションエリア
                        ///////////////////////////////////////////////
                        include_once dirname(__FILE__) . '/parts/input_attention_area.php';
?>
                    <p class="sentence">
                        <strong class="red">※迷惑メールを設定されている方は、必ず「sagawa-mov.co.jp」からのメールを受信する設定にしてください。
                            <br>詳しくは「<a href="#bounce_mail">ご連絡メールが届かない場合</a>」をご確認ください。</strong>
                    </p>

                    <p class="sentence"><span class="sp_only pcH">上記「個人情報の取り扱い」および「特定商取引法に基づく表記」の</span>内容についてご同意頂ける方は、下のボタンを押してください。</p>

                    <p class="text_center">
                        <input id="submit_button" type="submit" name="submit" value="同意して次に進む（入力内容の確認）">
                    </p>
                    
                    <?php if ($hasCommiket) { ?>
                    <p class="text_center">
                        <input  class="btnBackEvent" type="button" name="btnBack" onclick="backRedirectEvent('<?php echo $eventNm; ?>', '<?php echo $userType; ?>', '<?php echo $baseUrl; ?>');" value="戻る">
                    </p>
                    <?php } ?>
                    
                </form>
            </div>
        </div>
    </div>
    <!--main-->

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
    <script charset="UTF-8" type="text/javascript" src="/common/js/multi_send.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/common.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/smooth_scroll.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/ajax_searchaddress.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/jquery.ah-placeholder.js"></script>
<?php
    if (!$isSmartPhone) {
?>
    <script charset="UTF-8" type="text/javascript" src="/common/js/jquery.autoKana.js"></script>
<?php
    }
?>
    <script charset="UTF-8" type="text/javascript" src="/fmt/js/define.js" id="definejs-arg"
    data-G_DEV_INDIVIDUAL="<?php echo Sgmov_View_Fmt_Common::COMIKET_DEV_INDIVIDUA; ?>"
    data-G_DEV_BUSINESS="<?php echo Sgmov_View_Fmt_Common::COMIKET_DEV_BUSINESS; ?>"
    >
    </script>
    <script charset="UTF-8" type="text/javascript" src="/fmt/js/input.js?<?php echo $strSysdate; ?>"></script>
</body>
</html>

