<?php

namespace App\Tests\FilesManagement\FileSystem;

use PHPUnit\Framework\TestCase;

use Prophecy\Prophecy\ObjectProphecy;

use org\bovigo\vfs\vfsStreamDirectory;

use App\FilesManagement\FileEntityInterface;
use App\FilesManagement\FileSystem\SimpleFileSystemRepository;

use App\Exception\FileException\FileNotFound;
use App\Exception\FileException\FileAlreadyExists;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class SimpleFileSystemRepositoryTest extends TestCase
{
    use CreateFSTrait;

    /** @var SimpleFileSystemRepository */
    protected $repository;

    /** @var ContainerInterface|ObjectProphecy $container */
    protected $container;

    /** @var FileEntityInterface|ObjectProphecy $entity */
    protected $entity;

    /** @var vfsStreamDirectory $file_system */
    protected $file_system;

    protected $dataDirectory = 'directory';

    protected function setUp()
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->entity = $this->prophesize(FileEntityInterface::class);

        $this->createFS();

        $this->container->hasParameter('data_dir')->willReturn(true);

        $this->container->getParameter('data_dir')->willReturn($this->file_system->url() . '/' . $this->dataDirectory);

        $this->repository = new SimpleFileSystemRepository($this->container->reveal(), $this->entity->reveal());
    }

    public function testConstructor()
    {
        $repo = new SimpleFileSystemRepository($this->container->reveal(), $this->entity->reveal());

        $this->assertInstanceOf(SimpleFileSystemRepository::class, $repo,
            'Must construct SimpleFileSystemRepository');

        $this->container->hasParameter('data_dir')->willReturn(false);

        $this->expectException(ParameterNotFoundException::class);
        new SimpleFileSystemRepository($this->container->reveal(), $this->entity->reveal());
    }

    public function testGetFilesList()
    {
        $files = $this->repository->getFilesList();

        $this->assertInstanceOf(\IteratorIterator::class, $files,
            'SimpleFileSystemRepository::getFilesList must return Iterator');

        $this->assertCount(2, $files,
            'Number of files in vFS - 2');
    }

    /**
     * @throws FileNotFound
     */
    public function testDelete()
    {
        $this->assertTrue($this->repository->delete('file1'),
            'Must can delete file1');

        $this->expectException(FileNotFound::class);

        $this->repository->delete('file1');
    }

    /**
     * @throws FileNotFound
     */
    public function testDownload()
    {
        $this->expectException(FileNotFound::class);

        $this->repository->download('fileNotFound');
    }

    /**
     * @throws FileAlreadyExists
     */
    public function testCreate()
    {
        /** @var FileEntityInterface|ObjectProphecy $fileEntity */
        $fileEntity = $this->prophesize(FileEntityInterface::class);

        $file = new UploadedFile(
            $this->file_system->url() . '/' . $this->dataDirectory . '/' . 'file1',
            'file1',
            'image/jpeg',
            null
        );

        $fileEntity->getFile()->willReturn($file);
        $fileEntity->getId()->willReturn('file1');
        $fileEntity->getPath()->willReturn($this->file_system->url() . '/' . $this->dataDirectory . '/' . 'file1');

        $this->expectException(FileAlreadyExists::class);
        $this->repository->create($fileEntity->reveal());
    }

    /**
     * @throws FileAlreadyExists|FileNotFound
     */
    public function testReplaceFileAlreadyExists()
    {
        /** @var FileEntityInterface|ObjectProphecy $fileEntity */
        $fileEntity = $this->prophesize(FileEntityInterface::class);

        $file = new UploadedFile(
            $this->file_system->url() . '/' . $this->dataDirectory . '/' . 'file1',
            'file2',
            'image/jpeg',
            null
        );

        $fileEntity->getFile()->willReturn($file);
        $fileEntity->getId()->willReturn('file1');
        $fileEntity->getPath()->willReturn($this->file_system->url() . '/' . $this->dataDirectory . '/' . 'file1');

        $this->expectException(FileAlreadyExists::class);
        $this->repository->replace('file3', $fileEntity->reveal());
    }
}
