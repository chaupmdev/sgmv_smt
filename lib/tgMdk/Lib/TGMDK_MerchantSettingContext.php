<?php
if (realpath($_SERVER["SCRIPT_FILENAME"]) == realpath(__FILE__)) die('Permission denied.');

if (!defined('MDK_LIB_DIR')) require_once('../3GPSMDK.php');

/**
 *
 * PHP版動的プロパティ設定クラス
 *
 * @category    Veritrans
 * @package     Lib
 * @copyright   VeriTrans Inc.
 * @access  public
 * @author VeriTrans Inc.
 */
class TGMDK_MerchantSettingContext {

    /** プロパティの "MERCHANT_CCID"（マーチャントCCID） を上書きする値を設定する変数 */
    private static $merchantCcid = null;

    /** プロパティの "MERCHANT_SECRET_KEY"（マーチャントパスワード） を上書きする値を設定する変数 */
    private static $merchantSecretKey = null;

    /** プロパティの "CONNECTION_TIMEOUT"（接続タイムアウト値） を上書きする値を設定する変数 */
    private static $connectionTimeout = null;

    /** プロパティの "READ_TIMEOUT"（読み取りタイムアウト値） を上書きする値を設定する変数 */
    private static $readTimeout = null;

    /** プロパティの "DUMMY_REQUEST"（ダミーモード） を上書きする値を設定する変数 */
    private static $dummyRequest = null;


    /**
     * マーチャントCCIDを設定する。
     *
     * @access  public
     * @static
     * @param String $merchantCcid マーチャントCCID
     */
    public static function set_merchant_ccid($merchantCcid) {
        self::$merchantCcid = $merchantCcid;
    }

    /**
     * マーチャントCCIDを取得する。
     *
     * @access  public
     * @static
     * @return String マーチャントCCID
     */
    public static function get_merchant_ccid() {
        return self::$merchantCcid;
    }

    /**
     * マーチャントパスワードを設定する。
     *
     * @access  public
     * @static
     * @param String $merchantSecretKey マーチャントパスワード
     */
    public static function set_merchant_secret_key($merchantSecretKey) {
        self::$merchantSecretKey = $merchantSecretKey;
    }

    /**
     * マーチャントパスワードを取得する。
     *
     * @access  public
     * @static
     * @return String マーチャントパスワード
     */
    public static function get_merchant_secret_key() {
        return self::$merchantSecretKey;
    }

    /**
     * 接続タイムアウト時間を設定する。
     *
     * @access  public
     * @static
     * @param String $timeout 接続タイムアウト時間
     */
    public static function set_timeout($timeout) {
        self::$connectionTimeout = $timeout;
    }

    /**
     * 接続タイムアウト時間を取得する。
     *
     * @access  public
     * @static
     * @return String 接続タイムアウト時間
     */
    public static function get_timeout() {
        return self::$connectionTimeout;
    }

    /**
     * 接続タイムアウト時間を設定する。
     *
     * @access  public
     * @static
     * @param String $connectionTimeout 接続タイムアウト時間
     */
    public static function set_connection_timeout($connectionTimeout) {
        self::$connectionTimeout = $connectionTimeout;
    }

    /**
     * 接続タイムアウト時間を取得する。
     *
     * @access  public
     * @static
     * @return String 接続タイムアウト時間
     */
    public static function get_connection_timeout() {
        return self::$connectionTimeout;
    }

    /**
     * 読み取りタイムアウト時間を設定する。
     *
     * @access  public
     * @static
     * @param String $readTimeout 読み取りタイムアウト時間
     */
    public static function set_read_timeout($readTimeout) {
        self::$readTimeout = $readTimeout;
    }

    /**
     * 読み取りタイムアウト時間を取得する。
     *
     * @access  public
     * @static
     * @return String 読み取りタイムアウト時間
     */
    public static function get_read_timeout() {
        return self::$readTimeout;
    }

    /**
     * ダミーリクエストフラグを設定する。
     *
     * @access  public
     * @static
     * @param String $dummyRequest ダミーリクエストフラグ
     */
    public static function set_dummy_request($dummyRequest) {
        self::$dummyRequest = $dummyRequest;
    }

    /**
     * ダミーリクエストフラグを取得する。
     *
     * @access  public
     * @static
     * @return String ダミーリクエストフラグ
     */
    public static function get_dummy_request() {
        return self::$dummyRequest;
    }
}
