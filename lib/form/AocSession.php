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
     * 他社連携キャンペーンフラグ
     * @var string
     */
    public $oc_flg;

    /**
     * 他社連携キャンペーン内容
     * @var string
     */
    public $oc_content;

    /**
     * 他社連携キャンペーン適応
     * @var string
     */
    public $oc_application;

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
