<?php

/**
 * @author Andreas Treichel <gmblar+github@gmail.com>
 */

namespace Blar\Filesystem;

use PHPUnit_Framework_TestCase as TestCase;

class FileTest extends TestCase {

    public function testExists() {
        $file = new File('/etc/passwd');
        $this->assertTrue($file->exists());
    }

    public function testSize() {
        $file = new File('/etc/passwd');
        $this->assertGreaterThan(4, $file->getSize());
    }

    public function testReadable() {
        $file = new File('/etc/passwd');
        $this->assertTrue($file->isReadable());
    }

    public function testWritable() {
        $file = new File('/etc/passwd');
        $this->assertFalse($file->isWritable());
    }

    public function testGetOwnerId() {
        $file = new File('/etc/passwd');
        $this->assertSame(0, $file->getOwnerId());
    }

    public function testPermissions() {
        $this->assertSame(0100, File::PERMISSION_OWNER_EXECUTE);
        $this->assertSame(0200, File::PERMISSION_OWNER_WRITE);
        $this->assertSame(0300, File::PERMISSION_OWNER_EXECUTE | File::PERMISSION_OWNER_WRITE);
        $this->assertSame(0400, File::PERMISSION_OWNER_READ);
        $this->assertSame(0500, File::PERMISSION_OWNER_EXECUTE | File::PERMISSION_OWNER_READ);
        $this->assertSame(0600, File::PERMISSION_OWNER_WRITE | File::PERMISSION_OWNER_READ);
        $this->assertSame(0700, File::PERMISSION_OWNER_EXECUTE | File::PERMISSION_OWNER_WRITE | File::PERMISSION_OWNER_READ);

        $this->assertSame(0010, File::PERMISSION_GROUP_EXECUTE);
        $this->assertSame(0020, File::PERMISSION_GROUP_WRITE);
        $this->assertSame(0030, File::PERMISSION_GROUP_EXECUTE | File::PERMISSION_GROUP_WRITE);
        $this->assertSame(0040, File::PERMISSION_GROUP_READ);
        $this->assertSame(0050, File::PERMISSION_GROUP_EXECUTE | File::PERMISSION_GROUP_READ);
        $this->assertSame(0060, File::PERMISSION_GROUP_WRITE | File::PERMISSION_GROUP_READ);
        $this->assertSame(0070, File::PERMISSION_GROUP_EXECUTE | File::PERMISSION_GROUP_WRITE | File::PERMISSION_GROUP_READ);

        $this->assertSame(0001, File::PERMISSION_OTHER_EXECUTE);
        $this->assertSame(0002, File::PERMISSION_OTHER_WRITE);
        $this->assertSame(0003, File::PERMISSION_OTHER_EXECUTE | File::PERMISSION_OTHER_WRITE);
        $this->assertSame(0004, File::PERMISSION_OTHER_READ);
        $this->assertSame(0005, File::PERMISSION_OTHER_EXECUTE | File::PERMISSION_OTHER_READ);
        $this->assertSame(0006, File::PERMISSION_OTHER_WRITE | File::PERMISSION_OTHER_READ);
        $this->assertSame(0007, File::PERMISSION_OTHER_EXECUTE | File::PERMISSION_OTHER_WRITE | File::PERMISSION_OTHER_READ);
    }

    public function testSetAndHasPermissions() {
        $file = new TempFile();
        # $file->setPermissions(0777);

        $file->setPermissions(File::PERMISSION_OWNER_ALL);
        $this->assertTrue($file->checkPermissions(0700));

        $file->setPermissions(File::PERMISSION_OWNER_ALL | File::PERMISSION_GROUP_READ);
        $this->assertTrue($file->checkPermissions(0740));

        $file->setPermissions(File::PERMISSION_OWNER_ALL | File::PERMISSION_GROUP_READ | File::PERMISSION_GROUP_WRITE | File::PERMISSION_OTHER_READ);
        $this->assertTrue($file->checkPermissions(0764));
    }

    public function testSlice() {
        $file = new File(__DIR__.'/test.mp3');
        $this->assertSame('TAG', $file->slice(-128, 3));
    }

    public function testGetName() {
        $file = new File(__FILE__);
        $this->assertSame('FileTest.php', $file->getName());
    }

    public function testGetExtension() {
        $file = new File(__FILE__);
        $this->assertSame('php', $file->getExtension());
    }

    public function testGetDirectoryName() {
        $file = new File('/tmp/phpunit_test_directory_name');
        $this->assertSame('/tmp', $file->getDirectoryName());
    }

    public function testUnlink() {
        $file = new File('/tmp/phpunit_test_unlink');
        $this->assertFalse($file->exists());

        $file->setContent('foo 42 bar');
        $this->assertTrue($file->exists());

        $file->unlink();
        $this->assertFalse($file->exists());
    }

    /**
     * @expectedException RuntimeException
     */
    public function testDoubleUnlink() {
        $file = new File('/tmp/phpunit_test_unlink');
        $this->assertFalse($file->exists());

        $file->setContent('foo 42 bar');
        $this->assertTrue($file->exists());

        $file->unlink();
        $this->assertFalse($file->exists());

        $file->unlink();
    }

    /**
     * @expectedException RuntimeException
     */
    public function testFactory() {
        Item::factory('foobar');
    }


}
