<?php /**********************************************************************************************************************/ ?>
                        <div class="input-inbound input-inbound-title">搬出</div>
<?php /**********************************************************************************************************************/ ?>
                        <div class="dl_block input-inbound comiket_block">
                            <dl>
                                <dt id="comiket_detail_inbound_name">
                                    お届け先名
                                </dt>
                                <dd>
                                    <?php echo $eve001Out->comiket_detail_inbound_name() ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_inbound_zip">
                                    お届け先郵便番号
                                </dt>

                                <dd>
                                    〒<?php echo $eve001Out->comiket_detail_inbound_zip1();?>-<?php echo $eve001Out->comiket_detail_inbound_zip2();?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_inbound_pref">
                                    お届け先都道府県
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_inbound_pref')) { echo ' class="form_error"'; } ?>>
<?php
        echo Sgmov_View_Dsn_Input::_getLabelSelectPulldownData($eve001Out->comiket_detail_inbound_pref_cds(), $eve001Out->comiket_detail_inbound_pref_lbls(), $eve001Out->comiket_detail_inbound_pref_cd_sel());
?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_inbound_address">
                                    お届け先市区町村
                                </dt>
                                <dd>
                                    <?php echo $eve001Out->comiket_detail_inbound_address();?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_inbound_building">
                                    お届け先番地・建物名・部屋番号
                                </dt>
                                <dd>
                                    <?php echo $eve001Out->comiket_detail_inbound_building();?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_inbound_tel">
                                    お届け先TEL
                                </dt>
                                <dd>
                                    <?php echo $eve001Out->comiket_detail_inbound_tel();?>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_inbound_collect_date">
                                    お預かり日
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_inbound_collect_date')) { echo ' class="form_error"'; } ?>>
                                    <?php $displaySetting = "block"; ?>
                                    <?php if(isset($dispItemInfo["eventsub_selected_data"]["is_eq_inbound_collect"]) && $dispItemInfo["eventsub_selected_data"]["is_eq_inbound_collect"]) : ?>
                                        <?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_collect_fr"]; ?>
                                        <?php $displaySetting = "none"; ?>
                                    <?php else: ?>

                                        <input type="hidden" id="hid_comiket-detail-inbound-collect-date-from"  name="hid_comiket-detail-inbound-collect-date-from" value="<?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_collect_fr_dt"]; ?>" />
                                        <input type="hidden" id="hid_comiket-detail-inbound-collect-date-to"    name="hid_comiket-detail-inbound-collect-date-to"   value="<?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_collect_to_dt"]; ?>" />
                                    <?php endif; ?>
                                        <div class="comiket_detail_inbound_collect_date_parts" style="display:<?php echo $displaySetting; ?>">
                                            <?php echo $eve001Out->comiket_detail_inbound_collect_date_year_sel();?>年<?php echo $eve001Out->comiket_detail_inbound_collect_date_month_sel();?>月<?php echo $eve001Out->comiket_detail_inbound_collect_date_day_sel();?>日（<?php echo Sgmov_View_Dsn_Input::_getWeek($eve001Out->comiket_detail_inbound_collect_date_year_sel(), $eve001Out->comiket_detail_inbound_collect_date_month_sel(), $eve001Out->comiket_detail_inbound_collect_date_day_sel()); ?>）&nbsp;
<?php
                echo Sgmov_View_Dsn_Input::_getLabelSelectPulldownData($eve001Out->comiket_detail_inbound_collect_time_cds(), $eve001Out->comiket_detail_inbound_collect_time_lbls(), $eve001Out->comiket_detail_inbound_collect_time_sel());
?>

                                        </div>

                                </dd>
                            </dl>
                            <dl class="comiket_detail_inbound_service_sel">
                                <dt id="comiket_detail_inbound_service_sel">
                                    サービス選択
                                </dt>
                                <dd<?php
                                    if (isset($e)
                                       && ($e->hasErrorForId('comiket_detail_inbound_service_sel'))
                                    ) {
                                        echo ' class="form_error"';
                                    }
                                ?>>

                                    <?php foreach($dispItemInfo['comiket_detail_service_lbls'] as $key => $val) : ?>
                                        <label class="radio-label" for="comiket_detail_inbound_service_sel<?php echo $key; ?>">
                                            <input<?php if ($eve001Out->comiket_detail_inbound_service_sel() == $key) echo ' checked="checked"'; ?> id="comiket_detail_inbound_service_sel<?php echo $key; ?>" name="comiket_detail_inbound_service_sel" type="radio" value="<?php echo $key; ?>" />
                                            <?php echo $val; ?>
                                        </label>
                                    <?php endforeach; ?>
                                    <div class='comiket_detail_inbound_service_sel2'>
                                        <strong class="red">※カーゴの場合、時間指定はお受けできません。配達は9:00～18:00でのお伺いとなります。</strong>
                                    </div>
                                </dd>
                            </dl>
                            <dl class="comiket_detail_inbound_delivery_date">
                                <dt id="comiket_detail_inbound_delivery_date">
                                    お届け指定日時
                                </dt>
                                <dd>
                                    <input type="hidden" id="hid_comiket-detail-inbound-delivery-date-from"     name="hid_comiket-detail-inbound-delivery-date-from"        value="<?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_delivery_fr_dt"]; ?>" />
                                    <input type="hidden" id="hid_comiket-detail-inbound-delivery-date-to"       name="hid_comiket-detail-inbound-delivery-date-to"          value="<?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_delivery_to_dt"]; ?>" />
                                    <input type="hidden" id="hid_comiket-detail-inbound-delivery-date-from_ori" name="hid_comiket-detail-inbound-delivery-date-from_ori"    value="<?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_delivery_fr_dt"]; ?>" />
                                    <input type="hidden" id="hid_comiket-detail-inbound-delivery-date-to_ori"   name="hid_comiket-detail-inbound-delivery-date-to_ori"      value="<?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_delivery_to_dt"]; ?>" />
                                    <div class="comiket_detail_inbound_delivery_date_parts">
                                        <?php echo $eve001Out->comiket_detail_inbound_delivery_date_year_sel();?>年<?php echo $eve001Out->comiket_detail_inbound_delivery_date_month_sel();?>月<?php echo $eve001Out->comiket_detail_inbound_delivery_date_day_sel();?>日（<?php echo Sgmov_View_Dsn_Input::_getWeek($eve001Out->comiket_detail_inbound_delivery_date_year_sel(), $eve001Out->comiket_detail_inbound_delivery_date_month_sel(), $eve001Out->comiket_detail_inbound_delivery_date_day_sel()); ?>）&nbsp;
<?php
            echo Sgmov_View_Dsn_Input::_getLabelSelectPulldownData($eve001Out->comiket_detail_inbound_delivery_time_cds(), $eve001Out->comiket_detail_inbound_delivery_time_lbls(), $eve001Out->comiket_detail_inbound_delivery_time_sel());
?>
                                    </div>
                                </dd>
                            </dl>
                            <dl class="service-inbound-item" service-id="1">
                                <dt id="comiket_box_inbound_num_ary">
                                    宅配数量
                                </dt>
                                <dd>
                                <table>
                                    <tr>
                                        <td class='box_table_td' style='vertical-align: middle;width:40%;'>
                                            
                                                <?php // if($eveOutForm->comiket_div() == Sgmov_View_Dsn_Common::COMIKET_DEV_INDIVIDUA) : ?>
                                                    <table>
                                                    <?php foreach($dispItemInfo['inbound_box_lbls'] as $key => $val) : ?>
                                                        <?php $boxNum = $eve001Out->comiket_box_inbound_num_ary($val["id"]); ?>
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
                                                <?php // endif; ?>
                                            <td style='padding-top:5px;'>
                                            </td>
                                    </tr>
                                </table>
                                </dd>
                            </dl>

                            <dl class="service-inbound-item" service-id="2">
                                <dt id="comiket_cargo_inbound_num_ary">
                                    カーゴ数量
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_cargo_inbound_num_ary')) { echo ' class="form_error"'; } ?>>

                                    <div class="comiket-cargo-inbound-num" div-id="<?php echo Sgmov_View_Dsn_Common::COMIKET_DEV_INDIVIDUA; ?>"> <!-- 個人 -->
                                    </div>
                                    <div class="comiket-cargo-inbound-num" div-id="<?php echo Sgmov_View_Dsn_Common::COMIKET_DEV_BUSINESS; ?>"> <!-- 法人 -->
                                    </div>
                                    <div class="">
                                        <input autocapitalize="off" class="number-only boxWid" maxlength="2" inputmode="numeric" name="comiket_cargo_inbound_num_sel" data-pattern="^\d+$" placeholder="例）1" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $eve001Out->comiket_cargo_inbound_num_sel();?>" />台
                                    </div>
                                </dd>
                            </dl>
                            <dl class="service-inbound-item" service-id="3">
                                <dt id="comiket_charter_inbound_num_ary">
                                    台数貸切
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_charter_inbound_num_ary')) { echo ' class="form_error"'; } ?>>

                                    <div class="comiket-charter-inbound-num" div-id="<?php echo Sgmov_View_Dsn_Common::COMIKET_DEV_INDIVIDUA; ?>"> <!-- 個人 -->
                                        <input autocapitalize="off" class="" style="width:10%;" maxlength="3" inputmode="numeric" name="comiket_charter_inbound_num_ary[0]" data-pattern="^\d+$" placeholder="例）7" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $eve001Out->comiket_charter_inbound_num_ary("0");?>" />台
                                    </div>
                                    <div class="comiket-charter-inbound-num" div-id="<?php echo Sgmov_View_Dsn_Common::COMIKET_DEV_BUSINESS; ?>"> <!-- 法人 -->
                                        <table>
                                        <?php foreach($dispItemInfo['charter_lbls'] as $key => $val) : ?>
                                            <tr>
                                                <td class='comiket_charter_item_name'><?php echo $val["name"]; ?>&nbsp;</td>
                                                <td class='comiket_charter_item_value'>
                                                    <input autocapitalize="off" class="number-only boxWid" maxlength="2" inputmode="numeric" name="comiket_charter_inbound_num_ary[<?php echo $val["id"]; ?>]" data-pattern="^\d+$" placeholder="例）1" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $eve001Out->comiket_charter_inbound_num_ary($val["id"]);?>" />台
                                                    &nbsp;
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </table>
                                    </div>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_inbound_note">
                                    備考
                                </dt>
                                <dd>
                                    <?php echo $eve001Out->comiket_detail_inbound_note1();?>
                                </dd>
                            </dl>
                        </div>