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
Sgmov_Lib::useView('hsk/Common');
Sgmov_Lib::useServices(array('EnqueteSenshuken'));

/**#@-*/

/**
 * 品質選手権アンケート入力官僚画面を表示します。
 * @package    View
 * @subpackage HSK
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Hsk_END extends Sgmov_View_Hsk_Common {
    
    /**
     * コミケ申込データサービス
     * @var Sgmov_Service_Comiket
     */
    private $_EnqueteSenshuken;
    
    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        parent::__construct();
        $this->_EnqueteSenshuken = new Sgmov_Service_EnqueteSenshuken();
    }
    
    /**
     * 
     */
    public function executeInner() {
Sgmov_Component_Log::debug('################### ZZZZZZZZZZ-2');
Sgmov_Component_Log::info($_POST);
        
        
        $id = @$_POST['id'];
        if(!$this->checkId($id)) {
            Sgmov_Component_Redirect::redirectPublicSsl("/hsk/error");
        }
        
        $checkInputRes = $this->checkInput();
        if(@!empty($checkInputRes)) {
            Sgmov_Component_Redirect::redirectPublicSsl("/hsk/error");
        }
        
        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();
        $insertInfo = array();
        $dbKeyInfo = $this->_EnqueteSenshuken->getDbValInit();
        foreach ($dbKeyInfo as $key => $val) {
            $insertInfo[$key] = $val;
        }
        
        $insertInfo['id'] = @$_POST['id'];
        
        // ゼッケン
        $chkZekkenList = $_POST['chkZekken'];
        
        $score = '1';
        if (count($chkZekkenList) == '1') { // チェックが１つしかない場合
            $score = '2';
        }
        
        foreach($chkZekkenList as $key => $val) {
            $key2 = sprintf('%02d', $val);
            $insertInfo["zekken{$key2}"] = $score;
        }
        
        // 業種
        $gyoshu = $_POST['gyoshu'];
        $insertInfo['gyoshu'] = $gyoshu;
        $insertInfo['gyoshu_sonota'] = @$_POST['gyoshuSonota'];
        
        // 年齢
        $nenrei = $_POST['nenrei'];
        $insertInfo['nenrei'] = $nenrei;
        
        // 性別
        $seibetsu = $_POST['seibetsu'];
        $insertInfo['seibetsu'] = $seibetsu;
        
        // 良かったところ(チェックボックス)
        $yoiList = $_POST['yoi'];
        foreach($yoiList as $key => $val) {
            $key2 = sprintf('%02d', $val);
            $insertInfo["yoi{$key2}"] = '1';
        }
        
        // 良かったところでその他を選ばれた場合
        $insertInfo['yoi_sonota'] = @$_POST['yoiSonota'];
        
        // どのような点がよかったか（テキストエリア）
        $insertInfo['yoi_textarea'] = @$_POST['yoiTextarea'];
        
        // 所要時間
        $insertInfo['shoyojikan'] =  @$_POST['shoyojikan'];
        
        // 利用区分
        $insertInfo['riyokbn'] = @$_POST['riyokbn'];
        
        // その他お気づきの点
        $insertInfo['sonota_textarea'] = @$_POST['sonotaTextarea'];
        
        // 所要時間
        $insertInfo['keihin'] =  @$_POST['keihin'];
        
Sgmov_Component_Log::debug($insertInfo);
        
        $this->_EnqueteSenshuken->insert($db, $insertInfo);
        
        return true;
    }
}