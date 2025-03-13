<?php

////Basic認証
//if(@$_GET['param'] == '5007') {
//    require_once dirname(__FILE__) . '/../../lib/component/auth.php';
//    print('<div style="position:absolute;top:0px;left:0;z-index:1020;">未公開</div>');
//}

/**
 * コストコ配送サービスのお申し込み入力画面を表示します。
 * @package    ssl_html
 * @subpackage CSC
 * @author     K.Sawada
 * @copyright  2021-2021 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */


require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('csc/Input');
Sgmov_Lib::useForms(array('Error', 'EveSession'));


Sgmov_Component_Log::debug("8");
// // 処理を実行
$view = new Sgmov_View_Csc_Input();
Sgmov_Component_Log::debug("9");
$result = array();
try {
    $result = $view->execute();
} catch (Exception $e) {
    $exInfo = $e->getMessage();
    $result = array(
        'status' => 'error',
        'message' => 'エラーが発生しました。',
        'res_data' => array(
            'error_info' => $exInfo,
        ),
    );
    Sgmov_Component_Redirect::redirectPublicSsl("/500.html");
    exit;
}

$eventsubId = @$result['res_data']['eventsub_id'];
$eventInfo = @$result['res_data']['event'];
$eventsubInfo = @$result['res_data']['eventsub'];
$inputInfo = @$result['res_data']['input_info'];
$shohinInfo = @$result['res_data']['shohin_info'];
$prefInfoList = @$result['res_data']['pref_info'];
$errorInfoList = @$result['res_data']['error_info'];
$haitatsuKiboItemInfo = @$result['res_data']['haitatsu_kibo_item_info'];
$noticeMessageInfo = @$result['res_data']['notice_message_info'];

$week = array('日', '月', '火', '水', '木', '金', '土');

function _h($val)
{
    return @htmlspecialchars($val);
}

// オプションサービス項目の無償オプションの表示制御
$dispOptionService='display:none;';
$dispOptionServiceYusho='display:none;';
// リサイクル項目の表示制御
$dispRecyclArea='display:none;';
// 商品コード未取得
if(@empty($shohinInfo['shohin'])){
    $dispRecyclArea='display:none;';
    $inputInfo['c_recycl_cd'] = 2;
}
else{
    // オプションサービス項目の無償オプションの表示制御
    if($shohinInfo['optionDisp']['dispMusho']==1){
        $dispOptionService='';
    }
    if($shohinInfo['optionDisp']['dispYusho']==1){
        $dispOptionServiceYusho='';
    }
    // リサイクル項目の表示制御
    if($shohinInfo['optionDisp']['dispRecycle']==1){
        $dispRecyclArea='';
    }
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
    <meta name="Description" content="配送受付サービスのお申し込みのご案内です。" />
    <meta name="author" content="SG MOVING Co.,Ltd" />
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved." />
    <title>配送受付サービスのお申し込み｜ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css?202110301711" rel="stylesheet" type="text/css" />
    <link href="/csc/css/eve.css?202110301711" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css" rel="stylesheet" type="text/css" />

    <script src="/js/ga.js" type="text/javascript"></script>
</head>

<body>

    <!-- ヘッダStart ************************************************ -->
    <header id="header" class="Header--simple">
        <div class="Header__inner">
            <div class="Header__head">
                <h1 class="header-logo">
                    <a href="http://www.sg-hldgs.co.jp/" target="_blank" rel="noopener"><span class="header-logo__image"><img src="/images/common/img_header_01.png" alt="SGH"></span></a>
                    <a href="/"><span class="header-logo__image"><img src="/images/common/img_header_02.png" alt="SGmoving"></span></a>
                </h1>
                <!--/Header__head-->
            </div>
            <!--/Header__inner-->
        </div>
        <!--/Header-->
    </header>
    <!-- ヘッダEnd ************************************************ -->
    <div id="breadcrumb">
        <ul class="wrap">

        </ul>
    </div>
    <div id="main">
        <div class="wrap clearfix">
            <h1 class="page_title" style="margin-bottom:15px !important;" >配送受付</h1>


            <?php if (@!empty($errorInfoList)) : ?>
                <div class="err_msg">
                    <p class="sentence br attention"> [!ご確認ください]下記の項目が正しく入力・選択されていません。 </p>
                    <ul>
                        <?php foreach ($errorInfoList as $key => $val) : ?>
                            <li><a href="#<?= $val['key'] ?>_err_apply"><?= $val['itemName'] . $val['errMsg'] ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>






            <div class="section other">
                <form id="form01" action="/csc/check_input2" data-feature-id="CSC" data-id="CSC001" method="post">
                    <div class="input-outbound input-outbound-title" style="font-weight: bolder;">店舗情報</div>
                    <div class="dl_block comiket_block">
                        <dl>
                            <dt id="event_sel" style=" border-top: solid 1px #ccc !important;">
                                店舗名
                            </dt>
                            <dd>
                                <?= $eventInfo['name'] ?><?= $eventsubInfo['name'] ?>
                            </dd>
                        </dl>
                        <dl>
                            <dt id="event_address">
                                申込日
                            </dt>
                            <dd>
                                <?= date('Y年m月d日'); ?>（<?= $week[date('w')]; ?>）
                            </dd>
                        </dl>
                    </div>


                    <div class="input-outbound input-outbound-title" style="font-weight: bolder;">アイテム番号入力</div>
                    <div class="dl_block comiket_block">
                        <dl>
                            <dt id="c_kanri_no_err_apply" style=" border-top: solid 1px #ccc !important;">
                                アイテム番号<span>必須</span>
                            </dt>
                            <dd class="c_kanri_no_err_apply">
                                <strong class="red">※売り場に掲示の値札もしくはレシートに記載の数字を入力してください</strong>
                                <br/><br/>
                                <input id="c_kanri_no" class="c_kanri_no" maxlength="30" inputmode="" name="c_kanri_no" data-pattern="" placeholder="例）0012345" type="text" style="width: 40%;" value="<?= @_h($inputInfo['c_kanri_no']) ?>">
                                <span class="l_shohin_name"><?= @empty($inputInfo['c_kanri_no']) ? '': @$shohinInfo['shohin']['shohin_name']; ?></span>
                            </dd>
                        </dl>
                        <input id="c_option_cd_type" name="c_option_cd_type" type="hidden" value="<?= @_h($inputInfo['c_option_cd_type']) ?>" />
                        
                        <dl class="option_area" style="<?= @empty($shohinInfo['shohin']) || @empty($shohinInfo['option']) ? 'display:none;' : ''; ?>">
                            <dt id="c_option_cd_err_apply"> オプションサービス<span>必須</span></dt>
                            <dd class="c_option_cd_err_apply">
                                <div class="option_area_sel" style="<?= @empty($shohinInfo['option']) ? 'display:none;' : '';  ?>">
                                    <span style="font-weight: bolder; color: red;">お買い上げいただいた商品は取付サービス対象商品です。取付サービスを希望しますか。</span>
                                    <div class="comiket_detail_type_sel-dd c_option_cd_type1" style="<?= (isset($inputInfo['c_option_cd_type']) && $inputInfo['c_option_cd_type'] == '1') ? '' : 'display:none;'; ?>">
                                        <label class="radio-label comiket_detail_type_sel-label1 c_option_cd_sel3" for="c_option_cd_sel3" 
                                            style="<?=$dispOptionService?>">
                                            <input id="c_option_cd_sel3" class="c_option_cd" name="c_option_cd" 
                                                type="radio" value="3" <?= @_h($inputInfo['c_option_cd']) == '3' ? 'checked=checked' : ''; ?>> 無償オプション
                                        </label>
                                        <label class="radio-label comiket_detail_type_sel-label1 c_option_cd_sel1" for="c_option_cd_sel1" 
                                               style="<?=$dispOptionServiceYusho?>">
                                            <input id="c_option_cd_sel1" class="c_option_cd" name="c_option_cd" 
                                                type="radio" value="1" <?= @_h($inputInfo['c_option_cd']) == '1' ? 'checked=checked' : ''; ?>> 有償オプション
                                        </label>
                                        <label class="radio-label comiket_detail_type_sel-label2" for="c_option_cd_sel2">
                                            <input id="c_option_cd_sel2" class="c_option_cd" name="c_option_cd" 
                                                type="radio" value="2" <?= @_h($inputInfo['c_option_cd']) == '2' ? 'checked=checked' : ''; ?>> オプションなし
                                        </label>
                                        <br>
                                        <span class="l_option_name"><?= @$shohinInfo['optionDisp']['dispSagyoNm'] ?></span><br/>
                                    </div>
                                    <div class="comiket_detail_type_sel-dd c_option_cd_type2" style="<?= (isset($inputInfo['c_option_cd_type']) && $inputInfo['c_option_cd_type'] == '2') ? '' : 'display:none;'; ?>">
                                        <?php if (isset($inputInfo['c_option_cd_type']) && $inputInfo['c_option_cd_type'] == '2') { ?>
                                            <?php $idx = 4; ?>
                                            <?php foreach($shohinInfo['option'] as $keyOption => $rowOption) { ?>
                                                <label class="radio-label comiket_detail_type_sel-label1 c_option_cd_sel<?= $idx ?>" for="c_option_cd_sel<?= $idx ?>" style="height: 25px;">
                                                <input id="c_option_cd_sel<?= $idx ?>" class="c_option_cd2" name="c_option_cd" type="radio" value="<?= $rowOption['id'] ?>" 
                                                        <?= @_h($inputInfo['c_option_cd']) == $rowOption['id'] ? 'checked=checked' : ''; ?>> <?= $rowOption['sagyo_naiyo'] ?>
                                                </label>
                                                <br/>
                                                <?php $idx ++;?>
                                            <?php } ?>
                                                <label class="radio-label comiket_detail_type_sel-label1 c_option_cd_sel<?= $idx ?>" for="c_option_cd_sel<?= $idx ?>" style="height: 25px;"> 
                                                <input id="c_option_cd_sel<?= $idx ?>" class="c_option_cd2" name="c_option_cd" type="radio" value="0" 
                                                       <?= @_h($inputInfo['c_option_cd']) == '0' ? 'checked=checked' : ''; ?>>オプションなし
                                                </label>
                                       <?php } ?> 
                                    </div>
                                        <br/>
                                        <p><span style="font-weight: bolder; color: red;">※上記以外に、現地で追加作業が必要な場合、別途費用が発生いたします。<br/>　例）吊り上げ、階段上げ作業など</span></p>
                                </div>
<!--
                                梱包数：<span class="l_shohin_konposu"><?= @$shohinInfo['shohin']['konposu'] ?></span>
                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="l_shohin_konposu_kingaku"><?= @number_format(1800*((int)$shohinInfo['shohin']['konposu']-1)) ?></span>円
-->
                            </dd>
                        </dl>
                        <dl class="kaidan_area" style="<?= @empty($shohinInfo['shohin']) || @empty($shohinInfo['kaidanList']) ? 'display:none;' : ''; ?>">
                            <dt id="c_kaidan_cd_err_apply"> 階段上げ作業<span>必須</span></dt>
                            <dd class="c_kaidan_cd_err_apply"><span style="font-weight: bolder; color: red;">お買い上げいただいた商品は、大型商品となっております。 エレベータの無い３階以上への屋外階段による搬入の場合、また宅内階段による搬入の場合は階段上げ作業料が発生いたします。 階段作業の発生はございますか。</span>
                                <div class="comiket_detail_type_sel-dd">
                                    <label class="radio-label comiket_detail_kaidan-label1" for="c_kaidan_cd1">
                                        <input id="c_kaidan_cd1" class="c_kaidan_cd" name="c_kaidan_cd" type="radio" value="1" 
                                            <?= @_h($inputInfo['c_kaidan_cd']) == '1' ? 'checked=checked' : ''; ?>> 作業あり
                                    </label>
                                    <label class="radio-label comiket_detail_kaidan-label2" for="c_kaidan_cd2">
                                        <input id="c_kaidan_cd2" class="c_kaidan_cd" name="c_kaidan_cd" type="radio" value="2" 
                                            <?= @_h($inputInfo['c_kaidan_cd']) == '2' ? 'checked=checked' : ''; ?>> 作業なし
                                    </label>
                                    <br>
                                    <label class='radio-label l_kaidan_type' for="l_kaidan_type1" style="<?= @_h($inputInfo['c_kaidan_cd']) != '1' ? 'display:none;' : ''; ?>">
                                        <input type="radio" id="l_kaidan_type1" class="l_kaidan_type" name="l_kaidan_type" value="A" 
                                            <?= @_h($inputInfo['c_kaidan_cd']) == '1' && @_h($inputInfo['l_kaidan_type']) == 'A' ? 'checked=checked' : ''; ?>> 外階段あり
                                    </label>
                                    <label class='radio-label l_kaidan_type' for="l_kaidan_type2" style="<?= @_h($inputInfo['c_kaidan_cd']) != '1' ? 'display:none;' : ''; ?>">
                                        <input type="radio" id="l_kaidan_type2" class="l_kaidan_type" name="l_kaidan_type" value="B" 
                                            <?= @_h($inputInfo['c_kaidan_cd']) == '1' && @_h($inputInfo['l_kaidan_type']) == 'B' ? 'checked=checked' : ''; ?>> 内階段あり
                                    </label>
                                </div>
                            </dd>
                        </dl>
                        <dl class="recycl_area" style="<?= $dispRecyclArea ?>">
                            <dt id="c_recycl_cd_err_apply">リサイクル<span>必須</span></dt>
                            <dd class="c_recycl_cd_err_apply">
                                <div style="font-weight: bolder; color: red;"> こちらの商品は、家電リサイクル対象商品です。<br> 同じ種類の不要な商品をお持ちの場合、リサイクル回収を承ります。（有償）<br> リサイクル料金は当日引取時にお支払いいただきます。 </div>
                                <div class="comiket_detail_type_sel-dd">
                                    <label class="radio-label comiket_detail_recycl-label1" for="c_recycl_cd1">
                                        <input id="c_recycl_cd1" class="c_recycl_cd" name="c_recycl_cd" type="radio" value="1" <?= @_h($inputInfo['c_recycl_cd']) == '1' ? 'checked=checked' : ''; ?>> 希望する
                                    </label>
                                    <label class="radio-label comiket_detail_recycl-label2" for="c_recycl_cd2">
                                        <input id="c_recycl_cd2" class="c_recycl_cd" name="c_recycl_cd" type="radio" value="2" <?= @_h($inputInfo['c_recycl_cd']) == '2' ? 'checked=checked' : ''; ?>> 希望しない
                                    </label>
                                    <br>
                                    <div class='l_recycl_name' style="<?= @_h($inputInfo['c_recycl_cd']) != '1' ? 'display:none;' : ''; ?>">
                                        <select name="l_recycl_name">
                                            <option value="">選択してください</option>
                                            <option value="冷蔵庫" <?= @_h($inputInfo['c_recycl_cd']) == '1' && @_h($inputInfo['l_recycl_name']) == '冷蔵庫' ? 'selected' : ''; ?>>
                                                冷蔵庫
                                            </option>
                                            <option value="洗濯機" <?= @_h($inputInfo['c_recycl_cd']) == '1' && @_h($inputInfo['l_recycl_name']) == '洗濯機' ? 'selected' : ''; ?>>
                                                洗濯機
                                            </option>
                                            <option value="エアコン" <?= @_h($inputInfo['c_recycl_cd']) == '1' && @_h($inputInfo['l_recycl_name']) == 'エアコン' ? 'selected' : ''; ?>>
                                                エアコン
                                            </option>
                                            <option value="テレビ" <?= @_h($inputInfo['c_recycl_cd']) == '1' && @_h($inputInfo['l_recycl_name']) == 'テレビ' ? 'selected' : ''; ?>>
                                                テレビ
                                            </option>
                                        </select>
                                        <a href="/csc/pdf/recycl_ryokin.pdf" target="_blank" style="text-decoration: underline;">リサイクル料金表はこちら</a>
                                    </div>
                                </div>
                            </dd>
                        </dl>
                    </div>


                    <div class="input-outbound input-outbound-title" style="font-weight: bolder;">お申込者・配送先情報入力</div>
                    <div class="dl_block comiket_block">
                        <dl class="comiket-personal-name-seimei">
                            <dt id="c_personal_name_sei_err_apply">氏名<span>必須</span></dt>
                            <dd class="c_personal_name_sei_err_apply comiket_personal_name_mei_err_apply">

                                氏<input class="c_personal_name_sei" maxlength="8" autocapitalize="off" inputmode="" name="c_personal_name_sei" data-pattern="" placeholder="例）佐川" type="text" style="width: 30%;" value="<?= @_h($inputInfo['c_personal_name_sei']) ?>">
                                名<input class="c_personal_name_mei" maxlength="8" autocapitalize="off" inputmode="" name="c_personal_name_mei" data-pattern="" placeholder="例）花子" type="text" style="width: 30%;" value="<?= @_h($inputInfo['c_personal_name_mei']) ?>">
                            </dd>
                        </dl>
                        <dl>
                            <dt id="c_tel_err_apply">電話番号<span>必須</span></dt>
                            <dd class="c_tel_err_apply"><span class="comiket_tel-lbl" style="display: none;"></span>
                                <input name="c_tel" class="number-p-only" type="text" maxlength="15" placeholder="例）080-1111-2222" data-pattern="^[0-9-]+$" value="<?= @_h($inputInfo['c_tel']) ?>">
                            </dd>
                        </dl>



                        <dl>
                            <dt id="c_mail_err_apply">メールアドレス<span>必須</span></dt>
                            <dd class="c_mail_err_apply">
                                <input class="w_220" maxlength="100" autocapitalize="off" inputmode="email" name="c_mail" data-pattern="^[!-~]+$" placeholder="例）ringo@sagawa.com" type="text" value="<?= @_h($inputInfo['c_mail']) ?>">
                                <br class="sp_only"><br><strong class="red">※申込完了の際に申込完了メールを送付させていただきますので、間違いのないように注意してご入力ください。</strong>
                                <p class="red"> ※迷惑メールを設定されている方は「sagawa-mov.co.jp」を受信する設定にしてください。 <br>
                                    詳しくは <a href="#bounce_mail">こちら</a></p>
                                <p class="red"> ※@以降のドメインの確認お願いします。<br> 例：@～.ne.jp、@～.co.jp、Gmailなら@gmail.com等 </p>
                            </dd>
                        </dl>

                        <dl>
                            <dt id="l_mail_kakunin_err_apply"> メールアドレス確認<span>必須</span></dt>
                            <dd class="l_mail_kakunin_err_apply">
                                <input class="w_220" maxlength="100" autocapitalize="off" inputmode="email" name="l_mail_kakunin" data-pattern="^[!-~]+$" placeholder="例）ringo@sagawa.com" type="text" value="<?= @_h($inputInfo['l_mail_kakunin']) ?>">
                                <strong class='red'>※確認のため、再入力をお願いいたします。 </strong>
                            </dd>
                        </dl>


                        <dl class="shohin_area" style="<?= @empty($shohinInfo['shohin']['shohin_cd'])  ? 'display:none;' :''; ?>">
                            <dt id="event_address">商品情報</dt>
                            <dd>
                                <div class="shohin_area" style="<?= @empty($shohinInfo['shohin']['shohin_cd'])  ? 'display:none;' :''; ?>">
                                    【タイプ】大型
                                </div><br>
                                <div class="shohin_area" style="<?= @empty($shohinInfo['shohin']['shohin_cd']) ? 'display:none;' :''; ?>">
                                    【管理番号】<span class='shohin_info_kanri_no'><?= @$shohinInfo['shohin']['shohin_cd'] ?></span>
                                </div><br>
                                <div class="shohin_area" style="<?= @empty($shohinInfo['shohin']['shohin_cd']) ? 'display:none;' :''; ?>">
                                    【商品名】<span class="shohin_area l_shohin_name"><?= @$shohinInfo['shohin']['shohin_name'] ?></span>
                                </div><br>
                                <div class="option_area" style="<?= @empty($shohinInfo['shohin']) || @empty($shohinInfo['option']) ? 'display:none;' : ''; ?>"> 
                                    【オプション】
                                    <span class="option_cd_kibo" style="<?= @empty($shohinInfo['option']) ? 'display:none;' : ''; ?>">
                                        <?php if (@$inputInfo['c_option_cd_type'] == '1') { ?>
                                            <?php if (@$inputInfo['c_option_cd'] == '3'): ?>
                                                無償オプション&nbsp;選択中
                                            <?php elseif (@$inputInfo['c_option_cd'] == '1'): ?>
                                                有償オプション&nbsp;選択中
                                            <?php elseif(@$inputInfo['c_option_cd'] == '2') :?>
                                                オプションなし&nbsp;選択中
                                            <?php endif; ?> 
                                        <?php } else if (@$inputInfo['c_option_cd_type'] == '2') { ?>
                                            <?php if (@$inputInfo['c_option_cd'] === '0') {?>
                                                オプションなし&nbsp;選択中
                                            <?php } else { ?>
                                            <?php foreach ($shohinInfo['option'] as $item) { ?> 
                                                <?php if ($item['id'] == @$inputInfo['c_option_cd']) {?>
                                                        <?= $item['sagyo_naiyo'] ?>&nbsp;選択中
                                                <?php } ?>
                                            <?php }?>
                                            <?php }?>
                                        <?php } ?>
                                    </span>
                                    <div class="option_area_sel" style="<?= @empty($shohinInfo['option']) ? 'display:none;' : '';  ?>">
                                        <div class="l_option_name" style='margin-left:30px;'>
                                            <?= @$shohinInfo['optionDisp']['dispSagyoNm'] ?>
                                        </div>
                                    </div>
<!--
                                    <div style='margin-left:30px;'>
                                        梱包数：<span class="l_shohin_konposu"><?= @$shohinInfo['shohin']['konposu'] ?></span>
                                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="l_shohin_konposu_kingaku"><?= @number_format(1800*((int)$shohinInfo['shohin']['konposu']-1)) ?></span>円
                                    </div>                                    <br/>
-->
                                </div>
                                <br/>
                                <div class="kaidan_area" style="<?= @empty($shohinInfo['kaidanList']) ? 'display:none;' :''; ?>">
                                    【階段上げ作業】
                                    <span class="kaidan_cd_kibo">
                                        <?php if (@$inputInfo['c_kaidan_cd'] == '1'): ?>
                                            作業あり
                                        <?php elseif(@$inputInfo['c_kaidan_cd'] == '2') :?>
                                            作業なし
                                        <?php endif; ?>
                                    </span>
                                    ：
                                    <span class="kaidan_type_kibo">
                                        <?php if (@$inputInfo['c_kaidan_cd'] == '1'): ?>
                                            <?php if (@$inputInfo['l_kaidan_type'] == 'A'): ?>
                                                外階段あり
                                            <?php elseif(@$inputInfo['l_kaidan_type'] == 'B') :?>
                                                内階段あり
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </span>
                                    &nbsp;<br><br>
                                </div>
                                
                                <div class="recycl_area" style="<?= $dispRecyclArea ?>"> 
                                    【リサイクル】
                                    <span class="recycl_cd_kibo">
                                        <?php if (@$inputInfo['c_recycl_cd'] == '1'): ?>
                                            希望する
                                        <?php elseif(@$inputInfo['c_recycl_cd'] == '2') :?>
                                            希望しない
                                        <?php endif; ?> 
                                    </span>
                                    ：
                                    <span class="recycl_name_kibo">
                                        <?php if (@$inputInfo['c_recycl_cd'] == '1' ): ?>
                                            <?= @_h($inputInfo['l_recycl_name']) ?>
                                        <?PHP endif; ?>
                                    </span>
                                    <br>
                                </div>
                                
                            </dd>
                        </dl>


                        <dl class="comiket-personal-name-seimei">
                            <dt id="d_name_err_apply"> 配送先宛名<span>必須</span></dt>
                            <dd class="d_name_err_apply">
                                <input class="d_name" autocapitalize="off" inputmode="comiket_detail_inbound_name" name="d_name" data-pattern="" placeholder="" type="text" style="width: 80%;" value="<?= @_h($inputInfo['d_name']) ?>">
                                <input class="m110 inbound_adrs_copy_btn" name="inbound_adrs_copy_btn" type="button" value="お申込者と同じ">
                            </dd>
                        </dl>
                        
                        <!-- GiapLN imp ticket #SMT6-385 2022/12/27 --> 
                        <dl class="comiket-personal-staff_tel">
                            <dt id="staff_tel_err_apply">配送先電話番号<span>必須</span></dt>
                            <dd class="staff_tel_err_apply"><span class="comiket_staff_tel-lbl" style="display: none;"></span>
                                <input name="staff_tel" class="number-p-only" type="text" maxlength="15" placeholder="例）080-1111-2222" data-pattern="^[0-9-]+$" value="<?= @_h($inputInfo['staff_tel']) ?>">
                            </dd>
                        </dl>
                        <!-- GiapLN End #SMT6-385 --> 
                        
                        <dl>
                            <dt id="l_zip1_err_apply">郵便番号<span>必須</span></dt>
                            <dd class="l_zip1_err_apply l_zip2_err_apply">
                                〒<input autocapitalize="off" class="w_70 number-only l_zip1" maxlength="3" inputmode="numeric" name="l_zip1" data-pattern="^\d+$" placeholder="例）136" type="text" value="<?= @_h($inputInfo['l_zip1']) ?>"> -
                                <input autocapitalize="off" class="w_70 number-only l_zip2" maxlength="4" inputmode="numeric" name="l_zip2" data-pattern="^\d+$" placeholder="例）0082" type="text" value="<?= @_h($inputInfo['l_zip2']) ?>">
                                <input class="m110" name="inbound_adrs_search_btn" type="button" value="住所検索">
                                <span class="forget-address-discription" style="font-size: 12px; display: inline-block !important;"> ※郵便番号が不明な方は<a target="_blank" href="http://www.post.japanpost.jp/zipcode/" style="text-decoration: underline;">こちら...</a></span>
                            </dd>
                        </dl>

                        <dl>
                            <dt id="d_pref_id_err_apply"> 都道府県<span>必須</span></dt>
                            <dd class="d_pref_id_err_apply">
                                <select class='d_pref_id' name="d_pref_id">
                                    <?php foreach ($prefInfoList as $key => $val) : ?>
                                        <option value="<?= @$val['prefecture_id'] ?>" <?= @$val['prefecture_id'] == @$inputInfo['d_pref_id'] ? ' selected' : ''; ?>>
                                            <?= @$val['name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </dd>
                        </dl>

                        <dl>
                            <dt id="d_address_err_apply"> 市区町村<span>必須</span></dt>
                            <dd class="d_address_err_apply">
                                <input class="d_address" maxlength="14" autocapitalize="off" name="d_address" placeholder="例）江東区新砂" type="text" style="width: 80%;" value="<?= @_h($inputInfo['d_address']) ?>">
                            </dd>
                        </dl>

                        <dl>
                            <dt id="d_building_err_apply"> 番地・建物名・部屋番号<span>必須</span></dt>
                            <dd class="d_building_err_apply">
                                <input class="d_building" maxlength="30" autocapitalize="off" name="d_building" placeholder="例）1-8-2" type="text" style="width: 80%;" value="<?= @_h($inputInfo['d_building']) ?>">
                            </dd>
                        </dl>

                        <dl class="comiket_detail_inbound_delivery_date" style="<?= (isset($haitatsuKiboItemInfo['display_val']) && $haitatsuKiboItemInfo['display_val'] == 'OFF') ? 'display:none;': '';?>">
                            <dt id="d_delivery_date_err_apply">配達希望日</dt>
                            <dd class="d_delivery_date_err_apply">
                                <div style="color: rgb(255, 0, 0);"> <span class="disp_d_from"></span> </div>
                                <p class="comiket-detail-inbound-delivery-date-fr-to" style="padding: 7px 0;">
                                    <span class="disp_d_from_day"></span> 
                                    <span class="disp_d_to_day"></span>
                                </p>
                                <p style="color: rgb(255, 0, 0);padding-top: 0px;"> <span class="disp_date_history"></span> </p>
                                <div class="comiket_detail_inbound_delivery_date_parts" style="white-space: nowrap;">
                                    <input type="text" id="d_delivery_date_fmt" class="d_delivery_date_fmt" name="d_delivery_date_fmt" placeholder="" tabindex="0" value="<?= isset($inputInfo['d_delivery_date_fmt']) ? $inputInfo['d_delivery_date_fmt'] : '' ?>">
                                    <br>
                                    <br>
                                </div>
                                <div style="color: rgb(255, 0, 0);"> ※あくまでも希望日となりますので、配送日指定をお受けできない場合がございます。<br> &nbsp;&nbsp;&nbsp;その場合は配送業者より、ご登録の連絡先へ改めて日程調整のご連絡をさせていただきます。 </div>
                                <div class="no_addr_txt" style="display:none;color: rgb(255, 0, 0);"><br>※お住まいの地域によっては配送希望日を選択できない場合がございます。<br> &nbsp;&nbsp;&nbsp;最寄りの配送拠点に荷物が到着次第、ご登録の連絡先へ日程調整のご連絡をさせていただきます。</div>
                            </dd>
                        </dl>

                    </div>
                    <!-- ************************************************************************************************************* -->
                    <!-- ************************************************************************************************************* -->
                    <!-- ************************************************************************************************************* -->
            </div>

            <?php if (@!empty($noticeMessageInfo['display_val'])) : ?>
                <div>
                    <?= @$noticeMessageInfo['display_val'] ?><br>
                </div>
            <?php endif; ?>
            <p class="text_center">
                <input id="submit_button" type="submit" name="submit" value="次に進む（入力内容の確認）" >
            </p>

            <input type="hidden" id="check_addr" name="check_addr" value="<?= isset($inputInfo['check_addr']) ? $inputInfo['check_addr'] : '1' ?>" />
            <input type="hidden" id="business_holiday"  name="business_holiday" value='<?= @$result['res_data']['business_holiday'] ?>' />
            <input type="hidden" id="arr_date_dis"  name="arr_date_dis" />
            <input type="hidden" class="c_event_id" name="c_event_id" value="<?= $result['res_data']['event']['id'] ?>" />
            <input type="hidden" class="c_eventsub_id" name="c_eventsub_id" value="<?= $result['res_data']['eventsub']['id'] ?>" />
            <input type="hidden" class="l_is_option" name="l_is_option" value="<?= @_h($inputInfo['l_is_option']) ?>" />
            <input type="hidden" class="l_is_kaidan" name="l_is_kaidan" value="<?= @_h($inputInfo['l_is_kaidan']) ?>" />
            <input type="hidden" class="l_haiso_kingaku_disp" name="l_haiso_kingaku_disp" value="<?= @_h($inputInfo['l_haiso_kingaku_disp']) ?>" />
            <input type="hidden" class="l_kaidan_kingaku_A_disp" name="l_kaidan_kingaku_A_disp" value="<?= @_h($inputInfo['l_kaidan_kingaku_B_disp']) ?>" />
            <input type="hidden" class="l_kaidan_kingaku_B_disp" name="l_kaidan_kingaku_B_disp" value="<?= @_h($inputInfo['l_kaidan_kingaku_B_disp']) ?>" />
<?php // 配送希望日の日付を保持 ?>
<?php // 初回表示 ?>
<?php if(@_h($inputInfo['d_from'])=='' || @_h($inputInfo['d_to'])==''): ?>
            <input type="hidden" class="d_from" name="d_from" value="<?= @$result['res_data']['leadtime_info']['plus_period']?>" />
            <input type="hidden" class="d_to" name="d_to" value="<?= @$result['res_data']['leadtime_info']['deli_period']?>" />
<?php else: ?>
                <input type="hidden" class="d_from" name="d_from" value="<?= @_h($inputInfo['d_from']) ?>" />
                <input type="hidden" class="d_to" name="d_to" value="<?= @_h($inputInfo['d_to']) ?>" />
<?php endif; ?>
            </form>
        </div>
    </div>
    <!--main-->

    <!-- フッターStart ************************************************ -->
    <footer id="footer" class="Footer--simple">
        <div class="Footer__inner">
            <div class="Footer__foot">
                <div class="Footer__foot__inner">
                    <div class="footer-copyright">
                        <small class="footer-copyright__label">&copy; SG Moving Co.,Ltd. All Rights Reserved.</small>
                    </div>
                </div>
                <!-- /Footer__foot -->
            </div>
            <!-- /Footer__inner -->
        </div>
        <!-- /Footer -->
    </footer>
    <!-- フッターEnd ************************************************ -->
    <script charset="UTF-8" type="text/javascript" src="/js/jquery-2.2.0.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/lodash.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/multi_send.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/common.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/smooth_scroll.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/ajax_searchaddress.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/jquery.ah-placeholder.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/jquery.autoKana.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/from_to_pulldate.js?202110301711"></script>
    <script charset="UTF-8" type="text/javascript" src="/csc/js/input.js?<?= date('YmdHis') ?>"></script>
    <script charset="UTF-8" type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js"></script>

    <script>
        $(function() {
            <?php if (@!empty($errorInfoList)) : ?>
                <?php foreach ($errorInfoList as $key => $val) : ?>
                    $('.<?= $val['key'] ?>_err_apply').addClass('err_input');
                <?php endforeach; ?>
            <?php endif; ?>
            $('#submit_button').on('click', function() {
                // ２重サブミット防止
                $(this).css('pointer-events','none');
                return true;
            });
        });
    </script>

    <style>
        .err_input {
            background-color: #fdd;
        }
    </style>

</body>

</html>