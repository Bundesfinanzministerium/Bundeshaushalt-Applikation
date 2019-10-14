
jQuery.fn.ppBundeshaushalt.unitnavigation = {

    // properties ########################################################
    'ul'          : '#data-unitNavigation ul',
    'piwikApp'    : 'Nav: Struktur',


    // methods ###########################################################
    'init' : function() {
    },

    'update' : function( requestAddress, dataCurrent ) {
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
        var plg = jQuery.fn.ppBundeshaushalt.unitnavigation, eL1, eL2, eL3;
        var glb = jQuery.fn.ppBundeshaushalt.globals;
        var lng = jQuery.fn.ppBundeshaushalt.language.de;

        // process #####################################################################################################
        if ( dataCurrent.u == 'group' ) {
            eL1 = '<h3><strong class="group" aria-controls="bundeshaushalt-data">' + lng.unit['groups'] + '</strong></h3>';
            eL2 = '<h3><a href="' + glb.url({'y':dataCurrent.y,'q':dataCurrent.q,'a':dataCurrent.a,'u':'agency','d':''})   + '" class="epl" aria-controls="bundeshaushalt-data">' + lng.unit['agencys'] + '</a></h3>';
            eL3 = '<h3><a href="' + glb.url({'y':dataCurrent.y,'q':dataCurrent.q,'a':dataCurrent.a,'u':'function','d':''}) + '" class="fnk lft" aria-controls="bundeshaushalt-data">' + lng.unit['functions'] + '</a></h3>';
        }

        else if ( dataCurrent.u == 'agency' ) {
            eL1 = '<h3><a href="' + glb.url({'y':dataCurrent.y,'q':dataCurrent.q,'a':dataCurrent.a,'u':'group','d':''})    + '" class="grp" aria-controls="bundeshaushalt-data">' + lng.unit['groups'] + '</a></h3>';
            eL2 = '<h3><strong class="section" aria-controls="bundeshaushalt-data">' + lng.unit['agencys'] + '</strong></h3>';
            eL3 = '<h3><a href="' + glb.url({'y':dataCurrent.y,'q':dataCurrent.q,'a':dataCurrent.a,'u':'function','d':''}) + '.html" class="fnk lft" aria-controls="bundeshaushalt-data">' + lng.unit['functions'] + '</a></h3>';
        }

        else if ( dataCurrent.u == 'function' ) {
            eL1 = '<h3><a href="' + glb.url({'y':dataCurrent.y,'q':dataCurrent.q,'a':dataCurrent.a,'u':'group','d':''})  + '" class="grp rgt" aria-controls="bundeshaushalt-data">' + lng.unit['groups'] + '</a></h3>';
            eL2 = '<h3><a href="' + glb.url({'y':dataCurrent.y,'q':dataCurrent.q,'a':dataCurrent.a,'u':'agency','d':''}) + '" class="epl" aria-controls="bundeshaushalt-data">' + lng.unit['agencys'] + '</a></h3>';
            eL3 = '<h3><strong class="function" aria-controls="bundeshaushalt-data">' + lng.unit['functions'] + '</strong></h3>';
        }

        $(plg.ul + ' li.group').html(eL1);
        $(plg.ul + ' li.section').html(eL2);
        $(plg.ul + ' li.function').html(eL3);

        $(plg.ul + ' li a.grp').click(function(){ requestAddress({'y':dataCurrent.y, 'q':dataCurrent.q, a:dataCurrent.a, u:'group',    d:''}, jQuery.fn.ppBundeshaushalt.unitnavigation.piwikApp); return false; });
        $(plg.ul + ' li a.epl').click(function(){ requestAddress({'y':dataCurrent.y, 'q':dataCurrent.q, a:dataCurrent.a, u:'agency',   d:''}, jQuery.fn.ppBundeshaushalt.unitnavigation.piwikApp); return false; });
        $(plg.ul + ' li a.fnk').click(function(){ requestAddress({'y':dataCurrent.y, 'q':dataCurrent.q, a:dataCurrent.a, u:'function', d:''}, jQuery.fn.ppBundeshaushalt.unitnavigation.piwikApp); return false; });

    }

};
