
jQuery.fn.ppBundeshaushalt = function(){};

var app                         = null;
var txPpBundeshaushalt          = txPpBundeshaushalt || {};
var txPpBundeshaushaltPreDomain = typeof(txPpBundeshaushaltPreDomain) == 'undefined' ? '' : txPpBundeshaushaltPreDomain;

txPpBundeshaushalt.divClass = 'tx-pp-bundeshaushalt';

$(function() {

    $('div.' + txPpBundeshaushalt.divClass).each(function() {

        var obj = this;
        var jP  = $.parseJSON($.trim($('script', obj).html()));

        $(obj).ppBundeshaushalt_Main({

            service: {
                protocol: window.location.protocol + '//',
                host:     txPpBundeshaushaltPreDomain,
                path:     'rest/'
            },

            width:      'auto',
            height:     'copy',
            interactive: true,

            embed:      1,
            year:       jP.jahr,
            quota:      jP.plan,
            account:    jP.typ,
            unit:       jP.struktur,
            address:    jP.adresse,

            showLevelStart  : jP.min,
            actLevelStart   : jP.delta,

            weiteres : {
                func : 'redirect',
                html : '<p>Weitere Posten kleiner als 1% finden Sie auf www.bundeshaushalt-info.de</p><p><em>Klick öffnet neues Fenster</em></p>'
            },

            eDivApplication : '#unit-detail'

        });

    });

});
