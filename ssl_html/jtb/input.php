<?php

$param = filter_input(INPUT_GET, 'param');
// Basic�F��
require_once dirname(__FILE__) . '/../../lib/component/auth_jtb.php';

if(empty($param)) {
    include_once dirname(__FILE__) . '/input1.php';
} else {
    include_once dirname(__FILE__) . '/input2.php';
}