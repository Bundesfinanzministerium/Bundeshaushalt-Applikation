<?php
/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'PPKOELN.' . $_EXTKEY,
    'Pi1',
    [
        'Budget' => 'show',

    ],
    // non-cacheable actions
    [
        'Budget' => '',

    ]
);

/***********************
 * adding tca validation for currencies
 */
$TYPO3_CONF_VARS['SC_OPTIONS']['tce']['formevals']['tx_bmfbudget_value'] =
    'EXT:bmf_budget/Classes/Domain/Validator/Tce/Value.php';

/***************
 * adding realurl configuration
 */
@include_once \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(
    $_EXTKEY,
    'Configuration/RealURL/Default.php'
);
