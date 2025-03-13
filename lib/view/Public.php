<?php
/**
 * @package    ClassDefFile
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useComponents('System', 'Log', 'String');
Sgmov_Lib::useServices(array('OutBoundCollectCal', 'Eventsub'));

/**#@-*/

 /**
 * 公開画面の全ビューに共通の情報を管理する抽象クラスです。
 *
 * 処理の実行にはテンプレートメソッドパターンを使用しています。
 *
 * @package    View
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Public
{
    /**
     * 未検査
     */
    const VALIDATION_NOT_YET = 0;

    /**
     * 検査失敗
     */
    const VALIDATION_FAILED = 1;

    /**
     * 検査成功
     */
    const VALIDATION_SUCCEEDED = 2;
    
    /**
     * 往路・集荷日範囲計算サービス
     * @var Sgmov_Service_Travel
     */
    public $_OutBoundUnCollectCal;

    /**
     * イベントサブマスタサービス
     * @var Sgmov_Service_Eventsub
     */
    public $_Eventsub;
    

    /**
     * 処理のテンプレートメソッドです。
     *
     * 全ビューに共通で必要な「エラー処理の開始」と「デバッグログの出力」を一括管理します。
     * $_POST 変数と $_GET 変数は全て正規化されます。
     *
     * @return array 処理に応じた値を持った配列を返します。
     */
    public final function execute()
    {
        // エラー処理を開始
        Sgmov_Component_System::startErrorHandling();

        // デバッグログ
        if (Sgmov_Component_Log::isDebug()) {
            $dbg = Sgmov_Component_String::toDebugString(array('$_GET'=>$_GET, '$_POST'=>$_POST));
            Sgmov_Component_Log::debug('入力値:' . $dbg);
        }

        // 入力値の正規化
        $_POST = Sgmov_Component_String::normalizeInput($_POST);
        $_GET = Sgmov_Component_String::normalizeInput($_GET);

        // 処理を実行
        $ret = $this->executeInner();

        // デバッグログ
        if (Sgmov_Component_Log::isDebug()) {
            $dbg = Sgmov_Component_String::toDebugString($ret);
            Sgmov_Component_Log::debug('戻り値:' . $dbg);
        }

        // 後処理を実行
        $this->postExecute($ret);

        return $ret;
    }

    /**
     * メイン処理を記述するメソッドです。
     * @return array 処理に応じた値を持った配列を返します。
     */
    public abstract function executeInner();

    /**
     * 後処理を記述するメソッドです。
     * @param executeInnerの戻り値
     * @return array 処理に応じた値を持った配列を返します。
     */
    public function postExecute($ret) {
        // オーバーライドがされない場合は何もしません
    }
    
    /**
     * お預かり日終了時間
     * @return string
     */
    protected function getLastSyukaTime() {
        return "12:00:00";
    }
    
    /**
     * アクセス時間で往路・搬入お預かり日の期間を算出(+1日、+2日の加算)
     * @param type $param
     */
    protected function getOutBoundCollectCalFromTo($eventSubId, $hatsuJis2, $minusNum = 0) {
        
        // 各画面のViewにて、親コンストラクタを呼び出している箇所は少ないためここでサービス生成
        $this->_OutBoundUnCollectCal= new Sgmov_Service_OutBoundCollectCal();
        $this->_Eventsub = new Sgmov_Service_Eventsub();

        // 期間範囲外に返す値
        $endList=array(
                    "errorMsg"   => '受付時間が終了しました。',
                    "strFrDate"  => '',  // 画面表示用開始日
                    "strToDate"  => '',  // 画面表示用終了日
                    "frDate"     => '1900-01-01', // 内部保持用開始日
                    "toDate"     => '1900-01-01', // 内部保持用終了日
                );

        // DB接続
        $db = Sgmov_Component_DB::getPublic();

        $week = array("日", "月", "火", "水", "木", "金", "土");
        try {

            // 預かり日情報を取得する
            $resSyukaDateFromTo = $this->getSyukaDateFromTo($hatsuJis2, $eventSubId, $minusNum);
            // 取得がカラなら終了
            if(empty($resSyukaDateFromTo)){
                return $endList;
            }


            // 現在日時を取得
            $dateChNow = new DateTime();
            // デバッグ用：任意の日時をセットしてテストしてください
            // $dateChNow = new DateTime('2022-05-17 12:59:59');

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // 地域ごとの申込最終日時のチェック
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////// 

            // arrival_date(お預かり申込最終日時)の最大日時
            $maxArrival = new DateTime($resSyukaDateFromTo['maxArrivalRecord']['arrival_date']);
            // 画面で選択した都道府県が発のお預かり申込最終日時
            $selectArrival = new DateTime($resSyukaDateFromTo['selectArrivalRecord']['arrival_date']);

            // 最大の最終申込日 <= 現在日時 || 選択した都道府県が発の最終申込日 <=  現在日時
            if( ($maxArrival <= $dateChNow) || ($selectArrival <= $dateChNow) ){
                return $endList;
            }

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // 12時～23時59分はお預かり日に翌日を選択させない
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
            // お預かり開始日
            $subFrDt = $resSyukaDateFromTo['shukaFrDate'];
            // お預かり開始日は、現在日時がお預かり開始日を超えていれば現在日時に丸める
            if ($resSyukaDateFromTo['shukaFrDate'] <= $dateChNow) {
                $subFrDt = $dateChNow;
            }

            // お預かり終了日： 開始日に選択した都道府県のリードタイムを加算した日付
            $subToDt = $resSyukaDateFromTo['shukaToDate'];

            // 計算用にコピー
            $subFrDtTmp = clone $subFrDt;
            // お預かり開始日1日前
            $lastSubFrDt = $subFrDtTmp->modify('-1day');
            // 12:00:00：お預かりが翌日に切り替わる時間
            $limitTime = $this->getLastSyukaTime();
            // お預かり開始日1日前の12:00:00(this->getLastSyukaTime())
            $lastSubFrDt=new DateTime($lastSubFrDt->format('Y-m-d '.$limitTime));

            // お預かり開始日1日前の12:00 <= 現在日時
            // ↑の条件は絶対なので↓のifは絶対に通る
            if( $lastSubFrDt <= $dateChNow ) {
                // お預かり開始日の12:00
                $subFrDt = new DateTime($subFrDt->format('Y-m-d '.$limitTime));

                // お預かり開始日の12:00:00 <= 現在日時が12時以降の場合
                if ( $subFrDt <= $dateChNow) {
                    // お預かりは日はあさっての00:00:00まで
                    $subFrDt = $subFrDt->modify('+2day')->setTime(0,0,0);
                } else {
                    // お預かりは日はあしたの00:00:00まで
                    $subFrDt = $subFrDt->modify('+1day')->setTime(0,0,0);
                }

                // 終了日 < 開始日 の場合はお預かり日を選択できなくさせる
                // 「 < 」なので開始と終了が同日の申込最終日は選択できる
                if ($subToDt < $subFrDt) {
                    return $endList;
                }
            }

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // ここでreturnしないと$subFrDtは「undefined variable」エラーを発生
            return array(
                "strFrDate"     => $subFrDt->format('Y年m月d日') . '（' . $week[$subFrDt->format('w')] . '）',  // 画面表示用開始日
                "strToDate"     => $subToDt->format('Y年m月d日') . '（' . $week[$subToDt->format('w')] . '）',  // 画面表示用終了日
                "frDate"        => $subFrDt->format('Y-m-d'),                                                   // 内部保持用開始日
                "toDate"        => $subToDt->format('Y-m-d'),                                                   // 内部保持用終了日
            );
        } catch (exception $e) {
            Sgmov_Component_Log::debug("Exception {$e->getCode()} line {$e->getLine()} in '{$e->getFile()}': {$e->getMessage()}");
        }
        
        return null;
    }

    /**
     * 往路・搬入預かり日時を取得する
     *
     * @return array 
     */
    protected function getSyukaDateFromTo($hatsuJis2, $eventSubId, $minusNum = 0) {

        $db = Sgmov_Component_DB::getPublic();

        $this->_OutBoundUnCollectCal= new Sgmov_Service_OutBoundCollectCal();
        $this->_Eventsub = new Sgmov_Service_Eventsub();

        // イベントサブマスタを検索
        $eveSubData = $this->_Eventsub->fetchEventsubByEventsubId($db, $eventSubId);
        if (empty($eveSubData)) {
            Sgmov_Component_Log::info("イベントサブマスタが取得できませんでした。eventSubId:" . $eventSubId);
            return array();
        }

        $chakuJis2 = substr($eveSubData['jis5cd'], 0, 2);

        // 現在日時が預かり日の範囲内にいるかチェック
        $nowDay          = new DateTime('today');
        $hannyuAzukariFr = new DateTime($eveSubData['out_bound_unloading_fr']);
        $hannyuAzukariTo = new DateTime($eveSubData['out_bound_unloading_to']);
        //搬入のお預かり日について、当日が開催前の場合でも、選ぶ出来るため
//        if(!($hannyuAzukariFr<=$nowDay && $nowDay <= $hannyuAzukariTo)){
//            Sgmov_Component_Log::info("搬入・往路預かり日が範囲外:" 
//                                    . $eveSubData['out_bound_unloading_fr'].'-'
//                                    . $eveSubData['out_bound_unloading_to']);
//            return array();
//        }


        // 往路・集荷日範囲計算マスタ取得
        $selectArrivalRecord = $this->_OutBoundUnCollectCal->fetchOutBoundCollectCalByHaChaku($db, $eventSubId, $hatsuJis2, $chakuJis2);
        if(empty($selectArrivalRecord)) {
            throw new Exception("A call to fetchOutBoundCollectCalByHaChaku(db, {$eventSubId}, {$hatsuJis2}, {$chakuJis2}) returned NO outBoundData!)");
        }

        // 終了加算日
        $frSubNum = $selectArrivalRecord['deli_period'];
        if (@empty($frSubNum)) {
            $frSubNum = 0;
        }
        if ((int)$minusNum !== 0 && $frSubNum > 0 && $frSubNum > (int)$minusNum) {
            $frSubNum = $frSubNum - (int)$minusNum;
        }

        //  集荷開始日(お預かり日FROM)
        // この日付を基準に お預かり最終日を算出する
        $shukaFrDate = new DateTime($eveSubData['out_bound_unloading_fr']);
        // 往路・集荷日範囲計算マスタ 往、預かり日が最大日時のレコードを取得
        $maxArrivalRecord = $this->_OutBoundUnCollectCal->fetchOutBoundCollectCalMaxDateByEventsubId($db, $eventSubId);

        // お預かり終了日算出
        // お預かり開始日にリードタイムを加算
        $subToDt = (new DateTime($shukaFrDate->format('Y-m-d H:i:s')));
        $subToDt = $subToDt->modify("+{$frSubNum} days");

        return array(
            // お預かり日FROM：eventsub.out_bound_unloading_fr+out_bound_collect_cal.plus_period
            // out_bound_collect_cal.plus_periodは0に算出されるので加算の意味なし
            "shukaFrDate" => $shukaFrDate,
            // お預かり日TO：$subToDt+out_bound_collect_cal.deli_period
            "shukaToDate" => $subToDt,
            // out_bound_collect_cal.arrival_date(お預かり申込最終日時)が最大日時のレコード
            "maxArrivalRecord" => $maxArrivalRecord,
            // 画面で選択した都道府県が発のout_bound_collect_calのレコード
            "selectArrivalRecord" => $selectArrivalRecord,
        );
    }

    /**
     * 宇奈月温泉用：往路・搬入預かり日時を取得する
     *
     * @return array 
     */
    protected function getOutBoundShukaDateForUna($eventSubId,$hatsuJis2,$deliveryDtSelect) {
        $db = Sgmov_Component_DB::getPublic();
        $this->_OutBoundUnCollectCal= new Sgmov_Service_OutBoundCollectCal();
        $this->_Eventsub = new Sgmov_Service_Eventsub();
        
        $nowDay = new DateTime();
        $week = array("日", "月", "火", "水", "木", "金", "土");
        // 期間範囲外に返す値
        $endList=array(
                    "errorMsg"   => '受付時間が終了しました。',
                    "strFrDate"  => '',  // 画面表示用開始日
                    "strToDate"  => '',  // 画面表示用終了日
                    "frDate"     => '1900-01-01', // 内部保持用開始日
                    "toDate"     => '1900-01-01', // 内部保持用終了日
                );
        // イベントサブマスタを検索
        $eveSubData = $this->_Eventsub->fetchEventsubByEventsubId($db, $eventSubId);
        if (empty($eveSubData)) {
            Sgmov_Component_Log::warning("イベントサブマスタが取得できませんでした。eventSubId:" . $eventSubId);
            return array();
        }

        $chakuJis2 = substr($eveSubData['jis5cd'], 0, 2);
        // 往路・集荷日範囲計算マスタ取得
        $selectArrivalRecord = $this->_OutBoundUnCollectCal->fetchOutBoundCollectCalByHaChaku($db, $eventSubId, $hatsuJis2, $chakuJis2);
        if(empty($selectArrivalRecord)) {
            Sgmov_Component_Log::warning("out_bound_collect_calマスタが取得できませんでした。eventSubId={$eventSubId},hatsuJis2={$hatsuJis2},chakuJis2={$chakuJis2}");
            return $endList;
        }
        $deli_period = (int)$selectArrivalRecord['deli_period'];

        //宿泊日
        $shukuHakuDt = new DateTime($deliveryDtSelect);

        //集荷希望日は翌日～宿泊日-リードタイム（全国分）
        $frDate = $nowDay->modify('1day');
        //宿泊日-リードタイム（全国分）
        $toDate = $shukuHakuDt->modify('-'.$deli_period.'day');
        //out_bound_collect_cal.arrival_date
        $maxArrivalDate = new DateTime($selectArrivalRecord['arrival_date']);
        
        //集荷日は最大arrival_date
        if ($toDate > $maxArrivalDate) {
            $toDate = $maxArrivalDate;
        }
        //選択範囲が存在しない場合、終了となる
        if ($frDate > $maxArrivalDate || $toDate->format('Y-m-d') < $frDate->format('Y-m-d')) {
            Sgmov_Component_Log::warning("日付の範囲が間違い。frDate={$frDate->format('Y-m-d')},toDate={$toDate->format('Y-m-d')},maxArrivalDate={$maxArrivalDate->format('Y-m-d')}");
            return $endList;
        }
        Sgmov_Component_Log::info("集荷日の取得が完了：frDate={$frDate->format('Y-m-d')},toDate={$toDate->format('Y-m-d')}");
        return array(
            "strFrDate"     => $frDate->format('Y年m月d日') . '（' . $week[$frDate->format('w')] . '）',  // 画面表示用開始日
            "strToDate"     => $toDate->format('Y年m月d日') . '（' . $week[$toDate->format('w')] . '）',  // 画面表示用終了日
            "frDate"        => $frDate->format('Y-m-d'),                                                   // 内部保持用開始日
            "toDate"        => $toDate->format('Y-m-d'),                                                   // 内部保持用終了日
        );        
    }

    /**
     * イベントサブID=15：コミケ99用のお預かり日算出
     * ※使用しないこと
     * @return array 
     */
    private function getOutboundRengeEventsub15($eventsubId) {
        $week = array("日", "月", "火", "水", "木", "金", "土");
        $dateRangeInfo = array(
            '2021-12-30' => array(
                'long-area' => array(
                    'from' => '2021-12-21',
                    'to' => '2021-12-25',
                    'moshikomi-end' => '2021-12-24 12:00:00'
                    
                ),
                'short-area' => array(
                    'from' => '2021-12-21',
                    'to' => '2021-12-26',
                    'moshikomi-end' => '2021-12-25 12:00:00'
                ),
            ),
            '2021-12-31' => array(
                'long-area' => array(
                    'from' => '2021-12-21',
                    'to' => '2021-12-26',
                    'moshikomi-end' => '2021-12-25 12:00:00'
                ),
                'short-area' => array(
                    'from' => '2021-12-21',
                    'to' => '2021-12-27',
                    'moshikomi-end' => '2021-12-26 12:00:00'
                ),
            ),
        );

        $longAreaList = array(); // 北海道など
        $shortAreaList = array(); // 関東など

        for($i=0; $i<=47; $i++) {
            if (3 <= $i && $i <= 30) {
                $shortAreaList[] = sprintf('%02d', $i);
            } else {
                $longAreaList[] = sprintf('%02d', $i);
            }
        }

        $hatsuJis2 = filter_input(INPUT_POST, 'comiket_detail_outbound_pref_cd_sel');
        $hatsuJis2 = sprintf('%02d', $hatsuJis2);
        $deliveryYear = filter_input(INPUT_POST, 'comiket_detail_outbound_delivery_date_year_sel'); // 引渡し年
        $deliveryMonth = filter_input(INPUT_POST, 'comiket_detail_outbound_delivery_date_month_sel'); // 引渡し月
        $deliveryDay = filter_input(INPUT_POST, 'comiket_detail_outbound_delivery_date_day_sel'); // 引渡し日
        $daliveryDate = "{$deliveryYear}-{$deliveryMonth}-{$deliveryDay}";
        $todate = (new DateTime())->format('Y-m-d h:i:s');


        if (in_array($hatsuJis2, $longAreaList)) {
            if($todate <= $dateRangeInfo[$daliveryDate]['long-area']['moshikomi-end']) {
                if ((new DateTime($dateRangeInfo[$daliveryDate]['long-area']['from']))->format('Y-m-d') <= $todate) {
                    $dateRangeInfo[$daliveryDate]['long-area']['from'] = (new DateTime())->modify("+1 days")->format('Y-m-d');
                }
                return array(
                    "strFrDate"     => (new DateTime($dateRangeInfo[$daliveryDate]['long-area']['from']))->format('Y年m月d日') 
                                        . '（' . $week[(new DateTime($dateRangeInfo[$daliveryDate]['long-area']['from']))->format('w')] . '）',  // 画面表示用開始日
                    "strToDate"     => (new DateTime($dateRangeInfo[$daliveryDate]['long-area']['to']))->format('Y年m月d日') 
                                        . '（' . $week[(new DateTime($dateRangeInfo[$daliveryDate]['long-area']['to']))->format('w')] . '）',  // 画面表示用終了日
                    "frDate"        => (new DateTime($dateRangeInfo[$daliveryDate]['long-area']['from']))->format('Y-m-d'), // 内部保持用開始日
                    "toDate"        => (new DateTime($dateRangeInfo[$daliveryDate]['long-area']['to']))->format('Y-m-d'), // 内部保持用終了日
                );
            } else {
                return array(
                    "errorMsg" => '受付時間が終了しました。',
                    "strFrDate"  => '',  // 画面表示用開始日
                    "strToDate" => '',  // 画面表示用終了日
                    "frDate" => '1900-01-01', // 内部保持用開始日
                    "toDate" => '1900-01-01', // 内部保持用終了日
                );
            }

        } else {
            if($todate <= $dateRangeInfo[$daliveryDate]['short-area']['moshikomi-end']) {
                if ((new DateTime($dateRangeInfo[$daliveryDate]['short-area']['from']))->format('Y-m-d') <= $todate) {
                    $dateRangeInfo[$daliveryDate]['short-area']['from'] = (new DateTime())->modify("+1 days")->format('Y-m-d');
                }
                return array(
                    "strFrDate"     => (new DateTime($dateRangeInfo[$daliveryDate]['short-area']['from']))->format('Y年m月d日') 
                                    . '（' . $week[(new DateTime($dateRangeInfo[$daliveryDate]['short-area']['from']))->format('w')] . '）',  // 画面表示用開始日
                    "strToDate"     => (new DateTime($dateRangeInfo[$daliveryDate]['short-area']['to']))->format('Y年m月d日') 
                                    . '（' . $week[(new DateTime($dateRangeInfo[$daliveryDate]['short-area']['to']))->format('w')] . '）',  // 画面表示用終了日
                    "frDate"        => (new DateTime($dateRangeInfo[$daliveryDate]['short-area']['from']))->format('Y-m-d'), // 内部保持用開始日
                    "toDate"        => (new DateTime($dateRangeInfo[$daliveryDate]['short-area']['to']))->format('Y-m-d'), // 内部保持用終了日
                );
            } else {
                return array(
                    "errorMsg" => '受付時間が終了しました。',
                    "strFrDate"  => '',  // 画面表示用開始日
                    "strToDate" => '',  // 画面表示用終了日
                    "frDate" => '1900-01-01', // 内部保持用開始日
                    "toDate" => '1900-01-01', // 内部保持用終了日
                );
            }
        }
    }
}
?>
