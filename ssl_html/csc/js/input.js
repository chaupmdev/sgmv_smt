const startHoliday = new Date(2024, 6, 24);
const endHoliday = new Date(2024, 7, 6, 23, 59, 59);

$(function() {

    // 商品情報取得
    getShohinInfo = function() {
        var kanriNo = $('.c_kanri_no').val();
        if (kanriNo == "") {
            $('.l_shohin_name').text('');
            initShohinInfo(true);
            return;
        }

        if (kanriNo.length <= 4) {

            $('.l_shohin_name').text('5桁以上入力してください');
            initShohinInfo(true);
            return;
        }
        var form = $('form').first();
        var data = form.serializeArray();
        $('.l_shohin_name').text('商品情報取得中です');
        var result = $.ajax({
            async: true,
            cache: false,
            data: data,
            // dataType: 'json',
            timeout: 60000,
            type: 'post',
            url: '/csc/get_shohin_info.php'
        }).done(function(data, textStatus, jqXHR) {
            initShohinInfo(true);
            sessionStorage.setItem("resData", JSON.stringify(data.res_data));

            if (data.res_data.shohin.length == 0) {
                $('.l_shohin_name').text('商品情報を取得できませんでした');
            } else {
                $('.option_cd_kibo').text('');
                $('.shohin_area').show();
                $('.l_shohin_name').text(data.res_data.shohin.shohin_name);
                $('.l_is_kaidan').text(data.res_data.shohin.is_kaidan);
                $('input[name="l_is_kaidan"]').val(data.res_data.shohin.is_kaidan);
                $('.recycl_area').show();
                $('.c_recycl_cd').attr('checked', false);
                $('.shohin_info_kanri_no').text($('.c_kanri_no').val());
                $('.l_shohin_konposu').text(data.res_data.shohin.konposu);
                $('.konposu_area').show();

                var konpoFareTax=0;
                if(data.res_data.konpo.fare_tax!=undefined){
                    konpoFareTax=data.res_data.konpo.fare_tax;
                }
                $('.l_shohin_konposu_kingaku').text(String(konpoFareTax * (data.res_data.shohin.konposu-1)).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,'));

                if (data.res_data.shohin.is_kaidan == true) {
                    $('.kaidan_area').show();
                    $('.l_kaidan_type').hide();
                } else {
                    $('.kaidan_area').hide();
                    // サイズが 300cm 以下で階段作業がない場合
                    $('.c_kaidan_cd1').attr('checked', false);
                    $('.c_kaidan_cd2').attr('checked', false);
                    $('.l_kaidan_type1').attr('checked', false);
                    $('.l_kaidan_type2').attr('checked', false);
                }

                ///////////////////////////////////////////////////////////////////////////////
                // 配送料金設定
                ///////////////////////////////////////////////////////////////////////////////
                if (data.res_data.shohin.data_type == "6") { // DBデータ上 _kokyaku の方に金額が入っている為、 "6"(顧客負担エンドユーザ支払いあり)にする
                    // 6：顧客負担なし（エンドユーザ支払あり）（通常商品）l_haiso_kingaku_disp
                    // 7：顧客負担あり（エンドユーザ支払なし）（D24）l_haiso_kingaku_disp
                    $('input[name="l_haiso_kingaku_disp"]').val(data.res_data.deliv.fare_tax_kokyaku);
                } else {
                    $('input[name="l_haiso_kingaku_disp"]').val(data.res_data.deliv.fare_tax);
                }

                ///////////////////////////////////////////////////////////////////////////////
                // 階段料金設定
                ///////////////////////////////////////////////////////////////////////////////
                if (data.res_data.kaidanList.length != 0) {
                    $('.l_kaidan_kingaku_A_disp').val(data.res_data.kaidanList.A.fare_tax);
                    $('.l_kaidan_kingaku_B_disp').val(data.res_data.kaidanList.B.fare_tax);
                }

                var isEmptyObj = function(obj) {
                    if(obj==null) {
                        return false;
                    }
                    return !Object.keys(obj).length;
                };

                ///////////////////////////////////////////////////////////////////////////////
                // オプション・リサイクル設定
                ///////////////////////////////////////////////////////////////////////////////

                $('.option_area').show();
                $('.recycl_area').hide();
                if(isEmptyObj(data.res_data.option)) {
                    $('.option_area').hide();
                    $('.option_area_sel').hide();
                    $('.option_cd_kibo').hide();
                    // リサイクル希望しないにチェック
                    $('#c_recycl_cd2').prop('checked', true);
                    $('#c_option_cd_type').val(0);
                } else {
                    //GiapLN update task Cosco #SMT6-345 3.11.2022
                    //オプションが縦並びに対応する
                    let type = 2;
//                    if (data.res_data.option.length > 1) {
//                        let counts = {};
//                        data.res_data.option.forEach(function(item) {
//                            counts[item.yumusyou_kbn] = counts[item.yumusyou_kbn] ? counts[item.yumusyou_kbn] + 1 : 1;
//                        });
//                        let count0 = counts['0'] ? counts['0'] : 0;
//                        let count1 = counts['1'] ? counts[1] : 0;
//                        if (count0 > 1 || count1 > 1) {
//                            type = 2;
//                        }
//                    }
                    var optionDisp=data.res_data.optionDisp;
                    if (type == 2) {
                        $('.c_option_cd_type2').show();
                        $('.c_option_cd_type1').hide();
                        appendOptionCdType(data.res_data.option);
                        $('#c_option_cd_type').val(2);
                        $( ".c_option_cd2" ).on( "click", function() {
                            let optionId = $(this).val();
                            if (optionId == '0') {
                                $('.option_cd_kibo').text('オプションなし 選択中');
                            } else {
                                data.res_data.option.forEach(function(val) {
                                    if (val.id == optionId) {
                                        $('.option_cd_kibo').text(val.sagyo_naiyo + ' 選択中');
                                    }
                                });
                            }
                            $('.l_option_name').html(data.res_data.optionDisp.dispSagyoNm);
                        });  
                    } else {
                        $('#c_option_cd_type').val(1);
                        $('.c_option_cd_type2').hide();
                        $('.c_option_cd_type1').show();
                        
                         // オプションサービス項目のオプション無償ラジオボタンの表示
                        if (optionDisp.dispMusho == '1') {
                            $('.c_option_cd_sel3').show();
                        } else {
                            $('.c_option_cd_sel3').hide();
                        }

                         // オプションサービス項目のオプション有償ラジオボタンの表示
                        if (optionDisp.dispYusho == '1') {
                            $('.c_option_cd_sel1').show();
                        } else {
                            $('.c_option_cd_sel1').hide();
                        }
                    }
                    
                    
                    $('.option_area_sel').show();
                    //$('.option_area_sel').hide();
                    $('.option_cd_kibo').show();

                    $('.l_is_option').val('true');
                    // $('.option_kingaku').text('【料金】￥' + String(data.res_data.option.fare_tax).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,'));
                    //$('input[name="l_opt_rcy_kingaku"]').val(data.res_data.option.fare_tax);
                    //$('input[name="l_opt_rcy_kingaku_disp"]').val(0);

                    
                    if (optionDisp.dispSagyoNm) {
                        $('.l_option_name').html(optionDisp.dispSagyoNm);
                    }

                    

                    // リサイクル項目の表示
                    if (optionDisp.dispRecycle == '1') {
                        $('.recycl_area').show();
                    } else{
                        // リサイクル希望しないにチェック
                        $('#c_recycl_cd2').prop('checked', true);
                    }

                }
            }


        }).fail(function(jqXHR, textStatus, errorThrown) {
            $('.l_shohin_name').text('商品情報を取得できませんでした');
            initShohinInfo(true);
            console.log(error);
        });
    }
    
    function appendOptionCdType(options) {
        let text = "";
        let idx = 4;
        options.forEach(function(item, index) {
            text += '<label class="radio-label comiket_detail_type_sel-label1 c_option_cd_sel'+ idx +'" for="c_option_cd_sel'+ idx +'" style="height: 25px;"> ';
            text += '<input id="c_option_cd_sel'+ idx +'" class="c_option_cd2" name="c_option_cd" type="radio" value="'+ item.id +'">' + item.sagyo_naiyo;
            text += '</label>';
            text += '<br/>';
            idx++;
        });
        text += '<label class="radio-label comiket_detail_type_sel-label1 c_option_cd_sel'+ idx +'" for="c_option_cd_sel'+ idx +'" style="height: 25px;"> ';
        text += '<input id="c_option_cd_sel'+ idx +'" class="c_option_cd2" name="c_option_cd" type="radio" value="0">オプションなし：軒先でのお引渡しのため、設置・廃材回収なし';
        text += '</label>';
            
        //c_option_cd_type2
        $('.c_option_cd_type2').empty();
        $('.c_option_cd_type2').append(text);
    }
    
    function initShohinInfo(foceFlg) {
        if (!foceFlg) {
            foceFlg = false;
        }

        if ($('.c_kanri_no').val() == '' || $('.c_kanri_no').val().length <= 4 || foceFlg == true) {
            $('.konposu_area').hide();
            $('.shohin_area').hide();
            $('select[name="l_recycl_name"]').val('');
            $('.l_shohin_name').val("");
            $('.option_area').hide();
            // $('.l_option_name').hide();
            $('.kaidan_area').hide();
            $('.l_kaidan_type').hide();
            $('.recycl_area').hide();
            $('.l_recycl_name').hide();
            $('.l_is_option').val(false);
            $('.c_option_cd').attr('checked', false);
            $('.c_kaidan_cd').attr('checked', false);
            $('.l_kaidan_type').attr('checked', false);
            $('.l_is_kaidan').val(false);
            $('.c_recycl_cd').attr('checked', false);
            $('.kaidan_cd_kibo').text('');
            $('.kaidan_type_kibo').text('');
            $('.option_cd_kibo').text('');
            // getShohinInfo();
        }


    };

    var kanriNoChangeFlg = false;
    $('.c_kanri_no').on('blur', function() {
        if (kanriNoChangeFlg) {
            initShohinInfo(false);
            getShohinInfo();
            kanriNoChangeFlg = false;
        }
    });
    $('.c_kanri_no').on('change', function() {
        kanriNoChangeFlg = true;
    });
    //GiapLN fix bug 2022.11.8 
    $(".c_kanri_no").keydown(function (event) {
        if (event.keyCode == 13) {
            // Enter key pressed
            initShohinInfo(false);
            getShohinInfo();
            kanriNoChangeFlg = false;
            event.preventDefault();
            return false;
        }
    });



    //////////////////////////////////////////////////////////////////
    // オプション
    //////////////////////////////////////////////////////////////////
    $('.c_option_cd').on('click', function() {
//  alert($('.c_option_cd:checked').val());
        if ($(this).val() == '3') { // 無償オプション
            $('.l_option_name').show();
            $('.option_cd_kibo').text('無償オプション 選択中');
        } else if ($(this).val() == '1') { // 有償オプション
            $('.l_option_name').show();
            $('.option_cd_kibo').text('有償オプション 選択中');
        } else {
            $('.l_option_name').show();
            $('.option_cd_kibo').text('オプションなし 選択中');
        }
    });

    //////////////////////////////////////////////////////////////////
    // 階段
    //////////////////////////////////////////////////////////////////
    $('input[name="c_kaidan_cd"]').on('click', function() {
        if ($(this).val() == '1') { // 作業あり
            $('.l_kaidan_type').show(); // 外階段 or 内階段
            $('.kaidan_cd_kibo').text('作業あり');
            if ($('input[name="l_kaidan_type"]:checked').val() == 'A') {
                $('.kaidan_type_kibo').text('外階段あり');
            } else if($('input[name="l_kaidan_type"]:checked').val() == 'B') {
                $('.kaidan_type_kibo').text('内階段あり');
            } else {
                $('.kaidan_type_kibo').text('');
            }
        } else if ($(this).val() == '2') { // 作業なし
            $('.l_kaidan_type').hide(); // 外階段 or 内階段
            $('.kaidan_cd_kibo').text('作業なし');
            $('.kaidan_type_kibo').text('');
        } else {
            $('.l_kaidan_type').hide(); // 外階段 or 内階段
            $('.kaidan_cd_kibo').text('');
            $('.kaidan_type_kibo').text('');
        }
    });

    $('input[name="l_kaidan_type"]').on('click', function() {
        if ($('input[name="l_kaidan_type"]:checked').val() == 'A') {
            $('.kaidan_type_kibo').text('外階段あり');
        } else if($('input[name="l_kaidan_type"]:checked').val() == 'B') {
            $('.kaidan_type_kibo').text('内階段あり');
        } else {
            $('.kaidan_type_kibo').text('');
        }
    });

    //////////////////////////////////////////////////////////////////
    // リサイクル
    //////////////////////////////////////////////////////////////////
    $('.c_recycl_cd').on('click', function() {
        if ($(this).val() == '1') { // 希望する
            $('.l_recycl_name').show(); 
            $('.recycl_cd_kibo').text('希望する');
            $('.recycl_name_kibo').text($('l_recycl_name').val());
        } else if ($(this).val() == '2') { // 希望しない
            $('.l_recycl_name').hide(); 
            $('select[name="l_recycl_name"]').val(''); 
            $('.recycl_cd_kibo').text('希望しない');
            $('.recycl_name_kibo').text('');
        } else {
            $('.l_recycl_name').hide(); 
            $('.recycl_cd_kibo').text('希望しない');
            $('.recycl_name_kibo').text('');
            $('select[name="l_recycl_name"]').val('');
        }
    });

    $('select[name="l_recycl_name"]').on('change', function() {
        $('.recycl_name_kibo').text($(this).val());
    });

    //////////////////////////////////////////////////////////////////
    // 住所検索
    //////////////////////////////////////////////////////////////////
    $('input[name="inbound_adrs_search_btn"]').on('click', (function () {
        var $form = $('form').first();
        var result = $.ajax({
            async: true,
            cache: false,
            // dataType: 'json',
            timeout: 60000,
            type: 'get',
            //url: 'https://www.sagawa-mov.co.jp/common/php/SearchAddressForOuterSystem.php?f=CSC&g=CSC&t=CSC&nzip=' + $('.l_zip1').val() + $('.l_zip2').val()
            url: window.location.origin + '/common/php/SearchAddressForOuterSystem.php?f=CSC&g=CSC&t=CSC&nzip=' + $('.l_zip1').val() + $('.l_zip2').val()
        }).done(function(data, textStatus, jqXHR) {

            var prm=JSON.parse(data);
            $('.d_pref_id').val(prm.jpref);
            $('.d_address').val(prm.jcity+prm.jarea+prm.jstrt);
            $('.d_building').val('');

            checkAddress(); // 2022-12-19 FPT-AnNV6 update SMT6-383

            var data = $form.serializeArray();
            // 取得成功時、さらにリードタイムを取得する
            var result = $.ajax({
                async: true,
                cache: false,
                data: data,
                // dataType: 'json',
                timeout: 60000,
                type: 'post',
                url: '/csc/get_lead_time.php'
            }).done(function(data, textStatus, jqXHR) {
                prm=JSON.parse(data);

                // 配達希望日カレンダーの選択期間設定
                if(prm.res_data.plus_period >0 && prm.res_data.deli_period >0){
                    $("input").removeClass("hasDatepicker");
                    $('.d_from').val(prm.res_data.plus_period);
                    $('.d_to').val(prm.res_data.deli_period);
                    setHaitasuCalendar();
                }

            }).fail(function(jqXHR, textStatus, errorThrown) {
                $('.l_shohin_name').text('商品情報を取得できませんでした');
                initShohinInfo(true);
                //console.log(error);
            });


        });

    }));

    //////////////////////////////////////////////////////////////////
    // 申込者と同じ
    //////////////////////////////////////////////////////////////////
    $('.inbound_adrs_copy_btn').on('click', function() {
        var personalSei = $('.c_personal_name_sei').val();
        var personalMei = $('.c_personal_name_mei').val();
    
        if (personalSei != "" || personalMei != "") {
          $('.d_name').val(personalSei);
          if (personalSei != "" && personalMei != "") {
            $('.d_name').val($('.d_name').val() + "　");
          }
          $('.d_name').val($('.d_name').val() + personalMei);
        }
        //GiapLN imp ticket #SMT6-385 2022/12/27
        var cTel = $('input[name="c_tel"]').val();
        if (cTel != "") {
            $('input[name="staff_tel"]').val(cTel);
        }
    });

// 配達希望日カレンダーの選択できる期間を設定
setHaitasuCalendar();
    $( ".c_option_cd2" ).on( "click", function() {
        let resData = JSON.parse(sessionStorage.getItem("resData"));
        let optionId = $(this).val();
        if (optionId == '0') {
            $('.option_cd_kibo').text('オプションなし 選択中');
        } else {
            resData.option.forEach(function(val) {
                if (val.id == optionId) {
                    $('.option_cd_kibo').text(val.sagyo_naiyo + ' 選択中');
                }
            });
        }
        $('.l_option_name').html(resData.optionDisp.dispSagyoNm);
    });  

    // 2022-12-19 FPT-AnNV6 update SMT6-383
    if ($('#check_addr').val() == '1') {
        checkAddrOk();
    } else {
        checkAddrNg();
    }
    $('.l_zip1, .l_zip2, .d_pref_id, .d_address, .d_building').change(function () {
        checkAddress();
    });

// end function ready
});

// 2022-12-19 FPT-AnNV6 update SMT6-383
function checkAddress() {
    var l_zip1 = $('.l_zip1').val();
    var l_zip2 = $('.l_zip2').val();
    var d_pref_id = $('.d_pref_id').val();
    var d_address = $('.d_address').val();
    var d_building = $('.d_building').val();
    var c_event_id = $('.c_event_id').val();
    var c_eventsub_id = $('.c_eventsub_id').val();
    if (l_zip1 != '' && l_zip2 != '' && d_pref_id != '' && d_address != '') {
        var result = $.ajax({
            async: true,
            cache: false,
            data: {
                l_zip1: l_zip1,
                l_zip2: l_zip2,
                d_pref_id: d_pref_id,
                d_address: d_address,
                d_building: d_building,
                c_event_id: c_event_id,
                c_eventsub_id: c_eventsub_id
            },
            type: 'post',
            url: '/csc/check_address.php'
        }).done(function(data, textStatus, jqXHR) {
            var res = JSON.parse(data);
            console.log('data', res.status);
            if (res.status == '404') {
                $('#check_addr').val('0');
                checkAddrNg();
            } else {
                $('#check_addr').val('1');
                checkAddrOk();
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.log(error);
        });
    }
}

function checkAddrOk() {
    $('.d_delivery_date_fmt').prop('disabled', false);
    $('.disp_d_from').show('');
    $('.comiket-detail-inbound-delivery-date-fr-to').show('');
    $('.disp_date_history').show('');
    $('.no_addr_txt').hide('');
}

function checkAddrNg() {
    $('.d_delivery_date_fmt').val('');
    $('.d_delivery_date_fmt').prop('disabled', true);
    $('.disp_d_from').hide('');
    $('.comiket-detail-inbound-delivery-date-fr-to').hide('');
    $('.disp_date_history').hide('');
    $('.no_addr_txt').show('');
}

function checkArrangeHoliday() {
    let today =  new Date();
    if (today < startHoliday || today > endHoliday) {
        return false;
    }
    return true;
}
//////////////////////////////////////////////////////////////////
// 配達希望日カレンダーの選択できる期間を設定
//////////////////////////////////////////////////////////////////
function setHaitasuCalendar(){

    const HAISO_KANO_DATE_MIN = Number($('.d_from').val());
    const HAISO_KANO_DATE_MAX   = HAISO_KANO_DATE_MIN+Number($('.d_to').val());
    
    //GiapLN implement task #SMT6-348 2022.11.16
    let businessHoliday = $('#business_holiday').val();
    businessHoliday = JSON.parse(businessHoliday);
    
    // 配達希望日 の最小日付
    let lowerLimit = new Date();
    let limitDay = lowerLimit.getDate();
    lowerLimit.setDate(limitDay + HAISO_KANO_DATE_MIN);

    // 配達希望日 の最大日付
    let upperLimit = new Date();
    let upperDay = upperLimit.getDate();
    upperLimit.setDate(upperDay + HAISO_KANO_DATE_MAX);
    let strHolidayMsg = getMessageHoliday(businessHoliday);
    console.log('getMessageHoliday result');
    console.log(strHolidayMsg);
    let arrDateDisplay = calculatorDateDisplay(lowerLimit, upperLimit, businessHoliday);
    //arr_date_dis
    $('#arr_date_dis').val(JSON.stringify(arrDateDisplay));
    let len = arrDateDisplay.length;
    let arrDateResult = dateDisplayCalendar(arrDateDisplay);
    //let arrDateHoliday = calculatorDateHoliday(lowerLimit, upperLimit, arrDateResult);
    //console.log(arrDateResult);
    // 曜日フォーマット
    let weekName=['日','月','火','水','木','金','土'];
    
    if (len > 0) {
        // 日数
        let dateMin = lowerLimit;//arrDateDisplay[0][0];
        let dateMax = upperLimit;//arrDateDisplay[len - 1][1];
        let now = new Date();
        let diff = datediff(now, dateMin);
        //GiapLN add display holiday 2024/07/24 ～ 2024/08/06 - 2024/07/18
        if (!checkArrangeHoliday()) {
            $('.disp_date_history').parent().hide();
            $('.disp_d_from').text('※ 申込日より' + diff + '日目以降より選択できます。');
//            $('.disp_d_from').text('※ 2024年12月29日（日）～2025年01月10日（金）は配送指定日として選択できません。');
            
        }
        
       
        // 最小日付 表示
        $('.disp_d_from_day').text(dateMin.getFullYear()+'年'
                                    +Number(dateMin.getMonth()+1)+'月'
                                    +dateMin.getDate()+'日'
                                    +'（'+weekName[dateMin.getDay()]+'）から '
                                    );
        // 最大日付 表示
        $('.disp_d_to_day').text(dateMax.getFullYear()+'年'
                                    +Number(dateMax.getMonth()+1)+'月'
                                    +dateMax.getDate()+'日'
                                    +'（'+weekName[dateMax.getDay()]+'）まで選択できます。'
                                    );
        //date Holiday 
//        console.log();
//        if (arrDateHoliday.length > 0) {
//            let strHolidateDate = '';
//            for (let x = 0; x < arrDateHoliday.length - 1; x = x + 2) {
//                if (arrDateHoliday[x].getTime() !== arrDateHoliday[x + 1].getTime()) {
//                    //format: 2022年11月26日～2022年11月28日、
//                    let monthx = Number(arrDateHoliday[x].getMonth()+1);
//                    if (monthx < 10) {
//                        monthx = '0' + monthx;
//                    }
//                    let dayx = arrDateHoliday[x].getDate();
//                    if (dayx < 10) {
//                        dayx = '0' + dayx;
//                    }
//                    let monthx1 = Number(arrDateHoliday[x + 1].getMonth()+1);
//                    if (monthx1 < 10) {
//                        monthx1 = '0' + monthx1;
//                    }
//                    let dayx1 = arrDateHoliday[x + 1].getDate();
//                    if (dayx1 < 10) {
//                        dayx1 = '0' + dayx1;
//                    }
//                    strHolidateDate += arrDateHoliday[x].getFullYear() +'年' + monthx +'月' + dayx +'日';
//                    strHolidateDate += '～';
//                    strHolidateDate += arrDateHoliday[x + 1].getFullYear() +'年' + monthx1 +'月' + dayx1 +'日';
//                    strHolidateDate += '、';
//                } else {
//                    //format: 2022年11月26日、
//                    let monthx = Number(arrDateHoliday[x].getMonth()+1);
//                    if (monthx < 10) {
//                        monthx = '0' + monthx;
//                    }
//                    let dayx = arrDateHoliday[x].getDate();
//                    if (dayx < 10) {
//                        dayx = '0' + dayx;
//                    }
//                    strHolidateDate += arrDateHoliday[x].getFullYear() +'年' + monthx +'月' + dayx +'日';
//                    strHolidateDate += '、';
//                }
//            }
//            //remove 、
//            strHolidateDate = strHolidateDate.substr(0, strHolidateDate.length - 1);
//            //addition の間は選択できません
//            console.log("##########################");
//            console.log(strHolidateDate);
//            if (!strHolidateDate.includes('～')) {
//                strHolidateDate += 'は選択できません。';
//            } else {
//                strHolidateDate += 'の間は選択できません。';
//            }
//            console.log(strHolidateDate);
//            $('.disp_date_history').text(strHolidateDate);
//        }
        if (strHolidayMsg) {
            console.log("DISPLAY HOLIDAY MESSAGE");
            console.log(strHolidayMsg);
            //GiapLN add display holiday 2024/07/24 ～ 2024/08/06 - 2024/07/18
            if (checkArrangeHoliday()) {
                $('.d_delivery_date_err_apply').css("padding-top", "0px");
                $('.disp_date_history').html(strHolidayMsg);
            }
            //2024/04/25 「～選択できません。」の文言が出る時は,選択期間のメッセージを非表示
            $('.disp_d_from_day').html('');
            $('.disp_d_to_day').html('');
        } else {
            console.log("NODISPLAY HOLIDAY MESSAGE");
            console.log(strHolidayMsg);
            $('.disp_date_history').html();
        }
                        
    }
    // datepickerに選択日数をセット
    // 配達希望日 の最小日付
    if (arrDateResult.length > 0) {
        let ranges = [];
        for(let i = 0; i < arrDateResult.length; i = i + 2) {
            ranges.push({start: new Date(arrDateResult[i].getFullYear(), arrDateResult[i].getMonth(), arrDateResult[i].getDate()), end: new Date(arrDateResult[i + 1].getFullYear(), arrDateResult[i + 1].getMonth(), arrDateResult[i + 1].getDate())});
        }
        //console.log(ranges);
        $('.d_delivery_date_fmt').datepicker({
            beforeShowDay: function(date) {
                for(let i=0; i<ranges.length; i++) {
                  if(date >= ranges[i].start && date <= ranges[i].end) return [true, ''];
                }
                return [false, ''];
            },
            minDate: ranges[0].start,
            maxDate: ranges[ranges.length -1].end
        });
    } else {
        $('.d_delivery_date_fmt').datepicker({
            minDate: new Date(),
            maxDate: (new Date).subDays(1)
        });
    }
}

function datediff(first, second) {        
    return Math.round((second - first) / (1000 * 60 * 60 * 24));
}

function parseDate(str) {
    return new Date(str);
}
Date.prototype.addDays = function(days) {
    var date = new Date(this.valueOf());
    date.setDate(date.getDate() + days);
    return date;
}
Date.prototype.subDays = function(days) {
    var date = new Date(this.valueOf());
    date.setDate(date.getDate() - days);
    return date;
}

function calculatorDateDisplay(lowerLimit, upperLimit, businessHoliday) {
    let arrDateResult = [];
    arrDateResult.push([lowerLimit, upperLimit]);
    
    for (let i = 0; i < businessHoliday.length; i++) {
        let fromHoliday = parseDate(businessHoliday[i].holiday_from);
        let toHoliday = parseDate(businessHoliday[i].holiday_to);
        fromHoliday = removeTime(fromHoliday);
        toHoliday = removeTime(toHoliday);
        for (let j = 0; j < arrDateResult.length; j++) {
            let from = arrDateResult[j][0];
            let to = arrDateResult[j][1];
            from = removeTime(from);
            to = removeTime(to);

            //case 1: toHoliday < From hoac fromHoliday > To  
            if (toHoliday < from || fromHoliday > to) {
                continue;
            } else if (fromHoliday <= from && toHoliday >= from && toHoliday < to) {
                //case 2: fromHoliday < From va toHoliday >= From va toHoliday <To 
                arrDateResult[j][0] = toHoliday.addDays(1);
            } else if (fromHoliday <= from && toHoliday >= to) {
                //case 3:  fromHoliday <= From va toHoliday >= To 
                arrDateResult.splice(j, 1);
                continue;
            } else if (fromHoliday > from && toHoliday < to) {
                //case 4: fromHoliday > From va toHoliday < To 
                arrDateResult[j][1] = fromHoliday.subDays(1);
                arrDateResult.push([toHoliday.addDays(1), to]);
            } else if(fromHoliday > from && fromHoliday <= to && toHoliday >= to) {
                //case 5: fromHoliday > From va fromHoliday < To va toHoliday > To 
                arrDateResult[j][1] = fromHoliday.subDays(1);
            }
        }
    }
    console.log(arrDateResult);
    return arrDateResult;
}

function dateDisplayCalendar(arrDateDisplay) {
    let arrResult = [];
    arrDateDisplay.forEach(function(item) {
        arrResult.push(removeTime(item[0]));
        arrResult.push(removeTime(item[1]));
    });
    //arrResult.sort();
    arrResult.sort((date1, date2) => date1 - date2);
    return arrResult;
}
function removeTime(date) {
    return new Date(date.getFullYear(),date.getMonth(),date.getDate());
}

function calculatorDateHoliday(lowerLimit, upperLimit, arrDateResult) {
    let resultDate = [];
    let n = arrDateResult.length; 
    if (n > 0) {
        lowerLimit = removeTime(lowerLimit);
        upperLimit = removeTime(upperLimit);
        if (lowerLimit.getTime() !== arrDateResult[0].getTime()) {
            resultDate.push(lowerLimit);
            resultDate.push(arrDateResult[0].subDays(1));
        }
        if (upperLimit.getTime() !== arrDateResult[n - 1].getTime()) {
            resultDate.push(arrDateResult[n - 1].addDays(1));
            resultDate.push(upperLimit);
        }
        for (let i = 1; i < n - 2; i = i + 2) {
            if (arrDateResult[i].getTime() !== arrDateResult[i + 1].getTime()) {
                let nextTo = arrDateResult[i].addDays(1);
                let previous = arrDateResult[i + 1].subDays(1);
                if (nextTo <= previous) {
                    resultDate.push(nextTo);
                    resultDate.push(previous);
                }
            }
        }
        if (resultDate.length > 0) {
            resultDate.sort((date1, date2) => date1 - date2);
        }
    }
//    console.log('Date holiday');
//    console.log(resultDate);
    return resultDate;
}

function getMessageHoliday(arrDateHoliday) {
    let result = "";
    arrDateHoliday.forEach(function(item) {
        if (item.holiday_from === item.holiday_to) {
            dateFrom = parseDate(item.holiday_from);
            result += formatDate(dateFrom) + 'は選択できません。<br/>';
        } else {
            dateFrom = parseDate(item.holiday_from);
            dateTo = parseDate(item.holiday_to);
            result += "※ " + formatDate(dateFrom) + '～' + formatDate(dateTo) + 'は配送指定日として選択できません。<br/>';//'の間は選択できません。<br/>';
        }
    });
    console.log('getMessageHoliday');
    console.log(result);
    return result;
}

const weekday = ["日","月","火","水","木","金","土"];
function formatDate(date) {
    let dayx = date.getDate();
    if (dayx < 10) {
        dayx = '0' + dayx;
    }
    let monthx = Number(date.getMonth()+1);
    if (monthx < 10) {
        monthx = '0' + monthx;
    }
    let day = date.getDay();
    return date.getFullYear() +'年' + monthx +'月' + dayx +'日' + '（' + weekday[day] + '）';
}