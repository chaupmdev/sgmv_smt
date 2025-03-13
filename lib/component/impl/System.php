<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useComponents(array('SideEffect'));
/**#@-*/

 /**
 * {@link Sgmov_Component_System} の実装クラスです。
 *
 * [注意事項(共通)]
 *
 * エラーハンドリングでエラーが例外に変換されることを
 * 前提として設計されています。
 *
 * テストのため全て public で宣言します。
 * 名前がアンダーバーで始まるものは使用しないでください。
 *
 * テストでモックを使用するものや、実装を含めると複雑になるものは
 * 実装が分離されています。
 *
 * @package Component_Impl
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Component_Impl_System
{
    /**
     * システムログファイル名
     */
//    const _SYS_LOG_FNAME = 'system.log';

    /**
     * ログの日時フォーマット
     */
    const _LOG_TIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * エラー処理が開始されているかどうか
     * @var boolean
     */
    public $_errorHandling = FALSE;

    /**
     * ログファイルのパス
     * @var string
     */
    public $_logFilePath;

    /**
     * デストラクターです。
     *
     * エラー処理が開始されている場合はエラー処理を終了します。
     */
    public function __destruct()
    {
        $this->_stopErrorHandling();
    }

    /**
     * {@link Sgmov_Component_System::startErrorHandling()} の実装です。
     */
    public function startErrorHandling()
    {
        if ($this->_errorHandling === FALSE) {
            $this->_errorHandling = TRUE;
            set_error_handler(array($this, "_errorHandlerCallback"));
            set_exception_handler(array($this, "_exceptionHandlerCallback"));
        }
    }

    /**
     * エラー処理が開始されている場合は終了します。
     */
    public function _stopErrorHandling()
    {
        if ($this->_errorHandling) {
            $this->_errorHandling = FALSE;
            restore_error_handler();
            restore_exception_handler();
        }
    }

    /**
     * エラーを例外に変換するコールバック関数です。
     *
     * errno が error_reporting に含まれている場合に例外を投げます。
     * 含まれていない場合は何も行われません。
     *
     * "@"演算子が使用されている場合は error_reporting 値が0となるため、
     * エラーのレベルに関係なく処理は何も行われません。
     *
     * @param int $errno 発生させるエラーのレベルを整数で格納
     * @param string $errstr エラーメッセージを文字列で格納
     * @param string $errfile エラーが発生したファイルの名前を文字列で格納
     * @param int $errline エラーが発生した行番号を整数で格納
     * @return boolean デフォルトハンドラーに処理を委譲する場合にFALSEを返します。
     */
    public function _errorHandlerCallback($errno, $errstr, $errfile, $errline)
    {
        if ((error_reporting() & $errno) === $errno) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        } else {
            Sgmov_Component_Log::debug($errstr);
            Sgmov_Component_Log::debug($errfile);
            Sgmov_Component_Log::debug($errline);
        }
        return TRUE;
    }

    /**
     * 発生した例外が処理されなかった場合に
     * 現在のスクリプトをシステムエラーとして終了するコールバック関数です。
     *
     * @param Exception $e 処理されなかった例外
     */
    public function _exceptionHandlerCallback($e)
    {
        $this->log("[未処理例外]", $e);
        $this->systemErrorExit();
    }

    /**
     * {@link Sgmov_Component_System::isErrorHandling()} の実装です。
     */
    public function isErrorHandling()
    {
        return $this->_errorHandling;
    }

    /**
     * {@link Sgmov_Component_System::log()} の実装です。
     *
     * @param string $message 出力するメッセージ
     * @param exception $cause [optional] 原因となった例外
     */
    public function log($message, $cause = NULL)
    {
        try {
            $date = new DateTime();
            $datetime = $date->format(self::_LOG_TIME_FORMAT);
            if (isset($cause)) {
                $causeString = "\n" . $cause->__toString();
            } else {
                $causeString = '';
            }
            error_log("{$datetime} {$message}{$causeString}\n", 3, $this->_getLogFilePath());
        }
        catch (exception $e) {
            $this->systemErrorExit();
        }
    }

    /**
     * ログファイルのパスを取得します。
     *
     * 既に取得している場合はその値を返します。
     * @return ログファイルパス
     */
    public function _getLogFilePath()
    {
        if (!isset($this->_logFilePath)) {
            $date = new DateTime();
            $this->_logFilePath = Sgmov_Lib::getLogDir() . '/system' . $date->format('Ymd') . '.log';
            //$this->_logFilePath = Sgmov_Lib::getLogDir() . '/' . self::_SYS_LOG_FNAME;
        }
        return $this->_logFilePath;
    }

    /**
     * {@link Sgmov_Component_System::systemErrorExit()} の実装です。
     */
    public function systemErrorExit()
    {
        Sgmov_Component_SideEffect::callHeader('HTTP/1.0 500 Internal Server Error');
        Sgmov_Component_SideEffect::callExit();
    }
}