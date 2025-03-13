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
Sgmov_Lib::useServices(array('Login', 'Prefecture', 'TravelProvinces', 'TravelProvincesPrefectures'));
Sgmov_Lib::useView('Maintenance');
/**#@-*/

/**
 * ツアーエリアマスタメンテナンスの共通処理を管理する抽象クラスです。
 * @package    View
 * @subpackage ATP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Atp_Common extends Sgmov_View_Maintenance {

    /**
     * 機能ID
     */
    const FEATURE_ID      = 'ATP';

    /**
     * ATP001の画面ID
     */
    const GAMEN_ID_ATP001 = 'ATP001';

    /**
     * ATP002の画面ID
     */
    const GAMEN_ID_ATP002 = 'ATP002';

    /**
     * 機能IDを取得します。
     * @return string 機能ID
     */
    public function getFeatureId() {
        return self::FEATURE_ID;
    }
}