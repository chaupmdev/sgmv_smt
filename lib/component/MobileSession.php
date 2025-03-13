<?php
/**
 * @package    ClassDefFile
 * @author     S.Tokuoka
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */

ini_set('session.use_trans_sid', '1'); // 下のrequire_onceより先に処理する事が重要
require_once 'Session.php';

/**
 * 携帯用のセッションクラス
 *
 * 詳細はSession.phpを参照してください。
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
 * @author     S.Tokuoka
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Component_MobileSession extends Sgmov_Component_Session
{
    /**
     * このクラスの唯一のインスタンスを返します。
     *
     * 初回呼び出しでインスタンスが生成されます。
     * 二度目以降の呼び出しではそのインスタンスが使用されます。
     *
     * @return Sgmov_Component_Session このクラスの唯一のインスタンス
     */
    public static function get()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new Sgmov_Component_MobileSession();
        }
        return self::$_instance;
    }

    /**
     * URLを携帯ページ用に修正して返します。
     * 戻り値のURLを使うと遷移先などでセッション情報が引き継がれます。
     * @param string 元のURL
     * @return string セッション情報を付加したURL
     */
    public static function getUrl($url) {
        // リクエストパラメータがすでに含まれている場合
        if(preg_match('/\?/', $url)) {
            return $url . ini_get('arg_separator.output') . session_name() . '=' . session_id();
        } else {
            return $url . '?' . session_name() . '=' . session_id();
        }
    }

    /**
     * 携帯ページではセッションIDはリクエストパラメータとして持ちまわします。
     * セッションジャック対策のためセッションIDはページへのアクセス毎に変更されます。
     */
    public function sessionRegenarete() {
        session_commit();

        session_regenerate_id(FALSE); // ID変更前のセッションファイルは残る(ブラウザバックに対応のため)

        session_start();
    }
}
