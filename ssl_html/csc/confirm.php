<?php

/**
 * コストコ配送サービスのお申し込み確認画面を表示します。
 * @package    ssl_html
 * @subpackage CSC
 * @author     K.Sawada
 * @copyright  2021-2021 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('csc/Confirm');
Sgmov_Lib::useForms(array('Error', 'EveSession'));

// // 処理を実行
$view = new Sgmov_View_Csc_Confirm();

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
}

$eventInfo = @$result['res_data']['event'];
$eventsubInfo = @$result['res_data']['eventsub'];
$inputInfo = @$result['res_data']['input_info'];
$shohinInfo = @$result['res_data']['shohin_info'];
$prefInfoList = @$result['res_data']['pref_info'];
$errorInfoList = @$result['res_data']['error_info'];
$haitatsuKiboItemInfo = @$result['res_data']['haitatsu_kibo_item_info'];

$week = array('日', '月', '火', '水', '木', '金', '土');

function _h($val)
{
    return @htmlspecialchars($val);
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
            <h1 class="page_title" style="margin-bottom:15px !important;">配送受付入力内容の確認</h1>

            <div class="section other">
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
                            <?= @_h(date('Y年m月d日')); ?>（<?= @_h($week[date('w')]); ?>）
                        </dd>
                    </dl>
                </div>


                <div class="input-outbound input-outbound-title" style="font-weight: bolder;">アイテム番号入力</div>
                <div class="dl_block comiket_block">
                    <dl>
                        <dt id="event_sel" style=" border-top: solid 1px #ccc !important;">
                            アイテム番号<span>必須</span>
                        </dt>
                        <dd id="c_kanri_no_err_apply">
                            <?= @_h($shohinInfo['shohin']['shohin_cd']) ?>：<?= @_h($shohinInfo['shohin']['shohin_name']) ?>
                        </dd>
                    </dl>

                    <dl style="<?= @empty($shohinInfo['shohin']) || @empty($shohinInfo['option']) ? 'display:none;' : ''; ?>">
                        <dt id="comiket_detail_type_sel"> オプションサービス<span>必須</span></dt>
                        <dd>
                            <span class="option_cd_kibo" style="<?= @empty($shohinInfo['option']) ? 'display:none;' : ''; ?>">
                                <!-- GiapLN update task Cosco #SMT6-345 3.11.2022 -->
                                <?php if (@$inputInfo['c_option_cd_type'] == '1') { ?>
                                    <?php if (@$inputInfo['c_option_cd'] == '3') : ?>
                                        無償オプション&nbsp;選択中
                                    <?php elseif (@$inputInfo['c_option_cd'] == '1') : ?>
                                        有償オプション&nbsp;選択中
                                    <?php elseif (@$inputInfo['c_option_cd'] == '2') : ?>
                                        オプションなし&nbsp;選択中
                                    <?php endif; ?>
                                <?php } else if (@$inputInfo['c_option_cd_type'] == '2') { ?>
                                    <?php if ($inputInfo['c_option_cd'] == '0') {?>
                                        オプションなし&nbsp;選択中
                                    <?php } else { ?>
                                    <?php foreach ($shohinInfo['option'] as $item) { ?> 
                                        <?php if ($item['id'] == $inputInfo['c_option_cd']) {?>
                                            <?php if ($item['yumusyou_kbn'] == '1') {?> 
                                                有償オプション&nbsp;選択中
                                            <?php } else { ?> 
                                                無償オプション&nbsp;選択中
                                            <?php } ?>
                                        <?php } ?>
                                    <?php }?>
                                    <?php }?>
                                <?php } ?>
                            </span>
                            <br/>
                            <div class="l_option_name" style="margin-left: 30px;<?= @empty($shohinInfo['option']) ? 'display:none;' : ''; ?>">
                                <?php if (@$inputInfo['c_option_cd_type'] == '1') { ?>
                                    <?php if ('1' <= @$inputInfo['c_option_cd'] || @$inputInfo['c_option_cd'] <= '3') : ?>
                                        <?= @$shohinInfo['optionDisp']['dispSagyoNm'] ?>
                                    <?php endif; ?>
                                <?php } else if (@$inputInfo['c_option_cd_type'] == '2') { ?>
                                    <?php if ($inputInfo['c_option_cd'] == '0') {?>
                                        <?= @$shohinInfo['optionDisp']['dispSagyoNm'] ?>
                                    <?php } else { ?>
                                    <?php foreach ($shohinInfo['option'] as $item) { ?> 
                                        <?php if ($item['id'] == $inputInfo['c_option_cd']) {?>
                                            <?= $item['sagyo_naiyo'] ?>
                                        <?php } ?>
                                    <?php }?>
                                    <?php }?>
                                <?php } ?>
                                
                            </div>
                        </dd>
                    </dl>

                    <dl style="<?= @empty($shohinInfo['kaidanList']) ? 'display:none;' : ''; ?>">
                        <dt id="comiket_detail_type_sel">階段上げ作業<span>必須</span></dt>
                        <dd>
                            <span class="kaidan_cd_kibo">
                                <?php if (@$inputInfo['c_kaidan_cd'] == '1') : ?>
                                    作業あり
                                <?php elseif (@$inputInfo['c_kaidan_cd'] == '2') : ?>
                                    作業なし
                                <?php endif; ?>
                            </span>
                            ：
                            <span class="kaidan_type_kibo">
                                <?php if (@$inputInfo['c_kaidan_cd'] == '1') : ?>
                                    <?php if (@$inputInfo['l_kaidan_type'] == 'A') : ?>
                                        外階段あり
                                    <?php elseif (@$inputInfo['l_kaidan_type'] == 'B') : ?>
                                        内階段あり
                                    <?php endif; ?>
                                <?php endif; ?>
                            </span>
                        </dd>
                    </dl>

<?php // 2022/01/21追加
if($shohinInfo['shohin']['option_id']=='1' || $shohinInfo['shohin']['option_id']=='3' || $shohinInfo['shohin']['option_id']=='5' ): ?>
                    <dl>
                        <dt id="comiket_detail_type_sel">リサイクル<span>必須</span></dt>
                        <dd>
                            <span class="recycl_cd_kibo">
                                <?php if (@$inputInfo['c_recycl_cd'] == '1') : ?>
                                    希望する
                                <?php elseif (@$inputInfo['c_recycl_cd'] == '2') : ?>
                                    希望しない
                                <?php endif; ?>
                            </span>
                            ：
                            <span class="recycl_name_kibo">
                                <?php if ($inputInfo['c_recycl_cd'] == '1') : ?>
                                    <?= @_h($inputInfo['l_recycl_name']) ?>
                                <?PHP endif; ?>
                            </span>
                        </dd>
                    </dl>
<?php endif; ?>

                </div>






                <div class="input-outbound input-outbound-title" style="font-weight: bolder;">お申込者・配送先情報入力</div>
                <div class="dl_block comiket_block">
                    <dl class="comiket-personal-name-seimei">
                        <dt id="c_personal_name_sei_err_apply">氏名<span>必須</span></dt>
                        <dd class="c_personal_name_sei_err_apply comiket_personal_name_mei_err_apply"><span class="comiket_personal_name_sei-lbl" style="display: none;"></span>&nbsp; <span class="comiket_personal_name_mei-lbl" style="display: none;"></span>
                            <?= @_h($inputInfo['c_personal_name_sei']) ?>&nbsp;&nbsp;<?= @_h($inputInfo['c_personal_name_mei']) ?>
                        </dd>
                    </dl>
                    <dl>
                        <dt id="c_tel_err_apply">電話番号<span>必須</span></dt>
                        <dd class="c_tel_err_apply"><span class="comiket_tel-lbl" style="display: none;"></span>
                            <?= @_h($inputInfo['c_tel']) ?>
                        </dd>
                    </dl>
                    <dl>
                        <dt id="c_mail_err_apply">メールアドレス<span>必須</span></dt>
                        <dd class="c_mail_err_apply">
                            <?= @_h($inputInfo['c_mail']) ?>
                        </dd>
                    </dl>


                    <dl class="shohin_area" style="<?= @empty($shohinInfo['shohin']['shohin_cd'])  ? 'display:none;' : ''; ?>">
                        <dt id="event_address">商品情報</dt>
                        <dd>
                            <div class="shohin_area" style="<?= @empty($shohinInfo['shohin']['shohin_cd'])  ? 'display:none;' : ''; ?>">
                                【タイプ】大型
                            </div><br>
                            <div class="shohin_area" style="<?= @empty($shohinInfo['shohin']['shohin_cd']) ? 'display:none;' : ''; ?>">
                                【管理番号】<span class='shohin_info_kanri_no'><?= $shohinInfo['shohin']['shohin_cd'] ?></span>
                            </div><br>
                            <div class="shohin_area" style="<?= @empty($shohinInfo['shohin']['shohin_cd']) ? 'display:none;' : ''; ?>">
                                【商品名】<span class="shohin_area l_shohin_name"><?= @$shohinInfo['shohin']['shohin_name'] ?></span>
                            </div><br>

                            <div class="option_area" style="<?= @empty($shohinInfo['shohin']) || @empty($shohinInfo['option']) ? 'display:none;' : ''; ?>">
                            
                                【オプション】
                                <span class="option_cd_kibo" style="<?= @empty($shohinInfo['option']) ? 'display:none;' : ''; ?>">
                                    <?php if (@$inputInfo['c_option_cd_type'] == '1') { ?>
                                        <?php if (@$inputInfo['c_option_cd'] == '3') : ?>
                                            無償オプション&nbsp;選択中
                                        <?php elseif (@$inputInfo['c_option_cd'] == '1') : ?>
                                            有償オプション&nbsp;選択中
                                        <?php elseif (@$inputInfo['c_option_cd'] == '2') : ?>
                                            オプションなし&nbsp;選択中
                                        <?php endif; ?>
                                    <?php } else if (@$inputInfo['c_option_cd_type'] == '2') { ?>
                                        <?php if ($inputInfo['c_option_cd'] == '0') {?>
                                            オプションなし&nbsp;選択中
                                        <?php } else { ?>
                                            <?php foreach ($shohinInfo['option'] as $item) { ?> 
                                                <?php if ($item['id'] == $inputInfo['c_option_cd']) {?>
                                                    <?= $item['sagyo_naiyo']?>&nbsp;選択中
                                                <?php } ?>
                                            <?php }?>
                                        <?php }?>
                                    <?php } ?>       
                                </span>
                                <br/>
                                
                                <div class="l_option_name" style="margin-left: 30px;<?= @empty($shohinInfo['option']) ? 'display:none;' : ''; ?>">
                                    <?php if (@$inputInfo['c_option_cd_type'] == '1') { ?>
                                        <?php if ('1' <= @$inputInfo['c_option_cd'] || @$inputInfo['c_option_cd'] <= '3') : ?>
                                            <?= @$shohinInfo['optionDisp']['dispSagyoNm'] ?>
                                        <?php endif; ?>
                                    <?php } else if (@$inputInfo['c_option_cd_type'] == '2') { ?>
                                        <?= @$shohinInfo['optionDisp']['dispSagyoNm'] ?>
                                    <?php } ?>
                                </div><br/>
                            </div>
                            <div class="kaidan_area" style="<?= @empty($shohinInfo['kaidanList']) ? 'display:none;' : ''; ?>">
                                【階段上げ作業】
                                <span class="kaidan_cd_kibo">
                                    <?php if (@$inputInfo['c_kaidan_cd'] == '1') : ?>
                                        作業あり
                                    <?php elseif (@$inputInfo['c_kaidan_cd'] == '2') : ?>
                                        作業なし
                                    <?php endif; ?>
                                </span>
                                ：
                                <span class="kaidan_type_kibo">
                                    <?php if (@$inputInfo['c_kaidan_cd'] == '1') : ?>
                                        <?php if (@$inputInfo['l_kaidan_type'] == 'A') : ?>
                                            外階段あり
                                        <?php elseif (@$inputInfo['l_kaidan_type'] == 'B') : ?>
                                            内階段あり
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </span>
                                &nbsp;<br><br>
                            </div>

<?php if($shohinInfo['shohin']['option_id']=='1' || $shohinInfo['shohin']['option_id']=='3' || $shohinInfo['shohin']['option_id']=='5' ): ?>

                            <div class="recycl_area" style="<?= @empty($shohinInfo['shohin']['shohin_cd'])  ? 'display:none;' : ''; ?>">
                                【リサイクル】
                                <span class="recycl_cd_kibo">
                                    <?php if (@$inputInfo['c_recycl_cd'] == '1') : ?>
                                        希望する
                                    <?php elseif (@$inputInfo['c_recycl_cd'] == '2') : ?>
                                        希望しない
                                    <?php endif; ?>
                                </span>
                                ：
                                <span class="recycl_name_kibo">
                                    <?php if ($inputInfo['c_recycl_cd'] == '1') : ?>
                                        <?= @_h($inputInfo['l_recycl_name']) ?>
                                    <?PHP endif; ?>
                                </span>
                                <br>
                            </div>
<?php endif; ?>

                        </dd>
                    </dl>



                    <dl class="comiket-personal-name-seimei">
                        <dt id="d_name_err_apply"> 配送先宛名<span>必須</span></dt>
                        <dd class="d_name_err_apply">
                            <?= @_h($inputInfo['d_name']) ?>
                        </dd>
                    </dl>
                    
                    <!-- GiapLN imp ticket #SMT6-385 2022/12/27 --> 
                    <dl class="comiket-personal-staff_tel">
                        <dt id="staff_tel_err_apply">配送先電話番号<span>必須</span></dt>
                        <dd class="staff_tel_err_apply"><span class="comiket_staff_tel-lbl" style="display: none;"></span>
                            <?= @_h($inputInfo['staff_tel']) ?>
                        </dd>
                    </dl>
                    <!-- GiapLN End #SMT6-385 --> 
                    <dl>
                        <dt id="l_zip1_err_apply">郵便番号<span>必須</span></dt>
                        <dd class="l_zip1_err_apply l_zip2_err_apply">
                            〒<?= @_h($inputInfo['l_zip1']) ?>-<?= @_h($inputInfo['l_zip2']) ?>
                        </dd>
                    </dl>

                    <dl>
                        <dt id="d_pref_id_err_apply"> 都道府県<span>必須</span></dt>
                        <dd class="d_pref_id_err_apply">
                            <?= @_h($prefInfoList[((int)$inputInfo['d_pref_id'])]['name']) ?>
                        </dd>
                    </dl>

                    <dl>
                        <dt id="d_address_err_apply"> 市区町村<span>必須</span></dt>
                        <dd class="d_address_err_apply">
                            <?= @_h($inputInfo['d_address']) ?>
                        </dd>
                    </dl>

                    <dl>
                        <dt id="d_building_err_apply"> 番地・建物名・部屋番号<span>必須</span></dt>
                        <dd class="d_building_err_apply">
                            <?= @_h($inputInfo['d_building']) ?>
                        </dd>
                    </dl>
                    <dl class="comiket_detail_inbound_delivery_date"  style="<?= (isset($haitatsuKiboItemInfo['display_val']) && @$haitatsuKiboItemInfo['display_val'] == 'OFF') ? 'display:none;': '';?>">
                        <dt id="d_delivery_date_err_apply">配達希望日</dt>
                        <dd class="d_delivery_date_err_apply">
                            <?php if ($inputInfo['check_addr'] == '1'): ?>
                                <?php if (@!empty($inputInfo['d_delivery_date_fmt'])) : ?>
                                    <?= @_h(date('Y年m月d日', strtotime($inputInfo['d_delivery_date_fmt']))) ?>
                                    （<?= @$week[date('w', strtotime($inputInfo["d_delivery_date_fmt"]))] ?>）
                                <?php endif; ?>
                            <?php endif; ?>
                        </dd>
                    </dl>

                </div>

                <div class="input-inbound input-inbound-title" style="font-weight: bolder;"> 店舗でのお支払い（配送料金） </div>
                <div class="dl_block comiket_block">
                    <dl class="comiket-personal-name-seimei">
                        <dt id="comiket_personal_name-seimei">配送料金合計</dt>
                        <dd>
                            <span style="font-weight: bolder; color: red;">※ 商品情報と配送先情報をもとに料金計算を行いました。下記の料金をデリバリーサービスカウンターでお支払いください</span>
                            <br><br>￥
                            <?php
                            if ($shohinInfo['shohin']['data_type'] == '6') { // エンドユーザ支払
                                if (@empty($shohinInfo['deliv']['fare_tax_kokyaku'])) {
                                    $kingaku = 0;
                                    echo number_format(((int)$kingaku));
                                } else {
                                    $kingaku = $shohinInfo['deliv']['fare_tax_kokyaku'];
                                    // 複数梱包の場合
                                    if (isset($shohinInfo['shohin']['konposu']) && @$shohinInfo['shohin']['konposu'] != '1') {
                                        $konpoKingaku = (int)@$shohinInfo['konpo']['fare_tax'] * ((int)@$shohinInfo['shohin']['konposu']-1);
                                        $kingaku += $konpoKingaku;
                                        echo number_format(((int)$kingaku));
//                                        echo('<br/><br/>金額計算：￥'.number_format($shohinInfo['deliv']['fare_tax_kokyaku'])
//                                        .' ＋（￥'.number_format($shohinInfo['konpo']['fare_tax']).' *（梱包数-1））');
                                    }
                                    else{
                                        echo number_format(((int)$kingaku));
                                    }

                                }

                            } else if ($shohinInfo['shohin']['data_type'] == '7') { // 顧客支払
                                if (@empty($shohinInfo['deliv']['fare_tax'])) {
                                    $kingaku = 0;
                                } else {
                                    $kingaku = number_format(((int)$shohinInfo['deliv']['fare_tax']));
                                }
                                echo $kingaku;
                            }
                            ?>
                        </dd>
                    </dl>
                </div>

                <div class="input-inbound input-inbound-title" style="font-weight: bolder;"> 配達時のお支払い（オプション料金） </div>
                <div class="dl_block comiket_block">
                    <dl class="comiket-personal-name-seimei">
                        <dt id="comiket_personal_name-seimei">オプション料金合計</dt>
                        <dd>
                            <span style="font-weight: bolder; color: red;">リサイクル料金は現地にて案内いたします。</span><br><br>
                            ※ 下記料金については概算料金となります。トラブル防止のため現地にて改めて作業内容の確認を行った後、料金を再度ご提示いたします。料金は作業員へお支払いください<span style="font-weight: bolder; color: red;"> （お支払いは現金のみ） </span>
                            <br><br> ￥
                            <?php
                            $sumKingaku = 0;
                            $optionKingaku = 0;
                            $konpoKingaku = 0;
                            //有償の場合、金額を計算する。無償の時、D24以外の場合、金額を計算する。D24且つ無償の場合、計算しない。
                            if (!empty($shohinInfo['option'])) {
                                if ($inputInfo['c_option_cd_type'] == '1') {
                                    if ($inputInfo['c_option_cd'] == '1' || ($inputInfo['c_option_cd'] == '3' && ($shohinInfo['shohin']['option_id'] != '4' || $shohinInfo['shohin']['data_type'] != '7'))) {
                                        foreach ($shohinInfo['option'] as $op) {
                                            if (($inputInfo['c_option_cd'] == '1' && $op['yumusyou_kbn'] == '1') || 
                                                ($inputInfo['c_option_cd'] == '3' && $op['yumusyou_kbn'] == '0')) {
                                                $sumKingaku += ((int)$op['fare_tax']);
                                            }
                                        }
                                    }
                                } else {//c_option_cd_type=2 オプションが重複の場合
                                    if ($inputInfo['c_option_cd'] != '0') {
                                        foreach ($shohinInfo['option'] as $op) {
                                            if ($op['id'] == $inputInfo['c_option_cd']) {
                                                if ($op['yumusyou_kbn'] == '1' || ($op['yumusyou_kbn'] == '0' && ($shohinInfo['shohin']['option_id'] != '4' || $shohinInfo['shohin']['data_type'] != '7'))) {
                                                    $sumKingaku += ((int)$op['fare_tax']);
                                                }
                                            }
                                        }
                                        
                                    }
                                }
                            }
//                            if (@!empty($shohinInfo['option']) && 
//                                    (
//                                        @$inputInfo['c_option_cd'] == '1' || 
//                                            (
//                                                $inputInfo['c_option_cd'] == '3' && 
//                                                    ( 
//                                                        $shohinInfo['shohin']['option_id'] != '4' || 
//                                                        $shohinInfo['shohin']['data_type'] != '7' 
//                                                    )
//                                            )
//                                    )
//                                ) {
//                                foreach ($shohinInfo['option'] as $op) {
//                                    if (($inputInfo['c_option_cd'] == '1' && $op['yumusyou_kbn'] == '1') || 
//                                        ($inputInfo['c_option_cd'] == '3' && $op['yumusyou_kbn'] == '0')) {
//                                        $sumKingaku += ((int)$op['fare_tax']);
//                                    }
//                                }
//                            }

                            if (@!empty($shohinInfo['kaidanList']) && @$inputInfo['c_kaidan_cd'] == '1') {
// var_dump('######################## 2');
                                if (@$inputInfo['l_kaidan_type'] == 'A') {
                                    $sumKingaku += ((int)$shohinInfo['kaidanList']['A']['fare_tax']);
                                } else if (@$inputInfo['l_kaidan_type'] == 'B') {
                                    $sumKingaku += ((int)$shohinInfo['kaidanList']['B']['fare_tax']);
                                }
                            }
                            $optionKingaku = $sumKingaku;

                            echo number_format($sumKingaku);
                            ?>
                            <br/><br/>金額計算：￥<?= number_format($optionKingaku) ?>
                        </dd>
                    </dl>
                </div>
                <!-- ************************************************************************************************************* -->
                <!-- ************************************************************************************************************* -->
                <!-- ************************************************************************************************************* -->
                <div style="text-align:center;">
                    <a href="<?= !empty($eventsubInfo['csc_nensho_file_nm']) ? '/csc/pdf/' . $eventsubInfo['csc_nensho_file_nm'] . '?20240401' : '#' ?>" class="nyuryokumae_link" target='_blank' style="text-decoration: underline;font-size: 1.5em;" >
                        入力送信前にお読みください
                    </a>
                    <br/><strong style="color: red;"><br/>※ 入力内容を送信した場合は同意したとみなします</strong>
                </div>
            </div>

            <div class="btn_area">
                <a class="back" href="/csc/input/<?= $inputInfo['c_eventsub_id'] ?>">修正する</a>
                <input id="submit_button" class="submit_button" type="button" name="submit" 
                style="background-color:#999;" value="入力内容を確認し同意して送信する" disabled>
                </input>
            </div>
            
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

    <script>
        $(function() {
            $('.nyuryokumae_link').on('click', function() {
                $('.submit_button').prop('style', '');
                $('.submit_button').prop('disabled', false);
            });
            $('#submit_button').on('click', function() {
                // ２重サブミット防止
                $(this).css('pointer-events','none');
                location.href="/csc/complete2";
            });
        });
    </script>
</body>

</html>