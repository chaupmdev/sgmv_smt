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
Sgmov_Lib::useAllComponents(FALSE);
/**#@-*/

 /**
 * アンケート情報を扱います。
 *
 * @package Service
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_Questionnaire
{

    /**
     * アンケート結果をDBに保存します。
     *
     * 区分値ではなく文字列データを保存します。
     * <ul><li>
     * $data['answer1']:回答1
     * </li><li>
     * $data['answer2']:回答2
     * </li><li>
     * $data['answer2_text']:回答2(その他)
     * </li><li>
     * $data['answer3']:回答3
     * </li><li>
     * $data['answer4']:回答4
     * </li><li>
     * $data['answer5']:回答5
     * </li><li>
     * $data['answer6']:回答6
     * </li><li>
     * $data['answer7']:回答7
     * </li><li>
     * $data['answer8']:回答8
     * </li><li>
     * $data['answer9']:回答9
     * </li><li>
     * $data['answer10']:回答10
     * </li></ul>
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data 保存するデータ
     */
    public function insert($db, $data)
    {
        // この順番でSQLのプレースホルダーに適用されます。
        $keys = array('answer1',
                         'answer2',
                         'answer2_text',
                         'answer3',
                         'answer4',
                         'answer5',
                         'answer6',
                         'answer7',
                         'answer8',
                         'answer9',
                         'answer10');

        // パラメータのチェック
        $params = array();
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $params[] = $data[$key];
        }

        $query = 'INSERT INTO questionnaires(';
        $query .= '        created, modified, ';
        $query .= '        answer1, answer2, answer2_text, answer3, answer4, answer5, answer6,';
        $query .= '        answer7, answer8, answer9, answer10)';
        $query .= '    VALUES (now(), now(),';
        $query .= '            $1, $2, $3, $4, $5, $6, $7,';
        $query .= '            $8, $9, $10, $11);';
        Sgmov_Component_Log::debug("####### START INSERT questionnaires #####");
        $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END INSERT questionnaires #####");
    }

    /**
     * 未ダウンロードのアンケート結果件数を取得します。
     * @param Sgmov_Component_DB $db DB接続
     * @return integer 未ダウンロードのアンケート結果件数
     */
    public function getNotYetDownloadedCount($db)
    {
        $query = 'SELECT';
        $query .= '        COUNT(id) AS cnt';
        $query .= '    FROM';
        $query .= '        questionnaires';
        $query .= '    WHERE';
        $query .= '        downloaded_flag = \'FALSE\'';
        $result = $db->executeQuery($query)->
                        get(0);
        return intval($result['cnt']);
    }

    /**
     * アンケート結果を取得します。
     *
     * 区分値ではなく文字列データを保存します。
     * <ul><li>
     * $data['from']:登録日の最小値(YYYYMMDD)
     * </li><li>
     * $data['to']:登録日の最大値(YYYYMMDD)
     * </li><li>
     * $data['office_flag']:オフィス移転フラグ
     * </li><li>
     * $data['setting_flag']:設置輸送フラグ
     * </li><li>
     * $data['personal_flag']:個人引越フラグ
     * </li><li>
     * $data['downloaded_flag']:ダウンロード済みを含むフラグ
     * </li></ul>
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param array $data パラメーター
     * @return Sgmov_Component_DBResult 検索結果
     */
    public function fetchCsvData($db, $data)
    {
        $keys = array('from',
                         'to',
                         'office_flag',
                         'setting_flag',
                         'personal_flag',
                         'downloaded_flag');

        // パラメータのチェック
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new Sgmov_Component_Exception('$data[' . $key . ']が未設定です。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
        }

        $params = array('from'=>$data['from'],
                         'to'=>$data['to']);

        $query = 'SELECT';
        $query .= '        id';
        $query .= '        ,TO_CHAR(created, \'YYYY/MM/DD HH24:MI:SS\')';
        $query .= '        ,answer1';
        $query .= '        ,answer2';
        $query .= '        ,answer2_text';
        $query .= '        ,answer3';
        $query .= '        ,answer4';
        $query .= '        ,answer5';
        $query .= '        ,answer6';
        $query .= '        ,answer7';
        $query .= '        ,answer8';
        $query .= '        ,answer9';
        $query .= '        ,answer10';
        $query .= '    FROM';
        $query .= '        questionnaires';
        $query .= '    WHERE';
        $query .= '        created >= TO_DATE($1, \'YYYYMMDD\')';
        $query .= '        AND created < TO_DATE($2, \'YYYYMMDD\') + 1';

        // ダウンロード済みを含まない場合
        if ($data['downloaded_flag'] !== '1') {
            $query .= ' AND downloaded_flag = \'0\'';
        }

        // 区分が選択されている場合
        if ($data['office_flag'] === '1' || $data['setting_flag'] === '1' || $data['personal_flag'] === '1') {
            $appended = FALSE;

            $query .= ' AND (';
            if ($data['office_flag'] === '1') {
                $query .= ' answer1 = \'オフィス移転\'';
                $appended = TRUE;
            }
            if ($data['setting_flag'] === '1') {
                if ($appended === TRUE) {
                    $query .= ' OR';
                }
                $query .= ' answer1 = \'設置輸送\'';
                $appended = TRUE;
            }
            if ($data['personal_flag'] === '1') {
                if ($appended === TRUE) {
                    $query .= ' OR';
                }
                $query .= ' answer1 = \'個人引越\'';
            }
            $query .= ' )';
        }
        $query .= ' ORDER BY id';

        $result = $db->executeQuery($query, $params);

        $csvData = array();
        // ヘッダ
        $csvData[] = array('ID',
                             '登録日時',
                             '質問1',
                             '質問2',
                             '質問2その他',
                             '質問3',
                             '質問4',
                             '質問5',
                             '質問6',
                             '質問7',
                             '質問8',
                             '質問9',
                             '質問10');
        for ($i = 0; $i < $result->size(); $i++) {
            $csvData[] = $result->get($i);
        }

        Sgmov_Component_Log::debug(Sgmov_Component_String::toDebugString($csvData));
        return $csvData;
    }

    /**
     * アンケート結果のダウンロードフラグを更新します。
     *
     * 既にダウンロード済みのものは更新しません。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param string $userAccount 更新ユーザーのアカウント
     * @param array $recordIds 更新するレコードIDの配列
     */
    public function updateDownloadFlag($db, $userAccount, $recordIds)
    {
        foreach($recordIds as $id){
            $query = 'UPDATE questionnaires';
            $query .= '    SET';
            $query .= '        modify_user_account=$1, modified=now(), downloaded_flag=\'1\'';
            $query .= '    WHERE';
            $query .= '        id = $2 AND downloaded_flag=\'0\'';

            $params = array($userAccount, $id);
            Sgmov_Component_Log::debug("####### START UPDATE questionnaires #####");
            $db->executeUpdate($query, $params);
            Sgmov_Component_Log::debug("####### END UPDATE questionnaires #####");
        }
    }
}
?>
