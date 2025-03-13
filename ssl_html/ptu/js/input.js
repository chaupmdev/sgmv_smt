/*global $,_,AjaxZip2,api,multiSend*/
$(function () {
    'use strict';

    function getDate(date) {
        var week = ['日', '月', '火', '水', '木', '金', '土'];
        return date.getFullYear() + '年' + (date.getMonth() + 1) + '月' + date.getDate() + '日（' + week[date.getDay()] + '）';
    }

    $('input[name="adrs_search_btn"]').on('click', function () {
        var $form = $('form').first();
        $.ajaxSetup({ async: false });
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
        $.ajaxSetup({ async: true });
        $('input[name="address"]').removeAttr('style');
        $('input').filter('[name="zip1"],[name="zip2"]').trigger('focusout');
        $('#pref_cd_sel').trigger('change');
    });

    $('input[name="adrs_search_btn_hksaki"]').on('click', function () {
        var $form = $('form').first();
        $.ajaxSetup({ async: false });
        AjaxZip2.zip2addr(
            'input_forms',
            'zip1_hksaki',
            'pref_cd_sel_hksaki',
            'address_hksaki',
            'zip2_hksaki',
            '',
            '',
            $form.data('featureId'),
            $form.data('id'),
            $('input[name="ticket"]').val()
        );
        $.ajaxSetup({ async: true });
        $('input[name="address_hksaki"]').removeAttr('style');
        $('input').filter('[name="zip1_hksaki"],[name="zip2_hksaki"]').trigger('focusout');
        $('#pref_cd_sel_hksaki').trigger('change');

    });

    $('#name_copy_btn').on('click', function () {
        $('#surname_hksaki').val($('#surname').val());
        $('#forename_hksaki').val($('#forename').val());
        $('#surname_hksaki').removeAttr('style');
        $('#forename_hksaki').removeAttr('style');

    });
    $('input[name="tel_copy_btn"]').on('click', function () {
    	$('#tel1_hksaki').val($('#tel1').val());
        $('#tel2_hksaki').val($('#tel2').val());
        $('#tel3_hksaki').val($('#tel3').val());
        $('#tel1_hksaki').removeAttr('style');
        $('#tel2_hksaki').removeAttr('style');
        $('#tel3_hksaki').removeAttr('style');

    });

	$('input[name="hikitori_yoteji_sel"]').on('change', function () {
		showHikitori();
		// オプション料金
		calcOutOptRyokin();
		// 見積金額
		mitsumoriKin();
	});

	$('input[name="hikoshi_yoteji_sel"]').on('change', function () {
		showHikoshi();
		// オプション料金
		calcInOptRyokin();
		// 見積金額
		mitsumoriKin();
	});

    $('#submit_button').on('click', function () {

    	$('.chkHikitoriTime').prop("disabled", false);
    	$('.chkHikitoriJustTime').prop("disabled", false);
    	$('.chkHikoshiTime').prop("disabled", false);
    	$('.chkHikoshiJustTime').prop("disabled", false);
    	$('.tanName').prop("disabled", false);
    	$('.clone_tanhin').find('#tanhin_cd_sel').prop("disabled", true);
    	$('.clone_tanhin').find('#tanNmFree').prop("disabled", true);
    	$('.clone_tanhin').find('#hidden_size').prop("disabled", true);

        $('form').first().attr('action', '/ptu/check_input').submit();
    });

    function showCon() {
        // コンビニ決済非表示暫定対応
        var show = ($.trim($('select[name="travel_cd_sel"]').val()) !== '22');
        $('#payment_method').find('label').filter('[for="pay_convenience_store"]').toggle(show).find('#pay_convenience_store').prop('disabled', !show);
        if (!show) {
            $('#pay_card').prop('checked', true).trigger('change');
        }
    }

    showCon();

    function fadeToggle($object, isShowing) {
        if (isShowing) {
            $object.fadeIn(300);
//            if (isIE8) {
//                $object.filter('dd').css({
//                    'padding-left': '0',
//                    'width': '650px'
//                });
//            }
        } else {
            $object.fadeOut(300);
        }
    }

    // お引取り予定日時
    showHikitori();
    // お引越し予定日時
    showHikoshi();

    $('input[name="payment_method_cd_sel"]').on('change', function () {
        var val = parseInt($(this).val(), 10);
        if (_.isNaN(val)) {
            val = 0;
        }
        if (val == '2') {
        	$("#convenience_store_cd_sel").val('');
        }
        fadeToggle($('#convenience'), (val & 1) === 1);
    }).filter(':checked').trigger('change');

    $('input[data-pattern]').filter('[data-pattern="^\\\\d+$"],[data-pattern="^\\\\w+$"],[data-pattern="^[!-~]+$"]').on('change', function () {
        var $this = $(this);
        $this.val($this.val().replace(/[Ａ-Ｚａ-ｚ０-９]/g, function (s) {
            return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
        }));
    });

    if (!('placeholder' in document.createElement('input'))) {
        $('input').filter('[placeholder]').ahPlaceholder({
            placeholderAttr: 'placeholder'
        });
    }

    $('input:text,select,textarea').first().focus();

    setEventAddRow();
    setEventRemoveTr();

    //カンマ、円マク付け
	setDeformat();

	// オプション表示
	var binshu = $("#binshu_cd").val();
	if (binshu == '906') {
		var tanhin = $("#tanhin_cd_sel").val();
		hinmokuShow();
		optReShow('');
	    // お引取り予定日時
	    showHikitori();
	    // お引越し予定日時
	    showHikoshi();
		// 料金計算
		calcOutOptRyokin();
		calcInOptRyokin();
		tanhinKinhonKinGokei();
	} else {
		// テキスト表示切替
		optTextDisplay();
		// 料金計算
		calcOutOptRyokin();
		calcInOptRyokin();
		getUnchinKin();
	}

	// 見積金額
	mitsumoriKin();

	// 都道府県と日付変更時
	$(".todofuken").change(function(e){
		if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			return false;
		}

		var binshu = $("#binshu_cd").val();
		if (binshu == '359') {
			var daisu_danpin = $("#cago_daisu").val();
			if (daisu_danpin != null && daisu_danpin != "") {
				// 基本料金を再計算
				getUnchinKin();
				// 見積金額
				mitsumoriKin();
			} else {
				$("#kihonKin").text("");
				$("#hanshutsuSum").text("");
				$("#hannyuSum").text("");
				$("#mitumoriZeinuki").text("");
				$("#zeiKin").text("");
				$("#mitumoriZeikomi").text("");
				$("#hidden_kihonKin").val("");
				$("#hidden_hanshutsuSum").val("");
				$("#hidden_hannyuSum").val("");
				$("#hidden_mitumoriZeinuki").val("");
				$("#hidden_zeiKin").val("");
				$("#hidden_mitumoriZeikomi").val("");
			}
		} else {
			tanhinKinhonKinGokei();
		}
	});

	// お引越し予定日時変更時
	$(".optChg").change(function(e){
		if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			return false;
		}
		// 繁忙期対応
		optTankaSet();

	});

	// カーゴ台数変更時
	$(".cagoChange").change(function(e){
		if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			return false;
		}

		var daisu = $("#cago_daisu").val();

		if (daisu != null && daisu != "" && daisu != "0") {

			// オプション料金を再計算
			calcOutOptRyokin();
			calcInOptRyokin();

			// 基本料金を再計算
			getUnchinKin();

			// 見積金額
			mitsumoriKin();

		} else {
			$("#kihonKin").text("");
			$("#hanshutsuSum").text("");
			$("#hannyuSum").text("");

			$("#mitumoriZeinuki").text("");
			$("#zeiKin").text("");
			$("#mitumoriZeikomi").text("");

			$("#hidden_kihonKin").val("");
			$("#hidden_hanshutsuSum").val("");
			$("#hidden_hannyuSum").val("");
			$("#hidden_mitumoriZeinuki").val("");
			$("#hidden_zeiKin").val("");
			$("#hidden_mitumoriZeikomi").val("");
		}

	});

	// 単品輸送変更時
	$(".tanhinChange").change(function(e){
		if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			return false;
		}

		var element = $(this);
		var tanhin = element.val();
		var obj =element.parents('li').find('.tanka');
		obj.text("");

		// 単品輸送品目その他の表示
		hinmokuShow();

		// オプション品目再表示
		optReShow('1');

	    // お引取り予定日時
	    showHikitori();
	    // お引越し予定日時
	    showHikoshi();

		// オプション料金を再計算
		calcOutOptRyokin();
		calcInOptRyokin();

		// 基本金額計算（運賃金額）
		getTanhinKihonKin(tanhin,obj);

	});

	// オプション料金搬出変更時
	$(".outOpt").change(function(e){
		if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			return false;
		}

		// テキスト表示切替
		optTextDisplay();

		calcOutOptRyokin();
		// 見積金額
		mitsumoriKin();
	});

	// オプション料金搬入変更時
	$(".inOpt").change(function(e){
		if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			return false;
		}

		// テキスト表示切替
		optTextDisplay();

		calcInOptRyokin();
		// 見積金額
		mitsumoriKin();
	});

});

// 合計サイズ、合計金額計算(単品輸送品目)
function tanhinKinKeisan() {

	var kinhonKinGokei = 0;
	var sizeGokei = 0;

	$("#sizeGokei").text('');
	$("#kihonKin").text('');
	$("#hidden_sizeGokei").val('');
	$("#hidden_kihonKin").val('');

	$(".textHst").val('');
	$(".textHyu").val('');
	$('#checkboxHanshutsu_004').prop("checked", false);
	$('#CheckboxHannyu_017').prop("checked", false);

	$.each($('.tanhinChange'), function(index,value) {

		var element = $(this);

		var kihonKin = removeMoneyFmt(element.parents('li').find('.tanka').text());

		if(isNum(kihonKin)){
			kinhonKinGokei += parseInt(kihonKin);
		} else {
			element.parents('li').find('.tanka').text('');
		}

		var selectedVal = element.children('option:selected').val();
		if (selectedVal != '') {
			var label = element.children('option:selected').text();
			var size = delcomma(label.split(":")[0]);
			if(isNum(size)){
				sizeGokei += parseInt(size);
			} else {
				element.parents('li').find('#hidden_size').val('');
			}
		}

	});

	$("#sizeGokei").text(addcomma(sizeGokei));
	$("#kihonKin").text(addMoneyFmt(kinhonKinGokei));
	$("#hidden_sizeGokei").val(sizeGokei);
	$("#hidden_kihonKin").val(kinhonKinGokei);

	// サイズから自動計算
	if (sizeGokei > 0) {
		var konboSize = Math.ceil(parseFloat(sizeGokei) / 100);
		$(".textHst").val(konboSize);
		$(".textHyu").val(konboSize);

		$('#checkboxHanshutsu_004').prop("checked", true);
		$('#CheckboxHannyu_017').prop("checked", true);

		// オプション料金を再計算
		calcOutOptRyokin();
		calcInOptRyokin();
		// 見積金額
		mitsumoriKin();
	}

}

function optTextDisplay() {
	var binshu = $("#binshu_cd").val();

	if (binshu == '359') {
		if ($('#checkboxHanshutsu_004').prop("checked")) {
			$('#textHanshutsu_004').removeAttr("readonly");
		} else {
			$('#textHanshutsu_004').val("");
			$('#textHanshutsu_004').attr("readonly","readonly");
		}

		if ($('#CheckboxHannyu_017').prop("checked")) {
			$('#textHannyu_017').removeAttr("readonly");
		} else {
			$('#textHannyu_017').val("");
			$('#textHannyu_017').attr("readonly","readonly");
		}
	}
}

function hinmokuShow() {

	$.each($('.tanhinChange'), function(index,value) {

		var element = $(this);
		var tanpin = element.val();
		if (tanpin == '99001' || tanpin == '99002'|| tanpin == '99003'
			|| tanpin == '99004'|| tanpin == '99005'|| tanpin == '99006'|| tanpin == '99007') {
			element.parents('li').find('#tanNmFree').prop("disabled", false);
		} else {
			element.parents('li').find('#tanNmFree').val("");
			element.parents('li').find('#tanNmFree').prop("disabled", true);
		}

	});
}

function showHikitori() {
	var yoteji = $('input[name="hikitori_yoteji_sel"]:checked').val();

	if (yoteji == '2') {
		$('#hikitori_yotehiji_time_cd_sel').css("display","inline");
		$('#hikitori_yotehiji_justime_cd_sel').css("display","none");
		$('.chkHikitoriTime').prop("checked", true);
		$('.chkHikitoriJustTime').prop("checked", false);
	} else if (yoteji == '3') {
		$('#hikitori_yotehiji_time_cd_sel').css("display","none");
		$('#hikitori_yotehiji_justime_cd_sel').css("display","inline");
		$('.chkHikitoriTime').prop("checked", false);
		$('.chkHikitoriJustTime').prop("checked", true);
	} else {
		$('#hikitori_yotehiji_time_cd_sel').css("display","none");
		$('#hikitori_yotehiji_justime_cd_sel').css("display","none");
		$('.chkHikitoriTime').prop("checked", false);
		$('.chkHikitoriJustTime').prop("checked", false);
	}

	$('.chkHikitoriTime').prop("disabled", true);
	$('.chkHikitoriJustTime').prop("disabled", true);

}

function showHikoshi() {
	var yoteji = $('input[name="hikoshi_yoteji_sel"]:checked').val();

	if (yoteji == '2') {
		$('#hikoshi_yotehiji_time_cd_sel').css("display","inline");
		$('#hikoshi_yotehiji_justime_cd_sel').css("display","none");

		$('.chkHikoshiTime').prop("checked", true);
		$('.chkHikoshiJustTime').prop("checked", false);
	} else if (yoteji == '3') {
		$('#hikoshi_yotehiji_time_cd_sel').css("display","none");
		$('#hikoshi_yotehiji_justime_cd_sel').css("display","inline");
		$('.chkHikoshiTime').prop("checked", false);
		$('.chkHikoshiJustTime').prop("checked", true);
	} else {
		$('#hikoshi_yotehiji_time_cd_sel').css("display","none");
		$('#hikoshi_yotehiji_justime_cd_sel').css("display","none");
		$('.chkHikoshiTime').prop("checked", false);
		$('.chkHikoshiJustTime').prop("checked", false);
	}

	$('.chkHikoshiTime').prop("disabled", true);
	$('.chkHikoshiJustTime').prop("disabled", true);
}

/**
 * オプション表示変更（初期表示、単品品目変更、行追加削除）
 */
function optReShow(kbn) {

	var tanhin = '';
	var count = $('.tanhinChange').size();
	if (count == 2) {
		tanhin = $('#tanhin_cd_sel').val();
	}

	// OP再表示
	if (tanhin != null && tanhin != ""
			&& tanhin != "99001" && tanhin != "99002" && tanhin != "99003"
				&& tanhin != "99004" && tanhin != "99005" && tanhin != "99006" && tanhin != "99007" && count == 2) {

		var param = {"tanhin":tanhin}
		api('/common/php/SearchTanpinyusoHinmoku.php', param, function (data) {
	        if (!data) {
	            return false;
	        }

	        $.each($('.hanshutsuOpt'), function(index,value) {
	        	var cd = $(this).find("#hd_HANSHUTSU_cd").val();
	        	if(data.optCds.indexOf(cd) < 0){
	        		$(this).css("display","none");
	        	} else {
	        		$(this).css("display","");
	        	}
			});

	        $.each($('.hannyuOpt'), function(index,value) {
	        	var cd = $(this).find("#hd_HANNYU_cd").val();
	        	if(data.optCds.indexOf(cd) < 0){
	        		$(this).css("display","none");
	        	} else {
	        		$(this).css("display","");
	        	}
			});
	    });
	} else if (kbn == '1') {

			// OP全表示
			$.each($('.hanshutsuOpt'), function(index,value) {
	     		$(this).css("display","");
	     		$(this).find(":checked").prop("checked", false);
	     		$(this).find(":text").val("");
			});

		    $.each($('.hannyuOpt'), function(index,value) {
	     		$(this).css("display","");
	     		$(this).find(":checked").prop("checked", false);
	     		$(this).find(":text").val("");
			});
	}
}

/**
 * 搬出オプションの料金計算
 */
function calcOutOptRyokin() {

	var binshu = $("#binshu_cd").val();
	// 搬出料金合計
	var hanshutsuTotal = 0;
	// カーゴ台数
	var cargoDaisu = 1;
	if (binshu == '359') {
		cargoDaisu = $("#cago_daisu").val();
	} else if (binshu == '906') {
		var leth = $(".tanhinChange").length;
		if (leth > 1) {
			cargoDaisu = parseInt(leth) - 1;
		}
	}

	if (cargoDaisu != null && cargoDaisu != "" && cargoDaisu != "0") {
		$.each($('.chkHst'), function() {

			var element = $(this);
			var tanka = element.parents('li').find('#hanshutsuTanka').text();
			var kbn = element.parents('li').find('#hd_HANSHUTSU_kbn').val();

			if (element.prop("checked")) {
				if (kbn == '3') {
					hanshutsuTotal += parseInt(removeMoneyFmt(tanka)) * parseInt(cargoDaisu);
				} else if (kbn == '4') {
					var set = 1;
					if (cargoDaisu > 3) {
						set = Math.ceil(cargoDaisu / 3);
					}
					hanshutsuTotal += parseInt(removeMoneyFmt(tanka)) * set;
				} else {
					hanshutsuTotal += parseInt(removeMoneyFmt(tanka));
				}

			}
		});

		$.each($('.textHst'), function() {

			var element = $(this);
			var tanka = element.parents('li').find('#hanshutsuTanka').text();
			var suryo = element.val();

			if (isNum(suryo)) {
				hanshutsuTotal += parseInt(removeMoneyFmt(tanka)) * parseInt(suryo);
			}
		});

		$("#hanshutsuSum").text(addMoneyFmt(hanshutsuTotal));
		$("#hidden_hanshutsuSum").val(hanshutsuTotal);
	}
}

/**
 * 搬入オプションの料金計算
 */
function calcInOptRyokin() {

	var binshu = $("#binshu_cd").val();
	// 搬出料金合計
	var hannyuTotal = 0;
	// カーゴ台数
	var cargoDaisu = 1;
	if (binshu == '359') {
		cargoDaisu = $("#cago_daisu").val();
	} else if (binshu == '906') {
		var leth = $(".tanhinChange").length;
		if (leth > 1) {
			cargoDaisu = parseInt(leth) - 1;
		}
	}

	if (cargoDaisu != null && cargoDaisu != "" && cargoDaisu != "0") {
		$.each($('.chkHyu'), function() {

			var element = $(this);
			var tanka = element.parents('li').find('#hannyuTanka').text();
			var kbn = element.parents('li').find('#hd_HANNYU_kbn').val();

			if (element.prop("checked")) {
				if (kbn == '3') {
					hannyuTotal += parseInt(removeMoneyFmt(tanka)) * parseInt(cargoDaisu);
				} else if (kbn == '4') {
					var set = 1;
					if (cargoDaisu > 3) {
						set = Math.ceil(cargoDaisu / 3);
					}
					hannyuTotal += parseInt(removeMoneyFmt(tanka)) * set;
				} else {
					hannyuTotal += parseInt(removeMoneyFmt(tanka));
				}

			}
		});

		$.each($('.textHyu'), function() {

			var element = $(this);
			var tanka = element.parents('li').find('#hannyuTanka').text();
			var suryo = element.val();

			if (isNum(suryo)) {
				hannyuTotal += parseInt(removeMoneyFmt(tanka)) * parseInt(suryo);
			}
		});

		$("#hannyuSum").text(addMoneyFmt(hannyuTotal));
		$("#hidden_hannyuSum").val(hannyuTotal);
	}
}

/**
 * 基本金額を計算(単身カーゴ)
 */
function getUnchinKin() {

	var hatsu = $("#pref_cd_sel").val();
	var chaku = $("#pref_cd_sel_hksaki").val();
	var binshu = $("#binshu_cd").val();
	var daisu = 1;
	if (binshu == '359') {
		daisu = $("#cago_daisu").val();
	}

	if (hatsu == '' || chaku == '' || daisu == '' || binshu == '') {
		return;
	}
	if ($("#hikitori_yotehiji_date_year_cd_sel").val() == '' || $("#hikitori_yotehiji_date_month_cd_sel").val() == '' || $("#hikitori_yotehiji_date_day_cd_sel").val() == '') {
		return;
	}

	$('#kihonKin').text('');
	$("#hidden_kihonKin").val("");

	api('/common/php/SearchCargoUnchin.php', getFormData(), function (data) {
        if (!data) {
            return false;
        }
		if (isNum(data) && isNum(daisu)) {
			if (binshu == '359') {
				$('#kihonKin').text(addMoneyFmt(parseInt(data) * parseInt(daisu)));
				$("#hidden_kihonKin").val(parseInt(data) * parseInt(daisu));
			} else {

			}

		} else {
			$('#kihonKin').text('');
			$("#hidden_kihonKin").val("");
		}
    }).done(mitsumoriKin);
}

/**
 * 基本金額を計算(単品輸送品目)
 */
function getTanhinKihonKin(tanhinVal,element) {

	var hatsu = $("#pref_cd_sel").val();
	var chaku = $("#pref_cd_sel_hksaki").val();
	var binshu = $("#binshu_cd").val();
	var daisu = 1;

	if (hatsu == '' || chaku == '' || daisu == '' || binshu == '') {
		return;
	}
	if ($("#hikitori_yotehiji_date_year_cd_sel").val() == '' || $("#hikitori_yotehiji_date_month_cd_sel").val() == '' || $("#hikitori_yotehiji_date_day_cd_sel").val() == '') {
		return;
	}

	element.text('');

	api('/common/php/SearchCargoUnchin.php', getFormDataTanhin(tanhinVal), function (data) {
//        if (!data) {
//            return false;
//        }
		if (isNum(data) && isNum(daisu)) {
			element.text(addMoneyFmt(parseInt(data) * parseInt(daisu)));
			$("#hidden_kihonKin").val(parseInt(data) * parseInt(daisu));
		}

		// 合計サイズ、合計金額計算(単品輸送品目)
		tanhinKinKeisan();

		// 見積金額を再計算
		mitsumoriKin();
    });
}

function tanhinKinhonKinGokei() {
	$("#sizeGokei").text('');
	$("#kihonKin").text('');
	$("#hidden_sizeGokei").val('');
	$("#hidden_kihonKin").val('');
	$(".textHst").val('');
	$(".textHyu").val('');
	$('#checkboxHanshutsu_004').prop("checked", false);
	$('#CheckboxHannyu_017').prop("checked", false);
	mitsumoriKin();
	$.each($('.tanhinChange'), function(index,value) {
		var daisu_danpin = '';
		var element = $(this);
		daisu_danpin = element.val();
		var obj =element.parents('li').find('.tanka');
		obj.text("");
		// 基本金額計算（運賃金額）
		getTanhinKihonKin(daisu_danpin,obj);
	});
}

/**
 * 見積金額を計算
 */
function mitsumoriKin() {

	var kihonKin = removeMoneyFmt($("#kihonKin").text());
	var hanshutsuSum = removeMoneyFmt($("#hanshutsuSum").text());
	var hannyuSum = removeMoneyFmt($("#hannyuSum").text());
	var shohizei = removeMoneyFmt($("#shohizei").val());
	var zeinuki = 0;

	if (shohizei == '' || !isNum(shohizei)) {
		return;
	}

	if (isNum(kihonKin)) {
		zeinuki += parseInt(kihonKin);
	}
	if (isNum(hanshutsuSum)) {
		zeinuki += parseInt(hanshutsuSum);
	}
	if (isNum(hannyuSum)) {
		zeinuki += parseInt(hannyuSum);
	}
	if (zeinuki > 0) {
		$('#mitumoriZeinuki').text(addMoneyFmt(zeinuki));
		$("#hidden_mitumoriZeinuki").val(zeinuki);
		$('#zeiKin').text(addMoneyFmt(Math.floor(zeinuki * parseInt(shohizei) / 100)));
		$("#hidden_zeiKin").val(Math.floor(zeinuki * parseInt(shohizei) / 100));
		$('#mitumoriZeikomi').text(addMoneyFmt(Math.floor(zeinuki * (100 + parseInt(shohizei)) / 100)));
		$("#hidden_mitumoriZeikomi").val(Math.floor(zeinuki * (100 + parseInt(shohizei)) / 100));
	} else {
		$("#mitumoriZeinuki").text("");
		$("#zeiKin").text("");
		$("#mitumoriZeikomi").text("");
		$("#hidden_mitumoriZeinuki").val("");
		$("#hidden_zeiKin").val("");
		$("#hidden_mitumoriZeikomi").val("");
	}

}

function getFormData() {

	var binshu = $("#binshu_cd").val();
	var hatsu = $("#pref_cd_sel").val();
	var chaku = $("#pref_cd_sel_hksaki").val();
	var dt = $("#hikitori_yotehiji_date_year_cd_sel").val() + "/" + $("#hikitori_yotehiji_date_month_cd_sel").val() + "/" + $("#hikitori_yotehiji_date_day_cd_sel").val();
	var tanhin = '';
	if (binshu == '906') {
		tanhin = $("#tanhin_cd_sel").val();
	}
	var data = {
			"hatsuArea"	:hatsu,
			"chakuArea"	:chaku,
			"hikitoriDate"	:dt,
			"tanhin"	:tanhin,
			"binshu"	:binshu
           }

    return data;
}

function getFormDataTanhin(tanhin) {

	var binshu = $("#binshu_cd").val();
	var hatsu = $("#pref_cd_sel").val();
	var chaku = $("#pref_cd_sel_hksaki").val();
	var dt = $("#hikitori_yotehiji_date_year_cd_sel").val() + "/" + $("#hikitori_yotehiji_date_month_cd_sel").val() + "/" + $("#hikitori_yotehiji_date_day_cd_sel").val();

	var data = {
			"hatsuArea"	:hatsu,
			"chakuArea"	:chaku,
			"hikitoriDate"	:dt,
			"tanhin"	:tanhin,
			"binshu"	:binshu
           }

    return data;
}

/**
 * オプション単価を再取得
 */
function optTankaSet() {

	if ($("#hikitori_yotehiji_date_year_cd_sel").val() == '' || $("#hikitori_yotehiji_date_month_cd_sel").val() == '' || $("#hikitori_yotehiji_date_day_cd_sel").val() == '') {
		return;
	}

	api('/common/php/SearchOptTanka.php', getFormData1(), function (data) {
        if (!data) {
            return false;
        }

		$.each($('.hanshutsuOpt'), function(index,value) {
			var element = $(this);
			var cd = element.find("#hd_HANSHUTSU_cd").val();
		//	element.find(":checked").prop("checked", false);
		//	element.find(":text").val("");

			var cds = data.cds;
			var tankas = data.tankas;
//			var index = cds.indexOf(cd);
			var index = $.inArray(cd,cds);
			if (index >= 0) {
				element.find('#hanshutsuTanka').text(addMoneyFmt(tankas[index]));
			}

		});

		$.each($('.hannyuOpt'), function(index,value) {
			var element = $(this);
			var cd = element.find("#hd_HANNYU_cd").val();
		//	element.find(":checked").prop("checked", false);
		//	element.find(":text").val("");
			var cds = data.cds;
			var tankas = data.tankas;
//			var index = cds.indexOf(cd);
			var index = $.inArray(cd,cds);
			if (index >= 0) {
				element.find('#hannyuTanka').text(addMoneyFmt(tankas[index]));
			}
		});

		// 消費税
		var shohizei = data.shohizei;
		$("#shohizei").val(shohizei)

		// オプション料金を再計算
		calcOutOptRyokin();
		calcInOptRyokin();
		// 見積金額
		mitsumoriKin();

    });
}

function getFormData1() {

	var binshu = $("#binshu_cd").val();
	var dt = $("#hikitori_yotehiji_date_year_cd_sel").val() + "/" + $("#hikitori_yotehiji_date_month_cd_sel").val() + "/" + $("#hikitori_yotehiji_date_day_cd_sel").val();
	var data = {
			"hikitoriDate"	:dt,
			"binshu"	:binshu
           }

    return data;
}

/**
 * カンマ、円マック付け
 */
function setDeformat() {

	// 単価
	$.each($(".money"), function() {
		var element = $(this);
		var value = element.text();
		element.text(addMoneyFmt(value));
	});
}

/**
 *  3桁カンマ付与
 * @param s
 * @returns
 */
function addcomma(s) {
	var arg = "" + s;
	if (arg == null || arg == "") {
		return "";
	}

	var regex = /^(-?)0{1,}[1-9]+/;
	var match = arg.match(regex);
	if (arg.match(regex)) {
		arg = arg.replace(/^(-?)0{1,}/, "");
		arg = match[1] + arg;

	}

	regex = /^(-?)0{1,}[0]+/;
	match = arg.match(regex);
	if (arg.match(regex)) {
		arg = '0';
	}
	var n = String(arg).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' );
	return n;
}

/**
 * 3桁カンマ削除
 * @param s
 * @returns
 */
function delcomma(s) {
	var n = new String(s).replace(/,/g, "");
	return n;
}

/**
 * 金額フォーマット 付与
 * @param s
 * @returns
 */
function addMoneyFmt(s) {
	var arg = addcomma(s);
	if (arg == null || arg == "") {
		return s;
	}
	if (!isNum(s)) {
		return s;
	}

	var browser = $('#hd_browser_safira').val();

	if(browser == '1'){
		return '\￥' + arg;
	}

	return '\\' + arg;
}

/**
 * 金額フォーマット 除去
 * @param s
 * @returns
 */
function removeMoneyFmt(s) {
	if (s == null || s == "") {
		return '';
	}
	var n = delcomma(s);
	var browser = $('#hd_browser_safira').val();
	if(browser == '1'){
		if (n.match(/^\￥-?[0-9]{1,}/)) {
			n = n.replace(/^\￥/, "");
		}
	} else {
		if (n.match(/^\\-?[0-9]{1,}/)) {
			n = n.replace(/^\\/, "");
		}
	}


	return n;
}
/**
 * 数字のみかどうか判定する
 * @param argVal
 * @returns {Boolean}
 */
function isNum(argVal) {
	var ret = false;
	var val = "" + argVal;
	if (!argVal && argVal != "0") {
		ret = false;
	} else if (isNaN(argVal)) {
		ret = false;
	} else if (val.match( /[^\-?0-9]/g)) {
		ret = false;
	} else if (val.indexOf("-") >= 0 && val.split("-").length > 2) {
		ret = false;
	} else {
		ret = true;
	}
	return ret;
}

/**
 * 行追加
 */
function setEventAddRow() {
	var addRowObj = $(".addRow");
	addRowObj.off('click');
	addRowObj.on('click', function(e) {
		e.preventDefault();
		if ($(this).hasClass('readonly')) {
			return;
		}
		var newTr = addTrToLast(this);
		newTr.find('.error').removeClass("error");// 背景色リセット
		_isChanged = true;
		return newTr;
	});
}

/**
 * テーブルの最終行に行をコピー追加する
 * @param elem jQueryオブジェクト
 */
function addTrToLast(elem) {
	var tableId = 'tanpinyuso';
	var ret 	= null;
	if (tableId) {
		ret = $("#" + tableId).find("li:last").clone(true);
		$("#" + tableId).append(ret);
		// 行初期化処理
		if(typeof addRowInitTr == "function") {
			addRowInitTr(ret);
		}
		// テーブル初期化処理
		if(typeof refreshTable == "function") {
			refreshTable($("#" + tableId));
		}
		setEventRemoveTr();
	}
	return ret;
}

/**
 * 行追加後の行初期化処理
 *  ※個別処理が必要な場合はオーバーライドしてください。
 * @param targetTr jQueryオブジェクト
 */
function addRowInitTr(targetTr) {
	targetTr.find("textarea, :text, select").val("");
	targetTr.find(":checked").prop("checked", false);
	targetTr.find(".tanka").text("");
	//targetTr.css("display","inline");
	targetTr.css("display","block");
	targetTr.removeClass("clone_tanhin");
}

/**
 * 行削除後のテーブル初期化処理
 *  ※個別処理が必要な場合はオーバーライドしてください。
 */
function refreshTable() {

	// オプション品目再表示
	optReShow('1');

    // お引取り予定日時
    showHikitori();
    // お引越し予定日時
    showHikoshi();

	// オプション料金を再計算
	calcOutOptRyokin();
	calcInOptRyokin();

	// 合計サイズ、合計金額計算(単品輸送品目)
	tanhinKinKeisan();

	// 見積金額を再計算
	mitsumoriKin();
}

/**
 * 行削除イベント設定
 */
function setEventRemoveTr() {
	var delRowObj = $(".delRow");
	delRowObj.off('click');
	delRowObj.on('click', function(e) {
		e.preventDefault();
		if ($(this).hasClass('readonly')) {
			return;
		}
		removeTr(this);
		_isChanged = true;
	});
}

/**
 * 行削除
 * @param elem jQueryオブジェクト
 */
function removeTr(elem) {
	var targetTable = $(elem).parents("ul:first");
	var targetTr = $(elem).parents("li:first");
	if (elem && !isOnlyTr($(elem))) {
		targetTr.remove();
	} else {
		addRowInitTr(targetTr);
	}
	if(typeof refreshTable == "function") {
		refreshTable();
	}

	_isChanged = true;
	if (targetTable.attr("delete") !== undefined && targetTr.attr("rownum") !== undefined) {
		var rownum = targetTr.attr("rownum");
		var delete_rows = targetTable.attr("delete");
		var hidden = $("#" + delete_rows);
		if (hidden != null) {
			if (hidden.val() != "") {
				var list = hidden.val().split(",");
				for (var i = 0; i < list.length; i++) {
					if (list[i] == rownum) {
						return;	// 0が削除済みの場合、追加しない
					}
				}
				hidden.val(hidden.val() + "," + rownum);	// 追加

			} else if (hidden.val() == "") {
				hidden.val(rownum);
			}
		}
	}
}

/**
 * テーブルに行が1行のみかどうか判定する（ヘッダ含めて2行かどうか）
 * @param tr jQueryオブジェクト
 * @returns {Boolean}
 */
function isOnlyTr(tr) {
	var ret = false;
	var targetTable = tr.parents("ul:first");
	if (targetTable.find("li").length == 2) {
		ret = true;
	}
	return ret;
}

/**
 * 行がテーブルの先頭行かどうか判定する
 * @param tr jQueryオブジェクト
 * @returns {Boolean}
 */
function isTopTr(tr) {
	var ret = false;
	if (tr.index() == 1) {
		ret = true;
	}
	return ret;
}

