<?php

declare(strict_types=1);

namespace App\FilesManagement\FileSystem;

use App\Exception\FileException\FileAlreadyExists;
use App\Exception\FileException\FileNotFound;
use IteratorIterator;

use App\FilesManagement\FileEntityInterface;
use App\FilesManagement\FileRepositoryInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
     *
     * @throws FileNotFound
     */
    public function getFileByName(string $id): FileEntityInterface
    {
        $finder = Finder::create();

        $files = $finder->files()->name($id)->in($this->dataDir)->getIterator();
        $files->rewind();

        if (!$files->valid()) {
            throw new FileNotFound(sprintf('File %s not found', $id));
        }

        $entity = $this->fileEntity;
        $entity->setFile($files->current());

        return $entity;
    }

    /**
     * Download file by id
     *
     * @param string $id
     *
     * @throws FileNotFound
     */
    public function download(string $id): void
    {
        $path = "{$this->dataDir}/{$id}";

        if (!$this->fileExists($path)) {
            throw new FileNotFound(sprintf('File %s not found', $id));
        }

        header('Content-Type: ' . mime_content_type($path));
        header('Content-Disposition: attachment; filename="' . $id . '"');
        header('Content-Length: ' . filesize($path));

        readfile($path);
        exit;
    }

    /**
     * Create specific file
     *
     * @param FileEntityInterface $fileEntity
     * @return FileEntityInterface
     *
     * @throws FileAlreadyExists
     */
    public function create(FileEntityInterface $fileEntity): FileEntityInterface
    {
        if ($this->checkFileDestinationExists($fileEntity)) {
            throw new FileAlreadyExists(sprintf('File already exist'));
        }

        $file = $fileEntity->getFile();

        if ($file instanceof UploadedFile) {
            $fileEntity->setFile($this->saveUpload($file, $this->dataDir, $file->getClientOriginalName()));
        }

        return $fileEntity;
    }

    /**
     * Replace specific file
     *
     * @param string $name
     * @param FileEntityInterface $fileEntity
     * @return FileEntityInterface
     *
     * @throws FileAlreadyExists
     */
    public function replace(string $name, FileEntityInterface $fileEntity): FileEntityInterface
    {
        if (!$this->fileExists("{$this->dataDir}/{$name}")) {
            throw new FileAlreadyExists(sprintf('File already exist'));
        }

        $file = $fileEntity->getFile();

        if ($file instanceof UploadedFile) {
            $fileEntity->setFile($this->saveUpload($file, $this->dataDir, $name));
        }

        return $fileEntity;
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
     * Prepare destination dir params (for UploadFiles) and call fileExists
     *
     * @param FileEntityInterface $fileEntity
     * @return bool
     */
    protected function checkFileDestinationExists(FileEntityInterface $fileEntity): bool
    {
        $file = $fileEntity->getFile();
        $path = $file->getPath();
        $fileName = $file->getFilename();

        if ($file instanceof UploadedFile) {
            $path = $this->dataDir;
            $fileName = $file->getClientOriginalName();
        }

        return $this->fileExists("{$path}/{$fileName}");
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
     * Upload file
     *
     * @param UploadedFile $uploadedFile
     * @param $path
     * @param $name
     * @return File
     */
    protected function saveUpload(UploadedFile $uploadedFile, $path, $name): File
    {
        return $uploadedFile->move($path, $name);
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
