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
Sgmov_Lib::useView('cst/Common');
Sgmov_Lib::useServices(array('Comiket'));
/**#@-*/

/**
 * comiket_detailの no_chg_flg を更新します。
 * @package    View
 * @subpackage CST
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Cst_GetComiketInfo extends Sgmov_View_Cst_Common {
    
    public $_Comiket;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_Comiket = new Sgmov_Service_Comiket();
    }
    
    /**
     * 処理を実行します。
     *
     */
    public function executeInner() {
        
        $comiketId = filter_input(INPUT_GET, 'comiket_id');
        
        $comiketInfoForRes = array();
        $comiketId = $this->checkChkDComiketId($comiketId);
        
        if (@empty($comiketId)) {
            $comiketInfoForRes['status'] = '0';
            $comiketInfoForRes['message'] = 'パラメータ comiket_idに誤りがあります。';
            $reqXml = $this->array2string('response', $comiketInfoForRes);
            $reqXml = '<?xml version="1.0" encoding="UTF-8"?>' . $reqXml;
            echo $reqXml;
            exit;
        }
        
        // DB接続
        $db = Sgmov_Component_DB::getPublic();
        
        $comiketInfo = $this->_Comiket->fetchComiketByIdForApi($db, $comiketId);
        $comiketInfoForRes = array();
        if (empty($comiketInfo)) {
            $comiketInfoForRes['data'] = array();
            $comiketInfoForRes['status'] = '1';
            $comiketInfoForRes['message'] = '検索結果は０件です。';
        } else {
            $comiketInfoForRes['data']['comiket'] = $comiketInfo;
            $comiketInfoForRes['status'] = '1';
            $comiketInfoForRes['message'] = '取得成功しました。';
        }
        $reqXml = $this->array2string('response', $comiketInfoForRes);
        $reqXml = '<?xml version="1.0" encoding="UTF-8"?>' . $reqXml;
        
        echo $reqXml;
        exit;
    }
}