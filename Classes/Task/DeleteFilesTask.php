<?php

namespace FelixNagel\DeleteFiles\Task;

/**
 * This file is part of the "deletefiles" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * Class DeleteFilesTask.
 */
class DeleteFilesTask extends AbstractTask
{
    protected bool $debugging = false;

    public ?string $deletefiles_directory = null;

    public ?string $deletefiles_regex = null;

    public ?string $deletefiles_time = null;

    public ?string $deletefiles_method = null;

    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function execute()
    {
        $this->cleanValues();

        if (!$this->isPathValid()) {
            $this->log('Invalid path: '.$this->deletefiles_directory);

            return false;
        }

        return $this->startCleaning();
    }

    /**
     * Check if given path is valid.
     *
     * @return bool
     */
    public function isPathValid()
    {
        $path = $this->deletefiles_directory;
        $publicPath = Environment::getPublicPath().'/';

        return
            strlen($path) > 0 &&
            file_exists($publicPath.$path) &&
            GeneralUtility::isAllowedAbsPath($publicPath.$path) &&
            GeneralUtility::validPathStr($path)
        ;
    }

    /**
     * @return bool
     */
    protected function startCleaning()
    {
        $items = [];
        $itemsToDelete = [];
        $path = Environment::getPublicPath().'/'.trim($this->deletefiles_directory, DIRECTORY_SEPARATOR);

        // get needed dirs and files
        $addPath = true;
        switch ($this->deletefiles_method) {
            case 'delete_directory':
                $items[0] = $path;
                $addPath = false;
                break;

            case 'delete_files':
                $items = GeneralUtility::getFilesInDir($path);
                break;
            case 'delete_directories':
                $items = GeneralUtility::get_dirs($path);
                break;

            case 'delete_all':
                $files = GeneralUtility::getFilesInDir($path);
                $dirs = GeneralUtility::get_dirs($path);
                $items = array_merge($files, $dirs);
                break;

            case 'delete_all_recursive':
                // get all files and folders and sort items from deep level to top level
                $items = array_reverse(
                    GeneralUtility::getAllFilesAndFoldersInPath($items, $path.DIRECTORY_SEPARATOR, '', true)
                );
                // remove last array item as its the input $path
                array_pop($items);
                $addPath = false;
                break;

            default:
        }

        // check filetime
        if ($this->deletefiles_time) {
            $timestamp = $this->getTimestamp();
            foreach ($items as $item) {
                if ($addPath) {
                    $item = $path.DIRECTORY_SEPARATOR.$item;
                }

                if (filemtime($item) < $timestamp) {
                    $itemsToDelete[] = $item;
                }
            }
        } else {
            if ($addPath) {
                $items = $this->prefixArrayValues($path.DIRECTORY_SEPARATOR, $items);
            }

            $itemsToDelete = $items;
        }

        if (count($itemsToDelete) > 0) {
            $flag = $this->deleteItems($itemsToDelete);
        } else {
            $flag = true;
            $this->log('No old files to delete');
        }

        return $flag;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getAdditionalInformation()
    {
        $text = '';

        $text .= $this->deletefiles_directory;
        $text .= $this->deletefiles_regex;
        $text .= ', '.$this->deletefiles_method;
        $text .= ', '.$this->deletefiles_time;

        return $text;
    }

    /**
     */
    protected function cleanValues()
    {
        $this->deletefiles_time = strip_tags($this->deletefiles_time);
        $this->deletefiles_directory = strip_tags($this->deletefiles_directory);
        $this->deletefiles_regex = strip_tags($this->deletefiles_regex);
        $this->deletefiles_method = strip_tags($this->deletefiles_method);

        $this->log('$this->time: '.$this->deletefiles_time);
        $this->log('$this->extkey: '.$this->deletefiles_directory);
        $this->log('$this->regex: '.$this->deletefiles_regex);
        $this->log('$this->method: '.$this->deletefiles_method);
    }

    /**
     * @param array $items
     *
     * @return bool
     */
    protected function deleteItems($items)
    {
        // @todo: better delete error handling
        $flag = true;
        $pattern = $this->deletefiles_regex;
        foreach ($items as $item) {
            $basename = basename($item);
            if (!empty($pattern) && !empty($basename) && !preg_match($pattern, $basename)) {
                continue;
            }
            if ($this->debugging) {
                $this->log('Use delete method ['.$this->deletefiles_method.'] on '.$item);
                continue;
            }

            switch ($this->deletefiles_method) {
                case 'delete_directory':
                case 'delete_directories':
                    $this->recursiveRemoveDirectory($item);
                    break;

                case 'delete_files':
                    $this->deleteSingleFile($item);
                    break;

                case 'delete_all':
                    if (is_dir($item)) {
                        $this->recursiveRemoveDirectory($item);
                    } else {
                        $this->deleteSingleFile($item);
                    }

                    break;

                // this works because non empty (but old) dirs cant be deleted with rmdir in PHP
                // so we do not need to remove non empty dirs from our items array
                case 'delete_all_recursive':
                    if (is_dir($item)) {
                        @rmdir($item);
                    } else {
                        $this->deleteSingleFile($item);
                    }

                    break;

                default:
            }
        }

        return $flag;
    }

    protected function getTimestamp(): bool|int
    {
        return match ($this->deletefiles_time) {
            '1h' => mktime(date('H') - 1, date('i'), date('s'), date('m'), date('d'), date('Y')),
            '6h' => mktime(date('H') - 6, date('i'), date('s'), date('m'), date('d'), date('Y')),
            '12h' => mktime(date('H') - 12, date('i'), date('s'), date('m'), date('d'), date('Y')),
            '24h' => mktime(date('H'), date('i'), date('s'), date('m'), date('d') - 1, date('Y')),
            '48h' => mktime(date('H'), date('i'), date('s'), date('m'), date('d') - 2, date('Y')),
            '72h' => mktime(date('H'), date('i'), date('s'), date('m'), date('d') - 3, date('Y')),
            '7d' => mktime(date('H'), date('i'), date('s'), date('m'), date('d') - 7, date('Y')),
            '14d' => mktime(date('H'), date('i'), date('s'), date('m'), date('d') - 14, date('Y')),
            '1m' => mktime(date('H'), date('i'), date('s'), date('m') - 1, date('d'), date('Y')),
            '3m' => mktime(date('H'), date('i'), date('s'), date('m') - 3, date('d'), date('Y')),
            '6m' => mktime(date('H'), date('i'), date('s'), date('m') - 6, date('d'), date('Y')),
            '12m' => mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y') - 1),
            default => false,
        };
    }

    /**
     * Delete single file.
     *
     * @param string $file
     */
    protected function deleteSingleFile($file)
    {
        $filename = basename($file);
        if ($filename === '.htaccess' || $filename === '.htpasswd') {
            return;
        }

        // Remove file from FAL if indexed
        try {
            $fileObject = GeneralUtility::makeInstance(ResourceFactory::class)->retrieveFileOrFolderObject($file);
            if ($fileObject !== null) {
                $fileObject->delete();
            }
        } catch (\Exception $exception) {
            $this->log('Exception '.$exception->getCode().': '.$exception->getMessage());
        }

        // Remove file physically
        @unlink($file);
    }

    /**
     * Delete directory recursive.
     *
     * @param string $dir
     */
    protected function recursiveRemoveDirectory($dir)
    {
        $objects = scandir($dir);

        foreach ($objects as $object) {
            if ($object !== '.' && $object !== '..') {
                if (filetype($dir.DIRECTORY_SEPARATOR.$object) === 'dir') {
                    $this->recursiveRemoveDirectory($dir.DIRECTORY_SEPARATOR.$object);
                } else {
                    $this->deleteSingleFile($dir.DIRECTORY_SEPARATOR.$object);
                }
            }
        }

        reset($objects);
        @rmdir($dir);
    }

    /**
     * Adds a prefix to every array value.
     *
     * @param $prefix
     * @param $array
     *
     * @return array
     */
    protected function prefixArrayValues($prefix, $array)
    {
        $callback = static fn($str): string => sprintf('%s', $prefix) . $str;

        return array_map($callback, $array);
    }

    /**
     * @param $msg
     */
    protected function log($msg)
    {
        if ($this->debugging) {
            \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($msg);
        }
    }
}
