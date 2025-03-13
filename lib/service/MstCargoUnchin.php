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
 * カーゴ運賃マスタ
 *
 * @package Service
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_MstCargoUnchin {

    /**
     * カーゴオプションマスタをDBから取得します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return
     */
    public function fetchJyuryoutai($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array('hstsu_area','chaku_area','jyuryoutai','binshu_cd','hanboki_kbn','hikitori_date');

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }

        $query = 'SELECT max(cuc_jyuryoutai) as jyuryo'
                .' FROM mst_cargo_unchin'
                .' WHERE cuc_hatsu_area = $1'
                .' AND cuc_chaku_area = $2'
                .' AND cuc_jyuryoutai <= $3'
                .' AND cuc_binshu_cd = $4'
                .' AND cuc_hamboki_kbn = $5'
                .' AND cuc_st_date < TO_DATE($6, \'YYYY/MM/DD\')'
				.' AND cuc_ed_date >= TO_DATE($6, \'YYYY/MM/DD\')';

        $result = $db->executeQuery($query, $params);
		$row = $result->get(0);

        return $row['jyuryo'];

    }

    /**
    * カーゴオプションマスタをDBから取得します。
    *
    * @param Sgmov_Component_DB $db DB接続
    * @return
    */
    public function fetchCargoUnchin($db, $data) {

    	// この順番でSQLのプレースホルダーに適用されます。
    	$keys = array('hstsu_area','chaku_area','jyuryoutai','binshu_cd','hanboki_kbn','hikitori_date');

    	// パラメータのチェック
    	$params = array();
    	foreach ($keys as $key) {
    		if (!array_key_exists($key, $data)) {
    			throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
    		}
    		$params[] = $data[$key];
    	}

    	$query = 'SELECT cuc_unchin '
    	.' FROM mst_cargo_unchin'
    	.' WHERE cuc_hatsu_area = $1'
    	.' AND cuc_chaku_area = $2'
    	.' AND cuc_jyuryoutai = $3'
    	.' AND cuc_binshu_cd = $4'
    	.' AND cuc_hamboki_kbn = $5'
    	.' AND cuc_st_date < TO_DATE($6, \'YYYY/MM/DD\')'
    	.' AND cuc_ed_date >= TO_DATE($6, \'YYYY/MM/DD\')';

    	$result = $db->executeQuery($query, $params);
    	$row = $result->get(0);

    	return $row['cuc_unchin'];

    }
}