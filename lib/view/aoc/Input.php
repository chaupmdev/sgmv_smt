<?php
/**
 * @package    ClassDefFile
 * @author     T.Aono(SGS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('aoc/Common');
Sgmov_Lib::useServices(array('Login', 'OtherCampaign'));
Sgmov_Lib::useForms(array('Error', 'AocSession', 'Aoc002In', 'Aoc002Out'));
/**#@-*/

/**
 * 他社連携キャンペーン入力画面を表示します。
 * @package    View
 * @subpackage AOC
 * @author     T.Aono(SGS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Aoc_Input extends Sgmov_View_Aoc_Common {

    /**
     * ログインサービス
     * @var Sgmov_Service_Login
     */
    public $_loginService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_loginService = new Sgmov_Service_Login();
        $this->_OtherCampaignService = new Sgmov_Service_OtherCampaign();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * 表示ボタン押下ではない場合
     *   <ol><li>
     *   セッションに情報がある場合、セッション情報を元に出力情報を作成
     *   </li><li>
     *   セッションに情報がない場合、出力情報を作成
     *   </li><li>
     *   その他情報を生成
     *   </li><li>
     *   チケット発行
     *   </li></ol>
     * </li><li>
     * 表示ボタン押下の場合
     *   <ol><li>
     *   入力エラーがなければ
     *     <ol><li>
     *     セッション情報をクリア
     *     </li><li>
     *     検索実行
     *     </li><li>
     *     セッションに検索情報を保存(元金額・金額共に書き込み)
     *     </li></ol>
     *   </li><li>
     *   セッションに情報がある場合、セッション情報を元に出力情報を作成
     *   </li><li>
     *   セッションに情報がない場合、出力情報を作成
     *   </li><li>
     *   入力エラーの場合には入力値がセッションに保存されていないので出力フォームに設定する
     *   </li><li>
     *   その他情報を生成
     *   </li><li>
     *   チケット発行
     *   </li></ol>
     * </li></ol>
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['ticket']:チケット文字列
     * </li><li>
     * ['outForm']:出力フォーム
     * </li><li>
     * ['errorForm']:エラーフォーム
     * </li><li>
     * ['searchErrorForm']:検索部エラーフォーム
     * </li></ul>
     */
    public function executeInner() {

        // セッションの取得
        $session = Sgmov_Component_Session::get();


        if (!isset($_POST['reading_btn_x'])) {
            Sgmov_Component_Log::debug('表示ボタン押下ではない場合');

            // セッションに情報があるかどうかを確認
            $sessionForm = $session->loadForm(self::FEATURE_ID);
            //他社連携キャンペーン編集用パラメータ取得
            if (isset($_GET['param'])) {
                $oc_id = $_GET['param'];
                $outForm = $this->_createOutForm($oc_id);
                $errorForm = new Sgmov_Form_Error();
            } elseif (isset($sessionForm)) {
                // セッション情報を元に出力情報を作成
                $outForm = $this->_createOutFormBySessionForm($sessionForm);
                $errorForm = $sessionForm->error;
            } else {
                // 出力情報を設定
                $outForm = new Sgmov_Form_Aoc002Out();
                $outForm->raw_cond_selected_flag = '0';
                $errorForm = new Sgmov_Form_Error();
            }

            // 基本情報の設定
            $this->_setBasicInfo($outForm);


            // チケット発行
            $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_AOC002);
            return array(
                'ticket'          => $ticket,
                'outForm'         => $outForm,
                'errorForm'       => $errorForm,
                'searchErrorForm' => new Sgmov_Form_Error()
            );
        }
    }

    /**
     * セッションフォームの値を元に出力フォームを生成します。
     * @param Sgmov_Form_AocSession $sessionForm セッションフォーム
     * @return Sgmov_Form_Aoc002Out 出力フォーム
     */
    public function _createOutFormBySessionForm($sessionForm) {
        $outForm = new Sgmov_Form_Aoc002Out();
        $outForm->raw_oc_id          = $sessionForm->oc_id;
        $outForm->raw_oc_name        = $sessionForm->oc_name;
        $outForm->raw_oc_flg         = $sessionForm->oc_flg;
        $outForm->raw_oc_content     = $sessionForm->oc_content;
        $outForm->raw_oc_application = $sessionForm->oc_application;
        return $outForm;
    }

    /**
     * GETパラメーターを元に出力情報を生成します。
     * @param string $listModeCd 一覧画面の表示モード
     * @return array
     * ['outForm'] 出力フォーム
     * ['errorForm'] エラーフォーム
     */
    public function _createOutForm($oc_id) {
        $outForm = new Sgmov_Form_Aoc002Out();

        $db = Sgmov_Component_DB::getAdmin();

        // 他社連携キャンペーン詳細の取得
        $spInfos = $this->_OtherCampaignService->
                fetchOtherCampaignByStatus($db, $oc_id);

        $this->_setSpInfo($outForm, $spInfos);
        return $outForm;
    }

    /**
     * 出力フォームに他社連携キャンペーン情報を設定します。
     * @param Sgmov_Form_Aoc001Out $outForm 出力フォーム
     * @param array $spInfo 特価情報
     * @param array $fromAreaIds 特価情報に紐付く出発エリア情報
     * @param array $masters マスター情報
     */
    public function _setSpInfo($outForm, $spInfo) {
        // 他社連携キャンペーン内容
        $outForm->raw_oc_id          = $spInfo['id'];
        $outForm->raw_oc_name        = $spInfo['campaign_name'];
        $outForm->raw_oc_flg         = $spInfo['campaign_flg'];
        $outForm->raw_oc_content     = $spInfo['campaign_content'];
        $outForm->raw_oc_application = $spInfo['campaign_application'];
    }

    /**
     * 出力フォームに基本情報を設定します。
     * @param Sgmov_Form_Aoc001Out $outForm 出力フォーム
     * @param string $listModeCd 一覧画面表示モード
     */
    public function _setBasicInfo($outForm) {
        // 本社ユーザーかどうか
        $outForm->raw_honsha_user_flag = $this->getHonshaUserFlag();
    }
}