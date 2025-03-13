<?php
/**
 * @package    ClassDefFile
 * @author     S.Nakamichi
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
Sgmov_Lib::useServices(array('Prefecture', ));
/**#@-*/


/**
 *
 * SgFinancial API
 *
 * @package Service
 * @author     S.Nakamichi
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_SgFinancial{

//    const SHOP_CODE = "sgf000104401";
////    const SHOP_CODE = "sgf000072001"; // 本番
//
//    const LINK_ID = "original00";
////    const LINK_ID = "original00"; // 本番
//
//    const LINK_PASSWORD = "qlVel5IflX";
////    const LINK_PASSWORD = "XXFg1NcfGO"; // 本番
//
//    const BASE_API_URL = 'https://test.manage.sg-atobara.jp/api';
////    const BASE_API_URL = 'https://www.manage.sg-atobara.jp/api'; // 本番

    private static $SGF_SHOP_CODE;
    private static $SGF_LINK_ID;
    private static $SGF_LINK_PASSWORD ;
    private static $SGF_BASE_API_URL;

    /**
     * 都道府県サービス
     * @var Sgmov_Service_Prefecture
     */
    private $_PrefectureService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_PrefectureService = new Sgmov_Service_Prefecture();
        self::$SGF_SHOP_CODE = Sgmov_Component_Config::getSgFinancialShopCode();
        self::$SGF_LINK_ID = Sgmov_Component_Config::getSgFinancialLinkId();
        self::$SGF_LINK_PASSWORD = Sgmov_Component_Config::getSgFinancialLinkPasword();
        self::$SGF_BASE_API_URL = Sgmov_Component_Config::getSgFinancialBaseApiUrl();
    }
    
    /**
     * 
     * @param type $execDate 実行日時 'Y-m-d H:i:s'
     * @return type
     */
    private function getAuthInfo ($execDate = null) {
        if (@empty($execDate)) {
            $execDate = date('Y-m-d H:i:s');
        }
        ////////////////////////////////////////////////////////////////////////////////
        // 認証情報生成
        ////////////////////////////////////////////////////////////////////////////////
        $useDate = date('YmdHis', strtotime($execDate)); // 利⽤⽇時⽇時 [形式（YYYYMMDDHH24MISS）]
        $useCode = self::$SGF_LINK_ID; // ★ 利用コード(恐らくSG-Financial からもらえる)
        $shopCode = self::$SGF_SHOP_CODE; // ★ 店舗コード
        $secretKey = self::$SGF_LINK_PASSWORD; // ★ 秘密鍵(恐らくSG-Financial からもらえる)
        
        // 認証コード生成
        $authCode = hash('sha512', "{$useDate}{$useCode}{$shopCode}{$secretKey}");
Sgmov_Component_Log::debug("{$useDate}{$useCode}{$shopCode}{$secretKey}");
        $reqAuthInfo = array(
            'useCode' => $useCode, // 恐らくSG-Financial からもらえる
            'authCode' => $authCode, // 認証コード
            'hashType' => '001', // ハッシュ関数区分 SHA512 = 001 / SHA256 = 002 / SHA1 = 003
            'useDate' => $useDate,
            'tenpoNo' => $shopCode, // ★ ?
        );
        
        return $reqAuthInfo;
        
//        $authCode = hash('sha512', "{$useDate}731C7513AD551EC100300008100001146f49469e0241c7a7d00ec0c3b641f5");
//        $reqAuthInfo = array(
//            'useCode' => '731C7513AD551EC1', // 恐らくSG-Financial からもらえる
//            'authCode' => $authCode, // 認証コード
//            'hashType' => '001', // ハッシュ関数区分 SHA512 = 001 / SHA256 = 002 / SHA1 = 003
//            'useDate' => $useDate,
//            'tenpoNo' => '00300008100001', // 店舗コード
//        );
//        return $reqAuthInfo;
    }
    
    public function sendTest($shopOrderId) {
        $reqAuthInfo = $this->getAuthInfo();
        $reqest = $reqAuthInfo + array(
//  'useCode' => '731C7513AD551EC1',
//  'authCode' => 'f17a8a4848186025020d180a6e6834b7da079543e348c33b86c28e7165c741b440f54173cb0524d5774d76ed51473c88267bb391f23b75473c7664dbd457b458',
//  'hashType' => '001',
//  'useDate' => '20190711171746',
//  'tenpoNo' => '00300008100001',
  'shopOrderId' => $shopOrderId,
  'shimeiKanji' => '澤源',
  'shimeiKana' => 'ミセッテイ',
  'zipCode' => '1000001',
  'address' => '東京都千代田区千代田',
  'tel' => '08011239910',
  'mail' => 'ken-sawada@spcom.co.jp',
  'orderDate' => '2019/07/11',
  'orderShohinName_1' => '個人 宅配',
  'orderKosu_1' => '1',
  'orderTanka_1' => '6117',
  'souryou' => '0',
  'atobaraiTesuryo' => '0',
  'seikyuKingaku' => '6117',
            
        );
//                $reqAuthInfo + array(
//            'shopOrderId' => '1234567893',
//            'shimeiKanji' => '佐川太郎',
//            'shimeiKana' => 'サガワタロウ',
//            'zipCode' => '1000001',
//            'address' => '東京都千代田区千代田１－１－１',
//            'tel' => '08011239911',
//            'mail' => 'ken-sawada@spcom.co.jp',
//            'orderDate' => '2019/07/11',
//            'orderShohinName_1' => 'テスト商品',
//            'orderKosu_1' => '1',
//            'orderTanka_1' => '108',
//            'souryou' => '200',
//            'atobaraiTesuryo' => '0',
//            'seikyuKingaku' => '308',
//        );
        
//Sgmov_Component_Log::debug($reqest);
        
        $reqestData = http_build_query($reqest, "", "&");
//Sgmov_Component_Log::debug($reqestData);
        
        // header
        $header = array(
            "Content-Type: application/x-www-form-urlencoded",
            "Content-Length: ".strlen($reqestData),
            "User-Agent: Mozilla",
        );
        
        $context = array(
            "http" => array(
                "method"  => "POST",
                "header"  => implode("\r\n", $header),
                "content" => $reqestData,
            )
        );
        $context2 = stream_context_create($context);
        
        // json => https://api-demo-atobarai.lifecard.co.jp/api/v1/chumon/add.json
        // xml =>  https://api-demo-atobarai.lifecard.co.jp/api/v1/chumon/add.xml
        $responceJson = file_get_contents("https://api-demo-atobarai.lifecard.co.jp/api/v1/chumon/add.json", false, $context2);
//echo $responceJson;
//print_r($http_response_header);
        Sgmov_Component_Log::debug($http_response_header);
        Sgmov_Component_Log::debug($responceJson);
        
        return $responceJson;
    }
    
    /**
     * リクエスト用XMLを返す。
     * @param $li 設定用配列
     * @return string
     */
    public function requestSgFinancialService($li){
        
        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();
        
        $execDate = date('Y-m-d H:i:s');
        
        ////////////////////////////////////////////////////////////////////////////////
        // 認証情報生成
        ////////////////////////////////////////////////////////////////////////////////
        $reqAuthInfo = $this->getAuthInfo($execDate);
        
        ////////////////////////////////////////////////////////////////////////////////
        // 詳細情報生成
        ////////////////////////////////////////////////////////////////////////////////
        
        $prefName = $this->_PrefectureService->fetchPrefecturesById($db, $li['pref_id']);
        
        //購入者情報
        $reqPostInfo = array(
            'shopOrderId' => $li['id'],  // 申込ID
            'shinsaType' => '1', // 審査実施区分 1 => 同期審査 / 0 => 非同期
            'shimeiKanji' => $li['personal_name_sei'].$li['personal_name_mei'], // 購入者の氏名の漢字
            'shimeiKana' => ' ', // 購入者の氏名のカタカナ [必須]
            'zipCode' => $li['zip'], // 購入者の郵便番号
            'address' => str_replace('　', '', $prefName['name'].$li['address'].$li['building']), // 購入者の住所 : 購⼊者の住所 都道府県＋市区町村＋町名・番地＋マンション名など
            'tel' => $li['tel'], // 購入者のの電話番号１
            'mail' => $li['mail'], // 購入者のメールアドレス１
        );
        
        // 配送先情報
        $reqShipList = array();
        
        // 明細情報
        $reqDetailInfo = array();
        $count = 1;
        foreach($li['comiket_detail_list'] as $keyDtl => $valDtl) {
            $prefNameDtl = $this->_PrefectureService->fetchPrefecturesById($db, $valDtl['pref_id']);
            
            $goods = ($li['div'] == 2) ? '法人' : '個人';
            // 配送先情報
            $reqShipList += array(
                "soufusakiShimeiKanji_{$count}" => $li['staff_sei'].$li['staff_mei'], // 送付先⽒名（漢字） 
                "soufusakiShimeiKana_{$count}" => $li['staff_sei_furi'].$li['staff_mei_furi'], // 送付先⽒名（カナ） 
                "soufusakiZipCode_{$count}" => $valDtl['zip'], // 送付先郵便番号
                "soufusakiAddress_{$count}" => str_replace('　', '', $prefNameDtl['name'].$valDtl['address'].$valDtl['building']), // 送付先住所
                "soufusakiTel_{$count}" => $valDtl['tel'], // 送付先電話番号
                "orderDate" => date('Y/m/d', strtotime($execDate)),
                "orderShohinName_{$count}" => $goods.' 宅配',
                "orderKosu_{$count}" => '1',
                "orderTanka_{$count}" => "" . ($valDtl['fare_tax'] + $valDtl['cost_tax']) . "",
            );

            $count++;
        }
        
        //ブラウザ関連情報 (任意なので設定しない)
//        $httpHeader = '';
//        foreach(getallheaders() as $name => $value){
//            $httpHeader .= $name.': '.$value.' ';
//        }
//        $deviceInfo = $this->getDeviceInfo();
//        if( $deviceInfo === false ){
//            $deviceInfoVal = '';
//        }else{
//            //$deviceInfoVal = $deviceInfo['ua'].' '.$deviceInfo['browser_name'].' '.$deviceInfo['browser_version'].' '.$deviceInfo['platform'].'';
//            $deviceInfoVal = $deviceInfo['ua'];
//        }
        
        // 合計金額など
        $reqFooterInfo = array(
            "souryou" => '0',
            "atobaraiTesuryo" => '0',
            "seikyuKingaku" => "" . $li['amount_tax'] . "",
            // ▼ 任意なので設定しない
//            'fraudbuster' => $deviceInfoVal, //デバイス情報
//            'httpHeader' => $httpHeader, // HTTPヘッダー情報
        );
        
        // 送信データ作成
        $reqestDataList = $reqAuthInfo + $reqPostInfo + $reqShipList + $reqDetailInfo + $reqFooterInfo;
        
        $reqestData = http_build_query($reqestDataList, "", "&");
        
        // header
        $header = array(
            "Content-Type: application/x-www-form-urlencoded",
            "Content-Length: ".strlen($reqestData),
            "User-Agent: Mozilla",
        );
        
        $context = array(
            "http" => array(
                "method"  => "POST",
                "header"  => implode("\r\n", $header),
                "content" => $reqestData
            )
        );
        
        Sgmov_Component_Log::info('▼ SGF 注文情報連携API リクエスト :');
        Sgmov_Component_Log::info($reqestData);
        Sgmov_Component_Log::info($reqestDataList);

        $context2 = stream_context_create($context);
        $responceJson = file_get_contents(self::$SGF_BASE_API_URL . "/v1/chumon/add.json", false, $context2);

        
        preg_match('/HTTP\/1\.[0|1|x] ([0-9]{3})/', $http_response_header[0], $matches);
        $statusCode = $matches[1];
        
        switch ($statusCode) {
            case '200':
                // レスポンスコード = 200の場合 ///////////////////////////////////////////////////////////////////
                $resultData = array();
                
                $responceAry = json_decode($responceJson , true);
                Sgmov_Component_Log::info('▼ SGF 注文情報連携API レスポンス :');
                Sgmov_Component_Log::info($responceJson);
                Sgmov_Component_Log::info($responceAry);
                
                
                ////////////////////////////////////////////////////////////////////////////////
                // 処理結果
                ////////////////////////////////////////////////////////////////////////////////
                $resultCode = $responceAry['resultCode']; 
                if ($resultCode == 'success') {
                    $resultData['result'] = 'OK';
                } else {
                    $resultData['result'] = 'NG';
                }
                
                ////////////////////////////////////////////////////////////////////////////////
                // 審査結果コード
                ////////////////////////////////////////////////////////////////////////////////
                $shinsaReusltCode = $responceAry['shinsaReusltCode'];
                if ($shinsaReusltCode == '001') { // 審査OK
                    $resultData["transactionInfo"]["autoAuthoriresult"] = 'OK';
                    
                } else if ($shinsaReusltCode == '002') { // 審査NG
                    $resultData["transactionInfo"]["autoAuthoriresult"] = 'NG';
                    
                } else if ($shinsaReusltCode == '003' || $shinsaReusltCode == '004') { // 審査中
                    $resultData["transactionInfo"]["autoAuthoriresult"]= '審査中';
                    
                } else if ($shinsaReusltCode == '005') { // 審査NG(属性確認)
                    $resultData["transactionInfo"]["autoAuthoriresult"] = 'NG';
                    
                } else {
                    $resultData["transactionInfo"]["autoAuthoriresult"] = 'NG';
                }

                ////////////////////////////////////////////////////////////////////////////////
                // 加盟店注文番号
                ////////////////////////////////////////////////////////////////////////////////
                $shopOrderId = $responceAry['shopOrderId'];
                $resultData["transactionInfo"]["shopOrderId"] = $shopOrderId;
                
                ////////////////////////////////////////////////////////////////////////////////
                // 受付番号
                ////////////////////////////////////////////////////////////////////////////////
                $acceptNo =  $responceAry['acceptNo'];
                $resultData["transactionInfo"]["transactionId"] = $acceptNo;
                
                
                return $resultData;
            case '404':
            default:
                // 404の場合
                Sgmov_Component_Log::info('SgFinancial-API 取引登録処理に失敗しました。');
                Sgmov_Component_Log::info('エラー情報 : ');
                Sgmov_Component_Log::info($http_response_header);
                Sgmov_Component_Log::info($responceJson);
                throw new Exception('SgFinancial-API 取引登録処理に失敗しました。');
                break;
        }
    }

    /**
     * リクエスト用XMLを返す。
     * @param $li 設定用配列
     * @return string
     */
    public function requestSgFinancialService_old($li){

        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();
        $retArr = false;
        try{
            $li['personal_name_mei'] = empty($li['personal_name_mei']) ? '（申込者）' : $li['personal_name_mei'];

            //連携情報
            $linkInfo = array(
                //加盟店コード
                'shopCode' => self::$SGF_SHOP_CODE,
                //接続先特定ID
                'linkId' => self::$SGF_LINK_ID,
                //連携パスワード
                'linkPassword' => self::$SGF_LINK_PASSWORD,
            );

            //ブラウザ関連情報
            $httpHeader = '';
            foreach(getallheaders() as $name => $value){
                $httpHeader .= $name.': '.$value.' ';
            }
            $deviceInfo = $this->getDeviceInfo();
            if( $deviceInfo === false ){
                $deviceInfoVal = '';
            }else{
                //$deviceInfoVal = $deviceInfo['ua'].' '.$deviceInfo['browser_name'].' '.$deviceInfo['browser_version'].' '.$deviceInfo['platform'].'';
                $deviceInfoVal = $deviceInfo['ua'];
            }

            $browserInfo = array(
                //HTTPヘッダー情報
                'httpHeader' => $httpHeader,
                //デバイス情報
                'deviceInfo' => $deviceInfoVal,
            );
            $prefName = $this->_PrefectureService->fetchPrefecturesById($db, $li['pref_id']);
            //購入者情報
            $customer = array(
                //ご購入店受注番号
                'shopOrderId' => $li['id'],
                //購入者注文日
//                'shopOrderDate' => date('Y/m/d', strtotime($li['created'])),
                'shopOrderDate' => date('Y/m/d'),
                //氏名（漢字）
                'name' => $li['personal_name_sei'].$li['personal_name_mei'],
                //氏名（カナ）
                'kanaName' => '', // TODO カナ列はない？
                //郵便番号
                'zip' => $li['zip'],
                //住所 住所にスペースが含まれているとエラーとなってしまうので空白を除く
                'address' => str_replace('　', '', $prefName['name'].$li['address'].$li['building']),    //TODO 都道府県コードがくる？
                //会社名
                'companyName' => $li['office_name'],
                //部署名
                'sectionName' => ($li['div'] == 2) ? '法人' : '',
                //電話番号
                'tel' => $li['tel'],
                //メールアドレス
                'email' => $li['mail'],
                //顧客請求金額（税込）
                'billedAmount' => $li['amount_tax'],
                //拡張項目１
                'expand1' => '',
                //請求書送付方法
                'service' => '2',    //別送
            );

            foreach($li['comiket_detail_list'] as $keyDtl => $valDtl){
                $prefNameDtl = $this->_PrefectureService->fetchPrefecturesById($db, $valDtl['pref_id']);
                //配送先情報
                $ship = array(
                    //氏名（漢字）
                    'shipName' => $li['staff_sei'].$li['staff_mei'],
                    //氏名（カナ）
                    'shipKananame' => $li['staff_sei_furi'].$li['staff_mei_furi'],
                    //郵便番号
                    'shipZip' => $valDtl['zip'],
                    //住所 住所にスペースが含まれているとエラーとなってしまうので空白を除く
                    'shipAddress' => str_replace('　', '', $prefNameDtl['name'].$valDtl['address'].$valDtl['building']),
                    //会社名
                    'shipCompanyName' => '',    // TODO
                    //部署名
                    'shipSectionName' => '',    // TODO
                    //電話番号
                    'shipTel' => $valDtl['tel'],
                );

                //明細詳細項目
                $details = array();
                $goods = ($li['div'] == 2) ? '法人' : '個人';
                //---宅配便
                if( isset($valDtl['comiket_box_list']) && !empty($valDtl['comiket_box_list']) ){
                    $num = 0;
                    foreach($valDtl['comiket_box_list'] as $keyBox => $valBox) {
                        $num += intval($valBox['num']);
                    }

                    $details[] = array(
                        'detail' => array(
                            //明細名（商品名）
                            'goods' => $goods.' 宅配',
                            //単価（税込）
                            'goodsPrice' => $li['amount_tax'],
                            //数量
                            'goodsAmount' => '1',
//                            'goodsAmount' => $num,
                            //拡張項目２
                            'expand2' => '',
                            //拡張項目３
                            'expand3' => '',
                            //拡張項目４
                            'expand4' => '',
                        ),
                    );
                }
                //---カーゴ
                if( isset($valDtl['comiket_cargo_list']) && !empty($valDtl['comiket_cargo_list']) ){
                    foreach($valDtl['comiket_cargo_list'] as $keyCargo => $valCargo) {
                        $details[] = array(
                            'detail' => array(
                                //明細名（商品名）
                                'goods' => $goods.' カーゴ',
                                //単価（税込）
                                'goodsPrice' => $li['amount_tax'],   //TODO 列なし
                                //数量
                                'goodsAmount' => '1',
//                                'goodsAmount' => $valCargo['num'],
                                //拡張項目２
                                'expand2' => '',
                                //拡張項目３
                                'expand3' => '',
                                //拡張項目４
                                'expand4' => '',
                            ),
                        );
                    }
                }
                //---貸切
                if( isset($valDtl['comiket_charter_list']) && !empty($valDtl['comiket_charter_list']) ){
                    foreach($valDtl['comiket_charter_list'] as $keyCharter => $valCharter) {
                        $details[] = array(
                            'detail' => array(
                                //明細名（商品名）
                                'goods' => $goods.' チャーター',
                                //単価（税込）
                                'goodsPrice' => '',   //TODO 列なし
                                //数量
//                              'goodsAmount' => $valDtl['comiket_charter_list']['num'],
                                'goodsAmount' => '15000',
                                //拡張項目２
                                'expand2' => '',
                                //拡張項目３
                                'expand3' => '',
                                //拡張項目４
                                'expand4' => '',
                            ),
                        );
                    }
                }

                $arrs = array(
                    'linkInfo'=>$linkInfo,
                    'browserInfo'=>$browserInfo,
                    'customer'=>$customer,
                    'ship'=>$ship,
                    'details'=>$details,
                );
                $reqXml = $this->array2string('request', $arrs);
                $reqXml = '<?xml version="1.0" encoding="UTF-8"?>' . $reqXml;
//var_dump($reqXml);
                $responseXml = '';
                try{
/*
                    $reqXml = '<?xmlversion="1.0"encoding="UTF-8"?><request><linkInfo><shopCode>sgf000104401</shopCode><linkId>original00</linkId><linkPassword>qlVel5IflX</linkPassword></linkInfo><browserInfo><httpHeader></httpHeader><deviceInfo></deviceInfo></browserInfo><customer><shopOrderId>12345678901234567890</shopOrderId><shopOrderDate>2018/04/01</shopOrderDate><name>寺尾雅人</name><kanaName>テラオマサト</kanaName><zip>617-8588</zip><address>京都府向日市森本町戌亥5番地の3佐川印刷本社ビル6階</address><companyName>エスピーメディアテック株式会社</companyName><sectionName>開発部</sectionName><tel>075-924-2222</tel><email>mas-terao@spcom.co.jp</email><billedAmount>23456</billedAmount><expand1></expand1><service>2</service></customer><ship><shipName>寺尾雅人</shipName><shipKananame>テラオマサト</shipKananame><shipZip>617-8588</shipZip><shipAddress>京都府向日市森本町戌亥5番地の3佐川印刷本社ビル6階</shipAddress><shipCompanyName>エスピーメディアテック株式会社</shipCompanyName><shipSectionName>開発部</shipSectionName><shipTel>075-924-2222</shipTel></ship><details><detail><goods>配送料１</goods><goodsPrice>15600</goodsPrice><goodsAmount>1</goodsAmount><expand2></expand2><expand3></expand3><expand4></expand4></detail><detail><goods>配送料２</goods><goodsPrice>18600</goodsPrice><goodsAmount>2</goodsAmount><expand2></expand2><expand3></expand3><expand4></expand4></detail></details></request>';
*/
Sgmov_Component_Log::info("############### SgFinancial transaction.do API EXEC START ##################");
Sgmov_Component_Log::info("*************** REQUEST XML ******************");
Sgmov_Component_Log::info($reqXml);
                    $header = array(
                        "Content-Type: application/x-www-form-urlencoded",
                        "Content-Length: ".strlen($reqXml)
                    );
                    $contextOptions = array(
                        'http' => array(
                            "method"  => "POST",
                            "header"  => implode("\r\n", $header),
                            "content" => $reqXml
                        )
                    );
                    $sslContext = stream_context_create($contextOptions);
                    // TODO 取引登録API
                    $responseXml = file_get_contents(self::$SGF_BASE_API_URL . '/transaction.do', FALSE, $sslContext);
Sgmov_Component_Log::info("*************** RESPONSE XML ******************");
Sgmov_Component_Log::info($responseXml);
Sgmov_Component_Log::info("############### SgFinancial transaction.do API EXEC END ##################");

                }catch(Exception $e){
                    Sgmov_Component_Log::info('SgFinancial-API 取引登録処理に失敗しました。');
//                    Sgmov_Component_Log::debug($e);
                    Sgmov_Component_Log::info($e);
                    throw new Exception('SgFinancial-API 取引登録処理に失敗しました。');
                }

                $retArr = $this->responseXmlToArray($responseXml);
Sgmov_Component_Log::debug($retArr);
            }


        }catch(Exception $e){
            Sgmov_Component_Log::info('SgFinancial-API 取引登録処理に失敗しました。');
//            Sgmov_Component_Log::debug($e);
            Sgmov_Component_Log::info($e);
            throw new Exception('SgFinancial-API 取引登録処理に失敗しました。');
        }

        return $retArr;
    }
    
    /**
     * リクエスト用XMLを返す。
     * @param $li 設定用配列
     * @return string
     */
    public function requestCancel($li) {
        
        $execDate = date('Y-m-d H:i:s');
        
        ////////////////////////////////////////////////////////////////////////////////
        // 認証情報生成
        ////////////////////////////////////////////////////////////////////////////////
        $reqAuthInfo = $this->getAuthInfo($execDate);
        
        ////////////////////////////////////////////////////////////////////////////////
        // 詳細情報生成
        ////////////////////////////////////////////////////////////////////////////////
        $reqPostInfo = array(
            'acceptNo' => $li['res_sgf_transactionId'],
        );
        
        // 送信データ作成
        $reqestDataList = $reqAuthInfo + $reqPostInfo;
        
        $reqestData = http_build_query($reqestDataList, "", "&");
        
        // header
        $header = array(
            "Content-Type: application/x-www-form-urlencoded",
            "Content-Length: ".strlen($reqestData)
        );
        
        $context = array(
            "http" => array(
                "method"  => "POST",
                "header"  => implode("\r\n", $header),
                "content" => $reqestData
            )
        );
        
        Sgmov_Component_Log::info('▼ SGF 取引キャンセルAPI リクエスト :');
        Sgmov_Component_Log::info($reqestData);
        Sgmov_Component_Log::info($reqestDataList);
        
        $responceJson = file_get_contents(self::$SGF_BASE_API_URL . "/v1/chumon/cancel.json", false, stream_context_create($context));
        
        preg_match('/HTTP\/1\.[0|1|x] ([0-9]{3})/', $http_response_header[0], $matches);
        $statusCode = $matches[1];
        
        switch ($statusCode) {
            case '200':
                // レスポンスコード = 200の場合 ///////////////////////////////////////////////////////////////////
                $resultData = array();
                
                $responceAry = json_decode($responceJson , true);
                Sgmov_Component_Log::info('▼ SGF 取引キャンセルAPI レスポンス :');
                Sgmov_Component_Log::info($responceJson);
                Sgmov_Component_Log::info($responceAry);
                
                ////////////////////////////////////////////////////////////////////////////////
                // 処理結果
                ////////////////////////////////////////////////////////////////////////////////
                $resultCode = $responceAry['resultCode']; 
                if ($resultCode == 'success') {
                    $resultData['result'] = 'OK';
                } else {
                    $resultData['result'] = 'NG';
                }
                
                ////////////////////////////////////////////////////////////////////////////////
                // 加盟店注文番号
                ////////////////////////////////////////////////////////////////////////////////
                $shopOrderId = $responceAry['shopOrderId'];
                $resultData["transactionInfo"]["shopOrderId"] = $shopOrderId;
                
                ////////////////////////////////////////////////////////////////////////////////
                // 受付番号
                ////////////////////////////////////////////////////////////////////////////////
                $acceptNo =  $responceAry['acceptNo'];
                $resultData["transactionInfo"]["transactionId"] = $acceptNo;
                
                return $resultData;
            case '404':
            default:
                // 404の場合
                Sgmov_Component_Log::info('SgFinancial-API キャンセル処理に失敗しました。');
                Sgmov_Component_Log::info('エラー情報 : ');
                @Sgmov_Component_Log::info($responceAry);
                throw new Exception('SgFinancial-API キャンセル処理に失敗しました。');
                break;
        }
    }

    /**
     * リクエスト用XMLを返す。
     * @param $li 設定用配列
     * @return string
     */
    public function requestCancel_old($li) {
        
//        throw new Exception();
        
        try{
            //連携情報
            $linkInfo = array(
                //加盟店コード
                'shopCode' => self::$SGF_SHOP_CODE,
                //接続先特定ID
                'linkId' => self::$SGF_LINK_ID,
                //連携パスワード
                'linkPassword' => self::$SGF_LINK_PASSWORD,
            );

            if(@empty($li['res_sgf_transactionId'])) {

            }

            $transactionInfo = array(
                "updateTypeFlag" => "1", // 更新種別フラグ:キャンセル
                "transactionId" => $li['res_sgf_transactionId'],
            );

            $arrs = array(
                'linkInfo ' => $linkInfo,
                'transactionInfo' => $transactionInfo,
            );

            $reqXml = $this->array2string('request', $arrs);
            $reqXml = '<?xml version="1.0" encoding="UTF-8"?>' . $reqXml;
//var_dump($reqXml);
            $responseXml = '';
            try{
/*
                $reqXml = '<?xmlversion="1.0"encoding="UTF-8"?><request><linkInfo><shopCode>sgf000104401</shopCode><linkId>original00</linkId><linkPassword>qlVel5IflX</linkPassword></linkInfo><browserInfo><httpHeader></httpHeader><deviceInfo></deviceInfo></browserInfo><customer><shopOrderId>12345678901234567890</shopOrderId><shopOrderDate>2018/04/01</shopOrderDate><name>寺尾雅人</name><kanaName>テラオマサト</kanaName><zip>617-8588</zip><address>京都府向日市森本町戌亥5番地の3佐川印刷本社ビル6階</address><companyName>エスピーメディアテック株式会社</companyName><sectionName>開発部</sectionName><tel>075-924-2222</tel><email>mas-terao@spcom.co.jp</email><billedAmount>23456</billedAmount><expand1></expand1><service>2</service></customer><ship><shipName>寺尾雅人</shipName><shipKananame>テラオマサト</shipKananame><shipZip>617-8588</shipZip><shipAddress>京都府向日市森本町戌亥5番地の3佐川印刷本社ビル6階</shipAddress><shipCompanyName>エスピーメディアテック株式会社</shipCompanyName><shipSectionName>開発部</shipSectionName><shipTel>075-924-2222</shipTel></ship><details><detail><goods>配送料１</goods><goodsPrice>15600</goodsPrice><goodsAmount>1</goodsAmount><expand2></expand2><expand3></expand3><expand4></expand4></detail><detail><goods>配送料２</goods><goodsPrice>18600</goodsPrice><goodsAmount>2</goodsAmount><expand2></expand2><expand3></expand3><expand4></expand4></detail></details></request>';
*/
Sgmov_Component_Log::info("############### SgFinancial modifytransaction.do(CANCEL) API EXEC START ##################");
Sgmov_Component_Log::info("*************** REQUEST XML ******************");
Sgmov_Component_Log::info($reqXml);
                $header = array(
                    "Content-Type: application/x-www-form-urlencoded",
                    "Content-Length: ".strlen($reqXml)
                );
                $contextOptions = array(
                    'http' => array(
                        "method"  => "POST",
                        "header"  => implode("\r\n", $header),
                        "content" => $reqXml
                    )
                );
                $sslContext = stream_context_create($contextOptions);
                // TODO 取引登録API
                $responseXml = file_get_contents(self::$SGF_BASE_API_URL . '/modifytransaction.do', FALSE, $sslContext);
Sgmov_Component_Log::info("*************** RESPONSE XML ******************");
Sgmov_Component_Log::info($responseXml);
Sgmov_Component_Log::info("############### SgFinancial  modifytransaction.do(CANCEL) API EXEC END ##################");

            }catch(Exception $e){
                Sgmov_Component_Log::info('SgFinancial-API キャンセル処理に失敗しました。');
//                Sgmov_Component_Log::debug($e);
                Sgmov_Component_Log::info($e);
                throw new Exception('SgFinancial-API キャンセル処理に失敗しました。');
            }

            $retArr = $this->responseXmlToArray($responseXml);
Sgmov_Component_Log::debug($retArr);
        } catch(Exception $e) {
            Sgmov_Component_Log::info('SgFinancial-API キャンセル処理に失敗しました。');
//            Sgmov_Component_Log::debug($e);
            Sgmov_Component_Log::info($e);
            throw new Exception('SgFinancial-API キャンセル処理に失敗しました。');
        }

        return $retArr;
    }
    
    /**
     * 出荷情報登録API用リクエストXMLを返す
     * @param type $li
     * @param type $toiBan
     */
    public function requestShipmentReport($li, $toiBan) {
        
        $execDate = date('Y-m-d H:i:s');
        
        ////////////////////////////////////////////////////////////////////////////////
        // 認証情報生成
        ////////////////////////////////////////////////////////////////////////////////
        $reqAuthInfo = $this->getAuthInfo($execDate);
        
        ////////////////////////////////////////////////////////////////////////////////
        // 詳細情報生成
        ////////////////////////////////////////////////////////////////////////////////
        $reqPostInfo = array(
            'acceptNo' => $li['res_sgf_transactionId'],
            'shoriType' => '002', // 未設定?
            'deliCompanyCode_1' => '003', // 003 => 佐川急便
            'traceNo_1' => $toiBan, // 追跡番号
        );
        
        // 送信データ作成
        $reqestDataList = $reqAuthInfo + $reqPostInfo;
        
        $reqestData = http_build_query($reqestDataList, "", "&");
        
        // header
        $header = array(
            "Content-Type: application/x-www-form-urlencoded",
            "Content-Length: ".strlen($reqestData)
        );
        
        $context = array(
            "http" => array(
                "method"  => "POST",
                "header"  => implode("\r\n", $header),
                "content" => $reqestData
            )
        );
        
        Sgmov_Component_Log::info('▼ SGF 出荷情報連携API リクエスト :');
        Sgmov_Component_Log::info($reqestData);
        Sgmov_Component_Log::info($reqestDataList);

        $responceJson = file_get_contents(self::$SGF_BASE_API_URL . "/v1/shukka/add.json", false, stream_context_create($context));
        
        preg_match('/HTTP\/1\.[0|1|x] ([0-9]{3})/', $http_response_header[0], $matches);
        $statusCode = $matches[1];
        
        switch ($statusCode) {
            case '200':
                // レスポンスコード = 200の場合 ///////////////////////////////////////////////////////////////////
                $resultData = array();
                
                $responceAry = json_decode($responceJson , true);
                Sgmov_Component_Log::info('▼ SGF 出荷情報連携API レスポンス :');
                Sgmov_Component_Log::info($responceJson);
                Sgmov_Component_Log::info($responceAry);
                
                ////////////////////////////////////////////////////////////////////////////////
                // 処理結果
                ////////////////////////////////////////////////////////////////////////////////
                $resultCode = $responceAry['resultCode']; 
                if ($resultCode == 'success') {
                    $resultData['result'] = 'OK';
                } else {
                    $resultData['result'] = 'NG';
                }
                
                ////////////////////////////////////////////////////////////////////////////////
                // 加盟店注文番号
                ////////////////////////////////////////////////////////////////////////////////
                $shopOrderId = $responceAry['shopOrderId'];
                $resultData["transactionInfo"]["shopOrderId"] = $shopOrderId;
                
                ////////////////////////////////////////////////////////////////////////////////
                // 受付番号
                ////////////////////////////////////////////////////////////////////////////////
                $acceptNo =  $responceAry['acceptNo'];
                $resultData["transactionInfo"]["transactionId"] = $acceptNo;
                
                return $resultData;
            case '404':
            default:
                // 404の場合
                Sgmov_Component_Log::info('SgFinancial-API 出荷情報登録API処理に失敗しました。');
                Sgmov_Component_Log::info('エラー情報 : ');
                @Sgmov_Component_Log::info($responceAry);
                throw new Exception('SgFinancial-API 出荷情報登録API処理に失敗しました。');
                break;
        }
    }

    /**
     * 出荷完了用リクエストXMLを返す
     * @param type $li
     * @param type $toiBan
     */
    public function requestShipmentReport_old($li, $toiBan) {
Sgmov_Component_Log::info("出荷報告処理開始");
        try {
            //連携情報
            $linkInfo = array(
                //加盟店コード
                'shopCode' => self::$SGF_SHOP_CODE,
                //接続先特定ID
                'linkId' => self::$SGF_LINK_ID,
                //連携パスワード
                'linkPassword' => self::$SGF_LINK_PASSWORD,
            );

            $transactionInfo = array(
                "deliveryType" => "1", // 出荷報告種別:出荷報告
                "transactionId" => $li['res_sgf_transactionId'],
                "deliveryCompanyCode" => "11", // 運送会社コード：11【佐川急便】固定
                "deliverySlipNo" => $toiBan,
            );

            $arrs = array(
                'linkInfo ' => $linkInfo,
                'transactionInfo' => $transactionInfo,
            );

            $reqXml = $this->array2string('request', $arrs);
            $reqXml = '<?xml version="1.0" encoding="UTF-8"?>' . $reqXml;
            $responseXml = '';

            try {
Sgmov_Component_Log::info("############### SgFinancial modifytransaction.do(ShipmentComplete) API EXEC START ##################");
Sgmov_Component_Log::info("*************** REQUEST XML ******************");
Sgmov_Component_Log::info($reqXml);
                $header = array(
                    "Content-Type: application/x-www-form-urlencoded",
                    "Content-Length: ".strlen($reqXml)
                );
                $contextOptions = array(
                    'http' => array(
                        "method"  => "POST",
                        "header"  => implode("\r\n", $header),
                        "content" => $reqXml
                    )
                );
                $sslContext = stream_context_create($contextOptions);
                // TODO 取引登録API
                $responseXml = file_get_contents(self::$SGF_BASE_API_URL . '/shippingrequest.do', FALSE, $sslContext);
Sgmov_Component_Log::info("*************** RESPONSE XML ******************");
Sgmov_Component_Log::info($responseXml);
Sgmov_Component_Log::info("############### SgFinancial  modifytransaction.do(ShipmentComplete) API EXEC END ##################");
            } catch (Exception $ex) {
                Sgmov_Component_Log::info('SgFinancial-API 出荷完了処理に失敗しました。');
//                Sgmov_Component_Log::debug($e);
                Sgmov_Component_Log::info($ex);
                throw new Exception('SgFinancial-API 出荷完了処理に失敗しました。');
            }

            $retArr = $this->responseXmlToArray($responseXml);
Sgmov_Component_Log::debug($retArr);
        } catch (Exception $e) {
            Sgmov_Component_Log::info('SgFinancial-API 出荷完了処理に失敗しました。');
//            Sgmov_Component_Log::debug($e);
            Sgmov_Component_Log::info($e);
            throw new Exception('SgFinancial-API 出荷完了処理に失敗しました。');
        }

        return $retArr;
    }

    /**
     * レスポンスXMLを配列にして返す。
     * @param $xml レスポンスXML文字列
     * @return array
     */
    private function responseXmlToArray($xml_string){
        $xml_string = mb_convert_encoding($xml_string, 'UTF-8', 'auto');
        $xml = simplexml_load_string($xml_string);
        $json = json_encode($xml);
        return json_decode($json,TRUE);
    }

    /**
     * 配列を再帰的にXML用の文字列に変換
     *
     * foreachの$valueの値が配列でなくなるまで再帰
     * ※ ['key' => array()]という場合も考慮
     *
     * @param string $name 要素の名前
     * @param array or string $data 配列もしくは要素の中身
     * @return string $str
     */
    private function array2string($name = '', $data){
        $str = '';

        $EOL = PHP_EOL;//$EOL = '';

        if(!empty($name)) $str .= $EOL."<".$name.">";

        if(!is_array($data)){
            $str .= $data;
        }else{
            foreach ($data as $key => $val){
                if(is_numeric($key))
                {
                    $str .= $this->array2string('', $val);
                }
                else
                {
                    if(is_array($val) && !empty($val))
                    {
                        $str .= $this->array2string($key, $val);
                    }
                    else
                    {
                        $str .= $EOL."<".$key.">";
                        $str .= (empty($val)) ? "" : $val;
                        $str .= "</".$key.">";
                    }
                }
            }
        }

        if(!empty($name))$str .= $EOL."</".$name.">";

        return $str;
    }

    /**
     * デバイス情報取得
     */
    private function getDeviceInfo(){
        if( !isset($_SERVER['HTTP_USER_AGENT']) ){
            return false;
        }
        $ua = $_SERVER['HTTP_USER_AGENT'];
        $browser_name = $browser_version = $webkit_version = $platform = NULL;
        $is_webkit = false;

        //Browser
        if(preg_match('/Edge/i', $ua)){

          $browser_name = 'Edge';

          if(preg_match('/Edge\/([0-9.]*)/', $ua, $match)){

            $browser_version = $match[1];
          }

        }elseif(preg_match('/(MSIE|Trident)/i', $ua)){

          $browser_name = 'IE';

          if(preg_match('/MSIE\s([0-9.]*)/', $ua, $match)){

            $browser_version = $match[1];

          }elseif(preg_match('/Trident\/7/', $ua, $match)){

            $browser_version = 11;
          }

        }elseif(preg_match('/Presto|OPR|OPiOS/i', $ua)){

          $browser_name = 'Opera';

          if(preg_match('/(Opera|OPR|OPiOS)\/([0-9.]*)/', $ua, $match)) $browser_version = $match[2];

        }elseif(preg_match('/Firefox/i', $ua)){

          $browser_name = 'Firefox';

          if(preg_match('/Firefox\/([0-9.]*)/', $ua, $match)) $browser_version = $match[1];

        }elseif(preg_match('/Chrome|CriOS/i', $ua)){

          $browser_name = 'Chrome';

          if(preg_match('/(Chrome|CriOS)\/([0-9.]*)/', $ua, $match)) $browser_version = $match[2];

        }elseif(preg_match('/Safari/i', $ua)){

          $browser_name = 'Safari';

          if(preg_match('/Version\/([0-9.]*)/', $ua, $match)) $browser_version = $match[1];
        }

        //Webkit
        if(preg_match('/AppleWebkit/i', $ua)){

          $is_webkit = true;

          if(preg_match('/AppleWebKit\/([0-9.]*)/', $ua, $match)) $webkit_version = $match[1];
        }

        //Platform
        if(preg_match('/ipod/i', $ua)){

          $platform = 'iPod';

        }elseif(preg_match('/iphone/i', $ua)){

          $platform = 'iPhone';

        }elseif(preg_match('/ipad/i', $ua)){

          $platform = 'iPad';

        }elseif(preg_match('/android/i', $ua)){

          $platform = 'Android';

        }elseif(preg_match('/windows phone/i', $ua)){

          $platform = 'Windows Phone';

        }elseif(preg_match('/linux/i', $ua)){

          $platform = 'Linux';

        }elseif(preg_match('/macintosh|mac os/i', $ua)) {

          $platform = 'Mac';

        }elseif(preg_match('/windows/i', $ua)){

          $platform = 'Windows';
        }

        return array(
            'ua' => $ua,
            'browser_name' => $browser_name,
            'browser_version' => intval($browser_version),
            'is_webkit' => $is_webkit,
            'webkit_version' => intval($webkit_version),
            'platform' => $platform
        );
    }
}

/**
 * HTTPヘッダー情報取得
 */
if (!function_exists('getallheaders')){
    function getallheaders()
    {
       $headers = array();
       foreach ($_SERVER as $name => $value)
       {
           if (substr($name, 0, 5) == 'HTTP_')
           {
               $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
           }
       }
       return $headers;
    }
}