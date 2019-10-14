<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'BMF, budget visualization - crawler',
    'description' => '',
    'category' => 'plugin',
    'author' => 'Publicis Pixelpark Köln',
    'author_email' => 'kontakt@publicispixelpark.de',
    'state' => 'stable',
    'uploadfolder' => '0',
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '6.2.5',
    'constraints' => [
        'depends' => [
            'typo3' => '6.2.0-9.5.99',
            'bmf_budget' => '6.2.0-9.5.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
