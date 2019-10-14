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
        'title' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:tx_bmfbudget_domain_model_budget',
        'label' => 'title_target',
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
        'searchFields' => 'title_target, title_actual, year',
        'iconfile' => \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName(
            'EXT:bmf_budget/Resources/Public/Icons/tx_bmfbudget_domain_model_budget.gif'
        )
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title_target, title_actual, year,
			section_actual_income, section_actual_expenses, section_target_income, section_target_expenses,
			function_actual_income, function_actual_expenses, function_target_income, function_target_expenses,
			group_actual_income, group_actual_expenses, group_target_income, group_target_expenses, pid_title,
			pid_section, pid_function, pid_group, pid_budgetgroup, pid_titlegroup, pid_supplementary_budget,
			pid_supplementary_title, info_image, info_text, info_link, sections, groups, functions, supplementary_budgets',
    ],
    'types' => [
        '1' => [
            'showitem' => '
				title_actual, title_target, year,
			--div--;LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:sections,
				section_actual_income,section_actual_expenses,section_target_income,section_target_expenses,sections,
			--div--;LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:functions,
				function_actual_income,function_actual_expenses,function_target_income,function_target_expenses,functions,
			--div--;LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:groups,
				group_actual_income,group_actual_expenses,group_target_income,group_target_expenses,groups,
			--div--;LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:supplementary_budgets,
				supplementary_budgets,
			--div--;LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:pageids,
				pid_title,pid_section,pid_function,pid_group,pid_budgetgroup,pid_titlegroup,pid_supplementary_budget,pid_supplementary_title,
			--div--;LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:additional_informations,
				info_image, info_text,info_link,
			--div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access,
				sys_language_uid,l10n_parent,l10n_diffsource,hidden;;1,starttime,endtime',

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
                'foreign_table' => 'tx_bmfbudget_domain_model_budget',
                'foreign_table_where' => 'AND tx_bmfbudget_domain_model_budget.pid=###CURRENT_PID### ' .
                    'AND tx_bmfbudget_domain_model_budget.sys_language_uid IN (-1,0)',
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

        'title_actual' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:title_actual',
            'config' => [
                'type' => 'text',
                'rows' => 2,
                'size' => 40,
                'eval' => 'trim'
            ],
        ],
        'title_target' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:title_target',
            'config' => [
                'type' => 'text',
                'rows' => 2,
                'size' => 40,
                'eval' => 'trim,required'
            ],
        ],
        'year' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:year',
            'config' => [
                'type' => 'input',
                'size' => 6,
                'eval' => 'trim,required'
            ],
        ],
        'section_actual_income' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:actual_income',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'tx_bmfbudget_value'
            ]
        ],
        'section_actual_expenses' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:actual_expenses',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'tx_bmfbudget_value'
            ]
        ],
        'section_target_income' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:target_income',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'tx_bmfbudget_value'
            ]
        ],
        'section_target_expenses' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:target_expenses',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'tx_bmfbudget_value'
            ]
        ],
        'function_actual_income' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:actual_income',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'tx_bmfbudget_value'
            ]
        ],
        'function_actual_expenses' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:actual_expenses',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'tx_bmfbudget_value'
            ]
        ],
        'function_target_income' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:target_income',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'tx_bmfbudget_value'
            ]
        ],
        'function_target_expenses' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:target_expenses',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'tx_bmfbudget_value'
            ]
        ],
        'group_actual_income' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:actual_income',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'tx_bmfbudget_value'
            ]
        ],
        'group_actual_expenses' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:actual_expenses',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'tx_bmfbudget_value'
            ]
        ],
        'group_target_income' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:target_income',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'tx_bmfbudget_value'
            ]
        ],
        'group_target_expenses' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:target_expenses',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'tx_bmfbudget_value'
            ]
        ],
        'pid_title' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:pid_title',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'pages',
                'size' => '1',
                'maxitems' => '1',
                'minitems' => '0',
                'show_thumbs' => '1',
                'wizards' => [
                    'suggest' => [
                        'type' => 'suggest'
                    ]
                ]
            ]
        ],
        'pid_section' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:pid_section',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'pages',
                'size' => '1',
                'maxitems' => '1',
                'minitems' => '0',
                'show_thumbs' => '1',
                'wizards' => [
                    'suggest' => [
                        'type' => 'suggest'
                    ]
                ]
            ]
        ],
        'pid_function' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:pid_function',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'pages',
                'size' => '1',
                'maxitems' => '1',
                'minitems' => '0',
                'show_thumbs' => '1',
                'wizards' => [
                    'suggest' => [
                        'type' => 'suggest'
                    ]
                ]
            ]
        ],
        'pid_group' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:pid_group',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'pages',
                'size' => '1',
                'maxitems' => '1',
                'minitems' => '0',
                'show_thumbs' => '1',
                'wizards' => [
                    'suggest' => [
                        'type' => 'suggest'
                    ]
                ]
            ]
        ],
        'pid_budgetgroup' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:pid_budgetgroup',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'pages',
                'size' => '1',
                'maxitems' => '1',
                'minitems' => '0',
                'show_thumbs' => '1',
                'wizards' => [
                    'suggest' => [
                        'type' => 'suggest'
                    ]
                ]
            ]
        ],
        'pid_titlegroup' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:pid_titlegroup',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'pages',
                'size' => '1',
                'maxitems' => '1',
                'minitems' => '0',
                'show_thumbs' => '1',
                'wizards' => [
                    'suggest' => [
                        'type' => 'suggest'
                    ]
                ]
            ]
        ],
        'pid_supplementary_budget' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:pid_supplementary_budget',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'pages',
                'size' => '1',
                'maxitems' => '1',
                'minitems' => '0',
                'show_thumbs' => '1',
                'wizards' => [
                    'suggest' => [
                        'type' => 'suggest'
                    ]
                ]
            ]
        ],
        'pid_supplementary_title' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:pid_supplementary_title',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'pages',
                'size' => '1',
                'maxitems' => '1',
                'minitems' => '0',
                'show_thumbs' => '1',
                'wizards' => [
                    'suggest' => [
                        'type' => 'suggest'
                    ]
                ]
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
        'sections' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:sections',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_bmfbudget_domain_model_section',
                'foreign_field' => 'budget',
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
        'groups' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:groups',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_bmfbudget_domain_model_groupe',
                'foreign_field' => 'budget',
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
        'functions' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:functions',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_bmfbudget_domain_model_functione',
                'foreign_field' => 'budget',
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
        'supplementary_budgets' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:bmf_budget/Resources/Private/Language/locallang_db.xlf:supplementary_budgets',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_bmfbudget_domain_model_supplementarybudget',
                'foreign_field' => 'budget',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => 1,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'useSortable' => 1,
                    'showAllLocalizationLink' => 1
                ],
            ],
        ],

    ],
];
// phpcs:enable
