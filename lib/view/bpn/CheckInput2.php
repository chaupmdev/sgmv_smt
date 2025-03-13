<?php
/**
 * @package    ClassDefFile
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/**#@+
 * include files
 */
require_once dirname(__FILE__).'/../../Lib.php';
Sgmov_Lib::useView('bpn/Common');
Sgmov_Lib::useForms(array('Error', 'BpnSession', 'Bpn001In', 'Bpn002In'));
Sgmov_Lib::useServices(array('HttpsZipCodeDll'));
/**#@-*/
/**
 * 物販お申し込みの入力情報をチェックします。
 * @package    View
 * @subpackage BPN
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Bpn_CheckInput2 extends Sgmov_View_Bpn_Common {

    /**
     * イベントサービス
     * @var Sgmov_Service_Event
     */
    protected $_EventService;

    /**
     * イベントサブサービス
     * @var Sgmov_Service_Event
     */
    protected $_EventsubService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_EventService                     = new Sgmov_Service_Event();
        $this->_EventsubService                  = new Sgmov_Service_Eventsub();
        $this->_TimeService                      = new Sgmov_Service_Time();


        parent::__construct();
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
     * 情報をセッションに保存
     * </li><li>
     * 入力エラー無し
     *   <ol><li>
     *   pcr/confirm へリダイレクト
     *   </li></ol>
     * </li><li>
     * 入力エラー有り
     *   <ol><li>
     *   pcr/input へリダイレクト
     *   </li></ol>
     * </li></ol>
     */
    public function executeInner() {
        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();

        // チケットの確認と破棄
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_BPN001, $this->_getTicket());
        // 入力チェック
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        if (empty($sessionForm)) {
            $sessionForm = new Sgmov_Form_BpnSession();
            $sessionForm->in = null;
        }
        // DB接続
        $db = Sgmov_Component_DB::getPublic();

        $inForm = $this->_createInFormFromPost($_POST, $sessionForm->in);

        ///////////////////////////////////////////////////////////////////////////////////////////////////
        // サイトの表示を shohin.term_fr(申込開始) ～ eventsub.arrival_to_time(復路申込期間終了) で制御する
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        $this->checkShohinInTerm($db, $inForm->eventsub_sel);

        $errorForm = $this->_validate($inForm, $db);

        // 物販配列に[0,00]がある場合、削除する。
        foreach ($inForm->comiket_box_buppan_num_ary as $key => $value) {
            if(empty($value) || $value == "00"){
                unset($inForm->comiket_box_buppan_num_ary[$key]);
            }
        }

        // 情報をセッションに保存
        $sessionForm->in = $inForm;
        $sessionForm->error = $errorForm;
        if ($errorForm->hasError()) {
            $sessionForm->status = self::VALIDATION_FAILED;
        } else {
            $sessionForm->status = self::VALIDATION_SUCCEEDED;
        }

        $session->saveForm(self::FEATURE_ID, $sessionForm);
        // リダイレクト処理
        $this->_redirectProc($inForm, $errorForm);
    }

    /**
     * バリデーション後の画面遷移先を決定
     * @param type $inForm
     * @param type $errorForm
     */
    public function _redirectProc($inForm, $errorForm) {
        if ($errorForm->hasError()) {
            $redirectUrl = '/bpn/input/'.$inForm->shikibetsushi."/".$inForm->bpn_type."/".$inForm->shohin_pattern;
            Sgmov_Component_Redirect::redirectPublicSsl($redirectUrl);
        }  else {  // 法人
            Sgmov_Component_Redirect::redirectPublicSsl('/bpn/confirm');
            //Sgmov_Component_Redirect::redirectPublicSsl('/bpn/confirm_active_shohin');
        }

        // 個人の場合は、クレジット・コンビニ支払で表示画面切り替え
        switch ($inForm->comiket_payment_method_cd_sel) {
            case '1': // コンビニ
                Sgmov_Component_Redirect::redirectPublicSsl('/bpn/confirm');
                break;
            case '2': // クレジット
                Sgmov_Component_Redirect::redirectPublicSsl('/bpn/credit_card');
                break;
            case '3': // 電子マネー
                Sgmov_Component_Redirect::redirectPublicSsl('/bpn/confirm');
                break;
            case '4': // コンビニ後払い
                Sgmov_Component_Redirect::redirectPublicSsl('/bpn/confirm');
                break;
        }
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
     * POST情報から入力フォームを生成します。
     *
     * 全ての値は正規化されてフォームに設定されます。
     *
     * @param array $post ポスト情報
     * @param Sgmov_Form_Bpn002In $creditCardForm 入力フォーム
     * @return Sgmov_Form_Bpn001In 入力フォーム
     */
    public function _createInFormFromPost($post, $creditCardForm) {

        // DB接続
        $db = Sgmov_Component_DB::getPublic();

        $inForm = new Sgmov_Form_Bpn002In();

        $creditCardForm = (array)$creditCardForm;

        // チケット
        $inForm->ticket = filter_input(INPUT_POST, 'ticket');

        // input mode
        $inForm->input_mode = filter_input(INPUT_POST, 'input_mode');

        // 商品ヘッダパターン[事前物販用:1、当日物販用:2]
        $inForm->bpn_type = filter_input(INPUT_POST, 'bpn_type');

        // 商品リストパターン[飛沫ブロッカー用:1、梱包資材用:2]
        $inForm->shohin_pattern = filter_input(INPUT_POST, 'shohin_pattern');

        // イベント識別子
        $inForm->shikibetsushi = filter_input(INPUT_POST, 'shikibetsushi');

        ////////////////////////////////////////////////////////////////////////////////////////////
        // コミケ申込
        ////////////////////////////////////////////////////////////////////////////////////////////
        $inForm->event_sel = filter_input(INPUT_POST, 'event_sel');
        $inForm->eventsub_sel = filter_input(INPUT_POST, 'eventsub_sel');
        $inForm->eventsub_zip = filter_input(INPUT_POST, 'eventsub_zip');
        $inForm->eventsub_address = filter_input(INPUT_POST, 'eventsub_address');
        $inForm->eventsub_term_fr = filter_input(INPUT_POST, 'eventsub_term_fr');
        $inForm->eventsub_term_to = filter_input(INPUT_POST, 'eventsub_term_to');

        ////////////////////////////////////////////////////////////////////////////////////////////
        // コミケ申込
        ////////////////////////////////////////////////////////////////////////////////////////////
        $inForm->comiket_id = filter_input(INPUT_POST, 'comiket_id');
        $inForm->comiket_div = filter_input(INPUT_POST, 'comiket_div');

        // $inForm->comiket_personal_name_sei = mb_convert_kana(filter_input(INPUT_POST, 'comiket_personal_name_sei'), 'RNASKV', 'UTF-8');
        // $inForm->comiket_personal_name_mei = mb_convert_kana(filter_input(INPUT_POST, 'comiket_personal_name_mei'), 'RNASKV', 'UTF-8');
        // $inForm->comiket_tel = @str_replace(array('-', 'ー', '−', '―', '‐', 'ｰ'), "-", mb_convert_kana(filter_input(INPUT_POST, 'comiket_tel'), 'rnask', 'UTF-8'));
        // $inForm->comiket_mail = mb_convert_kana(filter_input(INPUT_POST, 'comiket_mail'), 'rnask', 'UTF-8');
        // $inForm->comiket_mail_retype = mb_convert_kana(filter_input(INPUT_POST, 'comiket_mail_retype'), 'rnask', 'UTF-8');


        // $inForm->comiket_detail_collect_date_year_sel = filter_input(INPUT_POST, 'comiket_detail_collect_date_year_sel');
        // $inForm->comiket_detail_collect_date_month_sel = filter_input(INPUT_POST, 'comiket_detail_collect_date_month_sel');
        // $inForm->comiket_detail_collect_date_day_sel = filter_input(INPUT_POST, 'comiket_detail_collect_date_day_sel');

      
        // 物販
        $inForm->comiket_box_buppan_num_ary = $this->cstm_filter_input_array(INPUT_POST, 'comiket_box_buppan_num_ary', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY, 'rnask');
        $inForm->comiket_box_buppan_ziko_shohin_cd_ary = $this->cstm_filter_input_array(INPUT_POST, 'comiket_box_buppan_ziko_shohin_cd_ary', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY, 'rnask');

        ////////////////////////////////////////////////////////////////////////////////////////////
        // 支払方法
        ////////////////////////////////////////////////////////////////////////////////////////////

        // コンビニ
        $inForm->comiket_convenience_store_cd_sel = filter_input(INPUT_POST, 'comiket_convenience_store_cd_sel');//
        $inForm->comiket_payment_method_cd_sel = filter_input(INPUT_POST, 'comiket_payment_method_cd_sel');//$creditCardForm['comiket_payment_method_cd_sel'];
        
        $calcDataInfoData = $this->calcEveryKindData((array)$inForm);
        $calcDataInfo = $calcDataInfoData["treeData"];
        $calcDataInfoForBuppan = $calcDataInfoData["treeDataForBuppan"];
        //$inForm->delivery_charge = @$calcDataInfo['amount_tax'];
        $inForm->delivery_charge_buppan = @$calcDataInfoForBuppan['amount_tax'];
        

        // クレジットカード
        $inForm->card_number = @$creditCardForm['card_number'];
        $inForm->card_expire_month_cd_sel = @$creditCardForm['card_expire_month_cd_sel'];
        $inForm->card_expire_year_cd_sel = @$creditCardForm['card_expire_year_cd_sel'];
        $inForm->security_cd = @$creditCardForm['security_cd'];

        return $inForm;
    }

    /**
     * 配列から$variable_nameがキーの値を抽出して全角カナに変換
     * @param type $type
     * @param type $variable_name
     * @param type $filter
     * @param type $options
     * @return type
     */
    private function cstm_filter_input_array($type, $variable_name, $filter = FILTER_DEFAULT, $options = null, $mbConvKanaOpt = NULL) {
        $res = filter_input($type, $variable_name, $filter, $options);

        if(empty($res)) {
            return array();
        }

        if(is_array($res) && !empty($mbConvKanaOpt)) {
            $resultList = array();
            foreach($res as $key => $val) {
                $resultList[$key] = mb_convert_kana($val, $mbConvKanaOpt, 'UTF-8');
            }
            $res = $resultList;
        }

        return $res;
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_Bpn001In $inForm 入力フォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($inForm, $db) {
        $errorForm = new Sgmov_Form_Error();

        // 都道府県のリストを取得しておく
        $eventArray = $this->_EventService->fetchEventListWithinTerm($db);
        $eventsubArray = $this->_EventsubService->fetchEventsubListWithinTermByEventId($db, $inForm->event_sel);
        $eventsubInfo = NULL;
        if(!empty($inForm->eventsub_sel)) {
            $eventsubInfo = $this->_EventsubService->fetchEventsubByEventsubId($db, $inForm->eventsub_sel);
        }

        if (filter_input(INPUT_POST, 'hid_timezone_flg') == '1') {
            $errorForm->addError('event_sel', '選択のイベントは受付時間を超過しています。');
        }

        // if($inForm->eventsub_sel == "302" && $inForm->shohin_pattern != "1"){
        
        //     $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_detail_collect_date_year_sel)->isNotEmpty();
        //     if (!$v->isValid()) {
        //         $errorForm->addError('comiket_detail_collect_date', '商品引き渡し日' . $v->getResultMessageTop());
        //     }

        //     $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_detail_collect_date_month_sel)->isNotEmpty();
        //     if (!$v->isValid()) {
        //         $errorForm->addError('comiket_detail_collect_date', '商品引き渡し日' . $v->getResultMessageTop());
        //     }


        //     $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_detail_collect_date_day_sel)->isNotEmpty();
        //     if (!$v->isValid()) {
        //         $errorForm->addError('comiket_detail_collect_date', '商品引き渡し日' . $v->getResultMessageTop());
        //     }
        // }

        // if($inForm->eventsub_sel == "302" && $inForm->bpn_type == "2" && $inForm->shohin_pattern == "2"){
        //     //お名前 姓
        //     $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_personal_name_sei)->isNotEmpty()->isLengthLessThanOrEqualTo(8)->
        //             isNotHalfWidthKana()->isWebSystemNg();
        //     if (!$v->isValid()) {
        //         $errorForm->addError('comiket_personal_name-seimei', 'お名前' . $v->getResultMessageTop());
        //     }

        //     // お名前 名 
        //     $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_personal_name_mei)->isLengthLessThanOrEqualTo(8)->
        //             isNotHalfWidthKana()->isWebSystemNg();
        //     if (!$v->isValid()) {
        //         $errorForm->addError('comiket_personal_name-seimei', 'お名前' . $v->getResultMessageTop());
        //     }


        //     // 電話番号 必須チェック 型チェック WEBシステムNG文字チェック
        //     $comiketTel = @str_replace(array('-', 'ー', '−', '―', '‐', 'ｰ'), "", $inForm->comiket_tel);
        //     $v = Sgmov_Component_Validator::createSingleValueValidator($comiketTel)->isNotEmpty()->isPhoneHyphen();
        //     if (!$v->isValid()) {
        //         $errorForm->addError('comiket_tel', '電話番号' . $v->getResultMessageTop());
        //     } else {
        //         $v = Sgmov_Component_Validator::createSingleValueValidator($comiketTel)->isLengthMoreThanOrEqualTo(8)->isLengthLessThanOrEqualTo(12);
        //         if (!$v->isValid()) {
        //             $errorForm->addError('comiket_tel', '電話番号の数値部分' . $v->getResultMessageTop());
        //         }
        //     }

        //     // メールアドレス 必須チェック 100文字チェック
        //     $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_mail)->isNotEmpty()->isMail()->isLengthLessThanOrEqualTo(100)->isWebSystemNg();
        //     if (!$v->isValid()) {
        //         $errorForm->addError('comiket_mail', 'メールアドレス' . $v->getResultMessageTop());
        //     }

        //     // メールアドレス確認 必須チェック 100文字チェック
        //     $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_mail_retype)->isNotEmpty()->isMail()->isLengthLessThanOrEqualTo(100)->isWebSystemNg();
        //     if (!$v->isValid()) {
        //         $errorForm->addError('comiket_mail_retype', 'メールアドレス確認' . $v->getResultMessageTop());
        //     }

        //     // エラーがない場合は,確認メールアドレス一致チェック
        //     if(!$errorForm->hasError()){
        //         $v = Sgmov_Component_Validator::createMultipleValueValidator(array($inForm->comiket_mail, $inForm->comiket_mail_retype))->isStringComparison();
        //         if (!$v->isValid()) {
        //             $errorForm->addError('comiket_mail_retype', 'メールアドレス確認' . $v->getResultMessageTop());
        //         }
        //     }
        // }


        ////////////////////////////////////////////////////////////////////////////////////////////
        // 物販
        ////////////////////////////////////////////////////////////////////////////////////////////
        if (!$errorForm->hasError()) {

            // エラーがない場合は,物販枚数40以上チェック
            $isShohinErrorFlg = false;

            if($this->checkBuppanRecCount((Array)$inForm)) {

                $buppanErrItem = "";
                $maxBuppan = 40;
                foreach($inForm->comiket_box_buppan_num_ary as $key => $val) {
                    $boxInfo = $this->_ShohinService->fetchShohinById($db, $key);
                      // 数量ラベル
                    if($boxInfo["suryo_flg"] != 1){
                        if(@!empty($val) && ($val > 1 || $val != 1)){
                            Sgmov_Component_Redirect::redirectPublicSsl("/bpn/temp_error");
                            exit;
                        }
                    }

                    // if (@!empty($val) && $maxBuppan < $val) {
                    //     $buppanErrItem .= $boxInfo['name']."、";
                    //     //$isShohinErrorFlg = true;
                    // }

                    // 最大商品数権限
                    //if(!$isShohinErrorFlg){
                    if ((@!empty($val) && is_numeric( $val) && @!empty($boxInfo["max_shohin_count"])) && $boxInfo["max_shohin_count"] <= $val) {
                        $errorForm->addError("comiket_box_buppan_num_ary_".$key, "{$boxInfo['name']}は上限を超えました。");
                        $isShohinErrorFlg = true;
                    }
                    //}
                }
                // if(!@empty($buppanErrItem )){
                //    // $errorForm->addError('comiket_box_buppan_num_ary_max_err', "商品-{$buppanErrItem}は{$maxBuppan}枚まで入力可能です。");
                // }
            }

            if(!$isShohinErrorFlg){
                $this->checkComiketBoxOutInBuppanboundNumAry($db, $inForm, $errorForm, $inForm->comiket_box_buppan_num_ary, "comiket_box_buppan_num_ary", "商品");
            }
        }    

        ////////////////////////////////////////////////////////////////////////////////////////////
        // 支払方法
        ////////////////////////////////////////////////////////////////////////////////////////////

       $this->_checkPaymentMethod($inForm, $errorForm);


        return $errorForm;
    }

    /**
     * お支払い方法の入力チェック
     * @param type $inForm
     * @param type $errorForm
     */
    public function _checkPaymentMethod($inForm, &$errorForm) {
      // if($inForm->comiket_div == self::COMIKET_DEV_INDIVIDUA) { // 個人
            // お支払方法 値範囲チェック
            $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_payment_method_cd_sel)->isIn(array_keys($this->payment_method_lbls));
            if (!$v->isValid()) {
//                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT);
                $errorForm->addError('payment_method', 'お支払い方法' . $v->getResultMessageTop());
            }

            // お支払方法 必須チェック
            $v->isSelected();
            if (!$v->isValid()) {
                $errorForm->addError('payment_method', 'お支払い方法' . $v->getResultMessageTop());
            }
            // if ($inForm->comiket_payment_method_cd_sel === '1') {
            //     // お支払い店舗 必須チェック
            //     $v = Sgmov_Component_Validator::createSingleValueValidator($inForm->comiket_convenience_store_cd_sel)->isSelected()->
            //             isIn(array_keys($this->convenience_store_lbls));
            //     if (!$v->isValid()) {
            //         $errorForm->addError('payment_method', 'お支払い方法' . $v->getResultMessageTop());
            //     }
            // }
       // }
    }

    /**
     * 販売物品の購入入力情報チェック
     * @param type $inForm
     * @param type $errorForm
     * @param type $targetList
     * @param type $targetClassName
     * @param type $errMsgBuppan
     */
    protected function checkComiketBoxOutInBuppanboundNumAry($db, $inForm, &$errorForm, $targetList, $targetClassName, $errMsgBuppan, $isEmptyCheck = true) {
        $result = array(
            "errflg" => FALSE,
            "errData" => array(),
        );

        $emptyCount = 0;
        $notEmptyCount = 0;
        $errorFlg = FALSE;
        $expiryFlg = false;

        $i = 1;
        $totalCnt = count($targetList);
        $shohinList = $this->_ShohinService->fetchShohinByEventSubIdWithInTerm($db, $inForm->eventsub_sel, "2");
        $getAllShohin = $this->filterShohinResult($shohinList);

        foreach($targetList as $key => $val) {
            // 0 は法人で使用するためとばす
            if($key == "0") {
                continue;
            }
            $v = Sgmov_Component_Validator::createSingleValueValidator($val)->isNotEmpty();

            // if ($v->isValid()) {
            //     $notEmptyCount++;
            // }

            $soldOutFlg = false;
            $result = $this->_ShohinService->fetchShohin($db, $inForm->eventsub_sel, $inForm->bpn_type, $inForm->shohin_pattern, $key);
            foreach ($result as $resultVal) {
                $existsPlusCurrentCnt = $resultVal["count"] + $val;
                if($val != "0" && ($resultVal["count"] > $resultVal["max_shohin_count"] || $resultVal["max_shohin_count"] == "0")){
                    $soldOutFlg = true;
                    $emptyCount--;
                    $errorForm->addError($targetClassName."_".$key, "{$getAllShohin[$key]}は完売しました。");
                }elseif($val != "0" && ($resultVal["max_shohin_count"] != 0 && $existsPlusCurrentCnt > $resultVal["max_shohin_count"])){
                    $errorFlg = true;
                    $soldOutFlg = true;
                    $errorForm->addError($targetClassName."_".$key, "{$getAllShohin[$key]}は上限を超えています。");
                }
            }

            // 申込期間チェック
            if(!$soldOutFlg){
                $expiryFlg = false;
                $check = $this->_ShohinService->checkShohinTerm($db, $key);
                if($val != "0" && (@empty($check) || $check["count"] == 0)){
                    $errorFlg = true;
                    $expiryFlg = true;
                    $errorForm->addError($targetClassName."_".$key, "{$getAllShohin[$key]}の申込期間範囲外です。");
                }
            }

            if(!$soldOutFlg && !$expiryFlg){
                if($val == "0" ||  $val == ""){
                    $emptyCount++;
                }

                // 数値 チェック
                $v = Sgmov_Component_Validator::createSingleValueValidator($val)->isInteger(1)->isWebSystemNg();
                if (!$v->isValid() && $val != "0") {
                    $errorFlg = TRUE;
                    $errorForm->addError($targetClassName."_".$key, "{$getAllShohin[$key]}の入力値を確認してください。（数値のみ）");
                }
            }

            $i++;
        }

        $isErrFlg2 = FALSE;
        if($errorFlg) {
            //$errorForm->addError($targetClassName, "{$errMsgBuppan}の入力値を確認してください。（数値のみ）");
            $isErrFlg2 = TRUE;
        }


        if($totalCnt == $emptyCount){
            $errorForm->addError($targetClassName, "{$errMsgBuppan}-数量を入力してください。");
            $isErrFlg2 = TRUE;
        }

        // if(0 == $notEmptyCount && $isEmptyCheck) {
        //     $errorForm->addError($targetClassName, "{$errMsgBuppan}-枚数を入力してください。");
        //     $isErrFlg2 = TRUE;
        // }

        if(!$isErrFlg2) {
            if(!empty($inForm->delivery_charge_buppan)
                    && intval($inForm->delivery_charge_buppan) > 999999) {
                $errorForm->addError($targetClassName, "{$errMsgBuppan}-物販料金は、￥999,999までが取り扱い金額となります。");
            }

            if($inForm->comiket_payment_method_cd_sel === '3') { // 電子マネー
                if(!empty($inForm->delivery_charge_buppan)
                        && intval($inForm->delivery_charge_buppan) > 10000) {
                    if(!array_key_exists($targetClassName, $errorForm->_errors)) {
                        $errorForm->addError($targetClassName, "{$errMsgBuppan}-電子マネーの場合、物販料金は￥10,000までが取り扱い金額となります。");
                    }
                }
            }
        }

        if(5 <= $notEmptyCount) {
            $errorForm->addError($targetClassName, "{$errMsgBuppan}の入力は４つまでです。");
            $isErrFlg2 = TRUE;
        }
    }
}