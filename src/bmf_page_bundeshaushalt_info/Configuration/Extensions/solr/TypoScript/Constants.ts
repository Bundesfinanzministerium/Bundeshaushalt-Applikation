<INCLUDE_TYPOSCRIPT: source="FILE:EXT:solr/Configuration/TypoScript/Solr/constants.txt">

plugin.tx_solr {
	view {
		templateRootPath = EXT:bmf_page_bundeshaushalt_info/Resources/Private/Solr/Templates
		partialRootPath = EXT:bmf_page_bundeshaushalt_info/Resources/Private/Solr/Partials
		layoutRootPath = EXT:bmf_page_bundeshaushalt_info/Resources/Private/Solr/Layouts
	}
	solr {
		scheme = http
		host = localhost
		port = 8983
		path = /solr/german/
	}
	search {
        results {
            resultsPerPage = 20
        }
    }
}
