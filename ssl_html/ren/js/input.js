

$(document).ready(function(){

    if ($.fn && $.fn.autoKana) {
        $.fn.autoKana('.personal_name', '.personal_name_furi', {
            katakana: true
        });
    }
    $(".hanToZen").blur(function(){
        var result = toFullWidth($(this).val());
        $(this).val(result.replace(/ /g, "　"));
    });


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

    $('input[name="occupation_cd"]').on('change', function() {
        var occupation_cd = $(this).attr('data-value');
        $("#occupation_cd_hidden").val(occupation_cd);
    });

    $(function() {
        getOccupationData("", "", true);
    });
    
    $('select[name=date_of_birth_year_cd_sel]').on('change', function(e) {
        if (!$('select[name=date_of_birth_year_cd_sel]').val() || !$('select[name=date_of_birth_month_cd_sel]').val()) {
            clearDay();
            return false;
        }
        let year = $('select[name=date_of_birth_year_cd_sel]').val();
        let month = $('select[name=date_of_birth_month_cd_sel]').val();
        const days = getDays(year, month);
        fillDay(days);

    });

    $('select[name=date_of_birth_month_cd_sel]').on('change', function(e) {
        if (!$('select[name=date_of_birth_year_cd_sel]').val() || !$('select[name=date_of_birth_month_cd_sel]').val()) {
            clearDay();
            return false;
        }
        let year = $('select[name=date_of_birth_year_cd_sel]').val();
        let month = $('select[name=date_of_birth_month_cd_sel]').val();
        const days = getDays(year, month);
        fillDay(days);
    });

});
function fillDay(days) {
    let option = '<option value="">--</option>';
    for(let i = 1; i<= days; i++) {
        option += '<option value="' + i + '">' + i + '</option>';
    }
    let $select = $('select[name=date_of_birth_day_cd_sel]');
    $select.empty().append(option).val('');
}
function clearDay() {
    let option = '<option value="">--</option>';
    let $select = $('select[name=date_of_birth_day_cd_sel]');
    $select.empty().append(option);
}
function getDays(year, month) {
    return new Date(year, month, 0).getDate();
}
