
<p class="sentence" style="margin-bottom: 5px;">
    手荷物当日配送サービス概要は<a href="https://www.sagawa-exp.co.jp/ttk/service/hotel-sokuhai.html" target="_blank" style="color: #1774bc; text-decoration: underline;">こちら</a>
</p>

<br/>
<div class="dl_header header-receipt-delivery">●お預かり/お届け日</div>
<div class="dl_block comiket_block">
    <dl>
    <dt id="delivery_date_store">
        お預かり/お届け日
    </dt>
    <dd <?php
            if (isset($e)
                && ($e->hasErrorForId('comiket_div'))
            ) {
                echo ' class="form_error"';
            }
        ?>data-stt-ignore>
        <?php echo $eve001Out->delivery_date_store;?>
    </dd>
</dl>
</div>

<div class="dl_header header-sender-title">●お預け先(From)</div>
<div class="dl_block comiket_block">
    <dl>
        <dt id="tag_id">
            申込番号
        </dt>
        <dd>
            <?php echo $eve001Out->comiket_id();  ?> 
        </dd>
    </dl>

    <dl>
        <dt id="hotel_nm">
            お預け先名称
        </dt>
        <dd class="sender-hotel-nm">
            <?php echo $hachakutenInfo['name_jp'] ?>
        </dd>
    </dl>

    <dl>
        <dt id="hotel_address">
            住所
        </dt>
        <dd class="sender-address-val">
            <?php echo $hachakutenInfo['address'] ?>
        </dd>
    </dl>

    <dl>
        <dt id="hotel_tel">
            電話番号
        </dt>
        <dd class="sender-tel-val">
            <?php echo $hachakutenInfo['tel'] ?>
        </dd>
    </dl>
    <input name="comiket_office_name"  type="hidden" value="<?php echo $hachakutenInfo['name_jp'] ?>" />
    <input name="comiket_address"  type="hidden" value="<?php echo $hachakutenInfo['address'] ?>" />
    <input name="comiket_tel"  type="hidden" value="<?php echo $hachakutenInfo['tel'] ?>" />
    <input name="comiket_id" type="hidden" value="<?php echo $eve001Out->comiket_id(); ?>" /> 
    <input name="delivery_date_store" type="hidden" value="<?php echo $eve001Out->delivery_date_store; ?>" /> 
    <select name="event_sel" class="comiket_diplay_none">
        <option value="" timeoverflg="0">選択してください</option>
<?php
echo Sgmov_View_Eve_Input::_createPulldown($eve001Out->event_cds(), $eve001Out->event_lbls(), $eve001Out->event_cd_sel(), $eve001Out->eve_timeover_flg(), $eve001Out->eve_timeover_date());
?>
        <input type="hidden" id="hid_timezone_flg" name="hid_timezone_flg" value="" />
        <select name="eventsub_sel" class="eventsub_sel" class="comiket_diplay_none">
            <option value="">選択してください</option>
<?php
echo Sgmov_View_Eve_Input::_createPulldown($eve001Out->eventsub_cds(), $eve001Out->eventsub_lbls(), $eve001Out->eventsub_cd_sel());
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

        <dl style="display: none;">
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
            <label class="radio-label comiket_div1" for="comiket_div1" style="display:none;">
                <input checked="checked" id="comiket_div1" name="comiket_div" type="radio" value="1" />
            </label>
            <br />
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

    </div>
</div>
<div class="dl_header header-addressee-title">●お届け先(To)</div>
<div class="dl_block comiket_block">
    <dl>
        <dt id="addressee_type_sel">
            お届け先の選択<span>必須</span>
        </dt>
        <dd <?php
            if (isset($e)
               && ($e->hasErrorForId('addressee_type_sel'))
            ) {
                echo ' class="form_error"';
            }
        ?>>
            <select name="addressee_type_sel">
                <option value="">選択してください</option>
                <?php
                    echo Sgmov_View_Eve_Input::_createPulldown($eve001Out->addressee_type_cds(), $eve001Out->addressee_type_lbls(), $eve001Out->addressee_type_sel());
                ?>
            </select>
            <br/>
            <div class="delivery_search" style="display:none;">
                <input class="with-item-delivery" name="hotel_nm" maxlength="100" autocapitalize="off"  type="text" value="" placeholder="ホテル名をご入力ください" />
                <input class="search-btn" name="btn_hotel_search" type="button" value="検索" style="width: 60px;"/>
                <br/>
                <br/>
                <div id="hotel_msg" style="display:none;">下記プルダウンよりお選びください。</div>
                <br/>
                <select name="hotel_sel" class="with-item-delivery">
                    
                </select>
            </div>
            <br/>
            <select name="airport_sel" class="with-item-delivery"   style="display:none;">
               
            </select>
            
            <select name="sevice_center_sel" class="with-item-delivery" style="display:none;">
                
            </select>
        </dd>
    </dl>

    <dl>
        <dt id="addressee_address" class="addressee-address-title">
            住所
        </dt>
        <dd class="addressee-address-nm">
            
        </dd>
    </dl>

    <dl>
        <dt id="addressee_tel" class="addressee-tel-title">
            電話番号
        </dt>
        <dd class="addressee-tel">
            
        </dd>
    </dl>

    <dl style="display: none">
        <dt id="comiket_detail_delivery_date" class="addressee-date-delivery">
            搭乗日時<span>必須</span>
        </dt>
        <dd <?php if (isset($e) && $e->hasErrorForId('comiket_detail_delivery_date')) { echo ' class="form_error"'; } ?>>
            <?php
                $strSelected = '';
                $strSelectedToday = '';
                $strSelectedTomorrow = '';
                $date = new DateTime($eve001Out->delivery_date_store);
        
                $dateAdd = new DateTime($eve001Out->delivery_date_store);
                $dateAdd->modify('+1 day');
                if ($eve001Out->comiket_detail_delivery_date() == '') {
                    $strSelected = ' selected="selected"';
                } else if ($eve001Out->comiket_detail_delivery_date() == $date->format('Y-m-d')) {
                    $strSelectedToday = ' selected="selected"';
                } else if ($eve001Out->comiket_detail_delivery_date() == $dateAdd->format('Y-m-d')) {
                    $strSelectedTomorrow = ' selected="selected"';
                }
            ?>
            <table>
                <tr>
                  <th style="width: 63%;"></th>
                  <th style="width: 37%;"></th>
                </tr>
                <tr>
                    <td>
                        <select name="comiket_detail_delivery_date">
                            <option value="" <?php echo $strSelected; ?>>選択してください</option>
                            <option value="<?php echo $date->format('Y-m-d'); ?>" <?php echo $strSelectedToday; ?> data-stt-ignore><?php echo $date->format('Y/m/d') ?></option>
                            <option value="<?php echo $dateAdd->format('Y-m-d'); ?>" <?php echo $strSelectedTomorrow; ?> data-stt-ignore><?php echo $dateAdd->format('Y/m/d'); ?></option>
                        </select>
                        <br/><br/>
                        <select name="comiket_detail_delivery_date_hour" style="margin-bottom: 10px;">
                            <option value="">選択してください</option>
                            <?php 
                                for($i = 0; $i < 24; $i++) {
                                    $strSelectedHour = '';
                                    if ($i == $eve001Out->comiket_detail_delivery_date_hour() && $eve001Out->comiket_detail_delivery_date_hour() !== '') {
                                        $strSelectedHour = ' selected="selected"';
                                    }
                                    if ($i < 10) {
                                        ?>
                                        <option value="<?php echo $i; ?>" <?php echo $strSelectedHour; ?> data-stt-ignore>0<?php echo $i; ?></option>
                                        <?php 
                                    } else {
                                        ?>
                                        <option value="<?php echo $i; ?>" <?php echo $strSelectedHour; ?> data-stt-ignore><?php echo $i; ?></option>
                                        <?php 
                                    }    
                                }
                            ?>
                        </select>
                        <span class="addressee-date-delivery-hour">時</span>
                        
                        &nbsp;&nbsp;
                        <select name="comiket_detail_delivery_date_min" style="margin-bottom: 10px;">
                            <option value="">選択してください</option>
                            <?php 
                                for($i = 0; $i < 60; $i++) {
                                    $strSelectedMin = '';
                                    if ($i == $eve001Out->comiket_detail_delivery_date_min() && $eve001Out->comiket_detail_delivery_date_min() !== '') {
                                        $strSelectedMin = ' selected="selected"';
                                    }
                                    if ($i < 10) {
                                        ?>
                                        <option value="<?php echo $i; ?>" <?php echo $strSelectedMin; ?> data-stt-ignore>0<?php echo $i; ?></option>
                                        <?php 
                                    } else {
                                        ?>
                                        <option  value="<?php echo $i; ?>" <?php echo $strSelectedMin; ?> data-stt-ignore><?php echo $i; ?></option>
                                        <?php 
                                    }
                                }
                            ?>
                        </select>
                        <span class="addressee-date-delivery-min">分</span>
                    </td>
                    <td style="vertical-align: top;">
                        <p style="padding-top: 0px;">
                            カウンター営業時間7:00～20:00<br/>
                            当日20時以降のフライト予定の場合、20時までの受取をお願いします。
                        </p>
                    </td>
                </tr>
            </table>
            
            
            
        </dd>
    </dl>

    <dl style="display: none">
        <dt id="comiket_detail_inbound_note">
            便名<span>必須</span>
        </dt>
        <dd <?php if (isset($e) && $e->hasErrorForId('comiket_detail_inbound_note')) { echo ' class="form_error"'; } ?>>
            <input style="width:50%;" autocapitalize="off" maxlength="16" inputmode="text" name="comiket_detail_inbound_note"  type="text" value="<?php echo $eve001Out->comiket_detail_inbound_note();?>" />
        </dd>
    </dl>
    
</div>
<div class="dl_header header-applicant">●ご利用者情報(Applicant information)</div>
<div class="dl_block comiket_block">
    <dl>
        <dt id="comiket_staff-seimei" class="comiket-title">
            名前<span>必須</span>
        </dt>
        <dd <?php
            if (isset($e)
               && ($e->hasErrorForId('comiket_staff-seimei'))
            ) {
                echo ' class="form_error"';
            }
        ?>>
            
            <span class="comiket-sei">姓</span>&nbsp;
            <input class="comiket-sei-mei" maxlength="8" autocapitalize="off" style="width: 30%;" name="comiket_staff_sei"  type="text"  placeholder="例）佐川" value="<?php echo $eve001Out->comiket_staff_sei() ?>"/>
            &nbsp;&nbsp;
            <span class="comiket-mei">名</span>&nbsp;
            <input class="comiket-sei-mei" maxlength="8" autocapitalize="off" style="width: 30%;" name="comiket_staff_mei"  type="text"  placeholder="例）花子" value="<?php echo $eve001Out->comiket_staff_mei() ?>"/>
        </dd>
    </dl>

    <dl>
        <dt id="comiket_staff_tel">
            電話番号<span>必須</span>
        </dt>
        <dd<?php
            if (isset($e)
               && ($e->hasErrorForId('comiket_staff_tel'))
            ) {
                echo ' class="form_error"';
            }
        ?>>
            <input name="comiket_staff_tel" maxlength="15" data-pattern="^[0-9-]+$" type="text" value="<?php echo $eve001Out->comiket_staff_tel() ?>" placeholder="例）080-1111-2222"/>
            <br/>
            <br/>
            <strong class="red note-retype">
                ※当日ご連絡できる電話番号をお持ちで無い方は「0000」を入力して下さい。
            </strong>
        </dd>
    </dl>

    <dl>
        <dt id="comiket_mail">
            メールアドレス<span>必須</span>
        </dt>
        <dd<?php
            if (isset($e)
               && ($e->hasErrorForId('comiket_mail'))
            ) {
                echo ' class="form_error"';
            }
        ?>>
            <input name="comiket_mail" maxlength="100" autocapitalize="off" data-pattern="^[!-~]+$"  type="text" class="with-item-delivery" placeholder="例）ringo@sagawa.com" value="<?php echo $eve001Out->comiket_mail() ?>"/>
            <br class="sp_only" /><br>
            <strong class="red mail-note-1">※申込完了の際に申込完了メールを送付させていただきますので、間違いのないように注意してご入力ください。</strong>
            <p class="red mail-note-2">
                ※迷惑メールを設定されている方は「sagawa-mov.co.jp」を受信する設定にしてください。
                <br />詳しくは
                <a href="#bounce_mail">こちら</a>
            </p>
            <p class="red mail-note-3">
                ※@以降のドメインの確認お願いします。<br />
                例：@～.ne.jp、@～.co.jp、Gmailなら@gmail.com等
            </p>

        </dd>
    </dl>

    <dl>
        <dt id="comiket_mail_retype">
            メールアドレス確認<span>必須</span>
        </dt>
        <dd <?php
            if (isset($e)
               && ($e->hasErrorForId('comiket_mail_retype'))
            ) {
                echo ' class="form_error"';
            }
        ?>>
            <input name="comiket_mail_retype" maxlength="100" autocapitalize="off" data-pattern="^[!-~]+$" oncopy="return false"  type="text" class="with-item-delivery" value="<?php echo $eve001Out->comiket_mail_retype() ?>" />
            <br/>
            <br/>
            <strong class="red note-retype">
                ※確認のため、再入力をお願いいたします。
            </strong>
        </dd>
    </dl>

    <dl>
        <dt id="comiket_detail_inbound_note1">
            備考
        </dt>
        <dd <?php if (isset($e) && $e->hasErrorForId('comiket_detail_inbound_note1')) { echo ' class="form_error"'; } ?>>
            <input autocapitalize="off" style="width:50%;" maxlength="16" inputmode="text" name="comiket_detail_inbound_note1" placeholder="" type="text" value="<?php echo $eve001Out->comiket_detail_inbound_note1();?>" /><br/>
        </dd>
    </dl>

</div>

<div class="dl_header header-size">●荷物情報</div>
<div class="dl_block comiket_block">
    <dl>
        <dt id="comiket_box_num">
            サイズ<span>必須</span>
        </dt>
        <dd <?php
            if (isset($e)
               && ($e->hasErrorForId('comiket_box_num'))
            ) {
                echo ' class="form_error"';
            }
        ?>>
            <?php if (!empty($dispItemInfo['box_lbls'])): ?>
                <?php foreach($dispItemInfo['box_lbls'] as $row): ?>
                    <label class="radio-label" for="size_<?php echo $row['size_display']; ?>">
                        <?php if (empty($eve001Out->raw_comiket_box_inbound_num_ary[$row['cd']])): ?>
                            <input id="size_<?php echo $row['size_display']; ?>" name="comiket_box_inbound_num_ary" type="radio" value="<?php echo $row['cd']; ?>">
                        <?php else: ?>
                            <input id="size_<?php echo $row['size_display']; ?>" name="comiket_box_inbound_num_ary" checked="checked" type="radio" value="<?php echo $row['cd']; ?>">
                        <?php endif; ?>
                        
                        <span><?php echo $row['name']; ?></span> 
                    </label>
                    <br/>
                <?php endforeach; ?>
            <?php endif; ?>
            <br>
            <div class="size-note" id="size_note">【サイズの目安】</div>
            <div class="size-note" id="size_note_100">・Mサイズ：スーツケース・カバン・ゴルフバッグ</div>
            <div class="size-note" id="size_note_140">・Lサイズ：スノーボード・折りたたみ自転車など</div>
            <div>
                <?php if ($isSmartPhone) : ?>
                    <table class="size-table-note" style="width:99%;">
                <?php else: ?>
                    <table class="size-table-note" style="width:60%;">
                <?php endif; ?>
                    <tr style="color: white;background: #4472C4;font-weight: bold">
                        <th style="width: 30%">料金（税込）</th>
                        <th style="width: 40%">ホテル行き/<br>佐川急便手ぶら<br>観光カウンター行き</th>
                        <th style="width: 30%">空港行き</th>
                    </tr>
                    <tr>
                        <td>Mサイズ</td>
                        <td>1,980円</td>
                        <td>2,460円</td>
                    </tr>
                    <tr>
                        <td>Lサイズ</td>
                        <td>3,480円</td>
                        <td>4,140円</td>
                    </tr>
                </table>
            </div>
        </dd>
    </dl>
</div>

<div class="dl_header header-payment">●お支払い方法</div>
<div class="dl_block comiket_block">
    <dl>
        <dt id="payment">
            お支払い方法
        </dt>
        <dd>
            <label class="radio-label" for="payment">
                <input id="pay_card" name="comiket_payment_method_cd_sel" type="radio" checked="checked" value="2">
                <span class="payment_type_credit_card">クレジットカード</span>                                      
            </label>
            
        </dd>
    </dl>
</div>