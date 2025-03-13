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
Sgmov_Lib::useForms(array('Error', 'AcfSession', 'Acf002In'));
/**#@-*/

 /**
 * 料金マスタメンテナンス入力情報をチェックします。
 * @package    View
 * @subpackage ACF
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Acf_CheckInput extends Sgmov_View_Acf_Common
{
    /**
     * 処理を実行します。
     * <ol><li>
     * チケットの確認と破棄
     * </li><li>
     * 情報をセッションに保存
     * </li><li>
     * 入力チェック
     * </li><li>
     * 入力エラー無し
     *   <ol><li>
     *   pin/confirm へリダイレクト
     *   </li></ol>
     * </li><li>
     * 入力エラー有り
     *   <ol><li>
     *   pin/input へリダイレクト
     *   </li></ol>
     * </li></ol>
     */
    public function executeInner()
    {
        Sgmov_Component_Log::debug('チケットの確認と破棄');
        $session = Sgmov_Component_Session::get();
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_ACF002, $this->_getTicket());

        Sgmov_Component_Log::debug('情報を取得');
        $inForm = $this->_createInFormFromPost($_POST);
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        $sessionForm->base_prices = $inForm->base_prices;
		$sessionForm->max_prices = $inForm->max_prices;
        $sessionForm->min_prices = $inForm->min_prices;

        Sgmov_Component_Log::debug('入力チェック');
        $errorForm = $this->_validate($sessionForm);

        Sgmov_Component_Log::debug('セッション保存');
        $sessionForm->error = $errorForm;
        if ($errorForm->hasError()) {
            $sessionForm->status = self::VALIDATION_FAILED;
        } else {
            $sessionForm->status = self::VALIDATION_SUCCEEDED;
        }
        $session->saveForm(self::FEATURE_ID, $sessionForm);

        // リダイレクト
        if ($errorForm->hasError()) {
            Sgmov_Component_Log::debug('リダイレクト /acf/input');
            Sgmov_Component_Redirect::redirectMaintenance('/acf/input');
        } else {
            Sgmov_Component_Log::debug('リダイレクト /acf/confirm');
            Sgmov_Component_Redirect::redirectMaintenance('/acf/confirm');
        }
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
     * @return Sgmov_Form_Acf002In 入力フォーム
     */
    public function _createInFormFromPost($post)
    {
        $inForm = new Sgmov_Form_Acf002In();
        //$inForm->from_area_cd_sel = $post['from_area_cd_sel'];
        $inForm->from_area_cd_sel = $post['formareacd'];
        $inForm->to_area_cds = $post['to_area_cds'];
        $inForm->max_prices = $post['max_prices'];
        $inForm->base_prices = $post['base_prices'];
        $inForm->min_prices = $post['min_prices'];
        $inForm->course_plan_cd_sel = $post['course_plan_cd_sel'];
        return $inForm;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_AcfSession $sessionForm セッションフォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($sessionForm)
    {
        // 入力チェック
        // エラーメッセージのキーは'base_prices_'、'max_prices_'、'min_prices_'の接頭辞に到着エリアコードをつけたもの
        $errorForm = new Sgmov_Form_Error();

        $blnEmptyError = FALSE;
        $blnInvalidNumberError = FALSE;
        $blnMinMaxError = FALSE;
        $blnNoEdit = TRUE;
        for ($i = 0; $i < count($sessionForm->to_area_cds); $i++) {
            // max
            $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->max_prices[$i]);
            $v->isNotEmpty();
            if (!$v->isValid()) {
                $blnEmptyError = TRUE;
                // 項目にエラーメッセージは表示しないのでキーだけでよい
                $errorForm->addError('max_prices_' . $i, '');
            } else {
                // 0以上の整数
                $v->isInteger(0);
                if (!$v->isValid()) {
                    $blnInvalidNumberError = TRUE;
                    $errorForm->addError('max_prices_' . $i, '');
                }else{
                    // 正しい場合先頭の0を除去
                    $sessionForm->max_prices[$i] = strval(intval($sessionForm->max_prices[$i]));
                }
            }

            // min
            $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->min_prices[$i]);
            $v->isNotEmpty();
            if (!$v->isValid()) {
                $blnEmptyError = TRUE;
                $errorForm->addError('min_prices_' . $i, '');
            } else {
                // 0以上の整数
                $v->isInteger(0);
                if (!$v->isValid()) {
                    $blnInvalidNumberError = TRUE;
                    $errorForm->addError('min_prices_' . $i, '');
                }else{
                    // 正しい場合先頭の0を除去
                    $sessionForm->min_prices[$i] = strval(intval($sessionForm->min_prices[$i]));
                }
            }

            // base
            $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->base_prices[$i]);
            $v->isNotEmpty();
            if (!$v->isValid()) {
                $blnEmptyError = TRUE;
                $errorForm->addError('base_prices_' . $i, '');
            } else {
                // 0以上の整数
                $v->isInteger(0);
                if (!$v->isValid()) {
                    $blnInvalidNumberError = TRUE;
                    $errorForm->addError('base_prices_' . $i, '');
                }else{
                    // 正しい場合先頭の0を除去
                    $sessionForm->base_prices[$i] = strval(intval($sessionForm->base_prices[$i]));
                }
            }

            // min <= base <= max
            if ($v->isValid()) {
                $v->isInteger(intval($sessionForm->min_prices[$i]), intval($sessionForm->max_prices[$i]));
                if (!$v->isValid()) {
                    $blnMinMaxError = TRUE;
                    $errorForm->addError('base_prices_' . $i, '');
                    $errorForm->addError('max_prices_' . $i, '');
                    $errorForm->addError('min_prices_' . $i, '');
                }
            }

            // 変更されているか
            if ($sessionForm->max_prices[$i] !== $sessionForm->orig_max_prices[$i] || $sessionForm->min_prices[$i] !== $sessionForm->orig_min_prices[$i] ||
                 $sessionForm->base_prices[$i] !== $sessionForm->orig_base_prices[$i]) {
                $blnNoEdit = FALSE;
            }

        }

        if ($blnEmptyError) {
            $errorForm->addError('top_empty', '未入力の項目があります。');
        }

        if ($blnInvalidNumberError) {
            $errorForm->addError('top_invalid', '入力値をお確かめください。');
        }

        if ($blnMinMaxError) {
            $errorForm->addError('top_minmax', '下限料金 <= 基本料金 <= 上限料金になっていません。');
        }

        if ($blnNoEdit) {
            $errorForm->addError('top_noedit', '変更がありません。');
        }

        return $errorForm;
    }
}
?>
