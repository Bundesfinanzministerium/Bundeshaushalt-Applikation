jQuery.fn.ppBundeshaushalt.globals = {

    'animObject'     : null,
    'animElements'   : new Array(),
    'animInterval'   : 50,
    'animFades'      : {
        'jQ' : {
            'i' : 350,
            'o' : 250
        },
        'Rf': {
            'i' : 150,
            'o' : 150
        }
    },

    // jQuery.fn.ppBundeshaushalt.globals.tracking
    'tracking'    : false,

    'pathSegment' : {

        'account' : {
            'e' : 'einnahmen',
            'a' : 'ausgaben'
        },

        'unit' : {
            'agency'   : 'einzelplan',
            'group'    : 'gruppe',
            'function' : 'funktion'
        }

    },

    'labelLength' : 70,

    'animAddElement' : function( object ) {
        jQuery.fn.ppBundeshaushalt.globals.animElements.push(object);
    },

    'animStart' : function() {

        if ( jQuery.fn.ppBundeshaushalt.globals.animObject == null && jQuery.fn.ppBundeshaushalt.globals.animElements.length > 0 )
            jQuery.fn.ppBundeshaushalt.globals.animObject = setInterval('jQuery.fn.ppBundeshaushalt.globals.animRotate()', jQuery.fn.ppBundeshaushalt.globals.animInterval);

    },

    'animRotate' : function() {

        var plg     = jQuery.fn.ppBundeshaushalt.globals;
        var element = jQuery.fn.ppBundeshaushalt.globals.animElements.shift();

        for (var i = 0; i < element.length; i++) {

            var sub = element[i];

            if ( sub.t == 'jQ' ) {

                if ( sub.f == 'dO' ) $(sub.e).hide().remove();
                else if ( sub.f == 'dI' ) $(sub.e).show();
                else if ( sub.f == 'fI' ) $(sub.e).fadeIn(250);
                else if ( sub.f == 'fO' ) $(sub.e).fadeOut(250).remove();

            } else if ( sub.t == 'Rf' ) {

                if ( sub.f == 'fI' )        // fade In
                    sub.e.animate({'fill-opacity':1, 'stroke-opacity':1}, plg.animFades.Rf.i, 'linear');

                else if ( sub.f == 'fO' )   // fade Out
                    sub.e.animate({'fill-opacity':0, 'stroke-opacity':0}, plg.animFades.Rf.o, 'linear').remove();

                else if ( sub.f == 'rS' )   // remove Segmentset
                    sub.e.animate({'opacity':0}, plg.animFades.Rf.i + 100, 'linear', function() {
                        this.remove();
                    });

            } else if ( sub.t == 'updateTableSorter' ) {
                window.setTimeout("jQuery.fn.ppBundeshaushalt.tabledata.updateTableSorter()", 300);

            }

        }

        if ( jQuery.fn.ppBundeshaushalt.globals.animElements.length  <= 0 ) {
            window.clearInterval(jQuery.fn.ppBundeshaushalt.globals.animObject);
            jQuery.fn.ppBundeshaushalt.globals.animObject = null;
        }

    },

    'url' : function( dataCurrent, address ) {

        // initialize ##################################################################################################
        var plg      = jQuery.fn.ppBundeshaushalt.globals, url = '';
        var year     = typeof(dataCurrent.y) == 'undefined' ? '' : dataCurrent.y;
        var quota    = typeof(dataCurrent.q) == 'undefined' ? '' : dataCurrent.q;
        var account  = typeof(dataCurrent.a) == 'undefined' ? '' : dataCurrent.a;
        var unit     = typeof(dataCurrent.u) == 'undefined' ? '' : dataCurrent.u;
        var addresse = typeof(address)       == 'undefined' ? '' : address;

        // year #######################################
        if ( year != '' && year != 'blank' )
            url += (url==''?'/':'/') + dataCurrent.y;

        // quota ######################################
        if ( quota != '' && quota != 'blank' )
            url += (url==''?'':'/') + dataCurrent.q;

        // account ####################################
        if ( account != '' && account != 'blank' )
            url += (url==''?'':'/') + plg.pathSegment.account[dataCurrent.a];

        // unit #######################################
        if ( unit != '' && unit != 'blank' )
            url += (url==''?'':'/') + plg.pathSegment.unit[dataCurrent.u];

        // address ####################################
        if ( addresse != '' && addresse != 'blank' )
            url += (url==''?'':'/') + address;

        return  url == '' ? '/' : url + '.html';

    },

    'crop' : function(arg, length) {

        var len = typeof(length) == 'undefined' ? this.labelLength : length;

        if ( arg.length > len )
            arg = arg.substr(0,len) + '...';

        return arg;

    },

    'checkDataObject' : function(dataObject, dataCurrent) {

        if ( typeof(dataCurrent.y) == 'undefined' )                             return false;
        if ( typeof(dataCurrent.q) == 'undefined' )                             return false;


        if ( typeof(dataCurrent.a) == 'undefined' )                             return false;
        if ( dataCurrent.a != 'a' &&
             dataCurrent.a != 'e'  )                                            return false;

        if ( typeof(dataCurrent.u) == 'undefined' )                             return false;
        if ( dataCurrent.u != 'agency' &&
            dataCurrent.u != 'group' &&
            dataCurrent.u != 'function'  )                                      return false;

        if ( typeof(dataObject[dataCurrent.y]) == 'undefined' )                                                 return false;
        if ( typeof(dataObject[dataCurrent.y][dataCurrent.q]) == 'undefined' )                                  return false;
        if ( typeof(dataObject[dataCurrent.y][dataCurrent.q][dataCurrent.a]) == 'undefined' )                   return false;
        if ( typeof(dataObject[dataCurrent.y][dataCurrent.q][dataCurrent.a][dataCurrent.u]) == 'undefined' )    return false;

        return true;

    },

    'piwik' : function( embed, application, current ) {

        if (!jQuery.fn.ppBundeshaushalt.globals.tracking) {
            return true;
        }

        if ( typeof(ppBh) != 'undefined' ) { if ( typeof(ppBh.piwik) != 'undefined' ) {
            ppBh.piwik.track([
                {'n' : 'Anwendungsart', 'v' : (parseInt(embed) == 1 ? 'Embed' : 'Hauptanwendung') },
                {'n' : 'Applikation',   'v' : application },
                {'n' : 'Adresse',       'v' : jQuery.fn.ppBundeshaushalt.globals.url(current, current.d) }
            ]);
        } }

    },

    'number_format' : function (number, decimals, dec_point, thousands_sep) {

        var exponent = "";
        var numberstr = number.toString ();
        var eindex = numberstr.indexOf ("e");
        if (eindex > -1)
        {
            exponent = numberstr.substring (eindex);
            number = parseFloat (numberstr.substring (0, eindex));
        }

        if (decimals != null)
        {
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
        if (decimals != null && decimals > 0)
        {
            for (i = fractional.length - 1, z = decimals; i < z; ++i)
                fractional += "0";
        }

        thousands_sep = (thousands_sep != dec_point || fractional.length == 0) ?
            thousands_sep : null;
        if (thousands_sep != null && thousands_sep != "")
        {
            for (i = integer.length - 3; i > 0; i -= 3)
                integer = integer.substring (0 , i) + thousands_sep + integer.substring (i);
        }

        return sign + integer + fractional + exponent;
    }

}