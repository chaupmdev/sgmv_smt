<?php

    /**
     * 04_会員登録画面。
     * @package    ssl_html
     * @subpackage event/register
    * @author     GiapLN(FPT Software) 
     */

    /**#@+
     * include files
     */
    require_once dirname(__FILE__) . '/../../lib/Lib.php';
    Sgmov_Lib::useView('event/InputRegister');
    /**#@-*/

    $view = new Sgmov_View_Event_InputRegister();

    $forms = $view->execute();

    $register001Out = $forms['outForm'];

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
    <title>会員登録│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
<?php
    // キャッシュ対策
    $sysdate = new DateTime();
    $strSysdate = $sysdate->format('YmdHi');
?>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <!-- <link href="/login/common/css/login.css?<?php //echo $strSysdate; ?>" rel="stylesheet" type="text/css" /> -->
    <link href="/event/css/event.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <script charset="UTF-8" type="text/javascript" src="/event/js/jquery-3.1.1.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  
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
            <h1 class="page_title">会員登録</h1>
            <?php
                if (isset($e) && $e->hasError()) { //$errorFlg && $error->hasError()
            ?>
            <div class="err_msg">
                <p class="sentence br attention">[!ご確認ください]下記の項目が正しく入力・選択されていません。</p>
                <ul>
                    <?php
                    // top_email
                    if ($e->hasErrorForId('top_email')) {
                        echo '<li><a href="#top_email">' . $e->getMessage('top_email') . '</a></li>';
                    }
                    
                    // top_email_confirm
                    if ($e->hasErrorForId('top_email_confirm')) {
                        echo '<li><a href="#top_email_confirm">' . $e->getMessage('top_email_confirm') . '</a></li>';
                    }
                    ?>
                </ul>
            </div>
            <?php
                }
            ?>
            <div class="section other">
                <form action="/event/check_input_register.php"  method="post">
                    <div class="section">
                        <div class="dl_block">
                            <dl>
                                <dt id="top_email">
                                    メールアドレス<span>必須</span>
                                </dt>
                                <dd <?php if (isset($e) && $e->hasErrorForId('top_email')) { echo ' class="form_error"'; } ?>>
                                    <div style="margin-bottom: 9px;">
                                        <span class="red">
                                            ※登録完了したら、このメールアドレスに仮パスワードを送信する<br/>
                                        </span>
                                    </div>
                                
                                    <input style="width:80%;" autocapitalize="off" autocomplete="off" maxlength="100" name="email" placeholder="" type="text" value="<?php echo $register001Out->email(); ?>" />
                                </dd>
                            </dl>
                            
                            <dl>
                                <dt id="top_email_confirm">
                                    メールアドレス確認<span>必須</span>
                                </dt>
                                <dd <?php if (isset($e) && $e->hasErrorForId('top_email_confirm')) { echo ' class="form_error"'; } ?>>
                                    <input style="width:80%;" autocapitalize="off" autocomplete="off" maxlength="100" name="email_confirm" placeholder="" type="text" value="<?php echo $register001Out->email_confirm(); ?>" />
                                </dd>
                            </dl>
                            
                            <dl>
                                <dd style="width:25%;"></dd>
                                <dd>
                                    <div class="g-recaptcha" data-callback="imNotARobot" data-sitekey="<?php echo $siteKey; ?>"></div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                    
                    <div class="btn_area">
                        <!--  GiapLN fix comment by TuanLK 2022/03/25 -->
                        <input class="event-btn-submit-disable" disabled="disabled"  type="submit" name="btnRegister" value="登録する"/><br/>
                        <a class="event-btn-submit btnBackRobot" style="background: url(../images/common/img_allow_10.png) no-repeat 7% center;background-color: #fc826a;margin-top: 15px;padding: 3px 0px;cursor: pointer; max-width: 400px; width: 90%;" onclick='backUserSelect("<?php echo $sessionData['event_name'];?>");'>戻る</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php
    $footerSettings = 'under';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/footer.php';
?>
    <script>
        var imNotARobot = function() {
            $('input[name="btnRegister"]').css('background-color','#1774bc');
            $('input[name="btnRegister"]').css('cursor','pointer');
            $('input[name="btnRegister"]').prop('disabled', false);
        };
        function backUserSelect(event_nm) {
            location.href="userSelect?event_nm=" + event_nm ;
        }
    </script>
</body>
</html>