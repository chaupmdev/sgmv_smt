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
 * アンケート結果ダウンロード画面の共通処理を管理する抽象クラスです。
 * @package    View
 * @subpackage AQU
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Aqu_Common extends Sgmov_View_Maintenance
{
    /**
     * 機能ID
     */
    const FEATURE_ID = 'AQU';

    /**
     * AQU001の画面ID
     */
    const GAMEN_ID_AQU001 = 'AQU001';

    /**
     * 月
     * @var array
     */
    public $months = array('',
                             '01',
                             '02',
                             '03',
                             '04',
                             '05',
                             '06',
                             '07',
                             '08',
                             '09',
                             '10',
                             '11',
                             '12');

    /**
     * 日
     * @var array
     */
    public $days = array('',
                             '01',
                             '02',
                             '03',
                             '04',
                             '05',
                             '06',
                             '07',
                             '08',
                             '09',
                             '10',
                             '11',
                             '12',
                             '13',
                             '14',
                             '15',
                             '16',
                             '17',
                             '18',
                             '19',
                             '20',
                             '21',
                             '22',
                             '23',
                             '24',
                             '25',
                             '26',
                             '27',
                             '28',
                             '29',
                             '30',
                             '31');

    /**
     * 機能IDを取得します。
     * @return string 機能ID
     */
    public function getFeatureId()
    {
        return self::FEATURE_ID;
    }

    /**
     * 2009年から今年までの年の配列を生成します。
     *
     * 先頭には空白の項目を含みます。
     *
     * @return array 2009年から今年までの年の配列
     */
    public function getYears()
    {
        $years = array();
        $years[] = '';

        $min_year = 2009;
        $max_year = date('Y');
        for ($i = $min_year; $i <= $max_year; $i++) {
            $years[] = sprintf('%04d', $i);
        }

        return $years;
    }
}
?>
