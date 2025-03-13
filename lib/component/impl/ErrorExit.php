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
Sgmov_Lib::useComponents(array('System', 'Log', 'ErrorCode', 'String'));
/**#@-*/

 /**
 * {@link Sgmov_Component_ErrorExit} の実装クラスです。
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
class Sgmov_Component_Impl_ErrorExit
{
    /**
     * {@link Sgmov_Component_ErrorExit::errorExit()} の実装です。
     *
     * @param integer $code エラーコード
     * @param string $message [optional] エラーメッセージ
     * @param Exception $cause [optional] エラーの原因となった例外
     */
    public function errorExit($code, $message = '', $cause = NULL)
    {
        switch ($code) {
        case Sgmov_Component_ErrorCode::ERROR_SYS_UNKNOWN:
        case Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT:
        case Sgmov_Component_ErrorCode::ERROR_DB_CONNECT:
        case Sgmov_Component_ErrorCode::ERROR_DB_BEGIN:
        case Sgmov_Component_ErrorCode::ERROR_DB_COMMIT:
        case Sgmov_Component_ErrorCode::ERROR_DB_ROLLBACK:
        case Sgmov_Component_ErrorCode::ERROR_DB_QUERY:
        case Sgmov_Component_ErrorCode::ERROR_DB_UPDATE:
        case Sgmov_Component_ErrorCode::ERROR_DB_RECORD_GET:
        case Sgmov_Component_ErrorCode::ERROR_DB_RECORD_SIZE:
        case Sgmov_Component_ErrorCode::ERROR_DB_COPY_FROM:
        case Sgmov_Component_ErrorCode::ERROR_MAIL_SEND:
        case Sgmov_Component_ErrorCode::ERROR_MAIL_SENDTEMPLATE:
        case Sgmov_Component_ErrorCode::ERROR_CSV_DOWNLOAD:
        case Sgmov_Component_ErrorCode::ERROR_BVE_WS_CONNECT:
        case Sgmov_Component_ErrorCode::ERROR_BVE_WS_SEND:
        case Sgmov_Component_ErrorCode::ERROR_BVE_WS_RECV_STATUS:
        case Sgmov_Component_ErrorCode::ERROR_BVE_WS_BAD_STATUS:
        case Sgmov_Component_ErrorCode::ERROR_BVE_WS_RECV_DATA:
        case Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_INPUT:
        case Sgmov_Component_ErrorCode::ERROR_AUTH_NOT_ALLOWED:
            // メール通知が必要なシステムエラー(システム障害または不正アクセス)
            if (is_null($cause)) {
                $causeString = '';
            } else {
                $causeString = $cause->__toString();
            }
            $debugString = Sgmov_Component_String::toDebugString(array('$code'=>$code, '$message'=>$message, '$cause'=>$causeString));
            $debugTrace = Sgmov_Component_String::toDebugString(debug_backtrace(), TRUE);
            $logMessage = $debugString . "\n" . $debugTrace;

            // エラーログとエラーメール
            Sgmov_Component_Log::errWithMail("ＳＧムービングサイト：エラー", "システムエラー[エラーコード={$code}]", $logMessage);

            // エラー画面表示
            $this->_showErrorPageAndExit("申し訳ございませんが、予想外のアクセスを確認いたしました。ブラウザバックなどは利用せずに画面上のボタンをご利用下さい。");
        case Sgmov_Component_ErrorCode::ERROR_SESSION_TIMEOUT:
            // セッション切れ(通常使用で起こりうる)
            if (is_null($cause)) {
                $causeString = '';
            } else {
                $causeString = $cause->__toString();
            }
            $debugString = Sgmov_Component_String::toDebugString(array('$code'=>$code, '$message'=>$message, '$cause'=>$causeString));
            $debugTrace = Sgmov_Component_String::toDebugString(debug_backtrace(), TRUE);
            $logMessage = $debugString . "\n" . $debugTrace;

            // 警告ログ
            Sgmov_Component_Log::warning('セッション切れ:' . $logMessage);

            // エラー画面表示
            $this->_showErrorPageAndExit("申し訳ございませんが、長時間同じ画面を表示しつづけたか、予想されない画面遷移がありました。ブラウザバックなどは利用せずに画面上のボタンをご利用下さい。");
        case Sgmov_Component_ErrorCode::ERROR_TICKET_INVALID:
        case Sgmov_Component_ErrorCode::ERROR_AUTH_NOT_LOGIN:
        case Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS:
            // 画面遷移関係のエラー(通常使用で起こりうる)
            if (is_null($cause)) {
                $causeString = '';
            } else {
                $causeString = $cause->__toString();
            }
            $debugString = Sgmov_Component_String::toDebugString(array('$code'=>$code, '$message'=>$message, '$cause'=>$causeString));
            $debugTrace = Sgmov_Component_String::toDebugString(debug_backtrace(), TRUE);
            $logMessage = $debugString . "\n" . $debugTrace;

            // 警告ログ
            Sgmov_Component_Log::warning('画面遷移関係のエラー:' . $logMessage);

            // エラー画面表示
            $this->_showErrorPageAndExit("申し訳ございませんが、予想外の画面遷移です。ブラウザバックなどは利用せずに画面上のボタンをご利用下さい。");
        default:
            // ここにはこないはず
            if (is_null($cause)) {
                $causeString = '';
            } else {
                $causeString = $cause->__toString();
            }
            $debugString = Sgmov_Component_String::toDebugString(array('$code'=>$code, '$message'=>$message, '$cause'=>$causeString));
            $debugTrace = Sgmov_Component_String::toDebugString(debug_backtrace(), TRUE);
            $logMessage = $debugString . "\n" . $debugTrace;

            // エラーログとエラーメール
            Sgmov_Component_Log::errWithMail("ＳＧムービングサイト：エラー", "不明なエラー[エラーコード={$code}]", $logMessage);

            // エラー画面表示
            $this->_showErrorPageAndExit("申し訳ございませんが、エラーが発生いたしました。");
        }
    }

    /**
     * エラー画面を表示して処理を終了します。
     * @param string $msg [optional] 画面に渡すメッセージ
     */
    public function _showErrorPageAndExit($msg = '')
    {
        if (isset($_SERVER['DOCUMENT_ROOT'])) {
            $documentRoot = $_SERVER['DOCUMENT_ROOT'];
            if (Sgmov_Component_String::endsWith($documentRoot, 'public_html')) {
                include dirname(__FILE__) . '/../../error/public.php';
                Sgmov_Component_SideEffect::callExit();
            } else if (Sgmov_Component_String::endsWith($documentRoot, 'ssl_html')) {
                include dirname(__FILE__) . '/../../error/ssl.php';
                Sgmov_Component_SideEffect::callExit();
            } else if (Sgmov_Component_String::endsWith($documentRoot, 'maintenance')) {
                //include dirname(__FILE__) . '/../../error/maintenance.php';
                Sgmov_Component_SideEffect::callExit();
            }
        }
        Sgmov_Component_Log::warning("[サーバー外実行]{$msg}");
        Sgmov_Component_System::systemErrorExit();
    }

}