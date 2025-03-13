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
 * 拠点・出発エリア・到着エリア情報を扱います。
 *
 * @package    Service
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_CenterArea
{
    /**
     * 出発エリアリストをDBから取得し、
     * キーに出発エリアIDを値に出発エリア名を持つ配列を返します。
     *
     * 先頭には空白の項目を含みます。
     *
     * 開始終了日が(開始日 <= 現在日付 <= 終了日)を満たすものを表示順の昇順で取得します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array ['ids'] 出発エリアIDの文字列配列、['names'] 出発エリア名の文字列配列
     */
    public function fetchFromAreaList($db)
    {
        $query = 'SELECT id, name FROM from_areas';
        $query .= '     WHERE start_date <= current_date and stop_date >= current_date';
        $query .= '     ORDER BY show_order';

        $ids = array();
        $names = array();

        // 先頭に空白を追加
        $ids[] = '';
        $names[] = '';

        $result = $db->executeQuery($query);
        for ($i = 0; $i < $result->size(); $i++) {
            $row = $result->get($i);
            $ids[] = $row['id'];
            $names[] = $row['name'];
        }

        return array('ids'=>$ids,
                         'names'=>$names);
    }

    /**
     * 到着エリアリストをDBから取得し、
     * キーに到着エリアIDを値に到着エリア名を持つ配列を返します。
     *
     * 先頭には空白の項目を含みます。
     *
     * 開始終了日が(開始日 <= 現在日付 <= 終了日)を満たすものを表示順の昇順で取得します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array ['ids'] 到着エリアIDの文字列配列、['names'] 到着エリア名の文字列配列
     */
    public function fetchToAreaList($db)
    {
        $query = 'SELECT id, name FROM to_areas';
        $query .= '     WHERE start_date <= current_date and stop_date >= current_date';
        $query .= '     ORDER BY show_order';

        $ids = array();
        $names = array();

        // 先頭に空白を追加
        $ids[] = '';
        $names[] = '';

        $result = $db->executeQuery($query);
        for ($i = 0; $i < $result->size(); $i++) {
            $row = $result->get($i);
            $ids[] = $row['id'];
            $names[] = $row['name'];
        }

        return array('ids'=>$ids,
                         'names'=>$names);
    }

    /**
     * 出発エリアのリストに拠点情報を加えて取得します。
     *
     * 拠点・出発エリア紐付けの開始終了日が(開始日 <= 現在日付 <= 終了日)を満たすものを
     * 拠点表示順の昇順、次に出発エリア表示順の昇順で取得します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array ['center_ids'] 拠点IDの文字列配列、['center_names'] 拠点名の文字列配列、
     * ['from_area_ids'] 出発エリアIDの文字列配列、['from_area_names'] 出発エリア名の文字列配列
     */
    public function fetchCenterFromAreas($db)
    {
        // TODO 未テスト
        $query = 'SELECT';
        $query .= '        centers.id AS center_id';
        $query .= '        ,centers.name AS center_name';
        $query .= '        ,from_areas.id AS from_area_id';
        $query .= '        ,from_areas.name AS from_area_name';
        $query .= '    FROM';
        $query .= '        (';
        $query .= '            centers_from_areas';
        $query .= '                JOIN centers';
        $query .= '                    ON centers_from_areas.center_id = centers.id';
        $query .= '        )';
        $query .= '    JOIN from_areas';
        $query .= '        ON centers_from_areas.from_area_id = from_areas.id';
        $query .= '  WHERE';
        $query .= '    centers_from_areas.start_date <= current_date';
        $query .= '    AND centers_from_areas.stop_date >= current_date';
        $query .= '  ORDER BY';
        $query .= '    centers.show_order';
        $query .= '    ,from_areas.show_order';

        $center_ids = array();
        $center_names = array();
        $from_area_ids = array();
        $from_area_names = array();

        $result = $db->executeQuery($query);
        for ($i = 0; $i < $result->size(); $i++) {
            $row = $result->get($i);
            $center_ids[] = $row['center_id'];
            $center_names[] = $row['center_name'];
            $from_area_ids[] = $row['from_area_id'];
            $from_area_names[] = $row['from_area_name'];
        }

        return array('center_ids'=>$center_ids,
                         'center_names'=>$center_names,
                         'from_area_ids'=>$from_area_ids,
                         'from_area_names'=>$from_area_names);
    }

    /**
     * 到着エリアのリストに拠点情報を加えて取得します。
     *
     * 拠点・到着エリア紐付けの開始終了日が(開始日 <= 現在日付 <= 終了日)を満たすものを
     * 拠点表示順の昇順、次に到着エリア表示順の昇順で取得します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array ['center_ids'] 拠点IDの文字列配列、['center_names'] 拠点名の文字列配列、
     * ['to_area_ids'] 到着エリアIDの文字列配列、['to_area_names'] 到着エリア名の文字列配列
     */
    public function fetchCenterToAreas($db)
    {
        // TODO 未テスト
        $query = 'SELECT';
        $query .= '        centers.id AS center_id';
        $query .= '        ,centers.name AS center_name';
        $query .= '        ,to_areas.id AS to_area_id';
        $query .= '        ,to_areas.name AS to_area_name';
        $query .= '    FROM';
        $query .= '        (';
        $query .= '            centers_to_areas';
        $query .= '                JOIN centers';
        $query .= '                    ON centers_to_areas.center_id = centers.id';
        $query .= '        )';
        $query .= '    JOIN to_areas';
        $query .= '        ON centers_to_areas.to_area_id = to_areas.id';
        $query .= '  WHERE';
        $query .= '    centers_to_areas.start_date <= current_date';
        $query .= '    AND centers_to_areas.stop_date >= current_date';
        $query .= '  ORDER BY';
        $query .= '    centers.show_order';
        $query .= '    ,to_areas.show_order';

        $center_ids = array();
        $center_names = array();
        $to_area_ids = array();
        $to_area_names = array();

        $result = $db->executeQuery($query);
        for ($i = 0; $i < $result->size(); $i++) {
            $row = $result->get($i);
            $center_ids[] = $row['center_id'];
            $center_names[] = $row['center_name'];
            $to_area_ids[] = $row['to_area_id'];
            $to_area_names[] = $row['to_area_name'];
        }

        return array('center_ids'=>$center_ids,
                         'center_names'=>$center_names,
                         'to_area_ids'=>$to_area_ids,
                         'to_area_names'=>$to_area_names);
    }
}
?>
