<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**
 * 文字列処理機能を提供します。
 *
 * [注意事項(共通)]
 *
 * エラーハンドリングでエラーが例外に変換されることを
 * 前提として設計されています。
 *
 * テストのため全て public で宣言します。
 * 名前がアンダーバーで始まるものは使用しないでください。
 *
 * テストでモックを使用するものや、実装を含めると複雑になるものは
 * 実装を分離しています。
 *
 * @package Component
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Component_String
{
    /**
     * 使用する文字エンコード
     */
    const _ENCODING = 'UTF-8';

    /**
     * バイト数ではなく文字数を取得します。
     * 文字エンコードは UTF-8 です。
     * @param string $string 文字列
     * @return integer 文字数
     */
    public static function getCount($string)
    {
        return mb_strlen($string, self::_ENCODING);
    }

    /**
     * バイト数ではなく文字数で部分文字列を取得します。
     * 文字エンコードは UTF-8 です。
     * @param string $str 調べたい文字列
     * @param integer $start str の中の使用開始位置
     * @param integer $length [optional] 返す文字列の最大の長さ
     * @return integer 文字数
     */
    public static function substr($str, $start, $length = PHP_INT_MAX)
    {
        return mb_substr($str, $start, $length, self::_ENCODING);
    }

    /**
     * HTMLの特殊文字(&amp;&lt;&gt;&#039;&quot;)をエンティティ化します。
     * 文字エンコードは UTF-8 です。
     * 配列が渡された場合、再帰により全ての項目に対して処理が行われます。
     *
     * @param mixed $src 文字列または文字列の配列
     * @return string エンティティ化された文字列または文字列の配列
     */
    public static function htmlspecialchars($src)
    {
        if (is_array($src)) {
            $ret = array();
            foreach ($src as $key=>$value) {
                $ret[htmlspecialchars($key, ENT_QUOTES, self::_ENCODING)] = self::htmlspecialchars($value);
            }
            return $ret;
        } else {
            return htmlspecialchars($src, ENT_QUOTES, self::_ENCODING);
        }
    }

    /**
     * 改行文字の前にBRタグを挿入します。
     * 配列が渡された場合、再帰により全ての項目に対して処理が行われます。
     *
     * @param mixed $src 文字列または文字列の配列
     * @return string 変換された文字列または文字列の配列
     */
    public static function nl2br($src)
    {
        if (is_array($src)) {
            $ret = array();
            foreach ($src as $key=>$value) {
                $ret[$key] = self::nl2br($value);
            }
            return $ret;
        } else {
            return nl2br($src);
        }
    }

    /**
     * 前方一致判定を行います。
     * @param string $haystack この文字列の中を探します。
     * @param string $needle この文字列を探します。
     * @return boolean 前方一致の場合 TRUE を、そうでない場合 FALSE を返します。
     */
    public static function startsWith($haystack, $needle)
    {
        if ($needle === "" || $needle === $haystack) {
            return TRUE;
        } else {
            return (strpos($haystack, $needle) === 0) ? TRUE : FALSE;
        }
    }

    /**
     * 後方一致判定を行います。
     * @param string $haystack この文字列の中を探します。
     * @param string $needle この文字列を探します。
     * @return boolean 後方一致の場合 TRUE を、そうでない場合 FALSE を返します。
     */
    public static function endsWith($haystack, $needle)
    {
        if ($needle === "" || $needle === $haystack) {
            return TRUE;
        } else {
            return (strpos(strrev($haystack), strrev($needle)) === 0) ? TRUE : FALSE;
        }
    }

    /**
     * 整数文字列を通貨フォーマットします。
     *
     * 整数ではない場合、そのまま返します。
     *
     * @param string $string この文字列をフォーマットします。
     * @return string フォーマットされた文字列を返します。
     */
    public static function number_format($string)
    {
        // TODO 未テスト
        if ($string === "" || !preg_match('/^[-+]?[0-9]+$/u', $string)) {
            return $string;
        } else {
            return number_format($string);
        }
    }

    /**
     * 入力文字列の正規化を行います。
     * 配列が渡された場合、再帰により全ての項目に対して処理が行われます。
     *
     * <ul>
     * <li>magic_quotes_gpcONの時、エスケープ文字を削除</li>
     * <li>NULLバイト文字を除去</li>
     * <li>Tabスペースを半角スペースへ置換</li>
     * <li>全角スペースを半角スペースへ置換</li>
     * <li>行頭行末の半角スペース削除</li>
     * <li>2文字以上の半角スペースを1文字に置換</li>
     * <li>改行コードを統一</li>
     * <li>半角カタカナを全角に置換</li>
     * </ul>
     * @param mixed $src 文字列または文字列の配列
     * @return 正規化された文字列または文字列の配列
     */
    public static function normalizeInput($src)
    {
        if (is_array($src)) {
            $ret = array();
            foreach ($src as $key=>$value) {
                $ret[$key] = self::normalizeInput($value);
            }
            return $ret;
        } else {
            // if (get_magic_quotes_gpc()) {
                $src = stripslashes($src);
            // }
            $src = str_replace("\0", "", $src);
            $src = str_replace("\t", " ", $src);
            $src = mb_convert_kana($src, "s", self::_ENCODING);
            $src = trim($src);
            $src = preg_replace("/ {2,}/", " ", $src);
            $src = str_replace("\r\n", "\n", $src);
            $src = str_replace("\r", "\n", $src);
            $src = mb_convert_kana($src, "KV", self::_ENCODING);
            return $src;
        }
    }

    /**
     * オブジェクトや配列をデバッグ用の文字列へ変換します。
     *
     * @param mixed $mixed 文字列に変換する対象
     * @param boolean $format [otional] TRUE:整形します。FALSE:整形しません。
     * @return string 変換された文字列
     */
    public static function toDebugString($mixed, $format = FALSE)
    {
        if($format){
            return self::_toDebugStringRecursive($mixed);
        }else{
            return self::_toDebugStringRecursive2($mixed);
        }
    }

    /**
     * オブジェクトや配列をデバッグ用の文字列へ変換します。
     *
     * 入力値がオブジェクトまたは配列の場合は先頭に改行が出力されます。
     * 入力値が文字列や数値の場合は先頭に改行は出力されません。
     *
     * 再帰処理によって、$max で指定された階層までを文字列化します。
     *
     * @param mixed $src 文字列に変換する対象
     * @param string $dump [optional] 変換中の文字列
     * @param int $depth [optional] 現在の階層
     * @param int $max [optional] 最大の階層
     * @return string 変換された文字列
     */
    public static function _toDebugStringRecursive($src, $dump = '', $depth = 1, $max = 5)
    {
        // インデント
        $titleIndent = str_repeat('  ', $depth * 2 - 1);
        $varIndent = $titleIndent . '  ';

        if (is_array($src)) {
            if ($depth > $max) {
                $dump .= "(これ以上深い階層は出力されません)\n";
            } else {
                // 配列
                $dump .= "\n";
                $dump .= "{$titleIndent}[Array]\n";
                foreach ($src as $prop=>$val) {
                    $dump .= "{$varIndent}{$prop} = ";
                    $dump = self::_toDebugStringRecursive($val, $dump, $depth + 1, $max);
                }
            }
        } else if (is_object($src)) {
            if ($depth > $max) {
                $dump .= "(これ以上深い階層は出力されません)\n";
            } else {
                // オブジェクト
                $dump .= "\n";
                $dump .= "{$titleIndent}[" . get_class($src) . "]\n";
                foreach (get_object_vars($src) as $prop=>$val) {
                    $dump .= "{$varIndent}{$prop} = ";
                    $dump = self::_toDebugStringRecursive($val, $dump, $depth + 1, $max);
                }
            }
        } else {
            // プリミティブ
            $dump .= $src;
            if ($depth != 1) {
                $dump .= "\n";
            }
        }
        return $dump;
    }

    /**
     * オブジェクトや配列をデバッグ用の文字列へ変換します。
     *
     * _toDebugStringRecursive2と違って、改行を行いません。
     *
     * 再帰処理によって、$max で指定された階層までを文字列化します。
     *
     * @param mixed $src 文字列に変換する対象
     * @param int $depth [optional] 現在の階層
     * @param int $max [optional] 最大の階層
     * @return string 変換された文字列
     */
    public static function _toDebugStringRecursive2($src, $depth = 1, $max = 5)
    {
        if (is_array($src)) {
            if ($depth > $max) {
                $dump = '･･･';
            } else {
                // 配列
                $dump = '[Array]{';
                $rest = count($src);
                foreach ($src as $prop=>$val) {
                    if (is_string($prop)) {
                        $dump .= '"' . $prop . '"';
                    } else {
                        $dump .= $prop;
                    }
                    $dump .= '=>';
                    $dump .= self::_toDebugStringRecursive2($val, $depth + 1, $max);

                    if(--$rest > 0){
                        $dump .= ',';
                    }
                }
                $dump .= '}';
            }
        } else if (is_object($src)) {
            if ($depth > $max) {
                $dump = '･･･';
            } else {
                // オブジェクト
                $dump = '[' . get_class($src) . ']{';

                $vars = get_object_vars($src);
                if(is_null($vars)){
                    $dump .= 'NULL';
                }else{
                    $rest = count($vars);
                    foreach ($vars as $prop=>$val) {
                        if (is_string($prop)) {
                            $dump .= '"' . $prop . '"';
                        } else {
                            $dump .= $prop;
                        }
                        $dump .= '=>';
                        $dump .= self::_toDebugStringRecursive2($val, $depth + 1, $max);

                        if(--$rest > 0){
                            $dump .= ',';
                        }
                    }
                }
                $dump .= '}';
            }
        } else {
            // プリミティブ
            if (is_string($src)) {
                $dump = '"' . $src . '"';
            } else if(is_null($src)){
                $dump = 'NULL';
            } else {
                $dump = $src;
            }
        }
        return $dump;
    }
}