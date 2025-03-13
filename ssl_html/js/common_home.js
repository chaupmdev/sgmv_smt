var startTime=(new Date()).getTime();
$(function(){
	startTime = (new Date()).getTime();
	$('a[href*=\\#]:not([href=\\#])').click(function() {
	if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
			var $target = $(this.hash);
			$target = $target.length && $target || $('[name=' + this.hash.slice(1) +']');
			if ($target.length) {
				if($(this).parents('.menuBox').length){
					setTimeout(function(){
						var targetOffset = $target.offset().top;
						$('html,body').animate({scrollTop: targetOffset}, 1000);
					},100);
					$('.menu.open .txt').click();
					$('#gHeader').addClass('hide');
				}else{
					var targetOffset = $target.offset().top;
					$('html,body').animate({scrollTop: targetOffset}, 1000);
				}
				return false;
			}
		}
	});

	//loading
	function loadAni(){
		// $('.loading .txtBox p').contents().each(function(i) {
		// 	if (this.nodeType == 3) {
		// 		$(this).replaceWith($(this).text().replace(/(\S)/g, '<span class="str">$1</span>'));
		// 	}
		// });
		$('.loading .txtBox p').addClass('on');
		// $('.loading .txtBox p span').each(function(i) {
		// 	$(this).delay(40*i).animate({'opacity':1},400);
		// });
		setTimeout(function(){
			$('.loading .inner .txtBox .pho').addClass('on');
			$('.loading .inner .txtBox .line').addClass('on');
		},500);
	}
	loadAni();

	function paceInit() {
		var initDestroyTimeOutPace = function() {
			var counter = 0;
			var refreshIntervalId = setInterval(function() {
				var progress;
				if (typeof $('.pace-progress').attr('data-progress-text') !== 'undefined') {
					progress = Number($('.pace-progress').attr('data-progress-text').replace('%', ''));
					$('.loading .line span').css('width', progress + "%");
				}
				if (progress === 99) {
					counter++;
				}
				if (counter > 50) {
					clearInterval(refreshIntervalId);
					Pace.stop();
				}
			}, 100);
		};
		initDestroyTimeOutPace();
	}
	//paceInit();

	function loadOut(){
		if($('body').attr('id')=="index"){
			
			$('#gHeader,#main .mainVisual .text,#main .mainVisual .news,#main .mainVisual .scroll,.menu,#main .mainVisual .event').delay(1000).fadeIn();
			// setTimeout(function(){
			// 	$('#main .mainVisual .textBox').addClass('on');
			// },1500);

			//cookie
			var first = Cookies.get('first');
			if (first !== "Yes") {
				setTimeout(function(){
					$('.cookieBox').addClass('on');
				},2000);
			}

			$('.cookieBox .close').click(function() {
				Cookies.set('first', 'Yes');
				$(this).parent().hide();
			});
		}
	}
	/*Pace.on('done', function() {
		var endTime = (new Date()).getTime();
		var loadTime = endTime - startTime;
		if(loadTime < 1500){
			setTimeout(function(){
				$('.loading').fadeOut();
				loadOut();
			},1500-loadTime);
		}else{
			$('.loading').fadeOut();
			loadOut();
		}
	});*/
	// if($('body').attr('id')=="index"){
	// 	$('.loading .line span').animate({width:'100%'},1500);
	// 	$('#myVideo').get(0).addEventListener("canplay", function(){
	// 		var endTime = (new Date()).getTime();
	// 		var loadTime = endTime - startTime;
	// 		if(loadTime < 1500){
	// 			setTimeout(function(){
	// 				$('.loading').fadeOut();
	// 				loadOut();
	// 			},1500-loadTime);
	// 		}else{
	// 			$('.loading').fadeOut();
	// 			loadOut();
	// 		}
	// 	});
	// }
	// $(document).ready(function(){
		if($('body').attr('id')=="index"){

			$('#myVideo').get(0).addEventListener('playing', function(){
				// $('.loading .line span').animate({width:'100%'},300);
				setTimeout( function() {
					$('.loading .line span').toggleClass('complete');
					$('.loading').fadeOut();
					loadOut();
				}, 1500);
			});

			$('#myVideo').get(0).addEventListener('error', function(event){
				console.log(event);
				$('#myVideo').hide();
				$('.loading').fadeOut();
				loadOut();
			});

			$('#myVideo').get(0).addEventListener('ended', function(){
				$('#myVideo').get(0).currentTime = 0;			
				$('#myVideo').get(0).play();
			});

			if ($('#myVideo').get(0).canPlayType('application/vnd.apple.mpegurl')) {
				$('#myVideo').get(0).src = "/img/index/movie.m3u8";
				$('#myVideo').get(0).load();
				$('#myVideo').get(0).addEventListener("canplaythrough", function(){
					$('#myVideo').get(0).play()
					.then(function(){})
					.catch(function(error) {
						console.log(error);
						$('#myVideo').hide();
						$('.loading').fadeOut();
						loadOut();
					});
				});
			} else if(Hls.isSupported()) {
				var config = {
					manifestLoadingTimeOut: 4000, 
					manifestLoadingMaxRetry: 10,
					manifestLoadingMaxRetryTimeout: 4000,
					levelLoadingTimeOut: 4000,
					levelLoadingMaxRetry: 10,
					levelLoadingMaxRetryTimeout: 4000,
					fragLoadingTimeOut: 4000,
					fragLoadingMaxRetry: 10,
					fragLoadingMaxRetryTimeout: 4000,
					liveBackBufferLength: 0,
				  };
				var hls = new Hls();
				hls.loadSource('/img/index/movie.m3u8');
				hls.attachMedia( $('#myVideo').get(0) );

				hls.on(Hls.Events.MEDIA_ATTACHED, function () {});
				hls.on(Hls.Events.MANIFEST_PARSED, function (event, data) {
					console.log(hls);
					// hls.autoLevelEnabled = false;
					hls.currentLevel = 0;

					$('#myVideo').get(0).play()
					.then(function(){
						console.log('play');
					})
					.catch(function(error) {
						console.log(error);
						$('#myVideo').hide();
						$('.loading').fadeOut();
						loadOut();
					});
				});
				hls.on(Hls.Events.ERROR, function (event, data) {
					console.log(event);
					console.log(data);
					if( data.fatal === true ){
						$('#myVideo').hide();
					}
					$('.loading').fadeOut();
					loadOut();
				});
				// セグメントが再生されるたびに発火するイベントリスナー
				hls.on(Hls.Events.FRAG_CHANGED, function(event, data) {
					var currentFragment = data.frag;
					// console.log('Currently playing fragment:', currentFragment);
					if(currentFragment.relurl === 'movie-1-000.ts'){
						// $('#main .mainVisual .textBox .movie-section').removeClass('movie-section-active');
						setTimeout(function(){
							$('#main .mainVisual .textBox .movie-section-01').addClass('movie-section-active');
						},1500);

						// 5秒後にメッセージを消す
						setTimeout(function(){
							$('#main .mainVisual .textBox .movie-section-01').removeClass('movie-section-active');
						},4500);

					}
					if(currentFragment.relurl === 'movie-2-000.ts'){
						// $('#main .mainVisual .textBox .movie-section').removeClass('movie-section-active');
						// setTimeout(function(){
							// $('#main .mainVisual .textBox .movie-section-02').addClass('movie-section-active');
						// },1000);	

						// メッセージを表示したあと5秒後にメッセージを消す
						$('#main .mainVisual .textBox .movie-section-02').addClass('movie-section-active');

						setTimeout(function(){
							$('#main .mainVisual .textBox .movie-section').removeClass('movie-section-active');
						},7700);
						
					}
					if(currentFragment.relurl === 'movie-3-000.ts'){
						// $('#main .mainVisual .textBox .movie-section').removeClass('movie-section-active');
						// setTimeout(function(){
							$('#main .mainVisual .textBox .movie-section-03').addClass('movie-section-active');
						// },1000);	

						setTimeout(function(){
							$('#main .mainVisual .textBox .movie-section').removeClass('movie-section-active');
						},10500);

					}
					if(currentFragment.relurl === 'movie-4-000.ts'){
						// $('#main .mainVisual .textBox .movie-section').removeClass('movie-section-active');
						// setTimeout(function(){
							$('#main .mainVisual .textBox .movie-section-04').addClass('movie-section-active');
						// },1000);	

						setTimeout(function(){
							$('#main .mainVisual .textBox .movie-section').removeClass('movie-section-active');
						},6000);

					}
					if(currentFragment.relurl === 'movie-5-000.ts'){
						// $('#main .mainVisual .textBox .movie-section').removeClass('movie-section-active');
						// setTimeout(function(){
							$('#main .mainVisual .textBox .movie-section-05').addClass('movie-section-active');
						// },1000);	

						setTimeout(function(){
							$('#main .mainVisual .textBox .movie-section').removeClass('movie-section-active');
						},3000);

					}
					if(currentFragment.relurl === 'movie-6-000.ts'){
						// $('#main .mainVisual .textBox .movie-section').removeClass('movie-section-active');
						// setTimeout(function(){
							$('#main .mainVisual .textBox .movie-section-06').addClass('movie-section-active');
						// },1000);	

						setTimeout(function(){
							$('#main .mainVisual .textBox .movie-section').removeClass('movie-section-active');
						},3000);
					}
				});
			} else {
				$('.loading .line span').toggleClass('complete');
				$('#myVideo').hide();
				$('.loading').fadeOut();
				loadOut();
			}


		}
	// });

	//menu
	var state = false;  
	var scrollpos;  
	  
	$('.menu .txt').on('click', function(){
		if(state == false) {  
			state = true;  
			scrollpos = $(window).scrollTop();  
			$('body').addClass('fixed').css({'top': -scrollpos});  
			$('#gHeader,.menu').removeClass('on');
			$('#gHeader').addClass('header02');
			$('.menu').addClass('open');
			$('.menuBox').stop().fadeToggle();

			$('#gHeader .hBox #gNavi .hLinkList > li').removeClass('on');
			$('#gHeader .bgBox').fadeOut();
			$('.cover').fadeOut();
		} else {  
			$('body').removeClass('fixed').css({'top': 0});  
			window.scrollTo( 0 , scrollpos );  
			if($('body').attr('id') == 'index'){
				if($(window).scrollTop() > $('.mainVisual').height()-200){
					$('#gHeader,.menu').addClass('on');
				}else{
					$('#gHeader,.menu').removeClass('on');
				}
			}else{
				$('#gHeader,.menu').addClass('on');
			}
			$('.menuBox').stop().fadeToggle(function(){  
				$('#gHeader').removeClass('header02');
				$('.menu').removeClass('open');	
				state = false;  
			});
			// state = false;  
		}  
	});

	//navi
	/*$('#gHeader .hBox #gNavi .hLinkList li:has(.bgBox)').mouseenter(function(){
		$(this).addClass('on').siblings().removeClass('on').find('.bgBox').hide();
		$(this).find('.bgBox').fadeIn();
		$('.cover').fadeIn();
	});

	$('#gHeader .bgBox .close,.cover').click(function(){
		$('#gHeader .hBox #gNavi .hLinkList > li').removeClass('on');
		$('#gHeader .bgBox').fadeOut();
		$('.cover').fadeOut();
	});*/

	//header
	var prevTop = 0,
	currTop = 0,
	topflag = 0,
	btmflag = 0;
	$(window).scroll(function() {
		if(state == false) {
			currTop = $(window).scrollTop();
			if($(window).scrollTop() > 10 && $(window).scrollTop() < $('body').height() - $(window).height()){
				if (currTop < prevTop) {
					if(topflag == 0){
						topflag = 1;
						btmflag = 0;
						$('#gHeader,.menu').removeClass('hide');
					}
				} else if(currTop > prevTop) {
					if(btmflag == 0){
						$('#gHeader,.menu').addClass('hide');
						$('#gHeader .hBox #gNavi .hLinkList > li').removeClass('on');
						$('#gHeader .bgBox').fadeOut();
						$('.cover').fadeOut();
						btmflag = 1;
						topflag = 0;
					}
				}
				setTimeout(function(){prevTop = currTop;},0);
			}else if($(window).scrollTop() !== 0 && $(window).scrollTop() >= $('body').height() - $(window).height()){
				$('#gHeader,.menu').addClass('hide');
				$('#gHeader .hBox #gNavi .hLinkList > li').removeClass('on');
				$('#gHeader .bgBox').fadeOut();
				$('.cover').fadeOut();
			}else {
				$('#gHeader,.menu').removeClass('hide');
			}
			if($('body').attr('id') == 'index' || $('body').attr('id') == 'ev'){
				if($(window).scrollTop() > $('.mainVisual').height()-200){
					$('#gHeader,.menu').addClass('on');
				}else{
					$('#gHeader,.menu').removeClass('on');
				}
			}
		}
	});

	//scroll
	$(window).scroll(function(){
		var windowHeight = $(window).height(),
		topWindow = $(window).scrollTop();
		$('.fadeInUp,.swipeIn,.swipeInB,.swipeInL,.fadeInRight,.aniBox').each(function(){
			var targetPosition = $(this).offset().top;
			if(topWindow > targetPosition - windowHeight + 100){
				$(this).addClass('animate');
			}
		});

		//parallaxImg
		// $('.paImg').each(function(){
		// 	var targetPosition = $(this).offset().top;
		// 	if(topWindow > targetPosition - windowHeight){
		// 		var h = $(this).parents('.pho').height();
		// 		var st = topWindow + windowHeight - targetPosition;
		// 		var imgH = h*1.2;
		// 		var diff = imgH - h;
		// 		var half = diff*0.5;
		// 		var distance = half / windowHeight * st;
		// 		$(this).css({
		// 			'transform': 'translateY(-' + distance + 'px) scale(1.2)'
		// 		});
		// 	}
		// });

		//pageTop
		var winH;
		if(window.visualViewport){
			winH = window.visualViewport.height;
		}else{
			winH = window.innerHeight;
		}
		var pOff = window.innerWidth < 897? 0 : 91;
		if(topWindow > 200){
			$('.pageTop').stop().fadeIn();
			if(topWindow > $('#gFooter .fBtmBox').offset().top - winH-pOff){
				$('.pageTop').addClass('abs');
			}else {
				$('.pageTop').removeClass('abs');
			}
		}else{
			$('.pageTop').stop().fadeOut();
		}
	}).trigger('scroll');

	//accordion
	if($(window).width() < 897) {
		$('#gFooter .fBox .linkBox .fNavi li.down > a,.menuBox .inner .mLinkList .linkList li.down > a').click(function(){
			$(this).toggleClass('on').next().slideToggle();
			return false;
		});
	}
	var pcflag, spflag;
    if ($(window).width() > 896) {
        pcflag = 1;
        spflag = 0;
    } else {
        pcflag = 0;
        spflag = 1;
    }
    $(window).resize(function() {
        if ($(window).width() < 897) {
            if (pcflag) {
                setTimeout(function() { window.location.reload() }, 100);
                pcflag = 0;
                spflag = 1;
            }
        } else {
            if (spflag) {
                setTimeout(function() { window.location.reload() }, 100);
                pcflag = 1;
                spflag = 0;
            }
        }
    });

	if($('.comTextDl').length) {
		$('.comTextDl > dt').click(function(){
			$(this).toggleClass('on');
			$(this).next().stop().slideToggle();
		});
	}

	if($('.comImgUl').length) {
		$('.comImgUl li .ttl').matchHeight();
	}
});

$(window).on('load',function(){
	var localLink = window.location+'';
	if(localLink.indexOf("#") != -1 && localLink.slice(-1) != '#'){
		localLink = localLink.slice(localLink.indexOf("#")+1);
		$('html,body').animate({scrollTop: $('#'+localLink).offset().top}, 500);
	}
});