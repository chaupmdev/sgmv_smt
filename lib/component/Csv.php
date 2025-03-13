<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/impl/Csv.php';
/**#@-*/

 /**
 * CSV変換機能を提供します。
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
 * @package Component
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Component_Csv
{
    /**
     * 実装クラスのインスタンス
     * @var Sgmov_Component_Impl_Csv
     */
    public static $_impl;

    /**
     * 与えられたデータ配列をCSV形式で出力します。
     *
     * 各項目を次の処理で正規化します。
     * <ol>
     * <li>改行コードを LF に統一します。</li>
     * <li>ヌルバイト文字を除去します。</li>
     * <li>「"」を「""」にエスケープします。</li>
     * <li>各項目をダブルクォーテーションで囲みます。</li>
     * <li>文字コードを SJIS-win に変換します。</li>
     * </ol>
     *
     * 生成に失敗した場合は
     * アプリケーションエラーとなりスクリプトが終了します。
     *
     * @param string $fields 項目を $fields[行][列] の形で保持する二次元配列
     * @param string $fname [optional] ファイル名
     */
    public static function downloadCsv($fields, $fname = 'download.csv')
    {
        self::_getImpl()->downloadCsv($fields, $fname);
    }

    /**
     * 実装クラスのインスタンスを取得します。
     *
     * 既に生成されている場合はそのインスタンスを返します。
     * まだ生成されていない場合は生成して返します。
     *
     * @return Sgmov_Component_Impl_Csv 実装クラスのインスタンス
     */
    public static function _getImpl()
    {
        if (!isset(self::$_impl)) {
            self::$_impl = new Sgmov_Component_Impl_Csv();
        }
        return self::$_impl;
    }
}