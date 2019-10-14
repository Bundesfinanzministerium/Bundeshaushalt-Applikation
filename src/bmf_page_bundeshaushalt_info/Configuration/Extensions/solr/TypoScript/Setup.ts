<INCLUDE_TYPOSCRIPT: source="FILE:EXT:solr/Configuration/TypoScript/Solr/setup.txt">

plugin.tx_solr {
    _LOCAL_LANG.de {
        results_searched_for = Gesucht nach ""@searchWord""
        noresults_searched_for = Nichts gefunden für ""@searchWord""
        results_range = Zeige Ergebnisse <strong>@resultsFrom bis @resultsTo</strong> von @resultsTotal.
    }

    index {
        queue.pages.indexer.frontendDataHelper {
            scheme = https
        }
    }
    search.results {
        resultsHighlighting = 1
    }
}
