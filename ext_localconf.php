<?php

defined('TYPO3') || die();

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][FelixNagel\DeleteFiles\Task\DeleteFilesTask::class] = [
    'extension' => 'deletefiles',
    'title' => 'LLL:EXT:deletefiles/Resources/Private/Language/locallang.xlf:localconf_title',
    'description' => 'LLL:EXT:deletefiles/Resources/Private/Language/locallang.xlf:localconf_description',
    'additionalFields' => FelixNagel\DeleteFiles\Task\DeleteFilesAdditionalFieldProvider::class,
];
