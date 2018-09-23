<?php

declare(strict_types=1);

namespace App\FilesManagement;

use SplFileInfo;

interface FileEntityInterface
{
    /**
     * @return SplFileInfo|null
     */
    public function getFile(): ?SplFileInfo;

    /**
     * @param SplFileInfo $file
     */
    public function setFile(SplFileInfo $file): void;

    /**
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * @return string|null
     */
    public function getPath(): ?string;

    /**
     * Return metadata of object
     *
     * @return array|null
     */
    public function getMetadata(): ?array;
}
