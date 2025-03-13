<?php
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useAllComponents();
Sgmov_Lib::useView('CommonConst');
Sgmov_Lib::useForms(array('Error', ));
Sgmov_Lib::useServices(array('CostcoShohin', 'CostcoOption'));

//if(!isset($_SESSION)) {
//     session_start();
//}

/**
 * Sgmov_View_Csc_MstShohin
 * @package    View
 * @subpackage CSC
 * @author     K.Sawada
 * @copyright  2021-2021 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Csc_MstShohin //extends Sgmov_View_Csc_Common
{
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $_CostcoShohinService;
    private $_CostcoOption;
    const FEATURE_ID = 'CSC';
    const LIMIT_ROW = 50;
    //CostcoShohin
    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct()
    {
        // FPT-AnNV6 clear sessions of create and edit screen
        $_SESSION["CSC"]["SUCCESS"] = 0;
        $_SESSION["CSC"]["ERROR_MST_SHOHIN_INFO"] = array();
        $_SESSION["CSC"]["INPUT_MST_SHOHIN"] = array();
        
        $this->_CostcoShohinService = new Sgmov_Service_CostcoShohin();
        $this->_CostcoOption = new Sgmov_Service_CostcoOption();
    }
    
    /**
     * 処理を実行します。
     */
    public function executeInner($request)
    {
        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();
        // セッションに情報があるかどうかを確認
        $session = Sgmov_Component_Session::get();
        if(@empty($_SERVER["HTTP_REFERER"]) || strpos($_SERVER["HTTP_REFERER"], "/csc/") == false) {
            // セッション情報を破棄
            $session->deleteForm(self::FEATURE_ID);
            $_SESSION["CSC"]["SHOHIN_SEARCH_LIST"] = array();
        }
        
        //flg_save
        if (isset($request['flg_del']) 
                || isset($request['pageNum']) 
                || isset($request['flg_save']) 
                || isset($request['flg_back'])
                || isset($request['flag_export_csv'])) {
            $request = @$_SESSION["CSC"]["SHOHIN_SEARCH_LIST"];
            unset($request['btnSearch']);
        } else {
            $_SESSION["CSC"]["SHOHIN_SEARCH_LIST"] = $request;
        }
        //flag_export_csv 
        if (isset($_GET['flag_export_csv'])) {
            $dataCSV = $shohinListAll = $this->_CostcoShohinService->getInfoShohinListAll($db, $request);
            
            if (empty($dataCSV)) {
                echo '<script>alert("出力対象がありません。");</script>';
            } else {
                if (count($dataCSV) > 10000) {
                    echo '<script>alert("10000件以下となるように検索条件を絞り込んでください。");</script>';
                } else {
                    $this->exportCSV($dataCSV);
                }
            }
        }
        
        
        $maxRows = self::LIMIT_ROW;
        $linenum = self::LIMIT_ROW;

        $pageNum = 0;
        if (isset($_GET['pageNum'])){
            //Startページ数 計算
            $pageNum = $_GET['pageNum'];
            //Endページ数 計算
            $EndRow = $linenum * ($_GET['pageNum']+1);
        }else{
            //Endページ数 計算
            $EndRow = $linenum;
        }
        $startRow = $pageNum * $maxRows;
        $isErrorSearch = 0; 
        //$isErrorSearch = false;
        if (isset($request['btnSearch']) && empty($request['date_valid'])) {
            //echo '<script>alert("有効日を入力してください。");</script>';
            $isErrorSearch = 1;
        }
        $isError = $this->checkErrorCondSearch($request, $isErrorSearch);
        if ($isError) {
            $shohinListAll = [];
            $shohinList = [];
        } else {
            $shohinListAll = $this->_CostcoShohinService->getInfoShohinListAll($db, $request);
            $shohinList = $this->_CostcoShohinService->getInfoShohinList($db, $maxRows, $startRow, $request);
        }
        
        $options = $this->getCostcoOptions();

        foreach ($shohinList as $idxShohin => $valShohin) {
            foreach($options as $idxOpt => $valOpt) {
                if ($valShohin['option_id'] == $idxOpt) {
                    $shohinList[$idxShohin]['option_nm'] = $valOpt;
                    break;
                }
            }
        }
        $totalRows = count($shohinListAll);
        //Endページ数 計算(例外処理)
        if(count($shohinList) != $linenum){
            $EndRow = count($shohinList) + $startRow;
        }

        if (isset($_GET['totalRows'])){
            //既に計算済み
            $totalRows = $_GET['totalRows'];
        }

        //引数で指定した数値から、次に大きい整数を返す
        $totalPages = ceil($totalRows/$maxRows)-1;
        return array(
            'status' => 'success',
            'message' => '初期情報処理に成功しました。',
            'res_data' => array(
                //'input_info_search' => $inputInfoSearch,
                'shohinList' => $shohinList, 
                'request' => $request,
                'options' => $options,
                'startRow' => $startRow,
                'totalRows' => $totalRows,
                'endRow' => $EndRow,
                'pageNum' => $pageNum,
                'linenum' => $linenum, 
                'totalPages' => $totalPages,
                'isErrorSearch' => $isErrorSearch
            )
        );
    }
    
    public function getCostcoOptions() {
        $returnOpts = array(
            '' => '全て',
            '9999' => '9999：その他'
        );
        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();
        $options = $this->_CostcoOption->getAll($db);
        if (!empty($options)) {
            foreach ($options as $val) {
                if (ctype_digit($val['option_type'])) {
                    $returnOpts[(int) $val['option_type']] = $val['option_type'] . '：' . $val['option_name'];
                }
            }
        }
        ksort($returnOpts);
        return $returnOpts;
    }
    public function deleteShohin($id) {
        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();
        $result = $this->_CostcoShohinService->deleteShohin($db, $id);
        return $result;
    }
    
    private function checkErrorCondSearch($request) {
        //check data type interger for ID
        if (isset($request['id']) && $request['id'] != '') {
            if (!is_numeric($request['id']) || (is_numeric($request['id']) && (int)$request['id'] != $request['id'])) {
                return true;
            }
        }
        //check data type interger for juryo_from 
        if (isset($request['juryo_from']) && $request['juryo_from'] != '') {
            if (!is_numeric($request['juryo_from']) || (is_numeric($request['juryo_from']) && (int)$request['juryo_from'] != $request['juryo_from'])) {
                return true;
            }
        }
        //check data type interger for juryo_to
        if (isset($request['juryo_to']) && $request['juryo_to'] != '') {
            if (!is_numeric($request['juryo_to']) || (is_numeric($request['juryo_to']) && (int)$request['juryo_to'] != $request['juryo_to'])) {
                return true;
            }
        }
        //check data type numeric for size_from
        if (isset($request['size_from']) && $request['size_from'] != '') {
            if (!is_numeric($request['size_from'])) {
                return true;
            }
        }
        //check data type numeric for size_to
        if (isset($request['size_to']) && $request['size_to'] != '') {
            if (!is_numeric($request['size_to'])) {
                return true;
            }
        }
        return false;
    }
    
    function exportCSV($dataCsv) {
        $handle = fopen('php://memory', 'w+');
        $header = array(
            "ID",
            "商品コード",
            "商品名",
            "サイズ",
            "オプションid",
            "データ種別",
            "重量",
            //"適用開始日",
            //"適用終了日",
            "梱包数"
        );
        fwrite($handle, '"' . implode('","', $header) . '"' . PHP_EOL);
        $options = $this->getCostcoOptions();
        foreach ($dataCsv as $row) {
            //unset created, modified, start_date, end_date
            unset($row['created']);
            unset($row['modified']);
            unset($row['start_date']);
            unset($row['end_date']);
            
            if ($row['data_type'] == '6') {
                $row['data_type'] = '6：D24でない';
            } else if ($row['data_type'] == '7') {
                $row['data_type'] = '7：D24';
            } else {
                $row['data_type'] = '';
            }
            foreach($options as $idxOpt => $valOpt) {
                if ($row['option_id'] == $idxOpt) {
                    $row['option_id'] = $valOpt;
                    break;
                }
            }
//            $start_date = new DateTime($row['start_date']);
//            $row['start_date']  = $start_date->format('Y/m/d');
//            
//            $end_date = new DateTime($row['end_date']);
//            $row['end_date']  = $end_date->format('Y/m/d');
            
            fwrite($handle, '"' . implode('","', str_replace('"', '""', $row)) . '"' . PHP_EOL);
        }
        
        rewind($handle);
        $csv = str_replace(PHP_EOL, "\r\n", stream_get_contents($handle));
        $date = new DateTime();
        //costco_shohin_yyyyMMddhhssmm.csv
        $filename = 'costco_shohin_'.$date->format('YmdHis') . '.csv';

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $filename);
        echo mb_convert_encoding($csv, 'SJIS-win', 'UTF-8');
        exit();
    }
}
