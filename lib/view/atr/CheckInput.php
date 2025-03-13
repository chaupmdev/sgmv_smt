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
Sgmov_Lib::useView('atr/Common');
Sgmov_Lib::useForms(array('Error', 'AtrSession', 'Atr002In'));
/**#@-*/

/**
 * ツアー入力情報をチェックします。
 * @package    View
 * @subpackage ATR
 * @author     T.Aono(SGS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Atr_CheckInput extends Sgmov_View_Atr_Common {

    /**
     * ツアー会社サービス
     * @var Sgmov_Service_TravelAgency
     */
    private $_TravelAgencyService;

    /**
     * ツアーサービス
     * @var Sgmov_Service_Travel
     */
    private $_TravelService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_TravelAgencyService = new Sgmov_Service_TravelAgency();
        $this->_TravelService = new Sgmov_Service_Travel();
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
     *   atr/confirm へリダイレクト
     *   </li></ol>
     * </li><li>
     * 入力エラー有り
     *   <ol><li>
     *   atr/input へリダイレクト
     *   </li></ol>
     * </li></ol>
     */
    public function executeInner() {
        Sgmov_Component_Log::debug('チケットの確認と破棄');
        $session = Sgmov_Component_Session::get();
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_ATR002, $this->_getTicket());

        Sgmov_Component_Log::debug('情報を取得');
        $inForm = $this->_createInFormFromPost();
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        if (empty($sessionForm)) {
            $sessionForm = new Sgmov_Form_Atr002In();
        }
        $sessionForm->travel_id            = $inForm->travel_id;
        $sessionForm->travel_cd            = $inForm->travel_cd;
        $sessionForm->travel_name          = $inForm->travel_name;
        $sessionForm->travel_agency_cd_sel = $inForm->travel_agency_cd_sel;
        $sessionForm->round_trip_discount  = $inForm->round_trip_discount;
        $sessionForm->repeater_discount    = $inForm->repeater_discount;
        $sessionForm->embarkation_date     = $inForm->embarkation_date;
        $sessionForm->publish_begin_date   = $inForm->publish_begin_date;

        Sgmov_Component_Log::debug('入力チェック');
        $errorForm = $this->_validate($sessionForm);
        if (!$errorForm->hasError()) {
            $errorForm = $this->_updateTravel($sessionForm);
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
            Sgmov_Component_Log::debug('リダイレクト /atr/input/');
            Sgmov_Component_Redirect::redirectMaintenance('/atr/input/');
        } else {
            // TODO 確認画面と完了画面を作る
            $session->deleteForm($this->getFeatureId());
            Sgmov_Component_Log::debug('リダイレクト /atr/list/');
            Sgmov_Component_Redirect::redirectMaintenance('/atr/list/');
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
     * @return Sgmov_Form_Atr002In 入力フォーム
     */
    public function _createInFormFromPost() {
        $inForm = new Sgmov_Form_Atr002In();

        $inForm->travel_id            = filter_input(INPUT_POST, 'travel_id');
        $inForm->travel_cd            = filter_input(INPUT_POST, 'travel_cd');
        $inForm->travel_name          = filter_input(INPUT_POST, 'travel_name');
        $inForm->travel_agency_cd_sel = filter_input(INPUT_POST, 'travel_agency_cd_sel');
        $inForm->round_trip_discount  = filter_input(INPUT_POST, 'round_trip_discount');
        $inForm->repeater_discount    = filter_input(INPUT_POST, 'repeater_discount');
        $inForm->embarkation_date     = filter_input(INPUT_POST, 'embarkation_date');
        $inForm->publish_begin_date   = filter_input(INPUT_POST, 'publish_begin_date');

        return $inForm;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_AtrSession $sessionForm セッションフォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($sessionForm) {

        // DB接続
        $db = Sgmov_Component_DB::getPublic();
        $travelAgency = $this->_TravelAgencyService->fetchTravelAgencies($db);

        // 入力チェック
        $errorForm = new Sgmov_Form_Error();

        // ツアーコード
        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->travel_cd)->
                isNotEmpty()->
                isInteger()->
                isLengthLessThanOrEqualTo(4)->
                isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_travel_cd', $v->getResultMessageTop());
            $errorForm->addError('travel_cd', $v->getResultMessage());
        }

        // 乗船日名
        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->travel_name)->
                isNotEmpty()->
                isLengthLessThanOrEqualTo(60)->
                isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_travel_name', $v->getResultMessageTop());
            $errorForm->addError('travel_name', $v->getResultMessage());
        }

        // 船名
        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->travel_agency_cd_sel)->
                isSelected()->
                isIn((array)$travelAgency['ids']);
        if (!$v->isValid()) {
            $errorForm->addError('top_travel_agency_cd_sel', $v->getResultMessageTop());
            $errorForm->addError('travel_agency_cd_sel', $v->getResultMessage());
        }

        // 往復便割引
        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->round_trip_discount)->
                isNotEmpty()->
                isInteger()->
                isLengthLessThanOrEqualTo(6)->
                isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_round_trip_discount', $v->getResultMessageTop());
            $errorForm->addError('round_trip_discount', $v->getResultMessage());
        }

        // リピータ割引
        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->repeater_discount)->
                isNotEmpty()->
                isInteger()->
                isLengthLessThanOrEqualTo(6)->
                isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_repeater_discount', $v->getResultMessageTop());
            $errorForm->addError('repeater_discount', $v->getResultMessage());
        }

        $date = new DateTime('2015/03/08');
        $min = intval($date->format('U'));

        // 乗船日
        $embarkation_date = self::_formatDate($sessionForm->embarkation_date);
        $v = Sgmov_Component_Validator::createDateValidator(
                $embarkation_date[1],
                $embarkation_date[2],
                $embarkation_date[3])->
                isNotEmpty()->
                isDate($min);
        if (!$v->isValid()) {
            $errorForm->addError('top_embarkation_date', $v->getResultMessageTop());
            $errorForm->addError('embarkation_date', $v->getResultMessage());
        }

        // 掲載開始日
        $publish_begin_date = self::_formatDate($sessionForm->publish_begin_date);
        $v = Sgmov_Component_Validator::createDateValidator(
                $publish_begin_date[1],
                $publish_begin_date[2],
                $publish_begin_date[3])->
                isNotEmpty()->
                isNotEmpty()->
                isDate($min);
        if (!$v->isValid()) {
            $errorForm->addError('top_publish_begin_date', $v->getResultMessageTop());
            $errorForm->addError('publish_begin_date', $v->getResultMessage());
        }

        return $errorForm;
    }

    /**
     * セッション情報を元にツアー情報を更新します。
     *
     * 同時更新によって更新に失敗した場合はエラーフォームにメッセージを設定して返します。
     *
     * @param Sgmov_Form_AocSession $sessionForm セッションフォーム
     * @return Sgmov_Form_Error エラーフォーム
     */
    public function _updateTravel($sessionForm) {
        // DBへ接続
        $db = Sgmov_Component_DB::getAdmin();

        // 情報をDBへ格納
        if (!empty($sessionForm->travel_id)) {
            $data = array(
                'id'                  => $sessionForm->travel_id,
                'cd'                  => $sessionForm->travel_cd,
                'name'                => $sessionForm->travel_name,
                'travel_agency_id'    => $sessionForm->travel_agency_cd_sel,
                'round_trip_discount' => $sessionForm->round_trip_discount,
                'repeater_discount'   => $sessionForm->repeater_discount,
                'embarkation_date'    => $sessionForm->embarkation_date,
                'publish_begin_date'  => $sessionForm->publish_begin_date,
            );
            $ret = $this->_TravelService->_updateTravel($db, $data);
        } else {
            //登録用IDを取得
            $id = $this->_TravelService->select_id($db);
            $data = array(
                'id'                  => $id,
                'cd'                  => $sessionForm->travel_cd,
                'name'                => $sessionForm->travel_name,
                'travel_agency_id'    => $sessionForm->travel_agency_cd_sel,
                'round_trip_discount' => $sessionForm->round_trip_discount,
                'repeater_discount'   => $sessionForm->repeater_discount,
                'embarkation_date'    => $sessionForm->embarkation_date,
                'publish_begin_date'  => $sessionForm->publish_begin_date,
            );
            $ret = $this->_TravelService->_insertTravel($db, $data);
        }

        $errorForm = new Sgmov_Form_Error();
        if ($ret === false) {
            $errorForm->addError('top_conflict', '別のユーザーによって更新されています。すべての変更は適用されませんでした。');
        }
        return $errorForm;
    }

    /**
     * 日付整形
     * 
     * @param $s string 日付文字列
     * @return array 日付配列
     */
    private static function _formatDate($s) {

        $matches = array();

        if (empty($s)) {
            return array(
                1 => '',
                2 => '',
                3 => '',
            );
        }

        // 全角数字を半角に変換する
        $s = mb_convert_kana($s, 'n', 'UTF-8');

        // 日付文字列かチェックする
        if (preg_match('{^\D*(\d{4})\D+(\d{1,2})\D+(\d{1,2})\D*$}u', $s, $matches) === 1
            || preg_match('{^\D*(\d{4})(\d{2})(\d{2})\D*$}u', $s, $matches) === 1
        ) {
            return $matches;
        //} elseif (preg_match('{^\D*(\d{4})\D+(\d{1,2})\D*$}u', $s, $matches) === 1
        //    || preg_match('{^\D*(\d{4})(\d{2})\D*$}u', $s, $matches) === 1
        //) {
        //    return $matches;
        //} elseif (preg_match('{^\D*(\d{4})\D*$}u', $s, $matches) === 1) {
        //    return $matches;
        }
        // 日付ではない場合
        return array(
            1 => '',
            2 => '',
            3 => '',
        );
    }
}