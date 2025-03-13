<?php

$uketsuke_no = $_POST['uketsuke_no'];
$toiawase_no = $_POST['toiawase_no'];
$kessai_meisai_id = $_POST['kessai_meisai_id'];
$veritrans_kessai_id = $_POST['veritrans_kessai_id'];

$result = ["result" => "success", "error" => ""];

header("Access-Control-Allow-Origin: *");
echo json_encode($result);
