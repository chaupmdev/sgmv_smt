<?php

/**
 * @package    ClassDefFile
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
/**#@-*/

/**
 * アルペン記事欄マスタを扱います。
 *
 * @package Service
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_AlpenKijiran
{

    /**
     * 記事欄リストをDBから取得し、キーに記事欄ID、値に記事欄名を持つ配列を返します。
     *
     * 先頭には空白の項目を含みます。
     *
     * @param type $db
     * @return type
     */
    public function fetchAlpenKijiran($db)
    {
        $query = 'SELECT id, note FROM alpen_kijiran ORDER BY id';

        // 先頭に空白を追加
        $ids   = array('');
        $notes = array('');

        $result = $db->executeQuery($query);
        for ($i = 0; $i < $result->size(); ++$i) {
            $row     = $result->get($i);
            $ids[]   = $row['id'];
            $notes[] = $row['note'];
        }

        return array(
            'ids'   => $ids,
            'notes' => $notes,
        );
    }

    /**
     *
     * @param type $db
     * @return type
     */
    public function fetchAllAlpenKijiran($db, $eventsubId)
    {
        $query = 'SELECT id, note, eventsub_id FROM alpen_kijiran WHERE eventsub_id=$1 ORDER BY id, note';

        $result = $db->executeQuery($query, array($eventsubId));
        for ($i = 0; $i < $result->size(); ++$i) {
            $row     = $result->get($i);
            $ids[]   = $row['id'];
            $notes[] = $row['note'];
            $eventsubIds[] = $row['eventsub_id'];
        }

        return array(
            'ids'         => $ids,
            'notes'       => $notes,
            'eventsubIds' => $eventsubIds,
        );
    }

    /**
     * イベントサブIDで記事欄を取得
     * @param type $db
     * @return type
     */
    public function fetchAllAlpenKijiranList($db, $eventsubId)
    {
        $query = 'SELECT id, note, eventsub_id FROM alpen_kijiran WHERE eventsub_id=$1  ORDER BY id, note';

        if(empty($eventsubId)) {
            return array();
        }

        $result = $db->executeQuery($query, array($eventsubId));

        for ($i = 0; $i < $result->size(); ++$i) {
            $returnList[$result->get($i)['id']] = $result->get($i);
        }

        return $returnList;
    }

}
