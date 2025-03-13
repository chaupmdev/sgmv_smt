<?php
/**
 * @package    ClassDefFile
 * @author     T.Aono(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents(FALSE);
Sgmov_Lib::useServices('CoursePlan');
/**#@-*/

 /**
 * 特価情報を扱います。
 * TODO 未テスト
 *
 * @package    Service
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_OtherCampaign
{
    /**
     * 特価区分：閑散繁忙期設定
     */
    const SPECIAL_PRICE_DIVISION_EXTRA = '1';

    /**
     * 特価区分：キャンペーン設定
     */
    const SPECIAL_PRICE_DIVISION_CAMPAIGN = '2';

    /**
     * 特価IDから対象日付のリストを昇順で取得します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param string $id 特価ID
     * @return array
     * ['target_dates'] 対象日付の文字列配列(YYYY-MM-DD)
     */
    public function fetchTargetDates($db, $id)
    {
        $query = 'SELECT';
        $query .= '        TO_CHAR(target_date, \'YYYY-MM-DD\') AS target_date';
        $query .= '    FROM';
        $query .= '        special_prices_dates';
        $query .= '    WHERE';
        $query .= '        special_price_id = $1';
        $query .= '    ORDER BY';
        $query .= '        target_date';

        $params = array($id);

        $target_dates = array();

        $result = $db->executeQuery($query, $params);
        for ($i = 0; $i < $result->size(); $i++) {
            $row = $result->get($i);
            $target_dates[] = $row['target_date'];
        }

        return array('target_dates'=>$target_dates);
    }

    /**
     * 特価を新規登録します。
     *
     * [$specialPrice]<br>
     * 次の連想配列
     * <ul><li>
     * $specialPrice['draft_flag']:下書きフラグ
     * </li><li>
     * $specialPrice['center_id']:担当拠点ID
     * </li><li>
     * $specialPrice['special_price_division']:特価区分('1':閑散繁忙、'2':キャンペーン)
     * </li><li>
     * $specialPrice['title']:タイトル
     * </li><li>
     * $specialPrice['description']:説明(閑散繁忙の場合はNULLを設定)
     * </li><li>
     * $specialPrice['create_user_name']:登録者名
     * </li><li>
     * $specialPrice['min_date']:最小日付(YYYY-MM-DD)
     * </li><li>
     * $specialPrice['max_date']:最大日付(YYYY-MM-DD)
     * </li><li>
     * $specialPrice['priceset_kbn']:金額設定区分('1':なし、'2':一括、'3':個別)
     * </li><li>
     * $specialPrice['batchprice']:一括設定金額(一括以外の場合はNULLを設定)
     * </li></ul>
     *
     * [$coursePlanIds]<br>
     * 次の連想配列を項目に持つ配列
     * <ul><li>
     * $coursePlanId['course_id']:コースID
     * </li><li>
     * $coursePlanId['plan_id']:プランID
     * </li></ul>
     *
     * [$fromAreaIds]<br>
     * 出発エリアIDの配列
     *
     * [$toAreaIds]<br>
     * 到着エリアIDの配列
     *
     * [$targetDays]<br>
     * 対象日付の配列(YYYY-MM-DD)
     *
     * [$specialPriceDetails]<br>
     * 特価明細<br>
     * 金額個別設定の場合のみ指定<br>
     * 金額一括指定の場合は一括設定金額を元に特価明細情報が登録されます。<br>
     * {@link Sgmov_Service_CoursePlan::ID_DELIMITER} を区切り文字として
     * 「コースID」+「プランID」+「出発エリアID」+「到着エリアID」
     * を連結した文字列をキーに持ち、値には差額を持ちます。
     *
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param string $userAccount ユーザーアカウント
     * @param string $priceSetKbn '1':
     * @param array $specialPrice 特価基本情報
     * @param array $coursePlanIds コースプラン紐付け情報
     * @param array $fromAreaIds 出発エリア情報
     * @param array $toAreaIds 到着エリア情報
     * @param array $targetDays 対象日付情報
     * @param array $specialPriceDetails [optional] 特価明細情報
     * @return 成功した場合にTRUEを、失敗した場合にFALSEを返します。
     */
    public function insertSpecialPriceData($db, $userAccount, $specialPrice, $coursePlanIds, $fromAreaIds, $toAreaIds, $targetDays,
         $specialPriceDetails = NULL)
    {
        // トランザクション内で実行
        $db->begin();

        // 特価IDを作成
        $id = $this->_nextSpeialPriceId($db);

        // 登録
        $this->_insertSpecialPrice($db, $id, $specialPrice, $userAccount);

        // 関連の登録
        $this->_putCoursesPlansSpecialPrices($db, $id, $coursePlanIds);
        $this->_putFromAreasSpecialPrices($db, $id, $fromAreaIds);
        $this->_putSpecialPricesToAreas($db, $id, $toAreaIds);
        $this->_putSpecialPricesDates($db, $id, $targetDays);

        // 特価明細
        if ($specialPrice['priceset_kbn'] === '1') {
            // 金額指定なし
        } else if ($specialPrice['priceset_kbn'] === '2') {
            // 金額指定一括
            $this->_putSpecialPriceDetailsBatchPrice($db, $id, $coursePlanIds, $fromAreaIds, $toAreaIds, $specialPrice['batchprice']);
        } else if ($specialPrice['priceset_kbn'] === '3') {
            // 金額指定個別
            $this->_putSpecialPriceDetailsIndividualPrice($db, $id, $coursePlanIds, $fromAreaIds, $toAreaIds, $specialPriceDetails);
        } else {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, 'priceset_kbnが不正。');
        }

        $prevThrowError = $db->getThrowError();
        try {
            // コミット時のみエラーを内部処理せずに投げるようにする
            $db->setThrowError(TRUE);
            $db->commit();
            $db->setThrowError($prevThrowError);
            return TRUE;
        }
        catch (exception $e) {
            $db->setThrowError($prevThrowError);
            // 更新競合
            Sgmov_Component_Log::debug('登録に失敗しました。', $e);
            $db->rollback();
            return FALSE;
        }
    }

    /**
     * 特価を更新します。
     *
     * [$specialPrice]<br>
     * 次の連想配列
     * <ul><li>
     * $specialPrice['draft_flag']:下書きフラグ
     * </li><li>
     * $specialPrice['center_id']:担当拠点ID
     * </li><li>
     * $specialPrice['special_price_division']:特価区分('1':閑散繁忙、'2':キャンペーン)
     * </li><li>
     * $specialPrice['title']:タイトル
     * </li><li>
     * $specialPrice['description']:説明(閑散繁忙の場合はNULLを設定)
     * </li><li>
     * $specialPrice['create_user_name']:登録者名
     * </li><li>
     * $specialPrice['min_date']:最小日付(YYYY-MM-DD)
     * </li><li>
     * $specialPrice['max_date']:最大日付(YYYY-MM-DD)
     * </li><li>
     * $specialPrice['priceset_kbn']:金額設定区分('1':なし、'2':一括、'3':個別)
     * </li><li>
     * $specialPrice['batchprice']:一括設定金額(一括以外の場合はNULLを設定)
     * </li></ul>
     *
     * [$coursePlanIds]<br>
     * 次の連想配列を項目に持つ配列
     * <ul><li>
     * $coursePlanId['course_id']:コースID
     * </li><li>
     * $coursePlanId['plan_id']:プランID
     * </li></ul>
     *
     * [$fromAreaIds]<br>
     * 出発エリアIDの配列
     *
     * [$toAreaIds]<br>
     * 到着エリアIDの配列
     *
     * [$targetDays]<br>
     * 対象日付の配列(YYYY-MM-DD)
     *
     * [$specialPriceDetails]<br>
     * 特価明細<br>
     * 金額個別設定の場合のみ指定<br>
     * 金額一括指定の場合は一括設定金額を元に特価明細情報が登録されます。<br>
     * {@link Sgmov_Service_CoursePlan::ID_DELIMITER} を区切り文字として
     * 「コースID」+「プランID」+「出発エリアID」+「到着エリアID」
     * を連結した文字列をキーに持ち、値には差額を持ちます。
     *
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param string $id 特価ID
     * @param string $timestamp 情報取得時の更新タイムスタンプ
     * @param string $userAccount ユーザーアカウント
     * @param string $priceSetKbn '1':
     * @param array $specialPrice 特価基本情報
     * @param array $coursePlanIds コースプラン紐付け情報
     * @param array $fromAreaIds 出発エリア情報
     * @param array $toAreaIds 到着エリア情報
     * @param array $targetDays 対象日付情報
     * @param array $specialPriceDetails [optional] 特価明細情報
     * @return 成功した場合にTRUEを、失敗した場合にFALSEを返します。
     */
    public function updateSpecialPrice($db, $id, $timestamp, $userAccount, $specialPrice, $coursePlanIds, $fromAreaIds,
         $toAreaIds, $targetDays, $specialPriceDetails = NULL)
    {
        // トランザクション内で実行
        $db->begin();

        // 更新
        if (!$this->_updateSpecialPrice($db, $id, $timestamp, $specialPrice, $userAccount)) {
            Sgmov_Component_Log::debug('登録に失敗しました。', $e);
            $db->rollback();
            return FALSE;
        }

        // 関連の削除
        $this->_deleteCoursesPlansSpecialPrices($db, $id);
        $this->_deleteFromAreasSpecialPrices($db, $id);
        $this->_deleteSpecialPricesToAreas($db, $id);
        $this->_deleteSpecialPricesDates($db, $id);
        $this->_deleteSpecialPricesDetails($db, $id);

        // 関連の登録
        $this->_putCoursesPlansSpecialPrices($db, $id, $coursePlanIds);
        $this->_putFromAreasSpecialPrices($db, $id, $fromAreaIds);
        $this->_putSpecialPricesToAreas($db, $id, $toAreaIds);
        $this->_putSpecialPricesDates($db, $id, $targetDays);

        // 特価明細
        if ($specialPrice['priceset_kbn'] === '1') {
            // 金額指定なし
        } else if ($specialPrice['priceset_kbn'] === '2') {
            // 金額指定一括
            $this->_putSpecialPriceDetailsBatchPrice($db, $id, $coursePlanIds, $fromAreaIds, $toAreaIds, $specialPrice['batchprice']);
        } else if ($specialPrice['priceset_kbn'] === '3') {
            // 金額指定個別
            $this->_putSpecialPriceDetailsIndividualPrice($db, $id, $coursePlanIds, $fromAreaIds, $toAreaIds, $specialPriceDetails);
        } else {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, 'priceset_kbnが不正。');
        }

        $prevThrowError = $db->getThrowError();
        try {
            // コミット時のみエラーを内部処理せずに投げるようにする
            $db->setThrowError(TRUE);
            $db->commit();
            $db->setThrowError($prevThrowError);
            return TRUE;
        }
        catch (exception $e) {
            $db->setThrowError($prevThrowError);
            // 更新競合
            Sgmov_Component_Log::debug('登録に失敗しました。', $e);
            $db->rollback();
            return FALSE;
        }
    }

    /**
     * 指定された特価IDに関連する特価・コース・プラン紐付けテーブルのレコードを全て削除します。
     * @param Sgmov_Component_DB $db DB接続
     * @param string $specialPriceId 特価ID
     */
    public function _deleteCoursesPlansSpecialPrices($db, $specialPriceId)
    {
        $query = 'DELETE FROM cources_plans_special_prices WHERE special_price_id = $1';
        $params = array($specialPriceId);
        Sgmov_Component_Log::debug("####### START DELETE cources_plans_special_prices #####");
        $count = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END DELETE cources_plans_special_prices #####");
        Sgmov_Component_Log::debug("特価ID[{$specialPriceId}]に関連する{$count}件の特価・コース・プラン紐付けレコードが削除されます。");
    }

    /**
     * 指定された特価IDに関連する特価・出発エリア紐付けテーブルのレコードを全て削除します。
     * @param Sgmov_Component_DB $db DB接続
     * @param string $specialPriceId 特価ID
     */
    public function _deleteFromAreasSpecialPrices($db, $specialPriceId)
    {
        $query = 'DELETE FROM from_areas_special_prices WHERE special_price_id = $1';
        $params = array($specialPriceId);
        Sgmov_Component_Log::debug("####### START DELETE from_areas_special_prices #####");
        $count = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END DELETE from_areas_special_prices #####");
        Sgmov_Component_Log::debug("特価ID[{$specialPriceId}]に関連する{$count}件の特価・出発エリア紐付けレコードが削除されます。");
    }

    /**
     * 指定された特価IDに関連する特価・到着エリア紐付けテーブルのレコードを全て削除します。
     * @param Sgmov_Component_DB $db DB接続
     * @param string $specialPriceId 特価ID
     */
    public function _deleteSpecialPricesToAreas($db, $specialPriceId)
    {
        $query = 'DELETE FROM special_prices_to_areas WHERE special_price_id = $1';
        $params = array($specialPriceId);
        Sgmov_Component_Log::debug("####### START DELETE special_prices_to_areas #####");
        $count = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END DELETE special_prices_to_areas #####");
        Sgmov_Component_Log::debug("特価ID[{$specialPriceId}]に関連する{$count}件の特価・到着エリア紐付けレコードが削除されます。");
    }

    /**
     * 指定された特価IDに関連する特価日付テーブルのレコードを全て削除します。
     * @param Sgmov_Component_DB $db DB接続
     * @param string $specialPriceId 特価ID
     */
    public function _deleteSpecialPricesDates($db, $specialPriceId)
    {
        $query = 'DELETE FROM special_prices_dates WHERE special_price_id = $1';
        $params = array($specialPriceId);
        Sgmov_Component_Log::debug("####### START DELETE special_prices_dates #####");
        $count = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END DELETE special_prices_dates #####");
        Sgmov_Component_Log::debug("特価ID[{$specialPriceId}]に関連する{$count}件の特価日付レコードが削除されます。");
    }

    /**
     * 指定された特価IDに関連する特価明細テーブルのレコードを全て削除します。
     * @param Sgmov_Component_DB $db DB接続
     * @param string $specialPriceId 特価ID
     */
    public function _deleteSpecialPricesDetails($db, $specialPriceId)
    {
        $query = 'DELETE FROM special_price_details WHERE special_price_id = $1';
        $params = array($specialPriceId);
        Sgmov_Component_Log::debug("####### START DELETE special_price_details #####");
        $count = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END DELETE special_price_details #####");
        Sgmov_Component_Log::debug("特価ID[{$specialPriceId}]に関連する{$count}件の特価明細レコードが削除されます。");
    }

    /**
     * 新たな特価IDをシーケンスから取得します。
     * @param Sgmov_Component_DB $db DB接続
     * @return string 特価ID
     */
    public function _nextSpeialPriceId($db)
    {
        $query = 'SELECT nextval(\'special_prices_id_seq\') AS nextval';
        $result = $db->executeQuery($query);
        $row = $result->get(0);
        return $row['nextval'];
    }

    /**
     * 他社連携キャンペーンを新規作成します。
     * @param Sgmov_Component_DB $db DB接続
     * @param string $id  
     * @param array $specialPrice 特価情報
     * @param string $userAccount ユーザーアカウント
     */
    public function insertOtherCampaign($db, $name, $flg, $content, $application)
    {
    	$name    = pg_escape_string($name);
   		$content = pg_escape_string($content);
		
        $query = 'INSERT';
        $query .= '    INTO';
        $query .= '        aoc_campaign(';
        $query .= '            campaign_name';
        $query .= '            ,campaign_flg';
        $query .= '            ,campaign_content';
        $query .= '            ,campaign_application';
        $query .= '            ,created';
        $query .= '            ,modified';
        $query .= '        )';
        $query .= '    VALUES';
        $query .= '        (';
        $query .= '            \''.$name.'\'';
		$query .= '            ,\''.$flg.'\'';
		$query .= '            ,\''.$content.'\'';
		$query .= '            ,'.$application;
		$query .= '            ,now()';
        $query .= '            ,now()';
        $query .= '        )';

        if (Sgmov_Component_Log::isDebug()) {
            $log = Sgmov_Component_String::toDebugString($name);
            Sgmov_Component_Log::debug("他社連携キャンペーンを登録します。name={$log}");
        }
        Sgmov_Component_Log::debug("####### START INSERT aoc_campaign #####");
        $db->executeUpdate($query);
        Sgmov_Component_Log::debug("####### END INSERT aoc_campaign #####");
    }

    /**
     * 他社連携キャンペーンを更新します。
     * @param Sgmov_Component_DB $db DB接続
     * @param string $id  
     * @param array $specialPrice 特価情報
     * @param string $userAccount ユーザーアカウント
     */
    public function updateOtherCampaign($db, $name, $flg, $content, $application, $id)
    {
    	$name    = pg_escape_string($name);
   		$content = pg_escape_string($content);
		
   		$query = 'UPDATE';
        $query .= '        aoc_campaign';
        $query .= '    SET';
        $query .= '        campaign_name = \''.$name.'\'';
        $query .= '        ,campaign_flg = \''.$flg.'\'';
        $query .= '        ,campaign_content = \''.$content.'\'';
        $query .= '        ,campaign_application = '.$application;
        $query .= '        ,modified = now()';
        $query .= '    WHERE';
        $query .= '        id='.$id;
	
        if (Sgmov_Component_Log::isDebug()) {
            $log = Sgmov_Component_String::toDebugString($name);
            Sgmov_Component_Log::debug("他社連携キャンペーンを更新します。name={$log}");
        }
        Sgmov_Component_Log::debug("####### START UPDATE aoc_campaign #####");
        $db->executeUpdate($query);
        Sgmov_Component_Log::debug("####### END UPDATE aoc_campaign #####");
    }


    /**
     * 特価レコードを更新します。
     * @param Sgmov_Component_DB $db DB接続
     * @param string $id  特価ID
     * @param string $timestamp  情報取得時の更新タイムスタンプ
     * @param array $specialPrice 特価情報
     * @param string $userAccount ユーザーアカウント
     * @return 更新に成功した場合TRUEをそうでない場合はFALSEを返します。
     */
    public function _updateSpecialPrice($db, $id, $timestamp, $specialPrice, $userAccount)
    {
        $params = array($specialPrice['draft_flag'],
                         $specialPrice['center_id'],
                         $specialPrice['special_price_division'],
                         $specialPrice['title'],
                         $specialPrice['description'],
                         $specialPrice['create_user_name'],
                         $specialPrice['min_date'],
                         $specialPrice['max_date'],
                         $specialPrice['priceset_kbn'],
                         $specialPrice['batchprice'],
                         $userAccount,
                         $id,
                         $timestamp);

        $query = 'UPDATE';
        $query .= '        special_prices';
        $query .= '    SET';
        $query .= '        draft_flag = $1';
        $query .= '        ,center_id = $2';
        $query .= '        ,special_price_division = $3';
        $query .= '        ,title = $4';
        $query .= '        ,description = $5';
        $query .= '        ,create_user_name = $6';
        $query .= '        ,min_date = $7';
        $query .= '        ,max_date = $8';
        $query .= '        ,priceset_kbn = $9';
        $query .= '        ,batchprice = $10';
        $query .= '        ,modify_user_account = $11';
        $query .= '        ,modified = now()';
        $query .= '    WHERE';
        $query .= '        id=$12 AND modified=$13';

        if (Sgmov_Component_Log::isDebug()) {
            $log = Sgmov_Component_String::toDebugString($specialPrice);
            Sgmov_Component_Log::debug("特価レコードを登録します。specialPrice={$log}");
        }
        Sgmov_Component_Log::debug("####### START UPDATE special_prices #####");
        $affecteds = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END UPDATE special_prices #####");
        if ($affecteds !== 1) {
            Sgmov_Component_Log::debug('更新に失敗しました。');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * 特価日付レコードを一括登録します。
     * @param Sgmov_Component_DB $db DB接続
     * @param string $specialPriceId 特価ID
     * @param array $targetDays 対象日付の配列
     */
    public function _putSpecialPricesDates($db, $specialPriceId, $targetDays)
    {
        foreach ($targetDays as $targetDay) {
            $row = $specialPriceId;
            $row .= "\t";
            $row .= $targetDay;
            $rows[] = $row;
        }
        $db->executeCopyFrom('special_prices_dates', $rows);
    }

    /**
     * 特価・出発エリア紐付けレコードを一括登録します。
     * @param Sgmov_Component_DB $db DB接続
     * @param string $specialPriceId 特価ID
     * @param array $fromAreaIds 出発エリアIDの配列
     */
    public function _putFromAreasSpecialPrices($db, $specialPriceId, $fromAreaIds)
    {
        foreach ($fromAreaIds as $fromAreaId) {
            $row = $specialPriceId;
            $row .= "\t";
            $row .= $fromAreaId;
            $rows[] = $row;
        }
        $db->executeCopyFrom('from_areas_special_prices', $rows);
    }

    /**
     * 特価・到着エリア紐付けレコードを一括登録します。
     * @param Sgmov_Component_DB $db DB接続
     * @param string $specialPriceId 特価ID
     * @param array $toAreaIds 到着エリアIDの配列
     */
    public function _putSpecialPricesToAreas($db, $specialPriceId, $toAreaIds)
    {
        foreach ($toAreaIds as $toAreaId) {
            $row = $specialPriceId;
            $row .= "\t";
            $row .= $toAreaId;
            $rows[] = $row;
        }
        $db->executeCopyFrom('special_prices_to_areas', $rows);
    }

    /**
     * 特価・コース・プラン紐付けレコードを一括登録します。
     * @param Sgmov_Component_DB $db DB接続
     * @param string $specialPriceId 特価ID
     * @param array $coursePlanIds コースプランIDの配列
     */
    public function _putCoursesPlansSpecialPrices($db, $specialPriceId, $coursePlanIds)
    {
        foreach ($coursePlanIds as $coursePlanId) {
            $row = $specialPriceId;
            $row .= "\t";
            $row .= $coursePlanId['course_id'];
            $row .= "\t";
            $row .= $coursePlanId['plan_id'];
            $rows[] = $row;
        }
        $db->executeCopyFrom('cources_plans_special_prices', $rows);
    }

    /**
     * 金額一括指定の特価明細レコードを一括登録します。
     * @param Sgmov_Component_DB $db DB接続
     * @param string $specialPriceId 特価ID
     * @param array $coursePlanIds コースプラン紐付け情報
     * @param array $fromAreaIds 出発エリア情報
     * @param array $toAreaIds 到着エリア情報
     * @param string $batchPrice 一括指定金額
     */
    public function _putSpecialPriceDetailsBatchPrice($db, $specialPriceId, $coursePlanIds, $fromAreaIds, $toAreaIds, $batchPrice)
    {
        $rows = array();
        foreach ($coursePlanIds as $coursePlanId) {
            foreach ($fromAreaIds as $fromAreaId) {
                foreach ($toAreaIds as $toAreaId) {
                    $row = $specialPriceId;
                    $row .= "\t";
                    $row .= $coursePlanId['course_id'];
                    $row .= "\t";
                    $row .= $coursePlanId['plan_id'];
                    $row .= "\t";
                    $row .= $fromAreaId;
                    $row .= "\t";
                    $row .= $toAreaId;
                    $row .= "\t";
                    $row .= $batchPrice;
                    $rows[] = $row;
                }
            }
        }
        $db->executeCopyFrom('special_price_details', $rows);
    }

    /**
     * 金額個別指定の特価明細レコードを一括登録します。
     * @param Sgmov_Component_DB $db DB接続
     * @param string $specialPriceId 特価ID
     * @param array $coursePlanIds コースプラン紐付け情報
     * @param array $fromAreaIds 出発エリア情報
     * @param array $toAreaIds 到着エリア情報
     * @param string $prices 個別指定金額
     */
    public function _putSpecialPriceDetailsIndividualPrice($db, $specialPriceId, $coursePlanIds, $fromAreaIds, $toAreaIds,
         $prices)
    {
        $delim = Sgmov_Service_CoursePlan::ID_DELIMITER;
        $rows = array();
        foreach ($coursePlanIds as $coursePlanId) {
            foreach ($fromAreaIds as $fromAreaId) {
                foreach ($toAreaIds as $toAreaId) {
                    $key = $coursePlanId['course_id'] . $delim . $coursePlanId['plan_id'] . $delim . $fromAreaId . $delim . $toAreaId;

                    $row = $specialPriceId;
                    $row .= "\t";
                    $row .= $coursePlanId['course_id'];
                    $row .= "\t";
                    $row .= $coursePlanId['plan_id'];
                    $row .= "\t";
                    $row .= $fromAreaId;
                    $row .= "\t";
                    $row .= $toAreaId;
                    $row .= "\t";
                    $row .= $prices[$key];
                    $rows[] = $row;
                }
            }
        }
        $db->executeCopyFrom('special_price_details', $rows);
    }

    /**
     * 指定されたコース・プラン・出発エリア・到着エリア・日付から特価キャンペーン情報を取得します。
     *
     * 特価ID、特価区分、タイトル、金額の配列を返します。
     *
     * ソート順は、特価区分(閑散繁忙→キャンペーン)、特価IDとなります。
     *
     * 下書きフラグがTRUEのものは取得しません。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param string $course_id コースID
     * @param string $plan_id プランID
     * @param string $from_area_id 出発エリアID
     * @param string $to_area_id 到着エリアID
     * @param string $day 対象日
     * @return array
     * ['ids'] 特価IDの文字列配列
     * ['special_price_divisions'] 特価区分の文字列配列(1:閑散繁忙、2:キャンペーン)
     * ['titles'] タイトルの文字列配列
     * ['charges'] 料金の文字列配列
     */
    public function fetchSpecialPrices($db, $course_id, $plan_id, $from_area_id, $to_area_id, $day)
    {
        $query = 'SELECT';
        $query .= '        A.id,A.special_price_division,A.title,COALESCE(F.price_difference, 0) AS price_difference';
        $query .= '    FROM';
        $query .= '        (((((special_prices AS A ';
        $query .= '            INNER JOIN cources_plans_special_prices AS B ON B.special_price_id = A.id)';
        $query .= '            INNER JOIN from_areas_special_prices AS C ON C.special_price_id = A.id)';
        $query .= '            INNER JOIN special_prices_to_areas AS D ON D.special_price_id = A.id)';
        $query .= '            INNER JOIN special_prices_dates AS E ON E.special_price_id = A.id)';
        $query .= '            LEFT JOIN special_price_details AS F ';
        $query .= '                ON F.special_price_id = A.id';
        $query .= '                AND F.cource_id = B.cource_id';
        $query .= '                AND F.plan_id = B.plan_id';
        $query .= '                AND F.from_area_id = C.from_area_id';
        $query .= '                AND F.to_area_id = D.to_area_id)';
        $query .= '    WHERE';
        $query .= '        A.draft_flag = FALSE';
        $query .= '        AND B.cource_id = $1';
        $query .= '        AND B.plan_id = $2';
        $query .= '        AND C.from_area_id = $3';
        $query .= '        AND D.to_area_id = $4';
        $query .= '        AND E.target_date = $5';
        $query .= '    ORDER BY';
        $query .= '        A.special_price_division,A.id';

        $params = array($course_id,
                         $plan_id,
                         $from_area_id,
                         $to_area_id,
                         $day);

        $ids = array();
        $special_price_divisions = array();
        $titles = array();
        $charges = array();

        $result = $db->executeQuery($query, $params);
        for ($i = 0; $i < $result->size(); $i++) {
            $row = $result->get($i);
            $ids[] = $row['id'];
            $special_price_divisions[] = $row['special_price_division'];
            $titles[] = $row['title'];
            $charges[] = $row['price_difference'];
        }

        return array('ids'=>$ids,
                         'special_price_divisions'=>$special_price_divisions,
                         'titles'=>$titles,
                         'charges'=>$charges);
    }

    /**
     * 特価IDから特価の基本情報を取得します。
     * 見つからなかった場合はNULLを返します。
     * @param Sgmov_Component_DB $db DB接続
     * @param string $id 特価ID
     * @return array 特価基本情報
     * ['id'] 特価ID
     * ['center_id'] 担当拠点ID
     * ['center_name'] 担当拠点名
     * ['special_price_division'] 特価区分(1:閑散・繁忙期料金設定、2:キャンペーン設定)
     * ['title'] タイトル
     * ['description'] 説明(キャンペーンのみ)
     * ['draft_flag'] 下書きフラグ
     * ['create_user_name'] 登録者名
     * ['min_date'] 最小日付(YYYY-MM-DD)
     * ['max_date'] 最大日付(YYYY-MM-DD)
     * ['priceset_kbn'] 金額設定区分
     * ['batchprice'] 一括設定金額(一括時のみ)
     * ['created_day'] 作成日時(YYYY-MM-DD)
     * ['timestamp'] 更新日時
     */
    public function fetchSpecialPricesById($db, $id)
    {
        $query = 'SELECT';
        $query .= '        A.id AS id';
        $query .= '        ,A.center_id AS center_id';
        $query .= '        ,B.name AS center_name';
        $query .= '        ,A.special_price_division';
        $query .= '        ,A.title';
        $query .= '        ,A.description';
        $query .= '        ,A.draft_flag';
        $query .= '        ,A.create_user_name';
        $query .= '        ,TO_CHAR(A.min_date, \'YYYY-MM-DD\') AS min_date';
        $query .= '        ,TO_CHAR(A.max_date, \'YYYY-MM-DD\') AS max_date';
        $query .= '        ,A.priceset_kbn';
        $query .= '        ,A.batchprice';
        $query .= '        ,TO_CHAR(A.created, \'YYYY-MM-DD\') AS created_day';
        $query .= '        ,A.modified AS "timestamp"';
        $query .= '    FROM';
        $query .= '        special_prices AS A';
        $query .= '            JOIN centers AS B';
        $query .= '                ON B.id = A.center_id';
        $query .= '    WHERE';
        $query .= '        A.id = $1';

        $result = $db->executeQuery($query, array($id));
        if ($result->size() === 0) {
            return NULL;
        }
        return $result->get(0);
    }

    /**
     * 特価IDからコースプランIDのリストを取得します。
     *
     * コース表示順の昇順、プラン表示順の昇順で取得します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param string $id 特価ID
     * @return array コースプランIDの配列
     */
    public function fetchCoursesPlansSpecialPricesById($db, $id)
    {
        $query = 'SELECT';
        $query .= '        A.cource_id';
        $query .= '        ,A.plan_id';
        $query .= '    FROM';
        $query .= '        (';
        $query .= '            cources_plans_special_prices AS A INNER JOIN cources AS B';
        $query .= '                ON B.id = A.cource_id';
        $query .= '        ) INNER JOIN plans AS C';
        $query .= '        ON C.id = A.plan_id';
        $query .= '    WHERE';
        $query .= '        A.special_price_id = $1';
        $query .= '    ORDER BY';
        $query .= '        B.show_order';
        $query .= '        ,C.show_order';

        $result = $db->executeQuery($query, array($id));
        $ret = array();
        $count = $result->size();
        for ($i = 0; $i < $count; $i++) {
            $row = $result->get($i);
            $ret[] = $row['cource_id'] . Sgmov_Service_CoursePlan::ID_DELIMITER . $row['plan_id'];
        }
        return $ret;
    }

    /**
     * 特価IDから出発エリアIDのリストを取得します。
     *
     * 出発エリア表示順の昇順で取得します
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param string $id 特価ID
     * @return array 出発エリアIDの配列
     */
    public function fetchFromAreasSpecialPricesById($db, $id)
    {
        $query = 'SELECT';
        $query .= '        A.from_area_id';
        $query .= '    FROM';
        $query .= '        from_areas_special_prices AS A';
        $query .= '            INNER JOIN from_areas AS B';
        $query .= '                ON B.id = A.from_area_id';
        $query .= '    WHERE';
        $query .= '        A.special_price_id = $1';
        $query .= '    ORDER BY';
        $query .= '        B.show_order';

        $result = $db->executeQuery($query, array($id));
        $ret = array();
        $count = $result->size();
        for ($i = 0; $i < $count; $i++) {
            $row = $result->get($i);
            $ret[] = $row['from_area_id'];
        }
        return $ret;
    }

    /**
     * 特価IDから到着エリアIDのリストを取得します。
     *
     * 到着エリア表示順の昇順で取得します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param string $id 特価ID
     * @return array 到着エリアIDの配列
     */
    public function fetchSpecialPricesToAreasById($db, $id)
    {
        $query = 'SELECT';
        $query .= '        A.to_area_id';
        $query .= '    FROM';
        $query .= '        special_prices_to_areas AS A';
        $query .= '            INNER JOIN to_areas AS B';
        $query .= '                ON B.id = A.to_area_id';
        $query .= '    WHERE';
        $query .= '        A.special_price_id = $1';
        $query .= '    ORDER BY';
        $query .= '        B.show_order';

        $result = $db->executeQuery($query, array($id));
        $ret = array();
        $count = $result->size();
        for ($i = 0; $i < $count; $i++) {
            $row = $result->get($i);
            $ret[] = $row['to_area_id'];
        }
        return $ret;
    }

    /**
     * 指定された特価ID・コース・プラン・出発エリアから特価明細情報を取得し、
     * 到着エリアID・差額それぞれの配列を返します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param string $id 特価ID
     * @param string $course_id コースID
     * @param string $plan_id プランID
     * @param string $from_area_id 出発エリアID
     * @return array
     * ['to_area_ids'] 到着エリアIDの文字列配列
     * ['price_differences'] 差額の文字列配列
     */
    public function fetchSpecialPriceDetailInfoById($db, $id, $course_id, $plan_id, $from_area_id)
    {
        $query = 'SELECT';
        $query .= '        to_area_id';
        $query .= '        ,price_difference';
        $query .= '    FROM';
        $query .= '        special_price_details';
        $query .= '    WHERE';
        $query .= '        special_price_id = $1';
        $query .= '        AND cource_id = $2';
        $query .= '        AND plan_id = $3';
        $query .= '        AND from_area_id = $4';

        $to_area_ids = array();
        $price_differences = array();

        $result = $db->executeQuery($query, array($id, $course_id, $plan_id, $from_area_id));
        $count = $result->size();
        for ($i = 0; $i < $count; $i++) {
            $row = $result->get($i);
            $to_area_ids[] = $row['to_area_id'];
            $price_differences[] = $row['price_difference'];
        }

        return array('to_area_ids'=>$to_area_ids,
                         'price_differences'=>$price_differences);
    }

    /**
     * 指定されたIDの特価を、更新タイムスタンプが指定されたタイムスタンプと一致する場合のみ削除します。
     * @param Sgmov_Component_DB $db DB接続
     * @param string $id 特価ID
     * @param string $timestamp タイムスタンプ
     * @return 成功した場合にTRUEを、失敗した場合にFALSEを返します。
     */
    public function deleteSpecialPrice($db, $id, $timestamp)
    {
        // トランザクション内で実行
        $db->begin();
        try {
            // 関連の削除
            $this->_deleteCoursesPlansSpecialPrices($db, $id);
            $this->_deleteFromAreasSpecialPrices($db, $id);
            $this->_deleteSpecialPricesToAreas($db, $id);
            $this->_deleteSpecialPricesDates($db, $id);
            $this->_deleteSpecialPricesDetails($db, $id);

            // 削除
            if (!$this->_deleteSpecialPrice($db, $id, $timestamp)) {
                Sgmov_Component_Log::debug('削除に失敗しました。', $e);
                $db->rollback();
                return FALSE;
            }

            $db->commit();
            return TRUE;
        }
        catch (exception $e) {
            // 更新競合
            Sgmov_Component_Log::debug('削除に失敗しました。', $e);
            $db->rollback();
            return FALSE;
        }
    }

    /**
     * 特価レコードを削除します。
     * @param Sgmov_Component_DB $db DB接続
     * @param string $id  特価ID
     * @param string $timestamp  情報取得時の更新タイムスタンプ
     * @return 削除に成功した場合TRUEをそうでない場合はFALSEを返します。
     */
    public function _deleteSpecialPrice($db, $id, $timestamp)
    {
        $query = 'DELETE FROM special_prices WHERE id = $1 AND modified = $2';
        $params = array($id,
                         $timestamp);
        Sgmov_Component_Log::debug("####### START DELETE special_prices #####");
        $count = $db->executeUpdate($query, $params);
        Sgmov_Component_Log::debug("####### END DELETE special_prices #####");
        if ($count !== 1) {
            Sgmov_Component_Log::debug('更新に失敗しました。');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * 指定された特価区分・ステータスの特価情報を取得します。
     * 見つからなかった場合は空の配列を返します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array 他社連携キャンペーン基本情報の配列
     * array(
     * ['id'] 他社連携ID
     * ['campaign_name'] キャンペーン名
     * ['campaign_content'] キャンペーン内容
     * ['campaign_application'] キャンペーン適用
     * ['created] 作成日時(YYYY-MM-DD)
     * ['modified'] 更新日時(YYYY-MM-DD)
     * )
 	 */
	public function fetchSpecialPricesByStatus($db)
    {
        $query = 'SELECT';
        $query .= '        id';
		$query .= '        ,campaign_name';
		$query .= '        ,campaign_content';
		$query .= '        ,campaign_application';
		$query .= '        ,created';
		$query .= '        ,modified';
        $query .= '    FROM';
        $query .= '        aoc_campaign';
        $query .= '    ORDER BY';
        $query .= '        modified';
		$query .= '    DESC';
        
        $rows = array();
        $result = $db->executeQuery($query);
        for ($i = 0; $i < $result->size(); $i++) {
            $rows[] = $result->get($i);
        }
        
        return $rows;
    } 

 /**
     * 指定された特価区分・ステータスの特価情報を取得します。
     * 見つからなかった場合は空の配列を返します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array 他社連携キャンペーン基本情報の配列
     * array(
     * ['id'] 他社連携ID
     * ['campaign_name'] キャンペーン名
     * ['campaign_content'] キャンペーン内容
     * ['campaign_application'] キャンペーン適用
     * ['created] 作成日時(YYYY-MM-DD)
     * ['modified'] 更新日時(YYYY-MM-DD)
     * )
 	 */
	public function fetchOtherCampaignByStatus($db, $id)
    {
        $query = 'SELECT';
        $query .= '        id';
		$query .= '        ,campaign_name';
		$query .= '        ,campaign_flg';
		$query .= '        ,campaign_content';
		$query .= '        ,campaign_application';
		$query .= '        ,created';
		$query .= '        ,modified';
        $query .= '    FROM';
        $query .= '        aoc_campaign';
       	$query .= '    where';
        $query .= '        id ='.$id;
        
        $rows = array();
		
        $result = $db->executeQuery($query);
        $rows = $result->get(0);

		return $rows;
    } 

	/**
     * 指定された特価区分・ステータスの特価情報を取得します。
     * 見つからなかった場合は空の配列を返します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array 他社連携キャンペーン基本情報の配列
     * array(
     * ['id'] 他社連携ID
     * ['campaign_name'] キャンペーン名
     * ['campaign_content'] キャンペーン内容
     * ['campaign_application'] キャンペーン適用
     * ['created] 作成日時(YYYY-MM-DD)
     * ['modified'] 更新日時(YYYY-MM-DD)
     * )
 	 */
	public function OtherCampaignFlgCheck($db,$id)
    {
        $query = 'SELECT';
        $query .= '        campaign_flg';
        $query .= '    FROM';
        $query .= '        aoc_campaign';
		if($id){
			$query .= '    WHERE';
			$query .= '        id <> '.$id;
        }  
    
	    $rows = array();
		$result = $db->executeQuery($query);
		for ($i = 0; $i < $result->size(); $i++) {
            $rows[] = $result->get($i);
        }
      
		return $rows;
    } 
	

    /**
     * 指定された特価区分・ステータスの特価情報を取得します。
     * 見つからなかった場合は空の配列を返します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @return array 他社連携キャンペーン基本情報の配列
     * array(
     * ['id'] 他社連携ID
     * ['campaign_name'] キャンペーン名
     * ['campaign_content'] キャンペーン内容
     * ['campaign_application'] キャンペーン適用
     * ['created] 作成日時(YYYY-MM-DD)
     * ['modified'] 更新日時(YYYY-MM-DD)
     * )
    */
    public function fetchOtherCampaignByStatus2($db, $flg)
    {
        $query  = 'SELECT';
        $query .= '        id';
		$query .= '        ,campaign_name';
		$query .= '        ,campaign_content';
		$query .= '        ,campaign_application';
		$query .= '        ,created';
		$query .= '        ,modified';
        $query .= '    FROM';
        $query .= '        aoc_campaign';
       	$query .= '    where';
        $query .= '        campaign_flg = \''.$flg.'\'';
        $query .= '    and';
        $query .= '        campaign_application = 1';
        
        $result = $db->executeQuery($query);
        if(!$result->size() == 0){
            for ($i = 0; $i < $result->size(); $i++) {
                $rows[] = $result->get($i);
            } 

            return $rows;
        }else{
            return false;
        }	
    } 
	 

    /**
     * 出発エリアIDから本日時点で終了していない公開中のキャンペーン情報を全て取得します。（キャンペーン一覧用）
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param string $id 特価ID
     * @return array 特価情報の配列
     */
    public function fetchSpecialPricesByFromAreaId($db, $id)
    {
        $query = 'SELECT';
        $query .= '        A.id';
        $query .= '        ,A.title';
        $query .= '        ,A.description';
        $query .= '        ,A.min_date';
        $query .= '        ,A.max_date';
        $query .= '        ,C.cource_id';
        $query .= '        ,D.name AS cource_name';
        $query .= '        ,C.plan_id';
        $query .= '        ,E.name AS plan_name';
        $query .= '    FROM';
        $query .= '        special_prices AS A';
        $query .= '            JOIN from_areas_special_prices AS B ON A.id = B.special_price_id';
        $query .= '            JOIN cources_plans_special_prices AS C ON A.id = C.special_price_id';
        $query .= '            JOIN cources AS D ON C.cource_id = D.id';
        $query .= '            JOIN plans AS E ON C.plan_id = E.id';
        $query .= '    WHERE';
        $query .= '        A.special_price_division = 2 AND A.id = B.special_price_id AND B.from_area_id=$1 AND A.draft_flag = \'0\' AND A.max_date >= current_date ';
        $query .= '    ORDER BY A.min_date,A.max_date,A.id';

        $result = $db->executeQuery($query, array($id));
        $count = $result->size();
        if ($count > 0) {
            $ret = array();
            for ($i = 0; $i < $count; $i++) {
                $row = $result->get($i);
                $ids[] = $row['id'];
                $title[] = $row['title'];
                $description[] = $row['description'];
                $min_date[] = $row['min_date'];
                $max_date[] = $row['max_date'];
                $cource_ids[] = $row['cource_id'];
                $cource_names[] = $row['cource_name'];
                $plan_ids[] = $row['plan_id'];
                $plan_names[] = $row['plan_name'];
            }
        } else {
            return null;
        }
        return array('ids'=>$ids,
                         'title'=>$title,
                         'description'=>$description,
                         'min_date'=>$min_date,
						 'max_date'=>$max_date,
						 'cource_ids'=>$cource_ids,
                         'cource_names'=>$cource_names,
						 'plan_ids'=>$plan_ids,
                         'plan_names'=>$plan_names);
    }

//    /**
//     * 特価IDから到着地方ID、エリアID、ラベルのリストを取得します。
//     *
//     * 到着エリア表示順の昇順で取得します。
//     *
//     * @param Sgmov_Component_DB $db DB接続
//     * @param string $id 特価ID
//     * @return array 到着エリアIDとラベルの配列
//     */
//    public function fetchSpecialPricesToAreasById($db, $id)
//    {
//        $query = 'SELECT';
//        $query .= '        A.to_area_id';
//        $query .= '    FROM';
//        $query .= '        special_prices_to_areas AS A';
//        $query .= '            INNER JOIN to_areas AS B';
//        $query .= '                ON B.id = A.to_area_id';
//        $query .= '    WHERE';
//        $query .= '        A.special_price_id = $1';
//        $query .= '    ORDER BY';
//        $query .= '        B.show_order';
//
//        $result = $db->executeQuery($query, array($id));
//        $ret = array();
//        $count = $result->size();
//        for ($i = 0; $i < $count; $i++) {
//            $row = $result->get($i);
//            $ret[] = $row['to_area_id'];
//        }
//        return $ret;
//    }

    /**
     * 本日時点で実施中のキャンペーン（閑散・繁忙期区分除く）から、指定件数分の特価キャンペーン情報を取得します。
     * 開始日の昇順で取得されます。
     *
     * 下書きフラグがTRUEのものは取得しません。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param string $cnt 取得件数
     * @return array
     *             ['title'] タイトルの文字列配列
     *             ['min_date'] 開始日付の文字列配列
     *             ['max_date'] 終了日付の文字列配列
     *             ['description'] 説明の文字列配列
     */
    public function fetchAllCampain($db, $cnt)
    {
        $query = "select
                        title,
                        min_date,
                        max_date,
                        description
                    from
                        special_prices
                    where
                        draft_flag = false and
                        special_price_division = '2' and
                        current_date between min_date and max_date
                    order by
                        min_date
                    offset 0 limit $1
                    ";

        $params = array($cnt);

        $ids = array();
        $special_price_divisions = array();
        $titles = array();
        $charges = array();

        $result = $db->executeQuery($query, $params);
        for ($i = 0; $i < $result->size(); $i++) {
            $row = $result->get($i);
            $titles[] = $row['title'];
            $mindate[] = $row['min_date'];
            $maxdate[] = $row['max_date'];
            $description[] = $row['description'];
        }

        return array('titles'=>$titles,
                         'mindates'=>$mindate,
                         'maxdates'=>$maxdate,
                         'descriptions'=>$description);

    }

    /**
     * 指定されたコース・プラン・出発エリア・到着エリアから、実施中または１年以内に実施開始の特価キャンペーン情報を取得します。
     * 開始日の昇順で取得されます。
     *
     * 下書きフラグがTRUEのものは取得しません。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param string $course_id コースID
     * @param string $plan_id プランID
     * @param string $from_area_id 出発エリアID
     * @param string $to_area_id 到着エリアID
     * @return array
     *             ['ids'] 特価IDの文字列配列
     *             ['title'] タイトルの文字列配列
     *             ['min_date'] 開始日付の文字列配列
     *             ['max_date'] 終了日付の文字列配列
     *             ['description'] 説明の文字列配列
     */
    public function fetchSpecificCampain($db, $course_id, $plan_id, $from_area_id, $to_area_id)
    {
        $query = "SELECT
					    sp.id,
					    sp.special_price_division,
					    sp.title,
					    sp.description,
					    sp.draft_flag,
					    sp.min_date,
					    sp.max_date
					FROM
					    special_prices sp,
					    cources_plans_special_prices cource,
					    from_areas_special_prices frarea,
					    special_prices_to_areas toarea
					WHERE
					    sp.special_price_division = 2 AND
					    sp.draft_flag = false AND
					    (current_date between sp.min_date and sp.max_date or sp.min_date between current_date and current_timestamp + '1 years') AND
					    sp.id = cource.special_price_id AND
					    sp.id = frarea.special_price_id AND
					    sp.id = toarea.special_price_id AND
					    cource.cource_id = $1 AND
					    cource.plan_id = $2 AND
					    frarea.from_area_id = $3 AND
					    toarea.to_area_id = $4
					ORDER BY
					    sp.min_date,
					    sp.id
                ";

        $params = array($course_id,
                         $plan_id,
                         $from_area_id,
                         $to_area_id);

        $ids = array();
        $special_price_divisions = array();
        $titles = array();
        $charges = array();

        $result = $db->executeQuery($query, $params);
        for ($i = 0; $i < $result->size(); $i++) {
            $row = $result->get($i);
            $ids[] = $row['id'];
            $titles[] = $row['title'];
            $mindate[] = $row['min_date'];
            $maxdate[] = $row['max_date'];
            $description[] = $row['description'];
        }

        return array('ids'=>$ids,
                         'titles'=>$titles,
                         'mindates'=>$mindate,
                         'maxdates'=>$maxdate,
                         'descriptions'=>$description);

    }

}
?>
