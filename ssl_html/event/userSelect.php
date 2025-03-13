<?php
    /**
     * 01_会員確認。
     * @package    ssl_html
    * @subpackage event/userSelect
    * @author     GiapLN(FPT Software) 
     */
    require_once dirname(__FILE__) . '/../../lib/Lib.php';
    Sgmov_Lib::useView('event/UserSelect');
    /**#@-*/

    $view = new Sgmov_View_Event_User_Select();
    $forms = $view->execute();

    $sessionData = $forms['sessionData'];
    
    $baseUrl = $forms['baseUrl'];
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
    <title>会員確認│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
<?php
    // キャッシュ対策
    $sysdate = new DateTime();
    $strSysdate = $sysdate->format('YmdHi');
?>

    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <link href="/event/css/event.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <script charset="UTF-8" type="text/javascript" src="/event/js/jquery-3.1.1.min.js"></script>
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
            <h1 class="page_title">会員確認</h1>
            
            <div class="section other">
                <!--<form action="/user/check_input.php"  method="post">-->
                    <div class="section">
                        <div class="dl_block">
                            <dl>
                                <dd style="text-align: center; padding-bottom: 40px;">
                                    <div>
                                        <input id="btnLogin" class="event-btn-submit-en" onclick="redirectUserSelect('<?php echo $sessionData['event_name'];?>', 'login');"  type="submit" name="btnLogin" value="既存会員ログインはこちら"/>
                                    </div>
                                    
                                    <div>
                                        <input id="btnRegister" class="event-btn-submit-en" onclick="redirectUserSelect('<?php echo $sessionData['event_name'];?>', 'register');"  type="submit" name="btnRegister" value="新規会員ログインはこちら"/>
                                    </div>
                                    <?php if ($sessionData['security_patten'] != 3) { ?>
                                    <div>
                                        <input id="btnRobotCheck" class="event-btn-submit-en" onclick="redirectUserSelect('<?php echo $sessionData['event_name'];?>', 'robot_check');"  type="submit" name="btnRobotCheck" value="会員登録なしログインはこちら"/>
                                    </div>
                                    <?php } ?>
                                    <?php if ($sessionData['security_patten'] != 3 && $sessionData['security_patten'] != 2) { ?>
                                    <div style="margin-top: 10px;">
                                        <span class="red" style="margin-left: 20px;">
                                            ※会員登録なしの場合は、個数変更とキャンセルができません<br/>
                                        </span>
                                    </div>
                                    <?php } ?>
                                </dd>
                            </dl>
                            
                        </div>
                    </div>
                <!--</form>-->
            </div>
        </div>
    </div>
    <!--main disabled="disabled"-->
    
<?php
    $footerSettings = 'under';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/footer.php';
?>
    <script>
        var baseUrl = "<?php echo $baseUrl; ?>";
        function redirectUserSelect(eventNm, type) {
            let subUrl = "";
            if (type == 'register') {
                subUrl = "/event/register?event_nm=";
            }
            if (type == 'robot_check') {
                subUrl = "/event/robotCheck?event_nm=";
            }
            if (type == 'login') {
                subUrl = "/event/login?event_nm=";
            }
            
            window.location.href = baseUrl + subUrl + eventNm;
        }
    </script>
</body>
</html>