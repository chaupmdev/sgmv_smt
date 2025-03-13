                        <div class="dl_block comiket_block">
                            <dl>
                                <dt id="event_sel">
                                    出展イベント
                                </dt>
                                <dd>
                                    <?php $inputMode = $eve001Out->input_mode(); ?>
                                    <span class="<?php if(empty($inputMode)) :?>comiket_diplay_none<?php endif; ?>" >
<?php
            echo Sgmov_View_Azk_Input::_getLabelSelectPulldownData($eve001Out->event_cds(), $eve001Out->event_lbls(), $eve001Out->event_cd_sel());
?>
                                    &nbsp;&nbsp;
                                    </span>
                                    <select name="event_sel" class="<?php if(!empty($inputMode)) :?>comiket_diplay_none<?php endif; ?>">
                                        <option value="" timeoverflg="0">選択してください</option>
<?php
            echo Sgmov_View_Azk_Input::_createPulldown($eve001Out->event_cds(), $eve001Out->event_lbls(), $eve001Out->event_cd_sel(), $eve001Out->eve_timeover_flg(), $eve001Out->eve_timeover_date());
?>
                                    </select>
                                    <input type="hidden" id="hid_timezone_flg" name="hid_timezone_flg" value="" />
                                    <select name="eventsub_sel" class="eventsub_sel">
                                        <option value="">選択してください</option>
<?php
            echo Sgmov_View_Azk_Input::_createPulldown($eve001Out->eventsub_cds(), $eve001Out->eventsub_lbls(), $eve001Out->eventsub_cd_sel());
?>
                                    </select>
                                        <br/>
<?php
        $eventName = Sgmov_View_Azk_Input::_getLabelSelectPulldownData($eve001Out->event_cds(), $eve001Out->event_lbls(), $eve001Out->event_cd_sel());
        $eventsubName = Sgmov_View_Azk_Input::_getLabelSelectPulldownData($eve001Out->eventsub_cds(), $eve001Out->eventsub_lbls(), $eve001Out->eventsub_cd_sel());
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
                                    <?php if($eve001Out->eventsub_term_fr_nm() == $eve001Out->eventsub_term_to_nm()):?>
                                        <span class="event-term_fr-lbl"><?php echo $eve001Out->eventsub_term_fr_nm(); ?></span>
                                    <?php else: ?>
                                        <span class="event-term_fr-lbl"><?php echo $eve001Out->eventsub_term_fr_nm(); ?></span>
                                        <span class="event-term_fr-str">&nbsp;から&nbsp;</span>
                                        <span class="event-term_to-lbl"><?php echo $eve001Out->eventsub_term_to_nm(); ?></span>
                                    <?php endif; ?>
                                    <input class="" style="width:100px;" autocapitalize="off" inputmode="eventsub_term_fr" name="eventsub_term_fr" data-pattern="" placeholder="" type="hidden" value="<?php echo $eve001Out->eventsub_term_fr(); ?>" />
                                    <input class="" style="width:100px;" autocapitalize="off" inputmode="eventsub_term_to" name="eventsub_term_to" data-pattern="" placeholder="" type="hidden" value="<?php echo $eve001Out->eventsub_term_to(); ?>" />
                                </dd>
                            </dl>
                            <dl style="display: none;">
                                <dt id="comiket_div">
                                    識別
                                </dt>
                                <dd>
                                    <?php foreach($dispItemInfo['comiket_div_lbls'] as $key => $val) : ?>
                                        <?php if ($eve001Out->comiket_div() == $key): ?>
                                            <label class="radio-label comiket_div<?php echo $key; ?>" for="comiket_div<?php echo $key; ?>" style="display:none;">
                                                <input<?php if ($eve001Out->comiket_div() == $key) echo ' checked="checked"'; ?> 
                                                    id="comiket_div<?php echo $key; ?>" name="comiket_div" type="radio" value="<?php echo $key; ?>" style="display: none;" />
                                                <?php echo $val; ?>
                                            </label>
                                            <br />
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    <div class="attention_customer_cd" style="">
                                        <strong class="disp_comiket red">※佐川急便のお取引先コード（お客様コード）をお持ちの方はこちら</strong>
                                    </div>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_staff_seimei_furi">
                                    お申込者
                                </dt>
                                <dd>
                                    <?php echo $eve001Out->comiket_personal_name_sei() ?>&nbsp;<?php echo $eve001Out->comiket_personal_name_mei() ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_tel">
                                    電話番号
                                </dt>
                                <dd>
                                    <span><?php echo $eve001Out->comiket_tel();?></span>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_mail">
                                    メールアドレス
                                </dt>
                                <dd>
                                    <?php echo $eve001Out->comiket_mail();?>
                                    <br>
                                    <p class="red">
                                        ※迷惑メールを設定されている方は「sagawa-mov.co.jp」を受信する設定にしてください。
                                        <br />詳しくは
                                        <a href="#bounce_mail">こちら</a>
                                    </p>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_collect_date">
                                    利用日
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_collect_date')) { echo ' class="form_error"'; } ?>>
                                    <?php $displaySetting = "block"; ?>
                                    <?php if(isset($dispItemInfo["eventsub_selected_data"]["is_eq_collect"]) && $dispItemInfo["eventsub_selected_data"]["is_eq_collect"]) : ?>
                                        <?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_collect_fr"]; ?>
                                        <?php $displaySetting = "none"; ?>
                                    <?php else: ?>

                                        <input type="hidden" id="hid_comiket-detail-collect-date-from"  name="hid_comiket-detail-collect-date-from" value="<?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_collect_fr_dt"]; ?>" />
                                        <input type="hidden" id="hid_comiket-detail-collect-date-to"    name="hid_comiket-detail-collect-date-to"   value="<?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_collect_to_dt"]; ?>" />
                                    <?php endif; ?>
                                        <div class="comiket_detail_collect_date_parts" style="display:<?php echo $displaySetting; ?>">
                                            <?php echo $eve001Out->comiket_detail_collect_date_year_sel();?>年<?php echo $eve001Out->comiket_detail_collect_date_month_sel();?>月<?php echo $eve001Out->comiket_detail_collect_date_day_sel();?>日（<?php echo Sgmov_View_Azk_Input::_getWeek($eve001Out->comiket_detail_collect_date_year_sel(), $eve001Out->comiket_detail_collect_date_month_sel(), $eve001Out->comiket_detail_collect_date_day_sel()); ?>）&nbsp;
                                        </div>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_name">
                                    取り出し回数
                                </dt>
                                <dd>
                                    <?php foreach($dispItemInfo['comiket_detail_azukari_kaisu_type_lbls'] as $key => $val) : ?>
                                        <?php if ($eve001Out->comiket_detail_azukari_kaisu_type_sel() == $key): ?>
                                            <?php echo $val; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </dd>
                            </dl>
                            <dl class="service-inbound-item">
                                <dt id="comiket_box_num_ary">
                                    手荷物数量
                                </dt>
                                <dd>
                                    <table>
                                        <tr>
                                            <td class='box_table_td' style='vertical-align: middle;width:40%;'>
                                                <table>
                                                    <?php foreach($dispItemInfo['comiket_box_lbls'] as $key => $val) : ?>
                                                        <?php $boxNum = $eve001Out->comiket_box_num_ary($val["id"]); ?>
                                                        <?php if(!empty($boxNum)) : ?>
                                                            <tr>
                                                                <td class='comiket_box_item_name'>
                                                                    <?php echo empty($val["name"]) ? "" : $val["name"]; ?>&nbsp;
                                                                </td>
                                                                <td class='comiket_box_item_value'>
                                                                    <?php echo $boxNum;?>個
                                                                    &nbsp;
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </dd>
                            </dl>
                        </div>