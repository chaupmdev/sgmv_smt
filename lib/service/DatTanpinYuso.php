<?php

/**
 * @package    ClassDefFile
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/* * #@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
/* * #@- */

/**
 * 単品輸送のお申し込みサービス情報を扱います。
 *
 * @package Service
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_DatTanpinYuso {

    /**
     * 単品輸送のお申し込み情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function insert($db, $id, $cd, $nm, $sort) {

        $query = 'INSERT';
        $query .= '    INTO';
        $query .= '        dat_tanpin_yuso(';
        $query .= '            tpy_crg_id';
        $query .= '            ,tpy_hinmoku_cd';
        $query .= '            ,tpy_hinmoku_nm';
        $query .= '            ,tpy_sort';
        $query .= '        )';
        $query .= '    VALUES';
        $query .= '        (';
        $query .= '            \''.$id.'\'';
        $query .= '            ,\''.$cd.'\'';
//         if ($nm == null) {
//         	$query .= '            ,null';
//         } else {
        	$query .= '            ,\''.$nm.'\'';
//         }
        $query .= '            ,\''.$sort.'\'';
        $query .= '        )';

        $db->begin();
        Sgmov_Component_Log::debug("####### START INSERT dat_tanpin_yuso #####");
        $db->executeUpdate($query);
        Sgmov_Component_Log::debug("####### END INSERT dat_tanpin_yuso #####");
        $db->commit();
    }
}
