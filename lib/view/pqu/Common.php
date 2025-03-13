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
Sgmov_Lib::useView('Public');
/**#@-*/

 /**
 * アンケートフォームの共通処理を管理する抽象クラスです。
 * @package    View
 * @subpackage PQU
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Pqu_Common extends Sgmov_View_Public
{
    /**
     * 機能ID
     */
    const FEATURE_ID = 'PQU';

    /**
     * PQU001の画面ID
     */
    const GAMEN_ID_PQU001 = 'PQU001';

    /**
     * PQU002の画面ID
     */
    const GAMEN_ID_PQU002 = 'PQU002';

    /**
     * 質問1の区分値
     * @var array
     */
    public $question1_lbls = array(''=>'',
                                     1=>'オフィス移転',
                                     2=>'設置輸送',
                                     3=>'個人引越');
    /**
     * 質問2_1の区分値
     * @var array
     */
    public $question2_1_lbls = array(0=>'',
                                         1=>'料金が安かった');

    /**
     * 質問2_2の区分値
     * @var array
     */
    public $question2_2_lbls = array(0=>'',
                                         1=>'サービスがよかった');

    /**
     * 質問2_3の区分値
     * @var array
     */
    public $question2_3_lbls = array(0=>'',
                                         1=>'営業マンがよかった');

    /**
     * 質問2_4の区分値
     * @var array
     */
    public $question2_4_lbls = array(0=>'',
                                         1=>'会社の指定だった');

    /**
     * 質問2_5の区分値
     * @var array
     */
    public $question2_5_lbls = array(0=>'',
                                         1=>'その他');

    /**
     * 質問3の区分値
     * @var array
     */
    public $question3_lbls = array(''=>'',
                                     0=>'利用していない',
                                     1=>'よくわかった',
                                     2=>'普通',
                                     3=>'よくわからなかった');

    /**
     * 質問4の区分値
     * @var array
     */
    public $question4_lbls = array(''=>'',
                                     1=>'大変よかった',
                                     2=>'良かった',
                                     3=>'普通',
                                     4=>'悪かった',
                                     5=>'大変悪かった');

    /**
     * 質問5の区分値
     * @var array
     */
    public $question5_lbls = array(''=>'',
                                     1=>'大変よかった',
                                     2=>'良かった',
                                     3=>'普通',
                                     4=>'悪かった',
                                     5=>'大変悪かった');

    /**
     * 質問6の区分値
     * @var array
     */
    public $question6_lbls = array(''=>'',
                                     1=>'大変よかった',
                                     2=>'良かった',
                                     3=>'普通',
                                     4=>'悪かった',
                                     5=>'大変悪かった');

    /**
     * 質問7の区分値
     * @var array
     */
    public $question7_lbls = array(''=>'',
                                     1=>'大変よかった',
                                     2=>'良かった',
                                     3=>'普通',
                                     4=>'悪かった',
                                     5=>'大変悪かった');

    /**
     * 質問8の区分値
     * @var array
     */
    public $question8_lbls = array(''=>'',
                                     1=>'大変よかった',
                                     2=>'良かった',
                                     3=>'普通',
                                     4=>'悪かった',
                                     5=>'大変悪かった');

    /**
     * 質問9の区分値
     * @var array
     */
    public $question9_lbls = array(''=>'',
                                     1=>'利用したい',
                                     2=>'わからない',
                                     3=>'利用しない');
}
?>
