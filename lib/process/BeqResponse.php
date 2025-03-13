<?php
/**
 * @package    ClassDefFile
 * @author     M.Tamada(NS)
 * @copyright  2016 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
/**#@-*/

/**
 * アンケート結果送信バッチの、送信結果情報を格納します。
 *
 * @package Process
 * @author     M.Tamada(NS)
 * @copyright  2016 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Process_BeqResponse {

    /**
     * 送信結果
     *
     * <ul>
     * <li>0：成功</li>
     * <li>1：型や桁などの形式が不正な場合</li>
     * <li>2：システム障害</li>
     * <li>3：一意制約違反(送信競合)</li>
     * <li>4：既に登録されている</li>
     * </ul>
     * @var string
     */
    public $sendSts;

    /**
     * 受付番号
     *
     * 送信ステータスが「0」または「4」以外の場合は空白です。
     * @var string
     */
    public $ukeNo;

    /**
     * エラー原因
     * @var string
     */
    public $exception;

    /**
     * エラーメッセージ
     * @var string
     */
    public $exceptionMessage;

    /**
     * レスポンス文字列をパースして情報を取り出します。
     * @param string $result 結果文字列
     * @throws Sgmov_Component_Exception 処理に失敗した場合例外を投げます。
     */
    public function initialize($result) {
        // レスポンスボディを取得
        $result = substr($result, strpos($result, "\r\n\r\n") + 4);

        // 1行づつ取得
        $body = explode("\r\n", $result);

        if ($body[1] != "\"HEADER\"" || $body[3] != "\"TRAILER\"") {
            // 途中でレスポンスが切れてしまっていた場合
            $this->sendSts          = '2';
            $this->ukeNo            = null;
            $this->exception        = null;
            $this->exceptionMessage = null;
        } else {
            $item                   = explode(",", $body[2]);
            $this->sendSts          = trim($item[0], '"');
            $this->ukeNo            = trim($item[1], '"');
            $this->exception        = trim($item[2], '"');
            $this->exceptionMessage = trim($item[3], '"');
        }
    }
}