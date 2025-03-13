<?php

/**
 * @package    ClassDefFile
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/* * #@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
/* * #@- */

/**
 * 単身カーゴプランのお申し込み情報を扱います。
 *
 * @package Service
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_DatCargo {

    /**
     * 単身カーゴプランのお申し込み情報を採番します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
    */
    public function select_id($db) {

    	$query    = 'SELECT max(crg_id) + 1 AS id, nextval($1) FROM dat_cargo;';
    	$params   = array();
    	$params[] = 'dat_cargo_crg_id_seq';

    	$db->begin();
    	$data = $db->executeQuery($query, $params);
    	$db->commit();
    	$row = $data->get(0);

    	if ($row['id'] > $row['nextval']) {
    		return $row['id'];
    	}

    	return $row['nextval'];
    }

    /**
     * 単身カーゴプランのお申し込み情報をDBに保存します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function insert($db, $data) {

        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array(
			'crg_id',
			'crg_name1',
			'crg_name2',
			'crg_telno',
			'crg_faxno',
			'crg_mail',
			'crg_shukamoto_yubin',
			'crg_shukamoto_ken',
			'crg_shukamoto_shi',
			'crg_shukamoto_banchi',
			'crg_haisosaki_name',
			'crg_haisosaki_yubin',
			'crg_haisosaki_ken',
			'crg_haisosaki_shi',
			'crg_haisosaki_banchi',
			'crg_haisosaki_telno',
			'crg_haisosaki_renraku',
			'crg_hanshutsu_dt',
			'crg_hansuhtsu_time',
			'crg_hannyu_dt',
			'crg_hannyu_time',
			'crg_daisu',
			//'crg_hinmoku',
			'crg_kihon_ryokin',
			'crg_hanshutsu_kei',
			'crg_hannyu_kei',
			'crg_hanbai_kakaku',
			'crg_hanbai_kakaku_zeigaku',
			'crg_merchant_result',
			'crg_datetime',
			'crg_receipted',
			'crg_send_result',
			'crg_sent',
			'crg_batch_status',
			'crg_retry_count',
			'crg_payment_method_cd',
			'crg_convenience_store_cd',
			'crg_authorization_cd',
			'crg_receipt_cd',
			'crg_payment_order_id',
			'crg_binshu',
        );

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }

        $query  = '
            INSERT
            INTO
                dat_cargo
            (
				crg_id,
				crg_name1,
				crg_name2,
				crg_telno,
				crg_faxno,
				crg_mail,
				crg_shukamoto_yubin,
				crg_shukamoto_ken,
				crg_shukamoto_shi,
				crg_shukamoto_banchi,
				crg_haisosaki_name,
				crg_haisosaki_yubin,
				crg_haisosaki_ken,
				crg_haisosaki_shi,
				crg_haisosaki_banchi,
				crg_haisosaki_telno,
				crg_haisosaki_renraku,
				crg_hanshutsu_dt,
				crg_hansuhtsu_time,
				crg_hannyu_dt,
				crg_hannyu_time,
				crg_daisu,

				crg_kihon_ryokin,
				crg_hanshutsu_kei,
				crg_hannyu_kei,
				crg_hanbai_kakaku,
				crg_hanbai_kakaku_zeigaku,
				crg_merchant_result,
				crg_datetime,
				crg_receipted,
				crg_send_result,
				crg_sent,
				crg_batch_status,
				crg_retry_count,
				crg_payment_method_cd,
				crg_convenience_store_cd,
				crg_authorization_cd,
				crg_receipt_cd,
				crg_payment_order_id,
				crg_insert_date,
				crg_update_date,
				crg_binshu
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
                current_timestamp,
                current_timestamp,
                $40
            );';

        $query = preg_replace('/\s+/u', ' ', trim($query));

        $db->begin();
        Sgmov_Component_Log::debug("####### START INSERT dat_cargo #####");
        $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END INSERT dat_cargo #####");
        $db->commit();
    }
}
