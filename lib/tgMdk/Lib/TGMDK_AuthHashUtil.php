<?php
if (realpath($_SERVER["SCRIPT_FILENAME"]) == realpath(__FILE__)) die('Permission denied.');

if (!defined('MDK_LIB_DIR')) require_once('../3GPSMDK.php');

/**
 * パラメータ改竄チェック関連ユーティリティクラス。
 *
 * @category    Veritrans
 * @package     Lib
 * @copyright   VeriTrans Inc.
 * @access  public
 * @author VeriTrans Inc.
 * @since 1.6.2
 */
class TGMDK_AuthHashUtil {

    /**
     * Bese64デコードを行う。
     *
     * @param $data デコードする文字列
     * @return デコードを行った結果の文字列
     */
    private static function base64Dec($data) {
        $data = str_replace("*", "=", $data);
        $data = str_replace("-", "/", $data);
        $data = str_replace("_", "+", $data);
        $data = str_replace(" ", "+", $data);
        $data = base64_decode($data);
        return $data;
    }

    /**
     * 指定した値に格納された先頭の値を取得する。<br>
     * ・指定した値が配列の場合は、配列の先頭の値を取得する。<br>
     * ・指定した値が配列ではない場合は、指定した値をそのまま返す。<br>
     * ・指定した値が空配列である場合は、NULLを取得する。<br>
     *
     * @param $value 配列もしくは値
     * @return 先頭の値
     */
    private static function getFirstValue($value) {

        if (!is_array($value)) {
            return $value;

        } else if (empty($value)) {
            return NULL;
        }
        return reset($value);
    }

    /**
     * ハッシュ値を算出する。
     *
     * @param request_params リクエストパラメータ
     * @param auth_params パラメータ順序
     * @param merchant_cc_id マーチャントCCID
     * @param merchant_pw マーチャントパスワード
     * @param charset 文字コード
     * @return ハッシュ値
     */
    private static function createAuthHashInfo($request_params, $auth_params, $merchant_cc_id, $merchant_pw, $charset) {

        if (!isset($merchant_cc_id) || !isset($merchant_pw)) {
            return NULL;
        }

        // デコードする。
        $decoded_params = TGMDK_AuthHashUtil::base64Dec($auth_params);

        // パラメータを連結する。
        $str_params = $merchant_cc_id;
        $keys = explode(',', $decoded_params);
        foreach ($keys as $key) {
            $value = TGMDK_AuthHashUtil::getFirstValue($request_params[$key]);
            if (!isset($value)) {
                return NULL;
            }
            $str_params = $str_params.$value;
        }
        $str_params = $str_params.$merchant_pw;

        // 連結したパラメータ値から指定されたアルゴリズムのダイジェストを生成する。
        $enc_params = mb_convert_encoding($str_params, $charset, 'UTF-8');
        return hash('sha256', $enc_params, false);
    }

    /**
     * リクエストパラメータ内のパラメータ順序（authParams）に従ってハッシュ値を計算し、
     * リクエストパラメータ内の改竄チェック用ハッシュ値（vAuthInfo）と比較する。<br>
     * リクエストパラメータにパラメータ順序と改竄チェック用ハッシュ値が含まれていない場合、
     * 改竄されているとみなす。<br>
     * <br>
     *
     * @param request_params リクエストパラメータ
     * @param merchant_cc_id マーチャントCCID
     * @param merchant_pw マーチャントパスワード
     * @param charset 文字コード
     * @return true 一致 or false 不一致（改竄されている）
     */
    public static function checkAuthHash($request_params, $merchant_cc_id, $merchant_pw, $charset) {

        try {
            if (!is_array($request_params)) {
                return false;
            }

            // パラメータ順序の取得
            $auth_params = TGMDK_AuthHashUtil::getFirstValue($request_params['authParams']);
            // 改ざんチェック用ハッシュ値の取得
            $v_auth_info = TGMDK_AuthHashUtil::getFirstValue($request_params['vAuthInfo']);
            if(!isset($auth_params) || !isset($v_auth_info)){
                return false;
            }

            // ハッシュ値の算出
            $hash_value = TGMDK_AuthHashUtil::createAuthHashInfo(
                    $request_params, $auth_params, $merchant_cc_id, $merchant_pw, $charset);

            // 改竄のチェック
            if (!isset($hash_value) || $hash_value != $v_auth_info) {
                return false;
            }
            return true;

        } catch (Exception $e) {
            TGMDK_Logger::getInstance()->debug('TGMDK_AuthHashUtil::checkAuthHash ==> Exception:' . $e->getMessage());
            return false;
        }
    }
}
?>
