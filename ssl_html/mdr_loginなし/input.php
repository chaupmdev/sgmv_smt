<?php
require_once(dirname(__FILE__) . '/../../lib/component/auth_event.php');

$param = filter_input(INPUT_GET, 'param');

//$now = new DateTime();
//$moushikomiKaishiDt = new DateTime("2020/11/28 10:00:00");
//if ($now < $moushikomiKaishiDt) {
//    $title = urlencode("���J�J�n�O�ł�");
//    $message = urlencode("�݂ǂ��̐\����t�́A2020�N11��28�� 10:00 ����J�n���܂��B");
//    header("Location: /mdr/error?t={$title}&m={$message}");
//    exit;
//}


// ����
 $now = new DateTime();
// // ���J��
 $releaseDate = new DateTime("2020/11/28 09:59:59");

// // �x�[�V�b�N�F��
 if($now < $releaseDate){
 	switch (true) {
 		case !isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']):
 		case $_SERVER['PHP_AUTH_USER'] !== 'sgmv':
 		case $_SERVER['PHP_AUTH_PW']   !== 'sagawa':
 		    header('WWW-Authenticate: Basic realm="Enter username and password."');
 		    header('Content-Type: text/plain; charset=utf-8');
 		    die('���̃y�[�W������ɂ̓��O�C�����K�v�ł�');
 	}

 	header('Content-Type: text/html; charset=utf-8');
 }

if(empty($param)) {
    include_once dirname(__FILE__) . '/input1.php';
} else {
    include_once dirname(__FILE__) . '/input2.php';
}