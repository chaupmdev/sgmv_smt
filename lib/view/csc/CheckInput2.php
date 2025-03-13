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
Sgmov_Lib::useForms(array('Error', ));
Sgmov_Lib::useServices(array(
));
Sgmov_Lib::useImageQRCode();
Sgmov_Lib::use3gMdk(array('3GPSMDK'));
/**#@-*/

/**
 * コストコ配送サービスの入力バリデーション
 * @package    View
 * @subpackage CSC
 * @author     K.Sawada
 * @copyright  2021-2021 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Csc_CheckInput2 extends Sgmov_View_Csc_Common
{



    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * フォーム値の入力バリデーションを行いセッションにセットする
     */
    public function executeInner()
    {

        Sgmov_Component_Log::debug("======================================================================================");
        @Sgmov_Component_Log::debug($_POST);
        Sgmov_Component_Log::debug("======================================================================================");

        if (@empty($_SESSION["CSC"])) {
            $_SESSION["CSC"] = array();
        }

        // セッションに入力値をセット
        $_SESSION["CSC"]["INPUT_INFO"] = $_POST;
        // 商品情報取得
        $shohinInfo = $this->getShohinInfo($_POST);
        //GiapLN fix bug 2022.11.8 
        //$dataRes = $this->getDataTypeOptionCd($shohinInfo);
        //$_SESSION["CSC"]["INPUT_INFO"]['c_option_cd_type'] = $dataRes['type'];
        if (isset($shohinInfo['option']) && !empty($shohinInfo['option'])) {
            $_SESSION["CSC"]["INPUT_INFO"]['c_option_cd_type'] = 2;//オプションが縦並びに対応する   
        } else {
            $_SESSION["CSC"]["INPUT_INFO"]['c_option_cd_type'] = 0;
        }
        $_SESSION["CSC"]["SHOHIN_INFO"] = $shohinInfo;

        // 入力バリデーション
        $errorInfo = $this->checkInput($_POST);
        if (@!empty($errorInfo)) {
            $_SESSION["CSC"]["ERROR_INFO"] = $errorInfo;
            @Sgmov_Component_Redirect::redirectPublicSsl("/csc/input/" . $_POST['c_eventsub_id']);
            exit;
        } else {
            @Sgmov_Component_Redirect::redirectPublicSsl("/csc/confirm");
            exit;
        }

        return array(
            'status' => 'success',
            'message' => 'チェック処理が成功しました。',
            'res_data' => $errorInfo,
        );

    }
    public function getDataTypeOptionCd($shohinInfoRes) {

        $data = [];
        $data['type'] = 1;
        $options = $shohinInfoRes['option'];
        $counts = [];
        foreach ($options as $row) {
            if (isset($counts[$row['yumusyou_kbn']])) {
                $counts[$row['yumusyou_kbn']]++;
            } else {
                $counts[$row['yumusyou_kbn']] = 1;
            }
        }
        $count0 = isset($counts['0']) ? $counts['0'] : 0;
        $count1 = isset($counts['1']) ? $counts[1] : 0;
        if ($count0 > 1 || $count1 > 1) {
            $data['type'] = 2;
        }

        
        return $data;
    }

}
