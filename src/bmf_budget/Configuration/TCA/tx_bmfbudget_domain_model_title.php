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
        'title' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:tx_bmfbudget_domain_model_title',
        'label' => 'address',
        'label_alt' => 'title',
        'label_alt_force' => 1,
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
        'searchFields' => 'address,title,info_text,',
        'requestUpdate' => 'section',
        'iconfile' => \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName(
            'EXT:bmf_budget/Resources/Public/Icons/tx_bmfbudget_domain_model_title.gif'
        )
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, address, title, flow,
		actual_income, actual_expenses, target_income, target_expenses, flexible, actual_page, actual_page_link, target_page, target_page_link,
		info_image, info_text, info_link, titlegroup,
		budgetgroup, budget, section, groupe, functione, supplementaries',
    ],
    'types' => [
        '1' => [
            'showitem' => '
				address, title, flexible, actual_page, actual_page_link, target_page, target_page_link, flow, actual_income, actual_expenses, target_income, target_expenses,
			--div--;LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:relations,
				budget, section, groupe, functione, titlegroup, budgetgroup,
			--div--;LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:supplementary_budgets,
				supplementaries,
			--div--;LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:additional_informations,
				info_image, info_text, info_link,
			--div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access,
				sys_language_uid, l10n_parent, l10n_diffsource, hidden;;1, starttime, endtime',

            'columnsOverrides' => [
                'info_text' => ['defaultExtras' => 'richtext:rte_transform[flag=rte_enabled|mode=ts_css]']
            ]
        ]
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
                'foreign_table' => 'tx_bmfbudget_domain_model_title',
                'foreign_table_where' => 'AND tx_bmfbudget_domain_model_title.pid=###CURRENT_PID### ' .
                    'AND tx_bmfbudget_domain_model_title.sys_language_uid IN (-1,0)',
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

        'address' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:address',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim,required'
            ],
        ],
        'title' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:title',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'eval' => 'trim,required'
            ],
        ],
        'flow' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:flow',
            'config' => [
                'type' => 'select',
                'items' => [
                    ['LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:please_choose', ''],
                    ['LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:income', 'e'],
                    ['LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:expenses', 'a'],
                ],
                'size' => 1,
                'maxitems' => 1,
                'eval' => ''
            ],
        ],
        'actual_income' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:actual_income',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'tx_bmfbudget_value'
            ]
        ],
        'actual_expenses' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:actual_expenses',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'tx_bmfbudget_value'
            ]
        ],
        'target_income' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:target_income',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'tx_bmfbudget_value'
            ]
        ],
        'target_expenses' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:target_expenses',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'tx_bmfbudget_value'
            ]
        ],
        'flexible' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:flexible',
            'config' => [
                'type' => 'check',
                'default' => 0
            ]
        ],
        'actual_page' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:actual_page',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'actual_page_link' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:actual_page_link',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'target_page' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:target_page',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'target_page_link' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:target_page_link',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'info_image' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:info_image',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'infoImage',
                [
                    'maxitems' => 1,
                    'appearance' => [
                        'collapseAll' => 1,
                        'createNewRelationLinkTitle' => 'LLL:EXT:cms/locallang_ttc.xlf:images.addFileReference'
                    ],
                    'foreign_types' => [
                        '0' => [
                            'showitem' => '
							--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
							--palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                            'showitem' => '
							--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
							--palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                            'showitem' => '
							--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
							--palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                            'showitem' => '
							--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
							--palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                            'showitem' => '
							--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
							--palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                            'showitem' => '
							--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
							--palette--;;filePalette'
                        ]
                    ]
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],
        'info_text' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:info_text',
            'config' => [
                'type' => 'text',
                'cols' => '48',
                'rows' => '5',
                'wizards' => [
                    'RTE' => [
                        'notNewRecords' => 1,
                        'RTEonly' => 1,
                        'type' => 'script',
                        'title' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:bodytext.W.RTE',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_rte.gif',
                        'module' => [
                            'name' => 'wizard_rte'
                        ]
                    ]
                ]
            ],
            'defaultExtras' => 'richtext:rte_transform[mode=ts_css]',
        ],
        'info_link' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:info_link',
            'config' => [
                'type' => 'input',
                'size' => '20',
                'max' => 1024,
                'wizards' => [
                    'link' => [
                        'type' => 'popup',
                        'title' => 'Info',
                        'icon' => 'link_popup.gif',
                        'module' => [
                            'name' => 'wizard_element_browser',
                            'urlParameters' => [
                                'mode' => 'wizard'
                            ]
                        ],
                        'params' => [
                            'blindLinkOptions' => 'folder',
                            'blindLinkFields' => 'target,title,class,params'
                        ],
                        'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
                    ]
                ],
                'softref' => 'typolink'
            ]
        ],
        'budget' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:corresponding_budget',
            'config' => [
                'type' => 'select',
                'items' => [
                    ['LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:please_choose', 0],
                ],
                'foreign_table' => 'tx_bmfbudget_domain_model_budget',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'supplementaries' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:supplementary_budgets',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_bmfbudget_domain_model_supplementarytitle',
                'foreign_field' => 'title',
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
        'section' => [
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:corresponding_section',
            'config' => [
                'type' => 'select',
                'items' => [
                    ['LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:please_choose', 0],
                ],
                'foreign_table' => 'tx_bmfbudget_domain_model_section',
                'foreign_table_where' => 'AND tx_bmfbudget_domain_model_section.pid = ###PAGE_TSCONFIG_ID### ' .
                    'AND char_length(`address`) = 4 ' .
                    'ORDER BY `address5`',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'groupe' => [
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:corresponding_group',
            'config' => [
                'type' => 'select',
                'items' => [
                    ['LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:please_choose', 0],
                ],
                'foreign_table' => 'tx_bmfbudget_domain_model_groupe',
                'foreign_table_where' => 'AND tx_bmfbudget_domain_model_groupe.pid = ###PAGE_TSCONFIG_ID### ' .
                    'AND char_length(`address`) = 3 ' .
                    'ORDER BY `address6`',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'functione' => [
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:corresponding_function',
            'config' => [
                'type' => 'select',
                'items' => [
                    ['LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:please_choose', 0],
                ],
                'foreign_table' => 'tx_bmfbudget_domain_model_functione',
                'foreign_table_where' => 'AND tx_bmfbudget_domain_model_functione.pid = ###PAGE_TSCONFIG_ID### ' .
                    'AND char_length(`address`) = 3 ',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'budgetgroup' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:budgetgroups',
            'config' => [
                'type' => 'select',
                'items' => [
                    ['LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:not_specified', 0],
                ],
                'foreign_table' => 'tx_bmfbudget_domain_model_budgetgroup',
                'foreign_table_where' => 'AND tx_bmfbudget_domain_model_budgetgroup.section = ###REC_FIELD_section### ',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'titlegroup' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:titlegroups',
            'config' => [
                'type' => 'select',
                'items' => [
                    ['LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:not_specified', 0],
                ],
                'foreign_table' => 'tx_bmfbudget_domain_model_titlegroup',
                'foreign_table_where' => 'AND tx_bmfbudget_domain_model_titlegroup.section = ###REC_FIELD_section### ' .
                    'ORDER BY `address`',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
    ],
];
// phpcs:enable
