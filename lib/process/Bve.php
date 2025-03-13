<?php
/**
 * BVE/Send 訪問見積もり申し込み送信バッチの、データ抽出＆チェック機能です。
 * @package    maintenance
 * @subpackage BVE
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('CommonConst');
Sgmov_Lib::useAllComponents(FALSE);
Sgmov_Lib::useServices('CenterMail');
Sgmov_Lib::useprocess(array('BveSender', 'BveResponse'));
/**#@-*/

class Sgmov_Process_Bve extends Sgmov_Process_BveSender
{

    /**
     * 起動チェックファイル名
     */
    const OPRATION_FILE_NAME = 'operation.txt';

    public function execute()
    {

        // バッチ起動チェックと起動
        $check = $this->startBvecheck(Sgmov_Lib::getLogDir() . '/' . self::OPRATION_FILE_NAME);
        if ($check === false) {
            $this->errorInformation('startBve');
        }

        // 1件以上対象があればバッチ処理の実行
        $alldata = $this->selectData();
        if ($alldata->size() > 0) {
            for ($i = 0; $i < $alldata->size(); $i++) {
                $row = $alldata->get($i);
                $this->bveOutline($row);
            }

        }

        // バッチ終了処理
        $check = $this->stopBve(Sgmov_Lib::getLogDir() . '/' . self::OPRATION_FILE_NAME);
        if ($check == false) {
            $this->errorInformation('stopBve');
        }
    }

    /**
     * バッチ起動チェック
     * @param object $file
     * @return true or false
     */
    public function startBvecheck($file)
    {
        $check = file_exists($file);
        if ($check === true) {
            return false;
        } else {
            $check = touch($file);
            return true;
        }
    }

    /**
     * バッチ終了処理
     * @param object $file
     * @return true or false
     */
    public function stopBve($file)
    {
        $check = unlink($file);
        return $check;
    }

    /**
     * システム管理者へバッチの起動失敗メールを送信
     * @param object $status
     * @return
     */
    public function errorInformation($status)
    {
        // システム管理者メールアドレスを取得する。
        $mail_to = Sgmov_Component_Config::getLogMailTo();
        //テンプレートメールを送信する。
        Sgmov_Component_Mail::sendTemplateMail($status, dirname(__FILE__) . '/../../lib/mail_template/bve_error.txt', $mail_to);
        exit;
    }

    /**
     * 対象レコードを取得
     * @return
     */
    public function selectData()
    {

        $db = Sgmov_Component_DB::getAdmin();
        $sql = "";
        $sql .= "
    SELECT
        visit_estimates.id,
        visit_estimates.modify_user_account,
        visit_estimates.created,
        visit_estimates.modified,
        visit_estimates.send_result,
        visit_estimates.batch_status,
        visit_estimates.retry_count,
        visit_estimates.uke_no,
        visit_estimates.pre_exist_flag,
        visit_estimates.company_flag,
        cources_plans.if_cource_code,
        cources_plans.if_plan_cd,
        cources.name                                      AS COURCES_NAME,
        plans.name                                        AS PLANS_NAME,
        visit_estimates.pre_aircon_exist_flag,
        visit_estimates.from_area_id,
        visit_estimates.to_area_id,
        TO_CHAR(visit_estimates.move_date,'YYYY/MM/DD')   AS MOVE_DATE,
        visit_estimates.pre_base_price,
        visit_estimates.pre_estimate_price,
        visit_estimates.visit_date1,
        visit_estimates.visit_date2,
        TO_CHAR(visit_estimates.visit_date1,'YYYY/MM/DD') AS VISIT_DATE1,
        TO_CHAR(visit_estimates.visit_date2,'YYYY/MM/DD') AS VISIT_DATE2,
        visit_estimates.cur_zip,
        PREF1.if_prefecture_code                          AS CUR_PREF_IFID,
        visit_estimates.cur_address,
        visit_estimates.cur_elevator_cd,
        visit_estimates.cur_floor,
        visit_estimates.cur_road_cd,
        visit_estimates.new_zip,
        PREF2.if_prefecture_code                          AS NEW_PREF_IFID,
        visit_estimates.new_address,
        visit_estimates.new_elevator_cd,
        visit_estimates.new_floor,
        visit_estimates.new_road_cd,
        visit_estimates.name,
        visit_estimates.furigana,
        visit_estimates.tel,
        visit_estimates.tel_type_cd,
        visit_estimates.tel_other,
        visit_estimates.contact_available_cd,
        visit_estimates.contact_start_cd                  AS contact_start_cd,
        visit_estimates.contact_end_cd                    AS contact_end_cd,
        visit_estimates.mail,
        visit_estimates.note,
        visit_estimates.company_name,
        visit_estimates.company_furigana,
        visit_estimates.charge_name,
        visit_estimates.charge_furigana,
        visit_estimates.contact_method_cd,
        visit_estimates.num_people,
        visit_estimates.tsubo_su,
        visit_estimates.other_operation_id,
        apartment.agency_cd                               AS APARTMENT_AGENCY_CD,
        visit_estimates.work_summary_cd,
        CENTER1.id                                        AS FROM_CENTER_ID,
        CENTER1.if_center_code                            AS FROM_CENTER_IFID,
        CENTER2.if_center_code                            AS TO_CENTER_IFID,
        from_areas.name                                   AS FROM_AREA_NAME,
        to_areas.name                                     AS TO_AREA_NAME,
        PREF1.name                                        AS CUR_PREF_NAME,
        PREF2.name                                        AS NEW_PREF_NAME
    FROM
        visit_estimates
        LEFT OUTER JOIN
        prefectures AS PREF1
        ON
            visit_estimates.cur_pref_id = PREF1.prefecture_id
        LEFT OUTER JOIN
        prefectures AS PREF2
        ON
            visit_estimates.new_pref_id = PREF2.prefecture_id
        LEFT OUTER JOIN
        centers_from_areas
        ON
            visit_estimates.from_area_id = centers_from_areas.id
        LEFT OUTER JOIN
        centers     AS CENTER1
        ON
            centers_from_areas.center_id = CENTER1.id
        LEFT OUTER JOIN
        centers_to_areas
        ON
            visit_estimates.to_area_id = centers_to_areas.id
        LEFT OUTER JOIN
        centers     AS CENTER2
        ON
            centers_to_areas.center_id = CENTER2.id
        LEFT OUTER JOIN
        to_areas
        ON
            visit_estimates.to_area_id = to_areas.id
        LEFT OUTER JOIN
        from_areas
        ON
            visit_estimates.from_area_id = from_areas.id
        LEFT OUTER JOIN
        cources
        ON
            visit_estimates.course_id = cources.id
        LEFT OUTER JOIN
        plans
        ON
            visit_estimates.plan_id = plans.id
        LEFT OUTER JOIN
        cources_plans
        ON
            visit_estimates.course_id = cources_plans.cource_id
        AND visit_estimates.plan_id   = cources_plans.plan_id
        LEFT OUTER JOIN
        apartment
        ON
            visit_estimates.apartment_id = apartment.id
    WHERE
        batch_status IN (0,1,2,3);";

    $selectData = $db->executeQuery($sql);

    return $selectData;
    }

    /**
     * 対象キャンペーンを取得
     * @return
     */
    public function selectCampaign($id)
    {
        $db = Sgmov_Component_DB::getAdmin();
        $selectCampaign = $db->executeQuery("SELECT * FROM pre_campaign WHERE visit_estimate_id= $1", array($id));

        if ($selectCampaign->size() == 0) {
            return null;
        } else {
            return $selectCampaign;
        }
    }

    /**
     * 他社連携キャンペーンを取得
     * @return
     */
    public function selectOtherCampaign($id)
    {
       $db = Sgmov_Component_DB::getAdmin();
        $selectOtherCampaign = $db->executeQuery("SELECT * FROM aoc_campaign WHERE id=".$id);

        if ($selectOtherCampaign->size() == 0) {
            return null;
        } else {
            return $selectOtherCampaign;
        }
    }

    /**
     * バッチメイン処理
     * @param object $selectData
     * @return
     */
    public function bveOutline($selectData)
    {
        //概算見積もり情報取得
        $campaignData = "";
        if ($selectData['pre_exist_flag'] === 't') {
            $campaignData = $this->selectCampaign($selectData['id']);
        }

       //他社連携キャンペーン情報取得
        //$othercampaignData = "";
        if (isset($selectData['other_operation_id'])) {
            $othercampaignData = $this->selectOtherCampaign($selectData['other_operation_id']);
        }

        if ($selectData["batch_status"] == 0) {
            //IFデータ送信

            $this->sendData($selectData,$campaignData,$othercampaignData);
        }
        if ($selectData["batch_status"] == 1) {
            //管理者へメール送信（送信エラー時のみ）
            $this->SendMailManager($selectData);
        }

        //メール用に値をセット
        $this->setData($selectData,$campaignData,$othercampaignData);

        if ($selectData["batch_status"] == 2) {
            //担当者へメール送信
            $res = $this->SendMailTanto($selectData);
        }
        if ($selectData["batch_status"] == 3) {
            //顧客へ完了メール送信
            $res = $this->SendMailCustomer($selectData);
        }

    }

    /**
     * IFデータ送信
     * @param object $selectData
     * @return
     */
    public function sendData(&$selectData,$campaignData,$othercampaignData)
    {
        $res = 0;
        //データ生成
        $csvdata = $this->makeIFcsv($selectData,$campaignData,$othercampaignData);

        //データ送信
        try {
            $res = Sgmov_Process_BveSender::sendCsvToWs('MITUMORI_' . date('YmdHis') . '.csv', $csvdata);
        } catch(Sgmov_Component_Exception $sce) {
            $sce->setInformation($selectData);
            throw $sce;
        }

        $responce = new Sgmov_Process_BveResponse;
        $responce->initialize($res);

        // レスポンス値によって処理のふりわけ
        switch ($responce->sendSts) {
        // 成功：update バッチ処理状況「送信済」 送信結果「成功」
        case 0:
            $db = Sgmov_Component_DB::getAdmin();
            $db->begin();
            $db->executeUpdate("UPDATE visit_estimates SET uke_no=$1,batch_status='1',send_result='3',modified = now() WHERE id=$2;", array($responce->ukeNo,$selectData['id']));
            $db->commit();
            $selectData["batch_status"] = 1;
            $selectData["send_result"] = 3;
            break;

        //不正データ：update バッチ処理状況「送信済」 送信結果「失敗」
        case 1:
            $db = Sgmov_Component_DB::getAdmin();
            $db->begin();
            $db->executeUpdate("UPDATE visit_estimates SET batch_status='1',send_result='1',modified = now() WHERE id=$1;", array($selectData['id']));
            $db->commit();
            $selectData["batch_status"] = 1;
            $selectData["send_result"] = 1;
            break;

        //システム障害：update 送信リトライ数「+1」
        case 2:
        //送信競合：update 送信リトライ数「+1」
        case 3:
            $db = Sgmov_Component_DB::getAdmin();
            $db->begin();
            $db->executeUpdate("UPDATE visit_estimates SET retry_count=retry_count+1,modified = now() WHERE id=$1;", array($selectData['id']));
            $db->commit();
            $selectData["retry_count"]++;

            // 送信リトライ階数が21以上の場合バッチ処理状況「送信済」 送信結果「リトライオーバー」
            if ($selectData["retry_count"] >= 21) {
                $db = Sgmov_Component_DB::getAdmin();
                $db->begin();
                $db->executeUpdate("UPDATE visit_estimates SET batch_status='1',send_result='2',modified = now() WHERE id=$1;", array($selectData['id']));
                $db->commit();
                $selectData["batch_status"] = 1;
                $selectData["send_result"] = 2;
            }
            break;

        // 登録済み：update バッチ処理状況「送信済」 送信結果「成功」
        case 4:
            $db = Sgmov_Component_DB::getAdmin();
            $db->begin();
            $db->executeUpdate("UPDATE visit_estimates SET uke_no=$1,batch_status='1',send_result='3',modified = now() WHERE id=$2;", array($responce->ukeNo,$selectData['id']));
            $db->commit();
            $selectData["batch_status"] = 1;
            $selectData["send_result"] = 3;
            break;

        //それ以外 送信リトライ数「+1」 送信リトライ階数が21以上の場合バッチ処理状況「送信済」 送信結果「リトライオーバー」（タイムアウト）
        default:
            $db = Sgmov_Component_DB::getAdmin();
            $db->begin();
            $db->executeUpdate("UPDATE visit_estimates SET retry_count=retry_count+1,modified = now() WHERE id=$1;", array($selectData['id']));
            $db->commit();
            $selectData["retry_count"]++;
            if ($selectData["retry_count"] >= 21) {
                $db = Sgmov_Component_DB::getAdmin();
                $db->begin();
                $db->executeUpdate("UPDATE visit_estimates SET batch_status='1',send_result='2',modified = now() WHERE id=$1;", array($selectData['id']));
                $db->commit();
                $selectData["batch_status"] = 1;
                $selectData["send_result"] = 2;
            }
            break;
        }
    }

    /**
     * メール送信用にCDやFlagから値を作成
     * @param object $selectData
     * @return
     */
    public function setData(&$selectData, $campaignData, $OthercampaignData)
    {
        //エアコン
        switch ($selectData['pre_aircon_exist_flag']) {
        case 't':
            $selectData['pre_aircon_exist'] = 'あり';
            break;
        case 'f':
            $selectData['pre_aircon_exist'] = 'なし';
            break;
        default:
            $selectData['pre_aircon_exist'] = '';
            break;
        }

        //現住所エレベーター
        switch ($selectData['cur_elevator_cd']) {
        case '1':
            $selectData['cur_elevator'] = 'あり';
            break;
        case '0':
            $selectData['cur_elevator'] = 'なし';
            break;
        default:
            $selectData['cur_elevator'] = '';
            break;
        }

        //現住所住居前道幅
        switch ($selectData['cur_road_cd']) {
        case 1:
            $selectData['cur_road'] = '車両通行不可';
            break;
        case 2:
            $selectData['cur_road'] = '1台通行可';
            break;
        case 3:
            $selectData['cur_road'] = '2台すれ違い可';
            break;
        default:
            $selectData['cur_road'] = '';
            break;
        }

        //新住所エレベーター
        switch ($selectData['new_elevator_cd']) {
        case '1':
            $selectData['new_elevator'] = 'あり';
            break;
        case '0':
            $selectData['new_elevator'] = 'なし';
            break;
        default:
            $selectData['new_elevator'] = '';
            break;
        }

        //新住所住居前道幅
        switch ($selectData['new_road_cd']) {
        case 1:
            $selectData['new_road'] = '車両通行不可';
            break;
        case 2:
            $selectData['new_road'] = '1台通行可';
            break;
        case 3:
            $selectData['new_road'] = '2台すれ違い可';
            break;
        default:
            $selectData['new_road'] = '';
            break;
        }

        //電話種類コード
        switch ($selectData['tel_type_cd']) {
        case 1:
            $selectData['tel_type'] = '自宅（携帯）';
            break;
        case 2:
            $selectData['tel_type'] = '勤務先';
            break;
        case 3:
            $selectData['tel_type'] = 'その他';
            break;
        default:
            $selectData['tel_type'] = '';
            break;
        }

        //電話連絡可能コード
        switch ($selectData['contact_available_cd']) {
        case 1:
            $selectData['contact_available'] = '時間指定';
            break;
        case 2:
            $selectData['contact_available'] = '終日OK';
            break;
        default:
            $selectData['contact_available'] = '';
            break;
        }

        //電話連絡可能開始時間
        if (strlen($selectData['contact_start_cd']) == 4) {
            $selectData['contact_start'] = substr($selectData['contact_start_cd'],0,2).":".$selectData['contact_start_cd'] = substr($selectData['contact_start_cd'],2,2);
        }else{
            $selectData['contact_start'] = '';
        }

        //電話連絡可能終了時間
        if (strlen($selectData['contact_end_cd']) == 4) {
            $selectData['contact_end'] = substr($selectData['contact_end_cd'],0,2).":".$selectData['contact_end_cd'] = substr($selectData['contact_end_cd'],2,2);
        }else{
            $selectData['contact_end'] = '';
        }

        switch ($selectData['cur_road_cd']) {
        case 1:
            $selectData['cur_road'] = '車両通行不可';
            break;
        case 2:
            $selectData['cur_road'] = '1台通行可';
            break;
        case 3:
            $selectData['cur_road'] = '2台すれ違い可';
            break;
        default:
            $selectData['cur_road'] = '';
            break;
        }

        //連絡方法コード
        switch ($selectData['contact_method_cd']) {
        case 1:
            $selectData['contact_method'] = '電話';
            break;
        case 2:
            $selectData['contact_method'] = 'メール';
            break;
        default:
            $selectData['contact_method'] = '';
            break;
        }

        //キャンペーン
        $selectData['campaign'] = '';
        if ($selectData['pre_exist_flag'] === 't' and !is_null($campaignData)) {
            for ($i = 0; $i < $campaignData->size(); $i++) {
                $row = $campaignData->get($i);
                if ($row['campaign_division'] == Sgmov_View_CommonConst::TOKKA_CAMPAIGNSETTEI) {
                    $selectData['campaign'] .= "\r\n";
                    $selectData['campaign'] .= $row['campaign_name'];
                    if($row['campaign_price']!=0){
                        if($row['campaign_price']>0){$selectData['campaign'] .=  ':' . $row['campaign_price']."円割増し";}
                        if($row['campaign_price']<0){$selectData['campaign'] .=  ':' . $row['campaign_price']."円引き";}
                    }
                }
            }
        }
        //他社連携キャンペーン
        $selectData['oc_campaign_name'] = '';
        if (!is_null($OthercampaignData)) {
          for ($i = 0; $i < $OthercampaignData->size(); $i++) {
                $row = $OthercampaignData->get($i);
                  if ($row['id'] == $selectData['other_operation_id']) {
                    $selectData['oc_campaign_name'] .= "\r\n";
                    $selectData['oc_campaign_name'] .= $row['campaign_name'];

                }
            }
        }

    }

    /**
     * 管理者へメール送信（送信エラー時のみ）
     * @param object $selectData
     * @return
     */
    public function SendMailManager(&$selectData)
    {
        if ($selectData["send_result"] == 1 || $selectData["send_result"] == 2) {
            // システム管理者メールアドレスを取得する。
            $mail_to = Sgmov_Component_Config::getLogMailTo();
            //メールを送信する。
            Sgmov_Component_Mail::sendTemplateMail($selectData, dirname(__FILE__) . '/../../lib/mail_template/bve_error_send.txt', $mail_to);
        }
        $db = Sgmov_Component_DB::getAdmin();
        $db->begin();
        $db->executeUpdate("UPDATE visit_estimates SET batch_status='2',modified = now() WHERE id=$1;", array($selectData['id']));
        $db->commit();
        $selectData["batch_status"] = 2;
    }
    /**
     * 担当者へメール送信
     * @param object $selectData
     * @return
     */
    public function SendMailTanto(&$selectData)
    {
        $db = Sgmov_Component_DB::getAdmin();
        $_centerMailService = new Sgmov_Service_CenterMail();
        if ($selectData["send_result"] == 3) {
            if ($selectData['company_flag'] == 't') {
                //オフィス移転
                 $_centerMailService->_sendAdminMailByFromAreaId($db, Sgmov_Service_CenterMail::FORM_KBN_PCV, $selectData['from_area_id'], $selectData, '/bve_admin_pcv.txt');
            } elseif ($selectData["pre_exist_flag"] === 't') {
                //概算経由者向け
                //print_r($selectData);
                //echo "test";
                //exit;
                 $_centerMailService->_sendAdminMailByFromAreaId($db, Sgmov_Service_CenterMail::FORM_KBN_PVE, $selectData['from_area_id'], $selectData, '/bve_admin_pre.txt');
            } else {
                //訪問見積もり者向け
                 $_centerMailService->_sendAdminMailByFromAreaId($db, Sgmov_Service_CenterMail::FORM_KBN_PRE, $selectData['from_area_id'], $selectData, '/bve_admin_pve.txt');
            }
        }

        //バッチステータスを更新
        $db = Sgmov_Component_DB::getAdmin();
        $db->begin();
        $db->executeUpdate("UPDATE visit_estimates SET batch_status='3',modified = now() WHERE id=$1;", array($selectData['id']));
        $db->commit();
        $selectData["batch_status"] = 3;
    }
    /**
     * 申込者へメール送信
     * @param object $selectData
     * @return
     */
    public function SendMailCustomer(&$selectData)
    {
        if ($selectData["send_result"] == 3) {
            // テンプレートメールを送信する（オフィス移転or個人概算or個人訪問）
            if ($selectData['company_flag'] == 't') {
                //オフィス移転
                Sgmov_Component_Mail::sendTemplateMail($selectData, dirname(__FILE__) . '/../../lib/mail_template/bve_user_pcv.txt',
                     $selectData["mail"]);
            } elseif ($selectData["pre_exist_flag"] == 't') {
                //概算経由者向け
                Sgmov_Component_Mail::sendTemplateMail($selectData, dirname(__FILE__) . '/../../lib/mail_template/bve_user_pre.txt',
                     $selectData["mail"]);
            } else {
                //訪問見積もり者向け
                Sgmov_Component_Mail::sendTemplateMail($selectData, dirname(__FILE__) . '/../../lib/mail_template/bve_user_pve.txt',
                     $selectData["mail"]);
            }
        }

        //バッチステータスを更新
        $db = Sgmov_Component_DB::getAdmin();
        $db->begin();
        $db->executeUpdate("UPDATE visit_estimates SET batch_status='4',modified = now() WHERE id=$1;", array($selectData['id']));
        $db->commit();
        $selectData["batch_status"] = 4;
    }

    /**
     * DB値から送信用csvファイル作成
     * @param object $data
     * @return
     */
    public function makeIFcsv($selectData,$campaignData,$othercampaignData)
    {
        $csv = "";
        $csv .= "\"HEADER\"";
        $csv .= "\r\n";
        $csv .= $this->setSHCF70($selectData, $campaignData, $othercampaignData);
        $csv .= $this->setSHCFEST101H($selectData, $campaignData);
        $csv .= $this->setSHCFEST101M($selectData, $campaignData);
        $csv .= $this->setSHCFEST102($selectData, $campaignData);
        $csv .= "\"TRAILER\"";

        return $csv;
    }

    /**
     * SHCF70セット
     * @param object $selectData
     * @param object $campaignData
     * @return
     */
    public function setSHCF70($selectData, $campaignData, $othercampaignData)
    {
        $sample = array();
        $sample[] = sprintf("%010d",$selectData['id']);//HP受付番号
        if ($selectData['pre_exist_flag'] === 't') {//内容CD
            $sample[] = '01';
        } else {
            $sample[] = '02';
        }
        $sample[] = '';//内容CD　その他
        if (is_null($selectData['if_cource_code'])){//コースCD
            $sample[] = '';
        }else{
            $sample[] = sprintf("%02d",$selectData['if_cource_code']);
        }
        if (is_null($selectData['if_plan_cd'])){//プランCD
            $sample[] = '';
        }else{
            $sample[] = sprintf("%02d",$selectData['if_plan_cd']);
        }
        $sample[] = $selectData['name'];//顧客氏名
        $sample[] = $selectData['furigana'];//顧客カナ
        $sample[] = $selectData['tel_type_cd'];//顧客連絡先区分
        $sample[] = $selectData['tel_other'];//顧客連絡先　その他
        $sample[] = $selectData['contact_available_cd'];//顧客連絡可否区分
        $sample[] = $selectData['contact_start_cd'];//顧客連絡時間
        $sample[] = $selectData['contact_end_cd'];//顧客連絡時間
        $sample[] = $selectData['tel'];//顧客電話
        $sample[] = $selectData['mail'];//顧客メールアドレス
        $sample[] = $selectData['move_date'];//搬出予定日2
        $sample[] = '';//搬出先電話番号
        $sample[] = '';//搬出予定時間
        $sample[] = $selectData['cur_zip'];//搬出郵便番号
        $sample[] = $selectData['cur_pref_ifid'];//搬出都道府県CD
        $sample[] = $selectData['cur_address'];//搬出住所
        $sample[] = '';//搬出ビル名
        $sample[] = '';//搬出内線
        $sample[] = '';//搬出住居CD
        $sample[] = '';//搬出間取CD
        $sample[] = $selectData['cur_floor'];//搬出階数
        $sample[] = $selectData['cur_elevator_cd'];//搬出EV区分
        $sample[] = $selectData['num_people'];//搬出大人人数
        $sample[] = '';//搬出子供人数
        $sample[] = $selectData['cur_road_cd'];//搬出道幅区分
        $sample[] = '';//搬入予定日2
        $sample[] = '';//搬入先電話番号
        $sample[] = '';//搬入予定時間
        $sample[] = $selectData['new_zip'];//搬入郵便番号
        $sample[] = $selectData['new_pref_ifid'];//搬入都道府県CD
        $sample[] = $selectData['new_address'];//搬入住所
        $sample[] = '';//搬入ビル名
        $sample[] = '';//搬入内線
        $sample[] = '';//搬入住居CD
        $sample[] = '';//搬入間取CD
        $sample[] = $selectData['new_floor'];//搬入階数
        $sample[] = $selectData['new_elevator_cd'];//搬入EV区分
        $sample[] = '';//搬入大人人数
        $sample[] = '';//搬入子供人数
        $sample[] = $selectData['new_road_cd'];//搬入道幅区分
        $sample[] = $selectData['visit_date1'];//訪問希望日1
        $sample[] = '';//訪問希望開始時間1
        $sample[] = '';//訪問希望終了時間1
        $sample[] = '';//訪問希望時間区分1
        $sample[] = $selectData['visit_date2'];//訪問希望日2
        $sample[] = '';//訪問希望開始時間2
        $sample[] = '';//訪問希望終了時間2
        $sample[] = '';//訪問希望時間区分2
        $sample[] = '0';//見積区分
        $sample[] = $selectData['note'];//備考
        $sample[] = $selectData['from_center_ifid'];//見積拠点CD
        $sample[] = $selectData['from_center_ifid'];//搬出拠点CD
        $sample[] = $selectData['to_center_ifid'];//搬入拠点CD
        $sample[] = $selectData['company_name'];//見積お客様名
        $sample[] = $selectData['cur_address'];//見積住所
        $sample[] = $selectData['tel'];//見積電話番号
        $sample[] = '';//見積fax番号
        $sample[] = $selectData['charge_name'];//見積担当者
        $sample[] = '';//見積担当部署
        $sample[] = '';//搬出住所コード
        $sample[] = '';//搬入住所コード
        $sample[] = $selectData['company_name'];//顧客会社名
        $sample[] = '';//顧客部署
        $sample[] = $selectData['charge_name'];//顧客依頼者
        $sample[] = '';//顧客内線
        $sample[] = '';//顧客携帯番号
        $sample[] = '';//請求先内線
        $sample[] = '';//請求先携帯番号
        //連絡欄
        $item = "";
        if ($selectData['company_flag'] == 't') {
            //法人の場合
            switch ($selectData['contact_method_cd']) {
                case 1:
                    $item .= '<連絡方法>';
                    $item .= '\r\n';
                    $item .= '電話';
                    $item .= '\r\n';
                    break;
                case 2:
                    $item .= '<連絡方法>';
                    $item .= '\r\n';
                    $item .= 'メール';
                    $item .= '\r\n';
                    break;
                default:
                    break;
            }
            if ($selectData['tsubo_su'] != null) {
                $item .= '<フロア坪数>';
                $item .= '\r\n';
                $item .= $selectData['tsubo_su'];
            }
        } else {
            //個人の場合
            if ($selectData['pre_exist_flag'] === 't') {
                $item .= "＜HP概算見積もり＞";
                $item .= '\r\n';
                $item .= "お引越しコース：" . $selectData['cources_name'];
                $item .= '\r\n';
                $item .= "お引越しプラン：" . $selectData['plans_name'];
                $item .= '\r\n';
                if ($selectData['pre_aircon_exist_flag'] == 't') {
                    $item .= "エアコン：あり";
                    $item .= '\r\n';
                } else {
                    $item .= "エアコン：なし";
                    $item .= '\r\n';
                }
                $item .= "現在お住まいの地域：" . $selectData['from_area_name'];
                $item .= '\r\n';
                $item .= "お引越し先の地域：" . $selectData['to_area_name'];
                $item .= '\r\n';
                $item .= "お引越し予定日：" . $selectData['move_date'];
                $item .= '\r\n';
                $item .= "概算金額：" . $selectData['pre_estimate_price']."円";
                $item .= '\r\n';
                $item .= '\r\n';
                $item .= "＜適用キャンペーン＞";
                if(!is_null($campaignData)){
                    for ($i = 0; $i < $campaignData->size(); $i++) {
                        $row = $campaignData->get($i);
                        $item .= '\r\n';
                        $item .= $row['campaign_name'] . ":" . $row['campaign_price']."円";
                        }
                }

                if(!is_null($othercampaignData)){
                    for ($i = 0; $i < $othercampaignData->size(); $i++) {
                        $row = $othercampaignData->get($i);
                        $item .= '\r\n';
                        $item .= $row['campaign_name'];
                    }
                }

                if(is_null($othercampaignData) && is_null($campaignData)){
                    $item .= '\r\n';
                    $item .= "なし";
                }

                //echo "ttttt";
                //echo $item;

            }
        }
        $sample[] = $item;
        $sample[] = $selectData['apartment_agency_cd'];//取次店コード
        // ダブルクォーテーションで囲んでつなげる
        $ret = '"SHCF70"';
        foreach ($sample as $aaa) {
            $ret .= ',' . $this->escapeIFcsv($aaa);
        }
        $ret .= "\r\n";
        return $ret;
    }

    /**
     * SHCFEST101Hセット
     * @param object $selectData
     * @return
     */
    public function setSHCFEST101H($selectData, $campaignData)
    {
        $sample = array();
        $sample[] = sprintf("%010d",$selectData['id']);//HP受付番号
        if ($selectData['company_flag'] === 't') {
            // 法人の場合
            $sample[] = '1';//見積区分
            $kind = '103';//便種CD
        } else {
            // 個人の場合
            $sample[] = '2';//見積区分
            $kind = '101';//便種CD
        }
        if (!empty($selectData['apartment_agency_cd'])) {
            // マンション一斉引っ越しの場合
            $kind = '105';//便種CD
        }
        $sample[] = $kind;//便種CD
        $sample[] = $selectData['work_summary_cd'];//作業概要CD
        $sample[] = '';//受付社員CD
        $sample[] = '';//受付区分コード
        $sample[] = '';//引越日
        $sample[] = '';//引越作業開始時間
        $sample[] = '';//引越作業終了時間
        $sample[] = '';//搬入日
        $sample[] = '';//搬入作業開始時間
        $sample[] = '';//搬入作業終了時間
        $sample[] = '';//前梱日1
        $sample[] = '';//前梱日1作業開始時間
        $sample[] = '';//前梱日1作業終了時間
        $sample[] = '';//前梱日2
        $sample[] = '';//前梱日2作業開始時間
        $sample[] = '';//前梱日2作業終了時間
        $sample[] = '';//資材配達日
        $sample[] = '';//資材配達作業開始時間
        $sample[] = '';//資材配達作業終了時間
        $sample[] = '';//資材回収日
        $sample[] = '';//資材回収開始時間
        $sample[] = '';//資材回収終了時間
        $sample[] = '';//保管開始日
        $sample[] = '';//保管終了日
        $sample[] = '';//顧客番号
        $sample[] = '';//顧客番号枝番
        $sample[] = '';//請求書発行区分
        $sample[] = '';//入金方法
        $sample[] = '';//請求先会社名
        $sample[] = '';//請求先住所
        $sample[] = '';//請求先電話番号
        $sample[] = '';//請求先FAX番号
        $sample[] = '';//請求先部署
        $sample[] = '';//請求先担当者
        $sample[] = '';//問合せ番号
        $sample[] = '';//信販会社承認番号

        // ダブルクォーテーションで囲んでつなげる
        $ret = '"SHCF_EST_101_H"';
        foreach ($sample as $aaa) {
            $ret .= ',' . $this->escapeIFcsv($aaa);
        }
        $ret .= "\r\n";
        return $ret;
    }
    /**
     * SHCFEST101Mセット
     * @param object $selectData
     * @param object $campaignData
     * @return
     */
    public function setSHCFEST101M($selectData, $campaignData)
    {
        //発登録
        $sample = array();
        $sample[] = sprintf("%010d",$selectData['id']);//HP受付番号
        $sample[] = '1';//拠点区分
        $sample[] = $selectData['from_center_ifid'];//拠点CD
        if ($selectData['pre_exist_flag'] === 't') {
            $sample[] = '4';//概算あり
        } else {
            $sample[] = '2';//概算なし
        }
        $sample[] = '';//見積ステータス変更フラグ
        $sample[] = '';//代理店有無フラグ
        $sample[] = '';//印刷時担当者仕様有フラグ
        $sample[] = '';//諸経費（税抜）
        $sample[] = '';//売上金額（税抜）
        $sample[] = '';//項目値引合計
        $sale = 0;
        if ($selectData['pre_exist_flag']==='t' and !is_null($campaignData)) {
            //値引き額計算（割増は+、値引は-)
            for ($i = 0; $i < $campaignData->size(); $i++) {
                $row = $campaignData->get($i);
                $sale += $row['campaign_price'];
            }
        }
        $sample[] = $sale * -1;//出精値引（プラスとマイナスを逆転）
        $sample[] = '';//値引合計
        $sample[] = '';//内消費税
        $sample[] = '';//諸経費(税込)
        $sample[] = '';//売上金額(税込)
        $sample[] = '';//引越取分金額
        $sample[] = '';//課税対象額
        $sample[] = '';//非課税額
        $sample[] = '';//SCS実費更新後合計金額
        $sample[] = '';//パック料金区分
        $sample[] = '';//SCS売上金額
        $sample[] = '';//基本金額(金額)
        $sample[] = '';//基本金額(値引後)
        $sample[] = '';//基本金額(原価)
        $sample[] = '';//オプション(金額)
        $sample[] = '';//オプション(値引後)
        $sample[] = '';//オプション(原価)
        $sample[] = '';//実費(金額)
        $sample[] = '';//実費(値引後)
        $sample[] = '';//実費(原価)
        $sample[] = '';//販売資材(金額)
        $sample[] = '';//販売資材(値引後)
        $sample[] = '';//販売資材(原価)
        $sample[] = '';//梱包資材(金額)
        $sample[] = '';//梱包資材(値引後)
        $sample[] = '';//梱包資材(原価)
        $sample[] = '';//小計1(金額)
        $sample[] = '';//小計1(値引後)
        $sample[] = '';//小計1(原価)
        $sample[] = '';//養生(金額)
        $sample[] = '';//養生(値引後)
        $sample[] = '';//養生(原価)
        $sample[] = '';//保険料(金額)
        $sample[] = '';//保険料(値引後)
        $sample[] = '';//保険料(原価)
        $sample[] = '';//小計2(金額)
        $sample[] = '';//小計2(値引後)
        $sample[] = '';//小計2(原価)
        $sample[] = '';//金額合計
        $sample[] = '';//原価合計

        // ダブルクォーテーションで囲んでつなげる
        $ret = '"SHCF_EST_101_M"';
        foreach ($sample as $aaa) {
            $ret .= ',' . $this->escapeIFcsv($aaa);
        }
        $ret .= "\r\n";

        //着登録
        if ($selectData['from_center_ifid'] != $selectData['to_center_ifid']) {
            $sample = array();
            $sample[] = sprintf("%010d",$selectData['id']);//HP受付番号
            $sample[] = '3';//拠点区分
            $sample[] = $selectData['to_center_ifid'];//拠点CD
            if ($selectData['pre_exist_flag'] === 't') {
                $sample[] = '4';//概算あり
            } else {
                $sample[] = '2';//概算なし
            }
            $sample[] = '';//見積ステータス変更フラグ
            $sample[] = '';//代理店有無フラグ
            $sample[] = '';//印刷時担当者仕様有フラグ
            $sample[] = '';//諸経費（税抜）
            $sample[] = '';//売上金額（税抜）
            $sample[] = '';//項目値引合計
            $sample[] = '0';//出精値引
            $sample[] = '';//値引合計
            $sample[] = '';//内消費税
            $sample[] = '';//諸経費(税込)
            $sample[] = '';//売上金額(税込)
            $sample[] = '';//引越取分金額
            $sample[] = '';//課税対象額
            $sample[] = '';//非課税額
            $sample[] = '';//SCS実費更新後合計金額
            $sample[] = '';//パック料金区分
            $sample[] = '';//SCS売上金額
            $sample[] = '';//基本金額(金額)
            $sample[] = '';//基本金額(値引後)
            $sample[] = '';//基本金額(原価)
            $sample[] = '';//オプション(金額)
            $sample[] = '';//オプション(値引後)
            $sample[] = '';//オプション(原価)
            $sample[] = '';//実費(金額)
            $sample[] = '';//実費(値引後)
            $sample[] = '';//実費(原価)
            $sample[] = '';//販売資材(金額)
            $sample[] = '';//販売資材(値引後)
            $sample[] = '';//販売資材(原価)
            $sample[] = '';//梱包資材(金額)
            $sample[] = '';//梱包資材(値引後)
            $sample[] = '';//梱包資材(原価)
            $sample[] = '';//小計1(金額)
            $sample[] = '';//小計1(値引後)
            $sample[] = '';//小計1(原価)
            $sample[] = '';//養生(金額)
            $sample[] = '';//養生(値引後)
            $sample[] = '';//養生(原価)
            $sample[] = '';//保険料(金額)
            $sample[] = '';//保険料(値引後)
            $sample[] = '';//保険料(原価)
            $sample[] = '';//小計2(金額)
            $sample[] = '';//小計2(値引後)
            $sample[] = '';//小計2(原価)
            $sample[] = '';//金額合計
            $sample[] = '';//原価合計

            // ダブルクォーテーションで囲んでつなげる
            $ret .= '"SHCF_EST_101_M"';
            foreach ($sample as $aaa) {
                $ret .= ',' . $this->escapeIFcsv($aaa);
            }
            $ret .= "\r\n";
        }

        return $ret;
    }

    /**
     * SHCFEST102セット
     * @param object $selectData
     * @param object $campaignData
     * @return
     */
    public function setSHCFEST102($selectData, $campaignData)
    {
        //発登録
        $sample = array();
        $sample[] = sprintf("%010d",$selectData['id']);//HP受付番号
        $sample[] = '1';//拠点区分
        $sample[] = '';//明細品目コード
        $sample[] = $selectData['from_center_ifid'];//拠点CD
        $sample[] = '';//明細品目名称
        if ($selectData['pre_exist_flag'] === 't') {
            //概算あり
            $sample[] = '1';//数量
            $sample[] = '0';//値引率
            $sample[] = '0';//値引金額
            $sample[] = '';//単価（税抜き）
            $sample[] = ceil($selectData['pre_base_price'] / 1.05);//金額（税抜き）
            $sample[] = '';//予想原価（税抜き）
            $sample[] = '';//予想原価計（税抜き）
            $sample[] = ceil($selectData['pre_estimate_price'] / 1.05);//値引後金額（税抜き）
            $sample[] = '';//単価（税込み）
            $sample[] = $selectData['pre_base_price'];//金額（税込み）
            $sample[] = '';//予想原価（税込み）
            $sample[] = '';//予想原価計（税込み）
            $sample[] = $selectData['pre_estimate_price'];//値引後金額（税込み）
            $sample[] = '';//備考欄
        } else {
            //概算なし
            $sample[] = '0';//数量
            $sample[] = '0';//値引率
            $sample[] = '0';//値引金額
            $sample[] = '';//単価（税抜き）
            $sample[] = '0';//金額（税抜き）
            $sample[] = '';//予想原価（税抜き）
            $sample[] = '';//予想原価計（税抜き）
            $sample[] = '0';//値引後金額（税抜き）
            $sample[] = '';//単価（税込み）
            $sample[] = '0';//金額（税込み）
            $sample[] = '';//予想原価（税込み）
            $sample[] = '';//予想原価計（税込み）
            $sample[] = '0';//値引後金額（税込み）
            $sample[] = '';//備考欄
        }

        // ダブルクォーテーションで囲んでつなげる
        $ret = '"SHCF_EST_102"';
        foreach ($sample as $aaa) {
            $ret .= ',' . $this->escapeIFcsv($aaa);
        }
        $ret .= "\r\n";

        //着登録
        if ($selectData['from_center_ifid'] != $selectData['to_center_ifid']) {
            $sample = array();
            $sample[] = sprintf("%010d",$selectData['id']);//HP受付番号
            $sample[] = '3';//拠点区分
            $sample[] = '';//明細品目コード
            $sample[] = $selectData['to_center_ifid'];//拠点CD
            $sample[] = '';//明細品目名称
            if ($selectData['pre_exist_flag'] === 't') {
                //概算あり
                $sample[] = '1';//数量
            } else {
                //概算なし
                $sample[] = '0';//数量
            }
            $sample[] = '0';//値引率
            $sample[] = '0';//値引金額
            $sample[] = '';//単価（税抜き）
            $sample[] = '0';//金額（税抜き）
            $sample[] = '';//予想原価（税抜き）
            $sample[] = '';//予想原価計（税抜き）
            $sample[] = '0';//値引後金額（税抜き）
            $sample[] = '';//単価（税込み）
            $sample[] = '0';//金額（税込み）
            $sample[] = '';//予想原価（税込み）
            $sample[] = '';//予想原価計（税込み）
            $sample[] = '0';//値引後金額（税込み）
            $sample[] = '';//備考欄

            // ダブルクォーテーションで囲んでつなげる
            $ret .= '"SHCF_EST_102"';
            foreach ($sample as $aaa) {
                $ret .= ',' . $this->escapeIFcsv($aaa);
            }
            $ret .= "\r\n";

        }
        return $ret;
    }

    /**
     * 値に対して、IFcsv用のエスケープ処理を行う
     * @param object $str
     * @return
     */
    public function escapeIFcsv($str)
    {
        $str = str_replace("\r\n", "\n", $str);//改行コードを統一
        $str = str_replace("\r", "\n", $str);//改行コードを統一
        $str = str_replace("\n", '\r\n', $str);//改行コードを統一
        $str = str_replace('\\', '\\\\', $str);//\→\\に置換
        $str = str_replace(",", "\\,", $str);//,→\,に置換
        $str = str_replace('"', '\"', $str);//"→\"に置換
        $str = "\"" . $str . "\"";
        $str = mb_convert_encoding($str, 'SJIS-win', 'UTF-8');

        return $str;
    }

}