<?php

/**
 * @package    ClassDefFile
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('hsk/Common');
/**#@-*/

/**
 * 品質選手権アンケート入力チェックします。
 * @package    View
 * @subpackage HSK
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Hsk_CheckInput extends Sgmov_View_Hsk_Common {
    
    /**
     * 
     */
    public function executeInner() {

        $result = $this->checkInput();
        return $result;
    }
}