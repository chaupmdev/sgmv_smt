<?php
/**
 * @package    ClassDefFile
 * @author     K.Sawada
 * @copyright  2009-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useAllComponents();
//Sgmov_Lib::useServices(array('Login', 'TravelAgency'));
Sgmov_Lib::useServices(array('Login', 'TravelAgency', 'Travel', 'TravelTerminal', 'TravelProvinces', 'TravelDeliveryCharge', 'TravelDeliveryChargeAreas', 'TravelProvincesPrefectures', 'Prefecture', 'InformationSchema'));
Sgmov_Lib::useView('Maintenance');
/**#@-*/

 /**
 * Excel一括取込の共通処理を管理する抽象クラスです。
 * @package    View
 * @subpackage ABI
 * @author     K.Sawada
 * @copyright  2009-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Abi_Common extends Sgmov_View_Maintenance
{
    /**
     * 機能ID
     */
    const FEATURE_ID = 'ABI';
    
    /**
     * ACF001の画面ID
     */
    const GAMEN_ID_ABI001 = 'ABI001';

    /**
     * ACF002の画面ID
     */
    const GAMEN_ID_ABI002 = 'ABI002';

    /**
     * ACF003の画面ID
     */
    const GAMEN_ID_ABI003 = 'ABI003';

    /**
     * ACF004の画面ID
     */
    const GAMEN_ID_ABI004 = 'ABI004';

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
