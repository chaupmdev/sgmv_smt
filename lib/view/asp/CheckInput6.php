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
Sgmov_Lib::useServices(array('CoursePlan'));
Sgmov_Lib::useForms(array('Error', 'AspSession', 'Asp009In'));
/**#@-*/

 /**
 * 特価個別編集金額入力情報をチェックします。
 * @package    View
 * @subpackage ASP
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Asp_CheckInput6 extends Sgmov_View_Asp_Common
{

    /**
     * 処理を実行します。
     * <ol><li>
     * チケットの確認と破棄
     * </li><li>
     * 金額情報を取得
     * </li><li>
     * セッションに金額を適用
     * </li><li>
     * セッションの金額全体の入力チェック
     * </li><li>
     * 入力エラー有り:input6へリダイレクト
     * </li><li>
     * 入力エラー無し:
     *   <ol><li>
     *   金額設定区分をセッションに設定
     *   </li><li>
     *   ASP008の情報をセッションから削除
     *   </li><li>
     *   confirmへリダイレクト
     *   </li></ol>
     * </li></ol>
     */
    public function executeInner()
    {
        Sgmov_Component_Log::debug('チケットの確認と破棄');
        $session = Sgmov_Component_Session::get();
        $session->checkTicket($this->getFeatureId(), self::GAMEN_ID_ASP009, $this->_getTicket());

        /**
         * コード補完のためだけにドキュメントコメント使います。
         * @var Sgmov_Form_AspSession
         */
        $sessionForm = $session->loadForm($this->getFeatureId());

        Sgmov_Component_Log::debug('情報を取得');
        $inForm = $this->_createInFormFromPost($_POST, $sessionForm->asp009_in->to_area_sel_cds);

        Sgmov_Component_Log::debug('セッションに適用');
        $sessionForm = $this->_applyPricesToSession($sessionForm, $inForm);

        Sgmov_Component_Log::debug('セッションのASP009の入力チェック');
        $errorForm = $this->_validate($sessionForm->asp009_in);

        Sgmov_Component_Log::debug('情報をセッションに保存');
        $sessionForm->asp009_error = $errorForm;
        if ($errorForm->hasError()) {
            $sessionForm->asp009_status = self::VALIDATION_FAILED;
        } else {
            $sessionForm->asp009_status = self::VALIDATION_SUCCEEDED;
        }
        $session->saveForm($this->getFeatureId(), $sessionForm);

        if ($errorForm->hasError()) {
            $to = '/asp/input6/' . $this->getFeatureGetParam();
        } else {
            Sgmov_Component_Log::debug('金額設定区分をセッションに設定');
            $sessionForm->priceset_kbn = self::PRICESET_KBN_EACH;

            Sgmov_Component_Log::debug('ASP008の情報をセッションから削除');
            $sessionForm->asp008_in = NULL;
            $sessionForm->asp008_error = NULL;
            $sessionForm->asp008_status = NULL;
            $session->saveForm($this->getFeatureId(), $sessionForm);

            $to = '/asp/confirm/' . $this->getFeatureGetParam();
        }
        Sgmov_Component_Log::debug('リダイレクト ' . $to);
        Sgmov_Component_Redirect::redirectMaintenance($to);
    }

    /**
     * POST値からチケットを取得します。
     * チケットが存在しない場合は空文字列を返します。
     * @return string チケット文字列
     */
    public function _getTicket()
    {
        if (!isset($_POST['ticket'])) {
            $ticket = '';
        } else {
            $ticket = $_POST['ticket'];
        }
        return $ticket;
    }

    /**
     * POST情報から入力フォームを生成します。
     * @param array $post ポスト情報
     * @param array $toAreaCds 到着エリアコード(システムエラーのチェックに使用)
     * @return Sgmov_Form_Asp009In 入力フォーム
     */
    public function _createInFormFromPost($post, $toAreaCds)
    {
        if (isset($post['sp_setting_charges'])) {
            $inForm = new Sgmov_Form_Asp009In();
            $inForm->sp_setting_charges = $post['sp_setting_charges'];

            // 通常の入力ではありえない値の場合はシステムエラー
            $count = count($inForm->sp_setting_charges);
            if ($count != count($toAreaCds)) {
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT, '差額一覧と到着エリア一覧の数が一致していません。');
            }
            // この段階で数値や有効な値である必要はない。全ての項目が文字列であることを確認しておく
            for ($i = 0; $i < $count; $i++) {
                if (!is_string($inForm->sp_setting_charges[$i])) {
                    Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT, '差額に文字列以外の情報が入力されました。');
                }
            }

            return $inForm;
        } else {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT, '差額一覧が設定されていません。');
        }
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
        Sgmov_Component_Log::debug('金額適用');
        $coursePlanCd = $sessionForm->asp009_in->cur_course_plan_cd;
        $fromAreaCd = $sessionForm->asp009_in->cur_from_area_cd;

        $coursePlanAreaCd = $this->_createCoursePlanAreaCd($coursePlanCd, $fromAreaCd);
        $sessionForm->asp009_in->all_charges[$coursePlanAreaCd] = $inForm->sp_setting_charges;
        return $sessionForm;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_Asp009In $asp009 入力フォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($asp009)
    {
        $errorForm = new Sgmov_Form_Error();
        foreach ($asp009->course_plan_sel_cds as $coursePlanCd) {
            foreach ($asp009->from_area_sel_cds as $fromAreaCd) {
                // 入力チェック
                $coursePlanAreaCd = $this->_createCoursePlanAreaCd($coursePlanCd, $fromAreaCd);
                $count = count($asp009->all_charges[$coursePlanAreaCd]);
                $hasEmpty = FALSE;
                $hasNotInteger = FALSE;
                $hasZero = FALSE;
                for ($i = 0; $i < $count; $i++) {
                    $charge = $asp009->all_charges[$coursePlanAreaCd][$i];

                    $v = Sgmov_Component_Validator::createSingleValueValidator($charge);

                    $v->isNotEmpty();
                    if (!$v->isValid()) {
                        $hasEmpty = TRUE;
                        $errorForm->addError("item_{$coursePlanAreaCd}_{$i}", '');
                        continue;
                    }

                    $v->isInteger();
                    if (!$v->isValid()) {
                        $hasNotInteger = TRUE;
                        $errorForm->addError("item_{$coursePlanAreaCd}_{$i}", '');
                        continue;
                    }

                    // 数値文字列として保存しなおす(先頭の0を除去)
                    $asp009->all_charges[$coursePlanAreaCd][$i] = (string) intval($charge);
                    if ($asp009->all_charges[$coursePlanAreaCd][$i] === '0') {
                        $hasZero = TRUE;
                        $errorForm->addError("item_{$coursePlanAreaCd}_{$i}", '');
                        continue;
                    }
                }

                if ($hasEmpty || $hasNotInteger || $hasZero) {
                    // コースプラン名・出発エリア名はテンプレート側で指定。
                    $temp = '';
                    if ($hasEmpty) {
                        $temp .= "未入力の項目";
                    }
                    if ($hasNotInteger) {
                        if (! empty($temp)) {
                            $temp .= "、";
                        }
                        $temp .= "数値ではない項目";
                    }
                    if ($hasZero) {
                        if (! empty($temp)) {
                            $temp .= "、";
                        }
                        $temp .= "値が0の項目";
                    }
                    $message = "に{$temp}があります。";
                    $errorForm->addError('top_' . $coursePlanAreaCd, $message);
                }
            }
        }
        return $errorForm;
    }
}
?>
