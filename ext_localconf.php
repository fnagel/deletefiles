<?php

use FelixNagel\DeleteFiles\Task\DeleteFilesTask;
use FelixNagel\DeleteFiles\Task\DeleteFilesAdditionalFieldProvider;

defined('TYPO3') || die();

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][DeleteFilesTask::class] = [
    'extension' => 'deletefiles',
    'title' => 'LLL:EXT:deletefiles/Resources/Private/Language/locallang.xlf:localconf_title',
    'description' => 'LLL:EXT:deletefiles/Resources/Private/Language/locallang.xlf:localconf_description',
    'additionalFields' => DeleteFilesAdditionalFieldProvider::class,
];
