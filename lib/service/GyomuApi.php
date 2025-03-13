<?php
/**
 * @package    ClassDefFile
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
/**#@-*/

/**
 * 業務連携請求書問番を取得
 *
 * @package Service
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_GyomuApi {
    
    private $WS_HOST;
    private $WS_PROTOCOL;
    private $WS_PORT;
    private $WS_USER_ID;
    private $WS_PASSWORD;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->WS_PROTOCOL = Sgmov_Component_Config::getWsProtocol();
        $this->WS_HOST = Sgmov_Component_Config::getWsHost();
        $this->WS_PORT = Sgmov_Component_Config::getWsPort();
        $this->WS_USER_ID = Sgmov_Component_Config::getWsUserId();
        $this->WS_PASSWORD = Sgmov_Component_Config::getWsPassword();
    }
    
    /**
     * 
     * @return string
     */
    public function getToiawaseNo() {
Sgmov_Component_Log::info('################ getToiawaseNo - start');
        $wsToiawaseNoPath = Sgmov_Component_Config::getWsToiawaseNoPath();
        
        if ($this->WS_PORT == '443') {
            $wsProtocol = 'https';
        } else {
            $wsProtocol = 'http';
        }
        $wsUrl =  "{$wsProtocol}://{$this->WS_HOST}{$wsToiawaseNoPath}?user_id={$this->WS_USER_ID}&password={$this->WS_PASSWORD}";
Sgmov_Component_Log::info($wsUrl);
        $responseJson = @file_get_contents($wsUrl);
        if (!$responseJson || @empty($responseJson)) {
            $errResponse = array();
            $errResponse['result'] = 9;
            $errResponse['error_message'] = 'php側のfile_get_contentsのレスポンスが空(もしくはNULL)です';
            $errResponse['error_message'] = "[接続URL] {$wsUrl}" ;
            
            return $errResponse;
        }
        
Sgmov_Component_Log::info($responseJson);
        $response = json_decode($responseJson, true);
        
Sgmov_Component_Log::info('################ getToiawaseNo - end');
        return $response;
    }
}