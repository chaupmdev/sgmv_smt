<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('atp/Common');
Sgmov_Lib::useForms(array('Error', 'Atp002Out'));
/**#@-*/

/**
 * ツアーエリアマスタ入力画面を表示します。
 * @package    View
 * @subpackage ATP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Atp_Input extends Sgmov_View_Atp_Common {

    /**
     * ログインサービス
     * @var Sgmov_Service_Login
     */
    public $_loginService;

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
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_loginService           = new Sgmov_Service_Login();
        $this->_PrefectureService      = new Sgmov_Service_Prefecture();
        $this->_TravelProvincesService = new Sgmov_Service_TravelProvinces();
    }

    /**
     * 処理を実行します。
     *
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['ticket']:チケット文字列
     * </li><li>
     * ['outForm']:出力フォーム
     * </li><li>
     * ['errorForm']:エラーフォーム
     * </li></ul>
     */
    public function executeInner() {
        if (isset($_POST['id'])) {
            return $this->_executeInnerUpdate($_POST);
        } else {
            return $this->_executeInnerReload($_POST);
        }
    }

    /**
     * 新規・変更ボタン押下の場合の処理を実行します。
     *
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['ticket']:チケット文字列
     * </li><li>
     * ['outForm']:出力フォーム
     * </li><li>
     * ['errorForm']:エラーフォーム
     * </li></ul>
     */
    public function _executeInnerUpdate($post) {
        Sgmov_Component_Log::debug('新規・変更ボタン押下の場合の処理を実行します。');

        // セッション削除
        $session = Sgmov_Component_Session::get();
        $session->deleteForm($this->getFeatureId());

        // 出力情報を作成
        $outForm = $this->_createOutFormByUpdate($post);
        $errorForm = new Sgmov_Form_Error();

        // チケット発行
        $ticket = $session->publishTicket($this->getFeatureId(), self::GAMEN_ID_ATP002);

        return array(
            'ticket'    => $ticket,
            'outForm'   => $outForm,
            'errorForm' => $errorForm,
        );
    }

    /**
     * 新規・変更ボタン押下ではない場合の処理を実行します。
     *
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['ticket']:チケット文字列
     * </li><li>
     * ['outForm']:出力フォーム
     * </li><li>
     * ['errorForm']:エラーフォーム
     * </li></ul>
     */
    public function _executeInnerReload($post) {
        Sgmov_Component_Log::debug('新規・変更ボタン押下ではない場合の処理を実行します。');

        // セッション
        $session = Sgmov_Component_Session::get();
        $sessionForm = $session->loadForm(self::FEATURE_ID);
Sgmov_Component_Log::debug($session);Sgmov_Component_Log::debug($sessionForm);
        // 出力情報を作成
        $outForm = $this->_createOutFormByReload($sessionForm);

        // TODO オブジェクトから値を直接取得できるよう修正する
        // オブジェクトから取得できないため、配列に型変換
        $sessionForm = (array)$sessionForm;
        $errorForm = $sessionForm['error'];

        // チケット発行
        $ticket = $session->publishTicket($this->getFeatureId(), self::GAMEN_ID_ATP002);

        return array(
            'ticket'    => $ticket,
            'outForm'   => $outForm,
            'errorForm' => $errorForm,
        );
    }

    /**
     * 入力フォームの値を出力フォームを生成します。
     * @return Sgmov_Form_Atp002Out 出力フォーム
     */
    private function _createOutFormByUpdate($post) {

        $outForm = new Sgmov_Form_Atp002Out();

        $outForm->raw_honsha_user_flag = $this->_loginService->getHonshaUserFlag();

        $db = Sgmov_Component_DB::getPublic();

        if (empty($post['id'])) {
            $prefectures = $this->_PrefectureService->fetchNewTravelProvincesPrefectures($db);
            $outForm->raw_prefecture_ids   = $prefectures['ids'];
            $outForm->raw_prefecture_names = $prefectures['names'];
            $outForm->raw_selected_cds     = $prefectures['selected_cds'];
            return $outForm;
        }

        $travelProvinces = $this->_TravelProvincesService->fetchTravelProvinceLimit($db, array('id' => $post['id']));
        $prefectures = $this->_PrefectureService->fetchTravelProvincesPrefectures($db, array('provinces_id' => $post['id']));
        
        if (empty($travelProvinces)) {
            return $outForm;
        }

        $outForm->raw_travel_province_id   = $travelProvinces['id'];
        $outForm->raw_travel_province_cd   = $travelProvinces['cd'];
        $outForm->raw_travel_province_name = $travelProvinces['name'];

        $outForm->raw_prefecture_ids   = $prefectures['ids'];
        $outForm->raw_prefecture_names = $prefectures['names'];
        $outForm->raw_selected_cds     = $prefectures['selected_cds'];

        return $outForm;
    }

    /**
     * 入力フォームの値を出力フォームを生成します。
     * @return Sgmov_Form_Atp002Out 出力フォーム
     */
    private function _createOutFormByReload($inForm) {

        $outForm = new Sgmov_Form_Atp002Out();

        $outForm->raw_honsha_user_flag = $this->_loginService->getHonshaUserFlag();

        $db = Sgmov_Component_DB::getPublic();
        $prefectures = $this->_PrefectureService->fetchNewTravelProvincesPrefectures($db);

        // TODO オブジェクトから値を直接取得できるよう修正する
        // オブジェクトから取得できないため、配列に型変換
        $inForm = (array)$inForm;

        $outForm->raw_travel_province_id   = $inForm['travel_province_id'];
        $outForm->raw_travel_province_cd   = $inForm['travel_province_cd'];
        $outForm->raw_travel_province_name = $inForm['travel_province_name'];

        if (!empty($inForm['prefecture_ids'])) {
            foreach ($inForm['prefecture_ids'] as $prefecture_id) {
                $prefectures['selected_cds'][array_search($prefecture_id, $prefectures['ids'])] = '1';
            }
        }
        $outForm->raw_prefecture_ids   = $prefectures['ids'];
        $outForm->raw_prefecture_names = $prefectures['names'];
        $outForm->raw_selected_cds     = $prefectures['selected_cds'];

        return $outForm;
    }
}