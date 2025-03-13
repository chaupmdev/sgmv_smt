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
Sgmov_Lib::useServices(array('ComiketDetail'));
/**#@-*/

/**
 * comiket_detailの no_chg_flg を更新します。
 * @package    View
 * @subpackage CST
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Cst_UpdateComiketNoChgFlg extends Sgmov_View_Cst_Common {
    
    /**
     *
     * @var type 
     */
    public $_ComiketDetail;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_ComiketDetail = new Sgmov_Service_ComiketDetail();
    }

    /**
     * 処理を実行します。
     *
     */
    public function executeInner() {
        $comiketId = filter_input(INPUT_GET, 'comiket_id');
        
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
        
        $responceList = array();
        try {
            $res = $this->_ComiketDetail->updateNoChgFlg($db, $comiketId);
            if (@empty($res)) {
                throw new Exception();
            } else {
                $responceList['status'] = '1';
                $responceList['message'] = '更新成功しました';
            }
        } catch (Exception $e) {
            $responceList['status'] = '0';
            $responceList['message'] = '更新失敗しました。';
        }
Sgmov_Component_Log::debug('################# 55555555555-2');

        $reqXml = $this->array2string('response', $responceList);
        $reqXml = '<?xml version="1.0" encoding="UTF-8"?>' . $reqXml;
        
        echo $reqXml;
        exit;
    }

    /**
     * チケットの確認を行います。
     * TODO ybn/SearchAddressと同記述あり
     */
    public function _checkSession($featureId, $fromGamenId, $ticket) {
        // セッション
        $session = Sgmov_Component_Session::get();

        // チケットの確認
        if (!isset($_SESSION[Sgmov_Component_Session::_KEY_TICKETS])) {
            Sgmov_Component_Log::warning('【ツアー会社検索 不正使用】チケットが存在していません。');
            header('HTTP/1.0 404 Not Found');
            exit;
        }

        $tickets = &$_SESSION[Sgmov_Component_Session::_KEY_TICKETS];
        if (!isset($tickets[$featureId]) || $tickets[$featureId] !== $fromGamenId . $ticket) {
            Sgmov_Component_Log::warning('【ツアー会社検索 不正使用】チケットが不正です。　'.$tickets[$featureId].' <=> '.$fromGamenId . $ticket);
            header('HTTP/1.0 404 Not Found');
            exit;
        } else {
            Sgmov_Component_Log::debug('ツアー会社検索実行 機能ID=>'.$tickets[$featureId].' <=> '.$fromGamenId . $ticket);
        }
    }
}