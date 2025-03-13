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
Sgmov_Lib::useComponents(array('Config', 'SideEffect'));
/**#@-*/

 /**
 * {@link Sgmov_Component_Redirect} の実装クラスです。
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
 *s
 * @package Component_Impl
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Component_Impl_Redirect
{
    /**
     * {@link Sgmov_Component_Redirect::redirectPublicHttp()} の実装です。
     *
     * @param string $relativePath ルートからの相対パスです。先頭にはスラッシュが必要です。
     */
    public function redirectPublicHttp($relativePath)
    {
        $baseUrl = Sgmov_Component_Config::getUrlPublicHttp();
        Sgmov_Component_SideEffect::callHeader("Location: {$baseUrl}{$relativePath}");
        Sgmov_Component_SideEffect::callExit();
    }

    /**
     * {@link Sgmov_Component_Redirect::redirectPublicSsl()} の実装です。
     *
     * @param string $relativePath ルートからの相対パスです。先頭にはスラッシュが必要です。
     */
    public function redirectPublicSsl($relativePath)
    {
        $baseUrl = Sgmov_Component_Config::getUrlPublicSsl();
        Sgmov_Component_SideEffect::callHeader("Location: {$baseUrl}{$relativePath}");
        Sgmov_Component_SideEffect::callExit();
    }

    /**
     * {@link Sgmov_Component_Redirect::redirectMaintenance()} の実装です。
     *
     * @param string $relativePath ルートからの相対パスです。先頭にはスラッシュが必要です。
     */
    public function redirectMaintenance($relativePath)
    {
        $baseUrl = Sgmov_Component_Config::getUrlMaintenance();
        Sgmov_Component_SideEffect::callHeader("Location: {$baseUrl}{$relativePath}");
        Sgmov_Component_SideEffect::callExit();
    }
}