<?php

/**
 * @author Andreas Treichel <gmblar+github@gmail.com>
 */

namespace Blar\Filesystem;

use PHPUnit_Framework_TestCase as TestCase;

class BufferedTempFileTest extends TestCase {

    public function testGetMaxMemory() {
        $temp = new BufferedTempFile(1337);
        $this->assertSame(1337, $temp->getMaxMemory());
    }

    public function testGetPath() {
        $temp = new BufferedTempFile(1337);
        $this->assertSame('php://temp/maxmemory:1337', $temp->getPath());
    }



}
