<?php
/**
 * @package    ClassDefFile
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useServices(array('Comiket',));
// イベント識別子(URLのpath)はマジックナンバーで記述せずこの変数で記述してください
$dirDiv=Sgmov_Lib::setDirDiv(dirname(__FILE__));
Sgmov_Lib::useView($dirDiv.'/Common');

Sgmov_Lib::useForms(array('Error', 'EveSession', 'Eve001Out', 'Eve002In'));
/**#@-*/

/**
 * コミケサービスのお申し込み入力画面を表示します。
 * @package    View
 * @subpackage EVE
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Evp_Use_Event extends Sgmov_View_Evp_Common {

    /**
     * 共通サービス
     * @var Sgmov_Service_AppCommon
     */
    protected $_appCommon;

    /**
     * コミケ申込データサービス
     * @var type
     */
    protected $_Comiket;

    /**
     * 都道府県サービス
     * @var Sgmov_Service_Prefecture
     */
    protected $_PrefectureService;

    /**
     * イベントサービス
     * @var Sgmov_Service_Event
     */
    protected $_EventService;

    /**
     * イベントサブサービス
     * @var Sgmov_Service_Eventsub
     */
    protected $_EventsubService;

    /**
     * 宅配サービス
     * @var type
     */
    protected $_BoxService;

    /**
     * カーゴサービス
     * @var type
     */
    protected $_CargoService;

    /**
     * 館マスタサービス(ブース番号)
     * @var type
     */
    protected $_BuildingService;

    // 識別子
    protected $_DirDiv;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_appCommon             = new Sgmov_Service_AppCommon();
        $this->_Comiket = new Sgmov_Service_Comiket();
        $this->_PrefectureService     = new Sgmov_Service_Prefecture();
        $this->_EventService          = new Sgmov_Service_Event();
        $this->_EventsubService       = new Sgmov_Service_Eventsub();
        $this->_BoxService       = new Sgmov_Service_Box();
        $this->_CargoService       = new Sgmov_Service_Cargo();
        $this->_BuildingService       = new Sgmov_Service_Building();
        $this->_CharterService       = new Sgmov_Service_Charter();

        // 識別子のセット
        $this->_DirDiv = Sgmov_Lib::setDirDiv(dirname(__FILE__));

        parent::__construct();
    }

    public function executeInner() {

    }

    /**
     * イベント開催スケジュール更新処理
     * @param post値
     * @return array eventマスタ
     */
    public function updateSchedule($param) {
        $db = Sgmov_Component_DB::getPublic();

        $lastDay=date("Y-m-d",strtotime("-1 day"));
        $nowDay=date("Y-m-d");
        $nextDay= date("Y-m-d",strtotime("1 day"));
        $startDay='1900/1/1';
        $endDay  ='2112/09/03';
        $after1minute = date("Y-m-d H:i:s",strtotime("10 second"));
        $after10day = date("Y-m-d",strtotime("10 day"));
        $before1minute = date("Y-m-d H:i:s",strtotime("-10 second"));

        if(!(int)$param['eventsub_id']){
            echo('<p style="color:red;">イベントサブIDは半角数字で入力すること<p>');
            return;
        }
        if(preg_match('/[a-z]{3}/',$param['shikibetsushi'])===false){
            echo('<p style="color:red;">識別子は半角英字3文字で入力すること<p>');
            return;
        }

        //往路終了
        $oroEndDay ='2022/04/23';
        $oroEndDate='2022/04/23 12:00:00';

        // 搬入前
        if($param['nyu_preopen']){
            echo($param['nyu_preopen'].'<br>');

            $query="
UPDATE public.eventsub 
SET
     term_fr                        = '2022/04/29' -- 期間開始
    , term_to                       = '2022/05/04' -- 期間終了
    , departure_fr                  = '{$startDay}' -- 往路申込期間開始
    , departure_to                  = '{$oroEndDay}' -- 往路申込期間終了
    , arrival_fr                    = '2022/05/03' -- 復路申込期間開始
    , arrival_to                    = '2022/05/04' -- 復路申込期間終了
    , out_bound_loading_fr          = '2022/04/28' -- 往路搬入開始日
    , out_bound_loading_to          = '2022/04/28' -- 往路搬入終了日
    , out_bound_unloading_fr        = '2022/04/20' -- 往路搬出開始日
    , out_bound_unloading_to        = '2022/04/23' -- 往路搬出終了日
    , in_bound_loading_fr           = '2022/05/04' -- 復路搬入開始日
    , in_bound_loading_to           = '2022/05/04' -- 復路搬入終了日
    , in_bound_unloading_fr         = '2022/05/06' -- 復路搬出開始日
    , in_bound_unloading_to         = '2022/05/12' -- 復路搬出終了日
    , arrival_to_time               = '{$oroEndDay} 12:00:00'       -- 終了日
    , departure_fr_time             = '{$after1minute}' -- 開始日
WHERE    id = {$param['eventsub_id']}                                -- イベントサブID
            ";

            $res = $db->executeUpdate($query, array());
        }
        // 搬入中
        else if($param['nyu_open']){
            echo($param['nyu_open'].'<br>');
            $query="
-- 搬入中クエリ
UPDATE public.eventsub 
SET
     term_fr                        = '2022/04/29' -- 期間開始
    , term_to                       = '2022/05/04' -- 期間終了
    , departure_fr                  = '{$startDay}' -- 往路申込期間開始
    , departure_to                  = '{$oroEndDay}' -- 往路申込期間終了
    , arrival_fr                    = '2022/05/03' -- 復路申込期間開始
    , arrival_to                    = '2022/05/04' -- 復路申込期間終了
    , out_bound_loading_fr          = '2022/04/28' -- 往路搬入開始日
    , out_bound_loading_to          = '2022/04/28' -- 往路搬入終了日
    , out_bound_unloading_fr        = '2022/04/20' -- 往路搬出開始日
    , out_bound_unloading_to        = '2022/04/23' -- 往路搬出終了日
    , in_bound_loading_fr           = '2022/05/04' -- 復路搬入開始日
    , in_bound_loading_to           = '2022/05/04' -- 復路搬入終了日
    , in_bound_unloading_fr         = '2022/05/06' -- 復路搬出開始日
    , in_bound_unloading_to         = '2022/05/12' -- 復路搬出終了日
    , arrival_to_time               = '{$oroEndDay} 12:00:00' -- 復路申込期間終了(時間あり)
    , departure_fr_time             = '{$startDay} 00:00:00' -- 公開開始日時(時間あり)
WHERE    id = {$param['eventsub_id']}                                -- イベントサブID
            ";
            $res = $db->executeUpdate($query, array());

        }
        // 搬入終了
        else if($param['nyu_close']){
            echo($param['nyu_close'].'<br>');
            $query="
-- 搬入終了
UPDATE public.eventsub 
SET
     term_fr                        = '2022/04/29' -- 期間開始
    , term_to                       = '2022/05/04' -- 期間終了
    , departure_fr                  = '{$startDay}' -- 往路申込期間開始
    , departure_to                  = '{$nowDay}' -- 往路申込期間終了
    , arrival_fr                    = '{$nextDay}' -- 復路申込期間開始
    , arrival_to                    = '2022/05/04' -- 復路申込期間終了
    , out_bound_loading_fr          = '2022/04/28' -- 往路搬入開始日
    , out_bound_loading_to          = '2022/04/28' -- 往路搬入終了日
    , out_bound_unloading_fr        = '2022/04/20' -- 往路搬出開始日
    , out_bound_unloading_to        = '2022/04/23' -- 往路搬出終了日
    , in_bound_loading_fr           = '2022/05/04' -- 復路搬入開始日
    , in_bound_loading_to           = '2022/05/04' -- 復路搬入終了日
    , in_bound_unloading_fr         = '2022/05/06' -- 復路搬出開始日
    , in_bound_unloading_to         = '2022/05/12' -- 復路搬出終了日
    , arrival_to_time               = '{$after1minute}' -- 復路申込期間終了(時間あり)
    , departure_fr_time             = '{$startDay}' -- 公開開始日時(時間あり)
WHERE    id = {$param['eventsub_id']}                                -- イベントサブID
            ";
            $res = $db->executeUpdate($query, array());

        }
        // 搬出開始1分前
        else if($param['shutsu_preopen']){
            echo($param['shutsu_preopen'].'<br>');
            $query="
-- 搬出開始1分前
UPDATE public.eventsub 
SET
     term_fr                        = '2022/04/29' -- 期間開始
    , term_to                       = '2022/05/04' -- 期間終了
    , departure_fr                  = '{$startDay}' -- 往路申込期間開始
    , departure_to                  = '{$lastDay}' -- 往路申込期間終了
    , arrival_fr                    = '{$nowDay}' -- 復路申込期間開始
    , arrival_to                    = '2022/05/04' -- 復路申込期間終了
    , out_bound_loading_fr          = '2022/04/28' -- 往路搬入開始日
    , out_bound_loading_to          = '2022/04/28' -- 往路搬入終了日
    , out_bound_unloading_fr        = '2022/04/16' -- 往路搬出開始日
    , out_bound_unloading_to        = '2022/04/23' -- 往路搬出終了日
    , in_bound_loading_fr           = '2022/05/04' -- 復路搬入開始日
    , in_bound_loading_to           = '2022/05/04' -- 復路搬入終了日
    , in_bound_unloading_fr         = '2022/05/06' -- 復路搬出開始日
    , in_bound_unloading_to         = '2022/05/12' -- 復路搬出終了日
    , arrival_to_time               = '{$endDay} 23:59:59' -- 復路申込期間終了(時間あり)
    , departure_fr_time             = '{$after1minute}' -- 公開開始日時(時間あり)
WHERE    id = {$param['eventsub_id']}                                -- イベントサブID
            ";
            $res = $db->executeUpdate($query, array());

        }
        // 搬出中
        else if($param['shutsu_open']){
            echo($param['shutsu_open'].'<br>');
            $query="
-- 搬出中
UPDATE public.eventsub 
SET
     term_fr                        = '2022/04/29' -- 期間開始
    , term_to                       = '2022/05/04' -- 期間終了
    , departure_fr                  = '{$startDay}' -- 往路申込期間開始
    , departure_to                  = '{$lastDay}' -- 往路申込期間終了
    , arrival_fr                    = '{$nowDay}' -- 復路申込期間開始
    , arrival_to                    = '2022/05/04' -- 復路申込期間終了
    , out_bound_loading_fr          = '2022/04/28' -- 往路搬入開始日
    , out_bound_loading_to          = '2022/04/28' -- 往路搬入終了日
    , out_bound_unloading_fr        = '2022/04/16' -- 往路搬出開始日
    , out_bound_unloading_to        = '2022/04/23' -- 往路搬出終了日
    , in_bound_loading_fr           = '2022/05/04' -- 復路搬入開始日
    , in_bound_loading_to           = '2022/05/04' -- 復路搬入終了日
    , in_bound_unloading_fr         = '2022/05/06' -- 復路搬出開始日
    , in_bound_unloading_to         = '2022/05/12' -- 復路搬出終了日
    , arrival_to_time               = '{$endDay} 23:59:59' -- 復路申込期間終了(時間あり)
    , departure_fr_time             = '{$nowDay} 00:00:00' -- 公開開始日時(時間あり)
WHERE    id = {$param['eventsub_id']}                                -- イベントサブID
            ";
            $res = $db->executeUpdate($query, array());

        }
        else if($param['shutsu_close']){
            echo($param['shutsu_close'].'<br>');
            $after1minute = date("Y-m-d H:i:s",strtotime("60 second"));
            $query="
-- 搬出終了1分前～搬出終了
UPDATE public.eventsub 
SET
     term_fr                        = '2022/04/29' -- 期間開始
    , term_to                       = '2022/05/04' -- 期間終了
    , departure_fr                  = '{$startDay}' -- 往路申込期間開始
    , departure_to                  = '{$lastDay}' -- 往路申込期間終了
    , arrival_fr                    = '{$nowDay}' -- 復路申込期間開始
    , arrival_to                    = '2022/05/04' -- 復路申込期間終了
    , out_bound_loading_fr          = '2022/04/28' -- 往路搬入開始日
    , out_bound_loading_to          = '2022/04/28' -- 往路搬入終了日
    , out_bound_unloading_fr        = '2022/04/16' -- 往路搬出開始日
    , out_bound_unloading_to        = '2022/04/23' -- 往路搬出終了日
    , in_bound_loading_fr           = '2022/05/04' -- 復路搬入開始日
    , in_bound_loading_to           = '2022/05/04' -- 復路搬入終了日
    , in_bound_unloading_fr         = '2022/05/06' -- 復路搬出開始日
    , in_bound_unloading_to         = '2022/05/12' -- 復路搬出終了日
    , arrival_to_time               = '{$after1minute}' -- 復路申込期間終了(時間あり)
    , departure_fr_time             = '{$nowDay} 00:00:00' -- 公開開始日時(時間あり)
WHERE    id = {$param['eventsub_id']}                                -- イベントサブID
            ";

            $res = $db->executeUpdate($query, array());

        }
echo('<pre>');echo($query);echo('</pre>');

        //開催状態を取得する
        $query = 'SELECT * FROM eventsub WHERE id = $1';
        $result = $db->executeQuery($query, array($param['eventsub_id']));
        $row = $result->get(0);

        $eventInfo = $this->_EventService->fetchEventInfoByEventId($db, $row[event_id]);
        return $eventInfo;


    }
}