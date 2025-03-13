                        <div class="dl_block comiket_block">
                            <dl>
                                <dt id="event_sel">
                                    出展イベント
                                </dt>
                                <dd>
<!--                                        <table>
                                            <tr>
                                                <td class="event_eventsub_td_name" style="">-->
                                                    <?php $inputMode = $eve001Out->input_mode(); ?>
                                                    <span class="<?php if(empty($inputMode)) :?>comiket_diplay_none<?php endif; ?>" >
<?php
            echo Sgmov_View_Tms_Input::_getLabelSelectPulldownData($eve001Out->event_cds(), $eve001Out->event_lbls(), $eve001Out->event_cd_sel());
?>
                                        &nbsp;&nbsp;
                                                    </span>
                                                    <select name="event_sel" class="<?php if(!empty($inputMode)) :?>comiket_diplay_none<?php endif; ?>">
                                                        <option value="" timeoverflg="0">選択してください</option>
<?php
Sgmov_Component_Log::debug($eve001Out);
            echo Sgmov_View_Tms_Input::_createPulldown($eve001Out->event_cds(), $eve001Out->event_lbls(), $eve001Out->event_cd_sel(), $eve001Out->Tms_timeover_flg(), $eve001Out->Tms_timeover_date());
?>
                                                    </select>
                                                    <input type="hidden" id="hid_timezone_flg" name="hid_timezone_flg" value="" />
                                                    <select name="eventsub_sel" class="eventsub_sel">
                                                        <option value="">選択してください</option>
<?php
            echo Sgmov_View_Tms_Input::_createPulldown($eve001Out->eventsub_cds(), $eve001Out->eventsub_lbls(), $eve001Out->eventsub_cd_sel());
?>
                                                    </select>
                                                    <!--<div class="paste_tag">-->
                                                        <br/>
                                                    <!--</div>-->
<?php
        $eventName = Sgmov_View_Tms_Input::_getLabelSelectPulldownData($eve001Out->event_cds(), $eve001Out->event_lbls(), $eve001Out->event_cd_sel());
        $eventsubName = Sgmov_View_Tms_Input::_getLabelSelectPulldownData($eve001Out->eventsub_cds(), $eve001Out->eventsub_lbls(), $eve001Out->eventsub_cd_sel());
        if(strpos($eventName,'デザインフェスタ') !== false){
        echo '<!--img src="/dsn/images/logo.gif"-->';
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
                                                            <a style="color: blue;" class="paste_tag_link" href="/dsn/pdf/paste_tag/paste_tag_<?php echo $eve001Out->eventsub_cd_sel(); ?>.pdf" target="_blank">貼付票</a>
                                                        </div>
                                                        <br/>
                                                        <div class="eventsub_dl_link manual" style="display:none;">
                                                            <img src="/images/common/img_icon_pdf.gif" width="18" height="21" alt="">
                                                            <a style="color: blue;" class="manual_link" href="/dsn/pdf/manual/<?php echo $eventName; ?><?php echo $eventsubName; ?>.pdf" target="_blank">説明書</a>
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

                                                         eventsub-outbound-collect-fr="<?php echo @$val["outbound_collect_fr"]; ?>"
                                                         eventsub-outbound-collect-to="<?php echo @$val["outbound_collect_to"]; ?>"
                                                         eventsub-outbound-delivery-fr="<?php echo @$val["outbound_delivery_fr"]; ?>"
                                                         eventsub-outbound-delivery-to="<?php echo @$val["outbound_delivery_to"]; ?>"

                                                         eventsub-inbound-collect-fr="<?php echo @$val["inbound_collect_fr"]; ?>"
                                                         eventsub-inbound-collect-to="<?php echo @$val["inbound_collect_to"]; ?>"
                                                         eventsub-inbound-delivery-fr="<?php echo @$val["inbound_delivery_fr"]; ?>"
                                                         eventsub-inbound-delivery-to="<?php echo @$val["inbound_delivery_to"]; ?>"

                                                         eventsub-is-departure-date-range="<?php echo @$val["is_departure_date_range"]; ?>"
                                                         eventsub-is-arrival-date-range="<?php echo @$val["is_arrival_date_range"]; ?>"

                                                         eventsub-is_eq_outbound_collect="<?php echo @$val["is_eq_outbound_collect"]; ?>"
                                                         eventsub-is_eq_outbound_delivery="<?php echo @$val["is_eq_outbound_delivery"]; ?>"
                                                         eventsub-is_eq_inbound_collect="<?php echo @$val["is_eq_inbound_collect"]; ?>"
                                                         eventsub-is_eq_inbound_delivery="<?php echo @$val["is_eq_inbound_delivery"]; ?>"



                                                        eventsub-outbound_collect_fr_year="<?php echo @$val["outbound_collect_fr_year"]; ?>"
                                                        eventsub-outbound_collect_fr_month="<?php echo @$val["outbound_collect_fr_month"]; ?>"
                                                        eventsub-outbound_collect_fr_day="<?php echo @$val["outbound_collect_fr_day"]; ?>"

                                                        eventsub-outbound_collect_to_year="<?php echo @$val["outbound_collect_to_year"]; ?>"
                                                        eventsub-outbound_collect_to_month="<?php echo @$val["outbound_collect_to_month"]; ?>"
                                                        eventsub-outbound_collect_to_day="<?php echo @$val["outbound_collect_to_day"]; ?>"

                                                        eventsub-outbound_delivery_fr_year="<?php echo @$val["outbound_delivery_fr_year"]; ?>"
                                                        eventsub-outbound_delivery_fr_month="<?php echo @$val["outbound_delivery_fr_month"]; ?>"
                                                        eventsub-outbound_delivery_fr_day="<?php echo @$val["outbound_delivery_fr_day"]; ?>"

                                                        eventsub-outbound_delivery_to_year="<?php echo @$val["outbound_delivery_to_year"]; ?>"
                                                        eventsub-outbound_delivery_to_month="<?php echo @$val["outbound_delivery_to_month"]; ?>"
                                                        eventsub-outbound_delivery_to_day="<?php echo @$val["outbound_delivery_to_day"]; ?>"


                                                        eventsub-inbound_collect_fr_year="<?php echo @$val["inbound_collect_fr_year"]; ?>"
                                                        eventsub-inbound_collect_fr_month="<?php echo @$val["inbound_collect_fr_month"]; ?>"
                                                        eventsub-inbound_collect_fr_day="<?php echo @$val["inbound_collect_fr_day"]; ?>"

                                                        eventsub-inbound_collect_to_year="<?php echo @$val["inbound_collect_to_year"]; ?>"
                                                        eventsub-inbound_collect_to_month="<?php echo @$val["inbound_collect_to_month"]; ?>"
                                                        eventsub-inbound_collect_to_day="<?php echo @$val["inbound_collect_to_day"]; ?>"

                                                        eventsub-inbound_delivery_fr_year="<?php echo @$val["inbound_delivery_fr_year"]; ?>"
                                                        eventsub-inbound_delivery_fr_month="<?php echo @$val["inbound_delivery_fr_month"]; ?>"
                                                        eventsub-inbound_delivery_fr_day="<?php echo @$val["inbound_delivery_fr_day"]; ?>"

                                                        eventsub-inbound_delivery_to_year="<?php echo @$val["inbound_delivery_to_year"]; ?>"
                                                        eventsub-inbound_delivery_to_month="<?php echo @$val["inbound_delivery_to_month"]; ?>"
                                                        eventsub-inbound_delivery_to_day="<?php echo @$val["inbound_delivery_to_day"]; ?>"


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

                                                        <?php
                                                            // 引渡フラグ
                                                        ?>
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
                                <span class="event-term_fr-lbl"><?php echo $eve001Out->eventsub_term_fr_nm(); ?></span>
                                <input class="" style="width:100px;" autocapitalize="off" inputmode="eventsub_term_fr" name="eventsub_term_fr" data-pattern="" placeholder="" type="hidden" value="<?php echo $eve001Out->eventsub_term_fr(); ?>" />
                                <span class="event-term_fr-str" style="display:none">&nbsp;から&nbsp;</span>
                                <span class="event-term_to-lbl"><?php echo $eve001Out->eventsub_term_to_nm(); ?></span>
                                <input class="" style="width:100px;" autocapitalize="off" inputmode="eventsub_term_to" name="eventsub_term_to" data-pattern="" placeholder="" type="hidden" value="<?php echo $eve001Out->eventsub_term_to(); ?>" />

                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_div">
                                    識別
                                </dt>
                                <dd>
                                    <?php foreach($dispItemInfo['comiket_div_lbls'] as $key => $val) : ?>
                                        <?php if ($eve001Out->comiket_div() == $key): ?>
                                            <label class="radio-label comiket_div<?php echo $key; ?>" for="comiket_div<?php echo $key; ?>">
                                                <?php echo $val; ?>
                                            </label>
                                            <br />
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </dd>
                            </dl>
                            <?php if($eve001Out->comiket_div() == Sgmov_View_Tms_Common::COMIKET_DEV_BUSINESS) : ?>
                                <dl class="comiket_customer_cd2">
                                    <dt id="comiket_customer_cd2">
                                        お取引先コード<br/>（お客様コード）<br/>
                                    </dt>
                                    <dd>
                                        <?php echo @substr($eve001Out->comiket_customer_cd(), 0, 11) ?>
                                    </dd>
                                </dl>
                            <?php endif; ?>
                            <?php $officeName = @$eve001Out->office_name(); ?>
                            <?php if (@!empty($officeName)) : ?>
                            <dl class="office-name">
                                <dt id="office_name">
                                    お申込者
                                </dt>
                                <dd>
                                <?php echo $eve001Out->office_name() ?>
                                </dd>
                            </dl>
                            <?php else: ?>
                                <dl class="comiket-personal-name-seimei2">
                                    <dt id="comiket_personal_name-seimei2">
                                        お申込者
                                    </dt>
                                    <dd>
                                    <?php echo $eve001Out->comiket_personal_name_sei();?>&nbsp;
                                    <?php echo $eve001Out->comiket_personal_name_mei();?>
                                    </dd>
                                </dl>
                            <?php endif; ?>
                            <dl>
                                <dt id="comiket_zip">
                                    郵便番号
                                </dt>

                                <dd>
                                    〒<?php echo $eve001Out->comiket_zip1();?>&nbsp;-&nbsp;<?php echo $eve001Out->comiket_zip2();?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_pref">
                                    都道府県
                                </dt>
                                <dd>
                                    <span class="comiket_pref_nm-lbl"><?php echo $eve001Out->comiket_pref_nm();?></span>

<?php
        echo Sgmov_View_Tms_Input::_getLabelSelectPulldownData($eve001Out->comiket_pref_cds(), $eve001Out->comiket_pref_lbls(), $eve001Out->comiket_pref_cd_sel());
?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_address">
                                    市区町村
                                </dt>
                                <dd>
                                    <?php echo $eve001Out->comiket_address();?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_building">
                                    番地・建物名
                                </dt>
                                <dd>
                                    <?php echo $eve001Out->comiket_building();?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_tel">
                                    電話番号
                                </dt>
                                <dd>
                                    <?php echo $eve001Out->comiket_tel();?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_mail">
                                    メールアドレス
                                </dt>
                                <dd>
                                    <?php echo $eve001Out->comiket_mail();?>
                                </dd>
                            </dl>
                            <dl class="class_comiket_booth_name">
                                <dt id="comiket_booth_name">
                                    ブース名
                                </dt>
                                <dd>
                                    <?php echo $eve001Out->comiket_booth_name() ?>
                                </dd>
                            </dl>
                            <dl class="class_building_name_sel">
                                <dt id="building_name_sel">
                                    <?php if ($eve001Out->event_cd_sel() == '2') : ?>
                                        ブース番号
                                    <?php else: ?>
                                        館名
                                    <?php endif; ?>
                                </dt>
                                <dd>
                                    
                                    <?php echo $eve001Out->building_name(); ?>&nbsp;<?php echo $eve001Out->building_booth_position(); ?>&nbsp;<?php echo $eve001Out->comiket_booth_num(); ?>
                                </dd>
                            </dl>

                            <dl>
                                <dt id="comiket_staff_seimei">
                                    当日の担当者名
                                </dt>
                                <dd>
                                    <?php echo $eve001Out->comiket_staff_sei() ?>&nbsp;<?php echo $eve001Out->comiket_staff_mei() ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_staff_seimei_furi">
                                    当日の担当者名（フリガナ）
                                </dt>
                                <dd>
                                    <?php echo $eve001Out->comiket_staff_sei_furi() ?>&nbsp;<?php echo $eve001Out->comiket_staff_mei_furi() ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_staff_tel">
                                    当日の担当者電話番号
                                </dt>
                                <dd>
                                    <?php echo $eve001Out->comiket_staff_tel();?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_type_sel">
                                    往復選択
                                </dt>
                                <dd>
                                    
                                        <?php foreach($dispItemInfo['comiket_detail_type_lbls'] as $key => $val) : ?>
                                            <?php if ($eve001Out->comiket_detail_type_sel() == $key) : ?>
                                                    <?php echo $val; ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    
                                </dd>
                            </dl>
                        </div>
