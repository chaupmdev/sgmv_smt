<?php
/**
 * @package    ClassDefFile
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useServices(array('Comiket',));
Sgmov_Lib::useView('evp/Common');
Sgmov_Lib::useForms(array('Error', 'EveSession', 'Eve001Out', 'Eve002In'));
/**#@-*/

/**
 * コミケサービスのお申し込み入力画面を表示します。
 * @package    View
 * @subpackage EVE
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Evp_BuildingBoothPosition extends Sgmov_View_Evp_Common {

    /**
     * 共通サービス
     * @var Sgmov_Service_AppCommon
     */
    protected $_appCommon;


    /**
     * イベントサービス
     * @var Sgmov_Service_Event
     */
    protected $_EventService;

    /**
     * イベントサブサービス
     * @var Sgmov_Service_Eventsub
     */
    protected $_EventsubService;

    /**
     * 館マスタサービス(ブース番号)
     * @var type
     */
    protected $_BuildingService;


    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_appCommon             = new Sgmov_Service_AppCommon();
        $this->_EventService          = new Sgmov_Service_Event();
        $this->_EventsubService       = new Sgmov_Service_Eventsub();
        $this->_BuildingService       = new Sgmov_Service_Building();

        parent::__construct();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * セッションに情報があるかどうかを確認
     * </li><li>
     * 情報有り
     *   <ol><li>
     *   セッション情報を元に出力情報を作成
     *   </li></ol>
     * </li><li>
     * 情報無し
     *   <ol><li>
     *   出力情報を設定
     *   </li></ol>
     * </li><li>
     * テンプレート用の値をセット
     * </li><li>
     * チケット発行
     * </li></ol>
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['ticket']:チケット文字列
     * </li><li>
     * ['outForm']:出力フォーム
     * </li><li>
     * ['errorForm']:エラーフォーム
     * </li></ul>
     */
    public function executeInner() {

        Sgmov_Component_Log::debug('############################ ZZZZZZZZZZZZZZZZZZZ');

        $featureId            = filter_input(INPUT_POST, 'featureId');
        $fromGamenId          = filter_input(INPUT_POST, 'id');
        $ticket               = filter_input(INPUT_POST, 'ticket');
        $eventSubId           = filter_input(INPUT_POST, 'eventsub_sel'); // イベントサブID
        $buildingCdAndName            = filter_input(INPUT_POST, 'building_cd_and_name'); // 集荷元JIS5（集荷元都道府県の選択）

        // チケット確認
        $this->_checkSession($featureId, $fromGamenId, $ticket);

        // DB接続
        $db = Sgmov_Component_DB::getPublic();

        $buildingCdAndNameExplo = explode(",", $buildingCdAndName);

        $buildingCd = '';
        if (@!empty($buildingCdAndNameExplo[0])) {
            $buildingCd = $buildingCdAndNameExplo[0];
        }

        $buildingName = '';
        if (@!empty($buildingCdAndNameExplo[1])) {
            $buildingName = $buildingCdAndNameExplo[1];
        }

        $boothPositionList = $this->_BuildingService->getBoothPositionByCdAndName($db, $buildingCd, $buildingName, $eventSubId);

        return array(
            'boothPositionList' => $boothPositionList
        );
    }

    // /**
    //  * 入力フォームの値を出力フォームを生成します。
    //  * @param Sgmov_Form_Eve001In $inForm 入力フォーム
    //  * @return Sgmov_Form_Eve001Out 出力フォーム
    //  */
    // protected function _createOutFormByInForm($inForm, $param=NULL) {
    //     $inForm = (array)$inForm;
    //     return $this->createOutFormByInForm($inForm, new Sgmov_Form_Eve001Out());
    // }

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