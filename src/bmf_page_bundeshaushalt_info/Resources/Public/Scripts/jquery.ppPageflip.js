/**
 * jQuery Plugin for Pageflip
 * Pixelpark AG, 31.05.2012
 */
jQuery.fn.ppPageflip = function (options) {

    var fnDefaults = jQuery.extend({
        selElement: 'a',
        nrmWidth: '50px',
        nrmHeight: '52px',
        nrmDuration: 250,
        ovrWidth: '307px',
        ovrHeight: '317px',
        ovrDuration: 250
    }, options );

    var obj = null;

    return this.each(function() {

        obj = $(fnDefaults.selElement, this);

        $(obj).hover(hoverIn, hoverOut);

    });

    function hoverIn() {
        $(obj).stop();
        $(obj).animate({
            width: fnDefaults.ovrWidth,
            height: fnDefaults.ovrHeight
        }, fnDefaults.ovrDuration);
    }

    function hoverOut() {
        $(obj).stop();
        $(obj).animate({
            width: fnDefaults.nrmWidth,
            height: fnDefaults.nrmHeight
        }, fnDefaults.nrmDuration);
    }

}
