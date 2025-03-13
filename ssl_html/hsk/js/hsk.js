/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(function() {
  $("#warn-msg-area2").fadeIn(1000);
//  setTimeout(function() {
//      $("#warn-msg-area2").fadeOut("slow");
//  }, 20000);
//  
    
  $('div#input-gyoshu-sonota').hide();
  $('select#gyoshu').change(function(){
    if($('[name=gyoshu] option:selected').text() == 'その他'){
      $('div#input-gyoshu-sonota').show(500);
      // bmd-form-group
    } else {
      $('div#input-gyoshu-sonota').hide(500);
    }
  });
  
  $('div#input-yoi-sonota').hide();
  $('input#checkbox-yoi-sonota').click(function(){
    $('div#input-yoi-sonota').toggle(500);
  });
  
  $('button#soushin').click(function(){
    $('#scrnLock').fadeIn(300);
    $('#form1').submit();
  });
  
  
  $('button.btn-conf').click(function(e) {
    $("#warn-msg-area2").hide();
    $('#scrnLock').fadeIn(300);
    e.stopPropagation();
    var $form = $('form').first();
    var data = $form.serializeArray();
    
    return $.ajax({
        async: true,
        cache: false,
        data: data,
        dataType: 'json',
        timeout: 60000,
        type: 'post',
        url: '/hsk/check_input.php'
    }).done(function (data, textStatus, jqXHR) {
//        alert("test");
        // consoleの存在チェック
//        if (!window.console) {
//            return;
//        }
//        // consoleが存在する場合、取得内容を出力
//        window.console.log(data);
//        window.console.log('#######################1');
//        window.console.log(data["chkZekken"]);
//        window.console.log(textStatus);
//        window.console.log(jqXHR);
        
        // 画面上部エラーメッセージ全て
        
        $('.input-error-all').hide();
        if (data["errMsgAll"] && data["errMsgAll"] != '') {
            $('.input-error-all').slideDown(250);
            $('.input-error-all .alert-message').html(data["errMsgAll"]);
        }

        // デモンストレーション投票
        $('#section-vote .alert-danger.input-error').hide();
        $('#section-vote .title').removeClass('has-danger');
        if (data["chkZekken"] && data["chkZekken"] != '') {
            $('#section-vote .title').addClass('has-danger');
            $('#section-vote .alert-danger.input-error').slideDown(250);
            $('#section-vote .alert-danger .alert-message').html(data["chkZekken"]);
        }
        
        // お客様の情報を下記より選択してください
        // 業種
        $('.okyakusama-info .alert-danger.input-error').hide();
        $('.okyakusama-info .alert-danger .alert-message').html('');
        $('.okyakusama-info div.form-group.gyoshu').removeClass('has-danger');
        if (data["gyoshu"] && data["gyoshu"] != '') {
            $('.okyakusama-info div.form-group.gyoshu').addClass('has-danger');
            $('.okyakusama-info .alert-danger.input-error').slideDown(250);
            $('.okyakusama-info .alert-danger .alert-message').html(data["gyoshu"]);
        }
        
        // 業種 - その他
        $('.okyakusama-info div.form-group.gyoshu-sonota').removeClass('has-danger');
        if (data["gyoshuSonota"] && data["gyoshuSonota"] != "") {
            $('.okyakusama-info div.form-group.gyoshu-sonota').addClass('has-danger');
            $('.okyakusama-info .alert-danger.input-error').slideDown(250);
            $('.okyakusama-info .alert-danger .alert-message').html(data["gyoshuSonota"]);
        }
        
        // 年齢
        $('.okyakusama-info div.form-group.nenrei').removeClass('has-danger');
        if (data["nenrei"] && data["nenrei"] != '') {
            $('.okyakusama-info div.form-group.nenrei').addClass('has-danger');
            $('.okyakusama-info .alert-danger.input-error').slideDown(250);
            var alertMessage = $('.okyakusama-info .alert-danger .alert-message').html();
            if (alertMessage != "") {
                alertMessage = alertMessage+"<br>";
            }
            $('.okyakusama-info .alert-danger .alert-message').html(alertMessage + data["nenrei"]);
        }
        
        // 性別
        $('.okyakusama-info div.form-group.seibetsu').removeClass('has-danger');
        if (data["seibetsu"] && data["seibetsu"] != '') {
            $('.okyakusama-info div.form-group.seibetsu').addClass('has-danger');
            $('.okyakusama-info .alert-danger.input-error').slideDown(250);
            var alertMessage = $('.okyakusama-info .alert-danger .alert-message').html();
            if (alertMessage != "") {
                alertMessage = alertMessage+"<br>";
            }
            $('.okyakusama-info .alert-danger .alert-message').html(alertMessage + data["seibetsu"]);
        }
        
        // 品質選手権の内容で良かったと思うものをお選びください。【複数回答可】
        $('.yoi-info .alert-danger.input-error').hide();
        $('.yoi-info div.form-group.yoi').removeClass('has-danger');
        if (data["yoi"] && data["yoi"] != '') {
            $('.yoi-info div.form-group.yoi').addClass('has-danger');
            $('.yoi-info .alert-danger.input-error').slideDown(250);
            $('.yoi-info .alert-danger .alert-message').html(data["yoi"]);
        }
        
        // 品質選手権の内容で良かったと思うもの - その他
        $('.yoi-info div.form-group.yoi-sonota').removeClass('has-danger');
        if (data["yoiSonota"] && data["yoiSonota"] != "") {
            $('.yoi-info div.form-group.yoi-sonota').addClass('has-danger');
            $('.yoi-info .alert-danger.input-error').slideDown(250);
            $('.yoi-info .alert-danger .alert-message').html(data["yoiSonota"]);
        }

        // 「2」でどのような点が良かったですか？【必須ではありません】
        $('.yoi-textarea-info .alert-danger.input-error').hide();
        $('.yoi-textarea-info div.form-group.yoi-textarea').removeClass('has-danger');
        if (data["yoiTextarea"] && data["yoiTextarea"] != '') {
            $('.yoi-textarea-info div.form-group.yoi-textarea').addClass('has-danger');
            $('.yoi-textarea-info .alert-danger.input-error').slideDown(250);
            $('.yoi-textarea-info .alert-danger .alert-message').html(data["yoiTextarea"]);
        }
        
        // イベント全体の所要時間はいかがでしたか？
        $('.shoyojikan-info .alert-danger.input-error').hide();
        $('.shoyojikan-info div.form-group.shoyojikan').removeClass('has-danger');
        if (data["shoyojikan"] && data["shoyojikan"] != '') {
            $('.shoyojikan-info div.form-group.shoyojikan').addClass('has-danger');
            $('.shoyojikan-info .alert-danger.input-error').slideDown(250);
            $('.shoyojikan-info .alert-danger .alert-message').html(data["shoyojikan"]);
        }
        
        // 今後当社を利用したいと思いますか？
        $('.riyokbn-info .alert-danger.input-error').hide();
        $('.riyokbn-info div.form-group.riyokbn').removeClass('has-danger');
        if (data["riyokbn"] && data["riyokbn"] != '') {
            $('.riyokbn-info div.form-group.riyokbn').addClass('has-danger');
            $('.riyokbn-info .alert-danger.input-error').slideDown(250);
            $('.riyokbn-info .alert-danger .alert-message').html(data["riyokbn"]);
        }

        
        // その他お気づきの点がございましたらご記入ください【必須ではありません】
        $('.sonota-textarea-info .alert-danger.input-error').hide();
        $('.sonota-textarea-info div.form-group.sonota-textarea').removeClass('has-danger');
        if (data["sonotaTextarea"] && data["sonotaTextarea"] != '') {
            $('.sonota-textarea-info div.form-group.sonota-textarea').addClass('has-danger');
            $('.sonota-textarea-info .alert-danger.input-error').slideDown(250);
            $('.sonota-textarea-info .alert-danger .alert-message').html(data["sonotaTextarea"]);
        }
        
        // ご希望の景品をお選びください。
        $('.keihin-info .alert-danger.input-error').hide();
        $('.keihin-info div.form-group.keihin').removeClass('has-danger');
        if (data["keihin"] && data["keihin"] != '') {
            $('.keihin-info div.form-group.keihin').addClass('has-danger');
            $('.keihin-info .alert-danger.input-error').slideDown(250);
            $('.keihin-info .alert-danger .alert-message').html(data["keihin"]);
        }
        
        $('#scrnLock').fadeOut(500);
        
        if (data['isErr']) {
            setTimeout(function() {
                $('#page_top').trigger('click');
            }, 600);
        } else {
//            $('#form1').submit();
            $('#kakuninModal').modal('show');
        }
        
    }).fail(function (jqXHR, textStatus, errorThrown) {
        location.href = '/hsk/error';
//        // consoleの存在チェック
//        if (!window.console) {
//            return;
//        }
//        // consoleが存在する場合、エラー内容を出力
//        window.console.log(jqXHR);
//        window.console.log(textStatus);
//        window.console.log(errorThrown);
//        $('#scrnLock').fadeOut(500);
    });
  });
});


$(function() {
    var pagetop = $('#page_top');   
    pagetop.hide();
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {  //100pxスクロールしたら表示
            pagetop.fadeIn();
        } else {
            pagetop.fadeOut();
        }
    });
    pagetop.click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 500); //0.5秒かけてトップへ移動
        return false;
    });
});


function movePageLink(myThis) {
      // スクロールの速度
      var speed = 400; // ミリ秒
      // アンカーの値取得
      var href= $(myThis).attr("href");
      // 移動先を取得
      var target = $(href == "#" || href == "" ? 'html' : href);
      // 移動先を数値で取得
      var position = target.offset().top-80;
      // スムーススクロール
      $('body,html').animate({scrollTop:position}, speed, 'swing');
      return false;
}


function scrollToDownload() {
  if ($('.section-download').length != 0) {
    $("html, body").animate({
      scrollTop: $('.section-download').offset().top
    }, 1000);
  }
}

function getFormData() {
    var $form = $('form').first(),
        data = $form.serializeArray();
    data.push({
        name: 'featureId',
        value: $form.data('featureId')
    }, {
        name: 'id',
        value: $form.data('id')
    });
    return data;
}

