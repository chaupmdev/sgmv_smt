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
Sgmov_Lib::useForms(array('Error', 'PinSession', 'Pin001Out'));
/**#@-*/

 /**
 * お問い合わせ入力画面を表示します。
 * @package    View
 * @subpackage PIN
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pin_Input extends Sgmov_View_Pin_Common
{
	
	/**
     * 都道府県サービス
     * @var Sgmov_Service_Prefecture
     */
    public $_prefecture;
    
    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_prefecture = new Sgmov_Service_Prefecture();
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
    public function executeInner()
    {
    	
    	// GETパラメータ取得
        $inqcase = $this->_parseGetParameter();

        // セッションに情報があるかどうかを確認
        $session = Sgmov_Component_Session::get();
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        if (isset($sessionForm)) {
            // セッション情報を元に出力情報を作成
            $outForm = $this->_createOutFormByInForm($sessionForm->in);
            $errorForm = $sessionForm->error;
            $sessionForm->error = NULL;
        } else {
            // 出力情報を設定
            $outForm = new Sgmov_Form_Pin001Out();
            $errorForm = new Sgmov_Form_Error();
        }

        // セッション破棄
        $session->deleteForm(self::FEATURE_ID);

        // テンプレート用の値をセット
        $outForm = $this->_setTemplateValuesToOutForm($outForm, $inqcase);

        // チケット発行
        $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_PIN001);

        return array('ticket'=>$ticket,
                         'outForm'=>$outForm,
                         'errorForm'=>$errorForm);
    }
    /**
     * 入力フォームの値を元に出力フォームを生成します。
     * @param Sgmov_Form_Pin001In $inForm 入力フォーム
     * @return Sgmov_Form_Pin001Out 出力フォーム
     */
    public function _createOutFormByInForm($inForm)
    {
        $outForm = new Sgmov_Form_Pin001Out();
        $outForm->raw_inquiry_type_cd_sel = $inForm->inquiry_type_cd_sel;
        $outForm->raw_need_reply_cd_sel = $inForm->need_reply_cd_sel;
        $outForm->raw_company_name = $inForm->company_name;
        $outForm->raw_name = $inForm->name;
        $outForm->raw_furigana = $inForm->furigana;
        $outForm->raw_tel1 = $inForm->tel1;
        $outForm->raw_tel2 = $inForm->tel2;
        $outForm->raw_tel3 = $inForm->tel3;
        $outForm->raw_mail = $inForm->mail;
        $outForm->raw_zip1 = $inForm->zip1;
        $outForm->raw_zip2 = $inForm->zip2;
        $outForm->raw_pref_cd_sel = $inForm->pref_cd_sel;
        $outForm->raw_address = $inForm->address;
        $outForm->raw_inquiry_title = $inForm->inquiry_title;
        $outForm->raw_inquiry_content = $inForm->inquiry_content;
        $outForm->raw_chb_agreement = $inForm->chb_agreement;
        return $outForm;
    }

    /**
     * 出力フォームにテンプレート用の値を設定して返します。
     * @param Sgmov_Form_Pin001Out $outForm 出力フォーム
     * @return Sgmov_Form_Pin001Out 出力フォーム
     */
    public function _setTemplateValuesToOutForm($outForm, $inqcase){
    	
    	// DB接続
        $db = Sgmov_Component_DB::getPublic();
        
        // 都道府県
        $prefs = $this->_prefecture->fetchPrefectures($db);
        $outForm->raw_pref_cds = $prefs['ids'];
        $outForm->raw_pref_lbls = $prefs['names'];
        
        // GET値
        if (isset($inqcase)) {
        	$outForm->raw_inquiry_type_cd_sel = $inqcase;
        }
        
        return $outForm;
    }
    
    /**
     * GETパラメータを取得します。
     *
     * @param none
     * @return plan_cd
     */
    public function _parseGetParameter() {

        $retParam = array();

        if (!isset($_GET['param'])) {
            return NULL;
        } else {

            $params = explode('/', $_GET['param']);
            if (@!in_array($params[0], array('9', '20'))) { // 2021/07/16 sawada 種類：お引越し、カヌー の場合は通す(新料金にあっていないため)
                if (!preg_match("/^[0-9]+$/", $params[0]) || !(1 <= $params[0] && $params[0] <= 5)) {
                    // 半角数字、または、1～5以内でない場合、NULLをセット
                    return NULL;
                }
            }

            // １個目以外の要素は無視
            return $params[0];
        }

    }

}
?>