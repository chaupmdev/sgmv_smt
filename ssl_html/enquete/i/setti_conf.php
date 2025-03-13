<?php 

    include_once('../lib/config.php');
    require_once dirname(__FILE__) . '/../../../lib/Lib.php';
    // �o���f�[�V�����`�F�b�N�Ɏg�p(�{���́A�����ł͂Ȃ�/lib/view/enquete/CheckInput.php���쐬���Ă��ׂ�����)
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
    
    $array_quality = array('�ƂĂ��ǂ�'=>'1', '�ǂ�'=>'2', '�ӂ�'=>'3', '����'=>'4', '�ƂĂ�����'=>'5');
    $array_poa = array('�L����'=>'1', '��������'=>'2');
    $array_adq = array('�K�؂�����'=>'1', '�K�؂ł͖�������'=>'2', '�A������������'=>'3');
    
    $array_promise = array('�񑩒ʂ�'=>'1', '���������i�A���L��j'=>'2', '�x�ꂽ�i�A���L��j'=>'3', '���������i�A���Ȃ��j'=>'4', '�x�ꂽ�i�A���Ȃ��j'=>'5');
    $array_use = array('�ƂĂ����p������'=>'1', '���p������'=>'2', '�ǂ���ł��Ȃ�'=>'3', '���܂藘�p�������Ȃ�'=>'4', '�܂��������p�������Ȃ�'=>'5');
    $array_yesno = array('�͂�'=>'1', '������'=>'2');
    
    $array_cus = array('���q�l�ƈꏏ�Ɋm�F'=>'1', '���q�l�����Ŋm�F'=>'2', '�z���������Ŋm�F'=>'3', '�s���Ă��Ȃ�'=>'4', '�킩��Ȃ�'=>'5');
    
    $err = false;$err1 = "";$err2 = "";$err3 = "";$err4 = "";
    $err5 = "";$err6 = "";$err7 = "";$err8 = "";$err9 = "";$err10 = "";
    $err11 = "";
    $err12a = "";$err12b = "";$err12c = "";$err12d = "";
    
    if($q1 === ""){
        $err = true; $err1 = "�G���[";   
    }
    if($q2 === ""){
        $err = true; $err2 = "�G���[";
    }
    if($q3 === ""){
        $err = true; $err3 = "�G���[";
    }
    if($q4 === ""){
        $err = true; $err4 = "�G���[";
    }
    if($q5 === ""){
        $err = true; $err5 = "�G���[";
    }
    if($q6 === ""){
        $err = true; $err6 = "�G���[";
    }
    if($q7 === ""){
        $err = true; $err7 = "�G���[";
    }
    if($q8 === ""){
        $err = true; $err8 = "�G���[";
    }
    if($q9 === ""){
        $err = true; $err9 = "�G���[";
    }
    if($q10 === ""){
        $err = true; $err10 = "�G���[";
    }
    if($q11 === ""){
        //$err = true; $err11 = "�G���[";
    }
    
    //1000�����`�F�b�N
    $q11 = mb_convert_kana($q11, "KV" ,"sjis-win");
    if(strlen($q11) > 1000){
        $err = true; $err11 = "�G���[";
    }
    // ���p�J�i(�ꉞ��L�őS�p�ϊ����Ă��邪�A�O�̂���)�A���ꕶ���`�F�b�N(�{���́A�����ł͂Ȃ�/lib/view/enquete/CheckInput.php���쐬���ă`�F�b�N���ׂ�����)
    $v11 = Sgmov_Component_Validator::createSingleValueValidator($q11)->isNotHalfWidthKana()->isWebSystemNg();
    if (!$v11->isValid()) {
        $err = true;
        $err11 = '�G���[1';
    }
    
    $q12b = mb_convert_kana($q12b, "KV" ,"sjis-win");
    $q12c =   mb_convert_kana($q12c, "KV" ,"sjis-win");
    $q12d =   mb_convert_kana($q12d, "KV" ,"sjis-win");
    
    if($q12a === ""){
        //$err = true; $err12a = "�G���[";
    }
    elseif($array_yesno[$q12a] === "1"){

        if($q12b === ""){
            $err = true; $err12b = "�G���[1";
        }
        if($q12c === ""){
            $err = true; $err12c = "�G���[1";
        }
        if($q12d === ""){
            $err = true; $err12d = "�G���[1";
        }
        if(strlen($q12b) > 150){
            $err = true; $err12b = "�G���[2";
        }
        if(strlen($q12c) > 120){
            $err = true; $err12c = "�G���[2";
        }
        if(strlen($q12d) > 300){
            $err = true; $err12d = "�G���[2";
        }
        // ���p�J�i(�ꉞ��L�őS�p�ϊ����Ă��邪�A�O�̂���)�A���ꕶ���`�F�b�N(�{���́A�����ł͂Ȃ�/lib/view/enquete/CheckInput.php���쐬���ă`�F�b�N���ׂ�����)
        $v12b = Sgmov_Component_Validator::createSingleValueValidator($q12b)->isNotHalfWidthKana()->isWebSystemNg();
        if (!$v12b->isValid()) {
            $err = true;
            $err12b = '�G���[3';
        }
        // ���p�J�i(�ꉞ��L�őS�p�ϊ����Ă��邪�A�O�̂���)�A���ꕶ���`�F�b�N(�{���́A�����ł͂Ȃ�/lib/view/enquete/CheckInput.php���쐬���ă`�F�b�N���ׂ�����)
        $v12c = Sgmov_Component_Validator::createSingleValueValidator($q12c)->isNotHalfWidthKana()->isWebSystemNg();
        if (!$v12c->isValid()) {
            $err = true;
            $err12c = '�G���[3';
        }
        // ���p�J�i(�ꉞ��L�őS�p�ϊ����Ă��邪�A�O�̂���)�A���ꕶ���`�F�b�N(�{���́A�����ł͂Ȃ�/lib/view/enquete/CheckInput.php���쐬���ă`�F�b�N���ׂ�����)
        $v12d = Sgmov_Component_Validator::createSingleValueValidator($q12d)->isNotHalfWidthKana()->isWebSystemNg();
        if (!$v12d->isValid()) {
            $err = true;
            $err12d = '�G���[3';
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
<html lang="ja" dir="ltr"><!-- InstanceBegin template="/Templates/common_smp.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=shift_jis" />
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
<!-- InstanceBeginEditable name="doctitle" -->
<title>SG���[�r���O</title>
<!-- InstanceEndEditable -->
<meta name="description" content="" />
<meta name="keywords" content="" />
</head>
<body id="" class="">

<!--���w�b�_-->
<header id="page_header">
    <div style="padding-top:10px"><img src="./images/img_header_01.jpg" alt="SGH" /> <img style="height: 45px; border-left: 2px solid #7A97C7; padding:0 5px;" src="./images/img_header_02.jpg" alt="SGmoving" /></div>
        <hr style="border-top: 1px solid #7A97C7; margin:10px 0;">    
	<h1>CS����ݹ��</h1>
</header>
<!--���w�b�_-->

<!--���R���e���c-->
<div id="contents">
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q1 �z��������K�⎞�Ԃ̂��ē��̘A���͂���܂������B</strong>
        </div>
        <p>
            <?php echo $q1; ?>
        </p>
        
        </br>
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q2 ���ē��̘A�����������Ԃ͓K�؂ł������B</strong>
        </div>
        <p>
            <?php echo $q2; ?>
        </p>
        
        </br>
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q3 ���񑩂̔z�����Ԃ͎���܂������B</strong>
        </div>
        <p>
            <?php echo $q3; ?>
        </p>
        
        </br>
        
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q4 �z�����̕����E�ԓx�E���A�E���t�g���̈�ۂ͂������ł������H</strong>
        </div>
        <p>
            <?php echo $q4; ?>
        </p>
        
        </br>
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q5 ��ƑO�ɂǂ̂悤�ɉƉ��̃L�Y�m�F���s���܂������B</strong>
        </div>
        <p>
            <?php echo $q5; ?>
        </p>
        
        </br>
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q6 ��Ǝ��ɉƉ��փL�Y�����Ȃ����߂̔z���͂���܂������B</strong>
        </div>
        <p>
            <?php echo $q6; ?>
        </p>
        
        </br>
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q7 ��Ǝ��ɔz�B���i�փL�Y�����Ȃ����߂̔z���͂���܂������B</strong>
        </div>
        <p>
            <?php echo $q7; ?>
        </p>
        
        </br>
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q8 �[�i��̏��i�̃L�Y�E����m�F���s���܂�����(���ւł̂��n���͊O���̃L�Y�̂݊m�F)</strong>
        </div>
        <p>
            <?php echo $q8; ?>
        </p>
        
        </br>
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q9 ��ƌ�ɂǂ̂悤�ɉƉ��̃L�Y�m�F���s���܂������B</strong>
        </div>
        <p>
            <?php echo $q9; ?>
        </p>
        
        </br>
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q10 �z���T�[�r�X���܂����p�������Ǝv���܂����H</strong>
        </div>
        <p>
            <?php echo $q10; ?>
        </p>
        
        </br>
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q11 �{���̍�ƂɊւ��Ă��ӌ����������܂����炲�L����������</strong>
        </div>
        <p><font color="red">�s���ӌ����t�����q�l�̂��ӌ��́A���޽���ٌ���ȊO�̖ړI�ł͎g�p�������܂���</font> </p>
        <p>
            <?php echo $q11; ?>
        </p>
        
        <br />
        
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q12 �sQ11�t�ɂ��L���������������e�ɂ��܂��āA���q�l�ւ̂��Ή��������߂ɂȂ�܂����H
                <br />�i�u�͂��v�Ƃ��񓚂����������ꍇ�́A���̘A���旓�̓��͂����肢�������܂��j</strong>
        </div>
        <p>
            <div style="margin: 0; padding: 5px;"><?php echo $q12a; ?></div>
            <?php if($array_yesno[$q12a] === "1"){ ?>
            <br />
            <div style="margin: 0; padding: 5px;">���O:<?php echo nl2br (htmlspecialchars($q12b, ENT_QUOTES, 'sjis-win')); ?></div><br />
            <div style="margin: 0; padding: 5px;">�d�b:<?php echo nl2br (htmlspecialchars($q12c, ENT_QUOTES, 'sjis-win')); ?></div><br />
            <div style="margin: 0; padding: 5px;">�Z��:<?php echo nl2br (htmlspecialchars($q12d, ENT_QUOTES, 'sjis-win')); ?></div>
            <?php } ?>
        </p>
        
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
            
            <input type="hidden" name="q11" value="<?php echo htmlspecialchars($q11, ENT_QUOTES, 'sjis-win'); ?>">
            
            <input type="hidden" name="q12a" value="<?php echo $array_yesno[$q12a]; ?>">
            <input type="hidden" name="q12b" value="<?php echo htmlspecialchars($q12b, ENT_QUOTES, 'sjis-win'); ?>">
            <input type="hidden" name="q12c" value="<?php echo htmlspecialchars($q12c, ENT_QUOTES, 'sjis-win'); ?>">
            <input type="hidden" name="q12d" value="<?php echo htmlspecialchars($q12d, ENT_QUOTES, 'sjis-win'); ?>">
            
            <input type="hidden" name="ptp" value="<?php echo 1; ?>">
            <input type="hidden" name="kbn" value="<?php echo 0; ?>">
            <input type="hidden" name="param" value="<?php echo $param; ?>">
            <p class="text_center">
                <input id="submit_button" type="submit" value="�߂�" />
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
            <input type="hidden" name="q11" value="<?php echo htmlspecialchars($q11, ENT_QUOTES, 'sjis-win'); ?>">
            
            <input type="hidden" name="q12a" value="<?php echo $array_yesno[$q12a]; ?>">
            <input type="hidden" name="q12b" value="<?php echo htmlspecialchars($q12b, ENT_QUOTES, 'sjis-win'); ?>">
            <input type="hidden" name="q12c" value="<?php echo htmlspecialchars($q12c, ENT_QUOTES, 'sjis-win'); ?>">
            <input type="hidden" name="q12d" value="<?php echo htmlspecialchars($q12d, ENT_QUOTES, 'sjis-win'); ?>">
            
            <input type="hidden" name="ptp" value="<?php echo 1; ?>">
            <input type="hidden" name="kbn" value="<?php echo 0; ?>">
            <input type="hidden" name="param" value="<?php echo $param; ?>">
            <p class="text_center">
                <input id="submit_button" type="submit" value="���M" />
            </p>
        </form>
</div>
<!--���R���e���c-->

<!--���t�b�^-->
<footer id="page_footer">
<!--	<p id="to_top"><a href="#page_header">�y�[�W�g�b�v�ɖ߂�</a></p>
    <div id="page_footer_inner">
		<p class="link">
		|<a href="">�T�C�g�|���V�[</a>
		|<a href="">�v���C�o�V�[�|���V�[</a>
		|</p>
    	<p id="site_top"><a href="/index.html">�T�C�g�g�b�v</a></p>
    </div>-->
	<p id="copyright">(C) SG Moving Co.,Ltd.</p>
</footer>
<!--���t�b�^-->
</body>
</html>