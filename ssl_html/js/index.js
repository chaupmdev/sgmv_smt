function mv_event_open(){
	document.getElementById('mv_event').classList.add('active');
}
function mv_event_close(){
	document.getElementById('mv_event').classList.remove('active');
}

$(function() {
	$('#main .pickup .swiper .swiper-slide').matchHeight();
	$('#main .service .tabBox .borderBox .textList li a .txtBox .ttl').matchHeight();
	$('#main .service .tabBox .borderBox .textList li a .txtBox .ttl + p').matchHeight();
	$('#main .service .tabBox .borderBox .textList li a').matchHeight({byRow: false});

	$('#gHeader,#main .mainVisual .text,#main .mainVisual .news,#main .mainVisual .scroll,.menu,#main .mainVisual .event').hide();

	//first view
	$(window).resize(function() {
		$('.mainVisual,#container > .bg').height(window.innerHeight);

		$('#main .about .imgBox .photoBox .photoList span').width($('#main .about .imgBox .photoBox').width());
	}).trigger('resize');

	//about slide
	function nmAnimation() {
		var animeID = 0;

		var zindex = 0;

		function mediaSlideMove() {
			zindex++;
			var $elm = $('#main .about .imgBox .photoBox .photoList li').eq(animeID);
			$elm.show().css({ 'z-index': zindex });
			gsap.fromTo($elm, 1.2, { width: '0%' }, {
				width: '100%',
				ease: "power4.inOut"
			});
		}

		function mediaSlideSet() {
			if (animeID >= 2) {
				animeID = 0;
			} else {
				animeID++;
			}
			mediaSlideMove();
		}
		reasonInterval = setInterval(mediaSlideSet, 3500);
	}

	$(window).scroll(function(){
		var windowHeight = $(window).height(),
		topWindow = $(window).scrollTop();
		$('#main .about .imgBox .photoBox').each(function(){
			var targetPosition = $(this).offset().top;
			if(topWindow > targetPosition - windowHeight + 100){
				if(!$(this).hasClass('animate')){
					$(this).addClass('animate');
					setTimeout(function(){
						nmAnimation();
					},800);
				}
			}
		});
	}).trigger('scroll');

  // Swiperの設定
//   const eventSwiper = new Swiper(".mv_event_contents", {
//     loop: true,
// 	pagination: {
// 		el: ".swiper-pagination", // ページネーションのクラス名を指定
// 		type: "fraction", // ページネーションのtypeを指定
// 	  },
// 	navigation: {
//       nextEl: ".swiper-button-next",
//       prevEl: ".swiper-button-prev",
//     },
//     spaceBetween: 30,
//   });

//   // モーダルを表示するボタンをクリックしたとき
//   document.getElementById('mv_event_start').addEventListener("click", () => {
// 		eventSwiper.slideTo(1);
//     });


	//pickup slide
	var swiper = new Swiper(".pickup:not(.solution) .swiper", {
		slidesPerView: 'auto',
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
		scrollbar: {
			el: ".swiper-scrollbar",
			hide: false,
		},
	});

	//solution slide
	var swiper2 = new Swiper(".solution .swiper", {
		slidesPerView: 'auto',
		loop: true,
		autoplay: {
		   delay: 3000,
		   disableOnInteraction: false
		},
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
		/*scrollbar: {
			el: ".swiper-scrollbar",
			hide: false,
		},
		on: {
			slideChange: function(e){
				if(e.isEnd){
					$(e.$el).addClass('last');
				}else{
					$(e.$el).removeClass('last');
				}
			}
		}*/
	});

	//tabchange
	$('.tabPanel .tabBox').hide();
	$('.tabPanel .tabBox').eq(0).show();

	$('#main .service .tabList li a').click(function() {
		var ind = $(this).parent('li').index();
		$(this).parent('li').addClass('on').siblings().removeClass('on');
		$('.tabPanel .tabBox').hide();
		$('.tabPanel .tabBox:eq(' + ind + ')').fadeIn();
		return false;
	});
/*});

$(window).on('load', function() {
*/	//filter
	var grid = new Muuri('.grid', {
		showDuration: 600,
		showEasing: 'cubic-bezier(0.215, 0.61, 0.355, 1)',
		hideDuration: 600,
		hideEasing: 'cubic-bezier(0.215, 0.61, 0.355, 1)',
		visibleStyles: {
			opacity: '1',
			transform: 'scale(1)'
		},
		hiddenStyles: {
			opacity: '0',
			transform: 'scale(0.5)'
		}
	});

	var moreItems;

	function filterItem(){
		moreItems = grid.getItems().filter(function(item) {
			return item.isActive();
		});
		if (moreItems.length > 4) {
			moreItems.splice(0, 4);
			grid.hide(moreItems, { instant: true });
			$('#main .service .tabBox .btn').show();
		}
	}
	filterItem();
	grid.on('filter', function (shownItems, hiddenItems) {
		moreItems = shownItems;
		if(shownItems.length > 4){
			shownItems.splice(0, 4);
			//console.log(shownItems);
			grid.hide(shownItems, { instant: true });
			$('#main .service .tabBox .btn').show();
		}
	});
	$('#main .service .tabBox .tDl dd .linkList a').on('click', function() {
		$("#main .service .tabBox .tDl dd .linkList .on").removeClass("on");
		var className = $(this).attr("class");
		className = className.split(' ');
		$("#main .service .tabBox .tDl dd .linkList ." + className[0]).parent().addClass("on");
		$('#main .service .tabBox .btn').hide();
		if (className[0] == "all") {
			grid.filter(function(item){
				return 1;
			},{
				onFinish: function(){
					//console.log('1');
					//filterItem();
				}
			});
		} else {
			grid.filter("." + className[0],{
				onFinish: function(){
					//console.log('2');
					//filterItem();
				}
			});
		}

		return false;
	});

	$('#main .service .tabBox .btn a').click(function() {
		grid.show(moreItems, { instant: true });
		$(this).parent().hide();
		return false;
	});
	$(window).on('load', function() {
		grid.refreshItems();
	});
});