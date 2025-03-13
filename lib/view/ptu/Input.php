<?php
/**
 * @package    ClassDefFile
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('ptu/Common');
Sgmov_Lib::useForms(array('Error', 'PtuSession', 'Ptu001Out'));
/**#@-*/

/**
 * 単身カーゴプランのお申し込み入力画面を表示します。
 * @package    View
 * @subpackage Ptu
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Ptu_Input extends Sgmov_View_Ptu_Common {

    /**
     * 共通サービス
     * @var Sgmov_Service_AppCommon
     */
    private $_appCommon;

    /**
     * カーゴ都道府県サービス
     * @var Sgmov_Service_MstCargoArea
     */
    private $_MstCargoArea;

    /**
    * カーゴオプションサービス
    * @var Sgmov_Service_MstCgCargoOpt
    */
    private $_MstCgCargoOpt;

    /**
    * カーゴ単品品目サービス
    * @var Sgmov_Service_MstCargoTanpinHinmoku
    */
    private $_MstCargoTanpinHinmoku;

    /**
    * 消費税サービス
    * @var Sgmov_Service_MstShohizei
    */
    private $_MstShohizei;

    /**
    * 繁忙期サービス
    * @var Sgmov_Service_MstHanbouki
    */
    public $_MstMstHanbouki;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_appCommon             = new Sgmov_Service_AppCommon();
        $this->_MstShohizei  		  = new Sgmov_Service_MstShohizei();
        $this->_MstCargoArea   		  = new Sgmov_Service_MstCargoArea();
        $this->_MstCgCargoOpt  		  = new Sgmov_Service_MstCgCargoOpt();
        $this->_MstCargoTanpinHinmoku = new Sgmov_Service_MstCargoTanpinHinmoku();
        $this->_MstMstHanbouki 		  = new Sgmov_Service_MstHanbouki();
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

        // 情報
        $sessionForm = $session->loadForm(self::FEATURE_ID);

        // チェック便種
        $chkBinshu = $this->_checkBinshu($sessionForm);

        $inForm    = NULL;
        $errorForm = NULL;
        if ( (isset($_POST["personal"]) && $chkBinshu) || (!isset($_POST["personal"]) && isset($sessionForm))) {

        	$inForm    = $sessionForm->in;
        	$errorForm = $sessionForm->error;
        	// セッション破棄
        	$sessionForm->error = NULL;
        }

        $outForm = $this->_createOutFormByInForm($inForm);

        // チケット発行
        $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_PTU001);
        return array(
            'ticket'    => $ticket,
            'outForm'   => $outForm,
            'errorForm' => $errorForm,
        );
    }

    private function _checkBinshu($sessionForm) {

    	$result = false;
    	$form = NULL;
    	if (isset($sessionForm)) {

    		$form = $sessionForm->in;
    		$form = (array)$form;
    		// SESSION便種
    		$sessionBinshu = $form['binshu_cd'];

    		if (isset($_POST["personal"])) {
    			// POST便種
    			$binshu = '';
    			switch ($_POST["personal"]) {
    				case 'transport':
    					$binshu    = self::BINSHU_TANPINYOSO;
    					break;
    				case 'cargo':
    					$binshu    = self::BINSHU_TANSHIKAGO;
    					break;
    			}
    			if ($binshu == $sessionBinshu) {
    				$result = true;
    			}
    		}
    	}

    	return $result;
    }

    /**
     * 入力フォームの値を出力フォームを生成します。
     * @param Sgmov_Form_Pve001In $inForm 入力フォーム
     * @return Sgmov_Form_Pve001Out 出力フォーム
     */
    private function _createOutFormByInForm($inForm) {

        $outForm = new Sgmov_Form_Ptu001Out();

        // TODO オブジェクトから値を直接取得できるよう修正する
        // オブジェクトから取得できないため、配列に型変換
        $inForm = (array)$inForm;

        // テンプレート用の値をセット
        $db = Sgmov_Component_DB::getPublic();

        // 基準日
        $date = new DateTime();
        $sys_year  = intval($date->format('Y'));
        $sys_month = intval($date->format('m'));
        $sys_day   = intval($date->format('d'));
        $kijyunBi  = $sys_year.'/'.$sys_month.'/'.$sys_day;

        // 便種
        if (isset($_POST["personal"])) {

        	switch ($_POST["personal"]) {
        		case 'transport':
        			$binshu    = self::BINSHU_TANPINYOSO;
        			break;
        		case 'cargo':
        			$binshu    = self::BINSHU_TANSHIKAGO;
        			break;
        		default:
        			$binshu    = self::BINSHU_TANPINYOSO;
        			break;
        	}
        } else {
        	if (!empty($inForm['binshu_cd'])) {
        		$binshu = $inForm['binshu_cd'];
        	} else {
        		$binshu = self::BINSHU_TANPINYOSO;
        	}
        }

        $outForm->raw_binshu_cd = $binshu;

        // 消費税
        $outForm->raw_shohizei = $this->_MstShohizei->fetchShohizei($db);

        // 開始日 (明日)
        $weekday = array( '日', '月', '火', '水', '木', '金', '土' );
        $frmdt = date('Y年m月d日', strtotime('+1 day'));
        $outForm->raw_frmDt = $frmdt.'('.$weekday[date( 'w', strtotime('+1 day') )].')';

        // 終了日 (半年後)
        $todt = date('Y年m月d日', strtotime('+6 month'));
        $outForm->raw_toDt = $todt.'('.$weekday[date( 'w',strtotime( '+6 month' ) )].')';

        // 都道府県
        $cargoAreas  = $this->_MstCargoArea->fetchCargoAreas($db);
        $outForm->raw_pref_cds  = $cargoAreas['ids'];
        $outForm->raw_pref_lbls = $cargoAreas['names'];

        if ($binshu == self::BINSHU_TANPINYOSO) {
        	// 単品輸送品名
        	$tanpinHinmoku  = $this->_MstCargoTanpinHinmoku->fetchCagoTanpinHinmokuList($db);
        	array_shift($tanpinHinmoku['ids']);
        	array_shift($tanpinHinmoku['names']);
        	$outForm->raw_tanhin_cds  = $tanpinHinmoku['ids'];
        	$outForm->raw_tanhin_lbls = $tanpinHinmoku['names'];

        	$outForm->raw_tanhin_cd_sel = array('');
        	$outForm->raw_tanNmFree = array('');

        }

        // 基準日により「繁忙期」と「消費税」を取得
        $hanboki = $this->_MstMstHanbouki->fetchHanbokiKbn($db);
        if (!empty($inForm) && !empty($inForm['hikitori_yotehiji_date_year_cd_sel']) && !empty($inForm['hikitori_yotehiji_date_month_cd_sel'])&& !empty($inForm['hikitori_yotehiji_date_day_cd_sel'])) {
        	// 繁忙期
        	$ymd = $inForm['hikitori_yotehiji_date_year_cd_sel'].'/'.$inForm['hikitori_yotehiji_date_month_cd_sel'].'/'.$inForm['hikitori_yotehiji_date_day_cd_sel'];
        	$hanboki = $this->_MstMstHanbouki->fetchHanbokiKbn($db,$ymd);
        	// 消費税
        	$outForm->raw_shohizei = $this->_MstShohizei->fetchShohizei($db,$ymd);
        	// 基準日
        	$kijyunBi = $ymd;
        }
        // 搬出オプション
        $hanshutsuOpt  = $this->_MstCgCargoOpt->fetchCagoOptList($db, array('io_kbn' => 1,'binshu_cd' => $binshu,'hanboki' => $hanboki,'ymd' => $kijyunBi));
        $outForm->raw_hanshutsu_cds  = $hanshutsuOpt['cds'];
        $outForm->raw_hanshutsu_komoku_names = $hanshutsuOpt['komoku_names'];
        $outForm->raw_hanshutsu_sagyo_names  = $hanshutsuOpt['sagyo_names'];
        $outForm->raw_hanshutsu_tankas = $hanshutsuOpt['tankas'];
        $outForm->raw_hanshutsu_input_kbns  = $hanshutsuOpt['input_kbns'];
        $outForm->raw_hanshutsu_bikos = $hanshutsuOpt['bikos'];

        // 搬入オプション
        $hannyuOpt  = $this->_MstCgCargoOpt->fetchCagoOptList($db, array('io_kbn' => 2,'binshu_cd' => $binshu,'hanboki' => $hanboki,'ymd' => $kijyunBi));
        $outForm->raw_hannyu_cds  = $hannyuOpt['cds'];
        $outForm->raw_hannyu_komoku_names = $hannyuOpt['komoku_names'];
        $outForm->raw_hannyu_sagyo_names  = $hannyuOpt['sagyo_names'];
        $outForm->raw_hannyu_tankas = $hannyuOpt['tankas'];
        $outForm->raw_hannyu_input_kbns  = $hannyuOpt['input_kbns'];
        $outForm->raw_hannyu_bikos = $hannyuOpt['bikos'];

        $years  = $this->_appCommon->getYears($date->format('Y'), 1, false);
        $months = $this->_appCommon->months;
        $days   = $this->_appCommon->days;

        array_shift($months);
        array_shift($days);

        // お引取り予定日時
        $outForm->raw_hikitori_yotehiji_date_year_cds   = $years;
        $outForm->raw_hikitori_yotehiji_date_year_lbls  = $years;
        $outForm->raw_hikitori_yotehiji_date_month_cds  = $months;
        $outForm->raw_hikitori_yotehiji_date_month_lbls = $months;
        $outForm->raw_hikitori_yotehiji_date_day_cds    = $days;
        $outForm->raw_hikitori_yotehiji_date_day_lbls   = $days;

        $cargo_collection_st_time = $this->cargo_collection_st_time_lbls;
        $outForm->raw_hikitori_yotehiji_time_cds  = array_keys($cargo_collection_st_time);
        $outForm->raw_hikitori_yotehiji_time_lbls = array_values($cargo_collection_st_time);

        $cargo_collection_st_time2 = $this->cargo_collection_justime_lbls;
        $outForm->raw_hikitori_yotehiji_justime_cds  = array_keys($cargo_collection_st_time2);
        $outForm->raw_hikitori_yotehiji_justime_lbls = array_values($cargo_collection_st_time2);

        // お引越し予定日時
        $outForm->raw_hikoshi_yotehiji_date_year_cds   = $years;
        $outForm->raw_hikoshi_yotehiji_date_year_lbls  = $years;
        $outForm->raw_hikoshi_yotehiji_date_month_cds  = $months;
        $outForm->raw_hikoshi_yotehiji_date_month_lbls = $months;
        $outForm->raw_hikoshi_yotehiji_date_day_cds    = $days;
        $outForm->raw_hikoshi_yotehiji_date_day_lbls   = $days;

        $outForm->raw_hikoshi_yotehiji_time_cds  = array_keys($cargo_collection_st_time);
        $outForm->raw_hikoshi_yotehiji_time_lbls = array_values($cargo_collection_st_time);
        $outForm->raw_hikoshi_yotehiji_justime_cds  = array_keys($cargo_collection_st_time2);
        $outForm->raw_hikoshi_yotehiji_justime_lbls = array_values($cargo_collection_st_time2);

        $convenience_store = $this->convenience_store_lbls;
        $outForm->raw_convenience_store_cds  = array_keys($convenience_store);
        $outForm->raw_convenience_store_lbls = array_values($convenience_store);

        if (empty($inForm)) {
            return $outForm;
        }

        $outForm->raw_surname  		= $inForm['surname'];
        $outForm->raw_forename 		= $inForm['forename'];
        $outForm->raw_tel1 			= $inForm['tel1'];
        $outForm->raw_tel2 			= $inForm['tel2'];
        $outForm->raw_tel3 			= $inForm['tel3'];
        $outForm->raw_fax1 			= $inForm['fax1'];
        $outForm->raw_fax2 			= $inForm['fax2'];
        $outForm->raw_fax3 			= $inForm['fax3'];
        $outForm->raw_mail        	= $inForm['mail'];
        $outForm->raw_retype_mail 	= $inForm['retype_mail'];
        $outForm->raw_zip1        	= $inForm['zip1'];
        $outForm->raw_zip2        	= $inForm['zip2'];
        $outForm->raw_pref_cd_sel 	= $inForm['pref_cd_sel'];
        $outForm->raw_address     	= $inForm['address'];
        $outForm->raw_building    	= $inForm['building'];

        $outForm->raw_surname_hksaki  	= $inForm['surname_hksaki'];
        $outForm->raw_forename_hksaki 	= $inForm['forename_hksaki'];
        $outForm->raw_zip1_hksaki       = $inForm['zip1_hksaki'];
        $outForm->raw_zip2_hksaki       = $inForm['zip2_hksaki'];
        $outForm->raw_pref_cd_sel_hksaki= $inForm['pref_cd_sel_hksaki'];
        $outForm->raw_address_hksaki    = $inForm['address_hksaki'];
        $outForm->raw_building_hksaki   = $inForm['building_hksaki'];
        $outForm->raw_tel1_hksaki 		= $inForm['tel1_hksaki'];
        $outForm->raw_tel2_hksaki 		= $inForm['tel2_hksaki'];
        $outForm->raw_tel3_hksaki 		= $inForm['tel3_hksaki'];
        $outForm->raw_tel1_fuzai_hksaki = $inForm['tel1_fuzai_hksaki'];
        $outForm->raw_tel2_fuzai_hksaki = $inForm['tel2_fuzai_hksaki'];
        $outForm->raw_tel3_fuzai_hksaki = $inForm['tel3_fuzai_hksaki'];

        if ($binshu == self::BINSHU_TANPINYOSO) {
        	if (empty($inForm['tanhin_cd_sel'])) {
        		$outForm->raw_tanhin_cd_sel		= array('');
        		$outForm->raw_tanNmFree = array('');
        	} else {
        		$outForm->raw_tanhin_cd_sel	= $inForm['tanhin_cd_sel'];
        		$outForm->raw_tanNmFree = $inForm['tanNmFree'];
        	}

        } else {
        	$outForm->raw_cago_daisu 		= $inForm['cago_daisu'];
        }

        $outForm->raw_textHanshutsu 	= $inForm['textHanshutsu'];
        $outForm->raw_checkboxHanshutsu = $inForm['checkboxHanshutsu'];
        $outForm->raw_textHannyu 		= $inForm['textHannyu'];
        $outForm->raw_checkboxHannyu 	= $inForm['checkboxHannyu'];

        $outForm->raw_hikitori_yotehiji_date_year_cd_sel  = $inForm['hikitori_yotehiji_date_year_cd_sel'];
        $outForm->raw_hikitori_yotehiji_date_month_cd_sel = $inForm['hikitori_yotehiji_date_month_cd_sel'];
        $outForm->raw_hikitori_yotehiji_date_day_cd_sel   = $inForm['hikitori_yotehiji_date_day_cd_sel'];

        $outForm->raw_hikitori_yoteji_sel       		  = $inForm['hikitori_yoteji_sel'];
        if ($inForm['hikitori_yoteji_sel'] == '2') {
        	$outForm->raw_hikitori_yotehiji_time_cd_sel   = $inForm['hikitori_yotehiji_time_cd_sel'];
        } else if ($inForm['hikitori_yoteji_sel'] == '3') {
        	$outForm->raw_hikitori_yotehiji_justime_cd_sel= $inForm['hikitori_yotehiji_justime_cd_sel'];
        }

        $outForm->raw_hikoshi_yotehiji_date_year_cd_sel   = $inForm['hikoshi_yotehiji_date_year_cd_sel'];
        $outForm->raw_hikoshi_yotehiji_date_month_cd_sel  = $inForm['hikoshi_yotehiji_date_month_cd_sel'];
        $outForm->raw_hikoshi_yotehiji_date_day_cd_sel    = $inForm['hikoshi_yotehiji_date_day_cd_sel'];

        $outForm->raw_hikoshi_yoteji_sel       		  	  = $inForm['hikoshi_yoteji_sel'];
        if ($inForm['hikoshi_yoteji_sel'] == '2') {
        	$outForm->raw_hikoshi_yotehiji_time_cd_sel    = $inForm['hikoshi_yotehiji_time_cd_sel'];
        } else if ($inForm['hikoshi_yoteji_sel'] == '3') {
        	$outForm->raw_hikoshi_yotehiji_justime_cd_sel = $inForm['hikoshi_yotehiji_justime_cd_sel'];
        }

        $outForm->raw_payment_method_cd_sel    = $inForm['payment_method_cd_sel'];
        $outForm->raw_convenience_store_cd_sel = $inForm['convenience_store_cd_sel'];

        return $outForm;
    }

}