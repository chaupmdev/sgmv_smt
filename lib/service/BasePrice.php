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
 * 基本料金情報を扱います。
 *
 * @package    Service
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_BasePrice
{
    /**
     * 指定されたコース・プラン・出発エリアから基本料金リストを取得し、
     * 到着エリアID・到着エリア名・料金ID・基本料金・上限料金・下限料金・更新日時それぞれの配列の配列を返します。
     *
     * 到着エリアの開始終了日が(開始日 <= 現在日付 <= 終了日)を満たすものを表示順の昇順で取得します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param string $course_id コースID
     * @param string $plan_id プランID
     * @param string $from_area_id 出発エリアID
     * @return array
     * ['to_area_ids'] 到着エリアIDの文字列配列
     * ['to_area_names'] 到着エリア名の文字列配列
     * ['base_price_ids'] 料金IDの文字列配列
     * ['base_prices'] 基本料金の文字列配列
     * ['max_prices'] 上限料金の文字列配列
     * ['min_prices'] 下限料金の文字列配列
     * ['modifieds'] 更新日時の文字列配列
     */
    public function fetchBasePrices($db, $course_id, $plan_id, $from_area_id)
    {
        $query = 'SELECT';
        $query .= '        to_areas.id AS to_area_id';
        $query .= '        ,to_areas.name AS to_area_name';
        $query .= '        ,base_prices.id AS base_price_id';
        $query .= '        ,base_prices.base_price AS base_price';
        $query .= '        ,base_prices.max_price AS max_price';
        $query .= '        ,base_prices.min_price AS min_price';
        $query .= '        ,base_prices.modified AS modified';
        $query .= '    FROM';
        $query .= '        to_areas';
        $query .= '            LEFT JOIN base_prices';
        $query .= '                ON (';
        $query .= '                    base_prices.to_area_id = to_areas.id';
        $query .= '                    AND base_prices.cource_id = $1';
        $query .= '                    AND base_prices.plan_id = $2';
        $query .= '                    AND base_prices.from_area_id = $3';
        $query .= '                    AND base_prices.start_date <= current_date';
        $query .= '                    AND base_prices.stop_date >= current_date';
        $query .= '                )';
        $query .= '    WHERE';
        $query .= '        to_areas.start_date <= current_date';
        $query .= '        AND to_areas.stop_date >= current_date';
        $query .= '    ORDER BY';
        $query .= '        to_areas.show_order';

        $params = array();
        $params[] = $course_id;
        $params[] = $plan_id;
        $params[] = $from_area_id;

        $to_area_ids = array();
        $to_area_names = array();
        $base_price_ids = array();
        $base_prices = array();
        $max_prices = array();
        $min_prices = array();
        $modifieds = array();

        $result = $db->executeQuery($query, $params);
        for ($i = 0; $i < $result->size(); $i++) {
            $row = $result->get($i);

            $to_area_ids[] = $row['to_area_id'];
            $to_area_names[] = $row['to_area_name'];
            $base_price_ids[] = $row['base_price_id'];
            $base_prices[] = $row['base_price'];
            $max_prices[] = $row['max_price'];
            $min_prices[] = $row['min_price'];
            $modifieds[] = $row['modified'];
        }

        return array('to_area_ids'=>$to_area_ids,
                         'to_area_names'=>$to_area_names,
                         'base_price_ids'=>$base_price_ids,
                         'base_prices'=>$base_prices,
                         'max_prices'=>$max_prices,
                         'min_prices'=>$min_prices,
                         'modifieds'=>$modifieds);
    }

    /**
     * 指定されたコース・プラン・出発エリアから基本料金リストを取得し、
     * 到着エリアID・到着エリア名・料金ID・基本料金・上限料金・下限料金・更新日時それぞれの配列の配列を返します。
     * ※fetchBasePricesと異なるのは、存在するコース・プラン・出発地域のみ返す点です。
     * 
     * 到着エリアの開始終了日が(開始日 <= 現在日付 <= 終了日)を満たすものを表示順の昇順で取得します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param string $course_id コースID
     * @param string $plan_id プランID
     * @param string $from_area_id 出発エリアID
     * @return array
     * ['to_area_ids'] 到着エリアIDの文字列配列
     * ['to_area_names'] 到着エリア名の文字列配列
     * ['base_price_ids'] 料金IDの文字列配列
     * ['base_prices'] 基本料金の文字列配列
     * ['max_prices'] 上限料金の文字列配列
     * ['min_prices'] 下限料金の文字列配列
     * ['modifieds'] 更新日時の文字列配列
     */
    public function fetchBasePricesExistence($db, $course_id, $plan_id, $from_area_id)
    {
        $query = 'SELECT
					    area.id AS to_area_id,
					    area.name AS to_area_name,
					    base.id AS base_price_id,
					    base.base_price AS base_price,
					    base.max_price AS max_price,
					    base.min_price AS min_price,
					    base.modified AS modified
					FROM
					    base_prices base,
					    to_areas area
					WHERE
					    base.cource_id = $1 AND
					    base.plan_id = $2 AND
					    base.from_area_id = $3 AND
					    area.id = base.to_area_id AND
					    current_date between base.start_date AND
					    base.stop_date
					ORDER BY
						area.show_order';

        $params = array();
        $params[] = $course_id;
        $params[] = $plan_id;
        $params[] = $from_area_id;

        $to_area_ids = array();
        $to_area_names = array();
        $base_price_ids = array();
        $base_prices = array();
        $max_prices = array();
        $min_prices = array();
        $modifieds = array();

        $result = $db->executeQuery($query, $params);
        for ($i = 0; $i < $result->size(); $i++) {
            $row = $result->get($i);

            $to_area_ids[] = $row['to_area_id'];
            $to_area_names[] = $row['to_area_name'];
            $base_price_ids[] = $row['base_price_id'];
            $base_prices[] = $row['base_price'];
            $max_prices[] = $row['max_price'];
            $min_prices[] = $row['min_price'];
            $modifieds[] = $row['modified'];
        }

        return array('to_area_ids'=>$to_area_ids,
                         'to_area_names'=>$to_area_names,
                         'base_price_ids'=>$base_price_ids,
                         'base_prices'=>$base_prices,
                         'max_prices'=>$max_prices,
                         'min_prices'=>$min_prices,
                         'modifieds'=>$modifieds);
    }

    /**
     * 基本料金を更新します。
     *
     * データが存在しない場合は作成します。
     * データが既に存在する場合は更新します。
     *
     * 一件でも既に別のユーザーによって更新されている場合は
     * 全ての登録・更新を取り消してFALSEを返します。
     *
     * 全ての更新に成功した場合はTRUEを返します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param string $user_account 更新ユーザーアカウント
     * @param string $course_id コースID
     * @param string $plan_id プランID
     * @param string $from_area_id 出発地ID
     * @param array $base_price_ids 基本料金ID
     * @param array $to_area_ids 到着地ID
     * @param array $base_prices 基本料金
     * @param array $max_prices 上限料金
     * @param array $min_prices 下限料金
     * @param array $modifieds 取得時のタイムスタンプ
     * @return 全ての更新に成功した場合TRUEを、そうでない場合はFALSEを返します。
     */
    public function updateBasePrices($db, $user_account, $course_id, $plan_id, $from_area_id, $base_price_ids, $to_area_ids,
         $base_prices, $max_prices, $min_prices, $modifieds)
    {
        // TODO 未テスト
        $db->begin();

        for ($i = 0; $i < count($base_price_ids); $i++) {
            $ret = $this->_updateBasePrice($db, $user_account, $course_id, $plan_id, $from_area_id, $base_price_ids[$i],
                 $to_area_ids[$i], $base_prices[$i], $max_prices[$i], $min_prices[$i], $modifieds[$i]);
            if ($ret === FALSE) {
                // 更新競合
                $db->rollback();
                return FALSE;
            }
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
            Sgmov_Component_Log::debug('更新に失敗しました。', $e);
            $db->rollback();
            return FALSE;
        }
    }

    /**
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param string $user_account 更新ユーザーアカウント
     * @param string $course_id コースID
     * @param string $plan_id プランID
     * @param string $from_area_id 出発地ID
     * @param string $base_price_id 基本料金ID
     * @param string $to_area_id 到着地ID
     * @param string $base_price 基本料金
     * @param string $max_price 上限料金
     * @param string $min_price 下限料金
     * @param string $modified 取得時のタイムスタンプ
     * @return 更新に成功した場合TRUEをそうでない場合はFALSEを返します。
     */
    public function _updateBasePrice($db, $user_account, $course_id, $plan_id, $from_area_id, $base_price_id, $to_area_id,
         $base_price, $max_price, $min_price, $modified)
    {
        // TODO 未テスト
        $query = 'UPDATE';
        $query .= '        base_prices';
        $query .= '    SET';
        $query .= '        base_price = $1';
        $query .= '        ,max_price = $2';
        $query .= '        ,min_price = $3';
        $query .= '        ,modify_user_account = $4';
        $query .= '        ,modified = now()';
        $query .= '    WHERE';
        $query .= '        id = $5';
        $query .= '        AND cource_id = $6';
        $query .= '        AND plan_id = $7';
        $query .= '        AND from_area_id = $8';
        $query .= '        AND to_area_id = $9';

        $params = array($base_price,
                         $max_price,
                         $min_price,
                         $user_account,
                         $base_price_id,
                         $course_id,
                         $plan_id,
                         $from_area_id,
                         $to_area_id);

        // 初期値がNULLなので
        if (is_null($modified)) {
            $query .= '        AND modified ISNULL';
        } else {
            $query .= '        AND modified = $10';
            $params[] = $modified;
        }

        $affecteds = $db->executeUpdate($query, $params);
        if ($affecteds !== 1) {
            Sgmov_Component_Log::debug('更新に失敗しました。');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * 指定されたコース・プラン・出発エリア・到着エリアから基本料金を取得します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param string $course_id コースID
     * @param string $plan_id プランID
     * @param string $from_area_id 出発エリアID
     * @param string $to_area_id 到着エリアID
     * @return array
     * ['base_price'] 基本料金
     * ['max_price'] 上限料金
     * ['min_price'] 下限料金
     */
    public function getBaseMinMaxPrice($db, $course_id, $plan_id, $from_area_id, $to_area_id)
    {
        // TODO 未テスト
        $query = 'SELECT';
        $query .= '        base_price';
        $query .= '        ,max_price';
        $query .= '        ,min_price';
        $query .= '    FROM';
        $query .= '        base_prices';
        $query .= '    WHERE';
        $query .= '        cource_id = $1';
        $query .= '        AND plan_id = $2';
        $query .= '        AND from_area_id = $3';
        $query .= '        AND to_area_id = $4';
        $query .= '        AND start_date <= current_date';
        $query .= '        AND stop_date >= current_date';

        $params = array($course_id,
                         $plan_id,
                         $from_area_id,
                         $to_area_id);

        $result = $db->executeQuery($query, $params);
        if ($result->size() != 1) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, '基本料金データ不整合');
        }
        return $result->get(0);
    }
}
?>
