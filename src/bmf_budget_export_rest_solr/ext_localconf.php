<?php

/*
 * This file is part of the "bmf_budget_export_rest_solr" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// Register hooks
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['bmf_budget_crawler']['crawler']['service'][] =
    \PPKOELN\BmfBudgetExportRestSolr\Hook\BudgetExport::class;
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['bmf_budget_crawler']['crawler']['timelineService'][] =
    \PPKOELN\BmfBudgetExportRestSolr\Hook\TimelineExport::class;

// Register eid script
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['bmf_budget_export_rest_solr'] =
    'EXT:bmf_budget_export_rest_solr/Classes/Eid/Delivery.php';
