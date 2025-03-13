<?php
session_start();

require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useAllComponents();
require_once 'DB.php';

Sgmov_Component_Log::info('############################################');
Sgmov_Component_Log::info('延長保証サービス修理受付-入力チェック');
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

// チェック処理
$errorInfo = array();

///////////////////////////////////////////////////////////////////////////////////////////
// クロスサイトチェック
///////////////////////////////////////////////////////////////////////////////////////////
$uniqueIdSession = $_SESSION['uniqueId'];
$uniqueIdPost = @$_POST['uniqueId'];

if ($uniqueIdSession != $uniqueIdPost) {
    $_SESSION['error_info']['common_error'] = '再度入力画面にアクセスして、再入力してください。';
    $path = "/rep/input";
    echo "<script>location.href = '{$path}';</script>";
    exit;
}

///////////////////////////////////////////////////////////////////////////////////////////
// 保証書番号
///////////////////////////////////////////////////////////////////////////////////////////

$kanyuusyouno = @$_POST['kanyuusyouno'];
if (@empty($kanyuusyouno)) {
    $errorInfo['kanyuusyouno'] = '必須入力項目です。';
}
if (@empty($errorInfo['kanyuusyouno'])  && 11 < mb_strlen($kanyuusyouno)) {
    $errorInfo['kanyuusyouno'] = '11桁まで入力可能です。';
}


///////////////////////////////////////////////////////////////////////////////////////////
// 修理依頼者区分
///////////////////////////////////////////////////////////////////////////////////////////

$iraiIraisya = @$_POST['irai_iraisya'];
if ($iraiIraisya != "10" && $iraiIraisya != "20" && $iraiIraisya != "90") {
    // 10 => 加入者本人
    // 20 => 加入者家族
    // 90 => その他
    $errorInfo['irai_iraisya'] = '選択した内容をご確認ください。';
}

///////////////////////////////////////////////////////////////////////////////////////////
// 修理依頼者区分-その他の場合
///////////////////////////////////////////////////////////////////////////////////////////

if (@$iraiIraisya == '90') { // その他の場合
    $iraiIraisyaSonota = @$_POST['irai_iraisya_sonota'];
    if (@empty($iraiIraisyaSonota)) {
        $errorInfo['irai_iraisya_sonota'] = '必須入力項目です。';
    }
    if (@empty($errorInfo['irai_iraisya_sonota'])  && 50 < mb_strlen($iraiIraisyaSonota)) {
        $errorInfo['irai_iraisya_sonota'] = '50桁まで入力可能です。';
    }
}

///////////////////////////////////////////////////////////////////////////////////////////
// 修理依頼者名
///////////////////////////////////////////////////////////////////////////////////////////

$iraiName = @$_POST['irai_name'];
if (@empty($iraiName)) {
    $errorInfo['irai_name'] = '必須入力項目です。';
}
if (@empty($errorInfo['irai_name'])  && 100 < mb_strlen($iraiName)) {
    $errorInfo['irai_name'] = '100桁まで入力可能です。';
}

///////////////////////////////////////////////////////////////////////////////////////////
// メールアドレス
//////////////////////////////////////////////////////////////////////////////////////////

$iraiMail = @$_POST['irai_mail'];
if (@empty($iraiMail)) {
    $errorInfo['irai_mail'] = '必須入力項目です。';
}

if (@empty($errorInfo['irai_mail']) && 100 < mb_strlen($iraiMail)) {
    $errorInfo['irai_mail'] = '100桁まで入力可能です。';
}

if (@empty($errorInfo['irai_mail']) && !preg_match('/^\s*[ -~]+@\w+[\w\.-]*\.\w{2,4}\s*$/u', $iraiMail)) {
    $errorInfo['irai_mail'] = 'メールアドレスの入力が不正です。';
}

///////////////////////////////////////////////////////////////////////////////////////////
// 機器名
//////////////////////////////////////////////////////////////////////////////////////////

$iraiKiki = @$_POST['irai_kiki'];
if (@empty($iraiKiki)) {
    $errorInfo['irai_kiki'] = '必須入力項目です。';
}

///////////////////////////////////////
// 機械名マスタ情報取得
///////////////////////////////////////
$sqlEquip = "select * from equipment order by equipment_cd";

$stmtEquip = $con->prepare($sqlEquip);

$flg = $stmtEquip->execute();
$iraiKikiInfoList = array();
if ($flg) {
    while ($data = $stmtEquip->fetch(PDO::FETCH_ASSOC)) {
        $iraiKikiInfoList[$data['equipment_cd']] = $data['equipment_nm'];
    }
}

if (@empty($errorInfo['irai_kiki'])) {
    if (@!array_key_exists($iraiKiki, $iraiKikiInfoList)) {
        $errorInfo['irai_kiki'] = '選択した内容をご確認ください。';
    }
}

///////////////////////////////////////////////////////////////////////////////////////////
// メーカー名
//////////////////////////////////////////////////////////////////////////////////////////

$iraiMaker = @$_POST['irai_maker'];
if (@empty($iraiMaker)) {
    $errorInfo['irai_maker'] = '必須入力項目です。';
}
if (@empty($errorInfo['irai_maker'])  && 100 < mb_strlen($iraiMaker)) {
    $errorInfo['irai_maker'] = '100桁まで入力可能です。';
}

///////////////////////////////////////////////////////////////////////////////////////////
// 購入日
//////////////////////////////////////////////////////////////////////////////////////////

$iraiKounyuuhi = @$_POST['irai_kounyuuhi'];

if (@empty($iraiKounyuuhi)) {
    // 購入日 かつ 製品形式 が未入力の場合
    $errorInfo['irai_kounyuuhi'] = '必須入力項目です。';
}

$todate = date('Y/m/d');
if (@empty($errorInfo['irai_kounyuuhi']) && @!strptime($iraiKounyuuhi, '%Y/%m/%d')) {
    $errorInfo['irai_kounyuuhi'] = 'フォーマットが違います。(YYYY/MM/DD)';
}

if (@empty($errorInfo['irai_kounyuuhi']) && 
        !('2009/04/01' <= $iraiKounyuuhi && $iraiKounyuuhi <= $todate)) {
    $errorInfo['irai_kounyuuhi'] = "2009/04/01 から {$todate} まで選択可能です。";
}

///////////////////////////////////////////////////////////////////////////////////////////
// 製品型式
//////////////////////////////////////////////////////////////////////////////////////////

$iraiSeihinKeishiki = @$_POST['irai_seihin_keishiki'];

if (@empty($iraiSeihinKeishiki)) {
    // 購入日 かつ 製品形式 が未入力の場合
    $errorInfo['irai_seihin_keishiki'] = '必須入力項目です。';
}

if (@empty($errorInfo['irai_seihin_keishiki'])  && 50 < mb_strlen($iraiSeihinKeishiki)) {
    $errorInfo['irai_seihin_keishiki'] = '50桁まで入力可能です。';
}

//////////////////////////////////////////////////////////////////////////////////////////
// 故障発生日
//////////////////////////////////////////////////////////////////////////////////////////

$iraiZikohi = @$_POST['irai_zikohi'];
if (@empty($iraiZikohi)) {
    $errorInfo['irai_zikohi'] = '必須入力項目です。';
}

if (@empty($errorInfo['irai_zikohi']) && @!strptime($iraiZikohi, '%Y/%m/%d')) {
    $errorInfo['irai_zikohi'] = 'フォーマットが違います。(YYYY/MM/DD)';
}

if (@empty($errorInfo['irai_zikohi']) && 
        !('2000/01/01' <= $iraiZikohi && $iraiZikohi <= $todate)) {
    $errorInfo['irai_zikohi'] = "2000/01/01 から {$todate} まで選択可能です。";
}

if (@empty($errorInfo['irai_zikohi']) && @empty($errorInfo['irai_kounyuuhi']) 
        && @!empty($iraiKounyuuhi)
        && $iraiZikohi < $iraiKounyuuhi) {
    $errorInfo['irai_zikohi'] = "購入日より過去日付になっています。";
}

//////////////////////////////////////////////////////////////////////////////////////////
// 状況詳細
//////////////////////////////////////////////////////////////////////////////////////////

$iraiZikozyoukyou = @$_POST['irai_zikozyoukyou'];
if (@empty($iraiZikozyoukyou)) {
    $errorInfo['irai_zikozyoukyou'] = '必須入力項目です。';
}
if (@empty($errorInfo['irai_zikozyoukyou'])  && 200 < mb_strlen($iraiZikozyoukyou)) {
    $errorInfo['irai_zikozyoukyou'] = '200文字まで入力可能です。';
}

//////////////////////////////////////////////////////////////////////////////////////////
// 日中連絡がとれる電話番号
//////////////////////////////////////////////////////////////////////////////////////////

$iraiTel = @$_POST['irai_tel'];
error_log(var_export($errorInfo, true));
if (@empty($iraiTel)) {
    $errorInfo['irai_tel'] = '必須入力項目です。';
}

if (@empty($errorInfo['irai_tel'])  && 50 < mb_strlen($iraiTel)) {
    $errorInfo['irai_tel'] = '50桁まで入力可能です。';
}

//////////////////////////////////////////////////////////////////////////////////////////

$_SESSION['post'] = $_POST;
$path = "";
if (@!empty($errorInfo)) {
    Sgmov_Component_Log::info('############################################');
    Sgmov_Component_Log::info('延長保証サービス修理受付-入力チェックエラーあり');
    Sgmov_Component_Log::info($errorInfo);
    Sgmov_Component_Log::info('############################################');
    
    $_SESSION['error_info'] = $errorInfo;
    $path = "/rep/input";
    echo "<script>location.href = '{$path}';</script>";
    exit;
}

Sgmov_Component_Log::info('############################################');
Sgmov_Component_Log::info('延長保証サービス修理受付-入力チェックエラーなし');
Sgmov_Component_Log::info('############################################');

?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="/rep/js/jquery.min.js"></script>
    </head>
    <body>
        <form method="post" action="/rep/complete" name="form1" id="form1">
            <input type="hidden" name="uniqueId" value="<?= $uniqueIdSession ?>" />
        </form>
        <script>$('#form1').submit();</script>
    </body>
</html>




