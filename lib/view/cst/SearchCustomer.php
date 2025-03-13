<?php
/**
 * @package    ClassDefFile
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useAllComponents();
Sgmov_Lib::useView('Public');
Sgmov_Lib::useServices(array('ComiketKokyaku'));
/**#@-*/

/**
 * 顧客コードから顧客情報を検索して返します。
 * @package    View
 * @subpackage CST
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Cst_SearchCustomer extends Sgmov_View_Public {

//    private $baseUrl = 'http://172.16.101.19/sgmv/cm0402/index';
    private $baseUrl = '';
    //http://local.mytest.jp:8080/
//    private $baseUrl = 'http://local.mytest.jp:8080/test.php';
    /**
     * ツアーサービス
     * @var Sgmov_Service_Travel
     */
//    public $_TravelService;

    public $_ComiketKokyaku;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $port = Sgmov_Component_Config::getWsPort();
        if(!empty($port) && $port != '80') {
            $port = ":{$port}";
        } else {
            $port = '';
        }
//        $this->baseUrl = Sgmov_Component_Config::getWsSearchCustomerProtocol() . "://" .  Sgmov_Component_Config::getWsHost() . $port . Sgmov_Component_Config::getWsSearchCustomerPath();
//        $this->_TravelService = new Sgmov_Service_Travel();
        $this->baseUrl = "https://sgmvweb.sagawa-mov.co.jp/sgmv/cm0402/index";
        $this->_ComiketKokyaku = new Sgmov_Service_ComiketKokyaku();
    }

    /**
     * 処理を実行します。
     *
     */
    public function executeInner() {


//        return array(
//            "kkyKokyakuNm" => "テスト顧客名", // 顧客名
//            "kkyKokyakuYubinNo" => "6178588", // 郵便番号 ハイフンなし
//            "kkyJisChikuTdfknNm" => "京都府", // 顧客住所都道府県
//            "kkyJisChikuTdfknCd" => "26", // 顧客住所都道府県コード
//            "kkyJisChikuSkgntysnNm" => "向日市森本町戌亥5番地の3", // 顧客住所市区町村
//            "kkyJisChikuOaztsushoNm" => "戌亥5番地の3", // 顧客住所大字
//            "kkyJisChikuAzchomeNm" => "",//"テスト顧客住所字丁目", // 顧客住所字丁目
//            "kkyKokyakuTelno" => "08011112222", // 顧客電話番号
//        );

        $featureId            = filter_input(INPUT_POST, 'featureId');
        $fromGamenId          = filter_input(INPUT_POST, 'id');
        $ticket               = filter_input(INPUT_POST, 'ticket');
        $comiketCustomerCd  = filter_input(INPUT_POST, 'comiket_customer_cd');
//        $travel_agency_cd_sel = filter_input(INPUT_POST, 'travel_agency_cd_sel');
//Sgmov_Component_Log::debug("################### 101 customerCd =$comiketCustomerCd");
//Sgmov_Component_Log::debug($_POST);

        // チケット確認
        $this->_checkSession($featureId, $fromGamenId, $ticket);

        try {
            if (empty($comiketCustomerCd)) {
                throw new Exception;
            }
            // DB接続
//            $db = Sgmov_Component_DB::getPublic();
//Sgmov_Component_Log::debug("################### 1010-1 : " . $comiketCustomerCd);
//            $comiketKokyakuList = $this->_ComiketKokyaku->fetchComiketKokyakuByCustomerCd($db, $comiketCustomerCd);
//            if(empty($comiketKokyakuList)) {
//                throw new Exception;
//            }

//            $travel = $this->_TravelService->fetchTravel($db, array('travel_agency_id' => $travel_agency_cd_sel));
            $baseUrl = $this->baseUrl;
            $result = array();
Sgmov_Component_Log::debug("################### 1010-2");
            // 日をまたがった場合、顧客データをとれない可能性があるため念のため２回業務側へアクセスする
            for($i=0; $i< 2; $i++) {
                $sysDate = date("Ymd");

                // 顧客コードを配列化
                $arrKokyakuCd = str_split($comiketCustomerCd);

                // 配列数が11未満ならエクセプションを投げて終了
                if (count($arrKokyakuCd) < 11) {
                    throw new Exception;
                }

                // 掛け算数値配列（固定らしいのでベタ書き）
                $intCheck = array(
                    0 => 4,
                    1 => 3,
                    2 => 2,
                    3 => 9,
                    4 => 8,
                    5 => 7,
                    6 => 6,
                    7 => 5,
                    8 => 4,
                    9 => 3,
                    10 => 2,
                );

                $total = 0;
                for ($i = 0; $i < 11; $i++) {
                    $total += $arrKokyakuCd[$i] * $intCheck[$i];
                }

                $amari = 11 - ($total % 11);
                $amariLen = count(str_split($amari));
                $kokyakuCdCheckDigit = substr($amari, $amariLen - 1);
//Sgmov_Component_Log::debug("comiketCustomerCd:" . $comiketCustomerCd);
//Sgmov_Component_Log::debug("kokyakuCdCheckDigit:" . $kokyakuCdCheckDigit);
//Sgmov_Component_Log::debug("kokyakuCd:" . $comiketCustomerCd . $kokyakuCdCheckDigit);
                $orgSysDateComiketCustomerCd = $sysDate . "_" . $comiketCustomerCd . $kokyakuCdCheckDigit;
                $hashSysDateComiketCustomerCd = hash('sha256', $orgSysDateComiketCustomerCd);

                $data = array(
                    "cid" => $comiketCustomerCd . $kokyakuCdCheckDigit,
                    "cidenc" => $hashSysDateComiketCustomerCd,
                    "kkbn" => "1", // SGW顧客コード
                );
                $data = http_build_query($data, "", "&");

                //header
                $header = array(
                    "Content-Type: application/x-www-form-urlencoded; charset=UTF-8",
    //                "Content-Type: html/text",
                    "Content-Length: ".strlen($data)
                );
                $context = array(
                    "http" => array(
                        "method"  => "POST",
                        "header"  => implode("\r\n", $header),
                        "content" => $data
                    )
                );
                $response = file_get_contents($baseUrl, false, stream_context_create($context));

                $result = json_decode($response, true);

Sgmov_Component_Log::debug("################### 1011");
Sgmov_Component_Log::debug($result);

                if(!empty($result)) {
                    break;
                }
            }

//Sgmov_Component_Log::debug("################### 101");
//Sgmov_Component_Log::debug(stream_context_create($context));
//Sgmov_Component_Log::debug($orgSysDateComiketCustomerCd);
//Sgmov_Component_Log::debug($hashSysDateComiketCustomerCd);
//Sgmov_Component_Log::debug($result);
        } catch (exception $e) {
//            throw $e;
        }
//        return $travel;

        $returnResult = array(
            "kkyKokyakuNm" => @$result['kkyKokyakuNm'], // 顧客名
            "kkyKokyakuYubinNo" => @$result['kkyKokyakuYubinNo'], // 郵便番号 ハイフンなし
            "kkyJisChikuTdfknNm" => @$result['kkyJisChikuTdfknNm'], // 顧客住所都道府県
            "kkyJisChikuTdfknCd" => @$result['kkyJisChikuTdfknCd'], // 顧客住所都道府県コード
            "kkyJisChikuSkgntysnNm" => @$result['kkyJisChikuSkgntysnNm'], // 顧客住所市区町村
            "kkyJisChikuOaztsushoNm" => @$result['kkyJisChikuOaztsushoNm'], // 顧客住所大字
            "kkyJisChikuAzchomeNm" => @$result['kkyJisChikuAzchomeNm'], // 顧客住所字丁目
            "kkyKokyakuTelno" => @$result['kkyKokyakuTelno'], // 顧客電話番号
            "kkyJushoBanchi" =>  @$result['kkyJushoBanchi'], // 住所番地
            "kkyJushoGo" =>  @$result['kkyJushoGo'], // 住所号
            "kkyJushoSonota" =>  @$result['kkyJushoSonota'], // 住所その他
        );

        $isCustomerInfoFromCode = FALSE;
        foreach($returnResult as $key => $val) {
            if(!empty($val)) {
                $isCustomerInfoFromCode = TRUE;
//                break;
            }
            $result[$key] = @(empty($result[$key]) && $result[$key] != "0") ? "" : $result[$key];
        }
        $test = "";
        return array(
            "kkyKokyakuNm" => @$result['kkyKokyakuNm'] . $test, // 顧客名
            "kkyKokyakuYubinNo" => @$result['kkyKokyakuYubinNo'], // 郵便番号 ハイフンなし
            "kkyJisChikuTdfknNm" => @$result['kkyJisChikuTdfknNm'], // 顧客住所都道府県
            "kkyJisChikuTdfknCd" => @$result['kkyJisChikuTdfknCd'], // 顧客住所都道府県コード
            "kkyJisChikuSkgntysnNm" => @$result['kkyJisChikuSkgntysnNm'] . $test, // 顧客住所市区町村
            "kkyJisChikuOaztsushoNm" => @$result['kkyJisChikuOaztsushoNm'] . $test, // 顧客住所大字
            "kkyJisChikuAzchomeNm" => @$result['kkyJisChikuAzchomeNm'] . $test, // 顧客住所字丁目
            "kkyKokyakuTelno" => @$result['kkyKokyakuTelno'], // 顧客電話番号
            "kkyJushoBanchi" =>  @$result['kkyJushoBanchi'] . $test, // 住所番地
            "kkyJushoGo" =>  @$result['kkyJushoGo'] . $test, // 住所号
            "kkyJushoSonota" =>  @$result['kkyJushoSonota'] . $test, // 住所その他
            "isGetCustomerInfo" => $isCustomerInfoFromCode,
        );


    }

    /**
     * チケットの確認を行います。
     * TODO ybn/SearchAddressと同記述あり
     */
    public function _checkSession($featureId, $fromGamenId, $ticket) {
        // セッション
        $session = Sgmov_Component_Session::get();

        // チケットの確認
        if (!isset($_SESSION[Sgmov_Component_Session::_KEY_TICKETS])) {
            Sgmov_Component_Log::warning('【ツアー会社検索 不正使用】チケットが存在していません。');
            header('HTTP/1.0 404 Not Found');
            exit;
        }

        $tickets = &$_SESSION[Sgmov_Component_Session::_KEY_TICKETS];
        if (!isset($tickets[$featureId]) || $tickets[$featureId] !== $fromGamenId . $ticket) {
            Sgmov_Component_Log::warning('【ツアー会社検索 不正使用】チケットが不正です。　'.$tickets[$featureId].' <=> '.$fromGamenId . $ticket);
            header('HTTP/1.0 404 Not Found');
            exit;
        } else {
            Sgmov_Component_Log::debug('ツアー会社検索実行 機能ID=>'.$tickets[$featureId].' <=> '.$fromGamenId . $ticket);
        }
    }
}