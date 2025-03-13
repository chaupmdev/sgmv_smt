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
Sgmov_Lib::useComponents(array('ErrorExit', 'ErrorCode'));
/**#@-*/

 /**
 * クエリ実行結果リソースのラッパーです。
 *
 * {@link Sgmov_Component_DB::executeQuery()} の結果として使用されます。
 *
 * このクラスのメソッドでは例外は発生しません。
 * 処理に失敗した場合は全てアプリケーションエラーとしてスクリプトを終了します。
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
class Sgmov_Component_DBResult
{
    /**
     * クエリ実行結果リソース
     * @var resource
     */
    public $_rs;

    /**
     * クエリの実行結果リソースを受け取ってクラスをインスタンス化します。
     * @param resource $result クエリ実行結果リソース
     */
    public function __construct($result)
    {
        $this->_rs = $result;
    }

    /**
     * 取得した行（レコード）を配列で返します。
     * @param integer $index 取得する行番号。最初の行は 0 です。
     * @return array フィールド名をキーとする連想配列
     */
    public function get($index)
    {
        try {
            return pg_fetch_array($this->_rs, $index, PGSQL_ASSOC);
        }
        catch (exception $e) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_DB_RECORD_GET, '', $e);
        }
    }

    /**
     * 結果のレコード数を取得します。
     * @return 結果のレコード数
     */
    public function size()
    {
        try {
            return pg_num_rows($this->_rs);
        }
        catch (exception $e) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_DB_RECORD_SIZE, '', $e);
        }
    }
}