<?php

/**
 * 各イベント共通の管理画面（予定）
 *
 * @author Honjo Masanori
 */
// ベーシック認証
require_once('../lib/component/auth.php');
//環境切替
// $environment_location=$_SERVER['REMOTE_ADDR'];//IPアドレス判定
$environment_location = 'Develop'; //手動開発環境
// $environment_location='Release';//手動本番環境
//****************************************
//     DB接続
//****************************************
define('DB_DRIVER', 'pgsql');
define('DB_PORT', '5432');
define('DB_DATABASE', 'moving_db');
define('DB_ENCODING', 'UTF8');
switch ($environment_location) {
    case '172.16.1.5': //テスト環境
    case 'Develop': //テスト環境
        define('DB_HOST', '172.16.1.5');
        define('DB_LOGIN', 'sgmvsp');
        define('DB_PASSWORD', 'PA9j97GF');
        break;
    case '10.60.224.165': //本番環境
    case 'Release': //本番環境
        define('DB_HOST', '10.60.61.133');
        define('DB_LOGIN', 'postgres');
        define('DB_PASSWORD', '');
        break;
    default: //ipが取得できない場合はテスト環境に接続してみる。
        define('DB_HOST', '172.16.1.5');
        define('DB_LOGIN', 'sgmvsp');
        define('DB_PASSWORD', 'PA9j97GF');
        break;
}
/**
 * ボタン表示設定
 *
 * ※表示しない場合、イベント識別子を設定する。(;セミコロン区切り)
 * 数量更新ボタン：$quantity_change_disable
 * キャンセルボタン(送り状発行済み自動判定：非表示)：$cancel_btn_disable
 * 貼付表ダウンロードボタン（自動判定：復路[搬入]のみ表示）：$affix_btn_disable
 * 再登録ボタン ：$reentry_btn_disable
 *
 * 各ボタンパラメータにセミコロン区切りでイベント識別子を追記
 *
 */
//[NEW ENERGY ZERO:710]
$quantity_change_disable = 'nen';
$cancel_btn_disable = '';
$affix_btn_disable = 'nen';
$reentry_btn_disable = 'nen';

// $quantity_change_disable_='style="display: none;"';
// $cancel_btn_disable_='style="display: none;"';
// $affix_btn_disable_='style="display: none;"';
// $reentry_btn_disable_='style="display: none;"';
$quantity_change_disable_ = '';
$cancel_btn_disable_ = '';
$affix_btn_disable_ = '';
$reentry_btn_disable_ = '';


$driver   = DB_DRIVER;
$host     = DB_HOST;
$port     = DB_PORT;
$login    = DB_LOGIN;
$password = DB_PASSWORD;
$database = DB_DATABASE;
$encoding = DB_ENCODING;

$dsn = $driver . ':host=' . $host . ';port=' . $port . ';dbname=' . $database . ';';
try {
    $con = new PDO($dsn, $login, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "PDO connection object created";
} catch (PDOException $e) {
    echo $e->getMessage();
    exit();
}

// if (empty($con)) {
//         throw new Exception('DB Connection failed![dsn='.$dsn.',login='.$login.',password='.$password.']');
// }

//****************************************
//     ページャー: 変数
//****************************************

//最新の20件を表示
$linenum = "100";

//ページNo
$pageNum = 0;

//最大行数
$maxRows = 100;

//サーバのホスト名取得
$currentPage = $_SERVER["PHP_SELF"];
//****************************************
//     2ページ目以降の処理
//***************************************
if (isset($_GET['pageNum'])) {
    //Startページ数 計算
    $pageNum = $_GET['pageNum'];
    //Endページ数 計算
    $EndRow = $linenum * ($_GET['pageNum'] + 1);
} else {
    //Endページ数 計算
    $EndRow = $linenum;
}
//SQLリミット計算(スタート)
$startRow = $pageNum * $maxRows;
//SQL
//「comiket_detail」no_chg_flg チェック => "1" の場合はキャンセル・サイズ変更できない(搬出のみ)
function pr($arg)
{
    print '<pre>' . "\n";
    print_r($arg);
    print '</pre>' . "\n";
}
$query = <<<__LONG_STRRING__
            select
                c.id as 申込番号,
                c.delivery_slip_no as 問合せ番号,
                cast(c.created as date) as 申込日,
                case
                    cb."type" when '1' then '往路'
                    when '2' then '復路'
                    when '3' then 'ミルクラン'
                    when '4' then '手荷物'
                    when '5' then '物販'
                    when '6' then '通常商品'
                    when '7' then '顧客請求商品(D24)'
                    when '8' then 'オプション'
                    when '9' then 'リサイクル'
                    else ''
                end as 往復,
                SUM(cb.num) as 個数合計,
                to_char(c.id,'FM0000000000') as 申込番号10桁,
                c.tel as 電話番号,
                c.mail as メール,
                e."name" as イベント名,
                e2."name" as イベントサブ名,
                c.event_id as イベント番号,
                e2.cd as イベントサブ番号,
                case
                    c.div when 1 then '個人'
                    when 2 then '法人'
                    else '設置'
                end as 識別,
                c.office_name as 法人名,
                concat(c.personal_name_sei, c.personal_name_mei) as 氏名,
                case
                    c.del_flg when 2 then 'キャンセル'
                    else '-' end as キャンセル,
                    cd.no_chg_flg as キャンセル不可
            from
                public.comiket c
            inner join public.comiket_box cb on
                c.id = cb.comiket_id
            inner join public.event e on
                c.event_id = e.id
            inner join public.eventsub e2 on
                c.eventsub_id = e2.id
            inner join public.comiket_detail cd on
                c.id = cd.comiket_id
            where
                not c.delivery_slip_no is null
                and c.delivery_slip_no <> ''
__LONG_STRRING__;
if (@$_POST['cancel'] != null) {
    $_SESSION['post_cancel'] = $_POST['cancel'];
}
switch (@$_SESSION['post_cancel']) {
    default:
    case 0:
        $where = " and ( c.del_flg != 2 or c.del_flg is null) ";
        $query .= $where;
        break;
    case 1:
        break;
    case 2:
        $where = " and c.del_flg = 2 ";
        $query .= $where;
        break;
}
//サブミット判定してページ切り替えの場合は変数保持、サブミットの場合は変数更新
if (@$_POST['submit_push'] == "true") {
    $_SESSION['post_input1'] = $_POST['input1'];
}
if (@$_SESSION['post_input1'] != "") {
    $str = str_replace("　", " ", $_SESSION['post_input1']);
    $search_key = explode(" ", $str);
    $query .= " and (";
    $cnt = 0;
    $union = "";
    foreach ($search_key as $key) {
        if ($cnt == 0) {
            $union = "";
        } else {
            $union = " or ";
        }
        $query .= $union;
        $query .= <<<__LONG_STRRING__
                        concat(to_char(c.id,'FM0000000000'),
                        ' ',
                        c.delivery_slip_no,
                        ' ',
                        cast(c.created as date),
                        c.tel,
                        concat(c.office_name, ' ', c.personal_name_sei, c.personal_name_mei),
                        case
                        cb."type" when '1' then '往路'
                        when '2' then '復路'
                        when '3' then 'ミルクラン'
                        when '4' then '手荷物'
                        when '5' then '物販'
                        when '6' then '通常商品'
                        when '7' then '顧客請求商品(D24)'
                        when '8' then 'オプション'
                        when '9' then 'リサイクル'
                        else '' end
                        )
__LONG_STRRING__;
        $where = sprintf(" ilike '%s'", "%" . $key . "%");
        $query .= $where;
        $cnt++;
    }
    $query .= " ) ";
}

if (@$_POST['submit_push'] == "true") {
    $_SESSION['post_from'] = $_POST['from'];
}
if (@$_SESSION['post_from'] != "") {
    $where = sprintf(" and  cast(c.created as date) >= '%s'", "%" . $_SESSION['post_from'] . "%");
    $query .= $where;
}

if (@$_POST['submit_push'] == "true") {
    $_SESSION['post_to'] = $_POST['to'];
}
if (@$_SESSION['post_to'] != "") {
    $where = sprintf(" and  cast(c.created as date) <= '%s'", "%" . $_SESSION['post_to'] . "%");
    $query .= $where;
}

if (@$_POST['submit_push'] == "true") {
    $_SESSION['post_event'] = $_POST['event'];
}
//NEW ENERGY ZERO 限定
// $_SESSION['post_event'] = 710;
if (@$_SESSION['post_event'] != "") {
    $where = sprintf(" and  c.event_id = %d ", $_SESSION['post_event']);
    $query .= $where;
}

if (@$_POST["eventsub"] != "") {
    $where = sprintf(" and  e2.cd = '%s' ", $_POST["eventsub"]);
    $query .= $where;
}

if (@$_POST['submit_push'] == "true") {
    $_SESSION['post_type'] = $_POST['delivery_type'];
}
if (@$_SESSION['post_type'] != "") {
    if ($_SESSION['post_type'] != 0) {
        $where = sprintf(" and  cb.type = '%s' ", $_SESSION['post_type']);
        $query .= $where;
    }
}
$query .= <<<__LONG_STRRING__
                group by
                    c.id,
                    c.delivery_slip_no,
                    c.created,
                    cb.type,
                    c.tel,
                    c.mail,
                    c.div,
                    c.event_id,
                    c.eventsub_id,
                    c.office_name,
                    c.personal_name_sei,
                    c.personal_name_mei,
                    c.del_flg,
                    e.name,
                    e2.name,
                    e2.cd,
                    cd.no_chg_flg
                order by
                    c.created desc
__LONG_STRRING__;
//LIMIT句:posgre（$startRowから$maxRowsまで）
$query_limit = sprintf("%s LIMIT %d OFFSET %d", $query, $maxRows, $startRow);
//var_dump($query_limit);
//クエリー送信
$dataAcObj = $con->prepare($query_limit);
$dataAcObj->execute();
$result = array();
while ($data = $dataAcObj->fetch(PDO::FETCH_ASSOC)) {
    $result[] = $data;
}

//結果における行の数を得る
$totalRows = count($result);
//****************************************
//     ページ数 計算
//****************************************

//Endページ数 計算(例外処理)
if (count($result) != $linenum) {
    $EndRow = count($result) + $startRow;
}

if (isset($_GET['totalRows'])) {
    //既に計算済み
    $totalRows = $_GET['totalRows'];
} else {
    //LMITなしでクエリー送信
    $all = $con->query($query);
    $totalRows = 0;
    foreach ($con->query($query) as $row) {
        $totalRows++;
    }
    //結果セットから行の数を取得
    // $totalRows = count($all);
}

//引数で指定した数値から、次に大きい整数を返す
$totalPages = ceil($totalRows / $maxRows) - 1;
$num_hit = $totalPages;
$queryString = "";


//****************************************
//     URLパラメータ(GET)を取得
//****************************************

if (!empty($_SERVER['QUERY_STRING'])) {
    //"&"で分割
    $params = explode("&", $_SERVER['QUERY_STRING']);
    $newParams = array();

    //foreach( 配列 as $value )
    foreach ($params as $param) {
        //stristr(処理対象の文字列,検索する文字列)
        if (stristr($param, "pageNum") == false && stristr($param, "totalRows") == false) {
            array_push($newParams, $param);
        }
    }
    if (count($newParams) != 0) {
        $queryString = "&" . implode("&", $newParams);
    }
}
$queryString = sprintf("&totalRows=%d%s", $totalRows, $queryString);

if (@$_POST["action"] != "") {
    if ($_POST["action"] == "get_eventsub") {
        $query = <<<__LONG_STRRING__
        select distinct
            e.event_id,
            e.name
        from
            public.eventsub e
__LONG_STRRING__;
        $where_event = sprintf(" where e.event_id = %d", $_POST["sub_list"]);
        $query .= $where_event;
        $query .= <<<__LONG_STRRING__
            order by
                e.event_id
__LONG_STRRING__;
        $EventsubdataObj = $con->prepare($query);
        $EventsubdataObj->execute();
        $eventsub = array();
        while ($data = $EventsubdataObj->fetch(PDO::FETCH_ASSOC)) {
            $eventsub[] = $data;
        }
        $j = json_encode($eventsub);
        $eventsub_sel = "";
        $eventsub_sel .= "<option value=''>--</option>";
        foreach ($eventsub as $key => $value) {
            $eventsub_sel .= "<option value='" . $eventsub[$key]['event_id'] . "'>" . $eventsub[$key]['name'] . "</option>";
        }
        echo json_encode($eventsub_sel);
        die;
    } else if ($_POST["action"] == "get_checkdigit") {
        $comiketIdCheckD = getChkD(sprintf("%010d", $_POST['eventid']));
        echo json_encode($comiketIdCheckD);
        die;
    }
}
/**
 * ID可変チェックデジット
 */
function getChkD($param)
{
    // 顧客コードを配列化
    $param2 = str_split($param);


    // 掛け算数値配列（固定らしいのでベタ書き）
    $intCheck = array(
        0 => 4,
        1 => 3,
        2 => 2,
        3 => 9,
        4 => 8,
        5 => 7,
        6 => 6,
        7 => 5,
        8 => 4,
        9 => 3,
    );

    $total = 0;
    for ($i = 0; $i < count($intCheck); $i++) {
        $total += $param2[$i] * $intCheck[$i];
    }

    return $total;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0" />
    <meta name="Keywords" content="佐川急便,ＳＧムービング,sgmoving,無料見積り,お引越し,設置,法人,家具輸送,家電輸送,精密機器輸送,設置配送" />
    <meta name="Description" content="サイトマップのご案内です。" />
    <title>申込管理画面│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
    <link rel="shortcut icon" href="/misc/favicon.ico" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/sitemap.css" rel="stylesheet" type="text/css" />
    <link href="./css/dashboard.css" rel="stylesheet" type="text/css" />
    <!--[if lt IE 9]>
	<script charset="UTF-8" type="text/javascript" src="/js/jquery-1.12.0.min.js"></script>
	<script charset="UTF-8" type="text/javascript" src="/js/lodash-compat.min.js"></script>
	<![endif]-->
    <!--[if gte IE 9]><!-->
    <script charset="UTF-8" type="text/javascript" src="/js/jquery-2.2.0.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/lodash.min.js"></script>
    <!--<![endif]-->
    <script charset="UTF-8" type="text/javascript" src="/js/common.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/personal/js/anchor.js"></script>
    <script src="/js/ga.js" type="text/javascript"></script>
    <script>
        // jQueryでselect要素の特定のoptionを隠したい！
        $(function() {
            $("#event").change(function() {
                let event_sel = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "./sgmv_dashboard.php",
                    data: {
                        'action': 'get_eventsub',
                        'sub_list': event_sel
                    },
                    dataType: "json",
                }).done(function(data) {
                    $('select#eventsub option').remove();
                    $("#eventsub").append(data);
                }).fail(function(XMLHttpRequest, textStatus, error) {
                    alert(error);
                });
            });
        });
        //一つ上の階層のURL（rep)
        let url = location.href;
        let ary = url.split('/');
        let str = ary[ary.length - 1];
        let rep = url.replace(str, '');
        /**
            チェックデジット風暗号化処理
            Risultの桁数は可変 1桁～
         */
        function get_checkdg(num, sel) {
            let ur = "";
            switch (sel) {
                case 1:
                    ur = "size_change/";
                    break;
                case 2:
                    ur = "cancel/";
                    break;
                case 3:
                    ur = "paste_tag/";
                    break;
                case 4:
                    ur = "input/";
                    break;
            }
            $.ajax({
                type: "POST",
                url: "./sgmv_dashboard.php",
                data: {
                    'action': 'get_checkdigit',
                    'eventid': num
                },
                dataType: "json",
            }).done(function(data) {
                window.location.href = rep + ur + num + data;
            }).fail(function(XMLHttpRequest, textStatus, error) {
                alert(error);
            });
        }

        function change_num(num) {
            //チェックデジットA
            get_checkdg(num, 1);
        }

        function cancel_order(num) {
            //チェックデジットA
            get_checkdg(num, 2);
        }

        function download_tag(num) {
            // get_checkdg(num,3);

            //チェックデジットB
            ur = "paste_tag/";
            let sp = num % 7;
            window.location.href = rep + ur + num + sp;
        }

        function re_order(num) {
            //チェックデジットA
            get_checkdg(num, 4);
        }

        function form_clear() {
            document.search_form.reset();
        }

        function form_submit() {
            let target = document.getElementById("search_form");
            target.method = "post";
            $('<input>').attr({
                'type': 'hidden',
                'name': 'submit_push',
                'value': true
            }).appendTo(target);
            target.submit();
        }
    </script>

</head>

<body>
    <?php
    $gnavSettings = "";
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/header.php';
    ?>
    <div id="breadcrumb">
        <ul class="wrap">
            <li><a href="/">ホーム</a></li>
            <li class="current">ダッシュボード</li>
        </ul>
    </div>

    <div id="main">
        <div class="wrap clearfix">
            <h1 class="page_title">Event Dashboard</h1>
        </div>
        <!--ページャー Start-->
        <table class="table_page">
            <tr>
                <td nowrap>
                    <form id="search_form" name="search_form" action="sgmv_dashboard.php" onsubmit="return false;" method="post">
                        <!-- <input type="button" name="_clear" class="btn btn--orange btn--radius" style="float: right;" onclick="form_clear();" value="クリア"> -->
                        <div style="word-wrap: break-word;">申込日範囲 自 <input type="date" name="from" value="<?= @$_SESSION['post_from'] ?>"> ～至 <input type="date" name="to" value="<?= @$_SESSION['post_to'] ?>">
                            イベント <select name='event' id="event">
                                <option selected>--</option>
                                <?php
                                $res = $con->prepare("select distinct e.id,e.name,e.shikibetsushi from event e order by e.id;");
                                //NEW ENERGY ZERO
                                // $res = $con->prepare("select distinct e.id,e.name from event e where e.id=710 order by e.id;");
                                $res->execute();
                                $event = [];
                                $distinction_cd = [];
                                while ($data = $res->fetch(PDO::FETCH_ASSOC)) {
                                    $event[] = $data;
                                }
                                foreach ($event as $key => $val) {
                                    $distinction_cd += [$event[$key]['id'] => $event[$key]['name']];
                                    if ($_SESSION['post_event'] == $event[$key]['id']) {
                                        //NEW ENERGY ZERO
                                        // { if($event[$key]['id']==710){
                                ?>
                                        <option value="<?= $event[$key]['id'] ?>" selected><?= $event[$key]['name'] ?></option>
                                    <?php } else { ?>
                                        <option value="<?= $event[$key]['id'] ?>"><?= $event[$key]['name'] ?></option>
                                    <?php } ?>
                                <?php } ?>
                                <? var_dump($distinction_cd); ?>
                            </select>
                            <select name='eventsub' id="eventsub"></select>
                        </div>
                        <?php if (!empty($_SESSION['post_type'])) {
                            $type1 = "";
                            $type2 = "";
                            $type3 = "";
                            $type4 = "";
                            $type5 = "";
                            switch ($_SESSION['post_type']) {
                                default:
                                case 0:
                                    $type1 = "selected";
                                    break;
                                case 1:
                                    $type2 = "selected";
                                    break;
                                case 2:
                                    $type3 = "selected";
                                    break;
                                case 4:
                                    $type4 = "selected";
                                    break;
                                case 5:
                                    $type5 = "selected";
                                    break;
                            }
                        } ?>
                        <p> 輸送<select name='delivery_type' id="delivery_type">
                                <option value="0" <?= $type1; ?>>--</option>
                                <option value="1" <?= $type2; ?>>往路</option>
                                <option value="2" <?= $type3; ?>>復路</option>
                                <option value="4" <?= $type4; ?>>手荷物</option>
                                <option value="5" <?= $type5; ?>>物販</option>
                            </select>
                            <?php if (!empty($_SESSION['post_cancel'])) {
                                $can1 = "";
                                $can2 = "";
                                $can3 = "";
                                switch ($_SESSION['post_cancel']) {
                                    case 1:
                                        $can2 = "selected";
                                        break;
                                    case 2:
                                        $can3 = "selected";
                                        break;
                                    default:
                                        $can1 = "selected";
                                }
                            } ?>
                            キャンセル <select name='cancel' id="cancel">
                                <option value="0" <?= $can1; ?>>含めない</option>
                                <option value="1" <?= $can2; ?>>全て</option>
                                <option value="2" <?= $can3; ?>>キャンセル</option>
                            </select>
                            <!-- 任意の<input>要素＝入力欄などを用意する -->
                            複数キーワード検索 </del><input type="text" name="input1" style="width:350px;" id="sbox" value="<?= @$_SESSION['post_input1'] ?>" placeholder="申込番号/問合せ番号/氏名/電話番号"></p>
                        <!-- 送信ボタンを用意する -->
                        <input type="button" name="_submit" onclick="form_submit();" class="btn btn--orange btn--radius" style="float: right;" value="絞込">
                    </form>
                </td>
            </tr>
        </table>
        <?php if ($totalRows > 0) { //0件の場合非表示
        ?>
            <table class="table_page">
                <tr>
                    <td width="55%" align="left" nowrap>
                        <strong class="font-size14 red"><?= $totalRows ?></strong>件中／<?= $startRow + 1 ?>～<?= $EndRow ?> 件表示&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td width="15%" align="right" nowrap>
                        <?php if ($pageNum > 0) { ?>
                            <a href="<?php printf("%s?pageNum=%d%s", $currentPage, max(0, $pageNum - 1), $queryString); ?>"> &lt;&lt; 前の<?= $linenum; ?>件へ</a>
                        <?php } ?>
                    </td>
                    <td width="15%" align="right" nowrap>
                        <?php if ($pageNum > 0) { ?>
                            <a href="<?php printf("%s?pageNum=%d%s", $currentPage, 0, $queryString); ?>">&nbsp;&nbsp;&nbsp;[最新の行にもどる]&nbsp;&nbsp;&nbsp;</a>
                        <?php }  ?>
                    </td>
                    <td width="15%" align="right" nowrap>
                        <?php if ($pageNum < $totalPages) { ?>
                            <a href="<?php printf("%s?pageNum=%d%s", $currentPage, min($totalPages, $pageNum + 1), $queryString); ?>">次の <?= $linenum; ?>件へ &gt;&gt;</a>
                        <?php }  ?>
                    </td>
                </tr>
            </table>
        <?php } ?>
        <!--ページャー End-->
        <table class="table_list">
            <thead class="thead-dark">
                <tr>
                    <th>申込番号</th>
                    <th>問合せ番号</th>
                    <th>申込日</th>
                    <th>氏名</th>
                    <th>電話番号</th>
                    <th>往復</th>
                    <th>個数<br>合計</th>
                    <th <?= $quantity_change_disable_; ?>>サイズ<br>個数変更</th>
                    <th <?= $cancel_btn_disable_; ?>>キャンセル</th>
                    <th <?= $affix_btn_disable_; ?>>貼り付け表</th>
                    <th <?= $reentry_btn_disable_; ?>>申込画面</th>
                </tr>
            </thead>
            <tbody>
                <!--ループ処理-->
                <?php
                foreach ($result as $key => $value) {
                ?>
                    <?php if ($result[$key]['キャンセル'] != 'キャンセル' && $result[$key]['キャンセル不可'] != "1") { ?>
                        <tr>
                        <?php } else if ($result[$key]['キャンセル不可'] == "1") { ?>
                        <tr style="background-color:#F0F0F0;">
                        <?php } else { ?>
                        <tr style="background-color:#C0C0C0;">
                        <?php } ?>
                        <td><?= $result[$key]['申込番号'] ?></td>
                        <td><?= $result[$key]['問合せ番号'] ?></td>
                        <td><?= $result[$key]['申込日'] ?></td>
                        <td><?= $result[$key]['氏名'] ?></td>
                        <td><?= $result[$key]['電話番号'] ?></td>
                        <td><?= $result[$key]['往復'] ?></td>
                        <td><?= $result[$key]['個数合計'] ?></td>

                        <?php if ($result[$key]['キャンセル'] != 'キャンセル') { ?>
                            <?php if ($result[$key]['キャンセル不可'] == "1") { ?>
                                <td></td>
                                <td></td>
                            <?php } else { ?>
                                <td>
                                    <p class="text_center"><input type="button" class="btn btn--blue" name="mofifi" onclick="change_num('<?= $result[$key]['申込番号10桁'] ?>')" value="変更"></p>
                                </td>
                                <td>
                                    <p class="text_center"><input type="button" class="btn btn--blue" name="mofifi" onclick="cancel_order('<?= $result[$key]['申込番号10桁'] ?>')" value="キャンセル"></p>
                                </td>
                            <?php } ?>
                        <?php } else { ?>
                            <td></td>
                            <td></td>
                        <?php } ?>
                        <?php if ($result[$key]['往復'] != '復路' && $result[$key]['キャンセル'] != 'キャンセル') { ?>
                            <td>
                                <p class="text_center"><input type="button" class="btn btn--blue" name="mofifi" onclick="download_tag('<?= $result[$key]['申込番号10桁'] ?>')" value="ダウンロード"></p>
                            </td>
                        <?php } else { ?>
                            <td><?php //復路は貼付表がないのでボタンを表示しない
                                ?></td>
                        <?php } ?>
                        <?php if ($result[$key]['キャンセル'] != 'キャンセル') { ?>
                            <td>
                                <p class="text_center"><input type="button" class="btn btn--blue" name="mofifi" onclick="re_order('<?= $result[$key]['申込番号10桁'] ?>')" value="再申込登録"></p>
                            </td>
                        <?php } else { ?>
                            <td></td>
                        <?php } ?>
                        </tr>
                    <?php
                }
                    ?>
            </tbody>
        </table>
    </div>

    </div><!-- /container -->
    <?php
    $footerSettings = "under";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/parts/footer.php");
    ?>

</body>

</html>
