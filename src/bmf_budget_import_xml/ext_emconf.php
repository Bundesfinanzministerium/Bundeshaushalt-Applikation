<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'BMF, budget visualization - xml importer',
    'description' => 'XML Importer for bundeshaushalt-info.de',
    'category' => 'plugin',
    'author' => 'Publicis Pixelpark Köln',
    'author_email' => 'kontakt@publicispixelpark.de',
    'state' => 'stable',
    'uploadfolder' => '0',
    'createDirs' => 'typo3temp/tx_bmf_budget_import_xml',
    'clearCacheOnLoad' => 0,
    'version' => '1.2.0',
    'constraints' => [
        'depends' => [
            'typo3' => '6.2.0-9.5.99',
            'bmf_budget' => '6.2.0-9.5.99'
        ],
        'conflicts' => [
            'bmf_budget_import_excel' => '',
        ],
        'suggests' => [],
    ],
];
