/*global window, document, jQuery:true, Raphael, console, describe, it, expect, txPpBundeshaushaltPreDomain, txPpBundeshaushaltStage */

txPpBundeshaushaltPreDomain = typeof txPpBundeshaushaltPreDomain === 'undefined' ? '' : txPpBundeshaushaltPreDomain;
txPpBundeshaushaltStage = typeof txPpBundeshaushaltStage === 'undefined' ? '' : txPpBundeshaushaltStage;

jQuery = jQuery || {fn: {}};
jQuery.fn.ppBundeshaushalt = jQuery.fn.ppBundeshaushalt || {};

jQuery.fn.ppBundeshaushalt.timeline = (function (window, $, Raphael) {

    "use strict";

    /**
     * Preparing private methods
     */
    var _plugin,
        _html,
        _events,
        _bargraph,

        _colors = {
            'orange': '#C85000'
        },

        _animation = {
            'duration': 125
        },

        _translate = {
            'de': {
                'account': 'Konto',
                'target': 'Soll',
                'actual': 'Ist',
                'flow': 'Stromgröße',
                'income': 'Einnahmen',
                'expenses': 'Ausgaben',
                'open': 'Öffnen',
                'close': 'Schließen'
            }
        },

        _service = {},

    /**
     *
     * @type {{initialize, show}}
     */
    _plugin = (function () {

        // private properties
        var _status = 0,                     // rendering active?         0 = off, 1 = on

            _cache = {
                'show': '',
                'stack': [],
                'stackLastIdentifier': ''
            },

            propertyMap = {
                'structure': {'agency': 'einzelplan', 'group': 'gruppe', 'function': 'funktion'},
                'account': {'soll': 'target', 'ist': 'actual'},
                'flow': {'e': 'income', 'a': 'expenses'}
            },

            _initialize,
            _show,
            _process,
            _request,
            _response,

            _getStatus,
            _setStatus,
            _getBrightness;

        _initialize = function () {
            _service = {
                'protocol': window.location.protocol + '//',
                'host': txPpBundeshaushaltPreDomain,
                'path': 'rest-timeline/'
            };

            // setup html container
            _html.setup($('#data-timeline'));

            // prepare events
            _events.initialize();

            return true;
        };

        _show = function (label, value, year, account, flow, structure, address, color) {
            // mapping properties
            structure = propertyMap.structure[structure];
            account = propertyMap.account[account];
            flow = propertyMap.flow[flow];

            _cache.show = JSON.stringify({
                'label': label,
                'value': account === 'target' ? value * 1000 : value,
                'year': year,
                'account': account,
                'flow': flow,
                'structure': structure,
                'address': address,
                'color': color
            });

            if (_status === 1) {
                _request();
            }

            return true;

        };

        _process = function (obj) {

            // set mode if not set
            var mode = _html.get.mode();
            if (mode.length === 0) {
                _html.set.mode(obj.header.account);
            }

            // render header
            _html.render.header(obj.header.year, _translate.de[obj.header.flow], obj.header.value, obj.header.label);
            _html.render.legend(obj.modes);
            _html.render.table(obj.header.label, obj.timeline);
            _html.render.info();

            // render bargraph
            _bargraph.render(
                'data-timeline-stage',
                obj,
                _html.render.xLabels,
                _html.render.yLabels
            );

            return true;
        };

        _request = function () {
            // build identifier
            var cache = {'show': JSON.parse(_cache.show)},
                identifier = cache.show.structure + (cache.show.address !== '' ? '/' + cache.show.address : '') + '.html',
                url = _service.protocol + _service.host + '/' + _service.path + identifier,
                crossDomain = false;

            // development
            if (txPpBundeshaushaltStage === 'development') {
                url = _service.protocol + 'www.bundeshaushalt-info.dev/' + _service.path + identifier;
                crossDomain = true;
            }

            // check if request already cached
            if (typeof _cache.stack[identifier] === 'string') {

                // process parsed request
                _process(
                    _response.parse(JSON.parse(_cache.stack[identifier]), cache.show)
                );

            } else {

                $.ajax({
                    'url': url,
                    'crossDomain': crossDomain,
                    'dataType': 'json',
                    'success': function (data, textStatus, jqXHR) {
                        _response.success(data, textStatus, jqXHR, identifier, cache.show);
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        _response.error(jqXHR, textStatus, errorThrown);
                        jQuery.fn.ppBundeshaushalt.messaging.error(
                            "Problem beim Verbindungsafbau",
                            "Leider ist ein Fehler bei der Verbindung mit:\n" + url + " aufgetreten"
                        );
                    }
                });

            }

        };

        _response = (function () {

            var _success, _error, _parse;

            _success = function (data, textStatus, jqXHR, identifier, request) {
                // cache request
                _cache.stack[identifier] = JSON.stringify(data);
                _cache.stackLastIdentifier = identifier;

                // process parsed request
                _process(
                    _response.parse(data, request)
                );

                return true;
            };

            _error = function (jqXHR, textStatus, errorThrown) {
                return true;
            };

            _parse = function (data, request) {
                var header = {
                        'year': request.year,
                        'account': request.account,
                        'flow': request.flow,
                        'value': request.value,
                        'label': request.label
                    },

                    session = [2002, 2005, 2009, 2013, 2018],
                    active = {
                        'label': request.year,
                        'value': request.account
                    },
                    modes = [],
                    timeline = [],

                    targetColor = request.color !== '' ? request.color : '#0777a5',
                    actualColor = _getBrightness(targetColor, -0.2),

                    index,
                    year,
                    value,
                    values,
                    element;

                // preparing colors
                modes.push({'label': 'target', 'color': targetColor});
                modes.push({'label': 'actual', 'color': actualColor});

                // preparing timeline
                for (index = 0; index < data.length; index += 1) {
                    for (year in data[index]) {
                        if (data[index].hasOwnProperty(year)) {
                            values = [];
                            for (value in data[index][year][request.flow]) {
                                if (data[index][year][request.flow].hasOwnProperty(value)) {
                                    element = {
                                        'mode': value,
                                        'value': data[index][year][request.flow][value]
                                    };
                                    values.push(element);
                                }
                            }
                            timeline.push({
                                'title': {
                                    'target': data[index][year]['label']['target'],
                                    'actual': data[index][year]['label']['actual']
                                },
                                'label': year,
                                'values': values
                            });
                        }
                    }
                }

                return {
                    'header': header,
                    'session': session,
                    'active': active,
                    'modes': modes,
                    'timeline': timeline
                };

            };

            return {
                'success': _success,
                'error': _error,
                'parse': _parse
            };

        }());

        /**
         * Return the current status (if application is en-/disabled)
         *
         * @returns {number}
         *
         * @private
         */
        _getStatus = function () {
            return _status;
        };

        /**
         * Set status (en-disable) application
         *
         * @param status integer
         *      possible values are 0: disable, 1: enable
         *
         * @private
         */
        _setStatus = function (status) {
            _status = status;

            if (_status === 1) {
                _request();
            }
        };

        _getBrightness = function (color, luminosity) {
            // validate hex string
            color = color.replace(/[^0-9a-f]/gi, '');
            if (color.length < 6) {
                color = color[0] + color[0] + color[1] + color[1] + color[2] + color[2];
            }
            luminosity = luminosity || 0;

            // convert to decimal and change luminosity
            var newColor = "#", c, i, black = 0, white = 255;
            for (i = 0; i < 3; i += 1) {
                c = parseInt(color.substr(i * 2, 2), 16);
                c = Math.round(Math.min(Math.max(black, c + (luminosity * white)), white)).toString(16);
                newColor += ("00" + c).substr(c.length);
            }
            return newColor;
        };

        return {
            'initialize': _initialize,
            'show': _show,
            'process': _process,
            'request': _request,
            'response': _response,
            'get': {
                'status': _getStatus,
                'brightness': _getBrightness
            },
            'set': {
                'status': _setStatus
            }
        };

    }());

    /*******************************************************************************************************************
     * HTML
     *
     * @type {{render}}
     */
    _html = (function () {

        var _setup,
            _setAccountMode,
            _getAccountMode,
            _renderHeader,
            _renderLegend,
            _moveStageToX,
            _showLegend,
            _hideLegend,
            _renderXLabels,
            _renderYLabels,
            _renderTable,
            _renderInfo,
            _showNavPrevious,
            _showNavNext,
            _moveProgress = 0;

        /**
         * Creates necessary html
         *
         * @param $divContainer jQuery Object
         */
        _setup = function ($divContainer) {
            $divContainer.html('<div class="header">' +
                '   <h2>Jahresvergleich</h2>' +
                '   <div class="info" style="display: none;">' +
                '       <p></p>' +
                '   </div>' +
                '   <span class="year">&nbsp;</span>' +
                '   <span class="flow">&nbsp;</span>' +
                '   <span class="value">&nbsp;</span>' +
                '   <span class="label">&nbsp;</span>' +
                '   <a href="#" class="toggle" aria-expanded="false" aria-controls="application"><span class="sr-hint">Jahresvergleich öffnen</span></a>' +
                '</div>' +
                '<div id="application" class="application" style="display:none;" aria-expanded="false" >' +
                '   <a href="#" title="zurück" class="nav previous" aria-controls="bundeshaushalt-tabellarischer-jahresvergleich"><span class="sr-hint">Zurück</span></a>' +
                '   <div class="wrapper">' +
                '       <ul class="budget"><li></li></ul>' +
                '       <div class="account">' +
                '           <form>' +
                '               <fieldset>' +
                '                   <legend>Auswahl</legend>' +
                '                   <ul>' +
                '                       <li><label for="modeAccountTarget">' +
                '                           soll' +
                '                           <input type="radio" id="modeAccountTarget" name="mode" value="target" aria-controls="bundeshaushalt-tabellarischer-jahresvergleich">' +
                '                       </label></li>' +
                '                       <li><label for="modeAccountActual">' +
                '                           ist' +
                '                           <input type="radio" id="modeAccountActual" name="mode" value="actual" aria-controls="bundeshaushalt-tabellarischer-jahresvergleich">' +
                '                       </label></li>' +
                '                       <li><label for="modeAccountTargetActual">' +
                '                           soll/ist' +
                '                           <input type="radio" id="modeAccountTargetActual" name="mode" value="target-actual" aria-controls="bundeshaushalt-tabellarischer-jahresvergleich">' +
                '                       </label></li>' +
                '                   </ul>' +
                '               </fieldset>' +
                '           </form>' +
                '           <div class="legend" style="display:none;" aria-hidden="true">' +
                '               <ul></ul>' +
                '           </div>' +
                '       </div>' +
                '       <div class="stage" aria-hidden="true">' +
                '           <div id="data-timeline-stage"></div>' +
                '           <div class="horizontal"></div>' +
                '       </div>' +
                '   </div>' +
                '   <a href="#" title="weiter" class="nav next" aria-controls="bundeshaushalt-tabellarischer-jahresvergleich"><span class="sr-hint">Weiter</span></a>' +
                '</div>' +
                '<div class="table sr-hint">' +
                '   <h2>Tabellarischer Jahresvergleich</h2>' +
                '   <table class="jahresvergleich" id="bundeshaushalt-tabellarischer-jahresvergleich" aria-live="polite">' +
                '       <caption>Jahresvergleich ... </caption>' +
                '       <tbody></tbody>' +
                '   </table>' +
                '</div>');
        };

        /**
         * Set account mode in form
         *
         * @param mode string
         *      possible values are 'target', 'actual', 'target-actual'
         *
         * @private
         */
        _setAccountMode = function (mode) {
            var $form = $('#data-timeline').find('.account form');

            // trigger all radio elements as unchecked
            $('input[name="mode"]:checked', $form).removeAttr('checked');

            // mark radio as selected
            $('input[value="' + mode + '"]', $form).attr('checked', 'checked');

        };

        /**
         * Get account mode from form
         *
         * @returns {Array}
         *      possible values are ['target'], ['actual'], ['target','actual']
         *
         * @private
         */
        _getAccountMode = function () {
            var $form = $('#data-timeline').find('.account form'),
                values = $form.serializeArray(),
                value = '',
                retVal = [];


            if (values.length > 0) {
                value = values[0].value;
                retVal = value.split('-');
            }

            return retVal;
        };

        _renderHeader = function (year, flow, value, label) {

            var glb = jQuery.fn.ppBundeshaushalt.globals,
                $header = $('#data-timeline').find('.header'),
                $year = $('.year', $header),
                $flow = $('.flow', $header),
                $value = $('.value', $header),
                $label = $('.label', $header);

            value = glb.number_format(value, 2, ",", ".") + ' €';

            if ($year.text() !== year && year !== null) {
                // $year.hide().text(year).fadeIn();
                $year.text(year);
            }

            if ($flow.text() !== flow && flow !== null) {
                // $flow.hide().text(flow).fadeIn();
                $flow.text(flow);
            }

            if ($value.text() !== value && value !== null) {
                // $value.hide().text(value).fadeIn();
                $value.text(value);
            }

            if ($label.text() !== label && label !== null) {
                // $label.hide().text(label).fadeIn();
                $label.text(label);
            }

        };

        /**
         *
         * @param modes array
         * @returns {boolean}
         * @private
         */
        _renderLegend = function (modes) {
            var $legend = $('#data-timeline').find('.legend'),
                index,
                li = '';

            for (index = 0; index < modes.length; index += 1) {
                li += '<li><span class="color" style="background-color: ' + modes[index].color + '"></span> ' + _translate.de[modes[index].label] + '</li>';
            }

            $('ul', $legend).html(li);
        };

        _showLegend = function () {
            var $legend = $('#data-timeline').find('.legend');

            if (!$legend.is(':visible')) {
                $legend.fadeIn(125);
            }

        };

        _hideLegend = function () {
            var $legend = $('#data-timeline').find('.legend');

            if ($legend.is(':visible')) {
                $legend.hide();
            }

        };

        _renderXLabels = function (labels) {
            var indexLabels,
                table = '';

            for (indexLabels = 0; indexLabels < labels.length; indexLabels += 1) {
                table += '<td>' + labels[indexLabels] + '</td>';
            }
            $('.stage .horizontal').html('<table><tr>' + table + '</tr></table>');

        };

        /**
         * Rendering y labels
         *
         * @param labels array [
         *      { 'value': 29500000, 'yPos': 199 },
         *      { 'value': 180000,   'yPos': 114 },
         *      ...
         * ]
         *
         * @private
         */
        _renderYLabels = function (labels) {
            var glb = jQuery.fn.ppBundeshaushalt.globals,
                index,
                temporaryValue,
                temporaryString = '',
                temporaryDecimal = 0,

                decimalSeperator = 0,
                $yLabels = $('#data-timeline').find('.budget');

            $yLabels.html('');

            for (index = 0; index < labels.length; index += 1) {
                temporaryValue = _bargraph.scope.get.shortenValue(labels[index].value);
                temporaryString = String(temporaryValue['value']);
                temporaryDecimal = temporaryString.indexOf(".");

                if (temporaryDecimal > 0) {
                    temporaryDecimal = temporaryString.length - 1 - temporaryDecimal;
                }

                decimalSeperator = temporaryDecimal > decimalSeperator ? temporaryDecimal : decimalSeperator;

                labels[index].short = {
                    'value': temporaryValue['value'],
                    'unit': temporaryValue['unit']
                };
            }


            for (index = 0; index < labels.length; index += 1) {
                $yLabels.append('<li style="top:' + labels[index].yPos + 'px;">' +
                    glb.number_format(labels[index].short.value, decimalSeperator, ",", ".") + ' ' +
                    labels[index].short.unit +
                    ' €</li>');
            }


        };

        _moveStageToX = function (xPos, diagramWidth, animate) {
            var $stage = $('#data-timeline').find('.stage'),
                stageWidth = $stage.width(),
                stageCenter = parseInt(stageWidth / 2, 10);


            animate = animate || false;

            if (_moveProgress === 0) {
                _moveProgress = 1;
                if (animate) {
                    $stage.animate({
                        scrollLeft: xPos - stageCenter
                    }, 125, function () {
                        _moveProgress = 0;
                    });
                } else {
                    $stage.scrollLeft(xPos - stageCenter);
                    _moveProgress = 0;
                }
            }

        };

        _showNavPrevious = function (visibility) {
            var $nav = $('.nav.previous');

            if (visibility) {
                $nav.removeClass('disabled');
            } else {
                $nav.addClass('disabled');
            }
        };

        _showNavNext = function (visibility) {
            var $nav = $('.nav.next');

            if (visibility) {
                $nav.removeClass('disabled');
            } else {
                $nav.addClass('disabled');
            }
        };

        _renderTable = function (label, timeline) {
            var index,
                $table = $('#data-timeline').find('table.jahresvergleich'),
                glb = jQuery.fn.ppBundeshaushalt.globals,
                trHeader = '<th></th>',
                trSoll = '<th>Soll</th>',
                trIst = '<th>Ist</th>',
                values = [],
                account = _getAccountMode(),
                getValues = function (val) {
                    var idx,
                        result = {};
                    for (idx = 0; idx < val.length; idx += 1) {
                        result[val[idx].mode] = val[idx].value;
                    }
                    return result;
                };

            account = account.toString();

            for (index = 0; index < timeline.length; index += 1) {
                values = getValues(timeline[index].values);
                trHeader += '<th>' + timeline[index].label + '</th>';
                trSoll += '<td>' + (values.target !== null ? glb.number_format(values.target, 2, ",", ".") + ' €' : '') + '</td>';
                trIst += '<td>' + (values.actual !== null ? glb.number_format(values.actual, 2, ",", ".") + ' €' : '') + '</td>';
            }

            $('caption', $table).text(label);
            $('tr', $table).remove();
            $('tbody', $table).append('<tr>' + trHeader + '</tr>');

            if (account.indexOf('target') >= 0) {
                $('tbody', $table).append('<tr>' + trSoll + '</tr>');
            }

            if (account.indexOf('actual') >= 0) {
                $('tbody', $table).append('<tr>' + trIst + '</tr>');
            }

        };

        _renderInfo = function (year) {
            var html = '<strong>Hinweis:</strong> Zu diesem Zeitpunkt sind noch <strong>keine</strong> Istwerte für das Jahr ' + year + ' vorhanden',
                $div = $('#data-timeline').find('.info');

            if ( typeof year === 'undefined' ) {
                if ($div.is(":visible")) {
                    $div.fadeOut(250);
                }
            } else {
                $div.hide();
                $('p', $div).html(html);
                $div.fadeIn(250);
            }
        }

        return {
            'setup': _setup,
            'set': {
                'mode': _setAccountMode
            },
            'get': {
                'mode': _getAccountMode
            },
            'show': {
                'legend': _showLegend,
                'nav': {
                    'previous': _showNavPrevious,
                    'next': _showNavNext
                }
            },
            'hide': {
                'legend': _hideLegend
            },
            'move': {
                'stage': {
                    'x': _moveStageToX
                }
            },
            'render': {
                'header': _renderHeader,
                'legend': _renderLegend,
                'xLabels': _renderXLabels,
                'yLabels': _renderYLabels,
                'table': _renderTable,
                'info': _renderInfo
            }
        };

    }());

    /*******************************************************************************************************************
     * EVENTS
     *
     * @type {{render}}
     */
    _events = (function () {

        var _initialize,
            _resize,
            _toggle,
            _nav,
            _mode,
            _layout = '',
            _$timeline,
            _$timelineButton;

        _initialize = function () {
            _$timeline = $('#data-timeline');
            _$timelineButton = $('#data-timeline a.toggle');

            // bind on resize
            window.onresize = _events.resize;

            // bind event on "show" switch
            $('.header a', _$timeline).click(_events.toggle);

            // bind event on navigation
            $('.nav', _$timeline).click(_events.nav);

            // bind event on "mode" switch
            $('.account input', _$timeline).click(_events.mode);

        };

        _resize = function () {
            if (_plugin.get.status() === 0) {
                return true;
            }

            var layout = $(document).width() < 640 ? 'small' : 'large',
                current = _bargraph.move.get();

            if (_layout !== layout) {
                // _plugin.set.status(1);
                _bargraph.update();
                _layout = layout;
            } else if (current) {
                _html.move.stage.x(
                    parseInt(current.active.x + (current.active.width / 2), 10),
                    current.diagram.width,
                    false
                );
            }

        };

        _toggle = function () {
            if (_$timeline.height() < 50) {
                _plugin.set.status(1);

                $('.application', _$timeline).show().attr('aria-expanded', 'true');
                _$timeline.animate({height: _$timeline.get(0).scrollHeight}, 500, function () {
                    _$timeline.css('height', 'auto');
                });
                _$timeline.removeClass('closed');
                _$timeline.addClass('opened');
                _$timelineButton.attr('aria-expanded', 'true');

            } else {
                _plugin.set.status(0);
                _$timeline.animate({height: "33px"}, 500, function () {
                    // hide application
                    $('.application', _$timeline).hide().attr('aria-expanded', 'false');
                    // remove account setting
                    _html.set.mode([]);
                });
                _$timeline.removeClass('opened');
                _$timeline.addClass('closed');
                _$timelineButton.attr('aria-expanded', 'false');
            }

            return false;
        };

        _nav = function (eventData) {
            eventData.preventDefault();

            if ($(this).hasClass('disabled')) {
                return false;
            }

            if ($(this).hasClass('previous')) {
                _bargraph.move.previous();
            } else if ($(this).hasClass('next')) {
                _bargraph.move.next();
            }
        };

        _mode = function (eventData) {
            var modes = _html.get.mode(),
                current = _bargraph.move.get();

            if (modes.length > 1) {
                _html.show.legend();
            } else {
                _html.hide.legend();
            }

            _plugin.set.status(1);

            _bargraph.graph.activate.byId(current.id);

        };

        return {
            'initialize': _initialize,
            'resize': _resize,
            'toggle': _toggle,
            'nav': _nav,
            'mode': _mode
        };
    }());

    /*******************************************************************************************************************
     * BAR GRAPH
     *
     * @type {{render}}
     */
    _bargraph = (function () {

        var _paper = null,              // global paper object

            _layout = 'large',          // current used layout
                                        //      possible values are 'large' and 'small'

            _breakpoint = 640,          // defines layout switch in pixel
                                        //      layout 'small' will be  < _breakpoint,
                                        //      layout 'large' will be => _breakpoint

            _sizes = {                  // defines unit and bar sizes in different layouts
                'large': {
                    'unit': {'width': 70, 'margin': 27},
                    'bar': {'width': 30, 'space': 10}
                },
                'small': {
                    'unit': {'width': 50, 'margin': 14},
                    'bar': {'width': 20, 'space': 10}
                }
            },

            _grid = {                   // cache for paper elements
                'xLines': [],           // they are needed to get controll of elements like lines or bars after rendering
                'yLines': [],
                'bars': [],
                'mark': []             // used for mark legislaturperiode
            },

            _budgetUnits = {    // Budget units for y-labels
                '3': 'Tsd.',
                '6': 'Mio.',
                '9': 'Mrd.'
            },

            _paperHeight = 205,
            _obj = null,

            _render,
            _update,
            _click,
            _move,
            _lines,
            _graph,
            _mark,
            _scope;

        /**
         * Render graph by object
         * callable via: jQuery.fn.ppBundeshaushalt.timeline.render(obj);
         *
         * @param stageID, string of element ID
         *
         * @param obj = {
         *          session: [ 2002, 2005, 2009, 2013 ],
         *          active : {
         *              label: '2013',
         *              value: 'target'
         *          }
         *          modes: [
         *              { label: 'target', color: '#990000' },
         *              { label: 'actual', color: '#009900' },
         *              ...
         *          ]
         *          timeline: [
         *              { label: '2013', values: [{ mode: 'target', value : 89712527 }, { mode: 'actual', value : 102873 }, ...  }] },
         *              { label: '2014', values: [{ 'target' : 89712527 }, { 'actual' : 102873 }, ...  }] },
         *              ...
         *          ]
         *       }
         *
         * @param cbXLabels callback for x labels
         *
         * @param cbYLabels callback for y labels
         *
         * @returns {boolean}
         */
        _render = function (stageID, obj, cbXLabels, cbYLabels) {
            var volume,
                width;

            // cache object
            _obj = obj;

            _layout = $(document).width() < _breakpoint ? 'small' : 'large';

            width = (obj.timeline.length * _sizes[_layout].unit.width) +      // bars
                (obj.timeline.length * 2 * _sizes[_layout].unit.margin) +     // margins
                (obj.timeline.length + 1);                                    // lines

            if (_paper === null) {
                _paper = new Raphael(stageID, width, _paperHeight);
                _paper.customAttributes.cstmTitle = function () {
                };
                _paper.customAttributes.cstmValue = function () {
                };
                _paper.customAttributes.cstmYear = function () {
                };
            }

            $('.stage > div').width(width);
            _paper.setSize(width, _paperHeight);

            // get volume
            volume = _scope.get.volume(obj.timeline, _html.get.mode());

            // initialize scope
            _scope.initialize(volume.min !== 0 ? volume.min - 1 : volume.min, volume.max);

            _lines.x(obj.timeline, _sizes[_layout], cbXLabels);
            _lines.y(cbYLabels);

            _graph.unit(obj.timeline, obj.modes, _sizes[_layout]);

            _graph.activate.byYear(obj.active.label);

        };

        _update = function () {
            var width,
                current;

            _layout = $(document).width() < _breakpoint ? 'small' : 'large';

            width = (_obj.timeline.length * _sizes[_layout].unit.width) +      // bars
                (_obj.timeline.length * 2 * _sizes[_layout].unit.margin) +     // margins
                (_obj.timeline.length + 1);                                    // lines

            // update div width
            $('.stage > div').width(width);

            // update stages
            _paper.setSize(width, _paperHeight);

            // current
            current = _move.current();

            // update lines
            _lines.x(_obj.timeline, _sizes[_layout]);
            _lines.y();

            // update bars
            _graph.unit(_obj.timeline, _obj.modes, _sizes[_layout]);

            // activate bar
            _graph.activate.byObject(_grid.bars[current]);

        };

        _lines = (function () {

            var _renderXLines,
                _renderYLines,
                _indexTimeline,
                _xPos,
                _xLabels = [],
                _cacheData = '';

            _renderXLines = function (timeline, sizes, cbXLabels) {
                var cacheIdentifier = JSON.stringify(sizes),
                    index,
                    session = _obj.session.toString();

                if (_cacheData === cacheIdentifier) {
                    return true;
                }
                _cacheData = cacheIdentifier;

                // clean mark
                _bargraph.mark.clear();

                // clean old lines
                if (_grid.yLines.length > 0) {
                    _xLabels = [];
                    for (index = _grid.yLines.length - 1; index >= 0; index -= 1) {
                        _grid.yLines[index].remove();
                        _grid.yLines.splice(index, 1);
                    }
                }

                // loop through timeline
                for (_indexTimeline = 0; _indexTimeline <= timeline.length; _indexTimeline += 1) {
                    _xPos = 1 + (_indexTimeline * (sizes.unit.width + (2 * sizes.unit.margin) + 1));
                    _grid.yLines.push(_paper.path('M' + (_xPos - 0.5) + ', ' + _paper.height + ' V' + (_paper.height - 7)).attr({
                        'stroke': '#d9d9d9',
                        'stroke-width': 1
                    }));
                    if (timeline[_indexTimeline] !== undefined) {
                        _xLabels.push(timeline[_indexTimeline].label);
                        // render mark "legislaturperiode" if label match
                        if (session.indexOf(timeline[_indexTimeline].label) >= 0) {
                            _bargraph.mark.set(_xPos);
                        }
                    }
                }

                // trigger callback if setup
                if (typeof cbXLabels === 'function') {
                    cbXLabels(_xLabels);
                }

            };

            _renderYLines = function (cbYLabels) {
                var value,
                    yPos,
                    index,
                    yLabels = [];


                // clean old lines
                if (_grid.xLines.length > 0) {
                    for (index = _grid.xLines.length - 1; index >= 0; index -= 1) {
                        _grid.xLines[index].remove();
                        _grid.xLines.splice(index, 1);
                    }
                }

                // render new xLines
                for (value = _scope.get.min(); value <= _scope.get.max(); value += _scope.get.tick()) {

                    yPos = _scope.get.y(value);

                    // render lines
                    _grid.xLines.push(_paper.path('M0, ' + yPos + ' H' + _paper.width).attr({
                        'stroke': '#d9d9d9',
                        'stroke-width': 1
                    }));

                    yLabels.push({
                        'value': value,
                        'yPos': yPos
                    });


                }

                // trigger callback if setup
                if (typeof cbYLabels === 'function') {
                    cbYLabels(yLabels);
                }

            };


            return {
                'x': _renderXLines,
                'y': _renderYLines
            };

        }());

        /***************************************************************************************************************
         * Render units and bars on paper
         *
         * @private
         */
        _graph = (function () {

            var _rCurrent = null,

                _renderUnit,
                _renderBar,

                _activateById,
                _activateByYear,
                _activateByObject,
                _activateReset;

            _renderUnit = function (timeline, modes, sizes) {
                var indexUnit,
                    indexBars,
                    xPos = 0;

                // clean old lines
                if (_grid.bars.length > 0) {
                    for (indexBars = _grid.bars.length - 1; indexBars >= 0; indexBars -= 1) {
                        _grid.bars[indexBars].remove();
                        _grid.bars.splice(indexBars, 1);
                    }
                }

                for (indexUnit = 0; indexUnit < timeline.length; indexUnit += 1) {

                    // add y-line length
                    xPos += 1;

                    // add left unit margin
                    xPos += sizes.unit.margin;

                    // define pos for unit
                    // _renderBar(xPos, unitWidth, units.units[unitIdx].values, color, modes);
                    _renderBar(timeline[indexUnit], modes, sizes, xPos);

                    // add unit width
                    xPos += sizes.unit.width;

                    // add right unit margin
                    xPos += sizes.unit.margin;

                }

            };


            _renderBar = function (unit, modes, sizes, xPos) {
                var mode = _html.get.mode(),
                    width = mode.length === 1 ? sizes.unit.width : sizes.bar.width,
                    index,
                    indexMode,
                    colorModes = {},
                    bars = {},
                    bar = null,
                    rect;

                // preparing mode array
                for (index = 0; index < modes.length; index += 1) {
                    colorModes[modes[index].label] = modes[index].color;
                }

                // preparing bars
                for (index = 0; index < unit.values.length; index += 1) {
                    bars[unit.values[index].mode] = unit.values[index].value;
                }

                // render bar
                for (indexMode = 0; indexMode < mode.length; indexMode += 1) {

                    if (typeof bars[mode[indexMode]] !== 'undefined' && bars[mode[indexMode]] !== null) {

                        // calculate rectangle coordinates
                        rect = _scope.get.rect(0, bars[mode[indexMode]]);

                        // render bars
                        bar = _paper.rect(xPos, rect.yPos, width, rect.height).attr({

                            'fill': mode.length === 1 ? colorModes.target : colorModes[mode[indexMode]],
                            'stroke': _colors.orange,
                            'stroke-width': 3,
                            'stroke-opacity': 0,

                            'cstmTitle': unit.title[mode[indexMode]],
                            'cstmYear': unit.label,
                            'cstmValue': bars[mode[indexMode]]

                        });

                        bar.id = unit.label + '-' + mode[indexMode];
                        bar.click(_click);

                        _grid.bars.push(bar);
                    }

                    xPos += sizes.bar.width + sizes.bar.space;
                }
            };

            _activateById = function (id) {

                var index,
                    idValues = id.split("-"),
                    object = null,
                    alternertiveYear = null,
                    alternertiveGlobal = null;

                if (_grid.bars.length > 0) {
                    for (index = 0; index < _grid.bars.length; index += 1) {
                        if (_grid.bars[index].id === id && _grid.bars[index].attr('cstmValue') !== null) {
                            object = _grid.bars[index];
                        }
                        if (_grid.bars[index].attr('cstmValue') !== null) {

                            if (idValues[0] === _grid.bars[index].attr('cstmYear')) {
                                alternertiveYear = _grid.bars[index];
                            }
                            alternertiveGlobal = _grid.bars[index];
                        }
                    }
                }

                if (object !== null) {
                    _activateByObject(object);
                } else {
                    if (alternertiveYear !== null) {
                        _activateByObject(alternertiveYear);
                    } else {
                        _html.render.info(idValues[0]);
                        _activateByObject(alternertiveGlobal);
                    }
                }

            };

            _activateByYear = function (year) {
                var index,
                    account = _html.get.mode(),
                    object = null,
                    alternertive = null;

                if (_grid.bars.length > 0) {
                    for (index = 0; index < _grid.bars.length; index += 1) {
                        if (_grid.bars[index].id === year + '-' + account[0] && _grid.bars[index].attr('cstmValue') !== null) {
                            object = _grid.bars[index];
                        }
                        if (_grid.bars[index].attr('cstmValue') !== null) {
                            alternertive = _grid.bars[index];
                        }
                    }
                }

                if (object !== null) {
                    _activateByObject(object);
                } else {
                    _activateByObject(alternertive);
                }

            };

            _activateByObject = function (raphaelObject) {
                // hide
                if (_rCurrent !== null) {
                    _rCurrent.attr({'stroke-opacity': 0});
                }

                // show
                raphaelObject.attr({'stroke-opacity': 1});
                _bargraph.move.check();

                // move stage
                _html.move.stage.x(parseInt(raphaelObject.attr('x') + (raphaelObject.attr('width') / 2), 10), _paper.width);

                // set header
                _html.render.header(raphaelObject.attr('cstmYear'), null, raphaelObject.attr('cstmValue'), raphaelObject.attr('cstmTitle'));

                // set current
                _rCurrent = raphaelObject;

            };

            _activateReset = function () {
                // hide current
                if (_rCurrent !== null) {
                    _rCurrent.animate({
                        'stroke-opacity': 0
                    }, _animation.duration);
                }
                _rCurrent = null;
            };

            return {
                'unit': _renderUnit,
                'bar': _renderBar,
                'activate': {
                    'byId': _activateById,
                    'byYear': _activateByYear,
                    'byObject': _activateByObject,
                    'reset': _activateReset
                }
            };

        }());


        _mark = (function () {

            var _setMarker,
                _clearMarker;

            /***********************************************************************************************************
             * Create a mark at position X
             *
             * @param xPos integer
             *
             * @private
             */
            _setMarker = function (xPos) {
                // Legislaturperiode
                var text,
                    lineTop,
                    lineBot;

                xPos -= 1;

                text = _paper.text(xPos - 9, 100, "Ende der Legislaturperiode");
                text.attr({
                    'transform': "r" + 270,
                    'fill': '#C85000',
                    "font-size": 12,
                    "font-family": "BundesSans, Arial, Helvetica, sans-serif"
                });

                lineTop = _paper.path('M' + (xPos + 0.5) + ', ' + 200 + ' V' + 0).attr({
                    'stroke': '#C85000',
                    'stroke-dasharray': '. ',
                    'stroke-width': 1
                });

                _grid.mark.push(text);
                _grid.mark.push(lineTop);
                _grid.mark.push(lineBot);
            };

            /***********************************************************************************************************
             * Delete all marker on stage
             *
             * @private
             */
            _clearMarker = function () {
                var index;
                if (_grid.mark.length > 0) {
                    for (index = _grid.mark.length - 1; index >= 0; index -= 1) {
                        _grid.mark[index].remove();
                        _grid.mark.splice(index, 1);
                    }
                }
            };

            return {
                'set': _setMarker,
                'clear': _clearMarker
            };
        }());


        _click = function () {
            _graph.activate.byObject(this);
        };

        _move = (function () {

            var _current,
                _check,
                _previous,
                _next,
                _get;

            _current = function () {
                var index,
                    current = null;

                if (_grid.bars.length > 0) {
                    for (index = 0; index < _grid.bars.length; index += 1) {
                        if (_grid.bars[index].attr('stroke-opacity') === 1) {
                            current = index;
                        }
                    }
                }

                return current !== null ? current : false;
            };

            _check = function () {
                var current = _current();

                _html.show.nav.previous(current > 0);
                _html.show.nav.next(current < (_grid.bars.length - 1));

            };

            _previous = function () {
                var current = _current();

                if (current !== false) {
                    if (current > 0 && typeof _grid.bars[current - 1] !== 'undefined') {
                        _graph.activate.byObject(_grid.bars[current - 1]);
                    }
                }
            };

            _next = function () {
                var current = _current();

                if (current !== false) {
                    if (current < (_grid.bars.length - 1) && typeof _grid.bars[current + 1] !== 'undefined') {

                        _graph.activate.byObject(_grid.bars[current + 1]);

                    }
                }
            };

            _get = function () {
                var current = _grid.bars[_current()],
                    retval = false;

                if (typeof current !== 'undefined') {
                    retval = {
                        'id': current.id,
                        'diagram': {
                            'width': _paper.width,
                            'height': _paper.height
                        },
                        'active': {
                            'x': current.attr('x'),
                            'width': current.attr('width')
                        }
                    };
                }

                return retval;

            };

            return {
                'current': _current,
                'check': _check,
                'previous': _previous,
                'next': _next,
                'get': _get
            };

        }());

        /**
         * This module was designed for calculating the y grid
         *
         * based on:
         *   http://stackoverflow.com/questions/8506881/nice-label-algorithm-for-charts-with-minimum-ticks/16363437#16363437
         * other solution:
         *   http://stackoverflow.com/questions/361681/algorithm-for-nice-grid-line-intervals-on-a-graph
         *   https://gist.github.com/gre/1987311
         *
         * @type {{initialize, getMin, getMax, getRange, getVolume, getTicks, getPercent}}
         * @private
         */
        _scope = (function () {

            var _minPoint = 0.0,
                _maxPoint = 0.0,
                _maxTicks = 5,
                _ticks = 0,
                _tickSpacing = 0.0,
                _range = 0.0,
                _niceMin = 0.0,
                _niceMax = 0.0,
                _exponent = 0,

            // private methods
                _initialize,
                _getVolume,
                _getNiceMinimum,
                _getNiceMaximum,
                _getTickSpace,
                _getTicks,
                _getRange,
                _getExponent,
                _getYPos,
                _getBasedNumber,
                _getShortenValue,
                _getRectangle;

            Math.log10 = function (n) {
                return (Math.log(n)) / (Math.log(10));
            };

            /***********************************************************************************************************
             * Initialize scope object by a minimum and maximum value
             *
             * @param minimum
             * @param maximum
             * @returns {number}
             * @private
             */
            _initialize = function (minimum, maximum) {
                _minPoint = minimum;
                _maxPoint = maximum;

                _range = _scope.get.basedNumber(_maxPoint - _minPoint);

                _tickSpacing = _scope.get.basedNumber(_range / (_maxTicks - 1));

                _niceMin = Math.floor(_minPoint / _tickSpacing) * _tickSpacing;
                _niceMax = Math.ceil(_maxPoint / _tickSpacing) * _tickSpacing;

                // 0.01 cause troubles in float calculations
                _exponent = Math.floor(Math.log10(Math.abs(_niceMax) > Math.abs(_niceMin) ? Math.abs(_niceMax) : Math.abs(_niceMin)) + 0.01);

                _range = _niceMax - _niceMin;
                _ticks = _range / _tickSpacing;
            };

            /***********************************************************************************************************
             * get volume from object
             *
             * this function should get the minimum and maximum values of "timeline" depending on "modes"
             *
             * @param timeline array of current values [
             *     { label: '2013', values: [{ 'target' : 89712527 }, { 'actual' : 102873 }, ...  }] },
             *     { label: '2014', values: [{ 'target' : 89712527 }, { 'actual' : 102873 }, ...  }] },
             *     ...
             * ]
             *
             * @param modes array of used modes [
             *     { label: 'target', color: '#990000' },
             *     { label: 'actual', color: '#009900' },
             *     ...
             * ]
             *
             * @returns {{
             *     min: number,
             *     max: number
             * }}
             *
             * @private
             */
            _getVolume = function (timeline, modes) {
                var indexTimeline,
                    indexYear,
                    year,
                    value,

                    minValue = null,
                    maxValue = null;

                // preparing timeline
                for (indexTimeline = 0; indexTimeline < timeline.length; indexTimeline += 1) {
                    year = timeline[indexTimeline];
                    for (indexYear = 0; indexYear < year.values.length; indexYear += 1) {
                        value = year.values[indexYear];

                        if (modes.indexOf(value.mode) !== -1 && value.value !== null) {
                            if (minValue === null) {
                                minValue = value.value;
                            } else {
                                minValue = value.value < minValue ? value.value : minValue;
                            }
                            if (maxValue === null) {
                                maxValue = value.value;
                            } else {
                                maxValue = value.value > maxValue ? value.value : maxValue;
                            }
                        }
                    }
                }

                if (minValue === maxValue) {

                    if (minValue > 0) {
                        minValue = 0;

                    } else if (maxValue < 0) {
                        maxValue = 0;

                    } else {
                        minValue = 0;
                        maxValue = 100000;
                    }
                }

                return {
                    'min': minValue,
                    'max': maxValue
                };
            };

            _getNiceMinimum = function () {
                return _niceMin;
            };

            _getNiceMaximum = function () {
                return _niceMax;
            };

            _getTickSpace = function () {
                return _tickSpacing;
            };

            _getTicks = function () {
                return _ticks;
            };

            _getRange = function () {
                return _range;
            };

            _getExponent = function () {
                return _exponent;
            };

            _getYPos = function (value) {
                var height = _paper.height - 7,
                    percent;

                value = value < _niceMin ? _niceMin : value;
                value = value > _niceMax ? _niceMax : value;

                value = _niceMax - value;

                percent = (100 / _range * value);

                return parseInt(height * percent / 100, 10) + 0.5;
            };

            /***********************************************************************************************************
             * get based number
             *
             * this function returns the next valid (based) value 1, 2, 5, 10 based on the exponent
             * examples:
             *     7   => 10  (1,   2,   5,   10)
             *     12  => 20  (10,  20,  50,  100)
             *     329 => 500 (100, 200, 500, 1000)
             *
             * @param range number
             *
             * @returns {number}
             * @private
             */
            _getBasedNumber = function (range) {
                var exponent,
                    fraction;

                // 0.01 cause troubles in float calculations
                exponent = Math.floor(Math.log10(range) + 0.01);
                fraction = range / Math.pow(10, exponent);

                if (fraction <= 1) {
                    fraction = 1;
                } else if (fraction <= 2) {
                    fraction = 2;
                } else if (fraction <= 5) {
                    fraction = 5;
                } else {
                    fraction = 10;
                }

                return fraction * Math.pow(10, exponent);
            };

            _getShortenValue = function (value) {
                var glb = jQuery.fn.ppBundeshaushalt.globals;
                value = value / Math.pow(10, parseInt(_exponent / 3, 10) * 3);

                return {
                    'value': value,
                    'unit': _budgetUnits[parseInt(_exponent / 3, 10) * 3]
                };
            };

            _getRectangle = function (from, to) {
                var yFrom = _getYPos(from),
                    yTo = _getYPos(to),
                    yPos,
                    height;

                if (yFrom >= yTo) {
                    height = yFrom - yTo;
                    yPos = yTo;

                } else if (yFrom < yTo) {
                    height = yTo - yFrom;
                    yPos = yFrom;
                }

                return {
                    'yPos': yPos,
                    'height': height
                };
            };


            return {
                'initialize': _initialize,
                'get': {
                    'volume': _getVolume,
                    'min': _getNiceMinimum,
                    'max': _getNiceMaximum,
                    'tick': _getTickSpace,
                    'ticks': _getTicks,
                    'range': _getRange,
                    'exponent': _getExponent,
                    'y': _getYPos,
                    'basedNumber': _getBasedNumber,
                    'shortenValue': _getShortenValue,
                    'rect': _getRectangle
                }
            };

        }());

        return {
            'render': _render,
            'update': _update,
            'click': _click,
            'move': _move,
            'lines': _lines,
            'graph': _graph,
            'mark': _mark,
            'scope': _scope
        };

    }());

    /*******************************************************************************************************************
     * Providing API
     */
    return {
        init: _plugin.initialize,
        show: _plugin.show,
        plugin: _plugin,
        html: _html,
        bargraph: _bargraph
    };

}(window, jQuery, Raphael));
