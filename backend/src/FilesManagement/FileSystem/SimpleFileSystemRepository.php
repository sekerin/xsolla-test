<?php

declare(strict_types=1);

namespace App\FilesManagement\FileSystem;

use IteratorIterator;

use App\FilesManagement\FileEntityInterface;
use App\FilesManagement\FileRepositoryInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;

class SimpleFileSystemRepository implements FileRepositoryInterface
{
    /** @var string data directory */
    protected $dataDir;

    /** @var FileEntityInterface */
    protected $fileEntity;

    /**
     * FilesRepository constructor.
     * Get from ContainerInterface param 'data_dir'
     *
     * @param ContainerInterface $container
     * @param FileEntityInterface $fileEntity
     */
    public function __construct(ContainerInterface $container, FileEntityInterface $fileEntity)
    {
        if (!$container->hasParameter('data_dir')) {
            throw new ParameterNotFoundException('data_dir', null, null, null, [],
                sprintf('Missing data_dir param for %s', SimpleFileSystemRepository::class)
            );
        }

        $this->fileEntity = $fileEntity;

        $this->dataDir = $container->getParameter('data_dir');
    }


    /**
     * Get files and return ArrayIterator.
     * In iterator
     *
     * @return IteratorIterator
     */
    public function getFilesList(): IteratorIterator
    {

    }

    /**
     * Get file Entity by id
     *
     * @param string $id
     * @return FileEntityInterface
     */
    public function getFileByName(string $id): FileEntityInterface
    {
        // TODO: Implement getFileByName() method.
    }

    /**
     * Download file by id
     *
     * @param string $id
     */
    public function download(string $id): void
    {
        // TODO: Implement download() method.
    }

    /**
     * Create specific file
     *
     * @param FileEntityInterface $fileEntity
     * @return FileEntityInterface
     */
    public function create(FileEntityInterface $fileEntity): FileEntityInterface
    {
        // TODO: Implement create() method.
    }

    /**
     * Replace specific file
     *
     * @param string $name
     * @param FileEntityInterface $fileEntity
     * @return FileEntityInterface
     */
    public function replace(string $name, FileEntityInterface $fileEntity): FileEntityInterface
    {
        // TODO: Implement replace() method.
    }

    /**
     * Remove file by specific ID
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        // TODO: Implement delete() method.
    }
}
