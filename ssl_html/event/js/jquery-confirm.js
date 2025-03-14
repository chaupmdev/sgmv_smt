/*!
 * jquery-confirm v1.6 (http://craftpip.github.io/jquery-confirm/)
 * Author: Boniface Pereira
 * Website: www.craftpip.com
 * Contact: hey@craftpip.com
 *
 * Copyright 2013-2015 jquery-confirm
 * Licensed under MIT (https://github.com/craftpip/jquery-confirm/blob/master/LICENSE)
 */

if (typeof jQuery === 'undefined') {
    throw new Error('jquery-confirm requires jQuery');
}

var jconfirm, Jconfirm;
(function ($) {
    
    $.confirm = function (options) {
        /*
         *  Alias of jconfirm 
         */
        return jconfirm(options);
    };
    $.alert = function (options) {
        /*
         *  Alias of jconfirm 
         */
        options.cancelButton = false;
        return jconfirm(options);
    };
    $.dialog = function (options) {
        /*
         *  Alias of jconfirm 
         */
        options.cancelButton = false;
        options.confirmButton = false;
        return jconfirm(options);
    };
    jconfirm = function (options) {
        /*
         * initial function for calling.
         */
        if (jconfirm.defaults) {
            /*
             * Merge global defaults with plugin defaults
             */
            $.extend(jconfirm.pluginDefaults, jconfirm.defaults);
        }
        /*
         * merge options with plugin defaults.
         */
        var options = $.extend({}, jconfirm.pluginDefaults, options);
        return new Jconfirm(options);
    };
    Jconfirm = function (options) {
        /*
         * constructor function Jconfirm,
         * options = user options.
         */
        $.extend(this, options);
        this._init();
    };
    Jconfirm.prototype = {
        _init: function () {
            var that = this;
            this._rand = Math.round(Math.random() * 99999);
            this._buildHTML();
            this._bindEvents();
            setTimeout(function () {
                that.open();
            }, 0);
        },
        animations: ['anim-scale', 'anim-top', 'anim-bottom', 'anim-left', 'anim-right', 'anim-zoom', 'anim-opacity', 'anim-none', 'anim-rotate', 'anim-rotatex', 'anim-rotatey', 'anim-scalex', 'anim-scaley'],
        _buildHTML: function () {
            var that = this;

            /*
             * Cleaning animations.
             */
            this.animation = 'anim-' + this.animation.toLowerCase();
            if (this.animation === 'none')
                this.animationSpeed = 0;

            /*
             * Append html to body.
             */
            this.$el = $(this.template).appendTo(this.container).addClass(this.theme);
            this.$b = this.$el.find('.jconfirm-box').css({
                '-webkit-transition-duration': this.animationSpeed / 1000 + 's',
                'transition-duration': this.animationSpeed / 1000 + 's',
                '-webkjit-transition-timing-function': 'cubic-bezier(0.27, 1.12, 0.32, ' + this.animationBounce + ')',
                'transition-timing-function': 'cubic-bezier(0.27, 1.12, 0.32, ' + this.animationBounce + ')',
            });
            this.$b.addClass(this.animation);

            var that = this;

            /*
             * Timeout needed for DOM render time. or it never animates.
             */
            setTimeout(function () {
                that.$el.find('.jconfirm-bg').animate({
                    opacity: 1
                }, that.animationSpeed / 2);
            }, 1);

            /*
             * Setup title contents
             */
            if (this.title) {
                this.$el.find('div.title').html('<i class="' + this.icon + '"></i> ' + this.title);
            } else {
                this.$el.find('div.title').remove();
            }

            this.contentDiv = this.$el.find('div.content');

            /*
             * Settings up buttons
             */
            this.$btnc = this.$el.find('.buttons');
            if (this.confirmButton && this.confirmButton.trim() !== '') {
                this.$confirmButton = $('<button class="btn">' + this.confirmButton + '</button>')
                        .appendTo(this.$btnc)
                        .addClass(this.confirmButtonClass);
            }
            if (this.cancelButton && this.cancelButton.trim() !== '') {
                this.$cancelButton = $('<button class="btn">' + this.cancelButton + '</button>')
                        .appendTo(this.$btnc)
                        .addClass(this.cancelButtonClass);
            }
            if (!this.confirmButton && !this.cancelButton) {
                this.$btnc.remove();

                if (this.closeIcon)
                    this.$closeButton = this.$b.find('.closeIcon').show();
            }

            this.setContent();

            if (this.autoClose)
                this._startCountDown();
        },
        setContent: function (string) {
            var that = this;

            /*
             * Set content.
             */
            if (typeof string !== undefined && typeof string === 'string') {
                this.content = string;
                this.setContent();
            } else if (typeof this.content === 'boolean') {
                if (!this.content)
                    this.contentDiv.remove();
                else
                    console.error('Invalid option for property content: passed TRUE');
            } else if (typeof this.content === 'string') {

                if (this.content.substr(0, 4).toLowerCase() === 'url:') {
                    this.contentDiv.html('');
                    this.$btnc.find('button').attr('disabled', 'disabled');
                    var url = this.content.substring(4, this.content.length);
                    $.get(url).done(function (html) {
                        that.contentDiv.html(html);
                    }).always(function () {
                        if(typeof that.contentLoaded === 'function')
                            that.contentLoaded(that.$b);
                        that.$btnc.find('button').removeAttr('disabled');
                        that.setDialogCenter();
                    });
                } else {
                    this.contentDiv.html(this.content);
                }

            } else if (typeof this.content === 'function') {

                this.contentDiv.html('');
                this.$btnc.find('button').attr('disabled', 'disabled');

                var promise = this.content(this);
                if (typeof promise !== 'object') {
                    console.error('The content function must return jquery promise.');
                } else if (typeof promise.always !== 'function') {
                    console.error('The object returned is not a jquery promise.');
                } else {
                    promise.always(function () {
                        /*
                         * in the future.
                         */
                        that.$btnc.find('button').removeAttr('disabled');
                        that.setDialogCenter();
                    });
                }

            } else {
                console.error('Invalid option for property content, passed: ' + typeof this.content);
            }

            this.setDialogCenter();
        },
        _startCountDown: function () {
            var opt = this.autoClose.split('|');
            if (/cancel/.test(opt[0]) && this.type === 'alert'){
                return false;
            }else if (/confirm|cancel/.test(opt[0])) {
                this.$cd = $('<span class="countdown">').appendTo(this['$' + opt[0] + 'Button']);
                var that = this;
                that.$cd.parent().click();
                var time = opt[1] / 1000;
                this.interval = setInterval(function () {
                    that.$cd.html(' [' + (time -= 1) + ']');
                    if (time === 0) {
                        that.$cd.parent().trigger('click');
                        clearInterval(that.interval);
                    }
                }, 1000);
            }else{
                console.error('Invalid option '+opt[0]+', must be confirm/cancel');
            }
        },
        _bindEvents: function () {
            var that = this;
            this.$el.find('.jconfirm-bg').click(function (e) {
                if (that.backgroundDismiss) {
                    that.cancel();
                    that.close();
                } else {
                    that.$b.addClass('hilight');
                    setTimeout(function () {
                        that.$b.removeClass('hilight');
                    }, 400);
                }
            });
            if (this.$confirmButton) {
                this.$confirmButton.click(function (e) {
                    e.preventDefault();
                    var r = that.confirm(that.$b);
                    if (typeof r === 'undefined' || r)
                        that.close();
                });
            }
            if (this.$cancelButton) {
                this.$cancelButton.click(function (e) {
                    e.preventDefault();
                    var r = that.cancel(that.$b);
                    if (typeof r === 'undefined' || r)
                        that.close();
                });
            }
            if (this.$closeButton) {
                this.$closeButton.click(function (e) {
                    e.preventDefault();
                    that.cancel();
                    that.close();
                });
            }
            if (this.keyboardEnabled) {
                setTimeout(function () {
                    $(window).on('keyup.' + this._rand, function (e) {
                        that.reactOnKey(e);
                    });
                }, 500);
            }

            $(window).on('resize.' + this._rand, function () {
                that.setDialogCenter();
            });

            this.setDialogCenter();
        },
        reactOnKey: function key(e) {
            /*
             * prevent keyup event if the dialog is not last! 
             */
            var a = $('.jconfirm');
            if (a.eq(a.length - 1)[0] !== this.$el[0])
                return false;

            var key = e.which;
            console.log(key);
            if (key === 27) {
                /*
                 * if ESC key
                 */
                if (!this.backgroundDismiss) {
                    /*
                     * If background dismiss is false, Glow the modal.
                     */
                    this.$el.find('.jconfirm-bg').click();
                    return false;
                }

                if (this.$cancelButton) {
                    this.$cancelButton.click();
                } else {
                    this.close();
                }
            }
            if (key === 13 || key == 32) {
                /*
                 * if ENTER or SPACE key
                 */
                if (this.$confirmButton) {
                    this.$confirmButton.click();
                } else {

                }
            }
        },
        setDialogCenter: function () {
            var h = $(window).height(),
                    h2 = this.$b.height(),
                    mar = (h - h2) / 2;
            this.$b.find('.content').css({
                'max-height': h - 200 + 'px'
            });
            this.$b.css({
                'margin-top': mar
            });
        },
        close: function () {
            var that = this;

            /*
             unbind the window resize & keyup event.
             */
            $(window).unbind('resize.' + this._rand);
            if (this.keyboardEnabled)
                $(window).unbind('keyup.' + this._rand);

            this.$el.find('.jconfirm-bg').animate({
                opacity: 0
            }, this.animationSpeed / 2);
            this.$b.addClass(this.animation);
            $('body').removeClass('jconfirm-noscroll');
            setTimeout(function () {
                that.$el.remove();
            }, this.animationSpeed + 30); // wait 30 miliseconds more, ensure everything is done.

            jconfirm.record.closed += 1;
            jconfirm.record.currentlyOpen -= 1;
        },
        open: function () {
            var that = this;
            if (this.isClosed())
                return false;

            $('body').addClass('jconfirm-noscroll');
            this.$b.removeClass(this.animations.join(' '));
            /**
             * Blur the focused elements, prevents re-execution with button press.
             */
            $('body :focus').trigger('blur');
            this.$b.find('input[autofocus]:visible:first').focus();
            jconfirm.record.opened += 1;
            jconfirm.record.currentlyOpen += 1;
            return true;
        },
        isClosed: function () {
            return (this.$el.css('display') === '') ? true : false;
        }
    };

    jconfirm.pluginDefaults = {
        template: '<div class="jconfirm"><div class="jconfirm-bg"></div><div class="container"><div class="row"><div class="col-md-6 col-md-offset-3 span6 offset3"><div class="jconfirm-box"><div class="closeIcon"><span class="glyphicon glyphicon-remove"></span></div><div class="title"></div><div class="content"></div><div class="buttons"></div><div class="jquery-clear"></div></div></div></div></div></div>',
        title: 'Hello',
        content: 'Are you sure to continue?',
        contentLoaded: function () {
        },
        icon: '',
        confirmButton: 'OK',
        cancelButton: 'Cancel',
        confirmButtonClass: 'btn-default',
        cancelButtonClass: 'btn-default',
        theme: 'white',
        animation: 'scale',
        animationSpeed: 400,
        animationBounce: 1.5,
        keyboardEnabled: false,
        container: 'body',
        confirm: function () {
        },
        cancel: function () {
        },
        backgroundDismiss: true,
        autoClose: false,
        closeIcon: true,
    };
    jconfirm.record = {
        opened: 0,
        closed: 0,
        currentlyOpen: 0,
    };
})(jQuery);
