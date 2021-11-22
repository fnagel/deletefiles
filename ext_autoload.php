<?php

return [
    FelixNagel\DeleteFiles\Task\DeleteFilesTask::class => TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(
        'deletefiles',
        'Classes/Task/DeleteFilesTask.php'
    ),
    FelixNagel\DeleteFiles\Task\DeleteFilesAdditionalFieldProvider::class => TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(
        'deletefiles',
        'Classes/Task/DeleteFilesAdditionalFieldProvider.php'
    ),
];
