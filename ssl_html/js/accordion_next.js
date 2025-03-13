// [BACKUP]: 干渉によるバグ発生のため、別クラス名に変更/採用する 2016-03-05
$(document).ready(function()
{
	$(".accordion_button_next").click(function(e)
	{
		$(this).next().slideToggle();
		$(this).toggleClass("active");
		e.preventDefault();
		return false;
	});
});