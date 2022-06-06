<?php

/**
 * @author Andreas Treichel <gmblar+github@gmail.com>
 */

namespace Blar\Filesystem;

use PHPUnit_Framework_TestCase as TestCase;
use RecursiveIteratorIterator;

class RecursiveDirectoryIteratorTest extends TestCase {

    public function testIterator() {
        $directory = new Directory(__DIR__.'/../..');
        $iterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::SELF_FIRST);
        iterator_to_array($iterator);
    }

}
