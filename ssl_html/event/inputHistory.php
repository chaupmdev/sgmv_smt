<?php
/**
     * 08_申し込み履歴_PC。
     * @package    ssl_html
    * @subpackage event/inputHistory
    * @author     GiapLN(FPT Software) 
     */

    /**#@+
     * include files
     */
    require_once dirname(__FILE__) . '/../../lib/Lib.php';
    Sgmov_Lib::useView('event/InputHistory');
    /**#@-*/

    // 処理を実行
    $view = new Sgmov_View_Input_History();

    $forms = $view->execute();

    $comiketHistory = $forms['comiketHistory'];
    $sessionData = $forms['sessionData'];
    $fullEventNm = $forms['fullEventNm'];
    
    $baseUrl = $forms['baseUrl'];
?> 
<!DOCTYPE html>
<html dir="ltr" lang="ja-jp" xml:lang="ja-jp">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0" />
    <meta http-equiv="content-style-type" content="text/css" />
    <meta http-equiv="content-script-type" content="text/javascript" />
    <meta name="Keywords" content="" />
    <meta name="Description" content="催事・イベント配送受付サービスのお申し込みのご案内です。" />
    <meta name="author" content="SG MOVING Co.,Ltd" />
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved." />
    <title>登録情報一覧│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
<?php
    // キャッシュ対策
    $sysdate = new DateTime();
    $strSysdate = $sysdate->format('YmdHi');
?>
    <!-- Materialize CSS -->
    <!--<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">-->
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <link href="/event/css/event.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <script charset="UTF-8" type="text/javascript" src="/event/js/jquery-3.1.1.min.js"></script>
</head>
<body>
<?php
    $gnavSettings = 'contact';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/header.php';
?>
    <div id="breadcrumb">
        
    </div>
    <div id="main">
        <div class="wrap clearfix">
            <h1 class="page_title" style="margin-bottom: 5px;">登録情報一覧</h1>
            <div style="float:left;">
                <p style="font-size: 12pt;"><?php echo $forms['fullNm']; ?></p>
            </div>
            
            <div style="float:right;margin-top: 25px;font-size: 14px;">
                <span class="text_link_user_his_pc" style="margin-top: 5px;margin-right: 20px;">
                    <a href="javascript:void(0);" onclick="checkRedirectUrl('<?php echo $sessionData['event_name'];?>', 'updateInfo');">会員情報変更</a>
                </span>
                
                <span class="text_link_user_his_pc" style="margin-top: 5px;margin-right: 20px;">
                    <a href="javascript:void(0);" onclick="checkRedirectUrl('<?php echo $sessionData['event_name'];?>', 'passChange');">パスワード変更</a>
                </span>
                <span class="text_link_user_his_pc" style="text-align: right;margin-top: 0px;">
                    <a href="logout.php?event_nm=<?php echo $sessionData['event_name'];?>">ログアウト</a>
                </span>
            </div>
            <div class="section other" style="margin-top:55px;">
                <div class="dl_block" style="height: auto;" >
                    
                    <table class="default_tbl" style="margin: auto;margin-top: 20px;margin-bottom: 40px;font-size: 10pt;">
                        <tr>
                            <td style="padding-left:0px; margin-left:0px;padding-left: 0px;margin-left: 0px;border-top: none;border-left: none;border-right: none;font-size: 12pt;" colspan="7">『<?php echo $fullEventNm; ?>』のお申し込み履歴</td>
                            
                            <td  style="text-align: right;padding: 0px;font-size: 11pt !important;border-top: none;border-left: none;border-right: none;" colspan="2">
                                <button class="button-his-pc button-his-pc-1" onclick="checkRedirectUrl('<?php echo $sessionData['event_name'];?>', 'input')">申込新規登録</button>
                            </td>
                        </tr>
                        <tr style="color: #fff;font-weight: bold;">
                            <th style="background-color: #1774bc;">申込み番号</th>
                            <th style="background-color: #1774bc;">問合せ番号</th>
                            <th style="background-color: #1774bc;">申込日</th>
                            <th style="background-color: #1774bc;">往復</th>
                            <th style="background-color: #1774bc;width: 60px;">個数合計</th>
                            <th style="background-color: #1774bc;width: 85px;">サイズ・個数変更</th>
                            <th style="background-color: #1774bc;">キャンセル</th>
                            <th style="background-color: #1774bc;"> 貼付票</th>
                            <th style="background-color: #1774bc;"></th>
                        </tr>
                        
                        <?php if (!empty($comiketHistory))  { ?>
                            <?php foreach ($comiketHistory as $row)  { ?>
                                <!-- GiapLN fix bug SMT6-118 --> 
                                <tr
                                    <?php if ($row['del_flg'] == 2 || $row['del_flg'] == 1) { ?>
                                    style="background-color: #e0e0e0;" 
                                   <?php } ?>
                                >
                                    <td class="td_info"><?php echo str_pad($row['id'],10,"0",STR_PAD_LEFT); ?></td>
                                    <td class="td_info"><?php echo $row['toiawase_no_niugoki']; ?></td>
                                    <td class="td_info"><?php echo date('Y/m/d', strtotime($row['created'])); ?></td>
                                    <td class="td_info"><?php echo $row['type']; ?></td>
                                    <td class="td_info text-right"><?php echo $row['total']; ?></td>
                                    <td class="td_info td_info_btn">
                                        <?php if ($row['del_flg'] != 2 && $row['del_flg'] != 1) { ?>
                                            <button class="button-his-pc button-his-pc-2" 
                                                <?php if ($row['isEnable'] == false) { ?>
                                                disabled="disabled" style="background-color: #616161;cursor: not-allowed;"  
                                               <?php } ?>
                                                onclick="checkRedirectUrlBtn('<?php echo $sessionData['event_name'];?>', 'size_change', '<?php echo $row['comiket_id_str']; ?>')">変更
                                            </button>
                                        <?php } ?>
                                    </td>
                                    <td class="td_info td_info_btn">
                                        <?php if ($row['del_flg'] != 2 && $row['del_flg'] != 1) { ?>
                                            <button class="button-his-pc button-his-pc-2" 
                                                <?php if ($row['isEnable'] == false) { ?>
                                                disabled="disabled" style="background-color: #616161;cursor: not-allowed;"  
                                               <?php } ?>
                                                onclick="checkRedirectUrlBtn('<?php echo $sessionData['event_name'];?>', 'cancel', '<?php echo $row['comiket_id_str']; ?>')">キャンセル
                                            </button>
                                        <?php } ?>
                                    </td>
                                    <td class="td_info td_info_btn">
                                        <?php if ($row['del_flg'] != 2 && $row['del_flg'] != 1 && ($row['detail_type'] == 1 || $row['detail_type'] == 3)) { ?>
                                        <button class="button-his-pc button-his-pc-2" onclick="window.open(getRedirectUrlBtn('<?php echo $sessionData['event_name'];?>', 'paste_tag', '<?php echo $row['comiket_id_str_2']; ?>','_blank'))">ダウンロード</button>
                                        <?php } ?>
                                    </td>

                                    <td class="td_info td_info_btn">
                                        <?php if ($row['del_flg'] != 2 &&  $row['del_flg'] != 1) { ?>
                                            <button class="button-his-pc button-his-pc-2" onclick="checkRedirectUrlBtn('<?php echo $sessionData['event_name'];?>', 'input', '<?php echo $row['comiket_id_str']; ?>')">再申込登録</button>
                                        <?php } ?>
                                    </td>
                                    
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--main disabled="disabled"-->
    
    
<?php
    $footerSettings = 'under';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/footer.php';
?>
    <script> 
        function checkRedirectUrl(event_nm, fuc) {
            if (fuc == 'updateInfo') {
                <?php if(isset($_SESSION['EVENT_LOGIN'])) { ?>
                    location.href="updateInfo?event_nm=" + event_nm ;
                <?php } else  { ?>
                    location.href="login?event_nm=" + event_nm ;
                <?php } ?>
            } 
            
            if (fuc == 'input') {
                <?php if(isset($_SESSION['EVENT_LOGIN'])) { ?>
                    location.href="<?php echo $baseUrl; ?>" + "/" + event_nm + "/input";
                <?php } else  { ?>
                    location.href="login?event_nm=" + event_nm ;
                <?php } ?>
            }
            if (fuc == 'size_change') {
                location.href="<?php echo $baseUrl; ?>" + "/" + event_nm + "/input";
            }
            if (fuc == 'passChange') {
                <?php if(isset($_SESSION['EVENT_LOGIN'])) { ?>
                    location.href="passChange?event_nm=" + event_nm ;
                <?php } else  { ?>
                    location.href="login?event_nm=" + event_nm ;
                <?php } ?>
            }
            
        }
        
        function checkRedirectUrlBtn(event_nm, fuc, commiketIDStr) {
            location.href="<?php echo $baseUrl; ?>" + "/" + event_nm + "/" + fuc + "/" + commiketIDStr;
        }
        
        function getRedirectUrlBtn(event_nm, fuc, commiketIDStr) {
            return "<?php echo $baseUrl; ?>" + "/" + event_nm + "/" + fuc + "/" + commiketIDStr; 
        }
    </script>
</body>
</html>