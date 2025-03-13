<?php
/**
* 09_会員情報登録・変更。。
* @package    View
* @subpackage event/CheckInputUpdateInfo
* @author     GiapLN(FPT Software) 
*/

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('event/Common');
Sgmov_Lib::useForms(array('Error', 'UserSession', 'UserUpdateInfo001In'));
Sgmov_Lib::useServices(array('EventLogin','Comiket','CenterMail','Prefecture'));

class Sgmov_View_Event_CheckInputUpdateInfo extends Sgmov_View_Event_Common
{
    protected $_ComiketService;
    
    protected $_EventLoginService;
    
    protected $_CenterMailService;
    
    protected $_PrefectureService;
    
    public function __construct() {
        $this->_ComiketService       = new Sgmov_Service_Comiket();
        $this->_EventLoginService = new Sgmov_Service_EventLogin();
        $this->_CenterMailService = new Sgmov_Service_CenterMail();
        $this->_PrefectureService = new Sgmov_Service_Prefecture();
        parent::__construct();
    }
    
    public function executeInner()
    {
        $db = Sgmov_Component_DB::getPublic();
        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();
        $eventNm = $_SESSION[self::FEATURE_ID]['event_name'];
        
        $this->redirectWhenEventInvalid($db, $eventNm);
        // 入力チェック
        $inForm = $this->_createInFormFromPost($_POST);
        
        $email = $_SESSION[self::LOGIN_ID]['email'];
        $eLogins = $this->_EventLoginService->fetchEventLoginByEmail($db, $email);
        
        $errorForm = $this->_validate($inForm, $eLogins);

        // 情報をセッションに保存
        $sessionForm = new Sgmov_Form_UserSession();
        $sessionForm->in = $inForm;
        $sessionForm->error = $errorForm;
        if ($errorForm->hasError()) {
            $sessionForm->status = self::VALIDATION_FAILED;
        } else {
            $sessionForm->status = self::VALIDATION_SUCCEEDED;
        }
        
        $session->saveForm(self::UPDATE_INFO_ID, $sessionForm);
        
        // リダイレクト
        if ($errorForm->hasError()) {
            Sgmov_Component_Redirect::redirectPublicSsl('/event/updateInfo?event_nm='.$eventNm);
        } else {
            //check exists event_login
            //Update event_login
            $insertDate = date('Y-m-d H:i:s');
            //$email = $_SESSION[self::LOGIN_ID]['email'];
            $eventId = $_SESSION[self::FEATURE_ID]['event_id'];
            $eventSubId = $_SESSION[self::FEATURE_ID]['eventsub_id'];
            
            $dataRow = array(
                'name_sei'      => $inForm->comiket_personal_name_sei,
                'name_mei'      => $inForm->comiket_personal_name_mei,
                'zip'           => $inForm->comiket_zip1.$inForm->comiket_zip2,
                'pref_id'       => $inForm->comiket_pref_cd_sel,
                'address'       => $inForm->comiket_address,
                'building'      => $inForm->comiket_building,
                'tel'           => $inForm->comiket_tel,
                'password_update_flag' => 0, 
                'modified'             => $insertDate
            );
            if (!empty($inForm->password)) {
                $password =  $inForm->password;
                //GiapLN fix bug SMT6-112 2022/03/26
                //$pass = crypt($password, md5($email));
                $pass = md5($email.$password);
                $dataRow['password'] = $pass;
            }
            $dataRow['id'] = $_SESSION[self::LOGIN_ID]['id'];

            $this->_EventLoginService->updateMemberInfo($db, $dataRow);
            $_SESSION[self::LOGIN_ID]['password_update_flag'] = 0;
            
            $keyLogin  = strtoupper($eventNm).'_LOGIN';
            $_SESSION[$keyLogin] = $_SESSION[self::LOGIN_ID];
            
            //GiapLN fix bug SMT6-111 2022/03/26
            // セッション破棄
            $session->deleteForm(self::UPDATE_INFO_ID);
            //check exists comiket 
            $eComiket = $this->_ComiketService->fetchComiketData($db, array($email, $eventId, $eventSubId));
            if (empty($eComiket)) {
                //redirect to input screen
                Sgmov_Component_Redirect::redirectPublicSsl('/'.$eventNm.'/input');
            } else {
                //redirect to input history
                Sgmov_Component_Redirect::redirectPublicSsl('/event/inputHistory?event_nm='.$eventNm);
            }
        }
                
    }


    /**
     * POST情報から入力フォームを生成します。
     * @param array $post ポスト情報
     * @return Sgmov_Form_Login001In 
     */
    public function _createInFormFromPost($post)
    {
        $inForm = new Sgmov_Form_UserUpdateInfo001In();
        
        $inForm->comiket_personal_name_sei = mb_convert_kana(filter_input(INPUT_POST, 'comiket_personal_name_sei'), 'RNASKV', 'UTF-8');
        $inForm->comiket_personal_name_mei = mb_convert_kana(filter_input(INPUT_POST, 'comiket_personal_name_mei'), 'RNASKV', 'UTF-8');
        $inForm->comiket_zip1 = mb_convert_kana(filter_input(INPUT_POST, 'comiket_zip1'), 'rnask', 'UTF-8');
        $inForm->comiket_zip2 = mb_convert_kana(filter_input(INPUT_POST, 'comiket_zip2'), 'rnask', 'UTF-8');
        $inForm->comiket_pref_cd_sel = filter_input(INPUT_POST, 'comiket_pref_cd_sel');
        $inForm->comiket_address = mb_convert_kana(filter_input(INPUT_POST, 'comiket_address'), 'RNASKV', 'UTF-8');
        $inForm->comiket_building = mb_convert_kana(filter_input(INPUT_POST, 'comiket_building'), 'RNASKV', 'UTF-8');
        $inForm->comiket_tel = @str_replace(array('-', 'ー', '−', '―', '‐', 'ｰ'), "-", mb_convert_kana(filter_input(INPUT_POST, 'comiket_tel'), 'rnask', 'UTF-8'));
        
        
        
        $inForm->password_old = filter_input(INPUT_POST, 'password_old');
        $inForm->password = filter_input(INPUT_POST, 'password');
        $inForm->password_confirm = filter_input(INPUT_POST, 'password_confirm');
        
        return $inForm;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_Pin001In $inForm 入力フォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     
     */
    public function _validate($inForm, $eLogins)
    {
        // 都道府県を取得
        $db = Sgmov_Component_DB::getPublic();
        $prefectures = $this->_PrefectureService->fetchPrefectures($db);
        // 入力チェック
        $errorForm = new Sgmov_Form_Error();
        // お申込者の姓
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_personal_name_sei)->
                                        isNotEmpty()->
                                        isLengthLessThanOrEqualTo(8)->
                                        isNotHalfWidthKana()->
                                        isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('comiket_personal_name-seimei','お名前'.$v->getResultMessageTop());
        }



        // お申込者の名
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_personal_name_mei)->
                                        isNotEmpty()->
                                        isLengthLessThanOrEqualTo(8)->
                                        isNotHalfWidthKana()->
                                        isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('comiket_personal_name-seimei', 'お名前'.$v->getResultMessageTop());
        }
        
        // 郵便番号
        // 郵便番号(最後に存在確認をするので別名でバリデータを作成) 必須チェック
        $zipV = Sgmov_Component_Validator::createZipValidator($inForm->comiket_zip1, $inForm->comiket_zip2)->isNotEmpty()->isZipCode();
        if (!$zipV->isValid()) {
            $errorForm->addError('comiket_zip', '郵便番号' . $zipV->getResultMessageTop());
        }
        // 都道府県
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_pref_cd_sel)->isSelected()->isIn($prefectures['ids']);
        if (!$v->isValid()) {
            $errorForm->addError('comiket_pref', '都道府県' . $v->getResultMessageTop());
        }

        // 市区町村 必須チェック 40文字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_address)->isNotEmpty()->isLengthLessThanOrEqualTo(14)->
                isNotHalfWidthKana()->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('comiket_address', '市区町村' . $v->getResultMessageTop());
        } else {
            $prefName = @$prefectures["names"][$prefectures["ids"][$inForm->comiket_pref_cd_sel]];
            if (strpos($inForm->comiket_address, $prefName) !== false) {
                $errorForm->addError('comiket_address', '市区町村には都道府県名は入力しないで下さい。');
            }
        }
        
        
        // 番地・建物名・部屋番号 必須チェック 40文字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_building)->isNotEmpty()->isLengthLessThanOrEqualTo(30)->
                isNotHalfWidthKana()->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('comiket_building', '番地・建物名・部屋番号' . $v->getResultMessageTop());
        } else {
            $prefName = @$prefectures["names"][$prefectures["ids"][$inForm->comiket_pref_cd_sel]];
            if (strpos($inForm->comiket_building, $prefName) !== false) {
                $errorForm->addError('comiket_building', '番地・建物名・部屋番号には都道府県名は入力しないで下さい。');
            }
        }

	// 電話番号 必須チェック 型チェック WEBシステムNG文字チェック
        $comiketTel = @str_replace(array('-', 'ー', '−', '―', '‐', 'ｰ'), "", $inForm->comiket_tel);
        //GiapLN imp ticket #SMT6-381 2022/12/29
        $v = Sgmov_Component_Validator::createSingleValueValidator($comiketTel)->isNotEmpty()->isPhoneHyphen()->isLengthLessThanOrEqualToForPhone();
        if (!$v->isValid()) {
            $errorForm->addError('comiket_tel', '電話番号' . $v->getResultMessageTop());
        } else {
            $v = Sgmov_Component_Validator::createSingleValueValidator($comiketTel)->isLengthMoreThanOrEqualTo(8)->isLengthLessThanOrEqualTo(12);
            if (!$v->isValid()) {
                $errorForm->addError('comiket_tel', '電話番号の数値部分' . $v->getResultMessageTop());
            }
        }
	
        if (!$errorForm->hasError()) {
            // 郵便番号と住所を確認する。
            $aKey = array_search($inForm->comiket_pref_cd_sel, $prefectures['ids']);
            //GiapLN fix bug SMT6-242 2022.08.18
//            $addressResult = $this->_getAddressByZip($inForm->comiket_zip1.$inForm->comiket_zip2);
//            
//            if (empty($addressResult)) {
//                $errorForm->addError('comiket_zip', '郵便番号の入力内容をお確かめください。');
//	    } else {
//                $isInvalidKenName = $addressResult['kenName'] !== trim($prefectures['names'][$aKey]);
//                // 住所がソケット通信で取得した住所から始まらない場合にtrue
//                $systemTownName = $addressResult['cityName'] . $addressResult['townName'];
//                $userInputTownName = trim($inForm->comiket_address) . trim($inForm->comiket_building);
//                $isInvalidTownName = strpos($userInputTownName, $systemTownName) !== 0;
//                if ($isInvalidKenName || $isInvalidTownName) {
//                    $errorForm->addError('comiket_zip', '郵便番号とお住所は合わせてください。');
//                }
//	    }
            
            $receive = $this->_getAddress($inForm->comiket_zip1.$inForm->comiket_zip2
                        , $prefectures['names'][$aKey].trim($inForm->comiket_address) .trim($inForm->comiket_building));
            $isErrorAddress = false;
            if (!empty($receive)) {
                if ($inForm->comiket_zip1.$inForm->comiket_zip2 != $receive['ZipCode']) {
                    $isErrorAddress = true;
                }
            } else {
                $isErrorAddress = true; 
            }
            if ($isErrorAddress) {
                $errorForm->addError('comiket_address', '郵便番号とお住所は合わせてください。');
            }
        }

        //新パスワード
        if ($eLogins['password_update_flag'] == 1) {
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->password_old)->
                                            isNotEmpty();
            if (!$v->isValid()) {
                $errorForm->addError('top_password_old', '現パスワード'.$v->getResultMessageTop());
            }
            
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->password)->
                                            isNotEmpty();
            if (!$v->isValid()) {
                $errorForm->addError('top_password', '新パスワード'.$v->getResultMessageTop());
            }
            
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->password_confirm)->
                                            isNotEmpty();
            if (!$v->isValid()) {
                $errorForm->addError('top_password_confirm', 'パスワードの確認入力'.($v->getResultMessageTop()));
            }
        }
        //if (!$errorForm->hasError()) {
        if (!empty($inForm->password_old)) {
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->password)->
                                        isNotEmpty();
            if (!$v->isValid()) {
                $errorForm->addError('top_password', '新パスワード'.$v->getResultMessageTop());
            }
        }
        //}
        //現パスワード
        //if (!$errorForm->hasError()) {
        if (!empty($inForm->password)) {
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->password_old)->
                                        isNotEmpty();
            if (!$v->isValid()) {
                $errorForm->addError('top_password_old', '現パスワード'.$v->getResultMessageTop());
            }
        }
       // }
        //現パスワード
        //GiapLN fix bug SMT6-110 2022/03/26
        //if (!$errorForm->hasError()) {
            if (!empty($inForm->password_old)) {
                $password = $inForm->password_old;
//                $errorPassStr = $this->checkpas($password);
//                if (!empty($errorPassStr)) {
//                    $errorForm->addError('top_password_old', $errorPassStr);
//                } else {
                    $email = $_SESSION[self::LOGIN_ID]['email'];
                    //$eLogin = $this->_EventLoginService->fetchEventLoginByEmail($db, $email);
                    //GiapLN fix bug SMT6-112 2022/03/26
                    //$pass = crypt($password, md5($email));
                    $pass = md5($email.$password);
                    //check exists password 
                    if ($eLogins['password'] != $pass) {
                        $errorForm->addError('top_password_old', '現パスワードをお確かめください。');
                    }
                //}
            }
        //}
        //
        //新パスワード
        //if (!$errorForm->hasError()) {
            if (!empty($inForm->password) || !empty($inForm->password_confirm)) {
                if ($inForm->password != $inForm->password_confirm) {
                    $errorForm->addError('top_password_confirm', 'パスワードの確認入力をお確かめください。');
                }  else {
                    $password =  $inForm->password;
                    $errorPassStr = $this->checkpas($password);
                    if (!empty($errorPassStr)) {
                        $errorForm->addError('top_password', '新パスワード'.$errorPassStr);
                    }
                }
            }
        //}
        if (!$errorForm->hasError()) {
            if (!empty($inForm->password) && !empty($inForm->password_old)) {
                if ($inForm->password === $inForm->password_old) {
                    $errorForm->addError('top_password', '新パスワードは旧パスワードと違って入力してください。');
                }
            }
        }
        return $errorForm;
    }
}
?>
