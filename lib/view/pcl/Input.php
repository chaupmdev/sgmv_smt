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
Sgmov_Lib::useView('pcl/Common');
Sgmov_Lib::useForms(array('Error', 'Pcl001Out'));
/**#@-*/

 /**
 * キャンペーン一覧を表示します。
 * @package    View
 * @subpackage PCL
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pcl_Input extends Sgmov_View_Pcl_Common
{
    /**
     * 地方コードサービス
     * @var Sgmov_Service_Prefecture
     */
    public $_ProvincesService;

    /**
     * 地方・エリアサービス
     * @var Sgmov_Service_Prefecture
     */
    public $_ProvincesAreaService;

    /**
     * 特価サービス
     * @var Sgmov_Service_SpecialPrice
     */
    public $_SpecialPriceService;

    /**
     * プランサービス
     * @var Sgmov_Service_SpecialPrice
     */
    public $_CoursePlanService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        $this->_ProvincesService = new Sgmov_Service_Provinces();
        $this->_ProvincesAreaService = new Sgmov_Service_ProvincesArea();
        $this->_SpecialPriceService = new Sgmov_Service_SpecialPrice();
        $this->_CoursePlanService = new Sgmov_Service_CoursePlan();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * GET値の取得
     *
     * 出力情報を設定
     *
     * テンプレート用の値をセット
     *
     * ['outForm']:出力フォーム
     */
    public function executeInner()
    {
        $db = Sgmov_Component_DB::getPublic();

        //地方一覧の抽出
        $provinces = $this->_ProvincesService->fetchProvinces($db);

        //GETパラメーターのチェック
        $param = $this->_GetParameter($provinces);

        // 出力情報を設定
        $outForm = $this->_createOutFormByParams($param, $db, $provinces);

        return array('outForm'=>$outForm);
    }

    /**
     * GETパラメータから地方コードを取得します。
     *
     * 未指定の場合は東京の5を返します。
     */
    public function _GetParameter($provinces)
    {
        if ($_GET['param'] != '') {
            $params = explode('/', $_GET['param'], 2);
            $region_cd = $params[0];
        } else {
            //東京表示
            $region_cd = '4';
        }
            $v = Sgmov_Component_Validator::createSingleValueValidator($region_cd);
            $v->isIn($provinces['ids']);
            if (!$v->isValid()) {
                // 不正遷移
                Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_VIEW_INVALID_PAGE_ACCESS, '$_GET[\'param\']が不正です。');
            }
        return $region_cd;
    }

    /**
     * GETのパラメーターを元に出力フォームを生成します。
     * @param 地方コード
     * @return Sgmov_Form_Pcl001Out 出力フォーム
     */
    public function _createOutFormByParams($region_cd, $db, $provinces)
    {
        $outForm = new Sgmov_Form_Pcl001Out();
        $outForm->raw_from_region_cds = $provinces['ids'];
        $outForm->raw_from_region_lbls = $provinces['names'];

        //表示対象出発エリアの抽出
        $Areas = $this->_ProvincesAreaService->fetchFromAreaListByProvinces($db, $region_cd);
        $outForm->raw_from_area_cds = $Areas['from_area_ids'];
        $outForm->raw_from_area_lbls = $Areas['from_area_names'];

        //表示用コース名とプラン名を取得
        $CourcesPlans = $this->_CoursePlanService->fetchCoursePlans($db);
        $Courcesids = $CourcesPlans['course_ids'];
        $Courceslbls = $CourcesPlans['course_names'];
        $Plansids = $CourcesPlans['plan_ids'];
        $Planslbls = $CourcesPlans['plan_names'];

        //地方と到着エリアの一覧抽出
        $ProvincesToAreasList = $this->_ProvincesAreaService->fetchProvincesToAreaList($db);

        //キャンペーン情報を取得
        $campaign_cds = array();
        $campaign_names = array();
        $campaign_contents = array();
        $campaign_starts = array();
        $campaign_ends = array();
        $campaign_cources_cds = array();
        $campaign_cources_lbls = array();
        $campaign_plans_cds = array();
        $campaign_plans_lbls = array();
        //地方分まわす
        for ($i = 1; $i < count($Areas['from_area_ids']); $i++) {
            $Special = $this->_SpecialPriceService->fetchSpecialPricesByFromAreaId($db, $Areas['from_area_ids'][$i]);
            //キャンペーン設定があれば実施
            if (!is_null($Special)) {
                $ids = $this->_GetUnique($Special['ids']);
                //重複を除いてセットしなおしたキャンペーンID（$ids）と同じように、他項目もセットしていく。
                foreach($ids as $key => $value){
                $campaign_cds[$Areas['from_area_ids'][$i]][$key] = $Special['ids'][array_search($value,$Special['ids'])];
                $campaign_names[$Areas['from_area_ids'][$i]][$key] = $Special['title'][array_search($value,$Special['ids'])];
                $campaign_contents[$Areas['from_area_ids'][$i]][$key] = $Special['description'][array_search($value,$Special['ids'])];
                $campaign_starts[$Areas['from_area_ids'][$i]][$key] = $Special['min_date'][array_search($value,$Special['ids'])];
                $campaign_ends[$Areas['from_area_ids'][$i]][$key] = $Special['max_date'][array_search($value,$Special['ids'])];
                }
                $cources_cds = $Special['cource_ids'];
                $cources_lbls = $Special['cource_names'];
                $plans_lbls = $Special['plan_names'];
                $array_ids = array();
                $array_names = array();

                //キャンペーンごとに
                $count_cam = count($ids);
                for ($r = 0; $r < $count_cam; $r++) {
                    //コースを配列化
                    $id_cds = array_keys($Special['ids'], $ids[$r]);
                    $count_cou = count($id_cds);
                    $temp_ci = array();
                    $temp_cn = array();
                    $temp_pn = array();
                    for ($t = 0; $t < $count_cou; $t++) {
                        $temp_ci[] = $Special['cource_ids'][$id_cds[$t]];
                        $temp_cn[] = $Special['cource_names'][$id_cds[$t]];
                        $temp_pn[$Special['cource_ids'][$id_cds[$t]]][] = $Special['plan_names'][$id_cds[$t]];
                    }
                    $array_cource_ids[$ids[$r]] = $this->_GetUnique($temp_ci);
                    $array_cource_names[$ids[$r]] = $this->_GetUnique($temp_cn);
                    $array_plan_names[$ids[$r]] = $temp_pn;

                    //到着IDを取得
                    $toarea = $this->_SpecialPriceService->fetchSpecialPricesToAreasById($db, $ids[$r]);
                    $toarea_ids = array();
                    $toarea_names = array();
                    $toareaprovince_ids = array();
                    foreach ($toarea as $key=>$val) {
                        $region_id = $ProvincesToAreasList['ids'][array_search($val, $ProvincesToAreasList['to_area_ids'])];
                        $campaign_region_cds[$Areas['from_area_ids'][$i]][] = $region_id;
                        $campaign_region_lbls[$Areas['from_area_ids'][$i]][] = $provinces['names'][array_search($region_id,$provinces['ids'])];
                        $campaign_to_area_lbls[$Areas['from_area_ids'][$i]][$ids[$r]][$val] = $ProvincesToAreasList['to_area_names'][array_search($val,$ProvincesToAreasList['to_area_ids'])];
                    }
                }
                $campaign_cources_cds[$Areas['from_area_ids'][$i]] = $array_cource_ids;
                $campaign_cources_lbls[$Areas['from_area_ids'][$i]] = $array_cource_names;
                $campaign_plan_lbls[$Areas['from_area_ids'][$i]] = $array_plan_names;
            } else {
                $campaign_cources_cds[$Areas['from_area_ids'][$i]] = null;
                $campaign_cources_lbls[$Areas['from_area_ids'][$i]] = null;
                $campaign_plan_lbls[$Areas['from_area_ids'][$i]] = null;
                $campaign_region_cds[$Areas['from_area_ids'][$i]][] = null;
                $campaign_region_lbls[$Areas['from_area_ids'][$i]][] = null;
                $campaign_to_area_lbls[$Areas['from_area_ids'][$i]] = null;

            }
        }

        $outForm->raw_from_region = $region_cd;
        $outForm->raw_campaign_cds = $campaign_cds;
        $outForm->raw_campaign_names = $campaign_names;
        $outForm->raw_campaign_contents = $campaign_contents;
        $outForm->raw_campaign_starts = $campaign_starts;
        $outForm->raw_campaign_ends = $campaign_ends;
        $outForm->raw_campaign_course_cds = $campaign_cources_cds;
        $outForm->raw_campaign_course_lbls = $campaign_cources_lbls;
        $outForm->raw_campaign_plan_lbls = $campaign_plan_lbls;
        $outForm->raw_campaign_region_cds = $campaign_region_cds;
        $outForm->raw_campaign_region_lbls = $campaign_region_lbls;
        $outForm->raw_campaign_to_area_lbls = $campaign_to_area_lbls;

        return $outForm;
    }

    /**
     * 配列から重複しない要素を取得します。
     *
     * 値は添え字のない配列で返します。
     */
    public function _GetUnique($array)
    {
        $temp = array_unique($array);
        $unique_array = array();
        $arraycount = count($temp);

        for ($t = 0; $t < $arraycount; $t++) {
            $unique_array[] = array_shift($temp);
        }

        return $unique_array;
    }

}
?>
