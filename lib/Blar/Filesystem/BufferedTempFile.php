<?php

/**
 * @author Andreas Treichel <gmblar+github@gmail.com>
 */

namespace Blar\Filesystem;

/**
 * Class BufferedTempFile
 *
 * @package Blar\Filesystem
 */
class BufferedTempFile extends File {

    /**
     * @var int In Bytes.
     */
    private $maxMemory;

    /**
     * BufferedTempFile constructor.
     *
     * @param int $maxMemory In Bytes.
     */
    public function __construct($maxMemory = 2097152) {
        $this->setMaxMemory($maxMemory);
    }

    /**
     * @return int In Bytes.
     */
    public function getMaxMemory(): int {
        return $this->maxMemory;
    }

    /**
     * Maximum amount of data to hold in memory.
     *
     * @param int $maxMemory In Bytes.
     */
    public function setMaxMemory(int $maxMemory) {
        $this->maxMemory = $maxMemory;
    }

    /**
     * @return string
     */
    public function getPath(): string {
        if(!$this->hasPath()) {
            $this->setPath($this->createPath());
        }
        return parent::getPath();
    }

    /**
     * @return string
     */
    private function createPath(): string {
        return sprintf('php://temp/maxmemory:%u', $this->getMaxMemory());
    }

}