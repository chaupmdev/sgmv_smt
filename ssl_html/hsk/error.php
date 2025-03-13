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
                                <?php 
                                    $message = $_GET['m'];
                                ?>
                                <?php if (@empty($message)) : ?>
                                    エラーが発生しました。<br/>
                                    再度入力画面からやり直してください。<br/>
                                <?php else: ?>
                                    <?php echo @htmlspecialchars(urldecode($message)); ?>
                                <?php endif; ?>
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