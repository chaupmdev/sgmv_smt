    <?php if ($eve001Out->comiket_payment_method_cd_sel() == '2') : // クレジット ?>
            <h4 class="table_title">クレジットお支払い情報</h4>
            <div class="dl_block" id ="payment_method" >
                <dl>
                    <dt>お支払い総額（仕分け特別料金含む）</dt>
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
        <div class="dl_block" id ="payment_method">
            <dl>
                <dt>お支払い総額（仕分け特別料金含む）</dt>
                <dd>￥<?php echo @number_format($dispItemInfo['amount_tax']).PHP_EOL; ?></dd>
            </dl>
        </div>
       <strong class="red">※電子マネーでのお支払いは、￥10,000を超える場合はお取り扱い出来ません。</strong>
    <?php endif; ?>
       
    <?php if ($eve001Out->comiket_payment_method_cd_sel() == '1') : // コンビニ前払い ?>
        <h4 class="table_title">コンビニ前払い情報</h4>
        <div class="dl_block" id ="payment_method">
            <dl>
                <dt>お支払い総額（仕分け特別料金含む）</dt>
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

    <?php if ($eve001Out->comiket_payment_method_cd_sel() == '4') : // コンビニ後払 ?>
        <h4 class="table_title">コンビニ後払い情報</h4>
        <div class="dl_block" id ="payment_method">
            <dl>
                <dt>お支払い総額（仕分け特別料金含む）</dt>
                <dd>￥<?php echo @number_format($dispItemInfo['amount_tax']).PHP_EOL; ?></dd>
            </dl>
        </div>
        <strong class="red">※ コンビニ後払いについて：決済時に、お申し込みに時間がかかる場合又は、お申し込みができない場合がございますのでご了承ください。</strong>
    <?php endif; ?>