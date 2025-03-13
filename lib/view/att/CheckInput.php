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
Sgmov_Lib::useView('att/Common');
Sgmov_Lib::useForms(array('Error', 'AttSession', 'Att002In'));
/**#@-*/

/**
 * ツアー発着地入力情報をチェックします。
 * @package    View
 * @subpackage ATT
 * @author     T.Aono(SGS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Att_CheckInput extends Sgmov_View_Att_Common {

    /**
     * 都道府県サービス
     * @var Sgmov_Service_Prefecture
     */
    private $_PrefectureService;

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
     * ツアー発着地サービス
     * @var Sgmov_Service_TravelTerminal
     */
    private $_TravelTerminalService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_PrefectureService     = new Sgmov_Service_Prefecture();
        $this->_TravelAgencyService   = new Sgmov_Service_TravelAgency();
        $this->_TravelService         = new Sgmov_Service_Travel();
        $this->_TravelTerminalService = new Sgmov_Service_TravelTerminal();
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
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_ATT002, $this->_getTicket());

        Sgmov_Component_Log::debug('情報を取得');
        $inForm = $this->_createInFormFromPost();
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        if (empty($sessionForm)) {
            $sessionForm = new Sgmov_Form_Att002In();
        }
        $sessionForm->travel_terminal_id         = $inForm->travel_terminal_id;
        $sessionForm->travel_agency_cd_sel       = $inForm->travel_agency_cd_sel;
        $sessionForm->travel_cd_sel              = $inForm->travel_cd_sel;
        $sessionForm->travel_terminal_cd         = $inForm->travel_terminal_cd;
        $sessionForm->travel_terminal_name       = $inForm->travel_terminal_name;
        $sessionForm->zip1                       = $inForm->zip1;
        $sessionForm->zip2                       = $inForm->zip2;
        $sessionForm->pref_cd_sel                = $inForm->pref_cd_sel;
        $sessionForm->address                    = $inForm->address;
        $sessionForm->building                   = $inForm->building;
        $sessionForm->store_name                 = $inForm->store_name;
        $sessionForm->tel                        = $inForm->tel;
        $sessionForm->terminal_cd                = $inForm->terminal_cd;
        $sessionForm->departure_date             = $inForm->departure_date;
        $sessionForm->departure_time             = $inForm->departure_time;
        $sessionForm->arrival_date               = $inForm->arrival_date;
        $sessionForm->arrival_time               = $inForm->arrival_time;
        $sessionForm->departure_client_cd        = $inForm->departure_client_cd;
        $sessionForm->departure_client_branch_cd = $inForm->departure_client_branch_cd;
        $sessionForm->arrival_client_cd          = $inForm->arrival_client_cd;
        $sessionForm->arrival_client_branch_cd   = $inForm->arrival_client_branch_cd;

        Sgmov_Component_Log::debug('入力チェック');
        $errorForm = $this->_validate($sessionForm);
        if (!$errorForm->hasError()) {
            $errorForm = $this->_updateTravelTerminal($sessionForm);
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
            Sgmov_Component_Log::debug('リダイレクト /att/input/');
            Sgmov_Component_Redirect::redirectMaintenance('/att/input/');
        } else {
            // TODO 確認画面と完了画面を作る
            $session->deleteForm($this->getFeatureId());
            Sgmov_Component_Log::debug('リダイレクト /att/list/');
            Sgmov_Component_Redirect::redirectMaintenance('/att/list/');
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
     * @return Sgmov_Form_Att002In 入力フォーム
     */
    public function _createInFormFromPost() {
        $inForm = new Sgmov_Form_Att002In();

        $inForm->travel_terminal_id         = filter_input(INPUT_POST, 'travel_terminal_id');
        $inForm->travel_agency_cd_sel       = filter_input(INPUT_POST, 'travel_agency_cd_sel');
        $inForm->travel_cd_sel              = filter_input(INPUT_POST, 'travel_cd_sel');
        $inForm->travel_terminal_cd         = filter_input(INPUT_POST, 'travel_terminal_cd');
        $inForm->travel_terminal_name       = filter_input(INPUT_POST, 'travel_terminal_name');
        $inForm->zip1                       = filter_input(INPUT_POST, 'zip1');
        $inForm->zip2                       = filter_input(INPUT_POST, 'zip2');
        $inForm->pref_cd_sel                = filter_input(INPUT_POST, 'pref_cd_sel');
        $inForm->address                    = filter_input(INPUT_POST, 'address');
        $inForm->building                   = filter_input(INPUT_POST, 'building');
        $inForm->store_name                 = filter_input(INPUT_POST, 'store_name');
        $inForm->tel                        = filter_input(INPUT_POST, 'tel');
        $terminal_cd1                       = filter_input(INPUT_POST, 'terminal_cd1');
        $terminal_cd2                       = filter_input(INPUT_POST, 'terminal_cd2');
        $inForm->terminal_cd                = $terminal_cd1 + $terminal_cd2;
        $inForm->departure_date             = filter_input(INPUT_POST, 'departure_date');
        $inForm->departure_time             = filter_input(INPUT_POST, 'departure_time');
        $inForm->arrival_date               = filter_input(INPUT_POST, 'arrival_date');
        $inForm->arrival_time               = filter_input(INPUT_POST, 'arrival_time');
        $inForm->departure_client_cd        = filter_input(INPUT_POST, 'departure_client_cd');
        $inForm->departure_client_branch_cd = filter_input(INPUT_POST, 'departure_client_branch_cd');
        $inForm->arrival_client_cd          = filter_input(INPUT_POST, 'arrival_client_cd');
        $inForm->arrival_client_branch_cd   = filter_input(INPUT_POST, 'arrival_client_branch_cd');

        return $inForm;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_AttSession $sessionForm セッションフォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($sessionForm) {

        // DB接続
        $db = Sgmov_Component_DB::getPublic();
        $prefectures  = $this->_PrefectureService->fetchPrefectures($db);
        $travelAgency = $this->_TravelAgencyService->fetchTravelAgencies($db);
        if (!empty($sessionForm->travel_agency_cd_sel)) {
            $travel = $this->_TravelService->fetchTravels($db, array('travel_agency_id' => $sessionForm->travel_agency_cd_sel));
        } else {
            $travel = array(
                'ids' => array(),
            );
        }

        // 入力チェック
        $errorForm = new Sgmov_Form_Error();

        // 船名
        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->travel_agency_cd_sel)->
                isSelected()->
                isIn((array)$travelAgency['ids']);
        if (!$v->isValid()) {
            $errorForm->addError('top_travel_agency_cd_sel', $v->getResultMessageTop());
            $errorForm->addError('travel_agency_cd_sel', $v->getResultMessage());
        }

        // 乗船日名
        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->travel_cd_sel)->
                isSelected()->
                isIn((array)$travel['ids']);
        if (!$v->isValid()) {
            $errorForm->addError('top_travel_cd_sel', $v->getResultMessageTop());
            $errorForm->addError('travel_cd_sel', $v->getResultMessage());
        }

        // ツアー発着地コード
        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->travel_terminal_cd)->
                isNotEmpty()->
                isInteger()->
                isLengthLessThanOrEqualTo(6)->
                isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_travel_terminal_cd', $v->getResultMessageTop());
            $errorForm->addError('travel_terminal_cd', $v->getResultMessage());
        }

        // ツアー発着地名
        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->travel_terminal_name)->
                isNotEmpty()->
                isLengthLessThanOrEqualTo(30)->
                isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_travel_terminal_name', $v->getResultMessageTop());
            $errorForm->addError('travel_terminal_name', $v->getResultMessage());
        }

        // 郵便番号
        $zipV = Sgmov_Component_Validator::createZipValidator($sessionForm->zip1, $sessionForm->zip2)->
                //isNotEmpty()->
                isZipCode();
        if (!$zipV->isValid()) {
            $errorForm->addError('top_zip', $zipV->getResultMessageTop());
            $errorForm->addError('zip', $zipV->getResultMessage());
        }

        // 都道府県
        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->pref_cd_sel)->
                //isSelected()->
                isIn((array)$prefectures['ids']);
        if (!$v->isValid()) {
            $errorForm->addError('top_pref_cd_sel', $v->getResultMessageTop());
            $errorForm->addError('pref_cd_sel', $v->getResultMessage());
        }

        // 市区町村
        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->address)->
                //isNotEmpty()->
                isLengthLessThanOrEqualTo(40)->
                isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_address', $v->getResultMessageTop());
            $errorForm->addError('address', $v->getResultMessage());
        }

        // 番地・建物名
        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->building)->
                //isNotEmpty()->
                isLengthLessThanOrEqualTo(80)->
                isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_building', $v->getResultMessageTop());
            $errorForm->addError('building', $v->getResultMessage());
        }

        // 発着店名(営業所名)
        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->store_name)->
                //isNotEmpty()->
                isLengthLessThanOrEqualTo(80)->
                isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_store_name', $v->getResultMessageTop());
            $errorForm->addError('store_name', $v->getResultMessage());
        }

        // 電話番号
        $v = Sgmov_Component_Validator::createSingleValueValidator(str_replace('-', '', $sessionForm->tel))->
                //isNotEmpty()->
                isPhone1()->
                isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_tel', $v->getResultMessageTop());
            $errorForm->addError('tel', $v->getResultMessage());
        }

        // 発着区分
        $v = Sgmov_Component_Validator::createSingleValueValidator(strval($sessionForm->terminal_cd))->
                isSelected()->
                isIn(array_keys($this->terminal_lbls));
        if (!$v->isValid()) {
            $errorForm->addError('top_terminal_cd', $v->getResultMessageTop());
            $errorForm->addError('terminal_cd', $v->getResultMessage());
        }

        $date = new DateTime('2015/03/08');
        $min = intval($date->format('U'));

        // 出発日
        $departure_date = self::_formatDate($sessionForm->departure_date);
        $v = Sgmov_Component_Validator::createDateValidator(
                $departure_date[1],
                $departure_date[2],
                $departure_date[3])->
                //isNotEmpty()->
                isDate($min);
        if (!$v->isValid()) {
            $errorForm->addError('top_departure_date', $v->getResultMessageTop());
            $errorForm->addError('departure_date', $v->getResultMessage());
        }

        // 出発時刻
        $departure_time = self::_formatTime($sessionForm->departure_time);
        $v = Sgmov_Component_Validator::createTimeValidator(
                $departure_time[1],
                $departure_time[2],
                $departure_time[3])->
                isTime();
        if (!$v->isValid()) {
            $errorForm->addError('top_departure_time', $v->getResultMessageTop());
            $errorForm->addError('departure_time', $v->getResultMessage());
        }

        // 到着日
        $arrival_date = self::_formatDate($sessionForm->arrival_date);
        $v = Sgmov_Component_Validator::createDateValidator(
                $arrival_date[1],
                $arrival_date[2],
                $arrival_date[3])->
                //isNotEmpty()->
                isDate($min);
        if (!$v->isValid()) {
            $errorForm->addError('top_arrival_date', $v->getResultMessageTop());
            $errorForm->addError('arrival_date', $v->getResultMessage());
        }

        // 到着時刻
        $arrival_time = self::_formatTime($sessionForm->arrival_time);
        $v = Sgmov_Component_Validator::createTimeValidator(
                $arrival_time[1],
                $arrival_time[2],
                $arrival_time[3])->
                isTime();
        if (!$v->isValid()) {
            $errorForm->addError('top_arrival_time', $v->getResultMessageTop());
            $errorForm->addError('arrival_time', $v->getResultMessage());
        }

        // 往路 顧客コード
        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->departure_client_cd)->
                //isNotEmpty()->
                isInteger()->
                isLengthLessThanOrEqualTo(8)->
                isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_departure_client_cd', $v->getResultMessageTop());
            $errorForm->addError('departure_client_cd', $v->getResultMessage());
        }

        // 往路 顧客コード枝番
        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->departure_client_branch_cd)->
                //isNotEmpty()->
                isInteger()->
                isLengthLessThanOrEqualTo(3)->
                isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_departure_client_branch_cd', $v->getResultMessageTop());
            $errorForm->addError('departure_client_branch_cd', $v->getResultMessage());
        }

        // 復路 顧客コード
        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->arrival_client_cd)->
                //isNotEmpty()->
                isInteger()->
                isLengthLessThanOrEqualTo(8)->
                isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_arrival_client_cd', $v->getResultMessageTop());
            $errorForm->addError('arrival_client_cd', $v->getResultMessage());
        }

        // 復路 顧客コード枝番
        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->arrival_client_branch_cd)->
                //isNotEmpty()->
                isInteger()->
                isLengthLessThanOrEqualTo(3)->
                isWebSystemNg();
        if (!$v->isValid()) {
            $errorForm->addError('top_arrival_client_branch_cd', $v->getResultMessageTop());
            $errorForm->addError('arrival_client_branch_cd', $v->getResultMessage());
        }

        // エラーがない場合は郵便番号存在チェック
        if (!$errorForm->hasError()) {
            $zipV->zipCodeExist()->zipCodeCollectable();
            if (!$zipV->isValid()) {
                $errorForm->addError('top_zip', $zipV->getResultMessageTop());
            }
        }

        return $errorForm;
    }

    /**
     * セッション情報を元にツアー発着地情報を更新します。
     *
     * 同時更新によって更新に失敗した場合はエラーフォームにメッセージを設定して返します。
     *
     * @param Sgmov_Form_AocSession $sessionForm セッションフォーム
     * @return Sgmov_Form_Error エラーフォーム
     */
    public function _updateTravelTerminal($sessionForm) {
        $departure_date = self::_formatDate($sessionForm->departure_date);
        $departure_time = self::_formatTime($sessionForm->departure_time);
        $arrival_date   = self::_formatDate($sessionForm->arrival_date);
        $arrival_time   = self::_formatTime($sessionForm->arrival_time);
        if (isset($departure_date[1]) && trim($departure_date[1]) !== '') {
            unset($departure_date[0]);
            $departure_date = implode('/', $departure_date);
        } else {
            $departure_date = null;
        }
        if (isset($departure_time[1]) && trim($departure_time[1]) !== '') {
            unset($departure_time[0]);
            $departure_time = implode(':', $departure_time);
        } else {
            $departure_time = null;
        }
        if (isset($arrival_date[1]) && trim($arrival_date[1]) !== '') {
            unset($arrival_date[0]);
            $arrival_date = implode('/', $arrival_date);
        } else {
            $arrival_date = null;
        }
        if (isset($arrival_time[1]) && trim($arrival_time[1]) !== '') {
            unset($arrival_time[0]);
            $arrival_time = implode(':', $arrival_time);
        } else {
            $arrival_time = null;
        }

        // DBへ接続
        $db = Sgmov_Component_DB::getAdmin();

        // 情報をDBへ格納
        if (!empty($sessionForm->travel_terminal_id)) {
            $data = array(
                'id'                         => $sessionForm->travel_terminal_id,
                'travel_id'                  => $sessionForm->travel_cd_sel,
                'cd'                         => $sessionForm->travel_terminal_cd,
                'name'                       => $sessionForm->travel_terminal_name,
                'zip'                        => $sessionForm->zip1 . $sessionForm->zip2,
                'pref_id'                    => !empty($sessionForm->pref_cd_sel) ? $sessionForm->pref_cd_sel : null,
                'address'                    => $sessionForm->address,
                'building'                   => $sessionForm->building,
                'store_name'                 => $sessionForm->store_name,
                'tel'                        => str_replace('-', '', $sessionForm->tel),
                'terminal_cd'                => $sessionForm->terminal_cd,
                'departure_date'             => $departure_date,
                'departure_time'             => $departure_time,
                'arrival_date'               => $arrival_date,
                'arrival_time'               => $arrival_time,
                'departure_client_cd'        => $sessionForm->departure_client_cd,
                'departure_client_branch_cd' => $sessionForm->departure_client_branch_cd,
                'arrival_client_cd'          => $sessionForm->arrival_client_cd,
                'arrival_client_branch_cd'   => $sessionForm->arrival_client_branch_cd,
            );
            $ret = $this->_TravelTerminalService->_updateTravelTerminal($db, $data);
        } else {
            //登録用IDを取得
            $id = $this->_TravelTerminalService->select_id($db);
            $data = array(
                'id'                         => $id,
                'travel_id'                  => $sessionForm->travel_cd_sel,
                'cd'                         => $sessionForm->travel_terminal_cd,
                'name'                       => $sessionForm->travel_terminal_name,
                'zip'                        => $sessionForm->zip1 . $sessionForm->zip2,
                'pref_id'                    => !empty($sessionForm->pref_cd_sel) ? $sessionForm->pref_cd_sel : null,
                'address'                    => $sessionForm->address,
                'building'                   => $sessionForm->building,
                'store_name'                 => $sessionForm->store_name,
                'tel'                        => str_replace('-', '', $sessionForm->tel),
                'terminal_cd'                => $sessionForm->terminal_cd,
                'departure_date'             => $departure_date,
                'departure_time'             => $departure_time,
                'arrival_date'               => $arrival_date,
                'arrival_time'               => $arrival_time,
                'departure_client_cd'        => $sessionForm->departure_client_cd,
                'departure_client_branch_cd' => $sessionForm->departure_client_branch_cd,
                'arrival_client_cd'          => $sessionForm->arrival_client_cd,
                'arrival_client_branch_cd'   => $sessionForm->arrival_client_branch_cd,
            );
            $ret = $this->_TravelTerminalService->_insertTravelTerminal($db, $data);
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

    /**
     * 時刻整形
     * 
     * @param $s string 時刻文字列
     * @return array 時刻配列
     */
    private static function _formatTime($s) {

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

        // 時刻文字列かチェックする
        if (preg_match('{^\D*(\d{1,2})\D+(\d{1,2})\D+(\d{1,2})\D*$}u', $s, $matches) === 1
            || preg_match('{^\D*(\d{2})(\d{2})(\d{2})\D*$}u', $s, $matches) === 1
        ) {
            if (strlen($matches[2]) === 1) {
                $matches[2] = '0' . $matches[2];
            }
            if (strlen($matches[3]) === 1) {
                $matches[3] = '0' . $matches[3];
            }
            return $matches;
        } elseif (preg_match('{^\D*(\d{1,2})\D+(\d{1,2})\D*$}u', $s, $matches) === 1
            || preg_match('{^\D*(\d{2})(\d{2})\D*$}u', $s, $matches) === 1
        ) {
            $matches[3] = '00';
            return $matches;
        } elseif (preg_match('{^\D*(\d{1,2})\D*$}u', $s, $matches) === 1) {
            $matches[2] = '00';
            $matches[3] = '00';
            return $matches;
        }
        // 時刻ではない場合
        return array(
            1 => '',
            2 => '',
            3 => '',
        );
    }
}