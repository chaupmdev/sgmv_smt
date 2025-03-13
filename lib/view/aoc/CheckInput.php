<?php
/**
 * @package    ClassDefFile
 * @author     T.Aono(SGS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('aoc/Common');
Sgmov_Lib::useForms(array('Error', 'AocSession', 'Aoc002In'));
/**#@-*/

 /**
 * 他社連携キャンペーン入力情報をチェックします。
 * @package    View
 * @subpackage AOC
 * @author     T.Aono(SGS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Aoc_CheckInput extends Sgmov_View_Aoc_Common
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
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_AOC002, $this->_getTicket());

        Sgmov_Component_Log::debug('情報を取得');
        $inForm = $this->_createInFormFromPost($_POST);
	$sessionForm = $session->loadForm(self::FEATURE_ID);

	if(empty($sessionForm)) {
		$sessionForm = new stdClass();
	}

	$sessionForm->oc_id          = $inForm->oc_id;
        $sessionForm->oc_name        = $inForm->oc_name;
	$sessionForm->oc_flg         = $inForm->oc_flg;
	$sessionForm->oc_content     = $inForm->oc_content;
	$sessionForm->oc_application = $inForm->oc_application;
		
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
 
            Sgmov_Component_Log::debug('リダイレクト /aoc/input/');
            Sgmov_Component_Redirect::redirectMaintenance('/aoc/input/');
        } else {
 
           Sgmov_Component_Log::debug('リダイレクト /aoc/confirm');
            Sgmov_Component_Redirect::redirectMaintenance('/aoc/confirm/');
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
     * @return Sgmov_Form_Aoc002In 入力フォーム
     */
    public function _createInFormFromPost($post)
    {
        $inForm = new Sgmov_Form_Aoc002In();
	if(empty($inForm)) {
		$inForm = new stdClass();
	}
	
	$inForm->oc_id           = isset($_POST['oc_id']) ? $_POST['oc_id'] : "";
        $inForm->oc_name         = $_POST['oc_name'];
        $inForm->oc_content      = $_POST['oc_content'];
        $inForm->oc_flg          = $_POST['oc_flg'];
        $inForm->oc_application  = $_POST['oc_application'];

        return $inForm;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_AocSession $sessionForm セッションフォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($sessionForm)
    {
        // 入力チェック
        $errorForm = new Sgmov_Form_Error();
	  
      // 他社連携キャンペーン名称
        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->oc_name)->
                                        isNotEmpty()->
                                        isLengthLessThanOrEqualTo(40)->
                                        isWebSystemNg();
		if (!$v->isValid()) {
            $errorForm->addError('top_oc_name', $v->getResultMessageTop());
            $errorForm->addError('oc_name', $v->getResultMessage());
        }
 		
		// 他社連携キャンペーンフラグ
		
		$v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->oc_flg)->
                                        isNotEmpty()->
                                        isLengthLessThanOrEqualTo(10)->
                                        isFlg()->
                                        isFlgRepet($sessionForm->oc_id)->
                                        isWebSystemNg();
	
  
		if (!$v->isValid()) {
            $errorForm->addError('top_oc_flg', $v->getResultMessageTop());
            $errorForm->addError('oc_flg', $v->getResultMessage());
        }
       
		
		// 他社連携キャンペーン内容
        $v = Sgmov_Component_Validator::createSingleValueValidator($sessionForm->oc_content)->
                                        isNotEmpty()->
                                        isLengthLessThanOrEqualTo(100)->
                                        isWebSystemNg();
		
        if (!$v->isValid()) {
            $errorForm->addError('top_oc_content', $v->getResultMessageTop());
            $errorForm->addError('oc_content', $v->getResultMessage());
        }
 
        return $errorForm;
    }
}
?>
