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
 * 都道府県情報を扱います。
 *
 * @package Service
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_Prefecture {
    
    /**
     * 引数の都道府県コードを元に都道府県情報をDBから取得します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param string prefecture_id 都道府県コード
     * @return array ['ids'] 都道府県IDの文字列配列、['names'] 都道府県名の文字列配列
     */
    public function fetchPrefecturesById($db, $id) {
        if(empty($id)) {
            return array();
        }
        
        $query = 'SELECT prefecture_id, name FROM prefectures WHERE prefecture_id = $1';

        $result = $db->executeQuery($query, array($id));
        $row = $result->get(0);
        if(@empty($row)) {
            return array();
        }
        return $row;
    }

    /**
     * 都道府県リストをDBから取得し、キーに都道府県IDを値に都道府県名を持つ配列を返します。
     *
     * 先頭には空白の項目を含みます。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array ['ids'] 都道府県IDの文字列配列、['names'] 都道府県名の文字列配列
     */
    public function fetchPrefectures($db) {
        $query = 'SELECT prefecture_id, name FROM prefectures ORDER BY prefecture_id';

        // 先頭に空白を追加
        $ids   = array('');
        $names = array('');

        $result = $db->executeQuery($query);
        for ($i = 0; $i < $result->size(); ++$i) {
            $row     = $result->get($i);
            $ids[]   = $row['prefecture_id'];
            $names[] = $row['name'];
        }

        return array(
            'ids'   => $ids,
            'names' => $names,
        );
    }

    /**
     * 都道府県リストをDBから取得し、キーに都道府県IDを値に都道府県名を持つ配列を返します。
     *
     * 先頭には空白の項目を含みます。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array ['ids'] 都道府県IDの文字列配列、['names'] 都道府県名の文字列配列
     */
    public function fetchTravelProvincesPrefectures($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array('provinces_id');

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
            $params[] = $data[$key];
        }

        $query = '
            SELECT
                prefectures.prefecture_id,
                MAX(prefectures.name) AS NAME,
                CASE
                    WHEN MAX(TRAVEL_PROVINCES_PREFECTURES1.provinces_id) IS NOT NULL
                        THEN 1
                    WHEN MAX(TRAVEL_PROVINCES_PREFECTURES2.provinces_id) IS NOT NULL
                        THEN 2
                    ELSE 3
                END                   AS SELECTED_CD
            FROM
                prefectures
                LEFT OUTER JOIN
                travel_provinces_prefectures AS TRAVEL_PROVINCES_PREFECTURES1
                ON
                    prefectures.prefecture_id                  = TRAVEL_PROVINCES_PREFECTURES1.prefecture_id
                AND TRAVEL_PROVINCES_PREFECTURES1.provinces_id = $1
                LEFT OUTER JOIN
                travel_provinces_prefectures AS TRAVEL_PROVINCES_PREFECTURES2
                ON
                    prefectures.prefecture_id                   = TRAVEL_PROVINCES_PREFECTURES2.prefecture_id
                AND TRAVEL_PROVINCES_PREFECTURES2.provinces_id <> $2
            GROUP BY
                prefectures.prefecture_id
            ORDER BY
                prefectures.prefecture_id;';

        $ids          = array();
        $names        = array();
        $selected_cds = array();

        $result = $db->executeQuery($query, $params);
        for ($i = 0; $i < $result->size(); ++$i) {
            $row = $result->get($i);
            $ids[]          = $row['prefecture_id'];
            $names[]        = $row['name'];
            $selected_cds[] = $row['selected_cd'];
        }

        return array(
            'ids'          => $ids,
            'names'        => $names,
            'selected_cds' => $selected_cds,
        );
    }

    /**
     * 都道府県リストをDBから取得し、キーに都道府県IDを値に都道府県名を持つ配列を返します。
     *
     * 先頭には空白の項目を含みます。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array ['ids'] 都道府県IDの文字列配列、['names'] 都道府県名の文字列配列
     */
    public function fetchNewTravelProvincesPrefectures($db) {

        $query = '
            SELECT
                prefectures.prefecture_id,
                MAX(prefectures.name) AS NAME,
                CASE
                    WHEN MAX(TRAVEL_PROVINCES_PREFECTURES2.provinces_id) IS NOT NULL
                        THEN 2
                    ELSE 3
                END                   AS SELECTED_CD
            FROM
                prefectures
                LEFT OUTER JOIN
                travel_provinces_prefectures AS TRAVEL_PROVINCES_PREFECTURES2
                ON
                    prefectures.prefecture_id = TRAVEL_PROVINCES_PREFECTURES2.prefecture_id
            GROUP BY
                prefectures.prefecture_id
            ORDER BY
                prefectures.prefecture_id;';

        $ids          = array();
        $names        = array();
        $selected_cds = array();

        $result = $db->executeQuery($query);
        for ($i = 0; $i < $result->size(); ++$i) {
            $row = $result->get($i);
            $ids[]          = $row['prefecture_id'];
            $names[]        = $row['name'];
            $selected_cds[] = $row['selected_cd'];
        }

        return array(
            'ids'          => $ids,
            'names'        => $names,
            'selected_cds' => $selected_cds,
        );
    }
}