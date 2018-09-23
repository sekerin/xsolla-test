<?php

declare(strict_types=1);

namespace App\Exception\FileException;

class FileAlreadyExists extends FileException
{
    protected $code = 409;
}
