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
 * 単身カーゴプランのお申し込みカーゴオプション情報を扱います。
 *
 * @package Service
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_DatCargoOpt {

    /**
     * 単身カーゴプランのお申し込み情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function insert($db, $id, $cd, $num = null) {

        $query = 'INSERT';
        $query .= '    INTO';
        $query .= '        dat_cargo_opt(';
        $query .= '            cop_crg_id';
        $query .= '            ,cop_mco_code';
        $query .= '            ,cop_chumon_num';
//         $query .= '            ,cop_insert_date';
//         $query .= '            ,cop_insert_program';
        $query .= '        )';
        $query .= '    VALUES';
        $query .= '        (';
        $query .= '            \''.$id.'\'';
        $query .= '            ,\''.$cd.'\'';
        if ($num == null) {
        	$query .= '            ,null';
        } else {
        	$query .= '            ,'.$num.'';
        }
//         $query .= '            ,now()';
//         $query .= '            ,\'sgmvHp\'';
        $query .= '        )';

        $db->begin();
        Sgmov_Component_Log::debug("####### START INSERT dat_cargo_opt #####");
        $db->executeUpdate($query);
        Sgmov_Component_Log::debug("####### END INSERT dat_cargo_opt #####");
        $db->commit();
    }
}
