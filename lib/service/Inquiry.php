<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
/**#@-*/

 /**
 * お問い合わせ情報を扱います。
 *
 * @package Service
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_Inquiry
{

    /**
     * お問い合わせ情報をDBに保存します。
     *
     * <ul><li>
     * $data['inquiry_type_cd']:お問い合わせ種類コード
     * </li><li>
     * $data['need_reply_flag']:回答希望フラグ
     * </li><li>
     * $data['company_name']:会社名
     * </li><li>
     * $data['name']:お名前
     * </li><li>
     * $data['furigana']:フリガナ
     * </li><li>
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
     * $data['inquiry_title']:お問い合わせ件名
     * </li><li>
     * $data['inquiry_content']:お問い合わせ内容
     * </li></ul>
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function insert($db, $data)
    {
        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array('inquiry_type_cd',
                         'need_reply_flag',
                         'company_name',
                         'name',
                         'furigana',
                         'tel',
                         'mail',
                         'zip',
                         'pref_id',
                         'address',
                         'inquiry_title',
                         'inquiry_content');

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }

        $query = 'INSERT INTO inquiries(';
        $query .= '            modify_user_account, created, modified, closed, state_type, claim_flag, ';
        $query .= '            inquiry_type_cd, need_reply_flag, company_name, name, ';
        $query .= '            furigana, tel, mail, zip, pref_id, address, inquiry_title, inquiry_content)';
        $query .= '    VALUES (null, now(), now(), null, 0, false, ';
        $query .= '            $1, $2, $3, $4, ';
        $query .= '            $5, $6, $7, $8, $9, $10, $11, $12);';

        $db->begin();
        Sgmov_Component_Log::debug("####### START INSERT inquiries #####");
        $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END INSERT inquiries #####");
        $db->commit();
    }
}
?>
