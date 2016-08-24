.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _administration:

Administration
==============

Just add a new scheduler task via scheduler module.
You need to define the default values and the deletefiles specific ones:

**Path**  

Define the path to clean. No ending slash. Please double check if its correct.

**Minimum age** 

The minimum age of a file or a directory to be deleted by this extension. Checks for the last
modified timestamp.

**Delete Method** 

What to delete and how to check which files to delete. Please see “Delete Methods” section
below.


.. toctree::
    :maxdepth: 2
    :titlesonly:

    DeleteMethods/Index
