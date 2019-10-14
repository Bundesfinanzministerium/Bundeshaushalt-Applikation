<?php

/*
 * This file is part of the "bmf_budget_import_xml" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

if (TYPO3_MODE === 'BE') {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'PPK.' . $_EXTKEY,
        'tools',
        'mod1',
        '',
        [
            'Backend' => 'index',
            'Budget\Update' => 'index, budget, upload, process',
            'Budget\Import' => 'index, budget, upload, process',
            'Budget\Create' => 'index, process',
            'Supplementary\Create' => 'index, budget, process',
            'Supplementary\Import' => 'index, budget, supplementary, upload, process',
            'Label\Update' => 'index, budget, upload, process'
        ],
        [
            'access' => 'user,group',
            'icon' => 'EXT:' . $_EXTKEY . '/Resources/Public/Icons/be_mod1.png',
            'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xlf',
        ]
    );
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $_EXTKEY,
    'Configuration/TypoScript',
    'BMF, import (xml)'
);
