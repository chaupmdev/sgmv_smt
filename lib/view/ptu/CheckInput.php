<?php
/**
 * @package    ClassDefFile
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/**#@+
 * include files
 */
require_once dirname(__FILE__).'/../../Lib.php';
Sgmov_Lib::useView('ptu/Common');
Sgmov_Lib::useForms(array('Error', 'PtuSession', 'Ptu001In'));
/**#@-*/
/**
 * 単身カーゴプランのお申し込み入力情報をチェックします。
 * @package    View
 * @subpackage ptu
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Ptu_CheckInput extends Sgmov_View_Ptu_Common {

	/**
	* カーゴ都道府県サービス
	* @var Sgmov_Service_MstCargoArea
	*/
	private $_MstCargoArea;

    /**
     * 郵便番号DLLサービス
     * @var Sgmov_Service_SocketZipCodeDll
     */
    public $_SocketZipCodeDll;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
    	$this->_MstCargoArea   		  = new Sgmov_Service_MstCargoArea();
        $this->_SocketZipCodeDll      = new Sgmov_Service_SocketZipCodeDll();
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
     *   ptu/confirm へリダイレクト
     *   </li></ol>
     * </li><li>
     * 入力エラー有り
     *   <ol><li>
     *   ptu/input へリダイレクト
     *   </li></ol>
     * </li></ol>
     */
    public function executeInner() {

        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();

        // DB接続
        $db = Sgmov_Component_DB::getPublic();

        // チケットの確認と破棄
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_PTU001, $this->_getTicket());

        // 入力チェック
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        if (empty($sessionForm)) {
            $sessionForm = new Sgmov_Form_PtuSession();
            $sessionForm->in = null;
        }
        $inForm = $this->_createInFormFromPost($_POST, $sessionForm->in);
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
        // リダイレクト
        if ($errorForm->hasError()) {
            Sgmov_Component_Redirect::redirectPublicSsl('/ptu/input');
        }
        switch ($inForm->payment_method_cd_sel) {
            case '1':
                Sgmov_Component_Redirect::redirectPublicSsl('/ptu/confirm');
                break;
            case '2':
                Sgmov_Component_Redirect::redirectPublicSsl('/ptu/credit_card');
                break;
        }
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

    /**
     * POST情報から入力フォームを生成します。
     *
     * 全ての値は正規化されてフォームに設定されます。
     *
     * @param array $post ポスト情報
     * @param Sgmov_Form_Ptu002In $creditCardForm 入力フォーム
     * @return Sgmov_Form_Ptu001In 入力フォーム
     */
    public function _createInFormFromPost($post, $creditCardForm) {
        $inForm = new Sgmov_Form_Ptu001In();

//         $post = Sgmov_Component_String::normalizeInput($post);

        // チケット
        $inForm->ticket = $post['ticket'];
        // 便種
        $inForm->binshu_cd = $post['binshu_cd'];

        $inForm->surname  = mb_convert_kana($post['surname'], "ASKV", "UTF-8");
        $inForm->forename = mb_convert_kana($post['forename'], "ASKV", "UTF-8");

        $inForm->tel1 = $post['tel1'];
        $inForm->tel2 = $post['tel2'];
        $inForm->tel3 = $post['tel3'];

        $inForm->fax1 = $post['fax1'];
        $inForm->fax2 = $post['fax2'];
        $inForm->fax3 = $post['fax3'];

        $inForm->mail        = $post['mail'];
        $inForm->retype_mail = $post['retype_mail'];

        $inForm->zip1        = $post['zip1'];
        $inForm->zip2        = $post['zip2'];
        $inForm->pref_cd_sel = $post['pref_cd_sel'];
        $inForm->address     = mb_convert_kana($post['address'], "ASKV", "UTF-8");
        $inForm->building    = mb_convert_kana($post['building'], "ASKV", "UTF-8");

        $inForm->surname_hksaki  = mb_convert_kana($post['surname_hksaki'], "ASKV", "UTF-8");
        $inForm->forename_hksaki = mb_convert_kana($post['forename_hksaki'], "ASKV", "UTF-8");

        $inForm->zip1_hksaki        = $post['zip1_hksaki'];
        $inForm->zip2_hksaki        = $post['zip2_hksaki'];
        $inForm->pref_cd_sel_hksaki = $post['pref_cd_sel_hksaki'];
        $inForm->address_hksaki     = mb_convert_kana($post['address_hksaki'], "ASKV", "UTF-8");
        $inForm->building_hksaki    = mb_convert_kana($post['building_hksaki'], "ASKV", "UTF-8");
        $inForm->tel1_hksaki = $post['tel1_hksaki'];
        $inForm->tel2_hksaki = $post['tel2_hksaki'];
        $inForm->tel3_hksaki = $post['tel3_hksaki'];
        $inForm->tel1_fuzai_hksaki = $post['tel1_fuzai_hksaki'];
        $inForm->tel2_fuzai_hksaki = $post['tel2_fuzai_hksaki'];
        $inForm->tel3_fuzai_hksaki = $post['tel3_fuzai_hksaki'];

        $inForm->hikitori_yotehiji_date_year_cd_sel  = $post['hikitori_yotehiji_date_year_cd_sel'];
        $inForm->hikitori_yotehiji_date_month_cd_sel = $post['hikitori_yotehiji_date_month_cd_sel'];
        $inForm->hikitori_yotehiji_date_day_cd_sel   = $post['hikitori_yotehiji_date_day_cd_sel'];

        $inForm->hikitori_yoteji_sel    			 = $post['hikitori_yoteji_sel'];
        if ($post['hikitori_yoteji_sel'] == '2') {
        	$inForm->hikitori_yotehiji_time_cd_sel   = $post['hikitori_yotehiji_time_cd_sel'];
        } else if ($post['hikitori_yoteji_sel'] == '3') {
        	$inForm->hikitori_yotehiji_justime_cd_sel= $post['hikitori_yotehiji_justime_cd_sel'];
        }

        $inForm->hikoshi_yotehiji_date_year_cd_sel  = $post['hikoshi_yotehiji_date_year_cd_sel'];
        $inForm->hikoshi_yotehiji_date_month_cd_sel = $post['hikoshi_yotehiji_date_month_cd_sel'];
        $inForm->hikoshi_yotehiji_date_day_cd_sel   = $post['hikoshi_yotehiji_date_day_cd_sel'];

        $inForm->hikoshi_yoteji_sel    			 = $post['hikoshi_yoteji_sel'];
        if ($post['hikoshi_yoteji_sel'] == '2') {
        	$inForm->hikoshi_yotehiji_time_cd_sel    = $post['hikoshi_yotehiji_time_cd_sel'];
        } else if ($post['hikoshi_yoteji_sel'] == '3') {
        	$inForm->hikoshi_yotehiji_justime_cd_sel= $post['hikoshi_yotehiji_justime_cd_sel'];
        }

        if ($post['binshu_cd'] == self::BINSHU_TANPINYOSO) {
        	$inForm->tanhin_cd_sel = $post['tanhin_cd_sel'];
        	$inForm->tanNmFree = $post['tanNmFree'];
        } else {
        	$inForm->cago_daisu = $post['cago_daisu'];
        }

        $inForm->hidden_kihonKin = $post['hidden_kihonKin'];
        $inForm->hidden_hanshutsuSum = $post['hidden_hanshutsuSum'];
        $inForm->hidden_hannyuSum = $post['hidden_hannyuSum'];
        $inForm->hidden_mitumoriZeinuki = $post['hidden_mitumoriZeinuki'];
        $inForm->hidden_zeiKin = $post['hidden_zeiKin'];
        $inForm->hidden_mitumoriZeikomi = $post['hidden_mitumoriZeikomi'];

        $inForm->checkboxHanshutsu = filter_input(INPUT_POST, 'checkboxHanshutsu');
        $inForm->textHanshutsu = $post['textHanshutsu'];
        $inForm->checkboxHannyu = filter_input(INPUT_POST, 'checkboxHannyu');
        $inForm->textHannyu = $post['textHannyu'];

        $inForm->payment_method_cd_sel    = filter_input(INPUT_POST, 'payment_method_cd_sel');
        $inForm->convenience_store_cd_sel = filter_input(INPUT_POST, 'convenience_store_cd_sel');

        // TODO オブジェクトから値を直接取得できるよう修正する
        // オブジェクトから取得できないため、配列に型変換
        $creditCardForm = (array)$creditCardForm;

        $inForm->card_number = isset($creditCardForm['card_number']) ? $creditCardForm['card_number'] : '';
        $inForm->card_expire_month_cd_sel = isset($creditCardForm['card_expire_month_cd_sel']) ? $creditCardForm['card_expire_month_cd_sel'] : '';
        $inForm->card_expire_year_cd_sel = isset($creditCardForm['card_expire_year_cd_sel']) ? $creditCardForm['card_expire_year_cd_sel'] : '';
        $inForm->security_cd = isset($creditCardForm['security_cd']) ? $creditCardForm['security_cd'] : '';

        return $inForm;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_Ptu001In $inForm 入力フォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($inForm, $db) {
        // 都道府県のリストを取得しておく
        $prefectures = $this->_MstCargoArea->fetchCargoAreas($db);
        $convenience = ($inForm->payment_method_cd_sel === '1');

        $hikitori_yotehiji_time_cds = array_keys($this->cargo_collection_st_time_lbls);
        $hikoshi_yotehiji_time_cds = array_values($this->cargo_collection_st_time_lbls);

        // 入力チェック
        $errorForm = new Sgmov_Form_Error();

        // お名前 姓 必須チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->surname)->isNotEmpty()->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_surname', $v->getResultMessageTop());
        }
        // お名前 名 必須チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->forename)->isNotEmpty()->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_forename', $v->getResultMessageTop());
        }
        // お名前 姓名 64文字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->surname.$inForm->forename)->isOverKetasuMax(64)->isWebSystemNg();
        if (!$v->isValid()) {
        	$errorForm->addError('top_name', $v->getResultMessageTop());
        }
        // 電話番号 必須チェック 型チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createPhoneValidator($inForm->tel1, $inForm->tel2, $inForm->tel3)->isNotEmpty()->isPhone()->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_tel', $v->getResultMessageTop());
        }
        // FAX番号 型チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createPhoneValidator($inForm->fax1, $inForm->fax2, $inForm->fax3)->isPhone()->isWebSystemNg();
        if (!$v->isValid()) {
        	$errorForm->addError('top_fax', $v->getResultMessageTop());
        }
        // メールアドレス 必須チェック 80文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->mail)->isNotEmpty()->isMail()->isOverKetasuMax(80)->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_mail', $v->getResultMessageTop());
        }
        // メールアドレス確認 必須チェック 80文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->retype_mail)->isNotEmpty()->isMail()->isOverKetasuMax(80)->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_retype_mail', $v->getResultMessageTop());
        }
        // 郵便番号(最後に存在確認をするので別名でバリデータを作成) 必須チェック
//         if (!empty($inForm->zip1) || !empty($inForm->zip2)) {
        	$zipV = Sgmov_Component_Validator::createZipValidator($inForm->zip1, $inForm->zip2)->isNotEmpty()->isZipCode();
        	if (!$zipV->isValid()) {
        		$errorForm->addError('top_zip', $zipV->getResultMessageTop());
        	}
//         }
        // 都道府県 値範囲チェック 必須チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->pref_cd_sel)->isSelected();
        if (!$v->isValid()) {
            $errorForm->addError('top_pref_cd_sel', $v->getResultMessageTop());
        }
        // 市区町村 必須チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->address)->isNotEmpty()->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_address', $v->getResultMessageTop());
        }
        // 番地・建物名 必須チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->building)->isNotEmpty()->isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_building', $v->getResultMessageTop());
        }
        // 市区町村と番地・建物名 56文字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->address.$inForm->building)->isOverKetasuMax(56)->isWebSystemNg();
        if (!$v->isValid()) {
        	$errorForm->addError('top_addressbuild', $v->getResultMessageTop());
        }

        // お名前 姓 必須チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->surname_hksaki)->isNotEmpty()->isWebSystemNg();
        if (!$v->isValid()) {
        	$errorForm->addError('top_surname_hksaki', $v->getResultMessageTop());
        }
        // お名前 名 必須チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->forename_hksaki)->isNotEmpty()->isWebSystemNg();
        if (!$v->isValid()) {
        	$errorForm->addError('top_forename_hksaki', $v->getResultMessageTop());
        }
        // お名前 姓名 64文字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->surname_hksaki.$inForm->forename_hksaki)->isOverKetasuMax(64)->isWebSystemNg();
        if (!$v->isValid()) {
        	$errorForm->addError('top_name_hksaki', $v->getResultMessageTop());
        }
        // 郵便番号(最後に存在確認をするので別名でバリデータを作成) 必須チェック
//         if (!empty($inForm->zip1_hksaki) || !empty($inForm->zip2_hksaki)) {
        	$zipV1 = Sgmov_Component_Validator::createZipValidator($inForm->zip1_hksaki, $inForm->zip2_hksaki)->isNotEmpty()->isZipCode();
        	if (!$zipV1->isValid()) {
        		$errorForm->addError('top_zip_hksaki', $zipV1->getResultMessageTop());
        	}
//         }
        // 都道府県 値範囲チェック 必須チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->pref_cd_sel_hksaki)->isSelected();
        if (!$v->isValid()) {
        	$errorForm->addError('top_pref_cd_sel_hksaki', $v->getResultMessageTop());
        }
        // 市区町村 必須チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->address_hksaki)->isNotEmpty()->isWebSystemNg();
        if (!$v->isValid()) {
        	$errorForm->addError('top_address_hksaki', $v->getResultMessageTop());
        }
        // 番地・建物名 必須チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->building_hksaki)->isNotEmpty()->isWebSystemNg();
        if (!$v->isValid()) {
        	$errorForm->addError('top_building_hksaki', $v->getResultMessageTop());
        }
        // 市区町村と番地・建物名 56文字チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->address_hksaki.$inForm->building_hksaki)->isOverKetasuMax(88)->isWebSystemNg();
        if (!$v->isValid()) {
        	$errorForm->addError('top_addressbuild_hksaki', $v->getResultMessageTop());
        }
        // 電話番号 必須チェック 型チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createPhoneValidator($inForm->tel1_hksaki, $inForm->tel2_hksaki, $inForm->tel3_hksaki)->isNotEmpty()->isPhone()->isWebSystemNg();
        if (!$v->isValid()) {
        	$errorForm->addError('top_tel_hksaki', $v->getResultMessageTop());
        }
        // 不在電話番号 型チェック WEBシステムNG文字チェック
        $v = Sgmov_Component_Validator::createPhoneValidator($inForm->tel1_fuzai_hksaki, $inForm->tel2_fuzai_hksaki, $inForm->tel3_fuzai_hksaki)->isPhone()->isWebSystemNg();
        if (!$v->isValid()) {
        	$errorForm->addError('top_tel_fuzai_hksaki', $v->getResultMessageTop());
        }

        // 引取り予定日 必須チェック
        // 年
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->hikitori_yotehiji_date_year_cd_sel)->isSelected();
        $is_valid = $v->isValid();
        if (!$is_valid) {
        	$errorForm->addError('top_hikitori_yotehiji_date', $v->getResultMessageTop());
        }
        // 月
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->hikitori_yotehiji_date_month_cd_sel)->isSelected();
        $is_valid = $v->isValid();
        if (!$is_valid) {
        	$errorForm->addError('top_hikitori_yotehiji_date', $v->getResultMessageTop());
        }
        // 日
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->hikitori_yotehiji_date_day_cd_sel)->isSelected();
        $is_valid = $v->isValid();
        if (!$is_valid) {
        	$errorForm->addError('top_hikitori_yotehiji_date', $v->getResultMessageTop());
        }

        // 引越し予定日 必須チェック
        // 年
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->hikoshi_yotehiji_date_year_cd_sel)->isSelected();
        $is_valid = $v->isValid();
        if (!$is_valid) {
        	$errorForm->addError('top_hikoshi_yotehiji_date', $v->getResultMessageTop());
        }
        // 月
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->hikoshi_yotehiji_date_month_cd_sel)->isSelected();
        $is_valid = $v->isValid();
        if (!$is_valid) {
        	$errorForm->addError('top_hikoshi_yotehiji_date', $v->getResultMessageTop());
        }
        // 日
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->hikoshi_yotehiji_date_day_cd_sel)->isSelected();
        $is_valid = $v->isValid();
        if (!$is_valid) {
        	$errorForm->addError('top_hikoshi_yotehiji_date', $v->getResultMessageTop());
        }

            // お引取り予定日 必須チェック
            $v = Sgmov_Component_Validator::createDateValidator($inForm->hikitori_yotehiji_date_year_cd_sel,
                    $inForm->hikitori_yotehiji_date_month_cd_sel, $inForm->hikitori_yotehiji_date_day_cd_sel);
//             if (empty($inForm->travel_departure_cd_sel) || empty($departure['dates'])) {
                $max = null;
//                 $max_year  = null;
//                 $max_month = null;
//                 $max_day   = null;
//             } else {
                $date = new DateTime();
                $date->modify('+6 month');
//                 $max_year  = intval($date->format('Y'));
//                 $max_month = intval($date->format('m'));
//                 $max_day   = intval($date->format('d'));
                $max  = intval($date->format('U'));
//                 $date->modify('-6 day');
//                 $min  = intval($date->format('U'));
//             }
            $current_date = new DateTime('tomorrow');
            $current_time = intval($current_date->format('U'));
            if (empty($min) || $min < $current_time) {
                $min = $current_time;
            }
            $v->isSelected()->isDate($min, $max);
            if (!$v->isValid()) {
                $errorForm->addError('top_hikitori_yotehiji_date', $v->getResultMessageTop());
            }

            // お引越予定日 必須チェック
            $v = Sgmov_Component_Validator::createDateValidator($inForm->hikoshi_yotehiji_date_year_cd_sel,
            $inForm->hikoshi_yotehiji_date_month_cd_sel, $inForm->hikoshi_yotehiji_date_day_cd_sel);
//             $max = null;
//             $max_year  = null;
//             $max_month = null;
//             $max_day   = null;
//             $date = new DateTime();
//             $date->modify('+6 month');
//             $max  = intval($date->format('U'));
            $current_date = new DateTime('tomorrow');
            $current_time = intval($current_date->format('U'));

            $min_year  = intval($inForm->hikitori_yotehiji_date_year_cd_sel);
            $min_month = intval($inForm->hikitori_yotehiji_date_month_cd_sel);
            $min_day   = intval($inForm->hikitori_yotehiji_date_day_cd_sel);
            $min = mktime(0, 0, 0, $min_month, $min_day, $min_year);
            if (empty($min) || $min < $current_time) {
            	$min = $current_time;
            }
            $v->isSelected()->isDate($min, $max);
            if (!$v->isValid()) {
            	$errorForm->addError('top_hikoshi_yotehiji_date', $v->getResultMessageTop());
            }


            $hikoshi_year  = intval($inForm->hikoshi_yotehiji_date_year_cd_sel);
            $hikoshi_month = intval($inForm->hikoshi_yotehiji_date_month_cd_sel);
            $hikoshi_day   = intval($inForm->hikoshi_yotehiji_date_day_cd_sel);
            $hikoshi = mktime(0, 0, 0, $hikoshi_month, $hikoshi_day, $hikoshi_year);

            if ($hikoshi == $min) {
				// 時間帯チェック
		        if ($inForm->hikitori_yoteji_sel == '2' && $inForm->hikoshi_yoteji_sel == '2') {
		        	$hikitori = intval($inForm->hikitori_yotehiji_time_cd_sel);
		        	$hikoshi = intval($inForm->hikoshi_yotehiji_time_cd_sel);
		        	if ($hikitori > $hikoshi) {
		        		$errorForm->addError('top_hikoshi_yotehiji_time', 'はお引取り予定時間帯以降を選択してください。');
		        	}
		        } else if ($inForm->hikitori_yoteji_sel == '3' && $inForm->hikoshi_yoteji_sel == '3' ) {
		        	$hikitori = intval($inForm->hikitori_yotehiji_justime_cd_sel);
		        	$hikoshi = intval($inForm->hikoshi_yotehiji_justime_cd_sel);
		        	if ($hikitori > $hikoshi) {
		        		$errorForm->addError('top_hikoshi_yotehiji_time', 'はお引取り予定時間帯以降を選択してください。');
		        	}
		        } else if ($inForm->hikitori_yoteji_sel == '2' && $inForm->hikoshi_yoteji_sel == '3' ) {
		        	$hikitori = intval($inForm->hikitori_yotehiji_time_cd_sel);
		        	$hikoshi = intval($inForm->hikoshi_yotehiji_justime_cd_sel);
		        	if ( ($hikitori == 14 && $hikoshi < 14) || ($hikitori == 16 && $hikoshi < 18) || ($hikitori == 18 && $hikoshi < 22)) {
		        		$errorForm->addError('top_hikoshi_yotehiji_time', 'はお引取り予定時間帯以降を選択してください。');
		        	}
		        } else if ($inForm->hikitori_yoteji_sel == '3' && $inForm->hikoshi_yoteji_sel == '2' ) {
		        	$hikitori = intval($inForm->hikitori_yotehiji_justime_cd_sel);
		        	$hikoshi = intval($inForm->hikoshi_yotehiji_time_cd_sel);
		        	if ( ($hikoshi == 10 && $hikitori > 14) || ($hikoshi == 14 && $hikitori > 18) || ($hikoshi == 16 && $hikitori > 22)) {
		        		$errorForm->addError('top_hikoshi_yotehiji_time', 'はお引取り予定時間帯以降を選択してください。');
		        	}
		        }
            }

        if ($inForm->binshu_cd == self::BINSHU_TANPINYOSO) {
        	// 単品輸送
        	$tanhinSel = $inForm->tanhin_cd_sel;
        	$tanNmFree = $inForm->tanNmFree;
        	if(count($tanhinSel) > 0){
        		$cnt = count($tanhinSel);
//         		if ($cnt > 1) {
//         			$cnt--;
//         		}
        		for ($i=0;$i<$cnt;$i++){
        			$v = Sgmov_Component_Validator::createSingleValueValidator($tanhinSel[$i])->isSelected();
        			$is_valid = $v->isValid();
        			if (!$is_valid) {
        				$errorForm->addError('top_tanhin_cd_sel', $v->getResultMessageTop());
        			} else if ($tanhinSel[$i] == '99001' || $tanhinSel[$i] == '99002'|| $tanhinSel[$i] == '99003'
        				|| $tanhinSel[$i] == '99004'|| $tanhinSel[$i] == '99005'|| $tanhinSel[$i] == '99006'|| $tanhinSel[$i] == '99007') {

        				$v = Sgmov_Component_Validator::createSingleValueValidator($tanNmFree[$i])->isNotEmpty()->isWebSystemNg();
        				if (!$v->isValid()) {
        					$errorForm->addError('top_tanhin_cd_sel', $v->getResultMessageTop());
        				}
        			}
        		}
        	}
        } else {
        	// カーゴ台数
        	$v = Sgmov_Component_Validator::createSingleValueValidator($inForm->cago_daisu)->isNotEmpty()->isInteger(1,999)->isWebSystemNg();
        	$is_valid = $v->isValid();
        	if (!$is_valid) {
        		$errorForm->addError('top_cago_daisu', $v->getResultMessageTop());
        	}
        }

        $chkHanshutsuOpt = $inForm->checkboxHanshutsu;
        $flg = false;
        if (!empty($chkHanshutsuOpt)) {
        	if (in_array('004', $chkHanshutsuOpt)) {
        		$flg = true;
        	}
        }

 	    $textHst = $inForm->textHanshutsu;
        if(count($textHst) > 0){
        	for ($i=0;$i<count($textHst);$i++){

        		if ($flg) {
        			$v = Sgmov_Component_Validator::createSingleValueValidator($textHst[$i])->isNotEmpty()->isWebSystemNg();
        			if (!$v->isValid()) {
        				$errorForm->addError('top_textHanshutsu', $v->getResultMessageTop());
        			}
        		}

        		$v = Sgmov_Component_Validator::createSingleValueValidator($textHst[$i])->isInteger(0,999)->isWebSystemNg();
        		$is_valid = $v->isValid();
        		if (!$is_valid) {
        			$errorForm->addError('top_textHanshutsu', $v->getResultMessageTop());
        		}
        	}
        }

        $chkHannyuOpt = $inForm->checkboxHannyu;
        $flg = false;
        if (!empty($chkHannyuOpt)) {
        	if (in_array('017', $chkHannyuOpt)) {
        		$flg = true;
        	}
        }

        $textHyu = $inForm->textHannyu;
        if(count($textHyu) > 0){
        	for ($i=0;$i<count($textHyu);$i++){

        		if ($flg) {
        			$v = Sgmov_Component_Validator::createSingleValueValidator($textHyu[$i])->isNotEmpty()->isWebSystemNg();
        			if (!$v->isValid()) {
        				$errorForm->addError('top_textHannyu', $v->getResultMessageTop());
        			}
        		}

        		$v = Sgmov_Component_Validator::createSingleValueValidator($textHyu[$i])->isInteger(0,999)->isWebSystemNg();
        		$is_valid = $v->isValid();
        		if (!$is_valid) {
        			$errorForm->addError('top_textHannyu', $v->getResultMessageTop());
        		}
        	}
        }

        // お支払方法 値範囲チェック
        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->payment_method_cd_sel)->isIn(array_keys($this->payment_method_lbls));
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        // お支払方法 必須チェック
        $v->isSelected();
        if (!$v->isValid()) {
            $errorForm->addError('top_payment_method_cd_sel', $v->getResultMessageTop());
        }
        if ($inForm->payment_method_cd_sel === '1') {
            // お支払い店舗 必須チェック
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->convenience_store_cd_sel)->isSelected()->
                    isIn(array_keys($this->convenience_store_lbls));
            if (!$v->isValid()) {
                $errorForm->addError('top_convenience_store_cd_sel', $v->getResultMessageTop());
            }
        }

        // エラーがない場合はメールアドレス一致チェック
        if (!$errorForm->hasError()) {
            $v = Sgmov_Component_Validator::createMultipleValueValidator(array($inForm->mail, $inForm->retype_mail))->isStringComparison();
            if (!$v->isValid()) {
                $errorForm->addError('top_mail', $v->getResultMessageTop());
            }
        }

        // エラーがない場合は郵便番号・住所の存在チェック
        if (!$errorForm->hasError()) {
        	if (!empty($inForm->zip1) && !empty($inForm->zip2)) {
        		$zipV->zipCodeExistSocket()->zipCodeCollectable();
        		if (!$zipV->isValid()) {
        			$errorForm->addError('top_zip', $zipV->getResultMessageTop());
        		}
        		$receive = $this->_getAddress($inForm, $prefectures);
        		if (empty($receive['ShopCodeFlag'])) {
        			$errorForm->addError('top_address', 'の入力内容をお確かめください。');
        		} elseif (!empty($receive['ExchangeFlag'])) {
        			$errorForm->addError('top_address', 'はお引取りできない地域の恐れがあります。');
        		} elseif (!empty($receive['TimeZoneFlag']) && $inForm->hikitori_yoteji_sel !== '1') {
        			$errorForm->addError('top_address', 'は時間帯指定できない地域の恐れがあります。');
        		}
        	}
        }

        // エラーがない場合は郵便番号・住所の存在チェック
        if (!$errorForm->hasError()) {
        	if (!empty($inForm->zip1_hksaki) && !empty($inForm->zip2_hksaki)) {
        		$zipV1->zipCodeExistSocket()->zipCodeCollectable();
        		if (!$zipV1->isValid()) {
        			$errorForm->addError('top_zip_hksaki', $zipV1->getResultMessageTop());
        		}
        		$receive = $this->_getAddress_hksaki($inForm, $prefectures);
        		if (empty($receive['ShopCodeFlag'])) {
        			$errorForm->addError('top_address_hksaki', 'の入力内容をお確かめください。');
        		} elseif (!empty($receive['ExchangeFlag'])) {
        			$errorForm->addError('top_address_hksaki', 'はお引取りできない地域の恐れがあります。');
        		} elseif (!empty($receive['TimeZoneFlag']) && $inForm->hikoshi_yoteji_sel !== '1') {
        			$errorForm->addError('top_address_hksaki', 'は時間帯指定できない地域の恐れがあります。');
        		}
        	}
        }

        // エラーがない場合はメールアドレス一致チェック
        if (!$errorForm->hasError()) {
        	if ( empty($inForm->hidden_mitumoriZeinuki) || $inForm->hidden_mitumoriZeinuki == '0') {
        		$errorForm->addError('top_kingaku', '運賃料金は、0円では登録出来ません。お手数ですがお電話などでお知らせください。');
        	}
        }

        // エラーがない場合はコンビニ決済時の乗船日チェック
//         if (!$errorForm->hasError() && $inForm->payment_method_cd_sel === '1') {
//             $dateV->isIn((array)$travel_convenience['ids']);
//             if (!$dateV->isValid()) {
//                 $errorForm->addError('top_travel_cd_sel_convenience', 'コンビニ決済の集荷ご依頼受付終了日は乗船日の10日前までです。');
//             }
//         }

        // エラーがない場合はコンビニ決済の送料上限チェック
//         if (!$errorForm->hasError() && $inForm->payment_method_cd_sel === '1') {
//             $data = array('prefecture_id' => $inForm->pref_cd_sel);
//             $departure_charge    = 0;
//             $arrival_charge      = 0;
//             $round_trip_discount = 0;
//             $checkDeparture = ((intval($inForm->terminal_cd_sel) & 1) === 1);
//             $checkArrival   = ((intval($inForm->terminal_cd_sel) & 2) === 2);
//             if ($checkDeparture) {
//                 $departure_charge = $this->_TravelDeliveryChargeAreasService->fetchDeliveryCharge($db,
//                         $data + array('travel_terminal_id'=>$inForm->travel_departure_cd_sel));
//             }
//             if ($checkArrival) {
//                 $arrival_charge = $this->_TravelDeliveryChargeAreasService->fetchDeliveryCharge($db,
//                         $data + array('travel_terminal_id'=>$inForm->travel_arrival_cd_sel));
//             }
//             if ($checkDeparture && $checkArrival) {
//                 $round_trip_discount = $this->_TravelService->fetchRoundTripDiscount($db,
//                         array('travel_id'=>$inForm->travel_cd_sel));
//             }
//             $delivery_charge = $departure_charge * $inForm->departure_quantity
//                 + $arrival_charge * $inForm->arrival_quantity
//                 - $round_trip_discount * min($inForm->departure_quantity, $inForm->arrival_quantity);
//             // 30万円がコンビニ決済の上限支払額
//             $v = Sgmov_Component_Validator::createSingleValueValidator(strval($delivery_charge))->isInteger(null, 300000);
//             if (!$v->isValid()) {
//                 $errorForm->addError('top_payment_method_cd_sel_convenience', '送料が30万円を超えたため、コンビニ決済できません。クレジットカードでお支払いください。');
//             }
//         }

        return $errorForm;
    }

    /**
     * 住所情報を取得します。
     * @param Sgmov_Form_Ptu001In $inForm 入力フォーム
     * @return boolean
     */
    public function _getAddress($inForm, $prefectures) {
        $zip = $inForm->zip1 . $inForm->zip2;
        $address = $prefectures['names'][array_search($inForm->pref_cd_sel, $prefectures['ids'])] . $inForm->address . $inForm->building;
        return $this->_SocketZipCodeDll->searchByAddress($address, $zip);
    }

    /**
    * 住所情報を取得します。
    * @param Sgmov_Form_Ptu001In $inForm 入力フォーム
    * @return boolean
    */
    public function _getAddress_hksaki($inForm, $prefectures) {
    	$zip = $inForm->zip1_hksaki . $inForm->zip2_hksaki;
    	$address = $prefectures['names'][array_search($inForm->pref_cd_sel_hksaki, $prefectures['ids'])] . $inForm->address_hksaki . $inForm->building_hksaki;
    	return $this->_SocketZipCodeDll->searchByAddress($address, $zip);
    }
}