<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['FelixNagel\\DeleteFiles\\Task\\DeleteFilesTask'] = [
    'extension' => $_EXTKEY,
    'title' => 'LLL:EXT:'.$_EXTKEY.'/Resources/Private/Language/locallang.xml:localconf_title',
    'description' => 'LLL:EXT:'.$_EXTKEY.'/Resources/Private/Language/locallang.xml:localconf_description',
    'additionalFields' => 'FelixNagel\\DeleteFiles\\Task\\DeleteFilesAdditionalFieldProvider',
];
