<?php
/**
 * @package    ClassDefFile
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/**#@+
 * include files
 */
require_once dirname(__FILE__).'/../../Lib.php';
Sgmov_Lib::useView('rec/Common');
Sgmov_Lib::useForms(array('Error', 'RecSession', 'Rec001In'));
Sgmov_Lib::useServices(array('HttpsZipCodeDll'));
/**#@-*/
/**
 * 旅客手荷物受付サービスのお申し込み入力情報をチェックします。
 * @package    View
 * @subpackage EVE
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Rec_CheckInput extends Sgmov_View_Rec_Common{
    /**
     * 都道府県サービス
     * @var Sgmov_Service_Prefecture
     */
    public $_PrefectureService;


    protected $_SocketZipCodeDll;

    const VALID_ONLY_ZEN = '/^([ａ-ｚＡ-Ｚぁ-んァ-ヶ０-９_　]|[\x{2E80}-\x{2FDF}々〇〻\x{3400}-\x{4DBF}\x{4E00}-\x{9FFF}\x{F900}-\x{FAFF}\x{20000}-\x{2FFFF} ])+$/u';

    const VALID_FOR_QUESTION = '/^([ａ-ｚＡ-Ｚぁ-んァ-ヶ０-９0-9]|[\x{2E80}-\x{2FDF}々〇〻\x{3400}-\x{4DBF}\x{4E00}-\x{9FFF}\x{F900}-\x{FAFF}\x{20000}-\x{2FFFF}]|[-!$%^&*()_+|~=`{}\[\]:";<>?,.\/])\w+.+$/u';

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_PrefectureService                = new Sgmov_Service_Prefecture();
        $this->_SocketZipCodeDll                 = new Sgmov_Service_SocketZipCodeDll();

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
     * 情報をセッションに保存
     * </li><li>
     * 入力エラー無し
     *   <ol><li>
     *   pcr/confirm へリダイレクト
     *   </li></ol>
     * </li><li>
     * 入力エラー有り
     *   <ol><li>
     *   pcr/input へリダイレクト
     *   </li></ol>
     * </li></ol>
     */
    public function executeInner() {
         // リダイレクト処理
        $session = Sgmov_Component_Session::get();
        // $session->checkSessionTimeout();

        // // DB接続
        $db = Sgmov_Component_DB::getPublic();

        // // チケットの確認と破棄
       // $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_REC001, $this->_getTicket());

        // //// 入力チェック
        $sessionForm = $session->loadForm(self::FEATURE_ID);

         if (empty($sessionForm)) {
            $sessionForm = new Sgmov_Form_RecSession();
            $sessionForm->in = null;
        }

        $inForm = $this->_createInFormFromPost($_POST, null);

        $errorForm = $this->_validate($inForm, $db);

         // 情報をセッションに保存
        $sessionForm->in = $inForm;
        $sessionForm->error = $errorForm;
        if ($errorForm->hasError()) {
            $sessionForm->status = self::VALIDATION_FAILED;
        } else {
            $sessionForm->status = self::VALIDATION_SUCCEEDED;
        }
        $session->saveForm(self::FEATURE_ID, $sessionForm);

        $this->_redirectProc($inForm, $errorForm);
    }

    /**
     *
     * @param type $inForm
     * @param type $errorForm
     */
    public function _redirectProc($inForm, $errorForm) {
        if ($errorForm->hasError()) {
            Sgmov_Component_Redirect::redirectPublicSsl('/rec/input/');
        }else{
            Sgmov_Component_Redirect::redirectPublicSsl('/rec/confirm/');
        }
    }


    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_Pcr001In $inForm 入力フォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($inForm, $db) {
        $errorForm = new Sgmov_Form_Error();

        $prefectures = $this->_PrefectureService->fetchPrefectures($db);

        // お名前(漢字)
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->personal_name)->isNotEmpty()->isLengthLessThanOrEqualTo(16)->
                    isNotHalfWidthKana()->isWebSystemNg();

        if (!$v->isValid()) {
            $errorForm->addError('personal_name', 'お名前(漢字)' . $v->getResultMessageTop());
        }

        if(!array_key_exists('personal_name', $errorForm->_errors)) {
            if(!preg_match(self::VALID_ONLY_ZEN, $inForm->personal_name)){
                $errorForm->addError('personal_name', 'お名前(漢字)は全角文字を入力してください。');
            }
        }
       

        // お名前(フリガナ)
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->personal_name_furi)->isLengthLessThanOrEqualTo(16)->isNotEmpty()->
                isAlphaCharactersOrFullWidthSquareJapaneseSyllabaryCharacters()->isNotHalfWidthKana()->isWebSystemNg();

        if (!$v->isValid()) {
            $errorForm->addError('personal_name_furi', 'お名前(フリガナ)' . $v->getResultMessageTop());
        }

        // 性別
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->sei)->isSelected()->isIn(array_keys($this->gender_lbls));
        if (!$v->isValid()) {
            $errorForm->addError('sei', '性別' . $v->getResultMessageTop());
        }

        $age_lbls = array();
        foreach (range(16, 100) as $key => $age) {
            $age_lbls[$age] = $age;
        }
        // ご年齢
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->age)->isSelected()->isIn($age_lbls);
        if (!$v->isValid()) {
            $errorForm->addError('age', 'ご年齢' . $v->getResultMessageTop());
        }


        // 郵便番号
        // 郵便番号(最後に存在確認をするので別名でバリデータを作成) 必須チェック
        $zipV = Sgmov_Component_Validator::createZipValidator($inForm->zip1, $inForm->zip2)->isNotEmpty()->isZipCode();
        if (!$zipV->isValid()) {
            $errorForm->addError('zip', '郵便番号' . $zipV->getResultMessageTop());
        }

        // 都道府県
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->pref_id)->isSelected()->isIn($prefectures['ids']);
        if (!$v->isValid()) {
            $errorForm->addError('pref_id', '都道府県' . $v->getResultMessageTop());
        }

        // 市区町村 必須チェック 40文字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->address)->isNotEmpty()->isLengthLessThanOrEqualTo(14)->
                isNotHalfWidthKana()->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('address', '市区町村' . $v->getResultMessageTop());
        } else {
            $prefName = @$prefectures["names"][$prefectures["ids"][$inForm->pref_id]];
            if (strpos($inForm->address, $prefName) !== false) {
                $errorForm->addError('address', '市区町村には都道府県名は入力しないで下さい。');
            }
        }

        // 番地・建物名・部屋番号 必須チェック 40文字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->building)->isNotEmpty()->isLengthLessThanOrEqualTo(30)->
                isNotHalfWidthKana()->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('building', '番地・建物名・部屋番号' . $v->getResultMessageTop());
        } else {
            $prefName = @$prefectures["names"][$prefectures["ids"][$inForm->pref_id]];
            if (strpos($inForm->building, $prefName) !== false) {
                $errorForm->addError('building', '番地・建物名・部屋番号には都道府県名は入力しないで下さい。');
            }    
        }

        // 電話番号 必須チェック 型チェック WEBシステムNG文字チェック
        $telNo = @str_replace(array('-', 'ー', '−', '―', '‐', 'ｰ'), "", $inForm->tel);
        //GiapLN imp ticket #SMT6-381 2022/12/29
        $v = Sgmov_Component_Validator::createSingleValueValidator($telNo)->isNotEmpty()->isPhoneHyphen()->isLengthLessThanOrEqualToForPhone();
        if (!$v->isValid()) {
            $errorForm->addError('tel', '電話番号（携帯電話番号）' . $v->getResultMessageTop());
        } else {
            $v = Sgmov_Component_Validator::createSingleValueValidator($telNo)->isLengthMoreThanOrEqualTo(8)->isLengthLessThanOrEqualTo(12);
            if (!$v->isValid()) {
                $errorForm->addError('tel', '電話番号（携帯電話番号）の数値部分' . $v->getResultMessageTop());
            }
        }
        
        // メールアドレス 必須チェック 100文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->mail)->isNotEmpty()->isMail()->isLengthLessThanOrEqualTo(100)->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('mail', 'メールアドレス' . $v->getResultMessageTop());
        }

        // 現在の就業状況
//        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->current_employment_status)->isSelected()->isIn(array_keys($this->current_employment_status_lbls));
//        if (!$v->isValid()) {
//            $errorForm->addError('current_employment_status', '現在の就業状況' . $v->getResultMessageTop());
//        }

        // 希望雇用形態
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->employ_cd)->isSelected()->isIn(array_keys($this->employ_cd_lbls));
        if (!$v->isValid()) {
            $errorForm->addError('employ_cd', '希望雇用形態' . $v->getResultMessageTop());
        }

        // 連絡可能な時間帯
//        if(empty($inForm->contact_time)){
//            $errorForm->addError('contact_time', '連絡可能な時間帯' . "を選択してください。");
//        }else{
//            foreach (array_keys($inForm->contact_time) as $key) {
//                if(!in_array($key, array_keys($this->contact_time_lbls))){
//                    $errorForm->addError('contact_time', '連絡可能な時間帯' . "の入力内容をお確かめください。");
//                }
//            }
//        }
      
        // 希望勤務地
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->center_id)->isSelected();
     
        if (!$v->isValid()) {
            $errorForm->addError('center_id', '希望勤務地' . $v->getResultMessageTop());
        }


        // 希望職種
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->occupation_cd)->isSelected();
        if (!$v->isValid()) {
            $errorForm->addError('occupation_cd', '希望職種' . $v->getResultMessageTop());
        }

        // お名前(漢字)
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->question)->isLengthLessThanOrEqualTo(1000)->isWebSystemNg();

        if (!$v->isValid()) {
            $errorForm->addError('question', 'ご質問など' . $v->getResultMessageTop());
        }

        if(!array_key_exists('question', $errorForm->_errors) && !empty($inForm->question)) {
            $validQuestion = $this->checkEmoji($inForm->question);
            if(!$validQuestion){
                $errorForm->addError('question', 'ご質問などの入力内容をお確かめください。');
            }
            
        }

        if($errorForm->hasError()){
           
        } else{
            $in_zipCodeResult = $this->validateZipCode($inForm);
            if(empty($in_zipCodeResult)){
                $errorForm->addError('zip', '郵便番号が不正です。');
            }else{
                $systemKenName = $in_zipCodeResult["kenName"];

                $systemKen = $in_zipCodeResult["ken"];

                $systemTownName = $in_zipCodeResult["cityName"].$in_zipCodeResult["townName"];

                $userInputKen = $inForm->pref_id;

                $userInputTownName = trim($inForm->address.$inForm->building);

                if($systemKen != $userInputKen ||  0 !== strpos($userInputTownName, $systemTownName)){

                    if($systemKen != $userInputKen){
                        $errorForm->addError('zip', '郵便番号の入力内容をお確かめください。');
                    }
                    if(0 !== strpos($userInputTownName, $systemTownName)){
                        $errorForm->addError('zip', '郵便番号の入力内容をお確かめください。');
                    }
                }


            }
        } 
    
        return $errorForm;
    }
        /**
     * POST情報から入力フォームを生成します。
     *
     * 全ての値は正規化されてフォームに設定されます。
     *
     * @param array $post ポスト情報
     * @param Sgmov_Form_Rec002In $creditCardForm 入力フォーム
     * @return Sgmov_Form_Rec001In 入力フォーム
     */
    public function _createInFormFromPost($post, $creditCardForm) {
        // DB接続
        $db = Sgmov_Component_DB::getPublic();

        $inForm = new Sgmov_Form_Rec001In();

        $inForm->personal_name = filter_input(INPUT_POST, 'personal_name');

        $inForm->personal_name_furi = filter_input(INPUT_POST, 'personal_name_furi');

        $inForm->sei = filter_input(INPUT_POST, 'sei');

        $inForm->age = filter_input(INPUT_POST, 'age');

        $inForm->zip1 = mb_convert_kana(filter_input(INPUT_POST, 'zip1'), 'rnask', 'UTF-8');
        $inForm->zip2 = mb_convert_kana(filter_input(INPUT_POST, 'zip2'), 'rnask', 'UTF-8');
        $inForm->pref_id = filter_input(INPUT_POST, 'pref_id');
        $inForm->address = mb_convert_kana(filter_input(INPUT_POST, 'address'), 'RNASKV', 'UTF-8');

        $inForm->building = filter_input(INPUT_POST, 'building');

        $inForm->tel = filter_input(INPUT_POST, 'tel');
        $inForm->mail = filter_input(INPUT_POST, 'mail');

        $inForm->employ_cd = filter_input(INPUT_POST, 'employ_cd');

        $inForm->center_id = filter_input(INPUT_POST, 'center_id');

        $inForm->occupation_cd = filter_input(INPUT_POST, 'occupation_cd');

        $inForm->question = filter_input(INPUT_POST, 'question');

        $inForm->center_id_hidden = filter_input(INPUT_POST, 'center_id_hidden');

        $inForm->occupation_cd_hidden = filter_input(INPUT_POST, 'occupation_cd_hidden');

        $inForm->current_employment_status = filter_input(INPUT_POST, 'current_employment_status');

        $inForm->contact_time = $this->cstm_filter_input_array(INPUT_POST, 'contact_time', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY, 'rnask');
        $inForm->chb_agreement = filter_input(INPUT_POST, 'chb_agreement');
        
        return $inForm;
    }

    public function validateZipCode($inform){
        $zipCode = $inform->zip1.$inform->zip2;

        try{
            $receive = $this->_SocketZipCodeDll->searchByZipCode($zipCode);
            if(empty($receive) || !array_key_exists('CityName', $receive) || !array_key_exists('KenName', $receive) || !array_key_exists('TownName', $receive)){
                    return array();//Invalid zip code
            }else{
                $ken = $receive['JIS2Code'];
                $cityName = $receive['CityName'];
                $kenName = $receive['KenName'];
                $townName = $receive['TownName'];
                if($ken == '' || $receive == '' || $kenName == '' || $townName == ''){
                    return array();//Invalid zip code
                }else{
                    return array(
                        "ken" => $receive['JIS2Code'],
                        "cityName" => $receive['CityName'],
                        "kenName" => $receive['KenName'],
                        "townName" => $receive['TownName']
                    );//Real zip code
                }
            }
        } catch (Exception $ex) {
            $this->debug($ex->getMessage());
            return array();//Invalid zip code
        }
    }


    /**
     *
     * @param type $type
     * @param type $variable_name
     * @param type $filter
     * @param type $options
     * @return type
     */
    private function cstm_filter_input_array($type, $variable_name, $filter = FILTER_DEFAULT, $options = null, $mbConvKanaOpt = NULL) {
        $res = filter_input($type, $variable_name, $filter, $options);

        if(empty($res)) {
            return array();
        }

        if(is_array($res) && !empty($mbConvKanaOpt)) {
            $resultList = array();
            foreach($res as $key => $val) {
                $resultList[$key] = mb_convert_kana($val, $mbConvKanaOpt, 'UTF-8');
            }
            $res = $resultList;
        }

        return $res;
    }

    /**
 * Check emoji from string
 * 
 * @return bool if existed emoji in string
 */
function checkEmoji($str) 
{
    $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
    preg_match($regexEmoticons, $str, $matches_emo);
    if (!empty($matches_emo[0])) {
        return false;
    }
    
    // Match Miscellaneous Symbols and Pictographs
    $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
    preg_match($regexSymbols, $str, $matches_sym);
    if (!empty($matches_sym[0])) {
        return false;
    }

    // Match Transport And Map Symbols
    $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
    preg_match($regexTransport, $str, $matches_trans);
    if (!empty($matches_trans[0])) {
        return false;
    }
   
    // Match Miscellaneous Symbols
    $regexMisc = '/[\x{2600}-\x{26FF}]/u';
    preg_match($regexMisc, $str, $matches_misc);
    if (!empty($matches_misc[0])) {
        return false;
    }

    // Match Dingbats
    $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
    preg_match($regexDingbats, $str, $matches_bats);
    if (!empty($matches_bats[0])) {
        return false;
    }

    return true;
}
}   