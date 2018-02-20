<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "newspermissions"
 *
 * Auto generated by Extension Builder 2014-05-25
 *
 * Manual updates:
 * Only the data in the array - anything else is removed by next write.
 * "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
    'title' => 'News Permissions',
    'description' => 'Manipulate action icons in the News list to mark News items that cannot be edited because of missing category permissions',
    'category' => 'be',
    'author' => 'Lorenz Ulrich',
    'author_email' => 'lorenz.ulrich@visol.ch',
    'state' => 'stable',
    'internal' => '',
    'uploadfolder' => '0',
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '2.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-8.99.99',
            'news' => '6.3.0-6.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
