<?php
/**
     * 12_申し込み履歴_スマホ。
     * @package    ssl_html
    * @subpackage event/m_inputHistory
    * @author     GiapLN(FPT Software) 
     */

    /**#@+
     * include files
     */
    require_once dirname(__FILE__) . '/../../lib/Lib.php';
    Sgmov_Lib::useView('event/MInputHistory');
    /**#@-*/

    // 処理を実行
    $view = new Sgmov_View_M_Input_History();

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
    <div id="main" class="main-history">
        <div class="wrap clearfix">
            <h1 class="page_title" style="margin-bottom: 5px;">登録情報一覧</h1>
            <div>
                <p style="font-size: 12pt;"><?php echo $forms['fullNm']; ?></p>
            </div>
            <div style="float:right;margin-top: 15px;font-size: 14px;">
                <span class="text_link_user_his" style="margin-top: 5px;margin-right: 20px;">
                    <a href="javascript:void(0);" onclick="checkRedirectUrl('<?php echo $sessionData['event_name'];?>', 'updateInfo');">会員情報変更</a>
                </span>
                
                <span class="text_link_user_his" style="margin-top: 5px;margin-right: 20px;">
                    <a href="javascript:void(0);" onclick="checkRedirectUrl('<?php echo $sessionData['event_name'];?>', 'passChange');">パスワード変更</a>
                </span>
                <span class="text_link_user_his" style="text-align: right;margin-top: 0px;">
                    <a href="logout.php?event_nm=<?php echo $sessionData['event_name'];?>">ログアウト</a>
                </span>
            </div>
            <div class="section other" style="margin-top:35px;"> 
                <div class="dl_block" style="height: auto;" >
                    <div style="display: inline-block; width: 100%" > 
                        <span style="margin-top: 5px;float:left;margin-left: 3px;">
                            <?php echo $fullEventNm; ?>
                        </span>
                        <span style="margin-top: 5px;float: right;margin-right: 3px;">
                            <button class="button button1" onclick="checkRedirectUrl('<?php echo $sessionData['event_name'];?>', 'input')">申込新規登録</button>
                        </span>
                    </div> 
                    <div style="margin-top:10px;">
                        <span style="margin-top: 5px;margin-left: 3px;">
                            <span style="color: #3409e9;"> 申込件数</span>： <?php echo $forms['countTotal'];?>件
                        </span>
                        <span style="margin-top: 5px;margin-left: 20px;">
                            <span style="color: #3409e9;">キャンセル件数</span>： <?php echo $forms['countDel'];?>件
                        </span>
                    </div>
                    <?php if (!empty($comiketHistory))  { ?>
                        <?php foreach ($comiketHistory as $row)  { ?>
                        <div class="item"
                            <?php if ($row['del_flg'] == 2) { ?>
                             style="background-color: #e0e0e0;"
                            <?php } ?>
                             > 
                            <div class="item-sub">
                                <p class="item-sub-1">
                                    <span style="color: #3409e9;">申込番号</span><br> 
                                    <span style="color: #3409e9;">往復区分</span><br> 
                                    <span style="color: #3409e9;">支払方法</span><br> 
                                    <span style="color: #3409e9;"> 
                                        <?php if ($row['detail_type'] == 1) { ?>
                                            お預り日 
                                        <?php } else { ?>
                                            搬出日
                                        <?php } ?>
                                    </span><br> 
                                </p>

                                <p class="item-sub-2">
                                    <span><?php echo str_pad($row['id'],10,"0",STR_PAD_LEFT); ?></span><br> 
                                    <span><?php echo $row['type']; ?></span><br> 
                                    <span><?php echo $row['shiharai']; ?></span><br> 
                                    <span><?php echo date('Y/m/d', strtotime($row['collect_date'])); ?></span><br> 
                                </p>
                                <p class="item-sub-3">
                                    <span style="color: #3409e9;">問合せ番号</span><br> 
                                    <span style="color: #3409e9;">個数合計</span><br> 
                                    <span style="color: #3409e9;">金額（税込）</span><br> 
                                    <span style="color: #3409e9;">
                                        <?php if ($row['detail_type'] == 1) { ?>
                                            搬入日 
                                        <?php } else { ?>
                                            配送日
                                        <?php } ?>
                                        
                                    </span><br> 
                                </p>

                                <p class="item-sub-4">
                                    <span><?php echo $row['toiawase_no_niugoki']; ?></span><br> 
                                    <span><?php echo $row['total']; ?></span><br> 
                                    <span>￥<?php echo number_format($row['amount_tax']); ?></span><br> 
                                    <span><?php echo date('Y/m/d', strtotime($row['delivery_date'])); ?></span><br> 
                                </p>
                            </div>
                            <div class="item-btn">
                                <button class="button button2" 
                                    <?php if ($row['del_flg'] == 2 || $row['del_flg'] == 1) { ?>
                                    disabled="disabled" style="background-color: #616161;margin-right: 10px;cursor: not-allowed;"
                                    <?php } else { ?>
                                    style="margin-right: 10px;" 
                                    <?php } ?>
                                    onclick="checkRedirectUrlBtn('<?php echo $sessionData['event_name'];?>', 'input', '<?php echo $row['comiket_id_str']; ?>')">再申込登録
                                </button>
                                <button class="button button2"                                        
                                    <?php if ($row['isEnable'] == false) { ?>
                                        disabled="disabled" style="background-color: #616161;margin-right: 10px;cursor: not-allowed;"
                                    <?php } else { ?>
                                        style="margin-right: 10px;" 
                                    <?php } ?>
                                    onclick="checkRedirectUrlBtn('<?php echo $sessionData['event_name'];?>', 'size_change', '<?php echo $row['comiket_id_str']; ?>')">サイズ変更
                                </button>
                                <button class="button button2"
                                    <?php if ($row['isEnable'] == false) { ?>
                                        disabled="disabled" style="background-color: #616161;margin-right: 10px;cursor: not-allowed;"
                                    <?php } else { ?>
                                        style="margin-right: 10px;" 
                                    <?php } ?>
                                    onclick="checkRedirectUrlBtn('<?php echo $sessionData['event_name'];?>', 'cancel', '<?php echo $row['comiket_id_str']; ?>')" >キャンセル
                                </button>
                                <button class="button button2"
                                    <?php if ($row['del_flg'] == 2 || $row['detail_type'] == 2 || $row['del_flg'] == 1) { ?>
                                    disabled="disabled" style="background-color: #616161;cursor: not-allowed;margin-right: 10px;"
                                    <?php } else { ?>
                                    style="margin-right: 10px;" 
                                    <?php } ?>
                                    onclick="window.open(getRedirectUrlBtn('<?php echo $sessionData['event_name'];?>', 'paste_tag', '<?php echo $row['comiket_id_str_2']; ?>','_blank'))"><span style="font-size: 11px">貼付票<br/>ダウンロード</span>
                                </button>

                            </div>
                        </div> 
                        <?php } ?>
                    <?php } ?>

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
            //passChange
            if (fuc == 'passChange') {
                <?php if(isset($_SESSION['EVENT_LOGIN'])) { ?>
                    location.href="passChange?event_nm=" + event_nm ;
                <?php } else  { ?>
                    location.href="login?event_nm=" + event_nm ;
                <?php } ?>
            }
            //input
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