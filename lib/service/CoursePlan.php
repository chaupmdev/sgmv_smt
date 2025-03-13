<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/**#@+
 * include files
 */
require_once dirname(__FILE__).'/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
/**#@-*/
/**
 * コース情報、プラン情報、コース・プラン情報を扱います。
 *
 * @package    Service
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_CoursePlan {
    /**
     * コース・プランIDの区切り文字
     */
    const ID_DELIMITER = '_';
    /**
     * コース・プラン名の区切り文字
     */
    const NAME_DELIMITER = '・';
    /**
     * コース・プランリストをDBから取得し、
     * キーにコースIDとプランIDを {@link Sgmov_Service_CoursePlan::ID_DELIMITER} で連結した文字列を、
     * 値にコース名とプラン名を {@link Sgmov_Service_CoursePlan::NAME_DELIMITER} で連結した文字列を
     * 持つ配列を返します。
     *
     * 先頭には空白の項目を含みます。
     *
     * 開始終了日が(開始日 <= 現在日付 <= 終了日)を満たすものを
     * コース表示順の昇順、プラン表示順の昇順で取得します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array ['ids'] コース・プランIDの文字列配列、['names'] コース・プラン名の文字列配列
     */
    public function fetchCoursePlanList($db) {
        $temp = $this->fetchCoursePlans($db);
        $ids = array();
        $names = array();
        // 先頭に空白を追加
        $ids[] = '';
        $names[] = '';
        $count = count($temp['course_ids']);
        for ($i = 0; $i < $count; $i++) {
            $ids[] = $temp['course_ids'][$i].Sgmov_Service_CoursePlan::ID_DELIMITER.$temp['plan_ids'][$i];
            $names[] = $temp['course_names'][$i].Sgmov_Service_CoursePlan::NAME_DELIMITER.$temp['plan_names'][$i];
        }
        return array('ids' => $ids,
            'names' => $names);
    }
    /**
     * コース・プランリストをDBから取得し、
     * それぞれのコードと名称を持つ配列を返します。
     *
     * 開始終了日が(開始日 <= 現在日付 <= 終了日)を満たすものを
     * コース表示順の昇順、プラン表示順の昇順で取得します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array ['course_ids'] コースIDの文字列配列、['course_names'] コース名の文字列配列、
     * ['plan_ids'] プランIDの文字列配列、['plan_names'] プラン名の文字列配列
     */
    public function fetchCoursePlans($db) {
        // TODO 未テスト
        $query = 'SELECT cource_id, plan_id, cources.name AS cource_name, plans.name AS plan_name';
        $query .= '     FROM (cources_plans JOIN cources ON cources.id = cources_plans.cource_id)';
        $query .= '         JOIN plans ON plans.id = cources_plans.plan_id';
        $query .= '     WHERE cources_plans.start_date <= current_date and cources_plans.stop_date >= current_date';
        $query .= '     ORDER BY cources.show_order, plans.show_order';
        $course_ids = array();
        $course_names = array();
        $plan_ids = array();
        $plan_names = array();
        $result = $db->executeQuery($query);
        for ($i = 0; $i < $result->size(); $i++) {
            $row = $result->get($i);
            $course_ids[] = $row['cource_id'];
            $course_names[] = $row['cource_name'];
            $plan_ids[] = $row['plan_id'];
            $plan_names[] = $row['plan_name'];
        }
        return array('course_ids' => $course_ids,
            'course_names' => $course_names,
            'plan_ids' => $plan_ids,
            'plan_names' => $plan_names);
    }
    /**
     * コースリストをDBから取得し、
     * キーにコースIDを値にコース名を持つ配列を返します。
     *
     * 先頭には空白の項目を含みます。
     *
     * 開始終了日が(開始日 <= 現在日付 <= 終了日)を満たすものを表示順の昇順で取得します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array ['ids'] コースIDの文字列配列、['names'] コース名の文字列配列
     */
    public function fetchCourseList($db) {
        $query = 'SELECT id, name FROM cources';
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
        return array('ids' => $ids,
            'names' => $names);
    }
    /**
     * プランリストをDBから取得し、
     * キーにプランIDを値にプラン名を持つ配列を返します。
     *
     * 先頭には空白の項目を含みます。
     *
     * 開始終了日が(開始日 <= 現在日付 <= 終了日)を満たすものを表示順の昇順で取得します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array ['ids'] プランIDの文字列配列、['names'] プラン名の文字列配列
     */
    public function fetchPlanList($db) {
        $query = 'SELECT id, name FROM plans';
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
        return array('ids' => $ids,
            'names' => $names);
    }
    /**
     * コースIDをキーとして該当プランリストをDBから取得し、
     * キーにプランIDを値にプラン名を持つ配列を返します。
     *
     * 先頭には空白の項目を含みます。
     *
     * 開始終了日が(開始日 <= 現在日付 <= 終了日)を満たすものを表示順の昇順で取得します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array ['ids'] プランIDの文字列配列、['names'] プラン名の文字列配列
     */
    public function fetchPlanListByCourse($db, $course) {
        $query = 'SELECT DISTINCT ON(plans.id) plans.id, plans.name FROM plans,cources_plans';
        $query .= '     WHERE plans.start_date <= current_date and plans.stop_date >= current_date';
        $query .= '         AND cources_plans.plan_id=plans.id';
        $query .= '         AND cources_plans.cource_id =$1';
        $query .= '     ORDER BY plans.id,plans.show_order;';
        $ids = array();
        $names = array();
        // 先頭に空白を追加
        $ids[] = '';
        $names[] = '';
        $result = $db->executeQuery($query, array($course));
        for ($i = 0; $i < $result->size(); $i++) {
            $row = $result->get($i);
            $ids[] = $row['id'];
            $names[] = $row['name'];
        }
        return array('ids' => $ids,
            'names' => $names);
    }

    /**
     * コースID・プランID・出発エリアID・到着エリアIDをキーとして該当プランリストの件数を取得し、
     * 1件の場合のみ、TRUEを返します。
     * （開始日 <= 現在日付 <= 終了日付）
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return true:1件存在する false:存在しない
     */
    public function checkCourcePlanFromTo($db, $coursePlans, $froms, $tos) {

        $query = 'SELECT
						count(*) as cnt
								FROM
								    cources_plans_from_to
								WHERE
								    cource_id = $1 AND
								    plan_id = $2 AND
								    from_area_id = $3 AND
								    to_area_id = $4 AND
								    current_date between start_date AND
								    stop_date;';

        foreach ($coursePlans as $cpVal) {

            $cp = explode(Sgmov_Service_CoursePlan::ID_DELIMITER, $cpVal, 2);

            // 出発地域の要素数、以下を繰り返す
            foreach ($froms as $frVal) {

                // 到着地域の要素数、以下を繰り返す
                foreach ($tos as $toVal) {

                    $result = $db->executeQuery($query, array($cp[0], $cp[1], $frVal, $toVal));

                    $row = $result->get(0);

                    if ($row['cnt'] != 1) {
                        return false;
                    }

                }

            }

        }
        return true;
    }

    /**
     * コースID・プランID・出発エリアIDをキーとして該当プランリストの件数を取得し、
     * 0件ではない場合のみ、TRUEを返します。
     * （開始日 <= 現在日付 <= 終了日付）
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return true:1件存在する　false:存在しない
     */
    public function checkCourcePlanFrom($db, $coursePlan, $from) {

        $query = 'SELECT
						count(*) as cnt
								FROM
								    cources_plans_from_to
								WHERE
								    cource_id = $1 AND
								    plan_id = $2 AND
								    from_area_id = $3 AND
								    current_date between start_date AND
								    stop_date;';

        $cp = explode(Sgmov_Service_CoursePlan::ID_DELIMITER, $coursePlan, 2);

        $result = $db->executeQuery($query, array($cp[0], $cp[1], $from));

        $row = $result->get(0);

        if ($row['cnt'] == 0) {
            return false;
        }

        return true;
    }


    /**
     * コースID・プランID・出発エリアIDをキーとして該当プランリストの件数を取得し、
     * 0件ではない場合のみ、TRUEを返します。
     * （開始日 <= 現在日付 <= 終了日付）
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return true:1件存在する　false:存在しない
     */
    public function checkCourcePlanFromTo2($db, $coursePlan, $from, $to) {

        $query = 'SELECT
                        count(*) as cnt
                                FROM
                                    cources_plans_from_to
                                WHERE
                                    cource_id = $1 AND
                                    plan_id = $2 AND
                                    from_area_id = $3 AND
                                    to_area_id = $4 AND
                                    current_date between start_date AND
                                    stop_date;';

        $cp = explode(Sgmov_Service_CoursePlan::ID_DELIMITER, $coursePlan, 2);

        $result = $db->executeQuery($query, array($cp[0], $cp[1], $from, $to));

        $row = $result->get(0);

        if ($row['cnt'] == 0) {
            return false;
        }

        return true;
    }
}
?>
