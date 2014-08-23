<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "deletefiles".
 *
 * Auto generated 11-03-2013 21:41
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Delete old files',
	'description' => 'This scheduler task helps to maintain your TYPO3 installation and / or to improve the privacy of your customers by deleting old files in specific intervals.',
	'category' => 'services',
	'author' => 'Felix Nagel',
	'author_email' => 'info@felixnagel.com',
	'shy' => '',
	'dependencies' => 'scheduler',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.0.2',
	'constraints' => array(
		'depends' => array(
			'scheduler' => '',
			'typo3' => '4.3.0-4.7.99',
		),
		'conflicts' => array(),
		'suggests' => array(),
	),
	'_md5_values_when_last_written' => 'a:9:{s:9:"ChangeLog";s:4:"e2ec";s:16:"ext_autoload.php";s:4:"1100";s:12:"ext_icon.gif";s:4:"4a76";s:17:"ext_localconf.php";s:4:"ce0b";s:14:"doc/manual.sxw";s:4:"4cea";s:18:"lang/locallang.xml";s:4:"0c8c";s:19:"lang/locallang2.xml";s:4:"b1ed";s:38:"tasks/class.tx_deletefiles_cleaner.php";s:4:"d085";s:48:"tasks/class.tx_deletefiles_cleaner_addFields.php";s:4:"2fed";}',
	'suggests' => array(),
);

?>