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
 * HTTPS通信で郵便番号DLLを検索し、郵便番号・住所情報を扱います。
 *
 * @package Service
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_HttpsZipCodeDll {
    
        private $baseUrl = '';
    
	/**
	 * 
	 * @param type $input
	 */
	public function _execYubin7($input) {
            
            $baseUrl = $this->baseUrl = Sgmov_Component_Config::getHttpsZipCodeDllUrl();
            
//        $baseUrl = 'https://carryasp.media-tec.jp/yubin7/yubin7.php';
//		$baseUrl = 'http://220.105.183.9:8085/yubin7.php';
//		$baseUrl = 'https://smt-sv015/yubin7/yubin7.php';
		$query = array(
			'dataType' => 'yubin7',
			'szZipCode' => @$input["szZipCode"],
			'szAddress' => @$input["szAddress"],
			'szName' => @$input["szName"],
			'szTel' => @$input["szTel"],
			'dtBaseDate' => @$input["dtBaseDate"],
			'flags' => '1',
		);
		$options = array();
		$options['ssl']['verify_peer']=FALSE;
		$options['ssl']['verify_peer_name']=FALSE;
		$response = file_get_contents($baseUrl . "?" . http_build_query($query), false, stream_context_create($options));

		// 結果はjson形式で返されるので
		$result = json_decode($response, true);
		
		return $result;

//		return array("Result" => "true", "ShopCode" => "100", "ShopCheckCode" => "101");
	}
    
//
//    //const HOST_NAME = '10.57.50.41'; // 客先テスト環境
//    const HOST_NAME = '172.16.101.30'; // 社内テスト環境
//    const PORT      = '10000';
//    const TIME_OUT  = 30;
//
//    private static $zips = array();
//
//    /**
//     * DLLから住所情報を取得し、返します。
//     *
//     * @param string $send JSON
//     * @return array 住所情報
//     */
//    public function search($send) {
//        $errno = null;
//        $errstr = null;
//        $socket = @fsockopen(self::HOST_NAME, self::PORT, $errno, $errstr, self::TIME_OUT);
//
//        if (!$socket) {
//            return false;
//        }
//
//        socket_set_timeout($socket, 3);
//        $send_json = json_encode($send);
//        fputs($socket, $send_json);
//        $receive_json = '';
//        while (!feof($socket)) {
//            $receive_json .= fgets($socket, 1024);
//        }
//
//        if (empty($receive_json)) {
//            fclose($socket);
//            return false;
//        }
//
//        $receive = (array)json_decode($receive_json);
//        fclose($socket);
//        $socket = null;
//        unset($socket);
//
//        return $receive;
//    }
//
//    /**
//     * 住所をキーに、DLLから住所情報を取得し、返します。
//     *
//     * @param string $address 住所
//     * @return array 住所情報
//     */
//    public function searchByAddress($address, $zip = null) {
//        $date = new DateTime();
//        $send = array(
//            'Method'     => 'SearchByAddress',
//            'szAddress'  => $address,
//            'dtBaseDate' => $date->format('Y-m-d'),
//        );
//        if (!empty($zip)) {
//            $send['szZipCode'] = $zip;
//        }
//        $send['flags'] = '1';
//        return $this->search($send);
//    }
//
//    /**
//     * 郵便番号をキーに、DLLから住所情報を取得し、返します。
//     *
//     * @param string $zip 郵便番号
//     * @return array 住所情報
//     */
//    public function searchByZipCode($zip) {
//        if (isset(self::$zips[$zip])) {
//            return self::$zips[$zip];
//        }
//        $date = new DateTime();
//        $send = array(
//            'Method'     => 'SearchByZipCode',
//            'szZipCode'  => $zip,
//            'dtBaseDate' => $date->format('Y-m-d'),
//            'flags'      => '1',
//        );
//        self::$zips[$zip] = $this->search($send);
//        return self::$zips[$zip];
//    }
}