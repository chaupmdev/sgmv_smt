/*global $*/
if (window.matchMedia) {
    $(function () {
        'use strict';

        function checkMobile() {
            return !window.matchMedia('(min-width: 737px)').matches;
        }

        function accordionToggle($this, tag) {
            $this.next(tag).slideToggle();
            $this.children('span').toggleClass('open');
        }

        $('.accordion p').on('click', function () {
            if (!checkMobile()) {
                return false;
            }
            accordionToggle($(this), 'ul');
        });

        $('#service_list_ttl').on('click', function () {
            if (!checkMobile()) {
                return false;
            }
            accordionToggle($(this), 'div');
        });

        $('.accordion h1').on('click', function () {
            if (!checkMobile()) {
                return false;
            }
            accordionToggle($(this), 'div');
        });
    });
}