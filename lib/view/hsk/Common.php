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
Sgmov_Lib::useAllComponents();
Sgmov_Lib::useView('Public');
Sgmov_Lib::useServices(array('EnqueteSenshuken'));
/**
 * 共通処理を管理する抽象クラスです。
 * @package    View
 * @subpackage Hsk
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Hsk_Common extends Sgmov_View_Public {
    
    /**
     * コミケ申込データサービス
     * @var Sgmov_Service_Comiket
     */
    private $_EnqueteSenshuken;
    
    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        
        $this->_EnqueteSenshuken = new Sgmov_Service_EnqueteSenshuken();
//        parent::__construct();
    }
    
    /**
     * 
     * @return boolean
     */
    protected function checkId($id = "") {
        if ($id == "") {
            $id = @$_POST['id'];
        }
        if (@empty($id)) {
            Sgmov_Component_Redirect::redirectPublicSsl("/hsk/error");
            exit;
        }
        
        $db = Sgmov_Component_DB::getPublic();
        $resInfo = $this->_EnqueteSenshuken->fetchEnqueteSenshukenById($db, $id);
        
        if (@!empty($resInfo)) {
            $message = urlencode("既に登録済みです。");
            Sgmov_Component_Redirect::redirectPublicSsl("/hsk/error?m={$message}");
            exit;
        }
        
        return true;
    }
    
    /**
     * 
     * @return array
     */
    protected function checkInput() {
        
        $errInfo = array();
        $isErr = false;
        
        if (@empty($_POST['id'])) {
            Sgmov_Component_Redirect::redirectPublicSsl("/hsk/error");
            exit;
        }
        
        // モンストレーション投票
        $chkZekkenList = @$_POST['chkZekken'];
        if (@empty($chkZekkenList)) {
            $chkZekkenList = array();
        }
        $errInfo['chkZekken'] = "";
        if (@empty($chkZekkenList) || 3 <= @count($chkZekkenList)) {
            $errInfo['chkZekken'] .= "・デモンストレーション投票は１つ以上、<br>２つまで選択してください。";
            $isErr = true;
        }

        foreach ($chkZekkenList as $key => $val) {
            if ( $val < "1" || "11" < $val) {
                $errInfo['chkZekken'] = "・デモンストレーション投票の選択値に誤りがあります。";
                $isErr = true;
            }
        }

        // 業種
        $gyoshu = @$_POST['gyoshu'];
        $errInfo['gyoshu'] = "";
        if (@empty($gyoshu)) {
            $errInfo['gyoshu'] = "・業種が未選択です。";
            $isErr = true;
        }
        
        if ($gyoshu == '99') { // 業種 - その他が選択された場合
            // 業種 - その他
            $gyoshuSonota = @$_POST['gyoshuSonota'];
            $errInfo['gyoshuSonota'] = "";
            if (@empty($gyoshuSonota)) {
                $errInfo['gyoshuSonota'] .= "・業種のその他を入力してください。";
                $isErr = true;
            } else {
                $gyoshuSonotaMaxCount = 100;
                if ($gyoshuSonotaMaxCount < mb_strlen($gyoshuSonota, "UTF-8")) {
                    $errInfo['gyoshuSonota'] .= "・業種のその他は{$gyoshuSonotaMaxCount}文字まで入力できます。";
                    $isErr = true;
                }
            }
        }
        
        // 年齢
        $nenrei = @$_POST['nenrei'];
        $errInfo['nenrei'] = "";
        if (@empty($nenrei)) {
            $errInfo['nenrei'] = "・年齢が未選択です。";
            $isErr = true;
        }
        
        // 性別
        $seibetsu = @$_POST['seibetsu'];
        $errInfo['seibetsu'] = "";
        if (@empty($seibetsu)) {
            $errInfo['seibetsu'] = "・性別が未選択です。";
            $isErr = true;
        }
        
        // 品質選手権の内容で良かったと思うものをお選びください。【複数回答可】
        $errInfo['yoi'] = "";
        $yoiList = @$_POST['yoi'];
        $errInfo['yoiTextarea'] = "";
        if (@empty($yoiList)) {
            $errInfo['yoi'] = "・良かったと思うものを１つ以上選択してください。";
            $yoiList = array();
            $isErr = true;
        }
        
        // 品質選手権の内容で良かったと思うもの - その他
        $errInfo['yoiSonota'] = "";
        foreach ($yoiList as $key => $val) {
            if ($val == "99") { // その他が選択されている場合
                $yoiSonota = @$_POST['yoiSonota'];
                if (@empty($yoiSonota)) {
                    $errInfo['yoiSonota'] .= "・良かった点のその他を入力してください。";
                    $isErr = true;
                } else {
                    $yoiSonotaMaxCount = 100;
                    if ($yoiSonotaMaxCount < mb_strlen($yoiSonota, "UTF-8")) {
                        $errInfo['yoiSonota'] .= "・良かった点のその他は{$yoiSonotaMaxCount}文字まで入力できます。";
                        $isErr = true;
                    }
                }
                break;
            }
        }
        
        // 「2」でどのような点が良かったですか？【必須ではありません】
        $errInfo['yoiTextarea'] = "";
        $yoiTextarea = @$_POST['yoiTextarea'];
        $yoiTextareaMaxCount = 100;
        if ($yoiTextareaMaxCount < mb_strlen($yoiTextarea, "UTF-8")) {
            $errInfo['yoiTextarea'] .= "・良かった点は{$yoiTextareaMaxCount}文字まで入力できます。";
            $isErr = true;
        }
        
        // 所要時間
        $errInfo['shoyojikan'] = "";
        $shoyojikan = $yoiTextarea = @$_POST['shoyojikan'];
        if (@empty($shoyojikan)) {
            $errInfo['shoyojikan'] = "・所要時間を選択してください。";
            $isErr = true;
        }
        
        // 利用区分
        $errInfo['riyokbn'] = "";
        $riyokbn = @$_POST['riyokbn'];
        if (@empty($riyokbn)) {
            $errInfo['riyokbn'] = "・今後の利用について選択してください。";
            $isErr = true;
        }
        
        // その他お気づきの点がございましたらご記入ください【必須ではありません】
        $errInfo['sonotaTextarea'] = "";
        $sonotaTextarea = @$_POST['sonotaTextarea'];
        $sonotaTextareaMaxCount = 100;
        if ($sonotaTextareaMaxCount < mb_strlen($sonotaTextarea, "UTF-8")) {
            $errInfo['sonotaTextarea'] .= "・お気づきの点は{$sonotaTextareaMaxCount}文字まで入力できます。";
            $isErr = true;
        }
        
        // ご希望の景品をお選びください。
        $errInfo['keihin'] = "";
        $keihin = $yoiTextarea = @$_POST['keihin'];
        if (@empty($keihin)) {
            $errInfo['keihin'] = "・ご希望の景品を選択してください。";
            $isErr = true;
        }
        
        if ($isErr) {
            // エラーメッセージ全て
            $errInfo['errMsgAll'] = "";
            
            foreach($errInfo as $key => $val) {
                if (@!empty($val)) {
                    $errInfo['errMsgAll'] .= "<a href='#alert-{$key}' onclick='movePageLink(this);return false;' class='pagelink-err anchor-link'>{$val}</a><br>";
                    $errInfo[$key] = "<span id='alert-{$key}'></span>{$val}";
                }
            }
            $errInfo['errMsgAll'] = "以下の入力エラーがあります。<br/>(メッセージを押すと該当箇所にジャンプします)<br/><br/>".$errInfo['errMsgAll'];
            $errInfo['isErr'] = true;
        } else {
            $errInfo = array();
        }

        return $errInfo;
    }
}