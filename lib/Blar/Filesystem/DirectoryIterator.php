<?php

/**
 * @author Andreas Treichel <gmblar+github@gmail.com>
 */

namespace Blar\Filesystem;

use Iterator;

/**
 * Class DirectoryIterator
 *
 * @package Blar\Filesystem
 */
class DirectoryIterator implements Iterator {

    /**
     * @var resource
     */
    private $handle;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var int
     */
    protected $key = -1;

    /**
     * @var Item
     */
    protected $current;

    /**
     * DirectoryIterator constructor.
     *
     * @param string $path
     */
    public function __construct(string $path) {
        $this->path = $path;
        $this->handle = opendir($this->path);
    }

    public function __destruct() {
        closedir($this->handle);
    }

    /**
     * @return Item
     */
    public function current(): Item {
        return $this->current;
    }

    /**
     * @return int
     */
    public function key(): int {
        return $this->key;
    }

    public function next() {
        do {
            $this->current = readdir($this->handle);
        }
        while($this->isDotFile($this->current));
        if($this->valid()) {
            $this->key++;
            $this->current = Item::factory($this->path.'/'.$this->current);
        }
    }

    public function rewind() {
        rewinddir($this->handle);
        $this->next();
    }

    /**
     * @return bool
     */
    public function valid(): bool {
        return $this->current !== false;
    }

    /**
     * @param string $fileName
     *
     * @return bool
     */
    private function isDotFile(string $fileName): bool {
        return in_array($fileName, ['.', '..']);
    }

}
