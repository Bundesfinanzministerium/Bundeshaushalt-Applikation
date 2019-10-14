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

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\PPKOELN\BmfBudgetCrawler\Task\CrawlerTask::class] = [
    'extension' => $_EXTKEY,
    'title' => 'BMF, Budget crawler',
    'description' => 'minor description',
    'additionalFields' => \PPKOELN\BmfBudgetCrawler\Task\CrawlerTaskAdditionalFieldProvider::class
];

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['bmf_budget']['maintenance']['export'][] =
    'PPKOELN\\BmfBudgetExportRestFile\\Hook\\File';
