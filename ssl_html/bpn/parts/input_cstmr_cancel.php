                        <div class="dl_block comiket_block">
                            <dl>
                                <dt id="event_sel">
                                    出展イベント
                                </dt>
                                <dd>
<!--                                        <table>
                                            <tr>
                                                <td class="event_eventsub_td_name" style="">-->
                                                    <?php $inputMode = $bpn001Out->input_mode(); ?>
                                                    <span class="<?php if(empty($inputMode)) :?>comiket_diplay_none<?php endif; ?>" >
<?php
            echo Sgmov_View_Bpn_Input::_getLabelSelectPulldownData($bpn001Out->event_cds(), $bpn001Out->event_lbls(), $bpn001Out->event_cd_sel());
?>
                                        &nbsp;&nbsp;
                                                    </span>
                                                    <select name="event_sel" class="<?php if(!empty($inputMode)) :?>comiket_diplay_none<?php endif; ?>">
                                                        <option value="" timeoverflg="0">選択してください</option>
<?php
Sgmov_Component_Log::debug($bpn001Out);
            echo Sgmov_View_Bpn_Input::_createPulldown($bpn001Out->event_cds(), $bpn001Out->event_lbls(), $bpn001Out->event_cd_sel(), $bpn001Out->eve_timeover_flg(), $bpn001Out->eve_timeover_date());
?>
                                                    </select>
                                                    <input type="hidden" id="hid_timezone_flg" name="hid_timezone_flg" value="" />
                                                    <select name="eventsub_sel" class="eventsub_sel">
                                                        <option value="">選択してください</option>
<?php
            echo Sgmov_View_Bpn_Input::_createPulldown($bpn001Out->eventsub_cds(), $bpn001Out->eventsub_lbls(), $bpn001Out->eventsub_cd_sel());
?>
                                                    </select>
                                                    <!--<div class="paste_tag">-->
                                                        <br/>
                                                    <!--</div>-->
<?php
        $eventName = Sgmov_View_Bpn_Input::_getLabelSelectPulldownData($bpn001Out->event_cds(), $bpn001Out->event_lbls(), $bpn001Out->event_cd_sel());
        $eventsubName = Sgmov_View_Bpn_Input::_getLabelSelectPulldownData($bpn001Out->eventsub_cds(), $bpn001Out->eventsub_lbls(), $bpn001Out->eventsub_cd_sel());
        if(strpos($eventName,'デザインフェスタ') !== false){
        echo '<!--img src="/dsn/images/logo.gif"-->';
        }
?>
<?php
    // キャッシュ対策
    $sysdate = new DateTime();
    $strSysdate = $sysdate->format('YmdHi');
?>

                                        <div style="display: none;" class="event_data">
                                                <?php  foreach($dispItemInfo["eventsub_list"] as $key => $val): ?>
                                                    <div class="eventsub-info-list"
                                                         eventsub-id="<?php echo $val["id"]; ?>"
                                                         eventsub-business="<?php echo $val["business"]; ?>"
                                                         eventsub-individual="<?php echo $val["individual"]; ?>"
                                                         eventsub-place="<?php echo $val["place"]; ?>"
                                                         eventsub-term-fr="<?php echo $val["term_fr"]; ?>"
                                                         eventsub-term-to="<?php echo $val["term_to"]; ?>"
                                                         eventsub-term-fr-nm="<?php echo $val["term_fr_nm"]; ?>"
                                                         eventsub-term-to-nm="<?php echo $val["term_to_nm"]; ?>"

                                                        eventsub-is-booth-position="<?php echo $val["is_booth_position"]; ?>"
                                                      
                                                        eventsub-is-departure-date-range="<?php echo @$val["is_departure_date_range"]; ?>"
                                                        eventsub-is-arrival-date-range="<?php echo @$val["is_arrival_date_range"]; ?>"
                                                        eventsub-is_manual_display="<?php echo @$val["is_manual_display"]; ?>"
                                                        eventsub-is_paste_display="<?php echo @$val["is_paste_display"]; ?>"
                                                        eventsub-is_building_display="<?php echo @$val["is_building_display"]; ?>"

                                                        eventsub-kojin_box_col_date_flg="<?php echo @$val["kojin_box_col_date_flg"]; ?>"
                                                        eventsub-kojin_box_col_time_flg="<?php echo @$val["kojin_box_col_time_flg"]; ?>"
                                                        eventsub-kojin_box_dlv_date_flg="<?php echo @$val["kojin_box_dlv_date_flg"]; ?>"
                                                        eventsub-kojin_box_dlv_time_flg="<?php echo @$val["kojin_box_dlv_time_flg"]; ?>"
                                                        eventsub-kojin_cag_col_date_flg="<?php echo @$val["kojin_cag_col_date_flg"]; ?>"
                                                        eventsub-kojin_cag_col_time_flg="<?php echo @$val["kojin_cag_col_time_flg"]; ?>"
                                                        eventsub-kojin_cag_dlv_date_flg="<?php echo @$val["kojin_cag_dlv_date_flg"]; ?>"
                                                        eventsub-kojin_cag_dlv_time_flg="<?php echo @$val["kojin_cag_dlv_time_flg"]; ?>"
                                                        eventsub-hojin_box_col_date_flg="<?php echo @$val["hojin_box_col_date_flg"]; ?>"
                                                        eventsub-hojin_box_col_time_flg="<?php echo @$val["hojin_box_col_time_flg"]; ?>"
                                                        eventsub-hojin_box_dlv_date_flg="<?php echo @$val["hojin_box_dlv_date_flg"]; ?>"
                                                        eventsub-hojin_box_dlv_time_flg="<?php echo @$val["hojin_box_dlv_time_flg"]; ?>"
                                                        eventsub-hojin_cag_col_date_flg="<?php echo @$val["hojin_cag_col_date_flg"]; ?>"
                                                        eventsub-hojin_cag_col_time_flg="<?php echo @$val["hojin_cag_col_time_flg"]; ?>"
                                                        eventsub-hojin_cag_dlv_date_flg="<?php echo @$val["hojin_cag_dlv_date_flg"]; ?>"
                                                        eventsub-hojin_cag_dlv_time_flg="<?php echo @$val["hojin_cag_dlv_time_flg"]; ?>"
                                                        eventsub-hojin_kas_col_date_flg="<?php echo @$val["hojin_kas_col_date_flg"]; ?>"
                                                        eventsub-hojin_kas_col_time_flg="<?php echo @$val["hojin_kas_col_time_flg"]; ?>"
                                                        eventsub-hojin_kas_dlv_date_flg="<?php echo @$val["hojin_kas_dlv_date_flg"]; ?>"
                                                        eventsub-hojin_kas_dlv_time_flg="<?php echo @$val["hojin_kas_dlv_time_flg"]; ?>"
                                                    
                                                        eventsub-kojin_box_del_date_flg="<?php echo @$val["kojin_box_del_date_flg"]; ?>"
                                                        eventsub-kojin_box_del_time_flg="<?php echo @$val["kojin_box_del_time_flg"]; ?>"
                                                        eventsub-hojin_box_del_date_flg="<?php echo @$val["hojin_box_del_date_flg"]; ?>"
                                                        eventsub-hojin_box_del_time_flg="<?php echo @$val["hojin_box_del_time_flg"]; ?>"

                                                        eventsub-kojin_box_col_flg="<?php echo @$val["kojin_box_col_flg"]; ?>"
                                                        eventsub-kojin_box_dlv_flg="<?php echo @$val["kojin_box_dlv_flg"]; ?>"
                                                        eventsub-kojin_cag_col_flg="<?php echo @$val["kojin_cag_col_flg"]; ?>"
                                                        eventsub-kojin_cag_dlv_flg="<?php echo @$val["kojin_cag_dlv_flg"]; ?>"
                                                        eventsub-hojin_box_col_flg="<?php echo @$val["hojin_box_col_flg"]; ?>"
                                                        eventsub-hojin_box_dlv_flg="<?php echo @$val["hojin_box_dlv_flg"]; ?>"
                                                        eventsub-hojin_cag_col_flg="<?php echo @$val["hojin_cag_col_flg"]; ?>"
                                                        eventsub-hojin_cag_dlv_flg="<?php echo @$val["hojin_cag_dlv_flg"]; ?>"
                                                        eventsub-hojin_kas_col_flg="<?php echo @$val["hojin_kas_col_flg"]; ?>"
                                                        eventsub-hojin_kas_dlv_flg="<?php echo @$val["hojin_kas_dlv_flg"]; ?>"

                                                    ></div>
                                                <?php endforeach; ?>
                                        </div>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="event_address">
                                    会場名
                                </dt>
                                <dd>
                                <span class="event-place-lbl">
                                    <?php
                                        $selectedEventData = $dispItemInfo["eventsub_selected_data"];
                                        echo $selectedEventData["venue"];
                                    ?>
                                </span>
                                <input class="" style="width:80%;" autocapitalize="off" inputmode="eventsub_address" name="eventsub_address" data-pattern="" placeholder="" type="hidden" value="<?php echo $selectedEventData["venue"]; ?>" />
                                </dd>
                            </dl>
                            <dl>
                                <dt id="eventsub_term">
                                    <?php if($bpn001Out->eventsub_cd_sel() == "303"): ?> 
                                        会期
                                    <?php else: ?>
                                        期間
                                    <?php endif;?>
                                </dt>
                                <dd>
                                    <?php if($bpn001Out->eventsub_term_fr_nm() == $bpn001Out->eventsub_term_to_nm()):?>
                                        <span class="event-term_fr-lbl"><?php echo $bpn001Out->eventsub_term_fr_nm(); ?></span>
                                    <?php else: ?>
                                        <span class="event-term_fr-lbl"><?php echo $bpn001Out->eventsub_term_fr_nm(); ?></span>
                                        <span class="event-term_fr-str">&nbsp;から&nbsp;</span>
                                        <span class="event-term_to-lbl"><?php echo $bpn001Out->eventsub_term_to_nm(); ?></span>
                                    <?php endif; ?>
                                    <input class="" style="width:100px;" autocapitalize="off" inputmode="eventsub_term_fr" name="eventsub_term_fr" data-pattern="" placeholder="" type="hidden" value="<?php echo $bpn001Out->eventsub_term_fr(); ?>" />
                                   
                                    <input class="" style="width:100px;" autocapitalize="off" inputmode="eventsub_term_to" name="eventsub_term_to" data-pattern="" placeholder="" type="hidden" value="<?php echo $bpn001Out->eventsub_term_to(); ?>" />
                                </dd>
                            </dl>
                            <dl style="display: none;">
                                <dt id="comiket_div">
                                    識別
                                </dt>
                                <dd>
                                    <?php foreach($dispItemInfo['comiket_div_lbls'] as $key => $val) : ?>
                                        <?php if ($bpn001Out->comiket_div() == $key): ?>
                                            <label class="radio-label comiket_div<?php echo $key; ?>" for="comiket_div<?php echo $key; ?>">
                                                <?php echo $val; ?>
                                            </label>
                                            <br />
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </dd>
                            </dl>

                        <?php if($bpn001Out->eventsub_cd_sel() == "302" && $bpn001Out->bpn_type() == "2" && $bpn001Out->shohin_pattern() == "2"):?>
                            <dl class="comiket-personal-name-seimei2">
                                <dt id="comiket_personal_name-seimei2">
                                    お申込者
                                </dt>
                                <dd>
                                <?php echo $bpn001Out->comiket_personal_name_sei();?>&nbsp;
                                <?php echo $bpn001Out->comiket_personal_name_mei();?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_tel">
                                    電話番号
                                </dt>
                                <dd>
                                    <?php echo $bpn001Out->comiket_tel();?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_mail">
                                    メールアドレス
                                </dt>
                                <dd>
                                    <?php echo $bpn001Out->comiket_mail();?>
                                </dd>
                            </dl>
                        <?php endif; ?>


                        <?php if($bpn001Out->bpn_type() == "1"):// 物販 ?>
                            <?php if($bpn001Out->comiket_div() == Sgmov_View_Bpn_Common::COMIKET_DEV_BUSINESS) : ?>
                                <dl class="comiket_customer_cd2">
                                    <dt id="comiket_customer_cd2">
                                        お取引先コード<br/>（お客様コード）<br/>
                                    </dt>
                                    <dd>
                                        <?php echo @substr($bpn001Out->comiket_customer_cd(), 0, 11) ?>
                                    </dd>
                                </dl>
                            <?php endif; ?>
                            <?php $officeName = @$bpn001Out->office_name(); ?>
                            <?php if (@!empty($officeName)) : ?>
                            <dl class="office-name">
                                <dt id="office_name">
                                    お申込者
                                </dt>
                                <dd>
                                <?php echo $bpn001Out->office_name() ?>
                                </dd>
                            </dl>
                            <?php else: ?>
                                <dl class="comiket-personal-name-seimei2">
                                    <dt id="comiket_personal_name-seimei2">
                                        お申込者
                                    </dt>
                                    <dd>
                                    <?php echo $bpn001Out->comiket_personal_name_sei();?>&nbsp;
                                    <?php echo $bpn001Out->comiket_personal_name_mei();?>
                                    </dd>
                                </dl>
                            <?php endif; ?>
                            <dl>
                                <dt id="comiket_zip">
                                    郵便番号
                                </dt>

                                <dd>
                                    〒<?php echo $bpn001Out->comiket_zip1();?>&nbsp;-&nbsp;<?php echo $bpn001Out->comiket_zip2();?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_pref">
                                    都道府県
                                </dt>
                                <dd>
<?php
        echo Sgmov_View_Bpn_Input::_getLabelSelectPulldownData($bpn001Out->comiket_pref_cds(), $bpn001Out->comiket_pref_lbls(), $bpn001Out->comiket_pref_cd_sel());
?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_address">
                                    市区町村
                                </dt>
                                <dd>
                                    <?php echo $bpn001Out->comiket_address();?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_building">
                                    番地・建物名・部屋番号
                                </dt>
                                <dd>
                                    <?php echo $bpn001Out->comiket_building();?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_tel">
                                    電話番号
                                </dt>
                                <dd>
                                    <?php echo $bpn001Out->comiket_tel();?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_mail">
                                    メールアドレス
                                </dt>
                                <dd>
                                    <?php echo $bpn001Out->comiket_mail();?>
                                </dd>
                            </dl>
                        <?php endif; ?>
                        <?php if($bpn001Out->eventsub_cd_sel() == "303" && $bpn001Out->bpn_type() == "1"): ?>
                             <dl>
                                <dt id="comiket_mail">
                                    商品引き渡し日
                                </dt>
                                <dd>
                                    <?php echo $dispItemInfo["collect_date"];?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_booth_name">
                                    ブース名
                                </dt>
                                <dd>
                                    <?php echo $bpn001Out->comiket_booth_name() ?>
                                </dd>
                            </dl>
                            <dl class="class_building_name_sel">
                                <dt id="building_name_sel">
                                    ブースNO
                                </dt>
                                <dd>
                                    <?php if($bpn001Out->building_name() != "その他"): ?>
                                        <?php echo $bpn001Out->building_name(); ?>
                                        <span style="font-size: 0.5em;">ホール</span>&nbsp;
                                    <?php endif; ?>
                                    <?php echo $bpn001Out->building_booth_position(); ?>&nbsp;<?php echo $bpn001Out->comiket_booth_num(); ?>
                                </dd>
                            </dl>
                        <?php endif; ?> 
                        </div>