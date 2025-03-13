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
 * 繁忙期マスタ
 *
 * @package Service
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_MstHanbouki {

    /**
     * カーゴオプションマスタをDBから取得します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return
     */
    public function fetchHanbokiKbn($db, $ymd = null) {

    	if (empty($ymd)) {
    		$query = 'SELECT count(*) as cnt'
    		.' FROM mst_hanbouki'
    		.' WHERE hnb_st_date <= current_date'
    		.' AND hnb_ed_date >= current_date';
    	} else {
    		$query = 'SELECT count(*) as cnt'
    		.' FROM mst_hanbouki'
    		.' WHERE hnb_st_date <= TO_DATE(\''.$ymd.'\', \'YYYY/MM/DD\')'
    		.' AND hnb_ed_date >= TO_DATE(\''.$ymd.'\', \'YYYY/MM/DD\')';
    	}

        $result = $db->executeQuery($query);

        $row = $result->get(0);

        if ($row['cnt'] == 0) {
        	return '0';
        }

        return '1';

    }
}