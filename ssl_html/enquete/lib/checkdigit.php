<?php

include dirname (__FILE__) . '/DB.php';


if(isset($_GET["param"])){
    $param = $_GET["param"];
}else{
    $param = $_POST["param"];
}

if(isset($_GET["kbn"])){
    $kbn = $_GET["kbn"];
}else{
    $kbn = $_POST["kbn"];
}

$len = 0;
$checkd = 0;
$getd = 0;
$rest = 0;


if (!isset($param) || $param === '') {
    header("Location: err/error.php");
    exit;
}

//echo "param:" .$param .":kbn:".$kbn.":ptp:".$ptp;


$len = strlen($param);

//echo "桁数:" .$len;

if ($kbn == 0) {
    if ($len >= 20) {
        header("Location: err/error.php");
        exit;
    }
} elseif ($kbn == 1) {
    if ($len >= 12) {
        header("Location: err/error.php");
        exit;
    }
}



$rest = substr($param, 0, -1); //作業依頼番号,受付番号 チェックデジットを外す。
$checkd = substr($param, -1); //チェックデジット

$hzero = abs($rest); //先頭の0を取る
//echo "作業依頼番号,受付番号:".$rest.":チェックデジット:" .$checkd;



if ($kbn == 0) {//設置
    $stmt = $con->prepare('SELECT id FROM enquete WHERE sagyoirai_no = :rest limit 1');
} elseif ($kbn == 1) {//引越
    $stmt = $con->prepare('SELECT id FROM enquete WHERE uketsuke_no  = :rest limit 1');
}
$stmt->bindValue(':rest', $rest);
$flag = $stmt->execute();

//echo "フラグ:".$flag;

if ($flag) {
    while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $trzid = $data['id'];
    }

    if (isset($trzid)) {
        header("Location: err/error2.php"); //アンケートは、一度のみ回答可能です
        exit;
    }
} else {
    header("Location: err/error3.php"); //データベース接続エラー
    exit;
}


$check = $hzero - (int) ($rest / 7) * 7;

if ($checkd != $check) {
    header("Location: err/error.php");
    exit;
}
?>