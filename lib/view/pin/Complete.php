<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('pin/Common');
Sgmov_Lib::useServices(array('Inquiry', 'CenterMail'));
Sgmov_Lib::useForms(array('Error', 'PinSession', 'Pin001In', 'Pin003Out'));
/**#@-*/

 /**
 * お問い合わせ情報を登録し、完了画面を表示します。
 * @package    View
 * @subpackage PIN
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pin_Complete extends Sgmov_View_Pin_Common
{

    /**

     * 都道府県

     * @var Sgmov_Service_Employment

     */

    public $_prefectureService;


    /**
     * お問い合わせサービス
     * @var Sgmov_Service_Inquiry
     */
    public $_inquiryService;

    /**
     * 拠点メールアドレスサービス
     * @var Sgmov_Service_CenterMail
     */
    public $_centerMailService;

    public function __construct()
    {

    	$this->_prefectureService = new Sgmov_Service_Prefecture();
        $this->_inquiryService = new Sgmov_Service_Inquiry();
        $this->_centerMailService = new Sgmov_Service_CenterMail();
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
     * 管理者通知メール送信
     * </li><li>
     * サンキューメール送信
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
    public function executeInner()
    {
        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();

        // チケットの確認と破棄
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_PIN002, $this->_getTicket());

        // セッションから情報を取得
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        $data = $this->_createInsertDataFromInForm($sessionForm->in);

        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        // 情報をDBへ格納
        $this->_inquiryService->

                insert($db, $data);

        // メール送信用データを作成
        $mailData = $this->_createMailDataFromInForm($db, $sessionForm->in);

        // 管理者通知メール送信
        if ($sessionForm->in->inquiry_type_cd_sel == '11') {
            // お問い合わせ種類コード選択値 が 旅客手荷物受付サービス(value値:11)だった場合メール送信先を東京営業所に固定するため、第３引数のエリアコードを東京都(value値:13)に固定する
            $this->_centerMailService->_sendAdminMail($db, Sgmov_Service_CenterMail::FORM_KBN_PIN, '13', $mailData, '/pin_admin.txt', true);
        } else if ($sessionForm->in->inquiry_type_cd_sel == '3') {
            // 採用について を選択
            $this->_centerMailService->_sendAdminMail($db, Sgmov_Service_CenterMail::FORM_KBN_PIN, $sessionForm->in->pref_cd_sel, $mailData, '/pin_admin.txt');
            $this->_centerMailService->_sendAdminMail($db, Sgmov_Service_CenterMail::FORM_KBN_PIN_SAIYO, $sessionForm->in->pref_cd_sel, $mailData, '/pin_admin.txt', true); // 営業所のみ
        } else if ($sessionForm->in->inquiry_type_cd_sel == '20') { // カヌー輸送
            // カヌー輸送が選択された場合は、14：神奈川として扱い 該当メンバーにメールするようにする（区分は別）
            $this->_centerMailService->_sendAdminMail($db, Sgmov_Service_CenterMail::FORM_KBN_PIN_CANOE, '14', $mailData, '/pin_admin.txt', true);
        } else {
            $this->_centerMailService->_sendAdminMail($db, Sgmov_Service_CenterMail::FORM_KBN_PIN, $sessionForm->in->pref_cd_sel, $mailData, '/pin_admin.txt');
        }

        // サンキューメール送信

        if (!empty($sessionForm->in->mail)) {

            $this->_centerMailService->_sendThankYouMail('/pin_user.txt', $sessionForm->in->mail, $mailData);

        }

        // 出力情報を設定
        $outForm = new Sgmov_Form_Pin003Out();
        $outForm->raw_mail = $sessionForm->in->mail;

        // セッション情報を破棄
        $session->deleteForm(self::FEATURE_ID);

        return array('outForm'=>$outForm);
    }

    /**
     * POST値からチケットを取得します。
     * チケットが存在しない場合は空文字列を返します。
     * @return string チケット文字列
     */
    public function _getTicket()
    {
        if (!isset($_POST['ticket'])) {
            $ticket = '';
        } else {
            $ticket = $_POST['ticket'];
        }
        return $ticket;
    }

    /**
     * 入力フォームの値を元にインサート用データを生成します。
     * @param Sgmov_Form_Pin001In $inForm 入力フォーム
     * @return array インサート用データ
     */
    public function _createInsertDataFromInForm($inForm)
    {
        $data = array();
        $data['inquiry_type_cd'] = $inForm->inquiry_type_cd_sel;
        $data['need_reply_flag'] = $inForm->need_reply_cd_sel;
        $data['company_name'] = $inForm->company_name;
        $data['name'] = $inForm->name;
        $data['furigana'] = $inForm->furigana;
        $data['tel'] = $inForm->tel1 . $inForm->tel2 . $inForm->tel3;
        $data['mail'] = $inForm->mail;
        $data['zip'] = $inForm->zip1 . $inForm->zip2;
        if ( empty($inForm->pref_cd_sel)) {
            $data['pref_id'] = null;
        } else {
            $data['pref_id'] = $inForm->pref_cd_sel;
        }
        $data['address'] = $inForm->address;
        $data['inquiry_title'] = $inForm->inquiry_title;
        $data['inquiry_content'] = $inForm->inquiry_content;
        return $data;
    }

    /**
     * 入力フォームの値を元にメール送信用データを生成します。
     * @param Sgmov_Form_Pin001In $inForm 入力フォーム
     * @return array インサート用データ
     */
    public function _createMailDataFromInForm($db, $inForm)
    {
        // 都道府県を取得
        $prefs = $this->_prefectureService->fetchPrefectures($db);


        // 都道府県名を取得
        $key = array_search($inForm->pref_cd_sel, $prefs['ids']);
        $prefName = $prefs['names'][$key];

        $data = array();
        $data['inquiry_type'] = $this->inquiry_type_lbls[$inForm->inquiry_type_cd_sel];
        $data['need_reply'] = $this->need_reply_lbls[$inForm->need_reply_cd_sel];
        $data['company_name'] = $inForm->company_name;
        $data['name'] = $inForm->name;
        $data['furigana'] = $inForm->furigana;
        if ( empty($inForm->tel1)) {
            $data['tel'] = '';
        } else {
            $data['tel'] = $inForm->tel1 . '-' . $inForm->tel2 . '-' . $inForm->tel3;
        }
        $data['mail'] = $inForm->mail;
        if ( empty($inForm->zip1)) {
            $data['zip'] = '';
        } else {
            $data['zip'] = '〒' . $inForm->zip1 . '-' . $inForm->zip2;
        }
        $data['address_all'] = $prefName . $inForm->address;
        $data['inquiry_title'] = $inForm->inquiry_title;
        $data['inquiry_content'] = $inForm->inquiry_content;
        return $data;
    }

}
?>
