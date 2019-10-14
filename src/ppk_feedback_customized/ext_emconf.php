<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Feedback Form (customized)',
    'description' => 'Customizing EXT: ppk_feedback',
    'category' => 'templates',
    'author' => 'Publicis Pixelpark Köln',
    'author_email' => 'kontakt@publicispixelpark.de',
    'state' => 'stable',
    'uploadfolder' => '0',
    'createDirs' => '',
    'clearCacheOnLoad' => 1,
    'version' => '1.2.0-dev',
    'constraints' => [
        'depends' => [
            'typo3' => '7.4.0-9.5.99',
            'ppk_feedback' => ''
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
