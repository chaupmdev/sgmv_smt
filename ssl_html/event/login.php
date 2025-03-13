<?php
	
    /**
     * 06_ログイン画面。
     * @package    ssl_html
    * @subpackage event/login
    * @author     GiapLN(FPT Software) 
     */

    /**#@+
     * include files
     */
    require_once dirname(__FILE__) . '/../../lib/Lib.php';
    Sgmov_Lib::useView('event/InputLogin');
    /**#@-*/

    // 処理を実行
    $view = new Sgmov_View_Input_Login();

    $forms = $view->execute();

    $login001Out = $forms['outForm'];
    /**
     * エラーフォーム
     * @var Sgmov_Form_Error
     */
    $e = $forms['errorForm'];

    $sessionData = $forms['sessionData'];
    
    $siteKey = $forms['siteKey'];
    if (empty($e->_errors)) {
        $login001Out->raw_email = '';
        $login001Out->raw_password = '';
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
    <meta name="Keywords" content="" />
    <meta name="Description" content="催事・イベント配送受付サービスのお申し込みのご案内です。" />
    <meta name="author" content="SG MOVING Co.,Ltd" />
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved." />
    <title>会員ログイン│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
<?php
    // キャッシュ対策
    $sysdate = new DateTime();
    $strSysdate = $sysdate->format('YmdHi');
?>

    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
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
            <h1 class="page_title" style="margin-bottom: 15px;">会員ログイン</h1>
            <?php
                if (isset($e) && $e->hasError()) { //$errorFlg && $error->hasError()
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
            </div>
            <?php
                }
            ?>
            <div class="section other" style="font-size:12pt;margin-top: 0px;">
                <p>サービスをご利用になるにはログインしてください。</p>
                <form action="/event/check_input_login.php"  method="post">
                    <div class="section" style="margin-top:10px;">
                        <div class="dl_block" style="margin-bottom: 0px;">
                            <dl>
                                <dt id="top_email">
                                    メールアドレス
                                </dt>
                                <dd <?php if (isset($e) && $e->hasErrorForId('top_email')) { echo ' class="form_error"'; } ?>>
                                    <input style="width:80%;" autocapitalize="off" autocomplete="off" name="email" maxlength="100" placeholder="" type="email" value="<?php echo $login001Out->email(); ?>" />
                                </dd>
                            </dl>
                            
                            <dl>
                                <dt id="top_password">
                                    パスワード
                                </dt>
                                <dd <?php if (isset($e) && $e->hasErrorForId('top_password')) { echo ' class="form_error"'; } ?>>
                                    <input style="width:80%;" maxlength="50" autocapitalize="off" autocomplete="off" name="password" id="password" placeholder="" type="password" value="" />
                                    <i class="bi bi-eye-slash" style="margin-left:-25px;cursor: pointer;" id="togglePassword"></i>
                                </dd>
                            </dl>
                            
                            <dl>
                                <dd style="width:25%;"></dd>
                                <dd>
                                    <div class="g-recaptcha" data-callback="imNotARobot"  data-sitekey="<?php echo $siteKey; ?>"></div>
                                </dd>
                            </dl>
                            
                        </div>
                    </div>
                    
                    <p class="text_link_user">
                        <a href="passReset?event_nm=<?php echo $sessionData['event_name'];?>">パスワードを忘れた場合</a>
                    </p>
                    
                    <div class="btn_area">
                        <!-- / GiapLN fix comment by TuanLK 2022/03/25 -->
                        <input class="event-btn-submit-disable" disabled="disabled" type="submit" name="btnLogin" value="ログイン"/><br/>
                        <a style="background: url(../images/common/img_allow_10.png) no-repeat 7% center;background-color: #fc826a;margin-top: 15px;cursor: pointer; padding: 3px 0px; max-width: 400px; width: 90%;font-weight: bold;" onclick='backUserSelect("<?php echo $sessionData['event_name'];?>");'>戻る</a>
                    </div>
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
        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#password");

        togglePassword.addEventListener("click", function () {
            // toggle the type attribute
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            // toggle the icon
            this.classList.toggle("bi-eye");
        });
        
        var imNotARobot = function() {

            
            $('input[name="btnLogin"]').css('background-color','#1774bc');
            $('input[name="btnLogin"]').css('cursor','pointer');
            $('input[name="btnLogin"]').prop('disabled', false);
        };
        
        function backUserSelect(event_nm) {
            location.href="userSelect?event_nm=" + event_nm ;
        }
    </script>
</body>
</html>