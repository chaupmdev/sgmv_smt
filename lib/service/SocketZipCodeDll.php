<?php
/**
 * @package    ClassDefFile
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
/**#@-*/

/**
 * ソケット通信で郵便番号DLLを検索し、郵便番号・住所情報を扱います。
 *
 * @package Service
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_SocketZipCodeDll {

    const HOST_NAME = '10.60.58.64';
    //const HOST_NAME = '10.60.59.253';
    const PORT      = '10000';
    const TIME_OUT  = 30;

    private static $zips = array();

    /**
     * DLLから住所情報を取得し、返します。
     *
     * @param string $send JSON
     * @return array 住所情報
     */
    public function search($send) {
        $errno = null;
        $errstr = null;
        $socket = @fsockopen(self::HOST_NAME, self::PORT, $errno, $errstr, self::TIME_OUT);

        if (!$socket) {
            return false;
        }

        socket_set_timeout($socket, 3);
        $send_json = json_encode($send);
        fputs($socket, $send_json);
        $receive_json = '';
        while (!feof($socket)) {
            $receive_json .= fgets($socket, 1024);
        }

        if (empty($receive_json)) {
            fclose($socket);
            return false;
        }

        $receive = (array)json_decode($receive_json);
        fclose($socket);
        $socket = null;
        unset($socket);

        return $receive;
    }

    /**
     * 住所をキーに、DLLから住所情報を取得し、返します。
     *
     * @param string $address 住所
     * @return array 住所情報
     */
    public function searchByAddress($address, $zip = null) {
        $date = new DateTime();
        $send = array(
            'Method'     => 'SearchByAddress',
            'szAddress'  => $address,
            'dtBaseDate' => $date->format('Y-m-d'),
        );
        if (!empty($zip)) {
            $send['szZipCode'] = $zip;
        }
        $send['flags'] = '1';
        return $this->search($send);
    }

    /**
     * 郵便番号をキーに、DLLから住所情報を取得し、返します。
     *
     * @param string $zip 郵便番号
     * @return array 住所情報
     */
    public function searchByZipCode($zip) {
        if (isset(self::$zips[$zip])) {
            return self::$zips[$zip];
        }
        $date = new DateTime();
        $send = array(
            'Method'     => 'SearchByZipCode',
            'szZipCode'  => $zip,
            'dtBaseDate' => $date->format('Y-m-d'),
            'flags'      => '1',
        );
        self::$zips[$zip] = $this->search($send);
        return self::$zips[$zip];
    }
}