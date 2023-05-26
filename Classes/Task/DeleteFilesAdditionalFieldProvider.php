<?php

namespace FelixNagel\DeleteFiles\Task;

/**
 * This file is part of the "deletefiles" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Scheduler\AbstractAdditionalFieldProvider;
use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * Class DeleteFilesAdditionalFieldProvider.
 */
class DeleteFilesAdditionalFieldProvider extends AbstractAdditionalFieldProvider
{
    /**
     * @var LanguageService
     */
    protected $languageService;

    /**
     * Construct class.
     */
    public function __construct()
    {
        $this->languageService = $GLOBALS['LANG'];
    }

    /**
     * Gets additional fields to render in the form to add/edit a task.
     *
     * @param array $taskInfo Values of the fields from the add/edit task form
     * @param DeleteFilesTask $task The task object
     *
     * @return array
     */
    public function getAdditionalFields(
        array &$taskInfo,
        $task,
        SchedulerModuleController $schedulerModule
    ) {
        $additionalFields = [];
        $action = (string)$schedulerModule->getCurrentAction();

        // process fields
        if (empty($taskInfo['deletefiles_directory'])) {
            if ($action ===  'add') {
                $taskInfo['deletefiles_directory'] = '';
            } elseif ($action === 'edit') {
                $taskInfo['deletefiles_directory'] = $task->deletefiles_directory;
            } else {
                $taskInfo['deletefiles_directory'] = '';
            }
        }

        if (empty($taskInfo['deletefiles_time'])) {
            if ($action === 'add') {
                $taskInfo['deletefiles_time'] = [];
            } elseif ($action === 'edit') {
                $taskInfo['deletefiles_time'] = $task->deletefiles_time;
            } else {
                $taskInfo['deletefiles_time'] = '';
            }
        }

        if (empty($taskInfo['deletefiles_method'])) {
            if ($action === 'add') {
                $taskInfo['deletefiles_method'] = 0;
            } elseif ($action === 'edit') {
                $taskInfo['deletefiles_method'] = $task->deletefiles_method;
            } else {
                $taskInfo['deletefiles_method'] = 0;
            }
        }

        // render directory field
        $fieldId = 'task_deletefiles_directory';
        $fieldCode = '<input type="text" name="tx_scheduler[deletefiles_directory]" id="'.$fieldId.'" value="'.
            htmlspecialchars($taskInfo['deletefiles_directory']).'">';

        $additionalFields[$fieldId] = [
            'code' => $fieldCode,
            'label' => BackendUtility::wrapInHelp('deletefiles', $fieldId, $this->translate('addfields_label_directory')),
            'cshKey' => '_MOD_tools_txschedulerM1',
            'cshLabel' => $fieldId,
        ];

        // render time field
        $fieldId = 'task_deletefiles_time';
        $fieldCode = '<select name="tx_scheduler[deletefiles_time]" id="'.$fieldId.'">';

        $fieldValueArray = $this->getTimes();
        foreach ($fieldValueArray as $time => $label) {
            $fieldCode .= "\t".'<option value="'.htmlspecialchars($time).'"'.
                (($time == $taskInfo['deletefiles_time']) ? ' selected="selected"' : '').
                '>'.$label.'</option>';
        }

        $fieldCode .= '</select>';

        $additionalFields[$fieldId] = [
            'code' => $fieldCode,
            'label' => BackendUtility::wrapInHelp('deletefiles', $fieldId, $this->translate('addfields_label_time')),
            'cshKey' => '_MOD_tools_txschedulerM1',
            'cshLabel' => $fieldId,
        ];

        // render method field
        $fieldId = 'task_deletefiles_method';
        $fieldCode = '<select name="tx_scheduler[deletefiles_method]" id="'.$fieldId.'">';

        $fieldValueArray = $this->getMethods();
        foreach ($fieldValueArray as $method => $label) {
            $fieldCode .= "\t".'<option value="'.htmlspecialchars($method).'"'.
                (($method == $taskInfo['deletefiles_method']) ? ' selected="selected"' : '').'>'.$label.'</option>';
        }

        $fieldCode .= '</select>';

        $additionalFields[$fieldId] = [
            'code' => $fieldCode,
            'label' => BackendUtility::wrapInHelp('deletefiles', $fieldId, $this->translate('addfields_label_method')),
            'cshKey' => '_MOD_tools_txschedulerM1',
            'cshLabel' => $fieldId,
        ];

        return $additionalFields;
    }

    /**
     * @todo add a hook here
     *
     * @return array
     */
    public function getTimes()
    {
        return [
            '0' => $this->translate('addfields_time_all'),
            '1h' => '1 '.$this->translate('addfields_time_h'),
            '6h' => '6 '.$this->translate('addfields_time_h'),
            '12h' => '12 '.$this->translate('addfields_time_h'),
            '24h' => '24 '.$this->translate('addfields_time_h'),
            '48h' => '48 '.$this->translate('addfields_time_h'),
            '72h' => '72 '.$this->translate('addfields_time_h'),
            '7d' => '7 '.$this->translate('addfields_time_d'),
            '14d' => '14 '.$this->translate('addfields_time_d'),
            '1m' => '1 '.$this->translate('addfields_time_m'),
            '3m' => '3 '.$this->translate('addfields_time_m'),
            '6m' => '6 '.$this->translate('addfields_time_m'),
            '12m' => '12 '.$this->translate('addfields_time_m'),
        ];
    }

    /**
     * @todo add a hook here
     *
     * @return array
     */
    public function getMethods()
    {
        return [
            'delete_directory' => $this->translate('addfields_method_delete_directory'),
            'delete_files' => $this->translate('addfields_method_delete_files'),
            'delete_directories' => $this->translate('addfields_method_delete_directories'),
            'delete_all' => $this->translate('addfields_method_delete_all'),
            'delete_all_recursive' => $this->translate('addfields_method_delete_all_recursive'),
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function validateAdditionalFields(
        array &$submittedData,
        SchedulerModuleController $schedulerModule
    ) {
        $validInput = true;

        // clean data
        $submittedData['deletefiles_directory'] = trim($submittedData['deletefiles_directory'], '/');
        $submittedData['deletefiles_time'] = trim($submittedData['deletefiles_time']);

        $path = $submittedData['deletefiles_directory'];
        $publicPath = Environment::getPublicPath().'/';

        if (!(strlen($path) > 0 && is_dir($publicPath.$path) && GeneralUtility::isAllowedAbsPath($publicPath.$path))) {
            // @extensionScannerIgnoreLine
            $this->addMessage(
                sprintf($this->translate('addfields_notice_path_invalid'), $path),
                AbstractMessage::ERROR
            );
            $validInput = false;
        }

        return $validInput;
    }

    /**
     * {@inheritdoc}
     */
    public function saveAdditionalFields(array $submittedData, AbstractTask $task)
    {
        $task->deletefiles_directory = $submittedData['deletefiles_directory'];
        $task->deletefiles_time = $submittedData['deletefiles_time'];
        $task->deletefiles_method = $submittedData['deletefiles_method'];
    }

    /**
     * Translate by key.
     *
     * @param string $key
     * @param string $prefix
     *
     * @return string
     */
    protected function translate($key, $prefix = 'LLL:EXT:deletefiles/Resources/Private/Language/locallang.xlf:')
    {
        return $this->languageService->sL($prefix.$key);
    }
}
