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
// イベント識別子(URLのpath)はマジックナンバーで記述せずこの変数で記述してください
$dirDiv=Sgmov_Lib::setDirDiv(dirname(__FILE__));
Sgmov_Lib::useView($dirDiv.'/Common');
Sgmov_Lib::useView($dirDiv.'/CheckInput');
Sgmov_Lib::useForms(array('Error', 'EveSession', 'Eve001In', 'Eve002In'));
Sgmov_Lib::useServices(array('HttpsZipCodeDll'));
/**#@-*/
/**
 * イベント手荷物受付サービスのお申し込み入力情報をチェックします。
 * @package    View
 * @subpackage UNA
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Unf_CheckInput2 extends Sgmov_View_Unf_CheckInput {

    // 識別子
    protected $_DirDiv;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {

        // 識別子のセット
        $this->_DirDiv = Sgmov_Lib::setDirDiv(dirname(__FILE__));

        parent::__construct();
    }

    /**
     *
     * @param type $inForm
     * @param type $errorForm
     */
    public function _redirectProc($inForm, $errorForm) {
        if ($errorForm->hasError()) {
            Sgmov_Component_Redirect::redirectPublicSsl('/'.$this->_DirDiv.'/input2');
        }

        // 個人の場合は、クレジット・コンビニ支払で表示画面切り替え
        switch ($inForm->comiket_payment_method_cd_sel) {
            case '1': // コンビニ
                Sgmov_Component_Redirect::redirectPublicSsl('/'.$this->_DirDiv.'/confirm');
                break;
            case '2': // クレジット
                Sgmov_Component_Redirect::redirectPublicSsl('/'.$this->_DirDiv.'/credit_card');
                break;
            default:
                Sgmov_Component_Redirect::redirectPublicSsl('/'.$this->_DirDiv.'/confirm');
                break;
        }
    }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_Pcr001In $inForm 入力フォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($inForm, $db) {

        $errorForm = new Sgmov_Form_Error();

        if (filter_input(INPUT_POST, 'hid_timezone_flg') == '1') {
            $errorForm->addError('event_sel', '選択のイベントは受付時間を超過しています。');
        }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 搬出
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $this->_checkInbound($inForm, $errorForm);

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 支払方法
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $this->_checkPaymentMethod($inForm, $errorForm);

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        if (!$errorForm->hasError()) {

            if($inForm->comiket_detail_type_sel == "2" || $inForm->comiket_detail_type_sel == "3") { // 搬出の場合
                $prefectures = $this->_PrefectureService->fetchPrefectures($db);
                $key = array_search($inForm->comiket_detail_inbound_pref_cd_sel, $prefectures['ids']);
                $inForm->comiket_detail_inbound_address;
                $inForm->comiket_detail_inbound_building;
//                $receive = $this->_HttpsZipCodeDll->_execYubin7(array(
//                    "szZipCode" => $inForm->comiket_detail_inbound_zip1.$inForm->comiket_detail_inbound_zip2,
//                    "szAddress" => $prefectures['names'][$key] . $inForm->comiket_detail_inbound_address . $inForm->comiket_detail_inbound_building,
//                    "szTel" => $inForm->comiket_detail_inbound_tel
//                ));

                // 復路用時間帯対応（カンマ区切りでコード、名称を持っているため）
                $inboundColleTimeCd = '';
                if ($inForm->comiket_detail_inbound_collect_time_sel != NULL && $inForm->comiket_detail_inbound_collect_time_sel != '') {
                    $arrInbouCollectTimeCd = explode(',', $inForm->comiket_detail_inbound_collect_time_sel);
                    $inboundColleTimeCd = $arrInbouCollectTimeCd[0];
                }

                $outboundColleTimeCd = '';
                if ($inForm->comiket_detail_inbound_delivery_time_sel != NULL && $inForm->comiket_detail_inbound_delivery_time_sel != '') {
                    $arrOutbouCollectTimeCd = explode(',', $inForm->comiket_detail_inbound_delivery_time_sel);
                    $outboundColleTimeCd = $arrOutbouCollectTimeCd[0];
                }

                $receive = $this->_getAddress($inForm->comiket_detail_inbound_zip1.$inForm->comiket_detail_inbound_zip2
                        , $prefectures['names'][$key] . $inForm->comiket_detail_inbound_address . $inForm->comiket_detail_inbound_building);
//ｓ
                if (empty($receive['ShopCodeFlag'])) {
                    $errorForm->addError('comiket_detail_inbound_zip', '搬出-お届け先住所の入力内容をお確かめください。');
                } elseif (!empty($receive['ExchangeFlag'])) {
                    $errorForm->addError('comiket_detail_inbound_zip', '搬出-お届け先住所は集荷・配達できない地域の恐れがあります。');
                } elseif (!empty($receive['TimeZoneFlag'])
                        && ((!empty($inForm->comiket_detail_inbound_collect_time_sel) && $inboundColleTimeCd !== '00')
                            || (!empty($inForm->comiket_detail_inbound_delivery_time_sel) && $outboundColleTimeCd !== '00'))) {
                    $errorForm->addError('comiket_detail_inbound_delivery_date', '搬出-お届け先住所は時間帯指定できない地域の恐れがあります。');
                } elseif (!empty($receive['RelayFlag'])) {
                    $errorForm->addError('comiket_detail_inbound_zip', '搬出-お届け先住所は配達できない地域の恐れがあります。');
                }
            }
        }

        return $errorForm;
    }
}