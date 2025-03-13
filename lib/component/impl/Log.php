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
Sgmov_Lib::useComponents(array('System', 'Config', 'Mail'));
/**#@-*/

/**
 * {@link Sgmov_Component_Log} の実装クラスです。
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
class Sgmov_Component_Impl_Log {

    /**
     * ログレベル：なし
     */
    const _LOG_LEVEL_NONE = 0;

    /**
     * ログレベル：エラー
     */
    const _LOG_LEVEL_ERR = 3;

    /**
     * ログレベル：警告
     */
    const _LOG_LEVEL_WARNING = 4;

    /**
     * ログレベル：情報
     */
    const _LOG_LEVEL_INFO = 6;

    /**
     * ログレベル：デバッグ
     */
    const _LOG_LEVEL_DEBUG = 7;

    /**
     * ログの日時フォーマット
     */
    const _LOG_TIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * このクラスが初期化されているかどうかを示すフラグ
     * @var boolean
     */
    public $_initialized = FALSE;

    /**
     * ファイルが開いているかどうかを示すフラグ
     * @var boolean
     */
    public $_opened = FALSE;

    /**
     * ログ出力のしきい値: このレベル以下の値のログだけが出力されます。
     * @var integer
     */
    public $_level;

    /**
     * ログファイルのパス
     * @var string
     */
    public $_fpath;

    /**
     * ログファイルのファイルハンドラ
     * @var resource
     */
    public $_fp = FALSE;

    /**
     * ログファイルのアクセス権限(8進数)
     * @var integer
     */
    public $_mode = 0777;

    /**
     * ログディレクトリのアクセス権限(8進数)
     * @var integer
     */
    public $_dirmode = 0777;

    /**
     * デストラクターです。
     *
     * ログファイルが開いている場合閉じます。
     */
    public function __destruct() {
        if ($this->_opened) {
            @fclose($this->_fp);
        }
    }

    /**
     * {@link Sgmov_Component_Log::err()} の実装です。
     *
     * @param string $message 出力するメッセージ
     * @param exception $cause [optional] 原因となった例外
     */
    public function err($message, $cause = NULL) {
        $this->_initialize();
        $this->_log(self::_LOG_LEVEL_ERR, $message, $cause);
    }

    /**
     * {@link Sgmov_Component_Log::warning()} の実装です。
     *
     * @param string $message 出力するメッセージ
     * @param exception $cause [optional] 原因となった例外
     */
    public function warning($message, $cause = NULL) {
        $this->_initialize();
        $this->_log(self::_LOG_LEVEL_WARNING, $message, $cause);
    }

    /**
     * {@link Sgmov_Component_Log::info()} の実装です。
     *
     * @param string $message 出力するメッセージ
     * @param exception $cause [optional] 原因となった例外
     */
    public function info($message, $cause = NULL) {
        $this->_initialize();
        $this->_log(self::_LOG_LEVEL_INFO, $message, $cause);
    }

    /**
     * {@link Sgmov_Component_Log::debug()} の実装です。
     *
     * @param string $message 出力するメッセージ
     * @param exception $cause [optional] 原因となった例外
     */
    public function debug($message, $cause = NULL) {
        $this->_initialize();
        $this->_log(self::_LOG_LEVEL_DEBUG, $message, $cause);
    }

    /**
     * {@link Sgmov_Component_Log::errWithMail()} の実装です。
     *
     * @param string $title タイトル
     * @param string $summary 概要
     * @param string $message 出力するメッセージ
     * @param exception $cause [optional] 原因となった例外
     */
    public function errWithMail($title, $summary, $message, $cause = NULL) {
        $this->_initialize();
        // ログ出力
        $logMessage = "{$title}:{$summary}\n{$message}";
        $this->_log(self::_LOG_LEVEL_ERR, $logMessage, $cause);

        // メール送信
        $to = Sgmov_Component_Config::getLogMailTo();
        if ($to !== '') {
            $from = Sgmov_Component_Config::getLogMailFrom();
            $this->_sendErrorMail($from, $to, $title, $summary);
        } else {
            $this->warning('送信先メールアドレスが設定されていないため、通知メールは送信されません。');
        }
    }

    /**
     * {@link Sgmov_Component_Log::isDebug()} の実装です。
     * @return デバッグレベルのログが出力される場合は TRUE を、
     * 出力されない場合は FALSE を返します。
     */
    public function isDebug() {
        $this->_initialize();
        if (self::_LOG_LEVEL_DEBUG <= $this->_level) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * クラスを初期化します。
     *
     * レベルとファイルパスを設定ファイルから読み込んで
     * 設定します。
     */
    public function _initialize() {
        if (!$this->_initialized) {
            // ファイル名には日付を使用します
            $date = new DateTime();
            $this->_fpath = Sgmov_Lib::getLogDir() . '/' . $date->format('Ymd') . '.log';
            $this->_level = $this->_getLevelValueFromString(Sgmov_Component_Config::getLogLevel());
            $this->_initialized = TRUE;
        }
    }

    /**
     * ログを出力します。
     *
     * レベルの値がしきい値より大きい場合はログを出力しません。
     * ログの出力に失敗した場合は、システムログにログを出力します。
     *
     * 出力に失敗した場合は、システムログに出力します。
     * このメソッドでは例外は発生しません。
     *
     * @param integer $level ログのレベル
     * @param string $message 出力するメッセージ
     * @param exception $cause [optional] 原因となった例外
     */
    public function _log($level, $message, $cause = NULL) {
        if ($level > $this->_level) {
            return;
        }

        // 日時
        $datetime = date(self::_LOG_TIME_FORMAT);
        // ログレベル
        $levelString = $this->_getLevelOutputString($level);
        // ヘッダー
        $header = $this->_createLogHeader();
        // 例外
        $causeString = '';
        if (isset($cause)) {
            $causeString = "\n" . $cause->__toString();
        }
        // 配列やオブジェクトの場合、整形する
        if (!is_scalar($message)) {
            $message = var_export($message, true);
        }

        // ログ
        $log = "{$datetime} [{$levelString}] {$header} {$message}{$causeString}\n";

        try {
            $this->_open();
            fwrite($this->_fp, $log);
        } catch (exception $e) {
            Sgmov_Component_System::log('[ログ出力失敗] ', $e);
            Sgmov_Component_System::log("[ログ出力に失敗したメッセージ] ----\n" . $log);
        }
    }

    /**
     * エラー通知メールを送信します。
     *
     * 送信に失敗した場合は、システムログに出力します。
     * このメソッドでは例外は発生しません。
     *
     * @param string $from メッセージ
     * @param string $to メッセージ
     * @param string $title タイトル
     * @param string $summary 概要
     */
    public function _sendErrorMail($from, $to, $title, $summary) {
        $subject = "【ＳＧムービングウェブサイト】{$title}";

        $body = "{$title}\n";
        $body .= "--------------------------------------------------\n";
        $body .= date("Y/m/d H:i:s") . "\n";
        $body .= "\n";
        $body .= "SERVER:ホスティングサーバ \n";
        $body .= "\n";
        $body .= "LogFile:{$this->_fpath}\n";
        $body .= "\n";
        $body .= "{$summary}\n";
        $body .= "--------------------------------------------------\n";

        try {
            Sgmov_Component_Mail::sendMail($from, $to, $subject, $body);
        } catch (exception $e) {
            Sgmov_Component_System::log('[エラー通知メール送信失敗] ', $e);
        }
    }

    /**
     * レベル値に対応する出力文字列を取得します。
     *
     * レベル値が不正な値の場合は'不明'を返します。
     *
     * @param integer $level レベル値
     * @return レベル値に対応する文字列
     */
    public function _getLevelOutputString($level) {
        $levelString = '';
        if ($level === self::_LOG_LEVEL_ERR) {
            $levelString = 'エラー';
        } else if ($level === self::_LOG_LEVEL_WARNING) {
            $levelString = '警告';
        } else if ($level === self::_LOG_LEVEL_INFO) {
            $levelString = '情報';
        } else if ($level === self::_LOG_LEVEL_DEBUG) {
            $levelString = 'デバッグ';
        } else {
            $levelString = '不明';
        }
        return $levelString;
    }

    /**
     * 出力するログのヘッダーを生成します。
     *
     * 以下の形式のヘッダーが生成されます。<br />
     * "IP(ホスト名) ファイル名(行番号) [クラス名->メソッド名]"
     *
     * ファイル名とライン番号は4つ前の呼び出し元情報を取得します。<br />
     * 関数名は5つ前の呼び出し元情報から取得します。
     *
     * @return string 生成されたヘッダー文字列
     */
    public function _createLogHeader() {
        // IP,Host
        if (isset($_SERVER["REMOTE_ADDR"])) {
            $ip   = $_SERVER["REMOTE_ADDR"];
            $host = gethostbyaddr($ip);
        } else {
            // CLI
            $ip   = 'BATCH';
            $host = 'BATCH';
        }

        // 呼び出し元情報
        $dbg = debug_backtrace();

        // ファイル名：4つ前から取得
        $fname = '';
        if (isset($dbg[3]['file'])) {
            // ファイル名のみ
            $fname = basename($dbg[3]['file']);
        }

        // 行番号：4つ前から取得
        $line = '';
        if (isset($dbg[3]['line'])) {
            $line = $dbg[3]['line'];
        }

        // クラス名：5つ前から取得
        $class = '';
        if (isset($dbg[4]['class'])) {
            $class = $dbg[4]['class'];
        }

        // 呼び出しタイプ：5つ前から取得
        $type = '';
        if (isset($dbg[4]['type'])) {
            $type = $dbg[4]['type'];
        }

        // メソッド(関数)：5つ前から取得
        $function = '';
        if (isset($dbg[4]['function'])) {
            $function = $dbg[4]['function'];
        }

        return "{$ip}({$host}) {$fname}({$line}) [{$class}{$type}{$function}]";
    }

    /**
     * レベル文字列からレベル値を取得します。
     *
     * レベル文字列に対応する値がない場合は {@link Sgmov_Component_Impl_Log::_LOG_LEVEL_NONE} を返します。
     *
     * @param string $levelString レベル文字列
     * @return レベル値
     */
    public function _getLevelValueFromString($levelString) {
        $level = self::_LOG_LEVEL_INFO;
        if ($levelString === 'LOG_LEVEL_NONE') {
            $level = self::_LOG_LEVEL_NONE;
        } elseif ($levelString === 'LOG_LEVEL_ERR') {
            $level = self::_LOG_LEVEL_ERR;
        } elseif ($levelString === 'LOG_LEVEL_WARNING') {
            $level = self::_LOG_LEVEL_WARNING;
        } elseif ($levelString === 'LOG_LEVEL_INFO') {
            $level = self::_LOG_LEVEL_INFO;
        } elseif ($levelString === 'LOG_LEVEL_DEBUG') {
            $level = self::_LOG_LEVEL_DEBUG;
        } else {
            $level = self::_LOG_LEVEL_NONE;
        }
        return $level;
    }

    /**
     * ログファイルを開きます。
     *
     * ファイルが既に開いている場合は TRUE を返します。
     * ファイルが存在しない場合はフォルダも含めて作成します。
     * ファイルは追加モードで開きます。
     */
    public function _open() {
        if ($this->_opened) {
            return;
        }

        /**
         * ログファイルが存在しているか確認
         * 日付がファイル名になっているので本日で初めてでもファイル作成となる
         */
        $exists = file_exists($this->_fpath);
        if (!is_dir(dirname($this->_fpath))) {
            mkdir(dirname($this->_fpath), $this->_dirmode, TRUE);
        }

        $this->_fp = fopen($this->_fpath, 'ab');
        if (!$this->_fp) {
            exec(
                sprintf(
                    'export LANG=C;ls -lh %s',
                    escapeshellarg(
                        dirname($this->_fpath)
                    )
                ),
                $ls_arr
            );

            $ls = implode("\n", $ls_arr);

            // 管理者にメール送信
            $params = array(
                'fpath' => $this->_fpath,
                'ls'    => $ls
            );

            $mail_to = Sgmov_Component_Config::getLogMailTo();
            Sgmov_Component_Mail::sendTemplateMail($params, dirname(__FILE__) . '/../../lib/mail_template/log_open_error.txt', $mail_to);
        }

        // 今回でログを新規作成した場合にパーミッションや所有者を変更
        //if ($exists == FALSE && $this->_opened == FALSE) {
            @chmod($this->_fpath, $this->_mode);
            @chown($this->_fpath, 'apache');
            @chgrp($this->_fpath, 'apache');
        //}

        // オープンフラグを変更。プロセス中は常時オープンとなる。
        $this->_opened = TRUE;
    }
}