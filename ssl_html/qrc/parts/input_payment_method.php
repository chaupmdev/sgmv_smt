                    <div class="payment_method clearfix<?php if (isset($e) && $e->hasErrorForId('payment_method')) {
                                                            echo ' form_error';
                                                        } ?>" id="payment_method">
                        <span>ご希望のお支払い方法をお選びください。</span>
                        <label class="radio-label" for="pay_card">
                            <input<?php if ($qrc001Out->comiket_payment_method_cd_sel() == '2') echo ' checked="checked"'; ?> class="radio-btn" id="pay_card" name="comiket_payment_method_cd_sel" type="radio" value="2" />
                            クレジットカード
                        </label>
                        <label class="radio-label pay_digital_money" for="pay_digital_money">
                            <input<?php if ($qrc001Out->comiket_payment_method_cd_sel() == '3') echo ' checked="checked"'; ?> class="radio-btn" id="pay_digital_money" name="comiket_payment_method_cd_sel" type="radio" value="3" />
                            電子マネー
                        </label>
                        <?php /* 法人売掛 */ ?>
                        <input<?php if ($qrc001Out->comiket_payment_method_cd_sel() == '5') echo ' checked="checked"'; ?> style="display:none;" class="radio-btn" id="pay_convenience_store_comppay" name="comiket_payment_method_cd_sel" type="radio" value="5" />
                        <div id="convenience" style="display:none;">
                            <select name="comiket_convenience_store_cd_sel">
                                <option<?php if ($qrc001Out->comiket_convenience_store_cd_sel() !== '1' && $qrc001Out->comiket_convenience_store_cd_sel() !== '2' && $qrc001Out->comiket_convenience_store_cd_sel() !== '3') echo ' selected="selected"'; ?> value="">コンビニを選択してください</option>
                                    <option<?php if ($qrc001Out->comiket_convenience_store_cd_sel() === '1') echo ' selected="selected"'; ?> value="1">セブンイレブン</option>
                                        <option<?php if ($qrc001Out->comiket_convenience_store_cd_sel() === '2') echo ' selected="selected"'; ?> value="2">ローソン、セイコーマート、ファミリーマート、ミニストップ</option>
                                            <option<?php if ($qrc001Out->comiket_convenience_store_cd_sel() === '3') echo ' selected="selected"'; ?> value="3">デイリーヤマザキ</option>
                            </select>
                        </div>
                    </div>
                    <div class="pay_available_attention">
                        ※ご利用可能なクレジットカードは<a href="/pdf/available_card.pdf" target="_blank" style="color:blue;text-decoration: underline;">こちら</a><br />
                    </div>
                    <div class="convenience_store_laterpay_attention">

                    </div>
                    <br />
                    <div class="pay_digital_money_attention" style="">
                        ※ご利用可能な電子マネーは<a href="/pdf/available_degital_money.pdf" target="_blank" style="color:blue;text-decoration: underline;">こちら</a><br /><br />
                        <strong class="red">※ 電子マネーでのお支払いは、残高不足にご注意ください。なお1万円を超える場合は、お取り扱いできません。</strong>
                        <br>
                    </div>
                    <br>
                    <strong class="red">運賃目安料金表は<a href="https://www2.sagawa-exp.co.jp/send/fare/input/" target="_blank" style="color:blue;">こちら</a></strong>
                    <br>
                    <br>
