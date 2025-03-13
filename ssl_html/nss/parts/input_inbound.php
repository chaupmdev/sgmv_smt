<?php /**********************************************************************************************************************/ ?>

                        <div class="input-inbound input-inbound-title">搬出</div>
<?php /**********************************************************************************************************************/ ?>
                        <div class="dl_block input-inbound comiket_block">
                            <input id="comiket_detail_inbound_binshu_sel1" name="comiket_detail_inbound_binshu_kbn_sel" type="hidden" value="0">

                            <dl>
                                <dt id="comiket_detail_inbound_name">
                                    お届け先名<span>必須</span>
                                </dt>
                                <dd<?php
                                    if (isset($e)
                                       && ($e->hasErrorForId('comiket_detail_inbound_name'))
                                    ) {
                                        echo ' class="form_error"';
                                    }
                                ?>>
                                <input class="" style="width:80%;" autocapitalize="off" inputmode="comiket_detail_inbound_name" name="comiket_detail_inbound_name" data-pattern="" placeholder="例）佐川 花子" type="text" value="<?php echo $eve001Out->comiket_detail_inbound_name() ?>" />
                                <input class="m110" name="inbound_adrs_copy_btn" type="button" value="お申込者と同じ" />
                                <div class="disp_company">
                                    <br/>
                                    <strong class="red">
                                    ※お届け先がご契約の住所と異なる場合、ご契約の運賃が適用できない場合がございますので、
                                    最寄りの佐川急便の営業所にお問い合わせください。<br/><br/>
                                    <a href="http://www2.sagawa-exp.co.jp/send/branch_search/moyori/area/" target="_blank">営業所・サービスセンター・取次店検索はこちら</a>
                                    </strong>
                                </div>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_inbound_zip">
                                    お届け先郵便番号<span>必須</span>
                                </dt>

                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_inbound_zip')) { echo ' class="form_error"'; } ?>>
                                    〒<input autocapitalize="off" class="w_70 number-only" maxlength="3" inputmode="numeric" name="comiket_detail_inbound_zip1" data-pattern="^\d+$" placeholder="例）136" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $eve001Out->comiket_detail_inbound_zip1();?>" />
                                    -
                                    <input autocapitalize="off" class="w_70 number-only" maxlength="4" inputmode="numeric" name="comiket_detail_inbound_zip2" data-pattern="^\d+$" placeholder="例）0082" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $eve001Out->comiket_detail_inbound_zip2();?>" />
                                    <input class="m110" name="inbound_adrs_search_btn" type="button" value="住所検索" />
                                    <span style="font-size:12px;display: inline-block !important;" class="forget-address-discription">
                                        &#12288;※郵便番号が不明な方は<a style="text-decoration: underline" target="_blank" href="http://www.post.japanpost.jp/zipcode/">こちら...</a>
                                    </span>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_inbound_pref">
                                    お届け先都道府県<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_inbound_pref')) { echo ' class="form_error"'; } ?>>
                                    <select name="comiket_detail_inbound_pref_cd_sel">
                                        <option value="">選択してください</option>
<?php
        echo Sgmov_View_Nss_Input::_createPulldown($eve001Out->comiket_detail_inbound_pref_cds(), $eve001Out->comiket_detail_inbound_pref_lbls(), $eve001Out->comiket_detail_inbound_pref_cd_sel());
?>
                                    </select>
                                    <br/>
                                    <br/>
                                    <strong class="red">
                                        ※沖縄の場合は航空運賃が適用されます。<br/><br/>
                                        ※郡部・離島・一部地域で中継料が発生する場合は、お荷物のお取り扱いが出来ない場合がございます。
                                        <br /><a href="/pdf/kokubin_goriyoujyono_tyui.pdf" target="_blank" style="color: #1774bc; text-decoration: underline;">航空便ご利用上の注意はこちら</a>
                                        <br /><a href="/pdf/kokutakuhaibinnado_unso_yakkan.pdf" target="_blank" style="color: #1774bc; text-decoration: underline;">航空宅配便等個建運送約款はこちら</a>
                                    </strong>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_inbound_address">
                                    お届け先市区町村<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_inbound_address')) { echo ' class="form_error"'; } ?>>
                                    <input class="" style="width:80%;" maxlength="14" autocapitalize="off" name="comiket_detail_inbound_address" placeholder="例）江東区新砂" type="text" value="<?php echo $eve001Out->comiket_detail_inbound_address();?>" />
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_inbound_building">
                                    お届け先番地・建物名・部屋番号<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_inbound_building')) { echo ' class="form_error"'; } ?>>
                                    <input class="" style="width:80%;" maxlength="30" autocapitalize="off" name="comiket_detail_inbound_building" placeholder="例）1-8-2" type="text" value="<?php echo $eve001Out->comiket_detail_inbound_building();?>" />
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_inbound_tel">
                                    お届け先TEL<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_inbound_tel')) { echo ' class="form_error"'; } ?>>
                                    <input autocapitalize="off" class="number-p-only" style="width:50%;" maxlength="15" name="comiket_detail_inbound_tel" data-pattern="^[0-9-]+$" placeholder="例）075-1234-5678" type="text" value="<?php echo $eve001Out->comiket_detail_inbound_tel();?>" />
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_inbound_collect_date">
                                    お預かり日<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_inbound_collect_date')) { echo ' class="form_error"'; } ?>>
                                    <?php // var_dump($dispItemInfo["eventsub_selected_data"]["is_eq_inbound_collect"]); ?>
                                    <?php $displaySetting = "block"; ?>
                                    <?php if(isset($dispItemInfo["eventsub_selected_data"]["is_eq_inbound_collect"]) && $dispItemInfo["eventsub_selected_data"]["is_eq_inbound_collect"]) : ?>
                                        <?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_collect_fr"]; ?>
                                        <?php $displaySetting = "none"; ?>
                                    <?php else: ?>

                                        <p class="comiket-detail-inbound-collect-date-fr-to">
                                            <span class="comiket-detail-inbound-collect-date-from"><?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_collect_fr"]; ?></span>
                                            から
                                            <span class="comiket-detail-inbound-collect-date-to"><?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_collect_to"]; ?></span>
                                            まで選択できます。
                                        </p>
                                    <?php endif; ?>
                                        <input type="hidden" id="hid_comiket-detail-inbound-collect-date-from"  name="hid_comiket-detail-inbound-collect-date-from" value="<?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_collect_fr_dt"]; ?>" />
                                        <input type="hidden" id="hid_comiket-detail-inbound-collect-date-to"    name="hid_comiket-detail-inbound-collect-date-to"   value="<?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_collect_to_dt"]; ?>" />
                                        <div class="comiket_detail_inbound_collect_date_parts" style="display:<?php echo $displaySetting; ?>">
                                            <select name="comiket_detail_inbound_collect_date_year_sel"
                                                    class="from_to_selectbox_y"
                                                    _gid="inbound_collect_date"
                                                    _from_slctr ="#hid_comiket-detail-inbound-collect-date-from"
                                                    _to_slctr ="#hid_comiket-detail-inbound-collect-date-to"
                                                    _selected="<?php echo @$eve001Out->comiket_detail_inbound_collect_date_year_sel(); ?>"
                                                    _first="年を選択"
                                                    >
                                            </select>年
                                            <select name="comiket_detail_inbound_collect_date_month_sel"
                                                    class="from_to_selectbox_m"
                                                    _gid="inbound_collect_date"
                                                    _selected="<?php echo @$eve001Out->comiket_detail_inbound_collect_date_month_sel(); ?>"
                                                    _first="月を選択"
                                                    >
                                            </select>月
                                            <select name="comiket_detail_inbound_collect_date_day_sel"
                                                    class="from_to_selectbox_d"
                                                    _gid="inbound_collect_date"
                                                    _selected="<?php echo @$eve001Out->comiket_detail_inbound_collect_date_day_sel(); ?>"
                                                    _first="日を選択"
                                                    >
                                            </select>日
                                            &nbsp;
                                            <span class="comiket_detail_inbound_collect_time_sel" >
                                                時間帯
                                                <select name="comiket_detail_inbound_collect_time_sel">
                                                    <option value="">時間帯を選択</option>
<?php
                echo Sgmov_View_Nss_Input::_createPulldown($eve001Out->comiket_detail_inbound_collect_time_cds(), $eve001Out->comiket_detail_inbound_collect_time_lbls(), $eve001Out->comiket_detail_inbound_collect_time_sel());
?>
                                                </select>
                                            </span>
                                        </div>
                                        <br>
                                        <div class = "dsp-inblck vat fwb red lhn">
                                            お預かり日時・場所・会場内宅配カウンター開設時間はカウンターによって異なります。
                                        </div>
                                        <div class = "dsp-inblck vat fwb red lhn">
                                            <div class = "dsp-inblck"><img src="/images/common/img_icon_pdf.gif" class = "vat" width="18" height="21" alt="">
                                                    詳しくは<a style="color: blue;" href="<?=$dispItemInfo['dispEvent']['takuhai']?>" target="_blank">宅配カウンターの場所</a>をご覧下さい。</div>
                                        </div>
                                </dd>
                            </dl>
                            <dl class="comiket_detail_inbound_service_sel">
                                <dt id="comiket_detail_inbound_service_sel">
                                    サービス選択<span>必須</span>
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
                                    お届け指定日時<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_inbound_delivery_date')) { echo ' class="form_error"'; } ?>>
                                    <p class="comiket-detail-inbound-delivery-date-fr-to">
                                        <span class="comiket-detail-inbound-delivery-date-from"><?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_delivery_fr"]; ?></span>
                                        から
                                        <span class="comiket-detail-inbound-delivery-date-to"><?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_delivery_to"]; ?></span>
                                        まで選択できます。
                                    </p>
                                    <input type="hidden" id="hid_comiket-detail-inbound-delivery-date-from"     name="hid_comiket-detail-inbound-delivery-date-from"        value="<?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_delivery_fr_dt"]; ?>" />
                                    <input type="hidden" id="hid_comiket-detail-inbound-delivery-date-to"       name="hid_comiket-detail-inbound-delivery-date-to"          value="<?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_delivery_to_dt"]; ?>" />
                                    <input type="hidden" id="hid_comiket-detail-inbound-delivery-date-from_ori" name="hid_comiket-detail-inbound-delivery-date-from_ori"    value="<?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_delivery_fr_dt"]; ?>" />
                                    <input type="hidden" id="hid_comiket-detail-inbound-delivery-date-to_ori"   name="hid_comiket-detail-inbound-delivery-date-to_ori"      value="<?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_delivery_to_dt"]; ?>" />
                                    <div class="comiket_detail_inbound_delivery_date_parts">
                                        <select name="comiket_detail_inbound_delivery_date_year_sel"
                                                class="from_to_selectbox_y"
                                                _gid="inbound_delivery_date"
                                                _from_slctr ="#hid_comiket-detail-inbound-delivery-date-from"
                                                _to_slctr ="#hid_comiket-detail-inbound-delivery-date-to"
                                                _selected="<?php echo @$eve001Out->comiket_detail_inbound_delivery_date_year_sel(); ?>"
                                                _first="年を選択"
                                                >
                                        </select>年
                                        <select name="comiket_detail_inbound_delivery_date_month_sel"
                                                class="from_to_selectbox_m"
                                                _gid="inbound_delivery_date"
                                                _selected="<?php echo @$eve001Out->comiket_detail_inbound_delivery_date_month_sel(); ?>"
                                                _first="月を選択"
                                                >
                                        </select>月
                                        <select name="comiket_detail_inbound_delivery_date_day_sel"
                                                class="from_to_selectbox_d"
                                                _gid="inbound_delivery_date"
                                                _selected="<?php echo @$eve001Out->comiket_detail_inbound_delivery_date_day_sel(); ?>"
                                                _first="日を選択"
                                                >
                                        </select>日
                                        &nbsp;
                                        <span class="comiket_detail_inbound_delivery_time_sel" >
                                        時間帯
                                            <select name="comiket_detail_inbound_delivery_time_sel">
                                                <option value="">時間帯を選択</option>
<?php
            echo Sgmov_View_Nss_Input::_createPulldown($eve001Out->comiket_detail_inbound_delivery_time_cds(), $eve001Out->comiket_detail_inbound_delivery_time_lbls(), $eve001Out->comiket_detail_inbound_delivery_time_sel());
?>
                                            </select>
                                        </span>
                                        <br/>
                                        <br/>
                                    </div>
                                    <strong class="red">
                                        ※天災・事故などによる交通渋滞、異常気象が原因でお届けが遅れる場合がございます。<br/><br/>
                                        ※郡部・離島・一部地域等については、配達日数が異なる場合がございます。<br/><br/>
                                        ※お届け日を選択いただきますが、地域によりお届けが遅れる場合がございます。<br/><br/>
                                        ※貴重品、危険品、信書、貨幣および有価証券のお取り扱いはできません。<br/><br/>
                                    </strong>
                                </dd>
                            </dl>

                            <dl class="service-inbound-item" service-id="1">
                                <dt id="comiket_box_inbound_num_ary">
                                    宅配数量<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_box_inbound_num_ary')) { echo ' class="form_error"'; } ?>>

                                    <div class="comiket-box-inbound-num comiket-box-inbound-num-dmy"> <!-- 個人・法人 どちらでも -->
                                            <table>
                                                <tr>
                                                    <td>
                                                        <table>
                                                            <?php foreach($dispItemInfo['inbound_box_lbls'] as $key => $val) : ?>
                                                                <tr class ="box_<?php echo $val['size_display'];?>">
                                                                    <td class='comiket_box_item_name' style="vertical-align: top;">
                                                                        <?php echo empty($val["name"]) ? "" : $val["name"]; ?>&nbsp;
                                                                    </td>
                                                                    <td class='comiket_box_item_value'>
                                                                        <input autocapitalize="off" class="number-only comiket_box_item_value_input" style="" maxlength="2" inputmode="numeric" name="comiket_box_inbound_num_ary[<?php echo $val["id"]; ?>]" data-pattern="^\d+$" placeholder="例）1" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $eve001Out->comiket_box_inbound_num_ary($val["id"]);?>" />個
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </table>
                                                    </td>
                                                    <td class="dispSeigyoPC" style='vertical-align: middle;text-align: right;'>
                                                        <?php if($eve001Out->comiket_div() == Sgmov_View_Nss_Common::COMIKET_DEV_INDIVIDUA) : // 個人 ?>
                                                            <img src='/<?=$dirDiv?>/images/about_boxsize.png' width='100%'/>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            </table>
                                    </div>
                                    <?php if($eve001Out->comiket_div() == Sgmov_View_Nss_Common::COMIKET_DEV_INDIVIDUA) : // 個人 ?>
                                    <div class="dispSeigyoSP" style="margin-top: 1em;">
                                        <img src='/<?=$dirDiv?>/images/about_boxsize.png' width='250px' style='margin-top: 1em;' />
                                    </div>
                                    <?php else: ?>
                                    <?php endif; ?>
                                    <br/>
                                    <div class="outbound_example_boxsize example_boxsize">
                                        <strong class="red">※ 最大4種類(サイズ)までご指定頂けます</strong><br/><br/>
                                        <strong class="red">※ 5種類以上をご希望の場合は、4種類ずつに分けて、複数のお申込みをお願い致します</strong><br/><br/>
                                        <strong class="red">※ 重量はお荷物１つにつき３０Kｇが上限となります。</strong><br/><br/>
                                        <!--<strong class="red">※ ガラス・鏡などの「割れ物」は、集荷時にお申し出ください。<br>　 お申し出がない場合、損害補償の対象外となります。</strong><br/><br/>-->
                                        <strong class="red">※ １申込み４０個を超える場合は、
                                        <?=$dispItemInfo['dispEvent']['sgName']?>/<?=$dispItemInfo['dispEvent']['customName']?>
                                         <?=$dispItemInfo['dispEvent']['tel1']?> へお問い合わせください。</strong><br/><br/>
                                        <a href="/<?=$dirDiv?>/pdf/example/example_box_size.pdf" target="_blank" style="color:blue;">サイズの目安はこちらをご確認ください</a>
                                    </div>
                                </dd>
                            </dl>
                            <dl class="service-inbound-item" service-id="2">
                                <dt id="comiket_cargo_inbound_num_ary">
                                    カーゴ数量<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_cargo_inbound_num_ary')) { echo ' class="form_error"'; } ?>>

                                    <div class="comiket-cargo-inbound-num" div-id="<?php echo Sgmov_View_Nss_Common::COMIKET_DEV_INDIVIDUA; ?>"> <!-- 個人 -->
                                    </div>
                                    <div class="comiket-cargo-inbound-num" div-id="<?php echo Sgmov_View_Nss_Common::COMIKET_DEV_BUSINESS; ?>"> <!-- 法人 -->
                                    </div>
                                    <div class="">
                                        <input autocapitalize="off" class="number-only boxWid" maxlength="2" inputmode="numeric" name="comiket_cargo_inbound_num_sel" data-pattern="^\d+$" placeholder="例）1" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $eve001Out->comiket_cargo_inbound_num_sel();?>" />台
                                    </div>
                                </dd>
                            </dl>
                            <dl class="service-inbound-item" service-id="3">
                                <dt id="comiket_charter_inbound_num_ary">
                                    台数貸切<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_charter_inbound_num_ary')) { echo ' class="form_error"'; } ?>>

                                    <div class="comiket-charter-inbound-num" div-id="<?php echo Sgmov_View_Nss_Common::COMIKET_DEV_INDIVIDUA; ?>"> <!-- 個人 -->
                                        <input autocapitalize="off" class="" style="width:10%;" maxlength="3" inputmode="numeric" name="comiket_charter_inbound_num_ary[0]" data-pattern="^\d+$" placeholder="例）7" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $eve001Out->comiket_charter_inbound_num_ary("0");?>" />台
                                    </div>
                                    <div class="comiket-charter-inbound-num" div-id="<?php echo Sgmov_View_Nss_Common::COMIKET_DEV_BUSINESS; ?>"> <!-- 法人 -->
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
                            <dl style="display:none">
                                <dt id="comiket_detail_inbound_note">
                                    備考
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_inbound_note')) { echo ' class="form_error"'; } ?>>
                                    <input autocapitalize="off" class="" style="width:50%;" maxlength="16" inputmode="text" name="comiket_detail_inbound_note1" placeholder="" type="text" value="<?php echo $eve001Out->comiket_detail_inbound_note1();?>" /><br/><br/>
                                    <strong class="red" style="line-height: normal;">
                                        保険のご加入を希望される方は下記までご連絡ください。<br/>
                                        <?=$dispItemInfo['dispEvent']['sgName']?> <?=$dispItemInfo['dispEvent']['customName']?>
                                         <?=$dispItemInfo['dispEvent']['tel1']?>  (10:00～17:00)<br/>
                                        callcenter-kyouyuu@sagawa-exp.co.jp<br/>
                                    </strong>
                                </dd>
                            </dl>
                            <!-- GiapLN add 梱包ガイドライン 2022.07.14 --> 
                            <dl>
                                <dt>
                                    梱包ガイドライン<span>必須</span> 
                                </dt>
                                <dd<?php
                                    if (isset($e)
                                       && ($e->hasErrorForId('comiket_detail_caremark_flg_inbound'))
                                    ) {
                                        echo ' class="form_error"';
                                    }
                                ?>
                                >
                                    <div>
                                        <a style="margin-left: 5px;color:blue;text-decoration: underline; font-size: 19px" onclick="EnableCaremarkFlg();" href="/nss/pdf/manual/logistics_information_packing_guide_0111.pdf" target="_blank" class="comiket_detail_caremark_flg_link" >#にじそうさく　物流に関するご案内</a>
                                    </div>
                                    <div style="margin-top:7px; margin-bottom: 7px;">
                                        <a style="margin-left: 5px;color:blue;text-decoration: underline; font-size: 19px" onclick="EnableCaremarkFlg();" href="/nss/pdf/manual/packing_guideline.pdf" target="_blank" class="comiket_detail_caremark_flg_link" >梱包ガイドラインについて(PDF)</a>
                                    </div>
                                    <div style="margin-top:7px; margin-bottom: 7px;">
                                        <a style="margin-left: 5px;color:blue;text-decoration: underline; font-size: 19px" onclick="EnableCaremarkFlgInbound();" href="https://www.sagawa-exp.co.jp/send/howto-packing/" target="_blank" class="comiket_detail_caremark_flg_link" >梱包ガイドラインについて(動画)</a>
                                    </div>
                                    <div>
                                        <a style="margin-left: 5px;color:blue;text-decoration: underline;font-size: 19px" onclick="EnableCaremarkFlgInbound();" href="https://www.sagawa-exp.co.jp/send/howto-packing/heavy.html" target="_blank" class="comiket_detail_caremark_flg_link" >重量物の梱包について(動画)</a>
                                    </div>
                                    <div style="margin-top: 5px;">
                                        <input id="comiket_detail_caremark_flg_inbound" <?php if (@$eve001Out->comiket_detail_caremark_flg_enable_inbound() != '1') { ?> disabled="disabled" <?php } ?>  class="class_comiket_detail_caremark_flg_inbound" style="transform:scale(1.3)" <?php if ($eve001Out->comiket_detail_caremark_flg_inbound() == '1') { ?> checked="checked" <?php } ?> name="comiket_detail_caremark_flg_inbound" type="checkbox"/>
                                        <label for="comiket_detail_caremark_flg_inbound" style = "font-size: 18px">梱包ガイドラインに従って発送致します</label>
                                        <br/><strong class="red" style = "font-size: 18px;">※ 外装異常が発生した際に、中身の確認をさせて頂きます。</strong>
                                    </div>
                                    <br/>
                                    <input id="comiket_detail_caremark_flg_enable_inbound" name="comiket_detail_caremark_flg_enable_inbound" type="hidden" value="<?php echo $eve001Out->comiket_detail_caremark_flg_enable_inbound(); ?>" />
                                    
                                </dd>
                            </dl>
                            <!-- GiapLN add  梱包ガイドライン2022.07.14 END --> 
                        </div>