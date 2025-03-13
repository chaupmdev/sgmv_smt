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
Sgmov_Lib::useAllComponents();
Sgmov_Lib::useView('Maintenance');
/**#@-*/

 /**
 * 料金マスタメンテナンスの共通処理を管理する抽象クラスです。
 * @package    View
 * @subpackage ACF
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Acf_Common extends Sgmov_View_Maintenance
{
    /**
     * 機能ID
     */
    const FEATURE_ID = 'ACF';

    /**
     * ACF002の画面ID
     */
    const GAMEN_ID_ACF002 = 'ACF002';

    /**
     * ACF003の画面ID
     */
    const GAMEN_ID_ACF003 = 'ACF003';

    /**
     * ACF004の画面ID
     */
    const GAMEN_ID_ACF004 = 'ACF004';

    /**
     * 地域プルダウン（通常）
     */
    const AREA_HYOJITYPE_NORMAL = '1';
    
    /**
     * 地域プルダウン（沖縄なし）
     */
    const AREA_HYOJITYPE_OKINAWANASHI = '2';
    
    /**
     * 地域プルダウン（単身エアカーゴプラン）
     */
    const AREA_HYOJITYPE_AIRCARGO = '3';

    /**
     * 機能IDを取得します。
     * @return string 機能ID
     */
    public function getFeatureId()
    {
        return self::FEATURE_ID;
    }

}
?>
