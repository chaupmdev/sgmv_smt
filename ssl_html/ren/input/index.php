<?php
/**
 * 催事・イベント配送受付お申し込み入力画面を表示します。
 * @package    ssl_html
 * @subpackage EVE
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
// Basic認証
//require_once dirname(__FILE__) . '/../../../lib/component/auth.php';
/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../../lib/Lib.php';
Sgmov_Lib::useView('ren/Input');

// /**#@-*/

// // 処理を実行
$view = new Sgmov_View_Ren_Input();
$forms = $view->executeInner();

// /**
//  * チケット
//  * @var string
//  */
$ticket = $forms['ticket'];

// /**
//  * フォーム
//  * @var Sgmov_Form_Eve001Out
//  */
$ren001Out = $forms['outForm'];
        
$dispItemInfo = $forms['dispItemInfo'];
// *
//  * エラーフォーム
//  * @var Sgmov_Form_Error
 
$e = $forms['errorForm'];

// //error_log(var_export($e->_errors, true));

//     // スマートフォン・タブレット判定
//     $detect = new MobileDetect();
//     $isSmartPhone = $detect->isMobile();
//     if ($isSmartPhone) {
//         $inputTypeEmail  = 'email';
//         $inputTypeNumber = 'number';
//     } else {
//         $inputTypeEmail  = 'text';
//         $inputTypeNumber = 'text';
//     }
?>
<!DOCTYPE html>
<html dir="ltr" lang="ja-jp" xml:lang="ja-jp">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0">
    <meta http-equiv="content-style-type" content="text/css">
    <meta http-equiv="content-script-type" content="text/javascript">
    <meta name="Description" content="エントリーページです。">
    <meta name="author" content="SG MOVING Co.,Ltd">
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved.">
    <meta property="og:locale" content="ja_JP">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.sagawa-mov.co.jp/ren/input/">
    <meta property="og:site_name" content="<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl-ogp.php'); ?>">
    <meta property="og:image" content="/img/ogp/og_image_sgmv.jpg">
    <meta property="twitter:card" content="summary_large_image">
    <link rel="shortcut icon" href="/img/common/favicon.ico">
    <link rel="apple-touch-icon" sizes="192x192" href="/img/ogp/touch-icon.png">
    <title>新卒採用エントリーフォーム｜お問い合わせ｜<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/include/meta-ttl.php'); ?></title>
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
    <?php
    // キャッシュ対策
        $sysdate = new DateTime();
        $strSysdate = $sysdate->format('YmdHi');
    ?>
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
                <h1 class="topLead">新卒採用エントリーフォーム<em>Entry</em></h1>
                <ul id="pagePath">
                    <li><a href="/"><img class="home" src="/img/common/icon54.jpg" alt=""></a></li>
                    <li><a href="/careers/">採用情報</a></li>
                    <li>新卒採用エントリーフォーム</li>
                </ul>
            </div>
        </div>
        <!-- Start ************************************************ -->

        <!-- End ************************************************ -->
        <div class="wrap clearfix">
                <p class="sentence" style="margin-bottom: 5px;">
                    以下のフォームにご入力をお願いいたします。
                </p>

            <?php if (isset($e) && $e->hasError()) {?>
                <div class="err_msg">
                    <p class="sentence br attention">[!ご確認ください]下記の項目が正しく入力・選択されていません。</p>
                    <ul>
                    <?php
                        // エラー表示
                        foreach($e->_errors as $key => $val) {
                            echo "<li><a href='#" . $key . "'>" . $val . '</a></li>';
                        }?>
                    </ul>
                </div>
            <?php } ?>

            <div class="section other">
                <form action="/ren/check_input" data-feature-id="<?php echo Sgmov_View_Ren_Common::FEATURE_ID ?>" data-id="<?php echo Sgmov_View_Ren_Common::GAMEN_ID_REN001 ?>"  method="post">
                    <input name="ticket" type="hidden" value="<?php echo $ticket; ?>" />
                    <input name="input_mode" type="hidden" value="" />
                    <input name='input_type_email' type='hidden' value='' />
                    <input name='input_type_number' type='hidden' value='' />

                    <div class="section">
<?php
                        // ///////////////////////////////////////////////
                        // // 顧客情報入力エリア
                        // ///////////////////////////////////////////////
                         include_once dirname(__FILE__) . '/../parts/input_cstmr.php';
?>
                    </div>
                    <?php
                        ///////////////////////////////////////////////
                        // アテンションエリア
                        ///////////////////////////////////////////////
                        include_once dirname(__FILE__) . '/../parts/input_attention_area.php';
?>
                    
                    <div class="text_center comBtn02 btn01 fadeInUp animate">
                        <div class="btnInner">
                            <input id="submit_button" name="submit" type="submit" value="同意して次に進む（入力内容の確認）" />
                        </div>
                    </div>
                </form>
            </div>
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
    <script charset="UTF-8" type="text/javascript" src="/js/smooth_scroll.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/jquery.autoKana.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/ren/js/input.js?<?php echo $strSysdate; ?>"></script>
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