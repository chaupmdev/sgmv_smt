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
    'Event', 'Eventsub', 'Prefecture', 'CostcoDataDisplay',
    'CostcoLeadTime', 'EventBusinessHoliday'
));
/**#@-*/

/**
 * コストコ配送サービスの申込入力画面表示
 * @package    View
 * @subpackage CSC
 * @author     K.Sawada
 * @copyright  2021-2021 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Csc_Create extends Sgmov_View_Csc_Common
{
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $_CostcoShohin;

    private $_DefaultEndDate = '9999-12-31';

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        $this->_CostcoShohin = new Sgmov_Service_CostcoShohin();
        parent::__construct();
    }

    /**
     * 処理を実行します。
     */
    public function executeInner()
    {

        Sgmov_Component_Log::debug("======================================================================================");
        @Sgmov_Component_Log::debug($_GET);
        @Sgmov_Component_Log::debug($_POST);
        Sgmov_Component_Log::debug("======================================================================================");

        // セッションに情報があるかどうかを確認
        $session = Sgmov_Component_Session::get();

        if (@empty($_SESSION["CSC"])) {
            $_SESSION["CSC"] = array();
        }

        if(@empty($_SERVER["HTTP_REFERER"]) || strpos($_SERVER["HTTP_REFERER"], "/csc/") == false) {
            // セッション情報を破棄
            $session->deleteForm(self::FEATURE_ID);
            $_SESSION["CSC"] = array();
        }

        $costcoOptions = $this->getCostcoOptions();
        
        if (!empty($_POST)) {
            $_SESSION["CSC"]["INPUT_MST_SHOHIN"] = $_POST;
            $request = $_POST;
            // Add end_date into array $request to use for validation
            $request['end_date'] = $this->_DefaultEndDate;
            $errors = $this->checkMstInput($request, array());
            if (@!empty($errors)) {
                $_SESSION["CSC"]["ERROR_MST_SHOHIN_INFO"] = $errors;
                @Sgmov_Component_Redirect::redirectPublicSsl("/csc/create");
                exit;
            } else {
                try {
                    // DBへ接続
                    $db = Sgmov_Component_DB::getPublic();
                    $request['size'] = (float) $_POST['size']; 
                    $request['option_id'] = (int) $_POST['option_id']; 
                    $request['data_type'] = (int) $_POST['data_type']; 
                    $request['juryo'] = (int) $_POST['juryo']; 
                    $request['konposu'] = (int) $_POST['konposu']; 
                    $request['start_date'] = $_POST['start_date'] . ' 00:00:00'; 
                    $request['end_date'] = $this->_DefaultEndDate . ' 00:00:00';
                    $ins = $this->_CostcoShohin->insert($db, $request);
                    if ($ins) {
                        // @Sgmov_Component_Redirect::redirectPublicSsl("/csc/mst_shohin_list");
                        $_SESSION["CSC"]["SUCCESS"] = 1;
                        //GiapLN fix bug #SMT6-380 2022/12/15
                        $_SESSION["CSC"]["SHOHIN_SEARCH_LIST"]['date_valid'] = $_POST['start_date'];
                        @Sgmov_Component_Redirect::redirectPublicSsl("/csc/create");
                    }
                    exit;
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
        }

        $randKeyForXss = md5(uniqid());

        $_SESSION['RAND_KEY_FOR_XSS'] =  $randKeyForXss;
        setcookie('RAND_KEY_FOR_XSS', $randKeyForXss, time()+60*60*2, '/'); // 2時間で設定

        return array(
            'status' => 'success',
            'message' => '初期情報処理に成功しました。',
            'res_data' => array(
                'costco_options' => $costcoOptions,
                'input_info' => @$_SESSION["CSC"]["INPUT_MST_SHOHIN"],
                'error_info' => @$_SESSION["CSC"]['ERROR_MST_SHOHIN_INFO'],
            )
        );

    }

}
