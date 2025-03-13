<?php
/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
/**#@-*/

 /**
 * 預かり設定マスタ
 *
 */
class Sgmov_Service_AzukariSetting {

    // トランザクションフラグ
    private $transactionFlg = TRUE;

    /**
     * トランザクションフラグ設定.
     * @param type $flg TRUE=内部でトランザクション処理する/FALSE=内部でトランザクション処理しない
     */
    public function setTrnsactionFlg($flg) {
        $this->transactionFlg = $flg;
    }

    public function checkExists($db, $eventsubId) {
    	$query = "SELECT COUNT(*) FROM azukari_setting WHERE eventsub_id = $1";

    	$result = $db->executeQuery($query, array($eventsubId));
        $resSize = $result->size();
        $returnResult = true;
        if(empty($resSize)) {
            $returnResult = false;
        }

        return $returnResult;
    }

    /**
     * イベント（物販用）insert
     * @param obj $db DBコネクション
     * @param array $data コミケ申込宅配データ
     */
    public function insert($db, $data) {
        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            "max_azukari_cd",
            "max_azukari_cd_sub",
            "eventsub_id"
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
                azukari_setting
            (
                max_azukari_cd,
                max_azukari_cd_sub,
                eventsub_id
            )
            VALUES
            (
                $1,
                $2,
                $3
            );';

        $query = preg_replace('/\s+/u', ' ', trim($query));

        if($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START INSERT azukari_setting #####");
        $res = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END INSERT azukari_setting #####");
        if($this->transactionFlg) {
            $db->commit();
        }
    }

     /**
     * イベント（物販用）insert
     * @param obj $db DBコネクション
     * @param array $data コミケ申込宅配データ
     */
    public function update($db, $data) {
        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            "max_azukari_cd",
            "max_azukari_cd_sub",
            "eventsub_id"
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
            UPDATE
                azukari_setting
            SET
                max_azukari_cd = $1,
                max_azukari_cd_sub = $2
           WHERE eventsub_id = $3;';

        $query = preg_replace('/\s+/u', ' ', trim($query));

        if($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START UPDATE azukari_setting #####");
        $res = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END UPDATE azukari_setting #####");
        if($this->transactionFlg) {
            $db->commit();
        }
    }

    /**
     * 
     * @param type $db
     * @return type
     */
    public function fetchByEventSubId($db, $eventsubId) {
        $query = 'SELECT * FROM azukari_setting WHERE eventsub_id = $1';
        
        if(empty($eventsubId)) {
            return array();
        }
        
        $result = $db->executeQuery($query, array($eventsubId));
        
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }
        
        $dataInfo = $result->get(0);
        
        return $dataInfo;
    }
}
