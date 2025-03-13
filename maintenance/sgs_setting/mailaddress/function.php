<?php
//DB接続
function PgDBConnect($host, $port, $dbname, $user, $passwd)
{
    $conn_string = "host=" . $host . " port=" . $port . " dbname=" . $dbname . " user=" . $user . " password=" . $passwd;
    $con = @pg_connect($conn_string);
    if (!$con) {
        echo "DB接続エラー<br>DBに接続できません。";
    }
    return $con;
}

//DB切断
function PgDBClose($con)
{
    $res = @pg_close($con);
    return $res;
}

//メールアドレス取得SQL発行
function GetMailsAndCenters($form_kbn)
{
    $query  = 'SELECT MAIL.id,MAIL.center_id,CENTER.name,MAIL.mail,MAIL.set_kbn';
	$query .= '    FROM center_mails AS MAIL';
	$query .= '    LEFT OUTER JOIN  centers AS CENTER ON MAIL.center_id = CENTER.id';
    $query .= '    WHERE MAIL.form_division='.$form_kbn;
    $query .= '    ORDER BY center_id,id;';

    return $query;
}

//クエリ実行
function ExecuteQuery($query, $db)
{
    $result = pg_query($db, $query);
    return $result;
}

//設定取得
function GetKubunnameById($set_kbn)
{
    switch ($set_kbn) {
    case 1:
        $name = "To";
        break;
    case 2:
        $name = "Cc";
        break;
    case 3:
        $name = "Bcc";
        break;
    default:
        break;
    }
    return $name;
}
?>
