<?php
/**
 * 催事・イベント配送受付お申し込み入力画面を表示します。
 * @package    ssl_html
 * @subpackage EVE
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';
//Sgmov_Lib::useServices(array('Comiket',));
// イベント識別子(URLのpath)はマジックナンバーで記述せずこの変数で記述してください
$dirDiv=Sgmov_Lib::setDirDiv(dirname(__FILE__));
Sgmov_Lib::useView($dirDiv.'/UseEvent');

$view = new Sgmov_View_Evp_Use_Event();
$eventInfo = $view->updateSchedule($_POST);

/*
$_POST['preopen']がきたら開催前状態にする(1分後開催する)
$_POST['open']がきたらげんざい日時から3日間オープン状態にする
$_POST['close']がきたら終了直前状態にする(1分後に終了)
*/

// 初期値
if($_POST['eventsub_id']==''){
    $_POST['eventsub_id']=1110;
}
if($_POST['shikibetsushi']==''){
    $_POST['shikibetsushi']='skt';
}

?>
<style>
input{width:16em;}
input.box{width:5em;}
</style>


<div style="float:left;width:20%;">
<?php
foreach($eventInfo as $k=>$v){
echo($k.':'.$v.'<br>');
}
?>
</div>
<div style="float:right;width:80%;">
    <title>イベントスケジュール管理</title>
    <form method="post">
    イベントサブID：<input type=text value="<?=$_POST['eventsub_id']?>" name="eventsub_id" class="box">
<!--
<br>識別子　　　　：<input type=text value="<?=$_POST['shikibetsushi']?>" name="shikibetsushi" class="box">
-->
    <p>
<!--
    往複：<input type="submit" value="開始10秒前" name="both_preopen">
    <input type="submit" value="申込可能期間中" name="both_open">
    <input type="submit" value="終了10秒前～搬入終了" name="both_close">
-->

    <p>往路：<input type="submit" value="搬入開始10秒前" name="nyu_preopen">
    <input type="submit" value="搬入中" name="nyu_open">
    <input type="submit" value="搬入終了10秒前～搬入終了" name="nyu_close">

    <p>復路：<input type="submit" value="搬出開始10秒前" name="shutsu_preopen">
    <input type="submit" value="搬出中" name="shutsu_open">
    <input type="submit" value="搬出終了10秒前～搬出終了" name="shutsu_close">

    <p>
<!--
    <a href="https://sagawa-mov-test04.media-tec.jp/<?=$_POST['shikibetsushi']?>/input" target="_blank">テスト環境</a>
-->
テスト環境を開く
    <br><a href="https://sagawa-mov-test04.media-tec.jp/eve/input" target="_blank">14：コミケ法人</a>
    <br><a href="https://sagawa-mov-test04.media-tec.jp/evp/input" target="_blank">15：コミケ個人</a>
    <br><a href="https://sagawa-mov-test04.media-tec.jp/gmm/input" target="_blank">303：ゲームマーケット</a>
    <br><a href="https://sagawa-mov-test04.media-tec.jp/tme/input" target="_blank">1501：東京マラソン</a>
    <br><a href="https://sagawa-mov-test04.media-tec.jp/tms/input" target="_blank">800：東京モーターショー</a>
    <br>
    <br><a href="https://sagawa-mov-test04.media-tec.jp/skt/input" target="_blank">1110：生活のたのしみ展</a>
    </form>
</div>