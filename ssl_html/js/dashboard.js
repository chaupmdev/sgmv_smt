function open_dlg(enquiry)
{
    let x = (($(window).width() - $("#sample-dialog").outerWidth(true)) / 2);
    let y = $(window).scrollTop(400);
    $('#sample-dialog').css({top:(y),left:(x),display:'block'}).attr();
    //ダイアログを表示する
    $("#sample-dialog").show();
}
//閉じるボタンで非表示
function close_dialog()
{
    $('.dialog-close').parents(".dialog").hide();
}

// jQueryでselect要素の特定のoptionを隠したい！
$(function(){
    $("#event").change(function(){
        let event_sel=$(this).val();
        $.ajax({
            type: "POST",
            url: "./sgmv_dashboard.php",
            data: {
                    'action': 'get_eventsub',
                    'sub_list': event_sel
                },
            dataType : "json",
            }).done(function(data){
                $('select#eventsub option').remove();
                $("#eventsub").append(data);
            }).fail(function(XMLHttpRequest, textStatus, error){
                alert(error);
        });
    });
});
//一つ上の階層のURL（rep)
let url = location.href;
let ary = url.split('/');
let str = ary[ary.length - 1];
let rep = url.replace(str, '');
/**
     チェックデジット風暗号化処理
    Risultの桁数は可変 1桁～
*/
function get_checkdg(num,sel)
{
    let ur="";
    switch(sel)
    {
        case 1:
            ur="size_change/";
            break;
        case 2:
            ur="cancel/";
            break;
        case 3:
            ur="paste_tag/";
            break;
        case 4:
            ur="input/";
            break;
    }
    $.ajax({
            type: "POST",
            url: "./sgmv_dashboard.php",
            data: {
                    'action': 'get_checkdigit',
                    'eventid': num
                },
            dataType : "json",
            }).done(function(data){
                window.location.href = rep + ur + num + data;
            }).fail(function(XMLHttpRequest, textStatus, error){
                alert(error);
        });
}

function change_num(num)
{
    //チェックデジットA
    get_checkdg(num,1);
}
function cancel_order(num)
{
    //チェックデジットA
    get_checkdg(num,2);
}
function download_tag(num)
{
    // get_checkdg(num,3);

    //チェックデジットB
    ur="paste_tag/";
    let sp = num % 7;
    window.location.href = rep + ur + num + sp;
}
function re_order(num)
{
    //チェックデジットA
    get_checkdg(num,4);
}
function form_clear()
{
    document.search_form.reset();
}
function form_submit()
{
    let target = document.getElementById("search_form");
    target.method = "post";
    $('<input>').attr({
        'type': 'hidden',
        'name': 'submit_push',
        'value': true
    }).appendTo(target);
    target.submit();
}
