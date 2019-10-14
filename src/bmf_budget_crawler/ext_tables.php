<?php

/*
 * This file is part of the "bmf_budget_crawler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

if (TYPO3_MODE === 'BE') {
    /**
     * Registers a Backend Module
     */
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'PPKOELN.' . $_EXTKEY,
        'tools',
        'mod1',
        '',
        [
            'Backend' => 'index',
            'Crawler\Queue' => 'index',
            'Crawler\Flush' => 'index, process',
            'Crawler\Create\Budget' => 'index, create',
            'Crawler\Create\Timeline' => 'index, create',
        ],
        [
            'access' => 'user,group',
            'icon' => 'EXT:' . $_EXTKEY . '/Resources/Public/Icons/be_mod1.png',
            'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_mod1.xlf',
        ]
    );
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $_EXTKEY,
    'Configuration/TypoScript',
    'BMF, budget visualization - crawler'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
    'tx_bmfbudgetcrawler_domain_model_crawler',
    'EXT:bmf_budget_crawler/Resources/Private/Language/locallang_csh_tx_bmfbudgetcrawler_domain_model_crawler.xlf'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages(
    'tx_bmfbudgetcrawler_domain_model_crawler'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
    'tx_bmfbudgetcrawler_domain_model_queue',
    'EXT:bmf_budget_crawler/Resources/Private/Language/locallang_csh_tx_bmfbudgetcrawler_domain_model_queue.xlf'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages(
    'tx_bmfbudgetcrawler_domain_model_queue'
);
