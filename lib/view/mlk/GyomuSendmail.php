<?php
/**
 * イベント輸送サービスのメールアドレスを変更します。
 * @package    /lib/view/eve
 * @author     Juj-Yamagami(SP)
 * @copyright  2018 Sp Media-Tec CO,.LTD. All rights reserved.
 */

/**
 * #@+
 * include files
 */
require_once dirname ( __FILE__ ) . '/../../Lib.php';
Sgmov_Lib::useAllComponents ( FALSE );
Sgmov_Lib::useView('mlk/Common');
Sgmov_Lib::useForms(array('Error'));
Sgmov_Lib::useServices(array('Comiket', 'ComiketDetail', 'ComiketBox', 'CenterMail'));
Sgmov_Lib::useImageQRCode();

/**
 * #@-
 */
class Sgmov_View_Mlk_GyomuSendmail extends Sgmov_View_Eve_Common {

    /**
     * コミケ申込データサービス
     * @var Sgmov_Service_Comiket
     */
    private $_Comiket;

    /**
     * コミケ申込明細データサービス
     * @var Sgmov_Service_Comiket
     */
    private $_ComiketDetail;

    /**
     * コミケ申込宅配データサービス
     * @var Sgmov_Service_Comiket
     */
    private $_ComiketBox;

    /**
     * 宅配サービス
     * @var type
     */
    private $_BoxService;

    /**
     * 拠点メールアドレスサービス
     * @var Sgmov_Service_CenterMail
     */
    public $_centerMailService;

    /**
     * ユーザーID
     *
     * @var string
     */
    public $_wsUserId;

    /**
     * パスワード
     *
     * @var string
     */
    public $_wsPassWord;

    public $comiketData;

    //--------------------------------------------------------------------------
    // APIの戻る区分
    //--------------------------------------------------------------------------
    const STATUS_OK = '0';//正常
    const STATUS_NG = '1';//異常

    const TYPE_FUKURO = 2;//復路
    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        parent::__construct();
        $this->_BoxService          = new Sgmov_Service_Box();

        $this->_Comiket             = new Sgmov_Service_Comiket();
        $this->_ComiketDetail       = new Sgmov_Service_ComiketDetail();
        $this->_ComiketBox          = new Sgmov_Service_ComiketBox();

        $this->_centerMailService   = new Sgmov_Service_CenterMail();

        $this->_Comiket->setTrnsactionFlg(FALSE);
        $this->_ComiketDetail->setTrnsactionFlg(FALSE);
        $this->_ComiketBox->setTrnsactionFlg(FALSE);

        $this->_wsUserId = Sgmov_Component_Config::getWsUserId ();
        $this->_wsPassWord = Sgmov_Component_Config::getWsPassword ();
    }

    /**
	 * 処理
	 */
    public function executeInner() {
        Sgmov_Component_Log::info('【ミルクラン・業務メール送信：開始】');
        $ret = ['result' => self::STATUS_OK, 'error' => ''];
        
        // DBコネクション
        $db = Sgmov_Component_DB::getAdmin();

        $params = $_GET;
        Sgmov_Component_Log::debug("####### Params #####");
        Sgmov_Component_Log::debug($params);
        
        
        $validate = $this->validateParams($db, $params);

        if ($validate['result'] == self::STATUS_NG) {
            Sgmov_Component_Log::info($validate);
            Sgmov_Component_Log::info('【ミルクラン・業務メール送信：異常終了（エラーチェック）】');
            echo json_encode($validate);
            exit();
        }
        $data = $this->buildMailData($db);
		$type = $params['type'];
        $sendTo = $data['mail'];
        $result = $this->sendMlkGyomuMail($sendTo, $data, $type);

//        if ($result) {
//            $admResult = $this->sendMlkGyomuAdminMail($db, $data, $type);
//            if (!$admResult) {
//                Sgmov_Component_Log::info('【ミルクラン・業務メール送信：ユーザーメールに送信正常、管理者にメール送信異常となります。】');
//                $ret = ['result' => self::STATUS_NG, 'error' => 'ユーザーメールに送信正常、管理者にメール送信異常となります。'];
//            }
//        } else {
//            Sgmov_Component_Log::info('【ミルクラン・業務メール送信：ユーザーメールに送信異常となります。】');
//            $ret = ['result' => self::STATUS_NG, 'error' => 'ユーザーメールに送信異常となります。'];
//        }

        Sgmov_Component_Log::info('【ミルクラン・業務メール送信終了】');
        echo json_encode($ret);
		exit();
	}

    private function validateParams($db, $params) {

        if(!empty($params)) {
            // Validate empty or not exist param
            if(!isset($params['userid']) || !isset($params['password'])){
                return ['result' => self::STATUS_NG, 'error' => '（認証エラー）ユーザーIDやパスワードが未設定です。'];
            }
            if($params['userid'] != $this->_wsUserId || $params['password'] != $this->_wsPassWord){
                return ['result' => self::STATUS_NG, 'error' => '（認証エラー）ユーザーIDやパスワードが間違います。'];
            }
            
            if(empty($params['toiawase_no'])){
                return ['result' => self::STATUS_NG, 'error' => '（エラーチェック）問合せ番号が必須です。'];
            }
            if(empty($params['type'])){
                return ['result' => self::STATUS_NG, 'error' => '（エラーチェック）メールタイプが必須です。'];
            }

            // Validate toiawase_no
            if(strlen($params['toiawase_no']) > 20){
                return ['result' => self::STATUS_NG, 'error' => '（エラーチェック）問合せ番号の桁数が20桁まで指定してください。'];
            }
            $this->comiketData = $this->getComiketByToiawaseNo($db, $params['toiawase_no']);
            if (empty($this->comiketData)) {
                return ['result' => self::STATUS_NG, 'error' => "（エラーチェック）[{$params['toiawase_no']}が存在していません]。"];
            }

            // Validate type
            if(strlen($params['type']) != 1 || ($params['type'] != '1' && $params['type'] != '2')){
                return ['result' => self::STATUS_NG, 'error' => '（エラーチェック）メールタイプは「1」又は「2」を設定していください。'];
            }
            // Everything is OK
            return ['result' => self::STATUS_OK, 'error' => ''];

        } else {
            return ['result' => self::STATUS_NG, 'error' => 'リクエストURLが間違います。'];
        }
    }

    /**
     * コミケ申込データを取得
     * @param object $db
     * @param string $toiawase_no
     * @return type
     */

    private function getComiketByToiawaseNo($db, $toiawase_no) {
        $comiket = $this->_ComiketDetail->getComiketByToiawaseNo($db, $toiawase_no);
        return $comiket;
    }

    private function buildMailData($db) {
        $comiket = $this->comiketData[0];
        $week = ['日', '月', '火', '水', '木', '金', '土'];

        // 【お預かり/お届け日・Receipt/Delivery Date】
        $objCollectDate = new DateTime($comiket['collect_date']);
        $extWeek = date('w',strtotime($comiket['collect_date']));
        $collectDate = $objCollectDate->format('Y年m月d日')."(".$week[$extWeek].")";

        // 【フライト日時/Flight date and time】
        $deliveryDateStr = '';
        $flightName = '';
        $comiketDetail = $this->_ComiketDetail->fetchComiketDetailByComiketIdType($db, $comiket['id'], self::TYPE_FUKURO);
        if ($comiketDetail[0]['mlk_hachaku_type_cd'] == '1') {
            $objDeliveryDate = new DateTime($comiket['delivery_date']);
            $extWeek = date('w',strtotime($comiket['delivery_date']));
            $deliveryDate = $objDeliveryDate->format('Y年m月d日')."(".$week[$extWeek].") ";
            $objDeliveryStTime = new DateTime($comiketDetail[0]['delivery_st_time']);
            $deliveryStTime = $objDeliveryStTime->format("H:i");
            //$deliveryDateStr = "【搭乗日時/Flight date and time】" . $deliveryDate .$deliveryStTime;
            $flightName = $comiketDetail[0]['mlk_bin_nm']; //画面の便名
            $deliveryDateStr = "\n" ."【搭乗日時/Flight date and time】" . $deliveryDate .$deliveryStTime . "\n" . "【便名/Flight number】".$flightName;
        }

        // 【サービス選択/Service choice】
        $service = $this->comiket_detail_service_lbls[1];
        
        // 【宅配数量/Home delivery quantity】
        $num_area = '';
        $comiket_box_list = $this->_ComiketBox->fetchComiketBoxDataListByIdAndType($db, $comiket['id'], $comiket['detail_type']);
        if (!empty($comiket_box_list)) {
            $boxId = $comiket_box_list[0]['box_id'];
            $boxInfo = $this->_BoxService->fetchBoxById($db, $boxId);
            $num_area = "{$boxInfo['name']}/Size {$boxInfo['size_display']}［1 個］";
        }

        // 【集荷先名/Collection name】
        $comiketDetailCd = (!empty($comiket['cd']) && mb_strlen($comiket['cd']) > 8) ? substr($comiket['cd'], 0, 8) : '';
        $hachakutenMst = $this->_HachakutenService->fetchHachakutenByCode($db, $comiketDetailCd);
        $collectionName = (!empty($hachakutenMst)) ? $hachakutenMst['name_jp'] . '(' . $hachakutenMst['name_en'] . ')' : '';

        // 【お届け先名/Addressee name】
        $addressName = '';
        // If substr($comiket['cd'], 0, 8) = $comiket['mlk_hachaku_shikibetu_cd'], so no need to query again
        if ($comiketDetailCd == $comiketDetail[0]['mlk_hachaku_shikibetu_cd']) {
            $addressName = $collectionName;
        } else {
            $hachakutenMst = $this->_HachakutenService->fetchHachakutenByCode($db, $comiketDetail[0]['mlk_hachaku_shikibetu_cd']);
            $addressName = (!empty($hachakutenMst)) ? $hachakutenMst['name_jp'] . '(' . $hachakutenMst['name_en'] . ')' : '';
        }

        $data = [
            'pref_id' => $comiket['pref_id'],
            'mail' => $comiket['mail'],
            'comiket_id' => sprintf('%010d', $comiket['id']), // 【申込み番号/Registed number】
            'toiawase_no' => $comiket['toiawase_no'], // 【問合せ番号/Inquiry number】
            'tag_id' => $comiket['cd'], // 【タグID/Tag ID】
            'personal_name_seimei' => $comiket['staff_sei'] . " " . $comiket['staff_mei'], // 【お申込者/Applicant】
            'collect_date' => $collectDate, // 【お預かり/お届け日・Receipt/Delivery Date】
            'name' => $collectionName, // 【集荷先名/Collection name】
            'address' => $addressName, // 【お届け先名/Addressee name】
            'delivery_date' => $deliveryDateStr, // 【搭乗日時/Flight date and time】
            'service' => $service, // 【サービス選択/Service choice】
            'num_area' => $num_area, // 【宅配数量/Home delivery quantity】
            'note' => $comiket['note'], // 【備考/Note】
            'payment_method' => "クレジットカード/Credit Card", // 【お支払方法/Payment method】
            'amount' => '\\' . number_format($comiket['amount']), // 【金額（税抜）/Amount (excluding tax)】
            'amount_tax' => '\\' . number_format($comiket['amount_tax']), // 【金額（税込）/Amount (tax included)】
        ];

        return $data;
    }
}