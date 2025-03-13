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
class Sgmov_View_Csc_Edit extends Sgmov_View_Csc_Common
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
        
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            @Sgmov_Component_Redirect::redirectPublicSsl("/csc/mst_shohin_list");
        }
        
        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        $shohin = $this->_CostcoShohin->getById($db, $_GET['id']);

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
            $request = $_POST;
            
            // Base on business, add start_date, end_date into array $request to use for validation
            $dbStartDt = explode(' ', $shohin['start_date']);
            $dbEndDt = explode(' ', $shohin['end_date']);
            $currDt = date('Y-m-d');
            if ($dbStartDt[0] >= $currDt) {
                $request['start_date'] = $dbStartDt[0];
                $request['end_date'] = $dbEndDt[0];
            } else {
                $request['start_date'] = $currDt;
                $request['end_date'] = $this->_DefaultEndDate;
            }

            $errors = $this->checkMstInput($request, $shohin);
            if (@!empty($errors)) {
                $_SESSION["CSC"]["INPUT_MST_SHOHIN"] = $_POST;
                $_SESSION["CSC"]["ERROR_MST_SHOHIN_INFO"] = $errors;
                @Sgmov_Component_Redirect::redirectPublicSsl("/csc/edit?id=" . $_GET['id']);
            } else {
                try {
                    if ($dbStartDt[0] >= $currDt) {
                        $edit = $this->editCurrentRecord($db, $_POST, $_GET['id']);
                        if ($edit) {
                            // @Sgmov_Component_Redirect::redirectPublicSsl("/csc/mst_shohin_list");
                            $_SESSION["CSC"]["SUCCESS"] = 1;
                            @Sgmov_Component_Redirect::redirectPublicSsl("/csc/edit?id=" . $_GET['id']);
                        }
                        exit;

                    } else {
                        $db->begin();
                        $archive = $this->archiveCurrentRecord($db, $_GET['id']);
                        $create = $this->createNewRecord($db, $_POST);
                        if ($archive && $create) {
                            $db->commit();
                            $_SESSION["CSC"]["SUCCESS"] = 1;
                            //GiapLN fix bug #SMT6-380 2022/12/15
                            $_SESSION["CSC"]["SHOHIN_SEARCH_LIST"]['date_valid'] = $currDt;
                            @Sgmov_Component_Redirect::redirectPublicSsl("/csc/edit?id=" . $_GET['id']);
                        } else {
                            $db->rollback();
                        }
                        exit;
                    }
                    
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
                'id' => $_GET['id'],
                'shohin' => $shohin,
                'costco_options' => $costcoOptions,
                'input_info' => @$_SESSION["CSC"]["INPUT_MST_SHOHIN"],
                'error_info' => @$_SESSION["CSC"]['ERROR_MST_SHOHIN_INFO'],
            )
        );

    }

    private function editCurrentRecord($db, $post, $currId) {
        $request = $post;

        $request['size'] = (float) $_POST['size']; 
        $request['option_id'] = (int) $_POST['option_id']; 
        $request['data_type'] = (int) $_POST['data_type']; 
        $request['juryo'] = (int) $_POST['juryo']; 
        $request['konposu'] = (int) $_POST['konposu']; 
        $edit = $this->_CostcoShohin->edit($db, $request, $_GET['id']);
        
        return $edit ? true : false;
    }

    private function archiveCurrentRecord($db, $currId) {
        $request['end_date'] = date('Y-m-d', strtotime("-1 days")) . ' 00:00:00';
        $archive = $this->_CostcoShohin->archive($db, $request, $currId);
        return $archive ? true : false;
    }

    private function createNewRecord($db, $post) {
        $request = $post;
        $request['size'] = (float) $post['size']; 
        $request['option_id'] = (int) $post['option_id']; 
        $request['data_type'] = (int) $post['data_type']; 
        $request['juryo'] = (int) $post['juryo']; 
        $request['konposu'] = (int) $post['konposu']; 
        $request['start_date'] = date('Y-m-d') . ' 00:00:00'; 
        $request['end_date'] = $this->_DefaultEndDate . ' 00:00:00';

        $ins = $this->_CostcoShohin->insert($db, $request);
        return $ins ? true : false;
    }

}
