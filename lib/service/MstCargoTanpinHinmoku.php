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
 * カーゴ単品品目オプションマスタ
 *
 * @package Service
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_MstCargoTanpinHinmoku {

    /**
     * カーゴ単品輸送品目マスタをDBから取得します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return
     */
    public function fetchCagoTanpinHinmokuList($db) {

 		$query = 'SELECT cth_hinmoku_code, cth_hinmoku_mei,B.jsh_henkanbf_size FROM mst_cargo_tanpin_hinmoku A left join mst_juryo_size_henkan B
 				on A.cth_hinmoku_jyuryou > B.jsh_henkanaf_jyuryo_from and A.cth_hinmoku_jyuryou <= B.jsh_henkanaf_jyuryo_to
 				WHERE cth_st_date <= current_date and cth_ed_date >= current_date ORDER BY cth_seq';

        // 先頭に空白を追加
        $ids   = array('');
        $names = array('');

        $result = $db->executeQuery($query);
        for ($i = 0; $i < $result->size(); ++$i) {
            $row     = $result->get($i);
            $ids[]   = $row['cth_hinmoku_code'];
            $names[] = $row['jsh_henkanbf_size'].':'.$row['cth_hinmoku_mei'];
        }

        return array(
            'ids'   => $ids,
            'names' => $names,
        );
    }

    /**
    * カーゴ単品輸送品目マスタをDBから取得します。
    *
    * @param Sgmov_Component_DB $db DB接続
    * @return
    */
    public function fetchWeight($db, $data) {

    	// この順番でSQLのプレースホルダーに適用されます。
    	$keys = array('hinmokuCd');

    	// パラメータのチェック
    	$params = array();
    	foreach ($keys as $key) {
    		if (!array_key_exists($key, $data)) {
    			throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
    		}
    		$params[] = $data[$key];
    	}

    	$query = 'SELECT cth_hinmoku_jyuryou '
    	.' FROM mst_cargo_tanpin_hinmoku'
    	.' WHERE cth_hinmoku_code = $1'
    	.' AND cth_st_date <= current_date'
    	.' AND cth_ed_date >= current_date';

    	$result = $db->executeQuery($query, $params);
    	$row = $result->get(0);

    	return $row['cth_hinmoku_jyuryou'];

    }
}