<?php
    /**
     * 03_会員登録せずロボットチェック。
     * @package    ssl_html
    * @subpackage event/robotCheck
    * @author     GiapLN(FPT Software) 
     */

    /**#@+
     * include files
     */
    require_once dirname(__FILE__) . '/../../lib/Lib.php';
    Sgmov_Lib::useView('event/RobotCheck');
    /**#@-*/

    // 処理を実行
    $view = new Sgmov_View_Event_Robot_Check();
    $forms = $view->execute();
    $robot001Out = $forms['outForm'];

    /**
     * エラーフォーム
     * @var Sgmov_Form_Error
     */
    $e = $forms['errorForm'];
    $sessionData = $forms['sessionData'];
    $isDestination = isset($_GET['destination']) ? true: false;
    $strDes = $isDestination ? "?destination=".$_GET['destination'] : '';
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
    <title>ロボットチェック│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
<?php
    // キャッシュ対策
    $sysdate = new DateTime();
    $strSysdate = $sysdate->format('YmdHi');
?>

    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />

    <link href="/event/css/event.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <!-- Google Recaptcha -->
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
            <h1 class="page_title">ロボットチェック</h1>
            <?php
                if (isset($e) && $e->hasError()) { 
            ?>
            <div class="err_msg">
                <p class="sentence br attention">[!ご確認ください]下記の項目が正しく入力・選択されていません。</p>
                <ul>
                    <?php
                    // メールアドレス
                    if ($e->hasErrorForId('top_email')) {
                        echo '<li><a href="#mail">メールアドレス' . $e->getMessage('top_email') . '</a></li>';
                    }
                    
                    
                    ?>
                </ul>
            </div>
            <?php
                }
            ?>
            <div class="section other">
                <form action="/event/check_input_robot.php<?php echo $strDes; ?>"  method="post">
                    <div class="section">
                        <div class="dl_block">
                            <?php if ($isDestination) { ?>
                            <dl>
                                <dt>
                                    メールアドレス
                                </dt>
                                <dd <?php if (isset($e) && $e->hasErrorForId('top_email')) { echo ' class="form_error"'; } ?>>
                                    <input style="width:80%;" autocapitalize="off" autocomplete="off" maxlength="100" name="email" placeholder="" type="email" value="<?php echo $robot001Out->email(); ?>" />
                                </dd>
                            </dl>
                            <?php } ?>
                            <dl>
                                <dd style="width:25%;"></dd>
                                <dd>
                                    <div style="margin-bottom: 9px;">
                                        <span class="red">
                                            　セキュリティ上の理由から、イベントの申し込み前にロボットチェックを行っていただくようお願いいたします。 ご理解とご協力、どうぞよろしくお願いいたします。<br/>
                                        </span>
                                    </div>
                                    <div style="margin-bottom: 9px;">
                                        <span class="red">
                                            ご理解・ご協力のほどよろしくお願いいたします。<br/>
                                        </span>
                                    </div>
                                    <div class="g-recaptcha" data-callback="imNotARobot" data-sitekey="<?php echo $siteKey; ?>"></div>  
                                </dd>
                            </dl>
                        </div>
                    </div>

                    <div class="btn_area">
                        <!--  GiapLN fix comment by TuanLK 2022/03/25 -->
                        <input class="event-btn-submit-disable" disabled="disabled"  type="submit" name="btnRobotCheck" value="申し込み画面へ"/><br/>
                        <a class="btnBackRobot" style="background: url(../images/common/img_allow_10.png) no-repeat 7% center;background-color: #fc826a;margin-top: 15px;padding: 3px 0px;cursor: pointer; max-width: 400px; width: 90%;" onclick='backUserSelect("<?php echo $sessionData['event_name'];?>");'>戻る</a>
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
            $('input[name="btnRobotCheck"]').css('background-color','#1774bc');
            $('input[name="btnRobotCheck"]').css('cursor','pointer');
            $('input[name="btnRobotCheck"]').prop('disabled', false);
        };

        function backUserSelect(event_nm) {
            location.href="userSelect?event_nm=" + event_nm ;
        }
    </script>
</body>
</html>