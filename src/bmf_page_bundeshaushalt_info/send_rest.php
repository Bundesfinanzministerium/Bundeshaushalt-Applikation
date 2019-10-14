<?php

/*
 * This file is part of the "bmf_page_bundeshaushalt_info" Extension for TYPO3 CMS.
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

$requestedUri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);

$documentTitle = $requestedUri;

$translatedUri = $_SERVER['CONTEXT_DOCUMENT_ROOT'].$requestedUri;

$output = '';
$isJsonRepsonse = false;

if (is_dir($translatedUri)) {
    $translatedUri = $translatedUri.'index.json';
}

if (file_exists($translatedUri) &&
    ( substr($translatedUri, -11) === '/index.json' || substr($translatedUri, -13) === '/service.json')

    ) {
    // only deliver files with a known filename
    $output = file_get_contents($translatedUri);
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

$tracker = new \PPKOELN\BmfPageBundeshaushaltInfo\Tracking\Tracker();
$tracker->trackPageView($documentTitle);

if ($isJsonRepsonse === true) {
    header("Content-Type: application/json;charset=utf-8");
}
echo $output;
