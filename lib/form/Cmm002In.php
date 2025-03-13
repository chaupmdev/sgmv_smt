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
 * コメントマスタ設定画面の入力フォームです。
 *
 * @package    Form
 * @author     自動生成
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Form_Cmm002In {

    /**
     * コメントID
     * @var string
     */
    public $comment_id = '';

    /**
     * コメント区分
     * @var string
     */
    public $comment_flg = '';

    /**
     * コメントタイトル
     * @var string
     */
    public $comment_title = '';

    /**
     * コメント住所
     * @var string
     */
    public $comment_address = '';

    /**
     * コメント氏名
     * @var string
     */
    public $comment_name = '';

    /**
     * コメント営業所
     * @var string
     */
    public $comment_office = '';

    /**
     * コメントテキスト
     * @var string
     */
    public $comment_text = '';

    /**
     * コメント写真[1]
     * @var string
     */
    public $comment_file_1 = '';

    /**
     * コメント写真[2]
     * @var string
     */
    public $comment_file_2 = '';

    /**
     * コメント掲載開始日
     * @var string
     */
    public $comment_start_date = '';
    public $comment_start_date_y = '';
    public $comment_start_date_m = '';
    public $comment_start_date_d = '';

    /**
     * コメント掲載終了日
     * @var string
     */
    public $comment_end_date = '';
    public $comment_end_date_y = '';
    public $comment_end_date_m = '';
    public $comment_end_date_d = '';

}