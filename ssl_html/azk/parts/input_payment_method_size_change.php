    <?php if ($eve001Out->comiket_payment_method_cd_sel() == '2') : // クレジット ?>
            <h4 class="table_title">クレジットお支払い情報</h4>
            <div class="dl_block" id ="payment_method" >
                <dl>
                    <dt>お支払い総額</dt>
                    <dd>
                        ￥<?php echo @number_format($eve001Out->delivery_charge()).PHP_EOL; ?>
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
                <dt>お支払い総額</dt>
                <dd>￥<?php echo @number_format($eve001Out->delivery_charge()).PHP_EOL; ?></dd>
            </dl>
        </div>
       <strong class="red">※電子マネーでのお支払いは、￥10,000を超える場合はお取り扱い出来ません。</strong>
    <?php endif; ?>