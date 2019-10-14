<?php

/*
 * This file is part of the "bmf_page_bundeshaushalt_info_embed" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

/***************
 * Adding backend layouts
 */
if (TYPO3_MODE === 'BE') {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['BackendLayoutDataProvider']['file']
        = \PPKOELN\BmfPageBundeshaushaltInfoEmbed\Provider\FileProvider::class;

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['BackendLayoutFileProvider']['dir'][]
        =  'EXT:bmf_page_bundeshaushalt_info_embed/Resources/Private/BackendLayouts/';
}
