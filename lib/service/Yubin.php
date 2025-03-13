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
class Sgmov_Service_Yubin
{
    //TODO 未テスト
    /**
     * 郵便番号をキーに、DBから存在する有効なレコード数（該当住所件数）を取得し、返します。
     *
     *
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return count 該当住所情報数
     */
    public function countZip($db, $zip)
    {
        $query = "SELECT COUNT(zipcode) FROM yubin_tbl WHERE zipcode = $1 and update_kbn <> '2';";

        $result = $db->executeQuery($query, array($zip));

        $row = $result->get(0);
        $res = $row['count'];

        return $res;
    }

    /**
     * 郵便番号をキーに、DBから該当する住所情報を取得し、キーに連番を値に都道府県名、市区町村名、町域を持つ配列を返します。
     *
     * 空白は含みません。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array ['ids'] 都道府県IDの文字列配列、['names'] 都道府県名の文字列配列
     */
    public function fetchAddressByZip($db, $zip)
    {
        $query = "SELECT prefecture,city,address FROM yubin_tbl WHERE zipcode = $1 and update_kbn <> '2'";

        $data = array();

        $result = $db->executeQuery($query, array($zip));
        for ($i = 0; $i < $result->size(); $i++) {
            $row = $result->get($i);
            mb_regex_encoding('UTF-8');
            $data[$i]['prefecture'] = mb_ereg_replace('^[ 　\r\n]*(.*?)[ 　\r\n]*$', '\1', $row['prefecture']);
            $data[$i]['city'] = mb_ereg_replace('^[ 　\r\n]*(.*?)[ 　\r\n]*$', '\1', $row['city']);
            $data[$i]['address'] = mb_ereg_replace('^[ 　\r\n]*(.*?)[ 　\r\n]*$', '\1', $row['address']);
        }

        return $data;
    }
}
?>
