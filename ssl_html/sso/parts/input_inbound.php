<?php /**********************************************************************************************************************/ ?>
                        <div class="input-inbound input-inbound-title">搬出</div>
<?php /**********************************************************************************************************************/ ?>
                        <div class="dl_block input-inbound comiket_block">


<!--                                <dl>
                        搬出
                                </dl>-->
                            <dl>
                                <dt id="comiket_detail_inbound_name">
                                    配達先名<span>必須</span>
                                </dt>
                                <dd<?php
                                    if (isset($e)
                                       && ($e->hasErrorForId('comiket_detail_inbound_name'))
                                    ) {
                                        echo ' class="form_error"';
                                    }
                                ?>>
                                <input class="" style="width:80%;" autocapitalize="off" inputmode="comiket_detail_inbound_name" name="comiket_detail_inbound_name" data-pattern="" placeholder="" type="text" value="<?php echo $eve001Out->comiket_detail_inbound_name() ?>" />
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
                                    配達先郵便番号<span>必須</span>
                                </dt>

                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_inbound_zip')) { echo ' class="form_error"'; } ?>>
                                    〒<input autocapitalize="off" class="w_70 number-only" maxlength="3" inputmode="numeric" name="comiket_detail_inbound_zip1" data-pattern="^\d+$" placeholder="例）136" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $eve001Out->comiket_detail_inbound_zip1();?>" />
                                    -
                                    <input autocapitalize="off" class="w_70 number-only" maxlength="4" inputmode="numeric" name="comiket_detail_inbound_zip2" data-pattern="^\d+$" placeholder="例）0082" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $eve001Out->comiket_detail_inbound_zip2();?>" />
                                    <input class="m110" name="inbound_adrs_search_btn" type="button" value="住所検索" />
                                        <span class="wb" style="font-size:12px;">
                                            &#12288;※郵便番号が不明な方は<a style="text-decoration: underline" target="_blank" href="http://www.post.japanpost.jp/zipcode/">こちら...</a>
                                    </span>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_inbound_pref">
                                    配達先都道府県<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_inbound_pref')) { echo ' class="form_error"'; } ?>>
                                    <select name="comiket_detail_inbound_pref_cd_sel">
                                        <option value="">選択してください</option>
<?php
        echo Sgmov_View_Sso_Input::_createPulldown($eve001Out->comiket_detail_inbound_pref_cds(), $eve001Out->comiket_detail_inbound_pref_lbls(), $eve001Out->comiket_detail_inbound_pref_cd_sel());
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
                                    配達先市区町村<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_inbound_address')) { echo ' class="form_error"'; } ?>>
                                    <input class="" style="width:80%;" maxlength="14" autocapitalize="off" name="comiket_detail_inbound_address" placeholder="例）江東区新砂" type="text" value="<?php echo $eve001Out->comiket_detail_inbound_address();?>" />
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_inbound_building">
                                    配達先番地・建物名・部屋番号<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_inbound_building')) { echo ' class="form_error"'; } ?>>
                                    <input class="" style="width:80%;" maxlength="30" autocapitalize="off" name="comiket_detail_inbound_building" placeholder="例）1-8-2" type="text" value="<?php echo $eve001Out->comiket_detail_inbound_building();?>" />
                                    <br/>
                                    <strong class="red">※集合住宅にお住まいの方は建物名や部屋番号も漏れなくご記入ください。<br>　記載漏れがあると配達に伺えない場合があります。</strong>
                                </dd>
                                
                            </dl>
                            <dl>
                                <dt id="comiket_detail_inbound_tel">
                                    配達先TEL<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_inbound_tel')) { echo ' class="form_error"'; } ?>>
                                    <input autocapitalize="off" class="number-p-only" style="width:50%;" maxlength="15" name="comiket_detail_inbound_tel" data-pattern="^[0-9-]+$" placeholder="例）075-1234-5678" type="text" value="<?php echo $eve001Out->comiket_detail_inbound_tel();?>" />
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_detail_inbound_collect_date">
                                    カウンターお預かり日<span>必須</span>
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
                                        <input type="hidden" id="hid_comiket-detail-inbound-collect-date-from"  name="hid_comiket-detail-inbound-collect-date-from" value="<?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_collect_fr_dt"]; ?>" />
                                        <input type="hidden" id="hid_comiket-detail-inbound-collect-date-to"    name="hid_comiket-detail-inbound-collect-date-to"   value="<?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_collect_to_dt"]; ?>" />
                                    <?php endif; ?>
                                        <div class="comiket_detail_inbound_collect_date_parts" style="display:<?php echo $displaySetting; ?>" style="display: none;">
                                            <select name="comiket_detail_inbound_collect_date_year_sel">
                                                <!--<option value="">年を選択</option>-->
                                                <!--<option value="2020">2020</option>-->
<?php                                           
                echo Sgmov_View_Sso_Input::_createPulldown($eve001Out->comiket_detail_inbound_collect_date_year_cds(), $eve001Out->comiket_detail_inbound_collect_date_year_lbls(), $eve001Out->comiket_detail_inbound_collect_date_year_sel());
?>
                                            </select>年
                                            <select name="comiket_detail_inbound_collect_date_month_sel">
                                                <!--<option value="">月を選択</option>-->
<?php
                echo Sgmov_View_Sso_Input::_createPulldown($eve001Out->comiket_detail_inbound_collect_date_month_cds(), $eve001Out->comiket_detail_inbound_collect_date_month_lbls(), $eve001Out->comiket_detail_inbound_collect_date_month_sel());
?>
                                            </select>月
                                            <select name="comiket_detail_inbound_collect_date_day_sel">
                                                <option value="">日を選択</option>
<?php
                echo Sgmov_View_Sso_Input::_createPulldown($eve001Out->comiket_detail_inbound_collect_date_day_cds(), $eve001Out->comiket_detail_inbound_collect_date_day_lbls(), $eve001Out->comiket_detail_inbound_collect_date_day_sel());
?>
                                            </select>日
                                            &nbsp;
                                            <span class="comiket_detail_inbound_collect_time_sel" >
                                                時間帯
                                                <select name="comiket_detail_inbound_collect_time_sel">
                                                    <option value="">時間帯を選択</option>
<?php
                echo Sgmov_View_Sso_Input::_createPulldown($eve001Out->comiket_detail_inbound_collect_time_cds(), $eve001Out->comiket_detail_inbound_collect_time_lbls(), $eve001Out->comiket_detail_inbound_collect_time_sel());
?>
                                                </select>
                                            </span>
                                        </div>
                                        <br>
                                        <strong class="red">※会場での宅配カウンター開設時間は　2/20　の　14:00　から　20:00　までです。</strong><br/>
<!--                                        <strong class="red">※会場での宅配カウンター開設時間はイベント期間中10：00から21：00までです。</strong><br/>
                                        <strong class="red">※当日飛脚クール便の発送受付は15：00までです。14：30までにお申込を完了してください。<br/>最終日のみ閉場時間まで受付いたします。</strong>-->
                                </dd>
                            </dl>
<!--                            <dl>
                                <dt>
                                    イベント参加日<span>必須</span>
                                </dt>
                                <dd>
                                    aaa
                                </dd>
                            </dl>-->
                            <dl class="comiket_detail_inbound_service_sel" style="display: none;">
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
                                    配達希望日<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_inbound_delivery_date')) { echo ' class="form_error"'; } ?>>
<!--                                    <p class="comiket-detail-inbound-delivery-date-fr-to" style="display:block;">
                                        <span class="comiket-detail-inbound-delivery-date-from"><?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_delivery_fr"]; ?></span>
                                        から
                                        <span class="comiket-detail-inbound-delivery-date-to"><?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_delivery_to"]; ?></span>
                                        まで選択できます。
                                    </p>-->
                                    <input type="hidden" id="hid_comiket-detail-inbound-delivery-date-from"     name="hid_comiket-detail-inbound-delivery-date-from"        value="<?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_delivery_fr_dt"]; ?>" />
                                    <input type="hidden" id="hid_comiket-detail-inbound-delivery-date-to"       name="hid_comiket-detail-inbound-delivery-date-to"          value="<?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_delivery_to_dt"]; ?>" />
                                    <input type="hidden" id="hid_comiket-detail-inbound-delivery-date-from_ori" name="hid_comiket-detail-inbound-delivery-date-from_ori"    value="<?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_delivery_fr_dt"]; ?>" />
                                    <input type="hidden" id="hid_comiket-detail-inbound-delivery-date-to_ori"   name="hid_comiket-detail-inbound-delivery-date-to_ori"      value="<?php echo @$dispItemInfo["eventsub_selected_data"]["inbound_delivery_to_dt"]; ?>" />
                                    <div class="comiket_detail_inbound_delivery_date_parts">
                                        
                                    <p>
                                        選択できる日は地域ごとに異なります。詳しくは
                                        <a href="/sso/images/term_del_inbound.png" target="_blank" style="color: #1774bc; text-decoration: underline;">
                                            こちら
                                        </a>
                                    </p>
                                        
                                        <select name="comiket_detail_inbound_delivery_date_year_sel">
                                            <!--<option value="">年を選択</option>-->
                                            <!--<option value="2020">2020</option>-->
                                            
<?php

            echo Sgmov_View_Sso_Input::_createPulldown($eve001Out->comiket_detail_inbound_delivery_date_year_cds(), $eve001Out->comiket_detail_inbound_delivery_date_year_lbls(), $eve001Out->comiket_detail_inbound_delivery_date_year_sel());
?>
                                        </select>年
                                        <select name="comiket_detail_inbound_delivery_date_month_sel">
                                            <!--<option value="">月を選択</option>-->
<?php
            echo Sgmov_View_Sso_Input::_createPulldown($eve001Out->comiket_detail_inbound_delivery_date_month_cds(), $eve001Out->comiket_detail_inbound_delivery_date_month_lbls(), $eve001Out->comiket_detail_inbound_delivery_date_month_sel());
?>
                                        </select>月
                                        <select name="comiket_detail_inbound_delivery_date_day_sel">
                                            <option value="">日を選択</option>
<?php
            echo Sgmov_View_Sso_Input::_createPulldown($eve001Out->comiket_detail_inbound_delivery_date_day_cds(), $eve001Out->comiket_detail_inbound_delivery_date_day_lbls(), $eve001Out->comiket_detail_inbound_delivery_date_day_sel());
?>
                                        </select>日
                                        &nbsp;
                                        <span class="comiket_detail_inbound_delivery_time_sel" >
                                        時間帯
                                            <select name="comiket_detail_inbound_delivery_time_sel">
                                                <option value="">時間帯を選択</option>
<?php
            echo Sgmov_View_Sso_Input::_createPulldown($eve001Out->comiket_detail_inbound_delivery_time_cds(), $eve001Out->comiket_detail_inbound_delivery_time_lbls(), $eve001Out->comiket_detail_inbound_delivery_time_sel());
?>
                                            </select>
                                        </span>
                                        <br/>
                                        <br/>
<!--                                        <strong class="red">※ 天災、交通状況の影響により、希望に添えない可能性があります。</strong>-->
                                    </div>
                                    <div>
                                        
                                        <strong class="red">※お預かり日の翌々日～預かり日の7日目まで指定可能（北海道は4日後から、九州、東北、茨城、栃木は3日後から）<br>　範囲外を指定した場合、申込エラーとなり申込完了できません。</strong>
                                    </div>
                                </dd>
                            </dl>

                            <dl class="comiket_detail_inbound_binshu_kbn_sel">
                                <dt id="comiket_detail_inbound_binshu_kbn_sel">
                                    便種選択<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_inbound_binshu_kbn_sel')) { echo ' class="form_error"'; } ?>>
                                    <label class="radio-label-binshu" for="comiket_detail_inbound_binshu_kbn_sel1" style="height: 10px;white-space: nowrap;">
                                        <input id="comiket_detail_inbound_binshu_kbn_sel1" name="comiket_detail_inbound_binshu_kbn_sel" type="radio" value="0" style="vertical-align: middle;"
                                               <?php if ($eve001Out->comiket_detail_inbound_binshu_kbn_sel() == '0') : ?>checked="checked"<?php endif; ?>>
                                        飛脚宅配便 </label>

                                    <label class="radio-label-binshu" for="comiket_detail_inbound_binshu_kbn_sel2" style="white-space: nowrap;vertical-align: middle;">
                                        <input id="comiket_detail_inbound_binshu_kbn_sel2" name="comiket_detail_inbound_binshu_kbn_sel" type="radio" value="1" style="vertical-align: middle;"
                                               <?php if ($eve001Out->comiket_detail_inbound_binshu_kbn_sel() == '1') : ?>checked="checked"<?php endif; ?>>
                                        飛脚クール便（冷蔵）</label>

                                    <label class="radio-label-binshu" for="comiket_detail_inbound_binshu_kbn_sel3" style="white-space: nowrap;vertical-align: middle;">
                                        <input id="comiket_detail_inbound_binshu_kbn_sel3" name="comiket_detail_inbound_binshu_kbn_sel" type="radio" value="2" style="vertical-align: middle;"
                                               <?php if ($eve001Out->comiket_detail_inbound_binshu_kbn_sel() == '2') : ?>checked="checked"<?php endif; ?>>
                                        飛脚クール便（冷凍）</label>
                                    <br>
                                    <strong class="red">
                                        ※飛脚宅配便・飛脚クール便（冷蔵）・飛脚クール便（冷凍）は同時に選択できません。<br>
                                        サービスごとにお申込みをお願いいたします。
                                    </strong>
                                </dd>
                            </dl>

                            <dl class="service-inbound-item" service-id="1">
                                <dt id="comiket_box_inbound_num_ary">
                                    宅配数量<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_box_inbound_num_ary')) { echo ' class="form_error"'; } ?>>
                                    <div class="comiket-box-inbound-num comiket-box-inbound-num-dmy"> <!-- 個人・法人 どちらでも -->
                                        <?php // if($eve001Out->comiket_div() == Sgmov_View_Sso_Common::COMIKET_DEV_BUSINESS) : ?>
                                            <table>
                                                <tr>
                                                    <td>
                                                        
                                                        
                                                        <?php if ($eve001Out->comiket_detail_inbound_binshu_kbn_sel() != '' 
                                                                && $eve001Out->comiket_detail_inbound_binshu_kbn_sel() != null) : ?>
                                                            <table>
                                                            <?php foreach($dispItemInfo['inbound_box_lbls'] as $key => $val) : ?>
                                                                <tr>
                                                                <!--<div style="margin-bottom: 10px;">-->
                                                                    <td class='comiket_box_item_name'>
                                                                        <?php echo empty($val["name"]) ? "" : $val["name"]; ?>&nbsp;
                                                                    </td>
                                                                    <td class='comiket_box_item_value'>
                                                                        <input autocapitalize="off" class="number-only comiket_box_item_value_input" style="" maxlength="2" inputmode="numeric" name="comiket_box_inbound_num_ary[<?php echo $val["id"]; ?>]" data-pattern="^\d+$" placeholder="例）1" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $eve001Out->comiket_box_inbound_num_ary($val["id"]);?>" />個
                                                                        &nbsp;
                                                                    </td>
                                                                <!--</div>-->
                                                                </tr>
                                                            <?php endforeach; ?>
                                                            </table>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="dispSeigyoPC" style='vertical-align: middle;text-align: right;'>
                                                        <?php if($eve001Out->comiket_div() == Sgmov_View_Sso_Common::COMIKET_DEV_INDIVIDUA) : // 個人 ?>
                                                            <img src='/sso/images/about_boxsize.png' width='100%'/>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        <?php // endif; ?>
                                    </div>
                                    <?php if($eve001Out->comiket_div() == Sgmov_View_Sso_Common::COMIKET_DEV_INDIVIDUA) : // 個人 ?>
                                    <div class="dispSeigyoSP" style="margin-top: 1em;">
                                        <img src='/sso/images/about_boxsize.png' width='250px' style='margin-top: 1em;' />
                                    </div>
                                    <?php else: ?>
                                    <!--<img src='/sso/images/about_boxsize.png' width='100%'/>-->
                                    <?php endif; ?>
                                    <br/>
                                    <div class="inbound_example_boxsize example_boxsize">
<!--                                        <strong class="red msg-hikyaku_coolbin">※ 飛脚クール便を発送される方は、必ずご利用予定日の15時までに宅配カウンターへお荷物をお持ち込みください。15時を過ぎますと、お引受けができなくなります。</strong>
                                        <br class="msg-hikyaku_coolbin">
                                        <br class="msg-hikyaku_coolbin">-->
                                        <strong class="red msg-hikyaku_normalbin">※ 最大4種類(サイズ)までご指定頂けます</strong><br class="msg-hikyaku_normalbin"><br class="msg-hikyaku_normalbin">
                                        <strong class="red msg-hikyaku_normalbin">※ 5種類以上をご希望の場合は、4種類ずつに分けて、複数のお申込みをお願い致します</strong><br class="msg-hikyaku_normalbin"><br class="msg-hikyaku_normalbin">
                                        <strong class="red">※ 重量はお荷物１つにつき３０Kｇが上限となります。</strong><br/><br/>
                                        <strong class="red">※ ガラス・鏡などの「割れ物」は、集荷時にお申し出ください。<br>　 お申し出がない場合、損害補償の対象外となります。</strong><br/><br/>
                                        <strong class="red">※ １申込み４０個を超える場合は、ＳＧムービング/シーフードショー大阪・アグリフードＥＸＰＯ大阪係り ０３－５５３４－１０８０　もしくはカウンタースタッフへお問い合わせください。</strong><br/><br/>
                                        <a href="/sso/pdf/example/example_box_size.pdf" target="_blank" style="color:blue;">目安表</a>
                                    </div>
                                </dd>
                            </dl>
                            <dl class="service-inbound-item" service-id="2">
                                <dt id="comiket_cargo_inbound_num_ary">
                                    カーゴ数量<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_cargo_inbound_num_ary')) { echo ' class="form_error"'; } ?>>

                                    <div class="comiket-cargo-inbound-num" div-id="<?php echo Sgmov_View_Sso_Common::COMIKET_DEV_INDIVIDUA; ?>"> <!-- 個人 -->
                                    </div>
                                    <div class="comiket-cargo-inbound-num" div-id="<?php echo Sgmov_View_Sso_Common::COMIKET_DEV_BUSINESS; ?>"> <!-- 法人 -->
                                    </div>
                                    <div class="">
                                        <input autocapitalize="off" class="number-only boxWid" maxlength="2" inputmode="numeric" name="comiket_cargo_inbound_num_sel" data-pattern="^\d+$" placeholder="例）1" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $eve001Out->comiket_cargo_inbound_num_sel();?>" />台
<!--                                            <select name="comiket_cargo_inbound_num_sel">
                                                <option value="">カーゴ数量を選択</option>
<?php
                                        echo Sgmov_View_Sso_Input::_createPulldown($eve001Out->comiket_cargo_inbound_num_cds(), $eve001Out->comiket_cargo_inbound_num_lbls(), $eve001Out->comiket_cargo_inbound_num_sel());
?>
                                            </select>台-->
                                    </div>
                                </dd>
                            </dl>
                            <dl class="service-inbound-item" service-id="3">
                                <dt id="comiket_charter_inbound_num_ary">
                                    台数貸切<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_charter_inbound_num_ary')) { echo ' class="form_error"'; } ?>>

                                    <div class="comiket-charter-inbound-num" div-id="<?php echo Sgmov_View_Sso_Common::COMIKET_DEV_INDIVIDUA; ?>"> <!-- 個人 -->
                                        <input autocapitalize="off" class="" style="width:10%;" maxlength="3" inputmode="numeric" name="comiket_charter_inbound_num_ary[0]" data-pattern="^\d+$" placeholder="例）7" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $eve001Out->comiket_charter_inbound_num_ary("0");?>" />台
                                    </div>
                                    <div class="comiket-charter-inbound-num" div-id="<?php echo Sgmov_View_Sso_Common::COMIKET_DEV_BUSINESS; ?>"> <!-- 法人 -->
                                        <table>
                                        <?php foreach($dispItemInfo['charter_lbls'] as $key => $val) : ?>
                                            <tr>
                                            <!--<div style="margin-bottom: 10px;">-->
                                                <td class='comiket_charter_item_name'><?php echo $val["name"]; ?>&nbsp;</td>
                                                <td class='comiket_charter_item_value'>
                                                    <input autocapitalize="off" class="number-only boxWid" maxlength="2" inputmode="numeric" name="comiket_charter_inbound_num_ary[<?php echo $val["id"]; ?>]" data-pattern="^\d+$" placeholder="例）1" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $eve001Out->comiket_charter_inbound_num_ary($val["id"]);?>" />台
                                                    &nbsp;
                                                </td>
                                            <!--</div>-->
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
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_detail_inbound_note')) { echo ' class="form_error"'; } ?>>
                                    <input autocapitalize="off" class="" style="width:50%;" maxlength="16" inputmode="text" name="comiket_detail_inbound_note1" placeholder="" type="text" value="<?php echo $eve001Out->comiket_detail_inbound_note1();?>" /><br/>
                                </dd>
                            </dl>
                        </div>