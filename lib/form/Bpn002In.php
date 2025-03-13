<?php
/**
 * @package    ClassDefFile
 * @author     自動生成
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
Sgmov_Lib::useForms(array('Bpn001In'));
/**
 * 概算見積入力画面の入力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Bpn002In extends Sgmov_Form_Bpn001In {
    
    public $ticket;
    
////////////////////////////////////////////////////////////////////////////////
// 支払
////////////////////////////////////////////////////////////////////////////////
    
    /**
     * 送料
     * @var type 
     */
    public $delivery_charge;
    
    /**
     * 送料(無料含む)
     * @var type 
     */
    public $delivery_charge_free;
    
    /**
     * 割引
     * @var type 
     */
    public $repeater_discount;
    
    /**
     * お支払方法コード選択値
     * @var string
     */
    public $comiket_payment_method_cd_sel;

    /**
     * お支払店コード選択値
     * @var string
     */
    public $comiket_convenience_store_cd_sel = '';

    /**
     * クレジットカード番号
     * @var string
     */
    public $card_number = '';

    /**
     * 有効期限 月
     * @var string
     */
    public $card_expire_month_cd_sel = '';

    /**
     * 有効期限 年
     * @var string
     */
    public $card_expire_year_cd_sel = '';

    /**
     * セキュリティコード
     * @var string
     */
    public $security_cd = '';

    ////////////////////////////////////////////////////////////////////////////////
    // 支払-物販用
    ////////////////////////////////////////////////////////////////////////////////
    /**
     * 送料
     * @var type 
     */
    public $delivery_charge_buppan;
    
}