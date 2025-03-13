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
Sgmov_Lib::useView('acf/Common');
Sgmov_Lib::useServices(array('Login', 'CenterArea', 'CoursePlan', 'BasePrice'));
Sgmov_Lib::useForms(array('Error', 'AcfSession', 'Acf002In', 'Acf002Out'));
/**#@-*/

 /**
 * 料金マスタメンテナンス入力画面を表示します。
 * @package    View
 * @subpackage ACF
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Acf_Input extends Sgmov_View_Acf_Common
{
    /**
     * ログインサービス
     * @var Sgmov_Service_Login
     */
    public $_loginService;

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
     * 基本料金サービス
     * @var Sgmov_Service_BasePrice
     */
    public $_basePriceService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        $this->_loginService = new Sgmov_Service_Login();
        $this->_centerAreaService = new Sgmov_Service_CenterArea();
        $this->_coursePlanService = new Sgmov_Service_CoursePlan();
        $this->_basePriceService = new Sgmov_Service_BasePrice();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * 表示ボタン押下ではない場合
     *   <ol><li>
     *   セッションに情報がある場合、セッション情報を元に出力情報を作成
     *   </li><li>
     *   セッションに情報がない場合、出力情報を作成
     *   </li><li>
     *   その他情報を生成
     *   </li><li>
     *   チケット発行
     *   </li></ol>
     * </li><li>
     * 表示ボタン押下の場合
     *   <ol><li>
     *   入力エラーがなければ
     *     <ol><li>
     *     セッション情報をクリア
     *     </li><li>
     *     検索実行
     *     </li><li>
     *     セッションに検索情報を保存(元金額・金額共に書き込み)
     *     </li></ol>
     *   </li><li>
     *   セッションに情報がある場合、セッション情報を元に出力情報を作成
     *   </li><li>
     *   セッションに情報がない場合、出力情報を作成
     *   </li><li>
     *   入力エラーの場合には入力値がセッションに保存されていないので出力フォームに設定する
     *   </li><li>
     *   その他情報を生成
     *   </li><li>
     *   チケット発行
     *   </li></ol>
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
        // DB接続
        $db = Sgmov_Component_DB::getAdmin();

        // セッションの取得
        $session = Sgmov_Component_Session::get();

        // コースプラン・出発エリアのリストを取得しておく
        $coursePlans = $this->_coursePlanService->
                            fetchCoursePlanList($db);
        $fromAreas = $this->_centerAreaService->
                            fetchFromAreaList($db);

        if (!isset($_POST['reading_btn_x'])) {
            Sgmov_Component_Log::debug('表示ボタン押下ではない場合');

            // セッションに情報があるかどうかを確認
            $sessionForm = $session->loadForm(self::FEATURE_ID);
            if (isset($sessionForm)) {
                // セッション情報を元に出力情報を作成
                $outForm = $this->_createOutFormBySessionForm($sessionForm);
                $errorForm = $sessionForm->error;
            } else {
                // 出力情報を設定
                $outForm = new Sgmov_Form_Acf002Out();
                $outForm->raw_cond_selected_flag = '0';
                $errorForm = new Sgmov_Form_Error();
            }

            // その他情報を生成
            $outForm = $this->_setTemplateValuesToOutForm($outForm, $coursePlans, $fromAreas);

            // チケット発行
            $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_ACF002);

            return array('ticket'=>$ticket,
                             'outForm'=>$outForm,
                             'errorForm'=>$errorForm,
                             'searchErrorForm'=> new Sgmov_Form_Error());
        } else {
            Sgmov_Component_Log::debug('表示ボタン押下の場合');

            // 入力値の取得
            $searchInForm = $this->_createInFormFromPost($_POST);

            // 入力チェック
            $searchErrorForm = $this->_validate($searchInForm, $coursePlans['ids'], $fromAreas['ids'], $db);
            if (!$searchErrorForm->hasError()) {
                // 入力エラーがなければセッション情報をクリア
                $session->deleteForm(self::FEATURE_ID);

                // 検索実行
                $ids = explode(Sgmov_Service_CoursePlan::ID_DELIMITER, $searchInForm->course_plan_cd_sel);
                $pricesInfo = $this->_basePriceService->
                                    fetchBasePricesExistence($db, $ids[0], $ids[1], $searchInForm->from_area_cd_sel);

                // セッションに検索情報を保存(元金額・金額共に書き込み)
                $sessionForm = new Sgmov_Form_AcfSession();

                $sessionForm->cur_course_plan_cd = $searchInForm->course_plan_cd_sel;
                $key = array_search($sessionForm->cur_course_plan_cd, $coursePlans['ids']);
                $sessionForm->cur_course_plan = $coursePlans['names'][$key];

                $sessionForm->cur_from_area_cd = $searchInForm->from_area_cd_sel;
                $key = array_search($sessionForm->cur_from_area_cd, $fromAreas['ids']);
                $sessionForm->cur_from_area = $fromAreas['names'][$key];

                $sessionForm->to_area_cds = $pricesInfo['to_area_ids'];
                $sessionForm->to_area_lbls = $pricesInfo['to_area_names'];
                $sessionForm->base_price_cds = $pricesInfo['base_price_ids'];
                $sessionForm->base_prices = $pricesInfo['base_prices'];
                $sessionForm->max_prices = $pricesInfo['max_prices'];
                $sessionForm->min_prices = $pricesInfo['min_prices'];
                $sessionForm->orig_base_prices = $pricesInfo['base_prices'];
                $sessionForm->orig_max_prices = $pricesInfo['max_prices'];
                $sessionForm->orig_min_prices = $pricesInfo['min_prices'];
                $sessionForm->modifieds = $pricesInfo['modifieds'];

                $sessionForm->status = self::VALIDATION_NOT_YET;
                $sessionForm->error = new Sgmov_Form_Error();

                $session->saveForm(self::FEATURE_ID, $sessionForm);
            }

            // セッションに情報があるかどうかを確認
            $sessionForm = $session->loadForm(self::FEATURE_ID);
            if (isset($sessionForm)) {
                // セッション情報を元に出力情報を作成
                $outForm = $this->_createOutFormBySessionForm($sessionForm);
                $errorForm = $sessionForm->error;
            } else {
                // 出力情報を設定
                $outForm = new Sgmov_Form_Acf002Out();
                $outForm->raw_cond_selected_flag = '0';
                $errorForm = new Sgmov_Form_Error();
            }

            // 入力エラーの場合には検索条件はセッションに保存されていないので出力フォームに設定する
            if ($searchErrorForm->hasError()) {
                $outForm->raw_course_plan_cd_sel = $searchInForm->course_plan_cd_sel;
                $outForm->raw_from_area_cd_sel = $searchInForm->from_area_cd_sel;
                $outForm->raw_base_prices = $searchInForm->base_prices;
                $outForm->raw_max_prices = $searchInForm->max_prices;
                $outForm->raw_min_prices = $searchInForm->min_prices;
            }

            // その他情報を生成
            $outForm = $this->_setTemplateValuesToOutForm($outForm, $coursePlans, $fromAreas);

            // チケット発行
            $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_ACF002);

            return array('ticket'=>$ticket,
                             'outForm'=>$outForm,
                             'errorForm'=>$errorForm,
                             'searchErrorForm'=>$searchErrorForm);
        }
    }

    /**
     * POST情報から入力フォームを生成します。
     * @param array $post ポスト情報
     * @return Sgmov_Form_Acf002In 入力フォーム
     */
    public function _createInFormFromPost($post)
    {
        $inForm = new Sgmov_Form_Acf002In();

        $inForm->course_plan_cd_sel = $post['course_plan_cd_sel'];
        //$inForm->from_area_cd_sel = $post['from_area_cd_sel'];
        $inForm->from_area_cd_sel = $post['formareacd'];
        if (isset($post['base_prices'])) {
            $inForm->base_prices = $post['base_prices'];
        }
        if (isset($post['max_prices'])) {
            $inForm->max_prices = $post['max_prices'];
        }
        if (isset($post['min_prices'])) {
            $inForm->min_prices = $post['min_prices'];
        }

        return $inForm;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_Acf002In $inForm 入力フォーム
     * @param array $coursePlanCds コースプランコードの配列
     * @param array $fromAreaCds 出発エリアコードの配列
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($inForm, $coursePlanCds, $fromAreaCds, $db)
    {
        $errorForm = new Sgmov_Form_Error();

        $errFlag = false;

        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->course_plan_cd_sel);
        $v->isIn($coursePlanCds);
        // 通常の入力ではありえない値の場合はシステムエラー
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        // 入力エラー
        $v->isSelected();
        if (!$v->isValid()) {
            $errorForm->addError('top_course_plan_cd_sel', $v->getResultMessageTop());
            $errFlag = true;
        }

        $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->from_area_cd_sel);
        $v->isIn($fromAreaCds);
        // 通常の入力ではありえない値の場合はシステムエラー
        if (!$v->isValid()) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
        }
        
        // 入力エラー
        $v->isSelected();
        if (!$v->isValid()) {
            $errorForm->addError('top_from_area_cd_sel', $v->getResultMessageTop());
            $errFlag = true;
        }

        // コース・プラン・出発地で整合性チェック
        if (!$errFlag) {
            if (!$this->_coursePlanService->checkCourcePlanFrom($db, $inForm->course_plan_cd_sel, $inForm->from_area_cd_sel)) {
        		$errorForm->addError('top_course_plan_cd_sel', '入力されたコース・プラン・出発地域が不正です。');
        	}
        }

        return $errorForm;
    }

    /**
     * セッションフォームの値を元に出力フォームを生成します。
     * @param Sgmov_Form_AcfSession $sessionForm セッションフォーム
     * @return Sgmov_Form_Acf002Out 出力フォーム
     */
    public function _createOutFormBySessionForm($sessionForm)
    {
        $outForm = new Sgmov_Form_Acf002Out();
        $outForm->raw_cond_selected_flag = '1';
        $outForm->raw_course_plan_cd_sel = $sessionForm->cur_course_plan_cd;
        $outForm->raw_from_area_cd_sel = $sessionForm->cur_from_area_cd;
        $outForm->raw_to_area_cds = $sessionForm->to_area_cds;
        $outForm->raw_to_area_lbls = $sessionForm->to_area_lbls;
        $outForm->raw_base_prices = $sessionForm->base_prices;
        $outForm->raw_min_prices = $sessionForm->min_prices;
        $outForm->raw_max_prices = $sessionForm->max_prices;
        $outForm->raw_orig_base_prices = $sessionForm->orig_base_prices;
        $outForm->raw_orig_min_prices = $sessionForm->orig_min_prices;
        $outForm->raw_orig_max_prices = $sessionForm->orig_max_prices;
        return $outForm;
    }

    /**
     * 出力フォームにテンプレート用の値を設定して返します。
     * @param Sgmov_Form_Acf002Out $outForm 出力フォーム
     * @param array $coursePlanCds コースプランの配列
     * @param array $fromAreaCds 出発エリアの配列
     * @return Sgmov_Form_Acf002Out 出力フォーム
     */
    public function _setTemplateValuesToOutForm($outForm, $coursePlans, $fromAreas)
    {
        $outForm->raw_honsha_user_flag = $this->_loginService->
                                                getHonshaUserFlag();

        $db = Sgmov_Component_DB::getAdmin();

        $outForm->raw_course_plan_cds  = $coursePlans['ids'];
        $outForm->raw_course_plan_lbls = $coursePlans['names'];

        $outForm->raw_from_area_cds  = $fromAreas['ids'];
        $outForm->raw_from_area_lbls = $fromAreas['names'];

        return $outForm;
    }

    /**
     * プルダウンを生成し、HTMLソースを返します。
     * 
     *
     * @param $cds コードの配列
     * @param $lbls ラベルの配列
     * @param $select 選択値
     * @return 生成されたプルダウン
     */
    public static function _createPulldown($cds, $lbls, $select, $mode) {

        $html = '';

        $count = count($cds);
        if ($mode == Sgmov_View_Acf_Common::AREA_HYOJITYPE_OKINAWANASHI) {
            // 単身カーゴプラン
            // TODO 沖縄県が末尾にある前提
            for ($i = 0; $i < ($count - 1); ++$i) {
                if ($select === $cds[$i]) {
                    $html .= '<option value="' . $cds[$i] . '" selected="selected">' . $lbls[$i] . "</option>\n";
                } else {
                    $html .= '<option value="' . $cds[$i] . '">' . $lbls[$i] . "</option>\n";
                }
            }
        } else if ($mode == Sgmov_View_Acf_Common::AREA_HYOJITYPE_AIRCARGO) {
            // 単身エアカーゴプラン
            // TODO 北海道（札幌市）、東京23区、福岡県のコードが変わらない前提
            // TODO 将来的に、以下の東京23区、福岡県のコードをシステム全体で1つのファイルに保持しておきたいところ
            for ($i = 0; $i < $count; ++$i) {
                if (in_array($cds[$i], array("", "1", "17", "45"))) {
                    if ($select === $cds[$i]) {
                        $html .= '<option value="' . $cds[$i] . '" selected="selected">' . $lbls[$i] . "</option>\n";
                    } else {
                        $html .= '<option value="' . $cds[$i] . '">' . $lbls[$i] . "</option>\n";
                    }
                }
            }
        } else {
            for ($i = 0; $i < $count; ++$i) {
                if ($select === $cds[$i]) {
                    $html .= '<option value="' . $cds[$i] . '" selected="selected">' . $lbls[$i] . "</option>\n";
                } else {
                    $html .= '<option value="' . $cds[$i] . '">' . $lbls[$i] . "</option>\n";
                }
            }
        }


        return $html;
    }
}