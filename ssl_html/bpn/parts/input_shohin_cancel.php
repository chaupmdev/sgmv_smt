<h4 class="table_title" id ="comiket_box_buppan_num_ary">商品情報</h4>
<div class="dl_block">
    <dl>
        <dt>商品一覧</dt>
        <dd >
          <?php $unsetFlg = false;
            if(isset($dispItemInfo['input_buppan_lbls']["expiry_all"])){
              unset($dispItemInfo['input_buppan_lbls']["expiry_all"]);
              $unsetFlg = true;
            }?>
            <table>
              <?php foreach($dispItemInfo['input_buppan_lbls'] as $key => $val):
                  $boxNum = $bpn001Out->comiket_box_buppan_num_ary($val["id"]); 
                  if(!empty($boxNum)) :?>
                  <tr>
                      <td class='comiket_box_item_name'>
                         <b><?php echo empty($val["name"]) ? "" : $val["name"]; ?></b>&nbsp;
                      </td>
                      <td class='comiket_box_item_value'>
                         <b><?php echo $boxNum;?>枚</b> &nbsp;
                      </td>
                  </tr>
              <?php endif; ?>
          <?php endforeach; ?>
          </table>
            <?php if($unsetFlg){
                $dispItemInfo['input_buppan_lbls']["expiry_all"] = "1";
              }?>
        </dd>
    </dl>
</div>
.<style type="text/css">
	.size-span{ display: inline-block; }
</style>