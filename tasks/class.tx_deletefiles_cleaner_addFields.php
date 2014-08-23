<?php

/**
 * Class tx_deletefiles_cleaner_addFields
 */
class tx_deletefiles_cleaner_addFields implements tx_scheduler_AdditionalFieldProvider {

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
	 * @param \TYPO3\CMS\Scheduler\Task\AbstractTask $task The task object being edited. Null when adding a task!
	 * @param tx_scheduler_Module|\TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $schedulerModule Reference to the scheduler backend module
	 *
	 * @return array A two dimensional array, array('Identifier' => array('fieldId' => array('code' => '', 'label' => '', 'cshKey' => '', 'cshLabel' => ''))
	 */
	public function getAdditionalFields(array &$taskInfo, $task, tx_scheduler_Module $schedulerModule) {


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
		$fieldID = 'task_deletefiles_directory';

		$fieldCode = '<input type="text" name="tx_scheduler[deletefiles_directory]" id="' . $fieldID . '" value="' . htmlspecialchars($taskInfo['deletefiles_directory']) . '">';

		$label = $this->languageService->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_label_directory');
		$label = t3lib_BEfunc::wrapInHelp('deletefiles', $fieldID, $label);
		$additionalFields[$fieldID] = array(
			'code' => $fieldCode,
			'label' => $label,
			'cshKey' => '_MOD_tools_txschedulerM1',
			'cshLabel' => $fieldID
		);


		// render time field
		$fieldID = 'task_deletefiles_time';
		$fieldValueArray = $this->getTimes();

		$fieldCode = '<select name="tx_scheduler[deletefiles_time]" id="' . $fieldID . '">';
		foreach ($fieldValueArray as $deletefiles_time => $label) {
			$fieldCode .= "\t" . '<option value="' . htmlspecialchars($deletefiles_time) . '"' . (($deletefiles_time == $taskInfo['deletefiles_time']) ? ' selected="selected"' : '') . '>' . $label . '</option>';
		}
		$fieldCode .= '</select>';

		$label = $this->languageService->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_label_time');
		$label = t3lib_BEfunc::wrapInHelp('deletefiles', $fieldID, $label);
		$additionalFields[$fieldID] = array(
			'code' => $fieldCode,
			'label' => $label,
			'cshKey' => '_MOD_tools_txschedulerM1',
			'cshLabel' => $fieldID
		);


		// render method field
		$fieldID = 'task_deletefiles_method';
		$fieldValueArray = $this->getMethods();

		$fieldCode = '<select name="tx_scheduler[deletefiles_method]" id="' . $fieldID . '">';
		foreach ($fieldValueArray as $deletefiles_method => $label) {
			$fieldCode .= "\t" . '<option value="' . htmlspecialchars($deletefiles_method) . '"' . (($deletefiles_method == $taskInfo['deletefiles_method']) ? ' selected="selected"' : '') . '>' . $label . '</option>';
		}
		$fieldCode .= '</select>';

		$label = $this->languageService->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_label_method');
		$label = t3lib_BEfunc::wrapInHelp('deletefiles', $fieldID, $label);
		$additionalFields[$fieldID] = array(
			'code' => $fieldCode,
			'label' => $label,
			'cshKey' => '_MOD_tools_txschedulerM1',
			'cshLabel' => $fieldID
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
			'0' => $this->languageService->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_time_all'),
			'1h' => '1 ' . $this->languageService->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_time_h'),
			'6h' => '6 ' . $this->languageService->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_time_h'),
			'12h' => '12 ' . $this->languageService->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_time_h'),
			'24h' => '24 ' . $this->languageService->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_time_h'),
			'48h' => '48 ' . $this->languageService->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_time_h'),
			'72h' => '72 ' . $this->languageService->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_time_h'),
			'7d' => '7 ' . $this->languageService->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_time_d'),
			'14d' => '14 ' . $this->languageService->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_time_d'),
			'1m' => '1 ' . $this->languageService->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_time_m'),
			'3m' => '3 ' . $this->languageService->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_time_m'),
			'6m' => '6 ' . $this->languageService->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_time_m'),
			'12m' => '12 ' . $this->languageService->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_time_m'),
		);
	}

	/**
	 * @todo add a hook here
	 *
	 * @return array
	 */
	public function getMethods() {
		return array(
			'delete_directory' => $this->languageService->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_method_delete_directory'),
			'delete_files' => $this->languageService->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_method_delete_files'),
			'delete_directories' => $this->languageService->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_method_delete_directories'),
			'delete_all' => $this->languageService->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_method_delete_all'),
			'delete_all_recursive' => $this->languageService->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_method_delete_all_recursive'),
		);
	}

	/**
	 * Validates the additional fields' values
	 *
	 * @param array $submittedData An array containing the data submitted by the add/edit task form
	 * @param tx_scheduler_Module|\TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $schedulerModule Reference to the scheduler backend module
	 *
	 * @return boolean TRUE if validation was ok (or selected class is not relevant), FALSE otherwise
	 */
	public function validateAdditionalFields(array &$submittedData, tx_scheduler_Module $schedulerModule) {
		$validInput = TRUE;

		// clean data
		$submittedData['deletefiles_directory'] = trim($submittedData['deletefiles_directory'], "/");
		$submittedData['deletefiles_time'] = trim($submittedData['deletefiles_time']);

		$path = $submittedData['deletefiles_directory'];
		if (!(strlen($path) > 0 && file_exists(PATH_site . $path) && t3lib_div::isAllowedAbsPath(PATH_site . $path) && t3lib_div::validPathStr($path))) {
			$schedulerModule->addMessage(
				sprintf($this->languageService->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_notice_path_invalid'), $path),
				t3lib_FlashMessage::ERROR
			);
			$validInput = FALSE;
		}

		return $validInput;
	}

	/**
	 * Takes care of saving the additional fields' values in the task's object
	 *
	 * @param array $submittedData An array containing the data submitted by the add/edit task form
	 * @param tx_scheduler_Task|\TYPO3\CMS\Scheduler\Task\AbstractTask $task Reference to the scheduler backend module
	 *
	 * @return void
	 */
	public function saveAdditionalFields(array $submittedData, tx_scheduler_Task $task) {
		$task->deletefiles_directory = $submittedData['deletefiles_directory'];
		$task->deletefiles_time = $submittedData['deletefiles_time'];
		$task->deletefiles_method = $submittedData['deletefiles_method'];
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/deletefiles/tasks/class.tx_deletefiles_cleaner_addFields.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/deletefiles/tasks/class.tx_deletefiles_cleaner_addFields.php']);
}
?>