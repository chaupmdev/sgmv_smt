<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TCP)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useAllComponents();
Sgmov_Lib::useView('ptu/Common');
Sgmov_Lib::useServices(array('MstHanbouki','MstCgCargoOpt'));
/**#@-*/

/**
 * ツアー会社からツアーを検索して返します。
 * @package    View
 * @subpackage TRA
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Ptu_SearchOptTanka extends Sgmov_View_Ptu_Common {

    /**
    * カーゴオプションサービス
    * @var Sgmov_Service_MstCgCargoOpt
    */
    private $_MstCgCargoOpt;

    /**
    * 繁忙期サービス
    * @var Sgmov_Service_MstHanbouki
    */
    public $_MstMstHanbouki;

    /**
    * 消費税サービス
    * @var Sgmov_Service_MstShohizei
    */
    private $_MstShohizei;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_MstMstHanbouki = new Sgmov_Service_MstHanbouki();
        $this->_MstCgCargoOpt  = new Sgmov_Service_MstCgCargoOpt();
        $this->_MstShohizei    = new Sgmov_Service_MstShohizei();
    }

    /**
     * 処理を実行します。
     *
     */
    public function executeInner() {

        $hikitoriDate 		= filter_input(INPUT_POST, 'hikitoriDate');
        $binshu 			= filter_input(INPUT_POST, 'binshu');

        try {
            if (empty($binshu) || empty($hikitoriDate)) {
                throw new Exception;
            }
            // DB接続
            $db = Sgmov_Component_DB::getPublic();

            // 繁忙期
            $hanboki = $this->_MstMstHanbouki->fetchHanbokiKbn($db,$hikitoriDate);
            // 単価
            $optTanka  = $this->_MstCgCargoOpt->fetchOptTankaList($db, array('binshu_cd' => $binshu,'hanboki' => $hanboki,'ymd' => $hikitoriDate));

            // 消費税
        	$shohizei = $this->_MstShohizei->fetchShohizei($db,$hikitoriDate);

        	$shohizeiArray = array('shohizei' => $shohizei);

        	$result = array_merge($optTanka,$shohizeiArray);

        }
        catch (exception $e) {
            $result = null;
        }
        return $result;
    }
}
?>