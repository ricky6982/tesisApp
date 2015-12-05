<?php

namespace Tesis\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ServicioControllerTest extends WebTestCase
{
    public function testPutservicio()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/putServicio');
    }

}
