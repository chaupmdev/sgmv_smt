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
Sgmov_Lib::useServices('CoursePlan');
/**#@-*/

 /**
 * 一時テーブルを使用して基本料金と設定可能な上下限金額情報を扱います。
 *
 * @package    Service
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_WorkPrice
{
    /**
     * 指定されたコース・プラン・出発エリアと対象日付の配列から基本料金と特価で設定可能な差額料金の
     * 上限値・下限値を取得します。
     * (個別金額設定用)
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param string $course_id コースID
     * @param string $plan_id プランID
     * @param string $from_area_id 出発エリアID
     * @param string $to_area_id 到着エリアID
     * @param string $exclude_special_price_id [optional] 金額合計から除外する特価のID
     * @return array
     * ['to_area_ids'] 到着エリアID
     * ['base_prices'] 基本料金
     * ['diff_maxs'] 上限値
     * ['diff_mins'] 下限値
     */
    public function fetchBaseDiffMinMaxPrice($db, $course_id, $plan_id, $from_area_id, $target_dates, $exclude_special_price_id = '-1')
    {
        // TODO 未テスト

        // トランザクション内で一時テーブルのレコードを作成する
        $db->begin();

        // 一時テーブルレコードの登録
        Sgmov_Component_Log::debug("一時テーブルIDを登録します。");
        $workId = $this->_nextWorkId($db);

        Sgmov_Component_Log::debug("一時テーブル特価日付レコードを登録します。");
        $this->_putSpecialPricesDatesWorks($db, $workId, $target_dates);

        // クエリ
        $query = 'SELECT';
        $query .= '        base_prices.to_area_id';
        $query .= '        ,base_prices.base_price';
        $query .= '        ,base_prices.max_price - base_prices.base_price - COALESCE(view_diff_min_max.diff_max, 0) AS upper_diff';
        $query .= '        ,base_prices.min_price - base_prices.base_price - COALESCE(view_diff_min_max.diff_min, 0) AS lower_diff';
        $query .= '    FROM';
        $query .= '        (';
        $query .= '            SELECT';
        $query .= '                    to_area_id';
        $query .= '                    ,base_price';
        $query .= '                    ,max_price';
        $query .= '                    ,min_price';
        $query .= '                FROM';
        $query .= '                    base_prices';
        $query .= '                WHERE';
        $query .= '                    base_prices.cource_id = $1';
        $query .= '                    AND base_prices.plan_id = $2';
        $query .= '                    AND base_prices.from_area_id = $3';
        $query .= '        ) AS base_prices';
        $query .= '            LEFT JOIN (';
        $query .= '                SELECT';
        $query .= '                        to_area_id';
        $query .= '                        ,MAX(view_diff_per_day.diff) AS diff_max';
        $query .= '                        ,MIN(view_diff_per_day.diff) AS diff_min';
        $query .= '                    FROM';
        $query .= '                        (';
        $query .= '                            SELECT';
        $query .= '                                    to_area_id';
        $query .= '                                    ,target_date';
        $query .= '                                    ,SUM(price_difference) AS diff';
        $query .= '                                FROM';
        $query .= '                                    special_price_details';
        $query .= '                                        JOIN special_prices_dates';
        $query .= '                                            ON special_price_details.special_price_id = special_prices_dates.special_price_id';
        $query .= '                                WHERE';
        $query .= '                                    EXISTS (';
        $query .= '                                        SELECT';
        $query .= '                                                \'X\'';
        $query .= '                                            FROM';
        $query .= '                                                special_prices';
        $query .= '                                            WHERE';
        $query .= '                                                special_prices.id = special_price_details.special_price_id';
        $query .= '                                                AND special_prices.draft_flag != \'1\'';
        $query .= '                                    )';
        $query .= '                                    AND cource_id = $4';
        $query .= '                                    AND plan_id = $5';
        $query .= '                                    AND from_area_id = $6';
        $query .= '                                    AND target_date IN (';
        $query .= '                                        SELECT';
        $query .= '                                                target_date';
        $query .= '                                            FROM';
        $query .= '                                                special_prices_dates_works';
        $query .= '                                            WHERE';
        $query .= '                                                special_price_id = $7';
        $query .= '                                    )';
        $query .= '                                    AND special_price_details.special_price_id != $8';
        $query .= '                                GROUP BY';
        $query .= '                                    to_area_id';
        $query .= '                                    ,target_date';
        $query .= '                        ) AS view_diff_per_day';
        $query .= '                    GROUP BY';
        $query .= '                        to_area_id';
        $query .= '            ) AS view_diff_min_max';
        $query .= '                ON base_prices.to_area_id = view_diff_min_max.to_area_id';

        $params = array($course_id,
                         $plan_id,
                         $from_area_id,
                         $course_id,
                         $plan_id,
                         $from_area_id,
                         $workId,
                         $exclude_special_price_id);

        $to_area_ids = array();
        $base_prices = array();
        $diff_maxs = array();
        $diff_mins = array();

        $result = $db->executeQuery($query, $params);
        for ($i = 0; $i < $result->size(); $i++) {
            $row = $result->get($i);

            $to_area_ids[] = $row['to_area_id'];
            $base_prices[] = $row['base_price'];
            $diff_maxs[] = $row['upper_diff'];
            $diff_mins[] = $row['lower_diff'];
        }

        // ロールバックにより一時テーブルの情報は削除される
        $db->rollback();
        return array('to_area_ids'=>$to_area_ids,
                         'base_prices'=>$base_prices,
                         'diff_maxs'=>$diff_maxs,
                         'diff_mins'=>$diff_mins);
    }

    /**
     * 指定されたコースプラン・出発エリア・対象日付の配列から特価で設定可能な差額料金の
     * 上限値・下限値を取得します。
     * (一括金額設定用)
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $course_plan_ids コースプランIDの配列
     * @param array $from_area_id 出発エリアIDの配列
     * @param array $to_area_id 到着エリアIDの配列
     * @param string $exclude_special_price_id [optional] 金額合計から除外する特価のID
     * @return array
     * ['diff_max'] 上限値
     * ['diff_min'] 下限値
     */
    public function fetchAllBaseDiffMinMaxPrice($db, $course_plan_ids, $from_area_ids, $to_area_ids, $target_dates, $exclude_special_price_id = '-1')
    {
        // TODO 未テスト

        // トランザクション内で一時テーブルのレコードを作成する
        $db->begin();

        // 一時テーブルレコードの登録
        Sgmov_Component_Log::debug("一時テーブルIDを登録します。");
        $workId = $this->_nextWorkId($db);

        Sgmov_Component_Log::debug("一時テーブル特価・到着エリア紐付けレコードを登録します。");
        $this->_putSpecialPricesToAreasWorks($db, $workId, $to_area_ids);

        Sgmov_Component_Log::debug("一時テーブル特価日付レコードを登録します。");
        $this->_putSpecialPricesDatesWorks($db, $workId, $target_dates);

        // クエリ
        $query = 'SELECT';
        $query .= '        MIN(upper_diff) AS diff_max';
        $query .= '        ,MAX(lower_diff) AS diff_min';
        $query .= '    FROM';
        $query .= '        (';
        $query .= '            SELECT';
        $query .= '                    base_prices.max_price - base_prices.base_price - COALESCE(view_diff_min_max.diff_max, 0) AS upper_diff';
        $query .= '                    ,base_prices.min_price - base_prices.base_price - COALESCE(view_diff_min_max.diff_min, 0) AS lower_diff';
        $query .= '                FROM';
        $query .= '                    (';
        $query .= '                        SELECT';
        $query .= '                                to_area_id';
        $query .= '                                ,base_price';
        $query .= '                                ,max_price';
        $query .= '                                ,min_price';
        $query .= '                            FROM';
        $query .= '                                base_prices';
        $query .= '                            WHERE';
        $query .= '                                base_prices.cource_id = $1';
        $query .= '                                AND base_prices.plan_id = $2';
        $query .= '                                AND base_prices.from_area_id = $3';
        $query .= '                    AND to_area_id IN (';
        $query .= '                        SELECT';
        $query .= '                                to_area_id';
        $query .= '                            FROM';
        $query .= '                                special_prices_to_areas_works';
        $query .= '                            WHERE';
        $query .= '                                special_price_id = $4';
        $query .= '                    )';
        $query .= '                    ) AS base_prices';
        $query .= '                        LEFT JOIN (';
        $query .= '                            SELECT';
        $query .= '                                    to_area_id';
        $query .= '                                    ,MAX(view_diff_per_day.diff) AS diff_max';
        $query .= '                                    ,MIN(view_diff_per_day.diff) AS diff_min';
        $query .= '                                FROM';
        $query .= '                                    (';
        $query .= '                                        SELECT';
        $query .= '                                                to_area_id';
        $query .= '                                                ,target_date';
        $query .= '                                                ,SUM(price_difference) AS diff';
        $query .= '                                            FROM';
        $query .= '                                                special_price_details';
        $query .= '                                                    JOIN special_prices_dates';
        $query .= '                                                        ON special_price_details.special_price_id = special_prices_dates.special_price_id';
        $query .= '                                            WHERE';
        $query .= '                                                EXISTS (';
        $query .= '                                                    SELECT';
        $query .= '                                                            \'X\'';
        $query .= '                                                        FROM';
        $query .= '                                                            special_prices';
        $query .= '                                                        WHERE';
        $query .= '                                                            special_prices.id = special_price_details.special_price_id';
        $query .= '                                                            AND special_prices.draft_flag != \'1\'';
        $query .= '                                                )';
        $query .= '                                                AND cource_id = $5';
        $query .= '                                                AND plan_id = $6';
        $query .= '                                                AND from_area_id = $7';
        $query .= '                                                AND target_date IN (';
        $query .= '                                                    SELECT';
        $query .= '                                                            target_date';
        $query .= '                                                        FROM';
        $query .= '                                                            special_prices_dates_works';
        $query .= '                                                        WHERE';
        $query .= '                                                            special_price_id = $8';
        $query .= '                                                )';
        $query .= '                                                AND special_price_details.special_price_id != $9';
        $query .= '                    AND to_area_id IN (';
        $query .= '                        SELECT';
        $query .= '                                to_area_id';
        $query .= '                            FROM';
        $query .= '                                special_prices_to_areas_works';
        $query .= '                            WHERE';
        $query .= '                                special_price_id = $10';
        $query .= '                    )';
        $query .= '                                            GROUP BY';
        $query .= '                                                to_area_id';
        $query .= '                                                ,target_date';
        $query .= '                                    ) AS view_diff_per_day';
        $query .= '                                GROUP BY';
        $query .= '                                    to_area_id';
        $query .= '                        ) AS view_diff_min_max';
        $query .= '                            ON base_prices.to_area_id = view_diff_min_max.to_area_id';
        $query .= '        ) AS view_diffs';

        $diff_max = NULL;
        $diff_min = NULL;
        foreach ($course_plan_ids as $course_plan_id) {
            $splits = explode(Sgmov_Service_CoursePlan::ID_DELIMITER, $course_plan_id, 2);
            $course_id = $splits[0];
            $plan_id = $splits[1];
            foreach ($from_area_ids as $from_area_id) {
                $params = array($course_id,
                                 $plan_id,
                                 $from_area_id,
                                 $workId,
                                 $course_id,
                                 $plan_id,
                                 $from_area_id,
                                 $workId,
                                 $exclude_special_price_id,
                                 $workId);

                $result = $db->executeQuery($query, $params);
                $row = $result->get(0);
                if (is_null($diff_max) || $row['diff_max'] < $diff_max) {
                    // 上限に設定可能な最小値
                    $diff_max = $row['diff_max'];
                }
                if (is_null($diff_min) || $row['diff_min'] > $diff_min) {
                    // 下限に設定可能な最大値
                    $diff_min = $row['diff_min'];
                }
            }
        }

        // ロールバックにより一時テーブルの情報は削除される
        $db->rollback();
        return array('diff_max'=>$diff_max,
                         'diff_min'=>$diff_min);
    }

    // こちらは重くて動きません。
    //    /**
    //     * 指定されたコースプラン・出発エリア・対象日付の配列から特価で設定可能な差額料金の上下限値を取得します。
    //     * (一括金額設定用)
    //     *
    //     * @param Sgmov_Component_DB $db DB接続
    //     * @param array $course_plan_ids コースプランIDの配列
    //     * @param array $from_area_id 出発エリアIDの配列
    //     * @param array $to_area_id 到着エリアIDの配列
    //     * @param string $exclude_special_price_id [optional] 金額合計から除外する特価のID
    //     * @return array
    //     * ['diff_max'] 上限値
    //     * ['diff_min'] 下限値
    //     */
    //    public function fetchAllBaseDiffMinMaxPrice($db, $course_plan_ids, $from_area_ids, $to_area_ids, $target_dates, $exclude_special_price_id = '-1')
    //    {
    //        // TODO 未テスト
    //
    //        // トランザクションを開始
    //        $db->begin();
    //
    //        // 一時テーブルレコードの登録
    //        Sgmov_Component_Log::debug("一時テーブルIDを登録します。");
    //        $workId = $this->_nextWorkId($db);
    //
    //        Sgmov_Component_Log::debug("一時テーブル特価・コース・プラン紐付けレコードを登録します。");
    //        $this->_putCoursesPlansSpecialPricesWorks($db, $workId, $course_plan_ids);
    //
    //        Sgmov_Component_Log::debug("一時テーブル特価・出発エリア紐付けレコードを登録します。");
    //        $this->_putFromAreasSpecialPricesWorks($db, $workId, $from_area_ids);
    //
    //        Sgmov_Component_Log::debug("一時テーブル特価・到着エリア紐付けレコードを登録します。");
    //        $this->_putSpecialPricesToAreasWorks($db, $workId, $to_area_ids);
    //
    //        Sgmov_Component_Log::debug("一時テーブル特価日付レコードを登録します。");
    //        $this->_putSpecialPricesDatesWorks($db, $workId, $target_dates);
    //
    //        // クエリ
    //        $query = 'SELECT';
    //        $query .= '        MIN(upper_diff) AS diff_max';
    //        $query .= '        ,MAX(lower_diff) AS diff_min';
    //        $query .= '    FROM';
    //        $query .= '        (';
    //        $query .= '            SELECT';
    //        $query .= '                    base_prices.max_price - base_prices.base_price - COALESCE(view_diff_min_max.diff_max, 0) AS upper_diff';
    //        $query .= '                    ,base_prices.min_price - base_prices.base_price - COALESCE(view_diff_min_max.diff_min, 0) AS lower_diff';
    //        $query .= '                FROM';
    //        $query .= '                    base_prices';
    //        $query .= '                        LEFT JOIN (';
    //        $query .= '                            SELECT';
    //        $query .= '                                    cource_id';
    //        $query .= '                                    ,plan_id';
    //        $query .= '                                    ,from_area_id';
    //        $query .= '                                    ,to_area_id';
    //        $query .= '                                    ,MAX(view_diff_per_day.diff) AS diff_max';
    //        $query .= '                                    ,MIN(view_diff_per_day.diff) AS diff_min';
    //        $query .= '                                FROM';
    //        $query .= '                                    (';
    //        $query .= '                                        SELECT';
    //        $query .= '                                                cource_id';
    //        $query .= '                                                ,plan_id';
    //        $query .= '                                                ,from_area_id';
    //        $query .= '                                                ,to_area_id';
    //        $query .= '                                                ,target_date';
    //        $query .= '                                                ,SUM(price_difference) AS diff';
    //        $query .= '                                            FROM';
    //        $query .= '                                                special_price_details';
    //        $query .= '                                                    JOIN special_prices_dates';
    //        $query .= '                                                        ON special_price_details.special_price_id = special_prices_dates.special_price_id';
    //        $query .= '                                            WHERE';
    //        $query .= '                                                EXISTS (';
    //        $query .= '                                                    SELECT';
    //        $query .= '                                                            \'X\'';
    //        $query .= '                                                        FROM';
    //        $query .= '                                                            special_prices';
    //        $query .= '                                                        WHERE';
    //        $query .= '                                                            special_prices.id = special_price_details.special_price_id';
    //        $query .= '                                                            AND special_prices.draft_flag != \'1\'';
    //        $query .= '                                                )';
    //        $query .= '                                                AND target_date IN (';
    //        $query .= '                                                    SELECT';
    //        $query .= '                                                            target_date';
    //        $query .= '                                                        FROM';
    //        $query .= '                                                            special_prices_dates_works';
    //        $query .= '                                                        WHERE';
    //        $query .= '                                                            special_price_id = $1';
    //        $query .= '                                                )';
    //        $query .= '                                                AND special_price_details.special_price_id != $2';
    //        $query .= '                                            GROUP BY';
    //        $query .= '                                                cource_id';
    //        $query .= '                                                ,plan_id';
    //        $query .= '                                                ,from_area_id';
    //        $query .= '                                                ,to_area_id';
    //        $query .= '                                                ,target_date';
    //        $query .= '                                    ) AS view_diff_per_day';
    //        $query .= '                                GROUP BY';
    //        $query .= '                                    cource_id';
    //        $query .= '                                    ,plan_id';
    //        $query .= '                                    ,from_area_id';
    //        $query .= '                                    ,to_area_id';
    //        $query .= '                        ) AS view_diff_min_max';
    //        $query .= '                            ON base_prices.cource_id = view_diff_min_max.cource_id';
    //        $query .= '                            AND base_prices.plan_id = view_diff_min_max.plan_id';
    //        $query .= '                            AND base_prices.from_area_id = view_diff_min_max.from_area_id';
    //        $query .= '                            AND base_prices.to_area_id = view_diff_min_max.to_area_id';
    //        $query .= '                WHERE';
    //        $query .= '                    (';
    //        $query .= '                        base_prices.cource_id';
    //        $query .= '                        ,base_prices.plan_id';
    //        $query .= '                    ) IN (';
    //        $query .= '                        SELECT';
    //        $query .= '                                cource_id';
    //        $query .= '                                ,plan_id';
    //        $query .= '                            FROM';
    //        $query .= '                                cources_plans_special_prices_works';
    //        $query .= '                            WHERE';
    //        $query .= '                                special_price_id = $3';
    //        $query .= '                    )';
    //        $query .= '                    AND base_prices.from_area_id IN (';
    //        $query .= '                        SELECT';
    //        $query .= '                                from_area_id';
    //        $query .= '                            FROM';
    //        $query .= '                                from_areas_special_prices_works';
    //        $query .= '                            WHERE';
    //        $query .= '                                special_price_id = $4';
    //        $query .= '                    )';
    //        $query .= '                    AND base_prices.to_area_id IN (';
    //        $query .= '                        SELECT';
    //        $query .= '                                to_area_id';
    //        $query .= '                            FROM';
    //        $query .= '                                special_prices_to_areas_works';
    //        $query .= '                            WHERE';
    //        $query .= '                                special_price_id = $5';
    //        $query .= '                    )';
    //        $query .= '        ) AS sp_diff';
    //
    //        $params = array($workId,
    //                         $exclude_special_price_id,
    //                         $workId,
    //                         $workId,
    //                         $workId);
    //
    //        Sgmov_Component_Log::debug("クエリを実行します。");
    //        $result = $db->executeQuery($query, $params);
    //        $row = $result->get(0);
    //
    //        // ロールバックにより一時テーブルの情報は削除される
    //        $db->rollback();
    //        return array('diff_max'=>$row['diff_max'],
    //                         'diff_min'=>$row['diff_min']);
    //    }

    /**
     * 上下限取得用の一時テーブルのIDをシーケンスから取得します。
     * @param Sgmov_Component_DB $db DB接続
     * @return string 取得したID
     */
    public function _nextWorkId($db)
    {
        // TODO 未テスト
        $query = 'SELECT nextval(\'special_prices_works_id_seq\') AS nextval';
        $result = $db->executeQuery($query);
        $row = $result->get(0);
        return $row['nextval'];
    }

    /**
     * 一時テーブル特価日付レコードを一括登録します。
     * @param Sgmov_Component_DB $db DB接続
     * @param string $workId 一時テーブルID
     * @param array $targetDays 対象日付の配列
     */
    public function _putSpecialPricesDatesWorks($db, $workId, $targetDays)
    {
        // TODO 未テスト
        foreach ($targetDays as $targetDay) {
            $row = $workId;
            $row .= "\t";
            $row .= $targetDay;
            $rows[] = $row;
        }
        $db->executeCopyFrom('special_prices_dates_works', $rows);
    }

    /**
     * 一時テーブル特価日付レコードを一括登録します。
     * @param Sgmov_Component_DB $db DB接続
     * @param string $workId 一時テーブルID
     * @param array $fromAreaIds 出発エリアIDの配列
     */
    public function _putFromAreasSpecialPricesWorks($db, $workId, $fromAreaIds)
    {
        // TODO 未テスト
        foreach ($fromAreaIds as $fromAreaId) {
            $row = $workId;
            $row .= "\t";
            $row .= $fromAreaId;
            $rows[] = $row;
        }
        $db->executeCopyFrom('from_areas_special_prices_works', $rows);
    }

    /**
     * 一時テーブル特価日付レコードを一括登録します。
     * @param Sgmov_Component_DB $db DB接続
     * @param string $workId 一時テーブルID
     * @param array $toAreaIds 到着エリアIDの配列
     */
    public function _putSpecialPricesToAreasWorks($db, $workId, $toAreaIds)
    {
        // TODO 未テスト
        foreach ($toAreaIds as $toAreaId) {
            $row = $workId;
            $row .= "\t";
            $row .= $toAreaId;
            $rows[] = $row;
        }
        $db->executeCopyFrom('special_prices_to_areas_works', $rows);
    }

    /**
     * 一時テーブル特価・コース・プラン紐付けレコードを一括登録します。
     * @param Sgmov_Component_DB $db DB接続
     * @param string $workId 一時テーブルID
     * @param array $coursePlanIds コースプランIDの配列
     */
    public function _putCoursesPlansSpecialPricesWorks($db, $workId, $coursePlanIds)
    {
        // TODO 未テスト
        foreach ($coursePlanIds as $coursePlanId) {
            $splits = explode(Sgmov_Service_CoursePlan::ID_DELIMITER, $coursePlanId, 2);
            $row = $workId;
            $row .= "\t";
            $row .= $splits[0];
            $row .= "\t";
            $row .= $splits[1];
            $rows[] = $row;
        }
        $db->executeCopyFrom('cources_plans_special_prices_works', $rows);
    }

}
?>
