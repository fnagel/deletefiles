<?php

namespace TYPO3\DeleteFiles\Task;

use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Backend\Utility\BackendUtility;
use \TYPO3\CMS\Core\Messaging\FlashMessage;

/**
 * Class DeleteFilesAdditionalFieldProvider
 */
class DeleteFilesAdditionalFieldProvider implements \TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface {

	/**
	 * @var \TYPO3\CMS\Lang\LanguageService
	 */
	protected $languageService;

	/**
	 * Construct class
	 */
	public function __construct() {
		$this->languageService = $GLOBALS['LANG'];
	}

	/**
	 * Gets additional fields to render in the form to add/edit a task
	 *
	 * @param array $taskInfo Values of the fields from the add/edit task form
	 * @param \TYPO3\DeleteFiles\Task\DeleteFilesTask $task The task object
	 * @param \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $schedulerModule
	 *
	 * @return array
	 */
	public function getAdditionalFields(
		array &$taskInfo,
		$task,
		\TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $schedulerModule
	) {
		// process fields
		if (empty($taskInfo['deletefiles_directory'])) {
			if ($schedulerModule->CMD == 'add') {
				$taskInfo['deletefiles_directory'] = '';
			} elseif ($schedulerModule->CMD == 'edit') {
				$taskInfo['deletefiles_directory'] = $task->deletefiles_directory;
			} else {
				$taskInfo['deletefiles_directory'] = '';
			}
		}

		if (empty($taskInfo['deletefiles_time'])) {
			if ($schedulerModule->CMD == 'add') {
				$taskInfo['deletefiles_time'] = array();
			} elseif ($schedulerModule->CMD == 'edit') {
				$taskInfo['deletefiles_time'] = $task->deletefiles_time;
			} else {
				$taskInfo['deletefiles_time'] = '';
			}
		}

		if (empty($taskInfo['deletefiles_method'])) {
			if ($schedulerModule->CMD == 'add') {
				$taskInfo['deletefiles_method'] = 0;
			} elseif ($schedulerModule->CMD == 'edit') {
				$taskInfo['deletefiles_method'] = $task->deletefiles_method;
			} else {
				$taskInfo['deletefiles_method'] = 0;
			}
		}

		// render directory field
		$fieldId = 'task_deletefiles_directory';
		$fieldCode = '<input type="text" name="tx_scheduler[deletefiles_directory]" id="' . $fieldId . '" value="' .
			htmlspecialchars($taskInfo['deletefiles_directory']) . '">';

		$additionalFields[$fieldId] = array(
			'code' => $fieldCode,
			'label' => BackendUtility::wrapInHelp('deletefiles', $fieldId, $this->translate('addfields_label_directory')),
			'cshKey' => '_MOD_tools_txschedulerM1',
			'cshLabel' => $fieldId
		);

		// render time field
		$fieldId = 'task_deletefiles_time';
		$fieldCode = '<select name="tx_scheduler[deletefiles_time]" id="' . $fieldId . '">';

		$fieldValueArray = $this->getTimes();
		foreach ($fieldValueArray as $time => $label) {
			$fieldCode .= "\t" . '<option value="' . htmlspecialchars($time) . '"' .
				(($time == $taskInfo['deletefiles_time']) ? ' selected="selected"' : '') .
				'>' . $label . '</option>';
		}
		$fieldCode .= '</select>';

		$additionalFields[$fieldId] = array(
			'code' => $fieldCode,
			'label' => BackendUtility::wrapInHelp('deletefiles', $fieldId, $this->translate('addfields_label_time')),
			'cshKey' => '_MOD_tools_txschedulerM1',
			'cshLabel' => $fieldId
		);

		// render method field
		$fieldId = 'task_deletefiles_method';
		$fieldCode = '<select name="tx_scheduler[deletefiles_method]" id="' . $fieldId . '">';

		$fieldValueArray = $this->getMethods();
		foreach ($fieldValueArray as $method => $label) {
			$fieldCode .= "\t" . '<option value="' . htmlspecialchars($method) . '"' .
				(($method == $taskInfo['deletefiles_method']) ? ' selected="selected"' : '') . '>' . $label . '</option>';
		}
		$fieldCode .= '</select>';

		$additionalFields[$fieldId] = array(
			'code' => $fieldCode,
			'label' => BackendUtility::wrapInHelp('deletefiles', $fieldId, $this->translate('addfields_label_method')),
			'cshKey' => '_MOD_tools_txschedulerM1',
			'cshLabel' => $fieldId
		);

		return $additionalFields;
	}

	/**
	 * @todo add a hook here
	 *
	 * @return array
	 */
	public function getTimes() {
		return array(
			'0' => $this->translate('addfields_time_all'),
			'1h' => '1 ' . $this->translate('addfields_time_h'),
			'6h' => '6 ' . $this->translate('addfields_time_h'),
			'12h' => '12 ' . $this->translate('addfields_time_h'),
			'24h' => '24 ' . $this->translate('addfields_time_h'),
			'48h' => '48 ' . $this->translate('addfields_time_h'),
			'72h' => '72 ' . $this->translate('addfields_time_h'),
			'7d' => '7 ' . $this->translate('addfields_time_d'),
			'14d' => '14 ' . $this->translate('addfields_time_d'),
			'1m' => '1 ' . $this->translate('addfields_time_m'),
			'3m' => '3 ' . $this->translate('addfields_time_m'),
			'6m' => '6 ' . $this->translate('addfields_time_m'),
			'12m' => '12 ' . $this->translate('addfields_time_m'),
		);
	}

	/**
	 * @todo add a hook here
	 *
	 * @return array
	 */
	public function getMethods() {
		return array(
			'delete_directory' => $this->translate('addfields_method_delete_directory'),
			'delete_files' => $this->translate('addfields_method_delete_files'),
			'delete_directories' => $this->translate('addfields_method_delete_directories'),
			'delete_all' => $this->translate('addfields_method_delete_all'),
			'delete_all_recursive' => $this->translate('addfields_method_delete_all_recursive'),
		);
	}

	/**
	 * @inheritdoc
	 *
	 * @return boolean
	 */
	public function validateAdditionalFields(
		array &$submittedData,
		\TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $schedulerModule
	) {
		$validInput = TRUE;

		// clean data
		$submittedData['deletefiles_directory'] = trim($submittedData['deletefiles_directory'], '/');
		$submittedData['deletefiles_time'] = trim($submittedData['deletefiles_time']);

		$path = $submittedData['deletefiles_directory'];
		if (
			!(strlen($path) > 0 &&
			file_exists(PATH_site . $path) &&
			GeneralUtility::isAllowedAbsPath(PATH_site . $path) &&
			GeneralUtility::validPathStr($path))
		) {
			$schedulerModule->addMessage(
				sprintf($this->translate('addfields_notice_path_invalid'), $path),
				FlashMessage::ERROR
			);
			$validInput = FALSE;
		}

		return $validInput;
	}

	/**
	 * @inheritdoc
	 *
	 * @return void
	 */
	public function saveAdditionalFields(array $submittedData, \TYPO3\CMS\Scheduler\Task\AbstractTask $task) {
		$task->deletefiles_directory = $submittedData['deletefiles_directory'];
		$task->deletefiles_time = $submittedData['deletefiles_time'];
		$task->deletefiles_method = $submittedData['deletefiles_method'];
	}

	/**
	 * Translate by key
	 *
	 * @param string $key
	 * @param string $prefix
	 *
	 * @return string
	 */
	protected function translate($key, $prefix = 'LLL:EXT:deletefiles/Resources/Private/Language/locallang.xml:') {
		return $this->languageService->sL($prefix . $key);
	}
}
