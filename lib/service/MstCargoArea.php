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
 * 都道府県情報を扱います。
 *
 * @package Service
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_MstCargoArea {

    /**
     * 都道府県リストをDBから取得し、キーに都道府県IDを値に都道府県名を持つ配列を返します。
     *
     * 先頭には空白の項目を含みます。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array ['ids'] 都道府県IDの文字列配列、['names'] 都道府県名の文字列配列
     */
    public function fetchCargoAreas($db) {
        $query = 'SELECT cra_jis, cra_area, cra_area_name FROM mst_cargo_area
        			WHERE cra_st_date <= current_date AND cra_ed_date >= current_date ORDER BY cra_seq';

        $ids   = array();
        $names = array();

        $result = $db->executeQuery($query);
        for ($i = 0; $i < $result->size(); ++$i) {
            $row     = $result->get($i);
            $ids[]   = $row['cra_jis'];
            $names[] = $row['cra_area_name'];
        }

        return array(
            'ids'   => $ids,
            'names' => $names,
        );
    }

    /**
     * カーゴエリアコードをDBから取得します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return
     */
    public function fetchCargoAreaCd($db, $data) {

    	// この順番でSQLのプレースホルダーに適用されます。
    	$keys = array('jiscd');

    	// パラメータのチェック
    	$params = array();
    	foreach ($keys as $key) {
    		if (!array_key_exists($key, $data)) {
    			throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
    		}
    		$params[] = $data[$key];
    	}

    	$query = 'SELECT cra_area '
    	.' FROM mst_cargo_area'
    	.' WHERE cra_jis = $1'
    	.' AND cra_st_date <= current_date'
    	.' AND cra_ed_date >= current_date';

    	$result = $db->executeQuery($query, $params);
    	$row = $result->get(0);

    	return $row['cra_area'];

    }
}