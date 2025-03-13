<style type="text/css">
    .dl_block dl:last-child {
        border-bottom: solid 1px #ccc;
    }
    .center_id_label{
        width: 11%;
    }
    .date_of_birth {
        margin-right: 10px;
    }
    @media only screen and (max-width: 980px){
        .dl_block dl:last-child { 
            border-bottom: none;
        }
        #agedd{
            height: 77%
        }
        .sei_li{
            width: 100% !important;
        }
        .center_id_label{
            width: auto;
        }
    }
    @media only screen and (max-width: 768px){
        input[type="checkbox"] {
            -webkit-appearance: checkbox;
        }
    }
</style>
<input id="comiket_customer_kbn_sel1" class="comiket_customer_kbn" name="comiket_customer_kbn_sel" type="hidden" value="0">
<div class="dl_block comiket_block">
    <dl>
        <dt id="personal_name">
            お名前(漢字)<span>必須</span>
        </dt>
        <dd<?php if (isset($e) && ($e->hasErrorForId('personal_name'))) { echo ' class="form_error"';}?>>
            <input class="w_260 personal_name hanToZen" maxlength="16"  name="personal_name" type="text"  placeholder = "例）佐川　花子" value="<?php echo $ren001Out->personal_name(); ?>" />
        </dd>
    </dl>
    <dl>
        <dt id="personal_name_furi">
             お名前(フリガナ)<span>必須</span>
        </dt>
        <dd<?php if (isset($e) && ($e->hasErrorForId('personal_name_furi'))) { echo ' class="form_error"';}?>>
            <span class="event-place-lbl">
                <input class="w_260 personal_name_furi" maxlength="16" name="personal_name_furi" type="text" placeholder = "例）サガワ　ハナコ" value="<?php echo $ren001Out->personal_name_furi(); ?>" />
            </span>
        </dd>
    </dl>
<!--    <div style="width: 100%;display: flex;">-->
        <dl>
            <dt id="sei" >
                性別
            </dt>
            <dd  <?php if (isset($e) && ($e->hasErrorForId('sei'))) { echo ' class="form_error"';}?>>
                <ul class="clearfix">
                    <li class ="sei_li" style="width: 65%">
                        <label class="radio-label" for="male">
                            <input id="male" name="sei" type="radio" value="1" <?php if ($ren001Out->sei() == 1) echo 'checked = "checked"'; ?>/>
                            男性
                        </label>
                        <label class="radio-label" for="female">
                            <input id="female" name="sei" type="radio" value="2" <?php if ($ren001Out->sei() == 2) echo 'checked = "checked"'; ?>/>
                            女性
                        </label>
                        
                        <label class="radio-label" for="female">
                            <input id="female" name="sei" type="radio" value="3" <?php if ($ren001Out->sei() == 3) echo 'checked = "checked"'; ?>/>
                            その他
                        </label>
                    </li>
                </ul>
            </dd>
        </dl>
        <dl>
            <dt>
                生年月日<span>必須</span>
            </dt>
            <dd <?php if (isset($e) && ($e->hasErrorForId('date_of_birth'))) { echo ' class="form_error"';}?>>
                <span class="date_of_birth">
                    <select name="date_of_birth_year_cd_sel">
                        <option value="">--</option>
                    <?php
                        echo Sgmov_View_Ren_Input::_createPulldown($ren001Out->date_of_birth_year_cds(), $ren001Out->date_of_birth_year_lbls(), $ren001Out->date_of_birth_year_cd_sel());
                    ?>
                    </select>年
            </span>
            <span class="date_of_birth">
                <select name="date_of_birth_month_cd_sel">
                        <option value="">--</option>
                <?php
                    echo Sgmov_View_Ren_Input::_createPulldown($ren001Out->date_of_birth_month_cds(), $ren001Out->date_of_birth_month_lbls(), $ren001Out->date_of_birth_month_cd_sel());
                ?>
                </select>月
            </span>
            <span class="date_of_birth">
                <select name="date_of_birth_day_cd_sel">
                    <option value="">--</option>
                <?php
                    echo Sgmov_View_Ren_Input::_createPulldown($ren001Out->date_of_birth_day_cds(), $ren001Out->date_of_birth_day_lbls(), $ren001Out->date_of_birth_day_cd_sel());
                ?>
                </select>日
            </span>
            </dd>
        </dl>
<!--    </div>-->
    <dl>
        <dt id="zip">
            郵便番号<span>必須</span>
        </dt>
        <dd<?php if (isset($e) && ($e->hasErrorForId('zip'))) { echo ' class="form_error"';}?>>
            <ul>
                <li style="display: inline-block;"> 〒
                    <input class="w_70" maxlength="3" placeholder = "例）136" name="zip1" type="text"  value="<?php echo $ren001Out->zip1();?>"  />
                    -
                    <input class="w_70" maxlength="4" name="zip2" placeholder = "例）0082" type="text" value="<?php echo $ren001Out->zip2();?>" />
                    <input class="button ml10" name="adrs_search_btn" type="button" value="住所検索" />
                </li>
            </ul>
        </dd>
    </dl>
    <dl>
        <dt id="pref_id">
            都道府県<span>必須</span>
        </dt>
        <dd<?php if (isset($e) && ($e->hasErrorForId('pref_id'))) { echo ' class="form_error"';}?>>
            <ul>
                <li>
                    <select class="w110" name="pref_id">
                        <option value="">選択してください</option>
                    <?php echo Sgmov_View_Ren_Input::_createPulldown($ren001Out->pref_cds(), $ren001Out->pref_lbls(), $ren001Out->pref_id());?>
                    </select>
                </li>
            </ul>
        </dd>
    </dl>
    <dl>
        <dt id="address">
            市区町村<span>必須</span>
        </dt>
        <dd <?php if (isset($e) && ($e->hasErrorForId('address'))) { echo ' class="form_error"';}?>>
            <ul>
                <li>
                    <input style="width: 80%;" name="address" placeholder="例）江東区新砂" maxlength="14" type="text" value="<?php echo $ren001Out->address(); ?>" />
                </li>
            </ul>
        </dd>
    </dl>
    <dl>
        <dt id="building">
            番地・建物名・部屋番号<span>必須</span>
        </dt>
        <dd<?php if (isset($e) && ($e->hasErrorForId('building'))) { echo ' class="form_error"';}?>>
            <ul>
                <li>
                    <input style="width: 80%;" name="building" maxlength="30" type="text" value="<?php echo $ren001Out->building(); ?>" />
                </li>
            </ul>
        </dd>
    </dl>
    <dl>
        <dt id="tel">
            電話番号（携帯電話番号）<span>必須</span>
        </dt>
        <dd<?php if (isset($e) && ($e->hasErrorForId('tel'))) { echo ' class="form_error"';}?>>
           <input name="tel" class="number-p-only" type="text" maxlength="15" data-pattern="^[0-9-]+$" placeholder="例）080-1111-2222" value="<?php echo $ren001Out->tel(); ?>" />
        </dd>
    </dl>
    <dl>
        <dt id="mail">
            メールアドレス<span>必須</span>
        </dt>
        <dd<?php if (isset($e) && ($e->hasErrorForId('mail'))) { echo ' class="form_error"';}?>>
            <input style="width: 60%;" maxlength="100" name="mail" type="text" placeholder="例）ringo@sagawa.com" value="<?php echo $ren001Out->mail(); ?>" />
        </dd>
    </dl>
    
</div>