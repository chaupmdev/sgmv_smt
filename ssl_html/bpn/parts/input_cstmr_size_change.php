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
        echo '<!--img src="/eve/images/logo.gif"-->';
        }
?>
<?php
    // キャッシュ対策
    $sysdate = new DateTime();
    $strSysdate = $sysdate->format('YmdHi');
?>

<!--                                                </td>
                                                <td class="event_eventsub_td_dl_item" style="">-->
<!--                                                    <div class="pc_dl_area2">
                                                        <div style="margin-bottom:3px;display:none;" class="eventsub_dl_link pasete_tag">
                                                            <img src="/images/common/img_icon_pdf.gif" width="18" height="21" alt="">
                                                            <a style="color: blue;" class="paste_tag_link" href="/eve/pdf/paste_tag/paste_tag_<?php echo $bpn001Out->eventsub_cd_sel(); ?>.pdf" target="_blank">貼付票</a>
                                                        </div>
                                                        <br/>
                                                        <div class="eventsub_dl_link manual" style="display:none;">
                                                            <img src="/images/common/img_icon_pdf.gif" width="18" height="21" alt="">
                                                            <a style="color: blue;" class="manual_link" href="/eve/pdf/manual/<?php echo $eventName; ?><?php echo $eventsubName; ?>.pdf" target="_blank">説明書</a>
                                                        </div>
                                                    </div>-->
<!--                                                </td>
                                            </tr>
                                        </table>
                                        <br/>-->
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
                                                         eventsub-is_booth_display="<?php echo @$val["is_booth_display"]; ?>"

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
                                    期間
                                </dt>
                                <dd<?php
                                    if (isset($e)
                                       && ($e->hasErrorForId('eventsub_term_fr') || $e->hasErrorForId('evensubt_term_to'))
                                    ) {
                                        echo ' class="form_error"';
                                    }
                                ?>>

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
                                            <label class="radio-label comiket_div<?php echo $key; ?>" for="comiket_div<?php echo $key; ?>" style="display:none;">
                                                <input<?php if ($bpn001Out->comiket_div() == $key) echo ' checked="checked"'; ?> 
                                                    id="comiket_div<?php echo $key; ?>" name="comiket_div" type="radio" value="<?php echo $key; ?>" style="display: none;" />
                                                <?php echo $val; ?>
                                            </label>
                                            <br />
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </dd>
                            </dl>
                            <dl class="comiket_customer_cd"  style="display:none;">
                                <dt id="comiket_customer_cd">
                                    お取引先コード<br/>（お客様コード）<br/>
                                </dt>
                                <dd>
                                    <?php echo @substr($bpn001Out->comiket_customer_cd(), 0, 11) ?>
                                </dd>
                            </dl>
                            <dl class="office-name" style="display: none;">
                                <dt id="office_name">
                                    お申込者
                                </dt>
                                <dd>
                                <span class="office_name-lbl"><?php echo $bpn001Out->office_name();?></span>&nbsp;
                                <input class="" style="width:60%;" maxlength="16" autocapitalize="off" inputmode="office_name" name="office_name" data-pattern="" placeholder="" type="text" value="<?php echo $bpn001Out->office_name() ?>" />
                                </dd>
                            </dl>
                            <dl class="comiket-personal-name-seimei">
                                <dt id="comiket_personal_name-seimei">
                                    お申込者
                                </dt>
                                <dd>
                                <?php echo $bpn001Out->comiket_personal_name_sei();?>&nbsp;
                                <?php echo $bpn001Out->comiket_personal_name_mei();?>
                                <input class="" style="width:30%;" maxlength="8" autocapitalize="off" inputmode="comiket_personal_name_sei" name="comiket_personal_name_sei" data-pattern="" placeholder="例）佐川" type="hidden" value="<?php echo $bpn001Out->comiket_personal_name_sei() ?>" />
                                <input class="" style="width:30%;" maxlength="8" autocapitalize="off" inputmode="comiket_personal_name_mei" name="comiket_personal_name_mei" data-pattern="" placeholder="例）花子" type="hidden" value="<?php echo $bpn001Out->comiket_personal_name_mei() ?>" />
                                </dd>
                            </dl>
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
                        </div>