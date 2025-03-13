<html dir="ltr" lang="ja-jp" xml:lang="ja-jp">
<head>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
</head>
<div class="dl_block input-buppan comiket_block input-buppan-contents" style="">

                                <dl class="service-buppan-item" service-id="1">
                                    <dd style="background-color: #d5e8f7;">
                                        <div class="comiket-box-buppan-num comiket-box-buppan-num-dmy">
                                            <!-- 個人・法人 両方 -->

                                            <style>
                                                .sp_br {
                                                    display: none;
                                                }
                                                .buppan_tbl_td {
                                                    width: 50%;
                                                }
                                                @media screen and (max-width:420px) {
                                                    .sp_br {
                                                        display: block;
                                                    }
                                                    .buppan_tbl_td {
                                                        width: 100%;
                                                    }
                                                }
                                            </style>
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td class="buppan_tbl_td vam" id ="comiket_box_buppan_num_ary">
                                                            <table id ="comiket_box_buppan_num_ary_max_err">
                                                                <tbody>
                                                                <?php $unsetFlg = false;
                                                                    if(isset($dispItemInfo["input_buppan_lbls"]["expiry_all"])){
                                                                        unset($dispItemInfo["input_buppan_lbls"]["expiry_all"]);
                                                                        $unsetFlg = true; 
                                                                    }
                                                                ?>
                                                                <?php foreach($dispItemInfo["input_buppan_lbls"] as $key => $value): 

                                                                    $suuryo = $bpn001Out->comiket_box_buppan_num_ary($value["id"]);
                                                                    if(empty($suuryo)){
                                                                        $suuryo = 0;
                                                                    }
                                                                    // comiket_box_buppan_num_ary_101
                                                                    ?>
                                                                    <tr style="border-bottom: 1px dotted #000;" 
                                                                        <?php 
                                                                            if (isset($e) && 
                                                                                ($e->hasErrorForId('comiket_box_buppan_num_ary_'.$value['id']) || $e->hasErrorForId('comiket_box_buppan_num_ary_max_err') || $e->hasErrorForId('comiket_box_buppan_num_ary'))
                                                                            ) {
                                                                            echo ' class="form_error"';
                                                                            }
                                                                        ?>
                                                                        id = "comiket_box_buppan_num_ary_<?php echo $value['id']; ?>"
                                                                    >
                                                                        <td class="comiket_box_item_name">
                                                                            <?php echo $value["name_display"]; ?>
                                                                            <br>
                                                                            <b class = "clr-rd lhn">
                                                                                <?php echo $value["cost_tax"]; ?>円(税込)
                                                                            </b>
                                                                        </td>
                                                                        <td class="comiket_box_item_value ws_nowrap">
                                                                            <?php if($value["soldOutCnt"] == 9999 ): ?>
                                                                                <span class="fwb clr-rd soldout-span">SOLD OUT</span>
                                                                            <?php else: ?>
                                                                                <input autocapitalize="off" 
                                                                                       class="number-only comiket_box_item_value_input suuryo_<?php echo $value['id']?>"
                                                                                       maxlength="2" inputmode="numeric" 
                                                                                       name="comiket_box_buppan_num_ary[<?php echo $value['id']?>]" 
                                                                                       data-pattern="^d+$" 
                                                                                       placeholder="例）1" 
                                                                                       type="text" 
                                                                                       value="<?php echo $suuryo;?>" 
                                                                                       style="min-width: 50px;">
                                                                                <div class ="vam" style="display: inline-block;display: inline-grid;">
                                                                                    <button type="button" class ="incdec-btn plus" data-id="<?php echo $value['id']; ?>">
                                                                                        <i class="fas fa-caret-up"></i>
                                                                                    </button>
                                                                                    <button type="button" class ="incdec-btn minus mt3px" data-id="<?php echo $value['id']; ?>">
                                                                                        <i class="fas fa-caret-down"></i>
                                                                                    </button>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        </td>
                                                                    </tr>

                                                                    <input type="hidden" name="comiket_box_buppan_ziko_shohin_cd_ary[<?php echo $value['id']?>]"  value="<?php echo $value['ziko_shohin_cd']; ?>" />
                                                                <?php endforeach;?>
                                                                </tbody>
                                                                <?php  if($unsetFlg){ $dispItemInfo["input_buppan_lbls"]["expiry_all"] = "1";}?>
                                                            </table>
                                                        </td>

                                                        <?php ?>
<!-- 
                                                        <td class = "vam tar">
                                                            <img class="dispSeigyoPC" src="/bpn/images/about_boxsize.png" width="70%" style="margin: auto;">
                                                        </td>
 -->
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                      
<!--                                         <div class="dispSeigyoSP" style="margin-top: 1em;">
                                            <img src="/bpn/images/about_boxsize.png" width="250px" style="margin-top: 1em;">
                                        </div> -->
                                        <br>
                                        <div class="buppan_example_boxsize example_boxsize" style="">
                                            <a href="/bpn/pdf/example/example_box_size.pdf" class ="clr_blue" target="_blank">サイズの目安はこちらをご確認ください</a>
                                        </div>
                                    </dd>
                                </dl>
                            </div>

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
        
        if(isNaN(suuryoVal) && opt == "2"){
            suuryoVal = 0;
        }else if(isNaN(suuryoVal)){
            suuryoVal = 1;
        }else if(suuryoVal < 0){
            suuryoVal = 0;
        }else if(isNaN(suuryoVal)){
            suuryoVal = 0;
        }else if(suuryoVal >= 99){
            suuryoVal = 99;
        }

        $(".suuryo_"+dataId).val(suuryoVal);
    }
</script>    