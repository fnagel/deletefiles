<?php

class tx_deletefiles_cleaner_addFields implements tx_scheduler_AdditionalFieldProvider {

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

		$label = $GLOBALS['LANG']->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_label_directory');
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

		$label = $GLOBALS['LANG']->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_label_time');
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

		$label = $GLOBALS['LANG']->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_label_method');
		$label = t3lib_BEfunc::wrapInHelp('deletefiles', $fieldID, $label);
		$additionalFields[$fieldID] = array(
			'code' => $fieldCode,
			'label' => $label,
			'cshKey' => '_MOD_tools_txschedulerM1',
			'cshLabel' => $fieldID
		);

		return $additionalFields;
	}

	public function getTimes() {
		// TODO add a hook for adding times
		return array(
			'0' => $GLOBALS['LANG']->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_time_all'),
			'1h' => '1 ' . $GLOBALS['LANG']->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_time_h'),
			'6h' => '6 ' . $GLOBALS['LANG']->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_time_h'),
			'12h' => '12 ' . $GLOBALS['LANG']->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_time_h'),
			'24h' => '24 ' . $GLOBALS['LANG']->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_time_h'),
			'48h' => '48 ' . $GLOBALS['LANG']->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_time_h'),
			'72h' => '72 ' . $GLOBALS['LANG']->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_time_h'),
			'7d' => '7 ' . $GLOBALS['LANG']->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_time_d'),
			'14d' => '14 ' . $GLOBALS['LANG']->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_time_d'),
			'1m' => '1 ' . $GLOBALS['LANG']->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_time_m'),
			'3m' => '3 ' . $GLOBALS['LANG']->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_time_m'),
			'6m' => '6 ' . $GLOBALS['LANG']->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_time_m'),
			'12m' => '12 ' . $GLOBALS['LANG']->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_time_m'),
		);
	}

	public function getMethods() {
		// TODO add a hook for adding times
		return array(
			'delete_directory' => $GLOBALS['LANG']->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_method_delete_directory'),
			'delete_files' => $GLOBALS['LANG']->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_method_delete_files'),
			'delete_directories' => $GLOBALS['LANG']->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_method_delete_directories'),
			'delete_all' => $GLOBALS['LANG']->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_method_delete_all'),
			'delete_all_recursive' => $GLOBALS['LANG']->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_method_delete_all_recursive'),
		);
	}

	/**
	 * Validates the additional fields' values
	 *
	 * @param    array $submittedData An array containing the data submitted by the add/edit task form
	 * @param    tx_scheduler_Module $schedulerModule Reference to the scheduler backend module
	 * @return    boolean    True if validation was ok (or selected class is not relevant), false otherwise
	 */
	public function validateAdditionalFields(array &$submittedData, tx_scheduler_Module $schedulerModule) {
		$validInput = TRUE;

		// clean data
		$submittedData['deletefiles_directory'] = trim($submittedData['deletefiles_directory'], "/");
		$submittedData['deletefiles_time'] = trim($submittedData['deletefiles_time']);
		$submittedData['deletefiles_method'] = $submittedData['deletefiles_method'];

		$path = $submittedData['deletefiles_directory'];
		if (!(strlen($path) > 0 && file_exists(PATH_site . $path) && t3lib_div::isAllowedAbsPath(PATH_site . $path) && t3lib_div::validPathStr($path))) {
			$schedulerModule->addMessage(
				sprintf($GLOBALS['LANG']->sL('LLL:EXT:deletefiles/lang/locallang.xml:addfields_notice_path_invalid'), $path),
				t3lib_FlashMessage::ERROR
			);
			$validInput = FALSE;
		}

		return $validInput;
	}


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