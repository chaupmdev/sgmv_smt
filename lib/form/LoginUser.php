<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**
 * ログインユーザー情報を格納するクラスです。
 *
 * @package Form
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_LoginUser
{
    /**
     * ユーザーアカウント
     * @var string
     */
    public $account;

    /**
     * 本社ユーザーの場合 TRUE 、拠点ユーザーの場合 FALSE。
     * @var boolean
     */
    public $isHonshaUser;

    /**
     * 所属拠点ID
     * @var integer
     */
    public $centerId;

    /**
     * 所属拠点名
     * @var string
     */
    public $centerName;
}
?>
