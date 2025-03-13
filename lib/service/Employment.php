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
 * 採用エントリー情報を扱います。
 *
 * @package Service
 * @author     K.Hamada(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_Employment
{

    /**
     * 採用エントリー情報をDBに保存します。
     *
     * <ul><li>
     * $data['employ_type_cd']:採用区分コード
     * </li><li>
     * $data['job_type_cd']:職種コード
     * </li></ul>
     * $data['name']:お名前
     * </li></ul>
     * $data['furigana']:フリガナ
     * </li><li>
     * $data['age_cd_sel']:年齢コード
     * </li></ul>
     * $data['tel']:電話番号
     * </li><li>
     * $data['mail']:メールアドレス
     * </li><li>
     * $data['zip']:郵便番号
     * </li><li>
     * $data['pref_id']:都道府県ID
     * </li><li>
     * $data['address']:住所
     * </li><li>
     * $data['resume']:志望動機・PR
     * </li></ul>
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function select_id($db)
    {

        $query = 'SELECT nextval($1);';
        $params = array();
        $params[] = 'employments_id_seq';

        $db->begin();
        $data = $db->executeQuery($query, $params);
        $db->commit();
        $row = $data->get(0);

        return $row['nextval'];
    }


    public function insert($db, $data)
    {
        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array('id',
                         'employ_type_cd',
                         'job_type_cd',
                         'name',
                         'furigana',
                         'age_cd',
                         'tel',
                         'mail',
                         'zip',
                         'pref_id',
                         'address',
                         'resume');

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }

        $query = 'INSERT INTO employments(';
        $query .= '            id, modify_user_account, created, modified, closed, state_type, claim_flag, ';
        $query .= '            employ_type_cd, job_type_cd, name, furigana, age_cd, tel, ';
        $query .= '            mail, zip, pref_id, address, resume)';
        $query .= '    VALUES ($1, null, now(), now(), null, 0, false, ';
        $query .= '            $2, $3, $4, $5, $6, $7, ';
        $query .= '            $8, $9, $10, $11, $12 );';

        $db->begin();
        Sgmov_Component_Log::debug("####### START INSERT employments #####");
        $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END INSERT employments #####");
        $db->commit();

        $employ_center_id = $data['work_place_flag'];

        //選択された希望地の数だけ　登録する
        for($i = 0;$i<count($employ_center_id);$i++){

            $employ_id = $data['id'];

            $center_id = $employ_center_id[$i];
            $params_2 = array($employ_id, $center_id);

            $query_2 = 'INSERT INTO employments_employment_centers(';
            $query_2 .= '     employment_id, employment_center_id)';
            $query_2 .= '     VALUES ($1, $2);';

            $db->begin();
            Sgmov_Component_Log::debug("####### START INSERT employments_employment_centers #####");
            $db->executeUpdate($query_2, $params_2);
            Sgmov_Component_Log::debug("####### END INSERT employments_employment_centers #####");
            $db->commit();

        }
    }
}
?>
