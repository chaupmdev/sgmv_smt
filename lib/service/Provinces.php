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
 * 地方情報を扱います。
 *
 * @package Service
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_Provinces
{
    /**
     * 地方リストをDBから取得し、キーに地方IDを値に地方名を持つ配列を返します。
     *
     * 先頭には空白の項目を含みます。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array ['ids'] 地方IDの文字列配列、['names'] 地方名の文字列配列
     */
    public function fetchProvinces($db)
    {
        $query = 'SELECT id, name FROM provinces ORDER BY id';

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
}
?>
