<?php
/**
 * @package    ClassDefFile
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useAllComponents();
Sgmov_Lib::useView('Public');
/**#@-*/

/**
 * クルーズリピーターをCSVファイルでダウンロードします。
 * @package    View
 * @subpackage ACR
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Acr_DownloadRepeater extends Sgmov_View_Public {

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
    }

    public function executeInner() {

        $date = new DateTime();

        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        $query = <<<END_OF_SQL
        SELECT ARRAY_TO_STRING(ARRAY(
            SELECT
                '"' || REPLACE(COALESCE(cruise_repeater.tel, ''), '"', '""') || '",' || 
                '"' || REPLACE(COALESCE(cruise_repeater.zip, ''), '"', '""') || '",' || 
                '"' || REPLACE(COALESCE(cruise_repeater.address, ''), '"', '""') || '",' || 
                '"' || REPLACE(COALESCE(cruise_repeater.name, ''), '"', '""') || '",' || 
                '"' || REPLACE(COALESCE(cruise_repeater.travel_cd, ''), '"', '""') || '",' || 
                '"' || REPLACE(COALESCE(cruise_repeater.client_no, ''), '"', '""') || '",' || 
                '"' || REPLACE(COALESCE(TO_CHAR(cruise_repeater.created, 'YYYY年MM月DD日 HH24時MI分SS.US秒'), ''), '"', '""') || '",' || 
                '"' || REPLACE(COALESCE(TO_CHAR(cruise_repeater.modified, 'YYYY年MM月DD日 HH24時MI分SS.US秒'), ''), '"', '""') || '"'  AS line
            FROM
                cruise_repeater
            ORDER BY
                cruise_repeater.tel
        ), CHR(13) || CHR(10)) AS csv;
END_OF_SQL;

        $header = array(
            "電話番号",
            "郵便番号",
            "住所",
            "名前",
            "ツアーコード",
            "顧客管理No",
            "登録日時",
            "更新日時",
        );
        $csv = '"' . implode('","', $header) . '"' . "\r\n";

        $result = $db->executeQuery($query);
        $size = $result->size();
        for ($i = 0; $i < $size; ++$i) {
            $row = $result->get($i);
            $csv .= $row['csv'];
        }

        $filename = 'repeater_' . $date->format('Y-m-d-His') . '.csv';

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $filename);
        echo mb_convert_encoding($csv, 'SJIS-win', 'UTF-8');
    }

    public function getFeatureId() {
    }
}