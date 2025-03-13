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
Sgmov_Lib::useView('atp/Common');
Sgmov_Lib::useForms(array('Error', 'AtpSession', 'Atp002In'));
/**#@-*/

/**
 * ツアーエリア入力情報をチェックします。
 * @package    View
 * @subpackage ATP
 * @author     T.Aono(SGS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Atp_CheckInput extends Sgmov_View_Atp_Common {

    /**
     * 都道府県サービス
     * @var Sgmov_Service_Prefecture
     */
    private $_PrefectureService;

    /**
     * ツアーエリアサービス
     * @var Sgmov_Service_TravelProvinces
     */
    private $_TravelProvincesService;

    /**
     * ツアーエリア都道府県サービス
     * @var Sgmov_Service_TravelProvincesPrefectures
     */
    private $_TravelProvincesPrefecturesService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_PrefectureService                 = new Sgmov_Service_Prefecture();
        $this->_TravelProvincesService            = new Sgmov_Service_TravelProvinces();
        $this->_TravelProvincesPrefecturesService = new Sgmov_Service_TravelProvincesPrefectures();
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
     *   atp/confirm へリダイレクト
     *   </li></ol>
     * </li><li>
     * 入力エラー有り
     *   <ol><li>
     *   atp/input へリダイレクト
     *   </li></ol>
     * </li></ol>
     */
    public function executeInner() {
        Sgmov_Component_Log::debug('チケットの確認と破棄');
        $session = Sgmov_Component_Session::get();
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_ATP002, $this->_getTicket());

        Sgmov_Component_Log::debug('情報を取得');
        $inForm = $this->_createInFormFromPost($_POST);
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        if (empty($sessionForm)) {
            $sessionForm = new Sgmov_Form_Atp002In();
        }
        $sessionForm->travel_province_id   = $inForm->travel_province_id;
        $sessionForm->travel_province_cd   = $inForm->travel_province_cd;
        $sessionForm->travel_province_name = $inForm->travel_province_name;
        $sessionForm->prefecture_ids       = $inForm->prefecture_ids;

        Sgmov_Component_Log::debug('入力チェック');
        $errorForm = $this->_validate($sessionForm);
        if (!$errorForm->hasError()) {
            $errorForm = $this->_updateTravelProvince($sessionForm);
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
            Sgmov_Component_Log::debug('リダイレクト /atp/input/');
            Sgmov_Component_Redirect::redirectMaintenance('/atp/input/');
        } else {
            // TODO 確認画面と完了画面を作る
            $session->deleteForm($this->getFeatureId());
            Sgmov_Component_Log::debug('リダイレクト /atp/list/');
            Sgmov_Component_Redirect::redirectMaintenance('/atp/list/');
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
     * @return Sgmov_Form_Atp002In 入力フォーム
     */
    public function _createInFormFromPost($post) {
        $inForm = new Sgmov_Form_Atp002In();

        $inForm->travel_province_id   = filter_input(INPUT_POST, 'travel_province_id');
        $inForm->travel_province_cd   = filter_input(INPUT_POST, 'travel_province_cd');
        $inForm->travel_province_name = filter_input(INPUT_POST, 'travel_province_name');
        $inForm->prefecture_ids       = isset($post['prefecture_id'])        ? $post['prefecture_id']        : array();

        return $inForm;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_AtpSession $sessionForm セッションフォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($sessionForm) {

        // DB接続
        $db = Sgmov_Component_DB::getPublic();
        $prefectures  = $this->_PrefectureService->fetchPrefectures($db);

        // 入力チェック
        $errorForm = new Sgmov_Form_Error();

        // ツアーエリアコード
        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->travel_province_cd)->
                isNotEmpty()->
                isInteger()->
                isLengthLessThanOrEqualTo(3)->
                isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_travel_province_cd', $v->getResultMessageTop());
            $errorForm->addError('travel_province_cd', $v->getResultMessage());
        }

        // ツアーエリア名
        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->travel_province_name)->
                isNotEmpty()->
                isLengthLessThanOrEqualTo(20)->
                isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_travel_province_name', $v->getResultMessageTop());
            $errorForm->addError('travel_province_name', $v->getResultMessage());
        }

        // 都道府県
        if (!empty($sessionForm->prefecture_ids)) {
            $v = Sgmov_Component_Validator::createMultipleValueValidator($sessionForm->prefecture_ids)->
                    isNotEmpty()->
                    isIn((array)$prefectures['ids']);
            if (!$v->isValid()) {
                $errorForm->addError('top_prefecture_ids', $v->getResultMessageTop());
                $errorForm->addError('prefecture_ids', $v->getResultMessage());
            }
        }

        return $errorForm;
    }

    /**
     * セッション情報を元にツアーエリア情報を更新します。
     *
     * 同時更新によって更新に失敗した場合はエラーフォームにメッセージを設定して返します。
     *
     * @param Sgmov_Form_AocSession $sessionForm セッションフォーム
     * @return Sgmov_Form_Error エラーフォーム
     */
    public function _updateTravelProvince($sessionForm) {
        // DBへ接続
        $db = Sgmov_Component_DB::getAdmin();

        // 情報をDBへ格納
        if (!empty($sessionForm->travel_province_id)) {
            $data = array(
                'id'           => $sessionForm->travel_province_id,
                'provinces_id' => $sessionForm->travel_province_id,
                'cd'           => $sessionForm->travel_province_cd,
                'name'         => $sessionForm->travel_province_name,
            );
            $ret = $this->_TravelProvincesService->_updateTravelProvince($db, $data);
        } else {
            //登録用IDを取得
            $id = $this->_TravelProvincesService->select_id($db);
            $data = array(
                'id'           => $id,
                'provinces_id' => $id,
                'cd'           => $sessionForm->travel_province_cd,
                'name'         => $sessionForm->travel_province_name,
            );
            $ret = $this->_TravelProvincesService->_insertTravelProvince($db, $data);
        }

        $this->_TravelProvincesPrefecturesService->_deleteTravelProvincesPrefecture($db, $data);
        if (!empty($sessionForm->prefecture_ids)) {
            foreach ($sessionForm->prefecture_ids as $prefecture_id) {
                $data = array(
                    'prefecture_id' => $prefecture_id,
                ) + $data;
                $this->_TravelProvincesPrefecturesService->_insertTravelProvincesPrefecture($db, $data);
            }
        }

        $errorForm = new Sgmov_Form_Error();
        if ($ret === false) {
            $errorForm->addError('top_conflict', '別のユーザーによって更新されています。すべての変更は適用されませんでした。');
        }
        return $errorForm;
    }
}