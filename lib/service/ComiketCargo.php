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
class Sgmov_Service_ComiketCargo {

    // トランザクションフラグ
    private $transactionFlg = TRUE;

    /**
     * トランザクションフラグ設定.
     * @param type $flg TRUE=内部でトランザクション処理する/FALSE=内部でトランザクション処理しない
     */
    public function setTrnsactionFlg($flg) {
        $this->transactionFlg = $flg;
    }

    public function insert($db, $data) {
        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            "comiket_id",
            "type",
            "num",
            "fare_amount",
        );

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
                comiket_cargo
            (
                comiket_id,
                type,
                num,
                fare_amount
            )
            VALUES
            (
                $1,
                $2,
                $3,
                $4
            );';

        $query = preg_replace('/\s+/u', ' ', trim($query));

        if($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START INSERT comiket_cargo #####");
        $res = $db->executeUpdate($query, $params);
		Sgmov_Component_Log::debug("####### END INSERT comiket_cargo #####");
		Sgmov_Component_Log::debug($res);
        if($this->transactionFlg) {
            $db->commit();
        }
    }

    /**
     * コミケ申込カーゴデータをIDと往復区分によって検索します。
     * @param obj $db DBコネクション
     * @param string $comiket_id 申込ID
     * @param string $type 往復区分
     * @return array comiket_cargoの配列
     */
    public function fetchComiketCargoDataListByIdAndType($db, $comiket_id, $type) {

        $query = '
            SELECT
                *
            FROM
                comiket_cargo
            WHERE
                comiket_cargo.comiket_id = $1
            AND
                comiket_cargo.type = $2';

        if (empty($comiket_id) || empty($type)) {
            Sgmov_Component_Log::debug("パラメータが不足しています。");
            return array();
        }

        $result = $db->executeQuery($query, array($comiket_id, $type));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }

        $row = $result->get(0);

        return $row;
    }
}

