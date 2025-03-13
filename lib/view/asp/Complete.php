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
Sgmov_Lib::useView('asp/Common');
Sgmov_Lib::useServices(array('CoursePlan', 'Login', 'SpecialPrice'));
Sgmov_Lib::useForms(array('Error', 'AspSession', 'Asp011Out'));
/**#@-*/

 /**
 * 特価編集情報を登録し、完了画面を表示します。
 * @package    View
 * @subpackage ASP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Asp_Complete extends Sgmov_View_Asp_Common
{
    /**
     * ログインサービス
     * @var Sgmov_Service_Login
     */
    public $_loginService;

    /**
     * 特価サービス
     * @var Sgmov_Service_SpecialPrice
     */
    public $_specialPriceService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        $this->_loginService = new Sgmov_Service_Login();
        $this->_specialPriceService = new Sgmov_Service_SpecialPrice();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * チケットの確認と破棄
     * </li><li>
     * 情報をDBへ格納
     * </li><li>
     * 同時更新エラーの場合はエラーを設定して入力画面へ遷移
     * </li><li>
     * 出力情報を設定
     * </li><li>
     * セッション情報を破棄
     * </li></ol>
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['outForm']:出力フォーム
     * </li></ul>
     */
    public function executeInner()
    {
        Sgmov_Component_Log::debug('チケットの確認と破棄');
        $session = Sgmov_Component_Session::get();
        $session->checkTicket($this->getFeatureId(), self::GAMEN_ID_ASP010, $this->_getTicket());

        /**
         * コード補完のためだけにドキュメントコメント使います。
         * @var Sgmov_Form_AspSession
         */
        $sessionForm = $session->loadForm($this->getFeatureId());

        if (isset($_POST['draft_btn_x'])) {
            Sgmov_Component_Log::debug('下書きボタン押下');
            $draft = TRUE;
        } else if (isset($_POST['complete_btn_x'])) {
            Sgmov_Component_Log::debug('完了ボタン押下');
            $draft = FALSE;
        } else {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, 'ボタンが押下されていません。');
        }

        Sgmov_Component_Log::debug('情報をDBへ格納');
        $ret = $this->_updateSpecialPrice($draft, $sessionForm);

        Sgmov_Component_Log::debug('同時更新エラーの場合はエラーを設定して入力画面へ遷移');
        if ($ret === FALSE) {
            Sgmov_Component_Log::debug('同時更新エラー');
            $sessionForm->asp010_error = new Sgmov_Form_Error();
            $sessionForm->asp010_error->
                        addError('top_conflict', '別のユーザーによって更新されています。すべての変更は適用されませんでした。');
            $session->saveForm($this->getFeatureId(), $sessionForm);
            $to = '/asp/confirm/' . $this->getFeatureGetParam();
            Sgmov_Component_Log::debug('リダイレクト ' . $to);
            Sgmov_Component_Redirect::redirectMaintenance($to);
        }

        Sgmov_Component_Log::debug('出力情報を設定');
        $outForm = $this->_createOutFormBySessionForm($sessionForm);

        Sgmov_Component_Log::debug('セッション情報を破棄');
        $session->deleteForm($this->getFeatureId());

        return array('outForm'=>$outForm);
    }

    /**
     * POST値からチケットを取得します。
     * チケットが存在しない場合は空文字列を返します。
     * @return string チケット文字列
     */
    public function _getTicket()
    {
        if (!isset($_POST['ticket'])) {
            $ticket = '';
        } else {
            $ticket = $_POST['ticket'];
        }
        return $ticket;
    }

    /**
     * セッションフォームの値を元に出力フォームを生成します。
     * @param Sgmov_Form_AspSession $sessionForm セッションフォーム
     * @return Sgmov_Form_Asp011Out 出力フォーム
     */
    public function _createOutFormBySessionForm($sessionForm)
    {
        $outForm = new Sgmov_Form_Asp011Out();

        // 本社ユーザーかどうか
        $outForm->raw_honsha_user_flag = $this->getHonshaUserFlag();
        // 一覧に戻るリンクのURL
        $outForm->raw_sp_list_url = $this->createSpListUrl($sessionForm->sp_list_kind, $sessionForm->sp_list_view_mode);
        // 閑散繁忙設定かキャンペーン設定か
        $outForm->raw_sp_kind = $this->getSpKind();
        return $outForm;
    }

    /**
     * セッション情報を元にDBに情報を格納します。
     * @param boolean $draft 下書きモードかどうか(TRUE:下書き、FALSE:公開)
     * @param Sgmov_Form_AspSession $sessionForm セッションフォーム
     * @return boolean 成功した場合TRUEをそうでない場合はFALSEを返します。
     */
    public function _updateSpecialPrice($draft, $sessionForm)
    {
        $user = $this->_loginService->
                        getLoginUser();

        // 基本情報
        $specialPrice = array();
        $specialPrice['center_id'] = $user->centerId;
        $specialPrice['title'] = $sessionForm->asp004_in->sp_name;
        $specialPrice['create_user_name'] = $sessionForm->asp004_in->sp_regist_user;

        // 基本情報:下書き
        if ($draft) {
            $specialPrice['draft_flag'] = '1';
        } else {
            $specialPrice['draft_flag'] = '0';
        }

        if ($this->getFeatureId() === self::FEATURE_ID_EXTRA) {
            // 閑散繁忙
            $specialPrice['special_price_division'] = '1';
            $specialPrice['description'] = NULL;
        } else if ($this->getFeatureId() === self::FEATURE_ID_CAMPAIGN) {
            // キャンペーン
            $specialPrice['special_price_division'] = '2';
            $specialPrice['description'] = $sessionForm->asp004_in->sp_content;
        } else {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, 'FeatureIdが不正。');
        }

        // 基本情報:期間
        $specialPrice['min_date'] = $sessionForm->asp006_in->sel_days[0];
        $specialPrice['max_date'] = $sessionForm->asp006_in->sel_days[count($sessionForm->asp006_in->sel_days) - 1];

        // 基本情報:金額
        $specialPrice['priceset_kbn'] = $sessionForm->priceset_kbn;
        if ($sessionForm->priceset_kbn === '2') {
            // 金額一括指定
            $specialPrice['batchprice'] = $sessionForm->asp008_in->sp_whole_charge;
        } else {
            $specialPrice['batchprice'] = NULL;
        }

        // コースプラン
        $coursePlanIds = array();
        foreach ($sessionForm->asp005_in->course_plan_sel_cds as $cd) {
            $splits = explode(Sgmov_Service_CoursePlan::ID_DELIMITER, $cd, 2);
            $coursePlanIds[] = array('course_id'=>$splits[0],
                                         'plan_id'=>$splits[1]);
        }

        // 発着地・期間
        $fromAreaIds = $sessionForm->asp005_in->from_area_sel_cds;
        $toAreaIds = $sessionForm->asp005_in->to_area_sel_cds;
        $targetDays = $sessionForm->asp006_in->sel_days;

        // 金額
        if ($sessionForm->priceset_kbn === '3') {
            // 金額個別指定
            $specialPriceDetails = array();
            $delim = Sgmov_Service_CoursePlan::ID_DELIMITER;
            $toAreaCount = count($toAreaIds);
            foreach ($coursePlanIds as $coursePlanId) {
                foreach ($fromAreaIds as $fromAreaId) {
                    $allChargesKey = $coursePlanId['course_id'] . $delim . $coursePlanId['plan_id'] . $delim . $fromAreaId;
                    for ($i = 0; $i < $toAreaCount; $i++) {
                        $key = $allChargesKey . $delim . $toAreaIds[$i];
                        $value = $sessionForm->asp009_in->all_charges[$allChargesKey][$i];
                        $specialPriceDetails[$key] = $value;
                    }
                }
            }
        } else {
            $specialPriceDetails = NULL;
        }

        $db = Sgmov_Component_DB::getAdmin();
        if (isset($sessionForm->sp_cd) && ! empty($sessionForm->sp_cd)) {
            // 更新
            $id = $sessionForm->sp_cd;
            $timestamp = $sessionForm->sp_timestamp;
            return $this->_specialPriceService->
                        updateSpecialPrice($db, $id, $timestamp, $user->account, $specialPrice, $coursePlanIds, $fromAreaIds,
                             $toAreaIds, $targetDays, $specialPriceDetails);
        } else {
            return $this->_specialPriceService->
                        insertSpecialPriceData($db, $user->account, $specialPrice, $coursePlanIds, $fromAreaIds, $toAreaIds,
                             $targetDays, $specialPriceDetails);
        }
    }

}
?>
