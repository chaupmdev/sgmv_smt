<?php
    /**
     * 11_パスワード変更。
     * @package    ssl_html
    * @subpackage event/passChange
    * @author     GiapLN(FPT Software) 
     */
    require_once dirname(__FILE__) . '/../../lib/Lib.php';
    //Sgmov_Lib::useView('pin/Input');
    Sgmov_Lib::useView('event/InputPassChange');
    /**#@-*/

    // 処理を実行
    $view = new Sgmov_View_Event_InputPassChangeo();

    $forms = $view->execute();

    $email = $forms['email'];
    $object = $forms['object'];
    $passChange001Out = $forms['outForm'];

    /**
     * エラーフォーム
     * @var Sgmov_Form_Error
     */
    $e = $forms['errorForm'];
    $sessionData = $forms['sessionData'];
    
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
    <title>パスワード変更画面│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
<?php
    // キャッシュ対策
    $sysdate = new DateTime();
    $strSysdate = $sysdate->format('YmdHi');
?>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <link href="/event/css/event.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />

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
            <h1 class="page_title">パスワード変更画面</h1>
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
            </div>
            <?php
                }
            ?>
            <div class="section other">
                <form action="/event/check_input_passChange.php"  method="post">
                    <div class="section">
                        <div class="dl_block">
                            <dl>
                                <dt>
                                    メールアドレス
                                </dt>
                                <dd>
                                    <input style="width:80%; background-color: #ccc;" disabled="disabled" autocapitalize="off"  name="email" placeholder="" type="email" value="<?php echo $email; ?>" />
                                </dd>
                            </dl>
                            
                            <dl>
                                <dt id="top_password_old">
                                    現パスワード<span>必須</span>
                                </dt>
                                <dd <?php if (isset($e) && $e->hasErrorForId('top_password_old')) { echo ' class="form_error"'; } ?>>
                                    <input class="user-pass" style="width:40%;" autocapitalize="off"  name="password_old" id="password_old" autocomplete="new-password" placeholder="" type="password" value="<?php echo $passChange001Out->password_old(); ?>" />
                                    <i class="bi bi-eye-slash" style="margin-left:-25px;cursor: pointer;" id="togglePasswordOld"></i>
                                </dd>
                            </dl>
                            
                            <dl>
                                <dt id="top_password">
                                    新パスワード<span>必須</span>
                                </dt>
                                <dd style="padding-top: 10px;" <?php if (isset($e) && $e->hasErrorForId('top_password')) { echo ' class="form_error"'; } ?>>
                                    <div style="margin-bottom: 9px;">
                                        <span class="red">
                                            ※パスワードは8文字以上で英大文字、英小文字、数字の全てを含むパスワードを設定して下さい。<br/>
                                        </span>
                                    </div>
                                    <input class="user-pass" style="width:40%;" autocapitalize="off"  name="password" id="password" placeholder="" type="password" value="<?php echo $passChange001Out->password(); ?>" />
                                    <i class="bi bi-eye-slash" style="margin-left:-25px;cursor: pointer;" id="togglePassword"></i>
                                </dd>
                            </dl>
                            
                            <dl>
                                <dt id="top_password_confirm">
                                    パスワードの確認入力<span>必須</span>
                                </dt>
                                <dd <?php if (isset($e) && $e->hasErrorForId('top_password_confirm')) { echo ' class="form_error"'; } ?>>
                                    <div class="user-pass" style="width:40%;float:left;">
                                        <input style="width:100%;" autocapitalize="off"  name="password_confirm" id="password_confirm" placeholder="" type="password" value="<?php echo $passChange001Out->password_confirm(); ?>" />
                                        <i class="bi bi-eye-slash" style="margin-left:-25px;cursor: pointer;" id="togglePasswordConfirm"></i>
                                    </div>
                                    
                                    <div class="event-txt-left" style="float:left;width: 58%; margin-left: 5px;">
                                            ※確認のためもう一度、コピーせず直接入力してください<br/>
                                            （コピー・貼り付けはしないでください。）
                                    </div>
                                    
                                </dd>
                            </dl>
                            
                        </div>
                    </div>

                    <div class="btn_area">
                        <!--  GiapLN fix comment by TuanLK 2022/03/25 -->
                        <input class="event-btn-submit-disable" style="padding: 17px 0px;background-color: #1774bc;" type="submit" name="submit" value="入力内容を登録する"/><br/>
                        <?php if ($object['password_update_flag'] == 0) {?>
                            <a class="event-btn-back-2" onclick='backUserHistory("<?php echo $sessionData['event_name']; ?>");'>戻る</a>
                        <?php } ?>
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

    <!-- Materialize JavaScript -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>-->
    <script>
        const togglePasswordOld = document.querySelector("#togglePasswordOld");
        const passwordOld = document.querySelector("#password_old");

        togglePasswordOld.addEventListener("click", function () {
            // toggle the type attribute
            const type = passwordOld.getAttribute("type") === "password" ? "text" : "password";
            passwordOld.setAttribute("type", type);
            // toggle the icon
            this.classList.toggle("bi-eye");
        });
        
        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#password");

        togglePassword.addEventListener("click", function () {
            // toggle the type attribute
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            // toggle the icon
            this.classList.toggle("bi-eye");
        });
        
        const togglePasswordConfirm = document.querySelector("#togglePasswordConfirm");
        const password_confirm = document.querySelector("#password_confirm");

        togglePasswordConfirm.addEventListener("click", function () {
            // toggle the type attribute
            const type = password_confirm.getAttribute("type") === "password" ? "text" : "password";
            password_confirm.setAttribute("type", type);
            // toggle the icon
            this.classList.toggle("bi-eye");
        });
        
        function backUserHistory(event_nm) {
            location.href="inputHistory?event_nm=" + event_nm ;
        }
    </script>
</body>
</html>