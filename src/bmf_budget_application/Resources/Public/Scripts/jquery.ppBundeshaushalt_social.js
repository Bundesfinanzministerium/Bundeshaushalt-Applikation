jQuery.fn.ppBundeshaushalt.social = {

    'services': {
        'twitter' : window.location.protocol + '//twitter.com/home?status=[URL]',
        'google' : window.location.protocol + '//plus.google.com/share?url=[URL]',
        'facebook' : window.location.protocol + '//www.facebook.com/sharer/sharer.php?u=[URL]',
        'email' : 'mailto:Bitte%20Empfaenger%20eintragen?subject=Seitenempfehlung%20aus%20dem%20www.bundeshaushalt-info.de&body=Folgender%20Link%20zum%20Bundeshaushalt%20des%20Bundesministeriums%20der%20Finanzen%20wurde%20Ihnen%20empfohlen.%0D%0A%0D%0ADen%20vollstaendigen%20Inhalt%20finden%20Sie%20unter%20der%20URL:%0D%0A%0D%0A[URL]%0D%0A%0D%0AHinweis:%20Das%20BMF%20als%20Betreiber%20dieser%20Webseite%20uebernimmt%20keine%20Verantwortung%20fuer%20den%20Inhalt%20dieser%20Mitteilung.%20Die%20Korrektheit%20des%20angegebenen%20Namens%20und/oder%20der%20angegebenen%20E-Mail%20Adresse%20des%20Absenders%20kann%20nicht%20garantiert%20werden.'
    },

    // methods #########################################################################################################
    'init': function (current) {

    },

    'update': function (current) {

        var glb = jQuery.fn.ppBundeshaushalt.globals,
            url = window.location.protocol + '//' + txPpBundeshaushaltPreDomain + glb.url(current, current.d);

        for ( var element in jQuery.fn.ppBundeshaushalt.social.services ) {
            $('#socialbar a.' + element).attr('href', jQuery.fn.ppBundeshaushalt.social.services[element].replace("[URL]", url));
        }

    }

};