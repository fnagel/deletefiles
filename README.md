# Delete Files

TYPO3 CMS Extension Delete Files.

Scheduler task for deleting old files and folder. Supports FAL.

http://typo3.org/extensions/repository/view/deletefiles 


## Upgrade guide

### Update from 0.0.x to 1.0.0

**Changelog**

* TYPO3 7.x support (tested up to TYPO3 7.6)
* Refactoring (modern structure, PHP namespaces, etc.)
* Exclude .htaccess and .htpasswd files
* Remove deleted files from FAL index

**How to upgrade**

* Clear cache in Install Tool
* Re-add all tasks in scheduler module


## ToDo

* Migrate docs to ReSt


## Contribution

Any help is appreciated. Please feel free to drop me a line, open issues or send pull requests.

It is possible to sponsor features and bugfixes!


## Donation

Please consider a donation: http://www.felixnagel.com/donate/