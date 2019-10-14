var syncSlider;
syncSlider = function () {
    $('.slick-wrapper').each(function () {

        var randId = Math.ceil(Math.random() * 100000);
        var sliderID = "slider" + randId;



        $(this).on('init', function(event, slick){
            if((slick.$slides.length) > 1) {
                var slickLast = $('ul.slick-track > li:last-child');
                var slickArrowLast = $(this).find('.slick-arrow:last-child');
                if (slickLast.hasClass('slick-current')) {
                    slickArrowLast.addClass('slick-off');
                }
            }
        });

        $(this).on('afterChange', function(event, slick){
            if((slick.$slides.length) > 1) {
                var slickLast = $('ul.slick-track > li:last-child');
                var slickArrowLast = $(this).find('.slick-arrow:last-child');
                if (slickLast.hasClass('slick-current')) {
                    slickArrowLast.addClass('slick-off').attr('aria-disabled', 'true');
                } else if (slickLast.hasClass('slick-slide')) {
                    slickArrowLast.removeClass('slick-off').attr('aria-disabled', 'false');
                }
                var slickFirst = $('ul.slick-track > li:first-child');
                var slickArrowFirst = $(this).find('.slick-arrow:first-child');
                if (slickFirst.hasClass('slick-current')) {
                    slickArrowFirst.addClass('slick-off').attr('aria-disabled', 'true');;
                } else if (slickFirst.hasClass('slick-slide')) {
                    slickArrowFirst.removeClass('slick-off').attr('aria-disabled', 'false');
                }
            }
        });

        var amountSlides = $(this).find('.slider-nav > li').length;
        $(this).find('.slider-nav').attr('id', sliderID).slick({
            initialSlide: amountSlides - 1,
            slidesToShow: 3,
            slidesToScroll: 1,
            slide: 'li',
            asNavFor: '.slider-for',
            prevArrow: '<li><button type="button" data-role="none" class="slick-prev" aria-label="Zurück" tabindex="0" role="button" title="Zurück">Zurück</button></li>',
            nextArrow: '<li><button type="button" data-role="none" class="slick-next" aria-label="Weiter" tabindex="0" role="button" title="Weiter">Weiter</button></li>',
            dots: false,
            centerMode: false,
            infinite: false,
            focusOnSelect: true
        });

        var randId2 = Math.ceil(Math.random() * 100000);
        var sliderID2 = "slider" + randId2;
        $(this).on('init', function(event, slick){
            if((slick.$slides.length) > 1) {}
        });

        var amountSlideTracks = $(this).find('.slick-track > li').length;
        $(this).find('.slider-for').attr('id', sliderID2).slick({
            initialSlide: amountSlideTracks - 1,
            slidesToShow: 1,
            slidesToScroll: 1,
            slide: 'li',
            arrows: false,
            fade: true,
            infinite: false,
            adaptiveHeight: true,
            asNavFor: '.slider-nav'
        });

    });

    $('.slider-nav h3 a').click(function(event) {
        event.preventDefault();
    });
};
