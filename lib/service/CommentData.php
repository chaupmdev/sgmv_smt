<?php

/**
 * include files.
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(false);

/**
 * コメントマスタを扱います。
 *
 * @package Service
 * @author (SMT)
 *
 */
class Sgmov_Service_CommentData {

    /**
     * コメントマスタをDBから取得し、配列を返します。
     *
     * @param Sgmov_Component_DB $db データベース接続 オブジェクト
     * @return array ["ids"] コメントID の文字列配列、 ["names"] コメント氏名の文字列配列
     */
    public function fetchCommentDatas($db, $commen_flg, $space = false) {

        $query = '
            SELECT
                "comment_data"."id",
                "comment_flg",
                "comment_title",
                "comment_address",
                "comment_name",
                "comment_office",
                "comment_text",
                TO_CHAR("comment_start_date",\'YYYY年MM月DD日\') AS COMMENT_START_DATE,
                TO_CHAR("comment_end_date",\'YYYY年MM月DD日\')   AS COMMENT_END_DATE,
                "centers"."name" AS centernm
            FROM
                "comment_data"
            LEFT JOIN "employment_centers" on
                "employment_centers"."id" = "comment_data"."comment_office"
            LEFT JOIN "centers" on
                "centers"."id" = "employment_centers"."center_id"
            WHERE
                "comment_flg" = $1
            ORDER BY
                "id" DESC';

        $ids         = array();
        $flgs        = array();
        $titles      = array();
        $addresses   = array();
        $names       = array();
        $offices     = array();
        $texts       = array();
        $start_dates = array();
        $end_dates   = array();
        $center_names = array();

        if ($space) {
            $ids[]         = '';
            $flgs[]        = '';
            $titles[]      = '';
            $addresses[]   = '';
            $names[]       = '';
            $offices[]     = '';
            $texts[]       = '';
            $start_dates[] = '';
            $end_dates[]   = '';
            $center_names[] = '';
        }

        $params = array();
        $params[] = $commen_flg;

        $result = $db->executeQuery($query, $params);
        for ($i = 0; $i < $result->size(); ++$i) {
            $row = $result->get($i);
            $ids[]         = $row['id'];
            $flgs[]        = $row['comment_flg'];
            $titles[]      = $row['comment_title'];
            $addresses[]   = $row['comment_address'];
            $names[]       = $row['comment_name'];
            $offices[]     = $row['comment_office'];
            $texts[]       = $row['comment_text'];
            $start_dates[] = $row['comment_start_date'];
            $end_dates[]   = $row['comment_end_date'];
            $center_names[]= $row['centernm'];
        }

        return array(
            'ids'          => $ids,
            'flgs'         => $flgs,
            'titles'       => $titles,
            'addresses'    => $addresses,
            'names'        => $names,
            'offices'      => $offices,
            'texts'        => $texts,
            'start_dates'  => $start_dates,
            'end_dates'    => $end_dates,
            'center_names' => $center_names,
        );
    }

    /**
     *
     * @param Sgmov_Component_DB $db
     * @return Sgmov_Component_DBResult
     */
    public function fetchCommentData($db, $criteria_values) {

        if (!is_array($criteria_values)) {
            $criteria_values = array('id' => $criteria_values);
        }

        $params           = array();
        $criteria_strings = array();

        // array_key_exists は使用できない環境..?
        $key = 'id';          if (key_exists($key, $criteria_values))   {   $params[] = $criteria_values[$key]; $criteria_strings[] = '"comment_data"."'.$key.'" = $' . count($params); }
        $key = 'comment_flg'; if (key_exists($key, $criteria_values))   {   $params[] = $criteria_values[$key]; $criteria_strings[] = '"'.$key.'" = $' . count($params);    }

        $query = '
            SELECT
                "comment_data"."id",
                "comment_flg",
                "comment_title",
                "comment_address",
                "comment_name",
                "comment_office",
                "comment_text",
                TO_CHAR("comment_start_date",\'YYYY/MM/DD\')     AS COMMENT_START_DATE,
                TO_CHAR("comment_start_date",\'YYYY年MM月DD日\') AS COMMENT_START_DATE_JAPANESE,
                TO_CHAR("comment_end_date",\'YYYY/MM/DD\')       AS COMMENT_END_DATE,
                TO_CHAR("comment_end_date",\'YYYY年MM月DD日\')   AS COMMENT_END_DATE_JAPANESE,
                "centers"."name" as centernm
            FROM
                "comment_data"
            LEFT JOIN "employment_centers" on
                "employment_centers"."id" = "comment_data"."comment_office"
            LEFT JOIN "centers" on
                "centers"."id" = "employment_centers"."center_id" ';
        if (!empty($criteria_strings)) {
            $query .= '
            WHERE ' . implode(' AND ', $criteria_strings);
        }
        $query .= '
            ORDER BY
                "comment_data"."id" ASC
            LIMIT 1
            OFFSET 0;';

        $result = $db->executeQuery($query, $params);
        $row = $result->get(0);
        return $row;
    }

    /**
     *
     * @param Sgmov_Component_DB $db
     * @return Sgmov_Component_DBResult
     */
    public function fetchCommentDataList($db, $criteria_values) {

        if (!is_array($criteria_values)) {
            $criteria_values = array('comment_flg' => $criteria_values);
        }

        $params           = array();
        $criteria_strings = array();

        // array_key_exists は使用できない環境..?
        $key = 'comment_flg';
        if (key_exists($key, $criteria_values)) {
            $params[]           = $criteria_values[$key];
            $criteria_strings[] = '"' . $key . '" = $' . count($params);
        }
        $key = 'comment_start_date';
        if (key_exists($key, $criteria_values)) {
            $params[]           = $criteria_values[$key];
            $criteria_strings[] = '"' . $key . '" <= $' . count($params);
        }
        $key = 'comment_end_date';
        if (key_exists($key, $criteria_values)) {
            $params[]           = $criteria_values[$key];
            $criteria_strings[] = '"' . $key . '" >= $' . count($params);
        }

        $query = '
            SELECT
                "comment_data"."id",
                "comment_flg",
                "comment_title",
                "comment_address",
                "comment_name",
                "comment_office",
                "comment_text",
                "comment_start_date",
                "comment_end_date",
                "centers"."name" as centernm
            FROM
                "comment_data"
            LEFT JOIN "employment_centers" on
                "employment_centers"."id" = "comment_data"."comment_office"
            LEFT JOIN "centers" on
                "centers"."id" = "employment_centers"."center_id" ';
        if (!empty($criteria_strings)) {
            $query .= ' WHERE ' . implode(' AND ', $criteria_strings) . '';
        }
        $query .= ' ORDER BY "comment_start_date", "comment_end_date", "comment_data"."id" ASC ';
        //$query .= ' LIMIT 1 OFFSET 0; ';

        $result = $db->executeQuery($query, $params);

        $row = array();
        for ($i = 0; $i < $result->size(); ++$i) {
            $row[] = $result->get($i);
        }

        return $row;
    }

    /**
     * コメントマスタのシーケンス番号を取得します
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function select_id($db) {

        $query    = 'SELECT max(id) + 1 AS id, nextval($1) FROM comment_data;';
        $params   = array();
        $params[] = 'comment_data_id_seq';

        $db->begin();
        $data = $db->executeQuery($query, $params);
        $db->commit();
        $row = $data->get(0);

        if ($row['id'] > $row['nextval']) {
            return $row['id'];
        }

        return $row['nextval'];
    }

    /**
     * コメントマスタ情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function _insertCommentData($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            'comment_id',
            'comment_flg',
            'comment_title',
            'comment_address',
            'comment_name',
            'comment_office',
            'comment_text',
            'comment_start_date',
            'comment_end_date',
        );

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }

        $query = '
            INSERT
            INTO
                comment_data
            (
                id,
                comment_flg,
                comment_title,
                comment_address,
                comment_name,
                comment_office,
                comment_text,
                comment_start_date,
                comment_end_date,
                created,
                modified
            )
            VALUES
            (
                $1,
                $2,
                $3,
                $4,
                $5,
                $6,
                $7,
                $8,
                $9,
                CURRENT_TIMESTAMP,
                CURRENT_TIMESTAMP
            );';

        $db->begin();
        Sgmov_Component_Log::debug("####### START INSERT comment_data #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END INSERT comment_data #####");
        $db->commit();
        return ($affecteds === 1);
    }

    /**
     * コメントマスタ情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function _updateCommentData($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            'comment_title',
            'comment_address',
            'comment_name',
            'comment_office',
            'comment_text',
            'comment_start_date',
            'comment_end_date',
            'comment_id',
        );

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }

        $query = '
            UPDATE
                comment_data
            SET
                comment_title      = $1,
                comment_address    = $2,
                comment_name       = $3,
                comment_office     = $4,
                comment_text       = $5,
                comment_start_date = $6,
                comment_end_date   = $7,
                modified = CURRENT_TIMESTAMP
            WHERE
                id = $8;';

        $db->begin();
        Sgmov_Component_Log::debug("####### START UPDATE comment_data #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END UPDATE comment_data #####");
        $db->commit();
        return ($affecteds === 1);
    }

    /**
     * コメントマスタ情報をDBから削除します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 削除するデータ
     */
    public function _deleteCommentData($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            'id',
        );

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }

        $query = '
            DELETE
            FROM
                comment_data
            WHERE
                id = $1;';

        $db->begin();
        Sgmov_Component_Log::debug("####### START DELETE comment_data #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END DELETE comment_data #####");
        $db->commit();
        return ($affecteds === 1);
    }

}