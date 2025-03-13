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
Sgmov_Lib::useServices(array('Login', 'Apartment'));
Sgmov_Lib::useView('Maintenance');
/**#@-*/

/**
 * マンションマスタメンテナンスの共通処理を管理する抽象クラスです。
 * @package    View
 * @subpackage AAP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Aap_Common extends Sgmov_View_Maintenance {

    /**
     * 機能ID
     */
    const FEATURE_ID      = 'AAP';

    /**
     * AAP001の画面ID
     */
    const GAMEN_ID_AAP001 = 'AAP001';

    /**
     * AAP002の画面ID
     */
    const GAMEN_ID_AAP002 = 'AAP002';

    /**
     * 機能IDを取得します。
     * @return string 機能ID
     */
    public function getFeatureId() {
        return self::FEATURE_ID;
    }
}