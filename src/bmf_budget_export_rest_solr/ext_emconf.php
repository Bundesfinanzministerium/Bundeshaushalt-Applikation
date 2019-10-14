<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'BMF, budget visualization - rest solr exporter',
    'description' => '',
    'category' => 'plugin',
    'author' => 'Publicis Pixelpark Köln',
    'author_email' => 'kontakt@publicispixelpark.de',
    'state' => 'stable',
    'uploadfolder' => '0',
    'clearCacheOnLoad' => 0,
    'version' => '1.1.0',
    'constraints' => [
        'depends' => [
            'typo3' => '6.2.0-9.5.99',
            'bmf_budget' => '6.2.0-9.5.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
