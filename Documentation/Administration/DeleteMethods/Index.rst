.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _administration-delete-methods:

Delete Methods
--------------

Following deleting methods are available:

**Chosen directory** 

This will only check if the chosen path is old enough and deletes it with all its content.

**All old files** 

This will delete all old files within the chosen path. It will not check files within directories.

**All old directories (checks first level only, deletes recursive)** 

This will delete old directories (including content) within the chosen path. It will check the
timestamp of each directory but not its contents.

**All old files and directories (checks first level only, deletes recursive)** 

Merge of “All old files” and “All old directories (checks first level only, deletes
recursive)”. Deletes all old files and directories within the chosen path. Be aware this will only
check last modified timestamp in first level (contents of a directory will not be tested).

**All old files and directories (checks age of all files and directories recursive, ALPHA!)** 

This will check every single file and directory within the chosen path. It works recursive to make
sure every single file, even in a deep folder structure, will be tested. Please note: this
functionality is still alpha. Use with care and be aware this is a very performance hungry
operation.
