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
Sgmov_Lib::useForms(array('Error', 'AspSession', 'Asp005Out'));
/**#@-*/

 /**
 * 特価編集発着地入力画面を表示します。
 * @package    View
 * @subpackage ASP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Asp_Input2 extends Sgmov_View_Asp_Common
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
     * セッションに入力チェック済みのASP004情報があるかどうかを確認
     * </li><li>
     * セッション情報を元に出力情報を設定
     * </li><li>
     * テンプレート用の値をセット
     * </li><li>
     * チケット発行
     * </li></ol>
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
    public function executeInner()
    {
        Sgmov_Component_Log::debug('セッションに入力チェック済みのASP004情報があるかどうかを確認');
        $session = Sgmov_Component_Session::get();
        $sessionForm = $session->loadForm($this->getFeatureId());
        if(is_null($sessionForm)){
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, 'sessionFormがnullです。');
        }
        if ($sessionForm->asp004_status !== self::VALIDATION_SUCCEEDED) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, 'ASP004が未チェックです。');
        }

        Sgmov_Component_Log::debug('セッション情報を元に出力情報を設定');
        $outForm = $this->_createOutFormBySessionForm($sessionForm);
        if (isset($sessionForm->asp005_error)) {
            $errorForm = $sessionForm->asp005_error;
        } else {
            $errorForm = new Sgmov_Form_Error();
        }

        Sgmov_Component_Log::debug('テンプレート用の値をセット');
        $outForm = $this->_setTemplateValuesToOutForm($outForm);

        Sgmov_Component_Log::debug('チケット発行');
        $ticket = $session->publishTicket($this->getFeatureId(), self::GAMEN_ID_ASP005);

        return array('ticket'=>$ticket,
                         'outForm'=>$outForm,
                         'errorForm'=>$errorForm);
    }

    /**
     * セッションフォームの値を元に出力フォームを生成します。
     * @param Sgmov_Form_AspSession $sessionForm セッションフォーム
     * @return Sgmov_Form_Asp005Out 出力フォーム
     */
    public function _createOutFormBySessionForm($sessionForm)
    {
        $outForm = new Sgmov_Form_Asp005Out();

        // 本社ユーザーかどうか
        $outForm->raw_honsha_user_flag = $this->getHonshaUserFlag();

        // 一覧に戻るリンクのURL
        $outForm->raw_sp_list_url = $this->createSpListUrl($sessionForm->sp_list_kind, $sessionForm->sp_list_view_mode);

        // 閑散繁忙設定かキャンペーン設定か
        $outForm->raw_sp_kind = $this->getSpKind();

        // セッション値の適用
        if (isset($sessionForm->asp005_in)) {
            $outForm->raw_course_plan_sel_cds = $sessionForm->asp005_in->course_plan_sel_cds;
            $outForm->raw_from_area_sel_cds = $sessionForm->asp005_in->from_area_sel_cds;
            $outForm->raw_to_area_sel_cds = $sessionForm->asp005_in->to_area_sel_cds;
        }
        return $outForm;
    }

    /**
     * 出力フォームにテンプレート用の値を設定して返します。
     * @param Sgmov_Form_Asp005Out $outForm 出力フォーム
     * @return Sgmov_Form_Asp005Out 出力フォーム
     */
    public function _setTemplateValuesToOutForm($outForm)
    {
        $db = Sgmov_Component_DB::getAdmin();

        // コースプラン
        $outForm->raw_course_lbls = array();
        $outForm->raw_course_plan_cds = array();
        $outForm->raw_plan_lbls = array();

        $temp = $this->_coursePlanService->
                        fetchCoursePlans($db);
        $course_ids = $temp['course_ids'];
        $course_names = $temp['course_names'];
        $plan_ids = $temp['plan_ids'];
        $plan_names = $temp['plan_names'];
        $count = count($course_ids);
        $courseKey = -1;
        $prevCourseId = '';
        for ($i = 0; $i < $count; $i++) {
            if ($prevCourseId != $course_ids[$i]) {
                $prevCourseId = $course_ids[$i];
                $courseKey++;
                $outForm->raw_course_lbls[$courseKey] = $course_names[$i];
                $outForm->raw_course_plan_cds[$courseKey] = array();
                $outForm->raw_plan_lbls[$courseKey] = array();
            }
            $outForm->raw_course_plan_cds[$courseKey][] = $prevCourseId . Sgmov_Service_CoursePlan::ID_DELIMITER . $plan_ids[$i];
            $outForm->raw_plan_lbls[$courseKey][] = $plan_names[$i];
        }

        // 出発エリア
        $outForm->raw_from_center_lbls = array();
        $outForm->raw_from_area_cds = array();
        $outForm->raw_from_area_lbls = array();

        $temp = $this->_centerAreaService->
                        fetchCenterFromAreas($db);
        $center_ids = $temp['center_ids'];
        $center_names = $temp['center_names'];
        $from_area_ids = $temp['from_area_ids'];
        $from_area_names = $temp['from_area_names'];
        $count = count($center_ids);
        if ($this->getHonshaUserFlag() === '1') {
            // 本社ユーザー
            $centerKey = -1;
            $prevCenterId = '';
            for ($i = 0; $i < $count; $i++) {
                if ($prevCenterId != $center_ids[$i]) {
                    $prevCenterId = $center_ids[$i];
                    $centerKey++;
                    $outForm->raw_from_center_lbls[$centerKey] = $center_names[$i];
                    $outForm->raw_from_area_cds[$centerKey] = array();
                    $outForm->raw_from_area_lbls[$centerKey] = array();
                }
                $outForm->raw_from_area_cds[$centerKey][] = $from_area_ids[$i];
                $outForm->raw_from_area_lbls[$centerKey][] = $from_area_names[$i];
            }
        } else {
            // 拠点ユーザー
            $centerId = $session = Sgmov_Component_Session::get()->loadLoginUser()->centerId;
            $centerKey = -1;
            $centerFound = FALSE;
            for ($i = 0; $i < $count; $i++) {
                if ($centerId === $center_ids[$i]) {
                    if (!$centerFound) {
                        $centerFound = TRUE;
                        $centerKey++;
                        $outForm->raw_from_center_lbls[$centerKey] = $center_names[$i];
                        $outForm->raw_from_area_cds[$centerKey] = array();
                        $outForm->raw_from_area_lbls[$centerKey] = array();
                    }
                    $outForm->raw_from_area_cds[$centerKey][] = $from_area_ids[$i];
                    $outForm->raw_from_area_lbls[$centerKey][] = $from_area_names[$i];
                }
            }
        }

        // 到着エリア
        $outForm->raw_to_center_lbls = array();
        $outForm->raw_to_area_cds = array();
        $outForm->raw_to_area_lbls = array();

        $temp = $this->_centerAreaService->
                        fetchCenterToAreas($db);
        $center_ids = $temp['center_ids'];
        $center_names = $temp['center_names'];
        $to_area_ids = $temp['to_area_ids'];
        $to_area_names = $temp['to_area_names'];
        $count = count($center_ids);
        $centerKey = -1;
        $prevCenterId = '';
        for ($i = 0; $i < $count; $i++) {
            if ($prevCenterId != $center_ids[$i]) {
                $prevCenterId = $center_ids[$i];
                $centerKey++;
                $outForm->raw_to_center_lbls[$centerKey] = $center_names[$i];
                $outForm->raw_to_area_cds[$centerKey] = array();
                $outForm->raw_to_area_lbls[$centerKey] = array();
            }
            $outForm->raw_to_area_cds[$centerKey][] = $to_area_ids[$i];
            $outForm->raw_to_area_lbls[$centerKey][] = $to_area_names[$i];
        }

        return $outForm;
    }
}
?>
