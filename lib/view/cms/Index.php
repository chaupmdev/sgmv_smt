<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('cms/Common');
Sgmov_Lib::useForms(array('Error', 'Cms001Out'));
/**#@-*/

/**
 * コメント表示画面を表示します。
 * @package    View
 * @subpackage Cmm
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Cms_Index extends Sgmov_View_Cms_Common {

    /**
     * コメントマスタサービス
     * @var Sgmov_Service_CommentData
     */
    private $_CommentDataService;

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_CommentDataService = new Sgmov_Service_CommentData();
    }

    /**
     * 処理を実行します。
     * <ol><li>
     * セッション情報の削除
     * </li><li>
     * GETパラメーターのチェック
     * </li><li>
     * GETパラメーターを元に出力情報を生成
     * </li></ol>
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['outForm']:出力フォーム
     * </li></ul>
     */
    public function executeInner() {

        // GETパラメータ取得
        $getParam = $this->_parseGetParameter();

        // コメントマスタより表示データ取得
        $commentsData  = array();
        $attentionData = array();

        $outForm = $this->_createOutFormByInForm($commentsData, $attentionData);

        // 表示データが無い場合、個人向けページへリダイレクト
        switch ($this->_sp_king) {
            case self::SP_LIST_KIND_COMMENTS:
                if (empty($commentsData)) {
                    Sgmov_Component_Redirect::redirectPublicSsl('/menu_personal/');
                }
                break;
            case self::SP_LIST_KIND_ATTENTION:
                if (empty($attentionData)) {
                    Sgmov_Component_Redirect::redirectPublicSsl('/menu_personal/');
                }
                break;
            default:
                break;
        }

        return array(
            'outForm'       => $outForm,
            'commentsData'  => $commentsData,
            'attentionData' => $attentionData,
        );
    }

    /**
     * フォームの値を出力フォームを生成します。
     * @return Sgmov_Form_Cms001Out 出力フォーム
     */
    private function _createOutFormByInForm(&$commentsData, &$attentionData) {
        $outForm = new Sgmov_Form_Cms001Out();
        $outForm->raw_sp_list_kind = $this->_sp_king;

        // コメントマスタデータ取得
        $db = Sgmov_Component_DB::getPublic();

        $date    = new DateTime('now');
        $nowDate = $date->format('Y-m-d');

        $commentsData  = $this->_CommentDataService->fetchCommentDataList($db, array('comment_flg' => self::SP_LIST_KIND_COMMENTS, 'comment_start_date' => $nowDate, 'comment_end_date' => $nowDate));
        $attentionData = $this->_CommentDataService->fetchCommentDataList($db, array('comment_flg' => self::SP_LIST_KIND_ATTENTION, 'comment_start_date' => $nowDate, 'comment_end_date' => $nowDate));

        // ページングインデックス判別
        if (!isset($_POST['pageIdx'])) {
            $outForm->raw_page_index = 1;
        } else {
            if (!empty($_POST['pageIdx'])) {
                $outForm->raw_page_index = $_POST['pageIdx'];
                if (intval($outForm->raw_page_index) < 1) {
                    $outForm->raw_page_index = 1;
                }
            } else {
                $outForm->raw_page_index = 1;
            }
        }

        return $outForm;
    }

    /**
     * 画面に表示するコメントリスト部分のhtmlを生成し、返却する。
     *
     * @param $commentData 表示対象のコメントマスタデータ
     * @param $pageIdx 表示予定のページ
     * @param $sp_list_kind 表示種別（お客様の声 or この子に注目）
     * @return $html　コメントマスタリストhtml
     */
    public static function _createCommentListHtml($commentData, $pageIdx, $sp_list_kind) {

        $html = '';

        if (empty($commentData)) {
            return $html;
        }

        // ページ判定
        $idx = 0;
        if (count($commentData) >= intval($pageIdx)) {
            $idx = intval($pageIdx) - 1;
        } else {
            $idx = count($commentData) - 1;
        }

        $img = '/img/no_image.png';
        if (file_exists('../cmm/files/' . $commentData[$idx]['id'] . '_2.jpg')) {
            $img = '/cmm/files/' . $commentData[$idx]['id'] . '_2.jpg';
        }
        if ($sp_list_kind == self::SP_LIST_KIND_COMMENTS) {
            $html .= '
                    <section class="voice_box">
                        <div class="customer_box clearfix">
                            <img alt="" src="'.$img.'" width="300" />
                            <h1>' . Sgmov_Component_String::htmlspecialchars($commentData[$idx]['comment_title']) . '</h1>
                            <p>
                                ' . Sgmov_Component_String::htmlspecialchars($commentData[$idx]['comment_address']) . '
                                ' . Sgmov_Component_String::htmlspecialchars($commentData[$idx]['comment_name']) . '
                            </p>
                        </div>
                        <div class="voice_text">
                            ' . Sgmov_Component_String::nl2br(Sgmov_Component_String::htmlspecialchars($commentData[$idx]['comment_text'])) . '
                        </div>
                    </section>';
        } elseif ($sp_list_kind == self::SP_LIST_KIND_ATTENTION) {
            $html .= '
                    <section class="attention_box">
                        <div class="employee_box clearfix">
                            <img alt="" src="'.$img.'" width="300" />
                            <h1>' . Sgmov_Component_String::htmlspecialchars($commentData[$idx]['centernm']) . '</h1>
                            <p>' . Sgmov_Component_String::htmlspecialchars($commentData[$idx]['comment_name']) . '</p>
                        </div>
                        <div class="attention_text">
                            '.Sgmov_Component_String::nl2br(Sgmov_Component_String::htmlspecialchars($commentData[$idx]['comment_text'])).'
                        </div>
                    </section>';
        }


        return $html;
    }

    /**
     * 画面に表示するコメントリストのページリンク部分のhtmlを生成し、返却する。
     *
     * @param $commentData 表示対象のコメントマスタデータ
     * @param $pageIdx 表示予定のページ
     * @param $arekKbn 表示種別（ヘッダー or フッター）
     * @return $html　ページエリアhtml
     */
    public static function _createPageAreaHtml($commentData, $pageIdx, $arekKbn) {

        $PAGE_LINK_MAX = self::PAGE_LINK_MAX;
        $html = '';

        if (empty($commentData)) {
            return $html;
        }

        $html .= '<div class="tablenav">' . PHP_EOL;

        // ページ判定
        $commentDataCnt = count($commentData);
        if ($commentDataCnt < intval($pageIdx)) {
            $pageIdx = $commentDataCnt;
        }

        $linkCnt = 0;
        $link    = $pageIdx - ($PAGE_LINK_MAX - 1) / 2;
        if ($link <= 0) {
            $link = 1;
        } elseif (($link + $PAGE_LINK_MAX - 1) > $commentDataCnt) {
            $link = $link - (($link + $PAGE_LINK_MAX - 1) - $commentDataCnt);
            if ($link <= 0) {
                $link = 1;
            }
        }

        if ($pageIdx > 1) {
            $html .= '<a href="#" class="next page-numbers pageLinkPrev" >&laquo; 前へ</a>' . PHP_EOL;
        }
        while ($linkCnt < $PAGE_LINK_MAX) {
            if ($commentDataCnt < $link) {
                break;
            }
            if (intval($link) == intval($pageIdx)) {
                $html .= '<span class="page-numbers current">' . $link . '</span>' . PHP_EOL;
            } else {
                $html .= '<a href="#" class="page-numbers pageLink" data-page="' . $link . '">' . $link . '</a>' . PHP_EOL;
            }
            ++$link;
            ++$linkCnt;
        }
        if ( $pageIdx < $commentDataCnt ) {
            $html .= '<a href="#" class="next page-numbers pageLinkNext" >次へ &raquo;</a>' . PHP_EOL;
        }

        $html .= '</div>' . PHP_EOL;

        return $html;
    }

    /**
     * 画面に表示するお客様の声画面へのリンク部分のhtmlを生成し、返却する。
     *
     * @param $commentData 表示対象のコメントマスタデータ
     * @param $pageIdx 表示予定のページ
     * @param $sp_list_kind 表示種別（お客様の声 or この子に注目）
     * @return $html　コメントマスタリストhtml
     */
    public static function _createCommentsLinkHtml($commentData, $pageIdx) {
        if (count($commentData) < $pageIdx) {
            $pageIdx = count($commentData);
        }
        $row = $commentData[$pageIdx - 1];
        $img = '/img/no_image.png';
        if (file_exists('../cmm/files/' . $row['id'] . '_1.jpg')) {
            $img = '/cmm/files/' . $row['id'] . '_1.jpg';
        }
        $address = Sgmov_Component_String::htmlspecialchars($row['comment_address']);
        $name    = Sgmov_Component_String::htmlspecialchars($row['comment_name']);
        $html    = '
                        <h2>お客様の声</h2>
                        <img src="' . $img . '" />
                        <p>
                            ' . $address . '<br class="sp_only" />
                            ' . $name . '
                        </p> ' . PHP_EOL;
        return $html;
    }

    /**
     * 画面に表示するこの子に注目画面へのリンク部分のhtmlを生成し、返却する。
     *
     * @param $commentData 表示対象のコメントマスタデータ
     * @param $pageIdx 表示予定のページ
     * @param $sp_list_kind 表示種別（お客様の声 or この子に注目）
     * @return $html　コメントマスタリストhtml
     */
    public static function _createAttentionLinkHtml($attentionData, $pageIdx) {
        //http://placehold.it/300x300
        if (count($attentionData) < $pageIdx) {
            $pageIdx = count($attentionData);
        }
        $row = $attentionData[$pageIdx - 1];
        $img = '/img/no_image.png';
        if (file_exists('../cmm/files/' . $row['id'] . '_1.jpg')) {
            $img = '/cmm/files/' . $row['id'] . '_1.jpg';
        }
        $office = Sgmov_Component_String::htmlspecialchars($row['centernm']);
        $name   = Sgmov_Component_String::htmlspecialchars($row['comment_name']);
        $html   = '
                        <h2>この子に注目</h2>
                        <img src="' . $img . '" />
                        <p>' . $office . '　' . $name . '</p>' . PHP_EOL;
        return $html;
    }
}