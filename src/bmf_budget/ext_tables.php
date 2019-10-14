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

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    $_EXTKEY,
    'Pi1',
    'BMF, Budget visualisation'
);

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
            'Backend\Maintenance' => 'index',
            'Backend\Maintenance\Totalling' => 'index'
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
    'BMF, budget visualization'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
    'tx_bmfbudget_domain_model_title',
    'EXT:bmf_budget/Resources/Private/Language/locallang_csh_tx_bmfbudget_domain_model_title.xlf'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_bmfbudget_domain_model_title');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
    'tx_bmfbudget_domain_model_section',
    'EXT:bmf_budget/Resources/Private/Language/locallang_csh_tx_bmfbudget_domain_model_section.xlf'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_bmfbudget_domain_model_section');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
    'tx_bmfbudget_domain_model_groupe',
    'EXT:bmf_budget/Resources/Private/Language/locallang_csh_tx_bmfbudget_domain_model_groupe.xlf'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_bmfbudget_domain_model_groupe');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
    'tx_bmfbudget_domain_model_functione',
    'EXT:bmf_budget/Resources/Private/Language/locallang_csh_tx_bmfbudget_domain_model_functione.xlf'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_bmfbudget_domain_model_functione');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
    'tx_bmfbudget_domain_model_budgetgroup',
    'EXT:bmf_budget/Resources/Private/Language/locallang_csh_tx_bmfbudget_domain_model_budgetgroup.xlf'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_bmfbudget_domain_model_budgetgroup');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
    'tx_bmfbudget_domain_model_titlegroup',
    'EXT:bmf_budget/Resources/Private/Language/locallang_csh_tx_bmfbudget_domain_model_titlegroup.xlf'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_bmfbudget_domain_model_titlegroup');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
    'tx_bmfbudget_domain_model_budget',
    'EXT:bmf_budget/Resources/Private/Language/locallang_csh_tx_bmfbudget_domain_model_budget.xlf'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_bmfbudget_domain_model_budget');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
    'tx_bmfbudget_domain_model_supplementarytitle',
    'EXT:bmf_budget/Resources/Private/Language/locallang_csh_tx_bmfbudget_domain_model_supplementarytitle.xlf'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages(
    'tx_bmfbudget_domain_model_supplementarytitle'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
    'tx_bmfbudget_domain_model_supplementarybudget',
    'EXT:bmf_budget/Resources/Private/Language/locallang_csh_tx_bmfbudget_domain_model_supplementarybudget.xlf'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages(
    'tx_bmfbudget_domain_model_supplementarybudget'
);

/*************************************************************************
 * Register Flexform for Pi1
 */
$pluginSignature = str_replace('_', '', $_EXTKEY) . '_pi1';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    $pluginSignature,
    'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_pi1.xml'
);

/*************************************************************************
 * Register Wizicon
 */
$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses'][\PPKOELN\BmfBudget\Utility\Wizicon::class] =
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(
        $_EXTKEY,
        'Classes' . DIRECTORY_SEPARATOR . 'Utility' . DIRECTORY_SEPARATOR . 'Wizicon.php'
    );
