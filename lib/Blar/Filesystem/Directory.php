<?php

/**
 * @author Andreas Treichel <gmblar+github@gmail.com>
 */

namespace Blar\Filesystem;

use IteratorAggregate;
use RuntimeException;

/**
 * Class Directory
 *
 * @package Blar\Filesystem
 */
class Directory extends Item implements IteratorAggregate {

    const MODE_DEFAULT = Directory::PERMISSION_OWNER_ALL | Directory::PERMISSION_GROUP_EXECUTE | Directory::PERMISSION_GROUP_READ | Directory::PERMISSION_OTHER_EXECUTE | Directory::PERMISSION_OTHER_READ;

    /**
     * @return DirectoryIterator
     */
    public function getIterator(): DirectoryIterator {
        return new RecursiveDirectoryIterator($this);
    }

    /**
     * @param int $permissions
     * @param bool $recursive
     */
    public function create($permissions = self::MODE_DEFAULT, $recursive = false) {
        $status = @mkdir($this->getPath(), $permissions, $recursive);
        if(!$status) {
            $message = sprintf('Cannot create directory %s', $this->getPath());
            throw new RuntimeException($message);
        }
    }

    /**
     * Remove empty directories.
     */
    public function unlink() {
        rmdir($this->getPath());
    }

    /**
     * Get total space from a directory.
     *
     * @return int
     * @throws RuntimeException
     */
    public function getTotalSpace(): int {
        if(!$this->isDirectory()) {
            $message = sprintf('Cannot calculate total space on %s', $this->getPath());
            throw new RuntimeException($message);
        }
        return disk_total_space($this->getPath());
    }

    /**
     * Get free space from a directory.
     *
     * @return int
     * @throws RuntimeException
     */
    public function getFreeSpace(): int {
        if(!$this->isDirectory()) {
            $message = sprintf('Cannot calculate free space on %s', $this->getPath());
            throw new RuntimeException($message);
        }
        return disk_free_space($this->getPath());
    }

}