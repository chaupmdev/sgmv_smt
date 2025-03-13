<?php

// 公開開始日時
$openDateStr='2022-05-06 00:00:00';
$openDate= new dateTime($openDateStr);
$prm=array(   'open' =>$openDate
             ,'event'=>'Alpen TOKYO'
             ,'dir'  =>'evp' // evp固定で問題ないです
);
$nowDate = new dateTime();
// 現在日時テスト用
//$nowDate= new dateTime('2022-05-05 23:59:59');
//echo('<pre>');var_dump($nowDate);echo('</pre>');
//echo('<pre>');var_dump($openDate);echo('</pre>');

function redirectKohkaimae($prm){
    $title = urlencode("公開前です");
    $weekDay=['(日)','(月)','(火)','(水)','(木)','(金)','(土)',];
    $day =$prm['open']->format('Y/m/d');
    $yobi=$weekDay[$prm['open']->format('w')];
    $time=$prm['open']->format('H:i');

    $message = urlencode("「".$prm['event']."」のお申込は 「".$day." ".$yobi." ".$time."」 から受付を開始致します。");
    $browserTitle = "公開準備中｜SGムービング株式会社＜SGホールディングスグループ＞";
    $browserDiscption = "公開までしばらくお待ちください。";
    header("Location: /" . $prm['dir'] . "/error?t={$title}&m={$message}&bt={$browserTitle}&bd={$browserDiscption}");
    exit;
}

// 公開前
if($nowDate < $openDate){

    // 公開前ページを表示する
    redirectKohkaimae($prm);

    //Basic認証
    $user = 'sgmv';
    $pass = 'sagawa';

    if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER["PHP_AUTH_USER"]==$user && $_SERVER["PHP_AUTH_PW"]==$pass)){
        //print '<div style="position:absolute;top:0px;left:0;z-index:1020;">未公開</div>';
    } else {
        header("WWW-Authenticate: Basic realm=\"basic\"");
        header("HTTP/1.0 401 Unauthorized - basic");
        echo "<script>location.href='/404.html'</script>";
        exit();
    }
}
