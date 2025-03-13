<?php
/**
 * @package    ClassDefFile
 * @author     DucPM31
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
Sgmov_Lib::useAllComponents();
Sgmov_Lib::useServices(array('Comiket','ComiketDetail','ComiketBox', 'Event', 'Eventsub'));
Sgmov_Lib::useForms(array('Error', 'EveSession'));
Sgmov_Lib::useView('Public');
Sgmov_Lib::usePHPExcel();
/**#@-*/

/**
 * イベントサービスのお申し込みフォームの共通処理を管理する抽象クラスです。
 * @package    View
 * @subpackage EXPORT
 * @author     DucPM31
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Export_Common extends Sgmov_View_Public {

    // event_idが統一されていないイベントをグループ化するリスト
    public $groupList=[
            // デザインフェスタ：event_id=25に統一
                 '1'=>'25',
                 '3'=>'25',
                 '7'=>'25',
                '25'=>'25',
             '99997'=>'25',
            '100001'=>'25',
             '10007'=>'25',
             '10025'=>'25',
                // 開発環境
//              '1600'=>'25',

            // コミケ：event_id=2に統一
                 '2'=>'2',
             '10002'=>'2',
                // 開発環境
//               '295'=>'2',

            // ゲームマーケット：event_id=301に統一
                '30'=>'301',
               '301'=>'301',
           '9999301'=>'301',

                // 開発環境
               '300'=>'301',
        ];



    /**
     * 機能ID
     */
    const FEATURE_ID = 'EXPORT';
    
    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
    }

    /**
     * event_idが統一されていないイベントをグループ化する
     */
    public function groupingEventId($list){
        // 統一リスト
        $groupList=$this->groupList;
        $mergeList=[];
        foreach($list as $k=>$v){
            if(isset($groupList[$v['id']])){
                $mergeList[$groupList[$v['id']]]=$v;
            }
            else{
                $mergeList[$v['id']]=$v;
            }
        }

        return $mergeList;

    }
}
