<?php
$param = filter_input(INPUT_GET, 'param');

include_once dirname(__FILE__) . '/input1.php';

// if (!@empty($param)) {
// 	if (strlen($param) > 3) {
//     	include_once dirname(__FILE__) . '/input2.php';
// 	} else {
//     	include_once dirname(__FILE__) . '/input1.php';
// 	}
// }