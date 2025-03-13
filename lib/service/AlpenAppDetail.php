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
 * アルペン申込宅配データマスタ情報を扱います。
 *
 * @package Service
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_AlpenAppDetail {

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
     * イベント手荷物受付サービスのお申し込み情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function insert($db, $data) {
        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
            "comiket_id",
            "type",
            "cd",
            "name",
            "hatsu_jis5code",
            "hatsu_shop_check_code",
            "hatsu_shop_check_code_eda",
            "hatsu_shop_code",
            "hatsu_shop_local_code",
            "chaku_jis5code",
            "chaku_shop_check_code",
            "chaku_shop_check_code_eda",
            "chaku_shop_code",
            "chaku_shop_local_code",
            "zip",
            "pref_id",
            "address",
            "building",
            "tel",
            "collect_date",
            "collect_st_time",
            "collect_ed_time",
            "delivery_date",
            "delivery_st_time",
            "delivery_ed_time",
            "service",
            "note",
            "fare",
            "fare_tax",
            "cost",
            "cost_tax",
            "delivery_timezone_cd",
            "delivery_timezone_name",
            "binshu_kbn",
            "toiawase_no",
            "toiawase_no_niugoki",

            "fare_kokyaku",
            "fare_tax_kokyaku",
            "sagyo_jikan",
            "kokyaku_futan_flg",

            "kijiran1",
            "kijiran2",
            "kijiran3",
            "kijiran4",

        );

        if (@empty($data['fare_kokyaku'])) {
            $data['fare_kokyaku'] = '0';
        }

        if (@empty($data['fare_tax_kokyaku'])) {
            $data['fare_tax_kokyaku'] = '0';
        }

        if (@empty($data['sagyo_jikan'])) {
            $data['sagyo_jikan'] = '0';
        }

        if (@empty($data['kokyaku_futan_flg'])) {
            $data['kokyaku_futan_flg'] = '0';
        }


        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data) && $key !== "toiawase_no_niugoki") {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            if ($key == 'tel') {
                $params[] = str_replace('-', '', $data[$key]);
            } elseif($key == "toiawase_no_niugoki" && !isset($data["toiawase_no_niugoki"])){
                $params[] = "";
            }else {
                $params[] = $data[$key];
            }
        }

        $query  = "
            INSERT
            INTO
                alpen_app_detail
            (
                app_id,                      
                type,                        
                cd,                          
                name,                        
                hatsu_jis5code,              
                hatsu_shop_check_code,       
                hatsu_shop_check_code_eda,   
                hatsu_shop_code,             
                hatsu_shop_local_code,       
                chaku_jis5code,              
                chaku_shop_check_code,       
                chaku_shop_check_code_eda,   
                chaku_shop_code,             
                chaku_shop_local_code,       
                zip,                         
                pref_id,                     
                address,                     
                building,                    
                tel,                         
                collect_date,                
                collect_st_time,             
                collect_ed_time,             
                delivery_date,               
                delivery_st_time,            
                delivery_ed_time,            
                service,                     
                note,                        
                fare,                        
                fare_tax,                    
                cost,                        
                cost_tax,                    
                delivery_timezone_cd,        
                delivery_timezone_name,      
                binshu_kbn,                  
                toiawase_no,                 
                toiawase_no_niugoki,         
                                             
                fare_kokyaku,                
                fare_tax_kokyaku,            
                sagyo_jikan,                 
                kokyaku_futan_flg,           

                kijiran1,                    
                kijiran2,                    
                kijiran3,                    
                kijiran4                     
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
                $35,
                $36,

                $37,
                $38,
                $39,
                $40,

                $41,
                $42,
                $43,
                $44

            );";

        $query = preg_replace('/\s+/u', ' ', trim($query));
        if($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START INSERT alpen_app_detail #####");
        $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END INSERT alpen_app_detail #####");
        if($this->transactionFlg) {
            $db->commit();
        }
    }

    /**
     * アルペンット申込詳細データをIDから取得します
     * @param type $db
     * @param type $id
     * @return type
     */
    public function fetchAlpenAppDetailByAlpenId($db, $id) {
        $query = 'SELECT * FROM alpen_app_detail WHERE app_id=$1';

        if(empty($id)) {
            return array();
        }

        $result = $db->executeQuery($query, array($id));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }
        $returnArr = array();
        for ($i = 0; $i < $resSize; $i++) {
            $row = $result->get($i);
            array_push($returnArr, $row);
        }

        return $returnArr;
    }
    
    /**
     * アルペン申込詳細データのno_chg_flgを更新します
     * @param type $db
     * @param type $id
     * @param type $NoChgFlg
     * @return type
     * @throws Exception
     */
    public function updateNoChgFlg($db, $id, $NoChgFlg = '1') {
        $query = 'UPDATE alpen_app_detail SET no_chg_flg=$1 WHERE app_id=$2';
        
        if($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START UPDATE alpen_app_detail #####");
        $res = $db->executeUpdate($query, array($NoChgFlg, $id));
        if(empty($res)) {
            throw new Exception();
        }
        Sgmov_Component_Log::debug("####### END UPDATE alpen_app_detail #####");
        if($this->transactionFlg) {
            $db->commit();
        }
        
        return $res;
    }
}

