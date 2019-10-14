ppBh = {};

(function(ppBh) {

    function trackPiwik(obj) {

        $.each(obj, function( key, value ) {
            piwikTracker.setCustomVariable(key+1, value.n, value.v, 'page');
        });

        piwikTracker.trackPageView();

    };

    ppBh.piwik = {
        track : trackPiwik
    }

})(window.ppBh);
