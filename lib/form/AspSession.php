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
Sgmov_Lib::useForms(array('Error', 'Asp004In', 'Asp005In', 'Asp006In', 'Asp008In', 'Asp009In', 'Asp010In'));
/**#@-*/

 /**
 * 特価・キャンペーン設定のセッションフォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_AspSession
{
    /**
     * 画面ID
     * @var string
     */
    public $gamen_id;

    /**
     * 特価一覧種別
     * @var string
     */
    public $sp_list_kind;

    /**
     * 特価一覧表示モード
     * @var string
     */
    public $sp_list_view_mode;

    /**
     * 戻る用特価コード
     * @var string
     */
    public $backto_sp_cd;

    /**
     * 特価コード
     * @var string
     */
    public $sp_cd;

    /**
     * 特価タイムスタンプ
     * @var string
     */
    public $sp_timestamp;

    /**
     * ASP002エラーフォーム
     * @var Sgmov_Form_Error
     */
    public $asp002_error;

    /**
     * ASP004エラーフォーム
     * @var Sgmov_Form_Error
     */
    public $asp004_error;

    /**
     * ASP004入力フォーム
     * @var Sgmov_Form_Asp004In
     */
    public $asp004_in;

    /**
     * ASP004状態
     * @var string
     */
    public $asp004_status;

    /**
     * ASP005エラーフォーム
     * @var Sgmov_Form_Error
     */
    public $asp005_error;

    /**
     * ASP005入力フォーム
     * @var Sgmov_Form_Asp005In
     */
    public $asp005_in;

    /**
     * ASP005状態
     * @var string
     */
    public $asp005_status;

    /**
     * ASP006エラーフォーム
     * @var Sgmov_Form_Error
     */
    public $asp006_error;

    /**
     * ASP006入力フォーム
     * @var Sgmov_Form_Asp006In
     */
    public $asp006_in;

    /**
     * ASP006状態
     * @var string
     */
    public $asp006_status;

    /**
     * 金額設定区分
     * @var string
     */
    public $priceset_kbn;

    /**
     * ASP008エラーフォーム
     * @var Sgmov_Form_Error
     */
    public $asp008_error;

    /**
     * ASP008入力フォーム
     * @var Sgmov_Form_Asp008In
     */
    public $asp008_in;

    /**
     * ASP008状態
     * @var string
     */
    public $asp008_status;

    /**
     * ASP009エラーフォーム
     * @var Sgmov_Form_Error
     */
    public $asp009_error;

    /**
     * ASP009入力フォーム
     * @var Sgmov_Form_Asp009In
     */
    public $asp009_in;

    /**
     * ASP009状態
     * @var string
     */
    public $asp009_status;

    /**
     * ASP010入力フォーム
     * @var Sgmov_Form_Asp010In
     */
    public $asp010_in;

    /**
     * ASP010エラーフォーム
     * @var Sgmov_Form_Error
     */
    public $asp010_error;

}
?>
