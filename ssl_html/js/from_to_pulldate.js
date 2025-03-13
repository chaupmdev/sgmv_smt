////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function getAttrFromDate(gid) {
    var from = getAttrFromDate2(gid);
    if (from && from != '') {
        return from;
    }

    from = $('.from_to_selectbox_y[_gid=' + gid + ']').attr('_from');
    if (!from || from == '') {
        from = $('.from_to_selectbox_m[_gid=' + gid + ']').attr('_from');
        if (!from || from == '') {
            from = $('.from_to_selectbox_d[_gid=' + gid + ']').attr('_from');
        }
    }
    return from;
}

function getAttrFromDate2(gid) {
    var fromSlctr = $('.from_to_selectbox_y[_gid=' + gid + ']').attr('_from_slctr');
    var from = $(fromSlctr).val();
    if (!from || from == '') {
        fromSlctr = $('.from_to_selectbox_m[_gid=' + gid + ']').attr('_from_slctr');
        from = $(fromSlctr).val();
        if (!from || from == '') {
            fromSlctr = $('.from_to_selectbox_d[_gid=' + gid + ']').attr('_from_slctr');
            from = $(fromSlctr).val();
        }
    }
    return from;
}

function getAttrFromSlctr(gid) {
    var fromSlctr = $('.from_to_selectbox_y[_gid=' + gid + ']').attr('_from_slctr');
    if (!fromSlctr || fromSlctr == '') {
        fromSlctr = $('.from_to_selectbox_m[_gid=' + gid + ']').attr('_from_slctr');
        if (!fromSlctr || fromSlctr == '') {
            fromSlctr = $('.from_to_selectbox_d[_gid=' + gid + ']').attr('_from_slctr');
        }
    }
    return fromSlctr;
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function getAttrToDate(gid) {

    var to = getAttrToDate2(gid);
    if (to && to != '') {
        return to;
    }

    to = $('.from_to_selectbox_y[_gid=' + gid + ']').attr('_to');
    if (!to || to == '') {
        to = $('.from_to_selectbox_m[_gid=' + gid + ']').attr('_to');
        if (!to || to == '') {
            to = $('.from_to_selectbox_d[_gid=' + gid + ']').attr('_to');
        }
    }
    return to;
}

function getAttrToDate2(gid) {
    var toSlctr = $('.from_to_selectbox_y[_gid=' + gid + ']').attr('_to_slctr');
    var to = $(toSlctr).val();
    if (!to || to == '') {
        toSlctr = $('.from_to_selectbox_m[_gid=' + gid + ']').attr('_to_slctr');
        to = $(toSlctr).val();
        if (!to || to == '') {
            toSlctr = $('.from_to_selectbox_d[_gid=' + gid + ']').attr('_to_slctr');
            to = $(toSlctr).val();
        }
    }
    return to;
}

function getAttrToSlctr(gid) {
    var toSlctr = $('.from_to_selectbox_y[_gid=' + gid + ']').attr('_to_slctr');
    if (!toSlctr || toSlctr == '') {
        toSlctr = $('.from_to_selectbox_m[_gid=' + gid + ']').attr('_to_slctr');
        if (!toSlctr || toSlctr == '') {
            toSlctr = $('.from_to_selectbox_d[_gid=' + gid + ']').attr('_to_slctr');
        }
    }
    return toSlctr;
}
    
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
var g_DateList = {};
var g_fromList = {};
var g_toList = {};
function fromToPlldateInit() {
    $(".from_to_selectbox_y").each(function() {
        var gid = $(this).attr('_gid');

        var fromDate = getAttrFromDate(gid);
        var toDate = getAttrToDate(gid);
        
        // 日付が変更ないものはプルダウンの再構築しないためにグローバル変数にいれておく
        if (fromDate==g_fromList[gid] && toDate == g_toList[gid]) {
            return true;
        }

        if (!fromDate || fromDate == ""
                || !toDate || toDate == "") {
            return true;
        }

        var fromSplitList = fromDate.split('-');
        var toSplitList = toDate.split('-');

        if (!fromSplitList || fromSplitList.length != 3
                   || !toSplitList || toSplitList.length != 3) {
            return true;
        }

        var dateList = getSelectboxDateList(fromDate, toDate, gid);
        
        // 日付が変更ないものはプルダウンの再構築しないためにグローバル変数にいれておく
        g_DateList[gid] = dateList;
        g_fromList[gid] = fromDate;
        g_toList[gid] = toDate;

        //////////////////////////////////////////////////////////////////////////////
        // 年の設定
        //////////////////////////////////////////////////////////////////////////////
        var selectorYear = ".from_to_selectbox_y[_gid=" + gid + "]";
        var fromYear = parseInt(fromSplitList[0]);
        var toYear = parseInt(toSplitList[0]);

        $(this).empty();
        var first = $(this).attr('_first');
        if (first && first != "" && fromYear != toYear) {
            var option = $('<option>').val("").text(first);
            $(this).append(option);
            for (i = fromYear; i <= toYear; i++) {
                var option = $('<option>').val(i).text(i);
                $(this).append(option);
            }
            var defSelectedYear = $(this).attr('_selected');
            $(this).val(defSelectedYear);
        } else {
            var option = $('<option>').val(fromYear).text(fromYear);
            $(this).append(option);
            $(this).val(fromYear);
        }


        //////////////////////////////////////////////////////////////////////////////
        // 月の設定
        //////////////////////////////////////////////////////////////////////////////
        var selectorMonth = ".from_to_selectbox_m[_gid=" + gid + "]";

        var fromMonth = parseInt(fromSplitList[1]);
        var toMonth = parseInt(toSplitList[1]);
        $(selectorMonth).empty();
        var first = $(selectorMonth).attr('_first');
        if (first && first != "" && (fromYear != toYear || fromMonth != toMonth)) {
            var option = $('<option>').val("").text(first);
            $(selectorMonth).append(option);
        }
        var selYear = $(selectorYear + " option:selected").val();

        if (selYear && selYear != "") {
            var monthList = dateList[selYear];
            if (!monthList) {
                monthList = [];
            }

            Object.keys(monthList).forEach(function(prop) {
                if (!prop || prop == "") {
                    return true;
                }
                if (prop.length == '1') {
                    prop = '0' + prop;
                }

                var option = $('<option>').val(prop).text(prop);
                $(selectorMonth).append(option);
            });
            
            var defSelectedMonth = $(selectorMonth).attr('_selected');
            var isDefDataMonth = false;
            $(selectorMonth + " option").each(function() {
                if($(this).val() == defSelectedMonth) {
                    isDefDataMonth = true;
                    return false;
                }
            });
            
            if (monthList.length == 1 || !isDefDataMonth) {
                $(selectorMonth).prop("selectedIndex", 0);
            } else {
                if (defSelectedMonth && defSelectedMonth != "") {
                    $(selectorMonth).val(defSelectedMonth);
                }
            }
        }


        //////////////////////////////////////////////////////////////////////////////
        // 日の設定
        //////////////////////////////////////////////////////////////////////////////
        var selectorDay = ".from_to_selectbox_d[_gid=" + gid + "]";

        var fromDay = parseInt(fromSplitList[2]);
        var toDay = parseInt(toSplitList[2]);
        $(selectorDay).empty();
        var first = $(selectorDay).attr('_first');
        //if (first && first != "" && fromDay != toDay) {
        if (first && first != "" && (fromDay != toDay || fromYear != toYear || fromMonth != toMonth)) {
            var option = $('<option>').val("").text(first);
            $(selectorDay).append(option);
        }

        var selMonth = $(selectorMonth + " option:selected").val();

        if (selMonth && selMonth != "") {
            selMonth = String(parseInt(selMonth));
            var dayList = dateList[selYear][selMonth];
            $.each(dayList, function(prop, val) {
                if (val.length == '1') {
                    val = '0' + val;
                }
                var option = $('<option>').val(val).text(val);
                $(selectorDay).append(option);
            });
            var defSelectedDay = $(selectorDay).attr('_selected');
            
            var isDefDataDay = false;
            $(selectorDay + " option").each(function() {
                if($(this).val() == defSelectedDay) {
                    isDefDataDay = true;
                    return false;
                }
            });
            
            if (dayList.length == 1 || !isDefDataDay) {
                $(selectorDay).prop("selectedIndex", 0);
            } else {
                $(selectorDay).val(defSelectedDay);
            }
        }
    });
}
    
    /**
     * 
     * @param {type} fromYmd
     * @param {type} toYmd
     * @returns {undefined}
     */
    function getSelectboxDateList(fromYmd, toYmd, gid) {
        
//        if (gid && gid != "" && g_DateList[gid] && 1 <= g_DateList[gid].length) {
//            return g_DateList[gid];
//        }
        if (fromYmd==g_fromList[gid] && toYmd == g_toList[gid]) {
            return g_DateList[gid];
        }
        
        var fromSplitList = fromYmd.split('-');
        var toSplitList = toYmd.split('-');
        
        var dateList = new Array();

        var fromDate = new Date(fromSplitList[0], fromSplitList[1]-1, fromSplitList[2]);
        var toDate = new Date(toSplitList[0], toSplitList[1]-1, toSplitList[2]);
        for (dt = fromDate; dt <= toDate; dt.setDate(dt.getDate() + 1)) {
            var yearKey = String(dt.getFullYear());
            if(!dateList[yearKey]) {
                dateList[yearKey] = new Array();
            }
            
            var monthKey = String(dt.getMonth()+1);
            
            if (!dateList[yearKey][monthKey]) {
                dateList[yearKey][monthKey] = new Array();
            }
            var dayVal = String(dt.getDate());
            
            dateList[yearKey][monthKey].push(dayVal);
        }
        
        g_DateList[gid] = dateList;
        g_fromList[gid] = fromYmd;
        g_toList[gid] = fromYmd;
        
        return dateList;
    }

$(function () {
    
    /**
    * 指定した長さで０埋めする関数
    * @param {number} number ０埋めする数値
    * @param {number} length ０埋めする長さ
    **/
    function padStartWith0(number, length){
      return number.toString().padStart(length, '0');
    }
    
    
    $(function() {
        fromToPlldateInit();
    });
    
    
    $('.from_to_selectbox_y').on('change', function() {
        
        ////////////////////////////////////////////////////////////////////////////////////////////////////
        
        var gid = $(this).attr('_gid');
        
        var fromDate = getAttrFromDate(gid);
        var toDate = getAttrToDate(gid);
        
        var fromSplitList = fromDate.split('-');
        var toSplitList = toDate.split('-');
        
        var dateList = getSelectboxDateList(fromDate, toDate, gid);
        
        var selectorYear = ".from_to_selectbox_y[_gid=" + gid + "]";
        var selYear = $(selectorYear  + " option:selected").val();
        
        var fromYear = parseInt(fromSplitList[0]);
        var toYear = parseInt(toSplitList[0]);
        
        ////////////////////////////////////////////////////////////////////////////////////////////////////

        var selectorMonth = ".from_to_selectbox_m[_gid=" + gid + "]";

        var fromMonth = parseInt(fromSplitList[1]);
        var toMonth = parseInt(toSplitList[1]);
        $(selectorMonth).empty();
        var first = $(selectorMonth).attr('_first');
        if (first && first != "" && (fromYear != toYear || fromMonth != toMonth)) {
            var option = $('<option>').val("").text(first);
            $(selectorMonth).append(option);
        }

        if (fromYear != toYear || fromMonth != toMonth) {
            var monthList = dateList[selYear];
            if (!monthList) {
                monthList = [];
            }
            Object.keys(monthList).forEach(function(prop) {
                if (!prop || prop == "") {
                    return true;
                }
                if (prop.length == '1') {
                    prop = '0' + prop;
                }
                var option = $('<option>').val(prop).text(prop);
                $(selectorMonth).append(option);
            });
        }
        var selectorDay = ".from_to_selectbox_d[_gid=" + gid + "]";
        $(selectorDay).empty();
        var fromDay = parseInt(fromSplitList[2]);
        var toDay = parseInt(toSplitList[2]);
        var first = $(selectorDay).attr('_first');
        if (first && first != "" && 
                (fromYear != toYear || fromMonth != toMonth || fromDay != toDay)) {
            var option = $('<option>').val("").text(first);
            $(selectorDay).append(option);
        }
    });
   
    $('.from_to_selectbox_m').on('change', function() {
        
        ////////////////////////////////////////////////////////////////////////////////////////////////////
        
        var gid = $(this).attr('_gid');
        
        var fromDate = getAttrFromDate(gid);
        var toDate = getAttrToDate(gid);
        
        var fromSplitList = fromDate.split('-');
        var toSplitList = toDate.split('-');
        
        var dateList = getSelectboxDateList(fromDate, toDate, gid);
        
        var selectorYear = ".from_to_selectbox_y[_gid=" + gid + "]";
        var selYear = $(selectorYear + " option:selected").val();
        
        ////////////////////////////////////////////////////////////////////////////////////////////////////
        
        var selectorMonth = ".from_to_selectbox_m[_gid=" + gid + "]";
        var selMonth = $(selectorMonth + " option:selected").val();
        

        var fromMonth = parseInt(fromSplitList[1]);
        var toMonth = parseInt(toSplitList[1]);
        
        var fromYear = parseInt(fromSplitList[0]);
        var toYear = parseInt(toSplitList[0]);
        
        ////////////////////////////////////////////////////////////////////////////////////////////////////
        
        var selectorDay = ".from_to_selectbox_d[_gid=" + gid + "]";

        var fromDay = parseInt(fromSplitList[2]);
        var toDay = parseInt(toSplitList[2]);
        $(selectorDay).empty();
        var first = $(selectorDay).attr('_first');
        if (first && first != "" && 
                (fromYear != toYear || fromMonth != toMonth || fromDay != toDay)) {
            var option = $('<option>').val("").text(first);
            $(selectorDay).append(option);
        }

        if (selYear && selYear != "" &&
                selMonth && selMonth != "" &&
                (fromYear != toYear || fromMonth != toMonth)
                ) {
            selMonth = String(parseInt(selMonth));
            var dayList = dateList[selYear][selMonth];
            $.each(dayList, function(prop, val) {
                if (val.length == '1') {
                    val = '0' + val;
                }
                var option = $('<option>').val(val).text(val);
                $(selectorDay).append(option);
            });
        }
    });
});