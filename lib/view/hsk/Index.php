<?php

/**
 * @package    ClassDefFile
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('hsk/Common');
/**#@-*/

/**
 * 品質選手権アンケート入力画面を表示します。
 * @package    View
 * @subpackage HSK
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Hsk_Index extends Sgmov_View_Hsk_Common {
    
    /**
     * コンストラクタでサービスを初期化します。
     */
//    public function __construct() {
//        
//        parent::__construct();
//    }
    
    /**
     * 
     */
    public function executeInner() {
        
//var_dump($_GET);
        
        
        
        $id = @$_GET['param'];
        $this->checkId($id);
        
        $outInfo = array();
        
        // デモンストレーション投票
        $voteList = array(
            array(
                'teamName' => 'ゼッケン１',
                'companyName' => 'SGムービング',
                'officeName' => '西関東営業所',
                'pearsonNameList' => array(
                    '阿部則明',
                    '森明彦',
                ),
            ),
            array(
                'teamName' => 'ゼッケン２',
                'companyName' => '（有）Tトレジャー',
                'officeName' => '',
                'pearsonNameList' => array(
                    '村上勇治',
                    '伊藤光',
                ),
            ),
            array(
                'teamName' => 'ゼッケン３',
                'companyName' => 'SGムービング',
                'officeName' => '東京営業所',
                'pearsonNameList' => array(
                    '髙橋清仁',
                    '藤田与志弘',
                ),
            ),
            array(
                'teamName' => 'ゼッケン４',
                'companyName' => '（有）萩野谷商会',
                'officeName' => '',
                'pearsonNameList' => array(
                    '佐川滉貴',
                    '吉田あみ',
                ),
            ),
            array(
                'teamName' => 'ゼッケン５',
                'companyName' => 'エースカーゴ（株）',
                'officeName' => '',
                'pearsonNameList' => array(
                    '伊藤徹',
                    '大石祐樹',
                ),
            ),
            array(
                'teamName' => 'ゼッケン６',
                'companyName' => '（株）エム・エス・カンパニー',
                'officeName' => '',
                'pearsonNameList' => array(
                    '小泉怜',
                    '佐々木雅人',
                ),
            ),
            array(
                'teamName' => 'ゼッケン７',
                'companyName' => 'SGムービング',
                'officeName' => '福岡営業所',
                'pearsonNameList' => array(
                    '長谷部将',
                    '村上慎乃介',
                ),
            ),
            array(
                'teamName' => 'ゼッケン８',
                'companyName' => '堀部運送(株)',
                'officeName' => '',
                'pearsonNameList' => array(
                    '段村辰彦',
                    '橋之口航平',
                ),
            ),
            array(
                'teamName' => 'ゼッケン９',
                'companyName' => 'SGムービング',
                'officeName' => '札幌営業所',
                'pearsonNameList' => array(
                    '田口竜太郎',
                    '小平竜太',
                ),
            ),
            array(
                'teamName' => 'ゼッケン１０',
                'companyName' => '（株）ブレックス',
                'officeName' => '',
                'pearsonNameList' => array(
                    '髙木悠帆',
                    '北村百合子',
                ),
            ),
            array(
                'teamName' => 'ゼッケン１１',
                'companyName' => 'SGムービング',
                'officeName' => '名古屋営業所',
                'pearsonNameList' => array(
                    '加藤怜司',
                    'デバロスカルロス',
                ),
            ),
        );
        
        // 業種
        $gyoshuList = array(
            "1" => "建設・土木・工業",
            "2" => "運輸業・郵便業",
            "3" => "教員・学習支援業",
            "4" => "製造業",
            "5" => "卸売業・小売業",
            "6" => "医療・福祉",
            "7" => "電気・ガス・熱供給・水道業",
            "8" => "金融業・保険業",
            "99" => "その他",
            "9" => "通信販売業",
            "10" => "不動産業・物品賃貸業",
            "11" => "情報サービス業",
            "12" => "生活関連サービス業・娯楽業",
        );
        
        // 年齢
        $nenreiList = array(
            "1" => "１０代",
            "2" => "２０代",
            "3" => "３０代",
            "4" => "４０代",
            "5" => "５０代",
            "6" => "６０代",
            "7" => "その他",
        );
        
        // 品質選手権で良かったもの
        $yoiList = array(
            "1" => "品質選手権競技",
            "2" => "事業事例実演デモ",
            "3" => "システム関連デモ",
            "4" => "浴室乾燥暖房機設置デモ",
            "5" => "荷崩れ検知システムデモ",
            "6" => "多機能ＧＰＳデモ",
            "7" => "佐川印刷株式会社デモ",
            "8" => "養生用資材および機器展示",
            "9" => "美術品輸送展示",
            "10" => "オフィスクリエイティブ展示",
            "11" => "パネル（映像）展示",
            "99" => "その他",
        );
        
        $outInfo['voteList'] = $voteList;
        $outInfo['gyoshuList'] = $gyoshuList;
        $outInfo['nenreiList'] = $nenreiList;
        $outInfo['yoiList'] = $yoiList;
        
        return array(
            'outInfo'   => $outInfo,
        );
    }
    
}