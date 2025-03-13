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
Sgmov_Lib::useServices(array('Prefecture'));
Sgmov_Lib::useView('Public');
/**#@-*/

 /**
 * 法人引越輸送フォームの共通処理を管理する抽象クラスです。
 * @package    View
 * @subpackage PCS
 * @author     K.Hamada(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Pcs_Common extends Sgmov_View_Public {

    /**
     * 機能ID
     */
    const FEATURE_ID = 'PCS';

    /**
     * PCS001の画面ID
     */
    const GAMEN_ID_PCS001 = 'PCS001';

    /**
     * PCS002の画面ID
     */
    const GAMEN_ID_PCS002 = 'PCS002';

    /**
     * お問い合わせ種類コードの区分値
     * @var array
     */
    public $inquiry_type_lbls = array(
        '' => '',
        1  => 'お問い合わせ',
        2  => 'お申し込み'
    );

    /**
     * お問い合わせカテゴリーコードの区分値
     * @var array
     */
    public $inquiry_category_lbls = array(
        '' => '',
        //1  => '家具設置について',
        //2  => '家電設置について',
        1  => '家具・家電の設置について',
        3  => 'その他設置について',
        4  => 'イベント輸送について',
        5  => 'チャーター輸送について',
        6  => 'その他',
        7  => '設置輸送について',
        8  => '特殊輸送について',
        9  => '機密文書について',
        10 => '貸切輸送について',
        11 => 'テクニカル輸送について',
        12 => '美術品輸送について',
        13 => '旅客手荷物受付について',
        14 => '延長保証支援サービスについて',
    );

    /**
     * 電話種類コードの区分値
     * @var array
     */
    public $tel_type_lbls = array(
        '' => '',
        1  => '携帯',
        2  => '勤務先',
        3  => 'その他'
    );

    /**
     * 連絡方法コードの区分値
     * @var array
     */
    public $contact_method_lbls = array(
        '' => '',
        1  => '電話',
        2  => 'FAX',
        3  => 'メール'
    );

    /**
     * 電話連絡可能コードの区分値
     * @var array
     */
    public $contact_available_lbls = array(
        '' => '',
        1  => '時間指定',
        2  => '終日OK'
    );

    /**
     * 電話連絡可能開始時刻コードの区分値
     * @var array
     */
    public $contact_start_lbls = array(
        '' => '',
        0  => '0時',
        1  => '1時',
        2  => '2時',
        3  => '3時',
        4  => '4時',
        5  => '5時',
        6  => '6時',
        7  => '7時',
        8  => '8時',
        9  => '9時',
        10 => '10時',
        11 => '11時',
        12 => '12時',
        13 => '13時',
        14 => '14時',
        15 => '15時',
        16 => '16時',
        17 => '17時',
        18 => '18時',
        19 => '19時',
        20 => '20時',
        21 => '21時',
        22 => '22時',
        23 => '23時'
    );

    /**
     * 電話連絡可能終了時刻コードの区分値
     * @var array
     */
    public $contact_end_lbls = array(
        '' => '',
        0  => '0時',
        1  => '1時',
        2  => '2時',
        3  => '3時',
        4  => '4時',
        5  => '5時',
        6  => '6時',
        7  => '7時',
        8  => '8時',
        9  => '9時',
        10 => '10時',
        11 => '11時',
        12 => '12時',
        13 => '13時',
        14 => '14時',
        15 => '15時',
        16 => '16時',
        17 => '17時',
        18 => '18時',
        19 => '19時',
        20 => '20時',
        21 => '21時',
        22 => '22時',
        23 => '23時'
    );

}