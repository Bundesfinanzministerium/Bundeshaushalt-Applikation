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

// register static templates
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $_EXTKEY,
    'Configuration/TypoScript',
    'BMF, page template'
);

// register static templates
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $_EXTKEY,
    'Configuration/Extensions/google_sitemap',
    'BMF, page template - sitemap'
);

// register page- and user-tsconfig
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:bmf_page_bundeshaushalt_info/Configuration/TSconfig/PageTSconfig.ts">'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:bmf_page_bundeshaushalt_info/Configuration/TSconfig/UserTSconfig.ts">'
);

// Register Plugin in Backend (with Flexform) for Extended Slick Slider Accordion
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'PPKOELN.' . $_EXTKEY,
    'SlickSlider',
    'Erweitertes Slick Slider Akkordion'
);
$extensionName = \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($_EXTKEY);
$signature = strtolower($extensionName) . '_slickslider';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$signature] = 'select_key,pages,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$signature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    $signature,
    'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/SlickSlider.xml'
);
