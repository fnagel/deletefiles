<?php

namespace TYPO3\DeleteFiles\Task;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\ResourceFactory;

/**
 * Class DeleteFilesTask.
 */
class DeleteFilesTask extends \TYPO3\CMS\Scheduler\Task\AbstractTask
{
    /**
     * @var bool
     */
    protected $debugging = false;

    /**
     * @var string
     */
    public $deletefiles_directory;

    /**
     * @var string
     */
    public $deletefiles_time;

    /**
     * @var string
     */
    public $deletefiles_method;

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

        return
            strlen($path) > 0 &&
            file_exists(PATH_site.$path) &&
            GeneralUtility::isAllowedAbsPath(PATH_site.$path) &&
            GeneralUtility::validPathStr($path)
        ;
    }

    /**
     * @return bool
     */
    protected function startCleaning()
    {
        $items = array();
        $itemsToDelete = array();
        $path = PATH_site.trim($this->deletefiles_directory, DIRECTORY_SEPARATOR);

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
        $this->deletefiles_method = strip_tags($this->deletefiles_method);

        $this->log('$this->time: '.$this->deletefiles_time);
        $this->log('$this->extkey: '.$this->deletefiles_directory);
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

        foreach ($items as $item) {
            if ($this->debugging === true) {
                $this->log('Use delete method ['.$this->deletefiles_method.'] on '.$item);

                return true;
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

    /**
     * @return bool|int
     */
    protected function getTimestamp()
    {
        $timestamp = false;

        // choose correct time
        switch ($this->deletefiles_time) {

            case '1h':
                $timestamp = mktime(date('H') - 1, date('i'), date('s'), date('m'), date('d'), date('Y'));
                break;

            case '6h':
                $timestamp = mktime(date('H') - 6, date('i'), date('s'), date('m'), date('d'), date('Y'));
                break;

            case '12h':
                $timestamp = mktime(date('H') - 12, date('i'), date('s'), date('m'), date('d'), date('Y'));
                break;

            case '24h':
                $timestamp = mktime(date('H'), date('i'), date('s'), date('m'), date('d') - 1, date('Y'));
                break;

            case '48h':
                $timestamp = mktime(date('H'), date('i'), date('s'), date('m'), date('d') - 2, date('Y'));
                break;

            case '72h':
                $timestamp = mktime(date('H'), date('i'), date('s'), date('m'), date('d') - 3, date('Y'));
                break;

            case '7d':
                $timestamp = mktime(date('H'), date('i'), date('s'), date('m'), date('d') - 7, date('Y'));
                break;

            case '14d':
                $timestamp = mktime(date('H'), date('i'), date('s'), date('m'), date('d') - 14, date('Y'));
                break;

            case '1m':
                $timestamp = mktime(date('H'), date('i'), date('s'), date('m') - 1, date('d'), date('Y'));
                break;

            case '3m':
                $timestamp = mktime(date('H'), date('i'), date('s'), date('m') - 3, date('d'), date('Y'));
                break;

            case '6m':
                $timestamp = mktime(date('H'), date('i'), date('s'), date('m') - 6, date('d'), date('Y'));
                break;

            case '12m':
                $timestamp = mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y') - 1);
                break;

            default:
        }

        return $timestamp;
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
            $fileObject = ResourceFactory::getInstance()->retrieveFileOrFolderObject($file);
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
            if ($object != '.' && $object != '..') {
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
        $callback = create_function('$str', 'return "'.$prefix.'".$str;');

        return array_map($callback, $array);
    }

    /**
     * @param $msg
     * @param int $status
     */
    protected function log($msg, $status = 1)
    {
        // higher status for debugging
        if ($this->debugging) {
            \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($msg);
            $status = 3;
        }
        // write dev log if enabled
        if (TYPO3_DLOG) {
            GeneralUtility::devLog($msg, 'deletefiles', $status);
        }
    }
}
