<?php
/*
 * This file is part of the "bmf_budget_export_rest_solr" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

if (file_exists('../../vendor/autoload.php')) {
    // When this extension is located in src/
    require_once '../../vendor/autoload.php';
} else {
    // When this extension is located in public/typo3conf/ext/ext_key
    require_once '../../../../vendor/autoload.php';
}

/*
 * pushfile for delivery of all rest endpoints (/rest/ and /rest-timeline/) via solr
 *
 * use the following config in .htaccess to activate:
 *
 * # If the file/symlink/directory does not exist => Redirect to index.php.
 * # For httpd.conf, you need to prefix each '%{REQUEST_FILENAME}' with '%{DOCUMENT_ROOT}'.
 * RewriteCond %{REQUEST_FILENAME} !-f
 * RewriteCond %{REQUEST_FILENAME} !-d
 * RewriteCond %{REQUEST_FILENAME} !-l
 * RewriteCond %{REQUEST_URI} !^/server-info
 * RewriteCond %{REQUEST_URI} !^/status
 * RewriteCond %{REQUEST_URI} !^/server-status
 * RewriteCond %{REQUEST_URI} !^/rest
 * RewriteRule ^.*$ %{ENV:CWD}index.php [QSA,L]
 *
 * RewriteCond %{REQUEST_URI} ^/rest
 * RewriteRule ^.*$ %{ENV:CWD}typo3conf/ext/bmf_budget_export_rest_solr/send_rest.php [QSA,L]
 *
 */

$requestedUri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);

$documentTitle = $requestedUri;

$translatedUri = $_SERVER['CONTEXT_DOCUMENT_ROOT'].$requestedUri;

$requestedUri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);

$documentTitle = $requestedUri;

$translatedUri = ltrim($requestedUri, '/');

$output = '';
$isJsonRepsonse = false;
if (substr($translatedUri, -13) === '/service.json') {
    $output = '{

    "defaults" : {

        "e" : {
            "year"    : "2019",
            "quota"   : "soll",
            "account" : "e",
            "unit"    : "agency",
            "address" : ""
        },

        "a" : {
            "year"    : "2019",
            "quota"   : "soll",
            "account" : "a",
            "unit"    : "agency",
            "address" : ""
        }

    },

    "avail" : {

        "year"    : ["2012", "2013", "2014","2015","2016","2017","2018","2019"],
        "quota"   : ["soll", "ist"],
        "account" : ["e", "a"],
        "unit"    : ["agency", "group", "function"],

        "relYearQuota" : {
            "2012" : ["soll", "ist"],
            "2013" : ["soll", "ist"],
            "2014" : ["soll", "ist"],
            "2015" : ["soll", "ist"],
            "2016" : ["soll", "ist"],
            "2017" : ["soll", "ist"],
            "2018" : ["soll", "ist"],
            "2019" : ["soll"]
        }

    }

}';
} else {
    $pathSegments = explode('/', $translatedUri);

    $solrDocumentType = 'tx_bmfbudgetexportrestsolr_export-rest';
    if ($pathSegments[0] === 'rest-timeline') {
        $solrDocumentType = 'tx_bmfbudgetexportrestsolr_export-rest-timeline';
        if (strlen(rtrim($pathSegments[count($pathSegments) - 1], '.html')) === 9) {
            $translatedUriPathSegments = $pathSegments;
            unset($translatedUriPathSegments[count($translatedUriPathSegments) - 2]);
            $translatedUri = implode('/', $translatedUriPathSegments);
        }
    } else {
        $translatedUri = rtrim($translatedUri, '.html');
        $translatedUri .= 'index.json';
    }

    $solrQuery = urlencode('filePublicUrl:'.\Apache_Solr_Service::escapePhrase($translatedUri).' AND '.
        'type:' . $solrDocumentType);
    $url = 'http://127.0.0.1:8983/solr/german/select?indent=on&q=' . $solrQuery . '&sort=indexed+desc&wt=json';

    try {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response);

        $output = $response->response->docs[0]->budgetData;
    } catch (\Exception $e) {
        header('HTTP/1.0 404 Not Found');
        echo 'Not Found';
        die();
    }
}

// only deliver files with a known filename
if ($output) {
    $documentTitleParts = [];
    $jsonResponseObject = json_decode($output);
    if (is_object($jsonResponseObject)) {
        $isJsonRepsonse = true;
        if (property_exists($jsonResponseObject, 'meta')) {
            $documentTitleParts[] = $jsonResponseObject->meta->unit;
        }
        if (property_exists($jsonResponseObject, 'detail')) {
            $documentTitleParts[] = $jsonResponseObject->detail->label;
        }
        if (count($documentTitleParts) == 2) {
            $documentTitle = implode(' | ', $documentTitleParts);
        }
    }
}

$piwikTracker = new \PPKOELN\BmfPageBundeshaushaltInfo\Tracking\Tracker();
$piwikTracker->trackPageView($documentTitle);

if ($isJsonRepsonse === true) {
    header('Content-Type: application/json;charset=utf-8');
}
echo $output;
