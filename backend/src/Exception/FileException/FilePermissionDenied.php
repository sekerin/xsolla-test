<?php

declare(strict_types=1);

namespace App\Exception\FileException;

class FilePermissionDenied extends FileException
{
    protected $code = 403;
}
