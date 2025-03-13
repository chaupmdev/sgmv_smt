<?php
/**
 * @package    ClassDefFile
 * @author     GiapLN  FPT Software
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
/**#@-*/

class Sgmov_Service_EventLogin {
    
    /**
     * fetchEventLoginByEmail。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param string $email
     * @return array
     */
    public function fetchEventLoginByEmail($db, $email) {
        if(empty($email)) {
            return array();
        }
        
        $query = 'SELECT * FROM event_member WHERE mail = $1';
        

        $result = $db->executeQuery($query, array($email));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }
        
        $dataInfo = $result->get(0);
        
        return $dataInfo;
    }

    /**
     * fetchEventLoginValid。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param string $email
     * @return array 
     */
    public function fetchEventLoginValid($db, $email) {
        if(empty($email)) {
            return array();
        }
        
        $query = 'SELECT * FROM event_member WHERE mail = $1 AND login_yuko_flag = 1';
        

        $result = $db->executeQuery($query, array($email));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }
        
        $dataInfo = $result->get(0);
        
        return $dataInfo;
    }
    
    public function procLockDate($db, $id) {
        $query = 'Update event_member '
                . "Set lock_date = '".date("Y-m-d H:i:s", strtotime("+5 minutes"))."'"
                . ', modified = now()'
                . ', update_no = update_no + 1'
                . ' WHERE id = $1';
        
        $db->begin();
        Sgmov_Component_Log::debug("####### START UPDATE event_member #####");
        $db->executeUpdate($query, array($id));
        Sgmov_Component_Log::debug("####### END UPDATE event_member #####");
        $db->commit();
    }
    /**
     * 適用キャンペーン情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param void
     */

    public function insert($db, $dataRow) {

        // パラメータのチェック
        $params = array(
            $dataRow['mail'], 
            $dataRow['password'], 
            $dataRow['login_yuko_flag'], 
            $dataRow['password_update_flag'],
            $dataRow['created'], 
            $dataRow['modified'], 
            $dataRow['update_no']);
        $query = 'INSERT INTO event_member('
                . 'mail'
                . ', password'
                //. ', event_type'
                //. ', event_id'
                //. ', eventsub_id'
                . ', login_yuko_flag'
                . ', password_update_flag'
                . ', created'
                . ', modified'
                . ', update_no';

        $query .= ')VALUES (';

        $query .= '$1, $2, $3, $4, $5, $6, $7';

        $query .= ');';

        $db->begin();
        Sgmov_Component_Log::debug("####### START INSERT event_member #####");
        $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END INSERT event_member #####");
        $db->commit();

    }
    
    public function updateResetPass($db, $dataRow) {
        // パラメータのチェック
        $params = array( 
            $dataRow['password'], 
            $dataRow['login_yuko_flag'], 
            $dataRow['password_update_flag'],
            $dataRow['modified'], 
            $dataRow['mail']
            );
        $query = 'UPDATE event_member '
                . 'SET password = $1'
                . ', login_yuko_flag = $2'
                . ', password_update_flag = $3'
                . ', modified = $4'
                . ', update_no = update_no  + 1 ';
        $query .= 'WHERE  mail = $5';

        $db->begin();
        Sgmov_Component_Log::debug("####### START UPDATE event_member #####");
        $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END UPDATE event_member #####");
        $db->commit();

    }
    
    public function updateMemberInfo($db, $dataRow) {
        
        if (isset($dataRow['password'])) {
            $params = array( 
                $dataRow['name_sei'], 
                $dataRow['name_mei'], 
                $dataRow['zip'],
                $dataRow['pref_id'], 
                $dataRow['address'],
                $dataRow['building'],
                $dataRow['tel'],
                $dataRow['password_update_flag'],
                $dataRow['modified'], 
                $dataRow['password'], 
                $dataRow['id']
            );
        } else {
            $params = array( 
                $dataRow['name_sei'], 
                $dataRow['name_mei'], 
                $dataRow['zip'],
                $dataRow['pref_id'], 
                $dataRow['address'],
                $dataRow['building'],
                $dataRow['tel'],
                $dataRow['password_update_flag'],
                $dataRow['modified'], 
                $dataRow['id']
            );
        }
        $query = 'UPDATE event_member '
                . 'SET name_sei = $1'
                . ', name_mei = $2'
                . ', zip = $3'
                . ', pref_id = $4'
                . ', address = $5'
                . ', building = $6'
                . ', tel = $7'
                . ', password_update_flag = $8'
                . ', modified = $9'
                . ', update_no = update_no  + 1 ';
        if (isset($dataRow['password'])) {
            $query .=  ', password = $10 ';
            $query .= ' WHERE id = $11';
        } else {
            $query .= ' WHERE id = $10';
        }

        $db->begin();
        Sgmov_Component_Log::debug("####### START UPDATE event_member #####");
        $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END UPDATE event_member #####");
        $db->commit();
    }
    
    public function updatePassChange($db, $dataRow) {
        $params = array( 
            $dataRow['password'], 
            $dataRow['password_update_flag'], 
            $dataRow['modified'], 
            $dataRow['id']
        );
        
        $query = 'UPDATE event_member '
                . 'SET password = $1'
                . ', password_update_flag = $2'
                . ', modified = $3'
                . ', update_no = update_no  + 1 ';
        $query .= ' WHERE id = $4';

        $db->begin();
        Sgmov_Component_Log::debug("####### START UPDATE event_member #####");
        $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END UPDATE event_member #####");
        $db->commit();

    }
    
    
}