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
        
        <p>���޽���ٌ���̂��߁A�ݹ�Ă̂����͂����肢�������܂��B</p>
        <?php // echo $param; ?>
</header>
<!--���w�b�_-->

<!--���R���e���c-->
<div id="contents">
    <form action="./hikkoshi_conf.php" method="post">
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q1 ���񑩂̍�ƊJ�n���Ԃ͎���܂������B</strong>
        </div>
        <p>
            <?php if ($err1 === '�G���[') {?><div style="color: #F00; margin-bottom: 10px;">�����ꂩ��I�����Ă��������B</div><br /><?php } ?>
            <input type="radio" name="q1" value="�񑩒ʂ�" <?php if ($q1 === '1') { ?>checked="cheked"<?php } ?> >�񑩒ʂ� <br />
            <input type="radio" name="q1" value="���������i�A���L��j" <?php if ($q1 === '2') { ?>checked="cheked"<?php } ?> >���������i�A���L��j<br />
            <input type="radio" name="q1" value="�x�ꂽ�i�A���L��j" <?php if ($q1 === '3') { ?>checked="cheked"<?php } ?> >�x�ꂽ�i�A���L��j<br />
            <input type="radio" name="q1" value="���������i�A���Ȃ��j" <?php if ($q1 === '4') { ?>checked="cheked"<?php } ?> >���������i�A���Ȃ��j<br />
            <input type="radio" name="q1" value="�x�ꂽ�i�A���Ȃ��j" <?php if ($q1 === '5') { ?>checked="cheked"<?php } ?> >�x�ꂽ�i�A���Ȃ��j
        </p>
        
        <br />
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q2 ���z�X�^�b�t�̕����E�ԓx�E���A�E���t�g���̈�ۂ͂������ł������B</strong>
        </div>
        <p>
            <?php if ($err2 === '�G���[') {?><div style="color: #F00; margin-bottom: 10px;">�����ꂩ��I�����Ă��������B</div><br /><?php } ?>
            <input type="radio" name="q2" value="�ƂĂ��ǂ�" <?php if ($q2 === '1') { ?>checked="cheked"<?php } ?>>�ƂĂ��ǂ� <br />
            <input type="radio" name="q2" value="�ǂ�" <?php if ($q2 === '2') { ?>checked="cheked"<?php } ?>>�ǂ�<br />
            <input type="radio" name="q2" value="�ӂ�" <?php if ($q2 === '3') { ?>checked="cheked"<?php } ?>>�ӂ�<br />
            <input type="radio" name="q2" value="����" <?php if ($q2 === '4') { ?>checked="cheked"<?php } ?>>����<br />
            <input type="radio" name="q2" value="�ƂĂ�����" <?php if ($q2 === '5') { ?>checked="cheked"<?php } ?>>�ƂĂ�����
        </p>
        
        <br />
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q3 ��ƑO�ɂǂ̂悤�ɉƉ��E�ƍ��̃L�Y�m�F���s���܂������B</strong>
        </div>
        <p>
            <?php if ($err3 === '�G���[') {?><div style="color: #F00; margin-bottom: 10px;">�����ꂩ��I�����Ă��������B</div><br /><?php } ?>
            <input type="radio" name="q3" value="���q�l�ƈꏏ�Ɋm�F" <?php if ($q3 === '1') { ?>checked="cheked"<?php } ?>>���q�l�ƈꏏ�Ɋm�F<br /> 
            <input type="radio" name="q3" value="���q�l���m�F" <?php if ($q3 === '2') { ?>checked="cheked"<?php } ?>>���q�l���m�F<br />
            <input type="radio" name="q3" value="�z�������m�F" <?php if ($q3 === '3') { ?>checked="cheked"<?php } ?>>�z�������m�F<br />
            <input type="radio" name="q3" value="�s���Ă��Ȃ�" <?php if ($q3 === '4') { ?>checked="cheked"<?php } ?>>�s���Ă��Ȃ�<br />
            <input type="radio" name="q3" value="�킩��Ȃ�" <?php if ($q3 === '5') { ?>checked="cheked"<?php } ?>>�킩��Ȃ�
        </p>
        
        <br />
        
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q4 ��Ǝ��ɉƉ��փL�Y�����Ȃ����߂̔z���͂���܂������B</strong>
        </div>
        <p>
            <?php if ($err4 === '�G���[') {?><div style="color: #F00; margin-bottom: 10px;">�����ꂩ��I�����Ă��������B</div><br /><?php } ?>
            <input type="radio" name="q4" value="�ƂĂ��ǂ�" <?php if ($q4 === '1') { ?>checked="cheked"<?php } ?>>�ƂĂ��ǂ� <br />
            <input type="radio" name="q4" value="�ǂ�" <?php if ($q4 === '2') { ?>checked="cheked"<?php } ?>>�ǂ�<br />
            <input type="radio" name="q4" value="�ӂ�" <?php if ($q4 === '3') { ?>checked="cheked"<?php } ?>>�ӂ�<br />
            <input type="radio" name="q4" value="����" <?php if ($q4 === '4') { ?>checked="cheked"<?php } ?>>����<br />
            <input type="radio" name="q4" value="�ƂĂ�����" <?php if ($q4 === '5') { ?>checked="cheked"<?php } ?>>�ƂĂ�����
        </p>
        
        <br />
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q5 ��Ǝ��ɉƍ��E���z�ו��փL�Y�����Ȃ����߂̔z���͂���܂������B</strong>
        </div>
        <p>
            <?php if ($err5 === '�G���[') {?><div style="color: #F00; margin-bottom: 10px;">�����ꂩ��I�����Ă��������B</div><br /><?php } ?>
            <input type="radio" name="q5" value="�ƂĂ��ǂ�" <?php if ($q5 === '1') { ?>checked="cheked"<?php } ?>>�ƂĂ��ǂ� <br />
            <input type="radio" name="q5" value="�ǂ�" <?php if ($q5 === '2') { ?>checked="cheked"<?php } ?>>�ǂ�<br />
            <input type="radio" name="q5" value="�ӂ�" <?php if ($q5 === '3') { ?>checked="cheked"<?php } ?>>�ӂ�<br />
            <input type="radio" name="q5" value="����" <?php if ($q5 === '4') { ?>checked="cheked"<?php } ?>>����<br />
            <input type="radio" name="q5" value="�ƂĂ�����" <?php if ($q5 === '5') { ?>checked="cheked"<?php } ?>>�ƂĂ�����
        </p>
        
        <br />
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q6 ��ƌ����E�i���E��Ǝ��Ԃ͂������ł������B</strong>
        </div>
        <p>
            <?php if ($err6 === '�G���[') {?><div style="color: #F00; margin-bottom: 10px;">�����ꂩ��I�����Ă��������B</div><br /><?php } ?>
            <input type="radio" name="q6" value="�ƂĂ��ǂ�" <?php if ($q6 === '1') { ?>checked="cheked"<?php } ?>>�ƂĂ��ǂ� <br />
            <input type="radio" name="q6" value="�ǂ�" <?php if ($q6 === '2') { ?>checked="cheked"<?php } ?>>�ǂ�<br />
            <input type="radio" name="q6" value="�ӂ�" <?php if ($q6 === '3') { ?>checked="cheked"<?php } ?>>�ӂ�<br />
            <input type="radio" name="q6" value="����" <?php if ($q6 === '4') { ?>checked="cheked"<?php } ?>>����<br />
            <input type="radio" name="q6" value="�ƂĂ�����" <?php if ($q6 === '5') { ?>checked="cheked"<?php } ?>>�ƂĂ�����
        </p>
        
        <br />
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q7 ���q�l�̎w���ʂ�̃��C�A�E�g�ɔ������s���܂������B</strong>
        </div>
        <p>
            <?php if ($err7 === '�G���[') {?><div style="color: #F00; margin-bottom: 10px;">�����ꂩ��I�����Ă��������B</div><br /><?php } ?>
            <input type="radio" name="q7" value="�ƂĂ��ǂ�" <?php if ($q7 === '1') { ?>checked="cheked"<?php } ?>>�ƂĂ��ǂ� <br />
            <input type="radio" name="q7" value="�ǂ�" <?php if ($q7 === '2') { ?>checked="cheked"<?php } ?>>�ǂ�<br />
            <input type="radio" name="q7" value="�ӂ�" <?php if ($q7 === '3') { ?>checked="cheked"<?php } ?>>�ӂ�<br />
            <input type="radio" name="q7" value="����" <?php if ($q7 === '4') { ?>checked="cheked"<?php } ?>>����<br />
            <input type="radio" name="q7" value="�ƂĂ�����" <?php if ($q7 === '5') { ?>checked="cheked"<?php } ?>>�ƂĂ�����
        </p>
        
        <br />
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q8 ��ƏI�����Ɏ��ށE��Ɠ���E����p�ޓ����c���������A��܂������B</strong>
        </div>
        <p>
            <?php if ($err8 === '�G���[') {?><div style="color: #F00; margin-bottom: 10px;">�����ꂩ��I�����Ă��������B</div><br /><?php } ?>
            <input type="radio" name="q8" value="�ƂĂ��ǂ�" <?php if ($q8 === '1') { ?>checked="cheked"<?php } ?>>�ƂĂ��ǂ� <br />
            <input type="radio" name="q8" value="�ǂ�" <?php if ($q8 === '2') { ?>checked="cheked"<?php } ?>>�ǂ�<br />
            <input type="radio" name="q8" value="�ӂ�" <?php if ($q8 === '3') { ?>checked="cheked"<?php } ?>>�ӂ�<br />
            <input type="radio" name="q8" value="����" <?php if ($q8 === '4') { ?>checked="cheked"<?php } ?>>����<br />
            <input type="radio" name="q8" value="�ƂĂ�����" <?php if ($q8 === '5') { ?>checked="cheked"<?php } ?>>�ƂĂ�����
        </p>
        
        <br />
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q9 ��ƌ�ɂǂ̂悤�ɉƉ��E�ƍ��̃L�Y�m�F���s���܂������B</strong>
        </div>
        <p>
            <?php if ($err9 === '�G���[') {?><div style="color: #F00; margin-bottom: 10px;">�����ꂩ��I�����Ă��������B</div><br /><?php } ?>
            <input type="radio" name="q9" value="���q�l�ƈꏏ�Ɋm�F" <?php if ($q9 === '1') { ?>checked="cheked"<?php } ?>>���q�l�ƈꏏ�Ɋm�F <br />
            <input type="radio" name="q9" value="���q�l���m�F" <?php if ($q9 === '2') { ?>checked="cheked"<?php } ?>>���q�l���m�F<br />
            <input type="radio" name="q9" value="�z�������m�F" <?php if ($q9 === '3') { ?>checked="cheked"<?php } ?>>�z�������m�F<br />
            <input type="radio" name="q9" value="�s���Ă��Ȃ�" <?php if ($q9 === '4') { ?>checked="cheked"<?php } ?>>�s���Ă��Ȃ�<br />
            <input type="radio" name="q9" value="�킩��Ȃ�" <?php if ($q9 === '5') { ?>checked="cheked"<?php } ?>>�킩��Ȃ�
        </p>
        
        <br />
        
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q10 �r�f���[�r���O�̈��z���܂����p�������Ǝv���܂����H</strong>
        </div>
        <p>
            <?php if ($err10 === '�G���[') {?><div style="color: #F00; margin-bottom: 10px;">�����ꂩ��I�����Ă��������B</div><br /><?php } ?>
            <input type="radio" name="q10" value="�ƂĂ����p������" <?php if ($q10 === '1') { ?>checked="cheked"<?php } ?>>�ƂĂ����p������ <br />
            <input type="radio" name="q10" value="���p������" <?php if ($q10 === '2') { ?>checked="cheked"<?php } ?>>���p������<br />
            <input type="radio" name="q10" value="�ǂ���ł��Ȃ�" <?php if ($q10 === '3') { ?>checked="cheked"<?php } ?>>�ǂ���ł��Ȃ�<br />
            <input type="radio" name="q10" value="���܂藘�p�������Ȃ�" <?php if ($q10 === '4') { ?>checked="cheked"<?php } ?>>���܂藘�p�������Ȃ�<br />
            <input type="radio" name="q10" value="�܂��������p�������Ȃ�" <?php if ($q10 === '5') { ?>checked="cheked"<?php } ?>>�܂��������p�������Ȃ�
        </p>
        
        <br />
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q11 �{���̍�ƂɊւ��Ă��ӌ����������܂����炲�L����������</strong>
        </div>
        <p><font color="red">�s���ӌ����t�����q�l�̂��ӌ��́A���޽���ٌ���ȊO�̖ړI�ł͎g�p�������܂���</font> </p>
        <p>
            <?php if ($err11 === '�G���[') {?><div style="color: #F00; margin-bottom: 10px;">���S�p500�����A���p1000�����𒴂��Ă��܂��B</div><?php } ?>
            <?php if ($err11 === '�G���[1') { ?><div style="color: #F00; margin-bottom: 10px;">���p�J�i�A�������͓��ꕶ�����܂܂�Ă��܂��B</div><?php } ?>
            <textarea name="q11" rows="5" cols="50" maxlength="1000"><?php if (isset($q11)) { echo $q11; } ?></textarea>
            <br />���S�p500�����A���p1000�����܂łł��肢�������܂��B
            <br />���G�����͓��͂��Ȃ��ł��������B
        </p>
        
        <br />
        
        <div style="border:3px solid #78EAFE; background:#D3F8FF; padding: 5px;">
        <strong>Q12 �sQ11�t�ɂ��L���������������e�ɂ��܂��āA���q�l�ւ̂��Ή��������߂ɂȂ�܂����H
                <br />�i�u�͂��v�Ƃ��񓚂����������ꍇ�́A���̘A���旓�̓��͂����肢�������܂��j</strong>
        </div>
        <p>
            <?php if ($err12a === '�G���[') {?><div style="color: #F00;">�����ꂩ��I�����Ă��������B</div><br /><?php } ?>
            <input type="radio" name="q12a" value="�͂�" <?php if ($q12a === '1') { ?>checked="cheked"<?php } ?>>�͂�
            <input type="radio" name="q12a" value="������" <?php if ($q12a === '2') { ?>checked="cheked"<?php } ?>>������<br />
            
            <?php if ($err12b === '�G���[1') {?><div style="color: #F00; ">���͂��Ă��������B</div><br /><?php } ?>
            <?php if ($err12b === '�G���[2') {?><div style="color: #F00; ">�������I�[�o�[�ł��B</div><br /><?php } ?>
            <?php if ($err12b === '�G���[3') {?><div style="color: #F00; ">���p�J�i�A�������͓��ꕶ�����܂܂�Ă��܂��B</div><br /><?php } ?>
            <span>���O </span><input type="text" name="q12b" class="" value="<?php if (isset($q12b)) { echo $q12b; } ?>"><br />
            <?php if ($err12c === '�G���[1') {?><div style="color: #F00;">���͂��Ă��������B</div><br /><?php } ?>
            <?php if ($err12c === '�G���[2') {?><div style="color: #F00; ">�������I�[�o�[�ł��B</div><br /><?php } ?>
            <?php if ($err12c === '�G���[3') {?><div style="color: #F00; ">���p�J�i�A�������͓��ꕶ�����܂܂�Ă��܂��B</div><br /><?php } ?>
            <span>�d�b </span><input type="text" name="q12c" class="" value="<?php if (isset($q12c)) { echo $q12c; } ?>"><br />
            <?php if ($err12d === '�G���[1') {?><div style="color: #F00; ">���͂��Ă��������B</div><br /><?php } ?>
            <?php if ($err12d === '�G���[2') {?><div style="color: #F00; ">�������I�[�o�[�ł��B</div><br /><?php } ?>
            <?php if ($err12d === '�G���[3') {?><div style="color: #F00; ">���p�J�i�A�������͓��ꕶ�����܂܂�Ă��܂��B</div><br /><?php } ?>
            <span>�Z�� </span><textarea class="txtfiled" name="q12d"><?php if (isset($q12d)) { echo $q12d; } ?></textarea>
        </p>
        
        <input type="hidden" name="param" value="<?php echo $param; ?>">
        
        
        <div style="margin-bottom: 5px;">
        <input type="submit" value="�m�F">
        </div>
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
