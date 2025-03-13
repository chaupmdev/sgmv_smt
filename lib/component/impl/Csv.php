<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useComponents(array('ErrorExit','ErrorCode', 'SideEffect'));
/**#@-*/

 /**
 * {@link Sgmov_Component_Config} の実装クラスです。
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
 * 実装が分離されています。
 *
 * @package Component_Impl
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Component_Impl_Csv
{
    /**
     * CSVの区切り文字
     */
    const _CSV_DELIMITER = ',';

    /**
     * {@link Sgmov_Component_Csv::downloadCsv()} の実装です。
     *
     * @param string $fields 項目を $fields[行][列] の形で保持する二次元配列
     * @param string $fname [optional] ファイル名
     */
    public function downloadCsv($fields, $fname = 'download.csv')
    {
        Sgmov_Component_SideEffect::callHeader('Pragma: public');
        Sgmov_Component_SideEffect::callHeader('Cache-Control: public');
        Sgmov_Component_SideEffect::callHeader('Content-Type: application/octet-stream');
        Sgmov_Component_SideEffect::callHeader('Content-Disposition: inline; filename=' . $fname);

        $handle = NULL;
        try {
            $handle = fopen('php://output', 'w');
            $this->_fputCsv($handle, $fields);
            fclose($handle);
        }
        catch (exception $e) {
            if (isset($handle)) {
                @fclose($handle);
            }
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_CSV_DOWNLOAD, '', $e);
        }
    }

    /**
     * 与えられたデータ配列をCSV形式でファイルに出力します。
     *
     * @param resource $handle 書き込み先ファイルのポインタ
     * @param string $fields 項目を $fields[行][列] の形で保持する二次元配列
     */
    public function _fputCsv($handle, $fields)
    {
        foreach ($fields as $rowItems) {
            $line = $this->_createCsvLine($rowItems);
            fwrite($handle, $line);
        }
    }

    /**
     * 項目の配列を CSV 行に変換します。
     *
     * @param array $items CSV 行に変換する項目の配列
     * @return string CSV 行文字列
     */
    public function _createCsvLine($items)
    {
        $line = '';
        $appended = FALSE;
        foreach ($items as $item) {
            $normalizedItem = $this->_normalizeCsvItem($item);
            if ($appended === FALSE) {
                $line .= $normalizedItem;
                $appended = TRUE;
            } else {
                $line .= self::_CSV_DELIMITER . $normalizedItem;
            }
        }
        $line .= "\n";
        return $line;
    }

    /**
     * CSV 項目を正規化します。
     *
     * <ol>
     * <li>改行コードを LF に統一します。</li>
     * <li>ヌルバイト文字を除去します。</li>
     * <li>「"」を「""」にエスケープします。</li>
     * <li>各項目をダブルクォーテーションで囲みます。</li>
     * <li>文字コードを SJIS-win に変換します。</li>
     * </ol>
     *
     * @param string $item 正規化する項目
     * @return string 正規化された項目
     */
    public function _normalizeCsvItem($item)
    {
        $item = str_replace("\r\n", "\n", $item);
        $item = str_replace("\r", "\n", $item);
        $item = str_replace("\0", "", $item);
        $item = str_replace('"', '""', $item);
        $item = '"' . $item . '"';
        $item = mb_convert_encoding($item, 'SJIS-win', 'UTF-8');
        return $item;
    }

}