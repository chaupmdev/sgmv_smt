

$(document).ready(function(){

// 購入日 Datepicker
$("#irai_kounyuuhi").datepicker({
    showOn: "focus",
    changeMonth: true,
    changeYear: true,
    maxDate: 0,
    minDate: new Date('2009/04/01'),
    showButtonPanel: true,
    yearRange: '2009:+0',
});

// 故障発生日 Datepicker
$("#irai_zikohi").datepicker({
    showOn: "focus",
    changeMonth: true,
    changeYear: true,
    maxDate: 0,
    showButtonPanel: true,
    yearRange: '-20:+0',
});

// 製造年月 Datepicker
$("#irai_seizou").ympicker({
    changeYear: true,
    format: 'yyyy/mm',
    autoclose: true,
    minViewMode: 'months',
    language: 'ja',
    endDate: new Date(),
    clearBtn: true,
});

(function () {
var old_fn = $.datepicker._updateDatepicker;
$.datepicker._updateDatepicker = function (inst) {
    old_fn.call(this, inst);
    var buttonPane = $(this).datepicker("widget").find(".ui-datepicker-buttonpane");
    var buttonHtml = "<button type='button' class='ui-datepicker-clean ui-state-default ui-priority-primary ui-corner-all'>クリア</button>";
    $(buttonHtml).appendTo(buttonPane).click(
            function (ev) {
                $.datepicker._clearDate(inst.input);
            });
    $(".ui-datepicker-current").hide();
}
})();

// ファイル入力
$(document).on('change', ':file', function () {
    var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.parent().parent().next(':text').val(label);
});            

$(document).on('keydown', '#irai_kounyuuhi', function (e) {
    if (e.keyCode === 8) {
        return false;
    }
});

$(document).on('keydown', '#irai_zikohi', function (e) {
    if (e.keyCode === 8) {
        return false;
    }
});

$(document).on('keydown', '#irai_seizou', function (e) {
    if (e.keyCode === 8) {
        return false;
    }
});

/*
 * カレンダーアイコン処理
 */
$('.datepicker-div').on('click', function () {
    $('#interconnection_date').val('');
    $(this).children('.datepicker').focus();
});

// 依頼者区分その他テキストボックス制御
$("#irai_iraisya").on("change", function () {
    if ($("#irai_iraisya").val() == "90") {
        $("#irai_iraisya_sonota").prop("disabled", false);
    } else {
        $("#irai_iraisya_sonota").prop("disabled", true);
        $("#irai_iraisya_sonota").val("");
    }
});         
window.onload = function () {
    if ($("#irai_iraisya").val() == "90") {
        $("#irai_iraisya_sonota").prop("disabled", false);
    } else {
        $("#irai_iraisya_sonota").readOnly = false;
        $("#irai_iraisya_sonota").val("");
    }
};

});