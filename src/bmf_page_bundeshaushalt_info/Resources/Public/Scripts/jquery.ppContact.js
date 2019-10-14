/**
 * jQuery Plugin for Contactarea
 * Pixelpark AG, 31.05.2012
 */
jQuery.fn.ppContact = function (options) {

    var fnDefaults = jQuery.extend({
        tElement: 'elementId'
    }, options );

    // var obj = null;

    return this.each(function() {

        obj = this;

        $(obj).click(function() {

            if ( $(fnDefaults.tElement).is(":visible") ) {
                $(fnDefaults.tElement).slideUp(250);
                $('#contact').attr("aria-expanded", "false");
            } else {
                $(fnDefaults.tElement).slideDown(250, function() {
                    // $('#feedbackForm').attr("tabindex",-1).focus();
                    $('#contact').focus().attr("aria-expanded", "true");

                });
            }
            return false;
        });

    });

}
