<?php

//-----------------メンテナンス期間設定 ------------------//
    require_once dirname(__FILE__) . '/../../lib/component/maintain_event.php';
    require_once dirname(__FILE__) . '/../../lib/component/maintain_pcr.php';//沖縄那覇版
    require_once dirname(__FILE__) . '/../../lib/component/maintain_pct.php';//通常版
//----------------イベントのバナー表示設定期間------------//

    // 現在日時
    $nowDate = new DateTime('now');
//----------------イベントのバナー表示設定期間------------//
//    //デザインフェスタVol58
//    // 表示開始日時
//    $stDate = new DateTime('2023-10-27 00:00:00');
//    // 表示終了日時
//    $edDate = new DateTime('2023-11-12 22:00:00');
//    // 識別子
//    $shikibetushi='dsn';
//    // バナー画像：280*112程度のサイズ
//    $bannerFile='btn_dsn.png';
    
    // デザインフェスタ vol.60
    // 表示開始日時
    $stDate_1 = new DateTime('2025-03-01 00:00:00');
    // 表示終了日時
    $edDate_1 = new DateTime('2025-03-17 21:00:00');
    // 識別子
    $shikibetushi_1='dsn';
    // バナー画像：280*112程度のサイズ
    $bannerFile_1='btn_dsn.png';
        
    // 生活のたのしみ展2025
    // 表示開始日時
    $stDate_2 = new DateTime('2024-12-03 00:00:00');
    // 表示終了日時
    $edDate_2 = new DateTime('2024-12-23 12:00:00');
    // 識別子
    $shikibetushi_2='skt';
    // バナー画像：280*112程度のサイズ
    $bannerFile_2='btn_skt.jpg';
    
    // コミケットスマートコンテナ105
    // 表示開始日時
    $stDate_3 = new DateTime('2024-07-02 00:00:00');
    // 表示終了日時
    $edDate_3 = new DateTime('2024-12-20 17:00:00');
    // 識別子
    $shikibetushi_3='eve';
    // バナー画像：280*112程度のサイズ
    $bannerFile_3='btn_eve_info.png';

        
    // コミックマーケット105　企業
    // 表示開始日時
    $stDate_4 = new DateTime('2024-12-05 00:00:00');
    // 表示終了日時
    $edDate_4 = new DateTime('2024-12-30 22:00:00');
    // 識別子
    $shikibetushi_4='eve';
    // バナー画像：280*112程度のサイズ
    $bannerFile_4='btn_eve.png';
    
    //コミケットアピール105（サークル（一般）出展者）
    // 表示開始日時
    $stDate_5 = new DateTime('2024-12-05 00:00:00');
    // 表示終了日時
    $edDate_5 = new DateTime('2024-12-30 22:00:00');
    // 識別子
    $shikibetushi_5='evp';
    // バナー画像：280*112程度のサイズ
    $bannerFile_5='btn_evp.png';
//----------------クルーズのバナー表示設定期間-----------//
    //クルーズの那覇版のバナー表示
    // 表示開始日時
    $stDate_Pcr = new DateTime('2023-02-24 00:00:00');
    // 表示終了日時
    $edDate_pcr = new DateTime('9999-12-31 22:00:00');
    // 識別子
    $shikibetushi_pcr='pcr';
    // バナー画像：280*112程度のサイズ
    $bannerFile_pcr='btn_pcr.png';
    //イベントのバナーを表示するか確認

    //クルーズの通常版のバナー表示
    // 表示開始日時
    $stDate_Pct = new DateTime('2023-02-24 00:00:00');
    // 表示終了日時
    $edDate_pct = new DateTime('9999-12-31 22:00:00');
    // 識別子
    $shikibetushi_pct='pct';
    // バナー画像：280*112程度のサイズ
    $bannerFile_pct='btn_pct.png';
    //イベントのバナーを表示するか確認
//---------------------------------------------------------//
    $event1Display = ($stDate_1 <= $nowDate && $nowDate <= $edDate_1) ? true : false;
    $event2Display = ($stDate_2 <= $nowDate && $nowDate <= $edDate_2) ? true : false;
    $event3Display = ($stDate_3 <= $nowDate && $nowDate <= $edDate_3) ? true : false;
    $event4Display = ($stDate_4 <= $nowDate && $nowDate <= $edDate_4) ? true : false;
    $event5Display = ($stDate_5 <= $nowDate && $nowDate <= $edDate_5) ? true : false;
    
    $pcrDisplay = ($stDate_Pcr <= $nowDate && $nowDate <= $edDate_pcr) ? true : false;
    $pctDisplay = ($stDate_Pct <= $nowDate && $nowDate <= $edDate_pct) ? true : false;
    
    $margin = '';
    if ($event1Display && $event2Display && $event3Display && $event4Display) {
        $margin = 'style="margin-top: 5px"';//5番目のバナーを表示する時、改行なので、Marginを設定する
    }    
?>
<!-- ここから メンテナンス中のバナー表示 -->
<?php if (($main_stDate_ev <= $nowDate && $nowDate <= $main_edDate_ev) && ($main_stDate_pcr <= $nowDate && $nowDate <= $main_edDate_pcr) && ($main_stDate_pct <= $nowDate && $nowDate <= $main_edDate_pct)) : ?>
    <div id="mv_event_start" class="event" style="z-index:200">
        <span><img src="/img/index/btn_maintain.png" alt=""></span>
    </div> 
<!-- ここまで メンテナンス中のバナー表示 -->

<!-- ここまで クルーズのバナー表示 -->
<?php elseif ($event5Display || $event1Display || $event2Display || $event3Display ||$pcrDisplay || $pctDisplay || $event4Display) : ?>
<!-- ここから 各イベントのボタン表示 -->
<div id="mv_recruit_start" class="event" style="z-index: 200;">
        <a href="https://job.mynavi.jp/26/pc/search/corp107423/outline.html"><span><img src="/img/index/btn_recruit2026-2.png" alt=""></span></a>
    </div> 
    <div id="mv_event_start" class="event" style="z-index:200">
        <span onclick="mv_event_open()"><img src="/img/index/btn_event.png" alt=""></span>
    </div> 
    <div id="mv_event">
        <div class="mv_event_bg"></div>
        <div class="mv_event_contents">
            <img class="btn_close" src="/img/index/icon_close.png" alt="" onclick="mv_event_close()">
            <!--イベント表示-->
            
                <div class="mv_event_content">
                    <p class="title">各種イベントの荷物輸送お申し込み</p>
                    <ul class="bannerList">                       
                        <!-- イベントバナー1 -->
                        <?php if ($event1Display) : ?>
                            <?php if ($main_stDate_ev <= $nowDate && $nowDate <= $main_edDate_ev) : ?>
                            <li><img src="/img/event/btn_maintain.png" alt=""></li>
                            <?php else :?>
                            <li>
                                <a href="/<?=$shikibetushi_1?>/input/" target="_blank">
                                    <span style="color: white;font-weight: bold;font-size: 13.5px"></span><span style="color: white;font-size: 11.5px"></span>
                                    <img src="/img/event/<?=$bannerFile_1?>" alt="">
                                </a>
                            </li>
                            <?php endif; ?>
                        <?php else :?>
                        <br><br>
                        <?php endif; ?>
                        
                        <!-- イベントバナー2 -->
                        <?php if ($event2Display) : ?>
                            <?php if ($main_stDate_ev <= $nowDate && $nowDate <= $main_edDate_ev) : ?>
                        <li><img src="/img/event/btn_maintain.png" alt=""></li>
                            <?php else :?>
                            <li>
                                <a href="/<?=$shikibetushi_2?>/input/" target="_blank">
                                    <span style="color: white;font-weight: bold;font-size: 13.5px"></span><span style="color: white;font-size: 11.5px"></span>
                                    <img src="/img/event/<?=$bannerFile_2?>" alt="">
                                </a>
                            </li>
                            <?php endif; ?>
                        <?php else :?>
                        <br><br>
                        <?php endif; ?>
                        
                        <!-- イベントバナー3 -->
                        <?php if ($event3Display) : ?>
                            <?php if ($main_stDate_ev <= $nowDate && $nowDate <= $main_edDate_ev) : ?>
                            <li><img src="/img/event/btn_maintain.png" alt=""></li>
                            <?php else :?>
                            <li><a href="/<?=$shikibetushi_3?>/info/" target="_blank">
                                    <span style="color: white;font-weight: bold;font-size: 13.5px"></span><span style="color: white;font-size: 11.5px"></span>
                                    <img src="/img/event/<?=$bannerFile_3?>" alt=""></a></li>
                            <?php endif; ?>
                        <?php else :?>
                        <?php endif; ?>
                        
                        <!-- イベントバナー4 -->
                        <?php if ($event4Display) : ?>
                            <?php if ($main_stDate_ev <= $nowDate && $nowDate <= $main_edDate_ev) : ?>
                            <li><img src="/img/event/btn_maintain.png" alt=""></li>
                            <?php else :?>
                            <li><a href="/<?=$shikibetushi_4?>/input/" target="_blank">
                                    <span style="color: white;font-weight: bold;font-size: 13.5px"></span><span style="color: white;font-size: 11.5px"></span>
                                    <img src="/img/event/<?=$bannerFile_4?>" alt="">
                                </a>
                            </li>
                            <?php endif; ?>
                        <?php else :?>
                        <?php endif; ?>

                        <!-- イベントバナー5 -->
                        <?php if ($event5Display) : ?>
                            <?php if ($main_stDate_ev <= $nowDate && $nowDate <= $main_edDate_ev) : ?>
                            <li><img src="/img/event/btn_maintain.png" alt="" <?=$margin?>></li>
                            <?php else :?>
                            <li><a href="/<?=$shikibetushi_5?>/input/" target="_blank">
                                    <span style="color: white;font-weight: bold;font-size: 13.5px"></span><span style="color: white;font-size: 11.5px"></span>
                                    <img src="/img/event/<?=$bannerFile_5?>" alt=""  <?=$margin?>>
                                </a>
                            </li>
                            <?php endif; ?>
                        <?php else :?>
                        <br><br>
                        <?php endif; ?>
                        
                        <!-- HMJ -->
                        <!--<?php if ($event2Display) : ?>
                            <?php if ($main_stDate_ev <= $nowDate && $nowDate <= $main_edDate_ev) : ?>
                            <li><img src="/img/event/btn_maintain.png" alt=""></li>
                            <?php else :?>
                            <li><a href="/<?=$shikibetushi_2?>/input/" target="_blank"><img src="/img/event/<?=$bannerFile_2?>" alt=""></a></li>
                            <?php endif; ?>
                        <?php else :?>
                        <?php endif; ?> -->

                        <!--YID-->
                        <!--<?php if ($event1Display) : ?>
                            <?php if ($main_stDate_ev <= $nowDate && $nowDate <= $main_edDate_ev) : ?>
                            <li><img src="/img/event/btn_maintain.png" alt=""></li>
                            <?php else :?>
                            <li><a href="/<?=$shikibetushi_1?>/input/" target="_blank"><img src="/img/event/<?=$bannerFile_1?>" alt=""></a></li>
                            <?php endif; ?>
                        <?php else :?>
                        <?php endif; ?> -->
                    </ul>
                </div>
            
            <!--クルーズ表示-->
            <?php if ($pcrDisplay || $pctDisplay) : ?>
                <div class="mv_event_content">
                    <p class="title">各種旅客手荷物のお申し込み</p>
                    <ul class="bannerList">
                        <!--prc-->
                        
                        <?php if ($main_stDate_pcr <= $nowDate && $nowDate <= $main_edDate_pcr) : ?>
                            <li><img src="/img/event/btn_maintain.png" alt=""></li>
                        <?php else :?>
                            <li><a href="/<?=$shikibetushi_pcr?>/input/" target="_blank"><img src="/img/event/<?=$bannerFile_pcr?>" alt="クルーズ"></a></li>
                        <?php endif; ?>
                        
                            
                        <!--pct-->
                        <?php if ($main_stDate_pct <= $nowDate && $nowDate <= $main_edDate_pct) : ?>
                            <li><img src="/img/event/btn_maintain.png" alt=""></li>
                        <?php else :?>
                            <li><a href="/<?=$shikibetushi_pct?>/input/" target="_blank"><img src="/img/event/<?=$bannerFile_pct?>" alt="クルーズ"></a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div> 
<!-- ここまで 各イベントのボタン表示 -->
<?php endif; ?>