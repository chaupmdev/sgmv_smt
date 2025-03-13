<?php
/**
 * @package    ClassDefFile
 * @author     H.Tsuji(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/**#@+
 * include files
 */
require_once dirname(__FILE__).'/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
Sgmov_Lib::useView('CommonConst');
/**#@-*/
/**
 * 業務共通
 *
 * @package Service
 * @author     H.Tsuji(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_AppCommon extends Sgmov_View_CommonConst {

	/**
     *　入力画面引越し予定年、取得年数
     *
     */
    const INPUT_MOVEYTIYEAR_CNT = 1;
	/**
     *　入力画面クレジットカード有効期限年、取得年数
     *
     */
    const INPUT_CREDITCARD_CNT = 8;

    /**
     *　Web割引調整額
     *
     */
    const WEBWARIBIKI = 6300;

    /**
     * 初期表示コースリスト
     * @var array
     */
    public $initHyojiCorce = array('CARGO' => false, 'SYOURYO' => false, 'ONE' => false, 'TWO' => false, 'THREE' => false, 'FOUR' => false, 'FIVE' => false, 'SIX' => false);

    /**
     * 初期表示プランリスト
     * @var array
     */
    public $initHyojiPlan = array('CARGO' => "disabled", 'AIRCARGO' => "disabled", 'STANDARD' => "disabled", 'OMAKASE' => "disabled", 'CHARTAR' => "disabled");

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
     * 荷量（値はコースと同じ）の配列
     * @var array
     */
    public function getCources_amount() {

        $ids = array('',
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8');

        $names = array('',
            '1部屋（小）',
            'ワンルーム',
            '1K',
            '1DK、2K',
            '1LDK、2DK',
            '2LDK、3DK、4K',
            '3LDK、4DK、5K',
            '4LDK、5DK、6K');

        return array('ids' => $ids,
            'names' => $names);
    }

    /**
     * 指定年から指定年数後までの年の配列を生成します。
     *
     * @param startY 開始年
     * @param incY　インクリメント年
     * @param space 先頭の空白有無フラグ
     * @return array 2009年から今年までの年の配列
     */
    public function getYears($startY, $incY, $space) {

        $years = array();
        if ($space) {
            $years[] = '';
        }

        $max_year = $startY + $incY;

        for ($i = $startY; $i <= $max_year; $i++) {
            $years[] = sprintf('%04d', $i);
        }

        return $years;
    }

    /**
     * 指定日付（YYYYMMDD形式）をYYYY年MM月DD日形式に変換します。
     * 引き数の入力チェックは空かどうかのみ行います。
     *
     * @param $yyyymmdd 指定日付（YYYYMMDD形式）
     * @return ymd YYYY年MM月DD日形式
     */
    public function getYmd($yyyymmdd) {

        if (!isset($yyyymmdd) || $yyyymmdd == '') {
        	return $yyyymmdd;
        }

        $date = new DateTime($yyyymmdd);
        return $date->format('Y年m月d日');
    }

    /**
     * 指定文字列が空でない場合、指定単位を付けて返します。
     * 引き数の入力チェックは空かどうかのみ行います。
     *
     * @param $str 文字列
     * @param $tani 単位
     * @return 指定単位付き文字列
     */
    public function getTani($str, $tani, $prefix = '') {

        if (!isset($str) || $str == '') {
        	return '';
        }

        return $prefix.$str.$tani;
    }
}