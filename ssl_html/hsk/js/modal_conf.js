/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function() {
    $('.btn-conf').on('click', function() {
        // デモンストレーション投票
        $('.conf_team_names').empty();
        $('input[name="chkZekken[]"]').each(function() {
            var isCheck = $(this).prop('checked');
            if (isCheck) {
                var num = $(this).val();
                var teameName = $('td.label_team_name_' + num).html();
                $('.conf_team_names').append(teameName + '<br>');
            }
        });
        console.log($('input[name="chkZekken[]"]'));
        
        // 業種&その他
        var gyoshu = $('select[name=gyoshu] option:selected').text();
        if (gyoshu == 'その他') {
            gyoshu = gyoshu + '：' + $('input[name=gyoshuSonota]').val();
        }
        $('.conf_gyoshu').html(gyoshu);
        
        // 年齢
        var nenrei = $('select[name=nenrei] option:selected').text();
        $('.conf_nenrei').html(nenrei);
        
        // 性別
        var seibetsuVal = $('input[name=seibetsu]:checked').val();
        var seibetsuLbl = $('#seibetsu' + seibetsuVal).attr('lbl');
        $('.conf_seibetsu').html(seibetsuLbl);
        
        // 品質選手権の内容で良かったと思うものをお選びください。
        $('.conf_yoi_names').empty();
        $('input[name="yoi[]"]').each(function() {
            var isCheck = $(this).prop('checked');
            if (isCheck) {
                var yoiName = $(this).attr('lbl');
                if (yoiName == 'その他') {
                    $('.conf_yoi_names').append(yoiName + "：" + $('input[name=yoiSonota]').val() + '<br>');
                } else {
                    $('.conf_yoi_names').append(yoiName + '<br>');
                }
            }
        });
        
        // どのような点が良かったですか？
        var yoiTextarea = $('textarea[name=yoiTextarea]').val();
        var yoiTextarea2 = yoiTextarea.replace(/\r\n/g, '<br/>');
        var yoiTextarea2 = yoiTextarea2.replace(/\n/g, '<br/>');
        $('.conf_yoi_textarea').html(yoiTextarea2);
        
        // イベント全体の所要時間はいかがでしたか？
        var shoyojikanVal = $('input[name=shoyojikan]:checked').val();
        var shoyojikanLbl = $('#shoyojikan' + shoyojikanVal).attr('lbl');
        $('.conf_shoyojikan').html(shoyojikanLbl);

        // 今後当社を利用したいと思いますか？
        var riyokbnVal = $('input[name=riyokbn]:checked').val();
        var riyokbnLbl = $('#riyokbn' + riyokbnVal).attr('lbl');
        $('.conf_riyokbn').html(riyokbnLbl);
        
        // その他お気づきの点がございましたらご記入ください
        var sonotaTextarea = $('textarea[name=sonotaTextarea]').val();
        var sonotaTextarea2 = sonotaTextarea.replace(/\r\n/g, '<br/>');
        var sonotaTextarea2 = sonotaTextarea2.replace(/\n/g, '<br/>');
        $('.conf_sonota_textarea').html(sonotaTextarea2);
        
        // ７．ご希望の景品をお選びください。
        var keihinVal = $('input[name=keihin]:checked').val();
        var keihinLbl = $('#keihin' + keihinVal).attr('lbl');
        $('.conf_keihin').html(keihinLbl);
    });
});
