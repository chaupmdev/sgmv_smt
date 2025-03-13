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
 * 消費税マスタ
 *
 * @package Service
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_MstShohizei {

    /**
     * カーゴオプションマスタをDBから取得します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return
     */
    public function fetchShohizei($db, $ymd = null) {

    	if (empty($ymd)) {
    		$query = 'SELECT shz_zeiritsu '
    		.' FROM mst_shohizei'
    		.' WHERE shz_yuko_fr_dt <= current_date'
    		.' AND shz_yuko_to_dt >= current_date';
    	} else {
    		$query = 'SELECT shz_zeiritsu '
    		.' FROM mst_shohizei'
    		.' WHERE shz_yuko_fr_dt <= TO_DATE(\''.$ymd.'\', \'YYYY/MM/DD\')'
    		.' AND shz_yuko_to_dt >= TO_DATE(\''.$ymd.'\', \'YYYY/MM/DD\')';
    	}

        $result = $db->executeQuery($query);

        $row = $result->get(0);

        return $row['shz_zeiritsu'];
    }
}