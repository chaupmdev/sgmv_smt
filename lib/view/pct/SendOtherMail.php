<?php
/**
 * @package    ClassDefFile
 * @author     Tuan
 * @copyright  2023 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/**#@+
 * include files
 */
require_once dirname(__FILE__).'/../../Lib.php';
Sgmov_Lib::useView('pct/Common');
Sgmov_Lib::useServices(array('CenterMail'));
Sgmov_Lib::useAllComponents(FALSE);
/**#@-*/
/**
 * 旅客手荷物受付サービスの特別メール送信する。
 * @package    View
 * @subpackage PCR
 * @author     Tuan
 * @copyright  2023 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pct_SendOtherMail extends Sgmov_View_Pct_Common {

    /**
     * 共通サービス
     * @var Sgmov_Service_AppCommon
     */
    public $_appCommon;
    /**
     * 旅客手荷物受付サービスのお申し込みサービス
     * @var Sgmov_Service_Cruise
     */
    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_appCommon                        = new Sgmov_Service_AppCommon();
    }

    /**
     * 処理を実行します。
     */
    public function executeInner() {

        // セッションの継続を確認
        $kensu = 0;
        try {
            // DB接続
            $db = Sgmov_Component_DB::getPublic();
            
            //1 Get list email
            $listEmail = $this->getEmailSend($db);
            
            if (!isset($listEmail) || empty($listEmail)) {
                Sgmov_Component_Log::info("クルーズのメール送信リストが0件です。");
                return array(
                    'kensu' => $kensu
                );
            }
            Sgmov_Component_Log::info("クルーズのメール送信リストが".count($listEmail)."件です。");
            //2 Loop list email to send
            //メールテンプレート(申込者用)
            $mailTemplate = array();
            //テンプレートデータ
            $data = array();
            $mailTemplate[] = "/pct_user_sory_mail.txt";
            //メール送信実行
            $objMail = new Sgmov_Service_CenterMail();
            foreach ($listEmail as $item) {
                Sgmov_Component_Log::info("Email:{$item['mail']}");
                // 申込者へメール
                $objMail->_sendThankYouMail($mailTemplate, $item['mail'], $data);
                $kensu += 1;
            }
            Sgmov_Component_Log::info("クルーズのメール送信した{$kensu}件です。");
        } catch (Exception $e) {
            Sgmov_Component_Log::err('メール送信に失敗しました。');
            Sgmov_Component_Log::err($e);
            throw new Exception('メール送信に失敗しました。');
        }

        return array(
            'kensu' => $kensu,
        );
    }
    
    private function getEmailSend($db) {
        $returnList = array();
        // この順番でSQLのプレースホルダーに適用されます。
        $query = ' SELECT * FROM cruise_special_mail WHERE 1 = 1 ORDER BY id ';
        $params = array();
        
        $result = $db->executeQuery($query, $params);
        $resSize = $result->size();
        if (empty($resSize)) {
            return array();
        }
        for ($i = 0; $i < $result->size(); ++$i) {
            $returnList[] = $result->get($i);
        }
        return $returnList;
    }
}