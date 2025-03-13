<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/impl/ErrorExit.php';
/**#@-*/

 /**
 * エラーの処理を行います。
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
class Sgmov_Component_ErrorExit
{
    /**
     * 実装クラスのインスタンス
     * @var Sgmov_Component_Impl_ErrorExit
     */
    public static $_impl;

    /**
     * 指定されたエラーコードに対応した処理を行います。
     *
     * <ul><li>
     * [DBエラー]
     *   <ul><li>
     *   エラーログを出力しエラー通知メールを送信します。
     *   </li><li>
     *   システムエラーで終了します。
     *   </li></ul>
     * </li><li>
     * [その他]
     *   <ul><li>
     *   エラーログを出力しエラー通知メールを送信します。
     *   </li><li>
     *   システムエラーで終了します。
     *   </li></ul>
     * </li></ul>
     *
     * @param integer $code エラーコード
     * @param string $message [optional] エラーメッセージ
     * @param Exception $cause [optional] エラーの原因となった例外
     */
    public static function errorExit($code, $message = '', $cause = NULL)
    {
        self::_getImpl()->errorExit($code, $message, $cause);
    }

    /**
     * 実装クラスのインスタンスを取得します。
     *
     * 既に生成されている場合はそのインスタンスを返します。
     * まだ生成されていない場合は生成して返します。
     *
     * @return Sgmov_Component_Impl_Error 実装クラスのインスタンス
     */
    public static function _getImpl()
    {
        if (!isset(self::$_impl)) {
            self::$_impl = new Sgmov_Component_Impl_ErrorExit();
        }
        return self::$_impl;
    }

}