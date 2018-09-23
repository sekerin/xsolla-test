<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use Symfony\Component\HttpFoundation\JsonResponse;

class FileControllerTest extends WebTestCase
{
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
        $client->request('POST', '/files/');

        $this->assertEquals(400, $client->getResponse()->getStatusCode(),
            'POST / must fail');

        $client->request('POST', '/files/filename');

        $this->assertEquals(400, $client->getResponse()->getStatusCode(),
            'POST /filename must fail');
    }

    public function testDelete()
    {
        $client = static::createClient();
        $client->request('DELETE', '/files/filename');

        $this->assertEquals(404, $client->getResponse()->getStatusCode(),
            'DELETE /filename did not exist');
    }
}
