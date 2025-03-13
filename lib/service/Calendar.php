<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
/**#@-*/

 /**
 * カレンダー関係の情報を扱います。
 *
 * @package    Service
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_Calendar
{
    /**
     * 日付のセパレータ
     */
    const DATE_SEPARATOR = '-';

    /**
     * 渡されたタイムスタンプから1年後までの開始終了日タイムスタンプを取得します。
     *
     * 開始日：渡されたタイムスタンプの時刻情報を切り捨て、日付情報だけにしたもの。
     *
     * 終了日：開始日の1年後の日付の1日前の日付。
     *
     * @param integer $now 計算の基準となるタイムスタンプ
     * @return array ['from']:開始日、['to']:終了日
     */
    public function getOneYearPeriod($now)
    {
        $fromDay = mktime(0, 0, 0, date("n", $now), date("j", $now), date("Y", $now));
        $toDay = mktime(0, 0, 0, date("n", $fromDay), date("j", $fromDay) - 1, date("Y", $fromDay) + 1);
        return array('from'=>$fromDay,
                         'to'=>$toDay);
    }

    /**
     * 複数月カレンダーの開始終了日を取得します。
     *
     * 開始日：期間の開始日の月の1日
     *
     * 終了日：期間の終了日の月末日
     *
     * @param integer $fromDay 期間の開始日
     * @param integer $toDay 期間の終了日
     * @return array ['from']:カレンダーの開始日、['to']:カレンダーの終了日
     */
    public function getMultipleMonthsCalendarPeriod($fromDay, $toDay)
    {
        // TODO 未テスト
        $fromCalendarDay = mktime(0, 0, 0, date("n", $fromDay), 1, date("Y", $fromDay));
        $toCalendarDay = mktime(0, 0, 0, date("n", $toDay) + 1, 0, date("Y", $toDay));
        return array('from'=>$fromCalendarDay,
                         'to'=>$toCalendarDay);
    }

    /**
     * 指定された日付の範囲の祝日を取得します。
     *
     * 開始日・終了日タイムスタンプの時刻情報は切り捨てて、
     * 開始日から終了日までの祝日を取得します。開始日・終了日を含みます。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param integer $from 開始日のタイムスタンプ
     * @param integer $to 終了日のタイムスタンプ
     * @return array ['holidays'] 祝日の文字列(YYYY-MM-DD)配列、['names'] 祝日名の文字列配列
     */
    public function fetchHolidays($db, $from, $to)
    {
        $fromDayString = date("Y-m-d", $from);
        // 比較のため入力値の1日後を取得
        $temp = mktime(0, 0, 0, date("n", $to), date("j", $to) + 1, date("Y", $to));
        $toDayString = date("Y-m-d", $temp);

        $query = 'SELECT';
        $query .= '        TO_CHAR(holiday, \'YYYY-MM-DD\') AS holiday';
        $query .= '        ,name';
        $query .= '    FROM';
        $query .= '        national_holidays';
        $query .= '    WHERE';
        $query .= '        holiday >= $1';
        $query .= '        AND holiday < $2';

        $holidays = array();
        $names = array();

        $result = $db->executeQuery($query, array($fromDayString, $toDayString));
        for ($i = 0; $i < $result->size(); $i++) {
            $row = $result->get($i);
            $holidays[] = $row['holiday'];
            $names[] = $row['name'];
        }

        return array('holidays'=>$holidays,
                         'names'=>$names);
    }

    /**
     * 開始日から終了日までの日付・曜日・祝日フラグを2次元の配列で取得します。
     *
     * @param int $fromCalendarDay カレンダー開始日のタイムスタンプ
     * @param int $toCalendarDay カレンダー終了日のタイムスタンプ
     * @param int $fromDay 入力可能開始日のタイムスタンプ
     * @param int $toDay 入力可能終了日のタイムスタンプ
     * @param array $holidays 祝日日付文字列の配列
     * @return array ['days'] 日付文字列(YYYY-MM-DD)配列、
     *  ['weekday_cds'] 曜日コード配列 0(日曜)～6(土曜)、
     *  ['holiday_flags'] 祝日フラグ配列、
     *  ['between_flags'] from <= 日付 <= to の場合'1'そうでない場合は'0'
     */
    public function getBasicDateInfoMonthly($fromCalendarDay, $toCalendarDay, $fromDay, $toDay, $holidays)
    {
        // TODO 未テスト
        $days = array();
        $weekday_cds = array();
        $holiday_flags = array();
        $between_flags = array();

        $curYM = '0';
        $num = -1;
        $sep = self::DATE_SEPARATOR;
        // カレンダー開始日から終了日までを回します。
        for ($day = $fromCalendarDay; $day <= $toCalendarDay; $day = strtotime('+1 day', $day)) {
            $ym = date('Ym', $day);
            if ($curYM !== $ym) {
                // 年月が変わったら次の配列に移動する
                $num++;
                $curYM = $ym;
                $days[] = array();
                $holiday_flags[] = array();
                $weekday_cds[] = array();
                $between_flags[] = array();
            }

            $temp = date("Y{$sep}m{$sep}d", $day);
            // 日付
            $days[$num][] = $temp;
            // 曜日
            $weekday_cds[$num][] = date('w', $day);
            // 祝日
            if (in_array($temp, $holidays) !== FALSE) {
                $holiday_flags[$num][] = '1';
            } else {
                $holiday_flags[$num][] = '0';
            }
            // チェックボックス表示・非表示
            if ($day >= $fromDay && $day <= $toDay) {
                $between_flags[$num][] = '1';
            } else {
                $between_flags[$num][] = '0';
            }
        }
        return array('days'=>$days,
                         'weekday_cds'=>$weekday_cds,
                         'holiday_flags'=>$holiday_flags,
                         'between_flags'=>$between_flags);
    }

    /**
     * 開始日から終了日までの日付・曜日・祝日フラグを1次元の配列で取得します。
     *
     * @param int $fromCalendarDay カレンダー開始日のタイムスタンプ
     * @param int $toCalendarDay カレンダー終了日のタイムスタンプ
     * @param int $fromDay 入力可能開始日のタイムスタンプ
     * @param int $toDay 入力可能終了日のタイムスタンプ
     * @param array $holidays 祝日日付文字列の配列
     * @return array ['days'] 日付文字列(YYYY-MM-DD)配列、
     *  ['weekday_cds'] 曜日コード配列 0(日曜)～6(土曜)、
     *  ['holiday_flags'] 祝日フラグ配列、
     *  ['between_flags'] from <= 日付 <= to の場合'1'そうでない場合は'0'
     */
    public function getBasicDateInfoDaily($fromCalendarDay, $toCalendarDay, $fromDay, $toDay, $holidays)
    {
        // TODO 未テスト
        $days = array();
        $weekday_cds = array();
        $holiday_flags = array();
        $between_flags = array();

        $sep = self::DATE_SEPARATOR;
        // カレンダー開始日から終了日までを回します。
        for ($day = $fromCalendarDay; $day <= $toCalendarDay; $day = strtotime('+1 day', $day)) {
            $temp = date("Y{$sep}m{$sep}d", $day);
            // 日付
            $days[] = $temp;
            // 曜日
            $weekday_cds[] = date('w', $day);
            // 祝日
            if (in_array($temp, $holidays) !== FALSE) {
                $holiday_flags[] = '1';
            } else {
                $holiday_flags[] = '0';
            }
            // チェックボックス表示・非表示
            if ($day >= $fromDay && $day <= $toDay) {
                $between_flags[] = '1';
            } else {
                $between_flags[] = '0';
            }
        }
        return array('days'=>$days,
                         'weekday_cds'=>$weekday_cds,
                         'holiday_flags'=>$holiday_flags,
                         'between_flags'=>$between_flags);
    }

    /**
     * 開始日の年月から終了日までの年月のYYYYMM文字列のリストを生成します。
     *
     * @param integer $from 開始日のタイムスタンプ
     * @param integer $to 終了日のタイムスタンプ
     * @return array 開始日の年月から終了日までの年月のYYYYMM文字列のリスト
     */
    public function getYYYYMMList($from, $to)
    {
        $yyyymm = array();
        for ($d = $from; $d <= $to; $d = mktime(0, 0, 0, date("n", $d) + 1, 1, date("Y", $d))) {
            $yyyymm[] = date('Ym', $d);
        }
        return $yyyymm;
    }

    /**
     * 年月から月カレンダーの開始終了日を取得します。
     *
     * 左端が月曜日、右端が日曜日のカレンダーで
     * 前月、次月を含めて表示する開始終了日付を取得します。
     *
     * 例えば2010年4月の場合、2010/03/29と2010/05/02を返します。
     *
     * from = (対象月の1日) - 曜日コード + 1<br>
     * to = (対象月の末日) + (7 - 曜日コード) % 7<br>
     * となります。
     *
     * @param string $year 年文字列(YYYY)
     * @param string $month 月文字列(MM)
     * @return array
     * ['from'] integer 開始日タイムスタンプ
     * ['to'] integer 終了日タイムスタンプ
     */
    public function getMonthCalendarShowPeriod($year, $month)
    {
        $d = mktime(0, 0, 0, intval($month), 1, intval($year));
        $w = intval(date('w', $d));
        if ($w == 0) {
        	$from = mktime(0, 0, 0, date("n", $d), date("j", $d) - $w - 6, date("Y", $d));
        } else {
        	$from = mktime(0, 0, 0, date("n", $d), date("j", $d) - $w + 1, date("Y", $d));
        }

        $d = mktime(0, 0, 0, intval($month) + 1, 0, intval($year));
        $w = intval(date('w', $d));
        $to = mktime(0, 0, 0, date("n", $d), date("j", $d) + ((7 - $w) % 7), date("Y", $d));
        return array('from'=>$from,
                         'to'=>$to);
    }

}
?>
