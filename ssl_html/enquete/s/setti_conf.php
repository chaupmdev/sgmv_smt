<?php 
    include_once('../lib/config.php');
    require_once dirname(__FILE__) . '/../../../lib/Lib.php';
    // バリデーションチェックに使用(本来は、ここではなく/lib/view/enquete/CheckInput.phpを作成してすべきかも)
    Sgmov_Lib::useAllComponents();
    if(session_status() == PHP_SESSION_NONE) {
	session_start();
    }
    
    $q1 = isset($_POST['q1'])  ? $_POST['q1']  : "";
    $q2 = isset($_POST['q2'])  ? $_POST['q2']  : "";
    $q3 = isset($_POST['q3'])  ? $_POST['q3']  : "";
    $q4 = isset($_POST['q4'])  ? $_POST['q4']  : "";
    $q5 = isset($_POST['q5'])  ? $_POST['q5']  : "";
    $q6 = isset($_POST['q6'])  ? $_POST['q6']  : "";
    $q7 = isset($_POST['q7'])  ? $_POST['q7']  : "";
    $q8 = isset($_POST['q8'])  ? $_POST['q8']  : "";
    $q9 = isset($_POST['q9'])  ? $_POST['q9']  : "";
    
    $q10 = isset($_POST['q10'])  ? $_POST['q10']  : "";
    $q11 = isset($_POST['q11'])  ? $_POST['q11']  : "";
    
    $q12a = isset($_POST['q12a'])  ? $_POST['q12a']  : "";
    $q12b = isset($_POST['q12b'])  ? $_POST['q12b']  : "";
    $q12c = isset($_POST['q12c'])  ? $_POST['q12c']  : "";
    $q12d = isset($_POST['q12d'])  ? $_POST['q12d']  : "";
    
    $param= isset($_POST['param']) ? $_POST['param'] : "";
    
    $array_quality = array('とても良い'=>'1', '良い'=>'2', 'ふつう'=>'3', '悪い'=>'4', 'とても悪い'=>'5');
    $array_poa = array('有った'=>'1', '無かった'=>'2');
    $array_adq = array('適切だった'=>'1', '適切では無かった'=>'2', '連絡が無かった'=>'3');
    
    $array_promise = array('約束通り'=>'1', '早かった（連絡有り）'=>'2', '遅れた（連絡有り）'=>'3', '早かった（連絡なし）'=>'4', '遅れた（連絡なし）'=>'5');
    $array_use = array('とても利用したい'=>'1', '利用したい'=>'2', 'どちらでもない'=>'3', 'あまり利用したくない'=>'4', 'まったく利用したくない'=>'5');
    $array_yesno = array('はい'=>'1', 'いいえ'=>'2');
    
    $array_cus = array('お客様と一緒に確認'=>'1', 'お客様だけで確認'=>'2', '配送員だけで確認'=>'3', '行っていない'=>'4', 'わからない'=>'5');
    
    $err = false;$err1 = "";$err2 = "";$err3 = "";$err4 = "";
    $err5 = "";$err6 = "";$err7 = "";$err8 = "";$err9 = "";$err10 = "";
    $err11 = "";
    $err12a = "";$err12b = "";$err12c = "";$err12d = "";
    
    if($q1 === ""){
        $err = true; $err1 = "エラー";   
    }
    if($q2 === ""){
        $err = true; $err2 = "エラー";
    }
    if($q3 === ""){
        $err = true; $err3 = "エラー";
    }
    if($q4 === ""){
        $err = true; $err4 = "エラー";
    }
    if($q5 === ""){
        $err = true; $err5 = "エラー";
    }
    if($q6 === ""){
        $err = true; $err6 = "エラー";
    }
    if($q7 === ""){
        $err = true; $err7 = "エラー";
    }
    if($q8 === ""){
        $err = true; $err8 = "エラー";
    }
    if($q9 === ""){
        $err = true; $err9 = "エラー";
    }
    
    if($q10 === ""){
        $err = true; $err10 = "エラー";
    }
    if($q11 === ""){
        //$err = true; $err11 = "エラー";
    }
    
    //1000文字チェック
    $q11 = mb_convert_kana($q11, "KV");
    if(strlen(mb_convert_encoding( $q11, "sjis-win", "UTF-8")) > 1000){
        $err = true; $err11 = "エラー";
    }
    // 半角カナ(一応上記で全角変換しているが、念のため)、特殊文字チェック(本来は、ここではなく/lib/view/enquete/CheckInput.phpを作成してチェックすべきかも)
    $v11 = Sgmov_Component_Validator::createSingleValueValidator($q11)->isNotHalfWidthKana()->isWebSystemNg();
    if (!$v11->isValid()) {
        $err = true;
        $err11 = 'エラー1';
    }
    if($q12a === ""){
        //$err = true; $err12a = "エラー";
    }
    elseif($array_yesno[$q12a] === "1"){
        $q12b = mb_convert_kana($q12b, "KV");
        $q12c = mb_convert_kana($q12c, "KV");
        $q12d = mb_convert_kana($q12d, "KV");

        if($q12b === ""){
            $err = true; $err12b = "エラー1";
        }
        if($q12c === ""){
            $err = true; $err12c = "エラー1";
        }
        if($q12d === ""){
            $err = true; $err12d = "エラー1";
        }
        if(strlen(mb_convert_encoding( $q12b, "sjis-win", "UTF-8")) > 150){
            $err = true; $err12b = "エラー2";
        }
        if(strlen(mb_convert_encoding( $q12c, "sjis-win", "UTF-8")) > 100){
            $err = true; $err12c = "エラー2";
        }
        if(strlen(mb_convert_encoding( $q12d, "sjis-win", "UTF-8")) > 300){
            $err = true; $err12d = "エラー2";
        }
        
        // 半角カナ(一応上記で全角変換しているが、念のため)、特殊文字チェック(本来は、ここではなく/lib/view/enquete/CheckInput.phpを作成してチェックすべきかも)
        $v12b = Sgmov_Component_Validator::createSingleValueValidator($q12b)->isNotHalfWidthKana()->isWebSystemNg();
        if (!$v12b->isValid()) {
            $err = true;
            $err12b = 'エラー3';
        }
        // 半角カナ(一応上記で全角変換しているが、念のため)、特殊文字チェック(本来は、ここではなく/lib/view/enquete/CheckInput.phpを作成してチェックすべきかも)
        $v12c = Sgmov_Component_Validator::createSingleValueValidator($q12c)->isNotHalfWidthKana()->isWebSystemNg();
        if (!$v12c->isValid()) {
            $err = true;
            $err12c = 'エラー3';
        }
        // 半角カナ(一応上記で全角変換しているが、念のため)、特殊文字チェック(本来は、ここではなく/lib/view/enquete/CheckInput.phpを作成してチェックすべきかも)
        $v12d = Sgmov_Component_Validator::createSingleValueValidator($q12d)->isNotHalfWidthKana()->isWebSystemNg();
        if (!$v12d->isValid()) {
            $err = true;
            $err12d = 'エラー3';
        }
    }

    
    if($err == true){
        
        $_SESSION['q1'] = $array_poa[$q1];
        $_SESSION['q2'] = $array_adq[$q2];
        $_SESSION['q3'] = $array_promise[$q3];
        $_SESSION['q4'] = $array_quality[$q4];
        $_SESSION['q5'] = $array_cus[$q5];
        $_SESSION['q6'] = $array_quality[$q6];
        $_SESSION['q7'] = $array_quality[$q7];
        $_SESSION['q8'] = $array_cus[$q8];
        $_SESSION['q9'] = $array_cus[$q9];
        $_SESSION['q10'] = $array_use[$q10];
        $_SESSION['q11'] = $q11;
        
        $_SESSION['q12a'] = $array_yesno[$q12a];
        $_SESSION['q12b'] = $q12b;
        $_SESSION['q12c'] = $q12c;
        $_SESSION['q12d'] = $q12d;
        
        $_SESSION['param'] = $param;
        $_SESSION['err1'] = $err1;
        $_SESSION['err2'] = $err2;
        $_SESSION['err3'] = $err3;
        $_SESSION['err4'] = $err4;
        $_SESSION['err5'] = $err5;
        $_SESSION['err6'] = $err6;
        $_SESSION['err7'] = $err7;
        $_SESSION['err8'] = $err8;
        $_SESSION['err9'] = $err9;
        $_SESSION['err10'] = $err10;
        $_SESSION['err11'] = $err11;
        
        $_SESSION['err12a'] = $err12a;
        $_SESSION['err12b'] = $err12b;
        $_SESSION['err12c'] = $err12c;
        $_SESSION['err12d'] = $err12d;
        
        header("Location: setti.php?param=".$param);
        exit;
    }
    
    session_destroy();
    
?>
<!DOCTYPE html>
<html dir="ltr" lang="ja-jp" xml:lang="ja-jp">
<head>
    <meta charset="utf-8" />
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
  
                <div class="section">

                    <div class="dl_block">
                        <dl>
                            <dt>Q1 配送員から訪問時間のご案内の連絡はありましたか。<!--<span class="nes">必須</span>--></dt>
                            <dd id="cate" style="margin: 0; padding: 5px;">
                                <span class="slct" style="margin: 0;"><?php echo $q1; ?></span>
                            </dd>
                        </dl>
                        <dl>
                            <dt>Q2 ご案内の連絡をした時間は適切でしたか。<!--<span class="nes">必須</span>--></dt>
                            <dd id="cate" style="margin: 0; padding: 5px;">
                                <span class="slct" style="margin: 0;"><?php echo $q2; ?></span>
                            </dd>
                        </dl>
                        <dl>
                            <dt>Q3 お約束の配送時間は守られましたか。<!--<span class="nes">必須</span>--></dt>
                            <dd id="cate" style="margin: 0; padding: 5px;">
                                <span class="slct" style="margin: 0;"><?php echo $q3; ?></span>
                            </dd>
                        </dl>
                        <dl>
                            <dt>Q4 配送員の服装・態度・挨拶・言葉使いの印象はいかがでしたか？<!--<span class="nes">必須</span>--></dt>
                            <dd id="cate" style="margin: 0; padding: 5px;">
                                <span class="slct" style="margin: 0;"><?php echo $q4; ?></span>
                            </dd>
                        </dl>
                        <dl>
                            <dt>Q5 作業前にどのように家屋のキズ確認を行いましたか。<!--<span class="nes">必須</span>--></dt>
                            <dd id="cate" style="margin: 0; padding: 5px;">
                                <span class="slct" style="margin: 0;"><?php echo $q5; ?></span>
                            </dd>
                        </dl>
                        <dl>
                            <dt>Q6 作業時に家屋へキズをつけないための配慮はありましたか。<!--<span class="nes">必須</span>--></dt>
                            <dd id="cate" style="margin: 0; padding: 5px;">
                                <span class="slct" style="margin: 0;"><?php echo $q6; ?></span>
                            </dd>
                        </dl>
                        <dl>
                            <dt>Q7 作業時に配達商品へキズをつけないための配慮はありましたか。<!--<span class="nes">必須</span>--></dt>
                            <dd id="cate" style="margin: 0; padding: 5px;">
                                <span class="slct" style="margin: 0;"><?php echo $q7; ?></span>
                            </dd>
                        </dl>
                        <dl>
                            <dt>Q8 納品後の商品のキズ・動作確認を行いましたか(玄関でのお渡しは外装のキズのみ確認)<!--<span class="nes">必須</span>--></dt>
                            <dd id="cate" style="margin: 0; padding: 5px;">
                                <span class="slct" style="margin: 0;"><?php echo $q8; ?></span>
                            </dd>
                        </dl>
                        <dl>
                            <dt>Q9 作業後にどのように家屋のキズ確認を行いましたか。<!--<span class="nes">必須</span>--></dt>
                            <dd id="cate" style="margin: 0; padding: 5px;">
                                <span class="slct" style="margin: 0;"><?php echo $q9; ?></span>
                            </dd>
                        </dl>
                        
                        <dl>
                            <dt>Q10 配送サービスをまた利用したいと思いますか？<!--<span class="nes">必須</span>--></dt>
                            <dd id="cate" style="margin: 0; padding: 5px;">
                                <span class="slct" style="margin: 0;"><?php echo $q10; ?></span>
                            </dd>
                        </dl>
                        <dl>
                            <dt>Q11 本日の作業に関してご意見がございましたらご記入ください<!--<span class="nes">必須</span>--></dt>
                            <dd id="cate" style="margin: 0; padding: 5px;">
                                <span class="slct" style="margin: 0;"><?php echo $q11; ?></span>
                            </dd>
                        </dl>
                                          
                        <dl>
                            <dt>Q12 《Q11》にご記入いただいた内容につきまして、お客様へのご対応をお求めになりますか？
                                <br />（「はい」とご回答いただいた場合は、下の連絡先欄の入力をお願いいたします）
                            </dt>
                            
                            <div style="margin: 0; padding: 5px;"><?php echo $q12a; ?></div>
                            <?php if($array_yesno[$q12a] === "1"){ ?>
                            <br />
                            <div style="margin: 0; padding: 5px;">名前:<?php echo nl2br (htmlspecialchars($q12b, ENT_QUOTES)); ?></div><br />
                            <div style="margin: 0; padding: 5px;">電話:<?php echo nl2br (htmlspecialchars($q12c, ENT_QUOTES)); ?></div><br />
                            <div style="margin: 0; padding: 5px;">住所:<?php echo nl2br (htmlspecialchars($q12d, ENT_QUOTES)); ?></div>
                            <?php } ?>
                        </dl>
                    
                </div>
					
                </div>
                <form action="./setti.php" method="post">

                    <input type="hidden" name="q1" value="<?php echo $array_poa[$q1]; ?>">
                    <input type="hidden" name="q2" value="<?php echo $array_adq[$q2]; ?>">
                    <input type="hidden" name="q3" value="<?php echo $array_promise[$q3]; ?>">
                    <input type="hidden" name="q4" value="<?php echo $array_quality[$q4]; ?>">
                    <input type="hidden" name="q5" value="<?php echo $array_cus[$q5]; ?>">
                    <input type="hidden" name="q6" value="<?php echo $array_quality[$q6]; ?>">
                    <input type="hidden" name="q7" value="<?php echo $array_quality[$q7]; ?>">
                    <input type="hidden" name="q8" value="<?php echo $array_cus[$q8]; ?>">
                    <input type="hidden" name="q9" value="<?php echo $array_cus[$q9]; ?>">
                    
                    <input type="hidden" name="q10" value="<?php echo $array_use[$q10]; ?>">
            
                    <input type="hidden" name="q11" value="<?php echo htmlspecialchars($q11, ENT_QUOTES); ?>">
                    
                    <input type="hidden" name="q12a" value="<?php echo $array_yesno[$q12a]; ?>">
                    <input type="hidden" name="q12b" value="<?php echo htmlspecialchars($q12b, ENT_QUOTES); ?>">
                    <input type="hidden" name="q12c" value="<?php echo htmlspecialchars($q12c, ENT_QUOTES); ?>">
                    <input type="hidden" name="q12d" value="<?php echo htmlspecialchars($q12d, ENT_QUOTES); ?>">
                    
                    <input type="hidden" name="ptp" value="<?php echo 0; ?>">
                    <input type="hidden" name="kbn" value="<?php echo 0; ?>">
                    <input type="hidden" name="param" value="<?php echo $param; ?>">
                    <p class="text_center">
                        <input id="submit_button_rt" type="submit" value="戻る" />
                    </p>
                </form>
            
                <form action="../lib/dbinsert.php" method="post">

                    <input type="hidden" name="q1" value="<?php echo $array_poa[$q1]; ?>">
                    <input type="hidden" name="q2" value="<?php echo $array_adq[$q2]; ?>">
                    <input type="hidden" name="q3" value="<?php echo $array_promise[$q3]; ?>">
                    <input type="hidden" name="q4" value="<?php echo $array_quality[$q4]; ?>">
                    <input type="hidden" name="q5" value="<?php echo $array_cus[$q5]; ?>">
                    <input type="hidden" name="q6" value="<?php echo $array_quality[$q6]; ?>">
                    <input type="hidden" name="q7" value="<?php echo $array_quality[$q7]; ?>">
                    <input type="hidden" name="q8" value="<?php echo $array_cus[$q8]; ?>">
                    <input type="hidden" name="q9" value="<?php echo $array_cus[$q9]; ?>">
                    
                    <input type="hidden" name="q10" value="<?php echo $array_use[$q10]; ?>">
            
                    <input type="hidden" name="q11" value="<?php echo htmlspecialchars($q11, ENT_QUOTES); ?>">
                    
                    <input type="hidden" name="q12a" value="<?php echo $array_yesno[$q12a]; ?>">
                    <input type="hidden" name="q12b" value="<?php echo htmlspecialchars($q12b, ENT_QUOTES); ?>">
                    <input type="hidden" name="q12c" value="<?php echo htmlspecialchars($q12c, ENT_QUOTES); ?>">
                    <input type="hidden" name="q12d" value="<?php echo htmlspecialchars($q12d, ENT_QUOTES); ?>">
                    
                    <input type="hidden" name="ptp" value="<?php echo 0; ?>">
                    <input type="hidden" name="kbn" value="<?php echo 0; ?>">
                    <input type="hidden" name="param" value="<?php echo $param; ?>">
                    <p class="text_center">
                        <input id="submit_button" type="submit" value="入力内容の送信" />
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