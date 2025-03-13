                    <input id="comiket_customer_kbn_sel1" class="comiket_customer_kbn" name="comiket_customer_kbn_sel" type="hidden" value="0">
                    <div class="dl_block comiket_block">
                        <dl>
                            <dt id="event_sel">出展イベント</dt>
                            <dd>
                                <span class="">
                                    <?php
                                        $eventName = Sgmov_View_Bpn_ActiveShohin::_getLabelSelectPulldownData($bpn001Out->event_cds(), $bpn001Out->event_lbls(), $bpn001Out->event_cd_sel());
                                    echo $eventName;
                                    ?>&nbsp;&nbsp;
                                    <?php
                                        $eventsubName = Sgmov_View_Bpn_ActiveShohin::_getLabelSelectPulldownData($bpn001Out->eventsub_cds(), $bpn001Out->eventsub_lbls(), $bpn001Out->eventsub_cd_sel());
                                        echo $eventsubName;
                                    ?>
                                </span>
                                <input type="hidden" id="hid_timezone_flg" name="hid_timezone_flg" value="0">
                                <input type="hidden" id="event_sel" name="event_sel" value="<?php echo $bpn001Out->event_cd_sel(); ?>">
                                <input type="hidden" id="eventsub_sel" name="eventsub_sel" value="<?php echo $bpn001Out->eventsub_cd_sel(); ?>"><br>
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
                                    $selectedEventData = @$dispItemInfo["eventsub_selected_data"];
                                    echo @$selectedEventData["venue"];
                                ?>
                            </span>
                            <input autocapitalize="off" inputmode="eventsub_address" name="eventsub_address" data-pattern="" placeholder="" type="hidden" value="<?php echo @$selectedEventData["venue"]; ?>" />
                            </dd>
                        </dl>
                        <dl>
                            <dt id="eventsub_term">
                                期間
                            </dt>
                            <dd>
                                <?php if($bpn001Out->eventsub_term_fr_nm() == $bpn001Out->eventsub_term_to_nm()):?>
                                    <span class="event-term_fr-lbl"><?php echo $bpn001Out->eventsub_term_fr_nm(); ?></span>
                                <?php else: ?>
                                    <span class="event-term_fr-lbl"><?php echo $bpn001Out->eventsub_term_fr_nm(); ?></span>
                                    <span class="event-term_fr-str">&nbsp;から&nbsp;</span>
                                    <span class="event-term_to-lbl"><?php echo $bpn001Out->eventsub_term_to_nm(); ?></span>
                                <?php endif; ?>
                                <input autocapitalize="off" inputmode="eventsub_term_fr" name="eventsub_term_fr" data-pattern="" placeholder="" type="hidden" value="<?php echo $bpn001Out->eventsub_term_fr(); ?>" />
                                <input autocapitalize="off" inputmode="eventsub_term_to" name="eventsub_term_to" data-pattern="" placeholder="" type="hidden" value="<?php echo $bpn001Out->eventsub_term_to(); ?>" />
                            </dd>
                        </dl>
                         <dl style="display: none;">
                            <dt id="comiket_div">
                                識別<span>必須</span>
                            </dt>
                            <dd>
                                <?php foreach($dispItemInfo['comiket_div_lbls'] as $key => $val) : ?>
                                    <label class="radio-label comiket_div<?php echo $key; ?>" for="comiket_div<?php echo $key; ?>" style="display:none;">
                                        <input<?php if ($bpn001Out->comiket_div() == $key) echo ' checked="checked"'; ?> id="comiket_div<?php echo $key; ?>" name="comiket_div" type="radio" value="<?php echo $key; ?>" />
                                        <?php echo $val; ?>
                                    </label>
                                    <br />
                                <?php endforeach; ?>
                            </dd>
                        </dl>
                        <?php if($bpn001Out->eventsub_cd_sel() == "302" && $bpn001Out->bpn_type() == "1" && $bpn001Out->shohin_pattern() == "2"): ?>
                             <dl>
                                <dt id="comiket_detail_collect_date">
                                    商品引き渡し日<span>必須</span>
                                </dt>
                                <dd 
                                    <?php 
                                        if (isset($e) && $e->hasErrorForId('comiket_detail_collect_date')) { echo ' class="form_error"'; } 
                                    ?>
                                >
                                    <select name="comiket_detail_collect_date_year_sel">
                                            <?php
                                                echo Sgmov_View_Bpn_ActiveShohin::_createPulldown($bpn001Out->collect_year_cds(), $bpn001Out->collect_year_lbls(), $bpn001Out->collect_year_cd_sel());
                                            ?>
                                    </select>年
                                    <select name="comiket_detail_collect_date_month_sel">
                                                <?php
                                                    echo Sgmov_View_Bpn_ActiveShohin::_createPulldown($bpn001Out->collect_month_cds(), $bpn001Out->collect_month_lbls(), $bpn001Out->collect_month_cd_sel());
                                                ?>
                                    </select>月
                                    <select name="comiket_detail_collect_date_day_sel">
                                            <?php
                                                echo Sgmov_View_Bpn_ActiveShohin::_createPulldown($bpn001Out->collect_day_cds(), $bpn001Out->collect_day_lbls(), $bpn001Out->collect_day_cd_sel());
                                            ?>
                                    </select>日
                                </dd>
                            </dl>
                        <?php endif; ?>
                    </div>
