<?php
/**
 * EVENT共通dashboard
 * 
 * 数量変更、キャンセル、貼付表DL、再登録
 * 
 * 
 */
class Dashboard_Lib
{
    // プロパティ

    //最新の20件を表示
    public $linenum;

    //ページNo
    public $pageNum;

    //最大行数
    public $maxRows;

    //サーバのホスト名取得
    public $currentPage;

    public static $sql_list = <<<__LONG_STRRING__
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

    public static $sql_list2 =<<<__LONG_STRRING__
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

    public static $sql_list3 =<<<__LONG_STRRING__
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

public static $dialog_html =<<<__LONG_STRRING__
    <div class="dialog-header">
    <span class="dialog-title">数量変更</span>
    <button type="button" class="dialog-close" onclick="close_dialog();" >閉じる☓</button>
    </div>
    <div class="dialog-content">
                    <table>
                        <tr>
                            <!--<div style="margin-bottom: 10px;">-->
                            <td class='comiket_box_item_name'>
                                60サイズ&nbsp;
                            </td>
                            <td class='comiket_box_item_value'>
                                <input autocapitalize="off" class=
                                "number-only comiket_box_item_value_input" style="" maxlength=
                                "2" inputmode="numeric" name=
                                "comiket_box_inbound_num_ary[13299]" data-pattern="^\d+$"
                                placeholder="例）1" type="text" value="">個 &nbsp;
                            </td><!--</div>-->
                        </tr>
                        <tr>
                            <!--<div style="margin-bottom: 10px;">-->
                            <td class='comiket_box_item_name'>
                                80サイズ&nbsp;
                            </td>
                            <td class='comiket_box_item_value'>
                                <input autocapitalize="off" class=
                                "number-only comiket_box_item_value_input" style="" maxlength=
                                "2" inputmode="numeric" name=
                                "comiket_box_inbound_num_ary[13300]" data-pattern="^\d+$"
                                placeholder="例）1" type="text" value="">個 &nbsp;
                            </td><!--</div>-->
                        </tr>
                        <tr>
                            <!--<div style="margin-bottom: 10px;">-->
                            <td class='comiket_box_item_name'>
                                100サイズ&nbsp;
                            </td>
                            <td class='comiket_box_item_value'>
                                <input autocapitalize="off" class=
                                "number-only comiket_box_item_value_input" style="" maxlength=
                                "2" inputmode="numeric" name=
                                "comiket_box_inbound_num_ary[13301]" data-pattern="^\d+$"
                                placeholder="例）1" type="text" value="">個 &nbsp;
                            </td><!--</div>-->
                        </tr>
                        <tr>
                            <!--<div style="margin-bottom: 10px;">-->
                            <td class='comiket_box_item_name'>
                                140サイズ&nbsp;
                            </td>
                            <td class='comiket_box_item_value'>
                                <input autocapitalize="off" class=
                                "number-only comiket_box_item_value_input" style="" maxlength=
                                "2" inputmode="numeric" name=
                                "comiket_box_inbound_num_ary[13302]" data-pattern="^\d+$"
                                placeholder="例）1" type="text" value="">個 &nbsp;
                            </td><!--</div>-->
                        </tr>
                        <tr>
                            <!--<div style="margin-bottom: 10px;">-->
                            <td class='comiket_box_item_name'>
                                160サイズ&nbsp;
                            </td>
                            <td class='comiket_box_item_value'>
                                <input autocapitalize="off" class=
                                "number-only comiket_box_item_value_input" style="" maxlength=
                                "2" inputmode="numeric" name=
                                "comiket_box_inbound_num_ary[13303]" data-pattern="^\d+$"
                                placeholder="例）1" type="text" value="">個 &nbsp;
                            </td><!--</div>-->
                        </tr>
                        <tr>
                            <!--<div style="margin-bottom: 10px;">-->
                            <td class='comiket_box_item_name'>
                                170サイズ&nbsp;
                            </td>
                            <td class='comiket_box_item_value'>
                                <input autocapitalize="off" class=
                                "number-only comiket_box_item_value_input" style="" maxlength=
                                "2" inputmode="numeric" name=
                                "comiket_box_inbound_num_ary[13304]" data-pattern="^\d+$"
                                placeholder="例）1" type="text" value="">個 &nbsp;
                            </td><!--</div>-->
                        </tr>
                        <tr>
                            <!--<div style="margin-bottom: 10px;">-->
                            <td class='comiket_box_item_name'>
                                180サイズ&nbsp;
                            </td>
                            <td class='comiket_box_item_value'>
                                <input autocapitalize="off" class=
                                "number-only comiket_box_item_value_input" style="" maxlength=
                                "2" inputmode="numeric" name=
                                "comiket_box_inbound_num_ary[13305]" data-pattern="^\d+$"
                                placeholder="例）1" type="text" value="">個 &nbsp;
                            </td><!--</div>-->
                        </tr>
                        <tr>
                            <!--<div style="margin-bottom: 10px;">-->
                            <td class='comiket_box_item_name'>
                                200サイズ&nbsp;
                            </td>
                            <td class='comiket_box_item_value'>
                                <input autocapitalize="off" class=
                                "number-only comiket_box_item_value_input" style="" maxlength=
                                "2" inputmode="numeric" name=
                                "comiket_box_inbound_num_ary[13306]" data-pattern="^\d+$"
                                placeholder="例）1" type="text" value="">個 &nbsp;
                            </td><!--</div>-->
                        </tr>
                        <tr>
                            <!--<div style="margin-bottom: 10px;">-->
                            <td class='comiket_box_item_name'>
                                220サイズ&nbsp;
                            </td>
                            <td class='comiket_box_item_value'>
                                <input autocapitalize="off" class=
                                "number-only comiket_box_item_value_input" style="" maxlength=
                                "2" inputmode="numeric" name=
                                "comiket_box_inbound_num_ary[13307]" data-pattern="^\d+$"
                                placeholder="例）1" type="text" value="">個 &nbsp;
                            </td><!--</div>-->
                        </tr>
                        <tr>
                            <!--<div style="margin-bottom: 10px;">-->
                            <td class='comiket_box_item_name'>
                                240サイズ&nbsp;
                            </td>
                            <td class='comiket_box_item_value'>
                                <input autocapitalize="off" class=
                                "number-only comiket_box_item_value_input" style="" maxlength=
                                "2" inputmode="numeric" name=
                                "comiket_box_inbound_num_ary[13308]" data-pattern="^\d+$"
                                placeholder="例）1" type="text" value="">個 &nbsp;
                            </td><!--</div>-->
                        </tr>
                        <tr>
                            <!--<div style="margin-bottom: 10px;">-->
                            <td class='comiket_box_item_name'>
                                260サイズ&nbsp;
                            </td>
                            <td class='comiket_box_item_value'>
                                <input autocapitalize="off" class=
                                "number-only comiket_box_item_value_input" style="" maxlength=
                                "2" inputmode="numeric" name=
                                "comiket_box_inbound_num_ary[13309]" data-pattern="^\d+$"
                                placeholder="例）1" type="text" value="">個 &nbsp;
                            </td><!--</div>-->
                        </tr>
                        <tr>
          <td class='comiket_box_item_name'></td>
          <td class="comiket_box_item_value" colspan="2"><input id="submit_button" type="submit" name="submit" value="数量変更（入力内容の確認）"></td>
        </tr>
                    </table>
    </div>
    </div>
__LONG_STRRING__;

//コンストラクタ
public function __construct()
{
}

    /**
     * DB接続
     */
    function dbconn($sv)
    {
        //環境切替
    // $environment_location=$_SERVER['REMOTE_ADDR'];//IPアドレス判定
    $environment_location=$sv;//手動開発環境
    // $environment_location='Release';//手動本番環境
    //****************************************
    //     DB接続
    //****************************************
        define('DB_DRIVER', 'pgsql');
        define('DB_PORT', '5432');
        define('DB_DATABASE', 'moving_db');
        define('DB_ENCODING', 'UTF8');
    switch($environment_location)
    {
        case '172.16.1.5'://テスト環境
        case 'Develop'://テスト環境
            define('DB_HOST', '172.16.1.5');
            define('DB_LOGIN', 'sgmvsp');
            define('DB_PASSWORD', 'PA9j97GF');
            break;
        case '10.60.224.165'://本番環境
        case 'Release'://本番環境
            define('DB_HOST', '10.60.61.133');
            define('DB_LOGIN', 'postgres');
            define('DB_PASSWORD', '');
            break;
        default://ipが取得できない場合はテスト環境に接続してみる。
            define('DB_HOST', '172.16.1.5');
            define('DB_LOGIN', 'sgmvsp');
            define('DB_PASSWORD', 'PA9j97GF');
            break;
    }

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
    // $this->con = $con;
    return $con;

    }

    /**
     * 一覧リスト
     * 
     */
    function getorderlist($con, $search_key){
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
    function pr($arg) {
        print '<pre>'."\n";
        print_r($arg);
        print '</pre>'."\n";
    }
    $query =<<<__LONG_STRRING__
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
            $query .=<<<__LONG_STRRING__
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
    //NEW ENERGY ZERO 限定
    $_SESSION['post_event'] = 710; 
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
    $query .=<<<__LONG_STRRING__
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
    $dataAcObj->esxecute();
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
            $comiketIdCheckD = $this->getChkD(sprintf("%010d", $_POST['eventid']));
            echo json_encode($comiketIdCheckD);
            die;
        }
    }
}

    /**
     * ID可変チェックデジット
     */
    function getChkD($param) {
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

}