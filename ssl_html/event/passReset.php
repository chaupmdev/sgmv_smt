<?php

    /**
     * 07_会員情報忘れ 。
     * @package    ssl_html
    * @subpackage event/passReset
    * @author     GiapLN(FPT Software) 
     */

    /**#@+
     * include files
     */
    require_once dirname(__FILE__) . '/../../lib/Lib.php';
    Sgmov_Lib::useView('event/InputResetPass');
    /**#@-*/

    $view = new Sgmov_View_Input_Reset_Pass();

    $forms = $view->execute();

    $reset001Out = $forms['outForm'];

    /**
     * エラーフォーム
     * @var Sgmov_Form_Error
     */
    $e = $forms['errorForm'];
    $sessionData = $forms['sessionData'];
    $siteKey = $forms['siteKey'];
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
    <title>パスワードの再設定│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
<?php
    // キャッシュ対策
    $sysdate = new DateTime();
    $strSysdate = $sysdate->format('YmdHi');
?>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <link href="/event/css/jquery-confirm.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <link href="/event/css/bundled.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <link href="/event/css/event.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <script charset="UTF-8" type="text/javascript" src="/event/js/jquery-3.1.1.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/event/js/jquery-confirm.js?<?php echo $strSysdate; ?>"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        .btn-default { 
            background-color: #ddd;
        }
    </style>
</head>
<body>
<?php
    $gnavSettings = 'contact';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/header.php';
?>
    <div id="breadcrumb">
        
    </div>
    <div id="main">
        <div class="wrap clearfix">
            <h1 class="page_title">パスワードの再設定</h1>
            <?php
                if (isset($e) && $e->hasError()) { 
            ?>
            <div class="err_msg">
                <p class="sentence br attention">[!ご確認ください]下記の項目が正しく入力・選択されていません。</p>
                <ul>
                    <?php
                    // メールアドレス
                    if ($e->hasErrorForId('top_email')) {
                        echo '<li><a href="#top_email">' . $e->getMessage('top_email') . '</a></li>';
                    }
                    ?>
                </ul>
            </div>
            <?php
                }
            ?>
            <div class="section other">
                <form action="/event/check_input_resetPass.php"  method="post">
                    <div class="section">
                        <div class="dl_block" style="margin-bottom: 0px;">
                            <dl style="margin-bottom: 0px;">
                                <dt id="top_email">
                                    メールアドレス<span>必須</span>
                                </dt>
                                <dd <?php if (isset($e) && $e->hasErrorForId('top_email')) { echo ' class="form_error"'; } ?>>
                                    <div style="margin-bottom: 9px;">
                                        <span class="red">
                                            ※会員登録のメールアドレス<br/>
                                        </span>
                                    </div>
                                    <input style="width:80%;" autocapitalize="off" autocomplete="off" maxlength="100" name="email" placeholder="" type="email" value="<?php echo $reset001Out->email(); ?>" />
                                </dd>
                            </dl>
                            
                            <dl style="margin-bottom: 0px;">
                                <dd style="width:25%;"></dd>
                                <dd>
                                    <div class="g-recaptcha" data-callback="imNotARobot" data-sitekey="<?php echo $siteKey; ?>"></div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                    
                    <p class="text_link_user">
                        <a href="login?event_nm=<?php echo $sessionData['event_name'];?>">ログイン画面へ</a>
                    </p>
                    <p class="text_center">
                        <input class="event-btn-submit" disabled="disabled" style="background-image: none;margin-top: 0px;" type="submit" name="btnResetPass" value="パスワード再設定のメールを送信する"/>
                    </p>
                </form>
            </div>
        </div>
    </div>
    <!--main-->
    
    
<?php
    $footerSettings = 'under';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/footer.php';
    
?>
    <script>
        
        var imNotARobot = function() {
            $('input[name="btnResetPass"]').css('background-color','#1774bc');
            $('input[name="btnResetPass"]').css('cursor','pointer');
            $('input[name="btnResetPass"]').prop('disabled', false);
        };
        <?php 
        
        if (isset($forms['isResetPass']) && $forms['isResetPass'] == true) {
        ?>
            var is_reset_pass = 1;
        <?php 
        } else {
        ?>
            var is_reset_pass = 0;
        <?php 
        }
        ?>
       
        if (is_reset_pass) {
            var event_nm = '<?php echo $sessionData['event_name'];?>';
            
            $.alert({
                title: '通知',
                content: 'メールアドレスに仮パスワードを送信しました。',
                confirm: function(){
                    location.href="login?event_nm=" + event_nm ;
                }
            });
        }
    </script>
</body>
</html>