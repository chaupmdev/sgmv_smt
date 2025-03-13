
<!-- ***************************************************************************************************************************************** -->
<!-- 荷物預入取出2 Start -->
<!-- ***************************************************************************************************************************************** -->

<div class="input-azuke input-outbound-title" style="display: none;">手荷物預かりサービス </div>
<div class="dl_block input-azuke comiket_block" style="display: none;">
    <dl>
        <dt id="comiket_detail_azukari_one">
            取出回数<span>必須</span>
        </dt>
        <dd>
            <label class="label1" for="sel1" style="line-height: 40px;">
                <input id="sel1" class="comiket_detail_azukari_kaisu_type_sel comiket_detail_azukari_kaisu_type_sel1" name="comiket_detail_azukari_kaisu_type_sel" type="radio"
                    value="1" checked="checked">
                １回のみ
            </label>
            &nbsp;&nbsp;<strong class="red">※当日に「１度だけ」手荷物の出し入れが可能です。</strong>
            <br>
            <label class="label2" for="sel2" style="line-height: 40px;">
                <input id="sel2" class="comiket_detail_azukari_kaisu_type_sel comiket_detail_azukari_kaisu_type_sel2" name="comiket_detail_azukari_kaisu_type_sel" type="radio"
                    value="2">
                複数回
            </label>
            &nbsp;&nbsp;<strong class="red">※当日に「何度でも」手荷物の出し入れが可能です。</strong>
        </dd>
    </dl>
    
    <dl class="bk_item51" style="">
        <dt id="comiket_detail_type_sel">
            お預かり場所選択<span>必須</span>
        </dt>
        <dd>
            <div class="comiket_detail_type_sel-dd">
                <div>
                    <select name="comiket_detail_azukari_basho_sel" style="">
                        <option value="">選択してください</option>
                        <option value="1">東預かり所</option>
                        <option value="2">西預かり所</option>
                        <option value="3">南預かり所</option>
                        <option value="4">北預かり所</option>
                    </select>
                    <input class="m110" name="azukari_area_conf" type="button" value="預かり所確認">
                </div>
                <br>
                <div style="margin-left: 10px;">
                    <strong class="red">※手荷物を預けたままで、帰宅された場合は、登録された住所への着払い発送とさせて頂きます。</strong>
                </div>
            </div>
        </dd>
    </dl>

<!--    <dl class="item51" style="display:none;">
        <dt id="comiket_detail_azukari_kaisu_type_sel">
            お預かり手荷物の取り扱い<span>必須</span>
        </dt>
        <dd>
            <div class="comiket_detail_azukari_kaisu_type_sel-dd">
                <div>
                    <label class="radio-label comiket_detail_azukari_kaisu_type_sel-label1-99"
                        for="comiket_detail_azukari_toriatsukai_type_sel199">
                        <input id="comiket_detail_azukari_toriatsukai_type_sel199" class=""
                            name="comiket_detail_azukari_toriatsukai_type_sel99" type="radio" checked="checked"
                            value="1"
                            onclick="$('.toridashi_outbount').hide(300);$('.otodokesaki_simei').html('氏名');$('.otodokesaki_tel').html('TEL');$('.comiket_detail_azukari_delivery_date5').hide(300);" />
                        手荷物を持ち帰られる方はこちら
                    </label>
                </div>
                <div style="margin-left: 10px;">
                    <strong class="red">※持ち帰りを選択して、手荷物を預けたままで、帰宅された場合は、登録された住所への着払い発送とさせて頂きます</strong>
                </div>
                <label class="radio-label comiket_detail_azukari_kaisu_type_sel-label2-99"
                    for="comiket_detail_azukari_toriatsukai_type_sel299">
                    <input id="comiket_detail_azukari_toriatsukai_type_sel299" class=""
                        name="comiket_detail_azukari_toriatsukai_type_sel99" type="radio" value="2"
                        onclick="$('.toridashi_outbount').show(300);$('.otodokesaki_simei').html('お届け先氏名');$('.otodokesaki_tel').html('お届け先TEL');$('.comiket_detail_azukari_delivery_date5').show(300);" />
                    会場からご自宅まで手荷物を発送される方はこちら
                </label>
                <br />
        </dd>
    </dl>-->

    <dl>
        <dt id="comiket_detail_azukari_one">
            <abc class="otodokesaki_simei">氏名</abc><span>必須</span>
        </dt>
        <dd>
            <input class="" style="width:80%;" maxlength="32" autocapitalize="off"
                inputmode="comiket_detail_azukari_one" name="comiket_detail_azukari_one"
                data-pattern="" placeholder="" type="text" value="">
            <input class="m110" name="azukari_adrs_copy_btn" type="button" value="お申込者と同じ">
        </dd>
    </dl>

    <dl class="toridashi_outbount" style="display:none;">
        <dt id="comiket_detail_inbound_zip">
            お届け先郵便番号<span>必須</span>
        </dt>

        <dd>
            〒<input autocapitalize="off" class="w_70 number-only" maxlength="3"
                inputmode="numeric" name="comiket_detail_inbound_zip1" data-pattern="^\d+$"
                placeholder="例）136" type="text" value="" />
            -
            <input autocapitalize="off" class="w_70 number-only" maxlength="4"
                inputmode="numeric" name="comiket_detail_inbound_zip2" data-pattern="^\d+$"
                placeholder="例）0082" type="text" value="" />
            <input class="m110" name="inbound_adrs_search_btn" type="button" value="住所検索" />
            <span class="wb" style="font-size:12px;">
                &#12288;※郵便番号が不明な方は<a style="text-decoration: underline" target="_blank"
                    href="http://www.post.japanpost.jp/zipcode/">こちら...</a>
            </span>
        </dd>
    </dl>
    <dl class="toridashi_outbount" style="display:none;">
        <dt id="comiket_detail_inbound_pref">
            お届け先都道府県<span>必須</span>
        </dt>
        <dd>
            <select name="comiket_detail_inbound_pref_cd_sel">
                <option value="">選択してください</option>
                <option value="1">北海道</option>
                <option value="2">青森県</option>
                <option value="3">岩手県</option>
                <option value="4">宮城県</option>
                <option value="5">秋田県</option>
                <option value="6">山形県</option>
                <option value="7">福島県</option>
                <option value="8">茨城県</option>
                <option value="9">栃木県</option>
                <option value="10">群馬県</option>
                <option value="11">埼玉県</option>
                <option value="12">千葉県</option>
                <option value="13">東京都</option>
                <option value="14">神奈川県</option>
                <option value="15">新潟県</option>
                <option value="16">富山県</option>
                <option value="17">石川県</option>
                <option value="18">福井県</option>
                <option value="19">山梨県</option>
                <option value="20">長野県</option>
                <option value="21">岐阜県</option>
                <option value="22">静岡県</option>
                <option value="23">愛知県</option>
                <option value="24">三重県</option>
                <option value="25">滋賀県</option>
                <option value="26">京都府</option>
                <option value="27">大阪府</option>
                <option value="28">兵庫県</option>
                <option value="29">奈良県</option>
                <option value="30">和歌山県</option>
                <option value="31">鳥取県</option>
                <option value="32">島根県</option>
                <option value="33">岡山県</option>
                <option value="34">広島県</option>
                <option value="35">山口県</option>
                <option value="36">徳島県</option>
                <option value="37">香川県</option>
                <option value="38">愛媛県</option>
                <option value="39">高知県</option>
                <option value="40">福岡県</option>
                <option value="41">佐賀県</option>
                <option value="42">長崎県</option>
                <option value="43">熊本県</option>
                <option value="44">大分県</option>
                <option value="45">宮崎県</option>
                <option value="46">鹿児島県</option>
                <option value="47">沖縄県</option>
            </select>
            <br />
            <br />
            <strong class="red">
                ※沖縄の場合は航空運賃が適用されます。<br /><br />
                ※郡部・離島・一部地域で中継料が発生する場合は、お荷物のお取り扱いが出来ない場合がございます。
                <br/>
                <a href="https://sagawa-mov-test03.media-tec.jp/pdf/kokubin_goriyoujyono_tyui.pdf" target="_blank" style="color: #1774bc; text-decoration: underline;">航空便ご利用上の注意はこちら</a>
                <br/>
                <a href="https://sagawa-mov-test03.media-tec.jp/pdf/kokutakuhaibinnado_unso_yakkan.pdf" target="_blank" style="color: #1774bc; text-decoration: underline;">航空宅配便等個建運送約款はこちら</a>
            </strong>
        </dd>
    </dl>
    <dl class="toridashi_outbount" style="display:none;">
        <dt id="comiket_detail_inbound_address">
            お届け先市区町村<span>必須</span>
        </dt>
        <dd>
            <input class="" style="width:80%;" maxlength="14" autocapitalize="off"
                name="comiket_detail_inbound_address" placeholder="例）江東区新砂" type="text"
                value="" />
        </dd>
    </dl>
    <dl class="toridashi_outbount" style="display:none;">
        <dt id="comiket_detail_inbound_building">
            お届け先番地・建物名
        </dt>
        <dd>
            <input class="" style="width:80%;" maxlength="30" autocapitalize="off"
                name="comiket_detail_inbound_building" placeholder="例）1-8-2" type="text"
                value="" />
        </dd>
    </dl>







    <dl>
        <dt id="comiket_detail_azukari_tel">
            <abc class="otodokesaki_tel">TEL</abc><span>必須</span>
        </dt>
        <dd>
            <input autocapitalize="off" class="number-p-only" style="width:50%;" maxlength="15"
                name="comiket_detail_azukari_tel" data-pattern="^[0-9-]+$"
                placeholder="例）075-1234-5678" type="text" value="">
        </dd>
    </dl>
    <dl class="class_comiket_detail_azukari_collect_date" style="">
        <dt id="comiket_detail_azukari_collect_date">
            お預かり日<span>必須</span>
        </dt>
        <dd class="comiket_detail_azukari_collect_date_input_part">
            <p class="comiket-detail-azukari-collect-date-fr-to">
                2020年04月25日（土）&nbsp;から&nbsp;2020年04月26日（日）&nbsp;まで選択できます。</p>
            <input type="hidden" id="hid_comiket-detail-azukari-collect-date-from"
                name="hid_comiket-detail-azukari-collect-date-from" value="2019-06-10">
            <input type="hidden" id="hid_comiket-detail-azukari-collect-date-to"
                name="hid_comiket-detail-azukari-collect-date-to" value="2019-06-14">
            <input type="hidden" id="hid_comiket-detail-azukari-collect-date-from_ori"
                name="hid_comiket-detail-azukari-collect-date-from_ori" value="2019-06-10">
            <input type="hidden" id="hid_comiket-detail-azukari-collect-date-to_ori"
                name="hid_comiket-detail-azukari-collect-date-to_ori" value="2019-06-14">
            <div class="comiket_detail_azukari_collect_date_parts" style="">
                <span class="comiket_detail_azukari_collect_date">
                    <select name="comiket_detail_azukari_collect_date_year_sel">
                        <!-- <option value="">年を選択</option> -->
                        <!-- <option value="2019">2019</option> -->
                        <option value="2020">2020</option>
                    </select>年
                </span>
                <span class="comiket_detail_azukari_collect_date">
                    <select name="comiket_detail_azukari_collect_date_month_sel">
                        <!-- <option value="">月を選択</option> -->
                        <!-- <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option> -->
                        <option value="04">04</option>
                        <!-- <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option> -->
                    </select>月
                </span>
                <span class="comiket_detail_azukari_collect_date">
                    <select name="comiket_detail_azukari_collect_date_day_sel">
                        <option value="">日を選択</option>
                        <!-- <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                        <option value="24">24</option> -->
                        <option value="25">25</option>
                        <option value="26">26</option>
                        <!-- <option value="27">27</option>
                        <option value="28">28</option>
                        <option value="29">29</option>
                        <option value="30">30</option>
                        <option value="31">31</option> -->
                    </select>日
                </span>
                <!--
                    &nbsp;
                    <span class="comiket_detail_azukari_collect_time_sel" style="display: inline;">
                        時間帯
                        <select name="comiket_detail_azukari_collect_time_sel">
                            <option value="">時間を選択</option>
                            <option value="10:00:00-13:00:00">09:00</option>
                            <option value="12:00:00-15:00:00">09:30</option>
                            <option value="10:00:00-13:00:00">10:00</option>
                            <option value="12:00:00-15:00:00">10:30</option>
                            <option value="10:00:00-13:00:00">11:00</option>
                            <option value="12:00:00-15:00:00">11:30</option>
                            <option value="10:00:00-13:00:00">12:00</option>
                            <option value="12:00:00-15:00:00">12:30</option>
                            <option value="10:00:00-13:00:00">13:00</option>
                            <option value="12:00:00-15:00:00">13:30</option>
                            <option value="10:00:00-13:00:00">14:00</option>
                            <option value="12:00:00-15:00:00">14:30</option>
                            <option value="10:00:00-13:00:00">15:00</option>
                            <option value="12:00:00-15:00:00">15:30</option>
                            <option value="10:00:00-13:00:00">16:00</option>
                            <option value="12:00:00-15:00:00">16:30</option>
                            <option value="10:00:00-13:00:00">17:00</option>
                            <option value="12:00:00-15:00:00">17:30</option>
                            <option value="10:00:00-13:00:00">18:00</option>
                            <option value="12:00:00-15:00:00">18:30</option>
                            <option value="10:00:00-13:00:00">19:00</option>
                            <option value="12:00:00-15:00:00">19:30</option>
                            <option value="10:00:00-13:00:00">20:00</option>
                            <option value="12:00:00-15:00:00">20:30</option>
                            <option value="10:00:00-13:00:00">21:00</option>
                        </select>
                    </span>
                    -->
                <br><br>&nbsp;から&nbsp;<br><br>
                <span class="comiket_detail_azukari_collect_date">
                    <select name="comiket_detail_azukari_collect_date_year_sel">
                        <!-- <option value="">年を選択</option> -->
                        <!-- <option value="2019">2019</option> -->
                        <option value="2020">2020</option>
                    </select>年
                </span>
                <span class="comiket_detail_azukari_collect_date">
                    <select name="comiket_detail_azukari_collect_date_month_sel">
                        <!-- <option value="">月を選択</option> -->
                        <!-- <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option> -->
                        <option value="04">04</option>
                        <!-- <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option> -->
                    </select>月
                </span>
                <span class="comiket_detail_azukari_collect_date">
                    <select name="comiket_detail_azukari_collect_date_day_sel">
                        <option value="">日を選択</option>
                        <!-- <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                        <option value="24">24</option> -->
                        <option value="25">25</option>
                        <option value="26">26</option>
                        <!-- <option value="27">27</option>
                        <option value="28">28</option>
                        <option value="29">29</option>
                        <option value="30">30</option>
                        <option value="31">31</option> -->
                    </select>日
                </span>
                <br><br>&nbsp;まで&nbsp;<br><br>
            </div>
        </dd>
    </dl>
    <dl class="comiket_detail_azukari_service_sel" style="display: none;">
        <dt id="comiket_detail_azukari_service_sel">
            サービス選択<span>必須</span>
        </dt>
        <dd>

            <label class="radio-label" for="comiket_detail_azukari_service_sel1" style="">
                <input id="comiket_detail_azukari_service_sel1"
                    name="comiket_detail_azukari_service_sel" type="radio" value="1"
                    checked="checked">
                宅配便 </label>
            <label class="radio-label" for="comiket_detail_azukari_service_sel2"
                style="display: none;">
                <input id="comiket_detail_azukari_service_sel2"
                    name="comiket_detail_azukari_service_sel" type="radio" value="2"
                    checked="checked">
                カーゴ </label>
            <label class="radio-label" for="comiket_detail_azukari_service_sel3"
                style="display: none;">
                <input id="comiket_detail_azukari_service_sel3"
                    name="comiket_detail_azukari_service_sel" type="radio" value="3"
                    checked="checked">
                貸切（チャーター） </label>
            <div>
                <strong class="red">※カーゴの場合、時間指定はお受けできません。集荷は9:00～18:00でのお伺いとなります。</strong>
            </div>
        </dd>
    </dl>

    <dl class="comiket_detail_azukari_delivery_date5" style="display:none;">
        <dt id="comiket_detail_azukari_delivery_date">
            お届け日時<span>必須</span>
        </dt>
        <dd>
            <p class="comiket-detail-azukari-delivery-date-fr-to">
                2020年05月01日（金）&nbsp;から&nbsp;2020年05月31日（日）&nbsp;まで選択できます。</p>
            <input type="hidden" id="hid_comiket-detail-azukari_delivery-date-from"
                name="hid_comiket-detail-azukari_delivery-date-from" value="2019-06-18">
            <input type="hidden" id="hid_comiket-detail-azukari_delivery-date-to"
                name="hid_comiket-detail-azukari_delivery-date-to" value="2019-06-19">
            <div class="comiket_detail_azukari_delivery_date_parts" style="">
                <span class="comiket_detail_azukari_delivery_date" style="display: inline;">
                    <select name="comiket_detail_azukari_delivery_date_year_sel">
                        <!-- <option value="">年を選択</option> -->
                        <!-- <option value="2019">2019</option> -->
                        <option value="2020">2020</option>
                    </select>年
                </span>
                <span class="comiket_detail_azukari_delivery_date" style="display: inline;">
                    <select name="comiket_detail_azukari_delivery_date_month_sel">
                        <!-- <option value="">月を選択</option> -->
                        <!-- <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option> -->
                        <option value="05">05</option>
                        <!-- <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option> -->
                    </select>月
                </span>
                <span class="comiket_detail_azukari_delivery_date" style="display: inline;">
                    <select name="comiket_detail_azukari_delivery_date_day_sel">
                        <option value="">日を選択</option>
                        <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                        <option value="24">24</option>
                        <option value="25">25</option>
                        <option value="26">26</option>
                        <option value="27">27</option>
                        <option value="28">28</option>
                        <option value="29">29</option>
                        <option value="30">30</option>
                        <option value="31">31</option>
                    </select>日
                </span>
                &nbsp;
                <span class="comiket_detail_azukari_delivery_time_sel" style="">
                    時間帯
                    <select name="comiket_detail_inbound_delivery_time_sel">
                        <option value="">時間帯を選択</option>
                        <option value="00,指定なし">指定なし</option>
                        <option value="11,午前中">午前中</option>
                        <option value="22,12:00～14:00">12:00～14:00</option>
                        <option value="24,14:00～16:00">14:00～16:00</option>
                        <option value="26,16:00～18:00">16:00～18:00</option>
                        <option value="14,18:00～21:00">18:00～21:00</option>
                    </select>
                </span>
            </div>
        </dd>
    </dl>

    <!--
        <dl class="service-azukari-item" service-id="1" style="">
            <dt id="comiket_box_azukari_num_ary">
                宅配数量<span>必須</span>
            </dt>
            <dd>
                <div class="comiket-box-azukari-num comiket-box-azukari-num-dmy">

                    <table><tbody><tr><td><table><tbody><tr><td class="comiket_box_item_name">60サイズ&nbsp;</td><td class="comiket_box_item_value"><input autocapitalize="off" class="number-only comiket_box_item_value_input" maxlength="2" inputmode="numeric" name="comiket_box_outbound_num_ary[3436]" data-pattern="^d+$" placeholder="例）1" type="text" value="">個</td></tr><tr><td class="comiket_box_item_name">80サイズ&nbsp;</td><td class="comiket_box_item_value"><input autocapitalize="off" class="number-only comiket_box_item_value_input" maxlength="2" inputmode="numeric" name="comiket_box_outbound_num_ary[3437]" data-pattern="^d+$" placeholder="例）1" type="text" value="">個</td></tr><tr><td class="comiket_box_item_name">100サイズ&nbsp;</td><td class="comiket_box_item_value"><input autocapitalize="off" class="number-only comiket_box_item_value_input" maxlength="2" inputmode="numeric" name="comiket_box_outbound_num_ary[3438]" data-pattern="^d+$" placeholder="例）1" type="text" value="">個</td></tr><tr><td class="comiket_box_item_name">140サイズ&nbsp;</td><td class="comiket_box_item_value"><input autocapitalize="off" class="number-only comiket_box_item_value_input" maxlength="2" inputmode="numeric" name="comiket_box_outbound_num_ary[3439]" data-pattern="^d+$" placeholder="例）1" type="text" value="">個</td></tr><tr><td class="comiket_box_item_name">160サイズ&nbsp;</td><td class="comiket_box_item_value"><input autocapitalize="off" class="number-only comiket_box_item_value_input" maxlength="2" inputmode="numeric" name="comiket_box_outbound_num_ary[3440]" data-pattern="^d+$" placeholder="例）1" type="text" value="">個</td></tr><tr><td class="comiket_box_item_name">170サイズ&nbsp;</td><td class="comiket_box_item_value"><input autocapitalize="off" class="number-only comiket_box_item_value_input" maxlength="2" inputmode="numeric" name="comiket_box_outbound_num_ary[3441]" data-pattern="^d+$" placeholder="例）1" type="text" value="">個</td></tr><tr><td class="comiket_box_item_name">180サイズ&nbsp;</td><td class="comiket_box_item_value"><input autocapitalize="off" class="number-only comiket_box_item_value_input" maxlength="2" inputmode="numeric" name="comiket_box_outbound_num_ary[3442]" data-pattern="^d+$" placeholder="例）1" type="text" value="">個</td></tr><tr><td class="comiket_box_item_name">200サイズ&nbsp;</td><td class="comiket_box_item_value"><input autocapitalize="off" class="number-only comiket_box_item_value_input" maxlength="2" inputmode="numeric" name="comiket_box_outbound_num_ary[3443]" data-pattern="^d+$" placeholder="例）1" type="text" value="">個</td></tr><tr><td class="comiket_box_item_name">220サイズ&nbsp;</td><td class="comiket_box_item_value"><input autocapitalize="off" class="number-only comiket_box_item_value_input" maxlength="2" inputmode="numeric" name="comiket_box_outbound_num_ary[3444]" data-pattern="^d+$" placeholder="例）1" type="text" value="">個</td></tr><tr><td class="comiket_box_item_name">240サイズ&nbsp;</td><td class="comiket_box_item_value"><input autocapitalize="off" class="number-only comiket_box_item_value_input" maxlength="2" inputmode="numeric" name="comiket_box_outbound_num_ary[3445]" data-pattern="^d+$" placeholder="例）1" type="text" value="">個</td></tr><tr><td class="comiket_box_item_name">260サイズ&nbsp;</td><td class="comiket_box_item_value"><input autocapitalize="off" class="number-only comiket_box_item_value_input" maxlength="2" inputmode="numeric" name="comiket_box_outbound_num_ary[3446]" data-pattern="^d+$" placeholder="例）1" type="text" value="">個</td></tr></tbody></table></td><td style="vertical-align: middle;text-align: right;"><img class="dispSeigyoPC" src="./催事・イベント配送受付サービスのお申し込み｜お問い合わせ│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞_files/about_boxsize.png" width="100%"></td></tr></tbody></table><div class="dispSeigyoSP"><img src="./催事・イベント配送受付サービスのお申し込み｜お問い合わせ│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞_files/about_boxsize.png" width="250px" style="margin-top: 1em;"></div></div>
                <br>
                <div class="azukari_example_boxsize example_boxsize" style="">
                    <strong class="red">※ 最大4種類(サイズ)までご指定頂けます</strong><br><br>
                    <strong class="red">※ 5種類以上をご希望の場合は、4種類ずつに分けて、複数のお申込みをお願い致します</strong><br><br>
                    <a href="https://sagawa-mov-test03.media-tec.jp/dsn/pdf/example/example_box_size.pdf" target="_blank" style="color:blue;">目安表</a>
                </div>
            </dd>
        </dl>
        -->
    <!-- <dl class="class_hotel_system_luggage_type">
        <dt id="hotel_system_luggage_type">
            お預かり手荷物<span>必須</span>
        </dt>
        <dd class="hotel_system_luggage_type_input_part">
            <select name="hotel_system_luggage_type_cd_sel">
                <option value="">選択してください</option>
                <option value="suitcase" selected="selected">スーツケース　（140サイズ）</option>
                <option value="souvenir">ボストンバック（大）（140サイズ）</option>
                <option value="backpack">ボストンバック（中）（100サイズ）</option>
                <option value="bostonbag">ボストンバック（小）（80サイズ）</option>
            </select>
            <br>
            <br>
            <br>
            <b>■ 指定可能サイズ</b>
            <br>
            <img src="./images/about_boxsize.png" width="80%" style="margin-top: 0em;">
        </dd>
    </dl> -->










    <dl class="service-azukari-item" service-id="1" style="">
        <dt id="comiket_box_azukari_num_ary">
            宅配数量<span>必須</span>
        </dt>
        <dd>
            <div class="comiket-box-azukari-num comiket-box-azukari-num-dmy">
                <!-- 個人・法人 両方 -->
                
                <table>
                    <tbody>
                        <tr>
                            <td>
                                <table>
                                    <tbody>
                                        <tr class="box_suitcase">
                                            <td class="comiket_box_item_name">スーツケース<br class='sp_br' />（140サイズ）&nbsp;</td>
                                            <td class="comiket_box_item_value"><input
                                                    autocapitalize="off"
                                                    class="number-only comiket_box_item_value_input"
                                                    maxlength="2" inputmode="numeric"
                                                    name="comiket_box_azukari_num_ary[5000]"
                                                    data-pattern="^d+$" placeholder="例）1"
                                                    type="text" value="">個</td>
                                        </tr>
                                        <tr class="box_140">
                                            <td class="comiket_box_item_name">ボストンバック（大）<br class='sp_br' />（140サイズ）&nbsp;</td>
                                            <td class="comiket_box_item_value"><input
                                                    autocapitalize="off"
                                                    class="number-only comiket_box_item_value_input"
                                                    maxlength="2" inputmode="numeric"
                                                    name="comiket_box_azukari_num_ary[5000]"
                                                    data-pattern="^d+$" placeholder="例）1"
                                                    type="text" value="">個</td>
                                        </tr>
                                        <tr class="box_180">
                                            <td class="comiket_box_item_name">ボストンバック（中）<br class='sp_br' />（100サイズ）&nbsp;</td>
                                            <td class="comiket_box_item_value"><input
                                                    autocapitalize="off"
                                                    class="number-only comiket_box_item_value_input"
                                                    maxlength="2" inputmode="numeric"
                                                    name="comiket_box_azukari_num_ary[5001]"
                                                    data-pattern="^d+$" placeholder="例）1"
                                                    type="text" value="">個</td>
                                        </tr>
                                        <tr class="box_80">
                                            <td class="comiket_box_item_name">ボストンバック（小）<br class='sp_br' />（80サイズ）&nbsp;</td>
                                            <td class="comiket_box_item_value"><input
                                                    autocapitalize="off"
                                                    class="number-only comiket_box_item_value_input"
                                                    maxlength="2" inputmode="numeric"
                                                    name="comiket_box_azukari_num_ary[5002]"
                                                    data-pattern="^d+$" placeholder="例）1"
                                                    type="text" value="">個</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <br>
                                <br>
                                <b>■ 指定可能サイズ</b>
                                <br>
                                <img src="/images/common/about_suitcase_size.png" width="80%" style="margin-top: 0em;">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- <div class="dispSeigyoSP" style="margin-top: 1em;">
                <img 
                src="./催事・イベント配送受付サービスのお申し込み｜お問い合わせ│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞_files/about_boxsize.png"
                width="250px" style="margin-top: 1em;">
            </div>
            <br>
            <div class="azukari_example_boxsize example_boxsize" style="">
                <strong class="red msg-hikyaku_coolbin" style="display:none;">※
                    飛脚クール便を発送される方は、必ずご利用予定日の15時までに宅配カウンターへお荷物をお持ち込みください。15時を過ぎますと、お引受けができなくなります。</strong><br
                    class="msg-hikyaku_coolbin"><br class="msg-hikyaku_coolbin">
                <strong class="red msg-hikyaku_normalbin">※ 最大4種類(サイズ)までご指定頂けます</strong><br
                    class="msg-hikyaku_normalbin"><br class="msg-hikyaku_normalbin">
                <strong class="red msg-hikyaku_normalbin">※
                    5種類以上をご希望の場合は、4種類ずつに分けて、複数のお申込みをお願い致します</strong><br
                    class="msg-hikyaku_normalbin"><br class="msg-hikyaku_normalbin">
                <strong class="red">※ 重量はお荷物１つにつき３０Kｇが上限となります。</strong><br><br>
                <strong class="red">※ ガラス・鏡などの「割れ物」は、集荷時にお申し出ください。<br>
                    お申し出がない場合、損害補償の対象外となります。</strong><br><br>
                <strong class="red">※ １申込み４０個を超える場合は、ＳＧムービング/アラバキロックフェス係り
                    ０３－５５３４－１０８０　もしくはカウンタースタッフへお問い合わせください。</strong><br><br>

                <a href="data_files/example_box_size.pdf" target="_blank"
                    style="color:blue;">目安表</a>
            </div> -->
        </dd>
    </dl>








    <dl class="service-azukari-item" service-id="2" style="display: none;">
        <dt id="comiket_cargo_azukari_num_ary">
            カーゴ数量<span>必須</span>
        </dt>
        <dd>
            <div class="comiket-cargo-azukari-num" div-id="1" style="display: none;">
                <!-- 個人 -->
            </div>
            <div class="comiket-cargo-azukari-num" div-id="2" style="display: none;">
                <!-- 法人 -->
            </div>
            <div style="">
                <input autocapitalize="off" class="number-only boxWid" maxlength="2"
                    inputmode="numeric" name="comiket_cargo_azukari_num_sel"
                    data-pattern="^\d+$" placeholder="例）1" type="text" value="">台
            </div>
        </dd>
    </dl>
    <dl class="service-azukari-item" service-id="3" style="display: none;">
        <dt id="comiket_charter_azukari_num_ary">
            台数貸切<span>必須</span>
        </dt>
        <dd>
            <div class="comiket-charter-azukari-num" div-id="1" style="">
                <!-- 個人 -->
                <input autocapitalize="off" class="" style="width:10%;" maxlength="2"
                    inputmode="numeric" name="comiket_charter_azukari_num_ary[0]"
                    data-pattern="^\d+$" placeholder="例）7" type="text" value="">台
            </div>
            <div class="comiket-charter-azukari-num" div-id="2" style="display: none;">
                <!-- 法人 -->
                <!--<div style="margin-bottom: 10px;">-->
                <table>
                    <tbody>
                        <tr>
                            <!--<div style="margin-bottom: 10px;">-->
                            <td class="comiket_charter_item_name">2tショート&nbsp;</td>
                            <td class="comiket_charter_item_value">
                                <input autocapitalize="off" class="number-only boxWid"
                                    maxlength="2" inputmode="numeric"
                                    name="comiket_charter_azukari_num_ary[1]"
                                    data-pattern="^\d+$" placeholder="例）1" type="text"
                                    value="">台
                                &nbsp;
                            </td>
                            <!--</div>-->
                        </tr>
                        <tr>
                            <!--<div style="margin-bottom: 10px;">-->
                            <td class="comiket_charter_item_name">2tロング&nbsp;</td>
                            <td class="comiket_charter_item_value">
                                <input autocapitalize="off" class="number-only boxWid"
                                    maxlength="2" inputmode="numeric"
                                    name="comiket_charter_azukari_num_ary[2]"
                                    data-pattern="^\d+$" placeholder="例）1" type="text"
                                    value="">台
                                &nbsp;
                            </td>
                            <!--</div>-->
                        </tr>
                        <tr>
                            <!--<div style="margin-bottom: 10px;">-->
                            <td class="comiket_charter_item_name">3t&nbsp;</td>
                            <td class="comiket_charter_item_value">
                                <input autocapitalize="off" class="number-only boxWid"
                                    maxlength="2" inputmode="numeric"
                                    name="comiket_charter_azukari_num_ary[3]"
                                    data-pattern="^\d+$" placeholder="例）1" type="text"
                                    value="">台
                                &nbsp;
                            </td>
                            <!--</div>-->
                        </tr>
                        <tr>
                            <!--<div style="margin-bottom: 10px;">-->
                            <td class="comiket_charter_item_name">4t&nbsp;</td>
                            <td class="comiket_charter_item_value">
                                <input autocapitalize="off" class="number-only boxWid"
                                    maxlength="2" inputmode="numeric"
                                    name="comiket_charter_azukari_num_ary[4]"
                                    data-pattern="^\d+$" placeholder="例）1" type="text"
                                    value="">台
                                &nbsp;
                            </td>
                            <!--</div>-->
                        </tr>
                        <tr>
                            <!--<div style="margin-bottom: 10px;">-->
                            <td class="comiket_charter_item_name">4tロング&nbsp;</td>
                            <td class="comiket_charter_item_value">
                                <input autocapitalize="off" class="number-only boxWid"
                                    maxlength="2" inputmode="numeric"
                                    name="comiket_charter_azukari_num_ary[5]"
                                    data-pattern="^\d+$" placeholder="例）1" type="text"
                                    value="">台
                                &nbsp;
                            </td>
                            <!--</div>-->
                        </tr>
                    </tbody>
                </table>
                <!--</div>-->
            </div>
        </dd>
    </dl>
    <dl>
        <dt id="comiket_detail_azukari_note">
            備考
        </dt>
        <dd>
            <input autocapitalize="off" class="" style="width:50%;" maxlength="16"
                inputmode="text" name="comiket_detail_azukari_note1" placeholder="" type="text"
                value=""><br>
        </dd>
    </dl>
</div>

<!-- ***************************************************************************************************************************************** -->
<!-- 荷物預入取出 End -->
<!-- ***************************************************************************************************************************************** -->
