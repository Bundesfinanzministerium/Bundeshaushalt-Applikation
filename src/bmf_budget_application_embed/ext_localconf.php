<?php

/*
 * This file is part of the "bmf_budget_application_embed" Extension for TYPO3 CMS.
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
    ['Embed' => 'show'],
    ['Embed' => 'show']
);
