<?php
/**
 * @package    ClassDefFile
 * @author     K.Hamada(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/**#@+
 * include files
 */
require_once dirname(__FILE__).'/../../Lib.php';
Sgmov_Lib::useView('pcs/Common');
Sgmov_Lib::useServices(array('CorporativeSetting', 'CenterMail'));
Sgmov_Lib::useForms(array('Error', 'PcsSession', 'Pcs001In', 'Pcs003Out'));
/**#@-*/
/**
 * 法人設置輸送情報を登録し、完了画面を表示します。
 * @package    View
 * @subpackage PCS
 * @author     K.Hamada(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pcs_Complete extends Sgmov_View_Pcs_Common {
	
    /**
     * 都道府県
     * @var Sgmov_Service_Employment
     */
    public $_prefectureService;
	
    /**
     * 法人設置輸送サービス
     * @var Sgmov_Service_Corporativesetting
     */
    public $_corporativeSettingService;

    /**
     * 拠点メールアドレスサービス
     * @var Sgmov_Service_CenterMail
     */
    public $_centerMailService;

    public function __construct() {
    	$this->_prefectureService = new Sgmov_Service_Prefecture();
        $this->_corporativeSettingService = new Sgmov_Service_CorporativeSetting();
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
    public function executeInner() {
    	
        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();
        
        // チケットの確認と破棄
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_PCS002, $this->_getTicket());
        
        // セッションから情報を取得
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        $data = $this->_createInsertDataFromInForm($sessionForm->in);
        
        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();
        
        // 情報をDBへ格納
        $this->_corporativeSettingService->insert($db, $data);
        
        // メール送信用データを作成
        $mailData = $this->_createMailDataFromInForm($db, $sessionForm->in);
        
        // 管理者通知メール送信
        $this->_centerMailService->_sendAdminMail($db, Sgmov_Service_CenterMail::FORM_KBN_PCS, $sessionForm->in->pref_cd_sel, $mailData, '/pcs_admin.txt');
        
        // サンキューメール送信
        if (!empty($sessionForm->in->mail)) {
        	$this->_centerMailService->_sendThankYouMail('/pcs_user.txt', $sessionForm->in->mail, $mailData);
        }
        
        // 出力情報を設定
        $outForm = new Sgmov_Form_Pcs003Out();
        $outForm->raw_mail = $sessionForm->in->mail;
        
        // セッション情報を破棄
        $session->deleteForm(self::FEATURE_ID);
        
        return array('outForm' => $outForm);
    }

    /**
     * 入力フォームの値を元にインサート用データを生成します。
     * @param Sgmov_Form_Pcs001In $inForm 入力フォーム
     * @return array インサート用データ
     */
    public function _createInsertDataFromInForm($inForm) {

        $data = array();
        $data['inquiry_type_cd'] = $inForm->inquiry_type_cd_sel;
        $data['inquiry_category_cd'] = $inForm->inquiry_category_cd_sel;
        $data['inquiry_title'] = $inForm->inquiry_title;
        $data['inquiry_content'] = $inForm->inquiry_content;
        $data['company_name'] = $inForm->company_name;
        $data['post_name'] = $inForm->post_name;
        $data['charge_name'] = $inForm->charge_name;
        $data['charge_furigana'] = $inForm->charge_furigana;
        $data['tel'] = $inForm->tel1.$inForm->tel2.$inForm->tel3;
        $data['tel_type_cd'] = $inForm->tel_type_cd_sel;
        $data['tel_other'] = $inForm->tel_other;
        $data['fax'] = $inForm->fax1.$inForm->fax2.$inForm->fax3;
        $data['mail'] = $inForm->mail;

        if (empty($inForm->contact_method_cd_sel)) {
            $data['contact_method_cd'] = null;
        } else {
            $data['contact_method_cd'] = $inForm->contact_method_cd_sel;
        }
        if (empty($inForm->contact_available_cd_sel)) {
            $data['contact_available_cd'] = null;
        } else {
            $data['contact_available_cd'] = $inForm->contact_available_cd_sel;
        }
        if ($inForm->contact_start_cd_sel === '') {
            $data['contact_start_cd'] = null;
        } else {
            $data['contact_start_cd'] = $inForm->contact_start_cd_sel;
        }
        if ($inForm->contact_end_cd_sel === '') {
            $data['contact_end_cd'] = null;
        } else {
            $data['contact_end_cd'] = $inForm->contact_end_cd_sel;
        }
        $data['zip'] = $inForm->zip1.$inForm->zip2;
        if (empty($inForm->pref_cd_sel)) {
            $data['pref_id'] = null;
        } else {
            $data['pref_id'] = $inForm->pref_cd_sel;
        }
        $data['address'] = $inForm->address;
        
        return $data;

    }

    /**
     * 入力フォームの値を元にメール送信用データを生成します。
     * @param Sgmov_Form_Pcs001In $inForm 入力フォーム
     * @return array インサート用データ
     */
    public function _createMailDataFromInForm($db, $inForm) {

        // 都道府県を取得
        $prefs = $this->_prefectureService->fetchPrefectures($db);

        // 都道府県名を取得
        $key = array_search($inForm->pref_cd_sel, $prefs['ids']);
        $prefName = $prefs['names'][$key];
        
        $data = array();
        $data['inquiry_type'] = $this->inquiry_type_lbls[$inForm->inquiry_type_cd_sel];
        $data['inquiry_category'] = $this->inquiry_category_lbls[$inForm->inquiry_category_cd_sel];
        $data['inquiry_title'] = $inForm->inquiry_title;
        $data['inquiry_content'] = $inForm->inquiry_content;
        $data['company_name'] = $inForm->company_name;
        $data['post_name'] = $inForm->post_name;
        $data['charge_name'] = $inForm->charge_name;
        $data['charge_furigana'] = $inForm->charge_furigana;
        
        if (empty($inForm->tel1)) {
            $data['tel'] = '';
        } else {
            $data['tel'] = $inForm->tel1.'-'.$inForm->tel2.'-'.$inForm->tel3;
        }
        $data['tel_type'] = $this->tel_type_lbls[$inForm->tel_type_cd_sel];
        $data['tel_other'] = $inForm->tel_other;
        if (empty($inForm->fax1)) {
            $data['fax'] = '';
        } else {
            $data['fax'] = $inForm->fax1.'-'.$inForm->fax2.'-'.$inForm->fax3;
        }
        $data['mail'] = $inForm->mail;
        $data['contact_method'] = $this->contact_method_lbls[$inForm->contact_method_cd_sel];
        $data['contact_available'] = $this->contact_available_lbls[$inForm->contact_available_cd_sel];
        $data['contact_start'] = $this->contact_start_lbls[$inForm->contact_start_cd_sel];
        $data['contact_end'] = $this->contact_end_lbls[$inForm->contact_end_cd_sel];
        if (empty($inForm->zip1)) {
            $data['zip'] = '';
        } else {
            $data['zip'] = $inForm->zip1.'-'.$inForm->zip2;
        }
        $data['address_all'] = $prefName.$inForm->address;
        return $data;
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
?>
