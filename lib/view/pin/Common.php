<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useAllComponents();
Sgmov_Lib::useServices(array('Prefecture'));
Sgmov_Lib::useView('Public');
/**#@-*/

 /**
 * お問い合わせフォームの共通処理を管理する抽象クラスです。
 * @package    View
 * @subpackage PIN
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Pin_Common extends Sgmov_View_Public {

    /**
     * 機能ID
     */
    const FEATURE_ID = 'PIN';

    /**
     * PIN001の画面ID
     */
    const GAMEN_ID_PIN001 = 'PIN001';

    /**
     * PIN002の画面ID
     */
    const GAMEN_ID_PIN002 = 'PIN002';

    /**
     * お問い合わせ種類コードの区分値
     * @var array
     */
    public $inquiry_type_lbls = array(
        '' => '',
        1  => 'サービスについて',
        2  => '品質について',
        3  => '採用について',
        4  => '個人情報について',
        5  => 'その他',
        6  => '単品設置輸送',
        7  => '設置輸送',
        8  => 'レディースムービング',
        9  => 'お引越し',
        10 => '海外引越し',
        11 => '旅客手荷物受付サービス',
        12 => 'マンションご入居',
        13 => 'カーゴプラン',
        14 => 'オプション',
        15 => '生活応援プラン',
        16 => 'チャータープラン',
        17 => 'スタンダードプラン',
        18 => 'まるごとおまかせプラン',
        19 => ' ＳＧ－ＷＯＮＤＥＲ（イベント受付システム）',
        20 => 'カヌー輸送（江戸川、スラロームセンター向け）',
    );

    /**
     * ＳＧムービングからの回答コードの区分値
     * @var array
     */
    public $need_reply_lbls = array(
        '' => '',
        0  => '不要',
        1  => '必要'
    );

}