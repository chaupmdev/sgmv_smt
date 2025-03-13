<?php
/**
 * Cosco Shohin Dashboard
 * 
 */
// ベーシック認証
//require_once('../../lib/component/auth.php');
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('csc/MstShohin');
Sgmov_Lib::useForms(array('Error', 'EveSession'));

//処理を実行
$view = new Sgmov_View_Csc_MstShohin(); 
Sgmov_Component_Log::debug("9");
$result = array();
$request = $_REQUEST;

try {
    $result = $view->executeInner($request);
} catch (Exception $e) {
    $exInfo = $e->getMessage();
    $result = array(
        'status' => 'error',
        'message' => 'エラーが発生しました。',
        'res_data' => array(
            'error_info' => $exInfo,
        ),
    );
    Sgmov_Component_Redirect::redirectPublicSsl("/500.html");
    exit;
}

$shohinList = @$result['res_data']['shohinList'];

if (!empty($request)) {
    $options = @$result['res_data']['options'];
} else {
    $options = $view->getCostcoOptions();
}

$request = @$result['res_data']['request'];
$totalRows = @$result['res_data']['totalRows'];
$startRow = @$result['res_data']['startRow'];//startRow
$endRow = @$result['res_data']['endRow'];//endRow
$pageNum = @$result['res_data']['pageNum'];
$linenum = @$result['res_data']['linenum'];
$currentPage = $_SERVER["PHP_SELF"];
$totalPages = @$result['res_data']['totalPages'];
$queryString = '';
//'isErrorSearch' => $isErrorSearch
$isErrorSearch = @$result['res_data']['isErrorSearch'];
if (!empty($_SERVER['QUERY_STRING'])){
    //"&"で分割
    $params = explode("&", $_SERVER['QUERY_STRING']);
    $newParams = array();

    foreach ($params as $param){
        //stristr(処理対象の文字列,検索する文字列)
        if(stristr($param, "pageNum") == false && stristr($param, "totalRows") == false){
            array_push($newParams, $param);
        }
    }
    if (count($newParams) != 0) { $queryString = "&" . implode("&", $newParams); }
}
$queryString = sprintf("&totalRows=%d%s", $totalRows, $queryString);

$dateNow = date('Y-m-d');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0" />
    <meta name="Keywords" content="佐川急便,ＳＧムービング,sgmoving,無料見積り,お引越し,設置,法人,家具輸送,家電輸送,精密機器輸送,設置配送" />
    <meta name="Description" content="サイトマップのご案内です。" />
    <title>コストコ_商品検索一覧│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
    <link rel="shortcut icon" href="/misc/favicon.ico" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/sitemap.css" rel="stylesheet" type="text/css" />
    <link href="/csc/css/dashboard.css" rel="stylesheet" type="text/css" />
    <link href="/css/eve.css" rel="stylesheet" type="text/css">
    <link href="/css/form.css" rel="stylesheet" type="text/css">

    <script charset="UTF-8" type="text/javascript" src="/js/jquery-2.2.0.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/lodash.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/common.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/personal/js/anchor.js"></script>
    <script src="/js/ga.js" type="text/javascript"></script>
    <script charset="UTF-8" type="text/javascript" src="/csc/js/dashboard.js"></script>
    <script src="/js/form/rep/bootstrap.min.js"></script>
</head>
<body>
<?php
    $gnavSettings = "";
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/header.php';
    ?>
    <div id="main">
        <div class="wrap clearfix" style="width: 1280px;"><h1 class="page_title">コストコ_商品検索一覧</h1></div>
        <div style="width: 1280px;margin: auto;margin-bottom:7px;">
            <form method="post" action="/csc/mst_shohin_list.php" enctype="multipart/form-data">
                <table class="table_page" style="border-spacing: 10px;">
                    <tr>
                        <td class="td-search">
                            有効日
                            <span class="require">必須</span>
                        </td>
                        <td>
                            <input type="date" class="td-date"  max="9999-12-31" name="date_valid" value="<?= isset($request['date_valid']) ? $request['date_valid'] : $dateNow; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td class="td-search">ID</td>
                        <td><input type="text" class="td-txt-small" maxlength="10"  name="id"  value="<?= @$request['id'] ?>">
                        &nbsp;&nbsp;&nbsp;商品コード  &nbsp;
                        <input type="text"  name="shohin_cd" maxlength="30"  value="<?= @$request['shohin_cd'] ?>">
                        &nbsp;&nbsp;&nbsp;商品名&nbsp;
                        <input type="text" class="td-txt-large" maxlength="100" name="shohin_name"  value="<?= @$request['shohin_name'] ?>">
                        </td>
                    </tr>
                    <tr>
                        <td class="td-search">サイズ</td>
                        <td><input type="text" class="td-txt-small" maxlength="5"  name="size_from"  value="<?= @$request['size_from'] ?>"> &nbsp;～&nbsp;<input type="text" class="td-txt-small" maxlength="5"   name="size_to"  value="<?= @$request['size_to'] ?>">
                        &nbsp;&nbsp;&nbsp;オプションid&nbsp;
                            <select name='option_id' id="option_id" class="td-select">
                            <?php foreach ($options as $key => $val) : ?>
                                <option value="<?= @$key ?>" <?= @$key == @$request['option_id'] ? ' selected' : ''; ?>>
                                    <?= @$val ?>
                                </option>
                            <?php endforeach; ?>
                            </select>
                        &nbsp;&nbsp;&nbsp;データ種別&nbsp;
                        <select name='data_type' id="data_type" class="td-select">
                            <option value="">全て</option>
                            <option value="6" <?php if (@$request['data_type'] == '6') { ?> selected = "selected" <?php } ?>>6：D24でない</option>
                            <option value="7" <?php if (@$request['data_type'] == '7') { ?> selected = "selected" <?php } ?>>7：D24</option>
                        </select>
                        &nbsp;&nbsp;&nbsp;重量&nbsp;
                        <input type="text" class="td-txt-small" maxlength="4"   name="juryo_from"  value="<?= @$request['juryo_from'] ?>"> &nbsp;～&nbsp;
                        <input type="text" class="td-txt-small" maxlength="4"   name="juryo_to"  value="<?= @$request['juryo_to'] ?>">
                        </td>
                    </tr>
                </table>
                <input type="hidden" class="td-txt-small"  name="isErrorSearch"  value="<?= @$isErrorSearch ?>">
                <p style="width: 200px;float:right;display: inline-flex;margin-bottom: 10px;"><input type="button" class="btn btn--blue btn-search btn-clear" onclick="clearRequestSearch();" name="btnClear"  value="クリア"> <input type="submit" class="btn btn--blue btn-search" name="btnSearch"  value="検索"></p>
            </form>
        </div>
        
    <?php if($totalRows > 0){//0件の場合非表示 ?>
        <table class="table_page" style="width: 500px;">
            <tr>
                <td width="55%" align="left">
                    <strong class="font-size14 red"><?= $totalRows ?></strong>件中／<?= $startRow+1 ?>～<?= $endRow ?> 件表示	</td> 
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
    <div class="clearfix"></div>
    <!--ページャー End-->
    <div style="width: 1280px;margin: auto;margin-bottom:7px;">
        <p style="width: 100px;float:left;"><input type="button" class="btn btn--blue btn-search" name="add_new" onclick="detail('add')" value="新規登録"></p>
        <p style="width: 100px;float:right;"><input type="button" class="btn btn--blue btn-search" name="export_csv" onclick="exportCSV();" value="CSV出力"></p>
    </div>
    <div class="clearfix"></div>
    <table class="table_list">
        <thead class="thead-dark">
        <tr>
          <th>ID</th>
          <th>商品コード</th>
          <th>商品名</th>
          <th>サイズ</th>
          <th>オプションid</th>
          <th>データ種別</th>
          <th>重量</th>
          <!--<th>適用開始日</th>
          <th>適用終了日</th>-->
          <th>梱包数</th>
          <th></th>
          <!--<th></th>-->
        </tr>
        </thead>
        <tbody>
        <?php 
        if (!empty($shohinList)) {
            foreach ($shohinList as $key => $value) {
        ?>
                <tr>
                    <td class="txt-right"><?= $value['id'] ?></td>
                    <td><?= $value['shohin_cd'] ?></td>
                    <td><?= $value['shohin_name'] ?></td>
                    <td class="txt-right"><?= $value['size'] ?></td>
                    <td><?= $value['option_nm'] ?></td>
                    <td ><?= $value['data_type'] ?></td>
                    <td class="txt-right"><?= $value['juryo'] ?></td>
                    <!--<td class="txt-center"><?php //value['start_date'] ?></td> -->
                    <!--<td class="txt-center"><?php //value['end_date'] ?></td> -->
                    <td class="txt-right"><?= $value['konposu'] ?></td>
                    
                    <td>
                        <?php 
                        if ($value['isEnable'] == 1) {
                        ?>
                        <p class="text_center"><input type="button" class="btn btn--blue" name="edit[]" onclick="detail('edit',<?= $value['id'] ?>);" value="修正"></p>
                        <?php 
                        }
                        ?>
                        
                    </td>
                    <!--<td>
                        <p class="text_center"><input type="button" class="btn btn--blue" name="delete[]" onclick="deleteShohin(<?php //$value['id'] ?>);" value="削除"></p>
                    </td>-->
                </tr>
        <?php 
            }
        } else {
        ?>
            <tr>
                <td class="txt-center" colspan="9">検索結果は０件です。</td>
            </tr>
        <?php 
        }
        ?>
        </tbody>
    </table>
    </div>
    <?php
        $footerSettings = "under";
        include_once($_SERVER['DOCUMENT_ROOT']."/parts/footer.php");
    ?>
    
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="background-color: #f2dede;color: #a94442;border-top-left-radius: 6px;border-top-right-radius: 6px;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">エラーメッセージ</h4>
      </div>
      <div class="modal-body">
        <p>有効日を入力してください。</p>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>
<script>
    var isErrorSearch = $('input[name="isErrorSearch"]').val();
    if (isErrorSearch == '1') {
        $('#myModal').modal('show');
    }
    
</script>
</body>
</html>
