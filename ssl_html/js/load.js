/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function is_array(obj) {
    return Object.prototype.toString.call(obj) === '[object Array]';
}

function add_child_js(src) {
    var element = document.createElement('script');
    element.src = src;
    document.body.appendChild(element);
}

function add_child_css(src) {
    var css_element = document.createElement('link');
    css_element.type = 'text/css';
    css_element.rel = 'stylesheet';
    css_element.href = src;
    document.body.appendChild(css_element);
}

function require_onload(arg) {
    if (is_array(arg)) {
        var element = [],
            i;
        for (i = 0; i < arg.length; i += 1) {
            if (arg[i].match(/\.css$/)) {
                add_child_css(arg[i]);
            } else if (arg[i].match(/\.js$/)) {
                add_child_js(arg[i]);
            } else {
                window.alert('check url : ' + arg[i]);
            }
        }
    }
}

function add_child_js(src) {
    var element = document.createElement('script');
    element.src = src;
    document.body.appendChild(element);
}

function add_child_css(src) {
    var css_element = document.createElement('link');
    css_element.type = 'text/css';
    css_element.rel = 'stylesheet';
    css_element.href = src;
    document.body.appendChild(css_element);
}

// dom ready時に読み込み開始（事前にjQueryを読み込ませておいてください。）
$(window).load(function () {
    require_onload(['/js/form.js', '/css/formbase.css']);
});