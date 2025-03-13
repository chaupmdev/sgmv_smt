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
 * カーゴオプションマスタ
 *
 * @package Service
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_MstCgCargoOpt {

    /**
     * カーゴオプションマスタをDBから取得します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return
     */
    public function fetchCagoOptList($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array('io_kbn','binshu_cd','hanboki','ymd');

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }

        $query = 'SELECT * '
                .' FROM mst_cargo_opt'
                .' WHERE mco_io_kbn = $1'
                .' AND mco_binshu_cd = $2'
                .' AND mco_hamboki_kbn = $3'
                .' AND mco_st_date <= TO_DATE($4, \'YYYY/MM/DD\')'
                .' AND mco_ed_date >= TO_DATE($4, \'YYYY/MM/DD\')'
                .' ORDER BY mco_sort';

        $cds                  = array();
        $komoku_names         = array();
        $sagyo_names          = array();
        $tankas    			  = array();
        $input_kbns 		  = array();
        $bikos    			  = array();

        $result = $db->executeQuery($query, $params);
        for ($i = 0; $i < $result->size(); $i++) {
            $row = $result->get($i);
            $cds[]                  = $row['mco_code'];
            $komoku_names[]         = $row['mco_komoku_nm'];
            $sagyo_names[]          = $row['mco_sagyo_nm'];
            $tankas[]    			= $row['mco_tanka'];
            $input_kbns[] 			= $row['mco_input_kbn'];
            $bikos[]    			= $row['mco_biko'];
        }

        return array(
            'cds'                  => $cds,
            'komoku_names'         => $komoku_names,
            'sagyo_names'          => $sagyo_names,
            'tankas'    		   => $tankas,
            'input_kbns' 		   => $input_kbns,
            'bikos'    			   => $bikos,
        );
    }

    /**
    * カーゴオプションマスタをDBから取得します。
    *
    * @param Sgmov_Component_DB $db DB接続
    * @return
    */
    public function fetchOptTankaList($db, $data) {

    	// この順番でSQLのプレースホルダーに適用されます。
    	$keys = array('binshu_cd','hanboki','ymd');

    	// パラメータのチェック
    	$params = array();
    	foreach ($keys as $key) {
    		if (!array_key_exists($key, $data)) {
    			throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
    		}
    		$params[] = $data[$key];
    	}

    	$query = 'SELECT * '
    	.' FROM mst_cargo_opt'
    	.' WHERE '
    	.' mco_binshu_cd = $1'
    	.' AND mco_hamboki_kbn = $2'
    	.' AND mco_st_date <= TO_DATE($3, \'YYYY/MM/DD\')'
        .' AND mco_ed_date >= TO_DATE($3, \'YYYY/MM/DD\')'
    	.' ORDER BY mco_sort';

    	$cds                  = array();
    	$komoku_names         = array();
    	$sagyo_names          = array();
    	$tankas    			  = array();
    	$input_kbns 		  = array();
    	$bikos    			  = array();

    	$result = $db->executeQuery($query, $params);
    	for ($i = 0; $i < $result->size(); $i++) {
    		$row = $result->get($i);
    		$cds[]                  = $row['mco_code'];
    		$komoku_names[]         = $row['mco_komoku_nm'];
    		$sagyo_names[]          = $row['mco_sagyo_nm'];
    		$tankas[]    			= $row['mco_tanka'];
    		$input_kbns[] 			= $row['mco_input_kbn'];
    		$bikos[]    			= $row['mco_biko'];
    	}

    	return array(
                'cds'                  => $cds,
                'komoku_names'         => $komoku_names,
                'sagyo_names'          => $sagyo_names,
                'tankas'    		   => $tankas,
                'input_kbns' 		   => $input_kbns,
                'bikos'    			   => $bikos,
    	);
    }
}