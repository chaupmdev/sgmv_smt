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
Sgmov_Lib::useServices(array('Box','AlpenBox'));
/**#@-*/

/**
 * イベントIDからブース情報を検索して返します。
 * @package    View
 * @subpackage EVB
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Evb_SearchBox extends Sgmov_View_Public {

    /**
     * 宅配箱マスタサービス
     * @var type 
     */
    private $_BoxService;

    // アルペン宅配箱マスタサービス
    private $_AlpenBoxService;
    
    /**
     * ツアーサービス
     * @var Sgmov_Service_Travel
     */
//    public $_TravelService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_BoxService      = new Sgmov_Service_Box();
        $this->_AlpenBoxService = new Sgmov_Service_AlpenBox();
//        $this->_TravelService = new Sgmov_Service_Travel();
    }

    /**
     * イベント識別子で参照するボックスマスタを切り替える
     * 以降の実装で参照するボックスマスタが変わる場合は
     * このメソッドに追記してください
     */
    public function setBoxService($featureId) {
        // アルペンの場合
        if($featureId=='ALP'){
            return $this->_AlpenBoxService = new Sgmov_Service_AlpenBox();
        }

        // アルペン以外のイベント(コミケ、デザインフェスタ)やコストコなど
        return $this->_BoxService = new Sgmov_Service_Box();
    }



    /**
     * 処理を実行します。
     *
     */
    public function executeInner() {

        // イベント識別子：DSN、EVP、CSC等 Common.phpのFEATURE_IDで定義した値
        $featureId            = filter_input(INPUT_POST, 'featureId');
        // 画面ID：DSN001、EVP001、 CSC001 Common.phpで定義した値
        $fromGamenId          = filter_input(INPUT_POST, 'id');
        // 発行チケット：41f2e802d0fdf6d692ca54868bd613f8等
        $ticket               = filter_input(INPUT_POST, 'ticket');
        // イベントサブID
        $eventsubId = filter_input(INPUT_POST, 'eventsub_sel');
        // 識別子(1:個人、2:法人、3：設置)
        $comiketDiv = filter_input(INPUT_POST, 'comiket_div');
        // 往復区分(1：往路、2：復路、3:ミルクラン(往復)、4:手荷物、5:物販)
        $comiketDetailTypeSel = filter_input(INPUT_POST, 'comiket_detail_type_sel');
        // 顧客区分（1:出展者様、2:一般のご利用者様（来場者様））
        $comiketCustomerKbn = @filter_input(INPUT_POST, 'comiket_customer_kbn_sel');
        $comiketDetailOutInBinshuKbn = '';

        // チケット確認
        $this->_checkSession($featureId, $fromGamenId, $ticket);
        
        $returnList = array(
            'outbound' => array(),
            'inbound' => array(),
        );
        
        if ($comiketDetailTypeSel == '1') { // 搬入
            $comiketDetailOutInBinshuKbn = @filter_input(INPUT_POST, 'comiket_detail_outbound_binshu_kbn_sel');
            if (@empty($comiketDetailOutInBinshuKbn) && $comiketDetailOutInBinshuKbn != '0') {
                return $returnList;
            }
        } 
        else if ($comiketDetailTypeSel == '2') { // 搬出
            $comiketDetailOutInBinshuKbn = @filter_input(INPUT_POST, 'comiket_detail_inbound_binshu_kbn_sel');
            if (@empty($comiketDetailOutInBinshuKbn) && $comiketDetailOutInBinshuKbn != '0') {
                return $returnList;
            }

        }

        if(empty($eventsubId) || empty($comiketDiv)  || empty($comiketDetailTypeSel) ) {
            return $returnList;
        }

        $resultList = array();
        try {
            // DB接続
            $db = Sgmov_Component_DB::getPublic();
            $returnListOutbound = array();
            $returnListInbound = array();
            $returnListBuppan = array();
            // イベント識別子で取得するボックスマスタを切り替える
            $boxService=$this->setBoxService($featureId);

            if($comiketDetailTypeSel == "3") { // 搬入と搬出
                $returnListOutbound = $boxService->fetchBox2($db, $eventsubId, $comiketDiv, "1", $comiketCustomerKbn, $comiketDetailOutInBinshuKbn); // 搬入
                $returnListInbound = $boxService->fetchBox2($db, $eventsubId, $comiketDiv, "2", $comiketCustomerKbn, $comiketDetailOutInBinshuKbn); // 搬出
            } else if($comiketDetailTypeSel == "1") { // 搬入
                $returnListOutbound = $boxService->fetchBox2($db, $eventsubId, $comiketDiv, "1", $comiketCustomerKbn, $comiketDetailOutInBinshuKbn); // 搬入
            } else if($comiketDetailTypeSel == "2") { // 搬出
                $returnListInbound = $boxService->fetchBox2($db, $eventsubId, $comiketDiv, "2", $comiketCustomerKbn, $comiketDetailOutInBinshuKbn); // 搬出
            }

            // 搬入以外の場合
            if($comiketDetailTypeSel !== "1" ){
                $returnListBuppan = $this->_BoxService->fetchBox2($db, $eventsubId, $comiketDiv, "5", $comiketCustomerKbn, $comiketDetailOutInBinshuKbn); // 物販
            }

            $resultList = array(
                "outbound" => $returnListOutbound,
                "inbound" => $returnListInbound,
                "buppan" => $returnListBuppan,
            );

        }
        catch (exception $e) {
        }
        return $resultList;
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