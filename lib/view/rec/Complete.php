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
Sgmov_Lib::useView('rec/Common', 'CommonConst');
Sgmov_Lib::useForms(array('Error', 'RecSession'));
Sgmov_Lib::useServices(array('HttpsZipCodeDll', 'EmployRegist', 'CenterMail'));
/**#@-*/

/**
 * イベント手荷物受付サービスのお申し込み内容を登録し、完了画面を表示します。
 * @package    View
 * @subpackage EVE
 * @author     K.Sawada
 * @copyright  2018-2019 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Rec_Complete extends Sgmov_View_Rec_Common {

    /**
     * サービス
     * @var Sgmov_Service_Employ
     */
    private $_EmployRegistService;

    /**
     * 都道府県サービス
     * @var Sgmov_Service_Prefecture
     */
    private $_PrefectureService;

    /**
     * 拠点メールアドレスサービス
     * @var Sgmov_Service_CenterMail
     */
    public $_centerMailService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {

        $this->_EmployRegistService   = new Sgmov_Service_EmployRegist();

        $this->_PrefectureService   = new Sgmov_Service_Prefecture();

        $this->_centerMailService   = new Sgmov_Service_CenterMail();

        parent::__construct();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * セッションの継続を確認
     * </li><li>
     * チケットの確認と破棄
     * </li><li>
     * 入力チェック
     * </li><li>
     * セッションから情報を取得
     * </li><li>
     * 情報をDBへ格納
     * </li><li>
     * 出力情報を設定
     * </li><li>
     * セッション情報を破棄
     * </li></ol>
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['outForm']:出力フォーム
     * </li></ul>
     */
    public function executeInner() {
        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();

        $session->checkSessionTimeout();

        // //チケットの確認と破棄
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_REC003, $this->_getTicket());
        
        // // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        // // セッションから情報を取得
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        if (empty($sessionForm)) {
            $sessionForm = new Sgmov_Form_RecSession();
        }


        $inForm = $this->_createEmployData($sessionForm->in);
    
        $moushiKomiId = $this->insertEmployRegist($db, $inForm);

        // // セッション情報を破棄
        $session->deleteForm(self::FEATURE_ID);

        return array(
            "moushiKomiID" => $moushiKomiId);
    }

    public function insertEmployRegist($db, $formData){
       
        $this->_EmployRegistService->insert($db, $formData);

        $moushiKomiId = $this->_EmployRegistService->select_id($db);

        $formData["moushiKomiId"] = sprintf("%010d", $moushiKomiId);
        $this->sendMail($db, $formData);

        return $formData["moushiKomiId"];
    }

    /**
     * POST値からチケットを取得します。
     * チケットが存在しない場合は空文字列を返します。
     * @return string チケット文字列
     */
    public function _getTicket() {
        if (!isset($_POST['ticket'])) {
            $ticket = '';
        } else {
            $ticket = $_POST['ticket'];
        }
        return $ticket;
    }

 }   