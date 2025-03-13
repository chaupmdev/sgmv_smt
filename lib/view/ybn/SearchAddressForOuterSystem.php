<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TCP)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useAllComponents();
Sgmov_Lib::useView('Public');
Sgmov_Lib::useServices(array('Prefecture', 'Yubin'));
Sgmov_Lib::useServices('Yubin');
Sgmov_Lib::useServices('SocketZipCodeDll');
/**#@-*/

 /**
 * 郵便番号から住所を検索して返します。
 * @package    View
 * @subpackage MVE
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Yvn_SearchAddressForOuterSystem extends Sgmov_View_Public
{
    /**
     * 郵便・住所サービス
     * @var Sgmov_Service_Yubin
     */
    public $_YubinService;
    
    /**
     * 郵便番号DLLサービス
     * @var Sgmov_Service_SocketZipCodeDll
     */
    protected $_SocketZipCodeDll;
    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        $this->_YubinService = new Sgmov_Service_Yubin();
        $this->_SocketZipCodeDll = new Sgmov_Service_SocketZipCodeDll();
    }

    /**
     * 処理を実行します。
     *
     */
    public function executeInner()
    {
        //2022/11/02 データベースから郵便DLLに変更する。
        return $this->getAddressByZipCode();

//        $featureId = $_GET['f'];
//        $fromGamenId = $_GET['g'];
//        $ticket = $_GET['t'];
//        $zipcode = str_replace('-', '', $_GET['nzip']);
//
//        // チケット確認
//        //$this->_checkSession($featureId, $fromGamenId, $ticket);
//
//    try{
//        // DB接続
//        $db_yubin = Sgmov_Component_DB::getYubinPublic();
//
//        // 現住所の住所検索結果をセッションにセット
////        $db = Sgmov_Component_DB::getPublic();
////        $service = new Sgmov_Service_Prefecture();
////        $prefectures = $service->fetchPrefectures($db);
//        $SearchAdd = $this->_YubinService->fetchAddressByZip($db_yubin, $zipcode);
//        
//        if (count($SearchAdd) === 0) {
//            $temp1 = '';
//            $temp2 = '該当する住所がありません';
//            $temp3 = '';
//        } else {
//            $i = 0;
//            $temp1 = '';
//            $temp2 = '';
//            $temp3 = '';
//            foreach ($SearchAdd as $value) {
//                if ($i == 0) {
//                    $temp1 = $value['prefecture'];
//                    $temp2 = $value['city'];
//                    $temp3 = $value['address'];
//                } else {
//                    if ($temp1 != $value['prefecture']) {
//                        $temp1 = "";
//                    }
//                    if ($temp2 != $value['city']) {
//                        $temp2 = "";
//                    }
//                    if ($temp3 != $value['address']) {
//                        $temp3 = "";
//                    }
//                }
//                $i++;
//            }
//        if($temp2 =="" && $temp3 == ""){
//            $temp2 = "複数住所が該当しますので手入力でお願いします";
//        }
//
//        }
//	}
//	catch (exception $e) {
//            $temp1 = "";
//            $temp2 = "ただいま住所検索がご利用いただけません";
//            $temp3 = "";
//        }
//        return array(array_search($temp1, $prefectures['names']), $temp2, $temp3);
    }
    
    /**
     * JAVAの住所検索のAPIを呼んで、取得住所を戻る。
     *
     */
    public function getAddressByZipCode()
    {
        $featureId = $_GET['f'];
        $fromGamenId = $_GET['g'];
        $ticket = $_GET['t'];
        $zipcode = str_replace('-', '', $_GET['nzip']);

        try{
            // 現住所の住所検索結果をセッションにセット
            $db = Sgmov_Component_DB::getPublic();
            $service = new Sgmov_Service_Prefecture();
            $prefectures = $service->fetchPrefectures($db);

            //Javaの住所取得APIを呼ぶ
            $resultZipDll = $this->_SocketZipCodeDll->searchByZipCode($zipcode);
            Sgmov_Component_Log::debug($resultZipDll);

            if (!isset($resultZipDll) || empty($resultZipDll) || !empty($resultZipDll['LastErrorMessage'])) {
                Sgmov_Component_Log::debug("getAddressByZipCode address is null or empty");
                $temp1 = '';
                $temp2 = '該当する住所がありません';
                $temp3 = '';
            } else {
                Sgmov_Component_Log::debug("getAddressByZipCode address is not null");
                Sgmov_Component_Log::debug($resultZipDll);
                $temp1 = $resultZipDll['KenName'];
                $temp2 = $resultZipDll['CityName'];
                $temp3 = $resultZipDll['TownName'];
            }
        }
        catch (exception $e) {
            Sgmov_Component_Log::debug("getAddressByZipCode error");
            Sgmov_Component_Log::debug($e);
            $temp1 = "";
            $temp2 = "ただいま住所検索がご利用いただけません";
            $temp3 = "";
        }
        
        return array(array_search($temp1, $prefectures['names']), $temp2, $temp3);
    }

    /**
     * チケットの確認を行います。
     *
     */
    public function _checkSession($featureId, $fromGamenId, $ticket)
    {
    	// セッション
        $session = Sgmov_Component_Session::get();
Sgmov_Component_Log::debug($session);
Sgmov_Component_Log::debug($_SESSION);

        // チケットの確認
        if (!isset($_SESSION[Sgmov_Component_Session::_KEY_TICKETS])) {
            Sgmov_Component_Log::warning("【郵便番号検索 不正使用】チケットが存在していません。");
            header("HTTP/1.0 404 Not Found");
            exit;
        }

        $tickets = &$_SESSION[Sgmov_Component_Session::_KEY_TICKETS];
        if (!isset($tickets[$featureId]) || $tickets[$featureId] !== $fromGamenId . $ticket) {
            Sgmov_Component_Log::warning("【郵便番号検索 不正使用】チケットが不正です。　".$tickets[$featureId]." <=> ".$fromGamenId . $ticket);
            header("HTTP/1.0 404 Not Found");
            exit;
        } else {
        	Sgmov_Component_Log::debug("郵便番号検索実行 機能ID=>".$tickets[$featureId]." <=> ".$fromGamenId . $ticket);
        }
    	
    }
 
}
?>
