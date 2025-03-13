<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/impl/Redirect.php';
/**#@-*/

 /**
 * リダイレクト機能を提供します。
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
 * 実装を分離しています。
 *
 * @package Component
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Component_Redirect
{
    /**
     * 実装クラスのインスタンス
     * @var Sgmov_Component_Impl_Redirect
     */
    public static $_impl;

    /**
     * 公開画面(HTTP)のリダイレクトを実行して処理を終了します。
     * @param string $relativePath ルートからの相対パスです。先頭にはスラッシュが必要です。
     */
    public static function redirectPublicHttp($relativePath)
    {
        self::_getImpl()->redirectPublicHttp($relativePath);
    }

    /**
     * 公開画面(HTTPS)のリダイレクトを実行して処理を終了します。
     * @param string $relativePath ルートからの相対パスです。先頭にはスラッシュが必要です。
     */
    public static function redirectPublicSsl($relativePath)
    {
        self::_getImpl()->redirectPublicSsl($relativePath);
    }

    /**
     * 管理画面のリダイレクトを実行して処理を終了します。
     * @param string $relativePath ルートからの相対パスです。先頭にはスラッシュが必要です。
     */
    public static function redirectMaintenance($relativePath)
    {
        self::_getImpl()->redirectMaintenance($relativePath);
    }

    /**
     * 実装クラスのインスタンスを取得します。
     *
     * 既に生成されている場合はそのインスタンスを返します。
     * まだ生成されていない場合は生成して返します。
     *
     * @return Sgmov_Component_Impl_Redirect 実装クラスのインスタンス
     */
    public static function _getImpl()
    {
        if (!isset(self::$_impl)) {
            self::$_impl = new Sgmov_Component_Impl_Redirect();
        }
        return self::$_impl;
    }
}