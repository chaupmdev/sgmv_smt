<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
/**#@-*/

 /**
 * カーゴ単品関連マスタ
 *
 * @package Service
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_MstCargoTanpinKanren {

    /**
     * カーゴ単品関連マスタをDBから取得します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return
     */
    public function fetchCagoTanpinOptCds($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array('hinmoku_cd');

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }

        $query = 'SELECT cts_option_code '
                .' FROM mst_cargo_tanpin_kanren'
                .' WHERE cts_hinmoku_code = $1'
                .' ORDER BY cts_option_code';

        $optCds = array();

        $result = $db->executeQuery($query, $params);
        for ($i = 0; $i < $result->size(); ++$i) {
            $row = $result->get($i);
            $optCds[]                  = $row['cts_option_code'];
        }

        return array(
            'optCds'                  => $optCds,
        );
    }
}