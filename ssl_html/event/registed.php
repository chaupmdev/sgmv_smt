<?php
    /**
     * 05_会員登録完了。
     * @package    ssl_html
    * @subpackage event/registed
    * @author     GiapLN(FPT Software) 
     */
    require_once dirname(__FILE__) . '/../../lib/Lib.php';
    Sgmov_Lib::useView('event/Registed');
    /**#@-*/

    // 処理を実行
    $view = new Sgmov_View_Event_Registed();
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
    <title>会員登録の完了│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
<?php
    // キャッシュ対策
    $sysdate = new DateTime();
    $strSysdate = $sysdate->format('YmdHi');
?>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <link href="/event/css/event.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <style>
        /* GiapLN fix bug SMT6-72 18.03.22*/
        .phone {
            background-image: url("images/free_call.png");
            background-repeat: no-repeat;
            width: 60px;
            height: 55px;
            background-size: 60px;
            float: left;
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
            <h1 class="page_title">会員登録の完了</h1>
            
            <div class="section other">
                <form action="/event/login?event_nm=<?php echo $sessionData['event_name']; ?>"  method="post">
                    <div class="section">
                        <div class="dl_block">
                            <dl>
                                <!--<dd style="width:25%;"></dd>-->
                                <dd style="padding-top: 7px;">
                                    <p class="sentence" style="margin-bottom: 5px;margin-top: 0px;padding-top: 0px;">
                                        ご登録いただき、誠にありがとうございます。
                                        <br />
                                        仮パスワードをメールで送信いたしました。
                                        <br />
                                        メール内容をご確認下さい。
                                        <br />
                                        <br />
                                        ※メールが届かない場合はこちらまでお問合せください。
                                        <br />
                                        <br />
                                        <div>
                                            <div class="phone">
                                                
                                            </div>
                                            <div style="font-size: 1.2em;font-weight: bolder; margin-left: 70px;">
                                                <span>ＳＧムービング株式会社</span>
                                                <br/>
                                                <span>03-5857-2462</span>
                                                <br/>
                                                <span>受付時間：平日10時～17時</span>
                                            </div>
                                        </div>
                                        
                                    </p>
                                </dd>
                            </dl>
                        </div>
                    </div>

                    <p class="text_center">
                        <input class="event-btn-submit-en" style="background-image: none;" type="submit" name="submit" value="会員ログイン画面へ遷移する"/>
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
</body>
</html>