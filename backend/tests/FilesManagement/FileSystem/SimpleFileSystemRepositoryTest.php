<?php

namespace App\Tests\FilesManagement\FileSystem;

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
        $repo = new SimpleFileSystemRepository($this->container->reveal(), $this->entity->reveal());

        $files = $repo->getFilesList();

        $this->assertInstanceOf(\IteratorIterator::class, $files,
            'SimpleFileSystemRepository::getFilesList must return Iterator');

        $this->assertCount(2, $files,
            'Number of files in vFS - 2');
    }
}
