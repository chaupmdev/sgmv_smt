<?php
/**
 * @package    ClassDefFile
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/**#@+
 * include files
 */
require_once dirname(__FILE__).'/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);

/**#@-*/
/**
 * 訪問見積もり申し込みにひもづいた適用キャンペーン情報を扱います。
 *
 * @package Service
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_PreCampaign {
	
    /**
     * 適用キャンペーン情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function insert($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array('visit_estimate_id',
            'show_order',
            'campaign_name',
            'campaign_content',
            'campaign_start',
            'campaing_end',
            'campaign_price');
        
        // パラメータのチェック
        $params = array();

        $query = 'INSERT INTO pre_campaign(visit_estimate_id, show_order, campaign_name, campaign_content, campaign_start, campaing_end, campaign_price, campaign_division';
        $query .= ')VALUES (';
        $query .= '$1, $2, $3, $4, $5, $6, $7, $8';
        $query .= ');';
        
        $db->begin();

        for ($i = 0; $i < count($data); $i++) {
            Sgmov_Component_Log::debug("####### START INSERT pre_campaign #####");
            $db->executeUpdate($query, array($data[$i][0], $data[$i][1], $data[$i][2], $data[$i][3], $data[$i][4], $data[$i][5], $data[$i][6], $data[$i][7]));
            Sgmov_Component_Log::debug("####### END INSERT pre_campaign #####");
        }

        $db->commit();

    }

}
?>
