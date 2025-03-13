<?php
/**
 * @package    ClassDefFile
 * @author     AnNV6
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useServices(array('Hachakuten'));
Sgmov_Lib::useView('mlk/Common');
Sgmov_Lib::useForms(array('Error', 'EveSession'));
/**#@-*/

/**
 * コミケサービスのお申し込み入力画面を表示します。
 * @package    View
 * @subpackage EVE
 * @author     AnNV6
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_HachakutenMst extends Sgmov_View_Eve_Common {

    /**
     * 都道府県サービス
     * @var Sgmov_Service_Hachakuten
     */
    protected $_Hachakuten;

    const FIXED_NUMBER_OF_COLUMN_IN_EACH_ROW = 14;

    const PREG_MATCH_NUMBER_ONLY = "/^[0-9]*$/";
    const PREG_MATCH_HACHAKUTEN_CD_WITH_DASH = "/^[A-Za-z]{2}-[A-Za-z]{3}[0-9]{2}$/";
    const PREG_MATCH_ZIPCODE_WITH_DASH = "/^[0-9]{3}-[0-9]{4}$/";
    const PREG_MATCH_TEL_WITH_DASH = "/^[0-9 \-]+$/";
    const PREG_MATCH_DATE_STRING_WITH_SLASH = "/^[0-9]{4}\/(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])$/";
    const PREG_MATCH_DATE_STRING_WITH_DASH = "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/";
    const PREG_MATCH_TIME_STRING_WITH_COLON = "/^[0-9]{2}:[0-9]{2}$/";

    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        parent::__construct();
        
        $this->_Hachakuten     = new Sgmov_Service_Hachakuten();
    }

    public function executeInner() {
        
        $db = Sgmov_Component_DB::getPublic();
        
        // セッションに情報があるかどうかを確認
        $session = Sgmov_Component_Session::get();

        if (@empty($_SESSION["MLK"])) {
            $_SESSION["MLK"] = array();
        }

        if(@empty($_SERVER["HTTP_REFERER"]) || strpos($_SERVER["HTTP_REFERER"], "/mlk/") == false) {
            // セッション情報を破棄
            $session->deleteForm(self::FEATURE_ID);
            $_SESSION["MLK"] = array();
        }
        $request = $_POST;
        
        if (isset($request['btnExport'])) {
            $dataCSV = $this->_Hachakuten->getDataForExport($db, $request);
            
            if (empty($dataCSV)) {
                echo '<script>alert("出力対象がありません。");</script>';
            } else {
                if (count($dataCSV) > 10000) {
                    echo '<script>alert("10000件以下となるように検索条件を絞り込んでください。");</script>';
                } else {
                    $this->exportCSV($dataCSV);
                }
            }
            return array(
                'status' => 'success',
                'message' => '初期情報処理に成功しました。',
                'res_data' => array(
                    'request' => $request
                )
            );
        }

        if (isset($request['import'])) {
            $errors = $this->validate($_FILES);

            if (@!empty($errors)) {
                $_SESSION["MLK"]["ERROR_MLK_HACHAKUTEN_IMPORT"] = $errors;
                @Sgmov_Component_Redirect::redirectPublicSsl("/mlk/hachakuten_mst");
                exit;
            } else {
                $cntBeforeInport = $this->_Hachakuten->countAll($db);
                $import = $this->doImport($db, $_FILES);
                $cntAfterInport = $this->_Hachakuten->countAll($db);
                $_SESSION["MLK"]["SUCCESS"] = 1;
                $_SESSION["MLK"]["COUNT_BEFORE_IMPORT_MSG"] = "取り込み前のマスタ件数　" . $cntBeforeInport . "件";
                $_SESSION["MLK"]["COUNT_AFTER_IMPORT_MSG"] = "取り込み後のマスタ件数　" . $cntAfterInport . "件";

                $_SESSION["MLK"]["ERROR_MLK_HACHAKUTEN_IMPORT"] = [];

                return;
            }
        }
        $_SESSION["MLK"]["SUCCESS"] = 0;
        $_SESSION["MLK"]["COUNT_BEFORE_IMPORT_MSG"] = "";
        $_SESSION["MLK"]["COUNT_AFTER_IMPORT_MSG"] = "";
        return array(
            'status' => 'success',
            'message' => '初期情報処理に成功しました。',
            'res_data' => array(
                'request' => $request,
                'error_info' => isset($_SESSION["MLK"]["ERROR_MLK_HACHAKUTEN_IMPORT"]) ? @$_SESSION["MLK"]["ERROR_MLK_HACHAKUTEN_IMPORT"] : [],
            )
        );
    }

    private function doImport($db, $file) {
        $tmpName = $file['up_file']['tmp_name'];
        $csvAsArray = array_map('str_getcsv', file($tmpName));
        unset($csvAsArray[0]);
        $data = $this->buildImportData($csvAsArray);
        $ins = $this->_Hachakuten->doImportSvc($db, $data);
        return;
    }

    private function buildImportData($csvAsArray) {

        // Array of keys of 1 row in file, which needs to convert to kana
        // 0: 発着地識別番号 / hachakuten_shikibetu_cd
        // 2: 郵便番号 / zip
        // 5: 電話番号 / tel
        // 8: 分類 / type
        // 9: 適用開始日 / start_date
        // 10: 適用終了日 / end_date
        // 11: 申込画面締め時間 / input_end_time
        // 12: 確認画面締め時間 / confirm_end_time
        // 13: 空港フライト時間判定用時間 / airport_flight_end_time
        $idxConvertKana = array(0, 2, 5, 8, 9, 10, 11, 12, 13);
        // Convert some data into kana
        foreach ($csvAsArray as $key => $row) {

            $cvtRow = array();
            for ($i = 0; $i < count($row); $i++) {
                if (in_array($i, $idxConvertKana)) {
                    $val = mb_convert_kana($row[$i], 'rnask', 'UTF-8');

                    // If 適用開始日 / start_date
                    if ($i == 9) {
                        $slashStrDate = preg_match(self::PREG_MATCH_DATE_STRING_WITH_SLASH, $val);
                        if ($slashStrDate) {
                            $val = date('Y/m/d', strtotime('-1 day', strtotime($val)));
                        } else {
                            $val = date('Y-m-d', strtotime('-1 day', strtotime($val)));
                        }
                    }
                    $cvtRow[$i] = $val;
                } else {
                    $cvtRow[$i] = $row[$i];
                }
            }

            // Re-format 郵便番号 / zip code
            $isDashZip = (strpos($cvtRow[2], '-') === false) ? false : true;
            if ($isDashZip) {
                $cvtRow[2] = str_replace('-', '', $cvtRow[2]);
            }
            
            // Re-format 申込画面締め時間 / input_end_time
            $isColonInputEndTime = (strpos($cvtRow[11], ':') === false) ? false : true;
            if ($isColonInputEndTime) {
                $cvtRow[11] = str_replace(':', '', $cvtRow[11]);
            }
            
            // Re-format 確認画面締め時間 / confirm_end_time
            $isColonConfirmEndTime = (strpos($cvtRow[12], ':') === false) ? false : true;
            if ($isColonConfirmEndTime) {
                $cvtRow[12] = str_replace(':', '', $cvtRow[12]);
            }
            
            // Re-format 空港フライト時間判定用時間 / airport_flight_end_time
            if (!empty($cvtRow[13])) {
                $isColonAirportFlightEndTime = (strpos($cvtRow[13], ':') === false) ? false : true;
                if ($isColonAirportFlightEndTime) {
                    $cvtRow[13] = str_replace(':', '', $cvtRow[13]);
                }
            }

            // Remove data of column 区 out (no need to insert)
            unset($cvtRow[4]);

            // Re-index array after unset
            $reIndxArr = array_values($cvtRow);
            $csvAsArray[$key] = $reIndxArr;
        }

        return $csvAsArray;
    }

    function validate($file) {
        $errInfoList = array();

        if (empty($file['up_file']['tmp_name'])) {

            $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => "取込ファイルを選択してください。");
            $errInfoList[] = $errorInfo;

        } else {

            $mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
            if(!in_array($file['up_file']['type'], $mimes)){
                $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => "CSVファイルを指定してください。");
                $errInfoList[] = $errorInfo;

            } else {

                $tmpName = $file['up_file']['tmp_name'];
                $csvAsArray = array_map('str_getcsv', file($tmpName));

                if (count($csvAsArray) < 2) {

                    $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => "取込ファイルのデータがありません。");
                    $errInfoList[] = $errorInfo;

                } else if (count($csvAsArray) >= 2) {

                    $hachakuten_shikibetu_cd = array();
                    foreach ($csvAsArray as $key => $row) {
                        $line = $key + 1;
                        if ($key == 0) continue;
                        if (count($row) != self::FIXED_NUMBER_OF_COLUMN_IN_EACH_ROW) {

                            $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の項目が足りないです。");
                            $errInfoList[] = $errorInfo;

                        } else {
                            $hachakuten_shikibetu_cd[] = $row[0];
                        }
                    }

                    if (!empty($errInfoList)) {
                        return $errInfoList;
                    }

                    $errInfoList = $this->validateData($csvAsArray);
                    if (!empty($errInfoList)) {
                        return $errInfoList;
                    }
                    
                    // Validate duplicate hachakuten_shikibetu_cd
                    if (!empty($hachakuten_shikibetu_cd)) {
                        // Unique values
                        $unique = array_unique($hachakuten_shikibetu_cd);
                        // Duplicates
                        $duplicates = array_diff_assoc($hachakuten_shikibetu_cd, $unique);
                        // Get duplicate keys
                        $duplicate_keys = array_keys(array_intersect($hachakuten_shikibetu_cd, $duplicates));
                        if (!empty($duplicate_keys)) {
                            for ($i = 0; $i < count($duplicate_keys); $i++) {
                                $line = $duplicate_keys[$i] + 2;
                                $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「発着識別記号」が重複しています。");
                                $errInfoList[] = $errorInfo;
                            }
                        }
                    }

                    if (!empty($errInfoList)) {
                        return $errInfoList;
                    }
                }
            }
        }
        return $errInfoList;
    }

    function validateData($csvAsArray) {

        $errInfoList = array();
        
        try {

            foreach ($csvAsArray as $key => $row) {
                $line = $key + 1;
                if ($key == 0) continue;
                
                // 発着地識別番号 / hachakuten_shikibetu_cd
                if (empty($row[0])) {
                    $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「発着地識別番号」が必須です。");
                    $errInfoList[] = $errorInfo;
                } else {
                    $cd = mb_convert_kana($row[0], 'rnask', 'UTF-8');
                    if (mb_strlen($cd) != 8) {
                        $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「発着地識別番号」は8桁数で指定してください。");
                        $errInfoList[] = $errorInfo;
                    } else {
                        $isCorrentDashCdFormat = preg_match(self::PREG_MATCH_HACHAKUTEN_CD_WITH_DASH, $cd);
                        if (!$isCorrentDashCdFormat) {
                            $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「発着地識別番号」のフォーマットが間違いました。XX-YYY99形式で指定してください。");
                            $errInfoList[] = $errorInfo;
                        }
                    }
                }

                // 運営会社 / note

                // 郵便番号 / zip
                if (empty($row[2])) {
                    $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「郵便番号」が必須です。");
                    $errInfoList[] = $errorInfo;

                } else {
                    $zip = mb_convert_kana($row[2], 'rnask', 'UTF-8');
                    // Check zip code string has dash (-) or not
                    if (mb_strlen($zip) != 8) {
                        $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「郵便番号」は8桁数で指定してください。");
                        $errInfoList[] = $errorInfo;
                    } else {
                        $isCorrentDashZipFormat = preg_match(self::PREG_MATCH_ZIPCODE_WITH_DASH, $zip);
                        if (!$isCorrentDashZipFormat) {
                            $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「郵便番号」のフォーマットが間違いました。999-9999形式で指定してください。");
                            $errInfoList[] = $errorInfo;
                        }
                    }
                }

                // 住所 / address
                if (empty($row[3])) {
                    $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「住所」が必須です。");
                    $errInfoList[] = $errorInfo;

                } else if (mb_strlen($row[3]) > 100) {
                    $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「住所」は100桁数まで指定してください。");
                    $errInfoList[] = $errorInfo;
                }

                // 区 / district

                // 電話番号 / tel
                if (empty($row[5])) {
                    $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「電話番号」が必須です。");
                    $errInfoList[] = $errorInfo;

                } else {
                    $tel = mb_convert_kana($row[5], 'rnask', 'UTF-8');
                    if (mb_strlen($tel) > 15) {
                        $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「電話番号」は15桁数まで指定してください。");
                        $errInfoList[] = $errorInfo;
    
                    } else if (!preg_match(self::PREG_MATCH_TEL_WITH_DASH, $tel)) {
                        $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「電話番号」は半角数字と「-」で指定してください。");
                        $errInfoList[] = $errorInfo;
                    }
                }

                // 名称_日本語 / name_jp
                if (empty($row[6])) {
                    $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「名称_日本語」が必須です。");
                    $errInfoList[] = $errorInfo;

                } else if (mb_strlen($row[6]) > 100) {
                    $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「名称_日本語」は100桁数まで指定してください。");
                    $errInfoList[] = $errorInfo;
                }

                // 名称（英語） / name_en
                if (empty($row[7])) {
                    $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「名称（英語）」が必須です。");
                    $errInfoList[] = $errorInfo;

                } else if (mb_strlen($row[7]) > 100) {
                    $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「名称（英語）」は100桁数まで指定してください。");
                    $errInfoList[] = $errorInfo;
                }

                // 分類 / type
                $type = '';
                if (empty($row[8])) {
                    $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「分類」が必須です。");
                    $errInfoList[] = $errorInfo;

                } else {
                    $type = mb_convert_kana($row[8], 'rnask', 'UTF-8');
                    if (mb_strlen($type) != 1) {
                        $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「分類」は1桁数で指定してください。");
                        $errInfoList[] = $errorInfo;
    
                    } else if ($type != '1' && $type != '2' && $type != '3') {
                        $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「分類」は「1」、「2」、「3」の以外が設定できません。");
                        $errInfoList[] = $errorInfo;
                    }
                }

                // 適用開始日 / start_date
                $stDate = '';
                if (empty($row[9])) {
                    $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「適用開始日」が必須です。");
                    $errInfoList[] = $errorInfo;

                } else {
                    $stDate = mb_convert_kana($row[9], 'rnask', 'UTF-8');
                    if (!$this->isValidDate($stDate)) {
                        $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「適用開始日」のフォーマットが間違いました。YYYY/MM/DD形式で指定してください。");
                        $errInfoList[] = $errorInfo;
                    }
                }

                // 適用終了日 / end_date
                $edDate = '';
                if (empty($row[10])) {
                    $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「適用終了日」が必須です。");
                    $errInfoList[] = $errorInfo;

                } else {
                    $edDate = mb_convert_kana($row[10], 'rnask', 'UTF-8');
                    if (!$this->isValidDate($edDate)) {
                        $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「適用終了日」のフォーマットが間違いました。YYYY/MM/DD形式で指定してください。");
                        $errInfoList[] = $errorInfo;
                    }
                }

                // 申込画面締め時間 / input_end_time
                if (empty($row[11])) {
                    $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「申込画面締め時間」が必須です。");
                    $errInfoList[] = $errorInfo;

                } else {
                    $inputEndTime = mb_convert_kana($row[11], 'rnask', 'UTF-8');
                    if (!$this->isValidTime($inputEndTime)) {
                        $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「申込画面締め時間」のフォーマットが間違いました。HH:MM形式で指定してください。");
                        $errInfoList[] = $errorInfo;
                    }
                }

                // 確認画面締め時間 / confirm_end_time
                if (empty($row[12])) {
                    $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「確認画面締め時間」が必須です。");
                    $errInfoList[] = $errorInfo;

                } else {
                    $confirmEndTime = mb_convert_kana($row[12], 'rnask', 'UTF-8');
                    if (!$this->isValidTime($confirmEndTime)) {
                        $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「確認画面締め時間」のフォーマットが間違いました。HH:MM形式で指定してください。");
                        $errInfoList[] = $errorInfo;
                    }
                }

                // Check airport_flight_end_time when type = 1
                // 空港フライト時間判定用時間 / airport_flight_end_time
                if ($type == '1') {
                    if (empty($row[13])) {
                        $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「空港フライト時間判定用時間」が必須です。");
                        $errInfoList[] = $errorInfo;
    
                    } else {
                        $airportFlightEndTime = mb_convert_kana($row[13], 'rnask', 'UTF-8');
                        if (!$this->isValidTime($airportFlightEndTime)) {
                            $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「空港フライト時間判定用時間」のフォーマットが間違いました。HH:MM形式で指定してください。");
                            $errInfoList[] = $errorInfo;
                        }
                    }
                }

                if (empty($errInfoList)) {
                    $stDtArr = explode('/', $stDate);
                    $edDtArr = explode('/', $edDate);
                    if ($stDtArr[0] > $edDtArr[0]) {
                        $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「適用開始日」は「適用終了日」の以前を設定してください。");
                        $errInfoList[] = $errorInfo;
                    } else if ($stDtArr[0] == $edDtArr[0]) {
                        if ($stDtArr[1] > $edDtArr[1]) {
                            $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「適用開始日」は「適用終了日」の以前を設定してください。");
                            $errInfoList[] = $errorInfo;
                        } else if ($stDtArr[1] == $edDtArr[1]) {
                            if ($stDtArr[2] > $edDtArr[2]) {
                                $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $line . "行目の「適用開始日」は「適用終了日」の以前を設定してください。");
                                $errInfoList[] = $errorInfo;
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $errorInfo = array('key' => 'up_file', 'itemName' => '', 'errMsg' => $e->getMessage());
            $errInfoList[] = $errorInfo;
        }

        return $errInfoList;
    }

    private function isValidDate($strDate) {
        if (mb_strlen($strDate) != 10) {
            return false;
        } else if (!preg_match(self::PREG_MATCH_DATE_STRING_WITH_SLASH, $strDate)) {
            return false;
        } else {
            list($yyyy, $mm, $dd) = explode('/', $strDate);
            if (checkdate($mm, $dd, $yyyy)) {
                return true;
            } else {
                return false;
            }
        }
        // $isValidSlashDt = preg_match("/^((((19|[2-9]\d)\d{2})\/(0[13578]|1[02])\/(0[1-9]|[12]\d|3[01]))|(((19|[2-9]\d)\d{2})\/(0[13456789]|1[012])\/(0[1-9]|[12]\d|30))|(((19|[2-9]\d)\d{2})\/02\/(0[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))\/02\/29))$/", $strDate);
        // $isValidDashDt = preg_match("/^((((19|[2-9]\d)\d{2})\-(0[13578]|1[02])\-(0[1-9]|[12]\d|3[01]))|(((19|[2-9]\d)\d{2})\-(0[13456789]|1[012])\-(0[1-9]|[12]\d|30))|(((19|[2-9]\d)\d{2})\-02\-(0[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))\-02\-29))$/", $strDate);           
    }

    private function isValidTime($strTime) {
        if (mb_strlen($strTime) != 5) {
            return false;
        } else if (!preg_match(self::PREG_MATCH_TIME_STRING_WITH_COLON, $strTime)) {
            return false;
        } else {
            $arr = explode(':', $strTime);
            $hour = (int) $arr[0];
            $minute = (int) $arr[1];
            if (!in_array($hour, range(0, 23)) || !in_array($minute, range(0, 59))) {
                return false;
            }
        }
        return true;
    }


    
    function exportCSV($dataCsv) {
        $handle = fopen('php://memory', 'w+');
        $header = array(
            "発着地識別番号",
            "運営会社（備考）",
            "郵便番号",
            "住所",
            '区',
            "電話番号",
            "名称",
            "名称（英語）",
            "分類",
            "適用開始日",
            "適用終了日",
            "申込画面締め時間",
            "確認画面締め時間",
            "フライト時間判定",
        );
        fwrite($handle, '"' . implode('","', $header) . '"' . PHP_EOL);
        foreach ($dataCsv as $item) {
            $row = array(
                'hachakuten_shikibetu_cd' => $item['hachakuten_shikibetu_cd'],
                'note' => $item['note'],
                'zip' => substr($item['zip'],0,3). "-" .substr($item['zip'],3),
                'address' => $item['address'],
                '',
                'tel' => $item['tel'],
                'name_jp' => $item['name_jp'],
                'name_en' => $item['name_en'],
                'type' => $item['type'],
                'start_date' => str_replace('-','/',$item['start_date']),
                'end_date' => str_replace('-','/',$item['end_date']),
                'input_end_time' => substr($item['input_end_time'],0,2) . ":" . substr($item['input_end_time'],2),
                'confirm_end_time' => substr($item['confirm_end_time'],0,2) . ":" . substr($item['confirm_end_time'],2),
                'airport_flight_end_time' => !empty($item['airport_flight_end_time']) ? (substr($item['airport_flight_end_time'],0,2) . ":" . substr($item['airport_flight_end_time'],2)) : ""
            );
            
            fwrite($handle, '"' . implode('","', str_replace('"', '""', $row)) . '"' . PHP_EOL);
        }
        
        rewind($handle);
        $csv = str_replace(PHP_EOL, "\r\n", stream_get_contents($handle));
        $date = new DateTime();
        $filename = 'mlk_hachakuten_'.$date->format('YmdHis') . '.csv';

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $filename);
        echo mb_convert_encoding($csv, 'UTF-8', 'UTF-8');
        exit();
    }
}
