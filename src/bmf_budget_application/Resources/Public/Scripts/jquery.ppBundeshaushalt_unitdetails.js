
jQuery.fn.ppBundeshaushalt.unitdetails = {

    // properties ################################
    'divInfo'     : '#unit-info',
    'divOverview' : '#unit-overview',
    'piwikApp'    : 'Nav: Titeldetail',

    html : {
        'address'  : '<p class="address">ID:</p>',
        'agency'   : '<div class="agency" style="display:none;"><h2>Einzelpläne</h2><p>Strukturieren Einnahmen und Ausgaben grundsätzlich nach Bundesministerien. Dazu kommen besondere Einzelpläne, wie die Bundesschuld.</p></div>',
        'group'    : '<div class="group" style="display:none;"><h2>Gruppen</h2><p>Strukturieren Einnahmen und Ausgaben nach dem ökonomischen Typ. Dies können z. B. Ausgaben für Personal oder Baumaßnahmen sein.</p></div>',
        'function' : '<div class="function" style="display:none;"><h2>Funktionen</h2><p>Strukturieren Einnahmen und Ausgaben nach dem Aufgaben­gebiet bzw. Politikbereich, für den sie bestimmt sind.</p></div>'
    },

    'fadeDur' : {
        'in'  : 500,
        'out' : 250
    },



    // methods ###########################################################
    init : function() {
    },

    update : function( requestAddress, dataCurrent, dataRelated, levelTitle ) {

        if ( typeof(dataCurrent.a) == 'undefined' ) {
            return false;
        }

        if ( typeof(dataCurrent.u) == 'undefined' ) {
            return false;
        }

        if ( dataCurrent.a == '' || dataCurrent.u == '' ) {
            return false;
        }

        // initialize ##################################################################################################
        var plg = jQuery.fn.ppBundeshaushalt.unitdetails;

        // unit information ##############################################
        if ( $( plg.divInfo + ' div.' + dataCurrent.u ).children().length == 0 ) {
            plg.infoUnit( dataCurrent );
        }

        // unit relations ################################################
        if ( !levelTitle ) {
            if ( $(plg.divOverview).is(":visible") ) {

                $(plg.divOverview).fadeOut( plg.fadeDur.out );

            }

        } else {
            plg.infoRelated( requestAddress, dataCurrent, dataRelated );
        }

    },

    infoUnit : function( dataCurrent ) {

        // initialize ##################################################################################################
        var plg = jQuery.fn.ppBundeshaushalt.unitdetails;

        $( plg.divInfo + ' div' ).fadeOut( plg.fadeDur.out ).remove();
        $( plg.divInfo ).html( plg.html.address + '' + plg.html[dataCurrent.u] );
        $( plg.divInfo + ' div' ).fadeIn( plg.fadeDur['in'] );

    },


    infoRelated : function( requestAddress, dataCurrent, dataRelated ) {

        // initialize ##################################################################################################
        var plg = jQuery.fn.ppBundeshaushalt.unitdetails, eLi1, eLi2;

        if ( dataCurrent.u == 'agency' ) {

            eLi1 = plg.relatedInfoFunction(requestAddress, dataCurrent, dataRelated['function']);
            eLi2 = plg.relatedInfoGroup(requestAddress, dataCurrent, dataRelated['group']);

        } else if ( dataCurrent.u == 'function' ) {

            eLi1 = plg.relatedInfoAgency(requestAddress, dataCurrent, dataRelated['agency']);
            eLi2 = plg.relatedInfoGroup(requestAddress, dataCurrent, dataRelated['group']);

        } else if ( dataCurrent.u == 'group' ) {

            eLi1 = plg.relatedInfoAgency(requestAddress, dataCurrent, dataRelated['agency']);
            eLi2 = plg.relatedInfoFunction(requestAddress, dataCurrent, dataRelated['function']);

        }

        if ( $(plg.divOverview).is(":visible") ) {

            $(plg.divOverview).fadeOut(plg.fadeDur['out'], function(){
                $(plg.divOverview).html('');
                $(plg.divOverview).append(eLi1);
                $(plg.divOverview).append(eLi2);
                $(plg.divOverview).fadeIn(plg.fadeDur['in']);
            });

        } else {

            $(plg.divOverview).html('');
            $(plg.divOverview).append(eLi1);
            $(plg.divOverview).append(eLi2);
            $(plg.divOverview).fadeIn(plg.fadeDur['in']);

        }

    },

    relatedInfoAgency : function( requestAddress, dataCurrent, dataRelated ) {

        // initialize ##################################################################################################
        var plg  = jQuery.fn.ppBundeshaushalt.unitdetails;
        var glb  = jQuery.fn.ppBundeshaushalt.globals;
        var lng  = jQuery.fn.ppBundeshaushalt.language.de;
        var cr   = {'y':dataCurrent.y, 'q':dataCurrent.q, 'a':dataCurrent.a, 'u':'agency', 'd':''};
        var html = '';

        // preparing html
        html += '<h2>' + lng.related['agency'] + '</h2>';
        html += '<ul>';
        html += '<li class="level1x"><a href="' + glb.url(cr,dataRelated[0].a) + '">' + dataRelated[0].l + '</a></li>';
        html += '<li class="level2"><a href="'  + glb.url(cr,dataRelated[1].a) + '">' + dataRelated[1].l + '</a></li>';
        html += '</ul>';

        // preparing events
        html  = $(html);
        $('.level1x a', html).click(function(){requestAddress({y:dataCurrent.y,q:dataCurrent.q,a:dataCurrent.a,u:'agency',d:dataRelated[0].a}, plg.piwikApp);return false;});
        $('.level2 a',  html).click(function(){requestAddress({y:dataCurrent.y,q:dataCurrent.q,a:dataCurrent.a,u:'agency',d:dataRelated[1].a}, plg.piwikApp);return false;});

        return html;

    },

    relatedInfoFunction : function( requestAddress, dataCurrent, dataRelated ) {

        // initialize ##################################################################################################
        var plg  = jQuery.fn.ppBundeshaushalt.unitdetails;
        var glb  = jQuery.fn.ppBundeshaushalt.globals;
        var lng  = jQuery.fn.ppBundeshaushalt.language.de;
        var cr   = {'y':dataCurrent.y, 'q':dataCurrent.q, 'a':dataCurrent.a, 'u':'function', 'd':''};
        var html = '';

        // preparing html
        html += '<h2>' + lng.related['function'] + '</h2>';
        html += '<ul>';
        html += '<li class="level1x"><a href="' + glb.url(cr,dataRelated[0].a) + '">' + dataRelated[0].l + '</a></li>';
        html += '<li class="level2x"><a href="' + glb.url(cr,dataRelated[1].a) + '">' + dataRelated[1].l + '</a></li>';
        html += '<li class="level3"><a href="'  + glb.url(cr,dataRelated[2].a) + '">' + dataRelated[2].l + '</a></li>';
        html += '</ul>';

        // preparing events
        html  = $(html);
        $('.level1x a', html).click(function(){requestAddress({y:dataCurrent.y,q:dataCurrent.q,a:dataCurrent.a,u:'function',d:dataRelated[0].a}, plg.piwikApp);return false;});
        $('.level2x a', html).click(function(){requestAddress({y:dataCurrent.y,q:dataCurrent.q,a:dataCurrent.a,u:'function',d:dataRelated[1].a}, plg.piwikApp);return false;});
        $('.level3 a',  html).click(function(){requestAddress({y:dataCurrent.y,q:dataCurrent.q,a:dataCurrent.a,u:'function',d:dataRelated[2].a}, plg.piwikApp);return false;});

        return html;

    },

    relatedInfoGroup : function( requestAddress, dataCurrent, dataRelated ) {

        // initialize ##################################################################################################
        var plg  = jQuery.fn.ppBundeshaushalt.unitdetails;
        var glb  = jQuery.fn.ppBundeshaushalt.globals;
        var lng  = jQuery.fn.ppBundeshaushalt.language.de;
        var cr   = {'y':dataCurrent.y, 'q':dataCurrent.q, 'a':dataCurrent.a, 'u':'group', 'd':''};
        var html = '';

        // preparing html
        html += '<h2>' + lng.related['group'] + '</h2>';
        html += '<ul>';
        html += '<li class="level1x"><a href="' + glb.url(cr,dataRelated[0].a) + '">' + dataRelated[0].l + '</a></li>';
        html += '<li class="level2x"><a href="' + glb.url(cr,dataRelated[1].a) + '">' + dataRelated[1].l + '</a></li>';
        html += '<li class="level3"><a href="'  + glb.url(cr,dataRelated[2].a) + '">' + dataRelated[2].l + '</a></li>';
        html += '</ul>';

        // preparing events
        html  = $(html);
        $('.level1x a', html).click(function(){requestAddress({y:dataCurrent.y,q:dataCurrent.q,a:dataCurrent.a,u:'group',d:dataRelated[0].a}, plg.piwikApp);return false;});
        $('.level2x a', html).click(function(){requestAddress({y:dataCurrent.y,q:dataCurrent.q,a:dataCurrent.a,u:'group',d:dataRelated[1].a}, plg.piwikApp);return false;});
        $('.level3 a',  html).click(function(){requestAddress({y:dataCurrent.y,q:dataCurrent.q,a:dataCurrent.a,u:'group',d:dataRelated[2].a}, plg.piwikApp);return false;});

        return html;

    }

}
