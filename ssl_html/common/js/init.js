$(function(){
	
	$('.slider').mobilyslider({
		content: '.sliderContent',
		children: 'div',
		transition: 'fade',// transition: horizontal, vertical, fade
		animationSpeed: 1500,
		autoplay: true,
		autoplaySpeed: 4000,
		pauseOnHover: false,
		bullets: true,
		arrows: false,
		arrowsHide: false,
		prev: 'prev',
		next: 'next',
		animationStart: function(){},
		animationComplete: function(){}
	});
	
});
