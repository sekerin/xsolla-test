<?php

declare(strict_types=1);

namespace App\FilesManagement\FileSystem;

use App\Exception\FileException\FileNotFound;
use IteratorIterator;

use App\FilesManagement\FileEntityInterface;
use App\FilesManagement\FileRepositoryInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\Finder\Finder;

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
     * Find files in directory and return iterator
     *
     * @return IteratorIterator
     */
    public function getFilesList(): IteratorIterator
    {
        return new \IteratorIterator($this->getFilesTraversable());
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
     *
     * @throws FileNotFound
     */
    public function delete(string $id): bool
    {
        $path = "{$this->dataDir}/{$id}";

        if (!$this->fileExists($path)) {
            throw new FileNotFound(sprintf('File %s not found', $id));
        }

        return $this->unlink($path);
    }

    /**
     * Get files and return Traversable.
     * In iterator
     *
     * @return \Traversable
     */
    protected function getFilesTraversable(): \Traversable
    {
        $finder = Finder::create();

        foreach ($finder->files()->sortByName()->in($this->dataDir) as $file) {
            $entity = $this->fileEntity;
            $entity->setFile($file);
            yield $entity;
        }
    }

    /**
     * Check is file exists
     *
     * @param string $path
     * @return bool
     */
    protected function fileExists(string $path): bool
    {
        return file_exists($path);
    }

    /**
     * unlink file by absolute path
     *
     * @param string $path
     * @return bool
     */
    protected function unlink(string $path)
    {
        return unlink($path);
    }
}
