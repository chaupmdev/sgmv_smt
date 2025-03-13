/*global $*/
$(function () {
    'use strict';
    $('a[href^="#"]').on('click', function () {
        var speed = 400,
            href = $(this).attr('href'),
            $target = $(href == '#' || href == '' ? 'html' : href),
            position;
        if (!$target.size()) {
            return false;
        }
        position = $target.offset().top;
        $('body,html').animate({
            scrollTop: position
        }, speed, 'swing');
        return false;
    });
});