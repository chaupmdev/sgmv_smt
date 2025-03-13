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
 * 法人設置輸送情報を扱います。
 *
 * @package Service
 * @author     K.Hamada(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_CorporativeSetting
{

    /**
     * 法人設置輸送情報をDBに保存します。
     *
     * <ul><li>
     * $data['inquiry_type_cd']:お問い合わせ種類コード
     * </li><li>
     * $data['inquiry_category_cd']:お問い合わせカテゴリーコード
     * </li></ul>
     * $data['inquiry_title']:お問い合わせ件名
     * </li><li>
     * $data['inquiry_content']:お問い合わせ内容
     * </li></ul>
     * $data['company_name']:会社名
     * </li><li>
     * $data['post_name']:部署名
     * </li></ul>
     * $data['charge_name']:担当者名
     * </li><li>
     * $data['charge_furigana']:担当者フリガナ
     * </li><li>
     * $data['tel']:電話番号
     * </li><li>
     * $data['tel_type_cd']:電話種類コード
     * </li></ul>
     * $data['tel_other']:電話種類その他
     * </li></ul>
     * $data['fax']:FAX番号
     * </li></ul>
     * $data['mail']:メールアドレス
     * </li><li>
     * $data['contact_method_cd']:連絡方法コード
     * </li></ul>
     * $data['contact_available_cd']:電話連絡可能コード
     * </li></ul>
     * $data['contact_start_cd']:電話連絡可能開始時刻
     * </li></ul>
     * $data['contact_end_cd']:電話連絡可能終了時刻
     * </li></ul>
     * $data['zip']:郵便番号
     * </li><li>
     * $data['pref_id']:都道府県ID
     * </li><li>
     * $data['address']:住所
     * </li></ul>
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function insert($db, $data)
    {
        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array('inquiry_type_cd',
                         'inquiry_category_cd',
                         'inquiry_title',
                         'inquiry_content',
                         'company_name',
                         'post_name',
                         'charge_name',
                         'charge_furigana',
                         'tel',
                         'tel_type_cd',
                         'tel_other',
                         'fax',
                         'mail',
                         'contact_method_cd',
                         'contact_available_cd',
                         'contact_start_cd',
                         'contact_end_cd',
                         'zip',
                         'pref_id',
                         'address');

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }

        $query = 'INSERT INTO corp_settings(';
        $query .= '            modify_user_account, created, modified, closed, state_type, claim_flag, ';
        $query .= '            inquiry_type_cd, inquiry_category_cd, inquiry_title, inquiry_content, company_name, post_name, ';
        $query .= '            charge_name, charge_furigana, tel, tel_type_cd, tel_other, fax, mail, contact_method_cd, ';
        $query .= '            contact_available_cd, contact_start_cd, contact_end_cd, zip, pref_id, address)';
        $query .= '    VALUES (null, now(), now(), null, 0, false, ';
        $query .= '            $1, $2, $3, $4, $5, $6, ';
        $query .= '            $7, $8, $9, $10, $11, $12, $13, $14, ';
        $query .= '            $15, $16, $17, $18, $19, $20 );';

        $db->begin();
        Sgmov_Component_Log::debug("####### START INSERT corp_settings #####");
        $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END INSERT corp_settings #####");
        $db->commit();
    }
}
?>
