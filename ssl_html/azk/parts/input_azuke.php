                        <div class="dl_block comiket_block">
                            <dl>
                                <dt id="event_sel" style=" border-top: solid 1px #ccc !important;">
                                    出展イベント
                                </dt>
                                <dd<?php
                                    if (isset($e) && ($e->hasErrorForId('event_sel'))) {
                                        echo ' class="form_error"';
                                    }
                                ?>>
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
                                        <br />
                                    <img src="/azk/images/logo.gif" style="width: 230px;">
<?php

    // キャッシュ対策
    $sysdate = new DateTime();
    $strSysdate = $sysdate->format('YmdHi');

    $eventName = Sgmov_View_Azk_Input::_getLabelSelectPulldownData($eve001Out->event_cds(), $eve001Out->event_lbls(), $eve001Out->event_cd_sel());
    $eventsubName = Sgmov_View_Azk_Input::_getLabelSelectPulldownData($eve001Out->eventsub_cds(), $eve001Out->eventsub_lbls(), $eve001Out->eventsub_cd_sel());
?>
                                                    <div class="sp_dl_area2 paste_tag">

<?php if ($eve001Out->input_mode() != '002') {  // 入力モードが"002"(コミケ)なら説明書のリンクは表示しない ?>
                                                        <!-- <br /> -->
                                                        <!-- <div class="eventsub_dl_link manual">
                                                            <img src="/images/common/img_icon_pdf.gif" width="18" height="21" alt="">
                                                            <a style="color: blue;" class="manual_link" href="/msb/pdf/manual/<?php echo $eventName; ?><?php echo $eventsubName; ?>.pdf<?php echo '?' . $strSysdate; ?>" target="_blank">説明書</a>
                                                        </div> -->
<?php } ?>
                                                    </div>
                                                    <!-- <br/> -->

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
                                <dd<?php
                                    if (isset($e)
                                       && ($e->hasErrorForId('even_address'))
                                    ) {
                                        echo ' class="form_error"';
                                    }
                                ?>>
                                    <span class="event-place-lbl">
                                        <?php
                                            $selectedEventData = $dispItemInfo["eventsub_selected_data"];
                                            echo @$selectedEventData["venue"];
                                        ?>
                                    </span>
                                    <input class="" style="width:80%;" autocapitalize="off" inputmode="eventsub_address" name="eventsub_address" data-pattern="" placeholder="" type="hidden" value="<?php echo @$selectedEventData["venue"]; ?>" />
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
                                        <span class="event-term_to-lbl"><?php echo $eve001Out->eventsub_term_to_nm(); ?></span>&nbsp;まで&nbsp;
                                    <?php endif; ?>
                                    <input class="" style="width:100px;" autocapitalize="off" inputmode="eventsub_term_fr" name="eventsub_term_fr" data-pattern="" placeholder="" type="hidden" value="<?php echo $eve001Out->eventsub_term_fr(); ?>" />
                                    <input class="" style="width:100px;" autocapitalize="off" inputmode="eventsub_term_to" name="eventsub_term_to" data-pattern="" placeholder="" type="hidden" value="<?php echo $eve001Out->eventsub_term_to(); ?>" />
                                </dd>
                            </dl>
                            <dl style="display: none;">
                                <dt>
                                    サービス選択
                                </dt>
                                <dd>
                                    <div>
                                        <label for="service-selected2">
                                            手荷物預かりサービス
                                            &nbsp;<strong class="disp_comiket red">※当日に手荷物をお預けしたい方はこちら。</strong>
                                        <br>
                                    </div>
                                </dd>
                            </dl>
                            <dl style="display:none;">
                                <dt id="comiket_div">
                                    識別<span>必須</span>
                                </dt>
                                <dd<?php
                                    if (isset($e)
                                       && ($e->hasErrorForId('comiket_div'))
                                    ) {
                                        echo ' class="form_error"';
                                    }
                                ?>>
                                    <?php foreach($dispItemInfo['comiket_div_lbls'] as $key => $val) : ?>
                                        <label class="radio-label comiket_div<?php echo $key; ?>" for="comiket_div<?php echo $key; ?>" style="display:none;">
                                            <input<?php if ($eve001Out->comiket_div() == $key) echo ' checked="checked"'; ?> id="comiket_div<?php echo $key; ?>" name="comiket_div" type="radio" value="<?php echo $key; ?>" />
                                            <?php echo $val; ?>
                                        </label>
                                        <br />
                                    <?php endforeach; ?>
                                    <div class="attention_customer_cd" style="">
                                        <strong class="disp_comiket red">※佐川急便のお取引先コード（お客様コード）をお持ちの方はこちら</strong>
                                    </div>
                                </dd>
                            </dl>
                            <dl class="comiket-personal-name-seimei" style="display: none;">
                                <dt id="comiket_personal_name-seimei">
                                    お申込者<br/>
                                    カタカナ・英字のみ<span>必須</span>
                                </dt>
                                <dd<?php
                                    if (isset($e)
                                       && ($e->hasErrorForId('comiket_personal_name-seimei'))
                                    ) {
                                        echo ' class="form_error"';
                                    }
                                ?>>
                                    <span class="comiket_personal_name_sei-lbl"><?php echo $eve001Out->comiket_personal_name_sei();?></span>&nbsp;
                                    <span class="comiket_personal_name_mei-lbl"><?php echo $eve001Out->comiket_personal_name_mei();?></span>
                                    セイ<input class="katakana" style="width:30%;" maxlength="8" autocapitalize="off" inputmode="comiket_personal_name_sei" name="comiket_personal_name_sei" data-pattern="" placeholder="例）サガワ" type="text" value="<?php echo $eve001Out->comiket_personal_name_sei() ?>" mode = "katakana" />
                                    メイ<input class="katakana" style="width:30%;" maxlength="8" autocapitalize="off" inputmode="comiket_personal_name_mei" name="comiket_personal_name_mei" data-pattern="" placeholder="例）ハナコ" type="text" value="<?php echo $eve001Out->comiket_personal_name_mei() ?>" />
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_tel">
                                    電話番号<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_tel')) { echo ' class="form_error"'; } ?>>
                                    <span class="comiket_tel-lbl"><?php echo $eve001Out->comiket_tel();?></span>
                                    <input name="comiket_tel" class="number-p-only" type="text" maxlength="15" placeholder="例）080-1111-2222" data-pattern="^[0-9-]+$" value="<?php echo $eve001Out->comiket_tel();?>" />
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_mail">
                                    メールアドレス<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_mail')) { echo ' class="form_error"'; } ?>>
                                    <input class="w_220" maxlength="100" autocapitalize="off" inputmode="email" name="comiket_mail" data-pattern="^[!-~]+$" placeholder="例）ringo@sagawa.com" type="<?php echo $inputTypeEmail; ?>" value="<?php echo $eve001Out->comiket_mail();?>" />
                                    <br class="sp_only" /><br>
                                    <strong class="red">※申込完了の際に申込完了メールを送付させていただきますので、間違いのないように注意してご入力ください。</strong>
                                    <p class="red">
                                        ※迷惑メールを設定されている方は「sagawa-mov.co.jp」を受信する設定にしてください。
                                        <br />詳しくは
                                        <a href="#bounce_mail">こちら</a>
                                    </p>
                                    <p class="red">
                                        ※@以降のドメインの確認お願いします。<br />
                                        例：@～.ne.jp、@～.co.jp、Gmailなら@gmail.com等
                                    </p>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_mail_retype">
                                    メールアドレス確認<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_mail_retype')) { echo ' class="form_error"'; } ?>>
                                    <input class="w_220" maxlength="100" autocapitalize="off" inputmode="email" name="comiket_mail_retype" data-pattern="^[!-~]+$" placeholder="例）ringo@sagawa.com" type="<?php echo $inputTypeEmail; ?>" value="<?php echo $eve001Out->comiket_mail_retype();?>" oncopy="return false" onpaste="return false" oncontextmenu="return false"/>
                                    <br/>
                                    <br/>
                                    <strong class="red">
                                        ※確認のため、再入力をお願いいたします。
                                    </strong>
                                </dd>
                            </dl>
                            <dl class="class_comiket_detail_collect_date">
                                <dt id="comiket_detail_collect_date">
                                    利用日<span>必須</span>
                                </dt>
                                <dd <?php if (isset($e) && $e->hasErrorForId('comiket_detail_collect_date')) { echo ' class="form_error"'; } ?>>
                                    <!-- <p class="comiket-detail-outbound-collect-date-fr-to">2021年08月21日（月）&nbsp;から&nbsp;2021年08月22日（金）&nbsp;まで選択できます。</p> -->
                                    <p class="comiket-detail-outbound-collect-date-fr-to_test">2021年08月21日（土）&nbsp;・&nbsp;2021年08月22日（日）&nbsp;・&nbsp;2021年08月28日（土）選択できます。</p>
                                    <input type="hidden" id="hid_comiket-detail-collect-date-from"  name="hid_comiket-detail-collect-date-from" value="<?php echo @$dispItemInfo["eventsub_selected_data"]["collect_fr_dt"]; ?>" />
                                    <input type="hidden" id="hid_comiket-detail-collect-date-to"    name="hid_comiket-detail-collect-date-to"   value="<?php echo @$dispItemInfo["eventsub_selected_data"]["collect_to_dt"]; ?>" />
                                    <div class="comiket_detail_collect_date_parts">
                                        <select name="comiket_detail_collect_date_year_sel">
                                        <?php
                                           echo Sgmov_View_Azk_Input::_createPulldown($eve001Out->comiket_detail_collect_date_year_cds(), $eve001Out->comiket_detail_collect_date_year_lbls(), $eve001Out->comiket_detail_collect_date_year_sel());
                                        ?>
                                        </select>年
                                       <select name="comiket_detail_collect_date_month_sel">
                                        <?php
                                            echo Sgmov_View_Azk_Input::_createPulldown($eve001Out->comiket_detail_collect_date_month_cds(), $eve001Out->comiket_detail_collect_date_month_lbls(), $eve001Out->comiket_detail_collect_date_month_sel());
                                        ?>
                                        </select>月
                                        <select name="comiket_detail_collect_date_day_sel">
                                            <option value = "">日を選択</option>
                                            <?php
                                                echo Sgmov_View_Azk_Input::_createPulldown($eve001Out->comiket_detail_collect_date_day_cds(), $eve001Out->comiket_detail_collect_date_day_lbls(), $eve001Out->comiket_detail_collect_date_day_sel());
                                            ?>
                                        </select>日 
                                    </div>
                                </dd>
                            </dl>
                            <dl class="service-buppan-item" id = "comiket_box_num" service-id="1">
                                <?php foreach($dispItemInfo["comiket_box_lbls"] as $key => $value): ?>
                                    <dt id="comiket_detail_collect_date">
                                        <?php 
                                            $displayName = $value["name"];
                                            if (@!empty($value["name_display"]) && $value["name_display"] != null) :
                                                $displayName = $value["name_display"];
                                            endif;
                                            echo $displayName;
                                        ?>
                                        <br>
                                        <p class = "lhn">
                                            <?php echo number_format($value["cost_tax"]); ?>円（税込）<span>必須</span>
                                        </p>
                                    </dt>
                                    <dd <?php 
                                                if (isset($e) && 
                                                    ($e->hasErrorForId('comiket_box_num_ary'.$value['id']) || $e->hasErrorForId('comiket_box_buppan_num_ary_max_err') || $e->hasErrorForId('comiket_box_num'))
                                                ) {
                                                echo ' class="form_error"';
                                                }
                                            ?>>
                                        <div class="comiket-box-buppan-num comiket-box-buppan-num-dmy">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td class="buppan_tbl_td vam" id ="comiket_box_buppan_num_ary">
                                                            <table id ="comiket_box_buppan_num_ary_max_err">
                                                                <tbody>
                                                                    <?php 
                                                                            $suuryo = $eve001Out->comiket_box_num_ary($value["id"]);
                                                                            if(empty($suuryo)){
                                                                                $suuryo = 0;
                                                                            }
                                                                        ?>
                                                                        <tr id = "comiket_box_buppan_num_ary_<?php echo $value['id']; ?>">
                                                                            <td class="comiket_box_item_value ws_nowrap">
                                                                                <input autocapitalize="off" 
                                                                                       class="number-only comiket_box_item_value_input suuryo_<?php echo $value['id']?>"
                                                                                       maxlength="2" inputmode="numeric" 
                                                                                       name="comiket_box_num_ary[<?php echo $value['id']?>]" 
                                                                                       data-pattern="^d+$" 
                                                                                       placeholder="例）1" 
                                                                                       type="text" 
                                                                                       value="<?php echo $suuryo;?>" 
                                                                                       style="min-width: 50px;">
                                                                                <div class ="vam" style="display: inline-block;display: inline-grid;">
                                                                                    <button type="button" class ="incdec-btn plus" data-id="<?php echo $value['id']; ?>">
                                                                                        <i class="fas fa-caret-up"></i>
                                                                                    </button>
                                                                                    <button type="button" class ="incdec-btn minus mt3px" data-id="<?php echo $value['id']; ?>">
                                                                                        <i class="fas fa-caret-down"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach;?>
                                                                     <tr>
                                                                        <td>
                                                                            <br />
                                                                            <strong class="red">
                                                                            ※お申込は、おひとり様1申込でお願いいたします。
                                                                            </strong>    
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_azukari_kaisu_type_sel">
                                    取り出し回数<span>必須</span>
                                </dt>
                                <dd<?php
                                    if (isset($e)
                                       && ($e->hasErrorForId('comiket_detail_azukari_kaisu_type_sel'))
                                    ) {
                                        echo ' class="form_error"';
                                    }
                                ?>>
                                    <div class="comiket_detail_azukari_kaisu_type-dd">
                                        <?php foreach($dispItemInfo['comiket_detail_azukari_kaisu_type_lbls'] as $key => $val) : ?>
                                            <label class="radio-label" for="comiket_detail_azukari_kaisu_type_sel<?php echo $key; ?>" 
                                                <?php if($key == "2"): echo ' style="color:grey;"'; endif; ?> >

                                                <input 
                                                    <?php if($key == "2"): echo ' disabled="disabled"'; endif; ?>
                                                    id="comiket_detail_azukari_kaisu_type_sel<?php echo $key; ?>" 
                                                    class="comiket_detail_azukari_kaisu_type_sel" 
                                                    name="comiket_detail_azukari_kaisu_type_sel" 
                                                    type="radio" 
                                                    value="<?php echo $key; ?>" 
                                                    <?php if($key == "1"): echo 'checked'; endif; ?> />
                                                <?php echo $val; ?>

                                            </label>
                                            <br />
                                        <?php endforeach; ?>
                                    </div>
                                </dd>
                            </dl>
                            <dl class="comiket_detail_service" style="display: none;">
                                <dt id="comiket_detail_service">
                                    サービス選択<span>必須</span>
                                </dt>
                                <dd>
                                    <label class="radio-label" for="comiket_detail_service" style="display: none;">
                                        <input id="comiket_detail_service" name="comiket_detail_service_sel" type="radio" value="5" checked="checked">
                                        手荷物                                        
                                    </label>
                                </dd>
                            </dl>
                        </div>
                        <!-- ***************************************************************************************************************************************** -->
                        <!-- 荷物預入取出 End -->
                        <!-- ***************************************************************************************************************************************** -->
                            