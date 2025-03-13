<?php

/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**
 * ライブラリの各機能の読み込みを管理します。
 * lib フォルダからの相対パスを指定して絶対パスの取得を行います。
 *
 * [注意事項(共通)]
 *
 * エラーハンドリングでエラーが例外に変換されることを
 * 前提として設計されています。
 *
 * テストのため全て public で宣言します。
 * 名前がアンダーバーで始まるものは使用しないでください。
 *
 * テストでモックを使用するものは実装が分離されています。
 *
 * @package    Lib
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
// デバッグ用：画面にエラーを出力する場合は下記コメントアウトを外す
//error_reporting(-1);
//ini_set('display_errors', '1');

ini_set('date.timezone', 'Asia/Tokyo');
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . "/include");
class Sgmov_Lib
{
    const _MOBILE_KEY = 'MVE';

    /**
     * アクセスしたpathの識別子を返します
     * @param string $dir アクセスしたpath
     * return string
     */
    public static function setDirDiv($dir)
    {
        // 識別子リスト
        $divList = array(
              'dsn' // デザインフェスタ
            , 'eve' // コミケ(法人)
            , 'evp' // コミケ(個人)
            , 'nen' // NEW ENERGY
            , 'rms' // rooms
            , 'tme' // 東京マラソンEXPO
            , 'qrc' // 先行QR決済サイト（11件分）
            , 'qra' // 2022.04.01リリース：qrcからqraに変更してリリース
            , 'skt' // 2022.04.14リリース：ほぼ日 生活のたのしみ展
            , 'alp' // 2022.05.23リリース：アルペン
            , 'jns' // 2022.06.13リリース：受験なんでも相談会
            , 'mdr' // 2022.06.22リリース：みどり会
            , 'hmj' // 2022.07.12リリース：ハンドメイドインジャパンフェス
            , 'laf' // 2022.07.21リリース：LAFORET GRAND BAZAR
            , 'zzy' // 2022.08.26リリース：ベイクルーズ
            , 'jtb' // 2023.03.03デモ
            , 'una' // 2023.09.20
            ,'twf'  //2023.11.20
            ,'twi'  //2023.11.20
            ,'dst'
            ,'mlk'
            ,'pct'
            ,'yid'
            , 'unf' // 2024.03.11
            , 'nss' // 2024.05.17： にじそうさく09
            
        );

        $dirList = explode('/', str_replace('\\', '/', $dir));
        $dirName = $dirList[count($dirList) - 1];
        // 識別子が3文字、英小文字、リストに一致する場合
        if (strlen($dirName) == 3 && preg_match('/[a-z]/', $dirName) === 1) {
            if (in_array($dirName, $divList)) {
                return $dirName;
            }
        } else {
            return 'xxxxx';
        }
    }

    /**
     * ログの出力先フォルダ
     * @var string
     */
    public static $_logDir;

    /**
     * 設定ファイルの格納フォルダ
     * @var string
     */
    public static $_configDir;

    /**
     * メールテンプレートファイルの格納フォルダ
     * @var string
     */
    public static $_mailTemplateDir;

    /**
     * 全コンポーネントを require_once します。
     * @param boolean $useSession [optional] セッションを使用する場合はTRUEを、使用しない場合はFALSEを指定します。
     */
    public static function useAllComponents($useSession = TRUE)
    {
        $components = array();
        $components[] = 'Config';
        $components[] = 'Csv';
        $components[] = 'DB';
        $components[] = 'DBResult';
        $components[] = 'ErrorCode';
        $components[] = 'ErrorExit';
        $components[] = 'Exception';
        $components[] = 'Log';
        $components[] = 'Mail';
        $components[] = 'MobileDetect';
        $components[] = 'Redirect';
        $components[] = 'SideEffect';
        $components[] = 'String';
        $components[] = 'System';
        $components[] = 'Validator';
        $components[] = 'tfpdf/tfpdf';
        if ($useSession === TRUE) {
            $components[] = 'Session';
        } else if ($useSession === self::_MOBILE_KEY) {
            $components[] = 'MobileSession';
        }
        self::_requireOnce('component', $components);
    }

    /**
     * 使用するコンポーネントを require_once します。
     *
     * 名称はファイル名から拡張子を除いたものです。
     *
     * 名称には文字列または文字列の配列を指定することができます。
     *
     * 例: System.php と Csv.php を使用する場合、
     * <code>
     * Sgmov_Lib::useComponents(array('System', 'Csv'));
     * </code>
     *
     * @param mixed $names 名称の文字列、または文字列の配列。
     */
    public static function useComponents($names)
    {
        self::_requireOnce('component', $names);
    }

    /**
     * 使用するフォームを require_once します。
     *
     * 名称はファイル名から拡張子を除いたものです。
     *
     * 名称には文字列または文字列の配列を指定することができます。
     *
     * @param mixed $names 名称の文字列、または文字列の配列。
     */
    public static function useForms($names)
    {
        self::_requireOnce('form', $names);
    }

    /**
     * 使用するモデルを require_once します。
     *
     * 名称はファイル名から拡張子を除いたものです。
     *
     * 名称には文字列または文字列の配列を指定することができます。
     *
     * @param mixed $names 名称の文字列、または文字列の配列。
     */
    public static function useServices($names)
    {
        self::_requireOnce('service', $names);
    }

    /**
     * 使用するビューを require_once します。
     *
     * 名称はファイル名から拡張子を除いたものです。
     *
     * @param string $name 名称の文字列
     */
    public static function useView($name)
    {
        self::_requireOnce("view", array($name));
    }

    /**
     * 使用するプロセスを require_once します。
     *
     * 名称はファイル名から拡張子を除いたものです。
     *
     * 名称には文字列または文字列の配列を指定することができます。
     *
     * @param mixed $names 名称の文字列、または文字列の配列。
     */
    public static function useProcess($names)
    {
        self::_requireOnce("process", $names);
    }

    /**
     * 使用するVeriTrans3G Merchant Development Kitを require_once します。
     *
     * 名称はファイル名から拡張子を除いたものです。
     *
     * 名称には文字列または文字列の配列を指定することができます。
     *
     * @param mixed $names 名称の文字列、または文字列の配列。
     */
    public static function use3gMdk($names)
    {
        self::_requireOnce('tgMdk', $names);
    }

    /**
     * 使用するPHPExcelを require_once します。
     */
    public static function usePHPExcel()
    {
        self::_requireOnce('.', 'PHPExcel');
        self::_requireOnce('PHPExcel', 'IOFactory');
    }

    /**
     * 使用するImageQRCodeを require_once します。
     */
    public static function useImageQRCode()
    {
        self::_requireOnce('Image_QRCode/Image', 'QRCode');
    }

    /**
     * ログの出力フォルダを取得します。末尾にはスラッシュは含みません。
     * @return string ログの出力フォルダ
     */
    public static function getLogDir()
    {
        if (!isset(self::$_logDir)) {
            self::$_logDir = dirname(__FILE__) . '/../logs';
        }
        return self::$_logDir;
    }

    /**
     * 設定ファイルの格納フォルダを取得します。末尾にはスラッシュは含みません。
     * @return string 設定ファイルの格納フォルダ
     */
    public static function getConfigDir()
    {
        if (!isset(self::$_configDir)) {
            self::$_configDir = dirname(__FILE__) . '/config';
        }
        return self::$_configDir;
    }

    /**
     * メールテンプレートファイルの格納フォルダを取得します。末尾にはスラッシュは含みません。
     * @return string メールテンプレートファイルの格納フォルダ
     */
    public static function getMailTemplateDir()
    {
        if (!isset(self::$_mailTemplateDir)) {
            self::$_mailTemplateDir = dirname(__FILE__) . '/mail_template';
        }
        return self::$_mailTemplateDir;
    }

    /**
     * フォルダとファイル名(拡張子なし)を指定して require_once を行います。
     * @param string $dir lib フォルダ以下のフォルダ名。先頭にはスラッシュは不要です。
     * @param mixed $names 拡張子を除いたファイル名の文字列、または文字列の配列。
     */
    public static function _requireOnce($dir, $names)
    {
        if (is_string($names)) {
            $names = array($names);
        }
        foreach ($names as $name) {
            require_once dirname(__FILE__) . "/{$dir}/{$name}.php";
        }
    }
    /**
     * モジュラス11 ウェイト2－7
     */
    public static function getCheckDigit($num)
    {
        $cdnum = str_split($num);
        //配列を逆順にする
        $arr = array_reverse($cdnum);

        $mj = 11;
        $wait_min = 2;
        $wait_max = 7;
        $multi = $wait_min;
        $mod = 0;

        for ($i = 0; $i < count($arr); $i++) {
            $mod += intval($arr[$i]) * $multi;
            $multi++;
            if ($multi > $wait_max) $multi = $wait_min;
        }
        //合計値を11で割った余りを取得
        $cd = $mod % $mj;
        //最後に余りを11から引いた残りがチェックデジット
        //（本式：モジュラス11ウェイト2－7）
        $checkdigit = $mj - $cd;
        switch ($checkdigit) {
            case 10:
                //11-1=10 CD=0
            case 11:
                //11-0=11 CD=0
                return 0;
                break;
            default:
                //11-2=9 cd=9
                //11-3=8 cd=8
                //11-4=7 cd=7
                //11-5=6 cd=6
                //11-6=5 cd=5
                //11-7=4 cd=4
                //11-8=3 cd=3
                //11-9=2 cd=2
                //11-10=1 cd=1
                return $checkdigit;
                break;
        }
    }
    
    /**
     * 指定バイト数以下で文字列を切り出す(半角が1バイト、全角が2バイト）
     * @param string $str 文字列
     * @param string $byteSplit 数字
     * return string 切り出した文字列
     */
    public static function subbyte($str, $byteNumber)
    {
        //インプラントが空白の場合、終了      
        if (!is_int($byteNumber) || $byteNumber <=0) {
            throw new Exception("A call to subbyte byteNumber parameter is not interger!");
        }
        if (empty($str)) {
            return $str;
        }
        // 最大byte数
        $byteLenMax=$byteNumber;
        $byteLen=strlen($str);
        
        // 最大byte数以下なら終了
        if($byteLen <= $byteLenMax){
            return $str;
        }
        // 1文字ずつ切り出し
        $sp=preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
        $cnt=0;
        $cutStr='';
        foreach($sp as $v){
            $rowCnt=1;//半角文字
            if(strlen($v)>1){//全角文字
                $rowCnt=2;
            }
            $cnt=$cnt+$rowCnt;
            // 最大byte数以下なら継続
            if($cnt <= $byteLenMax){
                $cutStr=$cutStr.$v;
            }
            else{
                break;
            }
        }
        return $cutStr;
    }
    
    /**
     * テキストのバイトを取得する(半角が1バイト、全角が2バイト）
     * @param string $str 文字列
     * return interger byte of string
     */
    public static function getByteNumber($str)
    {
        //インプラントが空白の場合、終了
        if (empty($str)) {
            return 0;
        }
        // 1文字ずつ切り出し
        $sp=preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
        $cnt=0;
        foreach($sp as $v){
            $rowCnt=1;//半角文字
            if(strlen($v)>1){//全角文字
                $rowCnt=2;
            }
            $cnt=$cnt+$rowCnt;
        }
        return $cnt;
    }
}
