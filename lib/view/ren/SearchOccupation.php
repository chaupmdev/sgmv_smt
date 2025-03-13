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
Sgmov_Lib::useAllComponents();
Sgmov_Lib::useView('Public');
Sgmov_Lib::useServices(array('Occupation'));
/**#@-*/

/**
 * イベントIDからブース情報を検索して返します。
 * @package    View
 * @subpackage EVB
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Ren_SearchOccupation extends Sgmov_View_Public {

    /**
     * 営業所
     * @var type 
     */
    private $_OccupationService;
    

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_OccupationService     = new Sgmov_Service_Occupation();
    }

    /**
     * 処理を実行します。
     *
     */
    public function executeInner() {
        
        //$employ_cd = filter_input(INPUT_POST, 'employ_cd');
        $center_id = filter_input(INPUT_POST, 'center_id');

Sgmov_Component_Log::debug($center_id);
Sgmov_Component_Log::debug($_POST);


        // // チケット確認
        // $this->_checkSession($featureId, $fromGamenId, $ticket);
        $result = array();
        try {
            // DB接続
            $db = Sgmov_Component_DB::getPublic();
            $result = [];// $this->_OccupationService->fetchOccupationByEmpCdAndEigyoCd($db, $employ_cd, $center_id);
        }catch(Exeception $e){

        }   

        return $result;
    }

    
}