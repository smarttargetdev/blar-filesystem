<?php

/**
 * @author Andreas Treichel <gmblar+github@gmail.com>
 */

namespace Blar\Filesystem;

use RecursiveIterator;

/**
 * Class RecursiveDirectoryIterator
 *
 * @package Blar\Filesystem
 */
class RecursiveDirectoryIterator extends DirectoryIterator implements RecursiveIterator {

    /**
     * @return bool
     */
    public function hasChildren(): bool {
        return is_dir($this->current);
    }

    /**
     * @return RecursiveDirectoryIterator
     */
    public function getChildren(): RecursiveDirectoryIterator {
        return new RecursiveDirectoryIterator($this->current);
    }

}
