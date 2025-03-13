/*global jQuery,_,AjaxZip2,sgwns*/
//travel.embarkation_date(乗船日)より、集荷期間開始
const COLLECT_STATRT = 8;//１１日前から１５日前に変更したものから8日前に変更
//travel.embarkation_date(乗船日)より、集荷期間終了
const COLLECT_END = 4; //５日前から８日前に変更したのものから4日前に変更

// jQueryと他のライブラリの競合を回避するため、引数でjQueryを$にする
(function ($) {
    'use strict';

    if ($.fn && $.fn.autoKana) {
        $.fn.autoKana('input[name="surname"]', 'input[name="surname_furigana"]', {
            katakana: true
        });
        $.fn.autoKana('input[name="forename"]', 'input[name="forename_furigana"]', {
            katakana: true
        });
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

    function addOption($select, data) {
        var option = '';
        if (!data || (data.ids && data.ids.length !== 1)) {
            option += '<option value="">選択してください</option>';
        }
        if (data) {
            if (data.dates) {
                $.each(data.ids, (function (key, value) {
                    option += '<option data-date="' + data.dates[key] + '" value="' + value + '">' + data.names[key] + '</option>';
                }));
            } else {
                $.each(data.ids, (function (key, value) {
                    option += '<option value="' + value + '">' + data.names[key] + '</option>';
                }));
            }
        }
        $select.empty().append(option);
        $('select[name="travel_departure_cd_sel"]').trigger('change');
    }

    function fadeToggle($object, isShowing) {
        if (isShowing) {
            $object.fadeIn(300).removeAttr('style');
            if (isIE8) {
                $object.filter('dd').css({
                    'padding-left': '0',
                    'width': '650px'
                });
            }
        } else {
            $object.fadeOut(300);
        }
    }

    var changeTerminal = function () {
            var val = parseInt($('input[name="terminal_cd_sel"]').filter(':checked').filter(':enabled').val(), 10),
                existDeparture,
                existArrival;
            if (_.isNaN(val)) {
                val = 0;
            }
            existDeparture = ((val & 1) === 1);
            existArrival = ((val & 2) === 2);
            fadeToggle($('.departure'), existDeparture);
            fadeToggle($('.arrival'), existArrival);
            fadeToggle($('#quantity,#quantity_number'), existDeparture || existArrival);
        },
        disableTime = function ($select, data) {
            $select.find('option').prop('disabled', data);
            if (data) {
                $select.val('00').find(':selected').prop('disabled', false);
            }
        },
        ua = window.navigator.userAgent.toLowerCase(),
        ver = window.navigator.appVersion.toLowerCase(),
        isIE8 = ua.indexOf('msie') !== -1 && ver.indexOf('msie 8.') !== -1,
        travelAddOption,
        departureAddOption,
        arrivalAddOption,
        zips = {};

    function disableTerminal() {
        var departurOption = $('select[name="travel_departure_cd_sel"]').find('option'),
            arrivalOption = $('select[name="travel_arrival_cd_sel"]').find('option'),
            existDeparture = (departurOption.length > 1 || departurOption.first().val() !== ''),
            existArrival = (arrivalOption.length > 1 || arrivalOption.first().val() !== ''),
            enabledTerminal;
        $('#terminal1').prop('disabled', !existDeparture);
        $('#terminal2').prop('disabled', !existArrival);
        $('#terminal3').prop('disabled', !existDeparture || !existArrival);
        enabledTerminal = $('input[name="terminal_cd_sel"]').filter(':enabled');
        if (enabledTerminal.length === 1) {
            enabledTerminal.prop('checked', true).trigger('change');
        } else {
            changeTerminal();
        }
    }

    disableTerminal();

    function getDate(date) {
        var week = ['日', '月', '火', '水', '木', '金', '土'];
        return date.getFullYear() + '年' + (date.getMonth() + 1) + '月' + date.getDate() + '日（' + week[date.getDay()] + '）';
    }

    // lodash.jsのbindで関数の第一引数をあらかじめ割り当てておく
    travelAddOption = _.bind(addOption, undefined, $('select[name="travel_cd_sel"]'));
    departureAddOption = _.bind(addOption, undefined, $('select[name="travel_departure_cd_sel"]'));
    arrivalAddOption = _.bind(addOption, undefined, $('select[name="travel_arrival_cd_sel"]'));

    $('input').filter('[name="zip1"],[name="zip2"]').on('focusout keydown keyup', (function () {
        var zip = $('input[name="zip1"]').val() + $('input[name="zip2"]').val(),
            $select = $('select[name="cargo_collection_st_time_cd_sel"]'),
            data = zips[zip];
        if (zip.length !== 7) {
            $select.find('option').prop('disabled', false);
            return;
        }
        if (data !== undefined) {
            disableTime($select, data);
            return;
        }
        sgwns.api('/common/php/SearchTimeZoneFlag.php', getFormData(), (function (data) {
            disableTime($select, data);
            zips[zip] = data;
        }));
    })).first().trigger('focusout');

    $('input[name="adrs_search_btn"]').on('click', (function () {
        var $form = $('form').first();
        AjaxZip2.zip2addr(
            'input_forms',
            'zip1',
            'pref_cd_sel',
            'address',
            'zip2',
            '',
            '',
            $form.data('featureId'),
            $form.data('id'),
            $('input[name="ticket"]').val()
        );
        $('input[name="address"]').removeAttr('style');
        $('input').filter('[name="zip1"],[name="zip2"]').trigger('focusout');
    }));
    
    /**
     * 復路のみラジオボタン、表示/非表示切り替え
     * @returns
     */
    function dispArrivalRadio () {
        var travelAgencyId = $('select[name="travel_agency_cd_sel"]').val();
        
        var isArraival = false;
        var dispnoneArrivalTravelAgencyIds = $('.radio-label-arrival').attr('dispnone_arrival_travel_agency_id_list');

        if (dispnoneArrivalTravelAgencyIds) {
            var dispnoneArrivalTravelAgencyIdList = dispnoneArrivalTravelAgencyIds.split(',');
            if(dispnoneArrivalTravelAgencyIdList.indexOf(travelAgencyId) >= 0) {
                isArraival = true;
            }
        }
        
        if (isArraival) {
            var radioTerminalCdSel = $('input[name="terminal_cd_sel"]:checked').val();
            if (radioTerminalCdSel == '2') { // 復路のみが選択されていた場合
                $('input[name="terminal_cd_sel"]').prop('checked', '');
                $('#terminal3').prop('checked', 'checked');
            }
            $('.radio-label-arrival').hide();
        } else {
            $('.radio-label-arrival').show();
        }
    }
    $('select[name="travel_agency_cd_sel"]').on('change', (function () {
        sgwns.api('/common/php/SearchTravel.php', getFormData(), travelAddOption).done((function () {
            $('select[name="travel_cd_sel"]').trigger('change');
        }));
        dispArrivalRadio();
    }));
    dispArrivalRadio();

    $('select[name="travel_cd_sel"]').on('change', (function () {
        sgwns.api('/common/php/SearchTravelTerminal.php', getFormData(), (function (data) {
            departureAddOption(data.departure);
            arrivalAddOption(data.arrival);
        })).done(disableTerminal);
    }));

    $('input[name="terminal_cd_sel"]').on('change', changeTerminal).trigger('change');

    $('select[name="travel_departure_cd_sel"]').on('change', (function () {
        var $paragraph = $('#cargo_collection_date').find('p'),
            str = $(this).find(':selected').first().data('date'),
            $select = $('select[name="cargo_collection_date_year_cd_sel"]'),
            begin,
            end,
            beginYear,
            endYear,
            data,
            option,
            val;
        $paragraph.html('&nbsp;');
        if (!str) {
            return;
        }
        begin = new Date(str);
        begin.setDate(begin.getDate() - COLLECT_STATRT);
        end = new Date(str);
        end.setDate(end.getDate() - COLLECT_END);
        $paragraph.text(getDate(begin) + 'から' + getDate(end) + 'まで選択できます。');
        beginYear = begin.getFullYear();
        endYear = end.getFullYear();
        data = {};
        data[beginYear] = beginYear;
        data[endYear] = endYear;
        option = '<option value="">年を選択</option>';
        $.each(data, (function () {
            option += '<option value="' + this + '">' + this + '</option>';
        }));
        val = $select.val();
        $select.empty().append(option).val(val);
    })).trigger('change');

    $('input[name="payment_method_cd_sel"]').on('change', (function () {
        var val = parseInt($(this).val(), 10);
        if (_.isNaN(val)) {
            val = 0;
        }
        fadeToggle($('#convenience'), (val & 1) === 1);
    })).filter(':checked').trigger('change');

    $('input[data-pattern]').filter('[data-pattern="^\\\\d+$"],[data-pattern="^\\\\w+$"],[data-pattern="^[!-~]+$"]').on('change', (function () {
        var $this = $(this);
        $this.val($this.val().replace(/[Ａ-Ｚａ-ｚ０-９]/g, (function (s) {
            return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
        })));
    }));

    if (!('placeholder' in document.createElement('input'))) {
        $('input[name="surname"]').on('change click focus focusout keydown keyup keypress', (function () {
            var $furigana = $('input[name="surname_furigana"]'),
                val = $furigana.val(),
                placeholder = $furigana.attr('placeholder');
            if ($.trim(val)) {
                if (val === placeholder) {
                    $furigana.css({
                        color: 'silver'
                    });
                } else {
                    $furigana.removeAttr('style');
                }
            } else {
                $furigana.val(placeholder).css({
                    color: 'silver'
                });
            }
        }));

        $('input[name="forename"]').on('change click focus focusout keydown keyup keypress', (function () {
            var $furigana = $('input[name="forename_furigana"]'),
                val = $furigana.val(),
                placeholder = $furigana.attr('placeholder');
            if ($.trim(val)) {
                if (val === placeholder) {
                    $furigana.css({
                        color: 'silver'
                    });
                } else {
                    $furigana.removeAttr('style');
                }
            } else {
                $furigana.val(placeholder).css({
                    color: 'silver'
                });
            }
        }));

        $('input').filter('[placeholder]').ahPlaceholder({
            placeholderAttr: 'placeholder'
        });
    }
}(jQuery));