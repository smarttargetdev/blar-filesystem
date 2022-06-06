<?php

/**
 * @author Andreas Treichel <gmblar+github@gmail.com>
 */

namespace Blar\Filesystem;

/**
 * Class TempFile
 *
 * @package Blar\Streams
 */
class TempFile extends File {

    /**
     * @var string
     */
    private $directory;

    /**
     * @var string
     */
    private $prefix;

    /**
     * Inode of the created file.
     *
     * @var int
     */
    private $createdInode;

    /**
     * TempFile constructor.
     *
     * @param string $prefix
     */
    public function __construct($prefix = 'temp_') {
        $this->setPrefix($prefix);
    }

    public function __destruct() {
        // Unlink only if the inode is the same
        if(!$this->exists()) {
            return;
        }
        if($this->getInode() !== $this->createdInode) {
            return;
        }
        $this->unlink();
    }

    /**
     * @return string
     */
    public function getDirectory(): string {
        if(!$this->directory) {
            $this->directory = sys_get_temp_dir();
        }
        return $this->directory;
    }

    /**
     * @return string
     */
    public function getPrefix(): string {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     */
    public function setPrefix(string $prefix) {
        $this->prefix = $prefix;
    }

    /**
     * @return string
     */
    public function getPath(): string {
        if(!$this->hasPath()) {
            $this->setPath($this->createPath());
            $this->createdInode = $this->getInode();
        }
        return parent::getPath();
    }

    /**
     * @return string
     */
    private function createPath(): string {
        return tempnam($this->getDirectory(), $this->getPrefix());
    }

}
