<?php

// Basic認証
 require_once dirname(__FILE__) . '/../../lib/component/auth_buppan.php';

$param = filter_input(INPUT_GET, 'param');

$splitParam = explode("/", $param);

if(count($splitParam) > 0){
    // 当日物販
	if(isset($splitParam[1]) && $splitParam[1] == "2"){
		include_once dirname(__FILE__) . '/active_shohin.php';
    // 事前物販
	}else{
		include_once dirname(__FILE__) . '/input1.php';
	}
}