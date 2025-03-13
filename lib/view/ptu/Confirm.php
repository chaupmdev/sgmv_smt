<?php
/**
 * @package    ClassDefFile
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__).'/../../Lib.php';
Sgmov_Lib::useView('ptu/Common');
Sgmov_Lib::useForms(array('Error', 'PtuSession', 'Ptu003Out'));
/**#@-*/

/**
 * 単身カーゴプランのお申し込み確認画面を表示します。
 * @package    View
 * @subpackage Ptu
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Ptu_Confirm extends Sgmov_View_Ptu_Common {

    /**
     * 共通サービス
     * @var Sgmov_Service_AppCommon
     */
    public $_appCommon;

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
    * 繁忙期サービス
    * @var Sgmov_Service_MstHanbouki
    */
    public $_MstMstHanbouki;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_appCommon             = new Sgmov_Service_AppCommon();
        $this->_MstCargoArea   		  = new Sgmov_Service_MstCargoArea();
        $this->_MstCgCargoOpt  		  = new Sgmov_Service_MstCgCargoOpt();
        $this->_MstCargoTanpinHinmoku = new Sgmov_Service_MstCargoTanpinHinmoku();
        $this->_MstMstHanbouki 		  = new Sgmov_Service_MstHanbouki();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * セッションの継続を確認
     * </li><li>
     * セッションに入力チェック済みの情報があるかどうかを確認
     * </li><li>
     * セッション情報を元に出力情報を設定
     * </li><li>
     * チケット発行
     * </li></ol>
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['ticket']:チケット文字列
     * </li><li>
     * ['outForm']:出力フォーム
     * </li></ul>
     */
    public function executeInner() {

        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();

        // DB接続
        $db = Sgmov_Component_DB::getPublic();

        // セッションに入力チェック済みの情報があるかどうかを確認
        $sessionForm = $session->loadForm(self::FEATURE_ID);

        if (!isset($sessionForm) || $sessionForm->status != self::VALIDATION_SUCCEEDED) {
            // 不正遷移
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS);
        }

        // セッション情報を元に出力情報を設定
        $outForm = $this->_createOutFormByInForm($sessionForm->in, $db);

        // チケットを発行
        $ticket = $session->publishTicket(self::FEATURE_ID, self::GAMEN_ID_PTU003);

        return array(
            'ticket'  => $ticket,
            'outForm' => $outForm,
        );
    }
    /**
     * 入力フォームの値を元に出力フォームを生成します。
     * @param Sgmov_Form_Ptu001In $inForm 入力フォーム
     * @return Sgmov_Form_Ptu003Out 出力フォーム
     */
    public function _createOutFormByInForm($inForm, $db) {

        $outForm = new Sgmov_Form_Ptu003Out();

        // TODO オブジェクトから値を直接取得できるよう修正する
        // オブジェクトから取得できないため、配列に型変換
        $inForm = (array)$inForm;

        // 基準日
        $date = new DateTime();
        $sys_year  = intval($date->format('Y'));
        $sys_month = intval($date->format('m'));
        $sys_day   = intval($date->format('d'));
        $kijyunBi  = $sys_year.'/'.$sys_month.'/'.$sys_day;

        // 送料
        $outForm->raw_delivery_charge = number_format($inForm['hidden_mitumoriZeikomi']);

        $prefectures  = $this->_MstCargoArea->fetchCargoAreas($db);

        // 便種
        $outForm->raw_binshu_cd = $inForm['binshu_cd'];

        $outForm->raw_surname  = $inForm['surname'];
        $outForm->raw_forename = $inForm['forename'];

        if (empty($inForm['tel1'])) {
            $outForm->raw_tel = '';
        } else {
            $outForm->raw_tel = $inForm['tel1'] . '-' . $inForm['tel2'] . '-' . $inForm['tel3'];
        }
        if (empty($inForm['fax1'])) {
        	$outForm->raw_fax = '';
        } else {
        	$outForm->raw_fax = $inForm['fax1'] . '-' . $inForm['fax2'] . '-' . $inForm['fax3'];
        }
        $outForm->raw_mail = $inForm['mail'];
        if (empty($inForm['zip1'])) {
            $outForm->raw_zip = '';
        } else {
            $outForm->raw_zip = $inForm['zip1'] . '-' . $inForm['zip2'];
        }
        $outForm->raw_pref     = $prefectures['names'][array_search($inForm['pref_cd_sel'], $prefectures['ids'])];
        $outForm->raw_address  = $inForm['address'];
        $outForm->raw_building = $inForm['building'];

        $outForm->raw_surname_hksaki  = $inForm['surname_hksaki'];
        $outForm->raw_forename_hksaki = $inForm['forename_hksaki'];

        if (empty($inForm['tel1_hksaki'])) {
        	$outForm->raw_tel_hksaki = '';
        } else {
        	$outForm->raw_tel_hksaki = $inForm['tel1_hksaki'] . '-' . $inForm['tel2_hksaki'] . '-' . $inForm['tel3_hksaki'];
        }
        if (empty($inForm['tel1_fuzai_hksaki'])) {
        	$outForm->raw_tel_fuzai_hksaki = '';
        } else {
        	$outForm->raw_tel_fuzai_hksaki = $inForm['tel1_fuzai_hksaki'] . '-' . $inForm['tel2_fuzai_hksaki'] . '-' . $inForm['tel3_fuzai_hksaki'];
        }

        if (empty($inForm['zip1_hksaki'])) {
        	$outForm->raw_zip_hksaki = '';
        } else {
        	$outForm->raw_zip_hksaki = $inForm['zip1_hksaki'] . '-' . $inForm['zip2_hksaki'];
        }
        $outForm->raw_pref_cd_sel_hksaki = $prefectures['names'][array_search($inForm['pref_cd_sel_hksaki'], $prefectures['ids'])];
        $outForm->raw_address_hksaki  = $inForm['address_hksaki'];
        $outForm->raw_building_hksaki = $inForm['building_hksaki'];

        // 繁忙期
        $hanboki = $this->_MstMstHanbouki->fetchHanbokiKbn($db);
        if (!empty($inForm) && !empty($inForm['hikitori_yotehiji_date_year_cd_sel']) && !empty($inForm['hikitori_yotehiji_date_month_cd_sel'])&& !empty($inForm['hikitori_yotehiji_date_day_cd_sel'])) {
        	$ymd = $inForm['hikitori_yotehiji_date_year_cd_sel'].'/'.$inForm['hikitori_yotehiji_date_month_cd_sel'].'/'.$inForm['hikitori_yotehiji_date_day_cd_sel'];
        	$hanboki = $this->_MstMstHanbouki->fetchHanbokiKbn($db,$ymd);
        	$kijyunBi = $ymd;
        }
        // 搬出オプション
        $hanshutsuNm = '';
        $hanshutsuOpt  = $this->_MstCgCargoOpt->fetchCagoOptList($db, array('io_kbn' => 1,'binshu_cd' => $inForm['binshu_cd'],'hanboki' => $hanboki,'ymd' => $kijyunBi));
        $chkOpt = $inForm['checkboxHanshutsu'];
        $chkText = $inForm['textHanshutsu'];
        $y = 0;
        for ($i = 0; $i < count($hanshutsuOpt['cds']); $i++) {
        	if ($hanshutsuOpt['input_kbns'][$i] == '2') {
        		if (!empty($chkText) && $chkText[$y] != '' && $chkText[$y] > 0) {
        			$hanshutsuNm .= $hanshutsuOpt['komoku_names'][$i].'('.$chkText[$y].'),';
        		}
        		$y++;
        	} else if (!empty($chkOpt)) {
        		if (in_array($hanshutsuOpt['cds'][$i], $chkOpt)) {
        			if ($hanshutsuOpt['input_kbns'][$i] == '4') {
        				$hanshutsuNm .= $hanshutsuOpt['komoku_names'][$i].$hanshutsuOpt['sagyo_names'][$i].',';
        			} else {
        				$hanshutsuNm .= $hanshutsuOpt['komoku_names'][$i].',';
        			}
        		}
        	}
        }
        if ($hanshutsuNm != '') {
        	$hanshutsuNm = substr($hanshutsuNm, 0, -1);
        }

        $outForm->raw_hanshutsu_opt = $hanshutsuNm;

        // 搬入オプション
        $hannyuNm = '';
        $hannyuOpt  = $this->_MstCgCargoOpt->fetchCagoOptList($db, array('io_kbn' => 2,'binshu_cd' => $inForm['binshu_cd'],'hanboki' => $hanboki,'ymd' => $kijyunBi));
    	$chkOpt = $inForm['checkboxHannyu'];
        $chkText = $inForm['textHannyu'];
        $y = 0;
        for ($i = 0; $i < count($hannyuOpt['cds']); $i++) {
        	if ($hannyuOpt['input_kbns'][$i] == '2') {
        		if (!empty($chkText) && $chkText[$y] != '' && $chkText[$y] > 0) {
            		$hannyuNm .= $hannyuOpt['komoku_names'][$i].'('.$chkText[$y].'),';
            	}
        		$y++;
        	} else if (!empty($chkOpt)) {
        		if (in_array($hannyuOpt['cds'][$i], $chkOpt)) {
        			if ($hannyuOpt['input_kbns'][$i] == '4') {
        				$hannyuNm .= $hannyuOpt['komoku_names'][$i].$hannyuOpt['sagyo_names'][$i].',';
        			} else {
        				$hannyuNm .= $hannyuOpt['komoku_names'][$i].',';
        			}
        		}
        	}
        }
		if ($hannyuNm != '') {
			$hannyuNm = substr($hannyuNm, 0, -1);
		}

		$outForm->raw_hannyu_opt = $hannyuNm;

		if ($inForm['binshu_cd'] == self::BINSHU_TANPINYOSO) {
			$rst = '';
			$tanhinSel = $inForm['tanhin_cd_sel'];
			$tanNmFree = $inForm['tanNmFree'];
			$tanpinHinmoku  = $this->_MstCargoTanpinHinmoku->fetchCagoTanpinHinmokuList($db);
			$cnt = count($tanhinSel);
// 			if ($cnt > 1) {
// 				$cnt--;
// 			}
			for ($i=0;$i<$cnt;$i++){
				if (!empty($tanhinSel[$i])) {
					if ($tanhinSel[$i] == '99001' || $tanhinSel[$i] == '99002'|| $tanhinSel[$i] == '99003'
					|| $tanhinSel[$i] == '99004'|| $tanhinSel[$i] == '99005'|| $tanhinSel[$i] == '99006'|| $tanhinSel[$i] == '99007') {
						$rst.= $tanNmFree[$i].',';
					} else {
						$rst.= $tanpinHinmoku['names'][array_search($tanhinSel[$i], $tanpinHinmoku['ids'])].',';
					}
				}
			}
			if ($rst != '') {
				$rst = substr($rst, 0, -1);
			}
			$outForm->raw_tanhin_cd_sel		= $rst;
		} else {
			$outForm->raw_cago_daisu 		= $inForm['cago_daisu'];
		}

        $outForm->raw_hikitori_yotehiji_date_cd_sel = $this->_appCommon->getYmd($inForm['hikitori_yotehiji_date_year_cd_sel'] . $inForm['hikitori_yotehiji_date_month_cd_sel'] . $inForm['hikitori_yotehiji_date_day_cd_sel']);
        $cargo_collection_st_time = $this->cargo_collection_st_time_lbls;
        $cargo_collection_justime = $this->cargo_collection_justime_lbls;
//         if (!empty($inForm['hikitori_yotehiji_time_cd_sel'])) {
//             if ($inForm['hikitori_yotehiji_time_cd_sel'] === '00') {
//                 $outForm->raw_hikitori_yotehiji_time_cd_sel = '時間帯指定なし';
//             } else {
//             	$outForm->raw_hikitori_yotehiji_time_cd_sel  = $cargo_collection_st_time[$inForm['hikitori_yotehiji_time_cd_sel']];
//             }
//         } else {
//         	$outForm->raw_hikitori_yotehiji_time_cd_sel = '時間帯指定なし';
//         }
        $hikitori_yotehiji_time = '時間帯指定なし';
        if ($inForm['hikitori_yoteji_sel'] == '2') {
        	if (!empty($inForm['hikitori_yotehiji_time_cd_sel'])) {
        		$hikitori_yotehiji_time = ltrim($cargo_collection_st_time[$inForm['hikitori_yotehiji_time_cd_sel']], '0');
        	}
        } else if ($inForm['hikitori_yoteji_sel'] == '3') {
        	if (!empty($inForm['hikitori_yotehiji_justime_cd_sel'])) {
        		$hikitori_yotehiji_time = ltrim($cargo_collection_justime[$inForm['hikitori_yotehiji_justime_cd_sel']], '0');
        	}
        }
        $outForm->raw_hikitori_yotehiji_time_cd_sel = $hikitori_yotehiji_time;

        $outForm->raw_hikoshi_yotehiji_date_cd_sel = $this->_appCommon->getYmd($inForm['hikoshi_yotehiji_date_year_cd_sel'] . $inForm['hikoshi_yotehiji_date_month_cd_sel'] . $inForm['hikoshi_yotehiji_date_day_cd_sel']);
//         if (!empty($inForm['hikoshi_yotehiji_time_cd_sel'])) {
//         	if ($inForm['hikoshi_yotehiji_time_cd_sel'] === '00') {
//         		$outForm->raw_hikoshi_yotehiji_time_cd_sel = '時間帯指定なし';
//         	} else {
//         		$outForm->raw_hikoshi_yotehiji_time_cd_sel  = $cargo_collection_st_time[$inForm['hikoshi_yotehiji_time_cd_sel']];
//         	}
//         } else {
//         	$outForm->raw_hikoshi_yotehiji_time_cd_sel = '時間帯指定なし';
//         }

        $hikoshi_yotehiji_time = '時間帯指定なし';
        if ($inForm['hikoshi_yoteji_sel'] == '2') {
        	if (!empty($inForm['hikoshi_yotehiji_time_cd_sel'])) {
        		$hikoshi_yotehiji_time = ltrim($cargo_collection_st_time[$inForm['hikoshi_yotehiji_time_cd_sel']], '0');
        	}
        } else if ($inForm['hikoshi_yoteji_sel'] == '3') {

        	if (!empty($inForm['hikoshi_yotehiji_justime_cd_sel'])) {
        		$hikoshi_yotehiji_time = ltrim($cargo_collection_justime[$inForm['hikoshi_yotehiji_justime_cd_sel']], '0');
        	}
        }
        $outForm->raw_hikoshi_yotehiji_time_cd_sel = $hikoshi_yotehiji_time;

        $outForm->raw_payment_method_cd_sel = $inForm['payment_method_cd_sel'];

        $convenience_store_cd = isset($inForm['convenience_store_cd_sel']) ? $inForm['convenience_store_cd_sel'] : null;
        if (!empty($this->convenience_store_lbls[$convenience_store_cd])) {
            $outForm->raw_convenience_store = $this->convenience_store_lbls[$convenience_store_cd];
        }

        if ($inForm['payment_method_cd_sel'] === '2') {
            $outForm->raw_card_number = str_repeat('*', strlen($inForm['card_number']) - 4).substr($inForm['card_number'], -4);
            $outForm->raw_card_expire = $this->_appCommon->getTani($inForm['card_expire_year_cd_sel'], '年').$this->_appCommon->getTani($inForm['card_expire_month_cd_sel'], '月');
            $outForm->raw_security_cd = $inForm['security_cd'];
        }

        return $outForm;
    }
}