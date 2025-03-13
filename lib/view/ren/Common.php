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
Sgmov_Lib::useAllComponents();
Sgmov_Lib::useServices(array('Prefecture', 'SocketZipCodeDll', 'Employ', 'Eigyosho', 'Occupation', 'CenterMail'));
Sgmov_Lib::useView('Public');
/**#@-*/

//define("COMIKET_DEV_INDIVIDUA", 1); // 個人
//define("COMIKET_DEV_BUSINESS", 2); // 法人
/**
 * イベントサービスのお申し込みフォームの共通処理を管理する抽象クラスです。
 * @package    View
 * @subpackage EVE
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Ren_Common extends Sgmov_View_Public {
    /**
     * 機能ID
     */
    const FEATURE_ID = 'REN';

    /**
     * REN001の画面ID
     */
    const GAMEN_ID_REN001 = 'REN001';

    /**
     * REN002の画面ID
     */
    const GAMEN_ID_REN002 = 'REN002';

    /**
     * REN003の画面ID
     */
    const GAMEN_ID_REN003 = 'REN003';
    
    const AGE_FROM = 18; 
    const AGE_TO = 60;

    
    /**
     * 都道府県サービス
     * @var Sgmov_Service_Prefecture
     */
    private $_PrefectureService;

    /**
     * 営業所サービス
     * @var Sgmov_Service_Employ
     */
    private $_EmployService;

    /**
     * 営業所サービス
     * @var Sgmov_Service_Eigyosho
     */
    private $_EigyoshoService;

    /**
     * 職種サービス
     * @var Sgmov_Service_Occupation
     */
    private $_OccupationService;

    /**
     * 拠点メールアドレスサービス
     * @var Sgmov_Service_CenterMail
     */
    public $_centerMailService;

    protected $_SocketZipCodeDll;
    protected $dateOfBirthYears;
    protected $dateOfBirthMonths;
    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
    	$this->_PrefectureService     = new Sgmov_Service_Prefecture();

        $this->_centerMailService   = new Sgmov_Service_CenterMail();

        $this->_EmployService     = new Sgmov_Service_Employ();
        $this->_EigyoshoService     = new Sgmov_Service_Eigyosho();
        $this->_OccupationService     = new Sgmov_Service_Occupation();

        $db = Sgmov_Component_DB::getPublic();

        $this->_SocketZipCodeDll                 = new Sgmov_Service_SocketZipCodeDll();
        $this->dateOfBirthYears = [];
        $currentYear = intval(date('Y'));
        $yearTo = $currentYear - self::AGE_FROM;
        $yearFrom = $currentYear - self::AGE_TO;
        for ($i = $yearTo; $i >= $yearFrom; $i--) {
            array_push($this->dateOfBirthYears, $i);
        }
        
        $this->dateOfBirthMonths = [];
        for ($i = 1; $i <= 12; $i++) {
            array_push($this->dateOfBirthMonths, $i);
        }
        
    }


    /**
     *
     * @param type $inForm
     * @param Sgmov_Form_Eve001Out $outForm
     * @return type
     */
    protected function createOutFormByInForm($inForm, $outForm = array()) {

    	$dispItemInfo = array();

    	$inForm = (array)$inForm;

    	$db = Sgmov_Component_DB::getPublic();

        $dispItemInfo["chbAgreement"] = $inForm['chb_agreement'];
        
    	$outForm->raw_personal_name  = @$inForm['personal_name'];
    	$outForm->raw_personal_name_furi  = @$inForm['personal_name_furi'];
    	$outForm->raw_sei  = @$inForm['sei'];
        
        
        //year
        $outForm->raw_date_of_birth_year_cds  = $this->dateOfBirthYears;
        $outForm->raw_date_of_birth_year_lbls = $this->dateOfBirthYears;
        $outForm->raw_date_of_birth_year_cd_sel = @$inForm["date_of_birth_year_cd_sel"];
        
        //month 
        $outForm->raw_date_of_birth_month_cds  = $this->dateOfBirthMonths;
        $outForm->raw_date_of_birth_month_lbls = $this->dateOfBirthMonths;
        $outForm->raw_date_of_birth_month_cd_sel = @$inForm["date_of_birth_month_cd_sel"];
        
        $dateOfBirthday = [];
        if (!empty($inForm["date_of_birth_year_cd_sel"]) && !empty($inForm["date_of_birth_month_cd_sel"])) {
            $dayOfMonth = cal_days_in_month(CAL_GREGORIAN, $inForm["date_of_birth_month_cd_sel"], $inForm["date_of_birth_year_cd_sel"]);
            for ($i = 1; $i <= $dayOfMonth; $i++) {
                array_push($dateOfBirthday, $i);
            }
        }
        
        //day 
        $outForm->raw_date_of_birth_day_cds  = $dateOfBirthday;
        $outForm->raw_date_of_birth_day_lbls = $dateOfBirthday;
        $outForm->raw_date_of_birth_day_cd_sel = @$inForm["date_of_birth_day_cd_sel"];

    	//$outForm->raw_age  = @$inForm['age'];

    	// 都道府県名
        $outForm->raw_pref_nm = "";
        if(@!empty($inForm["pref_id"])) {
            $prefInfo = $this->_PrefectureService->fetchPrefecturesById($db, $inForm["pref_id"]);
            $outForm->raw_pref_nm = $prefInfo["name"];
        }
    	$prefectureAry = $this->_PrefectureService->fetchPrefectures($db);
        array_shift($prefectureAry["ids"]);
        array_shift($prefectureAry["names"]);
        $outForm->raw_pref_cds  = $prefectureAry["ids"];
        $outForm->raw_pref_lbls = $prefectureAry["names"];
        $outForm->raw_pref_id = $inForm["pref_id"];

    	$outForm->raw_zip1  = @$inForm['zip1'];
    	$outForm->raw_zip2  = @$inForm['zip2'];
    	$outForm->raw_address  = @$inForm['address'];
    	$outForm->raw_building  = @$inForm['building'];
    	$outForm->raw_tel  = @$inForm['tel'];
    	$outForm->raw_mail  = @$inForm['mail'];
       

    	return array(
    	 	"outForm" => $outForm
            ,"dispItemInfo" => $dispItemInfo
        );
    }

        /**
     * プルダウンを生成し、HTMLソースを返します。
     * TODO pre/Inputと同記述あり
     *
     * @param $cds コードの配列
     * @param $lbls ラベルの配列
     * @param $select 選択値
     * @return 生成されたプルダウン
     */
    public static function _createPulldown($cds, $lbls, $select) {

        $html = '';

        if (empty($cds)) {
            return $html;
        }

        $count = count($cds);
        for ($i = 0; $i < $count; ++$i) {
            if ($select === $cds[$i]) {
                $html .= '<option value="' . $cds[$i] . '" selected="selected">' . $lbls[$i] . '</option>' . PHP_EOL;
            } else {
                $html .= '<option value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
            }
        }

        return $html;
    }

    public function _createEmployData($formData){
        $inForm = (array)$formData;

        return array(
            "personal_name" => $inForm["personal_name"],
            "personal_name_furi" => $inForm["personal_name_furi"],
            "sei" => $inForm["sei"],
            "date_of_birth_year_cd_sel" => $inForm["date_of_birth_year_cd_sel"],
            "date_of_birth_month_cd_sel" => $inForm["date_of_birth_month_cd_sel"],
            "date_of_birth_day_cd_sel" => $inForm["date_of_birth_day_cd_sel"],
            "zip" => $inForm["zip1"].$inForm["zip2"],
            "pref_id" => $inForm["pref_id"],
            "address" => $inForm["address"],
            "building" => $inForm["building"],
            "tel" => $inForm["tel"],
            "mail" => $inForm["mail"],
            "created" => date("Y/m/d H:i:s"),
            "modified" => date("Y/m/d H:i:s")
        );
    }

    public function sendMail($db, $formData){

        $centerMail = new Sgmov_Service_CenterMail();

        $mailTemplate[] = "/ren/ren_complete.txt";
        
        if (empty($formData['sei'])) {
            $formData['sei_name'] = '';
        } elseif ($formData['sei'] == '1') {
            $formData['sei_name'] = "男性";
        } elseif ($formData['sei'] == '2') {
            $formData['sei_name'] = "女性";
        } else {
            $formData['sei_name'] = "その他";
        }
        
        $formData['zip1'] = @mb_substr($formData['zip'], 0, 3);
        $formData['zip2'] = @mb_substr($formData['zip'], 3, 4);
        
        $prefData = $this->_PrefectureService->fetchPrefecturesById($db, $formData['pref_id']);
        $formData['pref_name'] = $prefData["name"]; 
        
        $formData['date_of_birth'] = $formData['date_of_birth_year_cd_sel'].'年'.str_pad($formData['date_of_birth_month_cd_sel'], 2, "0", STR_PAD_LEFT) .'月'.str_pad($formData['date_of_birth_day_cd_sel'], 2, "0", STR_PAD_LEFT) .'日';
        $form_division = "21";
        $employ_cd = '03';
       // if($formData["employ_cd"] == "03"){//新卒正社員
            $centerId = 1;//新卒正社員の場合、本社人事課にのみ申込メールを送付
        //}
        $mails = $centerMail->fetchMailsByCenterId($db, $form_division, $centerId);
        
        
        if(isset($mails["to"])){
            
            $mailTemplateSaiyoTanto[] = "/ren/ren_complete_saiyotanto.txt";
            $eigyoshoInfo = $this->_EigyoshoService->fetchEigyoshoByEmpCdCenterId($db, $employ_cd, $centerId);

            if (@empty($eigyoshoInfo)) {
                // システム管理者メールアドレスを取得する。
                $mailTo = Sgmov_Component_Config::getLogMailTo ();

                // テンプレートメールを送信する。
                $this->sendCompleteMail(array('/ren/ren_error.txt'), $mailTo, $formData);
                
                Sgmov_Component_Log::info('営業所情報の取得に失敗しました');
                Sgmov_Component_Log::info($formData);
            } else {
                $formData['eigyosho_nm_org'] = str_replace("_", " ", $eigyoshoInfo['eigyosho_nm_org']);
                if ($formData['eigyosho_nm_org'] != '本社' && $formData['eigyosho_nm_org'] != 'ＴＯＫＹＯ ＢＡＳＥ') {
                    $formData['eigyosho_nm_org'] = $formData['eigyosho_nm_org'] . '営業所';
                }
                // 各地域担当者へメール
                $this->sendCompleteMail($mailTemplateSaiyoTanto, $mails["to"], $formData, $mails["cc"], $mails["bcc"]);
            }
       }

        $sendTo = $formData["mail"];
Sgmov_Component_Log::debug($formData);
       // 申込者へメール
       $this->sendCompleteMail($mailTemplate, $sendTo, $formData);
    }

    /**
     * サンキューテンプレートメールを送信します。
     *
     * @param String $tmp テンプレートパス
     * @param string $sendTo 送信先メールアドレス
     * @param array $data テンプレートデータ
     */
    public function sendCompleteMail($tmp, $sendTo, $data, $sendCc='', $sendBcc='') {

        try {
            if (is_array($tmp)) {
                foreach ($tmp as $k => $d) {
                    $tmp[$k] = Sgmov_Lib::getMailTemplateDir() . $d;
                }
            } else {
                $tmp = Sgmov_Lib::getMailTemplateDir() . $tmp;
            }
            return Sgmov_Component_Mail::sendTemplateMail($data, $tmp, $sendTo, $sendCc, $sendBcc);
        } catch (Sgmov_Component_Exception $e) {
            Sgmov_Component_ErrorExit::errorExit($e->getCode(), $e->getMessage(), $e->getPrevious());
        }
    }
}
