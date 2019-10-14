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

$columns = [
    'show_teaser' => [
        'label' => 'Zeige Teaser unterhalb des Inhalts an',
        'config' => [
            'type' => 'check',
            'default' => 0
        ]
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'pages',
    $columns
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    'show_teaser',
    '',
    'after:backend_layout'
);
