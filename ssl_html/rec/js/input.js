

$(document).ready(function(){

    if ($.fn && $.fn.autoKana) {
        $.fn.autoKana('.personal_name', '.personal_name_furi', {
            katakana: true
        });
    }

    // $('input[name="personal_name"]').on('input', function() {
    //     if ($('input[name="personal_name_furi"]').val().length >= 9) {
    //         $('input[name="personal_name_furi"]').val($('input[name="personal_name_furi"]').val().slice(0, 8));
    //     }
    // });
    $(".hanToZen").blur(function(){
        var result = toFullWidth($(this).val());
        $(this).val(result.replace(/ /g, "　"));
    });

    if($('input[name="employ_cd"]:checked').val() === undefined){
        $(".center_rd").prop('checked', false);
        $(".center_rd").prop('disabled', true);
        $(".occup_radio ").prop('disabled', true);
        $(".occup_radio ").prop('checked', false);
    }

    var toFullWidth = function(value) {
        if (!value) return value;
        return String(value).replace(/[!-~]/g, function(all) {
            return String.fromCharCode(all.charCodeAt(0) + 65248);
        });
    };
//////////////////////////////////////////////////////////////////////////////////////////////////////////
// 住所検索
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    $('input[name="adrs_search_btn"]').on('click', (function () {
        var $form = $('form').first();
        AjaxZip2.zip2addr(
            'input_forms',
            'zip1',
            'pref_id',
            'address',
            'zip2',
            '',
            'building',
            $form.data('featureId'),
            $form.data('id'),
            $('input[name="ticket"]').val()
        );
        $('input').filter('[name="zip1"],[name="zip2"]').trigger('focusout');
    }));

    $('input[name="employ_cd"]').on('change', function() {
        $(".center_id").prop('checked', false);
        $(".occup_radio").prop('checked', false);
        $(".occup_radio ").prop('disabled', true);
        $(".occup_radio ").parent().css({"font-weight":"normal", "color":"#DDD"});
        getEigyoShoData();
    });

    $('input[name="center_id"]').on('change', function() {
        var center_id = $(this).attr('data-value');
        $("#center_id_hidden").val(center_id);
    });

    $('input[name="occupation_cd"]').on('change', function() {
        var occupation_cd = $(this).attr('data-value');
        $("#occupation_cd_hidden").val(occupation_cd);
    });

    $(function() {
        getEigyoShoData("", "", true);
        getOccupationData("", "", true);
    });
});

function getEigyoShoData(live, thisId, isFirst){
    // ie11 ではデフォルト引数が使えないため以下のように処理する
    if(typeof live === 'undefined') live = "";
    if(typeof thisId === 'undefined') thisId = "";
    if(typeof isFirst === 'undefined') isFirst = false;
    
    sgwns.api('/common/php/SearchEigyoSho.php', getFormData(), (function(data) {
        var newarray = [];
        var i= 0;
        var centerId = $('.center_rd:checked').attr('id');
        
        Object.keys(data).forEach(function (key) {
            newarray[i] = data[key];
            $(".center_"+data[key]).attr('data-value',key);
            i++;
        });

        if(live != "1"){
            $(".center_rd").prop('checked', false);
            var li = $("li.center_id");
            var radiosBtns = li.find("input[type='radio']");
            var doSomeWork = function(i,radiobtn){
                var txt = $("#center_id_radio"+i).attr("data-href");
                if(newarray.indexOf(txt) !== -1){
                    $("#center_id_radio"+i).attr("disabled",false);
                    $("#center_id_radio"+i).parent().css({"font-weight":"bold","color":"black"});
                }else{
                   $("#center_id_radio"+i).attr("disabled",true);
                   $("#center_id_radio"+i).parent().css({"font-weight":"normal","color":"#DDD"});
                }
            };
            $.each(radiosBtns,doSomeWork);
        }else{
            var center_id = $("#"+thisId).attr('data-value');
            $("#center_id_hidden").val(center_id);
            getOccupationData();
        }
        
        if(isFirst) {
            if (centerId && centerId != "") {
                $('#' + centerId).prop('checked', true);
            }
        }
    }));
}

$("body").delegate('input[name="center_id"]','change',function(){
    var center_id_tag = $(this).attr('id');
    getEigyoShoData("1",center_id_tag);
});

$("body").delegate('input[name="occupation_cd"]','change',function(){
    var occupation_id_tag = $(this).attr('id');
    getOccupationData("1", occupation_id_tag);
});

function getOccupationData(live, thisId, isFirst){
    // ie11 ではデフォルト引数が使えないため以下のように処理する
    if(typeof live === 'undefined') live = "";
    if(typeof thisId === 'undefined') thisId = "";
    if(typeof isFirst === 'undefined') isFirst = false;
    
    var occupId = $('.occup_radio:checked').attr('id');

    sgwns.api('/common/php/SearchOccupation.php', getFormData2(), (function(data) {
        var newarray1 = [];
        var i= 0;
        Object.keys(data).forEach(function (key) {
            newarray1[i] = data[key];
             $(".occup_"+data[key]).attr('data-value',key);
          //  $(".occup_"+data[key]).val(key);
            i++;
        });
        //カスタマーサービス（CS）職
//"カスタマーサービス（CS）職"
        if(live != "1"){
            $(".occup_radio").prop('checked', false);
            var li = $("li.occup_id");
            var radiosBtns = li.find("input[type='radio']");
            var doSomeWork = function(i, radiobtn){
                var txt = $("#occupation_cd"+i).attr("data-href");
                if(newarray1.indexOf(txt) !== -1){
                     $("#occupation_cd"+i).attr("disabled",false);
                     $("#occupation_cd"+i).parent().css({"font-weight":"bold","color":"black"});
                }else{
                    $("#occupation_cd"+i).attr("disabled",true);
                    $("#occupation_cd"+i).parent().css({"font-weight":"normal","color":"#DDD"});
                }
            };
            $.each(radiosBtns,doSomeWork);
        }else{
            var occupation_cd = $("#"+thisId).attr('data-value');
            $("#occupation_cd_hidden").val(occupation_cd);
        }
        
        if(isFirst) {
            if (occupId && occupId != "") {
                $('#' + occupId).prop('checked', true);
            }
        }
    }));
}

function getFormData() {
    var $form = $('form').first(),
        data = $form.serializeArray();
    data.push({
        name: 'employ_cd',
        value: $('input[name="employ_cd"]:checked').val() 
    });

    return data;
}

function getFormData2() {
    var $form = $('form').first(),
        data = $form.serializeArray();
    data.push({
        name: 'center_id',
        value: $('#center_id_hidden').val() 
    });

    return data;
}