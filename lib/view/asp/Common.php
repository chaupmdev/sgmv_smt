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
Sgmov_Lib::useAllComponents();
Sgmov_Lib::useServices(array('Login'));
Sgmov_Lib::useView('Maintenance');
/**#@-*/

 /**
 * 特価メンテナンスの共通処理を管理する抽象クラスです。
 * @package    View
 * @subpackage ASP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Asp_Common extends Sgmov_View_Maintenance
{
    /**
     * ASP001の画面ID
     */
    const GAMEN_ID_ASP001 = 'ASP001';

    /**
     * ASP002の画面ID
     */
    const GAMEN_ID_ASP002 = 'ASP002';

    /**
     * ASP003の画面ID
     */
    const GAMEN_ID_ASP003 = 'ASP003';

    /**
     * ASP004の画面ID
     */
    const GAMEN_ID_ASP004 = 'ASP004';

    /**
     * ASP005の画面ID
     */
    const GAMEN_ID_ASP005 = 'ASP005';

    /**
     * ASP006の画面ID
     */
    const GAMEN_ID_ASP006 = 'ASP006';

    /**
     * ASP007の画面ID
     */
    const GAMEN_ID_ASP007 = 'ASP007';

    /**
     * ASP008の画面ID
     */
    const GAMEN_ID_ASP008 = 'ASP008';

    /**
     * ASP009の画面ID
     */
    const GAMEN_ID_ASP009 = 'ASP009';

    /**
     * ASP010の画面ID
     */
    const GAMEN_ID_ASP010 = 'ASP010';

    /**
     * ASP011の画面ID
     */
    const GAMEN_ID_ASP011 = 'ASP011';

    /**
     * ASP012の画面ID
     */
    const GAMEN_ID_ASP012 = 'ASP012';

    /**
     * ASP013の画面ID
     */
    const GAMEN_ID_ASP013 = 'ASP013';

    /**
     * ASP014の画面ID
     */
    const GAMEN_ID_ASP014 = 'ASP014';

    /**
     * 閑散繁忙期設定用の機能ID
     */
    const FEATURE_ID_EXTRA = 'ASP_EXTRA';

    /**
     * キャンペーン用の機能ID
     */
    const FEATURE_ID_CAMPAIGN = 'ASP_CAMPAIGN';

    /**
     * カレンダー用詳細画面の機能ID
     */
    const FEATURE_ID_CALDETAIL = 'ASP_CALDETAIL';

    /**
     * GETパラメーター：閑散繁忙期
     */
    const GET_PARAM_EXTRA = 'extra';

    /**
     * GETパラメーター：キャンペーン
     */
    const GET_PARAM_CAMPAIGN = 'campaign';

    /**
     * GETパラメーター：公開
     */
    const GET_PARAM_OPEN = 'open';

    /**
     * GETパラメーター：下書き
     */
    const GET_PARAM_DRAFT = 'draft';

    /**
     * GETパラメーター：終了
     */
    const GET_PARAM_CLOSE = 'close';

    /**
     * 特価一覧種別：閑散繁忙期
     */
    const SP_LIST_KIND_EXTRA = '1';

    /**
     * 特価一覧種別：キャンペーン
     */
    const SP_LIST_KIND_CAMPAIGN = '2';

    /**
     * 特価一覧表示モード：公開
     */
    const SP_LIST_VIEW_OPEN = '1';

    /**
     * 特価一覧表示モード：下書き
     */
    const SP_LIST_VIEW_DRAFT = '2';

    /**
     * 特価一覧表示モード：終了
     */
    const SP_LIST_VIEW_CLOSE = '3';

    /**
     * 特価一覧表示モード：公開
     */
    const SP_LIST_VIEW_OPEN_LABEL = '公開中';

    /**
     * 特価一覧表示モード：下書き
     */
    const SP_LIST_VIEW_DRAFT_LABEL = '下書き';

    /**
     * 特価一覧表示モード：終了
     */
    const SP_LIST_VIEW_CLOSE_LABEL = '終了';

    /**
     * 金額設定なし
     */
    const PRICESET_KBN_NONE = '1';

    /**
     * 金額一括設定あり
     */
    const PRICESET_KBN_ALL = '2';

    /**
     * 金額個別設定あり
     */
    const PRICESET_KBN_EACH = '3';

    /**
     * カーゴコースID
     */
    const COURCE_CARGO_ID = '1';

    /**
     * 単身カーゴプラン注意書き
     */
    const TANSHIN_CARGO_PLAN_COMMENT = '<font color=red>単身カーゴプランは沖縄が対象外のため、同プランを選択された場合、対象地域から沖縄が選択できません。</font>';
    
    /**
     * 単身エアカーゴプラン注意書き
     */
    const TANSHIN_AIRCARGO_PLAN_COMMENT = '<font color=red>単身AIR CARGOプランは、対象地域が限定されるため、単独での登録のみ可能です。</font>';
    
    /**
     * 機能IDはGETパラメーターによって決定します。
     * @var string
     */
    public $_featureId;

    /**
     * カーゴコースのコメント配列を返します。
     * @var array
     */
    public function getCourceComment()
    {
        return array(self::TANSHIN_CARGO_PLAN_COMMENT, self::TANSHIN_AIRCARGO_PLAN_COMMENT);
    }
    
    /**
     * GETパラメータに基づいて機能IDを取得します。
     * @return string 機能ID
     */
    public function getFeatureId()
    {
        if (!isset($this->_featureId)) {
            if (!isset($_GET['param'])) {
                // 不正遷移
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_GET[\'param\']が未設定です。');
            }
            $param = $_GET['param'];
            if ($param === self::GET_PARAM_EXTRA || Sgmov_Component_String::startsWith($param, self::GET_PARAM_EXTRA . '/')) {
                Sgmov_Component_Log::debug('閑散繁忙設定機能');
                $this->_featureId = self::FEATURE_ID_EXTRA;
            } else if ($param === self::GET_PARAM_CAMPAIGN || Sgmov_Component_String::startsWith($param, self::GET_PARAM_CAMPAIGN . '/')) {
                Sgmov_Component_Log::debug('キャンペーン機能');
                $this->_featureId = self::FEATURE_ID_CAMPAIGN;
            } else {
                Sgmov_Component_Log::debug('機能IDが不正：param=' . $param);
                // 不正遷移
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
            }
        }
        return $this->_featureId;
    }

    /**
     * 機能IDからGETパラメータを取得します。
     * @return string GETパラメータ
     */
    public function getFeatureGetParam()
    {
        $featureId = $this->getFeatureId();
        if ($featureId === self::FEATURE_ID_EXTRA) {
            return self::GET_PARAM_EXTRA;
        } else if ($featureId === self::FEATURE_ID_CAMPAIGN) {
            return self::GET_PARAM_CAMPAIGN;
        } else {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, '機能ID不整合');
        }
    }

    /**
     * 一覧に戻る用のリンクURLを生成します。
     * @param string $spListKind 特価一覧種別
     * @param string $spListViewMode 特価一覧表示モード
     * @return string 一覧に戻る用のリンクURL
     */
    public function createSpListUrl($spListKind, $spListViewMode)
    {
        $url = '/asp/list';
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

        return $url;
    }

    /**
     * 本社ユーザーフラグを取得します。
     * @return string '1':本社ユーザーである '0':本社ユーザーではない
     */
    public function getHonshaUserFlag()
    {
        $svc = new Sgmov_Service_Login();
        return $svc->getHonshaUserFlag();
    }

    /**
     * 閑散繁忙設定かキャンペーン設定かを表すフラグを返します。
     * @return string '1':閑散繁忙設定 '2':キャンペーン設定
     */
    public function getSpKind()
    {
        $featureId = $this->getFeatureId();
        if ($featureId === self::FEATURE_ID_EXTRA) {
            return '1';
        } else if ($featureId === self::FEATURE_ID_CAMPAIGN) {
            return '2';
        } else {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, '機能IDが不正です。');
        }
    }
}
?>
