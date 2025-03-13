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
Sgmov_Lib::useServices(array('CenterArea', 'CoursePlan', 'WorkPrice'));
Sgmov_Lib::useForms(array('Error', 'AspSession', 'Asp009Out'));
/**#@-*/

 /**
 * 特価個別編集金額入力画面を表示します。
 * @package    View
 * @subpackage ASP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Asp_Input6 extends Sgmov_View_Asp_Common
{
    /**
     * 拠点・エリアサービス
     * @var Sgmov_Service_CenterArea
     */
    public $_centerAreaService;

    /**
     * コースプランサービス
     * @var Sgmov_Service_CoursePlan
     */
    public $_coursePlanService;

    /**
     * 一時料金サービス
     * @var Sgmov_Service_WorkPrice
     */
    public $_workPriceService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        $this->_centerAreaService = new Sgmov_Service_CenterArea();
        $this->_coursePlanService = new Sgmov_Service_CoursePlan();
        $this->_workPriceService = new Sgmov_Service_WorkPrice();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * セッションに入力チェック済みのASP004～ASP006情報があることを確認
     * <ol><li>
     * 表示ボタン押下の場合
     *   <ol><li>
     *   セッションにASP009情報が保存されていない場合、
     *   またはASP009情報とASP005情報に相違がある場合は不正遷移システムエラー
     *   </li><li>
     *   入力チェック
     *   </li><li>
     *   入力されている金額情報値をセッションに適用
     *   </li><li>
     *   エラーがない場合は現在の検索条件に入力値を適用する
     *   </li></ol>
     * </li><li>
     * 表示ボタン押下ではない場合
     *   <ol><li>
     *   セッションにASP009情報が存在しないか、ASP009情報とASP005情報に相違があればASP009情報を生成
     *     <ul><li>
     *     セッションに情報がない場合はASP008情報があればそれを元に差額を設定、なければ空文字列の差額を設定
     *     </li><li>
     *     セッションに情報がある場合は元ASP009の金額情報を可能な限り適用
     *     </li></ul>
     *   </li></ol>
     * </li><li>
     * セッションの情報を元に出力情報を生成
     * </li><li>
     * 検索条件入力エラーの場合は検索条件が適用されていないので入力値を設定
     * </li><li>
     * チケット発行
     * </li></ol>
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['ticket']:チケット文字列
     * </li><li>
     * ['outForm']:出力フォーム
     * </li><li>
     * ['errorForm']:エラーフォーム
     * </li><li>
     * ['searchErrorForm']:検索部エラーフォーム
     * </li></ul>
     */
    public function executeInner()
    {
        Sgmov_Component_Log::debug('セッションに入力チェック済みのASP004～ASP006情報があるかどうかを確認');
        $session = Sgmov_Component_Session::get();

        /**
         * コード補完のためだけにドキュメントコメント使います。
         * @var Sgmov_Form_AspSession
         */
        $sessionForm = $session->loadForm($this->getFeatureId());
        // 不正遷移チェック
        if(is_null($sessionForm)){
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, 'sessionFormがnullです。');
        }
        if ($sessionForm->asp004_status !== self::VALIDATION_SUCCEEDED) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, 'ASP004が未チェックです。');
        }
        if ($sessionForm->asp005_status !== self::VALIDATION_SUCCEEDED) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, 'ASP005が未チェックです。');
        }
        if ($sessionForm->asp006_status !== self::VALIDATION_SUCCEEDED) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, 'ASP006が未チェックです。');
        }

        $searchErrorForm = new Sgmov_Form_Error();
        if (isset($_POST['reading_btn_x'])) {
            Sgmov_Component_Log::debug('表示ボタン押下');

            // 不正遷移チェック
            if (!isset($sessionForm->asp009_in) || $this->_checkAsp005IsChanged($sessionForm)) {
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '不正遷移：表示ボタン押下フラグが直接指定されました。');
            }

            // 入力チェック
            $inForm = $this->_createInFormFromPost($_POST);
            $searchErrorForm = $this->_validate($inForm, $sessionForm->asp009_in->course_plan_sel_cds, $sessionForm->asp009_in->from_area_sel_cds,
                 $sessionForm->asp009_in->to_area_sel_cds);

            // 入力されている金額情報値をセッションに適用
            $sessionForm = $this->_applyPricesToSession($sessionForm, $inForm);

            if (!$searchErrorForm->hasError()) {
                Sgmov_Component_Log::debug('エラーがない場合は現在の検索条件に入力値を適用する');
                $sessionForm->asp009_in->cur_course_plan_cd = $inForm->course_plan_cd_sel;
                $sessionForm->asp009_in->cur_from_area_cd = $inForm->from_area_cd_sel;
            }
            $sessionForm->asp008_status = NULL;
            $sessionForm->asp009_status = NULL;
        } else {
            Sgmov_Component_Log::debug('表示ボタン押下ではない');
            if (!isset($sessionForm->asp009_in) || $this->_checkAsp005IsChanged($sessionForm)) {
                $sessionForm = $this->_createSessionAsp009($sessionForm);
            }
        }

        // セッション情報の保存
        $session->saveForm($this->getFeatureId(), $sessionForm);
        if (isset($sessionForm->asp009_error)) {
            $errorForm = $sessionForm->asp009_error;
        } else {
            $errorForm = new Sgmov_Form_Error();
        }

        // セッションから出力情報を生成
        $outForm = $this->_createOutFormBySessionForm($sessionForm);

        if ($searchErrorForm->hasError()) {
            // セッションから出力情報を生成する際に「コースプラン」「出発地」の
            // 画面上の選択値にはカレントコースプラン、カレント出発地が適用される。
            //
            // 検索条件入力エラーの場合は表示用の値がカレントのものとは異なるため、
            // 全ての情報を設定した後に入力値(POST値)から適用する。
            $outForm->raw_course_plan_cd_sel = $inForm->course_plan_cd_sel;
            $outForm->raw_from_area_cd_sel = $inForm->from_area_cd_sel;
        }

        Sgmov_Component_Log::debug('チケット発行');
        $ticket = $session->publishTicket($this->getFeatureId(), self::GAMEN_ID_ASP009);

        return array('ticket'=>$ticket,
                         'outForm'=>$outForm,
                         'errorForm'=>$errorForm,
                         'searchErrorForm'=>$searchErrorForm);
    }

    /**
     * セッションに保存されているASP009の情報とASP005情報を比較して、
     * ASP005が変更されているかどうかをチェックします。
     *
     * 金額入力のための情報(ASP009)を作成した後に、コースプラン・発着地画面に戻って値(ASP005)を変更した場合に
     * 金額情報の再生成が必要なので変更のチェックを行います。
     *
     * ASP009には金額情報を生成する元となったASP005の情報を保持しているので、
     * その値とASP005の値を比較します。
     *
     * @param Sgmov_Form_AspSession $sessionForm セッションフォーム
     * @return boolean TRUE:変更されている、FALSE:変更されていない
     */
    public function _checkAsp005IsChanged($sessionForm)
    {
        $asp009In = $sessionForm->asp009_in;
        $asp005In = $sessionForm->asp005_in;

        if ($asp009In->course_plan_sel_cds != $asp005In->course_plan_sel_cds || $asp009In->from_area_sel_cds != $asp005In->from_area_sel_cds ||
             $asp009In->to_area_sel_cds != $asp005In->to_area_sel_cds) {
            Sgmov_Component_Log::debug('ASP005変更有り');
            return TRUE;
        }
        return FALSE;
    }

    /**
     * POST情報から入力フォームを生成します。
     *
     * @param array $post ポスト情報
     * @return Sgmov_Form_Asp009In 入力フォーム
     */
    public function _createInFormFromPost($post)
    {
        $inForm = new Sgmov_Form_Asp009In();
        if (isset($post['course_plan_cd_sel'])) {
            $inForm->course_plan_cd_sel = $post['course_plan_cd_sel'];
        } else {
            $inForm->course_plan_cd_sel = '';
        }
        if (isset($post['from_area_cd_sel'])) {
            $inForm->from_area_cd_sel = $post['from_area_cd_sel'];
        } else {
            $inForm->from_area_cd_sel = '';
        }
        if (isset($post['sp_setting_charges'])) {
            $inForm->sp_setting_charges = $post['sp_setting_charges'];
        }
        return $inForm;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_Asp009In $inForm 入力フォーム
     * @param array $coursePlanCds コースプランコードの配列
     * @param array $fromAreaCds 出発エリアコードの配列
     * @param array $toAreaCds 到着エリアコードの配列
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($inForm, $coursePlanCds, $fromAreaCds, $toAreaCds)
    {
        $errorForm = new Sgmov_Form_Error();

        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->course_plan_cd_sel);
        // 入力エラー
        $v->isSelected();
        if (!$v->isValid()) {
            $errorForm->addError('top_course_plan_cd_sel', $v->getResultMessageTop());
        } else {
            $v->isIn($coursePlanCds);
            // 通常の入力ではありえない値の場合はシステムエラー
            if (!$v->isValid()) {
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT, 'コースプランリストにないコードが指定されました。');
            }
        }

        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->from_area_cd_sel);
        // 入力エラー
        $v->isSelected();
        if (!$v->isValid()) {
            $errorForm->addError('top_from_area_cd_sel', $v->getResultMessageTop());
        } else {
            // 通常の入力ではありえない値の場合はシステムエラー
            $v->isIn($fromAreaCds);
            if (!$v->isValid()) {
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT, '出発エリアリストにないコードが指定されました。');
            }
        }

        if (isset($inForm->sp_setting_charges)) {
            // 通常の入力ではありえない値の場合はシステムエラー
            $count = count($inForm->sp_setting_charges);
            if ($count != count($toAreaCds)) {
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT, '差額一覧と到着エリア一覧の数が一致していません。');
            }
            // この段階で数値や有効な値である必要はないが、全ての項目が文字列であることを確認しておく
            for ($i = 0; $i < $count; $i++) {
                if (!is_string($inForm->sp_setting_charges[$i])) {
                    Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT, '差額に文字列以外の情報が入力されました。');
                }
            }
        }

        return $errorForm;
    }

    /**
     * コースプランコードと出発エリアコードからコースプラン出発エリアコードを作成します。
     *
     * コースプラン出発エリアコードは差額情報(sp_setting_charges)のキーとして使用されます。
     *
     * @param string $coursePlanCd
     * @param string $fromAreaCd
     * @return コースプラン出発エリアコード
     */
    public function _createCoursePlanAreaCd($coursePlanCd, $fromAreaCd)
    {
        return $coursePlanCd . Sgmov_Service_CoursePlan::ID_DELIMITER . $fromAreaCd;
    }

    /**
     * セッションフォームに入力フォームの金額情報を適用します。
     * @param Sgmov_Form_AspSession $sessionForm セッションフォーム
     * @param Sgmov_Form_Asp009In $inForm 入力フォーム
     * @return Sgmov_Form_AspSession セッションフォーム
     */
    public function _applyPricesToSession($sessionForm, $inForm)
    {
        if (isset($inForm->sp_setting_charges)) {
            Sgmov_Component_Log::debug('金額適用');
            $coursePlanCd = $sessionForm->asp009_in->cur_course_plan_cd;
            $fromAreaCd = $sessionForm->asp009_in->cur_from_area_cd;

            $coursePlanAreaCd = $this->_createCoursePlanAreaCd($coursePlanCd, $fromAreaCd);
            $sessionForm->asp009_in->all_charges[$coursePlanAreaCd] = $inForm->sp_setting_charges;
        }
        return $sessionForm;
    }

    /**
     * セッションフォームの情報を元に全金額情報を生成します。
     *
     * 既に金額情報が設定されていた場合は、新たなフォームでも可能な限りその金額を使用します。
     * 対応する項目がない場合は空文字列で初期化します。
     *
     * 金額情報が未設定で一括指定情報(Asp008)が存在する場合は、その値で初期化を行います。
     *
     * それ以外の場合は空文字列で初期化します。
     *
     * @param Sgmov_Form_AspSession $sessionForm セッションフォーム
     * @return Sgmov_Form_AspSession セッションフォーム
     */
    public function _createSessionAsp009($sessionForm)
    {
        if (isset($sessionForm->asp009_in) && isset($sessionForm->asp009_in->all_charges)) {
            $prevCharges = $sessionForm->asp009_in->all_charges;
            $prevToAreaCds = $sessionForm->asp009_in->to_area_sel_cds;
        }

        $sessionForm->asp009_in = new Sgmov_Form_Asp009In();

        $asp005In = $sessionForm->asp005_in;
        $asp009In = $sessionForm->asp009_in;

        $asp009In->course_plan_sel_cds = $asp005In->course_plan_sel_cds;
        $asp009In->from_area_sel_cds = $asp005In->from_area_sel_cds;
        $asp009In->to_area_sel_cds = $asp005In->to_area_sel_cds;

        // 全金額情報
        if (isset($prevCharges)) {
            // 既に金額情報が設定されていた場合
            $asp009In->all_charges = array();
            foreach ($asp009In->course_plan_sel_cds as $coursePlanCd) {
                foreach ($asp009In->from_area_sel_cds as $fromAreaCd) {
                    $cd = $this->_createCoursePlanAreaCd($coursePlanCd, $fromAreaCd);
                    $asp009In->all_charges[$cd] = array();
                    foreach ($asp009In->to_area_sel_cds as $toAreaCd) {
                        $price = '';
                        if (isset($prevCharges[$cd])) {
                            $key = array_search($toAreaCd, $prevToAreaCds, TRUE);
                            if ($key !== FALSE) {
                                $price = $prevCharges[$cd][$key];
                            }
                        }
                        $asp009In->all_charges[$cd][] = $price;
                    }
                }
            }
        } else {
            $price = '';

            // 一括金額の取得
            if (isset($sessionForm->asp008_in)) {
                // ASP008情報がセッションに存在してエラーがない場合
                // またエラー情報が全く設定されていない場合(編集時にこの状態になる)
                // ASP008の情報が有効であるとする。
                // エラー情報を見なければ数字以外の無効な文字列などが適用される可能性がある。
                //
                // 入力チェックでエラーがない場合にはstatusに成功を示す値がセットされるが
                // 画面を戻るとstatusはクリアされるためここでは使用できない。
                //
                // そのためエラー情報をみている。
                if (!isset($sessionForm->asp008_error) || !$sessionForm->asp008_error->hasError()) {
                    $price = $sessionForm->asp008_in->sp_whole_charge;
                }
            }

            $asp009In->all_charges = array();
            foreach ($asp009In->course_plan_sel_cds as $coursePlanCd) {
                foreach ($asp009In->from_area_sel_cds as $fromAreaCd) {
                    $cd = $this->_createCoursePlanAreaCd($coursePlanCd, $fromAreaCd);
                    $asp009In->all_charges[$cd] = array();
                    foreach ($asp009In->to_area_sel_cds as $toAreaCd) {
                        $asp009In->all_charges[$cd][] = $price;
                    }
                }
            }
        }
        return $sessionForm;
    }

    /**
     * セッション情報を元に出力情報を生成します。
     * @param Sgmov_Form_AspSession $sessionForm セッションフォーム
     * @return Sgmov_Form_Asp009Out 出力情報
     */
    public function _createOutFormBySessionForm($sessionForm)
    {
        $outForm = new Sgmov_Form_Asp009Out();
        $inForm = $sessionForm->asp009_in;

        // 本社ユーザーかどうか
        $outForm->raw_honsha_user_flag = $this->getHonshaUserFlag();
        // 一覧に戻るリンクのURL
        $outForm->raw_sp_list_url = $this->createSpListUrl($sessionForm->sp_list_kind, $sessionForm->sp_list_view_mode);
        // 閑散繁忙設定かキャンペーン設定か
        $outForm->raw_sp_kind = $this->getSpKind();

        // プルダウン用
        $outForm->raw_course_plan_cds = $inForm->course_plan_sel_cds;
        $outForm->raw_from_area_cds = $inForm->from_area_sel_cds;

        // 現在の差額一覧表示に使用されているコードを選択コードとして設定する。
        // 入力エラーがあった場合は選択コードはカレントコードに一致しないため後で設定しなおす。
        // 入力エラー以外の遷移ではカレントコードに一致するものが選択されている状態にするために
        // この操作を行っている。
        if (isset($inForm->cur_course_plan_cd)) {
            $outForm->raw_cur_course_plan_cd = $inForm->cur_course_plan_cd;
            $outForm->raw_course_plan_cd_sel = $inForm->cur_course_plan_cd;
        }
        if (isset($inForm->cur_from_area_cd)) {
            $outForm->raw_cur_from_area_cd = $inForm->cur_from_area_cd;
            $outForm->raw_from_area_cd_sel = $inForm->cur_from_area_cd;
        }

        // 金額
        if (isset($inForm->cur_course_plan_cd) && isset($inForm->cur_from_area_cd)) {
            $outForm->raw_cond_selected_flag = '1';

            $coursePlanAreaCd = $this->_createCoursePlanAreaCd($inForm->cur_course_plan_cd, $inForm->cur_from_area_cd);
            $outForm->raw_sp_setting_charges = $inForm->all_charges[$coursePlanAreaCd];
        } else {
            $outForm->raw_cond_selected_flag = '0';
        }

        $db = Sgmov_Component_DB::getAdmin();

        // コースプランの名称
        $outForm->raw_course_plan_lbls = array();
        $courseList = $this->_coursePlanService->
                            fetchCoursePlanList($db);
        $courseIds = $courseList['ids'];
        $courseNames = $courseList['names'];
        foreach ($inForm->course_plan_sel_cds as $cd) {
            $key = array_search($cd, $courseIds, TRUE);
            if ($key === FALSE) {
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, 'データ不整合');
            }
            $outForm->raw_course_plan_lbls[] = $courseNames[$key];
        }

        // 出発エリアの名称
        $outForm->raw_from_area_lbls = array();
        $fromAreaList = $this->_centerAreaService->
                                fetchFromAreaList($db);
        $fromAreaIds = $fromAreaList['ids'];
        $fromAreaNames = $fromAreaList['names'];
        foreach ($inForm->from_area_sel_cds as $cd) {
            $key = array_search($cd, $fromAreaIds, TRUE);
            if ($key === FALSE) {
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, 'データ不整合');
            }
            $outForm->raw_from_area_lbls[] = $fromAreaNames[$key];
        }

        // 選択されているコースプラン・出発エリアと到着エリアリストの名称
        if ($outForm->raw_cond_selected_flag === '1') {
            // コースプランの名称
            $key = array_search($outForm->raw_cur_course_plan_cd, $courseIds, TRUE);
            if ($key === FALSE) {
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, 'データ不整合');
            }
            $outForm->raw_cur_course_plan = $courseNames[$key];

            // 出発エリアリストの名称
            $key = array_search($outForm->raw_cur_from_area_cd, $fromAreaIds, TRUE);
            if ($key === FALSE) {
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, 'データ不整合');
            }
            $outForm->raw_cur_from_area = $fromAreaNames[$key];

            // 到着エリアリストの名称
            $outForm->raw_to_area_lbls = array();
            $toAreaList = $this->_centerAreaService->
                                fetchToAreaList($db);
            $toAreaIds = $toAreaList['ids'];
            $toAreaNames = $toAreaList['names'];
            foreach ($inForm->to_area_sel_cds as $cd) {
                $key = array_search($cd, $toAreaIds, TRUE);
                if ($key === FALSE) {
                    Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, 'データ不整合');
                }
                $outForm->raw_to_area_lbls[] = $toAreaNames[$key];
            }

            // 基本料金,差額の上限・下限料金

            // 現在編集中の特価は料金計算から除外する
            $sp_cd = '-1';
            if(!empty($sessionForm->sp_cd)){
                $sp_cd = $sessionForm->sp_cd;
            }

            $outForm->raw_sp_base_charges = array();
            $splits = explode(Sgmov_Service_CoursePlan::ID_DELIMITER, $outForm->raw_cur_course_plan_cd);
            $basePriceList = $this->_workPriceService->
                                    fetchBaseDiffMinMaxPrice($db, $splits[0], $splits[1], $outForm->raw_cur_from_area_cd,
                                         $sessionForm->asp006_in->sel_days, $sp_cd);
            $basePriceToAreaIds = $basePriceList['to_area_ids'];
            $basePrices = $basePriceList['base_prices'];
            $diffMaxs = $basePriceList['diff_maxs'];
            $diffMins = $basePriceList['diff_mins'];
            foreach ($inForm->to_area_sel_cds as $cd) {
                $key = array_search($cd, $basePriceToAreaIds, TRUE);
                if ($key === FALSE) {
                    Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, 'データ不整合');
                }
                $outForm->raw_sp_base_charges[] = $basePrices[$key];
                $outForm->raw_sp_diff_maxs[] = $diffMaxs[$key];
                $outForm->raw_sp_diff_mins[] = $diffMins[$key];
            }
        }

        return $outForm;
    }

}
?>
