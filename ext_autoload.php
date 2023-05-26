<?php

use FelixNagel\DeleteFiles\Task\DeleteFilesTask;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use FelixNagel\DeleteFiles\Task\DeleteFilesAdditionalFieldProvider;

return [
    DeleteFilesTask::class => ExtensionManagementUtility::extPath(
        'deletefiles',
        'Classes/Task/DeleteFilesTask.php'
    ),
    DeleteFilesAdditionalFieldProvider::class => ExtensionManagementUtility::extPath(
        'deletefiles',
        'Classes/Task/DeleteFilesAdditionalFieldProvider.php'
    ),
];
