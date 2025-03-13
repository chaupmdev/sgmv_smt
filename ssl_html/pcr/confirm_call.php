<?php
/**
 * 手荷物受付サービスのお申し込み確認画面を表示します。
 * @package    ssl_html
 * @subpackage PCR
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
//メンテナンス期間チェック
require_once dirname(__FILE__) . '/../../lib/component/maintain_pcr_call.php';
// 現在日時
$nowDate = new DateTime('now');
if ($main_stDate_pcr_call <= $nowDate && $nowDate <= $main_edDate_pcr_call) {
    header("Location: /maintenance.php");
    exit;
}
/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('pcr/Confirm');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Pcr_Confirm();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];

/**
 * フォーム
 * @var Sgmov_Form_Pcr003Out
 */
$pcr003Out = $forms['outForm'];
$paymentMethodNm = $view->payment_method_lbls[$pcr003Out->payment_method_cd_sel()];

?>
<!DOCTYPE html>
<html dir="ltr" lang="ja-jp" xml:lang="ja-jp">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0" />
    <meta http-equiv="content-style-type" content="text/css" />
    <meta http-equiv="content-script-type" content="text/javascript" />
    <meta name="Keywords" content="佐川急便,ＳＧムービング,sgmoving,無料見積り,お引越し,設置,法人,家具輸送,家電輸送,精密機器輸送,設置配送" />
    <meta name="Description" content="引越・設置のＳＧムービング株式会社のWEBサイトです。個人の方はもちろん、法人様への実績も豊富です。無料お見積りはＳＧムービング株式会社(フリーダイヤル:0570-056-006(受付時間:平日9時～17時))まで。" />
    <meta name="author" content="SG MOVING Co.,Ltd" />
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved." />
    <title>旅客手荷物受付サービスのお申し込みの確認│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css" rel="stylesheet" type="text/css" />
    <script src="/js/ga.js" type="text/javascript"></script>
</head>
<body>
<?php
    $gnavSettings = 'contact';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/header.php';
?>
    <div id="breadcrumb">
        <ul class="wrap">
            <li><a href="/">ホーム</a></li>
            <li><a href="/pcr/input/">お問い合わせ</a></li>
            <li class="current">旅客手荷物受付サービスのお申し込み</li>
        </ul>
    </div>
    <div id="main">
        <div class="wrap clearfix">
            <h1 class="page_title">旅客手荷物受付サービスのお申し込み</h1>
            <p class="sentence">
                ご入力内容をご確認のうえ、「入力内容を送信する」ボタンを押してください。
                <br />修正する場合は「修正する」ボタンを押してください。
            </p>
            <div class="section">
                <form action="/pcr/complete_call/" method="post">
                    <div class="section">
                        <input name="ticket" type="hidden" value="<?php echo $ticket ?>" />
                        <div class="dl_block">
                            <dl>
                                <dt>コールセンターオペレーターID</dt>
                                <dd>
                                    <?php echo $pcr003Out->call_operator_id().PHP_EOL; ?>
                                </dd>
                            </dl>
                            
                            
                            <dl>
                                <dt>船名</dt>
                                <dd><?php echo $pcr003Out->travel_agency(); ?></dd>
                            </dl>
                            <dl>
                                <dt>乗船日/下船日
                                    <br />
                                    <p>※表示のない港はお取り扱いはありません</p>
                                </dt>
                                
                                <dd><?php echo $pcr003Out->travel(); ?></dd>
                            </dl>
                            <dl>
                                <dt>
                                    船内のお部屋番号
                                    <br />
                                    <p>※半角英数字</p>
                                </dt>
                                <dd>
<?php
    if (empty($pcr003Out) || trim($pcr003Out->room_number()) === '') {
        echo '&nbsp;';
    } else {
        echo $pcr003Out->room_number();
    }
?>

                                </dd>
                            </dl>

                            <dl style="display: none;">
                                <dt>お名前</dt>
                                <dd>
                                    <?php echo $pcr003Out->surname().PHP_EOL; ?>
                                    <?php echo $pcr003Out->forename().PHP_EOL; ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt>お名前フリガナ</dt>
                                <dd>
                                    <?php echo $pcr003Out->surname_furigana().PHP_EOL; ?>
                                    <?php echo $pcr003Out->forename_furigana().PHP_EOL; ?>
                                </dd>
                            </dl>
                            <dl style="display: none">
                                <dt>同行のご家族人数<p>※ご本人を含む合計人数</p></dt>
                                <dd><?php echo $pcr003Out->number_persons(); ?></dd>
                            </dl>
                            <dl>
                                <dt>電話番号</dt>
                                <dd><?php echo $pcr003Out->tel(); ?></dd>
                            </dl>
                            <dl>
                                <dt>メールアドレス</dt>
                                <dd><?php echo $pcr003Out->mail(); ?></dd>
                            </dl>
                            <dl>
                                <dt>郵便番号</dt>
                                <dd><?php echo $pcr003Out->zip(); ?></dd>
                            </dl>
                            <dl>
                                <dt>都道府県</dt>
                                <dd><?php echo $pcr003Out->pref(); ?></dd>
                            </dl>
                            <dl>
                                <dt>市区町村</dt>
                                <dd><?php echo $pcr003Out->address(); ?></dd>
                            </dl>
                            <dl>
                                <dt>番地・建物名</dt>
                                <dd>
<?php
    if (empty($pcr003Out) || trim($pcr003Out->building()) === '') {
        echo '&nbsp;';
    } else {
        echo $pcr003Out->building();
    }
?>

                                </dd>
                            </dl>
                            <dl>
                                <dt>集荷の往復</dt>
                                <dd><?php echo $pcr003Out->terminal(); ?></dd>
                            </dl>
                            <dl>
                                <dt>
                                    配送荷物個数
                                    <br class="sp_only" />
                                    <p>※注文完了後は個数変更ができないため、変更する場合は再度入力をお願いいたします。</p>
                                </dt>
                                <dd>
<?php
    if ($pcr003Out->raw_departure_exist_flag) {
        echo $pcr003Out->departure_quantity().PHP_EOL;
    }
    if ($pcr003Out->raw_arrival_exist_flag) {
        echo $pcr003Out->arrival_quantity().PHP_EOL;
    }
?>
                                </dd>
                            </dl>
<?php
    if ($pcr003Out->raw_departure_exist_flag) {
?>

                            <dl>
                                <dt>出発地</dt>
                                <dd><?php echo $pcr003Out->travel_departure(); ?></dd>
                            </dl>
                            <dl>
                                <dt>
                                    集荷希望日時
                                    <br class="sp_only" />
                                    <p>※乗船日に合わせてお荷物を配送させて頂きます。</p>
                                </dt>
                                <dd>
                                    <?php echo $pcr003Out->cargo_collection_date().PHP_EOL; ?>
                                    <?php
                                        $wave_dash = '';
                                        if ($pcr003Out->cargo_collection_st_time() !== '時間帯指定なし') {
                                            $wave_dash = '～';
                                        }
                                        echo $pcr003Out->cargo_collection_st_time().$wave_dash.$pcr003Out->cargo_collection_ed_time().PHP_EOL;
                                    ?>
                                </dd>
                            </dl>

<?php
    }
    if ($pcr003Out->raw_arrival_exist_flag) {
?>

                            <dl>
                                <dt>到着地</dt>
                                <dd><?php echo $pcr003Out->travel_arrival(); ?></dd>
                            </dl>

<?php
    }
    if ($pcr003Out->payment_method_cd_sel() === '1') {
?>
                            

<?php
    }
?>
                            <dl>
                                <dt>確定送料</dt>
                                <dd><?php echo $pcr003Out->delivery_charge(); ?></dd>
                            </dl>
                            <?php
                                if ($pcr003Out->payment_method_cd_sel() === '2') {
                            ?>
                            <dl>
                                <dt>お支払い方法</dt>
                                <dd><?php echo $paymentMethodNm; ?></dd>
                            </dl>
                            <?php
                                } else {
                            ?>
                            <dl>
                                <dt>お支払い店舗</dt>
                                <dd><?php echo $pcr003Out->convenience_store(); ?></dd>
                            </dl>
                            <?php
                                } 
                            ?>
                        </div>
                    </div>
                    <div class="btn_area">
                        <a class="back" href="/pcr/input_call/">修正する</a>
                        <input id="submit_button" type="submit" name="submit" value="入力内容を送信する" />
                    </div>

                </form>
            </div>
        </div>
    </div>

<?php
    $footerSettings = 'under';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/footer.php';
?>

    <!--[if lt IE 9]>
    <script charset="UTF-8" type="text/javascript" src="/js/jquery-1.12.0.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/lodash-compat.min.js"></script>
    <![endif]-->
    <!--[if gte IE 9]><!-->
    <script charset="UTF-8" type="text/javascript" src="/js/jquery-2.2.0.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/lodash.min.js"></script>
    <!--<![endif]-->
    <script charset="UTF-8" type="text/javascript" src="/common/js/multi_send.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/common.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/pcr/js/confirm.js"></script>
</body>
</html>