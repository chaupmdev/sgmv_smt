$(function () {
    $('input').blur();
});

function detail(type, id = 0) {
    if (type == 'add') {
        window.location.href = '/csc/create';
    }
    if (type == 'edit') {
        window.location.href = '/csc/edit?id=' + id;
    }
}
function deleteShohin(id) {
    if (confirm("商品情報を削除してよろしいですか？")) {
        window.location.href = '/csc/delete_shohin?id=' + id;
    }
}

function clearRequestSearch() {
    $('input[name="id"]').val('');
    $('input[name="shohin_cd"]').val('');
    $('input[name="shohin_name"]').val('');
    $('input[name="size_from"]').val('');
    $('input[name="size_to"]').val('');
    //select
    $('select[name="option_id"]').val('');
    $('select[name="data_type"]').val('');

    $('input[name="juryo_from"]').val('');
    $('input[name="juryo_to"]').val('');
    
    //date 
    let date = new Date();
    let dayx = date.getDate();
    if (dayx < 10) {
        dayx = '0' + dayx;
    }
    let monthx = Number(date.getMonth()+1);
    if (monthx < 10) {
        monthx = '0' + monthx;
    }
    let yearx = date.getFullYear();
    let strDate = yearx + '-' + monthx + '-' + dayx;
    $('input[name="date_valid"]').val(strDate);

}

function exportCSV() {
    window.location.href = '/csc/mst_shohin_list?flag_export_csv=1';
}