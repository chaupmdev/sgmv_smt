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
//Sgmov_Lib::useServices(array('Calendar'));
Sgmov_Lib::useForms(array('Pre003In', 'PveSession', 'Pre002Out'));
/**#@-*/
/**
 * 概算見積り結果をセッションに格納し、訪問見積り画面を表示します。
 * @package    View
 * @subpackage PRE
 * @author     H.Tsuji(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pre_Topve extends Sgmov_View_Pre_Common {

	/**
     * 共通サービス
     * @var Sgmov_Service_AppCommon
     */
    public $_appCommon;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_appCommon = new Sgmov_Service_AppCommon();
    }

    /**
     * 処理を実行します。
     *
     */
    public function executeInner() {

        $session = Sgmov_Component_Session::get();

        $session->deleteForm(self::FEATURE_ID);

        // セッション情報の取得
        $fromPreForm = $session->loadForm(self::SCRID_PRE);

        // POST値を取得
        $inForm = $this->_createInFormFromPost($fromPreForm);

        // DB接続
        $db = Sgmov_Component_DB::getPublic();

        // フォームの生成
        $toPveForm = $this->_createMitsumoriInfo($db, $inForm);

        // 各種見積り結果をセッション情報に格納する
        $session->saveForm(self::SCRID_TOPVE, $toPveForm);

        Sgmov_Component_Redirect::redirectPublicSsl('/pve/input');
    }

    /**
     * POST情報から入力フォームを生成します。
     *
     * 全ての値は正規化されてフォームに設定されます。
     *
     * @param Sgmov_Form_Pre002In $fromPreForm ポスト情報
     * @return Sgmov_Form_Pre003In 入力フォーム
     */
    public function _createInFormFromPost($fromPreForm) {
        Sgmov_Component_Log::debug("_createInFormFromPost Start");
        $inForm = new Sgmov_Form_Pre003In();

        // コースコード
        $inForm->course_cd_sel = $fromPreForm->raw_course_cd_sel;
        // プランコード
        $inForm->plan_cd_sel = $fromPreForm->raw_plan_cd_sel;
        // エアコン取り付け・取り外し
        $inForm->aircon_exist_flag_sel = $fromPreForm->raw_aircon_exist_flag_sel;

        // 個人向けサービス ページから 入力されたメニュー
        $inForm->menu_personal = $fromPreForm->menu_personal;

        // 出発地域コード
        $inForm->from_area_cd_sel = $fromPreForm->raw_from_area_cd_sel;
        // 到着地域コード
        $inForm->to_area_cd_sel = $fromPreForm->raw_to_area_cd_sel;
        // 引越し予定日付（年）
        $inForm->move_date_year_cd_sel = $fromPreForm->raw_move_date_year_cd_sel;
        // 引越し予定日付（年）
        $inForm->move_date_month_cd_sel = $fromPreForm->raw_move_date_month_cd_sel;
        // 引越し予定日付（年）
        $inForm->move_date_day_cd_sel = $fromPreForm->raw_move_date_day_cd_sel;
        // 他社連携キャンペーンID
        $inForm->oc_id = $fromPreForm->raw_oc_id;
        // 他社連携キャンペーン名称
        $inForm->oc_name = $fromPreForm->raw_oc_name;
        // 他社連携キャンペーン内容
        $inForm->oc_content = $fromPreForm->raw_oc_content;

        Sgmov_Component_Log::debug("_createInFormFromPost End");
        return $inForm;
    }

}