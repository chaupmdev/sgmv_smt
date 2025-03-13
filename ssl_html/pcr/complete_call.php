<?php
/**
 * 手荷物受付サービスのお申し込み完了画面を表示します。
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
Sgmov_Lib::useView('pcr/Complete');
Sgmov_Lib::useForms(array('Error', 'PcrSession'));
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Pcr_Complete();
$forms = $view->execute();

/**
 * フォーム
 * @var Sgmov_Form_Pcr004Out
 */
$pcr004Out = $forms['outForm'];
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
    <title>旅客手荷物受付サービスのお申し込みフォームの完了│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
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
            <li><a href="/contact/">お問い合わせ</a></li>
            <li class="current">旅客手荷物受付サービスのお申し込みフォームの完了</li>
        </ul>
    </div>

    <div id="main">
        <div class="wrap clearfix">
            <h1 class="page_title">旅客手荷物受付サービスのお申し込みフォームの完了</h1>
            <div class="section">

<?php
    if ($pcr004Out->merchant_result() === '0') {
        if ($pcr004Out->payment_method_cd_sel() === '2') {
?>
                <h2 class="complete_msg">
                    クレジットカードでの決済が
                    <br />できませんでした。
                </h2>
<?php
        } else {
?>
                <h2 class="complete_msg">
                    コンビニでの決済が
                    <br />できませんでした。
                </h2>
<?php
        }
    } else {
?>
                <h2 class="complete_msg">
                    旅客手荷物受付サービスのお申し込み、
                    <br />ありがとうございました。
                </h2>
<?php
    }
    if ($pcr004Out->merchant_result() === '1' && $pcr004Out->payment_method_cd_sel() === '1') {
?>

                <p class="sentence btm30">
                    コンビニ決済の受付番号は以下の通りです。

                <table class="default_tbl">
                    <tr>
                        <th scope="row">受付番号</th>
                        <td><?php echo $pcr004Out->receipt_cd(); ?></td>
                    </tr>

<?php
        if ($pcr004Out->payment_url() !== '') {
?>
                    <tr>
                        <th scope="row">払込票URL</th>
                        <td>
                            <a href="<?php echo $pcr004Out->payment_url(); ?>" target="_blank"><?php echo $pcr004Out->payment_url(); ?></a>
                        </td>
                    </tr>
<?php
        }
?>

                </table>
                

<?php
            switch ($pcr004Out->convenience_store_cd_sel()) {
                case '1':
?>
                    <br />※受付番号をメモまたは払込票を印刷し、
                    <br />セブンイレブンのレジカウンターにてお支払い手続きをお願いいたいます。          
<?php
                    break;

                case '2':
?>
                <br />※受付番号を控えて、コンビニ備え付けの端末でお支払い手続きをお願いいたします。
                <br />端末操作方法は、各コンビニエンスストアのホームページにてご確認ください。
                <ul class="cnv_list">
                    <li>・ローソン</li>
                    <li>・セイコーマート</li>
                    <li>・ファミリーマート</li>
                    <li>・ミニストップ</li>
                </ul>
<?php
                    break;

                case '3':
?>
                    <br />※受付番号をメモまたは払込票を印刷し、
                    <br />デイリーヤマザキ店頭レジにてお支払い手続きをお願いいたいます。
<?php
                    break;

                default:
                    break;
            }
        }
?>
                

<?php
    if ($pcr004Out->merchant_result() === '1') {
?>
                <p class="sentence br">
                    決済完了された時点で、ご記入いただいたメールアドレス<!--[<?php echo $pcr004Out->mail(); ?>]-->宛に自動でメールを送らせていただいています。
                    <br />お申し込みから24時間以内に届かない場合はメールアドレスが間違っているか登録に失敗している可能性がありますので、
                    <br />お手数ですがお電話などでお知らせください。
                </p>
                <p class="sentence btm30">
                    今後ともどうぞよろしくお願いします。
                </p>
<?php
    } else {
?>
                <p class="sentence btm30">
                    インターネットお申込みについてのお問合せは、
                    <br />SGムービング株式会社TOKYO BASE　　TEL：03-6850-7828　（受付時間平日9時～17時）
                    <br />メールでのお問合せ・ご質問については、<a data-inquiry-type="10" href="/pin/input">こちら</a>からお願いします。
                </p>
<?php
    }
?>

                <div class="btn_area">
                    <a class="next" href="/pcr/input_call">申し込み画面へ戻る</a>
                </div>
            </div>

        </div>
    </div>
    <!--main-->

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
</body>
</html>