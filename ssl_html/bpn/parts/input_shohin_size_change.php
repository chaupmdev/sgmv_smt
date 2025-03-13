<html dir="ltr" lang="ja-jp" xml:lang="ja-jp">
<head>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
 
    <link href="/css/plan.css" rel="stylesheet" type="text/css" />
    <script charset="UTF-8" type="text/javascript" src="/js/jquery-2.2.0.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/lodash.min.js"></script>
    <!--<![endif]-->
    <script src="/js/common.js"></script>
    <script src="/js/smooth_scroll.js"></script>
    <script src="/js/ga.js" type="text/javascript"></script>
</head>
<body>
<style type="text/css">
    .table_color_layout table td{
        padding: 4px !important;
    }
</style>
    <div class="wrap checksheet clearfix">
            <div class="section">
                <div class="table_color_layout">
                    商品件数全<?php echo count($dispItemInfo["input_buppan_lbls"]);?>件
                    <?php $unsetFlg = false;
                        if(isset($dispItemInfo["input_buppan_lbls"]["expiry_all"])){
                        unset($dispItemInfo["input_buppan_lbls"]["expiry_all"]);
                        $unsetFlg = true; ?>
                        <br><br><strong style="color: red;font-size: 1.2em;font-weight: bold;">全ての商品が申込期間範囲外です。</strong>
                    <?php } ?>
                    <br><br>
                    <table id ="comiket_box_buppan_num_ary_max_err" 
                    <?php if (isset($e) && ($e->hasErrorForId('comiket_box_buppan_num_ary_max_err') || $e->hasErrorForId('comiket_box_buppan_num_ary'))) {
                                    echo ' class="form_error"';
                                    }?>>
                        <thead>
                            <tr class ="tal">
                                <th colspan="3" class ="tal" style="background-color: #fff7b4">商品一覧</th>
                            </tr>            
                        </thead>
                        <tbody id = "comiket_box_buppan_num_ary">
                            <?php $i = 1;
                                $totalCnt = 0;
                                $expiryCnt = 0;
                            foreach($dispItemInfo["input_buppan_lbls"] as $key => $value){
                                
                                $now = new DateTime();
                                $termFrom = new DateTime($value['term_fr']);
                                $termTo = new DateTime($value['term_to']);
                                $termFlg = false;
                                if(empty($value['term_to']) || empty($value['term_fr'])){
                                    $termFlg = true;
                                }elseif($termTo->format('Y') == "9999" && $termFrom->format('Y') == "1900"){
                                    $termFlg = true;
                                }

                                $suuryo = $bpn001Out->comiket_box_buppan_num_ary($value["id"]);
                                if(empty($suuryo)){
                                    $suuryo = 0;
                                }

                                $style = 'style="margin: 3px;margin-top:20px"';
                                if(!$termFlg):
                                    $style = 'style="margin: 3px;"';
                                endif;

                               ?>
                               <tr>
                                    <td rowspan="2" class ="tac"> 
                                        <div class ="tal fwb lbl mrg3px"><?php echo $i; ?></div>

                                        <div class = "image-div">
                                            <img src="<?php echo $value['img_path_smp']; ?>" class = "cls-img" alt="">
                                        </div>

                                        <div class = "mrg5px">
                                            <a href="" class="img-link lbl"><?php echo $value["attached_document"]; ?></a>
                                        </div>
                                    </td>
                                   
                                   <?php if(!$termFlg):?>
                                        <td>
                                            <?php if($value['expiry_status'] == '0'):?>
                                                <span class="lbl cls-kikan-lbl">申込期間：<div class = "period-div"><?php echo date("Y/m/d H:i",strtotime($value['term_fr']))."～".date("Y/m/d H:i",strtotime($value['term_to']));?></div>
                                                        </span>
                                            <?php else: ?>
                                                <li class ="cls-kikan-lbl clr-rd">申込期間範囲外です。</li>
                                                <li class ="cls-kikan-lbl clr-rd">申込期間：<div class = "period-div"><?php echo date("Y/m/d H:i",strtotime($value['term_fr']))."～".date("Y/m/d H:i",strtotime($value['term_to']));?></div></li>
                                            <?php endif; ?>
                                        </td>
                                    <?php endif; ?>

                                </tr>
                                <tr <?php if (isset($e) && ($e->hasErrorForId('comiket_box_buppan_num_ary_'.$value['id']))) {
                                    echo ' class="form_error"';
                                    }?>  id = "comiket_box_buppan_num_ary_<?php echo $value['id']; ?>">

                                        <td class ="vat" <?php if(!$termFlg):?> colspan="2" <?php endif;?>>

                                            <div <?php echo $style; ?>>
                                                <?php echo $value["category"]; ?>
                                                <span class = "lbl"><b>デザインフェスタ向け</b></span><br>
                                                <span class = "lbl"><b>【数量限定】</b></span><?php echo $value["name"]; ?>
                                            </div>

                                            <div class ="cls-price-div">
                                                <div class="item">
                                                    <img src="/bpn/images/price1.png"  alt="">
                                                </div>
                                                <span class = "cls-price-lbl lbl"><strong>8,800円</strong>&nbsp;<div style="display: inline-block;display: contents;" class ="amt-nl">⇒&nbsp;<strong class = "clr-rd"><?php echo number_format($value["cost_tax"]); ?>円(税込)</strong></div></span>
                                            </div>

                                            <div class="dflt lbl">カウンター上で使える飛沫防止対策。軽量で、女性でも簡単に組み立てられます。飛沫をブロックするシートを巻き上げ、お好きな高さで付属のクリップでとめると、手元で商品の受け渡しをスムーズに行えます。</div>
                                            <br>
                                            <span class="clr-rd fwb lbl" style="line-height:normal;"><strong>１回の注文でお一人様１台のご注文になります。</strong></span>
                                            <br><br>
                                            <div class = "suuryo-div lbl tac" style="width:60% !important;">
                                                数量&emsp;<span class="fwb" style="font-size: 13px;">1</span>
                                                <input autocapitalize="off" maxlength="2" name="comiket_box_buppan_num_ary[<?php echo $value['id']?>]" class="suuryo_<?php echo $value['id']; ?> suuryo-input" type="hidden" 
                                                        value="1" />
                                               <!--  <div class ="vam" style="display: none;">
                                                    <button type="button" class ="incdec-btn plus" data-id="<?php //echo $value['id']; ?>"><i class="fas fa-caret-up"></i></button>
                                                    <button type="button" class ="incdec-btn minus mt3px" data-id="<?php //echo $value['id']; ?>"><i class="fas fa-caret-down"></i></button>
                                                </div> -->
                                            </div>

                                             <div class ="vam mrg5">
                                                <?php echo $value["shohin_detail"]; ?>
                                            </div>
                                            <input type="hidden" name="comiket_box_buppan_ziko_shohin_cd_ary[<?php echo $value['id']?>]"  value="<?php echo $value['ziko_shohin_cd']; ?>" />
                                        </td>
                                    </tr>
                            <?php $i++;  } 
                            if($unsetFlg){
                                $dispItemInfo["input_buppan_lbls"]["expiry_all"] = "1";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</div><!--main-->
</body>
</html>
<style type="text/css">
    .product-name{
        display: inline-block;
    }
</style>
<script type="text/javascript">
    $(".plus").on('click', function(){
        addOrMinusSuuryo($(this).attr("data-id"), "1");
    });

    $(".minus").on('click', function(){
        addOrMinusSuuryo($(this).attr("data-id"), "2");
    });

    function addOrMinusSuuryo(dataId, opt){
        var suuryoVal = parseInt($(".suuryo_"+dataId).val());
        if(opt == "1"){
            suuryoVal = suuryoVal+1;
        }else{
            suuryoVal = suuryoVal-1;
        }
        
        if(suuryoVal < 0){
            suuryoVal = 0;
        }else if(isNaN(suuryoVal)){
            suuryoVal = 0;
        }else if(suuryoVal > 9999){
            suuryoVal = 9999;
        }

        $(".suuryo_"+dataId).val(suuryoVal);
    }
</script>