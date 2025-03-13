<?php
// Basic認証
require_once dirname(__FILE__) . '/../../lib/component/auth_twi.php';

$param = filter_input(INPUT_GET, 'param');

if(empty($param)) {
    include_once dirname(__FILE__) . '/input1.php';
} else {
    include_once dirname(__FILE__) . '/input2.php';
}