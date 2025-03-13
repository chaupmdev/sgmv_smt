<?php
/**
 * @package    ClassDefFile
 * @author     K.Hamada(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/**#@+
 * include files
 */
require_once dirname(__FILE__).'/../../Lib.php';
Sgmov_Lib::useView('pem/Common');
Sgmov_Lib::useServices(array('Employment', 'CenterMail'));
Sgmov_Lib::useForms(array('Error', 'PemSession', 'Pem001In', 'Pem003Out'));
/**#@-*/
/**
 * 採用エントリー情報を登録し、完了画面を表示します。
 * @package    View
 * @subpackage PEM
 * @author     K.Hamada(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_View_Pem_Complete extends Sgmov_View_Pem_Common {

    /**
     * 都道府県
     * @var Sgmov_Service_Employment
     */
    public $_prefectureService;

    /**
     * 拠点
     * @var Sgmov_Service_CenterMail
     */
    public $_centerService;

    /**
     * 採用エントリーサービス
     * @var Sgmov_Service_Employment
     */
    public $_employmentService;

    /**
     * 拠点メールアドレスサービス
     * @var Sgmov_Service_CenterMail
     */
    public $_centerMailService;

    public function __construct() {
    	$this->_prefectureService = new Sgmov_Service_Prefecture();
    	$this->_centerService = new Sgmov_Service_Center();
        $this->_employmentService = new Sgmov_Service_Employment();
        $this->_centerMailService = new Sgmov_Service_CenterMail();
    }
    /**
     * 処理を実行します。
     * <ol><li>
     * セッションの継続を確認
     * </li><li>
     * チケットの確認と破棄
     * </li><li>
     * 入力チェック
     * </li><li>
     * セッションから情報を取得
     * </li><li>
     * 情報をDBへ格納
     * </li><li>
     * 管理者通知メール送信
     * </li><li>
     * サンキューメール送信
     * </li><li>
     * 出力情報を設定
     * </li><li>
     * セッション情報を破棄
     * </li></ol>
     * @return array 生成されたフォーム情報。
     * <ul><li>
     * ['outForm']:出力フォーム
     * </li></ul>
     */
    public function executeInner() {

        // セッションの継続を確認
        $session = Sgmov_Component_Session::get();
        $session->checkSessionTimeout();

        // チケットの確認と破棄
        $session->checkTicket(self::FEATURE_ID, self::GAMEN_ID_PEM002, $this->_getTicket());

        // DBへ接続
        $db = Sgmov_Component_DB::getPublic();

        //登録用IDを取得
        $id = $this->_employmentService->select_id($db);

        // セッションから情報を取得
        $sessionForm = $session->loadForm(self::FEATURE_ID);
        $data = $this->_createInsertDataFromInForm($sessionForm->in, $id);

        // 情報をDBへ格納
        $this->_employmentService->insert($db, $data);

        // メール送信用データを作成
        $mailData = $this->_createMailDataFromInForm($db, $sessionForm->in, $id);

        // 管理者通知メール送信
        $this->_sendAdminMail($db, $sessionForm->in->work_place_flag_sels, $mailData);

        // サンキューメール送信
        if (!empty($sessionForm->in->mail)) {
        	$this->_centerMailService->_sendThankYouMail('/pem_user.txt', $sessionForm->in->mail, $mailData);
        }

        // 出力情報を設定
        $outForm = new Sgmov_Form_Pem003Out();
        $outForm->raw_mail = $sessionForm->in->mail;

        // セッション情報を破棄
        $session->deleteForm(self::FEATURE_ID);

        return array('outForm' => $outForm);

    }

    /**
     * 入力フォームの値を元にインサート用データを生成します。
     * @param Sgmov_Form_Pem001In $inForm 入力フォーム
     * @return array インサート用データ
     */
    public function _createInsertDataFromInForm($inForm, $id) {
        $data = array();
        $data['id'] = $id;
        $data['employ_type_cd'] = $inForm->employ_type_cd_sel;
        $data['job_type_cd'] = $inForm->job_type_cd_sel;
        $data['work_place_flag'] = $inForm->work_place_flag_sels;
        $data['name'] = $inForm->name;
        $data['furigana'] = $inForm->furigana;
        $data['age_cd'] = $inForm->age_cd_sel;
        $data['tel'] = $inForm->tel1.$inForm->tel2.$inForm->tel3;
        $data['mail'] = $inForm->mail;
        $data['zip'] = $inForm->zip1.$inForm->zip2;
        $data['pref_id'] = $inForm->pref_cd_sel;
        $data['address'] = $inForm->address;
        $data['resume'] = $inForm->resume;
        return $data;
    }

    /**
     * 入力フォームの値を元にメール送信用データを生成します。
     * @param Sgmov_Form_Pem001In $inForm 入力フォーム
     * @return array インサート用データ
     */
    public function _createMailDataFromInForm($db, $inForm, $id) {

        // 都道府県を取得
        $prefs = $this->_prefectureService->fetchPrefectures($db);

        // 都道府県名を取得
        $key = array_search($inForm->pref_cd_sel, $prefs['ids']);
        $prefName = $prefs['names'][$key];

        // 勤務地を取得
        $centers = $this->_centerService->fetchCenters($db);

        // 勤務地名を取得
        $place = $inForm->work_place_flag_sels;

        $centerName = "";
        for ($i = 0; $i < count($place); $i++) {
            $key = array_search($place[$i], $centers['ids']);
            $centerName .= $centers['names'][$key];
            if (!($i === (count($place) - 1))) {
                $centerName .= "、";
            }
        }

        $data = array();
        $data['employ_center_id'] = $id;
        $data['employ_type'] = $this->employ_type_lbls[$inForm->employ_type_cd_sel];
        $data['job_type'] = $this->job_type_lbls[$inForm->job_type_cd_sel];
        $data['work_places'] = $centerName;
        $data['name'] = $inForm->name;
        $data['furigana'] = $inForm->furigana;
        $data['age'] = $this->age_lbls[$inForm->age_cd_sel];
        $data['tel'] = $inForm->tel1.'-'.$inForm->tel2.'-'.$inForm->tel3;
        $data['mail'] = $inForm->mail;
        $data['zip'] = $inForm->zip1.'-'.$inForm->zip2;
        $data['address_all'] = $prefName.$inForm->address;
        $data['resume'] = $inForm->resume;

        return $data;

    }

    /**
     * 管理者通知メールを送信します。
     * @param string $prefId 都道府県ID
     * @param array $data メール送信用データ
     */
    public function _sendAdminMail($db, $centerId, $data) {

        foreach ($centerId as $value) {

            // 採用拠点.IDを採用拠点.拠点IDに変換する
            $value = $this->_changeId($db, $value);

            // メールアドレス送信
            $this->_centerMailService->_sendAdminMailForPEM($db, Sgmov_Service_CenterMail::FORM_KBN_PEM, $value, $data, '/pem_admin.txt');

        }
    }

    /**
     * 採用拠点.IDを採用拠点.拠点IDに変換する
     * @param string $centerId 拠点ID
     * @param array $data メール送信用データ
     */
    public function _changeId($db, $centerId) {
        $query = "SELECT
				    center_id
				FROM
				    employment_centers
				WHERE
				    id = $1";

        $params = array($centerId);

        $rs = $db->executeQuery($query, $params);

        $row = $rs->get(0);

        Sgmov_Component_Log::debug("採用拠点.IDを採用拠点.拠点IDに変換する　　".$centerId." => ".$row['center_id']);

        return $row['center_id'];

    }

    /**
     * POST値からチケットを取得します。
     * チケットが存在しない場合は空文字列を返します。
     * @return string チケット文字列
     */
    public function _getTicket() {
        if (!isset($_POST['ticket'])) {
            $ticket = '';
        } else {
            $ticket = $_POST['ticket'];
        }
        return $ticket;
    }

}
?>
