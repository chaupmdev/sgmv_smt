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
Sgmov_Lib::useServices(array('Login', 'BasePrice', 'CoursePlan'));
Sgmov_Lib::useForms(array('Error', 'AcfSession', 'Acf004Out'));
/**#@-*/

 /**
 * 料金マスタメンテナンス情報を登録し、完了画面を表示します。
 * @package    View
 * @subpackage ACF
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Acf_Complete extends Sgmov_View_Acf_Common
{
    /**
     * ログインサービス
     * @var Sgmov_Service_Login
     */
    public $_loginService;

    /**
     * 基本料金サービス
     * @var Sgmov_Service_BasePrice
     */
    public $_basePriceService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        $this->_loginService = new Sgmov_Service_Login();
        $this->_basePriceService = new Sgmov_Service_BasePrice();
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
     * 同時更新エラーの場合はエラーを設定して入力画面へ遷移
     * </li><li>
     * 出力情報を設定
     * </li><li>
     * セッション情報を破棄
     * </li></ol>
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['outForm']:出力フォーム
     * </li></ul>
     */
    public function executeInner()
    {
        Sgmov_Component_Log::debug('チケットの確認と破棄');
        $session = Sgmov_Component_Session::get();
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_ACF003, $this->_getTicket());

        Sgmov_Component_Log::debug('セッションから情報を取得');
        $sessionForm = $session->loadForm(self::FEATURE_ID);


        Sgmov_Component_Log::debug('ユーザーアカウントを取得');
        $user_account = $session->loadLoginUser()->account;

        Sgmov_Component_Log::debug('DBを更新');
        $errorForm = $this->_updatePrices($user_account, $sessionForm);
        if ($errorForm->hasError()) {
            Sgmov_Component_Log::debug('同時更新エラー');
            $sessionForm->error = $errorForm;
            $session->saveForm(self::FEATURE_ID, $sessionForm);
            Sgmov_Component_Redirect::redirectMaintenance('/acf/input');
        }

        Sgmov_Component_Log::debug('出力情報を設定');
        $outForm = new Sgmov_Form_Acf004Out();
        $outForm->raw_honsha_user_flag = $this->_loginService->

                                                getHonshaUserFlag();

        Sgmov_Component_Log::debug('セッション情報を破棄');
        $session->deleteForm(self::FEATURE_ID);

        return array('outForm'=>$outForm);
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
     * セッション情報を元に金額情報を更新します。
     *
     * 同時更新によって更新に失敗した場合はエラーフォームにメッセージを設定して返します。
     *
     * @param string $user_account ユーザーアカウント
     * @param Sgmov_Form_AcfSession $sessionForm セッションフォーム
     * @return Sgmov_Form_Error エラーフォーム
     */
    public function _updatePrices($user_account, $sessionForm)
    {
        // DBへ接続
        $db = Sgmov_Component_DB::getAdmin();

        // コースプランコード分割
        $ids = explode(Sgmov_Service_CoursePlan::ID_DELIMITER, $sessionForm->cur_course_plan_cd);

        // 出発エリアコード
        $from_area_id = $sessionForm->cur_from_area_cd;

        // 変更のあるレコードのみを更新
        $base_price_ids = array();
        $to_area_ids = array();
        $base_prices = array();
        $max_prices = array();
        $min_prices = array();
        $modifieds = array();
        for ($i = 0; $i < count($sessionForm->to_area_cds); $i++) {
            if ($sessionForm->max_prices[$i] !== $sessionForm->orig_max_prices[$i] || $sessionForm->min_prices[$i] !== $sessionForm->orig_min_prices[$i] ||

                 $sessionForm->base_prices[$i] !== $sessionForm->orig_base_prices[$i]) {
                $base_price_ids[] = $sessionForm->base_price_cds[$i];
                $to_area_ids[] = $sessionForm->to_area_cds[$i];
                $base_prices[] = $sessionForm->base_prices[$i];
                $max_prices[] = $sessionForm->max_prices[$i];
                $min_prices[] = $sessionForm->min_prices[$i];
                $modifieds[] = $sessionForm->modifieds[$i];
            }
        }

        // 情報をDBへ格納
        $ret = $this->_basePriceService->

                    updateBasePrices($db, $user_account, $ids[0], $ids[1], $from_area_id, $base_price_ids, $to_area_ids,

                         $base_prices, $max_prices, $min_prices, $modifieds);

        $errorForm = new Sgmov_Form_Error();
        if ($ret === FALSE) {
            $errorForm->addError('top_conflict', '別のユーザーによって更新されています。すべての変更は適用されませんでした。');
        }
        return $errorForm;
    }
}
?>
