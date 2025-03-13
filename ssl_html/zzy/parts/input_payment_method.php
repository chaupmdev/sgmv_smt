                    <div class="payment_method clearfix<?php if (isset($e) && $e->hasErrorForId('payment_method')) { echo ' form_error'; } ?>" id="payment_method">
                        <span>ご希望のお支払い方法をお選びください。</span>
                        <label class="radio-label pay_digital_money" for="pay_digital_money">
                            <input <?php if ($eve001Out->comiket_payment_method_cd_sel() == '3' || $eve001Out->comiket_payment_method_cd_sel() == '') echo ' checked="checked"'; ?> class="radio-btn" id="pay_digital_money" name="comiket_payment_method_cd_sel" type="radio" value="3" />
<!--                            電子マネー-->
                            現金
                        </label>
                    <div class="convenience_store_laterpay_attention">
                    </div>
                   <!--  <div class="pay_digital_money_attention" style="">
                        ※ご利用可能な電子マネーは<a href="/pdf/available_degital_money.pdf" target="_blank" style="color:blue;text-decoration: underline;">こちら</a><br/><br/>
                        <strong class="red">※ 電子マネーでのお支払いは、残高不足にご注意ください。なお1万円を超える場合は、お取り扱いできません。</strong>
                        <br>
                    </div> -->
                   <!--  <strong class="red">運賃目安料金表は<a href="https://www2.sagawa-exp.co.jp/send/fare/input/" target="_blank" style="color:blue;">こちら</a></strong> -->
                    </div>