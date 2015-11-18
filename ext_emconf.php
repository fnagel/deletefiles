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
	'description' => 'Scheduler task for deleting old files and folder. Supports FAL.',
	'category' => 'services',
	'author' => 'Felix Nagel',
	'author_email' => 'info@felixnagel.com',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'clearCacheOnLoad' => 0,
	'version' => '1.0.1-dev',
	'constraints' => array(
		'depends' => array(
			'scheduler' => '',
			'typo3' => '6.2.0-7.6.99',
		),
		'conflicts' => array(),
		'suggests' => array(),
	),
	'suggests' => array(),
);
