<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/* * #@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useAllComponents();
Sgmov_Lib::useServices(array('Login', 'TravelAgency'));
Sgmov_Lib::useView('Maintenance');
/* * #@- */

/**
 * ツアー会社マスタメンテナンスの共通処理を管理する抽象クラスです。
 * @package    View
 * @subpackage ATA
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Ata_Common extends Sgmov_View_Maintenance {

    /**
     * 機能ID
     */
    const FEATURE_ID = 'ATA';

    /**
     * ATA001の画面ID
     */
    const GAMEN_ID_ATA001 = 'ATA001';

    /**
     * ATA002の画面ID
     */
    const GAMEN_ID_ATA002 = 'ATA002';

    /**
     * ATA012の画面ID
     */
    const GAMEN_ID_ATA012 = 'ATA012';

    /**
     * 機能IDを取得します。
     * @return string 機能ID
     */
    public function getFeatureId() {
        return self::FEATURE_ID;
    }
}