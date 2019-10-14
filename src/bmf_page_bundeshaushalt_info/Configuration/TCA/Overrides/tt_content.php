<?php

/*
 * This file is part of the "bmf_page_bundeshaushalt_info" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

$languageFilePrefix = 'LLL:EXT:fluid_styled_content/Resources/Private/Language/Database.xlf:';
$frontendLanguageFilePrefix = 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'Text & Text',
        'texttext',
        'content-text'
    ],
    'header',
    'after'
);

$additionalColumns = [
    'bodytext2' => [
        'l10n_mode' => 'prefixLangTitle',
        'l10n_cat' => 'text',
        'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.text',
        'config' => [
            'type' => 'text',
            'cols' => '80',
            'rows' => '15',
            'wizards' => [
                'RTE' => [
                    'notNewRecords' => 1,
                    'RTEonly' => 1,
                    'type' => 'script',
                    'title' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:bodytext.W.RTE',
                    'icon' => 'actions-wizard-rte',
                    'module' => [
                        'name' => 'wizard_rte'
                    ]
                ],
                'table' => [
                    'notNewRecords' => 1,
                    'enableByTypeConfig' => 1,
                    'type' => 'script',
                    'title' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:bodytext.W.table',
                    'icon' => 'content-table',
                    'module' => [
                        'name' => 'wizard_table'
                    ],
                    'params' => [
                        'xmlOutput' => 0
                    ]
                ]
            ],
            'softref' => 'typolink_tag,images,email[subst],url',
            'search' => [
                'andWhere' => 'CType=\'texttext\''
            ]
        ]
    ],
    'header2' => $GLOBALS['TCA']['tt_content']['columns']['header'],
    'header_layout2' => $GLOBALS['TCA']['tt_content']['columns']['header_layout'],
    'header_link2' => $GLOBALS['TCA']['tt_content']['columns']['header_link'],
];

$GLOBALS['TCA']['tt_content']['palettes']['header2'] = [
    'showitem' => '
        header2;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_formlabel,
        --linebreak--,
        header_layout2;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_layout_formlabel,
        --linebreak--,
        header_link2;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_link_formlabel,
    '
];

$GLOBALS['TCA']['tt_content']['types']['texttext'] = [
    'showitem' => '
				--palette--;' . $frontendLanguageFilePrefix . 'palette.general;general,
				--palette--;Header Links;header,rowDescription,
				bodytext;Text Links,
				--palette--;Header Rechts;header2,
				bodytext2;Text Rechts,
			--div--;' . $frontendLanguageFilePrefix . 'tabs.media,
				assets,
				--palette--;' . $frontendLanguageFilePrefix . 'palette.imagelinks;imagelinks,
			--div--;' . $frontendLanguageFilePrefix . 'tabs.appearance,
				layout;' . $frontendLanguageFilePrefix . 'layout_formlabel,
				--palette--;' . $languageFilePrefix . 'tt_content.palette.mediaAdjustments;mediaAdjustments,
				--palette--;' . $languageFilePrefix . 'tt_content.palette.gallerySettings;gallerySettings,
				--palette--;' . $frontendLanguageFilePrefix . 'palette.appearanceLinks;appearanceLinks,
			--div--;' . $frontendLanguageFilePrefix . 'tabs.access,
				hidden;' . $frontendLanguageFilePrefix . 'field.default.hidden,
				--palette--;' . $frontendLanguageFilePrefix . 'palette.access;access,
			--div--;' . $frontendLanguageFilePrefix . 'tabs.extended
		',
    'columnsOverrides' => [
        'bodytext' => ['defaultExtras' => 'richtext:rte_transform[mode=ts_css]'],
        'bodytext2' => ['defaultExtras' => 'richtext:rte_transform[mode=ts_css]']
    ]
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $additionalColumns);
