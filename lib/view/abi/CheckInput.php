<?php
/**
 * @package    ClassDefFile
 * @author     K.Sawada
 * @copyright  2009-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * アップロードmaxファイルサイズ以上のファイルがアップロードされたばあい、500エラーで、class内部ではハンドリングができないため
 * ここで処理する。
 */
function abi_check_input_error_handle() {
    $e = error_get_last();
    if ($e['type'] == E_ERROR ||
            $e['type'] == E_PARSE ||
            $e['type'] == E_CORE_ERROR ||
            $e['type'] == E_COMPILE_ERROR ||
            $e['type'] == E_USER_ERROR) {
        
        if (0 === strpos($e['message'], "Allowed memory size of")) {
            $_SESSION["Sgmov_View_Abi.inputErrorInfo"] = array(
                "error" => "アップロード可能なファイルサイズを超えております。",
            );
        } else {
            $_SESSION["Sgmov_View_Abi.inputErrorInfo"] = array(
                "error" => "予期せぬエラーが発生しました。",
            );
        }

        Sgmov_Component_Log::err($e);
        Sgmov_Component_Log::debug('リダイレクト /abi/input/');
        Sgmov_Component_Redirect::redirectMaintenance('/abi/input/');
        exit;
    }
}
register_shutdown_function("abi_check_input_error_handle");
/////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../Lib.php';
Sgmov_Lib::useView('abi/Common');
Sgmov_Lib::usePHPExcel();
Sgmov_Lib::useForms(array('Error', 'Abi001In'));


/**#@-*/

 /**
 * Excel一括取込Excelファイルチェック。 
 * @package    View
 * @subpackage ABI
 * @author     K.Sawada
 * @copyright  2009-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Abi_CheckInput extends Sgmov_View_Abi_Common
{
    
    /**
     * ツアー会社サービス
     * @var Sgmov_Service_TravelAgency
     */
    private $_TravelAgencyService;
    
    
    /**
     * ツアーサービス
     * @var type 
     */
    private $_TravelService;
    
    /**
     * ツアー発着地サービス
     * @var type 
     */
    private $_TravelTerminalService;
    
    /**
     * ツアーエリア情報サービス
     * @var type 
     */
    private $_TravelProvincesService;
    
    /**
     * ツアーエリア都道府県サービス
     * @var type 
     */
    private $_TravelProvincesPrefecturesService;
    
    /**
     * ツアー配送料金サービス
     * @var type 
     */
    private $_TravelDeliveryChargeService;
    
    /**
     * ツアー配送料金エリアサービス
     * @var type 
     */
    private $_TravelDeliveryChargeAreasService;
    
    /**
     * 都道府県サービス
     * @var type 
     */
    private $_PrefectureService;
    
    /**
     * postgressのinformation_schema サービス
     * @var type 
     */
    private $_InformationSchemaService;
    
    /**
     * コンストラクタでサービスを初期化します。
     */
    public function __construct() {
        $this->_TravelAgencyService = new Sgmov_Service_TravelAgency();
        $this->_TravelService = new Sgmov_Service_Travel();
        $this->_TravelTerminalService = new Sgmov_Service_TravelTerminal();
        $this->_TravelProvincesService = new Sgmov_Service_TravelProvinces();
        $this->_TravelProvincesPrefecturesService = new Sgmov_Service_TravelProvincesPrefectures();
        $this->_TravelDeliveryChargeService = new Sgmov_Service_TravelDeliveryCharge();
        $this->_TravelDeliveryChargeAreasService = new Sgmov_Service_TravelDeliveryChargeAreas();
        $this->_PrefectureService = new Sgmov_Service_Prefecture();
        $this->_InformationSchemaService  = new Sgmov_Service_InformationSchema();
        
        // 一括で登録 or 更新するため、トランザクション制御は本クラスで実施する
        $this->_TravelAgencyService->setTrnsactionFlg(FALSE);
        $this->_TravelService->setTrnsactionFlg(FALSE);
        $this->_TravelTerminalService->setTrnsactionFlg(FALSE);
        $this->_TravelProvincesService->setTrnsactionFlg(FALSE);
        $this->_TravelProvincesPrefecturesService->setTrnsactionFlg(FALSE);
        $this->_TravelDeliveryChargeService->setTrnsactionFlg(FALSE);
        $this->_TravelDeliveryChargeAreasService->setTrnsactionFlg(FALSE);
    }
    
    /**
     * 処理を実行します。
     * <ol><li>
     * チケットの確認と破棄
     * </li><li>
     * 情報をセッションに保存
     * </li><li>
     * 入力チェック
     * </li><li>
     * 入力エラー無し
     *   <ol><li>
     *   pin/confirm へリダイレクト
     *   </li></ol>
     * </li><li>
     * 入力エラー有り
     *   <ol><li>
     *   pin/input へリダイレクト
     *   </li></ol>
     * </li></ol>
     */
    public function executeInner()
    {
        
        // max_post_size越えのデータを受信した場合の対策
        if(count($_POST) == 0 && count($_FILES) == 0) {
            $_SESSION["Sgmov_View_Abi.inputErrorInfo"] = array("max_post_size_over_err" => "・アップロード可能なファイルサイズを超えております。");
            Sgmov_Component_Log::debug('リダイレクト /abi/input/');
            Sgmov_Component_Redirect::redirectMaintenance('/abi/input/');
            exit;
        }
        
        Sgmov_Component_Log::debug('チケットの確認と破棄');
        $session = Sgmov_Component_Session::get();
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_ABI001, $this->_getTicket());
        Sgmov_Component_Log::debug('情報を取得');
        try {
            $inForm = $this->_createInFormFromPost($_FILES);
        } catch(Exception $e) {
            $code = $e->getCode();
            if($code == 119) { // エクセル拡張子エラー, 読込可能シート(名)がなかった, アップロード失敗
                $message = $e->getMessage();
                $errorForm = new Sgmov_Form_Error();
                $errorForm->addError("E{$code}", $message);
                $_SESSION["Sgmov_View_Abi.inputErrorInfo"] = $errorForm->_errors;
                
                Sgmov_Component_Log::err('エクセル拡張子エラー, 読込可能シート(名)がなかった, アップロード失敗');
                Sgmov_Component_Log::err($e);
                
                Sgmov_Component_Log::debug('リダイレクト /abi/input/');
                Sgmov_Component_Redirect::redirectMaintenance('/abi/input/');
                exit;
            }
            throw $e;
        }
        Sgmov_Component_Log::debug('入力チェック');
        $errorForm = $this->_validate($inForm);
        if ($errorForm->hasError()) {
            $_SESSION["Sgmov_View_Abi.inputErrorInfo"] = $errorForm->_errors;
            
            Sgmov_Component_Log::debug('リダイレクト /abi/input/');
            Sgmov_Component_Redirect::redirectMaintenance('/abi/input/');
            exit;
        }

        // シングルトンなので、どこで$dbを取得してもインスタンスは同じ
        $db = Sgmov_Component_DB::getAdmin();
        $db->begin();

        try {
            foreach($inForm->travel_agency as $data) {
                $this->_updateTravelAgency($data);
            }
            foreach($inForm->travel as $data) {
                $this->_updateTravel($data);
            }
            foreach($inForm->travel_terminal as $data) {
                $this->_updateTravelTerminal($data);
            }
            foreach($inForm->travel_provinces as $data) {
                $this->_updateTravelProvinces($data);
            }
            foreach($inForm->travel_provinces_prefectures as $data) {
                $this->_updateTravelProvincesPrefectures($data);
            }
            foreach($inForm->travel_delivery_charge as $data) {
                $this->_updateTravelDeliveryCharge($data);
            }
            foreach($inForm->travel_delivery_charge_areas as $data) {
                $this->_updateTravelDeliveryChargeAreas($data);
            }
        } catch(Exception $e) {
            $db->rollback();
            $_SESSION["Sgmov_View_Abi.inputErrorInfo"] = array("db_update_error"=> "・データベースの登録・更新に失敗しました。");

            Sgmov_Component_Log::debug('リダイレクト /abi/input/');
            Sgmov_Component_Log::err('データベースの登録・更新に失敗しました。');
            Sgmov_Component_Log::err($e);
            Sgmov_Component_Redirect::redirectMaintenance('/abi/input/');
            exit;
        }
        $db->commit();
        
Sgmov_Component_Log::debug("##############finish");
Sgmov_Component_Log::debug($inForm);
Sgmov_Component_Log::debug($errorForm);

        $session->deleteForm($this->getFeatureId());
        Sgmov_Component_Log::debug('リダイレクト /abi/complete/');
        Sgmov_Component_Redirect::redirectMaintenance('/abi/complete/');
        
        exit;
    }
    
    /**
     * POST値からチケットを取得します。
     * チケットが存在しない場合は空文字列を返します。
     * @return string チケット文字列
     */
    public function _getTicket()
    {
        if (!isset($_POST['ticket'])) {
            $ticket = '';
        } else {
            $ticket = $_POST['ticket'];
        }
        return $ticket;
    }

    /**
     * POST情報から入力フォームを生成します。
     * @param array $files アップロード情報
     * @return Sgmov_Form_Abi001In 入力フォーム
     */
    public function _createInFormFromPost($files)
    {
        $inForm = new Sgmov_Form_Abi001In();

        //一時ファイルができているか（アップロードされているか）チェック
        if (is_uploaded_file($files['up_file']['tmp_name'])) {
            //Excel読み込み
            $fileExtension = pathinfo($files['up_file']['name'], PATHINFO_EXTENSION);
            switch ($fileExtension) {
                case 'xls':
                    $readerType = 'Excel5';
                    break;
                case 'xlsx':
                    $readerType = 'Excel2007';
                    break;
                default:
                    throw new Exception('・ＥＸＣＥＬファイルの拡張子ではありません（xls,xlsx）', 119);
            }
            
            $objReader = PHPExcel_IOFactory::createReader($readerType);
            $book = $objReader->load($files['up_file']['tmp_name']);
            $sheetsCount = $book->getSheetCount();
            
            $columnErrInfoList = array();
            $sheetNameList = array(
                    "travel_agency",
                    "travel",
                    "travel_terminal",
                    "travel_provinces",
                    "travel_provinces_prefectures",
                    "travel_delivery_charge",
                    "travel_delivery_charge_areas",
                    );
            for($i=0; $i<$sheetsCount; $i++) {
                $book->setActiveSheetIndex($i);
                $sheet = $book->getActiveSheet();
                $title = $sheet->getTitle();
                if(!in_array($title, $sheetNameList)) {
                    continue;
                }
                $resultInfo = $this->_getExcelSheetData($sheet);
                $errInfoList = $this->_checkColumns($title, $resultInfo["headerArray"]);
                $columnErrInfoList = array_merge($columnErrInfoList, $errInfoList);
                if(!empty($errInfoList)) {
                    continue;
                }
                $inForm->headerInfo[$title] = $resultInfo["headerArray"];
                $inForm->$title = $resultInfo["dataList"];
            }
            if(!empty($columnErrInfoList)) {
                $columnErrInfoListSt = implode("<br/>", $columnErrInfoList);    
                throw new Exception($columnErrInfoListSt, 119);
            }
            
            if(empty($inForm->headerInfo)) {
                throw new Exception('・取込可能なシートが見つかりませんでした。', 119);
            }
        } else {
            throw new Exception('・アップロードに失敗しました。', 119);
        }
        return $inForm;
    }
    
    /**
     * 
     * @param type $tableName
     * @param type $targetHeader
     * @throws Exception
     */
    private function _checkColumns($tableName, $targetHeader) {
        
        // シングルトンなので、どこで$dbを取得してもインスタンスは同じ
        $db = Sgmov_Component_DB::getAdmin();
        
        $tableColumns = $this->_InformationSchemaService->getColumnNames($db, array("table_name" => $tableName));
        $resultList = array();
        foreach($tableColumns as $key => $tcolumn) {
            
            if($tableName == "travel" && $tcolumn=="repeater_discount") {
                $tcolumn = "rep_tar_flg";
            }
            
            if(!in_array($tcolumn, $targetHeader)) {
                $resultList[] = "・[{$tableName}] シートに [{$tcolumn}] 列がありません。";
            }
        }
        return $resultList;
    }
    
    /**
     * 
     * @param type $sheet
     * @return type
     */
    private function _getExcelSheetData($sheet) {
        
            $headerArray = array();
            $col = 0;
            while(true){
                $objCell = $sheet->getCellByColumnAndRow($col, 1); //col,rowの並び
                $cellText =  $this->_getText($objCell);
                if(empty($cellText)) {
                    break;
                }
                $headerArray[] = $cellText;
                $col++;
            }
            $rowCount = 0;
            $dataList = array();
            while(true) {
                $dataArray = array();
                for($j = 0; $j < count($headerArray); $j++){
                    $objCell = $sheet->getCellByColumnAndRow($j, ($rowCount+2)); //col,rowの並び
                    $dataArray[$headerArray[$j]] = $this->_getText($objCell);
                }

                $judge = array_filter($dataArray);
                if(empty($judge)) {
                    break;
                }
                $dataList[] = $dataArray;
                $rowCount++;
            }
            
            return array(
                "headerArray" => $headerArray,
                "dataList" => $dataList,
            );
    }
    
     /**
      * 指定したセルの文字列を取得する
      *
      * 色づけされたセルなどは cell->getValue()で文字列のみが取得できない
      * また、複数の配列に文字列データが分割されてしまうので、その部分も連結して返す
      *
      *
      * @param $objCell Cellオブジェクト
      */ 
      private function _getText($objCell = null) {
          if (is_null($objCell)) {
              return false;
          }
          $txtCell = "";
          //まずはgetValue()を実行
          $valueCell = $objCell->getValue();
          if (is_object($valueCell)) {
              //オブジェクトが返ってきたら、リッチテキスト要素を取得
              $rtfCell = $valueCell->getRichTextElements();
              //配列で返ってくるので、そこからさらに文字列を抽出
              $txtParts = array();
              foreach ($rtfCell as $v) {
                  $txtParts[] = $v->getText();
              }
              //連結する
              $txtCell = implode("", $txtParts);
          } else {
              if (!empty($valueCell)) {
                  $txtCell = $valueCell;
              }
          }
          return $txtCell;
      }

    /**
     * 入力値の妥当性検査を行います。
     * @param Sgmov_Form_AcfSession $sessionForm セッションフォーム
     * @return Sgmov_Form_Error エラーフォームを返します。
     */
    public function _validate($inForm)
    {
        // 入力チェック
        $errorForm = new Sgmov_Form_Error();
        
        $db = Sgmov_Component_DB::getAdmin();
        
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // travel_agency
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        foreach($inForm->travel_agency as $row => $data) {
            $lineNum = $row+2;
            
            // ツアー会社ID
            $v = Sgmov_Component_Validator::createSingleValueValidator($data["id"])->
                    isInteger()->
                    isWebSystemNg();
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_agency_cd_{$row}", "[travel_agency] シートの [{$lineNum}] 行目の [id] 列" . $msgTop);
            }
            
            // ツアー会社コード
            $v = Sgmov_Component_Validator::createSingleValueValidator($data["cd"])->
                    isNotEmpty()->
                    isInteger()->
                    isLengthLessThanOrEqualTo(10)->
                    isWebSystemNg();
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_agency_cd_{$row}", "[travel_agency] シートの [{$lineNum}] 行目の [cd] 列" . $msgTop);
            }

            // 船名
            $v = Sgmov_Component_Validator::createSingleValueValidator($data["name"])->
                    isNotEmpty()->
                    isLengthLessThanOrEqualTo(60)->
                    isWebSystemNg();
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_agency_name_{$row}", "[travel_agency] シートの [{$lineNum}] 行目の [name] 列" . $msgTop);
            }
        }
        
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // travel
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        foreach($inForm->travel as $row => $data) {
            $lineNum = $row+2;
            
            // ツアーID
            $v = Sgmov_Component_Validator::createSingleValueValidator($data["id"])->
                    isInteger()->
                    isWebSystemNg();
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_id_{$row}", "[travel] シートの [{$lineNum}] 行目の [id] 列" . $msgTop);
            }
            
            // ツアーコード
            $v = Sgmov_Component_Validator::createSingleValueValidator("" . $data["cd"] . "")->
                    isNotEmpty()->
                    isInteger()->
                    isLengthLessThanOrEqualTo(4)->
                    isWebSystemNg();
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_cd_{$row}", "[travel] シートの [{$lineNum}] 行目の [cd] 列" . $msgTop);
            }

            // 乗船日名
            $v = Sgmov_Component_Validator::createSingleValueValidator($data["name"])->
                    isNotEmpty()->
                    isLengthLessThanOrEqualTo(60)->
                    isWebSystemNg();
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_name_{$row}", "[travel] シートの [{$lineNum}] 行目の [name] 列" . $msgTop);
            }

            // 船名
            $v = Sgmov_Component_Validator::createSingleValueValidator($data["travel_agency_id"])->
                    isInteger();
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_travel_agency_id_{$row}", "[travel] シートの [{$lineNum}] 行目の [travel_agency_id] 列" . $msgTop);
            }

            // 往復便割引
            $v = Sgmov_Component_Validator::createSingleValueValidator($data["round_trip_discount"])->
                    isNotEmpty()->
                    isInteger()->
                    isLengthLessThanOrEqualTo(6)->
                    isWebSystemNg();
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_round_trip_discount_{$row}", "[travel] シートの [{$lineNum}] 行目の [round_trip_discount] 列" . $msgTop);
            }

            // リピータ割引(フラグ)
            if(!empty($data["rep_tar_flg"])) {
                $v = Sgmov_Component_Validator::createSingleValueValidator($data["rep_tar_flg"])->
                        isInteger()->
                        isLengthLessThanOrEqualTo(1)->
                        isWebSystemNg();
                if (!$v->isValid()) {
                    $msgTop = $v->getResultMessageTop();
                    $errorForm->addError("top_repeater_discount_{$row}", "[travel] シートの [{$lineNum}] 行目の [rep_tar_flg] 列" . $msgTop);
                } else {
                    if($data["rep_tar_flg"] != "" && $data["rep_tar_flg"] != "1") {
                        $msgTop = "には、空文字もしくは、「1」を入力してください。";
                        $errorForm->addError("top_repeater_discount_{$row}", "[travel] シートの [{$lineNum}] 行目の [rep_tar_flg] 列" . $msgTop);
                    }
                }
            }

            $date = new DateTime('2015/03/08');
            $min = intval($date->format('U'));

            // 乗船日
            $embarkation_date = self::_formatDate($data["embarkation_date"]);
            $v = Sgmov_Component_Validator::createDateValidator(
                    $embarkation_date[1],
                    $embarkation_date[2],
                    $embarkation_date[3])->
                    isNotEmpty()->
                    isDate($min);
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_embarkation_date_{$row}", "[travel] シートの [{$lineNum}] 行目の [embarkation_date] 列" . $msgTop);
            }

            // 掲載開始日
            $publish_begin_date = self::_formatDate($data["publish_begin_date"]);
            $v = Sgmov_Component_Validator::createDateValidator(
                    $publish_begin_date[1],
                    $publish_begin_date[2],
                    $publish_begin_date[3])->
                    isNotEmpty()->
                    isNotEmpty()->
                    isDate($min);
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_publish_begin_date_{$row}", "[travel] シートの [{$lineNum}] 行目の [publish_begin_date] 列" . $msgTop);
            }
        }
        
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // travel_terminal
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $prefectures  = $this->_PrefectureService->fetchPrefectures($db);

        foreach($inForm->travel_terminal as $row => $data) {
            $lineNum = $row+2;
            
            // ツアー発着地ID
            $v = Sgmov_Component_Validator::createSingleValueValidator($data["id"])->
                    isInteger()->
                    isWebSystemNg();
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_terminal_id_{$row}", "[travel_terminal] シートの [{$lineNum}] 行目の [id] 列" . $msgTop);
            }

            // 乗船日名
            $v = Sgmov_Component_Validator::createSingleValueValidator($data["travel_id"])->
                    isInteger()->
                    isNotEmpty();
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_terminal_travel_id_{$row}", "[travel_terminal] シートの [{$lineNum}] 行目の [travel_id] 列" . $msgTop);
            }

            // ツアー発着地コード
            $v = Sgmov_Component_Validator::createSingleValueValidator($data["cd"])->
                    isNotEmpty()->
                    isInteger()->
                    isLengthLessThanOrEqualTo(6)->
                    isWebSystemNg();
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_terminal_cd_{$row}", "[travel_terminal] シートの [{$lineNum}] 行目の [cd] 列" . $msgTop);
            }

            // ツアー発着地名
            $v = Sgmov_Component_Validator::createSingleValueValidator($data["name"])->
                    isNotEmpty()->
                    isLengthLessThanOrEqualTo(30)->
                    isWebSystemNg();
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_terminal_name_{$row}", "[travel_terminal] シートの [{$lineNum}] 行目の [name] 列" . $msgTop);
            }

            // 郵便番号
            $zipV = NULL;
            if(!empty($data["zip"]) || $data["zip"] == "0") {
                $inputErrFlg = FALSE;
                if(mb_strlen($data["zip"]) != 7 ) {
                    $inputErrFlg = TRUE;
                } else {
                    $zip1 = mb_substr($data["zip"], 0, 3);
                    $zip2 = mb_substr($data["zip"], 3, 4);
                    $zipV = Sgmov_Component_Validator::createZipValidator($zip1, $zip2)->
                            //isNotEmpty()->
                            isZipCode();
                }
                if ($inputErrFlg || !$zipV->isValid()) {
                    $msgTop = $v->getResultMessageTop();
                    $errorForm->addError("top_travel_terminal_zip_{$row}", "[travel_terminal] シートの [{$lineNum}] 行目の [zip] 列" . $msgTop);
                }
            }

            // 都道府県
            $v = Sgmov_Component_Validator::createSingleValueValidator($data["pref_id"])->
                    //isSelected()->
                    isIn((array)$prefectures['ids']);
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_terminal_pref_cd_sel_{$row}", "[travel_terminal] シートの [{$lineNum}] 行目の [pref_id] 列" . $msgTop);
            }

            // 市区町村
            $v = Sgmov_Component_Validator::createSingleValueValidator($data["address"])->
                    //isNotEmpty()->
                    isLengthLessThanOrEqualTo(40)->
                    isWebSystemNg();
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_terminal_address_{$row}", "[travel_terminal] シートの [{$lineNum}] 行目の [address] 列" . $msgTop);
            }

            // 番地・建物名
            $v = Sgmov_Component_Validator::createSingleValueValidator($data["building"])->
                    //isNotEmpty()->
                    isLengthLessThanOrEqualTo(80)->
                    isWebSystemNg();
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_terminal_building_{$row}", "[travel_terminal] シートの [{$lineNum}] 行目の [building] 列" . $msgTop);
            }

            // 発着店名(営業所名)
            $v = Sgmov_Component_Validator::createSingleValueValidator($data["store_name"])->
                    //isNotEmpty()->
                    isLengthLessThanOrEqualTo(80)->
                    isWebSystemNg();
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_terminal_store_name_{$row}", "[travel_terminal] シートの [{$lineNum}] 行目の [store_name] 列" . $msgTop);
            }

            // 電話番号
            $v = Sgmov_Component_Validator::createSingleValueValidator($data["tel"])->
                    //isNotEmpty()->
                    isPhone1()->
                    isWebSystemNg();
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_terminal_tel_{$row}", "[travel_terminal] シートの [{$lineNum}] 行目の [tel] 列" . $msgTop);
            }

            // 発着区分
            /**
             * 集荷の往復コード選択値
             * @var array
             */
            $terminal_lbls = array(
                1 => '出発地の選択肢に表示する',
                2 => '到着地の選択肢に表示する ',
                3 => '出発地・到着地の両方の選択肢に表示する ',
            );
            $v = Sgmov_Component_Validator::createSingleValueValidator(strval($data["terminal_cd"]))->
//                    isSelected()->
                    isIn(array_keys($terminal_lbls));
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_terminal_terminal_cd_{$row}", "[travel_terminal] シートの [{$lineNum}] 行目の [terminal_cd] 列" . $msgTop);
            }

            $date = new DateTime('2015/03/08');
            $min = intval($date->format('U'));

            // 出発日
            $departure_date = self::_formatDate($data["departure_date"]);
            $v = Sgmov_Component_Validator::createDateValidator(
                    $departure_date[1],
                    $departure_date[2],
                    $departure_date[3])->
                    //isNotEmpty()->
                    isDate($min);
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_terminal_departure_date_{$row}", "[travel_terminal] シートの [{$lineNum}] 行目の [departure_date] 列" . $msgTop);
            }

            // 出発時刻
            $departure_time = self::_formatTime($data["departure_time"]);
            $v = Sgmov_Component_Validator::createTimeValidator(
                    $departure_time[1],
                    $departure_time[2],
                    $departure_time[3])->
                    isTime();
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_terminal_departure_time_{$row}", "[travel_terminal] シートの [{$lineNum}] 行目の [departure_time] 列" . $msgTop);
            }

            // 到着日
            $arrival_date = self::_formatDate($data["arrival_date"]);
            $v = Sgmov_Component_Validator::createDateValidator(
                    $arrival_date[1],
                    $arrival_date[2],
                    $arrival_date[3])->
                    //isNotEmpty()->
                    isDate($min);
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_terminal_arrival_date_{$row}", "[travel_terminal] シートの [{$lineNum}] 行目の [arrival_date] 列" . $msgTop);
            }

            // 到着時刻
            $arrival_time = self::_formatTime($data["arrival_time"]);
            $v = Sgmov_Component_Validator::createTimeValidator(
                    $arrival_time[1],
                    $arrival_time[2],
                    $arrival_time[3])->
                    isTime();
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_terminal_arrival_time_{$row}", "[travel_terminal] シートの [{$lineNum}] 行目の [arrival_time] 列" . $msgTop);
            }

            // 往路 顧客コード
            $v = Sgmov_Component_Validator::createSingleValueValidator($data["departure_client_cd"])->
                    //isNotEmpty()->
                    isInteger()->
                    isLengthLessThanOrEqualTo(8)->
                    isWebSystemNg();
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_terminal_departure_client_cd_{$row}", "[travel_terminal] シートの [{$lineNum}] 行目の [departure_client_cd] 列" . $msgTop);
            }

            // 往路 顧客コード枝番
            $v = Sgmov_Component_Validator::createSingleValueValidator($data["departure_client_branch_cd"])->
                    //isNotEmpty()->
                    isInteger()->
                    isLengthLessThanOrEqualTo(3)->
                    isWebSystemNg();
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_terminal_departure_client_branch_cd_{$row}", "[travel_terminal] シートの [{$lineNum}] 行目の [departure_client_branch_cd] 列" . $msgTop);
            }

            // 復路 顧客コード
            $v = Sgmov_Component_Validator::createSingleValueValidator($data["arrival_client_cd"])->
                    //isNotEmpty()->
                    isInteger()->
                    isLengthLessThanOrEqualTo(8)->
                    isWebSystemNg();
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_terminal_arrival_client_cd_{$row}", "[travel_terminal] シートの [{$lineNum}] 行目の [arrival_client_cd] 列" . $msgTop);
            }

            // 復路 顧客コード枝番
            $v = Sgmov_Component_Validator::createSingleValueValidator($data["arrival_client_branch_cd"])->
                    //isNotEmpty()->
                    isInteger()->
                    isLengthLessThanOrEqualTo(3)->
                    isWebSystemNg();
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_terminal_arrival_client_branch_cd_{$row}", "[travel_terminal] シートの [{$lineNum}] 行目の [arrival_client_branch_cd] 列" . $msgTop);
            }

            // エラーがない場合は郵便番号存在チェック
            if (!$errorForm->hasError()) {
                $zipV->zipCodeExist()->zipCodeCollectable();
                if (!$zipV->isValid()) {
                    $msgTop = $v->getResultMessageTop();
                    $errorForm->addError("top_travel_terminal_zip_{$row}", "[travel_terminal] シートの [{$lineNum}] 行目の [zip] 列" . $msgTop);
                }
            }
        }
        
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // travel_provinces
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        foreach($inForm->travel_provinces as $row => $data) {
            $lineNum = $row+2;
            
            // ツアーエリアID
            $v = Sgmov_Component_Validator::createSingleValueValidator($data["id"])->
                    isInteger()->
                    isWebSystemNg();
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_provinces_id_{$row}", "[travel_provinces] シートの [{$lineNum}] 行目の [id] 列" . $msgTop);
            }
            
            // ツアーエリアコード
            $v = Sgmov_Component_Validator::createSingleValueValidator($data["cd"])->
                    isNotEmpty()->
                    isInteger()->
                    isLengthLessThanOrEqualTo(3)->
                    isWebSystemNg();
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_province_cd_{$row}", "[travel_provinces] シートの [{$lineNum}] 行目の [cd] 列" . $msgTop);
            }

            // ツアーエリア名
            $v = Sgmov_Component_Validator::createSingleValueValidator($data["name"])->
                    isNotEmpty()->
                    isLengthLessThanOrEqualTo(20)->
                    isWebSystemNg();
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_province_name_{$row}", "[travel_provinces] シートの [{$lineNum}] 行目の [name] 列" . $msgTop);
            }
        }
        
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // travel_provinces_prefectures
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        foreach($inForm->travel_provinces_prefectures as $row => $data) {
            $lineNum = $row+2;
            
            $v = Sgmov_Component_Validator::createSingleValueValidator("" . $data["provinces_id"] . "")->
                    isNotEmpty()->
                    isInteger();
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_provinces_prefectures_provinces_id_{$row}", "[travel_provinces_prefectures] シートの [{$lineNum}] 行目の [provinces_id] 列" . $msgTop);
            }
            
            // 都道府県
            $v = Sgmov_Component_Validator::createSingleValueValidator("" . $data["prefecture_id"] . "")->
                    isNotEmpty()->
                    isInteger()->
                    isIn((array)$prefectures['ids']);
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_provinces_prefectures_prefecture_id_{$row}", "[travel_provinces_prefectures] シートの [{$lineNum}] 行目の [prefecture_id] 列" . $msgTop);
            }
        }
        
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // travel_delivery_charge
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        foreach($inForm->travel_delivery_charge as $row => $data) {
            $lineNum = $row+2;
            
            // ツアー配送料金ID
            $v = Sgmov_Component_Validator::createSingleValueValidator($data["id"])->
                    isInteger()->
                    isWebSystemNg();
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_delivery_charge_id_{$row}", "[travel_delivery_charge] シートの [{$lineNum}] 行目の [id] 列" . $msgTop);
            }
            
            // ツアー発着地
            $v = Sgmov_Component_Validator::createSingleValueValidator($data["travel_terminal_id"])->
                isInteger();
            if (!$v->isValid() ) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_delivery_charge_travel_terminal_id_{$row}", "[travel_delivery_charge] シートの [{$lineNum}] 行目の [travel_terminal_id] 列" . $msgTop);
            }
        }
        
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // travel_delivery_charge_areas
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        foreach($inForm->travel_delivery_charge_areas as $row => $data) {
            $lineNum = $row+2;
            
            // ツアー配送料金ID
            $v = Sgmov_Component_Validator::createSingleValueValidator($data["travel_delivery_charge_id"])->
                    isNotEmpty()->
                    isInteger()->
                    isWebSystemNg();
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_delivery_charge_areas_travel_delivery_charge_id_{$row}", "[travel_delivery_charge_areas] シートの [{$lineNum}] 行目の [travel_delivery_charge_id] 列" . $msgTop);
            }
            
            // エリアID
            $v = Sgmov_Component_Validator::createSingleValueValidator($data["travel_areas_provinces_id"])->
                    isNotEmpty()->
                    isInteger()->
                    isWebSystemNg();
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_delivery_charge_areas_travel_areas_provinces_id_{$row}", "[travel_delivery_charge_areas] シートの [{$lineNum}] 行目の [travel_areas_provinces_id] 列" . $msgTop);
            }
            
            // ツアー配送料金
            $v = Sgmov_Component_Validator::createSingleValueValidator("" . $data["delivery_charg"] . "")->
                //isNotEmpty()->
                isInteger(0)->
                isLengthLessThanOrEqualTo(6)->
                isWebSystemNg();
            if (!$v->isValid()) {
                $msgTop = $v->getResultMessageTop();
                $errorForm->addError("top_travel_delivery_charge_areas_delivery_charg_{$row}", "[travel_delivery_charge_areas] シートの [{$lineNum}] 行目の [delivery_charg] 列" . $msgTop);
            }
        }
        
        return $errorForm;
    }
    
    /**
     * 日付整形
     * 
     * @param $s string 日付文字列
     * @return array 日付配列
     */
    private static function _formatDate($s) {

        $matches = array();

        if (empty($s)) {
            return array(
                1 => '',
                2 => '',
                3 => '',
            );
        }

        // 全角数字を半角に変換する
        $s = mb_convert_kana($s, 'n', 'UTF-8');

        // 日付文字列かチェックする
        if (preg_match('{^\D*(\d{4})\D+(\d{1,2})\D+(\d{1,2})\D*$}u', $s, $matches) === 1
            || preg_match('{^\D*(\d{4})(\d{2})(\d{2})\D*$}u', $s, $matches) === 1
        ) {
            return $matches;
        //} elseif (preg_match('{^\D*(\d{4})\D+(\d{1,2})\D*$}u', $s, $matches) === 1
        //    || preg_match('{^\D*(\d{4})(\d{2})\D*$}u', $s, $matches) === 1
        //) {
        //    return $matches;
        //} elseif (preg_match('{^\D*(\d{4})\D*$}u', $s, $matches) === 1) {
        //    return $matches;
        }
        // 日付ではない場合
        return array(
            1 => '',
            2 => '',
            3 => '',
        );
    }
    
    /**
     * 時刻整形
     * 
     * @param $s string 時刻文字列
     * @return array 時刻配列
     */
    private static function _formatTime($s) {

        $matches = array();

        if (empty($s)) {
            return array(
                1 => '',
                2 => '',
                3 => '',
            );
        }

        // 全角数字を半角に変換する
        $s = mb_convert_kana($s, 'n', 'UTF-8');

        // 時刻文字列かチェックする
        if (preg_match('{^\D*(\d{1,2})\D+(\d{1,2})\D+(\d{1,2})\D*$}u', $s, $matches) === 1
            || preg_match('{^\D*(\d{2})(\d{2})(\d{2})\D*$}u', $s, $matches) === 1
        ) {
            if (strlen($matches[2]) === 1) {
                $matches[2] = '0' . $matches[2];
            }
            if (strlen($matches[3]) === 1) {
                $matches[3] = '0' . $matches[3];
            }
            return $matches;
        } elseif (preg_match('{^\D*(\d{1,2})\D+(\d{1,2})\D*$}u', $s, $matches) === 1
            || preg_match('{^\D*(\d{2})(\d{2})\D*$}u', $s, $matches) === 1
        ) {
            $matches[3] = '00';
            return $matches;
        } elseif (preg_match('{^\D*(\d{1,2})\D*$}u', $s, $matches) === 1) {
            $matches[2] = '00';
            $matches[3] = '00';
            return $matches;
        }
        // 時刻ではない場合
        return array(
            1 => '',
            2 => '',
            3 => '',
        );
    }
    
    /**
     * ツアー会社マスタ登録／更新.
     * @param type $data
     * @return \Sgmov_Form_Error
     */
    private function _updateTravelAgency($data) {

        // DBへ接続
        $db = Sgmov_Component_DB::getAdmin();

        $travelAgencyInfo = array();
        if(!empty($data["id"])) {
            $travelAgencyInfo = $this->_TravelAgencyService->fetchTravelAgencyLimit($db, $data);
        }

        // 情報をDBへ格納
        if (!empty($travelAgencyInfo)) {

            $data2 = array(
                'id'   => $data["id"],
                'cd'   => $data["cd"],
                'name' => $data["name"],
            );
            $ret = $this->_TravelAgencyService->_updateTravelAgency($db, $data2);
        } else {

            //登録用IDを取得
            if(empty($data["id"])) {
                $data["id"] = $this->_TravelAgencyService->select_id($db);
            }
            $data2 = array(
                'id'   => $data["id"],
                'cd'   => $data["cd"],
                'name' => $data["name"],
            );
            $ret = $this->_TravelAgencyService->_insertTravelAgency($db, $data2);
        }

        $errorForm = new Sgmov_Form_Error();
        if ($ret === false) {
            throw new Exception();
        }
        return $errorForm;
    }
    
    /**
     * ツアーマスタ登録／更新.
     * @param type $data
     * @return \Sgmov_Form_Error
     */
    private function _updateTravel($data) {
        // DBへ接続
        $db = Sgmov_Component_DB::getAdmin();
        $travelInfo = array();
        if(!empty($data["id"])) {
            $travelInfo = $this->_TravelService->fetchTravelLimit($db, $data);
        }
        // 情報をDBへ格納
        if (!empty($travelInfo)) {
            $data2 = array(
                'id'                  => $data["id"],
                'cd'                  => $data["cd"],
                'name'                => $data["name"],
                'travel_agency_id'    => $data["travel_agency_id"],
                'round_trip_discount' => $data["round_trip_discount"],
                'repeater_discount'   => empty($data["rep_tar_flg"]) ? "0" : "100",
                'embarkation_date'    => $data["embarkation_date"],
                'publish_begin_date'  => $data["publish_begin_date"],
            );
            $ret = $this->_TravelService->_updateTravel($db, $data2);
        } else {
            //登録用IDを取得
            if(empty($data["id"])) {
                $data["id"] = $this->_TravelService->select_id($db);
            }
            $data2 = array(
                'id'                  => $data["id"],
                'cd'                  => $data["cd"],
                'name'                => $data["name"],
                'travel_agency_id'    => $data["travel_agency_id"],
                'round_trip_discount' => $data["round_trip_discount"],
                'repeater_discount'   => empty($data["rep_tar_flg"]) ? "0" : "100",
                'embarkation_date'    => $data["embarkation_date"],
                'publish_begin_date'  => $data["publish_begin_date"],
            );
            $ret = $this->_TravelService->_insertTravel($db, $data2);
        }

        $errorForm = new Sgmov_Form_Error();
        if ($ret === false) {
            throw new Exception();
        }
        return $errorForm;
    }
    
    /**
     * ツアー発着地マスタ登録／更新.
     * @param type $data
     */
    private function _updateTravelTerminal($data) {
        // DBへ接続
        $db = Sgmov_Component_DB::getAdmin();
        $travelTerminalInfo = array();
        if(!empty($data["id"])) {
            $travelTerminalInfo = $this->_TravelTerminalService->fetchTravelTerminalLimit($db, $data);
        }
        
        if (!empty($travelTerminalInfo)) {
            $data = array(
                'id'                         => $data["id"],
                'travel_id'                  => $data["travel_id"],
                'cd'                         => $data["cd"],
                'name'                       => $data["name"],
                'zip'                        => $data["zip"],
                'pref_id'                    => $data["pref_id"],
                'address'                    => $data["address"],
                'building'                   => $data["building"],
                'store_name'                 => $data["store_name"],
                'tel'                        => $data["tel"],
                'terminal_cd'                => $data["terminal_cd"],
                'departure_date'             => empty($data["departure_date"]) ? null : $data["departure_date"],
                'departure_time'             => empty($data["departure_time"]) ? null : $data["departure_time"],
                'arrival_date'               => empty($data["arrival_date"]) ? null : $data["arrival_date"],
                'arrival_time'               => empty($data["arrival_time"]) ? null : $data["arrival_time"],
                'departure_client_cd'        => $data["departure_client_cd"],
                'departure_client_branch_cd' => $data["departure_client_branch_cd"],
                'arrival_client_cd'          => $data["arrival_client_cd"],
                'arrival_client_branch_cd'   => $data["arrival_client_branch_cd"],
            );
            $ret = $this->_TravelTerminalService->_updateTravelTerminal($db, $data);
        } else {
            //登録用IDを取得
            if(empty($data["id"])) {
                $data["id"] = $this->_TravelTerminalService->select_id($db);
            }
            $data2 = array(
                'id'                         => $data["id"],
                'travel_id'                  => $data["travel_id"],
                'cd'                         => $data["cd"],
                'name'                       => $data["name"],
                'zip'                        => $data["zip"],
                'pref_id'                    => $data["pref_id"],
                'address'                    => $data["address"],
                'building'                   => $data["building"],
                'store_name'                 => $data["store_name"],
                'tel'                        => $data["tel"],
                'terminal_cd'                => $data["terminal_cd"],
                'departure_date'             => empty($data["departure_date"]) ? null : $data["departure_date"],
                'departure_time'             => empty($data["departure_time"]) ? null : $data["departure_time"],
                'arrival_date'               => empty($data["arrival_date"]) ? null : $data["arrival_date"],
                'arrival_time'               => empty($data["arrival_time"]) ? null : $data["arrival_time"],
                'departure_client_cd'        => $data["departure_client_cd"],
                'departure_client_branch_cd' => $data["departure_client_branch_cd"],
                'arrival_client_cd'          => $data["arrival_client_cd"],
                'arrival_client_branch_cd'   => $data["arrival_client_branch_cd"],
            );
            $ret = $this->_TravelTerminalService->_insertTravelTerminal($db, $data2);
        }
        $errorForm = new Sgmov_Form_Error();
        if ($ret === false) {
            throw new Exception();
        }
        return $errorForm;
    }
    
    /**
     * ツアーエリアマスタ登録／更新.
     * @param type $data
     * @return \Sgmov_Form_Error
     */
    private function _updateTravelProvinces($data) {
        // DBへ接続
        $db = Sgmov_Component_DB::getAdmin();
        $travelProvincesInfo = array();
        if(!empty($data["id"])) {
            $travelProvincesInfo = $this->_TravelProvincesService->fetchTravelProvinceLimit($db, $data);
        }
        // 情報をDBへ格納
        if (!empty($travelProvincesInfo)) {
            $data2 = array(
                'id'           => $data["id"],
                'provinces_id' => $data["id"],
                'cd'           => $data["cd"],
                'name'         => $data["name"],
            );
            $ret = $this->_TravelProvincesService->_updateTravelProvince($db, $data2);
        } else {
            //登録用IDを取得
            if(empty($data["id"])) {
                $data["id"] = $this->_TravelProvincesService->select_id($db);
            }
            $data2 = array(
                'id'           => $data["id"],
                'provinces_id' => $data["id"],
                'cd'           => $data["cd"],
                'name'         => $data["name"],
            );
            $ret = $this->_TravelProvincesService->_insertTravelProvince($db, $data2);
        }

        $errorForm = new Sgmov_Form_Error();
        if ($ret === false) {
            $errorForm->addError('top_conflict', '別のユーザーによって更新されています。すべての変更は適用されませんでした。');
            throw new Exception();
        }
        return $errorForm;
    }
    
    /**
     * ツアーエリア都道府県マスタ更新.
     * @param type $data
     */
    private function _updateTravelProvincesPrefectures($data) {
        $db = Sgmov_Component_DB::getAdmin();
        $data2 = array(
            'provinces_id' => $data["provinces_id"],
            'prefecture_id' => $data["prefecture_id"],
        );
        if(empty($data["provinces_id"]) || empty($data["prefecture_id"])) {
            throw new Exception();
        } else {
            $travelProvincesPrefecturesCount = $this->_TravelProvincesPrefecturesService->countTravelProvincesPrefectures($db, $data2);
        }

        $ret = true;
        if(empty($travelProvincesPrefecturesCount)) {
            $ret =$this->_TravelProvincesPrefecturesService->_insertTravelProvincesPrefecture($db, $data2);
        }
        $errorForm = new Sgmov_Form_Error();
        if ($ret === false) {
            throw new Exception();
        }
        return $errorForm;
    }
    
    /**
     * ツアー配送料金マスタ登録／更新.
     * @param type $data
     */
    private function _updateTravelDeliveryCharge($data) {
        $db = Sgmov_Component_DB::getAdmin();
        $travelDeliveryChargeInfo = array();
        if(!empty($data["id"])) {
            $travelDeliveryChargeInfo = $this->_TravelDeliveryChargeService->fetchTravelDeliveryChargeLimit($db, $data);
        }
        // 情報をDBへ格納
        if (!empty($travelDeliveryChargeInfo)) {
            $data2 = array(
                'id'                        => $data["id"],
                'travel_delivery_charge_id' => $data["id"],
                'travel_terminal_id'        => $data["travel_terminal_id"],
            );
            $ret = $this->_TravelDeliveryChargeService->_updateTravelDeliveryCharge($db, $data2);
        } else {
            //登録用IDを取得
            if(empty($data["id"])) {
                $data["id"] = $this->_TravelDeliveryChargeService->select_id($db);
            }
            $data2 = array(
                'id'                        => $data["id"],
                'travel_delivery_charge_id' => $data["id"],
                'travel_terminal_id'        => $data["travel_terminal_id"],
            );
            $ret = $this->_TravelDeliveryChargeService->_insertTravelDeliveryCharge($db, $data2);
        }

        $errorForm = new Sgmov_Form_Error();
        if ($ret === false) {
            throw new Exception();
        }
        return $errorForm;
    }
    
    /**
     * ツアー配送料金エリアマスタ登録／更新.
     * @param type $data
     */
    private function _updateTravelDeliveryChargeAreas($data) {
        $db = Sgmov_Component_DB::getAdmin();
        $travelDeliveryChargeAreasCount = array();
        if(!empty($data["travel_delivery_charge_id"]) && !empty($data["travel_areas_provinces_id"])) {
            $travelDeliveryChargeAreasCount = $this->_TravelDeliveryChargeAreasService->countProvinces($db, $data);
        }
        if (!empty($travelDeliveryChargeAreasCount)) {
            $ret = $this->_TravelDeliveryChargeAreasService->_updateTravelDeliveryChargeAreas($db, $data);
        } else {
            $ret = $this->_TravelDeliveryChargeAreasService->_insertTravelDeliveryChargeAreas($db, $data);
        }
        $errorForm = new Sgmov_Form_Error();
        if ($ret === false) {
            throw new Exception();
        }
        return $errorForm;
    }
}
?>
