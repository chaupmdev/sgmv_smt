<?php
/**
 * 品質選手権アンケート入力完了画面を表示します。
 * @package    ssl_html
 * @subpackage HSK
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/* * #@+
 * include files
 */

require_once dirname(__FILE__) . '/../../lib/Lib.php';

Sgmov_Lib::useView('hsk/End');
//echo 'test1';
//exit;

/* * #@- */

// 処理を実行
$view = new Sgmov_View_Hsk_End();
//echo 'test1';
//exit;
$result = $view->execute();

//$outInfo = $result['outInfo'];
//echo 'test1';
//exit;

?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <?php require_once './parts/html_head.php'; ?>
        <link href="/hsk/css/hsk.css?v=2.0.6" rel="stylesheet" />
    </head>
    <body>
        <?php require_once './parts/page_head.php'; ?>
        <div class="main main-raised">
            <div class="container">
                <div id="section-vote">
                    <div class="title border-bottom" style="padding: 10px;">
                        <h4 style="font-weight: normal;">
                            <span style="font-weight: 600;">
                                ご協力ありがとうございました。
                            </span>
                        </h4>
                    </div>
                </div>
            </div><!-- container -->
        </div><!-- main -->
        <?php require_once './parts/page_footer.php'; ?>
        <script src="/hsk/js/hsk.js?v=1.0.0" type="text/javascript"></script>
        <script src="/hsk/js/modal_conf.js?v=1.0.0" type="text/javascript"></script>
    </body>
</html>