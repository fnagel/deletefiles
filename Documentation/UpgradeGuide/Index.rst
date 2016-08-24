.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


Upgrade Guide
-------------

.. only:: html

	.. contents:: Within this page
		:local:
		:depth: 3


Update from 0.0.x to 1.0.0
^^^^^^^^^^^^^^^^^^^^^^^^^^

**Overview**

* TYPO3 7.x support (tested up to TYPO3 7.6)
* Refactoring (modern structure, PHP namespaces, etc.)
* Exclude .htaccess and .htpasswd files
* Remove deleted files from FAL index


**How to upgrade**

* Clear cache in Install Tool
* Re-add all tasks in scheduler module
