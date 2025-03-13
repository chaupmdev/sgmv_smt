/*global $*/
var news = 0,
    max_news = 3,
    animation1,
    animation2,
    animation3,
    animation4,
    animation5,
    animation6,
    animation7,
    animation8,
    changelist,
    frame1 = 0,
    frame2 = 0,
    frame3 = 0,
    frame4 = 0,
    frame5 = 0,
    frame6 = 0,
    frame7 = 0,
    frame8 = 0;

function intervalEvent() {
    'use strict';
    document.getElementById('news').style.display = 'block';

    var newslist1 = document.getElementById('news-0'),
        newslist2 = document.getElementById('news-1'),
        newslist3 = document.getElementById('news-2'),
        onceFlg = false;


    switch (news) {
    case 0:
        newslist1.style.display = 'block';
        newslist2.style.display = 'none';
        newslist3.style.display = 'none';
        break;
    case 1:
        newslist1.style.display = 'none';
        newslist2.style.display = 'block';
        newslist3.style.display = 'none';
        break;
    case 2:
        newslist1.style.display = 'none';
        newslist2.style.display = 'none';
        newslist3.style.display = 'block';
        break;
    }
    news += 1;

    if (news >= max_news) {
        if (onceFlg) {
            clearInterval(changelist);
        }
        news = 0;
    }
}

function intervalEvent1() {
    'use strict';
    var height = 587,
        max_frame = 12,
        onceFlg = false;

    $('#animation_target1').css({
        'background-position': '0 ' + -height * frame1 + 'px'
    });
    frame1 += 1;
    if (frame1 >= max_frame) {
        if (onceFlg) {
            clearInterval(animation1);
        }
        frame1 = 0;
    }
}

function intervalEvent2() {
    'use strict';
    var height = 587,
        max_frame = 18,
        onceFlg = false;

    $('#animation_target2').css({
        'background-position': '0 ' + -height * frame2 + 'px'
    });
    frame2 += 1;
    if (frame2 >= max_frame) {
        if (onceFlg) {
            clearInterval(animation2);
        }
        frame2 = 0;
    }
}

function intervalEvent3() {
    'use strict';
    var height = 587,
        max_frame = 33,
        onceFlg = false;

    $('#animation_target3').css({
        'background-position': '0 ' + -height * frame3 + 'px'
    });
    frame3 += 1;
    if (frame3 >= max_frame) {
        if (onceFlg) {
            clearInterval(animation3);
        }
        frame3 = 0;
    }
}

function intervalEvent4() {
    'use strict';
    var height = 587,
        max_frame = 45,
        onceFlg = false;

    $('#animation_train').css({
        'background-position': '0 ' + -height * frame4 + 'px'
    });
    frame4 += 1;
    if (frame4 >= max_frame) {
        if (onceFlg) {
            clearInterval(animation4);
        }
        frame4 = 0;
    }
}

function intervalEvent5() {
    'use strict';
    var height = 587,
        max_frame = 26;

    $('#animation_ship').css({
        'background-position': '0 ' + -height * frame5 + 'px'
    });
    frame5 += 1;
    if (frame5 >= max_frame) {
        clearInterval(animation5);
    }
}

function intervalEvent6() {
    'use strict';
    var height = 85,
        max_frame = 22;

    $('#animation_airplane').css({
        'background-position': '0 ' + -height * frame6 + 'px'
    });
    frame6 += 1;
    if (frame6 >= max_frame) {
        clearInterval(animation6);
        intervalEvent();
        changelist = setInterval((function () {
            intervalEvent();
        }), 6000);
    }
}

function intervalEvent7() {
    'use strict';
    var height = 85,
        max_frame = 5;

    $('#animation_target4').css({
        'background-position': '0 ' + -height * frame7 + 'px'
    });
    frame7 += 1;
    if (frame7 >= max_frame) {
        clearInterval(animation7);
    }
}

function intervalEvent8() {
    'use strict';
    var height = 85,
        max_frame = 13,
        onceFlg = false;

    $('#animation_target5').css({
        'background-position': '0 ' + -height * frame8 + 'px'
    });
    frame8 += 1;
    if (frame8 >= max_frame) {
        if (onceFlg) {
            clearInterval(animation8);
        }
        frame8 = 0;
    }
}
$((function () {
    'use strict';
    var fps,
        interval1,
        interval2,
        interval3,
        interval4,
        interval5,
        interval6,
        interval7,
        interval8,
        $relative = $('.relative'),
        $art = $('.art,.charteryuso,.encho,.event,.kimitsu,.ryokyaku,.technical'),
        $byouin = $('.byouin,.gakkou,.mansion,.office,.tenkin'),
        $carbon = $('.carbon,.kojin,.ladys,.seikatu'),
        $kagukaden = $('.kagukaden,.option,.tanpin'),
        $company = $('.company,.hinshitsu,.saiyo');

    fps = 6;
    interval1 = 1 / fps * 1000;
    animation1 = setInterval(intervalEvent1, interval1);

    fps = 6;
    interval2 = 1 / fps * 1000;
    animation2 = setInterval(intervalEvent2, interval2);

    fps = 6;
    interval3 = 1 / fps * 1000;
    animation3 = setInterval(intervalEvent3, interval3);

    fps = 10;
    interval4 = 1 / fps * 1000;
    animation4 = setInterval(intervalEvent4, interval4);

    fps = 6;
    interval5 = 1 / fps * 1000;
    animation5 = setInterval(intervalEvent5, interval5);

    fps = 12;
    interval6 = 1 / fps * 1000;
    setTimeout((function () {
        animation6 = setInterval(intervalEvent6, interval6);
    }), 800);

    fps = 6;
    interval7 = 1 / fps * 1000;
    setTimeout((function () {
        animation7 = setInterval(intervalEvent7, interval7);
    }), 1000);

    fps = 6;
    interval8 = 1 / fps * 1000;
    animation8 = setInterval(intervalEvent8, interval8);

    $relative.on('mouseenter', (function () {
        $art.hide();
        $byouin.hide();
        $carbon.hide();
        $kagukaden.hide();
        $company.hide();
    })).on('mouseleave', (function () {
        $relative.trigger('mouseenter');
    }));
    $('#art,#charteryuso,#encho,#event,#kimitsu,#ryokyaku,#technical').on('mouseenter', (function () {
        $relative.trigger('mouseenter');
        $art.show();
    }));
    $('#byouin,#gakkou,#mansion,#office,#tenkin').on('mouseenter', (function () {
        $relative.trigger('mouseenter');
        $byouin.show();
    }));
    $('#carbon,#kojin').on('mouseenter', (function () {
        $relative.trigger('mouseenter');
        $carbon.show();
    }));
    $('#kagukaden,#tanpin').on('mouseenter', (function () {
        $relative.trigger('mouseenter');
        $kagukaden.show();
    }));
    $('#company').on('mouseenter', (function () {
        $relative.trigger('mouseenter');
        $company.show();
    }));
}));