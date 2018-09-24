<?php

declare(strict_types=1);

namespace App\FilesManagement\FileSystem;

use App\Exception\FileException\FileAlreadyExists;
use App\Exception\FileException\FileException;
use App\Exception\FileException\FileNotFound;
use App\Exception\FileException\FilePermissionDenied;
use App\FilesManagement\FileEntityInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class StreamFileSystemRepository extends SimpleFileSystemRepository
{
    /**
     * @inheritDoc
     *
     * @throws FileException
     */
    public function download(string $id): void
    {
        $path = "{$this->dataDir}/{$id}";

        if (!$this->fileExists($path)) {
            throw new FileNotFound(sprintf('File %s not found', $id));
        }

        if (!$this->isReadable($path)) {
            throw new FilePermissionDenied('Permission denied');
        }

        header('Content-Type: ' . mime_content_type($path));
        header('Content-Disposition: attachment; filename="' . $id . '"');
        header('Content-Length: ' . filesize($path));

        $file = fopen($path, 'rb');

        if (fpassthru($file) === false) {
            throw new FileException();
        }

        fclose($file);

        exit;
    }

    /**
     * @inheritDoc
     *
     * @throws FilePermissionDenied|FileException
     */
    public function create(FileEntityInterface $fileEntity): FileEntityInterface
    {
        if ($this->checkFileDestinationExists($fileEntity)) {
            throw new FileAlreadyExists(sprintf('File already exist'));
        }

        if (!$this->isWritable("{$this->dataDir}")) {
            throw new FilePermissionDenied('Permission denied');
        }

        $file = $fileEntity->getFile();

        if ($file instanceof UploadedFile) {
            $fileEntity->setFile($this->saveUpload($file, $this->dataDir, $file->getClientOriginalName()));
        }

        return $fileEntity;
    }

    /**
     * @inheritDoc
     *
     * @param string $name
     * @param FileEntityInterface $fileEntity
     * @return FileEntityInterface
     *
     * @throws FileException|FileNotFound|FilePermissionDenied
     */
    public function replace(string $name, FileEntityInterface $fileEntity): FileEntityInterface
    {
        if (!$this->checkFileDestinationExists($fileEntity)) {
            throw new FileNotFound(sprintf('File not found'));
        }

        if (!$this->isWritable("{$this->dataDir}")) {
            throw new FilePermissionDenied('Permission denied');
        }

        $file = $fileEntity->getFile();

        if ($file instanceof UploadedFile) {
            $fileEntity->setFile($this->saveUpload($file, $this->dataDir, $name));
        }

        return $fileEntity;
    }

    /**
     * @inheritdoc
     *
     * @param UploadedFile $uploadedFile
     * @param $path
     * @param $name
     * @return File
     * @throws FileException
     */
    protected function saveUpload(UploadedFile $uploadedFile, $path, $name): File
    {
        $absPath = "{$path}/{$name}";
        $inStream = fopen($uploadedFile->getRealPath(), 'rb');
        $outStream = fopen($absPath, 'wb');

        if ($inStream === false || $outStream === false || stream_copy_to_stream($inStream, $outStream) === false) {
            throw new FileException();
        }

        $this->unlink($uploadedFile->getRealPath());

        return new File($absPath);
    }

    /**
     * Check cat read file
     *
     * @param string $path
     * @return bool
     */
    protected function isReadable(string $path)
    {
        return is_readable($path);
    }

    /**
     * Check can write to file or directory
     *
     * @param $path
     * @return bool
     */
    protected function isWritable($path)
    {
        return is_writable($path);
    }
}
