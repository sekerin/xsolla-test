<?php

namespace App\Tests\Controller;

use org\bovigo\vfs\vfsStreamDirectory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\FilesManagement\FileSystem\CreateFSTrait;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FileControllerTest extends WebTestCase
{
    use CreateFSTrait;

    /** @var vfsStreamDirectory $file_system */
    protected $file_system;

    protected function setUp()
    {
        $this->createFS();
    }

    protected function tearDown()
    {
        $dir = self::$container->getParameter('data_dir');

        @unlink($dir . '/file1');
        @unlink($dir . '/existFile');
    }

    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/files/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(),
            'GET / must be accessible');

        $this->assertInstanceOf(JsonResponse::class, $client->getResponse(),
            'Index action must return json');
    }

    public function testDownload()
    {
        $client = static::createClient();
        $client->request('GET', '/files/filename');

        $this->assertEquals(404, $client->getResponse()->getStatusCode(),
            'GET /filename did not exist');
    }

    public function testSave()
    {
        $client = static::createClient();

        $file = new UploadedFile(
            $this->file_system->url() . '/directory/file1',
            'file1',
            'text/plain'
        );

        $client->request('POST', '/files/', [], ['file' => $file]);

        $success = [
            'item' => [
                'name' => 'file1',
                'path' => '/file1',
                'size' => 13,
                'mime' => 'text/plain'
            ]
        ];

        $this->assertEquals($success, json_decode($client->getResponse()->getContent(), true),
            'Create file');

        $this->expectException(BadRequestHttpException::class);
        $client->request('POST', '/files/');
    }

    public function testDelete()
    {
        $dir = self::$container->getParameter('data_dir');
        touch($dir . '/existFile');

        $client = static::createClient();
        $client->request('DELETE', '/files/notExistFile');

        $this->assertEquals(404, $client->getResponse()->getStatusCode(),
            'DELETE /filename did not exist');

        $client->request('DELETE', '/files/existFile');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(),
            'DELETE /filename did not exist');

        $success = ['item' => ['name' => 'existFile']];

        $this->assertEquals($success, json_decode($client->getResponse()->getContent(), true));
    }
}
