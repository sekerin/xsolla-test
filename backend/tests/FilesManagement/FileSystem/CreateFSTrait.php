<?php

namespace App\Tests\FilesManagement\FileSystem;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

/**
 * Trait CreateFSTrait make virtual file system for tests
 *
 * @package App\Tests\FilesManagement\FileSystem
 *
 * @property vfsStreamDirectory $file_system
 */
trait CreateFSTrait
{
    public function createFS()
    {
        $directory = [
            'directory' => [
                'file1' => 'file1 content',
                'file2' => 'file2 content'
            ]
        ];

        $this->file_system = vfsStream::setup('root', 444, $directory);
    }
}
