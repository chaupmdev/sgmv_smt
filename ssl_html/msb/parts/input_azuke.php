                            <!-- ***************************************************************************************************************************************** -->
                            <!-- 荷物預入取出 Start -->
                            <!-- ***************************************************************************************************************************************** -->
                            <div class="input-azuke input-outbound-title">手荷物預かりサービス </div>
                            <div class="dl_block input-azuke comiket_block">
                            <dl>
                                <dt id="comiket_detail_azukari_kaisu_type">
                                    取出回数<span>必須</span>
                                </dt>
                                <dd<?php
                                    if (isset($e)
                                       && ($e->hasErrorForId('comiket_detail_azukari_kaisu_type_sel'))
                                    ) {
                                        echo ' class="form_error"';
                                    }
                                ?>>
                                    <div class="comiket_detail_azukari_kaisu_type-dd">
                                        <?php foreach($dispItemInfo['comiket_detail_azukari_kaisu_type_lbls'] as $key => $val) : ?>
                                            <label class="radio-label comiket_detail_type_sel-label<?php echo $key; ?>" for="comiket_detail_azukari_kaisu_type_sel<?php echo $key; ?>">
                                                <input <?php if ($eve001Out->comiket_detail_azukari_kaisu_type_sel() == $key) echo ' checked="checked"'; ?> id="comiket_detail_azukari_kaisu_type_sel" class="comiket_detail_azukari_kaisu_type_sel" name="comiket_detail_azukari_kaisu_type_sel" type="radio" value="<?php echo $key; ?>" />
                                                <?php echo $val; ?>
                                            </label>
                                            <br />
                                        <?php endforeach; ?>
                                    </div>
                                    &nbsp;&nbsp;<strong class="red">※当日に「何度でも」手荷物の出し入れが可能です。</strong>
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
                            <dl class="toridashi_outbount" style="display: none;">
                                <dt id="comiket_detail_zip">
                                    お届け先郵便番号<span>必須</span>
                                </dt>
                                <dd<?php
                                    if (isset($e)
                                       && ($e->hasErrorForId('comiket_detail_zip'))
                                    ) {
                                        echo ' class="form_error"';
                                    }
                                ?>>
                                    〒<input autocapitalize="off" class="w_70 number-only" maxlength="3" inputmode="numeric" name="comiket_detail_zip1" data-pattern="^\d+$" placeholder="例）136" type="text" value="<?php echo $eve001Out->comiket_detail_zip1() ?>" />
                                    -
                                    <input autocapitalize="off" class="w_70 number-only" maxlength="4" inputmode="numeric" name="comiket_detail_zip2" data-pattern="^\d+$" placeholder="例）0082" type="text" value="<?php echo $eve001Out->comiket_detail_zip2() ?>" />
                                    <input class="m110" name="comiket_detail_adrs_search_btn" type="button" value="住所検索" />
                                        <span class="wb" style="font-size:12px;">
                                            &#12288;※郵便番号が不明な方は<a style="text-decoration: underline" target="_blank" href="http://www.post.japanpost.jp/zipcode/">こちら...</a>
                                    </span>
                                </dd>
                            </dl>
                            <dl class="toridashi_outbount" style="display: none;">
                                <dt id="comiket_detail_pref">
                                    お届け先都道府県<span>必須</span>
                                </dt>
                                <dd<?php
                                    if (isset($e)
                                       && ($e->hasErrorForId('comiket_detail_pref'))
                                    ) {
                                        echo ' class="form_error"';
                                    }
                                ?>>
                                    <select name="comiket_detail_pref_cd_sel">
                                        <option value="">選択してください</option>
                                        <?php
                                            echo Sgmov_View_Msb_Input::_createPulldown($eve001Out->comiket_detail_pref_cds(), $eve001Out->comiket_detail_pref_lbls(), $eve001Out->comiket_detail_pref_cd_sel());
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
                            <dl class="toridashi_outbount" style="display: none;">
                                <dt id="comiket_detail_address">
                                    お届け先市区町村<span>必須</span>
                                </dt>
                                <dd<?php
                                    if (isset($e)
                                       && ($e->hasErrorForId('comiket_detail_address'))
                                    ) {
                                        echo ' class="form_error"';
                                    }
                                ?>>
                                    <input class="" style="width:80%;" maxlength="14" autocapitalize="off" name="comiket_detail_address" placeholder="例）江東区新砂" type="text" value="<?php echo $eve001Out->comiket_detail_address();?>" />
                                </dd>
                            </dl>
                            <dl class="toridashi_outbount" style="display: none;">
                                <dt id="comiket_detail_building">
                                    お届け先番地・建物名
                                </dt>
                                <dd<?php
                                    if (isset($e)
                                       && ($e->hasErrorForId('comiket_detail_building'))
                                    ) {
                                        echo ' class="form_error"';
                                    }
                                ?>>
                                    <input class="" style="width:80%;" maxlength="30" autocapitalize="off" name="comiket_detail_building" placeholder="例）1-8-2" type="text" value="<?php echo $eve001Out->comiket_detail_building();?>" />
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
                            <dl class="class_comiket_detail_collect_date" style="">
                                <dt id="comiket_detail_collect_date">
                                    お預かり日<span>必須</span>
                                </dt>
                                <dd class="comiket_detail_collect_date_input_part" <?php if (isset($e) && $e->hasErrorForId('comiket_detail_collect_date')) { echo ' class="form_error"'; } ?>>
                                    <!-- <p class="comiket-detail-outbound-collect-date-fr-to">2021年08月21日（月）&nbsp;から&nbsp;2021年08月22日（金）&nbsp;まで選択できます。</p> -->
                                    
                                    <p class="comiket-detail-outbound-collect-date-fr-to_test">2021年08月21日（土）&nbsp;から&nbsp;2021年08月22日（日）&nbsp;まで、&nbsp;2021年08月28日（土）選択できます。</p>

                                    <input type="hidden" id="hid_comiket-detail-outbound-collect-date-from" name="hid_comiket-detail-outbound-collect-date-from" value="2019-06-10">
                                    <input type="hidden" id="hid_comiket-detail-outbound-collect-date-to" name="hid_comiket-detail-outbound-collect-date-to" value="2019-06-14">
                                    <input type="hidden" id="hid_comiket-detail-outbound-collect-date-from_ori" name="hid_comiket-detail-outbound-collect-date-from_ori" value="2019-06-10">
                                    <input type="hidden" id="hid_comiket-detail-outbound-collect-date-to_ori" name="hid_comiket-detail-outbound-collect-date-to_ori" value="2019-06-14">
                                    <div class="comiket_detail_collect_date_parts" style="">
                                        <span class="comiket_detail_collect_date">
                                            <select name="comiket_detail_collect_date_year_sel">
                                                <option value="2020" selected="selected">2021</option>
                                            </select>年
                                        </span>
                                        <span class="comiket_detail_collect_date">
                                            <select name="comiket_detail_collect_date_month_sel">
                                                <option value="08" selected="selected">08</option>
                                            </select>月
                                        </span>
                                        <span class="comiket_detail_collect_date">
                                            <select name="comiket_detail_collect_date_day_sel">
                                                <option value="" selected="selected">日を選択</option>
                                                <option value="21">21</option>
                                                <option value="22">22</option>
                                                <option value="28">28</option>
                                            </select>日
                                        </span>
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
                                    <dt id="comiket_box_num_ary">
                                        手荷物数量<span>必須</span>
                                    </dt>
                                    <dd>
                                        <div class="comiket-box-num comiket-box-num-dmy">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table style="width: min-width;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="comiket_box_item_name">スーツケース　<br/> (140サイズ）&nbsp;</td>
                                                                        <td class="comiket_box_item_value">
                                                                            <input autocapitalize="none" class="number-only comiket_box_item_value_input" maxlength="2" inputmode="numeric" name="comiket_box_num_ary[3436]" data-pattern="^d+$" placeholder="例）1" type="text" value="">個
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="comiket_box_item_name">ボストンバック（大）<br/>(140サイズ)&nbsp;</td>
                                                                        <td class="comiket_box_item_value">
                                                                            <input autocapitalize="none" class="number-only comiket_box_item_value_input" maxlength="2" inputmode="numeric" name="comiket_box_num_ary[3437]" data-pattern="^d+$" placeholder="例）1" type="text" value="">個
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="comiket_box_item_name">ボストンバック（中）<br/>(100サイズ)&nbsp;</td>
                                                                        <td class="comiket_box_item_value">
                                                                            <input autocapitalize="none" class="number-only comiket_box_item_value_input" maxlength="2" inputmode="numeric" name="comiket_box_num_ary[3438]" data-pattern="^d+$" placeholder="例）1" type="text" value="">個
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="comiket_box_item_name">ボストンバック（小）<br/>(80サイズ)&nbsp;</td>
                                                                        <td class="comiket_box_item_value">
                                                                            <input autocapitalize="none" class="number-only comiket_box_item_value_input" maxlength="2" inputmode="numeric" name="comiket_box_num_ary[3439]" data-pattern="^d+$" placeholder="例）1" type="text" value="">個
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                        <td class="dispSeigyoPCTr" style="vertical-align: middle;text-align: right;width: 47%;">
                                                            <img class="dispSeigyoPC" src="/msb/images/about_boxsize.png" width="100%">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div class="dispSeigyoSP">
                                                <img src="/msb/images/about_boxsize.png" style="margin-top: 1em;" width="250px">
                                            </div>
                                        </div>
                                        <br>
                                        <!-- <div class="outbound_example_boxsize example_boxsize" style="">
                                            <strong class="red">※ 最大4種類(サイズ)までご指定頂けます</strong><br><br>
                                            <strong class="red">※ 5種類以上をご希望の場合は、4種類ずつに分けて、複数のお申込みをお願い致します</strong><br><br>
                                            <a href="https://sagawa-mov-test03.media-tec.jp/dsn/pdf/example/example_box_size.pdf" target="_blank" style="color:blue;">目安表</a>
                                        </div> -->
                                    </dd>
                                </dl>
                            <dl>
                                <dt id="comiket_detail_note">
                                    備考
                                </dt>
                                <dd>
                                    <input autocapitalize="off" class="" style="width:50%;" maxlength="16" inputmode="text" name="comiket_detail_note1" placeholder="" type="text" value=""><br>
                                </dd>
                            </dl>
                            </div>
                            <style type="text/css">
                                .comiket_box_item_name br {
                                    display: none;
                                }

                                .comiket_box_item_value_input{
                                    width: 75% !important;
                                }

                                @media only screen and (max-width: 980px) {
                                    .comiket_box_item_name br {
                                        display: inline;
                                    }

                                    .comiket_box_item_value_input {
                                        width: 50% !important;
                                    }
                                }
                            </style>
                            
                            <!-- ***************************************************************************************************************************************** -->
                            <!-- 荷物預入取出 End -->
                            <!-- ***************************************************************************************************************************************** -->