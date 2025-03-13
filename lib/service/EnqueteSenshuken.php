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
Sgmov_Lib::useAllComponents(TRUE);
/**#@-*/

 /**
 * アンケート品質選手権データトラン情報を扱います。
 *
 * @package Service
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_EnqueteSenshuken {
    
    // トランザクションフラグ
    private $transactionFlg = TRUE;

    /**
     * トランザクションフラグ設定.
     * @param type $flg TRUE=内部でトランザクション処理する/FALSE=内部でトランザクション処理しない
     */
    public function setTrnsactionFlg($flg) {
        $this->transactionFlg = $flg;
    }
    
    /**
     *
     * @param type $db
     * @param type $id
     * @return type
     */
    public function fetchEnqueteSenshukenById($db, $id) {
        $query = 'SELECT * FROM enquete_senshuken WHERE id=$1';

        if(empty($id)) {
            return array();
        }

        $result = $db->executeQuery($query, array($id));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }

        $row = $result->get(0);

        return $row;
    }
    
    /**
     * 
     * @return type
     */
    public function getDbValInit() {
        return array(
            "id" => '',
            "zekken01" => '0',
            "zekken02" => '0',
            "zekken03" => '0',
            "zekken04" => '0',
            "zekken05" => '0',
            "zekken06" => '0',
            "zekken07" => '0',
            "zekken08" => '0',
            "zekken09" => '0',
            "zekken10" => '0',
            "zekken11" => '0',
            "gyoshu" => '0',
            "gyoshu_sonota" => '',
            "nenrei" => '0',
            "seibetsu" => '0',
            "yoi01" => '0',
            "yoi02" => '0',
            "yoi03" => '0',
            "yoi04" => '0',
            "yoi05" => '0',
            "yoi06" => '0',
            "yoi07" => '0',
            "yoi08" => '0',
            "yoi09" => '0',
            "yoi10" => '0',
            "yoi11" => '0',
            "yoi99" => '0',
            "yoi_sonota" => '',
            "yoi_textarea" => '',
            "shoyojikan" => '0',
            "riyokbn" => '0',
            "sonota_textarea" => '',
            "keihin" => '0',
        );
    }
    
    /**
     * 品質選手権のアンケート情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function insert($db, $data) {
        
        // この順番でSQLのプレースホルダーに適用されます。
        $dbValInitInfo = $this->getDbValInit();
        $keys = array_keys($dbValInitInfo);
        
        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }

            $params[] = $data[$key];
        }

        $query = 
"
INSERT
INTO
    enquete_senshuken
(
id,
zekken01,
zekken02,
zekken03,
zekken04,
zekken05,
zekken06,
zekken07,
zekken08,
zekken09,
zekken10,
zekken11,
gyoshu,
gyoshu_sonota,
nenrei,
seibetsu,
yoi01,
yoi02,
yoi03,
yoi04,
yoi05,
yoi06,
yoi07,
yoi08,
yoi09,
yoi10,
yoi11,
yoi99,
yoi_sonota,
yoi_textarea,
shoyojikan,
riyokbn,
sonota_textarea,
keihin,
created,
modified
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
$18,
$19,
$20,
$21,
$22,
$23,
$24,
$25,
$26,
$27,
$28,
$29,
$30,
$31,
$32,
$33,
$34,
current_timestamp,
current_timestamp
);
";
        $query = preg_replace('/\s+/u', ' ', trim($query));
        if($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START INSERT enquete_senshuken #####");
        $res = $db->executeUpdate($query, $params);
        if(empty($res)) {
            throw new Exception();
        }
        Sgmov_Component_Log::debug("####### END INSERT enquete_senshuken #####");
        if($this->transactionFlg) {
            $db->commit();
        }
    }
}