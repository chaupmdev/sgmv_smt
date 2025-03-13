<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useComponents(array('ErrorExit', 'Log', 'ErrorCode', 'String'));
Sgmov_Lib::useForms(array('LoginUser'));
/**#@-*/
session_cache_limiter('nocache');
session_start();

/**
 * セッションを使用した情報の管理を行います。
 *
 * セッションデータの構成は以下の通りです。
 * <ul><li>
 * $_SESSION['ALIVE']
 * <ul><li>セッションの継続をチェックするためのフラグを格納</li></ul>
 * </li><li>
 * $_SESSION['TICKETS']
 * <ul><li>ワンタイムチケットを機能ごとに格納</li></ul>
 * </li><li>
 * $_SESSION['FORMS']
 * <ul><li>フォームを機能ごとに複数格納</li></ul>
 * </li><li>
 * $_SESSION['LOGIN_USER']
 * <ul><li>ログインユーザー情報を格納</li></ul>
 * </li></ul>
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
class Sgmov_Component_Session
{
    /**
     * セッションタイムアウトチェック用キー
     */
    const _KEY_ALIVE = 'ALIVE';

    /**
     * チケット格納用キー
     */
    const _KEY_TICKETS = 'TICKETS';

    /**
     * フォーム格納用キー
     */
    const _KEY_FORMS = 'FORMS';

    /**
     * ログインユーザー情報格納用キー
     */
    const _KEY_LOGIN_USER = 'LOGIN_USER';

    /**
     * このクラスのインスタンス
     * @var Sgmov_Component_Session
     */
    public static $_instance;

    /**
     * セッションが新規作成された場合は TRUE を、そうでない場合は FALSE を保持。
     * @var boolean
     */
    public $_isNewSession = FALSE;

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
            self::$_instance = new Sgmov_Component_Session();
        }
        return self::$_instance;
    }

    /**
     * 直接コンストラクタを呼び出さずに {@link get()} を使用してください。
     *
     * このコンストラクタはテストのためにスコープを public にしています。
     */
    public function __construct()
    {
        $this->_isNewSession = !isset($_SESSION[self::_KEY_ALIVE]);
        $_SESSION[self::_KEY_ALIVE] = TRUE;
    }

    /**
     * セッション情報をクリアします。
     */
    public function clearSession()
    {
        $_SESSION = array();
        $_SESSION[self::_KEY_ALIVE] = TRUE;
    }

    /**
     * セッションが継続していることを確認します。
     * タイムアウトしていた場合、アプリケーションエラーとなります。
     */
    public function checkSessionTimeout()
    {
        if ($this->_isNewSession) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SESSION_TIMEOUT);
        }
    }

    /**
     * チケットを発行します。
     * 機能IDに対して1つのチケットがセッションに保持されます。
     *
     * @param string $featureId 機能ID
     * @param string $gamenId 画面ID
     * @return string 発行されたチケット
     */
    public function publishTicket($featureId, $gamenId)
    {
        if (!isset($_SESSION[self::_KEY_TICKETS])) {
            $_SESSION[self::_KEY_TICKETS] = array();
        }
        $tickets = &$_SESSION[self::_KEY_TICKETS];
        $ticket = $this->_generateTicket();
        $tickets[$featureId] = $gamenId . $ticket;
        Sgmov_Component_Log::debug("チケットが発行されました。 gamenId={$gamenId} ticket={$ticket}");
        return $ticket;
    }

    /**
     * チケットを確認します。
     * 渡されたチケットとセッションに格納されているチケットが一致しない場合は
     * アプリケーションエラーとなりスクリプトが終了します。
     *
     * チケットの確認後、セッションに格納されていたチケットは破棄されます。
     *
     * @param string $featureId 機能ID
     * @param string $fromGamenId 遷移元画面ID
     * @param string $ticket チケット
     * @param boolean $disposeAfterCheck [optional] このフラグがTRUEの場合はチェック後にチケットを破棄します。
     */
    public function checkTicket($featureId, $fromGamenId, $ticket, $disposeAfterCheck = TRUE)
    {
        if (!isset($_SESSION[self::_KEY_TICKETS])) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_TICKET_INVALID);
        }

        $tickets = &$_SESSION[self::_KEY_TICKETS];
        if (!isset($tickets[$featureId]) || $tickets[$featureId] !== $fromGamenId . $ticket) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_TICKET_INVALID);
        }

        if ($disposeAfterCheck === TRUE) {
            Sgmov_Component_Log::debug("チケットが確認されました。チケットは破棄されます。 featureId={$featureId} fromGamenId={$fromGamenId}");
            unset($tickets[$featureId]);
        } else {
            Sgmov_Component_Log::debug("チケットが確認されました。チケットは破棄されません。 featureId={$featureId} fromGamenId={$fromGamenId}");
        }
    }

    /**
     * 指定された機能のチケットを削除します。
     *
     * @param string $featureId 機能ID
     */
    public function deleteTicket($featureId)
    {
        if (!isset($_SESSION[self::_KEY_TICKETS])) {
            Sgmov_Component_Log::debug('チケット情報はありません。featureId=' . $featureId);
            return;
        }

        $tickets = &$_SESSION[self::_KEY_TICKETS];
        if (!isset($tickets[$featureId])) {
            Sgmov_Component_Log::debug('指定された機能のチケット情報はありません。featureId=' . $featureId);
            return;
        }

        unset($tickets[$featureId]);
        Sgmov_Component_Log::debug('指定された機能のチケットが削除されました。featureId=' . $featureId);
    }

    /**
     * チケット文字列を生成します。
     * @return string チケット文字列
     */
    public function _generateTicket()
    {
        return md5(uniqid(rand(), true));
    }

    /**
     * フォームをセッションに保存します。
     * @param string $featureId 機能ID
     * @param object $form フォーム
     */
    public function saveForm($featureId, $form)
    {
        if (!isset($_SESSION[self::_KEY_FORMS])) {
            $_SESSION[self::_KEY_FORMS] = array();
        }
        $features = &$_SESSION[self::_KEY_FORMS];
        $features[$featureId] = serialize($form);
// ログ出力
//        if (Sgmov_Component_Log::isDebug()) {
//            $dbg = Sgmov_Component_String::toDebugString(array('featureId'=>$featureId, 'form'=>$form));
//            Sgmov_Component_Log::debug('フォームが保存されました。' . $dbg);
//        }
    }

    /**
     * フォームをセッションから読み込みます。存在しない場合は NULL を返します。
     * @param string $featureId 機能ID
     * @return object 読み込まれたフォーム。存在しない場合は NULL を返します。
     */
    public function loadForm($featureId)
    {
        if (!isset($_SESSION[self::_KEY_FORMS])) {
            Sgmov_Component_Log::debug('フォーム情報はありません。featureId=' . $featureId);
            return NULL;
        }
        $features = &$_SESSION[self::_KEY_FORMS];

        if (!isset($features[$featureId])) {
            Sgmov_Component_Log::debug('指定された機能のフォーム情報はありません。featureId=' . $featureId);
            return NULL;
        }

        $form = unserialize($features[$featureId]);
// ログ出力
//        if (Sgmov_Component_Log::isDebug()) {
//            $dbg = Sgmov_Component_String::toDebugString(array('featureId'=>$featureId, 'form'=>$form));
//            Sgmov_Component_Log::debug('フォームが読み込まれました。' . $dbg);
//        }
        return $form;
    }

    /**
     * フォームをセッションから削除します。
     * 指定されたフォームが存在しない場合は何もしません。
     * @param string $featureId 機能ID
     */
    public function deleteForm($featureId)
    {
        if (!isset($_SESSION[self::_KEY_FORMS])) {
            Sgmov_Component_Log::debug('フォーム情報はありません。featureId=' . $featureId);
            return;
        }
        $features = &$_SESSION[self::_KEY_FORMS];

        if (!isset($features[$featureId])) {
            Sgmov_Component_Log::debug('指定された機能のフォーム情報はありません。featureId=' . $featureId);
            return;
        }

        unset($features[$featureId]);
        Sgmov_Component_Log::debug('フォームが削除されました。featureId=' . $featureId);
    }

    /**
     * ログインユーザー情報をセッションに保存します。
     * @param Sgmov_Form_LoginUser $loginUser ユーザー情報
     */
    public function saveLoginUser($loginUser)
    {
        $_SESSION[self::_KEY_LOGIN_USER] = serialize($loginUser);
        if (Sgmov_Component_Log::isDebug()) {
            $loginUserString = Sgmov_Component_String::toDebugString($loginUser);
            Sgmov_Component_Log::debug('ログインユーザー情報が保存されました。' . $loginUserString);
        }
    }

    /**
     * ログインユーザー情報をセッションから取得します。存在しない場合は NULL を返します。
     * @return Sgmov_Form_LoginUser 取得されたユーザー情報。ユーザー情報が存在しない場合は NULL を返します。
     */
    public function loadLoginUser()
    {
        if (!isset($_SESSION[self::_KEY_LOGIN_USER])) {
            Sgmov_Component_Log::debug('ログインユーザー情報が存在しません。');
            return NULL;
        }
        $loginUser = unserialize($_SESSION[self::_KEY_LOGIN_USER]);
        if (Sgmov_Component_Log::isDebug()) {
            $loginUserString = Sgmov_Component_String::toDebugString($loginUser);
            Sgmov_Component_Log::debug('ログインユーザー情報が読み込まれました。' . $loginUserString);
        }
        return $loginUser;
    }

    /**
     * ログインユーザー情報をセッションから削除します。
     * 存在しない場合は何もしません。
     */
    public function deleteLoginUser()
    {
        if (!isset($_SESSION[self::_KEY_LOGIN_USER])) {
            Sgmov_Component_Log::debug('ログインユーザー情報が存在しません。');
            return;
        }
        unset($_SESSION[self::_KEY_LOGIN_USER]);
        Sgmov_Component_Log::debug('ログインユーザー情報をセッションから削除しました。');
    }

}