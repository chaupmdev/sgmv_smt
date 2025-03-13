<?php

/**
 * @package    ClassDefFile
 * @author     H.Tsuji(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__).'/../../Lib.php';
Sgmov_Lib::useView('pre/Common');
Sgmov_Lib::useServices(array('OtherCampaign'));
Sgmov_Lib::useForms(array('Error', 'Pre001In', 'Pre001Out', 'Pre002In', 'Pre002Out', 'PveSession'));
/**#@-*/

/**
 * 概算見積り入力
 * @package    View
 * @subpackage PRE
 * @author     H.Tsuji(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pre_Input extends Sgmov_View_Pre_Common {

    /**
     * 拠点・エリアサービス
     * @var Sgmov_Service_CenterArea
     */
    public $_centerAreaService;
    /**
     * 共通サービス
     * @var Sgmov_Service_AppCommon
     */
    public $_appCommon;

    /**
    * 他社連携キャンペーン
    * @var Sgmov_Service_SpecialPrice
    */
    public $_OtherCampaignService;


    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {

        $this->_centerAreaService    = new Sgmov_Service_CenterArea();
        $this->_appCommon            = new Sgmov_Service_AppCommon();
        $this->_OtherCampaignService = new Sgmov_Service_OtherCampaign();

    }

    /**
     * 概算見積り入力画面に表示するコース返却します。
     *
     * @param type 見積りタイプ
     * @param corce コース
     * @param plan プラン
     * @return 各種出力内容
     */
    public function executeInner() {

        // セッション
        $session = Sgmov_Component_Session::get();

        $refer_menu_personal_flg = isset($_POST["referer"]) && $_POST["referer"] == "menu_personal";
        if ($refer_menu_personal_flg)
        {
            // 個人向けサービス ページから飛んだ時は、前回入力値 ( セッション ) を使用しません。
            $session->deleteForm(self::SCRID_PRE);
            $sessionForm = null;

        } else {
            // セッション情報の取得
            $sessionForm = $session->loadForm(self::SCRID_PRE);
        }

        // GETパラメータ取得
        $getParam = $this->_parseGetParameter();

        if (isset($_GET['oc_flg'])) {
            // 他社連携キャンペーンGETパラメータ取得
            $getParam['oc_flg'] = $_GET['oc_flg'];

            // 出力フォーム編集
            $outForm = $this->_createOutForm(false, NULL, $getParam);

            return array('outForm' => $outForm);

        } elseif(isset($sessionForm)) {
            // セッションが存在する場合  ( 前ページから戻った または 同ページの入力値にエラーがあった )
            // 出力情報セット
            //print_r($sessionForm);

            $outForm = $this->_createOutForm(true, $sessionForm, NULL);
            //print_r($outForm);
            //exit;
            // エラー情報セット
            if (isset($sessionForm->error)) {
                // 既存セッションの削除
                $session->deleteForm(self::SCRID_PRE);
                return array('errorForm' => $sessionForm->error, 'outForm' => $outForm);
            }
            // 既存セッションの削除
            //$session->deleteForm(self::SCRID_PRE);
            return array('outForm' => $outForm);


        } else {

            // 出力フォーム編集
            $outForm = $this->_createOutForm(false, NULL, $getParam);

            return array('outForm' => $outForm);

        }

    }

    /**
     * 入力情報をもとに出力情報を生成します。
     *
     * @param $sessionUmu セッション有無フラグ
     * @param Sgmov_Form_Pre002In $session セッション ( null または inForm )
     * @param $getParam ゲットパラメータ
     * @return Sgmov_Form_Pre001Out $outForm
     */
    public function _createOutForm($sessionUmu, $session, $getParam) {

        // DB接続
        $db = Sgmov_Component_DB::getPublic();

        // Pre001Out生成
        $outForm = new Sgmov_Form_Pre001Out();

        if ($sessionUmu) {

            // セッションが存在し、かつ、エラーが存在する場合
            if (isset($session->error)) {
                // タイプコード
                $outForm->raw_type_cd = self::getSessionValue($session, 'type_cd');
                // コースコード選択値
                $outForm->raw_course_cd_sel = self::getSessionValue($session, 'course_cd_sel');
                // プランコード選択値
                $outForm->raw_plan_cd_sel = self::getSessionValue($session, 'plan_cd_sel');
                // 全選択ボタン押下フラグ
                $outForm->raw_all_sentakbtn_click_flag = self::getSessionValue($session, 'all_sentakbtn_click_flag');
                // 入力画面初期表示時コースコード選択値
                $outForm->raw_init_course_cd_sel = self::getSessionValue($session, 'init_course_cd_sel');
                // 入力画面初期表示時プランコード選択値
                $outForm->raw_init_plan_cd_sel = self::getSessionValue($session, 'init_plan_cd_sel');
                // エアコン有無フラグ選択値
                $outForm->raw_aircon_exist_flag_sel = self::getSessionValue($session, 'aircon_exist_flag_sel');
                // 出発エリアコード選択値
                $outForm->raw_from_area_cd_sel = self::getSessionValue($session, 'from_area_cd_sel');
                // 到着エリアコード選択値
                $outForm->raw_to_area_cd_sel = self::getSessionValue($session, 'to_area_cd_sel');
                // 引越予定日年コード選択値
                $outForm->raw_move_date_year_cd_sel = self::getSessionValue($session, 'move_date_year_cd_sel');
                // 引越予定日月コード選択値
                $outForm->raw_move_date_month_cd_sel = self::getSessionValue($session, 'move_date_month_cd_sel');
                // 引越予定日日コード選択値
                $outForm->raw_move_date_day_cd_sel = self::getSessionValue($session, 'move_date_day_cd_sel');
                // コース表示フラグリスト
                $outForm->raw_course_view_flag = $this->_getInitHyojiCorce($this->_appCommon->initHyojiCorce, $outForm->raw_type_cd, $outForm->raw_init_course_cd_sel, $outForm->raw_init_plan_cd_sel);
                // プラン表示フラグリスト
                $outForm->raw_plan_view_flag = $this->_getInitHyojiPlan($this->_appCommon->initHyojiPlan, $outForm->raw_type_cd, $outForm->raw_init_course_cd_sel, $outForm->raw_init_plan_cd_sel);
                // 全てのコース表示
                $outForm->raw_course_allbtn_flag = $this->_getInitAllCorceHyoji($outForm->raw_course_view_flag);

            } else {

                // タイプコード
                $outForm->raw_type_cd = self::getSessionValue($session, 'raw_type_cd');
                // コースコード選択値
                $outForm->raw_course_cd_sel = self::getSessionValue($session, 'raw_course_cd_sel');
                // プランコード選択値
                $outForm->raw_plan_cd_sel = self::getSessionValue($session, 'raw_plan_cd_sel');
                if (empty($outForm->raw_plan_cd_sel) && !empty($session->in)) {
                    $outForm->raw_plan_cd_sel = self::getSessionValue($session->in, 'raw_plan_cd_sel');
                }
                // 全選択ボタン押下フラグ
                $outForm->raw_all_sentakbtn_click_flag = self::getSessionValue($session, 'raw_all_sentakbtn_click_flag');
                // 入力画面初期表示時コースコード選択値
                $outForm->raw_init_course_cd_sel = self::getSessionValue($session, 'raw_init_course_cd_sel');
                // 入力画面初期表示時プランコード選択値
                $outForm->raw_init_plan_cd_sel = self::getSessionValue($session, 'raw_init_plan_cd_sel');
                // エアコン有無フラグ選択値
                $outForm->raw_aircon_exist_flag_sel = self::getSessionValue($session, 'raw_aircon_exist_flag_sel');
                // 出発エリアコード選択値
                $outForm->raw_from_area_cd_sel = self::getSessionValue($session, 'raw_from_area_cd_sel');
                // 到着エリアコード選択値
                $outForm->raw_to_area_cd_sel = self::getSessionValue($session, 'raw_to_area_cd_sel');
                // 引越予定日年コード選択値
                $outForm->raw_move_date_year_cd_sel = self::getSessionValue($session, 'raw_move_date_year_cd_sel');
                // 引越予定日月コード選択値
                $outForm->raw_move_date_month_cd_sel = self::getSessionValue($session, 'raw_move_date_month_cd_sel');
                // 引越予定日日コード選択値
                $outForm->raw_move_date_day_cd_sel = self::getSessionValue($session, 'raw_move_date_day_cd_sel');
                // コース表示フラグリスト
                $outForm->raw_course_view_flag = $this->_getInitHyojiCorce($this->_appCommon->initHyojiCorce, self::getSessionValue($session, 'raw_type_cd'), self::getSessionValue($session, 'raw_init_course_cd_sel'), self::getSessionValue($session, 'raw_init_plan_cd_sel'));
                // プラン表示フラグリスト
                $outForm->raw_plan_view_flag = $this->_getInitHyojiPlan($this->_appCommon->initHyojiPlan, self::getSessionValue($session, 'raw_type_cd'), self::getSessionValue($session, 'raw_init_course_cd_sel'), self::getSessionValue($session, 'raw_init_plan_cd_sel'));
                // 全てのコース表示
                $outForm->raw_course_allbtn_flag = $this->_getInitAllCorceHyoji($outForm->raw_course_view_flag);
            }

            // 他社連携キャンペーンID
            $outForm->raw_oc_id = self::getSessionValue($session, 'raw_oc_id');
            // 他社連携キャンペーン名称
            $outForm->raw_oc_name = self::getSessionValue($session, 'raw_oc_name');
            // 他社連携キャンペーン内容
            $outForm->raw_oc_content = self::getSessionValue($session, 'raw_oc_content');

            // 個人向けサービス ページの選択されたメニュー
            $outForm->menu_personal = isset($session->menu_personal) ? $session->menu_personal : '';

        } else {
            // セッションが存在しない場合

            // 他社連携キャンペーン
            if (isset($getParam['oc_flg'])) {

                $oc_status = $this->_OtherCampaignService->fetchOtherCampaignByStatus2($db, $getParam['oc_flg']);

                if (is_array($oc_status)) {
                    $outForm->raw_oc_id      = $oc_status[0]['id'];
                    $outForm->raw_oc_name    = $oc_status[0]['campaign_name'];
                    $outForm->raw_oc_content = $oc_status[0]['campaign_content'];
                } else {
                    $_SESSION = array();
                    Sgmov_Component_Redirect::redirectPublicSsl('/pre/input/');
                }
            }

            // タイプコード
            if (!preg_match("/^[0-9]+$/", $getParam[0])) {
                // 半角数字でない場合、0をセット
                $outForm->raw_type_cd = 0;
            } else {
                $outForm->raw_type_cd = $getParam[0];
            }
            // コースコード選択値
            if (isset($getParam[1])) {
                if (!preg_match("/^[0-9]+$/", $getParam[1])) {
                    // 半角数字でない場合、0をセット
                    $outForm->raw_course_cd_sel      = 0;
                    $outForm->raw_init_course_cd_sel = 0;
                } else {
                    $outForm->raw_course_cd_sel      = $getParam[1];
                    $outForm->raw_init_course_cd_sel = $getParam[1];
                }
            } else {
                $outForm->raw_course_cd_sel = 0;
            }
            // プランコード選択値
            if (isset($getParam[2])) {
                if (!preg_match("/^[0-9]+$/", $getParam[2])) {
                    // 半角数字でない場合、0をセット
                    $outForm->raw_plan_cd_sel      = 0;
                    $outForm->raw_init_plan_cd_sel = 0;
                } else {
                    $outForm->raw_plan_cd_sel      = $getParam[2];
                    $outForm->raw_init_plan_cd_sel = $getParam[2];
                }
            } else {
                $outForm->raw_plan_cd_sel = 0;
            }
            // コース表示フラグリスト
            $outForm->raw_course_view_flag = $this->_getInitHyojiCorce($this->_appCommon->initHyojiCorce, $outForm->raw_type_cd, $outForm->raw_course_cd_sel, $outForm->raw_plan_cd_sel);
            // プラン表示フラグリスト
            $outForm->raw_plan_view_flag = $this->_getInitHyojiPlan($this->_appCommon->initHyojiPlan, $outForm->raw_type_cd, $outForm->raw_course_cd_sel, $outForm->raw_plan_cd_sel);
            // 全てのコース表示
            $outForm->raw_course_allbtn_flag = $this->_getInitAllCorceHyoji($outForm->raw_course_view_flag);

            // 個人向けサービス ページの選択されたメニュー
            $outForm->menu_personal = filter_input(INPUT_POST, 'personal');

        }

        //出発エリア
        $fromareas = $this->_centerAreaService->fetchFromAreaList($db);
        $outForm->raw_from_area_cds = $fromareas['ids'];
        $outForm->raw_from_area_lbls = $fromareas['names'];
        //到着エリア
        $toareas = $this->_centerAreaService->fetchToAreaList($db);
        $outForm->raw_to_area_cds = $toareas['ids'];
        $outForm->raw_to_area_lbls = $toareas['names'];
        //引越し予定日
        $outForm->raw_move_date_year_cds = $this->_appCommon->getYears(date('Y'), Sgmov_View_Pre_Common::INPUT_MOVEYTIYEAR_CNT, true);
        $outForm->raw_move_date_year_lbls = $this->_appCommon->getYears(date('Y'), Sgmov_View_Pre_Common::INPUT_MOVEYTIYEAR_CNT, true);
        $outForm->raw_move_date_month_cds = $this->_appCommon->months;
        $outForm->raw_move_date_month_lbls = $this->_appCommon->months;
        $outForm->raw_move_date_day_cds = $this->_appCommon->days;
        $outForm->raw_move_date_day_lbls = $this->_appCommon->days;

        // キャンペーン情報を３件以内で取得
        $campains = Sgmov_Service_SpecialPrice::fetchAllCampain($db, Sgmov_View_Pre_Common::INPUT_CAMPGETCNT);
        if ($campains != NULL) {
            // キャンペーン名リスト
            $outForm->raw_campaign_names = $campains['titles'];
            // キャンペーン内容リスト
            $outForm->raw_campaign_contents = $campains['descriptions'];
            // キャンペーン開始日リスト
            $outForm->raw_campaign_ends = $campains['maxdates'];
            // キャンペーン終了日リスト
            $outForm->raw_campaign_starts = $campains['mindates'];
        }

        return $outForm;
    }

    /**
     * ラジオボタンの選択値に対して、checkedを返します。
     *
     * @param $targetCnt 対象ラジオボタン件数
     * @param $selectNo 選択値
     * @return 選択値にcheckedが付与された配列
     */
    public static function _getPulldownSelect($targetCnt, $selectNo) {

        $target = array();

        for ($i = 0; $i < $targetCnt; ++$i) {
            if ($selectNo == ($i + 1)) {
                $target[$i] = Sgmov_View_Pre_Common::CHECKED;
            } else {
                $target[$i] = '';
            }
        }

        return $target;
    }

    /**
     * プルダウンを生成し、HTMLソースを返します。
     *
     * @param $cds コードの配列
     * @param $lbls ラベルの配列
     * @param $select 選択値
     * @return 生成されたプルダウン
     */
    public static function _createAreaPulldown($cds, $lbls, $select, $mode) {

        $html = "";

        if ($mode == Sgmov_View_Pre_Common::AREA_HYOJITYPE_OKINAWANASHI) {
            // 単身カーゴプラン
            // TODO 沖縄県が末尾にある前提
            for ($i = 0; $i < (count($cds) - 1); ++$i) {
                if ($select === $cds[$i]) {
                    $html .= '<option selected="selected" value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
                } else {
                    $html .= '<option value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
                }
            }
        } elseif ($mode == Sgmov_View_Pre_Common::AREA_HYOJITYPE_AIRCARGO) {
            // 単身エアカーゴプラン
            // TODO 北海道（札幌市）、東京23区、大阪府、福岡県のコードが変わらない前提
            // TODO 将来的に、以下の東京23区、大阪府、福岡県のコードをシステム全体で1つのファイルに保持しておきたいところ
            for ($i = 0; $i < count($cds); ++$i) {
                if (in_array($cds[$i], array("", "1", "17", "32", "45"))) {
                    if ($select === $cds[$i]) {
                        $html .= '<option selected="selected" value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
                    } else {
                        $html .= '<option value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
                    }
                }
            }
        } elseif ($mode == Sgmov_View_Pre_Common::AREA_HYOJITYPE_AIRCARGO_TO_FUK) {
            // 単身エアカーゴプラン(福岡発)
            // TODO 北海道（札幌市）、東京23区、大阪府、福岡県のコードが変わらない前提
            // TODO 将来的に、以下の東京23区、大阪府、福岡県のコードをシステム全体で1つのファイルに保持しておきたいところ
            for ($i = 0; $i < count($cds); ++$i) {
                if (in_array($cds[$i], array("", "1", "17"))) {
                    if ($select === $cds[$i]) {
                        $html .= '<option selected="selected" value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
                    } else {
                        $html .= '<option value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
                    }
                }
            }
        } elseif ($mode == Sgmov_View_Pre_Common::AREA_HYOJITYPE_AIRCARGO_TO_TOK) {
            // 単身エアカーゴプラン(東京発)
            // TODO 北海道（札幌市）、東京23区、大阪府、福岡県のコードが変わらない前提
            // TODO 将来的に、以下の東京23区、大阪府、福岡県のコードをシステム全体で1つのファイルに保持しておきたいところ
            for ($i = 0; $i < count($cds); ++$i) {
                if (in_array($cds[$i], array("", "1", "45"))) {
                    if ($select === $cds[$i]) {
                        $html .= '<option selected="selected" value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
                    } else {
                        $html .= '<option value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
                    }
                }
            }
        } elseif ($mode == Sgmov_View_Pre_Common::AREA_HYOJITYPE_AIRCARGO_TO_HOK) {
            // 単身エアカーゴプラン(北海道発)
            // TODO 北海道（札幌市）、東京23区、大阪府、福岡県のコードが変わらない前提
            // TODO 将来的に、以下の東京23区、大阪府、福岡県のコードをシステム全体で1つのファイルに保持しておきたいところ
            for ($i = 0; $i < count($cds); ++$i) {
                if (in_array($cds[$i], array("", "17", "32", "45"))) {
                    if ($select === $cds[$i]) {
                        $html .= '<option selected="selected" value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
                    } else {
                        $html .= '<option value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
                    }
                }
            }
        } elseif ($mode == Sgmov_View_Pre_Common::AREA_HYOJITYPE_AIRCARGO_TO) {
            // 単身エアカーゴプラン(出発)
            // TODO 北海道（札幌市）、東京23区、福岡県のコードが変わらない前提
            // TODO 将来的に、以下の東京23区、福岡県のコードをシステム全体で1つのファイルに保持しておきたいところ
            for ($i = 0; $i < count($cds); ++$i) {
                if (in_array($cds[$i], array("", "1", "17", "45"))) {
                    if ($select === $cds[$i]) {
                        $html .= '<option id="air_' . $cds[$i] . '" value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
                    } else {
                        $html .= '<option id="air_' . $cds[$i] . '" value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
                    }
                }
            }
        } else {
            for ($i = 0; $i < count($cds); ++$i) {
                if ($select === $cds[$i]) {
                    $html .= '<option selected="selected" value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
                } else {
                    $html .= '<option value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
                }
            }
        }

        return $html;
    }

    /**
     * プルダウンを生成し、HTMLソースを返します。
     *
     * @param $cds コードの配列
     * @param $lbls ラベルの配列
     * @param $select 選択値
     * @return 生成されたプルダウン
     */
    public static function _createPulldown($cds, $lbls, $select) {

        $html = '';

        $count = count($cds);
        for ($i = 0; $i < $count; ++$i) {
            if ($select === $cds[$i]) {
                $html .= '<option selected="selected" value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
            } else {
                $html .= '<option value="' . $cds[$i] . '">' . $lbls[$i] . '</option>' . PHP_EOL;
            }
        }

        return $html;
    }
}