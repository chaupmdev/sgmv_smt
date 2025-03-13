<?php
/**
 * @package    ClassDefFile
 * @author     K.Hamada(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
/**#@-*/

 /**
 * 採用拠点を扱います。
 *
 * @package Service
 * @author     K.Hamada(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_Center
{
    /**
     * 拠点リストをDBから取得し、キーに拠点IDを値に拠点名を持つ配列を返します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array ['ids'] 拠点IDの文字列配列、['names'] 拠点名の文字列配列
     */
    public function fetchCenters($db)
    {
      //  $query = 'SELECT id, name FROM centers where honsya_flag = FALSE ORDER BY id';
        $query = 'SELECT employment_centers.id, centers.name FROM centers,employment_centers';
        $query .= '     WHERE centers.id=employment_centers.center_id';
        $query .= '     AND employment_centers.start_date <= current_date and';
        $query .= '     employment_centers.stop_date >= current_date';
        $query .= '     order by id';

        $ids = array();
        $names = array();

        $result = $db->executeQuery($query);
        for ($i = 0; $i < $result->size(); $i++) {
            $row = $result->get($i);
            $ids[] = $row['id'];
            $names[] = $row['name'];
        }

        return array('ids'=>$ids,
                         'names'=>$names);
    }
}
?>
