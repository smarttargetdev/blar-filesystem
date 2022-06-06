<?php

/**
 * @author Andreas Treichel <gmblar+github@gmail.com>
 */

namespace Blar\Filesystem;

use Countable;
use RuntimeException;

/**
 * Class File
 *
 * @package Blar\Filesystem
 */
class File extends Item implements Countable {

    /**
     * @param int $mode Stream::MODE_*
     *
     * @return FileStream
     */
    public function open(int $mode): FileStream {
        return new FileStream($this->getPath(), $mode);
    }

    /**
     * @param string $target
     *
     * @return File
     */
    public function copy(string $target): File {
        copy($this->getPath(), $target);
        return new File($target);
    }

    /**
     * @param string $content
     */
    public function setContent(string $content) {
        file_put_contents($this->getPath(), $content);
    }

    /**
     * @return string
     */
    public function getContent(): string {
        return file_get_contents($this->getPath());
    }

    /**
     * @param string $data
     */
    public function append(string $data) {
        file_put_contents($this->getPath(), $data, FILE_APPEND);
    }

    /**
     * Slice a part from a file.
     *
     * @param int $offset
     * @param int $length
     *
     * @return string
     */
    public function slice(int $offset, int $length = NULL): string {
        // Offset does not support negative values
        if($offset < 0) {
            $offset += $this->getSize();
        }
        // Length has no default value
        if($length === NULL) {
            return file_get_contents($this, FALSE, NULL, $offset);
        }
        return file_get_contents($this, FALSE, NULL, $offset, $length);
    }

    /**
     * Unlink (delete) a file.
     */
    public function unlink() {
        $status = @unlink($this->getPath());
        if(!$status) {
            $message = sprintf('Cannot unlink %s', $this->getPath());
            throw new RuntimeException($message);
        }
    }

    /**
     * @param string $target
     */
    public function createHardLink(string $target) {
        link($this->getPath(), $target);
    }

    /**
     * @return int
     */
    public function count(): int {
        return $this->getSize();
    }

}
