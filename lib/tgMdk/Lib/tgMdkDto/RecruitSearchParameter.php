<?php
/**
 * 検索条件:リクルート検索パラメータクラス<br>
 *
 * @author Veritrans, Inc.
 *
 */
class RecruitSearchParameter
{

    /**
     * 詳細オーダー決済状態<br>
     */
    private $detailOrderType;

    /**
     * 詳細コマンドタイプ名<br>
     */
    private $detailCommandType;

    /**
     * 商品番号<br>
     */
    private $itemId;

    /**
     * 詳細オーダー決済状態を取得する<br>
     *
     * @return 詳細オーダー決済状態<br>
     */
    public function getDetailOrderType() {
        return $this->detailOrderType;
    }

    /**
     * 詳細オーダー決済状態を設定する<br>
     *
     * @param detailOrderType 詳細オーダー決済状態<br>
     */
    public function setDetailOrderType($detailOrderType) {
        $this->detailOrderType = $detailOrderType;
    }

    /**
     * 詳細コマンドタイプ名を取得する<br>
     *
     * @return 詳細コマンドタイプ名<br>
     */
    public function getDetailCommandType() {
        return $this->detailCommandType;
    }

    /**
     * 詳細コマンドタイプ名を設定する<br>
     *
     * @param detailCommandType 詳細コマンドタイプ名<br>
     */
    public function setDetailCommandType($detailCommandType) {
        $this->detailCommandType = $detailCommandType;
    }

    /**
     * 商品番号を取得する<br>
     *
     * @return 商品番号<br>
     */
    public function getItemId() {
        return $this->itemId;
    }

    /**
     * 商品番号を設定する<br>
     *
     * @param itemId 商品番号<br>
     */
    public function setItemId($itemId) {
        $this->itemId = $itemId;
    }

}
?>