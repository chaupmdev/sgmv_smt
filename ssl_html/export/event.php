<?php
/**
 * イベント選択画面表示
 * 
 * @package    ssl_html
 * @subpackage EXPORT
 * @author     DucPM31
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('export/Event');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Export_Event();
$forms = $view->execute();

/**
 * イベント情報
 * @var string
 */
$events = $forms['events'];
$eventInfos = $forms['eventInfos'];

/**
 * エラーフォーム
 * @var Sgmov_Form_Error
 */
$error = $forms['errorForm'];
$baseUrl = Sgmov_Component_Config::getUrlPublicSsl();
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
    <meta name="Description" content="イベント出力" />
    <meta name="author" content="SG MOVING Co.,Ltd" />
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved." />
    <title>イベント出力</title>
<?php
    // キャッシュ対策
    $sysdate = new DateTime();
    $strSysdate = $sysdate->format('YmdHi');
?>
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
</head>
<body>

<?php
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/header.php';
?>
    <div id="breadcrumb">
        <ul class="wrap">
            <li><a href="/">ホーム</a></li>
            <li class="current">イベント出力</li>
        </ul>
    </div>


    <div id="main">
        <div class="wrap clearfix">            
            <h1 class="page_title"  style="margin-bottom:15px !important;">イベント出力</h1>
            <div id="timeover" class="message_flame" style="display: none;">
            </div>
<?php
    if (isset($error)) {
?>
            <div class="err_msg">
                <p class="sentence br attention">[!ご確認ください]下記の項目が正しく入力・選択されていません。</p>
                <ul>
                    <?php 
                    // エラー表示
                    foreach($error->_errors as $key => $val) {
                        echo "<li><a href='#" . $key . "'>" . $val . '</a></li>';
                    }
                    ?>
                </ul>
            </div>
<?php
    }
?>
            <div class="section other">
<?php if (empty($events)) :  ?>
    <p>イベントがありません。</p>
<?php else: ?>
                <form action="/export/excel"  method="post">
                    <input type="hidden" name="event_name" id="event_name">
                    <div class="dl_block comiket_block">
                        <dl>
                            <dt>
                                イベント
                            </dt>
                            <dd>
                                <select name='event' id="event" >
                                    <option value="">選択してください</option>
<?php
    echo Sgmov_View_Export_Event::createEventPulldown($eventInfos["event_ids"], $eventInfos['event_names']);
?>
                                </select>
                            </dd>
                        </dl>
                        <dl>
                            <dt>
                                サブイベント
                            </dt>
                            <dd>
                                <select name='eventsub' id="eventsub">
                                    <option value="">選択してください</option>
                                </select>
                            </dd>
                        </dl>
                    </div>

                    <p class="text_center">
                        <input id="submit_button" type="submit" name="submit" value="出力">
                    </p>
                </form>
<?php endif; ?>
            </div>
        </div>
    </div>

    <!--main-->

<?php
    $footerSettings = 'under';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/footer.php';
?>

    <script charset="UTF-8" type="text/javascript" src="/js/jquery-2.2.0.min.js"></script>
    <script type="text/javascript">
        // ajaxでイベントサブのHTML生成
        $(function(){
            $("#event").change(function(){
                let event_sel=$(this).val();
                let event_name = '';
                if (event_sel) {
                    event_name = $(this).find(":selected").text();
                }
                $('#event_name').val(event_name);
                $('.err_msg').remove();
                var baseUrl = '<?php echo $baseUrl;?>';
                $.ajax({
			async: true,
			cache: false,
			data: {
                            'event_id': event_sel
                        },
			dataType: 'json',
			timeout: 60000,
			type: 'post',
			url: baseUrl + '/export/eventsub.php'
		}).done(function (data, textStatus, jqXHR) {
                    $('select#eventsub option').remove();
                    if (data['isSuccess'] === 1) {
                        $("#eventsub").append(data['pulldown']);
                    } else {
                        $("#eventsub").append('<option value="">選択してください</option>');
                    }
		}).fail(function (jqXHR, textStatus, errorThrown) {
			location.href = '/500.php';
		});
            });
        });
    </script>
</body>
</html>