    <?php if ($eve001Out->comiket_payment_method_cd_sel() == '2') : // クレジット ?>

                        <h4 class="table_title">クレジットお支払い情報</h4>
                        <div class="dl_block">
                            <dl>
                                <dt>お支払総額</dt>
                                <dd>
                                    ￥<?php echo @number_format($dispItemInfo['amount_tax']).PHP_EOL; ?>
                                </dd>
                            </dl>
                            <dl>
                                <dt>お支払い方法</dt>
                                <dd>1回</dd>
                            </dl>
                        </div>


    <?php endif; ?>

    <?php if ($eve001Out->comiket_payment_method_cd_sel() == '3') : // 電子マネー ?>
       <h4 class="table_title">電子マネー情報</h4>
        <div class="dl_block">
            <dl>
                <dt>お支払総額</dt>
                <dd>￥<?php echo @number_format($dispItemInfo['amount_tax']).PHP_EOL; ?></dd>
            </dl>
        </div>
    <?php endif; ?>

    <?php if ($eve001Out->comiket_payment_method_cd_sel() == '1') : // コンビニ前払い ?>
        <h4 class="table_title">コンビニ前払い情報</h4>
        <div class="dl_block">
            <dl>
                <dt>お支払総額</dt>
                <dd>￥<?php echo @number_format($dispItemInfo['amount_tax']).PHP_EOL; ?></dd>
            </dl>
            <dl>
                <dt>お支払い先</dt>
                <dd>
                    <?php if ($eve001Out->comiket_convenience_store_cd_sel() == '1') : ?>
                        セブンイレブン
                    <?php elseif ($eve001Out->comiket_convenience_store_cd_sel() == '2') : ?>
                        ローソン、セイコーマート、ファミリーマート、ミニストップ
                    <?php elseif ($eve001Out->comiket_convenience_store_cd_sel() == '3') : ?>
                        デイリーヤマザキ
                    <?php endif; ?>
                </dd>
            </dl>
        </div>
    <?php endif; ?>
        
    <?php if ($eve001Out->comiket_payment_method_cd_sel() == '4') : // コンビニ後払い ?>
        <h4 class="table_title">コンビニ後払い情報</h4>
        <div class="dl_block">
            <dl>
                <dt>お支払総額</dt>
                <dd>￥<?php echo @number_format($dispItemInfo['amount_tax']).PHP_EOL; ?></dd>
            </dl>
        </div>
    <?php endif; ?>

<?php /*

                    <div class="payment_method" id="payment_method">
                        <label class="radio-label" for="pay_card">
                            <?php if ($eve001Out->comiket_payment_method_cd_sel() == '2') : ?>
                                クレジットカード
                            <?php endif; ?>
                        </label>
                        <label class="radio-label" for="pay_convenience_store">
                            <?php if ($eve001Out->comiket_payment_method_cd_sel() == '1') : ?>
                                コンビニ前払い
                            <?php endif; ?>
                        </label>
                        <label class="radio-label" for="pay_convenience_store_laterpay" > <!--style="color:#999;"-->
                            <?php if ($eve001Out->comiket_payment_method_cd_sel() == '4') : ?>
                                コンビニ後払い
                            <?php endif; ?>
                        </label>
                        <label class="radio-label pay_digital_money" for="pay_digital_money">
                            <?php if ($eve001Out->comiket_payment_method_cd_sel() == '3') : ?>
                                電子マネー
                            <?php endif; ?>
                        </label>
                        <input<?php if ($eve001Out->comiket_payment_method_cd_sel() == '5') echo ' checked="checked"'; ?> style="display:none;" class="radio-btn" id="pay_convenience_store_comppay" name="comiket_payment_method_cd_sel" type="radio" value="5" />
                        <div id="convenience">
                            <?php if ($eve001Out->comiket_payment_method_cd_sel() == '4') : ?>
                                <?php if ($eve001Out->comiket_convenience_store_cd_sel() === '1'): ?>セブンイレブン<?php endif; ?>
                                <?php if ($eve001Out->comiket_convenience_store_cd_sel() === '2'): ?>ローソン、セイコーマート、ファミリーマート、ミニストップ<?php endif; ?>
                                <?php if ($eve001Out->comiket_convenience_store_cd_sel() === '3'): ?>デイリーヤマザキ<?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
 * 
 * 
 */ ?>