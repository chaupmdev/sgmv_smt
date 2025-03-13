<?php
/**
 * EVENT Dashboard
 * 
 * 数量変更
 * キャンセル
 * 貼付表ダウンロード
 * 再登録
 * 
 * ※class化の準備中なので重複している
 *
 * @author Tamashiro Katsuhiko
 */
// ベーシック認証
require_once('../../lib/component/auth.php');
require_once dirname(__FILE__) . '/../../lib/Dashboard.php';
session_start();

$ds = new Dashboard_Lib;
$con = $ds->dbconn('Release');
//$con = $ds->dbconn('Develop');
//****************************************
//     ページャー: 変数
//****************************************

//最新の20件を表示
$linenum = "100";
$ds->linenum="100";
//ページNo
$pageNum = 0;
$ds->pageNum = 0;

//最大行数
$maxRows = 100;
$ds->maxRows = 100;

//サーバのホスト名取得
$currentPage = $_SERVER["PHP_SELF"];
$ds->currentPage = $_SERVER["PHP_SELF"];
//****************************************
//     2ページ目以降の処理
//***************************************
if (isset($_GET['pageNum'])){
    //Startページ数 計算
    $pageNum = $_GET['pageNum'];
    //Endページ数 計算
    $EndRow = $linenum * ($_GET['pageNum']+1);
}else{
    //Endページ数 計算
    $EndRow = $linenum;
}
//SQLリミット計算(スタート)
$startRow = $pageNum * $maxRows;
//SQL
//「comiket_detail」no_chg_flg チェック => "1" の場合はキャンセル・サイズ変更できない(搬出のみ)
$query = $ds::$sql_list;

if(@$_POST['cancel']!=null){
    $_SESSION['post_cancel'] = $_POST['cancel'];
}
switch(@$_SESSION['post_cancel'])
{
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
if(@$_POST['submit_push']=="true"){
    $_SESSION['post_input1'] = $_POST['input1'];
}
if(@$_SESSION['post_input1'] != "" )
{
    $str = str_replace("　", " ", $_SESSION['post_input1']);
    $search_key = explode(" ", $str);
    $query .= " and (";
    $cnt=0;
    $union="";
    foreach ($search_key as $key) {
        if($cnt==0){
            $union="";
        }else{
            $union=" or ";
        }
        $query .=$union;
        $query .=$ds::$sql_list2;

            $where = sprintf(" ilike '%s'", "%".$key."%");
            $query .= $where;
        $cnt++;
    }
    $query .= " ) ";
}

if(@$_POST['submit_push']=="true"){
    $_SESSION['post_from'] = $_POST['from'];
}
if(@$_SESSION['post_from'] != "" )
{
    $where = sprintf(" and  cast(c.created as date) >= '%s'", "%".$_SESSION['post_from']."%");
    $query .= $where;
}

if(@$_POST['submit_push']=="true"){
    $_SESSION['post_to'] = $_POST['to'];
}
if(@$_SESSION['post_to'] != "" )
{
    $where = sprintf(" and  cast(c.created as date) <= '%s'", "%".$_SESSION['post_to']."%");
    $query .= $where;
}

if(@$_POST['submit_push']=="true"){
    $_SESSION['post_event'] = $_POST['event'];
}
//東京マラソンEXPO 限定
$_SESSION['post_event'] = 5; 
if(@$_SESSION['post_event'] != "" )
{
    $where = sprintf(" and  c.event_id = %d ", $_SESSION['post_event']);
    $query .= $where;
}

if(@$_POST["eventsub"] != "" )
{
    $where = sprintf(" and  e2.cd = '%s' ", $_POST["eventsub"]);
    $query .= $where;
}

if(@$_POST['submit_push']=="true"){
    $_SESSION['post_type'] = $_POST['delivery_type'];
}
if(@$_SESSION['post_type'] != "" )
{
    if($_SESSION['post_type']!=0)
    {
        $where = sprintf(" and  cb.type = '%s' ", $_SESSION['post_type']);
        $query .= $where;
    }
}

$query .=$ds::$sql_list3;

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
if(count($result) != $linenum){
    $EndRow = count($result) + $startRow;
}

if (isset($_GET['totalRows'])){
    //既に計算済み
    $totalRows = $_GET['totalRows'];
} else {
    //LMITなしでクエリー送信
    $all = $con->query($query);
    $totalRows=0;
    foreach ($con->query($query) as $row) {
        $totalRows++;
    }
    //結果セットから行の数を取得
    // $totalRows = count($all);
}

//引数で指定した数値から、次に大きい整数を返す
$totalPages = ceil($totalRows/$maxRows)-1;
$num_hit = $totalPages;
$queryString = "";


//****************************************
//     URLパラメータ(GET)を取得
//****************************************

if (!empty($_SERVER['QUERY_STRING'])){
    //"&"で分割
    $params = explode("&", $_SERVER['QUERY_STRING']);
    $newParams = array();

    //foreach( 配列 as $value )
    foreach ($params as $param){
        //stristr(処理対象の文字列,検索する文字列)
        if(stristr($param, "pageNum") == false && stristr($param, "totalRows") == false){
            array_push($newParams, $param);
        }
    }
    if (count($newParams) != 0) { $queryString = "&" . implode("&", $newParams); }
}
$queryString = sprintf("&totalRows=%d%s", $totalRows, $queryString);

if(@$_POST["action"] != "" )
{
    if($_POST["action"]=="get_eventsub")
    {
        $query =<<<__LONG_STRRING__
        select distinct
            e.event_id,
            e.name
        from
            public.eventsub e
__LONG_STRRING__;
        $where_event = sprintf(" where e.event_id = %d", $_POST["sub_list"]);
        $query .= $where_event;
        $query .=<<<__LONG_STRRING__
            order by
                e.event_id
__LONG_STRRING__;
        $EventsubdataObj = $con->prepare($query);
        $EventsubdataObj->execute();
        $eventsub = array();
        while ($data = $EventsubdataObj->fetch(PDO::FETCH_ASSOC)) {
        $eventsub[] = $data;
        }
        $j=json_encode($eventsub);
        $eventsub_sel ="";
        $eventsub_sel .= "<option value=''>--</option>";
        foreach ($eventsub as $key => $value) 
        {
            $eventsub_sel .= "<option value='". $eventsub[$key]['event_id']."'>". $eventsub[$key]['name']. "</option>";
        }
        echo json_encode($eventsub_sel);
        die;
    }else if($_POST["action"]=="get_checkdigit"){
        //$comiketIdCheckD = getChkD(sprintf("%010d", $_POST['eventid']));
        $comiketIdCheckD = $ds->getChkD(sprintf("%010d", $_POST['eventid']));
        echo json_encode($comiketIdCheckD);
        die;
    }
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
	<title>キャンセル対応画面│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
	<link rel="shortcut icon" href="/misc/favicon.ico" type="image/vnd.microsoft.icon" />
	<link href="/css/common.css" rel="stylesheet" type="text/css" />
	<link href="/css/sitemap.css" rel="stylesheet" type="text/css" />
	<link href="/css/dashboard.css" rel="stylesheet" type="text/css" />
    <link href="./css/eve.css" rel="stylesheet" type="text/css">
    <link href="/css/form.css" rel="stylesheet" type="text/css">
    <!-- <link href="./css/eve.css" rel="stylesheet" type="text/css"> -->
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
	<script charset="UTF-8" type="text/javascript" src="/js/dashboard.js"></script>

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
		<div class="wrap clearfix"><h1 class="page_title">キャンセル対応画面</h1></div>
    <!--ページャー Start-->
        <table class="table_page">
            <tr>
                <td nowrap>
                <form id="search_form" name="search_form" action="sgmv_dashboard.php" onsubmit="return false;" method="post">
                <!-- <form id="search_form" name="search_form" action="../../lib/Dashboard_Lib.php" onsubmit="return false;" method="post"> -->
                <!-- <input type="button" name="_clear" class="btn btn--orange btn--radius" style="float: right;" onclick="form_clear();" value="クリア"> -->
                <p>申込日範囲 自 <input type="date" name="from" value="<?= @$_SESSION['post_from'] ?>"> ～至 <input type="date" name="to" value="<?= @$_SESSION['post_to'] ?>">  
                イベント <select name='event' id="event" >
                    <!-- <option selected disabled>--</option> -->
                    <?php
                        // $res = $con->prepare("select distinct e.id,e.name from event e order by e.id;");
                        $res = $con->prepare("select distinct e.id,e.name from event e where e.id=5 order by e.id;");
                        $res->execute();
                        $event = [];
                        while ($data = $res->fetch(PDO::FETCH_ASSOC)) {
                            $event[] = $data;
                        }
                        foreach ($event as $key => $val) 
                        //{ if($_SESSION['post_event']==$event[$key]['id']){
                        { if($event[$key]['id']==5){ ?>
                            <option value="<?=$event[$key]['id']?>" selected><?=$event[$key]['name']?></option>
                        <?php }else{ ?>
                            <option value="<?=$event[$key]['id']?>"><?=$event[$key]['name']?></option>
                            <?php } ?>
                    <?php } ?>
                </select>
                 <select name='eventsub' id="eventsub"></select></p>
                 <?php if( !empty($_SESSION['post_type']) ){ 
                                    $type1="";
                                    $type2="";
                                    $type3="";
                                    $type4="";
                                    $type5="";
                            switch($_SESSION['post_type'])
                            {
                                default:
                                case 0:
                                    $type1="selected";
                                    break;
                                case 1:
                                    $type2="selected";
                                    break;
                                case 2:
                                    $type3="selected";
                                    break;
                                case 4:
                                    $type4="selected";
                                    break;
                                case 5:
                                    $type5="selected";
                                    break;
                            }
                        } ?>
                <p> 輸送<select name='delivery_type' id="delivery_type">
                            <option value="0" <?=$type1;?>>--</option>
                            <option value="1" <?=$type2;?>>往路</option>
                            <option value="2" <?=$type3;?>>復路</option>
                            <option value="4" <?=$type4;?>>手荷物</option>
                            <option value="5" <?=$type5;?>>物販</option>
                        </select>
                        <?php if( !empty($_SESSION['post_cancel']) ){ 
                                    $can1="";
                                    $can2="";
                                    $can3="";
                            switch($_SESSION['post_cancel'])
                            {
                                case 1:
                                    $can2="selected";
                                    break;
                                case 2:
                                    $can3="selected";
                                    break;
                                default:
                                    $can1="selected";
                            }
                        } ?>
              キャンセル <select name='cancel' id="cancel">
                            <option value="0" <?=$can1;?>>含めない</option>
                            <option value="1"<?=$can2;?>>全て</option>
                            <option value="2"<?=$can3;?>>キャンセル</option>
                        </select>
                <!-- 任意の<input>要素＝入力欄などを用意する -->
                複数キーワード検索 </del><input type="text" name="input1" style="width:350px;" id="sbox" value="<?= @$_SESSION['post_input1'] ?>" placeholder="申込番号/問合せ番号/氏名/電話番号" ></p>
                <!-- 送信ボタンを用意する -->
                <input type="button" name="_submit" onclick="form_submit();" class="btn btn--orange btn--radius" style="float: right;" value="絞込">
                </form>
                </td>
            </tr>
        </table>
        <?php if($totalRows > 0){//0件の場合非表示 ?>
        <table class="table_page">
            <tr>
                <td width="55%" align="left">
                    <strong class="font-size14 red"><?= $totalRows ?></strong>件中／<?= $startRow+1 ?>～<?= $EndRow ?> 件表示	</td>
                <td width="15%" align="right">
                    <?php if ($pageNum > 0) { ?>
                        <a href="<?php printf("%s?pageNum=%d%s", $currentPage, max(0, $pageNum - 1), $queryString) ;?>"> &lt;&lt; 前の<?= $linenum ;?>件へ</a>
                    <?php } ?>
                </td>
                <td width="15%" align="right">
                    <?php if ($pageNum > 0) { ?>
                        <a href="<?php printf("%s?pageNum=%d%s", $currentPage, 0, $queryString); ?>">最新の行にもどる</a>
                    <?php }  ?>
                </td>
                <td width="15%" align="right">
                    <?php if ($pageNum < $totalPages) { ?>
                        <a href="<?php printf("%s?pageNum=%d%s", $currentPage, min($totalPages, $pageNum + 1), $queryString); ?>" >次の <?= $linenum ;?>件へ &gt;&gt;</a>
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
          <th style="display: none;">サイズ<br>個数変更</th>
          <th>キャンセル</th>
          <th style="display: none;">貼り付け表</th>
          <th style="display: none;">申込画面</th>
        </tr>
        </thead>
        <tbody>
        <!--ループ処理-->
        <?php 
			foreach ($result as $key => $value) {
        ?>
                <?php if($result[$key]['キャンセル']!='キャンセル' && $result[$key]['キャンセル不可']!="1"){ ?>
                    <tr>
                <?php } else if($result[$key]['キャンセル不可']=="1") { ?>
                    <tr style="background-color:#F0F0F0;">
                <?php } else { ?>
                    <tr style="background-color:#C0C0C0;">
                <?php }?>
                    <td><?= $result[$key]['申込番号'] ?></td>
                    <td><?= $result[$key]['問合せ番号'] ?></td>
                    <td><?= $result[$key]['申込日'] ?></td>
                    <td><?= $result[$key]['氏名'] ?></td>
                    <td><?= $result[$key]['電話番号'] ?></td>
                    <td><?= $result[$key]['往復'] ?></td>
                    <td><?= $result[$key]['個数合計'] ?></td>

                    <?php if($result[$key]['キャンセル']!='キャンセル'){ ?>
                        <!-- <td><p class="text_center"><input type="button" class="btn btn--blue" name="mofifi" onclick="change_num('<?//= $result[$key]['申込番号10桁'] ?>')" value="変更"></p></td> -->
                        <td style="display: none;"><p class="text_center"><input type="button" class="btn btn--blue" name="open-sample-dialog" onclick="open_dlg('<?= $result[$key]['問合せ番号'] ?>')" value="変更"></p></td>
                        <?php if($result[$key]['キャンセル不可']=="1"){ ?>
                            <td style="display: none;"></td>
                        <?php }else{?>
                            <td><p class="text_center"><input type="button" class="btn btn--blue" name="mofifi" onclick="cancel_order('<?= $result[$key]['申込番号10桁'] ?>')" value="キャンセル"></p></td>
                        <?php }?>
                    <?php } else { ?>
                        <td style="display: none;"></td>
                        <td></td>
                    <?php }?>
                    <?php if($result[$key]['往復']!='復路' && $result[$key]['キャンセル']!='キャンセル'){ ?>
                        <td style="display: none;"><p class="text_center"><input type="button" class="btn btn--blue" name="mofifi" onclick="download_tag('<?= $result[$key]['申込番号10桁'] ?>')" value="ダウンロード"></p></td>
                    <?php } else { ?>
                        <td style="display: none;"><?php //復路は貼付表がないのでボタンを表示しない ?></td>
                    <?php }?>
                    <?php if($result[$key]['キャンセル']!='キャンセル'){ ?>
                        <td style="display: none;"><p class="text_center"><input type="button" class="btn btn--blue" name="mofifi" onclick="re_order('<?= $result[$key]['申込番号10桁'] ?>')" value="再申込登録"></p></td>
                    <?php } else { ?>
                        <td style="display: none;"></td>
                    <?php }?>
                </tr>
		<?php } ?>
        </tbody>
    </table>
    </div>
    <div class="dialog" id="sample-dialog">
        <?=$ds::$dialog_html;?>
</div><!-- /container -->
<?php
	$footerSettings = "under";
	include_once($_SERVER['DOCUMENT_ROOT']."/parts/footer.php");
?>
</body>
</html>