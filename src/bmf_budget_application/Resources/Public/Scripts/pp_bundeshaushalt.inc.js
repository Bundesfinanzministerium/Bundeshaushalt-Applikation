
jQuery.fn.ppBundeshaushalt = function(){};

var app                         = null;
var txPpBundeshaushalt          = txPpBundeshaushalt || {};
var txPpBundeshaushaltPreDomain = typeof(txPpBundeshaushaltPreDomain) == 'undefined' ? '' : txPpBundeshaushaltPreDomain;
var txPpBundeshaushaltStage     = typeof(txPpBundeshaushaltStage)     == 'undefined' ? '' : txPpBundeshaushaltStage;

txPpBundeshaushalt.divClass = 'tx-bmf-budget';

$(function() {

    $('div.' + txPpBundeshaushalt.divClass).ppBundeshaushalt_Main({
        service: {
            protocol:  window.location.protocol + '//',
            host:      txPpBundeshaushaltPreDomain,
            path:     'rest/'
        },
        width:      'auto',
        height:     'copy',
        interactive: true
    });

    if ( typeof(txPpBundeshaushaltRev) != 'undefined' )
        if ( txPpBundeshaushaltRev ) app = $('div.' + txPpBundeshaushalt.divClass).data()['ppBundeshaushalt.Main'];

});
