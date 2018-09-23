<?php

declare(strict_types=1);

namespace App\FilesManagement\FileSystem;

use SplFileInfo;

use App\FilesManagement\FileEntityInterface;

class FileEntity implements FileEntityInterface, \JsonSerializable
{
    /**
     * @var SplFileInfo
     */
    protected $file;

    /**
     * @return SplFileInfo
     */
    public function getFile(): SplFileInfo
    {
        return $this->file;
    }

    /**
     * @param SplFileInfo $file
     */
    public function setFile(SplFileInfo $file): void
    {
        $this->file = $file;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->file->getBasename();
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return "/{$this->file->getBasename()}";
    }

    /**
     * Return metadata of object
     *
     * @return array
     */
    public function getMetadata(): array
    {
        return [
            'name' => $this->getId(),
            'path' => $this->getPath(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return $this->getMetadata();
    }
}