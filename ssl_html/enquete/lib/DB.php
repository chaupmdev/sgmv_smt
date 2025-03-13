<?php

define('DB_DRIVER', 'pgsql');
#define('DB_HOST', '172.16.101.18');
define('DB_HOST', '10.14.10.130');
define('DB_PORT', '5432');
define('DB_LOGIN', 'webscs');
define('DB_PASSWORD', 'webscs');
#define('DB_DATABASE', 'sgmv_db64');
define('DB_DATABASE', 'moving_db');
define('DB_ENCODING', 'UTF8');

$driver   = DB_DRIVER;
$host     = DB_HOST;
$port     = DB_PORT;
$login    = DB_LOGIN;
$password = DB_PASSWORD;
$database = DB_DATABASE;
$encoding = DB_ENCODING;

$dsn = $driver . ':host=' . $host . ';port=' . $port . ';dbname=' . $database;

$con = new PDO($dsn, $login, $password);

$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


if (empty($con)) {
    throw new Exception('DB Connection failed![dsn=' . $dsn . ',login=' . $login . ',password=' . $password . ']');
}