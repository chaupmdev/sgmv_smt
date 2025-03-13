<?php
/**
 * @package    ClassDefFile
 * @author     K.Hamada(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useAllComponents();
Sgmov_Lib::useServices(array('Prefecture','Center'));
Sgmov_Lib::useView('Public');
/**#@-*/

 /**
 * 採用エントリーフォームの共通処理を管理する抽象クラスです。
 * @package    View
 * @subpackage PEM
 * @author     K.Hamada(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Pem_Common extends Sgmov_View_Public
{
    /**
     * 機能ID
     */
    const FEATURE_ID = 'PEM';

    /**
     * PEM001の画面ID
     */
    const GAMEN_ID_PEM001 = 'PEM001';

    /**
     * 年齢（上限）
     */
    const OLD_MAX = 41;

    /**
     * 年齢（下限）
     */
    const OLD_MIN = 19;

    /**
     * 営業所
     */
    const STR_EIGYOSYO = '営業所';

    /**
     * PEM002の画面ID
     */
    const GAMEN_ID_PEM002 = 'PEM002';

    /**
     * 採用コードの区分値
     * @var array
     */
    public $employ_type_lbls = array(''=>'',
                                         1=>'新卒',
                                         2=>'中途',
                                         3=>'アルバイト');

    /**
     * 職種コードの区分値
     * @var array
     */
    public $job_type_lbls = array(''=>'',
                                         1=>'営業職',
                                         2=>'運転職',
                                         3=>'カスタマーサービス職',
                                         4=>'配送職',
                                         5=>'本社事務職');

    /**
     * 年齢の区分値
     * @var array
     */
    public $age_lbls = array(''=>'',
                                     19=>'19歳以下',
                                     20=>'20歳',
                                     21=>'21歳',
                                     22=>'22歳',
                                     23=>'23歳',
                                     24=>'24歳',
                                     25=>'25歳',
                                     26=>'26歳',
                                     27=>'27歳',
                                     28=>'28歳',
                                     29=>'29歳',
                                     30=>'30歳',
                                     31=>'31歳',
                                     32=>'32歳',
                                     33=>'33歳',
                                     34=>'34歳',
                                     35=>'35歳',
                                     36=>'36歳',
                                     37=>'37歳',
                                     38=>'38歳',
                                     39=>'39歳',
                                     40=>'40歳',
                                     41=>'41歳以上');

}
?>