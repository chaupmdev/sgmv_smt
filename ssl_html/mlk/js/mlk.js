$(function () {
    if (selectTypeVal !== '') {
        loadShowHideItem(selectTypeVal, selectedVal);
    }
    selectType.on('change', function() {
        arrayHotel = [];
        loadShowHideItem($(this).val());
        showAddressTelBySelectType($(this).val(), '');
    });
    

    selectAirport.on('change', function() {
        showAddressTelBySelectType(ADDRESS_TYPE_SELECT_AIRPORT, $(this).val());
    });
    selectCenter.on('change', function() {
        showAddressTelBySelectType(ADDRESS_TYPE_SELECT_SERVICE, $(this).val());
    });
    selectHotel.on('change', function() {
        showAddressTelBySelectType(ADDRESS_TYPE_SELECT_HOTEL, $(this).val());
    });

    btnHotelSearch.on('click', function () {
        const hotelName = hotelNm.val();
        var x = document.getElementById("hotel_msg");
        if (x.style.display === "none") {
            x.style.display = "block";
        }

        if (hotelName) {
            arrayHotel = [];
            let index = 0;
            for (let item of dataHachakutenAll) {
                const type = isNaN(item.type) ? 0 : parseInt(item.type);
                if (type === ADDRESS_TYPE_SELECT_HOTEL) {
                    let position = item.nameUpperCase.indexOf(hotelName.toUpperCase());
                    if (position !== -1) { 
                        arrayHotel.push(item);
                    }
                }
            }
            console.log(arrayHotel);
            
            let strOption = '';
            if (arrayHotel.length > 0) {
                if (arrayHotel.length === 1) {
                    strOption = `<option value="${arrayHotel[0]['id']}" data-stt-ignore>${arrayHotel[0]['name']}</option>`;
                    selectHotel.empty().append(strOption);
                    selectHotel.val(arrayHotel[0]['id']);
                    showAddressTelBySelectType(ADDRESS_TYPE_SELECT_HOTEL, arrayHotel[0]['id']);
                } else {
                    for (let item of arrayHotel) {
                        strOption += `<option value="${item['id']}" data-stt-ignore>${item['name']}</option>`;
                    }
                    selectHotel.empty().append(`<option value="0">${EMPTY_SELECT_HOTEL}</option>` + strOption);
                    selectHotel.value = '0';
                    $('.addressee-address-nm').html('');
                    $('.addressee-tel').html('');
                }
            } else {
                selectHotel.empty().append(`<option value="0">${EMPTY_SELECT_HOTEL}</option>`);
                selectHotel.value = '0';
                $('.addressee-address-nm').html('');
                $('.addressee-tel').html('');
            }
        } else {
            loadDataSelectFromAddressType(ADDRESS_TYPE_SELECT_HOTEL, '0');
        }
    });

    function loadShowHideItem(type, selectedVal) {
        let typeSelect = type === '' ? 0 : parseInt(type);
        
        switch(typeSelect) {
            case ADDRESS_TYPE_SELECT_NONE: //選択してください
                selectAirport.hide();
                selectCenter.hide();//佐川急便手ぶら観光センターを非表示化
                deliverySearch.hide();
                deliveryParent.hide();
                noteParent.hide();
                clearDateDateTimeForSelectAirport();
                deliverySearch.find('input[name="hotel_nm"]').val('');
                break;
            case ADDRESS_TYPE_SELECT_AIRPORT: //空港
                selectAirport.show();
                loadDataSelectFromAddressType(ADDRESS_TYPE_SELECT_AIRPORT, selectedVal);
                selectCenter.hide();//佐川急便手ぶら観光センターを非表示化
                deliverySearch.hide();
                deliveryParent.show();
                noteParent.show();
                break;
            case ADDRESS_TYPE_SELECT_SERVICE: //佐川急便手ぶら観光センター
                selectCenter.show();//佐川急便手ぶら観光センターのリストを表示する
                loadDataSelectFromAddressType(ADDRESS_TYPE_SELECT_SERVICE, selectedVal);
                selectAirport.hide();
                deliverySearch.hide();
                deliveryParent.hide();
                noteParent.hide();
                break; 
            case ADDRESS_TYPE_SELECT_HOTEL: //ホテル
                selectAirport.hide();      
                selectCenter.hide();//佐川急便手ぶら観光センターを非表示化

                deliverySearch.show();
                loadDataSelectFromAddressType(ADDRESS_TYPE_SELECT_HOTEL, selectedVal);
                deliveryParent.hide();
                noteParent.hide();
                break; 
            default: 
                break;
        }
        
        var x = document.getElementById("hotel_msg");
        if (x.style.display === "block") {
            x.style.display = "none";
        }
    }
    
    function loadDataSelectFromAddressType(selectType, selectedVal) {
        let strOption = '';
        let translateIg = 'data-stt-ignore';
        for (const item of dataHachakutenAll) {
            const type = isNaN(item.type) ? 0 : parseInt(item.type);
            if (type === selectType) {
                //First Item is translate
                translateIg = (item.id == '0')? "" : "data-stt-ignore";

                if (item.id == selectedVal) {
                    strOption += `<option value="${item.id}" selected="selected" ${translateIg}>${item.name}</option>`;
                } else {
                    strOption += `<option value="${item.id}" ${translateIg}>${item.name}</option>`;
                }
            }
            if (type === ADDRESS_TYPE_SELECT_HOTEL) {
                arrayHotel.push(item);
            }
        }

        if (selectType === ADDRESS_TYPE_SELECT_AIRPORT) {
            selectAirport.empty().append(`<option value="0">${EMPTY_SELECT_AIRPORT}</option>` + strOption);
            selectAirport.value = '0';
            deliverySearch.find('input[name="hotel_nm"]').val('');
        } else if (selectType === ADDRESS_TYPE_SELECT_SERVICE) {
            selectCenter.empty().append(`<option value="0">${EMPTY_SELECT_SERVICE}</option>` + strOption);
            selectCenter.value = '0';
            clearDateDateTimeForSelectAirport();
            deliverySearch.find('input[name="hotel_nm"]').val('');
        } else if (selectType === ADDRESS_TYPE_SELECT_HOTEL) {
            selectHotel.empty().append(`<option value="0">${EMPTY_SELECT_HOTEL}</option>` + strOption);
            selectHotel.value = '0';
            clearDateDateTimeForSelectAirport();
        }
        showAddressTelBySelectType(selectType, selectedVal);

    }
    function clearDateDateTimeForSelectAirport() {
        noteParent.find('input').val('');
        deliveryParent.find('select[name="comiket_detail_delivery_date"]').val('');
        deliveryParent.find('select[name="comiket_detail_delivery_date_hour"]').val('');
        deliveryParent.find('select[name="comiket_detail_delivery_date_min"]').val('');
    }

    function showAddressTelBySelectType(selectType, selectedVal) {
        let address = '';
        let tel = '';
        for (const item of dataHachakutenAll) {
            const type = isNaN(item.type) ? 0 : parseInt(item.type);
            if (type === selectType) {
                if (item.id == selectedVal) {
                    address = item.address;
                    tel = item.tel;
                } 
            }
        }
        $('.addressee-address-nm').html(address);
        $('.addressee-tel').html(tel);
    }
});