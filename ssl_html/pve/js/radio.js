/*global $*/
$(function () {
    'use strict';

    var lbs = document.getElementsByTagName('label'),
        cimgs,
        i,
        j;

    for (i = 0; i < lbs.length; i += 1) {
        cimgs = lbs[i].getElementsByTagName('img');
        for (j = 0; j < cimgs.length; j += 1) {
            cimgs[j].formCtrlId = lbs[i].htmlFor;
            cimgs[j].onclick = function () {
                document.getElementById(this.formCtrlId).click();
            };
        }
    }
});