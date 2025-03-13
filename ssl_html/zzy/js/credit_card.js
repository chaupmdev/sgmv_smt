/*global jQuery,multiSend*/

// jQueryと他のライブラリの競合を回避するため、引数でjQueryを$にする
(function ($) {
    'use strict';

    $('input[data-pattern]').filter('[data-pattern="^\\\\d+$"],[data-pattern="^\\\\w+$"],[data-pattern="^[!-~]+$"]').on('change', (function () {
        var $this = $(this);
        $this.val($this.val().replace(/[Ａ-Ｚａ-ｚ０-９]/g, (function (s) {
            return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
        })));
    }));

    $('input').filter('[placeholder]').ahPlaceholder({
        placeholderAttr: 'placeholder'
    });

    $('#card_expire_month').change(function() {
		var tarmFrYear = Number($('span#term_fr_year').text());
		var tarmFrMonth = Number($('span#term_fr_month').text());
		var tarmFrMonthLimit = tarmFrMonth+1;
                var selectedMonth = Number($(this).val());
		var listNum=Number($('span#input_creditcard_cnt').text());
		var i;
		$('#card_expire_year option:not(:first-child)').remove();
        
        var isFirst = true;
        for(i=tarmFrYear;i<=tarmFrYear+listNum;i++){
            //if (isFirst && selectedMonth <= tarmFrMonth) {
            if (isFirst && selectedMonth < tarmFrMonth) {
                isFirst = false;
                continue;
            }
            $('#card_expire_year').append($('<option>').html(i).val(i));
        }
    });

}(jQuery));