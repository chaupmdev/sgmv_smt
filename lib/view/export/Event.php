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
 * イベント関連のcsv情報出力画面を表示します。
 * @package    View
 * @subpackage EXPORT
 * @author     DucPM31
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Export_Event extends Sgmov_View_Export_Common {

    /**
     * イベントサービス
     * @var Sgmov_Service_Event
     */
    protected $_EventService;


    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_EventService = new Sgmov_Service_Event();
    }

    /**
     * 処理を実行します。
     */
    public function executeInner() {
        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        
        //GiapLN fix bug export load page 2022.07.15
        if ($session->_isNewSession != TRUE) {
            $session->checkSessionTimeout();
        }
        
        if(@empty($_SERVER["HTTP_REFERER"]) || strpos($_SERVER["HTTP_REFERER"], "/export/") == false) {
            // セッション情報を破棄
            $session->deleteForm(self::FEATURE_ID);
        }
        
        // セッション情報
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        $errorForm = NULL;
            
        // 初期表示時以外(入力エラーなどで戻ってきた場合など)
        if (isset($sessionForm) && $sessionForm->error) {
            $errorForm = $sessionForm->error;
            // セッション破棄
            $sessionForm->error = NULL;
        }

        // DB接続
        $db = Sgmov_Component_DB::getPublic();
        
        // イベント情報取得
        $tmpEvents = $this->_EventService->fetchAllEventsHasEventSub($db);
        // event_idが統一されていないイベントをまとめる
        $events = $this->groupingEventId($tmpEvents);

        $eventIds = array_map(function($value) {return $value['id'];}, $events);
        $eventNames = array_map(function($value) {return $value['name'];}, $events);
        $eventInfos = [
            'event_ids' => $eventIds,
            'event_names' => array_combine($eventIds, $eventNames),
        ];
        
        return array(
            'events'    => $events,
            'eventInfos'    => $eventInfos,
            'errorForm' => $errorForm, 
        );
    }
    
    /**
     * プルダウンを生成し、HTMLソースを返します。
     *
     * @param $cds コードの配列
     * @param $lbls ラベルの配列
     * @return 生成されたプルダウン
     */
    public static function createEventPulldown($cds, $lbls) {

        $html = '';

        if (empty($cds)) {
            return $html;
        }

        foreach ($cds as $value) {
            $html .= '<option value="' . $value . '">' . $value . "：" . $lbls[$value] . '</option>' . PHP_EOL;
        }

        return $html;
    }
}
