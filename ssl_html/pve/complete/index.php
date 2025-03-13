<?php
/**
 * 訪問見積もり申し込みを登録し、完了画面を表示します。
 * @package    ssl_html
 * @subpackage PVE
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../../lib/Lib.php';
Sgmov_Lib::useView('pve/Complete');
Sgmov_Lib::useForms(array('Error', 'PveSession'));
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Pve_Complete();
$forms = $view->execute();

/**
 * フォーム
 * @var Sgmov_Form_Pve003Out
 */
$pve003Out = $forms['outForm'];
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
    <meta property="og:url" content="https://www.sagawa-mov.co.jp/pve/complete/">
    <meta property="og:site_name" content="<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl-ogp.php'); ?>">
    <meta property="og:image" content="/img/ogp/og_image_sgmv.jpg">
    <meta property="twitter:card" content="summary_large_image">
    <link rel="shortcut icon" href="/img/common/favicon.ico">
    <link rel="apple-touch-icon" sizes="192x192" href="/img/ogp/touch-icon.png">
    <title><?php
    //タイトル
    if ($pve003Out->pre_exist_flag() === '1') {
        $title = 'お引越し申し込みの完了';
    } else {
        $title = '訪問お見積りの完了';
    }
    echo $title;
    ?>｜<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl.php'); ?></title>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_style.php'); ?>
    <link href="/css/form/common.css" rel="stylesheet" type="text/css">
    <link href="/css/form/form.css" rel="stylesheet" type="text/css">
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
                <h1 class="topLead">訪問お見積りの送信完了<em>Complete</em></h1>
                <ul id="pagePath">
                    <li><a href="/"><img class="home" src="/img/common/icon54.jpg" alt=""></a></li>
                    <li><a href="/contact/">お問い合わせ</a></li>
                    <li><a href="/pve/input/">訪問お見積りフォーム</a></li>
                    <li><span>入力内容確認</span></li>
                    <li>送信完了</li>
                </ul>
            </div>
        </div>

        <!-- Start ************************************************ -->
        
        <!-- End ************************************************ -->

        <div class="wrap clearfix">
<!--            <h1 class="page_title"><?php echo $title; ?></h1>-->
            <div class="section">

                <h2 class="complete_msg">
                    お問い合わせ、
                    <br />ありがとうございました。
                </h2>

                <p class="sentence br">
                    担当者より2営業日以内にご連絡いたします。
                    <br />この概算お見積り結果は、申込の成立を意味するものではありません。
                    <br />最終的確認は当社から、お電話の確認後かその後の下見見積によりよるもので成約いただいたものに限ります。
                    また、時期により、お受け出来ない場合もございますので予めご了承ください。
                    <br />お申し込み内容の登録が完了いたしましたら、ご記入いただいたメールアドレス[<?php echo $pve003Out->mail() ?>]宛に自動でメールを送らせていただいています。
                    <br />お申し込みから24時間以内に届かない場合はメールアドレスが間違っているか登録に失敗している可能性がありますので、お手数ですがお電話などでお知らせください。
                </p>

                <p class="sentence btm30">
                    今後ともどうぞよろしくお願いします。
                </p>

                <div class="btn_area">
                    <a class="next" href="/">ホームへ戻る</a>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!--main-->

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
    <script charset="UTF-8" type="text/javascript" src="/js/form/radio.js"></script>
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/common_script_foot.php'); ?>
</body>

</html>