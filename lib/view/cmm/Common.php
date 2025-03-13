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
Sgmov_Lib::useServices(array('Login', 'Apartment', 'CommentData', 'Center'));
Sgmov_Lib::useView('Maintenance');
/**#@-*/

/**
 * マンションマスタメンテナンスの共通処理を管理する抽象クラスです。
 * @package    View
 * @subpackage AAP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Cmm_Common extends Sgmov_View_Maintenance {

    /**
     * CMM001の画面ID
     */
    const GAMEN_ID_CMM001 = 'CMM001';

    /**
     * CMM002の画面ID
     */
    const GAMEN_ID_CMM002 = 'CMM002';

    /**
     * CMM012の画面ID
     */
    const GAMEN_ID_CMM012 = 'CMM012';

    /**
     * お客様の声設定用の機能ID
     */
    const FEATURE_ID_COMMENTS = 'CMM_COMMENTS';

    /**
     * キャンペーン用の機能ID
     */
    const FEATURE_ID_ATTENTION = 'CMM_ATTENTION';

    /**
     * GETパラメーター：お客様の声
     */
    const GET_PARAM_COMMENTS = 'comments';

    /**
     * GETパラメーター：この子に注目
     */
    const GET_PARAM_ATTENTION = 'attention';

    /**
     * 表示種別：お客様の声
     */
    const SP_LIST_KIND_COMMENTS = '1';

    /**
     * 表示種別：この子に注目
     */
    const SP_LIST_KIND_ATTENTION = '2';

    /**
     * 機能ID
     * @var string
     */
    public $_featureId;

    /**
     * GETパラメータに基づいて機能IDを取得します。
     * @return string 機能ID
     */
    public function getFeatureId() {
        if (!isset($_GET['param'])) {
            if (strstr($_SERVER['REQUEST_URI'], self::GET_PARAM_COMMENTS)) {
                $_GET['param'] = strstr($_SERVER['REQUEST_URI'], self::GET_PARAM_COMMENTS);
            } elseif (strstr($_SERVER['REQUEST_URI'], self::GET_PARAM_ATTENTION)) {
                $_GET['param'] = strstr($_SERVER['REQUEST_URI'], self::GET_PARAM_ATTENTION);
            }
        }
        if (!isset($this->_featureId)) {
            if (!isset($_GET['param'])) {
                // 不正遷移
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_GET[\'param\']が未設定です。');
            }
            $param = $_GET['param'];
            if ($param === self::GET_PARAM_COMMENTS || Sgmov_Component_String::startsWith($param, self::GET_PARAM_COMMENTS . '/')) {
                Sgmov_Component_Log::debug('お客様の声機能');
                $this->_featureId = self::FEATURE_ID_COMMENTS;
            } elseif ($param === self::GET_PARAM_ATTENTION || Sgmov_Component_String::startsWith($param, self::GET_PARAM_ATTENTION . '/')) {
                Sgmov_Component_Log::debug('キャンペーン機能');
                $this->_featureId = self::FEATURE_ID_ATTENTION;
            } else {
                Sgmov_Component_Log::debug('機能IDが不正：param=' . $param);
                // 不正遷移
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
            }
        }
        return $this->_featureId;
    }
}