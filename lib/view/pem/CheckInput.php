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
Sgmov_Lib::useView('pem/Common');
Sgmov_Lib::useForms(array('Error', 'PemSession', 'Pem001In'));
/**#@-*/
/**
 * 採用エントリー入力情報をチェックします。
 * @package    View
 * @subpackage PEM
 * @author     K.Hamada(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pem_CheckInput extends Sgmov_View_Pem_Common {
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
     *   pem/confirm へリダイレクト
     *   </li></ol>
     * </li><li>
     * 入力エラー有り
     *   <ol><li>
     *   pem/input へリダイレクト
     *   </li></ol>
     * </li></ol>
     */
    public function executeInner() {
    	
        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();
        
        // チケットの確認と破棄
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_PEM001, $this->_getTicket());
        
        // DB接続
        $db = Sgmov_Component_DB::getPublic();
        
        // 入力チェック
        $inForm = $this->_createInFormFromPost($_POST);
        $errorForm = $this->_validate($inForm, $db);
        
        // 情報をセッションに保存
        $sessionForm = new Sgmov_Form_PemSession();
        $sessionForm->in = $inForm;
        $sessionForm->error = $errorForm;
        if ($errorForm->hasError()) {
            $sessionForm->status = self::VALIDATION_FAILED;
        } else {
            $sessionForm->status = self::VALIDATION_SUCCEEDED;
        }
        $session->saveForm(self::FEATURE_ID, $sessionForm);
        
        // リダイレクト
        if ($errorForm->hasError()) {
            Sgmov_Component_Redirect::redirectPublicSsl('/pem/input');
        } else {
            Sgmov_Component_Redirect::redirectPublicSsl('/pem/confirm');
        }
    }

    /**
     * POST情報から入力フォームを生成します。
     * @param array $post ポスト情報
     * @return Sgmov_Form_Pem001In 入力フォーム
     */
    public function _createInFormFromPost($post) {
        $inForm = new Sgmov_Form_Pem001In();
        if (isset($post['employ_type_cd_sel'])) {
            $inForm->employ_type_cd_sel = $post['employ_type_cd_sel'];
        } else {
            $inForm->employ_type_cd_sel = '';
        }
        $inForm->job_type_cd_sel = $post['sntk_jobtype'];
        if (isset($post['work_place_flag_sels'])) {
            $inForm->work_place_flag_sels = $post['work_place_flag_sels'];
        } else {
            $inForm->work_place_flag_sels = '';
        }
        $inForm->name = $post['name'];
        $inForm->furigana = $post['furigana'];
        $inForm->age_cd_sel = $post['age_cd_sel'];
        $inForm->tel1 = $post['tel1'];
        $inForm->tel2 = $post['tel2'];
        $inForm->tel3 = $post['tel3'];
        $inForm->mail = $post['mail'];
        $inForm->zip1 = $post['zip1'];
        $inForm->zip2 = $post['zip2'];
        $inForm->pref_cd_sel = $post['pref_cd_sel'];
        $inForm->address = $post['address'];
        $inForm->resume = $post['resume'];
        return $inForm;
    }
    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_Pem001In $inForm 入力フォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($inForm, $db) {
    	
        // 都道府県を取得
        $service = new Sgmov_Service_Prefecture();
        $prefs = $service->fetchPrefectures($db);
        
        // 勤務地を取得
        $service = new Sgmov_Service_Center();
        $centers = $service->fetchCenters($db);
       
        /**
         * 通常の入力ではありえない値の場合はシステムエラー
         */
        // 採用区分
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->employ_type_cd_sel)->isIn(array_keys($this->employ_type_lbls));
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        // 職種
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->job_type_cd_sel)->isIn(array_keys($this->job_type_lbls));
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        // 希望勤務地
        if (($inForm->work_place_flag_sels) != "") {
            $v = Sgmov_Component_Validator::createMultipleValueValidator($inForm->work_place_flag_sels)->isIn($centers['ids']);
            if (!$v->isValid()) {
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
            }
        }
        // 都道府県
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->pref_cd_sel)->isIn($prefs['ids']);
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        
        /**
         * 業務エラー
         */
        $errorForm = new Sgmov_Form_Error();
        // 採用区分コード選択値
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->employ_type_cd_sel)->isNotEmpty();
        if (!$v->isValid()) {
            $errorForm->addError('top_employ_type_cd_sel', $v->getResultMessageTop());
        }
        // 職種コード選択値
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->job_type_cd_sel)->isNotEmpty();
        if (!$v->isValid()) {
            $errorForm->addError('top_job_type_cd_sel', $v->getResultMessageTop());
        }
        // 勤務地選択値
        if (($inForm->work_place_flag_sels) == "") {
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->work_place_flag_sels)->isNotEmpty();
        } else {
            $v = Sgmov_Component_Validator::createMultipleValueValidator($inForm->work_place_flag_sels)->isNotEmpty();
        }
        if (!$v->isValid()) {
            $errorForm->addError('top_work_place_flag_sels', $v->getResultMessageTop());
        }

        // 採用区分/職種/勤務地の整合性チェック
        $tmpWorkPlaceSels = $inForm->work_place_flag_sels;

//20110507 営業所に名称統一したため、一度チェックをはずす
//        if ($inForm->job_type_cd_sel == 2) {
//        	
//        	// 職種が運転手の場合、営業所不可
//        	for ($i = 0; $i < count($centers); $i++) {
//        	    if (strpos($centers['names'][$i], self::STR_EIGYOSYO, 0)) {
//        	    	// 営業の名前が含まれる場合、そのエリアコードが希望勤務地コード（配列）に存在するか突合せ
//        	    	if (in_array($centers['ids'][$i], $tmpWorkPlaceSels)) {
//        	    		$errorForm->addError('top_work_place_flag_sels', "職種「運転手」の場合、希望勤務地に勤務地は選択できません。");
//        	    		break;
//        	    	}
//            	}
//        	}
//        }

        // 臨時社員（アルバイト）の場合、運転職は選択できません。
        if ($inForm->employ_type_cd_sel == 3 && $inForm->job_type_cd_sel == 2) {
        	$errorForm->addError('top_job_type_cd_sel', "臨時社員（アルバイト）の場合、運転職は選択できません。");
        }
        
        // お名前
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->name)->isNotEmpty()->isLengthLessThanOrEqualTo(40);
        if (!$v->isValid()) {
            $errorForm->addError('top_name', $v->getResultMessageTop());
        }
        // フリガナ
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->furigana)->isNotEmpty()->isLengthLessThanOrEqualTo(40);
        if (!$v->isValid()) {
            $errorForm->addError('top_furigana', $v->getResultMessageTop());
        }
        // 年齢コード選択値
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->age_cd_sel)->isNotEmpty();
        if (!$v->isValid()) {
            $errorForm->addError('top_age_cd_sel', $v->getResultMessageTop());
        }
        // 電話番号
        $v = Sgmov_Component_Validator::createPhoneValidator($inForm->tel1, $inForm->tel2, $inForm->tel3)->isNotEmpty()->isPhone();
        if (!$v->isValid()) {
            $errorForm->addError('top_tel', $v->getResultMessageTop());
        }
        // メールアドレス
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->mail)->isNotEmpty()->isLengthLessThanOrEqualTo(80)->isMail();
        if (!$v->isValid()) {
            $errorForm->addError('top_mail', $v->getResultMessageTop());
        }
        // 郵便番号(最後に存在確認をするので別名でバリデータを作成)
        $zipV = Sgmov_Component_Validator::createZipValidator($inForm->zip1, $inForm->zip2)->isZipCode();
        if (!$zipV->isValid()) {
            $errorForm->addError('top_zip', $zipV->getResultMessageTop());
        }
        // 都道府県
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->pref_cd_sel);
        $v->isSelected();
        if (!$v->isValid()) {
            $errorForm->addError('top_pref_cd_sel', $v->getResultMessageTop());
        }
        // 住所
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->address)->isNotEmpty()->isLengthLessThanOrEqualTo(80);
        if (!$v->isValid()) {
            $errorForm->addError('top_address', $v->getResultMessageTop());
        }
        // 志望動機・自己PR
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->resume)->isNotEmpty()->isLengthLessThanOrEqualTo(1000);
        if (!$v->isValid()) {
            $errorForm->addError('top_resume', $v->getResultMessageTop());
        }
        // エラーがない場合は郵便番号存在チェック
        if (!$errorForm->hasError()) {
            $zipV->zipCodeExist();
            if (!$zipV->isValid()) {
                $errorForm->addError('top_zip', $zipV->getResultMessageTop());
            }
        }

        return $errorForm;
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
