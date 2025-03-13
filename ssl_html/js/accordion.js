//<![CDATA[
$(document).ready(function(){
	
	$(".accordion_button").click(function(e){
			$(this).next().slideToggle();
			$(this).toggleClass("active");
			e.preventDefault();
			return false;
		});
});
//]]>