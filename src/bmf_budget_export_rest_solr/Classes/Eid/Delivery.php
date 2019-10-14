<?php
namespace PPKOELN\BmfBudgetExportRestSolr\Eid;

// phpcs:disable

/*
 * This file is part of the "bmf_budget_export_rest_solr" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Utility\GeneralUtility;
use ApacheSolrForTypo3\Solr\ConnectionManager;

/**
 * Class Delivery
 *
 *
 * delivers json files for endpoints /rest/ and /rest-timeline/
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
 * RewriteRule ^.*$ %{ENV:CWD}?eID=bmf_budget_export_rest_solr [QSA,L]
 */
class Delivery
{
    /**
     * @var \ApacheSolrForTypo3\Solr\ConnectionManager ConnectionManager
     */
    protected $connectionManager;

    /**
     * Initialize Extbase
     *
     * @param \array $TYPO3_CONF_VARS
     */
    public function __construct($TYPO3_CONF_VARS)
    {
        $this->connectionManager = GeneralUtility::makeInstance(ConnectionManager::class);
    }

    /**
     *
     */
    public function run()
    {
        $piwikConfig = [
            'Development/vm-bmf-bundeshaushalt' => [
                'uri'      => 'http://piwik.bundeshaushalt-info.dev/piwik/',
                'idSite'    => '1'
            ]
        ];

        $requestedUri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);

        $documentTitle = $requestedUri;

        $translatedUri = ltrim($requestedUri, '/');

        $output = '';
        $isJsonRepsonse = false;

        if (substr($translatedUri, -13) === '/service.json') {
            return '{

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
            if ($pathSegments[0] == 'rest-timeline') {
                $solrDocumentType = 'tx_bmfbudgetexportrestsolr_export-rest-timeline';
                if (strlen(rtrim($pathSegments[count($pathSegments) - 1], '.html')) == 9) {
                    $translatedUriPathSegments = $pathSegments;
                    unset($translatedUriPathSegments[count($translatedUriPathSegments) - 2]);
                    $translatedUri = implode('/', $translatedUriPathSegments);
                }
            } else {
                $translatedUri = rtrim($translatedUri, '.html');
                $translatedUri .= 'index.json';
            }

/*
            $solrQuery = urlencode("filePublicUrl:".\ApacheSolrForTypo3\Solr\SolrService::escapePhrase($translatedUri)." AND ".
                "type:".$solrDocumentType);
            $url = "http://127.0.0.1:8983/solr/core_de/select?indent=on&q=".$solrQuery."&wt=json";

            try {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);

                $response = json_decode($response);

                $output = $response->response->docs[0]->budgetData;
            } catch(\Exception $e) {
                header("HTTP/1.0 404 Not Found");
                echo 'Not Found';
                die();
            }
*/

            /**
             * @var \ApacheSolrForTypo3\Solr\SolrService $solr
             */
            $solrConnection = $this->connectionManager->getConnectionByPageId(2);
            $solrIsAvailable = $solrConnection->ping();
            $solrResultOK = false;

            if ($solrIsAvailable) {
                $response = $solrConnection->search(
                    'filePublicUrl:'.\ApacheSolrForTypo3\Solr\SolrService::escapePhrase($translatedUri).' AND '.
                    'type:'.$solrDocumentType,
                    0,
                    1
                );

                if ($response->getHttpStatus() == 200) {
                    if ($response->response->numFound == 1) {
                        /**
                         * @var \Apache_Solr_Document $resultDocument
                         */
                        $resultDocument = current($response->response->docs);
                        $outputField = $resultDocument->getField('budgetData');
                        $output = $outputField['value'];
                        $solrResultOK = true;
                    }
                }
            }

            if ($solrResultOK === false) {
                header("HTTP/1.0 404 Not Found");
                echo 'Not Found';
                die();
            }

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
        }

        if ($isJsonRepsonse === true) {
            header("Content-Type: application/json;charset=utf-8");
        }
        echo $output;
    }
}

global $TYPO3_CONF_VARS;
$delivery = GeneralUtility::makeInstance(Delivery::class, $TYPO3_CONF_VARS);
echo $delivery->run();
// phpcs:enable
