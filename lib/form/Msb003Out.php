<?php
/**
 * @package    ClassDefFile
 * @author     自動生成
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useComponents(array('String'));
Sgmov_Lib::useForms(array('Msb002Out'));
/**#@-*/

/**
 * 入力画面の出力フォームです。
 * TODO TwigやSmartyなどのテンプレートエンジンの導入を検討する
 * @package    Form
 * @author     自動生成
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Msb003Out extends Sgmov_Form_Msb002Out {
    
    public $raw_qr_code_string = '';
    
    public $raw_convenience_store_cd_sel;
    
    public $raw_mail;
    
    public $raw_merchant_result;
    
    public $raw_payment_method_cd_sel;
    
    public $raw_payment_url;
    
    public $raw_receipt_cd;
    
    public $raw_sgf_shop_order_id = '';
    
    public $raw_sgf_transaction_id = '';
    
    public function qr_code_string() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_qr_code_string);
    }
    
    public function convenience_store_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_convenience_store_cd_sel);
    }
    
    public function mail() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_mail);
    }
    
    public function merchant_result() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_merchant_result);
    }
    
    public function payment_method_cd_sel() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_payment_method_cd_sel);
    }
    
    public function payment_url() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_payment_url);
    }
    
    public function receipt_cd() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_receipt_cd);
    }
    
    public function sgf_shop_order_id() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sgf_shop_order_id);
    }
    
    public function sgf_transaction_id() {
        return Sgmov_Component_String::htmlspecialchars($this->raw_sgf_transaction_id);
    }    
}