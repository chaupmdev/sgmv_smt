<?php
/**
 * BEQ/Send アンケート結果送信バッチの、データ抽出＆チェック機能です。
 * @package    maintenance
 * @subpackage BEQ
 * @author     M.Tamada(NS)
 * @copyright  2016 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('CommonConst');
Sgmov_Lib::useAllComponents(FALSE);
Sgmov_Lib::useServices('CenterMail');
Sgmov_Lib::useprocess(array('BeqSender', 'BeqResponse'));
/**#@-*/

class Sgmov_Process_Beq extends Sgmov_Process_BeqSender
{

    /**
     * 起動チェックファイル名
     */
    const OPRATION_FILE_NAME = 'operation_beq.txt';

    public function execute()
    {
    	Sgmov_Component_Log::debug('Execute');
        // バッチ起動チェックと起動
        $check1 = $this->startBeqcheck(Sgmov_Lib::getLogDir() . '/' . self::OPRATION_FILE_NAME);
        if ($check1 === false) {
            $this->errorInformation('startBeq');
        }

        // 1件以上対象があればバッチ処理の実行
        Sgmov_Component_Log::debug('SelectBe');
        $alldata = $this->selectData();
        Sgmov_Component_Log::debug('Selectaf');
        if ($alldata->size() > 0) {
            for ($i = 0; $i < $alldata->size(); ++$i) {
            	Sgmov_Component_Log::debug('GetData');
                $row = $alldata->get($i);
                $this->beqOutline($row);
            }
        }

        // バッチ終了処理
        $check2 = $this->stopBeq(Sgmov_Lib::getLogDir() . '/' . self::OPRATION_FILE_NAME);
        if ($check2 == false) {
            $this->errorInformation('stopBeq');
        }
    }

    /**
     * バッチ起動チェック
     * @param object $file
     * @return true or false
     */
    public function startBeqcheck($file)
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
    public function stopBeq($file)
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
        // todo t
        Sgmov_Component_Mail::sendTemplateMail($status, dirname(__FILE__) . '/../../lib/mail_template/beq_error.txt', $mail_to);
        exit;
    }

    /**
     * 対象レコードを取得
     * @return
     */
    public function selectData()
    {
        $db = Sgmov_Component_DB::getAdmin();
        //未送信データと、失敗しているものを再送
        $sql = "
        		SELECT
						id
						,enq_type
						,phone_type
						,uketsuke_no
						,sagyoirai_no
        				,batch_status
        				,send_result
      					,sent
        				,retry_count
        				,to_char(created,'yyyyMMdd') as created
				FROM enquete
			    WHERE
			        batch_status IN (1,2)
			    ORDER BY
			        id;";

        $selectData = $db->executeQuery($sql);

        return $selectData;
    }


    public function GetOneData($id){
    	$db = Sgmov_Component_DB::getAdmin();
    	$sql = "
			    SELECT
						enq_id
						,answer
				FROM enquete_meisai
				WHERE id = $1
				ORDER BY id,enq_id
				;";

    	$params = array($id);
    	$meisai = $db->executeQuery($sql,$params);

    	return $meisai;

    }



    /**
     * バッチメイン処理
     * @param object $selectData
     * @return
     */
    public function beqOutline($selectData)
    {

        if ($selectData["batch_status"] == 1) {
            //IFデータ送信
            $selectData = $this->sendData($selectData);
        }

        if ($selectData["batch_status"] == 2) {
            //管理者へメール送信（送信エラー時のみ）
            $selectData = $this->SendMailManager($selectData);
        }


    }

    /**
     * IFデータ送信
     * @param object $selectData
     * @return object $selectData
     */
    public function sendData($selectData)
    {
    	Sgmov_Component_Log::debug('SentStart');
        //データ生成
        $csvdata = $this->makeIFcsv($selectData);

        //データ送信
        $res = Sgmov_Process_BeqSender::sendCsvToEq('ENQUETE_' . date('YmdHis') . '.csv', $csvdata);

        $responce = new Sgmov_Process_BeqResponse;
        $responce->initialize($res);
        Sgmov_Component_Log::debug('SendEnd');
        // レスポンス値によって処理のふりわけ
        switch ($responce->sendSts) {
        // 成功：update バッチ処理状況「送信済」 送信結果「成功」
        case 0:
            $db = Sgmov_Component_DB::getAdmin();
            $db->begin();
            $db->executeUpdate("UPDATE enquete SET batch_status='2',send_result='3',sent = current_timestamp,modified = current_timestamp WHERE id=$1;", array($selectData['id']));
            $db->commit();
            $selectData["batch_status"] = 2;
            $selectData["send_result"] = 3;
            break;

        //不正データ：update バッチ処理状況「送信済」 送信結果「失敗」
        case 1:
            $db = Sgmov_Component_DB::getAdmin();
            $db->begin();
            $db->executeUpdate("UPDATE enquete SET batch_status='2',send_result='1',sent = current_timestamp,modified = current_timestamp WHERE id=$1;", array($selectData['id']));
            $db->commit();
            $selectData["batch_status"] = 2;
            $selectData["send_result"] = 1;
            break;

        //システム障害：update 送信リトライ数「+1」
        case 2:
        //送信競合：update 送信リトライ数「+1」
        case 3:
            $db = Sgmov_Component_DB::getAdmin();
            $db->begin();
            $db->executeUpdate("UPDATE enquete SET retry_count=retry_count+1,sent = current_timestamp,modified = current_timestamp WHERE id=$1;", array($selectData['id']));
            $db->commit();
            ++$selectData["retry_count"];

            // 送信リトライ階数が21以上の場合バッチ処理状況「送信済」 送信結果「リトライオーバー」
            if ($selectData["retry_count"] >= 21) {
                $db = Sgmov_Component_DB::getAdmin();
                $db->begin();
                $db->executeUpdate("UPDATE enquete SET batch_status='2',send_result='2',sent = current_timestamp,modified = current_timestamp WHERE id=$1;", array($selectData['id']));
                $db->commit();
                $selectData["batch_status"] = 2;
                $selectData["send_result"] = 2;
            }
            break;

        // 登録済み：update バッチ処理状況「送信済」 送信結果「成功」
        case 4:
            $db = Sgmov_Component_DB::getAdmin();
            $db->begin();
            $db->executeUpdate("UPDATE enquete SET batch_status='2',send_result='3',sent = current_timestamp,modified = current_timestamp WHERE id=$1;", array($selectData['id']));
            $db->commit();
            $selectData["batch_status"] = 2;
            $selectData["send_result"] = 3;
            break;

        //それ以外 送信リトライ数「+1」 送信リトライ階数が21以上の場合バッチ処理状況「送信済」 送信結果「リトライオーバー」（タイムアウト）
        default:
            $db = Sgmov_Component_DB::getAdmin();
            $db->begin();
            $db->executeUpdate("UPDATE enquete SET retry_count=retry_count+1,sent = current_timestamp,modified = current_timestamp WHERE id=$1;", array($selectData['id']));
            $db->commit();
            ++$selectData["retry_count"];
            if ($selectData["retry_count"] >= 21) {
                $db = Sgmov_Component_DB::getAdmin();
                $db->begin();
                $db->executeUpdate("UPDATE enquete SET batch_status='2',send_result='2',sent = current_timestamp,modified = current_timestamp WHERE id=$1;", array($selectData['id']));
                $db->commit();
                $selectData["batch_status"] = 2;
                $selectData["send_result"] = 2;
            }
            break;
        }
        Sgmov_Component_Log::debug('Updated');
        return $selectData;
    }


    /**
     * 管理者へメール送信（送信エラー時のみ）
     * @param object $selectData
     * @return object $selectData
     */
    public function SendMailManager($selectData)
    {

        if ($selectData["send_result"] == 1 || $selectData["send_result"] == 2) {
            // システム管理者メールアドレスを取得する。
            $mail_to = Sgmov_Component_Config::getLogMailTo();
            //メールを送信する。
            //todo t
            Sgmov_Component_Mail::sendTemplateMail($selectData, dirname(__FILE__) . '/../../lib/mail_template/beq_error_send.txt', $mail_to);
        }
        $db = Sgmov_Component_DB::getAdmin();
        $db->begin();
        $db->executeUpdate("UPDATE enquete SET batch_status='3',modified = current_timestamp WHERE id=$1;", array($selectData['id']));
        $db->commit();
        $selectData["batch_status"] = 3;

        return $selectData;
    }



    /**
     * DB値から送信用csvファイル作成
     * @param object $selectData
     * @return string $csv
     */
    public function makeIFcsv($selectData)
    {

    	//明細取得

    	$meisai = $this->GetOneData($selectData['id']);

        $csv = "";
        $csv .= "\"HEADER\"";
        $csv .= "\r\n";
        $csv .= $this->setEnqH($selectData);
        //$csv .= "\r\n";

        if ($meisai->size() > 0){
        	for ($i = 0; $i < $meisai->size(); ++$i){
        		$row = $meisai->get($i);
        		$csv .= $this->setEnqM($row);
        		//$csv .= "\r\n";
        	}
        }
        $csv .= "\"TRAILER\"";

        return $csv;
    }

    /**
     * Enqueteセット
     * @param object $selectData
     * @return
     */
    public function setEnqH($selectData)
    {

        // 登録
        $sample = array(
            $selectData['id'],
            $selectData['enq_type'],
            $selectData['phone_type'],
            $selectData['sagyoirai_no'],
            $selectData['uketsuke_no'],
        	$selectData['created'],
        );

        // ダブルクォーテーションで囲んでつなげる
        $ret = '"H"';
        foreach ($sample as $item) {
            $ret .= ',' . $this->escapeIFcsv($item);
        }
        $ret .= "\r\n";

        return $ret;
    }


    /**
     * ENQUETEセット
     * @param object $selectData
     * @return
     */
    public function setEnqM($selectData)
    {

    	// 登録
    	$sample = array(
    			$selectData['enq_id'],
    			$selectData['answer'],
    	);

    	// ダブルクォーテーションで囲んでつなげる
    	$ret = '"M"';
    	foreach ($sample as $item) {
    		$ret .= ',' . $this->escapeIFcsv($item);
    	}
    	$ret .= "\r\n";

    	return $ret;
    }



    /**
     * 値に対して、IFcsv用のエスケープ処理を行う
     * @param string $str
     * @return string $str
     */
    public function escapeIFcsv($str)
    {

        $str = str_replace("\r\n", "\n", $str);//改行コードを統一
        $str = str_replace("\r", "\n", $str);//改行コードを統一
        $str = str_replace("\n", '\r\n', $str);//改行コードを統一
        $str = str_replace('\\', '\\\\', $str);//\→\\に置換
        $str = str_replace(",", "\\,", $str);//,→\,に置換
        $str = str_replace('"', '\"', $str);//"→\"に置換
        $str = '"' . $str . '"';
        $str = mb_convert_encoding($str, 'SJIS-win', 'UTF-8');

        return $str;
    }
}