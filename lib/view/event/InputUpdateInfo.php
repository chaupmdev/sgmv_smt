<?php
/**
 * 09_会員情報登録・変更。。
 * @package    View
 * @subpackage event/InputUpdateInfo
 * @author     GiapLN(FPT Software) 
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('event/Common');
Sgmov_Lib::useForms(array('Error', 'UserSession', 'UserUpdateInfo001Out'));
Sgmov_Lib::useServices(array('EventLogin', 'Eventsub', 'Prefecture', 'Yubin', 'SocketZipCodeDll'));
/**#@-*/

class Sgmov_View_Event_InputUpdateInfo extends Sgmov_View_Event_Common
{
	
    /**
     * イベントサブサービス
     * @var Sgmov_Service_Eventsub
     */
    protected $_EventsubService;
    
    protected $_EventLoginService;
    /**
     * 都道府県サービス
     * @var Sgmov_Service_Prefecture
     */
    protected $_PrefectureService;
    
    public function __construct() {
        $this->_EventsubService       = new Sgmov_Service_Eventsub();
        $this->_EventLoginService = new Sgmov_Service_EventLogin();
        $this->_PrefectureService     = new Sgmov_Service_Prefecture();
        parent::__construct();
    }
    
    public function executeInner() {
    	// GETパラメータ取得
        $inqcase = $this->_parseGetParameter();
        $db = Sgmov_Component_DB::getPublic();
        // セッションに情報があるかどうかを確認
        $session = Sgmov_Component_Session::get();
        
        $this->redirectForUrlInvalid();
        $this->redirectWhenEventInvalid($db, $inqcase); 
        
        
        //if (!isset($_SESSION[self::LOGIN_ID])) {
        if (!isset($_SESSION[self::LOGIN_ID]['email'])) {
            Sgmov_Component_Redirect::redirectPublicSsl('/event/login?event_nm='.$inqcase);
        } else {
            //GiapLN fix bug event login 2022/04/15
            $this->checkEventNameInUrl($inqcase);
            
            $eLogins = $this->_EventLoginService->fetchEventLoginByEmail($db, $_SESSION[self::LOGIN_ID]['email']);
        }
        
        $sessionData = $_SESSION[self::FEATURE_ID];
        $sessionForm = $session->loadForm(self::UPDATE_INFO_ID);
        
        if (isset($sessionForm)) {
            // セッション情報を元に出力情報を作成
            $outForm = $this->_createOutFormByInForm($db, $sessionForm->in);
            $errorForm = $sessionForm->error;
            $sessionForm->error = NULL;

        } else {
            // 出力情報を設定
            $outForm = $this->_createOutFormByObject($db, $eLogins);
            $errorForm = new Sgmov_Form_Error();
        }

        // セッション破棄
        $session->deleteForm(self::UPDATE_INFO_ID);
        // チケット発行
        $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_EVENT009);
        
        return array('outForm'=>$outForm,
                        'errorForm'=>$errorForm, 
                        'sessionData' => $sessionData,
                        'object' => $eLogins, 
                        'ticket'    => $ticket
            );
    }
    /**
     * _createOutFormByObject。
     * @return Sgmov_Form_UserUpdateInfo001Out 出力フォーム
     */
    public function _createOutFormByObject($db, $eLogins) {
        $outForm = new Sgmov_Form_UserUpdateInfo001Out();
        if (!empty($eLogins['name_sei'])) {
            $outForm->raw_comiket_personal_name_sei = $eLogins['name_sei'];
        }
        
        if (!empty($eLogins['name_mei'])) {
            $outForm->raw_comiket_personal_name_mei = $eLogins['name_mei'];
        }
        
        if (!empty($eLogins['zip'])) {
            $zip1 = substr($eLogins['zip'], 0, 3);
            $zip2 = substr($eLogins['zip'], 3, 4);
            $outForm->raw_comiket_zip1 = $zip1;
            $outForm->raw_comiket_zip2 = $zip2;
        }
        
        if (!empty($eLogins['pref_id'])) {
            $outForm->raw_comiket_pref_cd_sel = $eLogins['pref_id'];
        }
        
        $prefectureAry = $this->_PrefectureService->fetchPrefectures($db);
        array_shift($prefectureAry["ids"]);
        array_shift($prefectureAry["names"]);
        $outForm->raw_comiket_pref_cds  = $prefectureAry["ids"];
        $outForm->raw_comiket_pref_lbls = $prefectureAry["names"];
        
        if (!empty($eLogins['address'])) {
            $outForm->raw_comiket_address = $eLogins['address'];
        }
        if (!empty($eLogins['building'])) {
            $outForm->raw_comiket_building = $eLogins['building'];
        }
        if (!empty($eLogins['tel'])) {
            $outForm->raw_comiket_tel = $eLogins['tel'];
        }
        
        
        return $outForm;
    }
    /**
     * 入力フォームの値を元に出力フォームを生成します。
     * @return Sgmov_Form_UserUpdateInfo001Out 出力フォーム
     */
    public function _createOutFormByInForm($db, $inForm)
    {
        $outForm = new Sgmov_Form_UserUpdateInfo001Out();
        
        $outForm->raw_comiket_personal_name_sei = $inForm->comiket_personal_name_sei;
        $outForm->raw_comiket_personal_name_mei = $inForm->comiket_personal_name_mei;
        
        $outForm->raw_comiket_zip1 = $inForm->comiket_zip1;
        $outForm->raw_comiket_zip2 = $inForm->comiket_zip2;
        $outForm->raw_comiket_pref_cd_sel = $inForm->comiket_pref_cd_sel;
        
        $prefectureAry = $this->_PrefectureService->fetchPrefectures($db);
        array_shift($prefectureAry["ids"]);
        array_shift($prefectureAry["names"]);
        $outForm->raw_comiket_pref_cds  = $prefectureAry["ids"];
        $outForm->raw_comiket_pref_lbls = $prefectureAry["names"];
        
        $outForm->raw_comiket_address = $inForm->comiket_address;
        $outForm->raw_comiket_building = $inForm->comiket_building;
        $outForm->raw_comiket_tel = $inForm->comiket_tel;
        
        $outForm->raw_password_old = $inForm->password_old;
        $outForm->raw_password = $inForm->password;
        $outForm->raw_password_confirm = $inForm->password_confirm;
        
        return $outForm;
    }
}
?>