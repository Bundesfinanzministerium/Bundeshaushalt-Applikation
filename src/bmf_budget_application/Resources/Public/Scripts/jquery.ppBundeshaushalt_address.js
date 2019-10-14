jQuery.fn.ppBundeshaushalt.address = {

    // properties ######################################################################################################

    // methods #########################################################################################################
    'init' : function( current, requestJSON, avail) {

        // initialize ####################################################
        var plg = jQuery.fn.ppBundeshaushalt.address;

        // change function ###############################################
        $.address.change(function(event) {

            // initialize ####################################################
            var temp = plg.status(current, avail);

            // check if temp is valid ########################################
            if ( temp.current.y == '' || temp.current.q == '' || temp.current.a == '' || temp.current.u == '' ) {
                return false;
            }
            requestJSON( temp.current );
        });

        return plg.status(current, avail);

    },

    'update' : function( dataCurrent ) {

        // initialize ####################################################
        var glb = jQuery.fn.ppBundeshaushalt.globals, hash = '';

        if ( typeof(dataCurrent.y) == 'undefined' ) dataCurrent.y = '';
        if ( typeof(dataCurrent.q) == 'undefined' ) dataCurrent.q = '';
        if ( typeof(dataCurrent.a) == 'undefined' ) dataCurrent.a = '';
        if ( typeof(dataCurrent.u) == 'undefined' ) dataCurrent.u = '';
        if ( typeof(dataCurrent.d) == 'undefined' ) dataCurrent.d = '';

        if ( dataCurrent.y != '' )
            hash += (hash==''?'':'/') + dataCurrent.y;

        if ( dataCurrent.q != '' )
            hash += (hash==''?'':'/') + dataCurrent.q;

        if ( dataCurrent.a != '' )
            hash += (hash==''?'':'/') + glb.pathSegment.account[dataCurrent.a];

        if ( dataCurrent.u != '' )
            hash += (hash==''?'':'/') + glb.pathSegment.unit[dataCurrent.u];

        if ( dataCurrent.d != '' )
            hash += (hash==''?'':'/') + dataCurrent.d;

        hash += (hash == '' ? '/' : '.html');

        $.address.value(hash);

    },


    'status' : function(current, avail) {

        // initialize ##################################################################################################
        var glb = jQuery.fn.ppBundeshaushalt.globals, hash = '';
        var pathSegments = $.address.pathNames();   // path as array

        if ( pathSegments.length == 0 ) {
            pathSegments[0] = current.y;
            pathSegments[1] = current.q;
            pathSegments[2] = glb.pathSegment.account[current.a];
            pathSegments[3] = glb.pathSegment.unit[current.u];
            pathSegments[4] = current.d;
        }

        var retLevel = 0;                                                                           // init default return value Level
        var yer = typeof(pathSegments[0]) != 'undefined' ? pathSegments[0] : '';                    // year information of path segment
        var qut = typeof(pathSegments[1]) != 'undefined' ? pathSegments[1] : '';                    // quota information of path segment
        var acc = typeof(pathSegments[2]) != 'undefined' ? pathSegments[2] : '';                    // account information of path segment
        var unt = typeof(pathSegments[3]) != 'undefined' ? pathSegments[3] : '';                    // unit information of path segment
        var add = typeof(pathSegments[4]) != 'undefined' ? pathSegments[4].match(/^[0-9]*/g) : '';  // address information of path segment

        var retValues = {                           // init default return object
            y : 'blank',                            //      account
            q : 'blank',                            //      account
            a : 'blank',                            //      account
            u : 'blank',                            //      unit
            d : ''                                  //      address
        };

        // check if values are valid
        add = add.length > 0 ? add[0] : '';

        if ( jQuery.inArray(yer, avail.year)  >= 0 )
            retValues.y = yer;

        if ( jQuery.inArray(qut, avail.quota) >= 0 )
            retValues.q = qut;

        // get account ###################################################
        if ( acc.indexOf(glb.pathSegment.account.e) >= 0 ) retValues.a = 'e';
        if ( acc.indexOf(glb.pathSegment.account.a) >= 0 ) retValues.a = 'a';

        // get unit and level ############################################
        if ( unt.indexOf(glb.pathSegment.unit['agency']) >= 0 && retValues.a != '' ) {
            retValues.u = 'agency';
            if ( add != '' ) {
                if ( add.length == 2 ) { retLevel = 1; retValues.d = add; }
                if ( add.length == 4 ) { retLevel = 2; retValues.d = add; }
                if ( add.length == 9 ) { retLevel = 3; retValues.d = add; }
            }
        }

        else if ( unt.indexOf(glb.pathSegment.unit['function']) >= 0 && retValues.a != '' ) {
            retValues.u = 'function';
            if ( add != '' ) {
                if ( add.length == 1 ) { retLevel = 1; retValues.d = add; }
                if ( add.length == 2 ) { retLevel = 2; retValues.d = add; }
                if ( add.length == 3 ) { retLevel = 3; retValues.d = add; }
                if ( add.length == 9 ) { retLevel = 4; retValues.d = add; }
            }
        }

        else if ( unt.indexOf(glb.pathSegment.unit['group']) >= 0 && retValues.a != '' ) {
            retValues.u = 'group';
            if ( add != '' ) {
                if ( add.length == 1 ) { retLevel = 1; retValues.d = add; }
                if ( add.length == 2 ) { retLevel = 2; retValues.d = add; }
                if ( add.length == 3 ) { retLevel = 3; retValues.d = add; }
                if ( add.length == 9 ) { retLevel = 4; retValues.d = add; }
            }
        }

        return { 'current' : retValues, 'level' : retLevel};

    }

}
