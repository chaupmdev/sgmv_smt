<?php
header("Content-type: application/json; charset=UTF-8");
    echo json_encode($_POST["toiban"]);
    echo json_encode($_POST["kakaku"]);
    exit;
?>