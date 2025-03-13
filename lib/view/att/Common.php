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
Sgmov_Lib::useServices(array('Login', 'Prefecture', 'TravelAgency', 'Travel', 'TravelTerminal'));
Sgmov_Lib::useView('Maintenance');
/**#@-*/

/**
 * ツアー発着地マスタメンテナンスの共通処理を管理する抽象クラスです。
 * @package    View
 * @subpackage ATT
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Att_Common extends Sgmov_View_Maintenance {

    /**
     * 機能ID
     */
    const FEATURE_ID      = 'ATT';

    /**
     * ATT001の画面ID
     */
    const GAMEN_ID_ATT001 = 'ATT001';

    /**
     * ATT002の画面ID
     */
    const GAMEN_ID_ATT002 = 'ATT002';

    /**
     * 集荷の往復コード選択値
     * @var array
     */
    public $terminal_lbls = array(
        1 => '出発地の選択肢に表示する',
        2 => '到着地の選択肢に表示する ',
        3 => '出発地・到着地の両方の選択肢に表示する ',
    );

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
}