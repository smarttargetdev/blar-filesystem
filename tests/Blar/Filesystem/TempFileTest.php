<?php

/**
 * @author Andreas Treichel <gmblar+github@gmail.com>
 */

namespace Blar\Filesystem;

use PHPUnit_Framework_TestCase as TestCase;
use RuntimeException;

class TempFileTest extends TestCase {

    public function testRemove() {
        $temp = new TempFile();
        $this->assertTrue($temp->exists());

        $file = new File($temp);
        $this->assertTrue($file->exists());

        unset($temp);
        $this->assertFalse($file->exists());
    }

    public function testCopy() {
        $temp = new TempFile();
        $temp->copy('/tmp/phpunit_test_copy');
    }

    public function testTouch() {
        $temp = new TempFile();
        $temp->touch(23, 42);

        $this->assertSame(23, $temp->getModificationTime());
        $this->assertSame(42, $temp->getAccessTime());
    }

    public function testSetAndGetContents() {
        $temp = new TempFile();
        $temp->setContent('foobar');
        $this->assertSame('foobar', $temp->getContent());
    }

    public function testSlice() {
        $temp = new TempFile();
        $temp->setContent('foo 42 bar');

        // TODO file_put_contents does not update the stat cache?
        $temp->clearStatCache();

        $this->assertSame('42', $temp->slice(4, 2));
        $this->assertSame('42', $temp->slice(-6, 2));

        $this->assertSame('bar', $temp->slice(-3));
        $this->assertSame('bar', $temp->slice(-3, 3));
    }

    public function testUnlink() {
        $temp = new TempFile();
        # $this->assertFalse($temp->exists());

        $temp->setContent('foo 42 bar');
        $this->assertTrue($temp->exists());

        $temp->unlink();
        $this->assertFalse($temp->exists());
    }

    public function testAppend() {
        $temp = new TempFile();
        $temp->setContent('foo');
        $this->assertSame('foo', $temp->getContent());

        $temp->append('bar');
        $this->assertSame('foobar', $temp->getContent());
    }

    public function testSetPermissions() {
        $temp = new TempFile();
        $temp->setPermissions(File::PERMISSION_OWNER_ALL | File::PERMISSION_GROUP_ALL);
        $this->assertSame(0770, $temp->getPermissions() & 0777);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testSetPermissionsOnMissingFile() {
        $temp = new TempFile();
        $temp->unlink();
        $temp->setPermissions(File::PERMISSION_OWNER_ALL | File::PERMISSION_GROUP_ALL);
    }

    public function testSymlink() {
        $file = new TempFile();

        $file->append('foo');
        $this->assertSame('foo', $file->getContent());

        $link = new TempFile();
        $link->unlink();
        $file->link($link);
        $this->assertSame('foo', $link->getContent());
    }

    public function testRealpath() {
        $file = new TempFile();

        $link = new TempFile();
        $link->unlink();
        $file->link($link);

        $this->assertSame($file->getPath(), $link->getRealPath());
    }

    public function testRename() {
        $file1 = new TempFile();
        $file2 = $file1->rename($file1->getPath().'.renamed');

        $this->assertNotSame($file1->getPath(), $file2->getPath());

        $this->assertNotSame('renamed', $file1->getExtension());
        $this->assertSame('renamed', $file2->getExtension());
    }

    public function testIsFile() {
        $file = new TempFile();
        $this->assertTrue($file->isFile());
    }

    public function testIsUploadedFile() {
        $file = new TempFile();
        $this->assertFalse($file->isUploaded());
    }

    public function testGetStats() {
        $file = new TempFile();
        $this->assertArrayHasKey('dev', $file->getStats());
        $this->assertArrayHasKey('ino', $file->getStats());
        $this->assertArrayHasKey('mode', $file->getStats());
        $this->assertArrayHasKey('nlink', $file->getStats());
        $this->assertArrayHasKey('uid', $file->getStats());
        $this->assertArrayHasKey('gid', $file->getStats());
        $this->assertArrayHasKey('rdev', $file->getStats());
        $this->assertArrayHasKey('size', $file->getStats());
        $this->assertArrayHasKey('atime', $file->getStats());
        $this->assertArrayHasKey('mtime', $file->getStats());
        $this->assertArrayHasKey('ctime', $file->getStats());
        $this->assertArrayHasKey('blksize', $file->getStats());
        $this->assertArrayHasKey('blocks', $file->getStats());
    }

    public function testIsLink() {
        $file = new TempFile();
        $this->assertFalse($file->isLink());
    }

    public function testAutoremove() {
        $file1 = new TempFile();
        $file1->setContent('foobar');

        $file2 = new File($file1->getPath());
        unset($file1);

        $this->assertFalse($file2->exists());
    }

    public function testDontAutoremoveIfSwapped() {
        $this->markTestSkipped('This test failed on travis');
        $file1 = new TempFile();
        $file1->setContent('foo');
        $this->assertTrue($file1->exists());

        $file2 = new File($file1->getPath());
        $file1->unlink();
        $this->assertFalse($file1->exists());

        $file2->setContent('bar');
        unset($file1);
        $this->assertTrue($file2->exists());
    }

    public function testHardlink() {
        $file1 = new TempFile();
        $path = $file1->getPath().'-hardlink';
        $file1->createHardLink($path);
        $file1->setContent('foo');

        $file2 = new File($path);
        $this->assertSame('foo', $file2->getContent());

        $file1->append('bar');
        $this->assertSame('foobar', $file2->getContent());
    }

    public function testSetAndGetGroupId() {
        $file = new TempFile();
        $groupId = $file->getGroupId();
        $file->setGroupId($groupId);
    }

    public function testGetSizeAndCount() {
        $file = new TempFile();

        $file->setContent('foo');
        $file->clearStatCache();

        $this->assertSame(3, $file->getSize());
        $this->assertSame(3, count($file));

        $file->append('bar');
        $file->clearStatCache();

        $this->assertSame(6, $file->getSize());
        $this->assertSame(6, count($file));
    }

    /**
     * @expectedException RuntimeException
     */
    public function testSetOwnerId() {
        $file = new TempFile();
        $file->setOwnerId(0);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testSetGroupId() {
        $file = new TempFile();
        $file->setGroupId(0);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testSetGroupName() {
        $file = new TempFile();
        $file->setGroupName('nobody');
    }

}
