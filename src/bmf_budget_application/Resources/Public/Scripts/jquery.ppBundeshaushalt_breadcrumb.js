
jQuery.fn.ppBundeshaushalt.breadcrumb = {

    // properties ################################
    'ul'          : '#data-breadcrumb ul',
    'piwikApp'    : 'Nav: Breadcrumb',
    'labelLength' : 60,                         // max. size of labels before cropping


    // methods ###################################
    'init' : function( requestAddress ) {

        $(jQuery.fn.ppBundeshaushalt.breadcrumb.ul).html('');

        var eLi1 = $('<li>', { 'class' : 'home' }).append(
            $('<a>', {
                'title' : 'Zum Bundeshaushalt',
                'text'  : 'Bundeshaushalt',
                'href'  : '/bundeshaushalt.html',
                'click' : function() {
                    requestAddress({'y':'', 'q':'', 'a':'', 'u':'', 'd':''}, jQuery.fn.ppBundeshaushalt.breadcrumb.piwikApp);
                    return false;
            }})
        );

        $(jQuery.fn.ppBundeshaushalt.breadcrumb.ul).append(eLi1);
        $(jQuery.fn.ppBundeshaushalt.breadcrumb.ul).append($('<li>',{'class':'year'}));
        $(jQuery.fn.ppBundeshaushalt.breadcrumb.ul).append($('<li>',{'class':'account'}));
        $(jQuery.fn.ppBundeshaushalt.breadcrumb.ul).append($('<li>',{'class':'flow'}));
        $(jQuery.fn.ppBundeshaushalt.breadcrumb.ul).append($('<li>',{'class':'structure'}));
        $(jQuery.fn.ppBundeshaushalt.breadcrumb.ul).append($('<li>',{'class':'level1'}));

    },

    'update' : function( requestAddress, dataCurrent, dataObject, dataActive, levelCur, levelMax, defaults, avail ) {

        var glb = jQuery.fn.ppBundeshaushalt.globals;
        var lng = jQuery.fn.ppBundeshaushalt.language.de;

        if ( !glb.checkDataObject(dataObject, dataCurrent) ) {
            return false;
        }

        dO = dataObject[dataCurrent.y][dataCurrent.q][dataCurrent.a][dataCurrent.u];

        var chkY = $(jQuery.fn.ppBundeshaushalt.breadcrumb.ul + ' li.year strong').text()  != dataCurrent.y;
        var chkQ = $(jQuery.fn.ppBundeshaushalt.breadcrumb.ul + ' li.account strong').text() != lng.quota[dataCurrent.q];
        var chkA = $(jQuery.fn.ppBundeshaushalt.breadcrumb.ul + ' li.flow strong').text() != lng.account[dataCurrent.a];
        var chkU = $(jQuery.fn.ppBundeshaushalt.breadcrumb.ul + ' li.structure strong').text() != lng.unit[dataCurrent.u];


        // year ########################################################################################################
        // #############################################################################################################
        if ( (chkY || chkQ || chkA || chkU) || true) {

            var url = '';
            var act = '';
            var ul  = $('<ul>');

            $.each(avail.year, function( key, value ) {

                url = glb.url({ 'y' : value,
                                'q' : dataCurrent.q,
                                'a' : dataCurrent.a,
                                'u' : dataCurrent.u,
                                'd' : ''
                }, dataCurrent.d);

                if ( value == dataCurrent.y ) {

                    // active segment ####################################
                    act = $('<strong>').append($('<a>',{ 'title' : value,
                                                         'text'  : value,
                                                         'href'  : url,
                                                         'click' : function() {
                                                             requestAddress({ 'y' : value,
                                                                              'q' : dataCurrent.q,
                                                                              'a' : dataCurrent.a,
                                                                              'u' : dataCurrent.u,
                                                                              'd' : dataCurrent.d
                                                             }, jQuery.fn.ppBundeshaushalt.breadcrumb.piwikApp);
                                                             return false;
                                                         }
                    }));

                } else {

                    // inactive segment ##################################
                    if (jQuery.inArray(dataCurrent.q,avail.relYearQuota[value]) >= 0) {
                        var quota = dataCurrent.q;
                    } else {
                        var quota = avail.relYearQuota[value][0];
                        url       = glb.url({ 'y' : value,
                                              'q' : avail.relYearQuota[value][0],
                                              'a' : dataCurrent.a,
                                              'u' : dataCurrent.u,
                                              'd' : dataCurrent.d
                        }, dataCurrent.d);
                    }

                    ul.append(
                        $('<li>').append($('<a>',{ 'title' : value,
                                                   'text'  : value,
                                                   'href'  : url,
                                                   'click' : function() {
                                                       requestAddress({ 'y' : value,
                                                                        'q' : quota,
                                                                        'a' : dataCurrent.a,
                                                                        'u' : dataCurrent.u,
                                                                        'd' : dataCurrent.d
                                                       }, jQuery.fn.ppBundeshaushalt.breadcrumb.piwikApp);
                                                       return false;
                                                   }
                        }))
                    );
                }
            });

            $(jQuery.fn.ppBundeshaushalt.breadcrumb.ul + ' li.year').html('');
            $(jQuery.fn.ppBundeshaushalt.breadcrumb.ul + ' li.year').append(act);
            $(jQuery.fn.ppBundeshaushalt.breadcrumb.ul + ' li.year').append(ul);

        }


        // quota #######################################################################################################
        // #############################################################################################################
        if ( avail.relYearQuota[dataCurrent.y].length > 1 ) {
            if ( (chkY || chkQ || chkA || chkU) || true ) {
                var url = '';
                var act = '';
                var ul  = $('<ul>');

                $.each(avail.quota, function( key, value ) {

                    url = glb.url({ 'y' : dataCurrent.y,
                                    'q' : value,
                                    'a' : dataCurrent.a,
                                    'u' : dataCurrent.u,
                                    'd' : ''
                    }, dataCurrent.d);

                    if ( value == dataCurrent.q ) {

                        // active segment ####################################
                        act = $('<strong>').append($('<a>',{ 'title' : lng.quota[value],
                                                             'text'  : lng.quota[value],
                                                             'href'  : url,
                                                             'click' : function() {
                                                                 requestAddress({ 'y' : dataCurrent.y,
                                                                                  'q' : value,
                                                                                  'a' : dataCurrent.a,
                                                                                  'u' : dataCurrent.u,
                                                                                  'd' : dataCurrent.d
                                                                 }, jQuery.fn.ppBundeshaushalt.breadcrumb.piwikApp);
                                                                 return false;
                                                             }
                        }));

                    } else {

                        // inactive segment ##################################
                        ul.append(
                            $('<li>').append($('<a>',{ 'title' : lng.quota[value],
                                                       'text'  : lng.quota[value],
                                                       'href'  : url,
                                                       'click' : function() {
                                                           requestAddress({ 'y' : dataCurrent.y,
                                                                            'q' : value,
                                                                            'a' : dataCurrent.a,
                                                                            'u' : dataCurrent.u,
                                                                            'd' : dataCurrent.d
                                                           }, jQuery.fn.ppBundeshaushalt.breadcrumb.piwikApp);
                                                           return false;
                                                       }
                            }))
                        );
                    }
                });

                $(jQuery.fn.ppBundeshaushalt.breadcrumb.ul + ' li.account').html('');
                $(jQuery.fn.ppBundeshaushalt.breadcrumb.ul + ' li.account').append(act);
                $(jQuery.fn.ppBundeshaushalt.breadcrumb.ul + ' li.account').append(ul);
            }
        } else {
            $(jQuery.fn.ppBundeshaushalt.breadcrumb.ul + ' li.account').html('');
        }


        // account #####################################################################################################
        // #############################################################################################################
        if ( chkY || chkQ || chkA || chkU ) {

            var url = '';
            var act = '';
            var ul  = $('<ul>');

            $.each(avail.account, function( key, value ) {

                url = glb.url({ 'y' : dataCurrent.y,
                                'q' : dataCurrent.q,
                                'a' : value,
                                'u' : dataCurrent.u,
                                'd' : ''
                });

                if ( value == dataCurrent.a ) {

                    // active segment ####################################
                    act = $('<strong>').append($('<a>',{ 'title' : lng.account[value],
                                                         'text'  : lng.account[value],
                                                         'href'  : url,
                                                         'click' : function() {
                                                             requestAddress({ 'y' : dataCurrent.y,
                                                                              'q' : dataCurrent.q,
                                                                              'a' : value,
                                                                              'u' : dataCurrent.u,
                                                                              'd' : ''
                                                             }, jQuery.fn.ppBundeshaushalt.breadcrumb.piwikApp);
                                                             return false;
                                                         }
                    }));

                } else {

                    // inactive segment ##################################
                    ul.append(
                        $('<li>').append($('<a>',{ 'title' : lng.account[value],
                                                   'text'  : lng.account[value],
                                                   'href'  : url,
                                                   'click' : function() {
                                                       requestAddress({ 'y' : dataCurrent.y,
                                                                        'q' : dataCurrent.q,
                                                                        'a' : value,
                                                                        'u' : dataCurrent.u,
                                                                        'd' : ''
                                                       }, jQuery.fn.ppBundeshaushalt.breadcrumb.piwikApp);
                                                       return false;
                                                   }
                        }))
                    );
                }
            });

            $(jQuery.fn.ppBundeshaushalt.breadcrumb.ul + ' li.flow').html('');
            $(jQuery.fn.ppBundeshaushalt.breadcrumb.ul + ' li.flow').append(act);
            $(jQuery.fn.ppBundeshaushalt.breadcrumb.ul + ' li.flow').append(ul);
        }


        // unit ########################################################################################################
        // #############################################################################################################
        if ( chkY || chkQ || chkA || chkU ) {

            var url = '';
            var act = '';
            var ul  = $('<ul>');

            $.each(avail.unit, function( key, value ) {

                url = glb.url({ 'y' : dataCurrent.y,
                    'q' : dataCurrent.q,
                    'a' : dataCurrent.a,
                    'u' : value,
                    'd' : ''
                });

                if ( value == dataCurrent.u ) {

                    // active segment ####################################
                    act = $('<strong>').append($('<a>',{ 'title' : lng.unit[value],
                                                         'text'  : lng.unit[value],
                                                         'href'  : url,
                                                         'click' : function() {
                                                             requestAddress({ 'y' : dataCurrent.y,
                                                                              'q' : dataCurrent.q,
                                                                              'a' : dataCurrent.a,
                                                                              'u' : value,
                                                                              'd' : ''
                                                             }, jQuery.fn.ppBundeshaushalt.breadcrumb.piwikApp);
                                                             return false;
                                                         }
                    }));

                } else {

                    // inactive segment ##################################
                    ul.append(
                        $('<li>').append($('<a>',{ 'title' : lng.unit[value],
                                                   'text'  : lng.unit[value],
                                                   'href'  : url,
                                                   'click' : function() {
                                                       requestAddress({ 'y' : dataCurrent.y,
                                                                        'q' : dataCurrent.q,
                                                                        'a' : dataCurrent.a,
                                                                        'u' : value,
                                                                        'd' : ''
                                                       }, jQuery.fn.ppBundeshaushalt.breadcrumb.piwikApp);
                                                       return false;
                                                   }
                        }))
                    );
                }
            });

            $(jQuery.fn.ppBundeshaushalt.breadcrumb.ul + ' li.structure').html('');
            $(jQuery.fn.ppBundeshaushalt.breadcrumb.ul + ' li.structure').append(act);
            $(jQuery.fn.ppBundeshaushalt.breadcrumb.ul + ' li.structure').append(ul);
        }


        // levels ######################################################################################################
        // #############################################################################################################
        var eLiMain = $('<li>', {'class':'level1'} );
        var eLiOrg  = $( jQuery.fn.ppBundeshaushalt.breadcrumb.ul + ' ul.breadcrumb .level1');
        var eLi1, eLi2, eLi3, eIn2, eIn3, eUl1;

        if ( typeof(dataActive[0]) != 'undefined' && 0 < levelMax ) {
            if ( typeof(dataActive[1]) == 'undefined' ) {
                eLi1 = $('<strong>');
            } else {
                eLi1 = $('<a>').attr('href', glb.url(dataCurrent, dataActive[0].a));
                eLi1.attr('title', dO[0][dataActive[0].a].label);
                eLi1.click(function(){
                    requestAddress({ y:dataCurrent.y,
                                     q:dataCurrent.q,
                                     a:dataCurrent.a,
                                     u:dataCurrent.u,
                                     d:dataActive[0].a
                    }, jQuery.fn.ppBundeshaushalt.breadcrumb.piwikApp);
                    return false;
                });
            }
            eLi1.text(glb.crop(dO[0][dataActive[0].a].label, jQuery.fn.ppBundeshaushalt.breadcrumb.labelLength));
            eLiMain.append(eLi1);
        }

        if ( typeof(dataActive[1]) != 'undefined' && 1 < levelMax ) {
            eUl1 = $('<ul>', {'class':'stay'});
            eLi2 = $('<li>', {'class':'level2'});
            if ( typeof(dataActive[2]) == 'undefined' ) {
                eIn2 = $('<strong>');
            } else {
                eIn2 = $('<a>').attr('href', glb.url(dataCurrent, dataActive[1].a));
                eIn2.attr('title', dO[1][dataActive[1].a].label);
                eIn2.click(function(){
                    requestAddress({ y:dataCurrent.y,
                                     q:dataCurrent.q,
                                     a:dataCurrent.a,
                                     u:dataCurrent.u,
                                     d:dataActive[1].a
                    }, jQuery.fn.ppBundeshaushalt.breadcrumb.piwikApp);
                    return false;
                });
            }
            eIn2.text(glb.crop(dO[1][dataActive[1].a].label, jQuery.fn.ppBundeshaushalt.breadcrumb.labelLength));
            eLi2.append(eIn2);
            eUl1.append(eLi2);
        }

        if ( typeof(dataActive[2]) != 'undefined' && 2 < levelMax ) {
            eLi3 = $('<li>', {'class':'level3'});
            if ( typeof(dataActive[3]) == 'undefined' ) {
                eIn3 = $('<strong>');
            } else {
                eIn3 = $('<a>').attr('href', glb.url(dataCurrent, dataActive[2].a));
                eIn3.attr('title', dO[2][dataActive[2].a].label);
                eIn3.click(function(){
                    requestAddress({ y:dataCurrent.y,
                                     q:dataCurrent.q,
                                     a:dataCurrent.a,
                                     u:dataCurrent.u,
                                     d:dataActive[2].a
                    }, jQuery.fn.ppBundeshaushalt.breadcrumb.piwikApp);
                    return false;
                });
            }
            eIn3.text(glb.crop(dO[2][dataActive[2].a].label, jQuery.fn.ppBundeshaushalt.breadcrumb.labelLength));
            eLi3.append(eIn3);
            eUl1.append(eLi3);
        }

        if ( typeof(eUl1) != 'undefined' )
            eLiMain.append(eUl1);

        $('ul.breadcrumb .level1').replaceWith(eLiMain);

        $('ul.breadcrumb').superfish({showArrows: false});

    }

};
