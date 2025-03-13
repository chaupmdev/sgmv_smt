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
    <title>催事・イベント配送受付サービスのお申し込み｜お問い合わせ│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
<?php
    // キャッシュ対策
    $sysdate = new DateTime();
    $strSysdate = $sysdate->format('YmdHi');
?>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <link href="/rms/css/eve.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <script src="/js/ga.js" type="text/javascript"></script>
</head>
<body>
<?php
    $gnavSettings = 'contact';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/header.php';
?>
    

    <form name="form1" id="form1" action="/rms/input2" method="POST">
    <input type="hidden" name="data1" id="data1" value=""/>
    <input type="hidden" name="id" id="id" value="<?php echo $_REQUEST["param"]; ?>"/>
    <input type="hidden" name="input2_dialog" id='input2_dialog' value="1"/>
</form>
    <br/>
    <br/>
    <br/>
    <br/>
<?php
    $footerSettings = 'under';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/footer.php';
?>
    
    <script charset="UTF-8" type="text/javascript" src="/js/jquery-2.2.0.min.js"></script>
    
<script>
    $(function() {
        // 入力ダイアログを表示 ＋ 入力内容を user に代入
        var data1 = window.prompt("登録した「電話番号」、「担当者電話番号」、または「メールアドレス」を入力してください", "");

        if(data1 == null || 100 < data1.length || data1 == '') {
            location.href = "/rms/temp_error/";
        } else {
            $('#data1').val(data1);
            $('#form1').submit();
        }
    });
</script>
</body>
</html>