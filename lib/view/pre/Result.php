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
Sgmov_Lib::useServices(array('Calendar'));
Sgmov_Lib::useForms(array('Error', 'Pre002In', 'Pre001Out', 'Pre002Out', 'PveSession'));
/**#@-*/
/**
 * 概算見積り入力内容をチェックし、見積り情報を表示します。
 * @package    View
 * @subpackage PRE
 * @author     H.Tsuji(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pre_Result extends Sgmov_View_Pre_Common {

    /**
     * 共通サービス
     * @var Sgmov_Service_AppCommon
     */
    public $_appCommon;

    /**
     * カレンダーサービス
     * @var Sgmov_Service_Calendar
     */
    public $_calendarService;

    /**
     * カレンダー表示開始日
     * @var Sgmov_View_Pre_Result
     */
    public $_calStartYmd = "";

    /**
     * カレンダー表示終了日
     * @var Sgmov_View_Pre_Result
     */
    public $_calEndYmd = "";

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_appCommon = new Sgmov_Service_AppCommon();
        $this->_calendarService = new Sgmov_Service_Calendar();
    }

    /**
     * 処理を実行します。
     *
     * 入力値のチェックを行います。
     * エラーが存在すれば入力画面を、エラーが存在しなければ見積もり表示画面を表示します
     *
     */
    public function executeInner() {

        // POSTパラメータを取得
        $postParam = $this->_parseGetParameter();

        // セッション接続
        $session = Sgmov_Component_Session::get();
        
        // DB接続
        $db = Sgmov_Component_DB::getPublic();

        switch ($postParam['mode']) {
            case Sgmov_View_Pre_Common::FUNC_INIT:
                // セッション情報の取得
                $inForm = $session->loadForm(self::SCRID_PRE);
                
                $classNm = "";
                if (isset($inForm)) {
                    $classNm = get_class($inForm);
                }
                if ($classNm == Sgmov_View_Pre_Common::PVE_SESSION_CLASS) {

                    // クラス詰め替え
                    $newForm = $this->_replaceSession($inForm);

                    $outForm = $this->_createOutForm($db, $newForm, NULL, Sgmov_View_Pre_Common::FUNC_INIT, $newForm->move_date_year_cd_sel, $newForm->move_date_month_cd_sel, $newForm->move_date_day_cd_sel, $newForm->move_date_year_cd_sel, $newForm->move_date_month_cd_sel, NULL);

                } elseif (isset($inForm)) {
                    $outForm = $this->_createOutForm($db, $inForm, NULL, Sgmov_View_Pre_Common::FUNC_INIT, $inForm->move_date_year_cd_sel, $inForm->move_date_month_cd_sel, $inForm->move_date_day_cd_sel, $inForm->move_date_year_cd_sel, $inForm->move_date_month_cd_sel, NULL);
                } else {
                    Sgmov_Component_Redirect::redirectPublicSsl('/pre/check_input');
                }

                $session->saveForm(self::SCRID_PRE, $outForm);

                return array('outForm' => $outForm);

            case Sgmov_View_Pre_Common::FUNC_CALLINK_DAY:
                if (!isset($postParam['calyear']) || !isset($postParam['calmonth']) || !isset($postParam['calday'])) {
                    // 不正遷移
                    Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_POST[\'param\']が不正です。');
                }
                // 指定範囲日付以内でない場合、不正遷移
//                $min = strtotime(date('Ymd', strtotime('+1 week')));
//                $max = strtotime(date('Ymd', strtotime('+6 month -1 days')));
//                $v = Sgmov_Component_Validator::createDateValidator($postParam['calyear'], $postParam['calmonth'], $postParam['calday'])->isDate($min, $max);
//                if (!$v->isValid()) {
//                    // 不正遷移
//                    Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_POST[\'param\']が不正です。');
//                }
                // セッション情報の取得
                $sessionForm = $session->loadForm(self::SCRID_PRE);
                $outForm = array();
                // 日付リンク押下による遷移
                if (isset($sessionForm)) {
                    $outForm = $this->_createOutForm($db, NULL, $sessionForm, Sgmov_View_Pre_Common::FUNC_CALLINK_DAY, NULL, NULL, NULL, $postParam['calyear'], $postParam['calmonth'], $postParam['calday']);
                } else {
                    Sgmov_Component_Redirect::redirectPublicSsl('/pre/check_input');
                }
                //$outForm = $this->_createOutForm($db, NULL, $sessionForm, Sgmov_View_Pre_Common::FUNC_CALLINK_DAY, NULL, NULL, NULL, $postParam['calyear'], $postParam['calmonth'], $postParam['calday']);
                $session->saveForm(self::SCRID_PRE, $outForm);
                
                ////////////////////////////////////////////////////////////////////
                // お引越し予定日 繁忙期:日付範囲チェック
                ///////////////////////////////////////////////////////////////////
                // セッション情報の取得
                $inForm = $session->loadForm(self::SCRID_PRE);

                $isMoveDateError = false;
                $errInfo = array();
                
                $min = strtotime(date('Ymd', strtotime('+1 week')));
                $max = strtotime(date('Ymd', strtotime('+2 month')));
                
                $v = Sgmov_Component_Validator::createDateValidator($postParam['calyear'], $postParam['calmonth'], $postParam['calday'])->isDate($min, $max);
                if (!$v->isValid()) {
                    $errInfo['isMoveDateError'] = true;
                    $resultMessageTop = str_replace('入力', '選択', $v->getResultMessageTop());
                    $errInfo['errorMessage'] = "ご希望のお引越し予定日には" . $resultMessageTop;
                } else {
                    
                    if ($inForm->raw_plan_cd_sel == '1' || $inForm->raw_plan_cd_sel == '2') { // 1:単身カーゴプランの場合 || 2:単身AIR CARGO プラン

                        // 入力チェック
                        $min2Date = date('Y/n/j', strtotime('2019-03-21 00:00:00'));
                        $max2Date = date('Y/n/j', strtotime('2019-03-31 23:59:59'));
                        $min2 = date('Y-m-d H:i:s', strtotime('2019-03-21 00:00:00'));
                        $max2 = date('Y-m-d H:i:s', strtotime('2019-03-31 23:59:59'));
                        $selectDate = date('Y-m-d H:i:s', 
                                strtotime("{$postParam['calyear']}-{$postParam['calmonth']}-{$postParam['calday']} 00:00:00"));

                        if ($min2 <= $selectDate && $selectDate <= $max2) {
                            $errInfo['isMoveDateError'] = true;
                            $errInfo['errorMessage'] = "お引越し予定日には{$min2Date}から{$max2Date}までの期間は選択できません。";
                        }
                    } else if ($inForm->raw_plan_cd_sel == '4' 
                            || $inForm->raw_plan_cd_sel == '3' 
                            || $inForm->raw_plan_cd_sel == '5') { // 4:まるごとおまかせプラン || 3:スタンダードプラン || 5:チャータープラン
                        // 入力チェック
                        $min2Date = date('Y/n/j', strtotime('2019-03-15 00:00:00'));
                        $max2Date = date('Y/n/j', strtotime('2019-04-08 23:59:59'));
                        $min2 = date('Y-m-d H:i:s', strtotime('2019-03-15 00:00:00'));
                        $max2 = date('Y-m-d H:i:s', strtotime('2019-04-08 23:59:59'));
                        $selectDate = date('Y-m-d H:i:s', 
                                strtotime("{$postParam['calyear']}-{$postParam['calmonth']}-{$postParam['calday']} 00:00:00"));

                        if ($min2 <= $selectDate && $selectDate <= $max2) {
                            $errInfo['isMoveDateError'] = true;
                            $errInfo['errorMessage'] = "ご希望のお引越し予定日には{$min2Date}から{$max2Date}までの期間は選択できません。";
                        }
                    }
                }
                
                return array('outForm' => $outForm, 'errInfo' => $errInfo);

            case Sgmov_View_Pre_Common::FUNC_CALLINK_MONTH:
                if (!isset($postParam['calyear']) || !isset($postParam['calmonth'])) {
                    // 不正遷移
                    Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_POST[\'param\']が不正です。');
                }
                // セッション情報の取得
                $sessionForm = $session->loadForm(self::SCRID_PRE);
                $outForm = array();
                // 先月・次月リンク押下による遷移
                if (isset($sessionForm)) {
                    $outForm = $this->_createOutForm($db, NULL, $sessionForm, Sgmov_View_Pre_Common::FUNC_CALLINK_MONTH, $sessionForm->raw_move_date_year_cd_sel, $sessionForm->raw_move_date_month_cd_sel, $sessionForm->raw_move_date_day_cd_sel, $postParam['calyear'], $postParam['calmonth'], NULL);
                } else {
                    Sgmov_Component_Redirect::redirectPublicSsl('/pre/check_input');
                }
                //$outForm = $this->_createOutForm($db, NULL, $sessionForm, Sgmov_View_Pre_Common::FUNC_CALLINK_MONTH, $sessionForm->raw_move_date_year_cd_sel, $sessionForm->raw_move_date_month_cd_sel, $sessionForm->raw_move_date_day_cd_sel, $postParam['calyear'], $postParam['calmonth'], NULL);
                $session->saveForm(self::SCRID_PRE, $outForm);

                ////////////////////////////////////////////////////////////////////
                // お引越し予定日 繁忙期:日付範囲チェック
                ///////////////////////////////////////////////////////////////////
                // セッション情報の取得
                $inForm = $session->loadForm(self::SCRID_PRE);
                
                $isMoveDateError = false;
                $errInfo = array();
                
                $min = strtotime(date('Ymd', strtotime('+1 week')));
                $max = strtotime(date('Ymd', strtotime('+2 month')));
                
                $v = Sgmov_Component_Validator::createDateValidator($inForm->raw_move_date_year_cd_sel, $inForm->raw_move_date_month_cd_sel, $inForm->raw_move_date_day_cd_sel)->isDate($min, $max);
                if (!$v->isValid()) {
                    $errInfo['isMoveDateError'] = true;
                    $resultMessageTop = str_replace('入力', '選択', $v->getResultMessageTop());
                    $errInfo['errorMessage'] = "ご希望のお引越し予定日には" . $resultMessageTop;
                } else {
                    if ($inForm->raw_plan_cd_sel == '1' || $inForm->raw_plan_cd_sel == '2') { // 1:単身カーゴプランの場合 || 2:単身AIR CARGO プラン

                        // 入力チェック
                        $min2Date = date('Y/n/j', strtotime('2019-03-21 00:00:00'));
                        $max2Date = date('Y/n/j', strtotime('2019-03-31 23:59:59'));
                        $min2 = date('Y-m-d H:i:s', strtotime('2019-03-21 00:00:00'));
                        $max2 = date('Y-m-d H:i:s', strtotime('2019-03-31 23:59:59'));
                        $selectDate = date('Y-m-d H:i:s', 
                                strtotime("{$inForm->raw_move_date_year_cd_sel}-{$inForm->raw_move_date_month_cd_sel}-{$inForm->raw_move_date_day_cd_sel} 00:00:00"));

                        if ($min2 <= $selectDate && $selectDate <= $max2) {
                            $errInfo['isMoveDateError'] = true;
                            $errInfo['errorMessage'] = "お引越し予定日には{$min2Date}から{$max2Date}までの期間は選択できません。";
                        }
                    } else if ($inForm->raw_plan_cd_sel == '4' 
                            || $inForm->raw_plan_cd_sel == '3' 
                            || $inForm->raw_plan_cd_sel == '5') { // 4:まるごとおまかせプラン || 3:スタンダードプラン || 5:チャータープラン
                        // 入力チェック
                        $min2Date = date('Y/n/j', strtotime('2019-03-15 00:00:00'));
                        $max2Date = date('Y/n/j', strtotime('2019-04-08 23:59:59'));
                        $min2 = date('Y-m-d H:i:s', strtotime('2019-03-15 00:00:00'));
                        $max2 = date('Y-m-d H:i:s', strtotime('2019-04-08 23:59:59'));
                        $selectDate = date('Y-m-d H:i:s', 
                                strtotime("{$inForm->raw_move_date_year_cd_sel}-{$inForm->raw_move_date_month_cd_sel}-{$inForm->raw_move_date_day_cd_sel} 00:00:00"));

                        if ($min2 <= $selectDate && $selectDate <= $max2) {
                            $errInfo['isMoveDateError'] = true;
                            $errInfo['errorMessage'] = "ご希望のお引越し予定日には{$min2Date}から{$max2Date}までの期間は選択できません。";
                        }
                    }
                }
                return array('outForm' => $outForm, 'errInfo' => $errInfo);
                
            case Sgmov_View_Pre_Common::FUNC_CALLINK_WEEK:
                if (!isset($postParam['calyear']) || !isset($postParam['calmonth'])) {
                    // 不正遷移
                    Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_POST[\'param\']が不正です。');
                }
                // セッション情報の取得
                $sessionForm = $session->loadForm(self::SCRID_PRE);
                // 先週・次週リンク押下による遷移
                if (isset($sessionForm)) {
                    $outForm = $this->_createOutForm($db, NULL, $sessionForm, Sgmov_View_Pre_Common::FUNC_CALLINK_WEEK, $sessionForm->raw_move_date_year_cd_sel, $sessionForm->raw_move_date_month_cd_sel, $sessionForm->raw_move_date_day_cd_sel, $postParam['calyear'], $postParam['calmonth'], $postParam['calday']);
                } else {
                    Sgmov_Component_Redirect::redirectPublicSsl('/pre/check_input');
                }
                $session->saveForm(self::SCRID_PRE, $outForm);

                return array('outForm' => $outForm);


            default:
                // 不正遷移
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_POST[\'param\']が不正です。');
                break;
        }
    }

    /**
     * クラス詰め替え
     *
     * @param Sgmov_Form_Pre002In $inForm
     * @return Sgmov_Form_Pre002Out
     */
    public function _replaceSession($inForm) {
        // クラス詰め替え
        $newForm = new Sgmov_Form_Pre002Out();

        // 全選択ボタン押下フラグ
        $newForm->all_sentakbtn_click_flag = $inForm->raw_all_sentakbtn_click_flag;
        // 入力画面初期表示時コースコード選択値
        $newForm->init_course_cd_sel = $inForm->raw_init_course_cd_sel;
        // 入力画面初期表示時プランコード選択値
        $newForm->init_plan_cd_sel = $inForm->raw_init_plan_cd_sel;
        // タイプコード
        $newForm->type_cd = $inForm->raw_type_cd;
        // コースコード選択値
        $newForm->course_cd_sel = $inForm->raw_course_cd_sel;
        // プランコード選択値
        $newForm->plan_cd_sel = $inForm->raw_plan_cd_sel;
        // エアコン有無フラグ選択値
        $newForm->aircon_exist_flag_sel = $inForm->raw_aircon_exist_flag_sel;

        //
        $newForm->menu_personal = $inForm->menu_personal;

        // 出発エリアコード選択値
        $newForm->from_area_cd_sel = $inForm->raw_from_area_cd_sel;
        // 到着エリアコード選択値
        $newForm->to_area_cd_sel = $inForm->raw_to_area_cd_sel;
        // 引越予定日年コード選択値
        $newForm->move_date_year_cd_sel = $inForm->raw_move_date_year_cd_sel;
        // 引越予定日月コード選択値
        $newForm->move_date_month_cd_sel = $inForm->raw_move_date_month_cd_sel;
        // 引越予定日日コード選択値
        $newForm->move_date_day_cd_sel = $inForm->raw_move_date_day_cd_sel;
        // 他社連携キャンペーンID
        $newForm->oc_id = $inForm->raw_oc_id;
        // 他社連携キャンペーン名称
        $newForm->oc_name = $inForm->raw_oc_name;
        // 他社連携キャンペーン内容
        $newForm->oc_content = $inForm->raw_oc_content;

        return $newForm;
    }

    /**
     * POSTパラメータを取得します。
     *
     * @param none
     * @return mode/calyear/calmonth/calday
     */
    public function _parseGetParameter() {

        $retParam = array();
        $keyArray = array(0, 1, 2, 'mode', 'calyear', 'calmonth', 'calday');

        if (!isset($_POST['param'])) {
            return array('mode' => 0);
        }

        $params = explode('/', $_POST['param']);
        if (count($params) > 7) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_POST[\'param\']が不正です。');
        }
        $count = count($params);
        for ($keyCnt = 3; $keyCnt < $count; ++$keyCnt) {
            $retParam[$keyArray[$keyCnt]] = $params[$keyCnt];
        }

        // パラメータチェック
        if (isset($retParam['calyear']) && !ctype_digit($retParam['calyear'])) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_POST[\'param\']が不正です。');
        }
        if (isset($retParam['calmonth']) && !ctype_digit($retParam['calmonth'])) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_POST[\'param\']が不正です。');
        }
        if (!isset($retParam['calday'])) {
            return $retParam;
        }
        if (!ctype_digit($retParam['calday'])) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_POST[\'param\']が不正です。');
        } elseif (isset($retParam['calyear']) && isset($retParam['calmonth'])) {
            $v = Sgmov_Component_Validator::createDateValidator($retParam['calyear'], $retParam['calmonth'], $retParam['calday']);
            if (!$v->isValid()) {
                // 不正遷移
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_POST[\'param\']が不正です。');
            }
        }

        return $retParam;
    }

    /**
     * POST情報から入力フォームを生成します。
     *
     * @param array $post ポスト情報
     * @return Sgmov_Form_Pre003In 入力フォーム
     */
    public function _createInFormFromPost($post) {

        $inForm = new Sgmov_Form_Pre002In();

        // タイプコード
        if (isset($post['type_cd'])) {
            $inForm->type_cd = $post['type_cd'];
        } else {
            $inForm->type_cd = 0;
        }
        // 全選択ボタン押下フラグ
        if (isset($post['all_sentakbtn_click_flag'])) {
            $inForm->all_sentakbtn_click_flag = $post['all_sentakbtn_click_flag'];
        } else {
            $inForm->all_sentakbtn_click_flag = 0;
        }
        if (isset($post['init_cource_cd'])) {
            // 入力画面初期表示時コースコード
            $inForm->init_course_cd_sel = $post['init_cource_cd'];
        }
        if (isset($post['init_plan_cd'])) {
            // 入力画面初期表示時プランコード
            $inForm->init_plan_cd_sel = $post['init_plan_cd'];
        }
        // コースコード
        if (isset($post['course_cd_sel'])) {
            $inForm->course_cd_sel = $post['course_cd_sel'];
        } else {
            $inForm->course_cd_sel = "";
        }
        // プランコード
        if (isset($post['plan_cd_sel'])) {
            $inForm->plan_cd_sel = $post['plan_cd_sel'];
        } else {
            $inForm->plan_cd_sel = "";
        }
        // エアコン取り付け・取り外し
        if (isset($post['aircon_exist_flag_sel'])) {
            $inForm->aircon_exist_flag_sel = $post['aircon_exist_flag_sel'];
        } else {
            $inForm->aircon_exist_flag_sel = "";
        }
        // 出発地域コード
        if (isset($post['from_area_cd_sel'])) {
            $inForm->from_area_cd_sel = $post['from_area_cd_sel'];
        } else {
            $inForm->from_area_cd_sel = "";
        }
        // 到着地域コード
        if (isset($post['to_area_cd_sel'])) {
            $inForm->to_area_cd_sel = $post['to_area_cd_sel'];
        } else {
            $inForm->to_area_cd_sel = "";
        }
        // 引越し予定日付（年）
        if (isset($post['move_date_year_cd_sel'])) {
            $inForm->move_date_year_cd_sel = $post['move_date_year_cd_sel'];
        } else {
            $inForm->move_date_year_cd_sel = "";
        }
        // 引越し予定日付（年）
        if (isset($post['move_date_month_cd_sel'])) {
            $inForm->move_date_month_cd_sel = $post['move_date_month_cd_sel'];
        } else {
            $inForm->move_date_month_cd_sel = "";
        }
        // 引越し予定日付（年）
        if (isset($post['move_date_day_cd_sel'])) {
            $inForm->move_date_day_cd_sel = $post['move_date_day_cd_sel'];
        } else {
            $inForm->move_date_day_cd_sel = "";
        }

        return $inForm;
    }

    /**
     * 見積り入力に問題ない場合の出力内容生成処理
     *
     * @param string $db DB接続情報
     * @param Sgmov_Form_Pre002In || Sgmov_Form_Pre002Out $inForm 入力情報
     * @param string $moveYear 引越し予定日付（年）
     * @param string $moveMonth 引越し予定日付（年）
     * @param string $moveDay 引越し予定日付（年）
     * @param string $calYear カレンダー日付（年）
     * @param string $calMonth カレンダー日付（月）
     * @param string $calDay カレンダー日付（日）
     * @return Sgmov_Form_Pre002Out 出力情報
     */
    public function _createOutForm($db, $inForm, $sessionForm, $mode, $moveYear, $moveMonth, $moveDay, $calYear, $calMonth, $calDay) {

        $outForm = new Sgmov_Form_Pre002Out();

        if ($mode == Sgmov_View_Pre_Common::FUNC_INIT) {
            // 見積り入力からの遷移時
            // タイプコード
            $outForm->raw_type_cd = $inForm->type_cd;
            // 全選択ボタン押下フラグ
            $outForm->raw_all_sentakbtn_click_flag = $inForm->all_sentakbtn_click_flag;
            // 全選択ボタン押下フラグ
            $outForm->raw_init_course_cd_sel = $inForm->init_course_cd_sel;
            // 全選択ボタン押下フラグ
            $outForm->raw_init_plan_cd_sel = $inForm->init_plan_cd_sel;
            // コースコード選択値
            $outForm->raw_course_cd_sel = $inForm->course_cd_sel;
            // プランコード選択値
            $outForm->raw_plan_cd_sel = $inForm->plan_cd_sel;
            // エアコン有無フラグ選択値
            $outForm->raw_aircon_exist_flag_sel = $inForm->aircon_exist_flag_sel;
            // 出発エリアコード選択値
            $outForm->raw_from_area_cd_sel = $inForm->from_area_cd_sel;
            // 到着エリアコード選択値
            $outForm->raw_to_area_cd_sel = $inForm->to_area_cd_sel;
            // 他社連携キャンペーンID
            $outForm->raw_oc_id = $inForm->raw_oc_id;
            // 他社連携キャンペーン名称
            $outForm->raw_oc_name = $inForm->raw_oc_name;
            // 他社連携キャンペーン内容
            $outForm->raw_oc_content = $inForm->raw_oc_content;
            //
            $outForm->menu_personal = $inForm->menu_personal;
        } else {

            // 日付リンク・前月/次月リンククリック時
            // タイプコード
            $outForm->raw_type_cd = $sessionForm->raw_type_cd;
            // 全選択ボタン押下フラグ
            $outForm->raw_all_sentakbtn_click_flag = $sessionForm->raw_all_sentakbtn_click_flag;
            // 全選択ボタン押下フラグ
            $outForm->raw_init_course_cd_sel = $sessionForm->raw_init_course_cd_sel;
            // 全選択ボタン押下フラグ
            $outForm->raw_init_plan_cd_sel = $sessionForm->raw_init_plan_cd_sel;
            // コースコード選択値
            $outForm->raw_course_cd_sel = $sessionForm->raw_course_cd_sel;
            // プランコード選択値
            $outForm->raw_plan_cd_sel = $sessionForm->raw_plan_cd_sel;
            // エアコン有無フラグ選択値
            $outForm->raw_aircon_exist_flag_sel = $sessionForm->raw_aircon_exist_flag_sel;
            // 出発エリアコード選択値
            $outForm->raw_from_area_cd_sel = $sessionForm->raw_from_area_cd_sel;
            // 到着エリアコード選択値
            $outForm->raw_to_area_cd_sel = $sessionForm->raw_to_area_cd_sel;
            // 他社連携キャンペーンID
            $outForm->raw_oc_id = $sessionForm->raw_oc_id;
            // 他社連携キャンペーン名称
            $outForm->raw_oc_name = $sessionForm->raw_oc_name;
            // 他社連携キャンペーン内容
            $outForm->raw_oc_content = $sessionForm->raw_oc_content;
            //
            $outForm->menu_personal = $sessionForm->raw_menu_personal;
        }

        // 閑散・繁忙期キャンペーン情報
        $kansanhanbou = array();

        // 特価キャンペーン情報
        $campaing = array();

        // 見積り情報取得用の日付を生成
        if ($mode == Sgmov_View_Pre_Common::FUNC_INIT || $mode == Sgmov_View_Pre_Common::FUNC_CALLINK_MONTH || $mode == Sgmov_View_Pre_Common::FUNC_CALLINK_WEEK) {
            $mitumoriTargetYmd = $moveYear . $moveMonth . $moveDay;
        } elseif ($mode == Sgmov_View_Pre_Common::FUNC_CALLINK_DAY) {
            $mitumoriTargetYmd = $calYear . $calMonth . $calDay;
        }

        // 基本料金
        $basePrice = null;
        // コースコード（名称）
        $courceNm = null;
        // プランコード（名称）
        $planNm = null;
        // 出発地域（名称）
        $fromAreaNm = null;
        // 到着地域（名称）
        $toAreaNm = null;

        if (!empty($mitumoriTargetYmd)) {
            // 見積り情報の取得（引越し予定日））
            $MitumoriResult = $this->fetchMitumoriResult($db, $mitumoriTargetYmd, $mitumoriTargetYmd, $outForm->raw_course_cd_sel, $outForm->raw_plan_cd_sel, $outForm->raw_from_area_cd_sel, $outForm->raw_to_area_cd_sel);
        }

        if (!empty($MitumoriResult)) {
            // 取得した見積り情報からコース名称や、閑散・繁忙期キャンペーン情報と特価キャンペーン情報を取得
            for ($i = 0; $i < $MitumoriResult['cnt']; ++$i) {
                if ($i == 0) {
                    $basePrice = $MitumoriResult['basePrice'][$i];
                    $campId = $MitumoriResult['campId'][$i];
                    $courceNm = $MitumoriResult['courceNm'][$i];
                    $planNm = $MitumoriResult['planNm'][$i];
                    $fromAreaNm = $MitumoriResult['fromAreaNm'][$i];
                    $toAreaNm = $MitumoriResult['toAreaNm'][$i];
                }

                if ($MitumoriResult['tokkaKbn'][$i] == Sgmov_View_CommonConst::TOKKA_KANSAN_HANBOUKI_RYOKINSETTEI) {
                    $kansanhanbou[] = array(
                        'title' => $MitumoriResult['title'][$i],
                        'price' => $MitumoriResult['sagaku'][$i]
                    );
                } else {
                    $campaing[] = array(
                        'title' => $MitumoriResult['title'][$i],
                        'price' => $MitumoriResult['sagaku'][$i]
                    );
                }
            }
        }

        // カレンダーの基本情報
        $calendarBasicInfo = $this->_setCalendarBasicValues($db, $outForm->raw_type_cd, $outForm->raw_course_cd_sel, $outForm->raw_plan_cd_sel, $outForm->raw_aircon_exist_flag_sel, $outForm->raw_from_area_cd_sel, $outForm->raw_to_area_cd_sel, $moveYear, $moveMonth, $moveDay, $calYear, $calMonth, $calDay);

        // カレンダー年月の見積り情報を取得する
        $calInfo = $this->_setCalendarPriceValues($db, $calendarBasicInfo['days'], $outForm->raw_course_cd_sel, $outForm->raw_plan_cd_sel, $outForm->raw_from_area_cd_sel, $outForm->raw_to_area_cd_sel);

        // カレンダー指定年月の見積り情報をもとに、日付ごとの見積り金額とキャンペーン実施フラグを取得する
        $calPriceAndCampInfo = $this->_getCalPriceAndCampInfo($calInfo);

        if (!empty($outForm->raw_course_cd_sel)
            && !empty($outForm->raw_plan_cd_sel)
            && !empty($outForm->raw_from_area_cd_sel)
            && !empty($outForm->raw_to_area_cd_sel)
        ) {
            // キャンペーン情報を取得する
	    $campains = Sgmov_Service_SpecialPrice::fetchSpecificCampain($db, $outForm->raw_course_cd_sel, $outForm->raw_plan_cd_sel, $outForm->raw_from_area_cd_sel, $outForm->raw_to_area_cd_sel);
            if (!empty($campains)) {
                // キャンペーン名リスト
                $outForm->raw_campaign_names = $campains['titles'];
                // キャンペーン内容リスト
                $outForm->raw_campaign_contents = $campains['descriptions'];
                // キャンペーン開始日リスト
                $outForm->raw_campaign_starts = $campains['mindates'];
                // キャンペーン終了日リスト
                $outForm->raw_campaign_ends = $campains['maxdates'];
            }
        }

        // 引越予定日年コード選択値
        $outForm->raw_move_date_year_cd_sel = substr($mitumoriTargetYmd, 0, 4);
        // 引越予定日月コード選択値
        $outForm->raw_move_date_month_cd_sel = substr($mitumoriTargetYmd, 4, 2);
        // 引越予定日日コード選択値
        $outForm->raw_move_date_day_cd_sel = substr($mitumoriTargetYmd, 6, 2);
        // コース（名称）
        $outForm->raw_course = $courceNm;
        // プラン（名称）
        $outForm->raw_plan = $planNm;
        // エアコン有無（名称）
        $outForm->raw_aircon_exist = $this->_getAirconKbnNm($outForm->raw_aircon_exist_flag_sel);

        // 出発エリア（名称）
        $outForm->raw_from_area = $fromAreaNm;
        // 到着エリア（名称）
        $outForm->raw_to_area = $toAreaNm;
        // 引越予定日
        $outForm->raw_move_date = substr($mitumoriTargetYmd, 0, 4) . '年' . substr($mitumoriTargetYmd, 4, 2) . '月' . substr($mitumoriTargetYmd, 6, 2) . '日';
        // 基本料金
        $outForm->raw_base_price = $this->_getBasePrice($basePrice, $kansanhanbou);
        // 割引キャンペーン情報（割引金額・タイトル）リスト
        $outForm->raw_discount_campaign_infos = $campaing;
        // 前月リンクアドレス
        $outForm->raw_prev_month_link = $calendarBasicInfo['prevmonthlink'];
        // 次月リンクアドレス
        $outForm->raw_next_month_link = $calendarBasicInfo['nextmonthlink'];

        // 前週リンクアドレス
        $outForm->raw_prev_week_link = $calendarBasicInfo['prevweeklink'];
        // 次週リンクアドレス
        $outForm->raw_next_week_link = $calendarBasicInfo['nextweeklink'];
        // スマホ版週表示開始日
        if ($mode == Sgmov_View_Pre_Common::FUNC_CALLINK_DAY) {
            $outForm->raw_start_week_day = NULL;
        } else {
            $outForm->raw_start_week_day = $calendarBasicInfo['startweekday'];
        }

        // カレンダー年
        $outForm->raw_cal_year = $calYear;
        // カレンダー月
        $outForm->raw_cal_month = $calMonth;
        // カレンダー日付リスト
        $outForm->raw_cal_days = $calendarBasicInfo['days'];
        // カレンダー祝日フラグリスト
        $outForm->raw_cal_holiday_flags = $calendarBasicInfo['holidayflags'];
        // カレンダーキャンペーンフラグリスト
        $outForm->raw_cal_campaign_flags = $calPriceAndCampInfo['CalCampList'];
        // カレンダー料金リスト
        $outForm->raw_cal_prices = $calPriceAndCampInfo['CalPriceList'];

        return $outForm;
    }

    /**
     * 出力フォームにカレンダーの基本情報を設定します。
     *
     * @param $db データベース接続情報
     * @param $typeId 選択中タイプコード
     * @param $courseId 選択中コースID
     * @param $planId 選択中プランID
     * @param $aircon 選択中エアコン取り付け・取り外し
     * @param $fromAreaId 選択中出発エリアID
     * @param $toAreaId 選択中到着エリアID
     * @param $moveYtiYear 選択中引越し予定日（年）
     * @param $moveYtiMonth 選択中引越し予定日（月）
     * @param $moveYtiDay 選択中引越し予定日（日）
     * @param $calYear カレンダー表示対象年(YYYY)
     * @param $calMonth カレンダー対象月(MM)
     * @return [days]日付文字列(YYYY-MM-DD)配列
     *         [weekdaycds]曜日コード配列 0(日曜)～6(土曜)
     *         [holidayflags]祝日フラグ配列
     *         [betweenflags]from <= 日付 <= to の場合'1'そうでない場合は'0'
     *         [prevmonthlink]前月リンク
     *         [nextmonthlink]次月リンク
     */
    public function _setCalendarBasicValues($db, $typeId, $courseId, $planId, $aircon, $fromAreaId, $toAreaId, $moveYtiYear, $moveYtiMonth, $moveYtiDay, $calYear, $calMonth, $calDay) {

        // 本日と半年後の日付を取得
        $min = mktime(0, 0, 0, date('n', time()), date('j', time()), date('Y', time()));
        $max = mktime(0, 0, 0, date('n', $min) + 6, date('j', $min) - 1, date('Y', $min));

        // 週が途中の場合前月・次月含む、カレンダーの範囲を取得
        $monthCalendarPeriod = $this->_calendarService->getMonthCalendarShowPeriod($calYear, $calMonth);
        $monthFrom = $monthCalendarPeriod['from'];
        $monthTo = $monthCalendarPeriod['to'];

        // 有効な開始終了日を取得（該当月の1日から末日の中で本日以降1年後以下の範囲のもの)
        $validFromDay = mktime(0, 0, 0, intval($calMonth), 1, intval($calYear));
        if ($validFromDay < $min) {
            $validFromDay = $min;
        }

        $validToDay = mktime(0, 0, 0, intval($calMonth) + 1, 0, intval($calYear));
        if ($validToDay > $max) {
            $validToDay = $max;
        }

        // 祝日を取得
        $holidays = $this->_calendarService->fetchHolidays($db, $monthFrom, $monthTo);

        // カレンダー情報を取得
        $calendar = $this->_calendarService->getBasicDateInfoDaily($monthFrom, $monthTo, $validFromDay, $validToDay, $holidays['holidays']);

        // 前月・次月リンク
        $prev_month_link = NULL;
        $next_month_link = NULL;
        $sep = Sgmov_Service_CoursePlan::ID_DELIMITER;
        $urlPrefix = '/pre/result/' . Sgmov_View_Pre_Common::FUNC_CALLINK_MONTH . '/';

        if ($validFromDay != $min) {
            $prevMonth = date('Ym', mktime(0, 0, 0, intval($calMonth) - 1, 1, intval($calYear)));
            $prev_month_link = $urlPrefix . substr($prevMonth, 0, 4) . '/' . substr($prevMonth, 4, 2);
        }
        if ($validToDay != $max) {
            $nextMonth = date('Ym', mktime(0, 0, 0, intval($calMonth) + 1, 1, intval($calYear)));
            $next_month_link = $urlPrefix . substr($nextMonth, 0, 4) . '/' . substr($nextMonth, 4, 2);
        }

        // 前週・次週リンク
        $caldays = $calendar['days'];
        $calYearSP = date('Y', strtotime($caldays[0]));
        $calMonthSP = date('m', strtotime($caldays[0]));
        $calDaySP = date('d', strtotime($caldays[0]));
        $startweekday = NULL;
        if (!empty($calDay)) {
            $calYearSP = $calYear;
            $calMonthSP = $calMonth;
            $calDaySP = $calDay;
            $startweekday = date('Ymd', mktime(0, 0, 0, intval($calMonthSP), intval($calDaySP), intval($calYearSP)));
        }

        return array(
            'days'          => $calendar['days'],
            'weekdaycds'    => $calendar['weekday_cds'],
            'holidayflags'  => $calendar['holiday_flags'],
            'betweenflags'  => $calendar['between_flags'],
            'prevmonthlink' => $prev_month_link,
            'nextmonthlink' => $next_month_link,
            'prevweeklink'  => null,
            'nextweeklink'  => null,
            'startweekday'  => $startweekday,
        );
    }

    /**
     * 出力フォームにカレンダーの特価・料金情報を設定します。
     *
     * @param $db データベース接続情報
     * @param $caldays 日付文字列(YYYY-MM-DD)配列
     * @param $courseId コースID
     * @param $planId プランID
     * @param $fromAreaId 出発エリアID
     * @param $toAreaId 到着エリアID
     * @param string $exclude_special_price_id [optional] 金額合計から除外する特価のID
     * @return 見積り金額（[targetYmd]対象年月日　[price]カレンダー表示見積り金額　[campFrg]キャンペーン中フラグ）
     */
    public function _setCalendarPriceValues($db, $caldays, $courseId, $planId, $fromAreaId, $toAreaId) {

        // 入力値から指定範囲日付分の見積り結果を取得する
        $this->_calStartYmd = date('Ymd', strtotime($caldays[0]));
        $this->_calEndYmd = date('Ymd', strtotime($caldays[count($caldays) - 1]));

        if (!empty($courseId) && !empty($planId) && !empty($fromAreaId) && !empty($toAreaId)) {
            // カレンダー日付範囲の見積り情報を取得する
            $MitumoriResult = $this->fetchMitumoriResult($db, $this->_calStartYmd, $this->_calEndYmd, $courseId, $planId, $fromAreaId, $toAreaId);
        }

        // カレンダー料金情報（対象日付・表示価格・キャンペーンフラグ）
        $calPriceInfo = array();

        // 初期化
        $campFrg = false;
        $kansanhanbou = array();
        $campaing = array();
        $price = 0;
        $targetYmd = $this->_calStartYmd;
        $basePrice = 0;
        $Waribikigo = 0;
        $cnt = 0;
        $endFlg = false;

        // その処置中日付の処理カウント
        $cnt = 0;

        if (!empty($MitumoriResult)) {
            // 見積り結果リストの要素数、以下を行う。
            for ($i = 0; $i < $MitumoriResult['cnt']; $i++) {
                // 基本料金を取得
                $basePrice = $MitumoriResult['basePrice'][$i];
                if ($targetYmd == date('Ymd', strtotime($MitumoriResult['targetdate'][$i]))) {

                    // 今回取得値をセット
                    // 対象日付の初回の場合、基本料金の取得
                    // キャンペーン特価合計
                    $price += $MitumoriResult['sagaku'][$i];
                    // キャンペーン実施フラグの設定
                    if ($MitumoriResult['tokkaKbn'][$i] == Sgmov_View_CommonConst::TOKKA_CAMPAIGNSETTEI) {
                        $campFrg = true;
                    }

                } else {
                    // 前回までの要素を返却値に格納する
                    $calPriceInfo[] = array('targetYmd' => $targetYmd, 'price' => ($basePrice + $price), 'campFrg' => $campFrg);

                    // 初期化
                    $price = 0;
                    $campFrg = false;

                    // 日付を+1日する
                    $targetYmd = $this->_getInqDay(1, substr($targetYmd, 0, 4), substr($targetYmd, 4, 2), substr($targetYmd, 6, 2));

                    // 今回処理日付が取得日付と等しくない間、以下を行う
                    while ($targetYmd != date('Ymd', strtotime($MitumoriResult['targetdate'][$i]))) {
                        // 今回処理日付/金額0/フラグ0を返却値に格納する
                        $calPriceInfo[] = array('targetYmd' => $targetYmd, 'price' => $basePrice, 'campFrg' => false);
                        // 日付を+1日する
                        $targetYmd = $this->_getInqDay(1, substr($targetYmd, 0, 4), substr($targetYmd, 4, 2), substr($targetYmd, 6, 2));
                        if ($targetYmd == $this->_calEndYmd) {
                            $endFlg = true;
                            break;
                        }
                    }

                    // 今回取得値をセット
                    // 対象日付の初回の場合、基本料金の取得
                    // 各種キャンペーン情報セット
                    $price += $MitumoriResult['sagaku'][$i];
                    if ($MitumoriResult['tokkaKbn'][$i] == Sgmov_View_CommonConst::TOKKA_CAMPAIGNSETTEI) {
                        $campFrg = true;
                    }

                    // 最終日付、かつ、最終要素の場合
                    if ($targetYmd == $this->_calEndYmd && $i == $MitumoriResult['cnt'] - 1) {
                        if (!$endFlg) {
                            $calPriceInfo[] = array('targetYmd' => $targetYmd, 'price' => ($basePrice + $price), 'campFrg' => $campFrg);
                        }
                    }
                }
            }
        }

        // 要素終了後、カレンダー範囲日付に達していない場合
        while ($targetYmd <= $this->_calEndYmd) {
            // 今回処理日付/金額0/フラグ0を返却値に格納する
            $calPriceInfo[] = array('targetYmd' => $targetYmd, 'price' => ($basePrice + $price), 'campFrg' => $campFrg);
            // 以降はキャンペーン情報なし
            $price = 0;
            $campFrg = false;
            // 日付を+1日する
            $targetYmd = $this->_getInqDay(1, substr($targetYmd, 0, 4), substr($targetYmd, 4, 2), substr($targetYmd, 6, 2));
        }

        return $calPriceInfo;
    }

    /**
     * 指定日付のn日後の日付を取得する
     *
     * @param string $inqDay 指定インクリメント日数
     * @param string $year    年
     * @param string $month    月
     * @param string $day    日
     * @return string    インクリメント後日付
     */
    function _getInqDay($inqDay, $year, $month, $day) {

        $inqDay = $inqDay * 24 * 60 * 60;
        $date_today = mktime(0, 0, 0, date($month), date($day), date($year));
        $date = $date_today + $inqDay;

        $date = getdate($date);

        $month = $date['mon'];
        $day = $date['mday'];

        if (strlen($month) == 1) {
            $month = '0' . $month;
        }
        if (strlen($day) == 1) {
            $day = '0' . $day;
        }
        return $date['year'] . $month . $day;
    }

    /**
     * エアコンの取り付け・取り外しの区分名称を返します。
     * 基本料金.基本料金 + 6300 + (閑散・繁忙期設定金額)
     *
     * @param aircon_exist_flag エアコンの取り付け・取り外し区分
     * @return str あり、なし
     */
    public function _getAirconKbnNm($aircon_exist_flag) {

        if ($aircon_exist_flag === '1') {
            return 'あり';
        } elseif ($aircon_exist_flag === '0') {
            return 'なし';
        }
        return '';
    }

    /**
     * カレンダー指定年月の見積り情報をもとに、日付ごとの見積り金額とキャンペーン実施フラグを返却します。
     *
     * @param $calInfo カレンダー指定年月見積り情報
     * @return array 日付ごとの見積り金額/日付ごとのキャンペーン実施フラグ
     */
    public function _getCalPriceAndCampInfo($calInfo) {

        // カレンダー指定年月キャンペーン実施フラグリスト
        $resFlg = array();

        // カレンダー指定年月金額リスト
        $resPrice = array();

        // 処理対象日付
        $tmpYmd = $this->_calStartYmd;

        // 処理対象終了日付
        $tmpEndYmd = $this->_calEndYmd;

        // 割引金額
        $tmpWrbkPrice = 0;

        // 見積り金額
        $tmpPrice = 0;

        // キャンペーンフラグ
        $tmpCampFlg = false;

        for ($res = 0; $res < count($calInfo); $res++) {

            if ($tmpYmd == $calInfo[$res]['targetYmd']) {
                // 前回処理日付と同日
                if ($calInfo[$res]['campFrg']) {
                    $tmpCampFlg = true;
                }
            } else {
                // 前回処理日付を異なる場合、返却要素に追加
                $resFlg[] = $tmpCampFlg;
                $resPrice[] = $tmpPrice;

                if ($calInfo[$res]['campFrg']) {
                    $tmpCampFlg = true;
                } else {
                    $tmpCampFlg = false;
                }

            }
            if ($calInfo[$res]['targetYmd'] == $tmpEndYmd && $res == count($calInfo) - 1) {
                if ($calInfo[$res]['campFrg']) {
                    $tmpCampFlg = true;
                }

                $resFlg[] = $tmpCampFlg;
                $resPrice[] = $tmpPrice;
            }

            $tmpYmd = $calInfo[$res]['targetYmd'];
            $tmpPrice = $calInfo[$res]['price'];

        }

        // カレンダー終了年月に到達する前に見積り情報が終了した場合
        if ($tmpYmd != $tmpEndYmd) {
            // 対象日付が終了日付と等しくなるまで、処理を行う
            while ($tmpYmd == $tmpEndYmd) {
                $resPrice[] = 0;
                $resFlg[] = 0;
                $targetYmd = $this->_getInqDay(1, substr($tmpYmd, 0, 4), substr($tmpYmd, 4, 2), substr($tmpYmd, 6, 2));
            }
        }

        return array('CalPriceList' => $resPrice, 'CalCampList' => $resFlg);

    }

    /**
     * 基本料金を算出します。
     * 基本料金.基本料金 + 6300 + (閑散・繁忙期設定金額)
     *
     * @param basePrice 基本料金
     * @param kansanhanbou 閑散・繁忙期の差額配列
     * @return basePrice 基本料金
     */
    public function _getBasePrice($basePrice, $kansanhanbou) {

        $basePrice += Sgmov_Service_AppCommon::WEBWARIBIKI;

        for ($i = 0; $i < count($kansanhanbou); $i++) {
            $basePrice += $kansanhanbou[$i]['price'];
        }

        return $basePrice;
    }

    /**
     * 画面に表示する見積り結果のキャンペーン情報リストhtmlを生成し、返却する。
     *
     * @param $base_price 基本料金
     * @param $campaigninfo キャンペーン情報
     * @return $html　キャンペーン情報リストhtml
     */
    public static function _createMitsumoriCampInfoHtml($base_price, $campaigninfo) {

        $retParam = array("", 0);
        $html = "";

        // 基本料金 - 6300円
        $Waribikigo = $base_price - Sgmov_Service_AppCommon::WEBWARIBIKI;

        // キャンペーン情報の数、以下を繰り返す
        for ($m = 0; $m < count($campaigninfo); $m++) {

            if ($campaigninfo[$m]['title'] !== '') {
                // キャンペーン値引き合計の加算
                $Waribikigo += $campaigninfo[$m]['price'];

                // 金額表示の整形
                $tmpCampPrice = 0;
                if (is_numeric($campaigninfo[$m]['price'])) {
                    $tmpCampPrice = $campaigninfo[$m]['price'];
                }

                // 値引きの場合は-を設定
                $subtraction = "";
                if (isset($tmpCampPrice) && $tmpCampPrice < 0) {
                    $subtraction = "-";
                }

                $html .= "<div align=\"right\">".$campaigninfo[$m]['title'].'　　&nbsp;'.$subtraction.'&nbsp;&yen;'.number_format(abs($tmpCampPrice))."</div>";
            }

        }

        if ($html === "") {
            $html = "-";
        }

        $retParam[0] = $html;
        $retParam[1] = $Waribikigo;

        return $retParam;
    }

    /**
     * 画面に表示するカレンダー部分のhtmlを生成し、返却する。
     *
     * @param $base_price 基本料金
     * @param $campaigninfo キャンペーン情報
     * @return $html　キャンペーン情報リストhtml
     */
    public static function _createCalendarHtml($calyear, $calmonth, $sntkYear, $sntkMonth, $sntkDay, $caldays, $calholidayflags, $calcampaignflags, $calprices, $calpricelinks, $prevmonthlink, $nextmonthlink) {

        $html = '';

        // TODO 2038年問題対応のため、dateTimeに変更する
        $now = new DateTime('now');
        // 本日より７日後の日付を取得
        $sevenAfterD = date('Ymd', strtotime('+7 day'));
        // 本日より半年後の日付を取得
        $sixAfterM = date('Ymd', mktime(0, 0, 0, $now->format('n') + 6, $now->format('j') - 1, $now->format('Y')));

        $priceHyojiFlag = false;

        // カレンダー日付範囲分、以下を行う
        $count = count($caldays);
        for ($m = 0; $m < $count; ++$m) {

            $html .= '                        <tr>' . PHP_EOL;
            for ($w = 0; $w < 7; ++$w) {

                // YYYYMMDD形式に変換
                $target = date('Ymd', strtotime($caldays[$m]));

                // 表示用日付
                $hyojiday = substr($target, 6, 2);

                $priceOut = '';
                // 対象日付が本日より7日後、かつ、半年後以内か確認
                if ($sevenAfterD <= $target && $target <= $sixAfterM) {
                    $priceOut = '<a href="' . $calpricelinks . '/' . $hyojiday . '">&yen;' . number_format($calprices[$m]) . '</a>';
                }

                $css = '';

                // 祝日/日曜日の場合は赤い文字に変更する
                if ($w === 6 || $calholidayflags[$m]) {
                    $css .= ' sun';
                } elseif ($w === 5) {
                    $css .= ' sat';
                }

                // 対象日がキャンペーン日かどうか
                if ($calcampaignflags[$m]) {
                    $css .= ' serviceDay';
                }

                // 選択日付
                if ($target === $sntkYear . $sntkMonth . $sntkDay && $calyear . $calmonth === $sntkYear . $sntkMonth) {
                    $css .= ' here';
                } elseif ($calmonth !== substr($target, 4, 2)) {
                	$priceOut = '';
                }

                if (!empty($css)) {
                    $html .= '                            <td class="' . trim($css) . '">' . PHP_EOL;
                } else {
                    $html .= '                            <td>' . PHP_EOL;
                }
                $html .= '                                ' . self::remZero($hyojiday) . PHP_EOL;
                $html .= '                                ' . $priceOut . PHP_EOL;
                $html .= '                            </td>' . PHP_EOL;

                // 日曜日で無ければ（週末のみ、ループ内とその外で二重インクリメントされてしまうので）
                if ($w !== 6) {
                    ++$m;
                }

            }
            $html .= '                        </tr>' . PHP_EOL;
        }

        return $html;
    }

    /**
     * 画面に表示するスマホ版カレンダー部分のhtmlを生成し、返却する。
     *
     * @param $base_price 基本料金
     * @param $campaigninfo キャンペーン情報
     * @return $html　キャンペーン情報リストhtml
     */
    public static function _createSPCalendarHtml($calyear, $calmonth, $sntkYear, $sntkMonth, $sntkDay, $caldays, $calholidayflags, $calcampaignflags, $calprices, $calpricelinks, $prevmonthlink, $nextmonthlink, $prevweeklink, $nextweeklink, $startweekday) {

        $html = '';

        // TODO 2038年問題対応のため、dateTimeに変更する
        $now = new DateTime('now');
        // 本日より７日後の日付を取得
        $sevenAfterD = date('Ymd', strtotime('+7 day'));
        // 本日より半年後の日付を取得
        $sixAfterM = date('Ymd', mktime(0, 0, 0, $now->format('n') + 6, $now->format('j') - 1, $now->format('Y')));

        $priceHyojiFlag = false;

        // スマホ版週表示開始日が設定されてい場合、選択日付を探す
        // なければカレンダー日付1週目を表示する
        $startCalIdx = 0;
        if (empty($startweekday)) {
            $searchDate = $sntkYear . $sntkMonth . $sntkDay;
        } else {
            $searchDate = $startweekday;
        }

        $calIdx = 0;
        $count = count($caldays);
        for ($m = 0; $m < $count; ++$m) {
            for ($w = 0; $w < 7; ++$w) {
                $target = date('Ymd', strtotime($caldays[$m]));

                // 選択日付
                if ($target == $searchDate) {
                    $startCalIdx = $calIdx;
                    break 2;
                }

                if ($w !== 6) {
                    ++$m;
                }
            }
            ++$calIdx;
        }

        $startCalIdx = $startCalIdx * 7;


        // 前週・次週リンクの判別
        $sep = Sgmov_Service_CoursePlan::ID_DELIMITER;
        $urlPrefix = '/pre/result/' . Sgmov_View_Pre_Common::FUNC_CALLINK_WEEK . '/';

        $startWeek = date('Ymd', strtotime($caldays[$startCalIdx]));
        $startWeekTime = mktime(0, 0, 0, intval(substr($startWeek, 4, 2)), intval(substr($startWeek, 6, 2)), intval(substr($startWeek, 0, 4)));
        $startWeek = date('Ymd', $startWeekTime);
        $startWeek_m = date('m', $startWeekTime);
        $startWeek_d = date('d', $startWeekTime);

        $prevWeekLink = date('Ymd', strtotime($caldays[$startCalIdx]));
        $prevWeekLink = date('Ymd', mktime(0, 0, 0, intval(substr($prevWeekLink, 4, 2)), intval(substr($prevWeekLink, 6, 2)) - 7, intval(substr($prevWeekLink, 0, 4))));
        $prevWeekLink_Y = date('Y', mktime(0, 0, 0, intval(substr($prevWeekLink, 4, 2)), intval(substr($prevWeekLink, 6, 2)), intval(substr($prevWeekLink, 0, 4))));
        $prevWeekLink_m = date('m', mktime(0, 0, 0, intval(substr($prevWeekLink, 4, 2)), intval(substr($prevWeekLink, 6, 2)), intval(substr($prevWeekLink, 0, 4))));

        if ($calmonth != $prevWeekLink_m) {
            if ($startCalIdx == 0 && $startWeek_d == '01') {
            } elseif ($startCalIdx <= 6) {
                $prevWeekLink = date('Ymd', strtotime($caldays[$startCalIdx]));
                $prevWeekLink = date('Ymd', mktime(0, 0, 0, intval(substr($prevWeekLink, 4, 2)), intval(substr($prevWeekLink, 6, 2)), intval(substr($prevWeekLink, 0, 4))));
            } else {
                $prevWeekLink = date('Ymd', mktime(0, 0, 0, intval($calmonth), 1, intval($calyear)));
            }
        }

        $nextWeekLink = date('Ymd', strtotime($caldays[$startCalIdx]));
        $nextWeekLink = date('Ymd', mktime(0, 0, 0, intval(substr($nextWeekLink, 4, 2)), intval(substr($nextWeekLink, 6, 2)) + 7, intval(substr($nextWeekLink, 0, 4))));
        $nextWeekLink_Y = date('Y', mktime(0, 0, 0, intval(substr($nextWeekLink, 4, 2)), intval(substr($nextWeekLink, 6, 2)), intval(substr($nextWeekLink, 0, 4))));
        $nextWeekLink_m = date('m', mktime(0, 0, 0, intval(substr($nextWeekLink, 4, 2)), intval(substr($nextWeekLink, 6, 2)), intval(substr($nextWeekLink, 0, 4))));
        if ($startWeek_m !== $nextWeekLink_m) {
            if ($calmonth == $nextWeekLink_m) {
                $nextWeekLink = date('Ymd', strtotime($caldays[$startCalIdx]));
                $nextWeekLink = date('Ymd', mktime(0, 0, 0, intval(substr($nextWeekLink, 4, 2)), intval(substr($nextWeekLink, 6, 2)) + 7, intval(substr($nextWeekLink, 0, 4))));
            } else {
                $nextWeekLink = date('Ymd', mktime(0, 0, 0, intval($nextWeekLink_m), 1, intval($nextWeekLink_Y)));
            }
        }

        $prevWeekLink = substr($prevWeekLink, 0, 4) . '/' . substr($prevWeekLink, 4, 2) . '/' . substr($prevWeekLink, 6, 2);
        $nextWeekLink = substr($nextWeekLink, 0, 4) . '/' . substr($nextWeekLink, 4, 2) . '/' . substr($nextWeekLink, 6, 2);
        if (!empty($prevWeekLink)) {
            $prevWeekLink = '<a class="back" href="' . $urlPrefix . $prevWeekLink . '">前の週へ</a>';
        }
        if (!empty($nextWeekLink)) {
            $nextWeekLink = '<a class="next" href="' . $urlPrefix . $nextWeekLink . '">次の週へ</a>';
        }

        // 対象日付が本日より7日後、かつ、半年後以内か確認
        if ($sevenAfterD > $startWeek) {
            $prevWeekLink = '';
        }
        $chkDate = date('Ymd', mktime(0, 0, 0, intval(substr($startWeek, 4, 2)), intval(substr($startWeek, 6, 2)) + 7, intval(substr($startWeek, 0, 4))));
        if ($chkDate > $sixAfterM) {
            if ($startWeek_m == $nextWeekLink_m) {
                $nextWeekLink = '';
            } elseif ($calmonth !== $nextWeekLink_m) {
                $nextWeekLink = '';
            }
        }

        // 対象日から1週間分以下の処理を行う
        for ($w = 0; $w < 7; ++$w) {

            $m = $startCalIdx + $w;

            // YYYYMMDD形式に変換
            $target = date('Ymd', strtotime($caldays[$m]));

            // 表示用日付
            $hyojiday = substr($target, 6, 2);

            $priceOut = '';
            // 対象日付が本日より7日後、かつ、半年後以内か確認
            if ($sevenAfterD <= $target && $target <= $sixAfterM) {
                $priceOut = '<a href="' . $calpricelinks . '/' . $hyojiday . '">&yen;' . number_format($calprices[$m]) . '</a>';
            }

            $sunday = '';
            // 祝日/日曜日の場合は赤い文字に変更する
            if ($w === 6 || $calholidayflags[$m]) {
                $sunday .= ' sun';
            } elseif ($w === 5) {
                $sunday .= ' sat';
            }

            // 対象日がキャンペーン日かどうか
            $css_camp = '';
            if ($calcampaignflags[$m]) {
                $css_camp = ' serviceDay';
            }

            $here = '';

            // 選択日付
            if ($target === $sntkYear . $sntkMonth . $sntkDay && $calyear . $calmonth === $sntkYear . $sntkMonth) {
                $here = ' here';
            } elseif ($calmonth !== substr($target, 4, 2)) {
            	$priceOut = '';
            }

            switch ($w) {
                case 0:
                    $wNm = '月';
                    break;
                case 1:
                    $wNm = '火';
                    break;
                case 2:
                    $wNm = '水';
                    break;
                case 3:
                    $wNm = '木';
                    break;
                case 4:
                    $wNm = '金';
                    break;
                case 5:
                    $wNm = '土';
                    break;
                case 6:
                    $wNm = '日';
                    break;
            }

            $html .= '                        <tr>' . PHP_EOL;
            $html .= '                            <th class="day' . $sunday . '" scope="row">' . $wNm . '</th>' . PHP_EOL;
            $html .= '                            <td class="date' . $sunday . $css_camp . '">' . self::remZero($hyojiday) . '</td>' . PHP_EOL;
            $html .= '                            <td class="fees' . $here . $css_camp . '">' . PHP_EOL;
            $html .= '                                ' . $priceOut . PHP_EOL;
            $html .= '                            </td>' . PHP_EOL;
            $html .= '                        </tr>' . PHP_EOL;
        }

        $htmlHead  = '                        <tr>' . PHP_EOL;
        $htmlHead .= '                            <th colspan="3" id="calendar_ttl">' . PHP_EOL;
        $htmlHead .= '                                ' . $prevWeekLink . PHP_EOL;
        $htmlHead .= '                                <span>' . $calyear . '</span>年' . PHP_EOL;
        $htmlHead .= '                                <span>' . $calmonth . '</span>月' . PHP_EOL;
        $htmlHead .= '                                ' . $nextWeekLink . '' . PHP_EOL;
        $htmlHead .= '                            </th>' . PHP_EOL;
        $htmlHead .= '                        </tr>' . PHP_EOL;

        return $htmlHead . $html;
    }

    /**
     * 画面に表示するキャンペーン情報リストhtmlを生成し、返却する。
     *
     * @param $base_price 基本料金
     * @param $campaigninfo キャンペーン情報
     * @return $html　キャンペーン情報リストhtml
     */
    public static function _createCampInfoHtml($camp_titles, $campaign_contents, $campaign_starts, $campaign_ends, $course, $plan, $from_area, $to_area) {

        $html = '';

        if (!empty($camp_titles)) {
            $html .= '            <div class="section">' . PHP_EOL;
            $html .= '                <h2 class="section_title">選択いただいた条件のキャンペーン情報</h2>' . PHP_EOL;
            $html .= '                <ul>' . PHP_EOL;
            $html .= '                    <li>' . PHP_EOL;
            $html .= '                        「'.$course."・".$plan."・".$from_area."から".$to_area."」を選んだお客様対象";
            $html .= '                    </li>' . PHP_EOL;
	    $count = count($camp_titles);
            for ($campCnt = 0; $campCnt < $count; ++$campCnt) {
                $html .= '                    <li>' . PHP_EOL;
                $html .= '                    </li>' . PHP_EOL;
                $html .= '                        ' . $camp_titles[$campCnt] . '（期間：' . date('Y年m月d日', strtotime($campaign_starts[$campCnt])) . " ～ " . date('Y年m月d日', strtotime($campaign_ends[$campCnt])) . 'まで）' . PHP_EOL;
                $html .= '                        <br />' . $campaign_contents[$campCnt] . PHP_EOL;
            }
            $html .= '                </ul>' . PHP_EOL;
            $html .= '            </div>' . PHP_EOL;
        }

        return $html;
    }

    /**
     * 1桁目が0の場合、それを除去して返します。
     *
     * @param string $str 対象文字列
     * @return string $str 処理後文字列
     */
    public static function remZero($str) {
        return ltrim($str, '0');
    }

    /**
     * Web割引金額を返します。（result.php用）
     *
     * @return Sgmov_Service_AppCommon Web割引金額
     */
    public static function _getWebWaribiki() {
        return Sgmov_Service_AppCommon::WEBWARIBIKI;
    }
}