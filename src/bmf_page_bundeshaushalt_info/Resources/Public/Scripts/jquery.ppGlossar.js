/**
 * jQuery plugin for for glossar
 * Pixelpark AG, 21.06.2012
 */

jQuery.fn.ppGlossar = function (options) {
    return this.each(function() {
        $(this).html('<a href="#">' + $(this).text() + '</a>');
        $('a', this).click(function() {
            $(this).parent().toggleClass('dtOpened').next().slideToggle("fast");
            return false;
        });
    });
};
