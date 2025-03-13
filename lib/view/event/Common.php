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
Sgmov_Lib::useAllComponents();
Sgmov_Lib::useServices(array('Eventsub', 'Event', 'AppCommon', 'Yubin', 'SocketZipCodeDll'));
Sgmov_Lib::useView('Public');


/**#@-*/

 /**
 * お問い合わせフォームの共通処理を管理する抽象クラスです。
 * @package    View
 * @subpackage PIN
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Event_Common extends Sgmov_View_Public {

    /**
     * 機能ID
     */
    const FEATURE_ID = 'EVENT';
    const REGISTER_ID = 'EVE_REGISTER';
    const LOGIN_ID = 'EVENT_LOGIN';
    const RESET_PASS_ID = 'EVE_RESET_PASS';
    const UPDATE_INFO_ID = 'EVE_UPDATE_INFO_ID';
    const PASS_CHANGE_ID = 'EVE_PASS_CHANGE_ID';
    const ROBOT_CHECK_ID = 'EVE_ROBOT_CHECK_ID';
    
    //reCaptchar site key(社内用)
    //const SITE_KEY = '6LfNr_cdAAAAAKD_VPETloRjmIVuX1R92_-8jwAB';
    //reCaptchar site key(本番)
    const SITE_KEY = '6LfNr_cdAAAAAKD_VPETloRjmIVuX1R92_-8jwAB';

    /**
     * EVENT001の画面ID
     */
    const GAMEN_ID_EVENT001 = 'EVENT001';
    
    /**
     * EVENT009の画面ID
     */
    const GAMEN_ID_EVENT009 = 'EVENT009';
    
    /**
     * EVENT002の画面ID
     */
    const GAMEN_ID_EVENT002 = 'EVENT002';
    
     /**
     * 共通サービス
     * @var Sgmov_Service_AppCommon
     */
    private $_appCommon;
    
    /**
     * イベントサービス
     * @var Sgmov_Service_Event
     */
    private $_EventService;

    /**
     * イベントサービス
     * @var Sgmov_Service_Eventsub
     */
    private $_EventsubService;
    
    /**
     * 郵便番号DLLサービス
     * @var Sgmov_Service_SocketZipCodeDll
     */
    protected $_SocketZipCodeDll;
    
    
    public function __construct() {
        $this->_appCommon             = new Sgmov_Service_AppCommon();
        $this->_EventService          = new Sgmov_Service_Event();
        $this->_EventsubService       = new Sgmov_Service_Eventsub();
        
        $this->_SocketZipCodeDll      = new Sgmov_Service_SocketZipCodeDll();
    }
    //④有効なイベントがない時、
    /**
     * redirectWhenEventInvalid 
     *
     * @param [type] $db
     * @param String $shikibetsushi
     * @return Array
     */
    public function redirectWhenEventInvalid($db, $shikibetsushi) {
        $litEventValid = $this->_EventsubService->getListEventValid($db, $shikibetsushi);
        $securityPatterns = array("1", "2", "3");
        if (!empty($litEventValid)) {
            //GiapLN update task security_patern is NULL => redirect to errors forms 
            if (empty($litEventValid['security_pattern']) ||!in_array($litEventValid['security_pattern'], $securityPatterns)) {
                $title = '会員対象外';
                $eventCode = $shikibetsushi;
                $message = "このイベントは会員機能が使用できません。";
                Sgmov_Component_Redirect::redirectPublicSsl("/{$eventCode}/error?t={$title}&m={$message}");
                exit;
            }
            $_SESSION[self::FEATURE_ID]['event_name'] = $shikibetsushi;
            $_SESSION[self::FEATURE_ID]['event_id'] = $litEventValid['event_id'];
            $_SESSION[self::FEATURE_ID]['eventsub_id'] = $litEventValid['id'];
            $_SESSION[self::FEATURE_ID]['security_patten'] = $litEventValid['security_pattern'];
        } else {
            //公開開始前を取得
            $eventInTheFuture = $this->_EventsubService->getEventInTheFuture($db, $shikibetsushi);
            if (!empty($eventInTheFuture)) {
                //GiapLN update task security_patern is NULL => redirect to errors forms 
                if (empty($eventInTheFuture['security_pattern']) ||!in_array($eventInTheFuture['security_pattern'], $securityPatterns)) {
                    $title = '会員対象外';
                    $eventCode = $shikibetsushi;
                    $message = "このイベントは会員機能が使用できません。";
                    Sgmov_Component_Redirect::redirectPublicSsl("/{$eventCode}/error?t={$title}&m={$message}");
                    exit;
                }
                $title = '公開開始前です';
                $eventCode = $shikibetsushi;
                $eventInfo = $this->_EventService->fetchEventInfoByEventId($db, $eventInTheFuture['event_id']);
                //$eventName = $eventInfo['name'];
                $eventName = ($shikibetsushi === 'eve' || $shikibetsushi === 'evp') ? $eventInfo['name']. $eventInTheFuture['name'] : $eventInfo['name'];
                $departureFrTime = $eventInTheFuture['departure_fr_time'];
                $departureFrTime = str_replace('-', '/', $departureFrTime);
                $message = "{$eventName}のお申込は {$departureFrTime} に開始します。";
                Sgmov_Component_Redirect::redirectPublicSsl("/{$eventCode}/error?t={$title}&m={$message}");
                exit;
            }
            //公開終了を取得	
            $eventExpiration = $this->_EventsubService->getEventLastExpiration($db, $shikibetsushi);
            if (!empty($eventExpiration)) {
                //GiapLN update task security_patern is NULL => redirect to errors forms 
                if (empty($eventExpiration['security_pattern']) || !in_array($eventExpiration['security_pattern'], $securityPatterns)) {
                    $title = '会員対象外';
                    $eventCode = $shikibetsushi;
                    $message = "このイベントは会員機能が使用できません。";
                    Sgmov_Component_Redirect::redirectPublicSsl("/{$eventCode}/error?t={$title}&m={$message}");
                    exit;
                }
                
                $title = '公開終了しています';
                $eventCode = $shikibetsushi;
                $eventInfo = $this->_EventService->fetchEventInfoByEventId($db, $eventExpiration['event_id']);
                //$eventName = $eventInfo['name'];
                $eventName = ($shikibetsushi === 'eve' || $shikibetsushi === 'evp') ? $eventInfo['name']. $eventExpiration['name'] : $eventInfo['name'];
                $arrivalToTime = isset($eventExpiration['arrival_to_time']) ? $eventExpiration['arrival_to_time'] : $eventExpiration['arrival_fr'];
                $arrivalToTime = str_replace('-', '/', $arrivalToTime);
                $message = urlencode("{$eventName}のお申込は {$arrivalToTime} をもって終了しました。");
                Sgmov_Component_Redirect::redirectPublicSsl("/{$eventCode}/error?t={$title}&m={$message}");
                exit;
            }
        }
    }
    
    //⑤URLのパラメータ確認
    /**
     * redirectForUrlInvalid 
     *
     * @param [type] $db
     * @param String $shikibetsushi
     * @return Array
     */
    public function redirectForUrlInvalid() {
        if (!isset($_GET['event_nm'])) {
            if (isset($_SESSION[self::LOGIN_ID])) {
                unset($_SESSION[self::LOGIN_ID]);
            }
            Sgmov_Component_Redirect::redirectPublicSsl("/404.html");
        } else {
            $shikibetsushi = $_GET['event_nm'];
            $db = Sgmov_Component_DB::getPublic();
            $eEvent = $this->_EventService->getEventWithShikibetsushi($db, $shikibetsushi);
            
            if (empty($eEvent)) {
                if (isset($_SESSION[self::LOGIN_ID])) {
                    unset($_SESSION[self::LOGIN_ID]);
                }
                Sgmov_Component_Redirect::redirectPublicSsl("/404.html");
            }
        }
    }
    
    /**
     * 一時パスワードを作成する
     *
     * @return string 一時パスワード
     */
    public function generateTempPass()
    {
        return
            $this->getRandomString(5, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"). // アルファベット
            $this->getRandomString(2, "0123456789"). // 数値
            $this->getRandomString(1, "!#%()*+,-./:;=?@[]^_`{|}~$\"\\"); // 記号
    }

    /**
     * ランダムな文字列を生成する。
     *
     * @param  int    $nLengthRequired 必要な文字列長。省略すると 8 文字
     * @param  string $charList        使用文字列リスト
     * @return string ランダムな文字列
     */
    function getRandomString(
        $nLengthRequired=8,
        $charList="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ")
    {
        mt_srand();
        $sRes = "";
        for ($i = 0; $i < $nLengthRequired; $i++)
        {
            $sRes .= $charList {
                mt_rand(0, strlen($charList) - 1)
            };
        }

        return $sRes;
    }
    
    /**
     * パスワードを確認する
     *
     * @param  string $str パスワード
     * @return string
     */
    public function checkpas($str)
    {
        //$msgError = "パスワードは8文字以上で英大文字、英小文字、数字の全てを含むパスワードを設定して下さい。";
        // 文字数は8文字以上12文字以内か
        if ((strlen($str) < 8) or (strlen($str) > 50)) {
            //return self::PAS_ERR_LENGTH;
            return "は8文字以上、50文字以内です。";
            //return $msgError;
        }
        if (!preg_match("/[A-Z]/", $str)) { 
            //return self::PAS_ERR_ALPHA;
            return "は英大文字が含まれていません。";
            //return $msgError;
        }
        // 半角英字が含まれているか
        if (!preg_match("/[a-z]/", $str)) { 
            //return self::PAS_ERR_ALPHA;
            return "は半角英字が含まれていません。";
            //return $msgError;
        }
        // 半角数字が含まれているか
        if (!preg_match("/[0-9]/", $str)) {
            //return self::PAS_ERR_INT;
            return "は半角数字が含まれていません。";
            //return $msgError;
        }
        // 半角記号が含まれているか
//        if (!preg_match("/[!#%()*+,-.\/:;=?@\[\]^_`{|}~$\"\\\\]/", $str)) {
//            //return self::PAS_ERR_SYMBOL;
//            return "は半角記号が含まれていません。";
//        }
        // 許可された文字以外が使われていないか
        if (!preg_match("/^[a-zA-Z0-9!#%()*+,-.\/:;=?@\[\]^_`{|}~$\"\\\\]+$/", $str)) {
            //return self::PAS_ERR_ALLOW;
            return "は許可されていない文字が入力されています。";
        }

        return "";
    }
    
    public function isMobile1() {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }
    
    /**
     * プルダウンを生成し、HTMLソースを返します。
     * TODO pre/Inputと同記述あり
     *
     * @param $cds コードの配列
     * @param $lbls ラベルの配列
     * @param $select 選択値
     * @return 生成されたプルダウン
     */
    public static function _createPulldown($cds, $lbls, $select, $flg = null, $date = null) {

        $html = '';

        if (empty($cds)) {
            return $html;
        }

        $count = count($cds);
        for ($i = 0; $i < $count; ++$i) {

            // $flg（受付時間超過フラグ）があるならプロパティ文字列を作成する
            $timeover = '';
            if (!empty($flg)) {
                $timeover = ' timeoverflg="' .$flg[$i] . '"';
            }

            // $date（受付終了日付）があるならプロパティ文字列を作成する
            $timeoverDt = '';
            if (!empty($date)) {
                $timeoverDt = ' timeoverdate="' .$date[$i] . '"';
            }

            if ($select === $cds[$i]) {
                $html .= '<option value="' . $cds[$i] . '" selected="selected"' . $timeover . $timeoverDt . '>' . $lbls[$i] . '</option>' . PHP_EOL;
            } else {
                $html .= '<option value="' . $cds[$i] . '"' . $timeover . $timeoverDt . '>' . $lbls[$i] . '</option>' . PHP_EOL;
            }
        }

        return $html;
    }
    
    public static function getChkD($param) {
        // 顧客コードを配列化
        $param2 = str_split($param);


        // 掛け算数値配列（固定らしいのでベタ書き）s
        $intCheck = array(
            0 => 4,
            1 => 3,
            2 => 2,
            3 => 9,
            4 => 8,
            5 => 7,
            6 => 6,
            7 => 5,
            8 => 4,
            9 => 3,
        );

        $total = 0;
        for ($i = 0; $i < count($intCheck); $i++) {
            $total += $param2[$i] * $intCheck[$i];
        }
        
        return $total;
    }
    
    
    public static function getChkD2($param) {

        $target = intval($param);
        $result = $target % 7;
        return $result;
    }
    
    /**
     * 住所情報を取得します。
     * @param type $zip
     * @return type
     */
    public function _getAddressByZip($zip){
        try{
            $receive = $this->_SocketZipCodeDll->searchByZipCode($zip);

            if (empty($receive)) {
                // 接続に失敗した場合はfalseが返ってくるのでリターンする
                return [];
            }

            $hasKenName = isset($receive['KenName']) && !empty($receive['KenName']);
            $hasCityName = isset($receive['CityName']) && !empty($receive['CityName']);
            $hasTownName = isset($receive['TownName']) && !empty($receive['TownName']);

            if (!$hasKenName || !$hasCityName || !$hasTownName) {
                return [];
            }

            return [
                'kenName'  => $receive['KenName'],
                'cityName' => $receive['CityName'],
                'townName' => $receive['TownName'],
            ];
        } catch (\Exception $ex) {
        }

        return [];
    }
    
    /**
     * 住所情報を取得します。
     * @param type $zip
     * @param type $address
     * @return type
     */
    public function _getAddress($zip, $address) {
        return $this->_SocketZipCodeDll->searchByAddress($address, $zip);
    }
    
    public function checkEventNameInUrl($inqcase) {
        $sessionEventNm = $_SESSION[self::FEATURE_ID]['event_name'];
        $sessionEventNm = strtoupper($sessionEventNm);

        if (!isset($_SESSION[$sessionEventNm.'_LOGIN']['email'])) {
            //remove data session
            unset($_SESSION[self::LOGIN_ID]);
            unset($_SESSION[self::FEATURE_ID]);
            $arrEventNm = ['EVE','DSN','EVP','GMM','MDR','FMT','NEN','TME','TMS','SSO','RMS','ZZY','MSB'];
            foreach ($arrEventNm as $eventNm) {
                if (isset($_SESSION[$eventNm.'_LOGIN'])) {
                    unset($_SESSION[$eventNm.'_LOGIN']);
                }
            }
            Sgmov_Component_Redirect::redirectPublicSsl('/event/login?event_nm='.$inqcase);
        }
    }
    
    /**
     * GETパラメータを取得します。
     *
     * @param none
     * @return $_GET['event_nm']
     */
    protected function _parseGetParameter() {

        $retParam = array();
        if (!isset($_GET['event_nm'])) {
            return NULL;
        } else {
            return strtolower($_GET['event_nm']);
        }
    }
    
    public function checkReqDate($db, $comiketId) {
        $isEnable = true;
        $comiketInfo = $this->_ComiketService->fetchComiketById($db, $comiketId);
        $comiketDetailList = $this->_ComiketDetailService->fetchComiketDetailByComiketId($db, $comiketId);
       
        //②del_flg!=0 ⇒ disable
        if ($comiketInfo['del_flg'] == '1' || $comiketInfo['del_flg'] == '2') {
            $isEnable = false;
        }
        //③(send_result!=3 or batch_status !=4) and payment_method_cd !=1 ⇒ disable
        if ($isEnable == true && ($comiketInfo['send_result'] != '3' || $comiketInfo['batch_status'] != '4') && $comiketInfo['payment_method_cd'] != '1') {
            $isEnable = false;
        }
                    
        if ($isEnable) {
            foreach ($comiketDetailList as $key => $comiketDetailInfo) {
                //①no_chg_flg　=１ ⇒disable 
                if ($comiketDetailInfo['no_chg_flg'] == '1') {
                    $isEnable = false;
                    break;
                }
                if($comiketDetailInfo['type'] == '1') { // 往路
                    /////////////////////////////////////////////////////////////////////////////////////////////
                    // 各地域ごとの締切日チェック
                    /////////////////////////////////////////////////////////////////////////////////////////////
                    $eveSubData = $this->_EventsubService->fetchEventsubByEventsubId($db, $comiketInfo['eventsub_id']);
                    $dateChNow = (new DateTime());
                    //⑤ (eventsub.departure_fr <= now() <= eventsub.departure_to) == false ⇒disable
                    $departureFr = $eveSubData['departure_fr'];
                    $departureTo = $eveSubData['departure_to'];
                    $departureFr = new DateTime($departureFr);
                    $departureTo = new DateTime($departureTo);
                    
                    if (!($departureFr->format('Y-m-d H:i:s') <= $dateChNow->format('Y-m-d H:i:s') && $dateChNow->format('Y-m-d H:i:s') <= $departureTo->format('Y-m-d H:i:s'))) {
                        $isEnable = false;
                        break;
                    }

                    $chakuJis2 = substr($eveSubData['jis5cd'], 0, 2);
                    $hatsuJis2 = $comiketDetailInfo['pref_id'];
                    $outBoundUnCollectCalInfo = $this->_OutBoundCollectCal->fetchOutBoundCollectCalByHaChaku($db, $comiketInfo['eventsub_id'], $hatsuJis2, $chakuJis2);
                    
                    $dateChArrival = new DateTime($outBoundUnCollectCalInfo['arrival_date']);

                    if ($dateChArrival->format('Y-m-d H:i:s') <= $dateChNow->format('Y-m-d H:i:s')) {
                        $isEnable = false;
                        break;
                    }
                    /////////////////////////////////////////////////////////////////////////////////////////////
                    // 毎日お昼の１２時が【翌日集荷の指定締切り時間】 チェック
                    /////////////////////////////////////////////////////////////////////////////////////////////
                    $collectDate = $comiketDetailInfo['collect_date'];
                    $lastSyukaTime = $this->getLastSyukaTime();
                    $collectDate2 = date('Y-m-d H:i:s', strtotime("{$collectDate} {$lastSyukaTime} -1 day"));
                    $toDate = date('Y-m-d H:i:s');
                    //④now > (comiket_detail.collect_date(yyyy/MM/dd) - 1day + 12:00:00) ⇒disable 
                    if ($collectDate2 <= $toDate) {
                        $isEnable = false;
                        break;
                    }
                } else {
                    $eventsubInfo = $this->_EventsubService->fetchEventsubByEventsubId($db, $comiketInfo['eventsub_id']);
                    $toDate = date('Y-m-d H:i:s');
                    $eventTermEndDate = date('Y-m-d H:i:s', strtotime($eventsubInfo['arrival_to_time']));
                    //④now >eventsub.arrival_to_time ⇒disable
                    if ($eventTermEndDate < $toDate) {
                        $isEnable = false;
                        break;
                    }
                    //⑤(binshu_kbn_sel=1 or binshu_kbn_sel=2) and now > (term_to + 14:30:00) ⇒disable
                    $closingTime = "14:30:00";
                    $termTo = date('Y-m-d H:i:s', strtotime("{$eventsubInfo['term_to']} {$closingTime}"));
                    if (($comiketDetailInfo['binshu_kbn'] == '1' || $comiketDetailInfo['binshu_kbn'] == '2') && $toDate > $termTo) {
                        $isEnable = false;
                        break;
                    }
                    //⑥ (arrival_fr <= now() <= arrival_to_time) == false ⇒disable
                    $arrivalFr = date('Y-m-d H:i:s', strtotime("{$eventsubInfo['arrival_fr']}"));
                    $arrivalToTime = date('Y-m-d H:i:s', strtotime("{$eventsubInfo['arrival_to_time']}"));
                    if (!($arrivalFr <= $toDate && $toDate <= $arrivalToTime)) {
                        $isEnable = false;
                        break;
                    }
                    
                }
            }
        }
        return $isEnable;
    }
    
}