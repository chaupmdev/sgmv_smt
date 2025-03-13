<?php

$param = isset($_POST['param']) ? $_POST['param'] : "";
$kbn = isset($_POST['kbn']) ? $_POST['kbn'] : "";
$ptp = isset($_POST['ptp']) ? $_POST['ptp'] : "";

$param = substr($param, 0, -1); //作業依頼番号,受付番号 チェックデジットを外す。

if ($kbn == 0) {//設置
    $stmt = $con->prepare('SELECT id FROM enquete WHERE sagyoirai_no = :param limit 1');
} elseif ($kbn == 1) {//引越
    $stmt = $con->prepare('SELECT id FROM enquete WHERE uketsuke_no  = :param limit 1');
}
$stmt->bindValue(':param', $param);
$flag = $stmt->execute();


if ($flag) {
    while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $trzid = $data['id'];
    }

    if (isset($trzid)) {
        if ($ptp == 0) {
            header("Location: ../s/err/error2.php");
        } elseif ($ptp == 1) {
            header("Location: ../i/err/error2.php");
        }
        exit;
    }
} else {
    if ($ptp == 0) {
        header("Location: ../s/err/error3.php");
    } elseif ($ptp == 1) {
        header("Location: ../i/err/error.php");
    }
    exit;
}
?>