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
            'tx_bmfbudgetcrawler_domain_model_queue',
        'label' => 'address',
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
        'searchFields' => 'address',
        'iconfile' => \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName(
            'EXT:bmf_budget_crawler/Resources/Public/Icons/tx_bmfbudgetcrawler_domain_model_queue.gif'
        )
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, ' .
            'crawler, address, status, result, error, error_message',
    ],
    'types' => [
        '1' => [
            'showitem' => '           
                crawler, address, status, result, error, error_message,
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
                'foreign_table' => 'tx_bmfbudgetcrawler_domain_model_queue',
                'foreign_table_where' => 'AND tx_bmfbudgetcrawler_domain_model_queue.pid=###CURRENT_PID### AND tx_bmfbudgetcrawler_domain_model_queue.sys_language_uid IN (-1,0)',
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
        'crawler' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'address' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget_crawler/Resources/Private/Language/locallang_db.xlf:' .
                'tx_bmfbudgetcrawler_domain_model_queue.address',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'status' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget_crawler/Resources/Private/Language/locallang_db.xlf:' .
                'tx_bmfbudgetcrawler_domain_model_queue.status',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'result' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget_crawler/Resources/Private/Language/locallang_db.xlf:' .
                'tx_bmfbudgetcrawler_domain_model_queue.result',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ]
        ],
        'error' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget_crawler/Resources/Private/Language/locallang_db.xlf:' .
                'tx_bmfbudgetcrawler_domain_model_queue.error',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'error_message' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget_crawler/Resources/Private/Language/locallang_db.xlf:' .
                'tx_bmfbudgetcrawler_domain_model_queue.error_message',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 5,
                'eval' => 'trim'
            ]
        ],
    ],
];
// phpcs:enable
