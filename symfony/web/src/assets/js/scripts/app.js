(function ($) {
    'use strict';
    var $body = $('body');

    function is_touch_device() {
        return (('ontouchstart' in window) || (navigator.MaxTouchPoints > 0) || (navigator.msMaxTouchPoints > 0));
    }

    var touch = is_touch_device();

    if (typeof FastClick == 'function' && touch) {
        FastClick.attach(document.body);
    }

    var scrollHandling = {
        fn: {
            documentMove: function (e) {
                e.preventDefault();
            },
            contentStart: function (e) {
                this.allowUp = (this.scrollTop > 0);
                this.allowDown = (this.scrollTop < this.scrollHeight - this.clientHeight);
                this.slideBeginY = e.pageY;
            },
            contentMove: function (e) {
                var up = (e.pageY > this.slideBeginY);
                var down = (e.pageY < this.slideBeginY);
                this.slideBeginY = e.pageY;
                if ((up && this.allowUp) || (down && this.allowDown)) {
                    e.stopPropagation();
                }
                else {
                    e.preventDefault();
                }
            },
            allowFull: function (e) {
                e.stopPropagation();
            }
        },
        preventTouch: function () {
            document.addEventListener("touchmove", this.fn.documentMove);
        },
        allowTouch: function (restore) {
            var aContent = document.getElementsByClassName('_scroll');
            var aContentFull = document.getElementsByClassName('_scroll-full');
            var aContentLen = aContent.length;
            for (var i = 0; i < aContentLen; i++) {
                var content = aContent[i];
                if (restore) {
                    content.removeEventListener('touchstart', this.fn.contentStart);
                    content.removeEventListener('touchmove', this.fn.contentMove);
                }
                content.addEventListener('touchstart', this.fn.contentStart);
                content.addEventListener('touchmove', this.fn.contentMove);
            }
            for (var i = 0; i < aContentFull.length; i++) {
                var content = aContentFull[i];
                if (restore) {
                    content.removeEventListener('touchmove', this.fn.allowFull);
                }
                content.addEventListener('touchmove', this.fn.allowFull);
            }
        },
        scrollTop: function () {
            $('.js-scrolltop').on('click', function () {
                $('._scroll').animate({"scrollTop": "0px"}, 300);
            });
        },
        rebuild: function () {
            this.allowTouch();
        },
        init: function () {
            this.preventTouch();
            this.allowTouch();
            this.scrollTop()
        }
    };


    var countUp = {
        ease: function (t, b, c, d) {
            t /= d;
            t--;
            return c * (t * t * t * t * t + 1) + b;
        },
        animate: function (from, to, duration, cb) {
            var start = new Date().getTime();
            var timer = setInterval(function () {
                var time = new Date().getTime() - start;
                cb(countUp.ease(time, from, to - from, duration));
                if (time >= duration) clearInterval(timer);
            }, 1000 / 60);
        },
        count: function (el, to) {
            var from = 0, duration = 1000;
            countUp.animate(from, to, duration, function (val) {
                el.innerHTML = val.toFixed(2);
            });
        },
        init: function () {
            $('[data-count]').each(function () {
                var countTo = $(this).attr('data-count');
                countUp.count(this, countTo);
            });
        }
    };

    var ajaxStandard = {
        addEvents: function (sel, eventName, fn) {
            $(sel).each(function () {
                var $el = $(this);
                if ($el.data('init' + eventName) != true) {
                    $el.data('init' + eventName, true);
                    $el.on(eventName, function (e) {
                        fn(e, this);
                    })
                }
            });
        },
        loadMain: function () {
            $.ajax({
                url: './',
                type: 'GET',
                success: function (data) {
                    var content = $('<div>').append(data).find('.body').html();
                    $('.body').html(content);
                }
            });
        },
        loadContent: function (url, cb) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    cb();
                }
            });
        },
        init: function () {
            ajaxStandard.addEvents('.js--ajax', 'click', function (e, el) {
                e.preventDefault();
                ajaxStandard.loadContent(el, function () {
                    ajaxStandard.loadMain();
                });
            })
        }
    };

    var ajaxModal = {
        opt: {
            time: 300,
            wait: '<div class="loading"><i></i><span>loading</span></div>'
        },
        classNames: {
            anim: '_anim',
            active: '_modal'
        },
        modal: {
            el: '.modal',
            content: '.modal-content',
            header: '#modal-header',
            post: '.js--submit-modal-form'
        },
        animation: function () {
            $body.addClass(ajaxModal.classNames.anim);
            setTimeout(function () {
                $body.removeClass(ajaxModal.classNames.anim);
            }, ajaxModal.opt.time);
        },
        loadContent: function (url, cb) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    var content = $('<div>').append(data).find('#ajax-content').html();
                    var header = $('<div>').append(data).find('#ajax-header').html();
                    cb(content, header);
                }
            });
        },
        closeModal: function (reload) {
            ajaxModal.animation();
            $body.removeClass(ajaxModal.classNames.active);
            if (reload) {
                ajaxModal.reInit();
                scrollHandling.rebuild();
                countUp.init();
            }
        },
        openModal: function (el) {
            var url = $(el).attr('href');

            $(ajaxModal.modal.post).hide();
            $(ajaxModal.modal.content).html(ajaxModal.opt.wait);
            $(ajaxModal.modal.header).html('');
            ajaxModal.animation();
            $body.addClass(ajaxModal.classNames.active);
            ajaxModal.loadContent(url, function (data, header) {
                $(ajaxModal.modal.content).hide().html(data).fadeIn();
                $(ajaxModal.modal.header).hide().html(header).fadeIn();
                scrollHandling.rebuild();
            })
        },
        addEvents: function (sel, eventName, fn) {
            $(sel).each(function () {
                var $el = $(this);
                if ($el.data('init' + eventName) != true) {
                    $el.data('init' + eventName, true);
                    $el.on(eventName, function (e) {
                        fn(e, this);
                    })
                }
            });
        },
        reInit: function () {
            ajaxModal.addEvents('.js-open-ajaxmodal', 'click', function (e, el) {
                e.preventDefault();
                ajaxModal.openModal(el);
            });
            ajaxModal.addEvents('.js-close-ajaxmodal', 'click', function (e) {
                e.preventDefault();
                ajaxModal.closeModal();
            });
        },
        init: function () {
            ajaxModal.reInit();
        }
    };


    var postLog = {
        submitForm: function (el) {
            var url = $(el).attr('action');
            $(ajaxModal.modal.content).html(ajaxModal.opt.wait);
            $(ajaxModal.modal.header).html('');
            $.post(url, $(el).serialize(), function (data) {
                if (data == 'success') {
                    views.setView('start', true, true, function () {
                        ajaxModal.closeModal(true);
                    })
                }
            });
        },
        resetTypes: function () {
            $('.js--template-name').each(function (e) {
                this.checked = false;
            });
        },
        chooseImage: function (el) {
            var image = $(el).attr('data-image');
            $('#logImage').val(image);
        },
        deleteEntry: function (cb) {
            $('[name="delete"]')[0].value = 1;
            cb();
        },
        addEvents: function (sel, eventName, fn) {
            $(sel).each(function () {
                var $el = $(this);
                if ($el.data('init' + eventName) != true) {
                    $el.data('init' + eventName, true);
                    $el.on(eventName, function (e) {
                        fn(e, this);
                    })
                }
            });
        },
        init: function () {
            postLog.addEvents('.js--post-form', 'submit', function (e, el) {
                e.preventDefault();
                postLog.submitForm(el);
            });
            postLog.addEvents('.js--template-name', 'change', function (e, el) {
                postLog.chooseImage(el);
            });
            postLog.addEvents('.js--log-segmented-control', 'change', function (e, el) {
                postLog.resetTypes();
            });
            postLog.addEvents('.js--submit-modal-form', 'click', function () {
                var el = $('.modal-content form')[0];
                postLog.submitForm(el);
            });
            postLog.addEvents('.js--delete-entry', 'click', function (e, el) {
                e.preventDefault();
                postLog.deleteEntry(function () {
                    var el = $('.modal-content form')[0];
                    postLog.submitForm(el);
                });
            });
            $(ajaxModal.modal.post).fadeIn();
        }
    };

    var login = {
        el: {
            form: '.js--login-form',
            input: '#password'
        },
        submitForm: function (el) {
            var formData = $(login.el.form).serializeArray();
            $.ajax({
                url: './login',
                type: 'POST',
                data: {_username: formData[0]['value'], _password: formData[1]['value']},
                success: function (data) {
                    if (data.indexOf('class="login-error"') < 0) {
                        $.ajax({
                            url: './',
                            type: 'POST',
                            success: function (data) {
                                console.log(url, data);
                                var content = $('<div>').append(data).find('body').html();
                                $('body').html(content);
                                login.init();
                                scrollHandling.init();
                                ajaxModal.init();
                                ajaxStandard.init();
                                countUp.init();
                            }
                        });
                    }
                    else {
                        var content = $('<div>').append(data).find('.body').html();
                        $('.body').html(content);
                        login.init();
                        scrollHandling.init();
                        ajaxModal.init();
                        ajaxStandard.init();
                        countUp.init();
                    }
                }
            });
        },
        events: function () {
            $(login.el.form).on('submit', function (e) {
                e.preventDefault();
                $(login.el.input).blur();
                login.submitForm(this);
            });
        },
        init: function () {
            if ($(login.el.form).length > 0) {
                login.events();
            }
        }
    };
    var calender = {
        el: {
            links: '.js-calender-link'
        },
        changeMonth: function (url) {
            $(ajaxModal.modal.content).html(ajaxModal.opt.wait);
            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    var content = $('<div>').append(data).find('.body').html();
                    $('.view--start .view-body').html(content);
                    setTimeout(function () {
                        views.setView('start', false);
                        ajaxModal.closeModal();
                        login.init();
                        scrollHandling.init();
                        ajaxModal.init();
                        ajaxStandard.init();
                        countUp.init();
                    }, 100);
                }
            });
        },
        events: function () {
            $(calender.el.links).on('click', function (e) {
                e.preventDefault();
                calender.changeMonth($(this).attr('href'));
            });
        },
        init: function () {
            calender.events();
        }
    };

    var views = {
        active: {
            views: 'view--active',
            nav: 'view-navigation-list-item--active'
        },
        get: {
            views: function (str) {
                return $('.view--' + str)
            },
            nav: function (str) {
                return $('[data-set-view="' + str + '"]');
            }
        },
        els: {
            views: $('.view'),
            nav: $('[data-set-view]')
        },
        fnAfter: {
            start: function () {
                countUp.init();
            },
            user: function () {
                ajaxModal.init();
                ajaxStandard.init();
            },
            calendar: function () {
                ajaxModal.init();
                ajaxStandard.init();
            },
            statistic: function () {

            }
        },
        loadView: function (sViewName, bCall, callback) {
            var VIEW = views.get.views(sViewName),
                getUrl = VIEW.attr('data-view');

            $.ajax({
                url: getUrl,
                type: 'GET',
                success: function (data) {
                    var content = $('<div>').append(data).find('.body').html(),
                        viewBody = $('<div>', {class: 'view-body', html: content});
                    VIEW.append(viewBody);
                    if (typeof views.fnAfter[sViewName] === 'function') {
                        views.fnAfter[sViewName]();
                    }
                    if(bCall){
                        scrollHandling.rebuild();
                        callback();
                    }
                }
            });
        },
        setView: function (str, bool, bForce, callback) {
            var bLoadPage = bool === false ? false : true,
                bForceLoad = bForce === true ? true : false,
                sViewName = str,
                bCall = typeof callback === 'function';
            views.els.views.removeClass(views.active.views);
            views.els.nav.parent().removeClass(views.active.nav);
            views.get.views(sViewName).addClass(views.active.views);
            views.get.nav(sViewName).parent().addClass(views.active.nav);


            var viewBody = views.get.views(sViewName).find('.view-body'),
                bLoaded = viewBody.length > 0;

            if ((bLoadPage && !bLoaded) || bForceLoad){
                console.log('remove',viewBody);
                viewBody.addClass('www');
                viewBody.remove();
                views.loadView(sViewName, bCall, callback);
            }
        },
        events: function () {
            views.els.nav.on('click', function () {
                views.setView($(this).attr('data-set-view'))
            });
        },
        init: function () {
            views.setView('start', false);
            views.events();
        }
    };
    window.view = views.setView;
    views.init();


    login.init();
    window.postLog = postLog;
    window.countUp = countUp;
    window.calender = calender;
    scrollHandling.init();
    ajaxModal.init();
    ajaxStandard.init();
    countUp.init();
}(jQuery));