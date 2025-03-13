<?php /**********************************************************************************************************************/ ?>
                        <div class="input-outbound input-outbound-title">搬入</div>
<?php /**********************************************************************************************************************/ ?>
                        <div class="dl_block input-outbound comiket_block">

                                <!--<dl style="font-size:20px;border-style: hidden;"><dt>搬入</dt><dd></dd></dl>-->
                            <dl>
                                <dt id="comiket_detail_outbound_name">
                                    集荷先名<span>必須</span>
                                </dt>
                                <dd<?php
                                    if (isset($e)
                                       && ($e->hasErrorForId('comiket_detail_outbound_name'))
                                    ) {
                                        echo ' class="form_error"';
                                    }
                                ?>>
                                <input class="" style="width:80%;" maxlength="32" autocapitalize="off" inputmode="comiket_detail_outbound_name" name="comiket_detail_outbound_name" data-pattern="" placeholder="" type="text" value="<?php echo $eve001Out->comiket_detail_outbound_name() ?>" />
                                <input class="m110" name="outbound_adrs_copy_btn" type="button" value="お申込者と同じ" />
                                <div class="disp_company">
                                    <br/>
                                    <strong class="red">
                                    ※集荷先がご契約の住所と異なる場合、ご契約の運賃が適用できない場合がございますので、
                                    最寄りの佐川急便の営業所にお問い合わせください。<br/><br/>
                                    <a href="http://www2.sagawa-exp.co.jp/send/branch_search/moyori/area/" target="_blank">営業所・サービスセンター・取次店検索はこちら</a>
                                    </strong>
                                </div>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_outbound_zip">
                                    集荷先郵便番号<span>必須</span>
                                </dt>

                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_outbound_zip')) { echo ' class="form_error"'; } ?>>
                                    〒<input autocapitalize="off" class="w_70 number-only" maxlength="3" inputmode="numeric" name="comiket_detail_outbound_zip1" data-pattern="^\d+$" placeholder="例）136" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $eve001Out->comiket_detail_outbound_zip1();?>" />
                                    -
                                    <input autocapitalize="off" class="w_70 number-only" maxlength="4" inputmode="numeric" name="comiket_detail_outbound_zip2" data-pattern="^\d+$" placeholder="例）0082" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $eve001Out->comiket_detail_outbound_zip2();?>" />
                                    <input class="m110" name="outbound_adrs_search_btn" type="button" value="住所検索" />
                                    <span class="wb" style="font-size:12px;">
                                            &#12288;※郵便番号が不明な方は<a style="text-decoration: underline" target="_blank" href="http://www.post.japanpost.jp/zipcode/">こちら...</a>
                                    </span>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_outbound_pref">
                                    集荷先都道府県<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_outbound_pref')) { echo ' class="form_error"'; } ?>>
                                    <select name="comiket_detail_outbound_pref_cd_sel">
                                        <option value="">選択してください</option>
<?php
        echo Sgmov_View_Eve_Input::_createPulldown($eve001Out->comiket_detail_outbound_pref_cds(), $eve001Out->comiket_detail_outbound_pref_lbls(), $eve001Out->comiket_detail_outbound_pref_cd_sel());
?>
                                    </select>
                                    <br/>
                                    <br/>
                                    <strong class="red">
                                        ※沖縄の場合は航空運賃が適用されます。<br/><br/>
                                        ※郡部・離島・一部地域で中継料が発生する場合は、お荷物のお取り扱いが出来ない場合がございます。
                                    </strong>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_outbound_address">
                                    集荷先市区町村<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_outbound_address')) { echo ' class="form_error"'; } ?>>
                                    <input class="" style="width:80%;" autocapitalize="off" maxlength="14" name="comiket_detail_outbound_address" placeholder="例）江東区新砂" type="text" value="<?php echo $eve001Out->comiket_detail_outbound_address();?>" />
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_outbound_building">
                                    集荷先番地・建物名
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_outbound_building')) { echo ' class="form_error"'; } ?>>
                                    <input class="" style="width:80%;" autocapitalize="off" maxlength="30" name="comiket_detail_outbound_building" placeholder="例）1-8-2" type="text" value="<?php echo $eve001Out->comiket_detail_outbound_building();?>" />
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_outbound_tel">
                                    集荷先TEL<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_outbound_tel')) { echo ' class="form_error"'; } ?>>
                                    <input autocapitalize="off" class="number-p-only" style="width:50%;" maxlength="15" name="comiket_detail_outbound_tel" data-pattern="^[0-9-]+$" placeholder="例）075-1234-5678" type="text" value="<?php echo $eve001Out->comiket_detail_outbound_tel();?>" />
                                </dd>
                            </dl>
                            <dl class="class_comiket_detail_outbound_collect_date">
                                <dt id="comiket_detail_outbound_collect_date">
                                    お預かり日時<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_outbound_collect_date')) { echo ' class="form_error"'; } ?> class="comiket_detail_outbound_collect_date_input_part">
                                    <p class="comiket-detail-outbound-collect-date-fr-to">
                                        <span class="comiket-detail-outbound-collect-date-from"><?php echo @$dispItemInfo["eventsub_selected_data"]["outbound_collect_fr"]; ?></span>
                                        から
                                        <span class="comiket-detail-outbound-collect-date-to"><?php echo @$dispItemInfo["eventsub_selected_data"]["outbound_collect_to"]; ?></span>
                                        まで選択できます。
                                    </p>
                                    <input type="hidden" id="hid_comiket-detail-outbound-collect-date-from"     name="hid_comiket-detail-outbound-collect-date-from"        value="<?php echo @$dispItemInfo["eventsub_selected_data"]["outbound_collect_fr_dt"]; ?>" />
                                    <input type="hidden" id="hid_comiket-detail-outbound-collect-date-to"       name="hid_comiket-detail-outbound-collect-date-to"          value="<?php echo @$dispItemInfo["eventsub_selected_data"]["outbound_collect_to_dt"]; ?>" />
                                    <input type="hidden" id="hid_comiket-detail-outbound-collect-date-from_ori" name="hid_comiket-detail-outbound-collect-date-from_ori"    value="<?php echo @$dispItemInfo["eventsub_selected_data"]["outbound_collect_fr_dt"]; ?>" />
                                    <input type="hidden" id="hid_comiket-detail-outbound-collect-date-to_ori"   name="hid_comiket-detail-outbound-collect-date-to_ori"      value="<?php echo @$dispItemInfo["eventsub_selected_data"]["outbound_collect_to_dt"]; ?>" />
                                    <div class="comiket_detail_outbound_collect_date_parts">
                                        <span class="comiket_detail_outbound_collect_date">
                                            <select name="comiket_detail_outbound_collect_date_year_sel">
                                                <option value="">年を選択</option>
<?php
        echo Sgmov_View_Eve_Input::_createPulldown($eve001Out->comiket_detail_outbound_collect_date_year_cds(), $eve001Out->comiket_detail_outbound_collect_date_year_lbls(), $eve001Out->comiket_detail_outbound_collect_date_year_sel());
?>
                                            </select>年
                                        </span>
                                        <span class="comiket_detail_outbound_collect_date">
                                            <select name="comiket_detail_outbound_collect_date_month_sel">
                                                <option value="">月を選択</option>
<?php
        echo Sgmov_View_Eve_Input::_createPulldown($eve001Out->comiket_detail_outbound_collect_date_month_cds(), $eve001Out->comiket_detail_outbound_collect_date_month_lbls(), $eve001Out->comiket_detail_outbound_collect_date_month_sel());
?>
                                            </select>月
                                        </span>
                                        <span class="comiket_detail_outbound_collect_date">
                                            <select name="comiket_detail_outbound_collect_date_day_sel">
                                                <option value="">日を選択</option>
<?php
            echo Sgmov_View_Eve_Input::_createPulldown($eve001Out->comiket_detail_outbound_collect_date_day_cds(), $eve001Out->comiket_detail_outbound_collect_date_day_lbls(), $eve001Out->comiket_detail_outbound_collect_date_day_sel());
?>
                                            </select>日
                                        </span>
                                        &nbsp;
                                        <span class="comiket_detail_outbound_collect_time_sel">
                                        時間帯
                                            <select name="comiket_detail_outbound_collect_time_sel">
                                                <option value="">時間帯を選択</option>
    <?php
            echo Sgmov_View_Eve_Input::_createPulldown($eve001Out->comiket_detail_outbound_collect_time_cds(), $eve001Out->comiket_detail_outbound_collect_time_lbls(), $eve001Out->comiket_detail_outbound_collect_time_sel());
    ?>
                                            </select>
                                        </span>
                                    </div>
                                </dd>
                            </dl>
                            <dl class="comiket_detail_outbound_service_sel">
                                <dt id="comiket_detail_outbound_service_sel">
                                    サービス選択<span>必須</span>
                                </dt>
                                <dd<?php
                                    if (isset($e)
                                       && ($e->hasErrorForId('comiket_detail_outbound_service_sel'))
                                    ) {
                                        echo ' class="form_error"';
                                    }
                                ?>>

                                    <?php foreach($dispItemInfo['comiket_detail_service_lbls'] as $key => $val) : ?>
                                        <label class="radio-label" for="comiket_detail_outbound_service_sel<?php echo $key; ?>">
                                            <input<?php if ($eve001Out->comiket_detail_outbound_service_sel() == $key) echo ' checked="checked"'; ?> id="comiket_detail_outbound_service_sel<?php echo $key; ?>" name="comiket_detail_outbound_service_sel" type="radio" value="<?php echo $key; ?>" />
                                            <?php echo $val; ?>
                                        </label>
                                    <?php endforeach; ?>
                                    <div>
                                        <strong class="red">※カーゴの場合、時間指定はお受けできません。集荷は9:00～18:00でのお伺いとなります。</strong>
                                    </div>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_outbound_delivery_date">
                                    引渡し希望日<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_outbound_delivery_date')) { echo ' class="form_error"'; } ?>>
                                    <p class="comiket-detail-outbound-delivery-date-fr-to">
                                        <span class="comiket-detail-outbound-delivery-date-from"><?php echo @$dispItemInfo["eventsub_selected_data"]["outbound_delivery_fr"]; ?></span>から<span class="comiket-detail-outbound-delivery-date-to"><?php echo @$dispItemInfo["eventsub_selected_data"]["outbound_delivery_to"]; ?></span>まで選択できます。
                                    </p>
                                    <input type="hidden" id="hid_comiket-detail-outbound_delivery-date-from"    name="hid_comiket-detail-outbound_delivery-date-from"   value="<?php echo @$dispItemInfo["eventsub_selected_data"]["outbound_delivery_fr_dt"]; ?>" />
                                    <input type="hidden" id="hid_comiket-detail-outbound_delivery-date-to"      name="hid_comiket-detail-outbound_delivery-date-to"     value="<?php echo @$dispItemInfo["eventsub_selected_data"]["outbound_delivery_to_dt"]; ?>" />
                                    <div class="comiket_detail_outbound_delivery_date_parts">
                                        <span class="comiket_detail_outbound_delivery_date">
                                            <select name="comiket_detail_outbound_delivery_date_year_sel">
                                                <option value="">年を選択</option>
<?php
        echo Sgmov_View_Eve_Input::_createPulldown($eve001Out->comiket_detail_outbound_delivery_date_year_cds(), $eve001Out->comiket_detail_outbound_delivery_date_year_lbls(), $eve001Out->comiket_detail_outbound_delivery_date_year_sel());
?>
                                            </select>年
                                        </span>
                                        <span class="comiket_detail_outbound_delivery_date">
                                            <select name="comiket_detail_outbound_delivery_date_month_sel">
                                                <option value="">月を選択</option>
<?php
        echo Sgmov_View_Eve_Input::_createPulldown($eve001Out->comiket_detail_outbound_delivery_date_month_cds(), $eve001Out->comiket_detail_outbound_delivery_date_month_lbls(), $eve001Out->comiket_detail_outbound_delivery_date_month_sel());
?>
                                            </select>月
                                        </span>
                                        <span class="comiket_detail_outbound_delivery_date">
                                            <select name="comiket_detail_outbound_delivery_date_day_sel">
                                                <option value="">日を選択</option>
<?php
        echo Sgmov_View_Eve_Input::_createPulldown($eve001Out->comiket_detail_outbound_delivery_date_day_cds(), $eve001Out->comiket_detail_outbound_delivery_date_day_lbls(), $eve001Out->comiket_detail_outbound_delivery_date_day_sel());
?>
                                            </select>日
                                        </span>
                                        &nbsp;
                                        <span class="comiket_detail_outbound_delivery_time_sel">
                                            時間帯
                                            <select name="comiket_detail_outbound_delivery_time_sel">
                                                <option value="">時間帯を選択</option>
    <?php
            echo Sgmov_View_Eve_Input::_createPulldown($eve001Out->comiket_detail_outbound_delivery_time_cds(), $eve001Out->comiket_detail_outbound_delivery_time_lbls(), $eve001Out->comiket_detail_outbound_delivery_time_sel());
    ?>
                                            </select>
                                        </span>
                                    </div>
                                </dd>
                            </dl>
                            <dl class="service-outbound-item" service-id="1">
                                <dt id="comiket_box_outbound_num_ary">
                                    宅配数量<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_box_outbound_num_ary')) { echo ' class="form_error"'; } ?>>
                                    <div class="comiket-box-outbound-num comiket-box-outbound-num-dmy"> <!-- 個人・法人 両方 -->
                                        <?php // if($eve001Out->comiket_div() == Sgmov_View_Eve_Common::COMIKET_DEV_BUSINESS) : ?>
                                        <table>
                                            <tr>
                                                <td>
                                                    <table>
                                                    <?php foreach($dispItemInfo['outbound_box_lbls'] as $key => $val) : ?>
                                                        <tr>
                                                        <!--<div style="margin-bottom: 10px;">-->
                                                            <td class='comiket_box_item_name'>
                                                                <?php echo empty($val["name"]) ? "" : $val["name"]; ?>&nbsp;
                                                            </td>
                                                            <td class='comiket_box_item_value'>
                                                                <input autocapitalize="off" <?php if(count($dispItemInfo['outbound_box_lbls']) == 1): ?> style='width:20%' <?php endif; ?> class="number-only comiket_box_item_value_input" maxlength="2" inputmode="numeric" name="comiket_box_outbound_num_ary[<?php echo $val["id"]; ?>]" data-pattern="^\d+$" placeholder="例）1" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $eve001Out->comiket_box_outbound_num_ary($val["id"]);?>" />個
                                                                &nbsp;
                                                            </td>
                                                        <!--</div>-->
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    </table>
                                                </td>
                                                <td class="dispSeigyoPC" style='vertical-align: middle;text-align: right;'>
                                                    <?php if($eve001Out->comiket_div() == Sgmov_View_Eve_Common::COMIKET_DEV_INDIVIDUA) : // 個人 ?>
                                                        <img src='/tme/images/about_boxsize.png' width='100%'/>
                                                    <?php else: ?>
                                                        <!--<img src='/tme/images/about_boxsize.png' width='100%'/>-->
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        </table>
                                        <?php // endif; ?>
                                    </div>
                                    <?php if($eve001Out->comiket_div() == Sgmov_View_Eve_Common::COMIKET_DEV_INDIVIDUA) : // 個人 ?>
                                    <div class="dispSeigyoSP" style="margin-top: 1em;">
                                        <img src='/tme/images/about_boxsize.png' width='250px' style='margin-top: 1em;' />
                                    </div>
                                    <?php else: ?>
                                    <!--<img src='/tme/images/about_boxsize.png' width='100%'/>-->
                                    <?php endif; ?>
                                    <br/>
                                    <div class="outbound_example_boxsize example_boxsize">
                                        <strong class="red">※ 最大4種類(サイズ)までご指定頂けます</strong><br/><br/>
                                        <strong class="red">※ 5種類以上をご希望の場合は、4種類ずつに分けて、複数のお申込みをお願い致します</strong><br/><br/>
                                        <a href="/tme/pdf/example/example_box_size.pdf" target="_blank" style="color:blue;">目安表</a>
                                    </div>
                                </dd>
                            </dl>
                            <dl class="service-outbound-item" service-id="2">
                                <dt id="comiket_cargo_outbound_num_ary">
                                    カーゴ数量<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_cargo_outbound_num_ary')) { echo ' class="form_error"'; } ?> >
                                    <div class="comiket-cargo-outbound-num" div-id="<?php echo Sgmov_View_Eve_Common::COMIKET_DEV_INDIVIDUA; ?>"> <!-- 個人 -->
                                    </div>
                                    <div class="comiket-cargo-outbound-num" div-id="<?php echo Sgmov_View_Eve_Common::COMIKET_DEV_BUSINESS; ?>"> <!-- 法人 -->
                                    </div>
                                        <div style="">
                                            <input autocapitalize="off" class="number-only boxWid" maxlength="2" inputmode="numeric" name="comiket_cargo_outbound_num_sel" data-pattern="^\d+$" placeholder="例）1" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $eve001Out->comiket_cargo_outbound_num_sel();?>" />台
<!--                                            <select name="comiket_cargo_outbound_num_sel">
                                                <option value="">カーゴ数量を選択</option>
<?php
                                                echo Sgmov_View_Eve_Input::_createPulldown($eve001Out->comiket_cargo_outbound_num_cds(), $eve001Out->comiket_cargo_outbound_num_lbls(), $eve001Out->comiket_cargo_outbound_num_sel());
?>
                                            </select>-->

                                        </div>
                                </dd>
                            </dl>
                            <dl class="service-outbound-item" service-id="3">
                                <dt id="comiket_charter_outbound_num_ary">
                                    台数貸切<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_charter_outbound_num')) { echo ' class="form_error"'; } ?>>
                                    <div class="comiket-charter-outbound-num" div-id="<?php echo Sgmov_View_Eve_Common::COMIKET_DEV_INDIVIDUA; ?>"> <!-- 個人 -->
                                        <input autocapitalize="off" class="" style="width:10%;" maxlength="2" inputmode="numeric" name="comiket_charter_outbound_num_ary[0]" data-pattern="^\d+$" placeholder="例）7" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $eve001Out->comiket_charter_outbound_num_ary("0");?>" />台
                                    </div>
                                    <div class="comiket-charter-outbound-num" div-id="<?php echo Sgmov_View_Eve_Common::COMIKET_DEV_BUSINESS; ?>"> <!-- 法人 -->
                                        <!--<div style="margin-bottom: 10px;">-->
                                            <table>
                                            <?php foreach($dispItemInfo['charter_lbls'] as $key => $val) : ?>
                                                    <tr>
                                                <!--<div style="margin-bottom: 10px;">-->
                                                        <td class='comiket_charter_item_name'><?php echo $val["name"]; ?>&nbsp;</td>
                                                        <td class='comiket_charter_item_value'>
                                                            <input autocapitalize="off" class="number-only boxWid" maxlength="2" inputmode="numeric" name="comiket_charter_outbound_num_ary[<?php echo $val["id"]; ?>]" data-pattern="^\d+$" placeholder="例）1" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $eve001Out->comiket_charter_outbound_num_ary($val["id"]);?>" />台
                                                            &nbsp;
                                                        </td>
                                                <!--</div>-->
                                                    </tr>
                                            <?php endforeach; ?>
                                            </table>
                                        <!--</div>-->
                                    </div>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_outbound_note">
                                    備考
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_outbound_note')) { echo ' class="form_error"'; } ?>>
                                    <input autocapitalize="off" class="" style="width:50%;" maxlength="16" inputmode="text" name="comiket_detail_outbound_note1" placeholder="" type="text" value="<?php echo $eve001Out->comiket_detail_outbound_note1();?>" /><br/>
                                </dd>
                            </dl>

                        </div>