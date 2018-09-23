<?php

namespace App\Tests\FilesManagement\FileSystem;

use PHPUnit\Framework\TestCase;

use App\FilesManagement\FileEntityInterface;
use App\FilesManagement\FileSystem\SimpleFileSystemRepository;

use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;

class SimpleFileSystemRepositoryTest extends TestCase
{
    /** @var ContainerInterface|ObjectProphecy $container */
    protected $container;

    /** @var FileEntityInterface|ObjectProphecy $entity */
    protected $entity;

    protected function setUp()
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->entity = $this->prophesize(FileEntityInterface::class);
    }

    public function testConstructor()
    {
        $this->container->hasParameter('data_dir')->willReturn(true);
        $this->container->getParameter('data_dir')->willReturn('directory');

        $repo = new SimpleFileSystemRepository($this->container->reveal(), $this->entity->reveal());

        $this->assertInstanceOf(SimpleFileSystemRepository::class, $repo,
            'Must construct SimpleFileSystemRepository');

        $this->container->hasParameter('data_dir')->willReturn(false);

        $this->expectException(ParameterNotFoundException::class);
        new SimpleFileSystemRepository($this->container->reveal(), $this->entity->reveal());
    }
}
