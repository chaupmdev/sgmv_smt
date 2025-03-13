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
Sgmov_Lib::useComponents(array('String'));
/**#@-*/

 /**
 * アンケート確認画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Pqu002Out
{
    /**
     * 質問1
     * @var string
     */
    public $raw_question1 = '';

    /**
     * 質問2
     * @var string
     */
    public $raw_question2 = '';

    /**
     * 質問2_5テキスト
     * @var string
     */
    public $raw_question2_5_text = '';

    /**
     * 質問3
     * @var string
     */
    public $raw_question3 = '';

    /**
     * 質問4
     * @var string
     */
    public $raw_question4 = '';

    /**
     * 質問5
     * @var string
     */
    public $raw_question5 = '';

    /**
     * 質問6
     * @var string
     */
    public $raw_question6 = '';

    /**
     * 質問7
     * @var string
     */
    public $raw_question7 = '';

    /**
     * 質問8
     * @var string
     */
    public $raw_question8 = '';

    /**
     * 質問9
     * @var string
     */
    public $raw_question9 = '';

    /**
     * 質問10テキスト
     * @var string
     */
    public $raw_question10_text = '';

    /**
     * エンティティ化された質問1を返します。
     * @return string エンティティ化された質問1
     */
    public function question1()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_question1);
    }

    /**
     * エンティティ化された質問2を返します。
     * @return string エンティティ化された質問2
     */
    public function question2()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_question2);
    }

    /**
     * エンティティ化された質問2_5テキストを返します（改行文字の前にBRタグが挿入されます）。
     * @return string エンティティ化された質問2_5テキスト
     */
    public function question2_5_text()
    {
        return Sgmov_Component_String::nl2br(Sgmov_Component_String::htmlspecialchars($this->raw_question2_5_text));
    }

    /**
     * エンティティ化された質問3を返します。
     * @return string エンティティ化された質問3
     */
    public function question3()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_question3);
    }

    /**
     * エンティティ化された質問4を返します。
     * @return string エンティティ化された質問4
     */
    public function question4()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_question4);
    }

    /**
     * エンティティ化された質問5を返します。
     * @return string エンティティ化された質問5
     */
    public function question5()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_question5);
    }

    /**
     * エンティティ化された質問6を返します。
     * @return string エンティティ化された質問6
     */
    public function question6()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_question6);
    }

    /**
     * エンティティ化された質問7を返します。
     * @return string エンティティ化された質問7
     */
    public function question7()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_question7);
    }

    /**
     * エンティティ化された質問8を返します。
     * @return string エンティティ化された質問8
     */
    public function question8()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_question8);
    }

    /**
     * エンティティ化された質問9を返します。
     * @return string エンティティ化された質問9
     */
    public function question9()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_question9);
    }

    /**
     * エンティティ化された質問10テキストを返します（改行文字の前にBRタグが挿入されます）。
     * @return string エンティティ化された質問10テキスト
     */
    public function question10_text()
    {
        return Sgmov_Component_String::nl2br(Sgmov_Component_String::htmlspecialchars($this->raw_question10_text));
    }

}
?>
