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
Sgmov_Lib::useServices(array('Login', 'TravelAgency', 'Travel', 'TravelTerminal', 'TravelProvinces', 'TravelDeliveryCharge', 'TravelDeliveryChargeAreas'));
Sgmov_Lib::useView('Maintenance');
/**#@-*/

/**
 * ツアー配送料金マスタメンテナンスの共通処理を管理する抽象クラスです。
 * @package    View
 * @subpackage ATC
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Atc_Common extends Sgmov_View_Maintenance {

    /**
     * 機能ID
     */
    const FEATURE_ID      = 'ATC';

    /**
     * ATC001の画面ID
     */
    const GAMEN_ID_ATC001 = 'ATC001';

    /**
     * ATC002の画面ID
     */
    const GAMEN_ID_ATC002 = 'ATC002';

    /**
     * 機能IDを取得します。
     * @return string 機能ID
     */
    public function getFeatureId() {
        return self::FEATURE_ID;
    }

    /**
     * プルダウンを生成し、HTMLソースを返します。
     * TODO pre/Inputと同記述あり
     *
     * @param $cds コードの配列
     * @param $lbls ラベルの配列
     * @param $select 選択値
     * @return 生成されたプルダウン
     */
    public static function _createPulldown($cds, $lbls, $select) {

        $html = '';

        if (empty($cds)) {
            return $html;
        }

        $count = count($cds);
        for ($i = 0; $i < $count; ++$i) {
            if ($select === $cds[$i]) {
                $html .= '<option value="' . $cds[$i] . '" selected="selected">' . $lbls[$i] . '</option>' . PHP_EOL;
            } else {
                $html .= '<option value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
            }
        }

        return $html;
    }

    /**
     * プルダウンを生成し、HTMLソースを返します。
     * TODO pre/Inputと同記述あり
     *
     * @param $cds コードの配列
     * @param $lbls ラベルの配列
     * @param $select 選択値
     * @return 生成されたプルダウン
     */
    public static function _createPulldownAddRoundTripDiscount($cds, $lbls, $select, $round_trip_discounts = null) {

        $html = '';

        if (empty($cds)) {
            return $html;
        }

        $count = count($cds);
        for ($i = 0; $i < $count; ++$i) {
            if ($select === $cds[$i]) {
                $html .= '<option data-round-trip-discount="' . $round_trip_discounts[$i] . '" value="' . $cds[$i] . '" selected="selected">' . $lbls[$i] . '</option>' . PHP_EOL;
            } else {
                $html .= '<option data-round-trip-discount="' . $round_trip_discounts[$i] . '" value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
            }
        }

        return $html;
    }
}