<?php

/**
 * @author Andreas Treichel <gmblar+github@gmail.com>
 */

namespace Blar\Filesystem;

use PHPUnit_Framework_TestCase as TestCase;
use RuntimeException;

/**
 * Class DirectoryTest
 *
 * @package Blar\Filesystem
 */
class DirectoryTest extends TestCase {

    public function testGetTotalSpace() {
        $directory = new Directory('/tmp');
        $this->assertGreaterThan(0, $directory->getTotalSpace());
    }

    public function testGetFreeSpace() {
        $directory = new Directory('/tmp');
        $this->assertGreaterThan(0, $directory->getFreeSpace());
    }

    /**
     * @expectedException RuntimeException
     */
    public function testGetTotalSpaceOnMissingDirectory() {
        $directory = new Directory('/foobar');
        $directory->getTotalSpace();
    }

    /**
     * @expectedException RuntimeException
     */
    public function testGetFreeSpaceOnMissingDirectory() {
        $directory = new Directory('/foobar');
        $directory->getFreeSpace();
    }

    public function testCreate() {
        $directory = new Directory('/tmp/phpunit_test_create');
        if($directory->exists()) {
            $directory->unlink();
        }

        $this->assertFalse($directory->exists());

        $directory->create();
        $this->assertTrue($directory->exists());

        $directory->unlink();
        $this->assertFalse($directory->exists());
    }

    /**
     * @expectedException RuntimeException
     */
    public function testCreateExisting() {
        $directory = new Directory('/tmp/phpunit_test_create');
        if($directory->exists()) {
            $directory->unlink();
        }

        $directory->create();
        $directory->create();
    }

    public function testUnlink() {
        $directory = new Directory('/tmp/phpunit_test_unlink');
        $this->assertFalse($directory->exists());

        $directory->create();
        $this->assertTrue($directory->exists());

        $directory->unlink();
        $this->assertFalse($directory->exists());

    }

    public function testIsExecutable() {
        $directory = new Directory(__DIR__);
        $this->assertTrue($directory->isExecutable());
    }

    /*
    public function testLink() {
        $directory = new Directory('/tmp');
        $directory->link(__DIR__.'/temp');
    }
    */

}
