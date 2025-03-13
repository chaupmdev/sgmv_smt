<?php
/**
 * @package    ClassDefFile
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useForms(array('Error'));
/**#@-*/

 /**
 * 料金マスタメンテナンスのセッションフォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_AcfSession
{
    /**
     * カレントコースプランコード
     * @var string
     */
    public $cur_course_plan_cd;

    /**
     * カレントコースプラン
     * @var string
     */
    public $cur_course_plan;

    /**
     * カレント出発エリアコード
     * @var string
     */
    public $cur_from_area_cd;

    /**
     * カレント出発エリア
     * @var string
     */
    public $cur_from_area;

    /**
     * 基本料金コードリスト
     * @var string
     */
    public $base_price_cds;

    /**
     * 更新タイムスタンプリスト
     * @var string
     */
    public $modifieds;

    /**
     * 到着エリアコードリスト
     * @var string
     */
    public $to_area_cds;

    /**
     * 到着エリアラベルリスト
     * @var string
     */
    public $to_area_lbls;

    /**
     * 元基本料金リスト
     * @var string
     */
    public $orig_base_prices;

    /**
     * 元上限料金リスト
     * @var string
     */
    public $orig_max_prices;

    /**
     * 元下限料金リスト
     * @var string
     */
    public $orig_min_prices;

    /**
     * 基本料金リスト
     * @var string
     */
    public $base_prices;

    /**
     * 上限料金リスト
     * @var string
     */
    public $max_prices;

    /**
     * 下限料金リスト
     * @var string
     */
    public $min_prices;

    /**
     * 状態
     * @var string
     */
    public $status;

    /**
     * エラーフォーム
     * @var Sgmov_Form_Error
     */
    public $error;

}
?>
