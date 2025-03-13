<?php
/**
 * @package    ClassDefFile
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useAllComponents();
Sgmov_Lib::useView('Public');
Sgmov_Lib::useServices(array('Comiket'));
/**#@-*/

/**
 * comiket_detailの no_chg_flg を更新します。
 * @package    View
 * @subpackage CST
 * @author     K.Sawada
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
abstract class Sgmov_View_Cst_Common extends Sgmov_View_Public {
    /**
     * 
     * @param type $param
     * @return type
     */
    protected static function getChkD($param) {

        // 顧客コードを配列化
        $param2 = str_split($param);


        // 掛け算数値配列（固定らしいのでベタ書き）
        $intCheck = array(
            0 => 4,
            1 => 3,
            2 => 2,
            3 => 9,
            4 => 8,
            5 => 7,
            6 => 6,
            7 => 5,
            8 => 4,
            9 => 3,
    //                    10 => 2,
        );

        $total = 0;
        for ($i = 0; $i < count($intCheck); $i++) {
            $total += $param2[$i] * $intCheck[$i];
        }
        return $total;
    }
    
    /**
     * 
     * @param type $comiketId
     */
    protected function checkChkDComiketId($comiketId) {
       
        if(!empty($comiketId)) {
            // チェックデジットチェック
            if(strlen($comiketId) <= 10){
                Sgmov_Component_Log::debug ( '11桁以上ではない' );
                return false;
            }
            
            if(!is_numeric($comiketId)){
                Sgmov_Component_Log::debug ( '数値ではない' );
                return false;
            }
            $id = substr($comiketId, 0, 10);
            $cd = substr($comiketId, 10);
            
            Sgmov_Component_Log::debug ( 'id:'.$id );
            Sgmov_Component_Log::debug ( 'cd:'.$cd );
            
            $sp = self::getChkD($id);
            
            Sgmov_Component_Log::debug ( 'sp:'.$sp );

            if($sp !== intval($cd)){
                Sgmov_Component_Log::debug ( 'CD不一致' );
                return false;
            }
            
            $comiketId = $id;
        }
        
        return $comiketId;
    }
    
    /**
     * 配列を再帰的にXML用の文字列に変換
     *
     * foreachの$valueの値が配列でなくなるまで再帰
     * ※ ['key' => array()]という場合も考慮
     *
     * @param string $name 要素の名前
     * @param array or string $data 配列もしくは要素の中身
     * @return string $str
     */
    protected function array2string($name = '', $data){
        $str = '';

        //$EOL = PHP_EOL;
        $EOL = '';

        if(!empty($name)) $str .= $EOL."<".$name.">";

        if(!is_array($data)){
            $str .= $data;
        }else{
            foreach ($data as $key => $val){
                if(is_numeric($key))
                {
                    $str .= $this->array2string('', $val);
                }
                else
                {
                    if(is_array($val) && !empty($val))
                    {
                        $str .= $this->array2string($key, $val);
                    }
                    else
                    {
                        $str .= $EOL."<".$key.">";
                        $str .= (empty($val) && $val!='0') ? "" : $val;
                        $str .= "</".$key.">";
                    }
                }
            }
        }

        if(!empty($name))$str .= $EOL."</".$name.">";

        return $str;
    }
}