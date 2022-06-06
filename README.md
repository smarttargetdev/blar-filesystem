[![License](https://poser.pugx.org/blar/filesystem/license)](https://packagist.org/packages/blar/filesystem)
[![Latest Stable Version](https://poser.pugx.org/blar/filesystem/v/stable)](https://packagist.org/packages/blar/filesystem)
[![Build Status](https://travis-ci.org/blar/filesystem.svg?branch=master)](https://travis-ci.org/blar/filesystem)
[![Coverage Status](https://coveralls.io/repos/blar/filesystem/badge.svg?branch=master&service=github)](https://coveralls.io/github/blar/filesystem?branch=master)
[![Dependency Status](https://gemnasium.com/blar/filesystem.svg)](https://gemnasium.com/blar/filesystem)
[![Flattr](https://button.flattr.com/flattr-badge-large.png)](https://flattr.com/submit/auto?user_id=Blar&url=https%3A%2F%2Fgithub.com%2Fblar%2Ffilesystem)

# blar/filesystem

Filesystem functions for PHP.

## Examples

### File exists

    $file = new File('/etc/passwd');
    if($file->exists()) {
        echo 'File exists';
    }

### Readable

    $file = new File('/etc/passwd');
    if($file->isReadable()) {
        echo 'File is readable';
    }

### Writable

    $file = new File('/etc/passwd');
    if($file->isWritable()) {
        echo 'File is writable';
    }

### Set content

    $file = new File('.htaccess');
    $file->setContent("Deny from all\n");

### Get content

    $file = new File('/etc/passwd');
    var_dump($file->getContent());

### Append content

    $file = new File('test.log');
    $file->append('Append some content at the end of the file');

### Slice

    $file = new File('test.mp3');
    if($file->slice(-128, 3) == 'TAG') {
        echo 'File has an ID3v1 tag';
    }

### Copy file

    $file = new File('foo.txt');
    $file->copy('bar.txt');

### Rename file

    $file = new File('foo.txt');
    $file->rename('bar.txt');

### Create a Symlink

    $file = new File('foo.txt');
    $file->link('bar.txt');

### Set permissions

    $file = new File('secret.txt');
    $file->setPermissions(File::PERMISSION_OWNER_ALL | File::PERMISSION_GROUP_NONE | File::PERMISSION_OTHER_NONE);
    // same as $file->setPermissions(0700);

### Check permissions

    $file = new File('secret.txt');
    if($file->checkPermissions(File::PERMISSION_OTHER_READ)) {
        echo 'File is readable for any user on the system';
    }

### Is uploaded file

    $file = new File('/etc/passwd');
    var_dump($file->isUploaded());


## Installing

### Dependencies

[View Dependencies of blar/filesystem on gemnasium](https://gemnasium.com/blar/filesystem)

### Installing with Composer

    $ composer require blar/filesystem

### Installing per Git

    $ git clone https://github.com/blar/filesystem.git
"# blarfilesystem" 
