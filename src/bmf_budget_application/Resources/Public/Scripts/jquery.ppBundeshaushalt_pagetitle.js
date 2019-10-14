jQuery.fn.ppBundeshaushalt.pagetitle = {
    // properties ######################################################################################################
    _weHaveTouchedTheTitle: false,

    // methods #########################################################################################################
    init: function () {

    },

    update: function (dataCurrent, metaInfo, levelCur) {
        if (!metaInfo && this._weHaveTouchedTheTitle) {
            // We are not inside the app
            document.title = 'Die Struktur des Bundeshaushaltes - Bundeshaushalt-Info.de';
            return;
        }

        var title = 'Bundeshaushalt-Info.de: ';
        if (levelCur > 0) {
            switch (dataCurrent.u) {
                case 'agency':
                    title += 'Einzelplan ';
                    break;
                case 'function':
                    title += 'Funktion ';
                    break;
                case 'group':
                    title += 'Gruppe ';
                    break;
            }
            title += dataCurrent.y + ', ';
            title += (dataCurrent.q === 'ist' ? 'Ist' : 'Soll') + ' - ';
            title += (dataCurrent.a === 'a' ? 'Ausgabe' : 'Einnahme') + ' ';
            title += '#' + dataCurrent.d + ' - ' + metaInfo.l;
        } else {
            switch (dataCurrent.u) {
                case 'agency':
                    title += 'Einzelpläne ';
                    break;
                case 'function':
                    title += 'Funktionen ';
                    break;
                case 'group':
                    title += 'Gruppen ';
                    break;
            }
            title += dataCurrent.y + ' - Übersicht ';
            title += (dataCurrent.q === 'ist' ? 'Ist' : 'Soll') + ' ';
            title += (dataCurrent.a === 'a' ? 'Ausgaben' : 'Einnahmen');
        }
        if (metaInfo) {
            document.getElementById('app-main-header').innerText = 'Aktueller Posten: ' + metaInfo.l;
            this._weHaveTouchedTheTitle = true;
            document.title = title;
        }
    }
};
