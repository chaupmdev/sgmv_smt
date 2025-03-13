<?php
/**
 * @package    ClassDefFile
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useAllComponents();
Sgmov_Lib::useServices(array('CenterArea','CoursePlan','Prefecture','VisitEstimate','Yubin','PreCampaign', 'AppCommon', 'Apartment'));
Sgmov_Lib::useView('Public');
/**#@-*/

 /**
 * 法人引越輸送フォームの共通処理を管理する抽象クラスです。
 * @package    View
 * @subpackage PVE
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Pve_Common extends Sgmov_View_Public
{
    /**
     * 機能ID
     */
    const FEATURE_ID = 'PVE';

    /**
     * 機能ID（PRE01）
     */
    const SCRID_PRE = 'PRE';

    /**
     * 機能ID（PVE001）
     */
    const SCRID_TOPVE = 'TOPVE';

    /**
     * PVE001の画面ID
     */
    const GAMEN_ID_PVE001 = 'PVE001';

    /**
     * PVE002の画面ID
     */
    const GAMEN_ID_PVE002 = 'PVE002';

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
     * エレベーター選択値
     * @var array
     */
    public $elevator_lbls = array(
        ''  => '',
        '0' => 'なし',
        '1' => 'あり'
    );

    /**
     * 住居前道幅選択値
     * @var array
     */
    public $road_lbls = array(
        ''  => '',
        '1' => '車両通行不可',
        '2' => '1台通行可',
        '3' => '2台すれ違い可'
    );

    /**
     * 電話種類コード選択値
     * @var array
     */
    public $tel_type_lbls = array(
        ''  => '',
        '1' => '自宅（携帯）',
        '2' => '勤務先',
        '3' => 'その他'
    );

    /**
     * 電話連絡可能コード値
     * @var array
     */
    public $contact_available_lbls = array(
        ''  => '',
        '1' => '時間指定',
        '2' => '終日OK'
    );

    /**
     * 電話連絡開始時刻コード値
     * @var array
     */
    public $contact_start_lbls = array(
        ''   => '',
//        '00' => '0時',
//        '01' => '1時',
//        '02' => '2時',
//        '03' => '3時',
//        '04' => '4時',
//        '05' => '5時',
//        '06' => '6時',
//        '07' => '7時',
//        '08' => '8時',
        '09' => '9時',
        '10' => '10時',
        '11' => '11時',
        '12' => '12時',
        '13' => '13時',
        '14' => '14時',
        '15' => '15時',
        '16' => '16時',
        '17' => '17時',
//        '18' => '18時',
//        '19' => '19時',
//        '20' => '20時',
//        '21' => '21時',
//        '22' => '22時',
//        '23' => '23時'
    );

    /**
     * 電話連絡終了時刻コード値
     * @var array
     */
    public $contact_end_lbls = array(
        ''   => '',
//        '00' => '0時',
//        '01' => '1時',
//        '02' => '2時',
//        '03' => '3時',
//        '04' => '4時',
//        '05' => '5時',
//        '06' => '6時',
//        '07' => '7時',
//        '08' => '8時',
        '09' => '9時',
        '10' => '10時',
        '11' => '11時',
        '12' => '12時',
        '13' => '13時',
        '14' => '14時',
        '15' => '15時',
        '16' => '16時',
        '17' => '17時',
//        '18' => '18時',
//        '19' => '19時',
//        '20' => '20時',
//        '21' => '21時',
//        '22' => '22時',
//        '23' => '23時'
        );

    /**
     * 連絡方法コード選択値
     * @var array
     */
    public $contact_method_lbls = array(
        ''  => '',
        '1' => '電話',
        '2' => 'メール'
    );

    /**
     * エアコンの取り付け・取り外しの区分名称を返します。
     * 基本料金.基本料金 + 6300 + (閑散・繁忙期設定金額)
     *
     * @param aircon_exist_flag エアコンの取り付け・取り外し区分
     * @return str あり、なし
     */
    public static function _getAirconKbnNm($aircon_exist_flag) {

        if ($aircon_exist_flag == 1) {
            return "あり";
        }
        return "なし";

    }

    /**
     * 画面に表示するキャンペーン情報リストhtmlを生成し、返却する。
     *
     * @param $base_price 基本料金
     * @param $campaigninfo キャンペーン情報
     * @return $html　キャンペーン情報リストhtml
     */
    public static function _createCampInfoHtml($camp_titles, $campaign_contents, $campaign_starts, $campaign_ends) {

        $html = "";

        if ($camp_titles[0] != NULL) {

            $html .= "<tr>\n";
            $html .= "<th scope=\"row\">キャンペーン情報</th>\n";
            $html .= "<td>\n";

            for ($campCnt = 0; $campCnt < count($camp_titles); $campCnt++) {
                $html .= $camp_titles[$campCnt]."<br>\n";
                $html .= $campaign_contents[$campCnt]."<br>\n";
                $html .= "＜期間：".date('Y年m月d日', strtotime($campaign_starts[$campCnt]))." ～ ".date('Y年m月d日', strtotime($campaign_ends[$campCnt]))."＞<br>\n";
            }

            $html .= "</td>\n";
            $html .= "</tr>\n";

        }

        return $html;
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