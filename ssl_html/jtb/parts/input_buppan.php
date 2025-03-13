<div class="dl_block_conf1 section mt20px tacwebkit buppanForm" >
    <div class="conf_regis_move_panel1 buppanParentdiv t_left crsr-default" style="font-size: 20px;background-color: #92c5ec;">搬出用の段ボールを希望の方はこちら<img class="arrow-img" src="/images/common/img_allow_9.png"></div>

    <div class="dl_block input-buppan comiket_block buppanChilddiv">
        <dl>
            <dt id="comiket_box_buppan_num_ary" style="background-color: #92c5ec;">
                ダンボール<br>エアーキャップ数量
            </dt>
            <dd class="service-buppan-item" service-id="1" style = "padding:13px 7px;background-color: #d5e8f7;">
                <div style="background-color: #d5e8f7;">
                    <div class="comiket-box-buppan-num comiket-box-buppan-num-dmy" >
                        <!-- 個人・法人 両方 -->
                        <table>
                            <tbody 
                            <?php
                            if (isset($e) && ($e->hasErrorForId('comiket_box_buppan_num_ary'))) {
                                echo ' class="form_error"';
                            }
                            ?>>
                                <tr>
                                    <td>
                                        <table id = "comiket_box_buppan_num_ary" style="margin-left: 8px;" class="buppanTable">
                                            <tbody>
                                            <?php foreach ($dispItemInfo['input_buppan_lbls'] as $key => $value) : ?>
                                                    <tr class="box_<?php echo $value['size_display']; ?>">
                                                        <td class="comiket_box_item_name"><?php echo $value['name'] ?>&nbsp;</td>
                                                        <td class="comiket_box_item_value" style="white-space: nowrap;"><input autocapitalize="off" class="number-only comiket_box_item_value_input" maxlength="2" inputmode="numeric" name="comiket_box_buppan_num_ary[<?php echo $value['id'] ?>]" data-pattern="^d+$" placeholder="例）1" type="text"  style="min-width: 60px;"  value="<?php echo $jtb001Out->comiket_box_buppan_num_ary($value["id"]); ?>">枚</td>
                                                    </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td class="dispSeigyoPC" style='vertical-align: middle;text-align: right;'>
                                        <?php if ($jtb001Out->comiket_div() == Sgmov_View_Jtb_Common::COMIKET_DEV_INDIVIDUA) : // 個人 ?>
                                            <img src='/jtb/images/about_boxsize.png' width='80%'/>
                                        <?php else: ?>
                                            <!--<img src='/<?=$dirDiv?>/images/about_boxsize.png' width='100%'/>-->
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="dispSeigyoSP" style="margin-top: 1em; margin-left: 8px;">
                        <img src="/<?=$dirDiv?>/images/about_boxsize.png" width="250px" style="margin-top: 1em;">
                    </div>
                    <br>
                    <div class="buppan_example_boxsize example_boxsize" style="margin-left: 8px;">
                        <a href="/<?=$dirDiv?>/pdf/example/example_box_size.pdf" target="_blank" style="color:blue;">目安表</a>
                    </div>
                </div> 
            </dd>
        </dl>
        <dl>
            <dt id="comiket_detail_buppan_collect_date" style="background-color: #92c5ec;">
                引渡し希望日<span style="display: none;">必須</span>
            </dt>
            <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_buppan_collect_date')) {
            echo ' class="form_error"';
        } ?>  style = "background-color: #d5e8f7;">
                <?php // var_dump($dispItemInfo["eventsub_selected_data"]["is_eq_buppan_collect"]); ?>
                <?php $displaySetting = "block"; ?>
                <?php if (isset($dispItemInfo["eventsub_selected_data"]["is_eq_inbound_collect"]) && $dispItemInfo["eventsub_selected_data"]["is_eq_inbound_collect"]) : ?>
                    <?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_collect_fr"]; ?>
                    <?php $displaySetting = "none"; ?>
                <?php else: ?>

                    <p class="comiket-detail-buppan-collect-date-fr-to">
                        <span class="comiket-detail-buppan-collect-date-from"><?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_collect_fr"]; ?></span>
                        から
                        <span class="comiket-detail-buppan-collect-date-to"><?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_collect_to"]; ?></span>
                        まで選択できます。
                    </p>
                    <input type="hidden" id="hid_comiket-detail-buppan-collect-date-from"  name="hid_comiket-detail-buppan-collect-date-from" value="<?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_collect_fr_dt"]; ?>" />
                    <input type="hidden" id="hid_comiket-detail-buppan-collect-date-to"    name="hid_comiket-detail-buppan-collect-date-to"   value="<?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_collect_to_dt"]; ?>" />
                <?php endif; ?>
                <div class="comiket_detail_buppan_collect_date_parts" style="display:<?php echo $displaySetting; ?>">
                    <select name="comiket_detail_buppan_collect_date_year_sel"
                            class="from_to_selectbox_y"
                            _gid="buppan_collect_date"
                            _from_slctr ="#hid_comiket-detail-buppan-collect-date-from"
                            _to_slctr ="#hid_comiket-detail-buppan-collect-date-to"
                            _selected="<?php echo @$jtb001Out->comiket_detail_buppan_collect_date_year_sel(); ?>"
                            _first="年を選択"
                            style="background-color: white;"
                            >
                    </select>年
                    <select name="comiket_detail_buppan_collect_date_month_sel"
                            class="from_to_selectbox_m"
                            _gid="buppan_collect_date"
                            _selected="<?php echo @$jtb001Out->comiket_detail_buppan_collect_date_month_sel(); ?>"
                            _first="月を選択"
                            style="background-color: white;"
                            >
                    </select>月
                    <select name="comiket_detail_buppan_collect_date_day_sel"
                            class="from_to_selectbox_d"
                            _gid="buppan_collect_date"
                            _selected="<?php echo @$jtb001Out->comiket_detail_buppan_collect_date_day_sel(); ?>"
                            _first="日を選択"
                            style="background-color: white;"
                            >
                    </select>日
                </div>
                <!--<strong class="red">※14時以降の受付となります</strong>-->
                <!--<strong class="red">※会場での宅配カウンター開設時間は、04月11日（土）16:00～21:00、04月12日（日）14:00～21:00 です。 </strong>-->

            </dd>
        </dl>
    </div>
</div>

    
