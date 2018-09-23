<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FileControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(),
            'GET / must be accessible');
    }

    public function testDownload()
    {
        $client = static::createClient();
        $client->request('GET', '/filename');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(),
            'GET /filename must be accessible');
    }

    public function testSave()
    {
        $client = static::createClient();
        $client->request('POST', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(),
            'POST / must be accessible');

        $client->request('POST', '/filename');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(),
            'POST /filename must be accessible');
    }

    public function testDelete()
    {
        $client = static::createClient();
        $client->request('DELETE', '/filename');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(),
            'DELETE /filename must be accessible');
    }
}
