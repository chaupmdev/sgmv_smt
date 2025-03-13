<?php
/**
 * 日本語/英語を切り替える
 * @package    ssl_html
 * @subpackage EVE
 * @author     Juj-Yamagami(SP)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
session_start();
 $lang = filter_input(INPUT_GET, 'param');
$_SESSION["common.web_lang"] = $lang;
$_SESSION["FORMS"] = null;
header('Location: /mlk/input?is_ch_lang=1');
//header('Location: /mlk/input');

exit;