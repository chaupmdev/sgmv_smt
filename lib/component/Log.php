<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/impl/Log.php';
/**#@-*/

 /**
 * ロギングを行います。
 *
 * 環境設定で指定されたログ出力フォルダに現在の日付で
 * ログファイルを作成してログを出力します。
 * 既にログファイルが存在する場合は、そのファイルにログを追記します。
 * フォルダが存在しない場合は作成します。
 *
 * 基本メソッドとして
 * err(エラー)、warning(警告)、info(情報)、debug(デバッグ)の
 * 4種類のメソッドを用意しています。
 *
 * (例) info を呼び出す場合
 * <code>
 * SgmovLog::info('お問い合わせが登録されました。');
 * </code>
 *
 * 使い分けの目安は以下の通りです。
 * <ul>
 * <li>err: 処理の継続が不可能な致命的問題が発生した場合</li>
 * <li>warn: 処理は継続するが注意を要する問題が発生した場合</li>
 * <li>info: 通常実行時に必要な情報を表示する場合</li>
 * <li>debug: デバッグ用の情報を表示する場合</li>
 * </ul>
 *
 * 通常運用時は debug の内容はログファイルにはは出力せずに
 * err、warn、infoだけを出力します。
 * システムに問題が発生した場合などには、設定を変更して
 * debug も出力するようにします。
 *
 * 上記4メソッドに加え、エラーログと共に通知メールを送信する場合のために
 * errWithMail メソッドも用意しています。
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
class Sgmov_Component_Log
{
    /**
     * 実装クラスのインスタンス
     * @var Sgmov_Component_Impl_Log
     */
    public static $_impl;

    /**
     * エラーログを出力します。
     *
     * 出力に失敗した場合は、システムログに出力します。
     * このメソッドでは例外は発生しません。
     *
     * @param string $message 出力するメッセージ
     * @param exception $cause [optional] 原因となった例外
     */
    public static function err($message, $cause = NULL)
    {
        self::_getImpl()->err($message, $cause);
    }

    /**
     * 警告ログを出力します。
     *
     * 出力に失敗した場合は、システムログに出力します。
     * このメソッドでは例外は発生しません。
     *
     * @param string $message 出力するメッセージ
     * @param exception $cause [optional] 原因となった例外
     */
    public static function warning($message, $cause = NULL)
    {
        self::_getImpl()->warning($message, $cause);
    }

    /**
     * 情報ログを出力します。
     *
     * 出力に失敗した場合は、システムログに出力します。
     * このメソッドでは例外は発生しません。
     *
     * @param string $message 出力するメッセージ
     * @param exception $cause [optional] 原因となった例外
     */
    public static function info($message, $cause = NULL)
    {
        self::_getImpl()->info($message, $cause);
    }

    /**
     * デバッグログを出力します。
     *
     * 出力に失敗した場合は、システムログに出力します。
     * このメソッドでは例外は発生しません。
     *
     * @param string $message 出力するメッセージ
     * @param exception $cause [optional] 原因となった例外
     */
    public static function debug($message, $cause = NULL)
    {
        self::_getImpl()->debug($message, $cause);
    }

    /**
     * エラーログを出力し、通知メールを送信します。
     *
     * エラーログを出力しない設定にしていても通知メールは送信されます。
     * ただし、送信先メールアドレスが設定されていない場合は送信されません。
     *
     * メールによって通知されるのはタイトルと概要です。
     * 詳細内容と原因となった例外はログファイルのみに出力されます。
     *
     * 出力に失敗した場合は、システムログに出力します。
     * このメソッドでは例外は発生しません。
     *
     * @param string $title タイトル
     * @param string $summary 概要
     * @param string $message 詳細内容
     * @param exception $cause [optional] 原因となった例外
     */
    public static function errWithMail($title, $summary, $message, $cause = NULL)
    {
        self::_getImpl()->errWithMail($title, $summary, $message, $cause);
    }

    /**
     * 現在の設定でデバッグレベルのログが出力されるかどうかを取得します。
     * @return デバッグレベルのログが出力される場合は TRUE を、
     * 出力されない場合は FALSE を返します。
     */
    public static function isDebug()
    {
        return self::_getImpl()->isDebug();
    }

    /**
     * 実装クラスのインスタンスを取得します。
     *
     * 既に生成されている場合はそのインスタンスを返します。
     * まだ生成されていない場合は生成して返します。
     *
     * @return Sgmov_Component_Impl_Log 実装クラスのインスタンス
     */
    public static function _getImpl()
    {
        if (!isset(self::$_impl)) {
            self::$_impl = new Sgmov_Component_Impl_Log();
        }
        return self::$_impl;
    }
}