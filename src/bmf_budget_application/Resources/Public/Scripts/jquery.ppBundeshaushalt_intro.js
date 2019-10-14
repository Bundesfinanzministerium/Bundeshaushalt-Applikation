jQuery.fn.ppBundeshaushalt.intro = {

    // properties ################################
    mainDiv: 'div.tx-bmf-budget',
    introDiv: 'div.tx-bmf-budget div.intro',
    canvasDiv: '#unit-detail',

    // methods ###################################
    'init': function (requestAddress, current, defaults) {

        var html = '',
            param = '';

        if (current['a'] == 'blank') {

            param = '';
            param += 'display:block;';
            param += 'position:absolute;';
            param += 'top:-10000;';
            param += 'left:-10000;';
            param += 'width:' + $(this.intro).width() + 'px;';

            html = '';
            html += '<div class="row" id="data-details" style="' + param + '">';
            html += '    <h1 id="app-main-header" class="sr-hint">Aktueller Posten</h1>';
            html += '    <div class="grid_3" id="unit-info"></div>';
            html += '    <div class="grid_6" id="unit-detail" aria-hidden="true"> </div>';
            html += '    <div class="grid_3" id="unit-overview"> </div>';
            html += '</div>';

            html += '<div id="data-timeline" style="display:none;"></div>';

            html += '<div id="data-unitNavigation" style="display:none;"><h2 class="sr-hint">Daten-Tabellen : Gliederungs-Navigation</h2><ul><li class="group" id="dgroup"></li><li class="section" id="dsection"></li><li class="function" id="dfunction"></li></ul></div>';
            html += '<h2 class="sr-hint" style="display:none;">Tabellarische Übersicht</h2>';
            html += '<div class="data-table"><table class="titel" id="bundeshaushalt-data" style="display:none;" aria-labelledby="dsection"></table></div>';

            $(this.mainDiv).append(html);

            $(this.introDiv).show();


        } else {

            html = '';
            html += '<div class="row" id="data-details" style="display:none;">';
            html += '    <h1 id="app-main-header" class="sr-hint">>Aktueller Posten</h1>';
            html += '    <div class="grid_3" id="unit-info"></div>';
            html += '    <div class="grid_6" id="unit-detail" aria-hidden="true"> </div>';
            html += '    <div class="grid_3" id="unit-overview"> </div>';
            html += '</div>';

            html += '<div id="data-timeline"></div>';

            html += '<div id="data-unitNavigation" style="display:none;"><h2 class="sr-hint">Daten-Tabellen : Gliederungs-Navigation</h2><ul><li class="group" id="dgroup"></li><li class="section" id="dsection"></li><li class="function" id="dfunction"></li></ul></div>';
            html += '<h2 class="sr-hint" style="display:none;">Tabellarische Übersicht</h2>';
            html += '<div class="data-table"><table id="bundeshaushalt-data" style="display:none;" aria-labelledby="dsection"></table></div>';

            $(this.mainDiv + ' .intro').hide();
            $(this.mainDiv).append(html);

            $(this.mainDiv + ' #data-breadcrumb').show();
            $(this.mainDiv + ' #data-details').show();
            $(this.mainDiv + ' #data-details #unit-overview').hide();
            $(this.mainDiv + ' #data-timeline').show();
            $(this.mainDiv + ' #data-unitNavigation').show();
            $(this.mainDiv + ' h2').show();
            $(this.mainDiv + ' #bundeshaushalt-data').show();

        }

        // setting events
        $(this.introDiv + ' .einnahmen a').click(function () {
            requestAddress({
                'y': defaults.e.year,
                'q': defaults.e.quota,
                'a': defaults.e.account,
                'u': defaults.e.unit,
                'd': defaults.e.address
            }, 'Bundeshaushalt');
            return false;
        });

        $(this.introDiv + ' .ausgaben a').click(function () {
            requestAddress({
                'y': defaults.a.year,
                'q': defaults.a.quota,
                'a': defaults.a.account,
                'u': defaults.a.unit,
                'd': defaults.a.address
            }, 'Bundeshaushalt');
            return false;
        });

    },

    'showApplication': function (eDivPlugin) {

        if ($('.intro', eDivPlugin).is(":visible")) {

            $('#data-breadcrumb', eDivPlugin).fadeIn();
            $('#data-details', eDivPlugin).fadeIn();
            $('.intro', eDivPlugin).fadeOut();

        }

    },

    'update': function (requestAddress, dataCurrent) {

        $('#site-titel h1').html('');

        if (dataCurrent.a === '') {
            $(this.mainDiv + ' #data-breadcrumb').hide();
            $(this.mainDiv + ' #data-details').hide();
            $(this.mainDiv + ' #data-timeline').hide();
            $(this.mainDiv + ' #data-unitNavigation').hide();
            //$(this.mainDiv + ' h2').hide();
            $(this.mainDiv + ' #bundeshaushalt-data').hide();
            $(this.introDiv).show();
        } else {
            $(this.mainDiv + ' #data-details').css({
                'position': '',
                'top': '',
                'left': '',
                'display': '',
                'width': '',
                'height': '',
                'opacity': ''
            });
            $(this.introDiv).hide();
            $(this.mainDiv + ' #data-breadcrumb').show();
            $(this.mainDiv + ' #data-details').show();
            $(this.mainDiv + ' #data-timeline').show();
            $(this.mainDiv + ' #data-unitNavigation').show();
            //$(this.mainDiv + ' h2').show();
            $(this.mainDiv + ' #bundeshaushalt-data').show();
        }

    }
};
