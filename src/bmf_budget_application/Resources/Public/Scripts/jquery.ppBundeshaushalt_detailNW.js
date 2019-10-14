jQuery.fn.ppBundeshaushalt.detailnw = {

    // methods #########################################################################################################
    'init' : function( current ) {
        var plg  = jQuery.fn.ppBundeshaushalt.detailnw;
        var link = plg.buildLink( current );
        $('#detail a').attr('href', link);
    },

    'update' : function( current ) {
        var plg  = jQuery.fn.ppBundeshaushalt.detailnw;
        var link = plg.buildLink( current );
        $('#detail a').attr('href', link);
    },

    'buildLink' : function ( current ) {

        var glb = jQuery.fn.ppBundeshaushalt.globals, link = '';

        if ( typeof(current.y) == 'undefined' ) current.y = '';
        if ( typeof(current.q) == 'undefined' ) current.q = '';
        if ( typeof(current.a) == 'undefined' ) current.a = '';
        if ( typeof(current.u) == 'undefined' ) current.u = '';
        if ( typeof(current.d) == 'undefined' ) current.d = '';

        if ( current.y != '' )
            link += (link==''?'':'/') + current.y;

        if ( current.q != '' )
            link += (link==''?'':'/') + current.q;

        if ( current.a != '' )
            link += (link==''?'':'/') + glb.pathSegment.account[current.a];

        if ( current.u != '' )
            link += (link==''?'':'/') + glb.pathSegment.unit[current.u];

        if ( current.d != '' )
            link += (link==''?'':'/') + current.d;

        link += (link == '' ? '/' : '.html');

        return 'http://' + txPpBundeshaushaltPreDomain + '/' + link;

    }

}