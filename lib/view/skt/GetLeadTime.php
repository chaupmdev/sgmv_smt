<?php

/**
 * @package    ClassDefFile
 * @author     Y.Fujikawa
 * @copyright  2022-2022 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('csc/Common', 'CommonConst');
Sgmov_Lib::useForms(array('Error',));
Sgmov_Lib::useServices(array(
    'CostcoLeadTime'
));
Sgmov_Lib::useImageQRCode();
Sgmov_Lib::use3gMdk(array('3GPSMDK'));
/**#@-*/

/**
 * 配達希望日のリードタイムを取得します
 * @package    View
 * @subpackage CSC
 * @author     Y.Fujikawa
 * @copyright  2022-2022 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Csc_GetLeadTime extends Sgmov_View_Csc_Common
{

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $_CostcoLeadTime;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        $this->_CostcoLeadTime = new Sgmov_Service_CostcoLeadTime();
        parent::__construct();
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
    public function executeInner()
    {

        Sgmov_Component_Log::debug("======================================================================================");
        @Sgmov_Component_Log::debug($_POST);
        Sgmov_Component_Log::debug("======================================================================================");

        @Sgmov_Component_Log::debug($this->getLeadTime());

        return array(
            'status' => 'success',
            'message' => 'リードタイムの取得に成功しました。',
            'res_data' => $this->getLeadTime(),
        );

    }
}
