<?php

/**
 * @package    ClassDefFile
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('csc/Common', 'CommonConst');
Sgmov_Lib::useForms(array('Error',));
Sgmov_Lib::useServices(array(
    'CostcoShohin', 'CostcoOption', 'CostcoDelivery'
));
Sgmov_Lib::useImageQRCode();
Sgmov_Lib::use3gMdk(array('3GPSMDK'));
/**#@-*/

/**
 * イベント手荷物受付サービスのお申し込み内容を登録し、完了画面を表示します。
 * @package    View
 * @subpackage CSC
 * @author     K.Sawada
 * @copyright  2021-2021 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Csc_GetShohinInfo extends Sgmov_View_Csc_Common
{

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $_CostcoShohin;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $_CostcoOption;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $_CostcoDelivery;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $_CostcoDeliveryFukusukonpo;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        $this->_CostcoShohin = new Sgmov_Service_CostcoShohin();
        $this->_CostcoOption = new Sgmov_Service_CostcoOption();
        $this->_CostcoDelivery = new Sgmov_Service_CostcoDelivery();
        $this->_CostcoDeliveryFukusukonpo = new Sgmov_Service_CostcoDeliveryFukusukonpo();
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

        @Sgmov_Component_Log::debug($this->getShohinInfo());
        return array(
            'status' => 'success',
            'message' => '商品取得処理に成功しました。',
            'res_data' => $this->getShohinInfo(),
        );
    }
}
