<?php

namespace Audsur\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ImportControllerTest extends WebTestCase
{
    public function testUpload()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/upload');
    }

    public function testImport()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/upload/import');
    }

}
