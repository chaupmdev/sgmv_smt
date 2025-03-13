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
Sgmov_Lib::useServices(array('CoursePlan', 'CenterArea'));
Sgmov_Lib::useForms(array('Error', 'AspSession', 'Asp005In'));
/**#@-*/

 /**
 * 特価編集発着地入力情報をチェックします。
 * @package    View
 * @subpackage ASP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Asp_CheckInput2 extends Sgmov_View_Asp_Common
{
    /**
     * コースプランサービス
     * @var Sgmov_Service_CoursePlan
     */
    public $_coursePlanService;

    /**
     * 拠点エリアサービス
     * @var Sgmov_Service_CenterArea
     */
    public $_centerAreaService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        $this->_coursePlanService = new Sgmov_Service_CoursePlan();
        $this->_centerAreaService = new Sgmov_Service_CenterArea();
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
     * 入力エラー有り:input2へリダイレクト
     * </li><li>
     * 入力エラー無し:input3へリダイレクト
     * </li></ol>
     */
    public function executeInner()
    {
        Sgmov_Component_Log::debug('チケットの確認と破棄');
        $session = Sgmov_Component_Session::get();
        $session->checkTicket($this->getFeatureId(), self::GAMEN_ID_ASP005, $this->_getTicket());

        Sgmov_Component_Log::debug('情報を取得');
        $inForm = $this->_createInFormFromPost($_POST);

        Sgmov_Component_Log::debug('入力チェック');
        $errorForm = $this->_validate($inForm);

        Sgmov_Component_Log::debug('情報をセッションに保存');
        $sessionForm = $session->loadForm($this->getFeatureId());
        $sessionForm->asp005_in = $inForm;
        $sessionForm->asp005_error = $errorForm;
        if ($errorForm->hasError()) {
            $sessionForm->asp005_status = self::VALIDATION_FAILED;
        } else {
            $sessionForm->asp005_status = self::VALIDATION_SUCCEEDED;
        }
        $sessionForm->asp006_status = NULL;
        $sessionForm->asp008_status = NULL;
        $sessionForm->asp009_status = NULL;
        $session->saveForm($this->getFeatureId(), $sessionForm);

        if ($errorForm->hasError()) {
            $to = '/asp/input2/' . $this->getFeatureGetParam();
        } else {
            $to = '/asp/input3/' . $this->getFeatureGetParam();
        }
        Sgmov_Component_Log::debug('リダイレクト ' . $to);
        Sgmov_Component_Redirect::redirectMaintenance($to);
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
     * POST情報から入力フォームを生成します。
     * @param array $post ポスト情報
     * @return Sgmov_Form_Asp005In 入力フォーム
     */
    public function _createInFormFromPost($post)
    {
        $inForm = new Sgmov_Form_Asp005In();
        if (isset($post['course_plan_sel_cds'])) {
            $inForm->course_plan_sel_cds = $post['course_plan_sel_cds'];
        } else {
            $inForm->course_plan_sel_cds = array();
        }
        if (isset($post['from_area_sel_cds'])) {
            $inForm->from_area_sel_cds = $post['from_area_sel_cds'];
        } else {
            $inForm->from_area_sel_cds = array();
        }
        if (isset($post['to_area_sel_cds'])) {
            $inForm->to_area_sel_cds = $post['to_area_sel_cds'];
        } else {
            $inForm->to_area_sel_cds = array();
        }
        return $inForm;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_Asp005In $inForm 入力フォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($inForm)
    {
        if (Sgmov_Component_Log::isDebug()) {
            Sgmov_Component_Log::debug('入力チェック:$inForm=' . Sgmov_Component_String::toDebugString($inForm));
        }
        $db = Sgmov_Component_DB::getAdmin();

        $errorForm = new Sgmov_Form_Error();

        $errorFlag = false;
        
        // コースプラン
        if (!is_array($inForm->course_plan_sel_cds)) {
            // 配列ではない場合はシステムエラー
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT, '不正入力：コースプランが配列ではありません。');
        } else if (count($inForm->course_plan_sel_cds) === 0) {
            $errorForm->addError('top_course_plan_sel_cds', 'を選択してください。');
            $errorFlag = true;
        } else {
            // 通常の入力ではありえない値の場合はシステムエラー
            // 値が正しいコードであること
            $temp = $this->_coursePlanService->
                            fetchCoursePlans($db);
            $count = count($temp['course_ids']);
            $coursePlanIds = array();
            for ($i = 0; $i < $count; $i++) {
                $coursePlanIds[] = $temp['course_ids'][$i] . Sgmov_Service_CoursePlan::ID_DELIMITER . $temp['plan_ids'][$i];
            }
            $v = Sgmov_Component_Validator::createMultipleValueValidator($inForm->course_plan_sel_cds)->
                                            isIn($coursePlanIds);
            if (!$v->isValid()) {
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT, '不正入力：コースプランに不正なコードが含まれています。');
            }

            // 値に重複がないこと
            if (count($inForm->course_plan_sel_cds) != count(array_unique($inForm->course_plan_sel_cds))) {
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT, '不正入力：コースプランに重複があります。');
            }
        }

        // 出発エリア
        if (!is_array($inForm->from_area_sel_cds)) {
            // 配列ではない場合はシステムエラー
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT, '不正入力：出発エリアが配列ではありません。');
        } else if (count($inForm->from_area_sel_cds) === 0) {
            $errorForm->addError('top_from_area_sel_cds', 'を選択してください。');
            $errorFlag = true;
        } else {
            // 通常の入力ではありえない値の場合はシステムエラー
            // 値が正しいコードであること
            $temp = $this->_centerAreaService->
                            fetchCenterFromAreas($db);
            $v = Sgmov_Component_Validator::createMultipleValueValidator($inForm->from_area_sel_cds)->
                                            isIn($temp['from_area_ids']);
            if (!$v->isValid()) {
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT, '不正入力：出発エリアに不正なコードが含まれています。');
            }

            // 値に重複がないこと
            if (count($inForm->from_area_sel_cds) != count(array_unique($inForm->from_area_sel_cds))) {
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT, '不正入力：出発エリアに重複があります。');
            }
        }

        // 到着エリア
        if (!is_array($inForm->to_area_sel_cds)) {
            // 配列ではない場合はシステムエラー
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT, '不正入力：到着エリアが配列ではありません。');
        } else if (count($inForm->to_area_sel_cds) === 0) {
            $errorForm->addError('top_to_area_sel_cds', 'を選択してください。');
            $errorFlag = true;
        } else {
            // 通常の入力ではありえない値の場合はシステムエラー
            // 値が正しいコードであること
            $temp = $this->_centerAreaService->
                            fetchCenterToAreas($db);
            $v = Sgmov_Component_Validator::createMultipleValueValidator($inForm->to_area_sel_cds)->
                                            isIn($temp['to_area_ids']);
            if (!$v->isValid()) {
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT, '不正入力：到着エリアに不正なコードが含まれています。');
            }

            // 値に重複がないこと
            if (count($inForm->to_area_sel_cds) != count(array_unique($inForm->to_area_sel_cds))) {
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT, '不正入力：到着エリアに重複があります。');
            }
        }
        
        // コースプラン・出発地域・到着地域の存在チェック
        if (!$this->_coursePlanService->checkCourcePlanFromTo($db, $inForm->course_plan_sel_cds, $inForm->from_area_sel_cds, $inForm->to_area_sel_cds)) {
        	$errorForm->addError('top_cource_plan_from_to', '入力されたコース・プラン・出発地域・到着地域が不正です。');
        }
        
        
        
        return $errorForm;
    }
}
?>
