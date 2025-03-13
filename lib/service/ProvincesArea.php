<?php
/**
 * @package    ClassDefFile
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
/**#@-*/

 /**
 * 地方・出発エリア・到着エリア情報を扱います。
 *
 * @package Service
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_ProvincesArea
{

    /**
     * 地方IDとそれにひもずく到着エリアリストをDBから取得し、
     * キーに地方IDを、値に到着エリアID、出発エリア名を持つ配列を返します。
     *
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array ['ids'] プランIDの文字列配列、['names'] プラン名の文字列配列
     */
    public function fetchProvincesToAreaList($db)
    {
        $query = 'SELECT provinces.id,to_areas_provinces.to_area_id,to_areas.name ';
        $query .= 'FROM provinces,to_areas_provinces,to_areas ';
        $query .= '     WHERE to_areas_provinces.provinces_id = provinces.id AND to_areas_provinces.to_area_id = to_areas.id ';
        $query .= '     AND to_areas_provinces.start_date <= current_date and to_areas_provinces.stop_date >= current_date ';
        $query .= '     ORDER BY to_area_id;';

        $ids = array();
        $areaids = array();
        $names = array();

        $result = $db->executeQuery($query);

        for ($i = 0; $i < $result->size(); $i++) {

            $row = $result->get($i);
            $ids[] = $row['id'];
            $areaids[] = $row['to_area_id'];
            $names[] = $row['name'];
        }
        return array('ids'=>$ids,
                         'to_area_ids'=>$areaids,
						 'to_area_names'=>$names);
    }


    /**
     * 地方IDをキーとして該当する出発エリアリストをDBから取得し、
     * キーに地方IDを値に出発エリアID、出発エリア名を持つ配列を返します。
     *
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array ['ids'] プランIDの文字列配列、['names'] プラン名の文字列配列
     */
    public function fetchFromAreaListByProvinces($db,$provinces)
    {
        $query = 'SELECT from_areas_provinces.provinces_id,from_areas_provinces.from_area_id,from_areas.name ';
        $query .= 'FROM from_areas_provinces,from_areas ';
        $query .= '     WHERE from_areas_provinces.provinces_id = $1 AND from_areas_provinces.from_area_id = from_areas.id ';
        $query .= '     AND from_areas_provinces.start_date <= current_date and from_areas_provinces.stop_date >= current_date ';
        $query .= '     ORDER BY from_area_id;';

        $ids = array();
        $areaids = array();
        $names = array();


        // 先頭に空白を追加
        $ids[] = '';
        $areaids[] = '';
        $names[] = '';

        $result = $db->executeQuery($query,array($provinces));

        for ($i = 0; $i < $result->size(); $i++) {

            $row = $result->get($i);
            $ids[] = $row['provinces_id'];
            $areaids[] = $row['from_area_id'];
            $names[] = $row['name'];
        }
        return array('provinces_ids'=>$ids,
                         'from_area_ids'=>$areaids,
                         'from_area_names'=>$names);
    }

}
?>
