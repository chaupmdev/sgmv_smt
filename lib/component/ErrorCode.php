<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**
 * ライブラリで使用するエラーコード定義です。
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
class Sgmov_Component_ErrorCode {

    /**
     * 不明なエラー
     */
    const ERROR_SYS_UNKNOWN = 0;
    /**
     * アサーションエラー: アプリケーションが正しく動いていることを確認するために使用されます。
     */
    const ERROR_SYS_ASSERT  = 1;

    /**
     * セッションタイムアウト
     */
    const ERROR_SESSION_TIMEOUT = 1000;
    /**
     * チケットが不正
     */
    const ERROR_TICKET_INVALID  = 1001;

    /**
     * メール送信エラー
     */
    const ERROR_MAIL_SEND         = 2000;
    /**
     * テンプレートメール送信エラー
     */
    const ERROR_MAIL_SENDTEMPLATE = 2001;

    /**
     * DB接続エラー
     */
    const ERROR_DB_CONNECT     = 3000;
    /**
     * トランザクション開始エラー
     */
    const ERROR_DB_BEGIN       = 3001;
    /**
     * トランザクションコミットエラー
     */
    const ERROR_DB_COMMIT      = 3002;
    /**
     * トランザクションロールバックエラー
     */
    const ERROR_DB_ROLLBACK    = 3003;
    /**
     * クエリ実行エラー
     */
    const ERROR_DB_QUERY       = 3004;
    /**
     * 更新実行エラー
     */
    const ERROR_DB_UPDATE      = 3005;
    /**
     * レコード取得エラー
     */
    const ERROR_DB_RECORD_GET  = 3006;
    /**
     * レコード数取得エラー
     */
    const ERROR_DB_RECORD_SIZE = 3007;
    /**
     * CopyFrom実行エラー
     */
    const ERROR_DB_COPY_FROM   = 3008;

    /**
     * CSV ダウンロードエラー
     */
    const ERROR_CSV_DOWNLOAD = 4000;

    /**
     * ログインしていない
     */
    const ERROR_AUTH_NOT_LOGIN   = 5000;
    /**
     * 権限がない
     */
    const ERROR_AUTH_NOT_ALLOWED = 5001;

    /**
     * 訪問見積送信バッチ：ウェブシステムへの接続失敗
     */
    const ERROR_BVE_WS_CONNECT     = 10000;
    /**
     * 訪問見積送信バッチ：ウェブシステムへの送信失敗
     */
    const ERROR_BVE_WS_SEND        = 10001;
    /**
     * 訪問見積送信バッチ：ウェブシステムからのステータスラインの受信失敗
     */
    const ERROR_BVE_WS_RECV_STATUS = 10002;
    /**
     * 訪問見積送信バッチ：ウェブシステムからのステータスが200以外
     */
    const ERROR_BVE_WS_BAD_STATUS  = 10003;
    /**
     * 訪問見積送信バッチ：ウェブシステムからのデータの受信失敗
     */
    const ERROR_BVE_WS_RECV_DATA   = 10004;

    /**
     * 旅客手荷物受付サービスのお申し込み送信バッチ：ウェブシステムへの接続失敗
     */
    const ERROR_BCR_WS_CONNECT     = 11000;
    /**
     * 旅客手荷物受付サービスのお申し込み送信バッチ：ウェブシステムへの送信失敗
     */
    const ERROR_BCR_WS_SEND        = 11001;
    /**
     * 旅客手荷物受付サービスのお申し込み送信バッチ：ウェブシステムからのステータスラインの受信失敗
     */
    const ERROR_BCR_WS_RECV_STATUS = 11002;
    /**
     * 旅客手荷物受付サービスのお申し込み送信バッチ：ウェブシステムからのステータスが200以外
     */
    const ERROR_BCR_WS_BAD_STATUS  = 11003;
    /**
     * 旅客手荷物受付サービスのお申し込み送信バッチ：ウェブシステムからのデータの受信失敗
     */
    const ERROR_BCR_WS_RECV_DATA   = 11004;

    /**
     * 単身カーゴプランのお申し込み送信バッチ：ウェブシステムへの接続失敗
     */
    const ERROR_BTU_WS_CONNECT     = 12000;
    /**
     * 単身カーゴプランのお申し込み送信バッチ：ウェブシステムへの送信失敗
     */
    const ERROR_BTU_WS_SEND        = 12001;
    /**
     * 単身カーゴプランのお申し込み送信バッチ：ウェブシステムからのステータスラインの受信失敗
     */
    const ERROR_BTU_WS_RECV_STATUS = 12002;
    /**
     * 単身カーゴプランのお申し込み送信バッチ：ウェブシステムからのステータスが200以外
     */
    const ERROR_BTU_WS_BAD_STATUS  = 12003;
    /**
     * 単身カーゴプランのお申し込み送信バッチ：ウェブシステムからのデータの受信失敗
     */
    const ERROR_BTU_WS_RECV_DATA   = 12004;

    /**
     * 不正遷移
     */
    const ERROR_VIEW_INVALID_PAGE_ACCESS = 50000;
    /**
     * 不正な入力(通常の使用ではありえない入力)
     */
    const ERROR_VIEW_INVALID_INPUT       = 50001;
}