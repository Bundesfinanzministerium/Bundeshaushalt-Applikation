<?php

/*
 * This file is part of the "bmf_page_bundeshaushalt_info" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['init'] = [
    'enableCHashCache' => true,
    'appendMissingSlash' => 'ifNotFile',
    'enableUrlDecodeCache' => true,
    'enableUrlEncodeCache' => true,
    'emptyUrlReturnValue' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL'),
];

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['pagePath'] = [
    'type' => 'user',
    'userFunc' => 'EXT:realurl/class.tx_realurl_advanced.php:&tx_realurl_advanced->main',
    'spaceCharacter' => '-',
    'languageGetVar' => 'L',
];

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['preVars'] = [
    '0' => [
        'GETvar' => 'no_cache',
        'valueMap' => [
            'nc' => '1',
        ],
        'noMatch' => 'bypass'
    ],
    '1' => [
        'GETvar' => 'L',
        'valueMap' => [
            'da' => '1',
            'de' => '2',
        ],
        'noMatch' => 'bypass',
    ],
];

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['redirects'] = [];
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['fixedPostVars'] = [];
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['fileName'] = [
    'defaultToHTMLsuffixOnPrev' => true
];
