<?php
/**
 * @package    ClassDefFile
 * @author     H.Tsuji(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/**#@+
 * include files
 */
require_once dirname(__FILE__).'/../../Lib.php';
Sgmov_Lib::useAllComponents();
Sgmov_Lib::useServices(array('CenterArea', 'CoursePlan', 'SpecialPrice', 'AppCommon'));
Sgmov_Lib::useView('Public');
/**#@-*/
/**
 * 概算見積り入力処理を管理する抽象クラスです。
 * @package    View
 * @subpackage PRE
 * @author     H.Tsuji(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Pre_Common extends Sgmov_View_Public {

    /**
     * ラジオボタン選択HTML
     */
    const CHECKED = 'checked="checked"';

    /**
     * 機能ID（PRE001）
     */
    const SCRID_PRE = 'PRE';

    /**
     * 機能ID（PRE001）
     */
    const SCRID_TOPVE = 'TOPVE';

    /**
     * 機能ID（PVE001）
     */
    const FEATURE_ID = 'PVE';

    /**
     * 入力画面引越し予定年、取得年数
     *
     */
    const INPUT_MOVEYTIYEAR_CNT = 1;

    /**
     * 入力画面キャンペーン取得件数
     *
     */
    const INPUT_CAMPGETCNT = 3;

    /**
     * 概算見積りを計算するボタン
     *
     */
    const FUNC_INIT = 0;

    /**
     * 訪問見積りから戻ってきた際のセッションクラス<br />
     * <br />
     *  pve の Common.php からコピーしているのか、クラス名は pve のまま。<br />
     *  正しくは 概算見積もりからの セッション クラス名
     *
     */
    const PVE_SESSION_CLASS = 'Sgmov_Form_Pre002Out';

    /**
     * カレンダー内リンク（日付リンク）
     *
     */
    const FUNC_CALLINK_DAY = 1;

    /**
     * カレンダー内リンク（前月・次月リンク）
     *
     */
    const FUNC_CALLINK_MONTH = 2;

    /**
     * カレンダー内リンク（前週・次週リンク）
     *
     */
    const FUNC_CALLINK_WEEK = 3;

    /**
     * 地域プルダウン（通常）
     */
    const AREA_HYOJITYPE_NORMAL = '1';

    /**
     * 地域プルダウン（沖縄なし）
     */
    const AREA_HYOJITYPE_OKINAWANASHI = '2';

    /**
     * 地域プルダウン（単身エアカーゴプラン）
     */
    const AREA_HYOJITYPE_AIRCARGO = '3';

    /**
     * 地域プルダウン（単身エアカーゴプラン　大阪無し）
     */
    const AREA_HYOJITYPE_AIRCARGO_TO = '4';

    /**
     * 地域プルダウン（単身エアカーゴプラン　福岡発の到着地）
     */
    const AREA_HYOJITYPE_AIRCARGO_TO_FUK = '5';

    /**
     * 地域プルダウン（単身エアカーゴプラン　東京発の到着地）
     */
    const AREA_HYOJITYPE_AIRCARGO_TO_TOK = '6';

    /**
     * 地域プルダウン（単身エアカーゴプラン　北海道発の到着地）
     */
    const AREA_HYOJITYPE_AIRCARGO_TO_HOK = '7';

    /**
     * 概算見積り入力画面、結果画面で「その他のコースを表示する」ボタンをクリックした際の
     * プランの表示ケースをJavaScriptの引数に設定する
     *
     * @param type 見積りタイプ
     * @param corce コース
     * @param plan プラン
     * @return プランの表示ケース
     */
    public function getJavaScriptParam($type, $corce, $plan) {
        if ($type == 1) {
            // 単身
            return 0;
        } elseif ($type == 2) {
            // 家族
            return 1;
        } else {
            // タイプ指定なし
            if ($corce == 1) {
                // 単身コース
                if ($plan == 1) {
                    // カーゴプラン
                    return 3;
                } elseif ($plan == 2) {
                    // エアカーゴプラン
                    return 4;
                } else {
                    return 2;
                }
            } else {
                // 単身コース以外
                return 1;
            }
        }
    }

    /**
     * 概算見積り入力画面に表示するコース返却します。
     *
     * @param initHyojiCorce コースリスト
     * @param type 見積りタイプ
     * @param corce コース
     * @param plan プラン
     * @return 初期表示コースNoリスト
     */
    public function _getInitHyojiCorce($initHyojiCorce, $type, $corce, $plan) {

        // 該当データ有無フラグ
        $hitFlg = true;

        // タイプ指定なし
        if ($type == 0) {
            // コース指定なし
            if ($corce == 0) {
                // プラン指定なし
                if ($plan == 0) {
                    $initHyojiCorce['CARGO'] = true;
                    $initHyojiCorce['SYOURYO'] = true;
                    $initHyojiCorce['ONE'] = true;
                    $initHyojiCorce['TWO'] = true;
                    $initHyojiCorce['THREE'] = true;
                    $initHyojiCorce['FOUR'] = true;
                    $initHyojiCorce['FIVE'] = true;
                    $initHyojiCorce['SIX'] = true;
                } // 単身プラン/少量プラン
                elseif ($plan == 1 || $plan == 2) {
                    $initHyojiCorce['CARGO'] = true;
                } // スタンダードプラン/おまかせプラン/チャータープラン
                elseif ($plan == 3 || $plan == 4 || $plan == 5) {
                    $initHyojiCorce['SYOURYO'] = true;
                    $initHyojiCorce['ONE'] = true;
                    $initHyojiCorce['TWO'] = true;
                    $initHyojiCorce['THREE'] = true;
                    $initHyojiCorce['FOUR'] = true;
                    $initHyojiCorce['FIVE'] = true;
                    $initHyojiCorce['SIX'] = true;
                } else {
                    // 該当データなし
                    $hitFlg = false;
                }
            } // 単身コース
            elseif ($corce == 1) {
                $initHyojiCorce['CARGO'] = true;
            } // 少量コース
            elseif ($corce == 2) {
                $initHyojiCorce['SYOURYO'] = true;
            } // 1人部屋コース
            elseif ($corce == 3) {
                $initHyojiCorce['ONE'] = true;
            } // 2人部屋コース
            elseif ($corce == 4) {
                $initHyojiCorce['TWO'] = true;
            } // 3人部屋コース
            elseif ($corce == 5) {
                $initHyojiCorce['THREE'] = true;
            } // 4人部屋コース
            elseif ($corce == 6) {
                $initHyojiCorce['FOUR'] = true;
            } // 5人部屋コース
            elseif ($corce == 7) {
                $initHyojiCorce['FIVE'] = true;
            } // 6人部屋コース
            elseif ($corce == 8) {
                $initHyojiCorce['SIX'] = true;
            } else {
                // 該当データなし
                $hitFlg = false;
            }
        } // タイプ単身
        elseif ($type == 1) {
            $initHyojiCorce['CARGO'] = true;
            $initHyojiCorce['SYOURYO'] = true;
            $initHyojiCorce['ONE'] = true;
            $initHyojiCorce['TWO'] = true;
            $initHyojiCorce['THREE'] = true;
        } // タイプ家族
        elseif ($type == 2) {
            $initHyojiCorce['THREE'] = true;
            $initHyojiCorce['FOUR'] = true;
            $initHyojiCorce['FIVE'] = true;
            $initHyojiCorce['SIX'] = true;
        } else {
            // 該当データなし
            $hitFlg = false;
        }

        // 該当データなしの場合
        if (!$hitFlg) {
            $initHyojiCorce['CARGO'] = true;
            $initHyojiCorce['SYOURYO'] = true;
            $initHyojiCorce['ONE'] = true;
            $initHyojiCorce['TWO'] = true;
            $initHyojiCorce['THREE'] = true;
            $initHyojiCorce['FOUR'] = true;
            $initHyojiCorce['FIVE'] = true;
            $initHyojiCorce['SIX'] = true;
        }

        return $initHyojiCorce;
    }

    /**
     * 概算見積り入力画面を非活性にするプランを返却します。
     *
     * @param initHyojiPlan プランリスト
     * @param type 見積りタイプ
     * @param corce コース
     * @param plan プラン
     * @return 初期表示プランNoリスト
     */
    public function _getInitHyojiPlan($initHyojiPlan, $type, $corce, $plan) {

        // 該当データ有無フラグ
        $hitFlg = true;

        if ($type == 2) {
            // 家族タイプ
            $initHyojiPlan['STANDARD'] = "";
            $initHyojiPlan['OMAKASE'] = "";
            $initHyojiPlan['CHARTAR'] = "";
        } else
            if ($type == 0) {
                // タイプ指定なし
                if ($corce == 1) {
                    // 単身コース
                    if ($plan == 1) {
                        // 単身カーゴプラン
                        $initHyojiPlan['CARGO'] = "";
                    } else
                        if ($plan == 2) {
                            // 単身AIRCARGOプラン
                            $initHyojiPlan['AIRCARGO'] = "";
                        } else {
                            // プラン指定なし
                            $initHyojiPlan['CARGO'] = "";
                            $initHyojiPlan['AIRCARGO'] = "";
                        }
                } else
                    if ($corce >= 2 && $corce <= 8) {
                        // 少量コース/1人部屋コース/2人部屋コース/3人部屋コース/4人部屋コース/5人部屋コース/6人部屋コース
                        $initHyojiPlan['STANDARD'] = "";
                        $initHyojiPlan['OMAKASE'] = "";
                        $initHyojiPlan['CHARTAR'] = "";
                    } else {
                        // コース指定なし
                        if ($plan >= 3 && $plan <= 5) {
                            // スタンダードプラン/おまかせプラン/チャータープラン
                            $initHyojiPlan['STANDARD'] = "";
                            $initHyojiPlan['OMAKASE'] = "";
                            $initHyojiPlan['CHARTAR'] = "";
                        } else {
                            // タイプ指定なし
                            // 該当データなし
                            $hitFlg = false;
                        }
                    }
            } else {
                // 該当データなし
                $hitFlg = false;
            }

        // 該当データなしの場合
        if (!$hitFlg) {
            $initHyojiPlan['CARGO'] = "";
            $initHyojiPlan['AIRCARGO'] = "";
            $initHyojiPlan['STANDARD'] = "";
            $initHyojiPlan['OMAKASE'] = "";
            $initHyojiPlan['CHARTAR'] = "";
        }

        return $initHyojiPlan;
    }

    /**
     * コース全表示ボタンの有無フラグを返却します。
     *
     * @param corce 初期表示コースリスト
     * @return コース全表示ボタン表示有無
     */
    public function _getInitAllCorceHyoji($initHyojiCorceList) {
        return array_search(false, $initHyojiCorceList);
    }

    /**
     * 基本料金を算出します。
     * 基本料金.基本料金 + 6300 + (閑散・繁忙期設定金額)
     *
     * @param basePrice 基本料金
     * @param kansanhanbou 閑散・繁忙期の差額配列
     * @return basePrice 基本料金
     */
    public function getBasePrice($basePrice, $kansanhanbou) {

        $basePrice += Sgmov_Service_AppCommon::WEBWARIBIKI;

        for ($i = 0; $i < count($kansanhanbou); $i++) {
            $basePrice += $kansanhanbou[$i]['price'];
        }

        return $basePrice;
    }

    /**
     * 概算お見積りの結果を取得します。
     *
     * @param Sgmov_Component_DB $db DB接続
     * @param mobeYtiYmdFrom 引越し予定日付From
     * @param mobeYtiYmdTo 引越し予定日日付To
     * @param corceId コースID
     * @param planId プランID
     * @param fromareaId 現住所地域
     * @param toareaId 引越し先地域
     * @return array ['cnt'] 検索結果の件数
     *               ['targetdate'] 対象日付
     *               ['basePrice'] 基本料金の文字列配列
     *               ['campId'] IDの文字列配列、
     *               ['tokkaKbn'] 特価区分の文字列配列
     *               ['title'] キャンペーン名称の文字列配列
     *               ['sagaku'] 差額の文字列配列
     *               ['courceNm'] コース名称の文字列配列
     *               ['planNm'] プラン名称の文字列配列
     *               ['fromAreaNm'] 出発地域名称の文字列配列
     *               ['toAreaNm'] 到着地域名称の文字列配列
     *
     */
    public function fetchMitumoriResult($db, $mobeYtiYmdFrom, $mobeYtiYmdTo, $corceId, $planId, $fromareaId, $toareaId) {
        Sgmov_Component_Log::debug("fetchMitumoriResult Start");

        $query = "
            select
                docamp.base_price,
                docamp.cource as courcesnm,
                docamp.plan as plansnm,
                docamp.from_area as fromareasnm,
                docamp.to_area as toareasnm,
                docamp.target_date,
                docamp.id,
                docamp.tokakbn,
                docamp.title,
                meisai.price_difference as sagaku,
                docamp.setumei,
                docamp.min_date,
                docamp.max_date
            from
                (
                select
                    base_price,
                    cource,
                    plan,
                    from_area,
                    to_area,
                    target_date,
                    id,
                    tokakbn,
                    title,
                    setumei,
                    min_date,
                    max_date
                from
                    (
                    select
                        base_prices.base_price,
                        cources.name as cource,
                        plans.name as plan,
                        from_areas.name as from_area,
                        to_areas.name as to_area
                    from
                        base_prices,
                        cources,
                        plans,
                        from_areas,
                        to_areas
                    where
                        base_prices.cource_id = $1 and
                        base_prices.plan_id = $2 and
                        base_prices.from_area_id = $3 and
                        base_prices.to_area_id = $4 and
                        base_prices.cource_id = cources.id and
                        base_prices.plan_id = plans.id and
                        base_prices.from_area_id = from_areas.id and
                        base_prices.to_area_id = to_areas.id
                    ) kihon
                left join
                    (
                    select
                        date.target_date,
                        price.id,
                        price.special_price_division as tokakbn,
                        price.title,
                        price.description as setumei,
                        price.min_date,
                        price.max_date
                    from
                        special_prices as price,
                        (
                        SELECT
                            date.special_price_id as id,
                            date.target_date
                        FROM
                            special_prices sp,
                            special_prices_dates date,
                            cources_plans_special_prices cp,
                            from_areas_special_prices frarea,
                            special_prices_to_areas toarea
                        WHERE
                            date.special_price_id = sp.id AND
                            date.special_price_id = cp.special_price_id AND
                            date.special_price_id = frarea.special_price_id AND
                            date.special_price_id = toarea.special_price_id AND
                            cp.cource_id = $5 AND
                            cp.plan_id = $6 AND
                            frarea.from_area_id = $7 AND
                            toarea.to_area_id = $8 AND
                            date.target_date BETWEEN $9 AND $10
                        ) date
                    where
                        price.id = date.id and
                        price.draft_flag = false
                    ) docamp
                on 1 = 1
                ) docamp
            left join (
                select
                    special_price_id,
                    cource_id,
                    plan_id,
                    from_area_id,
                    to_area_id,
                    price_difference
                from
                    special_price_details
                where
                cource_id = $11 and
                    plan_id = $12 and
                    from_area_id = $13 and
                    to_area_id = $14
                ) as meisai
             on docamp.id = meisai.special_price_id
            order by
                docamp.target_date";

        $targetdate = array();
        $basePrice = array();
        $campId = array();
        $tokkaKbn = array();
        $title = array();
        $sagaku = array();
        $courceNm = array();
        $planNm = array();
        $fromAreaNm = array();
        $toAreaNm = array();
        $campFrg = array();
        $content = array();
        $startYmd = array();
        $endYmd = array();

        $params = array($corceId, $planId, $fromareaId, $toareaId, $corceId, $planId, $fromareaId, $toareaId, $mobeYtiYmdFrom, $mobeYtiYmdTo, $corceId, $planId, $fromareaId, $toareaId);

        $result = $db->executeQuery($query, $params);

        if ($result->size() > 0) {
            for ($i = 0; $i < $result->size(); $i++) {
                $row = $result->get($i);
                $targetdate[] = $row['target_date'];
                $basePrice[] = $row['base_price'];
                $campId[] = $row['id'];
                $tokkaKbn[] = $row['tokakbn'];
                $title[] = $row['title'];
                $sagaku[] = $row['sagaku'];
                $courceNm[] = $row['courcesnm'];
                $planNm[] = $row['plansnm'];
                $fromAreaNm[] = $row['fromareasnm'];
                $toAreaNm[] = $row['toareasnm'];
                if ($row['tokakbn'] != Sgmov_View_CommonConst::TOKKA_KANSAN_HANBOUKI_RYOKINSETTEI) {
                    $campFrg[] = true;
                } else {
                    $campFrg[] = false;
                }
                $content[] = $row['setumei'];
                $startYmd[] = $row['min_date'];
                $endYmd[] = $row['max_date'];
            }
        } else {
            Sgmov_Component_Log::debug('該当する概算お見積りの結果は存在しません。');
            return NULL;
        }

        return array(
            'cnt'        => $result->size(),
            'targetdate' => $targetdate,
            'basePrice'  => $basePrice,
            'campId'     => $campId,
            'tokkaKbn'   => $tokkaKbn,
            'title'      => $title,
            'sagaku'     => $sagaku,
            'courceNm'   => $courceNm,
            'planNm'     => $planNm,
            'fromAreaNm' => $fromAreaNm,
            'toAreaNm'   => $toAreaNm,
            'campFrg'    => $campFrg,
            'contents'   => $content,
            'startYmds'  => $startYmd,
            'endYmds'    => $endYmd
        );
    }

    /**
     * POST値からチケットを取得します。
     * チケットが存在しない場合は空文字列を返します。
     * @return string チケット文字列
     */
    public function _getTicket() {
        if (!isset($_POST['ticket'])) {
            $ticket = '';
        } else {
            $ticket = $_POST['ticket'];
        }
        return $ticket;
    }

    /**
     * 概算見積り結果画面⇒訪問見積り画面へ渡すセッション情報の作成
     *
     * @param string $db DB接続情報
     * @param Sgmov_Form_Pre003In $inForm 入力情報
     * @return Sgmov_Form_PveSession 出力情報
     */
    public function _createMitsumoriInfo($db, $inForm) {
        Sgmov_Component_Log::debug("_createSessionInfo Start");

        // 閑散繁忙期キャンペーン情報
        $kansanhanbou = array();
        // 特価キャンペーン情報
        $campaing = array();
        // 割引額合計
        $waribiki = 0;
        // 【結果画面】引越し予定日
        $moveYmd = $inForm->move_date_year_cd_sel.$inForm->move_date_month_cd_sel.$inForm->move_date_day_cd_sel;
        // 【結果画面】コースコード
        $course_cd = $inForm->course_cd_sel;
        // 【結果画面】プランコード
        $plan_cd = $inForm->plan_cd_sel;
        // 【結果画面】エアコン取り付け取り外し
        $aircon_exist_flag = $inForm->aircon_exist_flag_sel;

        // 個人向けサービス ページから 選択されたメニュー
        $menu_personal = $inForm->menu_personal;

        // 【結果画面】出発地域
        $from_area_cd = $inForm->from_area_cd_sel;
        // 【結果画面】到着地域
        $to_area_cd = $inForm->to_area_cd_sel;

        // 基本料金
        $basePrice = null;
        // コースコード（名称）
        $courceNm = null;
        // プランコード（名称）
        $planNm = null;
        // 出発地域（名称）
        $fromAreaNm = null;
        // 到着地域（名称）
        $toAreaNm = null;

        if (!empty($moveYmd)) {
            // 見積り内容に基づく見積り結果の取得
            $MitumoriResult = $this->fetchMitumoriResult($db, $moveYmd, $moveYmd, $course_cd, $plan_cd, $from_area_cd, $to_area_cd);
        }

        if (!empty($MitumoriResult)) {
            // 見積り結果の取得要素数だけ以下を繰り返す
            for ($i = 0; $i < $MitumoriResult['cnt']; ++$i) {
                // 基本料金（繰り返し中、毎回同じ）
                $basePrice = $MitumoriResult['basePrice'][$i];
                $courceNm = $MitumoriResult['courceNm'][$i];
                $planNm = $MitumoriResult['planNm'][$i];
                $fromAreaNm = $MitumoriResult['fromAreaNm'][$i];
                $toAreaNm = $MitumoriResult['toAreaNm'][$i];
                // キャンペーン情報
                if ($MitumoriResult['tokkaKbn'][$i] == Sgmov_View_CommonConst::TOKKA_KANSAN_HANBOUKI_RYOKINSETTEI) {
                    // 閑散繁忙期キャンペーン情報
                    $kansanhanbou[] = array(
                        'title'    => $MitumoriResult['title'][$i],
                        'price'    => $MitumoriResult['sagaku'][$i],
                        'content'  => $MitumoriResult['contents'][$i],
                        'startYmd' => $MitumoriResult['startYmds'][$i],
                        'endYmd'   => $MitumoriResult['endYmds'][$i],
                    );
                } else {
                    // 割引額加算
                    $waribiki += $MitumoriResult['sagaku'][$i];
                    // 特価キャンペーン情報
                    $campaing[] = array(
                        'title'    => $MitumoriResult['title'][$i],
                        'price'    => $MitumoriResult['sagaku'][$i],
                        'content'  => $MitumoriResult['contents'][$i],
                        'startYmd' => $MitumoriResult['startYmds'][$i],
                        'endYmd'   => $MitumoriResult['endYmds'][$i],
                    );
                }
            }
        }

        // セッションに見積り情報とキャンペーン情報を保存
        $sessionForm = new Sgmov_Form_PveSession();

        // 概算見積もりフラグ
        $sessionForm->pre_exist_flag = 1;
        // コースコード
        $sessionForm->pre_course = $course_cd;
        // プランコード
        $sessionForm->pre_plan = $plan_cd;
        // コースコード（名称）
        $sessionForm->pre_course_name = $courceNm;
        // プランコード（名称）
        $sessionForm->pre_plan_name = $planNm;
        // エアコン取り付け取り外し
        $sessionForm->pre_aircon_exist = $aircon_exist_flag;

        // 個人向けサービス ページから選択されたメニュー
        $sessionForm->pre_menu_personal = $menu_personal;

        // 出発地域
        $sessionForm->pre_from_area = $from_area_cd;
        // 到着地域
        $sessionForm->pre_to_area = $to_area_cd;
        // 出発地域（名称）
        $sessionForm->pre_from_area_name = $fromAreaNm;
        // 到着地域（名称）
        $sessionForm->pre_to_area_name = $toAreaNm;
        // 引越し予定日付
        $sessionForm->pre_move_date = $moveYmd;
        // 基本料金
        $sessionForm->pre_base_price = $basePrice;
        // 表示用基本料金
        $sessionForm->pre_estimate_base_price = $this->getBasePrice($basePrice, $kansanhanbou);
        // 概算見積料金
        $sessionForm->pre_estimate_price = $sessionForm->pre_estimate_base_price + $waribiki - Sgmov_Service_AppCommon::WEBWARIBIKI;

        // 閑散繁忙期キャンペーン情報初期化
        $tmpKansanHanboCamNames = array();
        $tmpKansanHanboCamPrices = array();
        $tmpKansanHanboCamContents = array();
        $tmpKansanHanboCamStartYmd = array();
        $tmpKansanHanboCamEndYmd = array();

        // 特価キャンペーン情報の要素数だけ、以下を繰り返す
        for ($i = 0; $i < count($kansanhanbou); $i++) {
            $tmpKansanHanboCamNames[] = $kansanhanbou[$i]['title'];
            $tmpKansanHanboCamPrices[] = $kansanhanbou[$i]['price'];
            $tmpKansanHanboCamContents[] = $kansanhanbou[$i]['content'];
            $tmpKansanHanboCamStartYmd[] = $kansanhanbou[$i]['startYmd'];
            $tmpKansanHanboCamEndYmd[] = $kansanhanbou[$i]['endYmd'];
        }

        // 閑散繁忙期キャンペーン名称
        $sessionForm->pre_cam_kansanhanbo_names = $tmpKansanHanboCamNames;
        // 閑散繁忙期キャンペーン名称
        $sessionForm->pre_cam_kansanhanbo_prices = $tmpKansanHanboCamPrices;
        // 閑散繁忙期キャンペーン概要
        $sessionForm->pre_cam_kansanhanbo_contents = $tmpKansanHanboCamContents;
        // 閑散繁忙期キャンペーン開始日
        $sessionForm->pre_cam_kansanhanbo_starts = $tmpKansanHanboCamStartYmd;
        // 閑散繁忙期キャンペーン終了日
        $sessionForm->pre_cam_kansanhanbo_ends = $tmpKansanHanboCamEndYmd;

        // 特価キャンペーン情報初期化
        $tmpCamNames = array();
        $tmpCamPrices = array();
        $tmpCamContents = array();
        $tmpCamStartYmd = array();
        $tmpCamEndYmd = array();

        // 特価キャンペーン情報の要素数だけ、以下を繰り返す
        for ($i = 0; $i < count($campaing); $i++) {
            $tmpCamNames[] = $campaing[$i]['title'];
            $tmpCamPrices[] = $campaing[$i]['price'];
            $tmpCamContents[] = $campaing[$i]['content'];
            $tmpCamStartYmd[] = $campaing[$i]['startYmd'];
            $tmpCamEndYmd[] = $campaing[$i]['endYmd'];
        }

        // 特価キャンペーン名称
        $sessionForm->pre_cam_discount_names = $tmpCamNames;
        // 特価キャンペーン概要
        $sessionForm->pre_cam_discount_prices = $tmpCamPrices;
        // 特価キャンペーン概要
        $sessionForm->pre_cam_discount_contents = $tmpCamContents;
        // 特価キャンペーン開始日
        $sessionForm->pre_cam_discount_starts = $tmpCamStartYmd;
        // 特価キャンペーン終了日
        $sessionForm->pre_cam_discount_ends = $tmpCamEndYmd;
        // 他社連携キャンペーンID
        $sessionForm->oc_id    = $inForm->oc_id;
        // 他社連携キャンペーン名称
        $sessionForm->oc_name    = $inForm->oc_name;
        // 他社連携キャンペーン内容
        $sessionForm->oc_content = $inForm->oc_content;

        Sgmov_Component_Log::debug("_createSessionInfo End");
        return $sessionForm;

    }


    /**
     * GETパラメータを取得します。
     *
     * @param none
     * @return type_cd/course_cd/plan_cd
     */
    public function _parseGetParameter() {

        if (!isset($_GET['param'])) {
            return array(0, 0, 0);
        } else {

            $params = explode('/', $_GET['param']);
            if (count($params) > 4) {
                // 不正遷移
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_GET[\'param\']が不正です。');
            }

            // 分解できた数だけ返却用パラメータに格納する
            for ($cnt = 0; $cnt < count($params); $cnt++) {
                $retParam[] = $params[$cnt];
            }

            return $retParam;
        }
    }


    /**
     * セッションパラメータを取得します。
     *
     * @param $session セッション
     * @param $key
     * @return string
     */
    protected static function getSessionValue($session = null, $key = null) {

        if (empty($session) || empty($key) || !isset($session->$key)) {
            return;
        }
        return $session->$key;
    }
}