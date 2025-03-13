/*global jQuery,_,AjaxZip2,sgwns*/
////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 住所検索
//////////////////////////////////////////////////////////////////////////////////////////////////////////
$('input[name="adrs_search_btn"]').on('click', (function () {
    var $form = $('form').first();
    AjaxZip2.zip2addr(
        'input_forms',
        'comiket_zip1',
        'comiket_pref_cd_sel',
        'comiket_address',
        'comiket_zip2',
        '',
        '',
        $form.data('featureId'),
        $form.data('id'),
        $('input[name="ticket"]').val()
    );

    $('input').filter('[name="comiket_zip1"],[name="comiket_zip2"]').trigger('focusout');
}));

