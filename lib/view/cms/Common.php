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
Sgmov_Lib::useAllComponents();
Sgmov_Lib::useServices(array('Login', 'CommentData', 'Center'));
Sgmov_Lib::useView('Public');
/**#@-*/
/**
 * 概算見積り入力処理を管理する抽象クラスです。
 * @package    View
 * @subpackage Cms
 * @author     H.Tsuji(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Cms_Common extends Sgmov_View_Public {

	/**
     * ラジオボタン選択HTML
     */
    const CHECKED = 'checked="checked"';

    /**
     * 機能ID（CMS001）
     */
    const SCRID_CMS = 'CMS';

    /**
     * GETパラメーター：個人向けトップ
     */
    const GET_PARAM_MENU_PERSONAL = 'menu_personal';

    /**
     * GETパラメーター：お客様の声
     */
    const GET_PARAM_COMMENTS = 'comments';

    /**
     * GETパラメーター：この子に注目
     */
    const GET_PARAM_ATTENTION = 'attention';

    /**
     * 個人向けトップ用の機能ID
     */
    const FEATURE_ID_MENU_PERSONAL = 'CMM_MENU_PERSONAL';

    /**
     * お客様の声設定用の機能ID
     */
    const FEATURE_ID_COMMENTS = 'CMM_COMMENTS';

    /**
     * この子に注目用の機能ID
     */
    const FEATURE_ID_ATTENTION = 'CMM_ATTENTION';

    /**
     * 表示種別：個人向けページ
     */
    const SP_LIST_KIND_MENU_PERSONAL = '0';

    /**
     * 表示種別：お客様の声
     */
    const SP_LIST_KIND_COMMENTS = '1';

    /**
     * 表示種別：この子に注目
     */
    const SP_LIST_KIND_ATTENTION = '2';

    /**
     * ページングエリアのMAXリンク数（奇数を前提とする）
     */
    const PAGE_LINK_MAX = 5;

    /**
     * 機能ID
     * @var string
     */
    public $_featureId;

    /**
     * 表示種別
     * @var string
     */
    public $_sp_king;

    /**
     * POST値からチケットを取得します。
     * チケットが存在しない場合は空文字列を返します。
     * @return string チケット文字列
     */
    public function _getTicket() {
        if (!isset($_POST['ticket'])) {
            $ticket = '';
        } else {
            $ticket = $_POST['ticket'];
        }
        return $ticket;
    }

    /**
     * GETパラメータを取得します。
     *
     * @param none
     * @return type_cd/course_cd/plan_cd
     */
    public function _parseGetParameter() {
        if (!isset($_GET['param'])) {
            if (strstr($_SERVER['REQUEST_URI'], self::GET_PARAM_COMMENTS)) {
                $_GET['param'] = strstr($_SERVER['REQUEST_URI'], self::GET_PARAM_COMMENTS);
            } elseif (strstr($_SERVER['REQUEST_URI'], self::GET_PARAM_ATTENTION)) {
                $_GET['param'] = strstr($_SERVER['REQUEST_URI'], self::GET_PARAM_ATTENTION);
            } elseif (strstr($_SERVER['REQUEST_URI'], self::GET_PARAM_MENU_PERSONAL)) {
                $_GET['param'] = strstr($_SERVER['REQUEST_URI'], self::GET_PARAM_MENU_PERSONAL);
            }
        }

        $param = $_GET['param'];
        if ($param === self::GET_PARAM_COMMENTS || Sgmov_Component_String::startsWith($param, self::GET_PARAM_COMMENTS . '/')) {
            Sgmov_Component_Log::debug('お客様の声機能');
            $this->_featureId = self::FEATURE_ID_COMMENTS;
            $this->_sp_king   = self::SP_LIST_KIND_COMMENTS;
        } elseif ($param === self::GET_PARAM_ATTENTION || Sgmov_Component_String::startsWith($param, self::GET_PARAM_ATTENTION . '/')) {
            Sgmov_Component_Log::debug('この子に注目機能');
            $this->_featureId = self::FEATURE_ID_ATTENTION;
            $this->_sp_king   = self::SP_LIST_KIND_ATTENTION;
        } elseif ($param === self::GET_PARAM_MENU_PERSONAL || Sgmov_Component_String::startsWith($param, self::GET_PARAM_MENU_PERSONAL . '/')) {
            Sgmov_Component_Log::debug('個人向けページ');
            $this->_featureId = self::FEATURE_ID_MENU_PERSONAL;
            $this->_sp_king   = self::SP_LIST_KIND_MENU_PERSONAL;
        } else {
            Sgmov_Component_Log::debug('機能IDが不正：param=' . $param);
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
        }
    }

}