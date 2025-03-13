<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/**#@+
 * include files
 */
require_once dirname(__FILE__).'/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
/**#@-*/
/**
 * 拠点メールアドレス情報を扱います。
 *
 * @package    Service
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_CenterMail {
    /**
     * 本社のID
     */
    const HONSHA_ID = '1';
    /**
     * 1：To
     */
    const SET_KBN_TO = '1';
    /**
     * 2：Cc
     */
    const SET_KBN_CC = '2';
    /**
     * 3：Bcc
     */
    const SET_KBN_BCC = '3';
    /**
     * 1:概算見積もり申し込みフォーム
     */
    const FORM_KBN_PRE = '1';
    /**
     * 1:訪問見積もり申し込みフォーム
     */
    const FORM_KBN_PVE = '1';
    /**
     * 2:アンケートフォーム
     */
    const FORM_KBN_PQU = '2';
    /**
     * 3:採用エントリーフォーム
     */
    const FORM_KBN_PEM = '3';
    /**
     * 4:お問い合わせフォーム
     */
    const FORM_KBN_PIN = '4';
    /**
     * 5:法人向け引越輸送
     */
    const FORM_KBN_PCM = '5';
    /**
     * 6:法人向け設置輸送
     */
    const FORM_KBN_PCS = '6';
    /**
     * 7:法人向けオフィス移転訪問見積もり申し込みフォーム
     */
    const FORM_KBN_PCV = '7';
    /**
     * 8:旅客手荷物受付サービスのお申し込みフォーム
     */
    const FORM_KBN_PCR = '8';
    /**
     * 9:単身カーゴプランのお申し込みフォーム
     */
    const FORM_KBN_PTU = '9';
    /**
     * 10:お問い合わせフォーム - カヌー輸送
     */
    const FORM_KBN_PIN_CANOE = '10';
    /**
     * 11:お問い合わせフォーム - 採用について
     */
    const FORM_KBN_PIN_SAIYO = '11';
    /**
     * 11:お問い合わせフォーム - 採用について
     */
    const FORM_KBN_MLK = '12';
    /**
     * フォーム区分・拠点IDから拠点メールアドレスをTo・Cc・Bcc区分ごとに取得します。
     *
     * 1つの区分に複数アドレスが設定されている場合は', '(カンマ＋半角スペース)で連結します。
     *
     * 見つからない場合はNULLを返します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param string $formKbn フォーム区分
     * @param string $centerId 拠点ID
     * @return array ['to'] Toメールアドレス文字列、['cc'] Ccメールアドレス文字列、['bcc'] Bccメールアドレス文字列
     */
    public function fetchMailsByCenterId($db, $formKbn, $centerId) {
        $query = 'SELECT';
        $query .= '        mail';
        $query .= '        ,set_kbn';
        $query .= '    FROM';
        $query .= '        center_mails';
        $query .= '    WHERE';
        $query .= '        form_division = $1';
        $query .= '        AND center_id = $2';
        $params = array($formKbn,
            $centerId);
        $queryResult = $db->executeQuery($query, $params);
        $ret = $this->_makeAddresses($queryResult);
        if (Sgmov_Component_Log::isDebug()) {
            $temp = Sgmov_Component_String::toDebugString($ret);
            Sgmov_Component_Log::debug("メールアドレス取得結果[フォーム区分:{$formKbn}, 拠点ID:{$centerId}] {$temp}");
        }
        return $ret;
    }
    /**
     * フォーム区分・出発エリアIDから拠点メールアドレスをTo・Cc・Bcc区分ごとに取得します。
     *
     * 1つの区分に複数アドレスが設定されている場合は', '(カンマ＋半角スペース)で連結します。
     *
     * 見つからない場合はNULLを返します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param string $formKbn フォーム区分
     * @param string $fromAreaId 出発エリアID
     * @return array ['to'] Toメールアドレス文字列、['cc'] Ccメールアドレス文字列、['bcc'] Bccメールアドレス文字列
     */
    public function fetchMailsByFromAreaId($db, $formKbn, $fromAreaId) {
        $query = 'SELECT';
        $query .= '        B.mail';
        $query .= '        ,B.set_kbn';
        $query .= '    FROM';
        $query .= '        (';
        $query .= '            centers_from_areas AS A';
        $query .= '                JOIN center_mails AS B';
        $query .= '                    ON B.center_id = A.center_id';
        $query .= '        )';
        $query .= '    WHERE';
        $query .= '        B.form_division = $1';
        $query .= '        AND A.from_area_id = $2';
        $query .= '        AND A.start_date <= current_date';
        $query .= '        AND A.stop_date >= current_date';
        $params = array($formKbn,
            $fromAreaId);
        $queryResult = $db->executeQuery($query, $params);
        $ret = $this->_makeAddresses($queryResult);
        if (Sgmov_Component_Log::isDebug()) {
            $temp = Sgmov_Component_String::toDebugString($ret);
            Sgmov_Component_Log::debug("メールアドレス取得結果[フォーム区分:{$formKbn}, 出発エリアID:{$fromAreaId}] {$temp}");
        }
        return $ret;
    }
    /**
     * フォーム区分・都道府県IDから拠点メールアドレスをTo・Cc・Bcc区分ごとに取得します。
     *
     * 1つの区分に複数アドレスが設定されている場合は', '(カンマ＋半角スペース)で連結します。
     *
     * 見つからない場合はNULLを返します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param string $formKbn フォーム区分
     * @param string $prefId 都道府県ID
     * @return array ['to'] Toメールアドレス文字列、['cc'] Ccメールアドレス文字列、['bcc'] Bccメールアドレス文字列
     */
    public function fetchMailsByPrefId($db, $formKbn, $prefId) {
        $query = 'SELECT';
        $query .= '        B.mail';
        $query .= '        ,B.set_kbn';
        $query .= '    FROM';
        $query .= '        (';
        $query .= '            centers_prefectures AS A';
        $query .= '                JOIN center_mails AS B';
        $query .= '                    ON B.center_id = A.center_id';
        $query .= '        )';
        $query .= '    WHERE';
        $query .= '        B.form_division = $1';
        $query .= '        AND A.prefecture_id = $2';
        $params = array($formKbn,
            $prefId);
        $queryResult = $db->executeQuery($query, $params);
        $ret = $this->_makeAddresses($queryResult);
        if (Sgmov_Component_Log::isDebug()) {
            $temp = Sgmov_Component_String::toDebugString($ret);
            Sgmov_Component_Log::debug("メールアドレス取得結果[フォーム区分:{$formKbn}, 都道府県ID:{$prefId}] {$temp}");
        }
        return $ret;
    }

    /**
     * クエリ結果から拠点メールアドレスをTo・Cc・Bcc区分ごとに取得します。
     *
     * 1つの区分に複数アドレスが設定されている場合は', '(カンマ＋半角スペース)で連結します。
     *
     * クエリ結果が0件の場合はNULLを返します。
     *
     * @param Sgmov_Component_DBResult $queryResult クエリの実行結果
     * @return array ['to'] Toメールアドレス文字列、['cc'] Ccメールアドレス文字列、['bcc'] Bccメールアドレス文字列
     */
    public function _makeAddresses($queryResult) {
        $count = $queryResult->size();
        if ($count === 0) {
            return NULL;
        }
        $toList = array();
        $ccList = array();
        $bccList = array();
        for ($i = 0; $i < $count; $i++) {
            $row = $queryResult->get($i);
            switch ($row['set_kbn']) {
            case self::SET_KBN_TO:
                $toList[] = $row['mail'];
                break;
            case self::SET_KBN_CC:
                $ccList[] = $row['mail'];
                break;
            case self::SET_KBN_BCC:
                $bccList[] = $row['mail'];
                break;
            }
        }
        $delim = ', ';
        $toStr = implode($delim, $toList);
        $ccStr = implode($delim, $ccList);
        $bccStr = implode($delim, $bccList);
        return array('to' => $toStr,
            'cc' => $ccStr,
            'bcc' => $bccStr);
    }

    /**
     * 拠点のメールアドレスに本社用のメールアドレスを加えた送り先を生成します。
     *
     * 1つの区分に複数アドレスが設定されている場合は', '(カンマ＋半角スペース)で連結します。
     *
     * クエリ結果が0件の場合はNULLを返します。
     *
     * @param String $value 拠点ID
     * @param String $mails 拠点のメールアドレス
     * @param String $honshamail 本社用のメールアドレス
     * @return array ['to'] Toメールアドレス文字列、['cc'] Ccメールアドレス文字列、['bcc'] Bccメールアドレス文字列
     */
    public function _addHonsha($data, $value, $tmp, $mails, $honshamail) {

        $kugiri = ",";

        if ($mails === NULL && $honshamail === NULL) {
            Sgmov_Component_Log::warning("メールアドレスが未設定です。[FORM_KBN_PEM, 1 & {$value}]");
        }

        $toAdd = "";
        $ccAdd = "";
        $bccAdd = "";
        $honshatoAdd = "";
        $honshaccAdd = "";
        $honshabccAdd = "";

        // 拠点メールアドレス（To）を「;」区切りに修正する
        if ($mails['to'] != '') {
            $tomail = explode(', ', $mails['to']);
            foreach ($tomail as $to) {
                $toAdd .= $to.$kugiri;
            }
        }
        // 拠点メールアドレス（Cc）を「;」区切りに修正する
        if ($mails['cc'] != '') {
            $ccmail = explode(', ', $mails['cc']);
            foreach ($ccmail as $cc) {
                $ccAdd .= $cc.$kugiri;
            }
        }
        // 拠点メールアドレス（Bcc）を「;」区切りに修正する
        if ($mails['bcc'] != '') {
            $bccmail = explode(', ', $mails['bcc']);
            foreach ($bccmail as $bcc) {
                $bccAdd .= $bcc.$kugiri;
            }
        }
        // 本社メールアドレス（Bcc）を「;」区切りに修正する
        if ($honshamail['to'] != '') {
            $honshatomail = explode(', ', $honshamail['to']);
            foreach ($honshatomail as $to) {
                $honshatoAdd .= $to.$kugiri;
            }
        }
        // 本社メールアドレス（Bcc）を「;」区切りに修正する
        if ($honshamail['cc'] != '') {
            $honshaccmail = explode(', ', $honshamail['cc']);
            foreach ($honshaccmail as $cc) {
                $honshaccAdd .= $cc.$kugiri;
            }
        }
        // 本社メールアドレス（Bcc）を「;」区切りに修正する
        if ($honshamail['bcc'] != '') {
            $honshabccmail = explode(', ', $honshamail['bcc']);
            foreach ($honshabccmail as $bcc) {
                $honshabccAdd .= $bcc.$kugiri;
            }
        }

        try {
            // メール送信
            Sgmov_Component_Mail::sendTemplateMail($data, Sgmov_Lib::getMailTemplateDir().$tmp, $toAdd.$honshatoAdd, $ccAdd.$honshaccAdd, $bccAdd.$honshabccAdd);
        } catch (Sgmov_Component_Exception $e) {
            Sgmov_Component_ErrorExit::errorExit($e->getCode(), $e->getMessage(), $e->getPrevious());
        }

    }

    /**
     * 拠点のメールアドレスにメールを送信します。
     *
     * 1つの区分に複数アドレスが設定されている場合は', '(カンマ＋半角スペース)で連結します。
     *
     * クエリ結果が0件の場合はNULLを返します。
     *
     * @param String $value 拠点ID
     * @param String $mails 拠点のメールアドレス
     * @param String $honshamail 本社用のメールアドレス
     * @return array ['to'] Toメールアドレス文字列、['cc'] Ccメールアドレス文字列、['bcc'] Bccメールアドレス文字列
     */
    public function _sendEigyosho($data, $value, $tmp, $mails) {

        $kugiri = ",";

        if ($mails === NULL) {
            Sgmov_Component_Log::warning("メールアドレスが未設定です。[FORM_KBN_PEM, 1 & {$value}]");
        }

        $toAdd = "";
        $ccAdd = "";
        $bccAdd = "";
//        $honshatoAdd = "";
//        $honshaccAdd = "";
//        $honshabccAdd = "";

        // 拠点メールアドレス（To）を「;」区切りに修正する
        if ($mails['to'] != '') {
            $tomail = explode(', ', $mails['to']);
            foreach ($tomail as $to) {
                $toAdd .= $to.$kugiri;
            }
        }
        // 拠点メールアドレス（Cc）を「;」区切りに修正する
        if ($mails['cc'] != '') {
            $ccmail = explode(', ', $mails['cc']);
            foreach ($ccmail as $cc) {
                $ccAdd .= $cc.$kugiri;
            }
        }
        // 拠点メールアドレス（Bcc）を「;」区切りに修正する
        if ($mails['bcc'] != '') {
            $bccmail = explode(', ', $mails['bcc']);
            foreach ($bccmail as $bcc) {
                $bccAdd .= $bcc.$kugiri;
            }
        }
//        // 本社メールアドレス（Bcc）を「;」区切りに修正する
//        if ($honshamail['to'] != '') {
//            $honshatomail = explode(', ', $honshamail['to']);
//            foreach ($honshatomail as $to) {
//                $honshatoAdd .= $to.$kugiri;
//            }
//        }
//        // 本社メールアドレス（Bcc）を「;」区切りに修正する
//        if ($honshamail['cc'] != '') {
//            $honshaccmail = explode(', ', $honshamail['cc']);
//            foreach ($honshaccmail as $cc) {
//                $honshaccAdd .= $cc.$kugiri;
//            }
//        }
//        // 本社メールアドレス（Bcc）を「;」区切りに修正する
//        if ($honshamail['bcc'] != '') {
//            $honshabccmail = explode(', ', $honshamail['bcc']);
//            foreach ($honshabccmail as $bcc) {
//                $honshabccAdd .= $bcc.$kugiri;
//            }
//        }

        try {
            // メール送信
            Sgmov_Component_Mail::sendTemplateMail($data, Sgmov_Lib::getMailTemplateDir().$tmp, $toAdd, $ccAdd, $bccAdd);
        } catch (Sgmov_Component_Exception $e) {
            Sgmov_Component_ErrorExit::errorExit($e->getCode(), $e->getMessage(), $e->getPrevious());
        }

    }

    /**
     * 担当者テンプレートメールを送信します。
     *
     * @param string $db データベース接続
     * @param string $formKbn　フォーム区分
     * @param string $areaId 拠点ID
     * @param string $data テンプレートデータ
     * @param array $tmp テンプレートパス
     * @param boolean $flg 営業所のみの送信か本社も含むのかの判断フラグ基本は本社も含む
     */
    public function _sendAdminMail($db, $formKbn, $areaId, $data, $tmp, $flg = false) {

        // 本社
        $honshamail = self::fetchMailsByCenterId($db, $formKbn, Sgmov_Service_CenterMail::HONSHA_ID);

        // 都道府県から
        $mails = self::fetchMailsByPrefId($db, $formKbn, $areaId);
        $value = 1;

        if ($flg) {
            // 都道府県から選択された営業所のみにメールを送信する
            $sendMailAddress = self::_sendEigyosho($data, $value, $tmp, $mails);
        } else {
            // 本社メールアドレスを送信先に反映し、メールを送信する
            $sendMailAddress = self::_addHonsha($data, $value, $tmp, $mails, $honshamail);
        }

    }

    /**
     * 担当者テンプレートメールを送信します。
     *
     * @param string $db データベース接続
     * @param string $formKbn　フォーム区分
     * @param string $areaId 拠点ID
     * @param string $data テンプレートデータ
     * @param array $tmp テンプレートパス
     */
    public function _sendAdminMailForPEM($db, $formKbn, $areaId, $data, $tmp) {

        // 本社
        $honshamail = self::fetchMailsByCenterId($db, $formKbn, Sgmov_Service_CenterMail::HONSHA_ID);

        // 拠点IDから
        $mails = self::fetchMailsByCenterId($db, $formKbn, $areaId);

        // 本社メールアドレスを送信先に反映し、メールを送信する
        $sendMailAddress = self::_addHonsha($data, $areaId, $tmp, $mails, $honshamail);

    }

    /**
     * 担当者テンプレートメールを送信します。
     *
     * @param string $db データベース接続
     * @param string $formKbn　フォーム区分
     * @param string $areaId 拠点ID
     * @param string $data テンプレートデータ
     * @param array $tmp テンプレートパス
     */
    public function _sendAdminMailByFromAreaId($db, $formKbn, $areaId, $data, $tmp) {

        // 本社
        $honshamail = self::fetchMailsByCenterId($db, $formKbn, Sgmov_Service_CenterMail::HONSHA_ID);

        // 都道府県から
        $mails = self::fetchMailsByFromAreaId($db, $formKbn, $areaId);

        // 本社メールアドレスを送信先に反映し、メールを送信する
        $sendMailAddress = self::_addHonsha($data, $areaId, $tmp, $mails, $honshamail);

    }

    /**
     * サンキューテンプレートメールを送信します。
     *
     * @param String $tmp テンプレートパス
     * @param string $sendTo 送信先メールアドレス
     * @param array $data テンプレートデータ
     */
    public function _sendThankYouMail($tmp, $sendTo, $data, $sendCc='') {

        try {
            if (is_array($tmp)) {
                foreach ($tmp as $k => $d) {
                    $tmp[$k] = Sgmov_Lib::getMailTemplateDir() . $d;
                }
            } else {
                $tmp = Sgmov_Lib::getMailTemplateDir() . $tmp;
            }
            return Sgmov_Component_Mail::sendTemplateMail($data, $tmp, $sendTo, $sendCc);
        } catch (Sgmov_Component_Exception $e) {
            Sgmov_Component_ErrorExit::errorExit($e->getCode(), $e->getMessage(), $e->getPrevious());
        }
    }

    /**
     * サンキューテンプレートメールを送信します。
     *
     * @param String $tmp テンプレートパス
     * @param string $sendTo 送信先メールアドレス
     * @param array $data テンプレートデータ
     */
    public function _sendThankYouMailAttached($tmp, $sendTo, $data, $attachment = null, $attach_mime_type = null, $sendCc='') {

        try {
            if (is_array($tmp)) {
                foreach ($tmp as $k => $d) {
                    $tmp[$k] = Sgmov_Lib::getMailTemplateDir() . $d;
                }
            } else {
                $tmp = Sgmov_Lib::getMailTemplateDir() . $tmp;
            }
            return Sgmov_Component_Mail::sendTemplateMailAttached($data, $tmp, $sendTo, $sendCc, '', $attachment, $attach_mime_type);
        } catch (Sgmov_Component_Exception $e) {
            Sgmov_Component_ErrorExit::errorExit($e->getCode(), $e->getMessage(), $e->getPrevious());
        }
    }

    /**
     * サンキューテンプレートメールを送信します。
     *
     * @param String $tmp テンプレートパス
     * @param string $sendTo 送信先メールアドレス
     * @param array $data テンプレートデータ
     */
    public function _sendMlkGyomuMail($tmp, $sendTo, $data) {

        try {
            if (is_array($tmp)) {
                foreach ($tmp as $k => $d) {
                    $tmp[$k] = Sgmov_Lib::getMailTemplateDir() . $d;
                }
            } else {
                $tmp = Sgmov_Lib::getMailTemplateDir() . $tmp;
            }
            return Sgmov_Component_Mail::sendTemplateMail($data, $tmp, $sendTo);
        } catch (Sgmov_Component_Exception $e) {
            Sgmov_Component_ErrorExit::errorExit($e->getCode(), $e->getMessage(), $e->getPrevious());
        }
    }

    /**
     * 担当者テンプレートメールを送信します。
     *
     * @param string $db データベース接続
     * @param string $formKbn　フォーム区分
     * @param string $areaId 拠点ID
     * @param string $data テンプレートデータ
     * @param array $tmp テンプレートパス
     * @param boolean $flg 営業所のみの送信か本社も含むのかの判断フラグ基本は本社も含む
     */
    public function _sendMlkGyomuAdminMail($db, $pref_id, $data, $tmp) {

        // 本社
        $honshamail = self::fetchMailsByCenterId($db, Sgmov_Service_CenterMail::FORM_KBN_MLK, Sgmov_Service_CenterMail::HONSHA_ID);

        // 都道府県から
        $mails = self::fetchMailsByPrefId($db, Sgmov_Service_CenterMail::FORM_KBN_MLK, $pref_id);
        $value = 1;
        // 本社メールアドレスを送信先に反映し、メールを送信する
        $sendMailAddress = self::_addHonsha($data, $value, $tmp, $mails, $honshamail);

    }
}