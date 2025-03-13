<?php
/**
     * 10_アカウントロック。
     * @package    ssl_html
    * @subpackage event/accountLock
    * @author     GiapLN(FPT Software) 
     */
    require_once dirname(__FILE__) . '/../../lib/Lib.php';
    Sgmov_Lib::useView('event/AccountLock');
    
    $view = new Sgmov_View_Event_AccountLock();
    $forms = $view->execute();
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
    <title>アカウントロック│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
<?php
    // キャッシュ対策
    $sysdate = new DateTime();
    $strSysdate = $sysdate->format('YmdHi');
?>

    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <link href="/event/css/event.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <script charset="UTF-8" type="text/javascript" src="/user/common/js/jquery-3.1.1.min.js"></script>
    
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
            <h1 class="page_title">アカウントロック</h1>
           
            <div class="section other">
                <form action="/event/check_input_resetPass.php"  method="post">
                    <div class="section">
                        <div class="dl_block">
                            <dl>
                                <dd>
                                    <div style="margin-bottom: 9px;text-align: center;font-size: 14pt;">
                                        <span class="red">
                                            アカウントがロックされています。
                                        </span>
                                    </div> 
                                    <div style="text-align: center;font-size: 14pt;">
                                        <span class="red">
                                            ５分後に再度アクセスして下さい。
                                        </span>
                                    </div>
                                    
                                    <div class="btn_area">
                                        <a class="event-btn-back" onclick='backUserSelect("<?php echo $sessionData['event_name'];?>");'>戻る</a>
                                    </div>
                                </dd>
                            </dl>
                        </div>
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
        
        function backUserSelect(event_nm) {
            location.href="login?event_nm=" + event_nm ;
        }
    </script>
</body>
</html>