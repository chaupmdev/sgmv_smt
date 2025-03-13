<?php
/**
 * @package    ClassDefFile
 * @author     K.Sawada(SCS)
 * @copyright  2020-2020 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('ren/Common');
Sgmov_Lib::useForms(array('Error', 'RenSession', 'Ren001In', 'Ren001Out'));
/**#@-*/

/**
 * コミケサービスのお申し込み入力画面を表示します。
 * @package    View
 * @subpackage REC
 * @author     K.Sawada(SCS)
 * @copyright  2020-2020 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Ren_Input extends Sgmov_View_Ren_Common {

   
    /**
     * 都道府県サービス
     * @var Sgmov_Service_Prefecture
     */
    protected $_PrefectureService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {

        parent::__construct();
    }


    /**
     * 処理を実行します。
     * <ol><li>
     * セッションに情報があるかどうかを確認
     * </li><li>
     * 情報有り
     *   <ol><li>
     *   セッション情報を元に出力情報を作成
     *   </li></ol>
     * </li><li>
     * 情報無し
     *   <ol><li>
     *   出力情報を設定
     *   </li></ol>
     * </li><li>
     * テンプレート用の値をセット
     * </li><li>
     * チケット発行
     * </li></ol>
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['ticket']:チケット文字列
     * </li><li>
     * ['outForm']:出力フォーム
     * </li><li>
     * ['errorForm']:エラーフォーム
     * </li></ul>
     */
    public function executeInner() {

        // セッションに情報があるかどうかを確認
        $session = Sgmov_Component_Session::get();

        if(@empty($_SERVER["HTTP_REFERER"]) || strpos($_SERVER["HTTP_REFERER"], "/ren/") == false) {
            // セッション情報を破棄
            $session->deleteForm(self::FEATURE_ID);
        }

        // 情報
        $sessionForm = $session->loadForm(self::FEATURE_ID);

        $inForm = new Sgmov_Form_Ren001In();

        $errorForm = NULL;

        if(@!empty($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], "/ren/") !== false ){
            // 初期表示時以外(入力エラーなどで戻ってきた場合など)
            if (isset($sessionForm)) {
                $clearFlg = filter_input(INPUT_GET, 'clr');
                $inForm    = $sessionForm->in;
                if (empty($clearFlg)) {
                    $errorForm = $sessionForm->error;
                } else {
                    $errorForm = NULL;
                }

                // セッション破棄
                $sessionForm->error = NULL;
            }
        } else {
            // 初期表示時
            if(!empty($sessionForm)) {
                $sessionForm->in = NULL;
                $sessionForm->error = NULL;
            }
        }

        $param  = "";
        $resultData = $this->_createOutFormByInForm($inForm, $param);

        $outForm = $resultData["outForm"];
        $dispItemInfo = $resultData["dispItemInfo"];
        
        // チケット発行
        $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_REN001);
        
        return array(
            'ticket'    => $ticket,
            'outForm'   => $outForm,
            'dispItemInfo' => $dispItemInfo,
            'errorForm' => $errorForm,
        );
    }


    /**
     * 入力フォームの値を出力フォームを生成します。
     * @param Sgmov_Form_Ren001In $inForm 入力フォーム
     * @return Sgmov_Form_Ren001Out 出力フォーム
     */
    protected function _createOutFormByInForm($inForm, $param=NULL) {
        $inForm = (array)$inForm;
        return $this->createOutFormByInForm($inForm, new Sgmov_Form_Ren001Out());
    }

}    