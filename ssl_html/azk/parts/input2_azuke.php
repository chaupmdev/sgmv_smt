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
<?php
        $eventName = Sgmov_View_Azk_Input::_getLabelSelectPulldownData($eve001Out->event_cds(), $eve001Out->event_lbls(), $eve001Out->event_cd_sel());
        echo $eventName;
        if(strpos($eventName,'デザインフェスタ') !== false){
        echo '<br><br><img src="/dsn/images/logo.gif">';
        }
?>

                                    <select name="event_sel" class="comiket_diplay_none">
                                        <option value="" timeoverflg="0">選択してください</option>
                                        <?php
                                            echo Sgmov_View_Azk_Input::_createPulldown($eve001Out->event_cds(), $eve001Out->event_lbls(), $eve001Out->event_cd_sel(), $eve001Out->eve_timeover_flg(), $eve001Out->eve_timeover_date());
                                        ?>
                                        </select>
<span class='eventsub_sel'>
<?php
        $eventsubName = Sgmov_View_Azk_Input::_getLabelSelectPulldownData($eve001Out->eventsub_cds(), $eve001Out->eventsub_lbls(), $eve001Out->eventsub_cd_sel());
        echo $eventsubName;
?>

 <select name="eventsub_sel" class="eventsub_sel" style="display: none;">
            <option value="">選択してください</option>
            <?php
                echo Sgmov_View_Azk_Input::_createPulldown($eve001Out->eventsub_cds(), $eve001Out->eventsub_lbls(), $eve001Out->eventsub_cd_sel());
            ?>
        </select>
</span>
<?php if($eventsubInfo["is_manual_display"]) : ?>
<br/>
<br/>
<div class="sp_dl_area2">

    <div class="eventsub_dl_link manual" >
        <img src="/images/common/img_icon_pdf.gif" width="18" height="21" alt="">
        <a style="color: blue;" class="manual_link" href="/dsn/pdf/manual/<?php echo $eventName; ?>.pdf<?php echo '?' . $strSysdate; ?>" target="_blank">説明書</a>
    </div>
</div>
<?php endif; ?>


                                </dd>
                            </dl>
                            <dl>
                                <dt id="event_address">
                                    会場名
                                </dt>
                                <dd>
                                    <span>
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
                                    2021年08月21日（土）&nbsp;・&nbsp;2021年08月22日（日）&nbsp;・&nbsp;2021年08月28日（土）
                                    <input class="" style="width:100px;" autocapitalize="off" inputmode="eventsub_term_fr" name="eventsub_term_fr" data-pattern="" placeholder="" type="hidden" value="<?php echo $eve001Out->eventsub_term_fr(); ?>" />
                                    <input class="" style="width:100px;" autocapitalize="off" inputmode="eventsub_term_to" name="eventsub_term_to" data-pattern="" placeholder="" type="hidden" value="<?php echo $eve001Out->eventsub_term_to(); ?>" />
                                </dd>
                            </dl>
                             <dl>
                                <dt id="comiket_staff_seimei_furi">
                                    ご利用者名<span>必須</span>
                                </dt>
                                <dd>
                                    <?php echo $eve001Out->comiket_staff_sei_furi() ?>&nbsp;<?php echo $eve001Out->comiket_staff_mei_furi() ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_staff_seimei_furi">
                                    ご利用者名<span class ="hissu">必須</span><br/ ><font style="color:red">※ カタカナ・英字のみ</font>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_staff_seimei_furi')) { echo ' class="form_error"'; } ?>>
                                    <span class="wb">セイ<input class="w_40Per" maxlength="8" autocapitalize="off" inputmode="comiket_staff_sei_furi" name="comiket_staff_sei_furi" data-pattern="" placeholder="" type="text" value="<?php echo $eve001Out->comiket_staff_sei_furi() ?>" /></span>
                                    <span class="wb">メイ<input class="w_40Per" maxlength="8" autocapitalize="off" inputmode="comiket_staff_mei_furi" name="comiket_staff_mei_furi" data-pattern="" placeholder="" type="text" value="<?php echo $eve001Out->comiket_staff_mei_furi() ?>" /></span>
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
                                        １回のみ
                                    </div>
                                    <label class="radio-label comiket_detail_type_sel-label" for="comiket_detail_azukari_kaisu_type_sel1" style="display: none;">
                                            <input id="comiket_detail_azukari_kaisu_type_sel1" class="comiket_detail_azukari_kaisu_type_sel" name="comiket_detail_azukari_kaisu_type_sel" type="radio" value="1" checked />
                                            １回のみ
                                    </label>
                                    <label class="radio-label comiket_detail_type_sel-label" for="comiket_detail_azukari_kaisu_type_sel2" style="color: grey;display: none;">
                                        <input id="comiket_detail_azukari_kaisu_type_sel2" class="comiket_detail_azukari_kaisu_type_sel" name="comiket_detail_azukari_kaisu_type_sel" type="radio" value="2" disabled  />
                                        複数回 
                                    </label>
                                    <!-- &nbsp;&nbsp;<strong class="red">※当日に「何度でも」手荷物の出し入れが可能です。</strong> -->
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_name">
                                    <abc class="otodokesaki_simei">氏名</abc><span>必須</span>
                                </dt>
                                <dd<?php
                                    if (isset($e)
                                       && ($e->hasErrorForId('comiket_detail_name'))
                                    ) {
                                        echo ' class="form_error"';
                                    }
                                ?>>
                                    <input class="" style="width:80%;" maxlength="32" autocapitalize="off" inputmode="comiket_detail_name" name="comiket_detail_name" data-pattern="" placeholder="" type="text" value="<?php echo $eve001Out->comiket_detail_name() ?>">
                                    <input class="m110" name="azuke_adrs_copy_btn" type="button" value="お申込者と同じ">
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_tel">
                                    <abc class="otodokesaki_tel">TEL</abc><span>必須</span>
                                </dt>
                                <dd<?php
                                    if (isset($e)
                                       && ($e->hasErrorForId('comiket_detail_tel'))
                                    ) {
                                        echo ' class="form_error"';
                                    }
                                ?>>
                                    <input autocapitalize="off" class="number-p-only" style="width:50%;" maxlength="15" name="comiket_detail_tel" data-pattern="^[0-9-]+$" placeholder="例）075-1234-5678" type="text" value="<?php echo $eve001Out->comiket_detail_tel();?>">
                                </dd>
                            </dl>
                            <dl class="class_comiket_detail_collect_date">
                                <dt id="comiket_detail_collect_date">
                                    お預かり日<span>必須</span>
                                </dt>
                                <dd <?php if (isset($e) && $e->hasErrorForId('comiket_detail_collect_date')) { echo ' class="form_error"'; } ?>>

                                    <!-- <p class="comiket-detail-outbound-collect-date-fr-to">2021年08月21日（月）&nbsp;から&nbsp;2021年08月22日（金）&nbsp;まで選択できます。</p> -->
                                    
                                    <p class="comiket-detail-outbound-collect-date-fr-to_test">2021年08月21日（土）&nbsp;・&nbsp;2021年08月22日（日）&nbsp;・&nbsp;2021年08月28日（土）選択できます。</p>

                                

                                    <input type="hidden" id="hid_comiket-detail-collect-date-from"  name="hid_comiket-detail-collect-date-from" value="<?php echo @$dispItemInfo["eventsub_selected_data"]["collect_fr_dt"]; ?>" />
                                    <input type="hidden" id="hid_comiket-detail-collect-date-to"    name="hid_comiket-detail-collect-date-to"   value="<?php echo @$dispItemInfo["eventsub_selected_data"]["collect_to_dt"]; ?>" />
                                    <div class="comiket_detail_collect_date_parts">
                                       <!--  <select name="comiket_detail_date_year_sel"
                                                class="from_to_selectbox_y"
                                                _gid="delivery_date"
                                                _from_slctr ="#hid_comiket-detail-collect-date-from"
                                                _to_slctr ="#hid_comiket-detail-collect-date-to"
                                                _selected="<?php echo @$eve001Out->comiket_detail_collect_date_year_sel(); ?>"
                                                _first="年を選択"
                                                >
                                        </select>年
                                        <select name="comiket_detail_delivery_date_month_sel"
                                                class="from_to_selectbox_m"
                                                _gid="delivery_date"
                                                _selected="<?php echo @$eve001Out->comiket_detail_collect_date_month_sel(); ?>"
                                                _first="月を選択"
                                                >
                                        </select>月
                                        <select name="comiket_detail_delivery_date_day_sel"
                                                class="from_to_selectbox_d"
                                                _gid="delivery_date"
                                                _selected="<?php echo @$eve001Out->comiket_detail_collect_date_day_sel(); ?>"
                                                _first="日を選択"
                                                >
                                        </select>日 -->

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
                                <dl class="service-item">
                                    <dt id="comiket_box_num">
                                        荷物形状<span>必須</span>
                                    </dt>
                                    <dd <?php if (isset($e) && $e->hasErrorForId('comiket_box_num')) { echo ' class="form_error"'; } ?>>
                                        <div class="comiket-box-num comiket-box-num-dmy">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table style="width: min-width;">
                                                                <tbody>
                                                                    <?php foreach($dispItemInfo['comiket_box_lbls'] as $key => $val) : ?>
                                                                        <tr class ="box_<?php echo $val['id'];?>">
                                                                            <td class="comiket_box_item_name">
                                                                                <?php
                                                                                    $boxName = $val["name_display"];
                                                                                    if (empty($val['name_display']) || $val['name_display'] == null):
                                                                                        $boxName = $val['name'];
                                                                                    endif;
                                                                                    echo $boxName;
                                                                                ?>&nbsp;
                                                                            </td>
                                                                            <td class="comiket_box_item_value">
                                                                                <input autocapitalize="off" class="number-only comiket_box_item_value_input" maxlength="2" inputmode="numeric" name="comiket_box_num_ary[<?php echo $val['id']; ?>]" data-pattern="^d+$" placeholder="例）1" type="text" value="<?php echo $eve001Out->comiket_box_num_ary($val["id"]);?>">個
                                                                            &nbsp;
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach; ?>                                                                        
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                        <td class="dispSeigyoPCTr" style="vertical-align: middle;text-align: right;width: 47%;">
                                                            <img class="dispSeigyoPC" src="/azk/images/about_boxsize.png" width="100%">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div class="dispSeigyoSP">
                                                <img src="/azk/images/about_boxsize.png" style="margin-top: 1em;" width="250px">
                                            </div>
                                        </div>
                                    </dd>
                                </dl>
                                <dl>
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
                                </dl>
                            </div>
                            <style type="text/css">
                                .comiket_box_item_value_input {
                                    width: 25% !important;
                                }
                            </style>
                            <!-- ***************************************************************************************************************************************** -->
                            <!-- 荷物預入取出 End -->
                            <!-- ***************************************************************************************************************************************** -->