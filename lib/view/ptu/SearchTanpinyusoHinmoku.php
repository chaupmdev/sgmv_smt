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
Sgmov_Lib::useServices(array('MstCargoTanpinKanren'));
/**#@-*/

/**
 * ツアー会社からツアーを検索して返します。
 * @package    View
 * @subpackage TRA
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Ptu_SearchTanpinyusoHinmoku extends Sgmov_View_Ptu_Common {

    /**
    * カーゴ単品品目関連サービス
    * @var Sgmov_Service_MstCargoTanpinKanren
    */
    private $_MstCargoTanpinKanren;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_MstCargoTanpinKanren = new Sgmov_Service_MstCargoTanpinKanren();
    }

    /**
     * 処理を実行します。
     *
     */
    public function executeInner() {

        $tanhin = filter_input(INPUT_POST, 'tanhin');

        try {

            if (empty($tanhin)) {
                throw new Exception;
            }
            // DB接続
            $db = Sgmov_Component_DB::getPublic();

			// オプションコード
            $optCds = $this->_MstCargoTanpinKanren->fetchCagoTanpinOptCds($db, array('hinmoku_cd' => $tanhin));

        }
        catch (exception $e) {
            $optCds = null;
        }
        return $optCds;
    }
}
?>