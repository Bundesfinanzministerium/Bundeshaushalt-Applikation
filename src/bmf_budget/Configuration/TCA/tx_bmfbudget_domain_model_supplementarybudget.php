<?php
/*
 * This file is part of the "bmf_budget" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

// phpcs:disable
return [
    'ctrl' => [
        'title' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:tx_bmfbudget_domain_model_supplementarybudget',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'sortby' => 'sorting',
        'versioningWS' => 2,
        'versioning_followPages' => true,

        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'title,',
        'iconfile' => \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName(
            'EXT:bmf_budget/Resources/Public/Icons/tx_bmfbudget_domain_model_supplementarybudget.gif'
        )
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, budget, title, supplementary_titles',
    ],
    'types' => [
        '1' => [
            'showitem' => '
				budget, title,
			--div--;LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:supplementary_titles,
				supplementary_titles,
			--div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access,
				sys_language_uid, l10n_parent, l10n_diffsource, hidden;;1, starttime, endtime'
        ],
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
    'columns' => [
        'sorting' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'sys_language_uid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => [
                    ['LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1],
                    ['LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0]
                ],
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_bmfbudget_domain_model_supplementarybudget',
                'foreign_table_where' => 'AND tx_bmfbudget_domain_model_supplementarybudget.pid=###CURRENT_PID### ' .
                    'AND tx_bmfbudget_domain_model_supplementarybudget.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ]
        ],
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'starttime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
            ],
        ],
        'title' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'supplementary_titles' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:supplementary_titles',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_bmfbudget_domain_model_supplementarytitle',
                'foreign_field' => 'supplementarybudget',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => 1,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1
                ],
            ],
        ],
        'budget' => [
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:corresponding_budget',
            'config' => [
                'type' => 'select',
                'items' => [
                    ['LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:please_choose', 0],
                ],
                'foreign_table' => 'tx_bmfbudget_domain_model_budget',
                'foreign_table_where' => '',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
    ],
];
// phpcs:enable
