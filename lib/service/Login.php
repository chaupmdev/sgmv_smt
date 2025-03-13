<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

 /**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useAllComponents();
/**#@-*/

 /**
 * ログイン情報の管理と、権限のチェックを行います。
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
 * @package Service
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Service_Login
{
    /**
     * 拠点ユーザーがアクセス可能な機能ID
     * @var array
     */
    protected static $_kyotenUserAllowedFeatureIds = array('LOGIN',
                                                             'MENU',
                                                             'LOGOUT',
                                                             'ASP_EXTRA',
                                                             'ASP_CAMPAIGN',
                                                             'ASP_CALDETAIL',
                                                             'AIN');

    /**
     * ログインしているユーザーが指定された機能に対するアクセス権限を持っているかどうかを確認します。
     * ユーザーがログインしていない場合・アクセス権限がない場合はアプリケーションエラーとなります。
     *
     * ただし、ログイン画面は誰でもアクセス可能です。
     * @param string $featureId 機能ID
     * @see SgmovApp::applicationErrorExit()
     */
    public function checkUserAuth($featureId)
    {
        if ($featureId === 'LOGIN') {
            // ログイン画面は誰でもアクセス可能
            return;
        }

        $user = Sgmov_Component_Session::get()->loadLoginUser();
        if (is_null($user)) {
            // 未ログイン
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_AUTH_NOT_LOGIN);
        } else {
            if ($user->isHonshaUser === TRUE) {
                // 本社ユーザーは全てアクセス可能
            } else {
                // 拠点ユーザー
                if (in_array($featureId, self::$_kyotenUserAllowedFeatureIds) === FALSE) {
                    // 権限なし
                    Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_AUTH_NOT_ALLOWED);
                }
            }
        }
    }

    /**
     * ログアウト処理を実行します。
     * セッションの情報は、セッション継続情報以外全て削除されます。
     */
    public function logout()
    {
        $session = Sgmov_Component_Session::get();
        $session->clearSession();
        Sgmov_Component_Log::debug("ログアウトしました。");
    }

    /**
     * ログイン処理を実行します。
     *
     * ログインに成功した場合、ログインユーザー情報がセッションに保存されます。
     *
     * @param string $account アカウント
     * @param string $password パスワード
     * @return boolean 成功した場合TRUEを、失敗した場合FALSEを返します。
     */
    public function login($account, $password)
    {
        // DBから情報を取得
        $sql = 'SELECT';
        $sql .= '        login_users.account AS login_user_account';
        $sql .= '        ,centers_login_users.center_id AS center_id';
        $sql .= '        ,centers.honsya_flag AS center_honsya_flag';
        $sql .= '        ,centers.name AS center_name';
        $sql .= '    FROM';
        $sql .= '        login_users';
        $sql .= '            JOIN centers_login_users';
        $sql .= '                ON login_users.id = centers_login_users.login_user_id';
        $sql .= '            JOIN centers';
        $sql .= '                ON centers.id = centers_login_users.center_id';
        $sql .= '    WHERE';
        $sql .= '        login_users.account = $1';
        $sql .= '        AND login_users.password = $2';
        $sql .= '        AND login_users.enabled_flag = \'1\'';

        $db = Sgmov_Component_DB::getAdmin();
        $result = $db->executeQuery($sql, array($account, $password));

        // 結果の確認
        if ($result->size() === 0) {
            Sgmov_Component_Log::debug("ユーザーが存在しません。");
            return FALSE;
        }
        if ($result->size() !== 1) {
            Sgmov_Component_Log::warning("複数のユーザーが見つかりました。");
            return FALSE;
        }

        // 成功した場合はセッションに保存
        $row = $result->get(0);
        $loginUser = new Sgmov_Form_LoginUser();
        $loginUser->account = $row['login_user_account'];
        $loginUser->centerId = $row['center_id'];
        $loginUser->centerName = $row['center_name'];
        if ($row['center_honsya_flag'] === 't') {
            $loginUser->isHonshaUser = TRUE;
        } else {
            $loginUser->isHonshaUser = FALSE;
        }
        $session = Sgmov_Component_Session::get();
        $session->saveLoginUser($loginUser);

        return TRUE;
    }

    /**
     * ログインしているかどうかを確認します。
     * @return boolean ログインしている場合はTRUE、していない場合はFALSEを返します。
     */
    public function isLoggedIn()
    {
        return !is_null($this->getLoginUser());
    }

    /**
     * 本社ユーザーフラグを取得します。
     * @return string ログインしている場合は'1'、していない場合は'0'を返します。
     */
    public function getHonshaUserFlag()
    {
        $user = Sgmov_Component_Session::get()->loadLoginUser();
        if (is_null($user)) {
            Sgmov_Component_ErrorExit::errorExit(Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT, 'getHonshaUserFlagはログイン状態で呼び出してください。');
        }
        if ($user->isHonshaUser === TRUE) {
            return '1';
        } else {
            return '0';
        }
    }

    /**
     * ログインユーザー情報をセッションから取得します。存在しない場合は NULL を返します。
     * @return Sgmov_Form_LoginUser 取得されたユーザー情報。ユーザー情報が存在しない場合は NULL を返します。
     */
    public function getLoginUser()
    {
        return Sgmov_Component_Session::get()->loadLoginUser();
    }
}

?>
