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
Sgmov_Lib::useView('ptu/Common', 'CommonConst');
Sgmov_Lib::useForms(array('Error', 'PtuSession', 'Ptu004Out'));
Sgmov_Lib::useServices(array('CenterMail'));
Sgmov_Lib::use3gMdk(array('3GPSMDK'));
/**#@-*/

/**
 * 単身カーゴプランのお申し込み内容を登録し、完了画面を表示します。
 * @package    View
 * @subpackage Ptu
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Ptu_Complete extends Sgmov_View_Ptu_Common {

    const SEVEN_ELEVEN_CODE = 'sej';
    const E_CONTEXT_CODE    = 'econ';
    const WELL_NET_CODE     = 'other';

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
     * 単身カーゴプランのお申し込みサービス
     * @var Sgmov_Service_DatCargo
     */
    private $_DatCargoService;

    /**
    * 単身カーゴプランのお申し込みサービス
    * @var Sgmov_Service_DatCargoOpt
    */
    private $_DatCargoOpt;

    /**
    * 単品輸送のお申し込みサービス
    * @var Sgmov_Service_DatTanpinYuso
    */
    private $_DatTanpinYuso;

    /**
     * 拠点メールアドレスサービス
     * @var Sgmov_Service_CenterMail
     */
    public $_centerMailService;

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
    	$this->_MstMstHanbouki 		  = new Sgmov_Service_MstHanbouki();
    	$this->_MstCargoArea   		  = new Sgmov_Service_MstCargoArea();
    	$this->_MstCgCargoOpt  		  = new Sgmov_Service_MstCgCargoOpt();
        $this->_DatCargoService         = new Sgmov_Service_DatCargo();
        $this->_DatCargoOpt         = new Sgmov_Service_DatCargoOpt();
        $this->_DatTanpinYuso         = new Sgmov_Service_DatTanpinYuso();
        $this->_centerMailService     = new Sgmov_Service_CenterMail();
        $this->_MstCargoTanpinHinmoku = new Sgmov_Service_MstCargoTanpinHinmoku();
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
     * 出力情報を設定
     * </li><li>
     * セッション情報を破棄
     * </li></ol>
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['outForm']:出力フォーム
     * </li></ul>
     */
    public function executeInner() {

        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();

        //チケットの確認と破棄
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_PTU003, $this->_getTicket());

        // 基準日
        $date = new DateTime();
        $sys_year  = intval($date->format('Y'));
        $sys_month = intval($date->format('m'));
        $sys_day   = intval($date->format('d'));
        $kijyunBi  = $sys_year.'/'.$sys_month.'/'.$sys_day;

        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        //登録用IDを取得
        $id = $this->_DatCargoService->select_id($db);

        // セッションから情報を取得
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        if (empty($sessionForm)) {
            $sessionForm = new Sgmov_Form_PtuSession();
        }

        $inForm = $this->_createDataByInForm($db, $sessionForm->in);

        switch ($inForm['payment_method_cd_sel']) {
            case '2':
                $checkForm = $this->_createCheckCreditCardDataByInForm($inForm);
                break;
            case '1':
                $checkForm = $this->_createCheckConvenienceStoreDataByInForm($db, $inForm);
                break;
            default:
                break;
        }

        if (!empty($checkForm)) {
            $inForm = $this->_transact($checkForm, $inForm);
        }

        // メール送信
        if (!empty($inForm['mail']) && !empty($inForm['merchant_result'])) {
            // メール送信用データを作成
            $mailData = $this->_createMailDataByInForm($db, $inForm);
            switch ($inForm['binshu_cd']) {
                case self::BINSHU_TANPINYOSO:
                    $mailTemplate = '/ptu_user_danhinyuso.txt';
                    break;
                case self::BINSHU_TANSHIKAGO:
                default:
                    $mailTemplate = '/ptu_user_danshincago.txt';
                    break;
            }
            $this->_centerMailService->_sendThankYouMail($mailTemplate, $inForm['mail'], $mailData);
        }

        $data = $this->_createInsertDataByInForm($inForm, $id);

        // 情報をDBへ格納
        $this->_DatCargoService->insert($db, $data);

        // 単品輸送データ登録
        if ($inForm['binshu_cd'] == self::BINSHU_TANPINYOSO) {

        	$tanhinSel = $inForm['tanhin_cd_sel'];
        	$tanNmFree = $inForm['tanNmFree'];
        	$tanpinHinmoku  = $this->_MstCargoTanpinHinmoku->fetchCagoTanpinHinmokuList($db);
        	$cnt = count($tanhinSel);
//         	if ($cnt > 1) {
//         		$cnt--;
//         	}
        	for ($i=0;$i<$cnt;$i++){
        		if (!empty($tanhinSel[$i])) {
        			$sort = $i+1;
        			if ($tanhinSel[$i] == '99001' || $tanhinSel[$i] == '99002'|| $tanhinSel[$i] == '99003'
        			|| $tanhinSel[$i] == '99004'|| $tanhinSel[$i] == '99005'|| $tanhinSel[$i] == '99006'|| $tanhinSel[$i] == '99007') {
        				$this->_DatTanpinYuso->insert($db, $id, $tanhinSel[$i], $tanNmFree[$i],$sort);
        			} else {
        				$nm= $tanpinHinmoku['names'][array_search($tanhinSel[$i], $tanpinHinmoku['ids'])];
        				$val = '';
        				if (!empty($nm)) {
        					$val = explode(":",$nm);
        				}
        				$this->_DatTanpinYuso->insert($db, $id, $tanhinSel[$i], $val[1],$sort);
        			}
        		}
        	}
        }

        // 繁忙期
        $hanboki = $this->_MstMstHanbouki->fetchHanbokiKbn($db);
        if (!empty($inForm) && !empty($inForm['hikitori_yotehiji_date_year_cd_sel']) && !empty($inForm['hikitori_yotehiji_date_month_cd_sel'])&& !empty($inForm['hikitori_yotehiji_date_day_cd_sel'])) {
        	$ymd = $inForm['hikitori_yotehiji_date_year_cd_sel'].'/'.$inForm['hikitori_yotehiji_date_month_cd_sel'].'/'.$inForm['hikitori_yotehiji_date_day_cd_sel'];
        	$hanboki = $this->_MstMstHanbouki->fetchHanbokiKbn($db,$ymd);
        	$kijyunBi = $ymd;
        }
        // 搬出オプション登録
        $hanshutsuOpt  = $this->_MstCgCargoOpt->fetchCagoOptList($db, array('io_kbn' => 1,'binshu_cd' => $inForm['binshu_cd'],'hanboki' => $hanboki,'ymd' => $kijyunBi));
        $chkOpt = $inForm['checkboxHanshutsu'];
        $chkText = $inForm['textHanshutsu'];
        $y = 0;
        for ($i = 0; $i < count($hanshutsuOpt['cds']); $i++) {
        	if ($hanshutsuOpt['input_kbns'][$i] == '2') {
        		if (!empty($chkText) && $chkText[$y] != '' && $chkText[$y] > 0) {
        			$this->_DatCargoOpt->insert($db, $id, $hanshutsuOpt['cds'][$i], $chkText[$y]);
        		}
        		$y++;
        	} else if (!empty($chkOpt)) {
        		if (in_array($hanshutsuOpt['cds'][$i], $chkOpt)) {
        			$this->_DatCargoOpt->insert($db, $id, $hanshutsuOpt['cds'][$i]);
        		}
        	}
        }

        // 搬入オプション登録
        $hannyuOpt  = $this->_MstCgCargoOpt->fetchCagoOptList($db, array('io_kbn' => 2,'binshu_cd' => $inForm['binshu_cd'],'hanboki' => $hanboki,'ymd' => $kijyunBi));
        $chkOpt = $inForm['checkboxHannyu'];
        $chkText = $inForm['textHannyu'];
        $y = 0;
        for ($i = 0; $i < count($hannyuOpt['cds']); $i++) {
        	if ($hannyuOpt['input_kbns'][$i] == '2') {
        		if (!empty($chkText) && $chkText[$y] != '' && $chkText[$y] > 0) {
        			$this->_DatCargoOpt->insert($db, $id, $hannyuOpt['cds'][$i], $chkText[$y]);
        		}
        		$y++;
        	} else if (!empty($chkOpt)) {
        		if (in_array($hannyuOpt['cds'][$i], $chkOpt)) {
        			$this->_DatCargoOpt->insert($db, $id, $hannyuOpt['cds'][$i]);
        		}
        	}
        }

        // 出力情報を設定
        $outForm = $this->_createOutFormByInForm($inForm);

        // セッション情報を破棄
        $session->deleteForm(self::FEATURE_ID);

        return array('outForm' => $outForm);
    }

    /**
     * POST値からチケットを取得します。
     * チケットが存在しない場合は空文字列を返します。
     * @return string チケット文字列
     */
    public function _getTicket() {
        if (!isset($_POST['ticket'])) {
            $ticket = '';
        } else {
            $ticket = $_POST['ticket'];
        }
        return $ticket;
    }

    /**
     * 入力フォームの値を元に支払期限を生成します。
     * @param Sgmov_Form_Ptu001-003In $inForm 入力フォーム
     * @return string 支払期限
     */
    public function _getPayLimit($db, $inForm) {
        // コンビニの支払期限
        $date_convenience_store = new DateTime();
        switch ($inForm['convenience_store_cd_sel']) {
            case '1':
                $service_option_type = self::SEVEN_ELEVEN_CODE;
                $max_day = '+150 day';
                break;
            case '2':
                $service_option_type = self::E_CONTEXT_CODE;
                $max_day = '+60 day';
                break;
            case '3':
                $service_option_type = self::WELL_NET_CODE;
                $max_day = '+365 day';
                break;
            default:
                return;
        }
        $date_convenience_store->modify($max_day);
        $pay_limit_convenience_store = $date_convenience_store->format('Y/m/d');
Sgmov_Component_Log::debug($pay_limit_convenience_store);
/*
        // SGムービングの支払期限
        $embarkation_date = $this->_TravelService->fetchEmbarkationDate($db, array('travel_id' => $inForm['travel_cd_sel']));
        $departure = $this->_TravelTerminalService->fetchTravelDeparture($db, array('travel_id' => $inForm['travel_cd_sel']), true);
        if (!empty($embarkation_date)) {
            $date = new DateTime($embarkation_date);
        } elseif (!empty($departure['dates'])) {
            $date = new DateTime($departure['dates'][array_search($inForm['travel_departure_cd_sel'], $departure['ids'])]);
        }
        if (!empty($date)) {
            $date->modify('-10 day');
            $pay_limit = $date->format('Y/m/d');
Sgmov_Component_Log::debug($pay_limit);
        }
*/
        if (empty($pay_limit) || $pay_limit > $pay_limit_convenience_store) {
            $pay_limit = $pay_limit_convenience_store;
        }
Sgmov_Component_Log::debug($pay_limit);
/*
        if (!empty($inForm['cargo_collection_date_year_cd_sel'])
                && !empty($inForm['cargo_collection_date_month_cd_sel'])
                && !empty($inForm['cargo_collection_date_day_cd_sel'])) {
            $date2 = new DateTime($inForm['cargo_collection_date_year_cd_sel']
                    . '/' . $inForm['cargo_collection_date_month_cd_sel']
                    . '/' . $inForm['cargo_collection_date_day_cd_sel']);
            switch ($date2->format('N')) {
                case '1': // 月
                case '2': // 火
                    $date2->modify('-4 day');
                    break;
                case '3': // 水
                case '4': // 木
                case '5': // 金
                case '6': // 土
                    $date2->modify('-2 day');
                    break;
                case '7': // 日
                    $date2->modify('-3 day');
                    break;
                default:
                    break;
            }
            $pay_limit2 = $date2->format('Y/m/d');
Sgmov_Component_Log::debug($pay_limit2);
            if (empty($pay_limit) || $pay_limit > $pay_limit2) {
                $pay_limit = $pay_limit2;
            }
Sgmov_Component_Log::debug($pay_limit);
        }
*/
Sgmov_Component_Log::debug($pay_limit);
        return $pay_limit;
    }

    /**
     * 入力フォームの値を元にデータを生成します。
     * @param Sgmov_Form_Ptu001-003In $inForm 入力フォーム
     * @return array データ
     */
    public function _createDataByInForm($db, $inForm) {

        // TODO オブジェクトから値を直接取得できるよう修正する
        // オブジェクトから取得できないため、配列に型変換
        $inForm = (array) $inForm;

        $inForm['delivery_charge'] = $inForm['hidden_mitumoriZeikomi'];

        $inForm['authorization_cd'] = '';
        $inForm['receipt_cd']       = '';

        // 2038年問題対応のため、date()ではなくDateTime()を使う
        // DateTime::createFromFormat()はPHP5.3未満で対応していない
        if (method_exists('DateTime', 'createFromFormat')) {
            $date = DateTime::createFromFormat('U.u', gettimeofday(true))
                ->setTimezone(new DateTimeZone('Asia/Tokyo'));
        } else {
            $date = new DateTime();
        }

        $inForm['merchant_datetime'] = $date->format('Y/m/d H:i:s.u');

        $inForm['payment_order_id'] = 'sagawa-moving-cargo_' . $date->format('YmdHis') . '_' . str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');

        $inForm['pay_limit'] = $this->_getPayLimit($db, $inForm);

        return $inForm;
    }

    /**
     * 入力フォームの値を元にクレジットカード決済用データを生成します。
     * @param Sgmov_Form_Ptu001-003In $inForm 入力フォーム
     * @return array 決済用データ
     */
    public function _createCheckCreditCardDataByInForm($inForm) {

        // セキュリティコード
        $securityCode = htmlspecialchars($inForm['security_cd']);

        // 要求電文パラメータ値の指定
        $data = new CardAuthorizeRequestDto();

        // 取引ID
        $data->setOrderId($inForm['payment_order_id']);

        // 支払金額
        $data->setAmount(strval($inForm['delivery_charge']));

        // カード番号
        $data->setCardNumber($inForm['card_number']);

        // カード有効期限 MM/YY
        $cardExpire = $inForm['card_expire_month_cd_sel'] . '/' . substr($inForm['card_expire_year_cd_sel'], -2);
        $data->setCardExpire($cardExpire);

        // 与信方法
        $data->setWithCapture('true');

        // 支払オプション
/*
        $jpo1 = $inForm['jpo1'];
        $jpo2 = $inForm['jpo2'];
        switch ($jpo1) {
            case '61';
                $jpo = $jpo1.'C'.$jpo2;
                break;
            case '10';
            case '80';
            default:
                $jpo = $jpo1;
                break;
        }
*/
        // 支払は一回払い固定にする
        $jpo = '10';
        if (isset($jpo)) {
            $data->setJpo($jpo);
        }

        // セキュリティコード
        if (isset($securityCode)) {
            $data->setSecurityCode($securityCode);
        }

        return $data;
    }

    /**
     * 入力フォームの値を元にコンビニ決済用データを生成します。
     * @param Sgmov_Form_Ptu001-003In $inForm 入力フォーム
     * @return array 決済用データ
     */
    public function _createCheckConvenienceStoreDataByInForm($db, $inForm) {

        // 要求電文パラメータ値の指定
        $data = new CvsAuthorizeRequestDto();

        // お支払店舗
        switch ($inForm['convenience_store_cd_sel']) {
            case '1':
                $service_option_type = self::SEVEN_ELEVEN_CODE;
                break;
            case '2':
                $service_option_type = self::E_CONTEXT_CODE;
                break;
            case '3':
                $service_option_type = self::WELL_NET_CODE;
                break;
            default:
                break;
        }
        $data->setServiceOptionType($service_option_type);

        // 取引ID
        $data->setOrderId($inForm['payment_order_id']);

        // 支払金額
        $data->setAmount(strval($inForm['delivery_charge']));

        // 姓
        $data->setName1($inForm['surname']);

        // 名
        $data->setName2($inForm['forename']);

        // 電話番号
        $data->setTelNo($inForm['tel1'] . '-' . $inForm['tel2'] . '-' . $inForm['tel3']);

        // 支払期限
        $data->setPayLimit($inForm['pay_limit']);

        // 支払区分
        // リザーブパラメータのため無条件に '0' を設定する
        $data->setPaymentType('0');

        return $data;
    }

    /**
     * 入力フォームの値を元にインサート用データを生成します。
     * @param Sgmov_Form_Ptu001-003In $inForm 入力フォーム
     * @return array インサート用データ
     */
    public function _createInsertDataByInForm($inForm, $id) {

        $hikitori_yotehiji_date = null;
        if (!empty($inForm['hikitori_yotehiji_date_year_cd_sel'])
                && !empty($inForm['hikitori_yotehiji_date_month_cd_sel'])
                && !empty($inForm['hikitori_yotehiji_date_day_cd_sel'])) {
            $hikitori_yotehiji_date = $inForm['hikitori_yotehiji_date_year_cd_sel']
                    . '/' . $inForm['hikitori_yotehiji_date_month_cd_sel']
                    . '/' . $inForm['hikitori_yotehiji_date_day_cd_sel'];
        }

        $hikoshi_yotehiji_date = null;
        if (!empty($inForm['hikoshi_yotehiji_date_year_cd_sel'])
        && !empty($inForm['hikoshi_yotehiji_date_month_cd_sel'])
        && !empty($inForm['hikoshi_yotehiji_date_day_cd_sel'])) {
        	$hikoshi_yotehiji_date = $inForm['hikoshi_yotehiji_date_year_cd_sel']
        	. '/' . $inForm['hikoshi_yotehiji_date_month_cd_sel']
        	. '/' . $inForm['hikoshi_yotehiji_date_day_cd_sel'];
        }

        $cargo_collection_st_time = $this->cargo_collection_st_time_lbls;
        $cargo_collection_justime = $this->cargo_collection_justime_lbls;

        $hikitori_yotehiji_time = null;
//         if (!empty($inForm['hikitori_yotehiji_time_cd_sel'])) {
//         	if ($inForm['hikitori_yotehiji_time_cd_sel'] === '00') {
//         		$hikitori_yotehiji_time = '時間帯指定なし';
//         	} else {
//         		$hikitori_yotehiji_time = $cargo_collection_st_time[$inForm['hikitori_yotehiji_time_cd_sel']];
//         	}
//         }

        if ($inForm['hikitori_yoteji_sel'] == '1') {
        	$hikitori_yotehiji_time = '時間帯指定なし';
        } else if ($inForm['hikitori_yoteji_sel'] == '2') {
        	if (!empty($inForm['hikitori_yotehiji_time_cd_sel'])) {
        		$hikitori_yotehiji_time = $cargo_collection_st_time[$inForm['hikitori_yotehiji_time_cd_sel']];
        	}
        } else if ($inForm['hikitori_yoteji_sel'] == '3') {
        	if (!empty($inForm['hikitori_yotehiji_justime_cd_sel'])) {
        		$hikitori_yotehiji_time = ltrim($cargo_collection_justime[$inForm['hikitori_yotehiji_justime_cd_sel']], '0');
        	}
        }

        $hikoshi_yotehiji_time = null;
//         if (!empty($inForm['hikoshi_yotehiji_time_cd_sel'])) {
//         	if ($inForm['hikoshi_yotehiji_time_cd_sel'] === '00') {
//         		$hikoshi_yotehiji_time = '時間帯指定なし';
//         	} else {

//         	}
//         }

        if ($inForm['hikoshi_yoteji_sel'] == '1') {
        	$hikoshi_yotehiji_time = '時間帯指定なし';
        } else if ($inForm['hikoshi_yoteji_sel'] == '2') {
        	if (!empty($inForm['hikoshi_yotehiji_time_cd_sel'])) {
        		$hikoshi_yotehiji_time = $cargo_collection_st_time[$inForm['hikoshi_yotehiji_time_cd_sel']];
        	}
        } else if ($inForm['hikoshi_yoteji_sel'] == '3') {
        	if (!empty($inForm['hikoshi_yotehiji_justime_cd_sel'])) {
        		$hikoshi_yotehiji_time = ltrim($cargo_collection_justime[$inForm['hikoshi_yotehiji_justime_cd_sel']], '0');
        	}
        }

//         $kenId = null;
//         if (!empty($inForm['pref_cd_sel'])) {
//         	$splits = explode(Sgmov_Service_CoursePlan::ID_DELIMITER, $inForm['pref_cd_sel']);
//         	$kenId = $splits[1];
//         }

//         $kenId1 = null;
//         if (!empty($inForm['pref_cd_sel_hksaki'])) {
//         	$splits = explode(Sgmov_Service_CoursePlan::ID_DELIMITER, $inForm['pref_cd_sel_hksaki']);
//         	$kenId1 = $splits[1];
//         }

        $batch_status = '0';
        if (!empty($inForm['merchant_result']) && !empty($inForm['authorization_cd'])) {
        	$batch_status = '1';
        }

        $data = array(
	        'crg_id'           			=> $id,
	        'crg_name1'           		=> $inForm['surname'],
	        'crg_name2'           		=> $inForm['forename'],
	        'crg_telno'           		=> $inForm['tel1'] .'-'. $inForm['tel2'] .'-'. $inForm['tel3'],
	        'crg_faxno'           		=> !empty($inForm['fax1']) ? $inForm['fax1'] .'-'. $inForm['fax2'] .'-'. $inForm['fax3'] : null,
	        'crg_mail'           		=> $inForm['mail'],
	        'crg_shukamoto_yubin'       => !empty($inForm['zip1']) && !empty($inForm['zip2']) ? $inForm['zip1'] . $inForm['zip2'] : null,
	        'crg_shukamoto_ken'         => !empty($inForm['pref_cd_sel']) ? $inForm['pref_cd_sel'] : null,
	        'crg_shukamoto_shi'         => $inForm['address'],
	        'crg_shukamoto_banchi'      => $inForm['building'],
	        'crg_haisosaki_name'        => $inForm['surname_hksaki'] . $inForm['forename_hksaki'],
	        'crg_haisosaki_yubin'       => !empty($inForm['zip1_hksaki']) && !empty($inForm['zip2_hksaki']) ? $inForm['zip1_hksaki'] . $inForm['zip2_hksaki'] : null,
	        'crg_haisosaki_ken'         => !empty($inForm['pref_cd_sel_hksaki']) ? $inForm['pref_cd_sel_hksaki'] : null,
	        'crg_haisosaki_shi'         => $inForm['address_hksaki'],
	        'crg_haisosaki_banchi'      => $inForm['building_hksaki'],
	        'crg_haisosaki_telno'       => $inForm['tel1_hksaki'] .'-'. $inForm['tel2_hksaki'] .'-'. $inForm['tel3_hksaki'],
	        'crg_haisosaki_renraku'     => !empty($inForm['tel1_fuzai_hksaki']) ? $inForm['tel1_fuzai_hksaki'] .'-'. $inForm['tel2_fuzai_hksaki'] .'-'. $inForm['tel3_fuzai_hksaki'] : null,
	        'crg_hanshutsu_dt'          => $hikitori_yotehiji_date,
	        'crg_hansuhtsu_time'        => $hikitori_yotehiji_time,
	        'crg_hannyu_dt'           	=> $hikoshi_yotehiji_date,
	        'crg_hannyu_time'           => $hikoshi_yotehiji_time,
	        'crg_daisu'           		=> $inForm['cago_daisu'],
	        //'crg_hinmoku'           	=> null,
	        'crg_kihon_ryokin'          => !empty($inForm['hidden_kihonKin']) ? $inForm['hidden_kihonKin'] : 0,
	        'crg_hanshutsu_kei'         => !empty($inForm['hidden_hanshutsuSum']) ? $inForm['hidden_hanshutsuSum'] : 0,
	        'crg_hannyu_kei'            => !empty($inForm['hidden_hannyuSum']) ? $inForm['hidden_hannyuSum'] : 0,
	        'crg_hanbai_kakaku'         => !empty($inForm['hidden_mitumoriZeinuki']) ? $inForm['hidden_mitumoriZeinuki'] : 0,
	        'crg_hanbai_kakaku_zeigaku' => !empty($inForm['hidden_zeiKin']) ? $inForm['hidden_zeiKin'] : 0,
	        'crg_merchant_result'       => $inForm['merchant_result'],
	        'crg_datetime'           	=> $inForm['merchant_datetime'],
	        'crg_receipted'           	=> null,
	        'crg_send_result'           => 0,
	        'crg_sent'           		=> null,
	        'crg_batch_status'          => $batch_status,
	        'crg_retry_count'           => 0,
	        'crg_payment_method_cd'     => !empty($inForm['payment_method_cd_sel']) ? $inForm['payment_method_cd_sel'] : null,
	        'crg_convenience_store_cd'  => !empty($inForm['convenience_store_cd_sel']) ? $inForm['convenience_store_cd_sel'] : null,
	        'crg_authorization_cd'      => !empty($inForm['authorization_cd']) ? $inForm['authorization_cd'] : null,
	        'crg_receipt_cd'            => !empty($inForm['receipt_cd']) ? $inForm['receipt_cd'] : null,
	        'crg_payment_order_id'      => $inForm['payment_order_id'],
// 	        'crg_insert_program'        => 'sgmvHp',
// 	        'crg_update_program'        => 'sgmvHp',
// 	        'crg_update_no'           	=> 1,
        );

        if ($inForm['binshu_cd'] == self::BINSHU_TANPINYOSO) {
        	$data = array_merge($data, array('crg_daisu'=> 0));
//         	$data = array_merge($data, array('crg_hinmoku'=> $inForm['tanhin_cd_sel']));
        	$data = array_merge($data, array('crg_binshu'=> 2));
        } else {
        	$data = array_merge($data, array('crg_daisu'=> $inForm['cago_daisu']));
        	$data = array_merge($data, array('crg_hinmoku'=> null));
        	$data = array_merge($data, array('crg_binshu'=> 1));
        }

        return $data;
    }

    /**
     * 入力フォームの値を元にメール送信用データを生成します。
     * @param Sgmov_Form_Pin001In $inForm 入力フォーム
     * @return array インサート用データ
     */
    public function _createMailDataByInForm($db, $inForm) {

    	$prefectures  = $this->_MstCargoArea->fetchCargoAreas($db);

    	$hikitori_yotehiji_date = '';
        if (!empty($inForm['hikitori_yotehiji_date_year_cd_sel'])
                && !empty($inForm['hikitori_yotehiji_date_month_cd_sel'])
                && !empty($inForm['hikitori_yotehiji_date_day_cd_sel'])) {
            $hikitori_yotehiji_date = $inForm['hikitori_yotehiji_date_year_cd_sel']
                    . '年' . ltrim($inForm['hikitori_yotehiji_date_month_cd_sel'], '0')
                    . '月' . ltrim($inForm['hikitori_yotehiji_date_day_cd_sel'], '0')
                    . '日';
        }

        $hikitori_yotehiji_time = '時間帯指定なし';
        if ($inForm['hikitori_yoteji_sel'] == '2') {
	        $cargo_collection_st_time = $this->cargo_collection_st_time_lbls;
	        if (!empty($inForm['hikitori_yotehiji_time_cd_sel'])) {
	            $hikitori_yotehiji_time = ltrim($cargo_collection_st_time[$inForm['hikitori_yotehiji_time_cd_sel']], '0');
	        }
        } else if ($inForm['hikitori_yoteji_sel'] == '3') {
        	$cargo_collection_justime = $this->cargo_collection_justime_lbls;
        	if (!empty($inForm['hikitori_yotehiji_justime_cd_sel'])) {
        		$hikitori_yotehiji_time = ltrim($cargo_collection_justime[$inForm['hikitori_yotehiji_justime_cd_sel']], '0');
        	}
        }

        $hikoshi_yotehiji_date = '';
        if (!empty($inForm['hikoshi_yotehiji_date_year_cd_sel'])
        && !empty($inForm['hikoshi_yotehiji_date_month_cd_sel'])
        && !empty($inForm['hikoshi_yotehiji_date_day_cd_sel'])) {
        	$hikoshi_yotehiji_date = $inForm['hikoshi_yotehiji_date_year_cd_sel']
        	. '年' . ltrim($inForm['hikoshi_yotehiji_date_month_cd_sel'], '0')
        	. '月' . ltrim($inForm['hikoshi_yotehiji_date_day_cd_sel'], '0')
        	. '日';
        }

        $hikoshi_yotehiji_time = '時間帯指定なし';
        if ($inForm['hikoshi_yoteji_sel'] == '2') {
        	$cargo_collection_st_time = $this->cargo_collection_st_time_lbls;
        	if (!empty($inForm['hikoshi_yotehiji_time_cd_sel'])) {
        		$hikoshi_yotehiji_time = ltrim($cargo_collection_st_time[$inForm['hikoshi_yotehiji_time_cd_sel']], '0');
        	}
        } else if ($inForm['hikoshi_yoteji_sel'] == '3') {
        	$cargo_collection_justime = $this->cargo_collection_justime_lbls;
        	if (!empty($inForm['hikoshi_yotehiji_justime_cd_sel'])) {
        		$hikoshi_yotehiji_time = ltrim($cargo_collection_justime[$inForm['hikoshi_yotehiji_justime_cd_sel']], '0');
        	}
        }

        // 基準日
        $date = new DateTime();
        $sys_year  = intval($date->format('Y'));
        $sys_month = intval($date->format('m'));
        $sys_day   = intval($date->format('d'));
        $kijyunBi  = $sys_year.'/'.$sys_month.'/'.$sys_day;

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

        $data = array(
            'surname'                  => $inForm['surname'],
            'forename'                 => $inForm['forename'],
            'mail'                     => $inForm['mail'],
            'tel'                      => $inForm['tel1'] . '-' . $inForm['tel2'] . '-' . $inForm['tel3'],
            'zip'                      => !empty($inForm['zip1']) && !empty($inForm['zip2']) ? $inForm['zip1'] . '-' . $inForm['zip2'] : null,
            'pref_name'                => $prefectures['names'][array_search($inForm['pref_cd_sel'], $prefectures['ids'])],
            'address'                  => $inForm['address'],
            'building'                 => $inForm['building'],
        	'surname_hksaki'           => $inForm['surname_hksaki'],
            'forename_hksaki'          => $inForm['forename_hksaki'],
        	'tel_hksaki'               => $inForm['tel1_hksaki'] . '-' . $inForm['tel2_hksaki'] . '-' . $inForm['tel3_hksaki'],
        	'tel_fuzai_hksaki'	       => !empty($inForm['tel1_fuzai_hksaki']) ? $inForm['tel1_fuzai_hksaki'] .'-'. $inForm['tel2_fuzai_hksaki'] .'-'. $inForm['tel3_fuzai_hksaki'] : null,
        	'zip_hksaki'               => !empty($inForm['zip1_hksaki']) && !empty($inForm['zip2_hksaki']) ? $inForm['zip1_hksaki'] . '-' . $inForm['zip2_hksaki'] : null,
            'pref_name_hksaki'         => $prefectures['names'][array_search($inForm['pref_cd_sel_hksaki'], $prefectures['ids'])],
            'address_hksaki'           => $inForm['address_hksaki'],
            'building_hksaki'          => $inForm['building_hksaki'],
            'hikitori_yotehiji_date'   => $hikitori_yotehiji_date,
            'hikitori_yotehiji_time'   => $hikitori_yotehiji_time,
        	'hikoshi_yotehiji_date'    => $hikoshi_yotehiji_date,
            'hikoshi_yotehiji_time'    => $hikoshi_yotehiji_time,
        	'hanshutsuNm'              => $hanshutsuNm,
        	'hannyuNm'                 => $hannyuNm,
        	'mitumori_zeikomi'         => !empty($inForm['hidden_mitumoriZeikomi']) ? number_format($inForm['hidden_mitumoriZeikomi']) . '円' : '0 円',
        );

    	if ($inForm['binshu_cd'] == self::BINSHU_TANPINYOSO) {
    		$rst = '';
    		$tanhinSel = $inForm['tanhin_cd_sel'];
    		$tanNmFree = $inForm['tanNmFree'];
    		$tanpinHinmoku  = $this->_MstCargoTanpinHinmoku->fetchCagoTanpinHinmokuList($db);
    		$cnt = count($tanhinSel);
//     		if ($cnt > 1) {
//     			$cnt--;
//     		}
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

			$data = array_merge($data, array('cago_hinmoku'=> $rst));
		} else {
			$data = array_merge($data, array('cago_daisu'=> $inForm['cago_daisu']));
		}

        // 受付番号
        $data['mail_receipt_cd'] = '';
        if (!empty($inForm['receipt_cd'])) {
            $data['mail_receipt_cd'] = $inForm['receipt_cd'];
        } elseif (!empty($inForm['authorization_cd'])) {
            $data['mail_receipt_cd'] = $inForm['authorization_cd'];
        }

        // お支払方法
        switch ($inForm['payment_method_cd_sel']) {
            case '1':
                $data['payment_method'] = 'コンビニ決済';
                break;
            case '2':
                $data['payment_method'] = 'クレジットカード';
                break;
            default:
                $data['payment_method'] = '';
                break;
        }

        return $data;
    }

    /**
     * セッションの値を元に出力フォームを生成します。
     * @param $inForm 入力フォーム
     * @return Sgmov_Form_Ptu004Out 出力フォーム
     */
    public function _createOutFormByInForm($inForm) {

        $outForm = new Sgmov_Form_Ptu004Out();

        $outForm->raw_convenience_store_cd_sel = $inForm['convenience_store_cd_sel'];
        $outForm->raw_mail = $inForm['mail'];
        $outForm->raw_merchant_result = $inForm['merchant_result'];
        $outForm->raw_payment_method_cd_sel = $inForm['payment_method_cd_sel'];
        $outForm->raw_payment_url = $inForm['payment_url'];
        $outForm->raw_receipt_cd = $inForm['receipt_cd'];
        $outForm->raw_binshu_cd = $inForm['binshu_cd'];
/*
        $payment_limit = '';
        if (!empty($inForm['cargo_collection_date_year_cd_sel'])
                && !empty($inForm['cargo_collection_date_month_cd_sel'])
                && !empty($inForm['cargo_collection_date_day_cd_sel'])) {
            $date = new DateTime($inForm['cargo_collection_date_year_cd_sel']
                    . '/' . $inForm['cargo_collection_date_month_cd_sel']
                    . '/' . $inForm['cargo_collection_date_day_cd_sel']);
            switch ($date->format('N')) {
                case '1': // 月
                case '2': // 火
                    $date->modify('-4 day');
                    break;
                case '3': // 水
                case '4': // 木
                case '5': // 金
                case '6': // 土
                    $date->modify('-2 day');
                    break;
                case '7': // 日
                    $date->modify('-3 day');
                    break;
                default:
                    break;
            }
            $payment_limit = $date->format('Y年m月d日');
            switch ($date->format('N')) {
                case '1':
                    $payment_limit .= '（月）';
                    break;
                case '2':
                    $payment_limit .= '（火）';
                    break;
                case '3':
                    $payment_limit .= '（水）';
                    break;
                case '4':
                    $payment_limit .= '（木）';
                    break;
                case '5':
                    $payment_limit .= '（金）';
                    break;
                case '6':
                    $payment_limit .= '（土）';
                    break;
                case '7':
                    $payment_limit .= '（日）';
                    break;
                default:
                    break;
            }
        }
        $outForm->raw_payment_limit = $payment_limit;
*/
        return $outForm;
    }

    /**
     * 決済用データの入力値の妥当性検査を行います。
     * @param $checkForm 決済用データ
     * @param Sgmov_Form_Ptu003In $inForm 入力フォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _transact($checkForm, $inForm) {
Sgmov_Component_Log::debug($checkForm);Sgmov_Component_Log::debug($inForm);
        // VeriTrans3G MerchantDevelopmentKitマーチャントCCID、マーチャントパスワード設定
        switch ($inForm['payment_method_cd_sel']) {
            case '2': // クレジットカード決済
                $props = array(
                    'merchant_ccid'       => Sgmov_Component_Config::getMdkCreditCardMerchantCcId(),
                    'merchant_secret_key' => Sgmov_Component_Config::getMdkCreditCardMerchantSecretKey(),
                );
                break;
            case '1': // コンビニ決済
                $props = array(
                    'merchant_ccid'       => Sgmov_Component_Config::getMdkConvenienceStoreMerchantCcId(),
                    'merchant_secret_key' => Sgmov_Component_Config::getMdkConvenienceStoreMerchantSecretKey(),
                );
                break;
            default:
                $props = null;
                break;
        }
Sgmov_Component_Log::debug($props);
        // 決済の実行
        $transaction = new TGMDK_Transaction();
        $response = $transaction->execute($checkForm, $props);
Sgmov_Component_Log::debug($response);
        if (!isset($response)) {
            // 予期しない例外
            $inForm['merchant_result'] = '0';
            Sgmov_Component_Log::debug('予期しない例外');
        } else {
            // 想定応答の取得
            Sgmov_Component_Log::debug('想定応答の取得');

            // 取引ID取得
            $resultOrderId = $response->getOrderId();
            Sgmov_Component_Log::debug($resultOrderId);

            // 結果コード取得
            $resultStatus = $response->getMStatus();
            Sgmov_Component_Log::debug($resultStatus);

            // 詳細コード取得
            $resultCode = $response->getVResultCode();
            Sgmov_Component_Log::debug($resultCode);

            // エラーメッセージ取得
            $errorMessage = $response->getMerrMsg();
            Sgmov_Component_Log::debug($errorMessage);

            switch ($resultStatus) {
                case 'success';
                    // 成功
                    $inForm['merchant_result'] = '1';
                    Sgmov_Component_Log::debug('成功');
                    break;
                case 'pending';
                case 'failure';
                default:
                    // 失敗
                    $inForm['merchant_result'] = '0';
                    Sgmov_Component_Log::debug('失敗');
                    break;
            }

            switch ($inForm['payment_method_cd_sel']) {
                case '2': // クレジットカード決済
                    // 承認番号
                    $inForm['authorization_cd'] = $response->getResAuthCode();
                    Sgmov_Component_Log::debug($inForm['authorization_cd']);
                    break;
                case '1': // コンビニ決済
                    // 受付番号
                    $inForm['receipt_cd'] = $response->getReceiptNo();
                    Sgmov_Component_Log::debug($inForm['receipt_cd']);

                    // 払込票URL
                    $inForm['payment_url'] = $response->getHaraikomiUrl();
                    Sgmov_Component_Log::debug($inForm['payment_url']);
                    break;
                default:
                    break;
            }

        }

        return $inForm;
    }
}