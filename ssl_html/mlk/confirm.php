<?php
/**
 * コミケサービスのお申込み確認画面を表示します。
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
/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('mlk/Confirm');

/**#@-*/

// 処理を実行
$view = new Sgmov_View_Eve_Confirm();
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
$eveOutForm = $forms['outForm'];
$dispItemInfo = $forms['dispItemInfo'];
$hachakutenInfo = $forms['hachakutenInfo'];



/**
 * エラーフォーム
 * @var Sgmov_Form_Error
 */

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
    <title>手荷物当日配送サービスのお申込みフォームの確認</title>
<?php
    // キャッシュ対策
    $sysdate = new DateTime();
    $strSysdate = $sysdate->format('YmdHi');
?>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <link href="/mlk/css/mlk.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <script src="/js/ga.js" type="text/javascript"></script>
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
            <p class="sentence">
                ご入力内容をご確認のうえ、「入力内容を送信する」ボタンを押してください。
                <br />修正する場合は「修正する」ボタンを押してください。
            </p>

            <div class="section">
                <form action="/mlk/complete" data-feature-id="<?php echo Sgmov_View_Eve_Common::FEATURE_ID ?>" data-id="<?php echo Sgmov_View_Eve_Common::GAMEN_ID_EVE001 ?>" method="post">
                    <input name="ticket" type="hidden" value="<?php echo $ticket ?>" />
                    <input name="comiket_id" type="hidden" value="<?php echo $eveOutForm->comiket_id(); ?>" />
                    <div class="section">
                        <div class="dl_header">●お預かり/お届け日</div>
                        <div class="dl_block comiket_block">
                            <dl>
                                <dt id="delivery_date">
                                    お預かり/お届け日
                                </dt>
                                <dd>
                                    <?php echo $eveOutForm->delivery_date_store;  ?>
                                </dd>
                            </dl>
                        </div>
                        <div class="dl_header">●お預け先(From)</div>
                        <div class="dl_block comiket_block">
                            <dl>
                                <dt id="comiket_id">
                                    申込番号
                                </dt>
                                <dd>
                                    <?php echo $eveOutForm->comiket_id();// $hachakutenInfo['hachakuten_shikibetu_cd'] ?> 
                                </dd>
                            </dl>
                            <dl>
                                <dt id="hotel_name">
                                    お預け先名称
                                </dt>
                                <dd>
                                    <?php echo $hachakutenInfo['name_jp'] ?>
                                </dd>
                            </dl>
                            
                            <dl>
                                <dt id="hotel_address">
                                    住所
                                </dt>
                                <dd>
                                    <?php echo $hachakutenInfo['address'] ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="hotel_tel">
                                    電話番号
                                </dt>
                                <dd>
                                    <?php echo $hachakutenInfo['tel'] ?>
                                </dd>
                            </dl>
                        </div>
                        <div class="dl_header">●お届け先(To)</div>
                        <div class="dl_block comiket_block">
                            <?php
                            
                            ?>
                            <dl>
                                <dt>
                                    お届け先の選択
                                </dt>
                                <dd>
                                    <?php echo $eveOutForm->addressee_type_name; ?>
                                    <br/>
                                    <br/>
                                    <span data-stt-ignore><?php echo $eveOutForm->hotel_service_airport_name; ?></span>
                                </dd>
                            </dl>
                            <dl>
                                <dt>
                                    住所
                                </dt>
                                <dd>
                                    <?php echo $eveOutForm->comiket_address_to; ?>
                                </dd>
                            </dl>
                            
                            <dl>
                                <dt>
                                    電話番号
                                </dt>
                                <dd>
                                    <?php echo $eveOutForm->comiket_tel_to; ?>
                                </dd>
                            </dl>
                            <?php if ($eveOutForm->addressee_type_sel() == Sgmov_View_Eve_Common::DELIVERY_TYPE_AIRPORT): ?>
                            <dl>
                                <dt>
                                    搭乗日時
                                </dt>
                                <dd>
                                     <?php echo date('Y/m/d', strtotime($eveOutForm->comiket_detail_delivery_date())); ?>&nbsp;<?php echo sprintf("%02d", $eveOutForm->comiket_detail_delivery_date_hour()) ; ?>時<?php echo sprintf("%02d", $eveOutForm->comiket_detail_delivery_date_min()) ; ?>分
                                </dd>
                            </dl>
                            <dl>
                                <dt>
                                    便名
                                </dt>
                                <dd>
                                    <span data-stt-ignore><?php echo $eveOutForm->comiket_detail_inbound_note(); ?></span>
                                </dd>
                            </dl>
                            <?php endif; ?>
                        </div>
                        <div class="dl_header">●ご利用者情報(Applicant information)</div>
                        <div class="dl_block comiket_block">
                            <dl>
                                <dt>
                                    名前
                                </dt>
                                <dd>
                                    <span data-stt-ignore><?php echo $eveOutForm->comiket_staff_sei(); ?>&nbsp;<?php echo $eveOutForm->comiket_staff_mei(); ?></span>
                                </dd>
                            </dl>


                            <dl>
                                <dt>
                                    電話番号
                                </dt>
                                <dd>
                                    <span data-stt-ignore><?php echo $eveOutForm->comiket_staff_tel(); ?></span>
                                </dd>
                            </dl>
                            
                            <dl>
                                <dt>
                                    メールアドレス
                                </dt>
                                <dd>
                                    <span data-stt-ignore><?php echo $eveOutForm->comiket_mail(); ?></span>
                                </dd>
                            </dl>
                            
                            
                            <dl>
                                <dt>
                                    備考
                                </dt>
                                <dd>
                                    <span data-stt-ignore><?php echo $eveOutForm->comiket_detail_inbound_note1(); ?></span>
                                </dd>
                            </dl>
                            
                        </div> 
                        <div class="dl_header header-size">●荷物情報</div>
                        <div class="dl_block comiket_block">
                            <dl>
                                <dt>
                                    サイズ
                                </dt>
                                <dd>
                                    <?php echo $eveOutForm->comiket_box_name; ?> 
                                </dd>
                            </dl>
                        </div>
                        <div class="dl_header header-payment">●お支払い方法</div>
                        <div class="dl_block comiket_block">
                            <dl>
                                <dt>
                                    お支払い方法
                                </dt>
                                <dd>
                                    クレジットカード
                                </dd>
                            </dl>
                        </div>
                        <div class="btn_area">
                            <a class="back" href="/mlk/input?tagId=<?php echo $eveOutForm->comiket_id();?>&back=1"> 修正する</a>
                        </div>

                        <h4 class="table_title">クレジットお支払い情報</h4>
                        <div class="dl_block">
                            <dl>
                                <dt>確定送料(仕分け特別料金含む)</dt>
                                <dd>
                                    <span data-stt-ignore>￥<?php echo number_format($eveOutForm->delivery_charge()).PHP_EOL; ?></span>
                                    <?php if (intval($eveOutForm->repeater_discount()) > 0) : ?>
                                        <span class="f80">※リピータ割引（<?php echo number_format($eveOutForm->repeater_discount()); ?>円）が適用されました</span>
                                    <?php endif; ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt>有効期限</dt>
                                <dd>
                                    <?php echo $eveOutForm->card_expire_year_cd_sel(); ?>年<?php echo $eveOutForm->card_expire_month_cd_sel(); ?>月
                                </dd>
                            </dl>
                            <dl>
                                <dt>カード番号</dt>
                                <dd>
                                    <?php echo str_repeat('*', strlen($eveOutForm->card_number())-4).substr($eveOutForm->card_number(), -4) .PHP_EOL; ?>
                                    <span class="f80">※下4桁のみの表示となります</span>
                                </dd>
                            </dl>
                            <dl>
                                <dt>セキュリティコード</dt>
                                <dd><?php echo $eveOutForm->security_cd(); ?></dd>
                            </dl>
                            <dl>
                                <dt>お支払い方法</dt>
                                <dd>1回</dd>
                            </dl>
                        </div>

                        <div class="btn_area">
                            <a class="back" href="/mlk/credit_card?back=1">修正する</a>
                            <input id="submit_button" type="submit" name="submit" value="入力内容を送信する" />
                        </div>
                    </div>
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

    <script>
        $(function() {
            $('input[name="submit"]').on('click', function () {
                if (!multiSend.block()) {
                    return false;
                }
                $('form').first().submit();
            });
        });
    </script>
    <!--自動翻訳用-->
    <link href="/mlk/css/trans.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
</body>
</html>

