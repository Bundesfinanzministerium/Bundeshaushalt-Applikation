$(function() {

    $('#sr-navigation.srHome a.inhalt').click(function(){
        $('#main').attr("tabindex",-1).focus();
        return false;
    });

    $('#sr-navigation.srArticle a.inhalt').click(function(){
        $('#page-title').attr("tabindex",-1).focus();
        return false;
    });
    $('#sr-navigation.srArticle a.menu').click(function(){
        $('#nav-main').attr("tabindex",-1).focus();
        return false;
    });

    $('ul.sf-menu').supersubs({
        minWidth:    12,   // minimum width of sub-menus in em units
        maxWidth:    27,   // maximum width of sub-menus in em units
        extraWidth:  1     // extra width can ensure lines don't sometimes turn over
        // due to slight rounding differences and font-family
    }).superfish();

    $('#main-menu').smartmenus();

    $('button.navbar-toggler').click(function(){
        $(this).toggleClass('active').toggleAttr('aria-expanded', 'true', 'false')
            .next().toggleClass('collapse').toggleAttr('aria-expanded', 'true', 'false').slideToggle( "slow", function() {
            // Animation complete.
        });
    });

    $('ul.breadcrumb').superfish({
        showArrows: false
    });

    if ($('.slick-wrapper').length) {
        syncSlider();
    }

    $('.glossar dt').ppGlossar();

    $('#feedbackSwitch').ppContact({
        tElement:'#contact'
    });

    $('#bundeshaushalt-data th.col1').prepend('<span class="sortarrow" title="Sortieren"></span>');
    $('#bundeshaushalt-data th.col2 br').replaceWith('<span class="sortarrow" title="Sortieren"></span><br />');
    $('#bundeshaushalt-data th.col3').prepend('<span class="sortarrow" title="Sortieren"></span>');

});
