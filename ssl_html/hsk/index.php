<?php
/**
 * 品質選手権アンケート入力画面を表示します。
 * @package    ssl_html
 * @subpackage HSK
 * @author     K.Sawada(SCS)
 * @copyright  2018-2018 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
/* * #@+
 * include files
 */
require_once dirname(__FILE__) . '/../../lib/Lib.php';

Sgmov_Lib::useView('hsk/Index');


/* * #@- */
// 処理を実行
$view = new Sgmov_View_Hsk_Index();

$result = $view->execute();
$outInfo = $result['outInfo'];

?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <?php require_once './parts/html_head.php'; ?>
        <link href="/hsk/css/hsk.css?v=2.0.6" rel="stylesheet" />
    </head>
    <body>
        <?php require('./modal/modal_conf.php'); ?>
        <?php require_once './parts/page_head.php'; ?>

        <form action="/hsk/end" name="form1" id="form1" method="POST">
            <div class="main main-raised">
                <div class="container">
                    
             
                    
<div id="warn-msg-area2" class="input-warn-all" style="display:none;">
    <div class="title" style="margin-bottom: 0px;"></div>
    <div class="row" style="padding-top: 10px;">
        <div class="col-md-12">
            <div class="alert alert-warning">
                <div class="container">
                    <div class="alert-icon">
                        <i class="material-icons">warning</i>
                    </div>
<!--                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true"><i class="material-icons">clear</i></span>
                    </button>-->
                    <span style="font-weight: bold;">チーム投票・アンケートの回答は１回のみで、送信すると再アクセスできません。</span>
                </div>
            </div>
            
            <div class="alert alert-warning">
                <div class="container">
                    <div class="alert-icon">
                        <i class="material-icons">warning</i>
                    </div>
<!--                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true"><i class="material-icons">clear</i></span>
                    </button>-->
                    <span style="font-weight: bold;">全競技終了後の14：45～15：30迄の間に送信をお願いいたします。</span>
                </div>
            </div>
        </div>
    </div>
</div>
                    
                    
                    
                
                    <input type="hidden" name="id" value="<?php echo @$_GET["param"]; ?>"/>
                    
                    <div id="error-msg-area" class="input-error-all" style="display:none;">
                        <div class="title" style="margin-bottom: 0px;"></div>
                        <div class="row" style="padding-top: 10px;">
                            
                            <div class="col-md-12">
                                <!-- 入力エラーアラート Start -->
                                 <div class="alert alert-danger alert-dismissible fade show input-error-all" style="display:none;">
                                     <i class="material-icons">error_outline</i>
                                     <!--<button type="button" class="close" data-dismiss="alert">&times;</button>-->
                                     <span class="alert-message"></span>
                                 </div>
                                 <!-- 入力エラーアラート End -->
                            </div>
                        </div>
                    </div>
                    <div id="section-vote">
                        <div class="title border-bottom" style="padding-top: 10px;">
                            <h4 style="font-weight: normal;">
                                <i class="material-icons" style="font-size: 1.2em;">how_to_vote</i>&nbsp;
                                <span class="demovote-title" style="font-weight: 600;">
                                    デモンストレーション投票 
                                </span>
<!--                                <span class="form-control-feedback">
                                    <i class="material-icons">clear</i>
                                </span>-->
                            </h4>
                        </div>
                          <!--<i class="fa fa-spinner" aria-hidden="true"></i>-->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card discription">
                                    <div class="card-body">
                                        <i class="material-icons" style="font-size:22px;color:#0468b4;">info</i>
                                        &nbsp;<span style="color: #588ab1;">最大２チームまでお選びください</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">


                                <!-- 入力エラーアラート Start -->
                                <div id="alert-chkZekken" class="alert alert-danger alert-dismissible fade show input-error" style="display:none;">
                                    <!--<i class="material-icons">error_outline</i>-->
                                    <!--<button type="button" class="close" data-dismiss="alert">&times;</button>-->
                                    <span class="alert-message"></span>
                                </div>
                                <!-- 入力エラーアラート End -->


                                <table class="table table-responsive sampletable table-striped nowrap">
                                    <thead>
                                        <tr>
                                            <th scope="col">投票</th>
                                            <th scope="col">チーム名</th>
                                            <th scope="col">営業所</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $count = 1; ?>
                                        <?php foreach ($outInfo['voteList'] as $key => $val) : ?>
                                            <tr>
                                                <th scope="row" rowspan="2" style="vertical-align:middle;">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input class="form-check-input" name="chkZekken[]" type="checkbox" value="<?php echo $count; ?>">
                                                            <span class="form-check-sign">
                                                                <span class="check"></span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </th>
                                                <td class="label_team_name_<?php echo $count; ?>" style="width: 120px;"><?php echo $val['teamName']; ?></td>
                                                <td><?php echo $val['officeName']; ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <?php foreach ($val['pearsonNameList'] as $key2 => $val2) : ?>
                                                        <?php echo $val['companyName']; ?>　<?php echo $val2; ?><br />
                                                    <?php endforeach; ?>
                                                </td>
                                            </tr>
                                            <?php $count++; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div><!-- col -->
                        </div><!-- row -->
                    </div><!-- section-vote -->




                    <div id="section-enq">
                        <div class="title border-bottom" style="padding-top: 10px;">
                            <h3><i class="material-icons" style="font-size: 1.2em;">list_alt</i>&nbsp;
                                <span style="font-weight: 600;">
                                    ご来賓様アンケート
                                </span>
                            </h3>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card discription">
                                    <div class="card-body">
                                        <i class="material-icons" style="font-size:22px;color:#0468b4;">info</i>
                                        &nbsp;<span style="color: #588ab1;">今後の大会運営の参考にさせていただきます。ご協力をお願いいたします。</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- row -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card okyakusama-info">
                                    <div class="card-header card-header-text card-header-info">
                                        <div class="card-text">
                                            <!--filter_1 -->
                                            <h4 class="card-title">１．お客様の情報を下記より選択してください。</h4>
                                        </div>
                                    </div>
                                    <div class="card-body">


                                        <!-- 入力エラーアラート Start -->
                                        <div id="alert-gyoshu" class="alert alert-danger alert-dismissible fade show input-error" style="display:none;">
                                            <!--<button type="button" class="close" data-dismiss="alert">&times;</button>-->
                                            <span class="alert-message"></span>
                                        </div>
                                        <!-- 入力エラーアラート End -->


                                        <div class="form-group gyoshu">
                                            <!-- <label for="inputState">業種</label> -->
                                            <select id="gyoshu" name="gyoshu" class="form-control">
                                                <option value=""  selected>業種を選択</option>
                                                <?php foreach ($outInfo['gyoshuList'] as $key => $val) : ?>
                                                    <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="form-control-feedback">
                                                <i class="material-icons">clear</i>
                                            </span>
                                        </div>
                                        <div class="form-group gyoshu-sonota" id="input-gyoshu-sonota">
                                            <label for="gyoshu-sonota" class="bmd-label-floating">その他の内容</label>
                                            <input type="text" name="gyoshuSonota" class="form-control input-gyoshu-sonota">
                                        </div>
                                        <div class="form-group nenrei">
                                            <select id="nenrei" class="form-control" name="nenrei">
                                                <option value="" selected>年齢を選択</option>
                                                <?php foreach ($outInfo['nenreiList'] as $key => $val) : ?>
                                                    <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <div class="form-control-feedback">
                                                <i class="material-icons">clear</i>
                                            </div>
                                        </div>
                                        <div class="form-group bmd-form-group seibetsu">
                                            <div>
                                                <label for="disabledTextInput">性別</label>
                                            </div>
                                            <div class="form-check form-check-radio form-check-inline seibetsu">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="seibetsu" id="seibetsu1" value="1" lbl="男性"> 男性
                                                    <span class="circle"> 
                                                        <span class="check"></span>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="form-check form-check-radio form-check-inline">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="seibetsu" id="seibetsu2" value="2" lbl="女性"> 女性
                                                    <span class="circle">
                                                        <span class="check"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div><!-- card body -->
                                </div><!-- card -->
                            </div><!-- col-md-12 -->
                        </div><!-- row -->
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card yoi-info">
                                    <div class="card-header card-header-text card-header-info">
                                        <div class="card-text">
                                            <h4 class="card-title">２．品質選手権の内容で良かったと思うものをお選びください。【複数回答可】</h4>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        <!-- 入力エラーアラート Start -->
                                        <div id="alert-yoi" class="alert alert-danger alert-dismissible fade show input-error" style="display:none;">
                                            <!--<button type="button" class="close" data-dismiss="alert">&times;</button>-->
                                            <span class="alert-message"></span>
                                        </div>
                                        <!-- 入力エラーアラート End -->

                                        <div class="form-group">
                                            <?php foreach ($outInfo['yoiList'] as $key => $val) : ?>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" type="checkbox" name="yoi[]" value="<?php echo $key; ?>" lbl="<?php echo $val; ?>" <?php if ($val == 'その他') : ?> id="checkbox-yoi-sonota" <?php endif; ?>>
                                                        <?php echo $val; ?>
                                                        <span class="form-check-sign">
                                                            <span class="check"></span>
                                                        </span>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="form-group bmd-form-group yoi-sonota" id="input-yoi-sonota">
                                            <label for="exampleInput1" class="bmd-label-floating">その他</label>
                                            <input type="text" name="yoiSonota" class="form-control" id="exampleInput1">
                                            <span class="form-control-feedback">
                                                <i class="material-icons">clear</i>
                                            </span>
                                        </div>
                                    </div><!-- card-body -->
                                </div><!-- card -->
                            </div><!-- col-md-12 -->
                        </div><!-- row -->
                        
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card yoi-textarea-info">
                                    <div class="card-header card-header-text card-header-info">
                                        <div class="card-text">
                                            <h4 class="card-title">３．「２」でどのような点が良かったですか？【必須ではありません】</h4>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        <!-- 入力エラーアラート Start -->
                                        <div id="alert-yoiTextarea" class="alert alert-danger alert-dismissible fade show input-error" style="display:none;">
                                            <!--<button type="button" class="close" data-dismiss="alert">&times;</button>-->
                                            <span class="alert-message"></span>
                                        </div>
                                        <!-- 入力エラーアラート End -->

                                        <div class="form-group yoi-textarea">
                                            <label for="exampleFormControlTextarea1">記入欄</label>
                                            <textarea name="yoiTextarea" class="form-control" id="exampleFormControlTextarea1" rows="3" ></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- row -->
                        
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card shoyojikan-info">
                                    <div class="card-header card-header-text card-header-info">
                                        <div class="card-text">
                                            <h4 class="card-title">４．イベント全体の所要時間はいかがでしたか？</h4>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        <!-- 入力エラーアラート Start -->
                                        <div id="alert-shoyojikan" class="alert alert-danger alert-dismissible fade show input-error" style="display:none;">
                                            <!--<button type="button" class="close" data-dismiss="alert">&times;</button>-->
                                            <span class="alert-message"></span>
                                        </div>
                                        <!-- 入力エラーアラート End -->

                                        <div class="form-group shoyojikan">
                                            <div class="form-check form-check-radio form-check-inline">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="shoyojikan" id="shoyojikan1" value="1" lbl="長かった"> 長かった
                                                    <span class="circle">
                                                        <span class="check"></span>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="form-check form-check-radio form-check-inline">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="shoyojikan" id="shoyojikan2" value="2" lbl="ちょうど良かった"> ちょうど良かった
                                                    <span class="circle">
                                                        <span class="check"></span>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="form-check form-check-radio form-check-inline">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="shoyojikan" id="shoyojikan3" value="3" lbl="短かった"> 短かった
                                                    <span class="circle">
                                                        <span class="check"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div><!-- card-body -->
                                </div><!-- card -->
                            </div>
                        </div><!-- row -->
                        
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card riyokbn-info">
                                    <div class="card-header card-header-text card-header-info">
                                        <div class="card-text">
                                            <h4 class="card-title">５．今後当社を利用したいと思いますか？</h4>
                                        </div>
                                    </div>
                                    <div class="card-body">


                                        <!-- 入力エラーアラート Start -->
                                        <div id="alert-riyokbn" class="alert alert-danger alert-dismissible fade show input-error" style="display:none;">
                                            <!--<button type="button" class="close" data-dismiss="alert">&times;</button>-->
                                            <span class="alert-message"></span>
                                        </div>
                                        <!-- 入力エラーアラート End -->


                                        <div class="form-group riyokbn">
                                            <div class="form-check form-check-radio form-check-inline">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="riyokbn" id="riyokbn5" value="5" lbl="是非利用したい"> 是非利用したい
                                                    <span class="circle">
                                                        <span class="check"></span>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="form-check form-check-radio form-check-inline">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="riyokbn" id="riyokbn4" value="4" lbl="できれば利用したい"> できれば利用したい
                                                    <span class="circle">
                                                        <span class="check"></span>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="form-check form-check-radio form-check-inline">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="riyokbn" id="riyokbn3" value="3" lbl="どちらでもない"> どちらでもない
                                                    <span class="circle">
                                                        <span class="check"></span>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="form-check form-check-radio form-check-inline">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="riyokbn" id="riyokbn2" value="2" lbl="あまり利用したくない"> あまり利用したくない
                                                    <span class="circle">
                                                        <span class="check"></span>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="form-check form-check-radio form-check-inline">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="riyokbn" id="riyokbn1" value="1" lbl="全く利用したくない"> 全く利用したくない
                                                    <span class="circle">
                                                        <span class="check"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div><!-- card-body -->
                                </div>
                            </div>
                        </div><!-- row -->
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card sonota-textarea-info">
                                    <div class="card-header card-header-text card-header-info">
                                        <div class="card-text">
                                            <h4 class="card-title">６．その他お気づきの点がございましたらご記入ください【必須ではありません】</h4>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        <!-- 入力エラーアラート Start -->
                                        <div id="alert-sonotaTextarea" class="alert alert-danger alert-dismissible fade show input-error" style="display:none;">
                                            <!--<button type="button" class="close" data-dismiss="alert">&times;</button>-->
                                            <span class="alert-message"></span>
                                        </div>
                                        <!-- 入力エラーアラート End -->

                                        <div class="form-group sonota-textarea">
                                            <label for="exampleFormControlTextarea1">記入欄</label>
                                            <textarea name="sonotaTextarea" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- row -->
                        
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card keihin-info">
                                    <div class="card-header card-header-text card-header-info">
                                        <div class="card-text">
                                            <h4 class="card-title">７．ご希望の景品をお選びください。</h4>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        <!-- 入力エラーアラート Start -->
                                        <div id="alert-keihin" class="alert alert-danger alert-dismissible fade show input-error" style="display:none;">
                                            <!--<button type="button" class="close" data-dismiss="alert">&times;</button>-->
                                            <span class="alert-message"></span>
                                        </div>
                                        <!-- 入力エラーアラート End -->

                                        <div class="form-group keihin">
                                            <div class="form-check form-check-radio form-check-inline">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="keihin" id="keihin1" value="1" lbl="A賞 TVボード"> A賞&nbsp;TVボード
                                                    <span class="circle">
                                                        <span class="check"></span>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="form-check form-check-radio form-check-inline">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="keihin" id="keihin2" value="2" lbl="B賞 ノベルティグッズ"> B賞&nbsp;ノベルティグッズ
                                                    <span class="circle">
                                                        <span class="check"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div><!-- card-body -->
                                </div><!-- card -->
                            </div>
                        </div><!-- row -->
                        
                        
                        <div class="row">
                            <div class="col-md-12" style="text-align: center;">
                                <button type="button" class="btn btn-primary lg btn-conf" data-toggle="modal" data-target="#kakuninModal"><b>入力内容の確認</b></button>
                            </div>
                        </div><!-- row -->
                        
                        
                        <div class="row">
                            <div class="col-md-12">
                                &nbsp;
                            </div>
                        </div><!-- row -->
                        
                    </div><!-- section-enq -->
                </div><!-- container -->
            </div><!-- main -->
        </form>
        
        <?php require_once './parts/page_footer.php'; ?>
        <script src="/hsk/js/hsk.js?v=1.0.0" type="text/javascript"></script>
        <script src="/hsk/js/modal_conf.js?v=1.0.0" type="text/javascript"></script>
    </body>
</html>