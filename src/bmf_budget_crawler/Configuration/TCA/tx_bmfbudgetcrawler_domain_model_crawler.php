<?php
/*
 * This file is part of the "bmf_budget_crawler" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

// phpcs:disable
return [
    'ctrl' => [
        'title' => 'LLL:EXT:bmf_budget_crawler/Resources/Private/Language/locallang_db.xlf:' .
            'tx_bmfbudgetcrawler_domain_model_crawler',
        'label' => 'ext_title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,

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
        'searchFields' => 'ext_class,progress',
        'iconfile' => \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName(
            'EXT:bmf_budget_crawler/Resources/Public/Icons/tx_bmfbudgetcrawler_domain_model_crawler.gif'
        )
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, ext_title, ext_class, preprocessed, progress, queues, budget, account, flow, structure, error, error_message, process_start, process_end',
    ],
    'types' => [
        '1' => [
            'showitem' => '
                ext_title, ext_class, budget, account, flow, structure, preprocessed, progress, process_start, process_end, 
            --div--;Queues, queues,
            --div--;Error, error, error_message,
            --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, 
                sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, starttime, endtime'
        ],
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
    'columns' => [

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
                'foreign_table' => 'tx_bmfbudgetcrawler_domain_model_crawler',
                'foreign_table_where' => 'AND tx_bmfbudgetcrawler_domain_model_crawler.pid=###CURRENT_PID### ' .
                    'AND tx_bmfbudgetcrawler_domain_model_crawler.sys_language_uid IN (-1,0)',
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
        'ext_title' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget_crawler/Resources/Private/Language/locallang_db.xlf:' .
                'tx_bmfbudgetcrawler_domain_model_crawler.ext_title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'ext_class' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget_crawler/Resources/Private/Language/locallang_db.xlf:' .
                'tx_bmfbudgetcrawler_domain_model_crawler.ext_class',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'preprocessed' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:bmf_budget_crawler/Resources/Private/Language/locallang_db.xlf:' .
                'tx_bmfbudgetcrawler_domain_model_crawler.preprocessed',
            'config' => [
                'type' => 'check',
            ],
        ],
        'progress' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget_crawler/Resources/Private/Language/locallang_db.xlf:' .
                'tx_bmfbudgetcrawler_domain_model_crawler.progress',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'budget' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget_crawler/Resources/Private/Language/locallang_db.xlf:' .
                'tx_bmfbudgetcrawler_domain_model_crawler.budget',
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
        'account' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget_crawler/Resources/Private/Language/locallang_db.xlf:' .
                'tx_bmfbudgetcrawler_domain_model_crawler.account',
            'config' => [
                'type' => 'radio',
                'items' => [
                    [
                        'LLL:EXT:bmf_budget_crawler/Resources/Private/Language/locallang_db.xlf:' .
                        'tx_bmfbudgetcrawler_domain_model_crawler.account.target',
                        'target'
                    ],
                    [
                        'LLL:EXT:bmf_budget_crawler/Resources/Private/Language/locallang_db.xlf:' .
                        'tx_bmfbudgetcrawler_domain_model_crawler.account.actual',
                        'actual'
                    ]
                ],
            ],
        ],
        'flow' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget_crawler/Resources/Private/Language/locallang_db.xlf:' .
                'tx_bmfbudgetcrawler_domain_model_crawler.flow',
            'config' => [
                'type' => 'radio',
                'items' => [
                    [
                        'LLL:EXT:bmf_budget_crawler/Resources/Private/Language/locallang_db.xlf:' .
                        'tx_bmfbudgetcrawler_domain_model_crawler.flow.income',
                        'income'
                    ],
                    [
                        'LLL:EXT:bmf_budget_crawler/Resources/Private/Language/locallang_db.xlf:' .
                        'tx_bmfbudgetcrawler_domain_model_crawler.flow.expenses',
                        'expenses'
                    ]
                ],
            ],
        ],
        'structure' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget_crawler/Resources/Private/Language/locallang_db.xlf:' .
                'tx_bmfbudgetcrawler_domain_model_crawler.structure',
            'config' => [
                'type' => 'radio',
                'items' => [
                    [
                        'LLL:EXT:bmf_budget_crawler/Resources/Private/Language/locallang_db.xlf:' .
                        'tx_bmfbudgetcrawler_domain_model_crawler.structure.section',
                        'section'
                    ],
                    [
                        'LLL:EXT:bmf_budget_crawler/Resources/Private/Language/locallang_db.xlf:' .
                        'tx_bmfbudgetcrawler_domain_model_crawler.structure.function',
                        'function'
                    ],
                    [
                        'LLL:EXT:bmf_budget_crawler/Resources/Private/Language/locallang_db.xlf:' .
                        'tx_bmfbudgetcrawler_domain_model_crawler.structure.group',
                        'group'
                    ]
                ],
            ],
        ],
        'queues' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:bmf_budget_crawler/Resources/Private/Language/locallang_db.xlf:' .
                'tx_bmfbudgetcrawler_domain_model_crawler.queues',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_bmfbudgetcrawler_domain_model_queue',
                'foreign_field' => 'crawler',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => 1,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1
                ],
            ]
        ],
        'error' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget_crawler/Resources/Private/Language/locallang_db.xlf:' .
                'tx_bmfbudgetcrawler_domain_model_crawler.error',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'error_message' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget_crawler/Resources/Private/Language/locallang_db.xlf:' .
                'tx_bmfbudgetcrawler_domain_model_crawler.error_message',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 5,
                'eval' => 'trim'
            ]
        ],
        'process_start' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget_crawler/Resources/Private/Language/locallang_db.xlf:' .
                'tx_bmfbudgetcrawler_domain_model_crawler.process_start',
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
        'process_end' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget_crawler/Resources/Private/Language/locallang_db.xlf:' .
                'tx_bmfbudgetcrawler_domain_model_crawler.process_end',
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
    ],
];
// phpcs:enable
