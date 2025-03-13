<?php 
/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('mst/Edit');

// 処理を実行
$view = new Sgmov_View_Mst_Edit();
$forms = $view->execute();

$event = $forms["event"];

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
    <title>催事・イベント手荷物預かりサービスのカーゴ車管理｜お問い合わせ│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
<?php
    // キャッシュ対策
    $sysdate = new DateTime();
    $strSysdate = $sysdate->format('YmdHi');
?>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <script src="/js/ga.js" type="text/javascript"></script>
    <style type="text/css">
        #submit_button{
            background-image: none !important;
        }
    </style>
</head>
<body>
    <?php
        $gnavSettings = 'contact';
        include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/header.php';
    ?>
    <div id="breadcrumb">
        <ul class="wrap">
            <li><a href="/">ホーム</a></li>
            <li class="current">催事・イベント手荷物預かりサービスのカーゴ車管理</li>
        </ul>
    </div>
    <div id="main">
        <div class="wrap clearfix">
        <h1 class="page_title"  style="margin-bottom:15px !important;">催事・イベント手荷物預かりサービスのカーゴ車管理</h1>
            <div class="section other">
                <form action="/dsn/check_input" data-feature-id="" data-id="" method="post">
                    <div class="dl_block input-inbound comiket_block">
                       <dl>
                            <dt id="event_sel" style=" border-top: solid 1px #ccc !important;">
                                出展イベント
                            </dt>
                            <dd>
                                <?php echo $event["name"]; ?>
                            </dd>
                        </dl>
                        <dl>
                            <dt id="eventsub_max_basket_count">
                                最大カゴ車数<span>必須</span>
                            </dt>
                            <dd <?php
                                if (isset($e)
                                   && ($e->hasErrorForId('eventsub_max_basket_count'))
                                ) {
                                    echo ' class="form_error"';
                                }
                            ?>>
                            <input  type="text" class="number-p-only" name="eventsub_max_basket_count" maxlength="5" data-pattern="^[0-9]+$" placeholder="例）100" value="" />
                        </dd>
                    </dl>
                </div>
                </form>
                <p class="text_center">
                    <input id="submit_button" type="button" name="submit" class = "LeButton" value="更新">
                </p>
            </div>
        </div>
    </div><!--main-->

<?php
    $footerSettings = 'under';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/footer.php';
?>

    <script charset="UTF-8" type="text/javascript" src="/js/jquery-2.2.0.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/lodash.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/multi_send.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/common.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/smooth_scroll.js"></script>
</body>
</html>