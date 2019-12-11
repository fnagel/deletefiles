<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['FelixNagel\\DeleteFiles\\Task\\DeleteFilesTask'] = [
    'extension' => 'deletefiles',
    'title' => 'LLL:EXT:deletefiles/Resources/Private/Language/locallang.xml:localconf_title',
    'description' => 'LLL:EXT:deletefiles/Resources/Private/Language/locallang.xml:localconf_description',
    'additionalFields' => 'FelixNagel\\DeleteFiles\\Task\\DeleteFilesAdditionalFieldProvider',
];
