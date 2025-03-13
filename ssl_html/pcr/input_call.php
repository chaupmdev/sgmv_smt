<?php
/**
 * 手荷物受付サービスのお申し込み入力画面を表示します。
 * @package    ssl_html
 * @subpackage PCR
 * @author     M.Kokawa(SCS)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
//メンテナンス期間チェック
require_once dirname(__FILE__) . '/../../lib/component/maintain_pcr_call.php';
// 現在日時
$nowDate = new DateTime('now');
if ($main_stDate_pcr_call <= $nowDate && $nowDate <= $main_edDate_pcr_call) {
    header("Location: /maintenance.php");
    exit;
}
// Basic Authen
require_once dirname(__FILE__) . '/../../lib/component/auth_pcr.php';

require_once dirname(__FILE__) . '/../../lib/Lib.php';
Sgmov_Lib::useView('pcr/Input');
/**#@-*/

// 処理を実行
$view = new Sgmov_View_Pcr_Input();
$forms = $view->execute();

/**
 * チケット
 * @var string
 */
$ticket = $forms['ticket'];
$featureId = $forms['featureId'];

/**
 * フォーム
 * @var Sgmov_Form_Pcr001Out
 */
$pcr001Out = $forms['outForm'];

/**
 * エラーフォーム
 * @var Sgmov_Form_Error
 */
$e = $forms['errorForm'];

    // スマートフォン・タブレット判定
    $detect = new MobileDetect();
    $isSmartPhone = $detect->isMobile();
    if ($isSmartPhone) {
        $inputTypeEmail  = 'email';
        $inputTypeNumber = 'number';
    } else {
        $inputTypeEmail  = 'text';
        $inputTypeNumber = 'text';
    }
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
    <meta name="Description" content="旅客手荷物受付サービスのお申し込み（個人用）のご案内です。[旅行カバン等のお荷物承ります]フォームにもれなくご入力をお願いいたします。前日23時までにお支払の確認ができたお客様につきましては、翌日より集荷が可能となります。" />
    <meta name="author" content="SG MOVING Co.,Ltd" />
    <meta name="copyright" content="SG MOVING Co.,Ltd All rights reserved." />
    <title>旅客手荷物受付サービスのお申し込み（個人用）｜お問い合わせ│ＳＧムービング株式会社＜ＳＧホールディングスグループ＞</title>
    <link href="/misc/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/css/common.css" rel="stylesheet" type="text/css" />
    <link href="/css/form.css" rel="stylesheet" type="text/css" />
    <!--[if lt IE 9]>
    <link href="/css/form_ie8.css" rel="stylesheet" type="text/css" />
    <![endif]-->
    <script src="/js/ga.js" type="text/javascript"></script>
</head>
<body>
<?php
    $gnavSettings = 'contact';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/header.php';
?>
    <div id="breadcrumb">
        <ul class="wrap">
            <li><a href="/">ホーム</a></li>
            <li><a href="/contact/">お問い合わせ</a></li>
            <li class="current">旅客手荷物受付サービスのお申し込み</li>
        </ul>
    </div>
    <div id="main">
        <div class="wrap clearfix">
            <h1 class="page_title">旅客手荷物受付サービスのお申し込み</h1>
            <p class="sentence">
<!--                受付申込みは、<span style="color: red; font-weight: bold">乗船19日前から申し込み可能</span>となります。対象日より項目が表示されます。
                <br/>
                対応客船：2023年度ダイヤモンド・プリンセス全日程<br/>
                　　　　　2023年度クイーン・エリザベス全日程<br/>
                　　　　　2023年度MSCベリッシマ全日程-->
            </p>
            <p class="sentence">
                以下のフォームにもれなくご入力をお願いいたします。
                <br />※前日23時までにお支払の確認ができたお客様につきましては、翌日より集荷が可能となります。
                <br />
                <span class="red">
                    ※迷惑メールを設定されている方は「sagawa-mov.co.jp」を受信する設定にしてください。
                    <br />詳しくは<a href="#bounce_mail">こちら</a>
                </span>
            </p>

<?php
    if (isset($e) && $e->hasError()) {
?>
            <div class="err_msg">
                <p class="sentence br attention">[!ご確認ください]下記の項目が正しく入力・選択されていません。</p>
                <ul>
<?php
        // エラー表示
//        if ($e->hasErrorForId('top_surname')) {
//            echo '<li><a href="#name">お名前 姓' . $e->getMessage('top_surname') . '</a></li>';
//        }
//        if ($e->hasErrorForId('top_forename')) {
//            echo '<li><a href="#name">お名前 名' . $e->getMessage('top_forename') . '</a></li>';
//        }
        if ($e->hasErrorForId('top_surname_furigana')) {
            echo '<li><a href="#furigana">フリガナ 姓' . $e->getMessage('top_surname_furigana') . '</a></li>';
        }
        if ($e->hasErrorForId('top_forename_furigana')) {
            echo '<li><a href="#furigana">フリガナ 名' . $e->getMessage('top_forename_furigana') . '</a></li>';
        }
//        if ($e->hasErrorForId('top_number_persons')) {
//            echo '<li><a href="#number_persons">同行のご家族人数' . $e->getMessage('top_number_persons') . '</a></li>';
//        }
        if ($e->hasErrorForId('top_tel')) {
            echo '<li><a href="#tel">電話番号' . $e->getMessage('top_tel') . '</a></li>';
        }
        if ($e->hasErrorForId('top_mail')) {
            echo '<li><a href="#mail">メールアドレス' . $e->getMessage('top_mail') . '</a></li>';
        }
        if ($e->hasErrorForId('top_retype_mail')) {
            echo '<li><a href="#retype_mail">アドレス確認' . $e->getMessage('top_retype_mail') . '</a></li>';
        }
        if ($e->hasErrorForId('top_zip')) {
            echo '<li><a href="#zip">郵便番号' . $e->getMessage('top_zip') . '</a></li>';
        }
        if ($e->hasErrorForId('top_pref_cd_sel')) {
            echo '<li><a href="#pref">都道府県' . $e->getMessage('top_pref_cd_sel') . '</a></li>';
        }
        if ($e->hasErrorForId('top_address')) {
            echo '<li><a href="#address">市区町村' . $e->getMessage('top_address') . '</a></li>';
        }
        if ($e->hasErrorForId('top_building')) {
            echo '<li><a href="#building">番地・建物名' . $e->getMessage('top_building') . '</a></li>';
        }
        if ($e->hasErrorForId('top_travel_agency_cd_sel')) {
            echo '<li><a href="#travel_agency">船名' . $e->getMessage('top_travel_agency_cd_sel') . '</a></li>';
        }
        
        if ($e->hasErrorForId('top_call_operator_id_cd_sel')) {
            echo '<li><a href="#call_operator_id">コールセンターオペレーターID' . $e->getMessage('top_call_operator_id_cd_sel') . '</a></li>';
        }
        
        if ($e->hasErrorForId('top_travel_cd_sel')) {
            echo '<li><a href="#tour_name">乗船日' . $e->getMessage('top_travel_cd_sel') . '</a></li>';
        }
        if ($e->hasErrorForId('top_room_number')) {
            echo '<li><a href="#room_number">船内のお部屋番号' . $e->getMessage('top_room_number') . '</a></li>';
        }
        if ($e->hasErrorForId('top_terminal')) {
            echo '<li><a href="#terminal">集荷の往復' . $e->getMessage('top_terminal') . '</a></li>';
        }
        if ($e->hasErrorForId('top_departure_quantity')) {
            echo '<li><a href="#departure_quantity">配送荷物個数 往路' . $e->getMessage('top_departure_quantity') . '</a></li>';
        }
        if ($e->hasErrorForId('top_arrival_quantity')) {
            echo '<li><a href="#arrival_quantity">配送荷物個数 復路' . $e->getMessage('top_arrival_quantity') . '</a></li>';
        }
        if ($e->hasErrorForId('top_travel_departure_cd_sel')) {
            echo '<li><a href="#travel_departure">出発地' . $e->getMessage('top_travel_departure_cd_sel') . '</a></li>';
        }
        if ($e->hasErrorForId('top_cargo_collection_date')) {
            echo '<li><a href="#cargo_collection_date">集荷希望日時' . $e->getMessage('top_cargo_collection_date') . '</a></li>';
        }
        if ($e->hasErrorForId('top_cargo_collection_st_time')) {
            //echo '<li><a href="#cargo_collection_date">集荷希望開始時刻' . $e->getMessage('top_cargo_collection_st_time') . '</a></li>';
            echo '<li><a href="#cargo_collection_date">集荷希望時刻' . $e->getMessage('top_cargo_collection_st_time') . '</a></li>';
        }
        if (!$e->hasErrorForId('top_cargo_collection_st_time') && $e->hasErrorForId('top_cargo_collection_ed_time')) {
            //echo '<li><a href="#cargo_collection_date">集荷希望終了時刻' . $e->getMessage('top_cargo_collection_ed_time') . '</a></li>';
            echo '<li><a href="#cargo_collection_date">集荷希望時刻' . $e->getMessage('top_cargo_collection_ed_time') . '</a></li>';
        }
        if ($e->hasErrorForId('top_cargo_collection_st_time_last')) {
            echo '<li><a href="#cargo_collection_date">' . $e->getMessage('top_cargo_collection_st_time_last') . '</a></li>';
        }
        if (!$e->hasErrorForId('top_cargo_collection_st_time_last') && $e->hasErrorForId('top_cargo_collection_ed_time_last')) {
            echo '<li><a href="#cargo_collection_date">' . $e->getMessage('top_cargo_collection_ed_time_last') . '</a></li>';
        }
        if ($e->hasErrorForId('top_travel_arrival_cd_sel')) {
            echo '<li><a href="#travel_arrival">到着地' . $e->getMessage('top_travel_arrival_cd_sel') . '</a></li>';
        }
        if ($e->hasErrorForId('top_payment_method_cd_sel')) {
            echo '<li><a href="#payment_method">お支払い方法' . $e->getMessage('top_payment_method_cd_sel') . '</a></li>';
        }
        if ($e->hasErrorForId('top_payment_method_cd_sel_convenience')) {
            echo '<li><a href="#payment_method">' . $e->getMessage('top_payment_method_cd_sel_convenience') . '</a></li>';
        }
        if ($e->hasErrorForId('top_travel_cd_sel_convenience')) {
            echo '<li><a href="#payment_method">' . $e->getMessage('top_travel_cd_sel_convenience') . '</a></li>';
        }
        if ($e->hasErrorForId('top_convenience_store_cd_sel')) {
            echo '<li><a href="#convenience">お支払い店舗' . $e->getMessage('top_convenience_store_cd_sel') . '</a></li>';
        }
        if ($e->hasErrorForId('complete_back_call_api_Ivr')) {
            echo '<li><a href="#complete_back_call_api_Ivr">' . $e->getMessage('complete_back_call_api_Ivr') . '</a></li>';
        }
?>

                </ul>
                <p class="under">
                    インターネットお申込みについてのお問合せは、
                    <br />SGムービング株式会社TOKYO BASE　　TEL：03-6850-7828　（受付時間平日9時～17時）
                    <br />メールでのお問合せ・ご質問については、<a href="<?php echo Sgmov_Component_Config::getUrlPublicSsl(); ?>/pin/input" target = "_brank">こちら</a>からお願いします。
                </p>
            </div>

<?php
    }
?>
            <div class="section other">
                <form action="/pcr/check_input" data-feature-id="<?php echo $featureId; ?>" data-id="<?php echo Sgmov_View_Pcr_Common::GAMEN_ID_PCR001 ?>" method="post">
                    <input name="ticket" type="hidden" value="<?php echo $ticket ?>" />
                    <input name="req_flg" type="hidden" value="1" />
                    <input name="site_flg" type="hidden" value="2" /><!--那覇版-->
                    <div class="section">

                        <div class="dl_block">
                            <dl>
                                <dt id="call_operator_id">
                                    コールセンターオペレーターID
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('top_call_operator_id_cd_sel')) { echo ' class="form_error"'; } ?>>
                                    <select name="call_operator_id_cd_sel">
                                        <option value="">選択してください</option>
<?php
        echo Sgmov_View_Pcr_Input::_createPulldown($pcr001Out->call_operator_id_cds(), $pcr001Out->call_operator_id_lbls(), $pcr001Out->call_operator_id_cd_sel());
?>
                                    </select>
                                </dd>
                            </dl>
                            
                            <dl>
                                <dt id="travel_agency">
                                    船名
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('top_travel_agency_cd_sel')) { echo ' class="form_error"'; } ?>>
                                    <select name="travel_agency_cd_sel">
                                        <option value="">選択してください</option>
<?php
        echo Sgmov_View_Pcr_Input::_createPulldown($pcr001Out->travel_agency_cds(), $pcr001Out->travel_agency_lbls(), $pcr001Out->travel_agency_cd_sel());
?>
                                    </select>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="tour_name">
                                    乗船日/下船日
                                    <br />
                                    <p>※表示のない港はお取り扱いはありません</p>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('top_travel_cd_sel')) { echo ' class="form_error"'; } ?>>
                                    <select name="travel_cd_sel">
                                        <option value="">選択してください</option>
<?php
        echo Sgmov_View_Pcr_Input::_createPulldown($pcr001Out->travel_cds(), $pcr001Out->travel_lbls(), $pcr001Out->travel_cd_sel());
?>
                                    </select>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="room_number">
                                    船内のお部屋番号
                                    <br />
                                    <p>※半角英数字</p>
                                </dt>
                                <dd <?php if (isset($e) && $e->hasErrorForId('top_room_number')) { echo ' class="form_error"'; } ?> style="display: flex; align-items: center;">
                                    <input autocapitalize="off" class="w_70" inputmode="verbatim" maxlength="6" name="room_number" data-pattern="^\w+$" placeholder="例）A123" type="text" value="<?php echo $pcr001Out->room_number();?>" style="margin-right: 5px; width: 100px;"/>
                                    <span style="font-size:12px; white-space: nowrap; line-height: 1.8;">
                                        &#12288;※お部屋番号が決まっていない場合は、未入力でお願い致します。
                                        <br>&#12288;※申し込み期間内で部屋番号が確定しない場合は、クルーズご予約先へお問合せください。
                                    </span>
                                </dd>
                            </dl>
                            
                            <dl style="display:none;">
                                <dt id="name">
                                    お名前
                                </dt>
                                <dd<?php
    if (isset($e)
       && ($e->hasErrorForId('top_surname') || $e->hasErrorForId('top_forename'))
    ) {
        echo ' class="form_error"';
    }
                                ?>>
                                    <input autocapitalize="off" class="w_100" maxlength="30" name="surname" placeholder="例）佐川" type="text" value="<?php echo $pcr001Out->surname();?>" />
                                    <input autocapitalize="off" class="w_100" maxlength="30" name="forename" placeholder="例）花子" type="text" value="<?php echo $pcr001Out->forename();?>" />
                                </dd>
                            </dl>
                            
                            <dl>
                                <dt id="furigana">
                                    お名前フリガナ
                                </dt>
                                <dd<?php
    if (isset($e)
       && ($e->hasErrorForId('top_surname_furigana') || $e->hasErrorForId('top_forename_furigana'))
    ) {
        echo ' class="form_error"';
    }
                                ?>>
                                    <input autocapitalize="off" class="w_100" maxlength="30" name="surname_furigana" placeholder="例）サガワ" type="text" value="<?php echo $pcr001Out->surname_furigana();?>" />
                                    <input autocapitalize="off" class="w_100" maxlength="30" name="forename_furigana" placeholder="例）ハナコ" type="text" value="<?php echo $pcr001Out->forename_furigana();?>" />
                                </dd>
                            </dl>
                            <dl style="display: none">
                                <dt id="number_persons">
                                    同行のご家族人数
                                    <p>※ご本人を含む合計人数</p>
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('top_number_persons')) { echo ' class="form_error"'; } ?>>
                                    <input autocapitalize="off" class="w_60" inputmode="numeric" maxlength="3" name="number_persons" data-pattern="^\d+$" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $pcr001Out->number_persons();?>" />
                                    名
                                </dd>
                            </dl>
                            <dl>
                                <dt id="tel">
                                    電話番号
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('top_tel')) { echo ' class="form_error"'; } ?>>
                                    <input autocapitalize="off" class="w_70" inputmode="numeric" maxlength="18" name="tel1" data-pattern="^\d+$" placeholder="例）090" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $pcr001Out->tel1();?>" />
                                    -
                                    <input autocapitalize="off" class="w_70" inputmode="numeric" maxlength="18" name="tel2" data-pattern="^\d+$" placeholder="例）1234" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $pcr001Out->tel2();?>" />
                                    -
                                    <input autocapitalize="off" class="w_70" inputmode="numeric" maxlength="18" name="tel3" data-pattern="^\d+$" placeholder="例）5678" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $pcr001Out->tel3();?>" />
                                    
                                    <p>※所有する携帯電話がない方は、固定電話を入力ください。</p>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="mail">
                                    メールアドレス
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('top_mail')) { echo ' class="form_error"'; } ?>>
                                    <input class="w_220" autocapitalize="off" inputmode="email" name="mail" data-pattern="^[!-~]+$" placeholder="例）ringo@sagawa.com" type="<?php echo $inputTypeEmail; ?>" value="<?php echo $pcr001Out->mail();?>" />
                                    <br class="sp_only" />
                                    <p>※申込完了の際に申込完了メールを送付させていただきますので、ご入力ください。</p>
                                    <p class="red">
                                        ※迷惑メールを設定されている方は「sagawa-mov.co.jp」を受信する設定にしてください。
                                        <br />詳しくは
                                        <a href="#bounce_mail">こちら</a>
                                    </p>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="retype_mail">
                                    アドレス確認
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('top_retype_mail')) { echo ' class="form_error"'; } ?>>
                                    <input class="w_220" autocapitalize="off" inputmode="email" name="retype_mail" data-pattern="^[!-~]+$" placeholder="例）ringo@sagawa.com" type="<?php echo $inputTypeEmail; ?>" value="<?php echo $pcr001Out->retype_mail();?>" />
                                </dd>
                            </dl>
                            <dl>
                                <dt id="zip">
                                    郵便番号
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('top_zip')) { echo ' class="form_error"'; } ?>>
                                    <input autocapitalize="off" class="w_70" inputmode="numeric" name="zip1" data-pattern="^\d+$" placeholder="例）136" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $pcr001Out->zip1();?>" />
                                    -
                                    <input autocapitalize="off" class="w_70" inputmode="numeric" name="zip2" data-pattern="^\d+$" placeholder="例）0082" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $pcr001Out->zip2();?>" />
                                    <input class="m110" name="adrs_search_btn" type="button" value="住所検索" />
                                        <span style="font-size:12px;">
                                            &#12288;※郵便番号が不明な方は<a style="text-decoration: underline" target="_blank" href="http://www.post.japanpost.jp/zipcode/">こちら...</a>
                                    </span>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="pref">
                                    都道府県
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('top_pref_cd_sel')) { echo ' class="form_error"'; } ?>>
                                    <select name="pref_cd_sel">
                                        <option value="">選択してください</option>
<?php
        echo Sgmov_View_Pcr_Input::_createPulldown($pcr001Out->pref_cds(), $pcr001Out->pref_lbls(), $pcr001Out->pref_cd_sel());
?>
                                    </select>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="address">
                                    市区町村
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('top_address')) { echo ' class="form_error"'; } ?>>
                                    <input class="w_220" autocapitalize="off" maxlength="40" name="address" placeholder="例）江東区新砂" type="text" value="<?php echo $pcr001Out->address();?>" />
                                </dd>
                            </dl>
                            <dl>
                                <dt id="building">
                                    番地・建物名
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('top_building')) { echo ' class="form_error"'; } ?>>
                                    <input class="w_220" autocapitalize="off" maxlength="40" name="building" placeholder="例）1-8-2" type="text" value="<?php echo $pcr001Out->building();?>" />
                                </dd>
                            </dl>
                            <dl>
                                <dt id="terminal">
                                    集荷の往復
                                </dt>
                                <dd<?php if (isset($e) && $e->hasErrorForId('top_terminal')) { echo ' class="form_error"'; } ?>>
                                    <label class="radio-label" for="terminal1">
                                        <input<?php if ($pcr001Out->terminal_cd_sel() === '1') echo ' checked="checked"'; ?> id="terminal1" name="terminal_cd_sel" type="radio" value="1" />
                                        往路のみ
                                    </label>
                                    <label class="radio-label radio-label-arrival" for="terminal2" dispnone_arrival_travel_agency_id_list="<?= @implode(',', $pcr001Out->dispnone_arrival_travel_agency_id_list()) ?>">
                                        <input<?php if ($pcr001Out->terminal_cd_sel() === '2') echo ' checked="checked"'; ?> id="terminal2" name="terminal_cd_sel" type="radio" value="2" />
                                        復路のみ
                                    </label>
                                    <label class="radio-label" for="terminal3">
                                        <input<?php if ($pcr001Out->terminal_cd_sel() !== '1' && $pcr001Out->terminal_cd_sel() !== '2') echo ' checked="checked"'; ?> id="terminal3" name="terminal_cd_sel" type="radio" value="3" />
                                        往復
                                    </label>
                                </dd>
                            </dl>
                            <dl>
                                <dt id="quantity">
                                    配送荷物個数
                                    <br class="sp_only" /><p>※注文完了後は個数変更ができないため、変更する場合は再度入力をお願いいたします。</p>
                                </dt>
                                <dd<?php
    if (isset($e)
        && ($e->hasErrorForId('top_departure_quantity') || $e->hasErrorForId('top_arrival_quantity'))
    ) {
        echo ' class="form_error"';
    }
                                ?> id="quantity_number">
                                    <label class="departure" for="departure_quantity">
                                        往路
                                        <input autocapitalize="off" class="w_60" id="departure_quantity" maxlength="3" name="departure_quantity" data-pattern="^\d+$" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $pcr001Out->departure_quantity(); ?>" />
                                        個
                                    </label>
                                    <label class="arrival" for="arrival_quantity">
                                        復路
                                        <input autocapitalize="off" class="w_60" id="arrival_quantity" maxlength="3" name="arrival_quantity" data-pattern="^\d+$" type="<?php echo $inputTypeNumber; ?>" value="<?php echo $pcr001Out->arrival_quantity(); ?>" />
                                        個
                                    </label>
                                </dd>
                            </dl>
                            <dl>
                                <dt class="departure" id="travel_departure">
                                    出発地
                                </dt>
                                <dd class="departure<?php if (isset($e) && $e->hasErrorForId('top_travel_departure_cd_sel')) { echo ' form_error'; } ?>">
                                    <select name="travel_departure_cd_sel">
                                        <option value="">選択してください</option>
<?php
    echo Sgmov_View_Pcr_Input::_createPulldownAddDate($pcr001Out->travel_departure_cds(), $pcr001Out->travel_departure_lbls(), $pcr001Out->travel_departure_cd_sel(), $pcr001Out->travel_departure_dates());
?>
                                    </select>
                                </dd>
                            </dl>
                            <dl>
                                <dt class="condition_02 departure" id="cargo_collection">
                                    集荷希望日時
                                    <br class="sp_only" /><p>※乗船日に合わせてお荷物を配送させて頂きます。</p>
                                </dt>
                                <dd class="departure<?php
        if (isset($e)
            && ($e->hasErrorForId('top_cargo_collection_date')
                || $e->hasErrorForId('top_cargo_collection_st_time')
                || $e->hasErrorForId('top_cargo_collection_ed_time')
                || $e->hasErrorForId('top_cargo_collection_st_time_last')
                || $e->hasErrorForId('top_cargo_collection_ed_time_last')
            )
        ) {
            echo ' form_error';
        }
                                    ?>" id="cargo_collection_date">
                                    <p>&nbsp;</p>
                                    <select name="cargo_collection_date_year_cd_sel">
                                        <option value="">年を選択</option>
<?php
        echo Sgmov_View_Pcr_Input::_createPulldown($pcr001Out->cargo_collection_date_year_cds(), $pcr001Out->cargo_collection_date_year_lbls(), $pcr001Out->cargo_collection_date_year_cd_sel());
?>
                                    </select>
                                    年
                                    <select name="cargo_collection_date_month_cd_sel">
                                        <option value="">月を選択</option>
<?php
        echo Sgmov_View_Pcr_Input::_createPulldown($pcr001Out->cargo_collection_date_month_cds(), $pcr001Out->cargo_collection_date_month_lbls(), $pcr001Out->cargo_collection_date_month_cd_sel());
?>
                                    </select>
                                    月
                                    <select name="cargo_collection_date_day_cd_sel">
                                        <option value="">日を選択</option>
<?php
        echo Sgmov_View_Pcr_Input::_createPulldown($pcr001Out->cargo_collection_date_day_cds(), $pcr001Out->cargo_collection_date_day_lbls(), $pcr001Out->cargo_collection_date_day_cd_sel());
?>
                                    </select>
                                    日
                                    <select name="cargo_collection_st_time_cd_sel">
                                        <option value="">時間帯を選択</option>
<?php
        echo Sgmov_View_Pcr_Input::_createPulldown($pcr001Out->cargo_collection_st_time_cds(), $pcr001Out->cargo_collection_st_time_lbls(), $pcr001Out->cargo_collection_st_time_cd_sel());
?>
                                    </select>
                                    <br/><br/><!--<span style="color: red;font-weight: bold">※北海道にお住まいのお客様は最終集荷日は、選択しないでください。</span>-->
                                    <div class="">
                                        <br>
                                        ※<span class="red">北海道にお住まいのお客様</span>は、最終集荷日<span class="red">前日まで</span>の日程を選択ください。
                                        <br>
                                        <br>
                                        ※<span class="red">沖縄県にお住まいのお客様</span>は、最終集荷日<span class="red">3日前まで</span>の日程を選択ください。
                                        <br/>
                                        <br/>
                                        <span>受付後であっても、ご希望集荷日で乗船日までにお届けが間に合わない見込みがある場合は、</span>
                                        <br>
                                        <span>集荷日変更のご相談を差し上げますのでご承知おきください。</span>
                                        
                                    </div>
                                </dd>
                                
                            </dl>
                            <dl>
                                <dt class="arrival" id="travel_arrival">
                                    到着地
                                </dt>
                                <dd class="arrival<?php if (isset($e) && $e->hasErrorForId('top_travel_arrival_cd_sel')) { echo ' form_error'; } ?>">
                                    <select name="travel_arrival_cd_sel">
                                        <option value="">選択してください</option>
<?php
        echo Sgmov_View_Pcr_Input::_createPulldown($pcr001Out->travel_arrival_cds(), $pcr001Out->travel_arrival_lbls(), $pcr001Out->travel_arrival_cd_sel());
?>
                                    </select>
                                </dd>
                            </dl>
                        </div>
                    </div>

                    <!--▼往復便ご利用のお客様の復路発送ここから-->
                    <div class="gray_block">
                        <strong>【外国籍船をご利用のお客様】</strong>
                        <strong>往復便ご利用のお客様の復路発送</strong>
                        <ul class="disc_ul">
                            <li>
                                往復便ご利用のお客様は、下船日当日にターミナルで受付いたします。復路用伝票のご記入は不要ですので、お客様のお名前をターミナル内SGムービング受付カウンター係員にお申し付けください。
                            </li>
                            <li>
                                お荷物が増えた場合は下船日当日の追加お申し込みも承ります。ターミナル内SGムービング受付カウンターへお越しください。伝票をお渡しいたします。追加分の配送代金は現金でお支払いください。
                            </li>
                        </ul>
                        <strong>片道便(復路)のお申し込み</strong>
                        <ul class="disc_ul">
                            <li>
                                復路便のお申し込みも事前にインターネットでお申し込みされると港での面倒な手続きを省略できます(下船日当日のターミナルでの受付はかなりの混雑が予想されます)。復路便のみお申し込みのお客様の伝票は、下船後にターミナルのSGムービング受付カウンターでお渡しいたします。
                            </li>
                            <li>
                                事前のお申し込みがなく復路便のみのご利用は、下船日当日にターミナルで受付いたします。
                                <br />事前にお申し込みされていないお客様の伝票は受付カウンターにご用意しております。配送代金は受付時に現金でお支払いください。
                            </li>
                        </ul>
                        <br/>
                    <!--<strong>【日本籍船をご利用のお客様】</strong>
                        <strong>往復便ご利用のお客様の復路発送</strong>
                        <ul class="disc_ul">
                            <li>
                                往復便ご利用のお客様は、乗船後お荷物に貼付されている復路用伝票をご確認ください。新たな手続きや受付は不要です。
                            </li>
                            <li>
                                お荷物が増えた場合、船内で追加分の受付をいたします。レセプションにてお申し出ください。
                            </li>
                        </ul>
                        <strong>片道便(復路)のお申し込み</strong>
                        <ul class="disc_ul">
                            <li>
                                お帰りのみのお荷物発送をご希望の場合、船内で受付をいたします。レセプションにてお申し出ください。
                                <br /><br />※下船港によりお取り扱いの無い場合があります。
                            </li>
                        </ul>-->
                    </div>
                    <!--▲往復便ご利用のお客様の復路発送ここまで-->
                    <!--▼お支払い方法-->
                    <div class="payment_method clearfix<?php if (isset($e) && ($e->hasErrorForId('top_convenience_store_cd_sel') ||$e->hasErrorForId('top_travel_cd_sel_convenience')) ) { echo ' form_error'; } ?>" id="payment_method">
                        <span>ご希望のお支払い方法をお選びください。</span>
                        <label class="radio-label" for="pay_card">
                            <input<?php if ($pcr001Out->payment_method_cd_sel() !== '1') echo ' checked="checked"'; ?> class="radio-btn" id="pay_card" name="payment_method_cd_sel" type="radio" value="2" />
                            クレジットカード
                        </label>
                        <label class="radio-label" for="pay_convenience_store">
                            <input<?php if ($pcr001Out->payment_method_cd_sel() === '1') echo ' checked="checked"'; ?> class="radio-btn" id="pay_convenience_store" name="payment_method_cd_sel" type="radio" value="1" />
                            コンビニ決済
                        </label>
                        <div id="convenience" style="display:none;">
                            <select name="convenience_store_cd_sel">
                                <option<?php if ($pcr001Out->convenience_store_cd_sel() !== '1' && $pcr001Out->convenience_store_cd_sel() !== '2' && $pcr001Out->convenience_store_cd_sel() !== '3') echo ' selected="selected"'; ?> value="">コンビニを選択してください</option>
                                <option<?php if ($pcr001Out->convenience_store_cd_sel() === '1') echo ' selected="selected"'; ?> value="1">セブンイレブン</option>
                                <option<?php if ($pcr001Out->convenience_store_cd_sel() === '2') echo ' selected="selected"'; ?> value="2">ローソン、セイコーマート、ファミリーマート、ミニストップ</option>
                                <option<?php if ($pcr001Out->convenience_store_cd_sel() === '3') echo ' selected="selected"'; ?> value="3">デイリーヤマザキ</option>
                            </select>
                        </div> 
                    </div>
                    <!--▲お支払い方法-->

                    <div class="attention_area">

                        <!--▼ご連絡メールが届かない場合ここから-->
                        <!--<div id="bounce_mail" class="accordion">
                            <h3 class="accordion_button">ご連絡メールが届かない場合</h3>
                            <div id="bounce_mail_contents" class="ac_content">
                                <p class="sentence">
                                    お申込み受付後、ご登録いただいたメールアドレスに「sgmoving_system@sagawa-mov.co.jp」より、自動で「旅客手荷物受付サービスのお申し込み受付のご連絡」のメールをお送りしております。
                                    <br />メールが届かない原因として、以下のことが考えられます。
                                </p>
                                <h4 class="ttl">入力されたメールアドレスを確認してください。</h4>
                                <p class="sentence">
                                    メールアドレスに入力されたメールアドレスに間違いがないか、ご利用可能なメールアドレスかをご確認ください。
                                </p>
                                <h4 class="ttl">メール受信制限設定を確認してください。</h4>
                                <p class="sentence">
                                    スマートフォンや携帯電話のメール設定でドメイン指定受信をされているお客さまは、受信できない場合がございますので、必ず「sagawa-mov.co.jp」を受信する設定にしてください。
                                    <br />ドメイン指定受信の設定に付きましては、以下の通りに設定してください。
                                </p>
                                <ul>
                                    <li class="btm30">
                                        <h5 class="ttl">【スマートフォンの設定方法】</h5>
                                        <p class="sentence">
                                            各キャリアのWEBサイトをご確認ください。
                                        </p>
                                        <p class="text_link">
                                            <a href="https://www.nttdocomo.co.jp/info/spam_mail/spmode/domain/" target="_blank">DoCoMo 受信／拒否設定</a>
                                        </p>
                                        </p>
                                        <p class="text_link">
                                            <a href="http://www.au.kddi.com/support/mobile/trouble/forestalling/mail/anti-spam/fillter/" target="_blank">au 迷惑メールフィルター機能</a>
                                        </p>
                                        </p>
                                        <p class="text_link">
                                            <a href="http://www.softbank.jp/mobile/support/antispam/settings/indivisual/whiteblack/" target="_blank">ソフトバンク 受信許可・拒否設定</a>
                                        </p>
                                    </li>

                                    <li>
                                        <h5 class="ttl">【携帯電話の設定方法】</h5>
                                        <ul>
                                            <li>
                                                <h6>【DoCoMo】</h6>
                                                <p class="sentence">
                                                    お手持ちの携帯からｉｍｏｄｅのトップページ（ｉMENU）にアクセス
                                                    <br />&#8594;料金＞お申し込み＞設定
                                                    <br />&#8594;ｉモード設定（オプション設定）
                                                    <br />&#8594;メール設定
                                                    <br />&#8594;迷惑メール対策
                                                    <br />&#8594;受信＞拒否設定
                                                    <br />&#8594;ステップ3、4で「sagawa-mov.co.jp」を入れてください。
                                                    <br />「かんたん設定」を行うと届かなくなる可能性があります。
                                                </p>
                                            </li>

                                            <li>
                                                <h6>【au】</h6>
                                                <p class="sentence">
                                                    お手持ちの携帯でメールフィルターを呼び出し
                                                    <br />&#8594;指定受信リスト設定
                                                    <br />&#8594;「sagawa-mov.co.jp」を受信可能に設定してください。
                                                    <br />「ＵＲＬ付メール受信拒否設定」「ＨＴＭＬメール受信拒否設定」につきましても合わせてご確認ください。
                                                </p>
                                            </li>

                                            <li>
                                                <h6>【softbank】</h6>
                                                <p class="sentence">
                                                    お手持ちの携帯からメニューリストにアクセス
                                                    <br />&#8594;My Softbank
                                                    <br />&#8594;各種変更手続き
                                                    <br />&#8594;オリジナルメール設定で「sagawa-mov.co.jp」を受信可能ドメインに設定してください。
                                                    <br />「ＵＲＬ付きリンク付きメール拒否設定」につきましても合わせてご確認ください。
                                                </p>
                                            </li>
                                        </ul>
                                    </li>

                                    <li>
                                        <h5 class="ttl">【Yahoo！メールの設定方法】</h5>
                                        <ul class="btm30">
                                            <li>
                                                Yahoo!メールトップページにアクセス
                                                <br />&#8594;トップページ右上の[メールオプション]をクリック
                                                <br />&#8594;表示されるページで[なりすましメール拒否設定]をクリック
                                                <br />&#8594;「リストに追加」欄で、「sagawa-mov.co.jp」を入力
                                                <br />&#8594;[リストに追加]ボタンをクリックしてください。
                                            </li>
                                        </ul>
                                    </li>
                                </ul>

                                <h4 class="ttl">迷惑メールフォルダ等に移動していないかを確認してください。</h4>
                                <p class="sentence">
                                    メールソフトやウィルス対策ソフトのフィルタ設定、プロバイダの迷惑メール対策等により、迷惑メールと判定されている可能性があります。
                                    <br />迷惑メールフォルダ等にお申し込み受付のご連絡メールが移動していないかご確認ください。
                                    <br />機能や設定方法、対策等につきましては、各社ホームページ等でご確認ください。
                                </p>

                                <h4 class="ttl">URLを含む電子メールが受信拒否になっていないか確認してください。</h4>
                                <p class="sentence btm30">
                                    本文にＵＲＬを含むメールを受信しない設定をされている場合、お申込み受付のご連絡メールを受信できない場合があります。
                                    <br />設定・解除方法等につきましては、ご利用の端末販売会社のホームページ等でご確認ください。
                                </p>
                            </div>
                        </div> -->
                        <!--▲ご連絡メールが届かない場合ここまで-->
                        <!--▼個人情報の取り扱いここから-->
                        <!--<div id="privacy_policy" class="accordion">
                            <h3 class="accordion_button">個人情報の取り扱い</h3>
                            <div id="privacy_contents" class="ac_content">
                                <p class="sentence">
                                    SGムービング株式会社（以下「当社」）は、以下の方針に基づき、個人情報保護の管理・運用を行っております。
                                    <br />必ずお読みください。
                                    <br />本サイトにおいて個人情報をご提供頂いた場合、当社の個人情報の取り扱いに関しご同意いただいたものといたします。
                                </p>
                                <h4 class="ttl">個人情報の取扱について</h4>
                                <ol>
                                    <li>
                                        <h3>個人情報の取扱の基本方針</h3>
                                        <p>ご入力いただいた個人情報は、当社が定める「個人情報保護方針」に従い、適切な保護措置を講じ、厳重に管理いたします。</p>
                                    </li>
                                    <li>
                                        <h3>当社が保有する個人情報の利用目的</h3>
                                        <p>ご入力いただいた個人情報は、以下の目的のみ利用致します。</p>
                                        <ul>
                                            <li>お客様への見積作成およびご依頼頂いた作業を行うため。</li>
                                            <li>お客様のご依頼に付随する作業およびサービスを行うため。</li>
                                            <li>お客様などへの報告や必要な処理を行うため。</li>
                                            <li>お客様からの各種お問い合わせや資料請求などにご対応するため。</li>
                                        </ul>
                                        <p>上記以外の目的で個人情報を利用する場合は、改めて目的をお知らせし、同意を得るものと致します。</p>
                                    </li>
                                    <li>
                                        <h3>個人情報の第三者提供について</h3>
                                        <p>ご提供いただいた個人情報は、ご本人のご同意なしに第三者への提供は致しません。但し、法令に基づき、国の機関または地方公共団体等より法的義務を伴う協力要請を受けた場合には、例外的にご本人の同意なく関連機関等に提供する場合がございます。</p>
                                    </li>
                                    <li>
                                        <h3>個人情報の取扱いの委託について</h3>
                                        <p>ご本人のご同意なしに個人情報の取扱いの全部または一部を委託することがあります。委託にあたっては、十分な個人情報の保護水準を満たしている者を選定し、委託を受けた者に対する必要、かつ適切な監督を行います。</p>
                                    </li>
                                    <li>
                                        <h3>個人情報提供の任意性</h3>
                                        <p>当社が必要とする個人情報をご提供頂くことは任意です。ただし、個人情報を提供いただけない場合は、当社の各種サービスのご提供が行えなくなるなどの支障がでる恐れがあります。</p>
                                    </li>
                                    <li>
                                        <h3>当社の個人情報保護管理者</h3>
                                        <p>個人情報保護管理者：管理部　部長</p>
                                    </li>
                                    <li>
                                        <h3>個人情報に関する苦情、相談、開示等の求め先について</h3>
                                        <p>ご自身の個人情報について、苦情、相談、利用目的の通知、開示、内容の訂正、追加又は削除、利用の停止、消去　及び第三者への提供の停止を請求する権利があり、当社は合理的な範囲で対応致します。これらの権利行使を行う場合は、下記の窓口にて受付を致します。</p>
                                    </li>
                                </ol>
                                <p id="contact" class="sentence">
                                    ≪個人情報に関するお問合せ窓口≫
                                    <br />
                                    <span>所在地</span>：東京都江東区新砂3-2-9　Xフロンティア　EAST 6階
                                    <br />
                                    <span>名称</span>：SGムービング株式会社 &#160; 本社 &#160; 管理部
                                    <br />
                                    <span>連絡先</span>：03-5857-2457(受付時間:平日9時～18時)
                                </p>
                            </div>
                        </div> -->
                        <!--▲個人情報の取り扱いここまで-->
                        <!--▼特定商取引法に基づく表記ここから-->
                        <!--<div id="transactions" class="accordion">
                            <h3 class="accordion_button">特定商取引法に基づく表記</h3>
                            <div id="transactions_contents" class="ac_content">
                                <dl>
                                    <dt>販売業者：</dt>
                                    <dd>SGムービング株式会社</dd>
                                </dl>
                                <dl>
                                    <dt>運営統括責任者：</dt>
                                    <dd>斉藤 光政</dd>
                                </dl>
                                <dl>
                                    <dt>住所：</dt>
                                    <dd>
                                        東京都江東区新砂3-2-9　Xフロンティア　EAST 6階
                                        <br />電話番号：03-6850-7828(受付時間:平日10時～17時)
                                    </dd>
                                </dl>
                                <dl>
                                    <dt>配送料金以外の必要料金：</dt>
                                    <dd>天候などによりお客様の乗下船地が変更された場合は、SGムービングにてお荷物の移動を行う際の費用を別途請求させていただく場合がございます。</dd>
                                </dl>
                                <dl>
                                    <dt>お支払方法：</dt>
                                    <dd>
                                        &#9312;クレジットカード支払い（一括支払いのみ）
                                        <br />&#9313;コンビニエンスストア支払い
                                    </dd>
                                </dl>
                                <dl>
                                    <dt>申込の有効期限：</dt>
                                    <dd>
                                        
                                        <table class="pc_only spTabH">
                                            <tr>
                                                <th rowspan="2" scope="col">&nbsp;</th>
                                                <th rowspan="2" scope="col">
                                                    集荷ご依頼
                                                    <br />受付開始日
                                                </th>
                                                <th scope="col">
                                                    クレジット
                                                    <br />カード払い
                                                </th>
                                                <th colspan="2" scope="col">コンビニ払い</th>
                                                <th colspan="2" scope="col">手荷物集荷</th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    集荷ご依頼
                                                    <br />受付終了日
                                                </td>
                                                <td>
                                                    集荷ご依頼
                                                    <br />受付終了日
                                                </td>
                                                <td>お支払い期限</td>
                                                <td>開始日</td>
                                                <td>終了日</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    インターネット
                                                    <br />申し込み
                                                </th>
                                                <td>ご乗船日の<br/>19日前</td>
                                                <td>
                                                    ご乗船日の
                                                    <br />7日前
                                                </td>
                                                <td>
                                                    ご乗船日の
                                                    <br />10日前
                                                </td>
                                                <td>
                                                    ご乗船日の
                                                    <br />10日前
                                                </td>
                                                <td>
                                                    ご乗船日の
                                                    <br />11日前
                                                </td>
                                                <td>
                                                    ご乗船日の
                                                    <br />5日前
                                                </td>
                                            </tr>
                                        </table>
                                        
                                        <div class="sp_only pcH">
                                            <h4 class="ttl">インターネット申し込みの場合</h4>
                                            <ul>
                                                <li>
                                                    <strong>集荷ご依頼受付開始日</strong>
                                                    <p>集荷ご依頼受付開始日は乗船日により異なります。ガイドブックなどをご確認の上お申し込みください。</p>
                                                </li>
                                                <li>
                                                    <strong>集荷ご依頼受付終了日</strong>
                                                    <p>
                                                        クレジットカード払いの場合：ご乗船日の7日前
                                                        <br />コンビニ払いの場合：ご乗船日の10日前
                                                        <br />コンビニ払いの場合のお支払い期限：ご乗船日の10日前
                                                    </p>
                                                </li>
                                                <li>
                                                    <strong>手荷物集荷</strong>
                                                    <p>
                                                        開始日：ご乗船日の11日前
                                                        <br />終了日：ご乗船日の5日前
                                                    </p>
                                                </li>
                                            </ul>
                                        </div>
                                        
                                        
                                        <p>※船の入出港スケジュールや国内行事などによる交通規制により、受付期間を設定変更する場合がございます。</p>
                                        <p>※ お申し込み期間終了後のお申し込みはできませんのでご注意ください。</p>
                                        <p>※ コンビニ払いをご希望の場合、クレジットカード払いに比べご依頼受付終了日が早くなります。コンビニ払いの受付期間を過ぎた場合はクレジットカードでのお支払いのみとなりますので予めご了承ください。</p>
                                        <p><strong class="red">※ コンビニ払いで入金確認が取れない場合は集荷にお伺いしませんのでご注意ください。お支払いは期日までにお願いいたします。</strong></p>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt>お荷物の破損について：</dt>
                                    <dd>
                                        <p>下船時、ターミナル内で引き取ったお荷物に破損(劣化による破損は除く)などがありましたら、通関前にターミナル内に待機しているクルーズスタッフにお申し出ください。</p>
                                        <p>
                                            お帰り後、ご自宅で荷物の破損などを確認した場合は、
                                            <br />
                                            <strong>03-6850-7828(SGムービング)(受付時間:9時～18時)</strong>へご連絡ください。
                                        </p>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt>お荷物の集荷：</dt>
                                    <dd>ご入力いただいた情報をもとに本サービスでお荷物の配送業務を担当します佐川急便が伝票を作成し、集荷の際にお客様の荷物に貼付し集荷いたします。</dd>
                                </dl>
                                <dl>
                                    <dt>キャンセル：</dt>
                                    <dd>
                                        旅客手荷物受付サービスをキャンセルされたい場合は、お手数ですがお客様ご自身でSGムービングにご連絡頂き、キャンセルをお申し出ください。
                                        <br />(往復でお申し込みされているお客様が、お預かり後キャンセルされた場合は、復路代金で返送する為、全額ご返金はございません)
                                        <br />(往路しかお申し込みされていないお客様が、お預かり後キャンセルされた場合は、返送代金をご請求させて頂きます)
                                    </dd>
                                </dl>
                            </div>
                        </div>-->
                        <!--▲特定商取引法に基づく表記ここまで-->
                    </div>
                    <!--<p class="sentence">
                        <strong class="red">※迷惑メールを設定されている方は「sagawa-mov.co.jp」を受信する設定にしてください。
                        <br />詳しくは「<a href="#bounce_mail">ご連絡メールが届かない場合</a>」をご確認ください。</strong>
                    </p>-->

                    <!--<p class="sentence"><span class="sp_only pcH">上記「個人情報の取り扱い」および「特定商取引法に基づく表記」の</span>内容についてご同意頂ける方は、下のボタンを押してください。</p>-->

                    <p class="text_center">
                        <input id="submit_button" type="submit" name="submit" value="同意して次に進む（入力内容の確認）">
                    </p>
                </form>
            </div>
        </div>
    </div>
    <!--main-->

<?php
    $footerSettings = 'under';
    include_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/parts/footer.php';
?>

    <!--[if lt IE 9]>
    <script charset="UTF-8" type="text/javascript" src="/js/jquery-1.12.0.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/lodash-compat.min.js"></script>
    <![endif]-->
    <!--[if gte IE 9]><!-->
    <script charset="UTF-8" type="text/javascript" src="/js/jquery-2.2.0.min.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/lodash.min.js"></script>
    <!--<![endif]-->
    <script charset="UTF-8" type="text/javascript" src="/common/js/multi_send.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/common.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/js/smooth_scroll.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/ajax_searchaddress.js"></script>
    <script charset="UTF-8" type="text/javascript" src="/common/js/jquery.ah-placeholder.js"></script>
<?php
    if (!$isSmartPhone) {
?>
    <script charset="UTF-8" type="text/javascript" src="/common/js/jquery.autoKana.js"></script>
<?php
    }
?>
    <script charset="UTF-8" type="text/javascript" src="/pcr/js/input.js?v=<?php echo date('YmdHis'); ?>"></script>
</body>
</html>