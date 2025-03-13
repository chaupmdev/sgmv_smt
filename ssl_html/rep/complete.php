<?php
session_start();
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useAllComponents();
require_once 'DB.php';
require_once 'config.php';

Sgmov_Component_Log::info('############################################');
Sgmov_Component_Log::info('延長保証サービス修理受付-完了画面');
Sgmov_Component_Log::info('▼▼▼ POST ▼▼▼');
Sgmov_Component_Log::info($_POST);
Sgmov_Component_Log::info('');
Sgmov_Component_Log::info('▼▼▼ SESSION ▼▼▼');
Sgmov_Component_Log::info($_SESSION);
Sgmov_Component_Log::info('');
Sgmov_Component_Log::info('▼▼▼ SERVER ▼▼▼');
Sgmov_Component_Log::info($_SERVER);
Sgmov_Component_Log::info('');
Sgmov_Component_Log::info('############################################');

///////////////////////////////////////////////////////////////////////////////////////////
// クロスサイトチェック
///////////////////////////////////////////////////////////////////////////////////////////
$uniqueIdSession = @$_SESSION['uniqueId'];
$uniqueIdPost = @$_POST['uniqueId'];

if ($uniqueIdSession != $uniqueIdPost) {
    $_SESSION['error_info']['common_error'] = '再度入力画面にアクセスして、再入力してください。';
    $path = "/rep/input";
    echo "<script>location.href = '{$path}';</script>";
    exit;
}
///////////////////////////////////////////////////////////////////////////////////////////
$_POST = $_SESSION['post'];

$iraiIraisyaInfo = array(
    '10' => '加入者本人',
    '20' => '加入者家族',
    '90' => 'その他',
);

///////////////////////////////////////
// 機械名マスタ情報取得
///////////////////////////////////////
$sqlEquip = "select * from equipment order by equipment_cd";

$stmtEquip = $con->prepare($sqlEquip);

$flg = $stmtEquip->execute();
$iraiKikiInfo = array();
if ($flg) {
    while ($data = $stmtEquip->fetch(PDO::FETCH_ASSOC)) {
        $iraiKikiInfo[$data['equipment_cd']] = $data['equipment_nm'];
    }
}

$kanyuusyouno = @$_POST['kanyuusyouno']; // 保証書番号
$iraiIraisya = @$_POST['irai_iraisya']; // 修理依頼者区分
$iraiIraisyaSonota = @$_POST['irai_iraisya_sonota']; // 修理依頼者区分-その他の場合
$iraiName = @$_POST['irai_name']; // 修理依頼者名
$iraiMail = @$_POST['irai_mail']; // 保証書番号
$iraiKiki = @$_POST['irai_kiki']; // 機器名
$iraiMaker = @$_POST['irai_maker']; // メーカー名
$iraiKounyuuhi = @empty($_POST['irai_kounyuuhi']) ? null : $_POST['irai_kounyuuhi']; // 購入日
$iraiSeihinKeishiki = @$_POST['irai_seihin_keishiki']; // 製品形式
$iraiZikohi = @$_POST['irai_zikohi']; // 故障発生日
$iraiZikozyoukyou = @$_POST['irai_zikozyoukyou']; // 状況詳細
$iraiTel = @$_POST['irai_tel']; // 日中連絡がとれる電話番号

///////////////////////////////////////////////////////////////////////////////////////////
// DB　登録
///////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////
// シーケンス番号取得
//////////////////////////////////////
$sqlSeq = "select nextval('encho_hosho_id_seq')";


$stmtSeq = $con->prepare($sqlSeq);

$seq = "";
try {
    $flgSeq = $stmtSeq->execute();
    $data = $stmtSeq->fetch(PDO::FETCH_ASSOC);
    $seq = "" . (((int)$data['nextval']) +1) . "";
    if (@empty($flgSeq)) {
        throw new Exception();
    }
} catch (Exception $e) {
    Sgmov_Component_Log::err("DBの登録に失敗しました。");
    Sgmov_Component_Log::err($e->getMessage());
    Sgmov_Component_Redirect::redirectPublicSsl("/rep/error/?t=システムエラーが発生しました。&m=システムエラーが発生しました。再度入力画面からやり直してください。");
}

//////////////////////////////////////
// 延長保証サービステーブル登録
//////////////////////////////////////

$sql =
"
    insert into encho_hosho
    values (
         :id
        ,:encho_hosho_id
        ,:encho_hosho_no
        ,:repair_req_class
        ,:repair_req_class_sonota
        ,:repair_req_name
        ,:mail
        ,:equipment_cd
        ,:maker_name
        ,:purchase_date
        ,:katashiki
        ,:failure_date
        ,:situation_detail
        ,:day_tel
        ,NOW()
        ,NOW()
    )
";

$stmt = $con->prepare($sql);

$stmt->bindValue(':id', $seq);
$stmt->bindValue(':encho_hosho_id', $seq);
$stmt->bindValue(':encho_hosho_no', $kanyuusyouno); // 保証書番号
$stmt->bindValue(':repair_req_class', $iraiIraisya); // 修理依頼者区分
$stmt->bindValue(':repair_req_class_sonota', $iraiIraisyaSonota); // 修理依頼者区分・その他
$stmt->bindValue(':repair_req_name', $iraiName); // 修理依頼者名
$stmt->bindValue(':mail', $iraiMail); // メールアドレス
$stmt->bindValue(':equipment_cd', $iraiKiki); // 機器コード
$stmt->bindValue(':maker_name', $iraiMaker); // メーカ名
$stmt->bindValue(':purchase_date', $iraiKounyuuhi); // 購入日
$stmt->bindValue(':katashiki', $iraiSeihinKeishiki); // 製品形式
$stmt->bindValue(':failure_date', $iraiZikohi); // 故障発生日
$stmt->bindValue(':situation_detail', $iraiZikozyoukyou); // 状況詳細
$stmt->bindValue(':day_tel', $iraiTel); // 日中電話番号

try {
    $flag = $stmt->execute();
    if (!$flag) {
        throw new Exception();
    }
} catch (Exception $e) {
    Sgmov_Component_Log::err("DBの登録に失敗しました。");
    Sgmov_Component_Log::err($e->getMessage());
    Sgmov_Component_Redirect::redirectPublicSsl("/rep/error/?t=システムエラーが発生しました。&m=システムエラーが発生しました。再度入力画面からやり直してください。");
}

///////////////////////////////////////////////////////////////////////////////////////////
// メール送信
///////////////////////////////////////////////////////////////////////////////////////////

$mooushikomiNo = sprintf('%09d', $seq);

$iraiName2 = "{$iraiName}様　　";
$mooushikomiNo2 = "【修理管理番号：SG{$mooushikomiNo}】";

$mailTitle = "延長保証　修理受付のご連絡";
$mailBody = 
"
{$iraiName2}{$mooushikomiNo2}
延長保証サービス　修理受付センターでございます。
修理受付WEBフォームのご入力ありがとうございました。

以下の内容で受付いたしました。

-------------------------------------
受付内容
-------------------------------------
【申込番号】{$mooushikomiNo}
【保証書番号】{$kanyuusyouno}
【修理依頼者区分】{$iraiIraisyaInfo[$iraiIraisya]} {$iraiIraisyaSonota}
【修理依頼者名】{$iraiName}
【メールアドレス】{$iraiMail}
【機器名】{$iraiKikiInfo[$iraiKiki]}
【メーカー名】{$iraiMaker}
【購入日】{$iraiKounyuuhi}
【製品形式】{$iraiSeihinKeishiki}
【故障発生日】{$iraiZikohi}
【状況詳細】
{$iraiZikozyoukyou}
【日中ご連絡がとれる電話番号】{$iraiTel}

-------------------------------------
修理の流れ
-------------------------------------
修理担当者から3営業日以内にご連絡を差し上げますので、今しばらくお待ちください。
修理ご依頼品によって修理方法が異なります。
【大型家電・設備機器】　　出張修理のため技術担当の出張手配を行います。
【その他家電】　　　　　　延長保証　修理受付センターから引取り日時の確認についてご連絡いたします。
なお、修理ご依頼品の確認から修理見積金額確定までは、約7営業日を予定しております。
修理ご依頼品の故障個所を特定し、保証限度額の範囲内で修理可能な場合は、そのまま修理を実施いたします。
●保証適用外であった場合や予定修理代金が保証限度額を超過している場合等では、着手前にお客様にご負担いただく有償金額やお振込みの手続き等をメールにてお知らせいたします。
●修理キャンセルの場合でも、送料・出張費等はお客様負担となり、その際は料金のご案内やお振込みの手続き等をお知らせいたします。
あらかじめご了承ください。
-------------------------------------

＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
延長保証サービス　修理受付センター運営
ＳＧムービング株式会社
電話番号　0120-323-640　（受付時間:年末年始等の祝祭日を除く平日10時～17時）
＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
";

mb_language("Japanese");
mb_internal_encoding("UTF-8");

$mailFrom = MAIL_FROM;

// メールヘッダに送信元アドレスを設定
$header = "From: <".$mailFrom.">\r\n";

// 返送先アドレスの指定があればメールヘッダに設定
$header .= "Reply-To: <".$mailFrom.">\r\n";

// エラー時の返送先アドレスの指定があればメールヘッダに設定
//$header .= "Return-Path: <".$mailFrom.">\r\n";

// メールヘッダにその他情報を設定
$header .= "Date: ".date("r")."\r\n" .
            "Content-Type: text/plain; charset=ISO-2022-JP \r\n".
            "X-Mailer: PHP/" . phpversion() . "\r\n";

// sendMailコマンド実行時の引数を設定
// -f ： 送信元サーバのメールアドレスとして認識させるメールアドレスを設定する
//      この設定がないとスパムメールとして判断される場合がある
//$parameter = "-f ". 'sgmoving_system@sagawa-mov.co.jp';
$parameter = "-f ". $mailFrom;

$sendMailErr = "";
Sgmov_Component_Log::info('############################################');
///////////////////////////////////////////////
// 入力ユーザへ送信
///////////////////////////////////////////////
if(@mb_send_mail($iraiMail, $mailTitle, $mailBody, $header, $parameter)){
    Sgmov_Component_Log::info("入力ユーザへメールの送信に成功しました。");
} else {
    Sgmov_Component_Log::info("入力ユーザへメールの送信に失敗しました。");
}

///////////////////////////////////////////////
// 管理者へ送信
///////////////////////////////////////////////
$mailAdmin = MAIL_ADMIN;
$mailTitle = '【ＷＥＢ受付通知】延長保証　ＳＧ＋＋＋＋＋＋＋＋＋';
if(@mb_send_mail($mailAdmin, $mailTitle, $mailBody, $header, $parameter)){
    Sgmov_Component_Log::info("管理者へ送信へのメールの送信に成功しました。");
} else {
    Sgmov_Component_Log::info("管理者へ送信へのメールの送信に失敗しました。");
}

Sgmov_Component_Log::info('▼▼▼ メール内容 ▼▼▼');
Sgmov_Component_Log::info($mailTitle);
Sgmov_Component_Log::info($mailBody);
Sgmov_Component_Log::info('');
Sgmov_Component_Log::info('############################################');

// セッションクリア
$_SESSION = array();

?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="format-detection" content="telephone=no">
        <title>
            延長保証修理受付完了｜修理受付管理システム
        </title>
        <link rel="shortcut icon" href="/misc/favicon.ico" type="image/vnd.microsoft.icon" />

        <link rel="stylesheet" href="/rep/css/bootstrap.min.css">
        <link rel="stylesheet" href="/rep/css/style.css">
        <link rel="stylesheet" href="/rep/css/jquery-ui-1.7.2.custom.css">
        <link rel="stylesheet" href="/rep/css/bootstrap-clockpicker.min.css">
        <link rel="stylesheet" href="/rep/css/lightbox.min.css">
        <link rel="stylesheet" href="/rep/css/lity.min.css">
        <link rel="stylesheet" href="/rep/css/bootstrap-ympicker.min.css">
    </head>

    <body>
        <div class="header_fixed">
            <div class="header">
                <header class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <h1 class="text-center"><span class="spl20">延長保証修理受付完了</span></h1>
                        </div>
                    </div>
                </header>
            </div>
        </div>
        <div class="container contentsarea">
            <div class="form-horizontal spt20">
                <p style="font-size: 20px;font-weight: bold;">修理受付完了しました。</p>
                <p>入力したメールアドレスに受付完了メールを送りました。</p><br/>
                <p>修理管理番号：SG<?= $mooushikomiNo ?></p><br/>
                <a href="/rep/input">続けて申込をする</a><br/>
                <a href="/">トップ画面へ戻る</a><br/>
            </div>
            
            <div class="notes_inbox">
                <!--修理センター運営会社　日本リビング保証株式会社<br>-->
                延長保証サービス　修理受付センター運営<br>
                ＳＧムービング株式会社<br>
                電話番号　0120-323-640　（受付時間:年末年始等の祝祭日を除く平日10時～17時）<br>
            </div>
        </div>
        
        <footer class="container-fluid"><!--ここからフッター-->
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <p class="copy">© SG Moving Co.,Ltd. All Rights Reserved.</p>
                    </div>
                </div>
            </div>

            <p class="pagetop" style="display: none; position: fixed; bottom: 10px;">
                <a href="/rep/input">
                    <span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
                    TOPへ
                </a>
            </p>
        </footer><!--ここまでフッター-->
    </body>
</html>

