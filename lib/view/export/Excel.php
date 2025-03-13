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
 * イベント関連出力
 * 
 * @package    View
 * @subpackage EXPORT
 * @author     DucPM31
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Export_Excel extends Sgmov_View_Export_Common {
    
    /**
     * コミケ申込データサービス
     * @var Sgmov_Service_Comiket
     */
    private $_ComiketService;
    
    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_ComiketService = new Sgmov_Service_Comiket();
    }

    /**
     * 処理を実行します。
     */
    public function executeInner() {
        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();
        
        // 入力チェック
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        $errorForm = new Sgmov_Form_Error();

        if (empty($sessionForm)) {
            $sessionForm = new Sgmov_Form_EveSession();
        }
        
        // 画面入力情報取得
        $eventId = @$_POST ['event'];
        $eventSubId = @$_POST ['eventsub'];
        $eventName = @$_POST ['event_name'];
        
        if (empty($eventId) || empty($eventSubId)) {
            $errorForm->addError('event_sel', 'イベント又はサブイベントを選んでください。');
            
            // 情報をセッションに保存
            $sessionForm->error = $errorForm;
            $session->saveForm(self::FEATURE_ID, $sessionForm);
            
            // リダイレクト
            Sgmov_Component_Redirect::redirectPublicSsl('/export/event');
        }
        
        // DB接続
        $db = Sgmov_Component_DB::getPublic();
        
        // コミケ情報取得
        $comikets = $this->_ComiketService->fetchComiketByEventAndEventsub($db, $eventId, $eventSubId);
        
        if (empty($comikets)) {
            $errorForm->addError('event_empty', '情報がありません。');
            
            // 情報をセッションに保存
            $sessionForm->error = $errorForm;
            $session->saveForm(self::FEATURE_ID, $sessionForm);
            
            // リダイレクト
            Sgmov_Component_Redirect::redirectPublicSsl('/export/event');
        }


//        foreach($comikets as $k=>$v){
//            foreach($v as $k2=>$v2){
//                $comikets[$k][$k2]=str_replace("−",'-',$v2);
//            }
//        }
        // デリミタとファイル名設定
        $delimiter = ","; 
        $filename = str_replace("：", "_", $eventName) . "_" . $eventSubId  . "_" . date('YmdHis') . ".csv"; 
     
        // ファイルポインタ作成
        //$f = fopen('php://memory', 'w');

        
        $f = fopen('php://temp', 'w');
        //$f = fopen('php://output', 'w');
        // ファイルの文字コードをエンコード
        //stream_filter_prepend($f,'convert.iconv.utf-8/cp932', STREAM_FILTER_READ);
        // ヘッダー設定
        $comiketHeaders = [
            'コミケ申込ID', '決済データ送信結果', '決済データ送信日時', '入金確認日時', '連携データ送信結果', 
            '連携データ送信日時',  'バッチ処理状況', '送信リトライ数', 'お支払方法', 'コンビニ決済お支払店舗', 
            'コンビニ決済時の受付番号', 'クレジットカード決済時の承認番号', '決済取引ID', '識別', 'イベントID', 
            'イベントサブID', '顧客コード', '法人顧客名', '個人お名前姓', '個人お名前名', '郵便番号', '都道府県', 
            '市区町村', '番地・建物名', '電話番号', 'メールアドレス', '館名', 'ブース名', 'ブース位置', 'ブース番号', 
            '担当者姓', '担当者名', '担当者フリガナ姓', '担当者フリガナ名', '担当者電話番号', '選択', '金額（税抜）', 
            '金額（税込）', '登録IPアドレス', '登録日時', '更新IPアドレス', '更新日時', 'コンビニ後払お問合せ番号', 
            'コンビニ後払自動審査結果 OK/NG/審査中', '配送問合せ番号', '払込票URL(メール再送信用)', 
            'ご購入店受注番号(メール再送信用)', '0：初期中、1：削除中(送信中、送信失敗)、2：削除済', 'キャンセル用送信リトライ数', 
            'SGFキャンセルAPI実行フラグ', 'SGFキャンセルAPIバッチ実行カウント (21回まで)', 'コミケ申込ID枝番', '顧客区分', 
            '物販タイプ', '商品リストパターン', '識別子3文字イベント名', '金額_顧客全額負担_税抜（D24業務CSV出力用）', 
            '金額_顧客全額負担_税込（D24業務CSV出力用）', 'QR受付番号', 'QR問合せ番号', 'QRARK受付番号', 'QR決済明細ID', 
            'QR売上金額(税込)', 'QRシステム区分', 'QRチェックデジット', 'QR決裁 API連携結果の保存：success/failure', 
            'QR決裁 API連携結果の保存：error文字列'
        ];
        
        $comiketDetailHeaders = [
            '明細・コミケ申込ID', '往復区分', '顧客管理番号', '名前', '発JIS5コード', '発精算店コード', '発精算店枝番', 
            '発営業所コード', '発ローカルコード', '着JIS5コード', '着精算店コード', '着精算店枝番', '着営業所コード', 
            '着ローカルコード', '郵便番号', '都道府県', '市区町村', '番地・建物名', 'TEL', 'お預り日', 'お預り開始時刻', 
            'お預り終了時刻', 'お届け日', 'お届け開始時刻', 'お届け終了時刻', 'サービス選択', '備考', '運賃（税抜）', 
            '運賃（税込)', '作業費（税抜）', '作業費（税込）', '復路用お届け時間帯コード', '復路用お届け時間帯名称', 
            'コミケ申込ID枝番', '変更不可フラグ', '便種区分', 'お預かり回数区分', 'お預かり取扱い区分', '請求問合せ番号', 
            '荷動き問合せ番号', '運賃_顧客全額負担（税抜）', '運賃_顧客全額負担（税込)', '作業時間合計', '顧客負担フラグ'
        ];
        
        $comiketBoxHeaders = [
            'box・コミケ申込ID', '往復区分', '箱ID', '数量', '運賃単価（税抜）', '運賃金額（税抜）', '運賃単価（税込）', 
            '運賃金額（税込）', '作業費単価（税抜）', '作業費金額（税抜）', '作業費単価（税込）', '作業費金額（税込）', 
            'コミケ申込ID枝番', '在庫商品コード', 'データ種別', '運賃単価_顧客負担（税抜）', '運賃金額_顧客負担（税抜）', 
            '運賃単価_顧客負担（税込）', '運賃金額_顧客負担（税込）', '作業時間', '商品管理番号', 'ノート'
        ];
        
        $fields = array_merge($comiketHeaders, $comiketDetailHeaders, $comiketBoxHeaders, $comiketBoxHeaders, $comiketBoxHeaders, $comiketBoxHeaders); 
        fputcsv($f, $this->convertEncode($fields), $delimiter); 

        // データ設定
        foreach ($comikets as $comiket) {
            $comiketBoxFormat = explode(", ",$comiket["comiket_box_fomat"]);
            unset($comiket["comiket_box_fomat"]);
            $lineData = array_merge($comiket, $comiketBoxFormat);
            $this->my_fputcsv($f, $this->convertEncode($lineData), $delimiter);
        }
        
        //ファイルの先頭に戻る
        //fseek($f, 0); 
        rewind($f);
        // ヘッダ設定
        //header('Content-Type: text/csv;charset=UTF-8'); 
        header('Content-Type: text/csv;'); 
        header('Content-Disposition: attachment; filename="' . $filename . '";'); 
        // CSV出力
        fpassthru($f);
        fclose($f);
    }
    
    public function my_fputcsv($hFile, $aRow, $sSeparator=',')
    {
       
        $arr = [];
        foreach ($aRow as $item) {
             $val = sprintf('"%s"',$item);
             $arr[] = $val;
        }
       fwrite($hFile, join($arr, $sSeparator)."\n");
    }
    
    public function convertEncode($fields) {
        $output = [];
        foreach ($fields as $value) {
            $output [] = mb_convert_encoding($value, 'SJIS-win', 'UTF-8');
        }
        return $output;
    }
}
