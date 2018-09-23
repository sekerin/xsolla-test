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

        $this->assertEquals(200, $client->getResponse()->getStatusCode(),
            'GET /filename must be accessible');
    }

    public function testSave()
    {
        $client = static::createClient();
        $client->request('POST', '/files/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(),
            'POST / must be accessible');

        $client->request('POST', '/files/filename');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(),
            'POST /filename must be accessible');
    }

    public function testDelete()
    {
        $client = static::createClient();
        $client->request('DELETE', '/files/filename');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(),
            'DELETE /filename must be accessible');
    }
}
