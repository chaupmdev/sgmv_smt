<?php
/**
 * @package    ClassDefFile
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useAllComponents();
Sgmov_Lib::useServices(array('Provinces','ProvincesArea','SpecialPrice'));
Sgmov_Lib::useView('Public');
/**#@-*/

 /**
 * キャンペーン一覧の共通処理を管理する抽象クラスです。
 * @package    View
 * @subpackage PVE
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Pcl_Common extends Sgmov_View_Public
{
    /**
     * 機能ID
     */
    const FEATURE_ID = 'PCL';
}
?>
