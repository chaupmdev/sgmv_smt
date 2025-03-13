<?php
/**
 * 催事・イベント配送受付お申込み入力画面を表示します。
 * @package    ssl_html
 * @subpackage EVE
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
//メンテナンス期間チェック
require_once dirname(__FILE__) . '/../../lib/component/maintain_event.php';
// 現在日時
$nowDate = new DateTime('now');
if ($main_stDate_ev <= $nowDate && $nowDate <= $main_edDate_ev) {
    header("Location: /maintenance.php");
    exit;
}

// Basic認証
require_once dirname(__FILE__) . '/../../lib/component/auth_mlk.php';

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('mlk/Input');

/**#@-*/

// 処理を実行
$view = new Sgmov_View_Eve_Input();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Eve001Out
 */
$eve001Out = $forms['outForm'];
$dispItemInfo = $forms['dispItemInfo'];

$hachakutenInfo = $forms['hachakutenInfo'];
$dataHachakutenAll = $forms['dataHachakutenAll'];
$isPassDate = $forms['isPassDate'];
$isChange = $forms['isChange'];
/**
 * エラーフォーム
 * @var Sgmov_Form_Error
 */
$e = $forms['errorForm'];

//error_log(var_export($e->_errors, true));

    // スマートフォン・タブレット判定
    $detect = new MobileDetect();
    $isSmartPhone = $detect->isMobile();
    if ($isSmartPhone) {
        $inputTypeEmail  = 'email';
        $inputTypeNumber = 'number';
    } else {
        $inputTypeEmail  = 'text';
        $inputTypeNumber = 'text';
    }
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
    <meta name="Description" content="手荷物当日配送サービスのお申込みのご案内です。" />
    <meta name="author" content="SG MOVING Co.,Ltd" />
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved." />
    <title>手荷物当日配送サービスのお申込み</title>
<?php
    // キャッシュ対策
    $sysdate = new DateTime();
    $strSysdate = $sysdate->format('YmdHi');
?>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <link href="/mlk/css/mlk.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <!--自動翻訳用-->
    <script src="https://d.shutto-translation.com/trans.js?id=9363"></script>
</head>
<body>
<?php
    $gnavSettings = 'contact';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/header.php';
?>
    
    <div id="breadcrumb">
        <ul class="wrap">
            <li><a href="/">ホーム</a></li>
            <li><a href="/contact/">お問い合わせ</a></li>
            <li class="current">手荷物当日配送サービスのお申込み</li>
        </ul>
    </div>
    <div id="main">
        <div class="wrap clearfix">
            <h1 class="page_title" style="margin-bottom:0;">手荷物当日配送サービスのお申込み</h1>
            <?php
                include_once dirname(__FILE__) . '/parts/trans.php';
            ?>
            <br/>
            <?php
    if (isset($e) && $e->hasError()) {
?>
            <br/>
            <br/>
            <div class="err_msg">
                <p class="sentence br attention">[!ご確認ください]下記の項目が正しく入力・選択されていません。</p>
                <ul>
<?php
        // エラー表示
        foreach($e->_errors as $key => $val) {
            echo "<li><a href='#" . $key . "'>" . $val . '</a></li>';
        }
?>

                </ul>
            </div>

<?php
    }
?>

            <div class="section other">
                <form action="/mlk/check_input" id="myForm" data-feature-id="<?php echo Sgmov_View_Eve_Common::FEATURE_ID ?>" data-id="<?php echo Sgmov_View_Eve_Common::GAMEN_ID_EVE001 ?>" method="post" class="myForm">
                    <input name="ticket" type="hidden" value="<?php echo $ticket ?>" />
                    <input name="input_mode" type="hidden" value="<?php echo $eve001Out->input_mode(); ?>" />
                    <input name='input_type_email' type='hidden' value='<?php echo $inputTypeEmail; ?>' />
                    <input name='input_type_number' type='hidden' value='<?php echo $inputTypeNumber; ?>' />
                    <input name='input_lang' type='hidden' value='jp' />
                    
                    <div class="section">
<?php
                        ///////////////////////////////////////////////
                        // 顧客情報入力エリア
                        ///////////////////////////////////////////////
                        include_once dirname(__FILE__) . '/parts/input_cstmr.php';

?>
                        <div class="accordion" style="padding-top: 10px;padding-bottom: 10px">
                            <div>
                                <p class="sentence">
                                    本サービスの運営・提供は佐川急便株式会社にてお受けいたします。なお、手荷物当日配送の受付システムはSGムービング株式会社にて開発・提供しております。
                                </p>
                                <p class="sentence">
                                    佐川急便株式会社とSGムービング株式会社はSGホールディングスグループの一員です。
                                </p>
                            </div>
                        </div>
                        <br>
<?php
                        ///////////////////////////////////////////////
                        // アテンションエリア
                        ///////////////////////////////////////////////
                        include_once dirname(__FILE__) . '/parts/input_attention_area.php';
?>
                    </div>
                    
                    <p class="text_center">      
                        <input id="submit_button" type="submit" name="submit" value="同意して次に進む（入力内容の確認）">                
                    </p>
                </form>
            </div>
        </div>
    </div>
    <!--main-->

<?php
    $footerSettings = 'under';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/footer.php';
?>

    
    <script charset="UTF-8" type="text/javascript" src="/js/jquery-2.2.0.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/lodash.min.js"></script>
    <!--<![endif]-->
    <script charset="UTF-8" type="text/javascript" src="/common/js/multi_send.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/common.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/smooth_scroll.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/ajax_searchaddress.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/jquery.ah-placeholder.js"></script>
<?php
    if (!$isSmartPhone) {
?>
    <script charset="UTF-8" type="text/javascript" src="/common/js/jquery.autoKana.js"></script>
<?php
    }
?>
    <script charset="UTF-8" type="text/javascript" src="/mlk/js/define.js" id="definejs-arg"
    data-G_DEV_INDIVIDUAL="<?php echo Sgmov_View_Eve_Common::COMIKET_DEV_INDIVIDUA; ?>"
    data-G_DEV_BUSINESS="<?php echo Sgmov_View_Eve_Common::COMIKET_DEV_BUSINESS; ?>"
    >
    </script>
    <script charset="UTF-8" type="text/javascript" src="/mlk/js/input.js?<?php echo $strSysdate; ?>"></script>
    
    <script type="text/javascript">
        const ADDRESS_TYPE_SELECT_NONE = 0;
        const ADDRESS_TYPE_SELECT_AIRPORT = 1;
        const ADDRESS_TYPE_SELECT_SERVICE = 2;
        const ADDRESS_TYPE_SELECT_HOTEL = 3;
        const dataHachakutenAll = <?php echo $dataHachakutenAll ?>;
        const selectType = $('select[name="addressee_type_sel"]');
        const EMPTY_SELECT_AIRPORT = '選択してください';
        const selectAirport = $('select[name="airport_sel"]');
        const selectCenter = $('select[name="sevice_center_sel"]');
        const deliverySearch = $('.delivery_search');
        const deliveryParent = $('#comiket_detail_delivery_date').parent();
        const noteParent = $('#comiket_detail_inbound_note').parent();
        const EMPTY_SELECT_SERVICE  = '選択してください';
        const EMPTY_SELECT_HOTEL  = '選択してください';
        const selectHotel =  $('select[name="hotel_sel"]');
        let arrayHotel = [];
        const btnHotelSearch =  $('input[name="btn_hotel_search"]');

        const hotelNm = $('input[name="hotel_nm"]');
            
        const selectTypeVal = selectType.val();
        let  selectedVal = '';
        if (selectTypeVal !== '') {
            selectedVal = '';
            if (selectTypeVal == ADDRESS_TYPE_SELECT_AIRPORT) {
                selectedVal = '<?php echo $eve001Out->airport_sel() ?>';
            } else if (selectTypeVal == ADDRESS_TYPE_SELECT_SERVICE) {
                selectedVal = '<?php echo $eve001Out->sevice_center_sel() ?>';
            } else if (selectTypeVal == ADDRESS_TYPE_SELECT_HOTEL) {
                selectedVal = '<?php echo $eve001Out->hotel_sel() ?>';
            }
        }
    </script>
    <script charset="UTF-8" type="text/javascript" src="/mlk/js/mlk.js?<?php echo $strSysdate; ?>"></script>
    <!--自動翻訳用-->
    <link href="/mlk/css/trans.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <script type="text/javascript">
        window.addEventListener("load", function() {
            let isPassDate = "<?php echo $isPassDate ?>";
            let isChange = "<?php echo $isChange ?>";
            if (isPassDate == "1" && isChange == "0") {
                if (confirm('申込時間を過ぎているため、お預かり/お届け日が変わります。よろしいですか？')) {
                    window.location.href = "/mlk/input?tagId=<?php echo $eve001Out->comiket_id();?>&back=1&change=1";
                } else {
                    window.location.href = "/mlk/input?tagId=<?php echo $eve001Out->comiket_id();?>";
                }
            }
        });
    </script>
</body>
</html>

