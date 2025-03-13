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
Sgmov_Lib::useComponents(array('System'));
/**#@-*/

 /**
 * {@link Sgmov_Component_Config} の実装クラスです。
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
 * 実装が分離されています。
 *
 * @package Component_Impl
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Component_Impl_Config
{
    /**
     * 設定ファイル名
     */
    const _INI_FNAME = 'sgmov.ini';

    /**
     * このクラスが初期化されているかどうかを示すフラグ
     * @var boolean
     */
    public $_initialized = FALSE;

    /**
     * 設定ファイルの値を保持する連想配列
     * @var hash
     */
    public $_properties;

    /**
     * {@link Sgmov_Component_Config::getLogLevel()} の実装です。
     *
     * @return string ファイルログ出力レベル
     */
    public function getLogLevel()
    {
        return $this->_getProperty('LOG_LEVEL');
    }

    /**
     * {@link Sgmov_Component_Config::getLogMailFrom()} の実装です。
     *
     * @return string ログエラー通知の送信元メールアドレス
     */
    public function getLogMailFrom()
    {
        return $this->_getProperty('LOG_MAIL_FROM');
    }

    /**
     * {@link Sgmov_Component_Config::getLogMailTo()} の実装です。
     *
     * @return string ログエラー通知の送信先メールアドレス
     */
    public function getLogMailTo()
    {
        return $this->_getProperty('LOG_MAIL_TO');
    }

    /**
     * {@link Sgmov_Component_Config::getLogMailFromSpm()} の実装です。
     *
     * @return string ログエラー通知の送信元メールアドレス(エスピーメディアテック)
     */
    public function getLogMailFromSpm()
    {
        return $this->_getProperty('LOG_MAIL_FROM_SPM');
    }

    /**
     * {@link Sgmov_Component_Config::getLogMailToSpm()} の実装です。
     *
     * @return string ログエラー通知の送信先メールアドレス(エスピーメディアテック)
     */
    public function getLogMailToSpm()
    {
        return $this->_getProperty('LOG_MAIL_TO_SPM');
    }

    /**
     * {@link Sgmov_Component_Config::getUrlPublicHttp()} の実装です。
     *
     * @return string HttpのルートURL
     */
    public function getUrlPublicHttp()
    {
        return $this->_getProperty('URL_PUBLIC_HTTP');
    }

    /**
     * {@link Sgmov_Component_Config::getUrlPublicSsl()} の実装です。
     *
     * @return string HttpsのルートURL
     */
    public function getUrlPublicSsl()
    {
        return $this->_getProperty('URL_PUBLIC_SSL');
    }

    /**
     * {@link Sgmov_Component_Config::getUrlMaintenance()} の実装です。
     *
     * @return string HttpsのルートURL
     */
    public function getUrlMaintenance()
    {
        return $this->_getProperty('URL_MAINTENANCE');
    }

    /**
     * {@link Sgmov_Component_Config::getDbHost()} の実装です。
     *
     * @return string DBホスト名
     */
    public function getDbHost()
    {
        return $this->_getProperty('DB_HOST');
    }

    /**
     * {@link Sgmov_Component_Config::getDbPort()} の実装です。
     *
     * @return string DBポート
     */
    public function getDbPort()
    {
        return $this->_getProperty('DB_PORT');
    }

    /**
     * {@link Sgmov_Component_Config::getDbName()} の実装です。
     *
     * @return string DB名
     */
    public function getDbName()
    {
        return $this->_getProperty('DB_NAME');
    }

    /**
     * {@link Sgmov_Component_Config::getDbAdminUser()} の実装です。
     *
     * @return string 管理画面用DBユーザー
     */
    public function getDbAdminUser()
    {
        return $this->_getProperty('DB_ADMIN_USER');
    }

    /**
     * {@link Sgmov_Component_Config::getDbAdminPswd()} の実装です。
     *
     * @return string 管理画面用DBパスワード
     */
    public function getDbAdminPswd()
    {
        return $this->_getProperty('DB_ADMIN_PSWD');
    }

    /**
     * {@link Sgmov_Component_Config::getDbPublicUser()} の実装です。
     *
     * @return string 公開画面用DBユーザー
     */
    public function getDbPublicUser()
    {
        return $this->_getProperty('DB_PUBLIC_USER');
    }

    /**
     * {@link Sgmov_Component_Config::getDbPublicPswd()} の実装です。
     *
     * @return string 公開画面用DBパスワード
     */
    public function getDbPublicPswd()
    {
        return $this->_getProperty('DB_PUBLIC_PSWD');
    }

    /**
     * {@link Sgmov_Component_Config::getYubinDbHost()} の実装です。
     *
     * @return string 郵便DBホスト名
     */
    public function getYubinDbHost()
    {
        return $this->_getProperty('YUBIN_DB_HOST');
    }

    /**
     * {@link Sgmov_Component_Config::getYubinDbPort()} の実装です。
     *
     * @return string 郵便DBポート
     */
    public function getYubinDbPort()
    {
        return $this->_getProperty('YUBIN_DB_PORT');
    }

    /**
     * {@link Sgmov_Component_Config::getYubinDbName()} の実装です。
     *
     * @return string 郵便DB名
     */
    public function getYubinDbName()
    {
        return $this->_getProperty('YUBIN_DB_NAME');
    }

    /**
     * {@link Sgmov_Component_Config::getYubinDbAdminUser()} の実装です。
     *
     * @return string 郵便DB管理ユーザー
     */
    public function getYubinDbAdminUser()
    {
        return $this->_getProperty('YUBIN_DB_ADMIN_USER');
    }

    /**
     * {@link Sgmov_Component_Config::getYubinDbAdminPswd()} の実装です。
     *
     * @return string 郵便DB管理パスワード
     */
    public function getYubinDbAdminPswd()
    {
        return $this->_getProperty('YUBIN_DB_ADMIN_PSWD');
    }

    /**
     * {@link Sgmov_Component_Config::getYubinDbPublicUser()} の実装です。
     *
     * @return string 郵便DB一般ユーザー
     */
    public function getYubinDbPublicUser()
    {
        return $this->_getProperty('YUBIN_DB_PUBLIC_USER');
    }

    /**
     * {@link Sgmov_Component_Config::getYubinDbPublicPswd()} の実装です。
     *
     * @return string 郵便DB一般パスワード
     */
    public function getYubinDbPublicPswd()
    {
        return $this->_getProperty('YUBIN_DB_PUBLIC_PSWD');
    }

    /**
     * {@link Sgmov_Component_Config::getWsProtocol()} の実装です。
     *
     * @return string 訪問見積もり送信先ウェブサービスプロトコル
     */
    public function getWsProtocol()
    {
        return $this->_getProperty('WS_PROTOCOL');
    }

    /**
     * {@link Sgmov_Component_Config::getWsHost()} の実装です。
     *
     * @return string 訪問見積もり送信先ウェブサービスホスト名
     */
    public function getWsHost()
    {
        return $this->_getProperty('WS_HOST');
    }

    /**
     * {@link Sgmov_Component_Config::getWsPath()} の実装です。
     *
     * @return string 訪問見積もり送信先ウェブサービスパス
     */
    public function getWsPath()
    {
        return $this->_getProperty('WS_PATH');
    }

    /**
     * {@link Sgmov_Component_Config::getWsPort()} の実装です。
     *
     * @return string 訪問見積もり送信先ウェブサービスポート
     */
    public function getWsPort()
    {
        return $this->_getProperty('WS_PORT');
    }

    /**
     * {@link Sgmov_Component_Config::getWsUserId()} の実装です。
     *
     * @return string 訪問見積もり送信先ウェブサービスユーザーID
     */
    public function getWsUserId()
    {
        return $this->_getProperty('WS_USERID');
    }

    /**
     * {@link Sgmov_Component_Config::getWsPassword()} の実装です。
     *
     * @return string 訪問見積もり送信先ウェブサービスパスワード
     */
    public function getWsPassword()
    {
        return $this->_getProperty('WS_PASSWORD');
    }


    /**
     * {@link Sgmov_Component_Config::getWsBcrPath()} の実装です。
     *
     * @return string 旅客手荷物受付サービスのお申し込み送信先ウェブサービスパス
     */
    public function getWsBcrPath()
    {
        return $this->_getProperty('WS_BCR_PATH');
    }

    /**
     * {@link Sgmov_Component_Config::getWsBcmPath()} の実装です。
     *
     * @return string イベント輸送サービスのお申し込み送信先ウェブサービスパス
     */
    public function getWsBcmPath()
    {
    	return $this->_getProperty('WS_BCM_PATH');
    }

    /**
     * {@link Sgmov_Component_Config::getWsBtuPath()} の実装です。
     *
     * @return string のお申し込み送信先ウェブサービスパス
     */
    public function getWsBtuPath()
    {
        return $this->_getProperty('WS_BTU_PATH');
    }

    /**
     * {@link Sgmov_Component_Config::getEqProtocol()} の実装です。
     *
     * @return string アンケート送信先ウェブサービスプロトコル
     */
    public function getEqProtocol()
    {
    	return $this->_getProperty('EQ_PROTOCOL');
    }

    /**
     * {@link Sgmov_Component_Config::getEqHost()} の実装です。
     *
     * @return string アンケート送信先ウェブサービスホスト名
     */
    public function getEqHost()
    {
    	return $this->_getProperty('EQ_HOST');
    }

    /**
     * {@link Sgmov_Component_Config::getEqPath()} の実装です。
     *
     * @return string アンケート送信先ウェブサービスパス
     */
    public function getEqPath()
    {
    	return $this->_getProperty('EQ_PATH');
    }

    /**
     * {@link Sgmov_Component_Config::getEqPort()} の実装です。
     *
     * @return string アンケート送信先ウェブサービスポート
     */
    public function getEqPort()
    {
    	return $this->_getProperty('EQ_PORT');
    }

    /**
     * {@link Sgmov_Component_Config::getEqUserId()} の実装です。
     *
     * @return string アンケート送信先ウェブサービスユーザーID
     */
    public function getEqUserId()
    {
    	return $this->_getProperty('EQ_USERID');
    }

    /**
     * {@link Sgmov_Component_Config::getEqPassword()} の実装です。
     *
     * @return string アンケート送信先ウェブサービスパスワード
     */
    public function getEqPassword()
    {
    	return $this->_getProperty('EQ_PASSWORD');
    }


    //////////////////////////////////////////////////////////////////////
    // クルーズ対応用 ベリサインマーチャントCCID
    //////////////////////////////////////////////////////////////////////

    /**
     * {@link Sgmov_Component_Config::getMdkConvenienceStoreMerchantCcId()} の実装です。
     *
     * @return string コンビニ決済用VeriTrans3G MerchantDevelopmentKitマーチャントCCID
     */
    public function getMdkConvenienceStoreMerchantCcId()
    {
        return $this->_getProperty('MDK_CONVENIENCE_STORE_MERCHANT_CC_ID');
    }

    /**
     * {@link Sgmov_Component_Config::getMdkConvenienceStoreMerchantSecretKey()} の実装です。
     *
     * @return string コンビニ決済用VeriTrans3G MerchantDevelopmentKitマーチャントパスワード
     */
    public function getMdkConvenienceStoreMerchantSecretKey()
    {
        return $this->_getProperty('MDK_CONVENIENCE_STORE_MERCHANT_SECRET_KEY');
    }

    /**
     * {@link Sgmov_Component_Config::getMdkCreditCardMerchantCcId()} の実装です。
     *
     * @return string クレジット決済用VeriTrans3G MerchantDevelopmentKitマーチャントCCID
     */
    public function getMdkCreditCardMerchantCcId()
    {
        return $this->_getProperty('MDK_CREDIT_CARD_MERCHANT_CC_ID');
    }

    /**
     * {@link Sgmov_Component_Config::getMdkCreditCardMerchantSecretKey()} の実装です。
     *
     * @return string クレジット決済用VeriTrans3G MerchantDevelopmentKitマーチャントパスワード
     */
    public function getMdkCreditCardMerchantSecretKey()
    {
        return $this->_getProperty('MDK_CREDIT_CARD_MERCHANT_SECRET_KEY');
    }


    //////////////////////////////////////////////////////////////////////
    // イベント対応用 ベリサインマーチャントCCID
    //////////////////////////////////////////////////////////////////////

    /**
     * {@link Sgmov_Component_Config::getMdkConvenienceStoreMerchantCcId()} の実装です。
     *
     * @return string コンビニ決済用VeriTrans3G MerchantDevelopmentKitマーチャントCCID
     */
    public function getComiketMdkConvenienceStoreMerchantCcId()
    {
        return $this->_getProperty('COMIKET_MDK_CONVENIENCE_STORE_MERCHANT_CC_ID');
    }

    /**
     * {@link Sgmov_Component_Config::getMdkConvenienceStoreMerchantSecretKey()} の実装です。
     *
     * @return string コンビニ決済用VeriTrans3G MerchantDevelopmentKitマーチャントパスワード
     */
    public function getComiketMdkConvenienceStoreMerchantSecretKey()
    {
        return $this->_getProperty('COMIKET_MDK_CONVENIENCE_STORE_MERCHANT_SECRET_KEY');
    }

    /**
     * {@link Sgmov_Component_Config::getMdkCreditCardMerchantCcId()} の実装です。
     *
     * @return string クレジット決済用VeriTrans3G MerchantDevelopmentKitマーチャントCCID
     */
    public function getComiketMdkCreditCardMerchantCcId()
    {
        return $this->_getProperty('COMIKET_MDK_CREDIT_CARD_MERCHANT_CC_ID');
    }

    /**
     * {@link Sgmov_Component_Config::getMdkCreditCardMerchantSecretKey()} の実装です。
     *
     * @return string クレジット決済用VeriTrans3G MerchantDevelopmentKitマーチャントパスワード
     */
    public function getComiketMdkCreditCardMerchantSecretKey()
    {
        return $this->_getProperty('COMIKET_MDK_CREDIT_CARD_MERCHANT_SECRET_KEY');
    }


    //////////////////////////////////////////////////////////////////////
    // QRコード引っ越し決済用 ベリサインマーチャントCCID
    // ※カード決済のみ
    //////////////////////////////////////////////////////////////////////

    /**
     * {@link Sgmov_Component_Config::getQrhikkoshiMdkCreditCardMerchantCcId()} の実装です。
     *
     * @return string クレジット決済用VeriTrans3G MerchantDevelopmentKitマーチャントCCID
     */
    public function getQrhikkoshiMdkCreditCardMerchantCcId()
    {
        return $this->_getProperty('QRHIKKOSHI_MDK_CREDIT_CARD_MERCHANT_CC_ID');
    }

    /**
     * {@link Sgmov_Component_Config::getQrhikkoshiMdkCreditCardMerchantSecretKey()} の実装です。
     *
     * @return string クレジット決済用VeriTrans3G MerchantDevelopmentKitマーチャントパスワード
     */
    public function getQrhikkoshiMdkCreditCardMerchantSecretKey()
    {
        return $this->_getProperty('QRHIKKOSHI_MDK_CREDIT_CARD_MERCHANT_SECRET_KEY');
    }

    //////////////////////////////////////////////////////////////////////
    // ミルクラン決済用 ベリサインマーチャントCCID
    // ※カード決済のみ
    //////////////////////////////////////////////////////////////////////
    /**
     * {@link Sgmov_Component_Config::getMlkMdkCreditCardMerchantCcId()} の実装です。
     *
     * @return string クレジット決済用VeriTrans3G MerchantDevelopmentKitマーチャントCCID
     */
    public function getMlkMdkCreditCardMerchantCcId()
    {
        return $this->_getProperty('MIRUKURAN_MDK_CREDIT_CARD_MERCHANT_CC_ID');
    }

    /**
     * {@link Sgmov_Component_Config::getMlkMdkCreditCardMerchantSecretKey()} の実装です。
     *
     * @return string クレジット決済用VeriTrans3G MerchantDevelopmentKitマーチャントパスワード
     */
    public function getMlkMdkCreditCardMerchantSecretKey()
    {
        return $this->_getProperty('MIRUKURAN_MDK_CREDIT_CARD_MERCHANT_SECRET_KEY');
    }

    /**
     * {@link Sgmov_Component_Config::getWsBcmPath()} の実装です。
     *
     * @return string イベント輸送サービスのお申し込み送信先ウェブサービスプロトコル
     */
    public function getWsSearchCustomerProtocol()
    {
    	return $this->_getProperty('WS_SEARCH_CUSTOMER_PROTOCOL');
    }

    /**
     * {@link Sgmov_Component_Config::getWsBcmPath()} の実装です。
     *
     * @return string イベント輸送サービスのお申し込み送信先ウェブサービスパス
     */
    public function getWsSearchCustomerPath()
    {
    	return $this->_getProperty('WS_SEARCH_CUSTOMER_PATH');
    }

    /**
     * {@link Sgmov_Component_Config::getHttpsZipCodeDllUrl()} の実装です。
     *
     * @return string 郵便番号DLL-HTTPS用のURL取得
     */
    public function getHttpsZipCodeDllUrl()
    {
        return $this->_getProperty('HTTPS_ZIP_CODE_DLL_URL');
    }

    /**
     * {@link Sgmov_Component_Config::getComiketCharterFinMailCc()} の実装です。
     *
     * @return string コミケチャーター登録完了時のCcメールアドレス
     */
    public function getComiketCharterFinMailCc()
    {
        return $this->_getProperty('COMIKET_CHARTER_FIN_MAIL_CC');
    }

    /**
     * {@link Sgmov_Component_Config::getComiketCharterFinMailCc()} の実装です。
     *
     * @return string コミケチャーター登録完了時のCcメールアドレス
     */
    public function getComiketCargoFinMailCc()
    {
        return $this->_getProperty('COMIKET_CARGO_FIN_MAIL_CC');
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// SG Financial 定数
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * {@link Sgmov_Component_Config::getSgFinancialShopCode()} の実装です。
     *
     * @return string SG_FINANCIAL店舗コード
     */
    public function getSgFinancialShopCode()
    {
        return $this->_getProperty('SG_FINANCIAL_SHOP_CODE');
    }

    /**
     * {@link Sgmov_Component_Config::getSgFinancialLinkId()} の実装です。
     *
     * @return string SG_FINANCIAL link id
     */
    public function getSgFinancialLinkId()
    {
        return $this->_getProperty('SG_FINANCIAL_LINK_ID');
    }

    /**
     * {@link Sgmov_Component_Config::getSgFinancialLinkPasword()} の実装です。
     *
     * @return string SG_FINANCIAL パスワード
     */
    public function getSgFinancialLinkPasword()
    {
        return $this->_getProperty('SG_FINANCIAL_LINK_PASSWORD');
    }

    /**
     * {@link Sgmov_Component_Config::getSgFinancialBaseApiUrl()} の実装です。
     *
     * @return string SG_FINANCIAL ベースURL
     */
    public function getSgFinancialBaseApiUrl()
    {
        return $this->_getProperty('SG_FINANCIAL_BASE_API_URL');
    }

    /**
     * {@link Sgmov_Component_Config::getSpamMailDomainList()} の実装です。
     *
     * @return string SPAM_MAIL_DOMAIN_LIST を , でexplode したリスト
     */
    public function getSpamMailDomainList() {
        $spamMailDomains = $this->_getProperty('SPAM_MAIL_DOMAIN_LIST');
        $spamMailDomainList = explode(',', $spamMailDomains);
        return $spamMailDomainList;
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// イベント対応 定数
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * イベント関連お申込みキャンセルウェブサービスPATHです。
     */
    public function getWsCancelPath() {
        return $this->_getProperty('WS_CANCEL_PATH');
    }
    
    /**
     * イベント-キャンセル/サイズ変更時(キャンセル)の管理者宛てメールアドレス取得。
     */
    public function getComiketCancelAdminMail() {
        return $this->_getProperty('COMIKET_CANCEL_ADMIN_MAIL');
    }
    
    /**
     * イベント再送信確認用の管理者宛てメールアドレス取得。
     */
    public function getComiketResendAdminMail() {
        return $this->_getProperty('COMIKET_RESEND_ADMIN_MAIL');
    }
    
    /**
     * イベント関連-問合せ番号取得ウェブサービスPATH。
     *
     * @return string 問合せ番号取得ウェブサービスPATH
     */
    public function getWsToiawaseNoPath()
    {
        return $this->_getProperty('WS_GET_TOIAWASE_NO_PATH');
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 当日物販用　vasi add 2020/09/17 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * イベント関連-問合せ番号取得ウェブサービスPATH。
     *
     * @return string 問合せ番号取得ウェブサービスPATH
     */
    public function getBuppanCompleteMail()
    {
        return $this->_getProperty('TOJITSU_BUPPAN_COMPLETE_MAIL');
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 当日物販用　vasi add 2020/09/17 //
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 共通メール
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * イベント共通完了メール。
     *
     * @return string メール
     */
    public function getEveCommonCompleteMail()
    {
        return $this->_getProperty('EVE_COMPLETE_MAIL');
    }


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * 設定ファイルを読み込んでプロパティを取得します。
     * @param string $iniFilePath 設定ファイルのパス
     * @return hash 設定の連想配列
     */
    public function _load($iniFilePath)
    {
        $_properties = FALSE;
        try {
            $_properties = parse_ini_file($iniFilePath);
        }
        catch (exception $e) {
            Sgmov_Component_System::log('設定ファイルの読み込みに失敗しました。', $e);
            Sgmov_Component_System::systemErrorExit();
        }

        $this->_initialized = TRUE;
        $this->_properties = $_properties;
    }

    /**
     * 設定ファイルから指定されたキーに対応する値を取得します。
     * @param string $key キー
     * @return string キーに対応する値
     */
    public function _getProperty($key)
    {
        if (!$this->_initialized) {
            $this->_load(Sgmov_Lib::getConfigDir() . '/' . self::_INI_FNAME);
        }

        if (!isset($this->_properties[$key])) {
            Sgmov_Component_System::log("指定されたキー({$key})は存在しません。");
            Sgmov_Component_System::systemErrorExit();
        }

        return $this->_properties[$key];
    }

    /**
     * {@link Sgmov_Component_Config::getHikkoshiDbHost()} の実装です。
     *
     * @return string DBホスト名
     */
    public function getHikkoshiDbHost()
    {
        return $this->_getProperty('DB_HIKKOSHI_HOST');
    }

    /**
     * {@link Sgmov_Component_Config::getHikkoshiDbPort()} の実装です。
     *
     * @return string DBポート
     */
    public function getHikkoshiDbPort()
    {
        return $this->_getProperty('DB_HIKKOSHI_PORT');
    }

    /**
     * {@link Sgmov_Component_Config::getHikkoshiDbName()} の実装です。
     *
     * @return string DB名
     */
    public function getHikkoshiDbName()
    {
        return $this->_getProperty('DB_HIKKOSHI_NAME');
    }
    
     /**
     * {@link Sgmov_Component_Config::getHikkoshiUser()} の実装です。
     *
     * @return string DB名
     */
    public function getHikkoshiDbUser()
    {
        return $this->_getProperty('DB_HIKKOSHI_USER');
    }
    
     /**
     * {@link Sgmov_Component_Config::getHikkoshiDbPswd()} の実装です。
     *
     * @return string DB名
     */
    public function getHikkoshiDbPswd()
    {
        return $this->_getProperty('DB_HIKKOSHI_PSWD');
    }
     /**
     * {@link Sgmov_Component_Config::getUrlPaymentInforApiForIVR()} の実装です。
     *
     * @return string URL
     */
    public function getUrlPaymentInforApiForIVR()
    {
        return $this->_getProperty('IVR_PAYMENT_INFORMATION_API_URL');
    }
     /**
     * {@link Sgmov_Component_Config::getPswdPaymentInforApiForIVR()} の実装です。
     *
     * @return string パスワード
     */
    public function getPswdPaymentInforApiForIVR()
    {
        return $this->_getProperty('IVR_PAYMENT_INFORMATION_API_PASSWORD');
    }
    
     /**
     * {@link Sgmov_Component_Config::getCallCenterCommonMailTo()} の実装です。
     *
     * @return string メールアドレス
     */
    public function getCallCenterCommonMailTo()
    {
        return $this->_getProperty('PCR_IVR_CALL_CENTER_MAIL_TO');
    }
    
     /**
     * {@link Sgmov_Component_Config::getMerchantIdForIVR()} の実装です。
     *
     * @return string マーチャントID
     */
    public function getMerchantIdForIVR()
    {
        return $this->_getProperty('IVR_MERCHANT_ID');
    }
}