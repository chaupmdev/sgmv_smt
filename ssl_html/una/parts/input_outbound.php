                        <div class="dl_block input-outbound comiket_block" style="border-top: 0px">
                            <input id="comiket_detail_outbound_binshu_sel1" name="comiket_detail_outbound_binshu_kbn_sel" type="hidden" value="0">
                            <dl style="display:none">
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
                                <input class="" style="width:80%;" maxlength="32" autocapitalize="off" inputmode="comiket_detail_outbound_name" name="comiket_detail_outbound_name" data-pattern="" placeholder="例）佐川 花子" type="text" value="<?php echo $eve001Out->comiket_detail_outbound_name() ?>" />
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
                            <dl style="display:none">
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
                            <dl style="display:none">
                                <dt id="comiket_detail_outbound_pref">
                                    集荷先都道府県<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_outbound_pref')) { echo ' class="form_error"'; } ?>>
                                    <select name="comiket_detail_outbound_pref_cd_sel">
                                        <option value="">選択してください</option>
<?php
        echo Sgmov_View_Una_Input::_createPulldown($eve001Out->comiket_detail_outbound_pref_cds(), $eve001Out->comiket_detail_outbound_pref_lbls(), $eve001Out->comiket_detail_outbound_pref_cd_sel());
?>
                                    </select>
                                    <br/>
                                    <br/>
                                    <strong class="red" style="line-height: normal;">
                                        ※沖縄の場合は航空運賃が適用されます。<br/>
                                        ※郡部・離島・一部地域で中継料が発生する場合は、お荷物のお取り扱いが出来ない場合がございます。
                                        <br /><a href="/pdf/kokubin_goriyoujyono_tyui.pdf" target="_blank" style="color: #1774bc; text-decoration: underline;">航空便ご利用上の注意はこちら</a>
                                        <br /><a href="/pdf/kokutakuhaibinnado_unso_yakkan.pdf" target="_blank" style="color: #1774bc; text-decoration: underline;">航空宅配便等個建運送約款はこちら</a>
                                    </strong>
                                </dd>
                            </dl>
                            <dl style="display:none">
                                <dt id="comiket_detail_outbound_address">
                                    集荷先市区町村<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_outbound_address')) { echo ' class="form_error"'; } ?>>
                                    <input class="" style="width:80%;" autocapitalize="off" maxlength="14" name="comiket_detail_outbound_address" placeholder="例）江東区新砂" type="text" value="<?php echo $eve001Out->comiket_detail_outbound_address();?>" />
                                </dd>
                            </dl>
                            <dl style="display:none">
                                <dt id="comiket_detail_outbound_building">
                                    集荷先番地・建物名・部屋番号<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_outbound_building')) { echo ' class="form_error"'; } ?>>
                                    <input class="" style="width:80%;" autocapitalize="off" maxlength="30" name="comiket_detail_outbound_building" placeholder="例）1-8-2" type="text" value="<?php echo $eve001Out->comiket_detail_outbound_building();?>" />
                                    <br/><br/>
                                    <strong class="red" style="line-height: normal;">※集合住宅にお住まいの方は建物名や部屋番号も漏れなくご記入ください。<br>　記載漏れがあると集荷に伺えない場合があります。</strong>
                                </dd>
                            </dl>
                            <dl style="display:none">
                                <dt id="comiket_detail_outbound_tel">
                                    集荷先TEL<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_outbound_tel')) { echo ' class="form_error"'; } ?>>
                                    <input autocapitalize="off" class="number-p-only" style="width:50%;" maxlength="15" name="comiket_detail_outbound_tel" data-pattern="^[0-9-]+$" placeholder="例）075-1234-5678" type="text" value="<?php echo $eve001Out->comiket_detail_outbound_tel();?>" />
                                </dd>
                            </dl>
                            <dl class="service-outbound-item" service-id="1">
                                <dt id ="comiket_box_outbound_num_ary">
                                    宅配数量<span>必須</span>
                                    <br/>
                                    <br/>
                                    <strong class="red">お一人様１個まで</strong>
                                </dt>
                                <dd <?php
                                    if (isset($e)
                                       && ($e->hasErrorForId('comiket_box_outbound_num_ary'))
                                    ) {
                                        echo ' class="form_error"';
                                    }
                                ?>>
                                    <br/>
                                    <div class="outbound_example_boxsize example_boxsize">
                                        <strong class="red">お申込みはお一人様1点まで</strong>
                                        <p><strong class="red">申込人数以上の個数の入力は対応ができかねます</strong></p>
                                        <p>
                                           例）ツアーを２名様でお申し込み、スーツケースが２個になる場合、2個を入力してください。<br />
                                           ご家族で１個のお手荷物の場合は１を入力してください。<br />
                                           お申込みのご家族様以上の個数の入力はご遠慮ください。<br />
                                       </p>
                                    </div>
                                    <br/>
                                    <?php $isFirst = true;?>
                                    <?php foreach($dispItemInfo['box_lbls'] as $key => $val) : ?>
                                        <?php if ($isFirst) : ?>
                                            <label class="departure">
                                                <input autocapitalize="off" style='width:10%' class="number-only comiket_box_item_value_input" maxlength="2" inputmode="numeric" name="comiket_box_outbound_num_ary[<?php echo $val["id"]; ?>]" data-pattern="^\d+$" placeholder="例）1" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $eve001Out->comiket_box_outbound_num_ary($val["id"]); ?>" />
                                                個
                                            </label>
                                        <?php else : ?>
                                            <label class="arrival" <?php if ($eve001Out->raw_comiket_detail_type_sel2 === '1') { echo ' style="display:none;"';}?>>
                                                復路
                                                <input autocapitalize="off" style='width:10%' class="number-only comiket_box_item_value_input" maxlength="2" inputmode="numeric" name="comiket_box_outbound_num_ary[<?php echo $val["id"]; ?>]" data-pattern="^\d+$" placeholder="例）1" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $eve001Out->comiket_box_outbound_num_ary($val["id"]); ?>" />
                                                個
                                            </label>
                                        <?php endif; ?>
                                        <?php $isFirst = false;?>
                                    <?php endforeach; ?>
                                </dd>
                            </dl>
                            
                            <dl class="class_building_name_sel">
                                <dt id="building_name_sel" style=" border-bottom: solid 1px #ccc !important;">
                                    宿泊先<span class ="hissu">必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('building_name_sel')) { echo ' class="form_error"'; } ?>>
                                    <input type="hidden" name="building_name_sel" value="501" >
                                    <select name="building_booth_position_sel">
                                        <option value="">選択してください</option>
<?php
                                         echo Sgmov_View_Una_Input::_createPulldown($eve001Out->building_booth_position_ids(), $eve001Out->building_booth_position_lbls(), $eve001Out->building_booth_position_sel());
?>
                                    </select>
                                    <input style="display:none" class="w_30Per" autocapitalize="off" maxlength="4" name="comiket_booth_num" placeholder="例）1234(数値4桁)" type="text" value="<?php echo $eve001Out->comiket_booth_num();?>" />
                                </dd>
                            </dl>
                            <dl class="comiket_detail_outbound_service_sel" style="display:none">
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
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_outbound_delivery_date">
                                    宿泊日（引き渡し希望日）<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_outbound_delivery_date')) { echo ' class="form_error"'; } ?>>
                                    <p class="comiket-detail-outbound-delivery-date-fr-to" style="display:none">
                                        <span class="comiket-detail-outbound-delivery-date-from"><?php echo @$dispItemInfo["eventsub_selected_data"]["outbound_delivery_fr"]; ?></span>から<span class="comiket-detail-outbound-delivery-date-to"><?php echo @$dispItemInfo["eventsub_selected_data"]["outbound_delivery_to"]; ?></span>まで選択できます。
                                    </p>
                                    <input type="hidden" id="hid_comiket-detail-outbound_delivery-date-from"    name="hid_comiket-detail-outbound_delivery-date-from"   value="<?php echo @$dispItemInfo["eventsub_selected_data"]["outbound_delivery_fr_dt"]; ?>" />
                                    <input type="hidden" id="hid_comiket-detail-outbound_delivery-date-to"      name="hid_comiket-detail-outbound_delivery-date-to"     value="<?php echo @$dispItemInfo["eventsub_selected_data"]["outbound_delivery_to_dt"]; ?>" />
                                    <input type="hidden" id="hid_comiket_detail_outbound_delivery_date"         name="hid_comiket_detail_outbound_delivery_date"        value="" />
                                    <div class="comiket_detail_outbound_delivery_date_parts">
                                        <span class="comiket_detail_outbound_delivery_date">
                                            <select id="comiket_detail_outbound_delivery_date_year_sel" name="comiket_detail_outbound_delivery_date_year_sel" 
                                                    class="from_to_selectbox_y"
                                                    _gid="outbound_delivery_date"
                                                    _from_slctr ="#hid_comiket-detail-outbound_delivery-date-from"
                                                    _to_slctr ="#hid_comiket-detail-outbound_delivery-date-to"
                                                    _selected="<?php echo @$eve001Out->comiket_detail_outbound_delivery_date_year_sel(); ?>"
                                                    _first="年を選択"
                                                    >
                                            </select>年
                                        </span>
                                        <span class="comiket_detail_outbound_delivery_date">
                                            <select id="comiket_detail_outbound_delivery_date_month_sel" name="comiket_detail_outbound_delivery_date_month_sel"
                                                    class="from_to_selectbox_m"
                                                    _gid="outbound_delivery_date"
                                                    _selected="<?php echo @$eve001Out->comiket_detail_outbound_delivery_date_month_sel(); ?>"
                                                    _first="月を選択"
                                                    >
                                            </select>月
                                        </span>
                                        <span class="comiket_detail_outbound_delivery_date">
                                            <select id="comiket_detail_outbound_delivery_date_day_sel" name="comiket_detail_outbound_delivery_date_day_sel"
                                                    class="from_to_selectbox_d"
                                                    _gid="outbound_delivery_date"
                                                    _selected="<?php echo @$eve001Out->comiket_detail_outbound_delivery_date_day_sel(); ?>"
                                                    _first="日を選択"
                                                    >
                                            </select>日
                                        </span>
                                        &nbsp;
                                        <span class="comiket_detail_outbound_delivery_time_sel">
                                            時間帯
                                            <select name="comiket_detail_outbound_delivery_time_sel">
                                                <option value="">時間帯を選択</option>
    <?php
            echo Sgmov_View_Una_Input::_createPulldown($eve001Out->comiket_detail_outbound_delivery_time_cds(), $eve001Out->comiket_detail_outbound_delivery_time_lbls(), $eve001Out->comiket_detail_outbound_delivery_time_sel());
    ?>
                                            </select>
                                        </span>
                                    </div>
                                    <div class="eventsub_dl_link" style="display:none">
                                        <img src="/images/common/img_icon_pdf.gif" class = "vam" width="18" height="21" alt="">
                                        <a style="color: blue;" href="<?=$dispItemInfo['dispEvent']['manual']?>" target="_blank">引き渡し場所</a>
                                    </div>
                                </dd>
                            </dl>
                            <dl class="class_comiket_detail_outbound_collect_date">
                                <dt id="comiket_detail_outbound_collect_date">
                                    お預かり希望日時<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_outbound_collect_date')) { echo ' class="form_error"'; } ?> class="comiket_detail_outbound_collect_date_input_part">
                                    <p class="comiket-detail-outbound-collect-date-fr-to" style="display:none">
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
                                            <select id="comiket_detail_outbound_collect_date_year_sel" name="comiket_detail_outbound_collect_date_year_sel"
                                                    class="from_to_selectbox_y"
                                                    _gid="outbound_collect_date"
                                                    _from_slctr ="#hid_comiket-detail-outbound-collect-date-from"
                                                    _to_slctr ="#hid_comiket-detail-outbound-collect-date-to"
                                                    _selected="<?php echo @$eve001Out->comiket_detail_outbound_collect_date_year_sel(); ?>"
                                                    _first="年を選択"
                                                    >
                                            </select>年
                                        </span>
                                        <span class="comiket_detail_outbound_collect_date">
                                            <select name="comiket_detail_outbound_collect_date_month_sel"
                                                    class="from_to_selectbox_m"
                                                    _gid="outbound_collect_date"
                                                    _selected="<?php echo @$eve001Out->comiket_detail_outbound_collect_date_month_sel(); ?>"
                                                    _first="月を選択"
                                                    >
                                            </select>月
                                        </span>
                                        <span class="comiket_detail_outbound_collect_date">
                                            <select name="comiket_detail_outbound_collect_date_day_sel"
                                                    class="from_to_selectbox_d"
                                                    _gid="outbound_collect_date"
                                                    _selected="<?php echo @$eve001Out->comiket_detail_outbound_collect_date_day_sel(); ?>"
                                                    _first="日を選択"
                                                    >
                                            </select>日
                                        </span>
                                        &nbsp;
                                        <span class="comiket_detail_outbound_collect_time_sel">
                                        時間帯
                                            <select name="comiket_detail_outbound_collect_time_sel">
                                                <option value="">時間帯を選択</option>
    <?php
            echo Sgmov_View_Una_Input::_createPulldown($eve001Out->comiket_detail_outbound_collect_time_cds(), $eve001Out->comiket_detail_outbound_collect_time_lbls(), $eve001Out->comiket_detail_outbound_collect_time_sel());
    ?>
                                            </select>
                                        </span>
                                    </div>
                                    <br>
                                </dd>
                            </dl>
                            
                            <dl class="arrival" <?php if ($eve001Out->raw_comiket_detail_type_sel2 === '1') { echo ' style="display:none;"';}?>>
                                <dt  id="top_comiket_detail_collect_date">
                                    復路集荷日<span>必須</span>
                                </dt>
                                <dd class="<?php if (isset($e) && $e->hasErrorForId('top_comiket_detail_collect_date')) { echo ' form_error'; } ?>">
                                    <select name="comiket_detail_collect_date_sel">
                                        <option value="">選択してください</option>
                                        <?php
                                        echo Sgmov_View_Una_Input::_createPulldown($eve001Out->raw_comiket_detail_collect_date_cds, $eve001Out->raw_comiket_detail_collect_date_lbls, $eve001Out->raw_comiket_detail_collect_date_sel);
                                        ?>
                                    </select>
                                </dd>
                            </dl>
                            <dl class="service-outbound-item" service-id="2">
                                <dt id="comiket_cargo_outbound_num_ary">
                                    カーゴ数量<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_cargo_outbound_num_ary')) { echo ' class="form_error"'; } ?> >
                                    <div class="comiket-cargo-outbound-num" div-id="<?php echo Sgmov_View_Una_Common::COMIKET_DEV_INDIVIDUA; ?>"> <!-- 個人 -->
                                    </div>
                                    <div class="comiket-cargo-outbound-num" div-id="<?php echo Sgmov_View_Una_Common::COMIKET_DEV_BUSINESS; ?>"> <!-- 法人 -->
                                    </div>
                                        <div style="">
                                            <input autocapitalize="off" class="number-only boxWid" maxlength="2" inputmode="numeric" name="comiket_cargo_outbound_num_sel" data-pattern="^\d+$" placeholder="例）1" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $eve001Out->comiket_cargo_outbound_num_sel();?>" />台
                                        </div>
                                </dd>
                            </dl>
                            <dl class="service-outbound-item" service-id="3">
                                <dt id="comiket_charter_outbound_num_ary">
                                    台数貸切<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_charter_outbound_num')) { echo ' class="form_error"'; } ?>>
                                    <div class="comiket-charter-outbound-num" div-id="<?php echo Sgmov_View_Una_Common::COMIKET_DEV_INDIVIDUA; ?>"> <!-- 個人 -->
                                        <input autocapitalize="off" class="" style="width:10%;" maxlength="2" inputmode="numeric" name="comiket_charter_outbound_num_ary[0]" data-pattern="^\d+$" placeholder="例）7" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $eve001Out->comiket_charter_outbound_num_ary("0");?>" />台
                                    </div>
                                    <div class="comiket-charter-outbound-num" div-id="<?php echo Sgmov_View_Una_Common::COMIKET_DEV_BUSINESS; ?>"> <!-- 法人 -->
                                            <table>
                                            <?php //foreach($dispItemInfo['charter_lbls'] as $key => $val) : ?>
                                                    <tr>
                                                        <td class='comiket_charter_item_name'><?php //echo $val["name"]; ?>&nbsp;</td>
                                                        <td class='comiket_charter_item_value'>
                                                            <input autocapitalize="off" class="number-only boxWid" maxlength="2" inputmode="numeric" name="comiket_charter_outbound_num_ary[<?php //echo $val["id"]; ?>]" data-pattern="^\d+$" placeholder="例）1" type="<?php echo $inputTypeNumber; ?>" value="<?php //echo $eve001Out->comiket_charter_outbound_num_ary($val["id"]);?>" />台
                                                            &nbsp;
                                                        </td>
                                                    </tr>
                                            <?php //endforeach; ?>
                                            </table>
                                    </div>
                                </dd>
                            </dl>
                            <dl style="display:none">
                                <dt id="comiket_detail_outbound_note">
                                    備考
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_outbound_note')) { echo ' class="form_error"'; } ?>>
                                    <input autocapitalize="off" class="" style="width:50%;" maxlength="16" inputmode="text" name="comiket_detail_outbound_note1" placeholder="" type="text" value="<?php echo $eve001Out->comiket_detail_outbound_note1();?>" /><br/><br/>
                                    <strong class="red" style="line-height: normal;">
                                        保険のご加入を希望される方は下記までご連絡ください。<br/>
                                        <?=$dispItemInfo['dispEvent']['sgName']?> <?=$dispItemInfo['dispEvent']['customName']?>
                                         <?=$dispItemInfo['dispEvent']['tel1']?>  (10:00～17:00)<br/>
                                        callcenter-kyouyuu@sagawa-exp.co.jp<br/>
                                    </strong>
                                </dd>
                            </dl>
                            <dl>
                                <dt>
                                    その他注意事項
                                </dt>
                                <dd>
                                    <p>危険品、壊れやすいもの等はお送りできません。</br>
                                    天災・事故などによる交通渋滞、異常気象が原因でお届けが遅れる場合がございます。</br>
                                    貴重品類はご自身の管理にてお願い致します。</p>
                                </dd>
                            </dl>
                        </div>