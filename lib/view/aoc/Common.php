<?php
/**
 * @package    ClassDefFile
 * @author     T.Aono(SGS)
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
 * 他社連携キャンペーンの共通処理を管理する抽象クラスです。
 * @package    View
 * @subpackage AOC
 * @author     T.Aono(SGS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Aoc_Common extends Sgmov_View_Maintenance
{
    /**
     * 機能ID
     */
    const FEATURE_ID = 'AOC';

    /**
     * AOC002の画面ID
     */
    const GAMEN_ID_AOC002 = 'AOC002';

    /**
     * AOC003の画面ID
     */
    const GAMEN_ID_AOC003 = 'AOC003';

    /**
     * AOC004の画面ID
     */
    const GAMEN_ID_AOC004 = 'AOC004';
 
    /**
     * 機能IDを取得します。
     * @return string 機能ID
     */
    public function getFeatureId()
    {
        return self::FEATURE_ID;
    }
	
	 /**
     * 本社ユーザーフラグを取得します。
     * @return string '1':本社ユーザーである '0':本社ユーザーではない
     */
    public function getHonshaUserFlag()
    {
        $svc = new Sgmov_Service_Login();
        return $svc->getHonshaUserFlag();
    }
	

}
?>
