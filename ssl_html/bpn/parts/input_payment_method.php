                    <div class="payment_method clearfix<?php if (isset($e) && $e->hasErrorForId('payment_method')) { echo ' form_error'; } ?>" id="payment_method">
                        <span>お支払い方法：</span>
                        <label class="radio-label" for="pay_card">
                            <input checked="checked" class="radio-btn" id="pay_card" name="comiket_payment_method_cd_sel" type="radio" value="2" />クレジットカード払いのみ
                        </label><br>
                        <?php if(@!empty($bpn001Out) && $bpn001Out->eventsub_cd_sel() == "302"){ ?>
                            <label class = "" style="word-break: keep-all;">商品引き渡し方法：各ブースに置かせていただきます。</label>
                        <?php }else{?>
                            <label class="lbl-booth">商品引き渡し場所：</label><label style="word-break: keep-all;">各ブースに置かせていただきます。</label>
                        <?php }?>
                    </div>
                    <div class="pay_available_attention">
                        ※ご利用可能なクレジットカードは<a href="/pdf/available_card.pdf" target="_blank" style="color:blue;text-decoration: underline;">こちら</a><br/>
                    </div>
                    <br/>
                    <div class="pay_digital_money_attention" style="display:none;">
                        ※ご利用可能な電子マネーは<a href="/pdf/available_degital_money.pdf" target="_blank" style="color:blue;text-decoration: underline;">こちら</a><br/><br/>
                        <br>
                        <br>
                        <strong class="red">運賃目安料金表は<a href="https://www2.sagawa-exp.co.jp/send/fare/input/" target="_blank" style="color:blue;">こちら</a></strong>
                        <br>
                    </div>
