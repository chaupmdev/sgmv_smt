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
 * 訪問見積もり申し込み情報を扱います。
 *
 * @package Service
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_VisitEstimate
{

    /**
     * 訪問見積もり申し込み情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function select_id($db)
    {

        $query = 'SELECT nextval($1);';
        $params = array();
        $params[] = 'visit_estimates_id_seq';

        $db->begin();
        $data = $db->executeQuery($query, $params);
        $db->commit();
        $row = $data->get(0);

        return $row['nextval'];
    }

    /**
     * 訪問見積もり申し込み情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function insert($db, $data)
    {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            'id',
            'pre_exist_flag',
            'company_flag',
            'course_id',
            'plan_id',
            'pre_aircon_exist_flag',
            'from_area_id',
            'to_area_id',
            'move_date',
            'pre_base_price',
            'pre_estimate_price',
            'visit_date1',
            'visit_date2',
            'cur_zip',
            'cur_pref_id',
            'cur_address',
            'cur_elevator_cd',
            'cur_floor',
            'cur_road_cd',
            'new_zip',
            'new_pref_id',
            'new_address',
            'new_elevator_cd',
            'new_floor',
            'new_road_cd',
            'name',
            'furigana',
            'tel',
            'tel_type_cd',
            'tel_other',
            'contact_available_cd',
            'contact_start_cd',
            'contact_end_cd',
            'mail',
            'note',
            'company_name',
            'company_furigana',
            'charge_name',
            'charge_furigana',
            'contact_method_cd',
            'num_people',
            'tsubo_su',
        );

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }

        $query = 'INSERT INTO visit_estimates(';
        $query .= '               id,modify_user_account, created, modified, send_result, batch_status, retry_count, uke_no, ';
        $query .= '               pre_exist_flag, company_flag, course_id, plan_id, pre_aircon_exist_flag, from_area_id, to_area_id, move_date, pre_base_price, pre_estimate_price, ';
        $query .= '               visit_date1, visit_date2, cur_zip, cur_pref_id, cur_address, cur_elevator_cd, cur_floor, cur_road_cd, ';
        $query .= '               new_zip, new_pref_id, new_address, new_elevator_cd, new_floor, new_road_cd, ';
        $query .= '               name, furigana, tel, tel_type_cd, tel_other, contact_available_cd, contact_start_cd, contact_end_cd, mail, note, ';
        $query .= '               company_name, company_furigana, charge_name, charge_furigana, contact_method_cd, num_people, tsubo_su';

        $adds = array(
            'other_operation_id',
            'apartment_id',
            'work_summary_cd',
        );
        foreach ($adds as $add) {
            if (isset($data[$add]) && strlen($data[$add]) > 0) {
                $query .= ', ' . $add;
            }
        }

        $query .= ')VALUES (';
        $query .= '$1,null,now(),now(),0,0,0,null,';
        $query .= '$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,';
        $query .= '$12,$13,$14,$15,$16,$17,$18,$19,';
        $query .= '$20,$21,$22,$23,$24,$25,';
        $query .= '$26,$27,$28,$29,$30,$31,$32,$33,$34,$35,';
        $query .= '$36,$37,$38,$39,$40,$41,$42';

        foreach ($adds as $add) {
            if (isset($data[$add]) && strlen($data[$add]) > 0) {
                $query .= ', ' . pg_escape_string($data[$add]);
            }
        }

        $query .= ');';
Sgmov_Component_Log::debug($query);Sgmov_Component_Log::debug($params);
        $db->begin();
        Sgmov_Component_Log::debug("####### START INSERT visit_estimates #####");
        $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END INSERT visit_estimates #####");
        $db->commit();
    }
}