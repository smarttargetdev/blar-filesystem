<?php

/**
 * @author Andreas Treichel <gmblar+github@gmail.com>
 */

namespace Blar\Filesystem;

use PHPUnit_Framework_TestCase as TestCase;

class DirectoryIteratorTest extends TestCase {

    public function testIterator() {
        $directory = new Directory(__DIR__.'/../../..');
        iterator_to_array($directory);
    }

}
