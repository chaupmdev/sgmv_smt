<?php
/**
 * @package    ClassDefFile
 * @author     T.Aono(SGS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('aap/Common');
Sgmov_Lib::useForms(array('Error', 'Aap002In'));
/**#@-*/

/**
 * マンションマスタ入力情報をチェックします。
 * @package    View
 * @subpackage Aap
 * @author     T.Aono(SGS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Aap_CheckInput extends Sgmov_View_Aap_Common {

    /**
     * マンションサービス
     * @var Sgmov_Service_Apartment
     */
    private $_ApartmentService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_ApartmentService = new Sgmov_Service_Apartment();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * チケットの確認と破棄
     * </li><li>
     * 情報をセッションに保存
     * </li><li>
     * 入力チェック
     * </li><li>
     * 入力エラー無し
     *   <ol><li>
     *   att/confirm へリダイレクト
     *   </li></ol>
     * </li><li>
     * 入力エラー有り
     *   <ol><li>
     *   att/input へリダイレクト
     *   </li></ol>
     * </li></ol>
     */
    public function executeInner() {
        Sgmov_Component_Log::debug('チケットの確認と破棄');
        $session = Sgmov_Component_Session::get();
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_AAP002, $this->_getTicket());

        Sgmov_Component_Log::debug('情報を取得');
        $inForm = $this->_createInFormFromPost();
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        if (empty($sessionForm)) {
            $sessionForm = new Sgmov_Form_Aap002In();
        }
        $sessionForm->apartment_id   = $inForm->apartment_id;
        $sessionForm->apartment_cd   = $inForm->apartment_cd;
        $sessionForm->apartment_name = $inForm->apartment_name;
        $sessionForm->zip1           = $inForm->zip1;
        $sessionForm->zip2           = $inForm->zip2;
        $sessionForm->address        = $inForm->address;
        $sessionForm->agency_cd      = $inForm->agency_cd;

        Sgmov_Component_Log::debug('入力チェック');

        $errorForm = $this->_validate($sessionForm);

        if (!$errorForm->hasError()) {
            $errorForm = $this->_updateApartment($sessionForm);
        }

        Sgmov_Component_Log::debug('セッション保存');
        $sessionForm->error = $errorForm;
        if ($errorForm->hasError()) {
            $sessionForm->status = self::VALIDATION_FAILED;
        } else {
            $sessionForm->status = self::VALIDATION_SUCCEEDED;
        }

        // リダイレクト
        if ($errorForm->hasError()) {
            $session->saveForm(self::FEATURE_ID, $sessionForm);
            Sgmov_Component_Log::debug('リダイレクト /aap/input/');
            Sgmov_Component_Redirect::redirectMaintenance('/aap/input/');
        } else {
            // TODO 確認画面と完了画面を作る
            $session->deleteForm($this->getFeatureId());
            Sgmov_Component_Log::debug('リダイレクト /aap/list/');
            Sgmov_Component_Redirect::redirectMaintenance('/aap/list/');
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
     * @param array $post ポスト情報
     * @return Sgmov_Form_Aap002In 入力フォーム
     */
    public function _createInFormFromPost() {
        $inForm = new Sgmov_Form_Aap002In();

        $inForm->apartment_id   = filter_input(INPUT_POST, 'apartment_id');
        $inForm->apartment_cd   = filter_input(INPUT_POST, 'apartment_cd');
        $inForm->apartment_name = filter_input(INPUT_POST, 'apartment_name');
        $inForm->zip1           = filter_input(INPUT_POST, 'zip1');
        $inForm->zip2           = filter_input(INPUT_POST, 'zip2');
        $inForm->address        = filter_input(INPUT_POST, 'address');
        $inForm->agency_cd      = filter_input(INPUT_POST, 'agency_cd');

        return $inForm;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_AttSession $sessionForm セッションフォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($sessionForm) {

        // 入力チェック
        $errorForm = new Sgmov_Form_Error();

        // マンションコード
        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->apartment_cd)->
                isNotEmpty()->
                isInteger()->
                isLengthLessThanOrEqualTo(5)->
                isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_apartment_cd', $v->getResultMessageTop());
            $errorForm->addError('apartment_cd', $v->getResultMessage());
        }

        // マンション名
        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->apartment_name)->
                isNotEmpty()->
                isLengthLessThanOrEqualTo(40)->
                isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_apartment_name', $v->getResultMessageTop());
            $errorForm->addError('apartment_name', $v->getResultMessage());
        }

        // マンション郵便番号
        $zipV = Sgmov_Component_Validator::createZipValidator($sessionForm->zip1, $sessionForm->zip2)->
                //isNotEmpty()->
                isZipCode();
        if (!$zipV->isValid()) {
            $errorForm->addError('top_zip', $zipV->getResultMessageTop());
            $errorForm->addError('zip', $zipV->getResultMessage());
        }

        // マンション住所
        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->address)->
                //isNotEmpty()->
                isLengthLessThanOrEqualTo(40)->
                isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_address', $v->getResultMessageTop());
            $errorForm->addError('address', $v->getResultMessage());
        }

        // 取引先コード
        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->agency_cd)->
                //isNotEmpty()->
                isLengthLessThanOrEqualTo(10)->
                isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_agency_cd', $v->getResultMessageTop());
            $errorForm->addError('agency_cd', $v->getResultMessage());
        }

        return $errorForm;
    }

    /**
     * セッション情報を元にマンション情報を更新します。
     *
     * 同時更新によって更新に失敗した場合はエラーフォームにメッセージを設定して返します。
     *
     * @param Sgmov_Form_AocSession $sessionForm セッションフォーム
     * @return Sgmov_Form_Error エラーフォーム
     */
    public function _updateApartment($sessionForm) {

        // DBへ接続
        $db = Sgmov_Component_DB::getAdmin();

        // 情報をDBへ格納
        if (!empty($sessionForm->apartment_id)) {
            $data = array(
                'id'        => $sessionForm->apartment_id,
                'cd'        => $sessionForm->apartment_cd,
                'name'      => $sessionForm->apartment_name,
                'zip_code'  => $sessionForm->zip1 . $sessionForm->zip2,
                'address'   => $sessionForm->address,
                'agency_cd' => $sessionForm->agency_cd,
            );
            $ret = $this->_ApartmentService->_updateApartment($db, $data);
        } else {
            //登録用IDを取得
            $id = $this->_ApartmentService->select_id($db);
            $data = array(
                'id'        => $id,
                'cd'        => $sessionForm->apartment_cd,
                'name'      => $sessionForm->apartment_name,
                'zip_code'  => $sessionForm->zip1 . $sessionForm->zip2,
                'address'   => $sessionForm->address,
                'agency_cd' => $sessionForm->agency_cd,
            );
            $ret = $this->_ApartmentService->_insertApartment($db, $data);
        }

        $errorForm = new Sgmov_Form_Error();
        if ($ret === false) {
            $errorForm->addError('top_conflict', '別のユーザーによって更新されています。すべての変更は適用されませんでした。');
        }
        return $errorForm;
    }
}