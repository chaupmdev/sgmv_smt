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
<html lang="ja" dir="ltr"><!-- InstanceBegin template="/Templates/common_smp.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=shift_jis" />
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
<!-- InstanceBeginEditable name="doctitle" -->
<title>SGムービング</title>
<!-- InstanceEndEditable -->
<meta name="description" content="" />
<meta name="keywords" content="" />
</head>
<body id="" class="">

<!--▼ヘッダ-->
<header id="page_header">
    <div style="padding-top:10px"><img src="./images/img_header_01.jpg" alt="SGH" /> <img style="height: 45px; border-left: 2px solid #7A97C7; padding:0 5px;" src="./images/img_header_02.jpg" alt="SGmoving" /></div>
        <hr style="border-top: 1px solid #7A97C7; margin:10px 0;">  
	<h1>CS向上ｱﾝｹｰﾄ</h1>
        
        <p>ｻｰﾋﾞｽﾚﾍﾞﾙ向上のため、ｱﾝｹｰﾄのご協力をお願いいたします。</p>
        <?php // echo $param; ?>
</header>
<!--▲ヘッダ-->

<!--▼コンテンツ-->
<div id="contents">
    <form action="./hikkoshi_conf.php" method="post">
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q1 お約束の作業開始時間は守られましたか。</strong>
        </div>
        <p>
            <?php if ($err1 === 'エラー') {?><div style="color: #F00; margin-bottom: 10px;">いずれかを選択してください。</div><br /><?php } ?>
            <input type="radio" name="q1" value="約束通り" <?php if ($q1 === '1') { ?>checked="cheked"<?php } ?> >約束通り <br />
            <input type="radio" name="q1" value="早かった（連絡有り）" <?php if ($q1 === '2') { ?>checked="cheked"<?php } ?> >早かった（連絡有り）<br />
            <input type="radio" name="q1" value="遅れた（連絡有り）" <?php if ($q1 === '3') { ?>checked="cheked"<?php } ?> >遅れた（連絡有り）<br />
            <input type="radio" name="q1" value="早かった（連絡なし）" <?php if ($q1 === '4') { ?>checked="cheked"<?php } ?> >早かった（連絡なし）<br />
            <input type="radio" name="q1" value="遅れた（連絡なし）" <?php if ($q1 === '5') { ?>checked="cheked"<?php } ?> >遅れた（連絡なし）
        </p>
        
        <br />
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q2 引越スタッフの服装・態度・挨拶・言葉使いの印象はいかがでしたか。</strong>
        </div>
        <p>
            <?php if ($err2 === 'エラー') {?><div style="color: #F00; margin-bottom: 10px;">いずれかを選択してください。</div><br /><?php } ?>
            <input type="radio" name="q2" value="とても良い" <?php if ($q2 === '1') { ?>checked="cheked"<?php } ?>>とても良い <br />
            <input type="radio" name="q2" value="良い" <?php if ($q2 === '2') { ?>checked="cheked"<?php } ?>>良い<br />
            <input type="radio" name="q2" value="ふつう" <?php if ($q2 === '3') { ?>checked="cheked"<?php } ?>>ふつう<br />
            <input type="radio" name="q2" value="悪い" <?php if ($q2 === '4') { ?>checked="cheked"<?php } ?>>悪い<br />
            <input type="radio" name="q2" value="とても悪い" <?php if ($q2 === '5') { ?>checked="cheked"<?php } ?>>とても悪い
        </p>
        
        <br />
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q3 作業前にどのように家屋・家財のキズ確認を行いましたか。</strong>
        </div>
        <p>
            <?php if ($err3 === 'エラー') {?><div style="color: #F00; margin-bottom: 10px;">いずれかを選択してください。</div><br /><?php } ?>
            <input type="radio" name="q3" value="お客様と一緒に確認" <?php if ($q3 === '1') { ?>checked="cheked"<?php } ?>>お客様と一緒に確認<br /> 
            <input type="radio" name="q3" value="お客様が確認" <?php if ($q3 === '2') { ?>checked="cheked"<?php } ?>>お客様が確認<br />
            <input type="radio" name="q3" value="配送員が確認" <?php if ($q3 === '3') { ?>checked="cheked"<?php } ?>>配送員が確認<br />
            <input type="radio" name="q3" value="行っていない" <?php if ($q3 === '4') { ?>checked="cheked"<?php } ?>>行っていない<br />
            <input type="radio" name="q3" value="わからない" <?php if ($q3 === '5') { ?>checked="cheked"<?php } ?>>わからない
        </p>
        
        <br />
        
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q4 作業時に家屋へキズをつけないための配慮はありましたか。</strong>
        </div>
        <p>
            <?php if ($err4 === 'エラー') {?><div style="color: #F00; margin-bottom: 10px;">いずれかを選択してください。</div><br /><?php } ?>
            <input type="radio" name="q4" value="とても良い" <?php if ($q4 === '1') { ?>checked="cheked"<?php } ?>>とても良い <br />
            <input type="radio" name="q4" value="良い" <?php if ($q4 === '2') { ?>checked="cheked"<?php } ?>>良い<br />
            <input type="radio" name="q4" value="ふつう" <?php if ($q4 === '3') { ?>checked="cheked"<?php } ?>>ふつう<br />
            <input type="radio" name="q4" value="悪い" <?php if ($q4 === '4') { ?>checked="cheked"<?php } ?>>悪い<br />
            <input type="radio" name="q4" value="とても悪い" <?php if ($q4 === '5') { ?>checked="cheked"<?php } ?>>とても悪い
        </p>
        
        <br />
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q5 作業時に家財・引越荷物へキズをつけないための配慮はありましたか。</strong>
        </div>
        <p>
            <?php if ($err5 === 'エラー') {?><div style="color: #F00; margin-bottom: 10px;">いずれかを選択してください。</div><br /><?php } ?>
            <input type="radio" name="q5" value="とても良い" <?php if ($q5 === '1') { ?>checked="cheked"<?php } ?>>とても良い <br />
            <input type="radio" name="q5" value="良い" <?php if ($q5 === '2') { ?>checked="cheked"<?php } ?>>良い<br />
            <input type="radio" name="q5" value="ふつう" <?php if ($q5 === '3') { ?>checked="cheked"<?php } ?>>ふつう<br />
            <input type="radio" name="q5" value="悪い" <?php if ($q5 === '4') { ?>checked="cheked"<?php } ?>>悪い<br />
            <input type="radio" name="q5" value="とても悪い" <?php if ($q5 === '5') { ?>checked="cheked"<?php } ?>>とても悪い
        </p>
        
        <br />
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q6 作業効率・段取り・作業時間はいかがでしたか。</strong>
        </div>
        <p>
            <?php if ($err6 === 'エラー') {?><div style="color: #F00; margin-bottom: 10px;">いずれかを選択してください。</div><br /><?php } ?>
            <input type="radio" name="q6" value="とても良い" <?php if ($q6 === '1') { ?>checked="cheked"<?php } ?>>とても良い <br />
            <input type="radio" name="q6" value="良い" <?php if ($q6 === '2') { ?>checked="cheked"<?php } ?>>良い<br />
            <input type="radio" name="q6" value="ふつう" <?php if ($q6 === '3') { ?>checked="cheked"<?php } ?>>ふつう<br />
            <input type="radio" name="q6" value="悪い" <?php if ($q6 === '4') { ?>checked="cheked"<?php } ?>>悪い<br />
            <input type="radio" name="q6" value="とても悪い" <?php if ($q6 === '5') { ?>checked="cheked"<?php } ?>>とても悪い
        </p>
        
        <br />
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q7 お客様の指示通りのレイアウトに搬入を行いましたか。</strong>
        </div>
        <p>
            <?php if ($err7 === 'エラー') {?><div style="color: #F00; margin-bottom: 10px;">いずれかを選択してください。</div><br /><?php } ?>
            <input type="radio" name="q7" value="とても良い" <?php if ($q7 === '1') { ?>checked="cheked"<?php } ?>>とても良い <br />
            <input type="radio" name="q7" value="良い" <?php if ($q7 === '2') { ?>checked="cheked"<?php } ?>>良い<br />
            <input type="radio" name="q7" value="ふつう" <?php if ($q7 === '3') { ?>checked="cheked"<?php } ?>>ふつう<br />
            <input type="radio" name="q7" value="悪い" <?php if ($q7 === '4') { ?>checked="cheked"<?php } ?>>悪い<br />
            <input type="radio" name="q7" value="とても悪い" <?php if ($q7 === '5') { ?>checked="cheked"<?php } ?>>とても悪い
        </p>
        
        <br />
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q8 作業終了時に資材・作業道具・梱包廃材等を残さず持ち帰りましたか。</strong>
        </div>
        <p>
            <?php if ($err8 === 'エラー') {?><div style="color: #F00; margin-bottom: 10px;">いずれかを選択してください。</div><br /><?php } ?>
            <input type="radio" name="q8" value="とても良い" <?php if ($q8 === '1') { ?>checked="cheked"<?php } ?>>とても良い <br />
            <input type="radio" name="q8" value="良い" <?php if ($q8 === '2') { ?>checked="cheked"<?php } ?>>良い<br />
            <input type="radio" name="q8" value="ふつう" <?php if ($q8 === '3') { ?>checked="cheked"<?php } ?>>ふつう<br />
            <input type="radio" name="q8" value="悪い" <?php if ($q8 === '4') { ?>checked="cheked"<?php } ?>>悪い<br />
            <input type="radio" name="q8" value="とても悪い" <?php if ($q8 === '5') { ?>checked="cheked"<?php } ?>>とても悪い
        </p>
        
        <br />
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q9 作業後にどのように家屋・家財のキズ確認を行いましたか。</strong>
        </div>
        <p>
            <?php if ($err9 === 'エラー') {?><div style="color: #F00; margin-bottom: 10px;">いずれかを選択してください。</div><br /><?php } ?>
            <input type="radio" name="q9" value="お客様と一緒に確認" <?php if ($q9 === '1') { ?>checked="cheked"<?php } ?>>お客様と一緒に確認 <br />
            <input type="radio" name="q9" value="お客様が確認" <?php if ($q9 === '2') { ?>checked="cheked"<?php } ?>>お客様が確認<br />
            <input type="radio" name="q9" value="配送員が確認" <?php if ($q9 === '3') { ?>checked="cheked"<?php } ?>>配送員が確認<br />
            <input type="radio" name="q9" value="行っていない" <?php if ($q9 === '4') { ?>checked="cheked"<?php } ?>>行っていない<br />
            <input type="radio" name="q9" value="わからない" <?php if ($q9 === '5') { ?>checked="cheked"<?php } ?>>わからない
        </p>
        
        <br />
        
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q10 ＳＧムービングの引越をまた利用したいと思いますか？</strong>
        </div>
        <p>
            <?php if ($err10 === 'エラー') {?><div style="color: #F00; margin-bottom: 10px;">いずれかを選択してください。</div><br /><?php } ?>
            <input type="radio" name="q10" value="とても利用したい" <?php if ($q10 === '1') { ?>checked="cheked"<?php } ?>>とても利用したい <br />
            <input type="radio" name="q10" value="利用したい" <?php if ($q10 === '2') { ?>checked="cheked"<?php } ?>>利用したい<br />
            <input type="radio" name="q10" value="どちらでもない" <?php if ($q10 === '3') { ?>checked="cheked"<?php } ?>>どちらでもない<br />
            <input type="radio" name="q10" value="あまり利用したくない" <?php if ($q10 === '4') { ?>checked="cheked"<?php } ?>>あまり利用したくない<br />
            <input type="radio" name="q10" value="まったく利用したくない" <?php if ($q10 === '5') { ?>checked="cheked"<?php } ?>>まったく利用したくない
        </p>
        
        <br />
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q11 本日の作業に関してご意見がございましたらご記入ください</strong>
        </div>
        <p><font color="red">《ご意見欄》※お客様のご意見は、ｻｰﾋﾞｽﾚﾍﾞﾙ向上以外の目的では使用いたしません</font> </p>
        <p>
            <?php if ($err11 === 'エラー') {?><div style="color: #F00; margin-bottom: 10px;">※全角500文字、半角1000文字を超えています。</div><?php } ?>
            <?php if ($err11 === 'エラー1') { ?><div style="color: #F00; margin-bottom: 10px;">半角カナ、もしくは特殊文字が含まれています。</div><?php } ?>
            <textarea name="q11" rows="5" cols="50" maxlength="1000"><?php if (isset($q11)) { echo $q11; } ?></textarea>
            <br />※全角500文字、半角1000文字まででお願いいたします。
            <br />※絵文字は入力しないでください。
        </p>
        
        <br />
        
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q12 《Q11》にご記入いただいた内容につきまして、お客様へのご対応をお求めになりますか？
                <br />（「はい」とご回答いただいた場合は、下の連絡先欄の入力をお願いいたします）</strong>
        </div>
        <p>
            <?php if ($err12a === 'エラー') {?><div style="color: #F00;">いずれかを選択してください。</div><br /><?php } ?>
            <input type="radio" name="q12a" value="はい" <?php if ($q12a === '1') { ?>checked="cheked"<?php } ?>>はい
            <input type="radio" name="q12a" value="いいえ" <?php if ($q12a === '2') { ?>checked="cheked"<?php } ?>>いいえ<br />
            
            <?php if ($err12b === 'エラー1') {?><div style="color: #F00; ">入力してください。</div><br /><?php } ?>
            <?php if ($err12b === 'エラー2') {?><div style="color: #F00; ">文字数オーバーです。</div><br /><?php } ?>
            <?php if ($err12b === 'エラー3') {?><div style="color: #F00; ">半角カナ、もしくは特殊文字が含まれています。</div><br /><?php } ?>
            <span>名前 </span><input type="text" name="q12b" class="" value="<?php if (isset($q12b)) { echo $q12b; } ?>"><br />
            <?php if ($err12c === 'エラー1') {?><div style="color: #F00;">入力してください。</div><br /><?php } ?>
            <?php if ($err12c === 'エラー2') {?><div style="color: #F00; ">文字数オーバーです。</div><br /><?php } ?>
            <?php if ($err12c === 'エラー3') {?><div style="color: #F00; ">半角カナ、もしくは特殊文字が含まれています。</div><br /><?php } ?>
            <span>電話 </span><input type="text" name="q12c" class="" value="<?php if (isset($q12c)) { echo $q12c; } ?>"><br />
            <?php if ($err12d === 'エラー1') {?><div style="color: #F00; ">入力してください。</div><br /><?php } ?>
            <?php if ($err12d === 'エラー2') {?><div style="color: #F00; ">文字数オーバーです。</div><br /><?php } ?>
            <?php if ($err12d === 'エラー3') {?><div style="color: #F00; ">半角カナ、もしくは特殊文字が含まれています。</div><br /><?php } ?>
            <span>住所 </span><textarea class="txtfiled" name="q12d"><?php if (isset($q12d)) { echo $q12d; } ?></textarea>
        </p>
        
        <input type="hidden" name="param" value="<?php echo $param; ?>">
        
        
        <div style="margin-bottom: 5px;">
        <input type="submit" value="確認">
        </div>
    </form>
</div>
<!--▲コンテンツ-->

<!--▼フッタ-->
<footer id="page_footer">
<!--	<p id="to_top"><a href="#page_header">ページトップに戻る</a></p>
    <div id="page_footer_inner">
		<p class="link">
		|<a href="">サイトポリシー</a>
		|<a href="">プライバシーポリシー</a>
		|</p>
    	<p id="site_top"><a href="/index.html">サイトトップ</a></p>
    </div>-->
	<p id="copyright">(C) SG Moving Co.,Ltd.</p>
</footer>
<!--▲フッタ-->
</body>
</html>
