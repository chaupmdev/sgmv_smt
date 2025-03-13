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
 * 宅配箱マスタ情報を扱います。
 *
 * @package Service
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_EmployRegist {
        
    // トランザクションフラグ
    private $transactionFlg = TRUE;

    /**
     * トランザクションフラグ設定.
     * @param type $flg TRUE=内部でトランザクション処理する/FALSE=内部でトランザクション処理しない
     */
    public function setTrnsactionFlg($flg) {
        $this->transactionFlg = $flg;
    }

    public function insert($db, $data) {
        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            "personal_name",
            "personal_name_furi",
            "sei",
            "age",
            "zip",
            "pref_id",
            "address",
            "building",
            "tel",
            "mail",
            "employ_cd",
            "center_id",
            "occupation_cd",
            "question",
            "created",
            "modified",
            "current_employment_status",
            "contact_time"
        );

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }

        $query  = '
            INSERT
            INTO
                employ_regist
            (
                personal_name,
                personal_name_furi,
                sei,
                age,
                zip,
                pref_id,
                address,
                building,
                tel,
                mail,
                employ_cd,
                center_id,
                occupation_cd,
                question,
                created,
                modified,
                current_employment_status,
                contact_time
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
                $10,
                $11,
                $12,
                $13,
                $14,
                $15,
                $16,
                $17,
                $18

            );';

        $query = preg_replace('/\s+/u', ' ', trim($query));

        if($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START INSERT employ_regist #####");
        $res = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END INSERT employ_regist #####");
        if($this->transactionFlg) {
            $db->commit();
        }
    }

    /**
     * 最大のidを取得する。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function select_id($db) {

        $query = 'SELECT max(id) as id from employ_regist;';
      
        $data = $db->executeQuery($query);

        $row = $data->get('id');

        return $row['id'];
    }
}    