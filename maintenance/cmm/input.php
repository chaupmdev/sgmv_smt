<?php
/**
 * コメントマスタメンテナンス入力画面を表示します。
 * @package    maintenance
 * @subpackage CMM
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('cmm/Input');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Cmm_Input();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Cmm002Out
 */
$cmm002Out = $forms['outForm'];

/**
 * エラーフォーム
 * @var Sgmov_Form_Error
 */
$e = $forms['errorForm'];

?>
<!DOCTYPE html>
<html dir="ltr" lang="ja-jp" xml:lang="ja-jp">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <title>ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
    <meta name="author" content="SG MOVING Co.,Ltd" />
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved." />
    <link href="/common/css/top_top.css" rel="stylesheet" type="text/css" media="screen,tv,projection,print" />
    <link href="/common/css/top_print.css" rel="stylesheet" type="text/css" media="print" />
    <link href="/common/css/top_main.css" rel="stylesheet" type="text/css" media="screen,tv,projection,print" />
    <link href="/common/css/cruise.css" rel="stylesheet" type="text/css" />
    <link href="/common/css/jquery-ui.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <div class="helpNav">
        <p>
            <a id="pageTop" name="pageTop"></a>このページの先頭です
        </p>
        <p>
            <a href="#contentTop">メニューを飛ばして本文を読む</a>
        </p>
    </div>
    <div id="wrapper">
        <div id="header">
            <!-- ▼SGH共通ヘッダー start -->
            <div id="sghHeader">
                <h1><a href="<?php echo Sgmov_Component_Config::getUrlMaintenance(); ?>"><img src="/common/img/ttl_sgmoving-logo.png" alt="ＳＧムービング" width="118" height="40" /></a></h1>
                <p class="sgh">
                    <a href="http://www.sg-hldgs.co.jp/" target="_blank">
                        <img src="/common/img/pct_sgh-logo.png" alt="ＳＧホールディングス" width="41" height="29" />
                    </a>
                </p>
            </div>
            <!-- /#sghHeader -->
            <!-- ▲／SGH共通ヘッダー end -->

            <!-- ▼グローバルナビ start -->
            <dl id="globalNav">
                <dt>サイト内総合メニュー</dt>
                <dd>
                    <ul>
                        <li class="nav01">
                            <a href="/acm/menu"><img src="/common/img/nav_global01.gif" alt="メニュー" width="91" height="41" /></a>
                        </li>
                        <li class="nav02">
<?php if($cmm002Out->honsha_user_flag() === '1'){ ?>
                            <a href="/acf/input"><img src="/common/img/nav_global02.gif" alt="料金マスタ設定" width="131" height="41" /></a>
<?php }else{ ?>
                            <img src="/common/img/nav_global02_off.gif" alt="料金マスタ設定" width="131" height="41" />
<?php } ?>
                        </li>
                        <li class="nav03">
                            <a href="/asp/list/extra"><img src="/common/img/nav_global03.gif" alt="閑散・繁忙期料金設定" width="169" height="41" /></a>
                        </li>
                        <li class="nav04">
                            <a href="/asp/list/campaign"><img src="/common/img/nav_global04.gif" alt="キャンペーン特価設定" width="168" height="41" /></a>
                        </li>
                        <li class="nav05">
<?php if($cmm002Out->honsha_user_flag() === '1'){ ?>
                            <a href="/aoc/list/"><img src="/common/img/nav_global05.gif" alt="他社連携キャンペーン設定" width="242" height="41" /></a>
<?php }else{ ?>
                            <img src="/common/img/nav_global05_off.gif" alt="他社連携キャンペーン設定" width="242" height="41" />
<?php } ?>
                        </li>
                        <li class="nav06">
                            <a href="/acm/logout"><img src="/common/img/nav_global06.gif" alt="ログアウト" width="99" height="41" /></a>
                        </li>
                    </ul>
                </dd>
            </dl>
            <!-- /#globalNav -->
            <!-- ▲／グローバルナビ end -->
        </div>
        <!-- /#header -->
    </div>
    <!-- /#wrapper -->

    <div id="topWrap">

        <div class="helpNav">
            <p><a id="contentTop" name="contentTop"></a>ここから本文です</p>
        </div>

<?php if($cmm002Out->sp_list_kind() === Sgmov_View_Cmm_Common::SP_LIST_KIND_COMMENTS){ ?>
        <h2 class="page_ttl"><img src="/common/img/cruise/ttl_master_07.png" width="900" height="36" alt="お客様の声コメント設定" /></h2>
        <h3 class="register_ttl">お客様の声コメント情報の登録</h3>
<?php }else if($cmm002Out->sp_list_kind() === Sgmov_View_Cmm_Common::SP_LIST_KIND_ATTENTION){ ?>
        <h2 class="page_ttl"><img src="/common/img/cruise/ttl_master_08.png" width="900" height="36" alt="この子に注目コメント設定" /></h2>
        <h3 class="register_ttl">この子に注目コメント情報の登録</h3>
<?php } ?>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="register_area">

<?php if (isset($e) && $e->hasErrorForId('top_conflict')) { ?>
                <p class="red"><?php echo $e->getMessage('top_conflict'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_fileupload')) { ?>
                <p class="red"><?php echo $e->getMessage('top_fileupload'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_comment_title')) { ?>
                <p class="red">タイトル<?php echo $e->getMessage('top_comment_title'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_comment_address')) { ?>
                <p class="red">住所<?php echo $e->getMessage('top_comment_address'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_comment_office')) { ?>
                <p class="red">営業所<?php echo $e->getMessage('top_comment_office'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_comment_name')) { ?>
                <p class="red">氏名<?php echo $e->getMessage('top_comment_name'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_comment_text')) { ?>
                <p class="red">コメント<?php echo $e->getMessage('top_comment_text'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_comment_file_1')) { ?>
                <p class="red">写真[1]<?php echo $e->getMessage('top_comment_file_1'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_comment_file_2')) { ?>
                <p class="red">写真[2]<?php echo $e->getMessage('top_comment_file_2'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_comment_start_date')) { ?>
                <p class="red">掲載開始日<?php echo $e->getMessage('top_comment_start_date'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_comment_end_date')) { ?>
                <p class="red">掲載終了日<?php echo $e->getMessage('top_comment_end_date'); ?></p>
<?php } ?>
<?php if (isset($e) && $e->hasErrorForId('top_comment_date_check')) { ?>
                <p class="red">掲載終了日<?php echo $e->getMessage('top_comment_date_check'); ?></p>
<?php } ?>

                <ul class="register_item" id="comment_data_item">
<?php if($cmm002Out->sp_list_kind() === Sgmov_View_Cmm_Common::SP_LIST_KIND_COMMENTS){ ?>
                    <li id="comment_data_item_01" class="clearfix">
                        <fieldset>
                            <legend>タイトル</legend>
                            <input autocapitalize="off"<?php if (isset($e) && $e->hasErrorForId('top_comment_title')) { echo ' class="bg_red"'; } ?> id="comment_title" name="comment_title" type="text" value="<?php echo $cmm002Out->comment_title(); ?>" />
                        </fieldset>
                    </li>
                    <li id="comment_data_item_02" class="clearfix">
                        <fieldset>
                            <legend>住所</legend>
                            <input autocapitalize="off"<?php if (isset($e) && $e->hasErrorForId('top_comment_address')) { echo ' class="bg_red"'; } ?> id="comment_address" name="comment_address" type="text" value="<?php echo $cmm002Out->comment_address(); ?>" />
                        </fieldset>
                        <input id="comment_office" name="comment_office" type="hidden" value="" />
                    </li>
<?php } elseif ($cmm002Out->sp_list_kind() === Sgmov_View_Cmm_Common::SP_LIST_KIND_ATTENTION) { ?>
                    <li id="comment_data_item_03" class="clearfix">
                        <input id="comment_title" name="comment_title" type="hidden" value="" />
                        <input id="comment_address" name="comment_address" type="hidden" value="" />
                        <fieldset>
                            <legend>営業所</legend>
                            <select <?php if (isset($e) && $e->hasErrorForId('top_comment_office')) { echo ' class="bg_red"'; } ?> id="comment_office" name="comment_office" size="1" onChange="">
                                <option value=""></option>
                                <?php
                                  echo Sgmov_View_Cmm_Input::_createPulldown($cmm002Out->comment_office_cds(), $cmm002Out->comment_office_lbls(), $cmm002Out->comment_office());
                                ?>
                            </select>
                        </fieldset>
                    </li>
<?php } ?>
                    <li id="comment_data_item_04" class="clearfix">
                        <fieldset>
                            <legend>氏名</legend>
                            <input autocapitalize="off"<?php if (isset($e) && $e->hasErrorForId('top_comment_name')) { echo ' class="bg_red"'; } ?> id="comment_name" name="comment_name" type="text" value="<?php echo $cmm002Out->comment_name(); ?>" />
                        </fieldset>
                    </li>
                    <li id="comment_data_item_05" class="clearfix">
                        <fieldset>
                            <legend>コメント</legend>
                            <textarea autocapitalize="off"<?php if (isset($e) && $e->hasErrorForId('top_comment_text')) { echo ' class="bg_red"'; } ?> id="comment_text" name="comment_text" rows="4" cols="40"><?php echo $cmm002Out->comment_text(); ?></textarea>
                        </fieldset>
                    </li>
                    <li id="comment_data_item_06" class="clearfix">
                        <fieldset>
                            <legend>写真[1]</legend>
<!--[if (gte IE 10)|!(IE)]><!-->
                            <div class="img_input">
<!--<![endif]-->
<!--[if lt IE 10]>
                            <div>
<!--<![endif]-->
                                <input accept="image/png,image/gif,image/jpeg" id="comment_file_1" name="comment_file_1" type="file" />
                                <br /><br />
                                <img alt="" class="img_view" src="<?php echo $cmm002Out->comment_file_1(); ?>" />
                            </div>
                        </fieldset>
                    </li>
                    <li id="comment_data_item_07" class="clearfix">
                        <fieldset>
                            <legend>写真[2]</legend>
<!--[if (gte IE 10)|!(IE)]><!-->
                            <div class="img_input">
<!--<![endif]-->
<!--[if lt IE 10]>
                            <div>
<!--<![endif]-->
                                <input accept="image/png,image/gif,image/jpeg" id="comment_file_2" name="comment_file_2" type="file" />
                                <br /><br />
                                <img alt="" class="img_view" src="<?php echo $cmm002Out->comment_file_2(); ?>" />
                            </div>
                        </fieldset>
                    </li>
                    <li id="comment_data_item_08" class="clearfix">
                        <fieldset>
                            <legend>掲載開始日</legend>
                            <input autocapitalize="off" class="<?php if (isset($e) && ($e->hasErrorForId('top_comment_start_date') || $e->hasErrorForId('top_comment_date_check')) ) { echo 'bg_red '; } ?>datepicker" id="comment_start_date" name="comment_start_date" type="text" value="<?php echo $cmm002Out->comment_start_date(); ?>" />
                        </fieldset>
                    </li>
                    <li id="comment_data_item_09" class="clearfix">
                        <fieldset>
                            <legend>掲載終了日</legend>
                            <input autocapitalize="off" class="<?php if (isset($e) && ($e->hasErrorForId('top_comment_end_date')|| $e->hasErrorForId('top_comment_date_check')) ) { echo 'bg_red '; } ?>datepicker" id="comment_end_date" name="comment_end_date" type="text" value="<?php echo $cmm002Out->comment_end_date(); ?>" />
                        </fieldset>
                    </li>
                </ul>
                <input id="comment_id" name="comment_id" type="hidden" value="<?php echo $cmm002Out->comment_id(); ?>" />
                <input name="ticket" type="hidden" value="<?php echo $ticket; ?>" />
                <input id="cmm_list_king" name="cmm_list_king" type="hidden" value="<?php echo $cmm002Out->sp_list_kind(); ?>" />
            </div>
            <img alt="一覧に戻る" id="back_list" src="/common/img/cruise/btn_back_list.png" />
            <img alt="初期値に戻す" id="back_default" src="/common/img/cruise/btn_default.png" />
            <img alt="登録する" id="register" src="/common/img/cruise/btn_register.png" />
        </form>

    </div>
    <!-- /#topWrap -->
    <div id="footer">
        <address><img src="/common/img/txt_copyright.png" alt="&copy; SG Moving Co.,Ltd. All Rights Reserved." /></address>
    </div>
    <!-- /#footer -->
    <!--[if lt IE 9]>
    <script charset="UTF-8" type="text/javascript" src="/common/js/jquery-1.12.4.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/lodash-compat.min.js"></script>
    <![endif]-->
    <!--[if gte IE 9]><!-->
    <script charset="UTF-8" type="text/javascript" src="/common/js/jquery-3.1.1.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/lodash.min.js"></script>
    <!--<![endif]-->
    <script charset="UTF-8" type="text/javascript" src="/common/js/jquery-ui.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/jquery.ui.datepicker-ja.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/cmmInput.js"></script>
</body>
</html>