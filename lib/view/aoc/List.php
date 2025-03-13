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
Sgmov_Lib::useServices(array('Login', 'Calendar', 'OtherCampaign'));
Sgmov_Lib::useForms(array('Error', 'Aoc001Out'));
/**#@-*/

 /**
 * 特価一覧画面を表示します。
 * @package    View
 * @subpackage AOC
 * @author     T.Aono(SGS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Aoc_List extends Sgmov_View_Aoc_Common
{
    /**
     * ログインサービス
     * @var Sgmov_Service_Login
     */
    public $_loginService;
	

    /**
     * 拠点・エリアサービス
     * @var Sgmov_Service_CenterArea
     */
    public $_centerAreaService;

    /**
     * 他社連携キャンペーンサービス
     * @var Sgmov_Service_SpecialPrice
     */
    public $_OtherCampaignService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
 	    $this->_loginService = new Sgmov_Service_Login();
	    $this->_OtherCampaignService = new Sgmov_Service_OtherCampaign();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * セッション情報の削除
     * </li><li>
     * GETパラメーターのチェック
     * </li><li>
     * GETパラメーターを元に出力情報を生成
     * </li></ol>
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['outForm']:出力フォーム
     * </li></ul>
     */
   
    public function executeInner()
    {

        Sgmov_Component_Log::debug('セッション情報の削除');
        Sgmov_Component_Session::get()->deleteForm($this->getFeatureId());

        Sgmov_Component_Log::debug('GETパラメーターを元に出力情報を生成');
        $outForm = $this->_createOutForm();

        return array('outForm'=>$outForm);
	    }

    
    /**
     * GETパラメーターを元に出力情報を生成します。
     * @param string $listModeCd 一覧画面の表示モード
     * @return array
     * ['outForm'] 出力フォーム
     * ['errorForm'] エラーフォーム
     */
    public function _createOutForm()
    {
        $outForm = new Sgmov_Form_Aoc001Out();

        $db = Sgmov_Component_DB::getAdmin();
		
		// 基本情報の設定
        $this->_setBasicInfo($outForm);

        // 他社連携キャンペーンの取得
        $spInfos = $this->_OtherCampaignService->
                        fetchSpecialPricesByStatus($db);
						
        foreach ($spInfos as $spInfo) {
            $this->_setSpInfo($outForm, $spInfo);
        }
		return $outForm;
    }

  /**
     * 出力フォームに他社連携キャンペーン情報を設定します。
     * @param Sgmov_Form_Aoc001Out $outForm 出力フォーム
     * @param array $spInfo 特価情報
     * @param array $fromAreaIds 特価情報に紐付く出発エリア情報
     * @param array $masters マスター情報
     */
    public function _setSpInfo($outForm, $spInfo)
    {
        // 他社連携キャンペーン内容
        $outForm->raw_oc_ids[]            = $spInfo['id'];
        $outForm->raw_oc_names[]            = $spInfo['campaign_name'];
        $outForm->raw_oc_contents[]         = $spInfo['campaign_content'];
        $outForm->raw_oc_applications[]     = $spInfo['campaign_application'];
        $outForm->raw_oc_createds[]         = $this->_getDateStringToViewString($spInfo['created']);
        $outForm->raw_oc_modifieds[]        = $this->_getDateStringToViewString($spInfo['modified']);
    }
	
	/**
     * 出力フォームに基本情報を設定します。
     * @param Sgmov_Form_Aoc001Out $outForm 出力フォーム
     * @param string $listModeCd 一覧画面表示モード
     */
    public function _setBasicInfo($outForm)
    {
        // 本社ユーザーかどうか
        $outForm->raw_honsha_user_flag = $this->getHonshaUserFlag();
    }
  
    /**
     * "YYYY-MM-DD"から"YYYY/MM/DD"に変換します。
     *
     * @param string $dateStr 日付文字列
     * @return string 表示用文字列
     */
    public function _getDateStringToViewString($dateStr)
    {
        $splits = explode(Sgmov_Service_Calendar::DATE_SEPARATOR, $dateStr, 3);
        return "{$splits[0]}/{$splits[1]}/{$splits[2]}";
    }
}
?>
