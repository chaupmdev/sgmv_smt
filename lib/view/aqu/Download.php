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
Sgmov_Lib::useForms(array('Aqu001In', 'Aqu001Out', 'Error'));
Sgmov_Lib::useServices(array('Login', 'Questionnaire'));
Sgmov_Lib::useView('aqu/Common');
/**#@-*/

 /**
 * アンケート結果ダウンロード画面を表示します。
 * @package    View
 * @subpackage AQU
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Aqu_Download extends Sgmov_View_Aqu_Common
{
    /**
     * ログインサービス
     * @var Sgmov_Service_Login
     */
    public $_loginService;

    /**
     * アンケートサービス
     * @var Sgmov_Service_Questionnaire
     */
    public $_questionnaireService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        $this->_loginService = new Sgmov_Service_Login();
        $this->_questionnaireService = new Sgmov_Service_Questionnaire();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * ダウンロードボタン押下ではない場合
     * <ol><li>
     * フォームを初期化
     * </li></ol>
     * </li><li>
     * ダウンロードボタン押下の場合
     * <ol><li>
     * チケットの確認(破棄しない)
     * </li><li>
     * 入力チェック
     * </li><li>
     * 失敗の場合はエラーメッセージを設定してフォームを返す
     * </li><li>
     * 成功の場合はCSVをダウンロード
     * </li></ol>
     * </li></ol>
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['outForm']:出力フォーム
     * </li><li>
     * ['errorForm']:エラーフォーム
     * </li></ul>
     */
    public function executeInner()
    {
        if (!isset($_POST['download_btn_x'])) {
            // ダウンロードボタン押下ではない場合
            $outForm = new Sgmov_Form_Aqu001Out();
            $errorForm = new Sgmov_Form_Error();

            // チケット発行
            $session = Sgmov_Component_Session::get();
            $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_AQU001);
        } else {
            // ダウンロードボタン押下の場合
            // チケットの確認(破棄しない)
            $session = Sgmov_Component_Session::get();
            $ticket = $this->_getTicket();
            $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_AQU001, $ticket, FALSE);

            // 入力チェック
            $inForm = $this->_createInFormFromPost($_POST);
            $errorForm = $this->_validate($inForm);
            if ($errorForm->hasError()) {
                $outForm = $this->_createOutFormByInForm($inForm);
            } else {
                // アンケート情報を取得
                $params = $this->_createSearchParamsFromInForm($inForm);
                $db = Sgmov_Component_DB::getAdmin();
                $csvData = $this->_questionnaireService->fetchCsvData($db, $params);
                // 1行の場合はエラー(ヘッダのみ)
                $dataCount = count($csvData);
                if ($dataCount === 1) {
                    $outForm = $this->_createOutFormByInForm($inForm);
                    $errorForm = new Sgmov_Form_Error();
                    $errorForm->addError('top', '該当するアンケートはありません。');
                } else {
                    // 2行以上の場合

                    // 対象レコードのダウンロードフラグを更新する
                    $ids = array();
                    for ($i = 1; $i < $dataCount; $i++) {
                        $ids[] = $csvData[$i]['id'];
                    }
                    $params = array();
                    $params['user_account'] = $session->loadLoginUser()->account;
                    $params['ids'] = $ids;
                    $this->_questionnaireService->updateDownloadFlag($db, $params);

                    // 出力してスクリプトを終了
                    Sgmov_Component_Csv::downloadCsv($csvData);
                    Sgmov_Component_SideEffect::callExit();
                }
            }
        }

        // テンプレート用の値の設定
        $outForm = $this->_setTemplateValuesToOutForm($outForm);

        // 未ダウンロード件数
        $db = Sgmov_Component_DB::getAdmin();
        $notYetCount = $this->_questionnaireService->getNotYetDownloadedCount($db);
        if ($notYetCount === 0) {
            $errorForm->addError('top_no_not_yet', '未ダウンロードのアンケートはありません。');
        } else {
            $errorForm->addError('top_not_yet', sprintf('未ダウンロードのアンケートが %d 件あります。', $notYetCount));
        }

        return array('ticket'=>$ticket, 'outForm'=>$outForm, 'errorForm'=>$errorForm);
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
     * 出力フォームにテンプレート用の値を設定して返します。
     * @param Sgmov_Form_Aqu001Out $outForm 出力フォーム
     * @return Sgmov_Form_Aqu001Out 出力フォーム
     */
    public function _setTemplateValuesToOutForm($outForm)
    {
        $outForm->raw_honsha_user_flag = $this->_loginService->getHonshaUserFlag();

        $years = $this->getYears();
        $outForm->raw_from_year_cds = $years;
        $outForm->raw_from_year_lbls = $years;
        $outForm->raw_from_month_cds = $this->months;
        $outForm->raw_from_month_lbls = $this->months;
        $outForm->raw_from_day_cds = $this->days;
        $outForm->raw_from_day_lbls = $this->days;
        $outForm->raw_to_year_cds = $years;
        $outForm->raw_to_year_lbls = $years;
        $outForm->raw_to_month_cds = $this->months;
        $outForm->raw_to_month_lbls = $this->months;
        $outForm->raw_to_day_cds = $this->days;
        $outForm->raw_to_day_lbls = $this->days;

        return $outForm;
    }

    /**
     * POST情報から入力フォームを生成します。
     * @param array $post ポスト情報
     * @return Sgmov_Form_Aqu001In 入力フォーム
     */
    public function _createInFormFromPost($post)
    {
        $inForm = new Sgmov_Form_Aqu001In();
        $inForm->from_year_cd_sel = $post['from_year_cd_sel'];
        $inForm->from_month_cd_sel = $post['from_month_cd_sel'];
        $inForm->from_day_cd_sel = $post['from_day_cd_sel'];
        $inForm->to_year_cd_sel = $post['to_year_cd_sel'];
        $inForm->to_month_cd_sel = $post['to_month_cd_sel'];
        $inForm->to_day_cd_sel = $post['to_day_cd_sel'];
        if (isset($post['office_flag']) && $post['office_flag'] === '1') {
            $inForm->office_flag = '1';
        } else {
            $inForm->office_flag = '0';
        }
        if (isset($post['setting_flag']) && $post['setting_flag'] === '1') {
            $inForm->setting_flag = '1';
        } else {
            $inForm->setting_flag = '0';
        }
        if (isset($post['personal_flag']) && $post['personal_flag'] === '1') {
            $inForm->personal_flag = '1';
        } else {
            $inForm->personal_flag = '0';
        }
        if (isset($post['downloaded_flag']) && $post['downloaded_flag'] === '1') {
            $inForm->downloaded_flag = '1';
        } else {
            $inForm->downloaded_flag = '0';
        }
        return $inForm;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_Aqu001In $inForm 入力フォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($inForm)
    {
        // 入力チェック
        $errorForm = new Sgmov_Form_Error();

        // From
        $v = Sgmov_Component_Validator::createDateValidator($inForm->from_year_cd_sel, $inForm->from_month_cd_sel, $inForm->from_day_cd_sel);
        $v->isSelected()->isDate();
        if (!$v->isValid()) {
            $errorForm->addError('top_from_date', $v->getResultMessageTop());
        }

        // To
        $v = Sgmov_Component_Validator::createDateValidator($inForm->to_year_cd_sel, $inForm->to_month_cd_sel, $inForm->to_day_cd_sel);
        $v->isSelected()->isDate();
        if (!$v->isValid()) {
            $errorForm->addError('top_to_date', $v->getResultMessageTop());
        }

        // from <= to
        if (!$errorForm->hasError()) {
            $from = mktime(0, 0, 0, $inForm->from_month_cd_sel, $inForm->from_day_cd_sel, $inForm->from_year_cd_sel);
            $to = mktime(0, 0, 0, $inForm->to_month_cd_sel, $inForm->to_day_cd_sel, $inForm->to_year_cd_sel);
            if ($from > $to) {
                $errorForm->addError('top', '日付の指定が間違っています(開始 > 終了)');
            }
        }
        return $errorForm;
    }

    /**
     * 入力フォームの値を元に出力フォームを生成します。
     * @param Sgmov_Form_Aqu001In $inForm 入力フォーム
     * @return Sgmov_Form_Aqu001Out 出力フォーム
     */
    public function _createOutFormByInForm($inForm)
    {
        $outForm = new Sgmov_Form_Aqu001Out();
        $outForm->raw_from_year_cd_sel = $inForm->from_year_cd_sel;
        $outForm->raw_from_month_cd_sel = $inForm->from_month_cd_sel;
        $outForm->raw_from_day_cd_sel = $inForm->from_day_cd_sel;
        $outForm->raw_to_year_cd_sel = $inForm->to_year_cd_sel;
        $outForm->raw_to_month_cd_sel = $inForm->to_month_cd_sel;
        $outForm->raw_to_day_cd_sel = $inForm->to_day_cd_sel;
        $outForm->raw_office_flag = $inForm->office_flag;
        $outForm->raw_setting_flag = $inForm->setting_flag;
        $outForm->raw_personal_flag = $inForm->personal_flag;
        $outForm->raw_downloaded_flag = $inForm->downloaded_flag;
        return $outForm;
    }

    /**
     * 入力フォームの値を元に検索条件を生成します。
     * @param Sgmov_Form_Aqu001In $inForm 入力フォーム
     * @return array 検索条件
     */
    public function _createSearchParamsFromInForm($inForm)
    {
        $params = array();
        $params['from'] = $inForm->from_year_cd_sel . $inForm->from_month_cd_sel . $inForm->from_day_cd_sel;
        $params['to'] = $inForm->to_year_cd_sel . $inForm->to_month_cd_sel . $inForm->to_day_cd_sel;
        $params['office_flag'] = $inForm->office_flag;
        $params['setting_flag'] = $inForm->setting_flag;
        $params['personal_flag'] = $inForm->personal_flag;
        $params['downloaded_flag'] = $inForm->downloaded_flag;
        return $params;
    }
}
?>
