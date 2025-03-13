<?php
/**
 * @package    ClassDefFile
 * @author     DucPM31
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
Sgmov_Lib::useView('export/Common');
/**#@-*/

/**
 * イベントサブ生成
 * 
 * @package    View
 * @subpackage EXPORT
 * @author     DucPM31
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Export_Eventsub extends Sgmov_View_Export_Common {

    /**
     * イベントサブサービス
     * @var Sgmov_Service_Eventsub
     */
    protected $_EventsubService;


    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_EventsubService = new Sgmov_Service_Eventsub();
    }

    /**
     * 処理を実行します。
     */
    public function executeInner() {
        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();
        
        // イベントID取得
        $eventId = @$_POST ['event_id'];
        
        // イベントIDが空である場合
        if (empty($eventId)) {
            return array(
                'isSuccess' => 0
            );
        }

        // グループ化リストを復元
        $list = array_keys( $this->groupList, $eventId);
        if(empty($list)){
            $list=array($eventId);
        }
        
        // DB接続
        $db = Sgmov_Component_DB::getPublic();
        
        // イベントサブ取得
//        $eventsubs = $this->_EventsubService->fetchEventsubListByEventId($db, $eventId);
        $eventsubs = $this->_EventsubService->getEventSubForExport($db, $list);

        // プルダウン生成
        $eventsubPlldown = $this->createEventsubPulldown($eventsubs);

        return array(
            'isSuccess' => 1,
            'pulldown' => $eventsubPlldown,
        );
    }
    
    /**
     * プルダウンを生成し、HTMLソースを返します。
     *
     * @param $cds コードの配列
     * @param $lbls ラベルの配列
     * @param $select 選択値
     * @return 生成されたプルダウン
     */
    public static function createEventsubPulldown($eventsubs) {

        $html = '';

        if (empty($eventsubs)) {
            return $html;
        }

        foreach ($eventsubs as $value) {
            $html .= '<option value="' . $value['id'] . '">' . $value['name'] . '</option>' . PHP_EOL;
        }

        return $html;
    }
}
