<?php
echo 'resend-start=================<br/>';



define('DB_DRIVER', 'pgsql');
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_LOGIN', 'sgmvsp');
define('DB_PASSWORD', 'PA9j97GF');
define('DB_DATABASE', 'moving_db');
define('DB_ENCODING', 'UTF8');

$driver   = DB_DRIVER;
$host     = DB_HOST;
$port     = DB_PORT;
$login    = DB_LOGIN;
$password = DB_PASSWORD;
$database = DB_DATABASE;
$encoding = DB_ENCODING;

$dsn = $driver.':host='.$host.';port='.$port.';dbname='.$database;
$con = new PDO($dsn, $login, $password);
$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


if (empty($con)) {
    throw new Exception('DB Connection failed![dsn='.$dsn.',login='.$login.',password='.$password.']');
}

//$dataAcObj = $con->prepare('SELECT * FROM mail_send order by id asc');
$dataAcObj = $con->prepare('SELECT * FROM mail_send_dmy order by id asc');
$dataAcObj->execute();
$dataList = array();

while ($data = $dataAcObj->fetch(PDO::FETCH_ASSOC)) {
  $dataList[] = $data;
}

//var_dump($dataList);


require_once dirname(__FILE__) . '/../lib/Lib.php';
//Sgmov_Lib::useAllComponents ( TRUE );
Sgmov_Lib::useView('evp/ReSendMailToiawase');
$viewEvp = new Sgmov_View_Evp_ReSendMailToiawase();

Sgmov_Lib::useView('eve/ReSendMailToiawase');
$viewEve = new Sgmov_View_Eve_ReSendMailToiawase();

$countSend = 1;
$SEND_COUNT = 50;
$SLEEP_SECOND = 30;
//Sgmov_Component_Log::info ('mail_toiban_resend.php 送信開始 =========================');
foreach ($dataList as $key => $val) {
    $_GET['comiket_id'] = $val['moushikomi_id'];
    //Sgmov_Component_Log::info ('comiket_id = '.$comiket_id);
    if ($val['event_key'] == 'evp') {
        //Sgmov_Component_Log::info ("{$val['moushikomi_id']}:evp")
        $viewEvp->execute();
    } else if ($val['event_key'] == 'eve') {
        //Sgmov_Component_Log::info ("{$val['moushikomi_id']}:eve")
        $viewEve->execute($val['moushikomi_id']);
    }
    //Sgmov_Component_Log::info ("send-count：{$countSend}")
    if ($SEND_COUNT <= $countSend) {
        echo "一旦休止：{$SLEEP_SECOND}<br/>";
        sleep($SLEEP_SECOND);
        $countSend = 1;
    } else {
        $countSend++;
    }
}
//Sgmov_Component_Log::info ('mail_toiban_resend.php 送信終了 =========================');
echo 'resend-end =================<br/>';

/*


*/