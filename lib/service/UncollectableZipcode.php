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
 * 郵便番号・住所情報を扱います。
 *
 * @package Service
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_UncollectableZipcode {

    /**
     * 郵便番号をキーに、DBから存在する有効なレコード数（該当住所件数）を取得し、返します。
     *
     *
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return count 該当住所情報数
     */
    public function countZip($db, $zip) {
        $query = 'SELECT COUNT(zipcode) FROM uncollectable_zipcode WHERE zipcode = $1;';

        $result = $db->executeQuery($query, array($zip));

        $row = $result->get(0);
        $res = $row['count'];

        return $res;
    }
}