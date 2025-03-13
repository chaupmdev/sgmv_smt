const changeTerminal = function () {
    $('.arrival').find('input').val('');
    if (!$('[name=comiket_detail_outbound_delivery_date_year_sel]').val()
                    || !$('[name=comiket_detail_outbound_delivery_date_month_sel]').val()
                    || !$('[name=comiket_detail_outbound_delivery_date_day_sel]').val()
                    ) { 
        resetArrivalSelect();
    } else {
        getDataUnloadingCal();
    }

    var val = parseInt($('input[name="comiket_detail_type_sel2"]').filter(':checked').val(), 10),
            existDeparture,
            existArrival;
    if (_.isNaN(val)) {
            val = 0;
    }
    existDeparture = ((val & 1) === 1);
    existArrival = ((val & 2) === 2);
    fadeToggle($('.departure'), existDeparture);
    fadeToggle($('.arrival'), existArrival);
};

function fadeToggle($object, isShowing) {
    if (isShowing) {
        $object.fadeIn(300).removeAttr('style');
    } else {
        $object.fadeOut(300);
    }
}
$('input[name="comiket_detail_type_sel2"]').on('change', changeTerminal);
// お届け日時（年）プルダウンリスト変更時
$('select[name=comiket_detail_outbound_delivery_date_day_sel]').on('change', function(e) {
    if (!$('[name=comiket_detail_outbound_delivery_date_year_sel]').val()
                    || !$('[name=comiket_detail_outbound_delivery_date_month_sel]').val()
                    || !$('[name=comiket_detail_outbound_delivery_date_day_sel]').val()
                    ) { 
        resetArrivalSelect();
        return false;
    }
    // お届け可能日付範囲を取得
    getDataUnloadingCal();
});

$('select[name=comiket_detail_outbound_delivery_date_month_sel]').on('change', function(e) {
    if (!$('[name=comiket_detail_outbound_delivery_date_year_sel]').val()
                    || !$('[name=comiket_detail_outbound_delivery_date_month_sel]').val()
                    || !$('[name=comiket_detail_outbound_delivery_date_day_sel]').val()
                    ) { 
        resetArrivalSelect();
        return false;
    }

    // お届け可能日付範囲を取得
    getDataUnloadingCal();
});

$('select[name=comiket_detail_outbound_delivery_date_year_sel]').on('change', function(e) {
    if (!$('[name=comiket_detail_outbound_delivery_date_year_sel]').val()
                    || !$('[name=comiket_detail_outbound_delivery_date_month_sel]').val()
                    || !$('[name=comiket_detail_outbound_delivery_date_day_sel]').val()
                    ) { 
        resetArrivalSelect();
        return false;
    }

    // お届け可能日付範囲を取得
    getDataUnloadingCal();
});


function resetArrivalSelect() {
    let $select = $('select[name="comiket_detail_collect_date_sel"]');
    let option = '<option value="">選択してください</option>';
    $select.empty().append(option);
}

////////////////////////////////////////////////////////////////////////////////////////////////
//  復路・搬出：お届け可能日付範囲を取得
//  @returns {undefined}
////////////////////////////////////////////////////////////////////////////////////////////////
function getDataUnloadingCal() {
    let day = $('[name=comiket_detail_outbound_delivery_date_day_sel]').val();
    let month = $('[name=comiket_detail_outbound_delivery_date_month_sel]').val();
    let year = $('[name=comiket_detail_outbound_delivery_date_year_sel]').val();
    let frDt = year + '-' + month + '-' + day;
    let theDate = new Date(frDt);

    let $select = $('select[name="comiket_detail_collect_date_sel"]'),
        begin,
        end,
        beginYear,
        endYear,
        data,
        option,
        val;

    begin = new Date(theDate);
    begin.setDate(begin.getDate() + 1);//チェックIN日より、翌日
    end = new Date(theDate);
    console.log(theDate);
    console.log(ADD_DELIVERY_DAYS);
    end.setDate(end.getDate() + parseInt(ADD_DELIVERY_DAYS, 10));
    let strDateArr = [];
    for (let d = begin; d <= end; d.setDate(d.getDate() + 1)) {
        let colletDateMonth = d.getMonth() + 1;
        if (colletDateMonth < 10) {
            colletDateMonth = '0' + colletDateMonth;
        }
        let colletDateDay = d.getDate();
        if (colletDateDay < 10) {
            colletDateDay = '0' + colletDateDay;
        }
        let row = {
            ids: d.getFullYear() + '-' + colletDateMonth + '-' + colletDateDay, 
            names: d.getFullYear() + '年' + (d.getMonth() + 1) + '月' + d.getDate() + '日',
        }
        strDateArr.push(row);
    }
    option = '<option value="">選択してください</option>';
    strDateArr.forEach(function(item) {
        option += '<option value="' + item.ids + '">' + item.names + '</option>';
    });

    val = '';//$select.val();
    $select.empty().append(option).val(val);
}