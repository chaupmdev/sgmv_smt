<?php

    include '../lib/checkdigit.php';

    session_start();
    
    if(isset($_POST['q1'])){
        $q1 = $_POST['q1'];
    }else{
        $q1 = @$_SESSION['q1'];
    }
    if(isset($_POST['q2'])){
        $q2 = $_POST['q2'];
    }else{
        $q2 = @$_SESSION['q2'];
    }
    if(isset($_POST['q3'])){
        $q3 = $_POST['q3'];
    }else{
        $q3 = @$_SESSION['q3'];
    }
    if(isset($_POST['q4'])){
        $q4 = $_POST['q4'];
    }else{
        $q4 = @$_SESSION['q4'];
    }
    if(isset($_POST['q5'])){
        $q5 = $_POST['q5'];
    }else{
        $q5 = @$_SESSION['q5'];
    }
    if(isset($_POST['q6'])){
        $q6 = $_POST['q6'];
    }else{
        $q6 = @$_SESSION['q6'];
    }
    if(isset($_POST['q7'])){
        $q7 = $_POST['q7'];
    }else{
        $q7 = @$_SESSION['q7'];
    }
    if(isset($_POST['q8'])){
        $q8 = $_POST['q8'];
    }else{
        $q8 = @$_SESSION['q8'];
    }
    if(isset($_POST['q9'])){
        $q9 = $_POST['q9'];
    }else{
        $q9 = @$_SESSION['q9'];
    }
    
    if(isset($_POST['q10'])){
        $q10 = $_POST['q10'];
    }else{
        $q10 = @$_SESSION['q10'];
    }
    
    if(isset($_POST['q11'])){
        $q11 = $_POST['q11'];
    }else{
        $q11 = @$_SESSION['q11'];
    }
    
    if(isset($_POST['q12a'])){
        $q12a = $_POST['q12a'];
    }else{
        $q12a = @$_SESSION['q12a'];
    }
    if(isset($_POST['q12b'])){
        $q12b = $_POST['q12b'];
    }else{
        $q12b = @$_SESSION['q12b'];
    }
    if(isset($_POST['q12c'])){
        $q12c = $_POST['q12c'];
    }else{
        $q12c = @$_SESSION['q12c'];
    }
    if(isset($_POST['q12d'])){
        $q12d = $_POST['q12d'];
    }else{
        $q12d = @$_SESSION['q12d'];
    }
    
    $err1 = isset($_SESSION['err1'])  ? $_SESSION['err1']  : "";
    $err2 = isset($_SESSION['err2'])  ? $_SESSION['err2']  : "";
    $err3 = isset($_SESSION['err3'])  ? $_SESSION['err3']  : "";
    $err4 = isset($_SESSION['err4'])  ? $_SESSION['err4']  : "";
    $err5 = isset($_SESSION['err5'])  ? $_SESSION['err5']  : "";
    $err6 = isset($_SESSION['err6'])  ? $_SESSION['err6']  : "";
    $err7 = isset($_SESSION['err7'])  ? $_SESSION['err7']  : "";
    $err8 = isset($_SESSION['err8'])  ? $_SESSION['err8']  : "";
    
    $err9 = isset($_SESSION['err9'])  ? $_SESSION['err9']  : "";
    
    $err10 = isset($_SESSION['err10'])  ? $_SESSION['err10']  : "";
    $err11 = isset($_SESSION['err11'])  ? $_SESSION['err11']  : "";
    
    $err12a = isset($_SESSION['err12a'])  ? $_SESSION['err12a']  : "";
    $err12b = isset($_SESSION['err12b'])  ? $_SESSION['err12b']  : "";
    $err12c = isset($_SESSION['err12c'])  ? $_SESSION['err12c']  : "";
    $err12d = isset($_SESSION['err12d'])  ? $_SESSION['err12d']  : "";
    
    if(isset($_GET["param"])){
        $param = $_GET["param"];
    }else{
        $param= isset($_POST['param']) ? $_POST['param'] : "";
    }
    
?>

<!DOCTYPE html>
<html dir="ltr" lang="ja-jp" xml:lang="ja-jp">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0" />
    <meta http-equiv="content-style-type" content="text/css" />
    <meta http-equiv="content-script-type" content="text/javascript" />
    <meta name="author" content="SG MOVING Co.,Ltd" />
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved." />
    <title>ＳＧムービング</title>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/enquete/css/form.css" rel="stylesheet" type="text/css" />
    <link href="/enquete/css/common.css" rel="stylesheet" type="text/css" />
		
		
</head>
<?php
    $gnavSettings = 'contact';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/parts/header.php';
?>
    <div id="breadcrumb">
        <ul class="wrap">
            <li><a href="/">ホーム</a></li>
            <li class="current">アンケート</li>
        </ul>
    </div>

    <div id="main">
        <div class="wrap clearfix">
            <h1 class="page_title">CS向上アンケート</h1>

            <p class="sentence">
                サービスレベル向上のため、アンケートのご協力をお願いいたします。
            </p>
  
      
            <form action="./setti_conf.php" method="post">
                <div class="section">

                    <div class="dl_block">
                        <dl>
                            <dt>Q1 配送員から訪問時間のご案内の連絡はありましたか。<!--<span class="nes">必須</span>--></dt>
                            <dd id="cate" style="margin: 0; padding: 5px;">
                                <?php if ($err1 === 'エラー') { ?><div style="color: #F00; margin-top: 10px;">いずれかを選択してください。</div><?php } ?>
                                <ul class="option_ul" style=" padding: 0; border: 0; background-color: #fff;">
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q1" value="有った" <?php if ($q1 === '1') { ?>checked="checked"<?php } ?>>
                                                    有った
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q1" value="無かった"  <?php if ($q1 === '2') { ?>checked="checked"<?php } ?>>
                                                     無かった
                                            </label>
                                            </span>
                                    </li>
                                </ul>
                            </dd>
                        </dl>
                        <dl>
                            <dt>Q2 ご案内の連絡をした時間は適切でしたか。<!--<span class="nes">必須</span>--></dt>
                            <dd id="cate" style="margin: 0; padding: 5px;">
                                <?php if ($err2 === 'エラー') { ?><div style="color: #F00; margin-top: 10px;">いずれかを選択してください。</div><?php } ?>
                                <ul class="option_ul" style=" padding: 0; border: 0; background-color: #fff;">
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q2" value="適切だった" <?php if ($q2 === '1') { ?>checked="checked"<?php } ?>>
                                                    適切だった
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q2" value="適切では無かった" <?php if ($q2 === '2') { ?>checked="checked"<?php } ?>>
                                                     適切では無かった
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q2" value="連絡が無かった" <?php if ($q2 === '3') { ?>checked="checked"<?php } ?>>
                                                     連絡が無かった
                                            </label>
                                            </span>
                                    </li>
                                </ul>
                            </dd>
                        </dl>
                        
                        <dl>
                            <dt>Q3 お約束の作業開始時間は守られましたか。<!--<span class="nes">必須</span>--></dt>
                            <dd id="cate" style="margin: 0; padding: 5px;">
                                <?php if ($err3 === 'エラー') { ?><div style="color: #F00; margin-top: 10px;">いずれかを選択してください。</div><?php } ?>
                                <ul class="option_ul" style=" padding: 0; border: 0; background-color: #fff;">
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q3" value="約束通り" <?php if ($q3 === '1') { ?>checked="checked"<?php } ?>>
                                                    約束通り
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q3" value="早かった（連絡有り）" <?php if ($q3 === '2') { ?>checked="checked"<?php } ?>>
                                                     早かった（連絡有り）
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q3" value="遅れた（連絡有り）" <?php if ($q3 === '3') { ?>checked="checked"<?php } ?>>
                                                     遅れた（連絡有り）
                                            </label>
                                            </span>
                                    </li>
                                    
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q3" value="早かった（連絡なし）" <?php if ($q3 === '2') { ?>checked="checked"<?php } ?>>
                                                     早かった（連絡なし）
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q3" value="遅れた（連絡なし）" <?php if ($q3 === '3') { ?>checked="checked"<?php } ?>>
                                                     遅れた（連絡なし）
                                            </label>
                                            </span>
                                    </li>
                                    
                                </ul>
                            </dd>
                        </dl>
                        
                        <dl>
                            <dt>Q4 配送員の服装・態度・挨拶・言葉使いの印象はいかがでしたか？<!--<span class="nes">必須</span>--></dt>
                            <dd id="cate" style="margin: 0; padding: 5px;">
                                <?php if ($err4 === 'エラー') { ?><div style="color: #F00; margin-top: 10px;">いずれかを選択してください。</div><?php } ?>
                                <ul class="option_ul" style=" padding: 0; border: 0; background-color: #fff;">
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q4" value="とても良い" <?php if ($q4 === '1') { ?>checked="checked"<?php } ?>>
                                                    とても良い
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q4" value="良い" <?php if ($q4 === '2') { ?>checked="checked"<?php } ?>>
                                                     良い
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q4" value="ふつう" <?php if ($q4 === '3') { ?>checked="checked"<?php } ?>>
                                                     ふつう
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q4" value="悪い" <?php if ($q4 === '4') { ?>checked="checked"<?php } ?>>
                                                     悪い
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q4" value="とても悪い" <?php if ($q4 === '5') { ?>checked="checked"<?php } ?>>
                                                     とても悪い
                                            </label>
                                            </span>
                                    </li>
                                </ul>
                            </dd>
                        </dl>
                        <dl>
                            <dt>Q5 作業前にどのように家屋のキズ確認を行いましたか。<!--<span class="nes">必須</span>--></dt>
                            <dd id="cate" style="margin: 0; padding: 5px;">
                                <?php if ($err5 === 'エラー') { ?><div style="color: #F00; margin-top: 10px;">いずれかを選択してください。</div><?php } ?>
                                <ul class="option_ul" style=" padding: 0; border: 0; background-color: #fff;">
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q5" value="お客様と一緒に確認" <?php if ($q5 === '1') { ?>checked="checked"<?php } ?>>
                                                    お客様と一緒に確認
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q5" value="お客様だけで確認" <?php if ($q5=== '2') { ?>checked="checked"<?php } ?>>
                                                     お客様だけで確認
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q5" value="配送員だけで確認" <?php if ($q5 === '3') { ?>checked="checked"<?php } ?>>
                                                     配送員だけで確認
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q5" value="行っていない" <?php if ($q5 === '4') { ?>checked="checked"<?php } ?>>
                                                     行っていない
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q5" value="わからない" <?php if ($q5 === '5') { ?>checked="checked"<?php } ?>>
                                                     わからない
                                            </label>
                                            </span>
                                    </li>
                                </ul>
                            </dd>
                        </dl>
                        <dl>
                            <dt>Q6 作業時に家屋へキズをつけないための配慮はありましたか。<!--<span class="nes">必須</span>--></dt>
                            <dd id="cate" style="margin: 0; padding: 5px;">
                                <?php if ($err6 === 'エラー') { ?><div style="color: #F00; margin-top: 10px;">いずれかを選択してください。</div><?php } ?>
                                <ul class="option_ul" style=" padding: 0; border: 0; background-color: #fff;">
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q6" value="とても良い" <?php if ($q6 === '1') { ?>checked="checked"<?php } ?>>
                                                    とても良い
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q6" value="良い" <?php if ($q6 === '2') { ?>checked="checked"<?php } ?>>
                                                     良い
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q6" value="ふつう" <?php if ($q6 === '3') { ?>checked="checked"<?php } ?>>
                                                     ふつう
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q6" value="悪い" <?php if ($q6 === '4') { ?>checked="checked"<?php } ?>>
                                                     悪い
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q6" value="とても悪い" <?php if ($q6 === '5') { ?>checked="checked"<?php } ?>>
                                                     とても悪い
                                            </label>
                                            </span>
                                    </li>
                                </ul>
                            </dd>
                        </dl>
                        <dl>
                            <dt>Q7 作業時に配達商品へキズをつけないための配慮はありましたか。<!--<span class="nes">必須</span>--></dt>
                            <dd id="cate" style="margin: 0; padding: 5px;">
                                <?php if ($err7 === 'エラー') { ?><div style="color: #F00; margin-top: 10px;">いずれかを選択してください。</div><?php } ?>
                                <ul class="option_ul" style=" padding: 0; border: 0; background-color: #fff;">
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q7" value="とても良い" <?php if ($q7 === '1') { ?>checked="checked"<?php } ?>>
                                                    とても良い
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q7" value="良い" <?php if ($q7 === '2') { ?>checked="checked"<?php } ?>>
                                                     良い
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q7" value="ふつう" <?php if ($q7 === '3') { ?>checked="checked"<?php } ?>>
                                                     ふつう
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q7" value="悪い" <?php if ($q7 === '4') { ?>checked="checked"<?php } ?>>
                                                     悪い
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q7" value="とても悪い" <?php if ($q7 === '5') { ?>checked="checked"<?php } ?>>
                                                     とても悪い
                                            </label>
                                            </span>
                                    </li>
                                </ul>
                            </dd>
                        </dl>
                        
                        <dl>
                            <dt>Q8 納品後の商品のキズ・動作確認を行いましたか(玄関でのお渡しは外装のキズのみ確認)<!--<span class="nes">必須</span>--></dt>
                            <dd id="cate" style="margin: 0; padding: 5px;">
                                <?php if ($err8 === 'エラー') { ?><div style="color: #F00; margin-top: 10px;">いずれかを選択してください。</div><?php } ?>
                                <ul class="option_ul" style=" padding: 0; border: 0; background-color: #fff;">
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q8" value="お客様と一緒に確認" <?php if ($q8 === '1') { ?>checked="checked"<?php } ?>>
                                                    お客様と一緒に確認
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q8" value="お客様だけで確認" <?php if ($q8 === '2') { ?>checked="checked"<?php } ?>>
                                                     お客様だけで確認
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q8" value="配送員だけで確認" <?php if ($q8 === '3') { ?>checked="checked"<?php } ?>>
                                                     配送員だけで確認
                                            </label>
                                            </span>                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q8" value="行っていない" <?php if ($q8 === '4') { ?>checked="checked"<?php } ?>>
                                                     行っていない
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q8" value="わからない" <?php if ($q8 === '5') { ?>checked="checked"<?php } ?>>
                                                     わからない
                                            </label>
                                            </span>
                                    </li>
                                </ul>
                            </dd>
                        </dl>
                        
                        <dl>
                            <dt>Q9 作業後にどのように家屋のキズ確認を行いましたか。<!--<span class="nes">必須</span>--></dt>
                            <dd id="cate" style="margin: 0; padding: 5px;">
                                <?php if ($err9 === 'エラー') { ?><div style="color: #F00; margin-top: 10px;">いずれかを選択してください。</div><?php } ?>
                                <ul class="option_ul" style=" padding: 0; border: 0; background-color: #fff;">
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q9" value="お客様と一緒に確認" <?php if ($q9 === '1') { ?>checked="checked"<?php } ?>>
                                                    お客様と一緒に確認
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q9" value="お客様だけで確認" <?php if ($q9 === '2') { ?>checked="checked"<?php } ?>>
                                                     お客様だけで確認
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q9" value="配送員だけで確認" <?php if ($q9 === '3') { ?>checked="checked"<?php } ?>>
                                                     配送員だけで確認
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q9" value="行っていない" <?php if ($q9 === '4') { ?>checked="checked"<?php } ?>>
                                                     行っていない
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q9" value="わからない" <?php if ($q9 === '5') { ?>checked="checked"<?php } ?>>
                                                     わからない
                                            </label>
                                            </span>
                                    </li>
                                </ul>
                            </dd>
                        </dl>
                        
                        
                        <dl>
                            <dt>Q10 配送サービスをまた利用したいと思いますか？<!--<span class="nes">必須</span>--></dt>
                            
                            <dd id="cate" style="margin: 0; padding: 5px;">
                                <?php if ($err10 === 'エラー') { ?><div style="color: #F00; margin-top: 10px;">いずれかを選択してください。</div><?php } ?>
                                <ul class="option_ul" style=" padding: 0; border: 0; background-color: #fff;">
                                    
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q10" value="とても利用したい" <?php if ($q10 === '1') { ?>checked="checked"<?php } ?>>
                                                    とても利用したい
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q10" value="利用したい" <?php if ($q10 === '2') { ?>checked="checked"<?php } ?>>
                                                     利用したい
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q10" value="どちらでもない" <?php if ($q10 === '3') { ?>checked="checked"<?php } ?>>
                                                     どちらでもない
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q10" value="あまり利用したくない" <?php if ($q10 === '4') { ?>checked="checked"<?php } ?>>
                                                     あまり利用したくない
                                            </label>
                                            </span>
                                    </li>
                                    <li class="hanshutsuOpt" style=";margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q10" value="まったく利用したくない" <?php if ($q10 === '5') { ?>checked="checked"<?php } ?>>
                                                     まったく利用したくない
                                            </label>
                                            </span>
                                    </li>
                                    
                                </ul>
                            </dd>
                        </dl>       
                                         
                        <dl>
                            <dt>
                                Q11 本日の作業に関してご意見がございましたらご記入ください
                                <br />《ご意見欄》※お客様のご意見は、サービスレベル向上以外の目的では使用いたしません
                            </dt>
                            <dd>
                                <?php if ($err11 === 'エラー') {?><div style="color: #F00; margin-bottom: 10px;">全角500文字、半角1000文字を超えています。</div><?php } ?>
                                <?php if ($err11 === 'エラー1') { ?><div style="color: #F00; margin-bottom: 10px;">半角カナ、もしくは特殊文字が含まれています。</div><?php } ?>
                                <textarea class="w100p" cols="70" name="q11" rows="9"  ><?php if (isset($q11)) { echo $q11; } ?></textarea>
                                <p>※全角500文字、半角1000文字まででお願いいたします。<br />
                                   ※絵文字は入力しないでください。</p>
                            </dd>
                        </dl>
                        
                        <dl>
                            <dt>Q12 《Q11》にご記入いただいた内容につきまして、お客様へのご対応をお求めになりますか？
                                <br />（「はい」とご回答いただいた場合は、下の連絡先欄の入力をお願いいたします）
                            </dt>
                            <dd id="cate" style="margin: 0; padding: 5px;">
                                <ul class="option_ul" style=" padding: 0; border: 0; background-color: #fff;">
                                    <?php if ($err12a === 'エラー') { ?><div style="color: #F00; margin-top: 10px;">いずれかを選択してください。</div><?php } ?>
                                    <li class="hanshutsuOpt" style="margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <span class="slct" style="margin: 0;">
                                            <label><input class="outOpt chkHst " type="radio" name="q12a" value="はい" <?php if ($q12a === '1') { ?>checked="checked"<?php } ?>>
                                                    はい
                                            </label>
                                            <label><input class="outOpt chkHst " type="radio" name="q12a" value="いいえ" <?php if ($q12a === '2') { ?>checked="checked"<?php } ?>>
                                                    いいえ
                                            </label>
                                        </span>
                                    </li>
                                    <li style="margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <?php if ($err12b === 'エラー1') {?><div style="color: #F00; margin-bottom: 10px; line-height: 18px;">入力してください。</div><?php } ?>
                                        <?php if ($err12b === 'エラー2') {?><div style="color: #F00; margin-bottom: 10px;">文字数オーバーです。</div><?php } ?>
                                        <?php if ($err12b === 'エラー3') { ?><div style="color: #F00; margin-bottom: 10px;">半角カナ、もしくは特殊文字が含まれています。</div><?php } ?>
                                        <label><span>名前 </span><input type="text" name="q12b" class="" value="<?php if (isset($q12b)) { echo $q12b; } ?>"></label>
                                    </li>
                                    <li style="margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <?php if ($err12c === 'エラー1') {?><div style="color: #F00; margin-bottom: 10px; line-height: 18px;">入力してください。</div><?php } ?>
                                        <?php if ($err12c === 'エラー2') {?><div style="color: #F00; margin-bottom: 10px;">文字数オーバーです。</div><?php } ?>
                                        <?php if ($err12c === 'エラー3') { ?><div style="color: #F00; margin-bottom: 10px;">半角カナ、もしくは特殊文字が含まれています。</div><?php } ?>
                                        <label><span>電話 </span><input type="text" name="q12c" class="" value="<?php if (isset($q12c)) { echo $q12c; } ?>"></label>
                                    </li>
                                    <li style="margin: 0;padding: 30px 0px 20px 0px;border: none;">
                                        <?php if ($err12d === 'エラー1') {?><div style="color: #F00; margin-bottom: 10px; line-height: 18px;">入力してください。</div><?php } ?>
                                        <?php if ($err12d === 'エラー2') {?><div style="color: #F00; margin-bottom: 10px;">文字数オーバーです。</div><?php } ?>
                                        <?php if ($err12d === 'エラー3') { ?><div style="color: #F00; margin-bottom: 10px;">半角カナ、もしくは特殊文字が含まれています。</div><?php } ?>
                                        <label><span>住所 </span><textarea class="txtfiled" name="q12d" style="width: 77%;"><?php if (isset($q12d)) { echo $q12d; } ?></textarea></label>
                                    </li>
                                </ul>
                            </dd>
                        </dl>
                    
                </div>
					
                </div>
                
                <input type="hidden" name="param" value="<?php echo $param; ?>">
                
                <!-- △△個人情報の取り扱い　section　ここまで -->
                <p class="text_center">
                    <input id="submit_button" name="confirm_btn" type="button" value="入力内容の確認" />
                </p>
            </form>

        </div>
    </div>
    <!--main-->

<?php
    $footerSettings = 'under';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/enquete/footer.php';
?>

    <!--[if lt IE 9]>
    <script charset="UTF-8" type="text/javascript" src="/js/jquery-1.12.0.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/lodash-compat.min.js"></script>
    <![endif]-->
    <!--[if gte IE 9]><!-->
    <script charset="UTF-8" type="text/javascript" src="/js/jquery-2.2.0.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/lodash.min.js"></script>
    <!--<![endif]-->
    <script charset="UTF-8" type="text/javascript" src="/js/common.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/multi_send.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/ajax_searchaddress.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/pin/js/hissuChange.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/pin/js/radio.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/pin/js/input.js"></script>
</body>
</html>