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
Sgmov_Lib::useServices(array('MstCargoUnchin','MstHanbouki'));
/**#@-*/

/**
 * ツアー会社からツアーを検索して返します。
 * @package    View
 * @subpackage TRA
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Ptu_SearchCargoUnchin extends Sgmov_View_Ptu_Common {

	/**
	* カーゴ都道府県サービス
	* @var Sgmov_Service_MstCargoArea
	*/
	private $_MstCargoArea;

    /**
     * カーゴ運賃サービス
     * @var Sgmov_Service_MstCargoUnchin
     */
    public $_MstCargoUnchinService;

    /**
    * 繁忙期サービス
    * @var Sgmov_Service_MstHanbouki
    */
    public $_MstMstHanbouki;

    /**
    * カーゴ単品品目サービス
    * @var Sgmov_Service_MstCargoTanpinHinmoku
    */
    private $_MstCargoTanpinHinmoku;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
    	$this->_MstCargoArea   		  = new Sgmov_Service_MstCargoArea();
        $this->_MstCargoUnchinService = new Sgmov_Service_MstCargoUnchin();
        $this->_MstMstHanbouki = new Sgmov_Service_MstHanbouki();
        $this->_MstCargoTanpinHinmoku = new Sgmov_Service_MstCargoTanpinHinmoku();
    }

    /**
     * 処理を実行します。
     *
     */
    public function executeInner() {

        $hatsuJis          = filter_input(INPUT_POST, 'hatsuArea');
        $chakuJis          = filter_input(INPUT_POST, 'chakuArea');
        $hikitoriDate 		= filter_input(INPUT_POST, 'hikitoriDate');
        $binshu 			= filter_input(INPUT_POST, 'binshu');
        $tanhin 			= filter_input(INPUT_POST, 'tanhin');

        try {
            if (empty($hatsuJis) || empty($chakuJis) || empty($binshu) || empty($hikitoriDate)) {
                throw new Exception;
            }
            if ($binshu == self::BINSHU_TANPINYOSO && empty($tanhin)) {
            	throw new Exception;
            }
            // DB接続
            $db = Sgmov_Component_DB::getPublic();
            // エリアコード取得
            $hatsuArea = $this->_MstCargoArea->fetchCargoAreaCd($db, array('jiscd' => $hatsuJis));
            $chakuArea = $this->_MstCargoArea->fetchCargoAreaCd($db, array('jiscd' => $chakuJis));
            if (empty($hatsuArea) || empty($chakuArea)) {
            	throw new Exception;
            }

            //重量
            if ($binshu == self::BINSHU_TANPINYOSO && !empty($tanhin)) {
            	$weight  = $this->_MstCargoTanpinHinmoku->fetchWeight($db, array('hinmokuCd' => $tanhin));
            } else {
            	$weight = self::CAGO_WEIGHT;
            }

            // 繁忙期
            $hanboki = $this->_MstMstHanbouki->fetchHanbokiKbn($db,$hikitoriDate);
            // 重量
            $maxWeight = $this->_MstCargoUnchinService->fetchJyuryoutai($db, array('hstsu_area' => $hatsuArea
            																		,'chaku_area' => $chakuArea
																		            ,'jyuryoutai' => $weight
																		            ,'binshu_cd' => $binshu
																		            ,'hanboki_kbn' => $hanboki
																		            ,'hikitori_date' => $hikitoriDate));
            if (empty($maxWeight)) {
            	throw new Exception;
            }
			// 運賃
            $unchin = $this->_MstCargoUnchinService->fetchCargoUnchin($db, array('hstsu_area' => $hatsuArea
            																		,'chaku_area' => $chakuArea
																		            ,'jyuryoutai' => $maxWeight
																		            ,'binshu_cd' => $binshu
																		            ,'hanboki_kbn' => $hanboki
																		            ,'hikitori_date' => $hikitoriDate));

        }
        catch (exception $e) {
            $unchin = null;
        }
        return $unchin;
    }
}
?>