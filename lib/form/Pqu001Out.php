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
 * アンケート入力画面の出力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Pqu001Out
{
    /**
     * 質問1コード選択値
     * @var string
     */
    public $raw_question1_cd_sel = '';

    /**
     * 質問2_1選択フラグ
     * @var string
     */
    public $raw_question2_1_sel_flag = '';

    /**
     * 質問2_2選択フラグ
     * @var string
     */
    public $raw_question2_2_sel_flag = '';

    /**
     * 質問2_3選択フラグ
     * @var string
     */
    public $raw_question2_3_sel_flag = '';

    /**
     * 質問2_4選択フラグ
     * @var string
     */
    public $raw_question2_4_sel_flag = '';

    /**
     * 質問2_5選択フラグ
     * @var string
     */
    public $raw_question2_5_sel_flag = '';

    /**
     * 質問2_5テキスト
     * @var string
     */
    public $raw_question2_5_text = '';

    /**
     * 質問3コード選択値
     * @var string
     */
    public $raw_question3_cd_sel = '';

    /**
     * 質問4コード選択値
     * @var string
     */
    public $raw_question4_cd_sel = '';

    /**
     * 質問5コード選択値
     * @var string
     */
    public $raw_question5_cd_sel = '';

    /**
     * 質問6コード選択値
     * @var string
     */
    public $raw_question6_cd_sel = '';

    /**
     * 質問7コード選択値
     * @var string
     */
    public $raw_question7_cd_sel = '';

    /**
     * 質問8コード選択値
     * @var string
     */
    public $raw_question8_cd_sel = '';

    /**
     * 質問9コード選択値
     * @var string
     */
    public $raw_question9_cd_sel = '';

    /**
     * 質問10テキスト
     * @var string
     */
    public $raw_question10_text = '';

    /**
     * エンティティ化された質問1コード選択値を返します。
     * @return string エンティティ化された質問1コード選択値
     */
    public function question1_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_question1_cd_sel);
    }

    /**
     * エンティティ化された質問2_1選択フラグを返します。
     * @return string エンティティ化された質問2_1選択フラグ
     */
    public function question2_1_sel_flag()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_question2_1_sel_flag);
    }

    /**
     * エンティティ化された質問2_2選択フラグを返します。
     * @return string エンティティ化された質問2_2選択フラグ
     */
    public function question2_2_sel_flag()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_question2_2_sel_flag);
    }

    /**
     * エンティティ化された質問2_3選択フラグを返します。
     * @return string エンティティ化された質問2_3選択フラグ
     */
    public function question2_3_sel_flag()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_question2_3_sel_flag);
    }

    /**
     * エンティティ化された質問2_4選択フラグを返します。
     * @return string エンティティ化された質問2_4選択フラグ
     */
    public function question2_4_sel_flag()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_question2_4_sel_flag);
    }

    /**
     * エンティティ化された質問2_5選択フラグを返します。
     * @return string エンティティ化された質問2_5選択フラグ
     */
    public function question2_5_sel_flag()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_question2_5_sel_flag);
    }

    /**
     * エンティティ化された質問2_5テキストを返します。
     * @return string エンティティ化された質問2_5テキスト
     */
    public function question2_5_text()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_question2_5_text);
    }

    /**
     * エンティティ化された質問3コード選択値を返します。
     * @return string エンティティ化された質問3コード選択値
     */
    public function question3_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_question3_cd_sel);
    }

    /**
     * エンティティ化された質問4コード選択値を返します。
     * @return string エンティティ化された質問4コード選択値
     */
    public function question4_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_question4_cd_sel);
    }

    /**
     * エンティティ化された質問5コード選択値を返します。
     * @return string エンティティ化された質問5コード選択値
     */
    public function question5_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_question5_cd_sel);
    }

    /**
     * エンティティ化された質問6コード選択値を返します。
     * @return string エンティティ化された質問6コード選択値
     */
    public function question6_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_question6_cd_sel);
    }

    /**
     * エンティティ化された質問7コード選択値を返します。
     * @return string エンティティ化された質問7コード選択値
     */
    public function question7_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_question7_cd_sel);
    }

    /**
     * エンティティ化された質問8コード選択値を返します。
     * @return string エンティティ化された質問8コード選択値
     */
    public function question8_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_question8_cd_sel);
    }

    /**
     * エンティティ化された質問9コード選択値を返します。
     * @return string エンティティ化された質問9コード選択値
     */
    public function question9_cd_sel()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_question9_cd_sel);
    }

    /**
     * エンティティ化された質問10テキストを返します。
     * @return string エンティティ化された質問10テキスト
     */
    public function question10_text()
    {
        return Sgmov_Component_String::htmlspecialchars($this->raw_question10_text);
    }

}
?>
