<?php
/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('ptu/Complete');
Sgmov_Lib::useForms(array('Error', 'PtuSession'));
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Ptu_Complete();
$forms = $view->execute();

/**
 * フォーム
 * @var Sgmov_Form_Ptu004Out
 */
$ptu004Out = $forms['outForm'];

/**
* 便種
* @var string
*/
$binshu = $ptu004Out->binshu_cd();

    //タイトル
    if ($binshu == '906') {
        $title = '単品輸送プランのお申し込みフォームの完了';
        $comment = '単品輸送プランのお申し込み、';
    } else {
        $title = '単身カーゴプランのお申し込みフォームの完了';
        $comment = '単身カーゴプランのお申し込み、';
    }
?>
<!DOCTYPE html>
<html dir="ltr" lang="ja-jp" xml:lang="ja-jp">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0" />
	<meta name="Keywords" content="" />
	<meta name="Description" content="引越・設置のＳＧムービング株式会社のWEBサイトです。個人の方はもちろん、法人様への実績も豊富です。無料お見積りはＳＧムービング株式会社(フリーダイヤル:0570-056-006)まで。" />
	<title><?php echo $title; ?>│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
	<link rel="shortcut icon" href="/misc/favicon.ico" type="image/vnd.microsoft.icon" />
	<link href="/css/common.css" rel="stylesheet" type="text/css" />
	<link href="/css/plan.css" rel="stylesheet" type="text/css" />
	<link href="/css/pre.css" rel="stylesheet" type="text/css" />
	<link href="/css/form.css" rel="stylesheet" type="text/css" />
	<!--[if lt IE 9]>
	<script charset="UTF-8" type="text/javascript" src="/js/jquery-1.12.0.min.js"></script>
	<script charset="UTF-8" type="text/javascript" src="/js/lodash-compat.min.js"></script>
	<![endif]-->
	<!--[if gte IE 9]><!-->
	<script charset="UTF-8" type="text/javascript" src="/js/jquery-2.2.0.min.js"></script>
	<script charset="UTF-8" type="text/javascript" src="/js/lodash.min.js"></script>
	<!--<![endif]-->
	<script charset="UTF-8" type="text/javascript" src="/js/common.js"></script>
    <script src="/js/ga.js" type="text/javascript"></script>
</head>
<body>
<?php
	$gnavSettings = "contact";
	include_once($_SERVER['DOCUMENT_ROOT']."/parts/header.php");
?>
<div id="breadcrumb">
	<ul class="wrap">
		<li><a href="/">ホーム</a></li>
		<li><a href="/contact/">お問い合わせ</a></li>
		<li class="current"><?php echo $title; ?></li>
	</ul>
</div>
<div id="main">
	<div class="wrap clearfix">
		<h1 class="page_title"><?php echo $title; ?></h1>
<?php
    if ($ptu004Out->merchant_result() === '0') {
        if ($ptu004Out->payment_method_cd_sel() === '2') {
?>
				<h2 class="complete_msg">
                    クレジットカードでの決済が
                    <br />できませんでした。
                </h2>
<?php } else { ?>
		 		<h2 class="complete_msg">
                    コンビニでの決済が
                    <br />できませんでした。
                </h2>
<?php
  	}
    } else {
?>
		<h2 class="complete_msg">
             <?php echo $comment; ?>
              <br />ありがとうございました。
        </h2>

<?php }
	if ($ptu004Out->merchant_result() === '1' && $ptu004Out->payment_method_cd_sel() === '1') {
?>

        <p class="sentence btm30">
            コンビニ決済の受付番号は以下の通りです。

<?php
        if ($ptu004Out->payment_url() !== '') {
?>
            <br />コンビニのレジカウンターにて決済手続きをお願いいたします。

<?php
            switch ($ptu004Out->convenience_store_cd_sel()) {
                case '1':
?>
            <br />
            <a href="<?php echo Sgmov_Component_Config::getUrlPublicHttp(); ?>/cvs/pc/711.html" target="_blank">手続き方法はこちらをご参照ください。</a>
<?php
                    break;
                case '2':
                    break;
                case '3':
?>
            <br />
            <a href="<?php echo Sgmov_Component_Config::getUrlPublicHttp(); ?>/cvs/pc/dailyamazaki.html" target="_blank">手続き方法はこちらをご参照ください。</a>
<?php
                    break;
                default:
                    break;
            }
        } else {
?>
            <br />コンビニ備え付けの端末にて支払い手続きをお願いいたします。
            <br />端末の操作方法は下記をご参照ください。

<?php
            switch ($ptu004Out->convenience_store_cd_sel()) {
                case '1':
                    break;
                case '2':
?>
            <br />
            <a href="<?php echo Sgmov_Component_Config::getUrlPublicHttp(); ?>/cvs/pc/lawson.html" target="_blank">ローソン</a>
            <br />
            <a href="<?php echo Sgmov_Component_Config::getUrlPublicHttp(); ?>/cvs/pc/seicomart.html" target="_blank">セイコーマート</a>
            <br />
            <a href="<?php echo Sgmov_Component_Config::getUrlPublicHttp(); ?>/cvs/pc/famima2.html" target="_blank">ファミリーマート</a>
            <br />
            <a href="<?php echo Sgmov_Component_Config::getUrlPublicHttp(); ?>/cvs/pc/circleksunkus_econ.html" target="_blank">サークルＫサンクス</a>
            <br />
            <a href="<?php echo Sgmov_Component_Config::getUrlPublicHttp(); ?>/cvs/pc/ministop_loppi.html" target="_blank">ミニストップ</a>
<?php
                    break;
                case '3':
?>
            <br />
            <a href="<?php echo Sgmov_Component_Config::getUrlPublicHttp(); ?>/cvs/pc/dailyamazaki.html" target="_blank">デイリーヤマザキ</a>
<?php
                    break;
                default:
                    break;
            }
        }
?>

        </p>


        <table class="default_tbl">
                    <tr>
                        <th scope="row">受付番号</th>
                        <td><?php echo $ptu004Out->receipt_cd(); ?></td>
                    </tr>

<?php
        if ($ptu004Out->payment_url() !== '') {
?>
                    <tr>
                        <th scope="row">払込票URL</th>
                        <td>
                            <a href="<?php echo $ptu004Out->payment_url(); ?>" target="_blank"><?php echo $ptu004Out->payment_url(); ?></a>
                        </td>
                    </tr>
<?php
        }
?>

        </table>
<?php
    }
?>



<?php
    if ($ptu004Out->merchant_result() === '1') {
?>
        <p class="sentence btm30">
            お申し込み内容の登録が完了いたしましたら、ご記入いただいたメールアドレス[<?php echo $ptu004Out->mail(); ?>]宛に自動でメールを送らせていただいています。
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
            インターネットでお申し込みが出来なかった場合は、4月6日より下記SGムービングクルーズ専用ダイヤル2にて、お申し込みを受付致します。
            <br/>インターネットお申し込みについてのご質問については、※<a class="text_link" data-inquiry-type="10" href="https://sagawa-mov-test.media-tec.jp:44304/pin/">こちら</a>からのみなります。</p>

		<p class="border_box">
            TEL：<span class="b">0120-35-4192</span>(固定電話から)
            <br/>　　　<span class="b">03-5763-9188</span>(携帯電話から)
            <br/>　　(土日祝祭日含む9:00～17:00)
        </p>
<?php
    }
?>
        <div class="btn_area">
            <a class="next" href="<?php echo Sgmov_Component_Config::getUrlPublicHttp(); ?>/">ホームへ戻る</a>
        </div>
	</div>
</div>
</body>
</html>