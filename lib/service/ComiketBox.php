<?php
/**
 * @package    ClassDefFile
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
/**#@-*/

 /**
 * コミケ申込明細データマスタ情報を扱います。
 *
 * @package Service
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_ComiketBox {

    // トランザクションフラグ
    private $transactionFlg = TRUE;

    /**
     * トランザクションフラグ設定.
     * @param type $flg TRUE=内部でトランザクション処理する/FALSE=内部でトランザクション処理しない
     */
    public function setTrnsactionFlg($flg) {
        $this->transactionFlg = $flg;
    }

    /**
     * イベント（配送用）insert
     * @param obj $db DBコネクション
     * @param array $data コミケ申込宅配データ
     */
    public function insert($db, $data) {
        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            "comiket_id",
            "type",
            "box_id",
            "num",
            "fare_price",
            "fare_amount",
            "fare_price_tax",
            "fare_amount_tax",
            "cost_price",
            "cost_amount",
            "cost_price_tax",
            "cost_amount_tax",

            "fare_price_kokyaku",
            "fare_amount_kokyaku",
            "fare_price_tax_kokyaku",
            "fare_amount_tax_kokyaku",
            "sagyo_jikan",
            "shohin_cd",
            "note1",
            
        );

        if (@empty($data['fare_price_kokyaku'])) {
            $data['fare_price_kokyaku'] = '0';
        }

        if (@empty($data['fare_amount_kokyaku'])) {
            $data['fare_amount_kokyaku'] = '0';
        }

        if (@empty($data['fare_price_tax_kokyaku'])) {
            $data['fare_price_tax_kokyaku'] = '0';
        }

        if (@empty($data['fare_amount_tax_kokyaku'])) {
            $data['fare_amount_tax_kokyaku'] = '0';
        }

        if (@empty($data['sagyo_jikan'])) {
            $data['sagyo_jikan'] = '0';
        }

        if (@empty($data['shohin_cd'])) {
            $data['shohin_cd'] = NULL;
        }

        if (@empty($data['note1'])) {
            $data['note1'] = '';
        }

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }

        $query  = '
            INSERT
            INTO
                comiket_box
            (
                comiket_id,
                type,
                box_id,
                num,
                fare_price,
                fare_amount,
                fare_price_tax,
                fare_amount_tax,
                cost_price,
                cost_amount,
                cost_price_tax,
                cost_amount_tax,

                fare_price_kokyaku,
                fare_amount_kokyaku,
                fare_price_tax_kokyaku,
                fare_amount_tax_kokyaku,
                sagyo_jikan,
                shohin_cd,
                note1
            )
            VALUES
            (
                $1,
                $2,
                $3,
                $4,
                $5,
                $6,
                $7,
                $8,
                $9,
                $10,
                $11,
                $12,
                $13,
                $14,

                $15,
                $16,
                $17,
                $18,
                $19
            );';

        $query = preg_replace('/\s+/u', ' ', trim($query));

        if($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START INSERT comiket_box #####");
        $res = $db->executeUpdate($query, $params);
		Sgmov_Component_Log::debug("####### END INSERT comiket_box #####");
		Sgmov_Component_Log::debug($res);
        if($this->transactionFlg) {
            $db->commit();
        }
    }

    /**
     * イベント（物販用）insert
     * @param obj $db DBコネクション
     * @param array $data コミケ申込宅配データ
     */
    public function insert2($db, $data) {
        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            "comiket_id",
            "type",
            "box_id",
            "num",
            "fare_price",
            "fare_amount",
            "fare_price_tax",
            "fare_amount_tax",
            "cost_price",
            "cost_amount",
            "cost_price_tax",
            "cost_amount_tax",
            "ziko_shohin_cd",

            "data_type",
            "fare_price_kokyaku",
            "fare_amount_kokyaku",
            "fare_price_tax_kokyaku",
            "fare_amount_tax_kokyaku",
            "sagyo_jikan",
            "shohin_cd",
        );
        
        if (@empty($data['fare_price_kokyaku'])) {
            $data['fare_price_kokyaku'] = '0';
        }

        if (@empty($data['fare_amount_kokyaku'])) {
            $data['fare_amount_kokyaku'] = '0';
        }

        if (@empty($data['fare_price_tax_kokyaku'])) {
            $data['fare_price_tax_kokyaku'] = '0';
        }

        if (@empty($data['fare_amount_tax_kokyaku'])) {
            $data['fare_amount_tax_kokyaku'] = '0';
        }

        if (@empty($data['sagyo_jikan'])) {
            $data['sagyo_jikan'] = '0';
        }

        if (@empty($data['shohin_cd'])) {
            $data['shohin_cd'] = NULL;
        }

        if (@empty($data['note1'])) {
            $data['note1'] = '';
        }
        
        if (@empty($data['data_type'])) {
            $data['data_type'] = '0';
        }

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }

        $query  = '
            INSERT
            INTO
                comiket_box
            (
                comiket_id,
                type,
                box_id,
                num,
                fare_price,
                fare_amount,
                fare_price_tax,
                fare_amount_tax,
                cost_price,
                cost_amount,
                cost_price_tax,
                cost_amount_tax,
                ziko_shohin_cd,

                data_type,
                fare_price_kokyaku,
                fare_amount_kokyaku,
                fare_price_tax_kokyaku,
                fare_amount_tax_kokyaku,
                sagyo_jikan,
                shohin_cd
            )
            VALUES
            (
                $1,
                $2,
                $3,
                $4,
                $5,
                $6,
                $7,
                $8,
                $9,
                $10,
                $11,
                $12,
                $13,

                $14,
                $15,
                $16,
                $17,
                $18,
                $19,
                $20
            );';

        $query = preg_replace('/\s+/u', ' ', trim($query));

        if($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START INSERT comiket_box #####");
        $res = $db->executeUpdate($query, $params);
		Sgmov_Component_Log::debug("####### END INSERT comiket_box #####");
		Sgmov_Component_Log::debug($res);
        if($this->transactionFlg) {
            $db->commit();
        }
    }

    /**
     * コミケ宅配データをIDと往復区分によって検索します。
     * @param obj $db DBコネクション
     * @param string $comiket_id 申込ID
     * @param string $type 往復区分
     * @return array comiket_boxの配列
     */
    public function fetchComiketBoxDataListByIdAndType($db, $comiket_id, $type) {

        $query = '
            SELECT
                *
            FROM
                comiket_box
            WHERE
                comiket_box.comiket_id = $1
            AND
                comiket_box.type = $2;';

        if (empty($comiket_id) || empty($type)) {
            Sgmov_Component_Log::debug("パラメータが不足しています。");
            return array();
        }

        $result = $db->executeQuery($query, array($comiket_id, $type));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }

        $returnArr = array();
        for ($i = 0; $i < $resSize; $i++) {
            $row = $result->get($i);
            array_push($returnArr, $row);
        }

        return $returnArr;
    }

    public function getBuppanTotalCount($db, $eventsubId, $bpnType){
        $query = "SELECT 
                      comiket_box.box_id,
                      count(*) 
                  FROM 
                    comiket_box 
                  INNER join
                    comiket
                  ON  
                    comiket.id = comiket_box.comiket_id
                WHERE eventsub_id = $1 
                    AND comiket.del_flg='0'
                    AND comiket.bpn_type = $2
                GROUP BY
                    comiket_box.box_id 
                ORDER BY
                    comiket_box.box_id ASC";

        $result = $db->executeQuery($query, array($eventsubId, $bpnType));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }

        $returnArr = array();
        for ($i = 0; $i < $resSize; $i++) {
            $row = $result->get($i);
            array_push($returnArr, $row);
        }

        return $returnArr;
    }

    /**
     * コミケ宅配データをIDと往復区分によって検索します。
     * @param obj $db DBコネクション
     * @param string $comiket_id 申込ID
     * @param string $type 往復区分
     * @return array comiket_boxの配列
     */
    public function fetchComiketBoxDataListByIdAndTypeOrderByCd($db, $comiket_id, $type) {

        $query = '
            SELECT
                *
            FROM
                comiket_box
            INNER JOIN 
                box 
                ON box.id = comiket_box.box_id
            WHERE
                comiket_box.comiket_id = $1
            AND
                comiket_box.type = $2
            ORDER BY cd ASC';

        if (empty($comiket_id) || empty($type)) {
            Sgmov_Component_Log::debug("パラメータが不足しています。");
            return array();
        }

        $result = $db->executeQuery($query, array($comiket_id, $type));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }

        $returnArr = array();
        for ($i = 0; $i < $resSize; $i++) {
            $row = $result->get($i);
            array_push($returnArr, $row);
        }

        return $returnArr;
    }
    
    /**
     * イベントIDでコミケボックス情報取得
     * 
     * @param type $db
     * @param type $ids
     * @return array
     */
    public function fetchComiketBoxByListComiketIds($db, $ids) {
        if(empty($ids)) {
            return array();
        }
        
        $query = 'SELECT * FROM comiket_box WHERE comiket_id IN ('.implode(', ', $ids).')';

        $result = $db->executeQuery($query);
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }
        $returnArr = array();
        for ($i = 0; $i < $resSize; $i++) {
            $row = $result->get($i);
            array_push($returnArr, $row);
        }

        return $returnArr;
    }
}

