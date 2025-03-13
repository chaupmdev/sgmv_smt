                    <div class="payment_method clearfix<?php if (isset($e) && $e->hasErrorForId('payment_method')) { echo ' form_error'; } ?>" id="payment_method">
                        <span>ご希望のお支払い方法をお選びください。</span>
                        <label class="radio-label pay_digital_money" for="pay_digital_money">
                            <input class="radio-btn" id="pay_digital_money" name="comiket_payment_method_cd_sel" type="radio" value="3" checked="checked"/>
                            <?php if(@!empty($bpn001Out) && ($bpn001Out->shohin_pattern() == "2")){ ?>
                                    電子マネーのみ
                                    <!--現金-->
                                    <span class ="pl24px" style="line-height: 10px !important;white-space: nowrap;">（宅配受付カウンターでタッチ）</span>
                            <?php }else{ ?>
                                    電子マネー
                            <?php } ?>
                        </label><br>
                        <?php 
                        if(@!empty($bpn001Out)){
                            if($bpn001Out->eventsub_cd_sel() == "810" && $bpn001Out->shohin_pattern() == "2"){ ?>
                                <label  class ="wbka dsp-flex ln-height40px">商品受取場所： 
                                    <div class = "dsp-inblck ws-nowrap"><img src="/images/common/img_icon_pdf.gif" class = "vam" width="18" height="21" alt="">
                                        <a style="color: blue;" href="/bpn/pdf/manual/会場内宅配受付カウンター場所.pdf<?php echo '?' . $strSysdate; ?>" target="_blank">宅配受付カウンター</a></div>
                                </label>
                            <?php } elseif($bpn001Out->shohin_pattern() == "2"){ ?>
                               <!--  <label class ="wbka dsp-flex ln-height40px">商品受取場所： 
                                    <div class = "dsp-inblck ws-nowrap"><img src="/images/common/img_icon_pdf.gif" class = "vam" width="18" height="21" alt="">
                                        <a style="color: blue;" href="/bpn/pdf/manual/宅配受付カウンター.pdf<?php echo '?' . $strSysdate; ?>" target="_blank">宅配受付カウンター</a></div>
                                </label> -->
                            <?php } elseif (@!empty($bpn001Out) && $bpn001Out->shohin_pattern() == "1" && $bpn001Out->event_cd_sel() == '301'){ ?>
                               <label class = "wbka">商品引き渡し方法：各ブースに置かせていただきます。</label>
                            <?php } ?>

                        <?php }else { ?>
                            <label class = "wbka">商品引き渡し方法：ビッグサイト １Ｆ西アトリウム</label>
                        <?php } ?>
                    </div>
                    <style type="text/css">
                        @media only screen and (max-width: 768px){
                            label.radio-label {
                                height: auto !important;
                                line-height: normal !important;
                            }
                        }
                        .payment_method span {
                            line-height: 26px;
                        }

                        .payment_method {
                                padding: 15px 25px !important;
                        }
                    </style>
                    

