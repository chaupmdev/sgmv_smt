                    <div class="payment_method clearfix<?php if (isset($e) && $e->hasErrorForId('payment_method')) { echo ' form_error'; } ?>" id="payment_method">
                        <span>ご希望のお支払い方法をお選びください。</span>
                        <label class="radio-label" for="pay_card">
                            <input<?php if ($eve001Out->comiket_payment_method_cd_sel() == '2') echo ' checked="checked"'; ?> class="radio-btn" id="pay_card" name="comiket_payment_method_cd_sel" type="radio" value="2" />
                            クレジットカード
                        </label>

                        <label class="radio-label pay_digital_money" for="pay_digital_money">
                            <input<?php if ($eve001Out->comiket_payment_method_cd_sel() == '3') echo ' checked="checked"'; ?> class="radio-btn" id="pay_digital_money" name="comiket_payment_method_cd_sel" type="radio" value="3" />
                            電子マネー
                        </label>
                        <?php /* 法人売掛 */ ?>
                        <input<?php if ($eve001Out->comiket_payment_method_cd_sel() == '5') echo ' checked="checked"'; ?> style="display:none;" class="radio-btn" id="pay_convenience_store_comppay" name="comiket_payment_method_cd_sel" type="radio" value="5" />
                    </div>
                    <div class="pay_digital_money_attention" style="display:none;">
                        <!--<strong class="red" >※ 電子マネーは搬出のみ選択可能です。</strong><br/><br/>-->
                        <strong class="red">※ 電子マネーでのお支払いは、￥10,000を超える場合はお取り扱い出来ません。</strong>
                        <br/>
                        <br/>
                    </div>
