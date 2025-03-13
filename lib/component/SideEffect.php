<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/impl/SideEffect.php';
/**#@-*/

 /**
 * 単体テストのために exit() 関数と header() 関数をラッピングします。
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
class Sgmov_Component_SideEffect
{
    /**
     * 実装クラスのインスタンス
     * @var Sgmov_Component_Impl_SideEffect
     */
    public static $_impl;

    /**
     * exit() 関数を呼び出します。
     */
    public static function callExit()
    {
        self::_getImpl()->callExit();
    }

    /**
     * header() 関数を呼び出します。
     *
     * テスト時にモックを使用して入れ替えることができるように
     * ラッピングしています。
     *
     * @param string $string ヘッダー文字列
     */
    public static function callHeader($string)
    {
        self::_getImpl()->callHeader($string);
    }

    /**
     * 実装クラスのインスタンスを取得します。
     *
     * 既に生成されている場合はそのインスタンスを返します。
     * まだ生成されていない場合は生成して返します。
     *
     * @return Sgmov_Component_Impl_SideEffect 実装クラスのインスタンス
     */
    public static function _getImpl()
    {
        if (!isset(self::$_impl)) {
            self::$_impl = new Sgmov_Component_Impl_SideEffect();
        }
        return self::$_impl;
    }
}