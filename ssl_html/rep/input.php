<?php
session_start();
require_once 'DB.php';

/////////////////////////////////////////////////////////////////////////////////////////////////
// 画面内共通関数
/////////////////////////////////////////////////////////////////////////////////////////////////
function checkInputErr($checkData) {
    return @empty($checkData) ? "" : " form-error";
}
function outputErrMsg($outputData) {
    if (@empty($outputData)) {
        return "";
    }
    
    return 
"
        <div class='error-message'>{$outputData}</div>
";
}
function checkSelected($checkData, $val) {
    return $checkData == $val ? 'selected' : '';
}
/////////////////////////////////////////////////////////////////////////////////////////////////

$errorInfo = array();
$refere = $_SERVER['HTTP_REFERER'];
$uniqueId = "";
if(preg_match("/check_input?$/", $refere)){
    $_POST = $_SESSION['post'];
    $errorInfo = $_SESSION['error_info'];
    $uniqueId =  $_SESSION['uniqueId'];
} else {
    if(@preg_match("/complete?$/", $refere)){
        $errorInfo = @$_SESSION['error_info'];
        $_SESSION['error_info'] = array();
    } else {
        $_SESSION['error_info'] = array();
    }
    $_SESSION['post'] = array();
    $uniqueId = md5(date('YmdHis')) . str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');
    $_SESSION['uniqueId'] = $uniqueId;
}

if ($_SESSION['uniqueId'] != $uniqueId) {
    $_SESSION = array();
}

///////////////////////////////////////////////////////////////////////////////////////////
// DB情報取得
///////////////////////////////////////////////////////////////////////////////////////////

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

$iraiShukajikanInfoList = array();
$iraiShukajikanInfoList[0] = '以下よりお選びください。';
$iraiShukajikanInfoList[1] = '午前中（8時～12時）';
$iraiShukajikanInfoList[2] = '12時～14時';
$iraiShukajikanInfoList[3] = '14時～16時';
$iraiShukajikanInfoList[4] = '16時～18時';
$iraiShukajikanInfoList[5] = '18時～20時';
$iraiShukajikanInfoList[6] = '18時～21時';
$iraiShukajikanInfoList[7] = '19時～21時';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="format-detection" content="telephone=no">
        <title>
            延長保証修理受付｜修理受付管理システム
        </title>
        <link rel="shortcut icon" href="/misc/favicon.ico" type="image/vnd.microsoft.icon" />

        <link rel="stylesheet" href="/rep/css/bootstrap.min.css">
        <link rel="stylesheet" href="/rep/css/style.css">
        <link rel="stylesheet" href="/rep/css/jquery-ui-1.7.2.custom.css">
        <link rel="stylesheet" href="/rep/css/bootstrap-clockpicker.min.css">
        <link rel="stylesheet" href="/rep/css/lightbox.min.css">
        <link rel="stylesheet" href="/rep/css/lity.min.css">
        <link rel="stylesheet" href="/rep/css/bootstrap-ympicker.min.css">
        <script src="/rep/js/jquery.min.js"></script>
        <script src="/rep/js/jquery-ui.min.js"></script>
        <script src="/rep/js/bootstrap.min.js"></script>
        <script src="/rep/js/bootstrap-clockpicker.min.js"></script>
        <script src="/rep/js/jquery.ui.datepicker.js"></script>
        <script src="/rep/js/jquery.ui.datepicker-ja.js"></script>
        <script src="/rep/js/bootstrap-ympicker.js"></script>
        <script src="/rep/js/bootstrap-datepicker.ja.min.js"></script>
        <script src="/rep/js/lightbox.min.js"></script>
        <script src="/rep/js/original.js"></script>
        <script src="/rep/js/lity.min.js"></script>
        <style type="text/css">
            ::-ms-clear {display: none;}
        </style>
    </head>

    <body>
        <div class="header_fixed">
            <div class="header">
                <header class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <h1 class="text-center"><span class="spl20">延長保証　修理受付</span></h1>
                        </div>
                    </div>
                </header>
            </div>
        </div>        
        <script type="text/javascript">
            $(function () {

                // 購入日 Datepicker
                $("#irai_kounyuuhi").datepicker({
                    showOn: "focus",
                    changeMonth: true,
                    changeYear: true,
                    maxDate: 0,
                    minDate: new Date('2009/04/01'),
                    showButtonPanel: true,
                    yearRange: '2009:+0',
                });

                // 故障発生日 Datepicker
                $("#irai_zikohi").datepicker({
                    showOn: "focus",
                    changeMonth: true,
                    changeYear: true,
                    maxDate: 0,
                    showButtonPanel: true,
                    yearRange: '-20:+0',
                });

                // 製造年月 Datepicker
                $("#irai_seizou").ympicker({
                    changeYear: true,
                    format: 'yyyy/mm',
                    autoclose: true,
                    minViewMode: 'months',
                    language: 'ja',
                    endDate: new Date(),
                    clearBtn: true,
                });
                var oneYearFromNow = new Date();
                nextYearDate = oneYearFromNow.setFullYear(oneYearFromNow.getFullYear() + 1);
                // 集荷希望日 Datepicker
                $("#irai_shuka_kibobi").datepicker({
                    showOn: "focus",
                    changeMonth: true,
                    changeYear: true,
                    maxDate: nextYearDate,
                    minDate: new Date(),
                    showButtonPanel: true,
                    yearRange: '-0:+1',
                });

                (function () {
                    var old_fn = $.datepicker._updateDatepicker;
                    $.datepicker._updateDatepicker = function (inst) {
                        old_fn.call(this, inst);
                        var buttonPane = $(this).datepicker("widget").find(".ui-datepicker-buttonpane");
                        var buttonHtml = "<button type='button' class='ui-datepicker-clean ui-state-default ui-priority-primary ui-corner-all'>クリア</button>";
                        $(buttonHtml).appendTo(buttonPane).click(
                                function (ev) {
                                    $.datepicker._clearDate(inst.input);
                                });
                        $(".ui-datepicker-current").hide();
                    }
                })();

                /*
                 * バックスペースキー無効
                 */
                $(function () {
                    $(document).on('keydown', '#irai_kounyuuhi', function (e) {
                        if (e.keyCode === 8) {
                            return false;
                        }
                    });
                });

                $(function () {
                    $(document).on('keydown', '#irai_seizou', function (e) {
                        if (e.keyCode === 8) {
                            return false;
                        }
                    });
                });

                $(function () {
                    $(document).on('keydown', '#irai_zikohi', function (e) {
                        if (e.keyCode === 8) {
                            return false;
                        }
                    });
                });

                /*
                 * カレンダーアイコン処理
                 */
                $('.datepicker-div').on('click', function () {
                    $('#interconnection_date').val('');
                    $(this).children('.datepicker').focus();
                });

                // ファイル入力
                $(document).on('change', ':file', function () {
                    var input = $(this),
                            numFiles = input.get(0).files ? input.get(0).files.length : 1,
                            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                    input.parent().parent().next(':text').val(label);
                });

                // 依頼者区分その他テキストボックス制御
                $("#irai_iraisya").on("change", function () {
                    if ($("#irai_iraisya").val() == "90") {
                        $("#irai_iraisya_sonota").prop("disabled", false);
                    } else {
                        $("#irai_iraisya_sonota").prop("disabled", true);
                        $("#irai_iraisya_sonota").val("");
                    }
                });
                window.onload = function () {
                    if ($("#irai_iraisya").val() == "90") {
                        $("#irai_iraisya_sonota").prop("disabled", false);
                    } else {
                        $("#irai_iraisya_sonota").readOnly = false;
                        $("#irai_iraisya_sonota").val("");
                    }
                };
            });
        </script>

        <form method="post" accept-charset="utf-8" novalidate="novalidate" action="/rep/check_input">
            <input type="hidden" name="uniqueId" value="<?= $uniqueId ?>" />
            <!--<div style="display:none;">-->
            <input type="hidden" name="_method" value="POST">

            <!--<input type="hidden" name="_csrfToken" autocomplete="off" value="bf95e56d23683c85a0cfb57bd7428217cb835b3154c87df3eb4458bf1b4e402bdb46a8b14e83061273b733d5f52559211a45cec6da3f0960eccc0cdce17aaab3"></div>-->  
            <div class="container contentsarea">
                <div class="notes" style="line-height: 22px;margin-left: 55px">                    
                    ~~~~~~~~~~~~<strong>≪修理のご依頼前に確認いただきたい事項≫</strong>～～～～～～～～～<br>
                    下記の各事項をご確認頂いた上で、入力フォームより修理をご依頼をください。<br>
                    <strong>▶修理対応：　ご入力いただいた内容を確認の上、以下の対応を実施致します。</strong><br>
                    <strong>　大型家電</strong><br>
                    <span>　　提携修理会社を通じて当該メーカーに修理依頼を致します。</span><br>
                    <strong>　その他家電</strong><br>
                    <span>　　ご指定いただいた時間帯での集荷手配をし、提携拠点を通じて修理を実施致します。</span><br>
                    <span>　　なお保証対象事案で保証限度額以内である場合、修理を進行いたしますことをご了承ください。</span><br>
                    <strong>▶ご注意いただきたい事項</strong><br>
                    <strong>　①ご請求の発生</strong><br>
                    <span>　　症状未再現や保証対象外部位の不具合であるなど本保証の対象外となる場合があります。</span><br>
                    <span>　　その際は、往復の運送料や診断料などのご請求が発生いたします。</span><br>
                    <strong>　②保証限度額の超過時の対応</strong><br>
                    <div style="margin-left: 25px">
                    差額をご負担いただいて修理を実施するか、保証限度額から諸経費（運送料・キャンセル料等）を減額した金額内で代替品のご提供となります。その際、ご提供が不可能な場合はご返金での対応となります。なお、いずれの場合でも本保証は終了いたします。
                    </div>
                    <br>
                    <div style="margin-left: 25px">
                    <span>延長保証サービス規定【ベーシックプラン】（自然故障対応）</span><br>
                    <a href="https://www.sagawa-mov.co.jp/business/construction/pdf/warranty_kitei_basic.pdf" target="_blank" style="color: #0563C1;">https://www.sagawa-mov.co.jp/business/construction/pdf/warranty_kitei_basic.pdf</a><br>
                    <span>延長保証サービス規定【バリュープラン】（自然故障＋物損故障対応）</span><br>
                    <a href="https://www.sagawa-mov.co.jp/business/construction/pdf/warranty_kitei_value.pdf" target="_blank" style="color: #0563C1;">https://www.sagawa-mov.co.jp/business/construction/pdf/warranty_kitei_value.pdf</a><br>
                    </div>
                </div>
                
                <div class="form-horizontal spt20" style="font-weight: bold;">
                    <?= @outputErrMsg($errorInfo['common_error']) ?>
                </div>

                <div class="form-horizontal spt20">
                    
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="row">
                                
                                <label class="col-sm-3 col-sm-offset-1 control-label label_blue">
                                    保証書番号
                                </label>
                                <div class="col-sm-7">
                                    <input type="text" name="kanyuusyouno" id="kanrino" class="form-control <?= @checkInputErr($errorInfo['kanyuusyouno']); ?>" maxlength="11" value="<?= @htmlspecialchars($_POST['kanyuusyouno']); ?>">
                                    <?= @outputErrMsg($errorInfo['kanyuusyouno']) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="row">
                                <label class="col-sm-3 col-sm-offset-1 control-label label_blue">
                                    修理依頼者区分
                                </label>
                                <div class="col-sm-3">
                                    <select name="irai_iraisya" id="irai_iraisya" class="form-control">
                                        <option value="10" <?= @checkSelected($_POST['irai_iraisya'], '10') ?>>加入者本人</option>
                                        <option value="20" <?= @checkSelected($_POST['irai_iraisya'], '20') ?>>加入者家族</option>
                                        <option value="90" <?= @checkSelected($_POST['irai_iraisya'], '90') ?>>その他</option>
                                    </select>
                                </div>
                                <label class="col-sm-2 col-sm-offset-0 control-label label_blue">
                                    その他の場合<br>
                                </label>
                                <div class="col-sm-2">
                                    <input type="text" name="irai_iraisya_sonota" id="irai_iraisya_sonota" class="form-control <?= @checkInputErr($errorInfo['irai_iraisya_sonota']); ?>" disabled="disabled" maxlength="50" value="<?= @htmlspecialchars($_POST['irai_iraisya_sonota']); ?>">
                                    <?= @outputErrMsg($errorInfo['irai_iraisya_sonota']) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-7"></div>
                                <div class="col-sm-5"><span class="text_red f75">修理依頼者区分がその他の場合、加入者との関係をご入力ください。</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="row">
                                <label class="col-sm-3 col-sm-offset-1 control-label label_blue">
                                    修理依頼者名
                                </label>
                                <div class="col-sm-7">
                                    <input type="text" name="irai_name" id="irai_name" class="form-control <?= @checkInputErr($errorInfo['irai_name']); ?>" maxlength="100" value="<?= @htmlspecialchars($_POST['irai_name']); ?>">
                                    <?= @outputErrMsg($errorInfo['irai_name']) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="row">
                                <label class="col-sm-3 col-sm-offset-1 control-label label_blue">
                                    修理依頼者名（フリガナ）
                                </label>
                                <div class="col-sm-7">
                                    <input type="text" name="irai_name_kana" id="irai_name_kana" class="form-control <?= @checkInputErr($errorInfo['irai_name_kana']); ?>" maxlength="100" value="<?= @htmlspecialchars($_POST['irai_name_kana']); ?>">
                                    <?= @outputErrMsg($errorInfo['irai_name_kana']) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="row">
                                <label class="col-sm-3 col-sm-offset-1 control-label label_blue">メールアドレス</label>
                                <div class="col-sm-7">
                                    <input type="text" name="irai_mail" id="irai_mail" class="form-control <?= @checkInputErr($errorInfo['irai_mail']); ?>" value="<?= @htmlspecialchars($_POST['irai_mail']); ?>">
                                    <?= @outputErrMsg($errorInfo['irai_mail']) ?>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 col-sm-offset-1 control-label label_blue">郵便番号</label>
                                <div class="col-sm-2">
                                    <input type="text" name="irai_zip" id="irai_zip" class="form-control <?= @checkInputErr($errorInfo['irai_zip']); ?>" maxlength="8" value="<?= @htmlspecialchars($_POST['irai_zip']); ?>">
                                    <?= @outputErrMsg($errorInfo['irai_zip']) ?>
                                </div>
                                <input name="adrs_search_btn" type="button" value="住所検索" style="height: 30px">
                            </div>
                            <div class="row">
                                <label class="col-sm-3 col-sm-offset-1 control-label label_blue">ご住所</label>
                                <div class="col-sm-7">
                                    <input type="text" name="irai_address" id="irai_address" class="form-control <?= @checkInputErr($errorInfo['irai_address']); ?>" maxlength="100" value="<?= @htmlspecialchars($_POST['irai_address']); ?>">
                                    <?= @outputErrMsg($errorInfo['irai_address']) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="row">
                                <label class="col-sm-3 col-sm-offset-1 control-label label_blue">
                                    機器名
                                </label>
                                <div class="col-sm-7">
                                    <select name="irai_kiki" id="irai_kiki" class="form-control <?= @checkInputErr($errorInfo['irai_kiki']); ?>">
                                        <?php foreach ($iraiKikiInfoList as $key => $val) : ?>
                                            <option value="<?= $key ?>" <?= @checkSelected($_POST['irai_kiki'], $key) ?>><?= $val; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= @outputErrMsg($errorInfo['irai_kiki']) ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="row">
                                <label class="col-sm-3 col-sm-offset-1 control-label label_blue">
                                    メーカー名
                                </label>
                                <div class="col-sm-7">
                                    <input type="text" name="irai_maker" id="irai_maker" class="form-control <?= @checkInputErr($errorInfo['irai_maker']); ?>" maxlength="100" value="<?= @htmlspecialchars($_POST['irai_maker']); ?>">
                                    <?= @outputErrMsg($errorInfo['irai_maker']) ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="row">
                                <label class="col-sm-3 col-sm-offset-1 control-label label_blue">
                                    購入日<br>
                                </label>
                                <div class="col-sm-3">
                                    <div class="input-group date datepicker-div">
                                        <input type="text" name="irai_kounyuuhi" id="irai_kounyuuhi" class="form-control datepicker <?= @checkInputErr($errorInfo['irai_kounyuuhi']); ?>" onkeydown="return false;" readonly="readonly" value="<?= @htmlspecialchars($_POST['irai_kounyuuhi']); ?>">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        
                                    </div>
                                    <?= @outputErrMsg($errorInfo['irai_kounyuuhi']) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="row">
                                <label class="col-sm-3 col-sm-offset-1 control-label label_blue">
                                    製品型式
                                </label>
                                <div class="col-sm-7">
                                    <input type="text" name="irai_seihin_keishiki" id="seihin_keishiki" class="form-control <?= @checkInputErr($errorInfo['irai_seihin_keishiki']); ?>" value="<?= @htmlspecialchars($_POST['irai_seihin_keishiki']); ?>" maxlength="50">
                                    <?= @outputErrMsg($errorInfo['irai_seihin_keishiki']) ?>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 col-sm-offset-1 control-label label_blue">
                                    ご購入店
                                </label>
                                <div class="col-sm-7">
                                    <input type="text" name="irai_konyuten" id="irai_konyuten" class="form-control <?= @checkInputErr($errorInfo['irai_konyuten']); ?>" value="<?= @htmlspecialchars($_POST['irai_konyuten']); ?>" maxlength="100">
                                    <?= @outputErrMsg($errorInfo['irai_konyuten']) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="row">
                                <label class="col-sm-3 col-sm-offset-1 control-label label_blue">
                                    故障発生日
                                </label>
                                <div class="col-sm-3">
                                    <div class="input-group date datepicker-div">
                                        <input type="text" name="irai_zikohi" id="irai_zikohi" class="form-control datepicker <?= @checkInputErr($errorInfo['irai_zikohi']); ?>" onkeydown="return false;" readonly="readonly" value="<?= @htmlspecialchars($_POST['irai_zikohi']); ?>">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                    <?= @outputErrMsg($errorInfo['irai_zikohi']) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="row">
                                <label class="col-sm-3 col-sm-offset-1 control-label label_blue">
                                    状況詳細<br>
                                    <span class="text_red f75">
                                        どのような状況或いは原因で、その機器がどのようになったのかをご記入ください。また故障の場合にその症状が常時なのか断続的なのかもあわせてご記入ください。(200文字以内)
                                    </span>
                                </label>
                                <div class="col-sm-7">
                                    <textarea name="irai_zikozyoukyou" id="irai_zikozyoukyou" class="form-control <?= @checkInputErr($errorInfo['irai_zikozyoukyou']); ?>" rows="5"><?= @htmlspecialchars($_POST['irai_zikozyoukyou']); ?></textarea>
                                    <?= @outputErrMsg($errorInfo['irai_zikozyoukyou']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="row">
                                <label class="col-sm-3 col-sm-offset-1 control-label label_blue">
                                    日中ご連絡がとれる電話番号
                                </label>
                                <div class="col-sm-7">
                                    <input type="text" name="irai_tel" id="irai_tel" class="form-control <?= @checkInputErr($errorInfo['irai_tel']); ?>" maxlength="50" value="<?= @htmlspecialchars($_POST['irai_tel']); ?>">
                                    <?= @outputErrMsg($errorInfo['irai_tel']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="row">
                                <label class="col-sm-3 col-sm-offset-1 control-label label_blue">
                                    集荷希望日
                                </label>
                                <div class="col-sm-3">
                                    <div class="input-group date datepicker-div">
                                        <input type="text" name="irai_shuka_kibobi" id="irai_shuka_kibobi" class="form-control datepicker <?= @checkInputErr($errorInfo['irai_shuka_kibobi']); ?>" onkeydown="return false;" readonly="readonly" value="<?= @htmlspecialchars($_POST['irai_shuka_kibobi']); ?>">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                    <?= @outputErrMsg($errorInfo['irai_shuka_kibobi']) ?>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 col-sm-offset-1 control-label label_blue">
                                    希望時間帯
                                </label>
                                <div class="col-sm-7">
                                    <select name="irai_shuka_jikan" id="irai_shuka_jikan" class="form-control <?= @checkInputErr($errorInfo['irai_shuka_jikan']); ?>">
                                        <?php foreach ($iraiShukajikanInfoList as $key => $val) : ?>
                                            <option value="<?= $key ?>" <?= @checkSelected($_POST['irai_shuka_jikan'], $key) ?>><?= $val; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= @outputErrMsg($errorInfo['irai_shuka_jikan']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="margin-left: 65px">
                    <p>≪ご注意≫</p>
                    <p>・修理のご依頼が「土日祝日を除く平日」の正午までの場合、翌日からご指定いただけます。</p>
                    <p>・上記以外の場合は、土日祝日明けの翌々日よりご指定いただけます。</p>
                    <p>・なおご申請の内容が本保証の対象外であることが判明した場合は、集荷は出来ません。</p>
                </div>

                <div class="notes_inbox" style="margin-left: 65px">
                    <!--修理センター運営会社　日本リビング保証株式会社<br>-->
                    延長保証サービス　修理受付センター運営<br>
                    ＳＧムービング株式会社<br>
                    電話番号　0120-323-640　（受付時間:年末年始等の祝祭日を除く平日10時～17時）<br>
                    <!--<b>住所[住所表示の有無を別途決定]</b><br>-->
                </div><br>

                <div class="row spt30">
                    <div class="col-sm-7 col-sm-offset-3 col-xs-10 col-xs-offset-1">
                        <button type="submit" name="check_button" id="submit" class="btn btn-gray btn-block">≪修理のご依頼前に確認いただきたい事項≫に同意の上、修理を依頼する。</button>
                    </div>
                </div>
            </div>
        </form>    
        <footer class="container-fluid"><!--ここからフッター-->
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <p class="copy">© SG Moving Co.,Ltd. All Rights Reserved.</p>
                    </div>
                </div>
            </div>

            <p class="pagetop" style="display: none; position: fixed; bottom: 10px;">
                <a href="/rep/input#">
                    <span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
                    TOPへ
                </a>
            </p>
        </footer><!--ここまでフッター-->
        <div id="ui-datepicker-div" class="ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all"></div>
    </body>
    <style id="igor_ext_nofollow">
        a[rel~='nofollow'],a[rel~='sponsored'],a[rel~='ugc']{
            outline:.14em dotted red !important;
            outline-offset:.2em;
        }
        
        a[rel~='nofollow'] img,a[rel~='sponsored'] img,a[rel~='ugc'] img{
            outline:2px dotted red !important;
            outline-offset:.2em;
        }
    </style>
    <script charset="UTF-8" type="text/javascript" src="/common/js/ajax_searchaddress.js?<?php echo $strSysdate; ?>"></script>
    <script type="text/javascript">
        $('input[name="adrs_search_btn"]').on('click', (function () {
            var $form = $('form').first();
            AjaxZip2.zip2addr(
                'input_forms',
                'comiket_zip1',
                'comiket_pref_cd_sel',
                'comiket_address',
                'comiket_zip2',
                '',
                '',
                $form.data('featureId'),
                $form.data('id'),
                $('input[name="ticket"]').val()
            );
            $('input').filter('[name="comiket_zip1"],[name="comiket_zip2"]').trigger('focusout');
        }));
    </script>
</html>
