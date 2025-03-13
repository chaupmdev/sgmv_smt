<?php
/**
 * @package    ClassDefFile
 * @author     K.Sawada
 * @copyright  2009-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
/**#@-*/

 /**
 * Postgresが持つInformationSchema情報を扱います。
 *
 * @package Service
 * @author     K.Sawada
 * @copyright  2009-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_InformationSchema {

    /**
     * table_nameテーブルのカラムを取得します。
     * @param type $db
     * @param type $data
     * @return type
     */
    public function getColumnNames($db, $data) {
        
        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array('table_name');
        
        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }
        
        $query ="
                SELECT
                    column_name
                FROM
                    information_schema.columns
                WHERE
                    table_name = $1
                ORDER BY
                    ordinal_position
                ";

        $result = $db->executeQuery($query, $params);
        $returnResultList = array();
        for($i=0; $i < $result->size(); $i++) {
            // 引数のidが存在しない場合、エラーで止まってしまうため、@で回避($row はfalseになる)
            $row = @$result->get($i);
            $returnResultList[] = $row["column_name"];
        }
        return $returnResultList;
    }
}