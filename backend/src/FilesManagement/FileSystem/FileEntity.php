<?php
/** @noinspection PhpUnusedAliasInspection */

declare(strict_types=1);

namespace App\FilesManagement\FileSystem;

use SplFileInfo;

use App\FilesManagement\FileEntityInterface;

use Symfony\Component\Validator\Constraints as Assert;

class FileEntity implements FileEntityInterface, \JsonSerializable
{
    /**
     * @var SplFileInfo
     *
     * @Assert\NotNull(message="Please, insert the file")
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
            'size' => $this->getSize(),
            'mime' => $this->getMimeType()
        ];
    }

    /**
     * File size
     *
     * @return int
     */
    public function getSize()
    {
        return $this->file->getSize();
    }

    /**
     * File mime type
     *
     * @return string
     */
    public function getMimeType(): string
    {
        return mime_content_type($this->file->getRealPath());
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return $this->getMetadata();
    }
}
