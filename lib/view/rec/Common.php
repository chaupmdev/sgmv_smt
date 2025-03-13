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
abstract class Sgmov_View_Rec_Common extends Sgmov_View_Public {
    /**
     * 機能ID
     */
    const FEATURE_ID = 'REC';

    /**
     * REC001の画面ID
     */
    const GAMEN_ID_REC001 = 'REC001';

    /**
     * REC002の画面ID
     */
    const GAMEN_ID_REC002 = 'REC002';

    /**
     * REC003の画面ID
     */
    const GAMEN_ID_REC003 = 'REC003';

    /**
     * 性別
     */
    public $gender_lbls = array(
        1 => '男性',
        2 => '女性'
    );

    /**
     * 希望雇用形態
     */
    public $employ_cd_lbls = array(
        1 => '正社員',
        2 => 'パートナー社員(アルバイト・パートナー)',
        3 => '新卒正社員'
    );


    /**
     * 現在の就業状況
     */
    public $current_employment_status_lbls = array(
        1 => '就業中',
        2 => '就業していない'
    );

    /**
     * 連絡可能な時間帯
     */
    public $contact_time_lbls = array(
        1 => '9時～12時',
        2 => '12時～15時',
        3 => '15時～18時',
        4 => '18時以降'
    );

    /**
     * 希望勤務地
     */
    public $center_id_lbls;

    /**
     * 希望職種
     */
    public $occupation_cd_lbls;

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

        $this->employ_cd_lbls = $this->_EmployService->fetchEmploy($db);
        $this->center_id_lbls = $this->_EigyoshoService->fetchEigyoShoNm($db);
        $this->occupation_cd_lbls = $this->_OccupationService->fetchOccupationNm($db);
        $this->_SocketZipCodeDll                 = new Sgmov_Service_SocketZipCodeDll();
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

    	$age_lbls = array();
    	foreach (range(18, 60) as $key => $age) {
    		$age_[$age] = $age;
    		$age_lbls[$age] = $age;
		}

    	$dispItemInfo["age_lbls"] = $age_lbls;

    	$dispItemInfo["employ_cd_lbls"] = $this->employ_cd_lbls;
        $dispItemInfo["center_id_lbls"] = $this->center_id_lbls;
        $dispItemInfo["occupation_cd_lbls"] = $this->occupation_cd_lbls;

        // 現在の就業状況
        $dispItemInfo["current_employment_status_lbls"] = $this->current_employment_status_lbls;

        // 連絡可能な時間帯
        $dispItemInfo["contact_time_lbls"] = $this->contact_time_lbls;

        $dispItemInfo["center_id_lbls_control"] = array();
        if(!empty($inForm["employ_cd"])){
            $dispItemInfo["center_id_lbls_control"] = $this->_EigyoshoService->fetchEigyoShoByLocCd($db, $inForm["employ_cd"]);
        }

        $dispItemInfo["occupation_cd_lbls_control"] = array();
        if(!empty($inForm["center_id_hidden"]) && !empty($inForm["center_id"])){
            $dispItemInfo["occupation_cd_lbls_control"] = $this->_OccupationService->fetchOccupationByEigyoCd($db, $inForm["center_id_hidden"]);
        }
        $dispItemInfo["chbAgreement"] = $inForm['chb_agreement'];
        
    	$outForm->raw_personal_name  = @$inForm['personal_name'];
    	$outForm->raw_personal_name_furi  = @$inForm['personal_name_furi'];
    	$outForm->raw_sei  = @$inForm['sei'];
    	$outForm->raw_age  = @$inForm['age'];

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
    	$outForm->raw_employ_cd = $inForm['employ_cd'];
    	$outForm->raw_center_id  = @$inForm['center_id'];
    	$outForm->raw_occupation_cd  = @$inForm['occupation_cd'];

        $outForm->raw_center_id_hidden  = @$inForm['center_id_hidden'];
        $outForm->raw_occupation_cd_hidden  = @$inForm['occupation_cd_hidden'];

    	$outForm->raw_question  = @$inForm['question'];

        $outForm->raw_current_employment_status  = @$inForm['current_employment_status'];
        $outForm->raw_contact_time  = @$inForm['contact_time'];
       

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

        $contact_time = implode (",", $inForm["contact_time"]);

        return array(
            "personal_name" => $inForm["personal_name"],
            "personal_name_furi" => $inForm["personal_name_furi"],
            "sei" => $inForm["sei"],
            "age" => $inForm["age"],
            "zip" => $inForm["zip1"].$inForm["zip2"],
            "pref_id" => $inForm["pref_id"],
            "address" => $inForm["address"],
            "building" => $inForm["building"],
            "tel" => $inForm["tel"],
            "mail" => $inForm["mail"],
            "employ_cd" => $inForm["employ_cd"],
            "center_id" => $inForm["center_id_hidden"],
            "occupation_cd" => $inForm["occupation_cd_hidden"],
            "question" => $inForm["question"],
            "created" => date("Y/m/d H:i:s"),
            "modified" => date("Y/m/d H:i:s"),
            "current_employment_status" => '', //$inForm["current_employment_status"],
            "contact_time" => '', //$contact_time,
        );
    }

    public function sendMail($db, $formData){

        $centerMail = new Sgmov_Service_CenterMail();

        $mailTemplate[] = "/rec_complete.txt";

        $prefData = $this->_PrefectureService->fetchPrefecturesById($db, $formData['pref_id']);
        $wage = "【月給】";
        if($formData["employ_cd"] == "02"){
            $wage = "【時給】";
        }

        $this->center_id_lbls = $this->_EigyoshoService->fetchEigyoShoByLocCd($db, $formData["employ_cd"]);
        $this->occupation_cd_lbls = $this->_OccupationService->fetchOccupationByEigyoCd($db, $formData["center_id"]);

        $formData["wage"] = $wage."￥".number_format($this->_OccupationService->fetchWage($db, $formData["employ_cd"], $formData["center_id"], $formData["occupation_cd"]))."～";
        $formData['pref_name'] = $prefData["name"]; 
        $formData['employ_name'] = $this->employ_cd_lbls[$formData["employ_cd"]];
        $formData['eigyosho_nm'] = $this->center_id_lbls[$formData["center_id"]];
        $formData['eigyosho_nm'] = @str_replace('_', '　', $formData['eigyosho_nm']);
        $formData['occupation_nm'] = $this->occupation_cd_lbls[$formData["occupation_cd"]];
        $formData['question'] =  strip_tags(nl2br($formData['question']));
        $formData['sei_name'] = @$formData['sei'] == '1' ? "男性" : "女性";
        $formData['zip1'] = @mb_substr($formData['zip'], 0, 3);
        $formData['zip2'] = @mb_substr($formData['zip'], 3, 4);
        //$formData['current_employment_status'] = $this->current_employment_status_lbls[$formData["current_employment_status"]];//現在の就業状況

        // 時間帯をカンマ区切りで表示する。
//        $commaToArray = explode(",", $formData["contact_time"]);
//        $contact_time_lbls = "";
//        $tc = count($commaToArray);
//        $i = 0;
//        foreach ($commaToArray as $key) {
//            $i++;
//            $comma = "、";
//            if($tc == $i){
//                $comma = "";
//            }
//            $contact_time_lbls .= $this->contact_time_lbls[$key].$comma;
//        }
//        $formData['contact_time'] = $contact_time_lbls;//連絡可能な時間帯

        $form_division = "21";
        $centerId = $formData['center_id'];
        
        if($formData["employ_cd"] == "03"){//新卒正社員
            $centerId = 1;//新卒正社員の場合、本社人事課にのみ申込メールを送付
        }
        $mails = $centerMail->fetchMailsByCenterId($db, $form_division, $centerId);

        if(isset($mails["to"])){
            
            $mailTemplateSaiyoTanto[] = "/rec_complete_saiyotanto.txt";
            $eigyoshoInfo = $this->_EigyoshoService->fetchEigyoshoByEmpCdCenterId($db, $formData["employ_cd"], $formData["center_id"]);
            
            if (@empty($eigyoshoInfo)) {
                // システム管理者メールアドレスを取得する。
                $mailTo = Sgmov_Component_Config::getLogMailTo ();

                // テンプレートメールを送信する。
                $this->sendCompleteMail(array('/rec_error.txt'), $mailTo, $formData);
                
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
