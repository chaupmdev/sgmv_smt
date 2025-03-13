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
Sgmov_Lib::useView('pcr/Common', 'CommonConst');
Sgmov_Lib::useForms(array('Error', 'PcrSession', 'Pcr004Out'));
Sgmov_Lib::useServices(array('Cruise', 'CenterMail'));
Sgmov_Lib::use3gMdk(array('3GPSMDK'));
/**#@-*/

/**
 * 旅客手荷物受付サービスのお申し込み件数を表示します。
 * @package    View
 * @subpackage PCR
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pcr_Ct extends Sgmov_View_Pcr_Common {

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * セッションの継続を確認
     * </li><li>
     * チケットの確認と破棄
     * </li><li>
     * 入力チェック
     * </li><li>
     * セッションから情報を取得
     * </li><li>
     * 情報をDBへ格納
     * </li><li>
     * 出力情報を設定
     * </li><li>
     * セッション情報を破棄
     * </li></ol>
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['outForm']:出力フォーム
     * </li></ul>
     */
    public function executeInner() {

        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        $query = '
            SELECT
                SUM(merchant_result) AS s,
                COUNT(*)             AS c,
                SUM(CASE send_result WHEN 0 THEN 1 ELSE 0 END) AS b
            FROM
                cruise;';

        $db->begin();
        $data = $db->executeQuery($query);
        $db->commit();
        $row = $data->get(0);

        header('Content-Type: text/plain; charset=UTF-8');
        echo $row['s'] . '(' . $row['c'] . ')(' . $row['b'] . ')';
    }
}