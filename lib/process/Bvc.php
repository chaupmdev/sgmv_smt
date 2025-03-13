<?php
/**
 * BVC 訪問見積もり申し込みDBから、データ送信済みで1年以上更新されていないデータを削除します。
 * @package    maintenance
 * @subpackage BVE
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
/**#@-*/

class Sgmov_Process_Bvc{

    public function execute(){

        // 対象レコードを削除
        $this->deleteData();

    }

    // 対象レコードを削除
    public function deleteData(){
        $db = Sgmov_Component_DB::getAdmin();
        $db->begin();
        $db->executeUpdate("DELETE FROM pre_campaign WHERE visit_estimate_id IN (SELECT id FROM visit_estimates WHERE send_result!=3 and batch_status=4 and modified < current_timestamp + '-1 years');");
        $db->executeUpdate("DELETE FROM visit_estimates WHERE send_result!=3 and batch_status=4 and modified<current_timestamp + '-1 years'");
        $db->commit();
    }
}

?>