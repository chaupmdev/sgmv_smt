<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/impl/Config.php';
/**#@-*/

 /**
 * 設定ファイルから各種設定情報を取得します。
 *
 * このクラスで問題が発生した場合はシステムエラーとして処理され
 * 現在のスクリプトは終了します。
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
 * @package Component
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Component_Config
{
    /**
     * 実装クラスのインスタンス
     * @var Sgmov_Component_Impl_Config
     */
    public static $_impl;

    /**
     * ファイルログの出力レベルを取得します。
     * @return string ファイルログ出力レベル
     */
    public static function getLogLevel()
    {
        return self::_getImpl()->getLogLevel();
    }

    /**
     * メールログの送信元メールアドレスを取得します。
     * @return string ログエラー通知の送信元メールアドレス
     */
    public static function getLogMailFrom()
    {
        return self::_getImpl()->getLogMailFrom();
    }

    /**
     * メールログの送信先メールアドレスを取得します。
     * @return string ログエラー通知の送信先メールアドレス
     */
    public static function getLogMailTo()
    {
        return self::_getImpl()->getLogMailTo();
    }

    /**
     * メールログの送信元メールアドレスを取得します。
     * @return string ログエラー通知の送信元メールアドレス(エスピーメディアテック)
     */
    public static function getLogMailFromSpm()
    {
        return self::_getImpl()->getLogMailFromSpm();
    }

    /**
     * メールログの送信先メールアドレスを取得します。
     * @return string ログエラー通知の送信先メールアドレス(エスピーメディアテック)
     */
    public static function getLogMailToSpm()
    {
        return self::_getImpl()->getLogMailToSpm();
    }

    /**
     * 公開画面(HTTP)のルートURLを取得します。
     * @return string 公開画面(HTTP)のルートURL
     */
    public static function getUrlPublicHttp()
    {
        return self::_getImpl()->getUrlPublicHttp();
    }

    /**
     * 公開画面(SSL)のルートURLを取得します。
     * @return string 公開画面(SSL)のルートURL
     */
    public static function getUrlPublicSsl()
    {
        return self::_getImpl()->getUrlPublicSsl();
    }

    /**
     * 管理画面のルートURLを取得します。
     * @return string 管理画面のルートURL
     */
    public static function getUrlMaintenance()
    {
        return self::_getImpl()->getUrlMaintenance();
    }

    /**
     * DBホスト名を取得します。
     * @return string DBホスト名
     */
    public static function getDbHost()
    {
        return self::_getImpl()->getDbHost();
    }

    /**
     * DBポートを取得します。
     * @return string DBポート
     */
    public static function getDbPort()
    {
        return self::_getImpl()->getDbPort();
    }

    /**
     * DB名を取得します。
     * @return string DB名
     */
    public static function getDbName()
    {
        return self::_getImpl()->getDbName();
    }

    /**
     * 管理画面用DBユーザーを取得します。
     * @return string 管理画面用DBユーザー
     */
    public static function getDbAdminUser()
    {
        return self::_getImpl()->getDbAdminUser();
    }

    /**
     * 管理画面用DBパスワードを取得します。
     * @return string 管理画面用DBパスワード
     */
    public static function getDbAdminPswd()
    {
        return self::_getImpl()->getDbAdminPswd();
    }

    /**
     * 公開画面用DBユーザーを取得します。
     * @return string 公開画面用DBユーザー
     */
    public static function getDbPublicUser()
    {
        return self::_getImpl()->getDbPublicUser();
    }

    /**
     * 公開画面用DBパスワードを取得します。
     * @return string 公開画面用DBパスワード
     */
    public static function getDbPublicPswd()
    {
        return self::_getImpl()->getDbPublicPswd();
    }

    /**
     * 郵便DBホスト名を取得します。
     * @return string 郵便DBホスト名
     */
    public static function getYubinDbHost()
    {
        return self::_getImpl()->getYubinDbHost();
    }

    /**
     * 郵便DBポートを取得します。
     * @return string 郵便DBポート
     */
    public static function getYubinDbPort()
    {
        return self::_getImpl()->getYubinDbPort();
    }

    /**
     * 郵便DB名を取得します。
     * @return string 郵便DB名
     */
    public static function getYubinDbName()
    {
        return self::_getImpl()->getYubinDbName();
    }

    /**
     * 郵便DBの管理ユーザーを取得します。
     * @return string 郵便DBの管理ユーザー
     */
    public static function getYubinDbAdminUser()
    {
        return self::_getImpl()->getYubinDbAdminUser();
    }

    /**
     * 郵便DBの管理パスワードを取得します。
     * @return string 郵便DBの管理パスワード
     */
    public static function getYubinDbAdminPswd()
    {
        return self::_getImpl()->getYubinDbAdminPswd();
    }

    /**
     * 郵便DBの一般ユーザーを取得します。
     * @return string 郵便DBの一般ユーザー
     */
    public static function getYubinDbPublicUser()
    {
        return self::_getImpl()->getYubinDbPublicUser();
    }

    /**
     * 郵便DBの一般パスワードを取得します。
     * @return string 郵便DBの一般パスワード
     */
    public static function getYubinDbPublicPswd()
    {
        return self::_getImpl()->getYubinDbPublicPswd();
    }

    /**
     * 訪問見積もり送信先ウェブサービスプロトコルを取得します。
     *
     * @return string 訪問見積もり送信先ウェブサービスプロトコル
     */
    public static function getWsProtocol()
    {
        return self::_getImpl()->getWsProtocol();
    }

    /**
     * 訪問見積もり送信先ウェブサービスホスト名を取得します。
     *
     * @return string 訪問見積もり送信先ウェブサービスホスト名
     */
    public static function getWsHost()
    {
        return self::_getImpl()->getWsHost();
    }

    /**
     * 訪問見積もり送信先ウェブサービスパスを取得します。
     *
     * @return string 訪問見積もり送信先ウェブサービスパス
     */
    public static function getWsPath()
    {
        return self::_getImpl()->getWsPath();
    }

    /**
     * 訪問見積もり送信先ウェブサービスポートを取得します。
     *
     * @return string 訪問見積もり送信先ウェブサービスポート
     */
    public static function getWsPort()
    {
        return self::_getImpl()->getWsPort();
    }

    /**
     * 訪問見積もり送信先ウェブサービスユーザーIDを取得します。
     *
     * @return string 訪問見積もり送信先ウェブサービスユーザーID
     */
    public static function getWsUserId()
    {
        return self::_getImpl()->getWsUserId();
    }

    /**
     * 訪問見積もり送信先ウェブサービスパスワードを取得します。
     *
     * @return string 訪問見積もり送信先ウェブサービスパスワード
     */
    public static function getWsPassword()
    {
        return self::_getImpl()->getWsPassword();
    }

    /**
     * 旅客手荷物受付サービスのお申し込み送信先ウェブサービスパスを取得します。
     *
     * @return string 旅客手荷物受付サービスのお申し込み送信先ウェブサービスパス
     */
    public static function getWsBcrPath()
    {
        return self::_getImpl()->getWsBcrPath();
    }

    /**
     * 単身カーゴプランのお申し込み送信先ウェブサービスパスを取得します。
     *
     * @return string のお申し込み送信先ウェブサービスパス
     */
    public static function getWsBtuPath()
    {
        return self::_getImpl()->getWsBtuPath();
    }

    /**
     * イベント輸送サービスのお申し込み送信先ウェブサービスパスを取得します。
     *
     * @return string イベント輸送サービスのお申し込み送信先ウェブサービスパス
     */
    public static function getWsBcmPath()
    {
    	return self::_getImpl()->getWsBcmPath();
    }

    /**
     * アンケート送信先ウェブサービスプロトコルを取得します。
     *
     * @return string アンケート送信先ウェブサービスプロトコル
     */
    public static function getEqProtocol()
    {
    	return self::_getImpl()->getEqProtocol();
    }

    /**
     * アンケート送信先ウェブサービスホスト名を取得します。
     *
     * @return string アンケート送信先ウェブサービスホスト名
     */
    public static function getEqHost()
    {
    	return self::_getImpl()->getEqHost();
    }

    /**
     * アンケート送信先ウェブサービスパスを取得します。
     *
     * @return string アンケート送信先ウェブサービスパス
     */
    public static function getEqPath()
    {
    	return self::_getImpl()->getEqPath();
    }

    /**
     * アンケート送信先ウェブサービスポートを取得します。
     *
     * @return string アンケート送信先ウェブサービスポート
     */
    public static function getEqPort()
    {
    	return self::_getImpl()->getEqPort();
    }

    /**
     * アンケート送信先ウェブサービスユーザーIDを取得します。
     *
     * @return string アンケート送信先ウェブサービスユーザーID
     */
    public static function getEqUserId()
    {
    	return self::_getImpl()->getEqUserId();
    }

    /**
     * アンケート送信先ウェブサービスパスワードを取得します。
     *
     * @return string アンケート送信先ウェブサービスパスワード
     */
    public static function getEqPassword()
    {
    	return self::_getImpl()->getEqPassword();
    }



    //////////////////////////////////////////////////////////////////////
    // クルーズ対応用
    //////////////////////////////////////////////////////////////////////
    /**
     * コンビニ決済用VeriTrans3G MerchantDevelopmentKitマーチャントCCIDを取得します。
     *
     * @return string コンビニ決済用VeriTrans3G MerchantDevelopmentKitマーチャントCCID
     */
    public static function getMdkConvenienceStoreMerchantCcId()
    {
        return self::_getImpl()->getMdkConvenienceStoreMerchantCcId();
    }

    /**
     * コンビニ決済用VeriTrans3G MerchantDevelopmentKitマーチャントパスワードを取得します。
     *
     * @return string コンビニ決済用VeriTrans3G MerchantDevelopmentKitマーチャントパスワード
     */
    public static function getMdkConvenienceStoreMerchantSecretKey()
    {
        return self::_getImpl()->getMdkConvenienceStoreMerchantSecretKey();
    }

    /**
     * クレジット決済用VeriTrans3G MerchantDevelopmentKitマーチャントCCIDを取得します。
     *
     * @return string クレジット決済用VeriTrans3G MerchantDevelopmentKitマーチャントCCID
     */
    public static function getMdkCreditCardMerchantCcId()
    {
        return self::_getImpl()->getMdkCreditCardMerchantCcId();
    }

    /**
     * クレジット決済用VeriTrans3G MerchantDevelopmentKitマーチャントパスワードを取得します。
     *
     * @return string クレジット決済用VeriTrans3G MerchantDevelopmentKitマーチャントパスワード
     */
    public static function getMdkCreditCardMerchantSecretKey()
    {
        return self::_getImpl()->getMdkCreditCardMerchantSecretKey();
    }

    //////////////////////////////////////////////////////////////////////
    // イベント対応用
    //////////////////////////////////////////////////////////////////////

    /**
     * [イベント対応用]コンビニ決済用VeriTrans3G MerchantDevelopmentKitマーチャントCCIDを取得します。
     *
     * @return string コンビニ決済用VeriTrans3G MerchantDevelopmentKitマーチャントCCID
     */
    public static function getComiketMdkConvenienceStoreMerchantCcId()
    {
        return self::_getImpl()->getComiketMdkConvenienceStoreMerchantCcId();
    }

    /**
     * [イベント対応用]コンビニ決済用VeriTrans3G MerchantDevelopmentKitマーチャントパスワードを取得します。
     *
     * @return string コンビニ決済用VeriTrans3G MerchantDevelopmentKitマーチャントパスワード
     */
    public static function getComiketMdkConvenienceStoreMerchantSecretKey()
    {
        return self::_getImpl()->getComiketMdkConvenienceStoreMerchantSecretKey();
    }

    /**
     * [イベント対応用]クレジット決済用VeriTrans3G MerchantDevelopmentKitマーチャントCCIDを取得します。
     *
     * @return string クレジット決済用VeriTrans3G MerchantDevelopmentKitマーチャントCCID
     */
    public static function getComiketMdkCreditCardMerchantCcId()
    {
        return self::_getImpl()->getComiketMdkCreditCardMerchantCcId();
    }

    /**
     * [イベント対応用]クレジット決済用VeriTrans3G MerchantDevelopmentKitマーチャントパスワードを取得します。
     *
     * @return string クレジット決済用VeriTrans3G MerchantDevelopmentKitマーチャントパスワード
     */
    public static function getComiketMdkCreditCardMerchantSecretKey()
    {
        return self::_getImpl()->getComiketMdkCreditCardMerchantSecretKey();
    }

    //////////////////////////////////////////////////////////////////////
    // QRコード引っ越し決済用 ベリサインマーチャントCCID
    //////////////////////////////////////////////////////////////////////

    /**
     * [イベント対応用]クレジット決済用VeriTrans3G MerchantDevelopmentKitマーチャントCCIDを取得します。
     *
     * @return string クレジット決済用VeriTrans3G MerchantDevelopmentKitマーチャントCCID
     */
    public static function getQrhikkoshiMdkCreditCardMerchantCcId()
    {
        return self::_getImpl()->getQrhikkoshiMdkCreditCardMerchantCcId();
    }

    /**
     * [イベント対応用]クレジット決済用VeriTrans3G MerchantDevelopmentKitマーチャントパスワードを取得します。
     *
     * @return string クレジット決済用VeriTrans3G MerchantDevelopmentKitマーチャントパスワード
     */
    public static function getQrhikkoshiMdkCreditCardMerchantSecretKey()
    {
        return self::_getImpl()->getQrhikkoshiMdkCreditCardMerchantSecretKey();
    }

    //////////////////////////////////////////////////////////////////////
    // ミルクラン決済用 ベリサインマーチャントCCID
    //////////////////////////////////////////////////////////////////////

    /**
     * [イベント対応用]クレジット決済用VeriTrans3G MerchantDevelopmentKitマーチャントCCIDを取得します。
     *
     * @return string クレジット決済用VeriTrans3G MerchantDevelopmentKitマーチャントCCID
     */
    public static function getMlkMdkCreditCardMerchantCcId()
    {
        return self::_getImpl()->getMlkMdkCreditCardMerchantCcId();
    }

    /**
     * [イベント対応用]クレジット決済用VeriTrans3G MerchantDevelopmentKitマーチャントパスワードを取得します。
     *
     * @return string クレジット決済用VeriTrans3G MerchantDevelopmentKitマーチャントパスワード
     */
    public static function getMlkMdkCreditCardMerchantSecretKey()
    {
        return self::_getImpl()->getMlkMdkCreditCardMerchantSecretKey();
    }


    /**
     * {@link Sgmov_Component_Config::getWsBcmPath()} の実装です。
     *
     * @return string イベント輸送サービスのお申し込み送信先ウェブサービスパス
     */
    public static function getWsSearchCustomerProtocol()
    {
    	return self::_getImpl()->getWsSearchCustomerProtocol();
    }

    /**
     * {@link Sgmov_Component_Config::getWsBcmPath()} の実装です。
     *
     * @return string イベント輸送サービスのお申し込み送信先ウェブサービスパス
     */
    public static function getWsSearchCustomerPath()
    {
    	return self::_getImpl()->getWsSearchCustomerPath();
    }

    /**
     * {@link Sgmov_Component_Config::getHttpsZipCodeDllUrl()} の実装です。
     *
     * @return string 郵便番号DLL-HTTPS用のURL取得
     */
    public static function getHttpsZipCodeDllUrl()
    {
        return self::_getImpl()->getHttpsZipCodeDllUrl();
    }

    /**
     * {@link Sgmov_Component_Config::getComiketCharterFinMailTo()} の実装です。
     *
     * @return string コミケチャーター登録完了時のCcメールアドレス
     */
    public static function getComiketCharterFinMailCc()
    {
        return self::_getImpl()->getComiketCharterFinMailCc();
    }

    /**
     * {@link Sgmov_Component_Config::getComiketCharterFinMailTo()} の実装です。
     *
     * @return string コミケチャーター登録完了時のCcメールアドレス
     */
    public static function getComiketCargoFinMailCc()
    {
        return self::_getImpl()->getComiketCargoFinMailCc();
    }
    
    /**
     * イベント関連問合せ番号取得ウェブサービスPATH。
     *
     * @return string 問合せ番号取得ウェブサービスPATH
     */
    public static function getWsToiawaseNoPath()
    {
        return self::_getImpl()->getWsToiawaseNoPath();
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// SG Financial 定数
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * {@link Sgmov_Component_Config::getSgFinancialShopCode()} の実装です。
     *
     * @return string SG_FINANCIAL店舗コード
     */
    public static function getSgFinancialShopCode()
    {
        return self::_getImpl()->getSgFinancialShopCode();
    }



    /**
     * {@link Sgmov_Component_Config::getSgFinancialLinkId()} の実装です。
     *
     * @return string SG_FINANCIAL店舗コード
     */
    public static function getSgFinancialLinkId()
    {
        return self::_getImpl()->getSgFinancialLinkId();
    }

    /**
     * {@link Sgmov_Component_Config::getSgFinancialLinkPasword()} の実装です。
     *
     * @return string SG_FINANCIAL パスワード
     */
    public static function getSgFinancialLinkPasword()
    {
        return self::_getImpl()->getSgFinancialLinkPasword();
    }


    /**
     * {@link Sgmov_Component_Config::getSgFinancialBaseApiUrl()} の実装です。
     *
     * @return string SG_FINANCIAL ベースURL
     */
    public static function getSgFinancialBaseApiUrl()
    {
        return self::_getImpl()->getSgFinancialBaseApiUrl();
    }

    /**
     * {@link Sgmov_Component_Config::getSpamMailDomainList()} の実装です。
     *
     * @return string SPAM_MAIL_DOMAIN_LIST を ,(カンマ) でexplode したリスト
     */
    public function getSpamMailDomainList() {
        return self::_getImpl()->getSpamMailDomainList();
    }

    /**
     * イベント関連お申込みキャンセルウェブサービスPATHです。
     */
    public static function getWsCancelPath() {
        return self::_getImpl()->getWsCancelPath();
    }
    
    /**
     * イベント-キャンセル/サイズ変更時(キャンセル)の管理者宛てメールアドレス取得。
     */
    public function getComiketCancelAdminMail() {
        return self::_getImpl()->getComiketCancelAdminMail();
    }
    
    /**
     * イベント再送信確認用の管理者宛てメールアドレス取得。
     */
    public function getComiketResendAdminMail() {
        return self::_getImpl()->getComiketResendAdminMail();
    }


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 当日物販用　vasi add 2020/09/17 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * イベント当日物販用の完了宛てメールアドレス取得。
     */
    public function getBuppanCompleteMail() {
        return self::_getImpl()->getBuppanCompleteMail();
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 当日物販用　vasi add 2020/09/17 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// イベント共通完了メール
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * イベント共通完了メール。
     */
    public function getEveCommonCompleteMail() {
        return self::_getImpl()->getEveCommonCompleteMail();
    }

    
    /**
     * 実装クラスのインスタンスを取得します。
     *
     * 既に生成されている場合はそのインスタンスを返します。
     * まだ生成されていない場合は生成して返します。
     *
     * @return Sgmov_Component_Impl_System 実装クラスのインスタンス
     */
    public static function _getImpl()
    {
        if (!isset(self::$_impl)) {
            self::$_impl = new Sgmov_Component_Impl_Config();
        }
        return self::$_impl;
    }

    /**
     * 引越DBホスト名を取得します。
     * @return string DBホスト名
     */
    public static function getHikkoshiDbHost()
    {
        return self::_getImpl()->getHikkoshiDbHost();
    }

    /**
     * 引越DBポートを取得します。
     * @return string DBポート
     */
    public static function getHikkoshiDbPort()
    {
        return self::_getImpl()->getHikkoshiDbPort();
    }

    /**
     * 引越DB名を取得します。
     * @return string DB名
     */
    public static function getHikkoshiDbName()
    {
        return self::_getImpl()->getHikkoshiDbName();
    }
    
    /**
     * 引越DB名を取得します。
     * @return string DB名
     */
    public static function getHikkoshiDbUser()
    {
        return self::_getImpl()->getHikkoshiDbUser();
    }
    
    /**
     * 引越DB名を取得します。
     * @return string DB名
     */
    public static function getHikkoshiDbPswd()
    {
        return self::_getImpl()->getHikkoshiDbPswd();
    }
    /**
     * IVR連携APIのURLを取得。
     * @return string APIURL
     */
    public static function getUrlPaymentInforApiForIVR()
    {
        return self::_getImpl()->getUrlPaymentInforApiForIVR();
    }
    
    /**
     * IVR連携API用のパスワードを取得。
     * @return string パスワード
     */
    public static function getPswdPaymentInforApiForIVR()
    {
        return self::_getImpl()->getPswdPaymentInforApiForIVR();
    }
    
    /**
     * クルーズのコールセンターの共通メールアドレスを取得。
     * @return string メールアドレス
     */
    public static function getCallCenterCommonMailTo()
    {
        return self::_getImpl()->getCallCenterCommonMailTo();
    }
    
    /**
     * IVR連携API用のマーチャントIDを取得。
     * @return string マーチャントID
     */
    public static function getMerchantIdForIVR()
    {
        return self::_getImpl()->getMerchantIdForIVR();
    }
}