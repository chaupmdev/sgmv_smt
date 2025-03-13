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
Sgmov_Lib::useView('mlk/Common');
Sgmov_Lib::useForms(array('Error',));

/**#@-*/

/**
 * イベント手荷物受付サービスのお申し込み内容を登録し、完了画面を表示します。
 * @package    View
 * @subpackage CSC
 * @author     K.Sawada
 * @copyright  2021-2021 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Csc_GetCommmiket extends Sgmov_View_Eve_Common
{
    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function executeInner()
    {
        return array(
            'status' => 'success',
            'message' => '商品取得処理に成功しました。',
            'res_data' => $this->getCommiketInfo(),
        );
    }
}
