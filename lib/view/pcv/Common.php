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
Sgmov_Lib::useServices(array('CenterArea','CoursePlan','Prefecture','VisitEstimate','Yubin'));
Sgmov_Lib::useView('Public');
/**#@-*/

 /**
 * 法人引越輸送フォームの共通処理を管理する抽象クラスです。
 * @package    View
 * @subpackage PCV
 * @author     K.Hamada(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Pcv_Common extends Sgmov_View_Public
{
    /**
     * 機能ID
     */
    const FEATURE_ID = 'PCV';

    /**
     * PCV001の画面ID
     */
    const GAMEN_ID_PCV001 = 'PCV001';

    /**
     * PCV002の画面ID
     */
    const GAMEN_ID_PCV002 = 'PCV002';

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
     * 2010年から翌年までの年の配列を生成します。
     *
     * 先頭には空白の項目を含みます。
     *
     * @return array 2009年から今年までの年の配列
     */
    public function getYears()
    {

        $years = array();
        $years[] = '';

        $min_year = date('Y');
        $max_year = $min_year + 1;

        for ($i = $min_year; $i <= $max_year; $i++) {
            $years[] = sprintf('%04d', $i);
        }

        return $years;
    }

    /**
     * エレベーター選択値
     * @var array
     */
    public $elevator_lbls = array(''=>'',
                                         '0'=>'なし',
                                         '1'=>'あり');

    /**
     * 住居前道幅選択値
     * @var array
     */
    public $road_lbls = array(''=>'',
                                         '1'=>'車両通行不可',
                                         '2'=>'1台通行可',
                                         '3'=>'2台すれ違い可');

    /**
     * 電話種類コード選択値
     * @var array
     */
    public $tel_type_lbls = array(''=>'',
                                         '1'=>'携帯',
                                         '2'=>'勤務先',
                                         '3'=>'その他');

    /**
     * 電話連絡可能コード値
     * @var array
     */
    public $contact_available_lbls = array(''=>'',
                                         '1'=>'時間指定',
                                         '2'=>'終日OK');

    /**
     * 電話連絡開始時刻コード値
     * @var array
     */
    public $contact_start_lbls = array(''=>'',
                             '00'=>'00時',
                             '01'=>'01時',
                             '02'=>'02時',
                             '03'=>'03時',
                             '04'=>'04時',
                             '05'=>'05時',
                             '06'=>'06時',
                             '07'=>'07時',
                             '08'=>'08時',
                             '09'=>'09時',
                             '10'=>'10時',
                             '11'=>'11時',
                             '12'=>'12時',
                             '13'=>'13時',
                             '14'=>'14時',
                             '15'=>'15時',
                             '16'=>'16時',
                             '17'=>'17時',
                             '18'=>'18時',
                             '19'=>'19時',
                             '20'=>'20時',
                             '21'=>'21時',
                             '22'=>'22時',
                             '23'=>'23時');

    /**
     * 電話連絡終了時刻コード値
     * @var array
     */
    public $contact_end_lbls = array(''=>'',
                             '00'=>'00時',
                             '01'=>'01時',
                             '02'=>'02時',
                             '03'=>'03時',
                             '04'=>'04時',
                             '05'=>'05時',
                             '06'=>'06時',
                             '07'=>'07時',
                             '08'=>'08時',
                             '09'=>'09時',
                             '10'=>'10時',
                             '11'=>'11時',
                             '12'=>'12時',
                             '13'=>'13時',
                             '14'=>'14時',
                             '15'=>'15時',
                             '16'=>'16時',
                             '17'=>'17時',
                             '18'=>'18時',
                             '19'=>'19時',
                             '20'=>'20時',
                             '21'=>'21時',
                             '22'=>'22時',
                             '23'=>'23時');

    /**
     * 連絡方法コード選択値
     * @var array
     */
    public $contact_method_lbls = array(''=>'',
                                         '1'=>'電話',
                                         '2'=>'メール');
}
?>