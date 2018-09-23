<?php

declare(strict_types=1);

namespace App\FilesManagement;

use IteratorIterator;

interface FileRepositoryInterface
{
    /**
     * Get files and return ArrayIterator.
     * In iterator
     *
     * @return IteratorIterator
     */
    public function getFilesList(): IteratorIterator;

    /**
     * Get file Entity by id
     *
     * @param string $id
     * @return FileEntityInterface
     */
    public function getFileByName(string $id): FileEntityInterface;

    /**
     * Download file by id
     *
     * @param string $id
     */
    public function download(string $id): void;

    /**
     * Create specific file
     *
     * @param FileEntityInterface $fileEntity
     * @return FileEntityInterface
     */
    public function create(FileEntityInterface $fileEntity): FileEntityInterface;

    /**
     * Replace specific file
     *
     * @param string $name
     * @param FileEntityInterface $fileEntity
     * @return FileEntityInterface
     */
    public function replace(string $name, FileEntityInterface $fileEntity): FileEntityInterface;

    /**
     * Remove file by specific ID
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool;
}
