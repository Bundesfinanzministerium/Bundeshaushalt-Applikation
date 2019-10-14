(function ($) {

    if (!$.ppBundeshaushalt) {
        $.ppBundeshaushalt = new Object();
    }

    $.ppBundeshaushalt.Main = function (el, options) {

        // To avoid scope issues, use 'base' instead of 'this'
        // to reference this class from internal events and functions.
        var base = this;

        // Access to jQuery and DOM versions of element
        base.$el = $(el);
        base.el = el;

        // Add a reverse reference to the DOM object
        if (typeof(txPpBundeshaushaltRev) != 'undefined')
            if (txPpBundeshaushaltRev) base.$el.data("ppBundeshaushalt.Main", base);

        base.init = function () {

            base.options = $.extend({}, $.ppBundeshaushalt.Main.defaultOptions, options);

            if (!base.param)
                base.param = new Object();

            base.param.defaults = null;
            base.param.avail = null;

            base.param.startByApplication = true;         // plugin will start by application (will change by plugins)
            base.param.requestInProgress = false;        // preventing double-clicks
            base.param.levelCur = 0;
            base.param.levelMax = 0;
            base.param.levelTitle = 0;            // flag if current level is title
            base.param.colors = ["#333", "#a3d148", "#d1d148", "#ffff48", "#ff1a1a", "#ff1a76", "#d14876", "#a34876", "#a348a3", "#7648a3", "#484876", "#4848a3", "#1aa3ff", "#40ddff", "#40fff8", "#40ff83", "#83ff40", "#a3d148", "#d1d148", "#ffff48", "#ff1a1a", "#ff1a76", "#d14876", "#a34876", "#a348a3", "#7648a3", "#484876", "#4848a3", "#1aa3ff", "#40ddff", "#40fff8", "#40ff83", "#83ff40"];
            base.param.dataActiveRoot = null;
            base.param.init = 1;

            // global paper object
            base.param.paper = null;

            // stores important canvas informations
            base.param.canvas = {
                'c': {'x': 0, 'y': 0},     // canvas information: center of circle(s) X and Y (pixel/integer)
                'r': 0                         // canvas information: Radius (pixel/integer)
            };

            // stores the whole information in levels and address
            //      dataObject[unit][level][address] = {}
            base.param.dataObject = {};

            // jQuery re-sort the dataObject, so it is needed to create an indexed array in right order
            //      dataSorted[level][index][address]
            base.param.dataSorted = new Array();

            // persist segments and addresses of active elements per level
            //      activeElements[level] = {'a':'adresse','s':'segment'}
            //      this is needed, cause it's possible that an active element has an 'others' or NO segment
            base.param.dataActive = new Array();

            // current address with unit and account information
            //      dataCurrent = {'a':'account','u':'unit','d':'address'}
            base.param.dataCurrent = {
                'y': base.options.year,
                'q': base.options.quota,
                'a': base.options.account,
                'u': base.options.unit,
                'd': base.options.address
            };

            // related title information
            //      dataRelated =
            base.param.dataRelated = {};

            // standard informations per level
            //      dataLevelInfo[level] = {'a':'adresse','t';'address','l':'label','v':'value'}
            base.param.dataLevelInfo = {};

            // persist attributes and handler of each segment
            //      circleSegment[level][address]
            base.param.circleSegment = {};

            // persist white center circle for re-render level
            // ehemals centerCircleObj
            base.param.whiteCircle = null;

            // persist attributes and handler of each segment
            //      circleSegment[level][address]
            base.param.blankSegment = {};

            // requesting global configuration
            // redirect to 'base.startApplication()' after successful request
            base.requestConfiguration();

        };


        // getting global configuration informations from host #########################################################
        base.requestConfiguration = function () {

            var url = "";
            url += base.options.service.protocol;
            url += base.options.service.host + '/';
            url += base.options.service.path;
            url += 'service.json';

            $.ajax({

                url: url,
                dataType: "json",

                success: function (data, textStatus, jqXHR) {
                    base.param.defaults = data.defaults;
                    base.param.avail = data.avail;
                    base.startApplication();
                },

                error: function () {
                    jQuery.fn.ppBundeshaushalt.messaging.error(
                        "Problem beim Verbindungsafbau",
                        "Leider ist ein Fehler bei der Verbindung mit:\n" + url + " aufgetreten"
                    );
                }

            })

        };


        // start main application after getting global configuration ###################################################
        base.startApplication = function () {

            // initialize plugins ########################################
            base.initializePlugins();

            // paint canvas ##############################################
            base.paintCanvas();

            // request address if no 'address-plugin' was set ############
            if (!jQuery.fn.ppBundeshaushalt.address) {
                base.requestAddress(base.param.dataCurrent, 'Einstiegsseite');
            } else {
                base.requestJSON(base.param.dataCurrent, 'Einstiegsseite');
            }

        };


        // initialize and paint canvas object ##########################################################################
        base.paintCanvas = function () {

            // initialize ################################################
            var canvasWidth = 0;
            var canvasHeight = 0;
            var canvasScale = 0;
            var canvasDetail = null;
            var eDivApplication = $(base.options.eDivApplication, base.$el);

            // preparing canvas size ##################################################################
            canvasWidth = typeof(base.options.width) == 'number' ? parseInt(base.options.width) : null;
            canvasHeight = typeof(base.options.height) == 'number' ? parseInt(base.options.height) : null;
            if (base.options.width == 'auto') canvasWidth = parseInt($(eDivApplication).width());
            if (base.options.height == 'auto') canvasHeight = parseInt($(eDivApplication).height());
            if (base.options.width == 'copy') canvasWidth = canvasHeight;
            if (base.options.height == 'copy') canvasHeight = canvasWidth;

            base.param.canvas.c.x = parseInt(canvasWidth / 2);
            base.param.canvas.c.y = parseInt(canvasHeight / 2);
            base.param.canvas.r = parseInt(canvasWidth < canvasHeight ? canvasWidth / 2 : canvasHeight / 2);
            base.param.canvas.r = base.param.canvas.r / 100 * (100 - base.options.canvasHoverScale)

            $(eDivApplication).height(canvasHeight);

            canvasScale = ((100 * canvasWidth) / 460) / 100;
            canvasDetail = '<div class="details" style="';
            canvasDetail += 'top:' + ((canvasHeight * 0.215) + 10) + 'px; ';
            canvasDetail += 'left:' + (canvasWidth * 0.215) + 'px; ';
            canvasDetail += 'width:' + (canvasWidth * 0.57) + 'px; ';
            canvasDetail += 'height:0px; ';
            canvasDetail += 'font-size:' + canvasScale + 'em; ">';
            canvasDetail += '<div class="loadSpinner">';
            canvasDetail += '<img src="/typo3conf/ext/bmf_budget_application/Resources/Public/Images/ajax-loader.gif" style="display:none;">';
            canvasDetail += '</div>';
            canvasDetail += '<h2>Allgemeine Übersicht</h2>';
            canvasDetail += '<p class="budget"></p>';
            canvasDetail += '<p class="title"></p>';
            canvasDetail += '<p class="pdf"></p>';
            canvasDetail += '</div>';
            $(eDivApplication).append(canvasDetail);

            // initialize paper object #####################################################################################
            base.param.paper = Raphael('unit-detail', canvasWidth, canvasHeight);
            base.param.paper.customAttributes.chtLvl = function () {
            };
            base.param.paper.customAttributes.chtAdr = function () {
            };
            base.param.paper.customAttributes.chtTyp = function () {
            };

        };


        // request by click events #####################################################################################
        base.requestAddress = function (current, piwikApplikation) {

            if (base.param.requestInProgress) {
                return false;
            }

            if (jQuery.fn.ppBundeshaushalt.address) {
                jQuery.fn.ppBundeshaushalt.address.update(current);
            } else {
                base.requestJSON(current);
            }

            // piwiktracker #############################################################################
            if (typeof(piwikApplikation) != 'undefined')
                jQuery.fn.ppBundeshaushalt.globals.piwik(base.options.embed, piwikApplikation, current);

        };


        // request by browser events or address plugin (update) ########################################################
        base.requestJSON = function (current, piwikApplikation) {

            if (current.a == '' || current.u == '')
                return false;


            // initialize ################################################
            var level = base.getLevel(current);

            base.param.requestInProgress = true;

            if (current.a == 'blank' && current.u == 'blank') {

                base.param.requestInProgress = false;   // enable requests
                base.param.paper.clear();               // clear paper
                base.resetApp();                        // reset application
                base.updatePlugins();                   // update plugin

            } else if (base.param.init == 1) {

                base.param.init = 0;                    // update init flag
                base.processRequest(current);         // process request

            } else if (typeof(base.param.dataObject[current.y]) == 'undefined') {

                base.resetApp();                        // reset paper for complete replacement
                base.processRequest(current);         // process request

            } else if (typeof(base.param.dataObject[current.y][current.q]) == 'undefined') {

                base.resetApp();                        // reset paper for complete replacement
                base.processRequest(current);         // process request

            } else if (typeof(base.param.dataObject[current.y][current.q][current.a]) == 'undefined') {

                base.resetApp();                        // reset paper for complete replacement
                base.processRequest(current);         // process request

            } else if (typeof(base.param.dataObject[current.y][current.q][current.a][current.u]) == 'undefined') {

                base.resetApp();                        // reset paper for complete replacement
                base.processRequest(current);         // process request

            } else if (base.circleCheckAddress(current, level) && level < base.param.levelCur) {

                base.resetToLevel(level);

            } else {

                base.processRequest(current);         // process request

            }

            // piwiktracker #############################################################################
            if (typeof(piwikApplikation) != 'undefined')
                jQuery.fn.ppBundeshaushalt.globals.piwik(base.options.embed, piwikApplikation, current);

        };


        base.processRequest = function (current) {

            base.loadSpinner(1);

            var url = "";
            url += base.options.service.protocol;
            url += base.options.service.host + '/';
            url += base.options.service.path;
            url += current.y + '/';
            url += current.q + '/';
            url += base.mapAccount(current.a) + '/';
            url += base.mapUnit(current.u) + '/';
            url += current.d + (current.d != '' ? '/' : '');

            $.ajax({
                url: url,
                dataType: "json",
                success: base.requestSuccess,
                error: function () {
                    base.requestError(current)
                    jQuery.fn.ppBundeshaushalt.messaging.error(
                        "Problem beim Verbindungsafbau",
                        "Leider ist ein Fehler bei der Verbindung mit:\n" + url + " aufgetreten"
                    );
                }
            })

        };


        base.mapAccount = function (account) {
            if (account == 'e') return 'einnahmen';
            if (account == 'a') return 'ausgaben';
            return false;
        };


        base.mapUnit = function (unit) {
            if (unit == 'agency')   return 'einzelplan';
            if (unit == 'group')    return 'gruppe';
            if (unit == 'function') return 'funktion';
            return false;
        };


        base.requestSuccess = function (data, textStatus, jqXHR) {
            base.loadSpinner(0);
            base.processJSON(data);
        };


        base.requestError = function (current) {

            base.param.requestInProgress = false;

            if (jQuery.inArray(current.q, base.param.avail.relYearQuota[current.y]) >= 0) {
                base.requestAddress({
                    'y': current.y,
                    'q': current.q,
                    'a': current.a,
                    'u': current.u,
                    'd': ''
                }, jQuery.fn.ppBundeshaushalt.breadcrumb.piwikApp)
            } else {
                base.requestAddress({
                    'y': current.y,
                    'q': base.param.avail.relYearQuota[current.y][0],
                    'a': current.a,
                    'u': current.u,
                    'd': ''
                }, jQuery.fn.ppBundeshaushalt.breadcrumb.piwikApp)
            }
        };


        // process JSON Object #############################################################################################
        base.processJSON = function (JSONdata) {

            var glb = jQuery.fn.ppBundeshaushalt.globals;

            base.param.levelCur = typeof(JSONdata.meta.levelCur) != 'undefined' ? parseInt(JSONdata.meta.levelCur) : -1;
            base.param.levelMax = typeof(JSONdata.meta.levelMax) != 'undefined' ? parseInt(JSONdata.meta.levelMax) : -1;
            base.param.levelTitle = typeof(JSONdata.detail.typ) != 'undefined' ? (JSONdata.detail.typ == 'title' ? true : false ) : false;

            if (base.param.levelTitle)
                base.param.levelCur = base.param.levelMax + 1;

            // pixelpark 2012-10-19
            base.param.levelCur = base.param.levelCur >= 0 ? base.param.levelCur - base.options.showLevelStart : base.param.levelCur;
            base.param.levelMax = base.param.levelMax >= 0 ? base.param.levelMax - base.options.showLevelStart : base.param.levelMax;
            // ende

            // setup current values ########################################################################################
            base.param.dataCurrent = {
                'y': typeof(JSONdata.meta.year) != 'undefined' ? JSONdata.meta.year : '',
                'q': typeof(JSONdata.meta.quota) != 'undefined' ? JSONdata.meta.quota : '',
                'a': typeof(JSONdata.meta.account) != 'undefined' ? JSONdata.meta.account : 'u',
                'u': typeof(JSONdata.meta.unit) != 'undefined' ? base.options.units[JSONdata.meta.unit] : '',
                'd': typeof(JSONdata.detail.address) != 'undefined' ? JSONdata.detail.address : ''
            };

            // setup level values ##########################################################################################
            base.param.dataLevelInfo[base.param.levelCur] = {
                a: typeof(JSONdata.detail.text) != 'undefined' ? JSONdata.detail.text : '____ ___ __ - ___',
                l: typeof(JSONdata.detail.label) != 'undefined' ? JSONdata.detail.label : '',
                v: typeof(JSONdata.detail.value) != 'undefined' ? JSONdata.detail.value : ''
            };

            // if address is title setup detailed title informations #######################################################
            if (base.param.levelTitle) {
                base.param.dataRelated = JSONdata.related;
                base.param.dataLevelInfo[base.param.levelCur].titleDetail = JSONdata.detail.titleDetail;
            }

            if (base.options.showLevelStart > 0) {

                $.each(JSONdata.parents[base.options.showLevelStart], function (key, value) {
                    if (value.s == 1)
                        base.options.dataActiveRoot = value.a;
                });

                for (var i = 0; i < base.options.showLevelStart; i++) {
                    JSONdata.parents.shift();
                }

            }

            // render parent elements ####################################
            if (typeof(JSONdata.parents ) != 'undefined') {

                if (JSONdata.parents.length > 0) {
                    $.each(JSONdata.parents, function (index, $value) {
                        if (index == 0) {
                            if (base.options.showLevelStart === 0) {
                                base.setupLevelInfo($value[index], index);
                            }
                        } else {
                            base.renderCircle($value, index - 1, true);
                        }
                    });
                }
            }

            // render childs / siblings ##################################
            if (typeof(JSONdata.childs ) != 'undefined') {
                if (JSONdata.childs.length > 0)
                    base.renderCircle(JSONdata.childs, base.param.levelCur, false);
            }

            if (typeof(base.param.segmentsSet) != 'undefined')
                glb.animAddElement([{'e': base.param.segmentsSet, 'f': 'rS', 't': 'Rf'}]);

            base.centerCircleToFront();

            base.updatePlugins();

            base.metaInfoReset();

            base.param.requestInProgress = false;

            glb.animStart();

        };


        base.renderCircle = function (JSONobj, level, parent) {

            if (typeof( base.param.circleSegment[level]) != 'undefined') {

                var valid = true;
                var actAdd = '';

                if (typeof(base.param.dataObject) == 'undefined')
                    valid = false;
                else if (typeof(base.param.dataObject[base.param.dataCurrent.y]) == 'undefined')
                    valid = false;
                else if (typeof(base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q]) == 'undefined')
                    valid = false;
                else if (typeof(base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q][base.param.dataCurrent.a]) == 'undefined')
                    valid = false;
                else if (typeof(base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q][base.param.dataCurrent.a][base.param.dataCurrent.u]) == 'undefined')
                    valid = false;
                else if (typeof(base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q][base.param.dataCurrent.a][base.param.dataCurrent.u][level]) == 'undefined')
                    valid = false;
                else if (typeof(base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q][base.param.dataCurrent.a][base.param.dataCurrent.u][level][JSONobj[0].a]) == 'undefined')
                    valid = false;

                if (valid) {
                    var actOthers = false;

                    $.each(JSONobj, function (key, value) {

                        if (value.s == '1') {

                            base.param.dataActive[level] = {
                                'a': value.a,
                                's': base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q][base.param.dataCurrent.a][base.param.dataCurrent.u][level][value.a].sTyp
                            };

                            if (base.param.dataActive[level].s == 'segment') {
                                base.param.circleSegment[level][base.param.dataActive[level].a].attr({'transform': "s1.05 1.05 " + base.param.canvas.c.x + " " + base.param.canvas.c.y});
                            } else if (base.param.dataActive[level].s == 'others') {
                                actOthers = true;
                                base.param.circleSegment[level]['others'].attr({'transform': "s1.05 1.05 " + base.param.canvas.c.x + " " + base.param.canvas.c.y});
                            }

                            base.setupLevelInfo(value, level + 1);

                        } else {
                            if (base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q][base.param.dataCurrent.a][base.param.dataCurrent.u][level][value.a].sTyp == 'others' && !actOthers)
                                base.param.circleSegment[level]['others'].animate({'transform': "S1 " + base.param.canvas.c.x + " " + base.param.canvas.c.y}, (base.options.animSegmentFadeTime / 2), 'linear');
                            else if (base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q][base.param.dataCurrent.a][base.param.dataCurrent.u][level][value.a].sTyp == 'segment')
                                base.param.circleSegment[level][value.a].animate({'transform': "S1 " + base.param.canvas.c.x + " " + base.param.canvas.c.y}, (base.options.animSegmentFadeTime / 2), 'linear');
                        }

                    });

                    if (base.param.levelTitle && level == base.param.levelMax)
                        base.activeSegmentToFront(level);

                    return false;

                } else {
                    base.circleRemove(level);
                }

            }

            // initialize ##################################################################################################
            if (typeof(base.param.dataObject[base.param.dataCurrent.y]) == 'undefined')                                                                               base.param.dataObject[base.param.dataCurrent.y] = {};
            if (typeof(base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q]) == 'undefined')                                                     base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q] = {};
            if (typeof(base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q][base.param.dataCurrent.a]) == 'undefined')                           base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q][base.param.dataCurrent.a] = {};
            if (typeof(base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q][base.param.dataCurrent.a][base.param.dataCurrent.u]) == 'undefined') base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q][base.param.dataCurrent.a][base.param.dataCurrent.u] = {};

            base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q][base.param.dataCurrent.a][base.param.dataCurrent.u][level] = {};
            base.param.dataSorted[level] = new Array();

            var total = base.getTotal(JSONobj);
            var angleFrom = 0;
            var angleTo = 0;
            var colorIdx = 0;
            var segColor = '';
            var restSum = 0;
            var otherSelect = false;
            var dataArray = new Array();
            var lastAddress = '';

            if (level > 0)
                base.activeSegmentToFront(level - 1);

            $.each(JSONobj, function (key, value) {

                base.param.dataSorted[level].push(value.a);

                var percent100 = value.v > 0 ? (parseInt(value.v) / total * 100) : 0;
                var angleIter = value.v > 0 ? (value.v * 360 / total) : 0;
                var active = value.s == '1';

                base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q][base.param.dataCurrent.a][base.param.dataCurrent.u][level][value.a] = {
                    'percent': percent100,
                    'addText': value.t,
                    'label': value.l,
                    'budget': value.v,
                    'flex': value.f,
                    'color': ''
                };

                if (typeof(value.titleDetail) != 'undefined')
                    base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q][base.param.dataCurrent.a][base.param.dataCurrent.u][level][value.a]['titleDetail'] = value.titleDetail;

                if (percent100 > 1) {

                    // percent is greater 1 ##################################
                    colorIdx = colorIdx + 1;
                    segColor = base.param.colors[colorIdx];
                    angleTo = angleFrom + angleIter;
                    angleTo = angleTo > 360 ? 360 : angleTo;
                    base.segmentPrepare(level, value.a, angleFrom, angleTo, segColor, 'unit', parent, active);

                    base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q][base.param.dataCurrent.a][base.param.dataCurrent.u][level][value.a].sTyp = 'segment';
                    base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q][base.param.dataCurrent.a][base.param.dataCurrent.u][level][value.a].color = segColor;

                    if (active)
                        base.param.dataActive[level] = {'a': value.a, 's': 'segment'};

                    angleFrom += angleIter;
                    lastAddress = value.a;

                } else if (value.v > 0) {

                    // value is greater 0 ####################################
                    base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q][base.param.dataCurrent.a][base.param.dataCurrent.u][level][value.a].sTyp = 'others';

                    restSum += parseInt(value.v);

                    if (active)
                        otherSelect = true;

                    if (active)
                        base.param.dataActive[level] = {'a': value.a, 's': 'others'};

                } else {

                    // value is equal or lower than 0 ########################
                    base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q][base.param.dataCurrent.a][base.param.dataCurrent.u][level][value.a].sTyp = 'none';

                    if (active)
                        base.param.dataActive[level] = {'a': value.a, 's': 'none'};

                }

                if (active)
                    base.setupLevelInfo(value, level + 1);

            });

            if (restSum > 0) {

                var sonLabel = '';
                sonLabel = 'Weitere ';
                if (base.param.dataCurrent.u == 'agency')   sonLabel += 'Einzelpläne';
                if (base.param.dataCurrent.u == 'group')    sonLabel += 'Gruppen';
                if (base.param.dataCurrent.u == 'function') sonLabel += 'Funktionen';

                if (base.options.weiteres.html != '') {
                    sonLabel = base.options.weiteres.html;
                } else {
                    sonLabel = 'Weiteres';
                }

                if (angleFrom >= 358.5) angleFrom = 358.5;

                base.segmentPrepare (level, 'others', angleFrom, 360, base.param.colors[0], 'others', parent, otherSelect);

                base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q][base.param.dataCurrent.a][base.param.dataCurrent.u][level]['others'] = {
                    'percent': 0,
                    'addText': '____ ___ __ - ___',
                    'label': sonLabel,
                    'budget': restSum,
                    'flex': '',
                    'color': ''
                };
            }
            base.activeSegmentToFront(level);
        };


        /* SEGMENT CIRCLNCTIONS ########################################################################################
         ############################################################################################################ */
        base.segmentPrepare = function (level, address, angleFrom, angleTo, segColor, segTyp, parent, active) {

            // initialize ##################################################################################################
            var glb = jQuery.fn.ppBundeshaushalt.globals;

            if (typeof(base.param.circleSegment[level]) == 'undefined')
                base.param.circleSegment[level] = {};

            var angleOffset = 24 + (level * 45);
            var radius = base.param.canvas.r - ( (level) * base.param.canvas.r / 100 * base.options.canvasRadiusDiff );
            var hScale = (100 + base.options.canvasHoverScale) / 100;

            angleFrom += angleOffset;
            angleTo += angleOffset;

            if (angleTo - angleFrom < 360 / 100 * 99) {

                var p = base.segmentCreate(radius, angleFrom, angleTo, {
                    'fill': '' + segColor,
                    'stroke': '#fff',
                    'stroke-width': 2,
                    'fill-opacity': 0,
                    'stroke-opacity': 0,
                    'transform': active > 0 ? "s1.05 1.05 " + base.param.canvas.c.x + " " + base.param.canvas.c.y : "S1",
                    'chtLvl': level,
                    'chtAdr': address,
                    'chtTyp': segTyp
                });

            } else {

                var p = base.param.paper.circle(base.param.canvas.c.x, base.param.canvas.c.y, radius).attr({
                    'fill': '' + segColor,
                    'stroke': '#fff',
                    'stroke-width': 2,
                    'fill-opacity': 0,
                    'stroke-opacity': 0,
                    'transform': active > 0 ? "s1.05 1.05 " + base.param.canvas.c.x + " " + base.param.canvas.c.y : "S1",
                    'chtLvl': level,
                    'chtAdr': address,
                    'chtTyp': segTyp
                });

            }

            // setup eventhandler #############################################################
            if (base.options.interactive && level >= base.options.actLevelStart) {

                if (segTyp == 'others') {
                    p.click(base.segmentClickOthers);
                } else {
                    p.click(base.segmentClick);
                }

                p.mouseover(base.segmentOver);
                p.mouseout(base.segmentOut);
            }

            // update address if others is set
            address = (segTyp == 'others' ? 'others' : address);

            // register segment ###############################################################
            base.param.circleSegment[level][address] = p;

            // register animation #############################################################
            if (parent && !active) {
                base.param.circleSegment[level][address].attr({'fill-opacity': 1, 'stroke-opacity': 1});
            } else {
                glb.animAddElement([{'e': base.param.circleSegment[level][address], 'f': 'fI', 't': 'Rf'}]);
            }

        };

        base.segmentCreate = function (r, startAngle, endAngle, params) {
            rad = Math.PI / 180;
            var x1 = base.param.canvas.c.x + r * Math.cos(-startAngle * rad),
                x2 = base.param.canvas.c.x + r * Math.cos(-endAngle * rad),
                y1 = base.param.canvas.c.y + r * Math.sin(-startAngle * rad),
                y2 = base.param.canvas.c.y + r * Math.sin(-endAngle * rad);
            return base.param.paper.path(["M", base.param.canvas.c.x, base.param.canvas.c.y, "L", x1, y1, "A", r, r, 0, +(endAngle - startAngle > 180), 0, x2, y2, "z"]).attr(params);
        };


        // Navigation ######################################################################################################
        // #################################################################################################################

        base.segmentOver = function () {
            base.dataOver(this.attr('chtLvl'), this.attr('chtAdr'));
        };


        base.segmentOut = function () {
            base.dataOut(this.attr('chtLvl'), this.attr('chtAdr'));
        };


        base.segmentClick = function () {

            var address;

            if (this.attr('chtLvl') < base.param.levelCur) {

                // parent-element was clicked
                if (this.attr('chtLvl') > 0) {
                    // no root element
                    address = base.param.dataActive[this.attr('chtLvl') - 1].a;
                } else if (typeof(base.options.dataActiveRoot) == 'string') {
                    // root element, set by embed
                    address = base.options.dataActiveRoot;
                } else {
                    // root element, standard
                    address = '';
                }

            } else {

                // child-element was clicked
                address = this.attr('chtAdr');
            }

            base.requestAddress ({
                'y': base.param.dataCurrent.y,
                'q': base.param.dataCurrent.q,
                'a': base.param.dataCurrent.a,
                'u': base.param.dataCurrent.u,
                'd': address
            }, base.options.piwikApp);

        };


        base.segmentClickOthers = function () {

            if (this.attr('chtLvl') < base.param.levelCur) {

                // parent-element was clicked
                if (this.attr('chtLvl') > 0) {
                    var address = base.param.dataActive[this.attr('chtLvl') - 1].a;     // no root element

                } else if (typeof(base.options.dataActiveRoot) == 'string') {
                    var address = base.options.dataActiveRoot;                          // root element, set by embed

                } else {
                    var address = '';                                                   // root element, standard
                }

                base.requestAddress ({
                    'y': base.param.dataCurrent.a,
                    'q': base.param.dataCurrent.a,
                    'a': base.param.dataCurrent.a,
                    'u': base.param.dataCurrent.u,
                    'd': address
                }, base.options.piwikApp);

            } else {
                // child-element was clicked
                if (typeof(base.options.weiteres.func) == 'string') {

                    if (base.options.weiteres.func == 'redirect') {
                        var url = jQuery.fn.ppBundeshaushalt.globals.url(base.param.dataCurrent, base.param.dataCurrent.d);
                        fenstr = window.open('http://' + base.options.service.host + '/' + url);
                        fenstr.focus();
                    }

                } else if (typeof(base.options.weiteres.func) == 'function') {
                    base.options.weiteres.func();

                } else if (typeof($.scrollTo) != 'undefined') {
                    $.scrollTo('#bundeshaushalt-data', 500, {offset: {top: -88}});
                }
            }
        };


        base.segmentClickBlank = function () {

            var address = this.attr('chtLvl') > 0 ? base.param.dataActive[this.attr('chtLvl') - 1].a : '';

            if (this.attr('chtLvl') >= base.options.actLevelStart)
                base.requestAddress ({
                    'y': base.param.dataCurrent.y,
                    'q': base.param.dataCurrent.q,
                    'a': base.param.dataCurrent.a,
                    'u': base.param.dataCurrent.u,
                    'd': address
                }, base.options.piwikApp);

        };


        base.dataOver = function (level, address) {

            if (base.param.requestInProgress)
                return false;

            if (base.param.levelCur != level || base.param.levelTitle == 1)
                return false;

            var sTyp = base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q][base.param.dataCurrent.a][base.param.dataCurrent.u][level][address].sTyp;

            if (sTyp == 'others')
                base.param.circleSegment[level]['others'].animate({'transform': "s1.05 1.05 " + base.param.canvas.c.x + " " + base.param.canvas.c.y}, (base.options.animSegmentFadeTime / 2), 'linear');

            else if (sTyp == 'segment')
                base.param.circleSegment[level][address].animate({'transform': "s1.05 1.05 " + base.param.canvas.c.x + " " + base.param.canvas.c.y}, (base.options.animSegmentFadeTime / 2), 'linear');

            if (typeof(base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q][base.param.dataCurrent.a][base.param.dataCurrent.u][level][address]) != 'undefined') {
                base.metaInfoShow(
                    base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q][base.param.dataCurrent.a][base.param.dataCurrent.u][level][address].addText,
                    base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q][base.param.dataCurrent.a][base.param.dataCurrent.u][level][address].budget,
                    base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q][base.param.dataCurrent.a][base.param.dataCurrent.u][level][address].label,
                    '', ''
                );
            }

            $('#bundeshaushalt-data .lvl' + level + '.add' + address).addClass('hover');

            if (jQuery.fn.ppBundeshaushalt.timeline) {
                // jQuery.fn.ppBundeshaushalt.timeline.show(base.param.dataCurrent.y, base.param.dataCurrent.u, address);
            }

        };


        base.dataOut = function (level, address) {

            if (base.param.requestInProgress)
                return false;

            if (base.param.levelCur != level || base.param.levelTitle == 1)
                return false;

            var sTyp = base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q][base.param.dataCurrent.a][base.param.dataCurrent.u][level][address].sTyp;

            if (sTyp == 'others')
                base.param.circleSegment[level]['others'].animate({'transform': "S1 " + base.param.canvas.c.x + " " + base.param.canvas.c.y}, (base.options.animSegmentFadeTime / 2), 'linear');

            else if (sTyp == 'segment')
                base.param.circleSegment[level][address].animate({'transform': "S1 " + base.param.canvas.c.x + " " + base.param.canvas.c.y}, (base.options.animSegmentFadeTime / 2), 'linear');

            base.metaInfoReset();

            $('#bundeshaushalt-data tr.lvl' + level + '.add' + address).removeClass('hover');

            if (jQuery.fn.ppBundeshaushalt.timeline) {
                // jQuery.fn.ppBundeshaushalt.timeline.show(base.param.dataCurrent.y, base.param.dataCurrent.u, base.param.dataCurrent.d);
            }

        };


        /* CIRCLE FUNCTIONS ################################################################################################
         ################################################################################################################# */
        base.circleRemove = function (level) {
            var tempSet = base.param.paper.set();
            base.param.paper.forEach(function (el) {

                if (el.attrs.chtLvl == level && el.attrs.chtAdr != 'black' && el.attrs.chtAdr != 'white') {
                    tempSet.push(el);
                }

            });

            tempSet.remove();
            delete tempSet;
        };


        /* SEGMENT CIRCLE FUNCTIONS ##################################################################################################
         ########################################################################################################################### */
        base.resetSegments = function (level) {

            for (segments in base.param.circleSegment[level]) {

                base.param.circleSegment[level][segments].attr(
                    {'transform': "S1 " + base.param.canvas.c.x + " " + base.param.canvas.c.y}
                );

            }

            delete base.param.dataActive[level];

        };


        base.activeSegmentToFront = function (level) {

            if (typeof(base.param.dataActive[level]) != 'undefined') {

                base.blankCircleToFront(level);

                if (base.param.dataActive[level].s == 'segment') {

                    base.param.circleSegment[level][base.param.dataActive[level].a].toFront();
                    base.param.circleSegment[level][base.param.dataActive[level].a].attr({'transform': "s1.05 1.05 " + base.param.canvas.c.x + " " + base.param.canvas.c.y});

                } else if (base.param.dataActive[level].s == 'others') {

                    base.param.circleSegment[level]['others'].toFront();
                    base.param.circleSegment[level]['others'].attr({'transform': "s1.05 1.05 " + base.param.canvas.c.x + " " + base.param.canvas.c.y});

                }

            }

        };


        /* META FUNCTIONS ##################################################################################################
         ################################################################################################################# */
        base.setupLevelInfo = function (value, level) {

            if (typeof(base.param.dataLevelInfo[level]) == 'undefined')
                base.param.dataLevelInfo[level] = {};

            base.param.dataLevelInfo[level] = value;

        };


        base.metaInfoReset = function () {

            if (typeof(base.param.dataLevelInfo[base.param.levelCur]) != 'undefined') {

                var pdfAgency = '';
                var pdfPage = '';
                var pdfPageL = '';
                var pdfPageN1 = '';
                var pdfPageN2 = '';

                if (typeof(base.param.dataLevelInfo[base.param.levelCur].titleDetail) != 'undefined') {
                    pdfAgency = base.param.dataLevelInfo[base.param.levelCur].titleDetail.ep.a;
                    pdfPage = base.param.dataLevelInfo[base.param.levelCur].titleDetail.pdf;
                    pdfPageL = base.param.dataLevelInfo[base.param.levelCur].titleDetail.pdfL;
                    if (typeof(base.param.dataLevelInfo[base.param.levelCur].titleDetail.pdfN1) != 'undefined')
                        pdfPageN1 = base.param.dataLevelInfo[base.param.levelCur].titleDetail.pdfN1;
                    if (typeof(base.param.dataLevelInfo[base.param.levelCur].titleDetail.pdfN2) != 'undefined')
                        pdfPageN2 = base.param.dataLevelInfo[base.param.levelCur].titleDetail.pdfN2;
                }

                base.metaInfoShow(
                    base.param.dataLevelInfo[base.param.levelCur].t,
                    base.param.dataLevelInfo[base.param.levelCur].v,
                    base.param.dataLevelInfo[base.param.levelCur].l,
                    pdfAgency,
                    pdfPage,
                    pdfPageL,
                    pdfPageN1,
                    pdfPageN2
                );

            }

        };


        base.metaInfoShow = function (address, budget, label, pdfAgency, pdfPage, pdfPageL, pdfPageN1, pdfPageN2) {

            if (typeof(address) == 'undefined')
                address = "";

            if (address == "")
                address = "____ ___ __ - ___";

            pdfPage = typeof(pdfPage) == 'undefined' ? -1 : parseInt(pdfPage);
            pdfPageL = typeof(pdfPageL) == 'undefined' ? -1 : parseInt(pdfPageL);
            pdfPageN1 = typeof(pdfPageN1) == 'undefined' ? -1 : parseInt(pdfPageN1);
            pdfPageN2 = typeof(pdfPageN2) == 'undefined' ? -1 : parseInt(pdfPageN2);

            var head = '';
            head += '<small>' + base.param.dataCurrent.y + '</small><br />';
            head += base.options.accountInfo[base.param.dataCurrent.a];

            $('#site-titel h1').html('Bundeshaushalt ' + base.param.dataCurrent.y);
            $('.details h2').html(head);

            if (base.param.dataCurrent.q == 'ist') {
                $('.details p.budget').html(base.number_format(budget / 1000, 0, null, "."))
                    .attr('title', base.number_format(budget, 2, ',', '.') + ' €');
            } else {
                $('.details p.budget').html(base.number_format(budget, 0, null, "."));
            }
            $('.details p.title').html(label);
            $('#unit-info p.address').html(address != '' ? 'Haushaltsstelle: ' + address : 'Haushaltsstelle:');

            if (pdfPage > 0 || pdfPageN1 > 0 || pdfPageN2 > 0) {

                var title = '';
                var link = '';

                // ergaenzung nachtragshaushalt ##############
                if (pdfPageN1 > 0 || pdfPageN2 > 0) {
                    // nachtragshaushalt vorhanden

                    // stammhaushalt:
                    if (pdfPage > 0) {
                        link = '<a target="_blank" class="pdfSH" href="/fileadmin/de.bundeshaushalt/content_de/dokumente/' + base.param.dataCurrent.y + '/' + base.param.dataCurrent.q + '/epl' + pdfAgency + '.pdf#page=' + (pdfPageL > 0 ? pdfPageL : pdfPage) + '" ';
                        link += 'title="Stammhaushalt: Siehe Seite ' + pdfPage + ' im PDF-Dokument des zugehörigen Einzelplans"><span class="sr-hint">Stammhaushalt: Siehe Seite ' + pdfPage + ' im PDF-Dokument des zugehörigen Einzelplans</span></a>';
                    }

                    // 1. nachtragshaushalt:
                    if (pdfPageN1 > 0) {
                        link += '<a target="_blank" class="pdfNH1" href="/fileadmin/de.bundeshaushalt/content_de/dokumente/' + base.param.dataCurrent.y + '/' + base.param.dataCurrent.q + '/n1_epl' + pdfAgency + '.pdf#page=' + pdfPageN1 + '" ';
                        link += 'title="1. Nachtragshaushalt: Siehe Seite ' + pdfPageN1 + ' im PDF-Dokument des zugehörigen Einzelplans"><span class="sr-hint">1. Nachtragshaushalt: Siehe Seite ' + pdfPageN1 + ' im PDF-Dokument des zugehörigen Einzelplans</span></a>';
                    }

                    // 2. nachtragshaushalt:
                    if (pdfPageN2 > 0) {
                        link += '<a target="_blank" class="pdfNH2" href="/fileadmin/de.bundeshaushalt/content_de/dokumente/' + base.param.dataCurrent.y + '/' + base.param.dataCurrent.q + '/n2_epl' + pdfAgency + '.pdf#page=' + pdfPageN2 + '" ';
                        link += 'title="2. Nachtragshaushalt: Siehe Seite ' + pdfPageN2 + ' im PDF-Dokument des zugehörigen Einzelplans"><span class="sr-hint">2. Nachtragshaushalt: Siehe Seite ' + pdfPageN2 + ' im PDF-Dokument des zugehörigen Einzelplans</span></a>';
                    }


                } else {
                    title = 'Siehe Seite ' + pdfPage + ' im PDF-Dokument des zugehörigen Einzelplans';
                    link = '<a target="_blank" class="pdf" href="/fileadmin/de.bundeshaushalt/content_de/dokumente/' + base.param.dataCurrent.y + '/' + base.param.dataCurrent.q + '/epl' + pdfAgency + '.pdf#page=' + (pdfPageL > 0 ? pdfPageL : pdfPage) + '" title="' + title + '"><span class="sr-hint">' + title + '</span></a>';
                }

                if (link != '')
                    $('.details p.pdf').html(link);

            } else {
                $('.details p.pdf').html('');
            }

        };


        /* CENTER CIRCLE FUNCTIONS ###################################################################################################
         ########################################################################################################################### */
        base.centerCircleToFront = function () {
            radius = base.param.canvas.r - ( 4 * base.param.canvas.r / 100 * base.options.canvasRadiusDiff );
            if (base.param.whiteCircle == null) {
                base.param.whiteCircle = base.param.paper.circle(base.param.canvas.c.x, base.param.canvas.c.y, radius).attr({
                    'fill': '#fff',
                    'stroke-width': '0',
                    'stroke': '#fff',
                    'chtLvl': '99',
                    'chtAdr': 'white'
                });
            } else {
                base.param.whiteCircle.toFront();
            }
        };


        base.circleCheckAddress = function (current, level) {

            if (current.d == base.options.dataActiveRoot)
                return true;

            level = level - (level > 0 ? 1 : 0);

            if (typeof(base.param.dataObject[current.y]) == 'undefined')
                return false;

            if (typeof(base.param.dataObject[current.y][current.q]) == 'undefined')
                return false;

            if (typeof(base.param.dataObject[current.y][current.q][current.a]) == 'undefined')
                return false;

            if (typeof(base.param.dataObject[current.y][current.q][current.a][current.u]) == 'undefined')
                return false;

            if (level >= 0)
                if (typeof(base.param.dataObject[current.y][current.q][current.a][current.u][level]) == 'undefined')
                    return false;

            if (current.d != '')
                if (typeof(base.param.dataObject[current.y][current.q][current.a][current.u][level][current.d]) == 'undefined')
                    return false;

            return true;

        };


        /* BLANK CIRCLE FUNCTIONS ####################################################################################################
         ########################################################################################################################### */
        base.blankCircleToFront = function (level) {

            var render = false;

            if (typeof(base.param.blankSegment[level]) == 'undefined') {
                render = true;

            } else if (base.param.blankSegment[level][0] == null) {
                render = true;
            }

            if (render) {
                r = base.param.canvas.r - ( (level) * base.param.canvas.r / 100 * base.options.canvasRadiusDiff ) + 3;
                p = base.param.paper.circle(base.param.canvas.c.x, base.param.canvas.c.y, (r)).attr({
                    'fill': base.param.colors[0],
                    'stroke-width': 0,
                    'stroke-opacity': 0,
                    'fill-opacity': 0,
                    'chtLvl': level,
                    'chtAdr': 'black'
                });
                p.click(base.segmentClickBlank);
                base.param.blankSegment[level] = p;
            }

            base.param.blankSegment[level].toFront();

            base.blankCircleUpdateColor(level);

        };


        base.blankCircleUpdateColor = function (level) {

            if (base.param.blankSegment[level - 0]) base.param.blankSegment[level - 0].animate({
                'fill': '#888',
                'fill-opacity': 1
            }, base.options.animSegmentFadeTime);
            if (base.param.blankSegment[level - 1]) base.param.blankSegment[level - 1].animate({
                'fill': '#aaa',
                'fill-opacity': 1
            }, base.options.animSegmentFadeTime);
            if (base.param.blankSegment[level - 2]) base.param.blankSegment[level - 2].animate({
                'fill': '#ccc',
                'fill-opacity': 1
            }, base.options.animSegmentFadeTime);
            if (base.param.blankSegment[level - 3]) base.param.blankSegment[level - 3].animate({
                'fill': '#eee',
                'fill-opacity': 1
            }, base.options.animSegmentFadeTime);
        };


        /* global functions ################################################################################################
         ####################################################################################################################
         ################################################################################################################# */
        base.resetToLevel = function (level) {

            var glb = jQuery.fn.ppBundeshaushalt.globals;

            if (level > 0) {
                base.param.dataCurrent.d = base.param.dataActive[level - 1].a;    // no root element
            } else if (typeof(base.options.dataActiveRoot) == 'string') {
                base.param.dataCurrent.d = base.options.dataActiveRoot;         // root element, set by embed
            } else {
                base.param.dataCurrent.d = '';                                  // root element, standard
            }

            // updating current level
            base.param.levelCur = level;
            base.param.levelTitle = 0;

            base.param.segmentsSet = base.param.paper.set();
            base.param.segmentsSet.push(base.param.blankSegment[level]);

            base.param.paper.forEach(function (el) {
                if (el.attrs.chtLvl > level && el.attrs.chtLvl < 10)
                    base.param.segmentsSet.push(el);
            });

            if (base.param.segmentsSet.length > 0) {

                base.param.segmentsSet.animate({'opacity': 0}, (base.options.animSegmentFadeTime), 'linear', function () {

                    base.param.segmentsSet.remove();

                    for (i = (level + 1); i <= base.param.levelMax; i++) {
                        delete base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q][base.param.dataCurrent.a][base.param.dataCurrent.u][i];
                        delete base.param.dataActive[i];
                        delete base.param.dataLevelInfo[i];
                        delete base.param.circleSegment[i];
                        delete base.param.blankSegment[i];
                    }

                    delete base.param.blankSegment[level];

                    base.resetSegments(level);

                    base.blankCircleUpdateColor(level - 1);

                    base.metaInfoReset();

                    base.param.requestInProgress = false;

                    base.updatePlugins();

                    glb.animStart();

                });

            } else {

                base.param.segmentsSet.remove();

                for (i = (level + 1); i <= base.param.levelMax; i++) {
                    delete base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q][base.param.dataCurrent.a][base.param.dataCurrent.u][i];
                    delete base.param.dataActive[i];
                    delete base.param.dataLevelInfo[i];
                    delete base.param.circleSegment[i];
                    delete base.param.blankSegment[i];
                }

                delete base.param.blankSegment[level];

                base.resetSegments(level);

                base.blankCircleUpdateColor(level - 1);

                base.metaInfoReset();

                base.param.requestInProgress = false;

                base.updatePlugins();

                glb.animStart();

            }
        };


        base.loadSpinner = function (val) {

            if (val == 1) {
                $('#unit-detail .loadSpinner img').show();
                $('#unit-detail .details h2').animate({opacity: 0.4}, base.options.animSegmentFadeTime);
                $('#unit-detail .details p').animate({opacity: 0.4}, base.options.animSegmentFadeTime);
            } else {
                $('#unit-detail .loadSpinner img').hide();
                $('#unit-detail .details h2').animate({opacity: 1}, base.options.animSegmentFadeTime);
                $('#unit-detail .details p').animate({opacity: 1}, base.options.animSegmentFadeTime);
            }

        };


        base.resetApp = function () {

            base.param.segmentsSet = base.param.paper.set();

            base.param.paper.forEach(function (el) {
                base.param.segmentsSet.push(el);
            });

            base.param.dataObject = {};
            base.param.dataSorted = new Array();
            base.param.dataActive = new Array();
            base.param.dataCurrent = {
                'y': '',
                'q': '',
                'a': '',
                'u': '',
                'd': ''
            };

            base.param.dataRelated = {};
            base.param.dataLevelInfo = {};
            base.param.circleSegment = {};
            base.param.blankSegment = {};

            base.param.levelCur = 0;
            base.param.levelMax = 0;
            base.param.levelTitle = 0;
            base.param.whiteCircle = null;

        };


        base.getTotal = function (JSONobj) {

            var total = 0;

            $.each(JSONobj, function (key, value) {

                total += parseInt(value.v) > 0 ? parseInt(value.v) : 0;

            });

            return parseInt(total);
        };


        base.getLevel = function (current) {

            if (typeof(current.a) == 'undefined') return 0;
            if (typeof(current.u) == 'undefined') return 0;
            if (typeof(current.d) == 'undefined') return 0;

            if (current.u == 'agency') {
                if (current.d.length == 2) return 1 - ( base.options.showLevelStart > 1 ? 1 : base.options.showLevelStart );
                if (current.d.length == 4) return 2 - ( base.options.showLevelStart > 2 ? 2 : base.options.showLevelStart );
                if (current.d.length == 9) return 3 - ( base.options.showLevelStart > 3 ? 3 : base.options.showLevelStart );
            }

            if (current.u == 'function' || current.u == 'group') {
                if (current.d.length == 1) return 1 - ( base.options.showLevelStart > 1 ? 1 : base.options.showLevelStart );
                if (current.d.length == 2) return 2 - ( base.options.showLevelStart > 2 ? 2 : base.options.showLevelStart );
                if (current.d.length == 3) return 3 - ( base.options.showLevelStart > 3 ? 3 : base.options.showLevelStart );
                if (current.d.length == 9) return 4 - ( base.options.showLevelStart > 4 ? 4 : base.options.showLevelStart );
            }

            return 0;

        };


        base.number_format = function (number, decimals, dec_point, thousands_sep) {

            var exponent = "";
            var numberstr = number.toString ();
            var eindex = numberstr.indexOf ("e");
            if (eindex > -1) {
                exponent = numberstr.substring (eindex);
                number = parseFloat(numberstr.substring (0, eindex));
            }

            if (decimals != null) {
                var temp = Math.pow (10, decimals);
                number = Math.round (number * temp) / temp;
            }
            var sign = number < 0 ? "-" : "";
            var integer = (number > 0 ?
                Math.floor (number) : Math.abs (Math.ceil (number))).toString ();

            var fractional = number.toString ().substring (integer.length + sign.length);
            dec_point = dec_point != null ? dec_point : ".";
            fractional = decimals != null && decimals > 0 || fractional.length > 1 ?
                (dec_point + fractional.substring (1)) : "";
            if (decimals != null && decimals > 0) {
                for (i = fractional.length - 1, z = decimals; i < z; ++i)
                    fractional += "0";
            }

            thousands_sep = (thousands_sep != dec_point || fractional.length == 0) ?
                thousands_sep : null;
            if (thousands_sep != null && thousands_sep != "") {
                for (i = integer.length - 3; i > 0; i -= 3)
                    integer = integer.substring (0, i) + thousands_sep + integer.substring (i);
            }

            return sign + integer + fractional + exponent;
        };


        base.initializePlugins = function () {

            if (jQuery.fn.ppBundeshaushalt.address) {
                var temp = jQuery.fn.ppBundeshaushalt.address.init(base.param.dataCurrent, base.requestJSON, base.param.avail);
                base.param.dataCurrent = temp.current;
                base.param.levelCur = temp.level;
            }

            if (jQuery.fn.ppBundeshaushalt.intro)
                jQuery.fn.ppBundeshaushalt.intro.init(base.requestAddress, base.param.dataCurrent, base.param.defaults);

            if (jQuery.fn.ppBundeshaushalt.breadcrumb)
                jQuery.fn.ppBundeshaushalt.breadcrumb.init(base.requestAddress);

            if (jQuery.fn.ppBundeshaushalt.unitdetails)
                jQuery.fn.ppBundeshaushalt.unitdetails.init();

            if (jQuery.fn.ppBundeshaushalt.unitnavigation)
                jQuery.fn.ppBundeshaushalt.unitnavigation.init();

            if (jQuery.fn.ppBundeshaushalt.tabledata)
                jQuery.fn.ppBundeshaushalt.tabledata.init(base.requestAddress, base.param.dataCurrent);

            if (jQuery.fn.ppBundeshaushalt.detailnw)
                jQuery.fn.ppBundeshaushalt.detailnw.init(base.param.dataCurrent);

            if (jQuery.fn.ppBundeshaushalt.social)
                jQuery.fn.ppBundeshaushalt.social.init(base.param.dataCurrent);

            if (jQuery.fn.ppBundeshaushalt.timeline)
                jQuery.fn.ppBundeshaushalt.timeline.init();

            if (jQuery.fn.ppBundeshaushalt.pagetitle)
                jQuery.fn.ppBundeshaushalt.pagetitle.init();

        };


        base.updatePlugins = function () {

            if (jQuery.fn.ppBundeshaushalt.intro) {
                jQuery.fn.ppBundeshaushalt.intro.update(base.requestAddress, base.param.dataCurrent);
            }

            if (jQuery.fn.ppBundeshaushalt.address) {
                jQuery.fn.ppBundeshaushalt.address.update(base.param.dataCurrent);
            }

            if (jQuery.fn.ppBundeshaushalt.breadcrumb) {
                jQuery.fn.ppBundeshaushalt.breadcrumb.update(base.requestAddress, base.param.dataCurrent, base.param.dataObject, base.param.dataActive, base.param.levelCur, base.param.levelMax, base.param.defaults, base.param.avail);
            }

            if (jQuery.fn.ppBundeshaushalt.unitdetails) {
                jQuery.fn.ppBundeshaushalt.unitdetails.update(base.requestAddress, base.param.dataCurrent, base.param.dataRelated, base.param.levelTitle);
            }

            if (jQuery.fn.ppBundeshaushalt.unitnavigation) {
                jQuery.fn.ppBundeshaushalt.unitnavigation.update(base.requestAddress, base.param.dataCurrent);
            }

            if (jQuery.fn.ppBundeshaushalt.tabledata) {
                jQuery.fn.ppBundeshaushalt.tabledata.update(base.dataOver, base.dataOut, base.requestAddress, base.param.dataSorted, base.param.dataCurrent, base.param.dataObject, base.param.dataActive, base.param.levelCur, base.param.levelMax);
            }

            if (jQuery.fn.ppBundeshaushalt.detailnw) {
                jQuery.fn.ppBundeshaushalt.detailnw.update(base.param.dataCurrent);
            }

            if (jQuery.fn.ppBundeshaushalt.social) {
                jQuery.fn.ppBundeshaushalt.social.update(base.param.dataCurrent);
            }

            if (jQuery.fn.ppBundeshaushalt.pagetitle) {
                jQuery.fn.ppBundeshaushalt.pagetitle.update(base.param.dataCurrent, base.param.dataLevelInfo[base.param.levelCur], base.param.levelCur);
            }

            if (jQuery.fn.ppBundeshaushalt.timeline) {
                jQuery.fn.ppBundeshaushalt.timeline.show(
                    typeof base.param.dataLevelInfo[base.param.levelCur] !== 'undefined'
                        ? base.param.dataLevelInfo[base.param.levelCur].l
                        : '',
                    typeof base.param.dataLevelInfo[base.param.levelCur] !== 'undefined'
                        ? base.param.dataLevelInfo[base.param.levelCur].v
                        : '',
                    base.param.dataCurrent.y,
                    base.param.dataCurrent.q,
                    base.param.dataCurrent.a,
                    base.param.dataCurrent.u,
                    base.param.dataCurrent.d,
                    base.param.dataCurrent.d != ''
                        ? base.param.dataObject[base.param.dataCurrent.y][base.param.dataCurrent.q][base.param.dataCurrent.a][base.param.dataCurrent.u][base.param.levelCur - 1][base.param.dataCurrent.d]['color']
                        : ''
                );
            }
        };

        // Run initializer #############################################################################################
        base.init();
    };

    $.ppBundeshaushalt.Main.defaultOptions = {

        year: '',
        quota: '',
        account: '',
        unit: '',
        address: '',

        service: {
            protocol: '',
            host: '',
            path: ''
        },

        width: null,       // possible values: int : fixed size in pixel, 'auto' : use canvas width, 'copy' : copy from height
        height: null,       // possible values: int : fixed size in pixel, 'auto' : use canvas height, 'copy' : copy from width
        animSegmentInterval: 20,
        animSegmentFadeTime: 500,
        canvasRadiusDiff: 10,         // difference between circles in percent (percent/float)
        canvasHoverScale: 5,          // max scale of hovered segment (percent/float)
        interactive: false,      // enabling mouse events?

        eDivApplication: '#unit-detail',

        accountInfo: {
            "e": 'Einnahmen<br><small> in Tausend Euro</small>',
            "a": 'Ausgaben<br><small> in Tausend Euro</small>'
        },

        units: {
            "Einzelplan": 'agency',
            "Gruppe": 'group',
            "Funktion": 'function'
        },

        quotas: {
            "Ist": 'ist',
            "Soll": 'soll'
        },

        weiteres: {
            func: null,
            html: ''
        },

        piwikApp: 'App: Kreis',
        embed: 0,
        showLevelStart: 0,     // ab welchem Level soll angezeigt werden (0 = default)
        actLevelStart: 0      // ab welchem Level darf interagiert werden (0 = default)

    };

    $.fn.ppBundeshaushalt_Main = function (options) {
        return this.each(function () {
            (new $.ppBundeshaushalt.Main(this, options));
        });
    };

})(jQuery);
