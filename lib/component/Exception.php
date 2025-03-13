<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**
 * ライブラリで使用する例外クラスです。
 *
 * 今回の案件では PHP 5.2.6 を使用します。
 * Exception のコンストラクターの $previous は PHP 5.3.0からなので
 * 直接は使用できないことに注意してください。
 *
 * [注意事項(共通)]
 *
 * エラーハンドリングでエラーが例外に変換されることを
 * 前提として設計されています。
 *
 * テストのため全て public で宣言します。
 * 名前がアンダーバーで始まるものは使用しないでください。
 *
 * テストでモックを使用するものや、実装を含めると複雑になるものは
 * 実装を分離しています。
 *
 * @package Component
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Component_Exception extends Exception
{
    /**
     * この例外の前に呼び出された例外
     * @var Exception
     */
    protected $_previous;
    
    protected $_information = array();

    /**
     * コンストラクターです。
     *
     * @param string $message メッセージ
     * @param integer $code エラーコード
     * @param exception $previous [optional] 原因となった例外
     */
    public function __construct($message, $code, $previous = NULL, $information = array())
    {
        parent::__construct($message, $code);
        $this->_previous = $previous;
        $this->_information = $information;
    }

    /**
     * この例外を文字列で表示します。
     * @return string 例外の文字列表示
     */
    public function __toString()
    {
        $ret = parent::__toString();
        if (isset($this->_previous)) {
            $ret .= "\n" . $this->_previous->__toString();
        }
        return $ret;
    }
    
    /**
     * 
     * @return type
     */
    public function getInformaton() {
       return  $this->_information;
    }
    
    /**
     * 
     * @param type $information
     */
    public function setInformation($information) {
        $this->_information = $information;
    }
}