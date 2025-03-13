

$(document).ready(function(){
    changePageButton();
});

function changePageButton() {
    var checked = $('#checkbox_agreement').prop("checked");
    if (checked) {
        $("#submit_button").attr("disabled", false);
        document.getElementById("submit_button").style.opacity = "1";
    } else {
        $("#submit_button").attr("disabled", true);
        document.getElementById("submit_button").style.opacity = "0.2";
    }
}

$('#checkbox_agreement').on('click', function() {
    changePageButton();
});