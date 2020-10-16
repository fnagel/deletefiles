<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "deletefiles".
 *
 * Auto generated 11-03-2013 21:41
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
    'title' => 'Delete old files',
    'description' => 'Scheduler task for deleting old files and folder. Supports FAL. Useful for GDPR / DSGVO compliance.',
    'category' => 'be',
    'author' => 'Felix Nagel',
    'author_email' => 'info@felixnagel.com',
    'state' => 'stable',
    'uploadfolder' => 0,
    'clearCacheOnLoad' => 0,
    'version' => '1.1.1-dev',
    'constraints' => [
        'depends' => [
            'php' => '7.0.0-7.4.99',
            'typo3' => '9.4.0-10.4.99',
            'scheduler' => '',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
