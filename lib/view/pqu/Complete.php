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
Sgmov_Lib::useView('pqu/Common');
Sgmov_Lib::useServices(array('Questionnaire'));
Sgmov_Lib::useForms(array('Error', 'PquSession', 'Pqu001In'));
/**#@-*/

 /**
 * アンケート情報を登録し、完了画面を表示します。
 * @package    View
 * @subpackage PQU
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pqu_Complete extends Sgmov_View_Pqu_Common
{
    /**
     * アンケートサービス
     * @var Sgmov_Service_Questionnaire
     */
    public $_service;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        $this->_service = new Sgmov_Service_Questionnaire();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * セッションの継続を確認
     * </li><li>
     * チケットの確認と破棄
     * </li><li>
     * 入力チェック
     * </li><li>
     * セッションから情報を取得
     * </li><li>
     * 情報をDBへ格納
     * </li><li>
     * セッション情報を破棄
     * </li></ol>
     */
    public function executeInner()
    {
        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();

        // チケットの確認と破棄
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_PQU002, $this->_getTicket());

        // セッションから情報を取得
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        $data = $this->_createInsertDataFromInForm($sessionForm->in);

        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        // 情報をDBへ格納
        $this->_service->
                insert($db, $data);

        // セッション情報を破棄
        $session->deleteForm(self::FEATURE_ID);
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
     * 入力フォームの値を元にインサート用データを生成します。
     * @param Sgmov_Form_Pqu001In $inForm 入力フォーム
     * @return array インサート用データ
     */
    public function _createInsertDataFromInForm($inForm)
    {
        $data = array();

        $data['answer1'] = $this->question1_lbls[$inForm->question1_cd_sel];

        $value = '';
        $tempValue = $this->question2_1_lbls[$inForm->question2_1_sel_flag];
        if (! empty($tempValue)) {
            $value .= $tempValue;
        }
        $tempValue = $this->question2_2_lbls[$inForm->question2_2_sel_flag];
        if (! empty($tempValue)) {
            if (! empty($value)) {
                $value .= ', ';
            }
            $value .= $tempValue;
        }
        $tempValue = $this->question2_3_lbls[$inForm->question2_3_sel_flag];
        if (! empty($tempValue)) {
            if (! empty($value)) {
                $value .= ', ';
            }
            $value .= $tempValue;
        }
        $tempValue = $this->question2_4_lbls[$inForm->question2_4_sel_flag];
        if (! empty($tempValue)) {
            if (! empty($value)) {
                $value .= ', ';
            }
            $value .= $tempValue;
        }
        $tempValue = $this->question2_5_lbls[$inForm->question2_5_sel_flag];
        if (! empty($tempValue)) {
            if (! empty($value)) {
                $value .= ', ';
            }
            $value .= $tempValue;
        }
        $data['answer2'] = $value;

        $data['answer2_text'] = $inForm->question2_5_text;
        $data['answer3'] = $this->question3_lbls[$inForm->question3_cd_sel];
        $data['answer4'] = $this->question4_lbls[$inForm->question4_cd_sel];
        $data['answer5'] = $this->question5_lbls[$inForm->question5_cd_sel];
        $data['answer6'] = $this->question6_lbls[$inForm->question6_cd_sel];
        $data['answer7'] = $this->question7_lbls[$inForm->question7_cd_sel];
        $data['answer8'] = $this->question8_lbls[$inForm->question8_cd_sel];
        $data['answer9'] = $this->question9_lbls[$inForm->question9_cd_sel];
        $data['answer10'] = $inForm->question10_text;
        return $data;
    }
}
?>
