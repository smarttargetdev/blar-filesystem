<?php

/**
 * @author Andreas Treichel <gmblar+github@gmail.com>
 */

namespace Blar\Filesystem;

use RuntimeException;

/**
 * Class Item
 *
 * @package Blar\Filesystem
 */
class Item {

    const PERMISSION_OWNER_NONE = 0;

    const PERMISSION_OWNER_EXECUTE = 0100;

    const PERMISSION_OWNER_WRITE = 0200;

    const PERMISSION_OWNER_READ = 0400;

    const PERMISSION_OWNER_ALL = self::PERMISSION_OWNER_EXECUTE | self::PERMISSION_OWNER_WRITE | self::PERMISSION_OWNER_READ;


    const PERMISSION_GROUP_NONE = 0;

    const PERMISSION_GROUP_EXECUTE = 0010;

    const PERMISSION_GROUP_WRITE = 0020;

    const PERMISSION_GROUP_READ = 0040;

    const PERMISSION_GROUP_ALL = self::PERMISSION_GROUP_EXECUTE | self::PERMISSION_GROUP_WRITE | self::PERMISSION_GROUP_READ;


    const PERMISSION_OTHER_NONE = 0;

    const PERMISSION_OTHER_EXECUTE = 0001;

    const PERMISSION_OTHER_WRITE = 0002;

    const PERMISSION_OTHER_READ = 0004;

    const PERMISSION_OTHER_ALL = self::PERMISSION_OTHER_EXECUTE | self::PERMISSION_OTHER_WRITE | self::PERMISSION_OTHER_READ;

    /**
     * @var string
     */
    private $path;

    /**
     * @param string $path
     *
     * @return Item
     */
    public static function factory(string $path): Item {
        if(is_dir($path)) {
            return new Directory($path);
        }
        if(is_file($path)) {
            return new File($path);
        }
        $message = sprintf('Unsupported type of %s', $path);
        throw new RuntimeException($message);
    }

    /**
     * File constructor.
     *
     * @param string $path
     */
    public function __construct(string $path) {
        $this->setPath($path);
    }

    /**
     * @return string
     */
    public function __toString(): string {
        return $this->getPath();
    }

    /**
     * @return string
     */
    public function getPath(): string {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path) {
        $this->path = $path;
    }

    /**
     * @return bool
     */
    public function hasPath(): bool {
        return $this->path !== NULL;
    }

    /**
     * Get the last access time.
     *
     * @return int
     */
    public function getAccessTime(): int {
        return fileatime($this->getPath());
    }

    /**
     * Get the createdInode change time.
     *
     * @return int
     */
    public function getChangeTime(): int {
        return filectime($this->getPath());
    }

    /**
     * Get the modification time.
     *
     * @return int
     */
    public function getModificationTime(): int {
        return filemtime($this->getPath());
    }


    /**
     * Get the id of the createdInode.
     *
     * @return int
     */
    public function getInode(): int {
        return fileinode($this->getPath());
    }

    /**
     * @param int $owner
     *
     * @throws RuntimeException
     */
    public function setOwnerId(int $owner) {
        $status = @chown($this->getPath(), $owner);
        if(!$status) {
            $message = sprintf('Cannot set owner of file %s to %u', $this->getPath(), $owner);
            throw new RuntimeException($message);
        }
    }

    /**
     * @return int
     */
    public function getOwnerId(): int {
        return fileowner($this->getPath());
    }

    /**
     * @param int $group
     *
     * @throws RuntimeException
     */
    public function setGroupId(int $group) {
        $status = @chgrp($this->getPath(), $group);
        if(!$status) {
            $message = sprintf('Cannot change group of file %s to %u', $this->getPath(), $group);
            throw new RuntimeException($message);
        }
    }

    /**
     * @param string $group
     *
     * @throws RuntimeException
     */
    public function setGroupName(string $group) {
        $status = @chgrp($this->getPath(), $group);
        if(!$status) {
            $message = sprintf('Cannot change group of file %s to %s', $this->getPath(), $group);
            throw new RuntimeException($message);
        }
    }

    /**
     * @return int
     */
    public function getGroupId(): int {
        return filegroup($this->getPath());
    }

    /**
     * @param int $permissions
     *
     * @throws RuntimeException
     */
    public function setPermissions(int $permissions) {
        $status = @chmod($this->getPath(), $permissions);
        if(!$status) {
            $message = sprintf('Cannot set permissions of file %s to %o', $this->getPath(), $permissions);
            throw new RuntimeException($message);
        }
        $this->clearStatCache();
    }

    /**
     * @return int
     */
    public function getPermissions(): int {
        return fileperms($this->getPath());
    }

    /**
     * @param int $permissions
     *
     * @return bool
     */
    public function checkPermissions(int $permissions): bool {
        return ($this->getPermissions() & $permissions) == $permissions;
    }

    /**
     * @return string
     */
    public function getRealPath(): string {
        return realpath($this->getPath());
    }

    /**
     * @return int
     */
    public function getSize(): int {
        return filesize($this->getPath());
    }

    /**
     * @return bool
     */
    public function exists(): bool {
        return file_exists($this->getPath());
    }

    /**
     * @param string $target
     *
     * @return File
     */
    public function rename(string $target): File {
        rename($this->getPath(), $target);
        return new File($target);
    }

    /**
     * @return bool
     */
    public function isDirectory(): bool {
        return is_dir($this->getPath());
    }

    /**
     * @return bool
     */
    public function isFile(): bool {
        return is_file($this->getPath());
    }

    /**
     * @return bool
     */
    public function isLink(): bool {
        return is_link($this->getPath());
    }

    /**
     * @return bool
     */
    public function isUploaded(): bool {
        return is_uploaded_file($this->getPath());
    }

    /**
     * @return bool
     */
    public function isReadable(): bool {
        return is_readable($this->getPath());
    }

    /**
     * @return bool
     */
    public function isWritable(): bool {
        return is_writable($this->getPath());
    }

    /**
     * @return bool
     */
    public function isExecutable(): bool {
        return is_executable($this->getPath());
    }

    /**
     * @param string $target
     */
    public function link(string $target) {
        symlink($this->getPath(), $target);
    }

    /**
     * @param int $modificationTime
     * @param int $accessTime
     */
    public function touch(int $modificationTime, int $accessTime = NULL) {
        touch($this->getPath(), $modificationTime, $accessTime);
        $this->clearStatCache();
    }

    /**
     * @return Directory
     */
    /*
    public function getDirectory(): Directory {
        $path = pathinfo($this->getPath(), PATHINFO_DIRNAME);
        return new Directory($path);
    }
    */

    public function getDirectoryName(): string {
        return pathinfo($this->getPath(), PATHINFO_DIRNAME);
    }

    /**
     * @return string
     */
    public function getName(): string {
        return pathinfo($this->getPath(), PATHINFO_BASENAME);
    }

    /**
     * @return string
     */
    public function getExtension(): string {
        return pathinfo($this->getPath(), PATHINFO_EXTENSION);
    }

    /**
     * Clear Status Cache for this file.
     *
     * @param bool $clearRealpathCache
     */
    public function clearStatCache($clearRealpathCache = false) {
        clearstatcache($clearRealpathCache, $this->getPath());
    }

    /**
     * @return array
     */
    public function getStats() {
        return $this->filterNumericArrayKeys(stat($this->getPath()));
    }

    /**
     * @param array $array
     *
     * @return array
     */
    private function filterNumericArrayKeys(array $array) {
        return array_filter($array, function($key) {
            return !is_numeric($key);
        }, ARRAY_FILTER_USE_KEY);
    }

}
