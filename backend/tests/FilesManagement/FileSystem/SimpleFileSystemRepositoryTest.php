<?php

namespace App\Tests\FilesManagement\FileSystem;

use App\Exception\FileException\FileNotFound;
use PHPUnit\Framework\TestCase;

use Prophecy\Prophecy\ObjectProphecy;

use org\bovigo\vfs\vfsStreamDirectory;

use App\FilesManagement\FileEntityInterface;
use App\FilesManagement\FileSystem\SimpleFileSystemRepository;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;

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
     * @throws \App\Exception\FileException\FileNotFound
     */
    public function testDelete()
    {
        $this->assertTrue($this->repository->delete('file1'),
            'Must can delete file1');

        $this->expectException(FileNotFound::class);

        $this->repository->delete('file1');
    }
}
