<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**
 * {@link Sgmov_Component_SideEffect} の実装クラスです。
 *
 * このクラスはテストを実行することができないのでカバレッジ収集の対象外としています。
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
 * @package Component_Impl
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Component_Impl_SideEffect
{
    /**
     * {@link Sgmov_Component_SideEffect::callExit()} の実装です。
     */
    public function callExit()
    {
        exit();
    }

    /**
     * {@link Sgmov_Component_SideEffect::callHeader()} の実装です。
     * @param string $string ヘッダー文字列
     */
    public function callHeader($string)
    {
        header($string);
    }
}