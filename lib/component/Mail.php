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
Sgmov_Lib::useComponents(array('Exception', 'ErrorCode', 'String'));
/**#@-*/

 /**
 * メール送信機能を提供します。
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
class Sgmov_Component_Mail
{
    /**
     * メールを送信します。
     *
     * タイトルは次の処理で正規化されます。
     * <ol>
     * <li>改行コードを除去します。</li>
     * <li>ヌルバイト文字を除去します。</li>
     * <li>半角カナを全角カナに変換します。</li>
     * </ol>
     *
     * 本文は次の処理で正規化されます。
     * <ol>
     * <li>改行コードを LF に統一します。</li>
     * <li>ヌルバイト文字を除去します。</li>
     * <li>半角カナを全角カナに変換します。</li>
     * <li>50文字（半角のみの場合は100文字）ごとに改行を追加します。</li>
     * </ol>
     *
     * function.php の MailSender では X-Mailer が設定されていますが、
     * PHP のバージョンをユーザーに通知する必要はないので外しています。
     * (MailMgr.phpでも設定されていませんでした)
     *
     * @param string $from 送信元メールアドレス
     * @param string $to 送信先メールアドレス
     * @param string $subject メールタイトル
     * @param string $body メール本文
     * @param string $cc [optional] Ccメールアドレス
     * @param string $bcc [optional] Bccメールアドレス
     * @throws Sgmov_Component_Exception メールの送信に失敗した場合
     * {@link Sgmov_Component_ErrorCode::ERROR_MAIL_SEND} が設定されて投げられます。
     */
    public static function sendMail($from, $to, $subject, $body, $cc = '', $bcc = '')
    {
        try {
            $headers = '';
            if (! empty($cc)) {
                $headers .= "Cc:{$cc}\r\n";
            }
            if (! empty($bcc)) {
                $headers .= "Bcc:{$bcc}\r\n";
            }
            $headers .= "From: {$from}";

            $subject = self::_normalizeMailSubject($subject);
            $body = self::_normalizeMailBody($body);

            // sendmail用 envelope sender
            $additional_parameter = "-f{$from}";

            mb_language('ja');
            mb_internal_encoding('UTF-8');
            $ret = mb_send_mail($to, $subject, $body, $headers, $additional_parameter);
            Sgmov_Component_Log::info("\n{$headers}"."\n{$subject}"."\nmb_send_mail(\n{$to},\n{$subject},\n{$body},\n{$headers},\n{$additional_parameter})");
            Sgmov_Component_Log::debug("mb_send_mail(\n{$to},\n{$subject},\n{$body},\n{$headers},\n{$additional_parameter})");

            return $ret;
        }
        catch (exception $e) {
            $temp = array('from'=>$from,

                             'to'=>$to,

                             'subject'=>$subject,

                             'body'=>$body,

                             'cc'=>$cc,

                             'bcc'=>$bcc);
            $message = "メール送信に失敗しました。" + Sgmov_Component_String::toDebugString($temp);
            Sgmov_Component_Log::debug("####### START EXCEPTION send mail #####");
            $debugString = Sgmov_Component_String::toDebugString(array('code'=>$e->getCode(), 'smg_sm'=>$e->getMessage(), 'message' => $message));
            Sgmov_Component_Log::debug($debugString);
            Sgmov_Component_Log::debug("####### END EXCEPTION send mail #####");
            throw new Sgmov_Component_Exception($message, Sgmov_Component_ErrorCode::ERROR_MAIL_SEND, $e);
        }
    }

    /**
     * メールタイトルを正規化します。
     *
     * <ol>
     * <li>改行コードを除去します。</li>
     * <li>ヌルバイト文字を除去します。</li>
     * <li>半角カナを全角カナに変換します。</li>
     * </ol>
     *
     * @param string $subject 正規化するメールタイトル
     * @return string 正規化されたメールタイトル
     */
    public static function _normalizeMailSubject($subject)
    {
        $subject = str_replace("\n", "", $subject);
        $subject = str_replace("\r", "", $subject);
        $subject = str_replace("\0", "", $subject);
        //半角カタカナを全角に置換
        $subject = mb_convert_kana($subject, "KV", "UTF-8");
        return $subject;
    }

    /**
     * メール本文を正規化します。
     *
     * <ol>
     * <li>改行コードをLFに統一します。</li>
     * <li>ヌルバイト文字を除去します。</li>
     * <li>半角カナを全角カナに変換します。</li>
     * <li>50文字（半角のみの場合は100文字）ごとに改行を追加します。</li>
     * </ol>
     *
     * @param string $body 正規化するメール本文
     * @return string 正規化されたメール本文
     */
    public static function _normalizeMailBody($body)
    {
        $body = str_replace("\r\n", "\n", $body);
        $body = str_replace("\r", "\n", $body);
        $body = str_replace("\0", "", $body);
        //半角カタカナを全角に置換
        $body = mb_convert_kana($body, "KV", "UTF-8");

        //50文字（半角のみの場合は100文字）ごとに改行を強制追加
        $line = mb_split ("\n", $body);
        $body_tmp = "";
        for ($i = 0; $i < count($line); $i++) {
            if ( preg_match( "/[\\x80-\\xFF]/", $line[$i] ) ) {
                $part_length = 90;
            } else {
                $part_length = 180;
            }
            $line_tmp = "";
            $line_length = mb_strlen($line[$i], 'UTF-8');

            // 1行ごとに制限文字数内で分解して改行コードを挿入する
            $one_line="";
            if($line_length <= $part_length){
                $one_line = $line[$i]."\n";
            }else{
                for ($s=0; $s < $line_length; $s += $part_length) {
                    $one_line .= mb_substr($line[$i],$s,$part_length, 'UTF-8')."\n";
                }
            }
            $body_tmp .= $one_line;
        }
        $body = $body_tmp;

        return $body;
    }

    /**
     * テンプレートを使用してメールを送信します。
     *
     * テンプレート各行の内容は次の通りです。
     * <ul>
     * <li>1行目 タイトル</li>
     * <li>2行目 送信元</li>
     * <li>3行目 空行</li>
     * <li>4行目以降 本文</li>
     * </ul>
     *
     * 本文の'<::変数名::>'が$params['変数名']の値によって置換されます。
     *
     * タイトルは次の処理で正規化されます。
     * <ol>
     * <li>改行コードを除去します。</li>
     * <li>ヌルバイト文字を除去します。</li>
     * <li>半角カナを全角カナに変換します。</li>
     * <li>50文字（半角のみの場合は100文字）ごとに改行を追加します。</li>
     * </ol>
     *
     * 本文は次の処理で正規化されます。
     * <ol>
     * <li>改行コードを LF に統一します。</li>
     * <li>ヌルバイト文字を除去します。</li>
     * <li>半角カナを全角カナに変換します。</li>
     * <li>50文字（半角のみの場合は100文字）ごとに改行を追加します。</li>
     * </ol>
     *
     * @param array $params テンプレートで使用するパラメーターの配列
     * @param string $templateFile テンプレートファイルのパス
     * @param string $to 送信先
     * @param string $cc [optional] Ccメールアドレス
     * @param string $bcc [optional] Bccメールアドレス
     * @throws Sgmov_Component_Exception メールの送信に失敗した場合
     * {@link Sgmov_Component_ErrorCode::ERROR_MAIL_SENDTEMPLATE} が設定されて投げられます。
     */
    public static function sendTemplateMail($params, $templateFile, $to, $cc = '', $bcc = '') {
        try {
            $arrTmp = array();
            if (is_array($templateFile)) {
                $arrTmp = $templateFile;
            } else {
                $arrTmp[] = $templateFile;
            }

            $body = '';
            foreach ($arrTmp as $k => $tmp) {
                $lines = file($tmp);
                if ($k == 0) {
                    $subject = self::_applyParamsToTemplate($params, $lines[0]);
                    $from = $lines[1];
                    for ($i = 3; $i < count($lines); $i++) {
                        $body .= self::_applyParamsToTemplate($params, $lines[$i]);
                    }
                } else {
                    for ($i = 0; $i < count($lines); $i++) {
                        $body .= self::_applyParamsToTemplate($params, $lines[$i]);
                    }
                }
            }

            $ret = self::sendMail($from, $to, $subject, $body, $cc, $bcc);

            ///////////////
            //DEBUG START//
            ///////////////
//          echo PHP_EOL;
//          echo '=== sendTemplateMail ==='.PHP_EOL;
//          echo '[from] =>'.PHP_EOL;echo $from.PHP_EOL;
//          echo '[cc] =>'.PHP_EOL;echo $cc.PHP_EOL;
//          echo '[bcc] =>'.PHP_EOL;echo $bcc.PHP_EOL;
//          echo '[to] =>'.PHP_EOL;echo $to.PHP_EOL;
//          echo '[subject] =>'.PHP_EOL;echo mb_convert_encoding($subject,'shift-jis','auto').PHP_EOL;
//          echo '[body] =>'.PHP_EOL;echo mb_convert_encoding($body,'shift-jis','auto').PHP_EOL;
            ///////////////
            //DEBUG END  //
            ///////////////

            return $ret;
        } catch (exception $e) {
            $temp = array('params' => $params,
                'templateFile' => $arrTmp,
                'to' => $to);
            $message = "テンプレートメール送信に失敗しました。" + Sgmov_Component_String::toDebugString($temp);
            throw new Sgmov_Component_Exception($message, Sgmov_Component_ErrorCode::ERROR_MAIL_SENDTEMPLATE, $e);
        }

//        try {
//            $lines = file($templateFile);
//
//            $subject = self::_applyParamsToTemplate($params, $lines[0]);
//            $from = $lines[1];
//
//            $body = '';
//            for ($i = 3; $i < count($lines); $i++) {
//                $body .= self::_applyParamsToTemplate($params, $lines[$i]);
//            }
//
//            self::sendMail($from, $to, $subject, $body, $cc, $bcc);
//        }
//        catch (exception $e) {
//            $temp = array('params'=>$params,
//
//                             'templateFile'=>$templateFile,
//
//                             'to'=>$to);
//            $message = "テンプレートメール送信に失敗しました。" + Sgmov_Component_String::toDebugString($temp);
//            throw new Sgmov_Component_Exception($message, Sgmov_Component_ErrorCode::ERROR_MAIL_SENDTEMPLATE, $e);
//        }
    }

    /**
     * 添付つきメールを送信します。
     * send a mail as utf-8 encoding and attach a specified file.
     * using mb_send_mail().
     * @param  string $to               destination address
     * @param  string $subject          mail subject
     * @param  string $plain_message    plain text
     * @param  string $attachment       full path to attaching file
     * @param  string $attach_mime_type MIME type string (like 'text/xml')
     * @param  string $from             from address
     * @return boolean
     */
    public static function sendTemplateMailAttached($params, $templateFile, $to, $cc = '', $bcc = '', $attachment = null, $attach_mime_type = null) {
        if ($attachment === null) {
            //添付が無ければ添付なしで送信
            //ここでは書いてませんが、send_mail()という、ただ単にメールを送信するだけのメソッドを実装しています。
//            Self::send_mail($to, $subject, $plain_message, $from);
            $this->sendTemplateMail($params, $templateFile, $to, $cc, $bcc);
        } else {
            $from = "";
            $subject = "";
            $body = "";
            try {
                if (!file_exists($attachment))
                    return false;

                $arrTmp = array();

                if (is_array($templateFile)) {
                    $arrTmp = $templateFile;
                } else {
                    $arrTmp[] = $templateFile;
                }

                $plain_message = '';
                foreach ($arrTmp as $k => $tmp) {
                    $lines = file($tmp);
                    if ($k == 0) {
                        $subject = self::_applyParamsToTemplate($params, $lines[0]);
                        $from = $lines[1];
                        for ($i = 3; $i < count($lines); $i++) {
                            $plain_message .= self::_applyParamsToTemplate($params, $lines[$i]);
                        }
                    } else {
                        for ($i = 0; $i < count($lines); $i++) {
                            $plain_message .= self::_applyParamsToTemplate($params, $lines[$i]);
                        }
                    }
                }

                //必要に応じて適宜文字コードを設定してください。
                mb_language('Ja');
                mb_internal_encoding('UTF-8');

                $boundary = '__BOUNDARY__' . md5(rand());

                $headers = "Content-Type: multipart/mixed;boundary=\"{$boundary}\"\n";
                //            $headers .= "From: {$from}";
                if (!empty($cc)) {
                    $headers .= "Cc:{$cc}\r\n";
                }
                if (!empty($bcc)) {
                    $headers .= "Bcc:{$bcc}\r\n";
                }
                $headers .= "From: {$from}";

                $body = "--{$boundary}\n";
                $body .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n";
                $body .= "\n{$plain_message}\n";

                $filebase = basename($attachment);
                $body .= "--{$boundary}\n";
                $body .= "Content-Type: {$attach_mime_type}; name=\"{$filebase}\"\n";
                $body .= "Content-Disposition: attachment; filename=\"{$filebase}\"\n";
                $body .= "Content-Transfer-Encoding: base64\n";
                $body .= "\n";
                $body .= chunk_split(base64_encode(file_get_contents($attachment))) . "\n";

                $body .= "--{$boundary}--";

                $subject = self::_normalizeMailSubject($subject);
                $body = self::_normalizeMailBody($body);

                $additional_parameter = "-f{$from}";

                mb_language('ja');
                mb_internal_encoding('UTF-8');
                $ret = mb_send_mail($to, $subject, $body, $headers, $additional_parameter);
                Sgmov_Component_Log::debug("mb_send_mail(\n{$to},\n{$subject},\n{$body},\n{$headers},\n{$additional_parameter})");
                //                //              $ret = mb_send_mail($to, $subject, $body, $headers);
//                //              self::sendMail($from, $to, $subject, $body, $cc, $bcc);
                ///////////////
                //DEBUG START//
                ///////////////
//	            echo PHP_EOL;
//	            echo '=== sendTemplateMailAttached ==='.PHP_EOL;
//	            echo '[from] =>'.PHP_EOL;echo $from.PHP_EOL;
//	            echo '[cc] =>'.PHP_EOL;echo $cc.PHP_EOL;
//	            echo '[bcc] =>'.PHP_EOL;echo $bcc.PHP_EOL;
//	            echo '[to] =>'.PHP_EOL;echo $to.PHP_EOL;
//	            echo '[subject] =>'.PHP_EOL;echo mb_convert_encoding($subject,'shift-jis','auto').PHP_EOL;
//	            echo '[body] =>'.PHP_EOL;echo mb_convert_encoding($body,'shift-jis','auto').PHP_EOL;
                ///////////////
                //DEBUG END  //
                ///////////////

                return $ret;
            } catch (Excepton $e) {
                $temp = array('from' => $from,
                    'to' => $to,
                    'subject' => $subject,
                    'body' => $body, 'shift-jis', 'auto',
                    'cc' => $cc,
                    'bcc' => $bcc);
                $message = "メール送信に失敗しました。" + Sgmov_Component_String::toDebugString($temp);
                throw new Sgmov_Component_Exception($message, Sgmov_Component_ErrorCode::ERROR_MAIL_SEND, $e);
            }
        }
    }

    /**
     * 本文の'<::変数名::>'を$params['変数名']の値によって置換します。
     *
     * @param array $params テンプレートで使用するパラメーターの配列
     * @param array $line テンプレートの行データ
     * @return テンプレートにパラメータを適用した文字列
     */
    public static function _applyParamsToTemplate($params, $line)
    {
        if (!isset($params) || !is_array($params)) {
            return $line;
        }
//        return preg_replace('/<::(.+?)::>/e', 'array_key_exists(\'\\1\', $params) ? $params[\'\\1\'] : \'<::\\1::>\'', $line);
        // 2018/12/05 tahira fix 正規表現の /e修飾子 は PHP 5.5 から非推奨になり
        // E_DEPRECATED レベルのエラーが発生する事への対応 /e修飾子 を使用せず preg_replace_callback関数を使用するように変更
        return preg_replace_callback('/<::(.+?)::>/'
                , function($m) use($params) {
                    return array_key_exists($m[1], $params) ? $params[$m[1]] : $m[0];
                  }
                , $line);
    }
}