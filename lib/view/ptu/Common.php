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
Sgmov_Lib::useServices(array('MstCargoArea', 'Yubin', 'SocketZipCodeDll', 'MstCgCargoOpt','DatTanpinYuso', 'MstCargoUnchin', 'MstHanbouki', 'DatCargo', 'DatCargoOpt', 'MstCargoTanpinHinmoku', 'MstShohizei' , 'AppCommon'));
Sgmov_Lib::useView('Public');
/**#@-*/

/**
 * 単身カーゴプランのお申し込みフォームの共通処理を管理する抽象クラスです。
 * @package    View
 * @subpackage PTU
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Ptu_Common extends Sgmov_View_Public {
    /**
     * 機能ID
     */
    const FEATURE_ID = 'PTU';

    /**
     * PTU001の画面ID
     */
    const GAMEN_ID_PTU001 = 'PTU001';

    /**
     * PTU002の画面ID
     */
    const GAMEN_ID_PTU002 = 'PTU002';

    /**
     * PTU003の画面ID
     */
    const GAMEN_ID_PTU003 = 'PTU003';

    /**
    * 便種CD
    */
    const BINSHU_TANPINYOSO = '906';
    const BINSHU_TANSHIKAGO = '359';

    /**
    * 単身カーゴ場合の重量
    */
    const CAGO_WEIGHT = 500;

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
        2 => 'ローソン、セイコーマート、ファミリーマート、サークルＫサンクス、ミニストップ',
        3 => 'デイリーヤマザキ',
    );

    /**
     * 集荷希望時間帯コード選択値
     * @var array
     */
    public $cargo_collection_st_time_lbls = array(
        10 => 'AM中',
        14 => '12～14',
        16 => '14～16',
        18 => '16～18',
    );

    /**
    * 集荷希望時間帯コード選択値
    * @var array
    */
    public $cargo_collection_justime_lbls = array(
    	10 => '10:00',
    	11 => '10:30',
    	12 => '11:00',
    	13 => '11:30',
    	14 => '12:00',

    	15 => '12:30',
    	16 => '13:00',
    	17 => '13:30',
    	18 => '14:00',

    	19 => '14:30',
    	20 => '15:00',
    	21 => '15:30',
    	22 => '16:00',

    	23 => '16:30',
    	24 => '17:00',
    	25 => '17:30',
    	26 => '18:00',
    );

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

}