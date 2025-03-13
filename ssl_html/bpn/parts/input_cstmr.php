                    <input id="comiket_customer_kbn_sel1" class="comiket_customer_kbn" name="comiket_customer_kbn_sel" type="hidden" value="0">
                    <div class="dl_block comiket_block">
                        <dl>
                            <dt id="event_sel">出展イベント</dt>
                            <dd>
                                <span class="">
                                    <?php
                                        $eventName = Sgmov_View_Bpn_Input::_getLabelSelectPulldownData($bpn001Out->event_cds(), $bpn001Out->event_lbls(), $bpn001Out->event_cd_sel());
                                    echo $eventName;
                                    ?>&nbsp;&nbsp;
                                    <?php
                                        $eventsubName = Sgmov_View_Bpn_Input::_getLabelSelectPulldownData($bpn001Out->eventsub_cds(), $bpn001Out->eventsub_lbls(), $bpn001Out->eventsub_cd_sel());
                                        echo $eventsubName;
                                    ?>
                                </span>
                                <input type="hidden" id="hid_timezone_flg" name="hid_timezone_flg" value="0">
                                <input type="hidden" id="event_sel" name="event_sel" value="<?php echo $bpn001Out->event_cd_sel(); ?>">
                                <input type="hidden" id="eventsub_sel" name="eventsub_sel" value="<?php echo $bpn001Out->eventsub_cd_sel(); ?>"><br>
                                <!-- <img src="/gmm/images/GM_rogo3.gif" width="40%"> -->
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
                            <input class="" style="width:80%;" autocapitalize="off" inputmode="eventsub_address" name="eventsub_address" data-pattern="" placeholder="" type="hidden" value="<?php echo @$selectedEventData["venue"]; ?>" />
                            </dd>
                        </dl>
                        <dl>
                            <dt id="eventsub_term">
                                <?php $lbl = "期間"; 
                                    if($bpn001Out->event_cd_sel() == "301"):
                                        $lbl = "会期"; 
                                    endif;
                                    echo $lbl;
                                ?>
                            </dt>
                            <dd<?php
                                if (isset($e)
                                   && ($e->hasErrorForId('eventsub_term_fr') || $e->hasErrorForId('evensubt_term_to'))
                                ) {
                                    echo ' class="form_error"';
                                }
                            ?>>
                            <?php if($bpn001Out->eventsub_term_fr_nm() == $bpn001Out->eventsub_term_to_nm()):?>
                                <span class="event-term_fr-lbl"><?php echo $bpn001Out->eventsub_term_fr_nm(); ?></span>
                            <?php else: ?>
                                <span class="event-term_fr-lbl"><?php echo $bpn001Out->eventsub_term_fr_nm(); ?></span>
                                <span class="event-term_fr-str">&nbsp;から&nbsp;</span>
                                <span class="event-term_to-lbl"><?php echo $bpn001Out->eventsub_term_to_nm(); ?></span>
                            <?php endif; ?>

                            <input class="" style="width:100px;" autocapitalize="off" inputmode="eventsub_term_fr" name="eventsub_term_fr" data-pattern="" placeholder="" type="hidden" value="<?php echo $bpn001Out->eventsub_term_fr(); ?>" />
                            <input class="" style="width:100px;" autocapitalize="off" inputmode="eventsub_term_to" name="eventsub_term_to" data-pattern="" placeholder="" type="hidden" value="<?php echo $bpn001Out->eventsub_term_to(); ?>" />
                            </dd>
                        </dl>
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
                                <?php foreach($dispItemInfo['comiket_div_lbls'] as $key => $val) : ?>
                                    <label class="radio-label comiket_div<?php echo $key; ?>" for="comiket_div<?php echo $key; ?>" style="display:none;">
                                        <input<?php if ($bpn001Out->comiket_div() == $key) echo ' checked="checked"'; ?> id="comiket_div<?php echo $key; ?>" name="comiket_div" type="radio" value="<?php echo $key; ?>" />
                                        <?php echo $val; ?>
                                    </label>
                                    <br />
                                <?php endforeach; ?>
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
                            <input class="number-only" style="width: 120px;" maxlength="11" autocapitalize="off" inputmode="comiket_customer_cd" name="comiket_customer_cd" data-pattern="^\d+$" placeholder="" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $bpn001Out->comiket_customer_cd() ?>" />
                            <input class="m110" style="width:60px;" name="customer_search_btn" type="button" value="検索" />
                            <br/><strong class="comiket_customer_cd_message red"></strong>
                            <br/>
                            <div style="width:80%;">
                            <strong class="red">
                                ※佐川急便をご利用のお客様はお取引先コード（お客様コード）を入力して頂く事で売掛運賃となります。<br/><br/>
                                ※お取引先コード（お客様コード）はお持ちの商品に記載されております。<br/><br/>
                                ※自社のお取引先コード（お客様コード）が不明な場合は最寄りの佐川急便の営業所にお問い合わせください。<br/><br/>
                                <a href="http://www2.sagawa-exp.co.jp/send/branch_search/moyori/area/" target="_blank">営業所・サービスセンター・取次店検索はこちら</a>
                            </strong>
                            </div>
                            </dd>
                        </dl>
                        <dl class="office-name" style="display: none;">
                            <dt id="office_name">
                                お申込者<span>必須</span>
                            </dt>
                            <dd<?php
                                if (isset($e)
                                   && ($e->hasErrorForId('office_name'))
                                ) {
                                    echo ' class="form_error"';
                                }
                            ?>>
                            <span class="office_name-lbl"><?php echo $bpn001Out->office_name();?></span>&nbsp;
                            <input class="" style="width:60%;" maxlength="16" autocapitalize="off" inputmode="office_name" name="office_name" data-pattern="" placeholder="" type="text" value="<?php echo $bpn001Out->office_name() ?>" />
                            </dd>
                        </dl>
                        <dl class="office-name" style="display: none;">
                            <dt id="office_name">
                                お申込者<span>必須</span>
                            </dt>
                            <dd<?php
                                if (isset($e)
                                   && ($e->hasErrorForId('office_name'))
                                ) {
                                    echo ' class="form_error"';
                                }
                            ?>>
                            &nbsp;
                            <input class="" style="width:60%;" maxlength="16" autocapitalize="off" inputmode="office_name" name="office_name" data-pattern="" placeholder="" type="text" value="<?php echo $bpn001Out->office_name() ?>" />
                            </dd>
                        </dl>
                        <dl class="comiket-personal-name-seimei">
                            <dt id="comiket_personal_name-seimei">
                                お申込者<span>必須</span>
                            </dt>
                            <dd<?php
                                if (isset($e)
                                   && ($e->hasErrorForId('comiket_personal_name-seimei'))
                                ) {
                                    echo ' class="form_error"';
                                }
                            ?>>
                            姓<input class="" style="width:30%;" maxlength="8" autocapitalize="off" inputmode="comiket_personal_name_sei" name="comiket_personal_name_sei" data-pattern="" placeholder="例）佐川" type="text" value="<?php echo $bpn001Out->comiket_personal_name_sei() ?>" />
                            名<input class="" style="width:30%;" maxlength="8" autocapitalize="off" inputmode="comiket_personal_name_mei" name="comiket_personal_name_mei" data-pattern="" placeholder="例）花子" type="text" value="<?php echo $bpn001Out->comiket_personal_name_mei() ?>" />
                            <div class="disp_comiket disp_gooutcamp">
                                <br/>
                                <strong class="red">※ 法人の場合は、姓のみ入力してください。</strong>
                            </div>

                            </dd>
                        </dl>
                        <dl>
                            <dt id="comiket_zip">
                                郵便番号<span>必須</span>
                            </dt>

                            <dd<?php if (isset($e) && $e->hasErrorForId('comiket_zip')) { echo ' class="form_error"'; } ?>>
                                <span class="zip_mark1">〒</span><span class="comiket_zip1-lbl"><?php //echo $bpn001Out->comiket_zip1();?></span>
                                <input autocapitalize="off" class="w_70 number-only" maxlength="3" inputmode="numeric" name="comiket_zip1" data-pattern="^\d+$" placeholder="例）136" type="text" value="<?php echo $bpn001Out->comiket_zip1();?>" />
                                <span class="zip_mark2">-</span>
                                <span class="comiket_zip1-str">
                                </span>
                                <span class="comiket_zip2-lbl"><?php //echo $bpn001Out->comiket_zip2();?></span>
                                <input autocapitalize="off" class="w_70 number-only" maxlength="4" inputmode="numeric" name="comiket_zip2" data-pattern="^\d+$" placeholder="例）0082" type="text" value="<?php echo $bpn001Out->comiket_zip2();?>" />
                                <input class="m110" name="adrs_search_btn" type="button" value="住所検索" />
                                <span style="font-size:12px;display: inline-block !important;" class="forget-address-discription">
                                    &#12288;※郵便番号が不明な方は<a style="text-decoration: underline" target="_blank" href="http://www.post.japanpost.jp/zipcode/">こちら...</a>
                                </span>
                            </dd>
                        </dl>
                        <dl>
                            <dt id="comiket_pref">
                                都道府県<span>必須</span>
                            </dt>
                            <dd<?php if (isset($e) && $e->hasErrorForId('comiket_pref')) { echo ' class="form_error"'; } ?>>
                                <span class="comiket_pref_nm-lbl"><?php //echo $bpn001Out->comiket_pref_nm();?></span>
                                <select name="comiket_pref_cd_sel">
                                    <option value="">選択してください</option>
<?php
        echo Sgmov_View_Bpn_Input::_createPulldown($bpn001Out->comiket_pref_cds(), $bpn001Out->comiket_pref_lbls(), $bpn001Out->comiket_pref_cd_sel());
?>
                                </select>
                                
                                <div class="tenimotsu-only" style="display: none;">
                                    <br>
                                    <br>
                                    <strong class="red">
                                        ※沖縄の場合は航空運賃が適用されます。<br><br>
                                        ※郡部・離島・一部地域で中継料が発生する場合は、お荷物のお取り扱いが出来ない場合がございます。
                                        <br/>
                                        <a href="https://sagawa-mov-test03.media-tec.jp/pdf/kokubin_goriyoujyono_tyui.pdf" target="_blank" style="color: #1774bc; text-decoration: underline;">航空便ご利用上の注意はこちら</a>
                                        <br/>
                                        <a href="https://sagawa-mov-test03.media-tec.jp/pdf/kokutakuhaibinnado_unso_yakkan.pdf" target="_blank" style="color: #1774bc; text-decoration: underline;">航空宅配便等個建運送約款はこちら</a>
                                    </strong>
                                </div>
                            </dd>
                        </dl>
                        <dl>
                            <dt id="comiket_address">
                                市区町村<span>必須</span>
                            </dt>
                            <dd<?php if (isset($e) && $e->hasErrorForId('comiket_address')) { echo ' class="form_error"'; } ?>>
                                <span class="comiket_address-lbl"><?php //echo $bpn001Out->comiket_address();?></span>
                                <input name="comiket_address" style="width:80%;" maxlength="14" placeholder="例）江東区新砂" type="text" value="<?php echo $bpn001Out->comiket_address();?>" />
                            </dd>
                        </dl>
                        <dl>
                            <dt id="comiket_building">
                                番地・建物名・部屋番号<span>必須</span>
                            </dt>
                            <dd<?php if (isset($e) && $e->hasErrorForId('comiket_building')) { echo ' class="form_error"'; } ?>>
                                <span class="comiket_building-lbl"><?php //echo $bpn001Out->comiket_building();?></span>
                                <input name="comiket_building" style="width:80%;" maxlength="30" type="text" value="<?php echo $bpn001Out->comiket_building();?>" />
                            </dd>
                        </dl>
                        <dl>
                            <dt id="comiket_tel">
                                電話番号<span>必須</span>
                            </dt>
                            <dd<?php if (isset($e) && $e->hasErrorForId('comiket_tel')) { echo ' class="form_error"'; } ?>>
                                 <span class="comiket_tel-lbl"><?php //echo $bpn001Out->comiket_tel();?></span>
                                <input name="comiket_tel" class="number-p-only" type="text" maxlength="15" placeholder="例）080-1111-2222" data-pattern="^[0-9-]+$" value="<?php echo $bpn001Out->comiket_tel();?>" />
                                <div class="disp_comiket disp_gooutcamp">
                                    <br/>
                                    <strong class="red">※ イベント当日に、現地で連絡がとれる番号を入力してください。</strong>
                                </div>
                            </dd>
                        </dl>
                        <dl>
                            <dt id="comiket_mail">
                                メールアドレス<span>必須</span>
                            </dt>
                            <dd<?php if (isset($e) && $e->hasErrorForId('comiket_mail')) { echo ' class="form_error"'; } ?>>
                                <input class="w_220" maxlength="100" autocapitalize="off" inputmode="email" name="comiket_mail" data-pattern="^[!-~]+$" placeholder="例）ringo@sagawa.com" type="<?php echo $inputTypeEmail; ?>" value="<?php echo $bpn001Out->comiket_mail();?>" />
                                <br class="sp_only" /><br class="sp_only" />
                                <strong class="red">※申込完了の際に申込完了メールを送付させていただきますので、間違いのないように注意してご入力ください。</strong>
                                <p class="red">
                                    ※必ず「sagawa-mov.co.jp」からのメールを受信する設定にしてください。
                                    <br />詳しくは
                                    <a href="#bounce_mail">こちら</a>
                                </p>
                                <p class="red">
                                    ※@以降のドメインの確認お願いします。<br />
                                    例：@～.ne.jp、@～.co.jp、Gmailなら@gmail.com等
                                </p>
                            </dd>
                        </dl>
                        <dl>
                            <dt id="comiket_mail_retype">
                                メールアドレス確認<span>必須</span>
                            </dt>
                            <dd<?php if (isset($e) && $e->hasErrorForId('comiket_mail_retype')) { echo ' class="form_error"'; } ?>>
                                <input class="w_220" maxlength="100" autocapitalize="off" inputmode="email" name="comiket_mail_retype" data-pattern="^[!-~]+$" placeholder="例）ringo@sagawa.com" type="<?php echo $inputTypeEmail; ?>" value="<?php echo $bpn001Out->comiket_mail_retype();?>" oncopy="return false" onpaste="return false" oncontextmenu="return false"/>
                                <br/>
                                <br/>
                                <strong class="red">
                                    ※確認のため、再入力をお願いいたします。
                                </strong>
                            </dd>
                        </dl>
                        <?php if( ($bpn001Out->eventsub_cd_sel() == "303" || $bpn001Out->eventsub_cd_sel() == "26") && $bpn001Out->shohin_pattern() == "1"): ?>
                            <dl>
                                <dt id="comiket_detail_collect_date">
                                    商品引き渡し日<span>必須</span>
                                </dt>
                                <dd 
                                    <?php 
                                        if (isset($e) && $e->hasErrorForId('comiket_detail_collect_date')) { echo ' class="form_error"'; } 
                                    ?>
                                >
                                        <input type="hidden" id="hid_comiket-detail-collect-date-from"  name="hid_comiket-detail-collect-date-from" value="<?php echo @$dispItemInfo["eventsub_selected_data"]["term_fr"]; ?>" />
                                        <input type="hidden" id="hid_comiket-detail-collect-date-to"    name="hid_comiket-detail-collect-date-to"   value="<?php echo @$dispItemInfo["eventsub_selected_data"]["term_to"]; ?>" />

                                        <div class="comiket_detail_collect_date_parts">
                                            <select name="comiket_detail_collect_date_year_sel"
                                                    class="from_to_selectbox_y"
                                                    _gid="collect_date"
                                                   _from_slctr ="#hid_comiket-detail-collect-date-from"
                                                    _to_slctr ="#hid_comiket-detail-collect-date-to"
                                                    _selected="<?php echo @$bpn001Out->collect_year_cd_sel(); ?>"
                                                    _first="年を選択"
                                                    >
                                            </select>年
                                            <select name="comiket_detail_collect_date_month_sel"
                                                    class="from_to_selectbox_m"
                                                    _gid="collect_date"
                                                    _selected="<?php echo @$bpn001Out->collect_month_cd_sel(); ?>"
                                                    _first="月を選択"
                                                    >
                                            </select>月
                                            <select name="comiket_detail_collect_date_day_sel"
                                                    class="from_to_selectbox_d"
                                                    _gid="collect_date"
                                                    _selected="<?php echo @$bpn001Out->collect_day_cd_sel(); ?>"
                                                    _first="日を選択"
                                                    >
                                            </select>日
                                        </div>
                                    </dd>
                                </dl>
                            <?php endif; ?>    
                            <dl class="class_comiket_booth_name">
                                <dt id="comiket_booth_name">
                                    ブース名
                                    <span class ="hissu">必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_booth_name')) { echo ' class="form_error"'; } ?>>
                                    <input class="" style="width:80%;" maxlength="16" autocapitalize="off" name="comiket_booth_name" data-pattern="" placeholder="" type="text" value="<?php echo $bpn001Out->comiket_booth_name() ?>" />
                                </dd>
                            </dl>
                            <dl class="class_building_name_sel">
                                <dt id="building_name_sel">
                                    ブースNO<span class ="hissu">必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('building_name_sel')) { echo ' class="form_error"'; } ?>>
                                    <input type="hidden" name="building_name_sel" value="501" >
                                    <div style="margin-bottom: 2px;">
                                        <span style="font-size: 0.5em;">　　　　</span>
                                        <span style="font-size: 0.5em;margin-left: 100px;">ブース番号</span>
                                    </div>
                                    <select name="building_booth_position_sel">
                                        <option value="">選択してください</option>
                                        <?php
                                            echo Sgmov_View_Bpn_Input::_createPulldown($bpn001Out->building_booth_position_ids(), $bpn001Out->building_booth_position_lbls(), $bpn001Out->building_booth_position_sel());
                                        ?>
                                    </select>
                                    <input class="w_30Per" autocapitalize="off" maxlength="2" name="comiket_booth_num" placeholder="例）12(数値2桁)" type="text" value="<?php echo $bpn001Out->comiket_booth_num();?>" />
                                     <br/>
                                    <br/>
                                    <strong class="red lhn">
                                        ※入力例<br />
西1ホール → プルダウンより「A、ア～ケ」を選択、ブース番号欄に「01」と入力してください。<br />
西2ホール → プルダウンより「B、コ～ツ」を選択、ブース番号欄に「01」と入力してください。<br />
※出展者以外の方がご利用の場合は、「その他」「00」と、選択してください。
                                    </strong>
                                </dd>
                            </dl>
                        </div>
