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
Sgmov_Lib::useServices(array('Login', 'Calendar', 'CenterArea', 'SpecialPrice'));
Sgmov_Lib::useForms(array('Error', 'Asp001Out'));
/**#@-*/

 /**
 * 特価一覧画面を表示します。
 * @package    View
 * @subpackage ASP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Asp_List extends Sgmov_View_Asp_Common
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
        $this->_centerAreaService = new Sgmov_Service_CenterArea();
        $this->_specialPriceService = new Sgmov_Service_SpecialPrice();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * セッション情報の削除
     * </li><li>
     * GETパラメーターのチェック
     * </li><li>
     * GETパラメーターを元に出力情報を生成
     * </li></ol>
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['outForm']:出力フォーム
     * </li></ul>
     */
    public function executeInner()
    {
        Sgmov_Component_Log::debug('セッション情報の削除');
        Sgmov_Component_Session::get()->deleteForm($this->getFeatureId());

        Sgmov_Component_Log::debug('GETパラメーターのチェック');
        $params = $this->_parseGetParameter();
        $listModeCd = $params['listModeCd'];

        Sgmov_Component_Log::debug('GETパラメーターを元に出力情報を生成');
        $outForm = $this->_createOutForm($listModeCd);

        return array('outForm'=>$outForm);
    }

    /**
     * GETパラメータから特価IDを取得します。
     *
     * [パラメータ]
     * <ol><li>
     * 'open' or 'draft' or 'close' または未指定
     * </li></ol>
     * 未指定の場合は'open'を返します。
     *
     * [例]
     * <ul><li>
     * /asp/list/campaign
     * </li><li>
     * /asp/list/campaign/close
     * </li></ul>
     * @return array
     * ['listModeCd']:一覧表示画面の表示モードコード
     */
    public function _parseGetParameter()
    {
        if (!isset($_GET['param'])) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_GET[\'param\']が未設定です。');
        }

        $params = explode('/', $_GET['param'], 2);
        $paramCount = count($params);
        if ($paramCount === 1) {
            $listModeCd = self::SP_LIST_VIEW_OPEN;
        } else {
            $listModeCd = $params[1];
            if ($params[1] === self::GET_PARAM_OPEN) {
                $listModeCd = self::SP_LIST_VIEW_OPEN;
            } else if ($params[1] === self::GET_PARAM_DRAFT) {
                $listModeCd = self::SP_LIST_VIEW_DRAFT;
            } else if ($params[1] === self::GET_PARAM_CLOSE) {
                $listModeCd = self::SP_LIST_VIEW_CLOSE;
            } else {
                // 不正遷移
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_GET[\'param\']が不正です。');
            }
        }
        return array('listModeCd'=>$listModeCd);
    }

    /**
     * GETパラメーターを元に出力情報を生成します。
     * @param string $listModeCd 一覧画面の表示モード
     * @return array
     * ['outForm'] 出力フォーム
     * ['errorForm'] エラーフォーム
     */
    public function _createOutForm($listModeCd)
    {
        $outForm = new Sgmov_Form_Asp001Out();

        $db = Sgmov_Component_DB::getAdmin();

        // 基本情報の設定
        $this->_setBasicInfo($outForm, $listModeCd);

        // 必要なマスターの取得
        $masters = $this->_getMasters($db);

        // 特価の取得
        $spInfos = $this->_specialPriceService->
                        fetchSpecialPricesByStatus($db, $outForm->raw_sp_list_kind, $outForm->raw_sp_list_view_mode);
        foreach ($spInfos as $spInfo) {
            $fromAreaIds = $this->_specialPriceService->
                                fetchFromAreasSpecialPricesById($db, $spInfo['id']);
            // 特価情報の設定
            $this->_setSpInfo($outForm, $spInfo, $fromAreaIds, $masters);
        }
        return $outForm;
    }

    /**
     * 出力フォームに基本情報を設定します。
     * @param Sgmov_Form_Asp001Out $outForm 出力フォーム
     * @param string $listModeCd 一覧画面表示モード
     */
    public function _setBasicInfo($outForm, $listModeCd)
    {
        // 本社ユーザーかどうか
        $outForm->raw_honsha_user_flag = $this->getHonshaUserFlag();
        // 一覧画面用
        $outForm->raw_sp_list_kind = $this->getSpKind();
        $outForm->raw_sp_list_view_mode = $listModeCd;
    }

    /**
     * 出力情報の生成に必要となるマスター情報を取得します。
     * @param Sgmov_Component_DB $db DB接続
     * @return array
     * ['fromAreaIds'] 出発エリアIDの配列
     * ['fromAreaNames'] 出発エリア名の配列
     */
    public function _getMasters($db)
    {
        // マスタ情報の取得
        // 出発エリア(先頭の空白は除去)
        $fromAreaList = $this->_centerAreaService->
                                fetchFromAreaList($db);
        $fromAreaIds = $fromAreaList['ids'];
        array_shift($fromAreaIds);
        $fromAreaNames = $fromAreaList['names'];
        array_shift($fromAreaNames);
        return array('fromAreaIds'=>$fromAreaIds,
                         'fromAreaNames'=>$fromAreaNames);
    }

    /**
     * 出力フォームに特価の基本情報を設定します。
     * @param Sgmov_Form_Asp001Out $outForm 出力フォーム
     * @param array $spInfo 特価情報
     * @param array $fromAreaIds 特価情報に紐付く出発エリア情報
     * @param array $masters マスター情報
     */
    public function _setSpInfo($outForm, $spInfo, $fromAreaIds, $masters)
    {
        // 担当拠点の場合に編集可能
        if ($spInfo['center_id'] === $this->_loginService->getLoginUser()->centerId) {
            $outForm->raw_sp_charge_flags[] = '1';
        } else {
            $outForm->raw_sp_charge_flags[] = '0';
        }

        // 特価内容
        $outForm->raw_sp_cds[] = $spInfo['id'];
        $outForm->raw_sp_created_dates[] = $this->_getDateStringToViewString($spInfo['created_day']);
        $outForm->raw_sp_charge_centers[] = $spInfo['center_name'];
        $outForm->raw_sp_names[] = $spInfo['title'];
        $outForm->raw_sp_periods[] = $this->_getPeriodString($spInfo['min_date'], $spInfo['max_date']);
        // 出発エリア
        $outForm->raw_sp_from_areas[] = $this->_getAreaString($fromAreaIds, $masters['fromAreaIds'], $masters['fromAreaNames']);
        $outForm->raw_sp_detail_urls[] = $this->_createSpDetailUrl($outForm->raw_sp_list_kind, $outForm->raw_sp_list_view_mode, $spInfo['id']);
    }

    /**
     * 詳細画面のURLを生成します。
     * @param string $spListKind 特価一覧種別
     * @param string $spListViewMode 特価一覧表示モード
     * @param string $spCd 特価ID
     * @return string 詳細画面のURL
     */
    public function _createSpDetailUrl($spListKind, $spListViewMode, $spCd)
    {
        $url = '/asp/detail';
        if ($spListKind === self::SP_LIST_KIND_EXTRA) {
            $url .= '/' . self::GET_PARAM_EXTRA;
        } else if ($spListKind === self::SP_LIST_KIND_CAMPAIGN) {
            $url .= '/' . self::GET_PARAM_CAMPAIGN;
        } else {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, '特価一覧種別が不正です。');
        }

        if ($spListViewMode === self::SP_LIST_VIEW_OPEN) {
            $url .= '/' . self::GET_PARAM_OPEN;
        } else if ($spListViewMode === self::SP_LIST_VIEW_DRAFT) {
            $url .= '/' . self::GET_PARAM_DRAFT;
        } else if ($spListViewMode === self::SP_LIST_VIEW_CLOSE) {
            $url .= '/' . self::GET_PARAM_CLOSE;
        } else {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, '特価一覧表示モードが不正です。');
        }

        $url .= '/' . $spCd;

        return $url;
    }

    /**
     * エリア文字列を生成します。
     *
     * 全てのエリアと一致する場合は"全国"を返します。
     *
     * @param array $areaSelCds 対象エリアコードリスト
     * @param array $allAreaCds 到着エリアコードリスト
     * @param array $allAreaLbls 到着エリア名称リスト
     * @return string 到着エリア文字列
     */
    public function _getAreaString($areaSelCds, $allAreaCds, $allAreaLbls)
    {
        if (count(array_diff($allAreaCds, $areaSelCds)) == 0) {
            return '全国';
        }

        $delim = '、';
        $ret = '';
        foreach ($areaSelCds as $cd) {
            $key = array_search($cd, $allAreaCds, TRUE);
            if ($key === FALSE) {
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, 'データ不整合');
            }

            if (! empty($ret)) {
                $ret .= $delim;
            }
            $ret .= $allAreaLbls[$key];
        }

        return $ret;
    }

    /**
     * 期間文字列を生成します。
     *
     * 日付文字列は"YYYY-MM-DD"の形式であることを前提としています。
     *
     * @param string $fromStr 開始日
     * @param string $toStr 終了日
     * @return string 期間文字列
     */
    public function _getPeriodString($fromStr, $toStr)
    {
        return $this->_getDateStringToViewString($fromStr) . '～' . $this->_getDateStringToViewString($toStr);
    }

    /**
     * "YYYY-MM-DD"から"YYYY/MM/DD"に変換します。
     *
     * @param string $dateStr 日付文字列
     * @return string 表示用文字列
     */
    public function _getDateStringToViewString($dateStr)
    {
        $splits = explode(Sgmov_Service_Calendar::DATE_SEPARATOR, $dateStr, 3);
        return "{$splits[0]}/{$splits[1]}/{$splits[2]}";
    }
}
?>
