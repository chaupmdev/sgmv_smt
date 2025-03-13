    <?php if ($bpn001Out->comiket_payment_method_cd_sel() == '2') : // クレジット ?>

                        <h4 class="table_title">クレジットお支払い情報</h4>
                        <div class="dl_block">
                            <dl>
                                <dt>合計金額</dt>
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

<?php if ($bpn001Out->comiket_payment_method_cd_sel() == '3') : // 電子マネー ?>
   <h4 class="table_title">電子マネー情報</h4>
    <div class="dl_block">
        <dl>
            <dt>お支払い総額（仕分け特別料金含む）</dt>
            <dd>￥<?php echo @number_format($dispItemInfo['amount_tax']).PHP_EOL; ?></dd>
        </dl>
    </div>
 <?php endif; ?>

    <?php if ($bpn001Out->comiket_payment_method_cd_sel() == '1') : // コンビニ前払い ?>
        <h4 class="table_title">コンビニ前払い情報</h4>
        <div class="dl_block">
            <dl>
                <dt>合計金額</dt>
                <dd>￥<?php echo @number_format($dispItemInfo['amount_tax']).PHP_EOL; ?></dd>
            </dl>
            <dl>
                <dt>お支払い先</dt>
                <dd>
                    <?php if ($bpn001Out->comiket_convenience_store_cd_sel() == '1') : ?>
                        セブンイレブン
                    <?php elseif ($bpn001Out->comiket_convenience_store_cd_sel() == '2') : ?>
                        ローソン、セイコーマート、ファミリーマート、ミニストップ
                    <?php elseif ($bpn001Out->comiket_convenience_store_cd_sel() == '3') : ?>
                        デイリーヤマザキ
                    <?php endif; ?>
                </dd>
            </dl>
        </div>
    <?php endif; ?>
        
    <?php if ($bpn001Out->comiket_payment_method_cd_sel() == '4') : // コンビニ後払い ?>
        <h4 class="table_title">コンビニ後払い情報</h4>
        <div class="dl_block">
            <dl>
                <dt>合計金額</dt>
                <dd>￥<?php echo @number_format($dispItemInfo['amount_tax']).PHP_EOL; ?></dd>
            </dl>
        </div>
    <?php endif; ?>