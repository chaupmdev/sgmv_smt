<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/impl/System.php';
/**#@-*/

 /**
 * 最下層で必要となるシステム機能を提供します。
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
class Sgmov_Component_System
{
    /**
     * 実装クラスのインスタンス
     * @var Sgmov_Component_Impl_System
     */
    public static $_impl;

    /**
     * エラーを例外に変換する例外処理を開始します。
     *
     * 例外処理を開始すると全てのエラーは例外 ErrorException として
     * 投げられるようになります。投げられた例外が処理されなかった場合、
     * 現在のスクリプトはシステムエラーとして終了します。
     *
     * errno が error_reporting に含まれている場合に例外を投げます。
     * 含まれていない場合は何も行われません。
     *
     * "@"演算子が使用されている場合は error_reporting 値が0となるため、
     * エラーのレベルに関係なく処理は何も行われません。
     */
    public static function startErrorHandling()
    {
        self::_getImpl()->startErrorHandling();
    }

    /**
     * 例外処理が開始している場合は TRUE を、開始していない場合は FALSE を返します。
     * @return boolean 例外処理が開始している場合は TRUE を、
     * 開始していない場合は FALSE を返します。
     */
    public static function isErrorHandling()
    {
        return self::_getImpl()->isErrorHandling();
    }

    /**
     * システムログを出力します。
     *
     * ログファイル名は'system.log'です。
     *
     * 通常のログ出力には {@link Sgmov_Component_Log} を使用してください。
     * このメソッドは {@link Sgmov_Component_Log} が使用できない場合の
     * ログ出力に使用されます。
     *
     * ログの出力に失敗した場合、
     * 現在のスクリプトはシステムエラーとして終了します。
     *
     * @param string $message 出力するメッセージ
     * @param exception $cause [optional] 原因となった例外
     */
    public static function log($message, $cause = NULL)
    {
        self::_getImpl()->log($message, $cause);
    }

    /**
     * システムエラーとして
     * ステータスコード500(Internal Server Error)で処理を終了します。
     */
    public static function systemErrorExit()
    {
        self::_getImpl()->systemErrorExit();
    }

    /**
     * 実装クラスのインスタンスを取得します。
     *
     * 既に生成されている場合はそのインスタンスを返します。
     * まだ生成されていない場合は生成して返します。
     *
     * @return Sgmov_Component_Impl_System 実装クラスのインスタンス
     */
    public static function _getImpl()
    {
        if (!isset(self::$_impl)) {
            self::$_impl = new Sgmov_Component_Impl_System();
        }
        return self::$_impl;
    }
}