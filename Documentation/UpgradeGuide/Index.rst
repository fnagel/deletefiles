.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


Upgrade Guide
-------------

.. only:: html

	.. contents:: Within this page
		:local:
		:depth: 3


Update from 1.0.2 to 1.0.3
^^^^^^^^^^^^^^^^^^^^^^^^^^

**Overview**

* TYPO3 9.x support
* PHP 7.2 support
* Remove TYPO3 6.x and 7.x support
* Remove PHP < 7.0 support
* Changed PHP namespace to `FelixNagel`
* Switch to PSR-2 CGL

**How to upgrade**

* Use "Clear all caches including PHP opcode cache" and "Dump Autoload Information" in the install tool (if needed for your setup)
* Re-add all tasks in scheduler module


Update from 1.0.0 to 1.0.2
^^^^^^^^^^^^^^^^^^^^^^^^^^

**Overview**

* TYPO3 8.x support
* Rework and improve documentation


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
