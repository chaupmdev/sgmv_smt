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
    'Prefecture'
));
/**#@-*/

/**
 * 配達希望日のリードタイムを取得します
 * @package    View
 * @subpackage CSC
 * @author     FPT-AnNV6
 * @copyright  2022-2022 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Csc_CheckAddress extends Sgmov_View_Csc_Common
{

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $_PrefectureService;
    private $_CostcoHaisokanoJis5Service;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        $this->_PrefectureService = new Sgmov_Service_Prefecture();
        $this->_CostcoHaisokanoJis5Service = new Sgmov_Service_CostcoHaisokanoJis5();
        parent::__construct();
    }

    public function executeInner()
    {
        Sgmov_Component_Log::debug("======================================================================================");
        @Sgmov_Component_Log::debug($_POST);
        Sgmov_Component_Log::debug("======================================================================================");
        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        $prefecturesSelected = $this->_PrefectureService->fetchPrefecturesById($db, $_POST['d_pref_id']);
        $inputAddress = @$prefecturesSelected["name"] . @$_POST['d_address'] . @$_POST['d_building'];
        $resultZipDll = $this->_getAddress($_POST['l_zip1'] . $_POST['l_zip2'], @$inputAddress);
        $jis5Info = $this->_CostcoHaisokanoJis5Service->getInfo($db, $_POST['c_event_id'], $_POST['c_eventsub_id'], @$resultZipDll["JIS5Code"]);
        //リードタイムマスタから取得出来ない又はJIS5を取得出来ない場合、jis5が見つかないとなります。
        $leadTime = $this->getLeadTime();
        if (@empty($jis5Info) || @empty($leadTime)) {
            return array(
                'status' => '404',
                'message' => 'jis5 not found',
                'res_data' => $_POST,
            );
        }
        return array(
            'status' => '200',
            'message' => '',
            'res_data' => $_POST,
        );

    }
}
