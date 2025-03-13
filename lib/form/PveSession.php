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
Sgmov_Lib::useForms(array('Pre002In', 'Pve001In', 'Error'));
/**#@-*/

 /**
 * 訪問見積もり申し込みフォームのセッションフォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_PveSession
{
    /**
     * 概算見積存在フラグ
     * @var string
     */
    public $pre_exist_flag;

    /**
     * 概算見積入力フォーム
     * @var Sgmov_Form_Pre002In
     */
    public $pre_in;

    /**
     * 概算見積コース
     * @var string
     */
    public $pre_course;

    /**
     * 概算見積プラン
     * @var string
     */
    public $pre_plan;

    /**
     * 概算見積エアコン有無
     * @var string
     */
    public $pre_aircon_exist;


    /**
     * 個人向けサービス ページから選択されたメニュー
     * @var string
     */
    public $pre_menu_personal;


    /**
     * 概算見積出発エリア
     * @var string
     */
    public $pre_from_area;

    /**
     * 概算見積到着エリア
     * @var string
     */
    public $pre_to_area;

    /**
     * 概算見積引越予定日
     * @var string
     */
    public $pre_move_date;

    /**
     * 概算見積金額
     * @var string
     */
    public $pre_estimate_price;

    /**
     * 概算見積基本料金
     * @var string
     */
    public $pre_estimate_base_price;

    /**
     * 概算見積適用割引キャンペーン名リスト
     * @var array
     */
    public $pre_cam_discount_names;

    /**
     * 概算見積適用割引キャンペーン内容リスト
     * @var array
     */
    public $pre_cam_discount_contents;

    /**
     * 概算見積適用割引キャンペーン開始日リスト
     * @var array
     */
    public $pre_cam_discount_starts;

    /**
     * 概算見積適用割引キャンペーン終了日リスト
     * @var array
     */
    public $pre_cam_discount_ends;

    /**
     * 概算見積適用割引キャンペーン金額リスト
     * @var array
     */
    public $pre_cam_discount_prices;

    /**
     * 概算見積適用閑散繁忙期キャンペーン名リスト
     * @var array
     */
    public $pre_cam_kansanhanbo_names;

    /**
     * 概算見積適用閑散繁忙期キャンペーン内容リスト
     * @var array
     */
    public $pre_cam_kansanhanbo_contents;

    /**
     * 概算見積適用閑散繁忙期キャンペーン開始日リスト
     * @var array
     */
    public $pre_cam_kansanhanbo_starts;

    /**
     * 概算見積適用閑散繁忙期キャンペーン終了日リスト
     * @var array
     */
    public $pre_cam_kansanhanbo_ends;

    /**
     * 概算見積適用閑散繁忙期キャンペーン金額リスト
     * @var array
     */
    public $pre_cam_kansanhanbo_prices;

    /**
     * 概算見積適用価格なしキャンペーン名リスト
     * @var array
     */
    public $pre_cam_nodisc_names;

    /**
     * 概算見積適用価格なしキャンペーン内容リスト
     * @var array
     */
    public $pre_cam_nodisc_contents;

    /**
     * 概算見積適用価格なしキャンペーン開始日リスト
     * @var array
     */
    public $pre_cam_nodisc_starts;

    /**
     * 概算見積適用価格なしキャンペーン終了日リスト
     * @var array
     */
    public $pre_cam_nodisc_ends;

    /**
     * 概算見積コース（名称）
     * @var string
     */
    public $pre_course_name;

    /**
     * 概算見積プラン（名称）
     * @var string
     */
    public $pre_plan_name;

    /**
     * 概算見積出発エリア（名称）
     * @var string
     */
    public $pre_from_area_name;

    /**
     * 概算見積到着エリア（名称）
     * @var string
     */
    public $pre_to_area_name;

    /**
     * 訪問お見積りフォーム ページの入力パラメーター
     * @var Sgmov_Form_Pve001In
     */
    public $in;

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

	 /*
	 *
     * 他社連携キャンペーンID
     * @var string
     */
    public $oc_id;

	/**
     * 他社連携キャンペーン名称
     * @var string
     */
    public $oc_name;

    /**
     * 他社連携キャンペーン内容
     * @var Sgmov_Form_Error
     */
    public $oc_content;


}
?>
