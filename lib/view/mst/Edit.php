<?php
/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useServices(array('Comiket', 'Event'));
Sgmov_Lib::useView('Public');
/**#@-*/

/**
 * コミケサービスのお申し込み入力画面を表示します。
 * @package    View
 * @subpackage DSN
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Mst_Edit extends Sgmov_View_Public{


	/**
     * イベントサービス
     * @var Sgmov_Service_Event
     */
    protected $_EventService;

    public function __construct() {
        $this->_EventService          = new Sgmov_Service_Event();
    }

 	public function executeInner() {

 		// パラメータ
        $param = filter_input(INPUT_GET, 'param');
        if (empty($param)) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
        } else {
        	$splitParam = explode("/", $param);
        	
        	// イベント識別子
            if(!isset($splitParam)){
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
            }

            $shikibetsushi = $splitParam[0];

            // 識別子 is_stringチェック
            if (!is_string($shikibetsushi)) {                
                Sgmov_Component_Log::debug ( '文字値ではない' );
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
            }

            // DB接続
        	$db = Sgmov_Component_DB::getPublic();
            $eventInfo = $this->_EventService->fetchEventByShikibetsushi($db, $shikibetsushi);

            if (empty($eventInfo)) {
                $title = "対象のデータが見つかりません。";
                $message = urlencode("対象のデータが見つかりません。");
                Sgmov_Component_Redirect::redirectPublicSsl("/msb/error?t={$title}&m={$message}");
            } else {
            	 return array(
		            'event'    => $eventInfo,
        		);
            }
        }
 	}
}