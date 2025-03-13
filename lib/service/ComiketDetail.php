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
 * コミケ申込宅配データマスタ情報を扱います。
 *
 * @package Service
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_ComiketDetail {

    // postgresのint型最大値
    const INT_MAX = 2147483647;
    
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
        //ミルクラン用:ミルクラン_発着選択：1：空港、2：サービスセンター、3：ホテル
        if (isset($data["mlk_hachaku_type_cd"])) {
            $params = array_merge($params, array($data["mlk_hachaku_type_cd"]));
        } else {
            $params = array_merge($params, array(""));
        }
        
        //ミルクラン用:ミルクラン_発着地識別番号
        if (isset($data["mlk_hachaku_shikibetu_cd"])) {
            $params = array_merge($params, array($data["mlk_hachaku_shikibetu_cd"]));
        } else {
            $params = array_merge($params, array(""));
        }
        
        //ミルクラン用:ミルクラン_便名
        if (isset($data["mlk_bin_nm"])) {
            $params = array_merge($params, array($data["mlk_bin_nm"]));
        } else {
            $params = array_merge($params, array(""));
        }
        $query  = '
            INSERT
            INTO
                comiket_detail
            (
                comiket_id,
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
                mlk_hachaku_type_cd,
                mlk_hachaku_shikibetu_cd,
                mlk_bin_nm
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
                $43
            );';

        $query = preg_replace('/\s+/u', ' ', trim($query));
        if($this->transactionFlg) {
            $db->begin();
        }
        Sgmov_Component_Log::debug("####### START INSERT comiket_detail #####");
        $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END INSERT comiket_detail #####");
        if($this->transactionFlg) {
            $db->commit();
        }
    }

    /**
     * コミケット申込詳細データをIDから取得します
     * @param type $db
     * @param type $id
     * @return type
     */
    public function fetchComiketDetailByComiketId($db, $id) {
        $query = 'SELECT * FROM comiket_detail WHERE comiket_id=$1';
        
        //GiapLN fix bug max interger in query postgress 
        if(empty($id) || $id > self::INT_MAX) {
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
     * コミケット申込詳細データをIDから取得します
     * @param type $db
     * @param string $id
     * @param string $type
     * @return type
     */
    public function fetchComiketDetailByComiketIdType($db, $id, $type) {
        $query = 'SELECT * FROM comiket_detail WHERE comiket_id=$1 AND type =$2';
        
        //GiapLN fix bug max interger in query postgress 
        if(empty($id) || (int)$id > self::INT_MAX) {
            return array();
        }

        $result = $db->executeQuery($query, array($id, $type));
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
     * コミケット申込詳細データのno_chg_flgを更新します
     * @param type $db
     * @param type $id
     * @param type $NoChgFlg
     * @return type
     * @throws Exception
     */
    public function updateNoChgFlg($db, $id, $NoChgFlg = '1') {
        $queryComiket = 'UPDATE comiket_detail   SET no_chg_flg=$1 WHERE comiket_id=$2';
        $queryAlpen   = 'UPDATE alpen_app_detail SET no_chg_flg=$1 WHERE app_id=$2';

        if($this->transactionFlg) {
            $db->begin();
        }

        // comiket_detailを更新
        Sgmov_Component_Log::debug("####### START UPDATE comiket_detail #####");
        $resComiket = $db->executeUpdate($queryComiket, array($NoChgFlg, $id));
        Sgmov_Component_Log::debug("####### END UPDATE comiket_detail #####");
        if(empty($resComiket)) {
            // alpen_app_detailを更新
            Sgmov_Component_Log::debug("####### START UPDATE alpen_app_detail #####");
            $resAlpen = $db->executeUpdate($queryAlpen, array($NoChgFlg, $id));
            // どっちも更新に失敗
            if(empty($resAlpen)) {
                throw new Exception();
            }
            Sgmov_Component_Log::debug("####### END UPDATE alpen_app_detail #####");
        }
        
        if($this->transactionFlg) {
            $db->commit();
        }

        // 更新できた方を返す
        if(isset($resAlpen)){
            return $resAlpen;
        }
        return $resComiket;
    }
    
    /**
     * イベントIDでコミケ詳細情報取得
     * 
     * @param type $db
     * @param type $ids
     * @return array
     */
    public function fetchComiketDetailByListComiketIds($db, $ids) {
        if(empty($ids)) {
            return array();
        }
        
        $query = 'SELECT * FROM comiket_detail WHERE comiket_id IN ('.implode(', ', $ids).')';

        $result = $db->executeQuery($query);
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
     * getComiketByToiawaseNo
     * 
     * @param type $db
     * @param type $toiawase_no
     * @return array
     */
    public function getComiketByToiawaseNo($db, $toiawase_no) {
        $query = "SELECT
                cmk.id
                ,cmk.mail
                ,cmk.staff_sei
                ,cmk.staff_mei
                ,cmk.created
                ,cmk.del_flg
                ,cmk.amount
                ,cmk.amount_tax
                ,CASE WHEN cmk.payment_method_cd = 1 THEN 'コンビニ決済'
                    WHEN cmk.payment_method_cd = 2 THEN 'クレジットカード'
                    WHEN cmk.payment_method_cd = 3 THEN '電子マネー'
                    WHEN cmk.payment_method_cd = 4 THEN 'コンビニ後払い'
                    WHEN cmk.payment_method_cd = 5 THEN '法人売掛'
                    WHEN cmk.payment_method_cd = 5 THEN '法人売掛'
                    ELSE '支払いなし'
                    END AS shiharai
                ,ckd.mlk_hachaku_type_cd
                ,ckd.mlk_hachaku_shikibetu_cd
                ,ckd.type
                ,ckd.cd
                ,ckd.name
                ,ckd.pref_id
                ,ckd.address
                ,ckd.toiawase_no_niugoki
                ,CASE WHEN ckd.type = 1 THEN '搬入（お客様⇒会場）'
                    WHEN ckd.type = 2 THEN '搬出（会場⇒お客様）'
                    END AS type
                , ckd.type AS detail_type
                ,SUM(num) AS total
                ,ckd.collect_date
                ,ckd.delivery_date
                ,ckd.delivery_st_time
                ,ckd.toiawase_no
                ,ckd.service
                ,ckd.note
                ,ckd.mlk_bin_nm
            FROM comiket_detail AS ckd
            INNER JOIN comiket AS cmk ON cmk.id = ckd.comiket_id
            INNER JOIN comiket_box AS cmbo ON cmk.id = cmbo.comiket_id
            WHERE 
            1=1
            AND ckd.toiawase_no_niugoki = $1
            GROUP BY
                cmk.id
                ,cmk.mail
                ,cmk.staff_sei
                ,cmk.staff_mei
                ,cmk.created
                ,cmk.del_flg
                ,cmk.amount
                ,cmk.amount_tax
                ,cmk.payment_method_cd
                ,ckd.mlk_hachaku_type_cd
                ,ckd.mlk_hachaku_shikibetu_cd
                ,ckd.type
                ,ckd.cd
                ,ckd.name
                ,ckd.pref_id
                ,ckd.address
                ,ckd.toiawase_no_niugoki
                ,ckd.type
                ,ckd.collect_date
                ,ckd.delivery_date
                ,ckd.delivery_st_time
                ,ckd.toiawase_no
                ,ckd.service
                ,ckd.note
                ,ckd.mlk_bin_nm
            ORDER BY cmk.id DESC";

        $result = $db->executeQuery($query, array($toiawase_no));
        $resSize = $result->size();
        if (empty($resSize)) {
            return array();
        }

        $returnList = array();
        for ($i = 0; $i < $result->size(); ++$i) {
            $returnList[] = $result->get($i);
        }
        return $returnList;
    }

    /**
     * getComiketForExport
     * 
     * @param type $db
     * @param type $request
     * @return array
     */
    public function getComiketForExport($db, $request) {
        $query = "SELECT
                cmk.id
                ,cmk.mail
                ,cmk.personal_name_sei
                ,cmk.personal_name_mei
                ,cmk.created
                ,cmk.del_flg
                ,cmk.amount
                ,cmk.amount_tax
                ,CASE WHEN cmk.payment_method_cd = 1 THEN 'コンビニ決済'
                    WHEN cmk.payment_method_cd = 2 THEN 'クレジットカード'
                    WHEN cmk.payment_method_cd = 3 THEN '電子マネー'
                    WHEN cmk.payment_method_cd = 4 THEN 'コンビニ後払い'
                    WHEN cmk.payment_method_cd = 5 THEN '法人売掛'
                    WHEN cmk.payment_method_cd = 5 THEN '法人売掛'
                    ELSE '支払いなし'
                    END AS shiharai
                ,ckd.mlk_hachaku_type_cd
                ,ckd.mlk_hachaku_shikibetu_cd
                ,ckd.type
                ,ckd.name
                ,ckd.pref_id
                ,ckd.address
                ,ckd.toiawase_no_niugoki
                ,CASE WHEN ckd.type = 1 THEN '搬入（お客様⇒会場）'
                    WHEN ckd.type = 2 THEN '搬出（会場⇒お客様）'
                    END AS type
                , ckd.type AS detail_type
                ,SUM(num) AS total
                ,ckd.collect_date
                ,ckd.collect_st_time
                ,ckd.collect_ed_time
                ,ckd.delivery_date
                ,ckd.delivery_st_time
                ,ckd.delivery_ed_time
                ,ckd.toiawase_no
                ,ckd.service
                ,ckd.note
            FROM comiket_detail AS ckd
            INNER JOIN comiket AS cmk ON cmk.id = ckd.comiket_id
            INNER JOIN comiket_box AS cmbo ON cmk.id = cmbo.comiket_id
            WHERE 
            1=1
            AND ckd.collect_date >= $1
            AND ckd.delivery_date <= $2
            GROUP BY
                cmk.id
                ,cmk.mail
                ,cmk.personal_name_sei
                ,cmk.personal_name_mei
                ,cmk.created
                ,cmk.del_flg
                ,cmk.amount
                ,cmk.amount_tax
                ,cmk.payment_method_cd
                ,ckd.mlk_hachaku_type_cd
                ,ckd.mlk_hachaku_shikibetu_cd
                ,ckd.type
                ,ckd.name
                ,ckd.pref_id
                ,ckd.address
                ,ckd.toiawase_no_niugoki
                ,ckd.type
                ,ckd.collect_date
                ,ckd.collect_st_time
                ,ckd.collect_ed_time
                ,ckd.delivery_date
                ,ckd.delivery_st_time
                ,ckd.delivery_ed_time
                ,ckd.toiawase_no
                ,ckd.service
                ,ckd.note
            ORDER BY cmk.id DESC";

        $result = $db->executeQuery($query, array($request['date_from'], $request['date_to']));
        $resSize = $result->size();
        if (empty($resSize)) {
            return array();
        }

        $returnList = array();
        for ($i = 0; $i < $result->size(); ++$i) {
            $returnList[] = $result->get($i);
        }
        return $returnList;
    }
}

