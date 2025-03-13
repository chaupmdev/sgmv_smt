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
Sgmov_Lib::useServices(array('CruiseRepeater', 'Prefecture', 'Yubin', 'SocketZipCodeDll', 'TravelAgency', 'Travel', 'TravelTerminal', 'TravelDeliveryChargeAreas', 'AppCommon'));
Sgmov_Lib::useView('Public');
/**#@-*/

/**
 * 旅客手荷物受付サービスのお申し込みフォームの共通処理を管理する抽象クラスです。
 * @package    View
 * @subpackage PCR
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Pct_Common extends Sgmov_View_Public {
    /**
     * 機能ID
     */
    const FEATURE_ID = 'PCT';
    
    const FEATURE_ID_IVR = 'PCT_IVR';

    /**
     * PCR001の画面ID
     */
    const GAMEN_ID_PCR001 = 'PCT001';

    /**
     * PCR002の画面ID
     */
    const GAMEN_ID_PCR002 = 'PCT002';

    /**
     * PCR003の画面ID
     */
    const GAMEN_ID_PCR003 = 'PCT003';
    
    const PCR_IVR_REQUEST = 1;

    const PCR_WEB_REQUEST = 2;
    
    const COLLECT_DATE_END = '-5 day'; //５日前から８日前に変更したのものから4日前に変更
    const COLLECT_DATE_START = '-11 day'; //１１日前から１５日前に変更したものから8日前に変更
    const SITE_FLAG = '1';//通常版
    /**
     * 集荷の往復コード選択値
     * @var array
     */
    public $terminal_lbls = array(
        1 => '往路のみ',
        2 => '復路のみ',
        3 => '往復',
    );

    /**
     * お支払方法コード選択値
     * @var array
     */
    public $payment_method_lbls = array(
        '' => '',
        1  => 'コンビニ決済',
        2  => 'クレジットカード',
    );

    /**
     * お支払店コード選択値
     * @var array
     */
    public $convenience_store_lbls = array(
        1 => 'セブンイレブン',
        2 => 'ローソン、セイコーマート、ファミリーマート、ミニストップ',
        3 => 'デイリーヤマザキ',
    );

    /**
     * 集荷希望時間帯コード選択値
     * @var array
     */
    public $cargo_collection_st_time_lbls = array(
        '00' => '指定なし',
        10 => '10時～13時',
        12 => '12時～15時',
        15 => '15時～18時',
        18 => '18時～20時',
    );

    /**
     * 集荷希望終了時刻コード選択値
     * @var array
     */
    public $cargo_collection_ed_time_lbls = array(
        '00' => '00',
        10 => '13',
        12 => '15',
        15 => '18',
        18 => '20',
    );

    // 消費税率
    const CURRENT_TAX = 1.10;
    
    /**
     * 表示用時刻を取得する
     * @param object $db
     * @return
     */
    protected function _fetchTime($begin, $end, $step = 1) {
        $ids = array('');
        $names = array('');
        for ($i = $begin; $i <= $end; $i += $step) {
            $ids[] = sprintf('%02d', $i);
            $names[] = $i;
        }
        return array(
            'ids' => $ids,
            'names' => $names,
        );
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
    public static function _createPulldownAddDate($cds, $lbls, $select, $dates = null) {

        $html = '';

        if (empty($cds)) {
            return $html;
        }

        $count = count($cds);
        for ($i = 0; $i < $count; ++$i) {
            if ($select === $cds[$i]) {
                $html .= '<option data-date="' . $dates[$i] . '" value="' . $cds[$i] . '" selected="selected">' . $lbls[$i] . '</option>' . PHP_EOL;
            } else {
                $html .= '<option data-date="' . $dates[$i] . '" value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
            }
        }

        return $html;
    }

    /**
     * プルダウンを生成し、HTMLソースを返します。
     *
     * @param $cds コードの配列
     * @param $lbls ラベルの配列
     * @param $select 選択値
     * @return 生成されたプルダウン
     */
    public static function _createPulldownAddDiscount($cds, $lbls, $select, $discount = null) {

        $html = '';

        if (empty($cds)) {
            return $html;
        }

        $count = count($cds);
        for ($i = 0; $i < $count; ++$i) {
            if ($select === $cds[$i]) {
                $html .= '<option data-discount="' . $discount[$i] . '" value="' . $cds[$i] . '" selected="selected">' . $lbls[$i] . '</option>' . PHP_EOL;
            } else {
                $html .= '<option data-discount="' . $discount[$i] . '" value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
            }
        }

        return $html;
    }
}