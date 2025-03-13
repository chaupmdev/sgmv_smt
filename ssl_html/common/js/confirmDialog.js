var modal = document.getElementById('myModal');

window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
$(".btn-cancel-modal,.btn-close").click(function() {
    console.log('closed');
    $('#myModal').css("display", "none");
});
