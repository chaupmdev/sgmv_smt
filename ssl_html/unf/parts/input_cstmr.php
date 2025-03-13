                        <div class="dl_block comiket_block" style="margin-bottom:0px;border-bottom: 0px">
                            <dl>
                                <dt id="event_sel" style=" border-top: solid 1px #ccc !important;">
                                    ツアー名
                                </dt>
                                <dd<?php
                                    if (isset($e) && ($e->hasErrorForId('event_sel'))) {
                                        echo ' class="form_error"';
                                    }
                                ?>>
                                    <?php $inputMode = $eve001Out->input_mode(); ?>
                                    <span class="<?php if(empty($inputMode)) :?>comiket_diplay_none<?php endif; ?>" >
<?php
            echo Sgmov_View_Unf_Input::_getLabelSelectPulldownData($eve001Out->event_cds(), $eve001Out->event_lbls(), $eve001Out->event_cd_sel());
?>
                                    &nbsp;&nbsp;
                                    </span>
                                    <select name="event_sel" class="<?php if(!empty($inputMode)) :?>comiket_diplay_none<?php endif; ?>">
                                        <option value="" timeoverflg="0">選択してください</option>
<?php
            echo Sgmov_View_Unf_Input::_createPulldown($eve001Out->event_cds(), $eve001Out->event_lbls(), $eve001Out->event_cd_sel(), $eve001Out->eve_timeover_flg(), $eve001Out->eve_timeover_date());
?>
                                        </select>
                                        <input type="hidden" id="hid_timezone_flg" name="hid_timezone_flg" value="" />
                                        <select name="eventsub_sel" class="eventsub_sel">
                                            <option value="">選択してください</option>
<?php
            echo Sgmov_View_Unf_Input::_createPulldown($eve001Out->eventsub_cds(), $eve001Out->eventsub_lbls(), $eve001Out->eventsub_cd_sel());
?>
                                        </select>

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
                            <dl style="display:none;">
                                <dt id="eventsub_term">
                                    申込期間
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
                            <dl class="comiket_customer_cd"  style="display:none;">
                                <dt id="comiket_customer_cd">
                                    お取引先コード<br/>（お客様コード）<span>必須</span><br/>
                                    <strong class="red">※ 桁数11桁</strong>
                                </dt>
                                <dd<?php
                                    if (isset($e)
                                       && ($e->hasErrorForId('comiket_customer_cd'))
                                    ) {
                                        echo ' class="form_error"';
                                    }
                                ?>>
                                    <input class="number-only" style="width: 120px;" maxlength="11" autocapitalize="off" inputmode="comiket_customer_cd" name="comiket_customer_cd" data-pattern="^\d+$" placeholder="" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $eve001Out->comiket_customer_cd() ?>" />
                                    <input class="m110" style="width:60px;" name="customer_search_btn" type="button" value="検索" />
                                    <br/><strong class="comiket_customer_cd_message red"></strong>
                                    <br/>
                                    <div style="width:80%;">
                                    <strong class="red">
                                        ※佐川急便をご利用のお客様はお取引先コード（お客様コード）を入力して頂く事で売掛運賃となります。<br/><br/>
                                        ※お取引先コード（お客様コード）はお持ちの送り状に記載されております。<br/><br/>
                                        ※自社のお取引先コード（お客様コード）が不明な場合は最寄りの佐川急便の営業所にお問い合わせください。<br/><br/>
                                        <a href="http://www2.sagawa-exp.co.jp/send/branch_search/moyori/area/" target="_blank">営業所・サービスセンター・取次店検索はこちら</a>
                                    </strong>
                                    </div>
                                </dd>
                            </dl>
                            <dl class="office-name" style="display: none;">
                                <dt id="office_name">
                                    お申込者<span>必須</span>
                                </dt>
                                <dd<?php
                                    if (isset($e)
                                       && ($e->hasErrorForId('office_name'))
                                    ) {
                                        echo ' class="form_error"';
                                    }
                                ?>>
                                <span class="office_name-lbl"><?php echo $eve001Out->office_name();?></span>&nbsp;
                                <input class="" style="width:60%;" maxlength="16" autocapitalize="off" inputmode="office_name" name="office_name" data-pattern="" placeholder="" type="text" value="<?php echo $eve001Out->office_name() ?>" />
                                </dd>
                            </dl>
                            <dl class="comiket-personal-name-seimei" style="display: none;">
                                <dt id="comiket_personal_name-seimei">
                                    お申込者<span>必須</span>
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
                                姓<input class="" style="width:30%;" maxlength="8" autocapitalize="off" inputmode="comiket_personal_name_sei" name="comiket_personal_name_sei" data-pattern="" placeholder="例）佐川" type="text" value="<?php echo $eve001Out->comiket_personal_name_sei() ?>" />
                                名<input class="" style="width:30%;" maxlength="8" autocapitalize="off" inputmode="comiket_personal_name_mei" name="comiket_personal_name_mei" data-pattern="" placeholder="例）花子" type="text" value="<?php echo $eve001Out->comiket_personal_name_mei() ?>" />
                                <div class="disp_comiket disp_gooutcamp">
                                    <br/>
                                    <strong class="red">※ 法人の場合は、姓のみ入力してください。</strong>
                                </div>

                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_zip">
                                    郵便番号<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_zip')) { echo ' class="form_error"'; } ?>>
                                    <span class="zip_mark1">〒</span><span class="comiket_zip1-lbl"><?php echo $eve001Out->comiket_zip1();?></span>
                                    <input autocapitalize="off" class="w_70 number-only" maxlength="3" inputmode="numeric" name="comiket_zip1" data-pattern="^\d+$" placeholder="例）136" type="text" value="<?php echo $eve001Out->comiket_zip1();?>" />
                                    <span class="zip_mark2">-</span>
                                    <span class="comiket_zip1-str">
                                    </span>
                                    <span class="comiket_zip2-lbl"><?php echo $eve001Out->comiket_zip2();?></span>
                                    <input autocapitalize="off" class="w_70 number-only" maxlength="4" inputmode="numeric" name="comiket_zip2" data-pattern="^\d+$" placeholder="例）0082" type="text" value="<?php echo $eve001Out->comiket_zip2();?>" />
                                    <input class="m110" name="adrs_search_btn" type="button" value="住所検索" />
                                    <span style="font-size:12px;display: inline-block !important;" class="forget-address-discription">
                                        &#12288;※郵便番号が不明な方は<a style="text-decoration: underline" target="_blank" href="http://www.post.japanpost.jp/zipcode/">こちら...</a>
                                    </span>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_pref">
                                    都道府県<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_pref')) { echo ' class="form_error"'; } ?>>
                                    <span class="comiket_pref_nm-lbl"><?php echo $eve001Out->comiket_pref_nm();?></span>
                                    <select name="comiket_pref_cd_sel">
                                        <option value="">選択してください</option>
<?php
        echo Sgmov_View_Unf_Input::_createPulldown($eve001Out->comiket_pref_cds(), $eve001Out->comiket_pref_lbls(), $eve001Out->comiket_pref_cd_sel());
?>
                                    </select>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_address">
                                    市区町村<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_address')) { echo ' class="form_error"'; } ?>>
                                    <span class="comiket_address-lbl"><?php echo $eve001Out->comiket_address();?></span>
                                    <input name="comiket_address" style="width:80%;" maxlength="14" placeholder="例）江東区新砂" type="text" value="<?php echo $eve001Out->comiket_address();?>" />
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_building">
                                    番地・建物名・部屋番号<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_building')) { echo ' class="form_error"'; } ?>>
                                    <span class="comiket_building-lbl"><?php echo $eve001Out->comiket_building();?></span>
                                    <input name="comiket_building" style="width:80%;" maxlength="30" type="text" value="<?php echo $eve001Out->comiket_building();?>" />
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
                                <dt id="comiket_mail_retype" style=" border-bottom: solid 1px #ccc !important;">
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
                            <dl>
                                <dt id="terminal">
                                    集荷の往復<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('top_comiket_detail_type_sel2')) { echo ' class="form_error"'; } ?>>
                                    <label class="radio-label" for="detail_type_sel2">
                                        <input<?php if ($eve001Out->raw_comiket_detail_type_sel2 === '2') echo ' checked="checked"'; ?> id="detail_type_sel2" name="comiket_detail_type_sel2" type="radio" value="2" />
                                        宿泊施設 → 自宅
                                    </label>
                                </dd>
                            </dl>
                            
                            <dl class="class_comiket_booth_name" style="display:none;">
                                <dt id="comiket_booth_name">
                                    <?php if($eve001Out->event_cd_sel() == '10') : ?>
                                        部屋番号
                                    <?php else: ?>
                                        ブース名
                                    <?php endif; ?>
                                    <span class ="hissu">必須</span>
                                </dt>
                                
                                
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_booth_name')) { echo ' class="form_error"'; } ?>>
                                    <input class="" style="width:80%;" maxlength="16" autocapitalize="off" name="comiket_booth_name" data-pattern="" placeholder="" type="text" value="<?php echo $eve001Out->comiket_booth_name() ?>" />
                                </dd>
                            </dl>

                            <dl style="display:none;">
                                <dt id="comiket_staff_seimei">
                                    当日の担当者名<span class ="hissu">必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_staff_seimei')) { echo ' class="form_error"'; } ?>>
                                    <span class="wb">姓<input class="w_40Per" maxlength="8" autocapitalize="off" inputmode="comiket_staff_sei" name="comiket_staff_sei" data-pattern="" placeholder="" type="text" value="<?php echo $eve001Out->comiket_staff_sei() ?>" /></span>
                                    <span class="wb">
                                        名<input class="w_40Per" maxlength="8" autocapitalize="off" inputmode="comiket_staff_mei" name="comiket_staff_mei" data-pattern="" placeholder="" type="text" value="<?php echo $eve001Out->comiket_staff_mei() ?>" />
                                        <input class="m110 btn_cstm_info_copy" name="btn_cstm_info_copy" type="button" style="width:93px;" value="お申込者と同じ" />
                                    </span>
                                    <br/><br/>
                                    <strong class="red">※会場で引取りされる方、もしくは出荷される方</strong>
                                </dd>
                            </dl>
                            <dl style="display:none;">
                                <dt id="comiket_staff_seimei_furi">
                                    当日の担当者名（フリガナ）<span class ="hissu">必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_staff_seimei_furi')) { echo ' class="form_error"'; } ?>>
                                    <span class="wb">セイ<input class="w_40Per" maxlength="8" autocapitalize="off" inputmode="comiket_staff_sei_furi" name="comiket_staff_sei_furi" data-pattern="" placeholder="" type="text" value="<?php echo $eve001Out->comiket_staff_sei_furi() ?>" /></span>
                                    <span class="wb">メイ<input class="w_40Per" maxlength="8" autocapitalize="off" inputmode="comiket_staff_mei_furi" name="comiket_staff_mei_furi" data-pattern="" placeholder="" type="text" value="<?php echo $eve001Out->comiket_staff_mei_furi() ?>" /></span>
                                </dd>
                            </dl>
                            <dl style="display:none;">
                                <dt id="comiket_staff_tel">
                                    当日の担当者電話番号<span class ="hissu">必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_staff_tel')) { echo ' class="form_error"'; } ?>>
                                    <input name="comiket_staff_tel" class="number-p-only" type="text" maxlength="15" data-pattern="^[0-9-]+$" placeholder="例）080-1111-2222" value="<?php echo $eve001Out->comiket_staff_tel();?>" />
                                    <br/>
                                    <br/>
                                    <strong class="red">※ イベント当日に、現地で連絡がとれる番号を入力してください。</strong>
                                </dd>
                            </dl>
                            <dl style="display:none">
                                <dt id="comiket_detail_type_sel">
                                    往復<span>必須</span>
                                </dt>
                                <dd<?php
                                    if (isset($e)
                                       && ($e->hasErrorForId('comiket_detail_type_sel'))
                                    ) {
                                        echo ' class="form_error"';
                                    }
                                ?>>
                                    <div class="comiket_detail_type_sel-dd">
                                        <?php foreach($dispItemInfo['comiket_detail_type_lbls'] as $key => $val) : ?>

                                            <label class="radio-label comiket_detail_type_sel-label<?php echo $key; ?>" for="comiket_detail_type_sel<?php echo $key; ?>">
                                                <input checked="checked" id="comiket_detail_type_sel<?php echo $key; ?>" class="comiket_detail_type_sel" name="comiket_detail_type_sel" type="radio" value="<?php echo $key; ?>" />
                                                <?php echo $val; ?>
                                            </label>
                                            <br />
                                        <?php endforeach; ?>
                                    </div>
                                </dd>
                            </dl>
                        </div>