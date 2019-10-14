jQuery.fn.ppBundeshaushalt.tabledata = {

    // properties ################################
    // http://www.asual.com/jquery/address/
    'table'   : '#bundeshaushalt-data',
    'piwikApp': 'App: Tabelle',

    'label' : {
        'uPostenDef' : {
            'e' : 'Einnahmen',
            'a' : 'Ausgaben'
        }
    },

    'account' : {
        'e' : 'einnahmen',
        'a' : 'ausgaben'
    },

    'unit' : {
        'agency'   : 'einzelplan',
        'function' : 'funktion',
        'group'    : 'gruppe'
    },

    'labelLength'   : 70,

    'tableStdWidth' : 940,

    'theadSm'   : '<thead><tr class="head"><th class="col1" tabindex="0" aria-controls="bundeshaushalt-data" aria-label="Betrag in tausend Euro: aktivieren, um die Spalte absteigend zu sortieren" aria-sort="ascending"><span class="sortarrow" title="sortieren"></span>Betrag<br><small> in Tausend Euro</small></th><th class="col2" tabindex="0" aria-controls="bundeshaushalt-data" aria-label="Posten: aktivieren, um die Spalte absteigend zu sortieren" aria-sort="ascending">Posten<span class="sortarrow" title="sortieren"></span><br><small></small></th><th class="col3 noSPL" tabindex="0" aria-controls="bundeshaushalt-data" aria-label="Anteil an Summe pos. Posten: aktivieren, um die Spalte absteigend zu sortieren" aria-sort="ascending"><span class="sortarrow" title="sortieren"></span>Anteil<br><small>an Summe pos. Posten</small></th></tr></thead>',
    'theadLg'   : '<thead><tr class="head"><th class="col1" tabindex="0" aria-controls="bundeshaushalt-data" aria-label="Betrag in tausend Euro: aktivieren, um die Spalte absteigend zu sortieren" aria-sort="ascending"><span class="sortarrow" title="sortieren"></span>Betrag<br><small> in Tausend Euro</small></th><th class="col2" tabindex="0" aria-controls="bundeshaushalt-data" aria-label="Posten: aktivieren, um die Spalte absteigend zu sortieren" aria-sort="ascending">Posten<span class="sortarrow" title="sortieren"></span><br><small></small></th><th class="col3 noSPL" tabindex="0" aria-controls="bundeshaushalt-data" aria-label="Anteil an Summe pos. Posten: aktivieren, um die Spalte absteigend zu sortieren" aria-sort="ascending"><span class="sortarrow" title="sortieren"></span>Anteil<br><small>an Summe pos. Posten</small></th><th class="col4 noSPL noSPP">Details</th><th class="col5 noSPL noSPP">Erläuterungen und Vermerke</th><th class="col6 noSPL noSPP">Anmerkungen</th></tr></thead>',

    'animObj'   : new Array(),
    'animTimer' : null,


    'init' : function( requestAddress, dataCurrent ) {

    },


    'update' : function( dataOver, dataOut, requestAddress, dataSorted, dataCurrent, dataObject, dataActive, levelCur, levelMax ) {

        var glb = jQuery.fn.ppBundeshaushalt.globals;
        var lng = jQuery.fn.ppBundeshaushalt.language.de;

        if ( !glb.checkDataObject(dataObject, dataCurrent) ) {
            return false;
        }

        if ( dataSorted.length == 0 )
            return false;

        // initialize local variables ##################################################################################
        var plg = jQuery.fn.ppBundeshaushalt.tabledata;
        var glb = jQuery.fn.ppBundeshaushalt.globals;

        // initialize table #############################################################
        if ( $(plg.table + ' tbody').length == 0 ) {
            $(plg.table).html(plg.theadSm + '<tbody></tbody>');
        }

        // mark elements to fade out ####################################################
        $(plg.table + ' tbody tr').addClass('fO');
        $(plg.table + ' tbody tr').removeClass('fI');

        // get amount of childs #########################################################
        var oldTr = $(plg.table + ' tbody').children().length;

        // setup "übergeordneten Posten" ################################################
        if ( levelCur-1 < 0 )
            plg.uePostenDefault( dataCurrent );
        else
            plg.uePostenEntity( dataCurrent, dataObject, dataActive, levelCur, levelMax );

        // render table rows ############################################################
        if ( levelCur < levelMax ) {
            $(plg.table).attr('class', $(plg.table).hasClass('sorted') ? 'sorted' : '' );

            var curTr = plg.simpleRows(dataOver, dataOut, requestAddress, dataSorted, dataCurrent, dataObject, levelCur, oldTr);

        } else if ( levelCur >= levelMax ) {
            var level      = levelCur > levelMax ? levelMax : levelCur;
            var subAddress = dataActive[level-1].a;

            if ( ($(plg.table).hasClass('add'+subAddress)) && false ) {

                var curTr  = oldTr;
                var actRow = $(plg.table + ' tr.add' + dataCurrent.d);

                $(plg.table + ' tbody tr').removeClass('fO');
                $(plg.table + ' tr.active td.col2 h3 strong').hide();
                $(plg.table + ' tr.active td.col2 h3 a').show();

                if ( $(plg.table).width() >= plg.tableStdWidth ) {

                    // only process if table has its maxed width
                    $(plg.table + ' tr.active .meta').fadeOut(125);
                    $(plg.table + ' tr.active').removeClass('active');
                    if ( levelCur > levelMax ) {
                        $('td.col2 h3 a', actRow).hide();
                        $('td.col2 h3 strong', actRow).show();
                        $('.meta', actRow).fadeIn(500, function() {
                            $(this).parent().addClass('active');
                            $(this).parent().removeAttr('style');
                            $(this).removeAttr('style');
                        });
                    }

                } else {

                    // only process if table is scaled/croped by smaller screens
                    $(plg.table + ' tr.active').removeClass('active');
                    if ( levelCur > levelMax ) {
                        $('td.col2 h3 a', actRow).hide();
                        $('td.col2 h3 strong', actRow).show();
                        $(plg.table + ' tr.add' + dataCurrent.d).addClass('active');
                    }
                }


            } else {

                var curTr = plg.advancedRows(dataOver, dataOut, requestAddress, dataSorted, dataCurrent, dataObject, dataActive, levelCur, levelMax, oldTr);

                $(plg.table).attr('class', $(plg.table).hasClass('sorted') ? 'sorted add'+subAddress : 'add'+subAddress);

            }

        }

        $(plg.table + ' .fO').remove();
        $(plg.table + ' .fI').show();

        $(plg.table + ' tr').removeClass('fI');
        $(plg.table + ' tr').removeClass('hover');

        plg.updateTableSorter();

        $(plg.table + ' .fO').remove();
        $(plg.table + ' tr').removeAttr('style');

    },


    // #################################################################################################################
    // UEBERGEORDNETER POSTEN DEFAULT ##################################################################################
    // #################################################################################################################


    'uePostenDefault' : function( dataCurrent ) {

        // initialize local variables ###################################################
        var plg   = jQuery.fn.ppBundeshaushalt.tabledata;
        var glb   = jQuery.fn.ppBundeshaushalt.globals;
        var label = plg.label.uPostenDef[dataCurrent.a];

        // check if update is necessary #################################################
        if ( $(plg.table + ' th.col2 small').text() == label )
            return false;

        $( plg.table + ' th.col2 small').removeClass('fI');
        $( plg.table + ' th.col2 small').addClass('fO');

        $( plg.table + ' th.col2').append(
            $('<small>', {
                'text'  : label,
                'class' : 'fI',
                'style' : 'display:none;'
            })
        );

        // update animations ################################################
        glb.animAddElement([
            {'e' : $(plg.table+' th.col2 small.fO'), 'f':'fO', 't':'jQ'},
            {'e' : $(plg.table+' th.col2 small.fI'), 'f':'fI', 't':'jQ'}
        ]);

    },


    // #################################################################################################################
    // UEBERGEORDNETER POSTEN ENTITY ###################################################################################
    // #################################################################################################################


    'uePostenEntity' : function( dataCurrent, dataObject, dataActive, levelCur, levelMax ) {

        // initialize local variables ###################################################
        var plg   = jQuery.fn.ppBundeshaushalt.tabledata;
        var glb   = jQuery.fn.ppBundeshaushalt.globals;
        var level = levelCur > levelMax ? levelMax : levelCur;
        var label = " unterhalb von: " + dataObject[dataCurrent.y][dataCurrent.q][dataCurrent.a][dataCurrent.u][level-1][dataActive[level-1].a].label;

        // check if update is necessary #################################################
        if ( $(plg.table + ' th.col2 small').text() == label )
            return false;

        $( plg.table + ' th.col2 small').removeClass('fI');
        $( plg.table + ' th.col2 small').addClass('fO');

        $( plg.table + ' th.col2').append(
            $('<small>', {
                'text'  : label,
                'class' : 'fI',
                'style' : 'display:none;'
            })
        );

        // update animations ################################################
        glb.animAddElement([
            {'e' : $(plg.table+' th.col2 small.fO'), 'f':'fO', 't':'jQ'},
            {'e' : $(plg.table+' th.col2 small.fI'), 'f':'fI', 't':'jQ'}
        ]);

    },


    // #################################################################################################################
    // SIMPLE TABLE ROWS ###############################################################################################
    // #################################################################################################################


    'simpleRows' : function(dataOver, dataOut, requestAddress, dataSorted, dataCurrent, dataObject, levelCur, oldTr) {

        // initialize local variables ###################################################
        var plg = jQuery.fn.ppBundeshaushalt.tabledata;
        var glb = jQuery.fn.ppBundeshaushalt.globals;

        var data, percent0, percent2, curTr = 0, budget = 0;
        var eH3, eSp, eTr, eC1, eC2, eC3;

        if (dataSorted.length > 0 ) {

            $.each(dataSorted[levelCur], function(index, value) {

                curTr    = curTr + 1;
                data     = dataObject[dataCurrent.y][dataCurrent.q][dataCurrent.a][dataCurrent.u][levelCur][value];
                percent0 = data.budget > 0 ? Math.round(data.percent) : 0;
                percent2 = data.budget > 0 ? (data.percent * 100) / 100 : 0;
                budget   = dataCurrent.q == 'ist' ? glb.number_format(data.budget/1000, 0, null, ".") : glb.number_format(data.budget, 0, null, ".");

                eH3 = $('<h3>').append(
                    $('<a>',{
                        'title' : data.label,
                        'text'  : glb.crop(data.label),
                        'href'  : glb.url(dataCurrent,value),
                        'click' : function(){
                            requestAddress({'y':dataCurrent.y, 'q':dataCurrent.q, 'a':dataCurrent.a, 'u':dataCurrent.u, 'd':value}, plg.piwikApp);
                            return false;
                        }}))

                eSp = $('<span>',{'class':'balkenProzent','style':'width:'+percent0+'%; background-color:' + data.color + ';'})

                eTr = $('<tr>', {
                    'class'      : 'fI lvl' + levelCur + ' add' + value,
                    'style'      : 'display:none;',
                    'mouseenter' : function(){ dataOver(levelCur,value); },
                    'mouseleave' : function(){ dataOut(levelCur,value); }});

                eC1 = $('<td>',{'class':'col1','text':budget}).click(function(){
                    requestAddress({'y':dataCurrent.y, 'q':dataCurrent.q, 'a':dataCurrent.a, 'u':dataCurrent.u, 'd':value}, plg.piwikApp);
                });
                eC2 = $('<td>',{'class':'col2'}).append(eH3,eSp).click(function(){
                    requestAddress({'y':dataCurrent.y, 'q':dataCurrent.q, 'a':dataCurrent.a, 'u':dataCurrent.u, 'd':value}, plg.piwikApp);
                });
                // render values under 0.01% and over 0% as "< 0.01%"
                if (percent2 < 0.01 && percent2 !== 0 ) {
                    percent2 = 0.01;
                    eC3 = $('<td>',{'class':'col3','text':'< '+glb.number_format(percent2, 2, ",", ".") + '%'});
                }else {
                    eC3 = $('<td>',{'class':'col3','text':glb.number_format(percent2, 2, ",", ".") + '%'});
                }



                eTr.append(eC1,eC2,eC3);

                // append table row #################################################
                $(plg.table + ' tbody').append(eTr);

            })

        }

        return curTr;
    },


    // #################################################################################################################
    // ADVANCED TABLE ROWS #############################################################################################
    // #################################################################################################################


    'advancedRows' : function(dataOver, dataOut, requestAddress, dataSorted, dataCurrent, dataObject, dataActive, levelCur, levelMax, oldTr) {

        // initialize local variables ###################################################
        var plg = jQuery.fn.ppBundeshaushalt.tabledata;
        var glb = jQuery.fn.ppBundeshaushalt.globals;

        var data, percent0, percent2, curTr = 0, budget = 0;
        var title, level, actAddress;
        var eH3, eSp, eTr, eC1, eC2, eC3, eA, eA1, eA2, eA3, rowId;

        title      = levelCur > levelMax ? true : false;
        level      = title ? levelMax : levelCur;
        actAddress = typeof(dataActive[level]) != 'undefined' ? dataActive[level].a : '';

        if (dataSorted.length > 0 ) {

            $.each( dataSorted[level], function(index, value) {

                curTr    = curTr + 1;
                data     = dataObject[dataCurrent.y][dataCurrent.q][dataCurrent.a][dataCurrent.u][level][value];
                percent0 = data.budget > 0 ? Math.round(data.percent) : 0;
                percent2 = data.budget > 0 ? (data.percent * 100) / 100 : 0;
                budget   = dataCurrent.q == 'ist' ? glb.number_format (data.budget/1000, 0, null, ".") : glb.number_format (data.budget, 0, null, ".");
                rowId    = 'row' + curTr;

                // TR ##################################################################################################
                eTr = $('<tr>', {
                    'class'      : 'fI lvl' + levelCur + ' add' + value,
                    'style'      : 'display:none;'
                });

                if ( !title )
                    eTr.hover(function(){dataOver(level,value);},function(){dataOut(level,value);} );

                // H3 ##################################################################################################
                eH3 = $('<h3>', {id: rowId});

                eH3.append(
                    $('<strong>',{
                        'title' : data.label,
                        'text'  : glb.crop(data.label)
                    })
                );

                eH3.append(
                    $('<a>',{
                            'title' : data.label,
                            'text'  : glb.crop(data.label),
                            'href'  : glb.url(dataCurrent,value),
                            'click' : function(){
                                requestAddress({'y':dataCurrent.y, 'q':dataCurrent.q, 'a':dataCurrent.a, 'u':dataCurrent.u, 'd':value}, plg.piwikApp);
                                return false;
                            }
                        }
                    )
                );

                eSp = $('<span>',{'class':'balkenProzent','style':'width:'+percent0+'%; background-color:' + data.color + ';'})

                eC1 = $('<td>',{
                    'class' : 'col1',
                    'text'  : budget,
                    'click' : function(){ requestAddress({'y':dataCurrent.y, 'q':dataCurrent.q, 'a':dataCurrent.a, 'u':dataCurrent.u, 'd':value}, plg.piwikApp); }
                });

                eC2 = $('<td>',{
                    'class' : 'col2',
                    'click' : function(){ requestAddress({'y':dataCurrent.y, 'q':dataCurrent.q, 'a':dataCurrent.a, 'u':dataCurrent.u, 'd':value}, plg.piwikApp); }
                }).append(eH3,eSp);

                eC3 = $('<td>',{'class':'col3'});
                eC3.append(
                    $('<a>',{
                        // 'href'  : glb.url(dataCurrent, value),
                        'href'  : '#collapse' + rowId,
                        'aria-expanded': 'false',
                        'aria-controls': 'collapse' + rowId,
                        'click' :function(e) {
                            e.preventDefault();
                            var toggleLink = $(this);
                            var parentRow = $(this).parent().parent();
                            var collapse  = $('.collapse', parentRow);
                            if (collapse.is(':visible')) {
                                parentRow.removeClass('active');
                                collapse.slideUp(125, function () {
                                    toggleLink.attr('aria-expanded', 'false');
                                    collapse.attr('aria-expanded', 'false');
                                    collapse.removeClass('in');
                                });
                            } else {
                                parentRow.addClass('active');
                                collapse.slideDown(125, function () {
                                    toggleLink.attr('aria-expanded', 'true');
                                    collapse.attr('aria-expanded', 'true');
                                    collapse.addClass('in');
                                });
                            }
                            $('span', this).text($(parentRow).hasClass('active')?'weniger anzeigen':'mehr anzeigen');
                            return false;
                        }}).html('<span class="sr-hint"></span>'));

                // render values under 0.01% and over 0% as "< 0.01%"
                if (percent2 < 0.01 && percent2 !== 0 ) {
                    percent2 = 0.01;
                    eC3 = $('<td>',{'class':'col3','text':'< '+glb.number_format(percent2, 2, ",", ".") + '%'});
                }else {
                    eC3 = $('<td>',{'class':'col3','text':glb.number_format(percent2, 2, ",", ".") + '%'});
                }

                eA1 = plg.meta4(requestAddress, dataCurrent, data, percent2, value);
                eA2 = plg.meta5(parseInt(data.budget) > 0 && parseInt(data.flex) <= 0, data.titleDetail, dataCurrent.y, dataCurrent.q);
                eA3 = plg.meta6(parseInt(data.budget) > 0 && parseInt(data.flex) <= 0, parseInt(data.budget), parseInt(data.flex));
                eA  = $('<div>', {
                    id: 'collapse' + rowId,
                    class: 'collapse',
                    role: 'tabpanel',
                    "aria-labelledby": rowId
                });
                eA.append(eA1, eA2, eA3);

                eC2.append(eA);
                eTr.append(eC1,eC2,eC3);

                if ( actAddress == value ) {

                    eTr.addClass('active');
                    eA.addClass('in').attr('aria-expanded', 'true');
                    $('td.col2 h3 a', eTr).hide();
                    $('td.col3 a', eTr).attr('aria-expanded', 'true');
                    $('td.col3 a span', eTr).text('weniger anzeigen');

                } else {

                    eA.attr('aria-expanded', 'false');
                    $('td.col2 h3 strong', eTr).hide();
                    $('td.col3 a span', eTr).text('mehr anzeigen');

                }

                // append table row #################################################
                $(plg.table + ' tbody').append(eTr);

            });
        }

        return curTr;

    },

    'meta4' : function(requestAddress, dataCurrent, data, percent, address ) {

        // initialize local variables ###################################################
        var plg  = jQuery.fn.ppBundeshaushalt.tabledata;
        var glb  = jQuery.fn.ppBundeshaushalt.globals;
        var jQc4 = null;
        var col4 = '';

        // building HTML ################################################################
        col4 += '<div class="col4 noSPP noSPL">';
        col4 += '<div class="wrapper">';
        col4 += '<dl>';
        col4 += '<dt>Einzelplan ' + (data.titleDetail.ep.a) + '</dt>';
        col4 += '<dd class="ep"><strong><a href="' + glb.url({
            'y' : dataCurrent.y,
            'q' : dataCurrent.q,
            'a' : dataCurrent.a,
            'u' : 'agency'
        }, data.titleDetail.ep.a) + '">' + (data.titleDetail.ep.l) + '</a></strong></dd>';
        col4 += '<dt>Kapitel ' + (data.titleDetail.kp.a) + '</dt>';
        col4 += '<dd class="kp"><a href="' + glb.url({
            'y' : dataCurrent.y,
            'q' : dataCurrent.q,
            'a' : dataCurrent.a,
            'u' : 'agency'
        }, data.titleDetail.kp.a) + '">' + (data.titleDetail.kp.l) + '</a></dd>';
        if ( typeof(data.titleDetail.tg) != 'undefined' )
          col4 += '<dt class="additional">Titelgruppe ' + data.titleDetail.tg.a + '</dt><dd class="additional">' + data.titleDetail.tg.l + '</dd>';

        if ( typeof(data.titleDetail.ug) != 'undefined' )
            col4 += '<dt class="additional">Bereich</dt><dd class="additional">' + data.titleDetail.ug + '</dd>';

        col4 += '<dt class="additional">Titelnummer</dt><dd class="additional">' + address.substr(4,5) + '</dd>';

        col4 += '</dl>';
        col4 += '<div class="saeulen">';
        col4 += '<img src="/typo3conf/ext/bmf_budget/Resources/Public/Images/table_bar_shadow.png">';
        col4 += '<div style="height:' + (percent) + '%; background-color:' + data.color + '; border-color:#d0d0d0;" class="saeule1"><p>' + glb.number_format(percent, 2, ",", ".") + '%</p></div>';
        col4 += '<div class="saeule2"></div>';
        col4 += '</div>';
        col4 += '</div>';
        col4 += '</div>';

        // appending click events #######################################################
        jQc4 = $(col4);
        $('dd.ep a', jQc4).click( function(){ requestAddress({'y':dataCurrent.y, 'q':dataCurrent.q, 'a':dataCurrent.a, 'u':'agency', 'd':data.titleDetail.ep.a}, plg.piwikApp); return false; } );
        $('dd.kp a', jQc4).click( function(){ requestAddress({'y':dataCurrent.y, 'q':dataCurrent.q, 'a':dataCurrent.a, 'u':'agency', 'd':data.titleDetail.kp.a}, plg.piwikApp); return false; } );

        return jQc4;

    },

    'meta5' : function( wide, titleDetail, year, quota ) {

        var agency = titleDetail.ep.a;
        var page   = parseInt(titleDetail.pdf);
        var pageL  = typeof(titleDetail.pdfL)  != 'undefined' ? parseInt(titleDetail.pdfL) : 0;
        var pageN1 = typeof(titleDetail.pdfN1) != 'undefined' ? parseInt(titleDetail.pdfN1) : 0;
        var pageN2 = typeof(titleDetail.pdfN2) != 'undefined' ? parseInt(titleDetail.pdfN2) : 0;
        var col5   = '';
        var pHtml  = '';

        if ( pageN1 > 0 || pageN2 > 0 ) {
            pHtml  = 'siehe PDF-Dokument des zugehörigen Einzelplans:';
            if ( page > 0 ) {
                pHtml += '<br>Seite ' + page + ' im <a href="/fileadmin/de.bundeshaushalt/content_de/dokumente/' + year + '/' + quota + '/epl' + agency + '.pdf#page=' + (pageL > 0 ? pageL : page) + '" class="pdfSH" target="_blank">Stammhaushalt</a>';
            }

            if ( pageN1 > 0 ) {
                pHtml += '<br>Seite ' + pageN1 + ' im <a href="/fileadmin/de.bundeshaushalt/content_de/dokumente/' + year + '/' + quota + '/n1_epl' + agency + '.pdf#page=' + pageN1 + '" class="pdfNH1" target="_blank">1. Nachtragshaushalt</a>';
            }

            if ( pageN2 > 0 ) {
                pHtml += '<br>Seite ' + pageN2 + ' im <a href="/fileadmin/de.bundeshaushalt/content_de/dokumente/' + year + '/' + quota + '/n2_epl' + agency + '.pdf#page=' + pageN2 + '" class="pdfNH2" target="_blank">2. Nachtragshaushalt</a>';
            }


        } else {
            pHtml = 'siehe Seite ' + page + ' im <a href="/fileadmin/de.bundeshaushalt/content_de/dokumente/' + year + '/' + quota + '/epl' + agency + '.pdf#page=' + (pageL > 0 ? pageL : page) + '" class="pdf" target="_blank">PDF-Dokument</a> des zugehörigen Einzelplans'
        }

        col5 += '<div class="col5 noSPP noSPL' + ( wide ? ' wide' : '' ) + '">';
        col5 += '<div class="wrapper">';
        col5 += '<h4>Erläuterungen und Vermerke</h4>';
        col5 += '<p>' + pHtml + '</p>';
        col5 += '</div>';
        col5 += '</div>';

        return $(col5);

    },

    'meta6' : function( wide, budget, flex ) {

        var col6 = '';

        col6 += '<div class="col6 noSPP noSPL' + ( wide ? ' hidden' : '' ) + '">';
        col6 += '<div class="wrapper">';
        col6 += '<h4>Anmerkungen</h4>';

        if ( budget < 0 )
            col6 += '<p>Mittelansätze mit negativ veranschlagten Ausgaben sind im Rahmen der Ausführung des Haushaltsplans auszugleichen.</p>';

        if ( budget == 0 )
            col6 += '<p><a class="internal-link glossar" title="zum Glossar" href="/glossar.html#leertitel">Leertitel</a> ohne Dotierung. Buchungsstelle für mögliche, aber betragsmäßig nicht vorhersehbare Einnahmen und Ausgaben.</p>';

        if ( flex > 0 )
            col6 += '<p>Titel unterliegt der Flexibilisierung.</p>';

        col6 += '</div>';
        col6 += '</div>';

        return $(col6);

    },

    'updateTableSorter' : function() {

        // initialize local variables ###################################################
        var plg   = jQuery.fn.ppBundeshaushalt.tabledata;

        if ( $(plg.table).hasClass('sorted') ) {

            var sorting = [[0,1]];
            $(plg.table).trigger("update");
            $(plg.table).trigger("sorton", [sorting]);

        } else {

            $(plg.table).tablesorter({
                textExtraction: function(node) {
                    return $(node).text().replace(/\.|,/g, '');
                },
                sortList: [[0,1]]
            });

            $(plg.table).addClass('sorted');

        }
    }

};

$.tablesorter.language = {
    sortAsc      : 'Aufsteigend sortiert, ',
    sortDesc     : 'Absteigend sortiert, ',
    sortNone     : 'Keine Sortierung zugeordnet, ',
    sortDisabled : 'Sortierung ist deaktiviert',
    nextAsc      : 'aktiviere um aufsteigend zu sortieren',
    nextDesc     : 'aktiviere um absteigend zu sortieren',
    nextNone     : 'aktiviere um Sortierung zu aufzuheben'
};
