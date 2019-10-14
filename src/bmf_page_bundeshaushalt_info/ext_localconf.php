<?php

/*
 * This file is part of the "bmf_page_bundeshaushalt_info" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// adding realurl configuration
$settings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY], ['allowed_classes' => false]);
if ($settings['UseRealUrlConfig'] == 1) {
    // TODO: Remove
    @include_once \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(
        $_EXTKEY,
        'Configuration/RealURL/Global.php'
    );
}

// Adding backend layouts
if (TYPO3_MODE === 'BE') {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['BackendLayoutDataProvider']['file']
        = \PPKOELN\BmfPageBundeshaushaltInfo\Provider\FileProvider::class;

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['BackendLayoutFileProvider']['dir'][]
        =  'EXT:bmf_page_bundeshaushalt_info/Resources/Private/BackendLayouts/';
}

// Register Plugin for Extended Slick Slider Accordion
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'PPKOELN.' . $_EXTKEY,
    'SlickSlider',
    [
        'SlickSlider' => 'show',
    ]
);

// Register custom CKeditor configuration
$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['bundeshaushalt'] =
    'EXT:bmf_page_bundeshaushalt_info/Configuration/RTE/Bundeshaushalt.yaml';
