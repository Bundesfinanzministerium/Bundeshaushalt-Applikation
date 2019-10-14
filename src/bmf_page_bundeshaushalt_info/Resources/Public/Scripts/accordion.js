/**
 * $.fn.ppAccordion
 * Version 0.0.2
 *
 * All rights reserved, © Publicis Pixelpark GmbH
 */
(function ($) {
    "use strict";

    function findTrigger(accordion) {
        return $('[aria-controls="' + accordion.id + '"]');
    }

    function open(accordion, options) {
        accordion = $(accordion);
        var trigger = findTrigger(accordion);

        accordion.slideDown({
            duration: options.duration,
            easing: options.easing,
            start: function () {
                accordion.trigger('pp.accordion.opening');
                if (trigger.length) {
                    trigger.trigger('pp.accordion.opening');
                }
            },
            done: function () {
                accordion
                    .addClass('in')
                    .attr('aria-expanded', 'true')
                    .trigger('pp.accordion.opened');

                if (trigger.length) {
                    trigger.attr('aria-hidden', 'false').trigger('pp.accordion.opened');
                }

                if (options.setFocus) {
                    accordion.focus();
                }
            }
        });
    }

    function close(accordion, options) {
        accordion = $(accordion);
        var trigger = findTrigger(accordion);

        accordion.slideUp({
            duration: options.duration,
            easing: options.easing,
            start: function () {
                accordion.trigger('pp.accordion.closing');
                if (trigger.length) {
                    trigger.trigger('pp.accordion.closing');
                }
            },
            done: function () {
                accordion
                    .removeClass('in')
                    .attr('aria-expanded', 'false')
                    .trigger('pp.accordion.closed');

                if (trigger.length) {
                    trigger.attr('aria-hidden', 'true').trigger('pp.accordion.closed');
                }
            }
        });
    }

    function toggle(accordion, options) {
        accordion = $(accordion);
        if (isExpanded(accordion)) {
            close(accordion, options);
        } else {
            open(accordion, options);
        }
    }

    function registerEvents(accordion, trigger, options) {
        $(trigger).on('click', function (e) {
            e.preventDefault();
            toggle(accordion, options);
        });
    }

    function isExpanded(accordion) {
        accordion = $(accordion);
        return (accordion.attr('aria-expanded') === 'true' || accordion.hasClass('in'));
    }

    $.fn.ppAccordion = function (action, options) {
        var settings = $.extend({
            'duration': '500',
            'easing': 'swing',
            'setFocus': true
        }, options);

        this.each(function () {
            var $el = $(this);
            var target;
            if (!$el.attr('aria-controls') && !$el.hasClass('ppAccordion')) {
                console.warn('Target could not be determined.');
                return;
            }

            if ($el.attr('aria-controls')) {
                target = document.getElementById($el.attr('aria-controls'));
            } else {
                target = this;
            }
            switch (action) {
                case 'open':
                    open(target, settings);
                    break;
                case 'close':
                    close(target, settings);
                    break;
                case 'toggle':
                    toggle(target, settings);
                    break;
                default:
                    registerEvents(target, $el, settings);
                    break;
            }
        });

        return this;
    };

    $(document).on('click', '[data-toggle="ppAccordion"]', function (e) {
        e.preventDefault();
        $(this).ppAccordion('toggle').toggleClass('active');
    });
})(jQuery);
