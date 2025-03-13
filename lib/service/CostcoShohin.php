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
 * @copyright  2021-2021 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_CostcoShohin {

    /**
     * Undocumented function
     *
     * @param [type] $db
     * @param [type] $eventsubId
     * @param [type] $shohinCd
     * @return array
     */
    public function getInfo($db, $shohinCd) {
        $query = 'SELECT * FROM costco_shohin WHERE shohin_cd = $1 and start_date::date <= now()::date and end_date::date >= now()::date';
        if(empty($shohinCd)) {
            return array();
        }
        
        $result = $db->executeQuery($query, array($shohinCd));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }
        
        $dataInfo = $result->get(0);
        
        return $dataInfo;
    }

    /**
     * イベント手荷物受付サービスのお申し込み情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function insert($db, $data) {
        $keys = array(
            "shohin_cd",
            "shohin_name",
            "size",
            "option_id",
            "data_type",
            "juryo",
            "start_date",
            "end_date",
            "konposu"
        );

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }

        $query  = 'INSERT INTO costco_shohin
                (
                    shohin_cd,
                    shohin_name,
                    size,
                    option_id,
                    data_type,
                    juryo,
                    start_date,
                    end_date,
                    konposu,
                    created,
                    modified
                ) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);';

        $query = preg_replace('/\s+/u', ' ', trim($query));
        Sgmov_Component_Log::debug("####### START INSERT costco_shohin #####");
        $res = $db->executeUpdate($query, $params);
        if (empty($res)) {
            throw new Exception();
        }
        Sgmov_Component_Log::debug("####### END INSERT costco_shohin #####");
        Sgmov_Component_Log::debug($res);
        return $res;
    }

    /**
     * イベント手荷物受付サービスのお申し込み情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function edit($db, $data, $id) {
        $keys = array(
            "shohin_cd",
            "shohin_name",
            "size",
            "option_id",
            "data_type",
            "juryo",
            "konposu"
        );

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }
        $params[] = (int) $id;

        $query  = 'UPDATE costco_shohin
                SET
                    shohin_cd = $1,
                    shohin_name = $2,
                    size = $3,
                    option_id = $4,
                    data_type = $5,
                    juryo = $6,
                    konposu = $7,
                    modified = CURRENT_TIMESTAMP
                WHERE id = $8;';

        $query = preg_replace('/\s+/u', ' ', trim($query));
        Sgmov_Component_Log::debug("####### START UPDATE costco_shohin #####");
        $res = $db->executeUpdate($query, $params);
        if (empty($res)) {
            throw new Exception();
        }
        Sgmov_Component_Log::debug("####### END UPDATE costco_shohin #####");
        Sgmov_Component_Log::debug($res);
        return $res;
    }

    /**
     * イベント手荷物受付サービスのお申し込み情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function archive($db, $data, $id) {
        $keys = array(
            "end_date"
        );

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }
        $params[] = (int) $id;

        $query  = 'UPDATE costco_shohin
                SET end_date = $1,
                    modified = CURRENT_TIMESTAMP
                WHERE id = $2;';

        $query = preg_replace('/\s+/u', ' ', trim($query));
        $res = $db->executeUpdate($query, $params);
        if (empty($res)) {
            throw new Exception();
        }
        Sgmov_Component_Log::debug("####### Costco.archive() #####");
        Sgmov_Component_Log::debug($res);
        return $res;
    }

    public function getById($db, $id) {
        $query = 'SELECT * FROM costco_shohin WHERE id = $1';

        $result = $db->executeQuery($query, array($id));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }

        $res = $result->get(0);
        return $res;
    }

    public function checkAvailableShohinCd($db, $inputInfo, $id = null) {
        $startDt = $inputInfo['start_date'] . ' 00:00:00';
        $endDt = $inputInfo['end_date'] . ' 23:59:59';
        $query = "SELECT * FROM costco_shohin WHERE shohin_cd = $1 AND ((start_date <= $2 AND end_date >= $3) OR (start_date <= $4 AND end_date >= $5) OR (start_date >= $6 AND end_date <= $7))";
        $params = array(
            $inputInfo['shohin_cd'],
            $startDt,
            $startDt,
            $endDt,
            $endDt,
            $startDt,
            $endDt
        );

        if ($id !== null) {
            $query .= " AND id != $8";
            $params[] = $id;
        }

        $result = $db->executeQuery($query, $params);
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }

        $res = $result->get(0);
        return $res;
    }
    
    public function getInfoShohinListAll($db, $request) {
        $query = 'SELECT * FROM costco_shohin WHERE 1 = 1 ';
        $params = [];
        $i = 1;
        //id
        if (!empty($request['id']) && $request['id'] != '') {
            $query .= ' AND id = $'.$i;
            $params[] = $request['id'];
            $i++;
        }
        //shohin_cd
        if (!empty($request['shohin_cd']) && $request['shohin_cd'] != '') {
            $query .= ' AND shohin_cd LIKE $'.$i;
            $params[] = "%".$request['shohin_cd']."%";
            $i++;
        }
        //shohin_name
        if (!empty($request['shohin_name']) && $request['shohin_name'] != '') {
            $query .= ' AND shohin_name LIKE $'.$i;
            $params[] = "%".$request['shohin_name']."%";
            $i++;
        }
        //size_from
        if (!empty($request['size_from']) && $request['size_from'] != '') {
            $query .= ' AND size >= $'.$i;
            $params[] = $request['size_from'];
            $i++;
        }
        //size_to
        if (!empty($request['size_to']) && $request['size_to'] != '') {
            $query .= ' AND size <= $'.$i;
            $params[] = $request['size_to'];
            $i++;
        }
        //data_type
        if (!empty($request['data_type']) && $request['data_type'] != '') {
            $query .= ' AND data_type = $'.$i;
            $params[] = $request['data_type'];
            $i++;
        }
        
        //juryo_from
        if (!empty($request['juryo_from']) && $request['juryo_from'] != '') {
            $query .= ' AND juryo >= $'.$i;
            $params[] = $request['juryo_from'];
            $i++;
        }
        
        //juryo_to
        if (!empty($request['juryo_to']) && $request['juryo_to'] != '') {
            $query .= ' AND juryo <= $'.$i;
            $params[] = $request['juryo_to'];
            $i++;
        }
        
        //date_valid
        if (!empty($request['date_valid']) && $request['date_valid'] != '') {
            $query .= ' AND start_date <= $'.$i;
            $params[] = $request['date_valid'];
            $i++;
            
            $query .= ' AND end_date >= $'.$i;
            $params[] = $request['date_valid'];
            $i++;
            
        } else {
            $query .= ' AND start_date <= $'.$i;
            $params[] = date('Y-m-d');
            $i++;
            
            $query .= ' AND end_date >= $'.$i;
            $params[] = date('Y-m-d');
            $i++;
        }
        
        
        //option_id
        if (!empty($request['option_id']) && $request['option_id'] != '') {
            $query .= ' AND option_id = $'.$i;
            $params[] = $request['option_id'];
            $i++;
        }
        $query .= ' ORDER BY ID desc';
        $result = $db->executeQuery($query, $params);
        $returnList = [];
        for ($i = 0; $i < $result->size(); ++$i) {
            $row     = $result->get($i);
            $returnList[] = $row;
        }
        
        return $returnList;
    }
    
    public function getInfoShohinList($db, $maxRows, $startRow, $request) {
        $query = 'SELECT * FROM costco_shohin WHERE 1 = 1 ';
        $params = [];
        $i = 1;
        if (!empty($request['id']) && $request['id'] != '') {
            $query .= ' AND id = $'.$i;
            $params[] = $request['id'];
            $i++;
        }
        if (!empty($request['shohin_cd']) && $request['shohin_cd'] != '') {
            $query .= ' AND shohin_cd LIKE $'.$i;
            $params[] = "%".$request['shohin_cd']."%";
            $i++;
        }
        
        //shohin_name
        if (!empty($request['shohin_name']) && $request['shohin_name'] != '') {
            $query .= ' AND shohin_name LIKE $'.$i;
            $params[] = "%".$request['shohin_name']."%";
            $i++;
        }
        
        //size_from
        if (!empty($request['size_from']) && $request['size_from'] != '') {
            $query .= ' AND size >= $'.$i;
            $params[] = $request['size_from'];
            $i++;
        }
        
        //size_to
        if (!empty($request['size_to']) && $request['size_to'] != '') {
            $query .= ' AND size <= $'.$i;
            $params[] = $request['size_to'];
            $i++;
        }
        //data_type
        if (!empty($request['data_type']) && $request['data_type'] != '') {
            $query .= ' AND data_type = $'.$i;
            $params[] = $request['data_type'];
            $i++;
        }
        //juryo_from
        if (!empty($request['juryo_from']) && $request['juryo_from'] != '') {
            $query .= ' AND juryo >= $'.$i;
            $params[] = $request['juryo_from'];
            $i++;
        }
        //juryo_to
        if (!empty($request['juryo_to']) && $request['juryo_to'] != '') {
            $query .= ' AND juryo <= $'.$i;
            $params[] = $request['juryo_to'];
            $i++;
        }
        
        //date_valid
        if (!empty($request['date_valid']) && $request['date_valid'] != '') {
            $query .= ' AND start_date <= $'.$i;
            $params[] = $request['date_valid'];
            $i++;
            
            $query .= ' AND end_date >= $'.$i;
            $params[] = $request['date_valid'];
            $i++;
        } else {
            $query .= ' AND start_date <= $'.$i;
            $params[] = date('Y-m-d');
            $i++;
            
            $query .= ' AND end_date >= $'.$i;
            $params[] = date('Y-m-d');
            $i++;
        }
        
        
        //option_id
        if (!empty($request['option_id']) && $request['option_id'] != '') {
            $query .= ' AND option_id = $'.$i;
            $params[] = $request['option_id'];
            $i++;
        }
        
        
        $query .= ' ORDER BY ID desc';
        
        $query_limit = sprintf("%s LIMIT %d OFFSET %d", $query, $maxRows, $startRow);
        $result = $db->executeQuery($query_limit, $params);
        $returnList = [];
        for ($i = 0; $i < $result->size(); ++$i) {
            $row     = $result->get($i);
            //Data type:6：D24ではない、7:D24
            if ($row['data_type'] == '6') {
                $row['data_type'] = '6：D24でない';
            } else if ($row['data_type'] == '7') {
                $row['data_type'] = '7：D24';
            } else {
                $row['data_type'] = '';
            }
            
            //Date：format: yyyy/mm/dd
//            $row['start_date']  = date('Y/m/d', strtotime($row['start_date']));
//            $row['end_date']    = date('Y/m/d', strtotime($row['end_date']));
            $start_date = new DateTime($row['start_date']);
            $row['start_date']  = $start_date->format('Y/m/d');
            
            $end_date = new DateTime($row['end_date']);
            $row['end_date']  = $end_date->format('Y/m/d');
            
            $now =  new DateTime();
            if ($now <= $end_date) {
                $row['isEnable']  = 1;
            } else {
                $row['isEnable']  = 0;
            }
            
            $returnList[] = $row;
        }
        
        return $returnList;
    }
    
    public function deleteShohin($db, $id) {
        $query = 'DELETE FROM costco_shohin WHERE id = $1';
        $params = array($id);
        Sgmov_Component_Log::debug("####### START DELETE costco_shohin #####");
        $count = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### START DELETE costco_shohin #####");
        return $count;
    }
}

