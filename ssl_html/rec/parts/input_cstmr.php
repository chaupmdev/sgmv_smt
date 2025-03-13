<style type="text/css">
    .dl_block dl:last-child {
        border-bottom: solid 1px #ccc;
    }
    .center_id_label{
        width: 11%;
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
            <input class="w_260 personal_name hanToZen" maxlength="16"  name="personal_name" type="text"  placeholder = "例）佐川　花子" value="<?php echo $rec001Out->personal_name(); ?>" />
        </dd>
    </dl>
    <dl>
        <dt id="personal_name_furi">
             お名前(フリガナ)<span>必須</span>
        </dt>
        <dd<?php if (isset($e) && ($e->hasErrorForId('personal_name_furi'))) { echo ' class="form_error"';}?>>
            <span class="event-place-lbl">
                <input class="w_260 personal_name_furi" maxlength="16" name="personal_name_furi" type="text" placeholder = "例）サガワ　ハナコ" value="<?php echo $rec001Out->personal_name_furi(); ?>" />
            </span>
        </dd>
    </dl>
    <div style="width: 100%;display: flex;">
        <dl style ="width: 50%;">
            <dt id="sei" style="width: 17%">
                性別<span>必須</span>
            </dt>
            <dd style="width: 17%" <?php if (isset($e) && ($e->hasErrorForId('sei'))) { echo ' class="form_error"';}?>>
                <ul class="clearfix">
                    <li class ="sei_li" style="width: 65%">
                        <label class="radio-label" for="male">
                            <input id="male" name="sei" type="radio" value="1" <?php if ($rec001Out->sei() == 1) echo 'checked = "checked"'; ?>/>
                            男性
                        </label>
                        <label class="radio-label" for="female">
                            <input id="female" name="sei" type="radio" value="2" <?php if ($rec001Out->sei() == 2) echo 'checked = "checked"'; ?>/>
                            女性
                        </label>
                    </li>
                </ul>
            </dd>
        </dl>
        <dl style ="width: 50%;">
            <dt id="age">
                ご年齢<span>必須</span>
            </dt>
            <dd id ="agedd"<?php if (isset($e) && ($e->hasErrorForId('age'))) { echo ' class="form_error"';}?>>
                <select name ="age">
                    <option value="">選択してください</option>
                    <?php foreach ($dispItemInfo["age_lbls"] as $key => $age) { ?>
                        <option value="<?php echo $key; ?>" <?php if ($rec001Out->age() == $key) echo 'selected'; ?>><?php echo $age; ?></option>
                    <?php } ?>
               </select>&nbsp;歳
            </dd>
        </dl>
    </div>
    <dl>
        <dt id="zip">
            郵便番号<span>必須</span>
        </dt>
        <dd<?php if (isset($e) && ($e->hasErrorForId('zip'))) { echo ' class="form_error"';}?>>
            <ul>
                <li style="display: inline-block;"> 〒
                    <input class="w_70" maxlength="3" placeholder = "例）136" name="zip1" type="text"  value="<?php echo $rec001Out->zip1();?>"  />
                    -
                    <input class="w_70" maxlength="4" name="zip2" placeholder = "例）0082" type="text" value="<?php echo $rec001Out->zip2();?>" />
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
                    <?php echo Sgmov_View_Rec_Input::_createPulldown($rec001Out->pref_cds(), $rec001Out->pref_lbls(), $rec001Out->pref_id());?>
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
                    <input style="width: 80%;" name="address" placeholder="例）江東区新砂" maxlength="14" type="text" value="<?php echo $rec001Out->address(); ?>" />
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
                    <input style="width: 80%;" name="building" maxlength="30" type="text" value="<?php echo $rec001Out->building(); ?>" />
                </li>
            </ul>
        </dd>
    </dl>
    <dl>
        <dt id="tel">
            電話番号（携帯電話番号）<span>必須</span>
        </dt>
        <dd<?php if (isset($e) && ($e->hasErrorForId('tel'))) { echo ' class="form_error"';}?>>
           <input name="tel" class="number-p-only" type="text" maxlength="15" data-pattern="^[0-9-]+$" placeholder="例）080-1111-2222" value="<?php echo $rec001Out->tel(); ?>" />
        </dd>
    </dl>
    <dl>
        <dt id="mail">
            メールアドレス<span>必須</span>
        </dt>
        <dd<?php if (isset($e) && ($e->hasErrorForId('mail'))) { echo ' class="form_error"';}?>>
            <input style="width: 60%;" maxlength="100" name="mail" type="text" placeholder="例）ringo@sagawa.com" value="<?php echo $rec001Out->mail(); ?>" />
        </dd>
    </dl>
    <dl style="display:none">
        <dt id="current_employment_status">
           現在の就業状況<span>必須</span>
        </dt>
        <dd<?php if (isset($e) && ($e->hasErrorForId('current_employment_status'))) { echo ' class="form_error"';}?>>
            <ul class="clearfix">
                <li style="width: 100%;">
                    <?php foreach($dispItemInfo['current_employment_status_lbls'] as $key => $value):?>
                        <label class="radio-label" for="current_employment_status<?php echo $key;?>">
                            <input id="current_employment_status<?php echo $key;?>" name="current_employment_status" type="radio" value="<?php echo $key;?>" <?php if ($rec001Out->current_employment_status() == $key) echo ' checked="checked"'; ?>/>
                        <?php echo $value;?>
                    </label>&nbsp;&nbsp;
                    <?php endforeach;?>   
                </li>
            </ul>
        </dd>
    </dl>
    <dl>
        <dt id="employ_cd">
            希望雇用形態<span>必須</span>
        </dt>
        <dd<?php if (isset($e) && ($e->hasErrorForId('employ_cd'))) { echo ' class="form_error"';}?>>
            <ul class="clearfix">
                <li style="width: 100%;">
                    <?php foreach($dispItemInfo['employ_cd_lbls'] as $key => $value):?>
                        <label class="radio-label" for="employ_cd<?php echo $key;?>">
                            <input id="employ_cd<?php echo $key;?>" name="employ_cd" type="radio" value="<?php echo $key;?>" <?php if ($rec001Out->employ_cd() == $key) echo ' checked="checked"'; ?>/>
                        <?php echo $value;?>
                    </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php endforeach;?>   
                    
                </li>
            </ul>
        </dd>
    </dl>
    <dl style="display:none">
        <dt id="contact_time">
           連絡可能な時間帯<span>必須</span>
        </dt>
        <dd<?php if (isset($e) && ($e->hasErrorForId('contact_time'))) { echo ' class="form_error"';}?>>
            <ul >
                <li >
                    <?php foreach($dispItemInfo['contact_time_lbls'] as $key => $value):?>
                        <label class="radio-label" for="contact_time<?php echo $key;?>">
                            <input id="contact_time<?php echo $key;?>" name="contact_time[<?php echo $key;?>]" type="checkbox" value="<?php echo $key;?>" <?php if (in_array($key, $rec001Out->contact_time())) echo ' checked="checked"'; ?>/>
                        <?php echo $value;?>
                    </label>&nbsp;&nbsp;
                    <?php endforeach;?>   
                </li>
            </ul>
        </dd>
    </dl>
    <dl>
        <dt id="center_id">
            希望勤務地・営業所<span>必須</span>
        </dt>
        <dd <?php if (isset($e) && ($e->hasErrorForId('center_id'))) { echo ' class="form_error"';}?>>
            <ul class="clearfix">
                <li style="width: 100%" class = "center_id">
                    <?php foreach($dispItemInfo['center_id_lbls'] as $key => $value):?>
                        <label class="radio-label center_id_label" for="center_id_radio<?php echo $key;?>" 
                             style="white-space: nowrap;display: inline;"
                            <?php if (!in_array($value, $dispItemInfo['center_id_lbls_control'])){
                                 echo ' style=font-weight:normal;color:#DDD'; 
                             }else{
                                echo ' style=color:black;font-weight:bold'; 
                             }
                            ?>>
                            <input id="center_id_radio<?php echo $key;?>" class="center_rd center_<?php echo $value;?>" name="center_id" type="radio" value="<?php echo $key;?>" data-href= "<?php echo $value;?>"   data-value= "" <?php if ($rec001Out->center_id() !== "" && $rec001Out->center_id() == $key) echo ' checked="checked"'; ?>
                             <?php if (!in_array($value, $dispItemInfo['center_id_lbls_control'])) echo ' disabled="disabled"'; ?>/>
                            <?php echo @str_replace('_', '　', $value);?>
                        </label>
                    <?php endforeach;?>
                </li>
            </ul>
            <input type ="hidden" name ="center_id_hidden" id = "center_id_hidden" value="<?php echo $rec001Out->center_id_hidden(); ?>" >
        </dd>
    </dl>
    <dl>
        <dt id="occupation_cd">
            希望職種<span>必須</span>
        </dt>
        <dd <?php if (isset($e) && ($e->hasErrorForId('occupation_cd'))) { echo ' class="form_error"';}?>>
            <ul class="clearfix occupation_cd">
                <li style="width: 100%" class ="occup_id">
                    <?php foreach($dispItemInfo['occupation_cd_lbls'] as $key => $value):?>
                        <label class="radio-label" class = "occup_id" for="occupation_cd<?php echo $key;?>"

                             <?php if (!in_array($value, $dispItemInfo['occupation_cd_lbls_control'])){
                                 echo ' style=font-weight:normal;color:#DDD'; 
                             }else{
                                echo ' style=color:black;font-weight:bold'; 
                             }
                            ?>
                            >
                            <input id="occupation_cd<?php echo $key;?>" class ="occup_radio occup_<?php echo $value;?>" name="occupation_cd" type="radio" value="<?php echo $key;?>" data-href= "<?php echo $value;?>"
                             data-value= ""  <?php if ($rec001Out->occupation_cd() !== "" && $rec001Out->occupation_cd() == $key) echo ' checked="checked"'; ?>
                            <?php if (!in_array($value, $dispItemInfo['occupation_cd_lbls_control'])) echo ' disabled="disabled"'; ?>
                            />
                            <?php echo $value;?>
                        </label>
                    <?php endforeach;?>
                </li>
            </ul>
             <input type ="hidden" name ="occupation_cd_hidden" id = "occupation_cd_hidden" value="<?php echo $rec001Out->occupation_cd_hidden(); ?>">
        </dd>
    </dl>
    <dl style="border-bottom: none;">
        <dt id="question">
            ご質問など
        </dt>
        <dd <?php if (isset($e) && ($e->hasErrorForId('question'))) { echo ' class="form_error"';}?>>
            <textarea class="w100p" cols="70" name="question" rows="9"><?php echo $rec001Out->question(); ?></textarea>
            <p>※1000文字まででお願いいたします。</p>
        </dd>
    </dl>
</div>