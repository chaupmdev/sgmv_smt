<?php
/**
 * RCR クルーズリピータマスタ更新
 * @package    maintenance
 * @subpackage RCR
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
Sgmov_Lib::useServices('CruiseRepeater');
/**#@-*/

class Sgmov_Process_Rcr {

    /**
     * リクエストのユーザーIDのキー
     */
    const REQUEST_USER_ID_KEY = 'userId';

    /**
     * リクエストのパスワードのキー
     */
    const REQUEST_PASSWORD_KEY = 'passWord';

    /**
     * リクエストのデータのキー
     */
    const REQUEST_DATA_KEY = 'data';

    /**
     * ユーザーID
     */
    const USER_ID = '9009009';

    /**
     * パスワード
     */
    const PASSWORD = '0990990';

    /**
     * CSVの改行コード
     */
    const CSV_EOL = "\r\n";

    /**
     * CSVの文字コード
     */
    const CSV_ENCODING = 'SJIS-win';

    /**
     * PHPの文字コード
     */
    const PHP_ENCODING = 'UTF-8';

    /**
     * 項目数
     */
    const ITEM_COUNT = 9;

    /**
     * レコード区分
     */
    const RECORD_CATEGORY = 0;
    const RECORD_CATEGORY_NAME = 'レコード区分';

    /**
     * トランザクションID
     */
    const TRANSACTION_ID = 1;
    const TRANSACTION_ID_NAME = 'トランザクションID';

    /**
     * 処理区分
     */
    const PROCESSING_CATEGORY = 2;
    const PROCESSING_CATEGORY_NAME = '処理区分';

    /**
     * 電話番号
     */
    const TELEPHONE_NUMBER = 3;
    const TELEPHONE_NUMBER_NAME = '電話番号';

    /**
     * 郵便番号
     */
    const ZIP_CODE = 4;
    const ZIP_CODE_NAME = '郵便番号';

    /**
     * 住所
     */
    const ADDRESS = 5;
    const ADDRESS_NAME = '住所';

    /**
     * 名前
     */
    const NAME = 6;
    const NAME_NAME = '名前';

    /**
     * ツアーコード
     */
    const TRAVEL_CODE = 7;
    const TRAVEL_CODE_NAME = 'ツアーコード';

    /**
     * 顧客管理番号
     */
    const CLIENT_NUMBER = 8;
    const CLIENT_NUMBER_NAME = '顧客管理番号';

    /**
     * 処理区分：INSERT
     */
    const PROCESSING_CATEGORY_INSERT = '0';

    /**
     * 処理区分：UPDATE
     */
    const PROCESSING_CATEGORY_UPDATE = '1';

    /**
     * 送信ステータス：成功
     */
    const SEND_STATUS_SUCCESS = '0';

    /**
     * 送信ステータス：型や桁などの形式が不正な場合
     */
    const SEND_STATUS_ERROR = '1';

    /**
     * 送信ステータス：システム障害
     */
    const SEND_STATUS_SYSTEM_FAILURE = '2';

    /**
     * 送信ステータス：既に登録されている(INSERT時)
     */
    const SEND_STATUS_INSERT_ERROR = '3';

    /**
     * 送信ステータス：該当なし(UPDATE時)
     */
    const SEND_STATUS_UPDATE_ERROR = '4';

    /**
     * クルーズリピータサービス
     * @var Sgmov_Service_CruiseRepeater
     */
    public $_CruiseRepeater;

    /**
     * 処理を実行します。
     */
    public function execute() {

        if (!$this->rcrOutline()) {
            // 管理者宛にエラー通知メールを送るため
            throw new Exception;
        }
    }

    /**
     * メイン処理
     * @return boolean 成功した場合に TRUE を、失敗した場合に FALSE を返します。
     */
    public function rcrOutline() {

        try {
            // 認証チェック
            if (!$this->_checkAttestation()) {
                $this->_setError('1', '1', '認証チェックエラー');
                return FALSE;
            }

            // データ取得
            $data = $this->_getData();

            Sgmov_Component_Log::debug($data);

            $body = explode("\r\n", $data);

            // データ欠損チェック
            if (!$this->_checkNotDeficit($body)) {
                $this->_setError('1', '2', 'データ欠損チェックエラー');
                return FALSE;
            }

            // データ項目数チェック
            if (!$this->_checkCountItems($body)) {
                $this->_setError('1', '3', 'データ項目数チェックエラー');
                return FALSE;
            }

            $items = $this->_getItems($body);

            // データ項目チェック
            $result = $this->_checkItems($items);
            if (empty($result['success'])) {
                $exception_message = sprintf('データ項目チェックエラー 項目名[%1$s] 値[%2$s]', $result['name'], $result['value']);
                $this->_setError('1', '4', $exception_message);
                return FALSE;
            }

            // データ登録
            if (!$this->_entry($items)) {
                return FALSE;
            }

            // 成功時、トランザクションIDを返す
            $this->_setSuccess($items[self::TRANSACTION_ID]);

            return TRUE;
        } catch (Exception $e) {
            $this->_setError('2', $e->getCode(), $e->getMessage());
            return FALSE;
        }
    }

    /**
     * 認証チェック
     * @return boolean 成功した場合に TRUE を、失敗した場合に FALSE を返します。
     */
    private function _checkAttestation() {

        $user_id = filter_input(INPUT_POST, self::REQUEST_USER_ID_KEY);
        if ($user_id !== self::USER_ID) {
            return FALSE;
        }

        $password = filter_input(INPUT_POST, self::REQUEST_PASSWORD_KEY);
        if ($password !== self::PASSWORD) {
            return FALSE;
        }

        return TRUE;
    }

    /**
     * データ取得
     * @return string データ
     */
    private function _getData() {

        $data = filter_input(INPUT_POST, self::REQUEST_DATA_KEY);
        return mb_convert_encoding($data, self::PHP_ENCODING, self::CSV_ENCODING);
    }

    /**
     * データ欠損チェック
     * @param string $body
     * @return boolean 正常な場合に TRUE を、異常な場合に FALSE を返します。
     */
    private function _checkNotDeficit($body) {

        $count = count($body);
        if ($count < 3 || $count > 4) {
            Sgmov_Component_Log::debug(count($body));
            return FALSE;
        }

        if ($body[0] !== '"HEADER"') {
            Sgmov_Component_Log::debug($body[0]);
            return FALSE;
        }

        if ($body[2] !== '"TRAILER"') {
            Sgmov_Component_Log::debug($body[2]);
            return FALSE;
        }

        if (isset($body[3])
            && strlen($body[3]) > 0
        ) {
            Sgmov_Component_Log::debug($body[3]);
            return FALSE;
        }

        return TRUE;
    }

    /**
     * データ項目数チェック
     * @param array $body
     * @return boolean 正常な場合に TRUE を、異常な場合に FALSE を返します。
     */
    private function _checkCountItems($body) {

        $items = explode(',', $body[1]);

        if (count($items) < self::ITEM_COUNT) {
            Sgmov_Component_Log::debug(count($items));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * データ項目取得
     * @param array $body
     * @return array データ項目
     */
    private function _getItems($body) {

        $spl = new SplFileObject('php://memory', 'w+');

        $spl->fwrite($body[1]);

        $spl->rewind();

        $spl->setFlags(SplFileObject::READ_CSV);

        foreach ($spl as $items) {
            break;
        }

        foreach ($items as $key => $item) {
            $items[$key] = trim($this->unescapeIFcsv($item));
        }

        $items[self::TELEPHONE_NUMBER] = $this->removeNotNumeric($items[self::TELEPHONE_NUMBER]);
        $items[self::ZIP_CODE] = $this->removeNotNumeric($items[self::ZIP_CODE]);

        Sgmov_Component_Log::debug($items);

        return $items;
    }

    /**
     * データ項目チェック
     * @param array $items 項目
     * @return array 正常な場合に TRUE を、異常な場合に FALSE を返します。
     */
    private function _checkItems($items) {

        $record_category = $items[self::RECORD_CATEGORY];
        if ($record_category !== 'D') {
            Sgmov_Component_Log::debug($record_category);
            return array(
                'success' => FALSE,
                'name'    => self::RECORD_CATEGORY_NAME,
                'value'   => $record_category,
            );
        }

        $transaction_id = $items[self::TRANSACTION_ID];
        $transaction_id_length = strlen($transaction_id);
        if ($transaction_id_length === 0
            || $transaction_id_length > 18
            || !ctype_digit($transaction_id)
        ) {
            Sgmov_Component_Log::debug($transaction_id);
            return array(
                'success' => FALSE,
                'name'    => self::TRANSACTION_ID_NAME,
                'value'   => $transaction_id,
            );
        }

        $processing_category = $items[self::PROCESSING_CATEGORY];
        if (strlen($processing_category) !== 1) {
            Sgmov_Component_Log::debug($processing_category);
            return array(
                'success' => FALSE,
                'name'    => self::PROCESSING_CATEGORY_NAME,
                'value'   => $processing_category,
            );
        }
        switch ($processing_category) {
            case self::PROCESSING_CATEGORY_INSERT:
            case self::PROCESSING_CATEGORY_UPDATE:
                break;
            default:
                Sgmov_Component_Log::debug($processing_category);
                return array(
                    'success' => FALSE,
                    'name'    => self::PROCESSING_CATEGORY_NAME,
                    'value'   => $processing_category,
                );
        }

        $telephone_number = $items[self::TELEPHONE_NUMBER];
        $telephone_number_length = strlen($telephone_number);
        if ($telephone_number_length === 0
            || $telephone_number_length > 14
        ) {
            Sgmov_Component_Log::debug($telephone_number);
            return array(
                'success' => FALSE,
                'name'    => self::TELEPHONE_NUMBER_NAME,
                'value'   => $telephone_number,
            );
        }

        $zip_code = $items[self::ZIP_CODE];
        $zip_code_length = strlen($zip_code);
        if ($zip_code_length === 0
            || $zip_code_length > 7
            || !ctype_digit($zip_code)
        ) {
            Sgmov_Component_Log::debug($zip_code);
            return array(
                'success' => FALSE,
                'name'    => self::ZIP_CODE_NAME,
                'value'   => $zip_code,
            );
        }

        // 桁数はShift_JISのバイト数で確認する
        $address = mb_convert_encoding($items[self::ADDRESS], self::CSV_ENCODING, self::PHP_ENCODING);
        $address_length = strlen($address);
        if ($address_length === 0
            || $address_length > 136
        ) {
            Sgmov_Component_Log::debug($items[self::ADDRESS]);
            return array(
                'success' => FALSE,
                'name'    => self::ADDRESS_NAME,
                'value'   => $items[self::ADDRESS],
            );
        }

        // 桁数はShift_JISのバイト数で確認する
        $name = mb_convert_encoding($items[self::NAME], self::CSV_ENCODING, self::PHP_ENCODING);
        $name_length = strlen($name);
        if ($name_length === 0
            || $name_length > 68
        ) {
            Sgmov_Component_Log::debug($items[self::NAME]);
            return array(
                'success' => FALSE,
                'name'    => self::NAME_NAME,
                'value'   => $items[self::NAME],
            );
        }

        $travel_code = $items[self::TRAVEL_CODE];
        $travel_code_length = strlen($travel_code);
        if ($travel_code_length === 0
            || $travel_code_length > 4
            || !ctype_digit($travel_code)
        ) {
            Sgmov_Component_Log::debug($travel_code);
            return array(
                'success' => FALSE,
                'name'    => self::TRAVEL_CODE_NAME,
                'value'   => $travel_code,
            );
        }

        $client_number = $items[self::CLIENT_NUMBER];
        $client_number_length = strlen($client_number);
        if ($client_number_length === 0
            || $client_number_length > 16
            || !ctype_alnum($client_number)
        ) {
            Sgmov_Component_Log::debug($client_number);
            return array(
                'success' => FALSE,
                'name'    => self::CLIENT_NUMBER_NAME,
                'value'   => $client_number,
            );
        }

        return array(
            'success' => TRUE,
            'name'    => null,
            'value'   => null,
        );
    }

    /**
     * データ登録
     * @param array $items
     * @return boolean 成功した場合に TRUE を、失敗した場合に FALSE を返します。
     */
    private function _entry($items) {

        $data = array(
            'tel'       => $items[self::TELEPHONE_NUMBER],
            'zip'       => $items[self::ZIP_CODE],
            'address'   => $items[self::ADDRESS],
            'name'      => $items[self::NAME],
            'travel_cd' => $items[self::TRAVEL_CODE],
            'client_no' => $items[self::CLIENT_NUMBER],
        );

        $db = Sgmov_Component_DB::getAdmin();

        $this->_CruiseRepeater = new Sgmov_Service_CruiseRepeater();

        switch ($items[self::PROCESSING_CATEGORY]) {
            case self::PROCESSING_CATEGORY_INSERT:
                $success = $this->_CruiseRepeater->_insertCruiseRepeater($db, $data);
                if (!$success) {
                    $this->_setError('3', '5', '既に登録されています');
                    return FALSE;
                }
                break;
            case self::PROCESSING_CATEGORY_UPDATE:
                $success = $this->_CruiseRepeater->_updateCruiseRepeater($db, $data);
                if (!$success) {
                    $this->_setError('4', '6', '該当データが登録されていません');
                    return FALSE;
                }
                break;
            default:
                break;
        }

        return TRUE;
    }

    /**
     * エラー時のHTTPレスポンス作成
     * @param string $send_status
     * @param string $exception
     * @param string $exception_message
     */
    private function _setError($send_status, $exception, $exception_message) {
        $this->_setHttpResponse($send_status, null, $exception, $exception_message);
    }

    /**
     * 成功時のHTTPレスポンス作成
     * @param string $send_status
     * @param string $transaction_id
     */
    private function _setSuccess($transaction_id) {
        $this->_setHttpResponse('0', $transaction_id);
    }

    /**
     * HTTPレスポンス作成
     * @param string $send_status
     * @param string $transaction_id
     * @param string $exception
     * @param string $exception_message
     */
    private function _setHttpResponse($send_status, $transaction_id = null, $exception = null, $exception_message = null) {

        switch ($send_status) {
            case self::SEND_STATUS_SUCCESS:
                $exception = null;
                $exception_message = null;
                break;
            case self::SEND_STATUS_ERROR:
            case self::SEND_STATUS_SYSTEM_FAILURE:
            case self::SEND_STATUS_INSERT_ERROR:
            case self::SEND_STATUS_UPDATE_ERROR:
                $transaction_id = null;
                break;
            default:
                return;
        }

        $array = array(
            $send_status,
            $transaction_id,
            $exception,
            $exception_message,
        );

        $response = '"HEADER"' . self::CSV_EOL;

        foreach ($array as $item) {
            $response .= $this->escapeIFcsv($item) . ',';
        }

        $response = substr($response, 0, -1) . self::CSV_EOL . '"TRAILER"';

        echo mb_convert_encoding($response, self::CSV_ENCODING, self::PHP_ENCODING);
    }

    /**
     * 値に対して、IFcsv用のエスケープ処理を行う
     * @param string $str
     * @return string
     */
    public function escapeIFcsv($str) {

        $search = array(
            "\r\n",
            "\r",
            "\n",
            '\\',
            ",",
            '"',
        );

        $replace = array(
            "\n",
            "\n",
            '\r\n',
            '\\\\',
            "\\,",
            '\"',
        );

        return '"' . substr(str_replace($search, $replace, $str), 0, 4000) . '"';
    }

    /**
     * 値に対して、IFcsv用のエスケープ解除処理を行う
     * @param string $str
     * @return string
     */
    public function unescapeIFcsv($str) {

        $search = array(
            '\"',
            '\\,',
            '\\\\',
            '\r\n',
        );

        $replace = array(
            '"',
            ',',
            '\\',
            "\r\n",
        );

        return str_replace($search, $replace, $str);
    }

    /**
     * 値に対して、数字以外の文字を削除する
     * @param string $str
     * @return string
     */
    public function removeNotNumeric($str) {
        return preg_replace('{\D}u', '', $str);
    }
}