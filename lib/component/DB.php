<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useComponents(array('Config', 'Log', 'ErrorExit', 'DBResult', 'Exception', 'ErrorCode', 'String'));
/**#@-*/

 /**
 * データベースアクセス機能を提供します。
 *
 * このクラスのメソッドでは例外は発生しません。
 * 処理に失敗した場合は全てアプリケーションエラーとしてスクリプトを終了します。
 *
 * クエリの実行には内部で
 * {@link http://php.net/manual/ja/function.pg-query-params.php pg-query-params()}
 * を使用します。パラメータの指定方法などはマニュアルを参照してください。
 *
 * (使用例)
 * <code>
 * $db = Sgmov_Component_DB::getPublic();
 * $db->begin();
 * $updateCount = $db->executeUpdate("INSERT INTO foo (col1) VALUES ($1)", array('Sato'));
 * $updateCount = $db->executeUpdate("INSERT INTO foo (col1) VALUES ($1)", array('Suzuki'));
 * $db->commit();
 * </code>
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
 * @package Component
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Component_DB
{
    /**
     * 管理画面用のインスタンス
     * @var Sgmov_Component_DB
     */
    public static $_adminInstance;

    /**
     * 公開画面用のインスタンス
     * @var Sgmov_Component_DB
     */
    public static $_publicInstance;

    /**
     * 郵便DB管理用のインスタンス
     * @var Sgmov_Component_DB
     */
    public static $_yubinAdminInstance;

    /**
     * 郵便DB一般用のインスタンス
     * @var Sgmov_Component_DB
     */
    public static $_yubinPublicInstance;

    /**
     * 管理画面用ユーザーでDBに接続したインスタンスを返します。
     *
     * 初回呼び出しで接続が生成され、二度目以降の呼び出しでは
     * その接続が使用されます。
     * スクリプト終了時に接続は解放されます。
     *
     * @param boolean $throwError [optional] エラーを投げるかどうかを表すフラグ
     * FALSE(デフォルト):エラーを内部で処理しシステムエラー処理を実行します
     * TRUE:エラーを Sgmov_Component_Exception でラッピングして投げます。
     * @return Sgmov_Component_DB このクラスの唯一のインスタンス
     */
    public static function getAdmin($throwError = FALSE)
    {
        if (!isset(self::$_adminInstance)) {
            $dbHost = Sgmov_Component_Config::getDbHost();
            $dbPort = Sgmov_Component_Config::getDbPort();
            $dbName = Sgmov_Component_Config::getDbName();
            $dbUser = Sgmov_Component_Config::getDbAdminUser();
            $dbPswd = Sgmov_Component_Config::getDbAdminPswd();
            self::$_adminInstance = new Sgmov_Component_DB($dbHost, $dbPort, $dbName, $dbUser, $dbPswd, $throwError);
        }
        return self::$_adminInstance;
    }

	/**
     * 管理画面用ユーザーでDBに接続したインスタンスを返します。
     *
     * 初回呼び出しで接続が生成され、二度目以降の呼び出しでは
     * その接続が使用されます。
     * スクリプト終了時に接続は解放されます。
     *
     * @param boolean $throwError [optional] エラーを投げるかどうかを表すフラグ
     * FALSE(デフォルト):エラーを内部で処理しシステムエラー処理を実行します
     * TRUE:エラーを Sgmov_Component_Exception でラッピングして投げます。
     * @return Sgmov_Component_DB このクラスの唯一のインスタンス
     */
    public static function getHikkoshiDetail($throwError = FALSE)
    {
        if (!isset(self::$_publicInstance)) {  
            $dbHost = Sgmov_Component_Config::getHikkoshiDbHost();
            $dbPort = Sgmov_Component_Config::getHikkoshiDbPort();
            $dbName = Sgmov_Component_Config::getHikkoshiDbName();
            $dbUser = Sgmov_Component_Config::getHikkoshiDbUser();
            $dbPswd = Sgmov_Component_Config::getHikkoshiDbPswd();
            self::$_adminInstance = new Sgmov_Component_DB($dbHost, $dbPort, $dbName, $dbUser, $dbPswd, $throwError);
        }
        return self::$_adminInstance;
    }

    /**
     * 公開画面用ユーザーでDBに接続したインスタンスを返します。
     *
     * 初回呼び出しで接続が生成され、二度目以降の呼び出しでは
     * その接続が使用されます。
     * スクリプト終了時に接続は解放されます。
     *
     * @param boolean $throwError [optional] エラーを投げるかどうかを表すフラグ
     * FALSE(デフォルト):エラーを内部で処理しシステムエラー処理を実行します
     * TRUE:エラーを Sgmov_Component_Exception でラッピングして投げます。
     * @return Sgmov_Component_DB このクラスの唯一のインスタンス
     */
    public static function getPublic($throwError = FALSE)
    {
        if (!isset(self::$_publicInstance)) {
            $dbHost = Sgmov_Component_Config::getDbHost();
            $dbPort = Sgmov_Component_Config::getDbPort();
            $dbName = Sgmov_Component_Config::getDbName();
            $dbUser = Sgmov_Component_Config::getDbPublicUser();
            $dbPswd = Sgmov_Component_Config::getDbPublicPswd();
            self::$_publicInstance = new Sgmov_Component_DB($dbHost, $dbPort, $dbName, $dbUser, $dbPswd, $throwError);
        }
        return self::$_publicInstance;
    }

    /**
     * 管理用ユーザーで郵便DBに接続したインスタンスを返します。
     *
     * 初回呼び出しで接続が生成され、二度目以降の呼び出しでは
     * その接続が使用されます。
     * スクリプト終了時に接続は解放されます。
     *
     * @param boolean $throwError [optional] エラーを投げるかどうかを表すフラグ
     * FALSE(デフォルト):エラーを内部で処理しシステムエラー処理を実行します
     * TRUE:エラーを Sgmov_Component_Exception でラッピングして投げます。
     * @return Sgmov_Component_DB このクラスの唯一のインスタンス
     */
    public static function getYubinAdmin($throwError = FALSE)
    {
        if (!isset(self::$_yubinAdminInstance)) {
            $dbHost = Sgmov_Component_Config::getYubinDbHost();
            $dbPort = Sgmov_Component_Config::getYubinDbPort();
            $dbName = Sgmov_Component_Config::getYubinDbName();
            $dbUser = Sgmov_Component_Config::getYubinDbAdminUser();
            $dbPswd = Sgmov_Component_Config::getYubinDbAdminPswd();
            self::$_yubinAdminInstance = new Sgmov_Component_DB($dbHost, $dbPort, $dbName, $dbUser, $dbPswd, $throwError);
        }
        return self::$_yubinAdminInstance;
    }

    /**
     * 一般ユーザーで郵便DBに接続したインスタンスを返します。
     *
     * 初回呼び出しで接続が生成され、二度目以降の呼び出しでは
     * その接続が使用されます。
     * スクリプト終了時に接続は解放されます。
     *
     * @param boolean $throwError [optional] エラーを投げるかどうかを表すフラグ
     * FALSE(デフォルト):エラーを内部で処理しシステムエラー処理を実行します
     * TRUE:エラーを Sgmov_Component_Exception でラッピングして投げます。
     * @return Sgmov_Component_DB このクラスの唯一のインスタンス
     */
    public static function getYubinPublic($throwError = FALSE)
    {
        if (!isset(self::$_yubinPublicInstance)) {
            $dbHost = Sgmov_Component_Config::getYubinDbHost();
            $dbPort = Sgmov_Component_Config::getYubinDbPort();
            $dbName = Sgmov_Component_Config::getYubinDbName();
            $dbUser = Sgmov_Component_Config::getYubinDbPublicUser();
            $dbPswd = Sgmov_Component_Config::getYubinDbPublicPswd();
            self::$_yubinPublicInstance = new Sgmov_Component_DB($dbHost, $dbPort, $dbName, $dbUser, $dbPswd, $throwError);
        }
        return self::$_yubinPublicInstance;
    }

    /**
     * DB接続
     * @var resource
     */
    public $_connection;

    /**
     * トランザクションが開始されているかどうか
     * @var boolean
     */
    public $_inTransaction = FALSE;

    /**
     * エラーを投げるかどうか
     * FALSE(デフォルト):エラーを内部で処理しシステムエラー処理を実行します
     * TRUE:エラーを Sgmov_Component_Exception でラッピングして投げます。
     * @var boolean
     */
    public $_throwError = FALSE;

    /**
     * @return boolean エラーを投げるかどうか
     * FALSE(デフォルト):エラーを内部で処理しシステムエラー処理を実行します
     * TRUE:エラーを Sgmov_Component_Exception でラッピングして投げます。
     */
    public function getThrowError()
    {
        return $this->_throwError;
    }

    /**
     * @param boolean $throwError エラーを投げるかどうか
     * FALSE(デフォルト):エラーを内部で処理しシステムエラー処理を実行します
     * TRUE:エラーを Sgmov_Component_Exception でラッピングして投げます。
     */
    public function setThrowError($throwError)
    {
        $this->_throwError = $throwError;
    }

    /**
     * 使用時には直接コンストラクタを呼び出さずに、 {@link getPublic()} または
     * {@link getAdmin()} を使用してください。
     *
     * このコンストラクタはテストのために公開しています。
     *
     * DBに接続します。
     *
     * 接続に失敗した場合は
     * アプリケーションエラーとなりスクリプトが終了します。
     *
     * @param string $dbHost ホスト名
     * @param string $dbPort ポート
     * @param string $dbName DB名
     * @param string $dbUser ユーザー名
     * @param string $dbPassword パスワード
     * @param boolean $throwError [optional] エラーを投げるかどうかを表すフラグ
     * FALSE(デフォルト):エラーを内部で処理しシステムエラー処理を実行します
     * TRUE:エラーを Sgmov_Component_Exception でラッピングして投げます。
     */
    public function __construct($dbHost, $dbPort, $dbName, $dbUser, $dbPassword, $throwError = FALSE)
    {
        try {
            $this->_throwError = $throwError;
            $connectionString = "host={$dbHost} port={$dbPort} dbname={$dbName} user={$dbUser} password={$dbPassword}";
            $this->_connection = pg_connect($connectionString);
            Sgmov_Component_Log::debug("DB接続成功: host={$dbHost} port={$dbPort} dbname={$dbName} user={$dbUser} throwError={$throwError}");
        }
        catch (exception $e) {
            if ($this->_throwError === TRUE) {
                throw new Sgmov_Component_Exception('接続失敗', Sgmov_Component_ErrorCode::ERROR_DB_CONNECT, $e);
            }
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_DB_CONNECT, '接続失敗', $e);
        }
    }

    /**
     * デストラクタです。
     *
     * トランザクションが開始されている場合はロールバックします。
     */
    public function __destruct()
    {
        if (isset($this->_connection) && $this->_inTransaction) {
            if (PGSQL_CONNECTION_OK === @pg_connection_status($this->_connection)) {
                @pg_query($this->_connection, "ROLLBACK");
                $this->_inTransaction = FALSE;
            }
        }
    }

    /**
     * トランザクションを開始します。
     *
     * 既に開始されている場合は何もしません。
     *
     * トランザクションの隔離レベルはPostgresデフォルトの READ COMMITTED です。
     *
     * トランザクションの開始に失敗した場合は
     * アプリケーションエラーとなりスクリプトが終了します。
     */
    public function begin()
    {
        if ($this->_inTransaction) {
            Sgmov_Component_Log::warning('トランザクションは既に開始されています。');
            return;
        }
        try {
            pg_query($this->_connection, "BEGIN");
            $this->_inTransaction = TRUE;
            Sgmov_Component_Log::debug('トランザクション開始成功');
        }
        catch (exception $e) {
            if ($this->_throwError === TRUE) {
                throw new Sgmov_Component_Exception('トランザクション開始失敗', Sgmov_Component_ErrorCode::ERROR_DB_BEGIN, $e);
            }
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_DB_BEGIN, 'トランザクション開始失敗', $e);
        }
    }

    /**
     * トランザクションをコミットします。
     *
     * トランザクションが開始していない場合は何もしません。
     *
     * コミットに失敗した場合は
     * アプリケーションエラーとなりスクリプトが終了します。
     */
    public function commit()
    {
        if (!$this->_inTransaction) {
            Sgmov_Component_Log::warning('トランザクションは開始されていません。');
            return;
        }
        try {
            pg_query($this->_connection, "COMMIT");
            $this->_inTransaction = FALSE;
            Sgmov_Component_Log::debug('コミット成功');
        }
        catch (exception $e) {
            if ($this->_throwError === TRUE) {
                throw new Sgmov_Component_Exception('コミット失敗', Sgmov_Component_ErrorCode::ERROR_DB_COMMIT, $e);
            }
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_DB_COMMIT, 'コミット失敗', $e);
        }
    }

    /**
     * トランザクションをロールバックします。
     *
     * トランザクションが開始していない場合は何もしません。
     *
     * トランザクションの開始に失敗した場合は
     * アプリケーションエラーとなりスクリプトが終了します。
     */
    public function rollback()
    {
        if (!$this->_inTransaction) {
            Sgmov_Component_Log::warning('トランザクションは開始されていません。');
            return;
        }
        try {
            pg_query($this->_connection, "ROLLBACK");
            $this->_inTransaction = FALSE;
            Sgmov_Component_Log::debug('ロールバック成功');
        }
        catch (exception $e) {
            if ($this->_throwError === TRUE) {
                throw new Sgmov_Component_Exception('ロールバック失敗', Sgmov_Component_ErrorCode::ERROR_DB_ROLLBACK, $e);
            }
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_DB_ROLLBACK, 'ロールバック失敗', $e);
        }
    }

    /**
     * パラメータを指定して検索クエリ(SELECT)を実行します。
     *
     * 実行に失敗した場合は
     * アプリケーションエラーとなりスクリプトが終了します。
     *
     * @param string $query パラメータ化したSQL文。
     * ひとつの文のみである必要があります（複数の文をセミコロンで区切る形式は使用できません）。
     * パラメータを 使用する際は $1、$2 などの形式で参照されます。
     * @param array $params [optional] プリペアドステートメント中の $1、$2 などのプレースホルダを
     * 置き換えるパラメータの配列。配列の要素数はプレースホルダの 数と一致する必要があります。
     * @return Sgmov_Component_DBResult クエリ実行結果リソースのラッパークラスを返します。
     */
    public function executeQuery($query, $params = array())
    {
        try {
            if (Sgmov_Component_Log::isDebug()) {
                $debugString = Sgmov_Component_String::toDebugString(array('query'=>$query, 'params'=>$params));
                Sgmov_Component_Log::debug($debugString);
				// デバッグログのクエリ出力が複数行にまたがり見づらいので圧縮
                //Sgmov_Component_Log::debug(preg_replace(array('/ +/','/\r\n/','/\t/'),' ',$debugString));
            }
            
            $result = pg_query_params($this->_connection, $query, $params);
            $dbResult = new Sgmov_Component_DBResult($result);
            
            return $dbResult;
        }
        catch (exception $e) {
            $debugString = Sgmov_Component_String::toDebugString(array('query'=>$query, 'params'=>$params));
            if ($this->_throwError === TRUE) {
                throw new Sgmov_Component_Exception($debugString, Sgmov_Component_ErrorCode::ERROR_DB_QUERY, $e);
            }
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_DB_QUERY, $debugString, $e);
        }
    }

    /**
     * パラメータを指定して更新クエリ(INSERT, UPDATE, DELETE)を実行します。
     *
     * 実行に失敗した場合は
     * アプリケーションエラーとなり処理が終了します。
     *
     * @param string $query パラメータ化したSQL文。
     * ひとつの文のみである必要があります（複数の文をセミコロンで区切る形式は使用できません）。
     * パラメータを 使用する際は $1、$2 などの形式で参照されます。
     * @param array $params [optional] プリペアドステートメント中の $1、$2 などのプレースホルダを
     * 置き換えるパラメータの配列。配列の要素数はプレースホルダの 数と一致する必要があります。
     * @return integer 更新されたレコード数
     */
    public function executeUpdate($query, $params = array())
    {
        try {
            if (Sgmov_Component_Log::isDebug()) {
               $debugString = Sgmov_Component_String::toDebugString(array('query'=>$query, 'params'=>$params));
               Sgmov_Component_Log::debug($debugString);
               // デバッグログのクエリ出力が複数行にまたがり見づらいので圧縮
               //Sgmov_Component_Log::debug(preg_replace(array('/ +/','/\r\n/','/\t/'),' ',$debugString));
            }
            
            $result = pg_query_params($this->_connection, $query, $params);
            $count = pg_affected_rows($result);
            
            if (Sgmov_Component_Log::isDebug()) {
                Sgmov_Component_Log::debug('resul t= '.$count);
            }
            return $count;
        }
        catch (exception $e) {
            $debugString = Sgmov_Component_String::toDebugString(array('query'=>$query, 'params'=>$params));
            if ($this->_throwError === TRUE) {
               throw new Sgmov_Component_Exception($debugString, Sgmov_Component_ErrorCode::ERROR_DB_UPDATE, $e);
            }
           Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_DB_UPDATE, $debugString, $e);
        }
    }

    /**
     * rows の内容をテーブルに挿入します。レコードを挿入するために、内部では COPY FROM SQL コマンドを発行します。
     *
     * ※特価で全組み合わせを登録する際に特価明細が約6万件になるため、このメソッドを用意しました。
     * 現在は特価明細のインサートにのみ使用しています。
     *
     * 実行に失敗した場合はアプリケーションエラーとなり処理が終了します。
     *
     * @param string $table_name rows をコピーするテーブルの名前。
     * @param array $rows table_name にコピーするデータの配列。
     * rows の個々の値が table_name のひとつの行となります。rows の個々の値は、
     * それぞれのフィールドに対応する値が区切り文字で区切られており、最後は
     * 改行で終了していなければなりません。
     * @param string $delimiter [optional] rows の要素内で、各フィールドに対応する値を
     * 区切る文字。デフォルトは \t です。
     * @param string $null_as [optional] rows の中で、SQL の NULL をどのように表現するか。
     * デフォルトは \N ("\\N") です。
     * @return boolean 成功した場合に TRUE を、失敗した場合に FALSE を返します。
     */
    public function executeCopyFrom($table_name, $rows, $delimiter = "\t", $null_as = "\\N")
    {
        // TODO 未テスト
        Sgmov_Component_Log::debug('executeCopyFrom を実行します。');
        try {
            return pg_copy_from($this->_connection, $table_name, $rows, $delimiter, $null_as);
        }
        catch (exception $e) {
            if ($this->_throwError === TRUE) {
                throw new Sgmov_Component_Exception('executeCopyFrom 失敗', Sgmov_Component_ErrorCode::ERROR_DB_COPY_FROM,
                     $e);
            }
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_DB_COPY_FROM, 'executeCopyFrom 失敗', $e);
        }
    }
}