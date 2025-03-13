<?php
    
    /**
     * 09_会員情報登録・変更。
     * @package    ssl_html
    * @subpackage event/updateInfo
    * @author     GiapLN(FPT Software) 
     */

    /**#@+
     * include files
     */
    require_once dirname(__FILE__) . '/../../lib/Lib.php';
    Sgmov_Lib::useView('event/InputUpdateInfo');
    /**#@-*/

    // 処理を実行
    $view = new Sgmov_View_Event_InputUpdateInfo();

    $forms = $view->execute();

    $object = $forms['object'];
    $updateInfo001Out = $forms['outForm'];

    /**
     * エラーフォーム
     * @var Sgmov_Form_Error
     */
    $e = $forms['errorForm'];
    $sessionData = $forms['sessionData'];
    /**
    * チケット
    * @var string
    */
   $ticket = $forms['ticket'];
?> 
<!DOCTYPE html>
<html dir="ltr" lang="ja-jp" xml:lang="ja-jp">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0" />
    <meta http-equiv="content-style-type" content="text/css" />
    <meta http-equiv="content-script-type" content="text/javascript" />
    <meta name="Keywords" content="" />
    <meta name="Description" content="催事・イベント配送受付サービスのお申し込みのご案内です。" />
    <meta name="author" content="SG MOVING Co.,Ltd" />
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved." />
    <title>会員情報登録・変更│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
<?php
    // キャッシュ対策
    $sysdate = new DateTime();
    $strSysdate = $sysdate->format('YmdHi');
?>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <link href="/event/css/event.css?<?php echo $strSysdate; ?>" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
    <script charset="UTF-8" type="text/javascript" src="/event/js/jquery-3.1.1.min.js"></script>
</head>
<body>
<?php
    $gnavSettings = 'contact';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/header.php';
?>
    <div id="breadcrumb">
        
    </div>
    <div id="main">
        <div class="wrap clearfix">
            <h1 class="page_title">会員情報登録・変更</h1>
            <?php
                if (isset($e) && $e->hasError()) { //$errorFlg && $error->hasError()
            ?>
            <div class="err_msg">
                <p class="sentence br attention">[!ご確認ください]下記の項目が正しく入力・選択されていません。</p>
                <ul>
                <?php
                    // エラー表示
                    foreach($e->_errors as $key => $val) {
                        echo "<li><a href='#" . $key . "'>" . $val . '</a></li>';
                    }
                ?>
                </ul>
            </div>
            <?php
                }
            ?>
            <div class="section other">
                <form action="/event/check_input_updateInfo.php" data-feature-id="<?php echo Sgmov_View_Event_Common::FEATURE_ID ?>" data-id="<?php echo Sgmov_View_Event_Common::GAMEN_ID_EVENT009 ?>"  method="post">
                    <input name="ticket" type="hidden" value="<?php echo $ticket ?>" />
                    <div class="section">
                        <div class="dl_block">
                            <dl>
                                <dt>
                                    メールアドレス
                                </dt>
                                <dd>
                                    <input style="width:80%; background-color: #ccc;" disabled="disabled" autocapitalize="off"  name="email" placeholder="" type="email" value="<?php echo $object['mail']; ?>" />
                                </dd>
                            </dl>
                            <dl class="comiket-personal-name-seimei" style="">
                                <dt id="comiket_personal_name-seimei">
                                    お申込者<span>必須</span>
                                </dt>
                                <dd <?php if (isset($e) && ($e->hasErrorForId('comiket_personal_name-seimei'))) { echo ' class="form_error"'; } ?>>
                                <span class="comiket_personal_name_sei-lbl" style="display: none;"></span>&nbsp;
                                <span class="comiket_personal_name_mei-lbl" style="display: none;"></span>
                                姓<input class="" style="width: 30%;" maxlength="8" autocapitalize="off" inputmode="comiket_personal_name_sei" name="comiket_personal_name_sei" data-pattern="" placeholder="例）佐川" type="text" value="<?php echo $updateInfo001Out->comiket_personal_name_sei(); ?>">
                                名<input class="" style="width: 30%;" maxlength="8" autocapitalize="off" inputmode="comiket_personal_name_mei" name="comiket_personal_name_mei" data-pattern="" placeholder="例）花子" type="text" value="<?php echo $updateInfo001Out->comiket_personal_name_mei(); ?>">
                                <div class="disp_comiket disp_gooutcamp" style="display: none;">
                                    <br>
                                    <strong class="red">※ 法人の場合は、姓のみ入力してください。</strong>
                                </div>

                                </dd>
                            </dl>
                            
                            <dl>
                                <dt id="comiket_zip">
                                    郵便番号<span>必須</span>
                                </dt>

                                <dd <?php if (isset($e) && ($e->hasErrorForId('comiket_zip'))) { echo ' class="form_error"'; } ?>>
                                    <span class="zip_mark1">〒</span><span class="comiket_zip1-lbl" style="display: none;"></span>
                                    <input autocapitalize="off" class="w_70 number-only" maxlength="3" inputmode="numeric" name="comiket_zip1" data-pattern="^\d+$" placeholder="例）136" type="text" value="<?php echo $updateInfo001Out->comiket_zip1(); ?>" style="">
                                    <span class="zip_mark2">-</span>
                                    <span class="comiket_zip1-str" style="display: none;">
                                    </span>
                                    <span class="comiket_zip2-lbl" style="display: none;"></span>
                                    <input autocapitalize="off" class="w_70 number-only" maxlength="4" inputmode="numeric" name="comiket_zip2" data-pattern="^\d+$" placeholder="例）0082" type="text" value="<?php echo $updateInfo001Out->comiket_zip2(); ?>" style="">
                                    <input class="m110" name="adrs_search_btn" type="button" value="住所検索" style="">
                                    <span style="font-size: 12px; display: inline-block;" class="forget-address-discription">
                                        　※郵便番号が不明な方は<a style="text-decoration: underline" target="_blank" href="http://www.post.japanpost.jp/zipcode/">こちら...</a>
                                    </span>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_pref">
                                    都道府県<span>必須</span>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('comiket_pref')) { echo ' class="form_error"'; } ?>>
                                    <span class="comiket_pref_nm-lbl"><?php //echo $eve001Out->comiket_pref_nm();?></span>
                                    <select name="comiket_pref_cd_sel">
                                        <option value="">選択してください</option>
                                        <?php
                                                echo Sgmov_View_Event_InputUpdateInfo::_createPulldown($updateInfo001Out->comiket_pref_cds(), $updateInfo001Out->comiket_pref_lbls(), $updateInfo001Out->comiket_pref_cd_sel());
                                        ?>
                                    </select>
                                </dd>
                            </dl>
                            
                            <dl>
                                <dt id="comiket_address">
                                    市区町村<span>必須</span>
                                </dt>
                                <dd <?php if (isset($e) && $e->hasErrorForId('comiket_address')) { echo ' class="form_error"'; } ?>>
                                    <span class="comiket_address-lbl" style="display: none;"></span>
                                    <input name="comiket_address" style="width: 80%;" maxlength="14" placeholder="例）江東区新砂" type="text" value="<?php echo $updateInfo001Out->comiket_address(); ?>">
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_building">
                                    番地・建物名・部屋番号<span>必須</span>
                                </dt>
                                <dd <?php if (isset($e) && $e->hasErrorForId('comiket_building')) { echo ' class="form_error"'; } ?>>
                                    <span class="comiket_building-lbl" style="display: none;"></span>
                                    <input name="comiket_building" style="width: 80%;" maxlength="30" type="text" value="<?php echo $updateInfo001Out->comiket_building(); ?>">
                                </dd>
                            </dl>
                            <dl>
                                <dt id="comiket_tel">
                                    電話番号<span>必須</span>
                                </dt>
                                <dd <?php if (isset($e) && $e->hasErrorForId('comiket_tel')) { echo ' class="form_error"'; } ?>>
                                    <span class="comiket_tel-lbl" style="display: none;"></span>
                                    <input name="comiket_tel" autocomplete="new-password" class="number-p-only" type="text" maxlength="15" placeholder="例）080-1111-2222" data-pattern="^[0-9-]+$" value="<?php echo $updateInfo001Out->comiket_tel(); ?>" style="">
                                </dd>
                            </dl>
                            
                            <dl>
                                <dt id="top_password_old">
                                    現パスワード<?php if ($object['password_update_flag'] == 1) { ?><span>必須</span> <?php } ?>
                                </dt>
                                <dd <?php if (isset($e) && $e->hasErrorForId('top_password_old')) { echo ' class="form_error"'; } ?>>
                                    <input class="user-pass" style="width:40%;" autocapitalize="off" autocomplete="new-password"  name="password_old" id="password_old" placeholder="" type="password" value="<?php echo $updateInfo001Out->password_old(); ?>" />
                                    <i class="bi bi-eye-slash" style="margin-left:-25px;cursor: pointer;" id="togglePasswordOld"></i>
                                </dd>
                            </dl>
                            
                            <dl>
                                <dt id="top_password">
                                    新パスワード<?php if ($object['password_update_flag'] == 1) { ?><span>必須</span> <?php } ?>
                                </dt>
                                <dd style="padding-top: 10px;" <?php if (isset($e) && $e->hasErrorForId('top_password')) { echo ' class="form_error"'; } ?>>
                                    <div style="margin-bottom: 9px;">
                                        <span class="red">
                                            ※パスワードは8文字以上で英大文字、英小文字、数字の全てを含むパスワードを設定して下さい。<br/>
                                        </span>
                                    </div>
                                    <input class="user-pass" style="width:40%;" autocapitalize="off" autocomplete="new-password" name="password" id="password" placeholder="" type="password" value="<?php echo $updateInfo001Out->password(); ?>" />
                                    <i class="bi bi-eye-slash" style="margin-left:-25px;cursor: pointer;" id="togglePassword"></i>
                                </dd>
                            </dl>
                            
                            <dl>
                                <dt id="top_password_confirm">
                                    パスワードの確認入力<?php if ($object['password_update_flag'] == 1) { ?><span>必須</span><?php } ?>
                                </dt>
                                <dd <?php if (isset($e) && $e->hasErrorForId('top_password_confirm')) { echo ' class="form_error"'; } ?>>
                                    <div class="user-pass" style="width:40%;float:left;">
                                        <input style="width:100%;" autocapitalize="off"  name="password_confirm" id="password_confirm" placeholder="" type="password" value="<?php echo $updateInfo001Out->password_confirm(); ?>" />
                                        <i class="bi bi-eye-slash" style="margin-left:-25px;cursor: pointer;" id="togglePasswordConfirm"></i>
                                    </div>
                                    
                                    <div class="event-txt-left" style="float:left; width: 58%; margin-left: 5px;">
                                            ※確認のためもう一度、コピーせず直接入力してください<br/>
                                            （コピー・貼り付けはしないでください。）
                                    </div>
                                    
                                </dd>
                            </dl>
                        </div>
                    </div>

                    <div class="btn_area">
                        <input class="event-btn-submit-en" style="padding: 17px 0px;"  type="submit" name="btnUpdateInfo" value="入力内容を登録する"/> <br/>
                        <?php if ($object['password_update_flag'] == 0) {?>
                            <a class="event-btn-back-2" onclick='backUserHistory("<?php echo $sessionData['event_name']; ?>");'>戻る</a>
                        <?php } ?>
                    
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--main-->
<?php
    $footerSettings = 'under';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/footer.php';
?>
    <script>
        const togglePasswordOld = document.querySelector("#togglePasswordOld");
        const passwordOld = document.querySelector("#password_old");

        togglePasswordOld.addEventListener("click", function () {
            // toggle the type attribute
            const type = passwordOld.getAttribute("type") === "password" ? "text" : "password";
            passwordOld.setAttribute("type", type);
            // toggle the icon
            this.classList.toggle("bi-eye");
        });
        
        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#password");

        togglePassword.addEventListener("click", function () {
            // toggle the type attribute
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            // toggle the icon
            this.classList.toggle("bi-eye");
        });
        
        const togglePasswordConfirm = document.querySelector("#togglePasswordConfirm");
        const password_confirm = document.querySelector("#password_confirm");

        togglePasswordConfirm.addEventListener("click", function () {
            // toggle the type attribute
            const type = password_confirm.getAttribute("type") === "password" ? "text" : "password";
            password_confirm.setAttribute("type", type);
            // toggle the icon
            this.classList.toggle("bi-eye");
        });
        
        function backUserHistory(event_nm) {
            location.href="inputHistory?event_nm=" + event_nm ;
        }
        

    </script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/ajax_searchaddress.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/event/js/input.js?<?php echo $strSysdate; ?>"></script>
    
</body>
</html>